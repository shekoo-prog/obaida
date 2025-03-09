<?php

namespace App\Middleware;

use App\Core\Auth\Auth;

class GuestMiddleware extends Middleware
{
    protected $auth;

    public function __construct()
    {
        $this->auth = new Auth();
    }

    public function handle($request, $next)
    {
        if ($this->auth->check()) {
            return \redirect('/dashboard');
        }

        return $next($request);
    }
}