<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="pasien/assets/img/khm.png">

    <title>SIMKHM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .hero img {
            width: 100%;
            padding: 10px 10px;
        }

        .btn-custom {
            background-color: red;
            color: white;
            font-weight: bold;
            border-radius: 30px;
            padding: 10px 30px;
            width: 200px;
        }

        .btn-custom:hover {
            background-color: green;
            color: #fcd12a;
            font-weight: bold;
            border-radius: 30px;
            padding: 10px 30px;
        }

        .logo {
            position: absolute;
            top: 20px;
            left: 20px;
        }

        .register {
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .register button {
            width: 150px;
            height: 150px;
        }

        .register img {
            width: 120px;
            height: 120px;
        }

        .rounded-circle {
            width: 60px;
            height: 60px;
            background-color: #08592F;
            border-radius: 50%;
            align-items: center;
            justify-content: center;
            align-items: center;

        }

        .rounded-circle i {
            align-items: center;
            color: white;
            font-size: 40px;
        }

        #OurService {
            background-color: #08592f;
            color: white;
        }

        .running-service {
            display: flex;
            overflow-x: hidden;

        }

        @keyframes scroll {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        .arch-image {
            flex: none;
            margin: 0 10px;
        }

        .arch-image img {
            width: 200px;
            border-radius: 50px 50px 0 0;
            object-fit: cover;
            animation: scroll 15s linear infinite;
        }

        .hidden {
            display: none;
        }

        @media(min-width: 320px) and (max-width:799px) {

            .register button {
                width: 100px;
                height: 100px;
            }

            .register .last {
                flex: 0 0 100%;
                max-width: 100%;
                width: 100%;
                justify-content: center;
                align-items: center;
            }

            .register img {
                width: 75px;
                height: 75px;
            }
        }
    </style>
</head>

<body style="font-family: sans-serif;">

    <section class="hero w-100 mb-5">
        <center>
            <img src="assets/img/header.svg">
            <h2><b>Layanan KHM</b></h2>
        </center>
    </section>

    <div class="register mb-4" id="register">
        <div id="menu" class="container-fluid">
            <div class="row row-cols-3 row-cols-md-4 g-4">
                <div class="col">
                    <a href="daftar.php" style="color: black; text-decoration: none;">
                        <button class="btn btn-lg shadow" style="border-radius: 50%;">
                            <img src="assets/img/1.png" alt="">
                        </button>
                    </a>
                </div>
                <div class="col">
                    <a href="wonorejo/kosmetik/?halaman=shop" style="color: black; text-decoration: none;">
                        <button class="btn btn-lg shadow" style="border-radius: 50%;">
                            <img src="assets/img/2.png" alt="">
                        </button>
                    </a>
                </div>
                <div class="col">
                    <a href="wonorejo/kosmetik/login.php" style="color: black; text-decoration: none;">
                        <button class="btn btn-lg shadow" style="border-radius: 50%;">
                            <img src="assets/img/3.png" alt="">
                        </button>
                    </a>
                </div>
                <div class="col last">
                    <button class="btn btn-lg shadow" style="border-radius: 50%;">
                        <img src="assets/img/4.png" class="w-100" alt="">
                    </button>
                    <br>
                    <sup class="btn-custom p-2">Segera Hadir</sup>
                </div>
            </div>
        </div>
    </div>

    <section id="OurService" class="p-4 mt-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h2><b>Daftar <br>Layanan Kami</b></h2>
                    <span class="text-capitalize">
                        Klinik Husada Mulia adalah Klinik Pratama dengan berbagai layanan yang tersedia di masing-masing Cabang
                    </span>
                </div>
                <div class="col-md-8">
                    <div class="running-service d-flex">
                        <div class="arch-image">
                            <img src="assets/img/Screenshot 2024-08-26 122021.png" alt="Service 1">
                        </div>
                        <div class="arch-image">
                            <img src="assets/img/Screenshot 2024-08-26 122034.png" alt="Service 2">
                        </div>
                        <div class="arch-image">
                            <img src="assets/img/Screenshot 2024-08-26 122043.png" alt="Service 3">
                        </div>
                        <div class="arch-image">
                            <img src="assets/img/Screenshot 2024-08-26 122048.png" alt="Service 3">
                        </div>
                        <div class="arch-image">
                            <img src="assets/img/Screenshot 2024-08-26 122053.png" alt="Service 3">
                        </div>
                        <div class="arch-image">
                            <img src="assets/img/Screenshot 2024-08-26 122118.png" alt="Service 3">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <br>

    <section>
        <div class="container-fluid ">
            <div class="row">
                <div class="col-6  d-flex flex-column align-items-center justify-content-center">
                    <img src="assets/img/group2.png" alt="" style="width: 50%" />
                </div>
                <div class="col-6  d-flex flex-column align-items-start justify-content-center text-align-start">
                    <h3><b></b>
                        Yuk, Segera Belanja Kosmetik Sekarang!
                        </b></h3>
                    <button class="btn btn-warning" style="border-radius: 50px"><a href="wonorejo/kosmetik/"></a>Klik di sini ya!</a></button>
                </div>
            </div>
        </div>
    </section>

    <div style="background-color: #08592F">
        <footer class="mt-5 px-5 py-2 row col-md" style="color: #fff; width: 100%; font-size:13px;">
            <div class="col-md-4 mx-3 my-3">
                <img src="assets/img/20230831_110803.png" style="max-width: 120px;" class="mb-3" alt="" />
                <!-- <p>Lokal berkelas</p> -->
                <p><b>Layanan Pengaduan Konsumen</b></p>
                <p>Direktorat Jenderal Perlindungan Konsumen dan Tertib Niaga Kementerian Perdagangan RI</p>
                <p>
                    Kontak WhatsApp: +62 853 1111 1010
                </p>
            </div>

            <div class="col-md-4 mx-3 my-3 d-flex flex-column gap-2">
                <p><strong>Hubungi kami</strong></p>
                <div>
                    <h6>Alamat KHM Wonorejo</h6>
                    <p class="m-1">Jl. Nasional 25, Krajan, Wonorejo, Kec. Kedungjajang, Kabupaten Lumajang, Jawa Timur 67358</p>
                    <a href="https://wa.me/6282233880001" class="text-decoration-none text-white" target="_blank" rel="noopener noreferrer"><i class="bi bi-whatsapp"></i> CS +62 822 3388 0001</a>
                </div>
                <div>
                    <h6>Alamat KHM Klakah</h6>
                    <p class="m-1">Jl. Raya Lumajang - Probolinggo, Kec. Klakah, Kabupaten Lumajang, Jawa Timur 67356</p>
                    <a href="https://wa.me/6281355550275" class="text-decoration-none text-white" target="_blank" rel="noopener noreferrer"><i class="bi bi-whatsapp"></i> CS +62 813 5555 0275</a>
                </div>
                <div>
                    <h6>Alamat KHM Tunjung</h6>
                    <p class="m-1">Jl. Tunjung, Krajan Dua, Tunjung, Kec. Randuagung, Kabupaten Lumajang, Jawa Timur 67354</p>
                    <a href="https://wa.me/6281234571010" class="text-decoration-none text-white" target="_blank" rel="noopener noreferrer"><i class="bi bi-whatsapp"></i> CS +62 812 3457 1010</a>
                </div>
            </div>

            <div class="col-3 mx-3 my-3 d-flex flex-column">
                <p><strong>Ikuti kami</strong></p>
                <div class="d-flex gap-2">
                    <a href="https://www.instagram.com/husadamuliaofficial/" class="text-decoration-none text-white" target="_blank">
                        <h6><i class="bi bi-instagram"></i></h6>
                    </a>
                    <a href="https://www.facebook.com/profile.php?id=61553748481575" class="text-decoration-none text-white" target="_blank">
                        <h6><i class="bi bi-facebook"></i></h6>
                    </a>
                    <a href="https://www.tiktok.com/@husada_mulia" class="text-decoration-none text-white" target="_blank">
                        <h6><i class="bi bi-tiktok"></i></h6>
                    </a>
                    <a href="https://www.youtube.com/@sahabatmuliaofficial1463" class="text-decoration-none text-white" target="_blank">
                        <h6><i class="bi bi-youtube"></i></h6>
                    </a>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>