<div class="">
    <h5 class="card-title">Dashboard Bonus KPIM</h5>
    <div class="card shadow p-2 mb-1">
        <form method="get">
            <div class="row g-1">
                <input type="text" name="halaman" value="dashboardstaff" hidden id="">
                <input type="text" name="tipe" value="rekapBonusKPIMStaff" hidden id="">
                <div class="col-5">
                    <input type="text" name="date_start" id="" class="form-control form-control-sm" onclick="(this.type='date')" onblur="(this.type='text')" placeholder="Tanggal Mulai" value="<?= isset($_GET['date_start']) ? htmlspecialchars($_GET['date_start']) : date('Y-m-01')  ?>">
                </div>
                <div class="col-5">
                    <input type="text" name="date_end" id="" class="form-control form-control-sm" onclick="(this.type='date')" onblur="(this.type='text')" placeholder="Tanggal Akhir" value="<?= isset($_GET['date_end']) ? htmlspecialchars($_GET['date_end']) : date('Y-m-t')  ?>">
                </div>
                <div class="col-2">
                    <button class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
                </div>
            </div>
        </form>
    </div>
    <div class="row g-1">
        <div class="col-6">
            <div class="card shadow p-2 h-100">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-sm" style="font-size: 12px;">
                        <thead>
                            <tr>
                                <th>Staff Kasir</th>
                                <th>Jumlah KPIM</th>
                                <th>Bonus</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $kpiKasir = 250;
                            $date_start = isset($_GET['date_start']) ? htmlspecialchars($_GET['date_start']) : date('Y-m-01');
                            $date_end = isset($_GET['date_end']) ? htmlspecialchars($_GET['date_end']) : date('Y-m-t');
                            $getData = $koneksi->query("SELECT petugas, COUNT(*) as JumlahKPIM FROM registrasi_rawat_tag WHERE tipe = 'Kasir' AND DATE_FORMAT(created_at, '%Y-%m-%d') BETWEEN '$date_start' AND '$date_end' GROUP BY petugas ORDER BY JumlahKPIM DESC");
                            foreach ($getData as $data) {
                            ?>
                                <tr>
                                    <td><?php echo $data['petugas']; ?></td>
                                    <td><?php echo $data['JumlahKPIM']; ?>x</td>
                                    <td><?php echo $data['JumlahKPIM']; ?>x<?= $kpiKasir ?>: <?php echo 'Rp ' . number_format($data['JumlahKPIM'] * $kpiKasir, 0, ',', '.'); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card shadow p-2 h-100">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-sm" style="font-size: 12px;">
                        <thead>
                            <tr>
                                <th>Staff Pendaftaran</th>
                                <th>Jumlah KPIM</th>
                                <th>Bonus</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $kpiPendaftaran = 250;
                            $getData = $koneksi->query("SELECT petugas, COUNT(*) as JumlahKPIM FROM registrasi_rawat_tag WHERE tipe = 'Pendaftaran' AND DATE_FORMAT(created_at, '%Y-%m-%d') BETWEEN '$date_start' AND '$date_end' GROUP BY petugas ORDER BY JumlahKPIM DESC");
                            foreach ($getData as $data) {
                            ?>
                                <tr>
                                    <td><?php echo $data['petugas']; ?></td>
                                    <td><?php echo $data['JumlahKPIM']; ?>x</td>
                                    <td><?php echo $data['JumlahKPIM']; ?>x<?= $kpiPendaftaran ?>: <?php echo 'Rp ' . number_format($data['JumlahKPIM'] * $kpiPendaftaran, 0, ',', '.'); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card shadow p-2">
                <div class="table-responsive">
                    <table class="table-sm table table-striped table-hover" style="font-size: 12px;">
                        <thead>
                            <tr>
                                <th>Staff Perawat Poli</th>
                                <th>Jumlah KPIM</th>
                                <th>Bonus</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php
                                $kpiPerawatPoli = 300;
                                $getData = $koneksi->query("SELECT perawat, COUNT(*) as JumlahKPIM FROM registrasi_rawat WHERE perawatan = 'Rawat Jalan' AND DATE_FORMAT(jadwal, '%Y-%m-%d') BETWEEN '$date_start' AND '$date_end' GROUP BY perawat ORDER BY JumlahKPIM DESC");
                                foreach ($getData as $data) {
                                ?>
                                    <tr>
                                        <td><?php echo $data['perawat']; ?></td>
                                        <td><?php echo $data['JumlahKPIM']; ?>x</td>
                                        <td><?php echo $data['JumlahKPIM']; ?>x<?= $kpiPerawatPoli ?>: <?php echo 'Rp ' . number_format($data['JumlahKPIM'] * $kpiPerawatPoli, 0, ',', '.'); ?></td>
                                    </tr>
                                <?php } ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>