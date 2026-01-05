<div class="">
    <div class="card shadow p-2 m-0 mb-2">
        <h5 class="m-0">Rekap Kunjungan per Pendaftaran</h5>
        <form method="get">
            <input type="text" hidden name="halaman" value="<?= $_GET['halaman'] ?>" id="">
            <input type="text" hidden name="tipe" value="<?= $_GET['tipe'] ?>" id="">
            <div class="row g-1">
                <div class="col-5">
                    <input type="text" value="<?= $date_start = (isset($_GET['date_start']) ? htmlspecialchars($_GET['date_start']) : date('Y-m-01')) ?>" onblur="this.type='text'" onfocus="this.type='date'" placeholder="Pilih Tanggal" name="date_start" class="form-control form-control-sm" id="">
                </div>
                <div class="col-5">
                    <input type="text" value="<?= $date_end = (isset($_GET['date_end']) ? htmlspecialchars($_GET['date_end']) : date('Y-m-t')) ?>" onblur="this.type='text'" onfocus="this.type='date'" placeholder="Pilih Tanggal" name="date_end" class="form-control form-control-sm" id="">
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
        <div class="table-responsive">
            <table class="table table-sm table-hover table-striped" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th>Nama Staff</th>
                        <th>Shift</th>
                        <th>Pasien</th>
                        <th>Pasien/Shift</th>
                        <th>Datang</th>
                        <th>Datang/Shift</th>
                        <th>Tidak Datang</th>
                        <th>Tidak Datang/Shift</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $getData = $koneksi->query("SELECT DATE_FORMAT(jadwal, '%Y-%m') as bulan, petugaspoli, COUNT(*) as jumlahpasien, COUNT(CASE WHEN status_antri != 'Belum Datang' THEN 1 ELSE NULL END) as jumlahpasienDatang, COUNT(CASE WHEN status_antri = 'Belum Datang' THEN 1 ELSE NULL END) as jumlahpasienTidakDatang FROM registrasi_rawat WHERE DATE_FORMAT(jadwal, '%Y-%m-%d') BETWEEN '$date_start' AND '$date_end' GROUP BY DATE_FORMAT(jadwal, '%Y-%m'), petugaspoli ORDER BY jadwal DESC");
                    foreach ($getData as $data) {
                    ?>
                        <tr>
                            <td><?= $data['bulan'] ?></td>
                            <td><?= $data['petugaspoli'] ?></td>
                            <td>
                                <?php
                                $jumlahShift = $getJumlahShift = $koneksi->query("SELECT * FROM registrasi_rawat WHERE petugaspoli = '$data[petugaspoli]' AND DATE_FORMAT(jadwal, '%Y-%m-%d') BETWEEN '$date_start' AND '$date_end' GROUP BY DATE_FORMAT(jadwal, '%Y-%m-%d'), shift")->num_rows;
                                ?>
                                <?= $jumlahShift ?>x Jaga
                            </td>
                            <td><?= $data['jumlahpasien'] ?></td>
                            <td><?= number_format($data['jumlahpasien'] / $jumlahShift, 2) ?></td>
                            <td><?= $data['jumlahpasienDatang'] ?></td>
                            <td><?= number_format($data['jumlahpasienDatang'] / $jumlahShift, 2) ?></td>
                            <td><?= $data['jumlahpasienTidakDatang'] ?></td>
                            <td><?= number_format($data['jumlahpasienTidakDatang'] / $jumlahShift, 2) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>