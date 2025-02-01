<?php
    header("Content-Type: application/json");
    include '../../admin/dist/function.php';
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    $getToken = $_GET['token'] ?? $_POST['token'];
    $lengthToKeep = strlen($getToken) - 26;
    $token = substr($getToken, 0, $lengthToKeep);

    $pacient = $koneksi->query("SELECT * FROM pasien WHERE idpasien = '$token'")->fetch_assoc();

    

    if(isset($_GET['todayBookingSchedule'])){
        $hari_ini = date('Y-m-d');

        $jumlah_pasien_hari = $koneksi->query("SELECT jadwal, antrian FROM registrasi_rawat WHERE DATE_FORMAT(jadwal, '%Y-%m-%d') = '$hari_ini' and no_rm = '$pacient[no_rm]'")->fetch_assoc();

        if (!empty($jumlah_pasien_hari['jadwal']) or !empty($jumlah_pasien_hari['antrian'])) {
            $result=$koneksi->query("SELECT `idrawat`, `nama_pasien`, `umur`, `jenis_kunjungan`, `perawatan`, `kamar`, `dokter_rawat`, `jadwal`, `status_antri`, `antrian`, `id_pasien`, `no_rm`, `carabayar`, `kasir`, `petugaspoli`, `perawat`, `shift`, `kode`, `status_sinc`, `keluhan`, `kategori`, `start`, `end` FROM `registrasi_rawat` FROM pemesanan WHERE DATE_FORMAT(jadwal, '%Y-%m-%d') = '$hari_ini' and no_rm = '$pacient[no_rm]' ORDER BY idrawat DESC LIMIT 1");

            $data = array();
            while ($hasil = $result->fetch_assoc()){
                $data[] = $hasil; 
            }

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
        $result = $koneksi->query("SELECT * FROM registrasi_rawat WHERE no_rm = '$pacient[no_rm]' AND (status_antri != 'Menunggu Panggilan' OR status_antri != 'Belum Datang' OR status_antri != 'Dipanggil')");

        $totalRegistrasion = $result->num_rows;
        $data = array();
        while ($hasil = $result->fetch_assoc()){
            $data[] = $hasil; 
        }

        $response = array(
            "status" => "Successfully",
            "data" => $data,
            "total" => $totalRegistrasion
        );
    }

    echo json_encode($response);
?>