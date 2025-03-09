<?php

use App\Core\Routing\Router;
use App\Controllers\HomeController;
use App\Core\Helpers\Helper;

Router::get('/', [HomeController::class, 'index']);

Router::get('/500', function() {
    return Helper::view('errors.500');
});

Router::get('/404', function() {
    return Helper::view('errors.404');
});