<?php
$date = date("Y-m-d");
date_default_timezone_set('Asia/Jakarta');
$petugas = $_SESSION['admin']['namalengkap'];
$pasien = $koneksi->query("SELECT * FROM pasien  WHERE no_rm='$_GET[id]';")->fetch_assoc();
if (!isset($_GET['igd'])) {
  $jadwal = $koneksi->query("SELECT * FROM registrasi_rawat  WHERE no_rm='$_GET[id]' AND date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' AND perawatan = 'Rawat Inap' ORDER BY idrawat DESC LIMIT 1")->fetch_assoc();
}

$id = $koneksi->query("SELECT * FROM registrasi_rawat  WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' AND perawatan = 'Rawat Inap' ORDER BY idrawat DESC LIMIT 1")->fetch_assoc();

$ConditionCopy = 0;

if (isset($_GET['idlpo'])) {
  $ConditionCopy = htmlspecialchars($_GET['idlpo']);
  $dataCopy = $koneksi->query("SELECT * FROM lpo WHERE id_lpo = '$ConditionCopy'")->fetch_assoc();
}

function getFullUrl()
{
  $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ||
    $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

  return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
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
?>

<?php
// Handle hapus foto penunjang
if (isset($_GET['hapus_foto_penunjang'])) {
  $foto_hapus = $_GET['hapus_foto_penunjang'];
  $id_lpo = $_GET['id_lpo'];

  $lpo_data = $koneksi->query("SELECT penunjang FROM lpo WHERE id_lpo='$id_lpo'")->fetch_assoc();

  if (!empty($lpo_data['penunjang'])) {
    $foto_list = json_decode($lpo_data['penunjang'], true);
    if (is_array($foto_list)) {
      // Hapus dari array
      $foto_list = array_filter($foto_list, function ($f) use ($foto_hapus) {
        return $f !== $foto_hapus;
      });

      // Hapus file fisik
      $file_path = '../rawatinap/pemeriksaan_penunjang/' . $foto_hapus;
      if (file_exists($file_path)) {
        unlink($file_path);
      }

      // Update database
      $foto_json = json_encode(array_values($foto_list));
      $koneksi->query("UPDATE lpo SET penunjang='" . mysqli_real_escape_string($koneksi, $foto_json) . "' WHERE id_lpo='$id_lpo'");

      if (isset($_GET['igd'])) {
        echo "<script>alert('Foto berhasil dihapus'); window.location.href='index.php?halaman=lpo&igd&id=" . $_GET['id'] . "&idigd=" . $_GET['idigd'] . "&tgl=" . $_GET['tgl'] . "';</script>";
      } else {
        echo "<script>alert('Foto berhasil dihapus'); window.location.href='index.php?halaman=lpo&id=" . $_GET['id'] . "&inap&tgl=" . $_GET['tgl'] . "';</script>";
      }
    }
  }
}

if (isset($_GET['apotek']) && $_GET['apotek'] == 'done') {
  $koneksi->query("UPDATE registrasi_rawat SET apoteker_check_at = '" . date('Y-m-d H:i:s') . "' WHERE no_rm = '$_GET[id]' AND date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' AND perawatan = 'Rawat Inap' ORDER BY idrawat DESC LIMIT 1");
  echo "<script>alert('Proses Selesai, Pasien Boleh Pulang!');</script>";
  echo "<script>location='index.php?halaman=lpo&id=$_GET[id]&inap&tgl=$_GET[tgl]';</script>";
  exit();
}

// Handle Simpan Penggunaan Obat
if (isset($_POST['savePenggunaan'])) {
  // Cek apakah ada obat yang dipilih
  if (isset($_POST['idobatcheck']) && !empty($_POST['idobatcheck'])) {
    // Cek apakah ada jam yang dipilih
    if (isset($_POST['digunakan']) && !empty($_POST['digunakan'])) {
      $jam_dipilih = $_POST['digunakan']; // Array jam yang dipilih
      $id_obat_list = $_POST['idobatcheck']; // Array id obat yang dipilih

      // Gabungkan jam-jam yang dipilih menjadi string
      $jam_baru = implode(', ', $jam_dipilih);

      $success_count = 0;
      $error_count = 0;

      // Loop untuk setiap obat yang dipilih
      foreach ($id_obat_list as $idobat) {
        // Escape untuk keamanan
        $idobat = mysqli_real_escape_string($koneksi, $idobat);

        // Ambil data penggunaan yang sudah ada
        $query_cek = mysqli_query($koneksi, "SELECT digunakan_pada FROM obat_rm WHERE idobat = '$idobat'");
        $data_obat = mysqli_fetch_assoc($query_cek);

        if ($data_obat) {
          $digunakan_lama = trim($data_obat['digunakan_pada']);

          // Jika sudah ada jam sebelumnya, pisahkan dan gabung dengan yang baru
          if (!empty($digunakan_lama)) {
            // Pecah jam lama menjadi array
            $jam_lama_array = array_map('trim', explode(',', $digunakan_lama));

            // Gabungkan dengan jam baru
            $jam_gabung_array = array_merge($jam_lama_array, $jam_dipilih);

            // Hilangkan duplikat dan urutkan
            $jam_gabung_array = array_unique($jam_gabung_array);

            // Urutkan jam secara kronologis
            usort($jam_gabung_array, function ($a, $b) {
              return strcmp($a, $b);
            });

            // Gabungkan kembali menjadi string
            $jam_final = implode(', ', $jam_gabung_array);
          } else {
            // Jika belum ada jam sebelumnya, gunakan jam baru saja
            $jam_final = $jam_baru;
          }

          // Update ke database
          $update_query = "UPDATE obat_rm SET digunakan_pada = '$jam_final' WHERE idobat = '$idobat'";

          if (mysqli_query($koneksi, $update_query)) {
            $success_count++;
          } else {
            $error_count++;
          }
        }
      }

      echo "<script>alert('Penggunaan obat berhasil disimpan!\\n\\nBerhasil: $success_count obat\\nGagal: $error_count obat');</script>";

      // Redirect untuk refresh halaman
      if (isset($_GET['igd'])) {
        echo "<script>location='index.php?halaman=lpo&igd&id=" . $_GET['id'] . "&idigd=" . $_GET['idigd'] . "&tgl=" . $_GET['tgl'] . "';</script>";
      } else {
        echo "<script>location='index.php?halaman=lpo&id=" . $_GET['id'] . "&inap&tgl=" . $_GET['tgl'] . "';</script>";
      }
    } else {
      echo "<script>alert('Silakan pilih minimal satu jam penggunaan!');</script>";
    }
  } else {
    echo "<script>alert('Silakan pilih obat yang akan dicatat penggunaannya!');</script>";
  }
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
<?php if (!isset($_GET['view'])) { ?>
  <main>
    <div class="">
      <div class="pagetitle">
        <h1>LAPORAN OBSERVASI PERAWAT</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="index.php?halaman=daftarrmedis" style="color:blue;">Rekam
                Medis</a></li>
            <li class="breadcrumb-item">Laporan Observasi Perawat</li>
          </ol>
        </nav>
      </div><!-- End Page Title -->

      <form class="row g-3" method="post" enctype="multipart/form-data">
        <div class="">
          <div class="row">
            <div class="col-md-12">
              <div class="card" style="margin-top:10px">
                <div class="card-body col-md-12">
                  <h5 class="card-title">Data Pasien</h5>
                  <h5 class="card-title">
                    <?php echo $pasien['nama_lengkap'] ?>(<b><?php echo $pasien['no_rm'] ?></b>) | TglLahir:
                    <?php echo date("d-m-Y", strtotime($pasien['tgl_lahir'])) ?> | Alamat: <?php echo $pasien['alamat'] ?>
                    <br> <?php if (!isset($_GET['igd'])) { ?>Kamar: <?php echo $jadwal['kamar'] ?> |<?php } ?> JK:
                    <?php if ($pasien["jenis_kelamin"] == 1) {
                      echo "Laki-Laki";
                    } else {
                      echo "Perempuan";
                    } ?>
                  </h5>
                  <div class="row">
                    <div class="col-md-6" style="margin-bottom:0px; visibility: hidden; height: 0.01px;">
                      <label for="inputName5" class="form-label">Nama Pasien</label>
                      <input type="text" class="form-control" name="pasien" id="inputName5"
                        value="<?php echo $pasien['nama_lengkap'] ?>" placeholder="Masukkan Nama Pasien" readonly>
                    </div>
                    <div class="col-md-6" style="margin-bottom:0px; visibility: hidden; height: 0.01px;">
                      <label for="inputName5" class="form-label">No RM</label>
                      <input type="text" class="form-control" id="inputName5" name="norm"
                        value="<?php echo $pasien['no_rm'] ?>" placeholder="Masukkan Nama Pasien" readonly>
                    </div>
                    <div class="col-md-6"
                      style="margin-top: 10px; margin-bottom:0px; visibility: hidden; height: 0.01px;">
                      <label for="inputName5" class="form-label">Tanggal Lahir</label>
                      <input type="text" class="form-control" id="inputName5" name="tgl_lahir"
                        value="<?php echo date("d-m-Y", strtotime($pasien['tgl_lahir'])) ?>"
                        placeholder="Masukkan Nama Pasien" readonly>
                    </div>
                    <div class="col-md-6"
                      style="margin-top: 10px; margin-bottom:0px; visibility: hidden; height: 0.01px;">
                      <label for="inputName5" class="form-label">Alamat</label>
                      <input type="text" class="form-control" id="inputName5" name="alamat"
                        value="<?php echo $pasien['alamat'] ?>" placeholder="Masukkan Nama Pasien" readonly>
                    </div>
                    <?php if (!isset($_GET['igd'])) { ?>
                      <div class="col-md-6"
                        style="margin-top: 10px; margin-bottom:0px; visibility: hidden; height: 0.01px;">
                        <label for="inputName5" class="form-label">Ruangan</label>
                        <input type="text" class="form-control" id="inputName5" name="kamar"
                          value="<?php echo $jadwal['kamar'] ?>" placeholder="Masukkan Nama Pasien">
                      </div>
                    <?php } ?>
                    <?php if ($pasien["jenis_kelamin"] == 1) { ?>
                      <div class="col-md-6"
                        style="margin-top: 10px; margin-bottom:0px; visibility: hidden; height: 0.01px;">
                        <label for="inputName5" class="form-label">Jenis Kelamin</label>
                        <input type="text" class="form-control" id="inputName5" name="jenis_kelamin" value="Laki-laki"
                          placeholder="Masukkan Nama Pasien" readonly>
                      </div>
                    <?php } else { ?>
                      <div class="col-md-6"
                        style="margin-top: 10px; margin-bottom:0px; visibility: hidden; height: 0.01px;">
                        <label for="inputName5" class="form-label">Jenis Kelamin</label>
                        <input type="text" class="form-control" id="inputName5" name="jenis_kelamin" value="Perempuan"
                          placeholder="Masukkan Nama Pasien" readonly>
                      </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <?php if (!isset($_GET['entriObat'])) { ?>
                <?php if (!isset($_GET['insertObatDokterIgd'])) { ?>
                  <div class="card shadow p-3" id="observasiZone">
                    <h5 class="card-title">OBSERVASI PERAWAT</h5>
                    <div class="row">
                      <div class="col-md-6">
                        <label for="">Diagnosa</label>
                        <input type="text" class="form-control mb-3" name="diagnosa" <?= $ConditionCopy != 0 ? "value='$dataCopy[diagnosa]'" : "" ?> id="" placeholder="Diagnosa">
                      </div>
                      <div class="col-md-6">
                        <label for="">Tanggal & Waktu</label>
                        <input type="datetime-local" class="form-control mb-3" name="tgl_waktu" <?= $ConditionCopy != 0 ? "value='$dataCopy[tgl_waktu]'" : "" ?> id="" placeholder="Tanggal dan Waktu">
                      </div>
                      <div class="col-md-6">
                        <label for="">Tensi Darah</label>
                        <input type="text" class="form-control mb-3" name="tensi" <?= $ConditionCopy != 0 ? "value='$dataCopy[tensi]'" : "" ?> id="" placeholder="Tensi Darah">
                      </div>
                      <div class="col-md-6">
                        <label for="">Suhu Tubuh</label>
                        <input type="text" class="form-control mb-3" name="suhu" <?= $ConditionCopy != 0 ? "value='$dataCopy[suhu]'" : "" ?> id="" placeholder="Suhu Tubuh">
                      </div>
                      <div class="col-md-6">
                        <label for="">Cairan Ke</label>
                        <input type="text" class="form-control mb-3" name="cairan" <?= $ConditionCopy != 0 ? "value='$dataCopy[cairan]'" : "" ?> id="" placeholder="Cairan Ke">
                      </div>
                      <div class="col-md-6">
                        <label for="">Volume Cairan</label>
                        <input type="text" class="form-control mb-3" name="volume" <?= $ConditionCopy != 0 ? "value='$dataCopy[volume]'" : "" ?> id="" placeholder="Volume Cairan">
                      </div>
                      <div class="col-md-6">
                        <label for="">Keadaan Umum</label>
                        <input type="text" class="form-control mb-3" name="keadaan_umum" <?= $ConditionCopy != 0 ? "value='$dataCopy[keadaan_umum]'" : "" ?> id="" placeholder="Keadaan Umum">
                      </div>
                      <div class="col-md-6">
                        <label for="">Keluhan Pasien</label>
                        <textarea name="keluhan_pasien" id="" class="form-control mb-2"
                          placeholder="Keluahan Pasien"><?= $ConditionCopy != 0 ? "$dataCopy[keluhan_pasien]" : "" ?></textarea>
                      </div>
                      <div class="col-md-6">
                        <label for="">Cairan Infus</label>
                        <input type="text" class="form-control mb-3" name="infus" <?= $ConditionCopy != 0 ? "value='$dataCopy[infus]'" : "" ?> id="" placeholder="Cairan Infus">
                      </div>
                      <div class="col-md-6">
                        <label for="">Tindakan</label>
                        <textarea name="tindakan" id="" class="form-control mb-2"
                          placeholder="Tindakan"><?= $ConditionCopy != 0 ? "$dataCopy[tindakan]" : "" ?></textarea>
                      </div>
                      <div class="col-md-6">
                        <label for="">Perawat</label>
                        <input type="text" class="form-control mb-3" name="perawat" <?= $ConditionCopy != 0 ? "value='$dataCopy[perawat]'" : "" ?> readonly value="<?= $petugas ?>" placeholder="">
                      </div>
                      <div class="col-md-12">
                        <label for="penunjang_foto" class="form-label">Pemeriksaan Penunjang (Foto)</label>
                        <input type="file" name="penunjang_foto[]" id="penunjang_foto" class="form-control" accept="image/*" capture="environment" multiple>
                        <small class="text-muted">Klik untuk membuka kamera atau pilih file. Bisa pilih multiple foto.</small>
                      </div>
                    </div>
                    <div class="text-center" style="margin-top: 10px; margin-bottom: 40px;">
                      <button type="submit" name="save" class="btn btn-primary">Simpan Dulu</button>
                      <button type="reset" class="btn btn-secondary">Reset</button>
                    </div>
                  </div>
                <?php } ?>

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
                    <b for="">Obat Injeksi </b>
                    <div>
                      <?php if ($_SESSION['admin']['level'] == 'igd' or $_SESSION['admin']['level'] == 'inap' or $_SESSION['admin']['level'] == 'dokter' or $_SESSION['admin']['level'] == 'sup') { ?>
                        <button type="button" onclick="changeJenis('Injeksi')" class="btn btn-primary btn-sm text-right"
                          data-bs-toggle="modal" data-bs-target="#exampleModal45">Add Jadi</button>
                      <?php } ?>
                      <?php if ($_SESSION['admin']['level'] == 'apoteker' or $_SESSION['admin']['level'] == 'racik' or $_SESSION['admin']['level'] == 'dokter' or $_SESSION['admin']['level'] == 'sup') { ?>
                        <button type="button" onclick="changeJenis('Injeksi')" class="btn btn-primary btn-sm text-right"
                          data-bs-toggle="modal" data-bs-target="#exampleModal2">Add Racik</button>
                      <?php } ?>
                      <?php if ($id['apoteker_check_at'] == null) { ?>
                      <?php } ?>
                    </div>
                    <div class="table-responsive">
                      <table class="table table-hover table-striped" style="font-size: 12px;">
                        <thead>
                          <tr>
                            <th></th>
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
                          if (isset($_GET['igd'])) {
                            $getLatLpo = $koneksi->query("SELECT * FROM lpo WHERE pasien = '$pasien[nama_lengkap]' AND norm = '$pasien[no_rm]' AND status = 'igd' GROUP BY date_format(created_at, '%Y-%m-%d') ORDER BY id_lpo DESC LIMIT 1")->fetch_assoc();
                            if (!isset($_GET['tglobat'])) {
                              $tgl = date('Y-m-d', strtotime($getLatLpo['created_at']));
                            } else {
                              $tgl = $_GET['tglobat'];
                            }
                            $whereTgl = " AND date_format(created_at, '%Y-%m-%d') = '$tgl'";
                            $injek = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND idigd='$_GET[idigd]' AND obat_igd = 'injeksi' ORDER BY idobat DESC");
                            $urlBase = "index.php?halaman=lpo&igd&id=" . htmlspecialchars($_GET['id']) . "&idigd=" . htmlspecialchars($_GET['idigd']) . "&tgl=" . htmlspecialchars($_GET['tgl']);
                          } else {
                            $getLatLpo = $koneksi->query("SELECT * FROM lpo WHERE pasien = '$pasien[nama_lengkap]' AND norm = '$pasien[no_rm]' AND status = 'inap' GROUP BY date_format(created_at, '%Y-%m-%d') ORDER BY id_lpo DESC LIMIT 1")->fetch_assoc();
                            if (!isset($_GET['tglobat'])) {
                              $tgl = date('Y-m-d', strtotime($getLatLpo['created_at']));
                            } else {
                              $tgl = $_GET['tglobat'];
                            }
                            $whereTgl = " AND date_format(created_at, '%Y-%m-%d') = '$tgl'";
                            $injek = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND tgl_pasien='$_GET[tgl]' AND obat_igd = 'injeksi' ORDER BY idobat DESC");
                            $urlBase = "index.php?halaman=lpo&id=" . htmlspecialchars($_GET['id']) . "&inap&tgl=" . htmlspecialchars($_GET['tgl']);
                          }
                          $noo = 1;
                          foreach ($injek as $in) {
                          ?>
                            <tr>
                              <td><input type="checkbox" name="idobatcheck[]" value="<?= $in['idobat'] ?>" id=""></td>
                              <td><?php echo $noo++; ?></td>
                              <td><?php echo $in["nama_obat"]; ?> <br> <span style="font-size: 10px;"><?= $in['digunakan_pada'] ?></span></td>
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
                                <?php if ($_SESSION['admin']['level'] == 'sup') { ?>
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
                      <div>
                        <input type="checkbox" class="ms-2" name="digunakan[]" id="" value="09:00"> 09:00
                        <input type="checkbox" class="ms-2" name="digunakan[]" id="" value="12:00"> 12:00
                        <input type="checkbox" class="ms-2" name="digunakan[]" id="" value="15:00"> 15:00
                        <input type="checkbox" class="ms-2" name="digunakan[]" id="" value="18:00"> 18:00
                        <input type="checkbox" class="ms-2" name="digunakan[]" id="" value="21:00"> 21:00
                        <input type="checkbox" class="ms-2" name="digunakan[]" id="" value="24:00"> 24:00
                        <input type="checkbox" class="ms-2" name="digunakan[]" id="" value="05:00"> 05:00
                        <input type="time" name="digunakan[]" class="h-100" id="">
                        <button class="btn btn-primary btn-sm text-right" type="submit" name="savePenggunaan">Simpan Penggunaan</button>
                      </div>
                    </div>
                  </div>
                </div>
                <br>
                <div class="col-md-12">
                  <div class="card shadow p-2 mb-2 mt-0">
                    <b for="">Obat Oral</b>
                    <div align="left">
                      <?php if ($_SESSION['admin']['level'] == 'apoteker' or $_SESSION['admin']['level'] == 'racik' or $_SESSION['admin']['level'] == 'dokter' or $_SESSION['admin']['level'] == 'sup') { ?>
                        <button type="button" onclick="changeJenis('Oral')" class="btn btn-primary btn-sm text-right"
                          data-bs-toggle="modal" data-bs-target="#exampleModal45">Add Jadi</button>
                        <button type="button" onclick="changeJenis('Oral')" class="btn btn-primary btn-sm text-right"
                          data-bs-toggle="modal" data-bs-target="#exampleModal2">Add Racik</button>
                      <?php } ?>
                      <?php if ($id['apoteker_check_at'] == null) { ?>
                      <?php } ?>
                    </div>
                    <div class="table-responsive">
                      <table class="table table-hover table-striped" style="font-size: 12px;">
                        <thead>
                          <tr>
                            <td></td>
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
                          if (isset($_GET['igd'])) {
                            $getLatLpo = $koneksi->query("SELECT * FROM lpo WHERE pasien = '$pasien[nama_lengkap]' AND norm = '$pasien[no_rm]' AND status = 'igd' GROUP BY date_format(created_at, '%Y-%m-%d') ORDER BY id_lpo DESC LIMIT 1")->fetch_assoc();
                            if (!isset($_GET['tglobat'])) {
                              $tgl = date('Y-m-d', strtotime($getLatLpo['created_at']));
                            } else {
                              $tgl = $_GET['tglobat'];
                            }
                            $whereTgl = " AND date_format(created_at, '%Y-%m-%d') = '$tgl'";
                            $oral = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND idigd='$_GET[idigd]' AND obat_igd = 'oral' ORDER BY idobat DESC");
                          } else {
                            $getLatLpo = $koneksi->query("SELECT * FROM lpo WHERE pasien = '$pasien[nama_lengkap]' AND norm = '$pasien[no_rm]' AND status = 'inap' GROUP BY date_format(created_at, '%Y-%m-%d') ORDER BY id_lpo DESC LIMIT 1")->fetch_assoc();
                            if (!isset($_GET['tglobat'])) {
                              $tgl = date('Y-m-d', strtotime($getLatLpo['created_at']));
                            } else {
                              $tgl = $_GET['tglobat'];
                            }
                            $whereTgl = " AND date_format(created_at, '%Y-%m-%d') = '$tgl'";
                            $oral = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND tgl_pasien='$_GET[tgl]' AND obat_igd = 'oral' ORDER BY idobat DESC");
                          }
                          $no = 1;
                          foreach ($oral as $or) {
                          ?>
                            <tr>
                              <td><input type="checkbox" name="idobatcheck[]" value="<?= $or['idobat'] ?>" id=""></td>
                              <td><?php echo $no++; ?></td>
                              <td><?php echo $or["nama_obat"]; ?> <br> <span style="font-size: 10px;"><?= $or['digunakan_pada'] ?></span></td>
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
                                <?php if ($_SESSION['admin']['level'] == 'sup') { ?>
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
                      <div>
                        <input type="checkbox" class="ms-2" name="digunakan[]" id="" value="09:00"> 09:00
                        <input type="checkbox" class="ms-2" name="digunakan[]" id="" value="12:00"> 12:00
                        <input type="checkbox" class="ms-2" name="digunakan[]" id="" value="15:00"> 15:00
                        <input type="checkbox" class="ms-2" name="digunakan[]" id="" value="18:00"> 18:00
                        <input type="checkbox" class="ms-2" name="digunakan[]" id="" value="21:00"> 21:00
                        <input type="checkbox" class="ms-2" name="digunakan[]" id="" value="24:00"> 24:00
                        <input type="checkbox" class="ms-2" name="digunakan[]" id="" value="05:00"> 05:00
                        <input type="time" name="digunakan[]" class="h-100" id="">
                        <button class="btn btn-primary btn-sm text-right" type="submit" name="savePenggunaan">Simpan Penggunaan</button>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- <div class="col-12">
                  <div class="d-flex justify-content-end mb-4 mt-4">
                    <a class="btn btn-sm btn-primary"
                      onclick="return confirm('Jika sudah yakin maka tombol tambah obat akan hilang, apakah anda yakin akan menyelesaikan inputan obat ?')"
                      href="index.php?halaman=lpo&id=<?= $_GET['id'] ?>&inap&tgl=<?= $_GET['tgl'] ?>&apotek=done">Obat Sudah
                      di-Input Semua dan Pasien Boleh Pulang</a>
                  </div>
                </div> -->
                <div class="col-md-12">
                  <div class="card shadow p-3">
                    <h5 class="card-title">Riwayat Observasi</h5>
                    <div class="row">
                      <table class="table">
                        <thead>
                          <tr>
                            <th>Pasien</th>
                            <th>Diagnosa</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Foto Penunjang</th>
                            <th>Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          if (isset($_GET['igd'])) {
                            $getlpo = $koneksi->query("SELECT * FROM lpo WHERE pasien = '$pasien[nama_lengkap]' AND norm = '$pasien[no_rm]' AND status = 'igd'");
                          } else {
                            $getlpo = $koneksi->query("SELECT * FROM lpo WHERE pasien = '$pasien[nama_lengkap]' AND norm = '$pasien[no_rm]' AND status = 'inap'");
                          }
                          ?>
                          <?php foreach ($getlpo as $data) { ?>
                            <?php
                            $datetimeString = "$data[tgl_waktu]";
                            $datetimeObject = date_create($datetimeString);
                            $tanggal = date_format($datetimeObject, "Y-m-d");
                            $jam = date_format($datetimeObject, "H:i:s");
                            ?>
                            <tr>
                              <td><?= $data['pasien'] ?></td>
                              <td><?= $data['diagnosa'] ?></td>
                              <td><?= $tanggal ?></td>
                              <td><?= $jam ?></td>
                              <td>
                                <?php
                                if (!empty($data['penunjang'])) {
                                  $foto_list = json_decode($data['penunjang'], true);
                                  if (is_array($foto_list) && count($foto_list) > 0) {
                                    echo '<span class="badge bg-success">' . count($foto_list) . ' foto</span>';
                                    echo '<br>';
                                    foreach (array_slice($foto_list, 0, 2) as $foto) {
                                      if (file_exists('../rawatinap/pemeriksaan_penunjang/' . $foto)) {
                                        echo '<img src="../rawatinap/pemeriksaan_penunjang/' . htmlspecialchars($foto) . '" style="width: 40px; height: 40px; object-fit: cover; margin: 2px; cursor: pointer;" onclick="window.open(this.src, \'_blank\')">';
                                      }
                                    }
                                  } else {
                                    echo '<span class="text-muted">-</span>';
                                  }
                                } else {
                                  echo '<span class="text-muted">-</span>';
                                }
                                ?>
                              </td>
                              <td>
                                <?php if (!isset($_GET['igd'])) { ?>
                                  <a class="btn btn-sm btn-primary"
                                    href="index.php?halaman=lpo&id=<?= $_GET['id'] ?>&inap&tgl=<?= $_GET['tgl'] ?>&view=<?= $data['id_lpo'] ?>"><i
                                      class="bi bi-eye"></i></a>
                                  <a href="index.php?halaman=lpo&id=<?= htmlspecialchars($_GET['id']) ?>&inap&tgl=<?= htmlspecialchars($_GET['tgl']) ?>&idlpo=<?= $data['id_lpo'] ?>#observasiZone"
                                    class="btn btn-sm btn-warning">Copy</a>
                                <?php } else { ?>
                                  <a class="btn btn-sm btn-primary"
                                    href="index.php?halaman=lpo&id=<?= $_GET['id'] ?>&igd&view=<?= $data['id_lpo'] ?>&idigd=<?= $_GET['idigd'] ?>"><i
                                      class="bi bi-eye"></i></a>
                                <?php } ?>
                              </td>
                            </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
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
              <?php } else { ?>
                <div class="card shadow-sm mb-2 p-2">
                  <h5><b>Entri Obat Jadi</b></h5>
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
                    echo "<script>window.location.href = 'index.php?halaman=rmedis&id=" . htmlspecialchars($_GET['id']) . "&tgl=" . htmlspecialchars($_GET['tgl']) . "&entriObat=" . htmlspecialchars($_GET['entriObat']) . "';</script>";
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
                      echo "<script>alert('Data berhasil disimpan ke database.'); window.location.href = 'index.php?halaman=lpo&id=" . htmlspecialchars($_GET['id']) . "&inap&tgl=" . htmlspecialchars($_GET['tgl']) . "';</script>";
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
          </div>
        </div>
      </form>

    </div>
  </main>
  <?php
  // Fungsi untuk mendapatkan ID obat berikutnya yang unik


  // Panggil fungsi untuk mendapatkan ID obat yang unik
  // $uniqueId = getUniqueId($koneksi);

  if (isset($_POST['save'])) {
    // Handle upload foto pemeriksaan penunjang
    $foto_penunjang_array = array();

    // Proses upload foto baru
    if (isset($_FILES['penunjang_foto']) && !empty($_FILES['penunjang_foto']['name'][0])) {
      $upload_dir = '../rawatinap/pemeriksaan_penunjang/';

      // Buat folder jika belum ada
      if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
      }

      $total_files = count($_FILES['penunjang_foto']['name']);

      for ($i = 0; $i < $total_files; $i++) {
        if ($_FILES['penunjang_foto']['error'][$i] == 0) {
          $file_name = $_FILES['penunjang_foto']['name'][$i];
          $file_tmp = $_FILES['penunjang_foto']['tmp_name'][$i];
          $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

          // Generate nama file unik
          $new_file_name = 'lpo_' . $_POST['norm'] . '_' . time() . '_' . $i . '.' . $file_ext;
          $upload_path = $upload_dir . $new_file_name;

          // Upload file
          if (move_uploaded_file($file_tmp, $upload_path)) {
            $foto_penunjang_array[] = $new_file_name;
          }
        }
      }
    }

    // Convert array ke JSON untuk disimpan di database
    $penunjang_json = mysqli_real_escape_string($koneksi, json_encode($foto_penunjang_array));

    if (isset($_GET['igd'])) {

      $koneksi->query("INSERT INTO lpo (pasien, norm, tgl_lahir, alamat, kamar, jenis_kelamin, diagnosa, tgl_waktu, tensi, suhu, cairan, volume, keadaan_umum, keluhan_pasien, infus, obat_injeksi, obat_oral, tindakan, perawat, status, idigd, penunjang) VALUES ('$_POST[pasien]', '$_POST[norm]', '$_POST[tgl_lahir]', '$_POST[alamat]', '$_POST[kamar]', '$_POST[jenis_kelamin]', '$_POST[diagnosa]', '$_POST[tgl_waktu]', '$_POST[tensi]', '$_POST[suhu]', '$_POST[cairan]', '$_POST[volume]', '$_POST[keadaan_umum]', '$_POST[keluhan_pasien]', '$_POST[infus]', '$_POST[obat_injeksi]', '$_POST[obat_oral]', '$_POST[tindakan]', '$_POST[perawat]', 'igd', '$_GET[idigd]', '$penunjang_json')");
    } else {
      $koneksi->query("INSERT INTO lpo (pasien, norm, tgl_lahir, alamat, kamar, jenis_kelamin, diagnosa, tgl_waktu, tensi, suhu, cairan, volume, keadaan_umum, keluhan_pasien, infus, obat_injeksi, obat_oral, tindakan, perawat, status, penunjang) VALUES ('$_POST[pasien]', '$_POST[norm]', '$_POST[tgl_lahir]', '$_POST[alamat]', '$_POST[kamar]', '$_POST[jenis_kelamin]', '$_POST[diagnosa]', '$_POST[tgl_waktu]', '$_POST[tensi]', '$_POST[suhu]', '$_POST[cairan]', '$_POST[volume]', '$_POST[keadaan_umum]', '$_POST[keluhan_pasien]', '$_POST[infus]', '$_POST[obat_injeksi]', '$_POST[obat_oral]', '$_POST[tindakan]', '$_POST[perawat]', 'inap', '$penunjang_json')");
    }
    if (isset($_GET['igd'])) {
      echo "
                <script>
                alert('Yey Berhasil!');
                document.location.href='index.php?halaman=lpo&id=$_GET[id]&idigd=$_GET[idigd]&tgl=$_GET[tgl]&igd';
                </script>
                ";
    } else {
      echo "
                <script>
                alert('Yey Berhasil!');
                document.location.href='index.php?halaman=lpo&id=$_GET[id]&inap&tgl=$_GET[tgl]';
                </script>
                ";
    }
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
                  $id = isset($_POST["idrm"]) && $_POST['idrm'] !== '' ? $_POST["idrm"] : $_GET["idigd"];
                  $resep = 'Resep' . ' ' . $id . ' ' . $uniqueId;

                  if (!isset($_GET['idigd'])) {
                    $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran, ket, petugas, shiftinap ) VALUES ('$_POST[idrm]', '$tanggal', '$biaya', '$harga', '$resep', '$petugas', '" . $_SESSION['shift'] . "') ");
                  } else {
                    $koneksi->query("INSERT INTO igddetail (id, tgl, biaya, besaran, ket, petugas, shiftinap ) VALUES ('$_GET[idigd]', '$tanggal', '$biaya', '$harga', '$resep', '$petugas','" . $_SESSION['shift'] . "') ");
                  }

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

    if (isset($_GET['igd'])) {
      echo "
              <script>
                document.location.href='" . getFullUrl() . "';
              </script>
            ";
    } else {
      echo "
              <script>
                document.location.href='" . getFullUrl() . "';
              </script>
            ";
    }
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
      $id = isset($_POST["idrm"]) && $_POST['idrm'] !== '' ? $_POST["idrm"] : $_GET["idigd"];
      $resep = 'Resep' . ' ' . $id . ' ' . $uniqueId;

      if (!isset($_GET['idigd'])) {
        $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran, ket, petugas, shiftinap) VALUES ('$_POST[idrm]', '$tanggal', '$biaya', '$harga', '$resep', '$petugas', '" . $_SESSION['shift'] . "') ");
      } else {
        $koneksi->query("INSERT INTO igddetail (id, tgl, biaya, besaran, ket, petugas, shiftinap) VALUES ('$_GET[idigd]', '$tanggal', '$biaya', '$harga', '$resep', '$petugas','" . $_SESSION['shift'] . "') ");
      }

      $koneksi->query("INSERT INTO obat_rm SET idobat='$uniqueId', catatan_obat = '$catatan_obat[$i]', nama_obat = '$ObatKode[nama_obat]', kode_obat = '$nama[$i]', jml_dokter = '$jml_dokter[$i]', dosis1_obat = '$dosis1_obat[$i]', dosis2_obat = '$dosis2_obat[$i]', per_obat = '$per_obat[$i]', durasi_obat = '$durasi_obat[$i]', tgl_pasien = '$_GET[tgl]', petunjuk_obat = '$petunjuk_obat[$i]', jenis_obat = '$jenis_obat[$i]', idigd = '$_GET[idigd]', obat_igd = '$_POST[jenis]', idrm = '$_GET[id]', petugas = '" . $_SESSION['admin']['namalengkap'] . "'");
    }

    if (isset($_GET['igd'])) {
      echo "
          <script>
            document.location.href='" . getFullUrl() . "';
          </script>
        ";
    } else {
      echo "
          <script>
            document.location.href='" . getFullUrl() . "';
          </script>
        ";
    }
  }

  if (isset($_GET['idObat'])) {
    $idObat = $_GET['idObat'];
    $koneksi->query("DELETE FROM obat_rm WHERE idobat = '$idObat'");
    if (!isset($_GET['idigd'])) {
      $koneksi->query("DELETE FROM rawatinapdetail WHERE TRIM(SUBSTRING_INDEX(ket, ' ', -1)) = '$idObat'");
    } else {
      $koneksi->query("DELETE FROM igddetail WHERE TRIM(SUBSTRING_INDEX(ket, ' ', -1)) = '$idObat'");
    }
    echo "<script>alert('Successfully')</script>";
    echo "<script>document.location.href='" . $urlBase . "'</script>";
  }
  ?>
<?php } else { ?>
  <?php $lpo = $koneksi->query("SELECT * FROM lpo WHERE id_lpo = '$_GET[view]'")->fetch_assoc(); ?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>KHM WONOREJO</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script rel="javascript" type="text/javascript" href="js/jquery-1.11.3.min.js"></script>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>


  </head>

  <body>
    <main>
      <div class="container">
        <div class="pagetitle">
          <h1>LAPORAN OBSERVASI PERAWAT</h1>
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item active"><a href="index.php?halaman=daftarrmedis" style="color:blue;">Rekam
                  Medis</a></li>
              <li class="breadcrumb-item">Laporan Observasi Perawat</li>
            </ol>
          </nav>
        </div><!-- End Page Title -->
        <form class="row g-3" method="post" enctype="multipart/form-data">
          <div class="container">
            <div class="row">
              <div class="col-md-12">
                <?php if (isset($_GET['igd'])) { ?>
                  <a href="index.php?halaman=lpo&igd&id=<?= $_GET['id'] ?>&inap" class="btn btn-sm btn-dark">Kembali</a>
                <?php } else { ?>
                  <a href="index.php?halaman=lpo&id=<?= $_GET['id'] ?>&inap&tgl=<?= $_GET['tgl'] ?>"
                    class="btn btn-sm btn-dark">Kembali</a>
                <?php } ?>
                <div class="card" style="margin-top:10px">
                  <div class="card-body col-md-12">
                    <h5 class="card-title">Data Pasien</h5>
                    <!-- Multi Columns Form -->
                    <div class="row">
                      <div class="col-md-6">
                        <label for="inputName5" class="form-label">Nama Pasien</label>
                        <input type="text" class="form-control" name="pasien" id="inputName5"
                          value="<?php echo $pasien['nama_lengkap'] ?>" placeholder="Masukkan Nama Pasien" readonly>
                      </div>
                      <div class="col-md-6" style="margin-bottom:20px;">
                        <label for="inputName5" class="form-label">No RM</label>
                        <input type="text" class="form-control" id="inputName5" name="norm"
                          value="<?php echo $pasien['no_rm'] ?>" placeholder="Masukkan Nama Pasien" readonly>
                      </div>
                      <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                        <label for="inputName5" class="form-label">Tanggal Lahir</label>
                        <input type="text" class="form-control" id="inputName5" name="tgl_lahir"
                          value="<?php echo date("d-m-Y", strtotime($pasien['tgl_lahir'])) ?>"
                          placeholder="Masukkan Nama Pasien" readonly>
                      </div>
                      <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                        <label for="inputName5" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="inputName5" name="alamat"
                          value="<?php echo $pasien['alamat'] ?>" placeholder="Masukkan Nama Pasien" readonly>
                      </div>
                      <?php if (!isset($_GET['igd'])) { ?>
                        <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                          <label for="inputName5" class="form-label">Ruangan</label>
                          <input type="text" class="form-control" id="inputName5" name="kamar"
                            value="<?php echo $jadwal['kamar'] ?>" placeholder="Masukkan Nama Pasien">
                        </div>
                      <?php } ?>
                      <?php if ($pasien["jenis_kelamin"] == 1) { ?>
                        <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                          <label for="inputName5" class="form-label">Jenis Kelamin</label>
                          <input type="text" class="form-control" id="inputName5" name="jenis_kelamin" value="Laki-laki"
                            placeholder="Masukkan Nama Pasien" readonly>
                        </div>
                      <?php } else { ?>
                        <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                          <label for="inputName5" class="form-label">Jenis Kelamin</label>
                          <input type="text" class="form-control" id="inputName5" name="jenis_kelamin" value="Perempuan"
                            placeholder="Masukkan Nama Pasien" readonly>
                        </div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
                <br>
                <div class="card shadow p-3">
                  <h5 class="card-title">OBSERVASI PERAWAT</h5>
                  <div class="row">
                    <div class="col-md-6">
                      <label for="">Diagnosa</label>
                      <input type="text" class="form-control mb-3" readonly value="<?= $lpo['diagnosa'] ?>"
                        name="diagnosa" id="" placeholder="Diagnosa">
                    </div>
                    <div class="col-md-6">
                      <label for="">Tanggal & Waktu</label>
                      <input type="datetime-local" readonly value="<?= $lpo['tgl_waktu'] ?>" class="form-control mb-3"
                        name="tgl_waktu" id="" placeholder="Tanggal dan Waktu">
                    </div>
                    <div class="col-md-6">
                      <label for="">Tensi Darah</label>
                      <input type="text" class="form-control mb-3" readonly value="<?= $lpo['tensi'] ?>" name="tensi"
                        id="" placeholder="Tensi Darah">
                    </div>
                    <div class="col-md-6">
                      <label for="">Suhu Tubuh</label>
                      <input type="text" class="form-control mb-3" readonly value="<?= $lpo['suhu'] ?>" name="suhu" id=""
                        placeholder="Suhu Tubuh">
                    </div>
                    <div class="col-md-6">
                      <label for="">Cairan Ke</label>
                      <input type="text" class="form-control mb-3" readonly value="<?= $lpo['cairan'] ?>" name="cairan"
                        id="" placeholder="Cairan Ke">
                    </div>
                    <div class="col-md-6">
                      <label for="">Volume Cairan</label>
                      <input type="text" class="form-control mb-3" readonly value="<?= $lpo['volume'] ?>" name="volume"
                        id="" placeholder="Volume Cairan">
                    </div>
                    <div class="col-md-6">
                      <label for="">Keadaan Umum</label>
                      <input type="text" class="form-control mb-3" readonly value="<?= $lpo['keadaan_umum'] ?>"
                        name="keadaan_umum" id="" placeholder="Keadaan Umum">
                    </div>
                    <div class="col-md-6">
                      <label for="">Keluhan Pasien</label>
                      <textarea readonly name="keluhan_pasien" id="" class="form-control mb-2"
                        placeholder="Keluahan Pasien"><?= $lpo['keluhan_pasien'] ?></textarea>
                    </div>
                    <div class="col-md-6">
                      <label for="">Cairan Infus</label>
                      <input type="text" class="form-control mb-3" readonly value="<?= $lpo['infus'] ?>" name="infus"
                        id="" placeholder="Cairan Infus">
                    </div>
                    <!-- <div class="col-md-6">
                                            <label for="">Obat Injeksi</label>
                                            <textarea readonly name="obat_injeksi" id="" class="form-control mb-2" placeholder="Obat Injeksi"><?= $lpo['obat_injeksi'] ?></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="">Obat Oral</label>
                                            <textarea readonly name="obat_oral" id="" class="form-control mb-2" placeholder="Obat Oral"><?= $lpo['obat_oral'] ?></textarea>
                                        </div> -->
                    <div class="col-md-6">
                      <label for="">Tindakan</label>
                      <textarea readonly name="tindakan" id="" class="form-control mb-2"
                        placeholder="Tindakan"><?= $lpo['tindakan'] ?></textarea>
                    </div>
                    <div class="col-md-6">
                      <label for="">Perawat</label>
                      <input type="text" class="form-control mb-3" readonly value="<?= $lpo['perawat'] ?>" name="perawat"
                        readonly value="<?= $petugas ?>" placeholder="">
                    </div>
                    <div class="col-md-12">
                      <label for="" class="form-label">Pemeriksaan Penunjang (Foto)</label>
                      <?php
                      // Tampilkan foto di mode view
                      if (!empty($lpo['penunjang'])) {
                        $foto_list = json_decode($lpo['penunjang'], true);
                        if (is_array($foto_list) && count($foto_list) > 0) {
                          echo '<div class="row">';
                          foreach ($foto_list as $foto) {
                            if (file_exists('../rawatinap/pemeriksaan_penunjang/' . $foto)) {
                              echo '<div class="col-md-3 mb-2">';
                              echo '<img src="../rawatinap/pemeriksaan_penunjang/' . htmlspecialchars($foto) . '" class="img-thumbnail" style="max-height: 200px; cursor: pointer;" onclick="window.open(this.src, \'_blank\')"><br>';
                              echo '<small>' . htmlspecialchars($foto) . '</small>';
                              echo '</div>';
                            }
                          }
                          echo '</div>';
                        } else {
                          echo '<p class="text-muted">Tidak ada foto</p>';
                        }
                      } else {
                        echo '<p class="text-muted">Tidak ada foto</p>';
                      }
                      ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card shadow p-3">
            <h5 class="card-title">Obat Oral</h5>
            <?php
            $dateLPO = date("H:00", strtotime($lpo['tgl_waktu']));

            $whereClause = "";

            if ($dateLPO == "24:00" or $dateLPO == "00:00" or $dateLPO == "05:00") {
              $whereClause = " AND (DATE(created_at) = '" . date('Y-m-d', strtotime($lpo['tgl_waktu'] . ' -1 day')) . "' AND digunakan_pada LIKE '%$dateLPO%')";
            } else {
              $whereClause = " AND (DATE(created_at) = '" . date('Y-m-d', strtotime($lpo['tgl_waktu'])) . "' AND digunakan_pada LIKE '%$dateLPO%')";
            }

            if (isset($_GET['igd'])) {
              $oral = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND idigd='$_GET[idigd]' AND obat_igd = 'oral' " . $whereClause);
            } else {
              $oral = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND tgl_pasien='$_GET[tgl]' AND obat_igd = 'oral' " . $whereClause);
            }
            ?>


            <br>
            <div id="employee_table">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th width="5%">No.</th>
                    <th width="50%">Obat</th>
                    <th width="50%">Kode Obat</th>
                    <th width="50%">Jumlah Obat</th>
                    <th width="20%">Dosis</th>
                    <th width="20%">Jenis</th>
                    <th width="20%">Durasi</th>
                    <!-- <th width="20%"></th> -->
                  </tr>
                </thead>
                <tbody>

                  <?php $no = 1 ?>

                  <?php foreach ($oral as $obat): ?>

                    <tr>
                      <td><?php echo $no; ?></td>
                      <td style="margin-top:10px;"><?php echo $obat["nama_obat"]; ?></td>
                      <td style="margin-top:10px;"><?php echo $obat["kode_obat"]; ?></td>
                      <td style="margin-top:10px;"><?php echo $obat["jml_dokter"]; ?></td>
                      <td style="margin-top:10px;"><?php echo $obat["dosis1_obat"]; ?> X <?php echo $obat["dosis2_obat"]; ?>
                        <?php echo $obat["per_obat"]; ?>
                      </td>
                      <td style="margin-top:10px;"><?php echo $obat["jenis_obat"]; ?> <?php echo $obat["racik"]; ?></td>
                      <td style="margin-top:10px;"><?php echo $obat["durasi_obat"]; ?> hari</td>
                      <!-- <td style="margin-top:10px;"> <button type="button" class="btn btn-primary text-right" data-bs-toggle="modal" data-bs-target="#exampleModalEdit<?php echo $obat["idobat"]; ?>">Edit</button></td> -->
                    </tr>
                  <?php endforeach ?>
              </table>
            </div>

            <h5 class="card-title">Obat Injeksi</h5>

            <?php if (isset($_GET['igd'])) {

              $injek = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND idigd='$_GET[idigd]' AND obat_igd = 'injeksi' " . $whereClause);
            } else {
              $injek = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND tgl_pasien='$_GET[tgl]' AND obat_igd = 'injeksi' " . $whereClause);
            }

            ?>
            <br>
            <div id="employee_table">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th width="5%">No.</th>
                    <th width="50%">Obat</th>
                    <th width="50%">Kode Obat</th>
                    <th width="50%">Jumlah Obat</th>
                    <th width="20%">Dosis</th>
                    <th width="20%">Jenis</th>
                    <th width="20%">Durasi</th>
                    <!-- <th width="20%"></th> -->
                  </tr>
                </thead>
                <tbody>

                  <?php $no = 1 ?>

                  <?php foreach ($injek as $obat): ?>

                    <tr>
                      <td><?php echo $no; ?></td>
                      <td style="margin-top:10px;"><?php echo $obat["nama_obat"]; ?></td>
                      <td style="margin-top:10px;"><?php echo $obat["kode_obat"]; ?></td>
                      <td style="margin-top:10px;"><?php echo $obat["jml_dokter"]; ?></td>
                      <td style="margin-top:10px;"><?php echo $obat["dosis1_obat"]; ?> X <?php echo $obat["dosis2_obat"]; ?>
                        <?php echo $obat["per_obat"]; ?>
                      </td>
                      <td style="margin-top:10px;"><?php echo $obat["jenis_obat"]; ?> <?php echo $obat["racik"]; ?></td>
                      <td style="margin-top:10px;"><?php echo $obat["durasi_obat"]; ?> hari</td>
                      <!-- <td style="margin-top:10px;"> <button type="button" class="btn btn-primary text-right" data-bs-toggle="modal" data-bs-target="#exampleModalEdit<?php echo $obat["idobat"]; ?>">Edit</button></td> -->
                    </tr>
                  <?php endforeach ?>
              </table>
            </div>
            </from>
            <?php
            if (isset($_POST['save'])) {
              $koneksi->query("INSERT INTO lpo (pasien, norm, tgl_lahir, alamat, kamar, jenis_kelamin, diagnosa, tgl_waktu, tensi, suhu, cairan, volume, keadaan_umum, keluhan_pasien, infus, obat_injeksi, obat_oral, tindakan, perawat) VALUES ('$_POST[pasien]', '$_POST[norm]', '$_POST[tgl_lahir]', '$_POST[alamat]', '$_POST[kamar]', '$_POST[jenis_kelamin]', '$_POST[diagnosa]', '$_POST[tgl_waktu]', '$_POST[tensi]', '$_POST[suhu]', '$_POST[cairan]', '$_POST[volume]', '$_POST[keadaan_umum]', '$_POST[keluhan_pasien]', '$_POST[infus]', '$_POST[obat_injeksi]', '$_POST[obat_oral]', '$_POST[tindakan]', 'perawat')");

              echo "
                            <script>
                                alert('BERHASIL MENAMBAHKAN OBSERVASI');
                                document.location.href='index.php?halaman=lpo&id=$_GET[id]&inap&tgl=$_GET[tgl]';
                            </script>
                        ";
            }
            ?>
          </div>
    </main>
  </body>

  </html>
<?php } ?>