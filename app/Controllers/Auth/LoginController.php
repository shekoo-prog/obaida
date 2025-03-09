<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Core\Auth\Auth;
use App\Core\Validator;

class LoginController extends BaseController
{
    protected $auth;

    public function __construct()
    {
        $this->auth = new Auth();
    }

    public function index()
    {
        return $this->view('auth/login');
    }
}