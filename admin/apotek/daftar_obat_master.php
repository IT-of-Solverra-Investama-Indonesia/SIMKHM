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
    });
  });
</script>
<style>
  .dt-button {
    float:right !important;
    border: none;
    padding: 8px 16px !important;
    border-radius: 4px !important;
    cursor: pointer !important;
    margin-left: 10px !important;
  }
</style>
<div class="pagetitle mb-0">
  <h1>Daftar Obat Master</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item mb-0"><a href="index.php?halaman=daftarapotek" style="color:blue;">Apotek</a></li>
    </ol>
  </nav>
</div>
<div class="card shadow-sm p-2">
  <form method="post">
    <a href="index.php?halaman=daftarapotek" class="btn btn-sm btn-dark mt-0  me-2" style="max-width: 100px;">Kembali</a>
    <button onclick="return confirm('Data obat master akan ditambahkan semua ke data obat lokal, apakah anda yakin ?')" type="submit" name="masukanSemua" class="btn btn-sm btn-warning mt-0 " style="max-width: 170px;">Masukan Local Semua</button>
    <button type="button" data-bs-target="#add" data-bs-toggle="modal" class="btn btn-sm btn-primary" style="max-width: 100px;">[+] Add</button>
  </form>
  <?php
  if (isset($_POST['masukanSemua'])) {
    $getAll = $koneksimaster->query("SELECT * FROM master_obat");
    foreach ($getAll as $all) {
      $cekAda = $koneksi->query("SELECT * FROM apotek WHERE nama_obat = '$all[obat_master]'");
      if ($cekAda->num_rows <= 0) {
        $koneksi->query("INSERT INTO apotek (nama_obat, tipe, id_obat, bentuk, jml_obat, jml_obat_minim, harga_beli) VALUES ('$all[obat_maste]','Rajal','$all[kode_obat]','-','0','0')");
      }
    }
    echo "
                <script>
                    alert('Successfully');
                    document.location.href='index.php?halaman=daftar_obat_master';
                </script>
            ";
  }
  ?>
  <!-- Modal -->
  <div class="modal fade" id="add" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Obat</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST">
          <div class="modal-body">
            <input required class="form-control mb-2" type="text" name="obat_master" placeholder="Nama Obat">
            <input required class="form-control mb-2" type="number" name="kode_obat" placeholder="Kode Obat">
            <input class="form-control mb-2" type="text" name="pbf_master1" placeholder="PBF Obat">
            <input class="form-control mb-2" type="text" name="pbf_master2" placeholder="PBF Obat">
            <input class="form-control mb-2" type="text" name="pbf_master3" placeholder="PBF Obat">
            <input class="form-control mb-2" type="text" name="pbf_master4" placeholder="PBF Obat">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button name="simpan" class="btn btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php
  if (isset($_POST['simpan'])) {
    $koneksimaster->query("INSERT INTO master_obat (obat_master, kode_obat, pbf_master3, pbf_master4, pbf_master1, pbf_master2) VALUES ('" . htmlspecialchars($_POST['obat_master']) . "', '" . htmlspecialchars($_POST['kode_obat']) . "', '" . htmlspecialchars($_POST['pbf_master3']) . "', '" . htmlspecialchars($_POST['pbf_master4']) . "', '" . htmlspecialchars($_POST['pbf_master1']) . "', '" . htmlspecialchars($_POST['pbf_master2']) . "')");

    echo "
                <script>
                    alert('Successfully');
                    document.location.href='index.php?halaman=daftar_obat_master';
                </script>
              ";
  }
  ?>
  <div class="table-responsive">
    <table id="myTable" class="table table-hover table-striped mt-2" style="width:100%; font-size: 12px">
      <thead>
        <tr>
          <th>Nama</th>
          <th>Kode</th>
          <th>Margin Jual</th>
          <th>Margin Inap</th>
          <th>PBF1</th>
          <th>PBF2</th>
          <th>PBF3</th>
          <th>PBF4</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $getData = $koneksimaster->query("SELECT * FROM master_obat ORDER BY obat_master ASC");
        foreach ($getData as $data) {
        ?>
          <tr>
            <td><?= $data['obat_master'] ?></td>
            <td><?= $data['kode_obat'] ?></td>
            <td>
              <button class="btn btn-sm btn-warning" data-bs-target="#setMargin" onclick="upMargin('<?= $data['id'] ?>', '<?= $data['margin_jual'] == '' ? '0' : $data['margin_jual'] ?>', 'jual')" data-bs-toggle="modal">
                <?= $data['margin_jual'] == '' ? '0' : $data['margin_jual'] ?> %
              </button>
            </td>
            <td>
              <button class="btn btn-sm btn-warning" data-bs-target="#setMargin" onclick="upMargin('<?= $data['id'] ?>', '<?= $data['margin_inap'] == '' ? '0' : $data['margin_inap'] ?>', 'inap')" data-bs-toggle="modal">
                <?= $data['margin_inap'] == '' ? '0' : $data['margin_inap'] ?> %
              </button>
            </td>
            <td><?= $data['pbf_master1'] ?></td>
            <td><?= $data['pbf_master2'] ?></td>
            <td><?= $data['pbf_master3'] ?></td>
            <td><?= $data['pbf_master4'] ?></td>
            <td>
              <button onclick="upData('<?= $data['id'] ?>','<?= $data['obat_master'] ?>','<?= $data['kode_obat'] ?>','<?= $data['pbf_master1'] ?>','<?= $data['pbf_master2'] ?>','<?= $data['pbf_master3'] ?>','<?= $data['pbf_master4'] ?>')" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Edit</button>
              <a href="index.php?halaman=daftar_obat_master&del=<?= $data['id'] ?>" onclick="return confirm('Apakah anda yakin ingin menghapus data ini ?')" class="btn btn-sm btn-danger">Hapus</a>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
  <script>
    function upMargin(id, margin, jualinap) {
      var id2_var = document.getElementById('id2_id');
      var margin_var = document.getElementById('margin_id');
      var jualinap_var = document.getElementById('jualinap_id');

      margin_var.value = margin;
      jualinap_var.value = jualinap;
      id2_var.value = id;
    }
  </script>
  <!-- Modal -->
  <div class="modal fade" id="setMargin" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Setting Margin</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST">
          <div class="modal-body">
            <div class="input-group mb-3">
              <input type="number" class="form-control" name="margin" id="margin_id" placeholder="Setting Margin" aria-label="Setting Margin" aria-describedby="basic-addon2">
              <span class="input-group-text" id="basic-addon2">%</span>
            </div>
            <input required class="form-control mb-2" hidden type="text" name="id2" id="id2_id" placeholder="Kode Obat">
            <input required class="form-control mb-2" hidden type="text" name="jualinap" id="jualinap_id" placeholder="Kode Obat">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button name="setting" class="btn btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php
  if (isset($_POST['setting'])) {
    $kolomName = "margin_" . htmlspecialchars($_POST['jualinap']);
    $koneksimaster->query("UPDATE master_obat SET " . $kolomName . " = '" . htmlspecialchars($_POST['margin']) . "' WHERE id = '" . htmlspecialchars($_POST['id2']) . "'");
    echo "
      <script>
          alert('Successfully');
          document.location.href='index.php?halaman=daftar_obat_master';
      </script>
    ";
  }
  ?>
  <script>
    function upData(id, nama, kode, pbf, pbf2, pbf3, pbf4) {
      var id_var = document.getElementById('id_id');
      var obat_master_var = document.getElementById('obat_master_id');
      var pdf_master_var = document.getElementById('pbf_master1_id');
      var pdf2_master_var = document.getElementById('pbf_master2_id');
      var pdf3_master_var = document.getElementById('pbf_master3_id');
      var pdf4_master_var = document.getElementById('pbf_master4_id');
      var kode_obat_var = document.getElementById('kode_obat_id');

      id_var.value = id;
      obat_master_var.value = nama;
      pdf_master_var.value = pbf;
      pdf2_master_var.value = pbf2;
      pdf3_master_var.value = pbf3;
      pdf4_master_var.value = pbf4;
      kode_obat_var.value = kode;
    }
  </script>
  <!-- Modal -->
  <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Obat</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST">
          <div class="modal-body">
            <input required class="form-control mb-2" type="text" name="obat_master" id="obat_master_id" placeholder="Nama Obat">
            <input required class="form-control mb-2" hidden type="text" name="id" id="id_id">
            <input required class="form-control mb-2" type="text" name="kode_obat" id="kode_obat_id" placeholder="Kode Obat">
            <input class="form-control mb-2" type="text" name="pbf_master1" id="pbf_master1_id" placeholder="PBF Obat">
            <input class="form-control mb-2" type="text" name="pbf_master2" id="pbf_master2_id" placeholder="PBF Obat">
            <input class="form-control mb-2" type="text" name="pbf_master3" id="pbf_master3_id" placeholder="PBF Obat">
            <input class="form-control mb-2" type="text" name="pbf_master4" id="pbf_master4_id" placeholder="PBF Obat">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" name="save" class="btn btn-success">Save</button>
          </div>
        </form>
        <?php
        if (isset($_POST['save'])) {
          $koneksimaster->query("UPDATE master_obat SET obat_master='" . htmlspecialchars($_POST['obat_master']) . "', kode_obat='" . htmlspecialchars($_POST['kode_obat']) . "', pbf_master3='" . htmlspecialchars($_POST['pbf_master3']) . "', pbf_master4='" . htmlspecialchars($_POST['pbf_master4']) . "', pbf_master1='" . htmlspecialchars($_POST['pbf_master1']) . "', pbf_master2='" . htmlspecialchars($_POST['pbf_master2']) . "' WHERE id = '" . htmlspecialchars($_POST['id']) . "'");

          echo "
                <script>
                    alert('Successfully');
                    document.location.href='index.php?halaman=daftar_obat_master';
                </script>
              ";
        }

        if (isset($_GET['del'])) {
          $koneksimaster->query("DELETE FROM master_obat WHERE id = '" . htmlspecialchars($_GET['del']) . "'");

          echo "
                    <script>
                        alert('Successfully');
                        document.location.href='index.php?halaman=daftar_obat_master';
                    </script>
                ";
        }
        ?>
      </div>
    </div>
  </div>
</div>


<!-- ALTER TABLE `master_obat` ADD `margin_inap` VARCHAR(10) NOT NULL DEFAULT '100' AFTER `kode_obat`, ADD `margin_jual` VARCHAR(10) NOT NULL DEFAULT '100' AFTER `margin_inap`; -->