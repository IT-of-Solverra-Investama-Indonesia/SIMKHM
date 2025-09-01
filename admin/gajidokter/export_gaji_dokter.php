<?php
// File: export_gaji_dokter.php
include '../dist/function.php';

// Set header untuk download CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=gaji_dokter_' . date('Y-m-d') . '.csv');

// Buat output stream
$output = fopen('php://output', 'w');

// Tulis BOM untuk UTF-8 (agar Excel bisa baca karakter Indonesia)
fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

// Tulis header CSV
fputcsv($output, [
    'Tanggal',
    'Dokter',
    'Akun',
    'Besaran',
    'Keterangan',
    'Petugas',
    'Unit',
    'Shift'
]);

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

// Tulis data ke CSV
while ($data = $getData->fetch_assoc()) {
    fputcsv($output, [
        $data['tgl'],
        $data['dokter'],
        $data['akun'],
        $data['besaran'],
        $data['ket'],
        $data['petugas'],
        $data['unit'],
        $data['shiftgaji']
    ]);
}

fclose($output);
exit;
