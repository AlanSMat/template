<?php

namespace App;

use Psr\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class Container implements ContainerInterface 
{
    protected array $services = [];

    public function __construct()
    {
        $this->services = [
            'SalesTaxServiceInterface' => new SalesTaxService(),
            'PaymentGatewayService' => new PaymentGatewayService(),
            'EmailService' => new EmailService(),
        ];
    }

    public function get($id)
    {
        if(! $this->has($id)) 
        {   
            throw new NotFoundException("Service $id not found");
        }

        return $this->services[$id];
    }

    public function has($id): bool
    {
        return isset($this->services[$id]);
    }

    public function set($id, callable $service): void
    {
        $this->services[$id] = $service;
    }
}