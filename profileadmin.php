<?php
include_once "crud_pengguna.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Profil</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #e9ecef;
            font-family: Arial, sans-serif;
        }
        .container {
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            margin: 50px auto;
            max-width: 600px;
        }
        h1 {
            font-size: 2em;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
        }
        input[type="text"], input[type="password"], input[type="email"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div> <?php include_once "menu.php"?></div>
    <div class="container">
        <h1>Admin Profile</h1>
        <form method="post" action="crud_pengguna.php">
            <label for="nama_pengguna">Nama:</label><br>
            <input type="text" id="nama_pengguna" name="nama_pengguna" value="<?php echo $admin['nama_pengguna']; ?>" required><br>

            <label for="no_tel">No Telefon:</label><br>
            <input type="text" id="no_tel" name="no_tel" value="<?php echo $admin['no_tel']; ?>" required><br>
            <label for="ic_pengguna">Kad Pengenalan:</label><br>
            <input type="text" id="ic_pengguna" name="ic_pengguna" value="<?php echo $admin['ic_pengguna']; ?>" required><br>
            <input type="hidden" name="emel_pengguna" value="<?php echo $admin['emel_pengguna']; ?>">
            <input type="hidden" name="id_kategori_pengguna" value="<?php echo $admin['id_kategori_pengguna']; ?>">
			<label for="pass_pengguna">Kata laluan:</label><br>
            <input type="password" id="pass_pengguna" name="pass_pengguna" value="" required><br>
			
            <button type="submit" name="update">Update Profile</button>
        </form>
    </div>
</body>
</html>
