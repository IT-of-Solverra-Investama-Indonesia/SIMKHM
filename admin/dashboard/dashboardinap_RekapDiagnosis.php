<div>
    <?php if (!isset($_GET['diagnosis'])) { ?>
        <div>
            <h4 class="mt-4">Rekap Diagnosis Pasien Rawat Inap</h4>
            <div class="card shadow p-2 mb-2">
                <form method="get">
                    <input type="text" hidden name="halaman" value="dashboardinap" id="">
                    <input type="text" hidden name="dashboardinap" value="RekapDiagnosis" id="">
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
                            <th>Nama Desa</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $getJumlahPasien = $koneksi->query("SELECT *, REPLACE(diagnosa_masuk, '+', '') as hehe, COUNT(*) as jum FROM kajian_awal_inap JOIN registrasi_rawat ON registrasi_rawat.jadwal = kajian_awal_inap.jadwal AND registrasi_rawat.no_rm = kajian_awal_inap.norm WHERE DATE_FORMAT(kajian_awal_inap.jadwal, '%Y-%m-%d') BETWEEN '$date_start' AND '$date_end' GROUP BY diagnosa_masuk");
                        foreach ($getJumlahPasien as $pasien) {
                        ?>
                            <tr>
                                <td><?= $pasien['diagnosa_masuk'] ?></td>
                                <td>
                                    <a href="index.php?halaman=<?= $_GET['halaman'] ?>&dashboardinap=<?= $_GET['dashboardinap'] ?>&date_start=<?= $date_start ?>&date_end=<?= $date_end ?>&searching=&diagnosis=<?= $pasien['hehe'] ?>">
                                        <?= $pasien['jum'] ?>
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
            $diagnosis = $_GET['diagnosis'] ?? '';
            ?>
            <h5>Rekap Diagnosis Pasien Rawat Inap</h5>
            <a href="index.php?halaman=<?= $_GET['halaman'] ?>&dashboardinap=<?= $_GET['dashboardinap'] ?>&date_start=<?= $date_start ?>&date_end=<?= $date_end ?>&searching=" style="font-size: 12px;" class="badge text-bg-dark"><i class="bi bi-arrow-left"></i> Kembali</a>
            <span style="font-size: 12px;" class="badge text-bg-secondary"><?= $date_start ?> s/d <?= $date_end ?></span>
            <span style="font-size: 12px;" class="badge text-bg-warning"><?= $diagnosis ?></span>
            <div class="card shadow p-2 mt-2">
                <div class="table-responsive">
                    <table class="table table-sm table-hover table-striped" style="font-size: 12px;">
                        <thead>
                            <tr>
                                <td>No</td>
                                <td>Nama</td>
                                <td>Diagnosis</td>
                                <td>NoRM</td>
                                <td>Jadwal</td>
                                <td>Perawatan</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $getJumlahPasien = $koneksi->query("SELECT *,REPLACE(diagnosa_masuk, '+', '') as hehe FROM kajian_awal_inap JOIN registrasi_rawat ON registrasi_rawat.jadwal = kajian_awal_inap.jadwal AND registrasi_rawat.no_rm = kajian_awal_inap.norm JOIN pasien ON pasien.no_rm = registrasi_rawat.no_rm WHERE DATE_FORMAT(kajian_awal_inap.jadwal, '%Y-%m-%d') BETWEEN '$date_start' AND '$date_end'AND REPLACE(diagnosa_masuk, '+', '') = '$diagnosis'");
                            foreach ($getJumlahPasien as $row) {
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <?= $row['nama_pasien'] ?><br>
                                        <span style="font-size: 10px;"><?= $row['provinsi'] ?>|<?= $row['kota'] ?>|<?= $row['kecamatan'] ?>|<?= $row['kelurahan'] ?>|<?= $row['alamat'] ?></span>
                                    </td>
                                    <td><?= $row['hehe'] ?></td>
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