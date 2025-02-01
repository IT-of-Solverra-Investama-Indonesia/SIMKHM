
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
      <h1>Radiologi</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active"><a href="index.php?halaman=daftarrmedis" style="color:blue;">Radiologi</a></li>
          <li class="breadcrumb-item">Buat Radiologi</li>
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
                  <input type="text" class="form-control" name="no_rm" id="inputName5" value="<?php echo $pecah['norm']?>" placeholder="Masukkan No RM Pasien">
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
                 <div class="col-md-6" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Nama Dokter Pengirim</label>
                  <input type="text" class="form-control" name="dokter_pengirim_radio" value="" id="inputName5" placeholder="Masukkan Nama Dokter">
                </div>
                <div class="col-md-6" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">No Hp Dokter</label>
                  <input type="text" class="form-control" name="notelp_dokter_radio" id="inputName5" value="" placeholder="Masukkan No Dokter">
                </div>

              <div style="margin-bottom:2px; margin-top:30px">
              <hr>
              <h6 class="card-title">Data Pengirim</h6>
              </div>

              <!-- Multi Columns Form -->
              <div class="row">
                 <div class="col-md-6" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Nama Faskes Pengirim</label>
                  <input type="text" class="form-control" name="faskes_pengirim_radio" value="" id="inputName5" placeholder="Nama Fasilitas Pelayanan Kesehatan yang mengirimkan permintaan Radiologi (rujukan dari luar)">
                </div>
                <div class="col-md-6" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Unit Pengirim</label>
                  <input type="text" class="form-control" name="unit_pengirim_radio" id="inputName5" value="" placeholder="Unit internal RS yang melakukan pengiriman spesimen">
                </div>

              <div style="margin-bottom:2px; margin-top:30px">
              <hr>
              <h6 class="card-title">Data Kesehatan</h6>
              </div>

              <!-- Multi Columns Form -->
              <div class="row">
               <div class="col-md-6">
                  <label for="inputName5" class="form-label">Nama Pemeriksaan</label>
                  <input type="text" class="form-control" name="nama_pemeriksaan_radio" id="inputName5" value="" placeholder="Masukkan Pemeriksaan Pasien">
                </div>
                <div class="col-md-6">
                  <label for="inputName5" class="form-label">No Permintaan Rujukan</label>
                  <input type="text" class="form-control" id="inputName5" value="" name="no_permintaan_radio" placeholder="No Radiologi">
                </div>
                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Waktu Permintaan</label>
                  <input type="datetime-local" class="form-control" id="inputName5" name="waktu_permintaan_radio" value="" placeholder="Masukkan Nama Pasien">
                </div>

                <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Jenis Pemeriksaan</label>
                  <select id="inputState" name="jenis_pemeriksaan_radio" class="form-select">
                    <option value="">Pilih</option>
                    <option value="1. Cranium">1. Cranium</option>
                    <option value="2. Gigi Geligi">2. Gigi Geligi</option>
                    <option value="3. Vertebra">3. Vertebra</option>
                    <option value="4. Badan">4. Badan</option>
                    <option value="5. Ekstremitas atas">5. Ekstremitas atas</option>
                    <option value="6. Ekstremitas Bawah">6. Ekstremitas Bawah</option>
                    <option value="7. Kontras Saluran Cerna">7. Kontras Saluran Cerna</option>
                    <option value="8. Kontras Saluran Kencing">8. Kontras Saluran Kencing</option>
                  </select>
                </div>
                <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Prioritas Pemeriksaan</label>
                  <select id="inputState" name="prioritas_periksa_radio" class="form-select">
                    <option value="">Pilih</option>
                    <option value="1">1. CITO</option>
                    <option value="2">2. Non CITO</option>
                  </select>
                </div>
                <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Diagnosis/Masalah</label>
                  <input type="text" class="form-control" id="inputName5" name="diagnosis_radio" value="" placeholder="Masukkan Diagnosis/Masalah Pasien">
                </div>
                <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Catatan Permintaan</label>
                  <input type="text" class="form-control" id="inputName5" name="ctt_permintaan_radio" value="" placeholder="Masukkan Catatan Permintaan">
                </div>
                <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Status Alergi Pasien terhadap Bahan Kontras</label>
                  <select id="inputState" name="status_alergi_radio" class="form-select">
                    <option value="">Pilih</option>
                    <option value="Ya">Ya</option>
                    <option value="Tidak">Tidak</option>
                  </select>
                </div>
                <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Status Kehamilan</label>
                  <select id="inputState" name="status_kehamilan" class="form-select">
                    <option value="">Pilih</option>
                    <option value="1">1. Tidak Hamil</option>
                    <option value="2">2. Hamil</option>
                  </select>
                </div>
                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Tanggal dan Waktu Pemeriksaan</label>
                  <input type="datetime-local" class="form-control" id="inputName5" name="tgl_periksa_radio" value="" placeholder="Masukkan Catatan Permintaan">
                </div>
                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Jenis Bahan</label>
                  <input type="text" class="form-control" id="inputName5" name="jenis_bahan" value="" placeholder="Nama bahan kontras yang digunakan untuk pemeriksaan radiologi">
                </div>
                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Foto Pemeriksaan</label>
                  <input type="file" class="form-control" id="inputName5" name="foto_periksa_radio" value="" placeholder="Masukkan Catatan Permintaan">
                </div>
                <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Metode Pengiriman Hasil</label>
                  <select id="inputState" name="metode_hasil_radio" class="form-select">
                    <option value="">Pilih</option>
                    <option value="1">1. Penyerahan Langsung</option>
                    <option value="2">2. Dikirim Via Surel</option>
                  </select>
                </div>
                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Nama Dokter Interpretasi</label>
                  <input type="text" class="form-control" id="inputName5" name="dokter_inter" value="" placeholder="Nama Dokter yang Menginterpretasi Hasil Pemeriksaan">
                </div>
                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Interpretasi Radiologi</label>
                  <input type="text" class="form-control" id="inputName5" name="inter_radio" value="" placeholder="Pembacaan dari hasil pemindaian radiologi oleh dokter spesialis radiologi yang terdiri dari deskripsi dan konklusi">
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
    $no_rm=$_POST['no_rm'];
    $nama_pasien=$_POST['nama_pasien'];
    $nama_pemeriksaan_radio=$_POST['nama_pemeriksaan_radio'];
    $no_permintaan_radio=$_POST['no_permintaan_radio'];
    $waktu_permintaan_radio=$_POST['waktu_permintaan_radio'];
    $dokter_pengirim_radio=$_POST['dokter_pengirim_radio'];
    $notelp_dokter_radio=$_POST['notelp_dokter_radio'];
    $prioritas_periksa_radio=$_POST['prioritas_periksa_radio'];
    $diagnosis_radio=$_POST['diagnosis_radio'];
    $ctt_permintaan_radio=$_POST['ctt_permintaan_radio'];
    $metode_hasil_radio=$_POST['metode_hasil_radio'];

    $jenis_pemeriksaan_radio=$_POST['jenis_pemeriksaan_radio'];
    $faskes_pengirim_radio=$_POST['faskes_pengirim_radio'];
    $unit_pengirim_radio=$_POST['unit_pengirim_radio'];
    $status_alergi_radio=$_POST['status_alergi_radio'];
    $status_kehamilan=$_POST['status_kehamilan'];
    $tgl_periksa_radio=$_POST['tgl_periksa_radio'];
    $jenis_bahan=$_POST['jenis_bahan'];
    $dokter_inter=$_POST['dokter_inter'];
    $inter_radio=$_POST['inter_radio'];

  $foto_periksa_radio=$_FILES['foto_periksa_radio']['name'];

  $lokasi=$_FILES['foto_periksa_radio']['tmp_name'];

  move_uploaded_file($lokasi, '../radiologi/foto/'.$foto_periksa_radio);
   
  $koneksi->query("INSERT INTO radiologi 

    (no_rm, nama_pasien, nama_pemeriksaan_radio, no_permintaan_radio, waktu_permintaan_radio, dokter_pengirim_radio, notelp_dokter_radio, prioritas_periksa_radio, diagnosis_radio, ctt_permintaan_radio, metode_hasil_radio, jenis_pemeriksaan_radio, faskes_pengirim_radio, unit_pengirim_radio, status_alergi_radio, status_kehamilan, tgl_periksa_radio, jenis_bahan, foto_periksa_radio, dokter_inter, inter_radio)

    VALUES ('$no_rm', '$nama_pasien', '$nama_pemeriksaan_radio', '$no_permintaan_radio', '$waktu_permintaan_radio', '$dokter_pengirim_radio', '$notelp_dokter_radio', '$prioritas_periksa_radio', '$diagnosis_radio', '$ctt_permintaan_radio', '$metode_hasil_radio', '$jenis_pemeriksaan_radio', '$faskes_pengirim_radio', '$unit_pengirim_radio', '$status_alergi_radio', '$status_kehamilan', '$tgl_periksa_radio', '$jenis_bahan', '$foto_periksa_radio', '$dokter_inter', '$inter_radio')

    ");

  if (mysqli_affected_rows($koneksi)>0) {
  echo "
  <script>
  alert('Data berhasil ditambah');
  document.location.href='index.php?halaman=daftarradio;
  </script>

  ";

} else {

  echo "
  <script>
  alert('GAGAL MENGHAPUS DATA');
  document.location.href='index.php?halaman=daftarradio;
  </script>

  ";

}


}


//   // $koneksi->query("INSERT INTO log_user 

//   //   (status_log, username_admin, idadmin)

//   //   VALUES ('$status_log', '$username_admin', '$idadmin')

//   //   ");

// }

?>