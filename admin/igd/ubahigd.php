<?php
error_reporting(0);

$username = $_SESSION['admin']['username'];
$level = $_SESSION['admin']['level'];
$shift = $_SESSION['shift'];
$dokter = $_SESSION['dokter_rawat'];
$ambil = $koneksi->query("SELECT * FROM admin  WHERE username='$username';");
$igd = $koneksi->query("SELECT * FROM igd WHERE idigd='" . htmlspecialchars($_GET['id']) . "';");
$igd = $igd->fetch_assoc();
$pasien = $koneksi->query("SELECT * FROM pasien INNER JOIN kajian_awal WHERE idpasien='$_GET[id]' AND no_rm = norm;");
$pecah = $pasien->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>KHM WONOREJO</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <style>
    .hidden {
      display: hidden;
      max-height: 1px;
      overflow: hidden;
    }
  </style>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <script rel="javascript" type="text/javascript" href="js/jquery-1.11.3.min.js"></script>
  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Select2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</head>

<body>
  <main>
    <div class="pagetitle">
      <h1>IGD</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active"><a href="index.php?halaman=daftarterapi" style="color:blue;">IGD</a></li>
          <li class="breadcrumb-item">Buat IGD</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    <form class="row g-3" method="post" enctype="multipart/form-data">
      <div class="row">
        <div class="col-md-12">
          <div class="card mb-1">
            <div class="card-body col-md-12">
              <h5 class="card-title">Data Pasien</h5>
              <div class="row g-1">
                <div class="col-6">
                  <label for="inputName5" class="form-label">Nama Pasien</label>
                  <input type="text" class="form-control" name="nama_pasien" id="inputName5" value="<?php echo $igd['nama_pasien'] ?>" placeholder="Masukkan Nama Pasien">
                </div>
                <div class="col-6">
                  <label for="inputName5" class="form-label">NO RM Pasien</label>
                  <input type="text" class="form-control" name="no_rm" id="inputName5" value="<?php echo $igd['no_rm'] ?>" placeholder="Masukkan No RM Pasien">
                </div>
                <div class="col-6">
                  <label for="inputState" class="form-label">Dokter</label>
                  <select id="inputState" name="dokter_rawat" class="form-select mb-2" required>
                    <?php $dokter = $koneksi->query("SELECT *,  SUBSTRING(namalengkap, 4) AS namaDokter FROM admin where level = 'dokter' ORDER BY namaDokter ASC");
                    foreach ($dokter as $dok) { ?>
                      <option value="<?= $dok['namalengkap'] ?>" <?= $dok['namalengkap'] == $_SESSION['dokter_rawat'] ? 'selected' : '' ?>><?= $dok['namalengkap'] ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-6">
                  <label for="inputState" class="form-label">Pembayaran</label>
                  <select id="inputState" name="carabayar" class="form-select" required>
                    <option hidden value="">Pilih Pembayaran</option>
                    <option value="<?= $igd['carabayar'] ?>" selected><?= $igd['carabayar'] ?></option>
                    <option value="bpjs">bpjs</option>
                    <option value="umum">umum</option>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-body">
              <h6 class="card-title">Formulir Triase dan Gawat Darurat</h6>


              <hr>

              <!-- Multi Columns Form -->
              <div class="row g-1">
                <h6 class="card-title my-0">Identitas Pengantar Pasien</h6>
                <div class="col-md-6">
                  <label for="inputName5" class="form-label">Nama Pengantar</label>
                  <input type="text" class="form-control" name="nama_pengantar" id="inputName5" value="<?php echo $igd['nama_pengantar'] ?>" placeholder="Masukkan Nama Pengantar">
                </div>
                <div class="col-md-6">
                  <label for="inputName5" class="form-label">Telp Pengantar</label>
                  <input type="text" class="form-control" id="inputName5" value="<?php echo $igd['notelp_pengantar'] ?>" name="notelp_pengantar" placeholder="Masukkan No Telepon">
                </div>
              </div>
              <div class="row g-1">
                <div>
                  <h5 class="card-title my-0">Asesmen Keperawatan</h5>
                </div>
                <?php
                date_default_timezone_set('Asia/Jakarta');
                ?>
                <div class="col-md-6" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Tanggal Masuk</label>
                  <input type="date" class="form-control" name="tgl_masuk" id="inputName5" placeholder="Sesuai dengan ICD 9 CM dan standar lainnya" value="<?php echo $igd['tgl_masuk'] ?>">
                </div>
                <div class="col-md-6" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Jam Masuk</label>
                  <input type="time" class="form-control" name="jam_masuk" id="inputName5" value="<?php echo $igd['jam_masuk'] ?>" placeholder="Nama lengkap sesuai dengan kartu identitas">
                </div>
                <div class="col-4" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Kendaraan Kedatangan </label>
                  <select id="inputState" name="transportasi" class="form-select">
                    <option value="<?php echo $igd['transportasi'] ?>" hidden><?php echo $igd['transportasi'] ?></option>
                    <option value="Ambulans">1. Ambulans</option>
                    <option value="Mobil">2. Mobil</option>
                    <option value="Motor">3. Motor</option>
                    <option value="Lain-lain">4. Lain-lain</option>
                  </select>
                </div>
                <div class="col-4" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Surat Rujukan </label>
                  <select id="inputState" name="surat_pengantar" class="form-select">
                    <option value="<?php echo $igd['surat_pengantar'] ?>" hidden><?php echo $igd['surat_pengantar'] ?></option>
                    <option value="Ada">Ada</option>
                    <option value="Tidak Ada">Tidak Ada</option>
                  </select>
                </div>
                <div class="col-4" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Kondisi Pasien</label>
                  <select id="inputState" name="kondisi_tiba" class="form-select">
                    <option value="<?php echo $igd['kondisi_tiba'] ?>" hidden><?php echo $igd['kondisi_tiba'] ?></option>
                    <!-- <option value="1">Resusitasi</option> -->
                    <option value="Emergency">Emergency</option>
                    <option value="Urgent">Urgent</option>
                    <!-- <option value="4">Less Urgent</option> -->
                    <option value="Non Urgent">Non Urgent</option>
                    <option value="Death on Arrival">Death on Arrival</option>
                  </select>
                </div>

              </div>
              <div class="row g-1">
                <div class="col-4 mt-3">
                  <label for="inputName5" class="form-label">Keluhan </label>
                  <input type="text" class="form-control" name="keluhan" value="<?php echo $igd['keluhan'] ?>">
                </div>
                <div class="col-4 mt-3">
                  <label for="inputName5" class="form-label">Riw Penyakit</label>
                  <input type="text" class="form-control" name="riw_penyakit" value="<?php echo $igd['riw_penyakit'] ?>">
                </div>
                <div class="col-4 mt-3">
                  <label for="inputName5" class="form-label">Riw Alergi</label>
                  <input type="text" class="form-control" name="riw_alergi" value="<?php echo $igd['riw_alergi'] ?>">
                </div>

                <div>
                  <h5 class="card-title">Tanda-Tanda Vital</h5>
                </div>

                <div class="col-4">
                  <label for="inputCity" class="form-label">E</label>
                  <div class="input-group mb-10">
                    <input type="text" class="form-control" placeholder="E" name="e" aria-describedby="basic-addon2" value="<?php echo $igd['e'] ?>">
                    <!-- <span class="input-group-text" id="basic-addon2">celcius</span> -->
                  </div>
                </div>

                <div class="col-4">
                  <label for="inputCity" class="form-label">V</label>
                  <div class="input-group mb-10">
                    <input type="text" class="form-control" placeholder="V" name="v" aria-describedby="basic-addon2" value="<?php echo $igd['v'] ?>">
                    <!-- <span class="input-group-text" id="basic-addon2">%</span> -->
                  </div>
                </div>

                <div class="col-4">

                  <label for="inputCity" class="form-label">M</label>
                  <div class="input-group mb-10">
                    <input type="text" class="form-control" placeholder="M" name="m" aria-describedby="basic-addon2" value="<?php echo $igd['m'] ?>">
                    <!-- <span class="input-group-text" id="basic-addon2">mmHg</span> -->
                  </div>
                </div>

                <div class="col-6">
                  <label for="inputCity" class="form-label" style="margin-top:10px;">Td</label>
                  <div class="input-group mb-10">
                    <input type="text" class="form-control" placeholder="Tekanan Darah" name="td" aria-describedby="basic-addon2" value="<?php echo $igd['td'] ?>">
                    <span class="input-group-text" id="basic-addon2">mmHg</span>
                  </div>
                </div>
                <div class="col-6">
                  <label for="inputCity" class="form-label" style="margin-top:10px;">Rr</label>
                  <div class="input-group mb-10">
                    <input type="text" class="form-control" placeholder="Rr" name="rr" aria-describedby="basic-addon2" value="<?php echo $igd['rr'] ?>">
                    <span class="input-group-text" id="basic-addon2">kali/menit</span>
                  </div>
                </div>

                <div class="col-6">
                  <label for="inputCity" class="form-label" style="margin-top:10px;">Nadi</label>
                  <div class="input-group mb-10">
                    <input type="text" class="form-control" placeholder="Denyut Nadi" name="n" aria-describedby="basic-addon2" value="<?php echo $igd['n'] ?>">
                    <span class="input-group-text" id="basic-addon2">kali/menit</span>
                  </div>
                </div>

                <div class="col-md-6">
                  <label for="inputCity" class="form-label" style="margin-top:10px;">Suhu Tubuh</label>
                  <div class="input-group mb-10">
                    <input type="text" class="form-control" placeholder="Suhu Tubuh" name="s" aria-describedby="basic-addon2" value="<?php echo $igd['s'] ?>">
                    <span class="input-group-text" id="basic-addon2">celcius</span>
                  </div>
                </div>
                <div class="col-6">
                  <label for="inputCity" class="form-label" style="margin-top:10px;">GDA</label>
                  <div class="input-group mb-10">
                    <input type="text" class="form-control" placeholder="GDA" name="gda" aria-describedby="basic-addon2" value="<?php echo $igd['gda'] ?>">
                    <!-- <span class="input-group-text" id="basic-addon2">celcius</span> -->
                  </div>
                </div>
                <div class="col-6">
                  <label for="" class="form-label" style="margin-top: 10px;">Sat.Oksigen</label>
                  <input type="text" name="sat_oksigen" class="form-control" id="" value="<?php echo $igd['sat_oksigen'] ?>">
                </div>
                <div class="col-6">
                  <label for="inputCity" class="form-label" style="margin-top:10px;">BB</label>
                  <div class="input-group mb-10">
                    <input type="text" class="form-control" placeholder="BB" name="bb" aria-describedby="basic-addon2" value="<?php echo $igd['bb'] ?>">
                    <span class="input-group-text" id="basic-addon2">Kg</span>
                  </div>
                </div>
                <div class="col-6">
                  <label for="inputCity" class="form-label" style="margin-top:10px;">TB</label>
                  <div class="input-group mb-10">
                    <input type="text" class="form-control" placeholder="TB" name="tb" aria-describedby="basic-addon2" value="<?php echo $igd['tb'] ?>">
                    <span class="input-group-text" id="basic-addon2">Cm</span>
                  </div>
                </div>
                <div class="col-md-12" style="margin-top:5px;">
                  <label for="inputName5" class="form-label" style="margin-top:10px;">Asesmen Nyeri </label>
                  <select id="pilihan" name="asesmen_nyeri" class="form-select">
                    <option value="<?php echo $igd['asesmen_nyeri'] ?>"><?php echo $igd['asesmen_nyeri'] ?></option>
                    <option value="Ada">Ada</option>
                    <option value="Tidak Ada">Tidak Ada</option>
                  </select>
                </div>

                <div class="hidden" id="nyr">
                  <div class="row">
                    <div class="col-md-12 mt-3">
                      <label for="" class="form-label">Skala Nyeri (dari 1 -10)</label>
                      <input type="number" class="form-control" value="<?php echo $igd['skala_nyeri'] ?>" name="skala_nyeri" id="" max="10">
                    </div>

                  </div>
                </div>
                <script>
                  document.getElementById('pilihan').addEventListener('change', function() {
                    var formLain = document.getElementById('nyr');
                    if (this.value === 'Ada') {
                      formLain.classList.remove('hidden');
                    } else {
                      formLain.classList.add('hidden');
                    }
                  });
                </script>

                <div class="col-md-12">
                  <div>
                    <h5 class="card-title">Skrining Status Gizi</h5>
                  </div>
                  <p style="color:blue">Ya = 1 , Tidak = 0</p>
                  <div class="row">
                    <div class="col-md-4">
                      <p>1. Apakah pasien terlihat kurus ?</p>
                      <input type="number" oninput="hitungGizi()" name="resiko_decubitus" id="no1" class="form-control" value="<?php echo $igd['resiko_decubitus'] ?>">
                    </div>
                    <div class="col-md-4">
                      <p>2. Apakah pakaian anda terasa longgar ?</p>
                      <input type="number" oninput="hitungGizi()" name="penurunan_bb" id="no2" class="form-control" value="<?php echo $igd['penurunan_bb'] ?>">
                    </div>
                    <div class="col-md-4">
                      <p>3. Apakah akhir-akhir ini anda kehilangan berat badan yang tidak sengaja ?</p>
                      <input type="number" oninput="hitungGizi()" name="penurunan_asupan" id="no3" class="form-control" value="<?php echo $igd['penurunan_asupan'] ?>">
                    </div>
                    <div class="col-md-4">
                      <p>4. Apakah anda mengalami penurunan berat badan selama 1 minggu terakhir ?</p>
                      <input type="number" oninput="hitungGizi()" name="gejala_gastro" id="no4" class="form-control" value="<?php echo $igd['gejala_gastro'] ?>">
                    </div>
                    <div class="col-md-4">
                      <p>5. Apakah anda menderita suatu penyakit yang menyebabkan adanya perubahan jumlah jenis makanan yang anda makan ?</p>
                      <input type="number" oninput="hitungGizi()" name="faktor_pemberat" id="no5" class="form-control" value="<?php echo $igd['faktor_pemberat'] ?>">
                    </div>
                    <div class="col-md-4">
                      <p>6. Apakah anda merasa lemah, loyo dan tidak bertenaga ?</p>
                      <input type="number" oninput="hitungGizi()" name="penurunan_fungsional" id="no6" class="form-control" value="<?php echo $igd['penurunan_fungsional'] ?>">
                    </div>
                  </div>
                  <h5 class="mb-0"><b>Jumlah : <span id="totalGizi"></span> (<span id="interpretasiGizi"></span>)</b></h5>

                </div>

                <script>
                  // function hitungIMT(){
                  //   var tinggi_b =document.getElementById("tb").value;
                  //   var berat_b =document.getElementById("bb").value;
                  //   var imtt = berat_b/(tinggi_b*tinggi_b);
                  //   document.getElementById("imt").value = imtt.toFixed(2);
                  // }
                  function hitungGizi() {
                    var n1 = document.getElementById("no1").value;
                    var n2 = document.getElementById("no2").value;
                    var n3 = document.getElementById("no3").value;
                    var n4 = document.getElementById("no4").value;
                    var n5 = document.getElementById("no5").value;
                    var n6 = document.getElementById("no6").value;
                    var ttlGizi = Number(n1) + Number(n2) + Number(n3) + Number(n4) + Number(n5) + Number(n6);
                    if (ttlGizi <= 2) {
                      document.getElementById('interpretasiGizi').innerHTML = 'Tidak beresiko Malnutrisi';
                    } else {
                      document.getElementById('interpretasiGizi').innerHTML = 'Beresiko Malnutrisi';
                    }
                    document.getElementById("totalGizi").innerHTML = ttlGizi;
                  }
                </script>


                <div class="col-6" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Pengkajian Psikologis </label>
                  <select name="psiko" id="" class="form-select">
                    <option value="<?= $igd['psiko'] ?>"><?= $igd['psiko'] ?></option>
                    <option value="Tenang">Tenang</option>
                    <option value="Cemas">Cemas</option>
                    <option value="Sedih">Sedih</option>
                    <option value="Takut thd lingkungan">Takut thd lingkungan</option>
                    <option value="Marah">Marah</option>
                  </select>
                </div>

                <div class="col-6" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Pengkajian Sosial </label>
                  <select name="sosial" id="" class="form-select">
                    <option value="<?= $igd['sosial'] ?>"><?= $igd['sosial'] ?></option>
                    <option value="Sendiri">Sendiri</option>
                    <option value="Kontrak">Kontrak</option>
                    <option value="Rumah Ortu">Rumah Ortu</option>
                    <option value="Lainnya">Lainnya</option>
                  </select>
                </div>

                <div class="col-md-12" style="margin-top:5px;">
                  <label for="inputName5" class="form-label">Bantuan Yang Dibutuhkan Pasien Dirumah </label>
                  <select name="bantuan" id="" class="form-control">
                    <option value="<?php echo $igd['bantuan'] ?>"><?php echo $igd['bantuan'] ?></option>
                    <option value="Makan/Minum">Makan/Minum</option>
                    <option value="BAB">BAB</option>
                    <option value="BAK">BAK</option>
                    <option value="Perawatan Luka">Perawatan Luka</option>
                    <option value="Pemberian Obat">Pemberian Obat</option>
                    <option value="Mobilisasi">Mobilisasi</option>
                    <option value="Lainnya">Lainnya</option>
                  </select>
                </div>

                <div class="col-md-12 d-none" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Tindak Lanjut</label>
                  <select id="pilRujuk" name="tindak" class="form-select">
                    <option value="" hidden>Pilih</option>
                    <option value="Pulang">1.Pulang</option>
                    <option value="Pulang Paksa">2. Pulang Paksa</option>
                    <option value="Rawat">3.Rawat</option>
                    <option value="Meninggal">4.Meninggal</option>
                    <option value="Rujuk">5.Rujuk</option>
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
                      <label for="inputName5" class="form-label">Tag Nama Teman</label>
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
                        placeholder: 'Pilih Nama Teman',
                        allowClear: true,
                      });
                    });
                  </script>
                </div>

                <div class="col-md-12" style="margin-top:5px;">
                  <label for="inputName5" class="form-label">Perawat Yang Mengkaji </label>
                  <input type="text" name="perawat" readonly id="" class="form-control" value="<?= $username ?>">
                </div>

                <?php if ($level == 'dokter' or $level == 'sup') { ?>
                  <div style="margin-bottom:2px; margin-top:30px">
                    <hr>
                    <h5 class="card-title">Asesmen Medis</h5>
                  </div>
                  <div class="col-12" style="margin-top:5px;">
                    <label for="inputName5" class="form-label">Subjektif</label>
                    <textarea name="sub" id="" style="width:100%; height: 150px" class="form-control" value="<?php echo $igd['sub'] ?>"><?php echo $igd['sub'] ?></textarea>
                  </div>
                  <div class="col-12" style="margin-top:5px;">
                    <label for="inputName5" class="form-label">Objektif</label>
                    <?php
                    $getPemeriksaanFisik = $koneksi->query("SELECT *, COUNT(*) AS jumData FROM pemeriksaan_fisik_igd WHERE id_igd = '$_GET[id]' AND norm = '$igd[no_rm]' ORDER BY id DESC LIMIT 1")->fetch_assoc();
                    ?>
                    <h5 class=""><b>Sistem Saraf</b></h5>
                    <label for="" class="">GCS</label>
                    <div class="row">
                      <div class="col-4">
                        <label>Eye</label>
                        <input type="text" class="form-control" name="gcs_e" value="<?= $getPemeriksaanFisik['jumData'] == 1 ? $getPemeriksaanFisik['gcs_e'] : '4' ?>" placeholder="E">
                      </div>
                      <div class="col-4">
                        <label>Verbal</label>
                        <input type="text" class="form-control" name="gcs_v" value="<?= $getPemeriksaanFisik['jumData'] == 1 ? $getPemeriksaanFisik['gcs_v'] : '5' ?>" placeholder="V">
                      </div>
                      <div class="col-4">
                        <label>Motorik</label>
                        <input type="text" class="form-control" name="gcs_m" value="<?= $getPemeriksaanFisik['jumData'] == 1 ? $getPemeriksaanFisik['gcs_m'] : '6' ?>" placeholder="M">
                      </div>
                      <div class="col-md-3">
                        <div class="form-check mt-2">
                          <input class="form-check-input" type="checkbox" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['rangsangan_meninggal'] == '+' ? 'checked' : '') : '' ?> name="rangsangan_meninggal" id="">
                          <label class="form-check-label" for="">
                            Rangsangan Meninggal
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-check mt-2">
                          <input class="form-check-input" type="checkbox" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['refleks_fisiologis1'] == '+' ? 'checked' : '') : 'checked' ?> name="refleks_fisiologis1" id="">
                          <label class="form-check-label" for="">
                            Refleks Fisiologis 1
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-check mt-2">
                          <input class="form-check-input" type="checkbox" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['refleks_fisiologis2'] == '+' ? 'checked' : '') : 'checked' ?> name="refleks_fisiologis2" id="">
                          <label class="form-check-label" for="">
                            Refleks Fisiologis 2
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-check mt-2">
                          <input class="form-check-input" type="checkbox" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['refleks_patologis'] == '+' ? 'checked' : '') : '' ?> name="refleks_patologis" id="">
                          <label class="form-check-label" for="">
                            Refleks Patologis
                          </label>
                        </div>
                      </div>
                    </div>
                    <br>
                    <h5 class=""><b>Sistem Pencernaan</b></h5>
                    <div class="row">
                      <div class="col-6">
                        <label for="" class="">Flat</label>
                        <select name="flat" id="" class="form-select">
                          <option value="flat" selected>flat</option>
                          <option value="cembung">cembung</option>
                        </select>
                      </div>
                      <div class="col-6">
                        <label for="" class="">H/L</label>
                        <select name="hl" class="form-select">
                          <option value="membesar" selected>membesar</option>
                          <option value="tidak membesar">tidak membesar</option>
                        </select>
                      </div>
                      <div class="col-4">
                        <div class="form-check mt-2">
                          <input class="form-check-input" type="checkbox" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['assistos'] == '+' ? 'checked' : '') : '' ?> name="assistos" id="">
                          <label class="form-check-label" for="">
                            Ascites
                          </label>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-check mt-2">
                          <input class="form-check-input" type="checkbox" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['thympani'] == '+' ? 'checked' : '') : 'checked' ?> name="thympani" id="">
                          <label class="form-check-label" for="">
                            Thympani
                          </label>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-check mt-2">
                          <input class="form-check-input" type="checkbox" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['soepel'] == '+' ? 'checked' : '') : 'checked' ?> name="soepel" id="">
                          <label class="form-check-label" for="">
                            Soefl
                          </label>
                        </div>
                      </div>
                      <div class="col-md-5">
                        <label for="" class="">NTF</label>
                        <table class="">
                          <thead class="">
                            <tr>
                              <th></th>
                              <th></th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody class="">
                            <tr>
                              <td>
                                <label for="" class="form-check">
                                  <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['ntf_atas_kiri'] == '+' ? 'checked' : '') : '' ?> name="ntf_atas_kiri">
                                  Atas kiri
                                </label>
                              </td>
                              <td>
                                <label for="" class="form-check">
                                  <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['ntf_atas'] == '+' ? 'checked' : '') : '' ?> name="ntf_atas">
                                  Atas
                                </label>
                              </td>
                              <td>
                                <label for="" class="form-check">
                                  <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['ntf_atas_kanan'] == '+' ? 'checked' : '') : '' ?> name="ntf_atas_kanan">
                                  Atas kanan
                                </label>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <label for="" class="form-check">
                                  <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['ntf_tengah_kiri'] == '+' ? 'checked' : '') : '' ?> name="ntf_tengah_kiri">
                                  Tengah kiri
                                </label>
                              </td>
                              <td>
                                <label for="" class="form-check">
                                  <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['ntf_tengah'] == '+' ? 'checked' : '') : '' ?> name="ntf_tengah">
                                  Tengah
                                </label>
                              </td>
                              <td>
                                <label for="" class="form-check">
                                  <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['ntf_tengah_kanan'] == '+' ? 'checked' : '') : '' ?> name="ntf_tengah_kanan">
                                  Tengah kanan
                                </label>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <label for="" class="form-check">
                                  <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['ntf_bawah_kiri'] == '+' ? 'checked' : '') : '' ?> name="ntf_bawah_kiri">
                                  Bawah kiri
                                </label>
                              </td>
                              <td>
                                <label for="" class="form-check">
                                  <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['ntf_bawah'] == '+' ? 'checked' : '') : '' ?> name="ntf_bawah">
                                  Bawah
                                </label>
                              </td>
                              <td>
                                <label for="" class="form-check">
                                  <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['ntf_bawah_kanan'] == '+' ? 'checked' : '') : '' ?> name="ntf_bawah_kanan">
                                  Bawah kanan
                                </label>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <div class="col-md-7">
                        <br>
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['bu'] == '+' ? 'checked' : '') : 'checked' ?> name="bu">
                          BU
                        </label>
                        <input type="text" class="form-control" name="bu_komen" value="<?= $getPemeriksaanFisik['jumData'] == 1 ? $getPemeriksaanFisik['bu_komen'] : '' ?>" placeholder="BU Keterangan">
                      </div>

                    </div>
                    <br>
                    <h5 class=""><b>Sistem Penglihatan</b></h5>
                    <div class="row">
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['anemis_kiri'] == '+' ? 'checked' : '') : '' ?> name="anemis_kiri">
                          Konjungtiva Anemis Kiri
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['anemis_kanan'] == '+' ? 'checked' : '') : '' ?> name="anemis_kanan">
                          Konjungtiva Anemis Kanan
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['ikterik_kiri'] == '+' ? 'checked' : '') : '' ?> name="ikterik_kiri">
                          Sklera Ikterik Kiri
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['ikterik_kanan'] == '+' ? 'checked' : '') : '' ?> name="ikterik_kanan">
                          Sklera Ikterik Kanan
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['rcl_kiri'] == '+' ? 'checked' : '') : 'checked' ?> name="rcl_kiri">
                          RCL Kiri
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['rcl_kanan'] == '+' ? 'checked' : '') : 'checked' ?> name="rcl_kanan">
                          RCL Kanan
                        </label>
                      </div>
                      <div class="col-12">
                        <label for="" class="mt-2">Diameter Pupil</label>
                        <div class="row">
                          <div class="col-6">
                            <input type="text" class="form-control" name="pupil_kiri" value="<?= $getPemeriksaanFisik['jumData'] == 1 ? $getPemeriksaanFisik['pupil_kiri'] : '' ?>" placeholder="Pupil Kiri">
                          </div>
                          <div class="col-6">
                            <input type="text" class="form-control" name="pupil_kanan" value="<?= $getPemeriksaanFisik['jumData'] == 1 ? $getPemeriksaanFisik['pupil_kanan'] : '' ?>" placeholder="Pupil Kanan">
                          </div>
                        </div>
                      </div>
                      <div class="col-12">
                        <label for="" class="mt-2">Visus</label>
                        <div class="row">
                          <div class="col-6">
                            <input type="text" class="form-control" name="visus_kiri" value="<?= $getPemeriksaanFisik['jumData'] == 1 ? $getPemeriksaanFisik['visus_kiri'] : '6/6' ?>" placeholder="Visus Kiri">
                          </div>
                          <div class="col-6">
                            <input type="text" class="form-control" name="visus_kanan" value="<?= $getPemeriksaanFisik['jumData'] == 1 ? $getPemeriksaanFisik['visus_kanan'] : '6/6' ?>" placeholder="Visus Kanan">
                          </div>
                        </div>
                      </div>
                    </div>
                    <br>
                    <h5 class=""><b>Sistem Pernafasan</b></h5>
                    <div class="row">
                      <div class="col-6">
                        <label for="" class="">Torax</label>
                        <select name="torax" id="" class="form-select">
                          <option value="Simetris" selected>Simetris</option>
                          <option value="Tidak Simetris">Tidak Simetris</option>
                        </select>
                      </div>
                      <div class="col-6">
                        <br>
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['retraksi'] == '+' ? 'checked' : '') : '' ?> name="retraksi">
                          Retraksi
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['vesikuler_kiri'] == '+' ? 'checked' : '') : '' ?> name="vesikuler_kiri">
                          Vesikuler Kiri
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['vesikuler_kanan'] == '+' ? 'checked' : '') : '' ?> name="vesikuler_kanan">
                          Vesikuler Kanan
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['wheezing_kiri'] == '+' ? 'checked' : '') : '' ?> name="wheezing_kiri">
                          Wheezing Kiri
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['wheezing_kanan'] == '+' ? 'checked' : '') : '' ?> name="wheezing_kanan">
                          Wheezing Kanan
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['rongki_kiri'] == '+' ? 'checked' : '') : '' ?> name="rongki_kiri">
                          Rongki Kiri
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['rongki_kanan'] == '+' ? 'checked' : '') : '' ?> name="rongki_kanan">
                          Rongki Kanan
                        </label>
                      </div>
                    </div>
                    <br>
                    <h5 class=""><b>Sistem Jantung</b></h5>
                    <div class="row">
                      <div class="col-md-12">
                        <label for="" class="">S1 S2 Tunggal</label>
                        <select name="s1s2" id="" class="form-select">
                          <option value="reguler" selected>reguler</option>
                          <option value="ireguler">ireguler</option>
                        </select>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['murmur'] == '+' ? 'checked' : '') : '' ?> name="murmur">
                          Murmur
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['golop'] == '+' ? 'checked' : '') : '' ?> name="golop">
                          Gallop
                        </label>
                      </div>
                    </div>
                    <br>
                    <h5 class=""><b>THT</b></h5>
                    <div class="row">
                      <h6 class=""><b class="">Hidung</b></h6>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['nch_kiri'] == '+' ? 'checked' : '') : '' ?> name="nch_kiri">
                          NCH Kirim
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['nch_kanan'] == '+' ? 'checked' : '') : '' ?> name="nch_kanan">
                          NCH Kanan
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['polip_kiri'] == '+' ? 'checked' : '') : '' ?> name="polip_kiri">
                          Polip Kirim
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['polip_kanan'] == '+' ? 'checked' : '') : '' ?> name="polip_kanan">
                          Polip Kanan
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['conca_kiri'] == '+' ? 'checked' : '') : '' ?> name="conca_kiri">
                          Conca Kiri
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['conca_kanan'] == '+' ? 'checked' : '') : '' ?> name="conca_kanan">
                          Conca Kanan
                        </label>
                      </div>
                      <h6 class="mt-2"><b class="">Tenggorokan</b></h6>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['faring_hipertermis'] == '+' ? 'checked' : '') : '' ?> name="faring_hipertermis">
                          Faring Hiperemis
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['halitosis'] == '+' ? 'checked' : '') : '' ?> name="halitosis">
                          Halitosis
                        </label>
                      </div>
                      <div class="col-md-12">
                        <label for="" class="">Pembesaran Tonsil</label>
                        <select name="pembesaran_tonsil" id="" class="form-select">
                          <option value="T0" class="">T0</option>
                          <option value="T1" selected class="">T1</option>
                          <option value="T2" class="">T2</option>
                          <option value="T3" class="">T3</option>
                          <option value="T4" class="">T4</option>
                        </select>
                      </div>
                      <h6 class=" mt-2"><b class="">Telinga</b></h6>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['serumin_kiri'] == '+' ? 'checked' : '') : '' ?> name="serumin_kiri">
                          Serumen Kiri
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['serumin_kanan'] == '+' ? 'checked' : '') : '' ?> name="serumin_kanan">
                          Serumen Kanan
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" checked class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['typani_intak_kiri'] == '+' ? 'checked' : '') : '' ?> name="typani_intak_kiri">
                          Tympani Intak Kiri
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" checked class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['typani_intak_kanan'] == '+' ? 'checked' : '') : '' ?> name="typani_intak_kanan">
                          Tympani Intak Kanan
                        </label>
                      </div>
                      <h6 class=" mt-2"><b class="">Lain Lain</b></h6>
                      <div class="col-md-12">
                        <label for="" class="">Pembesaran Kelenjar Getah Bening</label>
                        <input type="text" name="pembesaran_getah_bening" class="form-control">
                      </div>
                      <h6 class=" mt-2"><b class="">Ekstermitas</b></h6>
                      <div class="col-md-6">
                        <table class="table-bordered mb-3">
                          <thead class="">
                            <tr class="">
                              <th class=""></th>
                              <th class=""></th>
                            </tr>
                          </thead>
                          <tbody class="">
                            <tr class="">
                              <td class="">
                                <label for="" class="form-check">
                                  <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['akral_hangat_atas_kiri'] == '+' ? 'checked' : '') : 'checked' ?> name="akral_hangat_atas_kiri">
                                  Akral Hangat Atas Kiri
                                </label>
                              </td>
                              <td class="">
                                <label for="" class="form-check">
                                  <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['akral_hangat_atas_kanan'] == '+' ? 'checked' : '') : 'checked' ?> name="akral_hangat_atas_kanan">
                                  Akral Hangat Atas Kanan
                                </label>
                              </td>
                            </tr>
                            <tr class="">
                              <td class="">
                                <label for="" class="form-check">
                                  <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['akral_hangat_bawah_kiri'] == '+' ? 'checked' : '') : 'checked' ?> name="akral_hangat_bawah_kiri">
                                  Akral Hangat Bawah Kiri
                                </label>
                              </td>
                              <td class="">
                                <label for="" class="form-check">
                                  <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['akral_hangat_bawah_kanan'] == '+' ? 'checked' : '') : 'checked' ?> name="akral_hangat_bawah_kanan">
                                  Akral Hangat Bawah Kanan
                                </label>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <div class="col-md-6">
                        <table class="table-bordered w-100">
                          <thead class="">
                            <tr class="">
                              <th class=""></th>
                              <th class=""></th>
                            </tr>
                          </thead>
                          <tbody class="">
                            <tr class="">
                              <td class="">
                                <label for="" class="form-check">
                                  <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['oe_atas_kiri'] == '+' ? 'checked' : '') : '' ?> name="oe_atas_kiri">
                                  OE Atas Kiri
                                </label>
                              </td>
                              <td class="">
                                <label for="" class="form-check">
                                  <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['oe_atas_kanan'] == '+' ? 'checked' : '') : '' ?> name="oe_atas_kanan">
                                  OE Atas Kanan
                                </label>
                              </td>
                            </tr>
                            <tr class="">
                              <td class="">
                                <label for="" class="form-check">
                                  <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['oe_bawah_kiri'] == '+' ? 'checked' : '') : '' ?> name="oe_bawah_kiri">
                                  OE Bawah Kiri
                                </label>
                              </td>
                              <td class="">
                                <label for="" class="form-check">
                                  <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['oe_bawah_kanan'] == '+' ? 'checked' : '') : '' ?> name="oe_bawah_kanan">
                                  OE Bawah Kanan
                                </label>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <div class="col-12">
                        <label for="" class="mt-3">CRT</label>
                        <input type="text" class="form-control" value="<= 2" name="crt">
                      </div>
                      <div class="col-md-12">
                        <label for="" class="mt-3">MOTORIK</label>
                        <table class="table-bordered">
                          <thead class="">
                            <tr class="">
                              <th class=""></th>
                              <th class=""></th>
                            </tr>
                          </thead>
                          <tbody class="">
                            <tr class="">
                              <td class="">
                                <input type="text" class="form-control" name="motorik_atas_kiri" value="<?= $getPemeriksaanFisik['jumData'] == 1 ? $getPemeriksaanFisik['motorik_atas_kiri'] : '5' ?>" style="max-width: 80%;">
                              </td>
                              <td class="">
                                <input type="text" class="form-control" name="motorik_atas_kanan" value="<?= $getPemeriksaanFisik['jumData'] == 1 ? $getPemeriksaanFisik['motorik_atas_kanan'] : '5' ?>" style="max-width: 80%;">
                              </td>
                            </tr>
                            <tr class="">
                              <td class="">
                                <input type="text" class="form-control" name="motorik_bawah_kiri" value="<?= $getPemeriksaanFisik['jumData'] == 1 ? $getPemeriksaanFisik['motorik_bawah_kiri'] : '5' ?>" style="max-width: 80%;">
                              </td>
                              <td class="">
                                <input type="text" class="form-control" name="motorik_bawah_kanan" value="<?= $getPemeriksaanFisik['jumData'] == 1 ? $getPemeriksaanFisik['motorik_bawah_kanan'] : '5' ?>" style="max-width: 80%;">
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-12" style="margin-top:5px;">
                    <label for="inputName5" class="form-label">Pemeriksaan Penunjang (Foto)</label>
                    <input type="file" name="penunjang_foto[]" id="penunjang_foto" class="form-control" accept="image/*" capture="environment" multiple>
                    <small class="text-muted">Klik untuk membuka kamera atau pilih file. Bisa pilih multiple foto.</small>

                    <?php
                    // Tampilkan foto yang sudah ada
                    if (!empty($igd['penunjang'])) {
                      $foto_list = json_decode($igd['penunjang'], true);
                      if (is_array($foto_list) && count($foto_list) > 0) {
                        echo '<div class="mt-2"><label>Foto yang sudah ada:</label><div class="row">';
                        foreach ($foto_list as $foto) {
                          if (file_exists('../igd/pemeriksaan_penunjang/' . $foto)) {
                            echo '<div class="col-md-3 mb-2">';
                            echo '<img src="../igd/pemeriksaan_penunjang/' . htmlspecialchars($foto) . '" class="img-thumbnail" style="max-height: 150px; cursor: pointer;" onclick="window.open(this.src, \'_blank\')"><br>';
                            echo '<small>' . htmlspecialchars($foto) . '</small>';
                            echo '<br><a href="?halaman=ubahigd&id=' . $_GET['id'] . '&hapus_foto=' . urlencode($foto) . '" class="btn btn-sm btn-danger mt-1" onclick="return confirm(\'Hapus foto ini?\')">Hapus</a>';
                            echo '</div>';
                          }
                        }
                        echo '</div></div>';
                      }
                    }
                    ?>
                  </div>

                  <div class="col-6" style="margin-top:5px;">
                    <label for="inputName5" class="form-label">Diagnosa Kerja </label>
                    <input type="text" name="dkerja" id="" class="form-control" value="<?php echo $igd['dkerja'] ?>">
                  </div>
                  <div class="col-6" style="margin-top:5px;">
                    <label for="inputName5" class="form-label">Diagnosa Banding </label>
                    <input type="text" name="dbanding" id="" class="form-control" value="<?php echo $igd['dbanding'] ?>">
                  </div>
                  <div class="col-6" style="margin-top:5px; margin-bottom:10px">
                    <label for="inputName5" class="form-label">Tanggal dan Jam </label>
                    <input type="datetime-local" name="tgl" id="" class="form-control" value="<?php echo date('Y-m-d H:i:s', strtotime($igd['tgl_masuk'] . date('H:i:s'))) ?>">
                  </div>
                  <div class="col-6" style="margin-top:5px;">
                    <label for="inputName5" class="form-label">Dokter Jaga </label>
                    <input type="text" name="dokter" id="" class="form-control" value="<?= $_SESSION['dokter_rawat'] ?>">
                  </div>
                  <div class="col-md-12">
                    <label for="inputName5" class="form-label">Planning (Selain Obat) </label>
                    <textarea name="rencana_rawat" id="editor" class="form-control"><?php echo $igd['rencana_rawat'] ?></textarea>
                  </div>
                  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
                  <script src="https://cdn.ckeditor.com/ckeditor5/37.1.0/classic/ckeditor.js"></script>
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
                      });    
                  </script>
                <?php } else { ?>
                  <!-- <div class="col-6" style="margin-top:5px;">
                    <label for="inputName5" class="form-label">Subjektif</label>
                    <textarea name="sub" id="" style="width:100%; height: 150px" class="form-control" value="<?php echo $igd['sub'] ?>" readonly><?php echo $igd['sub'] ?></textarea>
                  </div>

                  <div class="col-6" style="margin-top:5px;">
                    <label for="inputName5" class="form-label">Objektif</label>
                    <textarea name="ob" id="" style="width:100%; height: 150px" class="form-control" value="<?php echo $igd['ob'] ?>" readonly><?php echo $igd['ob'] ?></textarea>
                  </div>

                  <div class="col-md-12" style="margin-top:5px;">
                    <label for="inputName5" class="form-label">Pemeriksaan Penunjang (Foto)</label>
                    <?php
                    // Tampilkan foto di mode readonly
                    if (!empty($igd['penunjang'])) {
                      $foto_list = json_decode($igd['penunjang'], true);
                      if (is_array($foto_list) && count($foto_list) > 0) {
                        echo '<div class="row">';
                        foreach ($foto_list as $foto) {
                          if (file_exists('../igd/pemeriksaan_penunjang/' . $foto)) {
                            echo '<div class="col-md-3 mb-2">';
                            echo '<img src="../igd/pemeriksaan_penunjang/' . htmlspecialchars($foto) . '" class="img-thumbnail" style="max-height: 200px; cursor: pointer;" onclick="window.open(this.src, \'_blank\')"><br>';
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

                  <div class="col-6" style="margin-top:5px;">
                    <label for="inputName5" class="form-label">Diagnosa Kerja </label>
                    <input type="text" name="dkerja" id="" class="form-control" value="<?php echo $igd['dkerja'] ?>" readonly>
                  </div>

                  <div class="col-6" style="margin-top:5px;">
                    <label for="inputName5" class="form-label">Diagnosa Banding </label>
                    <input type="text" name="dbanding" id="" class="form-control" value="<?php echo $igd['dbanding'] ?>" readonly>
                  </div>

                  <div class="col-md-12" style="margin-top:5px;">
                    <label for="inputName5" class="form-label">Planning </label>
                    <input type="text" name="rencana_rawat" id="" class="form-control" value="<?php echo $igd['rencana_rawat'] ?>" readonly>
                  </div>

                  <div class="col-6" style="margin-top:5px; margin-bottom:10px">
                    <label for="inputName5" class="form-label">Tanggal dan Jam </label>
                    <input type="datetime-local" name="tgl" id="" class="form-control" value="<?php echo $igd['tgl'] ?>" readonly>
                  </div>

                  <div class="col-6" style="margin-top:5px;">
                    <label for="inputName5" class="form-label">Dokter Jaga </label>
                    <input type="text" name="dokter" id="" class="form-control" value="<?= $_SESSION['dokter_rawat'] ?>" readonly>
                  </div> -->
                <?php } ?>

                <!-- <div class="col-md-12" style="margin-top:20px;">
                    <label for="inputName5" class="form-label">Rencana Pemulangan Pasien</label>
                    <select id="inputState" name="rencana_pemulangan" class="form-select">
                      <option value="">Pilih</option>
                      <option value="Pasien lansia">1.Pasien lansia</option>
                      <option value="Gangguan anggota gerak">2. Gangguan anggota gerak</option>
                      <option value="Pasien dengan perawatan berkelanjutan atau panjang">3.Pasien dengan perawatan berkelanjutan atau panjang</option>
                      <option value="Memerlukan bantuan dalam aktivitas sehari-hari">4.Memerlukan bantuan dalam aktivitas sehari-hari</option>
                      <option value="Tidak masuk kriteria">5.Tidak masuk kriteria</option>
                    </select>
                  </div> -->
                <!-- <div class="col-md-12" style="margin-top:20px;">
                    <label for="inputName5" class="form-label">Rencana Rawat</label>
                    <input type="text" class="form-control" id="inputName5" name="rencana_rawat" value="" placeholder="Rencana Perawatan">
                  </div>
                  <div class="col-md-12" style="margin-top:20px;">
                    <label for="inputName5" class="form-label">Instruksi Medik dan Keperawatan</label>
                    <input type="text" class="form-control" id="inputName5" name="intruksi_medik" value="" placeholder="Intruksi Medis">
                  </div> -->
                <script>
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


                <div class="text-center" style="margin-top: 50px; margin-bottom: 80px;">
                  <button type="submit" name="save" class="btn btn-primary">Simpan Dulu</button>
                  <a href="?halaman=lpo&igd&id=<?= $igd['no_rm'] ?>&idigd=<?= $_GET['id'] ?>&tgl=<?= $igd['tgl_masuk'] ?>&insertObatDokterIgd" class="btn btn-success">Planning(Obat)</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="">
      </div>
    </form><!-- End Multi Columns Form -->
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


</body>

</html>


<?php
// Handle hapus foto
if (isset($_GET['hapus_foto'])) {
  $foto_hapus = $_GET['hapus_foto'];
  $igd_data = $koneksi->query("SELECT penunjang FROM igd WHERE idigd='" . htmlspecialchars($_GET['id']) . "'")->fetch_assoc();

  if (!empty($igd_data['penunjang'])) {
    $foto_list = json_decode($igd_data['penunjang'], true);
    if (is_array($foto_list)) {
      // Hapus dari array
      $foto_list = array_filter($foto_list, function ($f) use ($foto_hapus) {
        return $f !== $foto_hapus;
      });

      // Hapus file fisik
      $file_path = '../igd/pemeriksaan_penunjang/' . $foto_hapus;
      if (file_exists($file_path)) {
        unlink($file_path);
      }

      // Update database
      $foto_json = json_encode(array_values($foto_list));
      $koneksi->query("UPDATE igd SET penunjang='" . mysqli_real_escape_string($koneksi, $foto_json) . "' WHERE idigd='" . htmlspecialchars($_GET['id']) . "'");

      echo "<script>alert('Foto berhasil dihapus'); window.location.href='index.php?halaman=ubahigd&id=" . $_GET['id'] . "';</script>";
    }
  }
}

if (isset($_POST['save'])) {

  $no_rm = $_POST['no_rm'];
  $nama_pasien = $_POST['nama_pasien'];
  $tgl_masuk = $_POST['tgl_masuk'];
  $jam_masuk = $_POST['jam_masuk'];
  $transportasi = $_POST['transportasi'];
  $surat_pengantar = $_POST['surat_pengantar'];
  $kondisi_tiba = $_POST['kondisi_tiba'];
  $nama_pengantar = $_POST['nama_pengantar'];
  $notelp_pengantar = $_POST['notelp_pengantar'];
  $asesmen_nyeri = $_POST['asesmen_nyeri'];
  // $skala_nyeri=$_POST['skala_nyeri'];
  // $lokasi_nyeri=$_POST['lokasi_nyeri'];
  // $penyebab_nyeri=$_POST['penyebab_nyeri'];
  // $durasi_nyeri=$_POST['durasi_nyeri'];
  // $frekuensi_nyeri=$_POST['frekuensi_nyeri'];
  // $kajian_jatuh=$_POST['kajian_jatuh'];
  // $gambar_anatomi=$_POST['gambar_anatomi'];
  // $tingkat_kesadaran=$_POST['tingkat_kesadaran'];
  $resiko_decubitus = $_POST['resiko_decubitus'];
  $penurunan_bb = $_POST['penurunan_bb'];
  $penurunan_asupan = $_POST['penurunan_asupan'];
  $gejala_gastro = $_POST['gejala_gastro'];
  $faktor_pemberat = $_POST['faktor_pemberat'];
  $penurunan_fungsional = $_POST['penurunan_fungsional'];

  // $perujuk = htmlspecialchars($_POST['perujuk'] == 'Baru' ? $_POST['perujuk_baru'] : $_POST['perujuk']);
  // if ($_POST['perujuk'] != 'Baru') {
  //   $perujuk_hp = $koneksi->query("SELECT perujuk_hp FROM registrasi_rawat WHERE perujuk = '$perujuk' ORDER BY jadwal DESC LIMIT 1")->fetch_assoc()['perujuk_hp'];
  // } else {
  //   $perujuk_hp = htmlspecialchars($_POST['perujuk_hp']);
  // }
  // $rencana_pemulangan=$_POST['rencana_pemulangan'];
  // $rencana_rawat=$_POST['rencana_rawat'];
  // $intruksi_medik=$_POST['intruksi_medik'];
  // $idrm=$_POST['idrm'];

  //   $gambar_anatomi=$_FILES['gambar_anatomi']['name'];

  //   $lokasi=$_FILES['gambar_anatomi']['tmp_name'];

  //   move_uploaded_file($lokasi, '../igd/foto/'.$gambar_anatomi);


  //  if (!empty($lokasi))

  //  {

  // move_uploaded_file($lokasi, '../igd/foto/'.$gambar_anatomi);

  // Handle upload foto pemeriksaan penunjang
  $foto_penunjang_array = array();

  // Ambil foto lama dari database
  $igd_lama = $koneksi->query("SELECT penunjang FROM igd WHERE idigd='$_GET[id]'")->fetch_assoc();
  if (!empty($igd_lama['penunjang'])) {
    $foto_lama = json_decode($igd_lama['penunjang'], true);
    if (is_array($foto_lama)) {
      $foto_penunjang_array = $foto_lama;
    }
  }

  // Proses upload foto baru
  if (isset($_FILES['penunjang_foto']) && !empty($_FILES['penunjang_foto']['name'][0])) {
    $upload_dir = '../igd/pemeriksaan_penunjang/';

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
        $new_file_name = 'penunjang_' . $no_rm . '_' . time() . '_' . $i . '.' . $file_ext;
        $upload_path = $upload_dir . $new_file_name;

        // Upload file
        if (move_uploaded_file($file_tmp, $upload_path)) {
          $foto_penunjang_array[] = $new_file_name;
        }
      }
    }
  }

  // Convert array ke JSON untuk disimpan di database
  $penunjang_json = json_encode($foto_penunjang_array);

  if ($_POST['tindak'] == 'Rawat') {
    if ($_POST['kamar'] == "") {
      echo "
        <script>
          alert('Kamar Tidak Boleh Kosong !!!');
          document.location.href='index.php?halaman=ubahigd&id=$_GET[id]';
        </script>
      ";
      exit();
    }
  }

  if ($level == "igd" or $level == "perawat" or $level == "sup") {
    $koneksi->query("UPDATE igd SET no_rm='$no_rm', nama_pasien='$nama_pasien', tgl_masuk='$tgl_masuk', jam_masuk='$jam_masuk', transportasi='$transportasi', surat_pengantar='$surat_pengantar', kondisi_tiba='$kondisi_tiba', nama_pengantar='$nama_pengantar', notelp_pengantar='$notelp_pengantar', asesmen_nyeri='$asesmen_nyeri', resiko_decubitus='$resiko_decubitus',penurunan_bb='$penurunan_bb',penurunan_asupan='$penurunan_asupan',gejala_gastro='$gejala_gastro',faktor_pemberat='$faktor_pemberat',penurunan_fungsional='$penurunan_fungsional',psiko='$_POST[psiko]',sosial='$_POST[sosial]',bantuan='$_POST[bantuan]',tindak='$_POST[tindak]',skala_nyeri='$_POST[skala_nyeri]',tindak_rujuk='$_POST[tindak_rujuk]',keluhan='$_POST[keluhan]',riw_penyakit='$_POST[riw_penyakit]',riw_alergi='$_POST[riw_alergi]',perawat='$_POST[perawat]', e='$_POST[e]', v='$_POST[v]', m='$_POST[m]', td='$_POST[td]', rr='$_POST[rr]', n='$_POST[n]', s='$_POST[s]', gda='$_POST[gda]', bb='$_POST[bb]', tb='$_POST[tb]', tindak_rujuk_keterangan = '$_POST[tindak_rujuk_keterangan]', penunjang='" . mysqli_real_escape_string($koneksi, $penunjang_json) . "', sat_oksigen = '$_POST[sat_oksigen]' WHERE idigd='$_GET[id]' ");
  } elseif ($level == "dokter") {
    $koneksi->query("UPDATE igd SET penunjang='" . mysqli_real_escape_string($koneksi, $penunjang_json) . "',dkerja='$_POST[dkerja]', dbanding='$_POST[dbanding]', tgl='$_POST[tgl]', sub='$_POST[sub]', ob='$_POST[ob]', dokter='$_POST[dokter]', rencana_rawat='". $koneksi->real_escape_string($_POST['rencana_rawat'])."' WHERE idigd='$_GET[id]' ");

    // PemeriksaanFisik
    $gcs_e = htmlspecialchars(isset($_POST['gcs_e']) ? $_POST['gcs_e'] : '');
    $gcs_v = htmlspecialchars(isset($_POST['gcs_v']) ? $_POST['gcs_v'] : '');
    $gcs_m = htmlspecialchars(isset($_POST['gcs_m']) ? $_POST['gcs_m'] : '');
    $rangsangan_meninggal = htmlspecialchars(isset($_POST['rangsangan_meninggal']) ? $_POST['rangsangan_meninggal'] : '-');
    $refleks_fisiologis1 = htmlspecialchars(isset($_POST['refleks_fisiologis1']) ? $_POST['refleks_fisiologis1'] : '-');
    $refleks_fisiologis2 = htmlspecialchars(isset($_POST['refleks_fisiologis2']) ? $_POST['refleks_fisiologis2'] : '-');
    $refleks_patologis = htmlspecialchars(isset($_POST['refleks_patologis']) ? $_POST['refleks_patologis'] : '-');
    $flat = htmlspecialchars(isset($_POST['flat']) ? $_POST['flat'] : '');
    $hl = htmlspecialchars(isset($_POST['hl']) ? $_POST['hl'] : '');
    $assistos = htmlspecialchars(isset($_POST['assistos']) ? $_POST['assistos'] : '-');
    $thympani = htmlspecialchars(isset($_POST['thympani']) ? $_POST['thympani'] : '-');
    $soepel = htmlspecialchars(isset($_POST['soepel']) ? $_POST['soepel'] : '-');
    $ntf_atas_kiri = htmlspecialchars(isset($_POST['ntf_atas_kiri']) ? $_POST['ntf_atas_kiri'] : '-');
    $ntf_atas = htmlspecialchars(isset($_POST['ntf_atas']) ? $_POST['ntf_atas'] : '-');
    $ntf_atas_kanan = htmlspecialchars(isset($_POST['ntf_atas_kanan']) ? $_POST['ntf_atas_kanan'] : '-');
    $ntf_tengah_kiri = htmlspecialchars(isset($_POST['ntf_tengah_kiri']) ? $_POST['ntf_tengah_kiri'] : '-');
    $ntf_tengah = htmlspecialchars(isset($_POST['ntf_tengah']) ? $_POST['ntf_tengah'] : '-');
    $ntf_tengah_kanan = htmlspecialchars(isset($_POST['ntf_tengah_kanan']) ? $_POST['ntf_tengah_kanan'] : '-');
    $ntf_bawah_kiri = htmlspecialchars(isset($_POST['ntf_bawah_kiri']) ? $_POST['ntf_bawah_kiri'] : '-');
    $ntf_bawah = htmlspecialchars(isset($_POST['ntf_bawah']) ? $_POST['ntf_bawah'] : '-');
    $ntf_bawah_kanan = htmlspecialchars(isset($_POST['ntf_bawah_kanan']) ? $_POST['ntf_bawah_kanan'] : '-');
    $bu = htmlspecialchars(isset($_POST['bu']) ? $_POST['bu'] : '-');
    $bu_komen = htmlspecialchars(isset($_POST['bu_komen']) ? $_POST['bu_komen'] : '');
    $anemis_kiri = htmlspecialchars(isset($_POST['anemis_kiri']) ? $_POST['anemis_kiri'] : '-');
    $anemis_kanan = htmlspecialchars(isset($_POST['anemis_kanan']) ? $_POST['anemis_kanan'] : '-');
    $ikterik_kiri = htmlspecialchars(isset($_POST['ikterik_kiri']) ? $_POST['ikterik_kiri'] : '-');
    $ikterik_kanan = htmlspecialchars(isset($_POST['ikterik_kanan']) ? $_POST['ikterik_kanan'] : '-');
    $rcl_kiri = htmlspecialchars(isset($_POST['rcl_kiri']) ? $_POST['rcl_kiri'] : '-');
    $rcl_kanan = htmlspecialchars(isset($_POST['rcl_kanan']) ? $_POST['rcl_kanan'] : '-');
    $pupil_kiri = htmlspecialchars(isset($_POST['pupil_kiri']) ? $_POST['pupil_kiri'] : '');
    $pupil_kanan = htmlspecialchars(isset($_POST['pupil_kanan']) ? $_POST['pupil_kanan'] : '');
    $visus_kiri = htmlspecialchars(isset($_POST['visus_kiri']) ? $_POST['visus_kiri'] : '');
    $visus_kanan = htmlspecialchars(isset($_POST['visus_kanan']) ? $_POST['visus_kanan'] : '');
    $torax = htmlspecialchars(isset($_POST['torax']) ? $_POST['torax'] : '');
    $retraksi = htmlspecialchars(isset($_POST['retraksi']) ? $_POST['retraksi'] : '');
    $vesikuler_kiri = htmlspecialchars(isset($_POST['vesikuler_kiri']) ? $_POST['vesikuler_kiri'] : '-');
    $vesikuler_kanan = htmlspecialchars(isset($_POST['vesikuler_kanan']) ? $_POST['vesikuler_kanan'] : '-');
    $wheezing_kiri = htmlspecialchars(isset($_POST['wheezing_kiri']) ? $_POST['wheezing_kiri'] : '-');
    $wheezing_kanan = htmlspecialchars(isset($_POST['wheezing_kanan']) ? $_POST['wheezing_kanan'] : '-');
    $rongki_kiri = htmlspecialchars(isset($_POST['rongki_kiri']) ? $_POST['rongki_kiri'] : '-');
    $rongki_kanan = htmlspecialchars(isset($_POST['rongki_kanan']) ? $_POST['rongki_kanan'] : '-');
    $s1s2 = htmlspecialchars(isset($_POST['s1s2']) ? $_POST['s1s2'] : '');
    $murmur = htmlspecialchars(isset($_POST['murmur']) ? $_POST['murmur'] : '-');
    $golop = htmlspecialchars(isset($_POST['golop']) ? $_POST['golop'] : '-');
    $nch_kiri = htmlspecialchars(isset($_POST['nch_kiri']) ? $_POST['nch_kiri'] : '-');
    $nch_kanan = htmlspecialchars(isset($_POST['nch_kanan']) ? $_POST['nch_kanan'] : '-');
    $polip_kiri = htmlspecialchars(isset($_POST['polip_kiri']) ? $_POST['polip_kiri'] : '-');
    $polip_kanan = htmlspecialchars(isset($_POST['polip_kanan']) ? $_POST['polip_kanan'] : '-');
    $conca_kiri = htmlspecialchars(isset($_POST['conca_kiri']) ? $_POST['conca_kiri'] : '-');
    $conca_kanan = htmlspecialchars(isset($_POST['conca_kanan']) ? $_POST['conca_kanan'] : '-');
    $faring_hipertermis = htmlspecialchars(isset($_POST['faring_hipertermis']) ? $_POST['faring_hipertermis'] : '-');
    $halitosis = htmlspecialchars(isset($_POST['halitosis']) ? $_POST['halitosis'] : '-');
    $pembesaran_tonsil = htmlspecialchars(isset($_POST['pembesaran_tonsil']) ? $_POST['pembesaran_tonsil'] : '');
    $serumin_kiri = htmlspecialchars(isset($_POST['serumin_kiri']) ? $_POST['serumin_kiri'] : '-');
    $serumin_kanan = htmlspecialchars(isset($_POST['serumin_kanan']) ? $_POST['serumin_kanan'] : '-');
    $typani_intak_kiri = htmlspecialchars(isset($_POST['typani_intak_kiri']) ? $_POST['typani_intak_kiri'] : '-');
    $typani_intak_kanan = htmlspecialchars(isset($_POST['typani_intak_kanan']) ? $_POST['typani_intak_kanan'] : '-');
    $pembesaran_getah_bening = htmlspecialchars(isset($_POST['pembesaran_getah_bening']) ? $_POST['pembesaran_getah_bening'] : '');
    $akral_hangat_atas_kiri = htmlspecialchars(isset($_POST['akral_hangat_atas_kiri']) ? $_POST['akral_hangat_atas_kiri'] : '-');
    $akral_hangat_atas_kanan = htmlspecialchars(isset($_POST['akral_hangat_atas_kanan']) ? $_POST['akral_hangat_atas_kanan'] : '-');
    $akral_hangat_bawah_kiri = htmlspecialchars(isset($_POST['akral_hangat_bawah_kiri']) ? $_POST['akral_hangat_bawah_kiri'] : '-');
    $akral_hangat_bawah_kanan = htmlspecialchars(isset($_POST['akral_hangat_bawah_kanan']) ? $_POST['akral_hangat_bawah_kanan'] : '-');
    $oe_atas_kiri = htmlspecialchars(isset($_POST['oe_atas_kiri']) ? $_POST['oe_atas_kiri'] : '-');
    $oe_atas_kanan = htmlspecialchars(isset($_POST['oe_atas_kanan']) ? $_POST['oe_atas_kanan'] : '-');
    $oe_bawah_kiri = htmlspecialchars(isset($_POST['oe_bawah_kiri']) ? $_POST['oe_bawah_kiri'] : '-');
    $oe_bawah_kanan = htmlspecialchars(isset($_POST['oe_bawah_kanan']) ? $_POST['oe_bawah_kanan'] : '-');
    $crt = htmlspecialchars(isset($_POST['crt']) ? $_POST['crt'] : '');
    $motorik_atas_kiri = htmlspecialchars(isset($_POST['motorik_atas_kiri']) ? $_POST['motorik_atas_kiri'] : '');
    $motorik_atas_kanan = htmlspecialchars(isset($_POST['motorik_atas_kanan']) ? $_POST['motorik_atas_kanan'] : '');
    $motorik_bawah_kiri = htmlspecialchars(isset($_POST['motorik_bawah_kiri']) ? $_POST['motorik_bawah_kiri'] : '');
    $motorik_bawah_kanan = htmlspecialchars(isset($_POST['motorik_bawah_kanan']) ? $_POST['motorik_bawah_kanan'] : '');
    $kognitif = htmlspecialchars(isset($_POST['kognitif']) ? $_POST['kognitif'] : '');
    // End PemeriksaanFisik

    if ($getPemeriksaanFisik['jumData'] == 1) {
      $koneksi->query("UPDATE pemeriksaan_fisik_igd SET gcs_e='$gcs_e', gcs_v='$gcs_v', gcs_m='$gcs_m', rangsangan_meninggal='$rangsangan_meninggal', refleks_fisiologis1='$refleks_fisiologis1', refleks_fisiologis2='$refleks_fisiologis2', refleks_patologis='$refleks_patologis', flat='$flat', hl='$hl', assistos='$assistos', thympani='$thympani', soepel='$soepel', ntf_atas_kiri='$ntf_atas_kiri', ntf_atas='$ntf_atas', ntf_atas_kanan='$ntf_atas_kanan', ntf_tengah_kiri='$ntf_tengah_kiri', ntf_tengah='$ntf_tengah', ntf_tengah_kanan='$ntf_tengah_kanan', ntf_bawah_kiri='$ntf_bawah_kiri', ntf_bawah='$ntf_bawah', ntf_bawah_kanan='$ntf_bawah_kanan', bu='$bu', bu_komen='$bu_komen', anemis_kiri='$anemis_kiri', anemis_kanan='$anemis_kanan', ikterik_kiri='$ikterik_kiri', ikterik_kanan='$ikterik_kanan', rcl_kiri='$rcl_kiri', rcl_kanan='$rcl_kanan', pupil_kiri='$pupil_kiri', pupil_kanan='$pupil_kanan', visus_kiri='$visus_kiri', visus_kanan='$visus_kanan', torax='$torax', retraksi='$retraksi', vesikuler_kiri='$vesikuler_kiri', vesikuler_kanan='$vesikuler_kanan', wheezing_kiri='$wheezing_kiri', wheezing_kanan='$wheezing_kanan', rongki_kiri='$rongki_kiri', rongki_kanan='$rongki_kanan', s1s2='$s1s2', murmur='$murmur', golop='$golop', nch_kiri='$nch_kiri', nch_kanan='$nch_kanan', polip_kiri='$polip_kiri', polip_kanan='$polip_kanan', conca_kiri='$conca_kiri', conca_kanan='$conca_kanan', faring_hipertermis='$faring_hipertermis', halitosis='$halitosis', pembesaran_tonsil='$pembesaran_tonsil', serumin_kiri='$serumin_kiri', serumin_kanan='$serumin_kanan', typani_intak_kiri='$typani_intak_kiri', typani_intak_kanan='$typani_intak_kanan', pembesaran_getah_bening='$pembesaran_getah_bening', akral_hangat_atas_kiri='$akral_hangat_atas_kiri', akral_hangat_atas_kanan='$akral_hangat_atas_kanan', akral_hangat_bawah_kiri='$akral_hangat_bawah_kiri', akral_hangat_bawah_kanan='$akral_hangat_bawah_kanan', oe_atas_kiri='$oe_atas_kiri', oe_atas_kanan='$oe_atas_kanan', oe_bawah_kiri='$oe_bawah_kiri', oe_bawah_kanan='$oe_bawah_kanan', crt='$crt', motorik_atas_kiri='$motorik_atas_kiri', motorik_atas_kanan='$motorik_atas_kanan', motorik_bawah_kiri='$motorik_bawah_kiri', motorik_bawah_kanan='$motorik_bawah_kanan', kognitif='$kognitif' WHERE id_igd='$_GET[id]'");
    } else {
      $koneksi->query("INSERT INTO pemeriksaan_fisik_igd (id_igd, norm, gcs_e, gcs_v, gcs_m, rangsangan_meninggal, refleks_fisiologis1, refleks_fisiologis2, refleks_patologis, flat, hl, assistos, thympani, soepel, ntf_atas_kiri, ntf_atas, ntf_atas_kanan, ntf_tengah_kiri, ntf_tengah, ntf_tengah_kanan, ntf_bawah_kiri, ntf_bawah, ntf_bawah_kanan, bu, bu_komen, anemis_kiri, anemis_kanan, ikterik_kiri, ikterik_kanan, rcl_kiri, rcl_kanan, pupil_kiri, pupil_kanan, visus_kiri, visus_kanan, torax, retraksi, vesikuler_kiri, vesikuler_kanan, wheezing_kiri, wheezing_kanan, rongki_kiri, rongki_kanan, s1s2, murmur, golop, nch_kiri, nch_kanan, polip_kiri, polip_kanan, conca_kiri, conca_kanan, faring_hipertermis, halitosis, pembesaran_tonsil, serumin_kiri, serumin_kanan, typani_intak_kiri, typani_intak_kanan, pembesaran_getah_bening, akral_hangat_atas_kiri, akral_hangat_atas_kanan, akral_hangat_bawah_kiri, akral_hangat_bawah_kanan, oe_atas_kiri, oe_atas_kanan, oe_bawah_kiri, oe_bawah_kanan, crt, motorik_atas_kiri, motorik_atas_kanan, motorik_bawah_kiri, motorik_bawah_kanan, kognitif) VALUES ('$_GET[id]', '$igd[no_rm]', '$gcs_e', '$gcs_v', '$gcs_m', '$rangsangan_meninggal', '$refleks_fisiologis1', '$refleks_fisiologis2', '$refleks_patologis', '$flat', '$hl', '$assistos', '$thympani', '$soepel', '$ntf_atas_kiri', '$ntf_atas', '$ntf_atas_kanan', '$ntf_tengah_kiri', '$ntf_tengah', '$ntf_tengah_kanan', '$ntf_bawah_kiri', '$ntf_bawah', '$ntf_bawah_kanan', '$bu', '$bu_komen', '$anemis_kiri', '$anemis_kanan', '$ikterik_kiri', '$ikterik_kanan', '$rcl_kiri', '$rcl_kanan', '$pupil_kiri', '$pupil_kanan', '$visus_kiri', '$visus_kanan', '$torax', '$retraksi', '$vesikuler_kiri', '$vesikuler_kanan', '$wheezing_kiri', '$wheezing_kanan', '$rongki_kiri', '$rongki_kanan', '$s1s2', '$murmur' ,'$golop','$nch_kiri','$nch_kanan','$polip_kiri','$polip_kanan','$conca_kiri','$conca_kanan','$faring_hipertermis','$halitosis','$pembesaran_tonsil','$serumin_kiri','$serumin_kanan','$typani_intak_kiri','$typani_intak_kanan','$pembesaran_getah_bening','$akral_hangat_atas_kiri','$akral_hangat_atas_kanan','$akral_hangat_bawah_kiri','$akral_hangat_bawah_kanan','$oe_atas_kiri','$oe_atas_kanan','$oe_bawah_kiri','$oe_bawah_kanan','$crt','$motorik_atas_kiri','$motorik_atas_kanan','$motorik_bawah_kiri','$motorik_bawah_kanan','$kognitif')");
    }
  }

  $countigd = $koneksi->query("SELECT *, COUNT(perawat) as jumlah FROM igd WHERE idigd = '$_GET[id]' LIMIT 1")->fetch_assoc();

  // if($countigd["jumlah"] == 0){
  if ($_POST['tindak'] == "Rawat") {
    $getLast = $koneksi->query("SELECT * FROM registrasi_rawat ORDER BY idrawat DESC LIMIT 1")->fetch_assoc();
    $idrawat = $getLast['idrawat'] + 1;

    $koneksi->query("INSERT INTO registrasi_rawat (idrawat, nama_pasien, dokter_rawat, perawatan, kamar, jenis_kunjungan, id_pasien, no_rm, jadwal, antrian, status_antri, carabayar, shift, perawat, perujuk, perujuk_hp, perujuk_file) VALUES ('$idrawat', '$nama_pasien', '$_POST[dokter_rawat]', 'Rawat Inap', '$_POST[kamar]', 'Kunjungan Sakit', '', '$no_rm', '" . $tgl_masuk . date(' H:i:s') . "', '', 'Belum Datang', '$_POST[carabayar]', '$shift', '" . $_SESSION['admin']['username'] . "', '$igd[perujuk]', '$igd[perujuk_hp]', '$igd[perujuk_file]')");

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
  }
  // }

  if ($_POST['tindak'] == "Rawat") {

    echo "
  <script>
  alert('Data berhasil ditambah');
  document.location.href='index.php?halaman=daftarregistrasiinap';
  </script>

  ";
  } else {
    echo "
    <script>
    alert('Data berhasil ditambah');
    document.location.href='index.php?halaman=daftarigd';
    </script>
  
    ";
  }
}

// else {

//    $koneksi->query("UPDATE igd SET no_rm='$no_rm', nama_pasien='$nama_pasien', tgl_masuk='$tgl_masuk', jam_masuk='$jam_masuk', transportasi='$transportasi', surat_pengantar='$surat_pengantar', kondisi_tiba='$kondisi_tiba', nama_pengantar='$nama_pengantar', notelp_pengantar='$notelp_pengantar', asesmen_nyeri='$asesmen_nyeri', lokasi_nyeri='$lokasi_nyeri', penyebab_nyeri='$penyebab_nyeri', durasi_nyeri='$durasi_nyeri', frekuensi_nyeri='$frekuensi_nyeri', resiko_decubitus='$resiko_decubitus',penurunan_bb='$penurunan_bb',penurunan_asupan='$penurunan_asupan',gejala_gastro='$gejala_gastro',faktor_pemberat='$faktor_pemberat',penurunan_fungsional='$penurunan_fungsional',rencana_pemulangan='$rencana_pemulangan',psiko='$psiko',sosial='$sosial',bantuan='$bantuan',penunjang='$penunjang',dkerja='$dkerja',dbanding='$dbanding',tindak='$tindak',skala_nyeri='$skala_nyeri',tindak_rujuk='$tindak_rujuk',keluhan='$keluhan',riw_penyakit='$riw_penyakit',riw_alergi='$riw_alergi',perawat='$_SESSION[admin][nama_lengkap]', e='$_POST[e]', v='$_POST[v]', m='$_POST[m]', td='$_POST[td]', rr='$_POST[rr]', n='$_POST[n]', s='$_POST[s]', gda='$_POST[gda]', bb='$_POST[bb]', tb='$_POST[tb]' WHERE idigd='$_GET[id]' ");

//    if($_POST['tindak'] == "Rawat"){
//     $koneksi->query("INSERT INTO registrasi_rawat (nama_pasien, dokter_rawat, perawatan, kamar, jenis_kunjungan, id_pasien, no_rm, jadwal, antrian, status_antri, carabayar, shift) VALUES ('$nama_pasien', '$_POST[dokter_rawat]', 'Rawat Inap', '$_POST[kamar]', 'Kunjungan Sakit', '', '$no_rm', '$tgl_masuk', '', 'Belum Datang', '$_POST[carabayar]', '$shift')");
//     }


//   }




// if (mysqli_affected_rows($koneksi)>0) {


// } else {

//   echo "
//   <script>
//   alert('GAGAL MENGHAPUS DATA');
//   document.location.href='index.php?halaman=daftarigd;
//   </script>

//   ";

// }




//   // $koneksi->query("INSERT INTO log_user 

//   //   (status_log, username_admin, idadmin)

//   //   VALUES ('$status_log', '$username_admin', '$idadmin')

//   //   ");

// }

?>