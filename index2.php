<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .section {
            cursor: pointer;
            overflow: hidden;
        }

        .text-container {
            position: relative;
            z-index: 2;
            padding: 10px 20px;
            border-radius: 5px;
            transition: transform 0.3s ease-in-out;
        }

        @media (min-width: 576px) {

            html,
            body {
                width: 100%;
                height: 100%;
            }

            .section::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: inherit;
                transition: filter 0.3s ease-in-out;
                z-index: 1;
            }

            .section:hover .text-container {
                transform: scale(1.05);
            }

            .w-sm-75 {
                width: 75% !important;
            }

            .w-sm-25 {
                width: 25% !important;
            }

            .h-sm-50 {
                height: 50%;
            }
        }
    </style>
</head>

<body>

    <div class="container-fluid h-100 w-100 m-0 p-0 d-flex flex-sm-row flex-column">
        <div class="d-flex flex-column w-sm-75 w-100">
            <div class="d-flex justify-content-center align-items-center h-sm-50 py-4" style="background-color: #008000;">
                <h1 class="text-center text-white">Pelayanan Klinik Husada Mulia</h1>
            </div>
            <div class="d-flex flex-sm-row flex-column h-sm-50">
                <a href="wonorejo/pasien/login.php" class="section d-flex justify-content-center align-items-center text-white position-relative flex-fill text-decoration-none" style="background-color: #31A631;">
                    <div class="text-container m-3">
                        <div class="row">
                            <div class="col-md-3">
                                <center>
                                    <h1><i class="bi bi-journal-plus"></i></h1>
                                </center>
                            </div>
                            <div class="col-md-9 my-auto">
                                <center>
                                    <h5>KHM Wonorejo</h5>
                                </center>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="klakah/pasien/login.php" class="section d-flex justify-content-center align-items-center text-white position-relative flex-fill text-decoration-none" style="background-color: #45AC45;">
                    <div class="text-container m-3">
                        <div class="row">
                            <div class="col-md-3">
                                <center>
                                    <h1><i class="bi bi-journal-plus"></i></h1>
                                </center>
                            </div>
                            <div class="col-md-9 my-auto">
                                <center>
                                    <h5>KHM Klakah</h5>
                                </center>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="tunjung/pasien/login.php" class="section d-flex justify-content-center align-items-center text-white position-relative flex-fill text-decoration-none" style="background-color: #64BA64;">
                    <div class="text-container m-3">
                        <div class="row">
                            <div class="col-md-3">
                                <center>
                                    <h1><i class="bi bi-journal-plus"></i></h1>
                                </center>
                            </div>
                            <div class="col-md-9 my-auto">
                                <center>
                                    <h5>KHM Tunjung</h5>
                                </center>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="d-flex flex-column w-100 w-sm-25">
            <a href="wonorejo/kosmetik/index.php?halaman=home" class="section d-flex justify-content-center align-items-center text-white position-relative flex-fill text-decoration-none" style="background-color: #80AF81;">
                <div class="text-container m-3">
                    <div class="row">
                        <div class="col-md-12">
                            <center>
                                <h1><i class="bi bi-chat-text"></i></h1>
                            </center>
                        </div>
                        <div class="col-md-12">
                            <center>
                                <h5>Konsultasi Kosmetik Online</h5>
                            </center>
                        </div>
                    </div>
                </div>
            </a>
            <a href="wonorejo/kosmetik/index.php?halaman=shop" class="section d-flex justify-content-center align-items-center text-white position-relative flex-fill text-decoration-none" style="background-color: #508D4E;">
                <div class="text-container m-3">
                    <div class="row">
                        <div class="col-md-12">
                            <center>
                                <h1><i class="bi bi-cart4"></i></h1>
                            </center>
                        </div>
                        <div class="col-md-12">
                            <center>
                                <h5>Belanja Kosmetik</h5>
                            </center>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>