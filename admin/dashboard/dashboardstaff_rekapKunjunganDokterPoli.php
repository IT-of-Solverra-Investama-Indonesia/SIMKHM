<div class="card shadow p-2">
    <h5 class="m-0">Rekap Kunjungan Dokter Poli</h5>
    <form method="get">
        <div class="row g-1">
            <input type="text" name="halaman" value="dashboardstaff" hidden id="">
            <input type="text" name="tipe" value="rekapKunjunganDokterPoli" hidden id="">
            <div class="col-9">
                <input type="month" value="<?= $bulanSaatIni = (isset($_GET['bulan']) ? htmlspecialchars($_GET['bulan']) : date('Y-m')) ?>" onblur="this.type='text'" onfocus="this.type='month'" placeholder="Pilih Bulan" name="bulan" class="form-control form-control-sm" id="">
            </div>
            <div class="col-3">
                <button class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
            </div>
        </div>
    </form>
</div>
<div class="card shadow p-2">
    <div class="table-responsive">
        <table class="table table-striped table-hover table-sm" style="font-size: 12px;">
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th>Dokter</th>
                    <th>JumlahPasienDatang</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $getData = $koneksi->query("SELECT DATE_FORMAT(jadwal, '%Y-%m') as bulan, dokter_rawat, COUNT(*) as JumlahPasienPoliDatang FROM registrasi_rawat WHERE perawatan = 'Rawat Jalan' AND status_antri != 'Belum Datang' and DATE_FORMAT(jadwal, '%Y-%m') = '$bulanSaatIni' GROUP BY DATE_FORMAT(jadwal, '%Y-%m'), dokter_rawat ORDER BY jadwal DESC");
                foreach ($getData as $data) {
                ?>
                    <tr>
                        <td><?php echo $data['bulan']; ?></td>
                        <td><?php echo $data['dokter_rawat']; ?></td>
                        <td><?php echo $data['JumlahPasienPoliDatang']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>