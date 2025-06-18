<div class="pagetitle">
    <h1>Riwayat Gaji Dokter</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?halaman=daftarapotek" style="color:blue;">Gaji Dokter</a></li>
        </ol>
    </nav>
</div>

<div class="card shadow-sm p-2 mb-1">
    <form method="post">
        <div class="row">
            <div class="col-6">
                <input placeholder="Dari Tanggal" name="tgl_start" class="form-control mb-2 form-control-sm" type="text" onfocus="(this.type='date')" onblur="(this.type='text')" id="date" />
            </div>
            <div class="col-6">
                <input placeholder="Hingga Tanggal" name="tgl_end" class="form-control mb-2 form-control-sm" type="text" onfocus="(this.type='date')" onblur="(this.type='text')" id="date" />
            </div>
            <div class="col-4">
                <select name="dokter" id="" class="form-control mb-2 form-control-sm">
                    <option value="All Dokter">All Dokter</option>
                    <?php
                    $getDokter = $koneksi->query("SELECT * FROM admin WHERE level = 'dokter'");
                    foreach ($getDokter as $dokter) {
                    ?>
                        <option value="<?= $dokter['namalengkap'] ?>"><?= $dokter['namalengkap'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-4">
                <select name="akun" id="" class="form-control mb-2 form-control-sm">
                    <option value="All Akun">All Akun</option>
                    <?php
                    $getAkun = $koneksimaster->query("SELECT * FROM akungajidokter ");
                    foreach ($getAkun as $akun) {
                    ?>
                        <option value="<?= $akun['namaakungajidokter'] ?>"><?= $akun['namaakungajidokter'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-4">
                <select name="unit" id="" class="form-control mb-2 form-control-sm">
                    <option value="All Unit">All Unit</option>
                    <?php
                    $getUnit = $koneksimaster->query("SELECT * FROM gajidokter GROUP BY unit");
                    foreach ($getUnit as $Unit) {
                    ?>
                        <option value="<?= $Unit['unit'] ?>"><?= $Unit['unit'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-12">
                <button name="src" class="btn btn-sm btn-primary float-end"><i class="bi bi-search"></i> Search</button>
            </div>
        </div>
    </form>
</div>
<?php
if (isset($_POST['src'])) {
    echo "
            <script>
                document.location.href='index.php?halaman=gajidokter_history&src&tgl_start=" . htmlspecialchars($_POST['tgl_start']) . "&tgl_end=" . htmlspecialchars($_POST['tgl_end']) . "&dokter=" . htmlspecialchars($_POST['dokter']) . "&akun=" . htmlspecialchars($_POST['akun']) . "&unit=" . htmlspecialchars($_POST['unit']) . "';
            </script>
        ";
}
?>

<div class="card shadow-sm p-2 mt-1">
    <div class="table-responsive">
        <table class="table-hover table table-striped" style="font-size: 13px;">
            <thead>
                <tr>
                    <th>Tgl</th>
                    <th>Dokter</th>
                    <th>Akun</th>
                    <th>Besaran</th>
                    <th>Ket</th>
                    <th>Petugas</th>
                    <th>Unit</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $tgl_start = "";
                $tgl_end = "";
                $dokter = "";
                $akun = "";
                if (isset($_GET['src'])) {
                    if ($_GET['tgl_start'] == '') {
                        $tgl_start = "";
                    } else {
                        $tgl_start = "AND tgl >= '" . htmlspecialchars(date('Y-m-d', strtotime($_GET['tgl_start']))) . "'";
                    }
                    if ($_GET['tgl_end'] == '') {
                        $tgl_end = "";
                    } else {
                        $tgl_end = "AND tgl <= '" . htmlspecialchars(date('Y-m-d', strtotime($_GET['tgl_end']))) . "'";
                    }
                    if ($_GET['dokter'] == 'All Dokter') {
                        $dokter = "";
                    } else {
                        $dokter = "AND dokter LIKE '%" . htmlspecialchars($_GET['dokter']) . "%'";
                    }
                    if ($_GET['akun'] == 'All akun') {
                        $akun = "";
                    } else {
                        $akun = "AND akun = '" . htmlspecialchars($_GET['akun']) . "'";
                    }
                    if ($_GET['akun'] == 'All Unit') {
                        $akun = "";
                    } else {
                        $akun = "AND unit = '" . htmlspecialchars($_GET['unit']) . "'";
                    }
                }
                $query = "SELECT * FROM gajidokter WHERE dokter != '' " . $tgl_start . " " . $tgl_end . " " . $akun . "  " . $dokter . " ORDER BY tgl DESC";

                //   Pagination
                // Parameters for pagination
                $limit = 100; // Number of entries to show in a page
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $start = ($page - 1) * $limit;

                // Get the total number of records
                $result = $koneksimaster->query($query);
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

                $getData = $koneksimaster->query($query);
                foreach ($getData as $data) {
                ?>
                    <tr>
                        <td><?= $data['tgl'] ?></td>
                        <td><?= $data['dokter'] ?></td>
                        <td><?= $data['akun'] ?></td>
                        <td><?= $data['besaran'] ?></td>
                        <td><?= $data['ket'] ?></td>
                        <td><?= $data['petugas'] ?></td>
                        <td><?= $data['unit'] ?></td>
                        <td>
                            <button onclick="upData('<?= $data['idgaji'] ?>', '<?= $data['dokter'] ?>', '<?= $data['akun'] ?>', '<?= $data['besaran'] ?>', '<?= $data['ket'] ?>', '<?= $data['petugas'] ?>', '<?= $data['unit'] ?>')" type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="btn btn-sm btn-success" style="font-size: 12px;"><i class="bi bi-pencil"></i></button>
                            <a href="index.php?halaman=gajidokter_history&delete=<?= $data['idgaji'] ?>" onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')" class="btn btn-sm btn-danger" style="font-size: 12px;"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    function upData(id, dokter, akun, besaran, ket, petugas, unit) {
        document.getElementById('id').value = id;
        document.getElementById('dokter').value = dokter;
        document.getElementById('akun').value = akun;
        document.getElementById('besaran').value = besaran;
        document.getElementById('ket').value = ket;
        document.getElementById('petugas').value = petugas;
        document.getElementById('unit').value = unit;
    }
</script>
<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Update</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="text" hidden name="id" id="id">
                    <p class="mb-0 float-start" for=""><b>Dokter :</b></p>
                    <select name="dokter" id="dokter" class="form-control mb-2">
                        <option value="" hidden>Pilih Dokter</option>
                        <?php
                        $getDokter = $koneksi->query("SELECT * FROM admin WHERE level= 'dokter'");
                        foreach ($getDokter as $dokter) {
                        ?>
                            <option value="<?= $dokter['namalengkap'] ?>"><?= $dokter['namalengkap'] ?></option>
                        <?php } ?>
                    </select>
                    <p class="mb-0 float-start" for=""><b>Akun Gaji Dokter :</b></p>
                    <select name="akun" id="akun" class="form-control mb-2">
                        <option value="" hidden>Pilih Akun</option>
                        <?php
                        $getAkun = $koneksimaster->query("SELECT * FROM akungajidokter");
                        foreach ($getAkun as $akun) {
                        ?>
                            <option value="<?= $akun['namaakungajidokter'] ?>"><?= $akun['namaakungajidokter'] ?></option>
                        <?php } ?>
                    </select>
                    <p class="mb-0 float-start" for=""><b>Besaran Gaji Dokter :</b></p>
                    <input type="number" name="besaran" class="form-control mb-2" id="besaran">
                    <p class="mb-0 float-start" for=""><b>Keterangan Gaji Dokter :</b></p>
                    <input type="text" name="ket" class="form-control mb-2" id="ket">
                    <p class="mb-0 float-start" for=""><b>Unit Gaji Dokter :</b></p>
                    <select name="unit" class="form-control mb-2" id="unit">
                        <option value="">Pilih Unit</option>
                        <option value="KHM 1">KHM 1</option>
                        <option value="KHM 2">KHM 2</option>
                        <option value="KHM 3">KHM 3</option>
                        <option value="KHM 4">KHM 4</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="saveUpdate" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
if (isset($_POST['saveUpdate'])) {
    $id = $_POST['id'];
    $dokter = $_POST['dokter'];
    $akun = $_POST['akun'];
    $besaran = $_POST['besaran'];
    $ket = $_POST['ket'];
    $unit = $_POST['unit'];

    $update = "UPDATE gajidokter SET dokter = ?, akun = ?, besaran = ?, ket = ?, unit = ? WHERE idgaji = ?";
    $stmt = $koneksimaster->prepare($update);
    $stmt->bind_param('sssssi', $dokter, $akun, $besaran, $ket, $unit, $id);
    $stmt->execute();
    $stmt->close();

    echo "
            <script>
                alert('Data berhasil diupdate');
                window.location.href = 'index.php?halaman=gajidokter_history';
            </script>
        ";
}


if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete = "DELETE FROM gajidokter WHERE idgaji = '$id'";
    $koneksimaster->query($delete);
    echo "
            <script>
                alert('Data berhasil dihapus');
                window.location.href = 'index.php?halaman=gajidokter_history';
            </script>
        ";
}

?>
<div class="card shadow-sm p-2">
    <center>
        <?php
        // Display pagination
        echo '<nav>';
        echo '<ul class="pagination justify-content-center">';

        // Back button
        if ($page > 1) {
            echo '<li class="page-item"><a class="page-link" href="index.php?halaman=pendaftaranall&page=' . ($page - 1) . '">Back</a></li>';
        }

        // Determine the start and end page
        $start_page = max(1, $page - 2);
        $end_page = min($total_pages, $page + 2);

        if ($start_page > 1) {
            echo '<li class="page-item"><a class="page-link" href="index.php?halaman=pendaftaranall&page=1">1</a></li>';
            if ($start_page > 2) {
                echo '<li class="page-item"><span class="page-link">...</span></li>';
            }
        }

        for ($i = $start_page; $i <= $end_page; $i++) {
            if ($i == $page) {
                echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
            } else {
                echo '<li class="page-item"><a class="page-link" href="index.php?halaman=pendaftaranall&page=' . $i . '">' . $i . '</a></li>';
            }
        }

        if ($end_page < $total_pages) {
            if ($end_page < $total_pages - 1) {
                echo '<li class="page-item"><span class="page-link">...</span></li>';
            }
            echo '<li class="page-item"><a class="page-link" href="index.php?halaman=pendaftaranall&page=' . $total_pages . '">' . $total_pages . '</a></li>';
        }

        // Next button
        if ($page < $total_pages) {
            echo '<li class="page-item"><a class="page-link" href="index.php?halaman=pendaftaranall&page=' . ($page + 1) . '">Next</a></li>';
        }

        echo '</ul>';
        echo '</nav>';
        ?>
    </center>
</div>