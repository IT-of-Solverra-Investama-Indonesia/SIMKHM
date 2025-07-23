<?php

    header("Content-Type: application/json");
    // include "../function.php";
    include '../../admin/dist/function.php';
    // include 'function.php';

    if(isset($_GET['allProduk'])){
        $fil = sani($_GET['fil']);
        $lim = sani($_GET['lim']);
        $keyword = sani($_GET['keyword']);
        $page = isset($_GET['page']) ? sani($_GET['page']) : 1;

        if($fil != ''){
            if($fil == 'hargaTertinggi'){
                $orderCondition = "harga ASC";
            }else{
                $orderCondition = "harga DESC";
            }
        }else{
            $orderCondition = "created_at DESC";
        }
        $limitCondition = '';
        if($lim != ''){
            $limit = $lim;
            $start = ($page - 1) * $limit;
            $limitCondition = " LIMIT ?, ?";
        }

        $sql = "SELECT * FROM produk_kosmetik WHERE nama_produk LIKE ? ORDER BY $orderCondition";
        if($limitCondition != ''){
            $sql .= $limitCondition;
        }

        $stmt = $koneksi->prepare($sql);
        if($limitCondition != ''){
            $likeKeyword = "%$keyword%";
            $stmt->bind_param("sii", $likeKeyword, $start, $limit);
        } else {
            $likeKeyword = "%$keyword%";
            $stmt->bind_param("s", $likeKeyword);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){
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
        $stmt->close();
    }

    if(isset($_GET['produkTerlaris'])){
        $produkTerlaris = sani($_GET['produkTerlaris']);
        $src = ($produkTerlaris == 'true') ? 'DESC' : 'ASC';

        $sql = "SELECT produk_kosmetik.*, COUNT(*) as jumlahPemesanan 
                FROM pemesanan 
                INNER JOIN produk_kosmetik ON produk_kosmetik.id_produk = pemesanan.produk_id 
                GROUP BY id_produk 
                ORDER BY jumlahPemesanan $src";
        $stmt = $koneksi->prepare($sql);
        $stmt->execute();
        $getPemesana = $stmt->get_result();

        if($getPemesana->num_rows > 0){
            $data = array();
            while ($hasil = $getPemesana->fetch_assoc()){
                $data[] = $hasil; 
            }
            $response = array(
                "status" => "Successfully",
                "data" => $data
            );
        }else{
            $response = array(
                "status" => "Gagal",
                "data" => ""
            );
        }
        $stmt->close();
    }

    if(isset($_GET['singleProduk'])){
        $produk = sani($_GET['produk']);
        $sql = "SELECT * FROM produk_kosmetik WHERE id_produk = ? LIMIT 1";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("s", $produk);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){
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
        $stmt->close();
    }

    echo json_encode($response);

?>