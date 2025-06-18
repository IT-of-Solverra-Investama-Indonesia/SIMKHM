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
        $getdata = $koneksi->query("SELECT petugas, nama_obat, SUM(jumlah) AS jumlah, SUM(harga_umum * (1 - diskon_obat / 100) * jumlah) AS omset FROM penjualan_umum WHERE tgl_jual BETWEEN '$tgl_awal' AND '$tgl_akhir' AND kode_obat = '$kode_obat' GROUP BY nama_obat, petugas ORDER BY tgl_jual ASC;");
    } elseif ($tgl_awal && $tgl_akhir) {
        $getdata = $koneksi->query("SELECT petugas, nama_obat, SUM(jumlah) AS jumlah, SUM(harga_umum * (1 - diskon_obat / 100) * jumlah) AS omset FROM penjualan_umum WHERE tgl_jual BETWEEN '$tgl_awal' AND '$tgl_akhir' GROUP BY nama_obat, petugas ORDER BY tgl_jual ASC;");
    } elseif ($kode_obat) {
        $getdata = $koneksi->query("SELECT petugas, nama_obat, SUM(jumlah) AS jumlah, SUM(harga_umum * (1 - diskon_obat / 100) * jumlah) AS omset FROM penjualan_umum WHERE kode_obat = '$kode_obat' GROUP BY nama_obat, petugas ORDER BY tgl_jual ASC;");
    } else {
        $getdata = $koneksi->query("SELECT petugas, nama_obat, SUM(jumlah) AS jumlah, SUM(harga_umum * (1 - diskon_obat / 100) * jumlah) AS omset FROM penjualan_umum GROUP BY nama_obat, petugas ORDER BY nama_obat ASC;");
    }
}

if ($jenis == 'omset_umum') {
    $getdataumum = $koneksi->query("SELECT DATE_FORMAT(tgl_jual, '%Y-%m') AS bulan, SUM((harga_umum - ((diskon_obat / 100)*harga_umum)) * jumlah) AS omset FROM penjualan_umum WHERE tgl_jual >= DATE_SUB(CURDATE(), INTERVAL 24 MONTH) GROUP BY DATE_FORMAT(tgl_jual, '%Y-%m') ORDER BY bulan ASC;");
}
if ($jenis == 'rekanan') {
    $getdatarekanan = $koneksi->query("SELECT DATE_FORMAT(tgl_jual, '%Y-%m') AS bulan, SUM(harga_umum * (1 - diskon_obat / 100) * jumlah) AS omset FROM penjualan_rekanan WHERE tgl_jual >= DATE_SUB(CURDATE(), INTERVAL 24 MONTH) GROUP BY DATE_FORMAT(tgl_jual, '%Y-%m') ORDER BY bulan ASC;");
}
if ($jenis == 'penjualan_umum') {
    $getdatapenjualanumum = $koneksi->query("SELECT DATE_FORMAT(tgl_jual, '%Y-%m') AS bulan, petugas, SUM(harga_umum * (1 - diskon_obat / 100) * jumlah) AS omset FROM penjualan_umum WHERE tgl_jual >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH) GROUP BY DATE_FORMAT(tgl_jual, '%Y-%m'), petugas ORDER BY bulan ASC, petugas ASC;");
}

if ($jenis == 'penjualan_resep') {
    $getdatapenjualanresep = $koneksi->query("SELECT DATE_FORMAT(tgl_jual, '%Y-%m') AS bulan, petugas, SUM(harga_umum * (1 - diskon_obat / 100) * jumlah) AS omset FROM penjualan_resep WHERE tgl_jual >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH) GROUP BY DATE_FORMAT(tgl_jual, '%Y-%m'), petugas ORDER BY bulan ASC,petugas ASC;");
}

if ($jenis == '') {
    $getdata = $koneksi->query("SELECT
    bulan,
    SUM(omset) AS total_omset
    FROM (
    SELECT
    DATE_FORMAT(tgl_jual, '%Y-%m') AS bulan,
    ((harga_umum - ((diskon_obat / 100)*harga_umum)) * jumlah) AS omset
    FROM penjualan_umum
    WHERE tgl_jual >= DATE_SUB(CURDATE(), INTERVAL 24 MONTH)

    UNION ALL

    SELECT
    DATE_FORMAT(tgl_jual, '%Y-%m') AS bulan,
    ((harga_umum - ((diskon_obat / 100)*harga_umum)) * jumlah) AS omset
    FROM penjualan_resep
    WHERE tgl_jual >= DATE_SUB(CURDATE(), INTERVAL 24 MONTH)

    UNION ALL

    SELECT
    DATE_FORMAT(tgl_jual, '%Y-%m') AS bulan,
    ((harga_umum - ((diskon_obat / 100)*harga_umum)) * jumlah) AS omset
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
                <input type="date" name="tgl_awal" class="form-control mb-2" placeholder="Tanggal Awal">
            </div>
            <div class="col-3">
                <input type="date" name="tgl_akhir" class="form-control mb-2" placeholder="Tanggal Akhir">
            </div>
            <div class="col-4">
                <select name="kode_obat" class="from-control w-100 obat-select form-control-sm" id="obat_kode"></select>
            </div>
            <div class="col-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                <a href="index.php?halaman=dashboardapotek" class="btn btn-danger">Reset</a>
            </div>
        </div>
    </form>

    <form method="GET" action="index.php">
        <input type="hidden" name="halaman" value="dashboardapotek">
        <label for="jenis-omset" class="form-label">Filter</label>
        <div class="d-flex">
            <select name="jenis_omset" class="form-control" id="jenis-omset">
                <option value="">Pilih Jenis</option>
                <option value="omset_umum" <?= $jenis == 'omset_umum' ? 'selected' : '' ?>>Omset Apotek Umum (24 Bulan)</option>
                <option value="rekanan" <?= $jenis == 'rekanan' ? 'selected' : '' ?>>Penjualan Rekanan (24 Bulan)</option>
                <option value="penjualan_umum" <?= $jenis == 'penjualan_umum' ? 'selected' : '' ?>>Penjualan Umum</option>
                <option value="penjualan_resep" <?= $jenis == 'penjualan_resep' ? 'selected' : '' ?>>Penjualan Resep</option>
            </select>
            <button type="submit" class="btn btn-primary mx-2"><i class="bi bi-search"></i></button>
            <a href="index.php?halaman=dashboardapotek" class="btn btn-danger">Reset</a>
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
            <table class="table" id="myTable3">
                <thead>
                    <tr>
                        <th>Nama Petugas</th>
                        <th>Nama Obat</th>
                        <th>Jumlah Jual</th>
                        <th>Omset</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($getdata as $pecah) : ?>
                        <tr>
                            <td><?= $pecah['petugas'] ?></td>
                            <td><?= $pecah['nama_obat'] ?></td>
                            <td><?= $pecah['jumlah'] ?></td>
                            <td><?= number_format($pecah['omset'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Total</th>
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