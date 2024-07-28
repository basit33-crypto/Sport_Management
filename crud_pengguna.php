<?php
include_once 'database.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	//admin
	 if (isset($_POST['create'])) {
        $nama_pengguna = $_POST['nama_pengguna'];
        $ic_pengguna = $_POST['ic_pengguna'];
        $emel_pengguna = $_POST['emel_pengguna'];
        $no_tel = $_POST['no_tel'];
        $pass_pengguna = password_hash($_POST['pass_pengguna'], PASSWORD_DEFAULT); // Hash the password
        $id_kategori_pengguna = $_POST['id_kategori_pengguna'];

        // Check if the email already exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_pengguna WHERE emel_pengguna = :emel_pengguna");
        $stmt->bindParam(':emel_pengguna', $emel_pengguna);
        $stmt->execute();
        $emailExists = $stmt->fetchColumn();

        if ($emailExists) {
            // Redirect to daftarpengguna.php with an error message
            header("Location: daftaradmin.php?error=exists");
            exit();
        } else {
            // Insert the new user
            $stmt = $conn->prepare("INSERT INTO tbl_pengguna (nama_pengguna, ic_pengguna, emel_pengguna, no_tel, pass_pengguna, id_kategori_pengguna) VALUES (:nama_pengguna, :ic_pengguna, :emel_pengguna, :no_tel, :pass_pengguna, :id_kategori_pengguna)");

            $stmt->bindParam(':nama_pengguna', $nama_pengguna, PDO::PARAM_STR);
            $stmt->bindParam(':ic_pengguna', $ic_pengguna, PDO::PARAM_STR);
            $stmt->bindParam(':emel_pengguna', $emel_pengguna, PDO::PARAM_STR);
            $stmt->bindParam(':no_tel', $no_tel, PDO::PARAM_STR);
            $stmt->bindParam(':pass_pengguna', $pass_pengguna, PDO::PARAM_STR);
            $stmt->bindParam(':id_kategori_pengguna', $id_kategori_pengguna, PDO::PARAM_STR);

            $stmt->execute();
            header("Location: daftaradmin.php");
            exit();
        }
    }	 if (isset($_POST['createp'])) {
        $nama_pengguna = $_POST['nama_pengguna'];
        $ic_pengguna = $_POST['ic_pengguna'];
        $emel_pengguna = $_POST['emel_pengguna'];
        $no_tel = $_POST['no_tel'];
        $pass_pengguna = password_hash($_POST['pass_pengguna'], PASSWORD_DEFAULT); // Hash the password
        $id_kategori_pengguna = $_POST['id_kategori_pengguna'];

        // Check if the email already exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_pengguna WHERE emel_pengguna = :emel_pengguna");
        $stmt->bindParam(':emel_pengguna', $emel_pengguna);
        $stmt->execute();
        $emailExists = $stmt->fetchColumn();

        if ($emailExists) {
            // Redirect to daftarpengguna.php with an error message
            header("Location: daftarpengguna.php?error=exists");
            exit();
        } else {
            // Insert the new user
            $stmt = $conn->prepare("INSERT INTO tbl_pengguna (nama_pengguna, ic_pengguna, emel_pengguna, no_tel, pass_pengguna, id_kategori_pengguna) VALUES (:nama_pengguna, :ic_pengguna, :emel_pengguna, :no_tel, :pass_pengguna, :id_kategori_pengguna)");

            $stmt->bindParam(':nama_pengguna', $nama_pengguna, PDO::PARAM_STR);
            $stmt->bindParam(':ic_pengguna', $ic_pengguna, PDO::PARAM_STR);
            $stmt->bindParam(':emel_pengguna', $emel_pengguna, PDO::PARAM_STR);
            $stmt->bindParam(':no_tel', $no_tel, PDO::PARAM_STR);
            $stmt->bindParam(':pass_pengguna', $pass_pengguna, PDO::PARAM_STR);
            $stmt->bindParam(':id_kategori_pengguna', $id_kategori_pengguna, PDO::PARAM_STR);

            $stmt->execute();
            header("Location: login.php");
            exit();
        }
    }


    if (isset($_POST['update'])) {
        $nama_pengguna = $_POST['nama_pengguna'];
        $ic_pengguna = $_POST['ic_pengguna'];
        $emel_pengguna = $_POST['emel_pengguna'];
        $no_tel = $_POST['no_tel'];
        $pass_pengguna = password_hash($_POST['pass_pengguna'], PASSWORD_DEFAULT); // Hash the password
        $id_kategori_pengguna = $_POST['id_kategori_pengguna'];

        $stmt = $conn->prepare("UPDATE tbl_pengguna SET nama_pengguna = :nama_pengguna, ic_pengguna = :ic_pengguna, no_tel = :no_tel, pass_pengguna = :pass_pengguna, id_kategori_pengguna = :id_kategori_pengguna WHERE emel_pengguna = :emel_pengguna");

        $stmt->bindParam(':nama_pengguna', $nama_pengguna, PDO::PARAM_STR);
        $stmt->bindParam(':ic_pengguna', $ic_pengguna, PDO::PARAM_STR);
        $stmt->bindParam(':emel_pengguna', $emel_pengguna, PDO::PARAM_STR);
        $stmt->bindParam(':no_tel', $no_tel, PDO::PARAM_STR);
        $stmt->bindParam(':pass_pengguna', $pass_pengguna, PDO::PARAM_STR);
        $stmt->bindParam(':id_kategori_pengguna', $id_kategori_pengguna, PDO::PARAM_STR);

        $stmt->execute();
        header("Location: profileadmin.php");
        exit();
    }

    if (isset($_POST['delete'])) {
        $emel_pengguna = $_POST['emel_pengguna'];
        $stmt = $conn->prepare("DELETE FROM tbl_pengguna WHERE emel_pengguna = :emel_pengguna");
        $stmt->bindParam(':emel_pengguna', $emel_pengguna, PDO::PARAM_STR);
        $stmt->execute();
        header("Location: daftaradmin.php");
        exit();
    }

    if (isset($_GET['edit'])) {
        $emel_pengguna = $_GET['edit'];
        $stmt = $conn->prepare("SELECT * FROM tbl_pengguna WHERE emel_pengguna = :emel_pengguna");
        $stmt->bindParam(':emel_pengguna', $emel_pengguna, PDO::PARAM_STR);
        $stmt->execute();
        $editrow = $stmt->fetch(PDO::FETCH_ASSOC);
    }

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
