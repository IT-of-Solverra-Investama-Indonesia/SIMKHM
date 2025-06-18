<?php
$date = date("Y-m-d");
date_default_timezone_set('Asia/Jakarta');
$username = $_SESSION['admin']['username'];
$petugas = $_SESSION['admin']['namalengkap'];
$ambil = $koneksi->query("SELECT * FROM admin  WHERE username='$username';");
$pasien = $koneksi->query("SELECT * FROM pasien  WHERE no_rm='$_GET[id]';")->fetch_assoc();
$jadwal = $koneksi->query("SELECT * FROM registrasi_rawat  WHERE no_rm='$_GET[id]' AND date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' ")->fetch_assoc();
if (isset($_GET['statusPulang'])) {
  $koneksi->query("UPDATE registrasi_rawat SET status_antri = 'Pulang' WHERE idrawat = '$jadwal[idrawat]'");
  echo "
      <script>
        alert('Successfully');
        document.location.href='index.php?halaman=pulang&id=$_GET[id]&inap&tgl=$_GET[tgl]';
      </script>
    ";
}
function konversiNomorHP($nomor)
{
  // Hilangkan semua karakter selain angka
  $nomor = preg_replace('/[^0-9]/', '', $nomor);

  // Jika nomor diawali dengan '0', ganti dengan '62'
  if (substr($nomor, 0, 1) === '0') {
    $nomor = '62' . substr($nomor, 1);
  }
  // Jika sudah diawali dengan '62', biarkan
  else if (substr($nomor, 0, 2) === '62') {
    // do nothing
  }
  // Jika diawali dengan '8', anggap sebagai '08' lalu ubah ke '628'
  else if (substr($nomor, 0, 1) === '8') {
    $nomor = '62' . $nomor;
  } else {
    // Format tidak dikenali, bisa dikembalikan null atau aslinya
    return null;
  }

  return $nomor;
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <script rel="javascript" type="text/javascript" href="js/jquery-1.11.3.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
  <main>
    <div class="">
      <div class="pagetitle">
        <h1>PERENCANAAN PASIEN PULANG (DISCHARGE PLANNING)</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="index.php?halaman=daftarrmedis" style="color:blue;">Rekam Medis</a></li>
            <li class="breadcrumb-item">Perencanaan Pulang</li>
          </ol>
        </nav>
      </div><!-- End Page Title -->
      <!-- <?= $start ?> -->
      <form class="row g-3" method="post" enctype="multipart/form-data">
        <div class="container">
          <div class="row">
            <div class="col-md-12">

              <div class="card" style="margin-top:10px">
                <div class="card-body col-md-12">
                  <h5 class="card-title">Data Pasien</h5>
                  <div class="row">
                    <div class="col-md-6">
                      <label for="inputName5" class="form-label">Nama Pasien</label>
                      <input type="text" class="form-control" name="pasien" id="inputName5" value="<?php echo $pasien['nama_lengkap'] ?>" placeholder="Masukkan Nama Pasien" readonly>
                    </div>
                    <div class="col-md-6" style="margin-bottom:20px;">
                      <label for="inputName5" class="form-label">No RM</label>
                      <input type="text" class="form-control" id="inputName5" name="jadwal" value="<?php echo $pasien['no_rm'] ?>" placeholder="Masukkan Nama Pasien" readonly>
                    </div>
                    <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                      <label for="inputName5" class="form-label">Tanggal Lahir</label>
                      <input type="text" class="form-control" id="inputName5" name="jadwal" value="<?php echo date("d-m-Y", strtotime($pasien['tgl_lahir'])) ?>" placeholder="Masukkan Nama Pasien" readonly>
                    </div>
                    <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                      <label for="inputName5" class="form-label">Alamat</label>
                      <input type="text" class="form-control" id="inputName5" name="jadwal" value="<?php echo $pasien['alamat'] ?>" placeholder="Masukkan Nama Pasien" readonly>
                    </div>
                    <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                      <label for="inputName5" class="form-label">Ruangan</label>
                      <input type="text" class="form-control" id="inputName5" name="kamar" value="<?php echo $jadwal['kamar'] ?>" placeholder="Masukkan Nama Pasien">
                    </div>
                    <?php if ($pasien["jenis_kelamin"] == 1) { ?>
                      <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                        <label for="inputName5" class="form-label">Jenis Kelamin</label>
                        <input type="text" class="form-control" id="inputName5" name="jadwal" value="Laki-laki" placeholder="Masukkan Nama Pasien" readonly>
                      </div>
                    <?php } else { ?>
                      <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                        <label for="inputName5" class="form-label">Jenis Kelamin</label>
                        <input type="text" class="form-control" id="inputName5" name="jadwal" value="Perempuan" placeholder="Masukkan Nama Pasien" readonly>
                      </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <?php
              $cekOp = $koneksi->query("SELECT *, COUNT(*) as jumlah FROM pulang WHERE norm = '$_GET[id]' AND tgl_masuk = '$_GET[tgl]'  AND pasien !=''")->fetch_assoc();
              $cekJumRegist = $koneksi->query("SELECT *, COUNT(*) as jumlah FROM registrasi_rawat WHERE no_rm='$_GET[id]' AND date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' ")->fetch_assoc();
              if ($cekOp['jumlah'] < $cekJumRegist['jumlah']) {
              ?>
                <div class="card">
                  <div class="card-body">
                    <div style="margin-bottom:1px; margin-top:30px">
                      <h6 class="card-title">Pemberian Edukasi PERENCANAAN PASIEN PULANG (DISCHARGE PLANNING)</h6>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <label for="inputName5" class="form-label">Tgl MRS</label>
                        <input type="datetime" class="form-control" id="inputName5" name="tgl_mrs" value="<?= date("Y-m-d H:i:s") ?>">
                      </div>
                      <div class="col-md-6">
                        <label for="inputName5" class="form-label">Tgl KRS</label>
                        <input type="datetime" class="form-control" id="inputName5" name="tgl_krs" value="<?= date("Y-m-d H:i:s") ?>">
                      </div>
                      <div class="col-md-6" style="margin-top:20px;">
                        <label for="inputName5" class="form-label">Diagnosa MRS</label>
                        <input type="text" name="diag_mrs" class="form-control">
                      </div>
                      <div class="col-md-6" style="margin-top:20px;">
                        <label for="inputName5" class="form-label">Diagnosa KRS</label>
                        <input type="text" name="diag_krs" class="form-control">
                      </div>
                      <div class="col-md-12" style="margin-top:20px;">
                        <label for="inputName5" class="form-label">Diagnosa Keperawatan</label>
                        <textarea name="diag_prwt" id="" style="width:100%; height:80px"></textarea>
                      </div>
                      <div class="col-md-12" style="margin-top:20px;">
                        <label for="inputName5" class="form-label">Pengobatan yang dilanjutkan di rumah dan jumlahnya :</label>
                        <textarea name="lanjut_rmh" id="" style="width:100%; height:150px"></textarea>
                      </div>
                      <div class="col-md-12" style="margin-top:20px;">
                        <label for="inputName5" class="form-label">Aturan Diet / Nutrisi </label>
                        <textarea name="diet" id="" style="width:100%; height:80px"></textarea>
                      </div>
                      <div class="col-md-12" style="margin-top:20px;">
                        <label for="inputName5" class="form-label">Aktivitas dan istirahat </label>
                        <textarea name="istirahat" id="" style="width:100%; height:80px"></textarea>
                      </div>
                      <div class="col-md-12" style="margin-top:20px;">
                        <label for="inputName5" class="form-label">Jadwal kontrol berikutnya</label>
                        <input name="kontrol" id="" class="form-control" type="date"></input>
                      </div>
                      <div class="col-md-12" style="margin-top:20px;">
                        <label for="inputName5" class="form-label">Diberikan pada pasien / keluarga</label><br>
                        <input type="checkbox" name="diberikan[]" value="Obat-obatan"> Obat-obatan</input><br>
                        <input type="checkbox" name="diberikan[]" value="Hasil Laboratorium"> Hasil Laboratorium</input><br>
                        <input type="checkbox" name="diberikan[]" value="Foto Rontgent"> Foto Rontgent</input><br>
                        <input type="checkbox" name="diberikan[]" value="ECG"> ECG</input><br>
                        <input type="checkbox" name="diberikan[]" value="Lainnya"> Lainnya</input><br>
                      </div>
                      <div class="col-md-12" style="margin-top:20px;">
                        <label for="inputName5" class="form-label">Dipulangkan dari Klinik Husada Mulia dalam keadaan</label>
                        <select id="pilRujuk" name="keadaan" class="form-select">
                          <option value="">Pilih</option>
                          <option value="Sembuh">1.Sembuh</option>
                          <option value="Meneruskan dg berobat jalan">2. Meneruskan dg berobat jalan</option>
                          <option value="Pulang Paksa">3.Pulang Paksa</option>
                          <option value="Meninggal">4.Meninggal</option>
                          <option value="Kabur">5.Kabur</option>
                          <option value="Rujuk">6.Rujuk</option>
                        </select>
                      </div>
                      <div class="hidden mt-2" id="rjk">
                        <div class="row">
                          <div class="col-md-12">
                            <input type="text" placeholder="Tujuan Rujukan" class="form-control" name="rujuk">
                          </div>
                        </div>
                      </div>
                      <style>
                        .hidden {
                          display: hidden;
                          height: 0px;
                          overflow: hidden;
                        }
                      </style>
                      <div class="col-md-12" style="margin-top:20px;">
                        <label for="inputName5" class="form-label">Keterangan Lainnya</label>
                        <textarea name="lain" id="" style="width:100%; height:80px"></textarea>
                      </div>
                      <div class="col-md-12" style="margin-top:20px;">
                        <label for="inputName5" class="form-label">Pasien/Keluarga</label>
                        <input name="keluarga" id="" class="form-control" type="text"></input>
                      </div>
                      <div class="col-md-12" style="margin-top:20px;">
                        <label for="inputName5" class="form-label">Kepala Ruangan</label>
                        <input name="kep_ruang" id="" class="form-control" type="text"></input>
                      </div>
                      <div class="col-md-12" style="margin-top:20px;">
                        <label for="inputName5" class="form-label">Perawat</label>
                        <input name="perawat" id="" class="form-control" type="text"></input>
                      </div>
                      <div class="col-md-12" style="margin-top:20px;">
                        <label for="inputName5" class="form-label">Tgl Input</label>
                        <input name="tgl" id="" class="form-control" type="date" value="<?= date("Y-m-d") ?>"></input>
                      </div>
                      <script>
                        document.getElementById('pilRujuk').addEventListener('change', function() {
                          var formLain = document.getElementById('rjk');
                          if (this.value === 'Rujuk') {
                            formLain.classList.remove('hidden');
                          } else {
                            formLain.classList.add('hidden');
                          }
                        });
                      </script>
                    </div>
                  </div>
                </div>
            </div>
          </div>
        </div>
        <div class="text-center" style="margin-top: -10px; margin-bottom: 40px;">
          <button type="submit" name="save" class="btn btn-primary">Simpan</button>
          <button type="reset" class="btn btn-secondary">Reset</button>
        </div>
      <?php } else { ?>
        <div class="card">
          <div class="card-body">
            <div style="margin-bottom:1px; margin-top:30px">
              <h6 class="card-title">Pemberian Edukasi PERENCANAAN PASIEN PULANG (DISCHARGE PLANNING)</h6>
            </div>
            <div class="row">
              <div class="col-md-6">
                <label for="inputName5" class="form-label">Tgl MRS</label>
                <input type="datetime" class="form-control" id="inputName5" name="tgl_mrs" value="<?= $cekOp["tgl_mrs"] ?>">
              </div>
              <div class="col-md-6">
                <label for="inputName5" class="form-label">Tgl KRS</label>
                <input type="datetime" class="form-control" id="inputName5" name="tgl_krs" value="<?= $cekOp["tgl_krs"] ?>">
              </div>
              <div class="col-md-6" style="margin-top:20px;">
                <label for="inputName5" class="form-label">Diagnosa MRS</label>
                <input type="text" name="diag_mrs" class="form-control" value="<?= $cekOp["diag_mrs"] ?>">
              </div>


              <div class="col-md-6" style="margin-top:20px;">
                <label for="inputName5" class="form-label">Diagnosa KRS</label>
                <input type="text" name="diag_krs" class="form-control" value="<?= $cekOp["diag_krs"] ?>">
              </div>

              <hr>

              <div class="col-md-12" style="margin-top:20px;">
                <label for="inputName5" class="form-label">Diagnosa Keperawatan</label>
                <textarea name="diag_prwt" id="" style="width:100%; height:80px"><?= $cekOp["diag_prwt"] ?></textarea>
              </div>

              <div class="col-md-12" style="margin-top:20px;">
                <label for="inputName5" class="form-label">Pengobatan yang dilanjutkan di rumah dan jumlahnya :</label>
                <textarea name="lanjut_rmh" id="" style="width:100%; height:150px"><?= $cekOp["lanjut_rmh"] ?></textarea>
              </div>

              <div class="col-md-12" style="margin-top:20px;">
                <label for="inputName5" class="form-label">Aturan Diet / Nutrisi </label>
                <textarea name="diet" id="" style="width:100%; height:80px"><?= $cekOp["diet"] ?></textarea>
              </div>
              <div class="col-md-12" style="margin-top:20px;">
                <label for="inputName5" class="form-label">Aktivitas dan istirahat </label>
                <textarea name="istirahat" id="" style="width:100%; height:80px"><?= $cekOp["istirahat"] ?></textarea>
              </div>
              <div class="col-md-12" style="margin-top:20px;">
                <label for="inputName5" class="form-label">Jadwal kontrol berikutnya</label>
                <input name="kontrol" id="" class="form-control" type="date" value="<?= $cekOp["kontrol"] ?>"></input>
              </div>

              <div class="col-md-12" style="margin-top:20px;">
                <label for="inputName5" class="form-label">Diberikan pada pasien / keluarga</label><br>

                <input type="text" class="form-control" name="diberikan[]" value="<?= $cekOp["diberikan"] ?>"></input>

              </div>

              <div class="col-md-12" style="margin-top:20px;">
                <label for="inputName5" class="form-label">Dipulangkan dari Klinik Husada Mulia dalam keadaan</label>
                <select id="pilRujuk" name="keadaan" class="form-select">
                  <option value="<?= $cekOp["keadaan"] ?>"><?= $cekOp["keadaan"] ?></option>
                </select>
              </div>

              <!-- <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Jenis Pulang</label>
                  <select  name="pulang" class="form-select">
                    <option value="bolehpulang">bolehpulang</option>
                    <option value="pulangpaksa">pulangpaksa</option>
                    <option value="dirujuk">dirujuk</option>
                    <option value="meninggal">meninggal</option>
                  </select>
                </div> -->

              <div class="col-md-12" style="margin-top:20px;">
                <label for="inputName5" class="form-label">Keterangan Lainnya</label>
                <textarea name="lain" id="" value="<?= $cekOp["lain"] ?>" style="width:100%; height:80px"><?= $cekOp["lain"] ?></textarea>
              </div>

              <div class="col-md-12" style="margin-top:20px;">
                <label for="inputName5" class="form-label">Pasien/Keluarga</label>
                <input name="keluarga" id="" value="<?= $cekOp["keluarga"] ?>" class="form-control" type="text"></input>
              </div>

              <div class="col-md-12" style="margin-top:20px;">
                <label for="inputName5" class="form-label">Kepala Ruangan</label>
                <input name="kep_ruang" id="" value="<?= $cekOp["kep_ruang"] ?>" class="form-control" type="text"></input>
              </div>

              <div class="col-md-12" style="margin-top:20px;">
                <label for="inputName5" class="form-label">Perawat</label>
                <input name="perawat" id="" value="<?= $cekOp["perawat"] ?>" class="form-control" type="text"></input>
              </div>

              <div class="col-md-12" style="margin-top:20px;">
                <label for="inputName5" class="form-label">Tgl Masuk</label>
                <input name="tgl" id="" class="form-control" type="date" value="<?= $cekOp["tgl_masuk"] ?>"></input>
              </div>
              <div class="col-md-12">
                <?php
                if ($jadwal['status_antri'] == 'Datang') {
                ?>
                  <center>
                    <a href="index.php?halaman=pulang&id=<?= $_GET['id'] ?>&inap&tgl=<?= $_GET['tgl'] ?>&statusPulang" class="btn btn-dark mt-2">Pulangkan</a>
                  </center>
                <?php } ?>
              </div>

              <script>
                document.getElementById('pilRujuk').addEventListener('change', function() {
                  var formLain = document.getElementById('rjk');
                  if (this.value === 'Rujuk') {
                    formLain.classList.remove('hidden');
                  } else {
                    formLain.classList.add('hidden');
                  }
                });
              </script>

            </div>
          </div>


        </div>
    </div>
    </div>
    </div>

  <?php } ?>



  </main><!-- End #main -->
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
<?php
if (isset($_POST['save'])) {
  $ob = $_POST["diberikan"];
  $obat = implode(", ", $ob);

  $koneksi->query("INSERT INTO pulang(tgl, norm, tgl_mrs, diag_mrs, kamar, pasien, tgl_krs, diag_krs, diag_prwt, lanjut_rmh, diet, istirahat, kontrol, diberikan, keadaan, lain, keluarga, kep_ruang, perawat, tgl_masuk, rujuk, shift) VALUES ('$_POST[tgl]', '$_GET[id]','$_POST[tgl_mrs]', '$_POST[diag_mrs]', '$_POST[kamar]', '$_POST[pasien]', '$_POST[tgl_krs]', '$_POST[diag_krs]', '$_POST[diag_prwt]', '$_POST[lanjut_rmh]', '$_POST[diet]', '$_POST[istirahat]', '$_POST[kontrol]', '$obat', '$_POST[keadaan]', '$_POST[lain]', '$_POST[keluarga]', '$_POST[kep_ruang]', '$_POST[perawat]', '$_GET[tgl]', '$_POST[rujuk]', '" . $_SESSION['shift'] . "')");

  $koneksi->query("UPDATE registrasi_rawat SET status_antri='Pulang' WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]'");

  $selectRegistrasi = $koneksi->query("SELECT * FROM registrasi_rawat WHERE no_rm = '$_GET[id]' AND date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' ORDER BY idrawat DESC LIMIT 1")->fetch_assoc();

  $getPasienForSendMessage = $koneksi->query("SELECT * FROM pasien WHERE no_rm = '$_GET[id]'")->fetch_assoc();

  $hp2 = konversiNomorHP($getPasienForSendMessage["nohp"]);
  // $hp2 = substr($hp, 1);
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
    'message' =>  $newMes = str_replace('rating.php', 'ratinginap.php', $mes). $selectRegistrasi['idrawat'],
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

  echo "
    <script>
      alert('Data berhasil ditambah');
      document.location.href='index.php?halaman=daftarmedis&inap';
    </script>
  ";
}
?>