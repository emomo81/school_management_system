<?php

require_once __DIR__ . '/autoload.php';
use App\Core\Database;

$db = Database::getInstance()->getConnection();

echo "Checking Users table...\n";
$stmt = $db->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($users)) {
    echo "No users found in database!\n";
} else {
    foreach ($users as $user) {
        echo "Found User: {$user['email']} (Role: {$user['role']})\n";
        echo "Hash: {$user['password_hash']}\n";

        $testPass = 'admin123';
        if (password_verify($testPass, $user['password_hash'])) {
            echo "Password '$testPass' MATCHES.\n";
        } else {
            echo "Password '$testPass' DOES NOT MATCH.\n";
        }
    }
}

// Re-hashing admin123 just in case
echo "\nNew Hash for 'admin123': " . password_hash('admin123', PASSWORD_BCRYPT) . "\n";
