<?php
require '../dist/function.php';
$tanggal = htmlspecialchars($_GET['tanggal']);
$shift = htmlspecialchars($_GET['shift']);
$getData = $koneksi->query("SELECT sumber, SUM((harga_umum - diskon_obat) * jumlah) AS total_penjualan FROM (
                            SELECT 'UMUM' AS sumber, id_penjualan, nota, tgl_jual, kode_obat, nama_obat, harga_umum, diskon_obat, jumlah, harga_beli, akun, petugas, shift, created_at FROM penjualan_umum
                            UNION ALL
                            SELECT 'RESEP' AS sumber, id_penjualan, nota, tgl_jual, kode_obat, nama_obat, harga_umum, diskon_obat, jumlah, harga_beli, akun, petugas, shift, created_at FROM penjualan_resep
                            UNION ALL
                            SELECT 'REKANAN' AS sumber, id_penjualan, nota, tgl_jual, kode_obat, nama_obat, harga_umum, diskon_obat, jumlah, harga_beli, akun, petugas, shift, created_at FROM penjualan_rekanan
                            UNION ALL
                            SELECT 'INTERNAL' AS sumber, id_penjualan, nota, tgl_jual, kode_obat, nama_obat, harga_umum, diskon_obat, jumlah, harga_beli, akun, petugas, shift, created_at FROM penjualan_internal
                        ) AS penjualan_obat  WHERE 1 = 1 AND date_format(tgl_jual, '%Y-%m-%d') = '$tanggal' AND shift = '$shift' GROUP BY sumber ORDER BY tgl_jual DESC");
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
    <table class="table table-sm table-hover " style="font-size: 12px;">
        <thead>
            <tr>
                <!-- <th>Keterangan</th>
                <th>Total</th> -->
                <th>Keterangan</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($getData as $data) : ?>
                <tr>
                    <td><?= $data['sumber'] ?></td>
                    <td style="text-align: right;">Rp. <?= number_format($data['total_penjualan'], 0, 0), '' ?></td>
                </tr>
                <?php
                static $grandTotal = 0;
                $grandTotal += $data['total_penjualan'];
                ?>
            <?php endforeach; ?>

            <tr>
                <th>Total</th>
                <th style="text-align: right;">Rp. <?= number_format($grandTotal ?? 0, 0, 0, '.') ?></th>
            </tr>
        </tbody>
    </table>
</div>
<script>
    window.print();
    setTimeout(() => {
        window.close();
    }, 1000);
</script>