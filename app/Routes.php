<?php

declare(strict_types = 1);

namespace App;

use App\Config\RoutesConfig;

class Routes
{
    public function __construct(private Router $router, private RoutesConfig $config)
    {
    }

    public function registerRoutes()
    {
        $routes = ($this->config)->getRoutes();

        foreach($routes as $route)
        {
            $method = $route['method'];
            $uri = $route['route'];
            $action = $route['action'];

            $this->router->$method($uri, $action);
        }
    }

    public function getRouter(): Router
    {
        return $this->router;
    }
}