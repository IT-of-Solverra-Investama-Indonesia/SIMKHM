<div>
    <div class="card shadow p-2 mb-2">
        <h5><b>Rekap Pasien Berdasarkan Cara Bayar</b></h5>
        <form method="get">
            <input type="text" name="halaman" hidden value="dashboardinap" id="">
            <input type="text" name="dashboardinap" hidden value="RekapPasienCaraBayar" id="">
            <div class="row g-1">
                <div class="col-10">
                    <select name="carabayar" class="form-control form-control-sm" id="">
                        <option value="">Pilih Cara Bayar</option>
                        <option <?= isset($_GET['carabayar']) && $_GET['carabayar'] == 'umum' ? 'selected' : '' ?> value="umum">Umum</option>
                        <option <?= isset($_GET['carabayar']) && $_GET['carabayar'] == 'bpjs' ? 'selected' : '' ?> value="bpjs">BPJS</option>
                    </select>
                </div>
                <div class="col-2">
                    <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </div>
        </form>
    </div>
    <?php if (isset($_GET['carabayar'])) { ?>
        <div class="card shadow p-2">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th>Bulan</th>
                            <th>Cara Bayar</th>
                            <th>JumlahPasien</th>
                            <th>Umum</th>
                            <th>Obat</th>
                            <th>Lab</th>
                            <th>Total</th>
                            <th>Hari</th>
                            <th>TotalPerHari</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $carabayar = htmlspecialchars($_GET['carabayar']);

                        // Query yang diperbaiki dengan JOIN dan agregasi yang tepat
                        $query = "
                            SELECT 
                                DATE_FORMAT(r.jadwal, '%Y-%m') as bulan,
                                r.carabayar,
                                COUNT(DISTINCT r.idrawat) AS jumlahPasien,
                                SUM(CASE 
                                    WHEN rd.biaya NOT LIKE '%obat%' 
                                    AND rd.biaya NOT IN (SELECT tipe FROM daftartes) 
                                    THEN rd.besaran 
                                    ELSE 0 
                                END) AS umum,
                                SUM(CASE 
                                    WHEN rd.biaya LIKE '%obat%' 
                                    THEN rd.besaran 
                                    ELSE 0 
                                END) AS obat,
                                SUM(CASE 
                                    WHEN rd.biaya IN (SELECT tipe FROM daftartes) 
                                    THEN rd.besaran 
                                    ELSE 0 
                                END) AS lab,
                                SUM(rd.besaran) AS total,
                                DATE_FORMAT(r.jadwal, '%Y-%m') as bulan_check
                            FROM registrasi_rawat r
                            LEFT JOIN rawatinapdetail rd ON rd.id = r.idrawat
                            WHERE r.perawatan = 'Rawat Inap' 
                                AND r.carabayar = '$carabayar'
                            GROUP BY DATE_FORMAT(r.jadwal, '%Y-%m'), r.carabayar
                            ORDER BY bulan DESC
                        ";

                        $getData = $koneksi->query($query);

                        if (!$getData) {
                            echo '<tr><td colspan="9" class="text-danger">Error: ' . $koneksi->error . '</td></tr>';
                        } else {
                            while ($row = $getData->fetch_assoc()) {
                                $bulan = $row['bulan'];
                                $carabayar = $row['carabayar'];
                                $jumlahpasien = $row['jumlahPasien'];
                                $umum = $row['umum'];
                                $obat = $row['obat'];
                                $lab = $row['lab'];
                                $total = $row['total'];

                                // Hitung hari berdasarkan bulan
                                $bulanSekarang = date('Y-m');
                                if ($bulan == $bulanSekarang) {
                                    // Jika bulan sekarang, gunakan tanggal sekarang (tanpa leading zero)
                                    $hari = (int)date('j'); // j = tanggal tanpa leading zero
                                } else {
                                    // Jika bukan bulan sekarang, hitung total hari dalam bulan tersebut
                                    // Pisahkan tahun dan bulan dari format Y-m
                                    list($tahun, $bulanAngka) = explode('-', $bulan);
                                    $hari = cal_days_in_month(CAL_GREGORIAN, (int)$bulanAngka, (int)$tahun);
                                }

                                // Hitung total per hari
                                $totalperhari = $hari > 0 ? round($total / $hari, 2) : 0;
                        ?>
                                <tr>
                                    <td><?= $bulan ?></td>
                                    <td><?= $carabayar ?></td>
                                    <td><?= $jumlahpasien ?></td>
                                    <td><?= number_format($umum, 0, ',', '.') ?></td>
                                    <td><?= number_format($obat, 0, ',', '.') ?></td>
                                    <td><?= number_format($lab, 0, ',', '.') ?></td>
                                    <td><?= number_format($total, 0, ',', '.') ?></td>
                                    <td><?= $hari ?></td>
                                    <td><?= number_format($totalperhari, 0, ',', '.') ?></td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
            </div>
        </div>
    <?php } ?>
</div>