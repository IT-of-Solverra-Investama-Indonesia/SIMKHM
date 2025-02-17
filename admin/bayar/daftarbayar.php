<?php
date_default_timezone_set('Asia/Jakarta');
?>
<?php if (!isset($_GET['pasienInap'])) { ?>
  <?php
  if (isset($_GET['detail']) and isset($_GET['tgl'])) {
    $date = date('Y-m-d', strtotime($_GET['tgl']));
    $shift = $_GET["shift"];
    // var_dump($shift); 
    // $pasien=$koneksi->query("SELECT * FROM registrasi_rawat INNER JOIN biaya_rawat WHERE idregis=idrawat and (status_antri = 'Datang' or status_antri = 'Pembayaran') and registrasi_rawat.shift = '$shift' and perawatan = 'Rawat Jalan' and (date_format(jadwal, '%Y-%m-%d') = '$date' or date_format(jadwal, '%Y-%m-%d') = DATE(NOW() - INTERVAL 1 DAY));"); 
    $pasien = $koneksi->query("SELECT *, registrasi_rawat.kasir, registrasi_rawat.shift FROM registrasi_rawat WHERE perawatan = 'Rawat Jalan' AND registrasi_rawat.shift = '$shift' and (status_antri = 'Datang' or status_antri = 'Pembayaran') and date_format(jadwal, '%Y-%m-%d') = '$date' GROUP BY registrasi_rawat.idrawat;");
  } else {
    $shift = $_SESSION["shift"];
    if (date('Hi') > '0730') {
      $jamCondition = "AND jadwal >= '" . date('Y-m-d 07:30:00') . "' AND jadwal <= '" . date('Y-m-d 07:30:00', strtotime('+1 day')) . "'";
    } else {
      $jamCondition = "AND jadwal <= '" . date('Y-m-d 07:30:00') . "' AND jadwal >= '" . date('Y-m-d 07:30:00', strtotime('-1 day')) . "'";
    }
    // var_dump($shift);
    // $pasien=$koneksi->query("SELECT * FROM registrasi_rawat LEFT JOIN biaya_rawat WHERE idregis=idrawat and (status_antri = 'Datang' or status_antri = 'Pembayaran') and registrasi_rawat.shift = '$shift' and perawatan = 'Rawat Jalan' and (date_format(jadwal, '%Y-%m-%d') = '$date' or date_format(jadwal, '%Y-%m-%d') = DATE(NOW() - INTERVAL 1 DAY));"); 
    $pasien = $koneksi->query("SELECT *, registrasi_rawat.kasir, registrasi_rawat.shift FROM registrasi_rawat WHERE perawatan = 'Rawat Jalan' and (status_antri = 'Datang' or status_antri = 'Pembayaran') " . $jamCondition . " GROUP BY registrasi_rawat.idrawat;");
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
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>


  </head>

  <body>
    <main>
      <div class="">
        <div class="pagetitle">
          <h1>Pembayaran</h1>
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="index.php?halaman=daftarbayar" style="color:blue;">
                  <?php if (date('Hi') > '0730') { ?>
                    Daftar Pembayaran Dari <?= date('d-m-Y 07:30:00') ?> Hingga <?= date('d-m-Y 07:30:00', strtotime('+1 day')) ?>
                  <?php } else { ?>
                    Daftar Pembayaran Dari <?= date('d-m-Y 07:30:00', strtotime('-1 day')) ?> Hingga <?= date('d-m-Y 07:30:00') ?>
                  <?php } ?>
                </a>
              </li>
            </ol>
          </nav>
        </div><!-- End Page Title -->
        <section class="">
          <div class="">
            <div class="row">
              <div class="col-lg-12 col-md-12">

                <div class="card">
                  <div class="card-body">

                    <h5 class="card-title mb-0">Data Pembayaran Pasien</h5>
                    <span style="font-size: 12px; margin-top: 0px;" class="mt-0">Data berwarna merah adalah data yang belum di isi dokter</span>
                    <br>
                    <a href="index.php?halaman=daftarbayar&pasienInap" class="btn btn-sm btn-primary mb-2">Pasien Inap</a>
                    <form method="post">
                      <div class="row">
                        <div class="col-6">
                          Cari Berdasar Tanggal
                          <input type="date" class="form-control mb-2" name="tgl">
                        </div>
                        <div class="col-3">
                          Shift
                          <select name="shift" id="" required class="form-control">
                            <option value="" hidden>Pilih Shift</option>
                            <option value="Pagi">Pagi</option>
                            <option value="Sore">Sore</option>
                            <option value="Malam">Malam</option>
                          </select>
                        </div>
                        <div class="col-3">
                          <br>
                          <button class="btn btn-primary" name="search"><i class="bi bi-search"></i></button>
                        </div>
                      </div>
                    </form>
                    <?php
                    if (isset($_POST['search'])) {
                      echo "
                    <script>
                      document.location.href='index.php?halaman=daftarbayar&detail&tgl=" . ($_POST['tgl'] == '' ? date('Y-m-d') : $_POST['tgl']) . "&shift=$_POST[shift]';
                    </script>
                  ";
                    }
                    ?>
                    <!-- Multi Columns Form -->
                    <div class="table-responsive">
                      <table id="myTable" class="table table-striped table-hover" style="width:100%; font-size: 12px;">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>Nama Pasien</th>
                            <th>Jenis Perawatan</th>
                            <th>Dokter</th>
                            <th>No RM</th>
                            <th>Jadwal</th>
                            <th>Antrian</th>
                            <th>Cara Bayar</th>
                            <th>Shift</th>
                            <th>Nota</th>
                            <!-- <th>Status Bayar</th> -->
                            <th>Biaya Poli</th>
                            <th>Periksa lab</th>
                            <th>Total lab</th>
                            <th>Biaya Lain</th>
                            <th>Total Biaya Lain</th>
                            <th>Potongan</th>
                            <th>Total</th>
                            <th></th>
                            <!-- <th>Aksi</th> -->

                          </tr>
                        </thead>
                        <tbody>
                          <?php $subtotal = 0; ?>
                          <?php $subpoli = 0; ?>
                          <?php $sublain = 0; ?>
                          <?php $subigd = 0; ?>
                          <?php $sublab = 0; ?>

                          <?php $no = 1 ?>

                          <?php foreach ($pasien as $pecah) : ?>
                            <?php
                            $biayaRawat = $koneksi->query("SELECT * FROM biaya_rawat WHERE idregis= '$pecah[idrawat]' ORDER BY id DESC LIMIT 1")->fetch_assoc();
                            if (isset($biayaRawat["poli"])) {
                              $poli = $biayaRawat["poli"];
                            } else {
                              $poli = 0;
                            };
                            if (isset($biayaRawat["periksa_lab"])) {
                              $periksa_lab = $biayaRawat["periksa_lab"];
                            } else {
                              $periksa_lab = 0;
                            };
                            if (isset($biayaRawat["biaya_lab"])) {
                              $biaya_lab = $biayaRawat["biaya_lab"];
                            } else {
                              $biaya_lab = 0;
                            };
                            if ($biayaRawat["biaya_lain"] != "") {
                              $biaya_lain = $biayaRawat["biaya_lain"];
                            } else {
                              $biaya_lain = "";
                            };
                            if (isset($biayaRawat["total_lain"])) {
                              $total_lain = $biayaRawat["total_lain"];
                            } else {
                              $total_lain = 0;
                            };
                            if (isset($biayaRawat["potongan"])) {
                              $potongan = $biayaRawat["potongan"];
                            } else {
                              $potongan = 0;
                            };
                            ?>
                            <tr class="<?= $pecah['status_antri'] == 'Datang' ?  "bg-danger text-light" :  "" ?>">
                              <td><?php echo $no; ?></td>
                              <!-- <td><?php echo $pecah['idrawat']; ?></td> -->
                              <td style="margin-top:10px;"><?php echo $pecah["nama_pasien"]; ?></td>
                              <td style="margin-top:10px;"><?php echo $pecah["perawatan"]; ?><br><span style="font-size: 10px; font-weight: bold;"><?= $pecah['status_antri'] == 'Datang' ?  "Belum Entri Dokter" :  "" ?></span></td>
                              <td style="margin-top:10px;"><?php echo $pecah["dokter_rawat"]; ?></td>
                              <td style="margin-top:10px;"><?php echo $pecah["no_rm"]; ?></td>
                              <?php

                              $jadwal = strtotime($pecah['jadwal']) - (3600 * 7);
                              $date = $jadwal;
                              // date_add($date, date_interval_create_from_date_string('-2 hours'));
                              // echo date_format($date, 'Y-m-d\TH:i:s');
                              ?>
                              <td style="margin-top:10px;"> <?= $pecah['jadwal'] ?></td>
                              <td style="margin-top:10px;"><?php echo $pecah["antrian"]; ?></td>
                              <td style="margin-top:10px;"><?php echo $pecah["carabayar"]; ?></td>
                              <!-- <td style="margin-top:10px;">
                <?php if ($pecah["status_antri"] == 'Datang') { ?>
                <h6 style="color:green"><?php echo $pecah["status_antri"]; ?></h6>
                <?php } else { ?>
                  <h6 style="color:red"><?php echo $pecah["status_antri"]; ?></h6>
                <?php }  ?>
                </td> -->
                              <!-- <td><?php echo $pecah["status"]; ?></td> -->
                              <td><?php echo $shift; ?></td>
                              <td><?= $biayaRawat['nota'] ?></td>
                              <td><?php echo intval($poli); ?></td>
                              <td>
                                <?php echo intval($periksa_lab); ?>
                              </td>
                              <td>
                                <b>
                                  <?php echo intval($biaya_lab); ?>
                                </b>
                                <span style="font-size: 10px;">
                                  <?php
                                  $getBiayaLab = $koneksi->query("SELECT * FROM lab WHERE id_lab = '$pecah[idrawat]'");

                                  foreach ($getBiayaLab as $labBiaya) {
                                  ?>
                                    <?= $labBiaya['tipe_lab'] ?>,
                                  <?php } ?>
                                </span>
                              </td>
                              <td><?php echo $biaya_lain; ?></td>
                              <td><?php echo intval($total_lain); ?></td>
                              <td><?php echo intval($potongan); ?></td>
                              </td>
                              <td>
                                <?php
                                $t = intval($poli) + intval($biaya_lab) + intval($total_lain) - intval($potongan);
                                ?>
                                <?php echo $t; ?>
                              </td>
                              <td>
                                <div class="dropdown">
                                  <?php
                                  $ubah = $koneksi->query("SELECT * FROM kajian_awal WHERE nama_pasien = '$pecah[nama_pasien]';")->fetch_assoc();
                                  ?>
                                  <i data-bs-toggle="dropdown" style="color: blue; font-weight: bold; font-size: 20px;" class="bi bi-three-dots-vertical"></i>
                                  <ul class="dropdown-menu">
                                    <li>
                                      <a href="index.php?halaman=bayarrawat&rm=<?php echo $pecah["no_rm"]; ?>&id=<?php echo $pecah["idrawat"]; ?>&tgl=<?php echo $pecah["jadwal"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-currency-dollar" style="color:blue;"></i> Bayar</a>
                                    </li>
                                    <li><a href="index.php?halaman=rujuklab2&rm=<?php echo $pecah["no_rm"]; ?>&id=<?php echo $pecah["idrawat"]; ?>&tgl=<?php echo $pecah["jadwal"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-clipboard2-pulse" style="color:hotpink;"></i> Rujuk Lab</a></li>

                                    <li><a href="../bayar/rekappoli.php?rm=<?php echo $pecah["no_rm"]; ?>&id=<?php echo $pecah["idrawat"]; ?>&tgl=<?php echo $pecah["jadwal"]; ?>&dr=<?php echo $pecah['dokter_rawat'] ?>&shift=<?php echo $pecah['shift'] ?>&kasir=<?= $pecah['kasir'] ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-file" style="color:hotpink;" target="_blank"></i> Rekap Shift</a></li>

                                    <li><a href="../bayar/rekappolikasir.php?rm=<?php echo $pecah["no_rm"]; ?>&id=<?php echo $pecah["idrawat"]; ?>&tgl=<?php echo $pecah["jadwal"]; ?>&dr=<?php echo $pecah['dokter_rawat'] ?>&shift=<?php echo $pecah['shift'] ?>&kasir=<?= $pecah["kasir"] ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-file" style="color:hotpink;" target="_blank"></i> Rekap Kasir</a></li>
                                  </ul>
                                </div>
                              </td>
                              <td>

                              </td>
                            </tr>

                            <?php $no += 1 ?>
                            <?php $subtotal += $t; ?>
                          <?php endforeach; ?>

                          <tr>

                            <td></td>

                            <td colspan="11">

                              TOTAL

                            </td>
                            <td></td>
                            <td></td>
                            <td></td>

                            <td>

                              <h5> Rp.<?php echo  number_format($subtotal) ?></h5>

                            </td>
                            <td></td>

                          </tr>


                        </tbody>
                      </table>

                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
      </div>

      </section>

      </div>
    </main><!-- End #main -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


  </body>

  </html>

  <script>
    $(document).ready(function() {
      $('#myTable').DataTable({
        search: true,
        pagination: true
      });
    });
  </script>
<?php } else { ?>
  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
  <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
  <script>
    $(document).ready(function() {
      $('#myTable').DataTable({
        order: 'none'
      });
    });
  </script>
  <div>
    <div class="card shadow p-2">
      <h5>Pembayaran Pasien Inap</h5>
      <a href="index.php?halaman=daftarbayar" style="max-width: 100px;" class="btn btn-sm btn-dark">Kembali</a>
      <div class="table-responsive">
        <table class="table table-hover table-striped" style="font-size: 13px;" id="myTable">
          <thead>
            <tr>
              <th>Nama Pasien</th>
              <th>Jenis Perawatan</th>
              <th>Dokter</th>
              <th>NoRm</th>
              <th>Jadwal</th>
              <!--<th>Antrian</th>-->
              <th>Kamar</th>
              <th>Status Datang</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $getData = $koneksi->query("SELECT * FROM registrasi_rawat WHERE perawatan = 'Rawat Inap' AND status_antri != 'Pulang' ORDER BY idrawat DESC ");
            foreach ($getData as $data) {
            ?>
              <tr>
                <td><?= $data['nama_pasien'] ?></td>
                <td><?= $data['perawatan'] ?></td>
                <td><?= $data['dokter_rawat'] ?></td>
                <td><?= $data['no_rm'] ?></td>
                <td><?= $data['jadwal'] ?></td>
                <!--<td><?= $data['antrian'] ?></td>-->
                <td><?= $data['kamar'] ?></td>
                <td><?= $data['status_antri'] ?></td>
                <td>
                  <a href="index.php?halaman=rekapinap&id=<?= $data['idrawat'] ?>" style="font-size: 12px;" class="btn btn-danger"><i class="bi bi-cash-coin"></i></a>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
<?php } ?>