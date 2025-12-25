<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\AcademicYear;

class AcademicYearController extends Controller
{
    public function index()
    {
        if (!$this->isAdmin()) {
            $this->redirect('/dashboard');
        }

        $yearModel = new AcademicYear();
        $years = $yearModel->all();
        $activeYear = $yearModel->getActive();

        $content = $this->render('academic_years/index', [
            'years' => $years,
            'activeYear' => $activeYear
        ]);
        echo $this->render('layouts/main', ['content' => $content, 'title' => 'Academic Years']);
    }

    public function create()
    {
        if (!$this->isAdmin()) {
            $this->redirect('/dashboard');
        }

        $content = $this->render('academic_years/create');
        echo $this->render('layouts/main', ['content' => $content, 'title' => 'Add Academic Year']);
    }

    public function store()
    {
        if (!$this->isAdmin())
            return;

        $name = $_POST['name'] ?? '';
        $start_date = $_POST['start_date'] ?? '';
        $end_date = $_POST['end_date'] ?? '';

        if (empty($name) || empty($start_date) || empty($end_date)) {
            $_SESSION['flash_error'] = 'All fields are required';
            $this->redirect('/academic-years/create');
        }

        $yearModel = new AcademicYear();
        if ($yearModel->create(['name' => $name, 'start_date' => $start_date, 'end_date' => $end_date])) {
            $_SESSION['flash_success'] = 'Academic Year created successfully';
            $this->redirect('/academic-years');
        } else {
            $_SESSION['flash_error'] = 'Failed to create academic year';
            $this->redirect('/academic-years/create');
        }
    }

    public function makeActive()
    {
        if (!$this->isAdmin())
            return;

        $id = $_GET['id'] ?? null;
        if ($id) {
            $yearModel = new AcademicYear();
            if ($yearModel->setActive($id)) {
                $_SESSION['flash_success'] = 'Active academic year updated';
            } else {
                $_SESSION['flash_error'] = 'Failed to update active year';
            }
        }
        $this->redirect('/academic-years');
    }

    public function delete()
    {
        if (!$this->isAdmin())
            return;

        $id = $_GET['id'] ?? null;
        if ($id) {
            $yearModel = new AcademicYear();
            // Prevent deleting the active year? Maybe. For now allow it but be careful.
            // Better to check if it's active.
            $year = $yearModel->find($id);
            if ($year['is_active']) {
                $_SESSION['flash_error'] = 'Cannot delete the active academic year. Switch active year first.';
                $this->redirect('/academic-years');
            }

            if ($yearModel->delete($id)) {
                $_SESSION['flash_success'] = 'Academic Year deleted';
            } else {
                $_SESSION['flash_error'] = 'Failed to delete';
            }
        }
        $this->redirect('/academic-years');
    }

    private function isAdmin()
    {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
    }
}
