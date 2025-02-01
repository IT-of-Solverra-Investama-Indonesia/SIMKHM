<?php 

$id=$_GET['id'];

$ambil=$koneksi->query("SELECT * FROM lab_hasil JOIN daftartes WHERE id_lab_h='$id' AND nama_periksa=nama_tes");
$ambil2=$koneksi->query("SELECT * FROM lab_hasil JOIN lab WHERE id_lab_h='$id'");
$pecah2=$ambil2->fetch_assoc();

$lab=$koneksi->query("SELECT * FROM lab_hasil WHERE id_lab_h='$_GET[id]';");
$lab=$lab->fetch_assoc();





?>


 <!-- !-- DataTables  -->

<!-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css"> -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="../bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>

<br>
<br>
<br>

<div class="container">
      <div class="pagetitle">
      <h1>Detail Data Laboratorium</h1>
</div><!-- End Page Title -->
<br>


<a href="../lab/printlab.php?id=<?php echo $lab["id_lab_h"]; ?>" target="_blank" class="btn btn-success" style="width:80px; height:40px">Print</a>
<br>
<br>
<div class="table-responsive">
    <!-- <h3><?php echo $pecah2["pasien"]; ?></h3> -->
    <table class="table" style="width:100%;" id="myTable" >
    <thead>
        <tr>
            <th><?php echo $pecah2["pasien"]; ?></th>
            <th>Tipe</th>
            <th>Pemeriksaan</th>
            <th>Hasil</th>
            <th>Nilai Normal</th>
            <!-- <th>Aksi</th> -->

        </tr>
        

    </thead>

    <tbody>

            <?php while ($pecah=$ambil->fetch_assoc())  { ?>
        <tr>
            <td></td>
            <td> <?php echo $pecah["tipe"]; ?></td>
            <td>
                <?php echo $pecah["nama_periksa"]; ?>  
            </td>
            <td><?php echo $pecah["hasil_periksa"]; ?></td>
            <td><?php echo $pecah["indikator"]; ?></td>
            <!-- <td>
             <li><a href="index.php?halaman=hapusdetaillab&id=<?php echo $pecah["idhasil"]; ?>" class="btn-sm btn-danger" style="text-decoration: none; font-weight: bold; margin-left: 2px;" onclick="return confirm('Anda yakin mau menghapus item ini ?')">
                 Hapus</a></li>


            </td> -->

        </tr>
        <?php } ?>

    </tbody>

</table>
</div>



<script>
    $(document).ready(function() {
        $('#myTable').DataTable( {
       
        paging: true,
        pageLength: 50,
        lengthChange: true,
        lengthMenu: [[10, 50, 25, 100, 300, -1], [10, 25, 100, 300, "All"]]
        } );
    } );

</script> 