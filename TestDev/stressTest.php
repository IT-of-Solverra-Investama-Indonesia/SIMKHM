<?php
// Simpan di komputer lokal, jalankan via terminal: php test.php
$target = "https://personalstorage.my.id/";
$durasi = 60; // dalam detik
$akhir = time() + $durasi;

echo "Menjalankan stress test selama $durasi detik...\n";

while (time() < $akhir) {
    // Menggunakan file_get_contents atau curl untuk memukul server
    @file_get_contents($target);
}

echo "Simulasi selesai.\n";   
