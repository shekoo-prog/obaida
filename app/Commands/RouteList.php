<?php

namespace App\Commands;

use App\Core\Routing\Router;

class RouteList
{
    public function handle()
    {
        $routes = Router::getRoutes();
        
        echo "Available Routes:\n";
        echo str_repeat('-', 80) . "\n";
        echo sprintf("%-7s %-30s %-30s %s\n", 'Method', 'URI', 'Name', 'Action');
        echo str_repeat('-', 80) . "\n";
        
        foreach ($routes as $route) {
            echo sprintf(
                "%-7s %-30s %-30s %s\n",
                $route['method'],
                $route['uri'],
                $route['name'] ?? '',
                $route['action']
            );
        }
    }
}