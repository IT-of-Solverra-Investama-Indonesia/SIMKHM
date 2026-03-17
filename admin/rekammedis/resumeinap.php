<?php
// error_reporting(0);
$username = $_SESSION['admin']['username'];
$ambil = $koneksi->query("SELECT * FROM admin  WHERE username='$username';");
$pasien = $koneksi->query("SELECT * FROM registrasi_rawat  WHERE idrawat='$_GET[id]';");
$pasien = $pasien->fetch_assoc();
$p = $koneksi->query("SELECT * FROM pasien  WHERE no_rm='$_GET[norm]';");
$p = $p->fetch_assoc();
$id_pasien = $p['idpasien'];
$umur_pasien = $p['umur'];
$jk = $p['jenis_kelamin'];
$rm = $koneksi->query("SELECT * FROM rekam_medis  WHERE norm='$_GET[norm]';");
$rm = $rm->fetch_assoc();
if (isset($_GET['kj'])) {
  $awal = $koneksi->query("SELECT * FROM kajian_awal_inap WHERE norm='$_GET[norm]' AND id_rm = '$_GET[kj]' ORDER BY id_rm DESC LIMIT 1;");
} else {
  $awal = $koneksi->query("SELECT * FROM kajian_awal_inap WHERE norm='$_GET[norm]' ORDER BY id_rm DESC LIMIT 1;");
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
  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Select2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



<body>

  <main>
    <div class="container">
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
        <div class="container">
          <div class="row">
            <div class="col-lg-12 col-md-12 d-flex">
              <div class="card" style="max-width: 100%; display: inline-flex; ">
                <div class="card-body">
                  <h5 class="card-title">Data Pasien</h5>

                  <!-- Multi Columns Form -->
                  <form class="row g-3" method="post" enctype="multipart/form-data">
                    <?php foreach ($ambil as $pecah) : ?>
                      <input type="hidden" name="idadmin" class="form-control" value="<?php echo $pecah['idadmin'] ?>" placeholder="Masukkan nama karyawan">
                      <input type="hidden" name="username_admin" class="form-control" value="<?php echo $pecah['namalengkap'] ?>" placeholder="Masukkan nama karyawan">
                      <input type="hidden" name="status_log" class="form-control" value="<?php echo $pecah['namalengkap'] ?> Memperbarui Data Kajian Awal" placeholder="Masukkan nama karyawan">
                    <?php endforeach ?>
                    <div class="col-md-12">
                      <label for="inputName5" class="form-label">Nama Pasien</label>
                      <input type="text" class="form-control" id="inputName5" placeholder="Masukkan Nama Pasien" value="<?php echo $pasien['nama_pasien'] ?>" name="nama_pasien">
                    </div>
                    <div class="col-md-12">
                      <label for="inputName5" class="form-label">No. RM Pasien</label>
                      <input type="text" class="form-control" id="inputName5" placeholder="Masukkan No RM Pasien" value="<?php echo $pasien['no_rm'] ?>" name="norm">
                    </div>
                    <div class="col-md-12">
                      <label for="inputName5" class="form-label">Umur Pasien</label>
                      <input type="text" class="form-control" id="inputName5" placeholder="Masukkan Umur Pasien" value="<?php echo $p['umur'] ?>" name="jk">
                    </div>
                    <div class="col-md-12">
                      <label for="inputName5" class="form-label">NIK Pasien</label>
                      <input type="text" class="form-control" id="inputName5" placeholder="Masukkan Umur Pasien" value="<?php echo $p['no_identitas'] ?>" name="umur_pasien">
                    </div>
                    <div class="col-md-12">
                      <label for="inputName5" class="form-label">Sumber Pengajian</label>
                      <input type="text" class="form-control" id="inputName5" value="<?php echo $awal['sumber'] ?>" name="sumber">
                    </div>
                    <div class="col-md-6">
                      <label for="inputName5" class="form-label">Kamar</label>
                      <input type="text" class="form-control" id="inputName5" value="<?php echo $pasien['kamar'] ?>" name="umur_pasien">
                    </div>
                    <div class="col-md-6">
                      <label for="inputName5" class="form-label">Jam Mulai</label>
                      <input type="text" class="form-control" id="inputName5" value="<?php echo $pasien['start'] ?>" name="umur_pasien">
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
                      <input type="hidden" class="form-control" id="inputName5" placeholder="Masukkan No RM Pasien" value="<?= $p['jenis_kelamin'] ?>" name="jk">
                    </div>


                    <div>
                      <h5 class="card-title">Data Kesehatan</h5>
                    </div>
                    <div class="col-md-12">
                      <label for="inputCity" class="form-label">Diagnosa Medis saat Masuk </label>
                      <input type="text" class="form-control" id="inputCity" placeholder="Diagnosa Masuk" name="diagnosa_masuk" value="<?php echo $awal['diagnosa_masuk'] ?>">
                    </div>
                    <div class="col-md-12">
                      <label for="inputCity" class="form-label">Keluhan Utama </label>
                      <input type="text" class="form-control" id="inputCity" placeholder="Tuliskan Keluhan Utama Pasien" name="keluhan_utama" value="<?php echo $awal['keluhan_utama'] ?>">
                    </div>
                    <div class="col-md-6">
                      <label for="inputCity" class="form-label">Riwayat Penyakit Sekarang</label>
                      <input type="text" class="form-control" id="inputCity" placeholder="Tuliskan Riwayat Penyakit Pasien" name="riwayat_penyakit" value="<?php echo $awal['riwayat_penyakit'] ?>">
                    </div>
                    <div class="col-md-6">
                      <label for="inputCity" class="form-label">Pernah Dirawat</label>
                      <input type="text" class="form-control" id="inputCity" name="riwayat_rawat" value="<?php echo $awal['riwayat_rawat'] ?>">
                    </div>
                    <div class="col-md-6">
                      <label for="inputCity" class="form-label">Pernah Operasi</label>
                      <input type="text" class="form-control" id="inputCity" name="riwayat_operasi" value="<?php echo $awal['riwayat_operasi'] ?>">
                    </div>
                    <div class="col-md-6">
                      <label for="inputState" class="form-label">Riwayat Penyakit Keluarga</label>
                      <input type="text" class="form-control" id="inputState" name="riwayat_keluarga" value="<?php echo $awal['riwayat_keluarga'] ?>">
                    </div>
                    <div class="col-md-6">
                      <label for="inputState" class="form-label">Riwayat Alergi</label>
                      <input type="text" class="form-control" id="inputState" placeholder="Tuliskan Riwayat Alergi Pasien" name="riwayat_alergi" value="<?php echo $awal['riwayat_alergi'] ?>">
                    </div>
                    <!-- <div class="col-md-12">
                      <label for="inputState" class="form-label">Riwayat Pengobatan</label>
                      <textarea name="riwayat_pengobatan" class="form-control" placeholder="Tuliskan Riwayat Pengobatan Pasien"><?= $awal['riwayat_pengobatan'] ?></textarea> -->
                    <!-- <input type="text" class="form-control" id="inputState" placeholder="Tuliskan Riwayat Alergi Pasien" name="riwayat_alergi" value="<?php echo $awal['riwayat_alergi'] ?>"> -->
                    <!-- </div> -->

                    <!-- <br>
                    <br>
    
                    <div>
                      <h5 class="card-title">Riwayat Vaksinasi</h5>
                    </div>
                    <div class="col-md-6">
                      <label for="inputCity" class="form-label">Nama Vaksin </label>
                      <input type="text" class="form-control" id="inputCity" placeholder="Masukkan Vaksin" name="nama_vaksin" value="<?php echo $awal['nama_vaksin'] ?>">
                    </div> 
                    <div class="col-md-6">
                      <label for="inputCity" class="form-label">Pemberian Ke-</label>
                      <input type="text" class="form-control" id="inputCity" placeholder="Masukkan Email Pasien" name="pemberian_vaksin" value="<?php echo $awal['pemberian_vaksin'] ?>">
                    </div>
                    <div class="col-md-12">
                      <label for="inputCity" class="form-label">Tanggal Pemberian</label>
                      <input type="date" class="form-control" id="inputCity" placeholder="Masukkan Email Pasien" name="tgl_vaksin" value="<?= date("Y-m-d") ?>">
                    </div> -->

                    <div>
                      <h5 class="card-title">Pemeriksaan Fisik</h5>
                    </div>

                    <div class="col-md-6">
                      <label for="inputCity" class="form-label">Keadaan Umum</label>
                      <div class="input-group mb-6">
                        <select name="keadaan_umum" id="" class="form-control">
                          <option hidden>Pilih Keadaan Umum</option>
                          <option value="Tidak Tampak Sakit">Tidak Tampak Sakit</option>
                          <option value="Sakit Ringan">Sakit Ringan</option>
                          <option value="Sakit Sedang">Sakit Sedang</option>
                          <option value="Sakit Berat">Sakit Berat</option>
                        </select>
                        <!-- <input type="text" class="form-control"  name="keadaan_umum" aria-describedby="basic-addon2" value="<?php echo $awal['keadaan_umum'] ?>"> -->
                      </div>
                    </div>
                    <div class="col-md-6">
                      <label for="inputCity" class="form-label">Kesadaran</label>
                      <div class="input-group mb-6">
                        <select name="kesadaran" class="form-control" id="">
                          <option hidden>Pilih Kesadaran</option>
                          <option value="Compos Mentis">Compos Mentis</option>
                          <option value="Apatis">Apatis</option>
                          <option value="Somnolen">Somnolen</option>
                          <option value="Sopor">Sopor</option>
                          <option value="Sopor Coma">Sopor Coma</option>
                          <option value="Coma">Coma</option>
                        </select>
                        <!-- <input type="text" class="form-control"  name="kesadaran" aria-describedby="basic-addon2" value="<?php echo $awal['kesadaran'] ?>"> -->
                      </div>
                    </div>
                    <div class="col-md-6">
                      <label for="inputCity" class="form-label">GCS : E</label>
                      <div class="input-group mb-6">
                        <input type="text" class="form-control" name="gcs_e" aria-describedby="basic-addon2" value="<?php echo $awal['gcs_e'] ?>">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <label for="inputCity" class="form-label">GCS : M</label>
                      <div class="input-group mb-6">
                        <input type="text" class="form-control" name="gcs_m" aria-describedby="basic-addon2" value="<?php echo $awal['gcs_m'] ?>">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <label for="inputCity" class="form-label">GCS : V</label>
                      <div class="input-group mb-6">
                        <input type="text" class="form-control" name="gcs_v" aria-describedby="basic-addon2" value="<?php echo $awal['gcs_v'] ?>">
                      </div>
                    </div>

                    <br>
                    <br>

                    <!-- <div>
                      <h5 class="card-title">Tanda-Tanda Vital</h5>
                    </div> -->

                    <div class="col-md-6">
                      <label for="inputCity" class="form-label">Suhu Tubuh</label>
                      <div class="input-group mb-6">
                        <input type="text" class="form-control" placeholder="Suhu Tubuh" name="suhu" aria-describedby="basic-addon2" value="<?php echo $awal['suhu'] ?>">
                        <span class="input-group-text" id="basic-addon2">celcius</span>
                      </div>
                    </div>


                    <div class="col-md-6">

                      <label for="inputCity" class="form-label">Sistole</label>
                      <div class="input-group mb-6">
                        <input type="text" class="form-control" placeholder="Tekanan Darah" name="sistole" aria-describedby="basic-addon2" value="<?php echo $awal['sistole'] ?>">
                        <span class="input-group-text" id="basic-addon2">mmHg</span>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <label for="inputCity" class="form-label">Distole</label>
                      <div class="input-group mb-6">
                        <input type="text" class="form-control" placeholder="Tekanan Darah" name="distole" aria-describedby="basic-addon2" value="<?php echo $awal['distole'] ?>">
                        <span class="input-group-text" id="basic-addon2">mmHg</span>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <label for="inputCity" class="form-label">Nadi</label>
                      <div class="input-group mb-6">
                        <input type="text" class="form-control" placeholder="Denyut Nadi" name="nadi" aria-describedby="basic-addon2" value="<?php echo $awal['nadi'] ?>">
                        <span class="input-group-text" id="basic-addon2">kali/menit</span>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <label for="inputCity" class="form-label">Frekuensi Pernafasan</label>
                      <div class="input-group mb-6">
                        <input type="text" class="form-control" placeholder="Frekuensi Pernafasan" name="frek_nafas" aria-describedby="basic-addon2" value="<?php echo $awal['frek_nafas'] ?>">
                        <span class="input-group-text" id="basic-addon2">kali/menit</span>
                      </div>
                    </div>

                    <br>
                    <br>

                    <!-- <div>
                      <h5 class="card-title">Pemeriksaan Fisik</h5>
                    </div> -->
                    <!-- <div class="col-md-12">
                      <label for="inputName5" class="form-label">Gambar Anatomi Tubuh</label>
                      <input type="file" class="form-control"  name="anatomi" id="inputName5" placeholder="Lidah">
                    </div>
                    <div class="col-md-12">
                      <label for="inputName5" class="form-label">Tingkat Kesadaran</label>
                      <select name="kesadaran" class="form-control">
                        <option value="0">Sadar Baik/Alert</option>
                        <option value="1">Berespon dengan kata-kata/Voice</option>
                        <option value="2">Hanya berespons jika dirangsang nyeri/pain</option>
                        <option value="3">Pasien tidak sadar/unresponsive</option>
                      </select>
                    </div> -->

                    <!-- <div style="display: none;">
                      <div class="col-md-6">
                      <label for="inputCity" class="form-label">Lingkar Kepala</label>
                       <div class="input-group mb-6">
                            <input value="-" type="text" class="form-control" placeholder="Lingkar Kepala" name="kepala" value="<?= $awal['kepala'] ?>" aria-describedby="basic-addon2">
                            <span class="input-group-text" id="basic-addon2">cm</span>
                      </div>
                      </div>
      
                      <div class="col-md-6">
                      <label for="inputCity" class="form-label">Lingkar Perut</label>
                       <div class="input-group mb-6">
                            <input value="-" type="text" class="form-control" placeholder="Lingkar Perut" name="perut" value="<?= $awal['perut'] ?>" aria-describedby="basic-addon2">
                            <span class="input-group-text" id="basic-addon2">cm</span>
                      </div>
                      </div>
      
                      <div class="col-md-6">
                        <label for="inputName5" class="form-label">Mata</label>
                        <input value="-" type="text" class="form-control"  name="mata" value="<?= $awal['mata'] ?>" id="inputName5" placeholder="Mata">
                      </div>
      
                      <div class="col-md-6">
                        <label for="inputName5" class="form-label">Telinga</label>
                        <input value="-" type="text" class="form-control"  name="telinga" value="<?= $awal['telinga'] ?>" id="inputName5" placeholder="Telinga">
                      </div>
      
                      <div class="col-md-6">
                        <label for="inputName5" class="form-label">Hidung</label>
                        <input value="-" type="text" class="form-control"  name="hidung" value="<?= $awal['hidung'] ?>" id="inputName5" placeholder="Hidung">
                      </div>
      
                       <div class="col-md-6">
                        <label for="inputName5" class="form-label">Rambut</label>
                        <input value="-" type="text" class="form-control"  name="rambut" value="<?= $awal['rambut'] ?>" id="inputName5" placeholder="Rambut">
                      </div>
      
                       <div class="col-md-6">
                        <label for="inputName5" class="form-label">Bibir</label>
                        <input value="-" type="text" class="form-control"  name="bibir" value="<?= $awal['bibir'] ?>" id="inputName5" placeholder="Bibir">
                      </div>
                       <div class="col-md-6">
                        <label for="inputName5" class="form-label">Gigi Geligi</label>
                        <input value="-" type="text" class="form-control"  name="gigi" value="<?= $awal['gigi'] ?>" id="inputName5" placeholder="Gigi Geligi">
                      </div>
                    </div>
    
                     <div class="col-md-6">
                      <label for="inputName5" class="form-label">Lidah</label>
                      <input type="text" class="form-control"  name="lidah" value="<?= $awal['lidah'] ?>" id="inputName5" placeholder="Lidah">
                    </div>
    
                     <div class="col-md-6">
                      <label for="inputName5" class="form-label">Langit-langit</label>
                      <input type="text" class="form-control"  name="langit_langit" value="<?= $awal['langit_langit'] ?>" id="inputName5" placeholder="Langit-langit">
                    </div>
    
                     <div class="col-md-6">
                      <label for="inputName5" class="form-label">Leher</label>
                      <input type="text" class="form-control"  name="leher" value="<?= $awal['leher'] ?>" id="inputName5" placeholder="Leher">
                    </div>
    
                     <div class="col-md-6">
                      <label for="inputName5" class="form-label">Tenggorokan</label>
                      <input type="text" class="form-control"  name="tenggorokan" value="<?= $awal['tenggorokan'] ?>" id="inputName5" placeholder="Tenggorokan">
                    </div>
    
                     <div class="col-md-6">
                      <label for="inputName5" class="form-label">Tonsil</label>
                      <input type="text" class="form-control"  name="tonsil" value="<?= $awal['tonsil'] ?>" id="inputName5" placeholder="Tonsil">
                    </div>
    
                     <div class="col-md-6">
                      <label for="inputName5" class="form-label">Dada</label>
                      <input type="text" class="form-control"  name="dada" value="<?= $awal['dada'] ?>" id="inputName5" placeholder="Dada">
                    </div>
    
                     <div class="col-md-6">
                      <label for="inputName5" class="form-label">Payudara</label>
                      <input type="text" class="form-control"  name="payudara" value="<?= $awal['payudara'] ?>" id="inputName5" placeholder="Payudara">
                    </div>
    
                     <div class="col-md-6">
                      <label for="inputName5" class="form-label">Punggung</label>
                      <input type="text" class="form-control"  name="punggung" value="<?= $awal['punggung'] ?>" id="inputName5" placeholder="Punggung">
                    </div>
    
                     <div class="col-md-6">
                      <label for="inputName5" class="form-label">Genital</label>
                      <input type="text" class="form-control"  name="genital" value="<?= $awal['genital'] ?>" id="inputName5" placeholder="Genital">
                    </div>
    
                     <div class="col-md-6">
                      <label for="inputName5" class="form-label">Anus</label>
                      <input type="text" class="form-control"  name="anus" value="<?= $awal['anus'] ?>" id="inputName5" placeholder="Anus">
                    </div>
    
                     <div class="col-md-6">
                      <label for="inputName5" class="form-label">Lengan Atas</label>
                      <input type="text" class="form-control"  name="lengan_atas" value="<?= $awal['lengan_atas'] ?>" id="inputName5" placeholder="Lengan Atas">
                    </div>
    
                     <div class="col-md-6">
                      <label for="inputName5" class="form-label">Lengan Bawah</label>
                      <input type="text" class="form-control"  name="lengan_bawah" value="<?= $awal['lengan_bawah'] ?>" id="inputName5" placeholder="Lengan Bawah">
                    </div>
    
                     <div class="col-md-6">
                      <label for="inputName5" class="form-label">Jari Tangan</label>
                      <input type="text" class="form-control"  name="jari_tangan" value="<?= $awal['jari_tangan'] ?>" id="inputName5" placeholder="Jari Tangan">
                    </div>
    
                     <div class="col-md-6">
                      <label for="inputName5" class="form-label">Kuku Tangan</label>
                      <input type="text" class="form-control"  name="kuku_tangan" value="<?= $awal['kuku_tangan'] ?>" id="inputName5" placeholder="Kuku Tangan">
                    </div>
    
                     <div class="col-md-6">
                      <label for="inputName5" class="form-label">Persendian Tangan</label>
                      <input type="text" class="form-control"  name="persendian_tangan" value="<?= $awal['persendian_tangan'] ?>" id="inputName5" placeholder="Persendian Tangan">
                    </div>
    
                    <div class="col-md-6">
                      <label for="inputName5" class="form-label">Tungkai Atas</label>
                      <input type="text" class="form-control"  name="tungkai_atas" value="<?= $awal['tungkai_atas'] ?>" id="inputName5" placeholder="Tungkai Atas">
                    </div>
    
                    <div class="col-md-6">
                      <label for="inputName5" class="form-label">Tungkai Bawah</label>
                      <input type="text" class="form-control"  name="tungkai_bawah" value="<?= $awal['tungkai_bawah'] ?>" id="inputName5" placeholder="Tungkai Bawah">
                    </div>
    
                    <div class="col-md-6">
                      <label for="inputName5" class="form-label">Jari Kaki</label>
                      <input type="text" class="form-control"  name="jari_kaki" value="<?= $awal['jari_kaki'] ?>" id="inputName5" placeholder="Jari Kaki">
                    </div>
    
                    <div class="col-md-6">
                      <label for="inputName5" class="form-label">Kuku Kaki</label>
                      <input type="text" class="form-control"  name="kuku_kaki" value="<?= $awal['kuku_kaki'] ?>" id="inputName5" placeholder="Kuku Kaki">
                    </div>
    
                    <div class="col-md-6">
                      <label for="inputName5" class="form-label">Persendian Kaki</label>
                      <input type="text" class="form-control"  name="persendian_kaki" value="<?= $awal['persendian_kaki'] ?>" id="inputName5" placeholder="Persendian Kaki">
                    </div> -->

                    <div class="col-md-6">
                      <label for="inputCity" class="form-label mb-0">Tinggi Badan</label><br>
                      <span style="font-size: 12px;" class="mt-0">Gunakan tanda . sebagai pengganti ,</span>
                      <div class="input-group mb-6">
                        <input type="text" class="form-control" oninput="hitungIMT()" placeholder="Tinggi Badan" id="tb" name="tb" value="<?= $awal['tb'] ?>" aria-describedby="basic-addon2" value="<?php echo $awal['tb'] ?>">
                        <span class="input-group-text" id="basic-addon2">m</span>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <label for="inputCity" class="form-label mb-0">Berat Badan</label><br>
                      <span style="font-size: 12px;" class="mt-0">Gunakan tanda . sebagai pengganti ,</span>
                      <div class="input-group mb-6">
                        <input type="text" class="form-control" oninput="hitungIMT()" placeholder="Berat Badan" id="bb" name="bb" value="<?= $awal['bb'] ?>" aria-describedby="basic-addon2" value="<?php echo $awal['bb'] ?>">
                        <span class="input-group-text" id="basic-addon2">kg</span>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <label for="inputCity" class="form-label">IMT</label>
                      <div class="input-group mb-6">
                        <input type="text" class="form-control" placeholder="IMT" id="imt" name="imt" value="<?= $awal['imt'] ?>" aria-describedby="basic-addon2" value="<?php echo $awal['imt'] ?>">
                        <!-- <span class="input-group-text" id="basic-addon2">celcius</span> -->
                      </div>
                    </div>
                    <div class="col-md-6">
                      <label for="inputCity" class="form-label">Sistem Susunan Saraf Pusat </label>
                      <div class="input-group mb-6">
                        <input type="text" class="form-control" placeholder="Susunan Saraf" name="susunan_saraf" value="<?= $awal['susunan_saraf'] ?>" aria-describedby="basic-addon2"">
                      </div>
                    </div>
                    <div class=" col-md-6">
                        <label for="inputCity" class="form-label">Sistem Pengelihatan </label>
                        <div class="input-group mb-6">
                          <input type="text" class="form-control" placeholder="Pengelihatan" name="sistem_pengelihatan" value="<?= $awal['sistem_pengelihatan'] ?>" aria-describedby="basic-addon2" ">
                      </div>
                    </div>
                    <div class=" col-md-6">
                          <label for="inputCity" class="form-label">Sistem Pendengaran </label>
                          <div class="input-group mb-6">
                            <input type="text" class="form-control" placeholder="Pendengaran" name="sistem_pendengaran" value="<?= $awal['sistem_pendengaran'] ?>" aria-describedby="basic-addon2" value="<?php echo $awal['sistem_pendengaran'] ?>">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <label for="inputCity" class="form-label">Sistem Pernafasan </label>
                          <div class="input-group mb-6">
                            <input type="text" class="form-control" placeholder="pernafasan" name="sistem_pernafasan" value="<?= $awal['sistem_pernafasan'] ?>" aria-describedby="basic-addon2" value="<?php echo $awal['sistem_pernafasan'] ?>">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <label for="inputCity" class="form-label">Sistem Pencernaan </label>
                          <div class="input-group mb-6">
                            <input type="text" class="form-control" placeholder="pencernaan" name="sistem_pencernaan" value="<?= $awal['sistem_pencernaan'] ?>" aria-describedby="basic-addon2" value="<?php echo $awal['sistem_pencernaan'] ?>">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <label for="inputCity" class="form-label">Sistem Kardiovaskular/Jantung </label>
                          <div class="input-group mb-6">
                            <input type="text" class="form-control" placeholder="jantung" name="sistem_jantung" value="<?= $awal['sistem_jantung'] ?>" aria-describedby="basic-addon2" value="<?php echo $awal['sistem_jantung'] ?>">
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
                        <div class="col-md-12">
                          <label for="inputCity" class="form-label">Aktivitas Sehari-hari</label>
                          <div class="input-group mb-6">
                            <select name="aktivitas" id="" class="form-control">
                              <option value="<?= $awal['aktivitas'] ?>"><?= $awal['aktivitas'] ?></option>
                              <option value="Mandiri">Mandiri</option>
                              <option value="Bantuan Sebagian">Bantuan Sebagian</option>
                              <option value="Ketergantungan Total">Ketergantungan Total</option>
                            </select>
                            <!-- <input type="text" class="form-control" placeholder="aktivitas" name="aktivitas" value="<?= $awal['aktivitas'] ?>" aria-describedby="basic-addon2" value="<?php echo $awal['aktivitas'] ?>"> -->
                          </div>
                        </div>
                        <div class="col-md-12">
                          <label for="inputCity" class="form-label">Berjalan</label>
                          <div class="input-group mb-6">
                            <select name="berjalan" id="" class="form-control">
                              <option value="<?= $awal['berjalan'] ?>"><?= $awal['berjalan'] ?></option>
                              <option value="Tidak Ada Kesulitan">Tidak Ada Kesulitan</option>
                              <option value="Perlu Bantuan">Perlu Bantuan</option>
                              <option value="Sering Jatuh">Sering Jatuh</option>
                            </select>
                            <!-- <input type="text" class="form-control" placeholder="berjalan" name="berjalan" value="<?= $awal['berjalan'] ?>" aria-describedby="basic-addon2" value="<?php echo $awal['berjalan'] ?>"> -->
                          </div>
                        </div>
                        <div class="col-md-12">
                          <label for="inputCity" class="form-label">Kemampuan Koordinasi</label>
                          <div class="input-group mb-6">
                            <input type="text" class="form-control" placeholder="koordinasi" name="koordinasi" value="<?= $awal['koordinasi'] ?>" aria-describedby="basic-addon2" value="<?php echo $awal['koordinasi'] ?>">
                          </div>
                        </div>
                        <div class="col-md-12">
                          <label for="inputCity" class="form-label">Bicara</label>
                          <div class="input-group mb-6">
                            <input type="text" class="form-control" placeholder="bicara" name="bicara" value="<?= $awal['bicara'] ?>" aria-describedby="basic-addon2" value="<?php echo $awal['bicara'] ?>">
                          </div>
                        </div>

                        <br>
                        <br>

                        <div>
                          <h5 class="card-title">Pemeriksaan Psikologis, Sosial Ekonomi, Spiritual</h5>
                        </div>

                        <div class="col-md-12">
                          <label for="inputState" class="form-label" style="font-weight:bold">Status Psikologis</label>
                          <select id="inputState" name="psiko" class="form-select">
                            <option value="<?php echo $awal['psiko'] ?>"><?php echo $awal['psiko'] ?></option>
                            <option value="Menerima">Menerima</option>
                            <option value="Cemas">Cemas</option>
                            <option value="Rendah Diri">Rendah Diri</option>
                            <option value="Sedih">Sedih</option>
                            <option value="Tidak Mampu">Tidak Mampu</option>
                            <option value="Menilai Negatif Tentang Diri Sendiri">Menilai Negatif Tentang Diri Sendiri</option>
                            <option value="Putus Asa">Putus Asa</option>
                            <option value="Tidak Berdaya">Tidak Berdaya</option>
                            <option value="Tegang">Tegang</option>
                            <option value="Sulit Tidur">Sulit Tidur</option>
                            <option value="Takut">Takut</option>
                          </select>
                        </div>
                        <div class="col-md-12">
                          <label for="inputState" class="form-label" style="font-weight:bold">Sosial</label>
                          <select id="inputState" name="sosial" class="form-select">
                            <option value="<?php echo $awal['sosial'] ?>"><?php echo $awal['sosial'] ?></option>
                            <option value="Autism">Autism</option>
                            <option value="Aktif Sosial">Aktif Sosial</option>
                            <option value="Ingin Sendirian">Ingin Sendirian</option>
                            <option value="Merasa Berbeda Dengan Orang Lain">Merasa Berbeda Dengan Orang Lain</option>
                            <option value="Tidak Aman di Tempat Umum">Tidak Aman di Tempat Umum</option>
                          </select>
                        </div>
                        <!-- <div class="col-md-12">
                      <label for="inputName5" class="form-label">Lain-Lain Psiko</label>
                      <input type="text" class="form-control"  name="psiko" id="inputName5" placeholder="Masukkan status psikologis">
                    </div> -->

                        <div class="col-md-12">
                          <!-- <label for="inputName5" class="form-label" style="font-weight:bold">Sosial Ekonomi</label><br>
                      a. Status Pernikahan :<input type="text" class="form-control"  name="status_nikah" id="inputName5" placeholder="" value="<?php echo $awal['status_nikah'] ?>" style="margin-bottom:8px">
                      b. Pekerjaan: <input type="text" class="form-control"  name="pekerjaan" id="inputName5" placeholder="" value="<?php echo $awal['pekerjaan'] ?>" style="margin-bottom:8px"> -->
                          Tempat Tinggal:
                          <select name="status_tinggal" class="form-control" id="" style="margin-bottom:8px">
                            <option value="<?php echo $awal['status_tinggal'] ?>" hidden><?php echo $awal['status_tinggal'] ?></option>
                            <option value="Sendiri">Sendiri</option>
                            <option value="Keluarga">Keluarga</option>
                            <option value="Lainnya">Lainnya</option>
                          </select>
                          <!-- d. Hubungan Pasien Dengan Keluarga: 
                     <select name="hub_keluarga" class="form-control" id="" style="margin-bottom:8px">
                     <option value="<?php echo $awal['hub_keluarga'] ?>" hidden><?php echo $awal['hub_keluarga'] ?></option>
                      <option value="Baik">Baik</option>
                      <option value="Tidak Baik">Tidak Baik</option>
                     </select> -->
                          Pengobatan Alternatif:
                          <select name="pengobatan" class="form-control" id="" style="margin-bottom:8px">
                            <option value="<?php echo $awal['pengobatan'] ?>" hidden><?php echo $awal['pengobatan'] ?></option>
                            <option value="Tidak">Tidak</option>
                            <option value="Iya">Iya</option>
                          </select>
                          Pantangan:
                          <select name="pantangan" class="form-control" id="" style="margin-bottom:8px">
                            <option value="<?php echo $awal['pantangan'] ?>" hidden><?php echo $awal['pantangan'] ?></option>
                            <option value="Tidak">Tidak</option>
                            <option value="Iya">Iya</option>
                          </select>
                        </div>

                        <div class="col-md-12">
                          <label for="inputName5" class="form-label" style="font-weight:bold">Spiritual (Kegiatan Ibadah)</label>
                          <select name="spiritual" class="form-control" id="" style="margin-bottom:8px">
                            <option value="<?php echo $awal['spiritual'] ?>" hidden><?php echo $awal['spiritual'] ?></option>
                            <option value="Mandiri">Mandiri</option>
                            <option value="Bantuan">Bantuan</option>
                          </select>
                        </div>

                        <!-- <div class="col-md-12">
                      <label for="inputName5" class="form-label" style="font-weight:bold">Status Fungsional</label><br>
                      a. Cacat Tubuh
                     <select name="fungsional_cacat" class="form-control" id="" style="margin-bottom:8px">
                     <option value="<?php echo $awal['fungsional_cacat'] ?>" hidden><?php echo $awal['fungsional_cacat'] ?></option>
                      <option value="Tidak">Tidak</option>
                      <option value="Ada">Ada</option>
                     </select>
                     b. Penggunaan Alat Bantu
                     <select name="fungsional_alat" class="form-control" id="" style="margin-bottom:8px">
                     <option value="<?php echo $awal['fungsional_alat'] ?>" hidden><?php echo $awal['fungsional_alat'] ?></option>
                      <option value="Tidak">Tidak</option>
                      <option value="Tongkat">Tongkat</option>
                      <option value="Kursi Roda">Kursi Roda</option>
                      <option value="Lainnya">Lainnya</option>
                     </select>
                    </div>
    
                    <div class="col-md-12">
                      <label for="inputName5" class="form-label" style="font-weight:bold">Skrining Status Gizi</label>
                      <p style="color:blue">Ya = 1 , Tidak = 0</p>
                      <div class="row">
                        <div class="col-md-4">
                            <p>1. Apakah pasien terlihat kurus ?</p>
                            <input type="number" oninput="hitungGizi()" name="no1" id="no1" class="form-control" value="<?php echo $awal['no1'] ?>">
                          </div>
                        <div class="col-md-4">
                          <p>2. Apakah pakaian anda terasa longgar ?</p>
                          <input type="number" oninput="hitungGizi()" name="no2" id="no2" class="form-control" value="<?php echo $awal['no2'] ?>">
                        </div>
                        <div class="col-md-4">
                          <p>3. Apakah akhir-akhir ini anda kehilangan berat badan yang tidak sengaja ?</p>
                          <input type="number" oninput="hitungGizi()" name="no3" id="no3" class="form-control" value="<?php echo $awal['no3'] ?>">
                        </div>
                        <div class="col-md-4">
                          <p>4. Apakah anda mengalami penurunan berat badan selama 1 minggu terakhir ?</p>
                          <input type="number" oninput="hitungGizi()" name="no4" id="no4" class="form-control" value="<?php echo $awal['no4'] ?>">
                        </div>
                        <div class="col-md-4">
                          <p>5. Apakah anda menderita suatu penyakit yang menyebabkan adanya perubahan jumlah jenis makanan yang anda makan ?</p>
                          <input type="number" oninput="hitungGizi()" name="no5" id="no5" class="form-control" value="<?php echo $awal['no5'] ?>">
                        </div>
                        <div class="col-md-4">
                          <p>6. Apakah anda merasa lemah, loyo dan tidak bertenaga ?</p>
                          <input type="number" oninput="hitungGizi()" name="no6" id="no6" class="form-control" value="<?php echo $awal['no6'] ?>">
                        </div>
                      </div>
                      <h5 class="mb-0"><b>Jumlah : <span id="totalGizi"></span> (<span id="interpretasiGizi"></span>)</b></h5>
                      
                    </div>
                    
                    <div class="col-md-12">
                      <label for="sel" class="form-label" style="font-weight:bold">Diagnosis Keperawatan</label>
                      <select name="diagnois_prwt" id="sel" class="form-control">
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
                        <option value="lain">Lain lain</option>
                      </select>
                      <div id="ft"  style="display: none;">
                        <label for="sel" class="form-label" style="font-weight:bold">Diagnosis Keperawatan Lain</label>
                        <input type="text" id="lain" style="display: none;" class="form-control">
                      </div>
                     </div>
     -->
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
                          <textarea name="rencana_asuh" id="" style="width:100%; height:100%" value="<?php echo $awal['rencana_asuh'] ?>"><?php echo $awal['rencana_asuh'] ?></textarea>
                        </div>



                        <div class="text-center" style="margin-top: 50px; margin-bottom: 80px;">
                          <?php
                          $tes = $koneksi->query("SELECT * FROM kajian_awal_inap WHERE norm='$awal[norm]';")->fetch_assoc();

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
                <div>
                  <!-- <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#tagTeman">@ Tag</button> -->
                  <br>
                  <?php
                  $getTag = $koneksi->query("SELECT * FROM kajian_awal_inap_tag WHERE idrawat = '$_GET[id]'");
                  foreach ($getTag as $tag) {
                  ?>
                    <span class="badge bg-success">@<?= $tag['petugas'] ?></span>
                  <?php } ?>
                </div>
                <div class="table-responsive">
                  <table class="table table-sm table-striped table-hover">
                    <thead>
                      <tr>
                        <th>Nama</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $getKajiaAwal = $koneksi->query("SELECT * FROM kajian_awal_inap WHERE norm = '$_GET[norm]'");
                      foreach ($getKajiaAwal as $item) {
                      ?>
                        <tr>
                          <td><?= $item['nama_pasien'] ?></td>
                          <td><?= $item['tgl_rm'] ?></td>
                          <td><a href="index.php?halaman=resumeinap&id=<?= $_GET['id'] ?>&norm=<?= $_GET['norm'] ?>&ubah&kj=<?= $item['id_rm'] ?>" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i></a></td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
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
      var imtt = berat_b / (tinggi_b * tinggi_b);
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

  <!-- Modal Tag Teman -->
  <div class="modal fade" id="tagTeman" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="post">

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" name="tag_teman" class="btn btn-primary">Simpan Tag</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>


<?php
if (isset($_POST['save'])) {
  // $anatomi = $_FILES["anatomi"]["name"];
  // $lokasi_sementara = $_FILES["anatomi"]["tmp_name"];
  // $lokasi_tujuan = "../rawatinap/anatomi/" . $anatomi;

  // Pindahkan file ke lokasi tujuan
  // move_uploaded_file($lokasi_sementara, $lokasi_tujuan);

  $nama_pasien = htmlspecialchars($_POST["nama_pasien"]);
  $norm = htmlspecialchars($_POST["norm"]);
  $sumber = htmlspecialchars($_POST["sumber"]);
  $diagnosa_masuk = htmlspecialchars($_POST["diagnosa_masuk"]);
  $keadaan_umum = htmlspecialchars($_POST["keadaan_umum"]);
  $gcs_e = htmlspecialchars($_POST["gcs_e"]);
  $gcs_m = htmlspecialchars($_POST["gcs_m"]);
  $gcs_v = htmlspecialchars($_POST["gcs_v"]);
  $sistem_pengelihatan = htmlspecialchars($_POST["sistem_pengelihatan"]);
  $sistem_pendengaran = htmlspecialchars($_POST["sistem_pendengaran"]);
  $sistem_pernafasan = htmlspecialchars($_POST["sistem_pernafasan"]);
  $sistem_pencernaan = htmlspecialchars($_POST["sistem_pencernaan"]);
  $sistem_jantung = htmlspecialchars($_POST["sistem_jantung"]);
  $kognitif = htmlspecialchars($_POST["kognitif"]);
  $keluhan_utama = htmlspecialchars($_POST["keluhan_utama"]);
  $riwayat_penyakit = htmlspecialchars($_POST["riwayat_penyakit"]);
  $riwayat_alergi = htmlspecialchars($_POST["riwayat_alergi"]);
  $riwayat_rawat = htmlspecialchars($_POST["riwayat_rawat"]);
  $riwayat_operasi = htmlspecialchars($_POST["riwayat_operasi"]);
  $aktivitas = htmlspecialchars($_POST["aktivitas"]);
  $berjalan = htmlspecialchars($_POST["berjalan"]);
  $koordinasi = htmlspecialchars($_POST["koordinasi"]);
  $bicara = htmlspecialchars($_POST["bicara"]);
  $riwayat_keluarga = htmlspecialchars($_POST["riwayat_keluarga"]);
  $suhu = htmlspecialchars($_POST["suhu"]);
  $sistole = htmlspecialchars($_POST["sistole"]);
  $distole = htmlspecialchars($_POST["distole"]);
  $nadi = htmlspecialchars($_POST["nadi"]);
  $frek_nafas = htmlspecialchars($_POST["frek_nafas"]);
  $tb = htmlspecialchars($_POST["tb"]);
  $bb = htmlspecialchars($_POST["bb"]);
  $imt = htmlspecialchars($_POST["imt"]);
  $psiko = htmlspecialchars($_POST["psiko"]);
  $sosial = htmlspecialchars($_POST["sosial"]);
  $spiritual = htmlspecialchars($_POST["spiritual"]);
  $status_tinggal = htmlspecialchars($_POST["status_tinggal"]);
  // $umur_tinggal=htmlspecialchars($_POST["umur_tinggal"]);
  // $hub_keluarga=htmlspecialchars($_POST["hub_keluarga"]);
  $pantangan = htmlspecialchars($_POST["pantangan"]);
  // $pantangan=htmlspecialchars($_POST["pantangan"]);
  // $fungsional_alat=htmlspecialchars($_POST["fungsional_alat"]);
  // $fungsional_cacat=htmlspecialchars($_POST["fungsional_cacat"]);
  $rencana_asuh = htmlspecialchars($_POST["rencana_asuh"]);
  $username_admin = htmlspecialchars($_POST["username_admin"]);
  $pengobatan = htmlspecialchars($_POST["pengobatan"]);
  $susunan_saraf = htmlspecialchars($_POST["susunan_saraf"]);
  $kesadaran = htmlspecialchars($_POST["kesadaran"]);
  $tgl_rm = date('Y-m-d');

  $idadmin = htmlspecialchars($_POST["idadmin"]);
  $status_log = htmlspecialchars($_POST["status_log"]);

  // $foto=$_FILES['foto']['name'];

  // $lokasi=$_FILES['foto']['tmp_name'];

  // move_uploaded_file($lokasi, '../pasien/foto/'.$foto);
  $koneksi->query("INSERT INTO `kajian_awal_inap`(`nama_pasien`, `norm`, `id_pasien`, `umur_pasien`, `jk`, `tb`, `bb`, `imt`, `psiko`, `sosial`, `spiritual`, `tgl_rm`, `status_tinggal`, `pantangan`, `susunan_saraf`, `sumber`, `diagnosa_masuk`, `keluhan_utama`, `riwayat_penyakit`, `riwayat_rawat`, `riwayat_operasi`, `riwayat_keluarga`, `riwayat_alergi`, `keadaan_umum`, `kesadaran`, `gcs_e`, `gcs_m`, `gcs_v`, `suhu`, `sistole`, `distole`, `nadi`, `frek_nafas`, `sistem_pengelihatan`, `sistem_pendengaran`, `sistem_pernafasan`, `sistem_pencernaan`, `sistem_jantung`, `kognitif`, `koordinasi`, `bicara`, `aktivitas`, `berjalan`, `pengobatan`, `rencana_asuh`, `jadwal`) VALUES ('$nama_pasien', '$norm', '$id_pasien', '$umur_pasien', '$jk', '$tb', '$bb', '$imt', '$psiko', '$sosial', '$spiritual', '$tgl_rm', '$status_tinggal', '$pantangan', '$susunan_saraf', '$sumber', '$diagnosa_masuk', '$keluhan_utama', '$riwayat_penyakit', '$riwayat_rawat', '$riwayat_operasi', '$riwayat_keluarga', '$riwayat_alergi', '$keadaan_umum', '$kesadaran', '$gcs_e', '$gcs_m', '$gcs_v', '$suhu', '$sistole', '$distole', '$nadi', '$frek_nafas', '$sistem_pengelihatan', '$sistem_pendengaran', '$sistem_pernafasan', '$sistem_pencernaan', '$sistem_jantung', '$kognitif', '$koordinasi', '$bicara', '$aktivitas', '$berjalan', '$pengobatan', '$rencana_asuh', '$pasien[jadwal]')");

  // $koneksi->query("INSERT INTO `kajian_awal_inap`(`nama_pasien`, `norm`, `id_pasien`, `umur_pasien`, `jk`, `tb`, `bb`, `imt`, `psiko`, `sosial`, `spiritual`, `tgl_rm`, `status_tinggal`, `hub_keluarga`, `pantangan`, `fungsional_cacat`, `fungsional_alat`, `susunan_saraf`, `sumber`, `diagnosa_masuk`, `keluhan_utama`, `riwayat_penyakit`, `riwayat_rawat`, `riwayat_operasi`, `riwayat_keluarga`, `riwayat_alergi`, `keadaan_umum`, `kesadaran`, `gcs_e`, `gcs_m`, `gcs_v`, `suhu`, `sistole`, `distole`, `nadi`, `frek_nafas`, `sistem_pengelihatan`, `sistem_pendengaran`, `sistem_pernafasan`, `sistem_pencernaan`, `sistem_jantung`, `kognitif`, `koordinasi`, `bicara`) VALUES ('$nama_pasien', '$norm', '$id_pasien', '$umur_pasien', '$jk', '$tb', '$bb', '$imt', '$psiko', '$sosial', '$spiritual', '$tgl_rm', '$status_tinggal', '$hub_keluarga', '$pantangan', '$fungsional_cacat', '$fungsional_alat', '$susunan_saraf', '$sumber', '$diagnosa_masuk', '$keluhan_utama', '$riwayat_penyakit', '$riwayat_rawat', '$riwayat_operasi', '$riwayat_keluarga', '$riwayat_alergi', '$keadaan_umum', '$kesadaran', '$gcs_e', '$gcs_m', '$gcs_v', '$suhu', '$sistole', '$distole', '$nadi', '$frek_nafas', '$sistem_pengelihatan', '$sistem_pendengaran', '$sistem_pernafasan', '$sistem_pencernaan', '$sistem_jantung', '$kognitif', '$koordinasi', '$bicara')");

  // $koneksi->query("INSERT INTO kajian_awal_inap 

  //   (nama_pasien, keluhan_utama, riwayat_penyakit, pemberian_vaksin,  riwayat_alergi, tgl_vaksin, nama_vaksin, suhu, sistole, distole, nadi, frek_nafas, kepala, perut, mata, telinga, hidung, rambut, bibir, gigi, lidah, langit_langit, leher, tenggorokan, tonsil, dada, payudara, punggung, genital, anus, lengan_atas, lengan_bawah, jari_tangan, kuku_tangan, persendian_tangan, tungkai_atas, tungkai_bawah, jari_kaki, kuku_kaki, persendian_kaki, tb, bb, imt, spiritual, umur_pasien, jk, status_tinggal, hub_keluarga,pengobatan,pantangan,fungsional_cacat, fungsional_alat, no1, no2, no3, no4, no5, no6, norm, psiko, diagnois_prwt, rencana_asuh, anatomi, kesadaran, riwayat_pengobatan, jadwal, susunan_saraf)

  //   VALUES ('$nama_pasien', '$keluhan_utama', '$riwayat_penyakit', '$pemberian_vaksin', '$riwayat_alergi', '$tgl_vaksin', '$nama_vaksin', '$suhu', '$sistole', '$distole', '$nadi',  '$frek_nafas', '$kepala', '$perut', '$mata', '$telinga', '$hidung', '$rambut', '$bibir', '$gigi', '$lidah', '$langit_langit', '$leher', '$tenggorokan', '$tonsil', '$dada', '$payudara', '$punggung', '$genital', '$anus', '$lengan_atas', '$lengan_bawah', '$jari_tangan', '$kuku_tangan', '$persendian_tangan', '$tungkai_atas', '$tungkai_bawah', '$jari_kaki', '$kuku_kaki', '$persendian_kaki', '$tb', '$bb', '$imt', '$spiritual', '$umur_pasien', '$jk', '$_POST[status_tinggal]', '$_POST[hub_keluarga]', '$_POST[pengobatan]', '$_POST[pantangan]', '$_POST[fungsional_cacat]', '$_POST[fungsional_alat]', '$no1', '$no2', '$no3', '$no4', '$no5', '$no6', '$norm','$psiko', '$_POST[diagnois_prwt]', '$_POST[rencana_asuh]', '$anatomi', '$_POST[kesadaran]', '$_POST[riwayat_pengobatan]', '$pasien[jadwal]', '$susunan_saraf')

  //   ");

  // $koneksi->query("INSERT INTO kajian_awal_inap_tag (idrawat, petugas, shift) VALUES ('$_GET[id]', '".$_SESSION['admin']['namalengkap']."', '".$_SESSION['shift']."')");
  $koneksi->query("INSERT INTO log_user 

    (status_log, username_admin, idadmin)

    VALUES ('$status_log', '$username_admin', '$idadmin')

    ");


  if (mysqli_affected_rows($koneksi) > 0) {
    echo "
  <script>
  alert('Data berhasil ditambah');
  document.location.href='index.php?halaman=daftarregistrasiinap';

   </script>

  ";
  } else {

    echo "
  <script>
  alert('GAGAL MENGHAPUS DATA');
  document.location.href='index.php?halaman=daftarregistrasiinap';
  </script>

  ";
  }
}

if (isset($_POST['ubah'])) {
  $nama_pasien = htmlspecialchars($_POST["nama_pasien"]);
  $norm = htmlspecialchars($_POST["norm"]);
  $sumber = htmlspecialchars($_POST["sumber"]);
  $diagnosa_masuk = htmlspecialchars($_POST["diagnosa_masuk"]);
  $keadaan_umum = htmlspecialchars($_POST["keadaan_umum"]);
  $gcs_e = htmlspecialchars($_POST["gcs_e"]);
  $gcs_m = htmlspecialchars($_POST["gcs_m"]);
  $gcs_v = htmlspecialchars($_POST["gcs_v"]);
  $sistem_pengelihatan = htmlspecialchars($_POST["sistem_pengelihatan"]);
  $sistem_pendengaran = htmlspecialchars($_POST["sistem_pendengaran"]);
  $sistem_pernafasan = htmlspecialchars($_POST["sistem_pernafasan"]);
  $sistem_pencernaan = htmlspecialchars($_POST["sistem_pencernaan"]);
  $sistem_jantung = htmlspecialchars($_POST["sistem_jantung"]);
  $kognitif = htmlspecialchars($_POST["kognitif"]);
  $keluhan_utama = htmlspecialchars($_POST["keluhan_utama"]);
  $riwayat_penyakit = htmlspecialchars($_POST["riwayat_penyakit"]);
  $riwayat_alergi = htmlspecialchars($_POST["riwayat_alergi"]);
  $riwayat_rawat = htmlspecialchars($_POST["riwayat_rawat"]);
  $riwayat_operasi = htmlspecialchars($_POST["riwayat_operasi"]);
  $aktivitas = htmlspecialchars($_POST["aktivitas"]);
  $berjalan = htmlspecialchars($_POST["berjalan"]);
  $koordinasi = htmlspecialchars($_POST["koordinasi"]);
  $bicara = htmlspecialchars($_POST["bicara"]);
  $riwayat_keluarga = htmlspecialchars($_POST["riwayat_keluarga"]);
  $suhu = htmlspecialchars($_POST["suhu"]);
  $sistole = htmlspecialchars($_POST["sistole"]);
  $distole = htmlspecialchars($_POST["distole"]);
  $nadi = htmlspecialchars($_POST["nadi"]);
  $frek_nafas = htmlspecialchars($_POST["frek_nafas"]);
  $tb = htmlspecialchars($_POST["tb"]);
  $bb = htmlspecialchars($_POST["bb"]);
  $imt = htmlspecialchars($_POST["imt"]);
  $psiko = htmlspecialchars($_POST["psiko"]);
  $sosial = htmlspecialchars($_POST["sosial"]);
  $spiritual = htmlspecialchars($_POST["spiritual"]);
  $status_tinggal = htmlspecialchars($_POST["status_tinggal"]);
  // $umur_tinggal=htmlspecialchars($_POST["umur_tinggal"]);
  // $hub_keluarga=htmlspecialchars($_POST["hub_keluarga"]);
  $pantangan = htmlspecialchars($_POST["pantangan"]);
  $pantangan = htmlspecialchars($_POST["pantangan"]);
  $fungsional_alat = htmlspecialchars($_POST["fungsional_alat"]);
  $fungsional_cacat = htmlspecialchars($_POST["fungsional_cacat"]);
  $rencana_asuh = htmlspecialchars($_POST["rencana_asuh"]);
  $username_admin = htmlspecialchars($_POST["username_admin"]);
  $pengobatan = htmlspecialchars($_POST["pengobatan"]);
  $susunan_saraf = htmlspecialchars($_POST["susunan_saraf"]);
  $tgl_rm = date('Y-m-d');

  $idadmin = htmlspecialchars($_POST["idadmin"]);

  $status_log = htmlspecialchars($_POST["status_log"]);



  $koneksi->query("UPDATE kajian_awal_inap SET nama_pasien='$nama_pasien', norm='$norm', id_pasien='$id_pasien', umur_pasien='$umur_pasien', jk='$jk', tb='$tb', bb='$bb', imt='$imt', psiko='$psiko', sosial='$sosial', spiritual='$spiritual', tgl_rm='$tgl_rm', status_tinggal='$status_tinggal', hub_keluarga='$hub_keluarga', pantangan='$pantangan', fungsional_cacat='$fungsional_cacat', fungsional_alat='$fungsional_alat', susunan_saraf='$susunan_saraf', sumber='$sumber', diagnosa_masuk='$diagnosa_masuk', keluhan_utama='$keluhan_utama', riwayat_penyakit='$riwayat_penyakit', riwayat_rawat='$riwayat_rawat', riwayat_operasi='$riwayat_operasi', riwayat_keluarga='$riwayat_keluarga', riwayat_alergi='$riwayat_alergi', keadaan_umum='$keadaan_umum', kesadaran='$kesadaran', gcs_e='$gcs_e', gcs_m='$gcs_m', gcs_v='$gcs_v', suhu='$suhu', sistole='$sistole', distole='$distole', nadi='$nadi', frek_nafas='$frek_nafas', sistem_pengelihatan='$sistem_pengelihatan', sistem_pendengaran='$sistem_pendengaran', sistem_pernafasan='$sistem_pernafasan', sistem_pencernaan='$sistem_pencernaan', sistem_jantung='$sistem_jantung', kognitif='$kognitif', koordinasi='$koordinasi', bicara='$bicara', aktivitas='$aktivitas', berjalan='$berjalan', pengobatan='$pengobatan', rencana_asuh='$rencana_asuh', jadwal='$_POST[jadwal]' WHERE norm='$_GET[norm]'");



  $koneksi->query("INSERT INTO log_user 

    (status_log, username_admin, idadmin)

    VALUES ('$status_log', '$username_admin', '$idadmin')

    ");


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