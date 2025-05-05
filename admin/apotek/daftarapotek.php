<?php
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
                    <th>Jumlah Obat</th>
                    <th>Harga Beli</th>
                    <th>Aktif Ranap</th>
                    <th>Aktif Poli</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Data akan diisi oleh DataTables via AJAX -->
                </tbody>
              </table>
            </div>



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

<link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.dataTables.css" />
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
      processing: true,
      serverSide: true,
      ajax: {
        url: '../apotek/daftarapotek_api.php?getData',
        type: 'POST'
      },
      lengthMenu: [10, 30, 50, 100, 10000],
      pageLength: 30,
      // dom: 'Bfrtip',
      // buttons: [
      //   'excel', // Tombol Export Excel
      //   'print' // Tombol Print
      // ],
      columns: [{
          data: null,
          render: function(data, type, row) {
            return `<input type="checkbox" name="selectedIds[]" value="${row.id_obat}">`;
          },
          orderable: false
        },
        {
          data: null,
          render: function(data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
          },
          orderable: false
        },
        {
          data: 'nama_obat'
        },
        {
          data: 'id_obat'
        },
        {
          data: 'jumlah_beli'
        },
        {
          data: 'harga_beli'
        },
        {
          data: 'aktif_ranap',
          render: function(data, type, row) {
            if (data === 'aktif') {
              return `<a href="index.php?halaman=daftarapotek&aktifranap=nonaktif&kode_obat=${row.id_obat}" class="btn btn-sm btn-success"><i class="bi bi-check-circle"></i></a>`;
            } else {
              return `<a href="index.php?halaman=daftarapotek&aktifranap=aktif&kode_obat=${row.id_obat}" class="btn btn-sm btn-danger"><i class="bi bi-x-circle"></i></a>`;
            }
          },
          orderable: false
        },
        {
          data: 'aktif_poli',
          render: function(data, type, row) {
            if (data === 'aktif') {
              return `<a href="index.php?halaman=daftarapotek&aktifpoli=nonaktif&kode_obat=${row.id_obat}" class="btn btn-sm btn-success"><i class="bi bi-check-circle"></i></a>`;
            } else {
              return `<a href="index.php?halaman=daftarapotek&aktifpoli=aktif&kode_obat=${row.id_obat}" class="btn btn-sm btn-danger"><i class="bi bi-x-circle"></i></a>`;
            }
          },
          orderable: false
        },
        {
          data: 'id_obat',
          render: function(data) {
            return `<a href="index.php?halaman=detailapotek&id=${data}" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i></a>`;
          },
          orderable: false
        }
      ]
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