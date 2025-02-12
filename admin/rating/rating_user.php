<h3>Rating User <?= $_SESSION['admin']['namalengkap'] ?></h3>

<?php
if ($_SESSION['admin']['level'] == 'dokter') {
    $nama = "nama";
    $rating = "vote";
    $komentar = "komentar";
} elseif ($_SESSION['admin']['level'] == 'kasir') {
    $nama = "nama_kasir";
    $rating = "rating_kasir";
    $komentar = "komen_kasir";
} elseif ($_SESSION['admin']['level'] == 'daftar') {
    $nama = "nama_daftar";
    $rating = "rating_daftar";
    $komentar = "komen_daftar";
} elseif ($_SESSION['admin']['level'] == 'perawat') {
    $nama = "nama_prwt";
    $rating = "rating_prwt";
    $komentar = "komen_prwt";
} elseif ($_SESSION['admin']['level'] == 'apoteker') {
    $nama = "nama_apotek";
    $rating = "rating_apotek";
    $komentar = "komen_apotek";
} else {
    $nama = "nama_bersih";
    $rating = "rating_bersih";
    $komentar = "komen_bersih";
}
?>

<div class="card shadow p-2 mb-2">
    <form method="POST">
        <div class="row">
            <div class="col-6">
                <label for="">Date Start</label>
                <input type="date" name="date_start" id="" class="form-control mb-2">
            </div>
            <div class="col-6">
                <label for="">Date End</label>
                <input type="date" name="date_end" id="" class="form-control mb-2">
            </div>
            <div class="col-9">
                <input type="text" name="key" id="" class="form-control mb-2" placeholder="Cari...">
            </div>
            <div class="col-3">
                <button class="btn btn-primary" name="src"><i class="bi bi-filter"></i></button>
            </div>
        </div>
    </form>
</div>
<?php
if (isset($_POST['src'])) {
    echo "
            <script>
                document.location.href='index.php?halaman=rating_user&src&date_start=" . ($_POST['date_start'] == '' ? "0000-00-00" : $_POST['date_start']) . "&date_end=" . ($_POST['date_start'] == '' ? date('Y-m-d') : $_POST['date_start']) . "&key=" . $_POST['key'] . "';
            </script>
        ";
}

$whereCondition = "";
$urlPage = "index.php?halaman=rating_user";
if (isset($_GET['src'])) {
    $whereCondition = "AND tgl >= '$_GET[date_start]' AND tgl <= '$_GET[date_end]' AND ($komentar LIKE '%$_GET[key]%' OR $rating LIKE '%$_GET[key]%')";
    $urlPage = "index.php?halaman=rating_user&src&date_start=$_GET[date_start]&date_end=$_GET[date_end]&key=$_GET[key]";
}
$querySum = "SELECT SUM($rating) as jum, AVG($rating) as avg FROM rating WHERE $nama = '" . $_SESSION['admin']['namalengkap'] . "' AND ($rating != '0' OR $rating != '') " . $whereCondition . " ORDER BY tgl DESC";
$query = "SELECT * FROM rating WHERE $nama = '" . $_SESSION['admin']['namalengkap'] . "' AND ($rating != '0' OR $rating != '') " . $whereCondition . " ORDER BY tgl DESC";
?>

<div class="card shadow p-2 mb-2">
    <span>Total Rating: <?= $koneksi->query($querySum)->fetch_assoc()['jum'] ?> <b>|</b> Rata-rata Rating: <?= $koneksi->query($querySum)->fetch_assoc()['avg'] ?></span>
    <div class="table-responsive">
        <table class="table-hover table table-striped" style="font-size: 12px;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tgl</th>
                    <th>Nama</th>
                    <th>Rating</th>
                    <th>Komentar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $limit = 50; // Number of entries to show in a page
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $start = ($page - 1) * $limit;
                $no = $start+1;

                // Get the total number of records
                $result = $koneksi->query($query);
                $total_records = $result->num_rows;

                // Calculate total pages
                $total_pages = ceil($total_records / $limit);

                $getData = $koneksi->query($query . " LIMIT $start, $limit;");
                foreach ($getData as $data) {
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $data['tgl'] ?></td>
                        <td><?= $data[$nama] ?></td>
                        <td><?= $data[$rating] ?></td>
                        <td><?= $data[$komentar] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<div class="card p-2">
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