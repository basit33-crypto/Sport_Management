<?php
session_start();

include_once 'crud_kemudahan.php';
include_once 'database.php';

if (isset($_POST['b_fasiliti'])) {
    $_SESSION['id_fasiliti'] = $_POST['b_fasiliti'];
}

$id_fasiliti = $_SESSION['id_fasiliti'] ?? null;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Senarai Kemudahan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
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
		.well{
			padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
		}
    </style>
</head>
<body>
 <div> <?php include_once "menu.php"?></div>
<div class="container " style="width:1000px; margin: 0 auto;">
    <br><br><br>
    
        <div class="row">          
            <div class="col-md-12">		
                <form action="crud_kemudahan.php" method="post" enctype="multipart/form-data">
                    <div class="well">
                        <div class="row">
                            <div class="col-md-4">
                                <!-- Image Upload Boxes -->
                                <?php for ($i = 1; $i <= 3; $i++): ?>
                                    <div class="image-upload-box" onclick="document.getElementById('file<?php echo $i; ?>').click()">
                                        <span>+</span>
                                        <img id="img<?php echo $i; ?>" src="" alt="Kemudahan">
                                        <input id="file<?php echo $i; ?>" type="file" name="image<?php echo $i; ?>" accept="image/*" onchange="previewImage(event, <?php echo $i; ?>)">
                                    </div>
                                <?php endfor; ?>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-2">                                   
                                        <label>Nama:</label><br/><br/>
                                        <label>Ruang:</label><br/><br/>
                                        <label>Perincian Set:</label><br/><br/><br/>
                                        <label>Harga Warga:</label><br/><br/>
                                        <label>Harga Bukan Warga:</label><br/><br/>
                                    </div>
								<div class="col-md-10">
									<?php
									if ($id_fasiliti !== null) {
										try {
											$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
											$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

											$sql = "SELECT id_kemudahan FROM tbl_kemudahan WHERE id_kemudahan LIKE CONCAT(:id_fasiliti, 'K%') ORDER BY LENGTH(id_kemudahan) DESC, id_kemudahan DESC LIMIT 1";
											$stmt = $conn->prepare($sql);
											$stmt->bindParam(':id_fasiliti', $id_fasiliti, PDO::PARAM_STR);
											$stmt->execute();

											if ($stmt->rowCount() > 0) {
												$row = $stmt->fetch(PDO::FETCH_ASSOC);
												// Use regex to extract the number part
												preg_match('/K(\d+)$/', $row['id_kemudahan'], $matches);
												$lastIdNumber = (int)$matches[1];
												$newIdNumber = $lastIdNumber + 1;
											} else {
												$newIdNumber = 1;
											}
											$newIdKemudahan = $id_fasiliti . 'K' . $newIdNumber;
										} catch(PDOException $e) {
											echo "Database Error: " . $e->getMessage();
										}
									} else {
										echo "Error: id_fasiliti is not set.";
										exit;
									}
									?>

									<input type="hidden" name="id_kemudahan" value="<?php echo $newIdKemudahan; ?>">								
									<input type="text" name="nama_kemudahan" placeholder="Nama Kemudahan" class="form-control mb-3" value="<?php if(isset($_GET['edit'])) echo $editrow['nama_kemudahan']; ?>" required>
									<input type="text" name="ruang" placeholder="Ruang Kemudahan" class="form-control mb-3" value="<?php if(isset($_GET['edit'])) echo $editrow['ruang']; ?>" required>
									<input type="text" name="perincian" placeholder="Perincian Kemudahan" class="form-control mb-3" style="height: 100px" value="<?php if(isset($_GET['edit'])) echo $editrow['perincian']; ?>" required>
									<input type="number" name="harga_warga" placeholder="Harga Warga" class="form-control mb-3" value="<?php if(isset($_GET['edit'])) echo $editrow['harga_warga']; ?>" required><br/>
									<input type="number" name="harga_biasa" placeholder="Harga Bukan Warga" class="form-control mb-3" value="<?php if(isset($_GET['edit'])) echo $editrow['harga_biasa']; ?>" required>

								</div>
                            
                                </div> 
                            </div>
                            <div class="col-md-12 text-center">                                                  
                                <button class="btn btn-primary" type="submit" name="create"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Create</button>                              
                            </div>
                        </div>
                    </div>                  
                </form>
				</div>
                <script>
                function previewImage(event, index) {
                    const reader = new FileReader();
                    reader.onload = function(){
                        const output = document.getElementById('img' + index);
                        output.src = reader.result;
                        output.style.display = 'block';
                    };
                    reader.readAsDataURL(event.target.files[0]);
                }
                </script>

                <?php
                if ($id_fasiliti !== null) {
                    try {
                        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $stmt_kemudahan = $conn->prepare("SELECT * FROM tbl_kemudahan WHERE id_kemudahan LIKE CONCAT(:id_fasiliti, '%')");
                        $stmt_kemudahan->bindParam(':id_fasiliti', $id_fasiliti, PDO::PARAM_STR);
                        $stmt_kemudahan->execute();
                        $kemudahan_results = $stmt_kemudahan->fetchAll(PDO::FETCH_ASSOC);
                    } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }
                } else {
                    echo "Error: id_fasiliti is not set.";
                    exit;
                }
                ?>
				 <h2>Senarai Kemudahan</h2>
                <div class="col-md-12">
                    <?php
                    if (isset($kemudahan_results) && !empty($kemudahan_results)) {
                        foreach ($kemudahan_results as $row) {
                            ?>
                            <div class="well">                               
                                <div class="row">
                                    <div class="col-md-4">
                                        <?php for ($i = 1; $i <= 3; $i++): ?>
                                            <div class="image-upload-box">
                                                <?php 
                                                $imagePath = "gambar/gambarkemudahan/" . $row['id_kemudahan'] . 'G' . $i . '.jpg';
                                                if (file_exists($imagePath)): ?>
                                                    <img class="img-responsive img-thumbnail" src="<?php echo $imagePath; ?>" alt="Kemudahan">
                                                <?php endif; ?>
                                            </div>
                                        <?php endfor; ?>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <span><b><?php echo htmlspecialchars($row['nama_kemudahan'], ENT_QUOTES, 'UTF-8'); ?></b></span><br/>
                                                <?php if (isset($row['ruang'])): ?>
                                                    Ruang: <?php echo htmlspecialchars($row['ruang'], ENT_QUOTES, 'UTF-8'); ?><br/>
                                                <?php endif; ?>
                                                <?php if (isset($row['perincian'])): ?>
                                                    Perincian Set: <?php echo htmlspecialchars($row['perincian'], ENT_QUOTES, 'UTF-8'); ?><br/>
                                                <?php endif; ?>
                                                Harga Bukan Warga:
                                                <?php if (isset($row['harga_biasa'])): ?>
                                                  RM <?php echo htmlspecialchars($row['harga_biasa'], ENT_QUOTES, 'UTF-8'); ?><br/>
                                              
                                                <?php endif; ?>
                                                Harga Warga:
                                                <?php if (isset($row['harga_warga'])): ?>
                                                  RM <?php echo htmlspecialchars($row['harga_warga'], ENT_QUOTES, 'UTF-8'); ?><br/>
                                                   
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-md-4" align="right">                                            
                                                <div class="btn-group" role="group" >
													<div>
														<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#editModal" data-id="<?php echo $row['id_kemudahan']; ?>" data-nama="<?php echo htmlspecialchars($row['nama_kemudahan'], ENT_QUOTES, 'UTF-8'); ?>" data-ruang="<?php echo htmlspecialchars($row['ruang'], ENT_QUOTES, 'UTF-8'); ?>" data-perincian="<?php echo htmlspecialchars($row['perincian'], ENT_QUOTES, 'UTF-8'); ?>" data-harga_warga="<?php echo htmlspecialchars($row['harga_warga'], ENT_QUOTES, 'UTF-8'); ?>" data-harga_biasa="<?php echo htmlspecialchars($row['harga_biasa'], ENT_QUOTES, 'UTF-8'); ?>">
															<i class="fas fa-pencil"></i> Edit
														</button>                                                   
														<button type="button" class="btn btn-danger btn-sm" onclick="if (confirm('Are you sure to delete?')) { window.location.href='adminkemudahan.php?delete=<?php echo $row['id_kemudahan']; ?>'; }">
															<i class="fas fa-trash"></i> Delete
														</button>
													
														<br/><br/>
														   <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addSelenggaraModal" 
																data-id="<?= $row['id_kemudahan'] ?>" data-name="<?= $row['nama_kemudahan'] ?>">
															<i class="fas fa-plus"></i>Selenggara
															</button>
													
													</div> 
													
                                                </div>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "Tiada maklumat.";
                    }
                    ?>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal Edit -->
    <div id="editModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <form action="crud_kemudahan.php" method="post" enctype="multipart/form-data">
                    <div class="modal-header">
					 <h4 class="modal-title">Edit Kemudahan</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                       
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_kemudahan" id="edit_id_kemudahan">
                        <div class="form-group">
                            <label for="edit_nama_kemudahan">Nama:</label>
                            <input type="text" class="form-control" id="edit_nama_kemudahan" name="nama_kemudahan">
                        </div>
                        <div class="form-group">
                            <label for="edit_ruang">Ruang:</label>
                            <input type="text" class="form-control" id="edit_ruang" name="ruang">
                        </div>
                        <div class="form-group">
                            <label for="edit_perincian">Perincian Set:</label>
                            <textarea class="form-control" id="edit_perincian" name="perincian"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit_harga_warga">Harga Warga:</label>
                            <input type="number" class="form-control" id="edit_harga_warga" name="harga_warga">
                        </div>
                        <div class="form-group">
                            <label for="edit_harga_biasa">Harga Bukan Warga:</label>
                            <input type="number" class="form-control" id="edit_harga_biasa" name="harga_biasa">
                        </div>
                        <div class="form-group">
                            <label for="edit_images">Gambar:</label>
                            <div id="edit_image_boxes">
                                <?php for ($i = 1; $i <= 3; $i++): ?>
                                    <div class="image-upload-box" onclick="document.getElementById('edit_file<?php echo $i; ?>').click()">
                                        <span>+</span>
                                        <img id="edit_img<?php echo $i; ?>" src="" alt="Kemudahan">
                                        <input id="edit_file<?php echo $i; ?>" type="file" name="edit_image<?php echo $i; ?>" accept="image/*" onchange="previewEditImage(event, <?php echo $i; ?>)">
                                    </div>
                                <?php endfor; ?>
                            </div>
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
	<!-- Modal Selenggara-->
<div id="addSelenggaraModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="crud_selenggara_kemudahan.php" method="post">
                <div class="modal-header">                
                    <h4 class="modal-title">Selenggara Kemudahan</h4>
					 <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_selenggara_kemudahan" id="id_selenggara_kemudahan">
					 <input type="hidden" name="jumlah_selenggara" id="jumlah_selenggara" value=1 >
                    <input type="hidden" name="emel_admin" value="<?php echo $admin['emel_pengguna']; ?>">
                    <input type="hidden" name="id_kemudahan" id="modal_id_kemudahan">
                    <div class="form-group">
                        <label for="modal_nama_kemudahan">Nama Kemudahan:</label>
                        <input type="text" class="form-control" id="modal_nama_kemudahan" name="nama_kemudahan" readonly>
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
    <script>
    $(document).ready(function() {
        $('#editModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id');
            var nama = button.data('nama');
            var ruang = button.data('ruang');
            var perincian = button.data('perincian');
            var harga_warga = button.data('harga_warga');
            var harga_biasa = button.data('harga_biasa');
            
            var modal = $(this);
            modal.find('#edit_id_kemudahan').val(id);
            modal.find('#edit_nama_kemudahan').val(nama);
            modal.find('#edit_ruang').val(ruang);
            modal.find('#edit_perincian').val(perincian);
            modal.find('#edit_harga_warga').val(harga_warga);
            modal.find('#edit_harga_biasa').val(harga_biasa);

            // Load existing images into the modal
            for (var i = 1; i <= 3; i++) {
                var imgPath = 'gambar/gambarkemudahan/' + id + 'G' + i + '.jpg';
                if (fileExists(imgPath)) {
                    modal.find('#edit_img' + i).attr('src', imgPath).show();
                } else {
                    modal.find('#edit_img' + i).hide();
                }
            }
        });

        $('#addSelenggaraModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var kemudahanId = button.data('id'); // Extract info from data-* attributes
            var kemudahanName = button.data('name');
            
            // Generate a unique ID for id_selenggara_kemudahan
            var idSelenggaraKemudahan = '<?= bin2hex(random_bytes(6)); ?>';
            
            // Update the modal's content
            var modal = $(this);
            modal.find('#modal_id_kemudahan').val(kemudahanId);
            modal.find('#modal_nama_kemudahan').val(kemudahanName);
            modal.find('#id_selenggara_kemudahan').val(idSelenggaraKemudahan);
        });
    });

    function previewEditImage(event, index) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('edit_img' + index);
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function fileExists(url) {
        var http = new XMLHttpRequest();
        http.open('HEAD', url, false);
        http.send();
        return http.status != 404;
    }
    </script>
</body>
</html>
