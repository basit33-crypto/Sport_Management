<?php
session_start();
include_once 'database.php';

function convertTo24Hour($timeString) {
    $timeString = str_replace(['Ptg', 'Mlm'], ['PM', 'PM'], $timeString);
    $timeString = str_replace(['Pagi', 'Tghr'], ['AM', 'PM'], $timeString);
    $timeString = preg_replace('/\s+/', '', $timeString);
    $time = DateTime::createFromFormat('h:ia', $timeString);
    if (!$time) {
        $time = DateTime::createFromFormat('hia', $timeString);
    }
    return $time ? $time->format('H:i') : 'Invalid time format';
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create
    if (isset($_POST['create'])) {
        // Convert time inputs to 24-hour format
        $masa_mula = convertTo24Hour($_POST['masa_mula']);
        $masa_tamat = convertTo24Hour($_POST['masa_tamat']);

        // Insert into tbl_pinjaman_peralatan
        foreach ($_POST['id_pinjaman_peralatan'] as $id_peralatan => $dates) {
            foreach ($dates as $date => $id_pinjaman_peralatan) {
                $bil_pinjam = $_POST['bilangan'][$id_peralatan][$date];
                $tarikh_pinjam = $_POST['tarikh'][$id_peralatan][$date];
                if ($bil_pinjam > 0) {
                    $stmt = $conn->prepare("INSERT INTO tbl_pinjaman_peralatan (id_pinjaman_peralatan, id_peralatan, id_order, bil_pinjam, tarikh_pinjam) VALUES (:id_pinjaman_peralatan, :id_peralatan, :id_order, :bil_pinjam, :tarikh_pinjam)");
                    $stmt->bindParam(':id_pinjaman_peralatan', $id_pinjaman_peralatan, PDO::PARAM_STR);
                    $stmt->bindParam(':id_peralatan', $id_peralatan, PDO::PARAM_STR);
                    $stmt->bindParam(':id_order', $_POST['id_order'], PDO::PARAM_STR);
                    $stmt->bindParam(':bil_pinjam', $bil_pinjam, PDO::PARAM_INT);
                    $stmt->bindParam(':tarikh_pinjam', $tarikh_pinjam, PDO::PARAM_STR);
                    $stmt->execute();
                }
            }
        }

        // Insert into tbl_order
        $id_order = $_POST['id_order'];
        $emel_pengguna = $_SESSION['emel_pengguna'];
        $tarikh_mula = $_POST['tarikh_mula'];
        $tarikh_tamat = $_POST['tarikh_tamat'];
        $jumlah_harga = $_POST['jumlah_harga'];
        $aktiviti = $_POST['aktiviti'];
        $status_bayaran = $_POST['status_bayaran'];
        $status_pinjaman = $_POST['status_pinjaman'];
        $tujuan = $_POST['tujuan'];

        $stmt = $conn->prepare("INSERT INTO tbl_order (id_order, emel_pengguna, tarikh_mula, tarikh_tamat, masa_mula, masa_tamat, jumlah_harga, aktiviti, status_bayaran, status_pinjaman, tujuan) VALUES (:id_order, :emel_pengguna, :tarikh_mula, :tarikh_tamat, :masa_mula, :masa_tamat, :jumlah_harga, :aktiviti, :status_bayaran, :status_pinjaman, :tujuan)");
        $stmt->bindParam(':id_order', $id_order, PDO::PARAM_STR);
        $stmt->bindParam(':emel_pengguna', $emel_pengguna, PDO::PARAM_STR);
        $stmt->bindParam(':tarikh_mula', $tarikh_mula, PDO::PARAM_STR);
        $stmt->bindParam(':tarikh_tamat', $tarikh_tamat, PDO::PARAM_STR);
        $stmt->bindParam(':masa_mula', $masa_mula, PDO::PARAM_STR);
        $stmt->bindParam(':masa_tamat', $masa_tamat, PDO::PARAM_STR);
        $stmt->bindParam(':jumlah_harga', $jumlah_harga, PDO::PARAM_STR);
        $stmt->bindParam(':aktiviti', $aktiviti, PDO::PARAM_STR);
        $stmt->bindParam(':status_bayaran', $status_bayaran, PDO::PARAM_STR);
        $stmt->bindParam(':status_pinjaman', $status_pinjaman, PDO::PARAM_STR);
        $stmt->bindParam(':tujuan', $tujuan, PDO::PARAM_STR);
        $stmt->execute();

        // Insert into tbl_pinjaman_kemudahan
        $id_kemudahan = $_POST['id_kemudahan'];
        $id_pinjaman_kemudahan = bin2hex(random_bytes(6));

        $stmt = $conn->prepare("INSERT INTO tbl_pinjaman_kemudahan (id_pinjaman_kemudahan, id_kemudahan, id_order) VALUES (:id_pinjaman_kemudahan, :id_kemudahan, :id_order)");
        $stmt->bindParam(':id_pinjaman_kemudahan', $id_pinjaman_kemudahan, PDO::PARAM_STR);
        $stmt->bindParam(':id_kemudahan', $id_kemudahan, PDO::PARAM_STR);
        $stmt->bindParam(':id_order', $id_order, PDO::PARAM_STR);
        $stmt->execute();

        header("Location: tempahfasiliti.php?id_order=$id_order");
        exit();
    }

    // Update
    if (isset($_POST['update'])) {
        $masa_mula = convertTo24Hour($_POST['masa_mula']);
        $masa_tamat = convertTo24Hour($_POST['masa_tamat']);

        $id_order = $_POST['id_order'];
        $emel_pengguna = $_POST['emel_pengguna'];
        $tarikh_mula = $_POST['tarikh_mula'];
        $tarikh_tamat = $_POST['tarikh_tamat'];
        $jumlah_harga = $_POST['jumlah_harga'];
        $aktiviti = $_POST['aktiviti'];
        $status_bayaran = $_POST['status_bayaran'];
        $status_pinjaman = $_POST['status_pinjaman'];
        $tujuan = $_POST['tujuan'];

        $stmt = $conn->prepare("UPDATE tbl_order SET emel_pengguna = :emel_pengguna, tarikh_mula = :tarikh_mula, tarikh_tamat = :tarikh_tamat, masa_mula = :masa_mula, masa_tamat = :masa_tamat, jumlah_harga = :jumlah_harga, aktiviti = :aktiviti, status_bayaran = :status_bayaran, status_pinjaman = :status_pinjaman, tujuan = :tujuan WHERE id_order = :id_order");
        $stmt->bindParam(':id_order', $id_order, PDO::PARAM_STR);
        $stmt->bindParam(':emel_pengguna', $emel_pengguna, PDO::PARAM_STR);
        $stmt->bindParam(':tarikh_mula', $tarikh_mula, PDO::PARAM_STR);
        $stmt->bindParam(':tarikh_tamat', $tarikh_tamat, PDO::PARAM_STR);
        $stmt->bindParam(':masa_mula', $masa_mula, PDO::PARAM_STR);
        $stmt->bindParam(':masa_tamat', $masa_tamat, PDO::PARAM_STR);
        $stmt->bindParam(':jumlah_harga', $jumlah_harga, PDO::PARAM_STR);
        $stmt->bindParam(':aktiviti', $aktiviti, PDO::PARAM_STR);
        $stmt->bindParam(':status_bayaran', $status_bayaran, PDO::PARAM_STR);
        $stmt->bindParam(':status_pinjaman', $status_pinjaman, PDO::PARAM_STR);
        $stmt->bindParam(':tujuan', $tujuan, PDO::PARAM_STR);
        $stmt->execute();
        header("Location: tempahfasiliti.php?id_order=$id_order");
        exit();
    }

    // Pengguna Delete
    if (isset($_POST['delete']) && isset($_POST['id_order'])) {
        $id_order = $_POST['id_order'];

        $conn->beginTransaction();

        try {
            $stmt = $conn->prepare("DELETE FROM tbl_pinjaman_kemudahan WHERE id_order = :id_order");
            $stmt->bindParam(':id_order', $id_order, PDO::PARAM_STR);
            $stmt->execute();

            $stmt = $conn->prepare("DELETE FROM tbl_pinjaman_peralatan WHERE id_order = :id_order");
            $stmt->bindParam(':id_order', $id_order, PDO::PARAM_STR);
            $stmt->execute();

            $stmt = $conn->prepare("DELETE FROM tbl_order WHERE id_order = :id_order");
            $stmt->bindParam(':id_order', $id_order, PDO::PARAM_STR);
            $stmt->execute();

            $conn->commit();
        } catch (PDOException $e) {
            $conn->rollBack();
            throw $e;
        }

        header("Location: senaraitempahan.php");
        exit();
    }

    // Pengguna bayar
    if (isset($_POST['bayar']) && $_POST['bayar'] == 'bayar') {
        $id_order = $_POST['id_order'];

        $stmt = $conn->prepare("UPDATE tbl_order SET status_bayaran = 'Selesai' WHERE id_order = :id_order");
        $stmt->bindParam(':id_order', $id_order, PDO::PARAM_STR);
        $stmt->execute();
        header("Location: senaraitempahan.php");
        exit();
    }

    // Pengguna batalkan order
    if (isset($_POST['cancel'])) {
        $id_order = $_POST['id_order'];

        $stmt = $conn->prepare("UPDATE tbl_order SET status_pinjaman = 'Batal' WHERE id_order = :id_order");
        $stmt->bindParam(':id_order', $id_order, PDO::PARAM_STR);
        $stmt->execute();
        header("Location: senaraitempahan.php");
        exit();
    }

    // Pentadbir tolak/terima
    if (isset($_POST['id_order']) && isset($_POST['status_pinjaman'])) {
        $id_order = $_POST['id_order'];
        $status_pinjaman = $_POST['status_pinjaman'];

        $stmt = $conn->prepare("UPDATE tbl_order SET status_pinjaman = :status_pinjaman WHERE id_order = :id_order");
        $stmt->bindParam(':status_pinjaman', $status_pinjaman, PDO::PARAM_STR);
        $stmt->bindParam(':id_order', $id_order, PDO::PARAM_STR);
        $stmt->execute();
        header("Location: admintempahan.php");
        exit();
    }

    // Edit
    if (isset($_GET['edit'])) {
        $id_order = $_GET['edit'];
        $stmt = $conn->prepare("SELECT * FROM tbl_order WHERE id_order = :id_order");
        $stmt->bindParam(':id_order', $id_order, PDO::PARAM_STR);
        $stmt->execute();
        $editrow = $stmt->fetch(PDO::FETCH_ASSOC);
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
?>
