<?php
$id = 1; // Admin
require_once __DIR__ . '/../src/Core/Database.php'; // Adjust path
// Manually connecting or just checking file system
// We know the file name from previous step: user_1_1766659493.jpeg
$filename = 'user_1_1766659493.jpeg';
$path = __DIR__ . '/uploads/profile_pics/' . $filename;

echo "Checking: $path <br>";

if (file_exists($path)) {
    echo "File EXISTS.<br>";
    echo "Size: " . filesize($path) . "<br>";
    echo "Permissions: " . substr(sprintf('%o', fileperms($path)), -4) . "<br>";
} else {
    echo "File NOT FOUND.<br>";
}

echo "<hr>";
echo "DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "<br>";
echo "HTTP_HOST: " . $_SERVER['HTTP_HOST'] . "<br>";

$config = require __DIR__ . '/../config/database.php';
echo "Config URL: " . $config['app']['url'] . "<br>";
