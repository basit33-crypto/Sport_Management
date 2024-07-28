<?php
include_once 'crud_peralatan.php';
include_once 'crud_sukan.php';
include_once 'crud_selenggara_peralatan.php';
include_once 'database.php';

// Initialize $lastId to "S1" in case there are no existing entries
$lastId = "S1";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "SELECT id_sukan FROM tbl_sukan WHERE id_sukan LIKE 'S%' ORDER BY LENGTH(id_sukan) DESC, id_sukan DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // Use regex to extract the number part
        if (preg_match('/S(\d+)$/', $row['id_sukan'], $matches)) {
            $lastIdNumber = (int)$matches[1];
            $newIdNumber = $lastIdNumber + 1;
            $lastId = 'S' . $newIdNumber;
        } else {
            // Handle unexpected format
            $lastId = "S1";
        }
    } else {
        $lastId = "S1";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Pengurusan Peralatan</title>
  <style type="text/css">
        .image-upload-box {
            width: 100px;
            height: 100px;
            border: 2px dashed #ddd;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 5px;
            cursor: pointer;
            position: relative;
        }
        .image-upload-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .image-upload-box input {
            display: none;
        }
        .image-upload-box span {
            font-size: 24px;
            color: #aaa;
        }
        .fixed-size-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }
        .text-left {
            text-align: left;
        }
		.panel-heading {
            padding: 5px;
            background-color: #f8f9fa;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
			color: black;
        }
		.panel-body{
			border-radius: 5px;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
		}
		
    </style>
</head>
<body>
 <div> <?php include_once "menu.php"?></div> <div> <?php include_once "menu.php"?></div>

<div class="container " style="width: 1000px; margin: 0 auto;">
            
        <br><br><br>
        <div class="row">
            <div class="col-md-12">
                <h4>Peralatan Sukan</h4>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-md-8">

                                        <form method="post" action="crud_sukan.php">
                                            <input type="hidden" name="id_sukan" value="<?php echo  $lastId; ?>">
                                            <input type="text" name="nama_sukan" placeholder="Tambah Kategori Sukan" value="<?php if (isset($_GET['edit'])) echo htmlspecialchars($editrow['nama_sukan'], ENT_QUOTES, 'UTF-8'); ?>" required>
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn btn-default" type="submit" name="create"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Create</button>
                                    </div>
                                        </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <?php
                    include_once 'crud_peralatan.php';
                    try {
                        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $stmt_sukan = $conn->prepare("SELECT * FROM tbl_sukan");
                        $stmt_sukan->execute();
                        $sukan_results = $stmt_sukan->fetchAll();

                        foreach ($sukan_results as $sukan) {
                            $id_sukan = $sukan['id_sukan'];
                            $stmt_peralatan = $conn->prepare("SELECT * FROM tbl_peralatan WHERE id_peralatan LIKE CONCAT(:id_sukan, '%')");
                            $stmt_peralatan->bindParam(':id_sukan', $id_sukan);
                            $stmt_peralatan->execute();
                            $peralatan_results[$id_sukan] = $stmt_peralatan->fetchAll();
                        }
                    } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }
                    ?>

                    <?php foreach ($sukan_results as $sukan): ?>
                        <div class="col-md-12">
                            <div class="panel-group" id="accordion<?= $sukan['id_sukan'] ?>">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title" style="display: flex; justify-content: space-between; align-items: center;">
                                            <a data-toggle="collapse" data-parent="#accordion<?= $sukan['id_sukan'] ?>" href="#collapse<?= $sukan['id_sukan'] ?>" aria-expanded="true" aria-controls="collapse<?= $sukan['id_sukan'] ?>">
                                                <?= htmlspecialchars($sukan['nama_sukan'], ENT_QUOTES, 'UTF-8') ?>
                                            </a>
                                        <div >

                                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#editSukanModal" data-id="<?= $sukan['id_sukan'] ?>" data-nama="<?= htmlspecialchars($sukan['nama_sukan'], ENT_QUOTES, 'UTF-8') ?>">
                                                <i class="fas fa-pencil"></i> Edit
                                            </button>
                                             &nbsp;
                                            <button type="button" class="btn btn-danger btn-sm pull-right" onclick="if (confirm('Are you sure to delete?')) { window.location.href='crud_sukan.php?delete=<?= $sukan['id_sukan']; ?>'; }">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </div>

                                        </h4>
                                    </div>
                                    <div id="collapse<?= $sukan['id_sukan'] ?>" class="panel-collapse collapse " role="tabpanel" aria-labelledby="heading<?= $sukan['id_sukan'] ?>">
										<?php
										

										// Initialize $newIdPeralatan to "S1P1" in case there are no existing entries
										$newIdPeralatan = $sukan['id_sukan'] . 'P1';

										try {
											$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
											$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

											$sql = "SELECT id_peralatan FROM tbl_peralatan WHERE id_peralatan LIKE :id_sukan ORDER BY LENGTH(id_peralatan) DESC, id_peralatan DESC LIMIT 1";
											$stmt = $conn->prepare($sql);
											$likeParam = $sukan['id_sukan'] . 'P%';
											$stmt->bindParam(':id_sukan', $likeParam, PDO::PARAM_STR);
											$stmt->execute();

											if ($stmt->rowCount() > 0) {
												$row = $stmt->fetch(PDO::FETCH_ASSOC);
												// Use regex to extract the number part
												preg_match('/' . $sukan['id_sukan'] . 'P(\d+)$/', $row['id_peralatan'], $matches);
												$lastIdNumber = (int)$matches[1];
												$newIdNumber = $lastIdNumber + 1;
												$newIdPeralatan = $sukan['id_sukan'] . 'P' . $newIdNumber;
											} else {
												$newIdPeralatan = $sukan['id_sukan'] . 'P1';
											}
										} catch (PDOException $e) {
											echo "Error: " . $e->getMessage();
										}
										?>


                                        <div class="panel-body">
                                            <table class="table table-condensed">
                                                <thead>
                                                    <tr>
                                                        <td class="col-md-2"><label><u>Gambar</u></label></td>
                                                        <td class="col-md-2"><label><u>Nama Item</u></label></td>
                                                         <td class="col-md-2"><label><u>Jumlah</u></label></td>
                                                        <td class="col-md-2"><label><u>Catatan</u></label></td>
                                                        <td class="col-md-2"><label><u>Harga</u></label></td>
                                                        <td class="col-md-2"><label><u></u></label></td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <form method="post" action="crud_peralatan.php" enctype="multipart/form-data">
                                                            <input type="hidden" name="id_peralatan" value="<?php echo $newIdPeralatan ?>">
                                                            <td>
                                                                <div class="image-upload-box" onclick="document.getElementById('file<?php echo $newIdPeralatan; ?>').click()">
                                                                    <span>+</span>
                                                                    <img id="img<?php echo $newIdPeralatan; ?>" src="" alt="Peralatan">
                                                                    <input id="file<?php echo $newIdPeralatan; ?>" type="file" name="gmbr_peralatan" accept="image/*" onchange="previewImage(event, '<?php echo $newIdPeralatan; ?>')">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <input type="text" name="nama_peralatan" placeholder="Nama" class="form-control" value="" required>
                                                            </td>
                                                            <td>
                                                                
                                                            </td>
                                                    
                                                             <td>
                                                                <div class="form-group text-left">
                                                                    <label class="radio-inline">
                                                                        <input type="radio" name="catatan" value="Pulang Semula" required> Pulang Semula
                                                                    </label>
                                                                    <label class="radio-inline">
                                                                        <input type="radio" name="catatan" value="Pakai Habis" required> Pakai Habis 
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <input type="number" name="harga_peralatan" placeholder="Harga" class="form-control" value="" required>
                                                            </td>
                                                            <td>
                                                                <button type="submit" class="btn btn-primary" name="createp">Add</button>
                                                            </td>
                                                        </form>
                                                    </tr>

                                                    <?php foreach ($peralatan_results[$sukan['id_sukan']] as $peralatan): ?>
                                                        <tr>
                                                            <td width="15%">
                                                                <a data-toggle="lightbox" href="#demoLightbox<?= $peralatan['id_peralatan'] ?>">
                                                                    <div class="col-md-7" style="padding-left:0px;padding-right:5px;">
                                                                        <img class="img-responsive fixed-size-image" src="gambar/gambarperalatan/<?= $peralatan['id_peralatan'] ?>.jpg" alt="Peralatan">
                                                                    </div>
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <p class="table-v-middle"><?= htmlspecialchars($peralatan['nama_peralatan'], ENT_QUOTES, 'UTF-8') ?></p>
                                                            </td> 
                                                             <td>
                                                                <p class="table-v-middle"><?= htmlspecialchars($peralatan['kuantiti_peralatan'], ENT_QUOTES, 'UTF-8') ?></p>
                                                            </td>                                                       
                                                            <td>
                                                                <p class="table-v-middle"><?= htmlspecialchars($peralatan['catatan'], ENT_QUOTES, 'UTF-8') ?></p>
                                                            </td>
                                                            <td class="text-left">
                                                                <p class="table-v-middle">RM <?= htmlspecialchars($peralatan['harga_peralatan'], ENT_QUOTES, 'UTF-8') ?></p>
                                                            </td>
                                                            <td class="text-right">
                                                                <div >
                                                                    <!-- Edit Button -->
																	<button type="button" class="btn btn-info btn-sm" data-toggle="modal" 
																	data-target="#editPeralatanModal" data-id="<?= $peralatan['id_peralatan'] ?>" 
																	data-nama="<?= htmlspecialchars($peralatan['nama_peralatan'], ENT_QUOTES, 'UTF-8') ?>"
																	data-kuantiti="<?= htmlspecialchars($peralatan['kuantiti_peralatan'], ENT_QUOTES, 'UTF-8') ?>" 
																	data-catatan="<?= htmlspecialchars($peralatan['catatan'], ENT_QUOTES, 'UTF-8') ?>" 
																	data-harga="<?= htmlspecialchars($peralatan['harga_peralatan'], ENT_QUOTES, 'UTF-8') ?>">
																	<i class="fas fa-pencil"></i> Edit
																	</button>
																</div>
																<br>
																<div>																	
                                                                    <button type="button" class="btn btn-danger btn-sm" onclick="if (confirm('Are you sure to delete?')) { window.location.href='crud_peralatan.php?deletep=<?= $peralatan['id_peralatan']; ?>'; }">
                                                                        <i class="fas fa-trash"></i> Delete
                                                                    </button>
                                                                </div>
                                                                <br> 
																<div>
                                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addSelenggaraModal" 
                                                                        data-id="<?= $peralatan['id_peralatan'] ?>" data-name="<?= $peralatan['nama_peralatan'] ?>">
                                                                    <i class="fas fa-plus"></i>Tambah
                                                                </button>
																</div>                                                                                                                           
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
    

</div>

<!-- Modal for editing Sukan -->
<div id="editSukanModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="crud_sukan.php" method="post">
                <div class="modal-header">
                    
                    <h4 class="modal-title">Edit Sukan</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="old_id_sukan" id="edit_id_sukan">
                    <input type="hidden" name="id_sukan" id="edit_new_id_sukan">
                    <div class="form-group">
                        <label for="edit_nama_sukan">Nama:</label>
                        <input type="text" class="form-control" id="edit_nama_sukan" name="nama_sukan">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="update">Save changes</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for editing Peralatan -->
<div id="editPeralatanModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="crud_peralatan.php" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Peralatan</h4>				
                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                </div>
                <div class="modal-body">
                    <input type="hidden" name="old_id_peralatan" id="edit_id_peralatan">
                    <input type="hidden" name="id_peralatan" id="edit_new_id_peralatan">
                    <div class="form-group">
                        <label for="edit_nama_peralatan">Nama:</label>
                        <input type="text" class="form-control" id="edit_nama_peralatan" name="nama_peralatan">
                    </div>
                    <div class="form-group">
                        <label for="edit_catatan">Catatan:</label>
                        <div>
                            <label class="radio-inline">
                                <input type="radio" name="catatan" id="edit_catatan_pulang" value="Pulang Semula"> Pulang Semula
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="catatan" id="edit_catatan_pakai" value="Pakai Habis"> Pakai Habis
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_harga_peralatan">Harga:</label>
                        <input type="number" class="form-control" id="edit_harga_peralatan" name="harga_peralatan">
                    </div>
					<div class="form-group">
                        <label for="edit_kuantiti_peralatan">Jumlah Semasa:</label>
                        <input type="number" class="form-control" id="edit_kuantiti_peralatan" name="kuantiti_peralatan">
                    </div>
                    <div class="form-group">
                        <label for="edit_gmbr_peralatan">Gambar Peralatan:</label>
                        <div class="image-upload-box" onclick="document.getElementById('edit_file').click()">
                            <span>+</span>
                            <img id="edit_img" src="" alt="Peralatan">
                            <input id="edit_file" type="file" name="gmbr_peralatan" accept="image/*" onchange="previewEditImage(event)">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="updatep">Save changes</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div id="addSelenggaraModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="crud_selenggara_peralatan.php" method="post">
                <div class="modal-header">
                    
                    <h4 class="modal-title">Tambah Selenggara Peralatan</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_selenggara_peralatan" id="id_selenggara_peralatan">
                     <input type="hidden" name="emel_admin" value="<?php echo $admin['emel_pengguna']; ?>">
                    <input type="hidden" name="id_peralatan" id="modal_id_peralatan">
                    <div class="form-group">
                        <label for="modal_nama_peralatan">Nama Peralatan:</label>
                        <input type="text" class="form-control" id="modal_nama_peralatan" name="nama_peralatan" readonly>
                    </div>
                    <div class="form-group">
                        <label for="modal_jumlah_selenggara">Tambah Peralatan:</label>
                        <input type="number" class="form-control" id="modal_jumlah_selenggara" name="jumlah_selenggara" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="modal_hrg_selenggara">Harga Selenggara:</label>
                        <input type="number" class="form-control" id="modal_hrg_selenggara" name="hrg_selenggara" required>
                    </div>
                    <div class="form-group">
                        <label for="modal_catatan">Catatan:</label>
                        <input type="text" class="form-control" id="modal_catatan" name="catatan" required>
                    </div>
                    <input type="hidden" name="tarikh_selenggara" value="<?= date('Y-m-d'); ?>">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="create">Add</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>






<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script>
$(document).ready(function() {

    $('#editSukanModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var nama = button.data('nama');

        var modal = $(this);
        modal.find('#edit_id_sukan').val(id);
        modal.find('#edit_new_id_sukan').val(id);
        modal.find('#edit_nama_sukan').val(nama);
    });

    $('#editPeralatanModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var nama = button.data('nama');
        var kuantiti = button.data('kuantiti');
        var catatan = button.data('catatan');
        var harga = button.data('harga');

        var modal = $(this);
        modal.find('#edit_id_peralatan').val(id);
        modal.find('#edit_new_id_peralatan').val(id);
        modal.find('#edit_nama_peralatan').val(nama);
        modal.find('#edit_kuantiti_peralatan').val(kuantiti);
        modal.find('#edit_catatan_pulang').prop('checked', catatan === 'Pulang Semula');
        modal.find('#edit_catatan_pakai').prop('checked', catatan === 'Pakai Habis');
        modal.find('#edit_harga_peralatan').val(harga);

        var imgPath = 'gambar/gambarperalatan/' + id + '.jpg';
        modal.find('#edit_img').attr('src', imgPath).show();
    });

    $('#edit_file').change(function(event) {
        const file = event.target.files[0];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const previewImg = document.getElementById('edit_img');
            previewImg.src = e.target.result;
            previewImg.style.display = 'block';
        }
        
        reader.readAsDataURL(file);
    });

    $('#addSelenggaraModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var peralatanId = button.data('id'); // Extract info from data-* attributes
        var peralatanName = button.data('name');
        
        // Generate a unique ID for id_selenggara_peralatan
        var idSelenggaraPeralatan = '<?php echo bin2hex(random_bytes(6)); ?>';
        
        // Update the modal's content
        var modal = $(this);
        modal.find('#modal_id_peralatan').val(peralatanId);
        modal.find('#modal_nama_peralatan').val(peralatanName);
        modal.find('#id_selenggara_peralatan').val(idSelenggaraPeralatan);
    });

});


   


function previewImage(event, index) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('img' + index);
        output.src = reader.result;
        output.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
}

function previewEditImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('edit_img');
        output.src = reader.result;
        output.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>

</body>
</html>
