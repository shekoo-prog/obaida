<?php

use App\Core\Routing\Router;
use App\Controllers\HomeController;

Router::get('/', [HomeController::class, 'index']);