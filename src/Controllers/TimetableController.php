<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Timetable;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Student;

class TimetableController extends Controller
{
    public function index()
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }

        $timetableModel = new Timetable();
        $classModel = new SchoolClass();
        $userRole = $_SESSION['user']['role'];
        $userId = $_SESSION['user']['id'];

        $selectedClassId = $_GET['class_id'] ?? null;
        $timetable = [];
        $classes = [];

        if ($userRole === 'admin') {
            $classes = $classModel->getAll();
            if ($selectedClassId) {
                $timetable = $timetableModel->getByClass($selectedClassId);
            }
        } elseif ($userRole === 'teacher') {
            // Find teacher ID
            $db = \App\Core\Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT id FROM teachers WHERE user_id = ?");
            $stmt->execute([$userId]);
            $teacher = $stmt->fetch();

            if ($teacher) {
                $timetable = $timetableModel->getByTeacher($teacher['id']);
            }
        } elseif ($userRole === 'student') {
            // Find student class
            $studentModel = new Student();
            $student = $studentModel->getByUserId($userId);
            if ($student && $student['class_id']) {
                $selectedClassId = $student['class_id'];
                $timetable = $timetableModel->getByClass($selectedClassId);
            }
        }

        $view = $this->render('timetables/index', [
            'timetable' => $timetable,
            'classes' => $classes,
            'selectedClassId' => $selectedClassId,
            'role' => $userRole
        ]);
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Timetable']);
    }

    public function create()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $this->redirect('/dashboard');
        }

        $classModel = new SchoolClass();
        $subjectModel = new Subject();
        $teacherModel = new Teacher();

        $view = $this->render('timetables/create', [
            'classes' => $classModel->getAll(),
            'subjects' => $subjectModel->getAll(),
            'teachers' => $teacherModel->getAll()
        ]);
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Add Timetable Slot']);
    }

    public function store()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $this->redirect('/dashboard');
        }

        $data = [
            'class_id' => $_POST['class_id'],
            'subject_id' => $_POST['subject_id'],
            'teacher_id' => $_POST['teacher_id'],
            'day_of_week' => $_POST['day_of_week'],
            'start_time' => $_POST['start_time'],
            'end_time' => $_POST['end_time'],
            'room_number' => $_POST['room_number']
        ];

        $timetableModel = new Timetable();
        if ($timetableModel->create($data)) {
            $_SESSION['flash_success'] = "Timetable slot added successfully.";
            $this->redirect('/timetables?class_id=' . $data['class_id']);
        } else {
            $_SESSION['flash_error'] = "Failed to add slot.";
            $this->redirect('/timetables/create');
        }
    }

    public function delete()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $this->redirect('/dashboard');
        }

        $id = $_GET['id'] ?? null;
        if ($id) {
            $timetableModel = new Timetable();
            $timetableModel->delete($id);
            $_SESSION['flash_success'] = "Slot deleted.";
        }

        // Redirect back (ideally we should track referrer or just go to index)
        $this->redirect('/timetables');
    }
}
