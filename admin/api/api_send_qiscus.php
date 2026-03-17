<?php
error_reporting(0);
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Request Method']);
    exit;
}

// Ambil data dari POST (AJAX)
$hp = $_POST['hp'] ?? '';
$nama = $_POST['nama'] ?? '';
$pesan_promo = $_POST['pesan'] ?? '';

// Validasi sederhana
if (empty($hp) || empty($pesan_promo)) {
    echo json_encode(['status' => 'error', 'message' => 'Nomor HP dan Pesan tidak boleh kosong.']);
    exit;
}

// ---------------------------------------------------------
// KONFIGURASI QISCUS & TEMPLATE MARKETING
// ---------------------------------------------------------
$qiscus_app_id      = "mmgpu-ibeqprelthdmxcb";
$qiscus_secret_key  = "1ac1eb8eb81ee5adda8fae7233733b4c";
$qiscus_channel_id  = "8307";
$template_namespace = "fd4d98c9_14c0_4dc8_ba92_8dcdb8f73883";
$template_name      = "diagnose_template";

// Susun Payload
$payload = [
    "to" => $hp,
    "type" => "template",
    "template" => [
        "namespace" => $template_namespace,
        "name" => $template_name,
        "language" => [
            "policy" => "deterministic",
            "code" => "id"
        ],
        "components" => [
            [
                "type" => "body",
                "parameters" => [
                    [
                        "type" => "text",
                        "text" => $pesan_promo // Ini mengisi variabel {{1}}
                    ]
                ]
            ]
        ]
    ]
];

// Eksekusi cURL
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => "https://omnichannel.qiscus.com/whatsapp/v1/{$qiscus_app_id}/{$qiscus_channel_id}/messages",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_HTTPHEADER => array(
        "Qiscus-App-Id: " . $qiscus_app_id,
        "Qiscus-Secret-Key: " . $qiscus_secret_key,
        "Content-Type: application/json"
    ),
));

$response = curl_exec($curl);
$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
$err = curl_error($curl);
curl_close($curl);

// Evaluasi Hasil cURL
if ($err) {
    echo json_encode(['status' => 'error', 'message' => "cURL Error: $err"]);
} else {
    $resp_data = json_decode($response, true);
    
    // Qiscus mereturn HTTP 200 jika payload valid dan diterima API WA
    if ($http_code == 200 && isset($resp_data['messages'])) {
        echo json_encode([
            'status' => 'success', 
            'nama' => $nama,
            'response' => $resp_data
        ]);
    } else {
        // Kirim detail error dari Meta/Qiscus agar bisa dibaca di konsol
        echo json_encode([
            'status' => 'error', 
            'message' => 'Gagal mengirim. Response dari Meta: ' . ($resp_data['error']['message'] ?? 'Unknown Error'),
            'raw' => $resp_data
        ]);
    }
}
?>