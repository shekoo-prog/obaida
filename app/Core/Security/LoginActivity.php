<?php

namespace App\Core\Security;

use App\Core\Database;

class LoginActivity
{
    protected $db;
    protected $table = 'login_activities';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function log($userId, $status, $ip)
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        $data = [
            'user_id' => $userId,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'status' => $status,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $fields = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$values})";
        return $this->db->query($sql, array_values($data));
    }

    public function getFailedAttempts($userId, $minutes = 30)
    {
        $sql = "SELECT COUNT(*) as attempts FROM {$this->table} 
                WHERE user_id = ? AND status = 'failed' 
                AND created_at >= DATE_SUB(NOW(), INTERVAL ? MINUTE)";
        
        $result = $this->db->query($sql, [$userId, $minutes])->fetch();
        return $result->attempts;
    }
}