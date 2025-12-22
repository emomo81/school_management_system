<?php

require_once __DIR__ . '/../autoload.php';

session_start();

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\StudentController;

$router = new Router();

// Auth Routes
$router->get('/', [AuthController::class, 'loginForm']);
$router->get('/login', [AuthController::class, 'loginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

// Dashboard
$router->get('/dashboard', function () {
    if (!isset($_SESSION['user'])) {
        header('Location: /school_system/public/login');
        exit;
    }
    // Simple inline controller logic for now
    $role = $_SESSION['user']['role'];
    $name = $_SESSION['user']['name'];

    // In a real app, use DashboardController
    $view = App\Core\View::render('dashboard/index', ['role' => $role, 'name' => $name]);
    echo App\Core\View::render('layouts/main', ['content' => $view, 'title' => 'Dashboard']);
});

// Students
$router->get('/students', [StudentController::class, 'index']);
$router->get('/students/create', [StudentController::class, 'create']);
$router->post('/students/store', [StudentController::class, 'store']);
$router->get('/students/show', [StudentController::class, 'show']);
$router->get('/students/report', [StudentController::class, 'reportCard']);

// Teachers
use App\Controllers\TeacherController;
$router->get('/teachers', [TeacherController::class, 'index']);
$router->get('/teachers/create', [TeacherController::class, 'create']);
$router->post('/teachers/store', [TeacherController::class, 'store']);

// Classes
use App\Controllers\ClassController;
$router->get('/classes', [ClassController::class, 'index']);
$router->get('/classes/create', [ClassController::class, 'create']);
$router->post('/classes/store', [ClassController::class, 'store']);

// Subjects
use App\Controllers\SubjectController;
$router->get('/subjects', [SubjectController::class, 'index']);
$router->get('/subjects/create', [SubjectController::class, 'create']);
$router->post('/subjects/store', [SubjectController::class, 'store']);
// Exams & Marks
use App\Controllers\ExamController;
$router->get('/exams', [ExamController::class, 'index']);
$router->get('/exams/create', [ExamController::class, 'create']);
$router->post('/exams/store', [ExamController::class, 'store']);
$router->get('/exams/marks', [ExamController::class, 'marks']); // Step 1: Select
$router->post('/exams/enter-marks', [ExamController::class, 'enterMarks']); // Step 2: Form
$router->post('/exams/save-marks', [ExamController::class, 'saveMarks']); // Step 3: Save

$router->resolve();
