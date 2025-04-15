<?php
$username = $_SESSION['admin']['username'];
$dokter = $_SESSION['dokter_rawat'];
// var_dump($dokter);
$ambil = $koneksi->query("SELECT * FROM log_user ORDER BY idlog DESC;");
// $user=$ambil->fetch_assoc();

if (!isset($_SESSION['login'])) {
  header("location:login.php");
  exit;
}
?>

<!--memasukkan kamar dan lain2 otomatis begitu halaman dibuka-->
<?php
$tgl = date('Y-m-d');
$getDataReg = $koneksi->query("SELECT * from registrasi_rawat WHERE (status_antri != 'Pulang') AND perawatan = 'Rawat Inap'");
//   $arr = $data->fetch_assoc();
$row = $getDataReg->num_rows;
//jika lebih nol, masukkan semua yang kamarnya kosong ke biayadetail
if ($row > 0) {
  // $d=$koneksi->query("SELECT rawatinap.id, rawatinap.nama, rawatinap.noRM, kamar, tarif, tglmasuk from rawatinap left outer JOIN rawatinapsudah ON rawatinap.id=rawatinapsudah.id join kamar on kamar.namakamar=rawatinap.kamar where tglkeluar='' and rawatinapsudah.id is null ");
  // $d = $koneksi->query("SELECT * from registrasi_rawat join kamar on kamar.namakamar=registrasi_rawat.kamar where (status_antri != 'Pulang' AND status_pembayaran != 'Pembayaran')  and perawatan = 'Rawat Inap' ");
  foreach ($getDataReg as $i) {
    $id = $i['idrawat'];
    // $tgl;
    $getKamarSingle = $koneksi->query("SELECT * FROM kamar WHERE namakamar = '$i[kamar]' ORDER BY urut ASC LIMIT 1")->fetch_array();
    $tarif = $getKamarSingle['tarif'] ?? 100000;

    $cekTgl = $koneksi->query("SELECT COUNT(*) as jumlah FROM rawatinapdetail WHERE tgl = '$tgl' AND id = '$id' AND (biaya = 'sewa kamar' OR biaya = 'jasa servis' OR biaya = 'BHP' OR biaya = 'Administrasi')")->fetch_assoc();

    if ($cekTgl['jumlah'] == '0') {
      //kamar
      $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran) VALUES ('$id', '$tgl', 'sewa kamar', '$tarif') ");
      //jasa servis
      $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran) VALUES ('$id', '$tgl', 'jasa servis', '15000') ");
      //BHP
      $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran) VALUES ('$id', '$tgl', 'BHP', '10000') ");
      //administrasi
      $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran) VALUES ('$id', '$tgl', 'Administrasi', '3000') ");
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>KHM WONOREJO</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <!-- DATATABLES -->
  <!-- !-- DataTables  -->
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
  <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <link src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
  <link src="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css">
  <script type="text/javascript" language="javascript"
    src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" language="javascript"
    src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
  <script type="text/javascript" language="javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script type="text/javascript" language="javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script type="text/javascript" language="javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script type="text/javascript" language="javascript"
    src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
  <script type="text/javascript" language="javascript"
    src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
</head>

<body>
  <main>
    <div class="container">
      <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="index.php" style="color:blue;">Home</a></li>
            <li class="breadcrumb-item active"></li>
          </ol>
        </nav>
      </div><!-- End Page Title -->
      <a href="index.php?halaman=dashboard_detail" class="btn btn-sm btn-primary mb-2">Detail Dashboard</a>
      <div class="row">
        <!-- Left side columns -->
        <div class="col">
          <div class="row">
            <!-- Sales Card -->
            <div class="col-xxl-4 col-md-5">
              <div class="card info-card sales-card">
                <div class="card-body">
                  <h5 class="card-title">Pendaftaran <span>| Hari Ini</span></h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-file-text"></i>
                    </div>
                    <div class="ps-3">
                      <?php
                      $hari_ini = date('Y-m-d');
                      $jumlah_pasien_hari = $koneksi->query("SELECT COUNT(*) as jumlah FROM registrasi_rawat WHERE DATE_FORMAT(jadwal, '%Y-%m-%d') = '$hari_ini'")->fetch_assoc();
                      ?>
                      <h6><?= $jumlah_pasien_hari['jumlah'] ?></h6>
                      <!-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> -->

                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Sales Card -->

            <!-- Revenue Card -->
            <div class="col-xxl-4 col-md-7">
              <div class="card info-card revenue-card">

                <div class="card-body">
                  <h5 class="card-title">Pendapatan <span>| Bulan Ini</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div class="">
                      <?php
                      $bulan_ini = date('Y-m');
                      $pendapatan_bulan_ini = $koneksi->query("SELECT *, SUM(poli)+SUM(total_lain)+SUM(biaya_lab)-SUM(potongan) as jumlah, DATE_FORMAT(created_at, '%Y-%m-%d') as tgl FROM registrasi_rawat INNER JOIN biaya_rawat WHERE idregis=idrawat and status_antri = 'Pembayaran' and perawatan = 'Rawat Jalan' AND DATE_FORMAT(created_at, '%Y-%m') = '$bulan_ini' ")->fetch_assoc();
                      // $pendapatan_bulan_ini = $koneksi->query("SELECT SUM(poli)+SUM(total_lain)+SUM(biaya_lab)-SUM(potongan) as jumlah FROM biaya_rawat WHERE (nota != '' OR nota IS NOt NULL) AND DATE_FORMAT(created_at, '%Y-%m') = '$bulan_ini'")->fetch_assoc();
                      ?>
                      <!-- <h6>10.000.000</h6> -->
                      <h6><?= number_format($pendapatan_bulan_ini['jumlah'], 0, '', '.') ?></h6>
                      <!-- <span class="text-success small pt-1 fw-bold">8%</span> <span class="text-muted small pt-2 ps-1">increase</span> -->

                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Revenue Card -->

            <!-- Customers Card -->
            <div class="col-xxl-4 col-xl-12">

              <div class="card info-card customers-card">

                <div class="card-body">
                  <h5 class="card-title">Pasien <span>| Bulan Ini</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-people"></i>
                    </div>
                    <div class="ps-3">
                      <?php
                      $jumlah_pasien = $koneksi->query("SELECT COUNT(*) as jumlah FROM registrasi_rawat WHERE DATE_FORMAT(jadwal, '%Y-%m') = '$bulan_ini'")->fetch_assoc();
                      ?>
                      <h6><?= $jumlah_pasien['jumlah'] ?></h6>
                      <!-- <span class="text-danger small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">decrease</span> -->

                    </div>
                  </div>

                </div>
              </div>

            </div>
            <?php if($_SESSION['admin']['level'] == 'sup'){?>
              <div class="col-xxl-12 col-xl-12">
                <div class="card info-card customers-card">
                  <div class="card-body">
                    <h5 class="card-title">Cashflow <span>| <?= date('Y-m-01') ?> - <?= date('Y-m-d') ?></span></h5>
                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-currency-dollar"></i>
                      </div>
                      <div class="ps-3">
                        <?php
                        require '../dist/baseUrlAPI.php';
                        $url = $baseUrlLama . 'api_personal/api_cashflow.php?getAllCashflow&date_start=' . date('Y-m') . '-01&date_end=' . date('Y-m-d');
                        $response = file_get_contents($url);
                        $data = json_decode($response, true);
                        if ($data['status'] == "Successfully") {
                          $cashflow = $data['data'];
                        } else {
                          $cashflow = 0;
                        }
  
                        ?>
                        <h6>Rp <?= number_format($cashflow, 0, 0, '.') ?></h6>
                        <!-- <span class="text-danger small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">decrease</span> -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php }?>
            <!-- End Customers Card -->
            <?php
            $bulanSaatIni = date('y/m');
            ?>
            <?php if ($_SESSION['admin']['level'] == 'sup') { ?>
              <?php

              // $koneksi->query("
              // CREATE TABLE IF NOT EXISTS inap_jumlah  
              // SELECT DATE_FORMAT(jadwal, '%y/%m') as bulan, 
              // SUM(IF(carabayar='umum',1,0)) AS umum,
              // SUM(IF(carabayar='bpjs',1,0)) AS bpjs,
              // count(no_rm) as jumlah FROM registrasi_rawat where perawatan = 'Rawat Inap' group by bulan order by bulan desc ");
              // $koneksi->query("DROP TABLE inap_jumlah");
              // $koneksi->query("
              // CREATE TABLE IF NOT EXISTS inap_jumlah  
              // SELECT DATE_FORMAT(jadwal, '%y/%m') as bulan, 
              // SUM(IF(carabayar='umum',1,0)) AS umum,
              // SUM(IF(carabayar='bpjs',1,0)) AS bpjs,
              // count(no_rm) as jumlah FROM registrasi_rawat where perawatan = 'Rawat Inap' group by bulan order by bulan desc ");


              ?>
              <!-- pasien poli per bpjs -->
              <?php
              // $koneksi->query(" 
              //   CREATE TABLE IF NOT EXISTS poli_jumlah 
              //   SELECT DATE_FORMAT(jadwal, '%y/%m') as bulan,
              //     SUM(IF(carabayar='umum',1,0)) AS umum,
              //     SUM(IF(carabayar='bpjs',1,0)) AS bpjs,
              //     SUM(IF(carabayar='malam',1,0)) AS malam,
              //     SUM(IF(kategori='online',1,0)) AS online,
              //     SUM(IF(kategori='offline',1,0)) AS offline,
              //     COUNT(no_rm) AS jumlah
              //     FROM registrasi_rawat where status_antri = 'Datang' or status_antri = 'Pembayaran'  AND DATE_FORMAT(jadwal, '%y/%m') >= '$bulan6Lalu' AND DATE_FORMAT(jadwal, '%y/%m') <= '$bulanSaatIni' group by bulan order by bulan desc
              // ");



              //pendapatan poli
              //  $koneksiakuntansi->query("DROP TABLE pendapatanpoli");
              //  $koneksiakuntansi->query(" 
              // CREATE TABLE IF NOT EXISTS pendapatanpoli 
              // SELECT DATE_FORMAT(tgl, '%y/%m') as bulan, namaakun as akun, sum(kredit) as total, debet from transaksibaru where namaakun='pendapatan poli' group by bulan, namaakun");

              //pendapatan poli
              //  $koneksiakuntansi->query("DROP TABLE obatpoli");
              //  $koneksiakuntansi->query(" 
              // CREATE TABLE IF NOT EXISTS obatpoli 
              // SELECT DATE_FORMAT(tgl, '%y/%m') as bulan, namaakun as akun, sum(kredit) as obat, debet from transaksibaru where namaakun='Biaya Obat Poli' group by bulan, namaakun");



              ?>

            <?php } ?>
            <div class="col-12">

              </a>
              <!-- Vendor JS Files -->
              <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
              <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
              <script src="assets/vendor/chart.js/chart.umd.js"></script>
              <script src="assets/vendor/echarts/echarts.min.js"></script>
              <script src="assets/vendor/quill/quill.min.js"></script>
              <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
              <script src="assets/vendor/tinymce/tinymce.min.js"></script>
              <script src="assets/vendor/php-email-form/validate.js"></script>

              <!-- Template Main JS File -->
              <script src="assets/js/main.js"></script>

              <!-- rekap obat verif -->


              <!-- rekap pasien online offline -->


              <!-- </a> -->

              <!-- Reports -->
              <div class="col-12">
                <div class="card">

                  <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                      <li class="dropdown-header text-start">
                        <h6>Filter</h6>
                      </li>

                      <li><a class="dropdown-item" href="#">Today</a></li>
                      <li><a class="dropdown-item" href="#">This Month</a></li>
                      <li><a class="dropdown-item" href="#">This Year</a></li>
                    </ul>
                  </div>

                  <div class="card-body">
                    <h5 class="card-title">Pendapatan <span>/ 7 Hari Terakhir</span></h5>

                    <!-- Line Chart -->
                    <div id="reportsChart"></div>
                    <?php
                    // $pasien_7hari
                    // $pendaftaran_7hari
                    $pendapatan_7hari = $koneksi->query("SELECT *, SUM(poli)+SUM(total_lain)+SUM(biaya_lab)-SUM(potongan) as jumlah, DATE_FORMAT(created_at, '%Y-%m-%d') as tgl FROM registrasi_rawat INNER JOIN biaya_rawat WHERE idregis=idrawat and status_antri = 'Pembayaran' and perawatan = 'Rawat Jalan' GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d') ORDER BY DATE_FORMAT(created_at, '%Y-%m-%d') DESC LIMIT 7;");
                    ?>
                    <script>
                      document.addEventListener("DOMContentLoaded", () => {
                        new ApexCharts(document.querySelector("#reportsChart"), {
                          series: [{
                            name: 'Sales',
                            data: [
                              <?php foreach ($pendapatan_7hari as $data) { ?>
                                <?= $data['jumlah'] ?>,
                              <?php } ?>
                            ],
                          }],
                          chart: {
                            height: 350,
                            type: 'area',
                            toolbar: {
                              show: false
                            },
                          },
                          markers: {
                            size: 4
                          },
                          colors: ['#4154f1'],
                          fill: {
                            type: "gradient",
                            gradient: {
                              shadeIntensity: 1,
                              opacityFrom: 0.3,
                              opacityTo: 0.4,
                              stops: [0, 90, 100]
                            }
                          },
                          dataLabels: {
                            enabled: false
                          },
                          stroke: {
                            curve: 'smooth',
                            width: 3
                          },
                          xaxis: {
                            categories: [
                              <?php foreach ($pendapatan_7hari as $item) { ?> "<?= $item['tgl'] ?>",
                              <?php } ?>
                            ]
                          },
                          tooltip: {
                            x: {
                              format: 'y-M-d'
                            },
                          }
                        }).render();
                      });
                    </script>
                    <!-- End Line Chart -->

                  </div>

                </div>
              </div><!-- End Reports -->



              <!-- Recent Sales -->
              <!-- <div class="col-12">
              <div class="card recent-sales overflow-auto">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Recent Sales <span>| Today</span></h5>

                  <table class="table table-borderless datatable">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Customer</th>
                        <th scope="col">Product</th>
                        <th scope="col">Price</th>
                        <th scope="col">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <th scope="row"><a href="#">#2457</a></th>
                        <td>Brandon Jacob</td>
                        <td><a href="#" class="text-primary">At praesentium minu</a></td>
                        <td>$64</td>
                        <td><span class="badge bg-success">Approved</span></td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#">#2147</a></th>
                        <td>Bridie Kessler</td>
                        <td><a href="#" class="text-primary">Blanditiis dolor omnis similique</a></td>
                        <td>$47</td>
                        <td><span class="badge bg-warning">Pending</span></td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#">#2049</a></th>
                        <td>Ashleigh Langosh</td>
                        <td><a href="#" class="text-primary">At recusandae consectetur</a></td>
                        <td>$147</td>
                        <td><span class="badge bg-success">Approved</span></td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#">#2644</a></th>
                        <td>Angus Grady</td>
                        <td><a href="#" class="text-primar">Ut voluptatem id earum et</a></td>
                        <td>$67</td>
                        <td><span class="badge bg-danger">Rejected</span></td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#">#2644</a></th>
                        <td>Raheem Lehner</td>
                        <td><a href="#" class="text-primary">Sunt similique distinctio</a></td>
                        <td>$165</td>
                        <td><span class="badge bg-success">Approved</span></td>
                      </tr>
                    </tbody>
                  </table>

                </div>

              </div>
            </div>End Recent Sales -->

              <!-- Top Selling -->
              <!-- <div class="col-12">
              <div class="card top-selling overflow-auto">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body pb-0">
                  <h5 class="card-title">Top Selling <span>| Today</span></h5>

                  <table class="table table-borderless">
                    <thead>
                      <tr>
                        <th scope="col">Preview</th>
                        <th scope="col">Product</th>
                        <th scope="col">Price</th>
                        <th scope="col">Sold</th>
                        <th scope="col">Revenue</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <th scope="row"><a href="#"><img src="assets/img/product-1.jpg" alt=""></a></th>
                        <td><a href="#" class="text-primary fw-bold">Ut inventore ipsa voluptas nulla</a></td>
                        <td>$64</td>
                        <td class="fw-bold">124</td>
                        <td>$5,828</td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#"><img src="assets/img/product-2.jpg" alt=""></a></th>
                        <td><a href="#" class="text-primary fw-bold">Exercitationem similique doloremque</a></td>
                        <td>$46</td>
                        <td class="fw-bold">98</td>
                        <td>$4,508</td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#"><img src="assets/img/product-3.jpg" alt=""></a></th>
                        <td><a href="#" class="text-primary fw-bold">Doloribus nisi exercitationem</a></td>
                        <td>$59</td>
                        <td class="fw-bold">74</td>
                        <td>$4,366</td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#"><img src="assets/img/product-4.jpg" alt=""></a></th>
                        <td><a href="#" class="text-primary fw-bold">Officiis quaerat sint rerum error</a></td>
                        <td>$32</td>
                        <td class="fw-bold">63</td>
                        <td>$2,016</td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#"><img src="assets/img/product-5.jpg" alt=""></a></th>
                        <td><a href="#" class="text-primary fw-bold">Sit unde debitis delectus repellendus</a></td>
                        <td>$79</td>
                        <td class="fw-bold">41</td>
                        <td>$3,239</td>
                      </tr>
                    </tbody>
                  </table>

                </div>

              </div>
            </div>End Top Selling -->

            </div>
          </div>
          <!-- End Left side columns -->

          <!-- Right side columns -->
          <!-- <div class="col-md-4">

          Recent Activity
          <div class="card">
            <div class="filter">
              <a target="_blank" href="index.php?halaman=detaillog" class="icon" data-bs-toggle="dropdown"><i class="bi bi-eye-fill"></i></a>
            </div>

            <div class="card-body">
              <h5 class="card-title">Recent Activity <span>| Today</span></h5>
                <div class="scroll" style="overflow-x:scroll; height: 500px;">
                  <div class="activity">

              <?php
              // foreach ($ambil as $pecah): 
              ?>

                <div class="activity-item d-flex">
                  <div class="activite-label">
                    <?php
                    // echo $pecah['jam'] 
                    ?>
                  </div>
                  <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                  <div class="activity-content">
                    <?php
                    // echo $pecah['status_log'] 
                    ?>
                  </div>
                </div>
                End activity item

              <?php
              // endforeach 
              ?>

                </div>
              </div>
            </div>
          </div> -->

          <!-- Budget Report -->
          <!-- <div class="card">
            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>

            <div class="card-body pb-0">
              <h5 class="card-title">Budget Report <span>| This Month</span></h5>

              <div id="budgetChart" style="min-height: 400px;" class="echart"></div>

              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  var budgetChart = echarts.init(document.querySelector("#budgetChart")).setOption({
                    legend: {
                      data: ['Allocated Budget', 'Actual Spending']
                    },
                    radar: {
                      // shape: 'circle',
                      indicator: [{
                          name: 'Sales',
                          max: 6500
                        },
                        {
                          name: 'Administration',
                          max: 16000
                        },
                        {
                          name: 'Information Technology',
                          max: 30000
                        },
                        {
                          name: 'Customer Support',
                          max: 38000
                        },
                        {
                          name: 'Development',
                          max: 52000
                        },
                        {
                          name: 'Marketing',
                          max: 25000
                        }
                      ]
                    },
                    series: [{
                      name: 'Budget vs spending',
                      type: 'radar',
                      data: [{
                          value: [4200, 3000, 20000, 35000, 50000, 18000],
                          name: 'Allocated Budget'
                        },
                        {
                          value: [5000, 14000, 28000, 26000, 42000, 21000],
                          name: 'Actual Spending'
                        }
                      ]
                    }]
                  });
                });
              </script>

            </div>
          </div>End Budget Report -->

          <!-- Website Traffic -->
          <!-- <div class="card">
            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>

            <div class="card-body pb-0">
              <h5 class="card-title">Website Traffic <span>| Today</span></h5>

              <div id="trafficChart" style="min-height: 400px;" class="echart"></div>

              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  echarts.init(document.querySelector("#trafficChart")).setOption({
                    tooltip: {
                      trigger: 'item'
                    },
                    legend: {
                      top: '5%',
                      left: 'center'
                    },
                    series: [{
                      name: 'Access From',
                      type: 'pie',
                      radius: ['40%', '70%'],
                      avoidLabelOverlap: false,
                      label: {
                        show: false,
                        position: 'center'
                      },
                      emphasis: {
                        label: {
                          show: true,
                          fontSize: '18',
                          fontWeight: 'bold'
                        }
                      },
                      labelLine: {
                        show: false
                      },
                      data: [{
                          value: 1048,
                          name: 'Search Engine'
                        },
                        {
                          value: 735,
                          name: 'Direct'
                        },
                        {
                          value: 580,
                          name: 'Email'
                        },
                        {
                          value: 484,
                          name: 'Union Ads'
                        },
                        {
                          value: 300,
                          name: 'Video Ads'
                        }
                      ]
                    }]
                  });
                });
              </script>

            </div> -->
          <!-- </div> -->
          <!-- End Website Traffic -->

          <!-- News & Updates Traffic -->
          <!-- <div class="card">
            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>

            <div class="card-body pb-0">
              <h5 class="card-title">News &amp; Updates <span>| Today</span></h5>

              <div class="news">
                <div class="post-item clearfix">
                  <img src="assets/img/news-1.jpg" alt="">
                  <h4><a href="#">Nihil blanditiis at in nihil autem</a></h4>
                  <p>Sit recusandae non aspernatur laboriosam. Quia enim eligendi sed ut harum...</p>
                </div>

                <div class="post-item clearfix">
                  <img src="assets/img/news-2.jpg" alt="">
                  <h4><a href="#">Quidem autem et impedit</a></h4>
                  <p>Illo nemo neque maiores vitae officiis cum eum turos elan dries werona nande...</p>
                </div>

                <div class="post-item clearfix">
                  <img src="assets/img/news-3.jpg" alt="">
                  <h4><a href="#">Id quia et et ut maxime similique occaecati ut</a></h4>
                  <p>Fugiat voluptas vero eaque accusantium eos. Consequuntur sed ipsam et totam...</p>
                </div>

                <div class="post-item clearfix">
                  <img src="assets/img/news-4.jpg" alt="">
                  <h4><a href="#">Laborum corporis quo dara net para</a></h4>
                  <p>Qui enim quia optio. Eligendi aut asperiores enim repellendusvel rerum cuder...</p>
                </div>

                <div class="post-item clearfix">
                  <img src="assets/img/news-5.jpg" alt="">
                  <h4><a href="#">Et dolores corrupti quae illo quod dolor</a></h4>
                  <p>Odit ut eveniet modi reiciendis. Atque cupiditate libero beatae dignissimos eius...</p>
                </div> -->

          <!-- </div> -->
          <!-- End sidebar recent posts-->

        </div>
      </div><!-- End News & Updates -->

    </div><!-- End Right side columns -->

    </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; <strong>Solverra</strong>. All Rights Reserved
    </div>
    <div class="credits">
      <!-- All the links in the footer should remain intact. -->
      <!-- You can delete the links only if you purchased the pro version. -->
      <!-- Licensing information: https://bootstrapmade.com/license/ -->
      <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>



</body>

</html>