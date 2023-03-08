<?php
use App\Controllers\NECController;
use App\Controllers\HomeController;
use App\Controllers\CarryForwardController;
use App\Controllers\InvoiceController;
use App\Custom\Session;

require_once dirname(__DIR__) . '/vendor/autoload.php';

define('STORAGE_PATH', __DIR__ . '/../storage');
define('VIEW_PATH', __DIR__ . '/../views');

$router = new \App\Router();

$router->get('/', [HomeController::class, 'index']);
$router->get('/create', [HomeController::class, 'create']);

$router->get('/invoice', [InvoiceController::class, 'index']);
$router->get('/invoice/create', [InvoiceController::class, 'create']);
$router->post('/invoice/create', [InvoiceController::class, 'store']);
$router->get('/invoice/di', [InvoiceController::class, 'di']);

$router->get('/cfa', [CarryForwardController::class, 'index']);
$router->get('/cfa/create', [CarryForwardController::class, 'create']);
$router->post('/cfa/create', [CarryForwardController::class, 'store']);

$router->get('/nec', [NECController::class, 'index']);
$router->get('/nec/create', [NECController::class, 'store']);

echo $router->resolve(strtolower($_SERVER['REQUEST_METHOD']), $_SERVER['REQUEST_URI']);






