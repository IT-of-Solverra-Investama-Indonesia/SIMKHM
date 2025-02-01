 <?php 

$id=$_GET['id'];

$username=$_SESSION['admin']['namalengkap'];
$idadmin=$_SESSION['admin']['idadmin'];

// $user=$koneksi->query("SELECT idadmin FROM admin WHERE username='$username';");
// $pecah=$ambil->fetch_assoc();






if(isset($_GET['regis'])){



$status_log= "".$username." Menghapus Data Registrasi";



$koneksi->query("DELETE FROM registrasi_rawat WHERE idrawat='$_GET[id]' "); 

$koneksi->query("INSERT INTO log_user 

    (status_log, username_admin, idadmin)

    VALUES ('$status_log', '$username', '$idadmin')

    ");



	if (mysqli_affected_rows($koneksi)>0) {

	echo "

	<script>

	alert('Data berhasil terhapus!');

	document.location.href='index.php?halaman=daftarregistrasi';

	</script>

	";

	} else {

	echo "

	<script>

	alert('GAGAL MENGHAPUS DATA');

	document.location.href='index.php?halaman=daftarregistrasi';

	</script>

	";

	}
}else{



$status_log= "".$username." Menghapus Data Pasien";
$koneksi->query("DELETE FROM pasien WHERE idpasien='$_GET[id]' "); 

$koneksi->query("INSERT INTO log_user 

    (status_log, username_admin, idadmin)

    VALUES ('$status_log', '$username', '$idadmin')

    ");



if (mysqli_affected_rows($koneksi)>0) {

	echo "

	<script>

	alert('Data berhasil terhapus!');

	document.location.href='index.php?halaman=daftarpasien';

	</script>

	";

} else {

	echo "

	<script>

	alert('GAGAL MENGHAPUS DATA');

	document.location.href='index.php?halaman=daftarpasien';

	</script>

	";

}
}



 ?>