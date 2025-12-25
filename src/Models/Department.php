<?php

namespace App\Models;

use App\Core\Database;

class Department
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM departments ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM departments WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO departments (name, code) VALUES (:name, :code)");
        return $stmt->execute([
            'name' => $data['name'],
            'code' => $data['code']
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE departments SET name = :name, code = :code WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'code' => $data['code']
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM departments WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
