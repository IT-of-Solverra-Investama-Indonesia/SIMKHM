<?php

require '../dist/function.php';
header('Content-Type: application/json');

if(isset($_GET['getAntrian'])){
    $carabayar = isset($_GET['carabayar']) ? sani($_GET['carabayar']) : '';
    $tanggal = isset($_GET['tanggal']) ? sani($_GET['tanggal']) : date('Y-m-d');

    if($carabayar == 'spesialis anak'){
        $code = 'sa';
    }else if($carabayar == 'spesialis penyakit dalam'){
        $code = 'spd';
    }else if($carabayar == 'gigi umum'){
        $code = 'gu';
    } else if ($carabayar == 'gigi bpjs') {
        $code = 'gb';
    }

    $getLastAntrianDay = $koneksi->query("SELECT  * FROM registrasi_rawat WHERE carabayar = '$carabayar' AND jadwal LIKE '%$tanggal%' ORDER BY idrawat DESC LIMIT 1")->fetch_assoc();

    if ($getLastAntrianDay && isset($getLastAntrianDay['antrian']) && !empty($getLastAntrianDay['antrian'])) {
        // Ambil nomor urut terakhir dari format sa001
        $lastNumber = (int)substr($getLastAntrianDay['antrian'], strlen($code));
        $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        $lastAntrian = $code . $nextNumber;
    } else {
        // Jika belum ada antrian, mulai dari 001
        $lastAntrian = $code . '001';
    }

    $response = [
        'status' => 'success',
        'message' => 'Antrian berhasil diambil',
        'data' => [
            'antrian' => $lastAntrian
        ]
    ];

    echo json_encode($response);
}

?>