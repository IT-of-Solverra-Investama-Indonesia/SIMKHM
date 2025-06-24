<?php
session_start();
$id = $_GET['id'];
date_default_timezone_set('Asia/Jakarta');
$tgl = date('Ymd');
require '../dist/function.php';
$ambilSingle = $koneksi->query("SELECT * FROM  rawatinapdetail JOIN registrasi_rawat ON rawatinapdetail.id=registrasi_rawat.idrawat WHERE rawatinapdetail.id='$id' LIMIT 1")->fetch_assoc();
$ambil = $koneksi->query("SELECT * FROM  rawatinapdetail JOIN registrasi_rawat ON rawatinapdetail.id=registrasi_rawat.idrawat WHERE rawatinapdetail.id='$id' ");
function konversiNomorHP($nomor)
{
	// Hilangkan semua karakter selain angka
	$nomor = preg_replace('/[^0-9]/', '', $nomor);

	// Jika nomor diawali dengan '0', ganti dengan '62'
	if (substr($nomor, 0, 1) === '0') {
		$nomor = '62' . substr($nomor, 1);
	}
	// Jika sudah diawali dengan '62', biarkan
	else if (substr($nomor, 0, 2) === '62') {
		// do nothing
	}
	// Jika diawali dengan '8', anggap sebagai '08' lalu ubah ke '628'
	else if (substr($nomor, 0, 1) === '8') {
		$nomor = '62' . $nomor;
	} else {
		// Format tidak dikenali, bisa dikembalikan null atau aslinya
		return null;
	}

	return $nomor;
}
function getFullUrl()
{
	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ||
		$_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

	return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Nota Rawat Inap</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
	<?php if (!isset($_GET['print'])) { ?>
		<style>
			body {
				display: flex;
				justify-content: center;
				/* Memusatkan secara horizontal */
				align-items: center;
				/* Memusatkan secara vertikal */
				height: 100vh;
				/* Pastikan body memiliki tinggi penuh viewport */
				margin: 0;
				background: linear-gradient(to bottom, #3b7c47 50%, #ffffff 50%);
				/* Jika menggunakan gradien */
			}
		</style>
		<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
		<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
		<script>
			$(document).ready(function() {
				$('#myTable').DataTable({
					"paging": false,
					"info": false,
					"searching": false,
					"ordering": false,
					"scrollY": "200px",
					"scrollCollapse": true
				});
			});
		</script>
		<div class="container">
			<center>
				<div class="card shadow py-2 px-3 mt-0" style="max-width: 600px;">
					<center>
						<img src="https://simkhm.id/wonorejo/admin/dist/assets/img/3.png" style="width: 20%; margin-bottom: 10px;" alt="">
						<h5>Nota TRNSKS-<?= $id ?></h5>
					</center>
					<table>
						<thead>
							<tr>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Nama </td>
								<td>: <?= $ambilSingle['nama_pasien'] ?></td>
							</tr>
							<tr>
								<td>Nomor RM </td>
								<td>: <?= $ambilSingle['no_rm'] ?></td>
							</tr>
							<tr>
								<td>Jadwal Kunjungan</td>
								<td>: <?= date('d F Y H:i', strtotime($ambilSingle['jadwal'])) ?> (<?= $ambilSingle['perawatan'] ?>)</td>
							</tr>
							<tr>
								<td>Dokter </td>
								<td>: <?= $ambilSingle['dokter_rawat'] ?></td>
							</tr>
						</tbody>
					</table>
					<div class="">
						<table class="table table-bordered" id="myTable" style="font-size: 12px;">
							<thead>
								<tr>
									<th>Tgl</th>
									<th>Biaya</th>
									<th>Besaran</th>
									<th>Ket</th>
									<!-- <th>Petugas</th> -->
								</tr>
							</thead>
							<tbody>
								<?php $subtotal = 0; ?>
								<?php while ($pecah = $ambil->fetch_assoc()) { ?>
									<tr>
										<td><?php echo $pecah["tgl"]; ?></td>
										<td><?php echo $pecah["biaya"]; ?></td>
										<td> Rp. <?php echo number_format($pecah["besaran"]) ?></td>
										<td><?php echo $pecah["ket"]; ?></td>
										<!-- <td><?php echo $pecah["petugas"]; ?></td> -->
									</tr>
									<?php $subtotal += $pecah['besaran']; ?>
								<?php } ?>
							</tbody>
							<tfoot>
								<tr>
									<td colspan="2">TOTAL</td>
									<td>
										<b>Rp.<?php echo number_format($subtotal) ?></b>
									</td>
									<td colspan="2"></td>
								</tr>
							</tfoot>
						</table>
					</div>
					<p align="right">
						<?php if (isset($_SESSION['shift'])) { ?>
							<a href="../dist/index.php?halaman=rekapinap&id=<?= $id ?>" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-left"></i></a>
							<a href="<?= getFullUrl() ?>&print=thermal" class="btn btn-sm btn-warning" target="_blank"><i class="bi bi-printer"></i> Thermal</a>
							<a href="<?= getFullUrl() ?>&print=hvs" class="btn btn-sm btn-primary" target="_blank"><i class="bi bi-printer-fill"></i> HVS</a>
							<?php
							$getPasien = $koneksi->query("SELECT * FROM pasien WHERE no_rm = '" . $ambilSingle['no_rm'] . "'")->fetch_assoc();
							include '../rawatjalan/api_token_wa.php';
							?>
							<a href="https://wa.me/<?= konversiNomorHP($getPasien['nohp']) ?>?text=<?= $newMes = str_replace('rating.php', 'ratinginap.php', $mes); ?><?= $_GET['id'] ?>. Berikut Nota Online Anda <?= getFullUrl() ?>" class="btn btn-sm btn-success"><i class="bi bi-whatsapp"></i></a>
						<?php } ?>
					</p>
				</div>
			</center>
		</div>
	<?php } else { ?>
		<?php if ($_GET['print'] == 'thermal') { ?>
			<style>
				body {
					font-family: monospace;
					color: #000;
					/* letter-spacing: px; */
				}

				@media print {
					@page {
						size: 58mm auto;
						margin: 0;
					}

					body {
						font-size: 11px;
					}

					h6 {
						font-size: 12px;
						font-weight: bold;
					}

				}
			</style>
			<div style="max-width: 58mm; padding: 2mm;">
				<center>
					<img src="https://simkhm.id/wonorejo/admin/dist/assets/img/3.png" style="width: 25%; margin-bottom: 10px;" alt="">
					<h6>Nota TRNSKS-<?= $id ?></h6>
				</center>
				<table style="font-size: 10px;">
					<thead>
						<tr>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Nama </td>
							<td>: <?= $ambilSingle['nama_pasien'] ?></td>
						</tr>
						<tr>
							<td>No.RM </td>
							<td>: <?= $ambilSingle['no_rm'] ?></td>
						</tr>
						<tr>
							<td>Jadwal	</td>
							<td>: <?= date('d F Y H:i', strtotime($ambilSingle['jadwal'])) ?> </td>
						</tr>
						<tr>
							<td>Dokter </td>
							<td>: <?= $ambilSingle['dokter_rawat'] ?></td>
						</tr>
					</tbody>
				</table>
				<div style="font-size: 12px;">
					<?php
					$subtotal = 0;
					while ($pecah = $ambil->fetch_assoc()) {
					?>
						<div style="">
							<li>
								<b><?php echo $pecah["tgl"]; ?></b> <?php echo $pecah["biaya"]; ?>
								<div>
									Rp <?php echo number_format($pecah["besaran"]); ?>
									<?php if (!empty($pecah["ket"])) { ?>
										(<?php echo $pecah["ket"]; ?>)
									<?php } ?>
								</div>	
							</li>
						</div>
					<?php
						$subtotal += $pecah['besaran'];
					}
					?>
					<div style="border-top:2px solid #000; margin-top:10px; padding-top:6px;">
						<b>TOTAL: Rp.<?php echo number_format($subtotal); ?></b>
					</div>
				</div>
			</div>
			<script>
				// window.print();
				// setTimeout(function() {
				// });
			</script>
		<?php } elseif ($_GET['print'] == 'hvs') { ?>
			<div class="container mt-2">
				<center>
					<img src="https://simkhm.id/wonorejo/admin/dist/assets/img/3.png" style="width: 15%; margin-bottom: 10px;" alt="">
					<h4>Nota TRNSKS-<?= $id ?></h4>
				</center>
				<table>
					<thead>
						<tr>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Nama </td>
							<td>: <?= $ambilSingle['nama_pasien'] ?></td>
						</tr>
						<tr>
							<td>Nomor RM </td>
							<td>: <?= $ambilSingle['no_rm'] ?></td>
						</tr>
						<tr>
							<td>Jadwal Kunjungan</td>
							<td>: <?= date('d F Y H:i', strtotime($ambilSingle['jadwal'])) ?> (<?= $ambilSingle['perawatan'] ?>)</td>
						</tr>
						<tr>
							<td>Dokter </td>
							<td>: <?= $ambilSingle['dokter_rawat'] ?></td>
						</tr>
					</tbody>
				</table>
				<table class="table table-bordered table-striped mt-2 table-sm">
					<thead>
						<tr>
							<th>Tgl</th>
							<th>Biaya</th>
							<th>Besaran</th>
							<th>Ket</th>
							<!-- <th>Petugas</th> -->
						</tr>
					</thead>
					<tbody>
						<?php $subtotal = 0; ?>
						<?php while ($pecah = $ambil->fetch_assoc()) { ?>
							<tr>
								<td><?php echo $pecah["tgl"]; ?></td>
								<td><?php echo $pecah["biaya"]; ?></td>
								<td> Rp. <?php echo number_format($pecah["besaran"]) ?></td>
								<td><?php echo $pecah["ket"]; ?></td>
								<!-- <td><?php echo $pecah["petugas"]; ?></td> -->
							</tr>
							<?php $subtotal += $pecah['besaran']; ?>
						<?php } ?>
						<tr>
							<td colspan="2">TOTAL</td>
							<td>
								<b>Rp.<?php echo number_format($subtotal) ?></b>
							</td>
							<td colspan="2"></td>
						</tr>
					</tbody>
				</table>
			</div>
			<script>
				window.print();
				setTimeout(function() {
					window.close();
				}, 1000);
			</script>
		<?php } ?>
	<?php } ?>
</body>

</html>