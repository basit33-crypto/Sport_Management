<?php
// Include the database connection file
include_once 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tarikh = $_POST['tarikh'];

    try {
        // Prepare and execute the query to fetch data with the same tarikh_pinjam and join with tbl_peralatan
        $stmt = $conn->prepare("
            SELECT p.id_peralatan, p.nama_peralatan, p.kuantiti_peralatan, p.catatan, p.harga_peralatan, IFNULL(SUM(pp.bil_pinjam), 0) AS total_pinjam
            FROM tbl_peralatan p
            LEFT JOIN tbl_pinjaman_peralatan pp ON p.id_peralatan = pp.id_peralatan AND pp.tarikh_pinjam = :tarikh
            GROUP BY p.id_peralatan
        ");
        $stmt->bindParam(':tarikh', $tarikh);
        $stmt->execute();
        $results = $stmt->fetchAll();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        die();
    }
} else {
    $results = [];
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css"/>
    <title>Stor Sukan</title>
    <style type="text/css">
        body {
            background-color: #f8f9fa;
        }
        #peralatan {
            margin-top: 150px;
            background-color: #fff;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .container {
            margin-top: 10px;
            background-color: #fff;
            padding: 30px;         
        }
        h4 {
            margin-bottom: 20px;
            font-weight: 600;
            color: #343a40;
        }
        form {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        input[type="date"] {
            padding: 5px 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            margin-right: 10px;
        }
        input[type="submit"] {
            padding: 5px 15px;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            color: white;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .image-upload-box {
            width: 100px;
            height: 100px;
            border: 2px dashed #ddd;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 5px;
            cursor: pointer;
            position: relative;
        }
        .image-upload-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .image-upload-box input {
            display: none;
        }
        .image-upload-box span {
            font-size: 24px;
            color: #aaa;
        }
        .fixed-size-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }
        .text-left {
            text-align: left;
        }
        .panel-head-title {
            padding: 5px;
            background-color: #f8f9fa;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            color: black;
        }
        .panel-body--content {
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 15px;
            margin-bottom: 20px;
        }
        #pilih_tarikh {
            position: fixed;
            width: 100%;
            max-width: 1000px;
            z-index: 1030;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 10px 30px;
        }
    </style>
</head>
<body>
    <div><?php include_once "menu.php"?></div> 
    <div class="container"> 
	
        <div id="pilih_tarikh">      
            <div class="row">
                <div class="col-12">
                    <h4>Pilih Tarikh</h4>
                    <form action="stor.php" method="post">
                        <input type="date" id="tarikh" name="tarikh">
                        <input type="submit" value="PILIH">
                    </form>
                </div>
            </div>        
        </div>
        <div id="peralatan">
            <div class="row">
                <div class="col-md-12">
                    <h4>Peralatan Sukan</h4>
					    <?php if (isset($tarikh)): ?>
							<p>Tarikh: <?= htmlspecialchars($tarikh, ENT_QUOTES, 'UTF-8') ?></p>
						<?php endif; ?>
                    <?php if (count($results) > 0): ?>
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    
                                    <th>Nama Peralatan</th>
                                    <th>Jumlah Semasa</th>
                                    <th>Catatan</th>
                                    <th>Harga</th>
                                    <th>Dipinjam</th>
                                    <th>Tersedia</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($results as $row):?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['nama_peralatan'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($row['kuantiti_peralatan'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($row['catatan'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td>RM <?= htmlspecialchars($row['harga_peralatan'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($row['total_pinjam'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($bil = $row['kuantiti_peralatan'] - $row['total_pinjam'], ENT_QUOTES, 'UTF-8') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>Pilih Tarikh</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
