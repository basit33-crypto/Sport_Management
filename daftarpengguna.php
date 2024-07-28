<!DOCTYPE html>
<html>
<head>
    <title>Register User</title>
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
            max-width: 400px;
            width: 100%;
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
        .form-group input,
        .form-group select {
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
        .form-group .login-btn {
            background: #007bff;
            text-align: center;
            padding: 1em;
            border-radius: 4px;
            color: white;
            font-size: 1em;
            text-decoration: none;
            display: block;
        }
        .form-group .login-btn:hover {
            background: #0056b3;
        }
        .error {
            color: red;
            margin-bottom: 1em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Daftar Pengguna</h2>
        <?php
        include_once 'database.php';
        if (isset($_GET['error']) && $_GET['error'] == 'exists') {
            echo '<p class="error">Pengguna Telah Wujud</p>';
        }
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT id_kategori_pengguna, kategori_pengguna FROM tbl_jenispengguna");
            $stmt->execute();
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        ?>
        <form action="crud_pengguna.php" method="post">
            <div class="form-group">
                <label for="nama_pengguna">Nama Pengguna:</label>
                <input type="text" id="nama_pengguna" name="nama_pengguna" required>
            </div>
            <div class="form-group">
                <label for="ic_pengguna">IC Pengguna:</label>
                <input type="text" id="ic_pengguna" name="ic_pengguna" required>
            </div>
            <div class="form-group">
                <label for="emel_pengguna">Email Pengguna:</label>
                <input type="email" id="emel_pengguna" name="emel_pengguna" required>
            </div>
            <div class="form-group">
                <label for="no_tel">No Telefon:</label>
                <input type="text" id="no_tel" name="no_tel" required>
            </div>
            <div class="form-group">
                <label for="pass_pengguna">Password:</label>
                <input type="password" id="pass_pengguna" name="pass_pengguna" required>
            </div>
            <div class="form-group">
                <label for="kategori_pengguna">Kategori Pengguna:</label>
                <select id="id_kategori_pengguna" name="id_kategori_pengguna" required>
                    <?php foreach ($categories as $category): ?>
                        <?php if ($category['id_kategori_pengguna'] != 'P0' && $category['id_kategori_pengguna'] != 'P7'): ?>
                            <option value="<?= $category['id_kategori_pengguna'] ?>"
                                <?php if(isset($_POST['id_kategori_pengguna']) && $_POST['id_kategori_pengguna'] == $category['id_kategori_pengguna']) echo 'selected'; ?>>
                                <?= $category['kategori_pengguna'] ?>                                  
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" name="createp">Register</button>
            </div>
        </form>
        <div class="form-group">
            <a href="login.php" class="login-btn">Login</a>
        </div>
    </div>
</body>
</html>
