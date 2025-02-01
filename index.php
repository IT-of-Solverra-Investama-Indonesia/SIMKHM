<?php
include 'wonorejo/admin/dist/function.php';
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="pasien/assets/img/khm.png">

    <title>SIMKHM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- bikin icon -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <style>
        body{
            overflow-x: hidden;
        }
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

        .navbarr {
            position: fixed;
            bottom: 6%;
            width: 100%;
            height: 55px;
            box-shadow: 0 0 3px rgba(0, 0, 0, 0.2);
            background-color: #ffffff;
            display: flex;
            overflow-x: auto;
        }

        .nav__link {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-grow: 1;
            min-width: 50px;
            overflow: hidden;
            white-space: nowrap;
            font-family: sans-serif;
            font-size: 13px;
            color: #444444;
            text-decoration: none;
        }

        .nav__link:hover {
            background-color: #eeeeee;
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

    <!-- bottom bar -->
    <nav class="navbarr">
        <a href="" class="nav__link">
            <i class="material-icons nav__icon">dashboard</i>
            <span class="nav__text">Beranda</span>
        </a>

        <a href="index.php?halaman=surveyaktif" class="nav__link">
            <i class="material-icons nav__icon">request_quote</i>
            <span class="nav__text">Antrianku</span>
        </a>

        <a href="index.php?halaman=profil" class="nav__link">
            <i class="material-icons nav__icon">person</i>
            <span class="nav__text">Profil</span>
        </a>

        <a href="index.php?halaman=contact" class="nav__link">
            <i class="material-icons nav__icon">contact_support</i>
            <span class="nav__text">admin</span>
        </a>

    </nav>


    <div class="register mb-4" id="register">
        <div id="menu" class="container-fluid">
            <div class="row row-cols-5 row-cols-md-4 g-4">

                <div class="col" style="margin-left: 5px; margin-right: 5px;">
                    <a href="daftar.php">

                        <img src="assets/img/daftar.png">


                    </a>
                </div>
                <div class="col" style="margin-left: 5px; margin-right: 5px;">
                    <a href="wonorejo/kosmetik/?halaman=shop">

                        <img src="assets/img/kosmetik.png">


                    </a>
                </div>
                <div class="col" style="margin-left: 5px; margin-right: 5px;">
                    <a href="wonorejo/kosmetik/login.php">

                        <img src="assets/img/konsultasi.png">


                    </a>
                </div>

                <div class="col" style="margin-left: 5px; margin-right: 5px;">
                    <a href="layanan.php">

                        <img src="assets/img/layanan.png">


                    </a>
                </div>
                <div class="col">

                    <img src="assets/img/obat.png">
                </div>
            </div>
        </div>
    </div>

    <section id="OurService" class="p-4 mt-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h2><b>Info Terkini</b></h2>
                </div>
                <?php
                $get = $koneksi->query("SELECT * FROM informasi");
                while ($pecah = $get->fetch_assoc()) {
                ?>
                    <a href="detailinfo.php?id=<?= $pecah['id'] ?>" class="text-light" style="text-decoration:none">
                        <p><?= htmlspecialchars($pecah['judul']) ?>
                            <span class="btn btn-warning">Klik Disini</span>
                        </p>
                    </a> <?php } ?>

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
        <footer style="color: #fff; width: 100%; font-size:13px; margin-bottom: 65px;">

            <div class="row" style="width:100%;">
                <div class="col-md-4 mx-3 d-flex flex-column gap-2">
                    <p><strong>Hubungi kami</strong></p>
                    <div>
                        KHM Wonorejo :

                        <a href="https://wa.me/6282233880001" class="text-decoration-none text-white" target="_blank" rel="noopener noreferrer"><i class="bi bi-whatsapp"></i> CS +62 822 3388 0001</a>
                    </div>
                    <div>
                        KHM Klakah :

                        <a href="https://wa.me/6281355550275" class="text-decoration-none text-white" target="_blank" rel="noopener noreferrer"><i class="bi bi-whatsapp"></i> CS +62 813 5555 0275</a>
                    </div>
                    <div>
                        KHM Tunjung :
                        <a href="https://wa.me/6281234571010" class="text-decoration-none text-white" target="_blank" rel="noopener noreferrer"><i class="bi bi-whatsapp"></i> CS +62 812 3457 1010</a>
                    </div>
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
                <img src="assets/img/20230831_110803.png" width="100px" style="margin-bottom: 60px;" />

        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>