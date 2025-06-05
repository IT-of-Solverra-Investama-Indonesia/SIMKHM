<?php
require '../dist/function.php';
$total = 0;
$getDataSingle = $koneksi->query("SELECT * FROM penjualan_internal WHERE nota = '" . htmlspecialchars($_GET['nota']) . "' ORDER BY id_penjualan DESC LIMIT 1")->fetch_assoc();
?>

<style>
    body {
        font-family: monospace;
        /* letter-spacing: px; */
    }
</style>
<div style="max-width: 58mm; padding: 2mm;">
    <center>
        <img src="https://simkhm.id/wonorejo/admin/dist/assets/img/3.png" style="width: 25%; margin-bottom: 10px; margin-bottom: 0px;" alt="">
        <h5>Penjualan Apotek <?= $getDataSingle['nota'] ?></h5>
    </center>
    <table style="font-size: 11px;">
        <thead>
            <tr>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Tanggal</td>
                <td>: <?= $getDataSingle['tgl_jual'] ?></td>
            </tr>
            <tr>
                <td>Petugas</td>
                <td>: <?= $getDataSingle['petugas'] ?></td>
            </tr>
            <tr>
                <td>Shift</td>
                <td>: <?= $getDataSingle['shift'] ?></td>
            </tr>
        </tbody>
    </table>
    <?php
    $getData = $koneksi->query("SELECT * FROM penjualan_internal WHERE nota = '" . htmlspecialchars($_GET['nota']) . "' ORDER BY id_penjualan DESC");
    foreach ($getData as $data) {
    ?>
        <li style="font-size: 10.5px; margin-top: 5px;">
            <?= $data['nama_obat'] ?><br>
            Rp <?= number_format($data['harga_umum'], 0, 0, '.') ?> - <?= $data['diskon_obat'] ?> x <?= $data['jumlah'] ?> : Rp <?= number_format(($data['harga_umum'] - $data['diskon_obat']) * $data['jumlah'], 0, 0, '.') ?>,-
        </li>
        <?php $total += (($data['harga_umum'] - $data['diskon_obat']) * $data['jumlah']) ?>
    <?php } ?>
    <p align="right"><b>Total : Rp <?= number_format($total, 0, 0, '.') ?>,-</b></p>
    <script>
        window.print();
    </script>
</div>