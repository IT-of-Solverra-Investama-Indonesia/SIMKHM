
<?php 

$id=$_GET['id'];

// $user=$_SESSION['login']['username'];

// $ambil=$koneksi->query("SELECT * FROM admin  WHERE username='$username';");
$ambil=$koneksi->query("SELECT * FROM registrasi_rawat WHERE idrawat='$id' ");
$pecah=$ambil->fetch_assoc();

$ambil2=$koneksi->query("SELECT * FROM daftartes GROUP BY tipe ");
// $pecah=$ambil->fetch_assoc();

$p=$koneksi->query("SELECT * FROM pasien WHERE no_rm='$_GET[rm]';")->fetch_assoc();

 ?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>LAB</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <script rel="javascript" type="text/javascript" href="js/jquery-1.11.3.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>

   <!-- !-- DataTables  -->

<!-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css"> -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

 

   <main>
    <div class="container">
      <div class="pagetitle">
      <h1>Isi Data Laboratorium</h1>
     
    </div><!-- End Page Title -->

    <br>
<form class="row" method="post" enctype="multipart/form-data">
   <div class="container">
          <div class="row">
            <div class="col-md-12">
              <h5><b>Data Pasien</b></h5>
            </div>
              <!-- Multi Columns Form -->
                <div class="col-md-12">
                  <label for="inputName5" class="form-label">Nama Pasien</label>
                  <input type="text" class="form-control" name="pasienlab[]" id="inputName5" value="<?php echo $pecah['nama_pasien']?>" placeholder="Masukkan Nama Pasien" style="width: 1000px;" >
                </div>
                <div class="col-md-12">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">No RM Pasien</label>
                  <input type="text" class="form-control" name="normlab[]" id="inputName5" value="<?php echo $_GET['rm']?>" placeholder="Masukkan Nama Pasien" style="width: 1000px;">
                </div>
                
                <input type="hidden" class="form-control" name="id_lab[]" id="inputName5" value="<?php echo $pecah['idrawat']?>" placeholder="Masukkan Nama Pasien" style="width: 1000px;">
                <input type="hidden" class="form-control" name="register_lab[]" id="inputName5" value="<?php echo $pecah['antrian']?>" placeholder="Masukkan Nama Pasien" style="width: 1000px;">
                <input type="hidden" class="form-control" name="dokter_lab[]" id="inputName5" value="<?php echo $pecah['dokter_rawat']?>" placeholder="Masukkan Nama Pasien" style="width: 1000px;">


                <input type="hidden" class="form-control" name="umur_lab[]" id="inputName5" value="<?php echo $p['umur']?>" placeholder="Masukkan Nama Pasien" style="width: 1000px;">
                <input type="hidden" class="form-control" name="alamat_lab[]" id="inputName5" value="<?php echo $p['alamat']?>" placeholder="Masukkan Nama Pasien" style="width: 1000px;">
                

              <hr>

  <div class="col-md-12" style="margin-top:20px;margin-bottom:20px">
      <h3>Data Kesehatan</h3>
            
    <div class="table-responsive">
    <table class="row-border" style="width:100%;">
    <thead>
        <tr>
            <th>&nbsp;&nbsp;Nama Pemeriksaan <b style="color:orangered;">(Pemilihan Harus Urut)</b></th>
            <th>&nbsp;&nbsp;Harga <b style="color:orangered;">(Harap Pilih Sesuai Dengan Pemilihan Nama Pemeriksaan)</b></th>
        </tr>
        

    </thead>

    <tbody>
      <?php while ($pecah=$ambil2->fetch_assoc())  { ?>
        <tr>
            <td>
                 &nbsp;&nbsp;<input type="checkbox" name="tipe_lab[]" onclick="changeCheckbox<?= $pecah['idtes']?>()" id="namaTest<?= $pecah['idtes']?>" value="<?php echo $pecah["tipe"]; ?>" style="margin-bottom: 15px; margin-top: 15px" >&nbsp;&nbsp;<?php echo $pecah["tipe"]; ?><br>
            </td>
            <td>
                 &nbsp;&nbsp;<input style="margin-bottom: 15px; margin-top: 15px" onclick="changeCheckbox<?= $pecah['idtes']?>()" id="harga<?= $pecah['idtes']?>" type="checkbox" name="biaya[]" value="<?php echo $pecah["harga_lab"]; ?>">&nbsp;&nbsp;Rp.<?php echo $pecah["harga_lab"]; ?>&nbsp;&nbsp;(<?php echo $pecah["tipe"]; ?>)<br>
            </td>

        </tr>
        <script>
          function changeCheckbox<?= $pecah['idtes']?>() {
            var checkbox<?= $pecah['idtes']?> = document.getElementById("namaTest<?= $pecah['idtes']?>");
            var harga<?= $pecah['idtes']?> = document.getElementById("harga<?= $pecah['idtes']?>");

            if (checkbox<?= $pecah['idtes']?>.checked) {
              harga<?= $pecah['idtes']?>.checked = true;
            } else {
              harga<?= $pecah['idtes']?>.checked = false;
            }
          }
        </script>
        <?php } ?>

    </tbody>

</table>
</div>
</div>

          <div class="text-center">
          <button class="btn btn-success" type="submit" name="save" id="checkBtn" style="text-align:center;">Simpan</button>
          </div>
          <br>
          <br>
       
</form><!-- End Multi Columns Form -->
     
          </div>
        </div>
        </div>
    
      </div>
     </div>
    </div>

<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
  border-height: 10px ;
}

</style>

<script type="text/javascript">
  $(document).ready(function () {
    $('#checkBtn').click(function() {
        checked = $("input[name='biaya[]']:checked").length;

        if(!checked) {
            alert("Biaya belum dipilih!");
            return false;
        }
    });
});
</script>


<script>
    $(document).ready(function() {
        $('#myTable').DataTable( {
       
        paging: false,
        searching: false,
        lengthChange: false,
        bInfo : false,
        order: true
        } );
    } );

  
</script> 


<?php  

if(!isset($_GET['inap'])){
    if (isset ($_POST['save'])) {
   
    $pasienlab=$_POST['pasienlab'];
    $normlab=$_POST['normlab'];
    $id_lab=$_POST['id_lab'];
    $tipe_lab=$_POST['tipe_lab'];
    $umur_lab=$_POST['umur_lab'];
    $register_lab=$_POST['register_lab'];
    $dokter_lab=$_POST['dokter_lab'];
    $alamat_lab=$_POST['alamat_lab'];
    $biaya=$_POST['biaya'];

 

    $jumlahFile = count($_POST['tipe_lab']);

    for ($i = 0; $i < $jumlahFile; $i++) {
        
        foreach ($_POST['pasienlab'] as $value3){
        foreach ($_POST['normlab'] as $value4){
        foreach ($_POST['id_lab'] as $value5){
        foreach ($_POST['umur_lab'] as $value9){
        foreach ($_POST['register_lab'] as $value6){
        foreach ($_POST['alamat_lab'] as $value7){
        foreach ($_POST['dokter_lab'] as $value8){

        $koneksi->query("INSERT INTO lab SET
            tipe_lab    = '$tipe_lab[$i]',
            pasienlab   = '$value3',
            normlab   = '$value4',
            id_lab   = '$value5',
            umur_lab   = '$value9',
            register_lab   = '$value6',
            alamat_lab   = '$value7',
            dokter_lab   = '$value8',
            biaya   = '$biaya[$i]'
            ;
        ");
       
      
      }
      }
      }
      }
    }
  }
    }
    }
  echo "<div class='alert alert-success'>Data Tersimpan</div>";

  echo "<meta http-equiv='refresh' content='1;url=index.php?halaman=daftarlab'>";

}
}else{
    if (isset ($_POST['save'])) {
    $pasienlab=$_POST['pasienlab'];
    $normlab=$_POST['normlab'];
    $id_lab_inap=$_POST['id_lab'];
    $tipe_lab=$_POST['tipe_lab'];
    // $umur_lab=$_POST['umur_lab'];
    // $register_lab=$_POST['register_lab'];
    // $dokter_lab=$_POST['dokter_lab'];
    // $alamat_lab=$_POST['alamat_lab'];
    $biaya=$_POST['biaya'];

 

    $jumlahFile = count($_POST['tipe_lab']);

    for ($i = 0; $i < $jumlahFile; $i++) {
        
        foreach ($_POST['pasienlab'] as $value3){
        foreach ($_POST['normlab'] as $value4){
        foreach ($_POST['id_lab'] as $value5){
        // foreach ($_POST['umur_lab'] as $value9){
        // foreach ($_POST['register_lab'] as $value6){
        // foreach ($_POST['alamat_lab'] as $value7){
        // foreach ($_POST['dokter_lab'] as $value8){

        $koneksi->query("INSERT INTO lab SET
            tipe_lab    = '$tipe_lab[$i]',
            pasienlab   = '$value3',
            normlab   = '$value4',
            id_lab_inap   = '$value5',
            -- umur_lab   = '$value9',
            -- register_lab   = '$value6',
            -- alamat_lab   = '$value7',
            -- dokter_lab   = '$value8',
            biaya   = '$biaya[$i]'
            ;
        ");
       
      
      }
      }
      }
      }
      echo "<div class='alert alert-success'>Data Tersimpan</div>";

      echo "<meta http-equiv='refresh' content='1;url=index.php?halaman=daftarlabinap'>";
    }
}
    
  


?>