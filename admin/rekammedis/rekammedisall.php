<?php error_reporting(0);?>
<div class="container">
      <div class="pagetitle">
      <?php if(isset($_GET['pemeriksaan'])){?>
        <h1>Riwayat Pemeriksaan</h1>
      <?php }else{?>
        <h1>Resume Rekam Medis</h1>
      <?php }?>
</div>
</div><!-- End Page Title -->

<?php if(isset($_GET['list'])){?>
    <?php $data=$koneksi->query("SELECT * FROM rekam_medis WHERE norm = '$_GET[list]' LIMIT 1")->fetch_assoc();?>
    <div class="card p-3">
        <center>
            <a href="index.php?halaman=rekammedisall" style="float: left;" class="btn btn-sm btn-dark" ><i class="bi bi-arrow-left"></i></a><br><br><h5>Daftar Resume <?= $data['nama_pasien']?></h5>
        </center>
        <br>
        <table class="table table-striped" id="myTablee">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Nomor RM</th>
                    <th>Jadwal</th>
                    <th>Dokter</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $noo = 1;
                    $getRm = $koneksi->query("SELECT *, DATE_FORMAT(jadwal, '%Y-%m-%d') as tgl FROM rekam_medis WHERE norm= '$_GET[list]'");
                    foreach ($getRm as $data) {
                ?>
                <tr>
                    <td><?= $noo++ ?></td>
                    <td><?= $data['nama_pasien']?></td>
                    <td><?= $data['norm']?></td>
                    <td><?= $data['jadwal']?></td>
                    <?php $dokter = $koneksi->query("SELECT * FROM registrasi_rawat WHERE jadwal = '$data[jadwal]'")->fetch_assoc();?>
                    <td><?= $dokter['dokter_rawat']?></td>
                    <td>
                        <?php 
                            $dataPasien = $koneksi->query("SELECT * FROM pasien WHERE nama_lengkap = '$data[nama_pasien]'")->fetch_assoc();
                            $getRawat = $koneksi->query("SELECT * FROM registrasi_rawat WHERE nama_pasien= '$dataPasien[nama_lengkap]' and jadwal='$data[jadwal]'")->fetch_assoc()
                        ?>
                        <?php if(!isset($_GET['pemeriksaan'])){?>
                            <a href="index.php?halaman=detailrm&id=<?= $data['norm']?>&all&tgl=<?= $data['tgl']?>&rawat=<?= $getRawat['idrawat']?>idrm=<?= $data['id_rm'] ?>" class="btn btn-primary"><i class="bi bi-eye"></i></a>
                            <a href="index.php?halaman=editrm&id=<?= $data['norm']?>&all&tgl=<?= $data['tgl']?>&rawat=<?= $getRawat['idrawat']?>" class="btn btn-warning"><i class="bi bi-pencil"></i></a>
                            <a href="../pasien/fal-risk.php?id=<?php echo $dataPasien["idpasien"]; ?>&kunjungan=<?= $getRawat['idrawat']?>" style="text-decoration: none; margin-left: 1px; font-weight: bold;" class="btn btn-success"><i class="bi bi-printer" style="color:white;"></i> Fall Risk</a>
                        <?php }else{?>
                            <a href="index.php?halaman=detailrm&pemeriksaan&id=<?= $data['norm']?>&all&tgl=<?= $data['tgl']?>&rawat=<?= $getRawat['idrawat']?>" class="btn btn-primary"><i class="bi bi-eye"></i></a>
                            <!-- <a href="index.php?halaman=editrm&pemeriksaan&id=<?= $data['norm']?>&all&tgl=<?= $data['tgl']?>&rawat=<?= $getRawat['idrawat']?>" class="btn btn-warning"><i class="bi bi-pencil"></i></a> -->
                        <?php }?>
                    </td>
                </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
<?php }else{?>
    <div class="card p-3">
        <table class="table table-striped" id="myTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>No RM</th>
                    <!-- <th>Jadwal</th> -->
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $no =1;
                    // $pasien=$koneksi->query("SELECT * FROM rekam_medis JOIN pasien");
                    $pasien=$koneksi->query("SELECT * FROM rekam_medis GROUP BY norm ORDER BY nama_pasien ASC"); 
                    foreach ($pasien as $data) {
                ?>  
                    <tr>
                        <td><?= $no++?></td>
                        <td><?= $data['nama_pasien']?></td>
                        <td><?= $data['norm']?></td>
                        <!-- <td><?= $data['jadwal']?></td> -->
                        <td>
                            <?php if(isset($_GET['pemeriksaan'])){?>
                                <a href="index.php?halaman=rekammedisall&pemeriksaan&list=<?= $data['norm']?>"class="btn btn-primary"><i class="bi bi-eye"></i></a>
                            <?php }else{?>
                                <a href="index.php?halaman=rekammedisall&list=<?= $data['norm']?>"class="btn btn-primary"><i class="bi bi-eye"></i></a>
                            <?php }?>
                            <!-- <a href="index.php?halaman=detailrm&id=<?= $data['norm']?>" class="btn btn-primary"><i class="bi bi-eye"></i></a> -->
                        </td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
<?php }?>
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
<script>
    $(document).ready(function() {
        $('#myTable').DataTable( {
           
        } );
    } );
    $(document).ready(function() {
        $('#myTablee').DataTable( {
            search: true,
            pagination: true,
            dom: 'lBfrtip',
            buttons: [
                'excel', 'print'
            ]
        } );
    } );
</script>