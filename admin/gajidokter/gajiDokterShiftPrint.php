<?php
require '../dist/function.php';
$uri = $_SERVER['REQUEST_URI'];
$segments = explode('/', trim($uri, '/'));
$folder = $segments[0];
$getUnit = $folder === 'wonorejo' ? 'KHM 1' : ($folder === 'klakah' ? 'KHM 2' : (
    $folder === 'tunjung' ? 'KHM 3' : (
        $folder === 'kunir' ? 'KHM 4' : 'KHM 1'
    )
));
?>


<?php
$date = htmlspecialchars($_GET['date']) ?? date('Y-m-d');
$unit = htmlspecialchars($_GET['unit']) ?? $getUnit;
$shift = (htmlspecialchars($_GET['shiftgaji']) ?? $_SESSION['shift']);
$getDokter = $koneksimaster->query("SELECT * FROM gajidokter WHERE tgl = '" . $date . "' AND shiftgaji = '" . (htmlspecialchars($_GET['shiftgaji']) ?? $_SESSION['shift']) . "' AND unit = '" . $unit . "' GROUP BY dokter ORDER BY idgaji DESC");
?>

<?php foreach ($getDokter as $dokter) { ?>
    <?php
    $single = $koneksimaster->query("SELECT * FROM gajidokter WHERE tgl = '" . $date . "' AND shiftgaji = '" . (htmlspecialchars($_GET['shiftgaji']) ?? $_SESSION['shift']) . "' AND unit = '" . $unit . "' AND dokter = '" . $dokter['dokter'] . "' ORDER BY idgaji DESC LIMIT 1")->fetch_assoc();
    ?>

    <style>
        body {
            font-family: monospace;
            /* letter-spacing: px; */
        }
    </style>
    <div style="max-width: 58mm; padding: 2mm;">
        <center>
            <img src="https://simkhm.id/wonorejo/admin/dist/assets/img/3.png" style="width: 25%; margin-bottom: 0px;" alt="">
            <h4>Rekap Gaji Dokter</h4>
        </center>
        <table style="font-size: 10px; margin-top: 0px;">
            <thead>
                <!-- <tr>
                    <th></th>
                    <th></th>
                </tr> -->
            </thead>
            <tbody>
                <tr>
                    <td>Dokter</td>
                    <td>: <b><?= $single['dokter'] ?></b></td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>: <?= $single['tgl'] ?></td>
                </tr>
                <tr>
                    <td>Shift</td>
                    <td>: <?= $single['shiftgaji'] ?></td>
                </tr>
                <tr>
                    <td>Kasir</td>
                    <td>: <?= $single['petugas'] ?></td>
                </tr>
                <tr>
                    <td>Unit</td>
                    <td>: <?= $single['unit'] ?></td>
                </tr>
            </tbody>
        </table>
        <br>
        <b style="font-size: 11px;">Detail</b>
        <?php
        $total = 0;
        $getData = $koneksimaster->query("SELECT * FROM gajidokter WHERE tgl = '" . $date . "' AND shiftgaji = '" . (htmlspecialchars($_GET['shiftgaji']) ?? $_SESSION['shift']) . "' AND unit = '" . $unit . "' AND dokter = '" . $dokter['dokter'] . "' ORDER BY idgaji DESC");
        foreach ($getData as $data) {
        ?>
            <li style="font-size: 11px; text-transform: capitalize;"><?= $data['akun'] ?> <br> Rp <?= number_format($data['besaran'], 0, ',', '.') ?> | <?= $data['ket'] ?></li>
            <?php $total += $data['besaran'] ?>
        <?php } ?>
        <p align="right" style=" margin-top: 5px;">
            <b style="font-size: 12px;">Total : Rp <?= number_format($total, 0, ',', '.') ?></b>

        </p>
        <script>
            // window.print();
        </script>
    </div>
<?php } ?>