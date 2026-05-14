<?php

require_once __DIR__ . '/autoload.php';

use App\Core\Database;

echo "Starting users migration to MongoDB...\n\n";

try {
    // Get MySQL connection
    $db = Database::getInstance()->getConnection();
    
    // Fetch all users from MySQL
    $stmt = $db->query("SELECT id, name, email, password_hash, role, created_at FROM users ORDER BY id");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($users)) {
        echo "⚠️  No users found in MySQL database.\n";
        exit(1);
    }
    
    echo "Found " . count($users) . " user(s) in MySQL.\n";
    echo "Preparing to insert into MongoDB...\n\n";
    
    // Get MongoDB URI
    $mongoUri = $_ENV['MONGODB_URI'] ?? getenv('MONGODB_URI');
    
    if (!$mongoUri) {
        echo "❌ Error: MONGODB_URI not set in environment variables.\n";
        exit(1);
    }
    
    echo "MongoDB URI configured: " . substr($mongoUri, 0, 50) . "...\n\n";
    
    // Prepare documents for bulk insert
    $documents = [];
    foreach ($users as $user) {
        $documents[] = [
            'id' => (int)$user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'password_hash' => $user['password_hash'],
            'role' => $user['role'],
            'created_at' => $user['created_at'],
            'status' => 'active',
            'migrated_at' => date('Y-m-d H:i:s')
        ];
    }
    
    echo "User data prepared:\n";
    foreach ($documents as $doc) {
        echo "✓ {$doc['name']} ({$doc['email']}) - Role: {$doc['role']}\n";
    }
    
    echo "\n📝 Migration script created successfully.\n";
    echo "\nNext Steps:\n";
    echo "1. Install MongoDB PHP driver: composer install\n";
    echo "2. Run: php migrate_users_to_mongodb.php\n\n";
    
    // Display sample data structure
    echo "Sample document structure:\n";
    echo json_encode($documents[0] ?? [], JSON_PRETTY_PRINT) . "\n";
    
    // Save to JSON for reference
    file_put_contents(__DIR__ . '/users_for_mongodb.json', json_encode($documents, JSON_PRETTY_PRINT));
    echo "\n✅ Users data saved to users_for_mongodb.json\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
