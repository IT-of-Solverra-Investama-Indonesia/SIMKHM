<?php
include '../rawatjalan/urutan.php';

$id = $_GET['id'];
$shift = $_SESSION['shift'];
$dokter = $_SESSION['dokter_rawat'];
$poli = $_SESSION['admin']['username'];
//var_dump($id)
$ambil = $koneksi->query("SELECT * FROM pasien WHERE idpasien='$_GET[id]' ");
$pecah = $ambil->fetch_assoc();
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

  <style>
    .hidden {
      display: visible;
      height: 0px;
      overflow: hidden;
    }
  </style>
</head>

<body>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    $(document).ready(function() {
      // Fungsi untuk melakukan permintaan AJAX dengan tanggal tertentu
      function fetchAntrian(tanggal) {
        $.ajax({
          url: '../../pasien/antrian_api.php',
          type: 'POST',
          data: {
            tanggal: tanggal
          },
          success: function(response) {
            $('#antrian').html(response);
          }
        });
      }

      // Mendapatkan tanggal hari ini dalam format YYYY-MM-DD
      var today = new Date();
      var year = today.getFullYear();
      var month = String(today.getMonth() + 1).padStart(2, '0'); // Bulan dimulai dari 0
      var day = String(today.getDate()).padStart(2, '0');
      var formattedToday = year + '-' + month + '-' + day;

      // Memanggil fungsi fetchAntrian dengan tanggal hari ini saat halaman dimuat
      fetchAntrian(formattedToday);

      // Menambahkan event listener untuk perubahan pada elemen dengan ID 'jadwal'
      $('#jadwal').change(function() {
        var tanggal = $(this).val();
        fetchAntrian(tanggal);
      });
    });
  </script>


  <main>
    <div class="container">
      <div class="pagetitle">
        <h1>Rawat Jalan</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="index.php" style="color:blue;">Home</a></li>
            <li class="breadcrumb-item active"><a href="index.php?halaman=daftarregistrasi" style="color:blue;">Registrasi</a></li>
            <li class="breadcrumb-item">Buat Registrasi</li>
          </ol>
        </nav>
      </div><!-- End Page Title -->

      <section class="section  py-4">
        <div class="container">
          <div class="row">
            <div class="col-lg-12 col-md-12 d-flex">

              <div class="card" style="max-width: 70%; display: inline-flex; position: absolute;">
                <div class="card-body">
                  <h5 class="card-title">Jenis Pendaftaran</h5>
                  <form class="row g-3" method="post" enctype="multipart/form-data">

                    <div class="col-md-6">
                      <label for="inputState" class="form-label">Jenis Kunjungan</label><br>
                      <input required type="radio" name="jenis_kunjungan" value="Kunjungan Sakit" checked>
                      <label class="form-check-label" for="gridRadios1">
                        Kunjungan Sakit
                      </label>
                      <span>&nbsp;&nbsp;
                        <input required type="radio" name="jenis_kunjungan" value="Kunjungan Sehat">
                        <label class="form-check-label" for="gridRadios2">
                          Kunjungan Sehat
                        </label>
                      </span>
                    </div>
                    <br>
                    <div class="col-md-12">
                      <label for="inputState" class="form-label">Jenis Perawatan</label>
                      <select required id="pilihan" name="perawatan" class="form-select">
                        <option hidden>Pilih</option>
                        <option selected value="Rawat Jalan">Rawat Jalan</option>
                        <option value="Rawat Inap">Rawat Inap</option>
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
                        $getKamar = $koneksi->query("SELECT * FROM kamar LIMIT 10");
                        foreach ($getKamar as $kamar) {
                        ?>
                          <?php $cekKamar = $koneksi->query("SELECT COUNT(*) as jumlah FROM registrasi_rawat WHERE kamar = '$kamar[namakamar]' and status_antri != 'Pulang'")->fetch_assoc(); ?>
                          <?php if ($cekKamar['jumlah'] == 0) { ?>
                            <option value="<?= $kamar['namakamar'] ?>"><?= $kamar['namakamar'] ?></option>
                          <?php } else { ?>
                          <?php } ?>
                        <?php } ?>
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
                      <br>
                      <h5 class="card-title">Data Umum</h5>
                    </div>
                    <!-- Multi Columns Form -->
                    <div class="col-md-6">
                      <label for="inputName5" class="form-label">Nama Pasien</label>
                      <input type="text" class="form-control" name="nama_pasien" id="inputName5" value="<?php echo $pecah['nama_lengkap'] ?>" placeholder="Masukkan Nama Pasien">
                    </div>
                    <input type="hidden" class="form-control" name="id_pasien" id="inputName5" value="<?php echo $pecah['idpasien'] ?>" placeholder="Masukkan Nama Pasien">
                    <div class="col-md-6">
                      <label for="inputName5" class="form-label">No RM Pasien</label>
                      <input type="text" class="form-control" name="no_rm" id="inputName5" value="<?php echo $pecah['no_rm'] ?>" placeholder="Masukkan Nama Pasien">
                    </div>
                    <div class="col-md-6" id="ant">
                      <label for="inputState" class="form-label" style="color: orangered; font-weight:bold">Antrian</label>
                      <select id="antrian" name="antrian" class="form-control" autofocus>
                        <?php
                        date_default_timezone_set('Asia/Jakarta');
                        $date = date('Ymd') + 0;
                        $time = date('Hi') - 300;
                        //var_dump($time);

                        $k = mysqli_query($koneksi, "SELECT kode, urut, ket FROM tgltab 
            WHERE NOT EXISTS(SELECT antrian FROM registrasi_rawat 
                            WHERE registrasi_rawat.kode = tgltab.kode) 
            AND tgl=$date AND jam>=$time 
            ORDER BY tgltab.no ASC");
                        //   $k = $koneksi->query("SELECT * FROM tgltab WHERE tgl>=$tg AND jam>$time ORDER BY tgltab.no ASC");
                        ?>
                        <option value="" width="40">Silahkan Pilih Antrian</option>
                        <?php foreach ($k as $row3) { ?>

                          <option value="<?php echo $row3['urut']; ?>" width="40"><?php echo $row3['ket']; ?> </option>

                        <?php } ?>
                      </select>
                      <!-- <?= $time ?>
                  <?= $tg ?> -->
                    </div>
                    <div class="col-md-6">
                      <label for="inputName5" class="form-label">Jadwal</label>
                      <input type="datetime" class="form-control" name="jadwal" value="<?= date("Y-m-d H:i:s") ?>" placeholder="Masukkan Nama Pasien">
                    </div>
                    <div class="col-md-12">
                      <label for="inputState" class="form-label">Dokter</label>
                      <select id="inputState" name="dokter_rawat" class="form-select">
                        <option value="<?= $dokter ?>" hidden><?= $dokter ?></option>
                        <?php $dokter = $koneksi->query("SELECT * FROM admin where level = 'dokter' ORDER BY namalengkap ASC");
                        foreach ($dokter as $dok) { ?>
                          <option value="<?= $dok['namalengkap'] ?>"><?= $dok['namalengkap'] ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="col-md-12">
                      <label for="inputState" class="form-label">Pembayaran</label>
                      <select id="inputState" name="carabayar" class="form-select" required>
                        <option hidden>Pilih Pembayaran</option>
                        <option value="bpjs">bpjs</option>
                        <option selected value="umum">umum</option>
                        <option value="malam">malam</option>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label for="inputName5" class="form-label">Tanggal Lahir</label>
                      <input type="date" class="form-control" name="tgl_lahir" value="<?php echo $pecah['tgl_lahir'] ?>" id="inputName5" placeholder="Masukkan Tanggal Pasien">
                    </div>
                    <div class="col-md-6">
                      <label for="inputName5" class="form-label">Jenis Kelamin</label>
                      <!-- <input type="text" class="form-control" name="jenis_kelamin" value="<?php echo $pecah['jenis_kelamin'] ?>" id="inputName5" placeholder="Masukkan JK Pasien"> -->
                      <select id="inputState" name="jenis_kelamin" class="form-select">
                        <?php if ($pecah['jenis_kelamin'] != '') { ?>
                          <?php if ($pecah['jenis_kelamin'] == '1') { ?>
                            <option selected value="<?= $pecah['jenis_kelamin'] ?>">Laki-Laki</option>
                          <?php } elseif ($pecah['jenis_kelamin'] == '2') { ?>
                            <option selected value="<?= $pecah['jenis_kelamin'] ?>">Perempuan</option>
                          <?php } ?>
                        <?php } else { ?>
                          <option hidden>Pilih</option>
                        <?php } ?>
                        <option value="1">Laki-Laki</option>
                        <option value="2">Perempuan</option>
                      </select>
                    </div>
                    <div class="col-12" style="margin-top:10px">
                      <label for="inputAddress5" class="form-label">Alamat</label>
                      <input type="text" name="alamat" class="form-control" id="inputAddres5s" value="<?php echo $pecah['alamat'] ?>" placeholder="Masukkan Alamat Pasien">
                    </div>
                    <br>
                    <br>
                    <div>
                      <h5 class="card-title">Data Kontak</h5>
                    </div>
                    <div class="col-md-6">
                      <label for="inputCity" class="form-label">No. HP </label>
                      <input type="text" class="form-control" name="nohp" value="<?php echo $pecah['nohp'] ?>" id="inputCity" placeholder="Masukkan No. HP Pasien">
                    </div>
                    <div class="col-md-6">
                      <label for="inputCity" class="form-label">Email</label>
                      <input type="text" class="form-control" name="email" value="<?php echo $pecah['email'] ?>" id="inputCity" placeholder="Masukkan Email Pasien">
                    </div>
                    <div class="col-md-6" style="margin-top:10px">
                      <label for="inputState" class="form-label">Jenis Kartu Identitas</label>
                      <select id="inputState" class="form-select">
                        <option value="<?php echo $pecah['jenis_identitas'] ?>" hidden><?php echo $pecah['jenis_identitas'] ?></option>
                        <option value="KTP">KTP</option>
                      </select>
                    </div>
                    <div class="col-md-6" style="margin-top:10px">
                      <label for="inputCity" class="form-label">No. Kartu Identitas</label>
                      <input type="text" name="no_identitas" value="<?php echo $pecah['no_identitas'] ?>" class="form-control" id="inputCity" placeholder="Masukkan No. Identitas Pasien">
                    </div>

                    <div class="text-center" style="margin-top: 50px; margin-bottom: 80px;">
                      <button type="submit" name="save" class="btn btn-primary">Simpan</button>
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


</body>

</html>

<?php

if (isset($_POST['save'])) {

  $jenis_kunjungan = htmlspecialchars($_POST["jenis_kunjungan"]);

  $id_pasien = htmlspecialchars($_POST["id_pasien"]);

  $no_rm = htmlspecialchars($_POST["no_rm"]);

  $nama_pasien = htmlspecialchars($_POST["nama_pasien"]);
  $dokter_rawat = htmlspecialchars($_POST["dokter_rawat"]);
  $perawatan = htmlspecialchars($_POST["perawatan"]);
  $jadwal = htmlspecialchars($_POST["jadwal"]);
  $antrian = htmlspecialchars($_POST["antrian"]);

  $tgl2 = date('Y-m-d');
  $tgl = date('Ymd') + 0;
  $kode = $tgl;
  $kode .= "+";
  $kode .= $antrian;

  if ($perawatan == "Rawat Inap") {
    $koneksi->query("INSERT INTO igd (nama_pasien, no_rm, tgl_masuk) VALUES ('$nama_pasien','$no_rm', '$jadwal')");
  } else {
    $koneksi->query("INSERT INTO registrasi_rawat (nama_pasien, dokter_rawat, perawatan, kamar, jenis_kunjungan, id_pasien, no_rm, jadwal, antrian, status_antri, carabayar, shift, kode, petugaspoli, kategori) VALUES ('$nama_pasien', '$dokter_rawat', '$perawatan', '$_POST[kamar]', '$jenis_kunjungan', '$id_pasien', '$no_rm', '$jadwal', '$antrian', 'Belum Datang', '$_POST[carabayar]', '$shift', '$kode', '$poli', 'offline')");
  }

  // $koneksi->query("INSERT INTO log_user 

  // (status_log, username_admin, idadmin)

  // VALUES ('$status_log', '$username_admin', '$idadmin')

  // ");
  // $getToken = curl_init();
  // curl_setopt_array($getToken, array(
  //   CURLOPT_URL => 'https://api-satusehat.kemkes.go.id/oauth2/v1/accesstoken?grant_type=client_credentials',
  //   CURLOPT_RETURNTRANSFER => true,
  //   CURLOPT_ENCODING => '',
  //   CURLOPT_MAXREDIRS => 10,
  //   CURLOPT_TIMEOUT => 0,
  //   CURLOPT_FOLLOWLOCATION => true,
  //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  //   CURLOPT_CUSTOMREQUEST => 'POST',
  //   CURLOPT_POSTFIELDS => 'client_id=pnZFT0j4Hs1FKIqQKeRspG1ncJwauPVKNnrT2OeiuPpP2E3l&client_secret=FNTJCctvzsWjmjb7VHGbdzLT1xLG9FcV8bAWql27GKJ8o9S5iXxHvOQYpi85qzzv',
  //   CURLOPT_HTTPHEADER => array(
  //     'Content-Type: application/x-www-form-urlencoded',
  //     'Authorization: Bearer WVqDq4p8tYLyaNtYtCDytoaJLNJj'
  //   ),
  // ));

  // $responseToken = curl_exec($getToken);

  // curl_close($getToken);
  // // echo $responseToken;
  // $pecahToken = json_decode($responseToken, true);
  // $token = $pecahToken['access_token'];

  // // ID PASIEN
  // $curl = curl_init();
  // curl_setopt_array($curl, array(
  //   CURLOPT_URL => 'https://api-satusehat.kemkes.go.id/fhir-r4/v1/Patient?identifier=https%3A%2F%2Ffhir.kemkes.go.id%2Fid%2Fnik%7C'.$_POST['no_identitas'],
  //   CURLOPT_RETURNTRANSFER => true,
  //   CURLOPT_ENCODING => '',
  //   CURLOPT_MAXREDIRS => 10,
  //   CURLOPT_TIMEOUT => 0,
  //   CURLOPT_FOLLOWLOCATION => true,
  //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  //   CURLOPT_CUSTOMREQUEST => 'GET',
  //   CURLOPT_HTTPHEADER => array(
  //     'Content-Type: application/json',
  //     'Authorization: Bearer '.$token,
  //   ),
  // ));

  // $response = curl_exec($curl);
  // curl_close($curl);
  // $getIHS = json_decode($response, true);

  // $IHSpasien = $getIHS['entry'][0]['resource']['id'];
  // $koneksi->query("UPDATE pasien SET ihs_id='$IHSpasien' WHERE no_identitas='$_POST[no_identitas]' ");


  if ($perawatan == "Rawat Jalan") {

    echo "
    <script>
  
    alert('Data berhasil didaftarkan!');
    document.location.href='index.php?halaman=daftarregistrasi&day';
  
    </script>
  
    ";
  } else {
    echo "
      <script>
    
      alert('Data berhasil didaftarkan!');
      document.location.href='index.php?halaman=daftarigd';
    
      </script>
    
      ";
  }

  if (mysqli_affected_rows($koneksi) > 0) {
  }
}

?>