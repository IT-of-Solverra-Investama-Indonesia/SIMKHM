<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Klinik Husada Mulia</title>
	<link rel="icon" type="image/x-icon" href="pasien/assets/img/khm.png">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<style>
		@media (min-width: 992px) {
			.mobile {
				display: none;
			}
		}
	</style>
</head>

<body>
	<nav class="navbar navbar-expand-lg px-5" style="background-color: green;" data-bs-theme="dark">
		<div class="container-fluid">
			<a class="navbar-brand" href="#">
				<img src="pasien/assets/img/khm.png" alt="" height="30" />
			</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarText">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<li class="nav-item">
						<a class="nav-link active text-white" aria-current="page" href="/">Home</a>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							Poli Online
						</a>
						<ul class="dropdown-menu" style="background-color: green;">
							<li><a class="dropdown-item" href="wonorejo/pasien/login.php">KHM Wonorejo</a></li>
							<li><a class="dropdown-item" href="klakah/pasien/login.php">KHM Klakah</a></li>
							<li><a class="dropdown-item" href="tunjung/pasien/login.php">KHM Tunjung</a></li>
						</ul>
					</li>
				</ul>
				<span class="navbar-text">
					<ul class="navbar-nav me-auto mb-2 mb-lg-0">
						<li class="nav-item me-3">
							<a class="nav-link text-white" href="wonorejo/kosmetik/index.php?halaman=home" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Konsultasi Kosmetik Online">
								<i class="bi bi-chat-text"></i><span class="mobile"> Konsultasi Kosmetik Online</span>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link text-white" href="wonorejo/kosmetik/index.php?halaman=shop" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Belanja Kosmetik">
								<i class="bi bi-cart4"></i><span class="mobile"> Belanja Kosmetik</span>
							</a>
						</li>
					</ul>
				</span>
			</div>
		</div>
	</nav>
	<div class="container my-3">
		<div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
			<div class="carousel-inner rounded">
				<div class="carousel-item active">
					<img src="https://wedangtech.my.id/1.png" class="d-block w-100 object-fit-cover" alt="...">
				</div>
				<div class="carousel-item">
					<img src="https://wedangtech.my.id/2.png" class="d-block w-100 object-fit-cover" alt="...">
				</div>
				<div class="carousel-item">
					<img src="https://wedangtech.my.id/3.png" class="d-block w-100 object-fit-cover" alt="...">
				</div>
				<div class="carousel-item">
					<img src="https://wedangtech.my.id/WhatsApp%20Image%202024-07-15%20at%2015.25.12.jpeg" class="d-block w-100 object-fit-cover" alt="...">
				</div>
				<div class="carousel-item">
					<img src="https://wedangtech.my.id/WhatsApp%20Image%202024-07-15%20at%2015.25.12%20%281%29.jpeg" class="d-block w-100 object-fit-cover" alt="...">
				</div>
				<div class="carousel-item">
					<img src="https://wedangtech.my.id/WhatsApp%20Image%202024-07-15%20at%2015.25.12%20%282%29.jpeg" class="d-block w-100 object-fit-cover" alt="...">
				</div>
			</div>
			<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
				<span class="carousel-control-prev-icon" aria-hidden="true"></span>
				<span class="visually-hidden">Previous</span>
			</button>
			<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
				<span class="carousel-control-next-icon" aria-hidden="true"></span>
				<span class="visually-hidden">Next</span>
			</button>
		</div>

		<div class="d-flex flex-column align-items-center gap-4 my-5">
			<h1 class="text-center">Pendaftaran Poli Online</h1>
			<div class="d-flex flex-column flex-md-row gap-3">
				<div class="card mt-md-5 text-center" style="width: 18rem;">
					<img src="https://wedangtech.my.id/WhatsApp%20Image%202024-07-15%20at%2015.04.18.jpeg" class="card-img-top" alt="...">
					<div class="card-body d-flex flex-column justify-content-between">
						<h5 class="card-title">Klinik Husada Mulia Wonorejo</h5>
						<!-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p> -->
						<div class="d-grid gap-2">
							<a href="wonorejo/pasien/login.php" class="btn btn-primary">Kunjungi</a>
						</div>
					</div>
				</div>
				<div class="card mt-md-0 mb-md-5 text-center" style="width: 18rem;">
					<img src="https://husadamulia.com/gambar/klakah.jpeg" class="card-img-top" alt="...">
					<div class="card-body d-flex flex-column justify-content-between">
						<h5 class="card-title">Klinik Husada Mulia Klakah</h5>
						<!-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p> -->
						<div class="d-grid gap-2">
							<a href="klakah/pasien/login.php" class="btn btn-primary">Kunjungi</a>
						</div>
					</div>
				</div>
				<div class="card mt-md-5 text-center" style="width: 18rem;">
					<img src="https://husadamulia.com/gambar/klakah.jpeg" class="card-img-top" alt="...">
					<div class="card-body d-flex flex-column justify-content-between">
						<h5 class="card-title">Klinik Husada Mulia Tunjung</h5>
						<!-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p> -->
						<div class="d-grid gap-2">
							<a href="tunjung/pasien/login.php" class="btn btn-primary">Kunjungi</a>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="d-flex flex-column flex-md-row align-items-center gap-5 my-5">
			<div class="w-50 text-center text-md-end">
				<img src="https://wedangtech.my.id/Tanpa%20judul%20%281080%20x%201080%20piksel%29.png" class="w-50" alt="...">
			</div>
			<div class="text-md-start text-center">
				<h5>Belanja kosmetik sekarang!</h5>
				<a href="wonorejo/kosmetik/index.php?halaman=shop" class="btn btn-primary btn-sm px-4 py-2">Belanja kosmetik</a>
			</div>
		</div>

		<div class="d-flex flex-column-reverse flex-md-row align-items-center gap-5 my-5 text-end justify-content-end">
			<div class="text-md-end text-center">
				<h5>Konsultasi kosmetik dengan dokter ahli, GRATIS!</h5>
				<a href="wonorejo/kosmetik/index.php?halaman=home" class="btn btn-primary btn-sm px-4 py-2">Chat dokter</a>
			</div>
			<div class="w-50 text-center text-md-start">
				<img src="https://wedangtech.my.id/Desain%20tanpa%20judul%20%285%29.png" class="w-50" alt="...">
			</div>
		</div>

		<!-- <br>
		<center>
			<img src="admin/dist/assets/img/khm.png" style="max-width: 100px" alt="" />
			<h3 class="mt-2" style="color: green;">Pelayanan Klinik Husada Mulia</h3>
		</center>
		<br>
		<div class="row">
			<div class="col-md-6">
				<a href="wonorejo/kosmetik/index.php?halaman=home" style="text-decoration: none;">
					<div class="card shadow-sm w-100 mb-4 p-3" style="border: solid green 1px; color: green;">
						<div class="row">
							<div class="col-md-12">
								<center>
									<h1><i class="bi bi-chat-text"></i></h1>
								</center>
							</div>
							<div class="col-md-12">
								<center>
									<h3>Konsultasi Kosmetik Online</h3>
								</center>
							</div>
						</div>
					</div>
				</a>
			</div>
			<div class="col-md-6">
				<a href="wonorejo/kosmetik/index.php?halaman=shop" style="text-decoration: none;">
					<div class="card shadow-sm w-100 mb-4 p-3" style="border: solid green 1px; color: green;">
						<div class="row">
							<div class="col-md-12">
								<center>
									<h1><i class="bi bi-cart4"></i></h1>
								</center>
							</div>
							<div class="col-md-12">
								<center>
									<h3>Belanja Kosmetik</h3>
								</center>
							</div>
						</div>
					</div>
				</a>
			</div>

			<div class="col-md-4">
				<h4 style="color:green;">KHM Wonorejo</h4>
				<a href="wonorejo/pasien/login.php" style="text-decoration: none;">
					<div class="card shadow-sm w-100 mb-4 p-3" style="border: solid green 1px; color: green;">
						<div class="row">
							<div class="col-md-3">
								<center>
									<h1><i class="bi bi-journal-plus"></i></h1>
								</center>
							</div>
							<div class="col-md-9">
								<center>
									<h4>Pendaftaran Poli Online</h4>
								</center>
							</div>
						</div>
					</div>
				</a>
			</div>
			<div class="col-md-4">
				<h4 style="color:green;">KHM Klakah</h4>
				<a href="klakah/pasien/login.php" style="text-decoration: none;">
					<div class="card shadow-sm w-100 mb-4 p-3" style="border: solid green 1px; color: green;">
						<div class="row">
							<div class="col-md-3">
								<center>
									<h1><i class="bi bi-journal-plus"></i></h1>
								</center>
							</div>
							<div class="col-md-9">
								<center>
									<h4>Pendaftaran Poli Online</h4>
								</center>
							</div>
						</div>
					</div>
				</a>
			</div>
			<div class="col-md-4">
				<h4 style="color:green;">KHM Tunjung</h4>
				<a href="tunjung/pasien/login.php" style="text-decoration: none;">
					<div class="card shadow-sm w-100 mb-4 p-3" style="border: solid green 1px; color: green;">
						<div class="row">
							<div class="col-md-3">
								<center>
									<h1><i class="bi bi-journal-plus"></i></h1>
								</center>
							</div>
							<div class="col-md-9">
								<center>
									<h4>Pendaftaran Poli Online</h4>
								</center>
							</div>
						</div>
					</div>
				</a>
			</div>
		</div> -->

	</div>
	<div>
		<!-- <footer class="mt-5 px-5 py-2 d-flex flex-column flex-md-row" style="background-color: green;color: #fff; width: 100%; font-size:13px;"> -->
		<footer class="mt-5 px-5 py-2 row col-md" style="background-color: green;color: #fff; width: 100%; font-size:13px;">
			<div class="col-4 mx-3 my-3">
				<img src="admin/dist/assets/img/khm.png" style="max-width: 120px;" class="mb-3" alt="" />
				<p>Lokal berkelas</p>
				<p><b>Layanan Pengaduan Konsumen</b></p>
				<p>Direktorat Jenderal Perlindungan Konsumen dan Tertib Niaga Kementerian Perdagangan RI</p>
				<p>
					Kontak WhatsApp: +62 853 1111 1010
				</p>
			</div>

			<div class="col-4 mx-3 my-3 d-flex flex-column gap-2">
				<p><strong>Hubungi kami</strong></p>
				<div>
					<h6>Alamat KHM Wonorejo</h6>
					<p class="m-1">Jl. Nasional 25, Krajan, Wonorejo, Kec. Kedungjajang, Kabupaten Lumajang, Jawa Timur 67358</p>
					<a href="https://wa.me/6282233880001" class="text-decoration-none text-white" target="_blank" rel="noopener noreferrer"><i class="bi bi-whatsapp"></i> CS +62 822 3388 0001</a>
				</div>
				<div>
					<h6>Alamat KHM Klakah</h6>
					<p class="m-1">Jl. Raya Lumajang - Probolinggo, Kec. Klakah, Kabupaten Lumajang, Jawa Timur 67356</p>
					<a href="https://wa.me/6281355550275" class="text-decoration-none text-white" target="_blank" rel="noopener noreferrer"><i class="bi bi-whatsapp"></i> CS +62 813 5555 0275</a>
				</div>
				<div>
					<h6>Alamat KHM Tunjung</h6>
					<p class="m-1">Jl. Tunjung, Krajan Dua, Tunjung, Kec. Randuagung, Kabupaten Lumajang, Jawa Timur 67354</p>
					<a href="https://wa.me/6281234571010" class="text-decoration-none text-white" target="_blank" rel="noopener noreferrer"><i class="bi bi-whatsapp"></i> CS +62 812 3457 1010</a>
				</div>
			</div>

			<div class="col-3 mx-3 my-3 d-flex flex-column">
				<p><strong>Ikuti kami</strong></p>
				<div class="d-flex gap-2">
					<a href="https://www.instagram.com/husadamuliaofficial/" class="text-decoration-none text-white" target="_blank">
						<h6><i class="bi bi-instagram"></i></h6>
					</a>
					<a href="https://www.facebook.com/profile.php?id=61553748481575" class="text-decoration-none text-white" target="_blank">
						<h6><i class="bi bi-facebook"></i></h6>
					</a>
					<a href="https://www.tiktok.com/@husada_mulia" class="text-decoration-none text-white" target="_blank">
						<h6><i class="bi bi-tiktok"></i></h6>
					</a>
					<a href="https://www.youtube.com/@sahabatmuliaofficial1463" class="text-decoration-none text-white" target="_blank">
						<h6><i class="bi bi-youtube"></i></h6>
					</a>
				</div>
			</div>
		</footer>
	</div>
	<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> -->
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

	<script>
		const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
		const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
	</script>
</body>

</html>

<?php
// echo "
// 	<script>
// 		document.location.href='pasien/';
// 	</script>
// ";
?>