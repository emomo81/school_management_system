<?php

return [
    'google' => [
        'client_id' => $_ENV['GOOGLE_CLIENT_ID'] ?? '',
        'client_secret' => $_ENV['GOOGLE_CLIENT_SECRET'] ?? '',
        'redirect_uri' => $_ENV['GOOGLE_REDIRECT_URI'] ?? '',
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
