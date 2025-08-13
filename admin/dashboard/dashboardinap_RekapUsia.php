<div>
    <?php if (!isset($_GET['age_start'])) { ?>
        <div>
            <h4 class="mt-4">Rekap Usia Pasien Rawat Inap</h4>
            <div class="card shadow p-2 mb-2">
                <form method="get">
                    <input type="text" hidden name="halaman" value="dashboardinap" id="">
                    <input type="text" hidden name="dashboardinap" value="RekapUsia" id="">
                    <div class="row g-1">
                        <div class="col-5">
                            <label for="date_start">Dari Tanggal</label>
                            <input type="date" name="date_start" id="date_start" class="form-control form-control-sm" value="<?= $date_start = $_GET['date_start'] ?? date('Y-m-01') ?>">
                        </div>
                        <div class="col-5">
                            <label for="date_end">Hingga Tanggal</label>
                            <input type="date" name="date_end" id="date_end" class="form-control form-control-sm" value="<?= $date_end = $_GET['date_end'] ?? date('Y-m-d') ?>">
                        </div>
                        <div class="col-2">
                            <br>
                            <button name="searching" class="btn btn-sm btn-primary" type="submit"><i class="bi bi-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card shadow p-2">
                <table class="table table-sm table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Rentan Usia</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $rentang_usia = [
                            ['label' => '0-1 Tahun',    'start' => 0,  'end' => 1],
                            ['label' => '2-5 Tahun',    'start' => 2,  'end' => 5],
                            ['label' => '6-10 Tahun',   'start' => 6,  'end' => 10],
                            ['label' => '11-20 Tahun',  'start' => 11, 'end' => 20],
                            ['label' => '21-30 Tahun',  'start' => 21, 'end' => 30],
                            ['label' => '31-40 Tahun',  'start' => 31, 'end' => 40],
                            ['label' => '41-50 Tahun',  'start' => 41, 'end' => 50],
                            ['label' => '51-60 Tahun',  'start' => 51, 'end' => 60],
                            ['label' => '61-70 Tahun',  'start' => 61, 'end' => 70],
                            ['label' => '71-80 Tahun',  'start' => 71, 'end' => 80],
                            ['label' => '81-90 Tahun',  'start' => 81, 'end' => 90],
                            ['label' => '> 90 Tahun',   'start' => 91, 'end' => 10000000],
                        ];
                        foreach ($rentang_usia as $usia) {
                        ?>
                            <?php
                            $start = $usia['start'];
                            $end = $usia['end'];
                            ?>
                            <tr>
                                <td><?= $usia['label'] ?></td>
                                <td>
                                    <?php
                                    $getJumlahPasien = $koneksi->query("SELECT COUNT(*) as jum FROM registrasi_rawat JOIN pasien ON pasien.no_rm = registrasi_rawat.no_rm WHERE TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) BETWEEN $start AND $end AND perawatan = 'Rawat Inap' AND DATE_FORMAT(jadwal, '%Y-%m-%d') BETWEEN '$date_start' AND '$date_end'")->fetch_assoc();
                                    ?>
                                    <a href="index.php?halaman=<?= $_GET['halaman'] ?>&dashboardinap=<?= $_GET['dashboardinap'] ?>&date_start=<?= $date_start ?>&date_end=<?= $date_end ?>&searching=&age_start=<?= $start ?>&age_end=<?= $end ?>">
                                        <?= $getJumlahPasien['jum'] ?>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php } else { ?>
        <div class="mt-3">
            <?php
            $date_start = $_GET['date_start'] ?? date('Y-m-01');
            $date_end = $_GET['date_end'] ?? date('Y-m-d');
            $age_start = $_GET['age_start'] ?? 0;
            $age_end = $_GET['age_end'] ?? 10000;
            ?>
            <h5>Rekap Usia Pasien Rawat Inap</h5>
            <a href="index.php?halaman=<?= $_GET['halaman'] ?>&dashboardinap=<?= $_GET['dashboardinap'] ?>&date_start=<?= $date_start ?>&date_end=<?= $date_end ?>&searching=" style="font-size: 12px;" class="badge text-bg-dark"><i class="bi bi-arrow-left"></i> Kembali</a>
            <span style="font-size: 12px;" class="badge text-bg-secondary"><?= $date_start ?> s/d <?= $date_end ?></span>
            <span style="font-size: 12px;" class="badge text-bg-warning"><?= $age_start ?> - <?= $age_end ?> Tahun</span>
            <div class="card shadow p-2 mt-2">
                <div class="table-responsive">
                    <table class="table table-sm table-hover table-striped" style="font-size: 12px;">
                        <thead>
                            <tr>
                                <td>No</td>
                                <td>Nama</td>
                                <td>Usia</td>
                                <td>NoRM</td>
                                <td>Jadwal</td>
                                <td>Perawatan</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $getJumlahPasien = $koneksi->query("SELECT *, TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) AS usia FROM registrasi_rawat JOIN pasien ON pasien.no_rm = registrasi_rawat.no_rm WHERE TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) BETWEEN $age_start AND $age_end AND perawatan = 'Rawat Inap' AND DATE_FORMAT(jadwal, '%Y-%m-%d') BETWEEN '$date_start' AND '$date_end' ORDER BY jadwal ASC");
                            foreach ($getJumlahPasien as $row) {
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <?= $row['nama_pasien'] ?><br>
                                        <span style="font-size: 10px;"><?= $row['provinsi'] ?>|<?= $row['kota'] ?>|<?= $row['kecamatan'] ?>|<?= $row['kelurahan'] ?>|<?= $row['alamat'] ?></span>
                                    </td>
                                    <td><?= $row['usia'] ?> Tahun</td>
                                    <td><?= $row['no_rm'] ?></td>
                                    <td><?= $row['jadwal'] ?></td>
                                    <td><?= $row['perawatan'] ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php } ?>
</div>