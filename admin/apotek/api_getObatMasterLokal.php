<?php
session_start();
// api_get_obat.php
header('Content-Type: application/json');
include '../dist/function.php';
$response = [];


$response[] = [
    'kode_obat' => '',
    'nama_obat' => 'Pilih Obat',
];

$whereCondition = "";
if ($_SESSION['admin']['namalengkap'] != 'dr. Rosyid Mawardi, Sp.PD' and $_SESSION['admin']['namalengkap'] != 'dr. Wigit Kristianto, Sp.A') {
    if (isset($_GET['inap'])) {
        $whereCondition = " AND aktif_ranap = 'aktif'";
    } elseif (isset($_GET['umum'])) {
        $whereCondition = " AND aktif_umum = 'aktif'";
    } elseif (isset($_GET['all'])) {
        $whereCondition = " AND (aktif_poli = 'aktif' OR aktif_ranap = 'aktif' OR aktif_umum = 'aktif')";
    } else {
        $whereCondition = " AND aktif_poli = 'aktif'";
    }
}

$getObatMaster = $koneksimaster->query("SELECT * FROM master_obat WHERE kode_obat != 'V.1394489' $whereCondition ORDER BY obat_master ASC");

foreach ($getObatMaster as $dataMaster) {
    $response[] = [
        'kode_obat' => $dataMaster['kode_obat'],
        'nama_obat' => $dataMaster['obat_master']
    ];
}
// while ($data = $getObatMaster->fetch_assoc()) {
// }

echo json_encode($response);
