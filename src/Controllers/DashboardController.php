<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\SchoolClass;
use App\Models\Exam;

class DashboardController extends Controller
{
    public function index()
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }

        $role = $_SESSION['user']['role'];
        $name = $_SESSION['user']['name'];
        $stats = [];

        if ($role === 'admin') {
            $studentModel = new Student();
            $teacherModel = new Teacher();
            $classModel = new SchoolClass();
            $examModel = new Exam();

            // Stats for Charts
            $stmt = \App\Core\Database::getInstance()->getConnection()->query("
                SELECT c.name, COUNT(s.id) as count 
                FROM classes c 
                LEFT JOIN students s ON c.id = s.class_id 
                WHERE s.deleted_at IS NULL
                GROUP BY c.id
            ");
            $classDistribution = $stmt->fetchAll();

            $stats = [
                'students' => $studentModel->count(),
                'teachers' => $teacherModel->count(),
                'classes' => $classModel->count(),
                'exams' => $examModel->count(),
                'classDistribution' => $classDistribution
            ];
        } elseif ($role === 'student') {
            $db = \App\Core\Database::getInstance()->getConnection();
            $studentModel = new Student();
            $student = $studentModel->getByUserId($_SESSION['user']['id']);

            // Initialize variables for student stats
            $attendancePercent = 0;
            $pendingFees = 0;
            $avgScore = 0;
            $totalSubjects = 0;
            $notifications = [];

            if ($student) {
                // 1. Attendance
                $stmt = $db->prepare("SELECT COUNT(*) as total, SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present FROM attendance WHERE student_id = ?");
                $stmt->execute([$student['id']]);
                $att = $stmt->fetch();
                $attendancePercent = ($att['total'] > 0) ? round(($att['present'] / $att['total']) * 100, 1) : 0;

                // 2. Pending Fees
                $stmt = $db->prepare("SELECT SUM(amount) FROM fees WHERE student_id = ? AND status = 'pending'");
                $stmt->execute([$student['id']]);
                $pendingFees = $stmt->fetchColumn() ?: 0;

                // 3. Average Score
                $stmt = $db->prepare("SELECT AVG(score) FROM marks WHERE student_id = ?");
                $stmt->execute([$student['id']]);
                $avgScore = round($stmt->fetchColumn() ?: 0, 1);

                // 4. Total Subjects (assigned to their class)
                $stmt = $db->prepare("SELECT COUNT(*) FROM subject_assignments WHERE class_id = ?");
                $stmt->execute([$student['class_id']]);
                $totalSubjects = $stmt->fetchColumn();

                $stats = [
                    'attendance' => $attendancePercent,
                    'pending_fees' => $pendingFees,
                    'avg_score' => $avgScore,
                    'subjects' => $totalSubjects,
                    'student_info' => $student,
                    'notifications' => (new \App\Models\Notification())->getForUser($_SESSION['user']['id'])
                ];
            }
        }

        $view = $this->render('dashboard/index', [
            'role' => $role,
            'name' => $name,
            'stats' => $stats
        ]);

        echo $this->render('layouts/main', [
            'content' => $view,
            'title' => 'Dashboard'
        ]);
    }
}
