<?php

use BcMath\Number;

session_start();
include '../dist/function.php';
$petugas = $_SESSION['admin']['namalengkap'];
$pasien = $koneksi->query("SELECT * FROM pasien  WHERE no_rm='$_GET[id]';")->fetch_assoc();
if (!isset($_GET['igd'])) {
    $jadwal = $koneksi->query("SELECT * FROM registrasi_rawat  WHERE no_rm='$_GET[id]' AND date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' AND perawatan = 'Rawat Inap' ORDER BY idrawat DESC LIMIT 1")->fetch_assoc();
}

?>
<?php
$getPetugasSingle = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND tgl_pasien='$_GET[tgl]' AND DATE_FORMAT(created_at, '%Y-%m-%d') = '$_GET[tglObat]' AND obat_igd = '$_GET[jenis]' ORDER BY created_at DESC LIMIT 1")->fetch_assoc();
?>
<style>
    body {
        font-family: monospace;
        /* letter-spacing: px; */
    }

    @media print {
        #button {
            display: none;
        }
    }
</style>
<div id="button">
    <a href="lpo_print_obat.php?id=<?= $_GET['id'] ?>&inap&tgl=<?= $_GET['tgl'] ?>&tglObat=<?= $_GET['tglObat'] ?>&jenis=<?= $_GET['jenis'] ?>&eresep">E-Resep</a>
    <a href="lpo_print_obat.php?id=<?= $_GET['id'] ?>&inap&tgl=<?= $_GET['tgl'] ?>&tglObat=<?= $_GET['tglObat'] ?>&jenis=<?= $_GET['jenis'] ?>&kasir">Kasir</a>
    <a href="lpo_print_obat.php?id=<?= $_GET['id'] ?>&inap&tgl=<?= $_GET['tgl'] ?>&tglObat=<?= $_GET['tglObat'] ?>&jenis=<?= $_GET['jenis'] ?>">Default</a>
</div>
<div style="max-width: 58mm; padding: 2mm;">
    <center>
        <img src="https://simkhm.id/wonorejo/admin/dist/assets/img/3.png" style="width: 25%; margin-bottom: 10px; margin-bottom: 0px;" alt="">
        <h5>Obat Inap <?= $getPetugasSingle['obat_igd'] ?> Tanggal <?= $tglObat = htmlspecialchars($_GET['tglObat']) ?></h5>
    </center>
    <table>
        <thead>
            <tr>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Nama</td>
                <td>: <?= $pasien['nama_lengkap'] ?></td>
            </tr>
            <tr>
                <td>No.RM</td>
                <td>: <?= $pasien['no_rm'] ?></td>
            </tr>
            <tr>
                <td>Kamar</td>
                <td>: <?= $jadwal['kamar'] ?></td>
            </tr>
        </tbody>
    </table>
    <hr style="margin-bottom: 0px;">
    <div>
        <div style="font-size: 12px; margin-bottom: 10px;">
            <?php
            $total = 0;
            $getData = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND tgl_pasien='$_GET[tgl]' AND DATE_FORMAT(created_at, '%Y-%m-%d') = '$_GET[tglObat]' AND obat_igd = '$_GET[jenis]'");
            foreach ($getData as $data) {
            ?>
                <?php
                $getPriceInDate = $koneksi->query("SELECT * FROM apotek WHERE tgl_beli <= '" . date('Y-m-d', strtotime($data['created_at'])) . "' AND nama_obat = '$data[nama_obat]' ORDER BY tgl_beli DESC LIMIT 1")->fetch_assoc();
                ?>
                <?php number_format($harga = $getPriceInDate['harga_beli'] * ($getPriceInDate['margininap'] / 100), 0, 0, '.') ?>
                <li style="margin-bottom: 5px;">
                    <?= $data['nama_obat'] ?> (<b><?= $data['jml_dokter'] ?></b>)<br>
                    <?php if(isset($_GET['eresep']) OR !isset($_GET['kasir'])){?>
                        <?php if($data['petunjuk_obat'] != ''){?>
                            <span style="text-transform: capitalize;">
                                <?= $data['petunjuk_obat'] ?> <br>
                            </span>
                        <?php }?>
                        <?= $data['dosis1_obat'] ?>x<?= $data['dosis2_obat'] ?> <?= $data['per_obat'] ?> (<?= $data['durasi_obat'] ?> Hari)<br>
                    <?php }?>
                    <?php if(isset($_GET['kasir']) OR !isset($_GET['eresep'])){?>
                        <?= $data['jml_dokter'] ?> x Rp<?= number_format($harga, 0, 0, '.') ?> : Rp <?= number_format($harga * $data['jml_dokter'], 0, 0, '.') ?>
                        <?php $total += $harga * $data['jml_dokter'] ?>
                    <?php }?>
                </li>
            <?php } ?>
        </div>
        <hr>
        <?php if(isset($_GET['kasir']) OR !isset($_GET['eresep'])){?>
            <b style="margin-top: 10px;">Total : Rp <?= number_format($total, 0, 0, '.') ?></b><br>
            Petugas : <?= $getPetugasSingle['petugas'] == '' ? $petugas : $getPetugasSingle['petugas'] ?>
        <?php }?>
    </div>
</div>
<script>
    window.print();
</script>