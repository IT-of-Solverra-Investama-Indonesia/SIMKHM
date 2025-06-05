<?php
$queryPasien = "SELECT *, DATE_FORMAT(jadwal, '%Y-%m-%d') as tgl FROM registrasi_rawat WHERE status_antri != 'Pulang' and perawatan = 'Rawat Inap' ORDER BY idrawat DESC";

$limit = 30;
// Get the total number of records
$result = $koneksi->query($queryPasien);
$total_records = $result->num_rows;

// Calculate total pages
$total_pages = ceil($total_records / $limit);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Add LIMIT clause to the query
$queryPasien .= " LIMIT $start, $limit";

// Now execute the query to get the actual data
$pasien = $koneksi->query($queryPasien);

$linkPage = 'index.php?halaman=entri_obat_inap';
?>
<div class="pagetitle">
    <h1>Entri Obat Inap</h1>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar Pasien</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table <?php if (!isset($_GET['all']) and !isset($_GET['racik'])) {
                                echo "id='myTable'";
                            } ?> class="table table-striped" style="width:100%; font-size: 12px;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Pasien</th>
                                <th>Jenis Perawatan</th>
                                <th>Dokter</th>
                                <th>No RM</th>
                                <th>Jadwal</th>
                                <th>Antrian</th>
                                <th>Diagnosis</th>
                                <th>CaraBayar</th>
                                <?php if (!isset($_GET['racik'])) { ?>
                                    <th>Status</th>
                                <?php } elseif (isset($_GET['racik'])) { ?>
                                    <th>Status Telaah</th>
                                <?php } ?>
                                <th></th>
                                <!-- <th>Aksi</th> -->

                            </tr>
                        </thead>
                        <tbody>

                            <?php $no = 1 ?>

                            <?php foreach ($pasien as $pecah) : ?>
                                <?php
                                $tel = $koneksi->query("SELECT * FROM telaah_resep where jadwal = '$pecah[jadwal]' and no_rm = '$pecah[no_rm]'")->fetch_assoc();
                                ?>
                                <?php
                                $getLastRM = $koneksi->query("SELECT  *, COUNT(*) AS jumm, MAX(id_rm) as id_rm FROM rekam_medis WHERE norm = '" . htmlspecialchars($pecah['no_rm']) . "' AND DATE_FORMAT(jadwal, '%Y-%m-%d') = '" . date('Y-m-d', strtotime($pecah["tgl"])) . "' ORDER BY id_rm DESC LIMIT 1")->fetch_assoc();
                                ?>

                                <tr>
                                    <td onclick="toDetaill('<?php echo trim(preg_replace('/\t+/', '', $pecah['no_rm'])); ?>')"><?php echo $no; ?></td>
                                    <td onclick="toDetaill('<?php echo trim(preg_replace('/\t+/', '', $pecah['no_rm'])); ?>')" class="bg-secondary text-light" style="margin-top:10px;"><?php echo $pecah["nama_pasien"]; ?></td>
                                    <td onclick="toDetaill('<?php echo trim(preg_replace('/\t+/', '', $pecah['no_rm'])); ?>')" style="margin-top:10px;"><?php echo $pecah["perawatan"]; ?></td>
                                    <td onclick="toDetaill('<?php echo trim(preg_replace('/\t+/', '', $pecah['no_rm'])); ?>')" style="margin-top:10px;"><?php echo $pecah["dokter_rawat"]; ?></td>
                                    <td onclick="toDetaill('<?php echo trim(preg_replace('/\t+/', '', $pecah['no_rm'])); ?>')" style="margin-top:10px;"><?php echo $pecah["no_rm"]; ?></td>
                                    <td onclick="toDetaill('<?php echo trim(preg_replace('/\t+/', '', $pecah['no_rm'])); ?>')" style="margin-top:10px;"><?php echo $pecah["jadwal"]; ?></td>
                                    <td onclick="toDetaill('<?php echo trim(preg_replace('/\t+/', '', $pecah['no_rm'])); ?>')" style="margin-top:10px;"><?php echo $pecah["antrian"]; ?></td>
                                    <td onclick="toDetaill('<?php echo trim(preg_replace('/\t+/', '', $pecah['no_rm'])); ?>')" style="margin-top:10px;"><?php echo $getLastRM["diagnosis"]; ?></td>
                                    <td onclick="toDetaill('<?php echo trim(preg_replace('/\t+/', '', $pecah['no_rm'])); ?>')" style="margin-top:10px;"><?php echo $pecah["carabayar"]; ?></td>
                                    <?php if (!isset($_GET['racik'])) { ?>
                                        <td onclick="toDetaill('<?php echo trim(preg_replace('/\t+/', '', $pecah['no_rm'])); ?>')" style="margin-top:10px;">
                                            <?= $pecah['status_antri'] ?>
                                        </td>
                                    <?php } elseif (isset($_GET['racik'])) { ?>
                                        <td onclick="toDetaill('<?php echo trim(preg_replace('/\t+/', '', $pecah['no_rm'])); ?>')" style="margin-top:10px;">
                                            <?php if (!empty($tel["jadwal"])) { ?>
                                                <h6 style="color:success">Selesai</h6>
                                            <?php } else {  ?>
                                                <h6 style="color:red">Belum</h6>
                                            <?php }  ?>
                                        </td>
                                    <?php } ?>
                                    <td>
                                        <div class="dropdown">
                                            <?php if (isset($_GET['racik']) or $_SESSION['admin']['level'] == 'apoteker' or $_SESSION['admin']['level'] == 'racik' or $_SESSION['admin']['level'] == 'sup') { ?>
                                                <a href="index.php?halaman=lpo&id=<?php echo $pecah["no_rm"] ?>&inap&tgl=<?php echo $pecah["tgl"]; ?>" class="btn btn-warning btn-sm"><i class="bi bi-file-earmark-spreadsheet"></i></a>
                                                <a href="index.php?halaman=retur_obat_inap&id=<?php echo $pecah["no_rm"] ?>&inap&tgl=<?php echo $pecah["tgl"]; ?>&idrawat=<?= $pecah['idrawat'] ?>" class="btn btn-sm btn-danger"><i class="bi bi-capsule-pill"></i></a>    
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>

                                <?php $no += 1 ?>
                            <?php endforeach ?>

                        </tbody>
                    </table>
                    <?php
                    if (!isset($_POST['src'])) {
                        // Display pagination
                        echo '<nav>';
                        echo '<ul class="pagination justify-content-center">';

                        // Back button
                        if ($page > 1) {
                            echo '<li class="page-item"><a class="page-link" href="' . $linkPage . '&page=' . ($page - 1) . '">Back</a></li>';
                        }

                        // Determine the start and end page
                        $start_page = max(1, $page - 2);
                        $end_page = min($total_pages, $page + 2);

                        if ($start_page > 1) {
                            echo '<li class="page-item"><a class="page-link" href="' . $linkPage . '&page=1">1</a></li>';
                            if ($start_page > 2) {
                                echo '<li class="page-item"><span class="page-link">...</span></li>';
                            }
                        }

                        for ($i = $start_page; $i <= $end_page; $i++) {
                            if ($i == $page) {
                                echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
                            } else {
                                echo '<li class="page-item"><a class="page-link" href="' . $linkPage . '&page=' . $i . '">' . $i . '</a></li>';
                            }
                        }

                        if ($end_page < $total_pages) {
                            if ($end_page < $total_pages - 1) {
                                echo '<li class="page-item"><span class="page-link">...</span></li>';
                            }
                            echo '<li class="page-item"><a class="page-link" href="' . $linkPage . '&page=' . $total_pages . '">' . $total_pages . '</a></li>';
                        }

                        // Next button
                        if ($page < $total_pages) {
                            echo '<li class="page-item"><a class="page-link" href="' . $linkPage . '&page=' . ($page + 1) . '">Next</a></li>';
                        }

                        echo '</ul>';
                        echo '</nav>';
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>