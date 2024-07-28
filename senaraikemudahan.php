<?php
session_start();
include_once 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_fasiliti = $_POST["kod_venue"];   
    $id_kategori_pengguna = $_SESSION['id_kategori_pengguna'];
    $kodtujuan = isset($_SESSION['kodtujuan']) ? $_SESSION['kodtujuan'] : '';

    try {
        // Connect to the database
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch facility name
        $sql_fasiliti = "SELECT nama_fasiliti FROM tbl_fasiliti WHERE id_fasiliti = :id_fasiliti";
        $stmt_fasiliti = $conn->prepare($sql_fasiliti);
        $stmt_fasiliti->bindParam(':id_fasiliti', $id_fasiliti);
        $stmt_fasiliti->execute();
        $fasiliti = $stmt_fasiliti->fetch(PDO::FETCH_ASSOC); 
        $fasiliti_nama = $fasiliti['nama_fasiliti'];

        // Fetch amenity information based on facility ID
        $sql_kemudahan = "SELECT * FROM tbl_kemudahan WHERE id_kemudahan LIKE CONCAT(:id_fasiliti, '%')";
        $stmt_kemudahan = $conn->prepare($sql_kemudahan);
        $stmt_kemudahan->bindParam(':id_fasiliti', $id_fasiliti);    
        $stmt_kemudahan->execute();
        $kemudahan = $stmt_kemudahan->fetchAll(); 
    } catch(PDOException $e){
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <title>Senarai Kemudahan</title>
    <style type="text/css">
        /* Add your custom styles here */
    </style>
</head>
<body>
    <div class="container" style="padding-bottom:5px;width:1000px">
        <img src="status.jpg" class="img-responsive">
    </div>
<div class="container" style="padding-bottom:5px;width:1000px">
        <?php include_once 'menupengguna.php';?>
    </div>
    <div class="container"style="width:1000px">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-8">
                    <div class="well">       
                        <span><b>Senarai Kemudahan :<?php echo $fasiliti_nama; ?>
                        </b></span><br/>
                    </div>

                    <form action="tempahtarikh.php" method="post">
                        <?php   
						//send the id_kmudahan to session
                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($kemudahan) && !empty($kemudahan)) {
                            foreach ($kemudahan as $row) {
                        ?>
                                <div class="well">                               
                                    <div class="row">
                                        <div class="col-md-4">
                                            <?php  
                                                $gmbrkemudahan = 'gambar/gambarkemudahan/' . $row['id_kemudahan'] . 'G1.jpg';
                                                echo '<img class="img-responsive img-thumbnail" src="' . $gmbrkemudahan . '" alt="Kemudahan">';
                                            ?>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <span><b><?php echo $row['nama_kemudahan']; ?></b></span><br/>
                                                    <?php if(isset($row['ruang'])): ?>
                                                        Ruang : <?php echo $row['ruang']; ?><br/>
                                                    <?php endif; ?>
                                                    <?php if(isset($row['perincian'])): ?>
                                                        Perincian Set : <?php echo $row['perincian']; ?><br/>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-md-4" align="right">
                                                    <?php $id_kategori = $id_kategori_pengguna; ?>
                                                    <?php if (($id_kategori == "P4" || $id_kategori == "P5" || $id_kategori == "P6")): ?>
                                                        <span style="font-size:14px"><b>RM 
                                                            <?php echo $row['harga_warga']; ?></b></span><br/>
                                                        <span>Sehari</span><br/><br/>
                                                    <?php else: ?>
                                                        <span style="font-size:14px"><b>RM 
                                                            <?php echo $row['harga_biasa']; ?></b></span><br/>
                                                        <span>Sehari</span><br/><br/>
                                                    <?php endif; ?>                           
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="col-md-12">
                                            <br/>
											 <button type="submit" class="btn btn-primary" name="id_kemudahan" value="<?php echo $row['id_kemudahan']; ?>">Pilih</button>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        } else {                       
                            echo "Tiada maklumat.";
                        }
                        ?>
                    </form>
                </div>

                <div class="col-md-4">
                    <div class="alert alert-info">
                        <ul>
                            <li>Bayaran penuh perlu dijelaskan dalam tempoh 7 hari sebelum penggunaan untuk mengelakkan pembatalan.</li>
                            <li>Sila berhubung dengan kakitangan JANA@UKM di talian 03 8921 4519 untuk keterangan lanjut.</li>
                        </ul>                            
                    </div>                       
                </div>
            </div>
        </div>
    </div> 
    <!-- Add Bootstrap JavaScript if needed -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
