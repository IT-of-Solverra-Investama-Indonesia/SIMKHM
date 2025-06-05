<?php

$date = date("Y-m-d");

// $pasien=$koneksi->query("SELECT * FROM rekam_medis JOIN pasien WHERE nama_lengkap = nama_pasien;"); 
$queryKey = '';
if (isset($_POST['src'])) {
  $queryKey .= " AND (nama_pasien LIKE '%$_POST[key]%' or perawatan LIKE '%$_POST[key]%' or dokter_rawat LIKE '%$_POST[key]%' or no_rm LIKE '%$_POST[key]%' or jadwal LIKE '%$_POST[key]%' or antrian LIKE '%$_POST[key]%' or status_antri LIKE '%$_POST[key]%')";
}



if (isset($_GET['inap']) and !isset($_GET['detail'])) {
  // $pasien=$koneksi->query("SELECT *, DATE_FORMAT(jadwal, '%Y-%m-%d') as tgl FROM registrasi_rawat WHERE date_format(jadwal, '%Y-%m-%d') = '$date' and (status_antri='Datang' or status_antri='Pembayaran' or status_antri='Selesai') and perawatan ='Rawat Jalan';");
  $pasien = $koneksi->query("SELECT *, DATE_FORMAT(jadwal, '%Y-%m-%d') as tgl FROM registrasi_rawat WHERE  (status_antri!='Pulang') and perawatan ='Rawat Inap' order by idrawat desc;");
} elseif (isset($_GET['racik'])) {
  if (isset($_GET['pasrajal'])) {
    $queryPasien = "SELECT *, DATE_FORMAT(jadwal, '%Y-%m-%d') as tgl FROM registrasi_rawat INNER JOIN biaya_rawat ON biaya_rawat.idregis = registrasi_rawat.idrawat WHERE nota != '' AND (status_antri='Datang' or  status_antri='Pembayaran' or status_antri='Selesai' or status_antri='Pulang') and perawatan ='Rawat Jalan'" . $queryKey . " order by idrawat desc";
  } elseif (isset($_GET['pasinap'])) {
    $queryPasien = "SELECT *, DATE_FORMAT(jadwal, '%Y-%m-%d') as tgl FROM registrasi_rawat INNER JOIN biaya_rawat ON biaya_rawat.idregis = registrasi_rawat.idrawat WHERE nota != '' AND (status_antri='Datang' or  status_antri='Pembayaran' or status_antri='Selesai' or status_antri='Pulang') and perawatan ='Rawat Inap'" . $queryKey . " order by idrawat desc";
  } else {
    $queryPasien = "SELECT *, DATE_FORMAT(jadwal, '%Y-%m-%d') as tgl FROM registrasi_rawat INNER JOIN biaya_rawat ON biaya_rawat.idregis = registrasi_rawat.idrawat WHERE nota != '' AND (status_antri!='Belum Datang') " . $queryKey . " order by idrawat desc";
  }

  //   Pagination
  // Parameters for pagination
  $limit = 20; // Number of entries to show in a page
  $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
  $start = ($page - 1) * $limit;

  // Get the total number of records
  $tgl_mulaii = date('Y-m-d', strtotime('2024-03-28'));
  $result = $koneksi->query($queryPasien);
  $total_records = $result->num_rows;

  // Calculate total pages
  $total_pages = ceil($total_records / $limit);

  $cekPage = '';
  if (isset($_GET['page'])) {
    $cekPage = $_GET['page'];
  } else {
    $cekPage = '1';
  }
  // End Pagination
  if (isset($_POST['src'])) {
    $pasien = $koneksi->query($queryPasien);
  } else {
    $pasien = $koneksi->query($queryPasien . " LIMIT $start, $limit;");
  }
  if (isset($_GET['pasrajal'])) {
    $linkPage = "index.php?halaman=daftarrmedis&racik&pasrajal";
  } elseif (isset($_GET['pasinap'])) {
    $linkPage = 'index.php?halaman=daftarrmedis&racik&pasinap';
  } else {
    $linkPage = "index.php?halaman=daftarrmedis&racik";
  }
} elseif (isset($_GET['detail'])) {
  if (isset($_GET['inap'])) {
    $pasien = $koneksi->query("SELECT *, DATE_FORMAT(jadwal, '%Y-%m-%d') as tgl FROM registrasi_rawat WHERE  (status_antri='Datang' or  status_antri='Pembayaran' or status_antri='Selesai' or status_antri='Pulang') and perawatan ='Rawat Inap' and REPLACE(REPLACE(REPLACE(no_rm, '\t', ' '), '  ', ' '), '  ', ' ')  = '$_GET[detail]'  order by idrawat desc limit 1;");
  } else {
    $pasien = $koneksi->query("SELECT *, DATE_FORMAT(jadwal, '%Y-%m-%d') as tgl FROM registrasi_rawat WHERE (status_antri='Datang' or status_antri='0' or status_antri='Pembayaran' or status_antri='Selesai') and REPLACE(REPLACE(REPLACE(no_rm, '\t', ' '), '  ', ' '), '  ', ' ')  = '$_GET[detail]' and perawatan ='Rawat Jalan' order by idrawat desc Limit 1;");
  }
} elseif (isset($_GET['all'])) {
  if (isset($_GET['perawatan'])) {
    $queryKey .= " AND perawatan = '" . htmlspecialchars($_GET['perawatan']) . "'";
    $linkPage = 'index.php?halaman=daftarrmedis&all=&perawatan=' . $_GET['perawatan'] . '&fil=';
    if (isset($_GET['bulan'])) {
      $queryKey .= " AND DATE_FORMAT(jadwal, '%y/%m') = '" . htmlspecialchars($_GET['bulan']) . "'";
      $linkPage = 'index.php?halaman=daftarrmedis&all=&perawatan=' . $_GET['perawatan'] . '&fil=&bulan=' . htmlspecialchars($_GET['bulan']) . '';
    }
  } else {
    $linkPage = "index.php?halaman=daftarrmedis&all";
  }
  // if(isset($_GET['rajal']))
  $queryPasien = "SELECT *, DATE_FORMAT(jadwal, '%Y-%m-%d') as tgl FROM registrasi_rawat WHERE (status_antri!='') " . $queryKey . " order by idrawat desc";
  //   Pagination
  // Parameters for pagination
  $limit = 20; // Number of entries to show in a page
  $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
  $start = ($page - 1) * $limit;

  // Get the total number of records
  $tgl_mulaii = date('Y-m-d', strtotime('2024-03-28'));
  $result = $koneksi->query($queryPasien);
  $total_records = $result->num_rows;

  // Calculate total pages
  $total_pages = ceil($total_records / $limit);

  $cekPage = '';
  if (isset($_GET['page'])) {
    $cekPage = $_GET['page'];
  } else {
    $cekPage = '1';
  }
  // End Pagination
  if (isset($_POST['src'])) {
    $pasien = $koneksi->query($queryPasien);
  } else {
    $pasien = $koneksi->query($queryPasien . " LIMIT $start, $limit;");
  }
} else {
  // $pasien=$koneksi->query("SELECT *, DATE_FORMAT(jadwal, '%Y-%m-%d') as tgl FROM registrasi_rawat WHERE date_format(jadwal, '%Y-%m-%d') = '$date' and (status_antri='Datang' or status_antri='Pembayaran' or status_antri='Selesai') and perawatan ='Rawat Inap';");
  $pasien = $koneksi->query("SELECT *, DATE_FORMAT(jadwal, '%Y-%m-%d') as tgl FROM registrasi_rawat WHERE (status_antri='Datang' or status_antri='0') and perawatan ='Rawat Jalan' AND DATE_FORMAT(jadwal, '%Y-%m-%d') = '$date' order by idrawat desc ;");
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>KHM</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <!-- DATATABLES -->
  <!-- !-- DataTables  -->
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
  <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <link src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
  <link src="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css">
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
</head>

<body>
  <main>
    <div class="">
      <div class="pagetitle">
        <h1>Rekam Medis</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?halaman=daftarpasien" style="color:blue;">Rekam Medis</a></li>
          </ol>
        </nav>
      </div>

      <section class="section  py-0">
        <div class="">
          <?php if (isset($_GET['detail'])) { ?>
            <a href="index.php?halaman=daftarrmedis" class="btn btn-sm btn-dark mb-2" style="max-width: 100px;">Kembali</a>
            <?php if ($_SESSION['admin']['level'] == 'dokter' or $_SESSION['admin']['level'] == 'sup' or $_SESSION['admin']['level'] == 'perawat' or $_SESSION['admin']['level'] == 'labx') { ?>
              <?php
              $getRegisInap = $koneksi->query("SELECT *, COUNT(*) as jum FROM registrasi_rawat WHERE  (status_antri!='Pulang') and perawatan ='Rawat Inap' AND TRIM(no_rm) = TRIM('$_GET[detail]')")->fetch_assoc();
              ?>
              <?php if ($getRegisInap['jum'] == 1) { ?>
                <a href="index.php?halaman=cttpenyakit&id=<?= htmlspecialchars($_GET['detail']) ?>&inap&tgl=<?= date('Y-m-d', strtotime($getRegisInap['jadwal'])) ?>" target="_blank" class="btn btn-sm btn-warning mb-2">Visite (Catatan Penyakit)</a>
                <a href="index.php?halaman=rekapinap&id=<?= $getRegisInap['idrawat'] ?>" target="_blank" class="btn btn-sm btn-info mb-2">Biaya</a>
                <a href="index.php?halaman=rujuklab2&id=<?= $getRegisInap['idrawat'] ?>&rm=<?= htmlspecialchars($_GET['detail']) ?>&inap&tgl=<?= date('Y-m-d', strtotime($getRegisInap['jadwal'])) ?>" target="_blank" class="btn btn-sm btn-success mb-2">Lab</a>
                <a href="index.php?halaman=lpo&id=<?= htmlspecialchars($_GET['detail']) ?>&inap&tgl=<?= date('Y-m-d', strtotime($getRegisInap['jadwal'])) ?>" target="_blank" class="btn btn-sm btn-danger mb-2">TTV (Observasi Perawat)</a>

              <?php } ?>
            <?php } ?>
            <?php
            $detailPasien = $pasien->fetch_assoc();
            ?>
            <div class="card p-2 w-100">
              <h5><b>Riwayat RM <?= $detailPasien['nama_pasien'] ?></b></h5>
              <div class="table-responsive mt-0">
                <table class="table mt-0" style="font-size: 12px; ">
                  <thead>
                    <tr>
                      <th>Jadwal</th>
                      <th>Keluhan</th>
                      <!-- <th>Keluhan Tambahan</th> -->
                      <th>Pemeriksaan</th>
                      <th>Lab</th>
                      <th>Diagnosis</th>
                      <th>Pemeriksaan Fisik</th>
                      <th>Obat</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $getRm = $koneksi->query("SELECT * FROM rekam_medis WHERE norm='$_GET[detail]' ORDER BY jadwal DESC LIMIT 5");
                    foreach ($getRm as $data) {
                    ?>
                      <?php $getRegisSingle = $koneksi->query("SELECT * FROM kajian_awal WHERE norm = '$data[norm]' AND tgl_rm <= '" . date('Y-m-d', strtotime($data['jadwal'])) . "' ORDER BY id_rm DESC LIMIT 1")->fetch_assoc(); ?>
                      <tr>
                        <td><?= $data['jadwal'] ?></td>
                        <td>
                          <span>
                            <b>Utama : </b><?= $getRegisSingle['keluhan_utama'] ?><br>
                            <b>Tambahan : </b><?= $data['anamnesa'] ?>
                          </span>
                        </td>
                        <!-- <td><?= $data['anamnesa'] ?></td> -->
                        <td>
                          <ul class="list-group">
                            <?php
                            $getKajian = $koneksi->query("SELECT * FROM kajian_awal WHERE REPLACE(REPLACE(REPLACE(norm, '\t', ' '), '  ', ' '), '  ', ' ') = '$_GET[detail]' AND tgl_rm = '$data[tgl_rm]' Limit 1")->fetch_assoc();
                            // print_r($getKajian);
                            $getLabPoli = $koneksi->query("SELECT * FROM lab_poli WHERE nama_pasien = '$data[nama_pasien]' AND jadwal = '$data[jadwal]' LIMIT 1")->fetch_assoc();
                            ?>
                            <li class="list-group-item"><b>Nadi : <?= $getKajian['nadi'] ?></b></li>
                            <li class="list-group-item"><b>Suhu : <?= $getKajian['suhu_tubuh'] ?></b></li>
                            <li class="list-group-item"><b>S.Oksigen : <?= $getKajian['oksigen'] ?></b></li>
                            <li class="list-group-item"><b>Sistole : <?= $getKajian['sistole'] ?></b></li>
                            <li class="list-group-item"><b>Distole : <?= $getKajian['distole'] ?></b></li>
                            <li class="list-group-item"><b>F. Nafas : <?= $getKajian['frek_nafas'] ?></b></li>
                            <li class="list-group-item"><b>BB : <?= $getKajian['bb'] ?> KG</b></li>
                          </ul>
                        </td>
                        <td>
                          <table class="table">
                            <thead>
                              <tr>
                                <th>Pemeriksaan</th>
                                <th>Hasil</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>Gula Darah</td>
                                <td><?= $getLabPoli['gula_darah'] ?? "-" ?></td>
                              </tr>
                              <tr>
                                <td>Kolestrol</td>
                                <td><?= $getLabPoli['kolestrol'] ?? "-" ?></td>
                              </tr>
                              <tr>
                                <td>Asam Urat</td>
                                <td><?= $getLabPoli['asam_urat'] ?? "-" ?></td>
                              </tr>
                              <?php
                              $getLab = $koneksi->query("SELECT * FROM lab_hasil WHERE REPLACE(REPLACE(REPLACE(norm, '\t', ' '), '  ', ' '), '  ', ' ')  = '$_GET[detail]'  AND tgl_inap = '" . date('Y-m-d', strtotime($data['jadwal'])) . "'");
                              foreach ($getLab as $lab) {
                              ?>
                                <tr>
                                  <td><?= $lab['nama_periksa'] ?></td>
                                  <td><?= $lab['hasil_periksa'] ?></td>
                                </tr>
                              <?php } ?>
                            </tbody>
                          </table>
                        </td>
                        <td><?= $data['diagnosis'] ?></td>
                        <td>
                          <!-- Button trigger modal -->
                          <?php
                          $pemeriksaanFisik = $koneksi->query("SELECT *, COUNT(*) as jumlah FROM pemeriksaan_fisik WHERE REPLACE(REPLACE(REPLACE(norm, '\t', ' '), '  ', ' '), '  ', ' ') = '$_GET[detail]' AND DATE_FORMAT(created_at, '%Y-%m-%d') = '$data[tgl_rm]' ORDER BY created_at DESC LIMIT 1")->fetch_assoc();
                          ?>
                          <?php if ($pemeriksaanFisik['jumlah'] == 1) { ?>
                            <?= ($pemeriksaanFisik['gcs_e'] != 4) ? "gcs_e: " . $pemeriksaanFisik['gcs_e'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['gcs_v'] != 5) ? "gcs_v: " . $pemeriksaanFisik['gcs_v'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['gcs_m'] != 6) ? "gcs_m: " . $pemeriksaanFisik['gcs_m'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['rangsangan_meninggal'] != '-') ? "rangsangan_meninggal: " . $pemeriksaanFisik['rangsangan_meninggal'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['refleks_fisiologis1'] != '+') ? "refleks_fisiologis1: " . $pemeriksaanFisik['refleks_fisiologis1'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['refleks_fisiologis2'] != '+') ? "refleks_fisiologis2: " . $pemeriksaanFisik['refleks_fisiologis2'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['refleks_patologis'] != '-') ? "refleks_patologis: " . $pemeriksaanFisik['refleks_patologis'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['flat'] != 'flat') ? "flat: " . $pemeriksaanFisik['flat'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['hl'] != 'membesar') ? "hl: " . $pemeriksaanFisik['hl'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['assistos'] != '-') ? "assistos: " . $pemeriksaanFisik['assistos'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['thympani'] != '+') ? "thympani: " . $pemeriksaanFisik['thympani'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['soepel'] != '+') ? "soepel: " . $pemeriksaanFisik['soepel'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['ntf_atas_kiri'] != '-') ? "ntf_atas_kiri: " . $pemeriksaanFisik['ntf_atas_kiri'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['ntf_atas'] != '-') ? "ntf_atas: " . $pemeriksaanFisik['ntf_atas'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['ntf_atas_kanan'] != '-') ? "ntf_atas_kanan: " . $pemeriksaanFisik['ntf_atas_kanan'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['ntf_tengah_kiri'] != '-') ? "ntf_tengah_kiri: " . $pemeriksaanFisik['ntf_tengah_kiri'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['ntf_tengah'] != '-') ? "ntf_tengah: " . $pemeriksaanFisik['ntf_tengah'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['ntf_tengah_kanan'] != '-') ? "ntf_tengah_kanan: " . $pemeriksaanFisik['ntf_tengah_kanan'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['ntf_bawah_kiri'] != '-') ? "ntf_bawah_kiri: " . $pemeriksaanFisik['ntf_bawah_kiri'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['ntf_bawah'] != '-') ? "ntf_bawah: " . $pemeriksaanFisik['ntf_bawah'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['ntf_bawah_kanan'] != '-') ? "ntf_bawah_kanan: " . $pemeriksaanFisik['ntf_bawah_kanan'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['bu'] != '+') ? "bu: " . $pemeriksaanFisik['bu'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['bu_komen'] != '') ? "bu_komen: " . $pemeriksaanFisik['bu_komen'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['anemis_kiri'] != '-') ? "anemis_kiri: " . $pemeriksaanFisik['anemis_kiri'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['anemis_kanan'] != '-') ? "anemis_kanan: " . $pemeriksaanFisik['anemis_kanan'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['ikterik_kiri'] != '-') ? "ikterik_kiri: " . $pemeriksaanFisik['ikterik_kiri'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['ikterik_kanan'] != '-') ? "ikterik_kanan: " . $pemeriksaanFisik['ikterik_kanan'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['rcl_kiri'] != '+') ? "rcl_kiri: " . $pemeriksaanFisik['rcl_kiri'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['rcl_kanan'] != '+') ? "rcl_kanan: " . $pemeriksaanFisik['rcl_kanan'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['pupil_kiri'] != '') ? "pupil_kiri: " . $pemeriksaanFisik['pupil_kiri'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['pupil_kanan'] != '') ? "pupil_kanan: " . $pemeriksaanFisik['pupil_kanan'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['visus_kiri'] != '6/6') ? "visus_kiri: " . $pemeriksaanFisik['visus_kiri'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['visus_kanan'] != '6/6') ? "visus_kanan: " . $pemeriksaanFisik['visus_kanan'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['torax'] != 'Simetris') ? "torax: " . $pemeriksaanFisik['torax'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['retraksi'] != '') ? "retraksi: " . $pemeriksaanFisik['retraksi'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['vesikuler_kiri'] != '-') ? "vesikuler_kiri: " . $pemeriksaanFisik['vesikuler_kiri'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['vesikuler_kanan'] != '-') ? "vesikuler_kanan: " . $pemeriksaanFisik['vesikuler_kanan'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['wheezing_kiri'] != '-') ? "wheezing_kiri: " . $pemeriksaanFisik['wheezing_kiri'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['wheezing_kanan'] != '-') ? "wheezing_kanan: " . $pemeriksaanFisik['wheezing_kanan'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['rongki_kiri'] != '-') ? "rongki_kiri: " . $pemeriksaanFisik['rongki_kiri'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['rongki_kanan'] != '-') ? "rongki_kanan: " . $pemeriksaanFisik['rongki_kanan'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['s1s2'] != 'reguler') ? "s1s2: " . $pemeriksaanFisik['s1s2'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['murmur'] != '-') ? "murmur: " . $pemeriksaanFisik['murmur'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['golop'] != '-') ? "golop: " . $pemeriksaanFisik['golop'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['nch_kiri'] != '-') ? "nch_kiri: " . $pemeriksaanFisik['nch_kiri'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['nch_kanan'] != '-') ? "nch_kanan: " . $pemeriksaanFisik['nch_kanan'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['polip_kiri'] != '-') ? "polip_kiri: " . $pemeriksaanFisik['polip_kiri'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['polip_kanan'] != '-') ? "polip_kanan: " . $pemeriksaanFisik['polip_kanan'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['conca_kiri'] != '-') ? "conca_kiri: " . $pemeriksaanFisik['conca_kiri'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['conca_kanan'] != '-') ? "conca_kanan: " . $pemeriksaanFisik['conca_kanan'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['faring_hipertermis'] != '-') ? "faring_hipertermis: " . $pemeriksaanFisik['faring_hipertermis'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['halitosis'] != '-') ? "halitosis: " . $pemeriksaanFisik['halitosis'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['pembesaran_tonsil'] != 'T1') ? "pembesaran_tonsil: " . $pemeriksaanFisik['pembesaran_tonsil'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['serumin_kiri'] != '-') ? "serumin_kiri: " . $pemeriksaanFisik['serumin_kiri'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['serumin_kanan'] != '-') ? "serumin_kanan: " . $pemeriksaanFisik['serumin_kanan'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['typani_intak_kiri'] != '+') ? "typani_intak_kiri: " . $pemeriksaanFisik['typani_intak_kiri'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['typani_intak_kanan'] != '+') ? "typani_intak_kanan: " . $pemeriksaanFisik['typani_intak_kanan'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['pembesaran_getah_bening'] != '') ? "pembesaran_getah_bening: " . $pemeriksaanFisik['pembesaran_getah_bening'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['akral_hangat_atas_kiri'] != '+') ? "akral_hangat_atas_kiri: " . $pemeriksaanFisik['akral_hangat_atas_kiri'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['akral_hangat_atas_kanan'] != '+') ? "akral_hangat_atas_kanan: " . $pemeriksaanFisik['akral_hangat_atas_kanan'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['akral_hangat_bawah_kiri'] != '+') ? "akral_hangat_bawah_kiri: " . $pemeriksaanFisik['akral_hangat_bawah_kiri'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['akral_hangat_bawah_kanan'] != '+') ? "akral_hangat_bawah_kanan: " . $pemeriksaanFisik['akral_hangat_bawah_kanan'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['oe_atas_kiri'] != '-') ? "oe_atas_kiri: " . $pemeriksaanFisik['oe_atas_kiri'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['oe_atas_kanan'] != '-') ? "oe_atas_kanan: " . $pemeriksaanFisik['oe_atas_kanan'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['oe_bawah_kiri'] != '-') ? "oe_bawah_kiri: " . $pemeriksaanFisik['oe_bawah_kiri'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['oe_bawah_kanan'] != '-') ? "oe_bawah_kanan: " . $pemeriksaanFisik['oe_bawah_kanan'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['crt'] != '<= 2') ? "crt: " . $pemeriksaanFisik['crt'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['motorik_atas_kiri'] != 5) ? "motorik_atas_kiri: " . $pemeriksaanFisik['motorik_atas_kiri'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['motorik_atas_kanan'] != 5) ? "motorik_atas_kanan: " . $pemeriksaanFisik['motorik_atas_kanan'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['motorik_bawah_kiri'] != 5) ? "motorik_bawah_kiri: " . $pemeriksaanFisik['motorik_bawah_kiri'] . "<br>" : '' ?>
                            <?= ($pemeriksaanFisik['motorik_bawah_kanan'] != 5) ? "motorik_bawah_kanan: " . $pemeriksaanFisik['motorik_bawah_kanan'] . "<br>" : '' ?>
                            kognitif: <?= $pemeriksaanFisik['kognitif'] ?><br>

                            <button type="button" onclick="upDataPemeriksaanFisik('<?= $pemeriksaanFisik['gcs_e'] ?>', '<?= $pemeriksaanFisik['gcs_v'] ?>', '<?= $pemeriksaanFisik['gcs_m'] ?>', '<?= $pemeriksaanFisik['rangsangan_meninggal'] ?>', '<?= $pemeriksaanFisik['refleks_fisiologis1'] ?>', '<?= $pemeriksaanFisik['refleks_fisiologis2'] ?>', '<?= $pemeriksaanFisik['refleks_patologis'] ?>', '<?= $pemeriksaanFisik['flat'] ?>', '<?= $pemeriksaanFisik['hl'] ?>', '<?= $pemeriksaanFisik['assistos'] ?>', '<?= $pemeriksaanFisik['thympani'] ?>', '<?= $pemeriksaanFisik['soepel'] ?>', '<?= $pemeriksaanFisik['ntf_atas_kiri'] ?>', '<?= $pemeriksaanFisik['ntf_atas'] ?>', '<?= $pemeriksaanFisik['ntf_atas_kanan'] ?>', '<?= $pemeriksaanFisik['ntf_tengah_kiri'] ?>', '<?= $pemeriksaanFisik['ntf_tengah'] ?>', '<?= $pemeriksaanFisik['ntf_tengah_kanan'] ?>', '<?= $pemeriksaanFisik['ntf_bawah_kiri'] ?>', '<?= $pemeriksaanFisik['ntf_bawah'] ?>', '<?= $pemeriksaanFisik['ntf_bawah_kanan'] ?>', '<?= $pemeriksaanFisik['bu'] ?>', '<?= $pemeriksaanFisik['bu_komen'] ?>', '<?= $pemeriksaanFisik['anemis_kiri'] ?>', '<?= $pemeriksaanFisik['anemis_kanan'] ?>', '<?= $pemeriksaanFisik['ikterik_kiri'] ?>', '<?= $pemeriksaanFisik['ikterik_kanan'] ?>', '<?= $pemeriksaanFisik['rcl_kiri'] ?>', '<?= $pemeriksaanFisik['rcl_kanan'] ?>', '<?= $pemeriksaanFisik['pupil_kiri'] ?>', '<?= $pemeriksaanFisik['pupil_kanan'] ?>', '<?= $pemeriksaanFisik['visus_kiri'] ?>', '<?= $pemeriksaanFisik['visus_kanan'] ?>', '<?= $pemeriksaanFisik['torax'] ?>', '<?= $pemeriksaanFisik['retraksi'] ?>', '<?= $pemeriksaanFisik['vesikuler_kiri'] ?>', '<?= $pemeriksaanFisik['vesikuler_kanan'] ?>', '<?= $pemeriksaanFisik['wheezing_kiri'] ?>', '<?= $pemeriksaanFisik['wheezing_kanan'] ?>', '<?= $pemeriksaanFisik['rongki_kiri'] ?>', '<?= $pemeriksaanFisik['rongki_kanan'] ?>', '<?= $pemeriksaanFisik['s1s2'] ?>', '<?= $pemeriksaanFisik['murmur'] ?>', '<?= $pemeriksaanFisik['golop'] ?>', '<?= $pemeriksaanFisik['nch_kiri'] ?>', '<?= $pemeriksaanFisik['nch_kanan'] ?>', '<?= $pemeriksaanFisik['polip_kiri'] ?>', '<?= $pemeriksaanFisik['polip_kanan'] ?>', '<?= $pemeriksaanFisik['conca_kiri'] ?>', '<?= $pemeriksaanFisik['conca_kanan'] ?>', '<?= $pemeriksaanFisik['faring_hipertermis'] ?>', '<?= $pemeriksaanFisik['halitosis'] ?>', '<?= $pemeriksaanFisik['pembesaran_tonsil'] ?>', '<?= $pemeriksaanFisik['serumin_kiri'] ?>', '<?= $pemeriksaanFisik['serumin_kanan'] ?>', '<?= $pemeriksaanFisik['typani_intak_kiri'] ?>', '<?= $pemeriksaanFisik['typani_intak_kanan'] ?>', '<?= $pemeriksaanFisik['pembesaran_getah_bening'] ?>', '<?= $pemeriksaanFisik['akral_hangat_atas_kiri'] ?>', '<?= $pemeriksaanFisik['akral_hangat_atas_kanan'] ?>', '<?= $pemeriksaanFisik['akral_hangat_bawah_kiri'] ?>', '<?= $pemeriksaanFisik['akral_hangat_bawah_kanan'] ?>', '<?= $pemeriksaanFisik['oe_atas_kiri'] ?>', '<?= $pemeriksaanFisik['oe_atas_kanan'] ?>', '<?= $pemeriksaanFisik['oe_bawah_kiri'] ?>', '<?= $pemeriksaanFisik['oe_bawah_kanan'] ?>', '<?= $pemeriksaanFisik['crt'] ?>', '<?= $pemeriksaanFisik['motorik_atas_kiri'] ?>', '<?= $pemeriksaanFisik['motorik_atas_kanan'] ?>', '<?= $pemeriksaanFisik['motorik_bawah_kiri'] ?>', '<?= $pemeriksaanFisik['motorik_bawah_kanan'] ?>', '<?= $pemeriksaanFisik['kognitif'] ?>')" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#showPemeriksaanFisik">
                              <i class="bi bi-eye"></i> Lihat
                            </button>
                          <?php } ?>
                        </td>
                        <td>
                          <?php
                            $getObat = $koneksi->query("SELECT * FROM obat_rm WHERE idrm = '$_GET[detail]'  AND rekam_medis_id = '$data[id_rm]'");
                          ?>
                          <table class="table">
                            <thead>
                              <tr>
                                <th>Nama</th>
                                <th>Jumlah</th>
                                <th>Dosis</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php foreach ($getObat as $obat) { ?>
                                <tr>
                                  <td><?= $obat['nama_obat'] ?></td>
                                  <td><?= $obat['jml_dokter'] ?></td>
                                  <td><?= $obat['dosis1_obat'] ?> x <?= $obat['dosis2_obat'] ?></td>
                                  <?php if ($_SESSION['admin']['level'] == 'apoteker' or $_SESSION['admin']['level'] == 'racik') { ?>
                                    <td>
                                      <a href="index.php?halaman=daftarrmedis&detail=<?= $_GET['detail'] ?>&hapus_obat=<?= $obat['idobat'] ?>" class="btn btn-sm btn-danger text-right" onclick="return confirm('Anda yakin mau menghapus obat ini?')"><i class="bi bi-trash"></i></a>
                                    </td>
                                  <?php } ?>
                                </tr>
                              <?php } ?>
                            </tbody>
                          </table>
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>


            <?php if (isset($_GET['inap'])) { ?>
              <div class="card p-2 mb-2">
                <h5><b>Riwayat Terapi <?= $detailPasien['nama_pasien'] ?></b></h5>
                <table id="myTable3">
                  <thead>
                    <tr>
                      <th>Tindakan</th>
                      <th>Alat</th>
                      <th>BMHP</th>
                      <th>Bentuk Obat</th>
                      <th>Jumlah Obat</th>
                      <th>Dosis Obat</th>
                      <th>Dokter</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $getTerapi = $koneksi->query("SELECT * FROM terapi WHERE nama_pasien = '$detailPasien[nama_pasien]' ORDER BY tgl_tindakan DESC LIMIT 5");
                    foreach ($getTerapi as $dataTerapi) {
                    ?>
                      <tr>
                        <td><?= $dataTerapi['nama_tindakan'] ?></td>
                        <td><?= $dataTerapi['alat_medis'] ?></td>
                        <td><?= $dataTerapi['bmhp'] ?></td>
                        <td><?= $dataTerapi['bentuk_obat'] ?></td>
                        <td><?= $dataTerapi['jml_obat'] ?></td>
                        <td><?= $dataTerapi['dosis_obat'] ?></td>
                        <td><?= $dataTerapi['dokter_resep'] ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            <?php } ?>
            <script>
              function upDataPemeriksaanFisik(gcs_e, gcs_v, gcs_m, rangsangan_meninggal, refleks_fisiologis1, refleks_fisiologis2, refleks_patologis, flat, hl, assistos, thympani, soepel, ntf_atas_kiri, ntf_atas, ntf_atas_kanan, ntf_tengah_kiri, ntf_tengah, ntf_tengah_kanan, ntf_bawah_kiri, ntf_bawah, ntf_bawah_kanan, bu, bu_komen, anemis_kiri, anemis_kanan, ikterik_kiri, ikterik_kanan, rcl_kiri, rcl_kanan, pupil_kiri, pupil_kanan, visus_kiri, visus_kanan, torax, retraksi, vesikuler_kiri, vesikuler_kanan, wheezing_kiri, wheezing_kanan, rongki_kiri, rongki_kanan, s1s2, murmur, golop, nch_kiri, nch_kanan, polip_kiri, polip_kanan, conca_kiri, conca_kanan, faring_hipertermis, halitosis, pembesaran_tonsil, serumin_kiri, serumin_kanan, typani_intak_kiri, typani_intak_kanan, pembesaran_getah_bening, akral_hangat_atas_kiri, akral_hangat_atas_kanan, akral_hangat_bawah_kiri, akral_hangat_bawah_kanan, oe_atas_kiri, oe_atas_kanan, oe_bawah_kiri, oe_bawah_kanan, crt, motorik_atas_kiri, motorik_atas_kanan, motorik_bawah_kiri, motorik_bawah_kanan, kognitif) {
                document.getElementById('hasilPemeriksaan').innerHTML = "gcs_e : <b><span>" + gcs_e + "</span></b><br> gcs_v: <b><span> " + gcs_v + "</span></b><br>  gcs_m: <b><span> " + gcs_m + "</span></b><br>  rangsangan_meninggal: <b><span> " + rangsangan_meninggal + "</span></b><br>  refleks_fisiologis1: <b><span> " + refleks_fisiologis1 + "</span></b><br>  refleks_fisiologis2: <b><span> " + refleks_fisiologis2 + "</span></b><br>  refleks_patologis: <b><span> " + refleks_patologis + "</span></b><br>  flat: <b><span> " + flat + "</span></b><br>  hl: <b><span> " + hl + "</span></b><br>  assistos: <b><span> " + assistos + "</span></b><br>  thympani: <b><span> " + thympani + "</span></b><br>  soepel: <b><span> " + soepel + "</span></b><br>  ntf_atas_kiri: <b><span> " + ntf_atas_kiri + "</span></b><br>  ntf_atas: <b><span> " + ntf_atas + "</span></b><br>  ntf_atas_kanan: <b><span> " + ntf_atas_kanan + "</span></b><br>  ntf_tengah_kiri: <b><span> " + ntf_tengah_kiri + "</span></b><br>  ntf_tengah: <b><span> " + ntf_tengah + "</span></b><br>  ntf_tengah_kanan: <b><span> " + ntf_tengah_kanan + "</span></b><br>  ntf_bawah_kiri: <b><span> " + ntf_bawah_kiri + "</span></b><br>  ntf_bawah: <b><span> " + ntf_bawah + "</span></b><br>  ntf_bawah_kanan: <b><span> " + ntf_bawah_kanan + "</span></b><br>  bu: <b><span> " + bu + "</span></b><br>  bu_komen: <b><span> " + bu_komen + "</span></b><br>  anemis_kiri: <b><span> " + anemis_kiri + "</span></b><br>  anemis_kanan: <b><span> " + anemis_kanan + "</span></b><br>  ikterik_kiri: <b><span> " + ikterik_kiri + "</span></b><br>  ikterik_kanan: <b><span> " + ikterik_kanan + "</span></b><br>  rcl_kiri: <b><span> " + rcl_kiri + "</span></b><br>  rcl_kanan: <b><span> " + rcl_kanan + "</span></b><br>  pupil_kiri: <b><span> " + pupil_kiri + "</span></b><br>  pupil_kanan: <b><span> " + pupil_kanan + "</span></b><br>  visus_kiri: <b><span> " + visus_kiri + "</span></b><br>  visus_kanan: <b><span> " + visus_kanan + "</span></b><br>  torax: <b><span> " + torax + "</span></b><br>  retraksi: <b><span> " + retraksi + "</span></b><br>  vesikuler_kiri: <b><span> " + vesikuler_kiri + "</span></b><br>  vesikuler_kanan: <b><span> " + vesikuler_kanan + "</span></b><br>  wheezing_kiri: <b><span> " + wheezing_kiri + "</span></b><br>  wheezing_kanan: <b><span> " + wheezing_kanan + "</span></b><br>  rongki_kiri: <b><span> " + rongki_kiri + "</span></b><br>  rongki_kanan: <b><span> " + rongki_kanan + "</span></b><br>  s1s2: <b><span> " + s1s2 + "</span></b><br>  murmur: <b><span> " + murmur + "</span></b><br>  golop: <b><span> " + golop + "</span></b><br>  nch_kiri: <b><span> " + nch_kiri + "</span></b><br>  nch_kanan: <b><span> " + nch_kanan + "</span></b><br>  polip_kiri: <b><span> " + polip_kiri + "</span></b><br>  polip_kanan: <b><span> " + polip_kanan + "</span></b><br>  conca_kiri: <b><span> " + conca_kiri + "</span></b><br>  conca_kanan: <b><span> " + conca_kanan + "</span></b><br>  faring_hipertermis: <b><span> " + faring_hipertermis + "</span></b><br>  halitosis: <b><span> " + halitosis + "</span></b><br>  pembesaran_tonsil: <b><span> " + pembesaran_tonsil + "</span></b><br>  serumin_kiri: <b><span> " + serumin_kiri + "</span></b><br>  serumin_kanan: <b><span> " + serumin_kanan + "</span></b><br>  typani_intak_kiri: <b><span> " + typani_intak_kiri + "</span></b><br>  typani_intak_kanan: <b><span> " + typani_intak_kanan + "</span></b><br>  pembesaran_getah_bening: <b><span> " + pembesaran_getah_bening + "</span></b><br> krl_hangat_atas_kiri: <b><span> " + akral_hangat_atas_kiri + "</span></b></b> akal_hangat_atas_kanan: <b><span> " + akral_hangat_atas_kanan + "</span></b><br> krl_hangat_bawah_kiri: <b><span> " + akral_hangat_bawah_kiri + "</span></b><br> krl_hangat_bawah_kanan: <b><span> " + akral_hangat_bawah_kanan + "</span></b>< br> e_tas_kiri: <b><span> " + oe_atas_kiri + "</span></b><br>  oe_atas_kanan: <b><span> " + oe_atas_kanan + "</span></b><br>  oe_bawah_kiri: <b><span> " + oe_bawah_kiri + "</span></b><br>  oe_bawah_kanan: <b><span> " + oe_bawah_kanan + "</span></b><br>  crt: <b><span> " + crt + "</span></b><br>  motorik_atas_kiri: <b><span> " + motorik_atas_kiri + "</span></b><br>  motorik_atas_kanan: <b><span> " + motorik_atas_kanan + "</span></b><br>  motorik_bawah_kiri: <b><span> " + motorik_bawah_kiri + "</span></b><br>  motorik_bawah_kanan: <b><span> " + motorik_bawah_kanan + "</span></b><br>  kognitif: <b><span> " + kognitif + "</span></b><br> ";
              }
            </script>
            <!-- Modal -->
            <div class="modal fade" id="showPemeriksaanFisik" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Lihat Pemeriksaan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <p id="hasilPemeriksaan"></p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-primary">Understood</button> -->
                  </div>
                </div>
              </div>
            </div>
          <?php } ?>

          <div class="row">
            <div class="col-lg-12 col-md-12">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title mb-0" style="margin-bottom: -10px;">Daftar Pasien</h5>
                  <?php if (!isset($_GET['all'])) { ?>
                    <p style="margin-top: -20px; font-size: 12px; text-transform: capitalize;">data yang di tampilkan adalah data pasien datang pada hari ini saja</p>
                    <a href="index.php?halaman=daftarrmedis&all" class="btn btn-sm btn-primary mb-2">Pasien All</a>
                    <?php if (isset($_GET['racik'])) { ?>
                      <a href="index.php?halaman=daftarrmedis&racik&pasrajal" class="btn btn-sm btn-primary mb-2">Pasien Rajal</a>
                      <a href="index.php?halaman=daftarrmedis&racik&pasinap" class="btn btn-sm btn-primary mb-2">Pasien Inap</a>
                    <?php } ?>
                    <?php if (isset($_GET['inap'])) { ?>
                      <button class="btn btn-sm btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#showKamar">Lihat Kamar Terpakai</button>
                      <!-- Modal -->
                      <div class="modal fade" id="showKamar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h1 class="modal-title fs-5" id="staticBackdropLabel">Kamar Terpakai</h1>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                  <thead>
                                    <tr>
                                      <th>Nama Pasien</th>
                                      <th>Jenis Perawatan</th>
                                      <th>Dokter</th>
                                      <th>NoRm</th>
                                      <th>Jadwal</th>
                                      <th>Kamar</th>
                                      <th>Status</th>
                                      <th>Aksi</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                                    $getPasienKamar = $koneksi->query("SELECT *, DATE_FORMAT(jadwal, '%Y-%m-%d') as tgl FROM registrasi_rawat WHERE  (status_antri='Datang' or  status_antri='Pembayaran' or status_antri='Selesai' or status_antri = 'Belum Datang') and perawatan ='Rawat Inap'  order by idrawat desc;");
                                    foreach ($getPasienKamar as $kamar) {
                                    ?>
                                      <tr>
                                        <td><?= $kamar['nama_pasien'] ?></td>
                                        <td><?= $kamar['perawatan'] ?></td>
                                        <td><?= $kamar['dokter_rawat'] ?></td>
                                        <td><?= $kamar['no_rm'] ?></td>
                                        <td><?= $kamar['jadwal'] ?></td>
                                        <td><?= $kamar['kamar'] ?></td>
                                        <td><?= $kamar['status_antri'] ?></td>
                                        <td>
                                          <div class="dropdown">
                                            <?php if (isset($_GET['racik']) or $_SESSION['admin']['level'] == 'apoteker' or $_SESSION['admin']['level'] == 'apoteker' or $_SESSION['admin']['level'] == 'racik') { ?>
                                              <i data-bs-toggle="dropdown" style="color: blue; font-weight: bold; font-size: 20px;" class="bi bi-three-dots-vertical"></i>
                                              <ul class="dropdown-menu">
                                                <li><a href="index.php?halaman=detailrm&id=<?php echo $kamar["no_rm"]; ?>&tgl=<?php echo $kamar["jadwal"]; ?>&racik" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-eye-fill" style="color:darkblue;"></i> Detail</a></li>
                                                <li><a href="index.php?halaman=cttpenyakit&id=<?php echo $kamar["no_rm"] ?>&inap&tgl=<?php echo $kamar["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-clipboard2-pulse" style="color:green;"></i> Catatan Penyakit</a></li>
                                                <li><a href="index.php?halaman=lpo&id=<?php echo $kamar["no_rm"] ?>&inap&tgl=<?php echo $kamar["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-file-earmark-spreadsheet" style="color:orange;"></i>Observasi Perawat</a></li>
                                              <?php } else { ?>
                                                <i data-bs-toggle="dropdown" style="color: blue; font-weight: bold; font-size: 20px;" class="bi bi-three-dots-vertical"></i>
                                                <ul class="dropdown-menu">
                                                  <?php if ($kamar['perawatan'] == 'Rawat Jalan') { ?>
                                                    <li><a href="index.php?halaman=detailrm&id=<?php echo $kamar["no_rm"]; ?>&tgl=<?php echo $kamar["tgl"]; ?>&rawat=<?php echo $kamar["idrawat"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-eye-fill" style="color:darkblue;"></i> Detail</a></li>

                                                    <?php
                                                    $ubah = $koneksi->query("SELECT * FROM rekam_medis WHERE nama_pasien = '$kamar[nama_pasien]' AND jadwal = '$kamar[jadwal]';")->fetch_assoc();
                                                    ?>
                                                    <?php if ((empty($ubah["nama_pasien"])) or $level == "dokter" or  $level == "sup") { ?>
                                                      <li><a href="index.php?halaman=rmedis&id=<?php echo $kamar["no_rm"]; ?>&tgl=<?php echo $kamar["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-card-list" style="color:orangered;"></i> Isi Rekam Medis</a></li>

                                                    <?php } else { ?>
                                                      <!-- <li><a href="index.php?halaman=rmedis&id=<?php echo $kamar["no_rm"]; ?>&tgl=<?php echo $kamar["tgl"]; ?>&ubahrm" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-card-list" style="color:orangered;"></i> Ubah Rekam Medis</a></li> -->
                                                    <?php } ?>
                                                  <?php } else { ?>
                                                    <li><a href="index.php?halaman=detailrm&inap&id=<?php echo $kamar["no_rm"]; ?>&tgl=<?php echo $kamar["jadwal"]; ?>&rawat=<?php echo $kamar["idrawat"]; ?>&&cekrm&idrekammedis=<?= $kamar['id_rm'] ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-eye-fill" style="color:darkblue;"></i> Detail</a></li>
                                                    <li><a href="index.php?halaman=rekapinap&id=<?php echo $kamar["idrawat"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-cash-coin" style="color: red"></i> Rekap</a></li>
                                                    <?php
                                                    $cekKajian = $koneksi->query("SELECT COUNT(*) as jumlah FROM kajian_awal_inap WHERE norm = '$kamar[no_rm]'")->fetch_assoc();
                                                    if ($cekKajian['jumlah'] != 0) {
                                                    ?>
                                                      <li><a href="index.php?halaman=pengkajian&inap&norm=<?php echo $kamar["no_rm"]; ?>&id=<?php echo $kamar["idrawat"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-eye-fill" style="color:darkblue;"></i> Detail Pengkajian</a></li>
                                                    <?php } ?>

                                                    <?php
                                                    $ubah = $koneksi->query("SELECT * FROM rekam_medis WHERE nama_pasien = '$kamar[nama_pasien]' AND jadwal = '$kamar[jadwal]';")->fetch_assoc();
                                                    ?>
                                                    <?php if (empty($ubah["nama_pasien"])) { ?>
                                                      <li><a href="index.php?halaman=rmedis&inap=inap&id=<?php echo $kamar["no_rm"]; ?>&tgl=<?php echo $kamar["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-card-list" style="color:orangered;"></i> Isi Rekam Medis</a></li>
                                                    <?php } else { ?>
                                                      <!-- <li><a href="index.php?halaman=rmedis&id=<?php echo $kamar["no_rm"]; ?>&tgl=<?php echo $kamar["tgl"]; ?>&ubahrm" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-card-list" style="color:orangered;"></i> Ubah Rekam Medis</a></li> -->
                                                    <?php } ?>
                                                    <li><a href="index.php?halaman=falanak&id=<?php echo $kamar["no_rm"] ?>&inap&tgl=<?php echo $kamar["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-file" style="color:blueviolet;"></i> Fallrisk Pediatri (Anak)</a></li>
                                                    <li><a href="index.php?halaman=faldewasa&id=<?php echo $kamar["no_rm"] ?>&inap&tgl=<?php echo $kamar["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-file" style="color:blueviolet;"></i> Fallrisk (Dewasa)</a></li>
                                                    <li><a href="index.php?halaman=masukkeluar&id=<?php echo $kamar["no_rm"] ?>&inap&tgl=<?php echo $kamar["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-box-arrow-left" style="color:black;"></i> Ringkasan Masuk Keluar</a></li>
                                                    <li><a href="index.php?halaman=cttpenyakit&id=<?php echo $kamar["no_rm"] ?>&inap&tgl=<?php echo $kamar["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-clipboard2-pulse" style="color:green;"></i> Catatan Penyakit</a></li>
                                                    <li><a href="index.php?halaman=lpo&id=<?php echo $kamar["no_rm"] ?>&inap&tgl=<?php echo $kamar["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-file-earmark-spreadsheet" style="color:orange;"></i>Observasi Perawat</a></li>
                                                    <li><a href="index.php?halaman=rekonobat&id=<?php echo $kamar["no_rm"] ?>&inap&tgl=<?php echo $kamar["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-capsule" style="color:orange;"></i> Rekonsiliasi Obat</a></li>
                                                    <li><a href="index.php?halaman=asuhangizi&id=<?php echo $kamar["no_rm"] ?>&inap&tgl=<?php echo $kamar["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-file-medical" style="color:darkblue;"></i> Asuhan Gizi</a></li>
                                                    <li><a href="index.php?halaman=edukasi&id=<?php echo $kamar["no_rm"] ?>&inap&tgl=<?php echo $kamar["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-journal-text" style="color:maroon;"></i> Edukasi</a></li>
                                                    <li><a href="index.php?halaman=pulang&id=<?php echo $kamar["no_rm"] ?>&inap&tgl=<?php echo $kamar["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-house-heart-fill" style="color:tomato;"></i> Discharge Planning</a></li>
                                                    <li><a href="index.php?halaman=ivl&id=<?php echo $kamar["no_rm"] ?>&inap&tgl=<?php echo $kamar["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-bandaid-fill" style="color:brown;"></i> IVL</a></li>
                                                  <?php } ?>
                                                  <?php if (isset($_GET['inap'])) { ?>
                                                    <li><a href="index.php?halaman=rujuklab2&id=<?php echo $kamar["idrawat"]; ?>&rm=<?php echo $kamar["no_rm"] ?>&inap&tgl=<?php echo $kamar["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-clipboard2-pulse" style="color:hotpink;"></i> Rujuk Lab</a></li>
                                                  <?php } else { ?>
                                                    <li><a href="index.php?halaman=rujuklab2&id=<?php echo $kamar["idrawat"]; ?>&rm=<?php echo $kamar["no_rm"] ?>&tgl=<?php echo $kamar["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-clipboard2-pulse" style="color:hotpink;"></i> Rujuk Lab</a></li>
                                                  <?php } ?>
                                                  <li><a href="index.php?halaman=tambahterapi&id=<?php echo $kamar["no_rm"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-clipboard2-plus" style="color:darkorchid;"></i>Terapi</a></li>
                                                  <li><a href="index.php?halaman=hapusrm&id=<?php echo $kamar["idrawat"]; ?>" class="dropdown-item" style="text-decoration: none; font-weight: bold; margin-left: 2px;" onclick="return confirm('Anda yakin mau menghapus item ini ?')">
                                                      <i class="bi bi-trash" style="color:red;"></i> Hapus</a></li>
                                                </ul>
                                              <?php } ?>
                                          </div>
                                        </td>
                                      </tr>
                                    <?php } ?>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                              <!-- <button type="button" class="btn btn-primary">Understood</button> -->
                            </div>
                          </div>
                        </div>
                      </div>
                    <?php } ?>
                  <?php } else { ?>
                    <p style="margin-top: -20px; font-size: 12px; text-transform: capitalize;">data yang di tampilkan adalah data seluruh pasien dari semua waktu</p>
                    <a href="index.php?halaman=daftarrmedis" class="btn btn-sm btn-primary mb-2">Pasien Sekarang</a>
                    <form action="" method="get" class="mb-2">
                      <input type="text" name="halaman" id="" value="daftarrmedis" hidden>
                      <input type="text" name="all" id="" value="" hidden>
                      <div class="row">
                        <div class="col-9">
                          <select name="perawatan" class="form-select" id="">
                            <option value="All">All</option>
                            <option value="Rawat Jalan">Rawat Jalan</option>
                            <option value="Rawat Inap">Rawat Inap</option>
                          </select>
                        </div>
                        <div class="col-3">
                          <button type="submit" class="btn btn-primary" name="fil"><i class="bi bi-filter"></i></button>
                        </div>
                      </div>
                    </form>
                  <?php } ?>
                  <?php if (isset($_GET['all']) or isset($_GET['racik'])) { ?>
                    <form method="POST">
                      <div class="row">
                        <div class="col-9">
                          <input type="text" class="form-control mb-2" name="key" placeholder="Search ..." id="">
                        </div>
                        <div class="col-3">
                          <button type="submit" class="btn btn-primary" name="src"><i class="bi bi-search"></i></button>
                        </div>
                      </div>
                    </form>
                  <?php } ?>
                  <!-- Multi Columns Form -->
                  <div class="table-responsive">
                    <table <?php if (!isset($_GET['all']) and !isset($_GET['racik'])) {
                              echo "id='myTable'";
                            } ?> class="table table-striped" style="width:100%; font-size: 12px;">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Nama Pasien</th>
                          <th>Jenis Perawatan</th>
                          <th>Dokter</th>
                          <th>No RM</th>
                          <!-- <th>Jadwal</th> -->
                          <th>Antrian</th>
                          <!-- <th>Diagnosis</th> -->
                          <th>CaraBayar</th>
                          <?php if (!isset($_GET['racik'])) { ?>
                            <th>Status</th>
                          <?php } elseif (isset($_GET['racik']) or $_SESSION['admin']['level'] == 'apoteker' or $_SESSION['admin']['level'] == 'racik') { ?>
                            <th>Status Telaah</th>
                          <?php } ?>
                          <th></th>
                          <!-- <th>Aksi</th> -->

                        </tr>
                      </thead>
                      <tbody>

                        <?php $no = 1 ?>

                        <?php foreach ($pasien as $pecah) : ?>
                          <?php
                          $tel = $koneksi->query("SELECT * FROM telaah_resep where jadwal = '$pecah[jadwal]' and no_rm = '$pecah[no_rm]'")->fetch_assoc();
                          ?>
                          <?php
                          $getLastRM = $koneksi->query("SELECT  *, COUNT(*) AS jumm, MAX(id_rm) as id_rm FROM rekam_medis WHERE norm = '" . htmlspecialchars($pecah['no_rm']) . "' AND DATE_FORMAT(jadwal, '%Y-%m-%d') = '" . date('Y-m-d', strtotime($pecah["tgl"])) . "' ORDER BY id_rm DESC LIMIT 1")->fetch_assoc();
                          $obatData = $koneksi->query("SELECT * FROM obat_rm WHERE rekam_medis_id = '$getLastRM[id_rm]'")->fetch_assoc();
                          $getPasien = $koneksi->query("SELECT * FROM pasien WHERE TRIM(no_rm) = '" . htmlspecialchars($pecah['no_rm']) . "'")->fetch_assoc();
                          ?>

                          <?php if(!isset($_GET['racik'])){?>
                            <tr>
                              <td onclick="toDetaill('<?php echo trim(preg_replace('/\t+/', '', $pecah['no_rm'])); ?>')"
                                <?php if (($_SESSION['admin']['level'] == 'apoteker' || $_SESSION['admin']['level'] == 'racik') && isset($obatData["status_obat"]) && $obatData["status_obat"] == "selesai") {
                                  echo 'class="bg-success text-light"';
                                } ?>>
                                <?php echo $no; ?></td>
                              <td onclick="toDetaill('<?php echo trim(preg_replace('/\t+/', '', $pecah['no_rm'])); ?>')" class="bg-secondary text-light" style="margin-top:10px;"><?php echo $pecah["nama_pasien"]; ?><br>
                                <p class="mt-0 mb-0" style="font-size: 8px; line-height: 10px;"><?= $getPasien['alamat'] ?> | <?php echo $getLastRM["diagnosis"]; ?> <br> <?php echo $pecah["jadwal"]; ?></p>
                              </td>
                              <td style="margin-top:10px;">
                                <?php echo $pecah["perawatan"]; ?>
                                <?php if ($pecah["perawatan"] == "Rawat Inap") { ?>
                                  <br>
                                  <span style="font-size: 9px;" class="btn btn-sm btn-warning" <?php if ($_SESSION['admin']['level'] == 'sup') { ?> data-bs-toggle="modal" data-bs-target="#updateKamar" onclick="upDataKamar('<?= $pecah['idrawat'] ?>', '<?= $pecah['kamar'] ?>')" <?php } ?>><?= $pecah['kamar'] ?></span>
                                <?php } ?>
                              </td>
                              <td style="margin-top:10px;"><?php echo $pecah["dokter_rawat"]; ?></td>
                              <td style="margin-top:10px;"><?php echo $pecah["no_rm"]; ?></td>
                              <!-- <td style="margin-top:10px;"><?php echo $pecah["jadwal"]; ?></td> -->
                              <td style="margin-top:10px;"><?php echo $pecah["antrian"]; ?></td>
                              <!-- <td style="margin-top:10px;"><?php echo $getLastRM["diagnosis"]; ?></td> -->
                              <td style="margin-top:10px;"><?php echo $pecah["carabayar"]; ?></td>
                              <?php if (!isset($_GET['racik'])) { ?>
                                <td style="margin-top:10px;">
                                  <?php if ($_SESSION['admin']['level'] == 'sup') { ?>
                                    <button class="btn btn-sm btn-warning" onclick="upDataStatus('<?= $pecah['idrawat'] ?>')" data-bs-toggle="modal" data-bs-target="#editStatusAntri">
                                      <?= $pecah['status_antri'] ?>
                                    </button>
                                  <?php } else { ?>
                                    <?= $pecah['status_antri'] ?>
                                  <?php } ?>
                                </td>
                              <?php } elseif (isset($_GET['racik']) or $_SESSION['admin']['level'] == 'apoteker' or $_SESSION['admin']['level'] == 'racik') { ?>
                                <td style="margin-top:10px;">
                                  <?php if (!empty($tel["jadwal"])) { ?>
                                    <h6 style="color:success">Selesai</h6>
                                  <?php } else {  ?>
                                    <h6 style="color:red">Belum</h6>
                                  <?php }  ?>
                                </td>
                              <?php } ?>
                              <td>
                                <div class="dropdown">
                                  <?php if (isset($_GET['racik']) or $_SESSION['admin']['level'] == 'apoteker' or $_SESSION['admin']['level'] == 'racik') { ?>
                                    <i data-bs-toggle="dropdown" style="color: blue; font-weight: bold; font-size: 20px;" class="bi bi-three-dots-vertical"></i>
                                    <ul class="dropdown-menu">
                                      <li><a href="index.php?halaman=detailrm&id=<?php echo $pecah["no_rm"]; ?>&tgl=<?php echo $pecah["jadwal"]; ?>&racik&idrekammedis=<?= $getLastRM['id_rm'] ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-eye-fill" style="color:darkblue;"></i> Detail</a></li>
                                      <?php if ($pecah['perawatan'] == 'Rawat Inap') { ?>
                                        <li><a href="index.php?halaman=cttpenyakit&id=<?php echo $pecah["no_rm"] ?>&inap&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-clipboard2-pulse" style="color:green;"></i> Catatan Penyakit</a></li>
                                        <li><a href="index.php?halaman=lpo&id=<?php echo $pecah["no_rm"] ?>&inap&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-file-earmark-spreadsheet" style="color:orange;"></i>Observasi Perawat</a></li>
                                        <li><a href="index.php?halaman=lpogizi&id=<?php echo $pecah["no_rm"] ?>&inap&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-clipboard2-pulse" style="color:green;"></i> Catatan Penyakit (Gizi)</a></li>
                                      <?php } ?>
                                    <?php } else { ?>
                                      <i data-bs-toggle="dropdown" style="color: blue; font-weight: bold; font-size: 20px;" class="bi bi-three-dots-vertical"></i>
                                      <ul class="dropdown-menu">
                                        <?php if ($pecah['perawatan'] == 'Rawat Jalan') { ?>
                                          <?php
                                          // $getLastRM = $koneksi->query("SELECT  *, COUNT(*) AS jumm, MAX(id_rm) as id_rm FROM rekam_medis WHERE norm = '" . htmlspecialchars($pecah['no_rm']) . "' AND DATE_FORMAT(jadwal, '%Y-%m-%d') = '" . date('Y-m-d', strtotime($pecah["tgl"])) . "' ORDER BY id_rm DESC LIMIT 1")->fetch_assoc();
                                          ?>
                                          <li><a href="index.php?halaman=detailrm&id=<?php echo $pecah["no_rm"]; ?>&tgl=<?php echo $pecah["tgl"]; ?>&rawat=<?php echo $pecah["idrawat"]; ?>&cekrm&idrekammedis=<?= $getLastRM['id_rm'] ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-eye-fill" style="color:darkblue;"></i> Detail</a></li>
  
                                          <?php
                                          // $ubah = $koneksi->query("SELECT * FROM rekam_medis WHERE nama_pasien = '$pecah[nama_pasien]' AND jadwal = '$pecah[jadwal]';")->fetch_assoc();
                                          ?>
                                          <li><a href="index.php?halaman=rmedis&id=<?php echo $pecah["no_rm"]; ?>&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-card-list" style="color:orangered;"></i> Isi Rekam Medis</a></li>
                                          <?php if ($getLastRM['jumm'] == 0 or $level == "dokter" or  $level == "sup") { ?>
  
                                          <?php } else { ?>
                                            <!-- <li><a href="index.php?halaman=rmedis&id=<?php echo $pecah["no_rm"]; ?>&tgl=<?php echo $pecah["tgl"]; ?>&ubahrm" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-card-list" style="color:orangered;"></i> Ubah Rekam Medis</a></li> -->
                                          <?php } ?>
                                        <?php } else { ?>
                                          <?php
                                          // $getLastRM = $koneksi->query("SELECT  *, COUNT(*) AS jumm, MAX(id_rm) as id_rm FROM rekam_medis WHERE norm = '" . htmlspecialchars($pecah['no_rm']) . "' AND DATE_FORMAT(jadwal, '%Y-%m-%d') = '" . date('Y-m-d', strtotime($pecah["tgl"])) . "' ORDER BY id_rm DESC LIMIT 1")->fetch_assoc();
                                          ?>
                                          <li><a href="index.php?halaman=detailrm&inap&id=<?php echo $pecah["no_rm"]; ?>&tgl=<?php echo $pecah["jadwal"]; ?>&rawat=<?php echo $pecah["idrawat"]; ?>&cekrm&idrekammedis=<?= $getLastRM['id_rm'] ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-eye-fill" style="color:darkblue;"></i> Detail</a></li>
                                          <li><a href="index.php?halaman=rekapinap&id=<?php echo $pecah["idrawat"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-cash-coin" style="color: red"></i> Rekap</a></li>
                                          <?php
                                          $cekKajian = $koneksi->query("SELECT COUNT(*) as jumlah FROM kajian_awal_inap WHERE norm = '$pecah[no_rm]'")->fetch_assoc();
                                          if ($cekKajian['jumlah'] != 0) {
                                          ?>
                                            <li><a href="index.php?halaman=pengkajian&inap&norm=<?php echo $pecah["no_rm"]; ?>&id=<?php echo $pecah["idrawat"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-eye-fill" style="color:darkblue;"></i> Detail Pengkajian</a></li>
                                          <?php } ?>
  
                                          <?php
                                          $ubah = $koneksi->query("SELECT * FROM rekam_medis WHERE nama_pasien = '$pecah[nama_pasien]' AND jadwal = '$pecah[jadwal]';")->fetch_assoc();
                                          ?>
                                          <?php if (empty($ubah["nama_pasien"])) { ?>
                                            <li><a href="index.php?halaman=rmedis&inap=inap&id=<?php echo $pecah["no_rm"]; ?>&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-card-list" style="color:orangered;"></i> Isi Rekam Medis</a></li>
                                          <?php } else { ?>
                                            <!-- <li><a href="index.php?halaman=rmedis&id=<?php echo $pecah["no_rm"]; ?>&tgl=<?php echo $pecah["tgl"]; ?>&ubahrm" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-card-list" style="color:orangered;"></i> Ubah Rekam Medis</a></li> -->
                                          <?php } ?>
                                          <li><a href="index.php?halaman=falanak&id=<?php echo $pecah["no_rm"] ?>&inap&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-file" style="color:blueviolet;"></i> Fallrisk Pediatri (Anak)</a></li>
                                          <li><a href="index.php?halaman=faldewasa&id=<?php echo $pecah["no_rm"] ?>&inap&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-file" style="color:blueviolet;"></i> Fallrisk (Dewasa)</a></li>
                                          <li><a href="index.php?halaman=masukkeluar&id=<?php echo $pecah["no_rm"] ?>&inap&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-box-arrow-left" style="color:black;"></i> Ringkasan Masuk Keluar</a></li>
                                          <li><a href="index.php?halaman=cttpenyakit&id=<?php echo $pecah["no_rm"] ?>&inap&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-clipboard2-pulse" style="color:green;"></i> Catatan Penyakit</a></li>
                                          <li><a href="index.php?halaman=lpo&id=<?php echo $pecah["no_rm"] ?>&inap&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-file-earmark-spreadsheet" style="color:orange;"></i>Observasi Perawat</a></li>
                                          <li><a href="index.php?halaman=lpogizi&id=<?php echo $pecah["no_rm"] ?>&inap&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-clipboard2-pulse" style="color:green;"></i> Catatan Penyakit (Gizi)</a></li>
                                          <li><a href="index.php?halaman=rekonobat&id=<?php echo $pecah["no_rm"] ?>&inap&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-capsule" style="color:orange;"></i> Rekonsiliasi Obat</a></li>
                                          <li><a href="index.php?halaman=asuhangizi&id=<?php echo $pecah["no_rm"] ?>&inap&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-file-medical" style="color:darkblue;"></i> Asuhan Gizi</a></li>
                                          <li><a href="index.php?halaman=edukasi&id=<?php echo $pecah["no_rm"] ?>&inap&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-journal-text" style="color:maroon;"></i> Edukasi</a></li>
                                          <li><a href="index.php?halaman=pulang&id=<?php echo $pecah["no_rm"] ?>&inap&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-house-heart-fill" style="color:tomato;"></i> Discharge Planning</a></li>
                                          <li><a href="index.php?halaman=ivl&id=<?php echo $pecah["no_rm"] ?>&inap&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-bandaid-fill" style="color:brown;"></i> IVL</a></li>
                                        <?php } ?>
                                        <?php if (isset($_GET['inap'])) { ?>
                                          <li><a href="index.php?halaman=rujuklab2&id=<?php echo $pecah["idrawat"]; ?>&rm=<?php echo $pecah["no_rm"] ?>&inap&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-clipboard2-pulse" style="color:hotpink;"></i> Rujuk Lab</a></li>
                                        <?php } else { ?>
                                          <li><a href="index.php?halaman=rujuklab2&id=<?php echo $pecah["idrawat"]; ?>&rm=<?php echo $pecah["no_rm"] ?>&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-clipboard2-pulse" style="color:hotpink;"></i> Rujuk Lab</a></li>
                                        <?php } ?>
                                        <li><a href="index.php?halaman=tambahterapi&id=<?php echo $pecah["no_rm"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-clipboard2-plus" style="color:darkorchid;"></i>Terapi</a></li>
                                        <li><a href="index.php?halaman=hapusrm&id=<?php echo $pecah["idrawat"]; ?>" class="dropdown-item" style="text-decoration: none; font-weight: bold; margin-left: 2px;" onclick="return confirm('Anda yakin mau menghapus item ini ?')">
                                            <i class="bi bi-trash" style="color:red;"></i> Hapus</a></li>
                                      <?php } ?>
                                      </ul>
                                </div>
                              </td>
                            </tr>
                          <?php }else{?>
                            <?php if (empty($tel["jadwal"])) { ?>
                              <tr>
                                <td onclick="toDetaill('<?php echo trim(preg_replace('/\t+/', '', $pecah['no_rm'])); ?>')"
                                  <?php if (($_SESSION['admin']['level'] == 'apoteker' || $_SESSION['admin']['level'] == 'racik') && isset($obatData["status_obat"]) && $obatData["status_obat"] == "selesai") {
                                    echo 'class="bg-success text-light"';
                                  } ?>>
                                  <?php echo $no; ?></td>
                                <td onclick="toDetaill('<?php echo trim(preg_replace('/\t+/', '', $pecah['no_rm'])); ?>')" class="bg-secondary text-light" style="margin-top:10px;"><?php echo $pecah["nama_pasien"]; ?><br>
                                  <p class="mt-0 mb-0" style="font-size: 8px; line-height: 10px;"><?= $getPasien['alamat'] ?> | <?php echo $getLastRM["diagnosis"]; ?> <br> <?php echo $pecah["jadwal"]; ?></p>
                                </td>
                                <td style="margin-top:10px;">
                                  <?php echo $pecah["perawatan"]; ?>
                                  <?php if ($pecah["perawatan"] == "Rawat Inap") { ?>
                                    <br>
                                    <span style="font-size: 9px;" class="btn btn-sm btn-warning" <?php if ($_SESSION['admin']['level'] == 'sup') { ?> data-bs-toggle="modal" data-bs-target="#updateKamar" onclick="upDataKamar('<?= $pecah['idrawat'] ?>', '<?= $pecah['kamar'] ?>')" <?php } ?>><?= $pecah['kamar'] ?></span>
                                  <?php } ?>
                                </td>
                                <td style="margin-top:10px;"><?php echo $pecah["dokter_rawat"]; ?></td>
                                <td style="margin-top:10px;"><?php echo $pecah["no_rm"]; ?></td>
                                <!-- <td style="margin-top:10px;"><?php echo $pecah["jadwal"]; ?></td> -->
                                <td style="margin-top:10px;"><?php echo $pecah["antrian"]; ?></td>
                                <!-- <td style="margin-top:10px;"><?php echo $getLastRM["diagnosis"]; ?></td> -->
                                <td style="margin-top:10px;"><?php echo $pecah["carabayar"]; ?></td>
                                <?php if (!isset($_GET['racik'])) { ?>
                                  <td style="margin-top:10px;">
                                    <?php if ($_SESSION['admin']['level'] == 'sup') { ?>
                                      <button class="btn btn-sm btn-warning" onclick="upDataStatus('<?= $pecah['idrawat'] ?>')" data-bs-toggle="modal" data-bs-target="#editStatusAntri">
                                        <?= $pecah['status_antri'] ?>
                                      </button>
                                    <?php } else { ?>
                                      <?= $pecah['status_antri'] ?>
                                    <?php } ?>
                                  </td>
                                <?php } elseif (isset($_GET['racik']) or $_SESSION['admin']['level'] == 'apoteker' or $_SESSION['admin']['level'] == 'racik') { ?>
                                  <td style="margin-top:10px;">
                                    <?php if (!empty($tel["jadwal"])) { ?>
                                      <h6 style="color:success">Selesai</h6>
                                    <?php } else {  ?>
                                      <h6 style="color:red">Belum</h6>
                                    <?php }  ?>
                                  </td>
                                <?php } ?>
                                <td>
                                  <div class="dropdown">
                                    <?php if (isset($_GET['racik']) or $_SESSION['admin']['level'] == 'apoteker' or $_SESSION['admin']['level'] == 'racik') { ?>
                                      <i data-bs-toggle="dropdown" style="color: blue; font-weight: bold; font-size: 20px;" class="bi bi-three-dots-vertical"></i>
                                      <ul class="dropdown-menu">
                                        <li><a href="index.php?halaman=detailrm&id=<?php echo $pecah["no_rm"]; ?>&tgl=<?php echo $pecah["jadwal"]; ?>&racik&idrekammedis=<?= $getLastRM['id_rm'] ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-eye-fill" style="color:darkblue;"></i> Detail</a></li>
                                        <?php if ($pecah['perawatan'] == 'Rawat Inap') { ?>
                                          <li><a href="index.php?halaman=cttpenyakit&id=<?php echo $pecah["no_rm"] ?>&inap&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-clipboard2-pulse" style="color:green;"></i> Catatan Penyakit</a></li>
                                          <li><a href="index.php?halaman=lpo&id=<?php echo $pecah["no_rm"] ?>&inap&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-file-earmark-spreadsheet" style="color:orange;"></i>Observasi Perawat</a></li>
                                          <li><a href="index.php?halaman=lpogizi&id=<?php echo $pecah["no_rm"] ?>&inap&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-clipboard2-pulse" style="color:green;"></i> Catatan Penyakit (Gizi)</a></li>
                                        <?php } ?>
                                      <?php } else { ?>
                                        <i data-bs-toggle="dropdown" style="color: blue; font-weight: bold; font-size: 20px;" class="bi bi-three-dots-vertical"></i>
                                        <ul class="dropdown-menu">
                                          <?php if ($pecah['perawatan'] == 'Rawat Jalan') { ?>
                                            <?php
                                            // $getLastRM = $koneksi->query("SELECT  *, COUNT(*) AS jumm, MAX(id_rm) as id_rm FROM rekam_medis WHERE norm = '" . htmlspecialchars($pecah['no_rm']) . "' AND DATE_FORMAT(jadwal, '%Y-%m-%d') = '" . date('Y-m-d', strtotime($pecah["tgl"])) . "' ORDER BY id_rm DESC LIMIT 1")->fetch_assoc();
                                            ?>
                                            <li><a href="index.php?halaman=detailrm&id=<?php echo $pecah["no_rm"]; ?>&tgl=<?php echo $pecah["tgl"]; ?>&rawat=<?php echo $pecah["idrawat"]; ?>&cekrm&idrekammedis=<?= $getLastRM['id_rm'] ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-eye-fill" style="color:darkblue;"></i> Detail</a></li>
    
                                            <?php
                                            // $ubah = $koneksi->query("SELECT * FROM rekam_medis WHERE nama_pasien = '$pecah[nama_pasien]' AND jadwal = '$pecah[jadwal]';")->fetch_assoc();
                                            ?>
                                            <li><a href="index.php?halaman=rmedis&id=<?php echo $pecah["no_rm"]; ?>&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-card-list" style="color:orangered;"></i> Isi Rekam Medis</a></li>
                                            <?php if ($getLastRM['jumm'] == 0 or $level == "dokter" or  $level == "sup") { ?>
    
                                            <?php } else { ?>
                                              <!-- <li><a href="index.php?halaman=rmedis&id=<?php echo $pecah["no_rm"]; ?>&tgl=<?php echo $pecah["tgl"]; ?>&ubahrm" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-card-list" style="color:orangered;"></i> Ubah Rekam Medis</a></li> -->
                                            <?php } ?>
                                          <?php } else { ?>
                                            <?php
                                            // $getLastRM = $koneksi->query("SELECT  *, COUNT(*) AS jumm, MAX(id_rm) as id_rm FROM rekam_medis WHERE norm = '" . htmlspecialchars($pecah['no_rm']) . "' AND DATE_FORMAT(jadwal, '%Y-%m-%d') = '" . date('Y-m-d', strtotime($pecah["tgl"])) . "' ORDER BY id_rm DESC LIMIT 1")->fetch_assoc();
                                            ?>
                                            <li><a href="index.php?halaman=detailrm&inap&id=<?php echo $pecah["no_rm"]; ?>&tgl=<?php echo $pecah["jadwal"]; ?>&rawat=<?php echo $pecah["idrawat"]; ?>&cekrm&idrekammedis=<?= $getLastRM['id_rm'] ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-eye-fill" style="color:darkblue;"></i> Detail</a></li>
                                            <li><a href="index.php?halaman=rekapinap&id=<?php echo $pecah["idrawat"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-cash-coin" style="color: red"></i> Rekap</a></li>
                                            <?php
                                            $cekKajian = $koneksi->query("SELECT COUNT(*) as jumlah FROM kajian_awal_inap WHERE norm = '$pecah[no_rm]'")->fetch_assoc();
                                            if ($cekKajian['jumlah'] != 0) {
                                            ?>
                                              <li><a href="index.php?halaman=pengkajian&inap&norm=<?php echo $pecah["no_rm"]; ?>&id=<?php echo $pecah["idrawat"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-eye-fill" style="color:darkblue;"></i> Detail Pengkajian</a></li>
                                            <?php } ?>
    
                                            <?php
                                            $ubah = $koneksi->query("SELECT * FROM rekam_medis WHERE nama_pasien = '$pecah[nama_pasien]' AND jadwal = '$pecah[jadwal]';")->fetch_assoc();
                                            ?>
                                            <?php if (empty($ubah["nama_pasien"])) { ?>
                                              <li><a href="index.php?halaman=rmedis&inap=inap&id=<?php echo $pecah["no_rm"]; ?>&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-card-list" style="color:orangered;"></i> Isi Rekam Medis</a></li>
                                            <?php } else { ?>
                                              <!-- <li><a href="index.php?halaman=rmedis&id=<?php echo $pecah["no_rm"]; ?>&tgl=<?php echo $pecah["tgl"]; ?>&ubahrm" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-card-list" style="color:orangered;"></i> Ubah Rekam Medis</a></li> -->
                                            <?php } ?>
                                            <li><a href="index.php?halaman=falanak&id=<?php echo $pecah["no_rm"] ?>&inap&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-file" style="color:blueviolet;"></i> Fallrisk Pediatri (Anak)</a></li>
                                            <li><a href="index.php?halaman=faldewasa&id=<?php echo $pecah["no_rm"] ?>&inap&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-file" style="color:blueviolet;"></i> Fallrisk (Dewasa)</a></li>
                                            <li><a href="index.php?halaman=masukkeluar&id=<?php echo $pecah["no_rm"] ?>&inap&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-box-arrow-left" style="color:black;"></i> Ringkasan Masuk Keluar</a></li>
                                            <li><a href="index.php?halaman=cttpenyakit&id=<?php echo $pecah["no_rm"] ?>&inap&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-clipboard2-pulse" style="color:green;"></i> Catatan Penyakit</a></li>
                                            <li><a href="index.php?halaman=lpo&id=<?php echo $pecah["no_rm"] ?>&inap&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-file-earmark-spreadsheet" style="color:orange;"></i>Observasi Perawat</a></li>
                                            <li><a href="index.php?halaman=lpogizi&id=<?php echo $pecah["no_rm"] ?>&inap&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-clipboard2-pulse" style="color:green;"></i> Catatan Penyakit (Gizi)</a></li>
                                            <li><a href="index.php?halaman=rekonobat&id=<?php echo $pecah["no_rm"] ?>&inap&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-capsule" style="color:orange;"></i> Rekonsiliasi Obat</a></li>
                                            <li><a href="index.php?halaman=asuhangizi&id=<?php echo $pecah["no_rm"] ?>&inap&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-file-medical" style="color:darkblue;"></i> Asuhan Gizi</a></li>
                                            <li><a href="index.php?halaman=edukasi&id=<?php echo $pecah["no_rm"] ?>&inap&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-journal-text" style="color:maroon;"></i> Edukasi</a></li>
                                            <li><a href="index.php?halaman=pulang&id=<?php echo $pecah["no_rm"] ?>&inap&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-house-heart-fill" style="color:tomato;"></i> Discharge Planning</a></li>
                                            <li><a href="index.php?halaman=ivl&id=<?php echo $pecah["no_rm"] ?>&inap&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-bandaid-fill" style="color:brown;"></i> IVL</a></li>
                                          <?php } ?>
                                          <?php if (isset($_GET['inap'])) { ?>
                                            <li><a href="index.php?halaman=rujuklab2&id=<?php echo $pecah["idrawat"]; ?>&rm=<?php echo $pecah["no_rm"] ?>&inap&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-clipboard2-pulse" style="color:hotpink;"></i> Rujuk Lab</a></li>
                                          <?php } else { ?>
                                            <li><a href="index.php?halaman=rujuklab2&id=<?php echo $pecah["idrawat"]; ?>&rm=<?php echo $pecah["no_rm"] ?>&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-clipboard2-pulse" style="color:hotpink;"></i> Rujuk Lab</a></li>
                                          <?php } ?>
                                          <li><a href="index.php?halaman=tambahterapi&id=<?php echo $pecah["no_rm"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-clipboard2-plus" style="color:darkorchid;"></i>Terapi</a></li>
                                          <li><a href="index.php?halaman=hapusrm&id=<?php echo $pecah["idrawat"]; ?>" class="dropdown-item" style="text-decoration: none; font-weight: bold; margin-left: 2px;" onclick="return confirm('Anda yakin mau menghapus item ini ?')">
                                              <i class="bi bi-trash" style="color:red;"></i> Hapus</a></li>
                                        <?php } ?>
                                        </ul>
                                  </div>
                                </td>
                              </tr>
                            <?php }?>
                          <?php }?>


                          <?php $no += 1 ?>
                        <?php endforeach ?>
                      </tbody>
                    </table>

                    <!-- Modal Update Kamar -->
                    <script>
                      function upDataKamar(id) {
                        $('#idrawat').val(id);
                        // $('#kamar').val(kamar);
                      }
                    </script>
                    <div class="modal fade" id="updateKamar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Update Kamar Modal</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <form method="post">
                            <div class="modal-body">
                              <input type="hidden" name="idrawat" id="idrawat">
                              <select name="kamar" id="kamar" class="form-control mb-2" id="">
                                <option value="">Pilih Kamar</option>
                                <?php
                                $getKamar = $koneksi->query("SELECT * FROM kamar ORDER BY urut ASC");
                                foreach ($getKamar as $cekEmailTerdaftar) {
                                ?>
                                  <option value="<?= $cekEmailTerdaftar['namakamar'] ?>"><?= $cekEmailTerdaftar['namakamar'] ?></option>
                                <?php } ?>
                              </select>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                              <button type="submit" name="updateKamar" class="btn btn-sm btn-primary">Update Kamar</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                    <?php
                    if (isset($_POST['updateKamar'])) {
                      $koneksi->query("UPDATE registrasi_rawat SET kamar = '" . $_POST['kamar'] . "' WHERE idrawat = '" . $_POST['idrawat'] . "'");
                      echo "<script>alert('Kamar berhasil diupdate');</script>";
                      echo "<script>document.location.href='index.php?halaman=daftarrmedis&inap';</script>";
                    }
                    ?>

                    <!-- Modal Update Status -->
                    <script>
                      function upDataStatus(id) {
                        $('#idrawatStatus').val(id);
                      }
                    </script>
                    <div class="modal fade" id="editStatusAntri" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <form action="" method="post">
                            <div class="modal-body">
                              <label for="">Ubah Status</label>
                              <select name="status_antri_ubah" class="form-control form-control-sm">
                                <option value="">Datang</option>
                                <option value="Pembayaran">Pembayaran</option>
                              </select>
                              <input type="text" class="form-control mb-2" hidden name="idrawatStatus" id="idrawatStatus">
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-sm btn-warning">Ubah Status</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                    <?php
                    if ((isset($_GET['all']) or isset($_GET['racik'])) and !isset($_POST['src'])) {
                      // Display pagination
                      echo '<nav>';
                      echo '<ul class="pagination justify-content-center">';

                      // Back button
                      if ($page > 1) {
                        echo '<li class="page-item"><a class="page-link" href="' . $linkPage . '&page=' . ($page - 1) . '">Back</a></li>';
                      }

                      // Determine the start and end page
                      $start_page = max(1, $page - 2);
                      $end_page = min($total_pages, $page + 2);

                      if ($start_page > 1) {
                        echo '<li class="page-item"><a class="page-link" href="' . $linkPage . '&page=1">1</a></li>';
                        if ($start_page > 2) {
                          echo '<li class="page-item"><span class="page-link">...</span></li>';
                        }
                      }

                      for ($i = $start_page; $i <= $end_page; $i++) {
                        if ($i == $page) {
                          echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
                        } else {
                          echo '<li class="page-item"><a class="page-link" href="' . $linkPage . '&page=' . $i . '">' . $i . '</a></li>';
                        }
                      }

                      if ($end_page < $total_pages) {
                        if ($end_page < $total_pages - 1) {
                          echo '<li class="page-item"><span class="page-link">...</span></li>';
                        }
                        echo '<li class="page-item"><a class="page-link" href="' . $linkPage . '&page=' . $total_pages . '">' . $total_pages . '</a></li>';
                      }

                      // Next button
                      if ($page < $total_pages) {
                        echo '<li class="page-item"><a class="page-link" href="' . $linkPage . '&page=' . ($page + 1) . '">Next</a></li>';
                      }

                      echo '</ul>';
                      echo '</nav>';
                    }
                    ?>

                    <?php
                    if (isset($_POST['status_antri_ubah'])) {
                      $koneksi->query("UPDATE registrasi_rawat SET status_antri = '$_POST[status_antri_ubah]' WHERE idrawat = '$_POST[idrawatStatus]'");
                      echo "<script>alert('Status berhasil diubah');</script>";
                      echo "<script>location='index.php?halaman=daftarrmedis';</script>";
                    }
                    ?>
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
if (isset($_GET['hapus_obat'])) {
  $id = $_GET['hapus_obat'];
  $idrm = $_GET['detail'];
  $koneksi->query("DELETE FROM obat_rm WHERE idobat = '$id'");
  echo "<script>alert('Data berhasil dihapus');</script>";
  echo "<script>location='index.php?halaman=daftarrmedis&detail=$idrm';</script>";
}
?>

<script>
  $(document).ready(function() {
    $('#myTable').DataTable({
      pageLength: 100,
      search: true,
      pagination: true
    });
  });
  $(document).ready(function() {
    $('#myTable2').DataTable({
      pageLength: 100,
      search: true,
      pagination: true
    });
  });
  $(document).ready(function() {
    $('#myTable3').DataTable({
      pageLength: 100,
      search: true,
      pagination: true
    });
  });

  function toDetaill(rm) {
    <?php if (isset($_GET['inap'])) { ?>
      document.location.href = "index.php?halaman=daftarrmedis&inap&detail=" + rm;
    <?php } else { ?>
      document.location.href = "index.php?halaman=daftarrmedis&detail=" + rm;
    <?php } ?>
  }
</script>