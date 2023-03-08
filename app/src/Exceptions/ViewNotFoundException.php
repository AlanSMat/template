<?php
namespace App\Exceptions;

class RouteNotFoundException extends \Exception
{
    protected $message = 'View not found';
    public function __construct(string $viewPath)
    {
        parent::__construct("View not found: $viewPath");
    }
}