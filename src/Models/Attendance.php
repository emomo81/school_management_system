<?php

namespace App\Models;

use App\Core\Database;

class Attendance
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByClassAndDate($classId, $date)
    {
        $stmt = $this->db->prepare("
            SELECT s.id as student_id, u.name, a.status, a.remarks
            FROM students s
            JOIN users u ON s.user_id = u.id
            LEFT JOIN attendance a ON s.id = a.student_id AND a.date = :date
            WHERE s.class_id = :class_id
            ORDER BY u.name ASC
        ");
        $stmt->execute(['class_id' => $classId, 'date' => $date]);
        return $stmt->fetchAll();
    }

    public function save($classId, $date, $records)
    {
        $this->db->beginTransaction();
        try {
            foreach ($records as $studentId => $data) {
                // Check if already exists
                $stmt = $this->db->prepare("SELECT id FROM attendance WHERE student_id = ? AND date = ?");
                $stmt->execute([$studentId, $date]);
                $exists = $stmt->fetch();

                if ($exists) {
                    $upd = $this->db->prepare("UPDATE attendance SET status = ?, remarks = ? WHERE id = ?");
                    $upd->execute([$data['status'], $data['remarks'], $exists['id']]);
                } else {
                    $ins = $this->db->prepare("INSERT INTO attendance (student_id, date, status, remarks) VALUES (?, ?, ?, ?)");
                    $ins->execute([$studentId, $date, $data['status'], $data['remarks']]);
                }
            }
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getStudentSummary($studentId)
    {
        $stmt = $this->db->prepare("
            SELECT status, COUNT(*) as count 
            FROM attendance 
            WHERE student_id = ? 
            GROUP BY status
        ");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll();
    }
}
