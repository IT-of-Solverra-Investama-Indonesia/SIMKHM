<?php

$id =htmlspecialchars($_GET['id_produk']);
if (isset($_SESSION['kosmetik'])) {
  $products = $koneksi->query("SELECT * FROM produk_kosmetik WHERE id_produk='".htmlspecialchars($_GET['id_produk'])."'");
  $product = $products->fetch_assoc();
  
}else{
  echo "
          <script>
              alert('Lakukan Login Terlebih Dahulu Sebelum Melakukan Pembelian');
              document.location.href='login.php';
          </script>
      ";
}

?>
<div class="container">
  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <img src="produk_kosmetik/<?= $product['foto'] ?>" class="card-img-top" alt="https://picsum.photos/200">
       
      </div>
    </div>
    <div class="col-md-6">
      <div class="card border border-success border-1">
        <div class="card-body">
          <h4 class="card-title"><strong>Detail Produk</strong></h4>
          <p class="card-text"><strong>Nama produk:</strong><br> <?= $product['nama_produk'] ?></p>
          <p class="card-text"><strong>Deskripsi:</strong><br> <?= $product['deskripsi'] ?></p>
          <p class="card-text"><strong>Harga:</strong><br> Rp. <?= number_format($product['harga'], 0, ',', '.') ?>,00</p>
          <p class="card-text"><strong>Kategori:</strong><br> <?= $product['kategori'] ?></p>
          <p class="card-text"><strong>Berat:</strong> <?= $product['berat'] ?> gram</p>
          <p class="card-text"><strong>Stok:</strong> <?= $product['stok'] ?></p>
          <p class="card-text"><strong>Diskon:</strong> <span style="color: green;"><?= $product['diskon'] ?>%</span></p>
          <a href="index.php?halaman=shop&add=<?= $product['id_produk']?>&kategori=<?= $product['kategori']?>" class="btn btn-success w-100">+ Keranjang</a>
        
        </div>
        
      </div>
    </div>
  </div>
</div>
<?php

?>