<?php
include 'function.php';

$qiscus_app_id      = "mmgpu-ibeqprelthdmxcb";
$qiscus_secret_key  = "1ac1eb8eb81ee5adda8fae7233733b4c";
$qiscus_channel_id  = "8307";
$template_namespace = "fd4d98c9_14c0_4dc8_ba92_8dcdb8f73883";
$template_name      = "konfirmasi_status_kesehatan";

$target_date = date('Y-m-d', strtotime("-3 days"));

$url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (strpos($url_path, 'wonorejo') !== false) {
  $nama_klinik_sender = "wonorejo";
} elseif (strpos($url_path, 'klakah') !== false) {
  $nama_klinik_sender = "klakah";
} elseif (strpos($url_path, 'tunjung') !== false) {
  $nama_klinik_sender = "tunjung";
} else {
  $nama_klinik_sender = "kunir";
}


$sql_check = "SELECT r.idrawat, r.no_rm, p.nama_lengkap, p.nohp 
              FROM registrasi_rawat r 
              JOIN pasien p ON r.no_rm = p.no_rm 
              WHERE r.status_antri != 'Belum Datang' 
              AND DATE(r.jadwal) = '$target_date' 
              AND r.wa_at IS NULL 
              LIMIT 5";

$result_check = $koneksi->query($sql_check);

if ($result_check->num_rows > 0) {
  while ($row = $result_check->fetch_assoc()) {

    $hp = preg_replace('/[^0-9]/', '', $row['nohp']);
    if (substr($hp, 0, 1) == '0') {
      $hp = '62' . substr($hp, 1);
    } elseif (substr($hp, 0, 2) != '62') {
      $hp = '62' . $hp;
    }

    $payload = [
      "to" => $hp,
      "type" => "template",
      "template" => [
        "namespace" => $template_namespace,
        "name" => $template_name,
        "language" => ["policy" => "deterministic", "code" => "id"],
        "components" => [
          [
            "type" => "body",
            "parameters" => [
              ["type" => "text", "text" => $row['nama_lengkap']],
              ["type" => "text", "text" => $nama_klinik_sender]
            ]
          ]
        ]
      ]
    ];

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://omnichannel.qiscus.com/whatsapp/v1/{$qiscus_app_id}/{$qiscus_channel_id}/messages",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => json_encode($payload),
      CURLOPT_HTTPHEADER => array(
        "Qiscus-App-Id: " . $qiscus_app_id,
        "Qiscus-Secret-Key: " . $qiscus_secret_key,
        "Content-Type: application/json"
      ),
      CURLOPT_TIMEOUT => 5
    ));

    $response = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $waktu_sekarang = date('Y-m-d H:i:s');

    $koneksi->query("UPDATE registrasi_rawat SET wa_at = '$waktu_sekarang' WHERE idrawat = '{$row['idrawat']}'");
  }
}


session_start();
$session_duration = 8 * 60 * 60;

// Cek apakah session habis
$sessionExpired = false;
if (isset($_SESSION['login_time'])) {
  if (time() - $_SESSION['login_time'] > $session_duration) {
    $sessionExpired = true;
    // Hapus session yang lama
    $_SESSION = [];
    session_unset();
  }
}


// Jika session tidak ada atau habis, coba auto-login dari localStorage
if (!isset($_SESSION['login']) || $sessionExpired) {
?>
  <!DOCTYPE html>
  <html>

  <head>
    <title>Auto Login...</title>
    <style>
      body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
      }

      .loading {
        text-align: center;
      }

      .spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 0 auto 20px;
      }

      @keyframes spin {
        0% {
          transform: rotate(0deg);
        }

        100% {
          transform: rotate(360deg);
        }
      }
    </style>
  </head>

  <body>
    <div class="loading">
      <div class="spinner"></div>
      <p>Memuat ulang sesi...</p>
    </div>

    <script>
      // Coba ambil data login dari localStorage
      const loginData = localStorage.getItem('khm_login_data');

      if (loginData) {
        // Parse data login
        const data = JSON.parse(loginData);

        // Kirim request auto-login ke server
        fetch('api_autologin.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
          })
          .then(response => response.json())
          .then(result => {
            if (result.success) {
              // Auto-login berhasil, reload halaman
              window.location.reload();
            } else {
              // Auto-login gagal, hapus localStorage dan redirect ke login
              localStorage.removeItem('khm_login_data');
              window.location.href = 'login.php';
            }
          })
          .catch(error => {
            console.error('Error:', error);
            // Jika terjadi error, redirect ke login
            localStorage.removeItem('khm_login_data');
            window.location.href = 'login.php';
          });
      } else {
        // Tidak ada data di localStorage, redirect ke login
        window.location.href = 'login.php';
      }
    </script>
  </body>

  </html>
<?php
  exit;
}

$username = $_SESSION['admin']['username'];
$level = $_SESSION['admin']['level'];

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

      <?php if ($level == 'apoteker' or $level == 'ceo'  or $level == 'sup' or $level == 'racik') { ?>
        <a class="nav-link collapsed" href="index.php?halaman=dashboardapotek">
          <i class="bi bi-capsule"></i>
          <span>Dashboard Apotik</span>
        </a>
      <?php } ?>

      <?php if ($level == 'perawat' or $level == 'inap'  or $level == 'sup' or $level == 'igd') { ?>
        <a class="nav-link collapsed" href="index.php?halaman=dashboardinap">
          <i class="bi bi-heart-pulse"></i>
          <span>Dashboard Inap</span>
        </a>
      <?php } ?>

      <?php if ($level == 'sup') { ?>
        <a class="nav-link collapsed" href="index.php?halaman=dashboardkeuangan">
          <i class="bi bi-cash-coin"></i>
          <span>Dashboard Keuangan</span>
        </a>
      <?php } ?>
      <a class="nav-link collapsed" href="index.php?halaman=dashboardstaff">
        <i class="bi bi-cash-coin"></i>
        <span>Dashboard Staff</span>
      </a>
      <a class="nav-link collapsed" href="index.php?halaman=dashboardkpim">
        <i class="bi bi-cash-coin"></i>
        <span>Dashboard KPIM</span>
      </a>
      <li class="nav-item">
        <a class="nav-link collapsed" href="index.php?halaman=ratingall">
          <i class="bi bi-star"></i>
          <span>Rating</span>
        </a>
      </li>
      <!-- End Dashboard Nav -->
      <?php
      $notifKajianAwal = 0;

      if ($level == 'perawat' or $level == 'ceo' or $level == 'rekam medis' or $level == 'sup' or $level == 'inap' or $level == 'gizi' or $level == 'apoteker') {

        $getKajianAwal = $koneksi->query("SELECT COUNT(*) AS total FROM registrasi_rawat rr JOIN biaya_rawat br ON rr.idrawat = br.idregis WHERE br.biaya_lain LIKE '%ODC%' AND rr.perawat_at IS NULL;")->fetch_assoc();
        $dataKajianAwalBaru = (int) $getKajianAwal['total'];

        $dataKajianAwalLama = $_SESSION['notif_kajian_awal'] ?? 0;

        if ($dataKajianAwalBaru > 0 && $dataKajianAwalBaru != $dataKajianAwalLama) {
          echo "
              <audio autoplay>
                <source src='https://public-assets.content-platform.envatousercontent.com/bb57cbaa-7c56-447b-a9ae-a6d964b90750/ae7d06ae-d4c7-485c-96e6-edc50ecd062e/preview.m4a' type='audio/mpeg'>
              </audio>
            ";
        }

        $_SESSION['notif_kajian_awal'] = $dataKajianAwalBaru;

        $notifKajianAwal = $dataKajianAwalBaru;
      }
      ?>

      <?php if ($level == 'perawat' or $level == 'ceo' or $level == 'rekam medis' or $level == 'sup' or $level == 'inap' or $level == 'gizi' or $level == 'apoteker') { ?>

        <li class="nav-heading">Perawat</li>

        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-menu-button-wide"></i><span>Rawat Jalan</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              <a href="index.php?halaman=daftarregistrasi&day">
                <i class="bi bi-circle"></i><span>Kajian Awal
                  <?php if ($notifKajianAwal > 0) { ?>
                    <?php echo "<span class='badge bg-danger'>($notifKajianAwal)</span>"; ?>
                  <?php } ?>
                </span>
              </a>
            </li>
            <li>
              <a href="index.php?halaman=daftarregistrasi&day&carabayarlike=gigi">
                <i class="bi bi-circle"></i><span>Kajian Awal Gigi</span>
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
            <li>
              <a href="index.php?halaman=rekappasienpulang">
                <i class="bi bi-circle"></i><span>Rekap Pasien Pulang</span>
              </a>
            </li>
            <li>
              <a href="index.php?halaman=rekappasienrujuk">
                <i class="bi bi-circle"></i><span>Rekap Pasien Rujuk</span>
              </a>
            </li>
            <li>
              <a href="index.php?halaman=rekappasienperujuk">
                <i class="bi bi-circle"></i><span>Rekap Perujuk Pasien</span>
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
          <a class="nav-link collapsed" href="#" onclick="openDisplayAntrian()">
            <i class="bi bi-circle"></i>
            <span>Display Antrian</span>
          </a>
        </li>
        <script>
          function openDisplayAntrian() {
            // Deteksi apakah ada multiple monitor
            if (screen.availWidth < window.screen.width) {
              // Kemungkinan ada multiple monitor
              const secondMonitorLeft = window.screen.width;
              const windowFeatures = `width=${screen.availWidth},height=${screen.availHeight},left=${secondMonitorLeft},top=0,scrollbars=yes,fullscreen=yes,resizable=no`;

              const newWindow = window.open('../pasien/displayAntrian.php', 'displayAntrian', windowFeatures);
            } else {
              // Single monitor - buka maximized
              const windowFeatures = `width=${screen.availWidth},height=${screen.availHeight},left=0,top=0,scrollbars=yes,resizable=yes`;
              const newWindow = window.open('../pasien/displayAntrian.php', 'displayAntrian', windowFeatures);
            }

            // Focus pada window baru
            if (newWindow) {
              newWindow.focus();
            }
          }
        </script>
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
              <a href="index.php?halaman=daftarrmedis&carabayar=gigi">
                <i class="bi bi-circle"></i><span>Kajian Dokter Gigi (Rawat Jalan)</span>
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
          <a class="nav-link collapsed" href="index.php?halaman=absensidokter">
            <i class="bi bi-file-person"></i>
            <span>Absensi Dokter</span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=absensidokter_history">
            <i class="bi bi-person-check"></i>
            <span>Riwayat Absensi Dokter</span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=daftarpuyer">
            <i class="bi bi-capsule"></i>
            <span>Tambah Paket Racik</span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=daftarpuyerjadi">
            <i class="bi bi-capsule"></i>
            <span>Tambah Paket Jadi</span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=master_poli">
            <i class="bi bi-database-check"></i>
            <span>Master Poli</span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=master_layanan">
            <i class="bi bi-database-check"></i>
            <span>Master Layanan</span>
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
          <a class="nav-link collapsed" href="index.php?halaman=ubahbpjs">
            <i class="bi bi-files"></i>
            <span>Ubah BPJS</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=dashboard_ubahbpjs">
            <i class="bi bi-files"></i>
            <span>Rekap Ubah BPJS</span>
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
          <a class="nav-link collapsed" href="index.php?halaman=daftarregistrasi&day&carabayarlike=gigi">
            <i class="bi bi-file-earmark-medical-fill"></i>
            <span>Pendaftaran One Day Gigi</span>
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
        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=daftarigd">
            <i class="bi bi-circle"></i>
            <span>Daftar IGD</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="#" onclick="openDisplayAntrian()">
            <i class="bi bi-circle"></i>
            <span>Display Antrian</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=rekappasienpulang">
            <i class="bi bi-circle"></i><span>Rekap Pasien Pulang</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=rekappasienperujuk">
            <i class="bi bi-circle"></i><span>Rekap Perujuk Pasien</span>
          </a>
        </li>
        <!-- End Profile Page Nav -->
      <?php } ?>

      <?php
      $notifRanap = 0;
      $notifPenjualanObat = 0;


      if ($_SESSION['admin']['level'] == 'apoteker' or $_SESSION['admin']['level'] == 'racik') {
        $getRegistrasiInap = $koneksi->query("SELECT COUNT(*) as total FROM `ctt_penyakit_inap` WHERE plan_at IS NULL AND plan LIKE '%obat lanjut%'")->fetch_assoc();
        $dataRegisBaru = (int) $getRegistrasiInap['total'];

        $getPenjualanObat = $koneksi->query("SELECT COUNT(*) as total FROM `igd` WHERE rencana_rawat_at IS NULL AND tindak != 'Rawat' AND tindak != '';")->fetch_assoc();
        $dataJualObatBaru = (int) $getPenjualanObat['total'];

        $dataJualObatLama = $_SESSION['notif_jual_obat'] ?? 0;
        $dataRegisLama = $_SESSION['notif_ranap'] ?? 0;

        if ($dataRegisBaru > 0 && $dataRegisBaru != $dataRegisLama || $dataJualObatBaru > 0 && $dataJualObatBaru != $dataJualObatLama) {
          echo "
              <audio autoplay>
                <source src='https://public-assets.content-platform.envatousercontent.com/bb57cbaa-7c56-447b-a9ae-a6d964b90750/ae7d06ae-d4c7-485c-96e6-edc50ecd062e/preview.m4a' type='audio/mpeg'>
              </audio>
            ";
        }

        $_SESSION['notif_ranap'] = $dataRegisBaru;
        $_SESSION['notif_jual_obat'] = $dataJualObatBaru;

        $notifPenjualanObat = $dataJualObatBaru;
        $notifRanap = $dataRegisBaru;
      }
      ?>

      <?php if ($level == 'apoteker' or $level == 'ceo'  or $level == 'sup') { ?>
        <li class="nav-heading">Apoteker</li>
        <li class="nav-item">
          <!-- <a class="nav-link collapsed" href="index.php?halaman=daftarapotek">
            <i class="bi bi-capsule"></i>
            <span>Apoteker</span>
          </a> -->
          <a class="nav-link collapsed" href="index.php?halaman=tambah_obatmasuk">
            <i class="bi bi-capsule"></i>
            <span>Pemesanan Obat</span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=apotek_riwayat_faktur">
            <i class="bi bi-dropbox"></i>
            <span>Riwayat Faktur</span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=stok_obat_apoteker">
            <i class="bi bi-capsule"></i>
            <span>Stok Obat</span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=margin_obat">
            <i class="bi bi-cash-coin"></i>
            <span>Setting Margin Obat</span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=daftarrmedis&racik">
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
          <!-- <a class="nav-link collapsed" href="index.php?halaman=rekapobat">
            <i class="bi bi-capsule"></i>
            <span>Rekap Obat</span>
          </a> -->
          <a class="nav-link collapsed" href="index.php?halaman=daftarrmedis&inap">
            <i class="bi bi-capsule"></i>
            <span>
              Rawat Inap
              <?php if ($_SESSION['admin']['level'] == 'apoteker' or $_SESSION['admin']['level'] == 'racik') { ?>
                <?php if ($notifRanap > 0) { ?>
                  <?php echo "<span class='badge bg-danger'>($notifRanap)</span>"; ?>
                <?php } ?>
              <?php } ?>
            </span>
          </a>

          <!-- <a class="nav-link collapsed" href="index.php?halaman=entri_obat_inap">
            <i class="bi bi-capsule"></i>
            <span>Entri Obat Inap</span>
          </a> -->
          <a class="nav-link collapsed" href="index.php?halaman=penjualan_obat_umum">
            <i class="bi bi-cart-check"></i>
            <span>Penjualan Obat Umum</span>
          </a>
          <!-- <a class="nav-link collapsed" href="index.php?halaman=penjualan_obat_umum_riwayat">
            <i class="bi bi-bar-chart"></i>
            <span>Riwayat Penjualan Obat Umum</span>
          </a> -->
          <a class="nav-link collapsed" href="index.php?halaman=penjualan_obat_resep">
            <i class="bi bi-cart-check-fill"></i>
            <span>Penjualan Obat Resep
              <?php if ($_SESSION['admin']['level'] == 'apoteker' or $_SESSION['admin']['level'] == 'racik') { ?>
                <?php if ($notifPenjualanObat > 0) { ?>
                  <?php echo "<span class='badge bg-danger'>($notifPenjualanObat)</span>"; ?>
                <?php } ?>
              <?php } ?>
            </span>
          </a>
          <!-- <a class="nav-link collapsed" href="index.php?halaman=penjualan_obat_resep_riwayat">
            <i class="bi bi-bar-chart-fill"></i>
            <span>Riwayat Penjualan Obat Resep</span>
          </a> -->
          <a class="nav-link collapsed" href="index.php?halaman=penjualan_obat_rekanan">
            <i class="bi bi-cart-dash"></i>
            <span>Penjualan Obat Rekanan</span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=penjualan_obat_internal">
            <i class="bi bi-cart-dash-fill"></i>
            <span>Penjualan Obat Internal</span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=penjualan_obat_all_riwayat">
            <i class="bi bi-bar-chart-fill"></i>
            <span>Riwayat Penjualan Obat All</span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=setoran_shift_apoteker">
            <i class="bi bi-clipboard-check"></i>
            <span>Setoran Shift</span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=rekapobat">
            <i class="bi bi-bar-chart"></i>
            <span>HPP Penggunaan Obat</span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=daftar_obat_master">
            <i class="bi bi-database"></i>
            <span>Obat Master</span>
          </a>

        </li><!-- End Profile Page Nav -->
      <?php } ?>

      <?php
      $notifRacikObat = 0;

      if ($level == 'racik') {

        $getRacikObat = $koneksi->query("SELECT COUNT(*) as total FROM `igd` WHERE rencana_rawat_at_poli IS NULL AND tindak = 'ODC';")->fetch_assoc();
        $dataRacikObatBaru = (int) $getRacikObat['total'];

        $dataRacikObatLama = $_SESSION['notif_jual_obat_poli'] ?? 0;

        if ($dataRacikObatBaru > 0 && $dataRacikObatBaru != $dataRacikObatLama) {
          echo "
              <audio autoplay>
                <source src='https://public-assets.content-platform.envatousercontent.com/bb57cbaa-7c56-447b-a9ae-a6d964b90750/ae7d06ae-d4c7-485c-96e6-edc50ecd062e/preview.m4a' type='audio/mpeg'>
              </audio>
            ";
        }

        $_SESSION['notif_jual_obat_poli'] = $dataRacikObatBaru;

        $notifRacikObat = $dataRacikObatBaru;
      }
      ?>

      <?php if ($level == 'racik') { ?>
        <li class="nav-heading">Petugas Racik</li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=daftarrmedis&racik">
            <i class="bi bi-capsule"></i>
            <span>Racik Obat
              <?php if ($notifRacikObat > 0) { ?>
                <?php echo "<span class='badge bg-danger'>($notifRacikObat)</span>"; ?>
              <?php } ?>
            </span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=apotek_terima">
            <i class="bi bi-capsule"></i>
            <span>Terima Obat</span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=daftarrmedis&inap">
            <i class="bi bi-capsule"></i>
            <span>Rawat Inap <?php if ($notifRanap > 0) {
                                echo "<span class='badge bg-danger'>($notifRanap)</span>";
                              } ?></span>
          </a>
          <!-- <a class="nav-link collapsed" href="index.php?halaman=entri_obat_inap">
            <i class="bi bi-capsule"></i>
            <span>Entri Obat Inap</span>
          </a> -->
          <a class="nav-link collapsed" href="index.php?halaman=penjualan_obat_umum">
            <i class="bi bi-cart-check"></i>
            <span>Penjualan Obat Umum</span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=penjualan_obat_umum_riwayat">
            <i class="bi bi-bar-chart"></i>
            <span>Riwayat Penjualan Obat Umum</span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=penjualan_obat_resep">
            <i class="bi bi-cart-check-fill"></i>
            <span>Penjualan Obat Resep</span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=penjualan_obat_resep_riwayat">
            <i class="bi bi-bar-chart-fill"></i>
            <span>Riwayat Penjualan Obat Resep</span>
          </a>
          <!-- <a class="nav-link collapsed" href="index.php?halaman=penjualan_obat_rekanan">
            <i class="bi bi-cart-dash"></i>
            <span>Penjualan Obat Rekanan</span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=penjualan_obat_internal">
            <i class="bi bi-cart-dash-fill"></i>
            <span>Penjualan Obat Internal</span>
          </a> -->
          <a class="nav-link collapsed" href="index.php?halaman=penjualan_obat_all_riwayat">
            <i class="bi bi-bar-chart-fill"></i>
            <span>Riwayat Penjualan Obat All</span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=setoran_shift_apoteker">
            <i class="bi bi-bar-chart"></i>
            <span>Setoran Shift</span>
          </a>
        </li><!-- End Profile Page Nav -->
      <?php } ?>

      <?php
      $notifLabPoli = 0;
      $notifLabInap = 0;
      $notifLabIgd = 0;

      if ($level == 'lab') {

        $querySql = "
        SELECT
            -- Kategori IGD
            SUM(CASE 
                WHEN (l.id_lab_igd IS NOT NULL AND l.id_lab_igd != '') AND lh_igd.idhasil IS NULL 
                THEN 1 ELSE 0 
            END) as total_igd,

            -- Kategori Poli
            SUM(CASE 
                WHEN (l.id_lab IS NOT NULL AND l.id_lab != '') 
                     AND (l.id_lab_inap IS NULL OR l.id_lab_inap = '') 
                     AND (l.id_lab_igd IS NULL OR l.id_lab_igd = '')
                     AND lh_poli.idhasil IS NULL 
                THEN 1 ELSE 0 
            END) as total_poli,

            -- Kategori Inap
            SUM(CASE 
                WHEN (l.id_lab_inap IS NOT NULL AND l.id_lab_inap != '') 
                     AND (l.id_lab IS NULL OR l.id_lab = '') 
                     AND (l.id_lab_igd IS NULL OR l.id_lab_igd = '')
                     AND lh_inap.idhasil IS NULL 
                THEN 1 ELSE 0 
            END) as total_inap
        FROM `lab` l
        
        -- Menggabungkan tabel secara langsung (Jauh lebih cepat dari subquery)
        LEFT JOIN `lab_hasil` lh_igd  ON l.id_lab_igd = lh_igd.id_igd AND l.id_lab_igd != ''
        LEFT JOIN `lab_hasil` lh_poli ON l.id_lab = lh_poli.id_lab_h  AND l.id_lab != ''
        LEFT JOIN `lab_hasil` lh_inap ON l.id_lab_inap = lh_inap.id_inap AND l.id_lab_inap != ''
        
        WHERE l.tgl >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        ";

        $counts = $koneksi->query($querySql)->fetch_assoc();

        // 2. Ambil jumlah baru dari hasil query
        $dataLabIgdBaru  = (int) ($counts['total_igd'] ?? 0);
        $dataLabPoliBaru = (int) ($counts['total_poli'] ?? 0);
        $dataLabInapBaru = (int) ($counts['total_inap'] ?? 0);

        // 3. Ambil jumlah lama dari session
        $dataLabIgdLama  = $_SESSION['notif_lab_igd'] ?? null;
        $dataLabPoliLama = $_SESSION['notif_lab_poli'] ?? null;
        $dataLabInapLama = $_SESSION['notif_lab_inap'] ?? null;

        // 4. Bandingkan jumlah untuk memicu notifikasi suara
        $playNotif = false;
        if ($dataLabIgdBaru > 0 && $dataLabIgdBaru !== $dataLabIgdLama) $playNotif = true;
        if (!$playNotif && $dataLabPoliBaru > 0 && $dataLabPoliBaru !== $dataLabPoliLama) $playNotif = true;
        if (!$playNotif && $dataLabInapBaru > 0 && $dataLabInapBaru !== $dataLabInapLama) $playNotif = true;

        if ($playNotif) {
          echo "
          <audio autoplay>
            <source src='https://public-assets.content-platform.envatousercontent.com/bb57cbaa-7c56-447b-a9ae-a6d964b90750/ae7d06ae-d4c7-485c-96e6-edc50ecd062e/preview.m4a' type='audio/mpeg'>
          </audio>
        ";
        }

        // 5. Update session dengan jumlah yang baru
        $_SESSION['notif_lab_igd']  = $dataLabIgdBaru;
        $_SESSION['notif_lab_poli'] = $dataLabPoliBaru;
        $_SESSION['notif_lab_inap'] = $dataLabInapBaru;

        // 6. Update variabel untuk ditampilkan di UI
        $notifLabIgd  = $dataLabIgdBaru;
        $notifLabPoli = $dataLabPoliBaru;
        $notifLabInap = $dataLabInapBaru;
      }
      ?>

      <?php if ($level == 'lab' or $level == 'ceo'  or $level == 'sup' or $level == 'lab') { ?>

        <li class="nav-heading">Rujukan Laboratorium</li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=daftarlabdata">
            <i class="bi bi-clipboard2-pulse"></i>
            <span>Data Harga Lab</span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=daftarlab">
            <i class="bi bi-clipboard2-pulse"></i>
            <span>Lab (Poli)
              <?php if ($level == 'lab' or $level == 'ceo'  or $level == 'sup' or $level == 'lab') { ?>
                <?php if ($notifLabPoli > 0) { ?>
                  <?php echo "<span class='badge bg-danger'>($notifLabPoli)</span>"; ?>
                <?php } ?>
              <?php } ?>
            </span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=daftarlabinap">
            <i class="bi bi-clipboard2-pulse"></i>
            <span>Lab (Inap)
              <?php if ($level == 'lab' or $level == 'ceo'  or $level == 'sup' or $level == 'lab') { ?>
                <?php if ($notifLabInap > 0) { ?>
                  <?php echo "<span class='badge bg-danger'>($notifLabInap)</span>"; ?>
                <?php } ?>
              <?php } ?>
            </span>
          </a>
          <a class="nav-link collapsed" href="index.php?halaman=daftarlabigd">
            <i class="bi bi-clipboard2-pulse"></i>
            <span>Lab (IGD)
              <?php if ($level == 'lab' or $level == 'ceo'  or $level == 'sup' or $level == 'lab') { ?>
                <?php if ($notifLabIgd > 0) { ?>
                  <?php echo "<span class='badge bg-danger'>($notifLabIgd)</span>"; ?>
                <?php } ?>
              <?php } ?>
            </span>
          </a>
        </li><!-- End Profile Page Nav -->
      <?php } ?>

      <?php if ($level == 'kasir' or $level == 'ceo'  or $level == 'sup' or $level == 'igd') { ?>
        <li class="nav-heading">Kasir</li>

        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=daftarbayar&day">
            <i class="bi bi-cash-stack"></i>
            <span>Pembayaran</span>
          </a>
        </li><!-- End Contact Page Nav -->
        <!-- <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=daftarrmedis">
            <i class="bi bi-capsule"></i>
            <span>Entri Obat</span>
          </a>
        </li> End Contact Page Nav -->
        <a class="nav-link collapsed" href="index.php?halaman=daftarrmedis&inap">
          <i class="bi bi-capsule"></i>
          <span>Rawat Inap <span>Rawat Inap <?php if ($notifRanap > 0) {
                                              echo "<span class='badge bg-danger'>($notifRanap)</span>";
                                            } ?></span></span>
        </a>
        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=daftarrmedis&all">
            <i class="bi bi-circle"></i>
            <span>Kajian Dokter (Rawat Jalan)</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=rekappasienpulang">
            <i class="bi bi-circle"></i><span>Rekap Pasien Pulang</span>
          </a>
        </li>
      <?php } ?>

      <hr>
      </hr>
      <?php if ($level == 'ceo'  or $level == 'sup') { ?>
        <li class="nav-heading">Sup</li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=ubahbpjs">
            <i class="bi bi-files"></i>
            <span>Ubah BPJS</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=dashboard_ubahbpjs">
            <i class="bi bi-files"></i>
            <span>Rekap Ubah BPJS</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=kamar_inap">
            <i class="bi bi-cash-coin"></i>
            <span>Setting Tarif Kamar</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php?halaman=akun_gajidokter">
            <i class="bi bi-coin"></i>
            <span>Akun Gaji Dokter</span>
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
        <a class="nav-link collapsed" href="index.php?halaman=profile">
          <i class="bi bi-person-circle"></i>
          <span>Profile</span>
        </a>
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