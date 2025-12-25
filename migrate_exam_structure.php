<?php
require_once __DIR__ . '/autoload.php';

use App\Core\Database;

try {
    $db = Database::getInstance()->getConnection();

    $columns = $db->query("SHOW COLUMNS FROM exams")->fetchAll(PDO::FETCH_COLUMN);

    if (!in_array('department_id', $columns)) {
        $db->exec("ALTER TABLE exams ADD COLUMN department_id INT NULL AFTER name");
        $db->exec("ALTER TABLE exams ADD FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL");
        echo "Added department_id to exams.\n";
    }

    if (!in_array('program_id', $columns)) {
        $db->exec("ALTER TABLE exams ADD COLUMN program_id INT NULL AFTER department_id");
        $db->exec("ALTER TABLE exams ADD FOREIGN KEY (program_id) REFERENCES classes(id) ON DELETE SET NULL"); // program_id links to classes table
        echo "Added program_id to exams.\n";
    }

    if (!in_array('term', $columns)) {
        $db->exec("ALTER TABLE exams ADD COLUMN term VARCHAR(50) NULL AFTER program_id");
        echo "Added term to exams.\n";
    }

    echo "Migration complete.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
