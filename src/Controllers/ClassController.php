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
}
