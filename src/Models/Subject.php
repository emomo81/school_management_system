<?php

namespace App\Models;

use App\Core\Database;

class Subject
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM subjects WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM subjects WHERE deleted_at IS NULL ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO subjects (name, code, department_id, credits, total_marks) VALUES (:name, :code, :department_id, :credits, :total_marks)");
        return $stmt->execute([
            'name' => $data['name'],
            'code' => $data['code'],
            'department_id' => $data['department_id'],
            'credits' => $data['credits'],
            'total_marks' => $data['total_marks']
        ]);
    }
}
