<?php 
    header("Content-Type: application/json");
    include '../../admin/dist/function.php';
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    if(isset($_POST['login'])){
        $NIK = sani($_POST['NIK']);
        $stmt = $koneksi->prepare("SELECT `nama_lengkap`, `nama_ibu`, `no_rm`, `tgl_lahir`, `umur`, `no_bpjs`, `tempat_lahir`, `jenis_kelamin`, `nohp`, `email`, `jenis_identitas`, `no_identitas`, `agama`, `suku`, `bahasa`, `rt`, `rw`, `provinsi`, `kota`, `kecamatan`, `kelurahan`, `kode_pos`, `alamat`, `alamat_dom`, `rt_dom`, `rw_dom`, `kelurahan_dom`, `kecamatan_dom`, `kota_dom`, `provinsi_dom`, `kode_pos_dom`, `no_telp`, `pendidikan`, `pekerjaan`, `status_nikah`, `kategori`, `pembiayaan`, `foto`, `ihs_id` FROM pasien WHERE no_identitas = ? Limit 1");
        $stmt->bind_param("s", $NIK);
        $stmt->execute();
        $getPasien = $stmt->get_result();
        if ($getPasien->num_rows > 0) {
            $stmt2 = $koneksi->prepare("SELECT * FROM pasien WHERE no_identitas = ? ORDER BY idpasien DESC Limit 1");
            $stmt2->bind_param("s", $NIK);
            $stmt2->execute();
            $idUser = $stmt2->get_result()->fetch_assoc();

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
        $nama_lengkap = sani($_POST["nama_lengkap"]);
        $nama_ibu = sani($_POST["nama_ibu"]);
        $nohp = sani($_POST["nohp"]);
        $email = '';
        $tgl_lahir = date('Y-m-d', strtotime(sani($_POST['tanggal']) . '-' . sani($_POST['bulan']) . '-' . sani($_POST['tahun'])));
        $jenis_identitas = 'KTP';
        $no_identitas = sani($_POST["no_identitas"]);
        $jenis_kelamin = sani($_POST["jenis_kelamin"]);
        $provinsi = sani($_POST["provinsi"]);
        $kota = sani($_POST["kota"]);
        $kelurahan = sani($_POST["kelurahan"]);
        $kecamatan = sani($_POST["kecamatan"]);
        $kode_pos = sani($_POST["kode_pos"]);
        $alamat = sani($_POST["alamat"]);
        $kategori = '';
        $pembiayaan = '';
        $status_nikah = '';
        $foto = '';
        $no_bpjs = sani($_POST["no_bpjs"]);

        // Ambil Nomor RM Terakhir + 1
        $no_rm = '';

        // hitung usia
        $lahir = new DateTime($tgl_lahir);
        $today = new DateTime();
        $umur = $today->diff($lahir);
        $umur2 = $umur->y . " Tahun," . $umur->m . " Bulan," . $umur->d . " Hari";

        $stmt = $koneksi->prepare("INSERT INTO pasien (nama_lengkap, nama_ibu, nohp, email, no_identitas, tgl_lahir, jenis_kelamin, jenis_identitas, provinsi, kota, kelurahan, kecamatan, kode_pos, alamat, kategori, status_nikah, pembiayaan, foto, no_rm, umur, no_bpjs) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "sssssssssssssssssssss",
            $nama_lengkap,
            $nama_ibu,
            $nohp,
            $email,
            $no_identitas,
            $tgl_lahir,
            $jenis_kelamin,
            $jenis_identitas,
            $provinsi,
            $kota,
            $kelurahan,
            $kecamatan,
            $kode_pos,
            $alamat,
            $kategori,
            $status_nikah,
            $pembiayaan,
            $foto,
            $no_rm,
            $umur2,
            $no_bpjs
        );
        $stmt->execute();

        $stmt2 = $koneksi->prepare("SELECT nama_lengkap, nama_ibu, nohp, email, no_identitas, tgl_lahir, jenis_kelamin, jenis_identitas, provinsi, kota, kelurahan, kecamatan, kode_pos, alamat, kategori, status_nikah, pembiayaan, foto, no_rm, umur, no_bpjs FROM pasien WHERE no_identitas = ? LIMIT 1");
        $stmt2->bind_param("s", $no_identitas);
        $stmt2->execute();
        $result = $stmt2->get_result();

        $data = array();
        while ($hasil = $result->fetch_assoc()){
            $data[] = $hasil; 
        }

        $stmt3 = $koneksi->prepare("SELECT * FROM pasien WHERE no_identitas = ? LIMIT 1");
        $stmt3->bind_param("s", $no_identitas);
        $stmt3->execute();
        $idUser = $stmt3->get_result()->fetch_assoc();

        $uniq_id = $idUser['idpasien'].date('s').substr(str_shuffle($characters), 0, 3).date('d').substr(str_shuffle($characters), 0, 2).date('mY').substr(str_shuffle($characters), 0, 3).date('Ymd');

        $response = array(
            "status" => "Successfully",
            "message" => $data,
            "uniq_code" => $uniq_id
        );   
    }

    if(isset($_POST['checkPcientData'])){
        $getToken = isset($_GET['token']) ? sani($_GET['token']) : (isset($_POST['token']) ? sani($_POST['token']) : '');
        $lengthToKeep = strlen($getToken) - 26;
        $token = substr($getToken, 0, $lengthToKeep);

        $stmt = $koneksi->prepare("SELECT nama_lengkap, nama_ibu, nohp, email, no_identitas, tgl_lahir, jenis_kelamin, jenis_identitas, provinsi, kota, kelurahan, kecamatan, kode_pos, alamat, kategori, status_nikah, pembiayaan, foto, no_rm, umur, no_bpjs FROM pasien WHERE idpasien = ? ORDER BY idpasien DESC LIMIT 1");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

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

