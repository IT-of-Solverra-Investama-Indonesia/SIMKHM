<?php 

include 'urutan.php';

$shift = $_SESSION['shift'];
$poli=$_SESSION['admin']['username'];

$ambilrawat=$koneksi->query("SELECT * FROM registrasi_rawat WHERE idrawat='$_GET[idrawat]' ");
$pecahrawat=$ambilrawat->fetch_assoc();

$ambilpasien=$koneksi->query("SELECT * FROM pasien WHERE idpasien='$pecahrawat[id_pasien]' ");
$pecahpasien=$ambilpasien->fetch_assoc();

$dokter = $pecahrawat['dokter_rawat'] !== "" ? $pecahrawat['dokter_rawat'] : $_SESSION['dokter_rawat'];

date_default_timezone_set('Asia/Jakarta');


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
      display : visible;
      height: 0px;
      overflow: hidden;
    }
  </style>
</head>
 <body>

   <main>
    <div class="container">
      <div class="pagetitle">
      <h1>Rawat Jalan</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active"><a href="index.php" style="color:blue;">Home</a></li>
          <li class="breadcrumb-item active"><a href="index.php?halaman=daftarregistrasi" style="color:blue;">Registrasi</a></li>
          <li class="breadcrumb-item">Edit</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

      <section class="section  py-4">
        <div class="container">
          <div class="row">
            <div class="col-lg-12 col-md-12 d-flex">

            <div class="card" style="max-width: 70%; display: inline-flex; position: absolute;">
            <div class="card-body">
              <h5 class="card-title">Edit</h5>
              <form class="row g-3" method="post" enctype="multipart/form-data">

                <div class="col-md-6">
                  <label for="inputState" class="form-label">Jenis Kunjungan</label><br>
                       <input type="radio" name="jenis_kunjungan" value="Kunjungan Sakit" <?= ($pecahrawat['jenis_kunjungan'] === "Kunjungan Sakit") ? "checked" : ""; ?> disabled>
                      <label class="form-check-label" for="gridRadios1">
                        Kunjungan Sakit
                      </label>
                    <span>&nbsp;&nbsp;
                      <input type="radio" name="jenis_kunjungan" value="Kunjungan Sehat" <?= ($pecahrawat['jenis_kunjungan'] === "Kunjungan Sehat") ? "checked" : ""; ?> disabled >
                      <label class="form-check-label" for="gridRadios2">
                        Kunjungan Sehat
                      </label>
                    </span>
                </div>
                <br>
                <div class="col-md-12">
                  <label for="inputState" class="form-label">Jenis Perawatan</label>
                  <select id="pilihan" name="perawatan" class="form-select" disabled>
                    <option <?= ($pecahrawat['perawatan'] === "") ? "selected" : ""; ?> >Pilih</option>
                    <option value="Rawat Jalan" <?= ($pecahrawat['perawatan'] === "Rawat Jalan") ? "selected" : ""; ?>>Rawat Jalan</option>
                    <option value="Rawat Inap" <?= ($pecahrawat['perawatan'] === "Rawat Inap") ? "selected" : ""; ?>>Rawat Inap</option>
                  </select>
                </div>
                <!-- <div class="hidden" id="kamar">
                  <div class="row">
                    <div class="col-md-12">
                      <label for="inputState" class="form-label">Kamar / Ruangan</label> -->
                      <!-- <input type="text" class="form-control" value="" name="kamar"> -->
                      <!-- <select name="kamar" class="form-select" id="">
                        <option hidden>Pilih Kamar</option>
                        <?php
                          $getKamar = $koneksi->query("SELECT * FROM kamar LIMIT 10");
                          foreach($getKamar as $kamar){
                        ?>
                          <?php $cekKamar = $koneksi->query("SELECT COUNT(*) as jumlah FROM registrasi_rawat WHERE kamar = '$kamar[namakamar]' and status_antri != 'Pulang'")->fetch_assoc();?>
                          <?php if($cekKamar['jumlah'] == 0){?>
                            <option value="<?= $kamar['namakamar']?>"><?= $kamar['namakamar']?></option>
                          <?php }else{?>
                          <?php }?>
                        <?php }?>
                      </select>
                    </div>
                  </div>
                </div> -->
                <script>
                  document.getElementById('pilihan').addEventListener('change', function() {
                    var formAn = document.getElementById('ant');
                    if (this.value === 'Rawat Inap') {
                      // formLain.classList.remove('hidden');
                      formAn.classList.add('hidden');
                    } else {
                      // formLain.classList.add('hidden');
                      formAn.classList.remove('hidden');
                    }
                  });
                </script>
            <div>
              <br>
              <h5 class="card-title">Data Umum</h5>
            </div>
              <!-- Multi Columns Form -->
                <div class="col-md-6">
                  <label for="inputName5" class="form-label">Nama Pasien</label>
                  <input type="text" class="form-control" name="nama_pasien" id="inputName5" value="<?php echo $pecahpasien['nama_lengkap']?>" placeholder="Masukkan Nama Pasien" disabled>
                </div>
                <input type="hidden" class="form-control" name="id_pasien" id="inputName5" value="<?php echo $pecahpasien['idpasien']?>" placeholder="Masukkan Nama Pasien" disabled>
                <div class="col-md-6">
                  <label for="inputName5" class="form-label">No RM Pasien</label>
                  <input type="text" class="form-control" name="no_rm" id="inputName5" value="<?php echo $pecahpasien['no_rm']?>" placeholder="Masukkan Nama Pasien" disabled>
                </div>
                 <div class="col-md-6" id="ant">
                  <label for="inputState" class="form-label" style="color: orangered; font-weight:bold">Antrian</label>
                  <select id="antrian" name="antrian" class="form-control" disabled>
                   <?php 
		                $t=$_POST["from"]; 
		                date_default_timezone_set("asia/jakarta");
		                $tg=date('Ymd')+0;
		                $time=date('Hi')-300;
                    //var_dump($time);

		                $k=mysqli_query($koneksi, "SELECT kode, urut, ket FROM tgltab WHERE NOT EXISTS(SELECT antrian FROM registrasi_rawat WHERE registrasi_rawat.kode=tgltab.kode) AND tgl>=$tg AND jam>$time ORDER BY tgltab.no ASC");?>
                   
                   <option value="" width="40" <?= ($pecahrawat['antrian'] === "") ? "selected" : ""; ?> >Silahkan Pilih Antrian</option>  
		                <?php while($row3=mysqli_fetch_assoc($k)): ?> 
 		                <option value="<?php echo $row3['urut']; ?>" width="40" <?= ($pecahrawat['antrian'] === $row3['urut']) ? "selected" : ""; ?> ><?php echo $row3['ket']; ?> </option>  
 	                  	<?php endwhile; ?>
                  </select>
                  <!-- <?= $time?>
                  <?= $tg?> -->
                </div>
               <div class="col-md-6">
                  <label for="inputName5" class="form-label">Jadwal</label>
                  <input type="datetime" class="form-control" name="jadwal" value="<?= $pecahrawat['jadwal'] !== '' ? $pecahrawat['jadwal'] : date("Y-m-d H:i:s") ?>" placeholder="Masukkan Nama Pasien" disabled>
                </div>
                <div class="col-md-12">
                  <label for="inputState" class="form-label">Dokter</label>
                  <select id="inputState" name="dokter_rawat" class="form-select">
                    <option value="<?= $dokter ?>" hidden ><?= $dokter ?></option>
                    <?php 
                      $dokter = $koneksi->query("SELECT * FROM admin where level = 'dokter' ORDER BY namalengkap ASC"); 
                      foreach($dokter as $dok){ 
                    ?>
                      <option value="<?= $dok['namalengkap'] ?>"><?= $dok['namalengkap'] ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-md-12">
                  <label for="inputState" class="form-label">Pembayaran</label>
                  <select id="inputState" name="carabayar" class="form-select" required disabled>
                    <option <?= ($pecahrawat['carabayar'] === "") ? "selected" : ""; ?> >Pilih Pembayaran</option>
                    <option value="bpjs" <?= ($pecahrawat['carabayar'] === "bpjs") ? "selected" : ""; ?> >bpjs</option>
                    <option value="umum" <?= ($pecahrawat['carabayar'] === "umum") ? "selected" : ""; ?> >umum</option>
                  </select>
                </div>
               <div class="col-md-6">
                  <label for="inputName5" class="form-label">Tanggal Lahir</label>
                  <input type="date" class="form-control" name="tgl_lahir" value="<?php echo $pecahpasien['tgl_lahir']?>" id="inputName5" placeholder="Masukkan Tanggal Pasien" disabled>
                </div>
                <div class="col-md-6">
                  <label for="inputName5" class="form-label">Jenis Kelamin</label>
                  <!-- <input type="text" class="form-control" name="jenis_kelamin" value="<?php echo $pecahpasien['jenis_kelamin']?>" id="inputName5" placeholder="Masukkan JK Pasien"> -->
                  <select id="inputState" name="jenis_kelamin" class="form-select" disabled>
                    <?php if($pecahpasien['jenis_kelamin'] != ''){?>
                      <?php if($pecahpasien['jenis_kelamin'] == '1'){?>
                        <option selected value="<?= $pecahpasien['jenis_kelamin']?>">Laki-Laki</option>
                      <?php }elseif($pecahpasien['jenis_kelamin'] =='2'){?>
                        <option selected value="<?= $pecahpasien['jenis_kelamin']?>">Perempuan</option>
                      <?php }?>
                    <?php }else{?>
                      <option hidden>Pilih</option>
                    <?php }?>
                    <option value="1">Laki-Laki</option>
                    <option value="2">Perempuan</option>
                  </select>
                </div>
                <div class="col-12" style="margin-top:10px">
                  <label for="inputAddress5" class="form-label">Alamat</label>
                  <input type="text" name="alamat" class="form-control" id="inputAddres5s" value="<?php echo $pecahpasien['alamat']?>" placeholder="Masukkan Alamat Pasien" disabled>
                </div>
                <br>
                <br>
                <div>
                  <h5 class="card-title">Data Kontak</h5>
                </div>
                <div class="col-md-6">
                  <label for="inputCity" class="form-label">No. HP </label>
                  <input type="text" class="form-control" name="nohp" value="<?php echo $pecahpasien['nohp']?>" id="inputCity" placeholder="Masukkan No. HP Pasien" disabled>
                </div> 
                <div class="col-md-6">
                  <label for="inputCity" class="form-label">Email</label>
                  <input type="text" class="form-control" name="email" value="<?php echo $pecahpasien['email']?>" id="inputCity" placeholder="Masukkan Email Pasien" disabled>
                </div>
                <div class="col-md-6" style="margin-top:10px">
                  <label for="inputState" class="form-label">Jenis Kartu Identitas</label>
                  <select id="inputState" class="form-select" disabled>
                    <option value="<?php echo $pecahpasien['jenis_identitas']?>" hidden><?php echo $pecahpasien['jenis_identitas']?></option>
                    <option value="KTP">KTP</option>
                  </select>
                </div>
                <div class="col-md-6" style="margin-top:10px">
                  <label for="inputCity" class="form-label">No. Kartu Identitas</label>
                  <input type="text" name="no_identitas" value="<?php echo $pecahpasien['no_identitas']?>" class="form-control" id="inputCity" placeholder="Masukkan No. Identitas Pasien" disabled>
                </div>
                
                <div class="text-center" style="margin-top: 50px; margin-bottom: 80px;">
                  <button type="submit" name="edit" class="btn btn-primary">Edit</button>
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

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


</body>

</html>

<?php 

if (isset ($_POST['edit'])) 
{

$dokter_rawat=htmlspecialchars($_POST["dokter_rawat"]);
$perawatan=htmlspecialchars($_POST["perawatan"]);

if($perawatan == "Rawat Inap"){
  $koneksi->query("UPDATE igd SET dokter_rawat='$dokter_rawat' WHERE idrawat='$_GET[idrawat]'");
}else{
  $koneksi->query("UPDATE registrasi_rawat SET dokter_rawat='$dokter_rawat', petugaspoli='$poli', shift='$shift' WHERE idrawat='$_GET[idrawat]'");
}

echo "
<script>

alert('Data berhasil didaftarkan!');
document.location.href='index.php?halaman=daftarregistrasi';

</script>

";

if (mysqli_affected_rows($koneksi)>0) {
}


} 

?>