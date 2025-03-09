<?php

namespace App\Middleware;

use App\Core\Security\Security;

class XssMiddleware extends Middleware
{
    protected $security;

    public function __construct()
    {
        $this->security = new Security();
    }

    public function handle($request, $next)
    {
        $_GET = $this->security->xssClean($_GET);
        $_POST = $this->security->xssClean($_POST);
        $_REQUEST = $this->security->xssClean($_REQUEST);
        
        return $next($request);
    }
}