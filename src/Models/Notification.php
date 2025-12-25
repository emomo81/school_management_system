<?php

namespace App\Models;

use App\Core\Database;
use App\Services\EmailService;

class Notification
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($userId, $type, $title, $message, $sendEmail = false)
    {
        $stmt = $this->db->prepare("
            INSERT INTO notifications (user_id, type, title, message) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$userId, $type, $title, $message]);
        $notificationId = $this->db->lastInsertId();

        if ($sendEmail) {
            $this->dispatchEmail($userId, $title, $message, $notificationId);
        }

        return $notificationId;
    }

    public function getForUser($userId)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM notifications 
            WHERE user_id = ? 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function markAsRead($id)
    {
        $stmt = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    private function dispatchEmail($userId, $title, $message, $notificationId)
    {
        // Get user email
        $stmt = $this->db->prepare("SELECT email FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if ($user && !empty($user['email'])) {
            $emailService = EmailService::getInstance();
            $result = $emailService->send($user['email'], $title, $message);

            if ($result['status'] === 'success' || $result['status'] === 'logged' || $result['status'] === 'simulated') {
                $upd = $this->db->prepare("UPDATE notifications SET email_sent = 1 WHERE id = ?");
                $upd->execute([$notificationId]);
            }
        }
    }
}
