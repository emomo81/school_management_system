<?php
require 'autoload.php';
$db = App\Core\Database::getInstance()->getConnection();
$tables = ['users', 'students', 'teachers', 'subjects', 'classes', 'exams'];
foreach ($tables as $table) {
    try {
        $db->exec("ALTER TABLE $table ADD COLUMN IF NOT EXISTS deleted_at TIMESTAMP NULL DEFAULT NULL");
        echo "Soft delete added to $table table.\n";
    } catch (Exception $e) {
        echo "Error on $table: " . $e->getMessage() . "\n";
    }
}
