<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
    <style>
        body {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 2em;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 500%;
        }
        .container h2 {
            text-align: center;
            margin-bottom: 1em;
        }
        .form-group {
            margin-bottom: 1em;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5em;
        }
        .form-group input {
            width: 100%;
            padding: 0.5em;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group button {
            width: 100%;
            padding: 1em;
            background: #f43b47;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 1em;
            cursor: pointer;
        }
        .form-group button:hover {
            background: #d32f2f;
        }
        .error {
            color: red;
            margin-bottom: 1em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Log Masuk Pengguna</h2>
        <?php
        if (isset($_GET['error']) && $_GET['error'] == 'invalid') {
            echo '<p class="error">Emel Pengguna atau Kata Laluan salah</p>';
        }
        ?>
        <form action="login_action.php" method="post">
            <div class="form-group">
                <label for="emel_pengguna">Emel:</label>
                <input type="email" id="emel_pengguna" name="emel_pengguna" required>
            </div>
            <div class="form-group">
                <label for="pass_pengguna">Kata Laluan:</label>
                <input type="password" id="pass_pengguna" name="pass_pengguna" required>
            </div>
            <div class="form-group">
                <button type="submit" name="login">Log Masuk</button>
            </div>
        </form>
        <a href="daftarpengguna.php">Daftar</a>
    </div>
</body>
</html>
