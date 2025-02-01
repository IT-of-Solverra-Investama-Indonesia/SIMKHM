<?php

    header("Content-Type: application/json");
    include '../../admin/dist/function.php';
    // include 'function.php';

    if(isset($_POST['login'])){
        $cekUsers = $koneksi->query("SELECT `nama_lengkap`, `nama_ibu`, `no_rm`, `tgl_lahir`, `umur`, `no_bpjs`, `tempat_lahir`, `jenis_kelamin`, `nohp`, `email`, `password`, `jenis_identitas`, `no_identitas`, `agama`, `suku`, `bahasa`, `rt`, `rw`, `provinsi`, `kota`, `kecamatan`, `kelurahan`, `kode_pos`, `alamat`, `alamat_dom`, `rt_dom`, `rw_dom`, `kelurahan_dom`, `kecamatan_dom`, `kota_dom`, `provinsi_dom`, `kode_pos_dom`, `no_telp`, `pendidikan`, `pekerjaan`, `status_nikah`, `kategori`, `pembiayaan`, `foto`, `ihs_id` FROM pasien_kosmetik WHERE email = '$_POST[email]' and password = '$_POST[password]' Limit 1");

        if($cekUsers -> num_rows > 0){
            $idUser = $koneksi->query("SELECT * FROM pasien_kosmetik WHERE email = '$_POST[email]' and password = '$_POST[password]' Limit 1")->fetch_assoc();
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
        $nama_lengkap=htmlspecialchars($_POST["nama_lengkap"]);
        $nohp=htmlspecialchars($_POST["nohp"]);
        $jenis_identitas='KTP';
        $email=htmlspecialchars($_POST["email"]);
        $password=htmlspecialchars($_POST["password"]);
        $tgl_lahir = $tgl_lahir=date('Y-m-d', strtotime($_POST['tanggal'].'-'.$_POST['bulan'].'-'.$_POST['tahun']));

        $lahir =new DateTime($tgl_lahir);
        $today =new DateTime();
        $umur = $today->diff($lahir);
        $umur2=$umur->y." Tahun,".$umur->m." Bulan,".$umur->d." Hari";

        $cekEmailTerdaftar = $koneksi->query("SELECT * FROM pasien_kosmetik WHERE email = '$email' ");
        if($cekEmailTerdaftar->num_rows == 0){
            $koneksi->query("INSERT INTO pasien_kosmetik (nama_lengkap, nohp, email, tgl_lahir, jenis_identitas, umur, password) VALUES ('$nama_lengkap','$nohp', '$email', '$tgl_lahir', '$jenis_identitas', '$umur2', '$password')");
            $result = $koneksi->query("SELECT * FROM pasien_kosmetik WHERE nama_lengkap='$nama_lengkap', nohp='$nohp', email='$email', tgl_lahir='$tgl_lahir', jenis_identitas='$jenis_identitas', umur='$umur2', password='$password'");
    
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
        $jenis_kelamin = $_POST['jenis_kelamin'];
        $no_identitas = $_POST['no_identitas'];

        $lengthToKeep = strlen($_POST['token']) - 26;
        $token = substr($_POST['token'], 0, $lengthToKeep);

        $koneksi->query("UPDATE pasien_kosmetik SET jenis_kelamin='$jenis_kelamin', no_identitas='$no_identitas' WHERE idpasien='$token'");
        
        $result = $koneksi->query("SELECT * FROM pasien_kosmetik WHERE idpasien='$token'");

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
        $provinsi=htmlspecialchars($_POST["provinsi"]);
        $kota=htmlspecialchars($_POST["kota"]);
        $kelurahan=htmlspecialchars($_POST["kelurahan"]);
        $kecamatan=htmlspecialchars($_POST["kecamatan"]);
        $kode_pos=htmlspecialchars($_POST["kode_pos"]);
        $alamat=htmlspecialchars($_POST["alamat"]);

        $lengthToKeep = strlen($_POST['token']) - 26;
        $token = substr($_POST['token'], 0, $lengthToKeep);

        $koneksi->query("UPDATE pasien_kosmetik SET provinsi='$provinsi', kota='$kota', kecamatan='$kecamatan', kelurahan='$kelurahan', kode_pos='$kode_pos', alamat='$alamat' WHERE idpasien = '$token'");
        
        $result = $koneksi->query("SELECT * FROM pasien_kosmetik WHERE idpasien='$token'");

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