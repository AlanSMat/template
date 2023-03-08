<?php
namespace App\Session;

//use App\Session\SessionInterface;

class Session
{
    private bool $isStarted = false;

    public function __construct()
    {
        $this->start();
    }

    public function isStarted() : bool
    {
        $this->isStarted = session_status() === PHP_SESSION_ACTIVE;
        return $this->isStarted;
    }

    public function start() : bool
    {
        if($this->isStarted) 
        {
            return true;
        }

        if (session_status() === PHP_SESSION_ACTIVE) 
        {
            $this->isStarted = true;
            return true;
        }

        session_start();
        $this->isStarted = true;
        return true;
    }

    public static function has(string $key) : bool
    {
        if(isset($_SESSION[$key])) {
            return true;
        }
        return false;
    }

    public static function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function set(string $key, $value) : void
    {
        $_SESSION[$key] = $value;
    }

    public static function unset(string $key) : void
    {
        if(Self::has($key)) {
            unset($_SESSION[$key]);
        }
    }

    public static function clear()
    {
        $_SESSION = [];
    }

    public static function remove()
    {
        session_destroy();
    }

    public static function all()
    {
        return $_SESSION;
    }
}