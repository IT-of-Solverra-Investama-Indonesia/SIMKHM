<div>
    <h5>Rekap Staff Inap</h5>
    <?php if (isset($_GET['ITSolverra'])) { ?>
        <?php
        $ambilAllUrl = $_SERVER['REQUEST_URI'];
        // $ambilAllUrl = strtok($_SERVER['REQUEST_URI'], '&repairShiftInap');

        if (isset($_GET['repairShiftInap'])) {
            // Query untuk memperbaiki data shift inap
            $koneksi->query("UPDATE rawatinapdetail SET shiftinap = CASE 
                WHEN TIME(created_at) BETWEEN '07:00:00' AND '14:00:00' THEN 'Pagi'
                WHEN TIME(created_at) BETWEEN '14:00:01' AND '21:00:00' THEN 'Sore'
                ELSE 'Malam'
            END
            WHERE shiftinap IS NULL OR shiftinap = ''");
            // -- WHERE 1=1");
            echo "
                <script>
                    alert('Data shift inap telah diperbaiki.');
                    window.location.href = 'index.php?halaman=dashboardstaff&tipe=rekapStaffInap&ITSolverra';
                </script>
            ";
        }

        ?>
        <a href="<?= $ambilAllUrl ?>&repairShiftInap" class="btn btn-sm btn-success mb-2">Repair Shift Inap</a>
    <?php } ?>
    <div class="card shadow p-2 mb-2">
        <form method="get">
            <div class="row g-1">
                <input type="text" name="halaman" value="dashboardstaff" hidden id="">
                <input type="text" name="tipe" value="rekapStaffInap" hidden id="">
                <div class="col-5">
                    <input type="month" value="<?= $month_start = (isset($_GET['month_start']) ? htmlspecialchars($_GET['month_start']) : date('Y-m')) ?>" onblur="this.type='text'" onfocus="this.type='month'" placeholder="Dari Bulan" name="month_start" class="form-control form-control-sm" id="">
                </div>
                <div class="col-5">
                    <input type="month" value="<?= $month_end = (isset($_GET['month_end']) ? htmlspecialchars($_GET['month_end']) : date('Y-m')) ?>" onblur="this.type='text'" onfocus="this.type='month'" placeholder="Hingga Bulan" name="month_end" class="form-control form-control-sm" id="">
                </div>
                <div class="col-2">
                    <button class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
                </div>
            </div>
        </form>
    </div>
    <div class="card shadow p-2">
        <table class="table table-striped table-hover table-sm" style="font-size: 12px;">
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th>Petugas</th>
                    <th>Shift</th>
                    <th>Pasien</th>
                    <th>Pasien/Shift</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $getData = $koneksi->query("SELECT *, DATE_FORMAT(tgl, '%M %Y') as bulan, petugas FROM rawatinapdetail WHERE DATE_FORMAT(tgl, '%Y-%m') BETWEEN '$month_start' AND '$month_end' GROUP BY DATE_FORMAT(tgl, '%M %Y'), petugas ORDER BY created_at DESC");
                foreach ($getData as $data) {
                ?>
                    <tr>
                        <td><?= $data['bulan'] ?></td>
                        <td>
                            <?php
                            $getUser = $koneksi->query("SELECT * FROM admin WHERE namalengkap = '$data[petugas]' OR username = '$data[petugas]'")->fetch_assoc();
                            ?>
                            <?= $getUser['namalengkap'] ?? "" ?>
                        </td>
                        <td>
                            <?php
                            $jumlahShift = $getJumlahShift = $koneksi->query("SELECT * FROM rawatinapdetail WHERE DATE_FORMAT(tgl, '%Y-%m') = DATE_FORMAT('$data[tgl]', '%Y-%m') AND petugas = '$data[petugas]' GROUP BY DATE_FORMAT(tgl, '%Y-%m-%d'), shiftinap")->num_rows;
                            ?>
                            <?= $jumlahShift ?>x Jaga
                        </td>
                        <td>
                            <?php
                            $jumlahPasien = $koneksi->query("SELECT * FROM rawatinapdetail WHERE DATE_FORMAT(tgl, '%Y-%m') = DATE_FORMAT('$data[tgl]', '%Y-%m') AND petugas = '$data[petugas]' GROUP BY id")->num_rows;
                            ?>
                            <?= $jumlahPasien ?> Pasien
                        </td>
                        <td>
                            <?= number_format($jumlahPasien / $jumlahShift, 2) ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>