<?php
namespace App\Controllers;

use App\View;

class NECController
{
    public static function index(): View
    {
        return View::make('nec/index', ['title' => 'NEC']);
    }

    public static function create(): View
    {
        return View::make('nec/create');
    }

    public static function store(): View
    {
        return View::make('nec/store');
    }

}