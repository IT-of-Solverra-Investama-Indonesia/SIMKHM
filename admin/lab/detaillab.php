
<?php 

$username=$_SESSION['admin']['username'];
$ambil=$koneksi->query("SELECT * FROM admin  WHERE username='$username';");
$pasien=$koneksi->query("SELECT * FROM lab WHERE id_lab='$_GET[id]';");
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
                  <input type="text" class="form-control" name="nama_pasien" id="inputName5" value="<?php echo $pecah['nama_pasien']?>" placeholder="Masukkan Nama Pasien" disabled>
                </div>
                <div class="col-md-12">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">No RM Pasien</label>
                  <input type="text" class="form-control" name="norm" id="inputName5" value="<?php echo $pecah['norm']?>" placeholder="Masukkan Nama Pasien" disabled>
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
                  <input type="text" class="form-control" name="dokter_pengirim" value="<?php echo $pecah['dokter_pengirim']?>" id="inputName5" placeholder="Masukkan Nama Dokter" disabled>
                </div>
                <div class="col-md-6" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">No Hp Dokter</label>
                  <input type="text" class="form-control" name="notelp_dokter" id="inputName5" value="<?php echo $pecah['notelp_dokter']?>" placeholder="Masukkan No Dokter" disabled>
                </div>

              <div style="margin-bottom:2px; margin-top:30px">
              <hr>
              <h6 class="card-title">Data Kesehatan</h6>
              </div>

              <!-- Multi Columns Form -->
              <div class="row">
               <div class="col-md-6">
                  <label for="inputName5" class="form-label">Nama Pemeriksaan</label>
                  <input type="text" class="form-control" name="nama_pemeriksaan" id="inputName5" value="<?php echo $pecah['nama_pemeriksaan']?>" placeholder="Masukkan Pemeriksaan Pasien" disabled>
                </div>
                <div class="col-md-6">
                  <label for="inputName5" class="form-label">No Permintaan Rujukan</label>
                  <input type="text" class="form-control" id="inputName5" value="<?php echo $pecah['no_permintaan']?>" name="no_permintaan" placeholder="No Rujukan" disabled>
                </div>
                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Waktu Permintaan</label>
                  <input type="datetime-local" class="form-control" id="inputName5" name="waktu_permintaan" value="<?php echo $pecah['waktu_permintaan']?>" placeholder="Masukkan Nama Pasien" disabled>
                </div>
                <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Prioritas Pemeriksaan</label>
                  <select id="inputState" name="prioritas_periksa" class="form-select" disabled>
                    <option value="<?php echo $pecah['prioritas_periksa']?>"><?php echo $pecah['prioritas_periksa']?></option>
                    <option value="1">1. CITO</option>
                    <option value="2">2. Non CITO</option>
                  </select>
                </div>
                <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Diagnosis/Masalah</label>
                  <input type="text" class="form-control" id="inputName5" name="diagnosis" value="<?php echo $pecah['diagnosis']?>" placeholder="Masukkan Diagnosis/Masalah Pasien" disabled>
                </div>
                <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Catatan Permintaan</label>
                  <input type="text" class="form-control" id="inputName5" name="ctt_permintaan" value="<?php echo $pecah['ctt_permintaan']?>" placeholder="Masukkan Catatan Permintaan" disabled>
                </div>
                <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Metode Pengiriman Hasil</label>
                  <select id="inputState" name="metode_hasil" class="form-select" disabled>
                    <option value="<?php echo $pecah['metode_hasil']?>"><?php echo $pecah['metode_hasil']?></option>
                    <option value="1">1. Penyerahan Langsung</option>
                    <option value="2">2. Dikirim Via Surel</option>
                  </select>
                </div>
                </div>
                </div>
                </div>
                </div>

          <div class="card" style="margin-top:10px">
            <div class="card-body col-md-12">
              <h5 class="card-title">Data Uji</h5>
              <!-- Multi Columns Form -->
                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Asal Sumber Spesimen Klinis</label>
                  <select id="inputState" name="asal_spesimen" class="form-select" disabled>
                    <option value="">Pilih</option>
                    <option value="1">1. Darah</option>
                    <option value="2">2. Urin</option>
                    <option value="3">3. Feses</option>
                    <option value="4">4. Jaringan Tubuh</option>
                    <option value="5">5. Lain-lain</option>
                  </select>
                </div>
                <div class="col-md-12" style="margin-top:10px;">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">Lokasi Pengambilan Spesimen</label>
                  <input type="text" class="form-control" name="lokasi_spesimen" id="inputName5" value="<?php echo $pecah['lokasi_spesimen']?>" placeholder="Bagian anggota tubuh dimana jaringan diambil" disabled>
                </div>
                <div class="col-md-12" style="margin-top:10px;">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">Jumlah Spesimen Klinis</label>
                  <input type="number" class="form-control" name="jml_spesimen" id="inputName5" value="<?php echo $pecah['jml_spesimen']?>" placeholder="Jumlah potongan/slice jaringan yang diambil" disabled>
                </div>
                <div class="col-md-12" style="margin-top:10px;">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">Volume Spesimen Klinis</label>
                  <input type="text" class="form-control" name="vol_spesimen" id="inputName5" value="<?php echo $pecah['vol_spesimen']?>" placeholder="Jumlah potongan/slice jaringan yang diambil (ml)" disabled>
                </div>
                <div class="col-md-12" style="margin-top:10px;">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">Cara / Metode Pengambilan Spesimen Klinis</label>
                  <input type="text" class="form-control" name="metode_spesimen" id="inputName5" value="<?php echo $pecah['metode_spesimen']?>" placeholder="Cara pengambilan jaringan dengan menggunakan metode tertentu" disabled>
                </div>
                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Waktu Pengambilan Spesimen</label>
                  <input type="datetime-local" class="form-control" id="inputName5" name="waktu_spesimen" value="<?php echo $pecah['waktu_spesimen']?>" placeholder="Masukkan Nama Pasien" disabled>
                </div>
                <div class="col-md-12" style="margin-top:10px;">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">Kondisi Spesimen Klinis</label>
                  <input type="text" class="form-control" name="kondisi_spesimen" id="inputName5" value="<?php echo $pecah['kondisi_spesimen']?>" placeholder="Kualitas fisik pada saat pengambilanspesimen/jaringan (warna, bau,kekeruhan, dst)" disabled>
                </div>
                <div class="col-md-12" style="margin-top:10px;">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">Waktu Fiksasi Spesimen Klinis</label>
                  <input type="datetime-local" class="form-control" name="waktu_fik_spesimen" id="inputName5" value="<?php echo $pecah['waktu_fik_spesimen']?>" placeholder="Waktu ketika fiksasi jaringan dilakukan" disabled>
                </div>
                <div class="col-md-12" style="margin-top:10px;">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">Cairan Fiksasi</label>
                  <input type="text" class="form-control" name="cairan_fik" id="inputName5" value="<?php echo $pecah['cairan_fik']?>" placeholder="Nama bahan cairan fiksasi yang digunakan untuk fiksasi pada jaringan" disabled>
                </div>
                  <div class="col-md-12" style="margin-top:10px;">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">Volume Cairan Fiksasi</label>
                  <input type="text" class="form-control" name="vol_fik" id="inputName5" value="<?php echo $pecah['vol_fik']?>" placeholder="Jumlah kuantitas dari cairan fiksasi yang digunakan pada spesimen" disabled>
                </div>
                <div class="col-md-12" style="margin-top:10px;">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">Nama Petugas yang Mengambil Spesimen Klinis</label>
                  <input type="text" class="form-control" name="nama_ptgs_ambil" id="inputName5" value="<?php echo $pecah['nama_ptgs_ambil']?>" placeholder="Nama Lengkap Sesuai KTP" disabled>
                </div>
                  <div class="col-md-12" style="margin-top:10px;">
                <label for="inputName5" style="margin-top: 10px;" class="form-label">Nama Petugas yang Mengantar Spesimen Klinis</label>
                  <input type="text" class="form-control" name="nama_ptgs_antar" id="inputName5" value="<?php echo $pecah['nama_ptgs_antar']?>" placeholder="Nama Lengkap Sesuai KTP" disabled>
                </div>
                  <div class="col-md-12" style="margin-top:10px;">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">Nama Petugas yang Menerima Spesimen Klinis</label>
                  <input type="text" class="form-control" name="nama_ptgs_terima" id="inputName5" value="<?php echo $pecah['nama_ptgs_terima']?>" placeholder="Nama Lengkap Sesuai KTP" disabled>
                </div>
                  <div class="col-md-12" style="margin-top:10px;">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">Nama Petugas yang Menganalisis Spesimen Klinis</label>
                  <input type="text" class="form-control" name="nama_ptgs_analis" id="inputName5" value="<?php echo $pecah['nama_ptgs_analis']?>" placeholder="Nama Lengkap Sesuai KTP" disabled>
                </div>
                  <div class="col-md-12" style="margin-top:10px;">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">Waktu Pemeriksaan/Pengujian dan Pengolahan Spesimen Klinis</label>
                  <input type="datetime-local" class="form-control" name="waktu_uji_klinis" id="inputName5" value="<?php echo $pecah['waktu_uji_klinis']?>" placeholder="Waktu Pemeriksaan/Pengujian dan Pengolahan Spesimen Klinis" disabled>
                </div>

                  <div class="col-md-12" style="margin-top:10px;">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">Nilai Hasil Pemeriksaan</label>
                  <input type="text" class="form-control" name="nilai_hasil" id="inputName5" value="<?php echo $pecah['nilai_hasil']?>" placeholder="Nilai hasil dari pemeriksaan spesimen" disabled>
                </div>
                  <div class="col-md-12" style="margin-top:10px;">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">Nilai Normal/TidakNormal</label>
                  <select class="form-control" name="nilai_normal" disabled>
                    <option value="">Pilih</option>
                    <option value="1">1. Normal</option>
                    <option value="2">2. Tidak Normal</option>
                  </select>
                </div>
                  <div class="col-md-12" style="margin-top:10px;">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">Nilai Rujukan</label>
                  <input type="text" class="form-control" name="nilai_rujuk" id="inputName5" value="<?php echo $pecah['nilai_rujuk']?>" placeholder="Nilai standar batas normal hasil pemeriksaan" disabled>
                </div>
                <div class="col-md-12" style="margin-top:10px;">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">Nama Petugas yang Menganalisis Spesimen Klinis</label>
                  <input type="text" class="form-control" name="nilai_kritis" id="inputName5" value="<?php echo $pecah['nilai_kritis']?>" placeholder="Nilai ambang batas dari nilai rujukan" disabled>
                </div>

                <div class="col-md-12" style="margin-top:10px;">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">Interpretasi Hasil Pemeriksaan</label>
                  <input type="text" class="form-control" name="inter_hasil" id="inputName5" value="<?php echo $pecah['inter_hasil']?>" placeholder="Pembacaan oleh dokter spesialis di bidang laboratorium yang terkait" disabled>
                </div>
                <div class="col-md-12" style="margin-top:10px;">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">Nama Dokter yang Memvalidasi Hasil Pemeriksaan</label>
                  <input type="text" class="form-control" name="dokter_valid" id="inputName5" value="<?php echo $pecah['dokter_valid']?>" placeholder="Nama lengkap dokter sesuai dengan kartu identitas, KTP" disabled> 
                </div>
                <div class="col-md-12" style="margin-top:10px;">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">Nama Dokter yang Menginterpretasi Hasil Pemeriksaan</label>
                  <input type="text" class="form-control" name="dokter_inter" id="inputName5" value="<?php echo $pecah['dokter_inter']?>" placeholder="Nama lengkap dokter sesuai dengan kartu identitas, KTP" disabled>
                </div>
                <div class="col-md-12" style="margin-top:10px;">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">Waktu Hasil Pemeriksaan Keluar dari Laboratorium</label>
                  <input type="datetime-local" class="form-control" name="waktu_keluar" id="inputName5" value="<?php echo $pecah['waktu_keluar']?>" placeholder="Nilai ambang batas dari nilai rujukan" disabled>
                </div>
                <div class="col-md-12" style="margin-top:10px;">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">Waktu Hasil Pemeriksaan Laboratorium Diterima Unit Pengirim</label>
                  <input type="datetime-local" class="form-control" name="waktu_terima_unit" id="inputName5" value="<?php echo $pecah['waktu_terima_unit']?>" placeholder="Nilai ambang batas dari nilai rujukan" disabled>
                </div>
                <div class="col-md-12" style="margin-top:10px;">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">Nama Fasilitas Kesehatan yang Melakukan Pemeriksaan</label>
                  <input type="text" class="form-control" name="nama_faskes_uji" id="inputName5" value="<?php echo $pecah['nama_faskes_uji']?>" placeholder="Nama Fasilitas Pelayanan Kesehatan yang melalukan pemeriksaan spesimen klinis jika spesimen klinis dirujuk" disabled>
                </div>
              
              
                </div>
                <div class="text-center" style="margin-top: 50px; margin-bottom: 80px;">
                  <!-- <button type="submit" name="save" class="btn btn-primary">Simpan</button> -->
                </div>
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
    // $norm=$_POST['norm'];
    // $nama_pasien=$_POST['nama_pasien'];
    // $nama_pemeriksaan=$_POST['nama_pemeriksaan'];
    // $no_permintaan=$_POST['no_permintaan'];
    // $waktu_permintaan=$_POST['waktu_permintaan'];
    // $dokter_pengirim=$_POST['dokter_pengirim'];
    // $notelp_dokter=$_POST['notelp_dokter'];
    // $prioritas_periksa=$_POST['prioritas_periksa'];
    // $diagnosis=$_POST['diagnosis'];
    // $ctt_permintaan=$_POST['ctt_permintaan'];
    // $metode_hasil=$_POST['metode_hasil'];
    $asal_spesimen=$_POST['asal_spesimen'];
    $lokasi_spesimen=$_POST['lokasi_spesimen'];
    $jml_spesimen=$_POST['jml_spesimen'];
    $vol_spesimen=$_POST['vol_spesimen'];
    $metode_spesimen=$_POST['metode_spesimen'];
    $waktu_spesimen=$_POST['waktu_spesimen'];
    $kondisi_spesimen=$_POST['kondisi_spesimen'];
    $waktu_fik_spesimen=$_POST['waktu_fik_spesimen'];
    $cairan_fik=$_POST['cairan_fik'];
    $vol_fik=$_POST['vol_fik'];
    $nama_ptgs_ambil=$_POST['nama_ptgs_ambil'];
    $nama_ptgs_antar=$_POST['nama_ptgs_antar'];
    $nama_ptgs_terima=$_POST['nama_ptgs_terima'];
    $nama_ptgs_analis=$_POST['nama_ptgs_analis'];
    $waktu_uji_klinis=$_POST['waktu_uji_klinis'];
    $nilai_hasil=$_POST['nilai_hasil'];
    $nilai_normal=$_POST['nilai_normal'];
    $nilai_rujuk=$_POST['nilai_rujuk'];
    $nilai_kritis=$_POST['nilai_kritis'];
    $inter_hasil=$_POST['inter_hasil'];
    $dokter_valid=$_POST['dokter_valid'];
    $dokter_inter=$_POST['dokter_inter'];
    $waktu_keluar=$_POST['waktu_keluar'];
    $waktu_terima_unit=$_POST['waktu_terima_unit'];
    $nama_faskes_uji=$_POST['nama_faskes_uji'];
   
  $koneksi->query("UPDATE lab SET asal_spesimen='$asal_spesimen', lokasi_spesimen='$lokasi_spesimen', jml_spesimen='$jml_spesimen', vol_spesimen='$vol_spesimen', metode_spesimen='$metode_spesimen', waktu_spesimen='$waktu_spesimen', kondisi_spesimen='$kondisi_spesimen', waktu_fik_spesimen='$waktu_fik_spesimen', cairan_fik='$cairan_fik', vol_fik='$vol_fik', nama_ptgs_ambil='$nama_ptgs_ambil', nama_ptgs_antar='$nama_ptgs_antar', nama_ptgs_terima='$nama_ptgs_terima', nama_ptgs_analis='$nama_ptgs_analis', waktu_uji_klinis='$waktu_uji_klinis' , nilai_hasil='$nilai_hasil', nilai_normal='$nilai_normal', nilai_rujuk='$nilai_rujuk', nilai_kritis='$nilai_kritis', inter_hasil='$inter_hasil', dokter_valid='$dokter_valid', dokter_inter='$dokter_inter', waktu_keluar='$waktu_keluar', waktu_terima_unit='$waktu_terima_unit', nama_faskes_uji='$nama_faskes_uji' WHERE id_lab='$_GET[id]' ");
    


}


//   // $koneksi->query("INSERT INTO log_user 

//   //   (status_log, username_admin, idadmin)

//   //   VALUES ('$status_log', '$username_admin', '$idadmin')

//   //   ");

// }

?>