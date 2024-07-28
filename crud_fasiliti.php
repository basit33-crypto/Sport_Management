<?php
include_once 'database.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['create'])) {
        $fid = $_POST['fid'];
        $fname = $_POST['fname'];
        $fkapasiti = $_POST['fkapasiti'];
        $fkemudahan = $_POST['fkemudahan'];

        $stmt = $conn->prepare("INSERT INTO tbl_fasiliti (id_fasiliti, nama_fasiliti, kapasiti_fasiliti, ruang_fasiliti) VALUES (:fid, :fname, :fkapasiti, :fkemudahan)");
        $stmt->bindParam(':fid', $fid, PDO::PARAM_STR);
        $stmt->bindParam(':fname', $fname, PDO::PARAM_STR);
        $stmt->bindParam(':fkapasiti', $fkapasiti, PDO::PARAM_INT);
        $stmt->bindParam(':fkemudahan', $fkemudahan, PDO::PARAM_STR);
        $stmt->execute();
        
        // Handle the file upload
        if (isset($_FILES['gmbr_fasiliti']) && $_FILES['gmbr_fasiliti']['error'] == 0) {
            $fileTmpPath = $_FILES['gmbr_fasiliti']['tmp_name'];
            $fileName = $fid . '.jpg';
            $destination = 'gambar/gambarfasiliti/' . $fileName;

            if (move_uploaded_file($fileTmpPath, $destination)) {
                echo "File is successfully uploaded.";
            } else {
                echo "There was an error moving the uploaded file.";
            }
        } else {
            echo "No file uploaded or there was an upload error.";
        }

        header("Location: adminfasiliti.php");
        exit();
    }

    if (isset($_POST['update'])) {
        $fid = $_POST['fid'];
        $fname = $_POST['fname'];
        $fkapasiti = $_POST['fkapasiti'];
        $fkemudahan = $_POST['fkemudahan'];
        $oldfid = $_POST['oldfid'];

        $stmt = $conn->prepare("UPDATE tbl_fasiliti SET id_fasiliti = :fid, nama_fasiliti = :fname, kapasiti_fasiliti = :fkapasiti, ruang_fasiliti = :fkemudahan WHERE id_fasiliti = :oldfid");
        $stmt->bindParam(':fid', $fid, PDO::PARAM_STR);
        $stmt->bindParam(':fname', $fname, PDO::PARAM_STR);
        $stmt->bindParam(':fkapasiti', $fkapasiti, PDO::PARAM_INT);
        $stmt->bindParam(':fkemudahan', $fkemudahan, PDO::PARAM_STR);
        $stmt->bindParam(':oldfid', $oldfid, PDO::PARAM_STR);
        $stmt->execute();

        // Handle the file upload
        if (isset($_FILES['gmbr_fasiliti']) && $_FILES['gmbr_fasiliti']['error'] == 0) {
            $fileTmpPath = $_FILES['gmbr_fasiliti']['tmp_name'];
            $fileName = $fid . '.jpg';
            $destination = 'gambar/gambarfasiliti/' . $fileName;

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

        header("Location: adminfasiliti.php");
        exit();
    }

    if (isset($_GET['delete'])) {
        $fid = $_GET['delete'];

        // Delete the facility
        $stmt = $conn->prepare("DELETE FROM tbl_fasiliti WHERE id_fasiliti = :fid");
        $stmt->bindParam(':fid', $fid, PDO::PARAM_STR); 
        $stmt->execute();

        // Delete associated kemudahan and their images
        $stmt_kemudahan = $conn->prepare("SELECT id_kemudahan FROM tbl_kemudahan WHERE id_kemudahan LIKE CONCAT(:fid, 'K%')");
        $stmt_kemudahan->bindParam(':fid', $fid, PDO::PARAM_STR);
        $stmt_kemudahan->execute();
        $kemudahan_results = $stmt_kemudahan->fetchAll(PDO::FETCH_ASSOC);

        foreach ($kemudahan_results as $kemudahan) {
            $id_kemudahan = $kemudahan['id_kemudahan'];
            // Delete kemudahan
            $stmt_delete_kemudahan = $conn->prepare("DELETE FROM tbl_kemudahan WHERE id_kemudahan = :id_kemudahan");
            $stmt_delete_kemudahan->bindParam(':id_kemudahan', $id_kemudahan, PDO::PARAM_STR);
            $stmt_delete_kemudahan->execute();

            // Delete kemudahan images
            for ($i = 1; $i <= 3; $i++) {
                $target_file = "gambar/gambarkemudahan/" . $id_kemudahan . "G$i.jpg";
                if (file_exists($target_file)) {
                    unlink($target_file);
                }
            }
        }

        // Delete facility image
        $target_file = "gambar/gambarfasiliti/" . $fid . ".jpg";
        if (file_exists($target_file)) {
            unlink($target_file);
        }

        header("Location: adminfasiliti.php");
        exit();
    }

    if (isset($_GET['edit'])) {
        $fid = $_GET['edit'];
        $stmt = $conn->prepare("SELECT * FROM tbl_fasiliti WHERE id_fasiliti = :fid");
        $stmt->bindParam(':fid', $fid, PDO::PARAM_STR);
        $stmt->execute();
        $editrow = $stmt->fetch(PDO::FETCH_ASSOC);
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
