<div class="pagetitle">
    <h1>Produk Kosmetik</h1>
</div>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary btn-sm mb-0 ms-2" style="max-width: 100px;" data-bs-toggle="modal" data-bs-target="#addModal">
    Tambah
</button>
<!-- Modal -->
<div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Produk Kosmetik</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" enctype="multipart/form-data">
          <div class="modal-body">
              <input required type="text" name="nama_produk" class="form-control mb-2" placeholder="Nama Produk">
              <input required type="number" name="harga" class="form-control mb-2" placeholder="Harga Produk">
              <textarea required name="deskripsi" class="form-control mb-2" placeholder="Deskripsi"></textarea>
              <!-- <input required type="text" name="kategori" class="form-control mb-2" placeholder="Kategori Produk"> -->
               <label for="">Kategori Produk</label>
              <select class="form-select mb-2" name="kategori" aria-label="">
                        <option value="Bpom">Bpom</option>
                        <option value="Konsultasi">Konsultasi</option>
              </select>
              <input required type="number" step="any" min="0" name="berat" class="form-control mb-2" placeholder="Berat (gram)">
              <input required type="number" step="any" min="0" name="stok" class="form-control mb-2" placeholder="Stok Produk Saat Ini">
              <input required type="number" step="any" min="0" name="diskon" class="form-control mb-2" placeholder="Diskon Dalam % (Bila Ada)">
              <label for="">Foto Produk :</label>
              <input required type="file" name="foto" class="form-control mb-2" placeholder="Harga Produk">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" name="add" class="btn btn-primary">Tambah</button>
          </div>
      </form>
    </div>
  </div>
</div>
<?php
    if(isset($_POST['add'])){
         // Upload photo
         $target_dir = "../../kosmetik/produk_kosmetik/";
    
         // Pengecekan dan pembuatan folder jika belum ada
         if (!is_dir($target_dir)) {
             mkdir($target_dir, 0777, true);
         }
         $fileName = uniqid().$_FILES['foto']['name'];
         move_uploaded_file($_FILES["foto"]["tmp_name"], $target_dir.$fileName);
    
         $koneksi->query("INSERT INTO produk_kosmetik (nama_produk, harga, deskripsi, kategori, berat, stok, diskon, foto) VALUES ('$_POST[nama_produk]', '$_POST[harga]', '$_POST[deskripsi]', '$_POST[kategori]', '$_POST[berat]', '$_POST[stok]', '$_POST[diskon]', '$fileName')");
    
         echo "
             <script>
                 alert('Berhasil');
                 document.location.href='index.php?halaman=produk_kosmetik';
             </script>
         ";
    }
    if(isset($_POST['edit'])){
         $target_dir = "../../kosmetik/produk_kosmetik/";
         if($_FILES['foto']['name'] != ''){
             unlink('../../kosmetik/produk_kosmetik/'.$_POST['fotolama']);
             $fileName = uniqid().$_FILES['foto']['name'];
             move_uploaded_file($_FILES["foto"]["tmp_name"], $target_dir.$fileName);
             $koneksi->query("UPDATE produk_kosmetik SET foto='$fileName' WHERE id_produk = '$_POST[id]'");
         }
         $koneksi->query("UPDATE produk_kosmetik SET nama_produk='$_POST[nama_produk]', harga='$_POST[harga]', deskripsi='$_POST[deskripsi]', kategori='$_POST[kategori]', berat='$_POST[berat]', stok='$_POST[stok]', diskon='$_POST[diskon]' WHERE id_produk = '$_POST[id]'");
    
         echo "
             <script>
                 alert('Berhasil');
                 document.location.href='index.php?halaman=produk_kosmetik';
             </script>
         ";
    }
    if(isset($_GET['delProd'])){
         if($_GET['fotolama'] != ''){
             unlink('../../kosmetik/produk_kosmetik/'.$_GET['fotolama']);
         }
         $koneksi->query("DELETE FROM produk_kosmetik WHERE id_produk = '$_GET[delProd]'");
         echo "
             <script>
                 alert('Berhasil');
                 document.location.href='index.php?halaman=produk_kosmetik';
             </script>
         ";
    }
?>
<div class="card shadow p-2 mt-2">
  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Nama Produk</th>
          <th>Harga</th>
          <th>Diskon</th>
          <th>Berat</th>
          <th>Stok</th>
          <th>Keterangan</th>
          <th>Foto</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php 
          $getProduk = $koneksi->query("SELECT * FROM produk_kosmetik ORDER BY created_at DESC"); 
          foreach($getProduk as $produk){
        ?>
          <tr>
            <td><?= $produk['nama_produk']?></td>
            <td>Rp <?= number_format($produk['harga'],0,'','.')?> </td>
            <td><?= $produk['diskon']?></td>
            <td><?= $produk['berat']?></td>
            <td><?= $produk['stok']?></td>
            <td><?= $produk['deskripsi']?></td>
            <td><img src="../../kosmetik/produk_kosmetik/<?= $produk['foto']?>" style="max-width: 100px;"  alt="..."></td>
            <td>
              <a class="btn btn-sm btn-dark" href="index.php?halaman=produk_kosmetik&delProd=<?= $produk['id_produk']?>&fotolama=<?= $produk['foto']?>"><i class="bi bi-trash "></i></a>
              <button data-bs-toggle="modal" data-bs-target="#readModal<?= $produk['id_produk']?>" class="btn btn-sm btn-primary"><i class="bi bi-eye "></i></button>
              <button class="btn btn-sm btn-success"  data-bs-toggle="modal" data-bs-target="#editModal<?= $produk['id_produk']?>"><i class="bi bi-pencil-square"></i></button>
            </td>
          </tr>
           <!-- Modal -->
          <div class="modal fade" id="editModal<?= $produk['id_produk']?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Produk Kosmetik</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input required type="text" name="id" class="form-control mb-2" hidden value="<?= $produk['id_produk'] ?>" placeholder="id">
                        <input required type="text" name="nama_produk" class="form-control mb-2" value="<?= $produk['nama_produk'] ?>" placeholder="Nama Produk">
                        <input required type="number" name="harga" class="form-control mb-2" value="<?= $produk['harga'] ?>" placeholder="Harga Produk">
                        <textarea required name="deskripsi" class="form-control mb-2" placeholder="Deskripsi"><?= $produk['deskripsi'] ?></textarea>
                        <!-- <input required type="text" name="kategori" class="form-control mb-2" value="<?= $produk['kategori'] ?>" placeholder="Kategori Produk"> -->
                        <select class="form-select mb-2" name="kategori" aria-label="">
                          <option value="Bpom"<?= $produk['kategori']=='Bpom' ?'selected':'' ?>>Bpom</option>
                          <option value="Konsultasi"<?= $produk['kategori']=='Konsultasi' ? 'selected':'' ?>>Konsultasi</option>
                        </select>
                        <input required type="number" step="any" min="0" name="berat" class="form-control mb-2" value="<?= $produk['berat'] ?>" placeholder="Berat (gram)">
                        <input required type="number" step="any" min="0" name="stok" class="form-control mb-2" value="<?= $produk['stok'] ?>" placeholder="Stok Produk Saat Ini">
                        <input type="number" step="any" min="0" name="diskon" class="form-control mb-2" value="<?= $produk['diskon'] ?>" placeholder="Dalam % (Bila Ada)">
                        <label for="">Foto Produk :</label>
                        <input type="file" name="foto" class="form-control mb-2">
                        <input type="text" name="fotolama" class="form-control mb-2" hidden value="<?= $produk['foto'] ?>">
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" name="edit" class="btn btn-primary">Edit</button>
                    </div>
                </form>
              </div>
            </div>
          </div>
          <!-- Modal -->
          <div class="modal fade" id="readModal<?= $produk['id_produk']?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="staticBackdropLabel">Detail Produk Kosmetik</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                      <center>
                          <img src="../../kosmetik/produk_kosmetik/<?= $produk['foto']?>" style="max-width: 100px; border-radius: 10px;" class="mb-2" alt="">
                      </center>
                        <input readonly type="text" name="id" class="form-control mb-2" hidden value="<?=$produk['id_produk'] ?>" placeholder="id">
                        <input readonly type="text" name="nama_produk" class="form-control mb-2" value="<?=$produk['nama_produk'] ?>" placeholder="Nama Produk">
                        <input readonly type="number" name="harga" class="form-control mb-2" value="<?=$produk['harga'] ?>" placeholder="Harga Produk">
                        <textarea readonly name="deskripsi" class="form-control mb-2" value="" placeholder="Deskripsi"><?=$produk['deskripsi'] ?></textarea>
                        <input readonly type="text" name="kategori" class="form-control mb-2" value="<?=$produk['kategori'] ?>" placeholder="Kategori Produk">
                        <input readonly type="number" step="any" min="0" name="berat" class="form-control mb-2" value="<?=$produk['berat'] ?>" placeholder="Berat (gram)">
                        <input readonly type="number" step="any" min="0" name="stok" class="form-control mb-2" value="<?=$produk['stok'] ?>" placeholder="Stok Produk Saat Ini">
                        <input readonly type="number" step="any" min="0" name="diskon" class="form-control mb-2" value="<?=$produk['diskon'] ?>" placeholder="Dalam % (Bila Ada)">
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
              </div>
            </div>
          </div>
        <?php }?>
      </tbody>
    </table>
  </div>
</div>