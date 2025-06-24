<?php
include '../dist/function.php';
if (isset($_GET['updateStokReal'])) {
    $koneksi->query("INSERT INTO revisi_sementara (kode_obat, stok_seharusnya) VALUES ('$_GET[kode_obat]', '$_GET[stok_seharusnya]') ");
    $revisiStokSeharusnya = $_GET['stok_sekarang'] - $_GET['stok_seharusnya'];
    $getDataSingle = $koneksimaster->query("SELECT * FROM master_obat WHERE kode_obat = '$_GET[kode_obat]'")->fetch_assoc();
    $koneksi->query("INSERT INTO revisi_obat (kode_obat, nama_obat, jumlah, keterangan, tanggal, petugas, shift) VALUES ('$_GET[kode_obat]', '$getDataSingle[obat_master]', '$revisiStokSeharusnya', 'Revisi IT', NOW(), 'IT Solverra', 'Pagi')");
}
