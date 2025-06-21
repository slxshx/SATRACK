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

    public function showRegister()
    {
        // Bei GET-Anfrage wollen wir nur die Seite laden
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require __DIR__ . '/../Views/register.php';
        }
    }

    public function register()
    {
        try {
            // Wenn nicht POST dann wahrscheinlich GET und dabei soll nur die Seite mit leerem Formular angezeigt werden
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->showRegister();
                return;
            }

            // Erste Validierung zum Check ob in den Feldern überhaupt etwas drinnen steht UND ob Password und verifyPassword den gleichen Inhalt haben
            if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['verifyPassword'])) {
                $_SESSION['RegisterDataIsMissing'] = 'Bitte fülle alle Felder aus.';
                $this->showRegister();
                return;
            }


            // Eingaben säubern, wie leerzeichen etc.
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $verifiedPassword = $_POST['verifyPassword'];
            $createdAt = date('Y-m-d H:i:s');


            // Checken ob ein Nutzer mit dem Nutzernamen oder der E-Mail schon existiert
            $userByUsername =  User::findByUsername($username);

            // Validierung für den Username, schauen ob bereits einer existiert:
            if ($userByUsername) {
                $_SESSION['RegisterUsernameAlreadyExists'] = 'Dieser Username wird bereits verwendet.';
                $_SESSION['old_username'] = $username;
                $_SESSION['old_email'] = $email;

                $this->showRegister();
                return;
            }

            // Checken ob die Email bereits registriert ist: 
            $userByEmail = User::findByEmail($email);

            // Validierung der E-Mail und bestätigen eines gültigen E-Mail Formats:
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['invalidEmail'] = 'Ungültiges E-Mail-Format.';
                $_SESSION['old_username'] = $username;
                $this->showRegister();
                return;
            }

            // Wenn E-Mail korrekt ist, Überprüfung ob bereits ein User mit dieser E-Mail existiert
            if ($userByEmail) {
                $_SESSION['RegisterEmailAlreadyExists'] = 'Diese E-Mail wird bereits verwendet.';
                $_SESSION['old_username'] = $username;
                $this->showRegister();
                return;
            }

            // Passwortlänge checken
            if (strlen($password) < 8) {
                $_SESSION['RegisterPasswordTooShort'] = 'Das eingegebene Passwort ist zu kurz.';
                $_SESSION['old_username'] = $username;
                $_SESSION['old_email'] = $email;
                $this->showRegister();
                return;
            }

            // Checken ob Password und verifyPassword identisch sind
            if ($password !== $verifiedPassword) {
                $_SESSION['RegisterPasswordsNotIdentical'] = 'Deine Passwörter stimmen nicht überein.';
                $_SESSION['old_username'] = $username;
                $_SESSION['old_email'] = $email;
                $this->showRegister();
                return;
            }

            // Password hashen
            $password = password_hash($password, PASSWORD_DEFAULT);

            // Array vorbereiten zum Anlegen in die DB
            $data = [
                'username' => $username,
                'email' => $email,
                'password_hash' => $password,
                'created_at' => $createdAt
            ];

            // Anlegen eines neuen NutzerObjekts und speichern in die Datenbank
            $addedUser = new User($data);
            $userId = $addedUser->create($data);

            //Überprüfung ob speichern erfolgreich war
            if (!$userId) {
                $_SESSION['ErrorAddingUserToDB'] =  'Fehler beim speichern des neuen Kontos. Bitte probiere es erneut.';
                $this->showRegister();
                return;
            }

            // Registrierung erfolgreich → Nutzer-ID in die Session speichern
            $_SESSION['user_id'] = $userId;
            $_SESSION['username'] = $username;

            // Weiterleiten nach erfolgreicher Registrierung
            $_SESSION['RegisterSucceeded'] = 'Konto wurde erfolgreich erstellt.';
            header('Location: /login');
            exit;
        } catch (Exception $e) {
            // Allgemeiner Fehler
            $_SESSION['RegisterError'] = 'Es ist ein Fehler bei der Registrierung aufgetreten.';
            $this->showRegister();
            return;
        }
    }

    public function logout() {}
}
