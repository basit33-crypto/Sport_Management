<?php
session_start();
include_once 'database.php';

$id_kategori_pengguna = "";
if (isset($_SESSION['id_kategori_pengguna'])) {
    $id_kategori_pengguna = $_SESSION['id_kategori_pengguna'];
}

// Fetch the kategori_pengguna from the database based on the id_kategori_pengguna stored in the session
$kategori_pengguna = '';
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT id_kategori_pengguna, kategori_pengguna FROM tbl_jenispengguna WHERE id_kategori_pengguna = :id_kategori_pengguna");
    $stmt->bindParam(':id_kategori_pengguna', $id_kategori_pengguna, PDO::PARAM_STR);
    $stmt->execute();
    $category = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['kodtujuan'])) {
    $_SESSION['kodtujuan'] = $_POST['kodtujuan'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Senarai Fasiliti</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style type="text/css">

    </style>
</head>
<body>

    <div class="container" style="padding-bottom:5px;width:1000px">
        <img src="status.jpg" class="img-responsive">
    </div>
    <div class="container" style="padding-bottom:5px;width:1000px">
        <?php include_once 'menupengguna.php';?>
    </div>
    <div class="container" style="width:1000px">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-8">
                    <div class="well">
                        <form id="searchForm" method="post">
                            <div class="row">
                                <div class="form-group col-xs-6">
                                   
                                    <select id="kategori_pengguna" name="kategori_pengguna" class="form-control required" disabled>
                                        <option value="<?= $kategori['id_kategori_pengguna'] ?>" selected><?= $category['kategori_pengguna'] ?></option>
                                    </select>
                                     <input type="hidden" name="id_kategori_pengguna" value="<?= htmlspecialchars($category['id_kategori_pengguna']) ?>">
                               
                                </div>

                                <div class="form-group col-xs-6">
                                    <select id="kodtujuan" name="kodtujuan" class="form-control required" title="* Pastikan tujuan dipilih">
                                        <option value="Sukan" <?php if (isset($_SESSION['kodtujuan']) && $_SESSION['kodtujuan'] == 'Sukan') echo 'selected'; ?>>Sukan</option>
                                        <option value="Mesyuarat" <?php if (isset($_SESSION['kodtujuan']) && $_SESSION['kodtujuan'] == 'Mesyuarat') echo 'selected'; ?>>Mesyuarat</option>
                                    </select>
                                </div>
                                <div class="form-group col-xs-12">
                                    <button type="submit" class="btn btn-primary" name="b_search" value="b_search">Cari <span class="glyphicon glyphicon-chevron-right"></span></button>
                                </div>
                            </div>
                        </form>
                    </div>

                   <form action="senaraikemudahan.php" method="post">
                        <?php
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            $id_kategori = $id_kategori_pengguna;
                            $tujuan = $_SESSION['kodtujuan'];
                            

                            try {
                                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                $sql = "SELECT * FROM tbl_fasiliti WHERE ";

                                if (($id_kategori == "P4" || $id_kategori == "P5" || $id_kategori == "P6") && $tujuan == "Sukan") {
                                    $sql .= "(id_fasiliti LIKE 'S%' OR id_fasiliti LIKE 'F%')";
                                } elseif (($id_kategori == "P1" || $id_kategori == "P2" || $id_kategori == "P3") && $tujuan == "Sukan") {
                                    $sql .= "id_fasiliti LIKE 'F%'";
                                } elseif ($tujuan != "Sukan") {
                                    echo '<div class="alert alert-danger"><b>Tiada data berdasarkan carian.</b></div>';
                                    $sql = ""; 
                                }

                                // Proceed with executing the SQL query if it's not empty
                                if ($sql !== "") {
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute();
                                    $facilities = $stmt->fetchAll();
                                }
                            } catch (PDOException $e) {
                                echo "Error: " . $e->getMessage();
                            }
                        }
                        ?>
                        <?php
                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($facilities)) {

                            foreach ($facilities as $facility) { ?> 
                                <div class="well">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <?php
                                            $gmbrfas = 'gambar/gambarfasiliti/' . $facility['id_fasiliti'] . '.jpg';
                                            echo '<img class="img-responsive img-thumbnail" src="' . $gmbrfas . '" alt="Fasiliti">';
                                            ?>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <span><b><?php echo $facility['nama_fasiliti']; ?></b></span><br/>
                                                    <span><?php echo $facility['kapasiti_fasiliti']; ?></span><br />
                                                    <br/>Kemudahan: <br/><?php echo $facility['ruang_fasiliti']; ?><br/><br/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <br/>

                                            <button class="btn btn-info btn-block" name="kod_venue" value="<?php echo $facility['id_fasiliti']; ?>">
                                                <span class="glyphicon glyphicon-check"></span> Pilih
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php }
                        } ?>
                        </form>
                </div>

                <!--Kotak biru kt tepi-->
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
