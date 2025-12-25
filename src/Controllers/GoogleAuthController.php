<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class GoogleAuthController extends Controller
{
    private $config;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../../config/services.php';
    }

    public function googleRedirect()
    {
        $google = $this->config['google'];

        if (empty($google['client_id'])) {
            $this->showErrorPage();
            return;
        }

        $params = [
            'client_id' => $google['client_id'],
            'redirect_uri' => $google['redirect_uri'],
            'response_type' => 'code',
            'scope' => 'email profile',
            'access_type' => 'offline',
            'prompt' => 'select_account'
        ];

        $url = "https://accounts.google.com/o/oauth2/v2/auth?" . http_build_query($params);
        header("Location: $url");
        exit;
    }

    public function callback()
    {
        $google = $this->config['google'];
        $code = $_GET['code'] ?? null;

        if (!$code) {
            $this->redirect('/login');
            return;
        }

        // 1. Exchange code for access token
        $tokenData = $this->postRequest('https://oauth2.googleapis.com/token', [
            'code' => $code,
            'client_id' => $google['client_id'],
            'client_secret' => $google['client_secret'],
            'redirect_uri' => $google['redirect_uri'],
            'grant_type' => 'authorization_code'
        ]);

        if (isset($tokenData['error'])) {
            $_SESSION['flash_error'] = "Google Auth failed: " . $tokenData['error_description'];
            $this->redirect('/login');
            return;
        }

        // 2. Fetch User Profile
        $profile = $this->getRequest('https://www.googleapis.com/oauth2/v3/userinfo', $tokenData['access_token']);

        if (!$profile || !isset($profile['email'])) {
            $_SESSION['flash_error'] = "Could not fetch Google profile.";
            $this->redirect('/login');
            return;
        }

        // 3. Authenticate User
        $userModel = new User();
        $user = $userModel->findByEmail($profile['email']);

        if (!$user) {
            $_SESSION['flash_error'] = "No account found with this email. Please contact Admin.";
            $this->redirect('/login');
            return;
        }

        // 4. Start Session
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ];

        $_SESSION['flash_success'] = "Welcome back, " . $user['name'] . "!";
        $this->redirect('/dashboard');
    }

    private function postRequest($url, $data)
    {
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
                'ignore_errors' => true
            ]
        ];
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return json_decode($result, true);
    }

    private function getRequest($url, $token)
    {
        $options = [
            'http' => [
                'header' => "Authorization: Bearer $token\r\n",
                'method' => 'GET'
            ]
        ];
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return json_decode($result, true);
    }

    private function showErrorPage()
    {
        echo "<h3>Google OAuth 2.0 Integration</h3>";
        echo "<p>To finish this setup, please fill in the credentials in <code>config/services.php</code>:</p>";
        echo "<ul>
                <li>Enable 'Google People API' in Google Cloud Console</li>
                <li>Create OAuth 2.0 Credentials</li>
                <li>Set Redirect URI to: <strong>" . $this->config['google']['redirect_uri'] . "</strong></li>
              </ul>";
        echo "<p><a href='/login'>Return to Login</a></p>";
    }
}
