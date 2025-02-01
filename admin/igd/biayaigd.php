<?php 

$tenaga=$koneksi->query("SELECT * FROM igdbiaya;"); 

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  </head>
  <body>
    <h3>Tambah Biaya</h3>
  	<div class="card shadow-lg w-100 p-3">

  		<form method="post">
  			<div class="mb-3">
            <label for="">Nama Biaya</label>
			  <input type="text" class="form-control" placeholder="Masukkan Nama Biaya" name="akun" >
			</div>
			<div class="mb-3">
            <label for="">Tarif</label>
			  <input type="number" class="form-control" name="besaran" placeholder="Masukkan Tarif">
			</div><br>
			<center>
				<button class="btn btn-primary" name="tambah">Tambahkan</button>
			</center>
  		</form>
  	</div>

  	<?php 

  	if (isset ($_POST['tambah'])) {
  		$akun = $_POST['akun'];
  		$besaran= $_POST['besaran'];
  		// $tarif= $_POST['tarif'];

  		$koneksi->query("INSERT INTO igdbiaya (biayaigd, besaran) VALUES ('$akun', '$besaran')");
  		echo "
  			<script>
  				document.location.href='index.php?halaman=biayaigd';	
  			</script>
  		";

  	}

  	 ?>

  	<h2>Daftar Biaya</h2><br>
  	<div class="table-responsive">
	  <table class="table table-striped">
		  <thead>
		    <tr>
		      <th scope="col">ID</th>
		      <th scope="col">Nama Biaya</th>
		      <th scope="col">Tarif</th>
		      <th>Action</th>
		    </tr>
		  </thead>
		  <tbody>
		  	<?php foreach ($tenaga as $data): ?>
			    <tr>
			      <th scope="row"><?php echo $data["id"]; ?></th>
			      <td><?php echo $data["biayaigd"]; ?></td>
			      <td><?php echo $data["besaran"]; ?></td>
			      <td>
			      	<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#edit<?php echo $data["id"]; ?>">
					  Update
					</button>&nbsp;
					<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#del<?php echo $data["id"]; ?>">
					  Hapus
					</button>
			      </td>
			    </tr>
		  	<?php endforeach; ?>
		  </tbody>
		</table>
  	</div>

  	<?php foreach ($tenaga as $data): ?>
  		<div class="modal fade" id="edit<?php echo $data["id"]; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Biaya <?php echo $data["biayaigd"]; ?></h1>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
		      </div>
		      <div class="modal-body">
		        <form method="post">
		        	<div class="mb-3">
                        <label for="">Nama Biaya</label>
		        	<input type="number" name="id" value="<?php echo $data["id"]; ?>" hidden>
					<input type="text" class="form-control" name="akun" placeholder="Nama Biaya" value="<?php echo $data["biayaigd"]; ?>">
					</div>
					<div class="mb-3">
                        <label for="">Tarif</label>
					  <input type="number" class="form-control" name="besaran" placeholder="Tarif" value="<?php echo $data["besaran"]; ?>">
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


  	<?php foreach ($tenaga as $data): ?>
  		<div class="modal fade" id="del<?php echo $data["id"]; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="staticBackdropLabel">Hapus Biaya <?php echo $data["biayaigd"]; ?></h1>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
		      </div>
		      <div class="modal-body">
		        Data yang dihapus tidak dapat dikembalikan, apakah anda yakin ingin menghapus data ini ?
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
		        <!-- <button type="button" class="btn btn-danger">Hapus</button> -->
		        <!-- <a href='hapus_jurusan.php?hapus_id_jurusan=".$row['id_jurusan']>Hapus </a> -->
		        <a href="index.php?halaman=biayaigd&id=<?php echo $data["id"]; ?>&act=del" class="btn btn-danger">Hapus</a>
		      </div>
		    </div>
		  </div>
		</div>
  	<?php endforeach ;?>

  	<?php 
  		if (isset($_POST['update'])) {
	  		$akun = $_POST['akun'];
	  		$besaran= $_POST['besaran'];
	  		// $tarif= $_POST['tarif'];
	  		$id= $_POST['id'];
	  		$koneksi->query("UPDATE igdbiaya SET biayaigd='$akun', besaran='$besaran' WHERE id='$id'");
	  		echo "
	  			<script>
	  				document.location.href='index.php?halaman=biayaigd';	
	  			</script>
	  		";
	  	}

	  	if (isset($_GET['act'])) {
	  		$idd=$_GET['id'];
	  		$koneksi->query("DELETE FROM igdbiaya WHERE id='$idd'");
	  		echo "
	  			<script>
	  				document.location.href='index.php?halaman=biayaigd';	
	  			</script>
	  		";
	  	}
  	 ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>

  </body>
</html>

