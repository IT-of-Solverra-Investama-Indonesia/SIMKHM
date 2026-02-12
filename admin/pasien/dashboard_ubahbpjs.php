<div class="card shadow p-2">
    <table class="table table-sm">
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
                <td><?php echo $data['jumlah']; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>