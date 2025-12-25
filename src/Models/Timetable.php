<?php

namespace App\Models;

use App\Core\Database;

class Timetable
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO timetables (class_id, subject_id, teacher_id, day_of_week, start_time, end_time, room_number)
            VALUES (:class_id, :subject_id, :teacher_id, :day_of_week, :start_time, :end_time, :room_number)
        ");
        return $stmt->execute([
            'class_id' => $data['class_id'],
            'subject_id' => $data['subject_id'],
            'teacher_id' => $data['teacher_id'],
            'day_of_week' => $data['day_of_week'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'room_number' => $data['room_number'] ?? null
        ]);
    }

    public function getByClass($classId)
    {
        $stmt = $this->db->prepare("
            SELECT t.*, s.name as subject_name, te.user_id, u.name as teacher_name
            FROM timetables t
            JOIN subjects s ON t.subject_id = s.id
            JOIN teachers te ON t.teacher_id = te.id
            JOIN users u ON te.user_id = u.id
            WHERE t.class_id = ?
            ORDER BY 
                CASE 
                    WHEN day_of_week = 'Monday' THEN 1
                    WHEN day_of_week = 'Tuesday' THEN 2
                    WHEN day_of_week = 'Wednesday' THEN 3
                    WHEN day_of_week = 'Thursday' THEN 4
                    WHEN day_of_week = 'Friday' THEN 5
                    WHEN day_of_week = 'Saturday' THEN 6
                    WHEN day_of_week = 'Sunday' THEN 7
                END,
                t.start_time
        ");
        $stmt->execute([$classId]);
        return $stmt->fetchAll();
    }

    public function getByTeacher($teacherId)
    {
        $stmt = $this->db->prepare("
            SELECT t.*, s.name as subject_name, c.name as class_name, c.section
            FROM timetables t
            JOIN subjects s ON t.subject_id = s.id
            JOIN classes c ON t.class_id = c.id
            WHERE t.teacher_id = ?
            ORDER BY 
                 CASE 
                    WHEN day_of_week = 'Monday' THEN 1
                    WHEN day_of_week = 'Tuesday' THEN 2
                    WHEN day_of_week = 'Wednesday' THEN 3
                    WHEN day_of_week = 'Thursday' THEN 4
                    WHEN day_of_week = 'Friday' THEN 5
                    WHEN day_of_week = 'Saturday' THEN 6
                    WHEN day_of_week = 'Sunday' THEN 7
                END,
                t.start_time
        ");
        $stmt->execute([$teacherId]);
        return $stmt->fetchAll();
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM timetables WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
