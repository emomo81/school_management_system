<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Notice;

class NoticeController extends Controller
{
    public function index()
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }

        $noticeModel = new Notice();
        $notices = $noticeModel->getAll();

        $view = $this->render('notices/index', [
            'notices' => $notices,
            'isAdmin' => ($_SESSION['user']['role'] === 'admin' || $_SESSION['user']['role'] === 'teacher')
        ]);
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Noticeboard']);
    }

    public function create()
    {
        if ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'teacher') {
            $this->redirect('/notices');
        }

        $view = $this->render('notices/create');
        echo $this->render('layouts/main', ['content' => $view, 'title' => 'Post New Notice']);
    }

    public function store()
    {
        if ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'teacher') {
            $this->redirect('/notices');
        }

        $title = $_POST['title'];
        $content = $_POST['content'];

        $noticeModel = new Notice();
        if ($noticeModel->create($title, $content)) {
            $_SESSION['flash_success'] = "Notice posted successfully.";
        } else {
            $_SESSION['flash_error'] = "Failed to post notice.";
        }
        $this->redirect('/notices');
    }

    public function delete()
    {
        if ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'teacher') {
            $this->redirect('/notices');
        }

        $id = $_GET['id'] ?? null;
        if ($id) {
            $noticeModel = new Notice();
            $noticeModel->delete($id);
            $_SESSION['flash_success'] = "Notice deleted.";
        }
        $this->redirect('/notices');
    }
}
