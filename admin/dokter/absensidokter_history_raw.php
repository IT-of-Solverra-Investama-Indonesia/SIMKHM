<?php
include '../dist/function.php';
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Absensi Dokter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body class="p-2">
    <?php
    $date_start = sani($_GET['date_start'] ?? date('Y-m-01'));
    $date_end = sani($_GET['date_end'] ?? date('Y-m-d'));
    ?>
    <?php if (!isset($_GET['detail'])) { ?>
        <div>
            <h3>Absensi Dokter</h3>
            <div class="card shadow p-2 mb-1">
                <form method="get">
                    <div class="row g-1">
                        <div class="col-5">
                            <input type="text" onclick="this.type='date'" onfocus="this.type='date'" onblur="this.type='text'" placeholder="Dari Tanggal" name="date_start" class="form-control form-control-sm" value="<?= $date_start ?>" id="">
                        </div>
                        <div class="col-5">
                            <input type="text" onclick="this.type='date'" onfocus="this.type='date'" onblur="this.type='text'" placeholder="Sampai Tanggal" name="date_end" class="form-control form-control-sm" value="<?= $date_end ?>" id="">
                        </div>
                        <div class="col-2">
                            <button class="btn btn-sm btn-primary w-100" type="submit"><i class="bi bi-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card shadow p-2">
                <div class="table-responsive">
                    <table class="table-hover table-striped table table-sm" style="font-size: 12px;">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Rentan Tanggal</th>
                                <th>Jumlah Absensi Datang</th>
                                <th>Jumlah Absensi Datang</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $getData = $koneksimaster->query("SELECT nama_dokter, COUNT(CASE WHEN tipe_absen = 'masuk' THEN 1 END) AS jumlah_datang, COUNT(CASE WHEN tipe_absen = 'Pulang' THEN 1 END) AS jumlah_pulang FROM absensi_dokter WHERE tanggal BETWEEN '" . $date_start . "' AND '" . $date_end . "' GROUP BY nama_dokter ORDER BY nama_dokter ASC");
                            foreach ($getData as $data) {
                            ?>
                                <tr>
                                    <td><?= $data['nama_dokter'] ?></td>
                                    <td><?= $date_start . ' s/d ' . $date_end ?></td>
                                    <td>
                                        <button onclick="loadIframe('?detail=<?= $data['nama_dokter'] ?>&tipe_absen=masuk&date_start=<?= $date_start ?>&date_end=<?= $date_end ?>')" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                            <?= $data['jumlah_datang'] ?>
                                        </button>
                                    </td>
                                    <td>
                                        <button onclick="loadIframe('?detail=<?= $data['nama_dokter'] ?>&tipe_absen=pulang&date_start=<?= $date_start ?>&date_end=<?= $date_end ?>')" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                            <?= $data['jumlah_pulang'] ?>
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script>
            function loadIframe(url) {
                const iframe = `<iframe src="${url}" width="100%" height="500px" style="border:none;"></iframe>`;
                document.getElementById('showIframe').innerHTML = iframe;
            }
        </script>
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel"></h1>
                        <button type="button" onclick="loadIframe('')" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="showIframe">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="loadIframe('')" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <!-- <button type="button" class="btn btn-primary">Understood</button> -->
                    </div>
                </div>
            </div>
        </div>
    <?php } elseif (isset($_GET['detail'])) { ?>
        <?php
        $nama_dokter = sani($_GET['detail']);
        $tipe_absen = sani($_GET['tipe_absen'] ?? '');
        $getData = $koneksimaster->query("SELECT * FROM absensi_dokter WHERE nama_dokter = '" . $nama_dokter . "' AND tipe_absen = '" . $tipe_absen . "' AND tanggal BETWEEN '" . $date_start . "' AND '" . $date_end . "' ORDER BY tanggal ASC");
        ?>
        <div>
            <h3>Detail Absensi <?= $nama_dokter ?></h3>
            <div class="table-responsive">
                <table class="table-hover table-striped table table-sm" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Unit</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Seharusnya</th>
                            <th>Shift</th>
                            <th>Tipe</th>
                            <th>Tipe Absen</th>
                            <th>Lokasi</th>
                            <th>Foto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($getData as $data) {
                        ?>
                            <tr>
                                <td><?= $data['nama_dokter'] ?></td>
                                <td><?= $data['unit'] ?></td>
                                <td><?= $data['tanggal'] ?></td>
                                <td><?= $data['waktu'] ?></td>
                                <td><?= $data['waktu_seharusnya'] ?></td>
                                <td><?= $data['shift'] ?></td>
                                <td><?= $data['tipe_absen'] ?></td>
                                <td><?= $data['tipe_absen'] ?></td>
                                <td><a target="_blank" href="https://www.google.com/maps?q=<?= $data['latitude'] ?>,<?= $data['longitude'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-geo-alt"></i></a></td>
                                <td>
                                    <?php if ($data['foto'] != null || $data['foto'] != '') { ?>
                                        <a href="foto_absen_dokter/<?= $data['foto'] ?>" target="_blank">
                                            <img src="foto_absen_dokter/<?= $data['foto'] ?>" alt="" width="50">
                                        </a>
                                    <?php } else { ?>
                                        -
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
</body>

</html>