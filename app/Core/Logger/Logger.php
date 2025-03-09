<?php

namespace App\Core\Logger;

use App\Core\Database\Database;
use App\Core\Auth\Auth;

class Logger
{
    protected $db;
    protected $auth;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->auth = new Auth();
    }

    public function error($message, $context = [])
    {
        $this->log('error', $message, $context);
    }

    public function info($message, $context = [])
    {
        $this->log('info', $message, $context);
    }

    public function warning($message, $context = [])
    {
        $this->log('warning', $message, $context);
    }

    public function activity($message, $context = [])
    {
        $this->log('activity', $message, $context);
    }

    protected function log($type, $message, $context = [])
    {
        $data = [
            'user_id' => $this->auth->id(),
            'type' => $type,
            'message' => $message,
            'context' => json_encode($context),
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
        ];

        $sql = "INSERT INTO logs (user_id, type, message, context, ip_address, user_agent) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $this->db->query($sql, array_values($data));

        if ($type === 'error') {
            $this->writeToFile($message, $context);
        }
    }

    protected function writeToFile($message, $context)
    {
        $logPath = dirname(__DIR__, 3) . '/storage/logs';
        if (!is_dir($logPath)) {
            mkdir($logPath, 0755, true);
        }

        $date = date('Y-m-d');
        $time = date('H:i:s');
        $logFile = $logPath . "/error-{$date}.log";

        $logMessage = "[{$time}] {$message}\n";
        if (!empty($context)) {
            $logMessage .= "Context: " . json_encode($context) . "\n";
        }
        $logMessage .= "------------------------\n";

        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
}