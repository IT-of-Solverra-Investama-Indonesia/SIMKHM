<?php 
$id=$_GET['id'];

//ambil tangggal skr
date_default_timezone_set('Asia/Jakarta');
 $tgl=date('Ymd');


require '../dist/function.php';




$ambil=$koneksi->query("SELECT * FROM  rawatinapdetail JOIN registrasi_rawat ON rawatinapdetail.id=registrasi_rawat.idrawat WHERE rawatinapdetail.id='$id' "); 



 ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>panel admin</title>

    <!-- Bootstrap -->
    <link href="../assets/css/style.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

   
  </head>
  <body>

<center>
    Klinik Husada Mulia <br>
    Jalan Raya Wonorejo no 165 <br>
    0822-33-88-0001
</center>
<br>
<br>
<div class="table-responsive">
<table class="table table-bordered">
	<thead>
		<tr>
			<th>id</th>
			<th>noRM</th>
			<th>nama</th>
			<th>tgl</th>
			<th>biaya</th>
			<th>besaran</th>
			<th>keterangan</th>
						
			
			
		</tr>
	</thead>

	<tbody>
		<?php $subtotal=0; ?>
		<?php while ($pecah=$ambil->fetch_assoc())  { ?>
		<tr>
			<td><?php echo $pecah["idrawat"]; ?></td>
			<td><?php echo $pecah["no_rm"]; ?></td>
			<td><?php echo $pecah["nama_pasien"]; ?></td>
			<td><?php echo $pecah["tgl"]; ?></td>
			<td><?php echo $pecah["biaya"]; ?></td>

			<td> Rp. <?php echo number_format($pecah["besaran"]) ?></td>
			<td><?php echo $pecah["ket"]; ?></td>
			
			
			
		</tr>
		<?php $subtotal +=$pecah['besaran']; ?>
		
		<?php } ?>
		<tr>
			<td colspan="5" >total</td>
			<td ><h2>Rp.<?php echo number_format($subtotal) ?></h2></td>
		</tr>
	</tbody>
</table>
</div>

</body>
</html>