<?php
// $getMarginInap0 = $koneksi->query("SELECT * FROM apotek WHERE margininap = '0' ORDER BY nama_obat ASC");
// foreach ($getMarginInap0 as $marginInap0) {
//   $koneksi->query("UPDATE apotek SET margininap = '100' WHERE idapotek = '$marginInap0[idapotek]'");
// }

// $getMarginJual0 = $koneksi->query("SELECT * FROM apotek WHERE margin_jual = '' ORDER BY nama_obat ASC");
// foreach ($getMarginJual0 as $marginJual0) {
//   $koneksi->query("UPDATE apotek SET margin_jual = '100' WHERE idapotek = '$marginJual0[idapotek]'");
// }

// $totalRows = $getMarginInap0->num_rows + $getMarginJual0->num_rows;
// if ($totalRows > 0) {
//   echo "
//         <script>
//             document.location.href='index.php?halaman=margin_obat';
//         </script>
//     ";
// }

$limit = 30;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

if (isset($_GET['cari'])) {
  $cari = $_GET['cari'];
  $query_total = $koneksimaster->query("SELECT COUNT(*) AS total FROM master_obat WHERE obat_master LIKE '%" . $cari . "%' OR kode_obat LIKE '%" . $cari . "%'");
  $total_data = $query_total->fetch_assoc()['total'];

  $pasien = $koneksimaster->query("SELECT * FROM master_obat WHERE obat_master LIKE '%" . $cari . "%' OR kode_obat LIKE '%" . $cari . "%' ORDER BY obat_master ASC LIMIT $offset, $limit");
} else {
  $query_total = $koneksimaster->query("SELECT COUNT(*) AS total FROM master_obat");
  $total_data = $query_total->fetch_assoc()['total'];

  $pasien = $koneksimaster->query("SELECT * FROM master_obat ORDER BY obat_master ASC LIMIT $offset, $limit");
}

$total_pages = ceil($total_data / $limit);

error_reporting(0);
?>
<div>
  <div class="pagetitle">
    <h1>Daftar Obat</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php?halaman=daftarapotek" style="color:blue;">Apotek</a></li>
      </ol>
    </nav>
  </div>
  <div class="card">
    <div class="card-body">
      <h5 class="card-title mb-0">Data Obat</h5>
      <form method="GET" action="">
        <div class="input-group mb-3">
          <input type="hidden" name="halaman" value="margin_obat">
          <input
            type="text"
            id="cari_input"
            name="cari"
            class="form-control"
            placeholder="Cari Nama Obat / Kode Obat"
            value="<?php echo htmlspecialchars($_GET['cari'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
            aria-label="Cari Obat"
            aria-describedby="button-search">
          <button class="btn btn-primary" type="submit" id="button-search">
            <i class="bi bi-search"></i>
          </button>
        </div>
      </form>
      <div class="table-responsive">
        <table id="myTable" class="table table-striped table-hover table-sm" style="width:100%; font-size: 12px;">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama Obat</th>
              <th>Kode Obat</th>
              <th>Margin Inap</th>
              <th>Margin Jual</th>
              <th>Margin Resep</th>
              <th>Harga Beli Terakhir</th>
              <th>Inap</th>
              <th>Jual</th>
              <th>Resep</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = $offset + 1; 
            ?>
            <?php foreach ($pasien as $pecah) : ?>
              <tr>
                <td><?php echo $no; ?></td>
                <td><?php echo $pecah["obat_master"]; ?></td>
                <td><?php echo $pecah["kode_obat"]; ?></td>
                <td>
                  <span class="badge bg-warning" style="font-size: 12px;">
                    <?= $pecah['margin_inap'] ?>%
                  </span>
                </td>
                <td>
                  <span class="badge bg-warning" style="font-size: 12px;">
                    <?= $pecah['margin_jual'] ?>%
                  </span>
                </td>
                <td>
                  <span class="badge bg-warning" style="font-size: 12px;">
                    <?= $pecah['margin_resep'] ?>%
                  </span>
                </td>
                <td>
                  <?php
                  $getBeliAkhir = $koneksi->query("SELECT * FROM apotek WHERE id_obat = '$pecah[kode_obat]' ORDER BY idapotek DESC LIMIT 1")->fetch_assoc();

                  $marginInap = (intval($pecah['margin_inap']) / 100) * intval($getBeliAkhir['harga_beli']);
                  $marginJual = (intval($pecah['margin_jual']) / 100) * intval($getBeliAkhir['harga_beli']);
                  $marginResep = (intval($pecah['margin_resep']) / 100) * intval($getBeliAkhir['harga_beli']);
                  ?>
                  Rp<?= number_format($getBeliAkhir['harga_beli'] ?? 0, 0, 0, '.') ?>/Item
                </td>
                <td>
                  Rp<?= number_format($marginInap, 0, 0, '.') ?>
                </td>
                <td>
                  Rp<?= number_format($marginJual, 0, 0, '.') ?>
                </td>
                <td>
                  Rp<?= number_format($marginResep, 0, 0, '.') ?>
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
              <a class="page-link" href="?halaman=margin_obat<?= $cari_param ?>&page=<?= $page - 1 ?>">Prev</a>
            </li>

            <?php
            $start_page = max(1, $page - 2);
            $end_page = min($total_pages, $page + 2);

            if ($start_page > 1) {
              echo '<li class="page-item"><a class="page-link" href="?halaman=margin_obat' . $cari_param . '&page=1">1</a></li>';
              if ($start_page > 2) {
                echo '<li class="page-item disabled"><a class="page-link">...</a></li>';
              }
            }

            for ($i = $start_page; $i <= $end_page; $i++) {
              $active = ($i == $page) ? 'active' : '';
              echo '<li class="page-item ' . $active . '"><a class="page-link" href="?halaman=margin_obat' . $cari_param . '&page=' . $i . '">' . $i . '</a></li>';
            }

            if ($end_page < $total_pages) {
              if ($end_page < $total_pages - 1) {
                echo '<li class="page-item disabled"><a class="page-link">...</a></li>';
              }
              echo '<li class="page-item"><a class="page-link" href="?halaman=margin_obat' . $cari_param . '&page=' . $total_pages . '">' . $total_pages . '</a></li>';
            }
            ?>

            <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
              <a class="page-link" href="?halaman=margin_obat<?= $cari_param ?>&page=<?= $page + 1 ?>">Next</a>
            </li>
          </ul>
        </nav>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
  function upMargin(kode_obat, margin, tipe) {
    var margin_var = document.getElementById('margin');
    var kode_var = document.getElementById('kode');
    var jenis_var = document.getElementById('jenis');

    margin_var.value = margin;
    kode_var.value = kode_obat;
    jenis_var.value = tipe;
  }
</script>

<div class="modal fade" id="margininap_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Ubah Margin Inap</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="post">
        <div class="modal-body">
          <input type="text" name="id_obat" hidden id="kode" class="form-control mb-2">
          <input type="text" name="jenis" hidden id="jenis" class="form-control mb-2">
          <input type="text" name="margin" id="margin" class="form-control mb-2">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" name="save" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
if (isset($_POST['save'])) {
  if ($_POST['jenis'] == 'inap') {
    $koneksi->query("UPDATE apotek SET margininap = '" . htmlspecialchars($_POST['margin']) . "' WHERE id_obat = '" . htmlspecialchars($_POST['id_obat']) . "'");
  } elseif ($_POST['jenis'] == 'jual') {
    $koneksi->query("UPDATE apotek SET margin_jual = '" . htmlspecialchars($_POST['margin']) . "' WHERE id_obat = '" . htmlspecialchars($_POST['id_obat']) . "'");
  } elseif ($_POST['jenis'] == 'resep') {
    $koneksi->query("UPDATE apotek SET margin_resep = '" . htmlspecialchars($_POST['margin']) . "' WHERE id_obat = '" . htmlspecialchars($_POST['id_obat']) . "'");
  }

  echo "
            <script>
                alert('successfully');
                document.location.href='index.php?halaman=margin_obat';
            </script>
        ";
}
?>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

<!-- ALTER TABLE `apotek` ADD `margin_jual` VARCHAR(20) NOT NULL AFTER `margininap`; -->

<!-- Pada Table Unit -->
<!-- ALTER TABLE `apotek` ADD `margin_resep` VARCHAR(10) NOT NULL DEFAULT '100' AFTER `margin_jual`; -->