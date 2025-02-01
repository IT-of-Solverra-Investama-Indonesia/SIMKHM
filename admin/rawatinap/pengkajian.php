
<?php 
error_reporting(0);
$username=$_SESSION['admin']['username'];
$ambil=$koneksi->query("SELECT * FROM admin  WHERE username='$username';");
$pasien=$koneksi->query("SELECT * FROM registrasi_rawat  WHERE idrawat='$_GET[id]';");
$pasien=$pasien->fetch_assoc();
$p=$koneksi->query("SELECT * FROM pasien  WHERE no_rm='$_GET[norm]';");
$p=$p->fetch_assoc();
$rm=$koneksi->query("SELECT * FROM rekam_medis  WHERE norm='$_GET[norm]';");
$rm=$rm->fetch_assoc();

$awal=$koneksi->query("SELECT * FROM kajian_awal_inap WHERE norm='$_GET[norm]' ORDER BY id_rm DESC LIMIT 1;");
$awal=$awal->fetch_assoc();

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

            <div class="card" style="max-width: 70%; display: inline-flex; position: absolute;">
            <div class="card-body">
              <h5 class="card-title">Data Pasien</h5>

              <!-- Multi Columns Form -->
              <form class="row g-3" method="post" enctype="multipart/form-data">
                <?php foreach($ambil as $pecah) : ?>
                <input disabled type="hidden" name="idadmin" class="form-control" value="<?php echo $pecah['idadmin']?>" placeholder="Masukkan nama karyawan">
                <input disabled type="hidden" name="username_admin" class="form-control" value="<?php echo $pecah['namalengkap']?>" placeholder="Masukkan nama karyawan">
                <input disabled type="hidden" name="status_log" class="form-control" value="<?php echo $pecah['namalengkap']?> Memperbarui Data Kajian Awal" placeholder="Masukkan nama karyawan">
                <?php endforeach ?>
                <div class="col-md-12">
                  <label for="inputName5" class="form-label">Nama Pasien</label>
                  <input disabled type="text" class="form-control" id="inputName5" placeholder="Masukkan Nama Pasien" value="<?php echo $pasien['nama_pasien']?>" name="nama_pasien">
                </div>
                <div class="col-md-12">
                  <label for="inputName5" class="form-label">No. RM Pasien</label>
                  <input disabled type="text" class="form-control" id="inputName5" placeholder="Masukkan No RM Pasien" value="<?php echo $pasien['no_rm']?>" name="norm">
                </div>
                <div class="col-md-12">
                  <label for="inputName5" class="form-label">Umur Pasien</label>
                  <input disabled type="text" class="form-control" id="inputName5" placeholder="Masukkan Umur Pasien" value="<?php echo $p['umur']?>" name="umur_pasien">
                </div>
                <div class="col-md-12">
                  <label for="inputName5" class="form-label">NIK Pasien</label>
                  <input disabled type="text" class="form-control" id="inputName5" placeholder="Masukkan Umur Pasien" value="<?php echo $p['no_identitas']?>" name="umur_pasien">
                </div>

                 <!-- <div class="col-md-6">
                  <label for="inputState" class="form-label">Tipe Umur</label>
                  <select disabled id="inputState" class="form-select" name="tipe_umur" required>
                    <option value="Tahun">Tahun</option>
                    <option value="Hari">Hari</option>
                    <option value="Bulan">Bulan</option>
                  </select>
                </div> -->

                <div class="col-md-12">
                  <!-- <label for="inputName5" class="form-label">Jenis Kelamin Pasien</label> -->
                  <input disabled type="hidden" class="form-control" id="inputName5" placeholder="Masukkan No RM Pasien" value="<?php echo $pasien['jenis_kelamin']?>" name="jk">
                </div>


                <div>
                  <h5 class="card-title">Data Kesehatan</h5>
                </div>
                <div class="col-md-12">
                  <label for="inputCity" class="form-label">Keluhan Utama </label>
                  <input disabled type="text" class="form-control" id="inputCity" placeholder="Tuliskan Keluhan Utama Pasien" name="keluhan_utama" value="<?php echo $awal['keluhan_utama']?>">
                </div> 
                <div class="col-md-6">
                  <label for="inputCity" class="form-label">Riwayat Penyakit</label>
                  <input disabled type="text" class="form-control" id="inputCity" placeholder="Tuliskan Riwayat Penyakit Pasien" name="riwayat_penyakit" value="<?php echo $awal['riwayat_penyakit']?>">
                </div>
                <div class="col-md-6">
                  <label for="inputState" class="form-label">Riwayat Alergi</label>
                  <input disabled type="text" class="form-control" id="inputState" placeholder="Tuliskan Riwayat Alergi Pasien" name="riwayat_alergi" value="<?php echo $awal['riwayat_alergi']?>">
                </div>
                <div class="col-md-12">
                  <label for="inputState" class="form-label">Riwayat Pengobatan</label>
                  <textarea disabled name="riwayat_pengobatan" class="form-control" placeholder="Tuliskan Riwayat Pengobatan Pasien"><?= $awal['riwayat_pengobatan']?></textarea>
                  <!-- <input disabled type="text" class="form-control" id="inputState" placeholder="Tuliskan Riwayat Alergi Pasien" name="riwayat_alergi" value="<?php echo $awal['riwayat_alergi']?>"> -->
                </div>
               
                <br>
                <br>

                <div>
                  <h5 class="card-title">Riwayat Vaksinasi</h5>
                </div>
                <div class="col-md-6">
                  <label for="inputCity" class="form-label">Nama Vaksin </label>
                  <input disabled type="text" class="form-control" id="inputCity" placeholder="Masukkan Vaksin" name="nama_vaksin" value="<?php echo $awal['nama_vaksin']?>">
                </div> 
                <div class="col-md-6">
                  <label for="inputCity" class="form-label">Pemberian Ke-</label>
                  <input disabled type="text" class="form-control" id="inputCity" placeholder="Masukkan Email Pasien" name="pemberian_vaksin" value="<?php echo $awal['pemberian_vaksin']?>">
                </div>
                <div class="col-md-12">
                  <label for="inputCity" class="form-label">Tanggal Pemberian</label>
                  <input disabled type="date" class="form-control" id="inputCity" placeholder="Masukkan Email Pasien" name="tgl_vaksin" value="<?= date("Y-m-d")?>">
                </div>

                <br>
                <br>

                <div>
                  <h5 class="card-title">Tanda-Tanda Vital</h5>
                </div>

                <div class="col-md-6">
                <label for="inputCity" class="form-label">Suhu Tubuh</label>
                <div class="input-group mb-6">
                      <input disabled type="text" class="form-control" placeholder="Suhu Tubuh" name="suhu_tubuh" aria-describedby="basic-addon2" value="<?php echo $awal['suhu_tubuh']?>">
                      <span class="input-group-text" id="basic-addon2">celcius</span>
                </div>
                </div>

                <div class="col-md-6">
                <label for="inputCity" class="form-label">Saturasi Oksigen</label>
                <div class="input-group mb-6">
                      <input disabled type="text" class="form-control" placeholder="Saturasi Oksigen" name="oksigen" aria-describedby="basic-addon2" value="<?php echo $awal['oksigen']?>">
                      <span class="input-group-text" id="basic-addon2">%</span>
                </div>
                </div>

                <div class="col-md-6">

                <label for="inputCity" class="form-label">Sistole</label>
                 <div class="input-group mb-6">
                      <input disabled type="text" class="form-control" placeholder="Tekanan Darah" name="sistole" aria-describedby="basic-addon2" value="<?php echo $awal['sistole']?>">
                      <span class="input-group-text" id="basic-addon2">mmHg</span>
                </div>
                </div>

                <div class="col-md-6">
                <label for="inputCity" class="form-label">Distole</label>
                 <div class="input-group mb-6">
                      <input disabled type="text" class="form-control" placeholder="Tekanan Darah" name="distole" aria-describedby="basic-addon2" value="<?php echo $awal['distole']?>">
                      <span class="input-group-text" id="basic-addon2">mmHg</span>
                </div>
                </div>
               
                <div class="col-md-12">
                <label for="inputCity" class="form-label">Nadi</label>
                <div class="input-group mb-6">
                      <input disabled type="text" class="form-control" placeholder="Denyut Nadi" name="nadi" aria-describedby="basic-addon2" value="<?php echo $awal['nadi']?>">
                      <span class="input-group-text" id="basic-addon2">kali/menit</span>
                </div>
                </div>

                 <div class="col-md-12">
                <label for="inputCity" class="form-label">Frekuensi Pernafasan</label>
                <div class="input-group mb-6">
                      <input disabled type="text" class="form-control" placeholder="Frekuensi Pernafasan" name="frek_nafas" aria-describedby="basic-addon2" value="<?php echo $awal['frek_nafas']?>">
                      <span class="input-group-text" id="basic-addon2">kali/menit</span>
                </div>
                </div>
                
                 <br>
                <br>

                <div>
                  <h5 class="card-title">Pemeriksaan Fisik</h5>
                </div>
                <div class="col-md-12">
                  <label for="inputName5" class="form-label">Gambar Anatomi Tubuh</label>
                  <input disabled type="file" class="form-control"  name="anatomi" id="inputName5" placeholder="Lidah">
                </div>
                <div class="col-md-12">
                  <label for="inputName5" class="form-label">Tingkat Kesadaran</label>
                  <select disabled name="kesadaran" class="form-control">
                    <option value="0">Sadar Baik/Alert</option>
                    <option value="1">Berespon dengan kata-kata/Voice</option>
                    <option value="2">Hanya berespons jika dirangsang nyeri/pain</option>
                    <option value="3">Pasien tidak sadar/unresponsive</option>
                  </select>
                </div>

                <div style="display: none;">
                  <div class="col-md-6">
                  <label for="inputCity" class="form-label">Lingkar Kepala</label>
                   <div class="input-group mb-6">
                        <input disabled value="-" type="text" class="form-control" placeholder="Lingkar Kepala" name="kepala" value="<?= $awal['kepala']?>" aria-describedby="basic-addon2">
                        <span class="input-group-text" id="basic-addon2">cm</span>
                  </div>
                  </div>
  
                  <div class="col-md-6">
                  <label for="inputCity" class="form-label">Lingkar Perut</label>
                   <div class="input-group mb-6">
                        <input disabled value="-" type="text" class="form-control" placeholder="Lingkar Perut" name="perut" value="<?= $awal['perut']?>" aria-describedby="basic-addon2">
                        <span class="input-group-text" id="basic-addon2">cm</span>
                  </div>
                  </div>
  
                  <div class="col-md-6">
                    <label for="inputName5" class="form-label">Mata</label>
                    <input disabled value="-" type="text" class="form-control"  name="mata" value="<?= $awal['mata']?>" id="inputName5" placeholder="Mata">
                  </div>
  
                  <div class="col-md-6">
                    <label for="inputName5" class="form-label">Telinga</label>
                    <input disabled value="-" type="text" class="form-control"  name="telinga" value="<?= $awal['telinga']?>" id="inputName5" placeholder="Telinga">
                  </div>
  
                  <div class="col-md-6">
                    <label for="inputName5" class="form-label">Hidung</label>
                    <input disabled value="-" type="text" class="form-control"  name="hidung" value="<?= $awal['hidung']?>" id="inputName5" placeholder="Hidung">
                  </div>
  
                   <div class="col-md-6">
                    <label for="inputName5" class="form-label">Rambut</label>
                    <input disabled value="-" type="text" class="form-control"  name="rambut" value="<?= $awal['rambut']?>" id="inputName5" placeholder="Rambut">
                  </div>
  
                   <div class="col-md-6">
                    <label for="inputName5" class="form-label">Bibir</label>
                    <input disabled value="-" type="text" class="form-control"  name="bibir" value="<?= $awal['bibir']?>" id="inputName5" placeholder="Bibir">
                  </div>
                   <div class="col-md-6">
                    <label for="inputName5" class="form-label">Gigi Geligi</label>
                    <input disabled value="-" type="text" class="form-control"  name="gigi" value="<?= $awal['gigi']?>" id="inputName5" placeholder="Gigi Geligi">
                  </div>
                </div>

                 <div class="col-md-6">
                  <label for="inputName5" class="form-label">Lidah</label>
                  <input disabled type="text" class="form-control"  name="lidah" value="<?= $awal['lidah']?>" id="inputName5" placeholder="Lidah">
                </div>

                 <div class="col-md-6">
                  <label for="inputName5" class="form-label">Langit-langit</label>
                  <input disabled type="text" class="form-control"  name="langit_langit" value="<?= $awal['langit_langit']?>" id="inputName5" placeholder="Langit-langit">
                </div>

                 <div class="col-md-6">
                  <label for="inputName5" class="form-label">Leher</label>
                  <input disabled type="text" class="form-control"  name="leher" value="<?= $awal['leher']?>" id="inputName5" placeholder="Leher">
                </div>

                 <div class="col-md-6">
                  <label for="inputName5" class="form-label">Tenggorokan</label>
                  <input disabled type="text" class="form-control"  name="tenggorokan" value="<?= $awal['tenggorokan']?>" id="inputName5" placeholder="Tenggorokan">
                </div>

                 <div class="col-md-6">
                  <label for="inputName5" class="form-label">Tonsil</label>
                  <input disabled type="text" class="form-control"  name="tonsil" value="<?= $awal['tonsil']?>" id="inputName5" placeholder="Tonsil">
                </div>

                 <div class="col-md-6">
                  <label for="inputName5" class="form-label">Dada</label>
                  <input disabled type="text" class="form-control"  name="dada" value="<?= $awal['dada']?>" id="inputName5" placeholder="Dada">
                </div>

                 <div class="col-md-6">
                  <label for="inputName5" class="form-label">Payudara</label>
                  <input disabled type="text" class="form-control"  name="payudara" value="<?= $awal['payudara']?>" id="inputName5" placeholder="Payudara">
                </div>

                 <div class="col-md-6">
                  <label for="inputName5" class="form-label">Punggung</label>
                  <input disabled type="text" class="form-control"  name="punggung" value="<?= $awal['punggung']?>" id="inputName5" placeholder="Punggung">
                </div>

                 <div class="col-md-6">
                  <label for="inputName5" class="form-label">Genital</label>
                  <input disabled type="text" class="form-control"  name="genital" value="<?= $awal['genital']?>" id="inputName5" placeholder="Genital">
                </div>

                 <div class="col-md-6">
                  <label for="inputName5" class="form-label">Anus</label>
                  <input disabled type="text" class="form-control"  name="anus" value="<?= $awal['anus']?>" id="inputName5" placeholder="Anus">
                </div>

                 <div class="col-md-6">
                  <label for="inputName5" class="form-label">Lengan Atas</label>
                  <input disabled type="text" class="form-control"  name="lengan_atas" value="<?= $awal['lengan_atas']?>" id="inputName5" placeholder="Lengan Atas">
                </div>

                 <div class="col-md-6">
                  <label for="inputName5" class="form-label">Lengan Bawah</label>
                  <input disabled type="text" class="form-control"  name="lengan_bawah" value="<?= $awal['lengan_bawah']?>" id="inputName5" placeholder="Lengan Bawah">
                </div>

                 <div class="col-md-6">
                  <label for="inputName5" class="form-label">Jari Tangan</label>
                  <input disabled type="text" class="form-control"  name="jari_tangan" value="<?= $awal['jari_tangan']?>" id="inputName5" placeholder="Jari Tangan">
                </div>

                 <div class="col-md-6">
                  <label for="inputName5" class="form-label">Kuku Tangan</label>
                  <input disabled type="text" class="form-control"  name="kuku_tangan" value="<?= $awal['kuku_tangan']?>" id="inputName5" placeholder="Kuku Tangan">
                </div>

                 <div class="col-md-6">
                  <label for="inputName5" class="form-label">Persendian Tangan</label>
                  <input disabled type="text" class="form-control"  name="persendian_tangan" value="<?= $awal['persendian_tangan']?>" id="inputName5" placeholder="Persendian Tangan">
                </div>

                <div class="col-md-6">
                  <label for="inputName5" class="form-label">Tungkai Atas</label>
                  <input disabled type="text" class="form-control"  name="tungkai_atas" value="<?= $awal['tungkai_atas']?>" id="inputName5" placeholder="Tungkai Atas">
                </div>

                <div class="col-md-6">
                  <label for="inputName5" class="form-label">Tungkai Bawah</label>
                  <input disabled type="text" class="form-control"  name="tungkai_bawah" value="<?= $awal['tungkai_bawah']?>" id="inputName5" placeholder="Tungkai Bawah">
                </div>

                <div class="col-md-6">
                  <label for="inputName5" class="form-label">Jari Kaki</label>
                  <input disabled type="text" class="form-control"  name="jari_kaki" value="<?= $awal['jari_kaki']?>" id="inputName5" placeholder="Jari Kaki">
                </div>

                <div class="col-md-6">
                  <label for="inputName5" class="form-label">Kuku Kaki</label>
                  <input disabled type="text" class="form-control"  name="kuku_kaki" value="<?= $awal['kuku_kaki']?>" id="inputName5" placeholder="Kuku Kaki">
                </div>

                <div class="col-md-6">
                  <label for="inputName5" class="form-label">Persendian Kaki</label>
                  <input disabled type="text" class="form-control"  name="persendian_kaki" value="<?= $awal['persendian_kaki']?>" id="inputName5" placeholder="Persendian Kaki">
                </div>
               
                <div class="col-md-6">
                <label for="inputCity" class="form-label mb-0">Tinggi Badan</label><br>
                <span style="font-size: 12px;" class="mt-0">Gunakan tanda . sebagai pengganti ,</span>
                <div class="input-group mb-6">
                      <input disabled type="text" class="form-control" oninput="hitungIMT()" placeholder="Tinggi Badan" id="tb" name="tb" value="<?= $awal['tb']?>" aria-describedby="basic-addon2" value="<?php echo $awal['tb']?>">
                      <span class="input-group-text" id="basic-addon2">m</span>
                </div>
                </div>

                <div class="col-md-6">
                <label for="inputCity" class="form-label mb-0">Berat Badan</label><br>
                <span style="font-size: 12px;" class="mt-0">Gunakan tanda . sebagai pengganti ,</span>
                <div class="input-group mb-6">
                      <input disabled type="text" class="form-control" oninput="hitungIMT()" placeholder="Berat Badan" id="bb" name="bb" value="<?= $awal['bb']?>" aria-describedby="basic-addon2" value="<?php echo $awal['bb']?>">
                      <span class="input-group-text" id="basic-addon2">kg</span>
                </div>
                </div>

                <div class="col-md-12">
                <label for="inputCity" class="form-label">IMT</label>
                <div class="input-group mb-6">
                      <input disabled type="text" class="form-control" placeholder="IMT" id="imt" name="imt" value="<?= $awal['imt']?>" aria-describedby="basic-addon2" value="<?php echo $awal['imt']?>">
                      <!-- <span class="input-group-text" id="basic-addon2">celcius</span> -->
                </div>
                </div>

                   <br>
                <br>

                <div>
                  <h5 class="card-title">Pemeriksaan Psikologis, Sosial Ekonomi, Spiritual</h5>
                </div>

                <div class="col-md-12">
                  <label for="inputState" class="form-label" style="font-weight:bold">Status Psikologis</label>
                  <select disabled id="inputState" name="psiko" class="form-select">
                    <option value="<?php echo $awal['psiko']?>"><?php echo $awal['psiko']?></option>
                    <option value="Tidak ada kelainan">Tidak ada kelainan</option>
                    <option value="Cemas">Cemas</option>
                    <option value="Takut">Takut</option>
                    <option value="Marah">Marah</option>
                    <option value="Sedih">Sedih</option>
                  </select>
                </div>
                <!-- <div class="col-md-12">
                  <label for="inputName5" class="form-label">Lain-Lain Psiko</label>
                  <input disabled type="text" class="form-control"  name="psiko" id="inputName5" placeholder="Masukkan status psikologis">
                </div> -->

               <div class="col-md-12">
                  <label for="inputName5" class="form-label" style="font-weight:bold">Sosial Ekonomi</label><br>
                  a. Status Pernikahan :<input disabled type="text" class="form-control"  name="status_nikah" id="inputName5" placeholder="" value="<?php echo $awal['status_nikah']?>" style="margin-bottom:8px">
                  b. Pekerjaan: <input disabled type="text" class="form-control"  name="pekerjaan" id="inputName5" placeholder="" value="<?php echo $awal['pekerjaan']?>" style="margin-bottom:8px"> 
                  c. Tempat Tinggal: 
                 <select disabled name="status_tinggal" class="form-control" id="" style="margin-bottom:8px">
                  <option value="<?php echo $awal['status_tinggal']?>" hidden><?php echo $awal['status_tinggal']?></option>
                  <option value="Sendiri">Sendiri</option>
                  <option value="Keluarga">Keluarga</option>
                  <option value="Lainnya">Lainnya</option>
                 </select>
                 d. Hubungan Pasien Dengan Keluarga: 
                 <select disabled name="hub_keluarga" class="form-control" id="" style="margin-bottom:8px">
                 <option value="<?php echo $awal['hub_keluarga']?>" hidden><?php echo $awal['hub_keluarga']?></option>
                  <option value="Baik">Baik</option>
                  <option value="Tidak Baik">Tidak Baik</option>
                 </select>
                 e. Pengobatan Alternatif: 
                 <select disabled name="pengobatan" class="form-control" id="" style="margin-bottom:8px">
                 <option value="<?php echo $awal['pengobatan']?>" hidden><?php echo $awal['pengobatan']?></option>
                  <option value="Tidak">Tidak</option>
                  <option value="Iya">Iya</option>
                 </select>
                 f. Pantangan: 
                 <select disabled name="pantangan" class="form-control" id="" style="margin-bottom:8px">
                 <option value="<?php echo $awal['pantangan']?>" hidden><?php echo $awal['pantangan']?></option>
                  <option value="Tidak">Tidak</option>
                  <option value="Iya">Iya</option>
                 </select>
                </div>

                 <div class="col-md-12">
                  <label for="inputName5" class="form-label" style="font-weight:bold">Spiritual</label>
                 <select disabled name="spiritual" class="form-control" id="" style="margin-bottom:8px">
                 <option value="<?php echo $awal['spiritual']?>" hidden><?php echo $awal['spiritual']?></option>
                  <option value="Mandiri">Mandiri</option>
                  <option value="Bantuan">Bantuan</option>
                 </select>
                </div>

                <div class="col-md-12">
                  <label for="inputName5" class="form-label" style="font-weight:bold">Status Fungsional</label><br>
                  a. Cacat Tubuh
                 <select disabled name="fungsional_cacat" class="form-control" id="" style="margin-bottom:8px">
                 <option value="<?php echo $awal['fungsional_cacat']?>" hidden><?php echo $awal['fungsional_cacat']?></option>
                  <option value="Tidak">Tidak</option>
                  <option value="Ada">Ada</option>
                 </select>
                 b. Penggunaan Alat Bantu
                 <select disabled name="fungsional_alat" class="form-control" id="" style="margin-bottom:8px">
                 <option value="<?php echo $awal['fungsional_alat']?>" hidden><?php echo $awal['fungsional_alat']?></option>
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
                        <input disabled type="number" oninput="hitungGizi()" name="no1" id="no1" class="form-control" value="<?php echo $awal['no1']?>">
                      </div>
                    <div class="col-md-4">
                      <p>2. Apakah pakaian anda terasa longgar ?</p>
                      <input disabled type="number" oninput="hitungGizi()" name="no2" id="no2" class="form-control" value="<?php echo $awal['no2']?>">
                    </div>
                    <div class="col-md-4">
                      <p>3. Apakah akhir-akhir ini anda kehilangan berat badan yang tidak sengaja ?</p>
                      <input disabled type="number" oninput="hitungGizi()" name="no3" id="no3" class="form-control" value="<?php echo $awal['no3']?>">
                    </div>
                    <div class="col-md-4">
                      <p>4. Apakah anda mengalami penurunan berat badan selama 1 minggu terakhir ?</p>
                      <input disabled type="number" oninput="hitungGizi()" name="no4" id="no4" class="form-control" value="<?php echo $awal['no4']?>">
                    </div>
                    <div class="col-md-4">
                      <p>5. Apakah anda menderita suatu penyakit yang menyebabkan adanya perubahan jumlah jenis makanan yang anda makan ?</p>
                      <input disabled type="number" oninput="hitungGizi()" name="no5" id="no5" class="form-control" value="<?php echo $awal['no5']?>">
                    </div>
                    <div class="col-md-4">
                      <p>6. Apakah anda merasa lemah, loyo dan tidak bertenaga ?</p>
                      <input disabled type="number" oninput="hitungGizi()" name="no6" id="no6" class="form-control" value="<?php echo $awal['no6']?>">
                    </div>
                  </div>
                  <h5 class="mb-0"><b>Jumlah : <span id="totalGizi"></span> (<span id="interpretasiGizi"></span>)</b></h5>
                  
                </div>
                
                <div class="col-md-12">
                  <label for="sel" class="form-label" style="font-weight:bold">Diagnosis Keperawatan</label>
                  <select disabled name="diagnois_prwt" id="sel" class="form-control">
                    <option value="<?php echo $awal['diagnois_prwt']?>"><?php echo $awal['diagnois_prwt']?></option>
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
                    <input disabled type="text" id="lain" style="display: none;" class="form-control">
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
                  <textarea disabled name="rencana_asuh" id="" style="width:100%; height:100%" value="<?php echo $awal['rencana_asuh']?>"><?php echo $awal['rencana_asuh']?></textarea>
                 </div>

          

                <div class="text-center" style="margin-top: 50px; margin-bottom: 80px;">
                <?php 
                $tes=$koneksi->query("SELECT * FROM kajian_awal_inap WHERE norm='$awal[norm]';")->fetch_assoc();
                
                ?>
                <?php if(empty($tes)) { ?>
                  <button type="submit" name="save" class="btn btn-primary">Simpan</button>
                <?php }else{ ?>
                  <button type="submit" name="ubah" class="btn btn-info">Ubah</button>
                <?php } ?>
                  <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
              </form><!-- End Multi Columns Form -->
                 
            </div>
          </div>
        </div>
    
            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->
  <script>
    function hitungIMT(){
      var tinggi_b =document.getElementById("tb").value;
      var berat_b =document.getElementById("bb").value;
      var imtt = berat_b/(tinggi_b*tinggi_b);
      document.getElementById("imt").value = imtt.toFixed(2);
    }
    function hitungGizi() {
      var n1 = document.getElementById("no1").value;
      var n2 = document.getElementById("no2").value;
      var n3 = document.getElementById("no3").value;
      var n4 = document.getElementById("no4").value;
      var n5 = document.getElementById("no5").value;
      var n6 = document.getElementById("no6").value;
      var ttlGizi = Number(n1)+Number(n2)+Number(n3)+Number(n4)+Number(n5)+Number(n6);
      if(ttlGizi <= 2){
        document.getElementById('interpretasiGizi').innerHTML = 'Tidak beresiko Malnutrisi';
      }else{
        document.getElementById('interpretasiGizi').innerHTML = 'Beresiko Malnutrisi';
      }
      document.getElementById("totalGizi").innerHTML = ttlGizi;
    }
  </script>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


</body>

</html>


<?php 


if (isset ($_POST['save'])) 
{
  $anatomi = $_FILES["anatomi"]["name"];
  $lokasi_sementara = $_FILES["anatomi"]["tmp_name"];
  $lokasi_tujuan = "../rawatinap/anatomi/" . $anatomi;

  // Pindahkan file ke lokasi tujuan
  move_uploaded_file($lokasi_sementara, $lokasi_tujuan);

$nama_pasien=htmlspecialchars($_POST["nama_pasien"]);
$norm=htmlspecialchars($_POST["norm"]);

$keluhan_utama=htmlspecialchars($_POST["keluhan_utama"]);

$riwayat_penyakit=htmlspecialchars($_POST["riwayat_penyakit"]);

$riwayat_alergi=htmlspecialchars($_POST["riwayat_alergi"]);

$nama_vaksin=htmlspecialchars($_POST["nama_vaksin"]);

$pemberian_vaksin=($_POST["pemberian_vaksin"]);

$tgl_vaksin=htmlspecialchars($_POST["tgl_vaksin"]);

$suhu_tubuh=htmlspecialchars($_POST["suhu_tubuh"]);

$sistole=htmlspecialchars($_POST["sistole"]);

$distole=htmlspecialchars($_POST["distole"]);

$nadi=htmlspecialchars($_POST["nadi"]);

$frek_nafas=htmlspecialchars($_POST["frek_nafas"]);

$kepala=htmlspecialchars($_POST["kepala"]);

$perut=htmlspecialchars($_POST["perut"]);

$mata=htmlspecialchars($_POST["mata"]);

$telinga=htmlspecialchars($_POST["telinga"]);
$hidung=htmlspecialchars($_POST["hidung"]);
$rambut=htmlspecialchars($_POST["rambut"]);
$bibir=htmlspecialchars($_POST["bibir"]);
$gigi=htmlspecialchars($_POST["gigi"]);
$lidah=htmlspecialchars($_POST["lidah"]);
$langit_langit=htmlspecialchars($_POST["langit_langit"]);
$leher=htmlspecialchars($_POST["leher"]);
$tenggorokan=htmlspecialchars($_POST["tenggorokan"]);
$tonsil=htmlspecialchars($_POST["tonsil"]);
$dada=htmlspecialchars($_POST["dada"]);
$payudara=htmlspecialchars($_POST["payudara"]);
$punggung=htmlspecialchars($_POST["punggung"]);
$genital=htmlspecialchars($_POST["genital"]);
$anus=htmlspecialchars($_POST["anus"]);
$lengan_atas=htmlspecialchars($_POST["lengan_atas"]);
$lengan_bawah=htmlspecialchars($_POST["lengan_bawah"]);
$jari_tangan=htmlspecialchars($_POST["jari_tangan"]);
$kuku_tangan=htmlspecialchars($_POST["kuku_tangan"]);
$persendian_tangan=htmlspecialchars($_POST["persendian_tangan"]);
$tungkai_atas=htmlspecialchars($_POST["tungkai_atas"]);
$tungkai_bawah=htmlspecialchars($_POST["tungkai_bawah"]);
$jari_kaki=htmlspecialchars($_POST["jari_kaki"]);
$kuku_kaki=htmlspecialchars($_POST["kuku_kaki"]);
$persendian_kaki=htmlspecialchars($_POST["persendian_kaki"]);
$tb=htmlspecialchars($_POST["tb"]);
$bb=htmlspecialchars($_POST["bb"]);
$imt=htmlspecialchars($_POST["imt"]);
$psiko=htmlspecialchars($_POST["psiko"]);
$sosial=htmlspecialchars($_POST["sosial"]);
$spiritual=htmlspecialchars($_POST["spiritual"]);
$umur_pasien=htmlspecialchars($_POST["umur_pasien"]);
// $tipe_umur=htmlspecialchars($_POST["tipe_umur"]);
$jk=htmlspecialchars($_POST["jk"]);
$no1=htmlspecialchars($_POST["no1"]);
$no2=htmlspecialchars($_POST["no2"]);
$no3=htmlspecialchars($_POST["no3"]);
$no4=htmlspecialchars($_POST["no4"]);
$no5=htmlspecialchars($_POST["no5"]);
$no6=htmlspecialchars($_POST["no6"]);
$username_admin=htmlspecialchars($_POST["username_admin"]);

$idadmin=htmlspecialchars($_POST["idadmin"]);

$status_log=htmlspecialchars($_POST["status_log"]);

  // $foto=$_FILES['foto']['name'];

  // $lokasi=$_FILES['foto']['tmp_name'];

  // move_uploaded_file($lokasi, '../pasien/foto/'.$foto);

  $koneksi->query("INSERT INTO kajian_awal_inap 

    (nama_pasien, keluhan_utama, riwayat_penyakit, pemberian_vaksin,  riwayat_alergi, tgl_vaksin, nama_vaksin, suhu_tubuh, sistole, distole, nadi, frek_nafas, kepala, perut, mata, telinga, hidung, rambut, bibir, gigi, lidah, langit_langit, leher, tenggorokan, tonsil, dada, payudara, punggung, genital, anus, lengan_atas, lengan_bawah, jari_tangan, kuku_tangan, persendian_tangan, tungkai_atas, tungkai_bawah, jari_kaki, kuku_kaki, persendian_kaki, tb, bb, imt, spiritual, umur_pasien, jk, status_tinggal, hub_keluarga,pengobatan,pantangan,fungsional_cacat, fungsional_alat, no1, no2, no3, no4, no5, no6, norm, psiko, diagnois_prwt, rencana_asuh, anatomi, kesadaran, riwayat_pengobatan, status_nikah, pekerjaan)

    VALUES ('$nama_pasien', '$keluhan_utama', '$riwayat_penyakit', '$pemberian_vaksin', '$riwayat_alergi', '$tgl_vaksin', '$nama_vaksin', '$suhu_tubuh', '$sistole', '$distole', '$nadi',  '$frek_nafas', '$kepala', '$perut', '$mata', '$telinga', '$hidung', '$rambut', '$bibir', '$gigi', '$lidah', '$langit_langit', '$leher', '$tenggorokan', '$tonsil', '$dada', '$payudara', '$punggung', '$genital', '$anus', '$lengan_atas', '$lengan_bawah', '$jari_tangan', '$kuku_tangan', '$persendian_tangan', '$tungkai_atas', '$tungkai_bawah', '$jari_kaki', '$kuku_kaki', '$persendian_kaki', '$tb', '$bb', '$imt', '$spiritual', '$umur_pasien', '$jk', '$_POST[status_tinggal]', '$_POST[hub_keluarga]', '$_POST[pengobatan]', '$_POST[pantangan]', '$_POST[fungsional_cacat]', '$_POST[fungsional_alat]', '$no1', '$no2', '$no3', '$no4', '$no5', '$no6', '$norm','$psiko', '$_POST[diagnois_prwt]', '$_POST[rencana_asuh]', '$anatomi', '$_POST[kesadaran]', '$_POST[riwayat_pengobatan]', '$_POST[status_nikah]','$_POST[pekerjaan]')

    ");


  $koneksi->query("INSERT INTO log_user 

    (status_log, username_admin, idadmin)

    VALUES ('$status_log', '$username_admin', '$idadmin')

    ");


if (mysqli_affected_rows($koneksi)>0) {
  echo "
  <script>
  alert('Data berhasil ditambah');
  document.location.href='index.php?halaman=daftarregistrasi';

   </script>

  ";

} else {

  echo "
  <script>
  alert('GAGAL MENGHAPUS DATA');
  document.location.href='index.php?halaman=daftarregistrasi';
  </script>

  ";

}

}

if(isset($_POST['ubah'])){

  $nama_pasien=htmlspecialchars($_POST["nama_pasien"]);
$norm=htmlspecialchars($_POST["norm"]);

$keluhan_utama=htmlspecialchars($_POST["keluhan_utama"]);

$riwayat_penyakit=htmlspecialchars($_POST["riwayat_penyakit"]);

$riwayat_alergi=htmlspecialchars($_POST["riwayat_alergi"]);

$nama_vaksin=htmlspecialchars($_POST["nama_vaksin"]);

$pemberian_vaksin=($_POST["pemberian_vaksin"]);

$tgl_vaksin=htmlspecialchars($_POST["tgl_vaksin"]);

$suhu_tubuh=htmlspecialchars($_POST["suhu_tubuh"]);

$sistole=htmlspecialchars($_POST["sistole"]);

$distole=htmlspecialchars($_POST["distole"]);

$nadi=htmlspecialchars($_POST["nadi"]);

$frek_nafas=htmlspecialchars($_POST["frek_nafas"]);

$kepala=htmlspecialchars($_POST["kepala"]);

$perut=htmlspecialchars($_POST["perut"]);

$mata=htmlspecialchars($_POST["mata"]);

$telinga=htmlspecialchars($_POST["telinga"]);
$hidung=htmlspecialchars($_POST["hidung"]);
$rambut=htmlspecialchars($_POST["rambut"]);
$bibir=htmlspecialchars($_POST["bibir"]);
$gigi=htmlspecialchars($_POST["gigi"]);
$lidah=htmlspecialchars($_POST["lidah"]);
$langit_langit=htmlspecialchars($_POST["langit_langit"]);
$leher=htmlspecialchars($_POST["leher"]);
$tenggorokan=htmlspecialchars($_POST["tenggorokan"]);
$tonsil=htmlspecialchars($_POST["tonsil"]);
$dada=htmlspecialchars($_POST["dada"]);
$payudara=htmlspecialchars($_POST["payudara"]);
$punggung=htmlspecialchars($_POST["punggung"]);
$genital=htmlspecialchars($_POST["genital"]);
$anus=htmlspecialchars($_POST["anus"]);
$lengan_atas=htmlspecialchars($_POST["lengan_atas"]);
$lengan_bawah=htmlspecialchars($_POST["lengan_bawah"]);
$jari_tangan=htmlspecialchars($_POST["jari_tangan"]);
$kuku_tangan=htmlspecialchars($_POST["kuku_tangan"]);
$persendian_tangan=htmlspecialchars($_POST["persendian_tangan"]);
$tungkai_atas=htmlspecialchars($_POST["tungkai_atas"]);
$tungkai_bawah=htmlspecialchars($_POST["tungkai_bawah"]);
$jari_kaki=htmlspecialchars($_POST["jari_kaki"]);
$kuku_kaki=htmlspecialchars($_POST["kuku_kaki"]);
$persendian_kaki=htmlspecialchars($_POST["persendian_kaki"]);
$tb=htmlspecialchars($_POST["tb"]);
$bb=htmlspecialchars($_POST["bb"]);
$imt=htmlspecialchars($_POST["imt"]);
$psiko=htmlspecialchars($_POST["psiko"]);
$sosial=htmlspecialchars($_POST["sosial"]);
$spiritual=htmlspecialchars($_POST["spiritual"]);
$umur_pasien=htmlspecialchars($_POST["umur_pasien"]);
// $tipe_umur=htmlspecialchars($_POST["tipe_umur"]);
$jk=htmlspecialchars($_POST["jk"]);
$no1=htmlspecialchars($_POST["no1"]);
$no2=htmlspecialchars($_POST["no2"]);
$no3=htmlspecialchars($_POST["no3"]);
$no4=htmlspecialchars($_POST["no4"]);
$no5=htmlspecialchars($_POST["no5"]);
$no6=htmlspecialchars($_POST["no6"]);
$username_admin=htmlspecialchars($_POST["username_admin"]);

$idadmin=htmlspecialchars($_POST["idadmin"]);

$status_log=htmlspecialchars($_POST["status_log"]);


  $koneksi->query("UPDATE kajian_awal_inap SET nama_pasien='$nama_pasien', keluhan_utama='$keluhan_utama', riwayat_penyakit='$riwayat_penyakit', pemberian_vaksin='$pemberian_vaksin', riwayat_alergi='$riwayat_alergi', tgl_vaksin='$tgl_vaksin', nama_vaksin='$nama_vaksin', suhu_tubuh='$suhu_tubuh', sistole='$sistole', distole='$distole', nadi='$nadi', frek_nafas='$frek_nafas', kepala='$kepala', perut='$perut', mata='$mata', telinga='$telinga', hidung='$hidung', rambut='$rambut', bibir='$bibir', gigi='$gigi', lidah='$lidah', langit_langit='$langit_langit', leher='$leher', tenggorokan='$tenggorokan', tonsil='$tonsil', dada='$dada', payudara='$payudara', punggung='$punggung', genital='$genital', anus='$anus', lengan_atas='$lengan_atas', lengan_bawah='$lengan_bawah', jari_tangan='$jari_tangan', kuku_tangan='$kuku_tangan', persendian_tangan='$persendian_tangan', tungkai_atas='$tungkai_atas', tungkai_bawah='$tungkai_bawah', jari_kaki='$jari_kaki', kuku_kaki='$kuku_kaki', persendian_kaki='$persendian_kaki', tb='$tb', bb='$bb', imt='$imt', spiritual='$spiritual', umur_pasien='$umur_pasien', jk='$jk', status_tinggal='$_POST[status_tinggal]', hub_keluarga='$_POST[hub_keluarga]', pengobatan='$_POST[pengobatan]', pantangan='$_POST[pantangan]', fungsional_cacat='$_POST[fungsional_cacat]', fungsional_alat='$_POST[fungsional_alat]', no1='$no1', no2='$no2', no3='$no3', no4='$no4', no5='$no5', no6='$no6', norm='$norm',psiko='$psiko', diagnois_prwt='$_POST[diagnois_prwt]', rencana_asuh='$_POST[rencana_asuh]', riwayat_pengobatan = '$_POST[riwayat_pengobatan]', status_nikah='$_POST[status_nikah]', pekerjaan='$_POST[pekerjaan]' WHERE norm='$_GET[norm]'");

   

  $koneksi->query("INSERT INTO log_user 

    (status_log, username_admin, idadmin)

    VALUES ('$status_log', '$username_admin', '$idadmin')

    ");


if (mysqli_affected_rows($koneksi)>0) {
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


