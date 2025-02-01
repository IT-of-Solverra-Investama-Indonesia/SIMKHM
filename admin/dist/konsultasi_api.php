<?php
    session_start();
    include "function.php"; 
    if(isset($_GET['getPesan'])){
        header('Content-Type: application/json');

        // Ambil semua pesan dari tabel chat_konsultasi
        $result = $koneksi->query("SELECT * FROM chat_konsultasi WHERE room_id = '$_GET[getPesan]' ORDER BY created_at ASC");
        $pesan = [];

        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }

        echo json_encode($messages);
    }

    if (isset($_POST['room'])) {
        // $user = $_POST['user'];
        // $message = $_POST['message'];
        if (isset($_POST['room'], $_POST['dari'], $_POST['type'], $_POST['message']) && isset($_SESSION['pasien']['idpasien'])) {
            $id = $_POST['room'] . date('ymdhis') . $_SESSION['pasien']['idpasien'];
        
            // Query untuk memasukkan pesan ke dalam tabel chat_konsultasi
            $stmt = $koneksi->prepare("INSERT INTO chat_konsultasi (id, room_id, dari, dari_id, type_chat, chat) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $id, $_POST['room'], $_POST['dari'], $_SESSION['pasien']['idpasien'], $_POST['type'], $_POST['message']);
            
            if ($stmt->execute()) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => $stmt->error]);
            }
        
            $stmt->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Missing required parameters']);
        }
    }

?>