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
        SUM(IF(carabayar='spesialis anak',1,0)) AS spesialisAnak,
        SUM(IF(carabayar='spesialis penyakit dalam',1,0)) AS spesialisPenyakitDalam,
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
                    <th class="text-capitalize">Anak</th>
                    <th class="text-capitalize">PenyakitDalam</th>
                    <th class="text-capitalize">malam</th>
                    <th class="text-capitalize">Ranap</th>
                    <!--<th class="text-capitalize">kosmetik</th>
                        <th class="text-capitalize">Gigi Umum</th>
                        <th class="text-capitalize">Gigi BPJS</th>-->
                    <th class="text-capitalize">Lab poli</th>
                    <th class="text-capitalize">ODC</th>

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
                        <td><?php echo $poli['spesialisAnak'] ?> || <?php echo number_format($poli['spesialisAnak'] / $poli['harii'], 2) ?></td>
                        <td><?php echo $poli['spesialisPenyakitDalam'] ?> || <?php echo number_format($poli['spesialisPenyakitDalam'] / $poli['harii'], 2) ?></td>
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
                        <td>
                            <?php
                            $apiUrl = $baseUrlLama . "api_personal/api_dashboard.php?randomToken=" . htmlspecialchars($randomToken) . "&odc&bulan=" . $poli['bulan'] . "";
                            $params = [
                                'randomToken' => $randomToken,
                                'pendapatanpoliakuntan' => true,
                                'bulan' => $poli['bulan'], // Contoh format bulan (ubah sesuai kebutuhan)
                            ];
                            $response = callAPI($apiUrl, "GET", $params);
                            $responseData = json_decode($response, true);
                            if ($responseData['status'] === "Successfully" && !empty($responseData['data'])) {
                                echo number_format($totalAkuntan = $responseData['data'][0]['total'], 2);
                            } else {
                                echo $totalAkuntan = 0;
                            }
                            // echo htmlspecialchars($response)." ".$randomToken." ";
                            // echo $decryptedData = decrypt($randomToken, $key , $iv);;

                            ?>
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