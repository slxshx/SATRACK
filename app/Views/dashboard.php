<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/dashboard.css">
    <title>SATRACK Dashboard</title>
</head>

<body>
    <header>
        <div class="header--logo">
            <a href="/dashboard">
                <h1>SATRACK</h1>
            </a>
        </div>
        <div class="navbar">
            <a class="navbar--entry" href="/dashboard">Dashboard</a>
            <a class="navbar--entry" href="/satellites">Satelliten</a>
            <a class="navbar--entry" href="/settings">Einstellungen</a>
            <a class="navbar--entry" href="/help">Hilfe</a>
            <div class="dropdown">
                <button onclick="kontoDropdown()" class="dropbtn">Konto</button>
                <div id="kontoDropdown" class="dropdown-content">
                    <a href="#">Account</a>
                    <a href="/logout">Logout</a>
                </div>
            </div>
        </div>
        </div>
        <form action="search" method="POST">
            <div class="searchbar">
                <label for="searchbar">Suchleiste</label>
                <input type="search" name="searchbar" placeholder="Suche..">
            </div>
        </form>
    </header>
    <div class="sidebar">
        <div class="sidebar--features">

        </div>
    </div>
    <div class="dashboard--wrapper">
        <div class="dashboard--box">
            <div class="dashboard--content">
                <div class="dashboard--item">

                </div>
            </div>
        </div>
    </div>
    <script src="/js/dashboard.js" defer></script>
</body>
<footer>
    <div class="footer--wrapper">
        <div class="footer--content">
            <div class="footer--links">

            </div>
        </div>
    </div>
</footer>

</html>