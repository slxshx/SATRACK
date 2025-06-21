<?php
// Short helper‑function für Flash‑Ausgabe + Auto‑Clear
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
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/auth.css">
    <title>SATRACK Login</title>
</head>

<body>

    <!-- Flash‑Fehler ausgeben -->
    <?php foreach (['loginError', 'loginDataIsMissing', 'invalidEmail'] as $flashKey): ?>
        <?php if ($msg = flash($flashKey)): ?>
            <div class="flash-error"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>
    <?php endforeach; ?>

    <div class="form">
        <form method="POST" action="/login">
            <div class="form--header">
                <h2>Login</h2>
            </div>

            <div class="input--field">
                <label for="email">E‑Mail:</label>
                <input type="text" name="email" placeholder="example@outlook.de" required>

                <label for="password">Passwort:</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>

            <div class="form--actions">
                <button type="submit">Login</button>
                <a href="/register">Noch kein Konto?</a>
            </div>
        </form>
    </div>
</body>

</html>