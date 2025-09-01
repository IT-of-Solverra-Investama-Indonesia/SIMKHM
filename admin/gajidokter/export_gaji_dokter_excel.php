<?php
// File: export_gaji_dokter_excel.php
include '../dist/function.php';

// Set header untuk download Excel
header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header('Content-Disposition: attachment; filename=gaji_dokter_' . date('Y-m-d') . '.xls');
header('Pragma: no-cache');
header('Expires: 0');

// Ambil parameter filter dari URL
$tgl_start = "";
$tgl_end = "";
$dokter = "";
$akun = "";
$unit = "";
$shiftGaji = "";

if (isset($_GET['tgl_start']) && $_GET['tgl_start'] != '') {
    $tgl_start = "AND tgl >= '" . htmlspecialchars(date('Y-m-d', strtotime($_GET['tgl_start']))) . "'";
}
if (isset($_GET['tgl_end']) && $_GET['tgl_end'] != '') {
    $tgl_end = "AND tgl <= '" . htmlspecialchars(date('Y-m-d', strtotime($_GET['tgl_end']))) . "'";
}
if (isset($_GET['dokter']) && $_GET['dokter'] != 'All Dokter') {
    $dokter = "AND dokter LIKE '%" . htmlspecialchars($_GET['dokter']) . "%'";
}
if (isset($_GET['akun']) && $_GET['akun'] != 'All Akun') {
    $akun = "AND akun = '" . htmlspecialchars($_GET['akun']) . "'";
}
if (isset($_GET['unit']) && $_GET['unit'] != 'All Unit') {
    $unit = "AND unit = '" . htmlspecialchars($_GET['unit']) . "'";
}
if (isset($_GET['shiftgaji']) && $_GET['shiftgaji'] != 'All Shift') {
    $shiftGaji = "AND shiftgaji = '" . htmlspecialchars($_GET['shiftgaji']) . "'";
}

// Query tanpa LIMIT - ambil semua data
$query = "SELECT * FROM gajidokter WHERE dokter != '' $tgl_start $tgl_end $dokter $akun $unit $shiftGaji ORDER BY tgl DESC";
$getData = $koneksimaster->query($query);

// Hitung total untuk summary
$totalQuery = "SELECT 
    COUNT(*) as total_records,
    SUM(besaran) as total_gaji,
    MIN(tgl) as tgl_awal,
    MAX(tgl) as tgl_akhir
    FROM gajidokter WHERE dokter != '' $tgl_start $tgl_end $dokter $akun $unit $shiftGaji";
$summary = $koneksimaster->query($totalQuery)->fetch_assoc();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Export Gaji Dokter</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .header {
            background-color: #4CAF50;
            color: white;
            text-align: center;
        }

        .summary {
            background-color: #e7f3ff;
        }

        .number {
            text-align: right;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <table>
        <tr class="header">
            <td colspan="8">
                <h2>LAPORAN GAJI DOKTER</h2>
                <p>Periode: <?= $summary['tgl_awal'] ?> s/d <?= $summary['tgl_akhir'] ?></p>
                <p>Dicetak: <?= date('d/m/Y H:i:s') ?></p>
            </td>
        </tr>
    </table>

    <br>

    <!-- Summary -->
    <table>
        <tr class="summary">
            <td><strong>Total Records:</strong></td>
            <td><?= number_format($summary['total_records']) ?> data</td>
            <td><strong>Total Gaji:</strong></td>
            <td class="number">Rp <?= number_format($summary['total_gaji'], 0, ',', '.') ?></td>
            <td colspan="4"></td>
        </tr>
    </table>

    <br>

    <!-- Data Table -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Dokter</th>
                <th>Akun</th>
                <th>Besaran</th>
                <th>Keterangan</th>
                <th>Petugas</th>
                <th>Unit</th>
                <th>Shift</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            while ($data = $getData->fetch_assoc()) {
            ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= date('d/m/Y', strtotime($data['tgl'])) ?></td>
                    <td><?= htmlspecialchars($data['dokter']) ?></td>
                    <td><?= htmlspecialchars($data['akun']) ?></td>
                    <td class="number">Rp <?= number_format($data['besaran'], 0, ',', '.') ?></td>
                    <td><?= htmlspecialchars($data['ket']) ?></td>
                    <td><?= htmlspecialchars($data['petugas']) ?></td>
                    <td><?= htmlspecialchars($data['unit']) ?></td>
                    <td><?= htmlspecialchars($data['shiftgaji']) ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>

</html>