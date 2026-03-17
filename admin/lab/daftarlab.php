<?php
$user = $_SESSION['admin']['username'];
$level = $_SESSION['admin']['level'];
// $pasien = $koneksi->query("SELECT * FROM lab JOIN registrasi_rawat WHERE id_lab=idrawat GROUP BY id_lab ORDER BY tgl DESC;");

$limit = 30;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

if (isset($_GET['cari'])) {
  $cari = $_GET['cari'];
  $query_total = $koneksi->query("SELECT COUNT(DISTINCT lab.id_lab) AS total FROM lab JOIN registrasi_rawat WHERE id_lab = idrawat AND (normlab LIKE '%" . $cari . "%' OR pasienlab LIKE '%" . $cari . "%')");
  $total_data = $query_total->fetch_assoc()['total'];

  $pasien = $koneksi->query("SELECT * FROM lab JOIN registrasi_rawat WHERE id_lab = idrawat AND (normlab LIKE '%" . $cari . "%' OR pasienlab LIKE '%" . $cari . "%') GROUP BY id_lab ORDER BY tgl DESC LIMIT $offset, $limit");
} else {
  $query_total = $koneksi->query("SELECT COUNT(DISTINCT lab.id_lab) AS total FROM lab JOIN registrasi_rawat ON lab.id_lab = registrasi_rawat.idrawat");
  $total_data = $query_total->fetch_assoc()['total'];

  $pasien = $koneksi->query("SELECT * FROM lab JOIN registrasi_rawat WHERE id_lab = idrawat GROUP BY id_lab ORDER BY tgl DESC LIMIT $offset, $limit");
}

$total_pages = ceil($total_data / $limit);
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

  <!-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css"> -->
  <!-- <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <link src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css"> -->
  <!-- <link src="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css">
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script> -->
  <!-- <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script> -->
  <!-- <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script> -->


</head>


<body>
  <main>

    <div class="container">
      <div class="pagetitle">
        <h1>Daftar Rujukan Lab</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?halaman=daftarpasien" style="color:blue;">Rujukan Lab</a></li>
          </ol>
        </nav>
      </div><!-- End Page Title -->

      <section class="section  py-4">
        <div class="container">
          <div class="row">
            <div class="col-lg-12 col-md-12">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Data Rujukan</h5>
                  <form method="GET" action="">
                    <div class="input-group mb-3">
                      <input type="hidden" name="halaman" value="daftarlab">
                      <input
                        type="text"
                        id="cari_input"
                        name="cari"
                        class="form-control"
                        placeholder="Cari Nama / No RM"
                        value="<?php echo htmlspecialchars($_GET['cari'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                        aria-label="Cari Obat"
                        aria-describedby="button-search">
                      <button class="btn btn-primary" type="submit" id="button-search">
                        <i class="bi bi-search"></i>
                      </button>
                    </div>
                  </form>
                  <!-- Multi Columns Form -->
                  <div class="table-responsive">
                    <table id="myTable" class="table table-striped" style="width:100%">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Tgl Permintaan</th>
                          <th>No RM</th>
                          <th>Nama Pasien</th>
                          <th>Dokter Pengirim</th>
                          <th>No Permintaan</th>
                          <!-- <th>Nama Pemeriksaan</th> -->
                          <th></th>
                          <!-- <th>Aksi</th> -->

                        </tr>
                      </thead>
                      <tbody>

                        <?php $no = 1 ?>

                        <?php foreach ($pasien as $pecah) : ?>

                          <tr>
                            <td><?php echo $no; ?></td>
                            <td style="margin-top:10px;"><?php echo $pecah["tgl"]; ?></td>
                            <td style="margin-top:10px;"><?php echo $pecah["normlab"]; ?></td>
                            <td style="margin-top:10px;"><?php echo $pecah["pasienlab"]; ?></td>
                            <td style="margin-top:10px;"><?php echo $pecah["dokter_lab"]; ?></td>
                            <td style="margin-top:10px;"><?php echo $pecah["register_lab"]; ?></td>
                            <!-- <td style="margin-top:10px;"><?php echo $pecah["tipe_lab"]; ?></td> -->
                            <td>
                              <div class="dropdown">
                                <i data-bs-toggle="dropdown" style="color: blue; font-weight: bold; font-size: 20px;" class="bi bi-three-dots-vertical"></i>
                                <ul class="dropdown-menu">
                                  <li><a href="index.php?halaman=detaillab2&id=<?php echo $pecah["id_lab"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-eye-fill" style="color:blue;"></i> Detail</a></li>
                                  <li><a href="index.php?halaman=isilab&id=<?php echo $pecah["id_lab"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-card-list" style="color:blueviolet;"></i> Isi Data Lab</a></li>
                                  <li><a href="index.php?halaman=ubahhasil&id=<?php echo $pecah["id_lab"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-pencil" style="color:hotpink;"></i> Ubah</a></li>
                                  <li><a href="index.php?halaman=hapuslab&id=<?php echo $pecah["idlab"]; ?>" class="dropdown-item" style="text-decoration: none; font-weight: bold; margin-left: 2px;" onclick="return confirm('Anda yakin mau menghapus item ini ?')">
                                      <i class="bi bi-trash" style="color:red;"></i> Hapus</a></li>
                                </ul>
                              </div>
                            </td>
                          </tr>

                          <?php $no += 1 ?>
                        <?php endforeach ?>

                      </tbody>
                    </table>
                  </div>
                  <?php if ($total_pages > 1): ?>
                    <nav aria-label="Page navigation" class="mt-3">
                      <ul class="pagination justify-content-center">
                        <?php
                        $cari_param = isset($_GET['cari']) ? "&cari=" . urlencode($_GET['cari']) : "";
                        ?>

                        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                          <a class="page-link" href="?halaman=daftarlab<?= $cari_param ?>&page=<?= $page - 1 ?>">Prev</a>
                        </li>

                        <?php
                        $start_page = max(1, $page - 2);
                        $end_page = min($total_pages, $page + 2);

                        if ($start_page > 1) {
                          echo '<li class="page-item"><a class="page-link" href="?halaman=daftarlab' . $cari_param . '&page=1">1</a></li>';
                          if ($start_page > 2) {
                            echo '<li class="page-item disabled"><a class="page-link">...</a></li>';
                          }
                        }

                        for ($i = $start_page; $i <= $end_page; $i++) {
                          $active = ($i == $page) ? 'active' : '';
                          echo '<li class="page-item ' . $active . '"><a class="page-link" href="?halaman=daftarlab' . $cari_param . '&page=' . $i . '">' . $i . '</a></li>';
                        }

                        if ($end_page < $total_pages) {
                          if ($end_page < $total_pages - 1) {
                            echo '<li class="page-item disabled"><a class="page-link">...</a></li>';
                          }
                          echo '<li class="page-item"><a class="page-link" href="?halaman=daftarlab' . $cari_param . '&page=' . $total_pages . '">' . $total_pages . '</a></li>';
                        }
                        ?>

                        <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                          <a class="page-link" href="?halaman=daftarlab<?= $cari_param ?>&page=<?= $page + 1 ?>">Next</a>
                        </li>
                      </ul>
                    </nav>
                  <?php endif; ?>
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

<!-- <script>
  $(document).ready(function() {
    $('#myTable').DataTable({
      search: true,
      pagination: true
    });
  });
</script> -->