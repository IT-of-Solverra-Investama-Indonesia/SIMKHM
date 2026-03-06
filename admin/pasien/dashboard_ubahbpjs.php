<div class="card shadow p-2">
    <?php if (!isset($_GET['detail'])) { ?>
        <table class="table table-sm table-hover table-striped">
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th>Perubah</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $getData = $koneksi->query("SELECT DATE_FORMAT(tanggal, '%Y-%m') as bulan, user, COUNT(*) as jumlah FROM ubahbpjs GROUP BY DATE_FORMAT(tanggal, '%Y%m'), user ORDER BY tanggal DESC");
                foreach ($getData as $data) {
                ?>
                    <tr>
                        <td><?php echo $data['bulan']; ?></td>
                        <td><?php echo $data['user']; ?></td>
                        <td>
                            <a href="?halaman=dashboard_ubahbpjs&detail&bulan=<?php echo $data['bulan']; ?>&user=<?php echo $data['user']; ?>" class="badge bg-warning text-dark">
                                <?php echo $data['jumlah']; ?>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <table class="table table-sm table-hover table-striped" style="font-size: 12px;">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Pasien</th>
                    <th>NIK</th>
                    <th>Asal Faskes</th>
                    <th>User</th>
                    <th>Foto</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $bulan = $_GET['bulan'];
                $user = $_GET['user'];
                $getData = $koneksi->query("SELECT * FROM ubahbpjs WHERE DATE_FORMAT(tanggal, '%Y-%m') = '$bulan' AND user = '$user' ORDER BY tanggal DESC");
                foreach ($getData as $data) {
                ?>
                    <tr>
                        <td><?php echo $data['tanggal']; ?></td>
                        <td><?php echo $data['nama']; ?></td>
                        <td><?php echo $data['nik']; ?></td>
                        <td><?php echo $data['asal_faskes']; ?></td>
                        <td><?php echo $data['user']; ?></td>
                        <td><img src="<?= htmlspecialchars($data['foto']) ?>" alt="" width="60"></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
</div>