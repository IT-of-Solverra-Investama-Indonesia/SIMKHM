<?php session_start() ?>
<?php
$id = htmlspecialchars($_GET['id']);
require '../dist/function.php';
$biaya = $koneksi->query("SELECT * FROM biaya_rawat WHERE idregis= '$id' ORDER BY id DESC LIMIT 1")->fetch_assoc();
$dataRawat = $koneksi->query("SELECT * FROM registrasi_rawat WHERE idrawat = '$id'")->fetch_assoc();
function konversiNomorHP($nomor)
{
    // Hilangkan semua karakter selain angka
    $nomor = preg_replace('/[^0-9]/', '', $nomor);

    // Jika nomor diawali dengan '0', ganti dengan '62'
    if (substr($nomor, 0, 1) === '0') {
        $nomor = '62' . substr($nomor, 1);
    }
    // Jika sudah diawali dengan '62', biarkan
    else if (substr($nomor, 0, 2) === '62') {
        // do nothing
    }
    // Jika diawali dengan '8', anggap sebagai '08' lalu ubah ke '628'
    else if (substr($nomor, 0, 1) === '8') {
        $nomor = '62' . $nomor;
    } else {
        // Format tidak dikenali, bisa dikembalikan null atau aslinya
        return null;
    }

    return $nomor;
}
function getFullUrl()
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ||
        $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

    return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}
?>
<?php if (!isset($_GET['print'])) { ?>
    <!doctype html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Nota TRNSKS-<?= $biaya['nota'] == '' ? $id : $biaya['nota'] ?></title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="shortcut icon" href="https://simkhm.id/wonorejo/admin/dist/assets/img/3.png" type="image/x-icon">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    </head>

    <body>
        <style>
            body {
                display: flex;
                justify-content: center;
                /* Memusatkan secara horizontal */
                align-items: center;
                /* Memusatkan secara vertikal */
                height: 100vh;
                /* Pastikan body memiliki tinggi penuh viewport */
                margin: 0;
                background: linear-gradient(to bottom, #3b7c47 50%, #ffffff 50%);
                /* Jika menggunakan gradien */
            }
        </style>
        <div class="container">
            <center>
                <div class="card shadow py-2 px-3 mt-0" style="max-width: 600px;">
                    <center>
                        <img src="https://simkhm.id/wonorejo/admin/dist/assets/img/3.png" style="width: 20%; margin-bottom: 10px;" alt="">
                        <h5>Nota TRNSKS-<?= $biaya['nota'] == '' ? $id : $biaya['nota'] ?></h5>
                    </center>
                    <table>
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Nama </td>
                                <td>: <?= $dataRawat['nama_pasien'] ?></td>
                            </tr>
                            <tr>
                                <td>Nomor RM </td>
                                <td>: <?= $dataRawat['no_rm'] ?></td>
                            </tr>
                            <tr>
                                <td>Jadwal Kunjungan</td>
                                <td>: <?= date('d F Y H:i', strtotime($dataRawat['jadwal'])) ?> (<?= $dataRawat['perawatan'] ?>)</td>
                            </tr>
                            <tr>
                                <td>Dokter </td>
                                <td>: <?= $dataRawat['dokter_rawat'] ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                    <table class="table table-striped table-bordered table-sm" style="font-size: 14px;">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <!-- <th>Potongan</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Biaya Poli</td>
                                <td>1x</td>
                                <td>:<?= number_format($biaya['poli'], 0, 0, '.') ?></td>
                            </tr>
                            <?php
                            $listTindakan = array_filter(explode('+', $biaya['biaya_lain']));
                            foreach ($listTindakan as $item) {
                            ?>
                                <tr>
                                    <td>Tindakan <?= $item ?></td>
                                    <td>1x</td>
                                    <td>
                                        <?php
                                        $BiayaTindakan = $koneksimaster->query("SELECT * FROM master_layanan WHERE nama_layanan = '" . $item . "'")->fetch_assoc();
                                        $getBiayaByCode = $koneksi->query("SELECT * FROM layanan WHERE kode_layanan = '" . $BiayaTindakan['id'] . "' AND idrm = '" . $dataRawat['no_rm'] . "' AND tgl_layanan = '" . $dataRawat['jadwal'] . "'")->fetch_assoc();
                                        ?>
                                        :<?= number_format(isset($getBiayaByCode['harga']) ? $getBiayaByCode['harga'] : $BiayaTindakan['harga'], 0, 0, '.') ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php
                            $getBiayaLab = $koneksi->query("SELECT * FROM lab WHERE id_lab = '$id'");
                            foreach ($getBiayaLab as $labBiaya) {
                            ?>
                                <tr>
                                    <td><?= $labBiaya['tipe_lab'] ?></td>
                                    <td>1x</td>
                                    <td>:<?= number_format($labBiaya['biaya'], 0, 0, '.') ?></td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td>Potongan Harga</td>
                                <td>1x</td>
                                <td>:<?= number_format(($biaya['potongan'] == '' ? '0' : $biaya['potongan']), 0, 0, '.') ?></td>
                            </tr>
                            <!-- <tr>
                        <td>Rp </td>
                        <td>Rp <?= number_format(($biaya['total_lain'] == '' ? '0' : $biaya['total_lain']), 0, 0, '.') ?></td>
                        <td>Rp <?= number_format(($biaya['biaya_lab'] == '' ? '0' : $biaya['biaya_lab']), 0, 0, '.') ?></td>
                        <td>Rp <?= number_format(($biaya['potongan'] == '' ? '0' : $biaya['potongan']), 0, 0, '.') ?></td>
                    </tr> -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2"><b>Total</b></td>
                                <td><b>:<?= number_format($biaya['poli'] + ($biaya['total_lain'] == '' ? '0' : $biaya['total_lain']) + ($biaya['biaya_lab'] == '' ? '0' : $biaya['biaya_lab']) - ($biaya['potongan'] == '' ? '0' : $biaya['potongan']), 0, 0, '.') ?></b></td>
                            </tr>
                        </tfoot>
                    </table>
                    <p align="right">
                        <b>Total : Rp <?= number_format($biaya['poli'] + ($biaya['total_lain'] == '' ? '0' : $biaya['total_lain']) + ($biaya['biaya_lab'] == '' ? '0' : $biaya['biaya_lab']) - ($biaya['potongan'] == '' ? '0' : $biaya['potongan']), 0, 0, '.') ?></b>
                    </p>

                    <p align="right">
                        <?php if (isset($_SESSION['shift'])) { ?>
                            <a href="../dist/index.php?halaman=daftarbayar" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-left"></i></a>
                            <?php
                            $getPasien = $koneksi->query("SELECT * FROM pasien WHERE no_rm = '" . $dataRawat['no_rm'] . "'")->fetch_assoc();
                            ?>
                            <a href="<?= getFullUrl() ?>&print" target="_blank" class="btn btn-sm btn-warning"><i class="bi bi-printer"></i></a>
                            <a href="<?= getFullUrl() ?>&sendWA" target="_blank" class="btn btn-sm btn-success"><i class="bi bi-whatsapp"></i></a>
                            <!-- <a href="https://wa.me/<?= konversiNomorHP($getPasien['nohp']) ?>?text=Berikut Nota Online Anda <?= getFullUrl() ?>" class="btn btn-sm btn-success"><i class="bi bi-whatsapp"></i></a> -->
                            <?php
                            if (isset($_GET['sendWA'])) {
                                $curl = curl_init();
                                include '../rawatjalan/api_token_wa.php';
                                $curl = curl_init();
                                $data = [
                                    'phone' => konversiNomorHP($getPasien['nohp']),
                                    'message' => 'Berikut Nota Online Anda ' . getFullUrl() . '',

                                ];
                                curl_setopt(
                                    $curl,
                                    CURLOPT_HTTPHEADER,
                                    array(
                                        "Authorization: $token",
                                    )
                                );
                                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
                                curl_setopt($curl, CURLOPT_URL,  "https://jogja.wablas.com/api/send-message");
                                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                                $result = curl_exec($curl);
                                curl_close($curl);
                                echo "<pre>";
                                print_r($result);

                                $koneksi->query("UPDATE biaya_rawat SET send_at = NOW() WHERE nota = '" . $biaya['nota'] . "'");

                                echo "
                                    <script>
                                        alert('Berhasil Kirim!');
                                        document.location.href='printNota.php?id=$_GET[id]';
                                    </script>
                                ";
                            }
                            ?>
                        <?php } ?>
                    </p>
                </div>
            </center>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js" integrity="sha384-VQqxDN0EQCkWoxt/0vsQvZswzTHUVOImccYmSyhJTp7kGtPed0Qcx8rK9h9YEgx+" crossorigin="anonymous"></script>
    </body>

    </html>
<?php } else { ?>
    <style>
        body {
            font-family: monospace;
            /* letter-spacing: px; */
        }
    </style>
    <div style="max-width: 58mm; padding: 2mm;">
        <center>
            <img src="https://simkhm.id/wonorejo/admin/dist/assets/img/3.png" style="width: 25%; margin-bottom: 10px;" alt="">
            <h5>Nota TRNSKS-<?= $biaya['nota'] == '' ? $id : $biaya['nota'] ?></h5>
        </center>
        <table style="font-size: 10px; ">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Nama </td>
                    <td>: <?= $dataRawat['nama_pasien'] ?></td>
                </tr>
                <tr>
                    <td>Nomor RM </td>
                    <td>: <?= $dataRawat['no_rm'] ?></td>
                </tr>
                <tr>
                    <td>Kunjungan</td>
                    <td>: <?= date('d F Y H:i', strtotime($dataRawat['jadwal'])) ?></td>
                </tr>
                <tr>
                    <td>Dokter </td>
                    <td>: <?= $dataRawat['dokter_rawat'] ?></td>
                </tr>
            </tbody>
        </table>
        <hr class="my-1">
        <table class="w-100" style="font-size: 11px;">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <!-- <th>Biaya Lab</th>
                        <th>Potongan</th> -->
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Biaya Poli</td>
                    <td>1x</td>
                    <td>:<?= number_format($biaya['poli'], 0, 0, '.') ?></td>
                </tr>
                <?php
                $listTindakan = array_filter(explode('+', $biaya['biaya_lain']));
                foreach ($listTindakan as $item) {
                ?>
                    <tr>
                        <td>Tindakan <?= $item ?></td>
                        <td>1x</td>
                        <td>
                            <?php
                            $BiayaTindakan = $koneksimaster->query("SELECT * FROM master_layanan WHERE nama_layanan = '" . $item . "'")->fetch_assoc();
                            $getBiayaByCode = $koneksi->query("SELECT * FROM layanan WHERE kode_layanan = '" . $BiayaTindakan['id'] . "' AND idrm = '" . $dataRawat['no_rm'] . "' AND tgl_layanan = '" . $dataRawat['jadwal'] . "'")->fetch_assoc();
                            ?>
                            :<?= number_format(isset($getBiayaByCode['harga']) ? $getBiayaByCode['harga'] : $BiayaTindakan['harga'], 0, 0, '.') ?>
                        </td>
                    </tr>
                <?php } ?>
                <?php
                $getBiayaLab = $koneksi->query("SELECT * FROM lab WHERE id_lab = '$id'");
                foreach ($getBiayaLab as $labBiaya) {
                ?>
                    <tr>
                        <td><?= $labBiaya['tipe_lab'] ?></td>
                        <td>1x</td>
                        <td>:<?= number_format($labBiaya['biaya'], 0, 0, '.') ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td>Potongan Harga</td>
                    <td>1x</td>
                    <td>:<?= number_format(($biaya['potongan'] == '' ? '0' : $biaya['potongan']), 0, 0, '.') ?></td>
                </tr>
                <!-- <tr>
                        <td>Rp </td>
                        <td>Rp <?= number_format(($biaya['total_lain'] == '' ? '0' : $biaya['total_lain']), 0, 0, '.') ?></td>
                        <td>Rp <?= number_format(($biaya['biaya_lab'] == '' ? '0' : $biaya['biaya_lab']), 0, 0, '.') ?></td>
                        <td>Rp <?= number_format(($biaya['potongan'] == '' ? '0' : $biaya['potongan']), 0, 0, '.') ?></td>
                    </tr> -->
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2"><b>Total</b></td>
                    <td><b>:<?= number_format($biaya['poli'] + ($biaya['total_lain'] == '' ? '0' : $biaya['total_lain']) + ($biaya['biaya_lab'] == '' ? '0' : $biaya['biaya_lab']) - ($biaya['potongan'] == '' ? '0' : $biaya['potongan']), 0, 0, '.') ?></b></td>
                </tr>
            </tfoot>
        </table>
        <p align="left" style="font-size: 12px; margin-top: 0px;">
            <!-- <b>Total : Rp </b> -->
            Kasir : <?= $dataRawat['kasir'] == '' ? $_SESSION['admin']['namalengkap'] : $dataRawat['kasir'] ?>
        </p>
    </div>
    <script>
        // window.print();
        // setTimeout(function() {
        //     window.close();
        // }, 1000);
    </script>
<?php } ?>