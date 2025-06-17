<?php
// Autoloading einbauen

use App\Core\Router;
use App\Controllers\AuthController;

require __DIR__ . '/../vendor/autoload.php';
// Session starten
session_start();

$router = new Router();

// Routen definieren
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'handleLogin']);


$router->dispatch();

$url = $_GET['url'] ?? 'home';
echo "Du rufst die Route '$url' auf!";
