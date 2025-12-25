<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\SchoolClass;

class ClassController extends Controller
{

    public function index()
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }

        $model = new SchoolClass();
        $classes = $model->getAll();

        $view = $this->render('classes/index', ['classes' => $classes]);
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Classes']);
    }

    public function create()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $this->redirect('/dashboard');
        }

        $view = $this->render('classes/create');
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Add Class']);
    }

    public function store()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $this->redirect('/dashboard');
        }

        $data = [
            'name' => $_POST['name'],
            'section' => $_POST['section']
        ];

        try {
            $model = new SchoolClass();
            $model->create($data);
            $_SESSION['flash_success'] = "Class created successfully.";
            $this->redirect('/classes');
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = "Error: " . $e->getMessage();
            $this->redirect('/classes/create');
        }
    }

    public function show()
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/classes');
        }

        $db = \App\Core\Database::getInstance()->getConnection();

        // Fetch Class
        $stmt = $db->prepare("SELECT * FROM classes WHERE id = ?");
        $stmt->execute([$id]);
        $class = $stmt->fetch();

        if (!$class) {
            die("Class not found");
        }

        // Fetch Students in this class
        $stmt2 = $db->prepare("SELECT s.*, u.name, u.email FROM students s JOIN users u ON s.user_id = u.id WHERE s.class_id = ? ORDER BY u.name ASC");
        $stmt2->execute([$id]);
        $students = $stmt2->fetchAll();

        $view = $this->render('classes/show', ['class' => $class, 'students' => $students]);
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Class Details']);
    }
}
