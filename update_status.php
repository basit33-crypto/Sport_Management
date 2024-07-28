<?php
include_once 'crud_order.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_order = $_POST['id_order'];

    try {
        // Establish a connection to the database
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare and execute the SQL statement to update status_bayaran
        $stmt = $conn->prepare("UPDATE tbl_order SET status_bayaran = 'selesai' WHERE id_order = :id_order");
        $stmt->bindParam(':id_order', $id_order);
        $stmt->execute();

        echo "Success";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close the database connection
    $conn = null;
}
?>
