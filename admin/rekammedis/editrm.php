
<?php 
error_reporting(0);
$username=$_SESSION['admin']['username'];
$ambil=$koneksi->query("SELECT * FROM admin  WHERE username='$username';");
if(isset($_GET['inap'])){
  $pasien=$koneksi->query("SELECT * FROM kajian_awal_inap WHERE kajian_awal_inap.norm='$_GET[id]';")->fetch_assoc();
  $lab=$koneksi->query("SELECT * FROM lab_hasil JOIN daftartes WHERE id_inap='$_GET[rawat]' AND nama_periksa=nama_tes ORDER BY idhasil");

}else{
  $pasien=$koneksi->query("SELECT * FROM kajian_awal WHERE kajian_awal.norm='$_GET[id]';")->fetch_assoc();

  $lab=$koneksi->query("SELECT * FROM lab_hasil JOIN daftartes WHERE id_lab_h='$_GET[rawat]' AND nama_periksa=nama_tes");

}


if(isset($_GET['all'])){
  $rm=$koneksi->query("SELECT * FROM rekam_medis WHERE rekam_medis.norm='$_GET[id]' AND DATE_FORMAT(jadwal, '%Y-%m-%d') = '$_GET[tgl]';")->fetch_assoc();
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

                <div>
                  <h6 class="card-title">Data Kesehatan</h6>
                  <label><b>Tanda-Tanda Vital</b></label>
                </div>
    
                    <div class="col-md-6">
                    <label for="inputCity" class="form-label">Suhu Tubuh</label>
                    <div class="input-group mb-6" style="margin-bottom:10px">
                          <input type="text" class="form-control" placeholder="Suhu Tubuh" name="suhu_tubuh" aria-describedby="basic-addon2" value="<?php echo $pecah['suhu_tubuh']?>" >
                          <span class="input-group-text" id="basic-addon2">celcius</span>
                    </div>
                    </div>
    
                    <div class="col-md-6">
                    <label for="inputCity" class="form-label">Saturasi Oksigen</label>
                    <div class="input-group mb-6" style="margin-bottom:10px">
                          <input type="text" class="form-control" placeholder="Saturasi Oksigen" name="oksigen" aria-describedby="basic-addon2" value="<?php echo $pecah['oksigen']?>" >
                          <span class="input-group-text" id="basic-addon2">%</span>
                    </div>
                    </div>
    
                    <div class="col-md-6">
    
                    <label for="inputCity" class="form-label">Sistole</label>
                     <div class="input-group mb-6" style="margin-bottom:10px">
                          <input type="text" class="form-control" placeholder="Tekanan Darah" name="sistole" aria-describedby="basic-addon2" value="<?php echo $pecah['sistole']?>" >
                          <span class="input-group-text" id="basic-addon2">mmHg</span>
                    </div>
                    </div>
    
                    <div class="col-md-6">
                    <label for="inputCity" class="form-label">Distole</label>
                     <div class="input-group mb-6" style="margin-bottom:10px">
                          <input type="text" class="form-control" placeholder="Tekanan Darah" name="distole" aria-describedby="basic-addon2" value="<?php echo $pecah['distole']?>" >
                          <span class="input-group-text" id="basic-addon2">mmHg</span>
                    </div>
                    </div>
                   
                    <div class="col-md-6">
                    <label for="inputCity" class="form-label">Nadi</label>
                    <div class="input-group mb-6" style="margin-bottom:10px">
                          <input type="text" class="form-control" placeholder="Denyut Nadi" name="nadi" aria-describedby="basic-addon2" value="<?php echo $pecah['nadi']?>" >
                          <span class="input-group-text" id="basic-addon2">kali/menit</span>
                    </div>
                    </div>
    
                  <div class="col-md-6">
                    <label for="inputCity" class="form-label">Frekuensi Pernafasan</label>
                    <div class="input-group mb-6" style="margin-bottom:10px">
                          <input type="text" class="form-control" placeholder="Frekuensi Pernafasan" name="frek_nafas" aria-describedby="basic-addon2" value="<?php echo $pecah['frek_nafas']?>" >
                          <span class="input-group-text" id="basic-addon2">kali/menit</span>
                    </div>
                    <br>
                  </div>
                  <hr>
                  
                <div class="col-md-6">
                  <b><label for="h6City" class="form-label">Keluhan Utama </label></b>
                  <input type="text"    id="h6City" placeholder="Masukkan No. HP Pasien" name="keluhan_utama" class="form-control" value="<?php echo $pasien['keluhan_utama']?>"></input>
                </div> 
                <div class="col-md-6">
                  <b><label for="h6City" class="form-label">Riwayat Penyakit</label></b>
                  <input type="text"    id="h6City" placeholder="Masukkan Email Pasien" name="riwayat_penyakit" class="form-control" value="<?php echo $pasien['riwayat_penyakit']?>"></input>
                </div>
                <div class="col-md-6">
                  <b><label for="h6State" class="form-label">Riwayat Alergi</label></b>
                  <input type="text"    id="h6City" placeholder="Masukkan Email Pasien" name="riwayat_alergi" class="form-control" value="<?php echo $pasien['riwayat_alergi']?>"></input>
                </div>
                 <div class="col-md-6">
                  <b><label for="h6State" class="form-label">Gol. Darah</label></b>
                  <input type="text"    id="h6City" placeholder="Masukkan Email Pasien" name="gol_darah" class="form-control" value="<?php echo $rm['gol_darah']?>"></input>
                </div>
                 <div class="col-md-6">
                  <b><label for="h6State" class="form-label">Status Perokok</label></b>
                  <input type="text"    id="h6City" placeholder="Masukkan Email Pasien" name="status_perokok" class="form-control" value="<?php echo $rm['status_perokok']?>"></input>
                </div>
                 <div class="col-md-6">
                  <b><label for="h6State" class="form-label">Anamnesa</label></b>
                  <input type="text"    id="h6City" placeholder="Masukkan Email Pasien" name="anamnesa" class="form-control" value=""<?php echo $rm['anamnesa']?>></input>
                </div>
                <div class="col-md-6">
                  <b><label for="h6State" class="form-label">Diagnosis</label></b>
                  <input type="text"    id="h6City" placeholder="Masukkan Email Pasien" name="diagnosis" class="form-control" value="<?php echo $rm['diagnosis']?>"></input>
                </div>
                <div class="col-md-6">
                  <b><label for="h6State" class="form-label">Prognosis</label></b>
                  <input type="text"    id="h6City" placeholder="Masukkan Email Pasien" name="prognosa" class="form-control" value="<?php echo $rm['prognosa']?>"></input>
                </div>
                  <div class="col-md-12">
                  <b><label for="h6State" class="form-label">ICD</label></b>
                  <input type="text"    id="h6City" placeholder="Masukkan Email Pasien" name="icd" class="form-control" value="<?php echo $rm['icd']?>"></input>
                </div>
               
               
                <br>
                <br>

                <div>
                  <h6 class="card-title">Riwayat Vaksinasi</h6>
                </div>
                <div class="col-md-6">
                  <b><label for="h6City" class="form-label">Nama Vaksin </label></b>
                  <input type="text" id="h6City" placeholder="Masukkan No. HP Pasien" name="nama_vaksin" class="form-control" value="<?php echo $pasien['nama_vaksin']?>"></input>
                </div> 
                <div class="col-md-6">
                  <b><label for="h6City" class="form-label">Pemberian Ke-</label></b>
                  <input type="text" id="h6City" placeholder="Masukkan Email Pasien" name="pemberian_vaksin" class="form-control" value="<?php echo $pasien['pemberian_vaksin']?>"></input>
                </div>
                <div class="col-md-12">
                  <b><label for="h6City" class="form-label">Tanggal Pemberian</label></b>
                  <input type="date" id="h6City" placeholder="Masukkan Email Pasien" name="tgl_vaksin" class="form-control" value="<?php echo $pasien['tgl_vaksin']?>"></input>
                </div>

                <br>
                <br>

                <div>
                  <h6 class="card-title">Tanda-Tanda Vital</h6>
                </div>

                <div class="col-md-12">
                <b><label for="h6City" class="form-label">Suhu Tubuh</label></b>
                <div class="input-group mb-6">
                      <input type="text"    placeholder="Suhu Tubuh" name="suhu_tubuh" aria-describedby="basic-addon2" value="<?php echo $pasien['suhu_tubuh']?>" class="form-control">
                      <span class="input-group-text" id="basic-addon2">celcius</span></input>
                </div>
                </div>

                <div class="col-md-6">

                <b><label for="h6City" class="form-label">Sistole</label></b>
                 <div class="input-group mb-6">
                      <input type="text"    placeholder="Tekanan Darah" name="sistole" aria-describedby="basic-addon2" value="<?php echo $pasien['sistole']?>" class="form-control">
                      <span class="input-group-text" id="basic-addon2">mmHg</span></input>
                </div>
                </div>

                <div class="col-md-6">
                <b><label for="h6City" class="form-label">Distole</label></b>
                 <div class="input-group mb-6">
                      <input type="text"    placeholder="Tekanan Darah" name="distole" aria-describedby="basic-addon2" value="<?php echo $pasien['distole']?>" class="form-control">
                      <span class="input-group-text" id="basic-addon2">mmHg</span></input>
                </div>
                </div>
               
                <div class="col-md-12">
                <b><label for="h6City" class="form-label">Nadi</label></b>
                <div class="input-group mb-6">
                      <input type="text" value="<?php echo $pasien['nadi']?>" class="form-control" placeholder="Denyut Nadi" name="nadi" aria-describedby="basic-addon2">
                      <span class="input-group-text" id="basic-addon2">kali/menit</span></input>
                </div>
                </div>

                 <div class="col-md-12">
                <b><label for="h6City" class="form-label">Frekuensi Pernafasan</label></b>
                <div class="input-group mb-6">
                      <input type="text" value="<?php echo $pasien['frek_nafas']?>" class="form-control" placeholder="Frekuensi Pernafasan" name="frek_nafas" aria-describedby="basic-addon2">
                      <span class="input-group-text" id="basic-addon2">kali/menit</span></input>
                </div>
                </div>
                
                 <br>
                <br>

                <div>
                  <h6 class="card-title">Pemeriksaan Fisik</h6>
                </div>

                <div class="col-md-6">
                <b><label for="h6City" class="form-label">Lingkar Kepala</label></b>
                 <div class="input-group mb-6">
                      <input type="text"    placeholder="Lingkar Kepala" name="kepala" aria-describedby="basic-addon2" class="form-control" value="<?php echo $pasien['kepala']?>">
                      <span class="input-group-text" id="basic-addon2">cm</span></input>
                </div>
                </div>

                <div class="col-md-6">
                <b><label for="h6City" class="form-label">Lingkar Perut</label></b>
                 <div class="input-group mb-6">
                      <input type="text"    placeholder="Lingkar Perut" name="perut" aria-describedby="basic-addon2" class="form-control" value="<?php echo $pasien['perut']?>">
                      <span class="input-group-text" id="basic-addon2">cm</span></input>
                </div>
                </div>
               
                <div class="col-md-6">
                <b><label for="h6City" class="form-label">Tinggi Badan</label></b>
                <div class="input-group mb-6">
                      <input type="text"    placeholder="Tinggi Badan" name="tb" aria-describedby="basic-addon2" class="form-control" value="<?php echo $pasien['tb']?>">
                      <span class="input-group-text" id="basic-addon2">m</span></input>
                </div>
                </div>

                 <div class="col-md-6">
                <b><label for="h6City" class="form-label">Berat Badan</label></b>
                <div class="input-group mb-6">
                      <input type="text"    placeholder="Berat Badan" name="bb" aria-describedby="basic-addon2" class="form-control" value="<?php echo $pasien['bb']?>">
                      <span class="input-group-text" id="basic-addon2">kg</span></input>
                </div>
                </div>

                <div class="col-md-12">
                <b><label for="h6City" class="form-label">IMT</label></b>
                <div class="input-group mb-6">
                      <input type="text"    placeholder="IMT" name="imt" aria-describedby="basic-addon2" class="form-control" value="<?php echo $pasien['imt']?>"></input>
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
                  <input type="text" class="form-control"   placeholder="Psikologis" name="psiko" aria-describedby="basic-addon2" value="<?php echo $pasien['psiko']?>"></input>
                </div>

               <div class="col-md-12">
                  <b><label for="h6Name5" class="form-label">Sosial Ekonomi</label></b>
              </div>

              <div class="col-md-6">
                  <label>a. Status Pernikahan: </label>
                  <input type="text" class="form-control"   placeholder="Psikologis" name="status_nikah" aria-describedby="basic-addon2" value="<?php echo $p['status_nikah']?>"></input>
              </div>

              <div class="col-md-6">

                  <label>b. Pekerjaan: </label>
                  <input type="text" class="form-control"   placeholder="Psikologis" name="status_nikah" aria-describedby="basic-addon2" value="<?php echo $p['pekerjaan']?>"></input>

                </div>

              <div class="col-md-6">
                  <label>c. Tempat Tinggal: </label>
                  <input type="text" class="form-control"   placeholder="Psikologis" name="status_nikah" aria-describedby="basic-addon2" value="<?php echo $pasien['status_tinggal']?>"></input></div>
                  
              <div class="col-md-6">

                  <label>d. Hub Pasien Dengan Keluarga: </label>
                  <input type="text" class="form-control"   placeholder="Psikologis" name="status_nikah" aria-describedby="basic-addon2" value="<?php echo $pasien['hub_keluarga']?>"></input></div>

              <div class="col-md-6">

                  <label>e. Pengobatan Alternatif: </label>
                  <input type="text" class="form-control"   placeholder="Psikologis" name="status_nikah" aria-describedby="basic-addon2" value="<?php echo $pasien['pengobatan']?>"></input></div>

              <div class="col-md-6">
                  
                  <label>f. Pantangan: </label>
                  <input type="text" class="form-control"   placeholder="Psikologis" name="status_nikah" aria-describedby="basic-addon2" value="<?php echo $pasien['pantangan']?>"></input></div>


                 <div class="col-md-12">
                  <b><label for="h6Name5" class="form-label">Spiritual</label></b>
                  <input type="text" value="<?php echo $pasien['spiritual']?>" class="form-control" name="spiritual" id="h6Name5" placeholder="Masukkan Agama/Nilai-nilai spiritual Pasien"></input>
                </div>
                </div>
                <center>
                    <br>
                    <button type="submit" name="ubah" class="btn btn-primary">Simpan</button>
                </center>
<br>
<br>
<br>

<?php

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

$tb=htmlspecialchars($_POST["tb"]);
$bb=htmlspecialchars($_POST["bb"]);
$imt=htmlspecialchars($_POST["imt"]);
$psiko=htmlspecialchars($_POST["psiko"]);
$sosial=htmlspecialchars($_POST["sosial"]);
$spiritual=htmlspecialchars($_POST["spiritual"]);
$no1=htmlspecialchars($_POST["no1"]);
$no2=htmlspecialchars($_POST["no2"]);
$no3=htmlspecialchars($_POST["no3"]);
$no4=htmlspecialchars($_POST["no4"]);
$no5=htmlspecialchars($_POST["no5"]);
$no6=htmlspecialchars($_POST["no6"]);




  $koneksi->query("UPDATE kajian_awal SET nama_pasien='$nama_pasien', keluhan_utama='$keluhan_utama', riwayat_penyakit='$riwayat_penyakit', pemberian_vaksin='$pemberian_vaksin', riwayat_alergi='$riwayat_alergi', tgl_vaksin='$tgl_vaksin', nama_vaksin='$nama_vaksin', suhu_tubuh='$suhu_tubuh', sistole='$sistole', distole='$distole', nadi='$nadi', frek_nafas='$frek_nafas', kepala='$kepala', perut='$perut', tb='$tb', bb='$bb', imt='$imt', spiritual='$spiritual', status_tinggal='$_POST[status_tinggal]', hub_keluarga='$_POST[hub_keluarga]', pengobatan='$_POST[pengobatan]', pantangan='$_POST[pantangan]', no1='$no1', no2='$no2', no3='$no3', no4='$no4', no5='$no5', no6='$no6',psiko='$psiko' WHERE id_rm='$_GET[idrm]'");



  $koneksi->query("UPDATE rekam_medis SET  gol_darah = '$_POST[gol_darah]', anamnesa = '$_POST[anamnesa]', diagnosis = '$_POST[diagnosis]', prognosa = '$_POST[prognosa]' , icd = '$_POST[icd]', status_perokok = '$_POST[status_perokok]' WHERE norm = '$_GET[id]' AND DATE_FORMAT(jadwal, '%Y-%m-%d') = '$_GET[tgl]';");
   

if (mysqli_affected_rows($koneksi)>0) {
  echo "
  <script>
  alert('Data berhasil diubah');
  document.location.href='index.php?halaman=rekammedisall';

   </script>

  ";

} else {

  echo "
  <script>
  alert('GAGAL MENGHAPUS DATA');
  document.location.href='index.php?halaman=rekammedisall';
  </script>

  ";

}


}

?>

     