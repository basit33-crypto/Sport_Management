<?php
// Assuming you have the dates passed from JavaScript
$tkhmula = $_POST['tkhmula'];
$tkhtamat = $_POST['tkhtamat'];
$id_kemudahan = $_POST['id_kemudahan'];

$id_cadangan_kemudahan = [];

// Check the facility ID and determine the facilities to check against
if ($id_kemudahan == 'F2K11') {
    $id_kemudahan_to_check = ['F2K5', 'F2K6', 'F2K7', 'F2K8', 'F2K9', 'F2K10', 'F2K12'];
} else {
    $id_kemudahan_to_check = [$id_kemudahan];
}


foreach ($id_kemudahan_to_check as $kemudahanid) {
    // Query to get orders for the given facility
    $stmt = $pdo->prepare("SELECT id_order FROM tbl_pinjaman_kemudahan WHERE id_kemudahan = :kemudahanid");
    $stmt->execute([':kemudahanid' => $kemudahanid]);

    $overlap = false;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $id_order = $row['id_order'];

        // Query to get the order details
        $stmt_order = $pdo->prepare("SELECT tarikh_mula, tarikh_tamat FROM tbl_order WHERE id_order = :id_order");
        $stmt_order->execute([':id_order' => $id_order]);

        while ($order = $stmt_order->fetch(PDO::FETCH_ASSOC)) {
            $tarikh_mula = $order['tarikh_mula'];
            $tarikh_tamat = $order['tarikh_tamat'];

            if (($tkhmula >= $tarikh_mula && $tkhmula <= $tarikh_tamat) || 
                ($tkhtamat >= $tarikh_mula && $tkhtamat <= $tarikh_tamat) || 
                ($tkhmula <= $tarikh_mula && $tkhtamat >= $tarikh_tamat)) {
                $overlap = true;
                break 2; // Exit both while loops if overlap is found
            }
        }
    }

    if (!$overlap) {
        $id_cadangan_kemudahan[] = $kemudahanid;
    }
}

// Output the available facilities
echo json_encode($id_cadangan_kemudahan);
?>
