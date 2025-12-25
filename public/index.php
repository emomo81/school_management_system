<?php


require_once __DIR__ . '/../autoload.php';

// Load Environment Variables
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

session_start();

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\StudentController;
use App\Controllers\GoogleAuthController; // Added for Google Auth

$router = new Router();

// Auth Routes
$router->get('/', [AuthController::class, 'loginForm']);
$router->get('/login', [AuthController::class, 'loginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);
$router->get('/auth/google', [GoogleAuthController::class, 'googleRedirect']);
$router->get('/auth/google/callback', [GoogleAuthController::class, 'callback']);

// Forgot Password
$router->get('/forgot-password', [AuthController::class, 'forgotPassword']);
$router->post('/forgot-password/send', [AuthController::class, 'sendResetLink']);
$router->get('/reset-password', [AuthController::class, 'resetPasswordForm']);
$router->post('/reset-password/update', [AuthController::class, 'updatePassword']);

// Profile Routes
use App\Controllers\ProfileController;
$router->get('/profile', [ProfileController::class, 'index']);
$router->post('/profile/update', [ProfileController::class, 'update']);

// Dashboard
use App\Controllers\DashboardController;
$router->get('/dashboard', [DashboardController::class, 'index']);

// Students
$router->get('/students', [StudentController::class, 'index']);
$router->get('/students/create', [StudentController::class, 'create']);
$router->post('/students/store', [StudentController::class, 'store']);
$router->get('/students/show', [StudentController::class, 'show']);
$router->get('/students/edit', [StudentController::class, 'edit']);
$router->post('/students/update', [StudentController::class, 'update']);
$router->get('/students/delete', [StudentController::class, 'delete']);
$router->get('/students/import', [StudentController::class, 'import']);
$router->post('/students/import-process', [StudentController::class, 'processImport']);
$router->get('/students/report', [StudentController::class, 'reportCard']);

// Teachers
use App\Controllers\TeacherController;
$router->get('/teachers', [TeacherController::class, 'index']);
$router->get('/teachers/create', [TeacherController::class, 'create']);
$router->post('/teachers/store', [TeacherController::class, 'store']);
$router->get('/teachers/show', [TeacherController::class, 'show']);

// Classes
use App\Controllers\ClassController;
$router->get('/classes', [ClassController::class, 'index']);
$router->get('/classes/create', [ClassController::class, 'create']);
$router->post('/classes/store', [ClassController::class, 'store']);
$router->get('/classes/show', [ClassController::class, 'show']);

// Subjects
use App\Controllers\SubjectController;
$router->get('/subjects', [SubjectController::class, 'index']);
$router->get('/subjects/create', [SubjectController::class, 'create']);
$router->post('/subjects/store', [SubjectController::class, 'store']);
$router->get('/subjects/show', [SubjectController::class, 'show']);
// Notices
use App\Controllers\NoticeController;
$router->get('/notices', [NoticeController::class, 'index']);
$router->get('/notices/create', [NoticeController::class, 'create']);
$router->post('/notices/store', [NoticeController::class, 'store']);
$router->get('/notices/delete', [NoticeController::class, 'delete']);

// Subject Assignments
use App\Controllers\AssignmentController;
$router->get('/assignments', [AssignmentController::class, 'index']);
$router->get('/assignments/create', [AssignmentController::class, 'create']);
$router->post('/assignments/store', [AssignmentController::class, 'store']);
$router->get('/assignments/delete', [AssignmentController::class, 'delete']);

// Fees
use App\Controllers\FeeController;
$router->get('/fees', [FeeController::class, 'index']);
$router->get('/fees/create', [FeeController::class, 'create']);
$router->post('/fees/store', [FeeController::class, 'store']);
$router->get('/fees/pay', [FeeController::class, 'pay']);

// Attendance
use App\Controllers\AttendanceController;
$router->get('/attendance', [AttendanceController::class, 'index']);
$router->get('/attendance/take', [AttendanceController::class, 'take']);
$router->get('/attendance/report', [AttendanceController::class, 'report']);
$router->post('/attendance/store', [AttendanceController::class, 'store']);

// Exams & Marks
use App\Controllers\ExamController;
$router->get('/exams', [ExamController::class, 'index']);
$router->get('/exams/create', [ExamController::class, 'create']);
$router->post('/exams/store', [ExamController::class, 'store']);
$router->get('/exams/marks', [ExamController::class, 'marks']); // Step 1: Select
$router->post('/exams/enter-marks', [ExamController::class, 'enterMarks']); // Step 2: Form
$router->post('/exams/save-marks', [ExamController::class, 'saveMarks']); // Step 3: Save

// Academic Years
use App\Controllers\AcademicYearController;
$router->get('/academic-years', [AcademicYearController::class, 'index']);
$router->get('/academic-years/create', [AcademicYearController::class, 'create']);
$router->post('/academic-years/store', [AcademicYearController::class, 'store']);
$router->get('/academic-years/make-active', [AcademicYearController::class, 'makeActive']);
$router->get('/academic-years/delete', [AcademicYearController::class, 'delete']);

$router->resolve();
