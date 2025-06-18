<?php
// Panggil API
$url = $baseUrlLama . "api_personal/api_dashboard_omset.php?OmsetKHM";
$response = file_get_contents($url);
$data = json_decode($response, true);
?>
<div class="card shadow p-2">
    <div class="table-responsive">
        <table class="table table-striped table-hover" style="font-size: 12px;">
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th>Hari</th>
                    <th>Omset</th>
                    <th>Per Hari</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($data['status'] == "Successfully") {
                    foreach ($data['data'] as $row) {
                        $omset = (int) $row['omset']; // Ubah omset ke integer
                        $harii = (int) $row['harii']; // Ubah hari ke integer
                        $perhari = $harii > 0 ? $omset / $harii : 0; // Hindari pembagian nol

                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['bulan']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['harii']) . "</td>";
                        echo "<td>Rp " . number_format($omset, 0, ',', '.') . "</td>";
                        echo "<td>Rp " . number_format($perhari, 0, ',', '.') . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Gagal mengambil data</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>