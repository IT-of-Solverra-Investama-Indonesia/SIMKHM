<?php 
 session_start();
      
 if (!isset($_SESSION['pasien']['nama_lengkap'])) {
     header("Location: login.php");
     exit();
 }
 if(isset($_GET['logout'])){
   // Hapus semua data sesi
   session_unset();
   // Hancurkan sesi
   session_destroy();
   // Redirect ke halaman login
   header("Location: login.php");
   exit();

 }
include "function.php";

// $perawat=$_SESSION['admin']['username'];
// $id=$_GET['id'];
$norm = $_SESSION['pasien']['no_rm'];


$date= date("Y-m-d");

  $pasien=$koneksi->query("SELECT * FROM registrasi_rawat WHERE no_rm = '$norm' ORDER BY idrawat DESC;"); 


?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>KHM WONOREJO</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <link rel="icon" type="image/png" href="../admin/dist/assets/img/logokhm.png" />
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" integrity="sha384-4LISF5TTJX/fLmGSxO53rV4miRxdg84mZsxmO8Rx5jGtp/LbrixFETvWa5a6sESd" crossorigin="anonymous">
  <link href="assets/css/style.css" rel="stylesheet"> -->

  <!-- Favicons -->
  <link href="assets/img/khm.png" rel="icon">
  <link href="assets/img/icons8-hospital-3-100.png" rel="apple-touch-icon">

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

    <!-- Vendor JS Files -->
    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/chart.js/chart.umd.js"></script>
    <script src="assets/vendor/echarts/echarts.min.js"></script>
    <script src="assets/vendor/quill/quill.min.js"></script>
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
  
    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>

  <!-- DATATABLES -->
  <link href="https://code.jquery.com/jquery-3.5.1.js" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.2/js/dataTables.bootstrap5.min.js" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

 <!-- DATATABLES -->
    
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
<link src="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css">
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>

</head>

 <body>
   <main>
    <div class="container">
      <div class="pagetitle">
      <nav>
        <!-- <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php?halaman=daftarpasien" style="color:blue;">Daftar Registrasi</a></li>
        </ol> -->
      </nav>
    </div><!-- End Page Title -->

      <section class="section  py-4">
        <div class="container">
        <h3>Riwayat Registrasi</h3>
              
          <div class="row">
            <div class="col-lg-12 col-md-12">

            <div class="card">
            <div class="card-body">
                <!--   <div style="margin-top: 50px; margin-bottom: 30px; text-align: right;">
               
                <a href="index.php?halaman=pasien" class="btn btn-primary"><i class="bi bi-plus"></i> Pasien</a>

                </div> -->
              <h5 class="card-title">Data Registrasi</h5>

              <!-- Multi Columns Form -->
            <div class="table-responsive">
            <table id="myTable" class="table table-striped" style="width:100%">
            <thead>
            <tr>
                <th>Jadwal</th>
                <th>Antrian</th>
                <th style="width:200px"></th>
            </tr>
            </thead>
            <tbody>

                <?php $no=1 ?>

                <?php foreach ($pasien as $pecah) : ?>

                <tr>
                
                    <td style="margin-top:10px;"> <?= $pecah['jadwal']?></td>
                    <td style="margin-top:10px;"><?php echo $pecah["antrian"]; ?></td>
                    <td style="width:200px">
                    <a href="riwayatdetail.php?idrawat=<?php echo $pecah["idrawat"]; ?>" class="btn btn-sm btn-success"></i> Detail</a>
                    </div>
                    <a href="riwayatdaftar.php?id=<?php echo $pecah["idrawat"]; ?>&hapus" class="btn btn-sm btn-danger"  onclick="return confirm('Data tidak dapat dikembalikan. Anda yakin mau membatalkan registrasi ini ?')"> Batal</a>
                    </div>
                    </td>
                </tr>

                <?php $no +=1 ?>
                <?php endforeach; ?>

                </tbody>
                </table>
                    
            </div>
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


if(isset($_GET['hapus'])){

$koneksi->query("DELETE FROM registrasi_rawat WHERE idrawat='$_GET[id]' "); 

	if (mysqli_affected_rows($koneksi)>0) {

	echo "

	<script>

	alert('Data berhasil terhapus!');

	document.location.href='riwayatdaftar.php';

	</script>

	";

	} else {

	echo "

	<script>

	alert('GAGAL MENGHAPUS DATA');

	document.location.href='riwayatdaftar.php';

	</script>

	";

	}

}



 ?>

<script>
  $(document).ready(function() {
      $('#myTable').DataTable( {
       search: true,
       pagination: true,
       order: [[0, 'desc']]
      } );
  } );
</script>