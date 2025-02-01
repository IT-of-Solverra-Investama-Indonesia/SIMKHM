 <?php 

$id=$_GET['id'];

$username=$_SESSION['admin']['namalengkap'];
$idadmin=$_SESSION['admin']['idadmin'];

// $user=$koneksi->query("SELECT idadmin FROM admin WHERE username='$username';");
// $pecah=$ambil->fetch_assoc();



$username_admin=$username;

$idadmin=$idadmin;

$status_log= "".$username." Menghapus Data Lab";



$koneksi->query("DELETE FROM lab WHERE idlab='$_GET[id]' "); 

$koneksi->query("INSERT INTO log_user 

    (status_log, username_admin, idadmin)

    VALUES ('$status_log', '$username_admin', '$idadmin')

    ");



if (mysqli_affected_rows($koneksi)>0) {

	echo "

	<script>

	alert('Data berhasil terhapus!');

	document.location.href='index.php?halaman=daftarlab';

	</script>

	";

} else {

	echo "

	<script>

	alert('GAGAL MENGHAPUS DATA');

	document.location.href='index.php?halaman=daftarlab';

	</script>

	";

}



 ?>