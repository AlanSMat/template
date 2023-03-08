<?php
namespace App;

use App\Exceptions\RouteNotFoundException;

class Router
{
    private $routes;

    public function register(string $requestMethod, string $route, callable|array $action): self
    {
        $this->routes[$requestMethod][$route] = $action;

        return $this;
    }

    public function get(string $route, callable|array $action): self
    {
        return $this->register('get', $route, $action);
    }

    public function post(string $route, callable|array $action): self
    {
        return $this->register('post', $route, $action);
    }

    public function store(string $route, $action): self
    {
        return $this->register('post', $route, $action);
    }

    public function routes(): array
    {
        return $this->routes;
    }

    public function resolve(string $requestMethod, string $requestUri)
    {   
        $route = explode('?', $requestUri)[0];
        $action = $this->routes[$requestMethod][$route] ?? null;

        if ($action === null) {
            throw new RouteNotFoundException($route);
        }
        
        if (is_array($action)) {
            [$class, $method] = $action;

            if (class_exists($class) && method_exists($class, $method)) {
                return call_user_func_array([$class, $method], []);
            }
        }

        if(is_callable($action)) {
            return call_user_func($action);
        }

        throw new RouteNotFoundException($route);
    }
}