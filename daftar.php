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

        .register p {
            font-size: 15px;
            font-weight: bold;
        }

        .register img {
            width: 50px;
            height: 50px;
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
    </style>
</head>

<body style="font-family: sans-serif;">

    <div class="d-flex flex-column align-items-center gap-4 my-5">
        <h2><b>Pendaftaran Poli Online</b></h2>
        <div class="d-flex flex-column flex-md-row gap-3">
            <div class="card mt-md-5 text-center" style="width: 18rem;">
                <img src="https://wedangtech.my.id/WhatsApp%20Image%202024-07-15%20at%2015.04.18.jpeg" class="card-img-top" alt="...">
                <div class="card-body d-flex flex-column justify-content-between">
                    <h5 class="card-title">Klinik Husada Mulia Wonorejo</h5>
                    <div class="d-grid gap-2">
                        <a href="wonorejo/pasien/login.php" class="btn btn-primary">Kunjungi</a>
                    </div>
                </div>
            </div>
            <div class="card mt-md-0 mb-md-5 text-center" style="width: 18rem;">
                <img src="https://husadamulia.com/gambar/klakah.jpeg" class="card-img-top" alt="...">
                <div class="card-body d-flex flex-column justify-content-between">
                    <h5 class="card-title">Klinik Husada Mulia Klakah</h5>
                    <div class="d-grid gap-2">
                        <a href="klakah/pasien/login.php" class="btn btn-primary">Kunjungi</a>
                    </div>
                </div>
            </div>
            <div class="card mt-md-5 text-center" style="width: 18rem;">
                <img src="https://husadamulia.com/gambar/klakah.jpeg" class="card-img-top" alt="...">
                <div class="card-body d-flex flex-column justify-content-between">
                    <h5 class="card-title">Klinik Husada Mulia Tunjung</h5>
                    <div class="d-grid gap-2">
                        <a href="tunjung/pasien/login.php" class="btn btn-primary">Kunjungi</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>