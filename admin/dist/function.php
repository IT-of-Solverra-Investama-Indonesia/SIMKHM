<?php
$koneksi = mysqli_connect("localhost", "root", "", "klinik_wonorejo");
    $koneksimaster = mysqli_connect("localhost", "root", "", "klinik_master");
// $koneksidoc = mysqli_connect("localhost", "root", "", "dokumen");


function sani($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}
?>

