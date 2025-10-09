<div>
    <div class="card shadow p-2 m-0 mb-2">
        <h5 class="m-0">Rekap Pendapatan Kasir</h5>
        <form method="get">
            <div class="row g-1">
                <input type="text" name="halaman" value="dashboardstaff" hidden id="">
                <input type="text" name="tipe" value="rekapPendapatanKasir" hidden id="">
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
            <table class="table table-sm table-hover table-striped" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th>Nama Staff</th>
                        <th>Poli</th>
                        <th>Ranap Non BPJS</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalPoliFinal = 0;
                    $totalRawatInapNonBPJSFinal = 0;
                    $shift = "'Pagi','Sore','Malam'";
                    $getData = $koneksi->query("SELECT DATE_FORMAT(jadwal, '%Y-%m') as bulan, kasir FROM registrasi_rawat WHERE DATE_FORMAT(jadwal, '%Y-%m') = '$bulanSaatIni' GROUP BY DATE_FORMAT(jadwal, '%Y-%m'), kasir ORDER BY jadwal DESC");
                    foreach ($getData as $data) {
                    ?>
                        <tr>
                            <td><?= $data['bulan'] ?></td>
                            <td><?= $data['kasir'] ?></td>
                            <td>
                                <?php
                                // Query tunggal untuk semua biaya poli
                                $queryBiayaPoli = "
                                    SELECT br.poli, br.biaya_lab
                                    FROM registrasi_rawat rr
                                    INNER JOIN biaya_rawat br ON br.idregis = rr.idrawat
                                    WHERE rr.perawatan = 'Rawat Jalan' 
                                    AND rr.status_antri IN ('Datang', 'Pembayaran', 'Selesai')
                                    AND DATE_FORMAT(rr.jadwal, '%Y-%m') = '$bulanSaatIni'
                                    AND rr.kasir = '{$data['kasir']}'
                                    AND rr.shift IN ($shift)
                                    AND (
                                        rr.carabayar IN ('umum', 'malam', 'bpjs', 'spesialis anak', 'spesialis penyakit dalam', 'gigi umum', 'gigi bpjs')
                                    )
                                    GROUP BY rr.idrawat
                                ";

                                $totalPoli = 0;
                                $totalRawatInapNonBPJS = 0;
                                $getBiayaPoli = $koneksi->query($queryBiayaPoli);
                                while ($dataBiayaPoli = $getBiayaPoli->fetch_assoc()) {
                                    $totalPoli += $dataBiayaPoli['poli'];
                                    $totalPoli += ($dataBiayaPoli['biaya_lab'] == '' ? 0 : $dataBiayaPoli['biaya_lab']);
                                }

                                // Query tunggal untuk layanan
                                $queryLayanan = "
                                    SELECT l.layanan, COUNT(*) as JumlahLayanan
                                    FROM layanan l
                                    INNER JOIN registrasi_rawat rr ON rr.no_rm = l.idrm 
                                        AND DATE_FORMAT(rr.jadwal, '%Y-%m-%d') = DATE_FORMAT(l.tgl_layanan, '%Y-%m-%d')
                                    WHERE rr.perawatan = 'Rawat Jalan' 
                                    AND DATE_FORMAT(l.tgl_layanan, '%Y-%m') = '$bulanSaatIni'
                                    AND rr.kasir = '{$data['kasir']}'
                                    AND rr.shift IN ($shift)
                                    GROUP BY l.layanan
                                ";

                                $getLayanan = $koneksi->query($queryLayanan);
                                while ($dataLayanan = $getLayanan->fetch_assoc()) {
                                    $getHargaLayanan = $koneksimaster->query("SELECT harga FROM master_layanan WHERE nama_layanan = '{$dataLayanan['layanan']}'")->fetch_assoc();
                                    $totalPoli += (($getHargaLayanan['harga'] ?? 0) * $dataLayanan['JumlahLayanan']);
                                }
                                ?>
                                Rp. <?= number_format($totalPoli, 0, ',', '.') ?>
                            </td>
                            <td>
                                <?php
                                $getRawatinapNonBPJS = $koneksi->query("SELECT biaya, SUM(besaran) as harga FROM `pulang` INNER JOIN registrasi_rawat ON DATE_FORMAT(registrasi_rawat.jadwal, '%Y-%m-%d') = tgl_masuk AND registrasi_rawat.no_rm = pulang.norm INNER JOIN rawatinapdetail ON rawatinapdetail.id = registrasi_rawat.idrawat WHERE carabayar != 'bpjs' AND DATE_FORMAT(pulang.tgl, '%Y-%m') = '$bulanSaatIni' AND registrasi_rawat.kasir = '{$data['kasir']}' AND pulang.shift IN (" . $shift . ") GROUP BY biaya ORDER BY biaya ASC");
                                foreach ($getRawatinapNonBPJS as $rawatInapNonBPJS) {
                                    $totalRawatInapNonBPJS += $rawatInapNonBPJS['harga'];
                                }
                                ?>
                                Rp. <?= number_format($totalRawatInapNonBPJS, 0, ',', '.') ?>
                            </td>
                            <td>
                                Rp. <?= number_format($totalPoli + $totalRawatInapNonBPJS, 0, ',', '.') ?>
                            </td>
                        </tr>
                        <?php
                        $totalPoliFinal += $totalPoli;
                        $totalRawatInapNonBPJSFinal += $totalRawatInapNonBPJS;
                        ?>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2"><b>Total</b></td>
                        <td>
                            <b>
                                Rp. <?= number_format($totalPoliFinal, 0, ',', '.') ?>
                            </b>
                        </td>
                        <td>
                            <b>
                                Rp. <?= number_format($totalRawatInapNonBPJSFinal, 0, ',', '.') ?>
                            </b>
                        </td>
                        <td>
                            <b>
                                Rp. <?= number_format($totalPoliFinal + $totalRawatInapNonBPJSFinal, 0, ',', '.') ?>
                            </b>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>