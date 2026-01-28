<h5 class="card-title">KPIM Perawat Inap</h5>
<div class="card shadow p-2 mb-1">
    <form method="GET">
        <div class="row g-1">
            <div class="col-5">
                <input type="text" name="halaman" value="dashboardkpim" hidden id="">
                <input type="text" name="kpim" value="perawatInap" hidden id="">
                <label for="">Dari Bulan:</label>
                <input type="month" name="month_start"  class="form-control form-control-sm" value="<?= $month_start = isset($_GET['month_start']) ? htmlspecialchars($_GET['month_start']) : date('Y-m') ?>" required>
            </div>
            <div class="col-5">
                <label for="">Sampai Bulan:</label>
                <input type="month" name="month_end" class="form-control form-control-sm" value="<?= $month_end = isset($_GET['month_end']) ? htmlspecialchars($_GET['month_end']) : date('Y-m') ?>" required>
            </div>
            <div class="col-2 d-flex align-items-end">
                <button class="btn btn-sm btn-primary w-100"><i class="bi bi-search"></i></button>
            </div>
        </div>
    </form>
</div>
<div class="card shadow p-2">
    <table class="table table-striped table-hover table-sm" style="font-size: 12px;">
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Petugas</th>
                <th>Pasien</th>
                <th>KPIM</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $totalPasienIGD = 0;
            $getDataIGD = $koneksi->query("SELECT DATE_FORMAT(cpi.tgl, '%M %Y') as Bulan, cpi.petugas as Petugas, COUNT(*) as jumlahPasien FROM ctt_penyakit_inap cpi WHERE DATE_FORMAT(cpi.tgl, '%Y-%m') >= '$month_start' AND DATE_FORMAT(cpi.tgl, '%Y-%m') <= '$month_end' GROUP BY DATE_FORMAT(cpi.tgl, '%Y-%m'), cpi.petugas ORDER BY DATE_FORMAT(cpi.tgl, '%Y-%m') DESC, cpi.petugas ASC");
            foreach ($getDataIGD as $dataIGD) {
            ?>
                <tr>
                    <td><?= $dataIGD['Bulan'] ?></td>
                    <td>
                        <?= $dataIGD['Petugas'] ?? "" ?>
                    </td>
                    <td>
                        <?= $dataIGD['jumlahPasien'] ?> Pasien
                        <!-- <a href="?halaman=dashboardstaff&tipe=rekapStaffInap&month_start=<?= $month_start ?>&month_end=<?= $month_end ?>&detailPetugas=<?= $dataIGD['Petugas'] ?>&IGD" class="badge bg-warning" style="font-size: 12px;">
                        </a> -->
                    </td>
                    <td>
                        <?php 
                            $kpimPerawatInap = 1000;
                        ?>
                        Rp <?= number_format($kpimPerawatInap) ?> x <?= $dataIGD['jumlahPasien'] ?>
                    </td>
                    <td>
                        Rp <?= number_format($dataIGD['jumlahPasien'] * $kpimPerawatInap) ?>
                    </td>
                </tr>
                <?php
                $totalPasienIGD += $dataIGD['jumlahPasien'];
                ?>
            <?php } ?>
        </tbody>
    </table>
    </div>