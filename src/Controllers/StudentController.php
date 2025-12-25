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
        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'teacher')) {
            $this->redirect('/dashboard');
        }

        $classModel = new \App\Models\SchoolClass();
        $classes = $classModel->getAll();

        $view = $this->render('students/create', ['classes' => $classes]);
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Add Student']);
    }

    public function store()
    {
        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'teacher')) {
            $this->redirect('/dashboard');
        }

        $data = [
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'email' => $_POST['email'],
            'password' => $_POST['password'],
            'admission_no' => $_POST['admission_no'],
            'dob' => $_POST['dob'],
            'gender' => $_POST['gender'],
            'address' => $_POST['address'],
            'class_id' => !empty($_POST['class_id']) ? $_POST['class_id'] : null
        ];

        try {
            $model = new Student();
            $model->create($data);

            // Send Welcome Email
            $emailService = \App\Services\EmailService::getInstance();
            $subject = "Welcome to SchoolSys";
            $message = "
                <h1>Welcome, {$data['first_name']}!</h1>
                <p>You have been successfully registered to SchoolSys.</p>
                <p><strong>Admission Number:</strong> {$data['admission_no']}</p>
                <p><strong>Initial Password:</strong> {$data['password']}</p>
                <p>Please login and change your password immediately.</p>
            ";
            $emailService->send($data['email'], $subject, $message);

            $_SESSION['flash_success'] = "Student created successfully and welcome email sent.";
            $this->redirect('/students');
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = "Error: " . $e->getMessage();
            $this->redirect('/students/create');
        }

    }

    public function edit()
    {
        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'teacher')) {
            $this->redirect('/dashboard');
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/students');
        }

        // Fetch Student Data
        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT s.*, u.name, u.email FROM students s JOIN users u ON s.user_id = u.id WHERE s.id = ?");
        $stmt->execute([$id]);
        $student = $stmt->fetch();

        if (!$student) {
            die("Student not found");
        }

        $classModel = new \App\Models\SchoolClass();
        $classes = $classModel->getAll();

        $view = $this->render('students/edit', ['student' => $student, 'classes' => $classes]);
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Edit Student']);
    }

    public function update()
    {
        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'teacher')) {
            $this->redirect('/dashboard');
        }

        $data = [
            'id' => $_POST['id'],
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'email' => $_POST['email'],
            'admission_no' => $_POST['admission_no'],
            'password' => !empty($_POST['password']) ? $_POST['password'] : null,
            'dob' => $_POST['dob'],
            'gender' => $_POST['gender'],
            'address' => $_POST['address'],
            'class_id' => !empty($_POST['class_id']) ? $_POST['class_id'] : null
        ];

        try {
            $model = new Student();
            $model->update($data); // We need to implement this
            $_SESSION['flash_success'] = "Student updated successfully.";
            $this->redirect('/students/show?id=' . $data['id']);
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = "Error: " . $e->getMessage();
            $this->redirect('/students/edit?id=' . $data['id']);
        }
    }

    public function delete()
    {
        if ($_SESSION['user']['role'] !== 'admin') {
            $this->redirect('/students');
        }

        $id = $_GET['id'] ?? null;
        if ($id) {
            $db = \App\Core\Database::getInstance()->getConnection();
            $db->beginTransaction();
            try {
                // 1. Get user_id
                $stmt = $db->prepare("SELECT user_id FROM students WHERE id = ?");
                $stmt->execute([$id]);
                $student = $stmt->fetch();

                if ($student) {
                    $now = date('Y-m-d H:i:s');
                    // 2. Soft delete student
                    $stmt = $db->prepare("UPDATE students SET deleted_at = ? WHERE id = ?");
                    $stmt->execute([$now, $id]);
                    // 3. Soft delete user
                    $stmt = $db->prepare("UPDATE users SET deleted_at = ? WHERE id = ?");
                    $stmt->execute([$now, $student['user_id']]);
                }

                $db->commit();
                $_SESSION['flash_success'] = "Student deleted successfully.";
            } catch (\Exception $e) {
                $db->rollBack();
                $_SESSION['flash_error'] = "Failed to delete student.";
            }
        }
        $this->redirect('/students');
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

        // Fetch Marks for this student
        $stmt2 = $db->prepare("
            SELECT m.*, s.name as subject_name, s.code as subject_code, e.name as exam_name 
            FROM marks m 
            JOIN subjects s ON m.subject_id = s.id 
            JOIN exams e ON m.exam_id = e.id 
            WHERE m.student_id = ?
            ORDER BY e.date DESC, s.name ASC
        ");
        $stmt2->execute([$id]);
        $marks = $stmt2->fetchAll();

        $view = $this->render('students/show', ['student' => $student, 'marks' => $marks]);
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Student Details']);
    }
    public function import()
    {
        if ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'teacher') {
            $this->redirect('/students');
        }

        $view = $this->render('students/import');
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Import Students']);
    }

    public function processImport()
    {
        if ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'teacher') {
            $this->redirect('/students');
        }

        if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['flash_error'] = "Please upload a valid CSV file.";
            $this->redirect('/students/import');
        }

        $file = $_FILES['csv_file']['tmp_name'];
        $handle = fopen($file, 'r');

        // Skip header
        fgetcsv($handle);

        $studentModel = new Student();
        $count = 0;
        $errors = 0;

        while (($data = fgetcsv($handle)) !== FALSE) {
            // Expected format: First Name, Last Name, Email, Admission No, DOB, Gender, Address, Class ID, Password
            if (count($data) < 9)
                continue;

            try {
                $studentData = [
                    'first_name' => $data[0],
                    'last_name' => $data[1],
                    'email' => $data[2],
                    'admission_no' => $data[3],
                    'dob' => $data[4],
                    'gender' => $data[5],
                    'address' => $data[6],
                    'class_id' => $data[7],
                    'password' => $data[8]
                ];
                $studentModel->create($studentData);
                $count++;
            } catch (\Exception $e) {
                $errors++;
            }
        }
        fclose($handle);

        $_SESSION['flash_success'] = "Successfully imported $count students. Errors: $errors";
        $this->redirect('/students');
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
