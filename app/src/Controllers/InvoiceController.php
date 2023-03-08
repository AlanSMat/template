<?php
namespace App\Controllers;

use App\View;

class InvoiceController
{
    public static function index(): View
    {
        return View::make('invoice/index', ['title' => 'Invoice']);
    }

    public static function create()
    {
        return View::make('invoice/create', ['title' => 'Invoice Create']);
    }

    public static function di()
    {
        return View::make('invoice/di',['title' => 'Direct Injection']);
    }
}
