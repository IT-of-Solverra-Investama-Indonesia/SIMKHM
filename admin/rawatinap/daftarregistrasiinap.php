<?php

$date = date("Y-m-d");

$queryKey = '';


if (!isset($_GET['day'])) {
  if (isset($_POST['src'])) {
    $queryKey = " AND (registrasi_rawat.nama_pasien LIKE '%$_POST[key]%' OR perawatan LIKE '%$_POST[key]%' 
    OR dokter_rawat LIKE '%$_POST[key]%' OR no_rm LIKE '%$_POST[key]%' OR status_antri LIKE '%$_POST[key]%'
    OR DATE_FORMAT(jadwal, '%d-%m-%Y') LIKE '%$_POST[key]%')";

    $pasiens_search = "SELECT * FROM registrasi_rawat WHERE perawatan = 'Rawat Inap' " . $queryKey . " ORDER BY idrawat DESC";
  }
  $pasiens = "SELECT * FROM registrasi_rawat WHERE perawatan = 'Rawat Inap' ORDER BY idrawat DESC";
} else {
  if (isset($_POST['src'])) {
    $queryKey = " AND (registrasi_rawat.nama_pasien LIKE '%$_POST[key]%' OR perawatan LIKE '%$_POST[key]%' 
    OR dokter_rawat LIKE '%$_POST[key]%' OR no_rm LIKE '%$_POST[key]%'OR status_antri LIKE '%$_POST[key]%'
    OR DATE_FORMAT(jadwal, '%d-%m-%Y') LIKE '%$_POST[key]%')";

    $pasiens_search = "SELECT * FROM registrasi_rawat WHERE perawatan = 'Rawat Inap' WHERE date%d') = '$date' " . $queryKey . " ORDER BY idrawat DESC";
  }

  $pasiens = "SELECT * FROM registrasi_rawat WHERE perawatan = 'Rawat Inap' WHERE date%d') = '$date' ORDER BY idrawat DESC";
}
// var_dump($pasien);

$limit = 100; // Number of entries to show in a page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;
$resultpasien = $koneksi->query($pasiens);
$total_records = $resultpasien->num_rows;

$total_pages = ceil($total_records / $limit);
// var_dump($total_pages);


$cekPage = '';
if (isset($_GET['page'])) {
  $cekPage = $_GET['page'];
} else {
  $cekPage = '1';
}

if (isset($_POST['src'])) {
  $pasien = $koneksi->query($pasiens_search);
} else {
  $pasien = $koneksi->query($pasiens . " LIMIT $start, $limit;");
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

  <script>
    $(document).ready(function() {
      $('#myTable').DataTable({
        search: true,
        pagination: true,
        order: [5, 'desc'],
        pageLength: 100
      });
    });
  </script>
</head>

<body>
  <main>
    <div class="container">
      <div class="pagetitle">
        <h1>Daftar Registrasi</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?halaman=daftarpasien" style="color:blue;">Daftar Registrasi</a></li>
          </ol>
        </nav>
      </div><!-- End Page Title -->

      <section class="section  py-4">
        <div class="container">
          <div class="row">
            <div class="col-lg-12 col-md-12">
              <div class="card">
                <div class="card-body">
                  <!--   <div style="margin-top: 50px; margin-bottom: 30px; text-align: right;">
               
                <a href="index.php?halaman=pasien" class="btn btn-primary"><i class="bi bi-plus"></i> Pasien</a>

                </div> -->
                  <h5 class="card-title">Data Registrasi</h5>
                  <form method="POST">
                    <div class="row">
                      <div class="col-9">
                        <input type="text" class="form-control mb-2" placeholder="Search ..." name="key">
                      </div>
                      <div class="col-3">
                        <button class="btn btn-primary" name="src"><i class="bi bi-search"></i></button>
                      </div>
                    </div>
                  </form>

                  <!-- Multi Columns Form -->
                  <div class="table-responsive">
                    <table id="" class="table table-striped" style="width:100%">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Nama Pasien</th>
                          <th>Jenis Perawatan</th>
                          <th>Dokter</th>
                          <th>No RM</th>
                          <th>Jadwal</th>
                          <!-- <th>Antrian</th> -->
                          <th>Status</th>
                          <th></th>
                          <!-- <th>Aksi</th> -->

                        </tr>
                      </thead>
                      <tbody>

                        <?php $no = 1 ?>

                        <?php foreach ($pasien as $pecah) : ?>

                          <tr>
                            <td><?php echo $no; ?></td>
                            <td style="margin-top:10px;"><?php echo $pecah["nama_pasien"]; ?></td>
                            <td style="margin-top:10px;"><?php echo $pecah["perawatan"]; ?></td>
                            <td style="margin-top:10px;"><?php echo $pecah["dokter_rawat"]; ?></td>
                            <td style="margin-top:10px;"><?php echo $pecah["no_rm"]; ?></td>
                            <?php


                            $jadwal = strtotime($pecah['jadwal']) - (3600 * 7);
                            $date = $jadwal;
                            // date_add($date, date_interval_create_from_date_string('-2 hours'));
                            // echo date_format($date, 'Y-m-d\TH:i:s');
                            ?>
                            <td style="margin-top:10px;"> <?= $pecah['jadwal'] ?></td>
                            <!-- <td style="margin-top:10px;"><?php echo $pecah["antrian"]; ?></td> -->
                            <td style="margin-top:10px;">
                              <?php if ($pecah["status_antri"] == 'Datang') { ?>
                                <h6 style="color:green"><?php echo $pecah["status_antri"]; ?></h6>
                              <?php } else { ?>
                                <h6 style="color:red"><?php echo $pecah["status_antri"]; ?></h6>
                              <?php }  ?>
                            </td>
                            <td>
                              <div class="dropdown">
                                <?php
                                $ubah = $koneksi->query("SELECT * FROM kajian_awal_inap WHERE nama_pasien = '$pecah[nama_pasien]';")->fetch_assoc();
                                ?>
                                <i data-bs-toggle="dropdown" style="color: blue; font-weight: bold; font-size: 20px;" class="bi bi-three-dots-vertical"></i>
                                <ul class="dropdown-menu">
                                  <?php if (empty($ubah['nama_pasien'])) { ?>
                                    <li><a href="index.php?halaman=resumeinap&id=<?php echo $pecah["idrawat"]; ?>&norm=<?php echo $pecah["no_rm"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-card-list" style="color:blueviolet;"></i> Isi Kajian Awal</a></li>
                                  <?php } else { ?>
                                    <li><a href="index.php?halaman=resumeinap&id=<?php echo $pecah["idrawat"]; ?>&norm=<?php echo $pecah["no_rm"]; ?>&ubah" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-card-list" style="color:blueviolet;"></i> Lihat Kajian Awal</a></li>
                                  <?php } ?>
                                  <li><a href="index.php?halaman=daftarregistrasiinap&id=<?php echo $pecah["idrawat"]; ?>&jadwal=<?= date("Y-m-d\TH:i:s+00:00", $jadwal); ?>&antrian=<?php echo $pecah["antrian"]; ?>&dokter=<?php echo $pecah["dokter_rawat"]; ?>&norm=<?php echo $pecah["no_rm"]; ?>&status=datang" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-check-circle" style="color:green;"></i> Datang</a></li>

                                  <?php $dataPasien = $koneksi->query("SELECT * FROM pasien WHERE nama_lengkap = '$pecah[nama_pasien]'")->fetch_assoc(); ?>
                                  <li><a href="index.php?halaman=falanakinap&id=<?php echo $pecah["idrawat"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-printer" style="color:black;"></i> Fall Risk (Anak)</a></li>
                                  <li><a href="index.php?halaman=faldewasainap&id=<?php echo $pecah["idrawat"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-printer" style="color:black;"></i> Fall Risk (Dewasa)</a></li>
                                  <li><a href="../pasien/fal-risk.php?id=<?php echo $dataPasien["idpasien"]; ?>&kunjungan=<?= $pecah['idrawat'] ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-printer" style="color:black;"></i> Fall Risk</a></li>

                                  <li><a href="index.php?halaman=hapuspasien&id=<?php echo $pecah["idrawat"]; ?>&regis" class="dropdown-item" style="text-decoration: none; font-weight: bold; margin-left: 2px;" onclick="return confirm('Anda yakin mau menghapus item ini ?')">
                                      <i class="bi bi-trash" style="color:red;"></i> Hapus</a></li>
                                </ul>
                              </div>
                            </td>
                          </tr>


                          <?php $no += 1 ?>
                        <?php endforeach; ?>

                      </tbody>
                    </table>

                  </div>
                  <br>
                  <?php
                  // Display pagination
                  echo '<nav>';
                  echo '<ul class="pagination justify-content-center">';

                  // Back button
                  if ($page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="index.php?halaman=daftarregistrasiinap&page=' . ($page - 1) . '">Back</a></li>';
                  }

                  // Determine the start and end page
                  $start_page = max(1, $page - 2);
                  $end_page = min($total_pages, $page + 2);

                  if ($start_page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="index.php?halaman=daftarregistrasiinap&page=1">1</a></li>';
                    if ($start_page > 2) {
                      echo '<li class="page-item"><span class="page-link">...</span></li>';
                    }
                  }

                  for ($i = $start_page; $i <= $end_page; $i++) {
                    if ($i == $page) {
                      echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
                    } else {
                      echo '<li class="page-item"><a class="page-link" href="index.php?halaman=daftarregistrasiinap&page=' . $i . '">' . $i . '</a></li>';
                    }
                  }

                  if ($end_page < $total_pages) {
                    if ($end_page < $total_pages - 1) {
                      echo '<li class="page-item"><span class="page-link">...</span></li>';
                    }
                    echo '<li class="page-item"><a class="page-link" href="index.php?halaman=daftarregistrasiinap&page=' . $total_pages . '">' . $total_pages . '</a></li>';
                  }

                  // Next button
                  if ($page < $total_pages) {
                    echo '<li class="page-item"><a class="page-link" href="index.php?halaman=daftarregistrasiinap&page=' . ($page + 1) . '">Next</a></li>';
                  }

                  echo '</ul>';
                  echo '</nav>';
                  ?>
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

<?php if (isset($_GET['status'])) {

  // $getToken = curl_init();
  // curl_setopt_array($getToken, array(
  //   CURLOPT_URL => 'https://api-satusehat.kemkes.go.id/oauth2/v1/accesstoken?grant_type=client_credentials',
  //   CURLOPT_RETURNTRANSFER => true,
  //   CURLOPT_ENCODING => '',
  //   CURLOPT_MAXREDIRS => 10,
  //   CURLOPT_TIMEOUT => 0,
  //   CURLOPT_FOLLOWLOCATION => true,
  //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  //   CURLOPT_CUSTOMREQUEST => 'POST',
  //   CURLOPT_POSTFIELDS => 'client_id=pnZFT0j4Hs1FKIqQKeRspG1ncJwauPVKNnrT2OeiuPpP2E3l&client_secret=FNTJCctvzsWjmjb7VHGbdzLT1xLG9FcV8bAWql27GKJ8o9S5iXxHvOQYpi85qzzv',
  //   CURLOPT_HTTPHEADER => array(
  //     'Content-Type: application/x-www-form-urlencoded',
  //     'Authorization: Bearer WVqDq4p8tYLyaNtYtCDytoaJLNJj'
  //   ),
  // ));

  // $responseToken = curl_exec($getToken);

  // curl_close($getToken);
  // // echo $responseToken;
  // $pecahToken = json_decode($responseToken, true);
  // $token = $pecahToken['access_token'];

  // $getIHS = $koneksi->query("SELECT * FROM pasien WHERE no_rm = '$_GET[norm]' LIMIT 1")->fetch_assoc();
  // $IHS = $getIHS['ihs_id'];
  // $getIHSDokter = $koneksi->query("SELECT * FROM admin WHERE namalengkap = '$_GET[dokter]' LIMIT 1")->fetch_assoc();
  // $IHSdokter = $getIHSDokter['ihs_id'];

  // $curl = curl_init();
  // curl_setopt_array($curl, array(
  //   CURLOPT_URL => 'https://api-satusehat.kemkes.go.id/fhir-r4/v1/Encounter',
  //   CURLOPT_RETURNTRANSFER => true,
  //   CURLOPT_ENCODING => '',
  //   CURLOPT_MAXREDIRS => 10,
  //   CURLOPT_TIMEOUT => 0,
  //   CURLOPT_FOLLOWLOCATION => true,
  //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  //   CURLOPT_CUSTOMREQUEST => 'POST',
  //   CURLOPT_POSTFIELDS =>'{
  //     "resourceType": "Encounter",
  //     "status": "arrived",
  //     "class": {
  //         "system": "http://terminology.hl7.org/CodeSystem/v3-ActCode",
  //         "code": "AMB",
  //         "display": "ambulatory"
  //     },
  //     "subject": {
  //         "reference": "Patient/'.$IHS.'",
  //         "display": "PATIENT '.$_GET['antrian'].'"
  //     },
  //     "participant": [
  //         {
  //             "type": [
  //                 {
  //                     "coding": [
  //                         {
  //                             "system": "http://terminology.hl7.org/CodeSystem/v3-ParticipationType",
  //                             "code": "ATND",
  //                             "display": "attender"
  //                         }
  //                     ]
  //                 }
  //             ],
  //             "individual": {
  //                 "reference": "Practitioner/'.$IHSdokter.'",
  //                 "display": "'.$_GET['dokter'].'"
  //             }
  //         }
  //     ],
  //     "period": {
  //         "start": "'.$_GET['jadwal'].'"
  //     },
  //     "statusHistory": [
  //         {
  //             "status": "arrived",
  //             "period": {
  //                 "start": "'.$_GET['jadwal'].'"
  //             }
  //         }
  //     ],
  //     "serviceProvider": {
  //         "reference": "Organization/100015704"
  //     }
  // }',
  //   CURLOPT_HTTPHEADER => array(
  //     'Content-Type: application/json',
  //     'Authorization: Bearer '.$token
  //   ),
  // ));

  // $response = curl_exec($curl);

  // curl_close($curl);
  // echo $response;
  $koneksi->query("UPDATE registrasi_rawat SET status_antri='Datang' WHERE idrawat='$_GET[id]'");

  // $koneksi->query("INSERT INTO biaya_rawat

  //   (poli, idregis)

  //   VALUES ('35000', '$_GET[id]')

  //   ");

  echo "
  <script>

  alert('Berhasil!');

  document.location.href='index.php?halaman=daftarregistrasiinap';

  </script>

  ";
  if (mysqli_affected_rows($koneksi) > 0) {
  }
} ?>
<!-- <script>
    $(document).ready(function() {
        $('#myTables').DataTable( {
         search: true,
         pagination: true
        } );
    } );
</script> -->