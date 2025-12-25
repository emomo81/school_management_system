<?php

namespace App\Models;

use App\Core\Database;

class Fee
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllWithStudents()
    {
        $stmt = $this->db->query("
            SELECT f.*, u.name as student_name, s.admission_no 
            FROM fees f
            JOIN students s ON f.student_id = s.id
            JOIN users u ON s.user_id = u.id
            ORDER BY f.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function getByStudent($studentId)
    {
        $stmt = $this->db->prepare("SELECT * FROM fees WHERE student_id = ? ORDER BY due_date ASC");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO fees (student_id, title, amount, status, due_date) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['student_id'],
            $data['title'],
            $data['amount'],
            $data['status'],
            $data['due_date']
        ]);
    }

    public function markAsPaid($feeId)
    {
        $stmt = $this->db->prepare("UPDATE fees SET status = 'paid' WHERE id = ?");
        return $stmt->execute([$feeId]);
    }
}
