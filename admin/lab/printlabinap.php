<?php
require '../dist/function.php';
error_reporting(0);

$id = $_GET['id'];
$tanggal = $_GET['tgl'];


$ambil = $koneksi->query("SELECT * FROM lab_hasil JOIN daftartes WHERE id_inap='$id' AND nama_periksa=nama_tes AND DATE(tgl_hasil)='$tanggal' ORDER BY idhasil");

$ambil2 = $koneksi->query("SELECT * FROM lab_hasil JOIN lab WHERE id_inap='$id' AND id_inap=id_lab_inap");
$pecah2 = $ambil->fetch_assoc();

$pasien = $koneksi->query("SELECT * FROM pasien WHERE no_rm='$pecah2[norm]'");
$pasien = $pasien->fetch_assoc();





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
<?php
$currentURL = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] : '';
$currentURL = strtolower($currentURL);

if (strpos($currentURL, 'wonorejo') !== false) {
    $alamat = "Jalan Raya Wonorejo No. 167 Kedungjajang, Lumajang";
    $nomor = "0822-3388-0001";
    $email = "husada.mulia@gmail.com";
    $namaInstansi = "Wonorejo";
} elseif (strpos($currentURL, 'klakah') !== false) {
    $alamat = "Jl. Raya Mlawang - Klakah Lumajang 67356";
    $nomor = "0812-3457-1010";
    $email = "husadamuliaklakah@gmail.com";
    $namaInstansi = "Tunjung";
} elseif (strpos($currentURL, 'tunjung') !== false) {
    $alamat = "Krajani Satu, Tunjung, Kec. Randuagung, Kabupaten Lumajang, Jawa Timur";
    $nomor = "0813-5555-0275";
    $email = "husadamuliatunjung@gmail.com";
    $namaInstansi = "Tunjung";
} else {
    $alamat = "Dsn. Sumber Eling RT.013 RW.003, Ds. Kunir Lor, Kec.Kunir, Kab. Lumajang";
    $nomor = "0822-3388-0001";
    $email = "husadamuliakunir@gmail.com";
    $namaInstansi = "Kunir";
}
?>
<span>
    <div style="float: left;"><img style=" width: 135px" src="../dist/assets/img/khm.png" /></div>
    <div style="margin-left: 140px; margin-top: 0.1px;">
        <large style="font-weight: bold; font-size:23px;">LABORATORIUM KLINIK</large><br>
        <large style="font-weight: bold; color: steelblue; font-size:23px;">HUSADA MULIA</large><br>
        <small><?php echo $alamat; ?> Telp: <?php echo $nomor; ?></small><br>
        <small>PENANGGUNG JAWAB: dr. AINUL INDRA JAYA</small><br>
    </div>
</span>

<hr style="height:3px; background-color: steelblue;">


<span>
    <div style="float: left; margin-right: 50px; margin-left: 15px;">
        <h5 style="font-weight:lighter;"><b>NAMA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</b> <?php echo $pecah2["pasien"]; ?></h5>
        <h5 style="font-weight:lighter;"><b>UMUR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</b> <?php echo $pasien["umur"]; ?></h5>
        <h5 style="font-weight:lighter;"><b>ALAMAT&nbsp;&nbsp;&nbsp;&nbsp;:</b> <?php echo $pasien["alamat"]; ?></h5>
    </div>

    <div style="float: left;">
        <h5 style="font-weight:lighter;"><b>REGISTER&nbsp;&nbsp;&nbsp;:</b> <?php echo $pecah2["norm"]; ?></h5>
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

            <?php foreach ($ambil as $pecah) : ?>
                <tr>
                    <td><?php echo $pecah["tipe"]; ?></td>
                    <td><?php echo $pecah["nama_periksa"]; ?></td>
                    <td><?php echo $pecah["hasil_periksa"]; ?></td>
                    <td><?php echo $pecah["indikator"]; ?></td>


                </tr>
            <?php endforeach ?>

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
        $('#myTable').DataTable({

            paging: false,
            searching: false,
            lengthChange: false,
            bInfo: false,
            order: true
        });
    });
</script>