<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

include '../dist/function.php';
session_start();

function addAntreanToDB()
{
    global $koneksi;

    // Validasi input
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        return ['status' => 'error', 'message' => 'ID tidak ditemukan'];
    }

    $id = $_POST['id'];

    try {
        // Cek apakah data registrasi ada
        $data = $koneksi->query("SELECT * FROM registrasi_rawat WHERE idrawat = '$id'")->fetch_assoc();

        if (!$data) {
            return ['status' => 'error', 'message' => 'Data registrasi tidak ditemukan'];
        }

        // Cek apakah session admin ada
        if (!isset($_SESSION['admin']['namalengkap'])) {
            return ['status' => 'error', 'message' => 'Session admin tidak valid'];
        }

        // Insert ke display_antrian dengan timestamp
        $currentTime = date('Y-m-d H:i:s');
        $result = $koneksi->query("INSERT INTO display_antrian (tipe, antrian, kode_antrian, petugas, status, created_at) VALUES ('{$data['carabayar']}', '{$data['antrian']}', '{$data['kode']}', '{$_SESSION['admin']['namalengkap']}', 'Perawat Poli', '$currentTime')");

        if ($result) {
            return [
                'status' => 'success',
                'message' => 'Antrian berhasil ditambahkan',
                'data' => [
                    'id' => $id,
                    'antrian' => $data['antrian'],
                    'kode' => $data['kode'],
                    'carabayar' => $data['carabayar']
                ]
            ];
        } else {
            return ['status' => 'error', 'message' => 'Gagal menambahkan antrian ke database'];
        }
    } catch (Exception $e) {
        return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
    }
}

function addAntreanDokterToDB()
{
    global $koneksi;

    // Validasi input
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        return ['status' => 'error', 'message' => 'ID tidak ditemukan'];
    }

    $id = $_POST['id'];

    try {
        // Cek apakah data registrasi ada
        $data = $koneksi->query("SELECT * FROM registrasi_rawat WHERE idrawat = '$id'")->fetch_assoc();

        if (!$data) {
            return ['status' => 'error', 'message' => 'Data registrasi tidak ditemukan'];
        }

        // Cek apakah session admin ada
        if (!isset($_SESSION['admin']['namalengkap'])) {
            return ['status' => 'error', 'message' => 'Session admin tidak valid'];
        }

        // Insert ke display_antrian dengan timestamp
        $currentTime = date('Y-m-d H:i:s');
        $result = $koneksi->query("INSERT INTO display_antrian (tipe, antrian, kode_antrian, petugas, status, created_at) VALUES ('{$data['carabayar']}', '{$data['antrian']}', '{$data['kode']}', '{$_SESSION['admin']['namalengkap']}', 'Dokter', '$currentTime')");

        if ($result) {
            return [
                'status' => 'success',
                'message' => 'Antrian berhasil ditambahkan',
                'data' => [
                    'id' => $id,
                    'antrian' => $data['antrian'],
                    'kode' => $data['kode'],
                    'carabayar' => $data['carabayar']
                ]
            ];
        } else {
            return ['status' => 'error', 'message' => 'Gagal menambahkan antrian ke database'];
        }
    } catch (Exception $e) {
        return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
    }
}

// Fungsi untuk mendapatkan antrian terakhir
function getAntrianTerakhir()
{
    global $koneksi;

    try {
        $result = $koneksi->query("SELECT * FROM display_antrian ORDER BY id DESC LIMIT 1");
        $data = $result->fetch_assoc();

        return [
            'status' => 'success',
            'data' => $data,
            'message' => 'Data berhasil diambil'
        ];
    } catch (Exception $e) {
        return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
    }
}

// Fungsi untuk mendapatkan antrian terbaru berdasarkan status
function getAntrianTerbaru()
{
    global $koneksi;

    try {
        // Ambil antrian terbaru yang belum di-sound
        $result = $koneksi->query("SELECT * FROM display_antrian WHERE sound_at IS NULL ORDER BY created_at DESC");
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return [
            'status' => 'success',
            'data' => $data,
            'message' => 'Data berhasil diambil'
        ];
    } catch (Exception $e) {
        return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
    }
}

// Fungsi untuk mendapatkan antrian aktif per poli
function getAntrianAktif()
{
    global $koneksi;

    try {
        // Ambil antrian terakhir untuk setiap poli
        $query = "
            SELECT 
                CASE 
                    WHEN tipe IN ('umum', 'bpjs', 'malam', 'kosmetik') THEN 'umum'
                    WHEN tipe IN ('gigi umum', 'gigi bpjs') THEN 'gigi'
                    WHEN tipe IN ('spesialis anak', 'spesialis penyakit dalam') THEN 'spesialis'
                    ELSE 'lain'
                END as poli_type,
                antrian,
                kode,
                tipe,
                status,
                created_at,
                id
            FROM display_antrian 
            ORDER BY created_at DESC
        ";

        $result = $koneksi->query($query);
        $allData = $result->fetch_all(MYSQLI_ASSOC);

        // Group berdasarkan poli dan status
        $antrianAktif = [
            'umum' => ['dokter' => null, 'perawat' => null],
            'gigi' => ['dokter' => null, 'perawat' => null],
            'spesialis' => ['dokter' => null, 'perawat' => null]
        ];

        foreach ($allData as $item) {
            $poli = $item['poli_type'];
            $status = strtolower($item['status']);

            if ($poli !== 'lain' && isset($antrianAktif[$poli])) {
                if ($status === 'dokter' && $antrianAktif[$poli]['dokter'] === null) {
                    $antrianAktif[$poli]['dokter'] = $item;
                } elseif ($status === 'perawat poli' && $antrianAktif[$poli]['perawat'] === null) {
                    $antrianAktif[$poli]['perawat'] = $item;
                }
            }
        }

        return [
            'status' => 'success',
            'data' => $antrianAktif,
            'message' => 'Data berhasil diambil'
        ];
    } catch (Exception $e) {
        return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
    }
}

// Fungsi untuk update sound_at setelah antrian diputar suaranya
function updateSoundAt()
{
    global $koneksi;

    $id = $_POST['id'] ?? null;

    if (!$id) {
        return ['status' => 'error', 'message' => 'ID tidak ditemukan'];
    }

    try {
        $currentTime = date('Y-m-d H:i:s');
        $result = $koneksi->query("UPDATE display_antrian SET sound_at = '$currentTime' WHERE id = '$id'");

        if ($result) {
            return [
                'status' => 'success',
                'message' => 'Sound timestamp berhasil diupdate'
            ];
        } else {
            return ['status' => 'error', 'message' => 'Gagal update sound timestamp'];
        }
    } catch (Exception $e) {
        return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
    }
}

// Keep Alive function untuk mencegah timeout
function keepAlive()
{
    return [
        'status' => 'success',
        'message' => 'Keep alive ping successful',
        'timestamp' => date('Y-m-d H:i:s'),
        'server_time' => time()
    ];
}

// Main API Handler
$fungsi = $_GET['fungsi'] ?? '';

$response = ['status' => 'error', 'message' => 'Fungsi tidak ditemukan'];

if ($fungsi && function_exists($fungsi)) {
    try {
        $result = call_user_func($fungsi);
        $response = $result;
    } catch (Throwable $e) {
        $response = ['status' => 'error', 'message' => 'Terjadi kesalahan saat eksekusi API.', 'details' => $e->getMessage()];
    }
} else if (empty($fungsi)) {
    $response = [
        'status' => 'error',
        'message' => 'Parameter fungsi harus diisi',
        'available_functions' => ['addAntreanToDB', 'addAntreanDokterToDB', 'getAntrianTerakhir', 'getAntrianTerbaru', 'getAntrianAktif', 'updateSoundAt', 'keepAlive']
    ];
}

// Return JSON response
echo json_encode($response);

/*
=== CONTOH PENGGUNAAN ===

1. Tambah antrian:
   URL: api_getAntrianTerakhir.php?fungsi=addAntreanToDB
   Method: POST
   Body: id=123

2. Get antrian terakhir:
   URL: api_getAntrianTerakhir.php?fungsi=getAntrianTerakhir
   Method: GET

=== CONTOH JAVASCRIPT ===

// Tambah antrian
fetch('../api/api_getAntrianTerakhir.php?fungsi=addAntreanToDB', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'id=' + encodeURIComponent(id)
})
.then(response => response.json())
.then(data => {
    console.log('Success:', data);
    if(data.status === 'success') {
        alert('Antrian berhasil dipanggil!');
    } else {
        alert('Error: ' + data.message);
    }
})
.catch((error) => {
    console.error('Error:', error);
    alert('Terjadi kesalahan saat memanggil antrian!');
});

*/
