<?php

    header("Content-Type: application/json");
    include '../../admin/dist/function.php';
    // include 'function.php';

    $getToken = $_GET['token'] ?? $_POST['token'];
    $lengthToKeep = strlen($getToken) - 26;
    $token = substr($getToken, 0, $lengthToKeep);

    if($_GET['addRoom']){
        $id =  'id'.date('ymdhis').$token;
        $pasien_id = $token;
        $getRegist = $koneksi->query("SELECT * FROM pasien_kosmetik WHERE idpasien = '$token'")->fetch_assoc();

        if($getRegist['jenis_kelamin'] == '' OR $getRegist['no_identitas'] == ''){
            $data = 'Silahlan Melengkapi Data Diri (Registrasi 2)';
            $status = 'Unsuccessfully';
        }else{
            $koneksi->query("INSERT INTO room_konsultasi (id, pasien_id, dokter, admin_id) VALUES ('$id', '$pasien_id', '','')");
            $result = $koneksi->query("SELECT * FROM rom_konsultasi WHERE id='$id'");
            $status = 'Successfully';
            $data = array();
        }

        while ($hasil = $result->fetch_assoc()){
            $data[] = $hasil; 
        }
        $response = array(
            "status" => $status,
            "message" => $data
        );
    }

    echo json_encode($response);

?>