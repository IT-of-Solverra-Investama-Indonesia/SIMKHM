<div class="">
    <div class="card shadow p-2 m-0 mb-2">
        <h5 class="m-0">Rekap Kunjungan per Pendaftaran</h5>
    </div>
    <div class="card shadow p-2">
        <div class="table-responsive">
            <table class="table table-sm table-hover table-striped" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th>Nama Staff</th>
                        <th>Jumlah Pasien</th>
                        <th>Jumlah Pasien Datang</th>
                        <th>Jumlah Pasien Tidak Datang</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $getData = $koneksi->query("SELECT DATE_FORMAT(jadwal, '%Y-%m') as bulan, petugaspoli, COUNT(*) as jumlahpasien, COUNT(CASE WHEN status_antri != 'Belum Datang' THEN 1 ELSE NULL END) as jumlahpasienDatang, COUNT(CASE WHEN status_antri = 'Belum Datang' THEN 1 ELSE NULL END) as jumlahpasienTidakDatang FROM registrasi_rawat GROUP BY DATE_FORMAT(jadwal, '%Y-%m'), petugaspoli ORDER BY jadwal DESC");
                    foreach ($getData as $data) {
                    ?>
                        <tr>
                            <td><?= $data['bulan'] ?></td>
                            <td><?= $data['petugaspoli'] ?></td>
                            <td><?= $data['jumlahpasien'] ?></td>
                            <td><?= $data['jumlahpasienDatang'] ?></td>
                            <td><?= $data['jumlahpasienTidakDatang'] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>