<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Core\Auth\Auth;
use App\Core\Validator;
use App\Models\User;
use function App\Core\redirect;

class RegisterController extends BaseController
{
    protected $auth;

    public function __construct()
    {
        $this->auth = new Auth();
    }
    public function index()
    {
        return $this->view('auth.register');
    }
}
