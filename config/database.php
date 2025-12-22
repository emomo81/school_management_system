<?php

return [
    'db' => [
        'host' => 'localhost',
        'dbname' => 'school_system',
        'user' => 'root',
        'password' => '',
        'charset' => 'utf8mb4'
    ],
    'app' => [
        'name' => 'School Board',
        'url' => (isset($_SERVER['HTTP_HOST']) && str_contains($_SERVER['HTTP_HOST'], '8000'))
            ? '' // Root path for built-in server
            : '/school_system/public', // Path for XAMPP
        'env' => 'local'
    ]
];
