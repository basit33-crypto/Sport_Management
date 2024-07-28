<?php
include_once 'database.php';

if (isset($_GET['id_order'])) {
    $id_order = $_GET['id_order'];

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch order details
        $stmt = $conn->prepare("SELECT * FROM tbl_order WHERE id_order = :id_order");
        $stmt->bindParam(':id_order', $id_order, PDO::PARAM_STR);
        $stmt->execute();
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order) {
            $emel = $order['emel_pengguna'];
            $tujuan = $order['tujuan'];
            $aktiviti = $order['aktiviti'];
            $tkhmula = $order['tarikh_mula'];
            $msmula = $order['masa_mula'];
            $tkhtamat = $order['tarikh_tamat'];
            $mstamat = $order['masa_tamat'];
            $status_pinjaman = $order['status_pinjaman'];

            // Fetch user details
            $stmt_user = $conn->prepare("SELECT * FROM tbl_pengguna WHERE emel_pengguna = :emel");
            $stmt_user->bindParam(':emel', $emel, PDO::PARAM_STR);
            $stmt_user->execute();
            $user = $stmt_user->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $id_kategori_pengguna = $user['id_kategori_pengguna'];
                $nama = $user['nama_pengguna'];

                // Fetch user category details
                $stmt_category = $conn->prepare("SELECT * FROM tbl_jenispengguna WHERE id_kategori_pengguna = :id_kategori_pengguna");
                $stmt_category->bindParam(':id_kategori_pengguna', $id_kategori_pengguna, PDO::PARAM_STR);
                $stmt_category->execute();
                $category = $stmt_category->fetch(PDO::FETCH_ASSOC);

                if ($category) {
                    $kategori = $category['kategori_pengguna'];
                } else {
                    echo "No category found with ID: $id_kategori_pengguna";
                    exit();
                }
            } else {
                echo "No user found with email: $emel";
                exit();
            }
        } else {
            echo "No order found with ID: $id_order";
            exit();
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
    $conn = null;
} else {
    echo "No ID provided.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tempah Tarikh Kemudahan</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/main.css">
    <style type="text/css">
        #tempahfasiliti {
            width: 50%; 
            height: 30px; 
            float: right;
        }
		@media print {
		  body {
			visibility: hidden;
		  }
		  #tempahfasiliti {
			visibility: visible;
			position:fixed;
			left: 0;
			top: 0;
			margin: 5px;
			width:90%;
		  }
		}
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
        <div class="row" id="tempahfasiliti">
            <div class="well">
                <h4><u>Maklumat Tempahan</u></h4>
                <u>ID Tempahan</u><br>
                <em><?php echo $id_order; ?></em><br><br>
                <u>Nama</u><br>
                <em><?php echo $nama; ?></em><br><br>
                <u>Status</u><br>
                <em><?php echo $status_pinjaman ?></em><br><br>
                <u>Kategori</u><br>
                <em><?php echo $kategori; ?></em><br><br>
                <u>Tujuan</u><br>
                <em><?php echo $tujuan; ?></em><br><br>
                <u>Aktiviti/Acara</u><br>
                <em><?php echo $aktiviti; ?></em><br><br>
                <u>Tarikh dan Masa</u><br>
               <em>Tarikh: &nbsp;<?php echo $tkhmula;?> sehingga: <?php echo $tkhtamat;?>
                    <br>Masa:&nbsp;<?php echo date('h:i a', strtotime($msmula)); ?> sehingga: <?php echo date('h:i a', strtotime($mstamat)); ?>
                </em><br><br>


                <div class="row">
                    
                </div>
            </div>
        </div>  
    </div>

    <!-- Add Bootstrap JavaScript if needed -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
    <script src="js/lightbox.js"></script> 
</body>
</html>
