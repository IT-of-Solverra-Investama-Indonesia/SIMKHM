<?php
$id = htmlspecialchars($_GET['id']);
include '../dist/function.php';
$rawat = $koneksi->query("SELECT * FROM registrasi_rawat WHERE idrawat = '$id'")->fetch_assoc();
$getcppt = $koneksi->query("SELECT * FROM ctt_penyakit_inap WHERE norm = '$rawat[no_rm]' GROUP BY petugas ORDER BY id DESC");
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rating</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <style>
        .rating-container {
            font-family: 'Arial', sans-serif;
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            background-color: #f5f9f8;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .rating-container h2 {
            color: #2c7873;
            margin-bottom: 10px;
        }

        .star-rating {
            direction: rtl;
            unicode-bidi: bidi-override;
            margin: 20px 0;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            color: #ccc;
            font-size: 40px;
            padding: 0 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .star-rating label:hover,
        .star-rating label:hover~label,
        .star-rating input:checked~label {
            color: #ffc107;
        }

        .rating-feedback textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            resize: vertical;
            min-height: 100px;
            margin: 15px 0;
            font-family: inherit;
        }

        .btn-submit {
            background-color: #2c7873;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .btn-submit:hover {
            background-color: #235f5b;
        }

        .message {
            padding: 10px;
            margin: 15px 0;
            border-radius: 5px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<?php if (isset($_POST['rating'])) { ?>
    <?php
    $ip =  $_SERVER['REMOTE_ADDR'] ?: ($_SERVER['HTTP_X_FORWARDED_FOR'] ?: $_SERVER['HTTP_CLIENT_IP']);
    $ratings = $_POST['rating'] ?? [];
    $feedbacks = $_POST['feedback'] ?? [];
    $no_rm = $rawat['no_rm'];

    foreach ($ratings as $petugas_id => $rating) {
        $feedback = $feedbacks[$petugas_id] ?? '';
        $petugas_type = ($petugas_id == 'dokter') ? 'dokter' : 'perawat';
        $petugas_name = ($petugas_id == 'dokter') ? $rawat['dokter_rawat'] : $petugas_id;

        $getPasien = $koneksi->query("SELECT * FROM pasien WHERE nama_lengkap = '$rawat[nama_pasien]' ")->fetch_assoc();

        if ($petugas_type == 'dokter') {
            $koneksi->query("INSERT INTO `rating`(tgl, nama, vote, ip, komentar, hp) VALUES (NOW(), '$petugas_name', '$rating', '$ip', '$feedback', '$getPasien[nohp]')");
        } elseif ($petugas_type == 'perawat') {
            $koneksi->query("INSERT INTO `rating`(tgl, nama_prwt, rating_prwt, ip, komen_prwt, hp) VALUES (NOW(), '$petugas_name', '$rating', '$ip', '$feedback', '$getPasien[nohp]')");
        }
    }
    $success_msg = "Berhasil memberikan penilaian! Terima kasih atas feedback Anda.";
    ?>
<?php } ?>

<body>
    <div class="rating-container">
        <h3>Beri Penilaian untuk Klinik Husada Mulia</h3>
        <p class="mb-1 text-capitalize">Bagaimana pengalaman <?= $rawat['nama_pasien'] ?> berobat di klinik kami?</p>

        <?php if (isset($success_msg)): ?>
            <div class="alert alert-success"><?= $success_msg ?></div>
        <?php endif; ?>

        <form method="post" action="">
            <!-- Rating untuk Dokter -->
            <div id="dokter" class="rating-section mb-4">
                <h5 class="text-capitalize"><?= $rawat['dokter_rawat'] ?> (Dokter)</h5>
                <?php $getAdminDokter = $koneksi->query("SELECT * FROM admin WHERE namalengkap = '$rawat[dokter_rawat]'")->fetch_assoc(); ?>
                <img src="../tenagamedis/foto/<?= $getAdminDokter['foto'] ?>" height="200" style="border-radius: 15px;" class="mb-3">

                <div class="star-rating">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <input type="radio" id="dokter-star<?= $i ?>" name="rating[dokter]" value="<?= $i ?>" required />
                        <label for="dokter-star<?= $i ?>" title="<?= ['Sangat Kurang', 'Kurang', 'Cukup', 'Memuaskan', 'Sangat Memuaskan'][$i - 1] ?>">★</label>
                    <?php endfor; ?>
                </div>

                <textarea name="feedback[dokter]" class="form-control mt-3" placeholder="Komentar untuk dokter..."></textarea>
            </div>

            <!-- Rating untuk Perawat -->
            <?php foreach ($getcppt as $cppt): ?>
                <?php $getPetugas = $koneksi->query("SELECT * FROM admin WHERE namalengkap = '$cppt[petugas]'")->fetch_assoc(); ?>
                <div id="perawat" class="rating-section mb-4">
                    <h5 class="text-capitalize"><?= $cppt['petugas'] ?> (Perawat)</h5>
                    <img src="../tenagamedis/foto/<?= $getPetugas['foto'] ?>" height="200" style="border-radius: 15px;" class="mb-3">

                    <div class="star-rating">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" id="perawat-<?= $cppt['id'] ?>-star<?= $i ?>"
                                name="rating[<?= $cppt['petugas'] ?>]" value="<?= $i ?>" required />
                            <label for="perawat-<?= $cppt['id'] ?>-star<?= $i ?>"
                                title="<?= ['Sangat Kurang', 'Kurang', 'Cukup', 'Memuaskan', 'Sangat Memuaskan'][$i - 1] ?>">★</label>
                        <?php endfor; ?>
                    </div>

                    <textarea name="feedback[<?= $cppt['petugas'] ?>]" class="form-control mt-3"
                        placeholder="Komentar untuk perawat..."></textarea>
                </div>
            <?php endforeach; ?>

            <button type="submit" class="btn-submit mt-3">Kirim Semua Penilaian</button>
        </form>
    </div>

    <script>
        // Fungsi untuk mengatur rating bintang
        function setupStarRating(section) {
            const stars = section.querySelectorAll('.star-rating label');
            const inputs = section.querySelectorAll('.star-rating input');

            stars.forEach(star => {
                star.addEventListener('mouseover', function() {
                    const rating = this.htmlFor.split('-star')[1];
                    highlightStars(section, rating);
                });

                star.addEventListener('mouseout', function() {
                    const checked = section.querySelector('.star-rating input:checked');
                    highlightStars(section, checked ? checked.value : 0);
                });
            });

            inputs.forEach(input => {
                input.addEventListener('change', function() {
                    highlightStars(section, this.value);
                });
            });
        }

        function highlightStars(section, rating) {
            const stars = section.querySelectorAll('.star-rating label');
            stars.forEach(star => {
                const starValue = star.htmlFor.split('-star')[1];
                star.style.color = starValue <= rating ? '#ffc107' : '#ccc';
            });
        }

        // Inisialisasi untuk semua section
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.rating-section').forEach(section => {
                setupStarRating(section);
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js" integrity="sha384-VQqxDN0EQCkWoxt/0vsQvZswzTHUVOImccYmSyhJTp7kGtPed0Qcx8rK9h9YEgx+" crossorigin="anonymous"></script>
</body>

</html>