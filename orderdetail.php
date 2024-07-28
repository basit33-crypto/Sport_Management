<?php
include_once 'database.php';
include_once 'crud_order.php';
try {
    // Connect to the database
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the order ID from POST request
    $id_order = $_POST['id_order'];

    // Prepare and execute the queries
    $stmt_order = $conn->prepare("SELECT * FROM tbl_order WHERE id_order = :id_order");
    $stmt_order->execute([':id_order' => $id_order]);
    $order = $stmt_order->fetch(PDO::FETCH_ASSOC);

    $stmt_kemudahan = $conn->prepare("SELECT * FROM tbl_pinjaman_kemudahan WHERE id_order = :id_order");
    $stmt_kemudahan->execute([':id_order' => $id_order]);
    $kemudahan = $stmt_kemudahan->fetchAll(PDO::FETCH_ASSOC);

    $stmt_peralatan = $conn->prepare("SELECT * FROM tbl_pinjaman_peralatan WHERE id_order = :id_order");
    $stmt_peralatan->execute([':id_order' => $id_order]);
    $peralatan = $stmt_peralatan->fetchAll(PDO::FETCH_ASSOC);

    $stmt_pengguna = $conn->prepare("SELECT * FROM tbl_pengguna WHERE emel_pengguna = :emel_pengguna");
    $stmt_pengguna->execute([':emel_pengguna' => $order['emel_pengguna']]);
    $pengguna = $stmt_pengguna->fetch(PDO::FETCH_ASSOC);

    // Fetch details for kemudahan
    $kemudahan_details = [];
    foreach ($kemudahan as $k) {
        $stmt = $conn->prepare("SELECT * FROM tbl_kemudahan WHERE id_kemudahan = :id_kemudahan");
        $stmt->execute([':id_kemudahan' => $k['id_kemudahan']]);
        $kemudahan_details[] = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fetch details for peralatan
    $peralatan_details = [];
    foreach ($peralatan as $p) {
        $stmt = $conn->prepare("SELECT * FROM tbl_peralatan WHERE id_peralatan = :id_peralatan");
        $stmt->execute([':id_peralatan' => $p['id_peralatan']]);
        $peralatan_details[] = $stmt->fetch(PDO::FETCH_ASSOC);
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        .container {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;      
            width: 1000px;
        }
        .button-container {
            text-align: right;
            margin-top: 20px;
        }
    
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
		
    </style>
    <script>
        function updateStatus(status) {
            document.getElementById('status_pinjaman').value = status;
            document.getElementById('statusForm').submit();
        }
    </script>
</head>
<body>
<div><?php include_once "menu.php" ?></div>

<div class="container">
<h2><u>Maklumat Tempahan</u></h2>
    <div class="row">
        <div class="col-md-12">
            <h2>Maklumat Pengguna</h2>
            <table class="table table-bordered">
                <thead>
                    <th>Emel Pengguna</th>
                    <th>Nama Pengguna</th>
                    <th>No Telefon</th>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo htmlspecialchars($pengguna['emel_pengguna']); ?></td>
                        <td><?php echo htmlspecialchars($pengguna['nama_pengguna']); ?></td>
                        <td><?php echo htmlspecialchars($pengguna['no_tel']); ?></td>
                    </tr>
                </tbody>
            </table>
            <h2>Maklumat Kemudahan</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID Kemudahan</th>
                        <th>Nama Kemudahan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kemudahan_details as $k): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($k['id_kemudahan']); ?></td>
                            <td><?php echo htmlspecialchars($k['nama_kemudahan']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h2>Maklumat Peralatan</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tarikh Pinjam</th>
                        <th>ID Peralatan</th>
                        <th>Nama Peralatan</th>
                        <th>Catatan</th>
                        <th>Harga Peralatan</th>
                        <th>Bil Pinjam</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($peralatan as $p): ?>
                        <?php 
                        $detail = array_filter($peralatan_details, function($item) use ($p) {
                            return $item['id_peralatan'] == $p['id_peralatan'];
                        });
                        $detail = reset($detail); // Get the first element of the filtered array
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($p['tarikh_pinjam']); ?></td>
                            <td><?php echo htmlspecialchars($detail['id_peralatan']); ?></td>
                            <td><?php echo htmlspecialchars($detail['nama_peralatan']); ?></td>
                            <td><?php echo htmlspecialchars($detail['catatan']); ?></td>
                            <td>RM<?php echo htmlspecialchars($detail['harga_peralatan']); ?></td>
                            <td><?php echo htmlspecialchars($p['bil_pinjam']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h2>Maklumat Peminjaman</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID Order</th>
                        <th>Tarikh Mula</th>
                        <th>Tarikh Tamat</th>
                        <th>Masa Mula</th>
                        <th>Masa Tamat</th>
                        <th>Aktiviti</th>
                        <th>Jumlah</th>
                        <th>Status Bayaran</th>
                        <th>Status Pinjaman</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo htmlspecialchars($order['id_order']); ?></td>
                        <td><?php echo htmlspecialchars($order['tarikh_mula']); ?></td>
                        <td><?php echo htmlspecialchars($order['tarikh_tamat']); ?></td>
						<td><?php echo htmlspecialchars(date("g:i A", strtotime($order['masa_mula']))); ?></td>
						<td><?php echo htmlspecialchars(date("g:i A", strtotime($order['masa_tamat']))); ?></td>
                        <td><?php echo htmlspecialchars($order['aktiviti']); ?></td>
                        <td>RM<?php echo htmlspecialchars($order['jumlah_harga']); ?></td>
                        <td><?php echo htmlspecialchars($order['status_bayaran']); ?></td>
                        <td><?php echo htmlspecialchars($order['status_pinjaman']); ?></td>

                    </tr>
                </tbody>
            </table>
            <div class="button-container">
                <form id="statusForm" action="crud_order.php" method="post">
                    <input type="hidden" name="id_order" value="<?php echo htmlspecialchars($id_order); ?>">
                    <input type="hidden" name="status_pinjaman" id="status_pinjaman">
                    <button type="button" class="btn btn-danger btn-sm" onclick="updateStatus('Ditolak')">Tolak</button>
                    <button type="button" class="btn btn-info btn-sm" onclick="updateStatus('Lulus')">Terima</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
