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

    // Forgot Password Flow
    public function forgotPassword()
    {
        $content = $this->render('auth/forgot');
        echo $this->render('layouts/main', ['content' => $content, 'title' => 'Forgot Password']);
    }

    public function sendResetLink()
    {
        $email = $_POST['email'] ?? '';

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user) {
            // Generate Token
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $db = \App\Core\Database::getInstance()->getConnection();
            $stmt = $db->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
            $stmt->execute([$email, $token, $expires]);

            // Send Email
            $link = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/reset-password?token=' . $token;
            $emailService = \App\Services\EmailService::getInstance();
            $emailService->send(
                $email,
                'Reset Your Password',
                "<h2>Password Reset Request</h2>
                <p>Click the link below to reset your password. This link expires in 1 hour.</p>
                <p><a href='$link'>$link</a></p>
                <p>If you did not request this, please ignore this email.</p>"
            );
        }

        // Always show success to prevent email enumeration
        $_SESSION['flash_success'] = 'If an account exists with this email, we have sent a reset link.';
        $this->redirect('/forgot-password');
    }

    public function resetPasswordForm()
    {
        $token = $_GET['token'] ?? null;
        if (!$token) {
            $this->redirect('/login');
        }

        $content = $this->render('auth/reset', ['token' => $token]);
        echo $this->render('layouts/main', ['content' => $content, 'title' => 'Reset Password']);
    }

    public function updatePassword()
    {
        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if ($password !== $confirm) {
            $_SESSION['flash_error'] = 'Passwords do not match.';
            $this->redirect("/reset-password?token=$token");
        }

        $db = \App\Core\Database::getInstance()->getConnection();

        // Find valid token
        $stmt = $db->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW() ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$token]);
        $reset = $stmt->fetch();

        if (!$reset) {
            $_SESSION['flash_error'] = 'Invalid or expired token.';
            $this->redirect('/forgot-password');
        }

        // Update User
        $userModel = new User();
        // Since User::update expects ID, we need to find user ID by email first.
        // Or simpler: direct SQL update here for expediency, but sticking to habits is good.
        // Let's query user by email from the reset record.
        $user = $userModel->findByEmail($reset['email']);

        if ($user) {
            // We can use the update method we added earlier
            $userModel->update($user['id'], ['password' => $password]);

            // Delete used token
            $del = $db->prepare("DELETE FROM password_resets WHERE email = ?");
            $del->execute([$reset['email']]);

            $_SESSION['flash_success'] = 'Password updated successfully. Please login.';
            $this->redirect('/login');
        } else {
            $_SESSION['flash_error'] = 'User not found.';
            $this->redirect('/login');
        }
    }
}
