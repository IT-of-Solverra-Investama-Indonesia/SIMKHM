<?php 
  $username=$_SESSION['admin']['username'];
  $ambil=$koneksi->query("SELECT * FROM admin  WHERE username='$username';");
  $nota=$koneksi->query("SELECT * FROM biaya_rawat WHERE idregis='$_GET[id]';")->fetch_assoc();
  $pasien=$koneksi->query("SELECT * FROM pasien INNER JOIN rekam_medis  WHERE norm='$_GET[rm]';");
  $pecah=$pasien->fetch_assoc();
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
    <div class="container">
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

   <div class="container">
          <div class="row">
            <div class="col-md-12">

            <div class="card">
            <div class="card-body col-md-12">
              <h5 class="card-title">Detail Pasien</h5>

              <!-- Multi Columns Form -->
             
                <div class="col-md-12">
                  <label for="inputName5" class="form-label mb-0">Nomor Pembayaran</label>
                  <input type="text" class="form-control mb-2" id="inputName5" name="nota" placeholder="Masukkan Nama Pasien" value="<?php echo $nota['nota']?>" >
                </div>
              <div class="col-md-12">
                  <label for="inputName5" class="form-label mb-0">Nomor Rekam Medis</label>
                  <input type="text" class="form-control mb-2" id="inputName5" value="<?php echo $pecah['norm']?>" placeholder="Masukkan Nama Pasien">
              </div>
              <div class="col-md-12">
                  <label for="inputName5" class="form-label mb-0">Pasien</label>
                  <input type="text" class="form-control mb-2" id="inputName5" value="<?php echo $pecah['nama_pasien']?>" placeholder="Masukkan Nama Pasien">
              </div>
              <div class="col-md-12">
                  <label for="inputName5" class="form-label mb-0">No BPJS (Jika "-" Maka Umum)</label>
                  <input type="text" class="form-control mb-2" id="inputName5" value="<?= $pecah['no_bpjs'] == '' ? '-': $pecah['no_bpjs'] ?>" placeholder="Masukkan Nama Pasien">
              </div>
              <div class="col-md-12" style="margin-bottom:50px;">
                  <label for="inputName5" class="form-label mb-0">Tipe Pembiayaan</label>
                  <input type="text" class="form-control mb-2" id="inputName5" value="<?php echo $pecah['pembiayaan']?>" placeholder="Masukkan Nama Pasien">
              </div>
              
            </div>
          </div>

<?php 

$plan=$koneksi->query("SELECT * FROM layanan WHERE idrm = '$_GET[rm]' AND DATE_FORMAT(tgl_layanan, '%Y-%m-%d') = DATE_FORMAT('$_GET[tgl]', '%Y-%m-%d')"); 

?>

            <div class="card">
            <div class="card-body">
              <h5 class="card-title">Layanan</h5>

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
                            <th width="40%">Layanan/Tindakan</th>
                            <!-- <th width="30%">Harga Layanan</th> -->
                            <th width="30%">Jumlah</th>
                        </tr>
                      </thead>
                        <tbody>

                    <?php $no=1 ?>

                  <?php foreach ($plan as $plan) : ?>

                  <tr>
                  <td><?php echo $no; ?></td>
                  <td style="margin-top:10px;"><?php echo $plan["layanan"]; ?></td>
                  <!-- <td style="margin-top:10px;"><?php echo $plan["kode_layanan"]; ?></td> -->
                  <td style="margin-top:10px;"><?php echo $plan["jumlah_layanan"]; ?></td>
                  </tr>

                  <?php $no +=1 ?>
                  <?php endforeach ?>

                      </tbody>
                      
                    </table>
                </div>
                </div>
              </div>

            <br>
            <br>
                
             
                </div>
              </div>
           
             <div style="margin-bottom:2px; margin-top:30px">
              <hr>

<?php 

$obat=$koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[rm]' AND DATE_FORMAT(tgl_pasien, '%Y-%m-%d') = DATE_FORMAT('$_GET[tgl]', '%Y-%m-%d')"); 
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

                    <?php $no=1 ?>

                  <?php foreach ($obat as $obat) : ?>

                  <tr>
                  <td><?php echo $no; ?></td>
                  <td style="margin-top:10px;"><?php echo $obat["nama_obat"]; ?></td>
                  <td style="margin-top:10px;"><?php echo $obat["dosis1_obat"]; ?> X <?php echo $obat["dosis2_obat"]; ?>  <?php echo $obat["per_obat"]; ?></td>
                  <td style="margin-top:10px;"><?php echo $obat["durasi_obat"]; ?> hari</td>
                  </tr>

                  <?php $no +=1 ?>
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
                       <td><b>Potongan/Jaminan</td></b><td style="margin-left: 20px;"><input value="0" type="number" class="form-control" name="potongan" id="inputCity" placeholder="Masukkan Potongan"></td>
                      </tr>
                      <tr>
                       <td><b>Biaya Lain</td></b><td style="margin-left: 20px;"><input value="" type="text" class="form-control" name="biaya_lain" id="inputCity" placeholder="Masukkan Biaya Lain"></td>
                      </tr>
                      <tr>
                       <td><b>Total Biaya Lain</td></b><td style="margin-left: 20px;"><input value="" type="number" class="form-control" name="total_lain" id="inputCity" placeholder="Masukkan Total Biaya lain"></td>
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

myModal.addEventListener('shown.bs.modal', function () {
  myInput.focus()
});
</script>

<?php
if (isset ($_POST['simpan'])) {

  $biaya_lain=$_POST['biaya_lain'];
  $total_lain=$_POST['total_lain'];
  $potongan=$_POST['potongan'];
  // $id_pasien=$_POST['id_pasien'];
  $notaNew=$_POST['nota'];
  // $status=$_POST['status'];
 


$koneksi->query("UPDATE biaya_rawat SET biaya_lain='$biaya_lain', nota='$notaNew', total_lain='$total_lain', potongan='$potongan' WHERE idregis='$_GET[id]'");

$koneksi->query("UPDATE registrasi_rawat SET kasir='$username' WHERE idrawat='$_GET[id]'");

echo "
  <script>

  document.location.href='index.php?halaman=daftarbayar';

  </script>

  ";


}
?>

