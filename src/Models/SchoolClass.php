<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class SchoolClass
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM classes WHERE deleted_at IS NULL ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO classes (name, section) VALUES (:name, :section)");
        return $stmt->execute([
            'name' => $data['name'],
            'section' => $data['section']
        ]);
    }

    public function count()
    {
        return $this->db->query("SELECT COUNT(*) FROM classes")->fetchColumn();
    }
}
