<?php
$date = date("Y-m-d");
date_default_timezone_set('Asia/Jakarta');
$username = $_SESSION['admin']['username'];
$petugas = $_SESSION['admin']['namalengkap'];
$ambil = $koneksi->query("SELECT * FROM admin  WHERE username='$username';");
$pasien = $koneksi->query("SELECT * FROM pasien  WHERE no_rm='$_GET[id]';")->fetch_assoc();

$jadwal = $koneksi->query("SELECT * FROM registrasi_rawat  WHERE no_rm='$_GET[id]' AND date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' ")->fetch_assoc();
function getFullUrl()
{
  $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ||
    $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

  return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

if (isset($_POST['tagTeman'])) {
  $temanPerawatIds = $_POST['temanPerawat']; // Array of selected nurse IDs
  $getLastCtt = $koneksi->query("SELECT *, COUNT(id) as total FROM ctt_penyakit_inap WHERE norm='$_GET[id]' AND id = '$_POST[id]' order by id DESC LIMIT 1")->fetch_assoc();

  if ($getLastCtt['total'] == 0) {
    echo "
      <script>
        alert('Tidak ada catatan perkembangan penyakit untuk di tag');
        document.location.href='index.php?halaman=cttpenyakit&id=$_GET[id]&inap&tgl=$_GET[tgl]';
      </script>
    ";
    exit();
  }

  // Loop through each selected nurse and insert separately
  foreach ($temanPerawatIds as $temanPerawatId) {
    $getTeman = $koneksi->query("SELECT * FROM admin WHERE idadmin = '$temanPerawatId'")->fetch_assoc();
    $petugasName = $getTeman['namalengkap'];

    $koneksi->query("INSERT INTO ctt_penyakit_inap(tgl, norm, ctt_dokter, ctt_tedis, petugas, kamar, pasien, ctt_penyakit_inap.object, alergi, assesment, plan, intruksi, edukasi, dokter) VALUES ('$getLastCtt[tgl]', '$_GET[id]','$getLastCtt[ctt_dokter]', '" . $koneksi->real_escape_string($getLastCtt['ctt_tedis']) . "', '$petugasName', '$getLastCtt[kamar]', '$getLastCtt[pasien]', '" . $koneksi->real_escape_string($getLastCtt['object']) . "', '" . $koneksi->real_escape_string($getLastCtt['alergi']) . "', '" . $koneksi->real_escape_string($getLastCtt['assesment']) . "', '" . $koneksi->real_escape_string($getLastCtt['plan']) . "', '" . $koneksi->real_escape_string($getLastCtt['intruksi']) . "', '" . $koneksi->real_escape_string($getLastCtt['edukasi']) . "','" . $_SESSION['dokter_rawat'] . "')");
  }
  echo "
    <script>
      alert('Berhasil menandai teman perawat pada catatan perkembangan penyakit.');
      document.location.href='index.php?halaman=cttpenyakit&id=$_GET[id]&inap&tgl=$_GET[tgl]';
    </script>
  ";
  exit();
}

if(isset($_GET['delete'])){
  // $koneksi->query("DELETE FROM ctt_penyakit_inap WHERE id='$_GET[ctt]'");
  $getCttToDelete = $koneksi->query("SELECT * FROM ctt_penyakit_inap WHERE id='$_GET[ctt]'")->fetch_assoc();
  $koneksi->query("DELETE FROM ctt_penyakit_inap WHERE norm='$getCttToDelete[norm]' AND tgl='$getCttToDelete[tgl]'");
  echo "
    <script>
      alert('Berhasil menghapus catatan perkembangan penyakit.');
      document.location.href='index.php?halaman=cttpenyakit&id=$_GET[id]&inap&tgl=$_GET[tgl]';
    </script>
  ";
  exit();
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
</head>

<body>

  <main>
    <div class="container">
      <div class="pagetitle">
        <h1>CATATAN PERKEMBANGAN PENYAKIT TERINTEGRASI RI</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="index.php?halaman=daftarrmedis" style="color:blue;">Rekam Medis</a></li>
            <li class="breadcrumb-item">Perkembangan Penyakit</li>
          </ol>
        </nav>
      </div>
    </div>
    <form class="row g-3" method="post" enctype="multipart/form-data">
      <div class="">
        <!-- End Page Title -->
        <!-- <?= $start ?> -->
        <div class="">
          <div class="row">
            <div class="col-md-12">
              <div class="card" style="margin-top:10px">
                <div class="card-body col-md-12">
                  <h5 class="card-title">Data Pasien</h5>
                  <!-- Multi Columns Form -->
                  <div class="row">
                    <div class="col-md-6">
                      <label for="inputName5" class="form-label">Nama Pasien</label>
                      <input type="text" class="form-control" name="pasien" id="inputName5" value="<?php echo $pasien['nama_lengkap'] ?>" placeholder="Masukkan Nama Pasien" readonly>
                    </div>
                    <div class="col-md-6" style="margin-bottom:20px;">
                      <label for="inputName5" class="form-label">No RM</label>
                      <input type="text" class="form-control" id="inputName5" name="jadwal" value="<?php echo $pasien['no_rm'] ?>" placeholder="Masukkan Nama Pasien" readonly>
                    </div>
                    <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                      <label for="inputName5" class="form-label">Tanggal Lahir</label>
                      <input type="text" class="form-control" id="inputName5" name="jadwal" value="<?php echo date("d-m-Y", strtotime($pasien['tgl_lahir'])) ?>" placeholder="Masukkan Nama Pasien" readonly>
                    </div>
                    <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                      <label for="inputName5" class="form-label">Alamat</label>
                      <input type="text" class="form-control" id="inputName5" name="jadwal" value="<?php echo $pasien['alamat'] ?>" placeholder="Masukkan Nama Pasien" readonly>
                    </div>
                    <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                      <label for="inputName5" class="form-label">Ruangan</label>
                      <input type="text" class="form-control" id="inputName5" name="kamar" value="<?php echo $jadwal['kamar'] ?>" placeholder="Masukkan Nama Pasien">
                    </div>
                    <?php if ($pasien["jenis_kelamin"] == 1) { ?>
                      <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                        <label for="inputName5" class="form-label">Jenis Kelamin</label>
                        <input type="text" class="form-control" id="inputName5" name="jadwal" value="Laki-laki" placeholder="Masukkan Nama Pasien" readonly>
                      </div>
                    <?php } else { ?>
                      <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                        <label for="inputName5" class="form-label">Jenis Kelamin</label>
                        <input type="text" class="form-control" id="inputName5" name="jadwal" value="Perempuan" placeholder="Masukkan Nama Pasien" readonly>
                      </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-body">
                  <div style="margin-bottom:1px; margin-top:30px" id="editorZone">
                    <h6 class="card-title">Hasil Pemeriksaan, Analisa & Rencana Penatalaksanaan Pasien </h6>
                  </div>
                  <?php
                  if (isset($_GET['ctt'])) {
                    $getDataCopy = $koneksi->query("SELECT * FROM ctt_penyakit_inap WHERE id = '$_GET[ctt]'")->fetch_assoc();
                  }
                  if (isset($_GET['ubah'])) {
                    $getDataCopy = $koneksi->query("SELECT * FROM ctt_penyakit_inap WHERE id = '$_GET[idcct]'")->fetch_assoc();
                  }
                  ?>
                  <p>(Instruksi ditulis dengan rinci dan jelas)</p>
                  <!-- Multi Columns Form -->
                  <div class="row">
                    <div class="col-md-12">
                      <label for="inputName5" class="form-label">Tgl & Jam</label>
                      <input type="datetime-local" class="form-control" id="inputName5" name="tgl" value="<?= date("Y-m-d H:i:s") ?>">
                    </div>
                    <div class="col-md-6" style="margin-top:20px;">
                      <label for="inputName5" class="form-label">Subject</label>
                      <textarea name="ctt_tedis" id="editor" style="width:100%; height:150px">
                          <?php if (isset($_GET['ctt']) or isset($_GET['ubah'])) { ?>
                            <?= $getDataCopy['ctt_tedis'] ?>
                          <?php } ?>
                      </textarea>

                    </div>
                    <div class="col-md-6" style="margin-top:20px;">
                      <label for="inputName5" class="form-label">Object</label>
                      <textarea name="object" id="editor2" style="width:100%; height:150px">
                          <?php if (isset($_GET['ctt']) or isset($_GET['ubah'])) { ?>
                            <?= $getDataCopy['object'] ?>
                          <?php } ?>
                      </textarea>
                    </div>
                    <div class="col-md-6" style="margin-top:20px;">
                      <label for="">Alergi</label>
                      <input type="text" name="alergi" id="" class="form-control" value="<?php if (isset($_GET['ctt']) or isset($_GET['ubah'])) {
                                                                                            echo $getDataCopy['alergi'];
                                                                                          } ?>" placeholder="Alergi Obat" style="width:100%; height:50px">
                    </div>
                    <div class="col-md-6" style="margin-top:20px;">
                      <label for="">Assesment</label>
                      <textarea name="assesment" id="editor3" style="width:100%; height:150px">
                        <?php if (isset($_GET['ctt']) or isset($_GET['ubah'])) { ?>
                          <?= $getDataCopy['assesment'] ?>
                        <?php } ?>
                      </textarea>
                    </div>
                    <div class="col-md-6" style="margin-top:20px;">
                      <label for="">Plan</label>
                      <textarea name="plan" id="editor4" style="width:100%; height:150px">
                        <?php if (isset($_GET['ctt']) or isset($_GET['ubah'])) { ?>
                          <?= $getDataCopy['plan'] ?>
                        <?php } ?>
                      </textarea>
                    </div>
                    <div class="col-md-6" style="margin-top:20px;">
                      <label for="">Intruksi</label>
                      <textarea name="intruksi" id="editor5" style="width:100%; height:150px">
                        <?php if (isset($_GET['ctt']) or isset($_GET['ubah'])) { ?>
                          <?= $getDataCopy['intruksi'] ?>
                        <?php } ?>
                      </textarea>
                    </div>
                    <div class="col-md-6" style="margin-top:20px;">
                      <label for="">Edukasi</label>
                      <textarea name="edukasi" id="editor6" style="width:100%; height:150px">
                        <?php if (isset($_GET['ctt']) or isset($_GET['ubah'])) { ?>
                          <?= $getDataCopy['edukasi'] ?>
                        <?php } ?>
                      </textarea>
                    </div>
                    <div class="col-md-12" style="margin-top:20px;">
                      <label for="inputName5" class="form-label">Petugas</label>
                      <input name="petugas" id="" class="form-control" value="<?php echo $petugas ?>" disabled></input>
                    </div>
                  </div>
                </div>
                <div class="text-center" style="margin-top: -10px; margin-bottom: 40px;">
                  <?php if (isset($_GET['ubah'])) { ?>
                    <button type="submit" name="update" class="btn btn-success">Update</button>
                  <?php } else { ?>
                    <button type="submit" name="save" class="btn btn-primary">Simpan</button>
                  <?php } ?>
                  <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
    <div class="">
      <div class="row">
        <div class="col-md-12">
          <div class="card" style="margin-top:10px">
            <div class="card-body col-md-12">
              <h5 class="card-title">DATA CATATAN PERKEMBANGAN PENYAKIT TERINTEGRASI RAWAT INAP</h5>
              <!-- Modal Tag Teman Perawat -->
              <!-- <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#tagTeman">@ Tag Teman Perawat</button> -->
              <div class="modal fade" id="tagTeman" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="staticBackdropLabel">Tag Teman Perawat</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post">
                      <div class="modal-body">
                        <input type="text" name="id" id="id_id" hidden>
                        <label for="selectTemanPerawatCtt">Pilih Teman Perawat</label>
                        <select name="temanPerawat[]" class="form-control form-control-sm" id="selectTemanPerawatCtt" multiple="multiple" style="width: 100%;" required>
                          <?php
                          $getPerawat = $koneksi->query("SELECT * FROM admin WHERE level IN ('perawat', 'inap', 'igd') ORDER BY namalengkap ASC");
                          foreach ($getPerawat as $perawat) :
                          ?>
                            <option value="<?= $perawat['idadmin'] ?>"><?= $perawat['namalengkap'] ?> (<?= $perawat['level'] ?>)</option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="tagTeman" class="btn btn-sm btn-primary">Tag Teman Perawat</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <script>
                function upDataId(id) {
                  document.getElementById('id_id').value = id;
                }
              </script>
              <br>
              <!-- End Modal Tag Teman Perawat -->
              <div class="table-responsive">
                <table id="myTable" class="table table-striped" style="width:100%; font-size: 12px;">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Tgl&Jam</th>
                      <th>Subjek</th>
                      <th>Objek</th>
                      <th>Alergi</th>
                      <th>Assesment</th>
                      <th>Plan</th>
                      <th>Intruksi</th>
                      <th>Edukasi</th>
                      <th>Petugas</th>
                      <th>Dokter</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $no = 1;
                    $riw = $koneksi->query("SELECT * FROM ctt_penyakit_inap WHERE norm='$_GET[id]' GROUP BY tgl order by id DESC");
                    ?>
                    <?php foreach ($riw as $pecah) : ?>
                      <tr>
                        <td><?php echo $no; ?></td>
                        <td>
                          <?php echo $pecah["tgl"]; ?>
                          <?php
                          $getTagCount = $koneksi->query("SELECT * FROM ctt_penyakit_inap WHERE norm='$_GET[id]' AND tgl='$pecah[tgl]'");
                          foreach ($getTagCount as $tag) {
                            echo "<br><span class='badge bg-primary' style='font-size: 10px;'>@" . $tag['petugas'] . "</span>";
                          }
                          ?>
                        </td>
                        <td><?php echo $pecah["ctt_tedis"]; ?> </td>
                        <td><?php echo $pecah["object"]; ?> </td>
                        <td><?php echo $pecah["alergi"]; ?> </td>
                        <td><?php echo $pecah["assesment"]; ?> </td>
                        <td><?php echo $pecah["plan"]; ?> </td>
                        <td><?php echo $pecah["intruksi"]; ?> </td>
                        <td><?php echo $pecah["edukasi"]; ?> </td>
                        <td><?php echo $pecah["petugas"]; ?></td>
                        <td><?php echo $pecah["dokter"]; ?></td>
                        <td>
                          <span class="badge bg-primary my-1" style="font-size: 12px;" data-bs-toggle="modal" onclick="upDataId('<?= $pecah['id'] ?>')" data-bs-target="#tagTeman">@ Tag</span>
                          <?php if ($pecah['petugas'] === $petugas) { ?>
                            <a href="<?= getFullUrl(); ?>&idcct=<?= $pecah['id'] ?>&ubah" class="badge bg-success my-1" style="font-size: 12px;">Edit</a>
                            <a href="<?= getFullUrl(); ?>&ctt=<?= $pecah['id'] ?>#editorZone" class="badge bg-warning my-1" style="font-size: 12px;">Copy</a>
                            <a href="<?= getFullUrl(); ?>&ctt=<?= $pecah['id'] ?>&delete" onclick="return confirm('Teman teman yang anda tag pada catatan ini juga akan terhapus, apakah anda yakin ingin menghapus data ini ?')" class="badge bg-danger my-1" style="font-size: 12px;">Delete</a>
                          <?php } ?>
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
    </div>

  </main>
  <!-- End #main -->
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
</body>

</html>


<script type="text/javascript">
  $(document).ready(function() {
    $(".add-more").click(function() {
      var html = $(".copy").html();
      $(".after-add-more").after(html);
    });

    // saat tombol remove dklik control group akan dihapus 
    $("body").on("click", ".remove", function() {
      $(this).parents(".control-group").remove();
    });
  });
</script>

<script>
  var myModal = document.getElementById('myModal');
</script>

<script type="text/javascript">
  $(document).ready(function() {
    refreshTable();
  });

  function refreshTable() {
    $('#userList').load('rmedis.php', function() {
      setTimeout(refreshTable, 1000);
    });
  }
</script>


<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

<!-- Select2 CDN -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://cdn.ckeditor.com/ckeditor5/37.1.0/classic/ckeditor.js"></script>

<!-- Initialize Select2 -->
<script>
  $(document).ready(function() {
    $('#selectTemanPerawatCtt').select2({
      placeholder: "Pilih Teman Perawat",
      allowClear: true,
      width: '100%',
      dropdownParent: $('#tagTeman')
    });
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    ClassicEditor
      .create(document.querySelector('#editor'), {
        ckfinder: {
          uploadUrl: 'https://www.gkjwtunjungrejo.com/image-upload?_token=i99BxDXahocEmpYJ9vCLcLSTnfwaDPss37KbA71C',
        },
        toolbar: [
          'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'undo', 'redo'
        ]
      })
      .catch(error => {
        console.error(error);
      });
    ClassicEditor
      .create(document.querySelector('#editor2'), {
        ckfinder: {
          uploadUrl: 'https://www.gkjwtunjungrejo.com/image-upload?_token=i99BxDXahocEmpYJ9vCLcLSTnfwaDPss37KbA71C',
        },
        toolbar: [
          'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'undo', 'redo'
        ]
      })
      .catch(error => {
        console.error(error);
      });
    ClassicEditor
      .create(document.querySelector('#editor3'), {
        ckfinder: {
          uploadUrl: 'https://www.gkjwtunjungrejo.com/image-upload?_token=i99BxDXahocEmpYJ9vCLcLSTnfwaDPss37KbA71C',
        },
        toolbar: [
          'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'undo', 'redo'
        ]
      })
      .catch(error => {
        console.error(error);
      });
    ClassicEditor
      .create(document.querySelector('#editor4'), {
        ckfinder: {
          uploadUrl: 'https://www.gkjwtunjungrejo.com/image-upload?_token=i99BxDXahocEmpYJ9vCLcLSTnfwaDPss37KbA71C',
        },
        toolbar: [
          'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'undo', 'redo'
        ]
      })
      .catch(error => {
        console.error(error);
      });
    ClassicEditor
      .create(document.querySelector('#editor5'), {
        ckfinder: {
          uploadUrl: 'https://www.gkjwtunjungrejo.com/image-upload?_token=i99BxDXahocEmpYJ9vCLcLSTnfwaDPss37KbA71C',
        },
        toolbar: [
          'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'undo', 'redo'
        ]
      })
      .catch(error => {
        console.error(error);
      });
    ClassicEditor
      .create(document.querySelector('#editor6'), {
        ckfinder: {
          uploadUrl: 'https://www.gkjwtunjungrejo.com/image-upload?_token=i99BxDXahocEmpYJ9vCLcLSTnfwaDPss37KbA71C',
        },
        toolbar: [
          'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'undo', 'redo'
        ]
      })
      .catch(error => {
        console.error(error);
      });
  });
</script>
<?php
if (isset($_POST['update'])) {

  // $koneksi->query("UPDATE ctt_penyakit_inap SET tgl='$_POST[tgl]', norm='$_GET[id]', ctt_dokter='$_POST[ctt_dokter]', ctt_tedis='" . $koneksi->real_escape_string($_POST['ctt_tedis']) . "', petugas='$petugas', kamar='$_POST[kamar]', pasien='$_POST[pasien]' WHERE id = '$_GET[idcct]'");

  $koneksi->query("UPDATE ctt_penyakit_inap SET tgl='$_POST[tgl]', norm='$_GET[id]', ctt_dokter='$_POST[ctt_dokter]', ctt_tedis='" . $koneksi->real_escape_string($_POST['ctt_tedis']) . "', petugas='$petugas', kamar='$_POST[kamar]', pasien='$_POST[pasien]', ctt_penyakit_inap.object='" . $koneksi->real_escape_string($_POST['object']) . "', alergi='" . $koneksi->real_escape_string($_POST['alergi']) . "', assesment='" . $koneksi->real_escape_string($_POST['assesment']) . "', plan='" . $koneksi->real_escape_string($_POST['plan']) . "', intruksi='" . $koneksi->real_escape_string($_POST['intruksi']) . "', edukasi='" . $koneksi->real_escape_string($_POST['edukasi']) . "', dokter='" . $_SESSION['dokter_rawat'] . "' WHERE id = '$_GET[idcct]'");

  $koneksi->query("UPDATE registrasi_rawat SET kamar='$_POST[kamar]' WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]'");
  echo "
    <script>
      alert('Data berhasil diubah');
      document.location.href='index.php?halaman=cttpenyakit&id=$_GET[id]&tgl=$_GET[tgl]';
    </script>
  ";
}

if (isset($_POST['save'])) {
  $koneksi->query("INSERT INTO ctt_penyakit_inap(tgl, norm, ctt_dokter, ctt_tedis, petugas, kamar, pasien, ctt_penyakit_inap.object, alergi, assesment, plan, intruksi, edukasi, dokter, shift) VALUES ('$_POST[tgl]', '$_GET[id]','$_POST[ctt_dokter]', '" . $koneksi->real_escape_string($_POST['ctt_tedis']) . "', '$petugas', '$_POST[kamar]', '$_POST[pasien]', '" . $koneksi->real_escape_string($_POST['object']) . "', '" . $koneksi->real_escape_string($_POST['alergi']) . "', '" . $koneksi->real_escape_string($_POST['assesment']) . "', '" . $koneksi->real_escape_string($_POST['plan']) . "', '" . $koneksi->real_escape_string($_POST['intruksi']) . "', '" . $koneksi->real_escape_string($_POST['edukasi']) . "','" . $_SESSION['dokter_rawat'] . "', '" . $_SESSION['shift'] . "')");
  // $koneksi->query("UPDATE ctt_penyakit_inap SET tgl='$_POST[tgl]', norm='$_GET[id]', ctt_dokter='$_POST[ctt_dokter]', ctt_tedis='$_POST[ctt_tedis]', petugas='$petugas', kamar='$_POST[kamar]', pasien='$_POST[pasien]'");
  $koneksi->query("UPDATE registrasi_rawat SET kamar='$_POST[kamar]' WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]'");
  echo "
    <script>
      alert('Data berhasil ditambah');
      document.location.href='index.php?halaman=cttpenyakit&id=$_GET[id]&tgl=$_GET[tgl]';
    </script>
  ";
}




?>