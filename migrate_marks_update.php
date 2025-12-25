<?php
require_once __DIR__ . '/autoload.php';

use App\Core\Database;

try {
    $db = Database::getInstance()->getConnection();

    // Update Subjects Table
    // Add department_id, credits, total_marks
    $columns = $db->query("SHOW COLUMNS FROM subjects")->fetchAll(PDO::FETCH_COLUMN);

    if (!in_array('department_id', $columns)) {
        $db->exec("ALTER TABLE subjects ADD COLUMN department_id INT NULL");
        $db->exec("ALTER TABLE subjects ADD FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL");
        echo "Added department_id to subjects.\n";
    }

    if (!in_array('credits', $columns)) {
        $db->exec("ALTER TABLE subjects ADD COLUMN credits INT DEFAULT 3"); // Default 3 credits
        echo "Added credits to subjects.\n";
    }

    if (!in_array('total_marks', $columns)) {
        $db->exec("ALTER TABLE subjects ADD COLUMN total_marks INT DEFAULT 100"); // 100 or 150
        echo "Added total_marks to subjects.\n";
    }

    // Update Marks Table
    // Add cat1, cat2, exam_marks
    $markColumns = $db->query("SHOW COLUMNS FROM marks")->fetchAll(PDO::FETCH_COLUMN);

    if (!in_array('cat1', $markColumns)) {
        $db->exec("ALTER TABLE marks ADD COLUMN cat1 INT DEFAULT 0");
        echo "Added cat1 to marks.\n";
    }
    if (!in_array('cat2', $markColumns)) {
        $db->exec("ALTER TABLE marks ADD COLUMN cat2 INT DEFAULT 0");
        echo "Added cat2 to marks.\n";
    }
    if (!in_array('exam_marks', $markColumns)) {
        $db->exec("ALTER TABLE marks ADD COLUMN exam_marks INT DEFAULT 0");
        echo "Added exam_marks to marks.\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
