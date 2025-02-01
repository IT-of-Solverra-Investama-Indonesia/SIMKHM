<?php
include '../dist/function.php'; // Pastikan ini adalah koneksi database Anda

if (isset($_POST['diagnosis'])) {
    $diagnosis = $_POST['diagnosis'];
    if($diagnosis != 'Diagnosis Baru'){
        $result = $koneksi->query("SELECT icd, name_en FROM rekam_medis JOIN icds ON icds.code = rekam_medis.icd WHERE diagnosis = '$diagnosis' GROUP BY icd ORDER BY id_rm DESC");
    }else{
        $result = $koneksi->query("SELECT icd, name_en FROM rekam_medis JOIN icds ON icds.code = rekam_medis.icd GROUP BY icd ORDER BY id_rm DESC ");
    }
    
    $icds = [];
    while ($row = $result->fetch_assoc()) {
        $icds[] = $row;
    }

    echo json_encode($icds);
}
?>