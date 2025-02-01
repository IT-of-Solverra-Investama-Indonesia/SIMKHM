
<?php if(isset($_GET['Poli'])){?>
    
    <?php
        $bulanSaatIni = date('y/m');
        $bulan6Lalu = date('y/m', strtotime('-6 months'));

        $koneksi->query("DROP TABLE hari");
        $koneksi->query(" 
            CREATE TABLE IF NOT EXISTS hari 
            SELECT DATE_FORMAT(jadwal, '%y/%m') as bulan, DATE_FORMAT(jadwal, '%y-%m-%d') as hari from registrasi_rawat  group by bulan,hari 
        ");

        $koneksi->query("DROP TABLE hari_jumlah");
        $koneksi->query(" 
            CREATE TABLE IF NOT EXISTS hari_jumlah 
            SELECT bulan, count(hari) as harii from hari group by bulan
        ");

        $koneksi->query("DROP TABLE poli_jumlah");
        $koneksi->query(" 
            CREATE TABLE IF NOT EXISTS poli_jumlah 
            SELECT DATE_FORMAT(jadwal, '%y/%m') as bulan,
            SUM(IF(carabayar='umum',1,0)) AS umum,
            SUM(IF(carabayar='bpjs',1,0)) AS bpjs,
            SUM(IF(carabayar='malam',1,0)) AS malam,
            SUM(IF(kategori='online',1,0)) AS online,
            SUM(IF(kategori='offline',1,0)) AS offline,
            COUNT(no_rm) AS jumlah
            FROM registrasi_rawat where status_antri = 'Datang' or status_antri = 'Pembayaran' group by bulan order by bulan desc
        ");

        $ambilpoli = $koneksi->query("SELECT * from poli_jumlah JOIN hari_jumlah On poli_jumlah.bulan=hari_jumlah.bulan order by poli_jumlah.bulan desc LIMIT 9 ");
    ?>
            <div class="col-12">
              <div class="card p-2">
                <b>POLI (Hanya Ditampilkan 6 Bulan Terakhir) | <a href="index.php?halaman=dashboard_detail&Poli">Dashboard Lengkap</a> | Poli Daerah klik <a href="?halaman=polidaerah">disini</a></b>
                <a href="index.php?halaman=poli">
                  <div style="overflow: scroll;" class="table-responsive">
                    <!-- <div class="table-responsive"> -->
                    <table class="table table-bordered">
                      <!-- Pasien Poli, Pendapatan dan Biaya. || Poli Perdaerah, klik <a href="index.php?halaman=polidaerah" target="_blank">disini</a> ||  
                      <a href="index.php?halaman=polilama" target="_blank">barulama</a> -->
                      <tr>
                        <th>bulan</th>
                        <th>hari</th>
                        <th>umum</th>
                        <th>bpjs</th>
                        <th>malam</th>
                        <!-- <th>kosmetik</th>
                        <th>Gigi Umum</th>
                        <th>Gigi BPJS</th>
                        <th>Lab poli</th>
                        <th>Vit C</th>
                        <th>ODC</th>
                        <th>Homecare</th> -->
                        <th>jumlah (datang)</th>
                        <th>pendapatan <br>(kasir)</th>
                        <th>obat/pasien <br>(kasir)</th>
                        <!-- <th>pendapatan <br>(akuntan)</th>
                        <th>Rp/hr <br>(akuntan)</th>
                        <th>Rp/umum <br>(akuntan)</th>
                        <th>obat/pasien <br>(akuntan)</th>
                        <th>igd</th> -->


                      </tr>
                      <?php while ($poli = $ambilpoli->fetch_assoc()) { ?>
                        <tr>
                          <td>
                            <!-- <a href="index.php?halaman=detailkunjungan&bulan=<?php echo $bulan = $poli['bulan'] ?> "> -->
                            <?php echo $bulan = $poli['bulan'] ?>
                            <!-- </a> -->
                          </td>
                          <td><?php echo $poli['harii'] ?></td>
                          <td><?php echo $poli['umum'] ?> || <?php echo number_format($poli['umum'] / $poli['harii'], 2) ?>
                          </td>
                          <td><?php echo $poli['bpjs'] ?> || <?php echo number_format($poli['bpjs'] / $poli['harii'], 2) ?>
                          </td>
                          <td><?php echo $poli['malam'] ?> || <?php echo number_format($poli['malam'] / $poli['harii'], 2) ?>
                          </td>
                          <!-- <td><?php echo $poli['kosmetik'] ?>  ||  <?php echo number_format($poli['kosmetik'] / $poli['harii'], 2) ?></td>
                          <td><a href="index.php?halaman=kasir1shift&gigiumum=<?php echo $bulan = $poli['bulan'] ?> "><?php echo $poli['gigiumum'] ?> ||  <?php echo number_format($poli['gigiumum'] / $poli['harii'], 2) ?></a></td>
                          <td><a href="index.php?halaman=kasir1shift&gigibpjs=<?php echo $bulan = $poli['bulan'] ?> "><?php echo $poli['gigibpjs'] ?>  ||  <?php echo number_format($poli['gigibpjs'] / $poli['harii'], 2) ?></a></td>
                          <td><?php echo $poli['lab'] ?>  ||  <?php echo number_format($poli['lab'] / $poli['harii'], 2) ?></td>
                          <td><?php echo $poli['vitc'] ?>  ||  <?php echo number_format($poli['vitc'] / $poli['harii'], 2) ?></td>
                          <td><?php echo $poli['ODC'] ?>  ||  <?php echo number_format($poli['ODC'] / $poli['harii'], 2) ?></td>
                          <td><?php echo $poli['homecare'] ?>  ||  <?php echo number_format($poli['homecare'] / $poli['harii'], 2) ?></td> -->
                          <td><?php echo $poli['jumlah'] ?> ||
                            <?php echo number_format($poli['jumlah'] / $poli['harii'], 2) ?></td>

                          <?php
                          //kasir 
                          $ambilkasir = $koneksi->query("SELECT DATE_FORMAT(created_at, '%y/%m') as bulan, sum(poli+biaya_lain) as pendapatanpoli FROM biaya_rawat where DATE_FORMAT(created_at, '%y/%m')='$bulan' group by bulan;");
                          ?>
                          <?php foreach ($ambilkasir as $kasir) { ?>
                            <td><?php echo number_format($kasir['pendapatanpoli']) ?></td>
                          <?php } ?>
                        </tr>
                      <?php } ?>
                    </table>
                    <!-- </div> -->
                  </div>
              </div>
            </div>
<?php }elseif(isset($_GET['verif'])){?>
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
<?php }elseif(isset($_GET['polibulan'])){?>
  <div class="card shadow-sm p-2">
    <div class="table-responsive">
      <table class="table table-hover table-striped">
        <thead>
          <tr>
            <th>IdRawat</th>
            <th>Tgl Kunjungan</th>
            <th>Nama</th>
            <th>NoRm</th>
            <th>NoHp</th>
            <th>CaraBayar</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $getData = $koneksi->query("SELECT registrasi_rawat.*, pasien.nama_lengkap, pasien.nohp FROM registrasi_rawat INNER JOIN pasien ON pasien.no_rm = registrasi_rawat.no_rm WHERE DATE_FORMAT(jadwal, '%y/%m') = '".htmlspecialchars($_GET['polibulan'])."' ORDER BY idrawat DESC");
            foreach ($getData as $data) {
          ?>
            <tr>
              <td><?= $data['idrawat']?></td>
              <td><?= $data['jadwal']?></td>
              <td><?= $data['nama_lengkap']?></td>
              <td><?= $data['no_rm']?></td>
              <td><?= $data['nohp']?></td>
              <td><?= $data['carabayar']?></td>
              <td><?= $data['status_antri']?></td>
            </tr>
          <?php }?>
        </tbody>
      </table>
    </div>
  </div>
<?php }elseif(isset($_GET['polibpjs'])){?>
  <div class="card shadow-sm p-2">
    <div class="table-responsive">
      <table class="table table-hover table-striped">
        <thead>
          <tr>
            <th>IdRawat</th>
            <th>Tgl Kunjungan</th>
            <th>Nama</th>
            <th>NoRm</th>
            <th>NoHp</th>
            <th>CaraBayar</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $getData = $koneksi->query("SELECT registrasi_rawat.*, pasien.nama_lengkap, pasien.nohp FROM registrasi_rawat INNER JOIN pasien ON pasien.no_rm = registrasi_rawat.no_rm WHERE DATE_FORMAT(jadwal, '%y/%m') = '".htmlspecialchars($_GET['polibpjs'])."' AND carabayar = 'bpjs' ORDER BY idrawat DESC");
            foreach ($getData as $data) {
          ?>
            <tr>
              <td><?= $data['idrawat']?></td>
              <td><?= $data['jadwal']?></td>
              <td><?= $data['nama_lengkap']?></td>
              <td><?= $data['no_rm']?></td>
              <td><?= $data['nohp']?></td>
              <td><?= $data['carabayar']?></td>
              <td><?= $data['status_antri']?></td>
            </tr>
          <?php }?>
        </tbody>
      </table>
    </div>
  </div>
<?php }?>


