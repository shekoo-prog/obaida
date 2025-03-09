<?php

namespace App\Controllers;

abstract class BaseController
{
    protected $middlewares = [];
    protected $view;
    protected $request;
    protected $response;

    public function __construct()
    {
        $this->initializeComponents();
    }

    protected function middleware($middleware, $permission = null)
    {
        $this->middlewares[] = [
            'middleware' => $middleware,
            'permission' => $permission
        ];
    }

    public function getMiddlewares()
    {
        return $this->middlewares;
    }

    protected function initializeComponents()
    {
        // Will be implemented later
        $this->request = new \stdClass();
        $this->response = new \stdClass();
    }

    protected function view($view, $data = [])
    {
        $viewEngine = new \App\Core\View();
        return $viewEngine->render($view, $data);
    }

    protected function json($data, int $status = 200)
    {
        header('Content-Type: application/json');
        http_response_code($status);
        return json_encode($data);
    }
}