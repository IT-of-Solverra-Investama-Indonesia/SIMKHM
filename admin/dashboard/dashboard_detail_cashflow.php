<?php if (!isset($_GET['hal'])) { ?>
    <div class="card shadow p-2 mb-2">
        <form method="POST">
            <div class="row">
                <div class="col-6">
                    Dari Tanggal
                    <input type="date" class="form-control" name="date_start" id="date_start" value="<?= htmlspecialchars($_GET['date_start']) ?? '' ?>">
                </div>
                <div class="col-6">
                    Hingga Tanggal
                    <input type="date" class="form-control" name="date_end" id="date_end" value="<?= htmlspecialchars($_GET['date_end']) ?? date('Y-m-d') ?>">
                </div>
                <div class="col-12">
                    <p align="right">
                        <button class="btn btn-primary mt-2 mb-0" name="fil" type="submit"><i class="bi bi-search"></i> Cari</button>
                    </p>
                </div>
            </div>
        </form>
    </div>
    <?php
    if (isset($_POST['fil'])) {
        echo "
          <script>
            document.location.href='index.php?halaman=dashboard_detail&cashflow&date_start=" . htmlspecialchars($_POST['date_start']) . "&date_end=" . htmlspecialchars($_POST['date_end']) . "';
          </script>
        ";
    }
    ?>
    <?php
    $urlFormApp = "index.php?halaman=dashboard_detail&cashflow&&date_start=" . htmlspecialchars($_GET['date_start']) . "&date_end=" . htmlspecialchars($_GET['date_end']) . "";
    ?>
    <div class="card shadow p-2">
        <div class="table-responsive">
            <?= file_get_contents($baseUrlLama . "api_personal/api_cashflow.php?getAllCashflow&date_start=" . htmlspecialchars($_GET['date_start']) . "&date_end=" . htmlspecialchars($_GET['date_end']) . "&html") ?>
        </div>
    </div>
<?php } else { ?>
    <?php
    $url = $baseUrlLama . "api_personal/api_cashflow.php?&date_start=" . htmlspecialchars($_GET['date_start']) . "&date_end=" . htmlspecialchars($_GET['date_end']) . "&akun=" . htmlspecialchars($_GET['akun']) . "&hal=" . htmlspecialchars($_GET['hal']) . "&cashflowDetail";
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    if ($data['status'] == 'Successfully') {
        $items = $data['data'];
    }
    $totalDebet = 0;
    $totalKredit = 0;
    ?>
    <div class="card shadow p-2">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="myTable" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama Akun</th>
                        <th>Debit</th>
                        <th>Kredit</th>
                        <th>Ket1</th>
                        <th>Ket2</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item) { ?>
                        <tr>
                            <td><?= $item['tgl'] ?></td>
                            <td><?= $item['namaakun'] ?></td>
                            <td><?= number_format($item['debet'], 0, ',', '.') ?></td>
                            <td><?= number_format($item['kredit'], 0, ',', '.') ?></td>
                            <td><?= $item['ket1'] ?></td>
                            <td><?= $item['ket2'] ?></td>
                        </tr>
                        <?php
                        $totalDebet += $item['debet'];
                        $totalKredit += $item['kredit'];
                        ?>
                    <?php } ?>
                    <tr>
                        <td colspan="2"><b>Total</b></td>
                        <td><b><?= number_format($totalDebet, 0, ',', '.') ?></b></td>
                        <td><b><?= number_format($totalKredit, 0, ',', '.') ?></b></td>
                        <td colspan="2"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php } ?>