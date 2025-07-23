<?php
    session_start();
    include "function.php";

    if (isset($_GET['getPesan'])) {
        header('Content-Type: application/json');

        $room_id = sani($_GET['getPesan']);
        $stmt = $koneksi->prepare("SELECT * FROM chat_konsultasi WHERE room_id = ? ORDER BY created_at ASC");
        $stmt->bind_param("s", $room_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $messages = [];

        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }

        echo json_encode($messages);
        $stmt->close();
        exit();
    }

    if (!empty($_SESSION['admin']['idadmin']) || !empty($_SESSION['pasien']['idpasien'])) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $response = ['status' => 'error'];

            $room = sani($_POST['room']);
            $dari = sani($_POST['dari']);
            $type_chat = sani($_POST['type']);
            $dari_id = sani($_SESSION['admin']['idadmin'] ?? $_SESSION['pasien']['idpasien']);

            // Generate a unique ID for the message
            $id = uniqid($room . '_');

            // Check if a file is uploaded
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'foto_chat/';
                $fileName = uniqid() . '_' . basename(sani($_FILES['file']['name']));
                $uploadFile = $uploadDir . $fileName;

                // Move the uploaded file to the target directory
                if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
                    $fileUrl = sani($uploadFile);

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
                $message = sani($_POST['message']);
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
