
<?php 

$username=$_SESSION['admin']['username'];
$ambil=$koneksi->query("SELECT * FROM admin  WHERE username='$username';");
$pasien=$koneksi->query("SELECT * FROM pasien JOIN terapi  WHERE idterapi='$_GET[id]';");
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
      <h1>Terapi</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active"><a href="index.php?halaman=daftarterapi" style="color:blue;">Terapi</a></li>
          <li class="breadcrumb-item">Buat Terapi</li>
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
                  <input type="text" class="form-control" name="no_rm" id="inputName5" value="<?php echo $pecah['no_rm']?>" placeholder="Masukkan No RM Pasien">
                </div>
                <input type="hidden" class="form-control" id="inputName5" name="id_rm" value="<?php echo $pecah['id_rm']?>">

                <div class="col-md-12">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">Tgl Lahir Pasien</label>
                  <input type="date" class="form-control" name="tgl_lahir_pasien" id="inputName5" value="<?php echo $pecah['tgl_lahir_pasien']?>" placeholder="Masukkan Tanggal Lahir Pasien">
                </div>
                <div class="col-md-12">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">TB Pasien</label>
                  <input type="text" class="form-control" name="tb_pasien" id="inputName5" value="<?php echo $pecah['tb_pasien']?>" placeholder="Hasil pengukuran tinggi badan pasien dalam satuan ukur centimeter">
                </div>
                <div class="col-md-12">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">BB Pasien</label>
                  <input type="text" class="form-control" name="bb_pasien" id="inputName5" value="<?php echo $pecah['bb_pasien']?>" placeholder="Massa tubuh pasien dalam satuan berat kilogram">
                </div>
              
                </div>
                </div>

            <div class="card">
            <div class="card-body">
              <h6 class="card-title">Tindakan</h6>

              <!-- Multi Columns Form -->
              <div class="row">
                 <div class="col-md-6" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Nama Tindakan</label>
                  <input type="text" class="form-control" name="nama_tindakan" value="<?php echo $pecah['nama_tindakan']?>" id="inputName5" placeholder="Sesuai dengan ICD 9 CM dan standar lainnya">
                </div>
                <div class="col-md-6" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Nama Petugas</label>
                  <input type="text" class="form-control" name="petugas" id="inputName5" value="<?php echo $pecah['petugas']?>" placeholder="Nama lengkap sesuai dengan kartu identitas">
                </div>
                 <div class="col-md-6" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Tanggal Pelaksanaan</label>
                  <input type="date" class="form-control" name="tgl_tindakan" id="inputName5" value="<?php echo $pecah['tgl_tindakan']?>" placeholder="Masukkan No Dokter">
                </div>
                 <div class="col-md-6" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Waktu Mulai Tindakan</label>
                  <input type="time" class="form-control" name="waktu_mulai" id="inputName5" value="<?php echo $pecah['waktu_mulai']?>" placeholder="Masukkan No Dokter">
                </div>
                 <div class="col-md-6" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Waktu Selesai Tindakan</label>
                  <input type="time" class="form-control" name="waktu_selesai" id="inputName5" value="<?php echo $pecah['waktu_selesai']?>" placeholder="Masukkan No Dokter">
                </div>
                 <div class="col-md-6" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Alat Medis</label>
                  <input type="text" class="form-control" name="alat_medis" id="inputName5" value="<?php echo $pecah['alat_medis']?>" placeholder="Peralatan medis yang digunakan untuk tindakan">
                </div>
                 <div class="col-md-6" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">BMHP</label>
                  <input type="text" class="form-control" name="bmhp" id="inputName5" value="<?php echo $pecah['bmhp']?>" placeholder="Bahan Medis Habis Pakai yang digunakan untuk tindakan">
                </div>

              <div style="margin-bottom:2px; margin-top:30px">
              <hr>
              <h6 class="card-title">Peresepan Obat</h6>
              </div>

              <!-- Multi Columns Form -->
              <div class="row">
               <div class="col-md-6">
                  <label for="inputName5" class="form-label">ID Resep</label>
                  <input type="text" class="form-control" name="id_resep" id="inputName5" value="<?php echo $pecah['id_resep']?>" placeholder="Masukkan ID Resep">
                </div>
                <div class="col-md-6">
                  <label for="inputName5" class="form-label">Nama Obat</label>
                  <input type="text" class="form-control" id="inputName5" value="<?php echo $pecah['nama_obat']?>" name="nama_obat" placeholder="Masukkan Nama Obat">
                </div>
                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">ID Obat</label>
                  <input type="text" class="form-control" id="inputName5" name="id_obat" value="<?php echo $pecah['id_obat']?>" placeholder="Masukkan ID Obat">
                </div>
                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Bentuk/Sediaan</label>
                  <input type="text" class="form-control" id="inputName5" name="bentuk_obat" value="<?php echo $pecah['bentuk_obat']?>" placeholder="Masukkan Bentuk Obat">
                </div>
                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Jumlah Obat</label>
                  <input type="number" class="form-control" id="inputName5" name="jml_obat" value="<?php echo $pecah['jml_obat']?>" placeholder="Masukkan Jumlah Obat">
                </div>

                <hr style="margin-top:30px;">

                <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Catatan Resep</label>
                  <input type="number" class="form-control" id="inputName5" name="ctt_resep" value="<?php echo $pecah['ctt_resep']?>" placeholder="Catatan tambahan mengenai pemberian obat">
                </div>
                <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Dokter Penulis Resep</label>
                  <input type="number" class="form-control" id="inputName5" name="dokter_resep" value="<?php echo $pecah['dokter_resep']?>" placeholder="Nama lengkap sesuai dengan kartu identitas">
                </div>
                <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">No Telp Dokter</label>
                  <input type="number" class="form-control" id="inputName5" name="notelp_dokter_resep" value="<?php echo $pecah['notelp_dokter_resep']?>" placeholder="Nomor kontak dokter penulis resep yang dapat dihubung">
                </div>
                <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Tgl Penulisan Resep</label>
                  <input type="date" class="form-control" id="inputName5" name="tgl_resep" value="<?php echo $pecah['tgl_resep']?>">
                </div>
                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Jam Penulisan Resep</label>
                  <input type="time" class="form-control" id="inputName5" name="jam_resep" value="<?php echo $pecah['jam_resep']?>">
                </div>
                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">TTD Penulisan Resep</label>
                  <input type="text" class="form-control" id="inputName5" name="ttd_dokter_resep" value="<?php echo $pecah['ttd_dokter_resep']?>" placeholder="Nama lengkap sesuai dengan kartu identitas">
                </div>
                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Status Resep</label>
                  <select id="inputState" name="status_resep" class="form-select">
                    <option value="<?php echo $pecah['status_resep']?>"><?php echo $pecah['status_resep']?></option>
                    <option value="Pending">Pending</option>
                    <option value="Sudah Diberikan">Sudah Diberikan</option>
                  </select>
                </div>
                <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Pengkajian Resep</label>
                  <select id="inputState" name="pengkaji_resep" class="form-select">
                    <option value="<?php echo $pecah['pengkaji_resep']?>"><?php echo $pecah['pengkaji_resep']?></option>
                    <option value="1">1. Pengkajian administrasi</option>
                    <option value="2">2. Persyaratan farmasetik</option>
                    <option value="3">3. Persyaratan klinis</option>
                  </select>
                </div>                

              <div style="margin-bottom:2px; margin-top:30px">
              <hr>
              <h6 class="card-title">Aturan Pakai</h6>
              </div>

              <!-- Multi Columns Form -->
              <div class="row">
               <div class="col-md-12">
                  <label for="inputName5" class="form-label">Metode Pemberian</label>
                  <input type="text" class="form-control" name="metode_pemberian" id="inputName5" value="<?php echo $pecah['metode_pemberian']?>" placeholder="Cara obat dimasukkan ke dalam tubuh pasien (IM, subkutan, IV, oral, suppositoria, topikal)">
                </div>
                <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Dosis Obat</label>
                  <input type="text" class="form-control" id="inputName5" value="<?php echo $pecah['dosis_obat']?>" name="dosis_obat" placeholder="Jumlah kuantitas dosis obat yang diresepkan kepada pasien">
                </div>
                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Unit</label>
                  <input type="text" class="form-control" id="inputName5" name="unit" value="<?php echo $pecah['unit']?>" placeholder="Satuan dosis obat (mg, unit, ml)">
                </div>
                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Frekuensi</label>
                  <input type="text" class="form-control" id="inputName5" name="frekuensi" value="<?php echo $pecah['frekuensi']?>" placeholder="Selang waktu pemberian obat yang diberikan dalam waktu 24 jam">
                </div>
                <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Aturan Tambahan</label>
                  <input type="number" class="form-control" id="inputName5" name="aturan_tambahan" value="<?php echo $pecah['aturan_tambahan']?>" placeholder="Jika diperlukan aturan tambahan dari dokter (sebelum makan, sesudah makan, dll)">
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
    $nama_tindakan=$_POST['nama_tindakan'];
    $petugas=$_POST['petugas'];
    $tgl_tindakan=$_POST['tgl_tindakan'];
    $waktu_mulai=$_POST['waktu_mulai'];
    $waktu_selesai=$_POST['waktu_selesai'];
    $alat_medis=$_POST['alat_medis'];
    $bmhp=$_POST['bmhp'];
    $nama_obat=$_POST['nama_obat'];
    $tgl_lahir_pasien=$_POST['tgl_lahir_pasien'];
    $tb_pasien=$_POST['tb_pasien'];
    $bb_pasien=$_POST['bb_pasien'];
    $id_resep=$_POST['id_resep'];
    $id_obat=$_POST['id_obat'];
    $bentuk_obat=$_POST['bentuk_obat'];
    $jml_obat=$_POST['jml_obat'];
    $metode_pemberian=$_POST['metode_pemberian'];
    $dosis_obat=$_POST['dosis_obat'];
    $frekuensi=$_POST['frekuensi'];
    $aturan_tambahan=$_POST['aturan_tambahan'];
    $ctt_resep=$_POST['ctt_resep'];
    $dokter_resep=$_POST['dokter_resep'];
    $notelp_dokter_resep=$_POST['notelp_dokter_resep'];
    $tgl_resep=$_POST['tgl_resep'];
    $jam_resep=$_POST['jam_resep'];
    $ttd_dokter_resep=$_POST['ttd_dokter_resep'];
    $status_resep=$_POST['status_resep'];
    $pengkaji_resep=$_POST['pengkaji_resep'];
    $id_rm=$_POST['id_rm'];


   
 $koneksi->query("UPDATE terapi SET no_rm='$no_rm', nama_pasien='$nama_pasien', nama_tindakan='$nama_tindakan', petugas='$petugas', tgl_tindakan='$tgl_tindakan', waktu_mulai='$waktu_mulai', waktu_selesai='$waktu_selesai', alat_medis='$alat_medis', bmhp='$bmhp', nama_obat='$nama_obat', tgl_lahir_pasien='$tgl_lahir_pasien', tb_pasien='$tb_pasien', bb_pasien='$bb_pasien', id_resep='$id_resep', id_obat='$id_obat',bentuk_obat='$bentuk_obat',jml_obat='$jml_obat',metode_pemberian='$metode_pemberian',dosis_obat='$dosis_obat',frekuensi='$frekuensi',aturan_tambahan='$aturan_tambahan',ctt_resep='$ctt_resep',dokter_resep='$dokter_resep',notelp_dokter_resep='$notelp_dokter_resep',tgl_resep='$tgl_resep',jam_resep='$jam_resep',ttd_dokter_resep='$ttd_dokter_resep',status_resep='$status_resep',pengkaji_resep='$pengkaji_resep',id_rm='$id_rm' WHERE idterapi='$_GET[id]'");
    
    // $koneksi->query("INSERT INTO log_user 

    // (status_log, username_admin, idadmin)

    // VALUES ('$status_log', '$username_admin', '$idadmin')

    // ");




if (mysqli_affected_rows($koneksi)>0) {
  echo "
    <script>

    alert('Data berhasil diubah');

    document.location.href='index.php?halaman=detailterapi&id=".$terapi["idterapi"]."'

    </script>

    ";
} 
}
  
?>