<?php

namespace App\Models;

use App\Core\Database;

class Assignment
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $stmt = $this->db->query("
            SELECT a.*, u.name as teacher_name, s.name as subject_name, c.name as class_name, c.section 
            FROM subject_assignments a
            JOIN teachers t ON a.teacher_id = t.id
            JOIN users u ON t.user_id = u.id
            JOIN subjects s ON a.subject_id = s.id
            JOIN classes c ON a.class_id = c.id
            ORDER BY c.name ASC, s.name ASC
        ");
        return $stmt->fetchAll();
    }

    public function create($teacherId, $subjectId, $classId)
    {
        $stmt = $this->db->prepare("INSERT INTO subject_assignments (teacher_id, subject_id, class_id) VALUES (?, ?, ?)");
        return $stmt->execute([$teacherId, $subjectId, $classId]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM subject_assignments WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
