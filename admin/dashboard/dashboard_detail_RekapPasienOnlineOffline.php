<?php
$ambilpoli = $koneksi->query("SELECT * from poli_jumlah JOIN hari_jumlah On poli_jumlah.bulan=hari_jumlah.bulan order by poli_jumlah.bulan desc");
?>
<div class="col-12">
    <div class="card p-2">
        <!-- <a href="index.php?halaman=poli"> -->
        <div style="height: 650px; overflow: scroll;" class="table-responsive">
            <!-- <div class="table-responsive"> -->
            <table class="table table-bordered">
                <center>
                    Rekap Pasien Online dan Offline
                </center>
                <!-- Pasien Poli, Pendapatan dan Biaya. || Poli Perdaerah, klik <a href="index.php?halaman=polidaerah" target="_blank">disini</a> ||  
                    <a href="index.php?halaman=polilama" target="_blank">barulama</a> -->
                <tr>
                    <th>bulan</th>
                    <th>online</th>
                    <th>offline</th>

                </tr>
                <?php while ($poli = $ambilpoli->fetch_assoc()) { ?>
                    <tr>
                        <td>
                            <?php echo $bulan = $poli['bulan'] ?>
                        </td>
                        <td><a
                                href="index.php?halaman=daftarregistrasi&bulan=<?php echo $bulan = $poli['bulan'] ?>&on"><?= $poli['online'] ?></a>
                        </td>
                        <td><a
                                href="index.php?halaman=daftarregistrasi&bulan=<?php echo $bulan = $poli['bulan'] ?>&off"><?= $poli['offline'] ?></a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <!-- </div> -->
        </div>
    </div>
</div>