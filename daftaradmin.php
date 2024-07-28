<!DOCTYPE html>
<html>
<head>
    <title>Daftar Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f4f4f4;
            height: 100vh;
            margin: 0;
        }
        .container {
            width: 60%;
            margin: auto;
        }
        #tmbahadmmin {
            background: white;
            padding: 2em;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);         
            width: 100%;
            max-width: 1000px;
            margin: 100px auto;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2em;
            margin-bottom: 2em; /* Added margin-bottom for space at the bottom */
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 0.5em;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .delete-btn {
            background: #f44336;
            color: white;
            border: none;
            padding: 0.5em 1em;
            border-radius: 4px;
            cursor: pointer;
        }
        .delete-btn:hover {
            background: #d32f2f;
        }
    </style>
</head>
<body>
    <div><?php include_once "menu.php"?></div>
    <div class="container">
        <div id="tmbahadmmin">
            <h2>Daftar Admin</h2>
            <?php
            include_once 'database.php';
            if (isset($_GET['error']) && $_GET['error'] == 'exists') {
                echo '<p class="error">Emel Telah Wujud</p>';
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
                    <label for="nama_pengguna">Nama Admin:</label>
                    <input type="text" id="nama_pengguna" name="nama_pengguna" required>
                </div>
                <div class="form-group">
                    <label for="ic_pengguna">IC Admin:</label>
                    <input type="text" id="ic_pengguna" name="ic_pengguna" required>
                </div>
                <div class="form-group">
                    <label for="emel_pengguna">Email Admin:</label>
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
                    <label for="kategori_pengguna">Kategori:</label>
                    <select id="id_kategori_pengguna" name="id_kategori_pengguna" required>
                        <?php foreach ($categories as $category): ?>
                            <?php if ($category['id_kategori_pengguna'] == 'P0' || $category['id_kategori_pengguna'] == 'P7'): ?>
                                <option value="<?= $category['id_kategori_pengguna'] ?>"
                                    <?php if(isset($_POST['id_kategori_pengguna']) && $_POST['id_kategori_pengguna'] == $category['id_kategori_pengguna']) echo 'selected'; ?>>
                                    <?= $category['kategori_pengguna'] ?>                                  
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" name="create">Register</button>
                </div>
            </form>
        </div>
        <div id="tbladmin">
		<h2>Senarai Admin</h2>
          <?php
			try {
				// Prepare the SQL statement with placeholders for the email and the category IDs
				$stmt = $conn->prepare("SELECT * FROM tbl_pengguna WHERE emel_pengguna != :admin_email AND id_kategori_pengguna IN ('P0', 'P7')");
				
				// Bind the email value to the placeholder
				$stmt->bindParam(':admin_email', $admin['emel_pengguna']);
				
				// Execute the prepared statement
				$stmt->execute();
				
				// Fetch all results as an associative array
				$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
			} catch (PDOException $e) {
				echo "Error: " . $e->getMessage();
			}
			?>

            <table>
                <tr>
                    <th>Nama Pengguna</th>
                    <th>Email Pengguna</th>
                    <th>No Telefon</th>
                    <th>IC Pengguna</th>
                    <th></th>
                </tr>
                <?php foreach ($admins as $admin): ?>
                    <tr>
                        <td><?php echo $admin['nama_pengguna']?></td>
                        <td><?php echo $admin['emel_pengguna']?></td>
                        <td><?php echo $admin['no_tel']?></td>
                        <td><?php echo $admin['ic_pengguna']?></td>
                        <td>
                           <form action="crud_pengguna.php" method="post" style="display:inline;">
                                <input type="hidden" name="delete" value="1">
                                <input type="hidden" name="emel_pengguna" value="<?php echo $admin['emel_pengguna']; ?>">
                                <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this admin?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</body>
</html>
