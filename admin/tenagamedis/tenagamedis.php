<?php

$tenaga = $koneksi->query("SELECT * FROM admin;");

?>

<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Bootstrap demo</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>

<body>
	<?php
	if (isset($_POST['tambah'])) {
		$nama = $_POST['nama'];
		$alamat = $_POST['alamat'];
		$jabatan = $_POST['jabatan'];
		$NIP = $_POST['nip'];

		$koneksi->query("INSERT INTO tenaga_medis (nama_tenaga, alamat_tenaga, jabatan, NIP, level) VALUES ('$nama', '$alamat', '$jabatan', '$NIP', '$_POST[level]')");
		echo "
  			<script>
  				document.location.href='index.php?halaman=tenagamedis';	
  			</script>
  		";
	}

	?>
	<div class="card p-3 shadow">
		<h4>Daftar User</h4>
		<a href="index.php?halaman=dokter_konsul"> <button class="btn btn-sm btn-success">Dokter Konsul</button>
		</a>
		<div class="w-100 table-responsive">
			<table class="table table-striped w-100" style="font-size: 12px;">
				<thead>
					<tr>
						<th scope="col">Nama</th>
						<th scope="col">Username</th>
						<th scope="col">Password</th>
						<th scope="col">Level</th>
						<th scope="col">NIK</th>
						<th scope="col">Foto</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($tenaga as $data) : ?>
						<tr>
							<th scope="row">
								<?php echo $data["namalengkap"]; ?> <br>
								<?php if ($data["level"] == 'dokter' && !$query['count'] > 0) { ?>
									<span style="font-size: 10px;" class="badge bg-warning" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#updateSIP" onclick="upSIP('<?= $data['SIP'] ?>', '<?= $data['idadmin'] ?>')">SIP: <?= $data['SIP'] ?></span>
								<?php } ?>
							</th>
							<td><?php echo $data["username"]; ?></td>
							<td><?php echo $data["password"]; ?></td>
							<td><?php echo $data["level"]; ?></td>
							<td><?php echo $data["NIK"]; ?></td>
							<td>
								<?php if ($data["foto"] != '') { ?>
									<a href="../tenagamedis/foto/<?php echo $data["foto"]; ?>" class="btn btn-sm btn-warning">
										<i class="bi bi-eye"></i>
									</a>
								<?php } ?>
							</td>
							<td>
								<button type="button" class="btn btn-sm btn-success mb-1" data-bs-toggle="modal" data-bs-target="#edit<?php echo $data["idadmin"]; ?>">
									<i class="bi bi-pencil-square"></i>
								</button>
								<button type="button" class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal" data-bs-target="#del<?php echo $data["idadmin"]; ?>">
									<i class="bi bi-trash"></i>
								</button>
								<?php
								$query = $koneksi->query("SELECT COUNT(*) as count FROM dokter_konsul 
									WHERE namalengkap = '$data[namalengkap]'")->fetch_assoc();
								?>

								<?php if ($data["level"] == 'dokter' && !$query['count'] > 0) { ?>
									<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#dokterkonsul<?= $data['idadmin'] ?>">
										[+] Dr Konsul
									</button>
								<?php } ?>

							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>

	<!-- Update SIP Dokter-->
	<script>
		function upSIP(sip, id) {
			document.getElementById('sip').value = sip;
			document.getElementById('id').value = id;
		}
	</script>
	<!-- Modal -->
	<div class="modal fade" id="updateSIP" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="staticBackdropLabel">Update SIP</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<form method="post">
					<div class="modal-body">
						<input type="number" name="idadmin" id="id" class="form-control" hidden>
						<input type="text" name="SIP" id="sip" class="form-control">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="submit" name="updateSIP" class="btn btn-primary">Understood</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<?php 
		if(isset($_POST['updateSIP'])){
			$id = htmlspecialchars($_POST['idadmin']);
			$sip = htmlspecialchars($_POST['SIP']);
			$koneksi->query("UPDATE admin SET SIP='$sip' WHERE idadmin='$id'");
			echo "
				<script>

					document.location.href='index.php?halaman=tenagamedis';	
				</script>
			";
		}
	?>
	<!--Update SIP Dokter END -->


	<?php foreach ($tenaga as $data) : ?>
		<div class="modal fade" id="edit<?php echo $data["idadmin"]; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="staticBackdropLabel">Edit <?php echo $data["namalengkap"]; ?></h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<form method="post" enctype="multipart/form-data">
							<div class="mb-3">
								<input type="number" name="id" value="<?php echo $data["idadmin"]; ?>" hidden disable>
								<input type="text" class="form-control" name="namalengkap" placeholder="Nama Lengkap" value="<?php echo $data["namalengkap"]; ?>">
							</div>
							<div class="mb-3">
								<input type="text" class="form-control" name="username" placeholder="Username" value="<?php echo $data["username"]; ?>">
							</div>

							<div class="mb-3">
								<input type="password" class="form-control" name="password" placeholder="Password" value="<?php echo $data["password"]; ?>">
							</div>
							<div class="mb-3">
								<input type="text" class="form-control" name="NIK" placeholder="NIK" value="<?php echo $data["NIK"]; ?>">
							</div>

							<div class="mb-3">
								<label for="">Level</label>
								<select name="level" id="" class="form-control">
									<option value="<?php echo $data["level"]; ?>" hidden><?php echo $data["level"]; ?></option>
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
									<option value="lab">lab</option>
								</select>
							</div>
							<div class="mb-3">
								<label for="">Foto</label>
								<input type="file" class="form-control" name="foto" placeholder="foto" value="<?php echo $data["foto"]; ?>">
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
								<button class="btn btn-primary" name="update">Update</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	<?php endforeach; ?>


	<?php foreach ($tenaga as $data) : ?>
		<div class="modal fade" id="del<?php echo $data["idadmin"]; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="staticBackdropLabel">Hapus <?php echo $data["username"]; ?></h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						Data yang dihapus tidak dapat dikembalikan, apakah anda yakin ingin menghapus data ini ?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<!-- <button type="button" class="btn btn-danger">Hapus</button> -->
						<!-- <a href='hapus_jurusan.php?hapus_id_jurusan=".$row['id_jurusan']>Hapus </a> -->
						<a href="index.php?halaman=tenagamedis&id=<?php echo $data["idadmin"]; ?>" class="btn btn-danger">Hapus</a>
					</div>
				</div>
			</div>
		</div>
	<?php endforeach; ?>

	<?php foreach ($tenaga as $data) : ?>
		<div class="modal fade" id="dokterkonsul<?= $data['idadmin'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="staticBackdropLabel"> Add Dokter Konsul </h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<!-- apakah anda yakin ingin menjadikan sebagai dokter konsul? -->
						<form method="post" enctype="multipart/form-data">
							<div class="mb-3">
								<label for="">Nama Lengkap</label>
								<input type="text" class="form-control" name="namalengkap" placeholder="Nama Lengkap" value="<?php echo $data["namalengkap"]; ?>">
							</div>
							<div class="mb-3">
								<label for="">NIK</label>
								<input type="text" class="form-control" name="nik" placeholder="NIK" value="<?php echo $data["NIK"]; ?>">
							</div>
							<div class="mb-3">
								<label for="">Nomor Whatsapp</label>
								<input type="text" class="form-control" name="nowa" placeholder="" value="62">
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
								<button class="btn btn-primary" name="add">Add</button>
							</div>
						</form>
					</div>


				</div>
			</div>
		</div>
	<?php endforeach; ?>

	<?php

	if (isset($_POST['add'])) {
		$nama = $_POST['namalengkap'];
		$nik = $_POST['nik'];
		$nowa = $_POST['nowa'];

		$koneksi->query("INSERT INTO dokter_konsul (namalengkap, nik, nowa) VALUES ('$nama', '$nik', '$nowa')");
		echo "
		  <script>
			  document.location.href='index.php?halaman=dokter_konsul';	
		  </script>
	  ";
	}

	?>

	<?php
	if (isset($_POST['update'])) {
		$username = htmlspecialchars($_POST["username"]);
		$namalengkap = htmlspecialchars($_POST["namalengkap"]);
		$password = ($_POST["password"]);
		$level = ($_POST["level"]);
		$id = $_POST['id'];

		$foto = $_FILES['foto']['name'];
		$lokasi = $_FILES['foto']['tmp_name'];

		//lokasi foto
		$folderUpload = "../tenagamedis/foto";

		# periksa apakah folder tersedia
		if (!is_dir($folderUpload)) {
			# jika tidak maka folder harus dibuat terlebih dahulu
			mkdir($folderUpload, 0777, $rekursif = true);
		}

		$namaBaru = $foto;
		$lokasiBaru = "{$folderUpload}/{$namaBaru}";
		$prosesUpload = move_uploaded_file($lokasi, $lokasiBaru);

		$koneksi->query("UPDATE admin SET username='$username', NIK='$_POST[NIK]', namalengkap='$namalengkap', password='$password', level='$level', foto='$namaBaru' WHERE idadmin='$id'");

		echo "
	  			<script>
	  				document.location.href='index.php?halaman=tenagamedis';	
	  			</script>
	  		";
	}

	if (isset($_GET['id'])) {
		$idd = $_GET['id'];
		$koneksi->query("DELETE FROM admin WHERE idadmin='$idd'");
		echo "
	  			<script>
	  				document.location.href='index.php?halaman=tenagamedis';	
	  			</script>
	  		";
	}
	?>


	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>
</body>

</html>