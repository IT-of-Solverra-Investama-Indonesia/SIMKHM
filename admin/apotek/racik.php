
<?php 
error_reporting(0);
$username=$_SESSION['admin']['username'];
$ambil=$koneksi->query("SELECT * FROM admin  WHERE username='$username';");
$pasien=$koneksi->query("SELECT * FROM kajian_awal WHERE kajian_awal.norm='$_GET[id]';")->fetch_assoc();
if(isset($_GET['all'])){
  $rm=$koneksi->query("SELECT * FROM rekam_medis WHERE rekam_medis.norm='$_GET[id]' AND jadwal = '$_GET[jadwal]';")->fetch_assoc();
}else{
  $rm=$koneksi->query("SELECT * FROM rekam_medis WHERE rekam_medis.norm='$_GET[id]' AND jadwal = '$_GET[tgl]';")->fetch_assoc();
}
$p=$koneksi->query("SELECT * FROM pasien WHERE no_rm='$_GET[id]';")->fetch_assoc();

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
      <h1>Detail Rekam Medis</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active"><a href="index.php?halaman=rekammedisall" style="color:blue;">Rekam Medis</a></li>
          <li class="breadcrumb-item">Detail RM</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

      <section class="section  py-4">
        <div class="container">
          <div class="row">
            <div class="col-lg-12 col-md-12 d-flex">

            <div class="card" style="max-width: 70%; display: inline-flex; position: absolute;">
            <div class="card-body">
              <h6 class="card-title">Data Pasien</h6>

              <!-- Multi Columns Form -->
              <form class="row g-3" method="post" enctype="multipart/form-data">
                <div class="col-md-4">
                  <b><label for="h6Name5" class="form-label">Nama Pasien</label></b>
                  <h6 type="text"    id="h6Name5" placeholder="Masukkan Nama Pasien" value="<?php echo $p['nama_lengkap']?>" name="nama_pasien"><?php echo $p['nama_lengkap']?></h6>
                </div>
                <div class="col-md-4">
                  <b><label for="h6Name5" class="form-label">Umur Pasien</label></b>
                  <h6 type="text"    id="h6Name5" placeholder="Masukkan Nama Pasien" value="<?php echo $p['umur']?>" name="nama_pasien"><?php echo $p['umur']?></h6>
                </div>
                   <div class="col-md-4">
                  <b><label for="h6Name5" class="form-label">Nama Ayah</label></b>
                  <h6 type="text"    id="h6Name5" placeholder="Masukkan Nama Pasien" value="<?php echo $p['nama_ibu']?>" name="nama_ibu"><?php echo $p['nama_ibu']?></h6>
                </div>
                <br>
                <br>
<?php if(!isset($_GET['racik'])) {?>

                <div>
                  <h6 class="card-title">Data Kesehatan</h6>
                </div>
                <div class="col-md-6">
                  <b><label for="h6City" class="form-label">Keluhan Utama </label></b>
                  <h6 type="text"    id="h6City" placeholder="Masukkan No. HP Pasien" name="keluhan_utama"><?php echo $pasien['keluhan_utama']?></h6>
                </div> 
                <div class="col-md-6">
                  <b><label for="h6City" class="form-label">Riwayat Penyakit</label></b>
                  <h6 type="text"    id="h6City" placeholder="Masukkan Email Pasien" name="riwayat_penyakit"><?php echo $pasien['riwayat_penyakit']?></h6>
                </div>
                <div class="col-md-6">
                  <b><label for="h6State" class="form-label">Riwayat Alergi</label></b>
                  <h6 type="text"    id="h6City" placeholder="Masukkan Email Pasien" name="riwayat_alergi"><?php echo $pasien['riwayat_alergi']?></h6>
                </div>
                 <div class="col-md-6">
                  <b><label for="h6State" class="form-label">Gol. Darah</label></b>
                  <h6 type="text"    id="h6City" placeholder="Masukkan Email Pasien" name="gol_darah"><?php echo $rm['gol_darah']?></h6>
                </div>
                 <div class="col-md-6">
                  <b><label for="h6State" class="form-label">Status Perokok</label></b>
                  <h6 type="text"    id="h6City" placeholder="Masukkan Email Pasien" name="status_perokok"><?php echo $rm['status_perokok']?></h6>
                </div>
                 <div class="col-md-6">
                  <b><label for="h6State" class="form-label">Anamnesa</label></b>
                  <h6 type="text"    id="h6City" placeholder="Masukkan Email Pasien" name="anamnesa"><?php echo $rm['anamnesa']?></h6>
                </div>
                <div class="col-md-6">
                  <b><label for="h6State" class="form-label">Diagnosis</label></b>
                  <h6 type="text"    id="h6City" placeholder="Masukkan Email Pasien" name="anamnesa"><?php echo $rm['diagnosis']?></h6>
                </div>
                <div class="col-md-6">
                  <b><label for="h6State" class="form-label">Prognosis</label></b>
                  <h6 type="text"    id="h6City" placeholder="Masukkan Email Pasien" name="anamnesa"><?php echo $rm['prognosa']?></h6>
                </div>
                  <div class="col-md-6">
                  <b><label for="h6State" class="form-label">ICD</label></b>
                  <h6 type="text"    id="h6City" placeholder="Masukkan Email Pasien" name="anamnesa"><?php echo $rm['icd']?></h6>
                </div>
                <div class="col-md-6">
                  <b><label for="h6State" class="form-label">Medikametosa</label></b>
                  <h6 type="text"    id="h6City" placeholder="Masukkan Email Pasien" name="anamnesa"><?php echo $rm['medika']?></h6>
                </div>
                <div class="col-md-6">
                  <b><label for="h6State" class="form-label">Non Medikametosa</label></b>
                  <h6 type="text"    id="h6City" placeholder="Masukkan Email Pasien" name="anamnesa"><?php echo $rm['nonmedika']?></h6>
                </div>
               
                <br>
                <br>

                <div>
                  <h6 class="card-title">Riwayat Vaksinasi</h6>
                </div>
                <div class="col-md-6">
                  <b><label for="h6City" class="form-label">Nama Vaksin </label></b>
                  <h6 type="text"    id="h6City" placeholder="Masukkan No. HP Pasien" name="nama_vaksin"><?php echo $pasien['nama_vaksin']?></h6>
                </div> 
                <div class="col-md-6">
                  <b><label for="h6City" class="form-label">Pemberian Ke-</label></b>
                  <h6 type="text"    id="h6City" placeholder="Masukkan Email Pasien" name="pemberian_vaksin"><?php echo $pasien['pemberian_vaksin']?></h6>
                </div>
                <div class="col-md-12">
                  <b><label for="h6City" class="form-label">Tanggal Pemberian</label></b>
                  <h6 type="date"    id="h6City" placeholder="Masukkan Email Pasien" name="tgl_vaksin"><?php echo $pasien['tgl_vaksin']?></h6>
                </div>

                <br>
                <br>

                <div>
                  <h6 class="card-title">Tanda-Tanda Vital</h6>
                </div>

                <div class="col-md-12">
                <b><label for="h6City" class="form-label">Suhu Tubuh</label></b>
                <div class="h6-group mb-6">
                      <h6 type="text"    placeholder="Suhu Tubuh" name="suhu_tubuh" aria-describedby="basic-addon2"><?php echo $pasien['suhu_tubuh']?>
                      <span class="h6-group-text" id="basic-addon2">celcius</span></h6>
                </div>
                </div>

                <div class="col-md-6">

                <b><label for="h6City" class="form-label">Sistole</label></b>
                 <div class="h6-group mb-6">
                      <h6 type="text"    placeholder="Tekanan Darah" name="sistole" aria-describedby="basic-addon2"><?php echo $pasien['sistole']?>
                      <span class="h6-group-text" id="basic-addon2">mmHg</span></h6>
                </div>
                </div>

                <div class="col-md-6">
                <b><label for="h6City" class="form-label">Distole</label></b>
                 <div class="h6-group mb-6">
                      <h6 type="text"    placeholder="Tekanan Darah" name="distole" aria-describedby="basic-addon2"><?php echo $pasien['distole']?>
                      <span class="h6-group-text" id="basic-addon2">mmHg</span></h6>
                </div>
                </div>
               
                <div class="col-md-12">
                <b><label for="h6City" class="form-label">Nadi</label></b>
                <div class="h6-group mb-6">
                      <h6 type="text"    placeholder="Denyut Nadi" name="nadi" aria-describedby="basic-addon2"><?php echo $pasien['nadi']?>
                      <span class="h6-group-text" id="basic-addon2">kali/menit</span></h6>
                </div>
                </div>

                 <div class="col-md-12">
                <b><label for="h6City" class="form-label">Frekuensi Pernafasan</label></b>
                <div class="h6-group mb-6">
                      <h6 type="text"    placeholder="Frekuensi Pernafasan" name="frek_nafas" aria-describedby="basic-addon2"><?php echo $pasien['frek_nafas']?>
                      <span class="h6-group-text" id="basic-addon2">kali/menit</span></h6>
                </div>
                </div>
                
                 <br>
                <br>

                <div>
                  <h6 class="card-title">Pemeriksaan Fisik</h6>
                </div>

                <div class="col-md-6">
                <b><label for="h6City" class="form-label">Lingkar Kepala</label></b>
                 <div class="h6-group mb-6">
                      <h6 type="text"    placeholder="Lingkar Kepala" name="kepala" aria-describedby="basic-addon2"><?php echo $pasien['kepala']?>
                      <span class="h6-group-text" id="basic-addon2">cm</span></h6>
                </div>
                </div>

                <div class="col-md-6">
                <b><label for="h6City" class="form-label">Lingkar Perut</label></b>
                 <div class="h6-group mb-6">
                      <h6 type="text"    placeholder="Lingkar Perut" name="perut" aria-describedby="basic-addon2"><?php echo $pasien['perut']?>
                      <span class="h6-group-text" id="basic-addon2">cm</span></h6>
                </div>
                </div>

            
                <div class="col-md-6">
                  <b><label for="h6Name5" class="form-label">Mata</label></b>
                  <h6 type="text"     name="mata" id="h6Name5" placeholder="Mata"><?php echo $pasien['mata']?></h6>
                </div>

                <div class="col-md-6">
                  <b><label for="h6Name5" class="form-label">Telinga</label></b>
                  <h6 type="text"     name="telinga" id="h6Name5" placeholder="Telinga"><?php echo $pasien['telinga']?></h6>
                </div>

                <div class="col-md-6">
                  <b><label for="h6Name5" class="form-label">Hidung</label></b>
                  <h6 type="text"     name="hidung" id="h6Name5" placeholder="Hidung"><?php echo $pasien['hidung']?></h6>
                </div>

                 <div class="col-md-6">
                  <b><label for="h6Name5" class="form-label">Rambut</label></b>
                  <h6 type="text"     name="rambut" id="h6Name5" placeholder="Rambut"><?php echo $pasien['rambut']?></h6>
                </div>

                 <div class="col-md-6">
                  <b><label for="h6Name5" class="form-label">Bibir</label></b>
                  <h6 type="text"     name="bibir" id="h6Name5" placeholder="Bibir"><?php echo $pasien['bibir']?></h6>
                </div>
                 <div class="col-md-6">
                  <b><label for="h6Name5" class="form-label">Gigi Geligi</label></b>
                  <h6 type="text"     name="gigi" id="h6Name5" placeholder="Gigi Geligi"><?php echo $pasien['gigi']?></h6>
                </div>

                 <div class="col-md-6">
                  <b><label for="h6Name5" class="form-label">Lidah</label></b>
                  <h6 type="text"     name="lidah" id="h6Name5" placeholder="Lidah"><?php echo $pasien['lidah']?></h6>
                </div>

                 <div class="col-md-6">
                  <b><label for="h6Name5" class="form-label">Langit-langit</label></b>
                  <h6 type="text"     name="langit_langit" id="h6Name5" placeholder="Langit-langit"><?php echo $pasien['langit_langit']?></h6>
                </div>

                 <div class="col-md-6">
                  <b><label for="h6Name5" class="form-label">Leher</label></b>
                  <h6 type="text"     name="leher" id="h6Name5" placeholder="Leher"><?php echo $pasien['leher']?></h6>
                </div>

                 <div class="col-md-6">
                  <b><label for="h6Name5" class="form-label">Tenggorokan</label></b>
                  <h6 type="text"     name="tenggorokan" id="h6Name5" placeholder="Tenggorokan"><?php echo $pasien['tenggorokan']?></h6>
                </div>

                 <div class="col-md-6">
                  <b><label for="h6Name5" class="form-label">Tonsil</label></b>
                  <h6 type="text"     name="tonsil" id="h6Name5" placeholder="Tonsil"><?php echo $pasien['tonsil']?></h6>
                </div>

                 <div class="col-md-6">
                  <b><label for="h6Name5" class="form-label">Dada</label></b>
                  <h6 type="text"     name="dada" id="h6Name5" placeholder="Dada"><?php echo $pasien['dada']?></h6>
                </div>

                 <div class="col-md-6">
                  <b><label for="h6Name5" class="form-label">Payudara</label></b>
                  <h6 type="text"     name="payudara" id="h6Name5" placeholder="Payudara"><?php echo $pasien['payudara']?></h6>
                </div>

                 <div class="col-md-6">
                  <b><label for="h6Name5" class="form-label">Punggung</label></b>
                  <h6 type="text"     name="punggung" id="h6Name5" placeholder="Punggung"><?php echo $pasien['punggung']?></h6>
                </div>

                 <div class="col-md-6">
                  <b><label for="h6Name5" class="form-label">Genital</label></b>
                  <h6 type="text"     name="genital" id="h6Name5" placeholder="Genital"><?php echo $pasien['genital']?></h6>
                </div>

                 <div class="col-md-6">
                  <b><label for="h6Name5" class="form-label">Anus</label></b>
                  <h6 type="text"     name="anus" id="h6Name5" placeholder="Anus"><?php echo $pasien['anus']?></h6>
                </div>

                 <div class="col-md-6">
                  <b><label for="h6Name5" class="form-label">Lengan Atas</label></b>
                  <h6 type="text"     name="lengan_atas" id="h6Name5" placeholder="Lengan Atas"><?php echo $pasien['lengan_atas']?></h6>
                </div>

                 <div class="col-md-6">
                  <b><label for="h6Name5" class="form-label">Lengan Bawah</label></b>
                  <h6 type="text"     name="lengan_bawah" id="h6Name5" placeholder="Lengan Bawah"><?php echo $pasien['lengan_bawah']?></h6>
                </div>

                 <div class="col-md-6">
                  <b><label for="h6Name5" class="form-label">Jari Tangan</label></b>
                  <h6 type="text"     name="jari_tangan" id="h6Name5" placeholder="Jari Tangan"><?php echo $pasien['jari_tangan']?></h6>
                </div>

                 <div class="col-md-6">
                  <b><label for="h6Name5" class="form-label">Kuku Tangan</label></b>
                  <h6 type="text"     name="kuku_tangan" id="h6Name5" placeholder="Kuku Tangan"><?php echo $pasien['kuku_tangan']?></h6>
                </div>

                 <div class="col-md-6">
                  <b><label for="h6Name5" class="form-label">Persendian Tangan</label></b>
                  <h6 type="text"     name="persendian_tangan" id="h6Name5" placeholder="Persendian Tangan"><?php echo $pasien['persendian_tangan']?></h6>
                </div>

                <div class="col-md-6">
                  <b><label for="h6Name5" class="form-label">Tungkai Atas</label></b>
                  <h6 type="text"     name="tungkai_atas" id="h6Name5" placeholder="Tungkai Atas"><?php echo $pasien['tungkai_atas']?></h6>
                </div>

                <div class="col-md-6">
                  <b><label for="h6Name5" class="form-label">Tungkai Bawah</label></b>
                  <h6 type="text"     name="tungkai_bawah" id="h6Name5" placeholder="Tungkai Bawah"><?php echo $pasien['tungkai_bawah']?></h6>
                </div>

                <div class="col-md-6">
                  <b><label for="h6Name5" class="form-label">Jari Kaki</label></b>
                  <h6 type="text"     name="jari_kaki" id="h6Name5" placeholder="Jari Kaki"><?php echo $pasien['jari_kaki']?></h6>
                </div>

                <div class="col-md-6">
                  <b><label for="h6Name5" class="form-label">Kuku Kaki</label></b>
                  <h6 type="text"     name="kuku_kaki" id="h6Name5" placeholder="Kuku Kaki"><?php echo $pasien['kuku_kaki']?></h6>
                </div>

                <div class="col-md-6">
                  <b><label for="h6Name5" class="form-label">Persendian Kaki</label></b>
                  <h6 type="text"     name="persendian_kaki" id="h6Name5" placeholder="Persendian Kaki"><?php echo $pasien['persendian_kaki']?></h6>
                </div>
               
                <div class="col-md-6">
                <b><label for="h6City" class="form-label">Tinggi Badan</label></b>
                <div class="h6-group mb-6">
                      <h6 type="text"    placeholder="Tinggi Badan" name="tb" aria-describedby="basic-addon2"><?php echo $pasien['tb']?>
                      <span class="h6-group-text" id="basic-addon2">m</span></h6>
                </div>
                </div>

                 <div class="col-md-6">
                <b><label for="h6City" class="form-label">Berat Badan</label></b>
                <div class="h6-group mb-6">
                      <h6 type="text"    placeholder="Berat Badan" name="bb" aria-describedby="basic-addon2"><?php echo $pasien['bb']?>
                      <span class="h6-group-text" id="basic-addon2">kg</span></h6>
                </div>
                </div>

                <div class="col-md-12">
                <b><label for="h6City" class="form-label">IMT</label></b>
                <div class="h6-group mb-6">
                      <h6 type="text"    placeholder="IMT" name="imt" aria-describedby="basic-addon2"><?php echo $pasien['imt']?></h6>
                      <!-- <span class="h6-group-text" id="basic-addon2">celcius</span> -->
                </div>
                </div>

                   <br>
                <br>

                <div>
                  <h6 class="card-title">Pemeriksaan Psikologis, Sosial Ekonomi, Spiritual</h6>
                </div>

                 <div class="col-md-12">
                  <b><label for="h6State" class="form-label">Status Psikologis</label></b>
                  <h6 type="text"    placeholder="Psikologis" name="psiko" aria-describedby="basic-addon2"><?php echo $pasien['psiko']?></h6>
                
                </div>
                  <div class="col-md-12">
                  <b><label for="h6Name5" class="form-label">Lain-Lain Psiko</label></b>
                  <h6 type="text"     name="psiko" id="h6Name5" placeholder="Masukkan status psikologis"><?php echo $pasien['psiko']?></h6>
                </div>

               <div class="col-md-12">
                  <b><label for="h6Name5" class="form-label">Sosial Ekonomi</label></b>
                  <h6>a. Status Pernikahan: <?php echo $p['status_nikah']?></h6>
                  <h6>b. Pekerjaan: <?php echo $p['pekerjaan']?></h6>
                  <h6>c. Tempat Tinggal: <?php echo $pasien['status_tinggal']?></h6>
                  <h6>d. Hub Pasien Dengan Keluarga: <?php echo $pasien['hub_keluarga']?></h6>
                  <h6>e. Pengobatan Alternatif: <?php echo $pasien['pengobatan']?></h6>
                  <h6>f. Pantangan: <?php echo $pasien['pantangan']?></h6>
                </div>

                 <div class="col-md-12">
                  <b><label for="h6Name5" class="form-label">Spiritual</label></b>
                  <h6 type="text"     name="spiritual" id="h6Name5" placeholder="Masukkan Agama/Nilai-nilai spiritual Pasien"><?php echo $pasien['spiritual']?></h6>
                </div>

                <div>
                  <h6 class="card-title">Obat Pasien</h6>
                </div>
<?php

$obat=$koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND DATE_FORMAT(tgl_pasien, '%Y-%m-%d') = DATE_FORMAT('$_GET[tgl]', '%Y-%m-%d')"); 

?>
              <div class="row">
               <div class="table-responsive" id="obat">

                <br>
                <div id="employee_table">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                            <th width="5%">No.</th>
                            <th width="50%">Obat</th>
                            <th width="20%">Dosis</th>
                            <th width="20%">Jumlah</th>
                            <th width="20%">Jenis</th>
                            <th width="20%">Durasi</th>
                        </tr>
                      </thead>
                       <tbody>

                    <?php $no=1 ?>

                  <?php foreach ($obat as $obat) : ?>

                  <tr>
                  <td><?php echo $no; ?></td>
                  <td style="margin-top:10px;"><?php echo $obat["nama_obat"]; ?></td>
                  <td style="margin-top:10px;"><?php echo $obat["dosis1_obat"]; ?> X <?php echo $obat["dosis2_obat"]; ?>  <?php echo $obat["per_obat"]; ?></td>
                  <td style="margin-top:10px;"><?php echo $obat["jml_dokter"]; ?></td>
                  <td style="margin-top:10px;"><?php echo $obat["jenis_obat"]; ?></td>
                  <td style="margin-top:10px;"><?php echo $obat["durasi_obat"]; ?> hari</td>
                  </tr>

                  <?php $no +=1 ?>
                  <?php endforeach ?>

                      </tbody>
                      
                    </table>
                </div>
                </div>
              </div>
    <?php }else{ ?>

      <div>
                  <h6 class="card-title">Obat Pasien</h6>
                </div>
<?php

$obat=$koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND DATE_FORMAT(tgl_pasien, '%Y-%m-%d') = DATE_FORMAT('$_GET[tgl]', '%Y-%m-%d')"); 

?>
              <div class="row">
                <a href="racik.php" style="width: 200px;" type="index.php?halaman=racik" class="btn btn-sm btn-primary">Racik</a>

               <div class="table-responsive" id="obat">
                 <!-- <div align="right">
                    <button type="button" class="btn btn-primary text-right" data-bs-toggle="modal" data-bs-target="#exampleModal2">Add</button>
                </div> -->
                <br>
                <div id="employee_table">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                            <th width="5%">No.</th>
                            <th width="50%">Obat</th>
                            <th width="20%">Dosis</th>
                            <th width="20%">Jumlah</th>
                            <th width="20%">Jenis</th>
                            <th width="20%">Durasi</th>
                        </tr>
                      </thead>
                       <tbody>

                    <?php $no=1 ?>

                  <?php foreach ($obat as $obat) : ?>

                  <tr>
                  <td><?php echo $no; ?></td>
                  <td style="margin-top:10px;"><?php echo $obat["nama_obat"]; ?></td>
                  <td style="margin-top:10px;"><?php echo $obat["dosis1_obat"]; ?> X <?php echo $obat["dosis2_obat"]; ?>  <?php echo $obat["per_obat"]; ?></td>
                  <td style="margin-top:10px;"><?php echo $obat["jml_dokter"]; ?></td>
                  <td style="margin-top:10px;"><?php echo $obat["jenis_obat"]; ?></td>
                  <td style="margin-top:10px;"><?php echo $obat["durasi_obat"]; ?> hari</td>
                  </tr>

                  <?php $no +=1 ?>
                  <?php endforeach ?>

                      </tbody>
                      
                    </table>
                </div>
                </div>
              </div>
      <?php } ?>
            <br>
            <br>


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

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


</body>

</html>
