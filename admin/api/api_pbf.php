<?php
header('Content-Type: application/json');
include '../dist/function.php';


function getObat()
{
    global $koneksimaster;
    $obat_master = $_REQUEST['obat_master'] ?? '';
    $query = "SELECT * FROM master_obat WHERE obat_master = '$obat_master' LIMIT 1";
    $result = $koneksimaster->query($query);
    if ($result && $result->num_rows > 0) {
        $data = $result->fetch_assoc();
        return ['status' => 'success', 'data' => $data];
    } else {
        return ['status' => 'error', 'message' => 'Obat tidak ditemukan'];
    }
}


function editStatusObat()
{
    global $koneksimaster;
    $kode_obat = $_REQUEST['kode_obat'] ?? '';
    $jenis = $_REQUEST['jenis'] ?? '';

    $allowed_jenis = ['aktif_poli', 'aktif_ranap', 'aktif_umum'];
    if (!in_array($jenis, $allowed_jenis, true)) {
        return ['status' => 'error', 'message' => 'Jenis tidak valid'];
    }
    $status = $_REQUEST['status'] ?? '';

    if (empty($kode_obat) || empty($jenis) || empty($status)) {
        return ['status' => 'error', 'message' => 'Parameter tidak lengkap'];
    } else {
        $query = "UPDATE master_obat SET $jenis = '$status' WHERE kode_obat = '$kode_obat' LIMIT 1";
        if ($koneksimaster->query($query) === TRUE) {
            return ['status' => 'success', 'message' => 'Status berhasil diperbarui'];
        } else {
            return ['status' => 'error', 'message' => 'Gagal memperbarui status', 'details' => $koneksimaster->error];
        }
    }
}

function hapusObat()
{
    global $koneksimaster;
    $kode_obat = $_REQUEST['kode_obat'] ?? '';

    if (empty($kode_obat)) {
        return ['status' => 'error', 'message' => 'Kode obat tidak boleh kosong'];
    } else {
        $query = "DELETE FROM master_obat WHERE kode_obat = '$kode_obat' LIMIT 1";
        if ($koneksimaster->query($query) === TRUE) {
            return ['status' => 'success', 'message' => 'Obat berhasil dihapus'];
        } else {
            return ['status' => 'error', 'message' => 'Gagal menghapus obat', 'details' => $koneksimaster->error];
        }
    }
}

function insertObat()
{
    global $koneksimaster;
    $obat_master = $_REQUEST['obat_master'] ?? '';
    $kode_obat = $_REQUEST['kode_obat'] ?? '';
    $aktif_poli = $_REQUEST['aktif_poli'] ?? '';
    $aktif_ranap = $_REQUEST['aktif_ranap'] ?? '';
    $aktif_umum = $_REQUEST['aktif_umum'] ?? '';

    if (empty($obat_master) || empty($aktif_poli) || empty($aktif_ranap) || empty($aktif_umum)) {
        return ['status' => 'error', 'message' => 'Parameter tidak lengkap'];
    } else {
        $query = "INSERT INTO master_obat (obat_master, kode_obat, margin_inap, margin_jual, margin_resep, pbf_master, pbf_master1, pbf_master2, pbf_master3, pbf_master4, aktif_poli, aktif_ranap, aktif_umum ) VALUES ('$obat_master', '$kode_obat', '100', 
            '100', '100', 'PBFHMI', '', '', '', '','$aktif_poli','$aktif_ranap','$aktif_umum')";
        if ($koneksimaster->query($query) === TRUE) {
            return ['status' => 'success', 'message' => 'Obat berhasil ditambahkan'];
        } else {
            return ['status' => 'error', 'message' => 'Gagal menambahkan obat', 'details' => $koneksimaster->error];
        }
    }
}

function updateObat()
{
    global $koneksimaster;
    $obat_master = $_REQUEST['obat_master'] ?? '';
    $kode_obat = $_REQUEST['kode_obat'] ?? '';

    if (empty($obat_master) || empty($kode_obat)) {
        return ['status' => 'error', 'message' => 'Parameter tidak lengkap'];
    } else {
        $cekQuery = "SELECT 1 FROM master_obat WHERE kode_obat = '$kode_obat' LIMIT 1";
        $cekResult = $koneksimaster->query($cekQuery);
        if (!$cekResult || $cekResult->num_rows === 0) {
            return ['status' => 'error', 'message' => 'Obat master lama tidak ditemukan'];
        }

        $query = "UPDATE master_obat SET obat_master = '$obat_master' WHERE kode_obat = '$kode_obat' LIMIT 1";
        if ($koneksimaster->query($query) === TRUE) {
            return ['status' => 'success', 'message' => 'Obat berhasil diperbarui'];
        } else {
            return ['status' => 'error', 'message' => 'Gagal memperbarui obat', 'details' => $koneksimaster->error];
        }
    }
}

$fungsi = $_GET['fungsi'] ?? '';

$response = ['status' => 'error', 'message' => 'Fungsi tidak ditemukan'];

if ($fungsi && function_exists($fungsi)) {
    try {
        $result = call_user_func($fungsi);
        $response = $result;
    } catch (Throwable $e) {
        $response = ['status' => 'error', 'message' => 'Terjadi kesalahan saat eksekusi API.', 'details' => $e->getMessage()];
    }
}

echo json_encode($response);
