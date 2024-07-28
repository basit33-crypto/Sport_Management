<?php
include_once 'crud_fasiliti.php';
include_once 'database.php';

// Initialize $lastId to "F1" in case there are no existing entries
$lastId = "F1";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT id_fasiliti FROM tbl_fasiliti WHERE id_fasiliti LIKE 'F%' ORDER BY LENGTH(id_fasiliti) DESC, id_fasiliti DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // Use regex to extract the number part
        preg_match('/F(\d+)$/', $row['id_fasiliti'], $matches);
        $lastIdNumber = (int)$matches[1];
        $newIdNumber = $lastIdNumber + 1;
        $lastId = 'F' . $newIdNumber;
    } else {
        $lastId = "F1";
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
    <title>Pengurusan Fasiliti</title>
    
	  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style type="text/css">
        .form-container {
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .form-header {
            font-size: 1.5em;
            margin-bottom: 20px;
            text-align: center;
            color: #343a40;
        }
        .form-group label {
            font-weight: bold;
            color: #495057;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .image-preview {
            width: 100%;
            height: 200px;
            border: 2px dashed #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5em;
            color: #aaa;
            background-color: #f8f9fa;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .image-preview img {
            max-width: 100%;
            max-height: 100%;
            display: none;
        }
        .fixed-size-image {
            width: 300px;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>

<body>
<div> <?php include_once "menu.php"?></div>
<div class="container " style="width: 1000px; margin: 0 auto;">
 <br><br><br>
    <div class="row">
        <div class="col-md-12"> 
            <div class="form-container">
                <div class="form-header">Tambah Fasiliti</div>
                <form action="crud_fasiliti.php" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fileInput" class="col-form-label">Gambar Fasiliti:</label>
                                <div class="image-preview" id="imagePreview">
                                    <span>+</span>
                                    <img id="previewImg" src="" alt="Image Preview">
                                </div>
                                <input type="file" id="fileInput" name="gmbr_fasiliti" accept="image/*" class="form-control-file">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group row">
                           
                                <div class="col-md-8">
                                    <input type="hidden" name="fid" value="<?php echo $lastId; ?>">
                                    
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="fname" class="col-md-4 col-form-label text-md-right">Nama:</label>
                                <div class="col-md-8">
                                    <input type="text" id="fname" name="fname" class="form-control" placeholder="Nama Fasiliti" value="<?php if (isset($_GET['edit'])) echo htmlspecialchars($editrow['nama_fasiliti'], ENT_QUOTES, 'UTF-8'); ?>" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="fkapasiti" class="col-md-4 col-form-label text-md-right">Kapasiti:</label>
                                <div class="col-md-8">
                                    <input type="text" id="fkapasiti" name="fkapasiti" class="form-control" placeholder="Kapasiti Fasiliti" value="<?php if (isset($_GET['edit'])) echo htmlspecialchars($editrow['kapasiti_fasiliti'], ENT_QUOTES, 'UTF-8'); ?>"required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="fkemudahan" class="col-md-4 col-form-label text-md-right">Kemudahan:</label>
                                <div class="col-md-8">
                                    <textarea id="fkemudahan" name="fkemudahan" class="form-control" placeholder="Perincian Kemudahan" style="height: 100px" ><?php if (isset($_GET['edit'])) echo htmlspecialchars($editrow['ruang_fasiliti'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12 text-center">                                                  
                                <button class="btn btn-primary" type="submit" name="create"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Create</button>                              
                            </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <?php
            try {
                $stmt_fasiliti = $conn->prepare("SELECT * FROM tbl_fasiliti WHERE id_fasiliti LIKE 'F%'");
                $stmt_fasiliti->execute();
                $fasiliti_results = $stmt_fasiliti->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            ?>
             <h2>Senarai Fasiliti</h2>
            <form action="adminkemudahan.php" method="post">
                <?php if (!empty($fasiliti_results)): ?>
                    <?php foreach ($fasiliti_results as $facility): ?>
					<div class="form-container">
                        <div class="well">
                            <div class="row">
                                <div class="col-md-4">
                                    <?php
                                    $gmbrfas = 'gambar/gambarfasiliti/' . $facility['id_fasiliti'] . '.jpg';
                                    echo '<img class="img-responsive img-thumbnail fixed-size-image" src="' . htmlspecialchars($gmbrfas, ENT_QUOTES, 'UTF-8') . '" alt="Fasiliti">';
                                    ?>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <span><b><?php echo htmlspecialchars($facility['nama_fasiliti'], ENT_QUOTES, 'UTF-8'); ?></b></span><br/>
                                            <span><?php echo htmlspecialchars($facility['kapasiti_fasiliti'], ENT_QUOTES, 'UTF-8'); ?></span><br/>
                                            <br/>Kemudahan: <br/><?php echo htmlspecialchars($facility['ruang_fasiliti'], ENT_QUOTES, 'UTF-8'); ?><br/><br/>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <div class="btn-group" role="group">
                                                <!-- Edit Button -->
                                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#editModal" data-id="<?php echo $facility['id_fasiliti']; ?>" data-nama="<?php echo htmlspecialchars($facility['nama_fasiliti'], ENT_QUOTES, 'UTF-8'); ?>" data-kapasiti="<?php echo htmlspecialchars($facility['kapasiti_fasiliti'], ENT_QUOTES, 'UTF-8'); ?>" data-ruang="<?php echo htmlspecialchars($facility['ruang_fasiliti'], ENT_QUOTES, 'UTF-8'); ?>">
                                                    <i class="fas fa-pencil"></i> Edit
                                                </button>
												&nbsp;&nbsp;
                                               
                                                <button type="button" class="btn btn-danger btn-sm" onclick="if(confirm('Are you sure to delete?')) { window.location.href='crud_fasiliti.php?delete=<?php echo $facility['id_fasiliti']; ?>'; }">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <br/>
                                    <div class="form-group">
                                        <input type="hidden" name="id_f" value="<?php echo $facility['id_fasiliti']; ?>">                         
                                        <button type="submit" class="btn btn-primary" name="b_fasiliti" value="<?php echo $facility['id_fasiliti']; ?>">Pilih<span class="glyphicon glyphicon-chevron-right"></span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
						</div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No facilities found.</p>
                <?php endif; ?>
            </form> 
        </div>
    </div>
    



</div>

<!-- Modal -->
<div id="editModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <form action="crud_fasiliti.php" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                   
                    <h4 class="modal-title">Edit Fasiliti</h4>
					 <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="oldfid" id="edit_id_fasiliti">
                    <input type="hidden" name="fid" id="edit_fid">
                    <div class="form-group">
                        <label for="edit_fname">Nama:</label>
                        <input type="text" class="form-control" id="edit_fname" name="fname">
                    </div>
                    <div class="form-group">
                        <label for="edit_fkapasiti">Kapasiti:</label>
                        <input type="text" class="form-control" id="edit_fkapasiti" name="fkapasiti">
                    </div>
                    <div class="form-group">
                        <label for="edit_fkemudahan">Kemudahan:</label>
                        <textarea class="form-control" id="edit_fkemudahan" name="fkemudahan"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit_fileInput">Gambar Fasiliti:</label>
                        <div class="image-preview" id="edit_imagePreview">
                            <span>+</span>
                            <img id="edit_previewImg" src="" alt="Image Preview">
                        </div>
                        <input type="file" id="edit_fileInput" name="gmbr_fasiliti" accept="image/*" class="form-control-file">
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>
document.getElementById('fileInput').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const reader = new FileReader();
    
    reader.onload = function(e) {
        const previewImg = document.getElementById('previewImg');
        previewImg.src = e.target.result;
        previewImg.style.display = 'block';
        document.getElementById('imagePreview').querySelector('span').style.display = 'none';
    }
    
    reader.readAsDataURL(file);
});

$(document).ready(function() {
    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var nama = button.data('nama');
        var kapasiti = button.data('kapasiti');
        var ruang = button.data('ruang');

        var modal = $(this);
        modal.find('#edit_id_fasiliti').val(id);
        modal.find('#edit_fid').val(id);
        modal.find('#edit_fname').val(nama);
        modal.find('#edit_fkapasiti').val(kapasiti);
        modal.find('#edit_fkemudahan').val(ruang);

        var imgPath = 'gambar/gambarfasiliti/' + id + '.jpg';
        modal.find('#edit_previewImg').attr('src', imgPath).show();
    });

    $('#edit_fileInput').change(function(event) {
        const file = event.target.files[0];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const previewImg = document.getElementById('edit_previewImg');
            previewImg.src = e.target.result;
            previewImg.style.display = 'block';
            document.getElementById('edit_imagePreview').querySelector('span').style.display = 'none';
        }
        
        reader.readAsDataURL(file);
    });
});
</script>

</body>
</html>
