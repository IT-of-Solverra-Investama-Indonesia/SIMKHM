<?php
// $ver = $koneksi->query("SELECT *,DATE_FORMAT(tgl_pasien, '%y/%m') as bulan, dokter, count(dokter) as jml FROM obat_rm JOIN rekam_medis WHERE verif_dokter = '' and tgl_pasien = tgl_rm and norm = idrm group by bulan, dokter order by bulan desc");   

$ver = $koneksi->query("SELECT *, COUNT(*) as jml,  DATE_FORMAT(registrasi_rawat.jadwal, '%Y/%m') as bulan FROM registrasi_rawat INNER JOIN obat_rm ON obat_rm.idrm = registrasi_rawat.no_rm AND DATE_FORMAT(tgl_pasien, '%Y-%m-%d') = DATE_FORMAT(registrasi_rawat.jadwal, '%Y-%m-%d') WHERE verif_dokter = '' AND (status_antri='Pembayaran' or status_antri='Selesai') and perawatan ='Rawat Jalan' GROUP BY bulan, dokter_rawat ORDER BY bulan DESC");
?>
<div class="col-12">
    <div class="card p-2">
        <!-- <a href="index.php?halaman=poli"> -->
        <div style="overflow: scroll;" class="table-responsive">
            <!-- <div class="table-responsive"> -->
            <table class="table table-bordered">
                <center>
                    Rekap Verif Obat Dokter (Ditampilkan Bulan Ini Saja) || <a href="index.php?halaman=dashboard_detail&verif">Lengkap</a>
                </center>

                <tr>
                    <th>bulan</th>
                    <th>dokter</th>
                    <th>belum verif</th>

                </tr>
                <?php foreach ($ver as $verif) { ?>
                    <tr>
                        <td>
                            <?php echo $bulan = $verif['bulan'] ?>
                        </td>
                        <td><?= $verif['dokter_rawat'] ?></td>
                        <td>
                            <?php
                            // $getJum = $koneksi->query("SELECT COUNT(*) AS jml FROM rekam_medis WHERE tgl_rm = '$verif[tgl_pasien]' and norm = '$verif[idrm]' and dokter = '$verif[dokter]' GROUP BY norm,tgl_rm ")->fetch_assoc(); 
                            ?>
                            <?= $verif['jml'] ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <!-- </div> -->
        </div>
    </div>
</div>