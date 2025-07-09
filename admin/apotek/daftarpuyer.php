<?php

$obat = $koneksimaster->query("SELECT * FROM puyer;");

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
        <h1>Daftar Racik</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?halaman=daftarpuyer" style="color:blue;">Racik</a></li>
          </ol>
        </nav>
      </div><!-- End Page Title -->

      <section class="section  py-4">
        <div class="">

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

                    <button type="button" class="btn btn-primary text-right" data-bs-toggle="modal" data-bs-target="#exampleModal2"> + Tambah Nama Paket</button>

                  </div>
                  <h5 class="card-title">Data Racik</h5>

                  <!-- Multi Columns Form -->
                  <div class="table-responsive">
                    <table id="myTable" class="table table-striped table-sm" style="width:100%; font-size: 12px;">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Nama Paket</th>
                          <th>Dosis</th>
                          <th>Petunjuk Pemakaian</th>
                          <th>Durasi</th>
                          <th>Catatan Obat</th>
                          <th></th>
                          <!-- <th>Aksi</th> -->
                        </tr>
                      </thead>
                      <tbody>

                        <?php $no = 1 ?>

                        <?php foreach ($obat as $pecah) : ?>

                          <tr>
                            <td><?php echo $no; ?></td>
                            <td >
                              <?php echo $pecah["nama_paket"]; ?> <br>
                              <span style="font-size: 10px;">
                                <?php
                                $getObatDetail = $koneksimaster->query("SELECT * FROM puyer_detail JOIN master_obat ON master_obat.obat_master = puyer_detail.nama_obat WHERE id_puyer = '$pecah[id]'");
                                foreach($getObatDetail as $obDet){
                                ?>
                                  <?= $obDet['obat_master']?> (<?= $obDet['kode_obat']?>)
                                <?php }?>
                              </span>
                            </td>
                            <td style=""><?php echo $pecah["dosis_paket1"]; ?> X <?php echo $pecah["dosis_paket2"]; ?> Per Hari</td>
                            <td style=""><?php echo $pecah['petunjuk_paket'] ?></td>
                            <td style=""><?php echo $pecah["durasi_paket"]; ?></td>
                            <td style=""><?php echo $pecah["ctt_paket"]; ?></td>
                            <td>
                              <div class="dropdown">
                                <i data-bs-toggle="dropdown" style="color: blue; font-weight: bold; font-size: 20px;" class="bi bi-three-dots-vertical"></i>
                                <ul class="dropdown-menu">
                                  <?php
                                  // Ambil data obat dari database
                                  $getObatDetail = $koneksimaster->query("SELECT * FROM puyer_detail WHERE id_puyer = '$pecah[id]'");
                                  $obatDetails = []; // Array untuk menyimpan data obat

                                  // Loop melalui hasil query dan simpan ke array
                                  while ($obat = $getObatDetail->fetch_assoc()) {
                                    $obatDetails[] = $obat['nama_obat'] . ' (' . $obat['jml_obat'] . ' pcs)';
                                  }

                                  // Konversi ke JSON untuk digunakan di JavaScript
                                  $obatJson = json_encode($obatDetails);
                                  ?>
                                  <li>
                                    <a class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;" onclick="showObat(<?= htmlspecialchars($obatJson, ENT_QUOTES, 'UTF-8') ?>)"
                                      data-bs-toggle="modal" data-bs-target="#showObat">
                                      <i class="bi bi-eye-fill" style="color:blue;"></i> Lihat Obat
                                    </a>
                                  </li>
                                  <li><a href="index.php?halaman=tambahpuyer&id=<?php echo $pecah["id"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-eye-fill" style="color:blue;"></i> Tambah Obat</a></li>
                                  <li><a href="index.php?halaman=daftarpuyer&id=<?php echo $pecah["id"]; ?>&hapus" class="dropdown-item" style="text-decoration: none; font-weight: bold; margin-left: 2px;" onclick="return confirm('Anda yakin mau menghapus item ini ?')"><i class="bi bi-trash" style="color:red;"></i> Hapus</a>
                                  </li>
                                </ul>
                              </div>
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

          </div>
        </div>
    </div>
    <div class="modal fade" id="showObat" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">Daftar Obat</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
            <div id="showObatDetail" class="list-group">
              <!-- Data obat akan dimasukkan di sini -->
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>

    <script>
      function showObat(obatArray) {
        const container = document.getElementById("showObatDetail");
        container.innerHTML = ''; // Kosongkan kontainer terlebih dahulu

        if (obatArray.length === 0) {
          container.innerHTML = '<div class="alert alert-info">Tidak ada data obat</div>';
          return;
        }

        // Buat elemen untuk setiap obat
        obatArray.forEach(obat => {
          const item = document.createElement('div');
          item.className = 'list-group-item';
          item.textContent = obat;
          container.appendChild(item);
        });
      }
    </script>
    </section>

    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Add Data Modal Obat -->
  <div class="modal  fade" role="dialog" id="exampleModal2" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Nama Paket</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <form method="post" enctype="multipart/form-data">
              <div class="control-group after-add-more">
                <!-- <div class="modal-body"> -->
                <div class="row">
                  <div class="col-md-12">
                    <label for="inputName5" class="form-label">Nama Paket</label><br>
                    <input type="text" name="nama_paket" class="form-control" placeholder="Masukan Nama Paket">
                    <br>
                  </div>
                  <label for="inputName5" class="form-label">Dosis</label><br>
                  <div class="col-md-6">
                    <div class="input-group">
                      <input type="text" class="form-control" id="" name="dosis_paket1">
                      <input type="text" style="text-align: center;" class="form-control" placeholder="X" disabled>
                      <input type="text" class="form-control" id="" name="dosis_paket2">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <select id="inputState" name="" class="form-select">
                      <option>Per Hari</option>
                    </select>
                    <br>
                  </div>

                  <div class="col-md-12">
                    <label for="inputName5" class="form-label">Durasi Pemakaian</label><br>
                    <input type="text" name="durasi_paket" class="form-control" placeholder="Masukan Durasi">
                    <br>
                  </div>
                  <div class="col-md-12">
                    <label for="inputName5" class="form-label">Petunjuk Pemakaian</label><br>
                    <input type="text" name="petunjuk_paket" class="form-control" placeholder="Masukan Petunjuk">
                    <br>
                  </div>
                  <div class="col-md-12">
                    <label for="inputName5" class="form-label">Catatan Interaksi Obat</label><br>
                    <input type="text" name="ctt_paket" class="form-control" placeholder="Masukan Catatan">
                    <br>
                  </div>

                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                  <input type="submit" class="btn btn-primary" name="save" value="Save changes">
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>

</html>



<?php if (isset($_POST['save'])) {

  $koneksimaster->query("INSERT INTO puyer 

  (nama_paket, dosis_paket1, dosis_paket2, durasi_paket, petunjuk_paket, ctt_paket)

  VALUES ('$_POST[nama_paket]', '$_POST[dosis_paket1]','$_POST[dosis_paket2]','$_POST[durasi_paket]','$_POST[petunjuk_paket]', '$_POST[ctt_paket]')

  ");

  echo "
    <script>
    document.location.href='index.php?halaman=daftarpuyer';
    </script>

    ";
} ?>

<?php if (isset($_GET['hapus'])) {

  $koneksimaster->query("DELETE FROM puyer WHERE id = '$_GET[id]'");

  echo "
    <script>
    alert('Data berhasil dihapus');
    document.location.href='index.php?halaman=daftarpuyer';
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