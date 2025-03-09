<?php

namespace App\Middleware;

use App\Core\Security\Security;

class RateLimitMiddleware extends Middleware
{
    protected $security;
    protected $maxAttempts = 60;
    protected $decayMinutes = 1;

    public function __construct()
    {
        $this->security = new Security();
    }

    public function handle($request, $next)
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $route = $_SERVER['REQUEST_URI'];
        $key = "rate_limit_{$ip}_{$route}";

        if (!$this->security->rateLimiter($key, $this->maxAttempts, $this->decayMinutes)) {
            throw new \Exception('Too Many Requests', 429);
        }

        return $next($request);
    }
}