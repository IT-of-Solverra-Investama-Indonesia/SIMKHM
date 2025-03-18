
<?php 
  $date= date("Y-m-d");
  date_default_timezone_set('Asia/Jakarta');

  $username=$_SESSION['admin']['username'];
  $petugas=$_SESSION['admin']['namalengkap'];
  $ambil=$koneksi->query("SELECT * FROM admin  WHERE username='$username';");

  $pasien=$koneksi->query("SELECT * FROM pasien  WHERE no_rm='$_GET[id]';")->fetch_assoc();
  
  $jadwal=$koneksi->query("SELECT * FROM registrasi_rawat  WHERE no_rm='$_GET[id]' AND date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' ")->fetch_assoc();
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
  <!-- Select2 CSS --> 
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" /> 

  <!-- jQuery --> 
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 

  <!-- Select2 JS --> 
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

 
</head>
<body>
   <main>
      <div class="container">
        <div class="pagetitle">
          <h1>CATATAN PERKEMBANGAN PENYAKIT TERINTEGRASI RI</h1>
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item active"><a href="index.php?halaman=daftarrmedis" style="color:blue;">Rekam Medis</a></li>
              <li class="breadcrumb-item">Perkembangan Penyakit</li>
            </ol>
          </nav>
        </div>
      </div>
      <?php if(!isset($_GET['ubah'])){?>
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <div class="card" style="margin-top:10px">
                <div class="card-body col-md-12">
                  <h5 class="card-title">DATA CATATAN PERKEMBANGAN PENYAKIT TERINTEGRASI RAWAT INAP</h5>
                  <div class="table-responsive">
                    <table id="myTable" class="table table-striped" style="width:100%">
                        <thead>
                          <tr>
                              <th>No</th>
                              <th>Tgl&Jam</th>
                              <!-- <th>Catatan Dokter</th> -->
                              <th>Catatan</th>
                              <th>Petugas</th>
                              <th>Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $no=1;
                              $riw=$koneksi->query("SELECT * FROM ctt_penyakit_inap WHERE norm='$_GET[id]'");
                              ?>
                          <?php foreach ($riw as $pecah) : ?>
                          <tr>
                              <td><?php echo $no; ?></td>
                              <td style="margin-top:10px;"><?php echo $pecah["tgl"]; ?></td>
                              <!-- <td style="margin-top:10px;"><?php echo $pecah["ctt_dokter"]; ?></td> -->
                              <td style="margin-top:10px;"><?php echo $pecah["ctt_tedis"]; ?></td>
                              <td style="margin-top:10px;"><?php echo $pecah["petugas"]; ?></td>                      
                              <td style="margin-top:10px;">
                                <?php if ($pecah['petugas'] === $petugas) { ?>
                                    <a href="index.php?halaman=cttpenyakit&id=<?= $_GET['id']?>&tgl=<?= $_GET['tgl']?>&idcct=<?= $pecah['id']?>&ubah" class="btn btn-success btn-sm">Edit</a>
                                <?php } ?>
                            </td>
                              <!-- <td> -->
                              <!-- <div class="dropdown">
                                <i data-bs-toggle="dropdown" style="color: blue; font-weight: bold; font-size: 20px;" class="bi bi-three-dots-vertical"></i>
                                <ul class="dropdown-menu">
                                
                                <li><a href="index.php?halaman=falanak&id=<?php echo $pecah["id"]; ?>&detail" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-eye-fill" style="color:darkblue;"></i> Detail</a></li>
                                <li><a href="index.php?halaman=hapusigd&id=<?php echo $pecah["id"]; ?>" class="dropdown-item" style="text-decoration: none; font-weight: bold; margin-left: 2px;" onclick="return confirm('Anda yakin mau menghapus item ini ?')">
                                <i class="bi bi-trash" style="color:red;"></i> Hapus</a></li>
                                </ul>
                                </div> -->
                              <!-- </td> -->
                          </tr>
                          <?php $no +=1 ?>
                          <?php endforeach ?>
                        </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <form class="row g-3" method="post" enctype="multipart/form-data">
          <div class="container">
            <!-- End Page Title -->
            <!-- <?= $start ?> -->
              <div class="container">
                <div class="row">
                    <div class="col-md-12">
                      <div class="card" style="margin-top:10px">
                          <div class="card-body col-md-12">
                            <h5 class="card-title">Data Pasien</h5>
                            <!-- Multi Columns Form -->
                            <div class="row">
                                <div class="col-md-6">
                                  <label for="inputName5" class="form-label">Nama Pasien</label>
                                  <input type="text" class="form-control" name="pasien" id="inputName5" value="<?php echo $pasien['nama_lengkap']?>" placeholder="Masukkan Nama Pasien" readonly>
                                </div>
                                <div class="col-md-6" style="margin-bottom:20px;">
                                  <label for="inputName5" class="form-label">No RM</label>
                                  <input type="text" class="form-control" id="inputName5" name="jadwal" value="<?php echo $pasien['no_rm']?>" placeholder="Masukkan Nama Pasien" readonly>
                                </div>
                                <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                                  <label for="inputName5" class="form-label">Tanggal Lahir</label>
                                  <input type="text" class="form-control" id="inputName5" name="jadwal" value="<?php echo date("d-m-Y", strtotime($pasien['tgl_lahir']))?>" placeholder="Masukkan Nama Pasien" readonly>
                                </div>
                                <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                                  <label for="inputName5" class="form-label">Alamat</label>
                                  <input type="text" class="form-control" id="inputName5" name="jadwal" value="<?php echo $pasien['alamat']?>" placeholder="Masukkan Nama Pasien" readonly>
                                </div>
                                <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                                  <label for="inputName5" class="form-label">Ruangan</label>
                                  <input type="text" class="form-control" id="inputName5" name="kamar" value="<?php echo $jadwal['kamar']?>" placeholder="Masukkan Nama Pasien">
                                </div>
                                <?php if($pasien["jenis_kelamin"] == 1){ ?>
                                <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                                  <label for="inputName5" class="form-label">Jenis Kelamin</label>
                                  <input type="text" class="form-control" id="inputName5" name="jadwal" value="Laki-laki" placeholder="Masukkan Nama Pasien" readonly>
                                </div>
                                <?php }else{ ?>
                                <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                                  <label for="inputName5" class="form-label">Jenis Kelamin</label>
                                  <input type="text" class="form-control" id="inputName5" name="jadwal" value="Perempuan" placeholder="Masukkan Nama Pasien" readonly>
                                </div>
                                <?php } ?>
                            </div>
                          </div>
                      </div>
                      <div class="card" >
                          <div class="card-body">
                            <div style="margin-bottom:1px; margin-top:30px">
                                <h6 class="card-title">Hasil Pemeriksaan, Analisa & Rencana Penatalaksanaan Pasien </h6>
                            </div>
                            <p>(Instruksi ditulis dengan rinci dan jelas)</p>
                            <!-- Multi Columns Form -->
                            <div class="row">
                                <div class="col-md-12">
                                  <label for="inputName5" class="form-label">Tgl & Jam</label>
                                  <input type="datetime" class="form-control" id="inputName5" name="tgl" value="<?= date("Y-m-d H:i:s")?>">
                                </div>
                                <div class="col-md-12" style="margin-top:20px;">
                                  <label for="inputName5" class="form-label">Catatan</label>
                                  <textarea name="ctt_tedis" id="" style="width:100%; height:150px"></textarea>
                                </div>
                                <div class="col-md-12" style="margin-top:20px;">
                                  <label for="inputName5" class="form-label">Petugas</label>
                                  <input name="petugas" id="" class="form-control" value="<?php echo $petugas ?>" disabled></input>
                                </div>
                            </div>
                          </div>
                      </div>
                    </div>
                </div>
              </div>
          </div>
          <div class="text-center" style="margin-top: -10px; margin-bottom: 40px;">
            <button type="submit" name="save" class="btn btn-primary">Simpan</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
          </div>
        </form>
      <?php }else{?>
        <?php $ctt = $koneksi->query("SELECT * FROM ctt_penyakit_inap WHERE id = '$_GET[idcct]'")->fetch_assoc();?>
        <form class="row g-3" method="post" enctype="multipart/form-data">
          <div class="container">
            <!-- End Page Title -->
            <!-- <?= $start ?> -->
              <div class="container">
                <div class="row">
                    <div class="col-md-12">
                      <div class="card" style="margin-top:10px">
                          <div class="card-body col-md-12">
                            <h5 class="card-title">Data Pasien</h5>
                            <!-- Multi Columns Form -->
                            <div class="row">
                                <div class="col-md-6">
                                  <label for="inputName5" class="form-label">Nama Pasien</label>
                                  <input type="text" class="form-control" name="pasien" id="inputName5" value="<?php echo $pasien['nama_lengkap']?>" placeholder="Masukkan Nama Pasien" readonly>
                                </div>
                                <div class="col-md-6" style="margin-bottom:20px;">
                                  <label for="inputName5" class="form-label">No RM</label>
                                  <input type="text" class="form-control" id="inputName5" name="jadwal" value="<?php echo $pasien['no_rm']?>" placeholder="Masukkan Nama Pasien" readonly>
                                </div>
                                <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                                  <label for="inputName5" class="form-label">Tanggal Lahir</label>
                                  <input type="text" class="form-control" id="inputName5" name="jadwal" value="<?php echo date("d-m-Y", strtotime($pasien['tgl_lahir']))?>" placeholder="Masukkan Nama Pasien" readonly>
                                </div>
                                <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                                  <label for="inputName5" class="form-label">Alamat</label>
                                  <input type="text" class="form-control" id="inputName5" name="jadwal" value="<?php echo $pasien['alamat']?>" placeholder="Masukkan Nama Pasien" readonly>
                                </div>
                                <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                                  <label for="inputName5" class="form-label">Ruangan</label>
                                  <input type="text" class="form-control" id="inputName5" name="kamar" value="<?php echo $jadwal['kamar']?>" placeholder="Masukkan Nama Pasien">
                                </div>
                                <?php if($pasien["jenis_kelamin"] == 1){ ?>
                                <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                                  <label for="inputName5" class="form-label">Jenis Kelamin</label>
                                  <input type="text" class="form-control" id="inputName5" name="jadwal" value="Laki-laki" placeholder="Masukkan Nama Pasien" readonly>
                                </div>
                                <?php }else{ ?>
                                <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                                  <label for="inputName5" class="form-label">Jenis Kelamin</label>
                                  <input type="text" class="form-control" id="inputName5" name="jadwal" value="Perempuan" placeholder="Masukkan Nama Pasien" readonly>
                                </div>
                                <?php } ?>
                            </div>
                          </div>
                      </div>
                      <div class="card" >
                          <div class="card-body">
                            <div style="margin-bottom:1px; margin-top:30px">
                                <h6 class="card-title">Hasil Pemeriksaan, Analisa & Rencana Penatalaksanaan Pasien </h6>
                            </div>
                            <p>(Instruksi ditulis dengan rinci dan jelas)</p>
                            <!-- Multi Columns Form -->
                            <div class="row">
                                <div class="col-md-12">
                                  <label for="inputName5" class="form-label">Tgl & Jam</label>
                                  <input type="datetime" class="form-control" id="inputName5" name="tgl" value="<?= $ctt['tgl']?>">
                                </div>
                                <div class="col-md-12" style="margin-top:20px;">
                                  <label for="inputName5" class="form-label">Catatan</label>
                                  <textarea name="ctt_tedis" id="" style="width:100%; height:150px"><?= $ctt['ctt_tedis']?></textarea>
                                </div>
                                <div class="col-md-12" style="margin-top:20px;">
                                  <label for="inputName5" class="form-label">Petugas</label>
                                  <input name="petugas" id="" class="form-control" value="<?php echo $petugas ?>" disabled></input>
                                </div>
                            </div>
                          </div>
                      </div>
                    </div>
                </div>
              </div>
          </div>
          <div class="text-center" style="margin-top: -10px; margin-bottom: 40px;">
            <button type="submit" name="update" class="btn btn-success">Ubah</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
          </div>
        </form>
      <?php }?>
      
   </main>
   <!-- End #main -->
   <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
</body>

</html>


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

<script>
var myModal = document.getElementById('myModal');

</script>

<script type="text/javascript">
    $(document).ready(function(){
      refreshTable();
    });

    function refreshTable(){
        $('#userList').load('rmedis.php', function(){
           setTimeout(refreshTable, 1000);
        });
    }
</script>


<?php 
if (isset ($_POST['update'])) 
{
  //  $koneksi->query("INSERT INTO ctt_penyakit_inap(tgl, norm, ctt_dokter, ctt_tedis, petugas, kamar, pasien) VALUES ('$_POST[tgl]', '$_GET[id]','$_POST[ctt_dokter]', '$_POST[ctt_tedis]', '$petugas', '$_POST[kamar]', '$_POST[pasien]')");

  $koneksi->query("UPDATE ctt_penyakit_inap SET tgl='$_POST[tgl]', norm='$_GET[id]', ctt_dokter='$_POST[ctt_dokter]', ctt_tedis='$_POST[ctt_tedis]', petugas='$petugas', kamar='$_POST[kamar]', pasien='$_POST[pasien]' WHERE id = '$_GET[idcct]'");

  $koneksi->query("UPDATE registrasi_rawat SET kamar='$_POST[kamar]' WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]'");
  echo "
    <script>
      alert('Data berhasil diubah');
      document.location.href='index.php?halaman=cttpenyakit&id=$_GET[id]&tgl=$_GET[tgl]';
    </script>
  ";
} 

if (isset ($_POST['save'])) 
{
   $koneksi->query("INSERT INTO ctt_penyakit_inap(tgl, norm, ctt_dokter, ctt_tedis, petugas, kamar, pasien) VALUES ('$_POST[tgl]', '$_GET[id]','$_POST[ctt_dokter]', '$_POST[ctt_tedis]', '$petugas', '$_POST[kamar]', '$_POST[pasien]')");

  // $koneksi->query("UPDATE ctt_penyakit_inap SET tgl='$_POST[tgl]', norm='$_GET[id]', ctt_dokter='$_POST[ctt_dokter]', ctt_tedis='$_POST[ctt_tedis]', petugas='$petugas', kamar='$_POST[kamar]', pasien='$_POST[pasien]'");
  $koneksi->query("UPDATE registrasi_rawat SET kamar='$_POST[kamar]' WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]'");
  echo "
    <script>
      alert('Data berhasil ditambah');
      document.location.href='index.php?halaman=cttpenyakit&id=$_GET[id]&tgl=$_GET[tgl]';
    </script>
  ";
} 




?>