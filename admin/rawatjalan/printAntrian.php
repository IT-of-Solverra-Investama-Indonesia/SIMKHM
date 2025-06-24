<?php
require '../dist/function.php';
$idrawat = htmlspecialchars($_GET['idrawat']);
$singleData = $koneksi->query("SELECT * FROM registrasi_rawat WHERE idrawat = '" . htmlspecialchars($_GET['idrawat']) . "'")->fetch_assoc();
?>
<style>
    @media print {
        @page {
            size: 88mm auto;
            /* margin: 0; */
        }
    }

    .nota {
        width: 88mm;
        /* padding: 5px; */
        font-family: monospace;
        line-height: 1.2;
    }
</style>

<div class="nota" style="font-family: monospace;">
    <center style="margin-bottom: 15px;">
        <img src="https://simkhm.id/wonorejo/admin/dist/assets/img/3.png" style="width: 20%; margin-bottom: 5px;" alt=""><br>
        <b style="text-transform: capitalize;margin-bottom: 15px; font-size: 50px;">Antrian <?= $singleData['antrian'] ?></b>
    </center>
    <table style="font-size: 15px;margin-top: 15px;">
        <thead>
            <tr>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Nama</td>
                <td><b>: <?= $singleData['nama_pasien'] ?></b></td>
            </tr>
            <tr>
                <td>No.RM</td>
                <td><b>: <?= $singleData['no_rm'] ?></b></td>
            </tr>
            <tr>
                <td>Antrian</td>
                <td><b style="text-transform: capitalize;">: <?= $singleData['antrian'] ?> (<?= $singleData['shift'] ?>)</b></td>
            </tr>
            <tr>
                <td>Jadwal</td>
                <td><b>: <?= date('d F Y H:i', strtotime($singleData['jadwal'])) ?> WIB</b></td>
            </tr>
            <tr>
                <td>Dokter</td>
                <td><b style="text-transform: capitalize;">: <?= $singleData['dokter_rawat'] ?></b></td>
            </tr>
            <tr>
                <td>Petugas</td>
                <td>
                    <b style="text-transform: capitalize;">
                        <?php
                        $getAdmin = $koneksi->query("SELECT * FROM admin WHERE username='$singleData[petugaspoli]' OR namalengkap = '$singleData[petugaspoli]'")->fetch_assoc();
                        ?>
                        : <?= $getAdmin['namalengkap'] ?>
                    </b>
                </td>
            </tr>
        </tbody>
    </table>
    <hr style="margin: 10px 0;">
    <center>
        Mohon datang tepat waktu, bila saat waktu dipanggil tidak hadir, maka nomer antrian akan tidak berlaku
    </center>
</div>
<script>
    window.print();
</script>