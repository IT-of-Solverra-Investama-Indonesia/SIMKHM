<?php
header('Content-Type: application/json');
include '../dist/function.php';
// if (!isset($_SESSION['admin'])) {
//     http_response_code(403);
//     echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
//     exit;
// }
function getLastRM1()
{
    global $koneksi;
    $getLastRm = $koneksi->query("SELECT MAX(no_rm) AS max_no_rm FROM pasien WHERE no_rm LIKE '" . date('y') . "____' AND LENGTH(no_rm) = 6 AND no_rm REGEXP '^[0-9]+$';")->fetch_assoc();

    if ($getLastRm['max_no_rm'] == null) {
        include "../dist/baseUrlAPI.php";
        $api_url = $baseUrlLama . "api_personal/api_rekamMedis.php?newRekamMedis";

        // Inisialisasi cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);

        // Eksekusi request
        $response = curl_exec($ch);

        // Cek error
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        } else {
            $noRM = $response;
            // echo "Nomor Rekam Medis berikutnya: " . $noRM;
        }
        curl_close($ch);
    } else {
        $noRM = $getLastRm['max_no_rm'] + 1;
    }
    return ['status' => 'success', 'no_rm' => $noRM];
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
