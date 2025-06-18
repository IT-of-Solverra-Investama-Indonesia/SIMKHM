<div class="">
    <h5 class="card-title">Riwayat Penjualan All</h5>
    <div class="card shadow p-1 mb-1">
        <form method="get">
            <div class="row g-1">
                <div class="col-4">
                    <input type="text" name="halaman" value="penjualan_obat_all_riwayat" hidden>
                    <input name="date_start" id="" class="form-control form-control-sm" placeholder="Date Start" onfocus="(this.type='date')" onblur="(this.type='text')">
                </div>
                <div class="col-4">
                    <input name="date_end" id="" class="form-control form-control-sm" placeholder="Date End" >
                </div>
                <div class="col-4">
                    <select name="sumber" id="" class="form-control form-control-sm">
                        <option value="All">All</option>
                        <option value="UMUM">UMUM</option>
                        <option value="RESEP">RESEP</option>
                        <option value="REKANAN">REKANAN</option>
                        <option value="INTERNAL">INTERNAL</option>
                    </select>
                </div>
                <div class="col-10">
                    <input type="text" name="key" id="" class="form-control form-control-sm" placeholder="Cari">
                </div>
                <div class="col-2">
                    <button class="btn btn-sm btn-primary" name="src"><i class="bi bi-search"></i></button>
                </div>
            </div>
        </form>
    </div>
    <div class="card shadow p-2 mb-1">
        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nota</th>
                        <th>KodeObat</th>
                        <th>NamaObat</th>
                        <th>HargaUmum</th>
                        <th>Diskon</th>
                        <th>Jumlah</th>
                        <th>Sub</th>
                        <th>Akun</th>
                        <th>Petugas</th>
                        <th>Shift</th>
                        <th>Sumber</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $srcCondition = '';
                    $urlPage = "index.php?halaman=penjualan_obat_all_riwayat";
                    if (isset($_GET['src'])) {
                        $date_start = htmlspecialchars($_GET['date_start']);
                        $date_end = htmlspecialchars($_GET['date_end']);
                        $sumber = htmlspecialchars($_GET['sumber']);
                        $key = htmlspecialchars($_GET['key']);
                        $urlPage = "index.php?halaman=penjualan_obat_all_riwayat&date_start=$date_start&date_end=$date_end&sumber=All&key=$key&src=";

                        if ($date_start != '') {
                            $srcCondition .= ' AND date_format(tgl_jual, "%Y-%m-%d") >= "' . $date_start . '"';
                        }
                        if ($date_end != '') {
                            $srcCondition .= ' AND date_format(tgl_jual, "%Y-%m-%d") <= "' . $date_end . '"';
                        }
                        if ($sumber != '' and $sumber != 'All') {
                            $srcCondition .= ' AND sumber = "' . $sumber . '"';
                        }
                        if ($key != '') {
                            $srcCondition .= ' AND (kode_obat LIKE "%' . $key . '%" OR nama_obat LIKE "%' . $key . '%" OR nota LIKE "%' . $key . '%")';
                        }
                    }

                    $query = "SELECT * FROM (
                            SELECT 'UMUM' AS sumber, id_penjualan, nota, tgl_jual, kode_obat, nama_obat, harga_umum, diskon_obat, jumlah, harga_beli, akun, petugas, shift, created_at FROM penjualan_umum
                            UNION ALL
                            SELECT 'RESEP' AS sumber, id_penjualan, nota, tgl_jual, kode_obat, nama_obat, harga_umum, diskon_obat, jumlah, harga_beli, akun, petugas, shift, created_at FROM penjualan_resep
                            UNION ALL
                            SELECT 'REKANAN' AS sumber, id_penjualan, nota, tgl_jual, kode_obat, nama_obat, harga_umum, diskon_obat, jumlah, harga_beli, akun, petugas, shift, created_at FROM penjualan_rekanan
                            UNION ALL
                            SELECT 'INTERNAL' AS sumber, id_penjualan, nota, tgl_jual, kode_obat, nama_obat, harga_umum, diskon_obat, jumlah, harga_beli, akun, petugas, shift, created_at FROM penjualan_internal

                        ) AS penjualan_obat WHERE 1 = 1 $srcCondition ORDER BY tgl_jual DESC";

                    $getData = $koneksi->query($query);
                    foreach ($getData as $data) {
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $data['tgl_jual'] ?></td>
                            <td><?= $data['nota'] ?></td>
                            <td><?= $data['kode_obat'] ?></td>
                            <td><?= $data['nama_obat'] ?></td>
                            <td><?= number_format($data['harga_umum'], 0, 0, '') ?></td>
                            <td><?= number_format($data['diskon_obat'], 0, 0, '') ?></td>
                            <td><?= number_format($data['jumlah'], 0, 0, '') ?></td>
                            <td><?= number_format(($data['harga_umum'] - $data['diskon_obat']) * $data['jumlah'], 0, 0, '') ?></td>
                            <td><?= $data['akun'] ?></td>
                            <td><?= $data['petugas'] ?></td>
                            <td><?= $data['shift'] ?></td>
                            <td><?= $data['sumber'] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card shadow p-2">
        <?php
        // Display pagination
        echo '<nav>';
        echo '<ul class="pagination justify-content-center">';

        // Back button
        if ($page > 1) {
            echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . ($page - 1) . '">Back</a></li>';
        }

        // Determine the start and end page
        $start_page = max(1, $page - 2);
        $end_page = min($total_pages, $page + 2);

        if ($start_page > 1) {
            echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=1">1</a></li>';
            if ($start_page > 2) {
                echo '<li class="page-item"><span class="page-link">...</span></li>';
            }
        }

        for ($i = $start_page; $i <= $end_page; $i++) {
            if ($i == $page) {
                echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
            } else {
                echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . $i . '">' . $i . '</a></li>';
            }
        }

        if ($end_page < $total_pages) {
            if ($end_page < $total_pages - 1) {
                echo '<li class="page-item"><span class="page-link">...</span></li>';
            }
            echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . $total_pages . '">' . $total_pages . '</a></li>';
        }

        // Next button
        if ($page < $total_pages) {
            echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . ($page + 1) . '">Next</a></li>';
        }

        echo '</ul>';
        echo '</nav>';
        ?>
    </div>
</div>