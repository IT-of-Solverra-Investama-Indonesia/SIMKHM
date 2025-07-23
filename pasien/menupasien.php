<?php session_start(); ?>
<?php
  error_reporting(0);
  if (!isset(($_SESSION['pasien']['nama_lengkap']))) {
    header("Location: login.php");
    exit();
  }
  if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
  }

  include "function.php";

  $nik = sani($_SESSION['pasien']['no_identitas']);
  $norm = sani($_SESSION['pasien']['no_rm']);

  $stmt = $koneksi->prepare("SELECT * FROM pasien WHERE no_identitas=? or no_rm=?;");
  $stmt->bind_param("ss", $nik, $norm);
  $stmt->execute();
  $admin = $stmt->get_result()->fetch_assoc();
  $stmt->close();
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
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" integrity="sha384-4LISF5TTJX/fLmGSxO53rV4miRxdg84mZsxmO8Rx5jGtp/LbrixFETvWa5a6sESd" crossorigin="anonymous">
  <link href="assets/css/style.css" rel="stylesheet"> -->

  <!-- Favicons -->
  <link href="assets/img/khm.png" rel="icon">
  <link href="assets/img/icons8-hospital-3-100.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

  <!-- DATATABLES -->
  <link href="https://code.jquery.com/jquery-3.5.1.js" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.2/js/dataTables.bootstrap5.min.js" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
<style>
  .btn-outline-success:hover{
    font-size: 18px;

  }
  
</style>

</head>

<body>



  <main>
    <div class="container">
      <div class="pagetitle">
        <nav>
          <!-- <ol class="breadcrumb">
          <li class="breadcrumb-item active"><a href="index.php" style="color:blue;">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol> -->
        </nav>
      </div><!-- End Page Title -->

      <br>
      <div class="container">
        <h3>Selamat Datang, <?= $_SESSION['pasien']['nama_lengkap'] ?></h3><br>

        <br>
        <div class="row">
          <!-- Left side columns -->
          <div class="col-md-12">
            <div class="row">
              <!-- Sales Card -->
              <div class="col-md-12">
                <div class="card info-card sales-card">
                  <div class="card-body">
                    <h5 class="card-title">Jadwal Hari Ini<span> | No. Antrian:</span></h5>

                    <div class="d-flex align-items-center">
                      <div class="btn btn-light" style="font-size: 2em;">
                        <i class="bi bi-file-text" style="color:#157347;"></i>
                      </div>
                      <div class="ps-3">
                        <?php
                        $hari_ini = date('Y-m-d');
                        $hari_ini_sani = sani($hari_ini);
                        $norm_sani = sani($norm);

                        // Query untuk $antrian
                        $stmt_antrian = $koneksi->prepare("SELECT * FROM tgltab INNER JOIN registrasi_rawat WHERE DATE_FORMAT(jadwal, '%Y-%m-%d') = ? AND no_rm = ? AND registrasi_rawat.kode = tgltab.kode");
                        $stmt_antrian->bind_param("ss", $hari_ini_sani, $norm_sani);
                        $stmt_antrian->execute();
                        $antrian = $stmt_antrian->get_result()->fetch_assoc();
                        $stmt_antrian->close();

                        // Query untuk $jumlah_pasien_hari
                        $stmt_jumlah = $koneksi->prepare("SELECT jadwal, antrian FROM registrasi_rawat WHERE DATE_FORMAT(jadwal, '%Y-%m-%d') = ? AND no_rm = ?");
                        $stmt_jumlah->bind_param("ss", $hari_ini_sani, $norm_sani);
                        $stmt_jumlah->execute();
                        $jumlah_pasien_hari = $stmt_jumlah->get_result()->fetch_assoc();
                        $stmt_jumlah->close();
                        ?>
                        <?php if (!empty($jumlah_pasien_hari['jadwal']) or !empty($jumlah_pasien_hari['antrian'])) { ?>
                          <div style="font-size: 2em; font-weight: bold; color: orangered">
                            <?= $jumlah_pasien_hari['antrian'] ?>
                          </div>
                          <div style="font-size: 0.9em; font-weight: bold; color: darkred">(<?= $antrian['ket'] ?>)</div>
                          <div style="font-size: 0.9em; font-weight: bold; margin-top:3px">Mohon datang tepat waktu, bila
                            terlambat, akan hangus dan antri ulang.</div>
                        <?php } else { ?>
                          <div style="font-size: 0.8em; font-weight: bold">Anda Belum Ada Jadwal Booking Hari Ini</div>
                        <?php } ?>
                      </div>

                    </div>
                  </div>
                </div>

                <div class="row">
                  <!-- Sales Card -->
                  <div class="col-md-12">
                    <div class="card info-card sales-card">
                      <div class="card-body">
                        <h5 class="card-title">Riwayat Kedatangan <?= $_SESSION['pasien']['nama_lengkap'] ?>: <span></span></h5>

                        <div class="d-flex align-items-center">
                          <div class="btn btn-light" style="font-size: 2em;">
                            <i class="bi bi-person" style="color:#157347;"></i>
                          </div>
                          <div class="ps-3">
                            <?php
                            $hari_ini = date('Y-m-d');
                            $hari_ini_sani = sani($hari_ini);
                            $no_rm_sani = sani($norm);

                            $stmt_jumlah_pasien = $koneksi->prepare("SELECT COUNT(*) AS idrawat FROM registrasi_rawat WHERE no_rm=? AND perawatan='Rawat Jalan' AND status_antri='Datang' AND perawat=''");
                            $stmt_jumlah_pasien->bind_param("s", $no_rm_sani);
                            $stmt_jumlah_pasien->execute();
                            $result_jumlah_pasien = $stmt_jumlah_pasien->get_result()->fetch_assoc();
                            $stmt_jumlah_pasien->close();
                            $jumlah_pasien_hari = $result_jumlah_pasien['idrawat'];
                            ?>

                            <?php if (!empty($jumlah_pasien_hari)) { ?>
                              <div style="font-size: 2em; font-weight: bold; color: darkgreen"><?= $jumlah_pasien_hari ?>
                              </div>
                            <?php } else { ?>
                              <div style="font-size: 2em; font-weight: bold; color: darkgreen">0</div>
                            <?php } ?>

                          </div>

                        </div>
                      </div>
                    </div>
                  </div>
                </div>


                <div class="row" >
                  <div class="col-12">
                    <a href="../kosmetik/chat.php"
                      style="text-decoration:none; margin-top:18px;background-color:white;color:green;  width:100%"
                      class="btn btn-outline-success" id="btnn"><i class="bi-chat-dots"
                        style="font-size: 1.5em; color:green;"></i><br>
                      Chat Konsultasi
                    </a>
                  </div>
                </div>
                <div class="row">

                  <div class="col-6">
                    <a href="../pasien/daftarpasienlama.php"
                      style="text-decoration:none; margin-top:18px; background-color:white;color:green; width:100%"
                      class="btn btn-outline-success" id="btnn"><i class="bi bi-person-lines-fill"
                        style="font-size: 1.5em; color:#157347;"></i><br>
                      Pendaftaran
                    </a>
                  </div>
                  <div class="col-6">
                    <a href="../pasien/riwayatdaftar.php"
                      style="text-decoration:none; margin-top:18px; background-color:white;color:green; width:100%"
                      class="btn btn-outline-success" id="btnn">
                      <i class="bi bi-clock-history" style="font-size: 1.5em; color:#157347;"></i><br> Riwayat Daftar
                    </a>
                  </div>
                  <!-- <div class="col-6">
                  <a href="../pasien/profile.php" style="text-decoration:none; margin-top:18px; background-color:white; width:100%" class="btn btn-outline-success" id="btnn">
                      <i class="bi bi-person-circle" style="font-size: 1.5em; color:#157347;"></i><br> Profile
                </a> -->
                </div>
                <div class="col-12" style="margin-bottom:50px">
                  <a href="menupasien.php?logout"
                    style="text-decoration:none; margin-top:18px; background-color:white;color:green; width:100%"
                    class="btn btn-outline-success" id="btnn">
                    <i class="bi bi-box-arrow-left" style="font-size: 1.5em; color: #157347;"></i><br> Logout
                  </a>
                </div>
              </div>
              <!-- <div class="col-sm-3">
                <div class="card" id="card" style="margin-top: 20px;">
                <a href="" style="text-decoration:none"><div class="card-body">
                        <p style="font-weight:bold; font-size: 18px;color:#01240a;"><span><i class="bi bi-file"></i></span>Daftar</p>
                </a>
                </div>
            </div>
            </div> -->

            </div>




            <style>
              #card {
                box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
                transition: 0.3s;
                height: 70px;
              }

              #btnn {
                box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
                transition: 0.2s;
              }
            </style>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
              integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
              crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
              integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
              crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
              integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
              crossorigin="anonymous"></script>
</body>

</html>