<?php
include_once 'database.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['emel_pengguna']) && isset($_POST['pass_pengguna'])) {
        $emel_pengguna = $_POST['emel_pengguna'];
        $pass_pengguna = $_POST['pass_pengguna'];

        $stmt = $conn->prepare("SELECT * FROM tbl_pengguna WHERE emel_pengguna = :emel_pengguna");
        $stmt->bindParam(':emel_pengguna', $emel_pengguna, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // User found, verify the password
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($pass_pengguna, $user['pass_pengguna'])) {
                // Password is correct, start a session and redirect based on user category
                session_start();
                $_SESSION['emel_pengguna'] = $user['emel_pengguna'];
                $_SESSION['id_kategori_pengguna'] = $user['id_kategori_pengguna'];

                if ($user['id_kategori_pengguna'] == 'P0' || $user['id_kategori_pengguna'] == 'P7') {
                    header("Location: adminfasiliti.php");
                } else {
                    header("Location: senaraifasiliti.php");
                }
                exit();
            } else {
                // Password is incorrect, redirect back to login with error
                header("Location: login.php?error=invalid");
                exit();
            }
        } else {
            // User not found, redirect back to login with error
            header("Location: login.php?error=invalid");
            exit();
        }
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
