<h5 class="card-title">KPIM IGD</h5>
<div class="card shadow p-2 mb-1">
    <form method="GET">
        <div class="row g-1">
            <div class="col-5">
                <input type="text" name="halaman" value="dashboardkpim" hidden id="">
                <input type="text" name="kpim" value="igd" hidden id="">
                <label for="">Dari Bulan:</label>
                <input type="month" name="month_start" min="2026-01" class="form-control form-control-sm" value="<?= $month_start = isset($_GET['month_start']) ? htmlspecialchars($_GET['month_start']) : date('Y-m') ?>" required>
            </div>
            <div class="col-5">
                <label for="">Sampai Bulan:</label>
                <input type="month" name="month_end" min="2026-01" class="form-control form-control-sm" value="<?= $month_end = isset($_GET['month_end']) ? htmlspecialchars($_GET['month_end']) : date('Y-m') ?>" required>
            </div>
            <div class="col-2 d-flex align-items-end">
                <button class="btn btn-sm btn-primary w-100"><i class="bi bi-search"></i></button>
            </div>
        </div>
    </form>
</div>
<div class="card shadow p-2">
    <div class="table-responsive">
        <table class="table-hover table-striped table table-sm" style="font-size: 12px;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Bulan</th>
                    <th>Petugas</th>
                    <th>Jumlah  dan KPIM</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $getData = $koneksi->query("SELECT petugas, DATE_FORMAT(created_at, '%Y-%m') as bulan FROM kajian_awal_inap_tag WHERE DATE_FORMAT(created_at, '%Y-%m') >= '$month_start' AND DATE_FORMAT(created_at, '%Y-%m') <= '$month_end' GROUP BY petugas, DATE_FORMAT(created_at, '%Y-%m') ORDER BY created_at DESC");
                    $no = 1;
                    while ($data = $getData->fetch_assoc()) {
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $data['bulan'] ?></td>
                        <td><?= $data['petugas'] ?></td>
                        <td>
                            <?php 
                            $getTag = $koneksi->query("SELECT COUNT(*) as jmlTag FROM kajian_awal_inap_tag WHERE petugas = '{$data['petugas']}' AND DATE_FORMAT(created_at, '%Y-%m') = '{$data['bulan']}'")->fetch_assoc();
                            $getMenTag = $koneksi->query("SELECT COUNT(*) as jmlMenTag FROM igd INNER JOIN admin ON igd.perawat = admin.username WHERE admin.namalengkap = '{$data['petugas']}' AND DATE_FORMAT(igd.tgl_masuk, '%Y-%m') = '{$data['bulan']}'")->fetch_assoc();

                            $kpiMenTag = 5000;
                            $kpiDiTag = 1000;
                            ?>
                            IGD (Mengisi) : <?= $getMenTag['jmlMenTag'] ?> x Rp. <?= number_format($kpiMenTag) ?> = Rp. <?= number_format($getMenTag['jmlMenTag'] * $kpiMenTag) ?>
                            <br>
                            Perawat (Ditag) : <?= $diTag = $getTag['jmlTag'] - $getMenTag['jmlMenTag'] < 0 ? 0 : $getTag['jmlTag'] - $getMenTag['jmlMenTag'] ?> x Rp. <?= number_format($kpiDiTag) ?> = Rp. <?= number_format($diTag * $kpiDiTag) ?>
                        </td>
                        <td>
                            Rp. <?= number_format(($getMenTag['jmlMenTag'] * $kpiMenTag) + ($diTag * $kpiDiTag)) ?>
                        </td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
</div>