<?php
include_once 'database.php';
 // Include this line to define $readrow_tujuan
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Senarai Fasiliti</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
<?php
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT * FROM tbl_fasiliti");
    $stmt->execute();
    $facilities = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
?>

<!-- Loop through facilities -->
<?php foreach ($facilities as $facility): ?>
    <div class="well">
        <div class="row">
            <div class="col-md-4">
                <img class="img-responsive img-thumbnail" src="dg1.jpg">
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-8">
                        <span><b><?php echo $facility['nama_fasiliti'] ?></b></span><br/>
                        <span><?php echo $facility['maklumat_fasiliti'] ?></span>
                        <br />
                        <br/>Bilangan: <br/><?php echo $facility['jumlah_fasiliti'] ?><br/>
                        <br />
                    </div>
                    <div class="col-md-4" align="right">
                        <span style="font-size:14px"><b></b></span><br/>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <form action="listvenue.php" method="post">
                  <br/>
                  <button class="btn btn-info btn-block" name="kod_venue" value="<?php echo $facility['id_fasiliti']; ?>">
                    <span class="glyphicon glyphicon-check"></span> Semak Senarai Kemudahan
                  </button>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<!-- Add Bootstrap JavaScript if needed -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
