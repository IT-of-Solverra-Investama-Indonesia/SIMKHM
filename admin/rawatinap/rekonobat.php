
<?php 
$date= date("Y-m-d");
date_default_timezone_set('Asia/Jakarta');

$username=$_SESSION['admin']['username'];
$petugas=$_SESSION['admin']['namalengkap'];
$ambil=$koneksi->query("SELECT * FROM admin  WHERE username='$username';");

  $pasien=$koneksi->query("SELECT * FROM pasien  WHERE no_rm='$_GET[id]';")->fetch_assoc();
  
  $jadwal=$koneksi->query("SELECT * FROM registrasi_rawat  WHERE no_rm='$_GET[id]' AND date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' ")->fetch_assoc();


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
  <!-- Select2 CSS --> 
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" /> 

  <!-- jQuery --> 
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 

  <!-- Select2 JS --> 
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

 

   <main>
    <div class="container">
      <div class="pagetitle">
      <h1>REKONSILIASI OBAT</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active"><a href="index.php?halaman=daftarrmedis" style="color:blue;">Rekam Medis</a></li>
          <li class="breadcrumb-item">Rekonsiliasi Obat</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
<!-- <?= $start ?> -->
 <form class="row g-3" method="post" enctype="multipart/form-data">
   <div class="container">
          <div class="row">
            <div class="col-md-12">

            <div class="card" style="margin-top:10px">
            <div class="card-body col-md-12">
              <h5 class="card-title">Data Pasien</h5>
              <!-- Multi Columns Form -->
          <div class="row">

                <div class="col-md-6">
                  <label for="inputName5" class="form-label">Nama Pasien</label>
                  <input type="text" class="form-control" name="pasien" id="inputName5" value="<?php echo $pasien['nama_lengkap']?>" placeholder="Masukkan Nama Pasien" readonly>
                </div>
                <div class="col-md-6" style="margin-bottom:20px;">
                  <label for="inputName5" class="form-label">No RM</label>
                  <input type="text" class="form-control" id="inputName5" name="jadwal" value="<?php echo $pasien['no_rm']?>" placeholder="Masukkan Nama Pasien" readonly>
                </div>
                <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                  <label for="inputName5" class="form-label">Tanggal Lahir</label>
                  <input type="text" class="form-control" id="inputName5" name="jadwal" value="<?php echo date("d-m-Y", strtotime($pasien['tgl_lahir']))?>" placeholder="Masukkan Nama Pasien" readonly>
                </div>
                <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                  <label for="inputName5" class="form-label">Alamat</label>
                  <input type="text" class="form-control" id="inputName5" name="jadwal" value="<?php echo $pasien['alamat']?>" placeholder="Masukkan Nama Pasien" readonly>
                </div>
                <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                  <label for="inputName5" class="form-label">Ruangan</label>
                  <input type="text" class="form-control" id="inputName5" name="kamar" value="<?php echo $jadwal['kamar']?>" placeholder="Masukkan Nama Pasien">
                </div>
                <?php if($pasien["jenis_kelamin"] == 1){ ?>
                <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                  <label for="inputName5" class="form-label">Jenis Kelamin</label>
                  <input type="text" class="form-control" id="inputName5" name="jadwal" value="Laki-laki" placeholder="Masukkan Nama Pasien" readonly>
                </div>
                <?php }else{ ?>
                    <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                  <label for="inputName5" class="form-label">Jenis Kelamin</label>
                  <input type="text" class="form-control" id="inputName5" name="jadwal" value="Perempuan" placeholder="Masukkan Nama Pasien" readonly>
                </div>
                <?php } ?>
              
                </div>
                </div>
                </div>

         
            <div class="card" >
            <div class="card-body">
    

            <div style="margin-bottom:1px; margin-top:30px">
                
                <h6 class="card-title">A. Daftar Obat Yang Menimbulkan Alergi </h6>
            </div>

              <!-- Multi Columns Form -->
              <div class="row">
               <div class="col-md-12">
                  <label for="inputName5" class="form-label">Tgl & Jam</label>
                  <input type="datetime" class="form-control" id="inputName5" name="tgl" value="<?= date("Y-m-d H:i:s")?>">
                </div>
               

                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Nama Obat</label>
                  <select name="nama_obat1" id="" class="form-select">
                      <option value="" hidden>Pilih Obat</option>
                      <?php
                        $getObat = $koneksi->query("SELECT * FROM apotek ORDER BY nama_obat ASC");
                        foreach ($getObat as $data) {
                      ?>
                        <option value="<?= $data['nama_obat']?>"><?= $data['nama_obat']?></option>
                      <?php }?>
                  </select>
                </div>

                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Reaksi Alergi</label>
                  <input type="text" name="reaksi_alergi" class="form-control">
                </div>
        </div>
        </div>
        </div>

        <div class="card" >
            <div class="card-body">
    

            <div style="margin-bottom:1px; margin-top:30px">
                
                <h6 class="card-title">B. Daftar Obat Yang Dibawah Dari Rumah </h6>
            </div>

              <!-- Multi Columns Form -->
              <div class="row">

                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Nama Obat</label>
                  <select name="nama_obat2" id="" class="form-select">
                      <?php
                        $getObat = $koneksi->query("SELECT * FROM apotek ORDER BY nama_obat ASC");
                        foreach ($getObat as $data) {
                      ?>
                        <option value="<?= $data['nama_obat']?>"><?= $data['nama_obat']?></option>
                      <?php }?>
                  </select>
                </div>

                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Dosis/Frekuensi</label>
                  <input type="text" name="dosis" class="form-control">
                </div>
                
                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Lama Pakai</label>
                  <input type="text" name="lama" class="form-control">
                </div>
                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Waktu Terakhir Pakai</label>
                  <input type="text" name="terakhir" class="form-control" value="<?= date('Y-m-d H:i:s') ?>">
                </div>
                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Alasan Pemakaian</label>
                  <input type="text" name="alasan" class="form-control">
                </div>
                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Kelanjutan</label>
                  <select name="kelanjutan" id="" class="form-control">
                    <option value="Ya">Ya</option>
                    <option value="Tidak">Tidak</option>
                  </select>
                </div>
                <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Apoteker</label>
                  <input name="apoteker" id="" class="form-control" value="<?php echo $petugas ?>"></input>
                </div>

                <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputState" class="form-label">Dokter</label>
                  <select id="inputState" name="dokter" class="form-select">
                    <option hidden>Pilih Dokter</option>
                    <option value="dr. Ainul Indra Jaya, MMRS">dr. Ainul Indra Jaya, MMRS</option>
                    <option value="dr. Ummul Azizah">dr. Ummul Azizah</option>
                    <option value="dr. Ongky Dyah A">dr. Ongky Dyah A</option>
                    <option value="dr. Indiana Maharani W">dr. Indiana Maharani W</option>
                    <option value="dr. Mohammad Fathur Rozi">dr. Mohammad Fathur Rozi</option>
                    <option value="dr. Muhammad  Ardhy F">dr. Muhammad  Ardhy F</option>
                    <option value="dr. Eki Yazid A">dr. Eki Yazid A</option>
                    <option value="dr. Imaylani Sriniti">dr. Imaylani Sriniti</option>
                    <option value="dr. Safira Idofia J">dr. Safira Idofia J</option>
                    <option value="dr. Victor">dr. Victor</option>
                    <option value="dr. Joy">dr. Joy</option>
                    <option value="dr. Ari">dr. Ari</option>
                    <option value="dr. Yusika">dr. Yusika</option>
                    <option value="dr. Lisa">dr. Lisa</option>
                    <option value="dr. Rizky">dr. Rizky</option>
                    <option value="dr. Cheeries">dr. Cheeries</option>
                    <option value="dr. Ajeng">dr. Ajeng</option>
                    <option value="dr. Wilda">dr. Wilda</option>
                    <option value="drg. Afinda Yanuar Riza">drg. Afinda Yanuar Riza</option>
                  </select>
                </div>

              
        </div>
        </div>
  </div>
  </div>
</div>
</div>
    <div class="text-center" style="margin-top: -10px; margin-bottom: 40px;">
                  <button type="submit" name="save" class="btn btn-primary">Simpan</button>
                  <button type="reset" class="btn btn-secondary">Reset</button>
                </div>


        <div class="container">
          <div class="row">
            <div class="col-md-12">

            <div class="card" style="margin-top:10px">
            <div class="card-body col-md-12">
            <?php $tgl=$koneksi->query("SELECT tgl FROM rekon_obat WHERE norm='$_GET[id]'")->fetch_assoc();?>
            <h5 class="card-title">Data Rekonsiliasi Obat</h5>
            <p><b>Tgl & Jam: <?php echo $tgl['tgl']; ?></b></p>

            <div class="table-responsive">
                <h6>A. DAFTAR OBAT YANG MENIMBULKAN ALERGI</h6>
            <table id="myTable" class="table table-striped" style="width:100%">
            <thead>
            <tr>
                <th>No</th>
                <th>Nama Obat</th>
                <th>Reaksi Alergi</th>
                <!-- <th>Aksi</th> -->
               
            </tr>
            </thead>
           <tbody>

        <?php $no=1;
              $riw=$koneksi->query("SELECT * FROM rekon_obat WHERE norm='$_GET[id]'");
        ?>

        <?php foreach ($riw as $pecah) : ?>

        <tr>
            <td><?php echo $no; ?></td>
            <td style="margin-top:10px;"><?php echo $pecah["nama_obat1"]; ?></td>
            <td style="margin-top:10px;"><?php echo $pecah["reaksi_alergi"]; ?></td>
        </tr>

        <?php $no +=1 ?>
        <?php endforeach ?>

        </tbody>
        </table>
                    
    </div>

    <div class="table-responsive" style="margin-top: 40px;">
                <h6>B. DAFTAR OBAT YANG DIBAWA DARI RUMAH</h6>
            <table id="myTable" class="table table-striped" style="width:100%">
            <thead>
            <tr>
                <th>No</th>
                <th>Nama Obat</th>
                <th>Dosis/Frekuensi</th>
                <th>Lama Pakai</th>
                <th>Waktu Terakhir Pakai</th>
                <th>Alasan Pemakaian</th>
                <th>Kelanjutan Di Ranap</th>
                <!-- <th>Aksi</th> -->
               
            </tr>
            </thead>
           <tbody>

        <?php $no=1;
            //   $riw=$koneksi->query("SELECT * FROM rekon_obat WHERE norm='$_GET[id]'");
        ?>

        <?php foreach ($riw as $pecah) : ?>

        <tr>
            <td><?php echo $no; ?></td>
            <td style="margin-top:10px;"><?php echo $pecah["nama_obat2"]; ?></td>
            <td style="margin-top:10px;"><?php echo $pecah["dosis"]; ?></td>
            <td style="margin-top:10px;"><?php echo $pecah["lama"]; ?></td>
            <td style="margin-top:10px;"><?php echo $pecah["terakhir"]; ?></td>
            <td style="margin-top:10px;"><?php echo $pecah["alasan"]; ?></td>
            <td style="margin-top:10px;"><?php echo $pecah["kelanjutan"]; ?></td>
        </tr>

        <?php $no +=1 ?>
        <?php endforeach ?>

        </tbody>
        </table>
                    
    </div>

  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


</body>

</html>


<script type="text/javascript">
    $(document).ready(function() {
      $(".add-more").click(function(){ 
          var html = $(".copy").html();
          $(".after-add-more").after(html);
      });

      // saat tombol remove dklik control group akan dihapus 
      $("body").on("click",".remove",function(){ 
          $(this).parents(".control-group").remove();
      });
    });
</script>

<script>
var myModal = document.getElementById('myModal');

</script>

<script type="text/javascript">
    $(document).ready(function(){
      refreshTable();
    });

    function refreshTable(){
        $('#userList').load('rmedis.php', function(){
           setTimeout(refreshTable, 1000);
        });
    }
</script>


<?php 

if (isset ($_POST['save'])) 
{


   $koneksi->query("INSERT INTO rekon_obat(tgl, norm, nama_obat1, reaksi_alergi, apoteker, kamar, pasien, nama_obat2, dosis, lama, terakhir, alasan, kelanjutan, dokter) VALUES ('$_POST[tgl]', '$_GET[id]','$_POST[nama_obat1]', '$_POST[reaksi_alergi]', '$_POST[apoteker]', '$_POST[kamar]', '$_POST[pasien]', '$_POST[nama_obat2]', '$_POST[dosis]', '$_POST[lama]', '$_POST[terakhir]', '$_POST[alasan]', '$_POST[kelanjutan]', '$_POST[dokter]')");

    $koneksi->query("UPDATE registrasi_rawat SET kamar='$_POST[kamar]' WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]'");


    echo "
    <script>

    alert('Data berhasil ditambah');

    document.location.href='index.php?halaman=rekonobat&id=$_GET[id]&tgl=$_GET[tgl]';

    </script>

    ";
   


} 



?>