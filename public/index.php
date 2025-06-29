<?php
// Autoloading einbauen

use App\Controllers\ApiRequestController;
use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\LogoutController;
use App\Controllers\DashboardController;


require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

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

$router->get('/logout', [LogoutController::class, 'logout']);

$router->get('/dashboard',  [DashboardController::class, 'showDashboard']);

$router->get('/api/satellite/{id}', [ApiRequestController::class, 'fetchSatelliteData']);


$router->dispatch();
