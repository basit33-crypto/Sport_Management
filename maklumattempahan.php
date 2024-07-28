<?php
include_once 'database.php';
include_once 'crud_order.php';
try {
    // Connect to the database
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the order ID from GET request
    $id_order = $_GET['id_order'];

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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        #resit {
            margin-top: 10px;
            margin-bottom: 40px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            width: 900px;
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
        .btn-right {
            float: right;
            margin-left: 10px;
        }
		@media print {
		  body {
			visibility: hidden;
		  }
		  #resit {
			visibility: visible;
			position:fixed;
			left: 0;
			top: 0;
			margin: 5px;
			width:90%;
		  }.button-container {
                display: none;
            }
		}
    </style>
</head>
<body>
<div class="container" style="padding-bottom:5px;width:1000px">
    <img src="status.jpg" class="img-responsive">
</div>
<div class="container" style="padding-bottom:5px;width:1000px">
    <?php include_once 'menupengguna.php'; ?>
</div>

<div class="container" id="resit">
    <div class="row">
        <div class="col-md-12">
            <h2>Maklumat Pengguna</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Emel Pengguna</th>
                        <th>Nama Pengguna</th>
                        <th>No Telefon</th>
                    </tr>
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
                        <th>Nama Kemudahan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kemudahan_details as $k): ?>
                        <tr>
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
                        <th>Nama Peralatan</th>
                        <th>Catatan</th>
                     
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
                            <td><?php echo htmlspecialchars($detail['nama_peralatan']); ?></td>
                            <td><?php echo htmlspecialchars($detail['catatan']); ?></td>
                          
                            <td><?php echo htmlspecialchars($p['bil_pinjam']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        
            <h2>Maklumat Peminjaman</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                       
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
        </div>   
        <div class="button-container">
            <form method="POST" action="crud_order.php">
                <input type="hidden" name="id_order" value="<?php echo htmlspecialchars($order['id_order']); ?>">
                <input type="hidden" name="bayar" value="bayar">
                <button type="submit" class="btn btn-danger btn-right bayar-btn">Bayar</button>
            </form>
            <button onclick="window.print()" class="btn btn-primary btn-right">Print</button>
        </div>
    </div>
</div>
</body>
</html>
