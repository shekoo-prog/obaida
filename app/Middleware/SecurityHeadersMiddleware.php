<?php

namespace App\Middleware;

use App\Core\Security\Security;

class SecurityHeadersMiddleware extends Middleware
{
    protected $security;

    public function __construct()
    {
        $this->security = new Security();
    }

    public function handle($request, $next)
    {
        $this->security->secureHeaders();
        return $next($request);
    }
}