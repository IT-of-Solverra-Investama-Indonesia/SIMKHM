<div class="pagetitle">
    <h1>Gaji Dokter</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php?halaman=daftarapotek" style="color:blue;">Gaji Dokter</a></li>
      </ol>
    </nav>
</div>

<?php if(!isset($_GET['historyToday'])){?>
  <a class="btn btn-sm btn-primary" style="max-width: 150px;" href="index.php?halaman=gajidokter&historyToday">Riwayat Hari Ini</a>
  <center>
    <div class="card shadow-sm p-2" style="max-width: 600px; border-radius: 15px;">
        <h4>Tambah Gaji Dokter</h4>
        <form method="POST">
          <p class="mb-0 float-start" for=""><b>Dokter :</b></p>
          <select name="dokter" id="" class="form-control mb-2">
            <option value="" hidden>Pilih Dokter</option>
            <?php
              $getDokter = $koneksi->query("SELECT * FROM admin WHERE level= 'dokter'");
              foreach($getDokter as $dokter){
            ?>
              <option value="<?= $dokter['namalengkap']?>"><?= $dokter['namalengkap']?></option>
            <?php }?>
          </select>
          <p class="mb-0 float-start" for=""><b>Akun Gaji Dokter :</b></p>
          <select name="akun" id="" class="form-control mb-2">
            <option value="" hidden>Pilih Akun</option>
            <?php
              $getAkun = $koneksimaster->query("SELECT * FROM akungajidokter");
              foreach($getAkun as $akun){
            ?>
              <option value="<?= $akun['namaakungajidokter']?>"><?= $akun['namaakungajidokter']?></option>
            <?php }?>
          </select>
          <p class="mb-0 float-start" for=""><b>Besaran Gaji Dokter :</b></p>
          <input type="number" name="besaran" class="form-control mb-2" id="">
          <p class="mb-0 float-start" for=""><b>Keterangan Gaji Dokter :</b></p>
          <input type="text" name="ket" class="form-control mb-2" id="">
          <p class="mb-0 float-start" for=""><b>Unit Gaji Dokter :</b></p>
          <select name="unit" class="form-control mb-2" id="">
            <option value="">Pilih Unit</option>
            <option value="KHM 1">KHM 1</option>
            <option value="KHM 2">KHM 2</option>
            <option value="KHM 3">KHM 3</option>
            <option value="KHM 4">KHM 4</option>
          </select>
          <button name="save" class="btn btn-md btn-primary float-end">Simpan</button>
        </form>
    </div>
    <?php 
      if(isset($_POST['save'])){
        $koneksimaster->query("INSERT INTO gajidokter (tgl, dokter, akun, besaran, ket, petugas, shiftgaji, unit) VALUES ('".htmlspecialchars(date('Y-m-d'))."', '".htmlspecialchars($_POST['dokter'])."', '".htmlspecialchars($_POST['akun'])."', '".htmlspecialchars($_POST['besaran'])."', '".htmlspecialchars($_POST['ket'])."', '".htmlspecialchars($_SESSION['admin']['namalengkap'])."', '".htmlspecialchars($_SESSION['shift'])."', '".htmlspecialchars($_POST['unit'])."')");
  
        echo "
          <script>
            alert('Successfully');
            document.location.href='index.php?halaman=gajidokter';
          </script>
        ";
      }
    ?>
  </center>
<?php }else{?>
  <a class="btn btn-sm btn-dark mb-2" style="max-width: 150px;" href="index.php?halaman=gajidokter">Kembali</a>
  <div class="card shadow-sm p-2">
    <div class="table-responsive">
      <table class="table table-hover table-striped" id="myTable" style="font-size: 12px;">
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Dokter</th>
            <th>Akun</th>
            <th>Besaran</th>
            <th>Keterangan</th>
            <th>Petugas</th>
            <th>Unit</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $getGaji = $koneksimaster->query("SELECT * FROM gajidokter WHERE tgl = '".date('Y-m-d')."' ORDER BY idgaji DESC");
            foreach($getGaji as $gaji){
          ?>
            <tr>
              <td><?= $gaji['tgl']?></td>
              <td><?= $gaji['dokter']?></td>
              <td><?= $gaji['akun']?></td>
              <td><?= $gaji['besaran']?></td>
              <td><?= $gaji['ket']?></td>
              <td><?= $gaji['petugas']?></td>
              <td><?= $gaji['unit']?></td>
            </tr>
          <?php }?>
        </tbody>
      </table>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
  <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
  <script>
    $(document).ready( function () {
        $('#myTable').DataTable();
    } );
  </script>
<?php }?>