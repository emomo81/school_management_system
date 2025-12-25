<?php
require_once __DIR__ . '/autoload.php';

use App\Core\Database;

try {
    $db = Database::getInstance()->getConnection();

    // Check if department_id exists in classes
    $columns = $db->query("SHOW COLUMNS FROM classes")->fetchAll(PDO::FETCH_COLUMN);

    if (!in_array('department_id', $columns)) {
        $db->exec("ALTER TABLE classes ADD COLUMN department_id INT NULL");
        $db->exec("ALTER TABLE classes ADD FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL");
        echo "Added department_id to classes.\n";
    } else {
        echo "department_id already exists in classes.\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
