<?php 

$obat=$koneksimaster->query("SELECT * FROM puyer;"); 

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
      <h1>Daftar Racik</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php?halaman=daftarpuyer" style="color:blue;">Racik</a></li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

      <section class="section  py-4">
        <div class="container">

              
          <div class="row">
            <div class="col-lg-12 col-md-12">

            <div class="card">
            <div class="card-body">
                <!-- <div style="margin-top: 50px; margin-bottom: 30px; text-align: right;">
               
                <button type="button" class="btn btn-primary text-right" data-bs-toggle="modal" data-bs-target="#exampleModal2"> + Tambah Nama Paket</button>

                </div> -->
              <h5 class="card-title">Pilih Obat Racik</h5>

              <div class="row">
                <form method="post" enctype="multipart/form-data">
                  <div class="control-group after-add-more">
                      <!-- <div class="modal-body"> -->
                      <div class="row">
                        <div class="col-md-6">
                        <select name="nama_obat" class="form-control w-100" style="width:100%;" id="selObat1" aria-label="Default select example">

                          <option value="" hidden>Pilih</option>

                            <?php
                                $getObat = $koneksimaster->query("SELECT * FROM puyer ORDER BY nama_paket ASC");
  
                                foreach ($getObat as $data) {
                            ?>
                              <option value="<?= $data['id']?>"><?= $data['nama_paket']?></option>
                            <?php }?>
                          </select>
                          <br>
                        </div>

                        <div class="col-md-6">
                        <button class="btn btn-info" name='lihat'>Lihat</button>
                        </div>
                      </div>  
                  
                    <input type="submit" class="btn btn-primary" name="save" value="Save changes">
                  </div>
                </form>
              </div>

              </section>
</body>

</html>
<?php
  $getLastRM = $koneksi->query("SELECT  *, COUNT(*) AS jumm, MAX(id_rm) as id_rm FROM rekam_medis WHERE norm = '" . htmlspecialchars($_GET['id']) . "' AND DATE_FORMAT(jadwal, '%Y-%m-%d') <= '" . date('Y-m-d', strtotime($_GET['tgl'])) . "' ORDER BY id_rm DESC LIMIT 1")->fetch_assoc();
?>


<?php if (isset ($_POST['save'])) 
{

  $ambil=$koneksimaster->query("SELECT * FROM puyer join puyer_detail on puyer.id=puyer_detail.id_puyer where puyer.id='$_POST[nama_obat]' ");
        foreach($ambil as $pecah){
          $catatan_obat = $pecah['ctt_paket'];
          $nama = $pecah['nama_obat'];
          $jml_dokter = $pecah['jml_obat'];
          $dosis1_obat = $pecah['dosis_paket1'];
          $dosis2_obat = $pecah['dosis_paket2'];
          $durasi_obat = $pecah['durasi_paket'];
          $petunjuk_obat = $pecah['petunjuk_paket'];
          $jenis_obat = $pecah['nama_paket'];

          //update stok
          // $ObatKode = $koneksi->query("SELECT id_obat, jml_obat FROM apotek WHERE tipe = 'Rajal' AND nama_obat= '$nama'")->fetch_assoc();

          // $stokAkhir = $ObatKode['jml_obat']-$jml_dokter;

          // $koneksi->query("UPDATE apotek SET jml_obat = '$stokAkhir' WHERE id_obat = '$ObatKode[id_obat]'");
          
          $getKodeObat = $koneksimaster->query("SELECT * FROM master_obat WHERE obat_master = '$nama' LIMIT 1")->fetch_assoc();

          $koneksi->query("INSERT INTO obat_rm SET
              catatan_obat    = '$catatan_obat',
              nama_obat      = '$nama',
              kode_obat      = '$getKodeObat[kode_obat]',
              jml_dokter      = '$jml_dokter',
              dosis1_obat      = '$dosis1_obat',
              dosis2_obat      = '$dosis2_obat',
              durasi_obat      = '$durasi_obat',
              petunjuk_obat      = '$petunjuk_obat',
              jenis_obat      = '$jenis_obat',
              rekam_medis_id = '$getLastRM[id_rm]',
              tgl_pasien      = '$_GET[tgl]',
              idrm      = '$_GET[id]'
          ");

        }

    echo "
    <script>
    document.location.href='index.php?halaman=rmedis&id=$_GET[id]&tgl=$_GET[tgl]';
    </script>

    ";

}?>

<?php 

//tombol lihat

if (isset ($_POST['lihat'])) {

$produkcopy=$_POST['nama_obat'];

$ambilproduk=$koneksimaster->query("SELECT * FROM puyer join puyer_detail on puyer.id=puyer_detail.id_puyer where puyer.id='$produkcopy' ");

$pecahproduk=$ambilproduk->fetch_assoc();

$id=$pecahproduk['id_puyer'];



 echo "<meta http-equiv='refresh' content='1;url=index.php?halaman=tambahpuyer&id=$id'>";



}



 ?>

<script>
    $(document).ready(function() {
        $('#myTable').DataTable( {
         search: true,
         pagination: true
        } );
    } );
</script>
