<div class="">
    <div class="card shadow p-2 mb-1">
        <form method="get">
            <div class="row g-1">
                <input type="text" name="halaman" value="dashboardkpim" hidden id="">
                <input type="text" name="kpim" value="kasir" hidden id="">
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