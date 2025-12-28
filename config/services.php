<?php

return [
    'google' => [
        'client_id' => $_ENV['GOOGLE_CLIENT_ID'] ?? '',
        'client_secret' => $_ENV['GOOGLE_CLIENT_SECRET'] ?? '',
        'redirect_uri' => (function () {
            if (!empty($_ENV['GOOGLE_REDIRECT_URI']) && !str_contains($_ENV['GOOGLE_REDIRECT_URI'], 'localhost')) {
                return $_ENV['GOOGLE_REDIRECT_URI'];
            }
            // Dynamic generation
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $path = str_contains($host, 'localhost') ? '' : ''; // Add subpath if needed, e.g. '/public' if not handled by htaccess
            // Since we added .htaccess, we don't need /public in the URL for production if root points there
            return $protocol . $host . $path . '/auth/google/callback';
        })(),
    ],
    'email' => [
        'host' => $_ENV['MAIL_HOST'] ?? 'smtp.gmail.com',
        'port' => $_ENV['MAIL_PORT'] ?? 587,
        'username' => $_ENV['MAIL_USERNAME'] ?? '',
        'password' => $_ENV['MAIL_PASSWORD'] ?? '',
        'from_email' => $_ENV['MAIL_FROM_ADDRESS'] ?? 'noreply@schoolsys.com',
        'from_name' => $_ENV['MAIL_FROM_NAME'] ?? 'SchoolSys Notifications'
    ]
];
