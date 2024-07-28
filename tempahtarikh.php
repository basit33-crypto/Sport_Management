<?php
session_start(); // Start the session at the beginning of the script
include_once 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_kemudahan'])) {
    $id_kemudahan = $_POST['id_kemudahan'];
    $_SESSION['id_kemudahan'] = $id_kemudahan;
}

// Fetch variables from session
$id_kategori_pengguna = $_SESSION['id_kategori_pengguna'];
$kodtujuan = isset($_SESSION['kodtujuan']) ? $_SESSION['kodtujuan'] : '';
$kemudahan = [];

if (isset($_SESSION['id_kemudahan'])) {
    $id_kemudahan = $_SESSION['id_kemudahan'];
    try {
        // Connect to the database
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Fetch amenity information based on facility ID
        $sql_kemudahan = "SELECT * FROM tbl_kemudahan WHERE id_kemudahan = :id_kemudahan";
        $stmt_kemudahan = $conn->prepare($sql_kemudahan);
        $stmt_kemudahan->bindParam(':id_kemudahan', $id_kemudahan);
        $stmt_kemudahan->execute();
        $kemudahan = $stmt_kemudahan->fetchAll(PDO::FETCH_ASSOC); // Fetch as associative array

    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

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

include_once "semakkalendar.php";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tempah Tarikh Kemudahan</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css"/>
    <link rel="stylesheet" href="css/main.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
    <script src="semakkemudahan.js"></script>
    <script src="pilihtarikh.js"></script>
<script>
    var bookedDates = [
        <?php
        $eventsCount = count($events);
        foreach ($events as $index => $event) {
            echo "{tarikh_mula: '" . $event['start'] . "', tarikh_tamat: '" . $event['end'] . "'}";
            if ($index < $eventsCount - 1) {
                echo ",";
            }
        }
        ?>
    ];
</script>

</head>
<body>
    <div class="container" style="padding-bottom:5px;width: 1000px;">
        <img src="status.jpg" class="img-responsive">
    </div>
    <div class="container" style="padding-bottom:5px; width: 1000px;">
        <?php include_once 'menupengguna.php'; ?>
    </div>
    <div class="container" style="width: 1000px;">
        <form id="tempahtarikhForm" method="post" action="senaraiperalatan.php" onsubmit="return validateDates()">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-6">
                        <?php if (!empty($kemudahan)): ?>
                            <?php foreach ($kemudahan as $row): ?>
                                <h2><?php echo htmlspecialchars($row['nama_kemudahan']); ?></h2>
                             
                                <table width="100%">
                                    <tbody>
                                        <tr>
                                            <td width="60%" style="border-bottom: #CCCCCC 1px solid;vertical-align:bottom;">
                                                <span style="font-size:24px">Kadar</span>
                                            </td>
                                            <td width="30%" style="border-bottom: #CCCCCC 1px solid;vertical-align:bottom;" align="right" name="harga">
                                                <?php
                                                    $id_kategori = $id_kategori_pengguna;
                                                    $harga_kemudahan = '';
                                                    if ($id_kategori == "P4" || $id_kategori == "P5" || $id_kategori == "P6") {
                                                        $harga_kemudahan = $row['harga_warga'];
                                                    } else {
                                                        $harga_kemudahan = $row['harga_biasa'];
                                                    }
                                                    $_SESSION['harga_kemudahan'] = $harga_kemudahan;
                                                ?>
                                                <span style="font-size:14px"><b>RM <?php echo htmlspecialchars($harga_kemudahan); ?></b></span><br/>
                                                <span>Sehari</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <br>
                                <div>
                                    <span style="font-size:12px">Perincian set : <br>-<?php echo htmlspecialchars($row['perincian']); ?></span><br>
                                    <span style="font-size:12px"><br>Ruang : <br>-<?php echo htmlspecialchars($row['ruang']); ?><br><br></span>
                                </div>
                                <div class="row" style="display:flex; flex-wrap: wrap;">
                                    <div class="col-xs-6 col-md-3" style="padding-left:0px;padding-right:5px;padding-bottom:5px;">
                                        <a data-toggle="lightbox" href="#demoLightbox0">
                                            <?php
                                            $gmbrkemudahan = 'gambar/gambarkemudahan/' . htmlspecialchars($row['id_kemudahan']) . 'G1.jpg';
                                            if (file_exists($gmbrkemudahan)) {
                                                echo '<img class="img-responsive img-thumbnail" src="' . $gmbrkemudahan . '" alt="Kemudahan">';
                                            }
                                            ?>
                                        </a>
                                    </div>
                                    <div class="col-xs-6 col-md-3" style="padding-left:0px;padding-right:5px;padding-bottom:5px;">
                                        <a data-toggle="lightbox" href="#demoLightbox1">
                                            <?php
                                            $gmbrkemudahan = 'gambar/gambarkemudahan/' . htmlspecialchars($row['id_kemudahan']) . 'G2.jpg';
                                            if (file_exists($gmbrkemudahan)) {
                                                echo '<img class="img-responsive img-thumbnail" src="' . $gmbrkemudahan . '" alt="Kemudahan">';
                                            }
                                            ?>
                                        </a>
                                    </div>
                                    <div class="col-xs-6 col-md-3" style="padding-left:0px;padding-right:5px;padding-bottom:5px;">
                                        <a data-toggle="lightbox" href="#demoLightbox2">
                                            <?php
                                            $gmbrkemudahan = 'gambar/gambarkemudahan/' . htmlspecialchars($row['id_kemudahan']) . 'G3.jpg';
                                            if (file_exists($gmbrkemudahan)) {
                                                echo '<img class="img-responsive img-thumbnail" src="' . $gmbrkemudahan . '" alt="Kemudahan">';
                                            }
                                            ?>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No facility information available.</p>
                        <?php endif; ?>
						
						<div style="display:none" id="amaranpenuh">
							<div class="well" style="background-color:red; color:white;">
								Kemudahan Penuh
							</div>                     
						</div>
						
						
						
						
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="well" style="width:500px;">
                                <h4>Maklumat Tempahan</h4>
                                <u>Kategori</u><br>
                                <em><?php echo htmlspecialchars($category['kategori_pengguna']); ?> </em><br><br>
                                <u>Tujuan</u><br>
                                <em><?php echo htmlspecialchars($kodtujuan); ?></em>
                                <br><br>
                                <u>Aktiviti / Acara</u>
                                <div class="row">
                                    <div class="form-group col-xs-12">
                                        <input type="text" class="form-control required" name="aktiviti" id="aktiviti" placeholder="Aktiviti / Acara" aria-required="true" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-xs-4">
                                        <div class="text-center">
                                            <div class="input-group date" id="tkhmula">
                                                <input type="date" class="form-control required" name="tkhmula" id="tkhmula_input" placeholder="Dari" value="" aria-required="true" required>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="form-group col-xs-4" style="padding:0px;margin-left: 10px">
                                        <select id="msmula" name="msmula" class="form-control required"   title="* Pastikan kategori dipilih" required>
                                            <option value="">Masa Mula</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-xs-4">
                                        <div class="text-center">
                                            <div class="input-group date" id="tkhtamat">
                                                <input type="date" class="form-control required" name="tkhtamat" id="tkhtamat_input" placeholder="Dari" value="" aria-required="true" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-xs-4" style="padding:0px;margin-left: 10px">
                                        <select id="mstamat" name="mstamat" class="form-control required"  id="mstamat" title="* Pastikan kategori dipilih" required>
                                            <option value="">Masa Tamat</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-xs-4">
                                        <input type="hidden" name="id_kemudahan" id="id_kemudahan" value="<?php echo htmlspecialchars($id_kemudahan); ?>">
                                        <input type="hidden" id="bil_hari" name="bil_hari" value="dayDiff">               
                                        <button type="submit" class="btn btn-primary" id="b_tarikh" name="b_tarikh" value="b_tarikh">Seterusnya <span class="glyphicon glyphicon-chevron-right"></span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="text-danger"><b>* Tarikh yang dibenarkan tempahan adalah selepas <span id="minStartDateDisplay"></span></b></p><br>
                        <?php include_once "kalendar.php"; ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script src="js/lightbox.js"></script> 
</body>
</html>
