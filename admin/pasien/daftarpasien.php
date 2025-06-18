<?php
  if(isset($_GET['perbaikanObatRM'])){
    $getDataRmResult = $koneksi->query("SELECT * FROM rekam_medis WHERE norm != '' ORDER BY id_rm ASC");

    $limit = 100; // Number of entries to show in a page
    $page = isset($_GET['pageCheck']) ? (int)$_GET['pageCheck'] : 1;
    $start = ($page - 1) * $limit;

    $total_records = $getDataRmResult->num_rows;
    // Calculate total pages
    $total_pages = ceil($total_records / $limit);

    $cekPage = '';
    if (isset($_GET['pageCheck'])) {
      $cekPage = $_GET['pageCheck'];
    } else {
      $cekPage = 1;
    }

    $nextPage = $cekPage+1;

    $getDataRm = $koneksi->query("SELECT * FROM rekam_medis WHERE norm != '' ORDER BY id_rm ASC LIMIT $start, $limit;");

    foreach($getDataRm as $rm){
      // $dataSingle = $koneksi->query("SELECT * FROM rekam_medis WHERE norm = '$rm[idrm]' AND DATE_FORMAT(jadwal, '%Y-%m-%d') <= '$rm[tgl_pasien]' ORDER BY jadwal DESC LIMIT 1")->fetch_assoc();

      $koneksi->query("UPDATE obat_rm SET rekam_medis_id ='$rm[id_rm]' WHERE rekam_medis_id IS NULL AND idrm = '$rm[norm]' AND tgl_pasien = '".date('Y-m-d', strtotime($rm['jadwal']))."'");
    }

    if($getDataRm->num_rows == 0){
      echo "
        <script>
          alert('Successfully');
          document.location.href='index.php?halaman=daftarpasien';
        </script>
      ";
    }

    echo "
      <script>
        document.location.href='index.php?halaman=daftarpasien&perbaikanObatRM&pageCheck=".$nextPage."';
      </script>
    ";
  }

  // $koneksi->query("UPDATE obat_rm SET rekam_medis_id = NULL");
?>
<?php
  //   Pagination
    // Parameters for pagination
    $limit = 50; // Number of entries to show in a page
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $start = ($page - 1) * $limit;

    if (isset($_POST['search']) && $_POST['keyword'] != '') {

      $result = $koneksi->query("SELECT COUNT(*) as count FROM pasien WHERE nama_lengkap LIKE '%$_POST[keyword]%' OR nama_ibu LIKE '%$_POST[keyword]%' OR DATE_FORMAT(tgl_lahir, '%d-%m-%Y') LIKE '%$_POST[keyword]%' OR pembiayaan LIKE '%$_POST[keyword]%' OR no_bpjs LIKE '%$_POST[keyword]%' OR no_rm LIKE '%$_POST[keyword]%' OR provinsi LIKE '%$_POST[keyword]%' OR kota LIKE '%$_POST[keyword]%' OR kecamatan LIKE '%$_POST[keyword]%' OR kelurahan LIKE '%$_POST[keyword]%' OR alamat LIKE '%$_POST[keyword]%' OR nohp LIKE '%$_POST[keyword]%'");
      $total_records = $result->fetch_assoc()['count'];

      // Calculate total pages
      $total_pages = ceil($total_records / $limit);

      $cekPage = '';
      if (isset($_GET['page'])) {
        $cekPage = $_GET['page'];
      } else {
        $cekPage = '1';
      }
    // End Pagination

    $pasien = $koneksi->query("SELECT * FROM pasien WHERE nama_lengkap LIKE '%$_POST[keyword]%' OR nama_ibu LIKE '%$_POST[keyword]%' OR DATE_FORMAT(tgl_lahir, '%d-%m-%Y') LIKE '%$_POST[keyword]%' OR pembiayaan LIKE '%$_POST[keyword]%' OR no_bpjs LIKE '%$_POST[keyword]%' OR no_rm LIKE '%$_POST[keyword]%'  OR provinsi LIKE '%$_POST[keyword]%' OR kota LIKE '%$_POST[keyword]%' OR kecamatan LIKE '%$_POST[keyword]%' OR kelurahan LIKE '%$_POST[keyword]%' OR alamat LIKE '%$_POST[keyword]%' OR nohp LIKE '%$_POST[keyword]%' ORDER BY idpasien DESC LIMIT $start, $limit;");
  } else {
    // Get the total number of records
    // $tgl_mulaii = date('Y-m-d', strtotime('2024-03-28'));
    $result = $koneksi->query("SELECT COUNT(*) as count FROM pasien ORDER BY idpasien DESC");
    $total_records = $result->fetch_assoc()['count'];

    // Calculate total pages
    $total_pages = ceil($total_records / $limit);

    $cekPage = '';
    if (isset($_GET['page'])) {
      $cekPage = $_GET['page'];
    } else {
      $cekPage = '1';
    }
    // End Pagination

    $pasien = $koneksi->query("SELECT * FROM pasien ORDER BY idpasien DESC LIMIT $start, $limit;");
  }

  // Ubah Nama AN Tn Ny   
    function hitungUsia($tanggal_lahir) {
      $tanggal_lahir = new DateTime($tanggal_lahir);
      $sekarang = new DateTime();
      $usia = $sekarang->diff($tanggal_lahir)->y;
      return $usia;
    }
    
    function ubahNamaLengkap($nama_lengkap, $tanggal_lahir, $jenis_kelamin, $status_nikah) {
      $usia = hitungUsia($tanggal_lahir);
      
      if ($usia >= 1 && $usia <= 12) {
          return "An " . $nama_lengkap;
      } elseif ($usia >= 13) {
          if ($jenis_kelamin == '1' && $status_nikah != '1') { // '1' untuk laki-laki
              return "Sdr " . $nama_lengkap;
          } elseif ($jenis_kelamin == '2' && $status_nikah != '1') { // '2' untuk perempuan
              return "Nn " . $nama_lengkap;
          }
          
          if ($jenis_kelamin == '1' && $status_nikah == '1') { // '1' untuk laki-laki menikah
            return "Tn " . $nama_lengkap;
          } elseif ($jenis_kelamin == '2' && $status_nikah == '1') { // '2' untuk perempuan menikah
              return "Ny " . $nama_lengkap;
          }
      } 
      return $nama_lengkap;
    }
    
    // Mendapatkan pasien yang namanya belum diubah
    $getPasienUsia = $koneksi->query("SELECT * FROM pasien WHERE nama_lengkap NOT LIKE 'An %' AND nama_lengkap NOT LIKE 'Sdr %' AND nama_lengkap NOT LIKE 'Nn %' AND nama_lengkap NOT LIKE 'Tn %' AND nama_lengkap NOT LIKE 'Ny %';");
    
    foreach ($getPasienUsia as $pasienUsia) {
      $nama_dengan_imbuhan = ubahNamaLengkap($pasienUsia['nama_lengkap'], $pasienUsia['tgl_lahir'], $pasienUsia['jenis_kelamin'], $pasienUsia['status_nikah']);
      
      // Update nama_lengkap dalam database
      $koneksi->query("UPDATE pasien SET nama_lengkap = '".$nama_dengan_imbuhan."' WHERE idpasien = '".$pasienUsia['idpasien']."'");
    }
  // End Ubah Nama AN Tn Ny 

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
        <h1>Daftar Pasien</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?halaman=daftarpasien" style="color:blue;">Pasien</a></li>
          </ol>
        </nav>
      </div><!-- End Page Title -->
    </div>
    <br>
    <br>

    <div class="row">
      <div class="col-lg-12 col-md-12">

        <div class="card">
          <div class="card-body">
            <div style="margin-top: 50px; margin-bottom: 30px; text-align: right;">

              <a href="index.php?halaman=pasien" class="btn btn-primary"><i class="bi bi-plus"></i> Pasien</a>

            </div>

            <div class="container">
              <form method="post">
                <div class="row">
                  <div class="col-md-8">
                    <input type="text" name="keyword" placeholder="Cari Pasien" class="form-control" id="keyword" value="<?= $_POST['keyword'] ?? '' ?>">
                  </div>
                  <div class="col-md-3">
                    <button class="btn btn-primary" name="search">Cari</button>
              </form>
            </div>

            <h5 class="card-title">Data Pasien</h5>

            <!-- Multi Columns Form -->
            <div class="table-responsive">
              <table id="myTable" class="table table-striped" style="width:100%">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Pasien</th>
                    <th>Nama Ayah</th>
                    <th>Tgl Lahir</th>
                    <th>Pembiayaan</th>
                    <th>No Rm</th>
                    <th>No BPJS</th>
                    <th></th>
                    <!-- <th>Aksi</th> -->

                  </tr>
                </thead>
                <tbody>

                  <?php $no = $start + 1 ?>

                  <?php foreach ($pasien as $pecah) : ?>

                    <tr>
                      <td><?php echo $no; ?></td>
                      <td style="margin-top:10px;"><?php echo $pecah["nama_lengkap"]; ?></td>
                      <td style="margin-top:10px;"><?php echo $pecah["nama_ibu"]; ?></td>
                      <td style="margin-top:10px;"><?php echo date("d-m-Y", strtotime($pecah['tgl_lahir'])) ?></td>
                      <td style="margin-top:10px;"><?php echo $pecah["pembiayaan"]; ?></td>
                      <td style="margin-top:10px;"><?php echo $pecah["no_rm"]; ?></td>
                      <td style="margin-top:10px;"><?php echo $pecah["no_bpjs"]; ?></td>
                      <td>
                        <div class="dropdown">
                          <i data-bs-toggle="dropdown" style="color: blue; font-weight: bold; font-size: 20px;" class="bi bi-three-dots-vertical"></i>
                          <ul class="dropdown-menu">
                            <li><a href="index.php?halaman=detailpasien&id=<?php echo $pecah["idpasien"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-eye-fill" style="color:blue;"></i> Detail</a></li>
                            <li><a href="index.php?halaman=registrasirawat&id=<?php echo $pecah["idpasien"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-person" style="color:black;"></i> Registrasi</a></li>
                            <!-- <li><a href="index.php?halaman=tambahigd&id=<?php echo $pecah["idpasien"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-hospital" style="color:black;"></i> IGD</a></li> -->
                            <li><a href="../pasien/gen_con.php?id=<?php echo $pecah["idpasien"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-printer" style="color:black;"></i> General Consent</a></li>
                            <li><a href="../pasien/fal-risk.php?id=<?php echo $pecah["idpasien"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-printer" style="color:black;"></i> Fall Risk</a></li>
                            <li><a href="index.php?halaman=hapuspasien&id=<?php echo $pecah["idpasien"]; ?>" class="dropdown-item" style="text-decoration: none; font-weight: bold; margin-left: 2px;" onclick="return confirm('Anda yakin mau menghapus item ini ?')">
                                <i class="bi bi-trash" style="color:red;"></i> Hapus</a></li>
                          </ul>
                        </div>
                      </td>
                    </tr>

                    <?php $no += 1 ?>
                  <?php endforeach ?>

                </tbody>
              </table>


              <?php
              // Display pagination
              echo '<nav>';
              echo '<ul class="pagination justify-content-center">';

              // Back button
              if ($page > 1) {
                echo '<li class="page-item"><a class="page-link" href="index.php?halaman=daftarpasien&page=' . ($page - 1) . '">Back</a></li>';
              }

              // Determine the start and end page
              $start_page = max(1, $page - 2);
              $end_page = min($total_pages, $page + 2);

              if ($start_page > 1) {
                echo '<li class="page-item"><a class="page-link" href="index.php?halaman=daftarpasien&page=1">1</a></li>';
                if ($start_page > 2) {
                  echo '<li class="page-item"><span class="page-link">...</span></li>';
                }
              }

              for ($i = $start_page; $i <= $end_page; $i++) {
                if ($i == $page) {
                  echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
                } else {
                  echo '<li class="page-item"><a class="page-link" href="index.php?halaman=daftarpasien&page=' . $i . '">' . $i . '</a></li>';
                }
              }

              if ($end_page < $total_pages) {
                if ($end_page < $total_pages - 1) {
                  echo '<li class="page-item"><span class="page-link">...</span></li>';
                }
                echo '<li class="page-item"><a class="page-link" href="index.php?halaman=daftarpasien&page=' . $total_pages . '">' . $total_pages . '</a></li>';
              }

              // Next button
              if ($page < $total_pages) {
                echo '<li class="page-item"><a class="page-link" href="index.php?halaman=daftarpasien&page=' . ($page + 1) . '">Next</a></li>';
              }

              echo '</ul>';
              echo '</nav>';
              ?>

            </div>
          </div>
        </div>
      </div>

    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


</body>

</html>

<!-- <script>
    $(document).ready(function() {
        $('#myTable').DataTable( {
         search: true,
         pagination: true
        } );
    } );
</script> -->