<?php
require_once __DIR__ . '/../autoload.php';
use App\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    $sql = file_get_contents(__DIR__ . '/add_exams.sql');
    $db->exec($sql);
    echo "Exams and Marks tables created successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
