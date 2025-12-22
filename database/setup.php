<?php

$config = require __DIR__ . '/../config/database.php';
$dbConfig = $config['db'];

echo "Attempting to create database '{$dbConfig['dbname']}'...\n";

try {
    // Connect without database name
    $pdo = new PDO(
        "mysql:host={$dbConfig['host']};charset={$dbConfig['charset']}",
        $dbConfig['user'],
        $dbConfig['password']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create Database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbConfig['dbname']}`");
    echo "Database '{$dbConfig['dbname']}' created successfully (or already exists).\n";

    // Switch to database
    $pdo->exec("USE `{$dbConfig['dbname']}`");

    // Run Migrations
    echo "Running Schema Migration...\n";
    $sql = file_get_contents(__DIR__ . '/schema.sql');
    $pdo->exec($sql);
    echo "Tables created successfully.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
