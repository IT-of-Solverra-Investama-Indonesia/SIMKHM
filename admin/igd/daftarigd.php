<?php
date_default_timezone_set('Asia/Jakarta');

// Handle POST Action untuk Tindak Lanjut - Diproses Sebelum Rendering HTML
if (isset($_POST['saveTindakLanjut'])) {
  $getIgd = $koneksi->query("SELECT * FROM igd WHERE idigd = '" . $_POST['idIGD'] . "'")->fetch_assoc();

  $koneksi->query("UPDATE igd SET tindak = '" . $_POST['tindak'] . "' WHERE idigd = '" . $_POST['idIGD'] . "'");

  if ($_POST['tindak'] == "Rawat") {
    $getLast = $koneksi->query("SELECT * FROM registrasi_rawat ORDER BY idrawat DESC LIMIT 1")->fetch_assoc();
    $idrawat = $getLast['idrawat'] + 1;

    // INSERT LAB IGD TO INAP 
    $koneksi->query("UPDATE lab SET id_lab_inap = '$idrawat' WHERE id_lab_igd = '" . $_POST['idIGD'] . "' ");
    $koneksi->query("UPDATE lab_hasil SET id_inap = '$idrawat' WHERE id_igd = '" . $_POST['idIGD'] . "' ");
    // END INSERT LAB IGD TO INAP 

    // INSERT BIAYA IGD TO INAP
    $getDetailIgd = $koneksi->query("SELECT * FROM igddetail WHERE id = '" . $_POST['idIGD'] . "'");
    while ($detail = $getDetailIgd->fetch_assoc()) {
      $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran, ket, petugas, shiftinap) VALUES ('$idrawat', '$detail[tgl]', '$detail[biaya]', '$detail[besaran]', '$detail[ket]', '$detail[petugas]', '$detail[shiftinap]')");
    }
    // END INSERT BIAYA IGD TO INAP

    $koneksi->query("INSERT INTO registrasi_rawat (idrawat, nama_pasien, dokter_rawat, perawatan, kamar, jenis_kunjungan, id_pasien, no_rm, jadwal, antrian, status_antri, carabayar, shift, perawat, perujuk, perujuk_hp, perujuk_file) VALUES ('$idrawat', '$getIgd[nama_pasien]', '" . $_SESSION['dokter_rawat'] . "', 'Rawat Inap', '$_POST[kamar]', 'Kunjungan Sakit', '', '$getIgd[no_rm]', '" . $getIgd['tgl_masuk'] . date(' H:i:s') . "', '', 'Belum Datang', '$getIgd[carabayar]', '" . $_SESSION['shift'] . "', '" . $_SESSION['admin']['username'] . "', '$getIgd[perujuk]', '$getIgd[perujuk_hp]', '$getIgd[perujuk_file]')");

    $tgl = date('Y-m-d');

    $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran) VALUES ('$idrawat', '$tgl', 'BHP IGD', '10000') ");
    $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran) VALUES ('$idrawat', '$tgl', 'Dokter IGD', '25000') ");

    if (isset($_POST['teman']) && is_array($_POST['teman'])) {
      $shift = isset($_SESSION['shift']) ? $_SESSION['shift'] : '';

      // Loop through each selected user and insert into database
      foreach ($_POST['teman'] as $petugas) {
        $petugas = htmlspecialchars($petugas);
        $koneksi->query("INSERT INTO kajian_awal_inap_tag (idrawat, petugas, shift) VALUES ('$idrawat', '$petugas', '$shift')");
      }
    }
  } else {
    $getLastRegis = $koneksi->query("SELECT * FROM registrasi_rawat ORDER BY idrawat DESC LIMIT 1")->fetch_assoc();
    // GET ANTRIAN UNTUK PASIEN 
    if (isset($_SESSION['shift'])) {
      if ($_SESSION['shift'] == 'Pagi') {
        $sif = 'pagi';
      } elseif ($_SESSION['shift'] == 'Sore') {
        $sif = 'sore';
      } elseif ($_SESSION['shift'] == 'Malam') {
        $sif = 'malam';
      }

      $whereShiftCondition = " AND tgltab.shift='$sif'";
    }
    $date = date('Ymd') + 0;
    $time = date('Hi') - 300;
    $getLastAntrian = $koneksi->query("SELECT kode, urut, ket FROM tgltab WHERE NOT EXISTS(SELECT antrian FROM registrasi_rawat WHERE registrasi_rawat.kode = tgltab.kode) AND NOT EXISTS(SELECT antrian FROM registrasi_booking WHERE DATE_FORMAT(jadwal, '%Y-%m-%d') = '" . date('Y-m-d', strtotime($date)) . "' AND registrasi_booking.kode = tgltab.kode) AND tgl=$date AND jam>=$time $whereShiftCondition ORDER BY tgltab.no ASC LIMIT 1")->fetch_assoc();
    // END GET ANTRIAN UNTUK PASIEN 
    $getPasien = $koneksi->query("SELECT * FROM pasien WHERE no_rm = '" . $getIgd['no_rm'] . "'")->fetch_assoc();
    $idrawat = $getLastRegis['idrawat'] + 1;

    $nama_pasien = $getIgd['nama_pasien'];
    $jenis_kunjungan = 'Kunjungan Sakit';
    $perawatan = "Rawat Jalan";
    $dokter_rawat = $_SESSION['dokter_rawat'];
    $jadwal = $getIgd['tgl_masuk'] . date(' H:i:s');
    $status_antri = "Datang";
    $antrian = $getLastAntrian['urut'];
    $id_pasien = $getPasien['idpasien'];
    $no_rm = $getPasien['no_rm'];
    $carabayar = $getIgd['carabayar'];
    $kasir = $getLastRegis['kasir'];
    $petugaspoli = $getLastRegis['petugaspoli'];
    $perawat = $getLastRegis['perawat'];
    $shift = $_SESSION['shift'];
    $kode = $getLastAntrian['kode'];
    $start = date('H:i:s');
    $end = "";
    $datang_at = $jadwal;
    $perawatan_at = null;
    $dokter_at = null;
    $pembayaran_at = null;
    $apoteker_check_at = null;
    $keluhan = $getIgd['keluhan'];
    $kategori = "offline";

    $koneksi->query("INSERT INTO registrasi_rawat (idrawat, nama_pasien, jenis_kunjungan, perawatan, dokter_rawat, jadwal, status_antri, antrian, id_pasien, no_rm, carabayar, kasir, petugaspoli, perawat, shift, kode, start, end, datang_at, perawat_at, dokter_at, pembayaran_at, apoteker_check_at, keluhan, kategori) VALUES ('$idrawat', '$nama_pasien', '$jenis_kunjungan', '$perawatan', '$dokter_rawat', '$jadwal', '$status_antri', '$antrian', '$id_pasien', '$no_rm', '$carabayar', '$kasir', '$petugaspoli', '$perawat', '$shift', '$kode', '$start', '$end', '$datang_at', '$perawatan_at', '$dokter_at', '$pembayaran_at', '$apoteker_check_at', '$keluhan', '$kategori')");

    $getLastRekamMedis = $koneksi->query("SELECT * FROM rekam_medis ORDER BY id_rm DESC LIMIT 1")->fetch_assoc();

    $newIDRM = $getLastRekamMedis['id_rm'] + 1;

    $koneksi->query("INSERT INTO igd_pick_rm (rekam_medis_id, registrasi_id, igd_id) VALUES ('" . $newIDRM . "', '" . $idrawat . "', '" . $_POST['idIGD'] . "')");

    // MEMINDAHKAN OBAT IGD KE RAWAT JALAN
    $getObatIGD = $koneksi->query("UPDATE obat_rm SET rekam_medis_id = '$newIDRM' WHERE idrm = '$no_rm' AND tgl_pasien='$getIgd[tgl_masuk]' AND (obat_igd != '') ORDER BY idobat DESC");
    // END MEMINDAHKAN OBAT IGD KE RAWAT JALAN

    // MEMINDAHKAN LAB IGD KE RAWAT JALAN 
    $koneksi->query("UPDATE lab SET id_lab = '$idrawat' WHERE id_lab_igd = '" . $_POST['idIGD'] . "' ");
    $koneksi->query("UPDATE lab_hasil SET id_lab_h = '$idrawat' WHERE id_igd = '" . $_POST['idIGD'] . "' ");
    $biaya_lab = $koneksi->query("SELECT SUM(biaya) AS biayaTotal FROM lab WHERE id_lab_igd = '" . $_POST['idIGD'] . "' AND id_lab = '$idrawat'")->fetch_assoc();
    $getLab = $koneksi->query("SELECT * FROM lab WHERE id_lab_igd = '" . $_POST['idIGD'] . "' AND id_lab = '$idrawat'");
    $periksaLab = '';
    $biayaLab = $biaya_lab['biayaTotal'] != null ? $biaya_lab['biayaTotal'] : 0;
    while ($lab = $getLab->fetch_assoc()) {
      $periksaLab = $periksaLab . $lab['tipe_lab'] . ', ';
    }
    // END MEMINDAHKAN LAB IGD KE RAWAT JALAN 

    $poli = 35000;
    if ($_SESSION['shift'] == 'Malam') {
      $poli = 50000;
    }
    if ($getIgd['carabayar'] == 'bpjs') {
      $poli = 0;
    }

    // INSERT BIAYA OBAT IGD KE PENJUALAN RESEP
    $getAllObatIGD = $koneksi->query("SELECT * FROM obat_rm WHERE idrm = '$no_rm' AND tgl_pasien='$getIgd[tgl_masuk]' AND (obat_igd != '') ORDER BY idobat DESC");
    $nota = date('ymdhis') . '-' . rand(1000, 9999);
    $akun = $nama_pasien . ' (ODC)';
    $tgl_jual = date('Y-m-d');
    foreach ($getAllObatIGD as $allObat) {
      $obatLokalSingle = $koneksi->query("SELECT * FROM apotek WHERE id_obat = '$allObat[kode_obat]' ORDER BY idapotek DESC LIMIT 1")->fetch_assoc();
      $kode_obat = $allObat['kode_obat'];
      $obatMasterSingle = $koneksimaster->query("SELECT * FROM master_obat WHERE kode_obat = '$kode_obat'")->fetch_assoc();
      $harga_umum = $obatLokalSingle['harga_beli'] * ($obatMasterSingle['margin_resep'] / 100);
      $nama_obat = $allObat['nama_obat'];
      $diskon_obat = 0;
      $jumlah = $allObat['jml_dokter'];
      $harga_beli = $obatLokalSingle['harga_beli'];
      $petugas = $koneksi->query("SELECT * FROM penjualan_resep ORDER BY id_penjualan DESC LIMIT 1")->fetch_assoc()['petugas'];

      $koneksi->query("INSERT INTO `penjualan_resep`(`nota`, `tgl_jual`, `kode_obat`, `nama_obat`, `harga_umum`, `diskon_obat`, `jumlah`, `harga_beli`, `akun`, `petugas`, `shift`) VALUES ('$nota', '$tgl_jual', '$kode_obat', '$nama_obat', '$harga_umum', '$diskon_obat', '$jumlah', '$harga_beli', '$akun', '$petugas', '$shift')");
    }

    $koneksi->query("DELETE FROM obat_rm WHERE idrm = '$no_rm' AND tgl_pasien='$getIgd[tgl_masuk]' AND (obat_igd != '')");

    // END INSERT BIAYA OBAT IGD KE PENJUALAN RESEP

    if ($_POST['tindak'] == 'ODC') {
      $layanan = $koneksimaster->query("SELECT * FROM master_layanan WHERE nama_layanan = 'ODC'")->fetch_assoc();

      $koneksi->query("INSERT INTO `layanan`(`layanan`, `kode_layanan`, `harga`, `jumlah_layanan`, `id_pasien`, `idrm`, `tgl_layanan`) VALUES ('$layanan[nama_layanan]', '$layanan[id]', '$layanan[harga]', '1', '$id_pasien', '$no_rm', '$jadwal')");

      $koneksi->query("INSERT INTO biaya_rawat (idregis, poli, biaya_lain, total_lain, biaya_lab, periksa_lab, shift) VALUE ('$idrawat', '$poli', '+$layanan[nama_layanan]', '" . ($layanan['harga']) . "',  '$biayaLab', '$periksaLab', '$shift')");
    } else {
      $koneksi->query("INSERT INTO biaya_rawat (idregis, poli, biaya_lain, total_lain, biaya_lab, periksa_lab, shift) VALUE ('$idrawat', '$poli', '', '', '$biayaLab', '$periksaLab', '$shift')");
    }
  }

  echo "
      <script>  
          alert('Tindak Lanjut Berhasil Disimpan');
          document.location.href='index.php?halaman=daftarigd';
      </script>
  ";
  exit();
}
// End Handle POST Action

$whereClause = "";
$date_start = "2000-01-01";
$date_end = date('Y-m-d');
$key = "";
$urlPage = "index.php?halaman=daftarigd";
if (isset($_GET['src'])) {
  $urlPage = "index.php?halaman=daftarigd&src&date_start=" . $_GET['date_start'] . "&date_end=" . $_GET['date_end'] . "&key=" . $_GET['key'];
  if ($_GET['date_start'] != "" && $_GET['date_end'] != "") {
    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $whereClause .= " AND tgl_masuk BETWEEN '$date_start' AND '$date_end' ";
  }
  if ($_GET['key'] != "") {
    $key = $_GET['key'];
    $whereClause .= " AND (no_rm LIKE '%$key%' OR nama_pasien LIKE '%$key%' OR nama_pengantar LIKE '%$key%') ";
  }
}

$query = "SELECT * FROM igd WHERE 1=1 $whereClause ORDER BY idigd DESC ";

//   Pagination
// Parameters for pagination
$limit = 30; // Number of entries to show in a page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Get the total number of records
$result = $koneksi->query($query);
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
$pasien = $koneksi->query($query . " LIMIT $start, $limit;");
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
  <style>
    .hidden {
      display: hidden;
      max-height: 1px;
      overflow: hidden;
    }
  </style>
  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Select2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>


<body>
  <main>
    <div class="">
      <div class="pagetitle">
        <h1>Daftar IGD</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?halaman=daftarigd" style="color:blue;">IGD</a></li>
          </ol>
        </nav>
      </div><!-- End Page Title -->

      <section class="section  py-4">
        <div class="">


          <div class="row">
            <div class="col-lg-12 col-md-12">
              <div class="card shadow p-2 mb-1">
                <form method="get">
                  <input type="text" name="halaman" value="daftarigd" hidden id="">
                  <div class="row g-1">
                    <div class="col-6">
                      <label for="" class="mb-0">Dari Tanggal:</label>
                      <input type="date" name="date_start" value="<?= $date_start ?>" class="form-control form-control-sm mb-2" required>
                    </div>
                    <div class="col-6">
                      <label for="" class="mb-0">Sampai Tanggal:</label>
                      <input type="date" name="date_end" value="<?= $date_end ?>" class="form-control form-control-sm mb-2" required>
                    </div>
                    <div class="col-10">
                      <input type="text" name="key" class="form-control form-control-sm" placeholder="Cari..." value="<?= $key ?>">
                    </div>
                    <div class="col-2">
                      <button name="src" class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>
                    </div>
                  </div>
                </form>
              </div>
              <div class="card shadow p-2">
                <div class="card-body">

                  <h5 class="card-title">Data IGD</h5>

                  <!-- Multi Columns Form -->
                  <div class="table-responsive">
                    <table class="table table-striped" style="width:100%; font-size: 12px;">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>No RM</th>
                          <th>Nama Pasien</th>
                          <th>Nama Pengantar</th>
                          <th>Tanggal Masuk</th>
                          <th>Jam Masuk</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $no = 1 ?>
                        <?php foreach ($pasien as $pecah) : ?>
                          <tr>
                            <td><?php echo $no; ?></td>
                            <td style="margin-top:10px;"><?php echo $pecah["no_rm"]; ?></td>
                            <td style="margin-top:10px;">
                              <?php echo $pecah["nama_pasien"]; ?><br>
                              <span class="badge bg-<?= $pecah['transportasi'] != '' ? 'success' : 'danger' ?>">Perawat</span>
                              <span class="badge bg-<?= $pecah['dokter'] != '' ? 'success' : 'danger' ?>">Dokter</span>
                            </td>
                            <td style="margin-top:10px;"><?php echo $pecah["nama_pengantar"]; ?></td>
                            <td style="margin-top:10px;"><?php echo $pecah["tgl_masuk"]; ?></td>
                            <td style="margin-top:10px;"><?php echo $pecah["jam_masuk"]; ?></td>
                            <td>
                              <?php if ($_SESSION['admin']['level'] == 'sup' or $_SESSION['admin']['level'] == 'dokter' or $_SESSION['admin']['level'] == 'igd') { ?>
                                <div class="dropdown">
                                  <i data-bs-toggle="dropdown" style="color: blue; font-weight: bold; font-size: 20px;" class="bi bi-three-dots-vertical"></i>
                                  <ul class="dropdown-menu">
                                    <li><a href="index.php?halaman=detailigd&id=<?php echo $pecah["idigd"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-eye-fill" style="color:darkblue;"></i> Detail</a></li>
                                    <li><a href="index.php?halaman=ubahigd&id=<?php echo $pecah["idigd"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-hospital" style="color:black;"></i> Kajian IGD</a></li>
                                    <li><span data-bs-toggle="modal" data-bs-target="#tindaklanjut" onclick="openModal('<?= $pecah['idigd'] ?>')" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-clipboard2-pulse-fill" style="color:black;"></i> Tindak Lanjut</span></li>
                                    <li><a href="?halaman=lpo&igd&id=<?= $pecah['no_rm'] ?>&idigd=<?= $pecah['idigd'] ?>&tgl=<?= $pecah['tgl_masuk'] ?>&insertObatDokterIgd" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-capsule" style="color:black;"></i> Planing (Obat)</a></li>
                                    <li><a href="index.php?halaman=lpo&igd&id=<?php echo $pecah["no_rm"] ?>&idigd=<?php echo $pecah["idigd"] ?>&tgl=<?php echo $pecah["tgl_masuk"] ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-file-earmark-spreadsheet" style="color:orange;"></i> Observasi Perawat</a></li>
                                    <li><a href="index.php?halaman=rujuklab2&id=<?php echo $pecah["idigd"]; ?>&rm=<?php echo $pecah["no_rm"] ?>&igd&tgl=<?php echo $pecah["tgl_masuk"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-flask" style="color:orange;"></i> Rujuk Lab</a></li>
                                    <li><a href="../pasien/gen_con.php?id=<?php echo $pecah["no_rm"]; ?>&igd" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-printer" style="color:black;"></i> General Consent</a></li>
                                    <li><a href="index.php?halaman=falanak&id=<?php echo $pecah["idigd"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-file" style="color:blueviolet;"></i> Fallrisk Pediatri (Anak)</a></li>
                                    <li><a href="index.php?halaman=faldewasa&id=<?php echo $pecah["idigd"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-file" style="color:blueviolet;"></i> Fallrisk (Dewasa)</a></li>
                                    <li><a href="index.php?halaman=ivl&id=<?php echo $pecah["no_rm"] ?>&igd" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-bandaid-fill" style="color:brown;"></i> IVL</a></li>
                                    <li><a href="index.php?halaman=hapusigd&id=<?php echo $pecah["idigd"]; ?>" class="dropdown-item" style="text-decoration: none; font-weight: bold; margin-left: 2px;" onclick="return confirm('Anda yakin mau menghapus item ini ?')">
                                        <i class="bi bi-trash" style="color:red;"></i> Hapus</a></li>
                                  </ul>
                                </div>
                              <?php } ?>
                            </td>
                          </tr>
                          <?php $no += 1 ?>
                        <?php endforeach ?>
                      </tbody>
                    </table>
                  </div>
                  <div class="modal fade" id="tindaklanjut" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h1 class="modal-title fs-5" id="staticBackdropLabel">Tindak Lanjut Pasien</h1>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST">
                          <div class="modal-body">
                            <input type="text" name="idIGD" hidden id="idIGD">
                            <div class="col-md-12 ">
                              <label for="inputName5" class="form-label">Tindak Lanjut</label>
                              <select id="pilRujuk" name="tindak" class="form-select">
                                <option value="" hidden>Pilih</option>
                                <option value="Pulang">1.Pulang</option>
                                <option value="Pulang Paksa">2. Pulang Paksa</option>
                                <option value="Rawat">3.Rawat</option>
                                <option value="Meninggal">4.Meninggal</option>
                                <option value="Rujuk">5.Rujuk</option>
                                <option value="ODC">6.ODC</option>
                              </select>
                            </div>
                            <div class="hidden mt-2" id="rjk">
                              <div class="row">
                                <div class="col-md-12">
                                  <input type="text" placeholder="Tujuan Rujukan" class="form-control mb-2" name="tindak_rujuk">
                                </div>
                                <div class="col-md-12">
                                  <input type="text" placeholder="Keterangan/Alasan Rujuk" class="form-control" name="tindak_rujuk_keterangan">
                                </div>
                              </div>
                            </div>

                            <div class="hidden mt-2" id="kamar">
                              <div class="row">
                                <div class="col-md-12">
                                  <label for="inputState" class="form-label">Kamar / Ruangan</label>
                                  <!-- <input type="text" class="form-control" value="" name="kamar"> -->
                                  <select name="kamar" class="form-select" id="">
                                    <option hidden value="">Pilih Kamar</option>
                                    <?php
                                    $getKamar = $koneksi->query("SELECT * FROM kamar");
                                    foreach ($getKamar as $kamar) {
                                    ?>
                                      <?php $cekKamar = $koneksi->query("SELECT * FROM registrasi_rawat WHERE kamar = '$kamar[namakamar]' ORDER BY idrawat DESC LIMIT 1"); ?>
                                      <?php if ($cekKamar->fetch_assoc()['status_antri'] == 'Pulang' or $cekKamar->num_rows == 0) { ?>
                                        <option value="<?= $kamar['namakamar'] ?>"><?= $kamar['namakamar'] ?></option>
                                      <?php } ?>
                                    <?php } ?>
                                  </select>
                                </div>
                              </div>
                              <div class="row g-1">
                                <div class="col-12">
                                  <label for="inputName5" class="form-label mt-2">Tag Nama Teman</label>
                                  <select name="teman[]" class="form-select form-select-sm" id="selectTeman" multiple="multiple" style="width: 100%;">
                                    <?php
                                    $getUser = $koneksi->query("SELECT * FROM admin WHERE level = 'inap' OR level = 'igd' OR level = 'perawat'");
                                    $currentUser = $_SESSION['admin']['namalengkap'];
                                    foreach ($getUser as $user) {
                                      $selected = ($user['namalengkap'] == $currentUser and $user['level'] == $_SESSION['admin']['level']) ? 'selected' : '';
                                    ?>
                                      <option value="<?= $user['namalengkap'] ?>" <?= $selected ?>><?= $user['namalengkap'] ?> (<?= $user['level'] ?>)</option>
                                    <?php } ?>
                                  </select>
                                </div>
                              </div>
                              <script>
                                $(document).ready(function() {
                                  $('#selectTeman').select2({
                                    dropdownParent: $('#tindaklanjut'),
                                    placeholder: 'Pilih Nama Teman',
                                    allowClear: true,
                                  });
                                });
                              </script>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="saveTindakLanjut" class="btn btn-primary">Simpan</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  <script>
                    function openModal(id) {
                      document.getElementById('idIGD').value = id;
                    }

                    document.getElementById('pilRujuk').addEventListener('change', function() {
                      var formLain = document.getElementById('rjk');
                      var formLain2 = document.getElementById('kamar');
                      if (this.value === 'Rujuk') {
                        formLain.classList.remove('hidden');
                      } else if (this.value === 'Rawat') {
                        formLain2.classList.remove('hidden');
                        // formLain2.setAttribute('required', 'true');
                      } else if (this.value != 'Rujuk') {
                        formLain.classList.add('hidden');
                      } else if (this.value != 'Rawat') {
                        formLain2.classList.add('hidden');
                      }
                    });
                  </script>
                  <br>
                  <?php
                  // Display pagination
                  echo '<nav>';
                  echo '<ul class="pagination justify-content-center">';

                  // Back button
                  if ($page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . ($page - 1) . '">Back</a></li>';
                  }

                  // Determine the start and end page
                  $start_page = max(1, $page - 2);
                  $end_page = min($total_pages, $page + 2);

                  if ($start_page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=1">1</a></li>';
                    if ($start_page > 2) {
                      echo '<li class="page-item"><span class="page-link">...</span></li>';
                    }
                  }

                  for ($i = $start_page; $i <= $end_page; $i++) {
                    if ($i == $page) {
                      echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
                    } else {
                      echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . $i . '">' . $i . '</a></li>';
                    }
                  }

                  if ($end_page < $total_pages) {
                    if ($end_page < $total_pages - 1) {
                      echo '<li class="page-item"><span class="page-link">...</span></li>';
                    }
                    echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . $total_pages . '">' . $total_pages . '</a></li>';
                  }

                  // Next button
                  if ($page < $total_pages) {
                    echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . ($page + 1) . '">Next</a></li>';
                  }

                  echo '</ul>';
                  echo '</nav>';
                  ?>
                  <br>
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
<script>
  $(document).ready(function() {
    $('#myTable').DataTable({
      search: true,
      order: [0, 'desc'],
      pagination: true
    });
  });
</script>