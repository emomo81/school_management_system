<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Attendance;
use App\Models\SchoolClass;

class AttendanceController extends Controller
{
    public function index()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] === 'student') {
            $this->redirect('/dashboard');
        }

        $classModel = new SchoolClass();
        $classes = $classModel->getAll();

        $view = $this->render('attendance/index', ['classes' => $classes]);
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Attendance Management']);
    }

    public function take()
    {
        $classId = $_GET['class_id'] ?? null;
        $date = $_GET['date'] ?? date('Y-m-d');

        if (!$classId) {
            $this->redirect('/attendance');
        }

        $attendanceModel = new Attendance();
        $students = $attendanceModel->getByClassAndDate($classId, $date);

        $classModel = new SchoolClass();
        // Fetch specific class name (not ideal to use getAll, but for now)
        $allClasses = $classModel->getAll();
        $className = '';
        foreach ($allClasses as $c) {
            if ($c['id'] == $classId)
                $className = $c['name'] . ' ' . $c['section'];
        }

        $view = $this->render('attendance/take', [
            'students' => $students,
            'class_id' => $classId,
            'className' => $className,
            'date' => $date
        ]);
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Mark Attendance']);
    }

    public function store()
    {
        $classId = $_POST['class_id'];
        $date = $_POST['date'];
        $attendance = $_POST['attendance']; // [student_id => ['status' => ..., 'remarks' => ...]]

        try {
            $model = new Attendance();
            $model->save($classId, $date, $attendance);
            $_SESSION['flash_success'] = "Attendance saved successfully for " . $date;
            $this->redirect('/attendance');
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = "Error: " . $e->getMessage();
            $this->redirect('/attendance/take?class_id=' . $classId . '&date=' . $date);
        }
    }

    public function report()
    {
        $classId = $_GET['class_id'] ?? null;
        $month = $_GET['month'] ?? date('Y-m');

        $classModel = new SchoolClass();
        $classes = $classModel->getAll();

        $reportData = [];
        if ($classId) {
            $db = \App\Core\Database::getInstance()->getConnection();
            // Get all students in class
            $stmt = $db->prepare("SELECT s.id, u.name FROM students s JOIN users u ON s.user_id = u.id WHERE s.class_id = ? ORDER BY u.name ASC");
            $stmt->execute([$classId]);
            $students = $stmt->fetchAll();

            // Get attendance for the month
            foreach ($students as $student) {
                $stmt = $db->prepare("
                    SELECT status, COUNT(*) as count 
                    FROM attendance 
                    WHERE student_id = ? AND date LIKE ? 
                    GROUP BY status
                ");
                $stmt->execute([$student['id'], $month . '%']);
                $stats = $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
                $reportData[] = [
                    'name' => $student['name'],
                    'present' => $stats['present'] ?? 0,
                    'absent' => $stats['absent'] ?? 0,
                    'late' => $stats['late'] ?? 0
                ];
            }
        }

        $view = $this->render('attendance/report', [
            'classes' => $classes,
            'reportData' => $reportData,
            'classId' => $classId,
            'month' => $month
        ]);
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Attendance Report']);
    }
}
