<div class="card shadow p-2">
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>IdRawat</th>
                    <th>Tgl Kunjungan</th>
                    <th>Nama</th>
                    <th>NoRm</th>
                    <th>NoHp</th>
                    <th>CaraBayar</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $getData = $koneksi->query("SELECT registrasi_rawat.*, pasien.nama_lengkap, pasien.nohp FROM registrasi_rawat INNER JOIN pasien ON pasien.no_rm = registrasi_rawat.no_rm WHERE DATE_FORMAT(jadwal, '%y/%m') = '" . htmlspecialchars($_GET['polibulan']) . "' ORDER BY idrawat DESC");
                foreach ($getData as $data) {
                ?>
                    <tr>
                        <td><?= $data['idrawat'] ?></td>
                        <td><?= $data['jadwal'] ?></td>
                        <td><?= $data['nama_lengkap'] ?></td>
                        <td><?= $data['no_rm'] ?></td>
                        <td><?= $data['nohp'] ?></td>
                        <td><?= $data['carabayar'] ?></td>
                        <td><?= $data['status_antri'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>