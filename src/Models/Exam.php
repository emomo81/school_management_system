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

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM exams ORDER BY date DESC");
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
}
