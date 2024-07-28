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
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <title>Tempah Peralatan</title>
    <style>
        .order-details { display: none; }
		@media print {
		  body {
			visibility: hidden;
		  }
		  #resit {
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
        <?php include_once 'menupengguna.php'; ?>
    </div>
    <div class="container" style="width:1000px;">
        <div class="row">
            <div class="col-md-4">
                <div class="order-list ">
                    <h3>Tempahan</h3>
                    <div class="row">
                        <?php foreach ($order_results as $order): ?>
						<?php select*from tbl_pinjaman_peralatan where id_order=$order[id_order]?>					
                            <div class="col-md-12">
                                <div class="panel-group" id="accordion<?= $order['id_order'] ?>">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title" style="display: flex; justify-content: space-between; align-items: center;">
                                                <a data-toggle="collapse" data-parent="#accordion<?= $order['id_order'] ?>" href="#collapse<?= $order['id_order'] ?>">
                                                    Order ID: <?= $order['id_order'] ?>
                                                </a>
                                                <button type="button" class="btn btn-info btn-sm perincian-btn" 
                                                data-id_order="<?= $order['id_order'] ?>" 
                                                data-emel_pengguna="<?= $order['emel_pengguna'] ?>" 
                                                data-tarikh_mula="<?= $order['tarikh_mula'] ?>" 
                                                data-tarikh_tamat="<?= $order['tarikh_tamat'] ?>" 
                                                data-masa_mula="<?= date('h:i a', strtotime($order['masa_mula'])); ?>"
                                                data-masa_tamat="<?= date('h:i a', strtotime($order['masa_tamat'])); ?>"
                                                data-jumlah_harga="<?= $order['jumlah_harga'] ?>" 
                                                data-aktiviti="<?= $order['aktiviti'] ?>" 
                                                data-status_bayaran="<?= $order['status_bayaran'] ?>" 
                                                data-status_pinjaman="<?= $order['status_pinjaman'] ?>" 
                                                data-tujuan="<?= $order['tujuan'] ?>">
                                                Perincian</button>
                                            </h4>
                                        </div>
                                        <div id="collapse<?= $order['id_order'] ?>" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <p>Activity: <?= $order['aktiviti'] ?></p>
                                                <p>Start Date: <?= $order['tarikh_mula'] ?></p>
                                                <p>End Date: <?= $order['tarikh_tamat'] ?></p>
                                                <p>Start Time: <?= $order['masa_mula'] ?></p>
                                                <p>End Time: <?= $order['masa_tamat'] ?></p>
                                                <p>Total Price: <?= $order['jumlah_harga'] ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="order-details well" id="resit">
                    <h3>Maklumat Order</h3>
                    <h4>
                        <p><strong>Order ID:</strong> <span id="detail-id_order"></span></p>
                        <p><strong>Email:</strong> <span id="detail-emel_pengguna"></span></p>
                        <p><strong>Tujuan:</strong> <span id="detail-tujuan"></span></p>
                        <p><strong>Aktiviti:</strong> <span id="detail-aktiviti"></span></p>
                        <p><strong>Tarikh Mula:</strong> <span id="detail-tarikh_mula"></span></p>
                        <p><strong>Tarikh Tamat:</strong> <span id="detail-tarikh_tamat"></span></p>
                        <p><strong>Masa Mula:</strong> <span id="detail-masa_mula"></span></p>
                        <p><strong>Masa Tamat:</strong> <span id="detail-masa_tamat"></span></p>
                        <p><strong>Jumlah Harga: RM </strong> <span id="detail-jumlah_harga"></span></p> 
                        <p><strong>Status Pinjaman:</strong> <span id="detail-status_pinjaman"></span></p>
                        <p><strong>Status Bayaran:</strong> <span id="detail-status_bayaran"></span></p>
                    </h4>
                    <button type="button" class="btn btn-danger btn-right bayar-btn" data-id_order="">Bayar</button>
					 <button onclick="window.print()">Print</button>
                </div>
            </div>
        </div>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.perincian-btn');
    const detailsBox = document.querySelector('.order-details');
    const bayarButton = document.querySelector('.bayar-btn');

    buttons.forEach(button => {
        button.addEventListener('click', function () {
            const statusBayaran = this.dataset.status_bayaran;
            const statusPinjaman = this.dataset.status_pinjaman;

            document.getElementById('detail-id_order').textContent = this.dataset.id_order;
            document.getElementById('detail-emel_pengguna').textContent = this.dataset.emel_pengguna;
            document.getElementById('detail-tarikh_mula').textContent = this.dataset.tarikh_mula;
            document.getElementById('detail-tarikh_tamat').textContent = this.dataset.tarikh_tamat;
            document.getElementById('detail-masa_mula').textContent = this.dataset.masa_mula;
            document.getElementById('detail-masa_tamat').textContent = this.dataset.masa_tamat;
            document.getElementById('detail-jumlah_harga').textContent = this.dataset.jumlah_harga;
            document.getElementById('detail-aktiviti').textContent = this.dataset.aktiviti;
            document.getElementById('detail-status_bayaran').textContent = statusBayaran;
            document.getElementById('detail-status_pinjaman').textContent = this.dataset.status_pinjaman;
            document.getElementById('detail-tujuan').textContent = this.dataset.tujuan;

            bayarButton.dataset.id_order = this.dataset.id_order
            if(statusPinjaman === 'Lulus'){
                 if (statusBayaran === 'selesai') {
                    bayarButton.style.display = 'none';
                } else {
                    bayarButton.style.display = 'block';
                }
            } else {
                    bayarButton.style.display = 'none';
            }

            detailsBox.style.display = 'block';
        });
    });

    bayarButton.addEventListener('click', function () {
        const id_order = this.dataset.id_order;

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_status.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                alert('Status bayaran telah dikemas kini kepada selesai.');
                document.getElementById('detail-status_bayaran').textContent = 'selesai';
                bayarButton.style.display = 'none';
            } else {
                alert('Terdapat ralat. Sila cuba lagi.');
            }
        };
        xhr.send('id_order=' + id_order);
    });
});
    </script>
</body>
</html>
