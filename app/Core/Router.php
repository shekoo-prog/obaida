<?php

namespace App\Core;

class Router
{
    protected $routes = [];
    protected $currentRoute = null;

    public function add($method, $path, $handler)
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function get($path, $handler)
    {
        $this->add('GET', $path, $handler);
    }

    public function post($path, $handler)
    {
        $this->add('POST', $path, $handler);
    }

    public function dispatch()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            if ($this->matchRoute($uri, $route['path']) && $route['method'] === $method) {
                return $this->handleRoute($route);
            }
        }

        throw new \Exception('Route not found', 404);
    }

    protected function matchRoute($uri, $path)
    {
        $uri = trim($uri, '/');
        $path = trim($path, '/');
        
        return $uri === $path;
    }

    protected function handleRoute($route)
    {
        if (is_array($route['handler'])) {
            [$controller, $method] = $route['handler'];
            $controller = new $controller();
            return $controller->$method();
        }
        
        return $route['handler']();
    }
}