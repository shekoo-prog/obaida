<?php

namespace App\Controllers;

class HomeController extends BaseController
{
    public function index()
    {
        return $this->view('home.index', [
            'title' => 'Welcome to Obaida Framework'
        ]);
    }

    public function about()
    {
        return $this->view('home.about');
    }
}