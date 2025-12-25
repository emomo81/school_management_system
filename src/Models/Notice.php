<?php

namespace App\Models;

use App\Core\Database;

class Notice
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM notices ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function create($title, $content)
    {
        $stmt = $this->db->prepare("INSERT INTO notices (title, content) VALUES (?, ?)");
        return $stmt->execute([$title, $content]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM notices WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
