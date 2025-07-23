<?php
    header("Content-Type: application/json");
    include '../../admin/dist/function.php';
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    // Sanitization function
    // function sani($value) {
    //     global $koneksi;
    //     return mysqli_real_escape_string($koneksi, trim($value));
    // }

    $getToken = sani($_GET['token'] ?? $_POST['token']);
    $lengthToKeep = strlen($getToken) - 26;
    $token = sani(substr($getToken, 0, $lengthToKeep));

    // Prepare statement for pasien
    $stmt = $koneksi->prepare("SELECT * FROM pasien WHERE idpasien = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $pacient = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if(isset($_GET['todayBookingSchedule'])){
        $hari_ini = sani(date('Y-m-d'));

        // Prepare statement for jumlah_pasien_hari
        $stmt = $koneksi->prepare("SELECT jadwal, antrian FROM registrasi_rawat WHERE DATE_FORMAT(jadwal, '%Y-%m-%d') = ? and no_rm = ?");
        $no_rm = sani($pacient['no_rm']);
        $stmt->bind_param("ss", $hari_ini, $no_rm);
        $stmt->execute();
        $jumlah_pasien_hari = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!empty($jumlah_pasien_hari['jadwal']) or !empty($jumlah_pasien_hari['antrian'])) {
            // Prepare statement for result
            $stmt = $koneksi->prepare("SELECT `idrawat`, `nama_pasien`, `umur`, `jenis_kunjungan`, `perawatan`, `kamar`, `dokter_rawat`, `jadwal`, `status_antri`, `antrian`, `id_pasien`, `no_rm`, `carabayar`, `kasir`, `petugaspoli`, `perawat`, `shift`, `kode`, `status_sinc`, `keluhan`, `kategori`, `start`, `end` FROM `registrasi_rawat` WHERE DATE_FORMAT(jadwal, '%Y-%m-%d') = ? and no_rm = ? ORDER BY idrawat DESC LIMIT 1");
            $stmt->bind_param("ss", $hari_ini, $no_rm);
            $stmt->execute();
            $result = $stmt->get_result();

            $data = array();
            while ($hasil = $result->fetch_assoc()){
                $data[] = $hasil; 
            }
            $stmt->close();

            $response = array(
                "status" => "Successfully",
                "data" => $data
            );

        }else{
            $response = array(
                "status" => "Successfully",
                "data" => []
            );
        }
    }
    
    if(isset($_GET['numberOfArrivalHistory'])){
        $no_rm = sani($pacient['no_rm']);
        // Prepare statement for result
        $stmt = $koneksi->prepare("SELECT * FROM registrasi_rawat WHERE no_rm = ? AND (status_antri != 'Menunggu Panggilan' OR status_antri != 'Belum Datang' OR status_antri != 'Dipanggil')");
        $stmt->bind_param("s", $no_rm);
        $stmt->execute();
        $result = $stmt->get_result();

        $totalRegistrasion = $result->num_rows;
        $data = array();
        while ($hasil = $result->fetch_assoc()){
            $data[] = $hasil; 
        }
        $stmt->close();

        $response = array(
            "status" => "Successfully",
            "data" => $data,
            "total" => $totalRegistrasion
        );
    }

    echo json_encode($response);
?>