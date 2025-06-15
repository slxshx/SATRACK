<?php

namespace App\Controllers;

use Exception;
use App\Models\User;

class AuthController
{
    public function showLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require __DIR__ . '/../Views/login.php';
        }
    }

    public function handleLogin()
    {
        try {
            // Nur POST-Anfragen akzeptieren
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->showLogin();
                return;
            }

            // Eingaben prüfen
            if (empty($_POST['email']) || empty($_POST['password'])) {
                $_SESSION['loginDataIsMissing'] = 'Bitte gebe deine Anmeldedaten ein.';
                $this->showLogin();
                return;
            }

            // Eingaben säubern
            $email = htmlspecialchars(trim($_POST['email']));
            $password = $_POST['password'];

            // E-Mail-Format prüfen
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['invalidEmail'] = 'Ungültiges E-Mail-Format.';
                $this->showLogin();
                return;
            }

            // Nutzer anhand der E-Mail aus der Datenbank laden
            $user = User::findByEmail($email);

            // Wenn Nutzer nicht gefunden
            if (!$user) {
                $_SESSION['loginError'] = 'Dieser Benutzer existiert nicht.';
                $this->showLogin();
                return;
            }

            // Passwort überprüfen
            if (!$user->verifyPassword($password)) {
                $_SESSION['loginError'] = 'Das eingegebene Passwort ist falsch.';
                $this->showLogin();
                return;
            }

            // Login erfolgreich → Nutzer-ID in die Session speichern
            $_SESSION['user_id'] = $user->getId();
            $_SESSION['username'] = $user->getUsername();

            // Weiterleiten nach erfolgreichem Login
            header('Location: /dashboard');
            exit;
        } catch (Exception $e) {
            // Allgemeiner Fehler
            $_SESSION['loginError'] = 'Es ist ein Fehler beim Login aufgetreten.';
            $this->showLogin();
        }
    }
}
