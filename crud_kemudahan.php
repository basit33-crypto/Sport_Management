<?php

include_once 'database.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['create'])) {
        $id_kemudahan = $_POST['id_kemudahan'];
        $nama_kemudahan = $_POST['nama_kemudahan'];
        $harga_warga = $_POST['harga_warga'];
        $harga_biasa = $_POST['harga_biasa'];
        $ruang = $_POST['ruang'];
        $perincian = $_POST['perincian'];

        $stmt = $conn->prepare("INSERT INTO tbl_kemudahan (id_kemudahan, nama_kemudahan, harga_warga, harga_biasa, ruang, perincian) VALUES (:id_kemudahan, :nama_kemudahan, :harga_warga, :harga_biasa, :ruang, :perincian)");

        $stmt->bindParam(':id_kemudahan', $id_kemudahan, PDO::PARAM_STR);
        $stmt->bindParam(':nama_kemudahan', $nama_kemudahan, PDO::PARAM_STR);
        $stmt->bindParam(':harga_warga', $harga_warga, PDO::PARAM_STR);
        $stmt->bindParam(':harga_biasa', $harga_biasa, PDO::PARAM_STR);
        $stmt->bindParam(':ruang', $ruang, PDO::PARAM_STR);
        $stmt->bindParam(':perincian', $perincian, PDO::PARAM_STR);
        
        $stmt->execute();

        // Handle file uploads
        for ($i = 1; $i <= 3; $i++) {
            if (isset($_FILES["image$i"]) && $_FILES["image$i"]["error"] == UPLOAD_ERR_OK) {
                $target_dir = "gambar/gambarkemudahan/";
                $target_file = $target_dir . $id_kemudahan . "G$i.jpg";
                move_uploaded_file($_FILES["image$i"]["tmp_name"], $target_file);
            }
        }

        header("Location: adminkemudahan.php");
        exit();
    }

    if (isset($_POST['update'])) {
        $id_kemudahan = $_POST['id_kemudahan'];
        $nama_kemudahan = $_POST['nama_kemudahan'];
        $harga_warga = $_POST['harga_warga'];
        $harga_biasa = $_POST['harga_biasa'];
        $ruang = $_POST['ruang'];
        $perincian = $_POST['perincian'];

        $stmt = $conn->prepare("UPDATE tbl_kemudahan SET nama_kemudahan = :nama_kemudahan, harga_warga = :harga_warga, harga_biasa = :harga_biasa, ruang = :ruang, perincian = :perincian WHERE id_kemudahan = :id_kemudahan");

        $stmt->bindParam(':nama_kemudahan', $nama_kemudahan, PDO::PARAM_STR);
        $stmt->bindParam(':harga_warga', $harga_warga, PDO::PARAM_STR);
        $stmt->bindParam(':harga_biasa', $harga_biasa, PDO::PARAM_STR);
        $stmt->bindParam(':ruang', $ruang, PDO::PARAM_STR);
        $stmt->bindParam(':perincian', $perincian, PDO::PARAM_STR);
        $stmt->bindParam(':id_kemudahan', $id_kemudahan, PDO::PARAM_STR);
        
        $stmt->execute();

        // Handle file uploads
        for ($i = 1; $i <= 3; $i++) {
            if (isset($_FILES["edit_image$i"]) && $_FILES["edit_image$i"]["error"] == UPLOAD_ERR_OK) {
                $target_dir = "gambar/gambarkemudahan/";
                $target_file = $target_dir . $id_kemudahan . "G$i.jpg";

                // Delete old file if it exists
                if (file_exists($target_file)) {
                    unlink($target_file);
                }

                move_uploaded_file($_FILES["edit_image$i"]["tmp_name"], $target_file);
            }
        }

        header("Location: adminkemudahan.php");
        exit();
    }

    if (isset($_GET['delete'])) {
        $id_kemudahan = $_GET['delete'];
        $stmt = $conn->prepare("DELETE FROM tbl_kemudahan WHERE id_kemudahan = :id_kemudahan");
        $stmt->bindParam(':id_kemudahan', $id_kemudahan, PDO::PARAM_STR); 
        $stmt->execute();

        // Delete associated images
        for ($i = 1; $i <= 3; $i++) {
            $target_file = "gambar/gambarkemudahan/" . $id_kemudahan . "G$i.jpg";
            if (file_exists($target_file)) {
                unlink($target_file);
            }
        }

        header("Location: adminkemudahan.php");
        exit();
    }

} catch(PDOException $e) { 
    echo "Error: " . $e->getMessage();
}

?>
