<?php
    session_start();
    include "function.php";
    include '../admin/rawatjalan/api_token_wa.php';


    if (isset($_GET['getPesan'])) {
        header('Content-Type: application/json');

        $room_id = sani($_GET['getPesan']);
        $stmt = $koneksi->prepare("SELECT * FROM chat_konsultasi WHERE room_id = ? ORDER BY created_at ASC");
        $stmt->bind_param("s", $room_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $messages = [];

        while ($row = $result->fetch_assoc()) {
            foreach ($row as $key => $value) {
            $row[$key] = sani($value);
            }
            $messages[] = $row;
        }

        $stmt->close();

        echo json_encode($messages);
        exit();
    }

    if (!empty($_SESSION['admin']['idadmin']) || !empty($_SESSION['kosmetik']['idpasien'])) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $response = ['status' => 'error'];

            $room = sani($_POST['room']);
            $dari = sani($_POST['dari']);
            $type_chat = sani($_POST['type']);
            $dari_id = sani($_SESSION['admin']['idadmin'] ?? $_SESSION['kosmetik']['idpasien']);

            // Generate a unique ID for the message
            $id = sani(uniqid($room . '_'));

            // Check if a file is uploaded
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $fileInfo = pathinfo($_FILES['file']['name']);
            $extension = sani(strtolower($fileInfo['extension']));

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'heic'];

            if (in_array($extension, $allowedExtensions)) {
                $uploadDir = 'foto_chat/';
                $fileName = sani(uniqid() . '_' . basename($_FILES['file']['name']));
                $uploadFile = sani($uploadDir . $fileName);

                if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
                $fileUrl = sani($uploadFile);

                $sql = "INSERT INTO chat_konsultasi (id, room_id, dari, dari_id, type_chat, chat) VALUES ('$id', '$room', '$dari', '$dari_id', '$type_chat', '$fileUrl')";
                if ($koneksi->query($sql)) {
                    $response['status'] = 'success';
                } else {
                    $response['message'] = $koneksi->error;
                }
                } else {
                $response['message'] = 'Failed to move uploaded file.';
                }
            } else {
                $response['message'] = 'Invalid file type. Only JPG, JPEG, PNG, and HEIC files are allowed.';
            }
            } else {
            $message = sani($_POST['message']);
            $sql = "INSERT INTO chat_konsultasi (id, room_id, dari, dari_id, type_chat, chat) VALUES ('$id', '$room', '$dari', '$dari_id', '$type_chat', '$message')";
            if ($koneksi->query($sql)) {
                $response['status'] = 'success';
            } else {
                $response['message'] = $koneksi->error;
            }
            }

            if ($dari_id == sani($_SESSION['kosmetik']['idpasien'])) {
            $getChatPertama = $koneksi->query("SELECT COUNT(*) as jum FROM chat_konsultasi WHERE room_id = '$room' LIMIT 1")->fetch_assoc();
            if (sani($getChatPertama['jum']) <= 1) {
                $result = $koneksi->query("SELECT * FROM room_konsultasi WHERE id = '$room' LIMIT 1")->fetch_assoc();
                $dokter_konsul = $koneksi->query("SELECT * FROM dokter_konsul");

                while ($row = $dokter_konsul->fetch_assoc()) {
                if (!empty($row['nowa'])) {
                    $curl = curl_init();
                    $phone = sani($row['nowa']);
                    $message = urlencode("Ada konsulan online dok, silahkan klik link dibawah berikut: https://simkhm.id/wonorejo/admin/dist/index.php?halaman=konsultasi&room=" . sani($room));

                    curl_setopt($curl, CURLOPT_URL, "https://jogja.wablas.com/api/send-message?phone=$phone&message=$message&token=$token");
                    $result = curl_exec($curl);
                    curl_close($curl);
                }
                }
            }
            }

            $koneksi->close();
            echo json_encode($response);
            exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User not authenticated']);
        exit();
    }
?>
