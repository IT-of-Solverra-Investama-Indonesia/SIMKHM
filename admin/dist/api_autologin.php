<?php
session_start();
$session_duration = 8 * 60 * 60;

require 'function.php';

// Set header untuk JSON response
header('Content-Type: application/json');

// Terima data dari POST request
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['username']) || !isset($input['password']) || !isset($input['shift']) || !isset($input['dokter_rawat'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Data tidak lengkap'
    ]);
    exit;
}

$username = sani($input['username']);
$password = sani($input['password']);
$shift = sani($input['shift']);
$dokter_rawat = sani($input['dokter_rawat']);

// Query ke database
$stmt = $koneksi->prepare("SELECT * FROM admin WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Cek username
if (mysqli_num_rows($result) === 1) {
    $row2 = mysqli_fetch_assoc($result);

    // Cek password
    if ($password == $row2['password']) {
        // Set session
        $_SESSION['admin'] = $row2;
        $_SESSION['login'] = true;
        $_SESSION['shift'] = $shift;
        $_SESSION['dokter_rawat'] = $dokter_rawat;
        $_SESSION['login_time'] = time();

        // Tentukan redirect URL berdasarkan level
        $redirectUrl = 'index.php';
        if ($row2['level'] == 'dokter') {
            $redirectUrl = 'index.php?halaman=daftarrmedis';
        } elseif ($row2['level'] == 'racik') {
            $redirectUrl = 'index.php?halaman=daftarrmedis&racik';
        } elseif ($row2['level'] == 'apoteker') {
            $redirectUrl = 'index.php?halaman=tambah_obatmasuk';
        } elseif ($row2['level'] == 'kasir') {
            $redirectUrl = 'index.php?halaman=daftarbayar';
        }

        echo json_encode([
            'success' => true,
            'message' => 'Auto-login berhasil',
            'redirect' => $redirectUrl,
            'level' => $row2['level']
        ]);
        exit;
    }
}

// Login gagal
echo json_encode([
    'success' => false,
    'message' => 'Username atau password salah'
]);
exit;
