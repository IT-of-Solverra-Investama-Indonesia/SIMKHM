<?php
    session_start();
    include "function.php";

    if (isset($_GET['getPesan'])) {
        header('Content-Type: application/json');

        $result = $koneksi->query("SELECT * FROM chat_konsultasi WHERE room_id = '$_GET[getPesan]' ORDER BY created_at ASC");
        $messages = [];

        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }

        echo json_encode($messages);
        exit();
    }

    if (!empty($_SESSION['admin']['idadmin']) || !empty($_SESSION['pasien']['idpasien'])) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $response = ['status' => 'error'];

            $room = $_POST['room'];
            $dari = $_POST['dari'];
            $type_chat = $_POST['type'];
            $dari_id = $_SESSION['admin']['idadmin'] ?? $_SESSION['pasien']['idpasien'];

            // Generate a unique ID for the message
            $id = uniqid($room . '_');

            // Check if a file is uploaded
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'foto_chat/';
                $fileName = uniqid() . '_' . basename($_FILES['file']['name']);
                $uploadFile = $uploadDir . $fileName;

                // Move the uploaded file to the target directory
                if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
                    $fileUrl = $uploadFile;

                    // Insert the file URL into the chat message
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
                // Handle text message
                $message = $_POST['message'];
                $stmt = $koneksi->prepare("INSERT INTO chat_konsultasi (id, room_id, dari, dari_id, type_chat, chat) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $id, $room, $dari, $dari_id, $type_chat, $message);
                if ($stmt->execute()) {
                    $response['status'] = 'success';
                } else {
                    $response['message'] = $stmt->error;
                }
                $stmt->close();
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
