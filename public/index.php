<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = \App\Core\Application::getInstance(__DIR__ . '/..');

$router = new \App\Core\Routing\Router();

require_once __DIR__ . '/../routes/web.php';
require_once __DIR__ . '/../routes/api.php';

try {
    $router->dispatch();
} catch (\Exception $e) {
    if ($_ENV['APP_DEBUG']) {
        throw $e;
    }
    echo 'Something went wrong!';
}