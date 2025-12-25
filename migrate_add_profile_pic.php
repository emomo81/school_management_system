<?php
require_once __DIR__ . '/autoload.php';

use App\Core\Database;

try {
    $db = Database::getInstance()->getConnection();

    // Check if column exists
    $check = $db->query("SHOW COLUMNS FROM users LIKE 'profile_pic'");
    if ($check->rowCount() == 0) {
        $sql = "ALTER TABLE users ADD COLUMN profile_pic VARCHAR(255) NULL AFTER role";
        $db->exec($sql);
        echo "Successfully added profile_pic column to users table.\n";
    } else {
        echo "Column profile_pic already exists in users table.\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
