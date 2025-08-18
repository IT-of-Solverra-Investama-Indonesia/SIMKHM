<?php
require '../dist/function.php';
$tanggal = $_GET['tanggal'] ?? date('Y-m-d');
$shift =  ($_GET['shift'] ?? $_SESSION['shift']);
if ($shift == "''Pagi', 'Sore', 'Malam''") {
    $shift = "'Pagi', 'Sore', 'Malam'";
}
?>
<?php
$totalPoli = 0;
$totalRawatInapBPJS = 0;
$totalRawatInapNonBPJS = 0;
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
        <h5>Kasir Klinik Husada Mulia</h5>
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
                <td>: <?= $tanggal ?></td>
            </tr>
            <tr>
                <td>Shift</td>
                <td>: <?= $shift ?></td>
            </tr>
        </tbody>
    </table>
    <?php
    $totalPoli = 0;
    $totalRawatInapBPJS = 0;
    $totalRawatInapNonBPJS = 0;
    ?>
    <table class="table table-sm table-hover " style="font-size: 12px;">
        <thead>
            <tr>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="2"><b>Poli</b></td>
            </tr>
            <tr>
                <td>Biaya Poli Umum</td>
                <td>
                    <?php
                    $totalBiayaPoli = 0;
                    $totalLab = 0;
                    $getBiayaPoli = $koneksi->query("SELECT *, registrasi_rawat.kasir, registrasi_rawat.shift FROM registrasi_rawat INNER JOIN biaya_rawat ON biaya_rawat.idregis = registrasi_rawat.idrawat WHERE perawatan = 'Rawat Jalan' and (status_antri = 'Datang' or status_antri = 'Pembayaran' or status_antri = 'Selesai') and date_format(jadwal, '%Y-%m-%d') = '$tanggal' AND registrasi_rawat.shift IN (" . $shift . ") and (registrasi_rawat.carabayar = 'umum' OR registrasi_rawat.carabayar = 'malam' OR registrasi_rawat.carabayar = 'bpjs')  GROUP BY registrasi_rawat.idrawat");
                    foreach ($getBiayaPoli as $dataBiayaPoli) {
                        $biayaRawat = $koneksi->query("SELECT * FROM biaya_rawat WHERE idregis= '$dataBiayaPoli[idrawat]' ORDER BY id DESC LIMIT 1")->fetch_assoc();

                        $totalBiayaPoli += $biayaRawat['poli'];
                        $totalLab += ($biayaRawat['biaya_lab'] == '' ? 0 : $biayaRawat['biaya_lab']);
                    }
                    ?>
                    Rp<?= number_format($totalBiayaPoli, 0, 0, '.') ?>
                    <?php
                    $totalPoli += ($totalBiayaPoli);
                    // $totalPoli += ($totalLab);
                    ?>
                </td>
            </tr>
            <tr>
                <td>Biaya Poli Spesialis Anak dan Penyakit Dalam</td>
                <td>
                    <?php
                    $totalBiayaPoli = 0;
                    // $totalLab = 0;
                    $getBiayaPoli = $koneksi->query("SELECT *, registrasi_rawat.kasir, registrasi_rawat.shift FROM registrasi_rawat INNER JOIN biaya_rawat ON biaya_rawat.idregis = registrasi_rawat.idrawat WHERE perawatan = 'Rawat Jalan' and (status_antri = 'Datang' or status_antri = 'Pembayaran' or status_antri = 'Selesai') and date_format(jadwal, '%Y-%m-%d') = '$tanggal' AND registrasi_rawat.shift IN (" . $shift . ") and (registrasi_rawat.carabayar = 'spesialis anak' OR registrasi_rawat.carabayar = 'spesialis penyakit dalam') GROUP BY registrasi_rawat.idrawat");
                    // $getBiayaPoli = $koneksi->query("SELECT *, registrasi_rawat.kasir, registrasi_rawat.shift FROM registrasi_rawat INNER JOIN biaya_rawat ON biaya_rawat.idregis = registrasi_rawat.idrawat WHERE perawatan = 'Rawat Jalan' and (status_antri = 'Datang' or status_antri = 'Pembayaran' or status_antri = 'Selesai') and date_format(jadwal, '%Y-%m-%d') = '$tanggal' AND registrasi_rawat.shift = 'Pagi' and registrasi_rawat.carabayar LIKE '%spesialis%' GROUP BY registrasi_rawat.idrawat");
                    foreach ($getBiayaPoli as $dataBiayaPoli) {
                        $biayaRawat = $koneksi->query("SELECT * FROM biaya_rawat WHERE idregis= '$dataBiayaPoli[idrawat]' ORDER BY id DESC LIMIT 1")->fetch_assoc();

                        $totalBiayaPoli += $biayaRawat['poli'];
                        $totalLab += ($biayaRawat['biaya_lab'] == '' ? 0 : $biayaRawat['biaya_lab']);
                    }
                    // 
                    ?>
                    Rp<?= number_format($totalBiayaPoli, 0, 0, '.') ?>
                    <?php
                    $totalPoli += ($totalBiayaPoli);
                    // $totalPoli += ($totalLab);
                    ?>
                </td>
            </tr>
            <tr>
                <td>Biaya Poli Gigi</td>
                <td>
                    <?php
                    $totalBiayaPoli = 0;
                    // $totalLab = 0;
                    $getBiayaPoli = $koneksi->query("SELECT *, registrasi_rawat.kasir, registrasi_rawat.shift FROM registrasi_rawat INNER JOIN biaya_rawat ON biaya_rawat.idregis = registrasi_rawat.idrawat WHERE perawatan = 'Rawat Jalan' and (status_antri = 'Datang' or status_antri = 'Pembayaran' or status_antri = 'Selesai') and date_format(jadwal, '%Y-%m-%d') = '$tanggal' AND registrasi_rawat.shift IN (" . $shift . ") and (registrasi_rawat.carabayar = 'gigi umum' OR registrasi_rawat.carabayar = 'gigi bpjs') GROUP BY registrasi_rawat.idrawat");
                    // $getBiayaPoli = $koneksi->query("SELECT *, registrasi_rawat.kasir, registrasi_rawat.shift FROM registrasi_rawat INNER JOIN biaya_rawat ON biaya_rawat.idregis = registrasi_rawat.idrawat WHERE perawatan = 'Rawat Jalan' and (status_antri = 'Datang' or status_antri = 'Pembayaran' or status_antri = 'Selesai') and date_format(jadwal, '%Y-%m-%d') = '$tanggal' AND registrasi_rawat.shift = 'Pagi' and registrasi_rawat.carabayar LIKE '%spesialis%' GROUP BY registrasi_rawat.idrawat");
                    foreach ($getBiayaPoli as $dataBiayaPoli) {
                        $biayaRawat = $koneksi->query("SELECT * FROM biaya_rawat WHERE idregis= '$dataBiayaPoli[idrawat]' ORDER BY id DESC LIMIT 1")->fetch_assoc();

                        $totalBiayaPoli += $biayaRawat['poli'];
                        $totalLab += ($biayaRawat['biaya_lab'] == '' ? 0 : $biayaRawat['biaya_lab']);
                    }
                    // 
                    ?>
                    Rp<?= number_format($totalBiayaPoli, 0, 0, '.') ?>
                    <?php
                    $totalPoli += ($totalBiayaPoli);
                    $totalPoli += ($totalLab);
                    ?>
                </td>
            </tr>
            <tr>
                <td>Biaya Lab</td>
                <td>Rp<?= number_format($totalLab, 0, 0, '.') ?></td>
            </tr>
            <?php
            $getLayanan = $koneksi->query("SELECT layanan, COUNT(*) as JumlahLayanan FROM layanan INNER JOIN registrasi_rawat ON registrasi_rawat.no_rm = layanan.idrm AND DATE_FORMAT(registrasi_rawat.jadwal, '%Y-%m-%d') = DATE_FORMAT(layanan.tgl_layanan, '%Y-%m-%d') WHERE perawatan = 'Rawat Jalan' AND DATE_FORMAT(tgl_layanan, '%Y-%m-%d') = '$tanggal' AND registrasi_rawat.shift IN (" . $shift . ") GROUP BY layanan");
            foreach ($getLayanan as $dataLayanan) {
            ?>
                <tr>
                    <td>Layanan/Tindakan <?= $dataLayanan['layanan'] ?></td>
                    <td>
                        <?php
                        $getHargaLayanan = $koneksimaster->query("SELECT * FROM master_layanan WHERE nama_layanan = '$dataLayanan[layanan]'")->fetch_assoc();
                        ?>
                        Rp<?= number_format(($getHargaLayanan['harga'] ?? 0) * $dataLayanan['JumlahLayanan'], 0, 0, '.') ?>
                    </td>
                    <?php $totalPoli += (($getHargaLayanan['harga'] ?? 0) * $dataLayanan['JumlahLayanan']); ?>
                </tr>
            <?php } ?>
            <tr>
                <td><b>Total Poli</b></td>
                <td><b>Rp<?= number_format($totalPoli, 0, 0, '.') ?></b></td>
            </tr>
            <tr>
                <td colspan="2">
                    <b>
                        <hr class="m-1">
                    </b>
                </td>
            </tr>
            <tr>
                <td colspan="2" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><b>Rawat Inap Non BPJS</b></td>
            </tr>
            <?php
            $getRawatinapNonBPJS = $koneksi->query("SELECT biaya, SUM(besaran) as harga FROM `pulang` INNER JOIN registrasi_rawat ON DATE_FORMAT(registrasi_rawat.jadwal, '%Y-%m-%d') = tgl_masuk AND registrasi_rawat.no_rm = pulang.norm INNER JOIN rawatinapdetail ON rawatinapdetail.id = registrasi_rawat.idrawat WHERE carabayar != 'bpjs' AND pulang.tgl = '$tanggal' AND pulang.shift IN (" . $shift . ") GROUP BY biaya ORDER BY biaya ASC");
            ?>
            <?php foreach ($getRawatinapNonBPJS as $rawatInapNonBPJS) { ?>
                <tr>
                    <td><?= $rawatInapNonBPJS['biaya'] ?></td>
                    <td>Rp<?= number_format($rawatInapNonBPJS['harga'], 0, 0, '.') ?></td>
                    <?php $totalRawatInapNonBPJS += $rawatInapNonBPJS['harga']; ?>
                </tr>
            <?php } ?>
            <tr>
                <td colspan=""><b>Total</b></td>
                <td colspan=""><b>Rp<?= number_format($totalRawatInapNonBPJS, 0, 0, '.') ?></b></td>
            </tr>
            <tr>
                <td colspan=""><b>Total Uang Cash</b></td>
                <td colspan=""><b>Rp<?= number_format($totalRawatInapNonBPJS + $totalPoli, 0, 0, '.') ?></b></td>
            </tr>
            <tr>
                <td colspan="2">
                    <b>
                        <hr class="m-1">
                    </b>
                </td>
            </tr>
            <?php
            $getRawatinapBPJS = $koneksi->query("SELECT biaya, SUM(besaran) as harga FROM `pulang` INNER JOIN registrasi_rawat ON DATE_FORMAT(registrasi_rawat.jadwal, '%Y-%m-%d') = tgl_masuk AND registrasi_rawat.no_rm = pulang.norm INNER JOIN rawatinapdetail ON rawatinapdetail.id = registrasi_rawat.idrawat WHERE carabayar = 'bpjs' AND pulang.tgl = '$tanggal' AND pulang.shift IN (" . $shift . ") GROUP BY biaya ORDER BY biaya ASC");
            ?>
            <tr>
                <td colspan="2" data-bs-toggle="modal" data-bs-target="#staticBackdropBPJS"><b>Rawat Inap BPJS</b></td>
            </tr>
            <?php foreach ($getRawatinapBPJS as $rawatInapBPJS) { ?>
                <tr>
                    <td><?= $rawatInapBPJS['biaya'] ?></td>
                    <td>Rp<?= number_format($rawatInapBPJS['harga'], 0, 0, '.') ?></td>
                    <?php $totalRawatInapBPJS += $rawatInapBPJS['harga']; ?>
                </tr>
            <?php } ?>
            <tr>
                <td><b>Total</b></td>
                <td><b>Rp<?= number_format($totalRawatInapBPJS, 0, 0, '.') ?></b></td>
            </tr>
        </tbody>
    </table>
</div>
<script>
    // window.print();
    // setTimeout(() => {
    //     window.close();
    // }, 1000);
</script>