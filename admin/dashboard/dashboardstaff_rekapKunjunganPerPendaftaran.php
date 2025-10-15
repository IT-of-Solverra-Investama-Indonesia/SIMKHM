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
                    $getData = $koneksi->query("SELECT DATE_FORMAT(jadwal, '%Y-%m') as bulan, petugaspoli, COUNT(*) as jumlahpasien, COUNT(CASE WHEN status_antri != 'Belum Datang' THEN 1 ELSE NULL END) as jumlahpasienDatang, COUNT(CASE WHEN status_antri = 'Belum Datang' THEN 1 ELSE NULL END) as jumlahpasienTidakDatang FROM registrasi_rawat GROUP BY DATE_FORMAT(jadwal, '%Y-%m'), petugaspoli ORDER BY jadwal DESC");
                    foreach ($getData as $data) {
                    ?>
                        <tr>
                            <td><?= $data['bulan'] ?></td>
                            <td><?= $data['petugaspoli'] ?></td>
                            <td>
                                <?php
                                $jumlahShift = $getJumlahShift = $koneksi->query("SELECT * FROM registrasi_rawat WHERE DATE_FORMAT(jadwal, '%Y-%m') = '$data[bulan]' AND petugaspoli = '$data[petugaspoli]' GROUP BY DATE_FORMAT(jadwal, '%Y-%m-%d'), shift")->num_rows;
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