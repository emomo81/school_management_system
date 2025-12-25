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
        $stmt = $this->db->query("SELECT c.*, d.name as department_name FROM classes c LEFT JOIN departments d ON c.department_id = d.id WHERE c.deleted_at IS NULL ORDER BY c.name ASC");
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO classes (name, section, department_id) VALUES (:name, :section, :department_id)");
        return $stmt->execute([
            'name' => $data['name'],
            'section' => $data['section'],
            'department_id' => $data['department_id']
        ]);
    }

    public function count()
    {
        return $this->db->query("SELECT COUNT(*) FROM classes")->fetchColumn();
    }
}
