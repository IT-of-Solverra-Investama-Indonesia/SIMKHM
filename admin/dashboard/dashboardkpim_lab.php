<h5 class="card-title m-0">KPIM Lab</h5>
<div class="card shadow p-2 mb-1">
    <form method="GET">
        <div class="row g-1">
            <div class="col-5">
                <input type="text" name="halaman" value="dashboardkpim" hidden id="">
                <input type="text" name="kpim" value="lab" hidden id="">
                <label for="">Dari Bulan:</label>
                <input type="month" name="month_start" class="form-control form-control-sm" value="<?= $month_start = isset($_GET['month_start']) ? htmlspecialchars($_GET['month_start']) : date('Y-m', strtotime('-5 months')) ?>" required>
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
    <div class="table-responsive">
        <table class="table table-sm table-hover table-striped" style="font-size: 12px;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Bulan</th>
                    <th>Omset</th>
                    <th>Prosentase KPIM</th>
                    <th>Total</th>
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
                    $no = 1;
                    foreach ($months as $bulan) {
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= date('F Y', strtotime($bulan)) ?></td>
                        <td>
                            <?php
                                // Deteksi URL saat ini
                                $currentUrl = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] : '';
                                $currentUrl = strtolower($currentUrl);
                                $baseApiUrl = '';

                                // Cek apakah URL mengandung localhost
                                if (strpos($currentUrl, 'localhost') !== false) {
                                    $baseApiUrl = "http://localhost/husadamulia/api_personal/api_getDataForKPIM.php?omsetLab&";
                                } else {
                                    // Cek kata kunci lainnya
                                    if (strpos($currentUrl, 'wonorejo') !== false) {
                                        $baseApiUrl = "https://husadamulia.com/wonorejo/api_personal/api_getDataForKPIM.php?omsetLab&";
                                    } elseif (strpos($currentUrl, 'klakah') !== false) {
                                        $baseApiUrl = "https://husadamulia.com/klakah/api_personal/api_getDataForKPIM.php?omsetLab&";
                                    } elseif (strpos($currentUrl, 'tunjung') !== false) {
                                        $baseApiUrl = "https://husadamulia.com/tunjung/api_personal/api_getDataForKPIM.php?omsetLab&";
                                    } elseif (strpos($currentUrl, 'kunir') !== false) {
                                        $baseApiUrl = "https://husadamulia.com/kunir/api_personal/api_getDataForKPIM.php?omsetLab&";
                                    } else {
                                        // Default fallback ke localhost jika tidak ada yang cocok
                                        $baseApiUrl = "http://localhost/husadamulia/api_personal/api_getDataForKPIM.php?omsetLab&";
                                    }
                                }

                                $apiUrl = $baseApiUrl . http_build_query([
                                    'month_start' => $bulan,
                                    'month_end'   => $bulan,
                                ]);

                                $rpk = 0;
                                $apiResponse = @file_get_contents($apiUrl);
                                if ($apiResponse !== false) {
                                    $decoded = json_decode($apiResponse, true);
                                    if (isset($decoded['data'][0]['rpk'])) {
                                        $rpk = (int) $decoded['data'][0]['rpk'];
                                    }
                                }
                            ?>
                            <?= 'Rp ' . number_format($rpk, 0, ',', '.'); ?>
                        </td>
                        <td>
                            <?php
                                $kpimProsentase = 1;
                            ?>
                            <?= $kpimProsentase ?>%
                        </td>
                        <td>
                            <?= 'Rp ' . number_format($rpk * ($kpimProsentase / 100), 0, ',', '.'); ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>