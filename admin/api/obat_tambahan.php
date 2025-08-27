<?php
header('Content-Type: application/json');
include '../dist/function.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

function response($data)
{
    echo json_encode($data);
    exit;
}

// CREATE
if ($method === 'POST') {
    $kode_obat = $_POST['kode_obat'] ?? '';
    $jumlah = $_POST['jumlah'] ?? '';
    $dosis_1 = $_POST['dosis_1'] ?? '';
    $dosis_2 = $_POST['dosis_2'] ?? '';
    $periode = $_POST['periode'] ?? '';
    $rekam_medis_id = $_POST['rekam_medis_id'] ?? '';

    // nama_obat, harga_beli, no_rm dikosongkan
    $getApotek = $koneksi->query("SELECT * FROM apotek WHERE id_obat='" . $koneksi->real_escape_string($kode_obat) . "' ORDER BY idapotek DESC LIMIT 1")->fetch_assoc();
    $nama_obat = $getApotek['nama_obat'] ?? '';
    $harga_beli = $getApotek['harga_beli'] ?? '';

    $getRekamMedis = $koneksi->query("SELECT * FROM rekam_medis WHERE id_rm='" . $koneksi->real_escape_string($rekam_medis_id) . "'")->fetch_assoc();
    $no_rm = $getRekamMedis['norm'] ?? '';

    global $koneksi;
    $q = $koneksi->prepare("INSERT INTO obat_tambahan (nama_obat, kode_obat, jumlah, dosis_1, dosis_2, periode, harga_beli, rekam_medis_id, no_rm) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $q->bind_param('sssssssis', $nama_obat, $kode_obat, $jumlah, $dosis_1, $dosis_2, $periode, $harga_beli, $rekam_medis_id, $no_rm);
    $ok = $q->execute();
    if ($ok) {
        response(['status' => 'success', 'id' => $koneksi->insert_id]);
    } else {
        response([
            'status' => 'error',
            'message' => 'Insert failed',
            'error' => $q->error
        ]);
    }
}

// READ (by rekam_medis_id)
if ($method === 'GET') {
    $rekam_medis_id = $_GET['rekam_medis_id'] ?? '';
    global $koneksi;
    $result = $koneksi->query("SELECT * FROM obat_tambahan WHERE rekam_medis_id='" . $koneksi->real_escape_string($rekam_medis_id) . "' ORDER BY id DESC");
    $data = [];
    while ($row = $result->fetch_assoc()) $data[] = $row;
    response(['status' => 'success', 'data' => $data]);
}

// UPDATE
if ($method === 'PUT') {
    $id = $input['id'] ?? '';
    $jumlah = $input['jumlah'] ?? '';
    $dosis_1 = $input['dosis_1'] ?? '';
    $dosis_2 = $input['dosis_2'] ?? '';
    $periode = $input['periode'] ?? '';
    global $koneksi;
    $q = $koneksi->prepare("UPDATE obat_tambahan SET jumlah=?, dosis_1=?, dosis_2=?, periode=? WHERE id=?");
    $q->bind_param('ssssi', $jumlah, $dosis_1, $dosis_2, $periode, $id);
    $ok = $q->execute();
    if ($ok) {
        response(['status' => 'success']);
    } else {
        response([
            'status' => 'error',
            'message' => 'Update failed',
            'error' => $q->error
        ]);
    }
}

// DELETE
if ($method === 'DELETE') {
    $id = $input['id'] ?? '';
    global $koneksi;
    $q = $koneksi->prepare("DELETE FROM obat_tambahan WHERE id=?");
    $q->bind_param('i', $id);
    $ok = $q->execute();
    if ($ok) {
        response(['status' => 'success']);
    } else {
        response([
            'status' => 'error',
            'message' => 'Delete failed',
            'error' => $q->error
        ]);
    }
}
