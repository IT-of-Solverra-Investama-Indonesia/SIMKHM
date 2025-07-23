<?php 
    header("Content-Type: application/json");
    include '../../admin/dist/function.php';
    // include 'function.php';

    // Pastikan fungsi sani() sudah tersedia di function.php
    $getToken = sani($_GET['token'] ?? $_POST['token']);
    $lengthToKeep = strlen($getToken) - 26;
    $token = sani(substr($getToken, 0, $lengthToKeep));

    if (isset($_GET['allHistoryUser'])) {
        $user_id = sani($token);
        $query = "SELECT * FROM pemesanan WHERE user_id = '$user_id' GROUP BY code_nota ORDER BY created_at DESC";
        $result = $koneksi->query($query);

        if ($result->num_rows > 0) {
            $data = array();
            while ($hasil = $result->fetch_assoc()) {
                $data[] = $hasil;
            }
            $response = array(
                "status" => "Successfully",
                "data" => $data
            );
        } else {
            $response = array(
                "status" => "Gagal, hehehe",
                "data" => "Data Kosong"
            );
        }
    }

    if (isset($_GET['allHistoruUserProduk'])) {
        $user_id = sani($token);
        $code_nota = sani($_GET['code_nota']);
        $query = "SELECT * FROM pemesanan WHERE user_id = '$user_id' AND code_nota = '$code_nota'";
        $result = $koneksi->query($query);

        if ($result->num_rows > 0) {
            $data = array();
            while ($hasil = $result->fetch_assoc()) {
                $data[] = $hasil;
            }
            $response = array(
                "status" => "Successfully",
                "data" => $data
            );
        } else {
            $response = array(
                "status" => "Gagal, hehehe",
                "data" => "Data Kosong"
            );
        }
    }

    if (isset($_POST['uploadBuktiPembayaran'])) {
        $target_dir = "bukti_pembayaran/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $fileName = uniqid() . sani($_FILES['foto_bukti']['name']);
        move_uploaded_file(sani($_FILES["foto_bukti"]["tmp_name"]), $target_dir . $fileName);

        $code_nota = sani($_POST['code_nota']);
        $update_query = "UPDATE pemesanan SET bukti_pembayaran ='$fileName',status='Diproses' where code_nota = '$code_nota'";
        $koneksi->query($update_query);

        $select_query = "SELECT * FROM pemesanan WHERE code_nota = '$code_nota'";
        $result = $koneksi->query($select_query);
        $data = array();
        while ($hasil = $result->fetch_assoc()) {
            $data[] = $hasil;
        }
        $response = array(
            "status" => "Successfully",
            "data" => $data
        );
    }

    echo json_encode($response);
?>