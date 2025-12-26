<?php

declare(strict_types = 1);

namespace App;

use App\Exceptions\Router\RouteNotFoundException;
use App\Exceptions\Router\RouterActionException;

class Router
{
    
    private array $routes = [];
    
    public function __construct(
        private Container $container
    )
    {}
    public function getContainer()
    {
        return $this->container;
    }
    private function register(string $method, string $uri, callable|array $action)
    {
        $this->routes[$method][$uri] = $action;

        return $this;
    }

    public function get(string $uri, callable|array $action)
    {
        return $this->register('get', $uri, $action);
    }

    public function post(string $uri, callable|array $action)
    {
        return $this->register('post', $uri, $action);
    }

    public function resolve(string $requestUri, string $requestMethod)
    {
        $route = explode('?', $requestUri)[0];
        $action = $this->routes[$requestMethod][$route] ?? null;

        if(!$action) {
            throw new RouterActionException('wrong action');
        }

        if(is_callable($action)) {
            return call_user_func($action);
        }

        if(is_array($action)) {
            [$class, $method] = $action;

            if(class_exists($class)) {
                $class = $this->container->get($class);
                if(method_exists($class, $method)) {
                    return call_user_func_array([$class, $method], []);
                }
            }
        }

        throw new RouteNotFoundException('route not found');
    }
}