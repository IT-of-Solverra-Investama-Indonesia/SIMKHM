<h3>Detail Dashboard</h3>
<div class="card shadow p-2">
  <form method="post">
    <div class="row">
      <div class="col-9">
        <select name="hal" id="" class="form-control">
          <option value="Poli">Poli</option>
          <option value="verif">Verif</option>
          <option value="polibulan">Poli Bulan</option>
          <option value="polibpjs">Poli BPJS</option>
          <option value="omsetKHM">Omset KHM Lama</option>
          <option value="RekapPasienOnlineOffline">Rekap Pasien Online Offline</option>
        </select>
      </div>
      <div class="col-3">
        <button name="searching" class="btn btn-primary"><i class="bi bi-search"></i></button>
      </div>
    </div>
  </form>
</div>
<?php
include "../dist/baseUrlAPI.php";
if (isset($_POST['searching'])) {
  $hal = htmlspecialchars($_POST['hal']);
  if ($hal == 'polibulan' or $hal == 'polibpjs') {
    echo "<script>document.location='index.php?halaman=dashboard_detail&$hal=" . date('y/m') . "'</script>";
  } else {
    echo "<script>document.location='index.php?halaman=dashboard_detail&$hal'</script>";
  }
}
?>
<!-- <a href="index.php?halaman=dashboard_detail&Poli" class="btn btn-sm btn-primary m-1" style="max-width: 190px; float: left;">Poli</a>
<a href="index.php?halaman=dashboard_detail&verif" class="btn btn-sm btn-primary m-1" style="max-width: 190px; float: left;">Verif</a>
<a href="index.php?halaman=dashboard_detail&polibulan=<?= date('y/m') ?>" class="btn btn-sm btn-primary m-1" style="max-width: 190px; float: left;">Poli Bulan</a>
<a href="index.php?halaman=dashboard_detail&polibpjs=<?= date('y/m') ?>" class="btn btn-sm btn-primary m-1" style="max-width: 190px; float: left;">Poli Bpjs</a>
<a href="index.php?halaman=dashboard_detail&omsetKHM" class="btn btn-sm btn-primary m-1" style="max-width: 190px; float: left;">Omset KHM</a>
<a href="index.php?halaman=dashboard_detail&RekapPasienOnlineOffline" class="btn btn-sm btn-primary m-1" style="max-width: 190px; float: left;">Rekap Pasien Online Offline</a> -->
<br>
<?php if (isset($_GET['Poli'])) { ?>
  <?php
  $bulanSaatIni = date('y/m');

  $bulan6Lalu = date('y/m', strtotime('-6 months'));

  $koneksi->query("DROP TABLE hari");
  $koneksi->query(" 
      CREATE TABLE IF NOT EXISTS hari 
      SELECT DATE_FORMAT(jadwal, '%y/%m') as bulan, DATE_FORMAT(jadwal, '%y-%m-%d') as hari from registrasi_rawat WHERE DATE_FORMAT(jadwal, '%y/%m') >= '$bulan6Lalu' AND DATE_FORMAT(jadwal, '%y/%m') <= '$bulanSaatIni' group by bulan,hari ");
  $koneksi->query("DROP TABLE hari_jumlah");
  $koneksi->query(" 
      CREATE TABLE IF NOT EXISTS hari_jumlah 
      SELECT bulan, count(hari) as harii from hari group by bulan");

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
        FROM registrasi_rawat where status_antri = 'Datang' or status_antri = 'Pembayaran'  AND DATE_FORMAT(jadwal, '%y/%m') >= '$bulan6Lalu' AND DATE_FORMAT(jadwal, '%y/%m') <= '$bulanSaatIni'group by bulan order by bulan desc
    ");

  $ambilpoli = $koneksi->query("SELECT * from poli_jumlah JOIN hari_jumlah On poli_jumlah.bulan=hari_jumlah.bulan order by poli_jumlah.bulan desc");
  function callAPI($url, $method, $data = [])
  {
    $ch = curl_init();

    // Set opsi untuk cURL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    // Tentukan metode (GET/POST)
    if ($method == "POST") {
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    }

    // Eksekusi cURL dan dapatkan respons
    $response = curl_exec($ch);

    // Periksa jika ada kesalahan
    if (curl_errno($ch)) {
      echo "Error: " . curl_error($ch);
    }

    // Tutup cURL
    curl_close($ch);

    return $response;
  }


  // Mengenkripsi token untuk dikirim
  function encrypt($data, $key, $iv)
  {
    $cipher = "AES-256-CBC"; // Algoritma enkripsi
    $encrypted = openssl_encrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    return rtrim(base64_encode($encrypted), '=');
  }
  function decrypt($encryptedData, $key, $iv)
  {
    $cipher = "AES-256-CBC"; // Algoritma enkripsi
    $encryptedData = str_pad($encryptedData, strlen($encryptedData) % 4 === 0 ? strlen($encryptedData) : strlen($encryptedData) + 4 - (strlen($encryptedData) % 4), '=', STR_PAD_RIGHT);
    return openssl_decrypt(base64_decode($encryptedData), $cipher, $key, OPENSSL_RAW_DATA, $iv);
  }

  $key = "D5UZa-SY0FL2[+nx;;qJCI2SuVaun&:5";
  $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length("AES-256-CBC"));

  $randomToken = encrypt("Solverra Investama", $key, $iv);
  ?>
  <div class="card p-2">
    <b>POLI (Hanya Ditampilkan 6 Bulan Terakhir) | <a href="index.php?halaman=dashboard_detail&Poli">Dashboard Lengkap</a> | Poli Daerah klik <a href="?halaman=polidaerah">disini</a></b>
    <div href="index.php?halaman=poli">
      <div style="font-size: 12px;" class="table-responsive">
        <!-- <div class="table-responsive"> -->
        <table class="table table-bordered">
          <!-- Pasien Poli, Pendapatan dan Biaya. || Poli Perdaerah, klik <a href="index.php?halaman=polidaerah" target="_blank">disini</a> ||  
                      <a href="index.php?halaman=polilama" target="_blank">barulama</a> -->
          <tr>
            <th class="text-capitalize">bulan</th>
            <th class="text-capitalize">hari</th>
            <th class="text-capitalize">umum</th>
            <th class="text-capitalize">bpjs</th>
            <th class="text-capitalize">malam</th>
            <th class="text-capitalize">Ranap</th>
            <!--<th class="text-capitalize">kosmetik</th>
                        <th class="text-capitalize">Gigi Umum</th>
                        <th class="text-capitalize">Gigi BPJS</th>-->
            <th class="text-capitalize">Lab poli</th>
            <!--<th class="text-capitalize">Vit C</th>
                        <th class="text-capitalize">ODC</th>
                        <th class="text-capitalize">Homecare</th> -->
            <th class="text-capitalize">jumlah (datang)</th>
            <th class="text-capitalize">pendapatan <br>(kasir)</th>
            <!-- <th class="text-capitalize">obatPoli/pasien <br>(kasir)</th> -->
            <th class="text-capitalize">pendapatan <br>(akuntan)</th>
            <th class="text-capitalize">Rp/hr <br>(akuntan)</th>
            <th class="text-capitalize">Rp/umum <br>(akuntan)</th>
            <!-- <th class="text-capitalize">obat/pasien <br>(akuntan)</th>
                        <th class="text-capitalize">igd</th> -->


          </tr>
          <?php while ($poli = $ambilpoli->fetch_assoc()) { ?>
            <tr>
              <td>
                <a href="index.php?halaman=dashboard_detail&polibulan=<?php echo $bulan = $poli['bulan'] ?> ">
                  <?php echo $bulan = $poli['bulan'] ?>
                </a>
              </td>
              <td><?php echo $poli['harii'] ?></td>
              <td><?php echo $poli['umum'] ?> || <?php echo number_format($poli['umum'] / $poli['harii'], 2) ?></td>
              <td>
                <a href="index.php?halaman=dashboard_detail&polibpjs=<?php echo $poli['bulan'] ?> ">
                  <?php echo $poli['bpjs'] ?> || <?php echo number_format($poli['bpjs'] / $poli['harii'], 2) ?>
                </a>
              </td>
              <td><?php echo $poli['malam'] ?> || <?php echo number_format($poli['malam'] / $poli['harii'], 2) ?></td>
              <?php
              $getRanap = $koneksi->query("SELECT COUNT(*) as jum FROM registrasi_rawat WHERE perawatan = 'Rawat Inap' AND DATE_FORMAT(jadwal, '%y/%m') = '$bulan'")->fetch_assoc();
              ?>
              <td>
                <a href="index.php?halaman=daftarrmedis&all&perawatan=Rawat Inap&bulan=<?= $bulan ?>">
                  <?= $getRanap['jum'] ?> || <?= number_format($getRanap['jum'] / $poli['harii'], 2) ?>
                </a>
              </td>
              <!-- <td><?php echo $poli['kosmetik'] ?>  ||  <?php echo number_format($poli['kosmetik'] / $poli['harii'], 2) ?></td>
                          <td><a href="index.php?halaman=kasir1shift&gigiumum=<?php echo $bulan = $poli['bulan'] ?> "><?php echo $poli['gigiumum'] ?> ||  <?php echo number_format($poli['gigiumum'] / $poli['harii'], 2) ?></a></td>
                          <td><a href="index.php?halaman=kasir1shift&gigibpjs=<?php echo $bulan = $poli['bulan'] ?> "><?php echo $poli['gigibpjs'] ?>  ||  <?php echo number_format($poli['gigibpjs'] / $poli['harii'], 2) ?></a></td> -->
              <td>
                <?php
                $getJumlahLab = $koneksi->query("SELECT COUNT(*) as jumlahLab FROM registrasi_rawat INNER JOIN lab ON lab.id_lab_inap = registrasi_rawat.idrawat WHERE DATE_FORMAT(jadwal, '%y/%m') = '$poli[bulan]'")->fetch_assoc();
                ?>
                <?php echo $getJumlahLab['jumlahLab'] ?> || <?php echo number_format($getJumlahLab['jumlahLab'] / $poli['harii'], 2) ?>
              </td>
              <!-- <td><?php echo $poli['vitc'] ?>  ||  <?php echo number_format($poli['vitc'] / $poli['harii'], 2) ?></td>
                          <td><?php echo $poli['ODC'] ?>  ||  <?php echo number_format($poli['ODC'] / $poli['harii'], 2) ?></td>
                          <td><?php echo $poli['homecare'] ?>  ||  <?php echo number_format($poli['homecare'] / $poli['harii'], 2) ?></td> -->
              <td><?php echo $poli['jumlah'] ?> || <?php echo number_format($poli['jumlah'] / $poli['harii'], 2) ?></td>
              <?php
              //kasir 
              $ambilkasir = $koneksi->query("SELECT DATE_FORMAT(created_at, '%y/%m') as bulan, sum(poli+biaya_lain) as pendapatanpoli FROM biaya_rawat WHERE DATE_FORMAT(created_at, '%y/%m')='$poli[bulan]' group by bulan;")->fetch_assoc();
              ?>
              <td>
                <?php echo number_format($ambilkasir['pendapatanpoli']) ?>
              </td>
              <td>
                <?php
                $apiUrl = $baseUrlLama . "api_personal/api_dashboard.php?randomToken=" . htmlspecialchars($randomToken) . "&pendapatanpoliakuntan&bulan=" . $poli['bulan'] . "";
                $params = [
                  'randomToken' => $randomToken,
                  'pendapatanpoliakuntan' => true,
                  'bulan' => $poli['bulan'], // Contoh format bulan (ubah sesuai kebutuhan)
                ];
                $response = callAPI($apiUrl, "GET", $params);
                $responseData = json_decode($response, true);
                if ($responseData['status'] === "Successfully" && !empty($responseData['data'])) {
                  echo number_format($totalAkuntan = $responseData['data'][0]['total'], 1, 0, '.');
                } else {
                  echo $totalAkuntan = 0;
                }
                // echo htmlspecialchars($response)." ".$randomToken." ";
                // echo $decryptedData = decrypt($randomToken, $key , $iv);;

                ?>
              </td>
              <td>
                <?= number_format($totalAkuntan / $poli['harii'], 0, 0, '.') ?>
              </td>
              <td>
                <?= number_format($poli['umum'] != 0 ? $totalAkuntan / $poli['umum'] : 0, 0, 0, '.') ?>
              </td>
              <!-- <td>

                            </td> -->
            </tr>
          <?php } ?>
        </table>
        <!-- </div> -->
      </div>
    </div>
  </div>
<?php } elseif (isset($_GET['verif'])) { ?>
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
<?php } elseif (isset($_GET['polibulan'])) { ?>
  <div class="card shadow p-2">
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
          $getData = $koneksi->query("SELECT registrasi_rawat.*, pasien.nama_lengkap, pasien.nohp FROM registrasi_rawat INNER JOIN pasien ON pasien.no_rm = registrasi_rawat.no_rm WHERE DATE_FORMAT(jadwal, '%y/%m') = '" . htmlspecialchars($_GET['polibulan']) . "' ORDER BY idrawat DESC");
          foreach ($getData as $data) {
          ?>
            <tr>
              <td><?= $data['idrawat'] ?></td>
              <td><?= $data['jadwal'] ?></td>
              <td><?= $data['nama_lengkap'] ?></td>
              <td><?= $data['no_rm'] ?></td>
              <td><?= $data['nohp'] ?></td>
              <td><?= $data['carabayar'] ?></td>
              <td><?= $data['status_antri'] ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
<?php } elseif (isset($_GET['polibpjs'])) { ?>
  <div class="card shadow p-2">
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
          $getData = $koneksi->query("SELECT registrasi_rawat.*, pasien.nama_lengkap, pasien.nohp FROM registrasi_rawat INNER JOIN pasien ON pasien.no_rm = registrasi_rawat.no_rm WHERE DATE_FORMAT(jadwal, '%y/%m') = '" . htmlspecialchars($_GET['polibpjs']) . "' AND carabayar = 'bpjs' ORDER BY idrawat DESC");
          foreach ($getData as $data) {
          ?>
            <tr>
              <td><?= $data['idrawat'] ?></td>
              <td><?= $data['jadwal'] ?></td>
              <td><?= $data['nama_lengkap'] ?></td>
              <td><?= $data['no_rm'] ?></td>
              <td><?= $data['nohp'] ?></td>
              <td><?= $data['carabayar'] ?></td>
              <td><?= $data['status_antri'] ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
<?php } elseif (isset($_GET['omsetKHM'])) { ?>
  <?php
  // Panggil API
  $url = $baseUrlLama . "api_personal/api_dashboard_omset.php?OmsetKHM";
  $response = file_get_contents($url);
  $data = json_decode($response, true);
  ?>
  <div class="card shadow p-2">
    <div class="table-responsive">
      <table class="table table-striped table-hover" style="font-size: 12px;">
        <thead>
          <tr>
            <th>Bulan</th>
            <th>Hari</th>
            <th>Omset</th>
            <th>Per Hari</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($data['status'] == "Successfully") {
            foreach ($data['data'] as $row) {
              $omset = (int) $row['omset']; // Ubah omset ke integer
              $harii = (int) $row['harii']; // Ubah hari ke integer
              $perhari = $harii > 0 ? $omset / $harii : 0; // Hindari pembagian nol

              echo "<tr>";
              echo "<td>" . htmlspecialchars($row['bulan']) . "</td>";
              echo "<td>" . htmlspecialchars($row['harii']) . "</td>";
              echo "<td>Rp " . number_format($omset, 0, ',', '.') . "</td>";
              echo "<td>Rp " . number_format($perhari, 0, ',', '.') . "</td>";
              echo "</tr>";
            }
          } else {
            echo "<tr><td colspan='4'>Gagal mengambil data</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
<?php } elseif (isset($_GET['RekapPasienOnlineOffline'])) { ?>
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
<?php } else { ?>
  <div class="card shadow p-2">
    <span><i>Pilih Terlebih Dahulu Dashboard Yang Ingin Anda Lihat</i></span>
  </div>
<?php } ?>