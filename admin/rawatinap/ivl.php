
<?php 
error_reporting(0);
$date= date("Y-m-d");
date_default_timezone_set('Asia/Jakarta');

$username=$_SESSION['admin']['username'];
$petugas=$_SESSION['admin']['namalengkap'];
$ambil=$koneksi->query("SELECT * FROM admin  WHERE username='$username';");

$pasien=$koneksi->query("SELECT * FROM pasien  WHERE no_rm='$_GET[id]';")->fetch_assoc();

if(!isset($_GET['igd'])){
  $jadwal=$koneksi->query("SELECT * FROM registrasi_rawat  WHERE no_rm='$_GET[id]' AND date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' ")->fetch_assoc();
}

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
      <h1>SURVEILANCE PEMAKAIAN ALAT INVASIF IVL (INTRA VENA LINE)</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active"><a href="index.php?halaman=daftarrmedis" style="color:blue;">Rekam Medis</a></li>
          <li class="breadcrumb-item">Invasif IVL</li>
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
                
            <h6 class="card-title">SURVEILANCE PEMAKAIAN ALAT INVASIF IVL (INTRA VENA LINE) </h6></div>
              <!-- Multi Columns Form -->
              <div class="row">
               <div class="col-md-12">
                  <label for="inputName5" class="form-label">Tgl MRS</label>
                  <input type="date" class="form-control" id="inputName5" name="tgl_mrs" value="<?= date("Y-m-d")?>">
                </div>
               
                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Diagnosa MRS</label>
                  <input type="text" name="diag_mrs" class="form-control">
                </div>
               
                 <div class="col-md-6" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Lokasi</label>
                  <select  id="pilihan" name="lokasi" id="" class="form-control">
                    <option value="" hidden>Pilih</option>
                    <option value="Metakarpal">Metakarpal</option>
                    <option value="Lengan">Lengan</option>
                    <option value="Cephalic">Cephalic</option>
                  </select>  
                </div>

                 <div class="col-md-6" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Ukuran</label>
                  <select  id="pilihan" name="ukuran" id="" class="form-control">
                    <option value="" hidden>Pilih</option>
                    <option value="18">18</option>
                    <option value="22">22</option>
                    <option value="20">20</option>
                    <option value="24">24</option>
                  </select>  
                </div>

                 <div class="col-md-6" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Jenis Cairan</label>
                  <select  name="cairan" id="" class="form-control">
                    <option value="" hidden>Pilih</option>
                    <option value="Hipotonis < 250">Hipotonis < 250</option>
                    <option value="Isotonis 250-375">Isotonis 250-375</option>
                    <option value="Hipertonis > 375">Hipertonis > 375</option>
                    <option value="Medicut / venflon">Medicut / venflon</option>
                  </select>  
                </div>

                 <div class="col-md-6" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Item Pencegahan Flebitis</label>
                  <select name="flebitis" id="" class="form-control">
                    <option value="" hidden>Pilih</option>
                    <option value="Pasang">Pasang</option>
                    <option value="Lepas">Lepas</option>
                    <option value="Masih ada alasan pemasangan">Masih ada alasan pemasangan</option>
                    <option value="Pemasangan dengan teknik aseptik">Pemasangan dengan teknik aseptik</option>
                    <option value="Fiksasi baik, bersih, tidak basah">Fiksasi baik, bersih, tidak basah</option>
                    <option value="Teknik, aseptik saat injeksi / sambung">Teknik, aseptik saat injeksi / sambung</option>
                    <option value="Menggunakan close system">Menggunakan close system</option>
                    <option value="Dilakukan desinfeksi sebelum injeksi">Dilakukan desinfeksi sebelum injeksi</option>
                    <option value="Tidak ada udara, bekuan darah (clothing)">Tidak ada udara, bekuan darah (clothing)</option>
                    <option value="Penggunaan jarum injeksi sekali pakai">Penggunaan jarum injeksi sekali pakai</option>
                  </select>
                </div>

                <div class="col-md-6" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Tanggal</label>
                  <input type="date" class="form-control" name="tgl">
                </div>
                 
                <div class="col-md-6" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Derajat</label>
                  <select name="derajat" id="" class="form-control">
                    <option value="" hidden>Pilih</option>
                    <option value="0 - Tidak ada nyeri, tidak kemerahan">Tidak ada nyeri, tidak kemerahan</option>
                    <option value="1 - Kemerahan pada area insersi dengan atau tanpa rasa sakit">Kemerahan pada area insersi dengan atau tanpa rasa sakit
                    </option>
                    <option value="2 - Nyeri di sertai kemerahan dan bengkak">Nyeri di sertai kemerahan dan bengkak</option>
                    <option value="3 - Nyeri pada area insersi dengan kemerahan, bengkak, pembentukan benjolan garis vena mengikuti arah proximal pembuluh darah">Nyeri pada area insersi dengan kemerahan, bengkak, pembentukan benjolan garis vena mengikuti arah proximal pembuluh darah</option>
                    <option value="4 - Nyeri pada area insersi dengan kemerahan, bengkak, pembentukan besar dan memanjang disertai drainase purulen">Nyeri pada area insersi dengan kemerahan, bengkak, pembentukan besar dan memanjang disertai drainase purulen</option>
                   </select>
                </div>

                <div class="col-md-12" style="margin-top:20px;">
                 <label for="inputName5" class="form-label">Keterangan</label>
                 <textarea name="ket" id="" style="width:100%; height:150px"></textarea>
               </div>
              

                <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Kepala Ruang</label>
                  <input name="kep_ruang" id="" class="form-control" value="<?php echo $petugas ?>"></input>
                </div>

                <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Nama IPCLN/PJ SHIFT</label>
                  <input name="shift" id="" class="form-control" value="<?php echo $petugas ?>"></input>
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
              <h5 class="card-title">Data Pasien Terpasang Infus</h5>

            <div class="table-responsive">
            <table id="myTable" class="table table-striped" style="width:100%">
            <thead>
            <tr>
                <th>No</th>
                <th>Lokasi</th>
                <th>Ukuran</th>
                <th>Jenis Cairan</th>
                <th>Item Pencegahan Flebitis</th>
                <th>Total Hari</th>
                <th>Ket</th>
                <!-- <th>Aksi</th> -->
               
            </tr>
            </thead>
           <tbody>

        <?php 
              $no=1;
              if(isset($_GET['igd'])){
                $riw=$koneksi->query("SELECT *, count(tgl) as total FROM ivl WHERE norm='$_GET[id]' AND status = 'igd' group by flebitis order by id asc");
              }else{
                $riw=$koneksi->query("SELECT *, count(tgl) as total FROM ivl WHERE norm='$_GET[id]' AND status = 'inap' group by flebitis order by id asc");
              }
        ?>

        <?php foreach ($riw as $pecah) : ?>

        <tr>
            <td><?php echo $no; ?></td>
            <td style="margin-top:10px;"><?php echo $pecah["lokasi"]; ?></td>
            <td style="margin-top:10px;"><?php echo $pecah["ukuran"]; ?></td>
            <td style="margin-top:10px;"><?php echo $pecah["cairan"]; ?></td>
            <td style="margin-top:10px;"><?php echo $pecah["flebitis"]; ?></td>
            <td style="margin-top:10px;"><?php echo $pecah["total"]; ?></td>
            <td style="margin-top:10px;"><?php echo $pecah["ket"]; ?></td>
        </tr>

        <?php $no +=1 ?>
        <?php endforeach ?>

            </tbody>
        </table>
        </div>

        <div class="table-responsive" style="margin-top: 40px;">
        <h5 class="card-title">Derajat</h5>

            <table id="myTable" class="table table-striped" style="width:100%">
            <thead>
            <tr>
                <th>No</th>
                <th>Derajat</th>
                <th>Total Hari</th>
                <th>Ket</th>
                <!-- <th>Aksi</th> -->
               
            </tr>
            </thead>
           <tbody>

        <?php 
            $no=1;
            if(isset($_GET['igd'])){
              $riwa=$koneksi->query("SELECT *, count(tgl) as total FROM ivl WHERE norm='$_GET[id]' AND status = 'igd' group by derajat order by id asc");
            }else{
              $riwa=$koneksi->query("SELECT *, count(tgl) as total FROM ivl WHERE norm='$_GET[id]' AND status = 'inap' group by derajat order by id asc");
            }
        ?>

        <?php foreach ($riwa as $pecah) : ?>

        <tr>
            <td><?php echo $no; ?></td>
            <td style="margin-top:10px;"><?php echo $pecah["derajat"]; ?></td>
            <td style="margin-top:10px;"><?php echo $pecah["total"]; ?></td>
            <td style="margin-top:10px;"><?php echo $pecah["ket"]; ?></td>
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
  if(isset($_GET['igd'])){
    $koneksi->query("INSERT INTO ivl(tgl, norm, tgl_mrs, diag_mrs, kamar, pasien, lokasi, ukuran, cairan, flebitis, ket, derajat, kep_ruang, shift, status) VALUES ('$_POST[tgl]', '$_GET[id]','$_POST[tgl_mrs]', '$_POST[diag_mrs]', '$_POST[kamar]', '$_POST[pasien]', '$_POST[lokasi]', '$_POST[ukuran]', '$_POST[cairan]', '$_POST[flebitis]', '$_POST[ket]', '$_POST[derajat]', '$_POST[kep_ruang]', '$_POST[shift]', 'igd')");
    echo "
      <script>
        alert('Data berhasil ditambah');
        document.location.href='index.php?halaman=ivl&id=$_GET[id]&igd';
      </script>
    ";
  }else{
    $koneksi->query("INSERT INTO ivl(tgl, norm, tgl_mrs, diag_mrs, kamar, pasien, lokasi, ukuran, cairan, flebitis, ket, derajat, kep_ruang, shift, status) VALUES ('$_POST[tgl]', '$_GET[id]','$_POST[tgl_mrs]', '$_POST[diag_mrs]', '$_POST[kamar]', '$_POST[pasien]', '$_POST[lokasi]', '$_POST[ukuran]', '$_POST[cairan]', '$_POST[flebitis]', '$_POST[ket]', '$_POST[derajat]', '$_POST[kep_ruang]', '$_POST[shift]', 'inap')");
    echo "
      <script>
        alert('Data berhasil ditambah');
        document.location.href='index.php?halaman=ivl&id=$_GET[id]&tgl=$_GET[tgl]';
      </script>
    ";
  }


    // $koneksi->query("UPDATE registrasi_rawat SET kamar='$_POST[kamar]' WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]'");


    
   


} 



?>