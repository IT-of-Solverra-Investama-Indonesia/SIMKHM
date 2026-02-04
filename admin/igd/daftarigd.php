<?php
$whereClause = "";
$date_start = "2000-01-01";
$date_end = date('Y-m-d');
$key = "";
$urlPage = "index.php?halaman=daftarigd";
if(isset($_GET['src'])){
  $urlPage = "index.php?halaman=daftarigd&src&date_start=" . $_GET['date_start'] . "&date_end=" . $_GET['date_end'] . "&key=" . $_GET['key'];
  if($_GET['date_start'] != "" && $_GET['date_end'] != ""){
    $date_start = $_GET['date_start'];
    $date_end = $_GET['date_end'];
    $whereClause .= " AND tgl_masuk BETWEEN '$date_start' AND '$date_end' ";
  }
  if($_GET['key'] != ""){
    $key = $_GET['key'];
    $whereClause .= " AND (no_rm LIKE '%$key%' OR nama_pasien LIKE '%$key%' OR nama_pengantar LIKE '%$key%') ";
  }
}

$query = "SELECT * FROM igd WHERE 1=1 $whereClause ORDER BY idigd DESC ";

//   Pagination
// Parameters for pagination
$limit = 30; // Number of entries to show in a page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Get the total number of records
$result = $koneksi->query($query);
$total_records = $result->num_rows;

// Calculate total pages
$total_pages = ceil($total_records / $limit);

$cekPage = '';
if (isset($_GET['page'])) {
    $cekPage = $_GET['page'];
} else {
    $cekPage = '1';
}
// End Pagination
$pasien = $koneksi->query($query . " LIMIT $start, $limit;");
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
        <h1>Daftar IGD</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?halaman=daftarigd" style="color:blue;">IGD</a></li>
          </ol>
        </nav>
      </div><!-- End Page Title -->

      <section class="section  py-4">
        <div class="">


          <div class="row">
            <div class="col-lg-12 col-md-12">
              <div class="card shadow p-2 mb-1">
                <form method="get">
                  <input type="text" name="halaman" value="daftarigd" hidden id="">
                  <div class="row g-1">
                    <div class="col-6">
                      <label for="" class="mb-0">Dari Tanggal:</label>
                      <input type="date" name="date_start" value="<?= $date_start ?>" class="form-control form-control-sm mb-2" required>
                    </div>
                    <div class="col-6">
                      <label for="" class="mb-0">Sampai Tanggal:</label>
                      <input type="date" name="date_end" value="<?= $date_end ?>" class="form-control form-control-sm mb-2" required>
                    </div>
                    <div class="col-10">
                      <input type="text" name="key" class="form-control form-control-sm" placeholder="Cari..." value="<?= $key?>">
                    </div>
                    <div class="col-2">
                      <button name="src" class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>
                    </div>
                  </div>
                </form>
              </div>
              <div class="card shadow p-2">
                <div class="card-body">

                  <h5 class="card-title">Data IGD</h5>

                  <!-- Multi Columns Form -->
                  <div class="table-responsive">
                    <table  class="table table-striped" style="width:100%; font-size: 12px;">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>No RM</th>
                          <th>Nama Pasien</th>
                          <th>Nama Pengantar</th>
                          <th>Tanggal Masuk</th>
                          <th>Jam Masuk</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $no = 1 ?>
                        <?php foreach ($pasien as $pecah) : ?>
                          <tr>
                            <td><?php echo $no; ?></td>
                            <td style="margin-top:10px;"><?php echo $pecah["no_rm"]; ?></td>
                            <td style="margin-top:10px;"><?php echo $pecah["nama_pasien"]; ?></td>
                            <td style="margin-top:10px;"><?php echo $pecah["nama_pengantar"]; ?></td>
                            <td style="margin-top:10px;"><?php echo $pecah["tgl_masuk"]; ?></td>
                            <td style="margin-top:10px;"><?php echo $pecah["jam_masuk"]; ?></td>
                            <td>
                              <?php if($_SESSION['admin']['level'] == 'sup' OR $_SESSION['admin']['level'] == 'dokter' OR $_SESSION['admin']['level'] == 'igd'){?>
                                <div class="dropdown">
                                  <i data-bs-toggle="dropdown" style="color: blue; font-weight: bold; font-size: 20px;" class="bi bi-three-dots-vertical"></i>
                                  <ul class="dropdown-menu">
                                    <li><a href="index.php?halaman=detailigd&id=<?php echo $pecah["idigd"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-eye-fill" style="color:darkblue;"></i> Detail</a></li>
                                    <li><a href="index.php?halaman=ubahigd&id=<?php echo $pecah["idigd"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-hospital" style="color:black;"></i> Kajian IGD</a></li>
                                    <li><a href="index.php?halaman=lpo&igd&id=<?php echo $pecah["no_rm"] ?>&idigd=<?php echo $pecah["idigd"] ?>&tgl=<?php echo $pecah["tgl_masuk"] ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-file-earmark-spreadsheet" style="color:orange;"></i> Observasi Perawat</a></li>
                                    <li><a href="../pasien/gen_con.php?id=<?php echo $pecah["no_rm"]; ?>&igd" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-printer" style="color:black;"></i> General Consent</a></li>
                                    <li><a href="index.php?halaman=falanak&id=<?php echo $pecah["idigd"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-file" style="color:blueviolet;"></i> Fallrisk Pediatri (Anak)</a></li>
                                    <li><a href="index.php?halaman=faldewasa&id=<?php echo $pecah["idigd"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-file" style="color:blueviolet;"></i> Fallrisk (Dewasa)</a></li>
                                    <li><a href="index.php?halaman=ivl&id=<?php echo $pecah["no_rm"] ?>&igd" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-bandaid-fill" style="color:brown;"></i> IVL</a></li>
                                    <li><a href="index.php?halaman=hapusigd&id=<?php echo $pecah["idigd"]; ?>" class="dropdown-item" style="text-decoration: none; font-weight: bold; margin-left: 2px;" onclick="return confirm('Anda yakin mau menghapus item ini ?')">
                                        <i class="bi bi-trash" style="color:red;"></i> Hapus</a></li>
                                  </ul>
                                </div>
                              <?php }?>
                            </td>
                          </tr>
                          <?php $no += 1 ?>
                        <?php endforeach ?>
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
                      echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . ($page - 1) . '">Back</a></li>';
                  }
      
                  // Determine the start and end page
                  $start_page = max(1, $page - 2);
                  $end_page = min($total_pages, $page + 2);
      
                  if ($start_page > 1) {
                      echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=1">1</a></li>';
                      if ($start_page > 2) {
                          echo '<li class="page-item"><span class="page-link">...</span></li>';
                      }
                  }
      
                  for ($i = $start_page; $i <= $end_page; $i++) {
                      if ($i == $page) {
                          echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
                      } else {
                          echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . $i . '">' . $i . '</a></li>';
                      }
                  }
      
                  if ($end_page < $total_pages) {
                      if ($end_page < $total_pages - 1) {
                          echo '<li class="page-item"><span class="page-link">...</span></li>';
                      }
                      echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . $total_pages . '">' . $total_pages . '</a></li>';
                  }
      
                  // Next button
                  if ($page < $total_pages) {
                      echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . ($page + 1) . '">Next</a></li>';
                  }
      
                  echo '</ul>';
                  echo '</nav>';
                  ?>
                  <br>
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
      order: [0, 'desc'],
      pagination: true
    });
  });
</script>