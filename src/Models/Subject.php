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

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM subjects ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO subjects (name, code) VALUES (:name, :code)");
        return $stmt->execute([
            'name' => $data['name'],
            'code' => $data['code']
        ]);
    }
}
