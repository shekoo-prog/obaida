<?php

namespace App\Core\Routing;

use App\Core\Helpers\Helper;

class Router
{
    private static $routes = [];
    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function add($method, $uri, $action, $name = null)
    {
        self::$routes[] = [
            'method' => strtoupper($method),
            'uri' => $uri,
            'action' => $action,
            'name' => $name
        ];
    }

    public static function get($uri, $action, $name = null)
    {
        self::add('GET', $uri, $action, $name);
    }

    public static function post($uri, $action, $name = null)
    {
        self::add('POST', $uri, $action, $name);
    }

    public static function put($uri, $action, $name = null)
    {
        self::add('PUT', $uri, $action, $name);
    }

    public static function delete($uri, $action, $name = null)
    {
        self::add('DELETE', $uri, $action, $name);
    }

    public static function getRoutes()
    {
        return self::$routes;
    }

    public function dispatch()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = str_replace('/obaida/public', '', $uri);
        $method = $_SERVER['REQUEST_METHOD'];

        foreach (self::$routes as $route) {
            if ($route['uri'] === $uri && $route['method'] === $method) {
                if (is_array($route['action'])) {
                    $controller = new $route['action'][0]();
                    $action = $route['action'][1];
                    return $controller->$action();
                }
                return $route['action']();
            }
        }

        // 404 Not Found
        header("HTTP/1.0 404 Not Found");
        return Helper::view('errors.404');
    }
}