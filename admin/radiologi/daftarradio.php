<?php 

$pasien=$koneksi->query("SELECT * FROM radiologi;"); 

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
      <h1>Daftar Radiologi</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php?halaman=daftarradio" style="color:blue;">Radiologi</a></li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

      <section class="section  py-4">
        <div class="container">

              
          <div class="row">
            <div class="col-lg-12 col-md-12">

            <div class="card">
            <div class="card-body">
                 
              <h5 class="card-title">Data Radiologi</h5>

              <!-- Multi Columns Form -->
            <div class="table-responsive">
            <table id="myTable" class="table table-striped" style="width:100%">
            <thead>
            <tr>
                <th>No</th>
                <th>No RM</th>
                <th>Nama Pasien</th>
                <th>Dokter Pengirim</th>
                <th>No Permintaan</th>
                <th>Nama Pemeriksaan</th>
                <th>Waktu Permintaan</th>
                <th></th>
                <!-- <th>Aksi</th> -->
               
            </tr>
            </thead>
           <tbody>

        <?php $no=1 ?>

        <?php foreach ($pasien as $pecah) : ?>

        <tr>
            <td><?php echo $no; ?></td>
            <td style="margin-top:10px;"><?php echo $pecah["no_rm"]; ?></td>
            <td style="margin-top:10px;"><?php echo $pecah["nama_pasien"]; ?></td>
            <td style="margin-top:10px;"><?php echo $pecah["dokter_pengirim_radio"]; ?></td>
            <td style="margin-top:10px;"><?php echo $pecah["no_permintaan_radio"]; ?></td>
            <td style="margin-top:10px;"><?php echo $pecah["nama_pemeriksaan_radio"]; ?></td>
            <td style="margin-top:10px;"><?php echo $pecah["waktu_permintaan_radio"]; ?></td>
            <td>
            <div class="dropdown">
             <i data-bs-toggle="dropdown" style="color: blue; font-weight: bold; font-size: 20px;" class="bi bi-three-dots-vertical"></i>
             <ul class="dropdown-menu">
             <li><a href="index.php?halaman=detailradio&id=<?php echo $pecah["idradio"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-eye-fill" style="color:darkblue;"></i> Detail</a></li>
             <li><a href="index.php?halaman=hapusradio&id=<?php echo $pecah["idradio"]; ?>" class="dropdown-item" style="text-decoration: none; font-weight: bold; margin-left: 2px;" onclick="return confirm('Anda yakin mau menghapus item ini ?')">
                <i class="bi bi-trash" style="color:red;"></i> Hapus</a></li>
             </ul>
            </div>
            </td>
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
         search: true,
         pagination: true
        } );
    } );
</script>
