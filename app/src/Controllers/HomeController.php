<?php
namespace App\Controllers;

use App\View;

class HomeController
{
    public static function index() : View
    {
        return View::make('index');
    }

    public static function create()
    {
        return View::make('create');
    }
}