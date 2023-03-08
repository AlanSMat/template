<?php
namespace App\Controllers;

use App\View;

class CarryForwardController
{
    public static function index(): View
    {
        return View::make('cfa/index', ['title' => 'Carry Forward Request - Index']);
    }

    public static function create(): View
    {
        return View::make('cfa/create', ['title' => 'Carry Forward Request - Create']);
    }

    public static function store(): View
    {
        return View::make('cfa/store', ['title' => 'Carry Forward Request - Store']);
    }


}