
<?php
session_start();
include_once 'database.php';

// Check if the user is logged in
if (!isset($_SESSION['emel_pengguna'])) {
    header("Location: login.php");
    exit();
}

$emel_pengguna = $_SESSION['emel_pengguna'];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT * FROM tbl_pengguna WHERE emel_pengguna = :emel_pengguna");
    $stmt->bindParam(':emel_pengguna', $emel_pengguna, PDO::PARAM_STR);
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style type="text/css">
        body {
            font-family: 'Lato', sans-serif;
        }

        .overlay {
            height: 100%;
            width: 300px;
            position: fixed;
            z-index: 1;
            top: 56px;
            left: 0;
            background-color: #2C3E50;
            overflow-x: hidden;
            transition: 0.5s;
        }

        .overlay-content {
            position: relative;
            width: 100%;
            text-align: left;
            margin-top: 30px;
            margin-left: 0px;
			margin-right: 5px;
        }

        .overlay a {
            padding: 8px;
            text-decoration: none;
            font-size: 30px;
            color: #818181;
            display: block;
            transition: 0.3s;
            border: 2px solid transparent;
            border-radius: 5px;
        }

        .overlay a:hover, .overlay a:focus {
            color: #f1f1f1;
            border: 2px solid #f1f1f1;
        }

        @media screen and (max-height: 450px) {
            .overlay a {
                font-size: 20px;
            }
            .overlay .closebtn {
                font-size: 40px;
                top: 15px;
                right: 35px;
            }
        }

        .navbar {
            position: fixed;
            width: 100%;
            height: 56px;
            top: 0;
            z-index: 1030;

        .navbar-toggler {
            margin-right: 10px;
        }

        .ms-auto {
            margin-left: auto !important;
        }
		
    </style>
</head>
<body>

    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid d-flex">
            <button class="navbar-toggler" type="button" aria-expanded="false" aria-controls="myNav" onclick="toggleNav()">
                <span class="navbar-toggler-icon"></span>
            </button>
			&nbsp;&nbsp;&nbsp;
		    <h4 style="color: white;">Admin(<?php echo $admin['nama_pengguna']; ?>)</h4>
            <a href="profileadmin.php" class="btn btn-link text-white ms-auto">
                <i class="fas fa-user"></i> 
            </a>
        </div>
    </nav>
    
    <br><br><br>
    <div id="myNav" class="overlay">
        <div class="overlay-content">
            <a href="admintempahan.php">Tempahan</a>
            <a href="selenggara_kemudahan.php">Penyelengaraan</a>
            <a href="adminfasiliti.php">Kemudahan</a>
            <a href="adminperalatan.php">Peralatan</a>
            <a href="stor.php">Stor Sukan</a>  
            <?php if ($admin['id_kategori_pengguna'] == 'P0'): ?>
                <a href="daftaradmin.php">Admin</a>
            <?php endif; ?>			
            <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
            <a href="logout.php">Log Out</a>				
        </div>
    </div>		
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function toggleNav() {
            var nav = document.getElementById("myNav");
            if (nav.style.width === "0px" || nav.style.width === "") {
                nav.style.width = "300px";
            } else {
                nav.style.width = "0";
            }
        }
    </script>
</body>
</html>
