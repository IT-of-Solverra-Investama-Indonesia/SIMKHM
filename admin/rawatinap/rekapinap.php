<?php 
$id=$_GET['id'];

//ambil tangggal skr
date_default_timezone_set('Asia/Jakarta');
 $tgl=date('Ymd');

if(!isset($_SESSION['login'])){
  header("location:login.php");
  exit;}

// $level=$_SESSION['login']['level'];



$ambil=$koneksi->query("SELECT * FROM  rawatinapdetail JOIN registrasi_rawat ON rawatinapdetail.id=registrasi_rawat.idrawat WHERE rawatinapdetail.id='$id' "); 
// $ambil=$koneksi->query("SELECT * FROM  registrasi_rawat WHERE registrasi_rawat.idrawat='$id' "); 




 ?>

 <!--memasukkan kamar dan lain2 otomatis begitu halaman dibuka-->

<?php 

$tgl = date('Y-m-d');
$data=$koneksi->query("SELECT * from registrasi_rawat where status_antri != 'Pulang'  and perawatan = 'Rawat Inap'");

$arr=$data->fetch_assoc();

$row=$data->num_rows;



//jika lebih nol, masukkan semua yang kamarnya kosong ke biayadetail

if ($row>=0) {

	// $d=$koneksi->query("SELECT rawatinap.id, rawatinap.nama, rawatinap.noRM, kamar, tarif, tglmasuk from rawatinap left outer JOIN rawatinapsudah ON rawatinap.id=rawatinapsudah.id join kamar on kamar.namakamar=rawatinap.kamar where tglkeluar='' and rawatinapsudah.id is null ");
  $d=$koneksi->query("SELECT * from registrasi_rawat join kamar on kamar.namakamar=registrasi_rawat.kamar where status_antri != 'Pulang'  and perawatan = 'Rawat Inap' ");

	

	while ($i=$d->fetch_assoc())  {

		 $id=$i['idrawat']; 

		 $tgl;

		 $tarif=$i['tarif'];

		
  $cekTgl = $koneksi->query("SELECT COUNT(*) as jumlah FROM rawatinapdetail WHERE tgl = '$tgl' AND id = '$id'")->fetch_assoc();

  if($cekTgl['jumlah'] == 0){
    //kamar
  
    $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran) VALUES ('$id', '$tgl', 'sewa kamar', '$tarif') ");
  
    //jasa servis
  
    $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran) VALUES ('$id', '$tgl', 'jasa servis', '15000') ");
  
    //BHP
  
    $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran) VALUES ('$id', '$tgl', 'BHP', '10000') ");
  
    //administrasi
    $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran) VALUES ('$id', '$tgl', 'Administrasi', '3000') ");
  }

	}

}

 ?>

<div class="row">

<div class="col-md-12">
<h1>Detail Transaksi</h1>
</div>
<br>

<div>
<a href="index.php?halaman=entridetailinap&id=<?php echo $_GET['id'] ?>" class="btn btn-danger">Tambah</a>

<a href="../rawatinap/notapulang.php?id=<?php echo $_GET['id'] ?>" class="btn btn-success">Buat Nota</a>
</div>
<br>
<br>
<br>
</div>


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
			<th>petugas</th>
						
			
			
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
			<td><?php echo $pecah["petugas"]; ?></td>
			
			
			
		</tr>
		<?php $subtotal +=$pecah['besaran']; ?>
		
		<?php } ?>
		<tr>
			<td colspan="5" >TOTAL</td>
			<td ><h2>Rp.<?php echo number_format($subtotal) ?></h2></td>
		</tr>
	</tbody>
</table>
</div>

