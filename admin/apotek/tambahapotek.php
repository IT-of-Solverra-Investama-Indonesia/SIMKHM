
<?php 

// $username=$_SESSION['admin']['username'];
// $ambil=$koneksi->query("SELECT * FROM admin  WHERE username='$username';");
// $pasien=$koneksi->query("SELECT * FROM pasien JOIN rekam_medis  WHERE id_rm='$_GET[id]';");
// $pecah=$pasien->fetch_assoc();


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
            <li class="breadcrumb-item">Tambah Data Apotek</li>
          </ol>
        </nav>
      </div><!-- End Page Title -->

      <form class="row g-3" method="post" enctype="multipart/form-data">
        <div class="container">
                <div class="row">
                  <div class="col-md-12">

                    <div class="card " style="margin-top:10px">
                      <div class="card-body col-md-12">
                        <h5 class="card-title">Tambah Data Apotek</h5>
                        <!-- Multi Columns Form -->
                        <div class="row">
                          <div class="col-md-6">
                            <label for="inputName5" class="form-label">Nama Obat</label>
                            <input type="text" class="form-control" name="nama_obat[]" id="inputName5" value="" placeholder="Masukkan Nama Obat">
                          </div>
                          <div class="col-md-6">
                            <label for="inputName5"  class="form-label">Kode Obat</label>
                            <input type="text" class="form-control" name="id_obat[]" id="inputName5" value="" placeholder="Masukkan Kode Obat">
                          </div>
                          <div class="col-md-12">
                            <label for="inputName5" style=" margin-top: 30px;" class="form-label">Perusahaan/Produsen</label>
                            <input type="text" class="form-control" name="produsen[]" id="inputName5" value="" placeholder="Masukkan Nama Perusahaan/Produsen" style="margin-bottom: 10px;">
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="card after-add-more">
                      <div class="card-body">
                        <h6 class="card-title">Satuan & Jumlah Stok</h6>
                        <!-- Multi Columns Form -->
                        <div class="row">
                          <div class="col-md-4" style="margin-top:10px;">
                            <label for="inputName5" class="form-label">Satuan </label>
                            <select id="inputState" name="bentuk[]" class="form-select">
                              <option value="">Pilih</option>
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
                            <input type="text" style="background: white;" class="form-control" name="jml_obat[]" id="inputName5" value="" placeholder="Masukkan Jumlah">
                          </div>
                          
                          <div class="col-md-4">
                                <label for="inputName5" style="margin-top: 10px;" class="form-label">Tipe</label>
                                <select name="tipe[]" id="" class="form-control">
                                  <option hidden>Pilih</option>
                                  <option value="Rajal">Rajal</option>
                                  <option value="Ranap">Ranap</option>
                                </select>
                              </div>
                        </div>
                      <div class="row">
                      <div class="col-md-6">
                            <label for="inputName5" style="margin-top: 10px;" class="form-label">Harga Beli Terakhir</label>
                            <input type="number" style="background: white;" class="form-control" name="harga_beli[]" id="inputName5" value="" placeholder="Masukkan Harga Terakhir">
                      </div>
                      <div class="col-md-6">
                            <label for="inputName5" style="margin-top: 10px;" class="form-label">Margin Inap</label>
                            <input type="number" style="background: white;" class="form-control" name="margininap[]" id="inputName5" value="" placeholder="Masukkan Harga Terakhir">
                      </div>
                      <!-- <div class="col-md-4">
                            <label for="inputName5" style="margin-top: 10px;" class="form-label">Harga Non Inap</label>
                            <input type="number" style="background: white;" class="form-control" name="marginnoninap[]" id="inputName5" value="" placeholder="Masukkan Harga Terakhir">
                      </div> -->
                      </div>
                    </div>
                    </div>
                    <div class="text-center" style="margin-top: 50px; margin-bottom: 0px;">
                      <button class="btn btn-warning add-more" type="button">
                        <i class="glyphicon glyphicon-plus"></i> Tambah Lagi
                      </button>
                      <button type="submit" name="save" class="btn btn-primary">
                        Simpan
                      </button>
                  </div>
                    <div class="copy invisible" style="display: none;">
                      <div class="control-group">
                        <div class="card" style="margin-top:10px">
                          <div class="card-body col-md-12">
                            <h5 class="card-title">Tambah Data Apotek</h5>
                            <!-- Multi Columns Form -->
                            <div class="row">
                              <div class="col-md-6">
                                <label for="inputName5" class="form-label">Nama Obat</label>
                                <input type="text" class="form-control" name="nama_obat[]" id="inputName5" value="" placeholder="Masukkan Nama Obat">
                              </div>
                              <div class="col-md-6">
                                <label for="inputName5"  class="form-label">Kode Obat</label>
                                <input type="text" class="form-control" name="id_obat[]" id="inputName5" value="" placeholder="Masukkan Kode Obat">
                              </div>
                              <div class="col-md-12">
                                <label for="inputName5" style=" margin-top: 30px;" class="form-label">Perusahaan/Produsen</label>
                                <input type="text" class="form-control" name="produsen[]" id="inputName5" value="" placeholder="Masukkan Nama Perusahaan/Produsen" style="margin-bottom: 10px;">
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
                                <select id="inputState" name="bentuk[]" class="form-select">
                                  <option value="">Pilih</option>
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
                                <input type="text" style="background: white;" class="form-control" name="jml_obat[]" id="inputName5" value="" placeholder="Masukkan Jumlah">
                              </div>
                              
                              <div class="col-md-4">
                                <label for="inputName5" style="margin-top: 10px;" class="form-label">Tipe</label>
                                <select name="tipe[]" id="" class="form-control">
                                <option hidden>Pilih</option>
                                  <option value="Rajal">Rajal</option>
                                  <option value="Ranap">Ranap</option>
                                </select>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                    <label for="inputName5" style="margin-top: 10px;" class="form-label">Harga Beli Terakhir</label>
                                    <input type="number" style="background: white;" class="form-control" name="harga_beli[]" id="inputName5" value="" placeholder="Masukkan Harga Terakhir">
                              </div>
                              <div class="col-md-6">
                                    <label for="inputName5" style="margin-top: 10px;" class="form-label">Margin Inap</label>
                                    <input type="number" style="background: white;" class="form-control" name="margininap[]" id="inputName5" value="" placeholder="Masukkan Harga Terakhir">
                              </div>
                              <!-- <div class="col-md-4">
                                    <label for="inputName5" style="margin-top: 10px;" class="form-label">Harga Non Inap</label>
                                    <input type="number" style="background: white;" class="form-control" name="marginnoninap[]" id="inputName5" value="" placeholder="Masukkan Harga Terakhir">
                              </div> -->
                              </div>
                            </div>
                            </div>
                            <button class="btn btn-danger remove" type="button" ><i class="glyphicon glyphicon-remove"></i> Batal</button>

                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
        </div>
      </form><!-- End Multi Columns Form -->
    </div>
  </main><!-- End #main -->

  <script type="text/javascript">
    $(document).ready(function() {
      $(".add-more").click(function(){ 
          var html = $(".copy").html();
          $(".after-add-more").after(html);
      });

      // saat tombol remove dklik control group akan dihapus 
      $("body").on("click",".remove",function(){ 
          $(this).parents(".control-group").remove();
      });
    });
</script>
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
    $tipe=$_POST['tipe'];
    $harga_beli=$_POST['harga_beli'];
    $margininap=$_POST['margininap'];
    // $marginnoninap=$_POST['marginnoninap'];
    $jumlah=count($_POST['nama_obat'])-1;
    for ($i=0; $i < $jumlah; $i++) { 
      $koneksi->query("INSERT INTO apotek (jml_obat_minim, nama_obat, id_obat, jml_obat, produsen, bentuk, tipe, harga_beli, margininap) VALUES ('0', '$nama_obat[$i]', '$id_obat[$i]', '$jml_obat[$i]', '$produsen[$i]', '$bentuk[$i]', '$tipe[$i]', '$harga_beli[$i]', '$margininap[$i]') ");
    }

    
    if (mysqli_affected_rows($koneksi)>0) {
      echo "
        <script>
          alert('Data berhasil ditambah');
          document.location.href='index.php?halaman=daftarapotek';
        </script>
      ";
    }else{

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