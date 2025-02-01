<?php 
    header("Content-Type: application/json");
    include '../../admin/dist/function.php';
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    $getToken = $_GET['token'] ?? $_POST['token'];
    $lengthToKeep = strlen($getToken) - 26;
    $token = substr($getToken, 0, $lengthToKeep);

    $pacient = $koneksi->query("SELECT * FROM pasien WHERE idpasien = '$token'")->fetch_assoc();

    if(isset($_POST['registration'])){        
        $id_pasien=htmlspecialchars($token);
        $no_rm=htmlspecialchars($_POST["no_rm"]);
        $nama_pasien=htmlspecialchars($_POST["nama_pasien"]);
        $perawatan=htmlspecialchars($_POST["perawatan"]);
        $jadwal=htmlspecialchars($_POST["jadwal"]);
        $antrian=htmlspecialchars($_POST["antrian"]);
        $keluhan=htmlspecialchars($_POST["keluhan"]);
        
        $tgl2=date('Y-m-d');
        $tgl=date('Ymd', strtotime($jadwal))+0;
        $kode=$tgl;
        $kode .="+";
        $kode .=$antrian;
        
        if($perawatan == "Rawat Inap"){
            $koneksi->query("INSERT INTO igd (nama_pasien, no_rm, tgl_masuk) VALUES ('$nama_pasien','$no_rm', '$jadwal')");
        }else{
            $koneksi->query("INSERT INTO registrasi_rawat (nama_pasien, perawatan, jenis_kunjungan, id_pasien, no_rm, jadwal, antrian, status_antri, carabayar, kode, keluhan, kategori) VALUES ('$nama_pasien', '$perawatan', 'Kunjungan Sakit', '$id_pasien', '$no_rm', '$jadwal', '$antrian', 'Belum Datang', '$_POST[carabayar]','$kode', '$keluhan', 'online')");
        }
    }

    if(isset($_GET['registrationDetail'])){
        $result = $koneksi->query("SELECT * FROM registrasi_rawat WHERE idrawat = '$_POST[idrawat]'");

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