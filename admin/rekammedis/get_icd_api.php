<?php
header('Content-Type: application/json');
require_once '../dist/function.php'; // Pastikan ini menginisialisasi $koneksimaster

// Validasi input
$diagnosis = isset($_POST['diagnosis']) ? trim($_POST['diagnosis']) : '';
$search = isset($_POST['search']) ? trim($_POST['search']) : '';

// Validasi minimal
if (empty($diagnosis)) {
    echo json_encode(['error' => 'Parameter diagnosis diperlukan']);
    exit;
}

try {
    // Gunakan prepared statements untuk keamanan
    if ($diagnosis !== 'Diagnosis Baru') {
        // Query untuk diagnosis spesifik
        $query = "SELECT icds.code as icd, icds.name_en 
                 FROM icds_diagnosis 
                 JOIN icds ON icds.code = icds_diagnosis.icd 
                 WHERE diagnosis = ? 
                 GROUP BY icds.code 
                 ORDER BY icds.name_en ASC";
        $stmt = $koneksimaster->prepare($query);
        $stmt->bind_param('s', $diagnosis);
    } else {
        // Query untuk pencarian bebas (Diagnosis Baru)
        $searchTerm = "%$search%";
        $query = "SELECT code as icd, name_en 
                 FROM icds 
                 WHERE code LIKE ? OR name_en LIKE ? 
                 ORDER BY name_en ASC 
                 LIMIT 50"; // Batasi hasil untuk performa
        $stmt = $koneksimaster->prepare($query);
        $stmt->bind_param('ss', $searchTerm, $searchTerm);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $icds = [];
    while ($row = $result->fetch_assoc()) {
        $icds[] = [
            'icd' => $row['icd'],
            'name_en' => $row['name_en']
        ];
    }

    echo json_encode($icds);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Terjadi kesalahan server: ' . $e->getMessage()]);
}
