<?php
namespace App\Session\SessionInterface;

interface SessionInterface 
{
    public function start() : bool;

    public static function has(string $key) : bool;

    public static function get(string $key, $default = null);

    public static function set(string $key, $value) : void;

    public static function unset(string $key) : void;

    public static function clear();

    public static function remove();

    public static function all();
}