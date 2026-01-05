<div>
    <?php
    $date_start = isset($_GET['date_start']) ? $_GET['date_start'] : date('Y-m-01');
    $date_end = isset($_GET['date_end']) ? $_GET['date_end'] : date('Y-m-t');
    ?>
    <?php if (!isset($_GET['detail'])) { ?>
        <div class="card shadow p-2 mb-1">
            <form method="get">
                <input type="text" name="halaman" value="dashboardstaff" hidden id="">
                <input type="text" name="tipe" value="rekapKunjunganDokterRanap" hidden id="">
                <div class="row g-1">
                    <div class="col-5">
                        <input type="text" onclick="this.type='date'" onfocus="this.type='date'" onblur="this.type='text'" placeholder="Dari Tanggal" name="date_start" class="form-control form-control-sm" value="<?= $date_start ?>">
                    </div>
                    <div class="col-5">
                        <input type="text" onclick="this.type='date'" onfocus="this.type='date'" onblur="this.type='text'" placeholder="Hingga Tanggal" name="date_end" class="form-control form-control-sm" value="<?= $date_end ?>">
                    </div>
                    <div class="col-2">
                        <button class="btn btn-sm btn-primary">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card shadow p-2">
            <h5 class="m-0">Rekap Kunjungan Dokter Ranap</h5>
            <div class="table-responsive">
                <table class="table table-sm table-hover table-striped" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Blan</th>
                            <th>Nama Dokter</th>
                            <th>Jumlah Pasien</th>
                            <th>Jumlah Shift</th>
                            <th>Jumlah Pasien/Shift</th>
                        </tr>
                    </thead>

                    <body>
                        <?php
                        $getData = $koneksi->query("SELECT DATE_FORMAT(ctt.tgl, '%M %Y') as Bulan, DATE_FORMAT(ctt.tgl, '%Y-%m') as bul, ctt.dokter, COUNT(DISTINCT DATE_FORMAT(ctt.tgl, '%Y-%m-%d'), ctt.shift) AS jumShift, COUNT(*) as jumlahPasien FROM ctt_penyakit_inap ctt WHERE DATE_FORMAT(tgl, '%Y-%m-%d') BETWEEN '$date_start' AND '$date_end' GROUP BY DATE_FORMAT(tgl, '%Y-%m'), ctt.dokter ORDER BY DATE_FORMAT(tgl, '%Y-%m') DESC, ctt.dokter ASC");
                        $no = 1;
                        foreach ($getData as $data) {
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $data['Bulan'] ?></td>
                                <td><?= $data['dokter'] ?></td>
                                <td>
                                    <a href="?halaman=dashboardstaff&tipe=rekapKunjunganDokterRanap&detail&date_start=<?= $date_start ?>&date_end=<?= $date_end ?>&detailDokter=<?= $data['dokter'] ?>&bulan=<?= $data['bul'] ?>" class="badge bg-warning">
                                        <?= $data['jumlahPasien'] ?>
                                    </a>
                                </td>
                                <td><?= $data['jumShift'] ?>x Jaga</td>
                                <td><?= number_format($data['jumlahPasien'] / $data['jumShift'], 2) ?></td>
                            </tr>
                        <?php }
                        ?>
                    </body>
                </table>
            </div>
        </div>
    <?php } else { ?>
        <div class="card shadow p-2">
            <h5 class="m-0">Detail Kunjungan Dokter Ranap</h5>
            <div class="table-responsive">
                <table class="table table-sm table-hover table-striped" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Dokter</th>
                            <th>Tanggal</th>
                            <th>Shift</th>
                            <th>Nama Pasien</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>

                    <body>
                        <?php
                        $detailDokter = htmlspecialchars($_GET['detailDokter']);
                        $bulan = htmlspecialchars($_GET['bulan']);
                        $getData = $koneksi->query("SELECT * FROM ctt_penyakit_inap WHERE dokter = '$detailDokter' AND DATE_FORMAT(tgl, '%Y-%m-%d') BETWEEN '$date_start' AND '$date_end' AND DATE_FORMAT(tgl, '%Y-%m') = '$bulan' ORDER BY tgl ASC, shift ASC");
                        $no = 1;
                        foreach ($getData as $data) {
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $data['dokter'] ?></td>
                                <td><?= $data['tgl'] ?></td>
                                <td><?= $data['shift'] ?></td>
                                <td><?= $data['pasien'] ?></td>
                                <td><?= $data['ctt_tedis'] ?></td>
                            </tr>
                        <?php }
                        ?>
                    </body>
                </table>
            </div>
        </div>
    <?php } ?>

</div>