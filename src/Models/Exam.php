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
        $stmt = $this->db->query("SELECT * FROM exams WHERE deleted_at IS NULL ORDER BY date DESC");
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO exams (name, date) VALUES (:name, :date)");
        return $stmt->execute([
            'name' => $data['name'],
            'date' => $data['date']
        ]);
    }

    public function count()
    {
        return $this->db->query("SELECT COUNT(*) FROM exams")->fetchColumn();
    }
}
