<?php
require_once __DIR__ . '/autoload.php';

use App\Core\Database;

try {
    $db = Database::getInstance()->getConnection();

    $sql = "CREATE TABLE IF NOT EXISTS password_resets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(100) NOT NULL,
        token VARCHAR(255) NOT NULL,
        expires_at DATETIME NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX (email),
        INDEX (token)
    )";

    $db->exec($sql);
    echo "Table 'password_resets' created successfully.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
