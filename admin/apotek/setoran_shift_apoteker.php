<div class="">
    <?php
    $date = $_GET['date'] ?? date('Y-m-d');
    $shift = $_GET['shift'] ?? $_SESSION['shift']
    ?>
    <h5 class="card-title">Rekap Storan Shift</h5>
    <a target="_blank" href="../apotek/setoran_shift_apotekerPrint.php?tanggal=<?= $date ?>&shift=<?= $shift ?>" class="btn btn-sm btn-warning mb-1"><i class="bi bi-printer"></i> Print</a>
    <div class="card shadow p-2 mb-1">
        <form method="get">
            <div class="row">
                <div class="col-5">
                    <input type="date" name="date" id="" value="<?= $date ?>" class="form-control form-control-sm" required>
                    <input type="text" name="halaman" value="setoran_shift_apoteker" hidden id="">
                </div>
                <div class="col-5">
                    <select name="shift" id="" class="form-control form-control-sm" required>
                        <option value="<?= $shift ?>"><?= $shift ?></option>
                        <option value="All">All</option>
                        <option value="Pagi">Pagi</option>
                        <option value="Sore">Sore</option>
                        <option value="Malam">Malam</option>
                    </select>
                </div>
                <div class="col-2">
                    <button class="btn btn-sm btn-primary" name="src"><i class="bi bi-search"></i></button>
                </div>
            </div>
        </form>
    </div>
    <div class="card shadow p-2">
        <div class="table-responsive">
            <table class="table table-sm table-striped table-hover">
                <thead>
                    <tr>
                        <th>Keterangan</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $srcCondition = '';
                    $urlPage = "index.php?halaman=setoran_shift_apoteker";

                    if (isset($_GET['src'])) {
                        $tanggal = htmlspecialchars($_GET['date']);
                        $shift = htmlspecialchars($_GET['shift']);
                        $urlPage = "index.php?halaman=setoran_shift_apoteker&date=$tanggal&shift=$shift";

                        if ($tanggal != '') {
                            $srcCondition .= ' AND date_format(tgl_jual, "%Y-%m-%d") = "' . $tanggal . '"';
                        }

                        if ($shift != '' and $shift != 'All') {
                            $srcCondition .= ' AND shift = "' . $shift . '"';
                        }
                    }

                    $query = "SELECT sumber, SUM((harga_umum - diskon_obat) * jumlah) AS total_penjualan FROM (
                            SELECT 'UMUM' AS sumber, id_penjualan, nota, tgl_jual, kode_obat, nama_obat, harga_umum, diskon_obat, jumlah, harga_beli, akun, petugas, shift, created_at FROM penjualan_umum
                            UNION ALL
                            SELECT 'RESEP' AS sumber, id_penjualan, nota, tgl_jual, kode_obat, nama_obat, harga_umum, diskon_obat, jumlah, harga_beli, akun, petugas, shift, created_at FROM penjualan_resep
                            UNION ALL
                            SELECT 'REKANAN' AS sumber, id_penjualan, nota, tgl_jual, kode_obat, nama_obat, harga_umum, diskon_obat, jumlah, harga_beli, akun, petugas, shift, created_at FROM penjualan_rekanan
                            UNION ALL
                            SELECT 'INTERNAL' AS sumber, id_penjualan, nota, tgl_jual, kode_obat, nama_obat, harga_umum, diskon_obat, jumlah, harga_beli, akun, petugas, shift, created_at FROM penjualan_internal

                        ) AS penjualan_obat  WHERE 1 = 1 $srcCondition GROUP BY sumber ORDER BY tgl_jual DESC";

                    $getData = $koneksi->query($query);
                    foreach ($getData as $data) {
                    ?>
                        <tr>
                            <td><?= $data['sumber'] ?></td>
                            <td>Rp. <?= number_format($data['total_penjualan'], 0, 0), '' ?></td>
                        </tr>
                        <?php

                        static $grandTotal = 0;
                        $grandTotal += $data['total_penjualan'];
                        ?>
                    <?php } ?>
                    <tr>
                        <th>Total</th>
                        <th>Rp. <?= number_format($grandTotal ?? 0, 0, 0, '.') ?></th>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>