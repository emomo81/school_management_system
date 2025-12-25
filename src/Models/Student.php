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
            WHERE s.deleted_at IS NULL
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
                'password' => password_hash($data['password'], PASSWORD_BCRYPT)
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

    public function update($data)
    {
        $this->db->beginTransaction();
        try {
            // 1. Get User ID
            $stmt = $this->db->prepare("SELECT user_id FROM students WHERE id = ?");
            $stmt->execute([$data['id']]);
            $student = $stmt->fetch();

            if (!$student) {
                throw new \Exception("Student not found");
            }
            $userId = $student['user_id'];

            // 2. Update User (Name, Email, Password)
            $query = "UPDATE users SET name = :name, email = :email";
            $params = [
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'email' => $data['email'],
                'id' => $userId
            ];

            if (!empty($data['password'])) {
                $query .= ", password_hash = :password";
                $params['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            }

            $query .= " WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);

            // 3. Update Student Profile
            $stmt = $this->db->prepare("
                UPDATE students SET 
                    admission_no = :admission_no, 
                    dob = :dob, 
                    gender = :gender, 
                    address = :address, 
                    class_id = :class_id 
                WHERE id = :id
            ");
            $stmt->execute([
                'admission_no' => $data['admission_no'],
                'dob' => $data['dob'],
                'gender' => $data['gender'],
                'address' => $data['address'],
                'class_id' => $data['class_id'] ?: null,
                'id' => $data['id']
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
        return $this->db->query("SELECT COUNT(*) FROM students WHERE deleted_at IS NULL")->fetchColumn();
    }

    public function getByUserId($userId)
    {
        $stmt = $this->db->prepare("SELECT s.*, c.name as class_name, c.section FROM students s LEFT JOIN classes c ON s.class_id = c.id WHERE s.user_id = ? AND s.deleted_at IS NULL");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }
}
