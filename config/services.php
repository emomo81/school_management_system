<?php

return [
    'google' => [
        'client_id' => $_ENV['GOOGLE_CLIENT_ID'] ?? '',
        'client_secret' => $_ENV['GOOGLE_CLIENT_SECRET'] ?? '',
        'redirect_uri' => (function () {
            // Localhost (Development)
            if (str_contains($_SERVER['HTTP_HOST'] ?? '', 'localhost') || str_contains($_SERVER['HTTP_HOST'] ?? '', '127.0.0.1')) {
                return 'http://localhost:8000/auth/google/callback';
            }

            // Production (Hardcoded to match your exact domain)
            // Note: If you enable SSL (HTTPS), change 'http' to 'https' below.
            return 'http://schoolproto.xo.je/auth/google/callback';
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
