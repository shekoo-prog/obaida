<?php

namespace App\Controllers;

use App\Core\Helpers\Helper;

class HomeController extends BaseController
{
    public function index()
    {
        return Helper::view('home.index', [
            'message' => 'Welcome to Obaida Framework',
            'title' => 'Home Page'
        ]);
    }
}