<?php
function flash(string $key): ?string
{
    if (!empty($_SESSION[$key])) {
        $msg = $_SESSION[$key];
        unset($_SESSION[$key]);
        return $msg;
    }
    return null;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/auth.css">
    <title>SATRACK Registrierung</title>
</head>

<body>
    <!-- Flash‑Fehler ausgeben -->
    <?php foreach (['invalidEmail', 'RegisterDataIsMissing', 'RegisterUsernameAlreadyExists', 'RegisterEmailAlreadyExists', 'RegisterPasswordTooShort', 'RegisterPasswordsNotIdentical', 'ErrorAddingUserToDB', 'RegisterError'] as $flashKey): ?>
        <?php if ($msg = flash($flashKey)): ?>
            <div class="flash-error"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>
    <?php endforeach; ?>


    <div class="form">
        <form method="POST" action="/register">
            <div class="form--header">
                <h2>Registrierung</h2>
            </div>

            <div class="input--field">
                <label for="username">Username:</label>
                <input type="text" name="username" placeholder="Username.." value="<?= $_SESSION['old_username'] ?? '' ?>" required>
                <br><br>
                <label for="email">E‑Mail:</label>
                <input type="text" name="email" placeholder="example@outlook.de" value="<?= $_SESSION['old_email'] ?? '' ?>" required>
                <br><br>
                <label for="password">Passwort (Min. 8 Zeichen):</label>
                <input type="password" name="password" placeholder="••••••••" required>
                <br><br>
                <label for="verifyPassword">Passwort bestätigen (Min. 8 Zeichen):</label>
                <input type="password" name="verifyPassword" placeholder="••••••••" required>
                <br><br>
            </div>
            <?php unset($_SESSION['old_username'], $_SESSION['old_email']); ?>
            <div class="form--actions">
                <button type="submit">Registrieren</button>
                <a href="/login">Du hast bereits ein Konto? Dann hier einloggen</a>
            </div>
        </form>
    </div>
</body>

</html>