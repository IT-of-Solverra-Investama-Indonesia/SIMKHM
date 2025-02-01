<?php
//ambil tangggal skr
date_default_timezone_set('Asia/Jakarta');
$tgl = date('Ymd');
$user = $_SESSION['admin']['username'];
// $ambil=$koneksi->query("SELECT * FROM rating order by idrating desc "); 
?>
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous"> -->

<?php if (!isset($_GET['detail']) and !isset($_GET['all'])) { ?>
	<h1>Rating</h1>
	<a href="index.php?halaman=ratingall&all" class="btn btn-sm btn-warning mb-2" style="max-width: 100px;">Ratting All</a>
	<div class="row">
		<div class="col-md-6 mb-2">
			<div class="card shadow p-2 h-100">
				<h4>Rating Dokter</h4>
				<div class="table-responsive">
					<table id="myTable1" class="table table-striped table-hover">
						<thead>
							<tr>
								<th>Bulan</th>
								<th>Nama Dokter</th>
								<th>Total Rating</th>
								<th>Jumlah Voting</th>
								<th>Rata Rata</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$getRatingDokter = $koneksi->query("SELECT *, DATE_FORMAT(tgl, '%Y-%m') as bulan, SUM(vote) as jumlahRating, COUNT(*) as jumlahVote FROM rating WHERE vote != 0 GROUP BY nama, bulan ORDER BY bulan DESC");
							foreach ($getRatingDokter as $dokter) {
							?>
								<tr>
									<td>
										<a href="index.php?halaman=ratingall&detail&petugas=nama&nama=<?= $dokter['nama'] ?>&bulan=<?= $dokter['bulan'] ?>">
											<?= $dokter['bulan'] ?>
										</a>
									</td>
									<td><?= $dokter['nama'] ?></td>
									<td><?= $dokter['jumlahRating'] ?></td>
									<td>
										<?php
										$getTotalKunjungan = $koneksi->query("SELECT COUNT(*) as jum FROM registrasi_rawat WHERE perawatan = 'Rawat Jalan' AND DATE_FORMAT(jadwal, '%Y-%m') = '$dokter[bulan]'")->fetch_assoc();
										?>
										<?= $dokter['jumlahVote'] . " " . "(" . number_format(($dokter['jumlahVote'] / $getTotalKunjungan['jum']) * 100, 1) . "%)" ?>
									</td>
									<td><?= number_format($dokter['jumlahRating'] / $dokter['jumlahVote'], 2) ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-md-6 mb-2">
			<div class="card shadow p-2 h-100">
				<h4>Rating Kasir</h4>
				<div class="table-responsive">
					<table id="myTable2" class="table table-striped table-hover">
						<thead>
							<tr>
								<th>Bulan</th>
								<th>Nama Kasir</th>
								<th>Total Rating</th>
								<th>Jumlah Voting</th>
								<th>Rata Rata</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$getRatingKasir = $koneksi->query("SELECT *, DATE_FORMAT(tgl, '%Y-%m') as bulan, SUM(rating_kasir) as jumlahRating, COUNT(*) as jumlahVote FROM rating WHERE rating_kasir != 0 GROUP BY nama_kasir, bulan ORDER BY bulan DESC");
							foreach ($getRatingKasir as $kasir) {
							?>
								<tr>
									<td>
										<a href="index.php?halaman=ratingall&detail&petugas=nama_kasir&nama=<?= $kasir['nama_kasir'] ?>&bulan=<?= $kasir['bulan'] ?>">
											<?= $kasir['bulan'] ?>
										</a>
									</td>
									<td><?= $kasir['nama_kasir'] ?></td>
									<td><?= $kasir['jumlahRating'] ?></td>
									<td>
										<?php
										$getTotalKunjungan = $koneksi->query("SELECT COUNT(*) as jum FROM registrasi_rawat WHERE perawatan = 'Rawat Jalan' AND DATE_FORMAT(jadwal, '%Y-%m') = '$kasir[bulan]'")->fetch_assoc();
										?>
										<?= $kasir['jumlahVote'] . " " . "(" . number_format(($kasir['jumlahVote'] / $getTotalKunjungan['jum']) * 100, 1) . "%)" ?>
									</td>
									<td><?= number_format($kasir['jumlahRating'] / $kasir['jumlahVote'], 2) ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-md-6 mb-2">
			<div class="card shadow p-2 h-100">
				<h4>Rating Pendaftaran</h4>
				<div class="table-responsive">
					<table id="myTable3" class="table table-striped table-hover">
						<thead>
							<tr>
								<th>Bulan</th>
								<th>Nama Pendaftaran</th>
								<th>Total Rating</th>
								<th>Jumlah Voting</th>
								<th>Rata Rata</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$getRatingDaftar = $koneksi->query("SELECT *, DATE_FORMAT(tgl, '%Y-%m') as bulan, SUM(rating_daftar) as jumlahRating, COUNT(*) as jumlahVote FROM rating WHERE rating_daftar != 0 GROUP BY nama_daftar, bulan ORDER BY bulan DESC");
							foreach ($getRatingDaftar as $daftar) {
							?>
								<tr>
									<td>
										<a href="index.php?halaman=ratingall&detail&petugas=nama_daftar&nama=<?= $daftar['nama_daftar'] ?>&bulan=<?= $daftar['bulan'] ?>">
											<?= $daftar['bulan'] ?>
										</a>
									</td>
									<td><?= $daftar['nama_daftar'] ?></td>
									<td><?= $daftar['jumlahRating'] ?></td>
									<td>
										<?php
										$getTotalKunjungan = $koneksi->query("SELECT COUNT(*) as jum FROM registrasi_rawat WHERE perawatan = 'Rawat Jalan' AND DATE_FORMAT(jadwal, '%Y-%m') = '$daftar[bulan]'")->fetch_assoc();
										?>
										<?= $daftar['jumlahVote'] . " " . "(" . number_format(($daftar['jumlahVote'] / $getTotalKunjungan['jum']) * 100, 1) . "%)" ?>
									</td>
									<td><?= number_format($daftar['jumlahRating'] / $daftar['jumlahVote'], 2) ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-md-6 mb-2">
			<div class="card shadow p-2 h-100">
				<h4>Rating Perawat</h4>
				<div class="table-responsive">
					<table id="myTable4" class="table table-striped table-hover">
						<thead>
							<tr>
								<th>Bulan</th>
								<th>Nama Perawat</th>
								<th>Total Rating</th>
								<th>Jumlah Voting</th>
								<th>Rata Rata</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$getRatingRawat = $koneksi->query("SELECT *, DATE_FORMAT(tgl, '%Y-%m') as bulan, SUM(rating_prwt) as jumlahRating, COUNT(*) as jumlahVote FROM rating WHERE rating_prwt != 0 GROUP BY nama_prwt, bulan ORDER BY bulan DESC");
							foreach ($getRatingRawat as $rawat) {
							?>
								<tr>
									<td>
										<a href="index.php?halaman=ratingall&detail&petugas=nama_prwt&nama=<?= $rawat['nama_prwt'] ?>&bulan=<?= $rawat['bulan'] ?>">
											<?= $rawat['bulan'] ?>
										</a>
									</td>
									<td><?= $rawat['nama_prwt'] ?></td>
									<td><?= $rawat['jumlahRating'] ?></td>
									<td>
										<?php
										$getTotalKunjungan = $koneksi->query("SELECT COUNT(*) as jum FROM registrasi_rawat WHERE perawatan = 'Rawat Jalan' AND DATE_FORMAT(jadwal, '%Y-%m') = '$rawat[bulan]'")->fetch_assoc();
										?>
										<?= $rawat['jumlahVote'] . " " . "(" . number_format(($rawat['jumlahVote'] / $getTotalKunjungan['jum']) * 100, 1) . "%)" ?>
									</td>
									<td><?= number_format($rawat['jumlahRating'] / $rawat['jumlahVote'], 2) ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-md-6 mb-2">
			<div class="card shadow p-2 h-100">
				<h4>Rating Kebersihan</h4>
				<div class="table-responsive">
					<table id="myTable5" class="table table-striped table-hover">
						<thead>
							<tr>
								<th>Bulan</th>
								<th>Kebersihan</th>
								<th>Total Rating</th>
								<th>Jumlah Voting</th>
								<th>Rata Rata</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$getRatingBersih = $koneksi->query("SELECT *, DATE_FORMAT(tgl, '%Y-%m') as bulan, SUM(rating_bersih) as jumlahRating, COUNT(*) as jumlahVote FROM rating WHERE rating_bersih != 0 GROUP BY nama_bersih, bulan ORDER BY bulan DESC");
							foreach ($getRatingBersih as $bersih) {
							?>
								<tr>
									<td>
										<a href="index.php?halaman=ratingall&detail&petugas=nama_bersih&nama=<?= $bersih['nama_bersih'] ?>&bulan=<?= $bersih['bulan'] ?>">
											<?= $bersih['bulan'] ?>
										</a>
									</td>
									<td><?= $bersih['nama_bersih'] ?></td>
									<td><?= $bersih['jumlahRating'] ?></td>
									<td>
										<?php
										$getTotalKunjungan = $koneksi->query("SELECT COUNT(*) as jum FROM registrasi_rawat WHERE perawatan = 'Rawat Jalan' AND DATE_FORMAT(jadwal, '%Y-%m') = '$bersih[bulan]'")->fetch_assoc();
										?>
										<?= $bersih['jumlahVote'] . " " . "(" . number_format(($bersih['jumlahVote'] / $getTotalKunjungan['jum']) * 100, 1) . "%)" ?>
									</td>
									<td><?= number_format($bersih['jumlahRating'] / $bersih['jumlahVote'], 2) ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-md-6 mb-2">
			<div class="card shadow p-2 h-100">
				<h4>Rating Apoteker</h4>
				<div class="table-responsive">
					<table id="myTable6" class="table table-striped table-hover">
						<thead>
							<tr>
								<th>Bulan</th>
								<th>Nama Apoteker</th>
								<th>Total Rating</th>
								<th>Jumlah Voting</th>
								<th>Rata Rata</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$getRatingApotek = $koneksi->query("SELECT *, DATE_FORMAT(tgl, '%Y-%m') as bulan, SUM(rating_apotek) as jumlahRating, COUNT(*) as jumlahVote FROM rating WHERE rating_apotek != 0 GROUP BY nama_apotek, bulan ORDER BY bulan DESC");
							foreach ($getRatingApotek as $apotek) {
							?>
								<tr>
									<td>
										<a href="index.php?halaman=ratingall&detail&petugas=nama_apotek&nama=<?= $apotek['nama_apotek'] ?>&bulan=<?= $apotek['bulan'] ?>">
											<?= $apotek['bulan'] ?>
										</a>
									</td>
									<td><?= $apotek['nama_apotek'] ?></td>
									<td><?= $apotek['jumlahRating'] ?></td>
									<td>
										<?php
										$getTotalKunjungan = $koneksi->query("SELECT COUNT(*) as jum FROM registrasi_rawat WHERE perawatan = 'Rawat Jalan' AND DATE_FORMAT(jadwal, '%Y-%m') = '$apotek[bulan]'")->fetch_assoc();
										?>
										<?= $apotek['jumlahVote'] . " " . "(" . number_format(($apotek['jumlahVote'] / $getTotalKunjungan['jum']) * 100, 1) . "%)" ?>
									</td>
									<td><?= number_format($apotek['jumlahRating'] / $apotek['jumlahVote'], 2) ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
<?php } elseif (isset($_GET['detail'])) { ?>
	<h3>Rating</h3>
	<button class="btn btn-dark btn-sm" onclick="window.history.back()" style="max-width: 100px;">Kembali</button>
	<div class="card shadow p-2 mt-2">
		<div class="table-responsive">
			<table id="myTable" class="table table-striped table-hover" style="width:100%">
				<!-- Boleh aku nyoba kak ? -->
				<thead>

					<tr>

						<th>tgl</th>
						<th>no hp</th>
						<th>Staff</th>
						<th>Vote</th>
						<th>Komentar</th>
						<!-- <th>nama perawat</th>
					<th>vote perawat</th>
					<th>komentar perawat</th>
					<th>nama kasir</th>
					<th>vote kasir</th>
					<th>komentar kasir</th>
					<th>nama poli</th>
					<th>vote poli</th>
					<th>komentar poli</th> -->
						<th>ip</th>
						<!-- <th>petugas</th> -->
					</tr>
				</thead>
				<tbody>
					<?php
					if ($_GET['petugas'] == 'nama') {
						$getDetail = $koneksi->query("SELECT *, vote as votee, komentar as kom FROM rating WHERE " . $_GET['petugas'] . " = '$_GET[nama]' AND DATE_FORMAT(tgl, '%Y-%m') = '$_GET[bulan]' order by tgl DESC");
					} else {
						$getDetail = $koneksi->query("SELECT *, rating_" . str_replace("nama_", "", $_GET['petugas']) . " as votee, komen_" . str_replace("nama_", "", $_GET['petugas']) . " as kom FROM rating WHERE " . $_GET['petugas'] . " = '$_GET[nama]' AND DATE_FORMAT(tgl, '%Y-%m') = '$_GET[bulan]' order by tgl DESC");
					}
					?>
					<?php while ($pecah = $getDetail->fetch_assoc()) { ?>
						<tr>
							<td><?= $pecah['tgl'] ?></td>
							<td><?= $pecah['hp'] ?></td>
							<td><?= $pecah['' . $_GET['petugas'] . ''] ?></td>
							<td><?= $pecah['votee'] ?></td>
							<td><?= $pecah['kom'] ?></td>
							<td><?= $pecah['ip'] ?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
<?php } elseif (isset($_GET['all'])) { ?>
	<h3><b>Rating All</b></h3>
	<a href="index.php?halaman=ratingall" class="btn btn-sm btn-dark mb-2" style="max-width: 100px;"><i class="bi bi-arrow-left"></i> Kembali</a>
	<div class="card shadow-sm p-2 mb-2">
		<form method="POST">
			<div class="row">
				<div class="col-12">
					<input type="text" class="form-control mb-2" name="key" placeholder="Cari...">
				</div>
				<div class="col-6">
					<label for="" class="mb-0">Mulai Tanggal :</label>
					<input type="date" name="date_start" id="" class="form-control mb-2 mt-0">
				</div>
				<div class="col-6">
					<label for="" class="mb-0">Hingga Tanggal :</label>
					<input type="date" name="date_end" id="" class="form-control mb-2 mt-0">
				</div>
				<div class="col-md-12">
					<button name="src" class="btn btn-sm btn-primary float-end"><i class="bi bi-search"></i></button>
				</div>
			</div>
		</form>
	</div>
	<?php
	if (isset($_POST['src'])) {
		echo "
				<script>
					document.location.href='index.php?halaman=ratingall&all&src&key=" . htmlspecialchars($_POST['key']) . "&date_start=" . htmlspecialchars($_POST['date_start'] == '' ? '0000-00-00' : $_POST['date_start']) . "&date_end=" . htmlspecialchars($_POST['date_end'] == '' ? date('Y-m-d') : $_POST['date_end']) . "';
				</script>
			";
	}
	?>
	<div class="card shadow-sm p-2 mb-2">
		<div class="table-responsive">
			<table class="table table-hover table-striped" style="font-size: 12px;">
				<thead>
					<tr>
						<th>Tanggal</th>
						<th>NoHp</th>
						<th>IP</th>
						<th>Dokter</th>
						<th>Rating Dokter</th>
						<th>Komentar Dokter</th>
						<th>Kasir</th>
						<th>Rating Kasir</th>
						<th>Komentar Kasir</th>
						<th>Pendaftaran</th>
						<th>Rating Pendaftaran</th>
						<th>Komentar Pendaftaran</th>
						<th>Perawat</th>
						<th>Rating Perawat</th>
						<th>Komentar Perawat</th>
						<th>Kebersihan</th>
						<th>Rating Kebersihan</th>
						<th>Komentar Kebersihan</th>
						<th>Apoteker</th>
						<th>Rating Apoteker</th>
						<th>Komentar Apoteker</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$query = "SELECT * FROM rating ORDER BY tgl DESC";
					$urlPage = 'index.php?halaman=ratingall&all';

					if (isset($_GET["src"])) {
						$query = "SELECT * FROM rating WHERE tgl >= '" . htmlspecialchars($_GET['date_start']) . "' AND tgl <= '" . htmlspecialchars($_GET['date_end']) . "' AND (nama LIKE '%" . htmlspecialchars($_GET['key']) . "%' OR komentar LIKE '%" . htmlspecialchars($_GET['key']) . "%' OR nama_kasir LIKE '%" . htmlspecialchars($_GET['key']) . "%' OR komen_kasir LIKE '%" . htmlspecialchars($_GET['key']) . "%' OR nama_daftar LIKE '%" . htmlspecialchars($_GET['key']) . "%' OR komen_daftar LIKE '%" . htmlspecialchars($_GET['key']) . "%' OR nama_prwt LIKE '%" . htmlspecialchars($_GET['key']) . "%' OR komen_prwt LIKE '%" . htmlspecialchars($_GET['key']) . "%' OR nama_bersih LIKE '%" . htmlspecialchars($_GET['key']) . "%' OR komen_bersih LIKE '%" . htmlspecialchars($_GET['key']) . "%' OR nama_apotek LIKE '%" . htmlspecialchars($_GET['key']) . "%') ORDER BY tgl DESC";

						$urlPage = 'index.php?halaman=ratingall&all&src&key=' . htmlspecialchars($_GET['key']) . '&date_start=' . htmlspecialchars($_GET['date_start']) . '&date_end=' . htmlspecialchars($_GET['date_end']) . '';
					}

					//   Pagination
					// Parameters for pagination
					$limit = 100; // Number of entries to show in a page
					$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
					$start = ($page - 1) * $limit;

					// Get the total number of records
					$total_records = $koneksi->query($query)->num_rows;

					// Calculate total pages
					$total_pages = ceil($total_records / $limit);

					$cekPage = '';
					if (isset($_GET['page'])) {
						$cekPage = $_GET['page'];
					} else {
						$cekPage = '1';
					}
					// End Pagination

					$getData = $koneksi->query($query . " LIMIT " . $start . "," . $limit);
					foreach ($getData as $data) {
					?>
						<tr>
							<td><?= $data['tgl'] ?></td>
							<td><?= $data['hp'] ?></td>
							<td><?= $data['ip'] ?></td>
							<td><?= $data['nama'] ?></td>
							<td><?= $data['vote'] ?></td>
							<td><?= $data['komentar'] ?></td>
							<td><?= $data['nama_kasir'] ?></td>
							<td><?= $data['rating_kasir'] ?></td>
							<td><?= $data['komen_kasir'] ?></td>
							<td><?= $data['nama_daftar'] ?></td>
							<td><?= $data['rating_daftar'] ?></td>
							<td><?= $data['komen_daftar'] ?></td>
							<td><?= $data['nama_prwt'] ?></td>
							<td><?= $data['rating_prwt'] ?></td>
							<td><?= $data['komen_prwt'] ?></td>
							<td><?= $data['nama_bersih'] ?></td>
							<td><?= $data['rating_bersih'] ?></td>
							<td><?= $data['komen_bersih'] ?></td>
							<td><?= $data['nama_apotek'] ?></td>
							<td><?= $data['rating_apotek'] ?></td>
							<td><?= $data['komen_apotek'] ?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="card shadow-sm p-2 mb-2">
		<?php
		// Display pagination
		echo '<nav>';
		echo '<ul class="pagination justify-content-center">';

		// Back button
		if ($page > 1) {
			echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . ($page - 1) . '">Back</a></li>';
		}

		// Determine the start and end page
		$start_page = max(1, $page - 2);
		$end_page = min($total_pages, $page + 2);

		if ($start_page > 1) {
			echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=1">1</a></li>';
			if ($start_page > 2) {
				echo '<li class="page-item"><span class="page-link">...</span></li>';
			}
		}

		for ($i = $start_page; $i <= $end_page; $i++) {
			if ($i == $page) {
				echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
			} else {
				echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . $i . '">' . $i . '</a></li>';
			}
		}

		if ($end_page < $total_pages) {
			if ($end_page < $total_pages - 1) {
				echo '<li class="page-item"><span class="page-link">...</span></li>';
			}
			echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . $total_pages . '">' . $total_pages . '</a></li>';
		}

		// Next button
		if ($page < $total_pages) {
			echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . ($page + 1) . '">Next</a></li>';
		}

		echo '</ul>';
		echo '</nav>';
		?>
	</div>
<?php } ?>

<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.css" />
<script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>
<script>
	$(document).ready(function() {
		$('#myTable').DataTable({
			order: false,
		});
		$('#myTable1').DataTable({
			order: false,
		});
		$('#myTable2').DataTable({
			order: false,
		});
		$('#myTable3').DataTable({
			order: false,
		});
		$('#myTable4').DataTable({
			order: false,
		});
		$('#myTable5').DataTable({
			order: false,
		});
		$('#myTable6').DataTable({
			order: false,
		});
	});
</script>