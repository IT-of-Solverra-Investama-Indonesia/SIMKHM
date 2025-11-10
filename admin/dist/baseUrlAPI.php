<?php
$currentURL = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] : '';
$currentURL = strtolower($currentURL);
if (strpos($currentURL, 'simkhm') !== false) {
    if (strpos($currentURL, 'wonorejo') !== false) {
        $baseUrlSIMKHM = "https://simkhm.id/wonorejo/";
        $baseUrlLama = "https://husadamulia.com/wonorejo/";
    } elseif (strpos($currentURL, 'klakah') !== false) {
        $baseUrlSIMKHM = "https://simkhm.id/klakah/";
        $baseUrlLama = "https://husadamulia.com/klakah/";
    } elseif (strpos($currentURL, 'tunjung') !== false) {
        $baseUrlSIMKHM = "https://simkhm.id/tunjung/";
        $baseUrlLama = "https://husadamulia.com/tunjung/";
    } else {
        $baseUrlSIMKHM = "https://simkhm.id/kunir/";
        $baseUrlLama = "https://husadamulia.com/kunir/";
    }
}else if (strpos($currentURL, 'ermkhm') !== false){
    if (strpos($currentURL, 'wonorejo') !== false) {
        $baseUrlSIMKHM = "https://ermkhm.id/wonorejo/";
        $baseUrlLama = "https://husadamulia.com/wonorejo/";
    } elseif (strpos($currentURL, 'klakah') !== false) {
        $baseUrlSIMKHM = "https://ermkhm.id/klakah/";
        $baseUrlLama = "https://husadamulia.com/klakah/";
    } elseif (strpos($currentURL, 'tunjung') !== false) {
        $baseUrlSIMKHM = "https://ermkhm.id/tunjung/";
        $baseUrlLama = "https://husadamulia.com/tunjung/";
    } else {
        $baseUrlSIMKHM = "https://ermkhm.id/kunir/";
        $baseUrlLama = "https://husadamulia.com/kunir/";
    }
}else{
    $baseUrlSIMKHM = "https://simkhm.id/kunir/";
    $baseUrlLama = "https://husadamulia.com/kunir/";
}


