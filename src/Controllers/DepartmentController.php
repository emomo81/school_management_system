<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index()
    {
        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'teacher')) {
            $this->redirect('/dashboard');
        }

        $model = new Department();
        $departments = $model->getAll();

        $view = $this->render('departments/index', ['departments' => $departments]);
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Departments']);
    }

    public function create()
    {
        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'teacher')) {
            $this->redirect('/dashboard');
        }

        $view = $this->render('departments/create');
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Add Department']);
    }

    public function store()
    {
        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'teacher')) {
            $this->redirect('/dashboard');
        }

        try {
            $model = new Department();
            $model->create([
                'name' => $_POST['name'],
                'code' => $_POST['code']
            ]);
            $_SESSION['flash_success'] = "Department created successfully.";
            $this->redirect('/departments');
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = "Error: " . $e->getMessage();
            $this->redirect('/departments/create');
        }
    }

    public function edit()
    {
        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'teacher')) {
            $this->redirect('/dashboard');
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->redirect('/departments');
        }

        $model = new Department();
        $department = $model->find($id);

        $view = $this->render('departments/edit', ['department' => $department]);
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Edit Department']);
    }

    public function update()
    {
        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'teacher')) {
            $this->redirect('/dashboard');
        }

        try {
            $model = new Department();
            $model->update($_POST['id'], [
                'name' => $_POST['name'],
                'code' => $_POST['code']
            ]);
            $_SESSION['flash_success'] = "Department updated successfully.";
            $this->redirect('/departments');
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = "Error: " . $e->getMessage();
            $this->redirect('/departments');
        }
    }

    public function delete()
    {
        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'teacher')) {
            $this->redirect('/dashboard');
        }

        $id = $_GET['id'] ?? null;
        if ($id) {
            $model = new Department();
            $model->delete($id);
            $_SESSION['flash_success'] = "Department deleted successfully.";
        }
        $this->redirect('/departments');
    }
}
