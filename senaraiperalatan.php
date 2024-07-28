<?php
include_once 'database.php';
include_once 'crud_order.php';

$emel_pengguna = $_SESSION['emel_pengguna'] ?? '';
$id_kategori_pengguna = $_SESSION['id_kategori_pengguna'] ?? '';
$kodtujuan = $_SESSION['kodtujuan'] ?? '';
$harga_kemudahan = $_SESSION['harga_kemudahan'] ?? '';
$peralatan_results = [];
$bilangan = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $aktiviti = $_POST['aktiviti'] ?? '';
    $tkhmula = $_POST['tkhmula'] ?? '';
    $msmula = $_POST['msmula'] ?? '';
    $tkhtamat = $_POST['tkhtamat'] ?? '';
    $mstamat = $_POST['mstamat'] ?? '';
    $bil_hari = $_POST['bil_hari'] ?? '';
    $id_kemudahan = $_POST['id_kemudahan'] ?? '';
 

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);        
        $stmt_kemudahan = $conn->prepare("SELECT * FROM tbl_kemudahan WHERE id_kemudahan = :id_kemudahan");
        $stmt_kemudahan->bindParam(':id_kemudahan', $id_kemudahan, PDO::PARAM_STR);
        $stmt_kemudahan->execute();
        $kemudahan_result = $stmt_kemudahan->fetch(PDO::FETCH_ASSOC);

        // Retrieve all sports
        $stmt_sukan = $conn->prepare("SELECT * FROM tbl_sukan");
        $stmt_sukan->execute();
        $sukan_results = $stmt_sukan->fetchAll(PDO::FETCH_ASSOC);
       
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    try {
        // Retrieve user category
        $stmt = $conn->prepare("SELECT id_kategori_pengguna, kategori_pengguna FROM tbl_jenispengguna WHERE id_kategori_pengguna = :id_kategori_pengguna");
        $stmt->bindParam(':id_kategori_pengguna', $id_kategori_pengguna, PDO::PARAM_STR);
        $stmt->execute();
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    $results = [];
}
$conn = null;

function createDateRange($startDate, $endDate) {
    $dates = [];
    $current = strtotime($startDate);
    $end = strtotime($endDate);

    while ($current <= $end) {
        $dates[] = date("Y-m-d", $current);
        $current = strtotime("+1 day", $current);
    }

    return $dates;
}

// Assuming $tkhmula and $tkhtamat are set
$dates = isset($tkhmula) && isset($tkhtamat) ? createDateRange($tkhmula, $tkhtamat) : [];

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <title>Tempah Peralatan</title>
</head>
<body>
    <div class="column">
        <div class="container" style="padding-bottom:5px;width:1000px">
            <img src="status.jpg" class="img-responsive">
        </div>
        <div class="container" style="padding-bottom:5px;width:1000px">
            <?php include_once 'menupengguna.php'; ?>
        </div>
       
	   <div class="container" style="width:1000px">
	   
	   
<form id="equipment-form" method="post" action="crud_order.php">
    <!-- Hidden inputs to include all dates -->
    <?php foreach ($dates as $date): ?>
        <input type="hidden" name="all_dates[]" value="<?= $date ?>">
    <?php endforeach; ?>

    <div class="row">
        <div class="col-md-8" <?php if ($id_kategori_pengguna !== "P4" && $id_kategori_pengguna !== "P5" && $id_kategori_pengguna !== "P6") echo 'style="display:none;"'; ?>>
            <h4>Peralatan Sukan</h4>
            <div class="row">
                <?php foreach ($dates as $date): ?>                          
                    <div class="col-md-12">
                        <div class="panel-group" id="accordionDate<?= str_replace('-', '', $date) ?>">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordionDate<?= str_replace('-', '', $date) ?>" href="#collapseDate<?= str_replace('-', '', $date) ?>" aria-expanded="false" aria-controls="collapseDate<?= str_replace('-', '', $date) ?>">
                                            <?php echo $tarikh = $date ?>                                                    
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseDate<?= str_replace('-', '', $date) ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingDate<?= str_replace('-', '', $date) ?>">
                                    <div class="panel-body">
                                        <div class="panel-body">
                                            <table class="table table-condensed">
                                                <?php foreach ($sukan_results as $sukan): ?>
                                                    <thead>
                                                        <tr>
                                                            <th class="col-md-2" colspan="6">
                                                                <label><?= $sukan['nama_sukan'] ?></label>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <thead>
                                                        <tr>
                                                            <th class="col-md-2"><label></label></th>
                                                            <th class="col-md-2"><label><u>Peralatan</u></label></th>                                                                    
                                                            <th class="col-md-2"><label><u>Tersedia</u></label></th>
                                                            <th class="col-md-2"><label><u>Catatan</u></label></th>
                                                            <th class="col-md-2"><label><u>Harga</u></label></th>                                                                        
                                                            <th class="col-md-2"><label><u>Bilangan</u></label></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        try {
                                                            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                                                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                                            $stmt = $conn->prepare("
                                                                SELECT p.id_peralatan, p.nama_peralatan, p.kuantiti_peralatan, p.catatan, p.harga_peralatan, IFNULL(SUM(pp.bil_pinjam), 0) AS total_pinjam
                                                                FROM tbl_peralatan p
                                                                LEFT JOIN tbl_pinjaman_peralatan pp ON p.id_peralatan = pp.id_peralatan AND pp.tarikh_pinjam = :tarikh
                                                                WHERE p.id_peralatan LIKE :id_sukan_prefix
                                                                GROUP BY p.id_peralatan
                                                            ");

                                                            $id_sukan_prefix = $sukan['id_sukan'] . '%';
                                                            $stmt->bindParam(':tarikh', $date);
                                                            $stmt->bindParam(':id_sukan_prefix', $id_sukan_prefix, PDO::PARAM_STR);
                                                            $stmt->execute();
                                                            $peralatan_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                        } catch (PDOException $e) {
                                                            echo "Error: " . $e->getMessage();
                                                            die();
                                                        }

                                                        foreach ($peralatan_results as $peralatan):
                                                            $unique_id = bin2hex(random_bytes(6)); // Generate a random unique ID
                                                        ?>
                                                            <tr>
                                                                <td width="15%">
                                                                    <a data-toggle="lightbox" href="#demoLightbox<?= $peralatan['id_peralatan'] ?>">
                                                                        <div class="col-md-7" style="padding-left:0px;padding-right:5px;">
                                                                            <img class="img-responsive fixed-size-image" src="gambar/gambarperalatan/<?= $peralatan['id_peralatan'] ?>.jpg" alt="Peralatan">
                                                                        </div>
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    <p class="table-v-middle"><?= $peralatan['nama_peralatan'] ?></p>
                                                                </td>
                                                                
                                                                <td class="text-center"><!--if(status_bayaran)-->
                                                                    <p class="table-v-middle"><?= $bil=$peralatan['kuantiti_peralatan']- $peralatan['total_pinjam'] ?></p>
                                                                </td>
                                                                <td>
                                                                    <p class="table-v-middle"><?= $peralatan['catatan'] ?></p>
                                                                </td>
                                                                <td class="harga-peralatan">
                                                                    <p class="table-v-middle">RM <?= $peralatan['harga_peralatan'] ?></p>
                                                                </td>                                                                                                                
                                                                <td class="text-right">
                                                                    <input type="number" name="bilangan[<?= $peralatan['id_peralatan'] ?>][<?= $date ?>]" class="form-control bilangan-input" placeholder="" style="width: 60px" min="0" max="<?=$bil ?>">                                                                                                                
                                                                    <input type="hidden" name="id_pinjaman_peralatan[<?= $peralatan['id_peralatan'] ?>][<?= $date ?>]" value="<?= $unique_id; ?>">
                                                                    <input type="hidden" name="tarikh[<?= $peralatan['id_peralatan'] ?>][<?= $date ?>]" value="<?php echo $date?>">
                                                                    <input type="hidden" name="id_peralatan[<?= $peralatan['id_peralatan'] ?>][<?= $date ?>]" value="<?= $peralatan['id_peralatan'] ?>">
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                <?php endforeach; ?>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
					
					<!---resit tepi-->
					
			        <input type="hidden" id="total_peralatan" name="total_peralatan" value="0">
                    <div class="col-md-4" style="float: right;">
                        <br/>
                        <div class="well">
                            <h4>Maklumat Tempahan</h4>
                            <u>Kategori</u> <br/>
                            <em><?php echo $category['kategori_pengguna']; ?></em><br/>
                            <input type="hidden" name="emel_pengguna" value="<?php echo $emel_pengguna; ?>">
                            <br/>
                            <u>Tujuan</u><br/>
                            <em><?php echo $kodtujuan; ?></em><br/><br/>
                            <input type="hidden" name="tujuan" value="<?php echo $kodtujuan; ?>">
                            <u>Aktiviti / Acara</u><br/>
                            <em style="word-wrap: break-word;"><?php echo $aktiviti; ?></em><br/><br/>
                            <input type="hidden" name="aktiviti" value="<?php echo $aktiviti; ?>">
                            <u>Tarikh dan Masa</u><br/>
                            <table border="0">
                                <tr>
                                    <td align="center">
                                        <em>
                                            <?php echo $tkhmula; ?>
                                            <input type="hidden" name="tarikh_mula" value="<?php echo $tkhmula; ?>">
                                            &nbsp;&nbsp;&nbsp;
                                            <?php echo $msmula; ?>
                                            <input type="hidden" name="masa_mula" value="<?php echo $msmula; ?>">
                                            <br/>
                                            hingga<br/>
                                            <?php echo $tkhtamat; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input type="hidden" name="tarikh_tamat" value="<?php echo $tkhtamat; ?>">
                                            <?php echo $mstamat; ?>
                                            <input type="hidden" name="masa_tamat" value="<?php echo $mstamat; ?>">
                                        </em>
                                    </td>
                                </tr>
                            </table>
                            <br/>
                            <u>Sebut harga</u><br/>
                            <table class="table table-responsive">
                                <tr>
                                    <td>Ruang/Pakej</td>
                                    <td></td>
                                    <td class="pull-right">Harga</td>
                                </tr>
                                <tr>
                                    <td>
                                        <em><?php echo $kemudahan_result['nama_kemudahan']; ?></em>										
                                    </td>
                                    <td></td>
                                    <td class="pull-right">RM <?php echo $harga_kemudahan; ?> x <?php echo $bil_hari + 1; ?></td>
                                </tr>
                            </table>
                            <table class="table table-responsive">
                                <tr>
                                    <td><b>A [Ruang/Pakej]</b></td>
                                    <td class="pull-right">
                                        <label data-cell="Z1" data-format="$ 0,0.00">
                                           <td class="pull-right">RM <?php echo $total_kemudahan = $harga_kemudahan * ($bil_hari + 1); ?></td>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>B [Alatan]</b></td>
                                    <td class="text-right">
                                        <label nama="total_peralatan"></label>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>A + B</b></td>
                                    <td class="text-right">
                                        <label name="total">RM 0.00</label>
                                        <input type="hidden" name="jumlah_harga" value="0">
                                    </td>
                                </tr>
                            </table>
                            <div class="row">
                                <div class="form-group col-xs-4">
                                    <?php
                                    $unique_id = bin2hex(random_bytes(6));
                                    ?>
                                    <input type="hidden" name="id_order" value="<?php echo $unique_id; ?>">
                                    <input type="hidden" name="id_kemudahan" value="<?php echo $id_kemudahan; ?>">
                                    <input type="hidden" name="status_bayaran" value="Tidak Selesai">
                                    <input type="hidden" name="status_pinjaman" value="Diproses">
                                    <button class="btn btn-default" type="submit" name="create"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Tempah</button>
                                </div>
                            </div>
                        </div>
                    </div>
					
					
					
                </div>
            </form>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	    <script>
        function calculateTotalPeralatan() {
            let totalPeralatan = 0;         

            document.querySelectorAll("input[name^='bilangan']").forEach(function (input) {
                const bilangan = parseInt(input.value) || 0;
                const harga = parseFloat(input.closest('tr').querySelector('.harga-peralatan').textContent.replace('RM ', ''));
                totalPeralatan += bilangan * harga;
            });

            const totalPeralatanFormatted = totalPeralatan.toFixed(2);
            document.getElementById('total_peralatan').value = totalPeralatanFormatted;
            document.querySelector('label[nama="total_peralatan"]').textContent = 'RM ' + totalPeralatanFormatted;

            const totalA = parseFloat('<?= $total_kemudahan ; ?>');
            const grandTotal = totalA + totalPeralatan;

            const grandTotalFormatted = grandTotal.toFixed(2);
            document.querySelector('label[name="total"]').textContent = 'RM ' + grandTotalFormatted;
            document.querySelector('input[name="jumlah_harga"]').value = grandTotalFormatted;
        }

        document.querySelectorAll("input[name^='bilangan']").forEach(function (input) {
            input.addEventListener('input', calculateTotalPeralatan);
        });

        calculateTotalPeralatan();
    </script>
</body>
</html>
