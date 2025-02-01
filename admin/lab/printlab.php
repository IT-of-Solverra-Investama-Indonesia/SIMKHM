<?php 
require '../dist/function.php';
error_reporting(0);

$id=$_GET['id'];

$ambil=$koneksi->query("SELECT * FROM lab_hasil JOIN daftartes WHERE id_lab_h='$id' AND nama_periksa=nama_tes");
$ambil2=$koneksi->query("SELECT * FROM lab_hasil JOIN lab WHERE id_lab_h='$id' AND id_lab_h=id_lab");
$pecah2=$ambil2->fetch_assoc();

$pasien=$koneksi->query("SELECT * FROM pasien WHERE no_rm='$pecah2[norm]'");
$pasien=$pasien->fetch_assoc();




?>


 <!-- !-- DataTables  -->

<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
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
<span>
<div style="float: left;"><img style=" width: 135px;" src="../dist/assets/img/khm.png" /></div>
<div style="margin-left: 140px; margin-top: 0.1px;">
<large style="font-weight: bold; font-size:23px;">LABORATORIUM KLINIK</large><br><large style="font-weight: bold; color: steelblue; font-size:23px;">HUSADA MULIA</large><br>
<small>Jl.Raya Wonorejo No. 167 Telp. (0334) 7714700 Wonorejo - Lumajang</small><br>
<small>PENANGGUNG JAWAB: dr. AINUL INDRA JAYA</small><br>
</div>
</span>

<hr style="height:3px; background-color: steelblue;">


<span>
<div style="float: left; margin-right: 50px; margin-left: 15px;"> <h5 style="font-weight:lighter;"><b>NAMA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</b> <?php echo $pecah2["pasien"]; ?></h5>
    <h5 style="font-weight:lighter;"><b>UMUR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</b> <?php echo $pasien["umur"]; ?></h5>
    <h5 style="font-weight:lighter;"><b>ALAMAT&nbsp;&nbsp;&nbsp;&nbsp;:</b> <?php echo $pasien["alamat"]; ?></h5>
</div>

 <div style="float: left;"><h5 style="font-weight:lighter;"><b>REGISTER&nbsp;&nbsp;&nbsp;:</b> <?php echo $pecah2["register_lab"]; ?></h5>
    <h5 style="font-weight:lighter;"><b>TANGGAL&nbsp;&nbsp;&nbsp;&nbsp;:</b> <?php echo $pecah2["tgl_hasil"]; ?></h5>
    <h5 style="font-weight:lighter;"><b>DOKTER&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</b> <?php echo $pecah2["dokter_lab"]; ?></h5>
</div>
</span>


<div class="table-responsive">
    <table class="display wrap" style="width:100%;" id="myTable">
    <thead>
        <tr>
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
            <td><?php echo $pecah["tipe"]; ?></td>
            <td><?php echo $pecah["nama_periksa"]; ?></td>
            <td><?php echo $pecah["hasil_periksa"]; ?></td>
            <td><?php echo $pecah["indikator"]; ?></td>
           

        </tr>
        <?php } ?>

    </tbody>

</table>
</div>
<br>
<br>
<br>



<div style="float: right;">
    <h4 style="margin-bottom: 80px;"><b>Petugas</b></h4>
    <h3 style="font-weight:lighter;"><?php echo $pecah2["petugas"]; ?></h3>
</div>



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