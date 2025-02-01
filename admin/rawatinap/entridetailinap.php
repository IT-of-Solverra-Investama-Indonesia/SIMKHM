<?php 
$id=$_GET['id'];
$user=$_SESSION['admin']['username'];
// $shift=$_SESSION['shift'];
 $tgl=date('Ymd');
$ambil=$koneksi->query("SELECT * from registrasi_rawat where idrawat='$id' ");
$pecah=$ambil->fetch_assoc();

 ?>


<h1>Entri Biaya Inap</h1>

<form method="post">

	<div class="form-group" style="margin-top:10px">
		<label>id</label>
		<input type="text" name="id" class="form-control" required="" value="<?php echo $id ?>" readonly="">
	</div>

	<div class="form-group" style="margin-top:10px">
		<label>Nama Pasien</label>
		<input type="text" name="nama" class="form-control" required="" value="<?php echo $pecah['nama_pasien'] ?>" readonly="">
	</div>

	<div class="form-group" style="margin-top:10px">
		<label>Kamar</label>
		<input type="text" name="kamar" class="form-control" required="" value="<?php echo $pecah['kamar'] ?>" readonly="">
	</div>


	<div class="form-group" style="margin-top:10px">
    <label>Biaya</label>
	
 				 <select class="form-control" name="biaya" required id="pilihan"> 
							<option value="">Pilih Biaya</option>
								<?php 
								$ambilbiayainap=$koneksi->query("SELECT * FROM igdbiaya WHERE biayaigd NOT LIKE '%Diskon%'");
								while($pecahbiayainap=$ambilbiayainap->fetch_assoc()) {
								?>
								<option value="<?php echo $pecahbiayainap['biayaigd']; ?>"><?php echo $pecahbiayainap['biayaigd']; ?> || <?php echo $pecahbiayainap['besaran']; ?>  </option>
							<?php } ?>
				</select>
					</div>


	
<div class="form-group" style="margin-top:10px">
		<label>Ket</label>
		<input type="text" name="ket" class="form-control" >
	</div>

	<div class="form-group" style="margin-top:10px">
		<label>Petugas (logout dan perbaiki lalu login ulang bila salah)</label>
		<input type="text" name="petugas" class="form-control"  value="<?php echo $user ?>" readonly="">
	</div>

	<div class="form-group" style="margin-top:10px">
		<label>Shift</label>
        <select name="shift" id="" class="form-control">
            <option value="Pagi">Pagi</option>
            <option value="Sore">Sore</option>
            <option value="Malam">Malam</option>
        </select>
	</div>
	
	<br>
    <center>
        <button class="btn btn-primary" name="save">Simpan</button>
    </center>
</form>

<?php 



if (isset ($_POST['save'])) 

{
$id=htmlspecialchars($_POST["id"]);
$biaya=htmlspecialchars($_POST["biaya"]);

$ambilbiayainap2=$koneksi->query("SELECT * FROM igdbiaya where biayaigd='$biaya' ");
$pecahbiayainap2=$ambilbiayainap2->fetch_assoc();

$besaran=$pecahbiayainap2['besaran'];
//var_dump($besaran);
//die;


$keterangan=htmlspecialchars($_POST["ket"]);
	
	$koneksi->query("INSERT INTO rawatinapdetail
		(id, biaya, besaran, ket, petugas, tgl, shiftinap )
		VALUES ('$id', '$biaya', '$besaran', '$keterangan', '$user', '$tgl', '$shift' ) ");


  if (mysqli_affected_rows($koneksi)>0) {
	echo "
	<script>
	alert('data berhasil disimpan');
	document.location.href='index.php?halaman=rekapinap&id=$id ';
	</script>
	";
} else {
	echo "
	<script>
	alert('GAGAL!');
	document.location.href='index.php?halaman=rekapinap&id=$id ';
	</script>
	";
}




}

 ?>
