<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Student;

class StudentController extends Controller
{

    public function index()
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }

        $model = new Student();
        $students = $model->getAll();

        $view = $this->render('students/index', ['students' => $students]);
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Students']);
    }

    public function create()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $this->redirect('/dashboard');
        }

        $classModel = new \App\Models\SchoolClass();
        $classes = $classModel->getAll();

        $view = $this->render('students/create', ['classes' => $classes]);
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Add Student']);
    }

    public function store()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $this->redirect('/dashboard');
        }

        $data = [
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'email' => $_POST['email'],
            'admission_no' => $_POST['admission_no'],
            'dob' => $_POST['dob'],
            'gender' => $_POST['gender'],
            'address' => $_POST['address'],
            'class_id' => !empty($_POST['class_id']) ? $_POST['class_id'] : null
        ];

        try {
            $model = new Student();
            $model->create($data);
            $_SESSION['flash_success'] = "Student created successfully.";
            $this->redirect('/students');
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = "Error: " . $e->getMessage();
            $this->redirect('/students/create');
        }
    }

    public function show()
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/students');
        }

        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT s.*, u.name, u.email, c.name as class_name, c.section FROM students s JOIN users u ON s.user_id = u.id LEFT JOIN classes c ON s.class_id = c.id WHERE s.id = ?");
        $stmt->execute([$id]);
        $student = $stmt->fetch();

        if (!$student) {
            die("Student not found");
        }

        $view = $this->render('students/show', ['student' => $student]);
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Student Details']);
    }
    public function reportCard()
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }

        $studentId = $_GET['id'] ?? null;
        if (!$studentId && $_SESSION['user']['role'] === 'student') {
            $db = \App\Core\Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT id FROM students WHERE user_id = ?");
            $stmt->execute([$_SESSION['user']['id']]);
            $student = $stmt->fetch();
            $studentId = $student['id'] ?? null;
        }

        if (!$studentId) {
            $this->redirect('/dashboard');
        }

        // Fetch Student Info
        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT s.*, u.name, c.name as class_name, c.section FROM students s JOIN users u ON s.user_id = u.id LEFT JOIN classes c ON s.class_id = c.id WHERE s.id = ?");
        $stmt->execute([$studentId]);
        $student = $stmt->fetch();

        if (!$student) {
            die("Student not found");
        }

        // Fetch Marks grouped by Exam
        $stmt2 = $db->prepare("
             SELECT m.*, s.name as subject_name, s.code as subject_code, e.name as exam_name 
             FROM marks m 
             JOIN subjects s ON m.subject_id = s.id 
             JOIN exams e ON m.exam_id = e.id 
             WHERE m.student_id = ?
             ORDER BY e.date DESC, s.name ASC
         ");
        $stmt2->execute([$studentId]);
        $marks = $stmt2->fetchAll();

        // Group by Exam
        $report = [];
        foreach ($marks as $mark) {
            $report[$mark['exam_name']][] = $mark;
        }

        $view = $this->render('students/report_card', ['student' => $student, 'report' => $report]);
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Report Card']);
    }
}
