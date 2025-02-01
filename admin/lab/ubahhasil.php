
<?php 

$id=$_GET['id'];

$user=$_SESSION['admin']['username'];

// $ambil=$koneksi->query("SELECT * FROM admin  WHERE username='$username';");
$ambil=$koneksi->query("SELECT * FROM lab WHERE id_lab='$id' ");
$ambil2=$koneksi->query("SELECT * FROM lab_hasil WHERE id_lab_h='$id' ");
$pecah=$ambil2->fetch_assoc();

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
      <h1>Ubah Data Laboratorium</h1>
     
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
                  <input type="text" class="form-control" name="pasien[]" id="inputName5" value="<?php echo $pecah['pasien']?>" placeholder="Masukkan Nama Pasien" style="width: 1000px;" >
                </div>
                <div class="col-md-12">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">NO RM Pasien</label>
                  <input type="text" class="form-control" name="norm[]" id="inputName5" value="<?php echo $pecah['norm']?>" placeholder="Masukkan Nama Pasien" style="width: 1000px;">
                </div>

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

            <?php foreach($ambil2 as $pecah) : ?>
        <tr>
            <td><?php echo $pecah["tgl_hasil"]; ?></td>
           
            <td><input type="text" name="nama_periksa[]" value="<?php echo $pecah['nama_periksa']; ?>"></td>

            <td><input type="text" name="hasil_periksa[]" value="<?php echo $pecah['hasil_periksa']; ?>"></td>
            <input type="hidden" name="idhasil[]" value="<?php echo $pecah['idhasil']; ?>">



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
    $idhasil=$_POST['idhasil'];


     $jumlahFile = count($_POST['hasil_periksa']);

    for ($i = 0; $i < $jumlahFile; $i++) {
        
        $koneksi->query("UPDATE lab_hasil SET
            hasil_periksa   = '$hasil_periksa[$i]',
            nama_periksa   = '$nama_periksa[$i]' 
            WHERE idhasil = '$idhasil[$i]';
          
        ");
   }

// Serialize the array
// $array = serialize($periksa_lab);
//  $periksa = implode(", ",$nama_periksa);


// $koneksi->query("UPDATE biaya SET 
  
//     biaya_lab='$sum'
//     WHERE
//     id='$_GET[id]' ");

// print_r($_POST['hasil_periksa']);

  echo "<div class='alert alert-success'>Data Tersimpan</div>";

  echo "<meta http-equiv='refresh' content='1;url=index.php?halaman=daftarlab'>";


}


?>