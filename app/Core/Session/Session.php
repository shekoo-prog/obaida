<?php

namespace App\Core\Session;

class Session
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public function has($key)
    {
        return isset($_SESSION[$key]);
    }

    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

    public function clear()
    {
        session_destroy();
    }

    public function flash($key, $value = null)
    {
        if ($value !== null) {
            $this->set($key, $value);
            $this->set('_flash', array_merge($this->get('_flash', []), [$key]));
            return;
        }

        $value = $this->get($key);
        $this->remove($key);
        return $value;
    }

    public function clearFlash()
    {
        foreach ($this->get('_flash', []) as $key) {
            $this->remove($key);
        }
        $this->remove('_flash');
    }

    public function regenerate()
    {
        session_regenerate_id(true);
    }

    public function token()
    {
        if (!$this->has('_token')) {
            $this->set('_token', bin2hex(random_bytes(32)));
        }
        return $this->get('_token');
    }

    public function validateToken($token)
    {
        return hash_equals($this->token(), $token);
    }

    public function put($key, $value)
    {
        $this->set($key, $value);
    }

    public function pull($key, $default = null)
    {
        $value = $this->get($key, $default);
        $this->remove($key);
        return $value;
    }

    public function forget($keys)
    {
        $keys = is_array($keys) ? $keys : [$keys];
        foreach ($keys as $key) {
            $this->remove($key);
        }
    }

    public function all()
    {
        return $_SESSION;
    }

    public function exists($key)
    {
        return array_key_exists($key, $_SESSION);
    }

    public function flush()
    {
        $_SESSION = [];
    }

    public function isStarted()
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    public function getId()
    {
        return session_id();
    }

    public function setId($id)
    {
        session_id($id);
    }

    public function getName()
    {
        return session_name();
    }

    public function setName($name)
    {
        session_name($name);
    }
}