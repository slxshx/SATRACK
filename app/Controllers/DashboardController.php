<?php

namespace App\Controllers;

class DashboardController
{
    public function showDashboard()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require __DIR__ . '/../Views/dashboard.php';
        }
    }
}
