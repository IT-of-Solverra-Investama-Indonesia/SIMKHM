<?php
    session_start();
    include "function.php";
    include '../admin/rawatjalan/api_token_wa.php';


    if (isset($_GET['getPesan'])) {
        header('Content-Type: application/json');

        $result = $koneksi->query("SELECT * FROM chat_konsultasi WHERE room_id = '".htmlspecialchars($_GET['getPesan'])."' ORDER BY created_at ASC");
        $messages = [];

        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }

        echo json_encode($messages);
        exit();
    }

    if (!empty($_SESSION['admin']['idadmin']) || !empty($_SESSION['kosmetik']['idpasien'])) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $response = ['status' => 'error'];

            $room = htmlspecialchars($_POST['room']);
            $dari = htmlspecialchars($_POST['dari']);
            $type_chat = htmlspecialchars($_POST['type']);
            $dari_id = $_SESSION['admin']['idadmin'] ?? $_SESSION['kosmetik']['idpasien'];

            // Generate a unique ID for the message
            $id = uniqid($room . '_');

            // Check if a file is uploaded
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                // Mendapatkan ekstensi file yang di-upload
                $fileInfo = pathinfo($_FILES['file']['name']);
                $extension = strtolower($fileInfo['extension']);
            
                // Daftar ekstensi yang diizinkan
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'heic'];
            
                // Memastikan ekstensi file sesuai dengan yang diizinkan
                if (in_array($extension, $allowedExtensions)) {
                    $uploadDir = 'foto_chat/';
                    $fileName = uniqid() . '_' . basename($_FILES['file']['name']);
                    $uploadFile = $uploadDir . $fileName;
            
                    // Memindahkan file yang di-upload ke direktori tujuan
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
                        $fileUrl = $uploadFile;
            
                        // Memasukkan URL file ke dalam pesan chat
                        $stmt = $koneksi->prepare("INSERT INTO chat_konsultasi (id, room_id, dari, dari_id, type_chat, chat) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param("ssssss", $id, $room, $dari, $dari_id, $type_chat, $fileUrl);
                        if ($stmt->execute()) {
                            $response['status'] = 'success';
                        } else {
                            $response['message'] = $stmt->error;
                        }
                        $stmt->close();
                    } else {
                        $response['message'] = 'Failed to move uploaded file.';
                    }
                } else {
                    $response['message'] = 'Invalid file type. Only JPG, JPEG, PNG, and HEIC files are allowed.';
                }
            } else {
                // Menangani pesan teks
                $message = htmlspecialchars($_POST['message']);
                $stmt = $koneksi->prepare("INSERT INTO chat_konsultasi (id, room_id, dari, dari_id, type_chat, chat) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $id, $room, $dari, $dari_id, $type_chat, $message);
                if ($stmt->execute()) {
                    $response['status'] = 'success';
                } else {
                    $response['message'] = $stmt->error;
                }
                $stmt->close();
            }
                
            
            if($dari_id == $_SESSION['kosmetik']['idpasien']){
                $getChatPertama = $koneksi->query("SELECT COUNT(*) as jum FROM chat_konsultasi WHERE room_id = '$room' LIMIT 1")->fetch_assoc();
                if($getChatPertama['jum'] <= 1){
                    $result = $koneksi->query("SELECT * FROM room_konsultasi WHERE id = '$room' LIMIT 1")->fetch_assoc();
                    $dokter_konsul = $koneksi->query("SELECT * FROM dokter_konsul");
                    
                    $nowa_list = [];
                    
                    while ($row = $dokter_konsul->fetch_assoc()) {
                        if (!empty($row['nowa'])) {
                            $curl = curl_init();
                            $phone = $row['nowa'];
                            $message = urlencode("Ada konsulan online dok, silahkan klik link dibawah berikut: https://simkhm.id/wonorejo/admin/dist/index.php?halaman=konsultasi&room=".$room."");
        
                            curl_setopt($curl, CURLOPT_URL, "https://jogja.wablas.com/api/send-message?phone=$phone&message=$message&token=$token");
                            $result = curl_exec($curl);
                            curl_close($curl);
                        }
                    }
                }
            }
            
            // if ($result['dokter'] == '') {
            //     $data = [
            //         "data" => array_map(function($wa)use($room) {
            //             return [
            //                 "phone" => $wa,
            //                 "message" => "Ada konsulan online dok, silahkan klik link dibawah ini:\n\n" .
                            // "http://192.168.1.12/klinik/admin/dist/index.php?halaman=konsultasi&room=" .$room,
                //             "https://simkhm.id/wonorejo/admin/dist/index.php?halaman=konsultasi&room=" .$room,
                //         ];
                //     }, $nowa_list)
                // ];
            
            //     $curl = curl_init();
            //     curl_setopt($curl, CURLOPT_HTTPHEADER, [
            //         "Authorization: $token",
            //         "Content-Type: application/json"
            //     ]);
            //     curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            //     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            //     curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            //     curl_setopt($curl, CURLOPT_URL, "https://jogja.wablas.com/api/v2/send-message");
            //     curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            //     curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            
            //     $result = curl_exec($curl);
            //     curl_close($curl);
            //     print_r($result);
            // }
            
            $koneksi->close();
            echo json_encode($response);
            exit();


        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User not authenticated']);
        exit();
    }
?>
