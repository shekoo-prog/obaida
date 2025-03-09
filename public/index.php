<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = App\Core\Application::getInstance(dirname(__DIR__));

if ($app->get('config')['app']['debug'] ?? false) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}