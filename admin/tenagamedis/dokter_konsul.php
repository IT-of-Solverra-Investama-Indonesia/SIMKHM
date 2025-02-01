<?php

$tenaga = $koneksi->query("SELECT * FROM dokter_konsul;");

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
	<div class="card p-3 shadow">
		<a href="index.php?halaman=tenagamedis"> <button class="btn btn-success">Kembali</button>
		</a>
		<h4>Daftar Dokter Konsul</h4>
		<a href="#">
		<!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add">
			Add
		</button> -->
		</a>

		<div class="w-100 table-responsive">
			<table class="table table-striped w-100">
				<thead>
					<tr>
						<th scope="col">Nama</th>
						<th scope="col">Nomor Wa</th>
						<th scope="col">NIK</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($tenaga as $data) : ?>
						<tr>
							<th scope="row"><?php echo $data["namalengkap"]; ?></th>
							<td><?php echo $data["nowa"]; ?></td>
							<td><?php echo $data["nik"]; ?></td>
							<td>
								<button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#edit<?php echo $data["id_dokterkonsul"]; ?>">
									Update
								</button>
								<button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#del<?php echo $data["id_dokterkonsul"]; ?>">
									Hapus
								</button>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>


	<div class="modal fade" id="add" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="staticBackdropLabel">ADD</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<form method="post" enctype="multipart/form-data">
							<div class="mb-3">
								<input type="number" name="id" value="<?php echo $data["id_dokterkonsul"]; ?>" hidden disable>
								<input type="text" class="form-control" name="namalengkap" placeholder="Nama Lengkap" >
							</div>
							<div class="mb-3">
								<input type="text" class="form-control" name="nowa" placeholder="Nomor WA" >
							</div>


							<div class="mb-3">
								<input type="text" class="form-control" name="nik" placeholder="NIK">
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

	<?php foreach ($tenaga as $data) : ?>
		<div class="modal fade" id="edit<?php echo $data["id_dokterkonsul"]; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="staticBackdropLabel">Edit <?php echo $data["namalengkap"]; ?></h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<form method="post" enctype="multipart/form-data">
							<div class="mb-3">
								<input type="number" name="id" value="<?php echo $data["id_dokterkonsul"]; ?>" hidden disable>
								<input type="text" class="form-control" name="namalengkap" placeholder="Nama Lengkap" value="<?php echo $data["namalengkap"]; ?>">
							</div>
							<div class="mb-3">
								<input type="text" class="form-control" name="nowa" placeholder="Nomor WA" value="<?php echo $data["nowa"]; ?>">
							</div>


							<div class="mb-3">
								<input type="text" class="form-control" name="nik" placeholder="NIK" value="<?php echo $data["nik"]; ?>">
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
		<div class="modal fade" id="del<?php echo $data["id_dokterkonsul"]; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="staticBackdropLabel">Hapus <?php echo $data["namalengkap"]; ?></h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						Data yang dihapus tidak dapat dikembalikan, apakah anda yakin ingin menghapus data ini ?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

						<a href="index.php?halaman=dokter_konsul&id=<?php echo $data["id_dokterkonsul"]; ?>" class="btn btn-danger">Hapus</a>
					</div>
				</div>
			</div>
		</div>
	<?php endforeach; ?>

	<?php
	if (isset($_POST['update'])) {
		$nowa = htmlspecialchars($_POST["nowa"]);
		$namalengkap = htmlspecialchars($_POST["namalengkap"]);
		$nik = $_POST["nik"];
		$id = $_POST['id'];


		$koneksi->query("UPDATE dokter_konsul SET nowa='$nowa', nik='$nik', namalengkap='$namalengkap' WHERE id_dokterkonsul='$id'");

		echo "
	  			<script>
	  				document.location.href='index.php?halaman=dokter_konsul';	
	  			</script>
	  		";
	}

	if (isset($_GET['id'])) {
		$idd = $_GET['id'];
		$koneksi->query("DELETE FROM dokter_konsul WHERE id_dokterkonsul='$idd'");
		echo "
	  			<script>
	  				document.location.href='index.php?halaman=dokter_konsul';	
	  			</script>
	  		";
	}
	?>


	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>
</body>

</html>