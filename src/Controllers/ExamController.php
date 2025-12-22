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
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $this->redirect('/dashboard');
        }

        $view = $this->render('exams/create');
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Create Exam']);
    }

    public function store()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $this->redirect('/dashboard');
        }

        try {
            $model = new Exam();
            $model->create([
                'name' => $_POST['name'],
                'date' => $_POST['date']
            ]);
            $_SESSION['flash_success'] = "Exam created successfully.";
            $this->redirect('/exams');
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = "Error: " . $e->getMessage();
            $this->redirect('/exams/create');
        }
    }

    // Marks Input
    public function marks()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
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
        // Easier: fetch map of student_id -> score
        $stmt3 = $db->prepare("SELECT student_id, score FROM marks WHERE exam_id = ? AND subject_id = ?");
        $stmt3->execute([$examId, $subjectId]);
        $marksMap = $stmt3->fetchAll(\PDO::FETCH_KEY_PAIR);

        $view = $this->render('exams/enter_marks', [
            'exam_id' => $examId,
            'class_id' => $classId,
            'subject_id' => $subjectId,
            'students' => $students,
            'marks' => $marksMap
        ]);
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Enter Marks']);
    }

    public function saveMarks()
    {
        $examId = $_POST['exam_id'];
        $subjectId = $_POST['subject_id'];
        $scores = $_POST['scores']; // Array of student_id => score

        $db = Database::getInstance()->getConnection();

        foreach ($scores as $studentId => $score) {
            if ($score === '')
                continue; // Skip empty?

            // Check if exists
            $stmt = $db->prepare("SELECT id FROM marks WHERE exam_id=? AND student_id=? AND subject_id=?");
            $stmt->execute([$examId, $studentId, $subjectId]);
            $exists = $stmt->fetch();

            if ($exists) {
                $upd = $db->prepare("UPDATE marks SET score=? WHERE id=?");
                $upd->execute([$score, $exists['id']]);
            } else {
                $ins = $db->prepare("INSERT INTO marks (exam_id, student_id, subject_id, score) VALUES (?, ?, ?, ?)");
                $ins->execute([$examId, $studentId, $subjectId, $score]);
            }
        }

        $_SESSION['flash_success'] = "Marks updated successfully.";
        $this->redirect('/exams');
    }
}
