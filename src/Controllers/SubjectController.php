<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Subject;

class SubjectController extends Controller
{

    public function index()
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }

        $model = new Subject();
        $subjects = $model->getAll();

        $view = $this->render('subjects/index', ['subjects' => $subjects]);
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Subjects']);
    }

    public function create()
    {
        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'teacher')) {
            $this->redirect('/dashboard');
        }

        $deptModel = new \App\Models\Department();
        $departments = $deptModel->getAll();

        $view = $this->render('subjects/create', ['departments' => $departments]);
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Add Subject']);
    }

    public function store()
    {
        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'teacher')) {
            $this->redirect('/dashboard');
        }

        $data = [
            'name' => $_POST['name'],
            'code' => $_POST['code'],
            'department_id' => !empty($_POST['department_id']) ? $_POST['department_id'] : null,
            'credits' => $_POST['credits'] ?? 3,
            'total_marks' => $_POST['total_marks'] ?? 100
        ];

        try {
            $model = new Subject();
            $model->create($data);
            $_SESSION['flash_success'] = "Subject created successfully.";
            $this->redirect('/subjects');
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = "Error: " . $e->getMessage();
            $this->redirect('/subjects/create');
        }
    }

    public function show()
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/subjects');
        }

        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM subjects WHERE id = ?");
        $stmt->execute([$id]);
        $subject = $stmt->fetch();

        if (!$subject) {
            die("Subject not found");
        }

        $view = $this->render('subjects/show', ['subject' => $subject]);
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Subject Details']);
    }
}
