<?php
if (isset($_GET['submit'])) {
  $keyword = $_GET['keyword'];
  $query = "SELECT *, SUM(jml_obat) as jumlah_beli FROM apotek WHERE nama_obat LIKE '%$keyword%' OR id_obat LIKE '%$keyword%' GROUP BY id_obat order by nama_obat ASC";
  $urlPage = "index.php?halaman=daftarapotek&keyword=" . htmlspecialchars($_GET['keyword']) . "";
} else {
  $query = "SELECT *, SUM(jml_obat) as jumlah_beli FROM apotek GROUP BY id_obat order by nama_obat ASC";
  $urlPage = "index.php?halaman=daftarapotek";
}


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

// $tgl_mulai = date('2024-03-28');
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
</head>

<body>
  <main>
    <div>
      <div class="pagetitle">
        <h1>Daftar Apotek</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?halaman=daftarapotek" style="color:blue;">Apotek</a></li>
          </ol>
        </nav>
      </div><!-- End Page Title -->
      <div class="card shadow-sm p-2 mb-2">
        <div class="">
          <a href="index.php?halaman=tambah_obatmasuk" class="btn btn-sm mb-1 btn-primary"><i class="bi bi-plus"></i> Pesan Obat</a>
          <a href="index.php?halaman=apotek_obat_expired" class="btn btn-sm mb-1 btn-primary"><i class="bi bi-plus"></i> Obat Expired</a>
          <a href="index.php?halaman=apotek_terima" class="btn btn-sm mb-1 btn-primary"><i class="bi bi-plus"></i> Terima Obat</a>
          <a href="index.php?halaman=daftar_obat_selaras" class="btn btn-sm mb-1 btn-warning">Penyelarasan</a>
          <a href="index.php?halaman=harga_beli_tarakhir" class="btn btn-sm mb-1 btn-success">Harga Beli Terakhir</a>
          <a href="index.php?halaman=daftar_obat_master" class="btn btn-sm mb-1 btn-danger">Obat Master</a>
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Data Apotek</h5>
          <form method="GET">
            <div class="form-group row">
              <div class="col-md-6">
                <input type="text" name="halaman" id="" value="daftarapotek" hidden>
                <input type="text" name="submit" id="" value="" hidden>
                <input type="text" name="keyword" placeholder="Cari Obat" class="form-control" id="keyword">
              </div>
              <div class="col-md-3">
                <button type="submit" class="btn btn-primary" name="submit">Cari</button>
              </div>
            </div>
          </form>

          <form method="POST">
            <!-- Multi Columns Form -->
            <div class="table-responsive">
              <table id="myTable" class="table table-striped" style="width:100%">
                <thead>
                  <tr>
                    <th></th>
                    <th>No</th>
                    <th>Nama Obat</th>
                    <th>Kode Obat</th>
                    <!-- <th>Tipe Obat</th> -->
                    <th>Jumlah Obat</th>
                    <!-- <th>Margin Inap</th> -->
                    <!-- <th>Margin Non Inap</th> -->
                    <th>Harga Beli</th>
                    <th>Aktif Ranap</th>
                    <th>Aktif Poli</th>
                    <th></th>
                    <!-- <th>Aksi</th> -->
                  </tr>
                </thead>
                <tbody>
                  <?php $no = 1 ?>
                  <?php foreach ($pasien as $pecah) : ?>
                    <?php
                    $single = $koneksi->query("SELECT * FROM apotek WHERE id_obat = '$pecah[id_obat]' ORDER BY idapotek DESC LIMIT 1")->fetch_assoc();
                    ?>
                    <tr>
                      <td>
                        <input type="checkbox" name="selectedIds[]" value="<?= $pecah['id_obat'] ?>">
                      </td>
                      <td><?php echo $no; ?></td>
                      <td style="margin-top:10px;"><?php echo $pecah["nama_obat"]; ?></td>
                      <td style="margin-top:10px;"><?php echo $pecah["id_obat"]; ?></td>
                      <!-- <td style="margin-top:10px;"><?php echo $pecah["tipe"]; ?></td> -->
                      <td style="margin-top:10px;"><?php echo $pecah["jumlah_beli"]; ?></td>
                      <!-- <td style="margin-top:10px;"><?php echo $pecah["margininap"]; ?></td> -->
                      <!-- <td style="margin-top:10px;"><?php echo $pecah["marginnoninap"]; ?></td> -->
                      <td style="margin-top:10px;"><?php echo $single["harga_beli"]; ?></td>
                      <td>
                        <?php if ($pecah["aktif_ranap"] == "aktif") { ?>
                          <a href="index.php?halaman=daftarapotek&aktifranap=nonaktif&kode_obat=<?= $pecah["id_obat"] ?>" class="btn btn-sm btn-success"><i class="bi bi-check-circle"></i></a>
                        <?php } else { ?>
                          <a href="index.php?halaman=daftarapotek&aktifranap=aktif&kode_obat=<?= $pecah["id_obat"] ?>" class="btn btn-sm btn-danger"><i class="bi bi-x-circle"></i></a>
                        <?php } ?>
                      </td>
                      <td>
                        <?php if ($pecah["aktif_poli"] == "aktif") { ?>
                          <a href="index.php?halaman=daftarapotek&aktifpoli=nonaktif&kode_obat=<?= $pecah["id_obat"] ?>" class="btn btn-sm btn-success"><i class="bi bi-check-circle"></i></a>
                        <?php } else { ?>
                          <a href="index.php?halaman=daftarapotek&aktifpoli=aktif&kode_obat=<?= $pecah["id_obat"] ?>" class="btn btn-sm btn-danger"><i class="bi bi-x-circle"></i></a>
                        <?php } ?>
                      </td>
                      <td>
                        <a href="index.php?halaman=detailapotek&id=<?php echo $pecah["id_obat"]; ?>" class="btn btn-sm btn-primary" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-eye"></i></a>
                        <!-- <div class="dropdown"> -->
                        <!-- <i data-bs-toggle="dropdown" style="color: blue; font-weight: bold; font-size: 20px;" class="bi bi-three-dots-vertical"></i> -->
                        <!-- <ul class="dropdown-menu">
                            <li></li> -->
                        <!-- <li><a href="index.php?halaman=ubahapotek&id=<?php echo $pecah["idapotek"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-pencil" style="color:blueviolet;"></i> Ubah</a></li> -->
                        <!-- <li><a href="index.php?halaman=hapusapotek&id=<?php echo $pecah["idapotek"]; ?>" class="dropdown-item" style="text-decoration: none; font-weight: bold; margin-left: 2px;" onclick="return confirm('Anda yakin mau menghapus item ini ?')">
                              <i class="bi bi-trash" style="color:red;"></i> Hapus</a></li> -->
                        <!-- </ul> -->
                        <!-- </div> -->
                      </td>
                    </tr>
                    <?php $no += 1 ?>
                  <?php endforeach ?>

                </tbody>
              </table>
            </div>

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
            <div class="row mb-3 mt-3">
              <div class="col-4">
                <select name="selectedItem" class="form-control" id="">
                  <option value="">Pilih</option>
                  <option value="aktifranap">Aktif Ranap</option>
                  <option value="nonaktifranap">nonAktif Ranap</option>
                  <option value="aktifpoli">Aktif Poli</option>
                  <option value="nonaktifpoli">nonAktif Poli</option>
                </select>
              </div>
              <div class="col-6">
                <button type="submit" name="status" class="btn btn-success">Ubah Status</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


</body>

</html>

<?php
if (isset($_GET['aktifranap'])) {
  if ($_GET['aktifranap'] == "aktif") {
    $koneksi->query("UPDATE apotek SET aktif_ranap = 'aktif' WHERE id_obat = '$_GET[kode_obat]'");
    echo "<script>alert('Data berhasil diaktifkan');</script>";
  } else {
    $koneksi->query("UPDATE apotek SET aktif_ranap = 'nonaktif' WHERE id_obat = '$_GET[kode_obat]'");
    echo "<script>alert('Data berhasil dinonaktifkan');</script>";
  }
  echo "<script>location='index.php?halaman=daftarapotek';</script>";
}

if (isset($_GET['aktifpoli'])) {
  if ($_GET['aktifpoli'] == "aktif") {
    $koneksi->query("UPDATE apotek SET aktif_poli = 'aktif' WHERE id_obat = '$_GET[kode_obat]'");
    echo "<script>alert('Data berhasil diaktifkan');</script>";
  } else {
    $koneksi->query("UPDATE apotek SET aktif_poli = 'nonaktif' WHERE id_obat = '$_GET[kode_obat]'");
    echo "<script>alert('Data berhasil dinonaktifkan');</script>";
  }
  echo "<script>location='index.php?halaman=daftarapotek';</script>";
}

if (isset($_POST['status'])) {
  $selectedIds = [];
  if (isset($_POST['selectedIds'])) {
    $selectedIds = $_POST['selectedIds'];
  }
  $selectedItem = $_POST['selectedItem'];

  if ($selectedItem == 'aktifranap') {
    foreach ($selectedIds as $id) {
      $koneksi->query("UPDATE apotek SET aktif_ranap = 'aktif' WHERE id_obat = '$id'");
    }
  } else if ($selectedItem == 'nonaktifranap') {
    foreach ($selectedIds as $id) {
      $koneksi->query("UPDATE apotek SET aktif_ranap = 'nonaktif' WHERE id_obat = '$id'");
    }
  } else if ($selectedItem == 'aktifpoli') {
    foreach ($selectedIds as $id) {
      $koneksi->query("UPDATE apotek SET aktif_poli = 'aktif' WHERE id_obat = '$id'");
    }
  } else if ($selectedItem == 'nonaktifpoli') {
    foreach ($selectedIds as $id) {
      $koneksi->query("UPDATE apotek SET aktif_poli = 'nonaktif' WHERE id_obat = '$id'");
    }
  }
  echo "<script>location='index.php?halaman=daftarapotek';</script>";
}

?>

<link rel="stylesheet" href="https://cdn.datatables.net/2.1.2/css/dataTables.dataTables.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.0/css/buttons.dataTables.css" />
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/2.1.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.0/js/dataTables.buttons.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.0/js/buttons.html5.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.0/js/buttons.print.js"></script>

<script>
  $(document).ready(function() {
    $('#myTable').DataTable({
      dom: 'Bfrtip',
      buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
      paging: false,
      order: false,
      searching: false,
    });
  });
</script>
<style>
  .dt-button {
    float: right !important;
    border: none;
    padding: 8px 16px !important;
    border-radius: 4px !important;
    cursor: pointer !important;
    margin-left: 10px !important;
  }
</style>