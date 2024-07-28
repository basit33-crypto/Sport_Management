<?php
include_once 'crud_order.php';
$emel_pengguna = $_SESSION['emel_pengguna'];

try {
    // Establish a connection to the database
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute the SQL statement to fetch orders
    $stmt = $conn->prepare("SELECT * FROM tbl_order WHERE emel_pengguna = :emel_pengguna");
    $stmt->bindParam(':emel_pengguna', $emel_pengguna);
    $stmt->execute();
    // Fetch all the orders for the user
    $order_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    // Handle any errors that occur during the database connection
    echo "Error: " . $e->getMessage();
}
// Close the database connection
$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <title>Senarai Tempahan</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;           
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .container{
            width:1000px;
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
<div class="container">
<h3>Tempahan</h3>
<table>
    <thead>
        <tr>
            <th>Bil</th>
            <th>Id Order</th>
            <th>Aktiviti</th>
            <th>Emel Pengguna</th>
            <th>Tarikh Mula</th>
            <th>Tarikh Tamat</th>
            <th>Jumlah Harga</th>
            <th>Status Bayaran</th>
            <th>Status Pinjaman</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $bil = 1;
        foreach ($order_results as $order) {
            echo "<tr>";
            echo "<td>{$bil}</td>";
            echo "<td><a href='maklumattempahan.php?id_order={$order['id_order']}'>{$order['id_order']}</a></td>";
            echo "<td>{$order['aktiviti']}</td>";
            echo "<td>{$order['emel_pengguna']}</td>";
            echo "<td>{$order['tarikh_mula']}</td>";
            echo "<td>{$order['tarikh_tamat']}</td>";
            echo "<td>RM {$order['jumlah_harga']}</td>";
            echo "<td>{$order['status_bayaran']}</td>";
            echo "<td>{$order['status_pinjaman']}</td>";
            echo "<td>";
            if ($order['status_pinjaman'] == "Batal") {
                echo "<form method='post' action='senaraitempahan.php' onsubmit='return confirm(\"Padam Tempahan Yang Dibuat?\");'>";
                echo "<input type='hidden' name='id_order' value='{$order['id_order']}'>";
                echo "<button type='submit' name='delete' class='btn btn-danger'>Delete</button>";
                echo "</form>";
            } else {
                echo "<form method='post' action='senaraitempahan.php'  onsubmit='return confirm(\"Batal Tempahan Yang Dibuat?\");'> ";
                echo "<input type='hidden' name='id_order' value='{$order['id_order']}'>";
                echo "<button type='submit' name='cancel' class='btn btn-warning'>Batal</button>";
                echo "</form>";
            }
            echo "</td>";
            echo "</tr>";
            $bil++;
        }
        ?>
    </tbody>
</table>
</div>
</body>
</html>
