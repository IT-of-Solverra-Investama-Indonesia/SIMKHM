<?php
$dokter_rawat = $_SESSION['dokter_rawat'];
$nama = '';
// if (isset($_GET['obat'])) {
//     $koneksi->query("UPDATE obat_rm SET verif_dokter = '" . $_SESSION['dokter_rawat'] . "' WHERE idobat =  '$_GET[obat]'");
//     echo "
//             <script>
//             alert('Berhasil');
//                 document.location.href='index.php?halaman=verif_obat';
//             </script>
//         ";
// }
if (isset($_GET['norm']) && isset($_GET['tgl'])) {



    $koneksi->query("UPDATE obat_rm SET verif_dokter = '" . $_SESSION['dokter_rawat'] . "' WHERE idrm = '$_GET[norm]'
    and tgl_pasien  =  '$_GET[tgl]'");
    echo "
            <script>
          alert('Berhasil');
                document.location.href='index.php?halaman=verif_obat';
            </script>
        ";
} else {
    $pasien = $koneksi->query("SELECT *, DATE_FORMAT(jadwal, '%Y-%m-%d') as tgl FROM registrasi_rawat 
        WHERE (status_antri='Pembayaran' or status_antri='Selesai') and perawatan ='Rawat Jalan' and dokter_rawat = '$dokter_rawat' order by idrawat desc;");
}
?>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
<link src="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css">
<script type="text/javascript" language="javascript"
    src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript"
    src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" language="javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" language="javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" language="javascript"
    src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" language="javascript"
    src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
<div class="container">
    <div class="pagetitle">
        <h1>Verifikasi Obat </h1>
        <?php
        echo $_SESSION['dokter_rawat']
            ?>
    </div>
    <br>
    <div class="class">
        <form method="post">

            <input type="hidden" name="all">

            <button class="btn btn-primary btn-sm" name="submit" class="col-xs-8">Data All</button>

        </form>

    </div>
    <div class="card p-3">

        <div class="table-responsive">
            <table class="table" id="myTable">
                <thead>
                    <tr>
                        <th>Pasien</th>
                        <th>dokter</th>
                        <th>Jadwal</th>
                        <th>Status</th>
                        <th>Obat</th>
                        <th>Verifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    if (isset($_POST['submit'])) {
                        $pasien = $koneksi->query("SELECT *, DATE_FORMAT(jadwal, '%Y-%m-%d') as tgl FROM registrasi_rawat 
                         WHERE (status_antri='Pembayaran' or status_antri='Selesai') and perawatan ='Rawat Jalan' order by idrawat desc;");
                    } else {
                        $pasien = $koneksi->query("SELECT *, DATE_FORMAT(jadwal, '%Y-%m-%d') as tgl FROM registrasi_rawat 
                          WHERE (status_antri='Pembayaran' or status_antri='Selesai') and perawatan ='Rawat Jalan' and dokter_rawat = '$dokter_rawat' order by idrawat desc;");
                    }
                    ;
                    ?>
                    <?php foreach ($pasien as $data) { ?>
                        <tr>
                            <td><?= $data['nama_pasien'] ?></td>
                            <td><?= $data['dokter_rawat'] ?></td>

                            <td><?= $data['jadwal'] ?></td>
                            <td><?= $data['status_antri'] ?></td>
                            <td>
                                <?php
                                $getObat = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$data[no_rm]'  AND DATE_FORMAT(tgl_pasien, '%Y-%m-%d') = DATE_FORMAT('$data[jadwal]', '%Y-%m-%d')");
                                ?>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Kode</th>
                                            <th>Jumlah</th>
                                            <th>Dosis</th>
                                            <th>Jenis</th>
                                            <th>Durasi</th>
                                            <!-- <th>Aksi</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($getObat as $obat) { ?>
                                            <tr>
                                                <td><?= $obat['nama_obat'] ?></td>
                                                <td><?= $obat['kode_obat'] ?></td>
                                                <td><?= $obat['jml_dokter'] ?></td>
                                                <td><?= $obat['dosis1_obat'] ?> x <?= $obat['dosis2_obat'] ?></td>
                                                <td><?= $obat['jenis_obat'] ?></td>
                                                <td><?= $obat['durasi_obat'] ?></td>
                                                <!-- <td>
                                                    <?php if ($obat['verif_dokter'] == '') { ?>
                                                        <a href="index.php?halaman=verif_obat&obat=<?= $obat['idobat'] ?>"
                                                            class="btn btn-primary btn-sm">Verif</a>
                                                    <?php } else { ?>
                                                        <?= $obat['verif_dokter'] ?>
                                                    <?php } ?>
                                                </td> -->

                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </td>
                            <td>
                                <?php
                                    $date = new DateTime($data['jadwal']);
                                    $tgl = date('Y-m-d', strtotime($data['jadwal']));
                                    $getVerif = $koneksi->query("SELECT * FROM obat_rm WHERE idrm = '$data[no_rm]'  AND DATE_FORMAT(tgl_pasien, '%Y-%m-%d') = DATE_FORMAT('$data[jadwal]', '%Y-%m-%d') order BY idobat DESC LIMIT 1")->fetch_assoc();

                                ?>
                                <?php if ($getVerif['verif_dokter'] != '') { ?>
                                    <?php echo $obat['verif_dokter']; ?>
                                <?php } else { ?>
                                    <a href="index.php?halaman=verif_obat&norm=<?= $data['no_rm'] ?>&tgl=<?= $data['tgl'] ?>"
                                        class="btn btn-primary btn-sm">Verifikasi Pasien</a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#myTable').DataTable({
            search: true,
            pagination: true
        });
    });
</script>