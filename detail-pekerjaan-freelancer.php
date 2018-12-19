<?php
    include './lib/connection.php';
    
    session_start();
    
    $id_job = $_GET["id"];

    if (isset($_SESSION['username']) && isset($_SESSION['level'])) {
        
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Freelancer - Detail Pekerjaan</title>
    <?php include './lib/header.php'; ?>
</head>
<body class="mt-5">
    <?php include './lib/navbar.php'; ?>
    <div class="row">
        <div class="col-8 pt-5 pl-5 pb-5 pr-4">
        <?php
            $username = $_SESSION["username"];
            $resultquery = mysqli_query($con, "SELECT u.id_user FROM user AS u INNER JOIN job AS j ON j.id_user = u.id_user WHERE u.username = '$username'");
            $rowusername = mysqli_fetch_assoc($resultquery);
            $query = "SELECT j.*, c.*, u.* FROM ((job AS j INNER JOIN category AS c ON j.id_category = c.id_category) INNER JOIN user AS u ON j.id_user = u.id_user) WHERE id_job = '$id_job'";
            $result = mysqli_query($con, $query);
            $row = mysqli_fetch_assoc($result);

            $category_image = $row["category_image"];
            $namapekerjaan = $row["job_name"];
            $idcategory = $row["id_category"];
            $category = $row["category_name"];
            $waktupost = $row["job_create_date"];
            $datepost = date_create($waktupost);
            $bataswaktu = $row["apply_expire_date"];
            $expire = date_create($bataswaktu);
            $usercreate = $row["user_create_date"];
            $dateuser = date_create($usercreate);
            $deskripsi = $row["job_description"];
            $salary = $row["job_salary"];
            $namaclient = $row["name"];
            $fotoclient = $row["profile_picture"];
            $queryapplicant = mysqli_query($con, "SELECT COUNT(*) AS applicants FROM applications WHERE id_job = '$id_job'");
            $rowapplicant = mysqli_fetch_assoc($queryapplicant);
            $checkapplicant = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM applications WHERE id_job = '$id_job' AND id_freelancer = '$id_user'"));
            $applicants = $rowapplicant['applicants'];
            $idjobapplicant = $checkapplicant['id_job'];
            $iduserapplicant = $checkapplicant['id_freelancer'];
            $waktusekarang = date('Y-m-d H:i:s');
        ?>
            <div class="container-fluid shadow bg-white p-4 mb-3">
                <div class="w-100 d-flex justify-content-end">
                <?php
                    if ($bataswaktu > $waktusekarang) {
                        if ($idjobapplicant != $id_job || $iduserapplicant != $id_user) {
                ?>
                            <a href="#" class="link-decoration" data-toggle="modal" data-target="#lamar">
                                <button class="btn btn-primary pl-5 pr-5">
                                    <i class="far fa-edit mr-2"></i>
                                    Lamar
                                </button>
                            </a>
                <?php
                        } else {
                ?>
                            <button class="btn btn-success pl-5 pr-5 mr-3" disabled>
                                <i class="fas fa-check mr-2"></i>
                                Anda telah mendaftar di pekerjaan ini
                            </button>
                            <a href="#" class="link-decoration" data-toggle="modal" data-target="#editLamaran">
                                <button class="btn btn-warning">
                                    <i class="fas fa-edit mr-2"></i>
                                    Edit Lamaran
                                </button>
                            </a>
                <?php
                        }
                    } else {
                ?>
                        <button class="btn btn-danger pl-5 pr-5" disabled>
                            <i class="fas fa-ban mr-2"></i>
                            Pendaftaran telah ditutup
                        </button>
                <?php
                    }
                ?>
                </div>
                <hr>
                <div class="row d-flex flex-column justify-content-center align-items-center w-100">
                    <img src="<?=$category_image?>" alt="Gambar Category" style="width: 220px;">
                    <h3 class="text-primary"><?=$namapekerjaan?></h3>
                    <div class="row">
                        <i class="far fa-file-alt ml-3 mr-2 text-dark"></i>
                        <span><?=$applicants?> Pelamar</span>
                        <i class="far fa-calendar-alt ml-3 mr-2 text-dark"></i>
                        <span>Pendaftaran ditutup pada: <span class="text-danger"><?=$expire->format('D, d M Y')?></span></span>
                    </div>
                </div>
                <hr>
                <div class="container">
                    <p>
                        <strong class="text-primary">Tanggal Post : </strong> 
                        <?=$datepost->format('D, d M Y')?> <br>
                    </p>
                    <p>
                        <strong class="text-primary">Kategori Pekerjaan : </strong> 
                        <?=$category?> <br>
                    </p>
                    <p>
                        <strong class="text-primary">Salary Pekerjaan : </strong> 
                        <strong>IDR</strong> <?=number_format($salary, 0, '.', ',')?> <br>
                    </p>
                    <p>
                        <strong class="text-primary">Deskripsi Pekerjaan : </strong> 
                    </p>
                    <p class="container">
                        <?=$deskripsi?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-4 pt-5 pl-4 pb-5 pr-5">
            <div class="container-fluid shadow bg-white p-4">
                <h4>Dipost Oleh</h4>
                <hr>
                <div class="row d-flex flex-column justify-content-center align-items-center">
                    <img src="<?=$fotoclient?>" alt="Gambar Category" class="rounded-circle mb-3" style="width: 120px; height: 120px;">
                    <h5><?=$namaclient?></h5>
                    <small>Terdaftar di Kerjalancer pada :</small>
                    <span class="text-success"><?=$dateuser->format('D, d M Y')?></span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Lamar -->
    <div class="modal fade" id="lamar" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="./process/lamar-pekerjaan-process.php" method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Lamar Pekerjaan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <input type="hidden" name="idjob" value="<?=$id_job?>">
                            <input type="hidden" name="iduser" value="<?=$id_user?>">
                            <div class="form-group row">
                                <label for="deskripsi" class="col-2 text-right">Tulis Lamaran Anda</label>
                                <textarea class="form-control col-10" name="deskripsi" id="deskripsi" rows="7" placeholder="Tuliskan lamaran Anda disini..." required></textarea>
                            </div>
                            <div class="form-group row">
                                <label for="file" class="col-2 text-right">Dokumen Pendukung</label>
                                <div class="col-10">
                                    <input type="file" class="form-control" name="file" id="file" required>
                                    <small class="text-danger">*) Ukuran maksimum 2Mb</small><br>
                                    <small class="text-danger">*) Hanya upload file dokumen saja. (.pdf)</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <input type="submit" name='submit' class="btn btn-primary" value="Simpan Perubahan">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Lamaran -->
    <div class="modal fade" id="editLamaran" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="./process/edit-lamaran-process.php" method="post" enctype="multipart/form-data">
                <?php
                    $querylamaran = mysqli_query($con, "SELECT * FROM applications WHERE id_job = '$idjobapplicant' AND id_freelancer = '$iduserapplicant'");
                    $resultlamaran = mysqli_fetch_assoc($querylamaran);
                    $idapplications = $resultlamaran["id_applications"];
                    $deskripsi = $resultlamaran["description"];
                    $document = $resultlamaran["document"];
                ?>
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Lamaran Pekerjaan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <input type="hidden" name="idapplications" value="<?=$idapplications?>">
                            <input type="hidden" name="idjob" value="<?=$idjobapplicant?>">
                            <div class="form-group row">
                                <label for="deskripsi" class="col-2 text-right">Tulis Lamaran Anda</label>
                                <textarea class="form-control col-10" name="deskripsi" id="deskripsi" rows="7" placeholder="Tuliskan lamaran Anda disini..." required><?=$deskripsi?></textarea>
                            </div>
                            <div class="form-group row">
                                <label for="file" class="col-2 text-right">Dokumen Pendukung</label>
                                <div class="col-10">
                                    <div class="p-3 mb-3" style="background-color: #f0f0f0">
                                        <a href="<?=$document?>" class="link-decoration" target="_blank">Lihat dokumen terupload</a>  
                                    </div>
                                    <input type="file" class="form-control" name="file" id="file" required>
                                    <small class="text-danger">*) Ukuran maksimum 2Mb</small><br>
                                    <small class="text-danger">*) Hanya upload file dokumen saja. (.pdf)</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <input type="submit" name='submit' class="btn btn-primary" value="Simpan Perubahan">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include './lib/footer.php'; ?>

    <?php include './lib/scripts.php'; ?>
    <script>
        $(function() {
            $('[data-toggle="datepicker"]').datepicker({
                format: 'yyyy-mm-dd',
                autoHide: true,
                zIndex: 2048,
            })
        })
    </script>
</body>
</html>
<?php
    mysqli_close($con);
?>