<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="pasien/assets/img/khm.png">
    <title>SIMKHM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
</head>

<style>
    body {
        margin: 0;
        padding: 0;
        position: relative;
        overflow: hidden;
    }

    .background-slider {
        display: none;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        position: absolute;
        width: 100%;
        height: 100%;
        z-index: -1; /* Ensure it's behind other content */
        transition: background-image 2s ease-in-out;
    }

    .background-slider::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.3);
    }

    .layanan .card {
        box-shadow: 0 .5rem 1rem rgba(black, .15);
        width: 14rem;
        height: auto;
    }

    .layanan .col {
        padding-bottom: 1.5rem;
        padding-top: 1.5rem;
    }

    @media (max-width: 720px) {
        .background-slider {
            display: block;
        }

        .col {
            pointer-events: none;
        }

        .layanan .card {
            width: 10rem;
        }
    }
</style>

<body>
    <div class="background-slider"></div>
    <div class="container">
        <div class="layanan row row-cols-md-2 row-cols-sm-2 row-cols-lg-4">
            <div class="col">
                <div class="card">
                    <a href="">
                        <img src="assets/img/Screenshot 2024-08-26 122118.png" class="card-img-top">
                    </a>
                    <div class="card-body p-2">
                        <h6 class="card-title">Layanan 24/7</h6>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <a href="">
                        <img src="assets/img/Screenshot 2024-08-26 122021.png" class="card-img-top">
                    </a>
                    <div class="card-body p-2">
                        <h6 class="card-title">Layanan Apotek</h6>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <a href="">
                        <img src="assets/img/Screenshot 2024-08-26 122034.png" class="card-img-top">
                    </a>
                    <div class="card-body p-2">
                        <h6 class="card-title">Layanan Akupuntur</h6>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <a href="">
                        <img src="assets/img/Screenshot 2024-08-26 122043.png" class="card-img-top">
                    </a>
                    <div class="card-body p-2">
                        <h6 class="card-title">Layanan Laboratorium</h6>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <a href="">
                        <img src="assets/img/Screenshot 2024-08-26 122048.png" class="card-img-top">
                    </a>
                    <div class="card-body p-2">
                        <h6 class="card-title">Layanan Khitan</h6>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <a href="">
                        <img src="assets/img/Screenshot 2024-08-26 122053.png" class="card-img-top">
                    </a>
                    <div class="card-body p-2">
                        <h6 class="card-title">Layanan Home Care</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<script>
    const images = [
        'assets/img/layanan/1.jpeg',
        'assets/img/layanan/2.jpeg',
        'assets/img/layanan/3.jpeg',
        'assets/img/layanan/4.jpeg',
        'assets/img/layanan/5.jpeg',
        'assets/img/layanan/6.jpeg',
        'assets/img/layanan/7.jpeg',
        'assets/img/layanan/8.jpeg',
    ];

    let currentIndex = 0;

    function changeBackground() {
        const slider = document.querySelector('.background-slider');
        slider.style.backgroundImage = `url(${images[currentIndex]})`;
        currentIndex = (currentIndex + 1) % images.length;
    }

    setInterval(changeBackground, 5000);
    changeBackground();
</script>
