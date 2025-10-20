-- =====================================================
-- QUICK SETUP - ABSENSI DOKTER MULTI-UNIT (VERSI 2.0)
-- =====================================================
-- Copy dan paste LANGSUNG ke phpMyAdmin > SQL tab
-- Pastikan database sudah dipilih (klinik_master)
-- =====================================================
-- Update: 17 Januari 2025
-- Fitur: Multi-unit support (KHM 1, KHM 2, KHM 3, KHM 4)
-- =====================================================

-- 1. Drop tabel lama jika ada (HATI-HATI: DATA AKAN HILANG!)
DROP TABLE IF EXISTS `absensi_dokter`;

-- 2. Buat tabel absensi_dokter dengan struktur lengkap
CREATE TABLE `absensi_dokter` (
  `id_absensi` int(11) NOT NULL AUTO_INCREMENT,
  `id_tenaga` varchar(50) NOT NULL,
  `nama_dokter` varchar(100) NOT NULL,
  `unit` varchar(20) NOT NULL DEFAULT 'KHM 1',
  `tanggal` date NOT NULL,
  `waktu` time NOT NULL,
  `waktu_seharusnya` time NOT NULL DEFAULT '00:00:00',
  `shift` varchar(50) NOT NULL,
  `tipe_absen` enum('masuk','pulang') NOT NULL,
  `foto` varchar(255) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_absensi`),
  KEY `idx_id_tenaga` (`id_tenaga`),
  KEY `idx_tanggal` (`tanggal`),
  KEY `idx_tipe_absen` (`tipe_absen`),
  KEY `idx_unit` (`unit`),
  KEY `idx_tenaga_tanggal` (`id_tenaga`, `tanggal`),
  KEY `idx_tenaga_created` (`id_tenaga`, `created_at`),
  KEY `idx_lokasi` (`latitude`, `longitude`),
  KEY `idx_unit_tenaga_tanggal` (`unit`, `id_tenaga`, `tanggal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 3. Insert data dummy untuk testing (OPTIONAL - Uncomment untuk testing)
/*
INSERT INTO `absensi_dokter` 
(`id_tenaga`, `nama_dokter`, `unit`, `tanggal`, `waktu`, `waktu_seharusnya`, `shift`, `tipe_absen`, `foto`, `latitude`, `longitude`) 
VALUES 
-- KHM 1 - Wonorejo
('DKT001', 'Dr. Budi Santoso', 'KHM 1', CURDATE(), '07:05:00', '07:00:00', 'Pagi (07:00 - 14:00)', 'masuk', 'absen_DKT001_20250117070500_abc123.jpg', -7.250445, 112.768845),
('DKT001', 'Dr. Budi Santoso', 'KHM 1', CURDATE(), '14:02:00', '14:00:00', 'Pagi (07:00 - 14:00)', 'pulang', 'absen_DKT001_20250117140200_def456.jpg', -7.250450, 112.768850),

-- KHM 2 - Klakah
('DKT002', 'Dr. Siti Aminah', 'KHM 2', CURDATE(), '14:15:00', '14:00:00', 'Siang (14:00 - 21:00)', 'masuk', 'absen_DKT002_20250117141500_ghi789.jpg', -8.123456, 113.456789),
('DKT002', 'Dr. Siti Aminah', 'KHM 2', CURDATE(), '21:05:00', '21:00:00', 'Siang (14:00 - 21:00)', 'pulang', 'absen_DKT002_20250117210500_jkl012.jpg', -8.123460, 113.456790),

-- KHM 3 - Tunjung
('DKT003', 'Dr. Ahmad Fauzi', 'KHM 3', CURDATE(), '21:10:00', '21:00:00', 'Malam (21:00 - 07:00)', 'masuk', 'absen_DKT003_20250117211000_mno345.jpg', -7.987654, 112.345678),

-- KHM 4 - Kunir
('DKT004', 'Dr. Dewi Lestari', 'KHM 4', CURDATE(), '07:00:00', '07:00:00', 'Pagi (07:00 - 14:00)', 'masuk', 'absen_DKT004_20250117070000_pqr678.jpg', -8.234567, 113.567890);
*/

-- 4. Verifikasi tabel berhasil dibuat
DESCRIBE absensi_dokter;

-- 5. Cek data (akan kosong jika tidak insert dummy data)
SELECT * FROM absensi_dokter ORDER BY created_at DESC LIMIT 10;

-- =====================================================
-- QUERY ANALISIS & MONITORING (READY TO USE)
-- =====================================================

-- [A] ABSENSI HARI INI
-- Lihat semua absensi hari ini (semua unit)
-- SELECT unit, nama_dokter, waktu, tipe_absen, shift FROM absensi_dokter WHERE tanggal = CURDATE() ORDER BY unit, waktu;

-- [B] REKAP PER UNIT
-- Statistik absensi per unit hari ini
-- SELECT unit, COUNT(*) as total_absensi, SUM(CASE WHEN tipe_absen='masuk' THEN 1 ELSE 0 END) as total_masuk, SUM(CASE WHEN tipe_absen='pulang' THEN 1 ELSE 0 END) as total_pulang FROM absensi_dokter WHERE tanggal = CURDATE() GROUP BY unit ORDER BY unit;

-- [C] DOKTER YANG BELUM PULANG
-- Cari dokter yang sudah masuk tapi belum pulang hari ini
-- SELECT a1.unit, a1.nama_dokter, a1.waktu as waktu_masuk FROM absensi_dokter a1 WHERE a1.tanggal = CURDATE() AND a1.tipe_absen = 'masuk' AND NOT EXISTS (SELECT 1 FROM absensi_dokter a2 WHERE a2.id_tenaga = a1.id_tenaga AND a2.tanggal = CURDATE() AND a2.tipe_absen = 'pulang' AND a2.created_at > a1.created_at) ORDER BY a1.unit, a1.nama_dokter;

-- [D] KETERLAMBATAN HARI INI
-- Dokter yang terlambat masuk hari ini (semua unit)
-- SELECT unit, nama_dokter, waktu as waktu_absen, waktu_seharusnya, TIMESTAMPDIFF(MINUTE, waktu_seharusnya, waktu) as terlambat_menit, shift FROM absensi_dokter WHERE tipe_absen = 'masuk' AND waktu > waktu_seharusnya AND tanggal = CURDATE() ORDER BY terlambat_menit DESC;

-- [E] REKAP BULANAN PER UNIT
-- Rekap kehadiran bulan ini per unit
-- SELECT unit, COUNT(DISTINCT id_tenaga) as jumlah_dokter, COUNT(CASE WHEN tipe_absen='masuk' THEN 1 END) as total_masuk, COUNT(CASE WHEN tipe_absen='pulang' THEN 1 END) as total_pulang FROM absensi_dokter WHERE MONTH(tanggal) = MONTH(CURDATE()) AND YEAR(tanggal) = YEAR(CURDATE()) GROUP BY unit ORDER BY unit;

-- [F] REKAP PER DOKTER (BULAN INI)
-- Detail absensi per dokter bulan ini
-- SELECT unit, nama_dokter, COUNT(CASE WHEN tipe_absen='masuk' THEN 1 END) as total_masuk, COUNT(CASE WHEN tipe_absen='pulang' THEN 1 END) as total_pulang, SUM(CASE WHEN tipe_absen='masuk' AND waktu > waktu_seharusnya THEN 1 ELSE 0 END) as jumlah_terlambat FROM absensi_dokter WHERE MONTH(tanggal) = MONTH(CURDATE()) AND YEAR(tanggal) = YEAR(CURDATE()) GROUP BY unit, nama_dokter, id_tenaga ORDER BY unit, nama_dokter;

-- [G] ABSENSI DENGAN LOKASI GPS
-- Lihat 20 absensi terakhir dengan link Google Maps
-- SELECT unit, nama_dokter, tanggal, waktu, tipe_absen, CONCAT('https://www.google.com/maps?q=', latitude, ',', longitude) as google_maps_link FROM absensi_dokter WHERE latitude IS NOT NULL AND longitude IS NOT NULL ORDER BY created_at DESC LIMIT 20;

-- [H] ANALISA KETERLAMBATAN PER UNIT
-- Rata-rata keterlambatan per unit (bulan ini)
-- SELECT unit, COUNT(*) as total_absen_masuk, SUM(CASE WHEN waktu > waktu_seharusnya THEN 1 ELSE 0 END) as jumlah_terlambat, ROUND(AVG(TIMESTAMPDIFF(MINUTE, waktu_seharusnya, waktu)), 2) as rata_rata_selisih_menit, ROUND((SUM(CASE WHEN waktu > waktu_seharusnya THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) as persentase_terlambat FROM absensi_dokter WHERE tipe_absen = 'masuk' AND MONTH(tanggal) = MONTH(CURDATE()) AND YEAR(tanggal) = YEAR(CURDATE()) GROUP BY unit ORDER BY unit;

-- [I] TOP 10 DOKTER PALING RAJIN
-- Dokter dengan kehadiran terbaik bulan ini (semua unit)
-- SELECT unit, nama_dokter, COUNT(CASE WHEN tipe_absen='masuk' THEN 1 END) as total_masuk, SUM(CASE WHEN tipe_absen='masuk' AND waktu <= waktu_seharusnya THEN 1 ELSE 0 END) as masuk_tepat_waktu, ROUND((SUM(CASE WHEN tipe_absen='masuk' AND waktu <= waktu_seharusnya THEN 1 ELSE 0 END) / COUNT(CASE WHEN tipe_absen='masuk' THEN 1 END)) * 100, 2) as persentase_tepat_waktu FROM absensi_dokter WHERE MONTH(tanggal) = MONTH(CURDATE()) AND YEAR(tanggal) = YEAR(CURDATE()) GROUP BY unit, nama_dokter, id_tenaga HAVING total_masuk > 0 ORDER BY persentase_tepat_waktu DESC, total_masuk DESC LIMIT 10;

-- [J] DOKTER YANG PINDAH UNIT
-- Deteksi dokter yang absen di berbagai unit (untuk audit)
-- SELECT id_tenaga, nama_dokter, GROUP_CONCAT(DISTINCT unit ORDER BY unit SEPARATOR ', ') as units, COUNT(DISTINCT unit) as jumlah_unit FROM absensi_dokter GROUP BY id_tenaga, nama_dokter HAVING COUNT(DISTINCT unit) > 1 ORDER BY jumlah_unit DESC;

-- =====================================================
-- MAINTENANCE QUERY (OPTIONAL)
-- =====================================================

-- Hapus data lebih dari 1 tahun (jalankan secara berkala)
-- DELETE FROM absensi_dokter WHERE tanggal < DATE_SUB(CURDATE(), INTERVAL 1 YEAR);

-- Optimize table (untuk performa)
-- OPTIMIZE TABLE absensi_dokter;

-- Backup data (export dulu sebelum hapus)
-- SELECT * FROM absensi_dokter INTO OUTFILE '/tmp/backup_absensi_dokter.csv' FIELDS TERMINATED BY ',' ENCLOSED BY '"' LINES TERMINATED BY '\n';

-- =====================================================
-- INFORMASI TAMBAHAN
-- =====================================================
-- URL Akses:
-- - KHM 1: https://simkhm.id/wonorejo/admin/dist/index.php?halaman=absensidokter
-- - KHM 2: https://simkhm.id/klakah/admin/dist/index.php?halaman=absensidokter
-- - KHM 3: https://simkhm.id/tunjung/admin/dist/index.php?halaman=absensidokter
-- - KHM 4: https://simkhm.id/kunir/admin/dist/index.php?halaman=absensidokter
-- - Localhost: http://localhost/SIMKHM/admin/dist/index.php?halaman=absensidokter
--
-- Riwayat:
-- - https://simkhm.id/[unit]/admin/dist/index.php?halaman=absensidokter_history
-- =====================================================
-- SELESAI! TABLE SIAP DIGUNAKAN
-- =====================================================

-- =====================================================
-- QUERY TAMBAHAN (OPTIONAL)
-- =====================================================

-- Lihat absensi hari ini semua dokter
-- SELECT * FROM absensi_dokter WHERE tanggal = CURDATE() ORDER BY waktu;

-- Lihat dokter yang sudah absen masuk tapi belum pulang hari ini
-- SELECT DISTINCT a1.nama_dokter, a1.waktu as waktu_masuk
-- FROM absensi_dokter a1
-- WHERE a1.tanggal = CURDATE() 
-- AND a1.tipe_absen = 'masuk'
-- AND NOT EXISTS (
--     SELECT 1 FROM absensi_dokter a2 
--     WHERE a2.id_tenaga = a1.id_tenaga 
--     AND a2.tanggal = CURDATE() 
--     AND a2.tipe_absen = 'pulang'
--     AND a2.created_at > a1.created_at
-- );

-- Lihat rekap kehadiran bulan ini per dokter
-- SELECT 
--     nama_dokter,
--     COUNT(CASE WHEN tipe_absen = 'masuk' THEN 1 END) as total_masuk,
--     COUNT(CASE WHEN tipe_absen = 'pulang' THEN 1 END) as total_pulang
-- FROM absensi_dokter 
-- WHERE MONTH(tanggal) = MONTH(CURDATE()) 
-- AND YEAR(tanggal) = YEAR(CURDATE())
-- GROUP BY nama_dokter, id_tenaga;

-- Lihat dokter yang terlambat hari ini
-- SELECT 
--     nama_dokter, 
--     waktu as waktu_absen, 
--     waktu_seharusnya, 
--     TIMESTAMPDIFF(MINUTE, waktu_seharusnya, waktu) as terlambat_menit,
--     shift
-- FROM absensi_dokter 
-- WHERE tipe_absen = 'masuk' 
-- AND waktu > waktu_seharusnya
-- AND tanggal = CURDATE();

-- Lihat absensi dengan lokasi
-- SELECT 
--     nama_dokter, 
--     tanggal, 
--     waktu, 
--     tipe_absen,
--     CONCAT('https://www.google.com/maps?q=', latitude, ',', longitude) as google_maps_link
-- FROM absensi_dokter 
-- WHERE latitude IS NOT NULL 
-- AND longitude IS NOT NULL
-- ORDER BY created_at DESC
-- LIMIT 10;

-- Analisa keterlambatan per dokter (bulan ini)
-- SELECT 
--     nama_dokter,
--     COUNT(*) as total_absen_masuk,
--     SUM(CASE WHEN waktu > waktu_seharusnya THEN 1 ELSE 0 END) as jumlah_terlambat,
--     AVG(TIMESTAMPDIFF(MINUTE, waktu_seharusnya, waktu)) as rata_rata_selisih_menit
-- FROM absensi_dokter
-- WHERE tipe_absen = 'masuk'
-- AND MONTH(tanggal) = MONTH(CURDATE())
-- AND YEAR(tanggal) = YEAR(CURDATE())
-- GROUP BY nama_dokter, id_tenaga;

-- Hapus data absensi lebih dari 1 tahun (untuk maintenance)
-- DELETE FROM absensi_dokter WHERE tanggal < DATE_SUB(CURDATE(), INTERVAL 1 YEAR);

-- =====================================================
-- SELESAI!
-- =====================================================
-- Akses halaman: index.php?hal=absensi-dokter
-- =====================================================
