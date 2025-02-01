
<?php 

$username=$_SESSION['admin']['username'];
$ambil=$koneksi->query("SELECT * FROM admin  WHERE username='$username';");
$pasien2=$koneksi->query("SELECT * FROM pasien  WHERE idpasien='$_GET[id]' ")->fetch_assoc();
$pasien=$koneksi->query("SELECT * FROM pasien INNER JOIN kajian_awal WHERE idpasien='$_GET[id]' AND no_rm = norm;");
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
  <style>
    .hidden{
      display: hidden;
      max-height: 1px;
      overflow: hidden;
    }
  </style>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <script rel="javascript" type="text/javascript" href="js/jquery-1.11.3.min.js"></script>
</head>
<body>

 

   <main>
    <div class="container">
      <div class="pagetitle">
      <h1>IGD</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active"><a href="index.php?halaman=daftarterapi" style="color:blue;">IGD</a></li>
          <li class="breadcrumb-item">Buat IGD</li>
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
                  <input type="text" class="form-control" name="nama_pasien" id="inputName5" value="<?php echo $pasien2['nama_lengkap']?>" placeholder="Masukkan Nama Pasien">
                </div>
                <div class="col-md-12">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">NO RM Pasien</label>
                  <input type="text" class="form-control" name="no_rm" id="inputName5" value="<?php echo $pasien2['no_rm']?>" placeholder="Masukkan No RM Pasien">
                </div>
                <!-- <input type="hidden" class="form-control" id="inputName5" name="id_rm" value="<?php echo $pecah['id_rm']?>"> -->
              
                </div>
                </div>

            <div class="card">
            <div class="card-body">
              <h6 class="card-title">Formulir Triase dan Gawat Darurat</h6>

              <!-- Multi Columns Form -->
              <div class="row">
                <?php 
                date_default_timezone_set('Asia/Jakarta');
                ?>
                 <div class="col-md-6" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Tanggal Masuk</label>
                  <input type="datetime" class="form-control" name="tgl_masuk"  id="inputName5" placeholder="Sesuai dengan ICD 9 CM dan standar lainnya" value="<?= date("Y-m-d") ?>">
                </div>
                <div class="col-md-6" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Jam Masuk</label>
                  <input type="time" class="form-control" name="jam_masuk" id="inputName5" value="<?= date("H:i:s")?>" placeholder="Nama lengkap sesuai dengan kartu identitas">
                </div>
                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Sarana Transportasi Kedatangan </label>
                  <select id="inputState" name="transportasi" class="form-select">
                    <option value="" hidden>Pilih</option>
                    <option value="Ambulans">1. Ambulans</option>
                    <option value="Mobil">2. Mobil</option>
                    <option value="Motor">3. Motor</option>
                    <option value="Lain-lain">4. Lain-lain</option>
                  </select>
                </div>    
                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Surat Pengantar Rujukan </label>
                  <select id="inputState" name="surat_pengantar" class="form-select">
                    <option value="" hidden>Pilih</option>
                    <option value="Ada">Ada</option>
                    <option value="Tidak Ada">Tidak Ada</option>
                  </select>
                </div>  
                <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Kondisi Pasien Tiba </label>
                  <select id="inputState" name="kondisi_tiba" class="form-select">
                    <option value="" hidden>Pilih</option>
                    <!-- <option value="1">Resusitasi</option> -->
                    <option value="2">Emergency</option>
                    <option value="3">Urgent</option>
                    <!-- <option value="4">Less Urgent</option> -->
                    <option value="5">Non Urgent</option>
                    <option value="6">Death on Arrival</option>
                  </select>
                </div>  

              <div style="margin-bottom:2px; margin-top:30px">
              <hr>
              <h6 class="card-title">Identitas Pengantar Pasien</h6>
              </div>

              <!-- Multi Columns Form -->
              <div class="row">
               <div class="col-md-6">
                  <label for="inputName5" class="form-label">Nama Pengantar</label>
                  <input type="text" class="form-control" name="nama_pengantar" id="inputName5" value="" placeholder="Masukkan Nama Pengantar">
                </div>
                <div class="col-md-6">
                  <label for="inputName5" class="form-label">Nomor Telepon Seluler Penanggung Jawab</label>
                  <input type="text" class="form-control" id="inputName5" value="" name="notelp_pengantar" placeholder="Masukkan No Telepon">
                </div>
           
              <div style="margin-bottom:2px; margin-top:30px">
              <hr>
              <h6 class="card-title">Asesmen Awal IGD</h6>
              </div>

              <!-- Multi Columns Form -->
              <div class="row">
                <div class="col-md-12" style="margin-top:5px;">
                  <label for="inputName5" class="form-label">Asesmen Nyeri </label>
                  <select id="pilihan" name="asesmen_nyeri" class="form-select">
                    <option value="">Pilih</option>
                    <option value="Ada">Ada</option>
                    <option value="Tidak Ada">Tidak Ada</option>
                  </select>
                </div>  
                <div class="col-md-12" style="margin-top:5px;">
                  <label for="inputName5" class="form-label">Keluhan </label>
                 <input type="text" class="form-control" name="keluhan">
                </div>  
                <div class="col-md-12" style="margin-top:5px;">
                  <label for="inputName5" class="form-label">Riwayat Penyakit Sebelumnya </label>
                 <input type="text" class="form-control" name="riw_penyakit" value="<?php echo $pecah['riwayat_penyakit']?>">
                </div>  
                <div class="col-md-12" style="margin-top:5px;">
                  <label for="inputName5" class="form-label">Riwayat Alergi </label>
                 <input type="text" class="form-control" name="riw_alergi" value="<?php echo $pecah['riwayat_alergi']?>">
                </div>  
                <div class="hidden" id="nyr">
                  <div class="row">
                    <div class="col-md-12 mt-3">
                      <label for="" class="form-label">Skala Nyeri (dari 1 -10)</label>
                      <input type="number" class="form-control" value="0" name="skala_nyeri" id="" max="10">
                    </div>
                    <div class="col-md-6" style="margin-top:20px;">
                      <label for="inputName5" class="form-label">Lokasi Nyeri</label>
                      <input type="text" class="form-control" id="inputName5" value="" name="lokasi_nyeri" placeholder="Lokasi Nyeri">
                    </div>
                     <div class="col-md-6" style="margin-top:20px;">
                      <label for="inputName5" class="form-label">Penyebab Nyeri</label>
                      <input type="text" class="form-control" id="inputName5" name="penyebab_nyeri" value="" placeholder="Penyebab Nyeri">
                    </div>
                     <div class="col-md-6" style="margin-top:20px;">
                      <label for="inputName5" class="form-label">Durasi Nyeri</label>
                      <input type="text" class="form-control" id="inputName5" name="durasi_nyeri" value="" placeholder="Durasi Nyeri">
                    </div>
                    <div class="col-md-6" style="margin-top:20px;">
                      <label for="inputName5" class="form-label">Frekuensi Nyeri</label>
                      <input type="number" class="form-control" id="inputName5" name="frekuensi_nyeri" value="" placeholder="Frekuensi Nyeri">
                    </div>
                  </div>
                </div>
                <script>
                  document.getElementById('pilihan').addEventListener('change', function() {
                    var formLain = document.getElementById('nyr');
                    if (this.value === 'Ada') {
                      formLain.classList.remove('hidden');
                    } else {
                      formLain.classList.add('hidden');
                    }
                  });
                </script>
                <div class="col-md-12 mt-2">
                    <label for="">Resiko Jatuh</label>
                    <input type="number" class="form-control" id="inputName5" name="resiko_jatuh" value="" placeholder="Kajian resiko Jatuh">
                </div>
                <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Gambar anatomi tubuh</label>
                  <input type="file" class="form-control" id="inputName5" name="gambar_anatomi" value="" placeholder="Frekuensi Nyeri">
                </div>
                <div class="col-md-6" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Risiko Luka Decubitus</label>
                  <select id="inputState" name="resiko_decubitus" class="form-select">
                    <option value="">Pilih</option>
                    <option value="Ada">Ya</option>
                    <option value="Tidak">Tidak</option>
                  </select>
                </div>
                <div class="col-md-6" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Penurunan BB dalam waktu 6 bulan terakhir</label>
                  <select id="inputState" name="penurunan_bb" class="form-select">
                    <option value="">Pilih</option>
                    <option value="Ada">Ya</option>
                    <option value="Tidak">Tidak</option>
                  </select>
                </div>
                <div class="col-md-6" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Penurunan asupan makanan</label>
                  <select id="inputState" name="penurunan_asupan" class="form-select">
                    <option value="">Pilih</option>
                    <option value="Ada">Ya</option>
                    <option value="Tidak">Tidak</option>
                  </select>
                </div>
                <div class="col-md-6" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Gejala gastrointestinal (mual, muntah, diare, anorexia)</label>
                  <select id="inputState" name="gejala_gastro" class="form-select">
                    <option value="">Pilih</option>
                    <option value="Ada">Ya</option>
                    <option value="Tidak">Tidak</option>
                  </select>
                </div>
                <div class="col-md-6" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Faktor pemberat (komorbid)</label>
                  <select id="inputState" name="faktor_pemberat" class="form-select">
                    <option value="">Pilih</option>
                    <option value="Ada">Ya</option>
                    <option value="Tidak">Tidak</option>
                  </select>
                </div>
                <div class="col-md-6" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Penurunan kapasitas fungsional</label>
                  <select id="inputState" name="penurunan_fungsional" class="form-select">
                    <option value="">Pilih</option>
                    <option value="Ada">Ya</option>
                    <option value="Tidak">Tidak</option>
                  </select>
                </div>

                <div class="col-md-12" style="margin-top:5px;">
                  <label for="inputName5" class="form-label">Pengkajian Psikologis </label>
                 <select name="psiko" id="" class="form-control">
                  <option value="<?= $pecah['psiko'] ?>"><?= $pecah['psiko'] ?></option>
                  <option value="Tenang">Tenang</option>
                  <option value="Cemas">Cemas</option>
                  <option value="Sedih">Sedih</option>
                  <option value="Takut thd lingkungan">Takut thd lingkungan</option>
                  <option value="Marah">Marah</option>
                 </select>
                </div>  

                <div class="col-md-12" style="margin-top:5px;">
                  <label for="inputName5" class="form-label">Pengkajian Sosial </label>
                 <select name="sosial" id="" class="form-control">
                  <option value="<?= $pecah['status_tinggal'] ?>"><?= $pecah['status_tinggal'] ?></option>
                  <option value="Sendiri">Sendiri</option>
                  <option value="Kontrak">Kontrak</option>
                  <option value="Rumah Ortu">Rumah Ortu</option>
                  <option value="Lainnya">Lainnya</option>
                 </select>
                </div>  

                <div class="col-md-12" style="margin-top:5px;">
                  <label for="inputName5" class="form-label">Bantuan Yang Dibutuhkan Pasien Dirumah </label>
                 <select name="bantuan" id="" class="form-control">
                  <option value="Makan/Minum">Makan/Minum</option>
                  <option value="BAB">BAB</option>
                  <option value="BAK">BAK</option>
                  <option value="Perawatan Luka">Perawatan Luka</option>
                  <option value="Pemberian Obat">Pemberian Obat</option>
                  <option value="Mobilisasi">Mobilisasi</option>
                  <option value="Lainnya">Lainnya</option>
                 </select>
                </div>  

                <div class="col-md-12" style="margin-top:5px;">
                  <label for="inputName5" class="form-label">Pemeriksaan Penunjang </label>
                <input type="text" name="penunjang" id="" class="form-control">
                </div>  
                <div class="col-md-12" style="margin-top:5px;">
                  <label for="inputName5" class="form-label">Diagnosa Kerja </label>
                <input type="text" name="dkerja" id="" class="form-control">
                </div>  
                <div class="col-md-12" style="margin-top:5px;">
                  <label for="inputName5" class="form-label">Diagnosa Banding </label>
                <input type="text" name="dbanding" id="" class="form-control">
                </div>  
                
                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Rencana Pemulangan Pasien</label>
                  <select id="inputState" name="rencana_pemulangan" class="form-select">
                    <option value="">Pilih</option>
                    <option value="Pasien lansia">1.Pasien lansia</option>
                    <option value="Gangguan anggota gerak">2. Gangguan anggota gerak</option>
                    <option value="Pasien dengan perawatan berkelanjutan atau panjang">3.Pasien dengan perawatan berkelanjutan atau panjang</option>
                    <option value="Memerlukan bantuan dalam aktivitas sehari-hari">4.Memerlukan bantuan dalam aktivitas sehari-hari</option>
                    <option value="Tidak masuk kriteria">5.Tidak masuk kriteria</option>
                  </select>
                </div>
                 <!-- <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Rencana Rawat</label>
                  <input type="text" class="form-control" id="inputName5" name="rencana_rawat" value="" placeholder="Rencana Perawatan">
                </div>
                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Instruksi Medik dan Keperawatan</label>
                  <input type="text" class="form-control" id="inputName5" name="intruksi_medik" value="" placeholder="Intruksi Medis">
                </div> -->

                <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Tindak Lanjut</label>
                  <select id="pilRujuk"  name="tindak" class="form-select">
                    <option value="">Pilih</option>
                    <option value="Pulang">1.Pulang</option>
                    <option value="Pulang Paksa">2. Pulang Paksa</option>
                    <option value="Rawat">3.Rawat</option>
                    <option value="Meninggal">4.Meninggal</option>
                    <option value="Rujuk">5.Rujuk</option>
                  </select>
                </div>
                <div class="hidden mt-2" id="rjk">
                  <div class="row">
                    <div class="col-md-12">
                      <input type="text" placeholder="Tujuan Rujukan" class="form-control" name="tindak_rujuk">
                    </div>
                  </div>
                </div>
                <script>
                  document.getElementById('pilRujuk').addEventListener('change', function() {
                    var formLain = document.getElementById('rjk');
                    if (this.value === 'Rujuk') {
                      formLain.classList.remove('hidden');
                    } else {
                      formLain.classList.add('hidden');
                    }
                  });
                </script>


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
    $tgl_masuk=$_POST['tgl_masuk'];
    $jam_masuk=$_POST['jam_masuk'];
    $transportasi=$_POST['transportasi'];
    $surat_pengantar=$_POST['surat_pengantar'];
    $kondisi_tiba=$_POST['kondisi_tiba'];
    $nama_pengantar=$_POST['nama_pengantar'];
    $notelp_pengantar=$_POST['notelp_pengantar'];
    $asesmen_nyeri=$_POST['asesmen_nyeri'];
    // $skala_nyeri=$_POST['skala_nyeri'];
    $lokasi_nyeri=$_POST['lokasi_nyeri'];
    $penyebab_nyeri=$_POST['penyebab_nyeri'];
    $durasi_nyeri=$_POST['durasi_nyeri'];
    $frekuensi_nyeri=$_POST['frekuensi_nyeri'];
    // $kajian_jatuh=$_POST['kajian_jatuh'];
    // $gambar_anatomi=$_POST['gambar_anatomi'];
    // $tingkat_kesadaran=$_POST['tingkat_kesadaran'];
    $resiko_decubitus=$_POST['resiko_decubitus'];
    $penurunan_bb=$_POST['penurunan_bb'];
    $penurunan_asupan=$_POST['penurunan_asupan'];
    $gejala_gastro=$_POST['gejala_gastro'];
    $faktor_pemberat=$_POST['faktor_pemberat'];
    $penurunan_fungsional=$_POST['penurunan_fungsional'];
    $rencana_pemulangan=$_POST['rencana_pemulangan'];
    $rencana_rawat=$_POST['rencana_rawat'];
    // $idrm=$_POST['idrm'];

    $gambar_anatomi=$_FILES['gambar_anatomi']['name'];

    $lokasi=$_FILES['gambar_anatomi']['tmp_name'];

    move_uploaded_file($lokasi, '../igd/foto/'.$gambar_anatomi);

   
  $koneksi->query("INSERT INTO igd 

    (no_rm, nama_pasien, tgl_masuk, jam_masuk, transportasi, surat_pengantar, kondisi_tiba, nama_pengantar, notelp_pengantar, asesmen_nyeri, lokasi_nyeri, penyebab_nyeri, durasi_nyeri, frekuensi_nyeri, gambar_anatomi, resiko_decubitus, penurunan_bb, penurunan_asupan, gejala_gastro, faktor_pemberat, penurunan_fungsional, rencana_pemulangan, rencana_rawat, psiko, sosial, bantuan, penunjang, dkerja, dbanding, tindak, skala_nyeri, tindak_rujuk, keluhan, riw_penyakit, riw_alergi, perawat)

    VALUES ('$no_rm', '$nama_pasien', '$tgl_masuk', '$jam_masuk', '$transportasi', '$surat_pengantar', '$kondisi_tiba', '$nama_pengantar', '$notelp_pengantar', '$asesmen_nyeri', '$lokasi_nyeri', '$penyebab_nyeri', '$durasi_nyeri', '$frekuensi_nyeri', '$gambar_anatomi', '$resiko_decubitus', '$penurunan_bb', '$penurunan_asupan', '$gejala_gastro', '$faktor_pemberat', '$penurunan_fungsional', '$rencana_pemulangan', '$rencana_rawat', '$_POST[psiko]', '$_POST[sosial]', '$_POST[bantuan]', '$_POST[penunjang]', '$_POST[dkerja]', '$_POST[dbanding]', '$_POST[tindak]', '$_POST[skala_nyeri]', '$_POST[tindak_rujuk]', '$_POST[keluhan]', '$_POST[riw_penyakit]', '$_POST[riw_alergi]', '$_SESSION[admin][nama_lengkap]')

    ");

  if (mysqli_affected_rows($koneksi)>0) {
  echo "
  <script>
  alert('Data berhasil ditambah');
  document.location.href='index.php?halaman=daftarigd;

  </script>

  ";

} else {

  echo "
  <script>
  alert('GAGAL MENGHAPUS DATA');
  document.location.href='index.php?halaman=daftarigd;
  
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