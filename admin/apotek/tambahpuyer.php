<?php

$obat = $koneksimaster->query("SELECT * FROM puyer WHERE id = '$_GET[id]';")->fetch_assoc();
$daftar = $koneksimaster->query("SELECT * FROM puyer_detail WHERE id_puyer = '$_GET[id]';");

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
  <script>
    document.addEventListener('DOMContentLoaded', async () => {
      try {
        <?php
        $apiUrlgetObat = '../apotek/api_getObatMasterLokal.php';
        if (isset($_GET['inap'])) {
          $apiUrlgetObat .= '?inap';
        } elseif (isset($_GET['penjualan'])) {
          $apiUrlgetObat .= '?penjualan';
        } else {
          $apiUrlgetObat .= '';
        }
        ?>
        const obatData = await (await fetch('<?= $apiUrlgetObat ?>')).json();

        document.querySelectorAll('.obat-select').forEach(select => {
          // Simpan nilai yang sedang dipilih (jika ada)
          const selectedValue = select.value;

          // Buat array dari nilai option yang sudah ada
          const existingOptions = Array.from(select.options).map(opt => opt.value);

          // Filter data obat untuk hanya menambahkan yang belum ada
          const newOptions = obatData.filter(obat =>
            !existingOptions.includes(obat.kode_obat)
          );

          // Tambahkan option baru
          newOptions.forEach(obat => {
            select.add(new Option(obat.nama_obat, obat.kode_obat));
          });

          // Kembalikan nilai yang dipilih sebelumnya (jika masih ada)
          if (selectedValue && select.querySelector(`option[value="${selectedValue}"]`)) {
            select.value = selectedValue;
          }
        });
      } catch (error) {
        console.error('Error:', error);
      }
    });
  </script>
  <main>
    <div class="container">
      <div class="pagetitle">
        <h1>Daftar Racik</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?halaman=daftarpuyer" style="color:blue;">Racik</a></li>
          </ol>
        </nav>
      </div><!-- End Page Title -->

      <section class="section  py-4">
        <div class="container">

          <!-- <form method="post">
      <div class="row">
	    <div class="col-md-8">
        <input type="number" name="keyword" placeholder="Cari Pasien Bersadarkan NIK" class="form-control" id="keyword">
      </div>
        <div class="col-md-3">
      <button class="btn btn-primary" name="submit">Cari</button>
        </form>
        </div>
        </div>
      </div>
      <br>
      <br> -->

          <div class="row">
            <div class="col-lg-12 col-md-12">

              <div class="card">
                <div class="card-body">
                  <div style="margin-top: 50px; margin-bottom: 30px; text-align: right;">

                    <button type="button" class="btn btn-primary text-right" data-bs-toggle="modal" data-bs-target="#exampleModal2"> + Tambah Obat</button>

                  </div>
                  <h5 class="card-title">Data Paket Obat <?= $obat['nama_paket'] ?></h5>

                  <!-- Multi Columns Form -->
                  <div class="table-responsive">
                    <table id="myTable" class="table table-striped" style="width:100%">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Nama Obat</th>
                          <th>Jumlah</th>
                          <th></th>
                          <!-- <th>Aksi</th> -->

                        </tr>
                      </thead>
                      <tbody>

                        <?php $no = 1 ?>

                        <?php foreach ($daftar as $pecah) : ?>

                          <tr>
                            <td><?php echo $no; ?></td>
                            <td style="margin-top:10px;"><?php echo $pecah["nama_obat"]; ?></td>
                            <td style="margin-top:10px;"><?php echo $pecah['jml_obat'] ?></td>
                            <td>
                              <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#staticBackdrop<?= $pecah['id'] ?>"> Ubah</button>


                              <a type="button" class="btn btn-dark" href="index.php?halaman=tambahpuyer&id=<?php echo $pecah["id"]; ?>&idd=<?php echo $_GET["id"]; ?>&hapus" onclick="return confirm('Anda yakin mau menghapus item ini ?')"> Hapus</a>

                            </td>
                          </tr>


                          <?php $no += 1 ?>

                          <!-- Edit Data Modal Obat -->

                          <div class="modal fade" id="staticBackdrop<?= $pecah['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLabel">Nama Obat</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  <div class="row">
                                    <form method="post" enctype="multipart/form-data">
                                      <div class="control-group after-add-more">
                                        <!-- <div class="modal-body"> -->
                                        <div class="row">
                                          <div class="col-md-12">
                                            <label for="inputName5" class="form-label">Nama Obat</label><br>
                                            <select name="nama_obat" class="obat-select form-control w-100" style="width:100%;" id="selObat1" aria-label="Default select example">
                                              <option value="<?php echo $pecah["kode_obat"]; ?>"><?php echo $pecah["nama_obat"]; ?></option>
                                            </select>
                                            <br>
                                          </div>
                                          <div class="col-md-12">
                                            <label for="inputName5" class="form-label">Jumlah Obat</label><br>
                                            <input type="text" name="jml_obat" value="<?php echo $pecah['jml_obat'] ?>" class="form-control" placeholder="Masukan Jml">
                                            <br>
                                          </div>

                                        </div>

                                        <input type="number" name="id" value="<?php echo $pecah["id"]; ?>" hidden>

                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                          <input type="submit" class="btn btn-primary" name="editobat" value="Save changes">
                                        </div>
                                    </form>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                        <?php endforeach ?>

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

  <!-- Add Data Modal Obat -->
  <div class="modal  fade" role="dialog" id="exampleModal2" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Nama Obat</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <form method="post" enctype="multipart/form-data">
              <div class="control-group after-add-more">
                <!-- <div class="modal-body"> -->
                <div class="row">
                  <div class="col-md-12">
                    <label for="inputName5" class="form-label">Nama Obat</label><br>
                    <select name="nama_obat" class="obat-select form-control w-100" style="width:100%;" id="selObat1" aria-label="Default select example">
                      <option value="">Pilih</option>

                    </select>
                    <br>
                  </div>

                  <div class="col-md-12">
                    <label for="inputName5" class="form-label">Jumlah Obat</label><br>
                    <input type="text" name="jml_obat" class="form-control" placeholder="Masukan Jml">
                    <br>
                  </div>

                </div>


                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                  <input type="submit" class="btn btn-primary" name="saveobat" value="Save changes">
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>

</html>



<?php if (isset($_POST['saveobat'])) {
  $getSingle = $koneksimaster->query("SELECT * FROM master_obat WHERE kode_obat = '$_POST[nama_obat]'")->fetch_assoc();

  $koneksimaster->query("INSERT INTO puyer_detail 
  (nama_obat, kode_obat, jml_obat, id_puyer)
  VALUES ('$getSingle[obat_master]', '$getSingle[kode_obat]', '$_POST[jml_obat]', '$_GET[id]')
  ");
  echo "
    <script>
    alert('Data berhasil ditambah');
    document.location.href='index.php?halaman=tambahpuyer&id=$_GET[id]';
    </script>

    ";
} ?>

<?php if (isset($_POST['editobat'])) {
  $getSingle = $koneksimaster->query("SELECT * FROM master_obat WHERE kode_obat = '$_POST[nama_obat]'")->fetch_assoc();

  $koneksimaster->query("UPDATE puyer_detail SET

    nama_obat      = '$getSingle[obat_master]',
    kode_obat      = '$getSingle[kode_obat]',
    jml_obat      = '$_POST[jml_obat]'
    WHERE id = '$_POST[id]'
  ");

  echo "
    <script>
    alert('Data berhasil diubah');
    document.location.href='index.php?halaman=tambahpuyer&id=$_GET[id]';
    </script>

    ";
} ?>

<?php if (isset($_GET['hapus'])) {

  $koneksimaster->query("DELETE FROM puyer_detail WHERE id = '$_GET[id]'");

  echo "
    <script>
    alert('Data berhasil dihapus');
    document.location.href='index.php?halaman=tambahpuyer&id=$_GET[idd]';
    </script>

    ";
} ?>

<script>
  $(document).ready(function() {
    $('#myTable').DataTable({
      search: true,
      pagination: true
    });
  });
</script>