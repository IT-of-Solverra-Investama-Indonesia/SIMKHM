<?php

// Deteksi URL dan set string berdasarkan lokasi
$currentURL = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] : '';
$currentURL = strtolower($currentURL); // Convert to lowercase untuk case-insensitive

$string = ''; // Default value
if(strpos($currentURL, 'ermkhm') !== false){
    if (strpos($currentURL, 'wonorejo') !== false) {
        $koneksi = mysqli_connect("localhost", "ermkhmid_wonorejo", "W5VAkyndC8GEBMDrRH7F", "ermkhmid_wonorejo");
        $koneksimaster = mysqli_connect("localhost", "ermkhmid_master", "5pa3D84p5kerQLVRjCR5", "ermkhmid_master");
    } elseif (strpos($currentURL, 'klakah') !== false) {
        $koneksi = mysqli_connect("localhost", "ermkhmid_klakah", "8JCL5nx39aKnSLtLgG6Q", "ermkhmid_klakah");
        $koneksimaster = mysqli_connect("localhost", "ermkhmid_master", "5pa3D84p5kerQLVRjCR5", "ermkhmid_master");
    } elseif (strpos($currentURL, 'tunjung') !== false) {
        $koneksi = mysqli_connect("localhost", "ermkhmid_tunjung", "2fK6K6xg4LshSTLBTMJG", "ermkhmid_tunjung");
        $koneksimaster = mysqli_connect("localhost", "ermkhmid_master", "5pa3D84p5kerQLVRjCR5", "ermkhmid_master");
    } elseif (strpos($currentURL, 'kunir') !== false) {
        $koneksi = mysqli_connect("localhost", "ermkhmid_kunir", "Fq65VjpxzKa4A7rZaXBN", "ermkhmid_kunir");
        $koneksimaster = mysqli_connect("localhost", "ermkhmid_master", "5pa3D84p5kerQLVRjCR5", "ermkhmid_master");
    }else{
        $koneksi = mysqli_connect("localhost", "ermkhmid_wonorejo", "W5VAkyndC8GEBMDrRH7F", "ermkhmid_wonorejo");
        $koneksimaster = mysqli_connect("localhost", "ermkhmid_master", "5pa3D84p5kerQLVRjCR5", "ermkhmid_master");
    }
}elseif(strpos($currentURL, 'simkhm') !== false){
    // if (strpos($currentURL, 'wonorejo') !== false) {
    //     $koneksi = mysqli_connect("103.28.12.123", "simkhmid_simkhmuser", "Wijaya1702!", "simkhmid_database");
    //     $koneksimaster = mysqli_connect("localhost", "simkhmid_simkhmuser", "Wijaya1702!", "simkhmid_master");
    // } elseif (strpos($currentURL, 'klakah') !== false) {
    //     $koneksi = mysqli_connect("103.28.12.123", "simkhmid_simkhmuser", "Wijaya1702!", "simkhmid_klakah");
    //     $koneksimaster = mysqli_connect("localhost", "simkhmid_simkhmuser", "Wijaya1702!", "simkhmid_master");
    // } elseif (strpos($currentURL, 'tunjung') !== false) {
    //     $koneksi = mysqli_connect("103.28.12.123", "simkhmid_simkhmuser", "Wijaya1702!", "simkhmid_tunjung");
    //     $koneksimaster = mysqli_connect("localhost", "simkhmid_simkhmuser", "Wijaya1702!", "simkhmid_master");
    // } elseif (strpos($currentURL, 'kunir') !== false) {
    //     $koneksi = mysqli_connect("103.28.12.123", "simkhmid_simkhmuser", "Wijaya1702!", "simkhmid_kunir");
    //     $koneksimaster = mysqli_connect("localhost", "simkhmid_simkhmuser", "Wijaya1702!", "simkhmid_master");
    // }else{
    //     $koneksi = mysqli_connect("103.28.12.123", "simkhmid_simkhmuser", "Wijaya1702!", "simkhmid_database");
    //     $koneksimaster = mysqli_connect("localhost", "simkhmid_simkhmuser", "Wijaya1702!", "simkhmid_master");
    //     if (!$koneksi) {
    //         // Jika koneksi gagal, fallback ke database lokal klinik
    //         $koneksi = mysqli_connect("localhost", "root", "", "klinik");
    //         $koneksimaster = mysqli_connect("localhost", "root", "", "klinik_master");
    //     }

    // }
    if (strpos($currentURL, 'wonorejo') !== false) {
        $koneksi = mysqli_connect("localhost", "bumicode_simkhmBumicode", "simkhmBumicode", "bumicode_simkhm_database");
        $koneksimaster = mysqli_connect("localhost", "bumicode_simkhmBumicode", "simkhmBumicode", "bumicode_simkhm_master");
    } elseif (strpos($currentURL, 'klakah') !== false) {
        $koneksi = mysqli_connect("localhost", "bumicode_simkhmBumicode", "simkhmBumicode", "bumicode_simkhm_klakah");
        $koneksimaster = mysqli_connect("localhost", "bumicode_simkhmBumicode", "simkhmBumicode", "bumicode_simkhm_master");
    } elseif (strpos($currentURL, 'tunjung') !== false) {
        $koneksi = mysqli_connect("localhost", "bumicode_simkhmBumicode", "simkhmBumicode", "bumicode_simkhm_tunjung");
        $koneksimaster = mysqli_connect("localhost", "bumicode_simkhmBumicode", "simkhmBumicode", "bumicode_simkhm_master");
    } elseif (strpos($currentURL, 'kunir') !== false) {
        $koneksi = mysqli_connect("localhost", "bumicode_simkhmBumicode", "simkhmBumicode", "bumicode_simkhm_kunir");
        $koneksimaster = mysqli_connect("localhost", "bumicode_simkhmBumicode", "simkhmBumicode", "bumicode_simkhm_master");
    }else{
        $koneksi = mysqli_connect("localhost", "bumicode_simkhmBumicode", "simkhmBumicode", "bumicode_simkhm_database");
        $koneksimaster = mysqli_connect("localhost", "bumicode_simkhmBumicode", "simkhmBumicode", "bumicode_simkhm_master");
        if (!$koneksi) {
            // Jika koneksi gagal, fallback ke database lokal klinik
            $koneksi = mysqli_connect("localhost", "root", "", "klinik");
            $koneksimaster = mysqli_connect("localhost", "root", "", "klinik_master");
        }

    }
}else{
    $koneksi = mysqli_connect("localhost", "root", "", "klinik");
    $koneksimaster = mysqli_connect("localhost", "root", "", "klinik_master");
}

function sani($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');   
}

