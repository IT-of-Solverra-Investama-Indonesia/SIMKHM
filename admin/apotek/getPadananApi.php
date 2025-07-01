<?php
header('Content-Type: application/json');
require_once '../dist/function.php';
if (isset($_GET['dataPadanan']) && $_GET['dataPadanan'] == 'padanan_obat') {
    $kodeObat = isset($_GET['kodeObat']) ? $_GET['kodeObat'] : '';
    if (empty($kodeObat)) {
        echo json_encode(['error' => 'Parameter kodeObat diperlukan']);
        exit;
    }

    $query = "SELECT kode_obat, kode_obat_padanan FROM padanan_obat WHERE kode_obat = ?";
    $stmt = $koneksimaster->prepare($query);
    $stmt->bind_param('s', $kodeObat);
    $stmt->execute();
    $result = $stmt->get_result();

    $padananData = [];
    foreach ($result as $row) {
        $padananData[] = [
            'kode_obat' => $row['kode_obat'],
            'kode_obat_padanan' => $row['kode_obat_padanan'],
            'nama_obat' => $koneksimaster->query("SELECT obat_master FROM master_obat WHERE kode_obat = '{$row['kode_obat_padanan']}'")->fetch_assoc()['obat_master'],
        ];
    }

    echo json_encode($padananData);
} else {
    echo json_encode(['error' => 'Parameter dataPadanan tidak valid']);
}
