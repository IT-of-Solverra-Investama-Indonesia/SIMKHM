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
if (isset($_POST['saveob'])) {
  $catatan_obat = $_POST['catatan_obat'];
  $nama = $_POST['nama_obat'];
  $jml_dokter = $_POST['jml_dokter'];
  $dosis1_obat = $_POST['dosis1_obat'];
  $dosis2_obat = $_POST['dosis2_obat'];
  $per_obat = $_POST['per_obat'];
  $durasi_obat = $_POST['durasi_obat'];
  $petunjuk_obat = $_POST['petunjuk_obat'];
  $end = date("H:i:s");

  $koneksi->query("UPDATE registrasi_rawat SET end='$end' WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' AND perawatan = 'Rawat Inap' ORDER BY idrawat DESC LIMIT 1;");
  $cekPemOb = $koneksi->query("SELECT * FROM registrasi_rawat WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' AND perawatan = 'Rawat Inap' ORDER BY idrawat DESC LIMIT 1")->fetch_assoc();
  $koneksi->query("UPDATE biaya_rawat SET poli = '35000' WHERE idregis='$cekPemOb[idrawat]'");

  for ($i = 0; $i < count($nama) - 1; $i++) {
    foreach ($_POST['catatan_obat'] as $catatan_obat) {
      foreach ($_POST['per_obat'] as $per_obat) {
        foreach ($_POST['durasi_obat'] as $durasi_obat) {
          foreach ($_POST['petunjuk_obat'] as $petunjuk_obat) {
            foreach ($_POST['jenis_obat'] as $jenis_obat) {
              foreach ($_POST['racik'] as $racik) {
                $uniqueId = getUniqeIdObat($koneksi);

                $ObatKode = $koneksi->query("SELECT id_obat, jml_obat, margininap, harga_beli, nama_obat FROM apotek WHERE tipe != '' AND id_obat = '" . $nama[$i] . "' ORDER BY idapotek DESC LIMIT 1")->fetch_assoc();

                $stokAkhir = $ObatKode['jml_obat'] - $jml_dokter[$i];

                $m = $ObatKode['margininap'];
                if ($m < 100) {
                  $margin = 1.30;
                } else {
                  $margin = $m / 100;
                }
                $subtotal = 0;
                $harga = $ObatKode['harga_beli'] * $margin * $jml_dokter[$i];

                date_default_timezone_set('Asia/Jakarta');
                $tanggal = date('Y-m-d');
                $biaya = isset($_GET['inap']) ? 'biayaobat inap' : 'biayaobat igd';
                $id = $_POST["idrm"];
                $resep = 'Resep' . ' ' . $id . ' ' . $uniqueId;

                $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran, ket, petugas ) VALUES ('$_POST[idrm]', '$tanggal', '$biaya', '$harga', '$resep', '$petugas') ");

                // $subtotal += $harga;

                // $koneksi->query("UPDATE apotek SET jml_obat = '$stokAkhir' WHERE id_obat = '$ObatKode[id_obat]'");

                $koneksi->query("INSERT INTO obat_rm SET idobat = '$uniqueId', catatan_obat = '$catatan_obat', nama_obat = '$ObatKode[nama_obat]', kode_obat = '$nama[$i]', jml_dokter = '$jml_dokter[$i]', dosis1_obat = '$dosis1_obat', dosis2_obat = '$dosis2_obat', per_obat = '$per_obat', durasi_obat = '$durasi_obat', petunjuk_obat = '$petunjuk_obat', jenis_obat = '$jenis_obat', idigd = '$_GET[idigd]', tgl_pasien = '$_GET[tgl]', obat_igd = '$_POST[jenis]', racik = '$racik', idrm = '$_GET[id]', petugas = '" . $_SESSION['admin']['namalengkap'] . "'");
              }
            }
          }
        }
      }
    }
  }
  echo "
  <script>
    document.location.href='index.php?halaman=cttpenyakit&id=$_GET[id]&inap&tgl=$_GET[tgl]';
  </script>
  ";
}

if (isset($_POST['saveobnew'])) {
  $catatan_obat = $_POST['catatan_obat'];
  $nama = $_POST['nama_obat'];
  $jml_dokter = $_POST['jml_dokter'];
  $dosis1_obat = $_POST['dosis1_obat'];
  $dosis2_obat = $_POST['dosis2_obat'];
  $per_obat = $_POST['per_obat'];
  $durasi_obat = $_POST['durasi_obat'];
  $jenis_obat = $_POST['jenis_obat'];
  $petunjuk_obat = $_POST['petunjuk_obat'];
  $idrm = $_POST['idrm'];

  $end = date("H:i:s");
  for ($i = 0; $i < count($nama) - 1; $i++) {
    $uniqueId = getUniqeIdObat($koneksi);

    $ObatKode = $koneksi->query("SELECT id_obat, jml_obat, margininap, harga_beli, nama_obat FROM apotek WHERE tipe != '' AND id_obat= '" . $nama[$i] . "' ORDER BY idapotek DESC LIMIT 1")->fetch_assoc();
    $stokAkhir = $ObatKode['jml_obat'] - $jml_dokter[$i];
    $m = $ObatKode['margininap'];
    if ($m < 100) {
      $margin = 1.30;
    } else {
      $margin = $m / 100;
    }
    $subtotal = 0;
    $harga = $ObatKode['harga_beli'] * $margin * $jml_dokter[$i];
    $subtotal += $harga;
    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date('Y-m-d');
    $biaya = isset($_GET['inap']) ? 'biayaobat inap' : 'biayaobat igd';
    $id = $_POST["idrm"];
    $resep = 'Resep' . ' ' . $id . ' ' . $uniqueId;

    $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran, ket, petugas ) VALUES ('$_POST[idrm]', '$tanggal', '$biaya', '$harga', '$resep', '$petugas') ");

    $koneksi->query("INSERT INTO obat_rm SET idobat='$uniqueId', catatan_obat = '$catatan_obat[$i]', nama_obat = '$ObatKode[nama_obat]', kode_obat = '$nama[$i]', jml_dokter = '$jml_dokter[$i]', dosis1_obat = '$dosis1_obat[$i]', dosis2_obat = '$dosis2_obat[$i]', per_obat = '$per_obat[$i]', durasi_obat = '$durasi_obat[$i]', tgl_pasien = '$_GET[tgl]', petunjuk_obat = '$petunjuk_obat[$i]', jenis_obat = '$jenis_obat[$i]', idigd = '$_GET[idigd]', obat_igd = '$_POST[jenis]', idrm = '$_GET[id]', petugas = '" . $_SESSION['admin']['namalengkap'] . "'");
  }

  echo "
  <script>
    document.location.href='index.php?halaman=cttpenyakit&id=$_GET[id]&inap&tgl=$_GET[tgl]';
  </script>
  ";
}

if (isset($_GET['idObat'])) {
  $idObat = $_GET['idObat'];
  $koneksi->query("DELETE FROM obat_rm WHERE idobat = '$idObat'");
  $koneksi->query("DELETE FROM rawatinapdetail WHERE TRIM(SUBSTRING_INDEX(ket, ' ', -1)) = '$idObat'");
  echo "
  <script>
    document.location.href='index.php?halaman=cttpenyakit&id=$_GET[id]&inap&tgl=$_GET[tgl]';
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
                <?php if($_SESSION['admin']['level'] != 'racik' AND $_SESSION['admin']['level'] != 'apoteker'){?>
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
                          <input type="text" name="alergi" id="editor7" class="form-control" value="<?php if (isset($_GET['ctt']) or isset($_GET['ubah'])) {echo $getDataCopy['alergi']; } ?>" placeholder="Alergi Obat" style="width:100%; height:50px">
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
                    </div>
                  </div>
                <?php }?>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </form>
    <div class="">
      <?php if (!isset($_GET['entriObat'])) { ?>
        <div class="row">
          <script>
            function changeJenis(jenisObat) {
              var jenis3 = document.getElementById('jenis3');
              var jenis2 = document.getElementById('jenis2');
              jenis3.value = jenisObat;
              jenis2.value = jenisObat;
            }
          </script>
          <div class="col-md-12">
            <div class="card shadow p-2 mb-1">
              <label for="">Obat Injeksi </label>
              <div>
                <button type="button" onclick="changeJenis('Injeksi')" class="btn btn-primary btn-sm text-right"
                  data-bs-toggle="modal" data-bs-target="#exampleModal45">Add Jadi</button>
                <button type="button" onclick="changeJenis('Injeksi')" class="btn btn-primary btn-sm text-right"
                  data-bs-toggle="modal" data-bs-target="#exampleModal2">Add Racik</button>
                <?php if ($id['apoteker_check_at'] == null) { ?>
                <?php } ?>
              </div>
              <div>
                <?php
                $getLpo = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND tgl_pasien='$_GET[tgl]' AND obat_igd = 'injeksi' GROUP BY date_format(created_at, '%Y-%m-%d') ORDER BY idobat DESC");
                foreach ($getLpo as $lpop) {
                ?>
                  <span onclick="document.location.href='index.php?halaman=cttpenyakit&id=<?= $_GET['id'] ?>&inap&tgl=<?= $_GET['tgl'] ?>&tglobat=<?= date('Y-m-d', strtotime($lpop['created_at'])) ?>'" class="badge bg-warning" style="font-size"><?= date('Y-m-d', strtotime($lpop['created_at'])) ?></span>
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
                        <td><?php echo $noo++; ?></td>
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
                            <a href="<?= $urlBase ?>&idObat=<?= $in['idobat'] ?>" class="btn btn-sm btn-danger"><i
                                class="bi bi-trash"></i></a>
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
                <button type="button" onclick="changeJenis('Oral')" class="btn btn-primary btn-sm text-right"
                  data-bs-toggle="modal" data-bs-target="#exampleModal45">Add Jadi</button>
                <button type="button" onclick="changeJenis('Oral')" class="btn btn-primary btn-sm text-right"
                  data-bs-toggle="modal" data-bs-target="#exampleModal2">Add Racik</button>
                <?php if ($id['apoteker_check_at'] == null) { ?>
                <?php } ?>
              </div>
              <div>
                <?php
                $getLpo = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND tgl_pasien='$_GET[tgl]' AND obat_igd = 'injeksi' GROUP BY date_format(created_at, '%Y-%m-%d') ORDER BY idobat DESC");
                foreach ($getLpo as $lpop) {
                ?>
                  <span onclick="document.location.href='index.php?halaman=cttpenyakit&id=<?= $_GET['id'] ?>&inap&tgl=<?= $_GET['tgl'] ?>&tglobat=<?= date('Y-m-d', strtotime($lpop['created_at'])) ?>'" class="badge bg-warning" style="font-size"><?= date('Y-m-d', strtotime($lpop['created_at'])) ?></span>
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
                        <td><?php echo $no++; ?></td>
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
                          <?php if ($_SESSION['admin']['level'] == 'sup' OR $_SESSION['admin']['level'] == 'apoteker' OR $_SESSION['admin']['level'] == 'racik') { ?>
                            <a href="<?= $urlBase ?>&idObat=<?= $or['idobat'] ?>" class="btn btn-sm btn-danger"><i
                                class="bi bi-trash"></i></a>
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
          <!-- <div class="col-12">
            <div class="d-flex justify-content-end mb-4 mt-4">
              <a class="btn btn-sm btn-primary"
                onclick="return confirm('Jika sudah yakin maka tombol tambah obat akan hilang, apakah anda yakin akan menyelesaikan inputan obat ?')"
                href="index.php?halaman=cttpenyakit&id=<?= $_GET['id'] ?>&inap&tgl=<?= $_GET['tgl'] ?>&apotek=done">Obat Sudah
                di-Input Semua dan Pasien Boleh Pulang</a>
            </div>
          </div> -->
          <?php if($_SESSION['admin']['level'] != 'racik' AND $_SESSION['admin']['level'] != 'apoteker'){?>
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

                        $no = 1;
                        $riw = $koneksi->query("SELECT * FROM ctt_penyakit_inap WHERE norm='$_GET[id]' AND DATE_FORMAT(tgl, '%Y-%m-%d') > '" . ($getPulangTerakhir['tgl'] ?? date('Y-m-d', strtotime('20000-01-01'))) . "' GROUP BY tgl order by id DESC");
                        ?>
                        <?php foreach ($riw as $pecah) : ?>
                          <tr>
                            <td><?php echo $no; ?></td>
                            <td>
                              <?php echo $pecah["tgl"]; ?>
                              <?php
                              $getTagCount = $koneksi->query("SELECT * FROM ctt_penyakit_inap WHERE norm='$_GET[id]' AND tgl='$pecah[tgl]'");
                              foreach ($getTagCount as $tag) {
                                echo "<br><span class='badge bg-primary' style='font-size: 10px;'>@" . $tag['petugas'] . "</span>";
                              }
                              ?>
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
                              <span class="badge bg-primary my-1" style="font-size: 12px;" data-bs-toggle="modal" onclick="upDataId('<?= $pecah['id'] ?>')" data-bs-target="#tagTeman">@ Tag</span>
                              <?php if ($pecah['petugas'] === $petugas || $_SESSION['admin']['level'] == 'dokter' || $_SESSION['admin']['level'] == 'sup') { ?>
                                <a href="<?= getFullUrl(); ?>&idcct=<?= $pecah['id'] ?>&ubah" class="badge bg-success my-1" style="font-size: 12px;">Edit</a>
                                <a href="<?= getFullUrl(); ?>&ctt=<?= $pecah['id'] ?>#editorZone" class="badge bg-warning my-1" style="font-size: 12px;">Copy</a>
                                <a href="<?= getFullUrl(); ?>&ctt=<?= $pecah['id'] ?>&delete" onclick="return confirm('Teman teman yang anda tag pada catatan ini juga akan terhapus, apakah anda yakin ingin menghapus data ini ?')" class="badge bg-danger my-1" style="font-size: 12px;">Delete</a>
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
              <div class="card shadow p-2">
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
                        $getBulanLalu = $koneksi->query("SELECT DATE_FORMAT(tgl, '%Y-%m') as bulan FROM ctt_penyakit_inap WHERE norm='$_GET[id]' GROUP BY DATE_FORMAT(tgl, '%Y-%m') ORDER BY tgl DESC");
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
                        $riw = $koneksi->query("SELECT * FROM ctt_penyakit_inap WHERE norm='$_GET[id]' AND DATE_FORMAT(tgl, '%Y-%m-%d') > '" . $getPulangTerakhir['tgl'] . "' GROUP BY tgl order by id DESC");
                        ?>
                        <?php foreach ($riw as $pecah) : ?>
                          <tr>
                            <td><?php echo $no; ?></td>
                            <td>
                              <?php echo $pecah["tgl"]; ?>
                              <?php
                              $getTagCount = $koneksi->query("SELECT * FROM ctt_penyakit_inap WHERE norm='$_GET[id]' AND tgl='$pecah[tgl]'");
                              foreach ($getTagCount as $tag) {
                                echo "<br><span class='badge bg-primary' style='font-size: 10px;'>@" . $tag['petugas'] . "</span>";
                              }
                              ?>
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
                              <span class="badge bg-primary my-1" style="font-size: 12px;" data-bs-toggle="modal" onclick="upDataId('<?= $pecah['id'] ?>')" data-bs-target="#tagTeman">@ Tag</span>
                              <?php if ($pecah['petugas'] === $petugas || $_SESSION['admin']['level'] == 'dokter' || $_SESSION['admin']['level'] == 'sup') { ?>
                                <a href="<?= getFullUrl(); ?>&idcct=<?= $pecah['id'] ?>&ubah" class="badge bg-success my-1" style="font-size: 12px;">Edit</a>
                                <a href="<?= getFullUrl(); ?>&ctt=<?= $pecah['id'] ?>#editorZone" class="badge bg-warning my-1" style="font-size: 12px;">Copy</a>
                                <a href="<?= getFullUrl(); ?>&ctt=<?= $pecah['id'] ?>&delete" onclick="return confirm('Teman teman yang anda tag pada catatan ini juga akan terhapus, apakah anda yakin ingin menghapus data ini ?')" class="badge bg-danger my-1" style="font-size: 12px;">Delete</a>
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
          <?php } ?>
        </div>
      <?php } else { ?>
        <div class="card shadow-sm mb-2 p-2">
          <h5><b>Entri Obat Jadi</b></h5>
          <form method="post">
            <div class="row g-1">
              <div class="col-md-2">
                <label>Nama Obat</label>
                <select name="nama_obat" class="obat-select form-control form-control-sm mb-2 w-100"
                  style="width:100%;" id="selectObatJadiEntriObat" aria-label="Default select example">
                  <option value="">Pilih</option>
                </select>
              </div>
              <div class="col-md-1">
                <label for="">Jenis</label>
                <select name="jenis_obat_2" class="form-control form-control-sm" id="">
                  <option value="Oral">Oral</option>
                  <option value="Injeksi">Injeksi</option>
                </select>
              </div>
              <div class="col-md-2">
                <label for="">Dosis</label>
                <div class="input-group input-group-sm ">
                  <input type="text" class="form-control form-control-sm mb-2" id="dosis1_obat" name="dosis1_obat">
                  <span type="text" style="text-align: center;" class="form-control form-control-sm mb-2"
                    placeholder="X" disabled>X</span>
                  <input type="text" class="form-control form-control-sm mb-2" id="dosis2_obat" name="dosis2_obat">
                </div>
              </div>
              <div class="col-md-2">
                <label for="">Per</label>
                <select id="inputState" name="per_obat" class=" form-control form-control-sm">
                  <option>Per Hari</option>
                  <option>Per Jam</option>
                </select>
              </div>
              <div class="col-md-2">
                <label for="">Jumlah</label>
                <input type="number" name="jml_dokter" class="form-control form-control-sm mb-2" id="inputName5"
                  placeholder="Jumlah Obat">
              </div>
              <div class="col-md-2">
                <label for="inputName5" class="">Petunjuk</label>
                <input type="text" name="petunjuk_obat" class="form-control form-control-sm mb-2" id="inputName5"
                  placeholder=" Petunjuk Pemakaian">
                <input type="text" name="catatan_obat" value="-" hidden class="form-control form-control-sm mb-2"
                  id="inputName5" placeholder="Masukkan Jumlah">
                <select name="jenis_obat" hidden class=" form-control form-control-sm mb-2">
                  <option value="Jadi">Jadi</option>
                </select>
              </div>
              <div class="col-md-1">
                <label for="inputCity" class="">Durasi</label>
                <div class="input-group mb-3">
                  <input type="text" name="durasi_obat" class="form-control form-control-sm" placeholder="Durasi"
                    aria-describedby="basic-addon2">
                </div>
              </div>
              <div class="col-md-12 text-end">
                <button class="btn btn-sm btn-primary" name="addToSession">Tambah [+]</button>
              </div>
            </div>
          </form>
          <?php
          // Proses tambah ke session
          if (isset($_POST['addToSession'])) {
            // Inisialisasi session jika belum ada
            if (!isset($_SESSION['temp_obat'])) {
              $_SESSION['temp_obat'] = array();
            }

            // Ambil data obat dari database untuk mendapatkan nama obat
            $id_obat = $_POST['nama_obat'];
            $query = $koneksi->query("SELECT * FROM apotek WHERE id_obat = '$id_obat'");
            $data_obat = $query->fetch_assoc();

            // Tambahkan data ke session
            $_SESSION['temp_obat'][] = array(
              'id_obat' => $id_obat,
              'nama_obat' => $data_obat['nama_obat'],
              'dosis1_obat' => $_POST['dosis1_obat'],
              'dosis2_obat' => $_POST['dosis2_obat'],
              'per' => $_POST['per_obat'],
              'jumlah' => $_POST['jml_dokter'],
              'petunjuk' => $_POST['petunjuk_obat'],
              'catatan' => $_POST['catatan_obat'],
              'jenis' => $_POST['jenis_obat'],
              'jenis2' => $_POST['jenis_obat_2'],
              'durasi' => $_POST['durasi_obat']
            );
          }

          // Proses hapus dari session
          if (isset($_GET['hapusObatSession'])) {
            $index = $_GET['hapusObatSession'];
            unset($_SESSION['temp_obat'][$index]);
          }

          if (isset($_GET['clear_session'])) {
            unset($_SESSION['temp_obat']);
            echo "<script>window.location.href = 'index.php?halaman=cttpenyakit&id=" . htmlspecialchars($_GET['id']) . "&tgl=" . htmlspecialchars($_GET['tgl']) . "&entriObat=" . htmlspecialchars($_GET['entriObat']) . "';</script>";
          }

          if (isset($_GET['saveToDatabase'])) {
            if (isset($_SESSION['temp_obat']) && count($_SESSION['temp_obat']) > 0) {
              foreach ($_SESSION['temp_obat'] as $obatSave) {

                // $koneksi->query("INSERT INTO obat_rm SET catatan_obat = '', kode_obat = '', nama_obat = '', jml_dokter = '', dosis1_obat = '$obatSave[dosis1_obat]', dosis2_obat = '$obatSave[dosis2_obat]', per_obat = '$obatSave[per]', durasi_obat = '$obatSave[durasi]', petunjuk_obat = '$obatSave[petunjuk]', jenis_obat = '$obatSave[jenis]', tgl_pasien = '$_GET[tgl]', rekam_medis_id = '$getLastRM[id_rm]', idrm = '$_GET[id]'");

                $uniqueId = getUniqeIdObat($koneksi);

                $koneksi->query("INSERT INTO obat_rm SET idobat='$uniqueId', catatan_obat = '$obatSave[catatan]', nama_obat = '$obatSave[nama_obat]', kode_obat = '$obatSave[id_obat]', jml_dokter = '$obatSave[jumlah]', dosis1_obat = '$obatSave[dosis1_obat]', dosis2_obat = '$obatSave[dosis2_obat]', per_obat = '$obatSave[per]', durasi_obat = '$obatSave[durasi]', tgl_pasien = '$_GET[tgl]', petunjuk_obat = '$obatSave[petunjuk]', jenis_obat = '$obatSave[jenis]', idigd = '" . (isset($_GET['idigd']) ? $_GET['idigd'] : '') . "', obat_igd = '$obatSave[jenis2]', idrm = '$id[no_rm]', petugas = '" . $_SESSION['admin']['namalengkap'] . "'");

                $ObatKode = $koneksi->query("SELECT id_obat, jml_obat, margininap, harga_beli, nama_obat FROM apotek WHERE tipe != '' AND id_obat= '" . $obatSave['id_obat'] . "' ORDER BY idapotek DESC LIMIT 1")->fetch_assoc();

                $stokAkhir = $ObatKode['jml_obat'] - $obatSave['jumlah'];
                $m = $ObatKode['margininap'];
                if ($m < 100) {
                  $margin = 1.30;
                } else {
                  $margin = $m / 100;
                }
                $subtotal = 0;
                $harga = $ObatKode['harga_beli'] * $margin * $obatSave['jumlah'];
                $subtotal += $harga;
                date_default_timezone_set('Asia/Jakarta');
                $tanggal = date('Y-m-d');
                $biaya = isset($_GET['inap']) ? 'biayaobat inap' : 'biayaobat igd';
                $id = $id['idrawat'];
                $resep = 'Resep' . ' ' . $id . ' ' . $uniqueId;

                $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran, ket, petugas ) VALUES ('$id', '$tanggal', '$biaya', '$harga', '$resep', '" . $_SESSION['admin']['namalengkap'] . "') ");

                // $koneksi->query("INSERT INTO rmedis_obat (id_rm, id_obat, dosis1_obat, dosis2_obat, per_obat, jumlah_obat, petunjuk_obat, catatan_obat, jenis_obat, durasi_obat) VALUES ('" . htmlspecialchars($_GET['id']) . "', '" . $obat['id_obat'] . "', '" . $obat['dosis1_obat'] . "', '" . $obat['dosis2_obat'] . "', '" . $obat['per'] . "', '" . $obat['jumlah'] . "', '" . $obat['petunjuk'] . "', '" . $obat['catatan'] . "', '" . $obat['jenis'] . "', '" . $obat['durasi'] . "')");
              }
              unset($_SESSION['temp_obat']);
              echo "<script>alert('Data berhasil disimpan ke database.'); window.location.href = 'index.php?halaman=cttpenyakit&id=" . htmlspecialchars($_GET['id']) . "&inap&tgl=" . htmlspecialchars($_GET['tgl']) . "';</script>";
            } else {
              echo "<script>alert('Tidak ada data obat untuk disimpan.');</script>";
            }
          }
          ?>
          <br>
          <div class="table-responsive">
            <div class="table-responsive">
              <table class="table table-striped table-hover table-sm" style="font-size: 12px;">
                <thead>
                  <tr>
                    <th>Obat</th>
                    <th>Kode</th>
                    <th>Dosis</th>
                    <th>Per</th>
                    <th>Jumlah</th>
                    <th>Petunjuk</th>
                    <th>Catatan</th>
                    <th>Durasi</th>
                    <th>JenisObat</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (isset($_SESSION['temp_obat']) && count($_SESSION['temp_obat']) > 0): ?>
                    <?php foreach ($_SESSION['temp_obat'] as $index => $obat): ?>
                      <tr>
                        <td><?= $obat['nama_obat'] ?></td>
                        <td><?= $obat['id_obat'] ?></td>
                        <td><?= $obat['dosis1_obat'] ?> X <?= $obat['dosis2_obat'] ?></td>
                        <td><?= $obat['per'] ?></td>
                        <td><?= $obat['jumlah'] ?></td>
                        <td><?= $obat['petunjuk'] ?></td>
                        <td><?= $obat['catatan'] ?></td>
                        <td><?= $obat['durasi'] ?></td>
                        <td><?= $obat['jenis2'] ?></td>
                        <td>
                          <a href="<?= getFullUrl() ?>&entriObat=<?= htmlspecialchars($_GET['entriObat']) ?>&hapusObatSession=<?= $index ?>"
                            class="btn btn-danger btn-sm">Hapus</a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="10" class="text-center">Tidak ada data obat</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
              <?php if (isset($_SESSION['temp_obat']) && count($_SESSION['temp_obat']) > 0): ?>
                <div class="text-end mt-3">
                  <a href="<?= getFullUrl() ?>&entriObat=<?= htmlspecialchars($_GET['entriObat']) ?>&saveToDatabase"
                    class="btn btn-sm btn-success">Simpan ke Database</a>
                  <a href="<?= getFullUrl() ?>&entriObat=<?= htmlspecialchars($_GET['entriObat']) ?>&clear_session=true"
                    class="btn btn-sm btn-danger">Bersihkan Session</a>
                </div>
              <?php endif; ?>
            </div>
          </div>
          <script>
            $(document).ready(function() {
              $('#selectObatJadiEntriObat').select2();
            });
          </script>
        </div>
      <?php } ?>
    </div>
  </main>

  <!-- Add Data Modal Obat  Jadi -->
  <div class="modal  fade" role="dialog" id="exampleModal45" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Obat <sup class="badge bg-primary text-light"><a
                href="<?= getFullUrl() ?>&entriObat=Jadi" class="text-light">NewTab</a></sup></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <form method="post" enctype="multipart/form-data">
              <div class="control-group">
                <!-- <div class="modal-body"> -->
                <div class="row">
                  <div class="col-md-12">
                    <label for="inputName5" class="form-label">Nama Obat</label><br>
                    <input type="text" name="jenis" id="jenis3" hidden>
                    <!-- <input type="text" name="nama_obat" class="form-control" id="inputName5" placeholder="Layanan/Tindakan"> -->
                    <select name="nama_obat[]" class="obat-select form-select mb-2 w-100" style="width:100%;"
                      id="selObat1" aria-label="Default select example">
                      <option value="">Pilih</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label for="inputName5">Dosis</label>
                    <div class="input-group">
                      <input type="text" class="form-control mb-2" id="dosis1_obat" name="dosis1_obat[]">
                      <span type="text" style="text-align: center;" class="form-control mb-2" placeholder="X"
                        disabled>X</span>
                      <input type="text" class="form-control mb-2" id="dosis2_obat" name="dosis2_obat[]">
                    </div>
                  </div>
                  <div class="col-md-6">
                    Per
                    <select id="inputState" name="per_obat[]" class="form-select">
                      <option>Per Hari</option>
                      <option>Per Jam</option>
                    </select>
                  </div>
                  <div class="col-md-12">
                    <label for="">Jumlah Obat</label>
                    <input type="number" name="jml_dokter[]" class="form-control mb-2" id="inputName5"
                      placeholder="Jumlah Obat">
                  </div>
                  <div class="col-md-12">
                    <label for="inputName5" class="">Petunjuk Pemakaian</label>
                    <input type="text" name="petunjuk_obat[]" class="form-control mb-2" id="inputName5"
                      placeholder="Masukkan Petunjuk Pemakaian">
                  </div>
                  <div class="col-md-12">
                    <!-- <label for="inputName5" class="">Catatan Interaksi Obat</label> -->
                    <input type="text" name="catatan_obat[]" value="-" hidden class="form-control mb-2"
                      id="inputName5" placeholder="Masukkan Jumlah">
                  </div>
                  <div class="col-md-12">
                    <label for="inputName5" class="">Jenis Obat</label>
                    <select name="jenis_obat[]" class="form-select mb-2">
                      <option value="Jadi">Jadi</option>
                      <!-- <option value="Jadi">Jadi</option> -->
                    </select>
                  </div>
                  <div class="col-md-12">
                    <label for="inputCity" class="">Durasi</label>
                    <div class="input-group mb-3">
                      <input type="text" name="durasi_obat[]" class="form-control" placeholder="Durasi"
                        aria-describedby="basic-addon2">
                      <span class="input-group-text" id="basic-addon2">Hari</span>
                    </div>
                  </div>
                </div>
                <hr>
              </div>
              <button class="btn btn-warning add-more2" type="button">
                <i class="glyphicon glyphicon-plus"></i> Tambah Lagi
              </button>
              <hr>
              <div class="after-add-more2"></div>
              <div class="copy2 invisible" style="display: none;">
                <br>
                <div class="control-group2">
                  <label for="inputName5" class="form-label">Nama Obat</label>
                  <!-- <input type="text" name="nama_obat" class="form-control" id="inputName5" placeholder="Layanan/Tindakan"> -->
                  <select name="nama_obat[]" class="obat-select form-select mb-2" id="selObat1"
                    aria-label="Default select example">
                    <option value="">Pilih</option>
                  </select>

                  <div class="row">
                    <div class="col-md-6">
                      <label for="inputName5" class="">Dosis</label>
                      <div class="input-group">
                        <input type="text" class="form-control mb-2" id="dosis1_obat" name="dosis1_obat[]">
                        <span type="text" style="text-align: center;" class="form-control mb-2"
                          placeholder="X">X</span>
                        <input type="text" class="form-control mb-2" id="dosis2_obat" name="dosis2_obat[]">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <label for="inputName5" class="">Per</label>
                      <select id="inputState" name="per_obat[]" class="form-select">
                        <option>Per Hari</option>
                        <option>Per Jam</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <label for="">Jumlah Obat</label>
                    <input type="number" name="jml_dokter[]" class="form-control mb-2" id="inputName5"
                      placeholder="jumlah obat">
                  </div>
                  <div class="col-md-12">
                    <label for="inputName5">Petunjuk Pemakaian</label>
                    <input type="text" name="petunjuk_obat[]" class="form-control mb-2" id="inputName5"
                      placeholder="Masukkan Petunjuk Pemakaian">
                  </div>
                  <div class="col-md-12">
                    <!-- <label for="inputName5">Catatan Interaksi Obat</label> -->
                    <input type="text" name="catatan_obat[]" value="-" hidden class="form-control mb-2"
                      id="inputName5" placeholder="Masukkan Jumlah">
                  </div>
                  <div class="col-md-12">
                    <label for="inputName5">Jenis Obat</label>
                    <select name="jenis_obat[]" class="form-select mb-2">
                      <option value="Jadi">Jadi</option>
                      <!-- <option value="Jadi">Jadi</option> -->
                    </select>
                  </div>

                  <div class="col-md-12">
                    <label for="inputCity">Durasi</label>
                    <div class="input-group mb-3">
                      <input type="text" name="durasi_obat[]" class="form-control mb-2" placeholder="Durasi"
                        aria-describedby="basic-addon2">
                      <span class="input-group-text" id="basic-addon2">Hari</span>
                    </div>
                  </div>
                  <button class="btn btn-danger remove2" type="button"><i
                      class="glyphicon glyphicon-remove"></i> Batal</button>
                  <button class="btn btn-warning"
                    onclick="document.getElementsByClassName('add-more2')[0].click()" type="button">
                    <i class="glyphicon glyphicon-plus"></i> Tambah Lagi
                  </button>
                  <hr>
                </div>
              </div>

              <!-- <input type="hidden" name="id_pasien" value="<?php echo $pecah['idpasien'] ?>"> -->
              <input type="hidden" name="idrm" value="<?php echo $id['idrawat'] ?>">
              <input type="hidden" class="form-control" id="inputName5" name="id"
                value="<?php echo $jadwal['idrawat'] ?>" placeholder="Masukkan Nama Pasien">
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <input type="submit" class="btn btn-primary" name="saveobnew" value="Save changes">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
    $(document).ready(function() {
      $(".add-more2").click(function() {
        var html = $(".copy2").html();
        $(".after-add-more2").after(html);
      });

      // saat tombol remove dklik control group akan dihapus 
      $("body").on("click", ".remove2", function() {
        $(this).parents(".control-group2").remove();
      });
    });
  </script>
  <!-- end -->

  <!-- Add Data Modal Obat Racik -->
  <div class="modal  fade" role="dialog" id="exampleModal2" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Obat</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <form method="post" enctype="multipart/form-data">
              <div class="control-group after-add-more">
                <!-- <div class="modal-body"> -->
                <div class="row">
                  <div class="col-md-12">
                    <input hidden type="text" id="jenis2" name="jenis" class="form-control">
                    <label for="inputName5" class="form-label">Nama Obat</label><br>
                    <select name="nama_obat[]" class="obat-select form-control w-100" style="width:100%;"
                      id="selObat1" aria-label="Default select example">
                      <option value="">Pilih</option>
                    </select>
                  </div>

                  <script></script>
                  <div class="col-md-12" style="margin-top:20px">
                    <label for="">Jumlah Obat</label>
                    <input type="number" name="jml_dokter[]" class="form-control" id="inputName5"
                      placeholder="jumlah obat">
                  </div>
                </div>
              </div>
              <button class="btn btn-warning add-more" type="button">
                <i class="glyphicon glyphicon-plus"></i> Tambah Lagi
              </button>
              <hr>
              <div class="copy invisible" style="display: none;">
                <br>
                <div class="control-group">
                  <label for="inputName5" class="form-label">Nama Obat</label>
                  <select name="nama_obat[]" class="obat-select form-control " id="selObat1"
                    aria-label="Default select example">
                    <option value="">Pilih</option>
                  </select>
                  <div class="col-md-12" style="margin-top:20px">
                    <label for="">Jumlah Obat</label>
                    <input type="number" name="jml_dokter[]" class="form-control" id="inputName5"
                      placeholder="jumlah obat">
                  </div>
                  <br>
                  <button class="btn btn-danger remove" type="button"><i
                      class="glyphicon glyphicon-remove"></i> Batal</button>
                  <hr>
                </div>
              </div>
              <div class="col-md-12" style="margin-top:20px; margin-bottom:20px;">
                <label for="inputName5" class="form-label">Catatan Interaksi Obat</label>
                <input type="text" name="catatan_obat[]" class="form-control" id="inputName5"
                  placeholder="Masukkan Jumlah">
              </div>
              <div class="col-md-12" style="margin-top:0px; margin-bottom:20px;">
                <label for="inputName5" class="form-label">Jenis Obat</label>
                <select name="jenis_obat[]" class="form-control">
                  <option value="Racik">Racik</option>
                  <!-- <option value="Jadi">Jadi</option> -->
                </select>
              </div>
              <div class="col-md-12" style="margin-top:20px; margin-bottom:20px;">
                <label for="inputName5" class="form-label">Racik Ke - </label>
                <input type="number" name="racik[]" class="form-control" id="inputName5"
                  placeholder="Masukkan racik">
              </div>
              <label for="inputName5" class="form-label">Dosis</label>
              <div class="row">
                <div class="col-md-6">
                  <div class="input-group mb-6">
                    <input type="text" class="form-control" id="dosis1_obat[]" name="dosis1_obat">
                    <input type="text" style="text-align: center;" class="form-control" placeholder="X">
                    <input type="text" class="form-control" id="dosis2_obat[]" name="dosis2_obat">
                  </div>
                </div>
                <div class="col-md-6">
                  <select id="inputState" name="per_obat[]" class="form-control">
                    <option>Per Hari</option>
                    <option>Per Jam</option>
                  </select>
                </div>
              </div>

              <div class="col-md-12" style="margin-top:20px">
                <label for="inputCity" class="form-label">Durasi</label>
                <div class="input-group mb-3">
                  <input type="text" name="durasi_obat[]" class="form-control" placeholder="Durasi"
                    aria-describedby="basic-addon2">
                  <span class="input-group-text" id="basic-addon2">Hari</span>
                </div>
              </div>
              <div class="col-md-12" style="margin-top:10px">
                <label for="inputName5" class="form-label">Petunjuk Pemakaian</label>
                <input type="text" name="petunjuk_obat[]" class="form-control" id="inputName5"
                  placeholder="Masukkan Petunjuk Pemakaian">
              </div>
              <input type="hidden" name="idrm" value="<?php echo $id['idrawat'] ?>">
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <input type="submit" class="btn btn-primary" name="saveob" value="Save changes">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
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
  <!-- end -->
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