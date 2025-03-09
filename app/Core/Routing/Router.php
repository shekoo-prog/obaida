<?php

namespace App\Core\Routing;

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
}