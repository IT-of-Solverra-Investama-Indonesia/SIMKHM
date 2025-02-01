<section class="hero">
    <div id="carouselExampleIndicators" class="carousel slide">
      <div class="carousel-indicators">
        <button
          type="button"
          data-bs-target="#carouselExampleIndicators"
          data-bs-slide-to="0"
          class="active"
          aria-current="true"
          aria-label="Slide 1"
        ></button>
        <button
          type="button"
          data-bs-target="#carouselExampleIndicators"
          data-bs-slide-to="1"
          aria-label="Slide 2"
        ></button>
        <button
          type="button"
          data-bs-target="#carouselExampleIndicators"
          data-bs-slide-to="2"
          aria-label="Slide 3"
        ></button>
      </div>
      <div class="carousel-inner">
        <div
          class="carousel-item bg-secondary ratio ratio-21x9 active"
          style="border-radius: 15px"
        >
          <img
            src="https://wedangtech.my.id/1.png"
            class="d-block w-100"
            alt="..."
            style="border-radius: 13px"
          />
        </div>
        <div
          class="carousel-item bg-secondary ratio ratio-21x9"
          style="border-radius: 15px"
        >
          <img
            src="https://wedangtech.my.id/2.png"
            class="d-block w-100"
            alt="..."
            style="border-radius: 13px"
          />
        </div>
        <div
          class="carousel-item bg-secondary ratio ratio-21x9"
          style="border-radius: 15px"
        >
          <img
            src="https://wedangtech.my.id/3.png"
            class="d-block w-100"
            alt="..."
            style="border-radius: 13px"
          />
        </div>
      </div>
      <button
        class="carousel-control-prev"
        type="button"
        data-bs-target="#carouselExampleIndicators"
        data-bs-slide="prev"
      >
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button
        class="carousel-control-next"
        type="button"
        data-bs-target="#carouselExampleIndicators"
        data-bs-slide="next"
      >
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
</section>
<br>
<hr>
<br>
<section class="konsultasi">
    <h2>Konsultasi Masalahmu Dengan Dokter Ahli Kami!</h2>
    <span class="mb-1 mt-0 text-gray opacity-50 ">Atasi Masalah Kecantikanmu Seketika dengan Konsultasi Dokter di SIMKHM</span>
    <p class="text-capitalize mb-1">Ubah mimpi kecantikanmu menjadi kenyataan dengan <b class="text-success">konsultasi dokter ahli di Aplikasi </b>. Dapatkan solusi instan untuk berbagai masalah kulit, rambut, dan kecantikan lainnya.</p>
    <center>
        <a href="chat.php" class="btn btn-success text-light shadow mt-3" style="border-radius: 15px; width: 200px; height: 40px;">Mulai Konsultasi</a>
    </center>
</section>
<br>
<hr>
<br><br>
<section class="ca-1">
    <h3 class="mb-4">Produk Terbaru</h3>
    <div class="row">
        <div class="owl-carousel owl-theme">
            <?php
                $getProd1 = $koneksi->query("SELECT * FROM produk_kosmetik ORDER BY id_produk DESC LIMIT 8");
                foreach($getProd1 as $prod1){
            ?>
                    <div class="card h-100 shadow-sm">
                        <img src="produk_kosmetik/<?= $prod1['foto']?>" class="card-img-top" style="" alt="...">
                        <div class="card-body">
                            <h6 class="card-title mb-1"><?= $prod1['nama_produk']?></h6>
                            <p class="card-text" style="margin-top: -8px;">
                                <div style="font-size: 12px; max-width: 100%; height: 15px; overflow: hidden;" class="mb-0 "><?= $prod1['deskripsi']?></div>...
                                <h3 class="mt-1">Rp <?= number_format($prod1['harga'],0,'','.')?></h3>
                                <a href="index.php?halaman=detail_produk&id_produk=<?= $prod1['id_produk']?>" class="stretched-link"></a>
                            </p>
                        </div>
                    </div>
            <?php }?>
        </div>
    </div>
</section>
<br><hr><br>
<section class="ca-2">
    <h3 class="mb-4">Produk Terlaris</h3>
    <div class="row">
        <div class="owl-carousel owl-theme">
            <?php
                $getProd2 = $koneksi->query("SELECT *, COUNT(*) as jumlahProduk FROM produk_kosmetik JOIN pemesanan ON pemesanan.produk_id = produk_kosmetik.id_produk ORDER BY jumlahProduk DESC LIMIT 8");
                foreach($getProd2 as $prod2){
            ?>
                    <div class="card h-100 shadow-sm">
                        <img src="produk_kosmetik/<?= $prod2['foto']?>" class="card-img-top" style="" alt="...">
                        <div class="card-body">
                            <h6 class="card-title mb-1"><?= $prod2['nama_produk']?></h6>
                            <p class="card-text" style="margin-top: -8px;">
                                <div style="font-size: 12px; max-width: 100%; height: 15px; overflow: hidden;" class="mb-0 "><?= $prod2['deskripsi']?></div>...
                                <h3 class="mt-1">Rp <?= number_format($prod2['harga'],0,'','.')?></h3>
                                <a href="index.php?halaman=detail_produk?id_produk=<?= $prod2['id_produk']?>" class="stretched-link"></a>
                            </p>
                        </div>
                    </div>
            <?php }?>
        </div>
    </div>
</section>
<br><br>
<center>
    <a href="?halaman=shop" class="btn btn-success shadow" style="border-radius: 15px; width: 300px; height: 50px; "><span><h4>Produk Lainya</h4></span></a>
</center>
<br><hr><br>
<div class="card shadow p-3 text-light" style="background: rgb(12,223,103); background: linear-gradient(90deg, rgba(12,223,103,1) 0%, rgba(1,125,51,1) 100%);">
    <center>
    <b>Sistem Informasi Manajemen Klinik Husada Mulia</b>
    </center>
    <br><br>
    <div class="row">
        <div class="col-md-4">
            <center class="mb-3">
            <h1><b>500+</b></h1>
            <b>Produk Terjual</b>
            </center>
        </div>
        <div class="col-md-4">
            <center class="mb-3">
            <h1><b>30+</b></h1>
            <b>Varian Produk</b>
            </center>

        </div>
        <div class="col-md-4">
            <center class="mb-3">
            <h1><b>200+</b></h1>
            <b>Pengguna</b>
            </center>
        </div>
    </div>
</div>
<br><hr><br><br>
<section class="cta">
    <div class="row">
        <div class="col-md-6 mb-3">
            <center>
                <img src="https://wedangtech.my.id/Desain%20tanpa%20judul%20%285%29.png" alt="" style="width: 80%;"">
            </center>
        </div>
        <div class="col-md-6 d-flex align-items-center flex-wrap mt-3">
            <h4>Konsultasi dengan Dokter Ahli Hanya di SIMKHM! <br><br> <center><a href="chat.php" class="btn btn-success btn-sm" style="border-radius: 12px; width:150px; height: 30px;">Konsultasi</a></center></h4>
        </div>
    </div>
</section>