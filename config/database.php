<?php

$isLocal = in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']) || str_contains($_SERVER['HTTP_HOST'] ?? '', 'localhost');

return [
    'db' => $isLocal ? [
        'host' => 'localhost',
        'dbname' => 'school_system',
        'user' => 'root',
        'password' => '',
        'charset' => 'utf8mb4'
    ] : [
        'host' => 'sql306.infinityfree.com',
        'dbname' => 'if0_40775840_school_system',
        'user' => 'if0_40775840',
        'password' => 'BkovB7UxygEL',
        'charset' => 'utf8mb4',
        'port' => 3306
    ],
    'app' => [
        'name' => 'School Board',
        'url' => $isLocal
            ? ((str_contains($_SERVER['HTTP_HOST'] ?? '', '8000')) ? '' : '/school_system/public')
            : '', // Root URL for production
        'env' => $isLocal ? 'local' : 'production'
    ]
];
