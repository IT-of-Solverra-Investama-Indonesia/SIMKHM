<?php 
error_reporting(0);
include '../dist/function.php'; 
?>
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
            padding: 7px;
        }

        .nota {
            width: 23.625rem;
            height: 30.75rem;
        }

        .header {
            text-align: center;
            /* margin-bottom: 20px; */
            font-size: 13px;
        }

        .section-title {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 20px;
        }

        .content {
            margin-top: 20px;
            font-size: 12px;
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
    if (empty($rekammedis)) {
        $registrasi = $koneksi->query("SELECT * FROM registrasi_rawat WHERE no_rm = '" . htmlspecialchars($_GET['id']) . "' AND jadwal = '$_GET[tgl]'")->fetch_assoc();
    }

    $kajian = $koneksi->query("SELECT * FROM kajian_awal WHERE norm = '" . htmlspecialchars($_GET['id']) . "' AND tgl_rm = '" . date('Y-m-d', strtotime($_GET['tgl'])) . "' ORDER BY id_rm DESC LIMIT 1")->fetch_assoc();
    if ($registrasi['perawatan'] == 'Rawat Inap') {
        $kajian = $koneksi->query("SELECT * FROM kajian_awal_inap WHERE norm = '" . htmlspecialchars($_GET['id']) . "' AND tgl_rm = '" . date('Y-m-d', strtotime($_GET['tgl'])) . "' ORDER BY id_rm DESC LIMIT 1")->fetch_assoc();
    }

    $dataObat = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND rekam_medis_id = '" . htmlspecialchars($_GET['idrekammedis']) . "' AND DATE_FORMAT(tgl_pasien, '%Y-%m-%d') = '" . date('Y-m-d', strtotime($_GET['tgl'])) . "'");
    if ($dataObat->num_rows == 0) {
        $dataObat = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND DATE_FORMAT(tgl_pasien, '%Y-%m-%d') = '" . date('Y-m-d', strtotime($_GET['tgl'])) . "'");
    }

    $dokter = $koneksi->query("SELECT * FROM admin WHERE namalengkap = '$registrasi[dokter_rawat]' LIMIT 1")->fetch_assoc();

    $resep = $koneksi->query("SELECT *, COUNT(*) as jumlah FROM resep INNER JOIN rekam_medis ON rekam_medis.norm = resep.no_rm WHERE resep.no_rm = '$_GET[id]' AND resep.jadwal = '$_GET[tgl]'")->fetch_assoc();

    $telaah = $koneksi->query("SELECT * FROM telaah_resep WHERE no_rm = '$_GET[id]' AND jadwal = '$_GET[tgl]'")->fetch_assoc();

    $pasien = $koneksi->query("SELECT * FROM pasien WHERE TRIM(no_rm) = '" . htmlspecialchars($_GET['id']) . "'")->fetch_assoc();
    ?>

    <!-- <script>
        window.print()
    </script> -->
    <div class="nota">
        <div class="header">
            <b>
                <p>KLINIK HUSADA MULIA <br /> Instansi Farmasi</p>
            </b>
            <hr>
        </div>
        <div class="content">
            <table>
                <tr>
                    <td>No.RM</td>
                    <td>:</td>
                    <td><b><?= $pasien['no_rm'] ?></b></td>
                </tr>
                <tr>
                    <td>Pasien</td>
                    <td>:</td>
                    <?php
                    // Hitung umur dari tanggal lahir pasien
                    $dob = isset($pasien['tgl_lahir']) ? $pasien['tgl_lahir'] : null;
                    $umur = '';
                    if ($dob) {
                        $dobDate = new DateTime($dob);
                        $now = new DateTime($_GET['tgl']);
                        $diff = $dobDate->diff($now);
                        // Format umur menjadi tahun dan bulan
                        if ($diff->y > 0) {
                            $umur = $diff->y . 'Thn';
                        }
                        if ($diff->y = 0) {
                            $umur = $diff->m . 'Bln';
                        }
                    }
                    ?>
                    <td><?= $pasien['nama_lengkap'] ?> (<?= $umur ?>)</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td><span style="font-size: 10px;"><?= $pasien['alamat'] ?>, <?= $pasien['kelurahan'] ?>, <?= $pasien['kecamatan'] ?>, <?= $pasien['kota'] ?>, <?= $pasien['provinsi'] ?></span></td>
                </tr>
                <tr>
                    <td>BB</td>
                    <td>:</td>
                    <td><?= $kajian['bb'] ?>KG</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>:</td>
                    <td><?= date('d-m-Y', strtotime($_GET['tgl'])) ?></td>
                </tr>
                <tr>
                    <td>Dokter</td>
                    <td>:</td>
                    <td><?= $dokter['namalengkap'] ?></td>
                </tr>
                <tr>
                    <td>SIP</td>
                    <td>:</td>
                    <td><?= $dokter['SIP'] ?></td>
                </tr>
                <tr>
                    <td>Perawatan</td>
                    <td>:</td>
                    <td><?= $registrasi['perawatan'] ?> <span style="text-transform: capitalize">(<?= $registrasi['carabayar'] ?>)</span></td>
                </tr>
                <tr>
                    <td>Diagnosa</td>
                    <td>:</td>
                    <td style="text-transform: capitalize">
                        <?php
                        if ($registrasi['perawatan'] == 'Rawat Inap') {
                            echo htmlspecialchars($kajian['diagnosa_masuk']);
                        } else {
                            echo htmlspecialchars($kajian['diagnois_prwt']);
                        }
                        ?>
                    </td>
                </tr>
            </table>
            <div class="list-obat mt-2">
                <h6 class="mb-0">Daftar Obat</h6>
                <?php
                $no = 1;
                foreach ($dataObat as $obat) { ?>
                    <div class="item-obat">
                        <li>
                            <b><?= htmlspecialchars($obat['nama_obat']) ?></b> <?= $obat['dosis1_obat'] == '' ? '' : htmlspecialchars($obat['dosis1_obat'] . 'x' . $obat['dosis2_obat']) ?> <?= $obat['petunjuk_obat'] == '' ? '' : '(' . htmlspecialchars($obat['petunjuk_obat']) . ')' ?> <?= htmlspecialchars($obat['jml_dokter']) ?> pcs
                        </li>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>