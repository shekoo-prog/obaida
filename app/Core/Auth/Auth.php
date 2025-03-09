<?php

namespace App\Core\Auth;

use App\Core\Session\Session;
use App\Core\Session\SessionManager;
use App\Models\User;

class Auth
{
    protected $session;
    protected $sessionManager;
    protected $user = null;

    public function __construct()
    {
        $this->session = new Session();
        $this->sessionManager = new SessionManager();
    }

    public function login($user)
    {
        $this->session->regenerate();
        $this->session->set('user_id', $user->id);
        $this->sessionManager->create($user->id);
    }

    public function logout($allDevices = false)
    {
        $userId = $this->session->get('user_id');
        if ($allDevices) {
            $this->sessionManager->destroyAllSessions($userId, false);
        } else {
            $this->sessionManager->destroySession($this->session->getId());
        }
        $this->session->clear();
    }

    public function check()
    {
        return $this->user() !== null;
    }

    public function user()
    {
        if ($this->user !== null) {
            return $this->user;
        }

        $userId = $this->session->get('user_id');
        if (!$userId) {
            return null;
        }

        $this->user = (new User())->find($userId);
        return $this->user;
    }

    public function guest()
    {
        return !$this->check();
    }

    public function id()
    {
        return $this->session->get('user_id');
    }
}