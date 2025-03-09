<?php

namespace App\Core\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Core\Helpers\Helper;

class Mailer
{
    protected $mailer;
    protected $config;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../../../config/mail.php';
        $this->mailer = new PHPMailer(true);
        $this->configure();
    }

    protected function configure()
    {
        try {
            $this->mailer->isSMTP();
            $this->mailer->Host = $this->config['host'];
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $this->config['username'];
            $this->mailer->Password = $this->config['password'];
            $this->mailer->SMTPSecure = $this->config['encryption'];
            $this->mailer->Port = $this->config['port'];
            $this->mailer->setFrom(
                $this->config['from']['address'],
                $this->config['from']['name']
            );
        } catch (Exception $e) {
            throw new Exception("Mail configuration error: " . $e->getMessage());
        }
    }

    public function send($to, $subject, $body, $isHtml = true)
    {
        try {
            $this->mailer->addAddress($to);
            $this->mailer->isHTML($isHtml);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;

            return $this->mailer->send();
        } catch (Exception $e) {
            throw new Exception("Mail sending failed: " . $e->getMessage());
        }
    }
}