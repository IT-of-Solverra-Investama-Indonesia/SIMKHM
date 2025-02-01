<?php

$pemesanan = $koneksi->query("SELECT pemesanan.*,produk_kosmetik.*,pasien_kosmetik.*
FROM pemesanan join produk_kosmetik on pemesanan.produk_id = produk_kosmetik.id_produk
join pasien_kosmetik on pemesanan.user_id = pasien_kosmetik.idpasien GROUP BY code_nota ORDER BY id_pemesanan DESC
");
// print_r($pemesanan);

if (isset($_POST['filter'])) {

  $status = $_POST["status"];
  $pemesanan = $koneksi->query("SELECT pemesanan.*,produk_kosmetik.*,pasien_kosmetik.*
  FROM pemesanan join produk_kosmetik on pemesanan.produk_id = produk_kosmetik.id_produk
  join pasien_kosmetik on pemesanan.user_id = pasien_kosmetik.idpasien where status ='$status' GROUP BY code_nota 
  ORDER BY id_pemesanan DESC");
} else {
  $pemesanan = $koneksi->query("SELECT pemesanan.*,produk_kosmetik.*,pasien_kosmetik.*
  FROM pemesanan join produk_kosmetik on pemesanan.produk_id = produk_kosmetik.id_produk
  join pasien_kosmetik on pemesanan.user_id = pasien_kosmetik.idpasien GROUP BY code_nota 
  ORDER BY id_pemesanan DESC");
}


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
    <div class="container">
      <div class="pagetitle">
        <h1>Daftar Pemesanan Pasien</h1>

      </div><!-- End Page Title -->

      <section class="section  py-4">
        <div class="container">
          <div class="row">
            <div class="col-lg-12 col-md-12">

              <div class="card">
                <div class="card-body">

                  <!-- Multi Columns Form -->

                  <div class="table-responsive">
                    <br>
                    <form method="post" class="form-inline">
                      <div class="row">
                        <div class="col-sm-6">
                          <div class="form-group">
                            <!-- <label for="status" class="mr-2">Status</label> -->
                            <select name="status" class="form-control">
                              <option value="Menunggu_pembayaran">Menunggu Pembayaran</option>
                              <option value="Diproses">Diproses</option>
                              <option value="Dikirim">Dikirim</option>
                              <option value="Selesai">Selesai</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <button name="filter" class="btn btn-primary">Filter</button>
                          </div>
                        </div>
                      </div>
                    </form>
                    <br>
                    <table id="myTable" class="table table-striped" style="width:100%">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>No. Invoice</th>
                          <th>Nama</th>
                          <th>Alamat</th>
                          <th>No.Hp</th>
                          <th>Produk</th>
                          <th>Total</th>
                          <th>Status</th>
                          <th>Bukti Bayar</th>
                          <th>Nomor Resi</th>
                          <th>Aksi</th>

                        </tr>
                      </thead>
                      <tbody>

                        <?php $no = 1 ?>

                        <?php foreach ($pemesanan as $pecah) : ?>

                          <tr>
                            <td><?php echo $no; ?></td>
                            <td style="margin-top:10px;"><?php echo $pecah["code_nota"]; ?></td>
                            <td style="margin-top:10px;"><?php echo $pecah["nama_lengkap"]; ?></td>
                            <td style="margin-top:10px;"><?php echo $pecah["alamat_lengkap"]; ?></td>
                            <td>
                              <?php
                              $nohp = $pecah['nohp'];
                              $nohp = preg_replace('/^0/', '62', $nohp);
                              ?>
                              <a href="https://api.whatsapp.com/send?phone=<?= $nohp ?>" class="btn btn-sm btn-success"><i class="bi bi-whatsapp"></i></a>
                            </td>
                            <td>
                              <div class="table-responsive">
                                <table class="table">
                                  <thead>
                                    <tr>
                                      <th>Produk</th>
                                      <th>Harga</th>
                                      <th>Diskon</th>
                                      <th>Jumlah</th>
                                      <th>Sub</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                                    $totalProduk = 0;
                                    $getProd = $koneksi->query("SELECT * FROM pemesanan WHERE code_nota = '$pecah[code_nota]'");
                                    foreach ($getProd as $prod) {
                                    ?>
                                      <tr>
                                        <td><?= $prod['produk'] ?></td>
                                        <td>Rp <?= number_format($prod['harga'], 0, '', '.') ?></td>
                                        <td>Rp <?= number_format($prod['harga'] - ($prod['sub_harga'] / ($prod['jumlah'])), 0, '', '.') ?></td>
                                        <td>Rp <?= number_format($prod['jumlah'], 0, '', '.') ?></td>
                                        <td>Rp <?= number_format($prod['sub_harga'], 0, '', '.') ?></td>
                                      </tr>
                                      <?php $totalProduk += $prod['sub_harga'] ?>
                                    <?php } ?>
                                  </tbody>
                                </table>
                              </div>
                            </td>
                            <td>Rp<?= number_format($totalProduk, 0, '', '.') ?></td>
                            <td style="margin-top:10px;"><?php echo $pecah["status"]; ?></td>
                            <td>
                              <a href="../../kosmetik/bukti_pembayaran/<?php echo $pecah['bukti_pembayaran']; ?>" target="_blank">
                                <img src="../../kosmetik/bukti_pembayaran/<?php echo $pecah['bukti_pembayaran']; ?>" class="img-fluid" alt="">

                              </a>
                            </td>
                            <!-- <td style="margin-top:10px;"><?php echo $pecah["bukti_pembayarab"]; ?></td><img src="../../kosmetik/' + message.chat + '" class="img-fluid"> -->
                            <td><?= $prod['no_resi'] ?></td>

                            <td>
                              <a href="#" data-bs-toggle="modal" data-bs-target="#update<?= $pecah['code_nota'] ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-pencil" style="color:blueviolet;">Ubah</i></a></li>
                            </td>
                  </div>
                  </td>
                  </tr>
                  <div class="modal fade" id="update<?= $pecah['code_nota'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h1 class="modal-title fs-5" id="staticBackdropLabel">Update</h1>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="post">
                          <div class="modal-body">
                            <label for="">Nomor Resi</label>
                            <input type="text" class="form-control" name="no_resi">

                            <label for="">Status</label>
                            <select id="statuse" name="statuse" class="form-control">
                              <!-- <option value="" disabled selected>Pilih Status</option> -->
                              <!-- <option value="menunggu pembayaran">Menunggu Pembayaran</option> -->
                              <!-- <option value="Diproses">Diproses</option> -->
                              <option value="Dikirim">Dikirim</option>
                              <option value="Selesai">Selesai</option>
                            </select>

                            <input type="hidden" name="code_nota" value="<?= $pecah['code_nota'] ?>" class="form-control border border-success">
                          </div>

                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" name="update-status">Save</button>
                          </div>
                        </form>
                        <?php
                          if (isset($_POST['update-status'])) {
                            $status = $_POST['statuse'];
                            $code_nota = $_POST['code_nota'];
                            $koneksi->query("UPDATE pemesanan SET status = '$status', no_resi ='$_POST[no_resi]' WHERE code_nota='$code_nota' 
                                    ");
                            if(isset($_POST['no_resi']) OR $_POST['no_resi'] != ''){
                              $getPem = $koneksi->query("SELECT * FROM pemesanan WHERE code_nota = '$code_nota' LIMIT 1")->fetch_assoc();
                              $getNo = $koneksi->query("SELECT * FROM pasien_kosmetik WHERE idpasien = '$getPem[user_id]'")->fetch_assoc();
                              
                              $nohp = $getNo['nohp'];
                              $nohp = preg_replace('/^0/', '62', $nohp);
                              include '../rawatjalan/api_token_wa.php';


                              $curl = curl_init();
                              $phone = $nohp;
                              $message = urlencode("Pesanan Anda dengan nomor nota ".$code_nota." Telah dikirim dengan nomor resi ".$_POST['no_resi']." ");
          
                              curl_setopt($curl, CURLOPT_URL, "https://jogja.wablas.com/api/send-message?phone=$phone&message=$message&token=$token");
                              $result = curl_exec($curl);
                              curl_close($curl);
                            }
                            echo "
                                    <script>
                                    alert('Berhasil mengupdate status');
                                    document.location.href='index.php?halaman=pemesanan';
                                    </script>
                                    ";
                          }
                        ?>

                      </div>
                    </div>
                  </div>
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
      pagination: true
    });
  });
</script>