<?php

namespace App\Core\Security;

use App\Core\Session\Session;
use App\Models\User;

class Security
{
    protected $session;
    protected $ipManager;
    protected $loginActivity;
    protected $maxLoginAttempts = 5;
    protected $lockoutTime = 5;
    protected $maxAttempts = 5;
    protected $decayMinutes = 1;

    public function __construct()
    {
        $this->session = new Session();
        $this->ipManager = new IpManager();
        $this->loginActivity = new LoginActivity();
    }

    public function csrf()
    {
        return $this->session->token();
    }

    public function validateCsrf($token)
    {
        return $this->session->validateToken($token);
    }

    public function xssClean($data)
    {
        if (is_string($data)) {
            return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        }

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->xssClean($value);
            }
        }

        return $data;
    }

    public function rateLimiter($key, $maxAttempts = null, $decayMinutes = null)
    {
        $attempts = $this->session->get("rate_limit_{$key}", 0);
        $lastAttempt = $this->session->get("rate_limit_{$key}_time", 0);
        
        if (time() - $lastAttempt > ($decayMinutes ?? $this->decayMinutes) * 60) {
            $attempts = 0;
        }

        if ($attempts >= ($maxAttempts ?? $this->maxAttempts)) {
            return false;
        }

        $this->session->set("rate_limit_{$key}", $attempts + 1);
        $this->session->set("rate_limit_{$key}_time", time());
        
        return true;
    }

    public function generateNonce()
    {
        return bin2hex(random_bytes(16));
    }

    public function secureHeaders()
    {
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        header('X-Content-Type-Options: nosniff');
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
        header('Content-Security-Policy: default-src \'self\'');
        header('Referrer-Policy: strict-origin-when-cross-origin');
    }

    public function sanitizeInput($input)
    {
        if (is_array($input)) {
            return array_map([$this, 'sanitizeInput'], $input);
        }
        
        return strip_tags(trim($input));
    }

    public function validatePassword($password)
    {
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
        return preg_match($pattern, $password);
    }

    public function encryptData($data, $key = null)
    {
        $key = $key ?? $_ENV['APP_KEY'];
        $ivlen = openssl_cipher_iv_length($cipher = "AES-256-CBC");
        $iv = openssl_random_pseudo_bytes($ivlen);
        $encrypted = openssl_encrypt($data, $cipher, $key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    public function decryptData($data, $key = null)
    {
        $key = $key ?? $_ENV['APP_KEY'];
        $data = base64_decode($data);
        $ivlen = openssl_cipher_iv_length($cipher = "AES-256-CBC");
        $iv = substr($data, 0, $ivlen);
        $encrypted = substr($data, $ivlen);
        return openssl_decrypt($encrypted, $cipher, $key, 0, $iv);
    }

    public function generateStrongPassword($length = 16)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=[]{}|;:,.<>?';
        return substr(str_shuffle(str_repeat($chars, $length)), 0, $length);
    }

    public function checkIp($ip)
    {
        if ($this->ipManager->isBlacklisted($ip)) {
            throw new \Exception('Access denied from this IP');
        }
        return !$this->ipManager->isWhitelisted($ip);
    }

    public function handleLoginAttempt($userId, $success = false)
    {
        $user = (new User())->find($userId);
        $ip = $_SERVER['REMOTE_ADDR'];
        $status = $success ? 'success' : 'failed';
        
        $this->loginActivity->log($userId, $status, $ip);
        
        if ($success) {
            $this->resetLoginAttempts($userId);
            return true;
        }

        $this->incrementLoginAttempts($userId);
        
        if ($user->login_attempts >= $this->maxLoginAttempts) {
            $this->lockAccount($userId);
            $remainingTime = $this->getRemainingLockTime($userId);
            throw new \Exception("Account locked. Please try again after {$remainingTime} minutes.", 423);
        }

        $remainingAttempts = $this->maxLoginAttempts - $user->login_attempts;
        throw new \Exception("Login failed. {$remainingAttempts} attempts remaining.", 401);
    }

    protected function lockAccount($userId)
    {
        $lockedUntil = date('Y-m-d H:i:s', strtotime("+{$this->lockoutTime} minutes"));
        $user = new User();
        $user->update($userId, [
            'locked_until' => $lockedUntil
        ]);
    }

    protected function incrementLoginAttempts($userId)
    {
        $user = new User();
        $currentUser = $user->find($userId);
        $user->update($userId, [
            'login_attempts' => $currentUser->login_attempts + 1
        ]);
    }

    protected function resetLoginAttempts($userId)
    {
        $user = new User();
        $user->update($userId, [
            'login_attempts' => 0,
            'locked_until' => null
        ]);
    }

    public function isAccountLocked($userId)
    {
        $user = (new User())->find($userId);
        if (!$user->locked_until) {
            return false;
        }

        $lockedUntil = strtotime($user->locked_until);
        if (time() >= $lockedUntil) {
            $this->resetLoginAttempts($userId);
            return false;
        }

        return true;
    }

    public function getRemainingLockTime($userId)
    {
        $user = (new User())->find($userId);
        if (!$user->locked_until) {
            return 0;
        }

        $remainingSeconds = strtotime($user->locked_until) - time();
        return ceil($remainingSeconds / 60);
    }
}