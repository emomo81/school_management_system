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
        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'teacher')) {
            $this->redirect('/dashboard');
        }

        $deptModel = new \App\Models\Department();
        $departments = $deptModel->getAll();

        $view = $this->render('classes/create', ['departments' => $departments]);
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Add Program']);
    }

    public function store()
    {
        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'teacher')) {
            $this->redirect('/dashboard');
        }

        // Handle Department Selection or Creation
        $departmentId = !empty($_POST['department_id']) ? $_POST['department_id'] : null;
        $newDepartmentName = trim($_POST['new_department'] ?? '');

        if (!empty($newDepartmentName)) {
            // Check if exists
            $db = \App\Core\Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT id FROM departments WHERE name = ?");
            $stmt->execute([$newDepartmentName]);
            $existing = $stmt->fetch();

            if ($existing) {
                $departmentId = $existing['id'];
            } else {
                // Create new
                $deptModel = new \App\Models\Department();
                // Generate a code (e.g. first 3 letters upper)
                $code = strtoupper(substr($newDepartmentName, 0, 3));
                $deptModel->create(['name' => $newDepartmentName, 'code' => $code]);
                // Fetch ID
                $stmt = $db->prepare("SELECT id FROM departments WHERE name = ?");
                $stmt->execute([$newDepartmentName]);
                $newDept = $stmt->fetch();
                $departmentId = $newDept['id'];
            }
        }

        $data = [
            'name' => $_POST['name'],
            'section' => $_POST['section'],
            'department_id' => $departmentId
        ];

        try {
            $model = new SchoolClass();
            $model->create($data);
            $_SESSION['flash_success'] = "Program created successfully.";
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
        // Fetch Class (Program) with Department
        $stmt = $db->prepare("SELECT c.*, d.name as department_name FROM classes c LEFT JOIN departments d ON c.department_id = d.id WHERE c.id = ?");
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
