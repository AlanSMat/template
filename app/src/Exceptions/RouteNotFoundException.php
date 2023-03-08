<?php

namespace App\Exceptions;

class RouteNotFoundException extends \Exception
{
    protected $message = '404 Route not found';
    public function __construct(string $route)
    {
        parent::__construct("Route not found: $route");
    }
}