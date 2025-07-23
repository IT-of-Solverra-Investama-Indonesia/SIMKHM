<?php

    header("Content-Type: application/json");
    include '../../admin/dist/function.php';
    // include 'function.php';

    if(isset($_POST['login'])){
        $email = sani($_POST['email']);
        $password = sani($_POST['password']);

        $stmt = $koneksi->prepare("SELECT `nama_lengkap`, `nama_ibu`, `no_rm`, `tgl_lahir`, `umur`, `no_bpjs`, `tempat_lahir`, `jenis_kelamin`, `nohp`, `email`, `password`, `jenis_identitas`, `no_identitas`, `agama`, `suku`, `bahasa`, `rt`, `rw`, `provinsi`, `kota`, `kecamatan`, `kelurahan`, `kode_pos`, `alamat`, `alamat_dom`, `rt_dom`, `rw_dom`, `kelurahan_dom`, `kecamatan_dom`, `kota_dom`, `provinsi_dom`, `kode_pos_dom`, `no_telp`, `pendidikan`, `pekerjaan`, `status_nikah`, `kategori`, `pembiayaan`, `foto`, `ihs_id` FROM pasien_kosmetik WHERE email = ? and password = ? Limit 1");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $cekUsers = $stmt->get_result();

        if($cekUsers->num_rows > 0){
            $stmt2 = $koneksi->prepare("SELECT * FROM pasien_kosmetik WHERE email = ? and password = ? Limit 1");
            $stmt2->bind_param("ss", $email, $password);
            $stmt2->execute();
            $idUser = $stmt2->get_result()->fetch_assoc();
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

            $data = array();
            while ($hasil = $cekUsers->fetch_assoc()){
                $data[] = $hasil;
                $uniq_id = $idUser['idpasien'].date('s').substr(str_shuffle($characters), 0, 3).date('d').substr(str_shuffle($characters), 0, 2).date('mY').substr(str_shuffle($characters), 0, 3).date('Ymd');
            }
            $response = array(
                "status" => "Successfully",
                "data" => $data,
                "uniq_code" => $uniq_id
            );
        }else{
            $response = array(
                "status" => "Gagal, hehehe",
                "data" => "Data Kosong"
            );
        }
    }

    if(isset($_POST['registerAwal'])){
        $nama_lengkap = sani(htmlspecialchars($_POST["nama_lengkap"]));
        $nohp = sani(htmlspecialchars($_POST["nohp"]));
        $jenis_identitas = 'KTP';
        $email = sani(htmlspecialchars($_POST["email"]));
        $password = sani(htmlspecialchars($_POST["password"]));
        $tanggal = sani($_POST['tanggal']);
        $bulan = sani($_POST['bulan']);
        $tahun = sani($_POST['tahun']);
        $tgl_lahir = date('Y-m-d', strtotime($tanggal.'-'.$bulan.'-'.$tahun));

        $lahir = new DateTime($tgl_lahir);
        $today = new DateTime();
        $umur = $today->diff($lahir);
        $umur2 = $umur->y." Tahun,".$umur->m." Bulan,".$umur->d." Hari";

        $stmt = $koneksi->prepare("SELECT * FROM pasien_kosmetik WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $cekEmailTerdaftar = $stmt->get_result();

        if($cekEmailTerdaftar->num_rows == 0){
            $stmt2 = $koneksi->prepare("INSERT INTO pasien_kosmetik (nama_lengkap, nohp, email, tgl_lahir, jenis_identitas, umur, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt2->bind_param("sssssss", $nama_lengkap, $nohp, $email, $tgl_lahir, $jenis_identitas, $umur2, $password);
            $stmt2->execute();

            $stmt3 = $koneksi->prepare("SELECT * FROM pasien_kosmetik WHERE nama_lengkap=? AND nohp=? AND email=? AND tgl_lahir=? AND jenis_identitas=? AND umur=? AND password=?");
            $stmt3->bind_param("sssssss", $nama_lengkap, $nohp, $email, $tgl_lahir, $jenis_identitas, $umur2, $password);
            $stmt3->execute();
            $result = $stmt3->get_result();

            $data = array();
            while ($hasil = $result->fetch_assoc()){
                $data[] = $hasil; 
            }
        }else{
            $data = "email sudah terpakai";
        }

        $response = array(
            "status" => "Successfully",
            "message" => $data
        );
    }

    if(isset($_POST['registerKe2'])){
        $jenis_kelamin = sani($_POST['jenis_kelamin']);
        $no_identitas = sani($_POST['no_identitas']);
        $token_raw = sani($_POST['token']);
        $lengthToKeep = strlen($token_raw) - 26;
        $token = substr($token_raw, 0, $lengthToKeep);

        $stmt = $koneksi->prepare("UPDATE pasien_kosmetik SET jenis_kelamin=?, no_identitas=? WHERE idpasien=?");
        $stmt->bind_param("sss", $jenis_kelamin, $no_identitas, $token);
        $stmt->execute();

        $stmt2 = $koneksi->prepare("SELECT * FROM pasien_kosmetik WHERE idpasien=?");
        $stmt2->bind_param("s", $token);
        $stmt2->execute();
        $result = $stmt2->get_result();

        $data = array();
        while ($hasil = $result->fetch_assoc()){
            $data[] = $hasil; 
        }

        $response = array(
            "status" => "Successfully",
            "message" => $data
        );
    }

    if(isset($_POST['registerKe3'])){
        $provinsi = sani(htmlspecialchars($_POST["provinsi"]));
        $kota = sani(htmlspecialchars($_POST["kota"]));
        $kelurahan = sani(htmlspecialchars($_POST["kelurahan"]));
        $kecamatan = sani(htmlspecialchars($_POST["kecamatan"]));
        $kode_pos = sani(htmlspecialchars($_POST["kode_pos"]));
        $alamat = sani(htmlspecialchars($_POST["alamat"]));
        $token_raw = sani($_POST['token']);
        $lengthToKeep = strlen($token_raw) - 26;
        $token = substr($token_raw, 0, $lengthToKeep);

        $stmt = $koneksi->prepare("UPDATE pasien_kosmetik SET provinsi=?, kota=?, kecamatan=?, kelurahan=?, kode_pos=?, alamat=? WHERE idpasien=?");
        $stmt->bind_param("sssssss", $provinsi, $kota, $kecamatan, $kelurahan, $kode_pos, $alamat, $token);
        $stmt->execute();

        $stmt2 = $koneksi->prepare("SELECT * FROM pasien_kosmetik WHERE idpasien=?");
        $stmt2->bind_param("s", $token);
        $stmt2->execute();
        $result = $stmt2->get_result();

        $data = array();
        while ($hasil = $result->fetch_assoc()){
            $data[] = $hasil; 
        }

        $response = array(
            "status" => "Successfully",
            "message" => $data
        );    
    }

    echo json_encode($response);

?>