<?php
  date_default_timezone_set('Asia/Jakarta');
  $perawat = $_SESSION['admin']['username'];
  // $id=$_GET['id'];

  $date = date("Y-m-d");
  $queryKey = '';
  
  if (isset($_POST['src'])) {
    $queryKey = " AND (registrasi_rawat.nama_pasien LIKE '%$_POST[key]%' OR diagnosis LIKE '%$_POST[key]%' OR perawatan LIKE '%$_POST[key]%' OR dokter_rawat LIKE '%$_POST[key]%' OR no_rm LIKE '%$_POST[key]%' OR antrian LIKE '%$_POST[key]%' OR registrasi_rawat.jadwal LIKE '%$_POST[key]%' OR status_antri LIKE '%$_POST[key]%')";
  }
  if (isset($_GET['day'])) {
    $queryPasien = "SELECT * FROM registrasi_rawat WHERE perawatan = 'Rawat Jalan' AND date_format(jadwal, '%Y-%m-%d') = '$date' " . $queryKey . " ORDER BY idrawat DESC";
    $linkPage = "index.php?halaman=daftarregistrasi&day";
  } elseif (isset($_GET['all'])) {
    $queryPasien = "SELECT * FROM registrasi_rawat INNER JOIN rekam_medis ON rekam_medis.jadwal = registrasi_rawat.jadwal WHERE perawatan = 'Rawat Jalan' " . $queryKey . " ORDER BY idrawat DESC";
    $linkPage = "index.php?halaman=daftarregistrasi&all";
    if (isset($_POST['filter'])) {
      $tgl_awal = $_POST['tgl_awal'];
      $tgl_akhir = $_POST['tgl_akhir'];
      $queryPasien = "SELECT * FROM registrasi_rawat INNER JOIN rekam_medis ON rekam_medis.jadwal = registrasi_rawat.jadwal WHERE perawatan = 'Rawat Jalan' AND registrasi_rawat.jadwal BETWEEN '$tgl_awal' AND '$tgl_akhir' " . $queryKey . " ORDER BY idrawat DESC";
    } 
  }else {
    $queryPasien = "SELECT * FROM registrasi_rawat WHERE perawatan = 'Rawat Jalan' " . $queryKey . " ORDER BY kode DESC";
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
  $limit = 100; // Number of entries to show in a page
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

      <section class="section  py-4">
        <div class="">
          <?php if (isset($_GET['detail'])) { ?>
            <a href="index.php?halaman=daftarregistrasi" class="btn btn-sm btn-dark mb-2" style="max-width: 100px;">Kembali</a>

            <?php
            $detailPasien = $pasien->fetch_assoc();
            ?>
            <div class="card p-1 w-100">
              <h5><b>Riwayat RM <?= $detailPasien['nama_pasien'] ?></b></h5>
              <div class="table-responsive mt-0">
                <table class="table mt-0" id="myTable2" style="font-size: 12px;">
                  <thead>
                    <tr>
                      <th>Jadwal</th>
                      <th>Keluhan Utama</th>
                      <th>Keluhan Tambahan</th>
                      <th>Pemeriksaan</th>
                      <th>Diagnosis</th>
                      <th>Pemeriksaan Fisik</th>
                      <th>Obat</th>
                      <th>Lab</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php

                    $getRm = $koneksi->query("SELECT * FROM rekam_medis WHERE norm='$_GET[detail]' ORDER BY jadwal DESC LIMIT 5");
                    foreach ($getRm as $data) {
                    ?>
                      <tr>
                        <td><?= $data['jadwal'] ?></td>
                        <td><?= $data['keluhan_utama'] ?></td>
                        <td><?= $data['anamnesa'] ?></td>
                        <td>
                          <ul class="list-group">
                            <?php
                            $getKajian = $koneksi->query("SELECT * FROM kajian_awal WHERE REPLACE(REPLACE(REPLACE(norm, '\t', ' '), '  ', ' '), '  ', ' ') = '$_GET[detail]' AND tgl_rm = '$data[tgl_rm]' Limit 1")->fetch_assoc();
                            // print_r($getKajian);
                            ?>
                            <li class="list-group-item"><b>Suhu : <?= $getKajian['suhu_tubuh'] ?></b></li>
                            <li class="list-group-item"><b>S.Oksigen : <?= $getKajian['oksigen'] ?></b></li>
                            <li class="list-group-item"><b>Sistole : <?= $getKajian['sistole'] ?></b></li>
                            <li class="list-group-item"><b>Distole : <?= $getKajian['distole'] ?></b></li>
                            <li class="list-group-item"><b>F. Nafas : <?= $getKajian['frek_nafas'] ?></b></li>
                            <li class="list-group-item"><b>Nadi : <?= $getKajian['nadi'] ?></b></li>
                          </ul>
                        </td>
                        <td><?= $data['diagnosis'] ?></td>
                        <td>
                          <!-- Button trigger modal -->
                          <?php 
                            $pemeriksaanFisik = $koneksi->query("SELECT *, COUNT(*) as jumlah FROM pemeriksaan_fisik WHERE REPLACE(REPLACE(REPLACE(norm, '\t', ' '), '  ', ' '), '  ', ' ') = '$_GET[detail]' AND DATE_FORMAT(created_at, '%Y-%m-%d') = '$data[tgl_rm]' ORDER BY created_at DESC LIMIT 1")->fetch_assoc();
                          ?>
                          <?php if($pemeriksaanFisik['jumlah'] == 1){?>
                            <?= ($pemeriksaanFisik['gcs_e'] != 4) ? "gcs_e: ".$pemeriksaanFisik['gcs_e']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['gcs_v'] != 5) ? "gcs_v: ".$pemeriksaanFisik['gcs_v']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['gcs_m'] != 6) ? "gcs_m: ".$pemeriksaanFisik['gcs_m']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['rangsangan_meninggal'] != '-') ? "rangsangan_meninggal: ".$pemeriksaanFisik['rangsangan_meninggal']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['refleks_fisiologis1'] != '+') ? "refleks_fisiologis1: ".$pemeriksaanFisik['refleks_fisiologis1']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['refleks_fisiologis2'] != '+') ? "refleks_fisiologis2: ".$pemeriksaanFisik['refleks_fisiologis2']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['refleks_patologis'] != '-') ? "refleks_patologis: ".$pemeriksaanFisik['refleks_patologis']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['flat'] != 'flat') ? "flat: ".$pemeriksaanFisik['flat']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['hl'] != 'membesar') ? "hl: ".$pemeriksaanFisik['hl']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['assistos'] != '-') ? "assistos: ".$pemeriksaanFisik['assistos']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['thympani'] != '+') ? "thympani: ".$pemeriksaanFisik['thympani']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['soepel'] != '+') ? "soepel: ".$pemeriksaanFisik['soepel']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['ntf_atas_kiri'] != '-') ? "ntf_atas_kiri: ".$pemeriksaanFisik['ntf_atas_kiri']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['ntf_atas'] != '-') ? "ntf_atas: ".$pemeriksaanFisik['ntf_atas']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['ntf_atas_kanan'] != '-') ? "ntf_atas_kanan: ".$pemeriksaanFisik['ntf_atas_kanan']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['ntf_tengah_kiri'] != '-') ? "ntf_tengah_kiri: ".$pemeriksaanFisik['ntf_tengah_kiri']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['ntf_tengah'] != '-') ? "ntf_tengah: ".$pemeriksaanFisik['ntf_tengah']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['ntf_tengah_kanan'] != '-') ? "ntf_tengah_kanan: ".$pemeriksaanFisik['ntf_tengah_kanan']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['ntf_bawah_kiri'] != '-') ? "ntf_bawah_kiri: ".$pemeriksaanFisik['ntf_bawah_kiri']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['ntf_bawah'] != '-') ? "ntf_bawah: ".$pemeriksaanFisik['ntf_bawah']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['ntf_bawah_kanan'] != '-') ? "ntf_bawah_kanan: ".$pemeriksaanFisik['ntf_bawah_kanan']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['bu'] != '+') ? "bu: ".$pemeriksaanFisik['bu']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['bu_komen'] != '') ? "bu_komen: ".$pemeriksaanFisik['bu_komen']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['anemis_kiri'] != '-') ? "anemis_kiri: ".$pemeriksaanFisik['anemis_kiri']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['anemis_kanan'] != '-') ? "anemis_kanan: ".$pemeriksaanFisik['anemis_kanan']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['ikterik_kiri'] != '-') ? "ikterik_kiri: ".$pemeriksaanFisik['ikterik_kiri']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['ikterik_kanan'] != '-') ? "ikterik_kanan: ".$pemeriksaanFisik['ikterik_kanan']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['rcl_kiri'] != '+') ? "rcl_kiri: ".$pemeriksaanFisik['rcl_kiri']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['rcl_kanan'] != '+') ? "rcl_kanan: ".$pemeriksaanFisik['rcl_kanan']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['pupil_kiri'] != '') ? "pupil_kiri: ".$pemeriksaanFisik['pupil_kiri']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['pupil_kanan'] != '') ? "pupil_kanan: ".$pemeriksaanFisik['pupil_kanan']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['visus_kiri'] != '6/6') ? "visus_kiri: ".$pemeriksaanFisik['visus_kiri']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['visus_kanan'] != '6/6') ? "visus_kanan: ".$pemeriksaanFisik['visus_kanan']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['torax'] != 'Simetris') ? "torax: ".$pemeriksaanFisik['torax']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['retraksi'] != '') ? "retraksi: ".$pemeriksaanFisik['retraksi']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['vesikuler_kiri'] != '-') ? "vesikuler_kiri: ".$pemeriksaanFisik['vesikuler_kiri']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['vesikuler_kanan'] != '-') ? "vesikuler_kanan: ".$pemeriksaanFisik['vesikuler_kanan']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['wheezing_kiri'] != '-') ? "wheezing_kiri: ".$pemeriksaanFisik['wheezing_kiri']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['wheezing_kanan'] != '-') ? "wheezing_kanan: ".$pemeriksaanFisik['wheezing_kanan']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['rongki_kiri'] != '-') ? "rongki_kiri: ".$pemeriksaanFisik['rongki_kiri']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['rongki_kanan'] != '-') ? "rongki_kanan: ".$pemeriksaanFisik['rongki_kanan']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['s1s2'] != 'reguler') ? "s1s2: ".$pemeriksaanFisik['s1s2']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['murmur'] != '-') ? "murmur: ".$pemeriksaanFisik['murmur']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['golop'] != '-') ? "golop: ".$pemeriksaanFisik['golop']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['nch_kiri'] != '-') ? "nch_kiri: ".$pemeriksaanFisik['nch_kiri']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['nch_kanan'] != '-') ? "nch_kanan: ".$pemeriksaanFisik['nch_kanan']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['polip_kiri'] != '-') ? "polip_kiri: ".$pemeriksaanFisik['polip_kiri']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['polip_kanan'] != '-') ? "polip_kanan: ".$pemeriksaanFisik['polip_kanan']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['conca_kiri'] != '-') ? "conca_kiri: ".$pemeriksaanFisik['conca_kiri']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['conca_kanan'] != '-') ? "conca_kanan: ".$pemeriksaanFisik['conca_kanan']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['faring_hipertermis'] != '-') ? "faring_hipertermis: ".$pemeriksaanFisik['faring_hipertermis']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['halitosis'] != '-') ? "halitosis: ".$pemeriksaanFisik['halitosis']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['pembesaran_tonsil'] != 'T1') ? "pembesaran_tonsil: ".$pemeriksaanFisik['pembesaran_tonsil']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['serumin_kiri'] != '-') ? "serumin_kiri: ".$pemeriksaanFisik['serumin_kiri']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['serumin_kanan'] != '-') ? "serumin_kanan: ".$pemeriksaanFisik['serumin_kanan']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['typani_intak_kiri'] != '+') ? "typani_intak_kiri: ".$pemeriksaanFisik['typani_intak_kiri']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['typani_intak_kanan'] != '+') ? "typani_intak_kanan: ".$pemeriksaanFisik['typani_intak_kanan']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['pembesaran_getah_bening'] != '') ? "pembesaran_getah_bening: ".$pemeriksaanFisik['pembesaran_getah_bening']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['akral_hangat_atas_kiri'] != '+') ? "akral_hangat_atas_kiri: ".$pemeriksaanFisik['akral_hangat_atas_kiri']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['akral_hangat_atas_kanan'] != '+') ? "akral_hangat_atas_kanan: ".$pemeriksaanFisik['akral_hangat_atas_kanan']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['akral_hangat_bawah_kiri'] != '+') ? "akral_hangat_bawah_kiri: ".$pemeriksaanFisik['akral_hangat_bawah_kiri']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['akral_hangat_bawah_kanan'] != '+') ? "akral_hangat_bawah_kanan: ".$pemeriksaanFisik['akral_hangat_bawah_kanan']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['oe_atas_kiri'] != '-') ? "oe_atas_kiri: ".$pemeriksaanFisik['oe_atas_kiri']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['oe_atas_kanan'] != '-') ? "oe_atas_kanan: ".$pemeriksaanFisik['oe_atas_kanan']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['oe_bawah_kiri'] != '-') ? "oe_bawah_kiri: ".$pemeriksaanFisik['oe_bawah_kiri']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['oe_bawah_kanan'] != '-') ? "oe_bawah_kanan: ".$pemeriksaanFisik['oe_bawah_kanan']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['crt'] != '<= 2') ? "crt: ".$pemeriksaanFisik['crt']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['motorik_atas_kiri'] != 5) ? "motorik_atas_kiri: ".$pemeriksaanFisik['motorik_atas_kiri']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['motorik_atas_kanan'] != 5) ? "motorik_atas_kanan: ".$pemeriksaanFisik['motorik_atas_kanan']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['motorik_bawah_kiri'] != 5) ? "motorik_bawah_kiri: ".$pemeriksaanFisik['motorik_bawah_kiri']."<br>" : '' ?>
                            <?= ($pemeriksaanFisik['motorik_bawah_kanan'] != 5) ? "motorik_bawah_kanan: ".$pemeriksaanFisik['motorik_bawah_kanan']."<br>" : '' ?>
                            kognitif: <?= $pemeriksaanFisik['kognitif']?><br>
                            <button type="button" onclick="upDataPemeriksaanFisik('<?= $pemeriksaanFisik['gcs_e']?>', '<?= $pemeriksaanFisik['gcs_v']?>', '<?= $pemeriksaanFisik['gcs_m']?>', '<?= $pemeriksaanFisik['rangsangan_meninggal']?>', '<?= $pemeriksaanFisik['refleks_fisiologis1']?>', '<?= $pemeriksaanFisik['refleks_fisiologis2']?>', '<?= $pemeriksaanFisik['refleks_patologis']?>', '<?= $pemeriksaanFisik['flat']?>', '<?= $pemeriksaanFisik['hl']?>', '<?= $pemeriksaanFisik['assistos']?>', '<?= $pemeriksaanFisik['thympani']?>', '<?= $pemeriksaanFisik['soepel']?>', '<?= $pemeriksaanFisik['ntf_atas_kiri']?>', '<?= $pemeriksaanFisik['ntf_atas']?>', '<?= $pemeriksaanFisik['ntf_atas_kanan']?>', '<?= $pemeriksaanFisik['ntf_tengah_kiri']?>', '<?= $pemeriksaanFisik['ntf_tengah']?>', '<?= $pemeriksaanFisik['ntf_tengah_kanan']?>', '<?= $pemeriksaanFisik['ntf_bawah_kiri']?>', '<?= $pemeriksaanFisik['ntf_bawah']?>', '<?= $pemeriksaanFisik['ntf_bawah_kanan']?>', '<?= $pemeriksaanFisik['bu']?>', '<?= $pemeriksaanFisik['bu_komen']?>', '<?= $pemeriksaanFisik['anemis_kiri']?>', '<?= $pemeriksaanFisik['anemis_kanan']?>', '<?= $pemeriksaanFisik['ikterik_kiri']?>', '<?= $pemeriksaanFisik['ikterik_kanan']?>', '<?= $pemeriksaanFisik['rcl_kiri']?>', '<?= $pemeriksaanFisik['rcl_kanan']?>', '<?= $pemeriksaanFisik['pupil_kiri']?>', '<?= $pemeriksaanFisik['pupil_kanan']?>', '<?= $pemeriksaanFisik['visus_kiri']?>', '<?= $pemeriksaanFisik['visus_kanan']?>', '<?= $pemeriksaanFisik['torax']?>', '<?= $pemeriksaanFisik['retraksi']?>', '<?= $pemeriksaanFisik['vesikuler_kiri']?>', '<?= $pemeriksaanFisik['vesikuler_kanan']?>', '<?= $pemeriksaanFisik['wheezing_kiri']?>', '<?= $pemeriksaanFisik['wheezing_kanan']?>', '<?= $pemeriksaanFisik['rongki_kiri']?>', '<?= $pemeriksaanFisik['rongki_kanan']?>', '<?= $pemeriksaanFisik['s1s2']?>', '<?= $pemeriksaanFisik['murmur']?>', '<?= $pemeriksaanFisik['golop']?>', '<?= $pemeriksaanFisik['nch_kiri']?>', '<?= $pemeriksaanFisik['nch_kanan']?>', '<?= $pemeriksaanFisik['polip_kiri']?>', '<?= $pemeriksaanFisik['polip_kanan']?>', '<?= $pemeriksaanFisik['conca_kiri']?>', '<?= $pemeriksaanFisik['conca_kanan']?>', '<?= $pemeriksaanFisik['faring_hipertermis']?>', '<?= $pemeriksaanFisik['halitosis']?>', '<?= $pemeriksaanFisik['pembesaran_tonsil']?>', '<?= $pemeriksaanFisik['serumin_kiri']?>', '<?= $pemeriksaanFisik['serumin_kanan']?>', '<?= $pemeriksaanFisik['typani_intak_kiri']?>', '<?= $pemeriksaanFisik['typani_intak_kanan']?>', '<?= $pemeriksaanFisik['pembesaran_getah_bening']?>', '<?= $pemeriksaanFisik['akral_hangat_atas_kiri']?>', '<?= $pemeriksaanFisik['akral_hangat_atas_kanan']?>', '<?= $pemeriksaanFisik['akral_hangat_bawah_kiri']?>', '<?= $pemeriksaanFisik['akral_hangat_bawah_kanan']?>', '<?= $pemeriksaanFisik['oe_atas_kiri']?>', '<?= $pemeriksaanFisik['oe_atas_kanan']?>', '<?= $pemeriksaanFisik['oe_bawah_kiri']?>', '<?= $pemeriksaanFisik['oe_bawah_kanan']?>', '<?= $pemeriksaanFisik['crt']?>', '<?= $pemeriksaanFisik['motorik_atas_kiri']?>', '<?= $pemeriksaanFisik['motorik_atas_kanan']?>', '<?= $pemeriksaanFisik['motorik_bawah_kiri']?>', '<?= $pemeriksaanFisik['motorik_bawah_kanan']?>', '<?= $pemeriksaanFisik['kognitif']?>')" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#showPemeriksaanFisik">
                              <i class="bi bi-eye"></i> Lihat
                            </button>        
                          <?php }?>
                        </td>
                        <td>
                          <?php
                          $getObat = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[detail]'  AND DATE_FORMAT(tgl_pasien, '%Y-%m-%d') = DATE_FORMAT('$data[jadwal]', '%Y-%m-%d')");
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
                                </tr>
                              <?php } ?>
                            </tbody>
                          </table>
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
                              <?php
                              $getLab = $koneksi->query("SELECT * FROM lab_hasil WHERE REPLACE(REPLACE(REPLACE(norm, '\t', ' '), '  ', ' '), '  ', ' ')  = '$_GET[detail]'");
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
              function upDataPemeriksaanFisik(gcs_e, gcs_v, gcs_m, rangsangan_meninggal, refleks_fisiologis1, refleks_fisiologis2, refleks_patologis, flat, hl, assistos, thympani, soepel, ntf_atas_kiri, ntf_atas, ntf_atas_kanan, ntf_tengah_kiri, ntf_tengah, ntf_tengah_kanan, ntf_bawah_kiri, ntf_bawah, ntf_bawah_kanan, bu, bu_komen, anemis_kiri, anemis_kanan, ikterik_kiri, ikterik_kanan, rcl_kiri, rcl_kanan, pupil_kiri, pupil_kanan, visus_kiri, visus_kanan, torax, retraksi, vesikuler_kiri, vesikuler_kanan, wheezing_kiri, wheezing_kanan, rongki_kiri, rongki_kanan, s1s2, murmur, golop, nch_kiri, nch_kanan, polip_kiri, polip_kanan, conca_kiri, conca_kanan, faring_hipertermis, halitosis, pembesaran_tonsil, serumin_kiri, serumin_kanan, typani_intak_kiri, typani_intak_kanan, pembesaran_getah_bening, akral_hangat_atas_kiri, akral_hangat_atas_kanan, akral_hangat_bawah_kiri, akral_hangat_bawah_kanan, oe_atas_kiri, oe_atas_kanan, oe_bawah_kiri, oe_bawah_kanan, crt, motorik_atas_kiri, motorik_atas_kanan, motorik_bawah_kiri, motorik_bawah_kanan, kognitif){
                document.getElementById('gcs_e_id').innerHTML = gcs_e;
                document.getElementById('gcs_v_id').innerHTML = gcs_v;
                document.getElementById('gcs_m_id').innerHTML = gcs_m;
                document.getElementById('rangsangan_meninggal_id').innerHTML = rangsangan_meninggal;
                document.getElementById('refleks_fisiologis1_id').innerHTML = refleks_fisiologis1;
                document.getElementById('refleks_fisiologis2_id').innerHTML = refleks_fisiologis2;
                document.getElementById('refleks_patologis_id').innerHTML = refleks_patologis;
                document.getElementById('flat_id').innerHTML = flat;
                document.getElementById('hl_id').innerHTML = hl;
                document.getElementById('assistos_id').innerHTML = assistos;
                document.getElementById('thympani_id').innerHTML = thympani;
                document.getElementById('soepel_id').innerHTML = soepel;
                document.getElementById('ntf_atas_kiri_id').innerHTML = ntf_atas_kiri;
                document.getElementById('ntf_atas_id').innerHTML = ntf_atas;
                document.getElementById('ntf_atas_kanan_id').innerHTML = ntf_atas_kanan;
                document.getElementById('ntf_tengah_kiri_id').innerHTML = ntf_tengah_kiri;
                document.getElementById('ntf_tengah_id').innerHTML = ntf_tengah;
                document.getElementById('ntf_tengah_kanan_id').innerHTML = ntf_tengah_kanan;
                document.getElementById('ntf_bawah_kiri_id').innerHTML = ntf_bawah_kiri;
                document.getElementById('ntf_bawah_id').innerHTML = ntf_bawah;
                document.getElementById('ntf_bawah_kanan_id').innerHTML = ntf_bawah_kanan;
                document.getElementById('bu_id').innerHTML = bu;
                document.getElementById('bu_komen_id').innerHTML = bu_komen;
                document.getElementById('anemis_kiri_id').innerHTML = anemis_kiri;
                document.getElementById('anemis_kanan_id').innerHTML = anemis_kanan;
                document.getElementById('ikterik_kiri_id').innerHTML = ikterik_kiri;
                document.getElementById('ikterik_kanan_id').innerHTML = ikterik_kanan;
                document.getElementById('rcl_kiri_id').innerHTML = rcl_kiri;
                document.getElementById('rcl_kanan_id').innerHTML = rcl_kanan;
                document.getElementById('pupil_kiri_id').innerHTML = pupil_kiri;
                document.getElementById('pupil_kanan_id').innerHTML = pupil_kanan;
                document.getElementById('visus_kiri_id').innerHTML = visus_kiri;
                document.getElementById('visus_kanan_id').innerHTML = visus_kanan;
                document.getElementById('torax_id').innerHTML = torax;
                document.getElementById('retraksi_id').innerHTML = retraksi;
                document.getElementById('vesikuler_kiri_id').innerHTML = vesikuler_kiri;
                document.getElementById('vesikuler_kanan_id').innerHTML = vesikuler_kanan;
                document.getElementById('wheezing_kiri_id').innerHTML = wheezing_kiri;
                document.getElementById('wheezing_kanan_id').innerHTML = wheezing_kanan;
                document.getElementById('rongki_kiri_id').innerHTML = rongki_kiri;
                document.getElementById('rongki_kanan_id').innerHTML = rongki_kanan;
                document.getElementById('s1s2_id').innerHTML = s1s2;
                document.getElementById('murmur_id').innerHTML = murmur;
                document.getElementById('golop_id').innerHTML = golop;
                document.getElementById('nch_kiri_id').innerHTML = nch_kiri;
                document.getElementById('nch_kanan_id').innerHTML = nch_kanan;
                document.getElementById('polip_kiri_id').innerHTML = polip_kiri;
                document.getElementById('polip_kanan_id').innerHTML = polip_kanan;
                document.getElementById('conca_kiri_id').innerHTML = conca_kiri;
                document.getElementById('conca_kanan_id').innerHTML = conca_kanan;
                document.getElementById('faring_hipertermis_id').innerHTML = faring_hipertermis;
                document.getElementById('halitosis_id').innerHTML = halitosis;
                document.getElementById('pembesaran_tonsil_id').innerHTML = pembesaran_tonsil;
                document.getElementById('serumin_kiri_id').innerHTML = serumin_kiri;
                document.getElementById('serumin_kanan_id').innerHTML = serumin_kanan;
                document.getElementById('typani_intak_kiri_id').innerHTML = typani_intak_kiri;
                document.getElementById('typani_intak_kanan_id').innerHTML = typani_intak_kanan;
                document.getElementById('pembesaran_getah_bening_id').innerHTML = pembesaran_getah_bening;
                document.getElementById('akral_hangat_atas_kiri_id').innerHTML = akral_hangat_atas_kiri;
                document.getElementById('akral_hangat_atas_kanan_id').innerHTML = akral_hangat_atas_kanan;
                document.getElementById('akral_hangat_bawah_kiri_id').innerHTML = akral_hangat_bawah_kiri;
                document.getElementById('akral_hangat_bawah_kanan_id').innerHTML = akral_hangat_bawah_kanan;
                document.getElementById('oe_atas_kiri_id').innerHTML = oe_atas_kiri;
                document.getElementById('oe_atas_kanan_id').innerHTML = oe_atas_kanan;
                document.getElementById('oe_bawah_kiri_id').innerHTML = oe_bawah_kiri;
                document.getElementById('oe_bawah_kanan_id').innerHTML = oe_bawah_kanan;
                document.getElementById('crt_id').innerHTML = crt;
                document.getElementById('motorik_atas_kiri_id').innerHTML = motorik_atas_kiri;
                document.getElementById('motorik_atas_kanan_id').innerHTML = motorik_atas_kanan;
                document.getElementById('motorik_bawah_kiri_id').innerHTML = motorik_bawah_kiri;
                document.getElementById('motorik_bawah_kanan_id').innerHTML = motorik_bawah_kanan;
                document.getElementById('kognitif_id').innerHTML = kognitif;
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
                    gcs_e : <b><span id="gcs_e_id"></span></b><br> 
                    gcs_v : <b><span id="gcs_v_id"></span></b><br> 
                    gcs_m : <b><span id="gcs_m_id"></span></b><br> 
                    rangsangan_meninggal : <b><span id="rangsangan_meninggal_id"></span></b><br> 
                    refleks_fisiologis1 : <b><span id="refleks_fisiologis1_id"></span></b><br> 
                    refleks_fisiologis2 : <b><span id="refleks_fisiologis2_id"></span></b><br> 
                    refleks_patologis : <b><span id="refleks_patologis_id"></span></b><br> 
                    flat : <b><span id="flat_id"></span></b><br> 
                    hl : <b><span id="hl_id"></span></b><br> 
                    assistos : <b><span id="assistos_id"></span></b><br> 
                    thympani : <b><span id="thympani_id"></span></b><br> 
                    soepel : <b><span id="soepel_id"></span></b><br> 
                    ntf_atas_kiri : <b><span id="ntf_atas_kiri_id"></span></b><br> 
                    ntf_atas : <b><span id="ntf_atas_id"></span></b><br> 
                    ntf_atas_kanan : <b><span id="ntf_atas_kanan_id"></span></b><br> 
                    ntf_tengah_kiri : <b><span id="ntf_tengah_kiri_id"></span></b><br> 
                    ntf_tengah : <b><span id="ntf_tengah_id"></span></b><br> 
                    ntf_tengah_kanan : <b><span id="ntf_tengah_kanan_id"></span></b><br> 
                    ntf_bawah_kiri : <b><span id="ntf_bawah_kiri_id"></span></b><br> 
                    ntf_bawah : <b><span id="ntf_bawah_id"></span></b><br> 
                    ntf_bawah_kanan : <b><span id="ntf_bawah_kanan_id"></span></b><br> 
                    bu : <b><span id="bu_id"></span></b><br> 
                    bu_komen : <b><span id="bu_komen_id"></span></b><br> 
                    anemis_kiri : <b><span id="anemis_kiri_id"></span></b><br> 
                    anemis_kanan : <b><span id="anemis_kanan_id"></span></b><br> 
                    ikterik_kiri : <b><span id="ikterik_kiri_id"></span></b><br> 
                    ikterik_kanan : <b><span id="ikterik_kanan_id"></span></b><br> 
                    rcl_kiri : <b><span id="rcl_kiri_id"></span></b><br> 
                    rcl_kanan : <b><span id="rcl_kanan_id"></span></b><br> 
                    pupil_kiri : <b><span id="pupil_kiri_id"></span></b><br> 
                    pupil_kanan : <b><span id="pupil_kanan_id"></span></b><br> 
                    visus_kiri : <b><span id="visus_kiri_id"></span></b><br> 
                    visus_kanan : <b><span id="visus_kanan_id"></span></b><br> 
                    torax : <b><span id="torax_id"></span></b><br> 
                    retraksi : <b><span id="retraksi_id"></span></b><br> 
                    vesikuler_kiri : <b><span id="vesikuler_kiri_id"></span></b><br> 
                    vesikuler_kanan : <b><span id="vesikuler_kanan_id"></span></b><br> 
                    wheezing_kiri : <b><span id="wheezing_kiri_id"></span></b><br> 
                    wheezing_kanan : <b><span id="wheezing_kanan_id"></span></b><br> 
                    rongki_kiri : <b><span id="rongki_kiri_id"></span></b><br> 
                    rongki_kanan : <b><span id="rongki_kanan_id"></span></b><br> 
                    s1s2 : <b><span id="s1s2_id"></span></b><br> 
                    murmur : <b><span id="murmur_id"></span></b><br> 
                    golop : <b><span id="golop_id"></span></b><br> 
                    nch_kiri : <b><span id="nch_kiri_id"></span></b><br> 
                    nch_kanan : <b><span id="nch_kanan_id"></span></b><br> 
                    polip_kiri : <b><span id="polip_kiri_id"></span></b><br> 
                    polip_kanan : <b><span id="polip_kanan_id"></span></b><br> 
                    conca_kiri : <b><span id="conca_kiri_id"></span></b><br> 
                    conca_kanan : <b><span id="conca_kanan_id"></span></b><br> 
                    faring_hipertermis : <b><span id="faring_hipertermis_id"></span></b><br> 
                    halitosis : <b><span id="halitosis_id"></span></b><br> 
                    pembesaran_tonsil : <b><span id="pembesaran_tonsil_id"></span></b><br> 
                    serumin_kiri : <b><span id="serumin_kiri_id"></span></b><br> 
                    serumin_kanan : <b><span id="serumin_kanan_id"></span></b><br> 
                    typani_intak_kiri : <b><span id="typani_intak_kiri_id"></span></b><br> 
                    typani_intak_kanan : <b><span id="typani_intak_kanan_id"></span></b><br> 
                    pembesaran_getah_bening : <b><span id="pembesaran_getah_bening_id"></span></b><br> 
                    akral_hangat_atas_kiri : <b><span id="akral_hangat_atas_kiri_id"></span></b><br> 
                    akral_hangat_atas_kanan : <b><span id="akral_hangat_atas_kanan_id"></span></b><br> 
                    akral_hangat_bawah_kiri : <b><span id="akral_hangat_bawah_kiri_id"></span></b><br> 
                    akral_hangat_bawah_kanan : <b><span id="akral_hangat_bawah_kanan_id"></span></b><br> 
                    oe_atas_kiri : <b><span id="oe_atas_kiri_id"></span></b><br> 
                    oe_atas_kanan : <b><span id="oe_atas_kanan_id"></span></b><br> 
                    oe_bawah_kiri : <b><span id="oe_bawah_kiri_id"></span></b><br> 
                    oe_bawah_kanan : <b><span id="oe_bawah_kanan_id"></span></b><br> 
                    crt : <b><span id="crt_id"></span></b><br> 
                    motorik_atas_kiri : <b><span id="motorik_atas_kiri_id"></span></b><br> 
                    motorik_atas_kanan : <b><span id="motorik_atas_kanan_id"></span></b><br> 
                    motorik_bawah_kiri : <b><span id="motorik_bawah_kiri_id"></span></b><br> 
                    motorik_bawah_kanan : <b><span id="motorik_bawah_kanan_id"></span></b><br> 
                    kognitif : <b><span id="kognitif_id"></span></b><br> 
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Understood</button>
                  </div>
                </div>
              </div>
            </div>
          <?php } ?>

          <div class="row">
            <div class="col-lg-12 col-md-12">

              <div class="card">
                <div class="card-body">
                  <!--   <div style="margin-top: 50px; margin-bottom: 30px; text-align: right;">
               
                <a href="index.php?halaman=pasien" class="btn btn-primary"><i class="bi bi-plus"></i> Pasien</a>

                </div> -->
                  <h5 class="card-title">Data Registrasi Diagnosis</h5>
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
                    <table class="table table-striped" style="width:100%; font-size: 13px;">
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
                          <th>Jadwal</th>
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

                          <tr>
                            <?php if ($pecah['kategori'] == 'offline') { ?>
                              <td onclick="toDetaill('<?php echo trim(preg_replace('/\t+/', '', $pecah['no_rm'])); ?>')"><?php echo $no; ?></td>
                            <?php } else { ?>
                              <td onclick="toDetaill('<?php echo trim(preg_replace('/\t+/', '', $pecah['no_rm'])); ?>')" style="background-color: green; color:white;"><?php echo $no; ?></td>
                              <!-- <td style="background-color: green; color:white;"><?php echo $no; ?></td> -->
                            <?php } ?>
                            <?php if ($pecah['kategori'] == 'offline') { ?>
                              <td onclick="toDetaill('<?php echo trim(preg_replace('/\t+/', '', $pecah['no_rm'])); ?>')" class="bg-secondary text-light" style="margin-top:10px;" onMouseOver="this.style.background-color='red'"><?php echo $pecah["nama_pasien"]; ?></td>
                            <?php } else { ?>
                              <td onclick="toDetaill('<?php echo trim(preg_replace('/\t+/', '', $pecah['no_rm'])); ?>')" style="margin-top:10px; background-color: green; color:white;"><?php echo $pecah["nama_pasien"]; ?></td>
                            <?php } ?>
                            <?php if (isset($_GET['all'])) { ?>
                              <td onclick="toDetaill('<?php echo trim(preg_replace('/\t+/', '', $pecah['no_rm'])); ?>')" onclick="toDetaill('<?php echo trim(preg_replace('/\t+/', '', $pecah['no_rm'])); ?>')" style="margin-top:10px;"><?php echo $pecah["diagnosis"]; ?></td>
                            <?php } ?>
                            <td onclick="toDetaill('<?php echo trim(preg_replace('/\t+/', '', $pecah['no_rm'])); ?>')" style="margin-top:10px;"><?php echo $pecah["perawatan"]; ?></td>
                            <td onclick="toDetaill('<?php echo trim(preg_replace('/\t+/', '', $pecah['no_rm'])); ?>')" style="margin-top:10px;"><?php echo $pecah["dokter_rawat"]; ?></td>
                            <td onclick="toDetaill('<?php echo trim(preg_replace('/\t+/', '', $pecah['no_rm'])); ?>')" style="margin-top:10px;"><?php echo $pecah["no_rm"]; ?></td>
                            <?php
                            $jadwal = strtotime($pecah['jadwal']) - (3600 * 7);
                            $date = $jadwal;
                            // date_add($date, date_interval_create_from_date_string('-2 hours'));
                            // echo date_format($date, 'Y-m-d\TH:i:s');
                            ?>
                            <td onclick="toDetaill('<?php echo trim(preg_replace('/\t+/', '', $pecah['no_rm'])); ?>')" style="margin-top:10px;"> <?= $pecah['jadwal'] ?></td>
                            <td style="margin-top:10px;"><?php echo $pecah["antrian"]; ?></td>
                            <td>
                              <?= $pecah['carabayar']?>
                            </td>
                            <td onclick="toDetaill('<?php echo trim(preg_replace('/\t+/', '', $pecah['no_rm'])); ?>')" style="margin-top:10px;">
                              <?php if ($pecah["status_antri"] == 'Datang') { ?>
                                <h6 style="color:green"><?php echo $pecah["status_antri"]; ?></h6>
                              <?php } else { ?>
                                <h6 style="color:red"><?php echo $pecah["status_antri"]; ?></h6>
                              <?php }  ?>
                            </td>
                            <td>
                              <div class="dropdown">
                                <?php
                                $ubah = $koneksi->query("SELECT * FROM kajian_awal WHERE nama_pasien = '$pecah[nama_pasien]';")->fetch_assoc();
                                ?>
                                <i data-bs-toggle="dropdown" style="color: blue; font-weight: bold; font-size: 20px;" class="bi bi-three-dots-vertical"></i>
                                <ul class="dropdown-menu">
                                  <?php if (empty($ubah['nama_pasien'])) { ?>
                                    <li><a href="index.php?halaman=resume&id=<?php echo $pecah["idrawat"]; ?>&norm=<?php echo $pecah["no_rm"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-card-list" style="color:blueviolet;"></i> Isi Kajian Awal</a></li>
                                  <?php } else { ?>
                                    <li><a href="index.php?halaman=resume&id=<?php echo $pecah["idrawat"]; ?>&norm=<?php echo $pecah["no_rm"]; ?>&ubah" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-card-list" style="color:blueviolet;"></i> Lihat Kajian Awal</a></li>
                                  <?php } ?>
                                  <li><a href="index.php?halaman=daftarregistrasi&day&id=<?php echo $pecah["idrawat"]; ?>&jadwal=<?= date("Y-m-d\TH:i:s+00:00", $jadwal); ?>&antrian=<?php echo $pecah["antrian"]; ?>&dokter=<?php echo $pecah["dokter_rawat"]; ?>&norm=<?php echo $pecah["no_rm"]; ?>&status=datang" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-check-circle" style="color:green;"></i> Datang</a></li>

                                  <?php $dataPasien = $koneksi->query("SELECT * FROM pasien WHERE nama_lengkap = '$pecah[nama_pasien]'")->fetch_assoc(); ?>
                                  <li><a href="../pasien/fal-risk.php?id=<?php echo $dataPasien["idpasien"]; ?>&kunjungan=<?= $pecah['idrawat'] ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-printer" style="color:black;"></i> Fall Risk</a></li>

                                  <li>
                                    <a href="index.php?halaman=editrawat&idrawat=<?php echo $pecah["idrawat"]; ?>" class="dropdown-item" style="text-decoration: none; font-weight: bold; margin-left: 2px;">
                                      <i class="bi bi-pencil" style="color:green;"></i> Edit
                                    </a>
                                  </li>

                                  <li><a href="index.php?halaman=hapuspasien&id=<?php echo $pecah["idrawat"]; ?>&regis" class="dropdown-item" style="text-decoration: none; font-weight: bold; margin-left: 2px;" onclick="return confirm('Anda yakin mau menghapus item ini ?')">
                                      <i class="bi bi-trash" style="color:red;"></i> Hapus</a></li>
                                </ul>
                              </div>
                            </td>
                          </tr>

                          <?php $no += 1 ?>
                        <?php endforeach; ?>

                      </tbody>
                    </table>
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

<?php if (isset($_GET['status'])) {
  $getBPJS = $koneksi->query("SELECT * FROM registrasi_rawat WHERE idrawat = '$_GET[id]'")->fetch_assoc();
  $jam = date('H:i:s', strtotime($_GET['jadwal']));
  $koneksi->query("UPDATE registrasi_rawat SET status_antri='Datang', start = '$jam', perawat='" . $_SESSION['admin']['username'] . "' WHERE idrawat='$_GET[id]'");

  if ($getBPJS['carabayar'] == 'bpjs') {
    $koneksi->query(" 
  
      (poli, idregis, kasir, shift)
  
      VALUES ('0', '$_GET[id]', '', '" . $_SESSION['shift'] . "')
  
      ");
  } elseif ($getBPJS['carabayar'] == 'malam') {
    $koneksi->query("INSERT INTO biaya_rawat
  
      (poli, idregis, kasir, shift)
  
      VALUES ('50000', '$_GET[id]', '', '" . $_SESSION['shift'] . "')
  
      ");
  } else {
    $koneksi->query("INSERT INTO biaya_rawat
  
  (poli, idregis, kasir, shift)
  
  VALUES ('20000', '$_GET[id]', '', '" . $_SESSION['shift'] . "')
  
  ");
  }

  // Logika Untuk Mengirim Link Rating Otomatis
    $ambilDataKunjungan=$koneksi->query("SELECT * FROM registrasi_rawat JOIN pasien where idrawat='$_GET[id]' and registrasi_rawat.no_rm = '$_GET[norm]'"); 
    $pecahData=$ambilDataKunjungan->fetch_assoc();

    $getPasienForSendMessage = $koneksi->query("SELECT * FROM pasien WHERE TRIM(no_rm) = TRIM('$_GET[norm]') ORDER BY idpasien DESC LIMIT 1")->fetch_assoc();

    $hp=$getPasienForSendMessage["nohp"];
    $hp2=substr($hp,1);
    $hp='62'.$hp2;
    $tglReminder = date('Y-m-d', strtotime("+1 days"));
    $waktuReminder = date('H:i:s');

    $curl = curl_init();
    include '../rawatjalan/api_token_wa.php';
    $data = [
        'phone' => $hp,
        'date' => $tglReminder,
        'time' => $waktuReminder,
        'timezone' => 'Asia/Jakarta',
        'message' => $mes.$_GET['id'],
        'isGroup' => 'true',
    ];
    curl_setopt($curl, CURLOPT_HTTPHEADER,
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
Halo '.$getPasienForSendMessage["nama_lengkap"].' 👋, bagaimana kabarnya? Semoga sehat selalu. Kami dari Klinik Husada Mulia ingin memastikan kenyamanan dan kesehatan Anda. 😊

Sudah 3 hari sejak kunjungan terakhir Anda ke klinik kami. Kami ingin bertanya, bagaimana kondisi kesehatan Anda saat ini? Apakah ada keluhan atau hal yang ingin didiskusikan?

Tim kami selalu siap membantu dan memberikan dukungan untuk kebutuhan Anda. Terima kasih telah mempercayai Klinik Husada Mulia dalam perawatan kesehatan Anda.🙏✨',
          'isGroup' => 'true',
      ];
      curl_setopt($curl1, CURLOPT_HTTPHEADER,
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
} ?>
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