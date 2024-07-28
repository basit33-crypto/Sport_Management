<?php

include_once 'database.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle Bayar action



} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>
