<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Assignment;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\SchoolClass;

class AssignmentController extends Controller
{
    public function index()
    {
        if ($_SESSION['user']['role'] !== 'admin') {
            $this->redirect('/dashboard');
        }

        $assignmentModel = new Assignment();
        $assignments = $assignmentModel->getAll();

        $view = $this->render('assignments/index', ['assignments' => $assignments]);
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Subject Assignments']);
    }

    public function create()
    {
        if ($_SESSION['user']['role'] !== 'admin') {
            $this->redirect('/assignments');
        }

        $teacherModel = new Teacher();
        $subjectModel = new Subject();
        $classModel = new SchoolClass();

        $view = $this->render('assignments/create', [
            'teachers' => $teacherModel->getAll(),
            'subjects' => $subjectModel->getAll(),
            'classes' => $classModel->getAll()
        ]);
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'New Assignment']);
    }

    public function store()
    {
        if ($_SESSION['user']['role'] !== 'admin') {
            $this->redirect('/assignments');
        }

        $assignmentModel = new Assignment();
        $assignmentModel->create($_POST['teacher_id'], $_POST['subject_id'], $_POST['class_id']);

        $_SESSION['flash_success'] = "Subject assigned to teacher successfully.";
        $this->redirect('/assignments');
    }

    public function delete()
    {
        if ($_SESSION['user']['role'] !== 'admin') {
            $this->redirect('/assignments');
        }

        $id = $_GET['id'] ?? null;
        if ($id) {
            $assignmentModel = new Assignment();
            $assignmentModel->delete($id);
            $_SESSION['flash_success'] = "Assignment removed.";
        }
        $this->redirect('/assignments');
    }
}
