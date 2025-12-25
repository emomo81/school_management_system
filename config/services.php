<?php

return [
    'google' => [
        'client_id' => '122388473050-bl8g58d3kka5heep2upr4vi0tb3k39fc.apps.googleusercontent.com', // Add your Google Client ID here
        'client_secret' => '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', // Add your Google Client Secret here
        'redirect_uri' => 'http://localhost:8000/auth/google/callback',
    ],
    'email' => [
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'username' => 'emomo2003@gmail.com', // Your Gmail address
        'password' => 'xgabbjxrvzeebbef', // Your Gmail App Password
        'from_email' => 'noreply@schoolsys.com',
        'from_name' => 'SchoolSys Notifications'
    ]
];
