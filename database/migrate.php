<?php

require_once __DIR__ . '/../autoload.php';

use App\Core\Database;

echo "Running Migrations...\n";

try {
    $db = Database::getInstance()->getConnection();
    $sql = file_get_contents(__DIR__ . '/schema.sql');

    // Split by semicolons to execute multiple statements? 
    // PDO might handle it if emulate prepares is on, but strictly safer to split or just run exec.
    // For simplicity with this specific file structure:
    $db->exec($sql);

    echo "Migration completed successfully.\n";
} catch (Exception $e) {
    echo "Migration Failed: " . $e->getMessage() . "\n";
}
