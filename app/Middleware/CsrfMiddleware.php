<?php

namespace App\Middleware;

use App\Core\Security\Security;

class CsrfMiddleware extends Middleware
{
    protected $security;
    protected $except = [
        // Add routes that should be excluded from CSRF protection
    ];

    public function __construct()
    {
        $this->security = new Security();
    }

    public function handle($request, $next)
    {
        if ($this->shouldCheckCsrf()) {
            $token = $_POST['_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
            
            if (!$token || !$this->security->validateCsrf($token)) {
                throw new \Exception('CSRF token mismatch');
            }
        }

        return $next($request);
    }

    protected function shouldCheckCsrf()
    {
        return !in_array($_SERVER['REQUEST_URI'], $this->except) 
            && in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT', 'DELETE', 'PATCH']);
    }
}