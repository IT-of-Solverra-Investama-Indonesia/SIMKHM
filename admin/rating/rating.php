<?php 
  require '../../admin/dist/function.php'; 
  $id=$_GET['id'];
  // $shift=$_SESSION['admin']['shift'];
  $ambil=$koneksi->query("SELECT * FROM registrasi_rawat WHERE idrawat='$id' ");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rating</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <style>
      .rating {
        display: inline-block;
      }
      .rating * {
        float: right;
      }
      .rating input {
        display: none;
      }
      .rating label {
        font-size: 30px;
        color: gray;
        cursor: pointer;
      }
      .rating label:before {
        content: "\2605";
      }
      .rating input:checked ~ label {
        color: gold;
      }
      .rating label:hover,
      .rating label:hover ~ label {
        color: gold;
      }
    </style>
</head>
<body>
<div class="container">
  <div class="jumbotron">
    <?php while ($hasil= $ambil->fetch_assoc())  { ?>

    <form method="post">
        <?php
          $dokter=$hasil['dokter_rawat']; 
          $petugaspoli=$hasil['petugaspoli']; 
          $shift=$hasil['shift']; 
          $kasir=$hasil['kasir']; 
          $perawat=$hasil['perawat']; 
          $rm=$hasil['no_rm'];
          //  $stafrating=$hasil['stafrating'];
          $ambilhp=$koneksi->query("SELECT * from pasien where no_rm='$rm' ");
          $pecahhp=$ambilhp->fetch_assoc();
          $notelp=$pecahhp['nohp'];
          //var_dump($notelp); 
        ?>
        <?php 
          $ambilfoto=$koneksi->query("SELECT * from admin where namalengkap='$dokter' ");
          $pecahfoto=$ambilfoto->fetch_assoc(); 
          $fotopoli=$koneksi->query("SELECT * from admin where username='$petugaspoli' ");
          $fotopoli=$fotopoli->fetch_assoc(); 
          $fotokasir=$koneksi->query("SELECT * from admin where username='$kasir' ");
          $fotokasir=$fotokasir->fetch_assoc(); 
          $fotoperawat=$koneksi->query("SELECT * from admin where username='$perawat' ");
          $fotoperawat=$fotoperawat->fetch_assoc(); 
        ?>
    <br>
    <h2>Rating</h2>
    <hr>
    <h6>Mohon untuk memberikan rating kepada dokter dan staf kami agar pelayanan KHM menjadi lebih baik.</h6>
    <h6>Langkah-langkah:</h6>
    <h6>1. Klik bintang sesuai dg penilaian anda, 1 bintang: buruk, 5 bintang: sangat bagus</h6>
    <h6>2. Berikan saran dan kritik</h6>

    <hr>
    <h5><b>Rating Dokter</b></h5>
    <div style="text-align: center;">
      <img src="../tenagamedis/foto/<?php echo $pecahfoto['foto'] ?>" height="200"> <br>
    <label><?php echo $hasil['dokter_rawat'] ?></label> <br>
    </div>
    <input type="hidden" name="nama" value="<?php echo $hasil['dokter_rawat'] ?>" >  
    <center>
      <div class="wrapper" style="font-weight:bold;" style="float:center">
      <input type="radio" id="r1" name="rating" value="5">
      <label for="r1">&#10038;</label>
      <input type="radio" id="r2" name="rating" value="4">
      <label for="r2">&#10038;</label>
      <input type="radio" id="r3" name="rating" value="3">
      <label for="r3">&#10038;</label>
      <input type="radio" id="r4" name="rating" value="2">
      <label for="r4">&#10038;</label>
      <input type="radio" id="r5" name="rating" value="1">
      <label for="r5">&#10038;</label>
      </div>
    </center>
    <label>Saran/Kritik</label>
    <div class="form-group">
    <textarea name="komentar" class="form-control"></textarea>
    </div>

    <hr>
    <h5><b>Rating Perawat Poli</b></h5>
    <div style="text-align: center;">
      <img src="../tenagamedis/foto/<?php echo $fotoperawat['foto'] ?>" height="200"> <br>
    <label><?php echo $hasil['perawat'] ?></label> <br>
    </div>
    <input type="hidden" name="nama_prwt" value="<?php echo $hasil['perawat'] ?>" > 
    <!-- <p> <span style="font-weight: bold; font-size: 14px">Langkah 1: Klik bintang sesuai dg penilaian anda</span><br> <span style="font-weight:bold; font-size: 12px">1 bintang: buruk, </span>  <br> <span style="font-weight: bold; font-size: 12px">5 bintang: sangat bagus </span></p> -->
    <center>
      <div class="wrapper" style="font-weight:bold;">
      <input type="radio" id="rp111" name="rating_prwt" value="5">
      <label for="rp111">&#10038;</label>
      <input type="radio" id="rp222" name="rating_prwt" value="4">
      <label for="rp222">&#10038;</label>
      <input type="radio" id="rp333" name="rating_prwt" value="3">
      <label for="rp333">&#10038;</label>
      <input type="radio" id="rp444" name="rating_prwt" value="2">
      <label for="rp444">&#10038;</label>
      <input type="radio" id="rp555" name="rating_prwt" value="1">
      <label for="rp555">&#10038;</label>
      </div>
    </center>
    <!-- <label>Langkah 2: berikan komentar </label> -->
    <label>Saran/Kritik</label>
    <div class="form-group">
    <textarea name="komen_prwt" class="form-control"></textarea>
    </div>

    <hr>
    <h5><b>Rating Pendaftaran</b></h5>
    <div style="text-align: center;">
      <img src="../tenagamedis/foto/<?php echo $fotopoli['foto'] ?>" height="200"> <br>
    <label><?php echo $hasil['petugaspoli'] ?></label> <br>
    </div>
    <input type="hidden" name="nama_daftar" value="<?php echo $petugaspoli ?>" >  
    <!-- <p> <span style="font-weight: bold; font-size: 14px">Langkah 1: Klik bintang sesuai dg penilaian anda</span><br> <span style="font-weight:bold; font-size: 12px">1 bintang: buruk, </span>  <br> <span style="font-weight: bold; font-size: 12px">5 bintang: sangat bagus </span></p> -->
    <center>
    <div class="wrapper" style="font-weight:bold;">
      <input type="radio" id="rd1" name="rating_daftar" value="5">
      <label for="rd1">&#10038;</label>
      <input type="radio" id="rd2" name="rating_daftar" value="4">
      <label for="rd2">&#10038;</label>
      <input type="radio" id="rd3" name="rating_daftar" value="3">
      <label for="rd3">&#10038;</label>
      <input type="radio" id="rd4" name="rating_daftar" value="2">
      <label for="rd4">&#10038;</label>
      <input type="radio" id="rd5" name="rating_daftar" value="1">
      <label for="rd5">&#10038;</label>
    </div>
    </center>
    <!-- <label>Langkah 2: berikan komentar </label> -->
    <label>Saran/Kritik</label>
    <div class="form-group">
    <textarea name="komen_daftar" class="form-control"></textarea>
    </div>

    <hr>
    <h5><b>Rating Kasir</b></h5>
    <div style="text-align: center;">
      <img src="../tenagamedis/foto/<?php echo $fotokasir['foto'] ?>" height="200"> <br>
    <label><?php echo $hasil['kasir'] ?></label> <br>
    </div>
    <input type="hidden" name="nama_kasir" value="<?php echo $hasil['kasir'] ?>" > 
    <!-- <p> <span style="font-weight: bold; font-size: 14px">Langkah 1: Klik bintang sesuai dg penilaian anda</span><br> <span style="font-weight:bold; font-size: 12px">1 bintang: buruk, </span>  <br> <span style="font-weight: bold; font-size: 12px">5 bintang: sangat bagus </span></p> -->
    <center>
    <div class="wrapper" style="font-weight:bold;">
      <input type="radio" id="r11" name="rating_kasir" value="5">
      <label for="r11">&#10038;</label>
      <input type="radio" id="r22" name="rating_kasir" value="4">
      <label for="r22">&#10038;</label>
      <input type="radio" id="r33" name="rating_kasir" value="3">
      <label for="r33">&#10038;</label>
      <input type="radio" id="r44" name="rating_kasir" value="2">
      <label for="r44">&#10038;</label>
      <input type="radio" id="r55" name="rating_kasir" value="1">
      <label for="r55">&#10038;</label>
    </div>
    </center>
    <!-- <label>Langkah 2: berikan komentar </label> -->
    <label>Saran/Kritik</label>
    <div class="form-group">
    <textarea name="komen_kasir" class="form-control"></textarea>
    </div>


    <?php
      $apotek = $koneksi->query("SELECT * FROM resep WHERE DATE_FORMAT(jadwal, '%Y-%m-%d') = '".date('Y-m-d', strtotime($hasil['jadwal']))."' ORDER BY id DESC LIMIT 1 ")->fetch_assoc();
    ?>
    <?php if($apotek == true){?>
      <?php
        $fotoapotek = $koneksi->query("SELECT * from admin where idadmin = '$apotek[adminid]'")->fetch_assoc();
      ?>
      <hr>
      <h5><b>Rating Apotekter</b></h5>
      <div style="text-align: center;">
        <img src="../tenagamedis/foto/<?php echo $fotoapotek['foto'] ?>" height="200"> <br>
      <label><?php echo $apotek['petugas'] ?></label> <br>
      </div>
      <input type="hidden" name="nama_apotek" value="<?php echo $apotek['petugas'] ?>" > 
      <!-- <p> <span style="font-weight: bold; font-size: 14px">Langkah 1: Klik bintang sesuai dg penilaian anda</span><br> <span style="font-weight:bold; font-size: 12px">1 bintang: buruk, </span>  <br> <span style="font-weight: bold; font-size: 12px">5 bintang: sangat bagus </span></p> -->
      <center>
      <div class="wrapper" style="font-weight:bold;">
        <input type="radio" id="ra11" name="rating_apotek" value="5">
        <label for="ra11">&#10038;</label>
        <input type="radio" id="ra22" name="rating_apotek" value="4">
        <label for="ra22">&#10038;</label>
        <input type="radio" id="ra33" name="rating_apotek" value="3">
        <label for="ra33">&#10038;</label>
        <input type="radio" id="ra44" name="rating_apotek" value="2">
        <label for="ra44">&#10038;</label>
        <input type="radio" id="ra55" name="rating_apotek" value="1">
        <label for="ra55">&#10038;</label>
      </div>
      </center>
      <!-- <label>Langkah 2: berikan komentar </label> -->
      <label>Saran/Kritik</label>
      <div class="form-group">
      <textarea name="komen_apotek" class="form-control"></textarea>
      </div>
    <?php }?>

    <hr>
    <h5><b>Rating Kebersihan</b></h5>
    <input type="hidden" name="nama_bersih" value="Kebersihan" > 
    <!-- <p> <span style="font-weight: bold; font-size: 14px">Langkah 1: Klik bintang sesuai dg penilaian anda</span><br> <span style="font-weight:bold; font-size: 12px">1 bintang: buruk, </span>  <br> <span style="font-weight: bold; font-size: 12px">5 bintang: sangat bagus </span></p> -->
    <center>
    <div class="wrapper" style="font-weight:bold;">
      <input type="radio" id="rb11" name="rating_bersih" value="5">
      <label for="rb11">&#10038;</label>
      <input type="radio" id="rb22" name="rating_bersih" value="4">
      <label for="rb22">&#10038;</label>
      <input type="radio" id="rb33" name="rating_bersih" value="3">
      <label for="rb33">&#10038;</label>
      <input type="radio" id="rb44" name="rating_bersih" value="2">
      <label for="rb44">&#10038;</label>
      <input type="radio" id="rb55" name="rating_bersih" value="1">
      <label for="rb55">&#10038;</label>
    </div>
    </center>
    <!-- <label>Langkah 2: berikan komentar </label> -->
    <label>Saran/Kritik</label>
    <div class="form-group">
    <textarea name="komen_bersih" class="form-control"></textarea>
    </div>
    <br>
    <center>
        <button class="btn btn-success" name="update">Kirim</button> <br><br>
    </center>





    </form>

    <?php } ?>
  </div>

</div>


<style type="text/css">
.wrapper {
  display: inline-block;
}
.wrapper * {
  float: right;
  font-size: 60px;
}
input {
  display: none;
}


input:checked ~ label {
  color: gold;
}
</style>



<?php 


if (isset ($_POST['update'])){

$nama=htmlspecialchars($_POST["nama"]);
$nama_kasir=htmlspecialchars($_POST["nama_kasir"]);
$nama_daftar=htmlspecialchars($_POST["nama_daftar"]);
$nama_prwt=htmlspecialchars($_POST["nama_prwt"]);
$nama_bersih=htmlspecialchars($_POST["nama_bersih"]);
$nama_apotek=htmlspecialchars($_POST["nama_apotek"]);

$rating=htmlspecialchars($_POST["rating"]);
$rating_daftar=htmlspecialchars($_POST["rating_daftar"]);
$rating_kasir=htmlspecialchars($_POST["rating_kasir"]);
$rating_prwt=htmlspecialchars($_POST["rating_prwt"]);
$rating_bersih=htmlspecialchars($_POST["rating_bersih"]);
$rating_apotek=htmlspecialchars($_POST["rating_apotek"]);

$komentar=htmlspecialchars($_POST["komentar"]);
$komen_kasir=htmlspecialchars($_POST["komen_kasir"]);
$komen_daftar=htmlspecialchars($_POST["komen_daftar"]);
$komen_prwt=htmlspecialchars($_POST["komen_prwt"]);
$komen_bersih=htmlspecialchars($_POST["komen_bersih"]);
$komen_apotek=htmlspecialchars($_POST["komen_apotek"]);


$ip =  $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);


date_default_timezone_set('Asia/Jakarta');

$tgl=date('Y-m-d H-m-s');


    $koneksi->query("INSERT INTO rating (nama, vote, ip, tgl, komentar, hp, rating_daftar, rating_kasir, komen_daftar, komen_kasir, nama_kasir, nama_daftar, nama_prwt, rating_prwt, komen_prwt, nama_bersih, rating_bersih, komen_bersih, nama_apotek, rating_apotek, komen_apotek, shift) VALUES ('$nama', '$rating', '$ip', '$tgl', '$komentar', '$notelp',' $rating_daftar', '$rating_kasir', '$komen_daftar', '$komen_kasir', '$nama_kasir', '$nama_daftar', '$nama_prwt', '$rating_prwt', '$komen_prwt', '$nama_bersih', '$rating_bersih', '$komen_bersih', '$nama_apotek', '$rating_apotek', '$komen_apotek', '$shift')

        ");

    if (mysqli_affected_rows($koneksi)>0) {

    echo "

    <script>

    alert('rating berhasil');

    document.location.href='https://husadamulia.com/';

    </script>

    ";

}else{

    echo "

    <script>

    alert('GAGAL');

    document.location.href='https://husadamulia.com/';

    </script>

    ";

}









}



 ?>





</body>





</html>



