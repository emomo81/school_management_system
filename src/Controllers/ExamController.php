<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Core\Database;

class ExamController extends Controller
{

    public function index()
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }

        $model = new Exam();
        $exams = $model->getAll();

        $view = $this->render('exams/index', ['exams' => $exams]);
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Exams']);
    }

    public function create()
    {
        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'teacher')) {
            $this->redirect('/dashboard');
        }

        $deptModel = new \App\Models\Department();
        $classModel = new \App\Models\SchoolClass();

        $view = $this->render('exams/create', [
            'departments' => $deptModel->getAll(),
            'programs' => $classModel->getAll()
        ]);
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Create Exam']);
    }

    public function store()
    {
        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'teacher')) {
            $this->redirect('/dashboard');
        }

        try {
            $departmentId = !empty($_POST['department_id']) ? $_POST['department_id'] : null;
            $programId = !empty($_POST['program_id']) ? $_POST['program_id'] : null;
            $term = !empty($_POST['term']) ? $_POST['term'] : '';
            $date = $_POST['date'];

            // Auto-generate Name
            $nameParts = [];
            if ($term)
                $nameParts[] = $term; // "Year 1 Term 1"

            // If Program is selected, fetch name
            if ($programId) {
                $db = Database::getInstance()->getConnection();
                $stmt = $db->prepare("SELECT name FROM classes WHERE id = ?");
                $stmt->execute([$programId]);
                $prog = $stmt->fetch();
                if ($prog)
                    $nameParts[] = $prog['name'];
            } elseif ($departmentId) {
                $db = Database::getInstance()->getConnection();
                $stmt = $db->prepare("SELECT name FROM departments WHERE id = ?");
                $stmt->execute([$departmentId]);
                $dept = $stmt->fetch();
                if ($dept)
                    $nameParts[] = $dept['name'];
            }

            $nameParts[] = "Exam"; // Suffix
            // e.g. "Year 1 Term 1 Software Engineering Exam"

            $examName = implode(' ', $nameParts);
            if (empty(trim($examName)) || $examName === 'Exam') {
                $examName = "Exam - " . $date;
            }

            $model = new Exam();
            $model->create([
                'name' => $examName,
                'date' => $date,
                'department_id' => $departmentId,
                'program_id' => $programId,
                'term' => $term
            ]);
            $_SESSION['flash_success'] = "Exam created successfully: $examName";
            $this->redirect('/exams');
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = "Error: " . $e->getMessage();
            $this->redirect('/exams/create');
        }
    }

    // Marks Input
    public function marks()
    {
        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'teacher')) {
            $this->redirect('/dashboard');
        }

        $examId = $_GET['id'] ?? null;
        if (!$examId) {
            $this->redirect('/exams');
        }

        // Fetch Class and Subject selection to load students
        $classModel = new SchoolClass();
        $classes = $classModel->getAll();

        $subjectModel = new Subject();
        $subjects = $subjectModel->getAll();

        $view = $this->render('exams/marks_select', [
            'exam_id' => $examId,
            'classes' => $classes,
            'subjects' => $subjects
        ]);
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Enter Marks']);
    }

    public function enterMarks()
    {
        $examId = $_POST['exam_id'];
        $classId = $_POST['class_id'];
        $subjectId = $_POST['subject_id'];

        // Fetch Students in this class
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT students.id as student_id, students.admission_no, users.name 
                              FROM students 
                              JOIN users ON students.user_id = users.id 
                              WHERE class_id = ?");
        $stmt->execute([$classId]);
        $students = $stmt->fetchAll();

        // Fetch existing marks if any
        $stmt2 = $db->prepare("SELECT * FROM marks WHERE exam_id = ? AND subject_id = ?");
        $stmt2->execute([$examId, $subjectId]);
        $existing = $stmt2->fetchAll(\PDO::FETCH_GROUP | \PDO::FETCH_UNIQUE | \PDO::FETCH_ASSOC); // Group by id is not right, assume student_id key

        $marksMap = [];
        foreach ($existing as $m) {
            // Re-map simple key lookup if possible, or just re-query.
        }
        // Easier: fetch map of student_id -> mark row
        $stmt3 = $db->prepare("SELECT student_id, cat1, cat2, exam_marks, score FROM marks WHERE exam_id = ? AND subject_id = ?");
        $stmt3->execute([$examId, $subjectId]);
        $marksMap = $stmt3->fetchAll(\PDO::FETCH_UNIQUE | \PDO::FETCH_ASSOC);

        // Fetch Subject for validation
        $subjectModel = new Subject();
        $subject = $subjectModel->getById($subjectId);
        $totalMarks = $subject['total_marks'] ?? 100;

        $view = $this->render('exams/enter_marks', [
            'exam_id' => $examId,
            'class_id' => $classId,
            'subject_id' => $subjectId,
            'students' => $students,
            'marks' => $marksMap,
            'total_marks' => $totalMarks
        ]);
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Enter Marks']);
    }

    public function saveMarks()
    {
        $examId = $_POST['exam_id'];
        $subjectId = $_POST['subject_id'];
        $marksData = $_POST['marks']; // Array of student_id => ['cat1'=>..., 'cat2'=>..., 'exam'=>...]

        $db = Database::getInstance()->getConnection();

        $notificationModel = new \App\Models\Notification();
        $examModel = new Exam();
        $exam = $examModel->getById($examId);
        $subjectModel = new Subject();
        $subject = $subjectModel->getById($subjectId);

        $totalMarks = $subject['total_marks'] ?? 100;
        $ratio = $totalMarks / 100;
        $maxCat1 = 30 * $ratio;
        $maxCat2 = 30 * $ratio;
        $maxExam = 40 * $ratio;

        foreach ($marksData as $studentId => $components) {
            $cat1 = (int) ($components['cat1'] ?? 0);
            $cat2 = (int) ($components['cat2'] ?? 0);
            $examMark = (int) ($components['exam'] ?? 0);

            // Validation/Clamping
            if ($cat1 > $maxCat1)
                $cat1 = (int) $maxCat1;
            if ($cat2 > $maxCat2)
                $cat2 = (int) $maxCat2;
            if ($examMark > $maxExam)
                $examMark = (int) $maxExam;

            $totalScore = $cat1 + $cat2 + $examMark;

            // Check if exists
            $stmt = $db->prepare("SELECT id FROM marks WHERE exam_id=? AND student_id=? AND subject_id=?");
            $stmt->execute([$examId, $studentId, $subjectId]);
            $exists = $stmt->fetch();

            if ($exists) {
                $upd = $db->prepare("UPDATE marks SET cat1=?, cat2=?, exam_marks=?, score=? WHERE id=?");
                $upd->execute([$cat1, $cat2, $examMark, $totalScore, $exists['id']]);
            } else {
                $ins = $db->prepare("INSERT INTO marks (exam_id, student_id, subject_id, cat1, cat2, exam_marks, score) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $ins->execute([$examId, $studentId, $subjectId, $cat1, $cat2, $examMark, $totalScore]);
            }

            $score = $totalScore; // For notification message

            // Notify Student
            $stmtStudent = $db->prepare("SELECT u.id, u.email, u.name FROM students s JOIN users u ON s.user_id = u.id WHERE s.id = ?");
            $stmtStudent->execute([$studentId]);
            $studentUser = $stmtStudent->fetch();

            if ($studentUser) {
                // 1. Create Internal Notification
                $notificationModel->create(
                    $studentUser['id'],
                    'exam',
                    "New Marks: " . ($subject['name'] ?? 'Subject'),
                    "Your marks for " . ($exam['name'] ?? 'Exam') . " in " . ($subject['name'] ?? 'Subject') . " have been updated to $score."
                );

                // 2. Send Real Email Notification
                $emailService = \App\Services\EmailService::getInstance();
                $subjectText = "New Marks Published: " . ($subject['name'] ?? 'Subject');
                $messageBody = "
                    <h2>Marks Update</h2>
                    <p>Dear {$studentUser['name']},</p>
                    <p>Your marks for <strong>" . ($exam['name'] ?? 'Exam') . "</strong> in <strong>" . ($subject['name'] ?? 'Subject') . "</strong> have been updated.</p>
                    <p style='font-size: 1.2rem;'><strong>Score: $score</strong></p>
                    <p>Please log in to your dashboard to view your full report card.</p>
                ";
                $emailService->send($studentUser['email'], $subjectText, $messageBody);
            }
        }

        $_SESSION['flash_success'] = "Marks updated successfully.";
        $this->redirect('/exams');
    }
}
