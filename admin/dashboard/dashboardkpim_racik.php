<h5 class="card-title">KPIM Apoteker Racik</h5>
<div class="card shadow p-2 mb-1">
    <form method="GET">
        <div class="row g-1">
            <div class="col-5">
                <input type="text" name="halaman" value="dashboardkpim" hidden id="">
                <input type="text" name="kpim" value="racik" hidden id="">
                <label for="">Dari Bulan:</label>
                <input type="month" name="month_start" class="form-control form-control-sm" value="<?= $month_start = isset($_GET['month_start']) ? htmlspecialchars($_GET['month_start']) : date('Y-m', strtotime('-5 months')) ?>" required>
            </div>
            <div class="col-5">
                <label for="">Sampai Bulan:</label>
                <input type="month" name="month_end" class="form-control form-control-sm" value="<?= $month_end = isset($_GET['month_end']) ? htmlspecialchars($_GET['month_end']) : date('Y-m') ?>" required>
            </div>
            <div class="col-2 d-flex align-items-end">
                <button class="btn btn-sm btn-primary w-100"><i class="bi bi-search"></i></button>
            </div>
        </div>
    </form>
</div>
<div class="card shadow p-2">
    <div class="table-responsive">
        <table class="table table-sm table-striped table-hover" style="font-size: 12px;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Bulan</th>
                    <th>Pasien Datang</th>
                    <th>Nominal KPIM</th>
                    <th>Total KPIM</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $koneksi->query("DROP TABLE hari");
                    $koneksi->query(" 
                      CREATE TABLE IF NOT EXISTS hari 
                      SELECT DATE_FORMAT(jadwal, '%Y-%m') as bulan, DATE_FORMAT(jadwal, '%y-%m-%d') as hari from registrasi_rawat WHERE DATE_FORMAT(jadwal, '%Y-%m') >= '$month_start' AND DATE_FORMAT(jadwal, '%Y-%m') <= '$month_end' group by bulan,hari ");
                    $koneksi->query("DROP TABLE hari_jumlah");
                    $koneksi->query(" 
                      CREATE TABLE IF NOT EXISTS hari_jumlah 
                      SELECT bulan, count(hari) as harii from hari group by bulan");
                
                    $koneksi->query("DROP TABLE poli_jumlah");
                    $koneksi->query(" 
                      CREATE TABLE IF NOT EXISTS poli_jumlah 
                      SELECT DATE_FORMAT(jadwal, '%Y-%m') as bulan,
                        SUM(IF(carabayar='umum',1,0)) AS umum,
                        COUNT(no_rm) AS jumlah
                        FROM registrasi_rawat where status_antri = 'Datang' or status_antri = 'Pembayaran' AND perawatan = 'Rawat Jalan' AND DATE_FORMAT(jadwal, '%Y-%m') >= '$month_start' AND DATE_FORMAT(jadwal, '%Y-%m') <= '$month_end' group by bulan order by bulan desc
                    ");
                    $ambilpoli = $koneksi->query("SELECT * from poli_jumlah JOIN hari_jumlah On poli_jumlah.bulan=hari_jumlah.bulan order by poli_jumlah.bulan desc");
                    $no = 1;
                    while ($poli = $ambilpoli->fetch_assoc()) {
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= date('F Y', strtotime($poli['bulan'])) ?></td>
                        <td><?= $poli['jumlah'] ?></td>
                        <td>
                            <?php 
                                $nominalKPIM = 80;
                            ?>
                            <?= $nominalKPIM ?>
                        </td>
                        <td>
                            <?= 'Rp ' . number_format($poli['jumlah'] * $nominalKPIM, 0, ',', '.'); ?>
                        </td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
</div>