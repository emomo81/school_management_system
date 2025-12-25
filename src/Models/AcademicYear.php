<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class AcademicYear
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function all()
    {
        $stmt = $this->db->query("SELECT * FROM academic_years ORDER BY start_date DESC");
        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM academic_years WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getActive()
    {
        $stmt = $this->db->query("SELECT * FROM academic_years WHERE is_active = 1 LIMIT 1");
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO academic_years (name, start_date, end_date) VALUES (:name, :start_date, :end_date)");
        return $stmt->execute([
            'name' => $data['name'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date']
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE academic_years SET name = :name, start_date = :start_date, end_date = :end_date WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date']
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM academic_years WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function setActive($id)
    {
        try {
            $this->db->beginTransaction();

            // Deactivate all
            $this->db->exec("UPDATE academic_years SET is_active = 0");

            // Activate specific one
            $stmt = $this->db->prepare("UPDATE academic_years SET is_active = 1 WHERE id = :id");
            $success = $stmt->execute(['id' => $id]);

            $this->db->commit();
            return $success;
        } catch (\PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
