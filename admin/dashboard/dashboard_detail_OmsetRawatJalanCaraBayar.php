<div class="m-0 p-0">
    <?php
    $date_start = htmlspecialchars($_GET['date_start'] ?? date('Y-m-01'));
    $date_end = htmlspecialchars($_GET['date_end'] ??  date('Y-m-d'));
    $carabayar = htmlspecialchars($_GET['carabayar'] ?? 'All');
    ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <h5><b>Omset Rawat Jalan per Cara Bayar</b></h5>
    <div class="card shadow p-2 mb-2">
        <form method="get">
            <input type="text" name="halaman" id="dashboard_detail" hidden value="dashboard_detail">
            <input type="text" name="OmsetRawatJalanCaraBayar" hidden id="">
            <div class="row g-1">
                <div class="col-4">
                    <select name="carabayar" id="" class="form-control form-control-sm">
                        <option <?= $carabayar == 'All' ? 'selected' : '' ?> value="All">All Cara Bayar</option>
                        <option <?= $carabayar == 'umum' ? 'selected' : '' ?> value="umum">Umum</option>
                        <option <?= $carabayar == 'malam' ? 'selected' : '' ?> value="malam">Malam</option>
                        <option <?= $carabayar == 'bpjs' ? 'selected' : '' ?> value="bpjs">BPJS</option>
                        <option <?= $carabayar == 'spesialis anak' ? 'selected' : '' ?> value="spesialis anak">Spesialis Anak</option>
                        <option <?= $carabayar == 'spesialis penyakit dalam' ? 'selected' : '' ?> value="spesialis penyakit dalam">Spesialis Penyakit Dalam</option>
                        <option <?= $carabayar == 'gigi umum' ? 'selected' : '' ?> value="gigi umum">Gigi Umum</option>
                        <option <?= $carabayar == 'gigi bpjs' ? 'selected' : '' ?> value="gigi bpjs">Gigi BPJS</option>
                        <option <?= $carabayar == 'kosmetik' ? 'selected' : '' ?> value="kosmetik">Kosmetik</option>
                    </select>
                </div>
                <div class="col-3">
                    <input type="date" name="date_start" class="form-control form-control-sm" value="<?= $date_start ?>">
                </div>
                <div class="col-3">
                    <input type="date" name="date_end" class="form-control form-control-sm" value="<?= $date_end ?>">
                </div>
                <div class="col-1">
                    <button name="src" class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
                </div>
            </div>
        </form>
    </div>
    <?php if (isset($_GET['src'])) { ?>
        <?php
        $whereCondition = "";
        if (isset($_GET['carabayar']) && $_GET['carabayar'] != 'All') {
            $carabayar = htmlspecialchars($_GET['carabayar']);
            $whereCondition .= " AND registrasi_rawat.carabayar = '$carabayar' ";
        }
        if (isset($_GET['date_start']) && $_GET['date_start'] != '') {
            $date_start = htmlspecialchars($_GET['date_start']);
            $whereCondition .= " AND DATE_FORMAT(registrasi_rawat.jadwal, '%Y-%m-%d') >= '$date_start' ";
        }
        if (isset($_GET['date_end']) && $_GET['date_end'] != '') {
            $date_end = htmlspecialchars($_GET['date_end']);
            $whereCondition .= " AND DATE_FORMAT(registrasi_rawat.jadwal, '%Y-%m-%d') <= '$date_end' ";
        }

        $queryPerHari = "SELECT SUM((poli+total_lain+biaya_lab)-potongan) AS omset, DATE_FORMAT(registrasi_rawat.jadwal, '%Y-%m-%d') AS waktu FROM registrasi_rawat INNER JOIN biaya_rawat ON biaya_rawat.idregis = registrasi_rawat.idrawat WHERE 1 = 1 " . $whereCondition . " GROUP BY DATE_FORMAT(registrasi_rawat.jadwal, '%Y-%m-%d') ORDER BY DATE_FORMAT(registrasi_rawat.jadwal, '%Y-%m-%d') ASC";
        $queryPerBulan = "SELECT SUM((poli+total_lain+biaya_lab)-potongan) AS omset, DATE_FORMAT(registrasi_rawat.jadwal, '%Y-%m') AS waktu FROM registrasi_rawat INNER JOIN biaya_rawat ON biaya_rawat.idregis = registrasi_rawat.idrawat WHERE 1 = 1 " . $whereCondition . " GROUP BY DATE_FORMAT(registrasi_rawat.jadwal, '%Y-%m') ORDER BY DATE_FORMAT(registrasi_rawat.jadwal, '%Y-%m-%d') ASC";

        $getDataPerHari = $koneksi->query($queryPerHari);
        $getDataPerBulan = $koneksi->query($queryPerBulan);
        ?>
        <div class="row g-2">
            <div class="col-12">
                <div class="card shadow p-1 mb-1">
                    <b>Data Omset Rawat Jalan Cara Bayar <?= $carabayar ?> Dari <?= $date_start ?> Hingga <?= $date_end ?></b>
                </div>
            </div>
            <div class="col-md-8">
                <canvas id="myChartHari" class="w-100 card shadow p-2 mt-0 mb-2"></canvas>
                <script>
                    const ctx = document.getElementById('myChartHari');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: [<?php
                                        foreach ($getDataPerHari as $data) {
                                            echo "'" . $data['waktu'] . "',";
                                        }
                                        ?>],
                            datasets: [{
                                label: 'Omset Perhari',
                                data: [<?php
                                        foreach ($getDataPerHari as $data) {
                                            echo $data['omset'] . ",";
                                        }
                                        ?>],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                </script>
            </div>
            <div class="col-md-4">
                <div class="card shadow p-2 h-100 mt-0 mb-2" style="max-height: 355px;">
                    <b class="mb-2">Omset Perhari</b>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-sm" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Omset</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalOmset = 0;
                                foreach ($getDataPerHari as $data) {
                                    $totalOmset += $data['omset'];
                                ?>
                                    <tr>
                                        <td><?= $data['waktu'] ?></td>
                                        <td>Rp <?= number_format($data['omset'], 0, 0, '.') ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total Omset</th>
                                    <th>Rp <?= number_format($totalOmset, 0, 0, '.') ?></th>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <canvas id="Bulan" class="w-100 card shadow p-2 mt-0"></canvas>
                <script>
                    const ctxx = document.getElementById('Bulan');
                    new Chart(ctxx, {
                        type: 'bar',
                        data: {
                            labels: [<?php
                                        foreach ($getDataPerBulan as $data) {
                                            echo "'" . $data['waktu'] . "',";
                                        }
                                        ?>],
                            datasets: [{
                                label: 'Omset Per Bulan',
                                data: [<?php
                                        foreach ($getDataPerBulan as $data) {
                                            echo $data['omset'] . ",";
                                        }
                                        ?>],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                </script>
            </div>
            <div class="col-md-4">
                <div class="card shadow p-2 h-100 mt-0" style="max-height: 355px;">
                    <b class="mb-2">Omset Perbulan</b>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-sm" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Omset</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalOmset = 0;
                                foreach ($getDataPerBulan as $data) {
                                    $totalOmset += $data['omset'];
                                ?>
                                    <tr>
                                        <td><?= $data['waktu'] ?></td>
                                        <td>Rp <?= number_format($data['omset'], 0, 0, '.') ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total Omset</th>
                                    <th>Rp <?= number_format($totalOmset, 0, 0, '.') ?></th>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="card shadow p-2">
            <center>
                <h6><b>Silahkan Filter Data Terlebih Dahulu</b></h6>
                <span class="text-gray">Hal ini dilakukan agar saat menampilkan omset per Cara Bayar tidak loading lama</span>
            </center>
        </div>
    <?php } ?>
</div>