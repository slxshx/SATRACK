<?php
// Autoloading einbauen
require __DIR__ . '/../vendor/autoload.php';
// Session starten
session_start();
$_SESSION['user_id'] = $user->id;
// Router starten
echo 'smd';
