<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

include '../dist/function.php';
session_start();

// Fungsi untuk menghitung rata-rata obat per dokter
function getRataRataObat()
{
    global $koneksi;

    $startTime = microtime(true);

    try {
        // Cek koneksi database
        if (!$koneksi) {
            throw new Exception('Koneksi database tidak tersedia');
        }
        // $bulanSaatIni = date('Y-m');
        $bulanSaatIni = date('Y-m'); // Sesuai dengan kode asli
        $whereCaraBayar = "AND (carabayar IN ('bpjs', 'umum', 'malam', 'gigi umum', 'gigi bpjs', 'spesialis anak', 'spesialis penyakit dalam', 'kosmetik'))";
        $whereCaraBayarR = "AND (r.carabayar IN ('bpjs', 'umum', 'malam', 'gigi umum', 'gigi bpjs', 'spesialis anak', 'spesialis penyakit dalam', 'kosmetik'))";

        $dokter = $_SESSION['dokter_rawat'] ?? '';

        if (empty($dokter)) {
            return [
                'status' => 'error',
                'message' => 'Session dokter tidak ditemukan',
                'debug_session' => isset($_SESSION['dokter_rawat']) ? 'exists but empty' : 'not exists'
            ];
        }

        // Query data pasien per bulan & dokter
        $query = "
            SELECT DATE_FORMAT(jadwal, '%Y-%m') as bulan,
                dokter_rawat,
                COUNT(*) as jumlahpasien
            FROM registrasi_rawat 
            WHERE perawatan = 'Rawat Jalan' 
                $whereCaraBayar 
                AND status_antri != 'Belum Datang'
                AND DATE_FORMAT(jadwal, '%Y-%m') = '$bulanSaatIni'
                AND dokter_rawat = '$dokter'
                AND EXISTS (
                    SELECT 1 FROM obat_rm 
                    WHERE DATE_FORMAT(registrasi_rawat.jadwal, '%Y-%m-%d') = obat_rm.tgl_pasien 
                    AND registrasi_rawat.no_rm = obat_rm.idrm
                )
            GROUP BY DATE_FORMAT(jadwal, '%Y-%m'), dokter_rawat
            ORDER BY DATE_FORMAT(jadwal, '%Y-%m') DESC, dokter_rawat
        ";

        $getData = $koneksi->query($query);

        if (!$getData) {
            throw new Exception('Error query pasien: ' . $koneksi->error);
        }

        // Query semua data obat + harga sekaligus
        $allObat = [];
        $obatQuery = "
            SELECT DATE_FORMAT(o.tgl_pasien, '%Y-%m') AS bulan,
                r.dokter_rawat,
                o.kode_obat,
                o.jml_dokter,
                a.harga_beli
            FROM obat_rm o
            INNER JOIN registrasi_rawat r 
                ON r.no_rm = o.idrm 
                AND o.tgl_pasien = DATE_FORMAT(r.jadwal, '%Y-%m-%d')
            LEFT JOIN (
                SELECT id_obat, harga_beli
                FROM apotek a1
                WHERE idapotek = (
                    SELECT MAX(idapotek) 
                    FROM apotek a2 
                    WHERE a2.id_obat = a1.id_obat AND DATE_FORMAT(a2.tgl_beli, '%Y-%m') <= '$bulanSaatIni'
                )
            ) a ON a.id_obat = o.kode_obat
            WHERE r.perawatan = 'Rawat Jalan'
                $whereCaraBayarR
                AND r.status_antri != 'Belum Datang'
                AND r.dokter_rawat = '$dokter'
                AND o.rekam_medis_id IS NOT NULL
                AND DATE_FORMAT(r.jadwal, '%Y-%m') = '$bulanSaatIni'
        ";

        $resultObat = $koneksi->query($obatQuery);

        if (!$resultObat) {
            throw new Exception('Error query obat: ' . $koneksi->error);
        }

        // Kelompokkan obat per bulan + dokter
        while ($row = $resultObat->fetch_assoc()) {
            $key = $row['bulan'] . '|' . $row['dokter_rawat'];
            if (!isset($allObat[$key])) {
                $allObat[$key] = [];
            }
            $allObat[$key][] = $row;
        }

        $hasil = [];

        // Hitung rata-rata per pasien
        while ($data = $getData->fetch_assoc()) {
            $bulan = $data['bulan'] == date('Y-m') ? date('Y-m') : $data['bulan'];
            $dokter = $data['dokter_rawat'];

            $key = $bulan . '|' . $dokter;
            $total = 0;

            if (isset($allObat[$key])) {
                foreach ($allObat[$key] as $obat) {
                    $jumlah = is_numeric($obat['jml_dokter']) ? $obat['jml_dokter'] : 0;
                    $harga = is_numeric($obat['harga_beli']) ? $obat['harga_beli'] : 0;
                    $total += $jumlah * $harga;
                }
            }

            $rataRata = $data['jumlahpasien'] > 0 ? $total / $data['jumlahpasien'] : 0;

            $hasil[] = [
                'bulan' => $bulan,
                'dokter' => $dokter,
                'total_obat' => $total,
                'jumlah_pasien' => $data['jumlahpasien'],
                'rata_rata' => $rataRata,
                'rata_rata_formatted' => number_format($rataRata, 0, 0, '.')
            ];
        }

        $executionTime = round((microtime(true) - $startTime) * 1000, 2);

        return [
            'status' => 'success',
            'data' => $hasil,
            'dokter' => $dokter,
            'bulan' => $bulanSaatIni,
            'total_records' => count($hasil),
            'execution_time' => $executionTime . 'ms',
            'timestamp' => date('Y-m-d H:i:s')
        ];
    } catch (Exception $e) {
        $executionTime = round((microtime(true) - $startTime) * 1000, 2);

        return [
            'status' => 'error',
            'message' => $e->getMessage(),
            'execution_time' => $executionTime . 'ms',
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
}

// Handle request
$fungsi = $_GET['fungsi'] ?? '';

switch ($fungsi) {
    case 'getRataRataObat':
        echo json_encode(getRataRataObat());
        break;

    default:
        echo json_encode([
            'status' => 'error',
            'message' => 'Fungsi tidak ditemukan',
            'available_functions' => ['getRataRataObat']
        ]);
        break;
}
