<?php

namespace App\Models;

use App\Core\Database;

class Exam
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM exams WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getAll()
    {
        $stmt = $this->db->query("
            SELECT e.*, d.name as department_name, c.name as program_name, c.section 
            FROM exams e
            LEFT JOIN departments d ON e.department_id = d.id
            LEFT JOIN classes c ON e.program_id = c.id
            ORDER BY e.date DESC
        ");
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO exams (name, date, department_id, program_id, term) VALUES (:name, :date, :department_id, :program_id, :term)");
        return $stmt->execute([
            'name' => $data['name'],
            'date' => $data['date'],
            'department_id' => $data['department_id'] ?? null,
            'program_id' => $data['program_id'] ?? null,
            'term' => $data['term'] ?? null
        ]);
    }

    public function count()
    {
        return $this->db->query("SELECT COUNT(*) FROM exams")->fetchColumn();
    }
}
