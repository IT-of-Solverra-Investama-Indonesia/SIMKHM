<div class="card shadow p-2">
    <h5 class="card-title mb-0 mt-0">Rata Rata Obat Pasien BPJS Per-Bulan</h5>
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
            // Query data pasien per bulan & dokter
            $query = "
    SELECT DATE_FORMAT(jadwal, '%m-%Y') as bulan,
           dokter_rawat,
           COUNT(*) as jumlahpasien
    FROM registrasi_rawat
    WHERE perawatan = 'Rawat Jalan' 
        AND carabayar = 'bpjs' 
        AND status_antri != 'Belum Datang'
    GROUP BY DATE_FORMAT(jadwal, '%m-%Y'), dokter_rawat
    ORDER BY DATE_FORMAT(jadwal, '%Y-%m') DESC, dokter_rawat;
";
            $getData = $koneksi->query($query);

            // Query semua data obat + harga sekaligus
            $allObat = [];
            $obatQuery = "
    SELECT DATE_FORMAT(o.tgl_pasien, '%m-%Y') AS bulan,
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
        AND r.carabayar = 'bpjs'
        AND r.status_antri != 'Belum Datang'
        AND o.rekam_medis_id IS NOT NULL;
";
            $resultObat = $koneksi->query($obatQuery);

            // Kelompokkan obat per bulan + dokter
            while ($row = $resultObat->fetch_assoc()) {
                $key = $row['bulan'] . '|' . $row['dokter_rawat'];
                if (!isset($allObat[$key])) $allObat[$key] = [];
                $allObat[$key][] = $row;
            }
            ?>

            <tbody>
                <?php foreach ($getData as $data): ?>
                    <?php
                    $bulan = $data['bulan'];
                    $dokter = $data['dokter_rawat'];
                    $hari = date('t', mktime(0, 0, 0, substr($bulan, 0, 2), 1, substr($bulan, 3)));

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
                        <td><?= $bulan ?></td>
                        <td><?= $hari ?> Hari</td>
                        <td><?= $dokter ?></td>
                        <td><?= $data['jumlahpasien'] ?> Pasien</td>
                        <td><?= number_format($total, 0, 0, '.') ?></td>
                        <td>
                            <?= number_format($total / $hari, 0, 0, '.') ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</div>