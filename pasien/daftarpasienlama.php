<?php session_start();?>

<?php 
if (!isset($_SESSION['pasien']['nama_lengkap'])) {
    header("Location: login.php");
    exit();
}
include '../admin/rawatjalan/urutan.php';

// $id=$_GET['id'];
// $shift = $_SESSION['shift'];
// $dokter=$_SESSION['dokter_rawat'];
// $poli=$_SESSION['admin']['username'];

//var_dump($id)
$idPasien = sani($_SESSION['pasien']['idpasien']);
$ambil = $koneksi->prepare("SELECT * FROM pasien WHERE idpasien = ?");
$ambil->bind_param("s", $idPasien);
$ambil->execute();
$pecah = $ambil->get_result()->fetch_assoc();
date_default_timezone_set('Asia/Jakarta');

 ?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>KHM WONOREJO</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <link rel="icon" type="image/png" href="../admin/dist/assets/img/logokhm.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" integrity="sha384-4LISF5TTJX/fLmGSxO53rV4miRxdg84mZsxmO8Rx5jGtp/LbrixFETvWa5a6sESd" crossorigin="anonymous">
  </head>
   
  <style>
    .hidden{
      display : visible;
      height: 0px;
      overflow: hidden;
    }
  </style>
</head>
 <body>

   <main>
    <div class="container">
      <div class="pagetitle"><br>
      <!-- <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active"><a href="index.php" style="color:blue;">Home</a></li>
          <li class="breadcrumb-item active"><a href="index.php?halaman=daftarregistrasi" style="color:blue;">Registrasi</a></li>
          <li class="breadcrumb-item">Buat Registrasi</li>
        </ol>
      </nav> -->
    </div><!-- End Page Title -->

      <section class="section  py-4">
        <div class="container">
        <h1>Rawat Jalan</h1><br>

          <div class="row">
            <div class="col-lg-12 col-md-12 d-flex">

            <div class="card" style="max-width: 85%; display: inline-flex; position: absolute;">
            <div class="card-body">
              <h5 class="card-title">Pendaftaran Online</h5>
              <form class="row g-3" method="post" enctype="multipart/form-data">

                <!-- <div class="col-md-6">
                  <label for="inputState" class="form-label">Jenis Kunjungan</label><br>
                       <input type="radio" name="jenis_kunjungan" value="Kunjungan Sakit">
                      <label class="form-check-label" for="gridRadios1">
                        Kunjungan Sakit
                      </label>
                    <span>&nbsp;&nbsp;
                      <input type="radio" name="jenis_kunjungan" value="Kunjungan Sehat">
                      <label class="form-check-label" for="gridRadios2">
                        Kunjungan Sehat
                      </label>
                    </span>
                </div> -->
                <br>
                <div class="col-md-12">
                  <label for="inputState" class="form-label">Jenis Perawatan</label>
                  <select id="pilihan" name="perawatan" class="form-select">
                    <!-- <option hidden>Pilih</option> -->
                    <option value="Rawat Jalan">Rawat Jalan</option>
                    <!-- <option value="Rawat Inap">Rawat Inap</option> -->
                  </select>
                </div>
                <!-- <div class="hidden" id="kamar">
                  <div class="row">
                    <div class="col-md-12">
                      <label for="inputState" class="form-label">Kamar / Ruangan</label> -->
                      <!-- <input type="text" class="form-control" value="" name="kamar"> -->
                      <!-- <select name="kamar" class="form-select" id="">
                        <option hidden>Pilih Kamar</option>
                        <?php
                          $getKamarStmt = $koneksi->prepare("SELECT * FROM kamar LIMIT 10");
                          $getKamarStmt->execute();
                          $getKamarResult = $getKamarStmt->get_result();
                          while($kamar = $getKamarResult->fetch_assoc()){
                          $namakamar = sani($kamar['namakamar']);
                          $cekKamarStmt = $koneksi->prepare("SELECT COUNT(*) as jumlah FROM registrasi_rawat WHERE kamar = ? and status_antri != 'Pulang'");
                          $cekKamarStmt->bind_param("s", $namakamar);
                          $cekKamarStmt->execute();
                          $cekKamar = $cekKamarStmt->get_result()->fetch_assoc();
                          if($cekKamar['jumlah'] == 0){
                        ?>
                          <option value="<?= $namakamar ?>"><?= $namakamar ?></option>
                        <?php
                          }
                          $cekKamarStmt->close();
                          }
                          $getKamarStmt->close();
                        ?>
                      </select>
                    </div>
                  </div>
                </div> -->
                <script>
                  document.getElementById('pilihan').addEventListener('change', function() {
                    var formAn = document.getElementById('ant');
                    if (this.value === 'Rawat Inap') {
                      // formLain.classList.remove('hidden');
                      formAn.classList.add('hidden');
                    } else {
                      // formLain.classList.add('hidden');
                      formAn.classList.remove('hidden');
                    }
                  });
                </script>
            
            <div>
              <h5 class="card-title">Data Umum</h5>
            </div>
              <!-- Multi Columns Form -->
                <div class="col-md-6">
                  <label for="inputName5" class="form-label">Nama Pasien</label>
                  <input type="text" class="form-control" name="nama_pasien" id="inputName5" value="<?php echo $pecah['nama_lengkap']?>" placeholder="Masukkan Nama Pasien" readonly>
                </div>
                <input type="hidden" class="form-control" name="id_pasien" id="inputName5" value="<?php echo $pecah['idpasien']?>" placeholder="Masukkan Nama Pasien">
                <div class="col-md-6">
                  <label for="inputName5" class="form-label">No RM Pasien</label>
                  <input type="text" class="form-control" name="no_rm" id="inputName5" value="<?php echo $pecah['no_rm']?>" placeholder="Masukkan Nama Pasien" readonly>
                </div>
                <div class="col-md-6">
                  <label for="inputName5" class="form-label">Jadwal</label>
                  <input type="datetime-local" class="form-control" required name="jadwal" id="jadwal" placeholder="Masukkan Nama Tanggal">
                </div>
                
                 <div class="col-md-6" id="ant">
                  <label for="inputState" class="form-label" style="color: orangered; font-weight:bold">Antrian</label>
                  <select id="antrian" name="antrian" class="form-control" required>
                   <option value="" width="40">Silahkan Pilih Antrian</option>  
                  </select>
                  <!-- <?= $time?>
                  <?= $tg?> -->
                </div>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                <script>
                    $(document).ready(function() {
                      $('#jadwal').change(function() {  
                          var tanggal = $(this).val();
                          $.ajax({
                              url: 'antrian_api.php',
                              type: 'POST',
                              data: { tanggal: tanggal },
                              success: function(response) {
                                  $('#antrian').html(response);
                              }
                          });
                        });
                    });
                </script>

                <!-- <div class="col-md-12">
                  <label for="inputState" class="form-label">Dokter</label>
                  <select id="inputState" name="dokter_rawat" class="form-select">
                    <option value="<?= $dokter ?>" hidden><?= $dokter ?></option>
                    <?php $dokter = $koneksi->query("SELECT * FROM admin where level = 'dokter' ORDER BY namalengkap ASC"); foreach($dokter as $dok){ ?>
                    <option value="<?= $dok['namalengkap'] ?>"><?= $dok['namalengkap'] ?></option>
                    <?php } ?>
                  </select>
                </div> -->
                <div class="col-md-12">
                  <label for="inputState" class="form-label">Pembayaran</label>
                  <select id="inputState" name="carabayar" class="form-select" required>
                    <option hidden>Pilih Pembayaran</option>
                    <option value="bpjs">bpjs</option>
                    <option value="umum">umum</option>
                  </select>
                </div>
                <div class="col-md-12">
                  <label for="inputState" class="form-label">Keluhan</label>
                  <textarea class="form-control" name="keluhan" id="keluhan" placeholder="Keluhan"></textarea>
                </div>
               <!-- <div class="col-md-6">
                  <label for="inputName5" class="form-label">Tanggal Lahir</label>
                  <input type="hidden" class="form-control" name="tgl_lahir" value="<?php echo $pecah['tgl_lahir']?>" id="inputName5" placeholder="Masukkan Tanggal Pasien">
                </div>
                <div class="col-md-6">
                  <label for="inputName5" class="form-label">Jenis Kelamin</label>
                  <input type="text" class="form-control" name="jenis_kelamin" value="<?php echo $pecah['jenis_kelamin']?>" id="inputName5" placeholder="Masukkan JK Pasien">
                  <select id="inputState" name="jenis_kelamin" class="form-select">
                    <?php if($pecah['jenis_kelamin'] != ''){?>
                      <?php if($pecah['jenis_kelamin'] == '1'){?>
                        <option selected value="<?= $pecah['jenis_kelamin']?>">Laki-Laki</option>
                      <?php }elseif($pecah['jenis_kelamin'] =='2'){?>
                        <option selected value="<?= $pecah['jenis_kelamin']?>">Perempuan</option>
                      <?php }?>
                    <?php }else{?>
                      <option hidden>Pilih</option>
                    <?php }?>
                    <option value="1">Laki-Laki</option>
                    <option value="2">Perempuan</option>
                  </select>
                </div>
                <div class="col-12" style="margin-top:10px">
                  <label for="inputAddress5" class="form-label">Alamat</label>
                  <input type="text" name="alamat" class="form-control" id="inputAddres5s" value="<?php echo $pecah['alamat']?>" placeholder="Masukkan Alamat Pasien">
                </div>
                <br>
                <br>
                <div>
                  <h5 class="card-title">Data Kontak</h5>
                </div>
                <div class="col-md-6">
                  <label for="inputCity" class="form-label">No. HP </label>
                  <input type="text" class="form-control" name="nohp" value="<?php echo $pecah['nohp']?>" id="inputCity" placeholder="Masukkan No. HP Pasien">
                </div> 
                <div class="col-md-6">
                  <label for="inputCity" class="form-label">Email</label>
                  <input type="text" class="form-control" name="email" value="<?php echo $pecah['email']?>" id="inputCity" placeholder="Masukkan Email Pasien">
                </div>
                <div class="col-md-6" style="margin-top:10px">
                  <label for="inputState" class="form-label">Jenis Kartu Identitas</label>
                  <select id="inputState" class="form-select">
                    <option value="<?php echo $pecah['jenis_identitas']?>" hidden><?php echo $pecah['jenis_identitas']?></option>
                    <option value="KTP">KTP</option>
                  </select>
                </div>
                <div class="col-md-6" style="margin-top:10px">
                  <label for="inputCity" class="form-label">No. Kartu Identitas</label>
                  <input type="text" name="no_identitas" value="<?php echo $pecah['no_identitas']?>" class="form-control" id="inputCity" placeholder="Masukkan No. Identitas Pasien">
                </div>
                 -->
                <div class="text-center" style="margin-top: 50px; margin-bottom: 40px;">
                  <button type="submit" name="save" class="btn btn-success">Simpan</button>
                  <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
              </form><!-- End Multi Columns Form -->

            </div>
          </div>
        </div>
    
            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
  </body>
</html>

<?php 

if (isset ($_POST['save'])) 
{
  // $jenis_kunjungan=sani($_POST["jenis_kunjungan"]);

  $id_pasien = sani($_POST["id_pasien"]);
  $no_rm = sani($_POST["no_rm"]);
  $nama_pasien = sani($_POST["nama_pasien"]);
  // $dokter_rawat = sani($_POST["dokter_rawat"]);
  $perawatan = sani($_POST["perawatan"]);
  $jadwal = sani($_POST["jadwal"]);
  $antrian = sani($_POST["antrian"]);
  $keluhan = sani($_POST["keluhan"]);
  $carabayar = sani($_POST["carabayar"]);

  $tgl2 = date('Y-m-d');
  $tgl = date('Ymd', strtotime($jadwal)) + 0;
  $kode = $tgl;
  $kode .= "+";
  $kode .= $antrian;

  if ($perawatan == "Rawat Inap") {
    $stmt = $koneksi->prepare("INSERT INTO igd (nama_pasien, no_rm, tgl_masuk) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nama_pasien, $no_rm, $jadwal);
    $stmt->execute();
    $stmt->close();
  } else {
    $stmt = $koneksi->prepare("INSERT INTO registrasi_rawat (nama_pasien, perawatan, jenis_kunjungan, id_pasien, no_rm, jadwal, antrian, status_antri, carabayar, kode, keluhan, kategori) VALUES (?, ?, 'Kunjungan Sakit', ?, ?, ?, ?, 'Belum Datang', ?, ?, ?, 'online')");
    $stmt->bind_param("ssssssssss", $nama_pasien, $perawatan, $id_pasien, $no_rm, $jadwal, $antrian, $carabayar, $kode, $keluhan);
    $stmt->execute();
    $stmt->close();
  }

  if ($perawatan == "Rawat Jalan") {
    echo "
    <script>
      alert('Berhasil Daftar!');
      document.location.href='../pasien/riwayatdaftar.php';
    </script>
    ";
  }
  // else{
  //   echo "
  //   <script>
  //   alert('Data berhasil didaftarkan!');
  //   document.location.href='index.php?halaman=daftarigd';
  //   </script>
  //   ";
  // }

  // You may check affected rows if needed
  // if ($koneksi->affected_rows > 0) {
  // }
}

?>