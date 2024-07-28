<?php include 'database.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Tempahan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .button-container {
            margin-bottom: 20px;
        }
        .button-container button {
            margin-right: 5px;
            padding: 8px 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .button-container button:hover {
            background-color: #45a049;
        }
        .pagination {
            display: flex;
            justify-content: flex-end;
        }
        .pagination button {
            padding: 8px 12px;
            margin-left: 5px;
            border: 1px solid #ddd;
            background-color: #f2f2f2;
            cursor: pointer;
        }
        .pagination button:hover {
            background-color: #ddd;
        }
        .pagination button.active {
            background-color: #4CAF50;
            color: white;
        }
        .status-cards {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .status-card {
            flex: 1;
            margin: 0 10px;
            padding: 15px;
            color: white;
            border-radius: 5px;
            text-align: left;
            cursor: pointer;
        }
        .status-card.blue {
            background-color: #007BFF;
        }
        .status-card.green {
            background-color: #28A745;
        }
        .status-card.red {
            background-color: #DC3545;
        }
        .status-card.orange {
            background-color: #FD7E14;
        }
        .status-card.purple {
            background-color: #6f42c1;
        }
        @media print {
            body {
                visibility: hidden;
            }
            #reportTable {
                visibility: visible;
                position: absolute;
                left: 0;
                top: 0;
            }
        }
    </style>
</head>
<body>
<?php
    try {
        $sql = "SELECT * FROM tbl_order WHERE status_pinjaman != 'Batal';";
        $stmt = $conn->query($sql);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $countLulus = 0;
        $countDitolak = 0;
        $countSelesai = 0;
        $countTidakSelesai = 0;
        $countDiproses = 0;

        foreach ($orders as $order) {
            if ($order['status_pinjaman'] === 'Lulus') {
                $countLulus++;
            } elseif ($order['status_pinjaman'] === 'Ditolak') {
                $countDitolak++;
            } elseif ($order['status_pinjaman'] === 'Diproses') {
                $countDiproses++;
            }
            if ($order['status_bayaran'] === 'Selesai') {
                $countSelesai++;
            } elseif ($order['status_bayaran'] === 'Tidak Selesai') {
                $countTidakSelesai++;
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
?>
<div><?php include_once "menu.php"?></div>
<div class="container" stye="width:100%">

    <div class="status-cards">
        <div class="status-card purple" onclick="filterTable('status_pinjaman', 'Diproses')">
            <h5>Peminjaman Diproses: <?php echo $countDiproses; ?> </h5>
        </div>
        <div class="status-card blue" onclick="filterTable('status_pinjaman', 'Lulus')">
            <h5>Peminjaman Diterima : <?php echo $countLulus; ?></h5>
        </div>
        <div class="status-card red" onclick="filterTable('status_pinjaman', 'Ditolak')">
            <h5>Peminjaman Ditolak : <?php echo $countDitolak; ?></h5>
        </div>
        <div class="status-card green" onclick="filterTable('status_bayaran', 'Selesai')">
            <h5>Bayaran Selesai : <?php echo $countSelesai; ?></h5>
        </div>
        <div class="status-card orange" onclick="filterTable('status_bayaran', 'Tidak Selesai')">
            <h5>Bayaran Tidak Selesai : <?php echo $countTidakSelesai; ?></h5>
        </div>
    </div>

    <div class="button-container">
        <button onclick="exportToExcel()">Excel</button>
        <button onclick="window.print()">Print</button>
    </div>
    <h2>Tempahan Fasiliti</h2>
    <table id="reportTable">
        <thead>
            <tr>
                <th>Id Order</th>
                <th>Emel Pengguna</th>
                <th>Harga Tempahan</th>
                <th>Aktiviti</th>
                <th>Status Pinjaman</th>
                <th>Status Bayaran</th>
                <th>Tarikh Mula</th>
                <th>Tarikh Tamat</th>
            </tr>
        </thead>
        <tbody>
            <?php
            try {
                foreach ($orders as $order) {
                    echo "<tr>";
                    echo "<td data-column='id_order'>
                            <form action='orderdetail.php' method='post' style='display:inline;'>
                                <input type='hidden' name='id_order' value='{$order['id_order']}'>
                                <button type='submit' style='background:none; border:none; color:blue; text-decoration:underline; cursor:pointer;'>{$order['id_order']}</button>
                            </form>
                          </td>";
                    echo "<td data-column='emel_pengguna'>{$order['emel_pengguna']}</td>";
                    echo "<td data-column='jumlah_harga'>RM {$order['jumlah_harga']}</td>";
                    echo "<td data-column='aktiviti'>{$order['aktiviti']}</td>";
                    echo "<td data-column='status_pinjaman'>{$order['status_pinjaman']}</td>";
                    echo "<td data-column='status_bayaran'>{$order['status_bayaran']}</td>";
                    echo "<td data-column='tarikh_mula'>{$order['tarikh_mula']}</td>";
                    echo "<td data-column='tarikh_tamat'>{$order['tarikh_tamat']}</td>";
                    echo "</tr>";
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            ?>
        </tbody>
    </table>

</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    function exportToExcel() {
        let table = document.getElementById("reportTable");
        let html = table.outerHTML;
        let a = document.createElement('a');
        a.href = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
        a.download = 'Laporan_Tempahan.xls';
        a.click();
    }

    function filterTable(column, value) {
        let table = document.getElementById("reportTable");
        let tr = table.getElementsByTagName("tr");

        for (let i = 1; i < tr.length; i++) {
            let td = tr[i].getElementsByTagName("td");
            let show = false;

            for (let j = 0; j < td.length; j++) {
                if (td[j].getAttribute('data-column') === column && td[j].innerText === value) {
                    show = true;
                    break;
                }
            }

            tr[i].style.display = show ? "" : "none";
        }
    }
</script>
</body>
</html>
