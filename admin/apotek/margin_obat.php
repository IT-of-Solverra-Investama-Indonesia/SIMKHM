<?php
$getMarginInap0 = $koneksi->query("SELECT * FROM apotek WHERE margininap = '0' ORDER BY nama_obat ASC");
foreach ($getMarginInap0 as $marginInap0) {
  $koneksi->query("UPDATE apotek SET margininap = '100' WHERE idapotek = '$marginInap0[idapotek]'");
}

$getMarginJual0 = $koneksi->query("SELECT * FROM apotek WHERE margin_jual = '' ORDER BY nama_obat ASC");
foreach ($getMarginJual0 as $marginJual0) {
  $koneksi->query("UPDATE apotek SET margin_jual = '100' WHERE idapotek = '$marginJual0[idapotek]'");
}

$totalRows = $getMarginInap0->num_rows + $getMarginJual0->num_rows;
if ($totalRows > 0) {
  echo "
        <script>
            document.location.href='index.php?halaman=margin_obat';
        </script>
    ";
}

$pasien = $koneksi->query("SELECT *, SUM(jml_obat) as jumlah_beli FROM apotek GROUP BY nama_obat, id_obat order by idapotek desc;");
?>
<div>
  <div class="pagetitle">
    <h1>Daftar Obat</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php?halaman=daftarapotek" style="color:blue;">Apotek</a></li>
      </ol>
    </nav>
  </div><!-- End Page Title -->
  <div class="card">
    <div class="card-body">
      <h5 class="card-title mb-0">Data Obat</h5>
      <!-- Multi Columns Form -->
      <div class="table-responsive">
        <table id="myTable" class="table table-striped" style="width:100%; font-size: 12px;">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama Obat</th>
              <th>Kode Obat</th>
              <th>Margin Inap</th>
              <th>Margin Jual</th>
              <th>Margin Resep</th>
              <th>Harga Beli Terakhir</th>
              <th>Harga Inap | Jual</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1 ?>
            <?php foreach ($pasien as $pecah) : ?>
              <tr>
                <td><?php echo $no; ?></td>
                <td style="margin-top:10px;"><?php echo $pecah["nama_obat"]; ?></td>
                <td style="margin-top:10px;"><?php echo $pecah["id_obat"]; ?></td>
                <td style="margin-top:10px;">
                  <?php if ($_SESSION['admin']['level'] == 'sup') { ?>
                    <button onclick="upMargin('<?= $pecah['id_obat'] ?>','<?= $pecah['margininap'] ?>','inap')" class="btn btn-sm btn-warning" type="button" data-bs-toggle="modal" data-bs-target="#margininap_modal">
                      <?= $pecah['margininap'] ?> %
                    </button>
                  <?php } else { ?>
                    <button class="btn btn-sm btn-warning" type="button">
                      <?= $pecah['margininap'] ?> %
                    </button>
                  <?php } ?>
                </td>
                <td style="margin-top:10px;">
                  <?php if ($_SESSION['admin']['level'] == 'sup') { ?>
                    <button onclick="upMargin('<?= $pecah['id_obat'] ?>','<?= $pecah['margin_jual'] == '' ? 0 : $pecah['margin_jual'] ?>','jual')" class="btn btn-sm" style="background-color: orange;" type="button" data-bs-toggle="modal" data-bs-target="#margininap_modal">
                      <?= $pecah['margin_jual'] == '' ? 0 : $pecah['margin_jual'] ?> %
                    </button>
                  <?php } else { ?>
                    <button class="btn btn-sm" style="background-color: orange;" type="button">
                      <?= $pecah['margin_jual'] == '' ? 0 : $pecah['margin_jual'] ?> %
                    </button>
                  <?php } ?>
                </td>
                <td style="margin-top:10px;">
                  <?php if ($_SESSION['admin']['level'] == 'sup') { ?>
                    <button onclick="upMargin('<?= $pecah['id_obat'] ?>','<?= $pecah['margin_resep'] == '' ? 0 : $pecah['margin_resep'] ?>','resep')" class="btn btn-sm" style="background-color: orange;" type="button" data-bs-toggle="modal" data-bs-target="#margininap_modal">
                      <?= $pecah['margin_resep'] == '' ? 0 : $pecah['margin_resep'] ?> %
                    </button>
                  <?php } else { ?>
                    <button class="btn btn-sm" style="background-color: orange;" type="button">
                      <?= $pecah['margin_resep'] == '' ? 0 : $pecah['margin_resep'] ?> %
                    </button>
                  <?php } ?>
                </td>
                <td>
                  <?php
                  $getBeliAkhir = $koneksi->query("SELECT * FROM apotek WHERE id_obat = '$pecah[id_obat]' ORDER BY idapotek DESC LIMIT 1")->fetch_assoc();

                  $marginInap = (intval($pecah['margininap']) / 100) * intval($getBeliAkhir['harga_beli']);
                  $marginJual = (intval($pecah['margin_jual']) / 100) * intval($getBeliAkhir['harga_beli']);
                  ?>
                  Rp<?= number_format($getBeliAkhir['harga_beli'], 0, 0, '.') ?>/Item
                </td>
                <td>
                  Rp<?= number_format($marginInap, 0, 0, '.') ?> |
                  Rp<?= number_format($marginJual, 0, 0, '.') ?>
                </td>
              </tr>
              <?php $no += 1 ?>
            <?php endforeach ?>
          </tbody>
        </table>
      </div>
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

<!-- Modal MarginInap -->
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
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script>
  $(document).ready(function() {
    $('#myTable').DataTable({
      search: true,
      pagination: true
    });
  });
</script>

<!-- ALTER TABLE `apotek` ADD `margin_jual` VARCHAR(20) NOT NULL AFTER `margininap`; -->

<!-- Pada Table Unit -->
<!-- ALTER TABLE `apotek` ADD `margin_resep` VARCHAR(10) NOT NULL DEFAULT '100' AFTER `margin_jual`; -->