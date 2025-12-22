<?php

require_once __DIR__ . '/autoload.php';
use App\Core\Database;

try {
    $db = Database::getInstance()->getConnection();

    // Hash for 'admin123'
    $newHash = password_hash('admin123', PASSWORD_BCRYPT);

    $stmt = $db->prepare("UPDATE users SET password_hash = :hash WHERE email = 'admin@school.com'");
    $stmt->execute(['hash' => $newHash]);

    echo "Admin password has been reset to 'admin123'.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
