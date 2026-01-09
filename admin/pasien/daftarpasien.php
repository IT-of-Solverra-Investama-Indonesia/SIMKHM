<?php
if (isset($_GET['perbaikanObatRM'])) {
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

  $nextPage = $cekPage + 1;

  $getDataRm = $koneksi->query("SELECT * FROM rekam_medis WHERE norm != '' ORDER BY id_rm ASC LIMIT $start, $limit;");

  foreach ($getDataRm as $rm) {
    // $dataSingle = $koneksi->query("SELECT * FROM rekam_medis WHERE norm = '$rm[idrm]' AND DATE_FORMAT(jadwal, '%Y-%m-%d') <= '$rm[tgl_pasien]' ORDER BY jadwal DESC LIMIT 1")->fetch_assoc();

    $koneksi->query("UPDATE obat_rm SET rekam_medis_id ='$rm[id_rm]' WHERE rekam_medis_id IS NULL AND idrm = '$rm[norm]' AND tgl_pasien = '" . date('Y-m-d', strtotime($rm['jadwal'])) . "'");
  }

  if ($getDataRm->num_rows == 0) {
    echo "
        <script>
          alert('Successfully');
          document.location.href='index.php?halaman=daftarpasien';
        </script>
      ";
  }

  echo "
      <script>
        document.location.href='index.php?halaman=daftarpasien&perbaikanObatRM&pageCheck=" . $nextPage . "';
      </script>
    ";
}

// $koneksi->query("UPDATE obat_rm SET rekam_medis_id = NULL");
?>
<?php
$urlPage = "index.php?halaman=daftarpasien";
$whereKey = '';
if (isset($_GET['keyword']) && isset($_GET['alamat'])) {
  $urlPage = "index.php?halaman=daftarpasien&keyword=$_GET[keyword]&alamat=$_GET[alamat]&search=$_GET[search]";
  $keyword = htmlspecialchars($_GET['keyword']);
  $alamat = htmlspecialchars($_GET['alamat']);
  $whereKey = "WHERE (nama_lengkap LIKE '%$keyword%' OR nama_ibu LIKE '%$keyword%' OR DATE_FORMAT(tgl_lahir, '%d-%m-%Y') LIKE '%$keyword%' OR pembiayaan LIKE '%$keyword%' OR no_bpjs LIKE '%$keyword%' OR no_rm LIKE '%$keyword%' OR nohp LIKE '%$keyword%') AND (provinsi LIKE '%$alamat%' OR kota LIKE '%$alamat%' OR kecamatan LIKE '%$alamat%' OR kelurahan LIKE '%$alamat%' OR alamat LIKE '%$alamat%')";
}
$query = "SELECT * FROM pasien $whereKey ORDER BY idpasien DESC";

//   Pagination
// Parameters for pagination
$limit = 50; // Number of entries to show in a page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;
$total_records = $koneksi->query($query)->num_rows;
// Calculate total pages
$total_pages = ceil($total_records / $limit);
$cekPage = '';
if (isset($_GET['page'])) {
  $cekPage = $_GET['page'];
} else {
  $cekPage = '1';
}
// End Pagination

$pasien = $koneksi->query($query . " LIMIT $start, $limit;");

// Ubah Nama AN Tn Ny   
function hitungUsia($tanggal_lahir)
{
  $tanggal_lahir = new DateTime($tanggal_lahir);
  $sekarang = new DateTime();
  $usia = $sekarang->diff($tanggal_lahir)->y;
  return $usia;
}

function ubahNamaLengkap($nama_lengkap, $tanggal_lahir, $jenis_kelamin, $status_nikah)
{
  $usia = hitungUsia($tanggal_lahir);

  if ($usia <= 12) {
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
  $koneksi->query("UPDATE pasien SET nama_lengkap = '" . $nama_dengan_imbuhan . "' WHERE idpasien = '" . $pasienUsia['idpasien'] . "'");
}
// End Ubah Nama AN Tn Ny 

if(isset($_GET['booking'])){
  $getBooking = $koneksi->query("SELECT * FROM registrasi_booking WHERE idrawat = '$_GET[booking]'")->fetch_assoc(); 

  $koneksi->query("INSERT INTO `registrasi_rawat`( `nama_pasien`, `umur`, `jenis_kunjungan`, `perawatan`, `kamar`, `dokter_rawat`, `jadwal`, `status_antri`, `antrian`, `id_pasien`, `no_rm`, `carabayar`, `kasir`, `petugaspoli`, `perawat`, `shift`, `kode`, `status_sinc`, `start`, `end`, `oke`, `keluhan`, `perujuk`, `perujuk_hp`, `perujuk_file`, `faskes_bpjs`, `kategori`) VALUES ('$getBooking[nama_pasien]','$getBooking[umur]','$getBooking[jenis_kunjungan]','$getBooking[perawatan]','$getBooking[kamar]','$getBooking[dokter_rawat]','$getBooking[jadwal]','$getBooking[status_antri]','$getBooking[antrian]','$getBooking[id_pasien]','$getBooking[no_rm]','$getBooking[carabayar]','$getBooking[kasir]','$getBooking[petugaspoli]','$getBooking[perawat]','$getBooking[shift]','$getBooking[kode]','$getBooking[status_sinc]','$getBooking[start]','$getBooking[end]','$getBooking[oke]','$getBooking[keluhan]','$getBooking[perujuk]','$getBooking[perujuk_hp]','$getBooking[perujuk_file]','$getBooking[faskes_bpjs]','$getBooking[kategori]')");

  $koneksi->query("DELETE FROM registrasi_booking WHERE idrawat = '$_GET[booking]'");

  echo "
      <script>
        alert('Booking berhasil diregistrasi ke pelayanan');
        document.location.href='index.php?halaman=daftarpasien';
      </script>
    ";

}

if(isset($_GET['bookingdel'])){
  $koneksi->query("DELETE FROM registrasi_booking WHERE idrawat = '$_GET[bookingdel]'");
  echo "
      <script>
        alert('Booking berhasil dihapus');
        document.location.href='index.php?halaman=daftarpasien';
      </script>
    ";
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
  <h5 class="card-title">Daftar Pasien</h5>
  <!-- Modal Booking -->
  <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Daftar Booking</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-sm table-hover table-striped" style="font-size: 12px;">
              <thead>
                <tr>
                  <th>Booking</th>
                  <th>Pasien</th>
                  <th>NoRm</th>
                  <th>Antrian</th>
                  <th>Carabayar</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $getBooking = $koneksi->query("SELECT * FROM registrasi_booking WHERE DATE_FORMAT(jadwal, '%Y-%m-%d') >= '" . date('Y-m-d') . "' ORDER BY jadwal ASC");
                foreach ($getBooking as $booking) :
                ?>
                  <tr>
                    <td><?php echo date("d-m-Y H:i:s", strtotime($booking['jadwal'])) ?></td>
                    <td><?php echo $booking['nama_pasien'] ?></td>
                    <td><?php echo $booking['no_rm'] ?></td>
                    <td><?php echo $booking['antrian'] ?></td>
                    <td><?php echo $booking['carabayar'] ?></td>
                    <td>
                      <?php if (date("d-m-Y", strtotime($booking['jadwal'])) == date("d-m-Y")) { ?>
                        <a href="index.php?halaman=daftarpasien&booking=<?= $booking['idrawat'] ?>" onclick="return confirm('Data yang dimasukan ke registrasi akan hilang pada daftar booking dan perpindah langsung ke pelayanan, apakah anda yakin ingin meregistrasi pasien ini ?')" class="btn btn-sm btn-primary">Registrasi</a>
                      <?php } ?>
                      <a href="index.php?halaman=daftarpasien&bookingdel=<?= $booking['idrawat'] ?>" onclick="return confirm('Apakah anda yakin ingin jadwal booking ini ?')" class="btn btn-sm btn-danger">Hapus</a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="card shadow p-2 mb-1">
    <form method="get">
      <input type="hidden" name="halaman" value="daftarpasien">
      <div class="row g-1">
        <div class="col-5">
          <input type="text" name="keyword" placeholder="Cari Pasien" class="form-control form-control-sm" id="keyword" value="<?= $_POST['keyword'] ?? '' ?>">
        </div>
        <div class="col-5">
          <input type="text" name="alamat" id="" placeholder="Alamat" class="form-control form-control-sm" value="<?= $_POST['alamat'] ?? '' ?>">
        </div>
        <div class="col-1">
          <button class="btn btn-sm btn-primary" name="search"><i class="bi bi-search"></i></button>
        </div>
      </div>
    </form>
  </div>

  <div class="card p-2">
    <div class="">
      <div style="text-align: left;">
        <a href="index.php?halaman=pasien" class="btn btn-sm btn-primary mb-0"><i class="bi bi-plus"></i> Pasien</a>
        <!-- Button trigger modal Booking -->
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
          <i class="bi bi-eye"></i> Daftar Booking
        </button>
        <a href="?halaman=gabungnorm" class="btn btn-sm btn-warning">Gabung No RM</a>
      </div>
      <h6 class="mb-0  mt-2"><b>Data Pasien</b></h6>
      <div class="table-responsive">
        <table id="myTable" class="table table-striped" style="width:100%; font-size: 12px;">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama Pasien</th>
              <th>Nama Ayah</th>
              <th>TglLahir</th>
              <th>Pembiayaan</th>
              <th>NoRm</th>
              <th>NoBPJS</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php $no = $start + 1 ?>
            <?php foreach ($pasien as $pecah) : ?>
              <tr>
                <td><?php echo $no; ?></td>
                <td style="margin-top:10px;">
                  <?php echo $pecah["nama_lengkap"]; ?><br>
                  <span style="font-size: 8px;"><?= $pecah['provinsi'] ?>|<?= $pecah['kota'] ?>|<?= $pecah['kecamatan'] ?>|<?= $pecah['kelurahan'] ?>|<?= $pecah['alamat'] ?></span>
                </td>
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
                      <li><a href="index.php?halaman=registrasirawat&book&id=<?php echo $pecah["idpasien"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-bookmark-plus" style="color:black;"></i> Booking</a></li>
                      <!-- <li><a href="index.php?halaman=tambahigd&id=<?php echo $pecah["idpasien"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-hospital" style="color:black;"></i> IGD</a></li> -->
                      <li><a href="../pasien/gen_con.php?id=<?php echo $pecah["idpasien"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-printer" style="color:black;"></i> General Consent</a></li>
                      <li><a href="../pasien/fal-risk.php?id=<?php echo $pecah["idpasien"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-printer" style="color:black;"></i> Fall Risk</a></li>
                      <li>
                        <a href="index.php?halaman=hapuspasien&id=<?php echo $pecah["idpasien"]; ?>" class="dropdown-item" style="text-decoration: none; font-weight: bold; margin-left: 2px;" onclick="return confirm('Anda yakin mau menghapus item ini ?')">
                          <i class="bi bi-trash" style="color:red;"></i> Hapus
                        </a>
                      </li>
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
          echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . ($page - 1) . '">Back</a></li>';
        }

        // Determine the start and end page
        $start_page = max(1, $page - 2);
        $end_page = min($total_pages, $page + 2);

        if ($start_page > 1) {
          echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=1">1</a></li>';
          if ($start_page > 2) {
            echo '<li class="page-item"><span class="page-link">...</span></li>';
          }
        }

        for ($i = $start_page; $i <= $end_page; $i++) {
          if ($i == $page) {
            echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
          } else {
            echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . $i . '">' . $i . '</a></li>';
          }
        }

        if ($end_page < $total_pages) {
          if ($end_page < $total_pages - 1) {
            echo '<li class="page-item"><span class="page-link">...</span></li>';
          }
          echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . $total_pages . '">' . $total_pages . '</a></li>';
        }

        // Next button
        if ($page < $total_pages) {
          echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . ($page + 1) . '">Next</a></li>';
        }

        echo '</ul>';
        echo '</nav>';
        ?>

      </div>
    </div>
  </div>
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