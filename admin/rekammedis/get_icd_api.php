<?php
include '../dist/function.php'; // Pastikan ini adalah koneksi database Anda

if (isset($_POST['diagnosis'])) {
    $diagnosis = htmlspecialchars($_POST['diagnosis']);
    $search = isset($_POST['search']) ? htmlspecialchars($_POST['search']) : '';

    if ($diagnosis != 'Diagnosis Baru') {
        $query = "SELECT code as icd, name_en FROM icds_diagnosis JOIN icds ON icds.code = icds_diagnosis.icd WHERE diagnosis != '' AND diagnosis = '$diagnosis' GROUP BY icd ORDER BY name_en ASC";
    } else {
        $query = "SELECT code as icd, name_en FROM icds WHERE code LIKE '%$search%' OR name_en LIKE '%$search%' ORDER BY name_en ASC";
    }

    $result = $koneksimaster->query($query);

    $icds = [];
    while ($row = $result->fetch_assoc()) {
        $icds[] = $row;
    }

    echo json_encode($icds);
}
