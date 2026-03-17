<?php

/**
 * Contoh cara menampilkan diagnosis dan ICD yang tersimpan dengan format:
 * diagnosis1+diagnosis2+diagnosis3 menjadi list
 * 
 * Gunakan code ini di halaman tampilan rekam medis
 */

// Contoh data dari database
// $rm['diagnosis'] = "Hipertensi+Diabetes Mellitus+Dyslipidemia";
// $rm['icd'] = "I10+E11.9+E78.5";

// Function untuk menampilkan diagnosis sebagai list
function displayDiagnosisList($diagnosisString, $icdString)
{
    if (empty($diagnosisString)) {
        return '<p class="text-muted">Tidak ada diagnosis</p>';
    }

    // Split string berdasarkan separator '+'
    $diagnosisList = explode('+', $diagnosisString);
    $icdList = explode('+', $icdString);

    $html = '<ol class="mb-0">';

    foreach ($diagnosisList as $index => $diagnosis) {
        $icd = isset($icdList[$index]) ? trim($icdList[$index]) : '';
        $diagnosisTrimmed = trim($diagnosis);

        if (!empty($diagnosisTrimmed)) {
            $html .= '<li>';
            $html .= '<strong>' . htmlspecialchars($diagnosisTrimmed) . '</strong>';

            if (!empty($icd)) {
                $html .= ' <span class="badge bg-info">' . htmlspecialchars($icd) . '</span>';
            }

            $html .= '</li>';
        }
    }

    $html .= '</ol>';

    return $html;
}

// Function alternatif: menampilkan sebagai badges
function displayDiagnosisBadges($diagnosisString, $icdString)
{
    if (empty($diagnosisString)) {
        return '<span class="text-muted">Tidak ada diagnosis</span>';
    }

    $diagnosisList = explode('+', $diagnosisString);
    $icdList = explode('+', $icdString);

    $html = '';

    foreach ($diagnosisList as $index => $diagnosis) {
        $icd = isset($icdList[$index]) ? trim($icdList[$index]) : '';
        $diagnosisTrimmed = trim($diagnosis);

        if (!empty($diagnosisTrimmed)) {
            $html .= '<div class="mb-2">';
            $html .= '<span class="badge bg-primary">' . ($index + 1) . '</span> ';
            $html .= '<strong>' . htmlspecialchars($diagnosisTrimmed) . '</strong>';

            if (!empty($icd)) {
                $html .= ' <span class="badge bg-secondary">ICD: ' . htmlspecialchars($icd) . '</span>';
            }

            $html .= '</div>';
        }
    }

    return $html;
}

// Function alternatif: menampilkan sebagai table
function displayDiagnosisTable($diagnosisString, $icdString)
{
    if (empty($diagnosisString)) {
        return '<p class="text-muted">Tidak ada diagnosis</p>';
    }

    $diagnosisList = explode('+', $diagnosisString);
    $icdList = explode('+', $icdString);

    $html = '
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="60%">Diagnosis</th>
                <th width="35%">ICD 10</th>
            </tr>
        </thead>
        <tbody>';

    foreach ($diagnosisList as $index => $diagnosis) {
        $icd = isset($icdList[$index]) ? trim($icdList[$index]) : '-';
        $diagnosisTrimmed = trim($diagnosis);

        if (!empty($diagnosisTrimmed)) {
            $html .= '<tr>';
            $html .= '<td>' . ($index + 1) . '</td>';
            $html .= '<td>' . htmlspecialchars($diagnosisTrimmed) . '</td>';
            $html .= '<td>' . htmlspecialchars($icd) . '</td>';
            $html .= '</tr>';
        }
    }

    $html .= '</tbody></table>';

    return $html;
}

?>

<!-- CONTOH PENGGUNAAN DI HALAMAN TAMPIL -->
<!DOCTYPE html>
<html>

<head>
    <title>Contoh Tampilan Diagnosis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h5>Detail Rekam Medis - Assessment</h5>
            </div>
            <div class="card-body">
                <?php
                // Simulasi data dari database
                $rm = [
                    'diagnosis' => 'Hipertensi Grade 2+Diabetes Mellitus Type 2+Dyslipidemia',
                    'icd' => 'I10+E11.9+E78.5'
                ];
                ?>

                <h6>Metode 1: Tampilan List (Ordered List)</h6>
                <?php echo displayDiagnosisList($rm['diagnosis'], $rm['icd']); ?>

                <hr>

                <h6 class="mt-3">Metode 2: Tampilan Badges</h6>
                <?php echo displayDiagnosisBadges($rm['diagnosis'], $rm['icd']); ?>

                <hr>

                <h6 class="mt-3">Metode 3: Tampilan Table</h6>
                <?php echo displayDiagnosisTable($rm['diagnosis'], $rm['icd']); ?>

                <hr>

                <h6 class="mt-3">Data Mentah (di Database)</h6>
                <p><strong>Diagnosis:</strong> <?php echo htmlspecialchars($rm['diagnosis']); ?></p>
                <p><strong>ICD:</strong> <?php echo htmlspecialchars($rm['icd']); ?></p>
            </div>
        </div>
    </div>

</body>

</html>

<?php
/**
 * CARA MENGGUNAKAN DI FILE ANDA:
 * 
 * 1. Copy salah satu function di atas (displayDiagnosisList, displayDiagnosisBadges, atau displayDiagnosisTable)
 * 2. Paste di file PHP Anda
 * 3. Panggil function tersebut di tempat yang Anda ingin tampilkan diagnosis
 * 
 * Contoh:
 * <?php echo displayDiagnosisList($rm['diagnosis'], $rm['icd']); ?>
 * 
 * atau untuk tampilan table:
 * <?php echo displayDiagnosisTable($rm['diagnosis'], $rm['icd']); ?>
 */
?>