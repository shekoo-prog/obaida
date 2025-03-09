<?php

namespace App\Core\Session;

use App\Core\Database;

class SessionManager
{
    protected $db;
    protected $sessionLifetime = 300;
    protected $session;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->session = new Session();
        $this->gc();
    }

    public function create($userId)
    {
        $sessionId = $this->session->getId();
        $payload = [
            'user_id' => $userId,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $data = [
            'id' => $sessionId,
            'user_id' => $userId,
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'payload' => json_encode($payload),
            'last_activity' => date('Y-m-d H:i:s')
        ];

        $sql = "INSERT INTO sessions (id, user_id, ip_address, user_agent, payload, last_activity) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $this->db->query($sql, array_values($data));
        return $sessionId;
    }

    public function getActiveSessions($userId)
    {
        $sql = "SELECT * FROM sessions WHERE user_id = ? AND last_activity >= ?";
        $cutoff = date('Y-m-d H:i:s', strtotime("-{$this->sessionLifetime} minutes"));
        return $this->db->query($sql, [$userId, $cutoff])->fetchAll();
    }

    public function destroySession($sessionId)
    {
        $sql = "DELETE FROM sessions WHERE id = ?";
        return $this->db->query($sql, [$sessionId]);
    }

    public function destroyAllSessions($userId, $exceptCurrentSession = true)
    {
        $sql = "DELETE FROM sessions WHERE user_id = ?";
        if ($exceptCurrentSession) {
            $sql .= " AND id != ?";
            return $this->db->query($sql, [$userId, $this->session->getId()]);
        }
        return $this->db->query($sql, [$userId]);
    }

    public function updateLastActivity()
    {
        $sql = "UPDATE sessions SET last_activity = ? WHERE id = ?";
        return $this->db->query($sql, [date('Y-m-d H:i:s'), $this->session->getId()]);
    }

    public function isValid($sessionId)
    {
        $sql = "SELECT last_activity FROM sessions WHERE id = ?";
        $session = $this->db->query($sql, [$sessionId])->fetch();

        if (!$session) {
            return false;
        }

        $cutoff = date('Y-m-d H:i:s', strtotime("-{$this->sessionLifetime} minutes"));
        return strtotime($session->last_activity) >= strtotime($cutoff);
    }

    protected function gc()
    {
        $cutoff = date('Y-m-d H:i:s', strtotime("-{$this->sessionLifetime} minutes"));
        $sql = "DELETE FROM sessions WHERE last_activity < ?";
        $this->db->query($sql, [$cutoff]);
    }

    public function setSessionLifetime($minutes)
    {
        $this->sessionLifetime = $minutes;
    }
}