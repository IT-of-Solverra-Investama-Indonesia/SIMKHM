<?php 
    header("Content-Type: application/json");
    include '../../admin/dist/function.php';
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    if(isset($_POST['login'])){
        $NIK = htmlspecialchars($_POST['NIK']);
        $getPasien = $koneksi->query("SELECT `nama_lengkap`, `nama_ibu`, `no_rm`, `tgl_lahir`, `umur`, `no_bpjs`, `tempat_lahir`, `jenis_kelamin`, `nohp`, `email`, `jenis_identitas`, `no_identitas`, `agama`, `suku`, `bahasa`, `rt`, `rw`, `provinsi`, `kota`, `kecamatan`, `kelurahan`, `kode_pos`, `alamat`, `alamat_dom`, `rt_dom`, `rw_dom`, `kelurahan_dom`, `kecamatan_dom`, `kota_dom`, `provinsi_dom`, `kode_pos_dom`, `no_telp`, `pendidikan`, `pekerjaan`, `status_nikah`, `kategori`, `pembiayaan`, `foto`, `ihs_id` FROM pasien WHERE no_identitas = '$NIK' Limit 1");
        if ($getPasien->num_rows > 0) {
            $idUser = $koneksi->query("SELECT * FROM pasien WHERE no_identitas = '$NIK' ORDER BY idpasien DESC Limit 1")->fetch_assoc();
            
            $data = array();
            while ($hasil = $getPasien->fetch_assoc()){
                $data[] = $hasil;
            }

            $uniq_id = $idUser['idpasien'].date('s').substr(str_shuffle($characters), 0, 3).date('d').substr(str_shuffle($characters), 0, 2).date('mY').substr(str_shuffle($characters), 0, 3).date('Ymd');

             $response = array(
                "status" => "Successfully",
                "data" => $data,
                "uniq_code" => $uniq_id
            );
        } else {
            $response = array(
                "status" => "Failed",
                "data" => ""
            );
        }
    }

    if(isset($_POST['register'])){
        $nama_lengkap = htmlspecialchars($_POST["nama_lengkap"]);
        $nama_ibu = htmlspecialchars($_POST["nama_ibu"]);
        $nohp = htmlspecialchars($_POST["nohp"]);
        $email = '';
        $tgl_lahir = date('Y-m-d', strtotime($_POST['tanggal'] . '-' . $_POST['bulan'] . '-' . $_POST['tahun']));
        $jenis_identitas = 'KTP';
        $no_identitas = htmlspecialchars($_POST["no_identitas"]);
        $jenis_kelamin = htmlspecialchars($_POST["jenis_kelamin"]);
        $provinsi = htmlspecialchars($_POST["provinsi"]);
        $kota = htmlspecialchars($_POST["kota"]);
        $kelurahan = htmlspecialchars($_POST["kelurahan"]);
        $kecamatan = htmlspecialchars($_POST["kecamatan"]);
        $kode_pos = htmlspecialchars($_POST["kode_pos"]);
        $alamat = htmlspecialchars($_POST["alamat"]);
        $kategori = '';
        $pembiayaan = '';
        $status_nikah = '';
        $foto = '';

        // Ambil Nomor RM Terakhir + 1
        $no_rm = '';

        //hitung usia
        $lahir = new DateTime($tgl_lahir);
        $today = new DateTime();
        $umur = $today->diff($lahir);
        $umur2 = $umur->y . " Tahun," . $umur->m . " Bulan," . $umur->d . " Hari";

        $koneksi->query("INSERT INTO pasien (nama_lengkap, nama_ibu, nohp, email, no_identitas,  tgl_lahir, jenis_kelamin, jenis_identitas, provinsi, kota, kelurahan, kecamatan, kode_pos, alamat, kategori, status_nikah, pembiayaan, foto, no_rm, umur, no_bpjs) VALUES ('$nama_lengkap', '$nama_ibu', '$nohp', '$email', '$no_identitas', '$tgl_lahir', '$jenis_kelamin', '$jenis_identitas', '$provinsi', '$kota', '$kelurahan', '$kecamatan',  '$kode_pos', '$alamat', '$kategori', '$status_nikah','$pembiayaan', '$foto', '$no_rm', '$umur2','$_POST[no_bpjs]')");

        $result = $koneksi->query("SELECT nama_lengkap, nama_ibu, nohp, email, no_identitas,  tgl_lahir, jenis_kelamin, jenis_identitas, provinsi, kota, kelurahan, kecamatan, kode_pos, alamat, kategori, status_nikah, pembiayaan, foto, no_rm, umur, no_bpjs FROM pasien WHERE no_identitas = '$no_identitas' LIMIT 1");

        $data = array();
        while ($hasil = $result->fetch_assoc()){
            $data[] = $hasil; 
        }

        $idUser = $koneksi->query("SELECT * FROM pasien WHERE no_identitas = '$no_identitas' Limit 1")->fetch_assoc();

        $uniq_id = $idUser['idpasien'].date('s').substr(str_shuffle($characters), 0, 3).date('d').substr(str_shuffle($characters), 0, 2).date('mY').substr(str_shuffle($characters), 0, 3).date('Ymd');

        $response = array(
            "status" => "Successfully",
            "message" => $data,
            "uniq_code" => $uniq_id
        );   
    }

    if(isset($_POST['checkPcientData'])){
        $getToken = $_GET['token'] ?? $_POST['token'];
        $lengthToKeep = strlen($getToken) - 26;
        $token = substr($getToken, 0, $lengthToKeep);

        $result = $koneksi->query("SELECT nama_lengkap, nama_ibu, nohp, email, no_identitas,  tgl_lahir, jenis_kelamin, jenis_identitas, provinsi, kota, kelurahan, kecamatan, kode_pos, alamat, kategori, status_nikah, pembiayaan, foto, no_rm, umur, no_bpjs FROM pasien WHERE idpasien = '$token' ORDER BY idpasien DESC LIMIT 1");

        $data = array();
        while ($hasil = $result->fetch_assoc()){
            $data[] = $hasil; 
        }

        $response = array(
            "status" => "Successfully",
            "message" => $data,
            "uniq_code" => $getToken
        ); 
    }

    echo json_encode($response);
?>

