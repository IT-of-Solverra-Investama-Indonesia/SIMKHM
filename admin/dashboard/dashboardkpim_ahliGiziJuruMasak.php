<div>
    <h5 class="card-title">Dashboard Ahli Gizi Juru Masak</h5>
    <div class="card shadow p-2 mb-1">
        <form method="get">
            <input type="hidden" name="halaman" value="dashboardkpim" id="">
            <input type="hidden" name="kpim" value="ahliGiziJuruMasak" id="">
            <div class="row g-1">
                <div class="col-5">
                    <input type="text" name="month_start" class="form-control form-control-sm" placeholder="Dari tanggal" onfocus="this.type='month'" onblur="this.type='text'" value="<?= $month_start = (isset($_GET['month_start']) && $_GET['month_start'] != "") ?  htmlspecialchars($_GET['month_start']) : date('Y-m', strtotime('-6 month')) ?>">
                </div>
                <div class="col-5">
                    <input type="text" name="month_end" class="form-control form-control-sm" placeholder="Sampai tanggal" onfocus="this.type='month'" onblur="this.type='text'" value="<?= $month_end = (isset($_GET['month_end']) && $_GET['month_end'] != "") ?  htmlspecialchars($_GET['month_end']) : date('Y-m') ?>">
                </div>
                <div class="col-2">
                    <button class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
                </div>
            </div>
        </form>
    </div>
    <div class="card shadow p-2">
        <div class="table-responsive">
            <table class="table table-sm table-hover table-striped" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th>Jumlah Hari</th>
                        <th>KPI</th>
                        <th>Total KPI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Generate array of months dari month_start hingga month_end
                    $start_date = date('Y-m-01', strtotime($month_start ));
                    $end_date = date('Y-m-t', strtotime($month_end));
                    
                    $months = [];
                    $current = $start_date;
                    
                    while ($current <= $end_date) {
                        $months[] = date('Y-m', strtotime($current));
                        $current = date('Y-m-d', strtotime('+1 month', strtotime($current)));
                    }
                    
                    // Reverse array agar bulan terbaru di atas
                    $months = array_reverse($months);
                    
                    // Loop setiap bulan
                    foreach ($months as $bulan) {
                        $bulan_display = date('F Y', strtotime($bulan . '-01'));
                    ?>
                        <tr>
                            <td><?= $bulan_display ?></td>
                            <td>
                                <?php 
                                $getHariRawat = $koneksi->query("SELECT SUM(datediff(tgl,tgl_masuk)+1) AS hariRawat FROM pulang WHERE DATE_FORMAT(tgl, '%Y-%m') = '{$bulan}'")->fetch_assoc();
                                ?>
                                <?= $getHariRawat['hariRawat'] ?> Hari
                            </td>
                            <td>
                                <?php 
                                $kpiAhliGiziJuruMasak = 450;
                                ?>
                                <?= $kpiAhliGiziJuruMasak ?> / Hari
                            </td>
                            <td>
                                <?= 'Rp ' . number_format($getHariRawat['hariRawat'] * $kpiAhliGiziJuruMasak, 0, ',', '.'); ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>