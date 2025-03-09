<?php

namespace App\Middleware;

use App\Core\Auth\Auth;

class AuthMiddleware extends Middleware
{
    protected $auth;

    public function __construct()
    {
        $this->auth = new Auth();
    }

    public function handle($request, $next)
    {
        if ($this->auth->guest()) {
            return \redirect('/login');
        }

        return $next($request);
    }
}