<?php

    header("Content-Type: application/json");
    include '../../admin/dist/function.php';
    // include 'function.php';

    $getToken = $_GET['token'] ?? $_POST['token'];
    $lengthToKeep = strlen($getToken) - 40;
    $token = substr($getToken, 0, $lengthToKeep);

    if(isset($_GET['getKeranjangUser'])){
        $result = $koneksi->query("SELECT * FROM cart_kosmetik join produk_kosmetik on cart_kosmetik.produk_id = produk_kosmetik.id_produk  WHERE cart_kosmetik.user_id = '$token'");

        if($result -> num_rows > 0){
            $data = array();
            while ($hasil = $result->fetch_assoc()){
                $data[] = $hasil; 
            }
            $response = array(
                "status" => "Successfully",
                "data" => $data
            );
        }else{
            $response = array(
                "status" => "Gagal, hehehe",
                "data" => "Data Kosong"
            );
        }
    }

    if(isset($_POST['addKeranjang'])){
        
        $getProd = $koneksi->query("SELECT * FROM produk_kosmetik WHERE id_produk = '" . htmlspecialchars($_POST['idProduk']) . "'")->fetch_assoc();
        $user_id = $token;
        $username = $_SESSION['kosmetik']['nama_lengkap'];
        $produk_id = $getProd['id_produk'];
        $produk = $getProd['nama_produk'];
        $harga = $getProd['harga'];
        $diskon = $getProd['diskon'];
        $jumlah = '1';
        $sub_harga = 1 * $getProd['harga'];

        $koneksi->query("INSERT INTO cart_kosmetik (user_id, username, produk_id, produk, harga, diskon, jumlah, sub_harga) VALUES ('$user_id', '$username', '$produk_id', '$produk', '$harga', '$diskon', '$jumlah', '$sub_harga')");

        $getKeranjang = $koneksi->query("SELECT * FROM cart_kosmetik WHERE user_id = '$token'");
        
        $data = array();
        while ($hasil = $result->fetch_assoc()){
            $data[] = $hasil; 
        }

        $response = array(
            "status" => $status,
            "data" => $data
        );

    }

    if(isset($_POST['updateJumlah'])){
        $jumlah = htmlspecialchars($_POST['jumlah']);
        $id = htmlspecialchars($_POST['id']);
        $koneksi->query("UPDATE cart_kosmetik SET jumlah = '$jumlah' WHERE id='$id' AND user_id ='$token'");
        $resul= $koneksi->query("SELECT * FROM cart_kosmetik WHERE id='$id' AND user_id ='$token'");
        $data = array();
        while ($hasil = $result->fetch_assoc()){
            $data[] = $hasil; 
        }
        $response = array(
            "status" => "Successfully",
            "data" => $data
        );
    }

    if(isset($_GET['delKeranjang'])){
        $id = htmlspecialchars($_POST['idCart']);
        $koneksi->query("DELETE FROM cart_kosmetik WHERE id='$id'");
        $response = array(
            "status" => "Successfully",
            "data" => ""
        );
    }

    if(isset($_POST['checkout'])){
        $getRegist = $koneksi->query("SELECT * FROM pasien_kosmetik WHERE idpasien = '$token'")->fetch_assoc();

        if($getRegist['provinsi'] =='' OR $getRegist['kabupaten'] =='' OR $getRegist['kelurahan'] =='' OR $getRegist['alamat'] ==''){
            $status = "Unsuccessfully";
            $data = 'Silahlan Melengkapi Data Diri (Registrasi 3)';
        }else{
            $getCart = $koneksi->query("SELECT cart_kosmetik.*, cart_kosmetik.harga as hargaa, produk_kosmetik.* FROM cart_kosmetik join produk_kosmetik on cart_kosmetik.produk_id = produk_kosmetik.id_produk  WHERE cart_kosmetik.user_id = '$token' ");
    
            $getUser = $koneksi->query("SELECT * FROM pasien_kosmetik WHERE idpasien = '$token' LIMIT 1")->fetch_assoc();
            
            $code_nota = date('dmYHis').$getUser['idpasien'];
            $user_id = $getUser['idpasien'];
            $username = $getUser['nama_lengkap'];
            $nama_lengkap = htmlspecialchars($_POST['nama_lengkap']);
            $alamat_lengkap = htmlspecialchars($_POST['alamat_lengkap']);
            $no_telp = htmlspecialchars($_POST['no_telp']);
    
            foreach($getCart as $data){
                $produk_id=$data['id_produk'];
                $produk=$data['nama_produk'];
                $harga=$data['hargaa'];
                $jumlah=$data['jumlah'];
                $sub_harga= $data['harga'] * $data['jumlah'] - (($data['harga'] * $data['jumlah']) * $data['diskon'] / 100);
    
                $koneksi->query("INSERT INTO pemesanan (code_nota, user_id, username, nama_lengkap, alamat_lengkap, no_telp, produk_id, produk, harga, jumlah, sub_harga, status) VALUES ('$code_nota', '$user_id', '$username', '$nama_lengkap', '$alamat_lengkap', '$no_telp', '$produk_id', '$produk', '$harga', '$jumlah', '$sub_harga', 'Menunggu_pembayaran')");
            }
    
            $koneksi->query("DELETE FROM cart_kosmetik WHERE user_id = '$user_id'");
            $status = "Successfully";
            $data = "Berhasil";
        }
        $response = array(
            "status" => $status,
            "data" => $data
        );
    }

    echo json_encode($response);
?>