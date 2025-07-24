<div class="card shadow p-2 mb-2">
    <form method="get">
        <div class="row g-1">
            <div class="col-5">
                <input type="text" hidden name="halaman" id="" value="dashboard_detail">
                <input type="text" hidden name="rataObatPasienBPJSperBulan" id="" value="">
                <input type="month" class="form-control form-control-sm" id="bulan" name="bulan"
                    value="<?= $bulanSaatIni = (isset($_GET['bulan']) ? htmlspecialchars($_GET['bulan']) : date('Y-m')) ?>">
            </div>
            <div class="col-5">
                <?php
                $carabayar = isset($_GET['carabayar']) ? htmlspecialchars($_GET['carabayar']) : 'bpjs';
                if ($carabayar == 'all') {
                    $whereCaraBayar = "AND (carabayar IN ('bpjs', 'umum', 'malam', 'gigi umum', 'gigi bpjs', 'spesialis anak', 'spesialis penyakit dalam', 'kosmetik'))";
                    $whereCaraBayarR = "AND (r.carabayar IN ('bpjs', 'umum', 'malam', 'gigi umum', 'gigi bpjs', 'spesialis anak', 'spesialis penyakit dalam', 'kosmetik'))";
                } else {
                    $whereCaraBayar = "AND carabayar = '$carabayar'";
                    $whereCaraBayarR = "AND r.carabayar = '$carabayar'";
                }
                ?>
                <select name="carabayar" class="form-select form-select-sm" id="">
                    <option value="all" <?= $carabayar == 'all' ? 'selected' : '' ?>>All</option>
                    <option value="bpjs" <?= $carabayar == 'bpjs' ? 'selected' : '' ?>>BPJS</option>
                    <option value="umum" <?= $carabayar == 'umum' ? 'selected' : '' ?>>Umum</option>
                    <option value="malam" <?= $carabayar == 'malam' ? 'selected' : '' ?>>Malam</option>
                    <option value="gigi umum" <?= $carabayar == 'gigi umum' ? 'selected' : '' ?>>Gigi Umum</option>
                    <option value="gigi bpjs" <?= $carabayar == 'gigi bpjs' ? 'selected' : '' ?>>Gigi BPJS</option>
                    <option value="spesialis anak" <?= $carabayar == 'spesialis anak' ? 'selected' : '' ?>>Spesialis Anak</option>
                    <option value="spesialis penyakit dalam" <?= $carabayar == 'spesialis penyakit dalam' ? 'selected' : '' ?>>Spesialis Penyakit Dalam</option>
                    <option value="kosmetik" <?= $carabayar == 'kosmetik' ? 'selected' : '' ?>>Kosmetik</option>
                </select>
            </div>
            <div class="col-2 align-self-end">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-search"></i></button>
            </div>
        </div>
    </form>
</div>
<div class="card shadow p-2">
    <h5 class="card-title mb-0 mt-0 text-capitalize">Rata Rata Obat Pasien <?= $carabayar ?> Per-Bulan</h5>
    <div class="table-responsive">
        <table class="table table-hover table-striped table-sm" style="font-size: 12px;">
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th>Jumlah Hari</th>
                    <th>Dokter</th>
                    <th>Jumlah Pasien</th>
                    <th>Biaya Total</th>
                    <th>Rata Rata</th>
                </tr>
            </thead>
            <?php
            $totalPasien = 0;
            $totalFinal = 0;
            $totalRata = 0;
            // Query data pasien per bulan & dokter
            $query = "
                SELECT DATE_FORMAT(jadwal, '%Y-%m') as bulan,
                    dokter_rawat,
                    COUNT(*) as jumlahpasien
                FROM registrasi_rawat
                WHERE perawatan = 'Rawat Jalan' 
                    " . $whereCaraBayar . " 
                    AND status_antri != 'Belum Datang'
                    AND DATE_FORMAT(jadwal, '%Y-%m') = '$bulanSaatIni'
                GROUP BY DATE_FORMAT(jadwal, '%Y-%m'), dokter_rawat
                ORDER BY DATE_FORMAT(jadwal, '%Y-%m') DESC, dokter_rawat;
            ";
            $getData = $koneksi->query($query);

            // Query semua data obat + harga sekaligus
            $allObat = [];
            $obatQuery = "
                SELECT DATE_FORMAT(o.tgl_pasien, '%Y-%m') AS bulan,
                    r.dokter_rawat,
                    o.kode_obat,
                    o.jml_dokter,
                    a.harga_beli
                FROM obat_rm o
                INNER JOIN registrasi_rawat r 
                    ON r.no_rm = o.idrm 
                    AND o.tgl_pasien = DATE_FORMAT(r.jadwal, '%Y-%m-%d')
                LEFT JOIN (
                    SELECT id_obat, harga_beli
                    FROM apotek a1
                    WHERE idapotek = (
                        SELECT MAX(idapotek) 
                        FROM apotek a2 
                        WHERE a2.id_obat = a1.id_obat
                    )
                ) a ON a.id_obat = o.kode_obat
                WHERE r.perawatan = 'Rawat Jalan'
                    " . $whereCaraBayarR . "
                    AND r.status_antri != 'Belum Datang'
                    AND o.rekam_medis_id IS NOT NULL
                    AND DATE_FORMAT(r.jadwal, '%Y-%m') = '$bulanSaatIni'
            ";
            $resultObat = $koneksi->query($obatQuery);

            // Kelompokkan obat per bulan + dokter
            foreach ($resultObat as $row) {
                $key = $row['bulan'] . '|' . $row['dokter_rawat'];
                if (!isset($allObat[$key])) $allObat[$key] = [];
                $allObat[$key][] = $row;
            }
            ?>

            <tbody>
                <?php foreach ($getData as $data): ?>
                    <?php
                    $bulan = $data['bulan'] == date('Y-m') ? date('Y-m') : $data['bulan'];
                    $dokter = $data['dokter_rawat'];
                    // $hari = date('t', mktime(0, 0, 0, substr($bulan, 0, 2), 1, substr($bulan, 3)));
                    $hari = $bulan == date('Y-m') ? date('d') : date('t', mktime(0, 0, 0, (int)substr($bulan, 0, 2), 1, (int)substr($bulan, 3)));

                    $key = $bulan . '|' . $dokter;
                    $total = 0;

                    if (isset($allObat[$key])) {
                        foreach ($allObat[$key] as $obat) {
                            $jumlah = is_numeric($obat['jml_dokter']) ? $obat['jml_dokter'] : 0;
                            $harga = is_numeric($obat['harga_beli']) ? $obat['harga_beli'] : 0;
                            $total += $jumlah * $harga;
                        }
                    }
                    ?>
                    <tr>
                        <td>
                            <?= $bulan ?>
                        </td>
                        <td><?= $hari ?> Hari</td>
                        <td><?= $dokter ?></td>
                        <td><?= $data['jumlahpasien'] ?> Pasien</td>
                        <td><?= number_format($total, 0, 0, '.') ?></td>
                        <td>
                            <?= number_format($total / $data['jumlahpasien'], 0, 0, '.') ?>
                        </td>
                    </tr>
                    <?php
                    $totalPasien += $data['jumlahpasien'];
                    $totalFinal += $total;
                    // $totalRata += ($total / $data['jumlahpasien']);
                    ?>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"><b>Total</b></td>
                    <td><b><?= number_format($totalPasien, 0, 0, '.') ?></b></td>
                    <td><b><?= number_format($totalFinal, 0, 0, '.') ?></b></td>
                    <td><b><?= number_format(($totalFinal / $totalPasien), 0, 0, '.') ?></b></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>