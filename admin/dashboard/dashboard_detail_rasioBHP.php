<div>
    <h5>Dashboard Rasion BHP</h5>
    <?php
    $date_start = isset($_GET['date_start']) ? $_GET['date_start'] : date('Y-m-1');
    $date_end = isset($_GET['date_end']) ? $_GET['date_end'] : date('Y-m-d');
    $api_url = $baseUrlLama . "api_personal/api_rasio.php?date_start=$date_start&date_end=$date_end";
    ?>
    <div class="card shadow p-2 mb-1">
        <form method="GET">
            <input type="text" hidden name="halaman" value="<?= htmlspecialchars($_GET['halaman']) ?>" id="">
            <input type="text" hidden name="rasioBHP" id="">
            <div class="row">
                <div class="col-5">
                    <input name="date_start" class="form-control form-control-sm" required placeholder="Date Start" onfocus="(this.type='date')" onblur="(this.type='text')" value="<?= $date_start ?>" id="">
                </div>
                <div class="col-5">
                    <input name="date_end" class="form-control form-control-sm" required placeholder="Date End" onfocus="(this.type='date')" onblur="(this.type='text')" value="<?= $date_end ?>" id="">
                </div>
                <div class="col-2">
                    <button class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
                </div>
            </div>
        </form>
    </div>
    <div class="card shadow p-2">
        <?php
        // require "../dist/baseUrlAPI.php";

        function getRasioBHPFromKHM($date_start, $date_end, $api_url)
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $api_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            return json_decode($response, true);
        }
        $data = getRasioBHPFromKHM($date_start, $date_end, $api_url);
        ?>

        <div class="table-responsive">
            <table class="table table-hover table-striped table-sm">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nomor Akun</th>
                        <th>Biaya Akun</th>
                        <th>Omset</th>
                        <th>Target</th>
                        <th>Rasio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($data['status'] === 'success'): ?>
                        <?php foreach ($data['data'] as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['tanggal']) ?></td>
                                <td><?= htmlspecialchars($item['nomor_akun']) ?> (<?= htmlspecialchars($item['nama_akun']) ?>)</td>
                                <td>Rp <?= number_format($item['biaya_akun'], 0, 0, '.') ?></td>
                                <td>Rp <?= number_format($item['omset'], 0, 0, '.') ?></td>
                                <td><?= htmlspecialchars($item['target']) ?>%</td>
                                <td><?= htmlspecialchars($item['rasio']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-danger">Error: <?= htmlspecialchars($data['error']) ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>