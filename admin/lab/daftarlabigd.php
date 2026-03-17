<?php
$user = $_SESSION['admin']['username'];
$level = $_SESSION['admin']['level'];


$ambil = $koneksi->query("SELECT *, lab.tgl AS tglLab FROM lab JOIN igd WHERE id_lab_igd=idigd  GROUP BY pasienlab, lab.tgl ORDER BY idlab DESC;");

$limit = 30;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

if (isset($_GET['cari'])) {
    $cari = $_GET['cari'];
    $query_total = $koneksi->query("SELECT COUNT(DISTINCT lab.pasienlab, lab.tgl) AS total FROM lab JOIN igd WHERE id_lab_igd = idigd AND (pasienlab LIKE '%" . $cari . "%' OR normlab LIKE '%" . $cari . "%')");
    $total_data = $query_total->fetch_assoc()['total'];

    $ambil = $koneksi->query("SELECT *, lab.tgl AS tglLab FROM lab JOIN igd WHERE id_lab_igd = idigd AND (pasienlab LIKE '%" . $cari . "%' OR normlab LIKE '%" . $cari . "%')  GROUP BY pasienlab, lab.tgl ORDER BY idlab DESC LIMIT $offset, $limit");
} else {
    $query_total = $koneksi->query("SELECT COUNT(DISTINCT lab.pasienlab, lab.tgl) AS total FROM lab JOIN igd WHERE id_lab_igd = idigd");
    $total_data = $query_total->fetch_assoc()['total'];

    $ambil = $koneksi->query("SELECT *, lab.tgl AS tglLab FROM lab JOIN igd WHERE id_lab_igd=idigd  GROUP BY pasienlab, lab.tgl ORDER BY idlab DESC LIMIT $offset, $limit");
}

$total_pages = ceil($total_data / $limit);
?>


<!-- !-- DataTables  -->

<!-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css"> -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<br>
<br>
<br>

<div class="container">
    <div class="pagetitle">
        <h1>Daftar Rujukan Lab IGD</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php?halaman=daftarlabinap" style="color:blue;">Rujukan Lab Inap</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <br>
    <br>
    <br>

    <form method="GET" action="">
        <div class="input-group mb-3">
            <input type="hidden" name="halaman" value="daftarlabigd">
            <input
                type="text"
                id="cari_input"
                name="cari"
                class="form-control"
                placeholder="Cari Nama / No RM"
                value="<?php echo htmlspecialchars($_GET['cari'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                aria-label="Cari Obat"
                aria-describedby="button-search">
            <button class="btn btn-primary" type="submit" id="button-search">
                <i class="bi bi-search"></i>
            </button>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table" style="width:100%;" id="myTable">
            <thead>
                <tr>
                    <th>Tgl</th>
                    <th>No RM</th>
                    <th>Nama</th>
                    <th>Aksi</th>

                </tr>

            </thead>

            <tbody>

                <?php foreach ($ambil as $pecah) : ?>
                    <tr>
                        <td><?php echo $pecah["tglLab"]; ?></td>
                        <td><?php echo $pecah["normlab"]; ?></td>
                        <td><?php echo $pecah["pasienlab"]; ?></td>
                        <td>
                            <div class="dropdown">
                                <i data-bs-toggle="dropdown" style="color: blue; font-weight: bold; font-size: 20px;" class="bi bi-three-dots-vertical"></i>
                                <ul class="dropdown-menu">
                                    <li><a href="index.php?halaman=detaillabigd&id=<?php echo $pecah["idigd"] ?>&tgl=<?php echo $pecah["tglLab"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-eye-fill" style="color:blue;"></i> Detail</a></li>
                                    <li><a href="index.php?halaman=isilabigd&id=<?php echo $pecah["idigd"] ?>&tgl=<?php echo $pecah["tglLab"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-card-list" style="color:blueviolet;"></i> Isi Data Lab</a></li>
                                    <li><a href="index.php?halaman=ubahhasiligd&id=<?php echo $pecah["idigd"] ?>&tgl=<?php echo $pecah["tglLab"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-pencil" style="color:hotpink;"></i> Ubah</a></li>
                                    <li><a href="index.php?halaman=hapuslab&id=<?php echo $pecah["idlab"]; ?>" class="dropdown-item" style="text-decoration: none; font-weight: bold; margin-left: 2px;" onclick="return confirm('Anda yakin mau menghapus item ini ?')">
                                            <i class="bi bi-trash" style="color:red;"></i> Hapus</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endforeach ?>

            </tbody>
        </table>
    </div>

    <?php if ($total_pages > 1): ?>
        <nav aria-label="Page navigation" class="mt-3">
            <ul class="pagination justify-content-center">
                <?php
                $cari_param = isset($_GET['cari']) ? "&cari=" . urlencode($_GET['cari']) : "";
                ?>

                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?halaman=daftarlabigd<?= $cari_param ?>&page=<?= $page - 1 ?>">Prev</a>
                </li>

                <?php
                $start_page = max(1, $page - 2);
                $end_page = min($total_pages, $page + 2);

                if ($start_page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="?halaman=daftarlabigd' . $cari_param . '&page=1">1</a></li>';
                    if ($start_page > 2) {
                        echo '<li class="page-item disabled"><a class="page-link">...</a></li>';
                    }
                }

                for ($i = $start_page; $i <= $end_page; $i++) {
                    $active = ($i == $page) ? 'active' : '';
                    echo '<li class="page-item ' . $active . '"><a class="page-link" href="?halaman=daftarlabigd' . $cari_param . '&page=' . $i . '">' . $i . '</a></li>';
                }

                if ($end_page < $total_pages) {
                    if ($end_page < $total_pages - 1) {
                        echo '<li class="page-item disabled"><a class="page-link">...</a></li>';
                    }
                    echo '<li class="page-item"><a class="page-link" href="?halaman=daftarlabigd' . $cari_param . '&page=' . $total_pages . '">' . $total_pages . '</a></li>';
                }
                ?>

                <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?halaman=daftarlabigd<?= $cari_param ?>&page=<?= $page + 1 ?>">Next</a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>


    <!-- <script>
        $(document).ready(function() {
            $('#myTable').DataTable({

                paging: true,
                pageLength: 50,
                sorting: true,
                lengthChange: true,
                lengthMenu: [
                    [10, 50, 25, 100, 300, -1],
                    [10, 25, 100, 300, "All"]
                ],
                dom: 'B<"clear">lfrtip',
                buttons: [
                    'excel'
                ]
            });
        });
    </script> -->
</div>