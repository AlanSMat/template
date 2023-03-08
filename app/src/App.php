<?php

use App\Exceptions\RouteNotFoundException;

class App 
{
    private static DB $db;
    private static Container $container;

    public function __construct(
        protected Config $config,
        protected Router $router,
        protected Request $request        
    )
    {
        static::$db = new DB($config->db ?? []);
        $this->loadContainer();
        $this->loadDB();
    }

    public static function db(): DB
    {
        return static::$db;
    }
}