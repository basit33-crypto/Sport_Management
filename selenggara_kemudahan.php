<?php include 'database.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penyelenggaraan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
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
        .card-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .card {
            flex: 1;
            margin: 0 10px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            background-color: #f2f2f2;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .card:hover {
            background-color: #e2e2e2;
        }
        @media print {
            body {
                visibility: hidden;
            }
            #kemudahanTable, #peralatanTable {
                visibility: visible;
                position: absolute;
                left: 0;
                top: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

<div><?php include_once "menu.php" ?></div>
<div class="container">
    <div class="card-container">
        <div class="card" onclick="showTable('kemudahan')">Kemudahan</div>
        <div class="card" onclick="showTable('peralatan')">Peralatan</div>
    </div>
    <div class="button-container">
        <button onclick="exportToExcel()">Excel</button>
        <button onclick="window.print()">Print</button>
		
    </div>



    <h2 id="tableTitle">Penyelenggaraan Kemudahan</h2>
    <table id="kemudahanTable">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nama Kemudahan</th>
                <th>Harga Selenggara</th>
                <th>Catatan</th>
                <th>Email Admin</th>
                <th>Jumlah</th>
                <th>Tarikh Selenggara</th>
                <th class="no-print"></th>
            </tr>
        </thead>
        <tbody>
            <?php
            function fetchKemudahanData($conn) {
                $sql = "SELECT * FROM tbl_selenggara_kemudahan;";
                $stmt = $conn->query($sql);
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($rows as $row) {
                    $id = $row['id_kemudahan'];
                    $detail_stmt = $conn->prepare("SELECT * FROM tbl_kemudahan WHERE id_kemudahan = :id");
                    $detail_stmt->execute(['id' => $id]);
                    $detail = $detail_stmt->fetch(PDO::FETCH_ASSOC);

                    echo "<tr>";
                    echo "<td>{$row['id_selenggara_kemudahan']}</td>";
                    echo "<td>{$detail['nama_kemudahan']}</td>";
                    echo "<td>RM {$row['hrg_selenggara']}</td>";
                    echo "<td>{$row['catatan']}</td>";
                    echo "<td>{$row['emel_admin']}</td>";
                    echo "<td>{$row['jumlah_selenggara']}</td>";
                    echo "<td>" . date("Y-m-d", strtotime($row['tarikh_selenggara'])) . "</td>";
                    echo "<td class='no-print'>";
                    echo "<form method='POST' action='crud_selenggara_kemudahan.php?delete={$row['id_selenggara_kemudahan']}' style='display:inline;'>";
                    echo "<button type='submit' class='btn btn-danger btn-sm'>Delete</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            }

            fetchKemudahanData($conn);
            ?>
        </tbody>
    </table>

    <h2 id="tableTitlePeralatan" style="display: none;">Penyelenggaraan Peralatan</h2>
    <table id="peralatanTable" style="display: none;">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nama Peralatan</th>
                <th>Harga Selenggara</th>
                <th>Catatan</th>
                <th>Email Admin</th>
                <th>Jumlah</th>
                <th>Tarikh Selenggara</th>
                <th class="no-print"></th>
            </tr>
        </thead>
        <tbody>
            <?php
            function fetchPeralatanData($conn) {
                $sql = "SELECT * FROM tbl_selenggara_peralatan;";
                $stmt = $conn->query($sql);
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($rows as $row) {
                    $id = $row['id_peralatan'];
                    $detail_stmt = $conn->prepare("SELECT * FROM tbl_peralatan WHERE id_peralatan = :id");
                    $detail_stmt->execute(['id' => $id]);
                    $detail = $detail_stmt->fetch(PDO::FETCH_ASSOC);

                    echo "<tr>";
                    echo "<td>{$row['id_selenggara_peralatan']}</td>";
                    echo "<td>{$detail['nama_peralatan']}</td>";
                    echo "<td>RM {$row['hrg_selenggara']}</td>";
                    echo "<td>{$row['catatan']}</td>";
                    echo "<td>{$row['emel_admin']}</td>";
                    echo "<td>{$row['jumlah_selenggara']}</td>";
                    echo "<td>" . date("Y-m-d", strtotime($row['tarikh_selenggara'])) . "</td>";
                    echo "<td class='no-print'>";
                    echo "<form method='POST' action='crud_selenggara_peralatan.php?delete={$row['id_selenggara_peralatan']}' style='display:inline;'>";
                    echo "<button type='submit' class='btn btn-danger btn-sm'>Delete</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            }

            fetchPeralatanData($conn);
            ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    function exportToExcel() {
        var table;
        if (document.getElementById('kemudahanTable').style.display === 'table') {
            table = document.getElementById('kemudahanTable');
        } else {
            table = document.getElementById('peralatanTable');
        }
        
        var clone = table.cloneNode(true);
        // Remove the "Actions" column
        var actionsIndex = 7; // index of Actions column
        for (var i = 0; i < clone.rows.length; i++) {
            clone.rows[i].deleteCell(actionsIndex);
        }
        
        var html = clone.outerHTML;
        var a = document.createElement('a');
        a.href = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
        a.download = 'Laporan Selenggara.xls';
        a.click();
    }

    function showTable(tableType) {
        document.getElementById('kemudahanTable').style.display = tableType === 'kemudahan' ? 'table' : 'none';
        document.getElementById('peralatanTable').style.display = tableType === 'peralatan' ? 'table' : 'none';
        document.getElementById('tableTitle').style.display = tableType === 'kemudahan' ? 'block' : 'none';
        document.getElementById('tableTitlePeralatan').style.display = tableType === 'peralatan' ? 'block' : 'none';
    }
</script>
</body>
</html>
