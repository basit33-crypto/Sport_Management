<?php
include_once 'database.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['createp'])) {
        $id_peralatan = $_POST['id_peralatan'];
        $nama_peralatan = $_POST['nama_peralatan'];
        $catatan = $_POST['catatan'];
        $harga_peralatan = $_POST['harga_peralatan'];

        $stmt = $conn->prepare("INSERT INTO tbl_peralatan (id_peralatan, nama_peralatan, catatan, harga_peralatan) VALUES (:id_peralatan, :nama_peralatan, :catatan, :harga_peralatan)");
        $stmt->bindParam(':id_peralatan', $id_peralatan, PDO::PARAM_STR);
        $stmt->bindParam(':nama_peralatan', $nama_peralatan, PDO::PARAM_STR);
        $stmt->bindParam(':catatan', $catatan, PDO::PARAM_STR);
        $stmt->bindParam(':harga_peralatan', $harga_peralatan, PDO::PARAM_STR);
        
        $stmt->execute();

        // Handle the file upload
        if (isset($_FILES['gmbr_peralatan']) && $_FILES['gmbr_peralatan']['error'] == 0) {
            $fileTmpPath = $_FILES['gmbr_peralatan']['tmp_name'];
            $fileName = $id_peralatan . '.jpg';
            $destination = 'gambar/gambarperalatan/' . $fileName;

            if (move_uploaded_file($fileTmpPath, $destination)) {
                echo "File is successfully uploaded.";
            } else {
                echo "There was an error moving the uploaded file.";
            }
        } else {
            echo "No file uploaded or there was an upload error.";
        }

        header("Location: adminperalatan.php");
        exit();
    }

    if (isset($_POST['updatep'])) {
        $id_peralatan = $_POST['id_peralatan'];
        $nama_peralatan = $_POST['nama_peralatan'];
        $catatan = $_POST['catatan'];
        $harga_peralatan = $_POST['harga_peralatan'];
        $old_id_peralatan = $_POST['old_id_peralatan'];

        $stmt = $conn->prepare("UPDATE tbl_peralatan SET id_peralatan = :id_peralatan, nama_peralatan = :nama_peralatan, catatan = :catatan, harga_peralatan = :harga_peralatan, kuantiti_peralatan = :kuantiti_peralatan WHERE id_peralatan = :old_id_peralatan");
        $stmt->bindParam(':id_peralatan', $id_peralatan, PDO::PARAM_STR);
        $stmt->bindParam(':nama_peralatan', $nama_peralatan, PDO::PARAM_STR);
        $stmt->bindParam(':catatan', $catatan, PDO::PARAM_STR);
        $stmt->bindParam(':harga_peralatan', $harga_peralatan, PDO::PARAM_STR);
		$stmt->bindParam(':kuantiti_peralatan', $kuantiti_peralatan, PDO::PARAM_INT);
        $stmt->bindParam(':old_id_peralatan', $old_id_peralatan, PDO::PARAM_STR);
        $stmt->execute();

        // Handle the file upload
        if (isset($_FILES['gmbr_peralatan']) && $_FILES['gmbr_peralatan']['error'] == 0) {
            $fileTmpPath = $_FILES['gmbr_peralatan']['tmp_name'];
            $fileName = $id_peralatan . '.jpg';
            $destination = 'gambar/gambarperalatan/' . $fileName;

            // Delete the old image if it exists
            if (file_exists($destination)) {
                unlink($destination);
            }

            if (move_uploaded_file($fileTmpPath, $destination)) {
                echo "File is successfully uploaded.";
            } else {
                echo "There was an error moving the uploaded file.";
            }
        }

        header("Location: adminperalatan.php");
        exit();
    }

    if (isset($_GET['deletep'])) {
        $id_peralatan = $_GET['deletep'];

        // Delete the peralatan
        $stmt = $conn->prepare("DELETE FROM tbl_peralatan WHERE id_peralatan = :id_peralatan");
        $stmt->bindParam(':id_peralatan', $id_peralatan, PDO::PARAM_STR);
        $stmt->execute();

        // Delete the image
        $target_file = "gambar/gambarperalatan/" . $id_peralatan . ".jpg";
        if (file_exists($target_file)) {
            unlink($target_file);
        }

        header("Location: adminperalatan.php");
        exit();
    }

    if (isset($_GET['editp'])) {
        $id_peralatan = $_GET['editp'];
        $stmt = $conn->prepare("SELECT * FROM tbl_peralatan WHERE id_peralatan = :id_peralatan");
        $stmt->bindParam(':id_peralatan', $id_peralatan, PDO::PARAM_STR);
        $stmt->execute();
        $editrow = $stmt->fetch(PDO::FETCH_ASSOC);
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
