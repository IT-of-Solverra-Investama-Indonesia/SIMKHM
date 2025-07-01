<div class="card shadow p-2 mb-1">
    <form method="get">
        <input type="text" hidden name="halaman" value="dashboardinap" id="">
        <input type="text" hidden name="dashboardinap" value="dashboardinapglobal" id="">
        <div class="row g-1">
            <div class="col-9">
                <input type="month" name="bulan" class="form-control form-control-sm" value="<?php echo $_GET['bulan'] ?? date('Y-m'); ?>" id="">
            </div>
            <div class="col-3">
                <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-search"></i></button>
            </div>
        </div>
    </form>
</div>
<div class="card shadow p-2">
    <h6 class="mb-0">Dashboard Inap Global</h6>
    <i style="font-size: 10px;" class="mb-2">Data yang dimunculkan adalah 6 Bulan Terakhir</i>
    <div class="table-responsive">
        <table class="table table-sm table-hover table-striped" style="font-size: 10px;">
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th>JumlahHari</th>
                    <th>Pasien</th>
                    <th>Pulang/Pasien</th>
                    <th>Rujuk/Pasien</th>
                    <th>Meninggal/Pasien</th>
                    <th>BPJS/Hari</th>
                    <th>Umum/Hari</th>
                    <th>Pasien/Hari</th>
                    <th>Rujuk/Hari</th>
                    <th>HariRawat</th>
                    <th>HariRawat/Hari</th>
                    <th>HariRawat/Pasien</th>
                    <th>TotalInap/Pasien</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $whereBulan = "";
                if (isset($_GET['bulan'])) {
                    $whereBulan = "AND DATE_FORMAT(jadwal, '%Y-%m') = '" . htmlspecialchars($_GET['bulan']) . "'";
                }
                $getDataPasienGroupByBulan = $koneksi->query("SELECT *, DATE_FORMAT(jadwal, '%Y-%m') as bulan FROM registrasi_rawat WHERE perawatan = 'Rawat Inap' " . $whereBulan . " GROUP BY DATE_FORMAT(jadwal, '%Y-%m') ORDER BY jadwal DESC LIMIT 6");
                foreach ($getDataPasienGroupByBulan as $dataBulan) {
                ?>
                    <tr>
                        <td><?= $dataBulan['bulan'] ?></td>
                        <td>
                            <?= $JumlahHari = $dataBulan['jadwal'] == date('Y-m') ? date('d') : date('t', strtotime($dataBulan['jadwal'])) ?>
                        </td>
                        <td>
                            <?php
                            $getDataPasien = $koneksi->query("SELECT COUNT(*) as jumlahPasien FROM registrasi_rawat WHERE perawatan = 'Rawat Inap' AND DATE_FORMAT(jadwal, '%Y-%m') = '{$dataBulan['bulan']}'")->fetch_assoc();
                            ?>
                            <?= $JumlahPasien = $getDataPasien['jumlahPasien'] ?>
                        </td>
                        <td>
                            <?php
                            $getDataPulang = $koneksi->query("SELECT COUNT(*) as jumlahPulang FROM registrasi_rawat WHERE perawatan = 'Rawat Inap' AND status_antri = 'Pulang' AND DATE_FORMAT(jadwal, '%Y-%m') = '{$dataBulan['bulan']}'")->fetch_assoc();
                            ?>
                            <?= $getDataPulang['jumlahPulang'] ?> | <?= number_format($getDataPulang['jumlahPulang'] / $JumlahPasien, 2) ?>
                        </td>
                        <td>
                            <?php
                            $getDataRujuk = $koneksi->query("SELECT COUNT(*) as jumlahRujuk FROM pulang WHERE DATE_FORMAT(tgl, '%Y-%m') = '{$dataBulan['bulan']}' AND keadaan = 'Rujuk'")->fetch_assoc();
                            ?>
                            <?= $getDataRujuk['jumlahRujuk'] ?> | <?= number_format($getDataRujuk['jumlahRujuk'] / $JumlahPasien, 2) ?>
                        </td>
                        <td>
                            <?php
                            $getDataMeninggal = $koneksi->query("SELECT COUNT(*) as jumlahMeninggal FROM pulang WHERE DATE_FORMAT(tgl, '%Y-%m') = '{$dataBulan['bulan']}' AND keadaan = 'Meninggal'")->fetch_assoc();
                            ?>
                            <?= $getDataMeninggal['jumlahMeninggal'] ?> | <?= number_format($getDataMeninggal['jumlahMeninggal'] / $JumlahPasien, 2) ?>
                        </td>
                        <td>
                            <?php
                            $getDataPasienBPJS = $koneksi->query("SELECT COUNT(*) as jumlahBPJS FROM registrasi_rawat WHERE perawatan = 'Rawat Inap' AND carabayar = 'bpjs' AND DATE_FORMAT(jadwal, '%Y-%m') = '{$dataBulan['bulan']}'")->fetch_assoc();
                            ?>
                            <?= $getDataPasienBPJS['jumlahBPJS'] ?> | <?= number_format($getDataPasienBPJS['jumlahBPJS'] / $JumlahHari, 2) ?>
                        </td>
                        <td>
                            <?php
                            $getDataPasienUmum = $koneksi->query("SELECT COUNT(*) as jumlahUmum FROM registrasi_rawat WHERE perawatan = 'Rawat Inap' AND carabayar = 'umum' AND DATE_FORMAT(jadwal, '%Y-%m') = '{$dataBulan['bulan']}'")->fetch_assoc();
                            ?>
                            <?= $getDataPasienUmum['jumlahUmum'] ?> | <?= number_format($getDataPasienUmum['jumlahUmum'] / $JumlahHari, 2) ?>
                        </td>
                        <td>
                            <?= number_format($JumlahPasien / $JumlahHari, 2) ?>
                        </td>
                        <td>
                            <?= number_format($getDataRujuk['jumlahRujuk'] / $JumlahHari, 2) ?>
                        </td>
                        <td>
                            <?php
                            $getHariRawat = $koneksi->query("SELECT SUM(datediff(tgl,tgl_masuk)+1) AS hariRawat FROM pulang WHERE DATE_FORMAT(tgl, '%Y-%m') = '{$dataBulan['bulan']}'")->fetch_assoc();
                            ?>
                            <?= $getHariRawat['hariRawat'] ?>
                        </td>
                        <td>
                            <?= number_format($getHariRawat['hariRawat'] / $JumlahHari, 2) ?>
                        </td>
                        <td>
                            <?= number_format($getHariRawat['hariRawat'] / $JumlahPasien, 2) ?>
                        </td>
                        <td>
                            <?php
                            $getTotalInap = $koneksi->query("SELECT SUM(besaran) AS totalInap FROM rawatinapdetail WHERE DATE_FORMAT(tgl, '%Y-%m') = '{$dataBulan['bulan']}'")->fetch_assoc();
                            ?>
                            Rp<?= number_format($getTotalInap['totalInap'] / $JumlahPasien, 0, 0, '.') ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>