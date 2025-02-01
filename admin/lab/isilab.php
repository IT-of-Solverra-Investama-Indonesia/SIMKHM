
<?php 

$id=$_GET['id'];

$user=$_SESSION['admin']['username'];

// $ambil=$koneksi->query("SELECT * FROM admin  WHERE username='$username';");
$ambil=$koneksi->query("SELECT * FROM lab WHERE id_lab='$id' ");
$pecah=$ambil->fetch_assoc();

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

<!-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css"> -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<br>
<br>
<br>
 

   <main>
    <div class="container">
      <div class="pagetitle">
      <h1>Isi Data Laboratorium</h1>
     
    </div><!-- End Page Title -->

<form class="row" method="post" enctype="multipart/form-data">
   <div class="container">
          <div class="row">
            <div class="col-md-12" >
              <h3>Data Pasien</h3>
            </div>
              <!-- Multi Columns Form -->
                <div class="col-md-12">
                  <label for="inputName5" class="form-label">Nama Pasien</label>
                  <input type="text" class="form-control" name="pasien[]" id="inputName5" value="<?php echo $pecah['pasienlab']?>" placeholder="Masukkan Nama Pasien" style="width: 1000px;" >
                </div>
                <div class="col-md-12">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">NO RM Pasien</label>
                  <input type="text" class="form-control" name="norm[]" id="inputName5" value="<?php echo $pecah['normlab']?>" placeholder="Masukkan Nama Pasien" style="width: 1000px;">
                </div>
                <input type="hidden" class="form-control" name="id_lab_h[]" id="inputName5" value="<?php echo $pecah['id_lab'] ?>" placeholder="Masukkan Nama Pasien"> 
                <input type="hidden" class="form-control" name="petugas[]" id="inputName5" value="<?php echo $user ?>" placeholder="Masukkan Nama Pasien">
      </div>
      
      <br>
      <br>  
    <div class="table-responsive">
    <table class="table" style="width:100%;" id="myTable" >
    <thead>
        <tr>
            <th>Tgl</th>
            <th>Nama Periksa</th>
            <th>Hasil</th>

        </tr>

    </thead>

    <tbody>

            <?php foreach($ambil as $pecah) : ?>
        <tr>
            <td><?php echo $pecah["tgl"]; ?></td>
           
            <td><?php echo $pecah['tipe_lab']; ?></td>

            <?php if($tipe = $pecah["tipe_lab"]) : ?>
            <td><?php  $ambil2=$koneksi->query("SELECT * FROM daftartes WHERE tipe='$tipe' ORDER BY idtes asc");

              while($perkat2=$ambil2->fetch_assoc()) {
               ?>

                 <input type="hidden" name="nama_periksa[]" value="<?php echo $perkat2['nama_tes']; ?>" ><?php echo $perkat2['nama_tes']; ?></input><br>
                 <input value="" name="hasil_periksa[]" ></input><br>
                 <br>

                 <?php } ?>  

                 <?php  $ambil=$koneksi->query("SELECT * FROM daftartes WHERE tipe='$tipe' ORDER BY idtes asc LIMIT 1");

              while($perkat=$ambil->fetch_assoc()) {
               ?>

                <input type="hidden" name="periksa_lab[]" value="<?php echo $perkat['tipe']; ?>" ></input><br>

                 <?php } ?>  
            </td>


            <?php endif ?>

        </tr>
        <?php endforeach ?>

      </tbody>

    </table>

      <?php 
      $result1 = $koneksi->query("SELECT sum(biaya) FROM lab WHERE id_lab='$_GET[id]'");

    while ($rows1 = mysqli_fetch_array($result1)) {
    ?> 
    <br>
    <br>
     <?php  $sum = $rows1['sum(biaya)']; 
        }

 ?></div>

        </div>
    </div>
    </div>
    </div>
    <br>
    <div class="text-center">
          <button class="btn btn-success" type="submit" name="save" style="text-align: center;">Simpan</button>
    </div>
    <br>
    <br>
</form>

      </div>
     </div>
    </div>

<script>
    $(document).ready(function() {
        $('#myTable').DataTable( {
       
        paging: true,
        pageLength: 50,
        lengthChange: true,
        lengthMenu: [[10, 50, 25, 100, 300, -1], [10, 25, 100, 300, "All"]],
        dom: 'B<"clear">lfrtip',
         buttons: [
               'excel'
            ]
        } );
    } );
</script> 


<?php  
if (isset ($_POST['save'])) {
   
    $hasil_periksa=$_POST['hasil_periksa'];
    $nama_periksa=$_POST['nama_periksa'];
    $petugas=$_POST['petugas'];
    $pasien=$_POST['pasien'];
    $norm=$_POST['norm'];
    $id_lab_h=$_POST['id_lab_h'];

    $periksa_lab=$_POST['periksa_lab'];

     $jumlahFile = count($_POST['hasil_periksa']);

    for ($i = 0; $i < $jumlahFile; $i++) {
        
        foreach ($_POST['pasien'] as $value3){
        foreach ($_POST['norm'] as $value4){
        foreach ($_POST['id_lab_h'] as $value6){
        foreach ($_POST['petugas'] as $value5){

        $koneksi->query("INSERT INTO lab_hasil SET
            hasil_periksa   = '$hasil_periksa[$i]',
            nama_periksa   = '$nama_periksa[$i]',
            pasien   = '$value3',
            norm   = '$value4',
            id_lab_h   = '$value6',
            petugas   = '$value5'
          
        ");
        }
      }
     }
    
    }
   }


       

// Serialize the array
// $array = serialize($periksa_lab);
 $periksa = implode(", ",$periksa_lab);


$koneksi->query("UPDATE biaya_rawat SET 
  
    biaya_lab='$sum',
    periksa_lab='".$periksa."'
    WHERE
    idregis='$_GET[id]' ");

// print_r($_POST['hasil_periksa']);

  echo "<div class='alert alert-success'>Data Tersimpan</div>";

  echo "<meta http-equiv='refresh' content='1;url=index.php?halaman=daftarlab'>";


}


?>