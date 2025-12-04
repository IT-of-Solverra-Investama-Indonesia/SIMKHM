<?php
// API MECHANISM 
if (isset($_GET['api'])) {
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-cache, must-revalidate');
    if ($_GET['api'] == 'getTotalPerDokter') {
        include '../dist/function.php';
        $carabayar = isset($_GET['carabayar']) ? htmlspecialchars(string: $_GET['carabayar']) : 'bpjs';
        $bulan = isset($_GET['bulan']) ? htmlspecialchars(string: $_GET['bulan']) : date('Y-m');
        $dokter = isset($_GET['dokter']) ? htmlspecialchars(string: $_GET['dokter']) : '';

        $totalPasien = 0;
        $total = 0;

        if ($carabayar == 'all') {
            $whereCaraBayar = "AND (carabayar IN ('bpjs', 'umum', 'malam', 'gigi umum', 'gigi bpjs', 'spesialis anak', 'spesialis penyakit dalam', 'kosmetik'))";
            $whereCaraBayarR = "AND (r.carabayar IN ('bpjs', 'umum', 'malam', 'gigi umum', 'gigi bpjs', 'spesialis anak', 'spesialis penyakit dalam', 'kosmetik'))";
        } else {
            $whereCaraBayar = "AND carabayar = '$carabayar'";
            $whereCaraBayarR = "AND r.carabayar = '$carabayar'";
        }

        $getData = $koneksi->query("SELECT DATE_FORMAT(registrasi_rawat.jadwal, '%Y-%m') AS bulan, registrasi_rawat.*, obat_rm.*, (SELECT harga_beli FROM apotek WHERE id_obat = kode_obat AND DATE_FORMAT(tgl_beli, '%Y-%m') <= '" . htmlspecialchars($_GET['bulan']) . "' ORDER BY idapotek DESC LIMIT 1) AS hpp FROM obat_rm INNER JOIN registrasi_rawat ON DATE_FORMAT(registrasi_rawat.jadwal, '%Y-%m-%d') = obat_rm.tgl_pasien AND registrasi_rawat.no_rm = obat_rm.idrm WHERE DATE_FORMAT(registrasi_rawat.jadwal, '%Y-%m') = '" . htmlspecialchars($_GET['bulan']) . "' AND perawatan = 'Rawat Jalan' AND status_antri != 'Belum Datang' " . $whereCaraBayar . " AND dokter_rawat = '" . htmlspecialchars($_GET['dokter']) . "' AND (obat_rm.rekam_medis_id IS NOT NULL OR obat_rm.rekam_medis_id != '')");

        foreach ($getData as $data) {
            $total += (intval($data['hpp']) ?? 0) * (intval($data['jml_dokter']) ?? 0);
        }

        echo json_encode([
            'status' => 'success',
            'data' => [
                'total' => $total
            ]
        ]);
    }
    exit();
}
// END API MECHANISM
?>

<?php if (!isset($_GET['detail'])) { ?>
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
                // $query = "
                //     SELECT DATE_FORMAT(jadwal, '%Y-%m') as bulan,
                //         dokter_rawat,
                //         COUNT(*) as jumlahpasien
                //     FROM registrasi_rawat 
                //     WHERE perawatan = 'Rawat Jalan' 
                //         " . $whereCaraBayar . " 
                //         AND status_antri != 'Belum Datang'
                //         AND DATE_FORMAT(jadwal, '%Y-%m') = '$bulanSaatIni'
                //     GROUP BY DATE_FORMAT(jadwal, '%Y-%m'), dokter_rawat
                //     ORDER BY DATE_FORMAT(jadwal, '%Y-%m') DESC, dokter_rawat;
                // ";

                $query = "
                    SELECT DATE_FORMAT(jadwal, '%Y-%m') as bulan,
                        dokter_rawat,
                        COUNT(*) as jumlahpasien
                    FROM registrasi_rawat 
                    WHERE perawatan = 'Rawat Jalan' 
                        " . $whereCaraBayar . " 
                        AND status_antri != 'Belum Datang'
                        AND DATE_FORMAT(jadwal, '%Y-%m') = '$bulanSaatIni'
                        AND EXISTS (
                            SELECT 1 FROM obat_rm 
                            WHERE DATE_FORMAT(registrasi_rawat.jadwal, '%Y-%m-%d') = obat_rm.tgl_pasien 
                            AND registrasi_rawat.no_rm = obat_rm.idrm
                        )
                    GROUP BY DATE_FORMAT(jadwal, '%Y-%m'), dokter_rawat
                    ORDER BY DATE_FORMAT(jadwal, '%Y-%m') DESC, dokter_rawat
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
                            WHERE a2.id_obat = a1.id_obat AND DATE_FORMAT(a2.tgl_beli, '%Y-%m') <= '$bulanSaatIni'
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
                            <td>
                                <a href="index.php?halaman=dashboard_detail&rataObatPasienBPJSperBulan=&bulan=<?= $bulanSaatIni ?>&carabayar=<?= $carabayar ?>&dokter=<?= $dokter ?>&detail=detailTotal"
                                    class="badge bg-warning badge-api-total"
                                    style="font-size: 12px;"
                                    data-bulan="<?= $bulanSaatIni ?>"
                                    data-dokter="<?= $dokter ?>"
                                    data-carabayar="<?= $carabayar ?>"
                                    data-pasien="<?= $data['jumlahpasien'] ?>">
                                    <span class="spinner-border spinner-border-sm"></span>
                                </a>
                            </td>
                            <td class="td-rata-api">
                                <span class="spinner-border spinner-border-sm"></span>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const badges = document.querySelectorAll('.badge-api-total');

            badges.forEach(function(badge) {
                const bulan = badge.getAttribute('data-bulan');
                const dokter = badge.getAttribute('data-dokter');
                const carabayar = badge.getAttribute('data-carabayar');
                const jumlahPasien = parseInt(badge.getAttribute('data-pasien'));
                const rataCell = badge.closest('tr').querySelector('.td-rata-api');

                // Build URL
                const params = new URLSearchParams({
                    api: 'getTotalPerDokter',
                    bulan: bulan,
                    dokter: dokter,
                    carabayar: carabayar
                });

                const url = '../dashboard/dashboard_detail_rataObatPasienBPJSperBulan.php?' + params.toString();

                // AJAX request menggunakan XMLHttpRequest (mirip CURL)
                const xhr = new XMLHttpRequest();
                xhr.open('GET', url, true);
                xhr.setRequestHeader('Accept', 'application/json');

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);

                            if (response.status === 'success') {
                                const total = response.data.total;
                                const rataRata = Math.round(total / jumlahPasien);

                                // Format angka Indonesia
                                badge.textContent = total.toLocaleString('id-ID');
                                rataCell.textContent = rataRata.toLocaleString('id-ID');
                            } else {
                                badge.textContent = 'Error';
                                rataCell.textContent = 'Error';
                            }
                        } catch (e) {
                            console.error('Parse error:', e);
                            badge.textContent = 'Error';
                            rataCell.textContent = 'Error';
                        }
                    } else {
                        badge.textContent = 'Error';
                        rataCell.textContent = 'Error';
                    }
                };

                xhr.onerror = function() {
                    console.error('Network error');
                    badge.textContent = 'Error';
                    rataCell.textContent = 'Error';
                };

                xhr.send();
            });
        });
    </script>
<?php } else { ?>
    <?php if (htmlspecialchars($_GET['detail']) == 'detailTotal') { ?>
        <?php
        $carabayar = isset($_GET['carabayar']) ? htmlspecialchars($_GET['carabayar']) : 'bpjs';
        ?>
        <div class="card shadow p-2">
            <div class="card-header">
                <h5 class="card-title text-capitalize">Detail Total Obat Pasien <?= $carabayar ?> <?= date('F Y', strtotime($_GET['bulan'])) ?></h5>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-sm table-hover" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th>Bulan</th>
                            <th>Dokter Rawat</th>
                            <th>Pasien</th>
                            <th>Jadwal</th>
                            <th>Obat</th>
                            <th>Jumlah</th>
                            <th>HPP</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $totalPasien = 0;
                        $total = 0;

                        if ($carabayar == 'all') {
                            $whereCaraBayar = "AND (carabayar IN ('bpjs', 'umum', 'malam', 'gigi umum', 'gigi bpjs', 'spesialis anak', 'spesialis penyakit dalam', 'kosmetik'))";
                            $whereCaraBayarR = "AND (r.carabayar IN ('bpjs', 'umum', 'malam', 'gigi umum', 'gigi bpjs', 'spesialis anak', 'spesialis penyakit dalam', 'kosmetik'))";
                        } else {
                            $whereCaraBayar = "AND carabayar = '$carabayar'";
                            $whereCaraBayarR = "AND r.carabayar = '$carabayar'";
                        }
                        $getData = $koneksi->query("SELECT DATE_FORMAT(registrasi_rawat.jadwal, '%Y-%m') AS bulan, registrasi_rawat.*, obat_rm.*, (SELECT harga_beli FROM apotek WHERE id_obat = kode_obat AND DATE_FORMAT(tgl_beli, '%Y-%m') <= '" . htmlspecialchars($_GET['bulan']) . "' ORDER BY idapotek DESC LIMIT 1) AS hpp FROM obat_rm INNER JOIN registrasi_rawat ON DATE_FORMAT(registrasi_rawat.jadwal, '%Y-%m-%d') = obat_rm.tgl_pasien AND registrasi_rawat.no_rm = obat_rm.idrm WHERE DATE_FORMAT(registrasi_rawat.jadwal, '%Y-%m') = '" . htmlspecialchars($_GET['bulan']) . "' AND perawatan = 'Rawat Jalan' AND status_antri != 'Belum Datang' " . $whereCaraBayar . " AND dokter_rawat = '" . htmlspecialchars($_GET['dokter']) . "' AND (obat_rm.rekam_medis_id IS NOT NULL OR obat_rm.rekam_medis_id != '')");


                        ?>
                        <?php foreach ($getData as $data): ?>
                            <tr>
                                <td><?= $data['bulan'] ?></td>
                                <td><?= $data['dokter_rawat'] ?></td>
                                <td><?= $data['nama_pasien'] ?> <br> <b><?= $data['no_rm'] ?></b></td>
                                <td><?= $data['jadwal'] ?></td>
                                <td><?= $data['nama_obat'] ?></td>
                                <td><?= $data['jml_dokter'] ?></td>
                                <td><?= $data['hpp'] ?></td>
                                <td><?= (intval($data['hpp']) ?? 0) * (intval($data['jml_dokter']) ?? 0) ?></td>
                            </tr>
                            <?php
                            $total += (intval($data['hpp']) ?? 0) * (intval($data['jml_dokter']) ?? 0);
                            ?>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7"><b>Total</b></td>
                            <td><b>Rp <?= number_format($total, 0, 0, '.') ?></b></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    <?php } ?>
<?php } ?>