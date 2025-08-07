<div class="">
    <h5 class="card-title">Riwayat Entri Faktur</h5>
    <div class="card shadow p-2 mb-2">
        <form method="GET">
            <div class="row">
                <div class="col-6">
                    <input type="text" name="halaman" id="" value="apotek_riwayat_faktur" hidden>
                    <input name="date_start" class="form-control form-control-sm mb-2" onblur="(this.type='text')" onfocus="(this.type='date')" value="<?= isset($_GET['date_start']) ? $_GET['date_start'] : "" ?>" placeholder="Tanggal Awal" id="">
                </div>
                <div class="col-6">
                    <input name="date_end" class="form-control form-control-sm mb-2" onblur="(this.type='text')" onfocus="(this.type='date')" value="<?= isset($_GET['date_start']) ? $_GET['date_start'] : "" ?>" placeholder="Tanggal Akhir" id="">
                </div>
                <div class="col-10">
                    <input type="text" name="key" id="" class="form-control form-control-sm " value="<?= isset($_GET['key']) ? $_GET['key'] : "" ?>" placeholder="Cari">
                </div>
                <div class="col-2">
                    <button class="form-control form-control-sm btn btn-sm btn-primary" type="submit" name="src"><i class="bi bi-search"></i></button>
                </div>
            </div>
        </form>
    </div>
    <div class="card shadow p-2 mb-2">
        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tgl Beli</th>
                        <th>Tgl Datang</th>
                        <th>Nama</th>
                        <th>Distributor</th>
                        <th>Petugas</th>
                        <th>Kode</th>
                        <th>Jumlah</th>
                        <th>Batch</th>
                        <th>Faktur</th>
                        <th>Expired</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $whereCondition = "";
                    $urlPage = 'index.php?halaman=apotek_riwayat_faktur';
                    if (isset($_GET['key']) and $_GET['key'] != "") {
                        $urlPage = 'index.php?halaman=apotek_riwayat_faktur&src&key=' . htmlspecialchars($_GET['key']) . '&date_start=' . htmlspecialchars($_GET['date_start']) . '&date_end=' . htmlspecialchars($_GET['date_end']);

                        $key = htmlspecialchars($_GET['key']);
                        $whereCondition .= " AND (apotek.nama_obat LIKE '%$key%' OR apotek.id_obat LIKE '%$key%')";
                    }

                    if (isset($_GET['date_start']) and $_GET['date_start'] != "") {
                        $whereCondition .= " AND apotek.tgl_datang LIKE '%" . htmlspecialchars($_GET['date_start']) . "%'";
                    }
                    if (isset($_GET['date_end']) and $_GET['date_end'] != "") {
                        $whereCondition .= " AND apotek.tgl_datang LIKE '%" . htmlspecialchars($_GET['date_end']) . "%'";
                    }

                    $query = "SELECT * FROM apotek WHERE tgl_datang IS NOT NULL $whereCondition ORDER BY tgl_datang DESC";

                    // Parameters for pagination
                    $limit = 100; // Number of entries to show in a page
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $start = ($page - 1) * $limit;

                    // Get the total number of records

                    $total_records = $koneksi->query($query)->num_rows;

                    // Calculate total pages
                    $total_pages = ceil($total_records / $limit);

                    $cekPage = '';
                    if (isset($_GET['page'])) {
                        $cekPage = $_GET['page'];
                    } else {
                        $cekPage = '1';
                    }
                    // End Pagination

                    $getData = $koneksi->query($query . " LIMIT $start, $limit");
                    $no = $start + 1;
                    foreach ($getData as $data) {
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $data['tgl_beli'] ?></td>
                            <td><?= $data['tgl_datang'] ?></td>
                            <td><?= $data['nama_obat'] ?></td>
                            <td><?= $data['produsen'] ?></td>
                            <td><?= $data['petugas_terima'] ?></td>
                            <td><?= $data['id_obat'] ?></td>
                            <td><?= $data['jml_obat'] ?> <?= $data['bentuk'] ?></td>
                            <td><?= $data['batch'] ?></td>
                            <td>
                                <?php if ($data['foto_faktur'] != "IT") { ?>
                                    <a href="../apotek/foto_faktur/<?= $data['foto_faktur'] ?>" class="badge badge-success bg-success text-light" style="font-size: 12px;">
                                        Lihat
                                    </a>
                                <?php } ?>
                            </td>
                            <td><?= $data['tgl_expired'] ?></td>
                            <td>
                                <a href="<?= $urlPage ?>&hapusObatTerima=<?= $data['idapotek'] ?>" onclick="return confirm('Are you sure ???')" class="badge badge-danger bg-danger text-light" style="font-size: 12px;">
                                    <i class="bi bi-trash"></i> Hapus
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
    if (isset($_GET['hapusObatTerima'])) {
        $koneksi->query("DELETE FROM apotek WHERE idapotek = '" . htmlspecialchars($_GET['hapusObatTerima']) . "'");
        echo "
                <script>
                    alert('Successfully');
                    document.location.href='index.php?halaman=apotek_riwayat_faktur';
                </script>
            ";
    }
    ?>
    <div class="card shadow-sm p-2">
        <?php
        // Display pagination
        echo '<nav>';
        echo '<ul class="pagination justify-content-center">';

        // Back button
        if ($page > 1) {
            echo '<li class="page-item mb-0"><a class="page-link" href="' . $urlPage . '&page=' . ($page - 1) . '">Back</a></li>';
        }

        // Determine the start and end page
        $start_page = max(1, $page - 2);
        $end_page = min($total_pages, $page + 2);

        if ($start_page > 1) {
            echo '<li class="page-item mb-0"><a class="page-link" href="' . $urlPage . '&page=1">1</a></li>';
            if ($start_page > 2) {
                echo '<li class="page-item mb-0"><span class="page-link">...</span></li>';
            }
        }

        for ($i = $start_page; $i <= $end_page; $i++) {
            if ($i == $page) {
                echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
            } else {
                echo '<li class="page-item mb-0"><a class="page-link" href="' . $urlPage . '&page=' . $i . '">' . $i . '</a></li>';
            }
        }

        if ($end_page < $total_pages) {
            if ($end_page < $total_pages - 1) {
                echo '<li class="page-item mb-0"><span class="page-link">...</span></li>';
            }
            echo '<li class="page-item mb-0"><a class="page-link" href="' . $urlPage . '&page=' . $total_pages . '">' . $total_pages . '</a></li>';
        }

        // Next button
        if ($page < $total_pages) {
            echo '<li class="page-item mb-0"><a class="page-link" href="' . $urlPage . '&page=' . ($page + 1) . '">Next</a></li>';
        }

        echo '</ul>';
        echo '</nav>';
        ?>
    </div>
</div>