<?php
// Set timezone Indonesia (WIB)
date_default_timezone_set('Asia/Jakarta');

// Fungsi untuk deteksi unit berdasarkan URL
function getUnitFromUrl()
{
    $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $url = strtolower($url);

    if (strpos($url, 'wonorejo') !== false) {
        return 'KHM 1';
    } elseif (strpos($url, 'klakah') !== false) {
        return 'KHM 2';
    } elseif (strpos($url, 'tunjung') !== false) {
        return 'KHM 3';
    } elseif (strpos($url, 'kunir') !== false) {
        return 'KHM 4';
    } else {
        // Default untuk localhost atau URL lain
        return 'KHM 1';
    }
}

// Ambil unit dari URL
$unit = getUnitFromUrl();

// Proses simpan absensi
if (isset($_POST['simpan_absen'])) {
    $id_tenaga = $_SESSION['admin']['idadmin'];
    $nama_dokter = $_SESSION['admin']['namalengkap'];
    $shift = $_POST['shift'];
    $foto_base64 = $_POST['foto_absen'];
    $unit_absen = getUnitFromUrl();

    // Validasi shift
    if (empty($shift)) {
        echo "<script>alert('Shift harus diisi!'); window.location.href='index.php?halaman=absensidokter_history';</script>";
        exit;
    }

    // Validasi foto
    if (empty($foto_base64)) {
        echo "<script>alert('Foto absensi harus diambil!'); window.location.href='index.php?halaman=absensidokter_history';</script>";
        exit;
    }

    // Proses foto base64
    $foto_base64 = str_replace('data:image/jpeg;base64,', '', $foto_base64);
    $foto_base64 = str_replace(' ', '+', $foto_base64);
    $foto_data = base64_decode($foto_base64);

    // Generate nama file unik
    $timestamp = date('YmdHis');
    $random = substr(md5(uniqid(rand(), true)), 0, 8);
    $nama_file = "absen_{$id_tenaga}_{$timestamp}_{$random}.jpg";

    // Buat direktori jika belum ada
    $direktori = "../dokter/foto_absen_dokter/";
    if (!is_dir($direktori)) {
        mkdir($direktori, 0777, true);
    }

    $file_path = $direktori . $nama_file;

    // Simpan dan kompress foto
    $img = imagecreatefromstring($foto_data);
    if ($img !== false) {
        // Kompress dengan kualitas 60%
        imagejpeg($img, $file_path, 60);
        imagedestroy($img);

        // Simpan ke database
        $tanggal = date('Y-m-d');
        $waktu = date('H:i:s');

        // Cek absen terakhir untuk menentukan tipe absen
        $cek_terakhir = $koneksimaster->query("SELECT * FROM absensi_dokter WHERE id_tenaga='$id_tenaga' ORDER BY created_at DESC LIMIT 1");

        if ($cek_terakhir->num_rows > 0) {
            $absen_terakhir = $cek_terakhir->fetch_assoc();
            // Jika absen terakhir adalah 'masuk', maka sekarang 'pulang', dan sebaliknya
            $tipe_absen = ($absen_terakhir['tipe_absen'] == 'masuk') ? 'pulang' : 'masuk';
        } else {
            // Jika belum pernah absen, default ke 'masuk'
            $tipe_absen = 'masuk';
        }

        // Ambil lokasi
        $latitude = isset($_POST['latitude']) ? $_POST['latitude'] : null;
        $longitude = isset($_POST['longitude']) ? $_POST['longitude'] : null;

        // Tentukan waktu seharusnya berdasarkan shift dan tipe absen
        $waktu_seharusnya = '00:00:00';
        if ($shift == 'Pagi (07:00 - 14:00)') {
            $waktu_seharusnya = ($tipe_absen == 'masuk') ? '07:00:00' : '14:00:00';
        } elseif ($shift == 'Siang (14:00 - 21:00)') {
            $waktu_seharusnya = ($tipe_absen == 'masuk') ? '14:00:00' : '21:00:00';
        } elseif ($shift == 'Malam (21:00 - 07:00)') {
            $waktu_seharusnya = ($tipe_absen == 'masuk') ? '21:00:00' : '07:00:00';
        }

        // Insert absen baru ke database master
        $koneksimaster->query("INSERT INTO absensi_dokter 
            (id_tenaga, nama_dokter, tanggal, waktu, foto, shift, tipe_absen, latitude, longitude, waktu_seharusnya, unit) 
            VALUES 
            ('$id_tenaga', '$nama_dokter', '$tanggal', '$waktu', '$nama_file', '$shift', '$tipe_absen', '$latitude', '$longitude', '$waktu_seharusnya', '$unit_absen')");

        $pesan = ($tipe_absen == 'masuk') ? 'Absen masuk berhasil!' : 'Absen pulang berhasil!';
        echo "<script>alert('$pesan'); window.location.href='index.php?halaman=absensidokter_history';</script>";
    } else {
        echo "<script>alert('Gagal memproses foto!'); window.location.href='index.php?halaman=absensidokter_history';</script>";
    }
}

// Ambil data absensi hari ini
$id_tenaga = $_SESSION['admin']['idadmin'];
$tanggal_hari_ini = date('Y-m-d');
$absen_hari_ini = $koneksimaster->query("SELECT * FROM absensi_dokter WHERE id_tenaga='$id_tenaga' AND tanggal='$tanggal_hari_ini' ORDER BY created_at DESC");

// Cek absen terakhir untuk info tipe absen selanjutnya
$cek_terakhir = $koneksimaster->query("SELECT * FROM absensi_dokter WHERE id_tenaga='$id_tenaga' ORDER BY created_at DESC LIMIT 1");
if ($cek_terakhir->num_rows > 0) {
    $absen_terakhir = $cek_terakhir->fetch_assoc();
    $tipe_absen_next = ($absen_terakhir['tipe_absen'] == 'masuk') ? 'pulang' : 'masuk';
} else {
    $tipe_absen_next = 'masuk';
}

// Ambil riwayat absensi
$riwayat_absen = $koneksimaster->query("SELECT * FROM absensi_dokter WHERE id_tenaga='$id_tenaga' ORDER BY created_at DESC LIMIT 20");
?>

<style>
    #video {
        width: 100%;
        max-width: 640px;
        border: 3px solid #007bff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        transform: scaleX(-1);
        /* Hilangkan mirror effect */
    }

    #canvas {
        display: none;
    }

    #preview {
        width: 100%;
        max-width: 640px;
        border: 3px solid #28a745;
        border-radius: 12px;
        display: none;
        margin-top: 15px;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }

    .camera-container {
        text-align: center;
        margin: 30px 0;
        padding: 20px;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-radius: 15px;
    }

    .jam-realtime {
        font-size: 3.5rem;
        font-weight: bold;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-align: center;
        margin: 20px 0;
        font-family: 'Courier New', monospace;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
    }

    .tanggal-realtime {
        font-size: 1.4rem;
        color: #495057;
        text-align: center;
        margin-bottom: 25px;
        font-weight: 500;
    }

    .btn-camera {
        margin: 10px 5px;
        padding: 12px 30px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 25px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    .btn-camera:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
    }

    .status-absen {
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .status-belum {
        background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
        border: 2px solid #ffc107;
    }

    .status-sudah {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border: 2px solid #28a745;
    }

    .tipe-badge {
        display: inline-block;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: bold;
        margin-left: 10px;
    }

    .badge-masuk {
        background-color: #28a745;
        color: white;
    }

    .badge-pulang {
        background-color: #dc3545;
        color: white;
    }

    .next-absen-info {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        border: 2px solid #2196f3;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        text-align: center;
    }

    .next-absen-info h5 {
        color: #1976d2;
        margin-bottom: 5px;
    }

    .foto-absen-preview {
        max-width: 150px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        transition: transform 0.3s ease;
    }

    .foto-absen-preview:hover {
        transform: scale(1.05);
    }

    .lokasi-info {
        background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
        border: 2px solid #ff9800;
        padding: 12px;
        border-radius: 10px;
        margin-bottom: 20px;
        text-align: center;
        font-size: 0.9rem;
    }

    .lokasi-info i {
        color: #e65100;
    }

    .btn-switch-camera {
        position: absolute;
        top: 15px;
        right: 15px;
        z-index: 1000;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        border: 2px solid white;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-switch-camera:hover {
        background: rgba(0, 0, 0, 0.8);
        transform: rotate(180deg);
    }

    .video-wrapper {
        position: relative;
        display: inline-block;
    }
</style>
<form method="POST" id="form-absen">
    <div class="card shadow p-2 mb-1 bg-primary text-white">
        <h4 class="mb-0"><i class="bi bi-camera-fill"></i> Absensi Dokter</h4>
    </div>
    <div class="card shadow p-2 mb-1">
        <div class="jam-realtime mb-0" id="jam-realtime"></div>
        <div class="tanggal-realtime" id="tanggal-realtime"></div>
    </div>
    <div class="card shadow p-2 mb-1">
        <div class="row">
            <div class="col-md-12 ">
                <label class="form-label"><strong>Pilih Shift <span class="text-danger">*</span></strong></label>
                <select class="form-select" name="shift" id="shift" required>
                    <option value="">-- Pilih Shift --</option>
                    <option value="Pagi (07:00 - 14:00)">Pagi (07:00 - 14:00)</option>
                    <option value="Siang (14:00 - 21:00)">Siang (14:00 - 21:00)</option>
                    <option value="Malam (21:00 - 07:00)">Malam (21:00 - 07:00)</option>
                </select>
            </div>
        </div>
    </div>
    <!-- Kamera -->
    <div class="card shadow p-2 mb-1">
        <center>
            <h5 class="text-capitalize">Ambil Foto Absensi <?= $tipe_absen_next ?></h5>
            <div class="video-wrapper">
                <video id="video" class="w-100" autoplay></video>
                <button type="button" class="btn-switch-camera" id="switch-camera" title="Balik Kamera">
                    <i class="bi bi-arrow-repeat"></i>
                </button>
            </div>
            <canvas id="canvas" class="w-100"></canvas>
            <img id="preview" class="w-100" alt="Preview Foto">
            <div class="mt-3">
                <button type="button" class="btn btn-primary btn-camera" id="start-camera">
                    <i class="bi bi-camera"></i> Buka Kamera
                </button>
                <button type="button" class="btn btn-success btn-camera" id="capture" style="display:none;">
                    <i class="bi bi-camera-fill"></i> Ambil Foto
                </button>
                <button type="button" class="btn btn-warning btn-camera" id="retake" style="display:none;">
                    <i class="bi bi-arrow-counterclockwise"></i> Foto Ulang
                </button>
            </div>
        </center>
    </div>

    <input type="hidden" name="foto_absen" id="foto_absen">
    <input type="hidden" name="latitude" id="latitude">
    <input type="hidden" name="longitude" id="longitude">

    <div class="card shadow p-2">
        <div class="text-center">
            <button type="submit" name="simpan_absen" class="btn btn-lg btn-success" id="btn-simpan" disabled>
                <i class="bi bi-check-circle"></i> Simpan Absensi
            </button>
        </div>
    </div>
</form>
<div class="">
    <div class="">
        <!-- Jam Real-time -->

        <!-- Info Absen Selanjutnya -->
        <!-- <div class="next-absen-info">
            <h5><i class="bi bi-info-circle-fill"></i> Absensi Selanjutnya</h5>
            <p class="mb-0" style="font-size: 1.3rem; font-weight: bold;">
                <span class="tipe-badge <?= ($tipe_absen_next == 'masuk') ? 'badge-masuk' : 'badge-pulang' ?>">
                    <?= strtoupper($tipe_absen_next) ?>
                </span>
            </p>
        </div> -->

        <!-- Info Lokasi -->
        <!-- <div class="lokasi-info" id="lokasi-info">
            <i class="bi bi-geo-alt-fill"></i> <strong>Lokasi:</strong> <span id="lokasi-text">Mengambil lokasi...</span>
        </div> -->

        <!-- Status Absensi Hari Ini -->
        <!-- <?php if ($absen_hari_ini->num_rows > 0): ?>
            <div class="status-absen status-sudah">
                <h5><i class="bi bi-check-circle-fill text-success"></i> Riwayat Absensi Hari Ini</h5>
                <div class="row">
                    <?php while ($absen = $absen_hari_ini->fetch_assoc()): ?>
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <span class="tipe-badge <?= ($absen['tipe_absen'] == 'masuk') ? 'badge-masuk' : 'badge-pulang' ?>">
                                        <?= strtoupper($absen['tipe_absen']) ?>
                                    </span>
                                    <p class="mt-2 mb-1"><strong><?= date('H:i:s', strtotime($absen['waktu'])) ?></strong></p>
                                    <p class="mb-2" style="font-size: 0.9rem;"><?= $absen['shift'] ?></p>
                                    <?php if ($absen['foto']): ?>
                                        <img src="dokter/foto_absen_dokter/<?= $absen['foto'] ?>" class="foto-absen-preview" alt="Foto Absen">
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="status-absen status-belum">
                <h5><i class="bi bi-exclamation-circle-fill text-warning"></i> Anda belum absen hari ini</h5>
                <p class="mb-0">Silakan lakukan absensi masuk terlebih dahulu</p>
            </div>
        <?php endif; ?> -->

        <!-- Form Absensi -->


        <!-- Riwayat Absensi -->
        <!-- <hr class="my-4"> -->

    </div>
</div>

<script>
    // Variabel global
    let video = document.getElementById('video');
    let canvas = document.getElementById('canvas');
    let preview = document.getElementById('preview');
    let stream = null;
    let fotoTerambil = false;
    let currentFacingMode = 'user'; // 'user' = depan, 'environment' = belakang
    let isMobileDevice = false;

    // Deteksi apakah device adalah mobile
    function detectMobileDevice() {
        const userAgent = navigator.userAgent.toLowerCase();
        const isMobile = /android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(userAgent);
        const hasTouch = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
        const isSmallScreen = window.innerWidth <= 768;

        return isMobile || (hasTouch && isSmallScreen);
    }

    isMobileDevice = detectMobileDevice();

    // Jam Real-time (Waktu Indonesia Barat - WIB)
    function updateJam() {
        // Gunakan toLocaleString untuk format waktu Indonesia
        const now = new Date();

        // Konversi ke waktu Indonesia (Asia/Jakarta)
        const optionsTime = {
            timeZone: 'Asia/Jakarta',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        };

        const optionsDate = {
            timeZone: 'Asia/Jakarta',
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        };

        // Format jam (HH:MM:SS)
        const waktuIndonesia = now.toLocaleTimeString('id-ID', optionsTime);
        document.getElementById('jam-realtime').textContent = waktuIndonesia;

        // Format tanggal lengkap
        const tanggalIndonesia = now.toLocaleDateString('id-ID', optionsDate);
        document.getElementById('tanggal-realtime').textContent = tanggalIndonesia;
    }

    // Update jam setiap detik
    setInterval(updateJam, 1000);
    updateJam(); // Panggil pertama kali

    // Ambil Geolocation
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;

                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lon;

                    document.getElementById('lokasi-text').innerHTML =
                        `<strong>Lat:</strong> ${lat.toFixed(6)}, <strong>Lon:</strong> ${lon.toFixed(6)} ` +
                        `<a href="https://www.google.com/maps?q=${lat},${lon}" target="_blank" class="text-primary">` +
                        `<i class="bi bi-box-arrow-up-right"></i> Lihat Maps</a>`;
                },
                function(error) {
                    let errorMsg = '';
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            errorMsg = 'Akses lokasi ditolak. Harap izinkan akses lokasi!';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMsg = 'Informasi lokasi tidak tersedia.';
                            break;
                        case error.TIMEOUT:
                            errorMsg = 'Waktu permintaan lokasi habis.';
                            break;
                        default:
                            errorMsg = 'Terjadi kesalahan saat mengambil lokasi.';
                    }
                    document.getElementById('lokasi-text').innerHTML =
                        `<span class="text-danger">${errorMsg}</span>`;
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        } else {
            document.getElementById('lokasi-text').innerHTML =
                '<span class="text-danger">Browser tidak mendukung Geolocation!</span>';
        }
    }

    // Ambil lokasi saat halaman dimuat
    getLocation();

    // Update lokasi setiap 30 detik
    setInterval(getLocation, 30000);

    // Fungsi untuk membuka kamera
    function startCamera(facingMode = 'user') {
        // Hentikan stream sebelumnya jika ada
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }

        const constraints = {
            video: {
                width: {
                    ideal: 1280
                },
                height: {
                    ideal: 720
                },
                facingMode: facingMode
            }
        };

        navigator.mediaDevices.getUserMedia(constraints)
            .then(function(mediaStream) {
                stream = mediaStream;
                video.srcObject = stream;
                video.style.display = 'block';
                document.getElementById('start-camera').style.display = 'none';
                document.getElementById('capture').style.display = 'inline-block';
                preview.style.display = 'none';

                // Tampilkan tombol switch camera hanya di mobile
                if (isMobileDevice) {
                    document.getElementById('switch-camera').style.display = 'flex';
                }
            })
            .catch(function(err) {
                alert('Tidak dapat mengakses kamera: ' + err.message);
                console.error('Camera error:', err);
            });
    }

    // Buka kamera
    document.getElementById('start-camera').addEventListener('click', function() {
        startCamera(currentFacingMode);
    });

    // Switch camera (balik kamera)
    document.getElementById('switch-camera').addEventListener('click', function() {
        // Toggle facing mode
        currentFacingMode = (currentFacingMode === 'user') ? 'environment' : 'user';
        startCamera(currentFacingMode);
    });

    // Ambil foto
    document.getElementById('capture').addEventListener('click', function() {
        // Validasi shift
        const shift = document.getElementById('shift').value;
        if (!shift) {
            alert('Silakan pilih shift terlebih dahulu!');
            return;
        }

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        let context = canvas.getContext('2d');

        // Flip horizontal hanya jika menggunakan kamera depan
        if (currentFacingMode === 'user') {
            context.translate(canvas.width, 0);
            context.scale(-1, 1);
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            // Reset transformation
            context.setTransform(1, 0, 0, 1, 0, 0);
        } else {
            // Kamera belakang tidak perlu di-flip
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
        }

        // Kompress foto dengan kualitas 0.6 (60%)
        let fotoBase64 = canvas.toDataURL('image/jpeg', 0.6);

        document.getElementById('foto_absen').value = fotoBase64;
        preview.src = fotoBase64;
        preview.style.display = 'block';

        // Hentikan kamera
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }

        video.style.display = 'none';
        document.getElementById('capture').style.display = 'none';
        document.getElementById('retake').style.display = 'inline-block';
        document.getElementById('btn-simpan').disabled = false;
        document.getElementById('switch-camera').style.display = 'none';

        fotoTerambil = true;
    });

    // Foto ulang
    document.getElementById('retake').addEventListener('click', function() {
        document.getElementById('foto_absen').value = '';
        preview.style.display = 'none';
        document.getElementById('retake').style.display = 'none';
        document.getElementById('start-camera').style.display = 'inline-block';
        document.getElementById('btn-simpan').disabled = true;
        fotoTerambil = false;
    });

    // Validasi form sebelum submit
    document.getElementById('form-absen').addEventListener('submit', function(e) {
        const shift = document.getElementById('shift').value;
        const foto = document.getElementById('foto_absen').value;
        const latitude = document.getElementById('latitude').value;
        const longitude = document.getElementById('longitude').value;

        if (!shift) {
            e.preventDefault();
            alert('Silakan pilih shift terlebih dahulu!');
            return false;
        }

        if (!foto) {
            e.preventDefault();
            alert('Silakan ambil foto terlebih dahulu!');
            return false;
        }

        if (!latitude || !longitude) {
            e.preventDefault();
            alert('Lokasi belum terdeteksi! Harap izinkan akses lokasi dan tunggu beberapa saat.');
            return false;
        }

        // Konfirmasi
        const lokasiInfo = `Lat: ${parseFloat(latitude).toFixed(6)}, Lon: ${parseFloat(longitude).toFixed(6)}`;
        if (!confirm(`Apakah Anda yakin data absensi sudah benar?\n\nLokasi: ${lokasiInfo}`)) {
            e.preventDefault();
            return false;
        }
    });

    // Cleanup saat halaman ditutup
    window.addEventListener('beforeunload', function() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
    });
</script>