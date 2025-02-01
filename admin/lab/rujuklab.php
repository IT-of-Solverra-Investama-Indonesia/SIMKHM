
<?php 

$username=$_SESSION['admin']['username'];
$ambil=$koneksi->query("SELECT * FROM admin  WHERE username='$username';");
$pasien=$koneksi->query("SELECT * FROM pasien JOIN rekam_medis  WHERE id_rm='$_GET[id]';");
$pecah=$pasien->fetch_assoc();


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

 

   <main>
    <div class="container">
      <div class="pagetitle">
      <h1>Rujuk Laboratorium</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active"><a href="index.php?halaman=daftarrmedis" style="color:blue;">Rujukan Lab</a></li>
          <li class="breadcrumb-item">Buat Rujukan</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

 <form class="row g-3" method="post" enctype="multipart/form-data">
   <div class="container">
          <div class="row">
            <div class="col-md-12">

            <div class="card" style="margin-top:10px">
            <div class="card-body col-md-12">
              <h5 class="card-title">Data Pasien</h5>
              <!-- Multi Columns Form -->
                <div class="col-md-12">
                  <label for="inputName5" class="form-label">Nama Pasien</label>
                  <input type="text" class="form-control" name="nama_pasien" id="inputName5" value="<?php echo $pecah['nama_pasien']?>" placeholder="Masukkan Nama Pasien">
                </div>
                <div class="col-md-12">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">NO RM Pasien</label>
                  <input type="number" class="form-control" name="norm" id="inputName5" value="<?php echo $pecah['no_rm']?>" placeholder="Masukkan Nama Pasien">
                </div>
                <div class="col-md-12" style="margin-top: 10px; margin-bottom:20px;">
                  <label for="inputName5" class="form-label">Jadwal</label>
                  <input type="datetime-local" class="form-control" id="inputName5" name="jadwal" value="<?php echo $pecah['jadwal']?>" placeholder="Masukkan Nama Pasien">
                </div>
              
                </div>
                </div>

            <div class="card">
            <div class="card-body">
              <h6 class="card-title">Data Dokter</h6>

              <!-- Multi Columns Form -->
              <div class="row">
                 <div class="col-md-6" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Nama Dokter Pengirim</label>
                  <input type="text" class="form-control" name="dokter_pengirim" value="" id="inputName5" placeholder="Masukkan Nama Dokter">
                </div>
                <div class="col-md-6" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">No Hp Dokter</label>
                  <input type="text" class="form-control" name="notelp_dokter" id="inputName5" value="" placeholder="Masukkan No Dokter">
                </div>

              <div style="margin-bottom:2px; margin-top:30px">
              <hr>
              <h6 class="card-title">Data Kesehatan</h6>
              </div>

              <!-- Multi Columns Form -->
              <div class="row">
               <div class="col-md-6">
                  <label for="inputName5" class="form-label">Nama Pemeriksaan</label>
                  <input type="text" class="form-control" name="nama_pemeriksaan" id="inputName5" value="" placeholder="Masukkan Pemeriksaan Pasien">
                </div>
                <div class="col-md-6">
                  <label for="inputName5" class="form-label">No Permintaan Rujukan</label>
                  <input type="text" class="form-control" id="inputName5" value="" name="no_permintaan" placeholder="No Rujukan">
                </div>
                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Waktu Permintaan</label>
                  <input type="datetime-local" class="form-control" id="inputName5" name="waktu_permintaan" value="" placeholder="Masukkan Nama Pasien">
                </div>
                <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Prioritas Pemeriksaan</label>
                  <select id="inputState" name="prioritas_periksa" class="form-select">
                    <option value="">Pilih</option>
                    <option value="1">1. CITO</option>
                    <option value="2">2. Non CITO</option>
                  </select>
                </div>
                <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Diagnosis/Masalah</label>
                  <input type="text" class="form-control" id="inputName5" name="diagnosis" value="" placeholder="Masukkan Diagnosis/Masalah Pasien">
                </div>
                <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Catatan Permintaan</label>
                  <input type="text" class="form-control" id="inputName5" name="ctt_permintaan" value="" placeholder="Masukkan Catatan Permintaan">
                </div>
                <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Metode Pengiriman Hasil</label>
                  <select id="inputState" name="metode_hasil" class="form-select">
                    <option value="">Pilih</option>
                    <option value="1">1. Penyerahan Langsung</option>
                    <option value="2">2. Dikirim Via Surel</option>
                  </select>
                </div>

                <div class="text-center" style="margin-top: 50px; margin-bottom: 80px;">
                  <button type="submit" name="save" class="btn btn-primary">Simpan</button>
                </div>
              </form><!-- End Multi Columns Form -->
            </div>
          </div>
        </div>
        </div>
    
      </div>
     </div>
    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


</body>

</html>


<?php  
if (isset ($_POST['save'])) {
    $norm=$_POST['norm'];
    $nama_pasien=$_POST['nama_pasien'];
    $nama_pemeriksaan=$_POST['nama_pemeriksaan'];
    $no_permintaan=$_POST['no_permintaan'];
    $waktu_permintaan=$_POST['waktu_permintaan'];
    $dokter_pengirim=$_POST['dokter_pengirim'];
    $notelp_dokter=$_POST['notelp_dokter'];
    $prioritas_periksa=$_POST['prioritas_periksa'];
    $diagnosis=$_POST['diagnosis'];
    $ctt_permintaan=$_POST['ctt_permintaan'];
    $metode_hasil=$_POST['metode_hasil'];
   
  $koneksi->query("INSERT INTO lab 

    (norm, nama_pasien, nama_pemeriksaan, no_permintaan, waktu_permintaan, dokter_pengirim, notelp_dokter, prioritas_periksa, diagnosis, ctt_permintaan, metode_hasil)

    VALUES ('$norm', '$nama_pasien', '$nama_pemeriksaan', '$no_permintaan', '$waktu_permintaan', '$dokter_pengirim', '$notelp_dokter', '$prioritas_periksa', '$diagnosis', '$ctt_permintaan', '$metode_hasil')

    ");


}


//   // $koneksi->query("INSERT INTO log_user 

//   //   (status_log, username_admin, idadmin)

//   //   VALUES ('$status_log', '$username_admin', '$idadmin')

//   //   ");

// }

?>