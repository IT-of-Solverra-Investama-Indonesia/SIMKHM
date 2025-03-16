<?php

session_start();
$session_duration = 8 * 60 * 60;
if (isset($_SESSION['login_time'])) {
  if (time() - $_SESSION['login_time'] > $session_duration) {
    // Redirect ke halaman login atau berikan pesan sesi kedaluwarsa
    header("location:logout.php");
  }
}
include 'function.php';
$username = $_SESSION['admin']['username'];
$level = $_SESSION['admin']['level'];



if (!isset($_SESSION['login'])) {

  header("location:login.php");

  exit;
}


?>




<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <!-- <meta content="width=device-width, initial-scale=1.0" name="viewport"> -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />


  <title>Dashboard - SIM KHM</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/khm.png" rel="icon">
  <link href="assets/img/icons8-hospital-3-100.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> -->
  <?php if (!isset($_GET['halaman'])) { ?>
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
  <?php } ?>
  <!-- DATATABLES -->
  <link href="https://code.jquery.com/jquery-3.5.1.js" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.2/js/dataTables.bootstrap5.min.js" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css" rel="stylesheet">



  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceAdmin - v2.5.0
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index.php" class="logo d-flex align-items-center">
        <img src="assets/img/logokhm.png" alt="">
        <span class="d-none d-lg-block">SIM KHM </span>
      </a>
      <div>
        <i class="bi bi-list toggle-sidebar-btn"></i>

      </div>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="../tenagamedis/foto/<?php echo $_SESSION['admin']['foto']; ?>" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block ps-2"><?= $username ?></span>
          </a><!-- End Profile Iamge Icon -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
      <li class="nav-heading">
        <b><?= $_SESSION['dokter_rawat'] ?> <br> Shift : <?= $_SESSION['shift'] ?></b>
      </li>

      <li class="nav-item">
        <a class="nav-link " href="index.php">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <!-- End Dashboard Nav -->

      <?php if ($level == 'perawat' or $level == 'ceo' or $level == 'rekam medis' or $level == 'sup' or $level == 'inap' or $level == 'gizi' or $level == 'apoteker') { ?>

        <li class="nav-heading">Perawat</li>

        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-menu-button-wide"></i><span>Rawat Jalan</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              <a href="index.php?halaman=daftarregistrasi&day">
                <i class="bi bi-circle"></i><span>Kajian Awal</span>
              </a>
            </li>
            <li>
              <a href="index.php?halaman=daftarregistrasi&all">
                <i class="bi bi-circle"></i><span>Pendaftaran (Diagnosis)</span>
              </a>
            </li>
          </ul>
        </li><!-- End Components Nav -->

        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#components-navv" data-bs-toggle="collapse" href="#">
            <i class="bi bi-menu-button-wide"></i><span>Rawat Inap</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="components-navv" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              <a href="index.php?halaman=daftarregistrasiinap">
                <i class="bi bi-circle"></i><span>Kajian Awal</span>
              </a>
            </li>
            <li>
              <a href="index.php?halaman=daftarrmedis&inap">
                <i class="bi bi-circle"></i><span>Kajian Ranap</span>
              </a>
            </li>
            <li>
              <a href="index.php?halaman=visitedokter">
                <i class="bi bi-circle"></i><span>Visite Dokter</span>
              </a>
            </li>
            <!-- <li>
            <a href="index.php?halaman=reservasirawat">
              <i class="bi bi-circle"></i><span>Reservasi</span>
            </a>
          </li> -->
          </ul>
        </li><!-- End Components Nav -->
      <?php } ?>


      <?php if ($level == 'dokter' or $level == 'ceo' or $level == 'rekam medis' or $level == 'sup') { ?>

        <li class="nav-heading">Dokter</li>

        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-journal-text"></i><span>Rekam Medis</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              <a href="index.php?halaman=daftarrmedis">
                <i class="bi bi-circle"></i><span>Kajian Dokter (Rawat Jalan)</span>
              </a>
            </li>
            <li>
              <a href="index.php?halaman=daftarrmedis&inap">
                <i class="bi bi-circle"></i><span>Kajian Dokter (Rawat Inap)</span>
              </a>
            </li>
            <li>
              <a href="index.php?halaman=verif_obat">
                <i class="bi bi-circle"></i><span>Verifikasi Obat Dokter</span>
              </a>
            </li>
            <li>
              <a href="index.php?halaman=daftarigd">
                <i class="bi bi-circle"></i><span>IGD</span>
              </a>
            </li>
            <li>
              <a href="index.php?halaman=visitedokter">
                <i class="bi bi-circle"></i><span>Visite Dokter</span>
              </a>
            </li>
            <li>
              <a href="index.php?halaman=gajidokter">
                <i class="bi bi-circle"></i><span>Gaji Dokter</span>
              </a>
            </li>
            <li>
              <a href="index.php?halaman=gajidokter_history">
                <i class="bi bi-circle"></i><span>Riwayat Gaji Dokter</span>
              </a>
            </li>
            <li>
              <a href="index.php?halaman=daftarterapi">
                <i class="bi bi-circle"></i><span>Pemeriksaan</span>
              </a>
            </li>

            <li>
              <a href="index.php?halaman=daftarradio">
                <i class="bi bi-circle"></i><span>Radiologi</span>
              </a>
            </li>
            <li>
              <a href="index.php?halaman=rekammedisall">
                <i class="bi bi-circle"></i><span>Resume</span>
              </a>
            </li>
            <li>
              <a href="index.php?halaman=rekammedisall&pemeriksaan">
                <i class="bi bi-circle"></i><span>Riwayat Pemeriksaan</span>
              </a>
            </li>

          </ul>
        </li><!-- End Forms Nav -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=konsultasi">
            <i class="bi bi-journal-text"></i><span>Konsultasi</span></i>
            <?php
            $result = $koneksi->query("SELECT COUNT(*) AS jumlah FROM room_konsultasi WHERE dokter =''");
            $row = $result->fetch_assoc();
            $belumbayar = $row['jumlah'];
            ?>
            <sup style="background-color: blue; color: white; border-radius: 50px;">
              <p class="my-2 mx-1"><?= $belumbayar ?></p>
            </sup>
          </a>
        </li>
      <?php } ?>


      <li class="nav-heading">Kosmetik</li>
      <?php if ($level == 'ceo' or $level == 'kasir' or $level == 'sup') { ?>
        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=konsultasiall">
            <i class="bi bi-journal-text"></i><span>Konsultasi All </span></i>
            <?php
            $result = $koneksi->query("SELECT COUNT(*) AS jumlah FROM room_konsultasi WHERE dokter =''");
            $row = $result->fetch_assoc();
            $belumbayar = $row['jumlah'];
            ?>
            <sup style="background-color: blue; color: white; border-radius: 50px;">
              <p class="my-2 mx-1"><?= $belumbayar ?></p>
            </sup>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=produk_kosmetik">
            <i class="bi bi-journal-text"></i><span>Produk Kosmetik</span></i>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=pemesanan">
            <i class="bi bi-journal-text"></i><span>Pemesanan </span></i>
            <?php
            $result = $koneksi->query("SELECT COUNT(*) AS jumlah FROM pemesanan WHERE status = 'Menunggu_pembayaran'");
            $row = $result->fetch_assoc();
            $belumbayar = $row['jumlah'];
            ?>
            <sup style="background-color: blue; color: white; border-radius: 50px;">
              <p class="my-2 mx-1"><?= $belumbayar ?></p>
            </sup>
          </a>
        </li>
      <?php } ?>

      <!-- End  -->




      <?php if ($level == 'igd') { ?>

        <li class="nav-heading">IGD</li>

        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-journal-text"></i><span>Rekam Medis</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <!-- <li>
            <a href="index.php?halaman=daftarrmedis">
              <i class="bi bi-circle"></i><span>Kajian Dokter (Rawat Jalan)</span>
            </a>
          </li> -->
            <li>
              <a href="index.php?halaman=daftarrmedis&inap">
                <i class="bi bi-circle"></i><span>Kajian Dokter (Rawat Inap)</span>
              </a>
            </li>
            <li>
              <a href="index.php?halaman=daftarigd">
                <i class="bi bi-circle"></i><span>IGD</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link collapsed" href="index.php?halaman=daftarpasien">
                <i class="bi bi-circle"></i>
                <span>Pasien</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link collapsed" href="index.php?halaman=daftarregistrasi&day">
                <i class="bi bi-circle"></i>
                <span>Pendaftaran One Day</span>
              </a>
            </li>

          </ul>
        </li><!-- End Forms Nav -->
      <?php } ?>

      <hr>
      </hr>

      <?php if ($level == 'ceo' or $level == 'sup') { ?>

        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#laporan-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-file-text"></i><span>Laporan</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="laporan-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              <a href="index.php?halaman=laporanmedis">
                <i class="bi bi-circle"></i><span>Laporan Medis</span>
              </a>
            </li>
            <!--  <li>
            <a href="index.php?halaman=reservasirawat">
              <i class="bi bi-circle"></i><span>Reservasi</span>
            </a>
          </li> -->
          </ul>
        </li><!-- End Components Nav -->

      <?php } ?>



      <?php if ($level == 'daftar' or $level == 'ceo'  or $level == 'sup') { ?>
        <li class="nav-heading">Pendaftaran</li>

        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=daftarpasien">
            <i class="bi bi-person"></i>
            <span>Pasien</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=daftarpasienkosmetik">
            <i class="bi bi-person"></i>
            <span>Pasien Kosmetik</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=daftarregistrasi">
            <i class="bi bi-list"></i>
            <span>Pendaftaran All</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=daftarregistrasi&day">
            <i class="bi bi-file-earmark-medical-fill"></i>
            <span>Pendaftaran One Day</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=daftarregistrasi&all">
            <i class="bi bi-list"></i>
            <span>Pendaftaran (Diagnosis)</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=kyc_satusehat">
            <i class="bi bi-person"></i>
            <span>Kyc (Know Your Customer)</span>
          </a>
        </li>


        <!-- End Profile Page Nav -->
      <?php } ?>

      <?php if ($level == 'apoteker' or $level == 'ceo'  or $level == 'sup') { ?>
        <li class="nav-heading">Apoteker</li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=daftarapotek">
            <i class="bi bi-capsule"></i>
            <span>Apotek</span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=margin_obat">
            <i class="bi bi-cash-coin"></i>
            <span>Setting Margin Obat</span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=daftarrmedis&all">
            <i class="bi bi-capsule"></i>
            <span>Petugas Racik</span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=apotek_terima">
            <i class="bi bi-capsule"></i>
            <span>Terima Obat</span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=daftarpuyer">
            <i class="bi bi-capsule"></i>
            <span>Tambah Paket Racik</span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=daftarpuyerjadi">
            <i class="bi bi-capsule"></i>
            <span>Tambah Paket Jadi</span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=rekapobat">
            <i class="bi bi-capsule"></i>
            <span>Rekap Obat</span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=entri_obat_inap">
            <i class="bi bi-capsule"></i>
            <span>Entri Obat Inap</span>
          </a>
        </li><!-- End Profile Page Nav -->
      <?php } ?>

      <?php if ($level == 'racik') { ?>
        <li class="nav-heading">Petugas Racik</li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=daftarrmedis&all">
            <i class="bi bi-capsule"></i>
            <span>Racik Obat</span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=apotek_terima">
            <i class="bi bi-capsule"></i>
            <span>Terima Obat</span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=entri_obat_inap">
            <i class="bi bi-capsule"></i>
            <span>Entri Obat Inap</span>
          </a>
        </li><!-- End Profile Page Nav -->
      <?php } ?>


      <?php if ($level == 'lab' or $level == 'ceo'  or $level == 'sup' or $level == 'lab') { ?>
        <li class="nav-heading">Rujukan Laboratorium</li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=daftarlabdata">
            <i class="bi bi-clipboard2-pulse"></i>
            <span>Data Harga Lab</span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=daftarlab">
            <i class="bi bi-clipboard2-pulse"></i>
            <span>Lab (Poli)</span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=daftarlabinap">
            <i class="bi bi-clipboard2-pulse"></i>
            <span>Lab (Inap)</span>
          </a>
        </li><!-- End Profile Page Nav -->
      <?php } ?>

      <?php if ($level == 'kasir' or $level == 'ceo'  or $level == 'sup') { ?>
        <li class="nav-heading">Kasir</li>

        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=daftarbayar">
            <i class="bi bi-cash-stack"></i>
            <span>Pembayaran</span>
          </a>
        </li><!-- End Contact Page Nav -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=daftarrmedis">
            <i class="bi bi-capsule"></i>
            <span>Entri Obat</span>
          </a>
        </li><!-- End Contact Page Nav -->
      <?php } ?>

      <hr>
      </hr>
      <?php if ($level == 'ceo'  or $level == 'sup') { ?>

        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=kamar_inap">
            <i class="bi bi-cash-coin"></i>
            <span>Setting Tarif Kamar</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=pendapatan">
            <i class="bi bi-cash-coin"></i>
            <span>Pendapatan</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=pendaftaranall">
            <i class="bi bi-arrow-repeat"></i>
            <span>Sync SatuSehat</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=tenagamedis">
            <i class="bi bi-person-square"></i>
            <span>User</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=ratingall">
            <i class="bi bi-star"></i>
            <span>Rating</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=informasi">
            <i class="bi bi-megaphone"></i>
            <span>Informasi</span>
          </a>
        </li>

        <!-- End Profile Page Nav -->


        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=biayaigd">
            <i class="bi bi-currency-dollar"></i>
            <span>Biaya Tindakan</span>
          </a>
        </li><!-- End Profile Page Nav -->

        <!-- Start Recent Activity Nav -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=detaillog">
            <i class="bi bi-activity"></i>
            <span>Recent Activity</span>
          </a>
        </li>
        <!-- End Recent Activity Nav -->

        <li class="nav-item">
          <a class="nav-link collapsed" href="registrasi.php">
            <i class="bi bi-card-list"></i>
            <span>Register</span>
          </a>
        </li><!-- End Register Page Nav -->

      <?php } ?>

      <li class="nav-item">
        <?php if ($level == 'kasir' or $level == 'apoteker' or $level == 'daftar' or $level == 'dokter') { ?>
          <a class="nav-link collapsed" href="index.php?halaman=rating_user">
            <i class="bi bi-star"></i>
            <span>Rating Per User</span>
          </a>
        <?php } ?>
        <a class="nav-link collapsed" href="logout.php">
          <i class="bi bi-box-arrow-in-right"></i>
          <span>Logout</span>
        </a>
      </li><!-- End Login Page Nav -->
      <!-- End Blank Page Nav -->
    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <section class="section dashboard">
      <div class="row">


        <?php include 'list.php' ?>

      </div>

    </section>

  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <?php if (isset($_GET['halaman'])) { ?>
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

  <?php } ?>

</body>

</html>