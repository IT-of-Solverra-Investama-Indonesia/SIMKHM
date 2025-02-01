
<?php 

$user=$_SESSION['admin']['username'];

// $ambil=$koneksi->query("SELECT * FROM admin  WHERE username='$username';");
// $ambil=$koneksi->query("SELECT * FROM kunjunganpoli WHERE id_kunjung='$id' ");
// $pecah=$ambil->fetch_assoc();

// $ambil2=$koneksi->query("SELECT * FROM daftartes GROUP BY tipe ");
// $pecah=$ambil->fetch_assoc();

 ?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>LAB</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <script rel="javascript" type="text/javascript" href="js/jquery-1.11.3.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>


 

   <main>
    <div class="container">
      <div class="pagetitle">
      <h1>Tambah Data Laboratorium</h1>
     
    </div><!-- End Page Title -->

<form class="row" method="post" enctype="multipart/form-data">
   <div class="container">
          <div class="row">
              <!-- Multi Columns Form -->
                <div class="col-md-12">
                  <label for="inputName5" class="form-label">Nama Pemeriksaan</label>
                  <input type="text" class="form-control" name="nama_tes" id="inputName5" value="" placeholder="Masukkan Nama Pemeriksaan" style="width: 1000px;" >
                </div>
                <div class="col-md-12">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">Nilai Normal</label>
                  <input type="text" class="form-control" name="indikator" id="inputName5" value="" placeholder="Masukkan Nilai Normal" style="width: 1000px;">
                </div>
                <div class="col-md-12">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">Tipe</label>
                  <select type="text" class="form-control" name="tipe" id="inputName5" value="" placeholder="Masukkan Tipe" style="width: 1000px;">
                    <option value="">Pilih</option>
                    <option value="Goldar">Gol Darah</option>
                    <option value="Darah">Darah Lengkap</option>
                    <option value="Fungsi Liver">Fungsi Liver</option>
                    <option value="Fungsi Ginjal">Fungsi Ginjal</option>
                    <option value="Kolestrol">Kolestrol</option>
                    <option value="Trigiliserida">Trigiliserida</option>
                    <option value="Widal">Widal</option>
                    <option value="Gula Darah Acak">Gula Darah Acak</option>
                    <option value="Gula Darah Puasa">Gula Darah Puasa</option>
                    <option value="Gula Darah 2JPP">Gula Darah 2JPP</option>
                    <option value="HIV">HIV</option>
                    <option value="Swab">Swab</option>
                    <option value="HBsAG">HBsAG</option>
                  </select>
                </div>
                <div class="col-md-12">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">Tipe Lain (Jika Tidak Ada Pilihan Diatas)</label>
                  <input type="text" class="form-control" name="tipe" id="inputName5" value="" placeholder="Masukkan Tipe" style="width: 1000px;">
                </div>
                <div class="col-md-12" style="margin-bottom: 20px;">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">Harga</label>
                  <input type="number" class="form-control" name="harga_lab" id="inputName5" value="" placeholder="Masukkan Harga (hanya angka, tidak memakai karakter titik/koma)" style="width: 1000px;">
                </div>

          <div class="text-center">
          <button class="btn btn-success" type="submit" name="save" id="checkBtn" style="text-align:center;">Simpan</button>
          </div>
          <br>
          <br>
       
</form><!-- End Multi Columns Form -->
     
          </div>
        </div>
        </div>
    
      </div>
     </div>
    </div>

<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
  border-height: 10px ;
}

</style>

<?php  
if (isset ($_POST['save'])) {
   
    $nama_tes=$_POST['nama_tes'];
    $indikator=$_POST['indikator'];
    $tipe=$_POST['tipe'];
    $harga_lab=$_POST['harga_lab'];


        $koneksi->query("INSERT INTO daftartes SET
            nama_tes   = '$nama_tes',
            indikator   = '$indikator',
            tipe   = '$tipe',
            harga_lab    = '$harga_lab'
            ;
        ");
       
  


  echo "<div class='alert alert-success'>Data Tersimpan</div>";

  echo "<meta http-equiv='refresh' content='1;url=index.php?halaman=daftarlabdata'>";


}


?>