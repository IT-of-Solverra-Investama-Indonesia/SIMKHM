<?php
include '../dist/function.php';

$getRekamMedis = $koneksi->query("SELECT * FROM rekam_medis GROUP BY diagnosis, icd ORDER BY diagnosis ASC");
foreach ($getRekamMedis as $rm) {
    echo "INSERT INTO icds_diagnosis (diagnosis, icd, unit, petugas) VALUES ('" . $rm['diagnosis'] . "', '" . $rm['icd'] . "', 'KHM 1', 'IT Solverra');<br>";
}
