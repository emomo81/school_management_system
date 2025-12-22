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
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $this->redirect('/dashboard');
        }

        $view = $this->render('subjects/create');
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Add Subject']);
    }

    public function store()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $this->redirect('/dashboard');
        }

        $data = [
            'name' => $_POST['name'],
            'code' => $_POST['code']
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
}
