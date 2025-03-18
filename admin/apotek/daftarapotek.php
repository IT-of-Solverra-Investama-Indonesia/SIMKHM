<?php
$pasien = $koneksi->query("SELECT *, SUM(jml_obat) as jumlah_beli FROM apotek GROUP BY id_obat order by nama_obat ASC");
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
          <!-- Multi Columns Form -->
          <div class="table-responsive">
            <table id="myTable" class="table table-striped" style="width:100%">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Nama Obat</th>
                  <th>Kode Obat</th>
                  <!-- <th>Tipe Obat</th> -->
                  <th>Jumlah Obat</th>
                  <!-- <th>Margin Inap</th> -->
                  <!-- <th>Margin Non Inap</th> -->
                  <th>Harga Beli</th>
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
                    <td><?php echo $no; ?></td>
                    <td style="margin-top:10px;"><?php echo $pecah["nama_obat"]; ?></td>
                    <td style="margin-top:10px;"><?php echo $pecah["id_obat"]; ?></td>
                    <!-- <td style="margin-top:10px;"><?php echo $pecah["tipe"]; ?></td> -->
                    <td style="margin-top:10px;"><?php echo $pecah["jumlah_beli"]; ?></td>
                    <!-- <td style="margin-top:10px;"><?php echo $pecah["margininap"]; ?></td> -->
                    <!-- <td style="margin-top:10px;"><?php echo $pecah["marginnoninap"]; ?></td> -->
                    <td style="margin-top:10px;"><?php echo $single["harga_beli"]; ?></td>
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
        </div>
      </div>
    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


</body>

</html>

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
    float: right !important;
    border: none;
    padding: 8px 16px !important;
    border-radius: 4px !important;
    cursor: pointer !important;
    margin-left: 10px !important;
  }
</style>