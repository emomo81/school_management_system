<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Teacher;

class TeacherController extends Controller
{

    public function index()
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }

        $model = new Teacher();
        $teachers = $model->getAll();

        $view = $this->render('teachers/index', ['teachers' => $teachers]);
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Teachers']);
    }

    public function create()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $this->redirect('/dashboard');
        }

        $view = $this->render('teachers/create');
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Add Teacher']);
    }

    public function store()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $this->redirect('/dashboard');
        }

        $data = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'password' => $_POST['password'],
            'phone' => $_POST['phone'],
            'qualification' => $_POST['qualification']
        ];

        try {
            $model = new Teacher();
            $model->create($data);
            $_SESSION['flash_success'] = "Teacher created successfully.";
            $this->redirect('/teachers');
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = "Error: " . $e->getMessage();
            $this->redirect('/teachers/create');
        }
    }

    public function show()
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/teachers');
        }

        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT t.*, u.name, u.email FROM teachers t JOIN users u ON t.user_id = u.id WHERE t.id = ?");
        $stmt->execute([$id]);
        $teacher = $stmt->fetch();

        if (!$teacher) {
            die("Teacher not found");
        }

        $view = $this->render('teachers/show', ['teacher' => $teacher]);
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Teacher Details']);
    }
}
