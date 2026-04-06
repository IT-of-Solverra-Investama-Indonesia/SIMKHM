<?php
$date = date("Y-m-d");
date_default_timezone_set('Asia/Jakarta');
$username = $_SESSION['admin']['username'];
$petugas = $_SESSION['admin']['namalengkap'];
$ambil = $koneksi->query("SELECT * FROM admin  WHERE username='$username';");
$pasien = $koneksi->query("SELECT * FROM pasien  WHERE no_rm='$_GET[id]';")->fetch_assoc();

$id = $koneksi->query("SELECT * FROM registrasi_rawat  WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' AND perawatan = 'Rawat Inap' ORDER BY idrawat DESC LIMIT 1")->fetch_assoc();

$jadwal = $koneksi->query("SELECT * FROM registrasi_rawat  WHERE no_rm='$_GET[id]' AND date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' ")->fetch_assoc();


function getFullUrl()
{
  $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ||
    $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

  return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

if (isset($_POST['tagTeman'])) {
  $temanPerawatIds = $_POST['temanPerawat']; // Array of selected nurse IDs
  $getLastCtt = $koneksi->query("SELECT *, COUNT(id) as total FROM ctt_penyakit_inap WHERE norm='$_GET[id]' AND id = '$_POST[id]' order by id DESC LIMIT 1")->fetch_assoc();

  if ($getLastCtt['total'] == 0) {
    echo "
      <script>
        alert('Tidak ada catatan perkembangan penyakit untuk di tag');
        document.location.href='index.php?halaman=cttpenyakit&id=$_GET[id]&inap&tgl=$_GET[tgl]';
      </script>
    ";
    exit();
  }

  // Loop through each selected nurse and insert separately
  foreach ($temanPerawatIds as $temanPerawatId) {
    $getTeman = $koneksi->query("SELECT * FROM admin WHERE idadmin = '$temanPerawatId'")->fetch_assoc();
    $petugasName = $getTeman['namalengkap'];

    $koneksi->query("INSERT INTO ctt_penyakit_inap(tgl, norm, ctt_dokter, ctt_tedis, petugas, kamar, pasien, ctt_penyakit_inap.object, alergi, assesment, plan, intruksi, edukasi, dokter) VALUES ('$getLastCtt[tgl]', '$_GET[id]','$getLastCtt[ctt_dokter]', '" . $koneksi->real_escape_string($getLastCtt['ctt_tedis']) . "', '$petugasName', '$getLastCtt[kamar]', '$getLastCtt[pasien]', '" . $koneksi->real_escape_string($getLastCtt['object']) . "', '" . $koneksi->real_escape_string($getLastCtt['alergi']) . "', '" . $koneksi->real_escape_string($getLastCtt['assesment']) . "', '" . $koneksi->real_escape_string($getLastCtt['plan']) . "', '" . $koneksi->real_escape_string($getLastCtt['intruksi']) . "', '" . $koneksi->real_escape_string($getLastCtt['edukasi']) . "','" . $_SESSION['dokter_rawat'] . "')");
  }
  echo "
    <script>
      alert('Berhasil menandai teman perawat pada catatan perkembangan penyakit.');
      document.location.href='index.php?halaman=cttpenyakit&id=$_GET[id]&inap&tgl=$_GET[tgl]';
    </script>
  ";
  exit();
}

if (isset($_GET['delete'])) {
  // $koneksi->query("DELETE FROM ctt_penyakit_inap WHERE id='$_GET[ctt]'");
  $getCttToDelete = $koneksi->query("SELECT * FROM ctt_penyakit_inap WHERE id='$_GET[ctt]'")->fetch_assoc();
  $koneksi->query("DELETE FROM ctt_penyakit_inap WHERE norm='$getCttToDelete[norm]' AND tgl='$getCttToDelete[tgl]'");
  echo "
    <script>
      alert('Berhasil menghapus catatan perkembangan penyakit.');
      document.location.href='index.php?halaman=cttpenyakit&id=$_GET[id]&inap&tgl=$_GET[tgl]';
    </script>
  ";
  exit();
}



function getUniqeIdObat($koneksi)
{
  $newId = $koneksi->query("SELECT * FROM obat_rm ORDER BY idobat DESC LIMIT 1")->fetch_assoc()['idobat'] + 1;
  while ($koneksi->query("SELECT COUNT(*) FROM obat_rm WHERE idobat = $newId")->fetch_row()[0] > 0) {
    $newId++;
  }
  return $newId;
}

function getUniqeIdObatRequest($koneksi)
{
  $newId = $koneksi->query("SELECT * FROM obat_rm_request ORDER BY idobat DESC LIMIT 1")->fetch_assoc()['idobat'] + 1;
  while ($koneksi->query("SELECT COUNT(*) FROM obat_rm_request WHERE idobat = $newId")->fetch_row()[0] > 0) {
    $newId++;
  }
  return $newId;
}

function getUniqeIdObatRetur($koneksi)
{
  $newId = $koneksi->query("SELECT * FROM retur_obat_inap ORDER BY idretur DESC LIMIT 1")->fetch_assoc()['idretur'] + 1;
  while ($koneksi->query("SELECT COUNT(*) FROM retur_obat_inap WHERE idretur = $newId")->fetch_row()[0] > 0) {
    $newId++;
  }
  return $newId;
}

function getLastWord($inputString)
{
  // Trim the input string to remove any leading or trailing whitespace
  $trimmedString = trim($inputString);

  // Check if the trimmed string is empty
  if (empty($trimmedString)) {
    return "The input string is empty.";
  }

  // Split the string into an array of words using space as the delimiter
  $wordsArray = explode(' ', $trimmedString);

  // Count the number of words in the array
  $wordCount = count($wordsArray);

  // Check if the string contains exactly three words
  if ($wordCount !== 3) {
    return "The input string does not contain exactly three words.";
  }

  // Get the last word from the array
  $lastWord = $wordsArray[$wordCount - 1];

  // Return the last word
  return $lastWord;
}

if (isset($_POST['requestObat'])) {
  $idrawat = $_POST['idrawat'];
  $getSingle = $koneksi->query("SELECT * FROM registrasi_rawat WHERE idrawat = '$idrawat'")->fetch_assoc();
  $koneksi->query("INSERT INTO `obat_request`(`request_obat`, `idrawat`, `nama_pasien`, `no_rm`, `jadwal`, `apotek_at`, `petugas`) VALUES ('" . $koneksi->real_escape_string($_POST['request']) . "','$idrawat','$getSingle[nama_pasien]','$getSingle[no_rm]','$getSingle[jadwal]', NULL, '" . $_SESSION['admin']['namalengkap'] . "')");

  echo "
    <script>
      alert('Obat berhasil di request, silahkan tunggu konfirmasi dari apotek!');
      window.location.href = '?halaman=cttpenyakit&id=$_GET[id]&inap&tgl=$_GET[tgl]';
    </script>
  ";
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">


</head>

<body>
  <?php
  if (isset($_GET['apotek']) && $_GET['apotek'] == 'done') {
    $koneksi->query("UPDATE registrasi_rawat SET apoteker_check_at = '" . date('Y-m-d H:i:s') . "' WHERE no_rm = '$_GET[id]' AND date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' AND perawatan = 'Rawat Inap' ORDER BY idrawat DESC LIMIT 1");
    echo "<script>alert('Proses Selesai, Pasien Boleh Pulang!');</script>";
    echo "<script>location='index.php?halaman=cttpenyakit&id=$_GET[id]&inap&tgl=$_GET[tgl]';</script>";
    exit();
  }
  ?>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', async () => {
      try {
        <?php
        $apiUrlgetObat = '../apotek/api_getObatMasterLokal.php';
        // if (isset($_GET['inap'])) {
        //   $apiUrlgetObat .= '?inap';
        // } elseif (isset($_GET['penjualan'])) {
        //   $apiUrlgetObat .= '?penjualan';
        // } else {
        //   $apiUrlgetObat .= '';
        // }
        ?>
        const obatData = await (await fetch('<?= $apiUrlgetObat . '?inap' ?>')).json();

        document.querySelectorAll('.obat-select').forEach(select => {
          // Simpan nilai yang sedang dipilih (jika ada)
          const selectedValue = select.value;

          // Buat array dari nilai option yang sudah ada
          const existingOptions = Array.from(select.options).map(opt => opt.value);

          // Filter data obat untuk hanya menambahkan yang belum ada
          const newOptions = obatData.filter(obat =>
            !existingOptions.includes(obat.kode_obat)
          );

          // Tambahkan option baru
          newOptions.forEach(obat => {
            select.add(new Option(obat.nama_obat, obat.kode_obat));
          });

          // Kembalikan nilai yang dipilih sebelumnya (jika masih ada)
          if (selectedValue && select.querySelector(`option[value="${selectedValue}"]`)) {
            select.value = selectedValue;
          }
        });
      } catch (error) {
        console.error('Error:', error);
      }
    });
  </script>
  <main>
    <div class="container">
      <div class="pagetitle">
        <h1>CATATAN PERKEMBANGAN PENYAKIT TERINTEGRASI RI</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="index.php?halaman=daftarrmedis" style="color:blue;">Rekam Medis</a></li>
            <li class="breadcrumb-item">Perkembangan Penyakit</li>
          </ol>
        </nav>
      </div>
    </div>
    <form class="row g-3" method="post" enctype="multipart/form-data">
      <div class="">
        <!-- End Page Title -->
        <!-- <?= $start ?> -->
        <div class="">
          <div class="row">
            <div class="col-md-12">
              <div class="card mb-1" style="margin-top:10px">
                <div class="card-body col-md-12">
                  <h5 class="card-title">Data Pasien</h5>
                  <!-- Multi Columns Form -->
                  <div class="row">
                    <div class="col-md-6">
                      <label>Nama Pasien</label>
                      <input type="text" class="form-control mb-2" name="pasien" id="inputName5" value="<?php echo $pasien['nama_lengkap'] ?>" placeholder="Masukkan Nama Pasien" readonly>
                    </div>
                    <div class="col-md-6">
                      <label>No RM</label>
                      <input type="text" class="form-control mb-2" id="inputName5" name="jadwal" value="<?php echo $pasien['no_rm'] ?>" placeholder="Masukkan Nama Pasien" readonly>
                    </div>
                    <div class="col-md-6">
                      <label>Tanggal Lahir</label>
                      <input type="text" class="form-control mb-2" id="inputName5" name="jadwal" value="<?php echo date("d-m-Y", strtotime($pasien['tgl_lahir'])) ?>" placeholder="Masukkan Nama Pasien" readonly>
                    </div>
                    <div class="col-md-6">
                      <label>Alamat</label>
                      <input type="text" class="form-control mb-2" id="inputName5" name="jadwal" value="<?php echo $pasien['alamat'] ?>" placeholder="Masukkan Nama Pasien" readonly>
                    </div>
                    <div class="col-md-6">
                      <label>Ruangan</label>
                      <input type="text" class="form-control mb-2" id="inputName5" name="kamar" value="<?php echo $jadwal['kamar'] ?>" placeholder="Masukkan Nama Pasien">
                    </div>
                    <?php if ($pasien["jenis_kelamin"] == 1) { ?>
                      <div class="col-md-6">
                        <label>Jenis Kelamin</label>
                        <input type="text" class="form-control mb-2" id="inputName5" name="jadwal" value="Laki-laki" placeholder="Masukkan Nama Pasien" readonly>
                      </div>
                    <?php } else { ?>
                      <div class="col-md-6">
                        <label>Jenis Kelamin</label>
                        <input type="text" class="form-control mb-2" id="inputName5" name="jadwal" value="Perempuan" placeholder="Masukkan Nama Pasien" readonly>
                      </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <?php if (!isset($_GET['entriObat'])) { ?>
                <?php if ($_SESSION['admin']['level'] != 'racik') { ?>
                  <div class="card mb-1">
                    <div class="card-body">
                      <div style="margin-bottom:1px; margin-top:30px" id="editorZone">
                        <h6 class="card-title">Hasil Pemeriksaan, Analisa & Rencana Penatalaksanaan Pasien </h6>
                      </div>
                      <?php
                      if (isset($_GET['ctt'])) {
                        $getDataCopy = $koneksi->query("SELECT * FROM ctt_penyakit_inap WHERE id = '$_GET[ctt]'")->fetch_assoc();
                      }
                      if (isset($_GET['ubah'])) {
                        $getDataCopy = $koneksi->query("SELECT * FROM ctt_penyakit_inap WHERE id = '$_GET[idcct]'")->fetch_assoc();
                      }
                      ?>
                      <p>(Instruksi ditulis dengan rinci dan jelas)</p>
                      <!-- Multi Columns Form -->
                      <div class="row">
                        <div class="col-md-12">
                          <label>Tgl & Jam</label>
                          <input type="datetime-local" class="form-control" id="inputName5" name="tgl" value="<?= date("Y-m-d H:i:s") ?>">
                        </div>
                        <div class="col-md-6" style="margin-top:20px;">
                          <label>Subject</label>
                          <textarea name="ctt_tedis" id="editor" style="width:100%; height:150px">
                              <?php if (isset($_GET['ctt']) or isset($_GET['ubah'])) { ?>
                                <?= $getDataCopy['ctt_tedis'] ?>
                              <?php } ?>
                          </textarea>

                        </div>
                        <div class="col-md-6" style="margin-top:20px;">
                          <label>Object</label>
                          <textarea name="object" id="editor2" style="width:100%; height:150px">
                              <?php if (isset($_GET['ctt']) or isset($_GET['ubah'])) { ?>
                                <?= $getDataCopy['object'] ?>
                              <?php } ?>
                          </textarea>
                        </div>
                        <div class="col-md-6" style="margin-top:20px;">
                          <label for="">Alergi</label>
                          <input type="text" name="alergi" id="editor7" class="form-control" value="<?php if (isset($_GET['ctt']) or isset($_GET['ubah'])) {
                                                                                                      echo $getDataCopy['alergi'];
                                                                                                    } ?>" placeholder="Alergi Obat" style="width:100%; height:50px">
                        </div>
                        <div class="col-md-6" style="margin-top:20px;">
                          <label for="">Assesment</label>
                          <textarea name="assesment" id="editor3" style="width:100%; height:150px">
                            <?php if (isset($_GET['ctt']) or isset($_GET['ubah'])) { ?>
                              <?= $getDataCopy['assesment'] ?>
                            <?php } ?>
                          </textarea>
                        </div>
                        <div class="col-md-6" style="margin-top:20px;">
                          <label for="">Plan</label>
                          <textarea name="plan" id="editor4" style="width:100%; height:150px">
                            <?php if (isset($_GET['ctt']) or isset($_GET['ubah'])) { ?>
                              <?= $getDataCopy['plan'] ?>
                            <?php } ?>
                          </textarea>
                        </div>
                        <div class="col-md-6" style="margin-top:20px;">
                          <label for="">Intruksi</label>
                          <textarea name="intruksi" id="editor5" style="width:100%; height:150px">
                            <?php if (isset($_GET['ctt']) or isset($_GET['ubah'])) { ?>
                              <?= $getDataCopy['intruksi'] ?>
                            <?php } ?>
                          </textarea>
                        </div>
                        <div class="col-md-6" style="margin-top:20px;">
                          <label for="">Edukasi</label>
                          <textarea name="edukasi" id="editor6" style="width:100%; height:150px">
                            <?php if (isset($_GET['ctt']) or isset($_GET['ubah'])) { ?>
                              <?= $getDataCopy['edukasi'] ?>
                            <?php } ?>
                          </textarea>
                        </div>
                        <div class="col-md-12" style="margin-top:20px;">
                          <label for="inputName5" class="form-label">Petugas</label>
                          <input name="petugas" id="" class="form-control" value="<?php echo $petugas ?>" disabled></input>
                        </div>
                      </div>
                    </div>
                    <div class="text-center" style="margin-top: -10px; margin-bottom: 40px;">
                      <?php if (isset($_GET['ubah'])) { ?>
                        <button type="submit" name="update" class="btn btn-success">Update</button>
                      <?php } else { ?>
                        <button type="submit" name="save" class="btn btn-primary">Simpan</button>
                      <?php } ?>
                      <button type="reset" class="btn btn-secondary">Reset</button>
                      <!-- <a href="?halaman=cttpenyakit_obat&id=<?= $_GET['id'] ?>&inap&tgl=<?= $_GET['tgl'] ?>" class="btn btn-success"><?= $_SESSION['admin']['level'] == 'dokter' ? 'Ajukan Obat' : 'Cek Obat' ?></a> -->
                    </div>
                  </div>
                <?php } ?>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </form>
    <div class="">
      <?php if (!isset($_GET['entriObat'])) { ?>
        <div class="row">
          <div class="col-md-12 <?= isset($_GET['bulan']) ? 'd-none' : '' ?>">
            <div class="card mb-1" style="margin-top:0px">
              <div class="card-body col-md-12">
                <h5 class="card-title">DATA CATATAN PERKEMBANGAN PENYAKIT TERINTEGRASI RAWAT INAP SAAT INI</h5>
                <!-- Modal Tag Teman Perawat -->
                <!-- <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#tagTeman">@ Tag Teman Perawat</button> -->
                <div class="modal fade" id="tagTeman" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Tag Teman Perawat</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <form method="post">
                        <div class="modal-body">
                          <input type="text" name="id" id="id_id" hidden>
                          <label for="selectTemanPerawatCtt">Pilih Teman Perawat</label>
                          <select name="temanPerawat[]" class="form-control form-control-sm" id="selectTemanPerawatCtt" multiple="multiple" style="width: 100%;" required>
                            <?php
                            $getPerawat = $koneksi->query("SELECT * FROM admin WHERE level IN ('perawat', 'inap', 'igd') ORDER BY namalengkap ASC");
                            foreach ($getPerawat as $perawat) :
                            ?>
                              <option value="<?= $perawat['idadmin'] ?>"><?= $perawat['namalengkap'] ?> (<?= $perawat['level'] ?>)</option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                          <button type="submit" name="tagTeman" class="btn btn-sm btn-primary">Tag Teman Perawat</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <script>
                  function upDataId(id) {
                    document.getElementById('id_id').value = id;
                  }
                </script>
                <br>
                <!-- End Modal Tag Teman Perawat -->
                <div class="table-responsive">
                  <table id="myTable" class="table table-striped" style="width:100%; font-size: 12px;">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Tipe</th>
                        <th>Tgl&Jam</th>
                        <th>Subjek</th>
                        <th>Objek</th>
                        <th>Alergi</th>
                        <th>Assesment</th>
                        <th>Plan</th>
                        <th>Intruksi</th>
                        <th>Edukasi</th>
                        <th>Petugas</th>
                        <th>Dokter</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $getPulangTerakhir = $koneksi->query("SELECT * FROM pulang WHERE norm='$_GET[id]' ORDER BY id DESC LIMIT 1")->fetch_assoc();
                      $getIGD = $koneksi->query("SELECT * FROM igd WHERE no_rm = '$_GET[id]' AND tindak = 'Rawat' AND tgl_masuk <= '$_GET[tgl]' ORDER BY idigd DESC LIMIT 1")->fetch_assoc();
                      $no = 1;
                      $riw = $koneksi->query("
                        SELECT 
                          id,
                          tgl,
                          ctt_tedis,
                          object,
                          alergi,
                          assesment,
                          plan,
                          intruksi,
                          edukasi,
                          petugas,
                          dokter,
                          norm,
                          'Catatan Penyakit' as tipe_data
                        FROM ctt_penyakit_inap 
                        WHERE norm='$_GET[id]' 
                          AND DATE_FORMAT(tgl, '%Y-%m-%d') > '" . ($getPulangTerakhir['tgl'] ?? date('Y-m-d', strtotime('2000-01-01'))) . "' GROUP BY tgl
                        
                        UNION ALL
                        
                        SELECT 
                          NULL as id,
                          created_at as tgl,
                          catatan as ctt_tedis,
                          '' as object,
                          '' as alergi,
                          '' as assesment,
                          '' as plan,
                          '' as intruksi,
                          '' as edukasi,
                          created_by as petugas,
                          '' as dokter,
                          no_rm as norm,
                          'Catatan Farmasi' as tipe_data
                        FROM catatan_farmasi 
                        WHERE idrawat_registrasi = '" . htmlspecialchars($id['idrawat']) . "' AND DATE_FORMAT(created_at, '%Y-%m-%d') > '" . ($getPulangTerakhir['tgl'] ?? date('Y-m-d', strtotime('2000-01-01'))) . "' 
                        
                        UNION ALL
                              
                        SELECT 
                          NULL as id,
                          created_at as tgl,
                          catatan as ctt_tedis,
                          '' as object,
                          '' as alergi,
                          '' as assesment,
                          '' as plan,
                          '' as intruksi,
                          '' as edukasi,
                          created_by as petugas,
                          '' as dokter,
                          no_rm as norm,
                          'Catatan Lab' as tipe_data
                        FROM catatan_lab
                        WHERE idrawat_registrasi = '" . htmlspecialchars($id['idrawat']) . "' AND DATE_FORMAT(created_at, '%Y-%m-%d') > '" . ($getPulangTerakhir['tgl'] ?? date('Y-m-d', strtotime('2000-01-01'))) . "'

                        UNION ALL

                        SELECT 
                          NULL as id,
                          tgl_waktu as tgl,
                          keadaan_umum as ctt_tedis,
                          keluhan_pasien as object,
                          '' as alergi,
                          diagnosa as assesment,
                          tindakan as plan,
                          '' as intruksi,
                          '' as edukasi,
                          perawat as petugas,
                          '' as dokter,
                          norm as norm,
                          'Observasi Perawat' as tipe_data
                        FROM lpo
                        WHERE norm = '$_GET[id]' AND DATE_FORMAT(tgl_waktu, '%Y-%m-%d') > '" . ($getPulangTerakhir['tgl'] ?? date('Y-m-d', strtotime('2000-01-01'))) . "'

                        UNION ALL
                        
                        SELECT
                          NULL as id,
                          tgl as tgl,
                          keluhan as ctt_tedis,
                          '' as object,
                          riw_alergi as alergi,
                          dkerja as assesment,
                          rencana_rawat as plan,
                          '' as intruksi,
                          '' as edukasi,
                          perawat as petugas,
                          dokter as dokter,
                          no_rm as norm,
                          'Catatan IGD' as tipe_data
                        FROM igd
                        WHERE idigd = '$getIGD[idigd]'

                        ORDER BY tgl DESC
                      ");
                      ?>
                      <?php foreach ($riw as $pecah) : ?>
                        <tr>
                          <td><?php echo $no; ?></td>
                          <td>
                            <?php if ($pecah['tipe_data'] == 'Catatan Farmasi') { ?>
                              <span class="badge bg-info text-light">Farmasi</span>
                            <?php } else if ($pecah['tipe_data'] == 'Catatan Lab') { ?>
                              <span class="badge bg-secondary text-light">Laboratorium</span>
                            <?php } else if ($pecah['tipe_data'] == 'Catatan IGD') { ?>
                              <span class="badge bg-warning text-dark">IGD</span>
                            <?php } else if ($pecah['tipe_data'] == 'Observasi Perawat') { ?>
                              <span class="badge bg-success text-light">Observasi Perawat</span>
                            <?php } else { ?>
                              <span class="badge bg-primary text-light">Penyakit</span>
                            <?php } ?>
                          </td>
                          <td>
                            <?php echo $pecah["tgl"]; ?>
                            <?php if ($pecah['tipe_data'] == 'Catatan Penyakit') { ?>
                              <?php
                              $getTagCount = $koneksi->query("SELECT * FROM ctt_penyakit_inap WHERE norm='$_GET[id]' AND tgl='$pecah[tgl]'");
                              foreach ($getTagCount as $tag) {
                                echo "<br><span class='badge bg-primary' style='font-size: 10px;'>@" . $tag['petugas'] . "</span>";
                              }
                              ?>
                            <?php } ?>
                          </td>
                          <td><?php echo $pecah["ctt_tedis"]; ?> </td>
                          <td>
                            <?php if ($pecah['tipe_data'] == 'Catatan IGD') { ?>
                              <!-- Button trigger modal -->
                              <span class="badge bg-primary" data-bs-toggle="modal" data-bs-target="#showIGD">
                                <i class="bi bi-eye"></i> Lihat Detail IGD
                              </span>

                              <!-- Modal -->
                              <div class="modal fade" id="showIGD" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                      <iframe src="index.php?halaman=detailigd&id=<?= $getIGD['idigd'] ?>" frameborder="0" width="100%" height="500px"></iframe>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            <?php } ?>
                            <?php echo $pecah["object"]; ?>

                          </td>
                          <td><?php echo $pecah["alergi"]; ?> </td>
                          <td><?php echo $pecah["assesment"]; ?> </td>
                          <td><?php echo $pecah["plan"]; ?> </td>
                          <td><?php echo $pecah["intruksi"]; ?> </td>
                          <td><?php echo $pecah["edukasi"]; ?> </td>
                          <td><?php echo $pecah["petugas"]; ?></td>
                          <td><?php echo $pecah["dokter"]; ?></td>
                          <td>
                            <?php if ($pecah['tipe_data'] == 'Catatan Penyakit') { ?>
                              <span class="badge bg-primary my-1" style="font-size: 12px;" data-bs-toggle="modal" onclick="upDataId('<?= $pecah['id'] ?>')" data-bs-target="#tagTeman">@ Tag</span>
                              <a href="<?= getFullUrl(); ?>&idcct=<?= $pecah['id'] ?>&ubah" class="badge bg-success my-1" style="font-size: 12px;">Edit</a>
                              <a href="<?= getFullUrl(); ?>&ctt=<?= $pecah['id'] ?>#editorZone" class="badge bg-warning my-1" style="font-size: 12px;">Copy</a>
                              <a href="<?= getFullUrl(); ?>&ctt=<?= $pecah['id'] ?>&delete" onclick="return confirm('Teman teman yang anda tag pada catatan ini juga akan terhapus, apakah anda yakin ingin menghapus data ini ?')" class="badge bg-danger my-1" style="font-size: 12px;">Delete</a>
                            <?php } else { ?>
                              <span class="badge bg-secondary">-</span>
                            <?php } ?>
                          </td>
                        </tr>
                        <?php $no += 1 ?>
                      <?php endforeach ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12">
            <div class="card shadow p-2 mb-1">
              <h5 class="card-title">DATA CATATAN PERKEMBANGAN PENYAKIT TERINTEGRASI RAWAT INAP SEBELUMNYA <?= isset($_GET['bulan']) ? $_GET['bulan'] : 'ALL' ?></h5>
              <div class="table-responsive">
                <?php if (!isset($_GET['bulan'])) { ?>
                  <table class="table-hover table table-sm table-striped" style="font-size: 12px;">
                    <thead>
                      <tr>
                        <th>Bulan</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $no = 1;
                      $getBulanLalu = $koneksi->query("
                        SELECT DATE_FORMAT(tgl, '%Y-%m') as bulan FROM ctt_penyakit_inap WHERE norm='$_GET[id]'
                        UNION
                        SELECT DATE_FORMAT(created_at, '%Y-%m') as bulan FROM catatan_farmasi WHERE no_rm='$_GET[id]'
                        UNION
                        SELECT DATE_FORMAT(created_at, '%Y-%m') as bulan FROM catatan_lab WHERE no_rm='$_GET[id]'
                        ORDER BY bulan DESC
                      ");
                      foreach ($getBulanLalu as $bulanLalu):
                      ?>
                        <tr>
                          <td><?= $bulanLalu['bulan'] ?></td>
                          <td><a href="index.php?halaman=cttpenyakit&id=<?= $_GET['id'] ?>&inap&tgl=<?= $_GET['tgl'] ?>&bulan=<?= $bulanLalu['bulan'] ?>" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i></a></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                <?php } else { ?>
                  <table id="myTable" class="table table-striped" style="width:100%; font-size: 12px;">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Tipe</th>
                        <th>Tgl&Jam</th>
                        <th>Subjek</th>
                        <th>Objek</th>
                        <th>Alergi</th>
                        <th>Assesment</th>
                        <th>Plan</th>
                        <th>Intruksi</th>
                        <th>Edukasi</th>
                        <th>Petugas</th>
                        <th>Dokter</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $getPulangTerakhir['tgl'] = date('Y-m-d', strtotime($_GET['bulan'] . '-01')); // Set tanggal pulang terakhir ke kemarin agar semua data muncul
                      $no = 1;
                      $riw = $koneksi->query("
                        SELECT 
                          id,
                          tgl,
                          ctt_tedis,
                          object,
                          alergi,
                          assesment,
                          plan,
                          intruksi,
                          edukasi,
                          petugas,
                          dokter,
                          norm,
                          'Catatan Penyakit' as tipe_data
                        FROM ctt_penyakit_inap 
                        WHERE norm='$_GET[id]' 
                          AND DATE_FORMAT(tgl, '%Y-%m') = '" . $_GET['bulan'] . "'
                        
                        UNION ALL
                        
                        SELECT 
                          NULL as id,
                          created_at as tgl,
                          catatan as ctt_tedis,
                          '' as object,
                          '' as alergi,
                          '' as assesment,
                          '' as plan,
                          '' as intruksi,
                          '' as edukasi,
                          created_by as petugas,
                          '' as dokter,
                          no_rm as norm,
                          'Catatan Farmasi' as tipe_data
                        FROM catatan_farmasi 
                        WHERE no_rm='$_GET[id]'
                          AND DATE_FORMAT(created_at, '%Y-%m') = '" . $_GET['bulan'] . "'
                        
                        UNION ALL
                              
                        SELECT 
                          NULL as id,
                          created_at as tgl,
                          catatan as ctt_tedis,
                          '' as object,
                          '' as alergi,
                          '' as assesment,
                          '' as plan,
                          '' as intruksi,
                          '' as edukasi,
                          created_by as petugas,
                          '' as dokter,
                          no_rm as norm,
                          'Catatan Lab' as tipe_data
                        FROM catatan_lab
                        WHERE no_rm='$_GET[id]'
                          AND DATE_FORMAT(created_at, '%Y-%m') = '" . $_GET['bulan'] . "'

                        ORDER BY tgl DESC
                      ");
                      ?>
                      <?php foreach ($riw as $pecah) : ?>
                        <tr>
                          <td><?php echo $no; ?></td>
                          <td>
                            <?php if ($pecah['tipe_data'] == 'Catatan Farmasi') { ?>
                              <span class="badge bg-info text-light">Farmasi</span>
                            <?php } else if ($pecah['tipe_data'] == 'Catatan Lab') { ?>
                              <span class="badge bg-secondary text-light">Laboratorium</span>
                            <?php } else { ?>
                              <span class="badge bg-primary text-light">Penyakit</span>
                            <?php } ?>
                          </td>
                          <td>
                            <?php echo $pecah["tgl"]; ?>
                            <?php if ($pecah['tipe_data'] == 'Catatan Penyakit') { ?>
                              <?php
                              $getTagCount = $koneksi->query("SELECT * FROM ctt_penyakit_inap WHERE norm='$_GET[id]' AND tgl='$pecah[tgl]'");
                              foreach ($getTagCount as $tag) {
                                echo "<br><span class='badge bg-primary' style='font-size: 10px;'>@" . $tag['petugas'] . "</span>";
                              }
                              ?>
                            <?php } ?>
                          </td>
                          <td><?php echo $pecah["ctt_tedis"]; ?> </td>
                          <td><?php echo $pecah["object"]; ?> </td>
                          <td><?php echo $pecah["alergi"]; ?> </td>
                          <td><?php echo $pecah["assesment"]; ?> </td>
                          <td><?php echo $pecah["plan"]; ?> </td>
                          <td><?php echo $pecah["intruksi"]; ?> </td>
                          <td><?php echo $pecah["edukasi"]; ?> </td>
                          <td><?php echo $pecah["petugas"]; ?></td>
                          <td><?php echo $pecah["dokter"]; ?></td>
                          <td>
                            <?php if ($pecah['tipe_data'] == 'Catatan Penyakit') { ?>
                              <span class="badge bg-primary my-1" style="font-size: 12px;" data-bs-toggle="modal" onclick="upDataId('<?= $pecah['id'] ?>')" data-bs-target="#tagTeman">@ Tag</span>
                              <?php if ($pecah['petugas'] === $petugas || $_SESSION['admin']['level'] == 'dokter' || $_SESSION['admin']['level'] == 'sup') { ?>
                                <a href="<?= getFullUrl(); ?>&idcct=<?= $pecah['id'] ?>&ubah" class="badge bg-success my-1" style="font-size: 12px;">Edit</a>
                                <a href="<?= getFullUrl(); ?>&ctt=<?= $pecah['id'] ?>#editorZone" class="badge bg-warning my-1" style="font-size: 12px;">Copy</a>
                                <a href="<?= getFullUrl(); ?>&ctt=<?= $pecah['id'] ?>&delete" onclick="return confirm('Teman teman yang anda tag pada catatan ini juga akan terhapus, apakah anda yakin ingin menghapus data ini ?')" class="badge bg-danger my-1" style="font-size: 12px;">Delete</a>
                              <?php } ?>
                            <?php } else { ?>
                              <span class="badge bg-secondary">-</span>
                            <?php } ?>
                          </td>
                        </tr>
                        <?php $no += 1 ?>
                      <?php endforeach ?>
                    </tbody>
                  </table>
                <?php } ?>
              </div>
            </div>
          </div>

          <div class="col-12">
            <div class="card shadow p-2 mb-1">
              <h5 class="card-title">Riwayat Hasil Lab</h5>
              <div class="table-responsive">
                <table class="table table-striped table-hover table-sm" style="font-size: 12px;">
                  <thead>
                    <tr>
                      <th>Nama</th>
                      <th>NoRM</th>
                      <th>Tgl</th>
                      <th>TglPengisian</th>
                      <th>Pemeriksaan</th>
                      <!-- <th>Act</th> -->
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $norm = sani($_GET['id']);
                    $getRiwayat = $koneksi->query("SELECT * FROM lab_hasil WHERE norm = '$norm' GROUP BY tgl_hasil ORDER BY tgl_inap DESC");
                    foreach ($getRiwayat as $riwayat) {

                    ?>
                      <tr>
                        <td><?= $riwayat['pasien'] ?></td>
                        <td><?= $riwayat['norm'] ?></td>
                        <td><?= $riwayat['tgl_inap'] ?></td>
                        <td><?= $riwayat['tgl_hasil'] ?></td>
                        <td>
                          <?php if ($riwayat['id_inap'] != '0') { ?>
                            <a href="../lab/printlabinap.php?id=<?= $riwayat['id_inap'] ?>&tgl=<?= date('Y-m-d', strtotime($riwayat['tgl_inap'])) ?>" target="_blank" class="btn btn-sm btn-primary">
                              <i class="bi bi-eye"></i>
                            </a>


                          <?php } else if ($riwayat['id_lab_h'] != '0') { ?>
                            <a href="../lab/printlab.php?id=<?= $riwayat['id_lab_h'] ?>" target="_blank" class="btn btn-sm btn-primary">
                              <i class="bi bi-eye"></i>
                            </a>
                          <?php } else { ?>
                            <a href="../lab/printlabigd.php?id=<?= $riwayat['id_igd'] ?>&tgl=<?= date('Y-m-d', strtotime($riwayat['tgl_hasil'])) ?>" target="_blank" class="btn btn-sm btn-primary">
                              <i class="bi bi-eye"></i>
                            </a>
                          <?php } ?>
                        </td>
                        <!-- <td><?= $riwayat['pasien'] ?></td> -->
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <script>
            function changeJenis(jenisObat) {
              var jenis3 = document.getElementById('jenis3');
              var jenis2 = document.getElementById('jenis2');
              jenis3.value = jenisObat;
              jenis2.value = jenisObat;
            }
          </script>

          <div class="col-12">
            <div class="card shadow p-3 mb-1">
              <h5 class="card-title">FOTO DOKUMEN PENUNJANG</h5>
              <div class="d-flex flex-wrap gap-3">
                <?php
                $getlpo = $koneksi->query("SELECT * FROM lpo WHERE pasien = '$pasien[nama_lengkap]' AND norm = '$pasien[no_rm]' AND status = 'inap' AND (penunjang != '' OR penunjang != null OR penunjang != '[]')");
                $hasFoto = false;
                foreach ($getlpo as $data) {
                  if (!empty($data['penunjang'])) {
                    $foto_list = json_decode($data['penunjang'], true);
                    if (is_array($foto_list) && count($foto_list) > 0) {
                      $hasFoto = true;
                      foreach ($foto_list as $index => $foto) {
                        if (file_exists('../rawatinap/pemeriksaan_penunjang/' . $foto)) {
                          echo '<div class="position-relative" style="border: 2px solid #e0e0e0; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); flex-shrink: 0;">';
                          echo '<img src="../rawatinap/pemeriksaan_penunjang/' . htmlspecialchars($foto) . '" 
                                style="width: 120px; height: 120px; object-fit: cover; cursor: pointer; transition: transform 0.2s;" 
                                onclick="window.open(this.src, \'_blank\')" 
                                onmouseover="this.style.transform=\'scale(1.05)\'" 
                                onmouseout="this.style.transform=\'scale(1)\'"
                                title="Klik untuk memperbesar - ' . htmlspecialchars($data['tgl_waktu']) . '">';
                          echo '<div class="position-absolute top-0 end-0 m-1"><span class="badge bg-success" style="font-size: 9px;">' . date('d/m/y', strtotime($data['tgl_waktu'])) . '</span></div>';
                          echo '<div class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-50 text-white text-center py-1" style="font-size: 10px;">Foto ' . ($index + 1) . '</div>';
                          echo '</div>';
                        }
                      }
                    }
                  }
                }
                if (!$hasFoto) {
                  echo '<div class="alert alert-info mb-0 w-100" style="font-size: 13px;"><i class="bi bi-info-circle"></i> Tidak ada foto dokumen penunjang</div>';
                }
                ?>
              </div>
            </div>
          </div>

          <div class="col-md-12">
            <div class="card shadow p-2 mb-1">
              <label for="">Obat Injeksi </label>
              <div>
                <?php if ($_SESSION['admin']['level'] != 'dokter') { ?>
                  <!-- <button type="button" onclick="changeJenis('Injeksi')" class="btn btn-primary btn-sm text-right"
                    data-bs-toggle="modal" data-bs-target="#exampleModal45">Add Jadi</button> -->
                <?php } ?>
                <!-- <button type="button" onclick="changeJenis('Injeksi')" class="btn btn-primary btn-sm text-right"
                  data-bs-toggle="modal" data-bs-target="#exampleModal2">Add Racik</button> -->
                <?php if ($id['apoteker_check_at'] == null) { ?>
                <?php } ?>
              </div>
              <div>
                <?php
                $getLpo = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND tgl_pasien='$_GET[tgl]' AND obat_igd = 'injeksi' GROUP BY date_format(created_at, '%Y-%m-%d') ORDER BY idobat DESC");
                foreach ($getLpo as $lpop) {
                ?>
                  <span class="badge bg-warning" style="font-size"><span onclick="document.location.href='index.php?halaman=cttpenyakit&id=<?= $_GET['id'] ?>&inap&tgl=<?= $_GET['tgl'] ?>&tglobat=<?= date('Y-m-d', strtotime($lpop['created_at'])) ?>'"><?= date('Y-m-d', strtotime($lpop['created_at'])) ?></span></span>
                <?php } ?>
              </div>
              <div class="table-responsive">
                <table class="table table-hover table-striped" style="font-size: 12px;">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Obat</th>
                      <th>Kode Obat</th>
                      <th>Jumlah</th>
                      <th>Harga</th>
                      <th>Sub</th>
                      <th>Dosis</th>
                      <th>Jenis</th>
                      <th>Durasi</th>
                      <th>Tanggal</th>
                      <th>Petugas</th>
                      <th>Act</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $getLatLpo = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND tgl_pasien='$_GET[tgl]' AND obat_igd = 'injeksi' GROUP BY date_format(created_at, '%Y-%m-%d') ORDER BY idobat DESC LIMIT 1")->fetch_assoc();
                    if (!isset($_GET['tglobat'])) {
                      $tgl = date('Y-m-d', strtotime($getLatLpo['created_at']));
                    } else {
                      $tgl = $_GET['tglobat'];
                    }
                    $whereTgl = " AND date_format(created_at, '%Y-%m-%d') = '$tgl'";
                    $injek = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND tgl_pasien='$_GET[tgl]' AND obat_igd = 'injeksi'" . $whereTgl);
                    $urlBase = "index.php?halaman=cttpenyakit&id=" . htmlspecialchars($_GET['id']) . "&inap&tgl=" . htmlspecialchars($_GET['tgl']);
                    $noo = 1;
                    foreach ($injek as $in) {
                    ?>
                      <tr>
                        <td class="text-light <?= $in['see_apotek_at'] == null ? 'bg-danger' : 'bg-success' ?>"><?php echo $noo++; ?></td>
                        <td><?php echo $in["nama_obat"]; ?></td>
                        <td><?php echo $in["kode_obat"]; ?></td>
                        <td><?php echo $in["jml_dokter"]; ?></td>
                        <td>
                          <?php
                          $getPriceInDate = $koneksi->query("SELECT * FROM apotek WHERE tgl_beli <= '" . date('Y-m-d', strtotime($in['created_at'])) . "' AND nama_obat = '$in[nama_obat]' ORDER BY tgl_beli DESC LIMIT 1")->fetch_assoc();
                          ?>
                          Rp
                          <?= number_format($harga = $getPriceInDate['harga_beli'] * ($getPriceInDate['margininap'] / 100), 0, 0, '.') ?>
                        </td>
                        <td>
                          Rp <?= number_format($harga * $in['jml_dokter'], 0, 0, '.') ?>
                        </td>
                        <td><?php echo $in["dosis1_obat"]; ?> X <?php echo $in["dosis2_obat"]; ?>
                          <?php echo $in["per_obat"]; ?>
                        </td>
                        <td><?php echo $in["jenis_obat"]; ?> <?php echo $in["racik"]; ?></td>
                        <td><?php echo $in["durasi_obat"]; ?> hari</td>
                        <td>
                          <a target="_blank"
                            href="../apotek/lpo_print_obat.php?id=<?= htmlspecialchars($_GET['id']) ?>&inap&tgl=<?= htmlspecialchars($_GET['tgl']) ?>&tglObat=<?php echo date('Y-m-d', strtotime($in["created_at"])) ?>&jenis=<?= $in['obat_igd'] ?>"
                            class="badge bg-warning text-dark" style="font-size: 12px;">
                            <?php echo date('Y-m-d', strtotime($in["created_at"])) ?>
                          </a>
                        </td>
                        <td><?= $in['petugas'] ?></td>
                        <!-- <td> <button type="button" class="btn btn-primary text-right" data-bs-toggle="modal" data-bs-target="#exampleModalEdit<?php echo $in["idobat"]; ?>">Edit</button></td> -->
                        <td>
                          <?php if ($_SESSION['admin']['level'] == 'sup' or $_SESSION['admin']['level'] == 'apoteker' or $_SESSION['admin']['level'] == 'racik') { ?>
                            <!-- <button class="btn btn-sm btn-warning" onclick="upData('<?= $in['idobat'] ?>','<?= $in['nama_obat'] ?>','<?= $in['kode_obat'] ?>','<?= $in['jenis_obat'] ?>', '<?= number_format($harga, 0, 0, '') ?>')" data-bs-toggle="modal" data-bs-target="#AddRetur"><i class="bi bi-capsule-pill"></i></button>
                            <a href="<?= $urlBase ?>&idObat=<?= $in['idobat'] ?>" class="btn btn-sm btn-danger"><i
                                class="bi bi-trash"></i></a> -->
                          <?php } else { ?>
                            <span style="font-size: 6.5px;">Kesalahan Silahkan Lapor Wadir</span>
                          <?php } ?>
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <br>
          <div class="col-md-12">
            <div class="card shadow p-2 mb-1">
              <label for="">Obat Oral</label>
              <div align="left">
                <?php if ($_SESSION['admin']['level'] != 'dokter') { ?>
                  <!-- <button type="button" onclick="changeJenis('Oral')" class="btn btn-primary btn-sm text-right"
                    data-bs-toggle="modal" data-bs-target="#exampleModal45">Add Jadi</button>
                  <button type="button" onclick="changeJenis('Oral')" class="btn btn-primary btn-sm text-right"
                    data-bs-toggle="modal" data-bs-target="#exampleModal2">Add Racik</button> -->
                <?php } ?>
              </div>
              <div>
                <?php
                $getLpo = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND tgl_pasien='$_GET[tgl]' AND obat_igd = 'injeksi' GROUP BY date_format(created_at, '%Y-%m-%d') ORDER BY idobat DESC");
                foreach ($getLpo as $lpop) {
                ?>
                  <span class="badge bg-warning" style="font-size"><span onclick="document.location.href='index.php?halaman=cttpenyakit&id=<?= $_GET['id'] ?>&inap&tgl=<?= $_GET['tgl'] ?>&tglobat=<?= date('Y-m-d', strtotime($lpop['created_at'])) ?>'"><?= date('Y-m-d', strtotime($lpop['created_at'])) ?></span></span>
                <?php } ?>
              </div>
              <div class="table-responsive">
                <table class="table table-hover table-striped" style="font-size: 12px;">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Obat</th>
                      <th>Kode Obat</th>
                      <th>Jumlah</th>
                      <th>Harga</th>
                      <th>Sub</th>
                      <th>Dosis</th>
                      <th>Jenis</th>
                      <th>Durasi</th>
                      <th>Tanggal</th>
                      <th>Petugas</th>
                      <th>Act</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php

                    $getLatLpo = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND tgl_pasien='$_GET[tgl]' AND obat_igd = 'oral' GROUP BY date_format(created_at, '%Y-%m-%d') ORDER BY idobat DESC LIMIT 1")->fetch_assoc();
                    if (!isset($_GET['tglobat'])) {
                      $tgl = date('Y-m-d', strtotime($getLatLpo['created_at']));
                    } else {
                      $tgl = $_GET['tglobat'];
                    }
                    $whereTgl = " AND date_format(created_at, '%Y-%m-%d') = '$tgl'";
                    $oral = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND tgl_pasien='$_GET[tgl]' AND obat_igd = 'oral'" . $whereTgl);

                    $no = 1;
                    foreach ($oral as $or) {
                    ?>
                      <tr>
                        <td class="text-light <?= $or['see_apotek_at'] == null ? 'bg-danger' : 'bg-success' ?>"><?php echo $no++; ?></td>
                        <td><?php echo $or["nama_obat"]; ?></td>
                        <td><?php echo $or["kode_obat"]; ?></td>
                        <td><?php echo $or["jml_dokter"]; ?></td>
                        <td>
                          <?php
                          $getPriceInDate = $koneksi->query("SELECT * FROM apotek WHERE tgl_beli <= '" . date('Y-m-d', strtotime($or['created_at'])) . "' AND id_obat = '$or[kode_obat]' ORDER BY tgl_beli DESC LIMIT 1")->fetch_assoc();
                          ?>
                          Rp
                          <?= number_format($harga = $getPriceInDate['harga_beli'] * ($getPriceInDate['margininap'] / 100), 0, 0, '.') ?>
                        </td>
                        <td>
                          Rp <?= number_format($harga * $or['jml_dokter'], 0, 0, '.') ?>
                        </td>
                        <td><?php echo $or["dosis1_obat"]; ?> X <?php echo $or["dosis2_obat"]; ?>
                          <?php echo $or["per_obat"]; ?>
                        </td>
                        <td><?php echo $or["jenis_obat"]; ?> <?php echo $or["racik"]; ?></td>
                        <td><?php echo $or["durasi_obat"]; ?> hari</td>
                        <td>
                          <a target="_blank"
                            href="../apotek/lpo_print_obat.php?id=<?= htmlspecialchars($_GET['id']) ?>&inap&tgl=<?= htmlspecialchars($_GET['tgl']) ?>&tglObat=<?php echo date('Y-m-d', strtotime($or["created_at"])) ?>&jenis=<?= $or['obat_igd'] ?>"
                            class="badge bg-warning text-dark" style="font-size: 12px;">
                            <?php echo date('Y-m-d', strtotime($or["created_at"])) ?>
                          </a>
                        </td>
                        <td>
                          <?= $or['petugas'] ?>
                        </td>
                        <!-- <td> <button type="button" class="btn btn-primary text-right" data-bs-toggle="modal" data-bs-target="#exampleModalEdit<?php echo $or["idobat"]; ?>">Edit</button></td> -->
                        <td>
                          <?php if ($_SESSION['admin']['level'] == 'sup' or $_SESSION['admin']['level'] == 'apoteker' or $_SESSION['admin']['level'] == 'racik') { ?>
                            <!-- <button class="btn btn-sm btn-warning" onclick="upData('<?= $or['idobat'] ?>','<?= $or['nama_obat'] ?>','<?= $or['kode_obat'] ?>','<?= $or['jenis_obat'] ?>', '<?= number_format($harga, 0, 0, '') ?>')" data-bs-toggle="modal" data-bs-target="#AddRetur"><i class="bi bi-capsule-pill"></i></button>
                            <a href="<?= $urlBase ?>&idObat=<?= $or['idobat'] ?>" class="btn btn-sm btn-danger"><i
                                class="bi bi-trash"></i></a> -->
                          <?php } else { ?>
                            <span style="font-size: 6.5px;">Kesalahan Silahkan Lapor Wadir</span>
                          <?php } ?>
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="card shadow p-2 mb-1">
              <b>Riwayat Retur Obat</b>
              <div class="table-responsive">
                <table class="table table-hover table-striped table-sm" style="font-size: 12px;">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Tgl</th>
                      <th>Kode</th>
                      <th>Nama Obat</th>
                      <th>Jenis Obat</th>
                      <th>Jenis</th>
                      <th>Harga</th>
                      <th>Jumlah Retur</th>
                      <th>Sub</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $nooo = 1;
                    $getRetur = $koneksi->query("SELECT *, obat_rm.obat_igd FROM retur_obat_inap INNER JOIN obat_rm ON obat_rm.idobat = retur_obat_inap.obat_rm_id WHERE idrawat = '" . htmlspecialchars($id['idrawat']) . "'");
                    foreach ($getRetur as $retur) {
                    ?>
                      <tr>
                        <td><?= $nooo++ ?></td>
                        <td><?= $retur['tgl_retur'] ?></td>
                        <td>
                          <a target="_blank" href="../apotek/retur_obat_inap_print.php?idrawat=<?= $retur['idrawat'] ?>&tgl=<?= $retur['tgl_retur'] ?>" class="badge bg-warning text-light" style="font-size: 12px;">
                            <?= $retur['kode_obat'] ?>
                          </a>
                        </td>
                        <td><?= $retur['nama_obat'] ?></td>
                        <td><?= $retur['jenis_obat'] ?></td>
                        <td><?= $retur['obat_igd'] ?></td>
                        <td>
                          <?php
                          $getPriceInDate = $koneksi->query("SELECT * FROM rawatinapdetail WHERE ket LIKE '%Retur%' AND ket LIKE '%$retur[idretur]%' ORDER BY created_at DESC LIMIT 1")->fetch_assoc();
                          $harga = $getPriceInDate['besaran'] / $retur['jumlah_retur'];
                          ?>
                          Rp <?= number_format($harga, 0, 0, '.') ?>
                        </td>
                        <td><?= $retur['jumlah_retur'] ?></td>
                        <td>Rp <?= number_format($harga * $retur['jumlah_retur'], 0, 0, '.') ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <script>
            function upData(idobat, nama_obat, kode_obat, jenis_obat, harga) {
              document.getElementById('idobat_id').value = idobat;
              document.getElementById('nama_obat_id').value = nama_obat;
              document.getElementById('kode_obat_id').value = kode_obat;
              document.getElementById('jenis_obat_id').value = jenis_obat;
              document.getElementById('harga_id').value = harga;
            }
          </script>
          <!-- Modal -->
          <div class="modal fade" id="AddRetur" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="staticBackdropLabel">Retur Obat</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post">
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-3">
                        <input type="text" readonly name="nama_obat" id="nama_obat_id" class="form-control form-control-sm mb-1">
                        <input type="text" readonly name="idobat" id="idobat_id" hidden class="form-control form-control-sm mb-1">
                      </div>
                      <div class="col-3">
                        <input type="text" readonly name="kode_obat" id="kode_obat_id" class="form-control form-control-sm mb-1">
                      </div>
                      <div class="col-3">
                        <input type="text" readonly name="harga" id="harga_id" class="form-control form-control-sm mb-1">
                      </div>
                      <div class="col-3">
                        <input type="text" readonly name="jenis_obat" id="jenis_obat_id" class="form-control form-control-sm mb-1">
                      </div>
                      <div class="col-12">
                        <input type="number" autofocus name="jumlah_retur" id="jumlah_retur_id" placeholder="Jumlah Retur" class="form-control form-control-sm mb-1">
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="addRetur" class="btn btn-sm btn-primary">Retur</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!-- <div class="col-12">
            <div class="d-flex justify-content-end mb-4 mt-4">
              <a class="btn btn-sm btn-primary"
                onclick="return confirm('Jika sudah yakin maka tombol tambah obat akan hilang, apakah anda yakin akan menyelesaikan inputan obat ?')"
                href="index.php?halaman=cttpenyakit&id=<?= $_GET['id'] ?>&inap&tgl=<?= $_GET['tgl'] ?>&apotek=done">Obat Sudah
                di-Input Semua dan Pasien Boleh Pulang</a>
            </div>
          </div> -->
        </div>
      <?php } else { ?>

      <?php } ?>
    </div>
  </main>


  <!-- End #main -->
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
</body>

</html>


<script type="text/javascript">
  $(document).ready(function() {
    $(".add-more").click(function() {
      var html = $(".copy").html();
      $(".after-add-more").after(html);
    });

    // saat tombol remove dklik control group akan dihapus 
    $("body").on("click", ".remove", function() {
      $(this).parents(".control-group").remove();
    });
  });
</script>

<script>
  var myModal = document.getElementById('myModal');
</script>

<script type="text/javascript">
  $(document).ready(function() {
    refreshTable();
  });

  function refreshTable() {
    $('#userList').load('rmedis.php', function() {
      setTimeout(refreshTable, 1000);
    });
  }
</script>


<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

<!-- Select2 CDN -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://cdn.ckeditor.com/ckeditor5/37.1.0/classic/ckeditor.js"></script>

<!-- Initialize Select2 -->
<script>
  $(document).ready(function() {
    $('#selectTemanPerawatCtt').select2({
      placeholder: "Pilih Teman Perawat",
      allowClear: true,
      width: '100%',
      dropdownParent: $('#tagTeman')
    });
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    ClassicEditor
      .create(document.querySelector('#editor'), {
        ckfinder: {
          uploadUrl: 'https://www.gkjwtunjungrejo.com/image-upload?_token=i99BxDXahocEmpYJ9vCLcLSTnfwaDPss37KbA71C',
        },
        toolbar: [
          'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'undo', 'redo'
        ]
      })
      .catch(error => {
        console.error(error);
      });
    ClassicEditor
      .create(document.querySelector('#editor2'), {
        ckfinder: {
          uploadUrl: 'https://www.gkjwtunjungrejo.com/image-upload?_token=i99BxDXahocEmpYJ9vCLcLSTnfwaDPss37KbA71C',
        },
        toolbar: [
          'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'undo', 'redo'
        ]
      })
      .catch(error => {
        console.error(error);
      });
    ClassicEditor
      .create(document.querySelector('#editor3'), {
        ckfinder: {
          uploadUrl: 'https://www.gkjwtunjungrejo.com/image-upload?_token=i99BxDXahocEmpYJ9vCLcLSTnfwaDPss37KbA71C',
        },
        toolbar: [
          'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'undo', 'redo'
        ]
      })
      .catch(error => {
        console.error(error);
      });
    ClassicEditor
      .create(document.querySelector('#editor4'), {
        ckfinder: {
          uploadUrl: 'https://www.gkjwtunjungrejo.com/image-upload?_token=i99BxDXahocEmpYJ9vCLcLSTnfwaDPss37KbA71C',
        },
        toolbar: [
          'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'undo', 'redo'
        ]
      })
      .catch(error => {
        console.error(error);
      });
    ClassicEditor
      .create(document.querySelector('#editor5'), {
        ckfinder: {
          uploadUrl: 'https://www.gkjwtunjungrejo.com/image-upload?_token=i99BxDXahocEmpYJ9vCLcLSTnfwaDPss37KbA71C',
        },
        toolbar: [
          'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'undo', 'redo'
        ]
      })
      .catch(error => {
        console.error(error);
      });
    ClassicEditor
      .create(document.querySelector('#editor6'), {
        ckfinder: {
          uploadUrl: 'https://www.gkjwtunjungrejo.com/image-upload?_token=i99BxDXahocEmpYJ9vCLcLSTnfwaDPss37KbA71C',
        },
        toolbar: [
          'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'undo', 'redo'
        ]
      })
      .catch(error => {
        console.error(error);
      });
    ClassicEditor
      .create(document.querySelector('#editor7'), {
        ckfinder: {
          uploadUrl: 'https://www.gkjwtunjungrejo.com/image-upload?_token=i99BxDXahocEmpYJ9vCLcLSTnfwaDPss37KbA71C',
        },
        toolbar: [
          'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'undo', 'redo'
        ]
      })
      .catch(error => {
        console.error(error);
      });
    ClassicEditor
      .create(document.querySelector('#request_obat'), {
        ckfinder: {
          uploadUrl: 'https://www.gkjwtunjungrejo.com/image-upload?_token=i99BxDXahocEmpYJ9vCLcLSTnfwaDPss37KbA71C',
        },
        toolbar: [
          'bulletedList', 'numberedList', '|', 'bold', 'italic', '|', 'undo', 'redo'
        ]
      })
      .then(editor => {
        // Auto focus pada editor saat modal dibuka
        const modal = document.getElementById('ajukanObat');
        if (modal) {
          modal.addEventListener('shown.bs.modal', function() {
            editor.focus();
          });
        }
      })
      .catch(error => {
        console.error(error);
      });
  });
</script>
<?php
if (isset($_POST['update'])) {

  // $koneksi->query("UPDATE ctt_penyakit_inap SET tgl='$_POST[tgl]', norm='$_GET[id]', ctt_dokter='$_POST[ctt_dokter]', ctt_tedis='" . $koneksi->real_escape_string($_POST['ctt_tedis']) . "', petugas='$petugas', kamar='$_POST[kamar]', pasien='$_POST[pasien]' WHERE id = '$_GET[idcct]'");

  $koneksi->query("UPDATE ctt_penyakit_inap SET tgl='$_POST[tgl]', norm='$_GET[id]', ctt_dokter='$_POST[ctt_dokter]', ctt_tedis='" . $koneksi->real_escape_string($_POST['ctt_tedis']) . "', petugas='$petugas', kamar='$_POST[kamar]', pasien='$_POST[pasien]', ctt_penyakit_inap.object='" . $koneksi->real_escape_string($_POST['object']) . "', alergi='" . $koneksi->real_escape_string($_POST['alergi']) . "', assesment='" . $koneksi->real_escape_string($_POST['assesment']) . "', plan='" . $koneksi->real_escape_string($_POST['plan']) . "', intruksi='" . $koneksi->real_escape_string($_POST['intruksi']) . "', edukasi='" . $koneksi->real_escape_string($_POST['edukasi']) . "', dokter='" . $_SESSION['dokter_rawat'] . "' WHERE id = '$_GET[idcct]'");

  $koneksi->query("UPDATE registrasi_rawat SET kamar='$_POST[kamar]' WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]'");
  echo "
    <script>
      alert('Data berhasil diubah');
      document.location.href='index.php?halaman=cttpenyakit&id=$_GET[id]&tgl=$_GET[tgl]';
    </script>
  ";
}

if (isset($_POST['save'])) {
  $koneksi->query("INSERT INTO ctt_penyakit_inap(tgl, norm, ctt_dokter, ctt_tedis, petugas, kamar, pasien, ctt_penyakit_inap.object, alergi, assesment, plan, intruksi, edukasi, dokter, shift) VALUES ('$_POST[tgl]', '$_GET[id]','$_POST[ctt_dokter]', '" . $koneksi->real_escape_string($_POST['ctt_tedis']) . "', '$petugas', '$_POST[kamar]', '$_POST[pasien]', '" . $koneksi->real_escape_string($_POST['object']) . "', '" . $koneksi->real_escape_string($_POST['alergi']) . "', '" . $koneksi->real_escape_string($_POST['assesment']) . "', '" . $koneksi->real_escape_string($_POST['plan']) . "', '" . $koneksi->real_escape_string($_POST['intruksi']) . "', '" . $koneksi->real_escape_string($_POST['edukasi']) . "','" . $_SESSION['dokter_rawat'] . "', '" . $_SESSION['shift'] . "')");
  // $koneksi->query("UPDATE ctt_penyakit_inap SET tgl='$_POST[tgl]', norm='$_GET[id]', ctt_dokter='$_POST[ctt_dokter]', ctt_tedis='$_POST[ctt_tedis]', petugas='$petugas', kamar='$_POST[kamar]', pasien='$_POST[pasien]'");
  $koneksi->query("UPDATE registrasi_rawat SET kamar='$_POST[kamar]' WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]'");
  echo "
    <script>
      alert('Data berhasil ditambah');
      document.location.href='index.php?halaman=cttpenyakit&id=$_GET[id]&tgl=$_GET[tgl]';
    </script>
  ";
}




?>