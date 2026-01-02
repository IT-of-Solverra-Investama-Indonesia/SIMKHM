<div>
    <?php if (!isset($_GET['detailPetugas'])) { ?>
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
            <div class="row g-1">
                <div class="col-md-6">
                    <div class="card shadow p-2">
                        <h6><b>Perawat Mengisi/Tag IGD</b></h6>
                        <table class="table table-striped table-hover table-sm" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th>Bulan</th>
                                    <th>Petugas</th>
                                    <th>Pasien</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalPasien = 0;
                                $getData = $koneksi->query("SELECT DATE_FORMAT(rr.jadwal, '%M %Y') as Bulan, kj.petugas as Petugas, COUNT(rr.no_rm) as jumlahPasien FROM kajian_awal_inap_tag kj INNER JOIN registrasi_rawat rr ON rr.idrawat = kj.idrawat WHERE DATE_FORMAT(rr.jadwal, '%Y-%m') >= '$month_start' AND DATE_FORMAT(rr.jadwal, '%Y-%m') <= '$month_end' AND rr.perawatan = 'Rawat Inap' GROUP BY DATE_FORMAT(rr.jadwal, '%Y-%m'), kj.petugas ORDER BY DATE_FORMAT(rr.jadwal, '%Y-%m') DESC, kj.petugas ASC");
                                foreach ($getData as $data) {
                                ?>
                                    <tr>
                                        <td><?= $data['Bulan'] ?></td>
                                        <td>
                                            <?= $data['Petugas'] ?? "" ?>
                                        </td>
                                        <td>
                                            <a href="?halaman=dashboardstaff&tipe=rekapStaffInap&month_start=<?= $month_start ?>&month_end=<?= $month_end ?>&detailPetugas=<?= $data['Petugas'] ?>" class="badge bg-warning" style="font-size: 12px;">
                                                <?= $data['jumlahPasien'] ?> Pasien
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                    $totalPasien += $data['jumlahPasien'];
                                    ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow p-2">
                        <h6><b>Perawatan Mengisi/Tag Catatatan Penyakit</b></h6>
                        <table class="table table-striped table-hover table-sm" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th>Bulan</th>
                                    <th>Petugas</th>
                                    <th>Pasien</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalPasienIGD = 0;
                                $getDataIGD = $koneksi->query("SELECT DATE_FORMAT(cpi.tgl, '%M %Y') as Bulan, cpi.petugas as Petugas, COUNT(*) as jumlahPasien FROM ctt_penyakit_inap cpi WHERE DATE_FORMAT(cpi.tgl, '%Y-%m') >= '$month_start' AND DATE_FORMAT(cpi.tgl, '%Y-%m') <= '$month_end' GROUP BY DATE_FORMAT(cpi.tgl, '%Y-%m'), cpi.petugas ORDER BY DATE_FORMAT(cpi.tgl, '%Y-%m') DESC, cpi.petugas ASC");
                                foreach ($getDataIGD as $dataIGD) {
                                ?>
                                    <tr>
                                        <td><?= $dataIGD['Bulan'] ?></td>
                                        <td>
                                            <?= $dataIGD['Petugas'] ?? "" ?>
                                        </td>
                                        <td>
                                            <a href="?halaman=dashboardstaff&tipe=rekapStaffInap&month_start=<?= $month_start ?>&month_end=<?= $month_end ?>&detailPetugas=<?= $dataIGD['Petugas'] ?>&IGD" class="badge bg-warning" style="font-size: 12px;">
                                                <?= $dataIGD['jumlahPasien'] ?> Pasien
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                    $totalPasienIGD += $dataIGD['jumlahPasien'];
                                    ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <?php
        $month_start = isset($_GET['month_start']) ? htmlspecialchars($_GET['month_start']) : date('Y-m');
        $month_end = isset($_GET['month_end']) ? htmlspecialchars($_GET['month_end']) : date('Y-m');
        $detailPetugas = htmlspecialchars($_GET['detailPetugas']);
        ?>
        <?php if (!isset($_GET['IGD'])) { ?>
            <div class="card shadow p-2 mt-0">
                <div>
                    <a href="?halaman=dashboardstaff&tipe=rekapStaffInap&month_start=<?= $month_start ?>&month_end=<?= $month_end ?>" class="btn btn-sm btn-secondary mb-2"><i class="bi bi-arrow-left"></i> Kembali</a>
                </div>
                <h6><b>Detail Rekap Staff Inap <?= $detailPetugas ?> Pada <?= $month_start ?> Sampai <?= $month_end ?></b></h6>
                <div class="table-responsive">
                    <table class="table table-sm table-hover table-striped" style="font-size: 11px;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Petugas</th>
                                <th>Pasien</th>
                                <th>Jadwal Pasien</th>
                                <th>Input Kajian Pada</th>
                                <th>Dokter</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $getDetail = $koneksi->query("SELECT DATE_FORMAT(rr.jadwal, '%M %Y') as Bulan, kj.petugas as Petugas, rr.nama_pasien, rr.jadwal, kj.created_at as tglCatatan, rr.dokter_rawat FROM kajian_awal_inap_tag kj INNER JOIN registrasi_rawat rr ON rr.idrawat = kj.idrawat WHERE DATE_FORMAT(rr.jadwal, '%Y-%m') >= '$month_start' AND DATE_FORMAT(rr.jadwal, '%Y-%m') <= '$month_end' AND rr.perawatan = 'Rawat Inap' AND kj.petugas = '$detailPetugas' ORDER BY DATE_FORMAT(rr.jadwal, '%Y-%m-%d') DESC");
                            foreach ($getDetail as $detail) {
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $detail['Petugas'] ?></td>
                                    <td><?= $detail['nama_pasien'] ?></td>
                                    <td><?= $detail['jadwal'] ?></td>
                                    <td><?= $detail['tglCatatan'] ?></td>
                                    <td><?= $detail['dokter_rawat'] ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } else { ?>
            <div class="card shadow p-2 mt-0">
                <div>
                    <a href="?halaman=dashboardstaff&tipe=rekapStaffInap&month_start=<?= $month_start ?>&month_end=<?= $month_end ?>" class="btn btn-sm btn-secondary mb-2"><i class="bi bi-arrow-left"></i> Kembali</a>
                </div>
                <h6><b>Detail Rekap Staff IGD <?= $detailPetugas ?> Pada <?= $month_start ?> Sampai <?= $month_end ?></b></h6>
                <div class="table-responsive">
                    <table class="table table-sm table-hover table-striped" style="font-size: 11px;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Petugas</th>
                                <th>Pasien</th>
                                <th>Input Pada</th>
                                <th>Dokter</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $getDetail = $koneksi->query("SELECT cpi.tgl, cpi.petugas as Petugas, cpi.pasien as nama_pasien, cpi.tgl, cpi.dokter as dokter_rawat FROM ctt_penyakit_inap cpi WHERE DATE_FORMAT(cpi.tgl, '%Y-%m') >= '$month_start' AND DATE_FORMAT(cpi.tgl, '%Y-%m') <= '$month_end' AND cpi.petugas = '$detailPetugas' ORDER BY cpi.tgl DESC");
                            foreach ($getDetail as $detail) {
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $detail['Petugas'] ?></td>
                                    <td><?= $detail['nama_pasien'] ?></td>
                                    <td><?= $detail['tgl'] ?></td>
                                    <td><?= $detail['dokter_rawat'] ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
</div>