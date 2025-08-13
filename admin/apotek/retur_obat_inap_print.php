<?php
session_start();
include '../dist/function.php';
$getPetugasSingle = $koneksi->query("SELECT retur_obat_inap.*, obat_rm.obat_igd FROM retur_obat_inap INNER JOIN obat_rm ON obat_rm.idobat = retur_obat_inap.obat_rm_id WHERE idrawat = '" . htmlspecialchars($_GET['idrawat']) . "' AND tgl_retur = '" . htmlspecialchars($_GET['tgl']) . "' ORDER BY tgl_retur ASC LIMIT 1")->fetch_assoc();
$getRegistrasi = $koneksi->query("SELECT * FROM registrasi_rawat WHERE idrawat = '" . htmlspecialchars($_GET['idrawat']) . "'")->fetch_assoc();
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
        <h5 style="margin-bottom: 3px;">Retur <?= $getPetugasSingle['obat_igd'] ?> Tanggal <?= $tglObat = htmlspecialchars($_GET['tgl']) ?></h5>
    </center>
    <table style=" font-size: 10px;">
        <thead>
            <tr>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Nama</td>
                <td>: <?= $getRegistrasi['nama_pasien'] ?></td>
            </tr>
            <tr>
                <td>Jadwal</td>
                <td>: <?= $getRegistrasi['jadwal'] ?></td>
            </tr>
            <tr>
                <td>Kamar</td>
                <td>: <?= $getRegistrasi['kamar'] ?></td>
            </tr>
            <tr>
                <td>Petugas</td>
                <td>: <?= $getPetugasSingle['petugas'] == '' ? $_SESSION['admin']['namalengkap'] : $getPetugasSingle['petugas'] ?></td>
            </tr>
            <tr>
                <td>Shift</td>
                <td>: <?= $getPetugasSingle['shift'] == '' ? $_SESSION['shift'] : $getPetugasSingle['shift'] ?></td>
            </tr>
        </tbody>
    </table>
    <hr>
    <div style="font-size: 12px;">
        <?php
        $total = 0;
        $getData = $koneksi->query("SELECT retur_obat_inap.*, obat_rm.obat_igd FROM retur_obat_inap INNER JOIN obat_rm ON obat_rm.idobat = retur_obat_inap.obat_rm_id WHERE idrawat = '" . htmlspecialchars($_GET['idrawat']) . "' AND tgl_retur = '" . htmlspecialchars($_GET['tgl']) . "'");
        foreach ($getData as $data) {
        ?>
            <li style="margin-bottom: 5px;">
                <?= $data['nama_obat'] ?>
                <br>
                <?php
                $getPriceInDate = $koneksi->query("SELECT * FROM rawatinapdetail WHERE ket LIKE '%Retur%' AND ket LIKE '%$data[idretur]%' ORDER BY created_at DESC LIMIT 1")->fetch_assoc();
                $harga = $getPriceInDate['besaran'] / $data['jumlah_retur'];
            // $getPriceInDate = $koneksi->query("SELECT * FROM apotek WHERE tgl_beli <= '" . date('Y-m-d', strtotime($data['created_at'])) . "' AND id_obat = '$data[kode_obat]' ORDER BY tgl_beli DESC LIMIT 1")->fetch_assoc();
        ?>
                <?= $data['jumlah_retur'] ?> x Rp<?= number_format($harga, 0, 0, '.') ?> = Rp<?= number_format($harga * $data['jumlah_retur'], 0, 0, '.') ?>
            </li>
            <?php
            $total += $harga * $data['jumlah_retur'];
            ?>
        <?php } ?>
        <b>Total : Rp <?= number_format($total, 0, 0, '.') ?></b>
    </div>
    <script>
        window.print();
    </script>
</div>