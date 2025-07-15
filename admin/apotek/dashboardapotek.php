<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">

<div class="pagetitle mb-3">
    <h1>Omset Apotek Per Bulan</h1>
</div>

<?php
$jenis = $_GET['jenis_omset'] ?? '';
$getdata = null;

if ($jenis == 'filter_penjualan_umum') {
    $tgl_awal = $_GET['tgl_awal'] ?? '';
    $tgl_akhir = $_GET['tgl_akhir'] ?? '';
    $kode_obat = $_GET['kode_obat'] ?? '';

    if ($tgl_awal && $tgl_akhir && $kode_obat) {
        $getdata = $koneksi->query("SELECT petugas, nama_obat, SUM(jumlah) AS jumlah, SUM((harga_umum-diskon_obat)*jumlah) AS omset FROM penjualan_umum WHERE tgl_jual BETWEEN '$tgl_awal' AND '$tgl_akhir' AND kode_obat = '$kode_obat' GROUP BY nama_obat, petugas ORDER BY tgl_jual ASC;");
    } elseif ($tgl_awal && $tgl_akhir) {
        $getdata = $koneksi->query("SELECT petugas, nama_obat, SUM(jumlah) AS jumlah, SUM((harga_umum-diskon_obat)*jumlah) AS omset FROM penjualan_umum WHERE tgl_jual BETWEEN '$tgl_awal' AND '$tgl_akhir' GROUP BY nama_obat, petugas ORDER BY tgl_jual ASC;");
    } elseif ($kode_obat) {
        $getdata = $koneksi->query("SELECT petugas, nama_obat, SUM(jumlah) AS jumlah, SUM((harga_umum-diskon_obat)*jumlah) AS omset FROM penjualan_umum WHERE kode_obat = '$kode_obat' GROUP BY nama_obat, petugas ORDER BY tgl_jual ASC;");
    } else {
        $getdata = $koneksi->query("SELECT petugas, nama_obat, SUM(jumlah) AS jumlah, SUM((harga_umum-diskon_obat)*jumlah) AS omset FROM penjualan_umum GROUP BY nama_obat, petugas ORDER BY nama_obat ASC;");
    }
}

if ($jenis == 'omset_umum') {
    $getdataumum = $koneksi->query("SELECT DATE_FORMAT(tgl_jual, '%Y-%m') AS bulan, SUM((harga_umum - diskon_obat)*jumlah) AS omset FROM penjualan_umum WHERE tgl_jual >= DATE_SUB(CURDATE(), INTERVAL 24 MONTH) GROUP BY DATE_FORMAT(tgl_jual, '%Y-%m') ORDER BY bulan ASC;");
}
if ($jenis == 'rekanan') {
    $getdatarekanan = $koneksi->query("SELECT DATE_FORMAT(tgl_jual, '%Y-%m') AS bulan, SUM((harga_umum - diskon_obat) * jumlah) AS omset FROM penjualan_rekanan WHERE tgl_jual >= DATE_SUB(CURDATE(), INTERVAL 24 MONTH) GROUP BY DATE_FORMAT(tgl_jual, '%Y-%m') ORDER BY bulan ASC;");
}
if ($jenis == 'penjualan_umum') {
    $getdatapenjualanumum = $koneksi->query("SELECT DATE_FORMAT(tgl_jual, '%Y-%m') AS bulan, petugas, SUM((harga_umum-diskon_obat)*jumlah) AS omset FROM penjualan_umum WHERE tgl_jual >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH) GROUP BY DATE_FORMAT(tgl_jual, '%Y-%m'), petugas ORDER BY bulan ASC, petugas ASC;");
}

if ($jenis == 'penjualan_resep') {
    $getdatapenjualanresep = $koneksi->query("SELECT DATE_FORMAT(tgl_jual, '%Y-%m') AS bulan, petugas, SUM((harga_umum-diskon_obat)*jumlah) AS omset FROM penjualan_resep WHERE tgl_jual >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH) GROUP BY DATE_FORMAT(tgl_jual, '%Y-%m'), petugas ORDER BY bulan ASC,petugas ASC;");
}

if ($jenis == '') {
    $getdata = $koneksi->query("SELECT
    bulan,
    SUM(omset) AS total_omset
    FROM (
    SELECT
    DATE_FORMAT(tgl_jual, '%Y-%m') AS bulan,
    ((harga_umum - diskon_obat)*jumlah) AS omset
    FROM penjualan_umum
    WHERE tgl_jual >= DATE_SUB(CURDATE(), INTERVAL 24 MONTH)

    UNION ALL

    SELECT
    DATE_FORMAT(tgl_jual, '%Y-%m') AS bulan,
    ((harga_umum - diskon_obat)*jumlah) AS omset
    FROM penjualan_resep
    WHERE tgl_jual >= DATE_SUB(CURDATE(), INTERVAL 24 MONTH)

    UNION ALL

    SELECT
    DATE_FORMAT(tgl_jual, '%Y-%m') AS bulan,
    ((harga_umum - diskon_obat)*jumlah) AS omset
    FROM penjualan_rekanan
    WHERE tgl_jual >= DATE_SUB(CURDATE(), INTERVAL 24 MONTH)

    UNION ALL

    SELECT
    DATE_FORMAT(tgl, '%Y-%m') AS bulan,
    SUM(besaran) AS omset
    FROM rawatinapdetail
    WHERE biaya LIKE '%obat%' AND tgl >= DATE_SUB(CURDATE(), INTERVAL 24 MONTH)
    ) AS semua_penjualan
    GROUP BY bulan
    ORDER BY bulan ASC LIMIT 24;");
}
?>

<div class="card shadow p-3">
    <form action="index.php" method="GET">
        <input type="hidden" name="halaman" value="dashboardapotek">
        <input type="hidden" name="jenis_omset" value="filter_penjualan_umum">
        <label for="filter-tanggal" class="form-label">Filter Tanggal</label>
        <div class="row g-3">
            <div class="col-3">
                <input type="date" name="tgl_awal" class="form-control form-control-sm mb-2" placeholder="Tanggal Awal">
            </div>
            <div class="col-3">
                <input type="date" name="tgl_akhir" class="form-control form-control-sm mb-2" placeholder="Tanggal Akhir">
            </div>
            <div class="col-4">
                <select name="kode_obat" class="from-control w-100 obat-select form-control-sm" id="obat_kode"></select>
            </div>
            <div class="col-2">
                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>
                <a href="index.php?halaman=dashboardapotek" class="btn btn-danger btn-sm">Reset</a>
            </div>
        </div>
    </form>

    <form method="GET" action="index.php">
        <input type="hidden" name="halaman" value="dashboardapotek">
        <label for="jenis-omset" class="form-label">Filter</label>
        <div class="d-flex">
            <select name="jenis_omset" class="form-control form-control-sm" id="jenis-omset">
                <option value="">Pilih Jenis</option>
                <option value="omset_umum" <?= $jenis == 'omset_umum' ? 'selected' : '' ?>>Omset Apotek Umum (24 Bulan)</option>
                <option value="rekanan" <?= $jenis == 'rekanan' ? 'selected' : '' ?>>Penjualan Rekanan (24 Bulan)</option>
                <option value="penjualan_umum" <?= $jenis == 'penjualan_umum' ? 'selected' : '' ?>>Penjualan Umum</option>
                <option value="penjualan_resep" <?= $jenis == 'penjualan_resep' ? 'selected' : '' ?>>Penjualan Resep</option>
                <option value="omset_apotek_all" <?= $jenis == 'omset_apotek_all' ? 'selected' : '' ?>>Omset Apotek All</option>
            </select>
            <button type="submit" class="btn btn-primary mx-2 btn-sm"><i class="bi bi-search"></i></button>
            <a href="index.php?halaman=dashboardapotek" class="btn btn-danger btn-sm">Reset</a>
        </div>
    </form>
</div>

<!-- Omset All -->
<?php if ($jenis === '') : ?>
    <div class="card shadow p-3">
        <div class="table-responsive">
            <table class="table" id="myTable">
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th>Omset</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($getdata as $pecah) : ?>
                        <tr>
                            <td><?= $pecah['bulan'] ?></td>
                            <td><?= number_format($pecah['total_omset'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="1" class="text-end">Total</th>
                        <th>
                            <?php
                            $totalOmset = 0;
                            foreach ($getdata as $pecah) {
                                $totalOmset += $pecah['total_omset'];
                            }
                            echo number_format($totalOmset, 2, ',', '.');
                            ?>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
<?php endif ?>

<?php if ($jenis === 'omset_umum') : ?>
    <!-- Omset Umum -->
    <div class="card shadow p-3">
        <div class="table-responsive">
            <table class="table" id="myTable1">
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th>Omset</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($getdataumum as $pecah) : ?>
                        <tr>
                            <td><?= $pecah['bulan'] ?></td>
                            <td><?= number_format($pecah['omset'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="1" class="text-end">Total</th>
                        <th>
                            <?php
                            $totalOmset = 0;
                            foreach ($getdataumum as $pecah) {
                                $totalOmset += $pecah['omset'];
                            }
                            echo number_format($totalOmset, 2, ',', '.');
                            ?>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
<?php endif ?>

<?php if ($jenis === 'rekanan') : ?>
    <!-- Penjualan Rekanan -->
    <div class="card shadow p-3">
        <div class="table-responsive">
            <table class="table" id="myTable2">
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th>Omset</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($getdatarekanan as $pecah) : ?>
                        <tr>
                            <td><?= $pecah['bulan'] ?></td>
                            <td><?= number_format($pecah['omset'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="1" class="text-end">Total</th>
                        <th>
                            <?php
                            $totalOmset = 0;
                            foreach ($getdatarekanan as $pecah) {
                                $totalOmset += $pecah['omset'];
                            }
                            echo number_format($totalOmset, 2, ',', '.');
                            ?>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
<?php endif ?>

<?php if ($jenis === 'penjualan_umum') : ?>
    <!-- Penjualan Umum -->
    <div class="card shadow p-3">
        <div class="table-responsive">
            <table class="table" id="myTable3">
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th>Petugas</th>
                        <th>Omset</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($getdatapenjualanumum as $pecah) : ?>
                        <tr>
                            <td><?= $pecah['bulan'] ?></td>
                            <td><?= $pecah['petugas'] ?></td>
                            <td><?= number_format($pecah['omset'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-end">Total</th>
                        <th>
                            <?php
                            $totalOmset = 0;
                            foreach ($getdatapenjualanumum as $pecah) {
                                $totalOmset += $pecah['omset'];
                            }
                            echo number_format($totalOmset, 2, ',', '.');
                            ?>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
<?php endif ?>

<?php if ($jenis === 'filter_penjualan_umum'): ?>
    <!-- Filter Penjualan Umum -->
    <div class="card shadow p-3">
        <div class="table-responsive">
            <h6>Filter Penjualan Umum dari <?= $_GET['tgl_awal'] ?> sampai <?= $_GET['tgl_akhir'] ?></h6>
            <table class="table" id="myTable3" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th>Nama Petugas</th>
                        <th>Nama Obat</th>
                        <th>Jumlah Jual</th>
                        <th>Omset</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $jumlahTotal = 0; ?>
                    <?php foreach ($getdata as $pecah) : ?>
                        <tr>
                            <td><?= $pecah['petugas'] ?></td>
                            <td><?= $pecah['nama_obat'] ?></td>
                            <td><?= $pecah['jumlah'] ?></td>
                            <td><?= number_format($pecah['omset'], 2, ',', '.'); ?></td>
                        </tr>
                        <?php
                        $jumlahTotal += $pecah['jumlah'];
                        ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-start">Total</th>
                        <th><?= $jumlahTotal ?></th>
                        <th>
                            <?php
                            $totalOmset = 0;
                            foreach ($getdata as $pecah) {
                                $totalOmset += $pecah['omset'];
                            }
                            echo number_format($totalOmset, 2, ',', '.');
                            ?>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

<?php endif ?>

<?php if ($jenis === 'penjualan_resep') : ?>
    <!-- Penjualan Resep -->
    <div class="card shadow p-3">
        <div class="table-responsive">
            <table class="table" id="myTable4">
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th>Petugas</th>
                        <th>Omset</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($getdatapenjualanresep as $pecah) : ?>
                        <tr>
                            <td><?= $pecah['bulan'] ?></td>
                            <td><?= $pecah['petugas'] ?></td>
                            <td><?= number_format($pecah['omset'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-end">Total</th>
                        <th>
                            <?php
                            $totalOmset = 0;
                            foreach ($getdatapenjualanresep as $pecah) {
                                $totalOmset += $pecah['omset'];
                            }
                            echo number_format($totalOmset, 2, ',', '.');
                            ?>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
<?php endif ?>

<?php if ($jenis === 'omset_apotek_all') { ?>
    <?php
    // Ambil bulan awal dan akhir dari GET, jika ada
    $month_start = $_GET['month_start'] ?? '';
    $month_end = $_GET['month_end'] ?? '';
    ?>
    <div class="card shadow p-2 mb-2">
        <form method="get">
            <div class="row g-1">
                <div class="col-5">
                    <input type="hidden" name="halaman" value="dashboardapotek">
                    <input type="hidden" name="jenis_omset" value="omset_apotek_all">
                    <input type="month" value="<?= htmlspecialchars($month_start) ?>" placeholder="Dari Bulan" class="form-control form-control-sm" name="month_start">
                </div>
                <div class="col-5">
                    <input type="month" value="<?= htmlspecialchars($month_end) ?>" placeholder="Hingga Bulan" class="form-control form-control-sm" name="month_end">
                </div>
                <div class="col-2">
                    <button class="btn btn-sm btn-primary" type="submit" name="src"><i class="bi bi-search"></i></button>
                </div>
            </div>
        </form>
    </div>
    <div class="card shadow p-3">
        <div class="table-responsive">
            <table class="table table-sm table-hover table-striped" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th>PenjualanUmum</th>
                        <th>PenjualanResep</th>
                        <th>PenjualanRekanan</th>
                        <th>PenjualanInternal</th>
                        <th>ObatInap</th>
                        <th>OmsetTotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Jika tidak ada, gunakan 12 bulan terakhir
                    if (!$month_end) {
                        $month_end = date('Y-m');
                    }
                    if (!$month_start) {
                        $month_start = date('Y-m', strtotime('-11 months', strtotime($month_end . '-01')));
                    }

                    // Buat array bulan dari month_start ke month_end (DESC)
                    $period = [];
                    $start = DateTime::createFromFormat('Y-m', $month_start);
                    $end = DateTime::createFromFormat('Y-m', $month_end);
                    $end->modify('+1 month');
                    for ($dt = clone $end; $dt > $start; $dt->modify('-1 month')) {
                        $period[] = (clone $dt)->modify('-1 month');
                    }

                    // Query omset per bulan per jenis
                    foreach ($period as $dt) {
                        $bulan = $dt->format('Y-m');
                        // Penjualan Umum
                        $q1 = $koneksi->query("SELECT SUM((harga_umum-diskon_obat) * jumlah) AS omset FROM penjualan_umum WHERE DATE_FORMAT(tgl_jual, '%Y-%m') = '$bulan'");
                        $umum = $q1 ? ($q1->fetch_assoc()['omset'] ?? 0) : 0;

                        // Penjualan Resep
                        $q2 = $koneksi->query("SELECT SUM((harga_umum-diskon_obat) * jumlah) AS omset FROM penjualan_resep WHERE DATE_FORMAT(tgl_jual, '%Y-%m') = '$bulan'");
                        $resep = $q2 ? ($q2->fetch_assoc()['omset'] ?? 0) : 0;

                        // Penjualan Rekanan
                        $q3 = $koneksi->query("SELECT SUM((harga_umum-diskon_obat) * jumlah) AS omset FROM penjualan_rekanan WHERE DATE_FORMAT(tgl_jual, '%Y-%m') = '$bulan'");
                        $rekanan = $q3 ? ($q3->fetch_assoc()['omset'] ?? 0) : 0;

                        // Penjualan Internal (jika ada tabelnya, jika tidak, set 0)
                        $q4 = $koneksi->query("SELECT SUM((harga_umum-diskon_obat) * jumlah) AS omset FROM penjualan_internal WHERE DATE_FORMAT(tgl_jual, '%Y-%m') = '$bulan'");
                        $internal = $q4 ? ($q4->fetch_assoc()['omset'] ?? 0) : 0;

                        // Obat Inap
                        $q5 = $koneksi->query("SELECT SUM(besaran) AS omset FROM rawatinapdetail WHERE biaya LIKE '%obat%' AND DATE_FORMAT(tgl, '%Y-%m') = '$bulan'");
                        $inap = $q5 ? ($q5->fetch_assoc()['omset'] ?? 0) : 0;

                        $total = $umum + $resep + $rekanan + $internal + $inap;

                        echo "<tr>
                            <td>{$bulan}</td>
                            <td>" . number_format($umum, 0, ',', '.') . "</td>
                            <td>" . number_format($resep, 0, ',', '.') . "</td>
                            <td>" . number_format($rekanan, 0, ',', '.') . "</td>
                            <td>" . number_format($internal, 0, ',', '.') . "</td>
                            <td>" . number_format($inap, 0, ',', '.') . "</td>
                            <td>" . number_format($total, 0, ',', '.') . "</td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
<?php } ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<script>
    $(document).ready(function() {
        $('#myTable').DataTable({});
        $('#myTable1').DataTable({});
        $('#myTable2').DataTable({});
        $('#myTable3').DataTable({});
        $('#myTable4').DataTable({});
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#obat_kode').select2();
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', async () => {
        try {
            <?php

            use BcMath\Number;

            $apiUrlgetObat = '../apotek/api_getObatMasterLokal.php';
            $apiUrlgetObat .= '?umum';
            ?>
            const obatData = await (await fetch('<?= $apiUrlgetObat ?>')).json();

            document.querySelectorAll('.obat-select').forEach(select => {
                // Simpan nilai yang sedang dipilih (jika ada)
                const selectedValue = select.value;

                // Buat array dari nilai option yang sudah ada
                const existingOptions = Array.from(select.options).map(opt => opt.value);

                // Filter data obat untuk hanya menambahkan yang belum ada
                const newOptions = obatData.filter(obat =>
                    !existingOptions.includes(obat.kode_obat)
                );

                // Tambahkan option baru
                newOptions.forEach(obat => {
                    select.add(new Option(obat.nama_obat, obat.kode_obat));
                });

                // Kembalikan nilai yang dipilih sebelumnya (jika masih ada)
                if (selectedValue && select.querySelector(`option[value="${selectedValue}"]`)) {
                    select.value = selectedValue;
                }
            });
        } catch (error) {
            console.error('Error:', error);
        }
    });
</script>