<?php
// Autoloading einbauen

use App\Core\Router;
use App\Controllers\AuthController;

require __DIR__ . '/../vendor/autoload.php';
$config = require __DIR__ . '/../config/app.php';
date_default_timezone_set($config['timezone']);

// Session starten
session_start();

$router = new Router();

// Routen definieren
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'handleLogin']);

$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);

$router->dispatch();
