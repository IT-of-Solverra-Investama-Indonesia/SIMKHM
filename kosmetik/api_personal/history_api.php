<?php 
    header("Content-Type: application/json");
    include '../../admin/dist/function.php';
    // include 'function.php';

    $getToken = $_GET['token'] ?? $_POST['token'];
    $lengthToKeep = strlen($getToken) - 26;
    $token = substr($getToken, 0, $lengthToKeep);

    if(isset($_GET['allHistoryUser'])){
        $result = $koneksi->query("SELECT * FROM pemesanan WHERE user_id = '$token' GROUP BY code_nota ORDER BY created_at DESC");

        if($result -> num_rows > 0){
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
                "status" => "Gagal, hehehe",
                "data" => "Data Kosong"
            );
        }
    }

    if(isset($_GET['allHistoruUserProduk'])){
        $result = $koneksi->query("SELECT * FROM pemesanan WHERE user_id = '$token' AND code_nota = '$_GET[code_nota]'");

        if($result -> num_rows > 0){
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
                "status" => "Gagal, hehehe",
                "data" => "Data Kosong"
            );
        }
    }

    if(isset($_POST['uploadBuktiPembayaran'])){
        $target_dir = "bukti_pembayaran/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $fileName = uniqid() . $_FILES['foto_bukti']['name'];
        move_uploaded_file($_FILES["foto_bukti"]["tmp_name"], $target_dir . $fileName);

        $koneksi->query("UPDATE pemesanan SET bukti_pembayaran ='$fileName',status='Diproses' where code_nota = $_POST[code_nota]");

        $result=$koneksi->query("SELECT * FROM pemesanan WHERE code_nota = '$_POST[code_nota]'");
        $data = array();
        while ($hasil = $result->fetch_assoc()){
            $data[] = $hasil; 
        }
        $response = array(
            "status" => "Successfully",
            "data" => $data
        );
    }

    echo json_encode($response);
?>