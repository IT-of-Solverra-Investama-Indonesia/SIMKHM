<?php
$username = $_SESSION['admin']['username'];
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>KHM WONOREJO</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <script rel="javascript" type="text/javascript" href="js/jquery-1.11.3.min.js"></script>



  <main>
    <div class="container">
      <div class="pagetitle">
        <h1>Apotek</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="index.php?halaman=daftarapotek" style="color:blue;">Apotek</a></li>
            <li class="breadcrumb-item">Detail Data Apotek</li>
          </ol>
        </nav>
      </div><!-- End Page Title -->
      <a href="index.php?halaman=daftarapotek " class="btn btn-sm btn-dark mb-2">Kembali</a>
      <div class="card shadow-sm p-2">
        <div class="table-responsive">
          <table class="table table-striped table-hover" style="font-size: 12px;">
            <thead>
              <tr>
                <th>Tgl Beli</th>
                <th>Tgl Expired</th>
                <th>Nama Obat</th>
                <th>Tipe</th>
                <th>Id Obat</th>
                <th>Suplier</th>
                <th>Jumlah Obat</th>
                <th>Harga</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $getObat = $koneksi->query("SELECT * FROM apotek WHERE id_obat = '" . htmlspecialchars($_GET['id']) . "'");
              if ($getObat->num_rows == 0) {
                echo "
                    <script>
                      document.location.href='index.php?halaman=daftarapotek';
                    </script>
                  ";
              }
              foreach ($getObat as $obat) {
              ?>
                <tr>
                  <td><?= $obat['tgl_beli'] ?></td>
                  <td><?= $obat['tgl_expired'] ?></td>
                  <td><?= $obat['nama_obat'] ?></td>
                  <td><?= $obat['tipe'] ?></td>
                  <td><?= $obat['id_obat'] ?></td>
                  <td><?= $obat['produsen'] ?></td>
                  <td><?= $obat['jml_obat'] ?></td>
                  <td><?= $obat['harga_beli'] ?></td>
                  <td>
                    <button onclick="upData('<?= $obat['harga_beli']?>', '<?= $obat['idapotek']?>')" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#setHarga"><i class="bi bi-coin"></i></button>
                    <a href="index.php?halaman=detailapotek&id=<?= htmlspecialchars($_GET['id']) ?>&delete&idapotek=<?= $obat['idapotek'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah anda yakin ingin menghapus data ini ?')"><i class="bi bi-trash"></i></a>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main><!-- End #main -->
  <script>
    function upData(harga, idapotek) {
      var harga_var = document.getElementById('harga_id');
      var idapotek_var = document.getElementById('idapotek_id');
      harga_var.value = harga;
      idapotek_var.value = idapotek;
    }
  </script>
  <div class="modal fade" id="setHarga" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Update Harga</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="post">
          <div class="modal-body">
            <label for="">Harga</label>
            <input type="number" name="harga" id="harga_id" class="form-control">
            <input type="text" hidden name="idapotek" id="idapotek_id" class="form-control">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" name="updateHarga" class="btn btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php 
    if(isset($_POST['updateHarga'])){
      $koneksi->query("UPDATE apotek SET harga_beli = '$_POST[harga]' WHERE idapotek = '$_POST[idapotek]'");
      echo "
        <script>
          alert('Successfully');
          document.location.href='index.php?halaman=detailapotek&id=$_GET[id]';
        </script>
      ";
    }
  ?>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


  </body>

</html>
<?php
if (isset($_GET['delete'])) {
  $koneksi->query("DELETE FROM apotek WHERE idapotek = '" . htmlspecialchars($_GET['idapotek']) . "'");

  echo "
      <script>
        alert('Successfully');
        document.location.href='index.php?halaman=detailapotek&id=" . htmlspecialchars($_GET['id']) . "';
      </script>
    ";
}
?>

<?php
if (isset($_POST['save'])) {

  $produsen = $_POST['produsen'];
  $nama_obat = $_POST['nama_obat'];
  $id_obat = $_POST['id_obat'];
  $jml_obat = $_POST['jml_obat'];
  $jml_obat_minim = $_POST['jml_obat_minim'];



  $koneksi->query("UPDATE apotek SET nama_obat='$nama_obat', produsen='$produsen', id_obat='$id_obat', jml_obat='$jml_obat', jml_obat_minim='$jml_obat_minim' WHERE idapotek='$_GET[id]' ");


  if (mysqli_affected_rows($koneksi) > 0) {
    echo "
  <script>
  ('Data berhasil ditambah');
  document.location.href='index.php?halaman=daftarigd;

  </script>

  ";
  } else {

    echo "
  <script>
  ('GAGAL MENGHAPUS DATA');
  document.location.href='index.php?halaman=daftarigd;
  
  </script>

  ";
  }
}


//   // $koneksi->query("INSERT INTO log_user 

//   //   (status_log, username_admin, idadmin)

//   //   VALUES ('$status_log', '$username_admin', '$idadmin')

//   //   ");

// }

?>