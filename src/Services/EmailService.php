<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    private static $instance = null;
    private $config;

    private function __construct()
    {
        $services = require __DIR__ . '/../../config/services.php';
        $this->config = $services['email'];
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    /**
     * Sends an email via SMTP.
     */
    public function send($to, $subject, $body): array
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = $this->config['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $this->config['username'];
            $mail->Password = $this->config['password'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $this->config['port'];

            // Recipients
            $mail->setFrom($this->config['from_email'], $this->config['from_name']);
            $mail->addAddress($to);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AltBody = strip_tags($body);

            $mail->send();

            $this->logEmail($to, $subject, "SENT VIA SMTP");
            return ['status' => 'success', 'message' => 'Email sent successfully'];
        } catch (Exception $e) {
            $error = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            $this->logEmail($to, $subject, "FAILED: " . $error);
            return ['status' => 'error', 'message' => $error];
        }
    }

    private function logEmail($to, $subject, $status)
    {
        $logFile = __DIR__ . '/../../logs/email_log.txt';
        $logDir = dirname($logFile);
        if (!is_dir($logDir))
            mkdir($logDir, 0777, true);
        $timestamp = date('Y-m-d H:i:s');
        file_put_contents($logFile, "[$timestamp] $status | TO: $to | SUBJ: $subject\n", FILE_APPEND);
    }
}
