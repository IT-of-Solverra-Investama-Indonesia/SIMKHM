<?php
    // Lakukan koneksi ke database dan setel zona waktu
    include 'function.php'; // Sesuaikan dengan file koneksi yang sesuai dengan konfigurasi database kamu
    date_default_timezone_set("Asia/Jakarta");
    
    // Ambil tanggal yang dipilih dari POST
    $tanggal = $_POST['tanggal'];
    $date = date('Ymd', strtotime($tanggal))+0;
    $time = date('Hi', strtotime($tanggal))-300;

    // Cek Apakah tgltab di tgl trsebut ada 
    $qu=mysqli_query($koneksi, "SELECT tgl FROM tgltab WHERE tgl=$date");
    $hasil=mysqli_num_rows($qu);

    // Jika Belum ada tgltab pada tanggal itu, akan menggenearet sesuai tanggal itu 
    if ($hasil==0 or $hasil=='NULL') {
                
        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p001', '$date+p001', 'no: 1, shift PAGI, masuk jam: 07.30-08.00', 800)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p002', '$date+p002', 'no: 2, shift PAGI, masuk jam: 07.30-08.00', 800)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p003', '$date+p003', 'no: 3, shift PAGI, masuk jam: 07.30-08.00', 800)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p004', '$date+p004', 'no: 4, shift PAGI, masuk jam: 07.30-08.00', 800)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p005', '$date+p005', 'no: 5, shift PAGI, masuk jam: 07.30-08.00', 800)");

        

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p006', '$date+p006', 'no: 6, shift PAGI, masuk jam: 07.45-08.15', 815)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p007', '$date+p007', 'no: 7, shift PAGI, masuk jam: 07.45-08.15', 815)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p008', '$date+p008', 'no: 8, shift PAGI, masuk jam: 07.45-08.15', 815)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p009', '$date+p009', 'no: 9, shift PAGI, masuk jam: 07.45-08.15', 815)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p010', '$date+p010', 'no: 10, shift PAGI, masuk jam: 07.45-08.15', 815)");

        

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p011', '$date+p011', 'no: 11, shift PAGI, masuk jam: 08.15-08.30', 830)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p012', '$date+p012', 'no: 12, shift PAGI, masuk jam: 08.15-08.30', 830)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p013', '$date+p013', 'no: 13, shift PAGI, masuk jam: 08.15-08.30', 830)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p014', '$date+p014', 'no: 14, shift PAGI, masuk jam: 08.15-08.30', 830)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p015', '$date+p015', 'no: 15, shift PAGI, masuk jam: 08.15-08.30', 830)");

        

        

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p016', '$date+p016', 'no: 16, shift PAGI, masuk jam: 09.30-09.45', 945)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p017', '$date+p017', 'no: 17, shift PAGI, masuk jam: 09.30-09.45', 945)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p018', '$date+p018', 'no: 18, shift PAGI, masuk jam: 09.30-09.45', 945)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p019', '$date+p019', 'no: 19, shift PAGI, masuk jam: 09.30-09.45', 945)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p020', '$date+p020', 'no: 20, shift PAGI, masuk jam: 09.30-09.45', 945)");

        

        

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p021', '$date+p021', 'no: 21, shift PAGI, masuk jam: 09.45-10.00', 1000)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p022', '$date+p022', 'no: 22, shift PAGI, masuk jam: 09.45-10.00', 1000)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p023', '$date+p023', 'no: 23, shift PAGI, masuk jam: 09.45-10.00', 1000)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p024', '$date+p024', 'no: 24, shift PAGI, masuk jam: 09.45.10.00', 1000)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p025', '$date+p025', 'no: 25, shift PAGI, masuk jam: 09.45-10.00', 1000)");

        

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p026', '$date+p026', 'no: 26, shift PAGI, masuk jam: 10.00-10.15', 1015)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p027', '$date+p027', 'no: 27, shift PAGI, masuk jam: 10.00-10.15', 1015)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p028', '$date+p028', 'no: 28, shift PAGI, masuk jam:10.00-10.15', 1015)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p029', '$date+p029', 'no: 29, shift PAGI, masuk jam: 10.00-10.15', 1015)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p030', '$date+p030', 'no: 30, shift PAGI, masuk jam: 10.00-10.15', 1015)");

        

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p031', '$date+p031', 'no: 31, shift PAGI, masuk jam: 10.15-10.30', 1030)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p032', '$date+p032', 'no: 32, shift PAGI, masuk jam: 10.15-10.30', 1030)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p033', '$date+p033', 'no: 33, shift PAGI, masuk jam:10.15-10.30', 1030)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p034', '$date+p034', 'no: 34, shift PAGI, masuk jam: 10.15-10.30', 1030)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p035', '$date+p035', 'no: 35, shift PAGI, masuk jam: 10.15-10.30', 1030)");

        

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p036', '$date+p036', 'no: 36, shift PAGI, masuk jam: 10.30-10.45', 1045)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p037', '$date+p037', 'no: 37, shift PAGI, masuk jam: 10.30-10.45', 1045)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p038', '$date+p038', 'no: 38, shift PAGI, masuk jam:10.30-10.45', 1045)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p039', '$date+p039', 'no: 39, shift PAGI, masuk jam: 10.30-10.45', 1045)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p040', '$date+p040', 'no: 40, shift PAGI, masuk jam: 10.30-10.45', 1045)");

        

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p041', '$date+p041', 'no: 41, shift PAGI, masuk jam: 10.45-11.00', 1100)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p042', '$date+p042', 'no: 42, shift PAGI, masuk jam: 1045-11.00', 1100)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p043', '$date+p043', 'no: 43, shift PAGI, masuk jam:10.45-11.00', 1100)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p044', '$date+p044', 'no: 44, shift PAGI, masuk jam: 1045-11.00', 1100)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p045', '$date+p045', 'no: 45, shift PAGI, masuk jam: 10.45-11.00', 1100)");

        

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p046', '$date+p046', 'no: 46, shift PAGI, masuk jam: 11.00-12.00', 1200)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p047', '$date+p047', 'no: 47, shift PAGI, masuk jam: 11.00-12.00', 1200)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p048', '$date+p048', 'no: 48, shift PAGI, masuk jam:11.00-12.00', 1200)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p049', '$date+p049', 'no: 49, shift PAGI, masuk jam: 11.00-12.00', 1200)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p050', '$date+p050', 'no: 50, shift PAGI, masuk jam: 11.00-12.00', 1200)");

        



        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p051', '$date+p051', 'no: 51, shift PAGI, masuk jam: 11.00-12.00', 1200)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p052', '$date+p052', 'no: 52, shift PAGI, masuk jam: 11.00-12.00', 1200)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p053', '$date+p053', 'no: 53, shift PAGI, masuk jam:11.00-12.00', 1200)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p054', '$date+p054', 'no: 54, shift PAGI, masuk jam: 11.00-12.00', 1200)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p055', '$date+p055', 'no: 55, shift PAGI, masuk jam: 11.00-12.00', 1200)");



        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p056', '$date+p056', 'no: 56, shift PAGI, masuk jam: 11.00-12.00', 1200)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p057', '$date+p057', 'no: 57, shift PAGI, masuk jam: 11.00-12.00', 1200)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p058', '$date+p058', 'no: 58, shift PAGI, masuk jam:11.00-12.00', 1200)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p059', '$date+p059', 'no: 59, shift PAGI, masuk jam: 11.00-12.00', 1200)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p060', '$date+p060', 'no: 60, shift PAGI, masuk jam: 11.00-12.00', 1200)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p061', '$date+p061', 'no: 61, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p062', '$date+p062', 'no: 62, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p063', '$date+p063', 'no: 63, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p064', '$date+p064', 'no: 64, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p065', '$date+p065', 'no: 65, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p066', '$date+p066', 'no: 66, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p067', '$date+p067', 'no: 67, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p068', '$date+p068', 'no: 68, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p069', '$date+p069', 'no: 69, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p070', '$date+p070', 'no: 70, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p071', '$date+p071', 'no: 71, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p072', '$date+p072', 'no: 72, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p073', '$date+p073', 'no: 73, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p074', '$date+p074', 'no: 74, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p075', '$date+p075', 'no: 75, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p076', '$date+p076', 'no: 76, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p077', '$date+p077', 'no: 77, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p078', '$date+p078', 'no: 78, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p079', '$date+p079', 'no: 79, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p080', '$date+p080', 'no: 80, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p081', '$date+p081', 'no: 81, shift PAGI, masuk jam: 11.00-12.00', 1300)");



        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p082', '$date+p082', 'no: 82, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p083', '$date+p083', 'no: 83, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p084', '$date+p084', 'no: 84, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p085', '$date+p085', 'no: 85, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p086', '$date+p086', 'no: 86, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p087', '$date+p087', 'no: 87, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p088', '$date+p088', 'no: 88, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p089', '$date+p089', 'no: 89, shift PAGI, masuk jam: 11.00-12.00', 1300)");



        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p090', '$date+p090', 'no: 90, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p091', '$date+p091', 'no: 91, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p092', '$date+p092', 'no: 92, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p093', '$date+p093', 'no: 93, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p094', '$date+p094', 'no: 94, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p095', '$date+p095', 'no: 95, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p096', '$date+p096', 'no: 96, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p097', '$date+p097', 'no: 97, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p098', '$date+p098', 'no: 98, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p099', '$date+p099', 'no: 99, shift PAGI, masuk jam: 11.00-12.00', 1300)");



        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p100', '$date+p100', 'no: 100, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p101', '$date+p101', 'no: 101, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p102', '$date+p102', 'no: 102, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p103', '$date+p103', 'no: 103, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p104', '$date+p104', 'no: 104, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p105', '$date+p105', 'no: 105, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p106', '$date+p106', 'no: 106, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p107', '$date+p107', 'no: 107, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p108', '$date+p108', 'no: 108, shift PAGI, masuk jam: 11.00-12.00', 1300)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'pagi', 'p109', '$date+p109', 'no: 109, shift PAGI, masuk jam: 11.00-12.00', 1300)");





        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's001', '$date+s001', 'no: 1, shift SORE, masuk jam: 16.00-16.10', 1610)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's002', '$date+s002', 'no: 2, shift SORE, masuk jam: 16.00-16.10', 1610)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's003', '$date+s003', 'no: 3, shift SORE, masuk jam: 16.00-16.10', 1610)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's004', '$date+s004', 'no: 4, shift SORE, masuk jam: 16.00-16.10', 1610)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's005', '$date+s005', 'no: 5, shift SORE, masuk jam: 16.00-16.10', 1610)");

        

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's006', '$date+s006', 'no: 6, shift SORE, masuk jam: 16.15-16.30', 1630)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's007', '$date+s007', 'no: 7, shift SORE, masuk jam: 16.15-16.30', 1630)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's008', '$date+s008', 'no: 8, shift SORE, masuk jam: 16.15-16.30', 1630)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's009', '$date+s009', 'no: 9, shift SORE, masuk jam: 16.15-16.30', 1630)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's010', '$date+s010', 'no: 10, shift SORE, masuk jam: 16.15-16.30', 1630)");

        

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's011', '$date+s011', 'no: 11, shift SORE, masuk jam: 16.30-16.45', 1645)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's012', '$date+s012', 'no: 12, shift SORE, masuk jam: 16.30-16.45', 1645)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's013', '$date+s013', 'no: 13, shift SORE, masuk jam: 16.30-16.45', 1645)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's014', '$date+s014', 'no: 14, shift SORE, masuk jam: 16.30-16.45', 1645)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's015', '$date+s015', 'no: 15, shift SORE, masuk jam: 16.30-16.45', 1645)");

        

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's016', '$date+s016', 'no: 16, shift SORE, masuk jam: 16.45-17.00', 1700)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's017', '$date+s017', 'no: 17, shift SORE, masuk jam: 16.45-17.00', 1700)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's018', '$date+s018', 'no: 18, shift SORE, masuk jam: 16.45-17.00', 1700)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's019', '$date+s019', 'no: 19, shift SORE, masuk jam: 16.45-17.00', 1700)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's020', '$date+s020', 'no: 20, shift SORE, masuk jam: 16.45-17.00', 1700)");

        

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's021', '$date+s021', 'no: 21, shift SORE, masuk jam: 17.00-17.15', 1715)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's022', '$date+s022', 'no: 22, shift SORE, masuk jam: 17.00-17.15', 1715)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's023', '$date+s023', 'no: 23, shift SORE, masuk jam: 17.00-17.15', 1715)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's024', '$date+s024', 'no: 24, shift SORE, masuk jam: 17.00-17.15', 1715)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's025', '$date+s025', 'no: 25, shift SORE, masuk jam: 17.00-17.15', 1715)");

        

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's026', '$date+s026', 'no: 26, shift SORE, masuk jam: 17.15-17.30', 1730)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's027', '$date+s027', 'no: 27, shift SORE, masuk jam: 17.15-17.30', 1730)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's028', '$date+s028', 'no: 28, shift SORE, masuk jam: 17.15-17.30', 1730)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's029', '$date+s029', 'no: 29, shift SORE, masuk jam: 17.15-17.30', 1730)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's030', '$date+s030', 'no: 30, shift SORE, masuk jam: 17.15-17.30', 1730)");

        

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's031', '$date+s031', 'no: 31, shift SORE, masuk jam: 17.30-17.45', 1745)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's032', '$date+s032', 'no: 32, shift SORE, masuk jam: 17.30-17.45', 1745)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's033', '$date+s033', 'no: 33, shift SORE, masuk jam: 17.30-17.45', 1745)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's034', '$date+s034', 'no: 34, shift SORE, masuk jam: 17.30-17.45', 1745)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's035', '$date+s035', 'no: 35, shift SORE, masuk jam: 17.30-17.45', 1745)");

        

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's036', '$date+s036', 'no: 36, shift SORE, masuk jam: 17.45-18.00', 1800)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's037', '$date+s037', 'no: 37, shift SORE, masuk jam: 17.45-18.00', 1800)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's038', '$date+s038', 'no: 38, shift SORE, masuk jam: 17.45-18.00', 1800)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's039', '$date+s039', 'no: 39, shift SORE, masuk jam: 17.45-18.00', 1800)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's040', '$date+s040', 'no: 40, shift SORE, masuk jam: 17.45-18.00', 1800)");

        

        

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's041', '$date+s041', 'no: 41, shift SORE, masuk jam: 18.30-18.45', 1845)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's042', '$date+s042', 'no: 42, shift SORE, masuk jam: 18.30-18.45', 1845)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's043', '$date+s043', 'no: 43, shift SORE, masuk jam: 18.30-18.45', 1845)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's044', '$date+s044', 'no: 44, shift SORE, masuk jam: 18.30-18.45', 1845)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's045', '$date+s045', 'no: 45, shift SORE, masuk jam: 18.30-18.45', 1845)");

        

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's046', '$date+s046', 'no: 46, shift SORE, masuk jam: 18.45-19.00', 1900)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's047', '$date+s047', 'no: 47, shift SORE, masuk jam: 18.45-19.00', 1900)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's048', '$date+s048', 'no: 48, shift SORE, masuk jam: 18.45-19.00', 1900)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's049', '$date+s049', 'no: 49, shift SORE, masuk jam: 18.45-19.00', 1900)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's050', '$date+s050', 'no: 50, shift SORE, masuk jam: 18.45-19.00', 1900)");

        

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's051', '$date+s051', 'no: 51, shift SORE, masuk jam: 19.00-19.15', 1915)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's052', '$date+s052', 'no: 52, shift SORE, masuk jam: 19.00-19.15', 1915)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's053', '$date+s053', 'no: 53, shift SORE, masuk jam: 19.00-19.15', 1915)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's054', '$date+s054', 'no: 54, shift SORE, masuk jam: 19.00-19.15', 1915)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's055', '$date+s055', 'no: 55, shift SORE, masuk jam: 19.00-19.15', 1915)");

        

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's056', '$date+s056', 'no: 56, shift SORE, masuk jam: 19.15-19.30', 1930)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's057', '$date+s057', 'no: 57, shift SORE, masuk jam: 19.15-19.30', 1930)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's058', '$date+s058', 'no: 58, shift SORE, masuk jam: 19.15-19.30', 1930)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's059', '$date+s059', 'no: 59, shift SORE, masuk jam: 19.15-19.30', 1930)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's060', '$date+s060', 'no: 60, shift SORE, masuk jam: 19.15-19.30', 1930)");

        

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's061', '$date+s061', 'no: 61, shift SORE, masuk jam: 19.30-19.45', 1945)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's062', '$date+s062', 'no: 62, shift SORE, masuk jam: 19.30-19.45', 1945)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's063', '$date+s063', 'no: 63, shift SORE, masuk jam: 19.30-19.45', 1945)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's064', '$date+s064', 'no: 64, shift SORE, masuk jam: 19.30-19.45', 1945)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's065', '$date+s065', 'no: 65, shift SORE, masuk jam: 19.30-19.45', 1945)");



        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's066', '$date+s066', 'no: 66, shift SORE, masuk jam: 19.45-20.00', 2000)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's067', '$date+s067', 'no: 67, shift SORE, masuk jam: 19.45-20.00', 2000)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's068', '$date+s068', 'no: 68, shift SORE, masuk jam: 19.45-20.00', 2000)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's069', '$date+s069', 'no: 69, shift SORE, masuk jam: 19.45-20.00', 2000)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's070', '$date+s070', 'no: 70, shift SORE, masuk jam: 19.45-20.00', 2000)");



        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's071', '$date+s071', 'no: 71, shift SORE, masuk jam: 20.00-20.15', 2015)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's072', '$date+s072', 'no: 72, shift SORE, masuk jam: 20.00-20.15', 2015)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's073', '$date+s073', 'no: 73, shift SORE, masuk jam: 20.00-20.15', 2015)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's074', '$date+s074', 'no: 74, shift SORE, masuk jam: 20.00-20.15', 2015)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's075', '$date+s075', 'no: 75, shift SORE, masuk jam: 20.00-20.15', 2015)");



        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's076', '$date+s076', 'no: 76, shift SORE, masuk jam: 20.15-20.30', 2030)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's077', '$date+s077', 'no: 77, shift SORE, masuk jam: 20.15-20.30', 2030)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's078', '$date+s078', 'no: 78, shift SORE, masuk jam: 20.15-20.30', 2030)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's079', '$date+s079', 'no: 79, shift SORE, masuk jam: 20.15-20.30', 2030)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's080', '$date+s080', 'no: 80, shift SORE, masuk jam: 20.15-20.30', 2030)");



        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's081', '$date+s081', 'no: 81, shift SORE, masuk jam: 20.15-20.30', 2030)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's082', '$date+s082', 'no: 82, shift SORE, masuk jam: 20.15-20.30', 2030)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's083', '$date+s083', 'no: 83, shift SORE, masuk jam: 20.15-20.30', 2030)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's084', '$date+s084', 'no: 84, shift SORE, masuk jam: 20.15-20.30', 2030)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's085', '$date+s085', 'no: 85, shift SORE, masuk jam: 20.15-20.30', 2030)");





        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's086', '$date+s086', 'no: 86, shift SORE, masuk jam: 20.15-20.30', 2030)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's087', '$date+s087', 'no: 87, shift SORE, masuk jam: 20.15-20.30', 2030)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's088', '$date+s088', 'no: 88, shift SORE, masuk jam: 20.15-20.30', 2030)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's089', '$date+s089', 'no: 89, shift SORE, masuk jam: 20.15-20.30', 2030)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's090', '$date+s090', 'no: 90, shift SORE, masuk jam: 20.15-20.30', 2030)");



        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's091', '$date+s091', 'no: 91, shift SORE, masuk jam: 20.15-20.30', 2030)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's092', '$date+s092', 'no: 92, shift SORE, masuk jam: 20.15-20.30', 2030)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's093', '$date+s093', 'no: 93, shift SORE, masuk jam: 20.15-20.30', 2030)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's094', '$date+s094', 'no: 94, shift SORE, masuk jam: 20.15-20.30', 2030)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's095', '$date+s095', 'no: 95, shift SORE, masuk jam: 20.15-20.30', 2030)");





        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's096', '$date+s096', 'no: 96, shift SORE, masuk jam: 20.15-20.30', 2030)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's097', '$date+s097', 'no: 97, shift SORE, masuk jam: 20.15-20.30', 2030)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's089', '$date+s098', 'no: 98, shift SORE, masuk jam: 20.15-20.30', 2030)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's099', '$date+s099', 'no: 99, shift SORE, masuk jam: 20.15-20.30', 2030)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's0100', '$date+s0100', 'no: 100, shift SORE, masuk jam: 20.15-20.30', 2030)");



        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's0101', '$date+s0101', 'no: 101, shift SORE, masuk jam: 20.15-20.30', 2030)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's0102', '$date+s0102', 'no: 102, shift SORE, masuk jam: 20.15-20.30', 2030)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's0103', '$date+s0103', 'no: 103, shift SORE, masuk jam: 20.15-20.30', 2030)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's0104', '$date+s0104', 'no: 104, shift SORE, masuk jam: 20.15-20.30', 2030)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's0105', '$date+s0105', 'no: 105, shift SORE, masuk jam: 20.15-20.30', 2030)");



        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's0106', '$date+s0106', 'no: 106, shift SORE, masuk jam: 20.15-20.30', 2030)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's0107', '$date+s0107', 'no: 107, shift SORE, masuk jam: 20.15-20.30', 2030)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's0108', '$date+s0108', 'no: 108, shift SORE, masuk jam: 20.15-20.30', 2030)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's0109', '$date+s0109', 'no: 109, shift SORE, masuk jam: 20.15-20.30', 2030)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's0110', '$date+s0110', 'no: 110, shift SORE, masuk jam: 20.15-20.30', 2030)");



        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's0111', '$date+s0111', 'no: 111, shift SORE, masuk jam: 20.15-20.30', 2045)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's0112', '$date+s0112', 'no:0112, shift SORE, masuk jam: 20.15-20.30', 2045)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's0113', '$date+s0113', 'no: 113, shift SORE, masuk jam: 20.15-20.30', 2045)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's0114', '$date+s0114', 'no: 114, shift SORE, masuk jam: 20.15-20.30', 2045)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'sore', 's0115', '$date+s0115', 'no: 115, shift SORE, masuk jam: 20.15-20.30', 2045)");



        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'malam', 'm01', '$date+m01', 'no: 1, shift MALAM, masuk jam: 22.15-22.30', 2110)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'malam', 'm01', '$date+m02', 'no: 2, shift MALAM, masuk jam: 22.15-22.30', 2110)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'malam', 'm01', '$date+m03', 'no: 3, shift MALAM, masuk jam: 22.15-22.30', 2110)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'malam', 'm01', '$date+m04', 'no: 4, shift MALAM, masuk jam: 22.15-22.30', 2110)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'malam', 'm01', '$date+m05', 'no: 5, shift MALAM, masuk jam: 22.15-22.30', 2110)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'malam', 'm01', '$date+m06', 'no: 6, shift MALAM, masuk jam: 22.15-22.30', 2110)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'malam', 'm01', '$date+m07', 'no: 7, shift MALAM, masuk jam: 22.15-22.30', 2110)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'malam', 'm01', '$date+m08', 'no: 8, shift MALAM, masuk jam: 22.15-22.30', 2110)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'malam', 'm01', '$date+m09', 'no: 9, shift MALAM, masuk jam: 22.15-22.30', 2110)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'malam', 'm01', '$date+m010', 'no: 10, shift MALAM, masuk jam: 22.15-22.30', 2110)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'malam', 'm01', '$date+m011', 'no: 11, shift MALAM, masuk jam: 22.15-22.30', 2110)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'malam', 'm01', '$date+m012', 'no: 12, shift MALAM, masuk jam: 22.15-22.30', 2110)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'malam', 'm01', '$date+m013', 'no: 13, shift MALAM, masuk jam: 22.15-22.30', 2110)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'malam', 'm01', '$date+m014', 'no: 14, shift MALAM, masuk jam: 22.15-22.30', 2110)");

        mysqli_query($koneksi, "INSERT INTO `tgltab` (`tgl`, `no`, `shift`, `urut`, `kode`, `ket`, `jam`) VALUES ('$date', NULL, 'malam', 'm01', '$date+m015', 'no: 15, shift MALAM, masuk jam: 22.15-22.30', 2110)");

    }

    // Hapus yang tanggalnya 19700101, itu error
    $koneksi->query("DELETE FROM tgltab WHERE tgl='19700101'");
    mysqli_query($koneksi, "DELETE FROM tgltab WHERE tgl = '19700101'");

if (isset($_SESSION['shift'])) {
    if ($_SESSION['shift'] == 'Pagi') {
        $sif = 'pagi';
    } elseif ($_SESSION['shift'] == 'Sore') {
        $sif = 'sore';
    } elseif ($_SESSION['shift'] == 'Malam') {
        $sif = 'malam';
    }

    $whereShiftCondition = "AND tgltab.shift='$sif'";
}

// Query untuk mendapatkan antrian yang tersedia pada tanggal tersebut
$query = "SELECT kode, urut, ket FROM tgltab 
            WHERE NOT EXISTS(SELECT antrian FROM registrasi_rawat 
                            WHERE registrasi_rawat.kode = tgltab.kode) 
            AND tgl=$date AND jam>=$time $whereShiftCondition
            ORDER BY tgltab.no ASC";

    $result = mysqli_query($koneksi, $query);

    // Buat opsi untuk dropdown antrian
    $options = '<option value="">Silahkan Pilih Antrian</option>';
    while($row = mysqli_fetch_assoc($result)) {
        $options .= '<option value="'.$row['urut'].'">'.$row['ket'].'</option>';
    }

    echo $options;
?>