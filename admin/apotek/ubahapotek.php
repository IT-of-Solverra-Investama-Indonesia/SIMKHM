
<?php 

$username=$_SESSION['admin']['username'];
$ambil=$koneksi->query("SELECT * FROM admin  WHERE username='$username';");
$apotek=$koneksi->query("SELECT * FROM apotek  WHERE idapotek='$_GET[id]';");
$apotek=$apotek->fetch_assoc();


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
          <li class="breadcrumb-item">Ubah Data Apotek</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

 <form class="row g-3" method="post" enctype="multipart/form-data">
   <div class="container">
          <div class="row">
            <div class="col-md-12">

            <div class="card" style="margin-top:10px">
            <div class="card-body col-md-12">
              <h5 class="card-title">Ubah Data Apotek</h5>
              <!-- Multi Columns Form -->
              <div class="row">
                <div class="col-md-6">
                  <label for="inputName5" class="form-label">Nama Obat</label>
                  <input type="text" class="form-control" name="nama_obat" id="inputName5" value="<?php echo $apotek['nama_obat']?>" placeholder="Masukkan Nama Obat">
                </div>
                <div class="col-md-6">
                  <label for="inputName5"  class="form-label">Kode Obat</label>
                  <input type="text" class="form-control" name="id_obat" id="inputName5" value="<?php echo $apotek['id_obat']?>" placeholder="Masukkan Kode Obat">
                </div>
                <div class="col-md-12">
                  <label for="inputName5" style=" margin-top: 30px;" class="form-label">Perusahaan/Produsen</label>
                  <input type="text" class="form-control" name="produsen" id="inputName5" value="<?php echo $apotek['produsen']?>" placeholder="Masukkan Nama Perusahaan/Produsen" style="margin-bottom: 10px;">
                </div>
               </div>
                </div>
                </div>

            <div class="card">
            <div class="card-body">
              <h6 class="card-title">Satuan & Jumlah Stok</h6>

              <!-- Multi Columns Form -->
              <div class="row">
                 <div class="col-md-4" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Satuan </label>
                  <select id="inputState" name="bentuk" class="form-select">
                    <option value="<?php echo $apotek['bentuk']?>"><?php echo $apotek['bentuk']?></option>
                    <option value="Box">Box</option>
                    <option value="Pcs">Pcs</option>
                    <option value="Strip">Strip</option>
                    <option value="Tablet">Tablet</option>
                    <option value="Kaplet">Kaplet</option>
                    <option value="Kapsul">Kapsul</option>
                    <option value="Pil">Pil</option>
                    <option value="Tube">Tube</option>
                    <option value="Botol">Botol</option>
                    <option value="ml">ml</option>
                    <option value="Tetes">Tetes</option>
                    <option value="Suspensi">Suspensi</option>
                    <option value="Emulsi">Emulsi</option>
                    <option value="Larutan">Larutan</option>
                    <option value="Puyer">Puyer</option>
                  </select>
                </div>    
                 <div class="col-md-4">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">Jumlah</label>
                  <input type="text" style="background: white;" class="form-control" name="jml_obat" id="inputName5" value="<?php echo $apotek['jml_obat']?>" placeholder="Masukkan Jumlah">
                </div>
                 <div class="col-md-4">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">Tipe</label>
                  <select name="tipe" id="" class="form-control">
                    <option value="<?php echo $apotek['tipe']?>" hidden><?php echo $apotek['tipe']?></option>
                    <option value="Rajal">Rajal</option>
                    <option value="Ranap">Ranap</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">Harga Beli Terkahir</label>
                  <input type="text" style="background: white;" class="form-control" name="harga_beli" id="inputName5" value="<?php echo $apotek['harga_beli']?>" placeholder="Masukkan Jumlah">
                </div>
                <div class="col-md-6">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">Margin Inap</label>
                  <input type="text" style="background: white;" class="form-control" name="margininap" id="inputName5" value="<?php echo $apotek['margininap']?>" placeholder="Masukkan Jumlah">
                </div>
                <!-- <div class="col-md-4">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">Margin Non Inap</label>
                  <input type="text" style="background: white;" class="form-control" name="marginnoninap" id="inputName5" value="<?php echo $apotek['marginnoninap']?>" placeholder="Masukkan Jumlah">
                </div> -->
             

                <div class="text-center" style="margin-top: 50px; margin-bottom: 80px;">
                  <button type="submit" name="save" class="btn btn-primary">Simpan</button>
                </div>
              </form><!-- End Multi Columns Form -->
            </div>
          </div>
        </div>
        </div>
    
      </div>
     </div>
    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


</body>

</html>


<?php  
if (isset ($_POST['save'])) {

    $produsen=$_POST['produsen'];
    $nama_obat=$_POST['nama_obat'];
    $id_obat=$_POST['id_obat'];
    $jml_obat=$_POST['jml_obat'];
    // $jml_obat_minim=$_POST['jml_obat_minim'];
    $bentuk=$_POST['bentuk'];

   

   
 $koneksi->query("UPDATE apotek SET nama_obat='$nama_obat', produsen='$produsen', id_obat='$id_obat', jml_obat='$jml_obat', bentuk='$bentuk', tipe='$_POST[tipe]', harga_beli='$_POST[harga_beli]', margininap='$_POST[margininap]' WHERE idapotek='$_GET[id]' ");


  if (mysqli_affected_rows($koneksi)>0) {
  echo "
  <script>
  alert('Data berhasil ditambah');
  document.location.href='index.php?halaman=daftarapotek;

  </script>

  ";

} else {

  echo "
  <script>
  alert('GAGAL MENGHAPUS DATA');
  document.location.href='index.php?halaman=daftarapotek;
  
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