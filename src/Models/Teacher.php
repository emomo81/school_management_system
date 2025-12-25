<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Teacher
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $stmt = $this->db->query("
            SELECT t.*, u.name, u.email 
            FROM teachers t
            JOIN users u ON t.user_id = u.id
            WHERE t.deleted_at IS NULL
            ORDER BY u.name ASC
        ");
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $this->db->beginTransaction();
        try {
            // 1. Create User
            $stmt = $this->db->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (:name, :email, :password, 'teacher')");
            $stmt->execute([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => password_hash($data['password'], PASSWORD_BCRYPT)
            ]);
            $userId = $this->db->lastInsertId();

            // 2. Create Teacher Profile
            $stmt = $this->db->prepare("INSERT INTO teachers (user_id, phone, qualification) VALUES (:user_id, :phone, :qualification)");
            $stmt->execute([
                'user_id' => $userId,
                'phone' => $data['phone'],
                'qualification' => $data['qualification']
            ]);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function count()
    {
        return $this->db->query("SELECT COUNT(*) FROM teachers")->fetchColumn();
    }
}
