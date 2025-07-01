<?php
date_default_timezone_set('Asia/Jakarta');
$perawat = $_SESSION['admin']['username'];
// $id=$_GET['id'];

$date = date("Y-m-d");
$queryKey = '';

$limit = 20; // Number of entries to show in a page


if (isset($_POST['src'])) {
  $queryKey = " AND (registrasi_rawat.nama_pasien LIKE '%$_POST[key]%' OR diagnosis LIKE '%$_POST[key]%' OR perawatan LIKE '%$_POST[key]%' OR dokter_rawat LIKE '%$_POST[key]%' OR no_rm LIKE '%$_POST[key]%' OR antrian LIKE '%$_POST[key]%' OR registrasi_rawat.jadwal LIKE '%$_POST[key]%' OR status_antri LIKE '%$_POST[key]%')";
}
if (isset($_GET['day'])) {
  $queryPasien = "SELECT * FROM registrasi_rawat WHERE perawatan = 'Rawat Jalan' AND date_format(jadwal, '%Y-%m-%d') = '$date' AND shift = '" . $_SESSION['shift'] . "' " . $queryKey . " ORDER BY idrawat DESC";
  $linkPage = "index.php?halaman=daftarregistrasi&day";
} elseif (isset($_GET['all'])) {
  $limit = 1;
  $queryPasien = "SELECT * FROM registrasi_rawat WHERE perawatan = 'Rawat Jalan' AND date_format(jadwal, '%Y-%m-%d') > '$date' " . $queryKey . " ORDER BY idrawat DESC";
  // $queryPasien = "SELECT * FROM registrasi_rawat INNER JOIN rekam_medis ON rekam_medis.jadwal = registrasi_rawat.jadwal WHERE perawatan = 'Rawat Jalan' " . $queryKey . " ORDER BY idrawat DESC";
  $linkPage = "index.php?halaman=daftarregistrasi&all";
  if (isset($_POST['filter'])) {
    $limit = 20;
    $tgl_awal = $_POST['tgl_awal'];
    $tgl_akhir = $_POST['tgl_akhir'];
    $queryPasien = "SELECT * FROM registrasi_rawat INNER JOIN rekam_medis ON rekam_medis.jadwal = registrasi_rawat.jadwal WHERE perawatan = 'Rawat Jalan' AND registrasi_rawat.jadwal BETWEEN '$tgl_awal' AND '$tgl_akhir' " . $queryKey . " ORDER BY idrawat DESC";
  }
} else {
  $queryPasien = "SELECT * FROM registrasi_rawat WHERE perawatan = 'Rawat Jalan' " . $queryKey . " ORDER BY kode DESC";
  $limit = 20; // Number of entries to show in a page
  $linkPage = "index.php?halaman=daftarregistrasi";
}

if (isset($_GET['detail'])) {
  $queryPasien = "SELECT * FROM registrasi_rawat WHERE perawatan = 'Rawat Jalan' AND no_rm = '$_GET[detail]' ORDER BY idrawat DESC LIMIT 1";
}

if (isset($_GET['off'])) {
  $queryPasien = "SELECT * FROM registrasi_rawat WHERE perawatan = 'Rawat Jalan' and DATE_FORMAT(jadwal, '%y/%m')='$_GET[bulan]' and kategori = 'offline' " . $queryKey . " ORDER BY kode DESC";
  $linkPage = "index.php?halaman=daftarregistrasi&off&bulan=$_GET[bulan]";
} elseif (isset($_GET['on'])) {
  $queryPasien = "SELECT * FROM registrasi_rawat WHERE perawatan = 'Rawat Jalan' and DATE_FORMAT(jadwal, '%y/%m')='$_GET[bulan]' and kategori = 'online' " . $queryKey . " ORDER BY kode DESC";
  $linkPage = "index.php?halaman=daftarregistrasi&on&bulan=$_GET[bulan]";
}
//   Pagination
// Parameters for pagination
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
// if(isset($_POST['src'])){
//   $pasien = $koneksi->query($queryPasien);
// }else{
//   $pasien = $koneksi->query($queryPasien." LIMIT $start, $limit;");
// }
if (isset($_GET['detail'])) {
  $pasien = $koneksi->query("SELECT * FROM registrasi_rawat WHERE perawatan = 'Rawat Jalan' AND no_rm = '$_GET[detail]' ORDER BY idrawat DESC LIMIT 1");
} else {
  if (isset($_POST['src'])) {
    $pasien = $koneksi->query($queryPasien);
  } else {
    $pasien = $koneksi->query($queryPasien . " LIMIT $start, $limit");
  }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>KHM WONOREJO</title>
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
        <h1>Daftar Registrasi</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?halaman=daftarpasien" style="color:blue;">Daftar Registrasi</a></li>
          </ol>
        </nav>
      </div><!-- End Page Title -->

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
                            <li class="list-group-item"><b>Nadi : <?= $getKajian['nadi'] ?? '' ?></b></li>
                            <li class="list-group-item"><b>Suhu : <?= $getKajian['suhu_tubuh'] ?? '' ?></b></li>
                            <li class="list-group-item"><b>S.Oksigen : <?= $getKajian['oksigen'] ?? '' ?></b></li>
                            <li class="list-group-item"><b>Sistole : <?= $getKajian['sistole'] ?? '' ?></b></li>
                            <li class="list-group-item"><b>Distole : <?= $getKajian['distole'] ?? '' ?></b></li>
                            <li class="list-group-item"><b>F. Nafas : <?= $getKajian['frek_nafas'] ?? '' ?></b></li>
                            <li class="list-group-item"><b>BB : <?= $getKajian['bb'] ?? '' ?> KG</b></li>
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
                  <h5 class="card-title mb-1">Data Registrasi Diagnosis</h5>
                  <?php if (isset($_GET['day'])) { ?>
                    <span class="mb-2 mt-0" stxyle="font-size: 9px;">Data ditampilkan pada Tgl <?= date('d-m-Y') ?> pada shift <?= $_SESSION['shift'] ?></span> <br>
                  <?php } ?>
                  <button class="btn btn-sm btn-primary mb-2 mt-1" data-bs-toggle="modal" data-bs-target="#showKamar">Lihat Kamar Terpakai</button>
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

                  <form method="POST">
                    <div class="row">
                      <div class="col-9">
                        <input type="text" class="form-control mb-2" placeholder="Search ..." name="key">
                      </div>
                      <div class="col-3">
                        <button class="btn btn-primary" name="src"><i class="bi bi-search"></i></button>
                      </div>
                    </div>
                  </form>
                  <form method="POST">
                    <?php if (isset($_GET['all'])) { ?>
                      <div class="row mt-2mb-4">
                        <h6><b>Filter Berdasarkan Jadwal</b></h6>
                        <div class="col-3">
                          <input type="date" class="form-control mb-2" name="tgl_awal" required>
                        </div>
                        hingga
                        <div class="col-3">
                          <input type="date" class="form-control mb-2" name="tgl_akhir" required>
                        </div>
                        <div class="col-3">
                          <button class="btn btn-primary" name="filter"><i class="bi bi-search"></i></button>
                          <a href="index.php?halaman=daftarregistrasi&all" class="btn btn-secondary">Reset</a>
                        </div>
                      </div>
                    <?php } ?>
                  </form>
                  <!-- Multi Columns Form -->
                  <div class="table-responsive">
                    <table class="table table-striped" style="width:100%; font-size: 12px;">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Nama Pasien</th>
                          <?php if (isset($_GET['all'])) { ?>
                            <th>Diagnosis</th>
                          <?php } ?>
                          <th>Jenis Perawatan</th>
                          <th>Dokter</th>
                          <th>No RM</th>
                          <!-- <th>Jadwal</th> -->
                          <th>Antrian</th>
                          <th>Cara Bayar</th>
                          <th>Status</th>
                          <th></th>
                          <!-- <th>Aksi</th> -->
                        </tr>
                      </thead>
                      <tbody>
                        <?php $no = 1 ?>
                        <?php foreach ($pasien as $pecah) : ?>
                          <?php
                          $getSinglePasien = $koneksi->query("SELECT * FROM pasien WHERE no_rm = '$pecah[no_rm]' LIMIT 1")->fetch_assoc();
                          ?>
                          <tr>
                            <?php if ($pecah['kategori'] == 'offline') { ?>
                              <td onclick="toDetaill('<?php echo trim(preg_replace('/\t+/', '', $pecah['no_rm'])); ?>')"><?php echo $no; ?></td>
                            <?php } else { ?>
                              <td onclick="toDetaill('<?php echo trim(preg_replace('/\t+/', '', $pecah['no_rm'])); ?>')" style="background-color: green; color:white;"><?php echo $no; ?></td>
                              <!-- <td style="background-color: green; color:white;"><?php echo $no; ?></td> -->
                            <?php } ?>
                            <?php
                            $getKajianAwal = $koneksi->query("SELECT COUNT(*) as jum FROM kajian_awal WHERE norm = '$pecah[no_rm]' AND tgl_rm = '" . date('Y-m-d', strtotime($pecah['jadwal'])) . "'")->fetch_assoc();
                            if ($getKajianAwal['jum'] > 0) {
                              $badge = "<sup class='badge bg-success text-light'>Sudah Isi</sup>";
                            } else {
                              $badge = "<sup class='badge bg-danger text-light'>Belum Isi</sup>";
                            }
                            ?>
                            <?php if ($pecah['kategori'] == 'offline') { ?>
                              <td onclick="toDetaill('<?php echo trim(preg_replace('/\t+/', '', $pecah['no_rm'])); ?>')" class="bg-secondary text-light" style="margin-top:10px;" onMouseOver="this.style.background-color='red'"><?php echo $pecah["nama_pasien"]; ?> <?= $badge ?><br>
                                <p class="mt-0 mb-0" style="font-size: 9px; line-height: 11px;"><?= $getSinglePasien['no_bpjs'] ?> <br> Jadwal: <?= $pecah['jadwal'] ?> </p>
                              </td>
                            <?php } else { ?>
                              <td onclick="toDetaill('<?php echo trim(preg_replace('/\t+/', '', $pecah['no_rm'])); ?>')" style="margin-top:10px; background-color: green; color:white;"><?php echo $pecah["nama_pasien"]; ?> <?= $badge ?><br>
                                <p class="mt-0 mb-0" style="font-size: 9px; line-height: 11px;"><?= $getSinglePasien['no_bpjs'] ?> <br> Jadwal: <?= $pecah['jadwal'] ?> </p>
                              </td>
                            <?php } ?>
                            <?php if (isset($_GET['all'])) { ?>
                              <td style="margin-top:10px;"><?php echo $pecah["diagnosis"]; ?></td>
                            <?php } ?>
                            <td style="margin-top:10px;"><?php echo $pecah["perawatan"]; ?></td>
                            <td style="margin-top:10px;"><?php echo $pecah["dokter_rawat"]; ?></td>
                            <td style="margin-top:10px;"><?php echo $pecah["no_rm"]; ?></td>
                            <?php
                            $jadwal = strtotime($pecah['jadwal']) - (3600 * 7);
                            $date = $jadwal;
                            // date_add($date, date_interval_create_from_date_string('-2 hours'));
                            // echo date_format($date, 'Y-m-d\TH:i:s');
                            ?>
                            <!-- <td style="margin-top:10px;"> <?= $pecah['jadwal'] ?></td> -->
                            <td style="margin-top:10px;"><?php echo $pecah["antrian"]; ?></td>
                            <td>
                              <?= $pecah['carabayar'] ?>
                            </td>
                            <td style="margin-top:10px;">
                              <?php if ($pecah["status_antri"] == 'Datang') { ?>
                                <span style="color:green"><?php echo $pecah["status_antri"]; ?></span>
                              <?php } else { ?>
                                <span style="color:red"><?php echo $pecah["status_antri"]; ?></span>
                              <?php }  ?>
                            </td>
                            <td>
                              <div class="dropdown">
                                <?php
                                $ubah = $koneksi->query("SELECT * FROM kajian_awal WHERE nama_pasien = '$pecah[nama_pasien]';")->fetch_assoc();
                                ?>
                                <i data-bs-toggle="dropdown" style="color: blue; font-weight: bold; font-size: 20px;" class="bi bi-three-dots-vertical"></i>
                                <ul class="dropdown-menu">
                                  <li>
                                    <a href="../rawatjalan/printAntrian.php?idrawat=<?php echo $pecah["idrawat"]; ?>" target="_blank" class="dropdown-item" style="text-decoration: none; font-weight: bold; margin-left: 2px;">
                                      <i class="bi bi-printer text-warning"></i> Print Antrian
                                    </a>
                                  </li>
                                  <?php if (empty($ubah['nama_pasien'])) { ?>
                                    <li><a href="index.php?halaman=resume&id=<?php echo $pecah["idrawat"]; ?>&norm=<?php echo $pecah["no_rm"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-card-list" style="color:blueviolet;"></i> Isi Kajian Awal</a></li>
                                  <?php } else { ?>
                                    <li><a href="index.php?halaman=resume&id=<?php echo $pecah["idrawat"]; ?>&norm=<?php echo $pecah["no_rm"]; ?>&ubah" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-card-list" style="color:blueviolet;"></i> Lihat Kajian Awal</a></li>
                                  <?php } ?>
                                  <?php if ($_SESSION['admin']['level'] == 'daftar' or $_SESSION['admin']['level'] == 'sup') { ?>
                                    <li><button onclick="upData('<?= $pecah['idrawat'] ?>', '<?= date('Y-m-d\TH:i:s+00:00', $jadwal); ?>', '<?php echo $pecah['antrian']; ?>', '<?php echo $pecah['dokter_rawat']; ?>', '<?php echo $pecah['no_rm']; ?>', 'Datang')" data-bs-toggle="modal" data-bs-target="#tambahKeluhanUtama" href="index.php?halaman=daftarregistrasi&day&id=<?php echo $pecah["idrawat"]; ?>&jadwal=<?= date("Y-m-d\TH:i:s+00:00", $jadwal); ?>&antrian=<?php echo $pecah["antrian"]; ?>&dokter=<?php echo $pecah["dokter_rawat"]; ?>&norm=<?php echo $pecah["no_rm"]; ?>&status=datang" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-check-circle" style="color:green;"></i> Datang</button></li>
                                  <?php } ?>
                                  <?php $dataPasien = $koneksi->query("SELECT * FROM pasien WHERE nama_lengkap = '$pecah[nama_pasien]'")->fetch_assoc(); ?>
                                  <li><a href="../pasien/fal-risk.php?id=<?php echo $dataPasien["idpasien"]; ?>&kunjungan=<?= $pecah['idrawat'] ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-printer" style="color:black;"></i> Fall Risk</a></li>
                                  <li>
                                    <a href="index.php?halaman=editrawat&idrawat=<?php echo $pecah["idrawat"]; ?>" class="dropdown-item" style="text-decoration: none; font-weight: bold; margin-left: 2px;">
                                      <i class="bi bi-pencil" style="color:green;"></i> Edit
                                    </a>
                                  </li>
                                  <?php if ($_SESSION['admin']['level'] == 'sup') { ?>

                                    <li>
                                      <a href="index.php?halaman=hapuspasien&id=<?php echo $pecah["idrawat"]; ?>&regis" class="dropdown-item" style="text-decoration: none; font-weight: bold; margin-left: 2px;" onclick="return confirm('Anda yakin mau menghapus item ini ?')">
                                        <i class="bi bi-trash" style="color:red;"></i> Hapus</a>
                                    </li>
                                  <?php } ?>
                                </ul>
                              </div>
                            </td>
                          </tr>

                          <?php $no += 1 ?>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                    <script>
                      function upData(id, jadwal, antrian, dokter, norm, status) {
                        document.getElementById('id_id').value = id;
                        document.getElementById('jadwal_id').value = jadwal;
                        document.getElementById('antrian_id').value = antrian;
                        document.getElementById('dokter_id').value = dokter;
                        document.getElementById('norm_id').value = norm;
                        document.getElementById('status_id').value = status;
                      }
                    </script>
                    <!-- Modal -->
                    <div class="modal fade" id="tambahKeluhanUtama" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Keluhan Utama</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <form method="get">
                            <div class="modal-body">
                              <label for="">Input Keluhan Utama</label>
                              <input type="text" name="halaman" value="daftarregistrasi" hidden>
                              <input type="text" name="day" hidden>
                              <textarea name="keluhanUtama" class="form-control " id=""></textarea>
                              <input hidden type="text" name="id" id="id_id">
                              <input hidden type="text" name="jadwal" id="jadwal_id">
                              <input hidden type="text" name="antrian" id="antrian_id">
                              <input hidden type="text" name="dokter" id="dokter_id">
                              <input hidden type="text" name="norm" id="norm_id">
                              <input hidden type="text" name="status" id="status_id">
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-sm btn-primary">Datangkan</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>

                    <?php
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
                    ?>

                  </div>
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
if (isset($_GET['status'])) {
  $getBPJS = $koneksi->query("SELECT * FROM registrasi_rawat WHERE idrawat = '$_GET[id]'")->fetch_assoc();
  $jam = date('H:i:s', strtotime($_GET['jadwal']));
  $koneksi->query("UPDATE registrasi_rawat SET status_antri='Datang', start = '$jam', datang_at= '".date('Y-m-d H:i:s')."', perawat='" . $_SESSION['admin']['namalengkap'] . "', keluhan = '" . htmlspecialchars($_GET['keluhanUtama']) . "' WHERE idrawat='$_GET[id]'");

  if ($getBPJS['carabayar'] == 'bpjs') {
    $koneksi->query("INSERT INTO biaya_rawat (poli, idregis, kasir, shift) VALUES ('0', '$_GET[id]', '', '" . $_SESSION['shift'] . "')");
  } elseif ($getBPJS['carabayar'] == 'malam') {
    $koneksi->query("INSERT INTO biaya_rawat (poli, idregis, kasir, shift) VALUES ('50000', '$_GET[id]', '', '" . $_SESSION['shift'] . "')");
  } elseif ($getBPJS['carabayar'] == 'spesialis anak' or $getBPJS['carabayar'] == 'spesialis penyakit dalam') {
    $koneksi->query("INSERT INTO biaya_rawat (poli, idregis, kasir, shift) VALUES ('200000', '$_GET[id]', '', '" . $_SESSION['shift'] . "')");
  } else {
    $koneksi->query("INSERT INTO biaya_rawat (poli, idregis, kasir, shift) VALUES ('35000', '$_GET[id]', '', '" . $_SESSION['shift'] . "')");
  }

  // Logika Untuk Mengirim Link Rating Otomatis
  $ambilDataKunjungan = $koneksi->query("SELECT * FROM registrasi_rawat JOIN pasien where idrawat='$_GET[id]' and registrasi_rawat.no_rm = '$_GET[norm]'");
  $pecahData = $ambilDataKunjungan->fetch_assoc();

  $getPasienForSendMessage = $koneksi->query("SELECT * FROM pasien WHERE TRIM(no_rm) = TRIM('$_GET[norm]') ORDER BY idpasien DESC LIMIT 1")->fetch_assoc();

  $hp = $getPasienForSendMessage["nohp"];
  $hp2 = substr($hp, 1);
  $hp = '62' . $hp2;
  $tglReminder = date('Y-m-d', strtotime("+1 days"));
  $waktuReminder = date('H:i:s');

  $curl = curl_init();
  include '../rawatjalan/api_token_wa.php';
  $data = [
    'phone' => $hp,
    'date' => $tglReminder,
    'time' => $waktuReminder,
    'timezone' => 'Asia/Jakarta',
    'message' => $mes . $_GET['id'],
    'isGroup' => 'true',
  ];
  curl_setopt(
    $curl,
    CURLOPT_HTTPHEADER,
    array(
      "Authorization: $token",
    )
  );
  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
  curl_setopt($curl, CURLOPT_URL,  "https://jogja.wablas.com/api/schedule");
  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

  $result = curl_exec($curl);
  curl_close($curl);
  echo "<pre>";
  print_r($result);

  // Menanyakan kabar setelah 3 hari 
  $tglReminder1 = date('Y-m-d', strtotime("+3 days"));
  // $tglReminder1 = date('Y-m-d');
  $waktuReminder1 = date('H:i:s', strtotime('+1 minutes'));

  $curl1 = curl_init();
  include '../rawatjalan/api_token_wa.php';
  $data1 = [
    'phone' => $hp,
    'date' => $tglReminder1,
    'time' => $waktuReminder1,
    'timezone' => 'Asia/Jakarta',
    'message' => '*Assalamualaikum wr wb*
Halo ' . $getPasienForSendMessage["nama_lengkap"] . ' 👋, bagaimana kabarnya? Semoga sehat selalu. Kami dari Klinik Husada Mulia ingin memastikan kenyamanan dan kesehatan Anda. 😊

Sudah 3 hari sejak kunjungan terakhir Anda ke klinik kami. Kami ingin bertanya, bagaimana kondisi kesehatan Anda saat ini? Apakah ada keluhan atau hal yang ingin didiskusikan?

Tim kami selalu siap membantu dan memberikan dukungan untuk kebutuhan Anda. Terima kasih telah mempercayai Klinik Husada Mulia dalam perawatan kesehatan Anda.🙏✨',
    'isGroup' => 'true',
  ];
  curl_setopt(
    $curl1,
    CURLOPT_HTTPHEADER,
    array(
      "Authorization: $token",
    )
  );
  curl_setopt($curl1, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($curl1, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl1, CURLOPT_POSTFIELDS, http_build_query($data1));
  curl_setopt($curl1, CURLOPT_URL,  "https://jogja.wablas.com/api/schedule");
  curl_setopt($curl1, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($curl1, CURLOPT_SSL_VERIFYPEER, 0);

  $result1 = curl_exec($curl1);
  curl_close($curl1);
  echo "<pre>";
  print_r($result1);
  // END Menanyakan kabar setelah 3 hari 
  // End Logic

  // $curl = curl_init();
  // $token = "kL44t1Vtsvxk5vlAnTGatfq4lJVjVwhlAjEwQxr6iqYtNCoaO6CtBwpj6VaTiXIQ";
  // $data = [
  //   'phone' => $hp,
  //   'timezone' => 'Asia/Jakarta',
  //   'message' => 'Terimakasih telah berkunjung ke KHM Klakah. Demi meningkatkan pelayanan kami, mohon berikan penilaian kepada staf kami. Klik link berikut: www.simkhm.id/klakah/admin/rating/rating.php?id='.$_GET['id'].'',
  //   'isGroup' => 'true',
  //   'random' => 'false',
  // ];
  // curl_setopt($curl, CURLOPT_HTTPHEADER,
  //   array(
  //     "Authorization: $token",
  //   )
  // );
  // curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
  // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  // curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
  // curl_setopt($curl, CURLOPT_URL,  "https://jogja.wablas.com/api/schedule");
  // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
  // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

  // $result = curl_exec($curl);
  // curl_close($curl);
  // echo "<pre>";
  // print_r($result);


  echo "
    <script>
      alert('Berhasil!');
      document.location.href='index.php?halaman=daftarregistrasi&day';
    </script>
  ";

  if (mysqli_affected_rows($koneksi) > 0) {
  }
}
?>
<script>
  $(document).ready(function() {
    $('#myTable').DataTable({
      search: true,
      pagination: true
    });
  });

  function toDetaill(rm) {
    <?php if (isset($_GET['inap'])) { ?>
      document.location.href = "index.php?halaman=daftarregistrasi&inap&detail=" + rm;
    <?php } else { ?>
      document.location.href = "index.php?halaman=daftarregistrasi&detail=" + rm;
    <?php } ?>
  }
</script>