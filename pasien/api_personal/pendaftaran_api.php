<?php 
    header("Content-Type: application/json");
    include '../../admin/dist/function.php';
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    $getToken = sani($_GET['token'] ?? $_POST['token']);
    $lengthToKeep = strlen($getToken) - 26;
    $token = substr($getToken, 0, $lengthToKeep);

    $stmt = $koneksi->prepare("SELECT * FROM pasien WHERE idpasien = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $pacient = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if(isset($_POST['registration'])){        
        $id_pasien = sani(htmlspecialchars($token));
        $no_rm = sani(htmlspecialchars($_POST["no_rm"]));
        $nama_pasien = sani(htmlspecialchars($_POST["nama_pasien"]));
        $perawatan = sani(htmlspecialchars($_POST["perawatan"]));
        $jadwal = sani(htmlspecialchars($_POST["jadwal"]));
        $antrian = sani(htmlspecialchars($_POST["antrian"]));
        $keluhan = sani(htmlspecialchars($_POST["keluhan"]));
        $carabayar = sani($_POST["carabayar"]);
        
        $tgl2 = date('Y-m-d');
        $tgl = date('Ymd', strtotime($jadwal)) + 0;
        $kode = $tgl . "+" . $antrian;
        
        if($perawatan == "Rawat Inap"){
            $stmt = $koneksi->prepare("INSERT INTO igd (nama_pasien, no_rm, tgl_masuk) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nama_pasien, $no_rm, $jadwal);
            $stmt->execute();
            $stmt->close();
        }else{
            $stmt = $koneksi->prepare("INSERT INTO registrasi_rawat (nama_pasien, perawatan, jenis_kunjungan, id_pasien, no_rm, jadwal, antrian, status_antri, carabayar, kode, keluhan, kategori) VALUES (?, ?, 'Kunjungan Sakit', ?, ?, ?, ?, 'Belum Datang', ?, ?, ?, 'online')");
            $stmt->bind_param("ssssssssss", $nama_pasien, $perawatan, $id_pasien, $no_rm, $jadwal, $antrian, $carabayar, $kode, $keluhan);
            $stmt->execute();
            $stmt->close();
        }
    }

    if(isset($_GET['registrationDetail'])){
        $idrawat = sani($_POST["idrawat"]);
        $stmt = $koneksi->prepare("SELECT * FROM registrasi_rawat WHERE idrawat = ?");
        $stmt->bind_param("s", $idrawat);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = array();
        while ($hasil = $result->fetch_assoc()){
            $data[] = $hasil; 
        }
        $stmt->close();

        $response = array(
            "status" => "Successfully",
            "data" => $data
        );        
    }

    echo json_encode($response);
?>