<?php
// Autoloading einbauen
require __DIR__ . '../vendor/autoload.php';
// Session starten
session_start();
// Router starten
$router->get('/dashboard', 'DashboardController@index');
$router->post('/api-request', 'ApiRequestController@store');
