<?php
// error_reporting(0);
$username = $_SESSION['admin']['username'];
$ambil = $koneksi->query("SELECT * FROM admin  WHERE username='$username';");

$pasien = $koneksi->query("SELECT * FROM registrasi_rawat  WHERE idrawat='$_GET[id]';");
$pasien = $pasien->fetch_assoc();

$p = $koneksi->query("SELECT * FROM pasien WHERE no_rm='$_GET[norm]';");
$p = $p->fetch_assoc();

$rm = $koneksi->query("SELECT * FROM rekam_medis  WHERE norm='$_GET[norm]';");
$rm = $rm->fetch_assoc();

if (isset($_GET['kj'])) {
  $awal = $koneksi->query("SELECT * FROM kajian_awal WHERE norm='$_GET[norm]' AND id_rm='$_GET[kj]';");
} else {
  // $awal=$koneksi->query("SELECT * FROM kajian_awal WHERE norm='$_GET[norm]';");
  $awal = $koneksi->query("SELECT * FROM kajian_awal WHERE norm='$_GET[norm]' ORDER BY tgl_rm DESC LIMIT 1;");
}
$awal = $awal->fetch_assoc();
date_default_timezone_set("asia/jakarta");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>KHM WONOREJO</title>
  <meta content="" name="description">
  <meta content="" name="keywords">



<body>

  <main>
    <div class="">
      <div class="pagetitle">
        <h1>Kajian Awal</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="index.php?halaman=daftarregistrasi" style="color:blue;">Daftar Registrasi</a></li>
            <li class="breadcrumb-item">Kajian Awal</li>
          </ol>
        </nav>
      </div><!-- End Page Title -->

      <section class="section  py-4">
        <div class="">
          <div class="row">
            <div class="col-lg-12 col-md-12 d-flex">
              <div class="card" style="max-width: 100%; display: inline-flex;">
                <div class="card-body">
                  <h5 class="card-title">Data Pasien</h5>

                  <!-- Multi Columns Form -->
                  <form class="row g-3" method="post" enctype="multipart/form-data">
                    <?php foreach ($ambil as $pecah) : ?>
                      <input type="hidden" name="idadmin" class="form-control" value="<?php echo $pecah['idadmin'] ?>" placeholder="Masukkan nama karyawan">
                      <input type="hidden" name="username_admin" class="form-control" value="<?php echo $pecah['namalengkap'] ?>" placeholder="Masukkan nama karyawan">
                      <input type="hidden" name="status_log" class="form-control" value="<?php echo $pecah['namalengkap'] ?> Memperbarui Data Kajian Awal" placeholder="Masukkan nama karyawan">
                    <?php endforeach ?>
                    <div class="col-6">
                      <label for="inputName5" class="form-label">Nama Pasien</label>
                      <input type="text" class="form-control" id="inputName5" placeholder="Masukkan Nama Pasien" value="<?php echo $pasien['nama_pasien'] ?>" name="nama_pasien" required>
                    </div>
                    <div class="col-6">
                      <label for="inputName5" class="form-label">Nomor BPJS Pasien</label>
                      <input type="text" class="form-control" id="inputName5" placeholder="Masukkan Nomor BPJS Pasien" value="<?php echo $pasien['no_bpjs'] ?? '-' ?>" name="no_bpjs" required>
                    </div>
                    <div class="col-6">
                      <label for="inputName5" class="form-label">No. RM Pasien</label>
                      <input type="text" class="form-control" id="inputName5" placeholder="Masukkan No RM Pasien" value="<?php echo $pasien['no_rm'] ?>" name="norm" required>
                    </div>
                    <?php
                    // if(){

                    // }
                    ?>
                    <div class="col-6">
                      <label for="inputName5" class="form-label">Umur Pasien</label>
                      <input type="text" class="form-control" id="inputName5" placeholder="Masukkan Umur Pasien" value="<?php echo $p['umur'] ?>" name="umur_pasien" required>
                    </div>

                    <!-- <div class="col-md-6">
                      <label for="inputState" class="form-label">Tipe Umur</label>
                      <select id="inputState" class="form-select" name="tipe_umur" required>
                        <option value="Tahun">Tahun</option>
                        <option value="Hari">Hari</option>
                        <option value="Bulan">Bulan</option>
                      </select>
                    </div> -->

                    <div class="col-md-12">
                      <!-- <label for="inputName5" class="form-label">Jenis Kelamin Pasien</label> -->
                      <!-- <input type="hidden" class="form-control" id="inputName5" placeholder="Masukkan No RM Pasien" value="<?php echo $pasien['jenis_kelamin'] ?>" name="jk"> -->
                    </div>


                    <div>
                      <h5 class="card-title">Data Kesehatan</h5>
                    </div>
                    <div class="col-md-12">
                      <label for="inputCity" class="form-label">Keluhan Utama </label>
                      <input type="text" class="form-control" required id="keluhan_utama" placeholder="Tuliskan Keluhan Utama Pasien" name="keluhan_utama" value="<?php echo $awal['keluhan_utama'] ?>">
                    </div>
                    <div class="col-md-6">
                      <label for="inputCity" class="form-label">Riwayat Penyakit</label>
                      <input type="text" class="form-control" required id="inputCity" placeholder="Tuliskan Riwayat Penyakit Pasien" name="riwayat_penyakit" value="<?php echo $awal['riwayat_penyakit'] ?>">
                    </div>
                    <div class="col-md-6">
                      <label for="inputState" class="form-label">Riwayat Alergi</label>
                      <input type="text" class="form-control" required id="inputState" placeholder="Tuliskan Riwayat Alergi Pasien" name="riwayat_alergi" value="<?php echo $awal['riwayat_alergi'] ?>">
                      <!-- <select id="inputState" class="form-select" name="riwayat_alergi">
                        <option selected>Udang</option>
                        <option>Kacang</option>
                      </select> -->
                    </div>

                    <br>
                    <br>
                    <div>
                      <h5 class="card-title">Pemeriksaan Psikologis, Sosial Ekonomi, Spiritual</h5>
                    </div>

                    <div class="col-md-12">
                      <label for="inputState" class="form-label" style="font-weight:bold">Status Psikologis</label>
                      <select required id="inputState" name="psiko" class="form-select">
                        <option value="<?php echo $awal['psiko'] ?>"><?php echo $awal['psiko'] ?></option>
                        <option value="Tidak ada kelainan">Tidak ada kelainan</option>
                        <option value="Cemas">Cemas</option>
                        <option value="Takut">Takut</option>
                        <option value="Marah">Marah</option>
                        <option value="Sedih">Sedih</option>
                      </select>
                    </div>
                    <!-- <div class="col-md-12">
                      <label for="inputName5" class="form-label">Lain-Lain Psiko</label>
                      <input type="text" class="form-control"  name="psiko" id="inputName5" placeholder="Masukkan status psikologis">
                    </div> -->

                    <div class="col-md-12">
                      <div class="row">
                        <label for="inputName5" class="form-label" style="font-weight:bold">Sosial Ekonomi</label><br>
                        <div class="col-6">
                          a. Status Pernikahan :<input type="text" class="form-control" required name="status_nikah" id="inputName5" placeholder="" value="<?php echo $p['status_nikah'] ?>" style="margin-bottom:8px">
                        </div>
                        <div class="col-6">
                          b. Pekerjaan: <input type="text" class="form-control" required name="pekerjaan" id="inputName5" placeholder="" value="<?php echo $p['pekerjaan'] ?>" style="margin-bottom:8px">
                        </div>
                        <div class="col-6">
                          c. Tempat Tinggal:
                          <select required name="status_tinggal" class="form-control" id="" style="margin-bottom:8px">
                            <option value="<?php echo $awal['status_tinggal'] ?>" hidden><?php echo $awal['status_tinggal'] ?></option>
                            <option value="Sendiri">Sendiri</option>
                            <option value="Keluarga">Keluarga</option>
                            <option value="Lainnya">Lainnya</option>
                          </select>
                        </div>
                        <div class="col-6">
                          d. Hubungan Pasien & Keluarga:
                          <select required name="hub_keluarga" class="form-control" id="" style="margin-bottom:8px">
                            <option value="<?php echo $awal['hub_keluarga'] ?>" hidden><?php echo $awal['hub_keluarga'] ?></option>
                            <option value="Baik">Baik</option>
                            <option value="Tidak Baik">Tidak Baik</option>
                          </select>
                        </div>
                        <div class="col-6">
                          e. Pengobatan Alternatif:
                          <select required name="pengobatan" class="form-control" id="" style="margin-bottom:8px">
                            <option value="<?php echo $awal['pengobatan'] ?>" hidden><?php echo $awal['pengobatan'] ?></option>
                            <option value="Tidak">Tidak</option>
                            <option value="Iya">Iya</option>
                          </select>
                        </div>
                        <div class="col-6">
                          f. Pantangan:
                          <select required name="pantangan" class="form-control" id="" style="margin-bottom:8px">
                            <option value="<?php echo $awal['pantangan'] ?>" hidden><?php echo $awal['pantangan'] ?></option>
                            <option value="Tidak">Tidak</option>
                            <option value="Iya">Iya</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <label for="inputName5" class="form-label" style="font-weight:bold">Spiritual</label>
                      <select required name="spiritual" class="form-control" id="" style="margin-bottom:8px">
                        <option value="<?php echo $awal['spiritual'] ?>" hidden><?php echo $awal['spiritual'] ?></option>
                        <option value="Mandiri">Mandiri</option>
                        <option value="Bantuan">Bantuan</option>
                      </select>
                    </div>

                    <div class="col-md-12">
                      <div class="row">
                        <label for="inputName5" class="form-label" style="font-weight:bold">Status Fungsional</label><br>
                        <div class="col-6">
                          a. Cacat Tubuh
                          <select required name="fungsional_cacat" class="form-control" id="" style="margin-bottom:8px">
                            <option value="<?php echo $awal['fungsional_cacat'] ?>" hidden><?php echo $awal['fungsional_cacat'] ?></option>
                            <option value="Tidak">Tidak</option>
                            <option value="Ada">Ada</option>
                          </select>
                        </div>
                        <div class="col-6">
                          b. Alat Bantu
                          <select required name="fungsional_alat" class="form-control" id="" style="margin-bottom:8px">
                            <option value="<?php echo $awal['fungsional_alat'] ?>" hidden><?php echo $awal['fungsional_alat'] ?></option>
                            <option value="Tidak">Tidak</option>
                            <option value="Tongkat">Tongkat</option>
                            <option value="Kursi Roda">Kursi Roda</option>
                            <option value="Lainnya">Lainnya</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <label for="inputName5" class="form-label" style="font-weight:bold">Skrining Status Gizi</label>
                      <p style="color:blue">Ya = 1 , Tidak = 0</p>
                      <div class="row">
                        <div class="col-4">
                          <p>1. Apakah pasien terlihat kurus ?</p>
                          <input type="number" oninput="hitungGizi()" name="no1" id="no1" class="form-control" value="<?php echo $awal['no1'] ?>">
                        </div>
                        <div class="col-4">
                          <p>2. Apakah pakaian anda terasa longgar ?</p>
                          <input type="number" oninput="hitungGizi()" name="no2" id="no2" class="form-control" value="<?php echo $awal['no2'] ?>">
                        </div>
                        <div class="col-4">
                          <p>3. Apakah akhir-akhir ini anda kehilangan berat badan yang tidak sengaja ?</p>
                          <input type="number" oninput="hitungGizi()" name="no3" id="no3" class="form-control" value="<?php echo $awal['no3'] ?>">
                        </div>
                        <div class="col-4">
                          <p>4. Apakah anda mengalami penurunan berat badan selama 1 minggu terakhir ?</p>
                          <input type="number" oninput="hitungGizi()" name="no4" id="no4" class="form-control" value="<?php echo $awal['no4'] ?>">
                        </div>
                        <div class="col-4">
                          <p>5. Apakah anda menderita suatu penyakit yang menyebabkan adanya perubahan jumlah jenis makanan yang anda makan ?</p>
                          <input type="number" oninput="hitungGizi()" name="no5" id="no5" class="form-control" value="<?php echo $awal['no5'] ?>">
                        </div>
                        <div class="col-4">
                          <p>6. Apakah anda merasa lemah, loyo dan tidak bertenaga ?</p>
                          <input type="number" oninput="hitungGizi()" name="no6" id="no6" class="form-control" value="<?php echo $awal['no6'] ?>">
                        </div>
                      </div>
                      <h5 class="mb-0"><b>Jumlah : <span id="totalGizi"></span> (<span id="interpretasiGizi"></span>)</b></h5>

                    </div>


                    <div>
                      <h5 class="card-title">Riwayat Vaksinasi</h5>
                    </div>
                    <div class="col-6">
                      <label for="inputCity" class="form-label">Nama Vaksin </label>
                      <input required type="text" class="form-control" id="inputCity" placeholder="Masukkan Vaksin" name="nama_vaksin" value="<?php echo $awal['nama_vaksin'] ?>">
                    </div>
                    <div class="col-6">
                      <label for="inputCity" class="form-label">Pemberian Ke-</label>
                      <input required type="text" class="form-control" id="inputCity" placeholder="Masukkan Email Pasien" name="pemberian_vaksin" value="<?php echo $awal['pemberian_vaksin'] ?>">
                    </div>
                    <div class="col-12">
                      <label for="inputCity" class="form-label">Tanggal Pemberian</label>
                      <input required type="date" class="form-control" id="inputCity" placeholder="Masukkan Email Pasien" name="tgl_vaksin" value="<?= date("Y-m-d") ?>">
                    </div>

                    <br>
                    <br>

                    <div>
                      <h5 class="card-title">Tanda-Tanda Vital</h5>
                    </div>

                    <div class="col-6">
                      <label for="inputCity" class="form-label">Suhu Tubuh</label>
                      <div class="input-group mb-6">
                        <input required type="text" class="form-control" placeholder="Suhu Tubuh" name="suhu_tubuh" aria-describedby="basic-addon2" value="<?php echo $awal['suhu_tubuh'] ?>">
                        <span class="input-group-text" id="basic-addon2">celcius</span>
                      </div>
                    </div>

                    <div class="col-6">
                      <label for="inputCity" class="form-label">Saturasi Oksigen</label>
                      <div class="input-group mb-6">
                        <input required type="text" class="form-control" placeholder="Saturasi Oksigen" name="oksigen" aria-describedby="basic-addon2" value="<?php echo $awal['oksigen'] ?>">
                        <span class="input-group-text" id="basic-addon2">%</span>
                      </div>
                    </div>

                    <div class="col-6">

                      <label for="inputCity" class="form-label">Sistole</label>
                      <div class="input-group mb-6">
                        <input required type="text" class="form-control" placeholder="Tekanan Darah" name="sistole" aria-describedby="basic-addon2" value="<?php echo $awal['sistole'] ?>">
                        <span class="input-group-text" id="basic-addon2">mmHg</span>
                      </div>
                    </div>

                    <div class="col-6">
                      <label for="inputCity" class="form-label">Distole</label>
                      <div class="input-group mb-6">
                        <input required type="text" class="form-control" placeholder="Tekanan Darah" name="distole" aria-describedby="basic-addon2" value="<?php echo $awal['distole'] ?>">
                        <span class="input-group-text" id="basic-addon2">mmHg</span>
                      </div>
                    </div>

                    <div class="col-6">
                      <label for="inputCity" class="form-label">Nadi</label>
                      <div class="input-group mb-6">
                        <input required type="text" class="form-control" placeholder="Denyut Nadi" name="nadi" aria-describedby="basic-addon2" value="<?php echo $awal['nadi'] ?>">
                        <span class="input-group-text" id="basic-addon2">kali/menit</span>
                      </div>
                    </div>

                    <div class="col-6">
                      <label for="inputCity" class="form-label">Frekuensi Pernafasan</label>
                      <div class="input-group mb-6">
                        <input required type="text" class="form-control" placeholder="Frekuensi Pernafasan" name="frek_nafas" aria-describedby="basic-addon2" value="<?php echo $awal['frek_nafas'] ?>">
                        <span class="input-group-text" id="basic-addon2">kali/menit</span>
                      </div>
                    </div>

                    <br>
                    <br>

                    <div>
                      <h5 class="card-title">Pemeriksaan Fisik</h5>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <label for="inputCity" class="form-label">Lingkar Kepala</label>
                        <div class="input-group mb-6">
                          <input required value="<?= $awal['kepala'] ?>" type="text" class="form-control" placeholder="Lingkar Kepala" name="kepala" aria-describedby="basic-addon2">
                          <span class="input-group-text" id="basic-addon2">cm</span>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <label for="inputCity" class="form-label">Lingkar Perut</label>
                        <div class="input-group mb-6">
                          <input required value="<?= $awal['perut'] ?>" type="text" class="form-control" placeholder="Lingkar Perut" name="perut" aria-describedby="basic-addon2">
                          <span class="input-group-text" id="basic-addon2">cm</span>
                        </div>
                      </div>
                    </div>

                    <!-- <div class="col-md-6">
                        <label for="inputName5" class="form-label">Mata</label>
                        <input value="-" type="text" class="form-control"  name="mata" id="inputName5" placeholder="Mata">
                      </div>
      
                      <div class="col-md-6">
                        <label for="inputName5" class="form-label">Telinga</label>
                        <input value="-" type="text" class="form-control"  name="telinga" id="inputName5" placeholder="Telinga">
                      </div>
      
                      <div class="col-md-6">
                        <label for="inputName5" class="form-label">Hidung</label>
                        <input value="-" type="text" class="form-control"  name="hidung" id="inputName5" placeholder="Hidung">
                      </div>
      
                       <div class="col-md-6">
                        <label for="inputName5" class="form-label">Rambut</label>
                        <input value="-" type="text" class="form-control"  name="rambut" id="inputName5" placeholder="Rambut">
                      </div>
      
                       <div class="col-md-6">
                        <label for="inputName5" class="form-label">Bibir</label>
                        <input value="-" type="text" class="form-control"  name="bibir" id="inputName5" placeholder="Bibir">
                      </div>
                       <div class="col-md-6">
                        <label for="inputName5" class="form-label">Gigi Geligi</label>
                        <input value="-" type="text" class="form-control"  name="gigi" id="inputName5" placeholder="Gigi Geligi">
                      </div>
                    </div> -->


                    <div class="col-6">
                      <label for="inputCity" class="form-label mb-0">Tinggi Badan</label><br>
                      <span style="font-size: 12px;" class="mt-0">Gunakan tanda . sebagai pengganti ,</span>
                      <div class="input-group mb-6">
                        <input required type="text" class="form-control" oninput="hitungIMT()" placeholder="Tinggi Badan" id="tb" name="tb" aria-describedby="basic-addon2" value="<?php echo $awal['tb'] ?>">
                        <span class="input-group-text" id="basic-addon2">cm</span>
                      </div>
                    </div>

                    <div class="col-6">
                      <label for="inputCity" class="form-label mb-0">Berat Badan</label><br>
                      <span style="font-size: 12px;" class="mt-0">Gunakan tanda . sebagai pengganti ,</span>
                      <div class="input-group mb-6">
                        <input required type="text" class="form-control" oninput="hitungIMT()" placeholder="Berat Badan" id="bb" name="bb" aria-describedby="basic-addon2" value="<?php echo $awal['bb'] ?>">
                        <span class="input-group-text" id="basic-addon2">kg</span>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <label for="inputCity" class="form-label">IMT</label>
                      <div class="input-group mb-6">
                        <input required type="text" class="form-control" placeholder="IMT" id="imt" name="imt" aria-describedby="basic-addon2" value="<?php echo $awal['imt'] ?>">
                        <!-- <span class="input-group-text" id="basic-addon2">celcius</span> -->
                      </div>
                    </div>
                    <!-- <h5 class="card-title">Pemeriksaan Fisik</h5> -->
                     <?php 
                      $getPemeriksaanFisik = $koneksi->query("SELECT *, COUNT(*) AS jumData FROM pemeriksaan_fisik WHERE id_regis = '$_GET[id]' AND norm = '$_GET[norm]' ORDER BY id DESC LIMIT 1")->fetch_assoc();
                     ?>
                    <h5 class=""><b>Sistem Saraf</b></h5>
                    <label for="" class="">GCS</label>
                    <div class="row">
                      <div class="col-4">
                        <label>Eye</label>
                        <input type="text" class="form-control" name="gcs_e" value="<?= $getPemeriksaanFisik['jumData'] == 1 ? $getPemeriksaanFisik['gcs_e'] : '4'?>" placeholder="E" >
                      </div>
                      <div class="col-4">
                        <label>Verbal</label>
                        <input type="text" class="form-control" name="gcs_v" value="<?= $getPemeriksaanFisik['jumData'] == 1 ? $getPemeriksaanFisik['gcs_v'] : '5'?>" placeholder="V" >
                      </div>
                      <div class="col-4">
                        <label>Motorik</label>
                        <input type="text" class="form-control" name="gcs_m" value="<?= $getPemeriksaanFisik['jumData'] == 1 ? $getPemeriksaanFisik['gcs_m'] : '6'?>" placeholder="M">
                      </div>
                      <div class="col-md-3">
                        <div class="form-check mt-2">
                          <input class="form-check-input" type="checkbox" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['rangsangan_meninggal'] == '+' ? 'checked' : '') : ''?> name="rangsangan_meninggal" id="">
                          <label class="form-check-label" for="">
                            Rangsangan Meninggal
                          </label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-check mt-2">
                          <input class="form-check-input" type="checkbox" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['refleks_fisiologis1'] == '+' ? 'checked' : '') : 'checked'?> name="refleks_fisiologis1" id="">
                          <label class="form-check-label" for="">
                            Refleks Fisiologis 1
                          </label>
                      </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-check mt-2">
                          <input class="form-check-input" type="checkbox" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['refleks_fisiologis2'] == '+' ? 'checked' : '') : 'checked'?> name="refleks_fisiologis2" id="">
                          <label class="form-check-label" for="">
                            Refleks Fisiologis 2
                          </label>
                      </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-check mt-2">
                          <input class="form-check-input" type="checkbox" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['refleks_patologis'] == '+' ? 'checked' : '') : ''?> name="refleks_patologis" id="">
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
                          <input class="form-check-input" type="checkbox" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['assistos'] == '+' ? 'checked' : '') : ''?> name="assistos" id="">
                          <label class="form-check-label" for="">
                            Ascites
                          </label>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-check mt-2">
                          <input class="form-check-input" type="checkbox" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['thympani'] == '+' ? 'checked' : '') : 'checked'?> name="thympani" id="">
                          <label class="form-check-label" for="">
                            Thympani
                          </label>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-check mt-2">
                          <input class="form-check-input" type="checkbox" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['soepel'] == '+' ? 'checked' : '') : 'checked'?> name="soepel" id="">
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
                                  <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['ntf_atas_kiri'] == '+' ? 'checked' : '') : ''?> name="ntf_atas_kiri">
                                  Atas kiri
                                </label>
                              </td>
                              <td>
                                <label for="" class="form-check">
                                  <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['ntf_atas'] == '+' ? 'checked' : '') : ''?> name="ntf_atas">
                                  Atas
                                </label>
                              </td>
                              <td>
                                <label for="" class="form-check">
                                  <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['ntf_atas_kanan'] == '+' ? 'checked' : '') : ''?> name="ntf_atas_kanan">
                                  Atas kanan
                                </label>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <label for="" class="form-check">
                                  <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['ntf_tengah_kiri'] == '+' ? 'checked' : '') : ''?> name="ntf_tengah_kiri">
                                  Tengah kiri
                                </label>
                              </td>
                              <td>
                                <label for="" class="form-check">
                                  <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['ntf_tengah'] == '+' ? 'checked' : '') : ''?> name="ntf_tengah">
                                  Tengah
                                </label>
                              </td>
                              <td>
                                <label for="" class="form-check">
                                  <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['ntf_tengah_kanan'] == '+' ? 'checked' : '') : ''?> name="ntf_tengah_kanan">
                                  Tengah kanan
                                </label>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <label for="" class="form-check">
                                  <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['ntf_bawah_kiri'] == '+' ? 'checked' : '') : ''?> name="ntf_bawah_kiri">
                                  Bawah kiri
                                </label>
                              </td>
                              <td>
                                <label for="" class="form-check">
                                  <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['ntf_bawah'] == '+' ? 'checked' : '') : ''?> name="ntf_bawah">
                                  Bawah
                                </label>
                              </td>
                              <td>
                                <label for="" class="form-check">
                                  <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['ntf_bawah_kanan'] == '+' ? 'checked' : '') : ''?> name="ntf_bawah_kanan">
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
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['bu'] == '+' ? 'checked' : '') : 'checked'?> name="bu">
                          BU
                        </label>
                        <input type="text" class="form-control" name="bu_komen" value="<?= $getPemeriksaanFisik['jumData'] == 1 ? $getPemeriksaanFisik['bu_komen'] : ''?>" placeholder="BU Keterangan">
                      </div>
    
                    </div>
                    <br>
                    <h5 class=""><b>Sistem Penglihatan</b></h5>
                    <div class="row">
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['anemis_kiri'] == '+' ? 'checked' : '') : ''?> name="anemis_kiri">
                          Konjungtiva Anemis Kiri
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['anemis_kanan'] == '+' ? 'checked' : '') : ''?> name="anemis_kanan">
                          Konjungtiva Anemis Kanan
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['ikterik_kiri'] == '+' ? 'checked' : '') : ''?> name="ikterik_kiri">
                          Sklera Ikterik Kiri
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['ikterik_kanan'] == '+' ? 'checked' : '') : ''?> name="ikterik_kanan">
                          Sklera Ikterik Kanan
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['rcl_kiri'] == '+' ? 'checked' : '') : 'checked'?> name="rcl_kiri">
                          RCL Kiri
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['rcl_kanan'] == '+' ? 'checked' : '') : 'checked'?> name="rcl_kanan">
                          RCL Kanan
                        </label>
                      </div>
                      <div class="col-12">
                        <label for="" class="mt-2">Diameter Pupil</label>
                        <div class="row">
                          <div class="col-6">
                            <input type="text" class="form-control" name="pupil_kiri" value="<?= $getPemeriksaanFisik['jumData'] == 1 ? $getPemeriksaanFisik['pupil_kiri'] : ''?>" placeholder="Pupil Kiri">
                          </div>
                          <div class="col-6">
                            <input type="text" class="form-control" name="pupil_kanan" value="<?= $getPemeriksaanFisik['jumData'] == 1 ? $getPemeriksaanFisik['pupil_kanan'] : ''?>" placeholder="Pupil Kanan">
                          </div>
                        </div>
                      </div>
                      <div class="col-12">
                        <label for="" class="mt-2">Visus</label>
                        <div class="row">
                          <div class="col-6">
                            <input type="text" class="form-control" name="visus_kiri" value="<?= $getPemeriksaanFisik['jumData'] == 1 ? $getPemeriksaanFisik['visus_kiri'] : '6/6'?>" placeholder="Visus Kiri" >
                          </div>
                          <div class="col-6">
                            <input type="text" class="form-control" name="visus_kanan" value="<?= $getPemeriksaanFisik['jumData'] == 1 ? $getPemeriksaanFisik['visus_kanan'] : '6/6'?>" placeholder="Visus Kanan" >
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
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['retraksi'] == '+' ? 'checked' : '') : ''?> name="retraksi">
                          Retraksi
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['vesikuler_kiri'] == '+' ? 'checked' : '') : ''?> name="vesikuler_kiri">
                          Vesikuler Kiri
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['vesikuler_kanan'] == '+' ? 'checked' : '') : ''?> name="vesikuler_kanan">
                          Vesikuler Kanan
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['wheezing_kiri'] == '+' ? 'checked' : '') : ''?> name="wheezing_kiri">
                          Wheezing Kiri
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['wheezing_kanan'] == '+' ? 'checked' : '') : ''?> name="wheezing_kanan">
                          Wheezing Kanan
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['rongki_kiri'] == '+' ? 'checked' : '') : ''?> name="rongki_kiri">
                          Rongki Kiri
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['rongki_kanan'] == '+' ? 'checked' : '') : ''?> name="rongki_kanan">
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
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['murmur'] == '+' ? 'checked' : '') : ''?> name="murmur">
                          Murmur
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['golop'] == '+' ? 'checked' : '') : ''?> name="golop">
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
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['nch_kiri'] == '+' ? 'checked' : '') : ''?> name="nch_kiri">
                          NCH Kirim
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['nch_kanan'] == '+' ? 'checked' : '') : ''?> name="nch_kanan">
                          NCH Kanan
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['polip_kiri'] == '+' ? 'checked' : '') : ''?> name="polip_kiri">
                          Polip Kirim
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['polip_kanan'] == '+' ? 'checked' : '') : ''?> name="polip_kanan">
                          Polip Kanan
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['conca_kiri'] == '+' ? 'checked' : '') : ''?> name="conca_kiri">
                          Conca Kiri
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['conca_kanan'] == '+' ? 'checked' : '') : ''?> name="conca_kanan">
                          Conca Kanan
                        </label>
                      </div>
                      <h6 class="mt-2"><b class="">Tenggorokan</b></h6>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['faring_hipertermis'] == '+' ? 'checked' : '') : ''?> name="faring_hipertermis">
                          Faring Hiperemis
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['halitosis'] == '+' ? 'checked' : '') : ''?> name="halitosis">
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
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['serumin_kiri'] == '+' ? 'checked' : '') : ''?> name="serumin_kiri">
                          Serumen Kiri
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['serumin_kanan'] == '+' ? 'checked' : '') : ''?> name="serumin_kanan">
                          Serumen Kanan
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" checked class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['typani_intak_kiri'] == '+' ? 'checked' : '') : ''?> name="typani_intak_kiri">
                          Tympani Intak Kiri
                        </label>
                      </div>
                      <div class="col-6">
                        <label for="" class="form-check">
                          <input type="checkbox" checked class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['typani_intak_kanan'] == '+' ? 'checked' : '') : ''?> name="typani_intak_kanan">
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
                                  <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['akral_hangat_atas_kiri'] == '+' ? 'checked' : '') : 'checked'?> name="akral_hangat_atas_kiri">
                                  Akral Hangat Atas Kiri
                                </label>
                              </td>
                              <td class="">
                                <label for="" class="form-check">
                                  <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['akral_hangat_atas_kanan'] == '+' ? 'checked' : '') : 'checked'?> name="akral_hangat_atas_kanan">
                                  Akral Hangat Atas Kanan
                                </label>
                              </td>
                            </tr>
                            <tr class="">
                              <td class="">
                                <label for="" class="form-check">
                                  <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['akral_hangat_bawah_kiri'] == '+' ? 'checked' : '') : 'checked'?> name="akral_hangat_bawah_kiri">
                                  Akral Hangat Bawah Kiri
                                </label>
                              </td>
                              <td class="">
                                <label for="" class="form-check">
                                  <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['akral_hangat_bawah_kanan'] == '+' ? 'checked' : '') : 'checked'?> name="akral_hangat_bawah_kanan">
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
                                  <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['oe_atas_kiri'] == '+' ? 'checked' : '') : ''?> name="oe_atas_kiri">
                                  OE Atas Kiri
                                </label>
                              </td>
                              <td class="">
                                <label for="" class="form-check">
                                  <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['oe_atas_kanan'] == '+' ? 'checked' : '') : ''?> name="oe_atas_kanan">
                                  OE Atas Kanan
                                </label>
                              </td>
                            </tr>
                            <tr class="">
                              <td class="">
                                <label for="" class="form-check">
                                  <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['oe_bawah_kiri'] == '+' ? 'checked' : '') : ''?> name="oe_bawah_kiri">
                                  OE Bawah Kiri
                                </label>
                              </td>
                              <td class="">
                                <label for="" class="form-check">
                                  <input type="checkbox" class="form-check-input" value="+" <?= $getPemeriksaanFisik['jumData'] == 1 ? ($getPemeriksaanFisik['oe_bawah_kanan'] == '+' ? 'checked' : '') : ''?> name="oe_bawah_kanan">
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
                                <input type="text" class="form-control" name="motorik_atas_kiri" value="<?= $getPemeriksaanFisik['jumData'] == 1 ? $getPemeriksaanFisik['motorik_atas_kiri'] : '5'?>" style="max-width: 80%;" >
                              </td>
                              <td class="">
                                <input type="text" class="form-control" name="motorik_atas_kanan" value="<?= $getPemeriksaanFisik['jumData'] == 1 ? $getPemeriksaanFisik['motorik_atas_kanan'] : '5'?>" style="max-width: 80%;" >
                              </td>
                            </tr>
                            <tr class="">
                              <td class="">
                                <input type="text" class="form-control" name="motorik_bawah_kiri" value="<?= $getPemeriksaanFisik['jumData'] == 1 ? $getPemeriksaanFisik['motorik_bawah_kiri'] : '5'?>" style="max-width: 80%;" >
                              </td>
                              <td class="">
                                <input type="text" class="form-control" name="motorik_bawah_kanan" value="<?= $getPemeriksaanFisik['jumData'] == 1 ? $getPemeriksaanFisik['motorik_bawah_kanan'] : '5'?>" style="max-width: 80%;" >
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <label for="inputCity" class="form-label">Kognitif </label>
                      <div class="input-group mb-6">
                        <select name="kognitif" class="form-control" id="">
                          <option hidden>Pilih Kognitif</option>
                          <option value="Orientasi Penuh">Orientasi Penuh</option>
                          <option value="Pelupa">Pelupa</option>
                          <option value="Bingung">Bingung</option>
                        </select>
                        <!-- <input type="text" class="form-control" placeholder="kognitif" name="kognitif" value="<?= $awal['kognitif'] ?>" aria-describedby="basic-addon2" value="<?php echo $awal['kognitif'] ?>"> -->
                      </div>
                    </div>

                    <br>
                    <br>
                    <div class="col-md-12">
                      <label for="sel" class="form-label" style="font-weight:bold">Diagnosis Keperawatan</label>
                      <select required name="diagnois_prwt" id="sel" class="form-control">
                        <option value="<?php echo $awal['diagnois_prwt'] ?>"><?php echo $awal['diagnois_prwt'] ?></option>
                        <option value="Nyeri">Nyeri</option>
                        <option value="Gangguan Perfusi Cerebral">Gangguan Perfusi Cerebral</option>
                        <option value="Cemas">Cemas</option>
                        <option value="Sensori Persepsi">Sensori Persepsi</option>
                        <option value="Hipertermia">Hipertermia</option>
                        <option value="Kerusakan Integritas Kulit">Kerusakan Integritas Kulit</option>
                        <option value="Gangguan Perfusi Jaringan">Gangguan Perfusi Jaringan</option>
                        <option value="Image Body">Image Body</option>
                        <option value="Gangguan Mobilitas Fisik">Gangguan Mobilitas Fisik</option>
                        <option value="Kurang Pengetahuan">Kurang Pengetahuan</option>
                        <option value="Perubahan Nutrisi Kurang Dari Kebutuhan">Perubahan Nutrisi Kurang Dari Kebutuhan</option>
                        <option value="Kekurangan Nutrisi">Kekurangan Nutrisi</option>
                        <option value="lain">Lain lain</option>
                      </select>
                      <div id="ft" style="display: none;">
                        <label for="sel" class="form-label" style="font-weight:bold">Diagnosis Keperawatan Lain</label>
                        <input type="text" id="lain" style="display: none;" class="form-control">
                      </div>
                    </div>

                    <script>
                      const sel = document.getElementById("sel");
                      const lain = document.getElementById("lain");
                      const ft = document.getElementById("ft");

                      sel.addEventListener("change", function() {
                        if (sel.value === "lain") {
                          lain.style.display = "block";
                          ft.style.display = "block";
                          sel.setAttribute("name", "tes");
                          lain.setAttribute("name", "diagnois_prwt");
                        } else {
                          lain.style.display = "none";
                          ft.style.display = "none";
                        }
                      });
                    </script>

                    <div class="col-md-12">
                      <label for=""><b>Rencana Asuh Keperawatan</b></label><br>
                      <textarea name="rencana_asuh" id="" class="form-control" style="width:100%; height:100%" value="<?php echo $awal['rencana_asuh'] ?>"><?php echo $awal['rencana_asuh'] ?></textarea>
                    </div>





                    <div class="text-center" style="margin-top: 50px; margin-bottom: 80px;">
                      <?php
                      $tes = $koneksi->query("SELECT * FROM kajian_awal WHERE norm='$awal[norm]';")->fetch_assoc();

                      ?>
                      <button type="submit" name="save" class="btn btn-primary">Simpan</button>
                      <?php if (empty($tes)) { ?>
                        <!-- <button type="submit" name="save" class="btn btn-primary">Simpan</button> -->
                      <?php } else { ?>
                        <!-- <button type="submit" name="ubah" class="btn btn-info">Ubah</button> -->
                      <?php } ?>
                      <button type="reset" class="btn btn-secondary">Reset</button>
                    </div>
                  </form><!-- End Multi Columns Form -->

                </div>
              </div>
            </div>
            <br>
            <div class="col-md-12">
              <div class="card shadow p-3">
                <h5 class="card-title">
                  Riwayat Kajian Awal
                </h5>
                <br>
                <table class="table">
                  <thead>
                    <tr>
                      <th>Nama</th>
                      <th>Tanggal</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $getKajiaAwal = $koneksi->query("SELECT * FROM kajian_awal WHERE norm = '$_GET[norm]'");
                    foreach ($getKajiaAwal as $item) {
                    ?>
                      <tr>
                        <td><?= $item['nama_pasien'] ?></td>
                        <td><?= $item['tgl_rm'] ?></td>
                        <td><a href="index.php?halaman=resume&id=<?= $_GET['id'] ?>&norm=<?= $_GET['norm'] ?>&ubah&kj=<?= $item['id_rm'] ?>" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i></a></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </main><!-- End #main -->
  <script>
    function hitungIMT() {
      var tinggi_b = document.getElementById("tb").value;
      var berat_b = document.getElementById("bb").value;
      var imtt = berat_b / ((tinggi_b / 100) * (tinggi_b / 100));
      document.getElementById("imt").value = imtt.toFixed(2);
    }

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

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


</body>

</html>


<?php


if (isset($_POST['save'])) {
  // Post Default
    $nama_pasien = htmlspecialchars($_POST["nama_pasien"] ?? '');
    $norm = htmlspecialchars($_POST["norm"] ?? '');
    $status_nikah = htmlspecialchars($_POST["status_nikah"] ?? '');
    $pekerjaan = htmlspecialchars($_POST["pekerjaan"] ?? '');
    $keluhan_utama = htmlspecialchars($_POST["keluhan_utama"] ?? '');
    $riwayat_penyakit = htmlspecialchars($_POST["riwayat_penyakit"] ?? '');
    $riwayat_alergi = htmlspecialchars($_POST["riwayat_alergi"] ?? '');
    $nama_vaksin = htmlspecialchars($_POST["nama_vaksin"] ?? '');
    $pemberian_vaksin = htmlspecialchars($_POST["pemberian_vaksin"] ?? '');
    $tgl_vaksin = htmlspecialchars($_POST["tgl_vaksin"] ?? '');
    $suhu_tubuh = htmlspecialchars($_POST["suhu_tubuh"] ?? '');
    $sistole = htmlspecialchars($_POST["sistole"] ?? '');
    $distole = htmlspecialchars($_POST["distole"] ?? '');
    $nadi = htmlspecialchars($_POST["nadi"] ?? '');
    $frek_nafas = htmlspecialchars($_POST["frek_nafas"] ?? '');
    $kepala = htmlspecialchars($_POST["kepala"] ?? '');
    $perut = htmlspecialchars($_POST["perut"] ?? '');
    $mata = htmlspecialchars($_POST["mata"] ?? '');
    $telinga = htmlspecialchars($_POST["telinga"] ?? '');
    $hidung = htmlspecialchars($_POST["hidung"] ?? '');
    $rambut = htmlspecialchars($_POST["rambut"] ?? '');
    $bibir = htmlspecialchars($_POST["bibir"] ?? '');
    $gigi = htmlspecialchars($_POST["gigi"] ?? '');
    $lidah = htmlspecialchars($_POST["lidah"] ?? '');
    $langit_langit = htmlspecialchars($_POST["langit_langit"] ?? '');
    $leher = htmlspecialchars($_POST["leher"] ?? '');
    $tenggorokan = htmlspecialchars($_POST["tenggorokan"] ?? '');
    $tonsil = htmlspecialchars($_POST["tonsil"] ?? '');
    $dada = htmlspecialchars($_POST["dada"] ?? '');
    $payudara = htmlspecialchars($_POST["payudara"] ?? '');
    $punggung = htmlspecialchars($_POST["punggung"] ?? '');
    $genital = htmlspecialchars($_POST["genital"] ?? '');
    $anus = htmlspecialchars($_POST["anus"] ?? '');
    $lengan_atas = htmlspecialchars($_POST["lengan_atas"] ?? '');
    $lengan_bawah = htmlspecialchars($_POST["lengan_bawah"] ?? '');
    $jari_tangan = htmlspecialchars($_POST["jari_tangan"] ?? '');
    $kuku_tangan = htmlspecialchars($_POST["kuku_tangan"] ?? '');
    $persendian_tangan = htmlspecialchars($_POST["persendian_tangan"] ?? '');
    $tungkai_atas = htmlspecialchars($_POST["tungkai_atas"] ?? '');
    $tungkai_bawah = htmlspecialchars($_POST["tungkai_bawah"] ?? '');
    $jari_kaki = htmlspecialchars($_POST["jari_kaki"] ?? '');
    $kuku_kaki = htmlspecialchars($_POST["kuku_kaki"] ?? '');
    $persendian_kaki = htmlspecialchars($_POST["persendian_kaki"] ?? '');
    $tb = htmlspecialchars($_POST["tb"] ?? '');
    $bb = htmlspecialchars($_POST["bb"] ?? '');
    $imt = htmlspecialchars($_POST["imt"] ?? '');
    $psiko = htmlspecialchars($_POST["psiko"] ?? '');
    $sosial = htmlspecialchars($_POST["sosial"] ?? '' ?? '');
    $spiritual = htmlspecialchars($_POST["spiritual"] ?? '');
    $umur_pasien = htmlspecialchars($_POST["umur_pasien"] ?? '');
    $tipe_umur=htmlspecialchars($_POST["tipe_umur"] ?? '');
    $jk = htmlspecialchars($_POST["jk"] ?? '');
    $no1 = htmlspecialchars($_POST["no1"] ?? '');
    $no2 = htmlspecialchars($_POST["no2"] ?? '');
    $no3 = htmlspecialchars($_POST["no3"] ?? '');
    $no4 = htmlspecialchars($_POST["no4"] ?? '');
    $no5 = htmlspecialchars($_POST["no5"] ?? '');
    $no6 = htmlspecialchars($_POST["no6"] ?? '');
    $username_admin = htmlspecialchars($_POST["username_admin"] ?? '');
    $idadmin = htmlspecialchars($_POST["idadmin"] ?? '');
    $status_log = htmlspecialchars($_POST["status_log"] ?? '');
    $susunan_saraf = htmlspecialchars($_POST['susunan_saraf'] ?? '');
    $sistem_pengelihatan = htmlspecialchars($_POST['sistem_pengelihatan'] ?? '');
    $sistem_pendengaran = htmlspecialchars($_POST['sistem_pendengaran'] ?? '');
    $sistem_pernafasan = htmlspecialchars($_POST['sistem_pernafasan'] ?? '');
    $sistem_pencernaan = htmlspecialchars($_POST['sistem_pencernaan'] ?? '');
    $sistem_jantung = htmlspecialchars($_POST['sistem_jantung'] ?? '');
    $kognitif = htmlspecialchars($_POST['kognitif'] ?? '');
  // End Post Default

  // $foto=$_FILES['foto']['name'];
  // $lokasi=$_FILES['foto']['tmp_name'];
  // move_uploaded_file($lokasi, '../pasien/foto/'.$foto);
  $koneksi->query("INSERT INTO kajian_awal (nama_pasien, keluhan_utama, riwayat_penyakit, pemberian_vaksin,  riwayat_alergi, tgl_vaksin, nama_vaksin, suhu_tubuh, sistole, distole, nadi, frek_nafas, kepala, perut, mata, telinga, hidung, rambut, bibir, gigi, lidah, langit_langit, leher, tenggorokan, tonsil, dada, payudara, punggung, genital, anus, lengan_atas, lengan_bawah, jari_tangan, tb, bb, imt, spiritual, umur_pasien, jk, status_tinggal, hub_keluarga,pengobatan,pantangan,fungsional_cacat, fungsional_alat, no1, no2, no3, no4, no5, no6, norm, psiko, diagnois_prwt, rencana_asuh, oksigen) VALUES ('$nama_pasien', '$keluhan_utama', '$riwayat_penyakit', '$pemberian_vaksin', '$riwayat_alergi', '$tgl_vaksin', '$nama_vaksin', '$suhu_tubuh', '$sistole', '$distole', '$nadi',  '$frek_nafas', '$kepala', '$perut', '$mata', '$telinga', '$hidung', '$rambut', '$bibir', '$gigi', '$lidah', '$langit_langit', '$leher', '$tenggorokan', '$tonsil', '$dada', '$payudara', '$punggung', '$genital', '$anus', '$lengan_atas', '$lengan_bawah', '$jari_tangan', '$tb', '$bb', '$imt', '$spiritual', '$umur_pasien', '$jk', '$_POST[status_tinggal]', '$_POST[hub_keluarga]', '$_POST[pengobatan]', '$_POST[pantangan]', '$_POST[fungsional_cacat]', '$_POST[fungsional_alat]', '$no1', '$no2', '$no3', '$no4', '$no5', '$no6', '$norm','$psiko', '$_POST[diagnois_prwt]', '$_POST[rencana_asuh]', '$_POST[oksigen]')");

  $koneksi->query("UPDATE pasien SET umur='$umur_pasien', pekerjaan='$pekerjaan', status_nikah='$status_nikah' WHERE no_rm='$_GET[norm]';");

  $koneksi->query("INSERT INTO log_user (status_log, username_admin, idadmin) VALUES ('$status_log', '$username_admin', '$idadmin')");

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

  $koneksi->query("INSERT INTO pemeriksaan_fisik (id_regis, norm, gcs_e, gcs_v, gcs_m, rangsangan_meninggal, refleks_fisiologis1, refleks_fisiologis2, refleks_patologis, flat, hl, assistos, thympani, soepel, ntf_atas_kiri, ntf_atas, ntf_atas_kanan, ntf_tengah_kiri, ntf_tengah, ntf_tengah_kanan, ntf_bawah_kiri, ntf_bawah, ntf_bawah_kanan, bu, bu_komen, anemis_kiri, anemis_kanan, ikterik_kiri, ikterik_kanan, rcl_kiri, rcl_kanan, pupil_kiri, pupil_kanan, visus_kiri, visus_kanan, torax, retraksi, vesikuler_kiri, vesikuler_kanan, wheezing_kiri, wheezing_kanan, rongki_kiri, rongki_kanan, s1s2, murmur, golop, nch_kiri, nch_kanan, polip_kiri, polip_kanan, conca_kiri, conca_kanan, faring_hipertermis, halitosis, pembesaran_tonsil, serumin_kiri, serumin_kanan, typani_intak_kiri, typani_intak_kanan, pembesaran_getah_bening, akral_hangat_atas_kiri, akral_hangat_atas_kanan, akral_hangat_bawah_kiri, akral_hangat_bawah_kanan, oe_atas_kiri, oe_atas_kanan, oe_bawah_kiri, oe_bawah_kanan, crt, motorik_atas_kiri, motorik_atas_kanan, motorik_bawah_kiri, motorik_bawah_kanan, kognitif) VALUES ('$_GET[id]', '$_GET[norm]', '$gcs_e', '$gcs_v', '$gcs_m', '$rangsangan_meninggal', '$refleks_fisiologis1', '$refleks_fisiologis2', '$refleks_patologis', '$flat', '$hl', '$assistos', '$thympani', '$soepel', '$ntf_atas_kiri', '$ntf_atas', '$ntf_atas_kanan', '$ntf_tengah_kiri', '$ntf_tengah', '$ntf_tengah_kanan', '$ntf_bawah_kiri', '$ntf_bawah', '$ntf_bawah_kanan', '$bu', '$bu_komen', '$anemis_kiri', '$anemis_kanan', '$ikterik_kiri', '$ikterik_kanan', '$rcl_kiri', '$rcl_kanan', '$pupil_kiri', '$pupil_kanan', '$visus_kiri', '$visus_kanan', '$torax', '$retraksi', '$vesikuler_kiri', '$vesikuler_kanan', '$wheezing_kiri', '$wheezing_kanan', '$rongki_kiri', '$rongki_kanan', '$s1s2', '$murmur', '$golop', '$nch_kiri', '$nch_kanan', '$polip_kiri', '$polip_kanan', '$conca_kiri', '$conca_kanan', '$faring_hipertermis', '$halitosis', '$pembesaran_tonsil', '$serumin_kiri', '$serumin_kanan', '$typani_intak_kiri', '$typani_intak_kanan', '$pembesaran_getah_bening', '$akral_hangat_atas_kiri', '$akral_hangat_atas_kanan', '$akral_hangat_bawah_kiri', '$akral_hangat_bawah_kanan', '$oe_atas_kiri', '$oe_atas_kanan', '$oe_bawah_kiri', '$oe_bawah_kanan', '$crt', '$motorik_atas_kiri', '$motorik_atas_kanan', '$motorik_bawah_kiri', '$motorik_bawah_kanan', '$kognitif')");


  if (mysqli_affected_rows($koneksi) > 0) {
    echo "
      <script>
        alert('Data berhasil ditambah');
        document.location.href='index.php?halaman=daftarregistrasi&day';
      </script>
    ";
  } else {
    echo "
      <script>
        alert('GAGAL MENGHAPUS DATA');
        document.location.href='index.php?halaman=daftarregistrasi&day';
      </script>
    ";
  }
}

if (isset($_POST['ubah'])) {
  $nama_pasien = htmlspecialchars($_POST["nama_pasien"]);
  $norm = htmlspecialchars($_POST["norm"]);
  $keluhan_utama = htmlspecialchars($_POST["keluhan_utama"]);
  $riwayat_penyakit = htmlspecialchars($_POST["riwayat_penyakit"]);
  $riwayat_alergi = htmlspecialchars($_POST["riwayat_alergi"]);
  $nama_vaksin = htmlspecialchars($_POST["nama_vaksin"]);
  $pemberian_vaksin = ($_POST["pemberian_vaksin"]);
  $tgl_vaksin = htmlspecialchars($_POST["tgl_vaksin"]);
  $suhu_tubuh = htmlspecialchars($_POST["suhu_tubuh"]);
  $sistole = htmlspecialchars($_POST["sistole"]);
  $distole = htmlspecialchars($_POST["distole"]);
  $nadi = htmlspecialchars($_POST["nadi"]);
  $frek_nafas = htmlspecialchars($_POST["frek_nafas"]);
  $kepala = htmlspecialchars($_POST["kepala"]);
  $perut = htmlspecialchars($_POST["perut"]);
  $mata = htmlspecialchars($_POST["mata"]);
  $telinga = htmlspecialchars($_POST["telinga"]);
  $hidung = htmlspecialchars($_POST["hidung"]);
  $rambut = htmlspecialchars($_POST["rambut"]);
  $bibir = htmlspecialchars($_POST["bibir"]);
  $gigi = htmlspecialchars($_POST["gigi"]);
  $lidah = htmlspecialchars($_POST["lidah"]);
  $langit_langit = htmlspecialchars($_POST["langit_langit"]);
  $leher = htmlspecialchars($_POST["leher"]);
  $tenggorokan = htmlspecialchars($_POST["tenggorokan"]);
  $tonsil = htmlspecialchars($_POST["tonsil"]);
  $dada = htmlspecialchars($_POST["dada"]);
  $payudara = htmlspecialchars($_POST["payudara"]);
  $punggung = htmlspecialchars($_POST["punggung"]);
  $genital = htmlspecialchars($_POST["genital"]);
  $anus = htmlspecialchars($_POST["anus"]);
  $lengan_atas = htmlspecialchars($_POST["lengan_atas"]);
  $lengan_bawah = htmlspecialchars($_POST["lengan_bawah"]);
  $jari_tangan = htmlspecialchars($_POST["jari_tangan"]);
  $kuku_tangan = htmlspecialchars($_POST["kuku_tangan"]);
  $persendian_tangan = htmlspecialchars($_POST["persendian_tangan"]);
  $tungkai_atas = htmlspecialchars($_POST["tungkai_atas"]);
  $tungkai_bawah = htmlspecialchars($_POST["tungkai_bawah"]);
  $jari_kaki = htmlspecialchars($_POST["jari_kaki"]);
  $kuku_kaki = htmlspecialchars($_POST["kuku_kaki"]);
  $persendian_kaki = htmlspecialchars($_POST["persendian_kaki"]);
  $tb = htmlspecialchars($_POST["tb"]);
  $bb = htmlspecialchars($_POST["bb"]);
  $imt = htmlspecialchars($_POST["imt"]);
  $psiko = htmlspecialchars($_POST["psiko"]);
  $sosial = htmlspecialchars($_POST["sosial"]);
  $spiritual = htmlspecialchars($_POST["spiritual"]);
  $umur_pasien = htmlspecialchars($_POST["umur_pasien"]);
  // $tipe_umur=htmlspecialchars($_POST["tipe_umur"]);
  $jk = htmlspecialchars($_POST["jk"]);
  $no1 = htmlspecialchars($_POST["no1"]);
  $no2 = htmlspecialchars($_POST["no2"]);
  $no3 = htmlspecialchars($_POST["no3"]);
  $no4 = htmlspecialchars($_POST["no4"]);
  $no5 = htmlspecialchars($_POST["no5"]);
  $no6 = htmlspecialchars($_POST["no6"]);
  $username_admin = htmlspecialchars($_POST["username_admin"]);
  $idadmin = htmlspecialchars($_POST["idadmin"]);
  $status_log = htmlspecialchars($_POST["status_log"]);

  $koneksi->query("UPDATE kajian_awal SET nama_pasien='$nama_pasien', keluhan_utama='$keluhan_utama', riwayat_penyakit='$riwayat_penyakit', pemberian_vaksin='$pemberian_vaksin', riwayat_alergi='$riwayat_alergi', tgl_vaksin='$tgl_vaksin', nama_vaksin='$nama_vaksin', suhu_tubuh='$suhu_tubuh', sistole='$sistole', distole='$distole', nadi='$nadi', frek_nafas='$frek_nafas', kepala='$kepala', perut='$perut', mata='$mata', telinga='$telinga', hidung='$hidung', rambut='$rambut', bibir='$bibir', gigi='$gigi', lidah='$lidah', langit_langit='$langit_langit', leher='$leher', tenggorokan='$tenggorokan', tonsil='$tonsil', dada='$dada', payudara='$payudara', punggung='$punggung', genital='$genital', anus='$anus', lengan_atas='$lengan_atas', lengan_bawah='$lengan_bawah', jari_tangan='$jari_tangan', kuku_tangan='$kuku_tangan', persendian_tangan='$persendian_tangan', tungkai_atas='$tungkai_atas', tungkai_bawah='$tungkai_bawah', jari_kaki='$jari_kaki', kuku_kaki='$kuku_kaki', persendian_kaki='$persendian_kaki', tb='$tb', bb='$bb', imt='$imt', spiritual='$spiritual', umur_pasien='$umur_pasien', jk='$jk', status_tinggal='$_POST[status_tinggal]', hub_keluarga='$_POST[hub_keluarga]', pengobatan='$_POST[pengobatan]', pantangan='$_POST[pantangan]', fungsional_cacat='$_POST[fungsional_cacat]', fungsional_alat='$_POST[fungsional_alat]', no1='$no1', no2='$no2', no3='$no3', no4='$no4', no5='$no5', no6='$no6', norm='$norm',psiko='$psiko', diagnois_prwt='$_POST[diagnois_prwt]', rencana_asuh='$_POST[rencana_asuh]', oksigen='$_POST[oksigen]' WHERE norm='$_GET[norm]'");
  $koneksi->query("INSERT INTO log_user (status_log, username_admin, idadmin) VALUES ('$status_log', '$username_admin', '$idadmin')");

  if (mysqli_affected_rows($koneksi) > 0) {
    echo "
      <script>
        alert('Data berhasil diubah');
        document.location.href='index.php?halaman=daftarregistrasi&day';
      </script>
    ";
  } else {
    echo "
      <script>
        alert('GAGAL MENGHAPUS DATA');
        document.location.href='index.php?halaman=daftarregistrasi&day';
      </script>
    ";
  }
}

?>
<script type="text/javascript">
  // Setelah dokumen selesai dimuat
  document.addEventListener("DOMContentLoaded", function() {
    // Fokuskan ke input dengan id "username"
    document.getElementById("keluhan_utama").focus();
  });
</script>