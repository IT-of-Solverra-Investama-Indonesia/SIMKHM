<div class="">
    <h5 class="card-title">Riwayat Penjualan All</h5>
    <div class="card shadow p-1 mb-1">
        <form method="get">
            <div class="row g-1">
                <div class="col-4">
                    <input type="text" name="halaman" value="penjualan_obat_all_riwayat" hidden>
                    <input name="date_start" id="" onfocus="(this.type='date')" onblur="(this.type='text')" class="form-control form-control-sm" placeholder="Date Start">
                </div>
                <div class="col-4">
                    <input name="date_end" id="" onfocus="(this.type='date')" onblur="(this.type='text')" class="form-control form-control-sm" placeholder="Date End">
                </div>
                <div class="col-4">
                    <select name="sumber" id="" class="form-control form-control-sm">
                        <option value="All">Sumber All</option>
                        <option value="UMUM">UMUM</option>
                        <option value="RESEP">RESEP</option>
                        <option value="REKANAN">REKANAN</option>
                        <option value="INTERNAL">INTERNAL</option>
                    </select>
                </div>
                <div class="col-7">
                    <input type="text" name="key" id="" class="form-control form-control-sm" placeholder="Cari">
                </div>
                <div class="col-3">
                    <select name="shift" id="" class="form-control form-control-sm">
                        <option value="All">Shift All</option>
                        <option value="Pagi">Pagi</option>
                        <option value="Sore">Sore</option>
                        <option value="Malam">Malam</option>
                    </select>
                </div>
                <div class="col-2">
                    <button class="btn btn-sm btn-primary w-100" name="src"><i class="bi bi-search"></i></button>
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
                        $shift = htmlspecialchars($_GET['shift']);
                        $urlPage = "index.php?halaman=penjualan_obat_all_riwayat&date_start=$date_start&date_end=$date_end&sumber=$sumber&key=$key&src=";

                        if ($date_start != '') {
                            $srcCondition .= ' AND date_format(tgl_jual, "%Y-%m-%d") >= "' . $date_start . '"';
                        }
                        if ($date_end != '') {
                            $srcCondition .= ' AND date_format(tgl_jual, "%Y-%m-%d") <= "' . $date_end . '"';
                        }
                        if ($sumber != '' and $sumber != 'All') {
                            $srcCondition .= ' AND sumber = "' . $sumber . '"';
                        }
                        if ($shift != '' and $shift != 'All') {
                            $srcCondition .= ' AND shift = "' . $shift . '"';
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

                    //   Pagination
                    // Parameters for pagination
                    $limit = 100; // Number of entries to show in a page
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $start = ($page - 1) * $limit;

                    // Get the total number of records
                    $result = $koneksi->query($query);
                    $total_records = $result->num_rows;

                    // Calculate total pages
                    $total_pages = ceil($total_records / $limit);

                    $cekPage = '';
                    if (isset($_GET['page'])) {
                        $cekPage = $_GET['page'];
                    } else {
                        $cekPage = '1';
                    }
                    // End Pagination

                    $getData = $koneksi->query($query . " LIMIT $start, $limit;");
                    foreach ($getData as $data) {
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $data['tgl_jual'] ?></td>
                            <td>
                                <a style="font-size: 12px;" target="_blank" href="../apotek/penjualan_obat_<?= strtolower($data['sumber'])?>_nota.php?nota=<?= $data['nota'] ?>" class="badge bg-warning text-light">
                                    <?= $data['nota'] ?>
                                </a>
                            </td>
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