<?php 
require '../dist/function.php';
error_reporting(0);
//ambil tangggal skr
date_default_timezone_set('Asia/Jakarta');
 $tanggal=date('Y-m-d');


 $tgl=$_GET['tgl'];
 $dokter=$_GET['dr'];
 $shift=$_GET['shift'];
//var_dump($tgl);
//var_dump($dokter);






 ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Rekap Poli</title>

    <!-- Bootstrap -->
    <link href="../assets/css/style.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

   
  <style type="text/css">
  .nota {
  width: 12cm;

  font-size: 10px;

}
  </style>


  </head>
  <body>


    <div class="nota">

    <div style="font-weight: bold; font-size: 15px; text-align: center;">REKAP POLI</div>
 <div style="text-align: center">
     KLINIK HUSADA MULIA <br>
     (KHM) <br>
</div>
<hr>
<?php $ambil=$koneksi->query ("SELECT * FROM registrasi_rawat WHERE jadwal='$tgl' and dokter_rawat='$dokter' and shift='$shift' and kasir !='' "); ?>

    <?php    $pecah=$ambil->fetch_assoc() ?>

   tanggal   : <?php echo $_GET['tgl'] ?> <br>
   nama dokter: <div style="font-size: 14px; font-weight: bold"><?php echo $_GET['dr'] ?></div>
 
   kasir      : <?php echo $_GET['kasir'] ?> <br>
  

    <table class="table">

    <th>akun</th>
    <th>jumlah</th>
    <th>total</th>
     
  
<?php  $ambilpoli=$koneksi->query("SELECT *, count(poli) as jumlah, carabayar as akun, sum(poli) as total, biaya_rawat.shift from biaya_rawat join registrasi_rawat on registrasi_rawat.idrawat=biaya_rawat.idregis where jadwal='$tgl' and dokter_rawat='$dokter' and registrasi_rawat.shift='$shift' group by carabayar") ?>
<?php $totalmalam=0; ?>
<?php while ($pecahpoli=$ambilpoli->fetch_assoc()) { ?>

<tr>
  <td><?php echo $pecahpoli['akun']; ?> </td>
  <td><?php echo $pecahpoli['jumlah']; ?> </td>
  <td><?php echo number_format($pecahpoli['total']); ?> </td>
 </tr>
 <?php $totalmalam+=$pecahpoli['total'] ?>
 <?php } ?>

<?php  $ambilpoli2=$koneksi->query("SELECT biaya_lain as akun, count(biaya_lain) as jumlah, sum(total_lain) as total from biaya_rawat join registrasi_rawat on registrasi_rawat.idrawat=biaya_rawat.idregis where jadwal='$tgl' and dokter_rawat='$dokter' and biaya_lain !='' and registrasi_rawat.shift='$shift' group by biaya_lain") ?>
<?php $totallain=0; ?>
<?php while ($pecahpoli2=$ambilpoli2->fetch_assoc()) { ?>

<tr>
  <td><?php echo $pecahpoli2['akun']; ?> </td>
  <td><?php echo $pecahpoli2['jumlah']; ?> </td>
  <td><?php echo number_format($pecahpoli2['total']); ?> </td>
 </tr>
  <?php $totallain+=$pecahpoli2['total'] ?>
 <?php } ?>
 

 <!-- <?php  $ambiligd=$koneksi->query("SELECT tgl, biayaigd as akun, count(biayaigd) as jumlah, sum(biaya) as total from igd where tgl='$tgl' and dokter='$dokter' and registrasi_rawat.shift='$shift'  group by biayaigd") ?>
<?php $totaligd=0; ?>
<?php while ($pecahigd=$ambiligd->fetch_assoc()) { ?>

<tr>
  <td><?php echo $pecahigd['akun']; ?> </td>
  <td><?php echo $pecahigd['jumlah']; ?> </td>
  <td><?php echo number_format($pecahigd['total']); ?> </td>
 </tr>
   <?php $totaligd+=$pecahigd['total'] ?>
 <?php } ?>
 <tr> -->

<?php  $ambillab=$koneksi->query("SELECT periksa_lab, count(periksa_lab) as jmllab, sum(biaya_lab) as totallab, kasir from biaya_rawat join registrasi_rawat where jadwal='$tgl' and dokter_rawat='$dokter' and registrasi_rawat.shift='$shift' and kasir='$kasir' group by periksa_lab") ?>
<?php $totallab=0; ?>
<?php while ($pecahlab=$ambillab->fetch_assoc()) { ?>

<tr>
  <td><?php echo $pecahlab['periksa_lab']; ?> </td>
  <td><?php echo $pecahlab['jmllab']; ?> </td>
  <td><?php echo number_format($pecahlab['totallab']); ?> </td>
 </tr>
  <?php $totallab+=$pecahlab['totallab'] ?>
 <?php } ?>


 <tr style="font-weight: bold">
   <td colspan="2">Total</td>
   <td><?php echo number_format($totalmalam+$totallain+$totallab) ?></td>
 </tr>
 </tr>
 </table> 


  </body>
  </html>