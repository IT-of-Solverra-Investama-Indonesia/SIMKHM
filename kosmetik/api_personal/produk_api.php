<?php

    header("Content-Type: application/json");
    // include "../function.php";
    include '../../admin/dist/function.php';
    // include 'function.php';

    if(isset($_GET['allProduk'])){
        if($_GET['fil'] != ''){
            if($_GET['fil'] == 'hargaTertinggi'){
                $orderCondition = "harga ASC";
            }else{
                $orderCondition = "harga DESC";
            }
        }else{
            $orderCondition = "created_at DESC";
        }
        $limitCondition='';
        if($_GET['lim'] != ''){
            // Parameters for pagination
            $limit = $_GET['lim']; // Number of entries to show in a page
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $start = ($page - 1) * $limit;

            $limitCondition = " Limit ".$start.", ".$limit."";
        }
        

        $result = $koneksi->query("SELECT * FROM produk_kosmetik WHERE nama_produk LIKE '%$_GET[keyword]%' ORDER BY ".$orderCondition." ".$limitCondition." ");

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

    if(isset($_GET['produkTerlaris'])){
        if($_GET['produkTerlaris'] == 'true'){
            $src='DESC';
        }else{
            $src='ASC';
        }
        $getPemesana = $koneksi->query("SELECT *, COUNT(*) as jumlahPemesanan FROM pemesanan INNER JOIN produk_kosmetik ON produk_kosmetik.id_produk = pemesanan.produk_id GROUP BY id_produk ORDER BY jumlahPemesanan ".$src."");
        if($getPemesana->num_rows > 0){
            $data = array();
            while ($hasil = $getPemesana->fetch_assoc()){
                $data[] = $hasil; 
            }
    
            $response = array(
                "status" => "Successfully",
                "data" => ""
            );
        }else{
            $response = array(
                "status" => "Gagal",
                "data" => ""
            );

        }
    }

    if(isset($_GET['singleProduk'])){
        $result = $koneksi->query("SELECT * FROM produk_kosmetik WHERE id_produk = '$_GET[produk]' LIMIT 1");

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

    echo json_encode($response);

?>