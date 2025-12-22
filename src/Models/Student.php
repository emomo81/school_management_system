<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Student
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $stmt = $this->db->query("
            SELECT s.*, u.name, u.email, c.name as class_name 
            FROM students s
            JOIN users u ON s.user_id = u.id
            LEFT JOIN classes c ON s.class_id = c.id
            ORDER BY s.admission_no DESC
        ");
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $this->db->beginTransaction();
        try {
            // 1. Create User
            $stmt = $this->db->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (:name, :email, :password, 'student')");
            $stmt->execute([
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'email' => $data['email'],
                'password' => password_hash($data['dob'], PASSWORD_BCRYPT) // Default password is DOB
            ]);
            $userId = $this->db->lastInsertId();

            // 2. Create Student Profile
            $stmt = $this->db->prepare("
                INSERT INTO students (user_id, admission_no, dob, gender, address, class_id) 
                VALUES (:user_id, :admission_no, :dob, :gender, :address, :class_id)
            ");
            $stmt->execute([
                'user_id' => $userId,
                'admission_no' => $data['admission_no'],
                'dob' => $data['dob'],
                'gender' => $data['gender'],
                'address' => $data['address'],
                'class_id' => $data['class_id'] ?: null
            ]);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
