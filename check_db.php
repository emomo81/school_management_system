<?php
require_once __DIR__ . '/autoload.php';

use App\Core\Database;

try {
    $db = Database::getInstance()->getConnection();

    // Check subjects table columns
    echo "--- Subjects Table Columns ---\n";
    $stm = $db->query("SHOW COLUMNS FROM subjects");
    $cols = $stm->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cols as $col) {
        echo $col['Field'] . "\n";
    }

    // Check assignments table
    echo "\n--- Assignments Table ---\n";
    $stm = $db->query("SHOW TABLES LIKE 'assignments'");
    if ($stm->rowCount() > 0) {
        echo "Table 'assignments' exists.\n";
        echo "Columns:\n";
        $stm = $db->query("SHOW COLUMNS FROM assignments");
        $cols = $stm->fetchAll(PDO::FETCH_ASSOC);
        foreach ($cols as $col) {
            echo $col['Field'] . "\n";
        }
    } else {
        echo "Table 'assignments' DOES NOT exist.\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
