session.php
<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['emel_pengguna'])) {
    header("Location: login.php");
    exit();
}

// Store the posted 'kodtujuan' in the session if it is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['kodtujuan'])) {
    $_SESSION['kodtujuan'] = $_POST['kodtujuan'];
}

// Store the posted 'harga_kemudahan' in the session if it is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['harga_kemudahan'])) {
    $_SESSION['harga_kemudahan'] = $_POST['harga_kemudahan'];
}

// Fetch session variables
$emel_pengguna = $_SESSION['emel_pengguna'];
$id_kategori_pengguna = $_SESSION['id_kategori_pengguna'];
$kodtujuan = isset($_SESSION['kodtujuan']) ? $_SESSION['kodtujuan'] : '';
$harga_kemudahan = isset($_SESSION['harga_kemudahan']) ? $_SESSION['harga_kemudahan'] : '';
?>