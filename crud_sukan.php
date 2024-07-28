<?php

include_once 'database.php';

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create operation
if (isset($_POST['create'])) {
    try {
        $id_sukan = $_POST['id_sukan'];
        $nama_sukan = $_POST['nama_sukan'];

        $stmt = $conn->prepare("INSERT INTO tbl_sukan (id_sukan, nama_sukan) VALUES (:id_sukan, :nama_sukan)");
        $stmt->bindParam(':id_sukan', $id_sukan, PDO::PARAM_STR);
        $stmt->bindParam(':nama_sukan', $nama_sukan, PDO::PARAM_STR);
        $stmt->execute();
        header("Location: adminperalatan.php");
        exit();
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Update operation
if (isset($_POST['update'])) {
    try {
        $id_sukan = $_POST['id_sukan'];
        $nama_sukan = $_POST['nama_sukan'];
        $old_id_sukan = $_POST['old_id_sukan'];

        $stmt = $conn->prepare("UPDATE tbl_sukan SET id_sukan = :id_sukan, nama_sukan = :nama_sukan WHERE id_sukan = :old_id_sukan");
        $stmt->bindParam(':id_sukan', $id_sukan, PDO::PARAM_STR);
        $stmt->bindParam(':nama_sukan', $nama_sukan, PDO::PARAM_STR);
        $stmt->bindParam(':old_id_sukan', $old_id_sukan, PDO::PARAM_STR);
        $stmt->execute();
        header("Location: adminperalatan.php");
        exit();
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Delete operation
if (isset($_GET['delete'])) {
    try {
        $id_sukan = $_GET['delete'];

        // Delete related peralatan images
        $stmt = $conn->prepare("SELECT id_peralatan FROM tbl_peralatan WHERE id_peralatan LIKE :id_sukan");
        $likeParam = $id_sukan . '%';
        $stmt->bindParam(':id_sukan', $likeParam, PDO::PARAM_STR);
        $stmt->execute();
        $peralatan = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($peralatan as $item) {
            $imagePath = 'gambar/gambarperalatan/' . $item['id_peralatan'] . '.jpg';
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        // Delete related peralatan
        $stmt = $conn->prepare("DELETE FROM tbl_peralatan WHERE id_peralatan LIKE :id_sukan");
        $stmt->bindParam(':id_sukan', $likeParam, PDO::PARAM_STR);
        $stmt->execute();

        // Delete sukan
        $stmt = $conn->prepare("DELETE FROM tbl_sukan WHERE id_sukan = :id_sukan");
        $stmt->bindParam(':id_sukan', $id_sukan, PDO::PARAM_STR);
        $stmt->execute();

        header("Location: adminperalatan.php");
        exit();
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Edit operation (fetching data for editing)
if (isset($_GET['edit'])) {
    try {
        $id_sukan = $_GET['edit'];
        $stmt = $conn->prepare("SELECT * FROM tbl_sukan WHERE id_sukan = :id_sukan");
        $stmt->bindParam(':id_sukan', $id_sukan, PDO::PARAM_STR);
        $stmt->execute();
        $editrow = $stmt->fetch(PDO::FETCH_ASSOC);
        header("Location: adminperalatan.php");
        exit();
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

$conn = null;
?>
