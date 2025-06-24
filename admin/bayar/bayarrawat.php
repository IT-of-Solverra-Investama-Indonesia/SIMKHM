<?php
$username = $_SESSION['admin']['namalengkap'];
$ambil = $koneksi->query("SELECT * FROM admin  WHERE username='$username';");
$nota = $koneksi->query("SELECT * FROM biaya_rawat WHERE idregis='$_GET[id]';")->fetch_assoc();
$pasien = $koneksi->query("SELECT * FROM pasien INNER JOIN rekam_medis ON rekam_medis.norm=pasien.no_rm WHERE TRIM(norm)='$_GET[rm]';");
$getRegis = $koneksi->query("SELECT * FROM registrasi_rawat WHERE idrawat='$_GET[id]'")->fetch_assoc();
$pecah = $pasien->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>KHM WONOREJO</title>
  <meta content="" name="description">
  <meta content="" name="keywords">



  <main>
    <div class="">
      <div class="pagetitle">
        <h1>Pembayaran</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="index.php" style="color:blue;">Pembayaran</a></li>
            <li class="breadcrumb-item">Buat Kuitansi</li>
          </ol>
        </nav>
      </div><!-- End Page Title -->
      <form method="post">

        <div class="">
          <div class="row">
            <div class="col-md-12">

              <div class="card">
                <div class="card-body col-md-12">
                  <h5 class="card-title">Detail Pasien</h5>

                  <!-- Multi Columns Form -->

                  <div class="col-md-12">
                    <label for="inputName5" class="form-label mb-0">Nomor Pembayaran <span style="font-size: 12px;">(Jika Nota Belum Terisi Maka Akan Otomatis)</span></label>
                    <?php if ($nota['nota'] != '') { ?>
                      <input type="text" class="form-control mb-2" id="inputName5" name="nota" placeholder="Masukkan Nama Pasien" value="<?php echo $nota['nota'] ?>">
                    <?php } else { ?>
                      <?php
                      $newNota = date('ymdhis');
                      ?>
                      <input type="text" class="form-control mb-2" id="inputName5" name="nota" placeholder="Masukkan Nama Pasien" value="<?php echo $newNota ?>">
                    <?php } ?>
                  </div>
                  <div class="col-md-12">
                    <label for="inputName5" class="form-label mb-0">Nomor Rekam Medis</label>
                    <input type="text" class="form-control mb-2" id="inputName5" value="<?php echo $pecah['norm'] ?>" placeholder="Masukkan Nama Pasien">
                  </div>
                  <div class="col-md-12">
                    <label for="inputName5" class="form-label mb-0">Pasien</label>
                    <input type="text" class="form-control mb-2" id="inputName5" value="<?php echo $pecah['nama_pasien'] ?>" placeholder="Masukkan Nama Pasien">
                  </div>
                  <div class="col-md-12">
                    <label for="inputName5" class="form-label mb-0">No BPJS (Jika "-" Maka Umum)</label>
                    <input type="text" class="form-control mb-2" id="inputName5" value="<?= $pecah['no_bpjs'] == '' ? '-' : $pecah['no_bpjs'] ?>" placeholder="Masukkan Nama Pasien">
                  </div>
                  <div class="col-md-12" style="margin-bottom:50px;">
                    <label for="inputName5" class="form-label mb-0">Tipe Pembiayaan</label>
                    <input type="text" class="form-control mb-2" id="inputName5" value="<?php echo $pecah['pembiayaan'] ?>" placeholder="Masukkan Nama Pasien">
                  </div>

                </div>
              </div>

              <?php

              $plan = $koneksi->query("SELECT * FROM layanan WHERE idrm = '$pecah[no_rm]' AND DATE_FORMAT(tgl_layanan, '%Y-%m-%d') = '" . date('Y-m-d', strtotime($getRegis['jadwal'])) . "'");

              ?>

              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Layanan <sup><span class="badge bg-success text-light" data-bs-toggle="modal" data-bs-target="#exampleModal">+ Tambah Layanan</span></sup></h5>
                  <!-- Add Data Modal Layanan -->
                  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Tambah Layanan/Tindakan</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="userEntry" method="post" enctype="multipart/form-data">
                          <div class="modal-body">
                            <div class="col-md-12">
                              <label for="inputName5" class="form-label">Layanan/Tindakan</label>
                              <select name="layanan" class="form-control form-control-sm" id="selLay" onchange="SelLay(this)">
                                <option hidden>Pilih Layanan</option>
                                <?php
                                $getLayananMaster = $koneksimaster->query("SELECT * FROM  master_layanan ORDER BY nama_layanan DESC");
                                ?>
                                <?php foreach ($getLayananMaster as $masterLayanan) { ?>
                                  <option value="<?= $masterLayanan['id'] ?>">
                                    <?= $masterLayanan['nama_layanan'] ?> || Rp <?= number_format($masterLayanan['harga'], 0, 0, '.') ?>
                                  </option>
                                <?php } ?>
                              </select>
                            </div>
                            <div class="col-md-12" style="margin-top:0px; height: 0.1px; visibility : hidden;">
                              <label for="inputName5" class="form-label">Jumlah</label>
                              <input type="text" name="jumlah_layanan" value="1" class="form-control form-control-sm" id="inputName5" placeholder="Masukkan Jumlah">
                            </div>
                            <input type="hidden" name="id_pasien" value="<?php echo $pecah['idpasien'] ?>">
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <input type="submit" class="btn btn-sm btn-primary" name="savelay" value="Save changes" />
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>

                  <?php
                  if (isset($_POST['savelay'])) {
                    $layanan = $_POST['layanan'];
                    $getLayananMasterById = $koneksimaster->query("SELECT * FROM master_layanan WHERE id='$layanan'")->fetch_assoc();
                    $DaftarLayanan = $nota['biaya_lain'] . "+" . $getLayananMasterById['nama_layanan'];
                    $HargaLayanan = intval($nota['total_lain']) + $getLayananMasterById['harga'];
                    // $ttlBiyLain = intval($getBiyLain['total_lain']) + intval($_POST['harga_layanan']);
                    $koneksi->query("UPDATE biaya_rawat SET biaya_lain = '$DaftarLayanan', total_lain = '$HargaLayanan' WHERE idregis='$_GET[id]'");
                    $koneksi->query("INSERT INTO layanan (layanan, kode_layanan, jumlah_layanan, id_pasien, idrm, tgl_layanan) VALUES ('$getLayananMasterById[nama_layanan]', '$getLayananMasterById[id]', '1', '$pecah[idpasien]', '$_GET[rm]', '$getRegis[jadwal]')");
                    if (isset($_GET['inap'])) {
                      echo "
                            <script>
                                document.location.href='index.php?halaman=rmedis&inap&id=$_GET[id]&tgl=$_GET[tgl]';
                            </script>
                          ";
                    } else {
                      echo "
                            <script>
                            alert('Successfully added');
                                document.location.href='index.php?halaman=bayarrawat&rm=$_GET[rm]&id=$_GET[id]&tgl=$_GET[tgl]';
                            </script>
                          ";
                    }
                  }
                  ?>
                  <!-- Multi Columns Form -->
                  <div class="row">
                    <div class="table-responsive">
                      <!-- Button trigger modal -->
                      <div id="employee_table">
                        <table class="table table-sm" style="font-size: 12px;">
                          <thead>
                            <t>
                              <th width="5%">No.</th>
                              <th width="40%">Layanan/Tindakan</th>
                              <th width="30%">Jumlah</th>
                              <th>Aksi</th>
                            </t>
                          </thead>
                          <tbody>
                            <?php $no = 1 ?>
                            <?php foreach ($plan as $plan) : ?>
                              <tr>
                                <td><?php echo $no; ?></td>
                                <td style="margin-top:10px;"><?php echo $plan["layanan"]; ?></td>
                                <td style="margin-top:10px;"><?php echo $plan["jumlah_layanan"]; ?></td>
                                <td>
                                  <a href="index.php?halaman=bayarrawat&rm=<?= htmlspecialchars($_GET['rm']) ?>&id=<?= htmlspecialchars($_GET['id']) ?>&tgl=<?= htmlspecialchars($_GET['tgl']) ?>&deleteLayanan=<?= $plan['idlayanan'] ?>" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
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
              <?php
              if (isset($_GET['deleteLayanan'])) {
                $idLayanan = htmlspecialchars($_GET['deleteLayanan']);
                $getSingleLayanaFormDelete = $koneksi->query("SELECT * FROM layanan WHERE idlayanan = '" . $idLayanan . "'")->fetch_assoc();

                $getMasterLayananSingle = $koneksimaster->query("SELECT * FROM master_layanan WHERE id = '$getSingleLayanaFormDelete[kode_layanan]'")->fetch_assoc();

                $layanan = $nota['biaya_lain'];
                $penghapusLayanan = $getMasterLayananSingle['nama_layanan'];

                $arrayLayanan = explode('+', $layanan);
                $arrayLayanan = array_filter($arrayLayanan, function ($item) use ($penghapusLayanan) {
                  return $item !== $penghapusLayanan;
                });

                $layananBaru = implode('+', $arrayLayanan);
                $nominalBaru = $nota['total_lain'] - $getMasterLayananSingle['harga'];
                $koneksi->query("UPDATE biaya_rawat SET biaya_lain = '$layananBaru', total_lain = '$nominalBaru' WHERE idregis='$_GET[id]'");
                $koneksi->query("DELETE FROM layanan WHERE idlayanan = '" . $idLayanan . "'");

                echo "
                <script>
                    alert('Successfully deleted');
                    document.location.href='index.php?halaman=bayarrawat&rm=$_GET[rm]&id=$_GET[id]&tgl=$_GET[tgl]';
                  </script>
                ";
              }
              ?>

              <div style="margin-bottom:2px; margin-top:30px">
                <hr>

                <?php

                $obat = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[rm]' AND DATE_FORMAT(tgl_pasien, '%Y-%m-%d') = DATE_FORMAT('$_GET[tgl]', '%Y-%m-%d')");
                ?>
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Obat</h5>

                    <!-- Multi Columns Form -->
                    <div class="row">
                      <div class="table-responsive">
                        <!-- Button trigger modal -->
                        <br>

                        <div id="employee_table">
                          <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th width="5%">No.</th>
                                <th width="50%">Obat</th>
                                <th width="20%">Dosis</th>
                                <th width="20%">Durasi</th>
                              </tr>
                            </thead>
                            <tbody>

                              <?php $no = 1 ?>

                              <?php foreach ($obat as $obat) : ?>

                                <tr>
                                  <td><?php echo $no; ?></td>
                                  <td style="margin-top:10px;"><?php echo $obat["nama_obat"]; ?></td>
                                  <td style="margin-top:10px;"><?php echo $obat["dosis1_obat"]; ?> X <?php echo $obat["dosis2_obat"]; ?> <?php echo $obat["per_obat"]; ?></td>
                                  <td style="margin-top:10px;"><?php echo $obat["durasi_obat"]; ?> hari</td>
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
              <br>

              <div id="employee_table">
                <table class="table">
                  <!-- <tr>
                       <td><b>Subtotal</td></b><td style="margin-left: 20px;"><input type="number" value="35000" class="form-control" id="inputCity" placeholder="Subtotal" disabled>
                        </td>
                      </tr> -->
                  <tr>
                    <td><b>Poli</td></b>
                    <td style="margin-left: 20px;">
                      <div class="row">
                        <div class="col-9">
                          <input value="<?= $nota['poli'] ?>" readonly type="number" class="form-control" name="poli" id="poliHarga" placeholder="Masukkan Potongan">
                        </div>
                        <div class="col-3">
                          <button class="btn btn-secondary" onclick="nolkan()" type="button">Nolkan</button>
                        </div>
                      </div>
                    </td>
                  </tr>
                  <script>
                    function nolkan() {
                      document.getElementById('poliHarga').value = 0;
                    }
                  </script>
                  <tr>
                    <td><b>Potongan/Jaminan</td></b>
                    <td style="margin-left: 20px;"><input value="0" type="number" class="form-control" name="potongan" id="inputCity" placeholder="Masukkan Potongan"></td>
                  </tr>
                  <tr>
                    <td><b>Biaya Lain</td></b>
                    <td style="margin-left: 20px;"><input type="text" class="form-control" name="biaya_lain" id="inputCity" value="<?= $nota['biaya_lain'] ?>" placeholder="Masukkan Biaya Lain"></td>
                  </tr>
                  <tr>
                    <td><b>Total Biaya Lain</td></b>
                    <td style="margin-left: 20px;"><input value="<?= $nota['total_lain'] ?>" type="number" class="form-control" name="total_lain" id="inputCity" placeholder="Masukkan Total Biaya lain"></td>
                  </tr>
                  <!-- <tr>
                      <td><b>Total</td></b><td style="margin-left: 20px;"><input value="" type="number" class="form-control" id="inputCity" placeholder="Masukkan Total" disabled></td>
                      </tr> -->
                  <!-- <tr>
                       <td><b>Status Pembayaran</td></b><td style="margin-left: 20px;"><select id="inputState" name="status" class="form-select">
                        <option hidden>Pilih</option>
                        <option value="Sudah Bayar">Sudah Bayar</option>
                        <option value="Belum Bayar">Belum Bayar</option></td>
                      </tr> -->
                  <!--<tr>
                       <td><b>Jumlah Bayar</td></b><td style="margin-left: 20px;"><input type="number" class="form-control" id="inputCity" placeholder="Masukkan Jml Pembayaran" disabled></td>
                      </tr> -->
                </table>
              </div>
            </div>
          </div>

          <div class="text-center" style="margin-top: 50px; margin-bottom: 80px;">
            <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
          </div>
      </form><!-- End Multi Columns Form -->

    </div>
    </div>
    </div>
    </div>
    </div>
    </div>

    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


  </body>

</html>

<script>
  var myModal = document.getElementById('myModal')
  var myInput = document.getElementById('myInput')

  myModal.addEventListener('shown.bs.modal', function() {
    myInput.focus()
  });
</script>

<?php
if (isset($_POST['simpan'])) {
  $biaya_lain = $_POST['biaya_lain'];
  $total_lain = $_POST['total_lain'];
  $potongan = $_POST['potongan'];
  // $id_pasien=$_POST['id_pasien'];
  $notaNew = $_POST['nota'];
  // $status=$_POST['status'];
  $koneksi->query("UPDATE biaya_rawat SET biaya_lain='$biaya_lain', nota='$notaNew', total_lain='$total_lain', potongan='$potongan', poli = '$_POST[poli]' WHERE idregis='$_GET[id]'");
  $koneksi->query("UPDATE registrasi_rawat SET kasir='$username', pembayaran_at = '".date('Y-m-d H:i:s')."' WHERE idrawat='$_GET[id]'");
  echo "
    <script>
      document.location.href='index.php?halaman=daftarbayar&day';
    </script>
  ";
}
?>