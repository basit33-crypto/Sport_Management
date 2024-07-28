<?php
 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "a189556";

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>