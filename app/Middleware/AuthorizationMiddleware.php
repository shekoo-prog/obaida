<?php

namespace App\Middleware;

use App\Core\Auth\Auth;

class AuthorizationMiddleware extends Middleware
{
    protected $auth;

    public function __construct()
    {
        $this->auth = new Auth();
    }

    public function handle($request, $next, $permission = null)
    {
        if (!$this->auth->check()) {
            throw new \Exception('Unauthorized', 401);
        }

        if ($permission && !$this->auth->user()->hasPermission($permission)) {
            throw new \Exception('Forbidden', 403);
        }

        return $next($request);
    }
}