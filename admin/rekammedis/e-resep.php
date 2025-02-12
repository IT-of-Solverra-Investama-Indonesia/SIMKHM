<?php include '../dist/function.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Format Pengkajian Resep dan PIO Rajal</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .section-title {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 20px;
        }

        .content {
            margin-top: 20px;
        }

        .table-1 th,
        .table-1 td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .table-page2 th,
        .table-page2 td {
            border: 1px solid rgb(235, 235, 235);
            padding: 8px;
            text-align: left;
        }

        .table-page2 th {
            text-align: center;
        }

        /* .table th {
            background-color: #f2f2f2;
        } */

        .signature {
            margin-top: 40px;
            text-align: right;
        }

        .card {
            border-color: #000;
            border-radius: 0;
            border-width: 2px;
        }

        .table-bawah {
            display: flex;
            justify-content: space-between;
        }

        @media print {
            .page {
                page-break-after: always;
            }
        }
    </style>
</head>

<body>
    <?php
    $rekammedis = $koneksi->query("SELECT * FROM rekam_medis WHERE id_rm = '" . htmlspecialchars($_GET['idrekammedis']) . "'")->fetch_assoc();

    $registrasi = $koneksi->query("SELECT * FROM registrasi_rawat WHERE no_rm = '" . htmlspecialchars($_GET['id']) . "' AND jadwal = '$rekammedis[jadwal]'")->fetch_assoc();

    $kajian = $koneksi->query("SELECT * FROM kajian_awal WHERE norm = '" . htmlspecialchars($_GET['id']) . "' AND tgl_rm = '" . date('Y-m-d', strtotime($_GET['tgl'])) . "' ORDER BY id_rm DESC LIMIT 1")->fetch_assoc();

    $dataObat = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND rekam_medis_id = '" . htmlspecialchars($_GET['idrekammedis']) . "' AND DATE_FORMAT(tgl_pasien, '%Y-%m-%d') = '" . date('Y-m-d', strtotime($_GET['tgl'])) . "'");

    $dokter = $koneksi->query("SELECT * FROM admin WHERE namalengkap = '$registrasi[dokter_rawat]' LIMIT 1")->fetch_assoc();

    $resep = $koneksi->query("SELECT *, COUNT(*) as jumlah FROM resep INNER JOIN rekam_medis ON rekam_medis.norm = resep.no_rm WHERE resep.no_rm = '$_GET[id]' AND resep.jadwal = '$_GET[tgl]'")->fetch_assoc();

    $telaah = $koneksi->query("SELECT * FROM telaah_resep WHERE no_rm = '$_GET[id]' AND jadwal = '$_GET[tgl]'")->fetch_assoc();

    $pasien = $koneksi->query("SELECT * FROM pasien WHERE no_rm = '" . htmlspecialchars($_GET['id']) . "'")->fetch_assoc();
    ?>

    <!-- <script>
        window.print()
    </script> -->
    <div class="" style="">
        <div class="header">
            <h4>KLINIK HUSADA MULIA</h4>
            <p>Instansi Farmasi</p>
            <hr>
        </div>
        <div class="content">
            <table>
                <tr>
                    <td>No. RM / No. RW</td>
                    <td>:</td>
                    <td><?= $pasien['no_rm'] ?></td>
                </tr>
                <tr>
                    <td>Nama Pasien</td>
                    <td>:</td>
                    <td><?= $pasien['nama_lengkap'] ?></td>
                </tr>
                <tr>
                    <td>Tgl. Resep</td>
                    <td>:</td>
                    <td><?= date('d-m-Y', strtotime($_GET['tgl'])) ?></td>
                </tr>
                <tr>
                    <td>Nama Dokter</td>
                    <td>:</td>
                    <td><?= $dokter['namalengkap'] ?></td>
                </tr>
                <tr>
                    <td>Poliklinik</td>
                    <td>:</td>
                    <td>Umum</td>
                </tr>
                <tr>
                    <td>Jenis Bayar</td>
                    <td>:</td>
                    <td><?= $registrasi['carabayar'] ?></td>
                </tr>
            </table>
            <table class="table mt-3">
                <thead>
                    <tr>
                        <td>No.</td>
                        <td>Nama Obat</td>
                        <td>Signa</td>
                        <td>Qty</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($dataObat as $obat) { ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($obat['nama_obat']) ?></td>
                            <td><?= htmlspecialchars($obat['dosis1_obat']) ?>x<?= htmlspecialchars($obat['dosis2_obat']) ?> <?= htmlspecialchars($obat['per_obat']) ?> (<?= htmlspecialchars($obat['petunjuk_obat']) ?>)</td>
                            <td><?= htmlspecialchars($obat['jml_dokter']) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- <div class="page">
        <div class="header">
            <h4>KLINIK KISADA MULIA</h4>
            <p>Instansi Farmasi</p>
            <hr>
        </div>

        <div class="content">
            <table>
                <tr>
                    <td><b>Nama Dokter</b></td>
                    <td><b> : </b></td>
                    <td><?= $dokter['namalengkap'] ?></td>
                </tr>
                <tr>
                    <td><b>SIP</b></td>
                    <td><b> : </b></td>
                    <td></td>
                </tr>
                <tr>
                    <td><b>No. Resep</b></td>
                    <td><b> : </b></td>
                    <td>RSP-<?= $resep['id'] ?></td>
                </tr>
                <tr>
                    <td><b>Ruangan/Poli</b></td>
                    <td><b> : </b></td>
                    <td>Umum</td>
                </tr>
                <tr>
                    <td><b>Pasien</b></td>
                    <td><b> : </b></td>
                    <td><?= $pasien['nama_lengkap'] ?></td>
                </tr>
            </table>

            <div class="card p-2">
                <table style="width: 40%;">
                    <tr>
                        <td><b>Jenis Pelayanan :</b></td>
                        <td><label><input type="checkbox" id=""> Rawat Inap</label></td>
                        <td><label><input type="checkbox" id="" checked readonly> Rawat Jalan</label></td>
                    </tr>
                    <tr>
                        <td><b>Status Pelayanan :</b></td>
                        <td><label><input type="checkbox" id="" <?= $registrasi['carabayar'] == "umum" ? "checked" : "" ?>> Umum</label></td>
                        <td><label><input type="checkbox" id="" <?= $registrasi['carabayar'] == "bpjs" ? "checked" : "" ?>> JKN</label></td>
                    </tr>
                </table>
            </div>
            <hr>
            <p align="right" class="mb-0"><strong>Tanggal:</strong> <?= date('d-m-Y', strtotime($_GET['tgl'])) ?></p>

            <?php foreach ($dataObat as $obat) { ?>
                <h4><?= $obat['nama_obat'] ?>(<?= $obat['jenis_obat'] ?>) | <?= $obat['dosis1_obat'] ?>x<?= $obat['dosis2_obat'] ?> <?= $obat['per_obat'] ?> | <?= $obat['durasi_obat'] ?> Hari | <?= $obat['petunjuk_obat'] ?></h4>
            <?php } ?>

            <div class="row">
                <div class="col-6">
                    <table border="0" class="mt-3">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Nama Pasien</strong> </td>
                                <td><strong> : </strong></td>
                                <td><?= $pasien['nama_lengkap'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Alamat</strong></td>
                                <td><strong> : </strong></td>
                                <td><?= $pasien['alamat'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Diagnosis</strong></td>
                                <td><strong> : </strong></td>
                                <td><?= $rekammedis['diagnosis'] ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-6">
                    <table border="0" class="mt-3">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>BB</strong> </td>
                                <td><strong> : </strong></td>
                                <td><?= $kajian['bb'] ?> Kg</td>
                            </tr>
                            <tr>
                                <td><strong>Tgl. Lahir</strong></td>
                                <td><strong> : </strong></td>
                                <td><?= $pasien['tgl_lahir'] ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="section-title">Jam Pengobatan</div>
            <div class="card p-2 mt-4">
                <div class="row">
                    <div class="col-6">
                        <b>Jam Terima Resep : <?= date('H:i:s', strtotime($resep['serah_obat']) - 600) ?></b>
                    </div>
                    <div class="col-6">
                        <b>Jam Penyerahan Obat : <?= $resep['serah_obat'] ?></b>
                    </div>
                </div>
            </div>
            <div class="row mt-4 table-bawah">
                <div class="col-6">
                    <table class="table table-1">
                        <thead>
                            <tr>
                                <th>Pelayanan Obat</th>
                                <th>Paraf</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Verifikasi Obat</td>
                                <td>
                                    <center>
                                        <img style="max-width: 60px; margin: 0px 0px 0px 0px;" src="../dist/img-qrcode/<?= $resep['petugas'] == '' ? $_SESSION['admin']['namalengkap'] : $resep['petugas']; ?>.png" alt=""><br>
                                        <p style="font-size:12px">(<?= $resep['petugas'] == '' ? $_SESSION['admin']['namalengkap'] :  $resep['petugas']; ?>)</p>
                                    </center>
                                </td>
                            </tr>
                            <tr>
                                <td>Dispensing</td>
                                <td>
                                    <center>
                                        <img style="max-width: 60px; margin: 0px 0px 0px 0px;" src="../dist/img-qrcode/<?= $resep['petugas'] == '' ? $_SESSION['admin']['namalengkap'] : $resep['petugas']; ?>.png" alt=""><br>
                                        <p style="font-size:12px">(<?= $resep['petugas'] == '' ? $_SESSION['admin']['namalengkap'] :  $resep['petugas']; ?>)</p>
                                    </center>
                                </td>
                            </tr>
                            <tr>
                                <td>Verifikasi Obat</td>
                                <td>
                                    <center>
                                        <img style="max-width: 60px; margin: 0px 0px 0px 0px;" src="../dist/img-qrcode/<?= $resep['petugas'] == '' ? $_SESSION['admin']['namalengkap'] : $resep['petugas']; ?>.png" alt=""><br>
                                        <p style="font-size:12px">(<?= $resep['petugas'] == '' ? $_SESSION['admin']['namalengkap'] :  $resep['petugas']; ?>)</p>
                                    </center>
                                </td>
                            </tr>
                            <tr>
                                <td>Dispensing Obat</td>
                                <td>
                                    <center>
                                        <img style="max-width: 60px; margin: 0px 0px 0px 0px;" src="../dist/img-qrcode/<?= $resep['petugas'] == '' ? $_SESSION['admin']['namalengkap'] : $resep['petugas']; ?>.png" alt=""><br>
                                        <p style="font-size:12px">(<?= $resep['petugas'] == '' ? $_SESSION['admin']['namalengkap'] :  $resep['petugas']; ?>)</p>
                                    </center>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-4">
                    <div class="card p-4">
                        <table>
                            <tr>
                                <td>Tepat Pasien</td>
                                <td><input type="checkbox" id="" <?= $resep['tepat1'] == "on" ? "checked" : "" ?>></td>
                            </tr>
                            <tr>
                                <td>Tepat Obat</td>
                                <td><input type="checkbox" id="" <?= $resep['tepat2'] == "on" ? "checked" : "" ?>></td>
                            </tr>
                            <tr>
                                <td>Tepat Dosis</td>
                                <td><input type="checkbox" id="" <?= $resep['tepat3'] == "on" ? "checked" : "" ?>></td>
                            </tr>
                            <tr>
                                <td>Tepat Rute</td>
                                <td><input type="checkbox" id="" <?= $resep['tepat4'] == "on" ? "checked" : "" ?>></td>
                            </tr>
                            <tr>
                                <td>Tepat Waktu</td>
                                <td><input type="checkbox" id="" <?= $resep['tepat5'] == "on" ? "checked" : "" ?>></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page">
        <div class="header">
            <h3><b>PENGKAJIAN TELAAH RESEP</b></h3>
            <h5><b>PERSYARATAN ADMINISTRASI</b></h5>
        </div>
        <table class="table table-page2">
            <thead>
                <th>
                    <h5><b>PARAMETER</b></h5>
                </th>
                <th>
                    <h5><b>ADA</b></h5>
                </th>
                <th>
                    <h5><b>TIDAK</b></h5>
                </th>
            </thead>
            <tbody>
                <tr>
                    <td>Nama Pasien</td>
                    <td><?= $telaah['pa1'] == "Ya" ? "✓" : "" ?></td>
                    <td><?= $telaah['pa1'] != "Ya" ? "✓" : "" ?></td>
                </tr>
                <tr>
                    <td>No. RM Pasien</td>
                    <td><?= $telaah['pa2'] == "Ya" ? "✓" : "" ?></td>
                    <td><?= $telaah['pa2'] != "Ya" ? "✓" : "" ?></td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td><?= $telaah['pa3'] == "Ya" ? "✓" : "" ?></td>
                    <td><?= $telaah['pa3'] != "Ya" ? "✓" : "" ?></td>
                </tr>
                <tr>
                    <td>Umur/TB/BB</td>
                    <td><?= $telaah['pa4'] == "Ya" ? "✓" : "" ?></td>
                    <td><?= $telaah['pa4'] != "Ya" ? "✓" : "" ?></td>
                </tr>
                <tr>
                    <td>Nama dan Paraf Dokter</td>
                    <td><?= $telaah['pa5'] == "Ya" ? "✓" : "" ?></td>
                    <td><?= $telaah['pa5'] != "Ya" ? "✓" : "" ?></td>
                </tr>
                <tr>
                    <td>Tanggal Resep</td>
                    <td><?= $telaah['pa6'] == "Ya" ? "✓" : "" ?></td>
                    <td><?= $telaah['pa6'] != "Ya" ? "✓" : "" ?></td>
                </tr>
                <tr>
                    <td>Asal Poli/Ruangan</td>
                    <td><?= $telaah['pa7'] == "Ya" ? "✓" : "" ?></td>
                    <td><?= $telaah['pa7'] != "Ya" ? "✓" : "" ?></td>
                </tr>
            </tbody>
        </table>
        <div class="header">
            <h5><b>PERSYARATAN FARMASETIS</b></h5>
        </div>
        <table class="table table-page2">
            <thead>
                <th>
                    <h5><b>PARAMETER</b></h5>
                </th>
                <th>
                    <h5><b>ADA</b></h5>
                </th>
                <th>
                    <h5><b>TIDAK</b></h5>
                </th>
            </thead>
            <tbody>
                <tr>
                    <td>Nama Obat</td>
                    <td><?= $telaah['pa8'] == "Ya" ? "✓" : "" ?></td>
                    <td><?= $telaah['pa8'] != "Ya" ? "✓" : "" ?></td>
                </tr>
                <tr>
                    <td>Bentuk Sediaan</td>
                    <td><?= $telaah['pa9'] == "Ya" ? "✓" : "" ?></td>
                    <td><?= $telaah['pa9'] != "Ya" ? "✓" : "" ?></td>
                </tr>
                <tr>
                    <td>Kekuatan Sediaan</td>
                    <td><?= $telaah['pa10'] == "Ya" ? "✓" : "" ?></td>
                    <td><?= $telaah['pa10'] != "Ya" ? "✓" : "" ?></td>
                </tr>
                <tr>
                    <td>Dosis dan Jumlah Obat</td>
                    <td><?= $telaah['pa11'] == "Ya" ? "✓" : "" ?></td>
                    <td><?= $telaah['pa11'] != "Ya" ? "✓" : "" ?></td>
                </tr>
                <tr>
                    <td>Aturan dan Cara Pakai</td>
                    <td><?= $telaah['pa12'] == "Ya" ? "✓" : "" ?></td>
                    <td><?= $telaah['pa12'] != "Ya" ? "✓" : "" ?></td>
                </tr>
            </tbody>
        </table>
        <div class="header">
            <h5><b>PERSYARATAN KLINIS</b></h5>
        </div>
        <table class="table table-page2">
            <thead>
                <th>
                    <h5><b>PARAMETER</b></h5>
                </th>
                <th>
                    <h5><b>ADA</b></h5>
                </th>
                <th>
                    <h5><b>TIDAK</b></h5>
                </th>
            </thead>
            <tbody>
                <tr>
                    <td>Duplikasi Pengobatan</td>
                    <td><?= $telaah['pa13'] == "Ya" ? "✓" : "" ?></td>
                    <td><?= $telaah['pa13'] != "Ya" ? "✓" : "" ?></td>
                </tr>
                <tr>
                    <td>Tempat Indikasi, dosis, waktu penggunaan obat</td>
                    <td><?= $telaah['pa14'] == "Ya" ? "✓" : "" ?></td>
                    <td><?= $telaah['pa14'] != "Ya" ? "✓" : "" ?></td>
                </tr>
                <tr>
                    <td>Alergi dan Reaksi Obat yang Tidak Dikehendaki (ROTD)</td>
                    <td><?= $telaah['pa15'] == "Ya" ? "✓" : "" ?></td>
                    <td><?= $telaah['pa15'] != "Ya" ? "✓" : "" ?></td>
                </tr>
                <tr>
                    <td>Interaksi Obat</td>
                    <td><?= $telaah['pa16'] == "Ya" ? "✓" : "" ?></td>
                    <td><?= $telaah['pa16'] != "Ya" ? "✓" : "" ?></td>
                </tr>
                <tr>
                    <td>Poli Farmasi</td>
                    <td><?= $telaah['pa17'] == "Ya" ? "✓" : "" ?></td>
                    <td><?= $telaah['pa17'] != "Ya" ? "✓" : "" ?></td>
                </tr>
            </tbody>
        </table>
        <div class="header">
            <h5><b>PERSYARATAN KLINIS</b></h5>
        </div>
        <table class="table table-page2">
            <thead>
                <th>
                    <h5><b>Nama Obat dan Jumlah</b></h5>
                </th>
                <th>
                    <h5><b>Signa</b></h5>
                </th>
                <th>
                    <h5><b>Paraf</b></h5>
                </th>
            </thead>
            <tbody>
                <?php foreach ($dataObat as $obat) { ?>
                    <tr>
                        <td>
                            <?= $obat['nama_obat'] ?>
                        </td>
                        <td></td>
                        <td>
                            <center>
                                <img style="max-width: 60px; margin: 0px 0px 0px 0px;" src="../dist/img-qrcode/<?= $resep['petugas'] ?>.png" alt=""><br>
                                <p style="font-size:12px">(<?= $resep['petugas'] ?>)</p>
                            </center>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td style="padding: 50px"><b>Edukasi</b></td>
                    <td style="padding: 50px" colspan="2"><?= $telaah['edukasi'] ?></td>
                </tr>
                <tr>
                    <td style="padding: 50px"><b>Paraf Pasien</b></td>
                    <td style="padding: 50px" colspan="2">
                        <center>
                            <img style="max-width: 60px; margin: 0px 0px 0px 0px;" src="../dist/img-qrcode/<?= $pasien['nama_lengkap'] ?>.png" alt=""><br>
                            <p style="font-size:12px">(<?= $pasien['nama_lengkap'] ?>)</p>
                        </center>
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="table table-page2">

            <tr>
                <th>PERSETUJUAN PERUBAHAN RESEP</th>
                <th>PETUGAS FARMASI</th>
            </tr>
            <tr>
                <td style="">
                    <center>
                        <img style="max-width: 60px; margin: 0px 0px 0px 0px;" src="../dist/img-qrcode/<?= $resep['petugas'] ?>.png" alt=""><br>
                        <p style="font-size:12px">(<?= $resep['petugas'] ?>)</p>
                    </center>
                </td>
                <td style="">
                    <center>
                        <img style="max-width: 60px; margin: 0px 0px 0px 0px;" src="../dist/img-qrcode/<?= $resep['petugas'] ?>.png" alt=""><br>
                        <p style="font-size:12px">(<?= $resep['petugas'] ?>)</p>
                    </center>
                </td>
            </tr>
        </table>
    </div> -->

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>