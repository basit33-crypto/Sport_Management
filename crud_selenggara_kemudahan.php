<?php
include_once 'database.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle the create operation
    if (isset($_POST['create'])) {
        $id_selenggara_kemudahan = $_POST['id_selenggara_kemudahan'];
        $id_kemudahan = $_POST['id_kemudahan'];
        $hrg_selenggara = $_POST['hrg_selenggara'];
        $catatan = $_POST['catatan'];
        $emel_admin = $_POST['emel_admin'];
        $tarikh_selenggara = date('Y-m-d H:i:s'); // Get the current date and time
		 $jumlah_selenggara = $_POST['jumlah_selenggara'];
        // Insert into tbl_selenggara_kemudahan
        $stmt = $conn->prepare("INSERT INTO tbl_selenggara_kemudahan (id_selenggara_kemudahan, id_kemudahan, hrg_selenggara, catatan, emel_admin, tarikh_selenggara,jumlah_selenggara) 
                                VALUES (:id_selenggara_kemudahan, :id_kemudahan, :hrg_selenggara, :catatan, :emel_admin, :tarikh_selenggara,:jumlah_selenggara)");
        $stmt->bindParam(':id_selenggara_kemudahan', $id_selenggara_kemudahan, PDO::PARAM_STR);
        $stmt->bindParam(':id_kemudahan', $id_kemudahan, PDO::PARAM_STR);
        $stmt->bindParam(':hrg_selenggara', $hrg_selenggara, PDO::PARAM_STR);
        $stmt->bindParam(':catatan', $catatan, PDO::PARAM_STR);
        $stmt->bindParam(':emel_admin', $emel_admin, PDO::PARAM_STR);
        $stmt->bindParam(':tarikh_selenggara', $tarikh_selenggara, PDO::PARAM_STR);
		$stmt->bindParam(':jumlah_selenggara', $jumlah_selenggara, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: adminkemudahan.php");
        exit();
    }

    // Handle the update operation
    if (isset($_POST['update'])) {
        $id_selenggara_kemudahan = $_POST['id_selenggara_kemudahan'];
        $id_kemudahan = $_POST['id_kemudahan'];
        $hrg_selenggara = $_POST['hrg_selenggara'];
        $catatan = $_POST['catatan'];
        $emel_admin = $_POST['emel_admin'];
        $tarikh_selenggara = $_POST['tarikh_selenggara'];

        $stmt = $conn->prepare("UPDATE tbl_selenggara_kemudahan SET id_kemudahan = :id_kemudahan, hrg_selenggara = :hrg_selenggara, catatan = :catatan, emel_admin = :emel_admin, tarikh_selenggara = :tarikh_selenggara  WHERE id_selenggara_kemudahan = :id_selenggara_kemudahan");
        $stmt->bindParam(':id_selenggara_kemudahan', $id_selenggara_kemudahan, PDO::PARAM_STR);
        $stmt->bindParam(':id_kemudahan', $id_kemudahan, PDO::PARAM_STR);
        $stmt->bindParam(':hrg_selenggara', $hrg_selenggara, PDO::PARAM_STR);
        $stmt->bindParam(':catatan', $catatan, PDO::PARAM_STR);
        $stmt->bindParam(':emel_admin', $emel_admin, PDO::PARAM_STR);
        $stmt->bindParam(':tarikh_selenggara', $tarikh_selenggara, PDO::PARAM_STR);
        $stmt->execute();

        header("Location: adminkemudahan.php");
        exit();
    }

    // Handle the delete operation
    if (isset($_GET['delete'])) {
        $id_selenggara_kemudahan = $_GET['delete'];

        $stmt = $conn->prepare("DELETE FROM tbl_selenggara_kemudahan WHERE id_selenggara_kemudahan = :id_selenggara_kemudahan");
        $stmt->bindParam(':id_selenggara_kemudahan', $id_selenggara_kemudahan, PDO::PARAM_STR);
        $stmt->execute();

        header("Location: selenggara_kemudahan.php");
        exit();
    }

    // Handle the edit operation
    if (isset($_GET['edit'])) {
        $id_selenggara_kemudahan = $_GET['edit'];

        $stmt = $conn->prepare("SELECT * FROM tbl_selenggara_kemudahan WHERE id_selenggara_kemudahan = :id_selenggara_kemudahan");
        $stmt->bindParam(':id_selenggara_kemudahan', $id_selenggara_kemudahan, PDO::PARAM_STR);
        $stmt->execute();

        $editrow = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fetch all records for display
    $stmt = $conn->prepare("SELECT * FROM tbl_selenggara_kemudahan");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
