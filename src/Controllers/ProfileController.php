<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }

        $userModel = new User();
        $user = $userModel->find($_SESSION['user']['id']);

        $content = $this->render('profile/edit', ['user' => $user]);
        echo $this->render('layouts/main', ['content' => $content, 'title' => 'My Profile']);
    }

    public function update()
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }

        $id = $_SESSION['user']['id'];
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (empty($name) || empty($email)) {
            $_SESSION['flash_error'] = 'Name and Email are required';
            $this->redirect('/profile');
        }

        if (!empty($password) && $password !== $confirm_password) {
            $_SESSION['flash_error'] = 'Passwords do not match';
            $this->redirect('/profile');
        }

        $data = [
            'name' => $name,
            'email' => $email
        ];

        if (!empty($password)) {
            $data['password'] = $password;
        }

        // Handle Profile Picture
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/profile_pics/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileInfo = pathinfo($_FILES['profile_pic']['name']);
            $extension = strtolower($fileInfo['extension']);
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (!in_array($extension, $allowedExtensions)) {
                $_SESSION['flash_error'] = 'Invalid file type. Allowed: jpg, jpeg, png, gif';
                $this->redirect('/profile');
            }

            // Generate unique filename
            $filename = 'user_' . $id . '_' . time() . '.' . $extension;
            $destination = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $destination)) {
                // Determine relative path for DB
                $data['profile_pic'] = '/uploads/profile_pics/' . $filename;

                // Optional: Delete old image if exists
                $userModel = new User();
                $currentUser = $userModel->find($id);
                if ($currentUser['profile_pic'] && file_exists(__DIR__ . '/../../public' . $currentUser['profile_pic'])) {
                    unlink(__DIR__ . '/../../public' . $currentUser['profile_pic']);
                }
            } else {
                $_SESSION['flash_error'] = 'Failed to upload image';
                $this->redirect('/profile');
            }
        }

        $userModel = new User();
        if ($userModel->update($id, $data)) {
            // Update session
            $_SESSION['user']['name'] = $name;
            if (isset($data['profile_pic'])) {
                $_SESSION['user']['profile_pic'] = $data['profile_pic'];
            }

            $_SESSION['flash_success'] = 'Profile updated successfully';
        } else {
            $_SESSION['flash_error'] = 'Failed to update profile';
        }

        $this->redirect('/profile');
    }
}
