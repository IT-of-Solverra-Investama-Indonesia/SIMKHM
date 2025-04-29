 <?php
    $date = date("Y-m-d");
    date_default_timezone_set('Asia/Jakarta');
    $petugas = $_SESSION['admin']['namalengkap'];
    $pasien = $koneksi->query("SELECT * FROM pasien  WHERE no_rm='$_GET[id]';")->fetch_assoc();
    if (!isset($_GET['igd'])) {
        $jadwal = $koneksi->query("SELECT * FROM registrasi_rawat  WHERE no_rm='$_GET[id]' AND date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' AND perawatan = 'Rawat Inap' ORDER BY idrawat DESC LIMIT 1")->fetch_assoc();
    }

    $id = $koneksi->query("SELECT * FROM registrasi_rawat  WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' AND perawatan = 'Rawat Inap' ORDER BY idrawat DESC LIMIT 1")->fetch_assoc();

    $ConditionCopy = 0;

    if (isset($_GET['idlpo'])) {
        $ConditionCopy = htmlspecialchars($_GET['idlpo']);
        $dataCopy = $koneksi->query("SELECT * FROM lpogizi WHERE id_lpogizi = '$ConditionCopy'")->fetch_assoc();
    }

    ?>
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <?php if (!isset($_GET['view'])) { ?>
     <main>
         <div class="">
             <div class="pagetitle">
                 <h1>Catatan Penyakit (GIZI) </h1>
                 <nav>
                     <ol class="breadcrumb">
                         <li class="breadcrumb-item active"><a href="index.php?halaman=daftarrmedis" style="color:blue;">Rekam Medis</a></li>
                         <li class="breadcrumb-item">Catatan Penyakit (Gizi)</li>
                     </ol>
                 </nav>
             </div><!-- End Page Title -->
             <form class="row" method="post" enctype="multipart/form-data">
                 <div class="">
                     <div class="row">
                         <div class="col-md-12">
                             <div class="card" style="margin-top:10px; margin-bottom: 10px;" class="mb-0">
                                 <div class="card-body col-md-12">
                                     <h5 class="card-title">Data Pasien | <?php echo $pasien['nama_lengkap'] ?> (<b><?php echo $pasien['no_rm'] ?></b>) | Tgl Lahir : <?php echo date("d-m-Y", strtotime($pasien['tgl_lahir'])) ?> | <b><?php echo $jadwal['kamar'] ?></b></h5>
                                     <!-- Multi Columns Form -->
                                     <div class="row">
                                         <div class="col-md-6">
                                             <!-- <label for="inputName5" class="form-label">Nama Pasien</label> -->
                                             <input type="text" name="pasien" hidden id="inputName5" value="<?php echo $pasien['nama_lengkap'] ?>" placeholder="Masukkan Nama Pasien" readonly>
                                         </div>
                                         <div class="col-md-6" style="margin-bottom:0px;">
                                             <!-- <label for="inputName5" class="form-label">No RM</label> -->
                                             <input type="text" id="inputName5" hidden name="norm" value="<?php echo $pasien['no_rm'] ?>" placeholder="Masukkan Nama Pasien" readonly>
                                         </div>
                                         <div class="col-md-6" style="margin-top: 0px; margin-bottom:0px;">
                                             <!-- <label for="inputName5" class="form-label">Tanggal Lahir</label> -->
                                             <input type="text" id="inputName5" name="tgl_lahir" hidden value="<?php echo date("d-m-Y", strtotime($pasien['tgl_lahir'])) ?>" placeholder="Masukkan Nama Pasien" readonly>
                                         </div>
                                         <div class="col-md-6" style="margin-top: 0px; margin-bottom:0px;">
                                             <!-- <label for="inputName5" class="form-label">Alamat</label> -->
                                             <input type="text" id="inputName5" name="alamat" hidden value="<?php echo $pasien['alamat'] ?>" placeholder="Masukkan Nama Pasien" readonly>
                                         </div>
                                         <?php if (!isset($_GET['igd'])) { ?>
                                             <div class="col-md-6" style="margin-top: 0px; margin-bottom:0px;">
                                                 <!-- <label for="inputName5" class="form-label">Ruangan</label> -->
                                                 <input type="text" id="inputName5" name="kamar" hidden value="<?php echo $jadwal['kamar'] ?>" placeholder="Masukkan Nama Pasien">
                                             </div>
                                         <?php } ?>
                                         <?php if ($pasien["jenis_kelamin"] == 1) { ?>
                                             <div class="col-md-6" style="margin-top: 0px; margin-bottom:0px;">
                                                 <!-- <label for="inputName5" class="form-label">Jenis Kelamin</label> -->
                                                 <input type="text" class="form-control" id="inputName5" name="jenis_kelamin" hidden value="Laki-laki" placeholder="Masukkan Nama Pasien" readonly>
                                             </div>
                                         <?php } else { ?>
                                             <div class="col-md-6" style="margin-top: 0px; margin-bottom:0px;">
                                                 <!-- <label for="inputName5" class="form-label">Jenis Kelamin</label> -->
                                                 <input type="text" class="form-control" id="inputName5" name="jenis_kelamin" hidden value="Perempuan" placeholder="Masukkan Nama Pasien" readonly>
                                             </div>
                                         <?php } ?>
                                     </div>
                                 </div>
                             </div>
                             <button type="button" class="btn btn-sm btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#staticBackdrop" style="width: 200px;">Master</button>
                             <div class="card shadow p-3 mt-0 pt-1" id="observasiZone">
                                 <h5 class="card-title mt-0">Catatan Tatalaksana Gizi</h5>
                                 <div class="row">
                                     <div class="col-md-6">
                                         <label for="">Tanggal & Waktu</label>
                                         <input type="datetime-local" class="form-control mb-3" name="tgl_waktu" value="<?= date('Y-m-d\TH:i') ?>" placeholder="Tanggal dan Waktu">
                                     </div>
                                     <div class="col-md-6">
                                         <label for="">Asesmen</label>
                                         <textarea name="asesmen" id="" class="form-control mb-2" placeholder="Asesmen"><?= $ConditionCopy != 0 ? "$dataCopy[asesmen]" : "" ?></textarea>
                                     </div>
                                     <div class="col-md-6">
                                         <label for="">Diagnosis</label>
                                         <textarea name="diagnosis" id="" class="form-control mb-2" placeholder="Diagnosis"><?= $ConditionCopy != 0 ? "$dataCopy[diagnosis]" : "" ?></textarea>
                                     </div>
                                     <div class="col-md-6">
                                         <label for="">Intervensi</label>
                                         <textarea name="intervensi" id="" class="form-control mb-2" placeholder="Intervensi"><?= $ConditionCopy != 0 ? "$dataCopy[intervensi]" : "" ?></textarea>
                                     </div>
                                     <div class="col-md-6">
                                         <label for="">Monitoring</label>
                                         <textarea name="monitoring" id="" class="form-control mb-2" placeholder="Monitoring"><?= $ConditionCopy != 0 ? "$dataCopy[monitoring]" : "" ?></textarea>
                                     </div>
                                     <div class="col-md-6">
                                         <label for="">Evaluasi</label>
                                         <textarea name="evaluasi" id="" class="form-control mb-2" placeholder="Evaluasi"><?= $ConditionCopy != 0 ? "$dataCopy[evaluasi]" : "" ?></textarea>
                                     </div>
                                     <div class="col-md-6">
                                         <label for="">Instruksi</label>
                                         <textarea name="intruksi" id="" class="form-control mb-2" placeholder="Intruksi"><?= $ConditionCopy != 0 ? "$dataCopy[intruksi]" : "" ?></textarea>
                                     </div>
                                     <div class="col-md-6">
                                         <label for="">Dokter</label>
                                         <input type="text" class="form-control mb-3" name="dokter" <?= $ConditionCopy != 0 ? "value='$dataCopy[dokter]'" : "" ?> readonly value="<?= $_SESSION['dokter_rawat'] ?>" placeholder="">
                                     </div>
                                     <div class="col-md-6">
                                         <label for="">Perawat</label>
                                         <input type="text" class="form-control mb-3" name="perawat" <?= $ConditionCopy != 0 ? "value='$dataCopy[perawat]'" : "" ?> readonly value="<?= $petugas ?>" placeholder="">
                                     </div>

                                     <!-- <button class="btn btn-primary w-40" name="save">Simpan</button> -->
                                 </div>
                                 <div class="text-center" style="margin-top: 10px; margin-bottom: 40px;">
                                     <button type="submit" name="save" class="btn btn-primary">Simpan</button>
                                     <!-- <button type="reset" class="btn btn-secondary">Reset</button> -->
                                 </div>
                             </div>
                             <!-- Modal -->
                             <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                 <div class="modal-dialog modal-lg">
                                     <div class="modal-content">
                                         <div class="modal-header">
                                             <h1 class="modal-title fs-5" id="staticBackdropLabel">Master Data (Riwayat)</h1>
                                             <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                         </div>
                                         <div class="modal-body">
                                             <table class="table table-sm table-striped table-hover" style="font-size: 12px;">
                                                 <thead>
                                                     <tr>
                                                         <th>Pasien</th>
                                                         <th>Diagnosa</th>
                                                         <th>Tanggal</th>
                                                         <th>Jam</th>
                                                         <th>Aksi</th>
                                                     </tr>
                                                 </thead>
                                                 <tbody>
                                                     <?php
                                                        $getlpo = $koneksi->query("SELECT * FROM lpogizi WHERE pasien = '$pasien[nama_lengkap]' AND norm = '$pasien[no_rm]'");
                                                        ?>
                                                     <?php foreach ($getlpo as $data) { ?>
                                                         <?php
                                                            $datetimeString = "$data[tgl_waktu]";
                                                            $datetimeObject = date_create($datetimeString);
                                                            $tanggal = date_format($datetimeObject, "Y-m-d");
                                                            $jam = date_format($datetimeObject, "H:i:s");
                                                            ?>
                                                         <tr>
                                                             <td><?= $data['pasien'] ?></td>
                                                             <td><?= $data['diagnosis'] ?></td>
                                                             <td><?= $tanggal ?></td>
                                                             <td><?= $jam ?></td>
                                                             <td>
                                                                 <a class="btn btn-sm btn-primary" href="index.php?halaman=lpogizi&id=<?= $_GET['id'] ?>&tgl=<?= $_GET['tgl'] ?>&view=<?= $data['id_lpogizi'] ?>"><i class="bi bi-eye"></i></a>
                                                                 <a href="index.php?halaman=lpogizi&id=<?= htmlspecialchars($_GET['id']) ?>&tgl=<?= htmlspecialchars($_GET['tgl']) ?>&idlpo=<?= $data['id_lpogizi'] ?>#observasiZone" class="btn btn-sm btn-warning">Copy</a>
                                                             </td>
                                                         </tr>
                                                     <?php } ?>
                                                 </tbody>
                                             </table>
                                         </div>
                                         <div class="modal-footer">
                                             <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                             <!-- <button type="button" class="btn btn-primary">Understood</button> -->
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </form>
         </div>
     </main>
     <?php
        if (isset($_POST['save'])) {
            $koneksi->query("INSERT INTO lpogizi (pasien, norm, tgl_lahir, alamat, kamar, jenis_kelamin, asesmen, diagnosis, tgl_waktu, intervensi, monitoring, evaluasi, intruksi, perawat, dokter) VALUES ('$_POST[pasien]', '$_POST[norm]', '$_POST[tgl_lahir]', '$_POST[alamat]', '$_POST[kamar]', '$_POST[jenis_kelamin]', '$_POST[asesmen]', '$_POST[diagnosis]', '$_POST[tgl_waktu]', '$_POST[intervensi]', '$_POST[monitoring]', '$_POST[evaluasi]', '$_POST[intruksi]', '$_POST[perawat]','$_POST[dokter]' )");
            echo "
                <script>
                alert('Yey Berhasil!');
                document.location.href='index.php?halaman=lpogizi&id=$_GET[id]&inap&tgl=$_GET[tgl]';
                </script>
                ";
        } ?>
     </body>

     </html>
 <?php } else { ?>
     <?php $lpo = $koneksi->query("SELECT * FROM lpogizi WHERE id_lpogizi = '$_GET[view]'")->fetch_assoc(); ?>
     <!DOCTYPE html>
     <html lang="en">

     <head>
         <meta charset="utf-8">
         <meta content="width=device-width, initial-scale=1.0" name="viewport">

         <title>KHM WONOREJO</title>
         <meta content="" name="description">
         <meta content="" name="keywords">
         <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
         <script rel="javascript" type="text/javascript" href="js/jquery-1.11.3.min.js"></script>
         <!-- Select2 CSS -->
         <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

         <!-- jQuery -->
         <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

         <!-- Select2 JS -->
         <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>


     </head>

     <body>
         <main>
             <div class="container">
                 <div class="pagetitle">
                     <h1>LAPORAN OBSERVASI PERAWAT (GIZI)</h1>
                     <nav>
                         <ol class="breadcrumb">
                             <li class="breadcrumb-item active"><a href="index.php?halaman=daftarrmedis" style="color:blue;">Rekam Medis</a></li>
                             <li class="breadcrumb-item">Laporan Observasi Perawat (Gizi)</li>
                         </ol>
                     </nav>
                 </div><!-- End Page Title -->
                 <form class="row g-3" method="post" enctype="multipart/form-data">
                     <div class="container">
                         <div class="row">
                             <div class="col-md-12">
                                 <a href="index.php?halaman=lpogizi&id=<?= $_GET['id'] ?>&tgl=<?= $_GET['tgl'] ?>" class="btn btn-sm btn-dark">Kembali</a>
                                 <div class="card" style="margin-top:10px">
                                     <div class="card-body col-md-12">
                                         <h5 class="card-title">Data Pasien</h5>
                                         <!-- Multi Columns Form -->
                                         <div class="row">
                                             <div class="col-md-6">
                                                 <label for="inputName5" class="form-label">Nama Pasien</label>
                                                 <input type="text" class="form-control" name="pasien" id="inputName5" value="<?php echo $pasien['nama_lengkap'] ?>" placeholder="Masukkan Nama Pasien" readonly>
                                             </div>
                                             <div class="col-md-6" style="margin-bottom:20px;">
                                                 <label for="inputName5" class="form-label">No RM</label>
                                                 <input type="text" class="form-control" id="inputName5" name="norm" value="<?php echo $pasien['no_rm'] ?>" placeholder="Masukkan Nama Pasien" readonly>
                                             </div>
                                             <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                                                 <label for="inputName5" class="form-label">Tanggal Lahir</label>
                                                 <input type="text" class="form-control" id="inputName5" name="tgl_lahir" value="<?php echo date("d-m-Y", strtotime($pasien['tgl_lahir'])) ?>" placeholder="Masukkan Nama Pasien" readonly>
                                             </div>
                                             <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                                                 <label for="inputName5" class="form-label">Alamat</label>
                                                 <input type="text" class="form-control" id="inputName5" name="alamat" value="<?php echo $pasien['alamat'] ?>" placeholder="Masukkan Nama Pasien" readonly>
                                             </div>
                                             <?php if (!isset($_GET['igd'])) { ?>
                                                 <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                                                     <label for="inputName5" class="form-label">Ruangan</label>
                                                     <input type="text" class="form-control" id="inputName5" name="kamar" value="<?php echo $jadwal['kamar'] ?>" placeholder="Masukkan Nama Pasien">
                                                 </div>
                                             <?php } ?>
                                             <?php if ($pasien["jenis_kelamin"] == 1) { ?>
                                                 <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                                                     <label for="inputName5" class="form-label">Jenis Kelamin</label>
                                                     <input type="text" class="form-control" id="inputName5" name="jenis_kelamin" value="Laki-laki" placeholder="Masukkan Nama Pasien" readonly>
                                                 </div>
                                             <?php } else { ?>
                                                 <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                                                     <label for="inputName5" class="form-label">Jenis Kelamin</label>
                                                     <input type="text" class="form-control" id="inputName5" name="jenis_kelamin" value="Perempuan" placeholder="Masukkan Nama Pasien" readonly>
                                                 </div>
                                             <?php } ?>
                                         </div>
                                     </div>
                                 </div>
                                 <br>
                                 <div class="card shadow p-3">
                                     <h5 class="card-title">OBSERVASI PERAWAT</h5>
                                     <div class="row">
                                         <div class="col-md-6">
                                             <label for="">Tanggal & Waktu</label>
                                             <input type="datetime-local" readonly value="<?= $lpo['tgl_waktu'] ?>" class="form-control mb-3" name="tgl_waktu" id="" placeholder="Tanggal dan Waktu">
                                         </div>
                                         <div class="col-md-6">
                                             <label for="">Asesmen</label>
                                             <textarea readonly name="asesmen" id="" class="form-control mb-2" placeholder="Keluahan Pasien"><?= $lpo['asesmen'] ?></textarea>
                                         </div>
                                         <div class="col-md-6">
                                             <label for="">Diagnosis</label>
                                             <textarea readonly name="diagnosis" id="" class="form-control mb-2" placeholder="Keluahan Pasien"><?= $lpo['diagnosis'] ?></textarea>
                                         </div>
                                         <div class="col-md-6">
                                             <label for="">Intervensi</label>
                                             <textarea readonly name="intervensi" id="" class="form-control mb-2" placeholder="Keluahan Pasien"><?= $lpo['intervensi'] ?></textarea>
                                         </div>
                                         <div class="col-md-6">
                                             <label for="">Monitoring</label>
                                             <textarea readonly name="monitoring" id="" class="form-control mb-2" placeholder="Keluahan Pasien"><?= $lpo['monitoring'] ?></textarea>
                                         </div>
                                         <div class="col-md-6">
                                             <label for="">Evaluasi</label>
                                             <textarea readonly name="evaluasi" id="" class="form-control mb-2" placeholder="Keluahan Pasien"><?= $lpo['evaluasi'] ?></textarea>
                                         </div>
                                         <div class="col-md-6">
                                             <label for="">Intruksi</label>
                                             <textarea readonly name="intruksi" id="" class="form-control mb-2" placeholder="Keluahan Pasien"><?= $lpo['intruksi'] ?></textarea>
                                         </div>
                                         <div class="col-md-6">
                                             <label for="">Dokter</label>
                                             <input type="text" class="form-control mb-3" readonly value="<?= $lpo['dokter'] ?>" name="dokter" id="" value="<?= $_SESSION['dokter_rawat'] ?>" placeholder="Cairan Infus">
                                         </div>
                                         <div class="col-md-6">
                                             <label for="">Perawat</label>
                                             <input type="text" class="form-control mb-3" readonly value="<?= $lpo['perawat'] ?>" name="perawat" readonly value="<?= $petugas ?>" placeholder="">
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </form>
             </div>
         </main>
     </body>

     </html>
 <?php } ?>