<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{

    public function loginForm()
    {
        if (isset($_SESSION['user'])) {
            $this->redirect('/dashboard');
        }

        $content = $this->render('auth/login');
        echo $this->render('layouts/main', ['content' => $content, 'title' => 'Login']);
    }

    public function login()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'role' => $user['role']
            ];
            $this->redirect('/dashboard');
        } else {
            $_SESSION['flash_error'] = 'Invalid credentials';
            $this->redirect('/login');
        }
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('/login');
    }
}
