

<!DOCTYPE html>
<html>
<head>
    <title>Menu Pengguna</title>
 <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background: linear-gradient(to right, #d32f2f, #f43b47);
            padding: 1em;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }
        .navbar .menu-left, .navbar .menu-right {
            display: flex;
            gap: 1em;
        }
        .navbar .menu-left {
            flex: 1;
        }
        .navbar .menu-right {
            margin-left: auto;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 0.5em 1em;
            border-radius: 4px;
        }
        .navbar a:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        .separator {
            padding: 0.5em 1em;
            color: white;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="menu-left">
            <?php if (isset($_SESSION['emel_pengguna'])): ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Log Masuk / Daftar</a>
            <?php endif; ?>
        </div>
        <div class="menu-right">
            <a href="senaraifasiliti.php">Home</a>
            <span class="separator">|</span>
            <a href="senaraitempahan.php">Tempahan</a>
        </div>
    </div>
</body>
</html>
