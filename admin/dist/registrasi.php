<?php 

session_start();
include 'function.php';


//error_reporting(0);



// if(!isset($_SESSION['login'])){

//   header("location:login.php");

//   exit;}

 ?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Klinik</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceAdmin - v2.5.0
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="../admin/dist/index.php" class="logo d-flex align-items-center w-auto">
                  <img src="assets/img/klinik.png" alt="">
                  <span class="d-none d-lg-block">SIM KHM</span>
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">REGISTRASI</h5>
                    <p class="text-center small">Daftarkan Akun KHM Wonorejo</p>
                  </div>

                  <form class="row g-3 needs-validation" method="post">
                    <div class="col-12">
                      <label for="yourName" class="form-label">Nama Lengkap</label>
                      <input type="text" name="namalengkap" class="form-control" placeholder="Nama Lengkap" required>
                      <div class="invalid-feedback">Masukkan Nama Lengkap</div>
                    </div>

                    <div class="col-12">
                      <label for="yourUsername" class="form-label">Username</label>
                      <div class="input-group has-validation">
                        <!-- <span class="input-group-text" id="inputGroupPrepend">@</span> -->
                        <input type="text" name="username" class="form-control"  placeholder="Username" required>
                        <div class="invalid-feedback">Masukkan Username Baru</div>
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="yourPassword" class="form-label">Password</label>
                      <input type="password" name="password" class="form-control" placeholder="Password" required>
                      <div class="invalid-feedback">Masukkan Password Baru</div>
                    </div>

                    <div class="col-12">
                      <label for="" class="form-label">NIK</label>
                      <input type="text" name="nik" class="form-control" placeholder="nik" required>
                      <div class="invalid-feedback">Masukkan NIK</div>
                    </div>

                    <div class="col-12">
                      <label for="">Level</label>
                      <select name="level" id="" class="form-control">
                        <option value="">level</option>

							          <option value="perawat">perawat</option>

							          <option value="igd">igd</option>

							          <!-- <option value="apotik">apotik</option> -->

							          <option value="apoteker">apoteker</option>

							          <option value="daftar">daftar</option>

							          <option value="dokter">dokter</option>

							          <option value="kasir">kasir</option>

							          <option value="inap">inap</option>
							
                        <option value="kosmetik">kosmetik</option>
                        
                        <option value="sup">sup</option>

                        <option value="gizi">gizi</option>

                        <option value="lab">lab</option>
                        
                        <option value="racik">racik</option>
                      </select>
                    </div>

                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit" name="save" >Daftar Akun</button>
                    </div>
                   <!--  <div class="col-12">
                      <p class="small mb-0">Sudah <a href="pages-login.html">Log in</a></p>
                    </div> -->
                  </form>

                </div>
              </div>

              <div class="credits">
                <!-- All the links in the footer should remain intact. -->
                <!-- You can delete the links only if you purchased the pro version. -->
                <!-- Licensing information: https://bootstrapmade.com/license/ -->
                <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
                Solverra
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

if (isset ($_POST['save'])) 

{
$username=htmlspecialchars($_POST["username"]);
$namalengkap=htmlspecialchars($_POST["namalengkap"]);
$password=($_POST["password"]);
$level=($_POST["level"]);

$query = mysqli_query($koneksi, "SELECT * FROM admin WHERE username = '$username';"); 
        
    if($query->num_rows > 0) {
       echo "
    <script>

    alert('Username telah didaftarkan, silahkan coba username lain.');

    document.location.href='registrasi.php';

    </script>

    ";
    }
    
    else{

  $koneksi->query("INSERT INTO admin

    (username, password, namalengkap, level, nik)

    VALUES ('$username', '$password', '$namalengkap', '$_POST[level]', '$_POST[nik]')

    ");

  if (mysqli_affected_rows($koneksi)>0) {
    echo "
    <script>
    alert('Berhasil!');
    document.location.href='index.php';
    </script>
    ";
  } else {
    echo "
    <script>
    alert('GAGAL!');
    document.location.href='index.php';
    </script>
    ";
  }

}
}


 ?>

