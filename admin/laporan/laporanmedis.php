<?php 

$pasien=$koneksi->query ("SELECT * FROM rekam_medis;");

$row=$koneksi->query ("SELECT icd, COUNT(icd) AS TOTAL FROM rekam_medis GROUP BY icd");
$total=$row->fetch_assoc();




?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>KHM WONOREJO</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
 <!-- DATATABLES -->
      <!-- !-- DataTables  -->

<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/…"></link>
<?php 

$pasien=$koneksi->query ("SELECT * FROM rekam_medis;");

$row=$koneksi->query ("SELECT icd, COUNT(icd) AS TOTAL FROM rekam_medis GROUP BY icd");
$total=$row->fetch_assoc();



?>



<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>KHM WONOREJO</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
 <!-- DATATABLES -->
      <!-- !-- DataTables  -->

<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
<link src="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css">
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>


</head>


 <body>
   <main>
    <div class="container">
      <div class="pagetitle">
      <h1>Laporan Medis</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php?halaman=daftarigd" style="color:blue;">Laporan</a></li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

      <section class="section  py-4">
        <div class="container">

              
          <div class="row">
            <div class="col-lg-12 col-md-12">

            <div class="card">
            <div class="card-body">
                 
              <h5 class="card-title">Data Laporan</h5>
             

              <form method="POST">

            <div class="ha">

                <div class="form-group row">
                    <div class="col-md-6 form-group">
                        <label for="date" class="col-form-label">Mulai Tanggal</label>
                        <input type="date" class="form-control input-sm" id="fromDate" name="mulai" required/><br>

                    </div>

                    <div class="col-md-6 form-group mt-md-0">
                        <label for="date" class="col-form-label">Hingga Tanggal</label>
                        <input type="date" class="form-control input-sm" id="toDate" name="tanggal" />

                    </div>

                    <div class="form-group">
                        <button type="submit" style="float:right;" class="btn btn-info" name="save" title="search">Filter Data</button>
                    </div>
                </div>
            </div><br>
        </form>


<?php 
if (isset ($_POST['save'])) 

{

$tanggal=$_POST['tanggal'];

$mulai=$_POST['mulai'];


$pasien=$koneksi->query("SELECT * FROM rekam_medis WHERE tgl_rm<='$tanggal' AND tgl_rm>='$mulai' group by id_rm order by id_rm desc ");

};

?>

              <!-- Multi Columns Form -->
            <div class="table-responsive">
            <table id="myTable" class="table table-striped" style="width:100%">
            <thead>
            <tr>
                <th>No</th>
                <th>Kode ICD 10</th>
                <th>Jenis Penyakit</th>
                <th>Perempuan</th>
                <th>Laki-laki</th>
                <th>0 - 7 Hari</th>
                <th>8 - 28 Hari</th>
                <th>29 Hari - 1 Tahun</th>
                <th>1 - 4 Tahun</th>
                <th>5 - 9 Tahun</th>
                <th>10 - 14 Tahun</th>
                <th>15 - 19 Tahun</th>
                <th>20 - 44 Tahun</th>
                <th>45 - 54 Tahun</th>
                <th>55 - 59 Tahun</th>
                <th>60 - 69 Tahun</th>
                <th>>= 70 Tahun</th>
                <th></th>
                <!-- <th>Aksi</th> -->
               
            </tr>
            </thead>
           <tbody>

        <?php $no=1 ?>

     <?php  if (isset ($_POST['save'])) {

    $result1 = $koneksi->query("SELECT icd, diagnosis, jk, umur_pasien, tipe_umur,count(icd), count(umur_pasien) FROM rekam_medis WHERE tgl_rm<='$tanggal' AND tgl_rm>='$mulai' GROUP BY  icd");

    while ($rows1 = mysqli_fetch_array($result1)) {
    ?>  
   
        <tr>
            <td><?php echo $no; ?></td>
            <td style="margin-top:10px;"><?php echo $rows1['icd']; ?></td>
            <td style="margin-top:10px;"><?php echo $rows1['diagnosis']; ?></td>
            <?php if ($rows1['jk'] === '2') : ?>
            <td style="margin-top:10px;"><?php echo $rows1['count(icd)']; ?></td>
            <?php else: ?>
            <td style="margin-top:10px;"> 0 </td>
            <?php endif ?>
            
            <?php if ($rows1['jk'] === '1') : ?>
            <td style="margin-top:10px;"><?php echo $rows1['count(icd)']; ?></td>
            <?php else: ?>
            <td style="margin-top:10px;"> 0 </td>
            <?php endif ?>
            
            <!--  -->
            <?php if ($rows1['umur_pasien'] <=7 and $rows1['umur_pasien'] >=0 and $rows1['tipe_umur'] == 'Hari' ) : ?>
            <td style="margin-top:10px;"><?php echo $rows1['count(umur_pasien)']; ?></td>
            <?php else: ?>
            <td style="margin-top:10px;"> 0 </td>
            <?php endif ?>

             <?php if ($rows1['umur_pasien'] <= 28 and $rows1['umur_pasien'] >=8 and $rows1['tipe_umur'] =='Hari') : ?>
            <td style="margin-top:10px;"><?php echo $rows1['count(umur_pasien)']; ?></td>
            <?php else: ?>
            <td style="margin-top:10px;"> 0 </td>
            <?php endif ?>

            <?php if ($rows1['umur_pasien'] <= 365 and $rows1['umur_pasien'] >= 29 and $rows1['tipe_umur'] == 'Hari'): ?>
            <td style="margin-top:10px;"><?php echo $rows1['count(umur_pasien)']; ?></td>
            <?php else: ?>
            <td style="margin-top:10px;"> 0 </td>
            <?php endif ?>

            <?php if ($rows1['umur_pasien'] <=4 and $rows1['umur_pasien'] >=1 and $rows1['tipe_umur'] == 'Tahun'): ?>
            <td style="margin-top:10px;"><?php echo $rows1['count(umur_pasien)']; ?></td>
            <?php else: ?>
            <td style="margin-top:10px;"> 0 </td>
            <?php endif ?>

            <?php if ($rows1['umur_pasien'] <=9 and $rows1['umur_pasien'] >=5 and $rows1['tipe_umur'] == 'Tahun'): ?>
            <td style="margin-top:10px;"><?php echo $rows1['count(umur_pasien)']; ?></td>
            <?php else: ?>
            <td style="margin-top:10px;"> 0 </td>
            <?php endif ?>

            <?php if ($rows1['umur_pasien'] <= 14 and $rows1['umur_pasien'] >= 10 and $rows1['tipe_umur'] == 'Tahun'): ?>
            <td style="margin-top:10px;"><?php echo $rows1['count(umur_pasien)']; ?></td>
            <?php else: ?>
            <td style="margin-top:10px;"> 0 </td>
            <?php endif ?>

            <?php if ($rows1['umur_pasien'] <= 19 and $rows1['umur_pasien'] >= 15 and $rows1['tipe_umur'] == 'Tahun'): ?>
            <td style="margin-top:10px;"><?php echo $rows1['count(umur_pasien)']; ?></td>
            <?php else: ?>
            <td style="margin-top:10px;"> 0 </td>
            <?php endif ?>

            <?php if ($rows1['umur_pasien'] <= 44 and $rows1['umur_pasien'] >= 20 and $rows1['tipe_umur'] == 'Tahun'): ?>
            <td style="margin-top:10px;"><?php echo $rows1['count(umur_pasien)']; ?></td>
            <?php else: ?>
            <td style="margin-top:10px;"> 0 </td>
            <?php endif ?>

            <?php if ($rows1['umur_pasien'] <= 54 and $rows1['umur_pasien'] >= 45 and $rows1['tipe_umur'] == 'Tahun'): ?>
            <td style="margin-top:10px;"><?php echo $rows1['count(umur_pasien)']; ?></td>
            <?php else: ?>
            <td style="margin-top:10px;"> 0 </td>
            <?php endif ?>


            <?php if ($rows1['umur_pasien'] <= 59 and $rows1['umur_pasien'] >= 55 and $rows1['tipe_umur'] == 'Tahun'): ?>
            <td style="margin-top:10px;"><?php echo $rows1['count(umur_pasien)']; ?></td>
            <?php else: ?>
            <td style="margin-top:10px;"> 0 </td>
            <?php endif ?>

             <?php if ($rows1['umur_pasien'] <= 69 and $rows1['umur_pasien'] >= 60 and $rows1['tipe_umur'] == 'Tahun'): ?>
            <td style="margin-top:10px;"><?php echo $rows1['count(umur_pasien)']; ?></td>
            <?php else: ?>
            <td style="margin-top:10px;"> 0 </td>
            <?php endif ?>

            <?php if ($rows1['umur_pasien'] >= 70 and $rows1['tipe_umur'] == 'Tahun'): ?>
            <td style="margin-top:10px;"><?php echo $rows1['count(umur_pasien)']; ?></td>
            <?php else: ?>
            <td style="margin-top:10px;"> 0 </td>
            <?php endif ?>

            
        </tr>

        <?php $no +=1 ?>
         <?php } 
    }?>

    </tbody>
        </table>
                    
            </div>
          </div>
        </div>
        </div>
    
            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


</body>

</html>

<script>
    $(document).ready(function() {
        $('#myTable').DataTable( {
            dom: 'Bfrtip',
            buttons: [
             'excel', 'pdf', 'print'
            ]

        } );
    } );
</script>