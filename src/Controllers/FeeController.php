<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Fee;
use App\Models\Student;

class FeeController extends Controller
{
    public function index()
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }

        $feeModel = new Fee();
        $role = $_SESSION['user']['role'];

        if ($role === 'student') {
            // Find student ID from user ID
            $stmt = \App\Core\Database::getInstance()->getConnection()->prepare("SELECT id FROM students WHERE user_id = ?");
            $stmt->execute([$_SESSION['user']['id']]);
            $student = $stmt->fetch();
            $fees = $feeModel->getByStudent($student['id']);
        } else {
            $fees = $feeModel->getAllWithStudents();
        }

        $view = $this->render('fees/index', ['fees' => $fees, 'role' => $role]);
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Fees Management']);
    }

    public function create()
    {
        if ($_SESSION['user']['role'] !== 'admin') {
            $this->redirect('/fees');
        }

        $studentModel = new Student();
        $students = $studentModel->getAll();

        $view = $this->render('fees/create', ['students' => $students]);
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Create Fee Invoice']);
    }

    public function store()
    {
        if ($_SESSION['user']['role'] !== 'admin') {
            $this->redirect('/fees');
        }

        $data = [
            'student_id' => $_POST['student_id'],
            'title' => $_POST['title'],
            'amount' => $_POST['amount'],
            'status' => $_POST['status'],
            'due_date' => $_POST['due_date']
        ];

        $feeModel = new Fee();
        if ($feeModel->create($data)) {
            $_SESSION['flash_success'] = "Fee invoice created successfully.";
            $this->redirect('/fees');
        } else {
            $_SESSION['flash_error'] = "Failed to create invoice.";
            $this->redirect('/fees/create');
        }
    }

    public function pay()
    {
        if ($_SESSION['user']['role'] !== 'admin') {
            $this->redirect('/fees');
        }

        $id = $_GET['id'] ?? null;
        if ($id) {
            $feeModel = new Fee();
            $feeModel->markAsPaid($id);
            $_SESSION['flash_success'] = "Fee marked as paid.";
        }
        $this->redirect('/fees');
    }
}
