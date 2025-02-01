<?php 

session_start();
include 'function.php';
$username=$_SESSION['admin']['username'];
$level=$_SESSION['admin']['level'];



if(!isset($_SESSION['login'])){

  header("location:login.php");

  exit;}


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
  <link href="../admin/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

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
        <img  src="assets/img/logokhm.png" alt="">
        <span class="d-none d-lg-block">SIM KHM</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="POST" action="#">
        <input type="text" name="query" placeholder="Search" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div><!-- End Search Bar -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->

        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i>
            <span class="badge bg-primary badge-number">4</span>
          </a><!-- End Notification Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
            <li class="dropdown-header">
              You have 4 new notifications
              <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-exclamation-circle text-warning"></i>
              <div>
                <h4>Lorem Ipsum</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>30 min. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-x-circle text-danger"></i>
              <div>
                <h4>Atque rerum nesciunt</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>1 hr. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-check-circle text-success"></i>
              <div>
                <h4>Sit rerum fuga</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>2 hrs. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-info-circle text-primary"></i>
              <div>
                <h4>Dicta reprehenderit</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>4 hrs. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>
            <li class="dropdown-footer">
              <a href="#">Show all notifications</a>
            </li>

          </ul><!-- End Notification Dropdown Items -->

        </li><!-- End Notification Nav -->

        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-chat-left-text"></i>
            <span class="badge bg-success badge-number">3</span>
          </a><!-- End Messages Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
            <li class="dropdown-header">
              You have 3 new messages
              <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="assets/img/messages-1.jpg" alt="" class="rounded-circle">
                <div>
                  <h4>Maria Hudson</h4>
                  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                  <p>4 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="assets/img/messages-2.jpg" alt="" class="rounded-circle">
                <div>
                  <h4>Anna Nelson</h4>
                  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                  <p>6 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="assets/img/messages-3.jpg" alt="" class="rounded-circle">
                <div>
                  <h4>David Muldon</h4>
                  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                  <p>8 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="dropdown-footer">
              <a href="#">Show all messages</a>
            </li>

          </ul><!-- End Messages Dropdown Items -->

        </li><!-- End Messages Nav -->

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="assets/img/messages-2.jpg" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2"><?= $username ?></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6>Kevin Anderson</h6>
              <span>Web Designer</span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                <i class="bi bi-gear"></i>
                <span>Account Settings</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="pages-faq.html">
                <i class="bi bi-question-circle"></i>
                <span>Need Help?</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="#">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link " href="index.php">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

     <?php if($level == 'perawat' or $level == 'ceo'  or $level == 'sup' or $level == 'inap' or $level == 'gizi' or $level == 'apoteker') {?>

      <li class="nav-heading">Perawat</li>

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-menu-button-wide"></i><span>Rawat Jalan</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="index.php?halaman=daftarregistrasi">
              <i class="bi bi-circle"></i><span>Kajian Awal</span>
            </a>
          </li>
          <!-- <li>
            <a href="index.php?halaman=reservasirawat">
              <i class="bi bi-circle"></i><span>Reservasi</span>
            </a>
          </li> -->
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
          <!-- <li>
            <a href="index.php?halaman=reservasirawat">
              <i class="bi bi-circle"></i><span>Reservasi</span>
            </a>
          </li> -->
        </ul>
      </li><!-- End Components Nav -->
     <?php } ?>


     <?php if($level == 'dokter' or $level == 'ceo'  or $level == 'sup') {?>

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
            <a href="index.php?halaman=daftarigd">
              <i class="bi bi-circle"></i><span>IGD</span>
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
     <?php } ?>

      <?php if($level == 'igd') {?>

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

     <hr></hr>

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

      <li class="nav-heading">Kosmetik</li>
  <?php if($level == 'ceo' or $level == 'kasir' or $level == 'sup') {?>
  <li class="nav-item">
    <a class="nav-link collapsed" href="index.php?halaman=konsultasiall">
      <i class="bi bi-journal-text"></i><span>Konsultasi All  </span></i>
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
          <i class="bi bi-journal-text"></i><span>Pemesanan  </span></i>
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
    

     <?php if($level == 'daftar' or $level == 'ceo'  or $level == 'sup') {?>
      <li class="nav-heading">Pendaftaran</li>

       <li class="nav-item">
        <a class="nav-link collapsed" href="index.php?halaman=daftarpasien">
          <i class="bi bi-person"></i>
          <span>Pasien</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="index.php?halaman=daftarregistrasi">
          <i class="bi bi-list"></i>
          <span>Pendaftaran</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="index.php?halaman=daftarregistrasi&day">
          <i class="bi bi-file-earmark-medical-fill"></i>
          <span>Pendaftaran One Day</span>
        </a>
      </li>

      <!-- End Profile Page Nav -->
      <?php } ?>

     <?php if($level == 'apoteker' or $level == 'ceo'  or $level == 'sup') { ?>
      <li class="nav-heading">Apoteker</li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="index.php?halaman=daftarapotek">
          <i class="bi bi-capsule"></i>
          <span>Apotek</span>
        </a>
        <a class="nav-link collapsed" href="index.php?halaman=daftarrmedis&racik">
          <i class="bi bi-capsule"></i>
          <span>Petugas Racik</span>
        </a>
      </li><!-- End Profile Page Nav -->
      <?php } ?>

     <?php if($level == 'lab' or $level == 'ceo'  or $level == 'sup') { ?>
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

     <?php if($level == 'kasir' or $level == 'ceo'  or $level == 'sup' ) { ?>
      <li class="nav-heading">Kasir</li>
    
      <li class="nav-item">
        <a class="nav-link collapsed" href="index.php?halaman=daftarbayar">
          <i class="bi bi-cash-stack"></i>
          <span>Pembayaran</span>
        </a>
      </li><!-- End Contact Page Nav -->
      <?php } ?>

      <hr></hr>
      <?php if($level == 'ceo'  or $level == 'sup') {?>
      <li class="nav-item">
        <a class="nav-link collapsed" href="index.php?halaman=tenagamedis">
          <i class="bi bi-person-square"></i>
          <span>User</span>
        </a>
      </li><!-- End Profile Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="index.php?halaman=biayaigd">
          <i class="bi bi-currency-dollar"></i>
          <span>Biaya Tindakan</span>
        </a>
      </li><!-- End Profile Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="registrasi.php">
          <i class="bi bi-card-list"></i>
          <span>Register</span>
        </a>
      </li><!-- End Register Page Nav -->

      <?php } ?>
      <li class="nav-item">
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

</body>

</html>