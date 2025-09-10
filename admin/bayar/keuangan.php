<div class="p-0">
    <?php
    $tanggal = $_GET['tanggal'] ?? date('Y-m-d');
    $shift = "'" . ($_GET['shift'] ?? $_SESSION['shift']) . "'";
    if ($shift == "'All'") {
        $shift = "'Pagi', 'Sore', 'Malam'";
    }
    ?>
    <h5 class="card-title">Keuangan</h5>
    <a target="_blank" href="../bayar/keuanganPrint.php?tanggal=<?= $tanggal ?>&shift=<?= $shift ?>" class="btn btn-sm btn-warning mb-1"><i class="bi bi-printer"></i> Print</a>
    <div class="card shadow p-2 mb-2">
        <form method="GET">
            <div class="row">
                <div class="col-6">
                    <input type="text" name="halaman" value="keuangan" hidden id="">
                    <input name="tanggal" id="" value="<?= $tanggal ?>" class="form-control form-control-sm" onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="Pilih Tanggal">
                </div>
                <div class="col-3">
                    <select name="shift" id="" class="form-control form-control-sm">
                        <option value="All">All</option>
                        <!-- <option  value="<?= $shift ?>"><?= $shift ?></option> -->
                        <option value="Pagi">Pagi</option>
                        <option value="Sore">Sore</option>
                        <option value="Malam">Malam</option>
                    </select>
                </div>
                <div class="col-3">
                    <button class="btn btn-sm btn-primary" name="src"><i class="bi bi-search"></i></button>
                    <!-- <button class="btn btn-sm btn-primary" name="src"><i class="bi bi-search"></i> BPJS</button> -->
                </div>
            </div>
        </form>
    </div>
    <div class="card shadow p-2">
        <?php
        $totalPoli = 0;
        $totalRawatInapBPJS = 0;
        $totalRawatInapNonBPJS = 0;
        ?>
        <table class="table table-sm table-hover " style="font-size: 12px;">
            <thead>
                <tr>
                    <th>Keterangan</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2"><b>Poli</b></td>
                </tr>
                <tr>
                    <td>Biaya Poli Umum</td>
                    <td>
                        <?php
                        $totalBiayaPoli = 0;
                        $totalLab = 0;
                        $getBiayaPoli = $koneksi->query("SELECT *, registrasi_rawat.kasir, registrasi_rawat.shift FROM registrasi_rawat INNER JOIN biaya_rawat ON biaya_rawat.idregis = registrasi_rawat.idrawat WHERE perawatan = 'Rawat Jalan' and (status_antri = 'Datang' or status_antri = 'Pembayaran' or status_antri = 'Selesai') and date_format(jadwal, '%Y-%m-%d') = '$tanggal' AND registrasi_rawat.shift IN (" . $shift . ") and (registrasi_rawat.carabayar = 'umum' OR registrasi_rawat.carabayar = 'malam' OR registrasi_rawat.carabayar = 'bpjs') GROUP BY registrasi_rawat.idrawat");
                        foreach ($getBiayaPoli as $dataBiayaPoli) {
                            $biayaRawat = $koneksi->query("SELECT * FROM biaya_rawat WHERE idregis= '$dataBiayaPoli[idrawat]' ORDER BY id DESC LIMIT 1")->fetch_assoc();

                            $totalBiayaPoli += $biayaRawat['poli'];
                            $totalLab += ($biayaRawat['biaya_lab'] == '' ? 0 : $biayaRawat['biaya_lab']);
                        }
                        ?>
                        Rp <?= number_format($totalBiayaPoli, 0, 0, '.') ?>
                        <?php
                        $totalPoli += ($totalBiayaPoli);
                        // $totalPoli += ($totalLab);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Biaya Poli Spesialis Anak dan Penyakit Dalam</td>
                    <td>
                        <?php
                        $totalBiayaPoli = 0;
                        // $totalLab = 0;
                        $getBiayaPoli = $koneksi->query("SELECT *, registrasi_rawat.kasir, registrasi_rawat.shift FROM registrasi_rawat INNER JOIN biaya_rawat ON biaya_rawat.idregis = registrasi_rawat.idrawat WHERE perawatan = 'Rawat Jalan' and (status_antri = 'Datang' or status_antri = 'Pembayaran' or status_antri = 'Selesai') and date_format(jadwal, '%Y-%m-%d') = '$tanggal' AND registrasi_rawat.shift IN (" . $shift . ") and (registrasi_rawat.carabayar = 'spesialis anak' OR registrasi_rawat.carabayar = 'spesialis penyakit dalam') GROUP BY registrasi_rawat.idrawat");
                        // $getBiayaPoli = $koneksi->query("SELECT *, registrasi_rawat.kasir, registrasi_rawat.shift FROM registrasi_rawat INNER JOIN biaya_rawat ON biaya_rawat.idregis = registrasi_rawat.idrawat WHERE perawatan = 'Rawat Jalan' and (status_antri = 'Datang' or status_antri = 'Pembayaran' or status_antri = 'Selesai') and date_format(jadwal, '%Y-%m-%d') = '$tanggal' AND registrasi_rawat.shift = 'Pagi' and registrasi_rawat.carabayar LIKE '%spesialis%' GROUP BY registrasi_rawat.idrawat");
                        foreach ($getBiayaPoli as $dataBiayaPoli) {
                            $biayaRawat = $koneksi->query("SELECT * FROM biaya_rawat WHERE idregis= '$dataBiayaPoli[idrawat]' ORDER BY id DESC LIMIT 1")->fetch_assoc();

                            $totalBiayaPoli += $biayaRawat['poli'];
                            $totalLab += ($biayaRawat['biaya_lab'] == '' ? 0 : $biayaRawat['biaya_lab']);
                        }
                        // 
                        ?>
                        Rp <?= number_format($totalBiayaPoli, 0, 0, '.') ?>
                        <?php
                        $totalPoli += ($totalBiayaPoli);
                        // $totalPoli += ($totalLab);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Biaya Poli Gigi</td>
                    <td>
                        <?php
                        $totalBiayaPoli = 0;
                        // $totalLab = 0;
                        $getBiayaPoli = $koneksi->query("SELECT *, registrasi_rawat.kasir, registrasi_rawat.shift FROM registrasi_rawat INNER JOIN biaya_rawat ON biaya_rawat.idregis = registrasi_rawat.idrawat WHERE perawatan = 'Rawat Jalan' and (status_antri = 'Datang' or status_antri = 'Pembayaran' or status_antri = 'Selesai') and date_format(jadwal, '%Y-%m-%d') = '$tanggal' AND registrasi_rawat.shift IN (" . $shift . ") and (registrasi_rawat.carabayar = 'gigi umum' OR registrasi_rawat.carabayar = 'gigi bpjs') GROUP BY registrasi_rawat.idrawat");
                        // $getBiayaPoli = $koneksi->query("SELECT *, registrasi_rawat.kasir, registrasi_rawat.shift FROM registrasi_rawat INNER JOIN biaya_rawat ON biaya_rawat.idregis = registrasi_rawat.idrawat WHERE perawatan = 'Rawat Jalan' and (status_antri = 'Datang' or status_antri = 'Pembayaran' or status_antri = 'Selesai') and date_format(jadwal, '%Y-%m-%d') = '$tanggal' AND registrasi_rawat.shift = 'Pagi' and registrasi_rawat.carabayar LIKE '%spesialis%' GROUP BY registrasi_rawat.idrawat");
                        foreach ($getBiayaPoli as $dataBiayaPoli) {
                            $biayaRawat = $koneksi->query("SELECT * FROM biaya_rawat WHERE idregis= '$dataBiayaPoli[idrawat]' ORDER BY id DESC LIMIT 1")->fetch_assoc();

                            $totalBiayaPoli += $biayaRawat['poli'];
                            $totalLab += ($biayaRawat['biaya_lab'] == '' ? 0 : $biayaRawat['biaya_lab']);
                        }
                        // 
                        ?>
                        Rp <?= number_format($totalBiayaPoli, 0, 0, '.') ?>
                        <?php
                        $totalPoli += ($totalBiayaPoli);
                        $totalPoli += ($totalLab);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Biaya Lab</td>
                    <td>Rp <?= number_format($totalLab, 0, 0, '.') ?></td>
                </tr>
                <?php
                $getLayanan = $koneksi->query("SELECT layanan, COUNT(*) as JumlahLayanan FROM layanan INNER JOIN registrasi_rawat ON registrasi_rawat.no_rm = layanan.idrm AND DATE_FORMAT(registrasi_rawat.jadwal, '%Y-%m-%d') = DATE_FORMAT(layanan.tgl_layanan, '%Y-%m-%d') WHERE perawatan = 'Rawat Jalan' AND DATE_FORMAT(tgl_layanan, '%Y-%m-%d') = '$tanggal' AND registrasi_rawat.shift IN (" . $shift . ") GROUP BY layanan");
                foreach ($getLayanan as $dataLayanan) {
                ?>
                    <tr>
                        <td>Layanan/Tindakan <?= $dataLayanan['layanan'] ?></td>
                        <td>
                            <?php
                            $getHargaLayanan = $koneksimaster->query("SELECT * FROM master_layanan WHERE nama_layanan = '$dataLayanan[layanan]'")->fetch_assoc();
                            ?>
                            Rp <?= number_format(($getHargaLayanan['harga'] ?? 0) * $dataLayanan['JumlahLayanan'], 0, 0, '.') ?>
                        </td>
                        <?php $totalPoli += (($getHargaLayanan['harga'] ?? 0) * $dataLayanan['JumlahLayanan']); ?>
                    </tr>
                <?php } ?>
                <tr>
                    <td><b>Total Poli</b></td>
                    <td><b>Rp <?= number_format($totalPoli, 0, 0, '.') ?></b></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <?php
                        $caraBayar = ['umum', 'malam', 'bpjs', 'spesialis anak', 'spesialis penyakit dalam', 'gigi umum', 'gigi bpjs', 'kosmetik'];
                        foreach ($caraBayar as $cb) {
                        ?>
                            <?php
                            $getJumlahPasienCaraBayar = $koneksi->query("SELECT COUNT(*) as jumlah, registrasi_rawat.kasir, registrasi_rawat.shift FROM registrasi_rawat INNER JOIN biaya_rawat ON biaya_rawat.idregis = registrasi_rawat.idrawat WHERE perawatan = 'Rawat Jalan' and (status_antri = 'Datang' or status_antri = 'Pembayaran' or status_antri = 'Selesai') and date_format(jadwal, '%Y-%m-%d') = '$tanggal' AND registrasi_rawat.shift IN (" . $shift . ") and (registrasi_rawat.carabayar = '$cb')")->fetch_assoc();
                            ?>
                            <?php if ($getJumlahPasienCaraBayar['jumlah'] > 0) { ?>
                                Jumlah Pasien <b><?= $cb ?>: <?= $getJumlahPasienCaraBayar['jumlah'] ?? 0 ?></b> |
                            <?php } ?>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <b>
                            <hr class="m-1">
                        </b>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><b>Rawat Inap Non BPJS</b></td>
                </tr>
                <?php
                $getRawatinapNonBPJS = $koneksi->query("SELECT biaya, SUM(besaran) as harga FROM `pulang` INNER JOIN registrasi_rawat ON DATE_FORMAT(registrasi_rawat.jadwal, '%Y-%m-%d') = tgl_masuk AND registrasi_rawat.no_rm = pulang.norm INNER JOIN rawatinapdetail ON rawatinapdetail.id = registrasi_rawat.idrawat WHERE carabayar != 'bpjs' AND pulang.tgl = '$tanggal' AND pulang.shift IN (" . $shift . ") GROUP BY biaya ORDER BY biaya ASC");
                ?>
                <?php foreach ($getRawatinapNonBPJS as $rawatInapNonBPJS) { ?>
                    <tr>
                        <td><?= $rawatInapNonBPJS['biaya'] ?></td>
                        <td>Rp <?= number_format($rawatInapNonBPJS['harga'], 0, 0, '.') ?></td>
                        <?php $totalRawatInapNonBPJS += $rawatInapNonBPJS['harga']; ?>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan=""><b>Total</b></td>
                    <td colspan=""><b>Rp <?= number_format($totalRawatInapNonBPJS, 0, 0, '.') ?></b></td>
                </tr>
                <tr>
                    <td colspan=""><b>Total Uang Cash</b></td>
                    <td colspan=""><b>Rp <?= number_format($totalRawatInapNonBPJS + $totalPoli, 0, 0, '.') ?></b></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <b>
                            <hr class="m-1">
                        </b>
                    </td>
                </tr>
                <?php
                $getRawatinapBPJS = $koneksi->query("SELECT biaya, SUM(besaran) as harga FROM `pulang` INNER JOIN registrasi_rawat ON DATE_FORMAT(registrasi_rawat.jadwal, '%Y-%m-%d') = tgl_masuk AND registrasi_rawat.no_rm = pulang.norm INNER JOIN rawatinapdetail ON rawatinapdetail.id = registrasi_rawat.idrawat WHERE carabayar = 'bpjs' AND pulang.tgl = '$tanggal' AND pulang.shift IN (" . $shift . ") GROUP BY biaya ORDER BY biaya ASC");
                ?>
                <tr>
                    <td colspan="2" data-bs-toggle="modal" data-bs-target="#staticBackdropBPJS"><b>Rawat Inap BPJS</b></td>
                </tr>
                <?php foreach ($getRawatinapBPJS as $rawatInapBPJS) { ?>
                    <tr>
                        <td><?= $rawatInapBPJS['biaya'] ?></td>
                        <td>Rp <?= number_format($rawatInapBPJS['harga'], 0, 0, '.') ?></td>
                        <?php $totalRawatInapBPJS += $rawatInapBPJS['harga']; ?>
                    </tr>
                <?php } ?>
                <tr>
                    <td><b>Total</b></td>
                    <td><b>Rp <?= number_format($totalRawatInapBPJS, 0, 0, '.') ?></b></td>
                </tr>
            </tbody>
        </table>

        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-sm table-hover table-striped" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Jadwal</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalNamaNonBPJS = 0;
                                $getRawatinapNonBPJSData = $koneksi->query("SELECT registrasi_rawat.nama_pasien, registrasi_rawat.jadwal, SUM(besaran) as harga FROM `pulang` INNER JOIN registrasi_rawat ON DATE_FORMAT(registrasi_rawat.jadwal, '%Y-%m-%d') = tgl_masuk AND registrasi_rawat.no_rm = pulang.norm INNER JOIN rawatinapdetail ON rawatinapdetail.id = registrasi_rawat.idrawat WHERE carabayar != 'bpjs' AND pulang.tgl = '$tanggal' GROUP BY nama_pasien ORDER BY nama_pasien ASC");
                                foreach ($getRawatinapNonBPJSData as $dataNonBPJS) {
                                ?>
                                    <tr>
                                        <td><?= $dataNonBPJS['nama_pasien'] ?></td>
                                        <td><?= $dataNonBPJS['jadwal'] ?></td>
                                        <td><?= number_format($dataNonBPJS['harga'], 0, 0, '.') ?></td>
                                    </tr>
                                    <?php $totalNamaNonBPJS += $dataNonBPJS['harga']; ?>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2"><b>Total</b></td>
                                    <td><b>Rp <?= number_format($totalNamaNonBPJS, 0, 0, '.') ?></b></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <!-- <button type="button" class="btn btn-primary">Understood</button> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="staticBackdropBPJS" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-sm table-hover table-striped" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Jadwal</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalNamaBPJS = 0;
                                $getRawatinapBPJSData = $koneksi->query("SELECT registrasi_rawat.nama_pasien, registrasi_rawat.jadwal, SUM(besaran) as harga FROM `pulang` INNER JOIN registrasi_rawat ON DATE_FORMAT(registrasi_rawat.jadwal, '%Y-%m-%d') = tgl_masuk AND registrasi_rawat.no_rm = pulang.norm INNER JOIN rawatinapdetail ON rawatinapdetail.id = registrasi_rawat.idrawat WHERE carabayar = 'bpjs' AND pulang.tgl = '$tanggal' GROUP BY nama_pasien ORDER BY nama_pasien ASC");
                                foreach ($getRawatinapBPJSData as $dataBPJS) {
                                ?>
                                    <tr>
                                        <td><?= $dataBPJS['nama_pasien'] ?></td>
                                        <td><?= $dataBPJS['jadwal'] ?></td>
                                        <td><?= number_format($dataBPJS['harga'], 0, 0, '.') ?></td>
                                    </tr>
                                    <?php $totalNamaBPJS += $dataBPJS['harga']; ?>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2"><b>Total</b></td>
                                    <td><b>Rp <?= number_format($totalNamaBPJS, 0, 0, '.') ?></b></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <!-- <button type="button" class="btn btn-primary">Understood</button> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>