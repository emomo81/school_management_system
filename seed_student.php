<?php
require_once __DIR__ . '/autoload.php';
use App\Core\Database;

try {
    $db = Database::getInstance()->getConnection();

    // 1. Create a Class if not exists
    $stmt = $db->query("SELECT id FROM classes LIMIT 1");
    $classId = $stmt->fetchColumn();

    if (!$classId) {
        $db->exec("INSERT INTO classes (name, section) VALUES ('Grade 10', 'A')");
        $classId = $db->lastInsertId();
        echo "Created Class: Grade 10 - A\n";
    }

    // 2. Create User
    $email = 'student@school.com';
    $password = 'student123';
    $hash = password_hash($password, PASSWORD_BCRYPT);

    // Check if user exists
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $userId = $stmt->fetchColumn();

    if (!$userId) {
        $stmt = $db->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, 'student')");
        $stmt->execute(['John Student', $email, $hash]);
        $userId = $db->lastInsertId();
        echo "Created User: $email\n";
    } else {
        echo "User $email already exists.\n";
    }

    // 3. Create Student Record
    $stmt = $db->prepare("SELECT id FROM students WHERE user_id = ?");
    $stmt->execute([$userId]);
    $studentId = $stmt->fetchColumn();

    if (!$studentId) {
        $stmt = $db->prepare("INSERT INTO students (user_id, admission_no, dob, gender, class_id) VALUES (?, 'ADM001', '2005-01-01', 'male', ?)");
        $stmt->execute([$userId, $classId]);
        echo "Created Student Record linked to Class ID: $classId\n";
    } else {
        echo "Student record already exists.\n";
    }

    echo "\n--------------------------------\n";
    echo "Student Credentials:\n";
    echo "Email: $email\n";
    echo "Password: $password\n";
    echo "--------------------------------\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
