<?php
require_once __DIR__ . '/autoload.php';

use App\Core\Database;

try {
    $db = Database::getInstance()->getConnection();

    // Check if academic_year_id exists in students
    $columns = $db->query("SHOW COLUMNS FROM students")->fetchAll(PDO::FETCH_COLUMN);

    if (!in_array('academic_year_id', $columns)) {
        $db->exec("ALTER TABLE students ADD COLUMN academic_year_id INT NULL");
        $db->exec("ALTER TABLE students ADD FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE SET NULL");
        echo "Added academic_year_id to students.\n";
    } else {
        echo "academic_year_id already exists in students.\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
