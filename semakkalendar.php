<?php
$events = [];

if ($id_kemudahan != "S1") {
    try {
        // Connect to the database
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare the initial query
        $id_kemudahan_to_check = [$id_kemudahan];
        include_once "checkdewan.php";

        // Function to fetch orders and add events
        function fetchOrdersAndAddEvents($conn, $kemudahanid, &$events) {
            $sql_pinjaman = "SELECT id_order, id_kemudahan FROM tbl_pinjaman_kemudahan WHERE id_kemudahan = :kemudahanid";
            $stmt_pinjaman = $conn->prepare($sql_pinjaman);
            $stmt_pinjaman->bindParam(':kemudahanid', $kemudahanid);
            $stmt_pinjaman->execute();
            $pinjaman = $stmt_pinjaman->fetchAll(PDO::FETCH_ASSOC);

            foreach ($pinjaman as $p) {
                $id_order = $p['id_order'];
                // Fetching the order details
                $sql_order = "SELECT tarikh_mula, tarikh_tamat, masa_mula, masa_tamat, status_bayaran, status_pinjaman FROM tbl_order WHERE id_order = :id_order";
                $stmt_order = $conn->prepare($sql_order);
                $stmt_order->bindParam(':id_order', $id_order);
                $stmt_order->execute();
                $order = $stmt_order->fetch(PDO::FETCH_ASSOC);

                if ($order['status_bayaran'] === 'Selesai' && $order['status_pinjaman'] !== 'Ditolak' && $order['status_pinjaman'] !== 'Batal') {
                    $start_datetime = $order['tarikh_mula'] . 'T' . $order['masa_mula'];
                    $end_datetime = $order['tarikh_tamat'] . 'T' . $order['masa_tamat'];

                    // Ensuring end date includes the whole last day
                    $end_date = date('Y-m-d', strtotime($order['tarikh_tamat'] ));
                    $events[] = [
                        'start' => $start_datetime,
                        'end' =>  $end_datetime,
                        'display' => 'background',
                        'color' => 'red'
                    ];
                }
            }
        }

        // Iterate over each id_kemudahan and fetch orders
        foreach ($id_kemudahan_to_check as $kemudahanid) {
            fetchOrdersAndAddEvents($conn, $kemudahanid, $events);
        }

        // Close the database connection
        $conn = null;

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
