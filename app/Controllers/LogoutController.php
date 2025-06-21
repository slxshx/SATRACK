<?php

namespace App\Controllers;

class LogoutController
{
    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: /login');
        exit;
    }
}
