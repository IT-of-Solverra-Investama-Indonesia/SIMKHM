<?php

require '../dist/function.php';
header('Content-Type: application/json');

if(isset($_GET['getAntrian'])){
    $carabayar = isset($_GET['carabayar']) ? sani($_GET['carabayar']) : '';
    $tanggal = isset($_GET['tanggal']) ? sani($_GET['tanggal']) : date('Y-m-d');
    $shift = isset($_GET['shift']) ? sani($_GET['shift']) : date('Y-m-d');

    $shiftCode = !empty($shift) ? strtolower(substr($shift, 0, 1)) : '';

    if($carabayar == 'spesialis anak'){
        $code = 'sa';
    }else if($carabayar == 'spesialis penyakit dalam'){
        $code = 'spd';
    }else if($carabayar == 'gigi umum'){
        $code = 'gu' . $shiftCode;
    } else if ($carabayar == 'gigi bpjs') {
        $code = 'gb' . $shiftCode;
    }

    // Khusus untuk gigi (umum dan bpjs), cek antrian terakhir dari kedua jenis
    if ($carabayar == 'gigi umum' || $carabayar == 'gigi bpjs') {
        // Cari antrian terakhir dari gigi umum ATAU gigi bpjs pada tanggal dan shift yang sama
        if(!isset($_GET['jenis'])){
            $getLastAntrianDay = $koneksi->query("
                SELECT antrian, jadwal FROM registrasi_rawat 
                    WHERE (carabayar = 'gigi umum' OR carabayar = 'gigi bpjs')
                    AND DATE(jadwal) = '$tanggal' 
                    AND antrian LIKE '%$shiftCode%'
                UNION ALL
                SELECT antrian, jadwal FROM registrasi_booking 
                    WHERE (carabayar = 'gigi umum' OR carabayar = 'gigi bpjs')
                    AND DATE(jadwal) = '$tanggal' 
                    AND antrian LIKE '%$shiftCode%'
                ORDER BY antrian DESC 
                LIMIT 1
            ")->fetch_assoc();
            
        }elseif(isset($_GET['jenis']) ){
            // Untuk booking, gunakan tabel registrasi_booking
            $getLastAntrianDay = $koneksi->query("
                SELECT antrian, jadwal FROM registrasi_rawat 
                    WHERE (carabayar = 'gigi umum' OR carabayar = 'gigi bpjs')
                    AND DATE(jadwal) = '$tanggal'
                UNION ALL
                SELECT antrian, jadwal FROM registrasi_booking 
                    WHERE (carabayar = 'gigi umum' OR carabayar = 'gigi bpjs')
                    AND DATE(jadwal) = '$tanggal' 
                ORDER BY antrian DESC 
                LIMIT 1
            ")->fetch_assoc();

        }

        if ($getLastAntrianDay && isset($getLastAntrianDay['antrian']) && !empty($getLastAntrianDay['antrian'])) {
            // Ambil nomor urut terakhir dari antrian (misal: gb001 atau gu001)
            // Ekstrak hanya angkanya (3 digit terakhir)
            $lastNumber = (int)substr($getLastAntrianDay['antrian'], -3);
            $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            $lastAntrian = $code . $nextNumber;
        } else {
            // Jika belum ada antrian gigi sama sekali di shift ini, mulai dari 001
            $lastAntrian = $code . '001';
        }
    } else {
        // Untuk poli selain gigi, tetap seperti biasa
        $getLastAntrianDay = $koneksi->query(
            "
            SELECT antrian, jadwal FROM registrasi_rawat
            WHERE carabayar = '$carabayar' AND DATE(jadwal) = '$tanggal' 
            UNION ALL
            SELECT antrian, jadwal FROM registrasi_booking
            WHERE carabayar = '$carabayar' AND DATE(jadwal) = '$tanggal'
            ORDER BY antrian DESC LIMIT 1"
        )->fetch_assoc();

        if ($getLastAntrianDay && isset($getLastAntrianDay['antrian']) && !empty($getLastAntrianDay['antrian'])) {
            // Ambil nomor urut terakhir dari format sa001
            $lastNumber = (int)substr($getLastAntrianDay['antrian'], strlen($code));
            $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            $lastAntrian = $code . $nextNumber;
        } else {
            // Jika belum ada antrian, mulai dari 001
            $lastAntrian = $code . '001';
        }
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