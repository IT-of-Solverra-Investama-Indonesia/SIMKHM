<?php
$id = $_GET['id'];
//ambil tangggal skr
date_default_timezone_set('Asia/Jakarta');
$tgl = date('Ymd');
if (!isset($_SESSION['login'])) {
	header("location:login.php");
	exit;
}
// $level=$_SESSION['login']['level'];
$ambil = $koneksi->query("SELECT * FROM  rawatinapdetail JOIN registrasi_rawat ON rawatinapdetail.id=registrasi_rawat.idrawat WHERE rawatinapdetail.id='$id' ");
// $ambil=$koneksi->query("SELECT * FROM  registrasi_rawat WHERE registrasi_rawat.idrawat='$id' "); 
?>

<?php
$tgl = date('Y-m-d');
$data = $koneksi->query("SELECT * from registrasi_rawat where status_antri != 'Pulang'  and perawatan = 'Rawat Inap'");
$arr = $data->fetch_assoc();
$row = $data->num_rows;
//jika lebih nol, masukkan semua yang kamarnya kosong ke biayadetail
if ($row >= 0) {
	// $d=$koneksi->query("SELECT rawatinap.id, rawatinap.nama, rawatinap.noRM, kamar, tarif, tglmasuk from rawatinap left outer JOIN rawatinapsudah ON rawatinap.id=rawatinapsudah.id join kamar on kamar.namakamar=rawatinap.kamar where tglkeluar='' and rawatinapsudah.id is null ");
	$d = $koneksi->query("SELECT * from registrasi_rawat join kamar on kamar.namakamar=registrasi_rawat.kamar where status_antri != 'Pulang'  and perawatan = 'Rawat Inap' ");
	while ($i = $d->fetch_assoc()) {
		$id = $i['idrawat'];
		$tgl;
		$tarif = $i['tarif'];
		$cekTgl = $koneksi->query("SELECT COUNT(*) as jumlah FROM rawatinapdetail WHERE tgl = '$tgl' AND id = '$id'")->fetch_assoc();
		if ($cekTgl['jumlah'] == 0) {
			//kamar
			// $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran) VALUES ('$id', '$tgl', 'sewa kamar', '$tarif') ");

			//jasa servis
			// $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran) VALUES ('$id', '$tgl', 'jasa servis', '15000') ");

			//BHP
			// $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran) VALUES ('$id', '$tgl', 'BHP', '10000') ");

			//administrasi
			// $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran) VALUES ('$id', '$tgl', 'Administrasi', '3000') ");
		}
	}
}
?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css" />
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script>
	$(document).ready(function() {
		$('#myTable').DataTable({
			"paging": false,
			"info": false,
			"searching": true,
			"ordering": false,
			"scrollY": "400px",
			"scrollCollapse": true,
			dom: 'Bfrtip',
			buttons: [{
				extend: 'excel',
				text: 'Export Excel',
				className: 'btn btn-success btn-sm'
			}]
		});
	});
</script>
<div class="mb-2">
	<h1>Detail Transaksi</h1>
	<a href="index.php?halaman=entridetailinap&id=<?php echo $_GET['id'] ?>" class="btn mb-2 btn-sm btn-danger">Tambah</a>
	<a href="../rawatinap/notapulang.php?id=<?php echo $_GET['id'] ?>" class="btn mb-2 btn-sm btn-success">Buat Nota</a>
	<div class="card shadow p-2">
		<div class="table-responsive">
			<table class="table table-bordered" id="myTable" style="font-size: 12px;">
				<thead>
					<tr>
						<th>Id</th>
						<th>NoRM</th>
						<th>Nama</th>
						<th>Tgl</th>
						<th>Biaya</th>
						<th>Besaran</th>
						<th>Keterangan</th>
						<th>Petugas</th>
					</tr>
				</thead>
				<tbody>
					<?php $subtotal = 0; ?>
					<?php while ($pecah = $ambil->fetch_assoc()) { ?>
						<tr>
							<td><?php echo $pecah["idrawat"]; ?></td>
							<td><?php echo $pecah["no_rm"]; ?></td>
							<td><?php echo $pecah["nama_pasien"]; ?></td>
							<td><?php echo $pecah["tgl"]; ?></td>
							<td>
								<?php echo $pecah["biaya"]; ?>
							</td>
							<td>
								Rp. <?php echo number_format($pecah["besaran"]) ?>
								<?php if ($_SESSION['admin']['level'] == 'sup') { ?>
									<?php if ($pecah["biaya"] == "sewa kamar") { ?>
										<button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#updateHarga" onclick="upDataHarga('<?= $pecah['id_data'] ?>', '<?= $pecah['besaran'] ?>')">
											<i class="bi bi-pencil"></i>
										</button>
									<?php } ?>
								<?php } ?>
							</td>
							<td><?php echo $pecah["ket"]; ?></td>
							<td><?php echo $pecah["petugas"]; ?></td>
						</tr>
						<?php $subtotal += $pecah['besaran']; ?>
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="5">TOTAL</td>
						<td>
							<b>Rp.<?php echo number_format($subtotal) ?></b>
						</td>
						<td colspan="2"></td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>
<!-- Modal Update Harga -->
<script>
	function upDataHarga(id, besaran) {
		$("#id_data").val(id);
		$("#besaran").val(besaran);
	}
</script>
<div class="modal fade" id="updateHarga" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="staticBackdropLabel">Update Harga</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form method="post">
				<div class="modal-body">
					<input type="text" name="id_data" id="id_data" class="form-control" hidden>
					<input type="text" name="besaran" id="besaran" class="form-control">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
					<button type="submit" name="updateHarga" class="btn btn-sm btn-primary">Update</button>
				</div>
			</form>
		</div>
	</div>
</div>
<?php
if (isset($_POST['updateHarga'])) {
	$koneksi->query("UPDATE rawatinapdetail SET besaran = '$_POST[besaran]' WHERE id_data = '$_POST[id_data]'");
	echo "<script>alert('Data Berhasil Diupdate');</script>";
	echo "<script>location='index.php?halaman=rekapinap&id=$_GET[id]';</script>";
}
?>