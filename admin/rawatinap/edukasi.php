
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

 

   <main>
    <div class="container">
      <div class="pagetitle">
      <h1>PEMBERIAN INFORMASI DAN EDUKASI</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active"><a href="index.php?halaman=daftarrmedis" style="color:blue;">Rekam Medis</a></li>
          <li class="breadcrumb-item">Pemberian Edukasi</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
<!-- <?= $start ?> -->
 <form class="row g-3" method="post" enctype="multipart/form-data">
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
                
            <h6 class="card-title">Pemberian Edukasi </h6></div>
              <!-- Multi Columns Form -->
              <div class="row">
               <div class="col-md-12">
                  <label for="inputName5" class="form-label">Tgl & Jam</label>
                  <input type="datetime" class="form-control" id="inputName5" name="tgl" value="<?= date("Y-m-d H:i:s")?>">
                </div>
               
                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Topik Edukasi</label>
                  <input type="text" name="topik" class="form-control">
                </div>
               
                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Penerima Edukasi</label>
                  <select  id="pilihan" name="penerima" id="" class="form-control">
                    <option value="" hidden>Pilih</option>
                    <option value="Pasien">Pasien</option>
                    <option value="Pasangan">Pasangan</option>
                    <option value="Orang Tua">Orang Tua</option>
                    <option value="Saudara Kandung">Saudara Kandung</option>
                    <option value="Lainnya">Lain-lain</option>
                  </select>  
                </div>
                <div class="hidden" id="lain" style="margin-top:5px">
                  <div class="row">
                    <div class="col-md-12">
                      <input type="text" class="form-control" value="" name="penerima" placeholder="Lainnya...">
                    </div>
                  </div>
                </div>
                <script>
                  document.getElementById('pilihan').addEventListener('change', function() {
                    var formLain = document.getElementById('lain');
                    if (this.value === 'Lainnya') {
                      formLain.classList.remove('hidden');
                    } else {
                      formLain.classList.add('hidden');
                    }
                  });
                </script>

                <style>
                    .hidden{
                    display : hidden;
                    height: 0px;
                    overflow: hidden;
                    }
                </style>

                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Hambatan</label>
                  <select name="hambatan" id="" class="form-control">
                    <option value="" hidden>Pilih</option>
                    <option value="Tidak Ada">Tidak Ada</option>
                    <option value="Pandangan terbatas">Pandangan terbatas</option>
                    <option value="Kognisi terbatas">Kognisi terbatas</option>
                    <option value="Pendengaran terbatas">Pendengaran terbatas</option>
                    <option value="Hambatan emosi">Hambatan emosi</option>
                    <option value="Keterbatasan fisik">Keterbatasan fisik</option>
                    <option value="Tidak bisa membaca">Tidak bisa membaca</option>
                    <option value="Pertimbangan budaya dalam perawatan">Pertimbangan budaya dalam perawatan</option>
                  </select>
                </div>

                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Metode</label>
                  <select name="metode" id="" class="form-control">
                    <option value="" hidden>Pilih</option>
                    <option value="Diskusi">Diskusi</option>
                    <option value="Leaflet">Leaflet</option>
                    <option value="Demonstrasi">Demonstrasi</option>
                   </select>
                </div>
                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Evaluasi</label>
                  <select name="evaluasi" id="" class="form-control">
                    <option value="" hidden>Pilih</option>
                    <option value="Memahami materi yang diberikan">Memahami materi yang diberikan</option>
                    <option value="Membutuhkan materi tambahan (tertulis)">Membutuhkan materi tambahan (tertulis)</option>
                    <option value="Membutuhkan edukasi ulang">Membutuhkan edukasi ulang</option>
                   </select>
                </div>
                

                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Edukator</label>
                  <input name="edukator" id="" class="form-control" value="<?php echo $petugas ?>"></input>
                </div>

                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Keterangan</label>
                  <textarea name="ket" id="" style="width:100%; height:150px"></textarea>
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


        <div class="container">
          <div class="row">
            <div class="col-md-12">

            <div class="card" style="margin-top:10px">
            <div class="card-body col-md-12">
              <h5 class="card-title">Data Edukasi Rawat Inap</h5>

              <div class="table-responsive">
            <table id="myTable" class="table table-striped" style="width:100%">
            <thead>
            <tr>
                <th>No</th>
                <th>Tgl&Jam</th>
                <th>Topik Edukasi</th>
                <th>Penerima Edukasi</th>
                <th>Hambatan</th>
                <th>Metode</th>
                <th>Evaluasi</th>
                <th>Edukator</th>
                <th>Keterangan</th>
                <!-- <th>Aksi</th> -->
               
            </tr>
            </thead>
           <tbody>

        <?php $no=1;
              $riw=$koneksi->query("SELECT * FROM edukasi WHERE norm='$_GET[id]'");
        ?>

        <?php foreach ($riw as $pecah) : ?>

        <tr>
            <td><?php echo $no; ?></td>
            <td style="margin-top:10px;"><?php echo $pecah["tgl"]; ?></td>
            <td style="margin-top:10px;"><?php echo $pecah["topik"]; ?></td>
            <td style="margin-top:10px;"><?php echo $pecah["penerima"]; ?></td>
            <td style="margin-top:10px;"><?php echo $pecah["hambatan"]; ?></td>
            <td style="margin-top:10px;"><?php echo $pecah["metode"]; ?></td>
            <td style="margin-top:10px;"><?php echo $pecah["evaluasi"]; ?></td>
            <td style="margin-top:10px;"><?php echo $pecah["edukator"]; ?></td>
            <td style="margin-top:10px;"><?php echo $pecah["ket"]; ?></td>
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

  </main><!-- End #main -->

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

if (isset ($_POST['save'])) 
{


   $koneksi->query("INSERT INTO edukasi(tgl, norm, topik, penerima, kamar, pasien, hambatan, metode, evaluasi, edukator, ket) VALUES ('$_POST[tgl]', '$_GET[id]','$_POST[topik]', '$_POST[penerima]', '$_POST[kamar]', '$_POST[pasien]', '$_POST[hambatan]', '$_POST[metode]', '$_POST[evaluasi]', '$_POST[edukator]', '$_POST[ket]')");

    $koneksi->query("UPDATE registrasi_rawat SET kamar='$_POST[kamar]' WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]'");


    echo "
    <script>

    alert('Data berhasil ditambah');

    document.location.href='index.php?halaman=edukasi&id=$_GET[id]&tgl=$_GET[tgl]';

    </script>

    ";
   


} 



?>