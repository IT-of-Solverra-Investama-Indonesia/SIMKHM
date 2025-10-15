<div>
    <div class="card shadow p-2 m-0 mb-2">
        <h5 class="m-0">Rekap Pendapatan Kasir</h5>
        <form method="get">
            <div class="row g-1">
                <input type="text" name="halaman" value="dashboardstaff" hidden id="">
                <input type="text" name="tipe" value="rekapPendapatanKasir" hidden id="">

                <?php
                // Tentukan tanggal akhir (default: hari ini)
                $tanggalAkhir = (isset($_GET['tanggal_akhir']) && $_GET['tanggal_akhir'] != '') ? htmlspecialchars($_GET['tanggal_akhir']) : date('Y-m-d');

                // Tentukan tanggal awal (default: 6 bulan ke belakang dari tanggal akhir)
                $tanggalAwalDefault = date('Y-m-d', strtotime("$tanggalAkhir - 5 months"));
                $tanggalAwal = (isset($_GET['tanggal_awal']) && $_GET['tanggal_awal'] != '') ? htmlspecialchars($_GET['tanggal_awal']) : $tanggalAwalDefault;

                // Pastikan format tanggal untuk query adalah Y-m-d
                $tanggalAwalQuery = date('Y-m-d', strtotime($tanggalAwal));
                $tanggalAkhirQuery = date('Y-m-d', strtotime($tanggalAkhir));

                $shift = "'Pagi','Sore','Malam'";
                $dataKasir = [];

                // =========================================================================
                // 1. OPTIMASI: AGGREGASI DATA DILUAR LOOP UTAMA
                // =========================================================================

                // A. Ambil Data Total Pendapatan Poli (UANG)
                $queryTotalPoliUang = "
                    SELECT 
                        DATE_FORMAT(rr.jadwal, '%Y-%m') as bulan, 
                        rr.kasir, 
                        SUM(br.poli) as total_poli, 
                        SUM(IF(br.biaya_lab IS NULL OR br.biaya_lab = '', 0, br.biaya_lab)) as total_lab
                    FROM registrasi_rawat rr
                    INNER JOIN biaya_rawat br ON br.idregis = rr.idrawat
                    WHERE rr.perawatan = 'Rawat Jalan' 
                    AND rr.status_antri IN ('Datang', 'Pembayaran', 'Selesai')
                    AND rr.jadwal BETWEEN '$tanggalAwalQuery 00:00:00' AND '$tanggalAkhirQuery 23:59:59' 
                    AND rr.shift IN ($shift)
                    AND rr.carabayar IN ('umum', 'malam', 'bpjs', 'spesialis anak', 'spesialis penyakit dalam', 'gigi umum', 'gigi bpjs')
                    GROUP BY bulan, rr.kasir
                ";
                $getTotalPoliUang = $koneksi->query($queryTotalPoliUang);
                while ($data = $getTotalPoliUang->fetch_assoc()) {
                    $key = $data['bulan'] . '|' . $data['kasir'];
                    $dataKasir[$key]['total_uang_poli'] = (float)$data['total_poli'] + (float)$data['total_lab'];
                    $dataKasir[$key]['bulan'] = $data['bulan'];
                    $dataKasir[$key]['kasir'] = $data['kasir'];
                }

                // A.1. Ambil Data Total PASIEN Poli (Untuk kolom "Total Pasien Poli")
                $queryTotalPasienPoli = "
                    SELECT 
                        DATE_FORMAT(rr.jadwal, '%Y-%m') as bulan, 
                        rr.kasir, 
                        COUNT(rr.idrawat) as total_pasien_poli
                    FROM registrasi_rawat rr
                    WHERE rr.perawatan = 'Rawat Jalan' 
                    AND rr.status_antri IN ('Datang', 'Pembayaran', 'Selesai')
                    AND rr.jadwal BETWEEN '$tanggalAwalQuery 00:00:00' AND '$tanggalAkhirQuery 23:59:59' 
                    AND rr.shift IN ($shift)
                    AND rr.carabayar IN ('umum', 'malam', 'bpjs', 'spesialis anak', 'spesialis penyakit dalam', 'gigi umum', 'gigi bpjs')
                    GROUP BY bulan, rr.kasir
                ";
                $getTotalPasienPoli = $koneksi->query($queryTotalPasienPoli);
                while ($data = $getTotalPasienPoli->fetch_assoc()) {
                    $key = $data['bulan'] . '|' . $data['kasir'];

                    // Inisialisasi total_uang_poli jika belum ada
                    if (!isset($dataKasir[$key]['total_uang_poli'])) {
                        $dataKasir[$key]['total_uang_poli'] = 0;
                        $dataKasir[$key]['bulan'] = $data['bulan'];
                        $dataKasir[$key]['kasir'] = $data['kasir'];
                    }
                    $dataKasir[$key]['total_pasien_poli'] = (int)$data['total_pasien_poli'];
                }

                // B. Ambil Data Total Layanan (Uang)
                $queryLayanan = "
                    SELECT 
                        DATE_FORMAT(rr.jadwal, '%Y-%m') as bulan, 
                        rr.kasir, 
                        l.layanan, 
                        COUNT(*) as JumlahLayanan
                    FROM registrasi_rawat rr
                    INNER JOIN layanan l ON rr.no_rm = l.idrm 
                        AND DATE_FORMAT(rr.jadwal, '%Y-%m-%d') = DATE_FORMAT(l.tgl_layanan, '%Y-%m-%d')
                    WHERE rr.perawatan = 'Rawat Jalan' 
                    AND rr.jadwal BETWEEN '$tanggalAwalQuery 00:00:00' AND '$tanggalAkhirQuery 23:59:59'
                    AND rr.shift IN ($shift)
                    GROUP BY bulan, rr.kasir, l.layanan
                ";
                $getLayanan = $koneksi->query($queryLayanan);
                while ($data = $getLayanan->fetch_assoc()) {
                    $key = $data['bulan'] . '|' . $data['kasir'];

                    // Asumsi $koneksimaster masih bisa diakses
                    $getHargaLayanan = $koneksimaster->query("SELECT harga FROM master_layanan WHERE nama_layanan = '{$data['layanan']}'")->fetch_assoc();
                    $hargaLayanan = (float)($getHargaLayanan['harga'] ?? 0);
                    $pendapatanLayanan = $hargaLayanan * (int)$data['JumlahLayanan'];

                    // Tambahkan pendapatan Layanan ke Total Uang Poli
                    if (!isset($dataKasir[$key]['total_uang_poli'])) {
                        $dataKasir[$key]['total_uang_poli'] = 0;
                        $dataKasir[$key]['bulan'] = $data['bulan'];
                        $dataKasir[$key]['kasir'] = $data['kasir'];
                    }
                    $dataKasir[$key]['total_uang_poli'] += $pendapatanLayanan;
                }

                // C. Ambil Data Jumlah Shift
                $queryJumlahShiftAll = "
                    SELECT 
                        DATE_FORMAT(jadwal, '%Y-%m') as bulan, 
                        kasir, 
                        COUNT(*) as jumlah_shift
                    FROM (
                        SELECT 
                            jadwal, kasir
                        FROM registrasi_rawat 
                        WHERE jadwal BETWEEN '$tanggalAwalQuery 00:00:00' AND '$tanggalAkhirQuery 23:59:59' 
                        AND shift IN ($shift) 
                        GROUP BY DATE_FORMAT(jadwal, '%Y-%m-%d'), shift, kasir 
                    ) as sub
                    GROUP BY bulan, kasir
                ";
                $getJumlahShift = $koneksi->query($queryJumlahShiftAll);
                while ($data = $getJumlahShift->fetch_assoc()) {
                    $key = $data['bulan'] . '|' . $data['kasir'];
                    // Inisialisasi jika belum ada
                    if (!isset($dataKasir[$key]['total_uang_poli'])) {
                        $dataKasir[$key]['total_uang_poli'] = 0;
                        $dataKasir[$key]['bulan'] = $data['bulan'];
                        $dataKasir[$key]['kasir'] = $data['kasir'];
                    }
                    $dataKasir[$key]['jumlah_shift'] = (int)$data['jumlah_shift'];
                }

                // Urutkan data berdasarkan bulan secara DESC (paling baru di atas)
                krsort($dataKasir);

                $totalUangPoliFinal = 0;
                $totalPasienPoliFinal = 0;
                $totalShiftFinal = 0;
                ?>

                <div class="col-4">
                    <input type="date" value="<?= $tanggalAwal ?>" name="tanggal_awal" class="form-control form-control-sm" placeholder="Tgl. Awal" required>
                </div>

                <div class="col-4">
                    <input type="date" value="<?= $tanggalAkhir ?>" name="tanggal_akhir" class="form-control form-control-sm" placeholder="Tgl. Akhir" required>
                </div>

                <div class="col-4">
                    <button class="btn btn-sm btn-primary w-100"><i class="bi bi-search"></i> Cari</button>
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
                        <th>Poli (Pendapatan)</th>
                        <th>Jumlah Shift</th>
                        <th>Total Pasien Poli</th>
                        <th>Poli/Shift</th>
                        <th>Pasien/Shift</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // =========================================================================
                    // 2. LOOPING DATA DARI ARRAY YANG SUDAH DIAGGREGASI
                    // =========================================================================
                    foreach ($dataKasir as $data) {
                        $totalUangPoli = $data['total_uang_poli'] ?? 0;
                        $totalPasienPoli = $data['total_pasien_poli'] ?? 0; // Data Total Pasien
                        $jumlahShift = $data['jumlah_shift'] ?? 0;

                        // Perhitungan Pasien per Shift (rata-rata) dihapus dari tampilan baris.
                    ?>
                        <tr>
                            <td><?= $data['bulan'] ?></td>
                            <td><?= $data['kasir'] ?></td>
                            <td>
                                Rp. <?= number_format($totalUangPoli, 0, ',', '.') ?>
                            </td>
                            <td>
                                <?= number_format($jumlahShift, 0, ',', '.') ?>x Jaga
                            </td>
                            <td>
                                <?= number_format($totalPasienPoli, 0, ',', '.') ?> Pasien
                            </td>
                            <td>
                                Rp. <?= number_format($totalUangPoli/$jumlahShift, 0, ',', '.') ?>
                            </td>
                            <td>
                                <?= number_format($totalPasienPoli/$jumlahShift, 2) ?>
                            </td>
                        </tr>
                        <?php
                        $totalUangPoliFinal += $totalUangPoli;
                        $totalPasienPoliFinal += $totalPasienPoli; // Akumulasi total pasien
                        $totalShiftFinal += $jumlahShift;
                        ?>
                    <?php }

                    // Rata-rata pasien per shift untuk footer (disimpan, tapi tidak ditampilkan dalam kode ini)
                    // $rataRataPasienPerShiftFinal = ($totalShiftFinal > 0) ? ($totalPasienPoliFinal / $totalShiftFinal) : 0;
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2"><b>Total</b></td>
                        <td>
                            <b>
                                Rp. <?= number_format($totalUangPoliFinal, 0, ',', '.') ?>
                            </b>
                        </td>
                        <td>
                            <b>
                                <?= number_format($totalShiftFinal, 0, ',', '.') ?>x Jaga
                            </b>
                        </td>
                        <td>
                            <b>
                                <?= number_format($totalPasienPoliFinal, 0, ',', '.') ?> Pasien
                            </b>
                        </td>
                        <td>
                            <b>
                                Rp. <?= number_format($totalUangPoliFinal, 0, ',', '.') ?>
                            </b>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>