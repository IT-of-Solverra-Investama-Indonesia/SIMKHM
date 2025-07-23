<?php

    header("Content-Type: application/json");
    include '../../admin/dist/function.php';
    // include 'function.php';

    $getToken = sani($_GET['token'] ?? $_POST['token']);
    $lengthToKeep = strlen($getToken) - 26;
    $token = sani(substr($getToken, 0, $lengthToKeep));

    if (isset($_GET['addRoom']) && sani($_GET['addRoom'])) {
        $id = sani('id' . date('ymdhis') . $token);
        $pasien_id = sani($token);

        $stmt = $koneksi->prepare("SELECT * FROM pasien_kosmetik WHERE idpasien = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $getRegist = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (sani($getRegist['jenis_kelamin']) == '' || sani($getRegist['no_identitas']) == '') {
            $data = sani('Silahlan Melengkapi Data Diri (Registrasi 2)');
            $status = sani('Unsuccessfully');
        } else {
            $stmt = $koneksi->prepare("INSERT INTO room_konsultasi (id, pasien_id, dokter, admin_id) VALUES (?, ?, '', '')");
            $stmt->bind_param("ss", $id, $pasien_id);
            $stmt->execute();
            $stmt->close();

            $stmt = $koneksi->prepare("SELECT * FROM room_konsultasi WHERE id = ?");
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            $status = sani('Successfully');
            $data = array();

            while ($hasil = $result->fetch_assoc()) {
                $data[] = array_map('sani', $hasil);
            }
            $stmt->close();
        }

        $response = array(
            "status" => $status,
            "message" => $data
        );
    }

    echo json_encode($response);

?>