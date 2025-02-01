<?php 

$id=$_GET['id'];
$koneksi->query("DELETE FROM tenaga_medis WHERE id_tenaga='$_GET[id]' "); 
echo "
	<script>
		document.location.href='index.php?halaman=tenagamedis';	
	</script>
";

 ?>