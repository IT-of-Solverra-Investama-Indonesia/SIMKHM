<?php
$date = date("Y-m-d");
date_default_timezone_set('Asia/Jakarta');
$petugas = $_SESSION['admin']['namalengkap'];
$pasien = $koneksi->query("SELECT * FROM pasien  WHERE no_rm='$_GET[id]';")->fetch_assoc();
if (!isset($_GET['igd'])) {
  $jadwal = $koneksi->query("SELECT * FROM registrasi_rawat  WHERE no_rm='$_GET[id]' AND date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' ")->fetch_assoc();
}

$id = $koneksi->query("SELECT * FROM registrasi_rawat  WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' ")->fetch_assoc();

?>
<?php if (!isset($_GET['view'])) { ?>
  <main>
    <div class="">
      <div class="pagetitle">
        <h1>LAPORAN OBSERVASI PERAWAT</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="index.php?halaman=daftarrmedis" style="color:blue;">Rekam Medis</a></li>
            <li class="breadcrumb-item">Laporan Observasi Perawat</li>
          </ol>
        </nav>
      </div><!-- End Page Title -->
      <form class="row g-3" method="post" enctype="multipart/form-data">
        <div class="container">
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
                      <input type="text" class="form-control" id="inputName5" name="norm" value="<?php echo $pasien['no_rm'] ?>" placeholder="Masukkan Nama Pasien" readonly>
                    </div>
                    <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                      <label for="inputName5" class="form-label">Tanggal Lahir</label>
                      <input type="text" class="form-control" id="inputName5" name="tgl_lahir" value="<?php echo date("d-m-Y", strtotime($pasien['tgl_lahir'])) ?>" placeholder="Masukkan Nama Pasien" readonly>
                    </div>
                    <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                      <label for="inputName5" class="form-label">Alamat</label>
                      <input type="text" class="form-control" id="inputName5" name="alamat" value="<?php echo $pasien['alamat'] ?>" placeholder="Masukkan Nama Pasien" readonly>
                    </div>
                    <?php if (!isset($_GET['igd'])) { ?>
                      <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                        <label for="inputName5" class="form-label">Ruangan</label>
                        <input type="text" class="form-control" id="inputName5" name="kamar" value="<?php echo $jadwal['kamar'] ?>" placeholder="Masukkan Nama Pasien">
                      </div>
                    <?php } ?>
                    <?php if ($pasien["jenis_kelamin"] == 1) { ?>
                      <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                        <label for="inputName5" class="form-label">Jenis Kelamin</label>
                        <input type="text" class="form-control" id="inputName5" name="jenis_kelamin" value="Laki-laki" placeholder="Masukkan Nama Pasien" readonly>
                      </div>
                    <?php } else { ?>
                      <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                        <label for="inputName5" class="form-label">Jenis Kelamin</label>
                        <input type="text" class="form-control" id="inputName5" name="jenis_kelamin" value="Perempuan" placeholder="Masukkan Nama Pasien" readonly>
                      </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <br>
              <div class="card shadow p-3">
                <h5 class="card-title">OBSERVASI PERAWAT</h5>
                <div class="row">
                  <div class="col-md-6">
                    <label for="">Diagnosa</label>
                    <input type="text" class="form-control mb-3" name="diagnosa" id="" placeholder="Diagnosa">
                  </div>
                  <div class="col-md-6">
                    <label for="">Tanggal & Waktu</label>
                    <input type="datetime-local" class="form-control mb-3" name="tgl_waktu" id="" placeholder="Tanggal dan Waktu">
                  </div>
                  <div class="col-md-6">
                    <label for="">Tensi Darah</label>
                    <input type="text" class="form-control mb-3" name="tensi" id="" placeholder="Tensi Darah">
                  </div>
                  <div class="col-md-6">
                    <label for="">Suhu Tubuh</label>
                    <input type="text" class="form-control mb-3" name="suhu" id="" placeholder="Suhu Tubuh">
                  </div>
                  <div class="col-md-6">
                    <label for="">Cairan Ke</label>
                    <input type="text" class="form-control mb-3" name="cairan" id="" placeholder="Cairan Ke">
                  </div>
                  <div class="col-md-6">
                    <label for="">Volume Cairan</label>
                    <input type="text" class="form-control mb-3" name="volume" id="" placeholder="Volume Cairan">
                  </div>
                  <div class="col-md-6">
                    <label for="">Keadaan Umum</label>
                    <input type="text" class="form-control mb-3" name="keadaan_umum" id="" placeholder="Keadaan Umum">
                  </div>
                  <div class="col-md-6">
                    <label for="">Keluhan Pasien</label>
                    <textarea name="keluhan_pasien" id="" class="form-control mb-2" placeholder="Keluahan Pasien"></textarea>
                  </div>
                  <div class="col-md-6">
                    <label for="">Cairan Infus</label>
                    <input type="text" class="form-control mb-3" name="infus" id="" placeholder="Cairan Infus">
                  </div>
                  <div class="col-md-6">
                    <label for="">Tindakan</label>
                    <textarea name="tindakan" id="" class="form-control mb-2" placeholder="Tindakan"></textarea>
                  </div>
                  <div class="col-md-6">
                    <label for="">Perawat</label>
                    <input type="text" class="form-control mb-3" name="perawat" readonly value="<?= $petugas ?>" placeholder="">
                  </div>
                  <!-- <button class="btn btn-primary w-40" name="save">Simpan</button> -->
                </div>
                <div class="text-center" style="margin-top: 10px; margin-bottom: 40px;">
                  <button type="submit" name="save" class="btn btn-primary">Simpan Dulu</button>
                  <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
              </div>
              <script>
                function changeJenis(jenisObat) {
                  var jenis3 = document.getElementById('jenis3');
                  var jenis2 = document.getElementById('jenis2');
                  jenis3.value = jenisObat;
                  jenis2.value = jenisObat;
                }
              </script>

              <!-- Add Data Modal Obat -->
              <div class="modal fade" id="exampleModal4" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Obat</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="row">
                        <form method="post" enctype="multipart/form-data">
                          <div class="control-group after-add-more">
                            <!-- <div class="modal-body"> -->
                            <div class="row">
                              <div class="col-md-12">
                                <input hidden type="text" id="jenis" name="jenis" class="form-control">
                                <label for="inputName5" class="form-label">Nama Obat</label>
                                <!-- <input type="text" name="nama_obat" class="form-control" id="inputName5" placeholder="Layanan/Tindakan"> -->
                                <select name="nama_obat" onchange="updateCatatan()" id="nama_obat" class="form-select">
                                  <?php
                                  $getObat = $koneksi->query("SELECT * FROM apotek WHERE tipe = 'Ranap' ORDER BY nama_obat ASC");
                                  foreach ($getObat as $data) {
                                  ?>
                                    <option value="<?= $data['nama_obat'] ?>"><?= $data['nama_obat'] ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                              <script>
                                function updateCatatan() {
                                  var inputState = document.getElementById('nama_obat');
                                  var catatanInput = document.getElementById('catatan');
                                  var petunjukInput = document.getElementById('petunjuk');

                                  // Kalok Gitu Mending Tidak otomatis

                                  if (inputState.value === 'glibenclamid' || inputState.value === 'metformin') {
                                    catatanInput.value = 'Pagi, Siang, Malam';
                                  } else if (inputState.value === 'furosemid') {
                                    catatanInput.value = 'Pagi, Siang';
                                  } else if (inputState.value === 'Allupurinol 100' || inputState.value === 'Amlodipin 5 mg' || inputState.value === 'Amlodipin 10 mg') {
                                    catatanInput.value = 'Pagi, Malam';
                                  } else {
                                    catatanInput.value = '';
                                  }

                                  if (inputState.value === 'Antasida tab' || inputState.value === 'Omeprazol tab') {
                                    petunjukInput.value = 'Sebelum Makan';
                                  } else {
                                    petunjukInput.value = '';
                                  }
                                }

                                function updateJumlah() {
                                  var dosis1 = document.getElementById('dosis1_obat');
                                  var dosis2 = document.getElementById('dosis2_obat');
                                  var jml = document.getElementById('jml_obat');

                                  if (dosis1.value === '3' && dosis2.value === '1') {
                                    jml.value = 9;
                                  } else if (dosis1.value === '2' && dosis2.value === '1') {
                                    jml.value = 6;
                                  } else if (dosis1.value === '1' && dosis2.value === '1') {
                                    jml.value = 3;
                                  } else {
                                    jml.value = '';
                                  }

                                }
                              </script>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12" style="margin-top:20px; margin-bottom:20px;">
                              <label for="inputName5" class="form-label">Catatan Obat</label>
                              <input type="text" name="catatan_obat" class="form-control" id="catatan" placeholder="Masukkan Catatan Waktu">
                            </div>
                            <div class="col-md-12" style="margin-top:0px; margin-bottom:20px;">
                              <label for="inputName5" class="form-label">Jenis Obat</label>
                              <select name="jenis_obat" class="form-select">
                                <!-- <option value="Racik">Racik</option> -->
                                <option value="Jadi">Jadi</option>
                                <option value="Jual">Jual</option>
                              </select>
                            </div>
                            <label for="inputName5" class="form-label">Dosis</label>
                            <div class="col-md-6">
                              <div class="input-group mb-6">
                                <input oninput="updateJumlah()" type="text" class="form-control" id="dosis1_obat" name="dosis1_obat">
                                <input type="text" style="text-align: center;" class="form-control" placeholder="X">
                                <input oninput="updateJumlah()" type="text" class="form-control" id="dosis2_obat" name="dosis2_obat">
                              </div>
                            </div>

                            <div class="col-md-6">
                              <select id="inputState" name="per_obat" class="form-select">
                                <option>Per Hari</option>
                                <option>Per Jam</option>
                              </select>
                            </div>
                            <div class="col-md-12" style="margin-top:20px">
                              <label for="">Jumlah Obat</label>
                              <input type="number" name="jml_dokter" class="form-control" id="jml_obat" placeholder="jumlah obat">
                            </div>
                            <div class="col-md-12" style="margin-top:20px">
                              <label for="inputCity" class="form-label">Durasi</label>
                              <div class="input-group mb-3">
                                <input type="text" name="durasi_obat" class="form-control" placeholder="Durasi" aria-describedby="basic-addon2">
                                <span class="input-group-text" id="basic-addon2">Hari</span>
                              </div>
                            </div>
                            <div class="col-md-12" style="margin-top:10px">
                              <label for="inputName5" class="form-label">Petunjuk Pemakaian</label>
                              <input type="text" name="petunjuk_obat" class="form-control" id="petunjuk" placeholder="Masukkan Petunjuk Pemakaian">
                            </div>
                          </div>
                          <!-- <input type="hidden" name="id_pasien" value="<?php echo $pecah['idpasien'] ?>"> -->
                          <input type="hidden" name="idrm" value="<?php echo $id['idrawat'] ?>">

                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <input type="submit" class="btn btn-primary" name="saveobjadi" value="Save changes">
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- end -->

              <!-- Add Data Modal Obat -->
              <div class="modal  fade" role="dialog" id="exampleModal45" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Obat</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="row">
                        <form method="post" enctype="multipart/form-data">
                          <div class="control-group after-add-more2">
                            <!-- <div class="modal-body"> -->
                            <div class="row">
                              <div class="col-md-12">
                                <label for="inputName5" class="form-label">Nama Obat</label><br>
                                <!-- <input type="text" name="nama_obat" class="form-control" id="inputName5" placeholder="Layanan/Tindakan"> -->
                                <input hidden type="text" id="jenis3" name="jenis" class="form-control">

                                <select name="nama_obat[]" class="form-control w-100" style="width:100%;" id="selObat2" class="form-control">
                                  <option value="">Pilih</option>
                                  <?php

                                  $getObat = $koneksi->query("SELECT * FROM apotek WHERE tipe = 'Ranap' ORDER BY nama_obat ASC");

                                  foreach ($getObat as $data) {
                                  ?>
                                    <option value="<?= $data['nama_obat'] ?>"><?= $data['nama_obat'] ?></option>
                                  <?php } ?>
                                </select>
                              </div>
  
                              <script></script>
                              <div class="col-md-12" style="margin-top:20px">
                                <label for="">Jumlah Obat</label>
                                <input type="number" name="jml_dokter[]" class="form-control" id="inputName5" placeholder="jumlah obat">
                              </div>
                              <div class="col-md-12" style="margin-top:20px; margin-bottom:20px;">
                                <label for="inputName5" class="form-label">Catatan Interaksi Obat</label>
                                <input type="text" name="catatan_obat[]" class="form-control" id="inputName5" placeholder="Masukkan Jumlah">
                              </div>
                              <div class="col-md-12" style="margin-top:0px; margin-bottom:20px;">
                                <label for="inputName5" class="form-label">Jenis Obat</label>
                                <select name="jenis_obat[]" class="form-control">
                                  <option value="Jadi">Jadi</option>
                                  <!-- <option value="Jadi">Jadi</option> -->
                                </select>
                              </div>
                              <label for="inputName5" class="form-label">Dosis</label>
                              <div class="col-md-6">
                                <div class="input-group">
                                  <input type="text" class="form-control" id="dosis1_obat" name="dosis1_obat[]">
                                  <input type="text" style="text-align: center;" class="form-control" placeholder="X">
                                  <input type="text" class="form-control" id="dosis2_obat" name="dosis2_obat[]">
                                </div>
                              </div>
                              <div class="col-md-6">
                                <select id="inputState" name="per_obat[]" class="form-select">
                                  <option>Per Hari</option>
                                  <option>Per Jam</option>
                                </select>
                              </div>
                              <div class="col-md-12" style="margin-top:20px">
                                <label for="inputCity" class="form-label">Durasi</label>
                                <div class="input-group mb-3">
                                  <input type="text" name="durasi_obat[]" class="form-control" placeholder="Durasi" aria-describedby="basic-addon2">
                                  <span class="input-group-text" id="basic-addon2">Hari</span>
                                </div>
                              </div>
                              <div class="col-md-12" style="margin-top:10px">
                                <label for="inputName5" class="form-label">Petunjuk Pemakaian</label>
                                <input type="text" name="petunjuk_obat[]" class="form-control" id="inputName5" placeholder="Masukkan Petunjuk Pemakaian">
                              </div>
                            </div>
                            <hr>
                          </div>
                          <button class="btn btn-warning add-more2" type="button">
                            <i class="glyphicon glyphicon-plus"></i> Tambah Lagi
                          </button>
                          <hr>

                          <div class="copy2 invisible" style="display: none;">
                            <br>
                            <div class="control-group2">
                              <label for="inputName5" class="form-label">Nama Obat</label>
                              <!-- <input type="text" name="nama_obat" class="form-control" id="inputName5" placeholder="Layanan/Tindakan"> -->
                              <select name="nama_obat[]" class="form-control " id="selObat1" class="form-control">
                                <option value="">Pilih</option>


                                <?php
                                $getObat = $koneksi->query("SELECT * FROM apotek WHERE tipe = 'Ranap' ORDER BY nama_obat ASC");
                                foreach ($getObat as $data) {
                                ?>
                                  <option value="<?= $data['nama_obat'] ?>"><?= $data['nama_obat'] ?></option>
                                <?php } ?>
                              </select>

                              <div class="col-md-12" style="margin-top:20px">
                                <label for="">Jumlah Obat</label>
                                <input type="number" name="jml_dokter[]" class="form-control" id="inputName5" placeholder="jumlah obat">
                              </div>

                              <div class="col-md-12" style="margin-top:20px; margin-bottom:20px;">
                                <label for="inputName5" class="form-label">Catatan Interaksi Obat</label>
                                <input type="text" name="catatan_obat[]" class="form-control" id="inputName5" placeholder="Masukkan Jumlah">
                              </div>
                              <div class="col-md-12" style="margin-top:0px; margin-bottom:20px;">
                                <label for="inputName5" class="form-label">Jenis Obat</label>
                                <select name="jenis_obat[]" class="form-control">
                                  <option value="Jadi">Jadi</option>
                                  <!-- <option value="Jadi">Jadi</option> -->
                                </select>
                              </div>
                              <label for="inputName5" class="form-label">Dosis</label>
                              <div class="row">
                                <div class="col-md-6">
                                  <div class="input-group">
                                    <input type="text" class="form-control" id="dosis1_obat" name="dosis1_obat[]">
                                    <input type="text" style="text-align: center;" class="form-control" placeholder="X">
                                    <input type="text" class="form-control" id="dosis2_obat" name="dosis2_obat[]">
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <select id="inputState" name="per_obat[]" class="form-select">
                                    <option>Per Hari</option>
                                    <option>Per Jam</option>
                                  </select>
                                </div>
                              </div>
                              <div class="col-md-12" style="margin-top:20px">
                                <label for="inputCity" class="form-label">Durasi</label>
                                <div class="input-group mb-3">
                                  <input type="text" name="durasi_obat[]" class="form-control" placeholder="Durasi" aria-describedby="basic-addon2">
                                  <span class="input-group-text" id="basic-addon2">Hari</span>
                                </div>
                              </div>
                              <div class="col-md-12" style="margin-top:10px">
                                <label for="inputName5" class="form-label">Petunjuk Pemakaian</label>
                                <input type="text" name="petunjuk_obat[]" class="form-control" id="inputName5" placeholder="Masukkan Petunjuk Pemakaian">
                              </div>
                              <br>
                              <button class="btn btn-danger remove2" type="button"><i class="glyphicon glyphicon-remove"></i> Batal</button>
                              <hr>
                            </div>
                          </div>

                          <!-- <input type="hidden" name="id_pasien" value="<?php echo $pecah['idpasien'] ?>"> -->
                          <!-- <input type="text" name="idrm" value="<?php echo $id['idrawat'] ?>"> -->
                          <input type="hidden" class="form-control" id="inputName5" name="id" value="<?php echo $jadwal['idrawat'] ?>" placeholder="Masukkan Nama Pasien">
                          <input type="hidden" name="idrm" value="<?php echo $id['idrawat'] ?>">

                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <input type="submit" class="btn btn-primary" name="saveobnew" value="Save changes">
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- end -->

              <!-- Add Data Modal Obat -->
              <div class="modal  fade" role="dialog" id="exampleModal2" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Obat</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="row">
                        <form method="post" enctype="multipart/form-data">
                          <div class="control-group after-add-more">
                            <!-- <div class="modal-body"> -->
                            <div class="row">
                              <div class="col-md-12">
                                <input hidden type="text" id="jenis2" name="jenis" class="form-control">
                                <label for="inputName5" class="form-label">Nama Obat</label><br>
                                <!-- <input type="text" name="nama_obat" class="form-control" id="inputName5" placeholder="Layanan/Tindakan"> -->
                                <select name="nama_obat[]" class="form-control w-100" style="width:100%;" id="selObat1" aria-label="Default select example">
                                  <option value="">Pilih</option>
                                  <?php
                                  $getObat = $koneksi->query("SELECT * FROM apotek WHERE tipe = 'Ranap' ORDER BY nama_obat ASC");
                                  foreach ($getObat as $data) {
                                  ?>
                                    <option value="<?= $data['nama_obat'] ?>"><?= $data['nama_obat'] ?></option>
                                  <?php } ?>
                                </select>
                              </div>

                              <script></script>
                              <div class="col-md-12" style="margin-top:20px">
                                <label for="">Jumlah Obat</label>
                                <input type="number" name="jml_dokter[]" class="form-control" id="inputName5" placeholder="jumlah obat">
                              </div>
                            </div>
                          </div>
                          <button class="btn btn-warning add-more" type="button">
                            <i class="glyphicon glyphicon-plus"></i> Tambah Lagi
                          </button>
                          <hr>
                          <div class="copy invisible" style="display: none;">
                            <br>
                            <div class="control-group">
                              <label for="inputName5" class="form-label">Nama Obat</label>
                              <!-- <input type="text" name="nama_obat" class="form-control" id="inputName5" placeholder="Layanan/Tindakan"> -->
                              <select name="nama_obat[]" class="form-control " id="selObat1" aria-label="Default select example">
                                <option value="">Pilih</option>

                                <?php
                                $getObat = $koneksi->query("SELECT * FROM apotek WHERE tipe = 'Ranap' ORDER BY nama_obat ASC");
                                foreach ($getObat as $data) {
                                ?>
                                  <option value="<?= $data['nama_obat'] ?>"><?= $data['nama_obat'] ?></option>
                                <?php } ?>
                              </select>
                              <!-- <label for="inputName5" class="form-label">Kode Obat</label>
                        <select name="kode_obat[]" id="" class="form-select">
                            <?php
                            $getObat = $koneksi->query("SELECT * FROM apotek WHERE tipe = 'Ranap' ORDER BY nama_obat ASC");
                            foreach ($getObat as $data) {
                            ?>
                            <option value="<?= $data['id_obat'] ?>"><?= $data['nama_obat'] . " || " . $data['id_obat'] ?></option>
                            <?php } ?>
                        </select> -->
                              <div class="col-md-12" style="margin-top:20px">
                                <label for="">Jumlah Obat</label>
                                <input type="number" name="jml_dokter[]" class="form-control" id="inputName5" placeholder="jumlah obat">
                              </div>
                              <br>
                              <button class="btn btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i> Batal</button>
                              <hr>
                            </div>
                          </div>
                          <div class="col-md-12" style="margin-top:20px; margin-bottom:20px;">
                            <label for="inputName5" class="form-label">Catatan Interaksi Obat</label>
                            <input type="text" name="catatan_obat[]" class="form-control" id="inputName5" placeholder="Masukkan Jumlah">
                          </div>
                          <div class="col-md-12" style="margin-top:0px; margin-bottom:20px;">
                            <label for="inputName5" class="form-label">Jenis Obat</label>
                            <select name="jenis_obat[]" class="form-control">
                              <option value="Racik">Racik</option>
                              <!-- <option value="Jadi">Jadi</option> -->
                            </select>
                          </div>
                          <div class="col-md-12" style="margin-top:20px; margin-bottom:20px;">
                            <label for="inputName5" class="form-label">Racik Ke - </label>
                            <input type="number" name="racik[]" class="form-control" id="inputName5" placeholder="Masukkan racik">
                          </div>
                          <label for="inputName5" class="form-label">Dosis</label>
                          <div class="row">
                            <div class="col-md-6">
                              <div class="input-group mb-6">
                                <input type="text" class="form-control" id="dosis1_obat[]" name="dosis1_obat">
                                <input type="text" style="text-align: center;" class="form-control" placeholder="X">
                                <input type="text" class="form-control" id="dosis2_obat[]" name="dosis2_obat">
                              </div>
                            </div>
                            <div class="col-md-6">
                              <select id="inputState" name="per_obat[]" class="form-control">
                                <option>Per Hari</option>
                                <option>Per Jam</option>
                              </select>
                            </div>
                          </div>

                          <div class="col-md-12" style="margin-top:20px">
                            <label for="inputCity" class="form-label">Durasi</label>
                            <div class="input-group mb-3">
                              <input type="text" name="durasi_obat[]" class="form-control" placeholder="Durasi" aria-describedby="basic-addon2">
                              <span class="input-group-text" id="basic-addon2">Hari</span>
                            </div>
                          </div>
                          <div class="col-md-12" style="margin-top:10px">
                            <label for="inputName5" class="form-label">Petunjuk Pemakaian</label>
                            <input type="text" name="petunjuk_obat[]" class="form-control" id="inputName5" placeholder="Masukkan Petunjuk Pemakaian">
                          </div>
                          <!-- <input type="hidden" name="id_pasien" value="<?php echo $pecah['idpasien'] ?>"> -->
                          <input type="hidden" name="idrm" value="<?php echo $id['idrawat'] ?>">
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <input type="submit" class="btn btn-primary" name="saveob" value="Save changes">
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- end -->
              <script type="text/javascript">
                $(document).ready(function() {
                  // Initialize select2
                  $("#selUser").select2();
                  $(".form-select").select2({
                    dropdownParent: $('#exampleModal2')
                  });
                  $("form-select").select2({
                    dropdownParent: $('#exampleModal2')
                  });

                });
              </script>
              <script type="text/javascript">
                $(document).ready(function() {
                  // Initialize select2
                  $("#selUser2").select2();
                  $(".form-select").select2({
                    dropdownParent: $('#exampleModal45')
                  });
                  $(".form-select").select2({
                    dropdownParent: $('#exampleModal45')
                  });

                });
              </script>
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
              <script type="text/javascript">
                $(document).ready(function() {
                  $(".add-more2").click(function() {
                    var html = $(".copy2").html();
                    $(".after-add-more2").after(html);
                  });

                  // saat tombol remove dklik control group akan dihapus 
                  $("body").on("click", ".remove2", function() {
                    $(this).parents(".control-group2").remove();
                  });
                });
              </script>


              <div class="col-md-6">
                <label for="">Obat Injeksi</label>
                <!-- <textarea name="obat_injeksi" id="" class="form-control mb-2" placeholder="Obat Injeksi"></textarea> -->
                <div>
                  <button type="button" onclick="changeJenis('Injeksi')" class="btn btn-primary text-right" data-bs-toggle="modal" data-bs-target="#exampleModal45">Add Jadi</button>
                  <button type="button" onclick="changeJenis('Injeksi')" class="btn btn-primary text-right" data-bs-toggle="modal" data-bs-target="#exampleModal2">Add Racik</button>
                </div>
              </div><br>
              <div class="col-md-6">
                <label for="">Obat Oral</label>
                <!-- <textarea name="obat_oral" id="" class="form-control mb-2" placeholder="Obat Oral"></textarea> -->
                <div align="left">
                  <button type="button" onclick="changeJenis('Oral')" class="btn btn-primary text-right" data-bs-toggle="modal" data-bs-target="#exampleModal45">Add Jadi</button>
                  <button type="button" onclick="changeJenis('Oral')" class="btn btn-primary text-right" data-bs-toggle="modal" data-bs-target="#exampleModal2">Add Racik</button>
                </div>
              </div>
              <br>
              <div class="card shadow p-3">
                <h5 class="card-title">Riwayat Observasi</h5>
                <div class="row">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Pasien</th>
                        <th>Diagnosa</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if (isset($_GET['igd'])) {
                        $getlpo = $koneksi->query("SELECT * FROM lpo WHERE pasien = '$pasien[nama_lengkap]' AND norm = '$pasien[no_rm]' AND status = 'igd'");
                      } else {
                        $getlpo = $koneksi->query("SELECT * FROM lpo WHERE pasien = '$pasien[nama_lengkap]' AND norm = '$pasien[no_rm]' AND status = 'inap'");
                      }
                      ?>
                      <?php foreach ($getlpo as $data) { ?>
                        <?php
                        $datetimeString = "$data[tgl_waktu]";

                        // Buat objek DateTime dari string datetime
                        $datetimeObject = date_create($datetimeString);

                        // Dapatkan tanggal dari objek DateTime
                        $tanggal = date_format($datetimeObject, "Y-m-d");
                        $jam = date_format($datetimeObject, "H:i:s");
                        ?>
                        <tr>
                          <td><?= $data['pasien'] ?></td>
                          <td><?= $data['diagnosa'] ?></td>
                          <td><?= $tanggal ?></td>
                          <td><?= $jam ?></td>
                          <td>
                            <?php if (!isset($_GET['igd'])) { ?>
                              <a class="btn btn-sm btn-primary" href="index.php?halaman=lpo&id=<?= $_GET['id'] ?>&inap&tgl=<?= $_GET['tgl'] ?>&view=<?= $data['id_lpo'] ?>"><i class="bi bi-eye"></i></a>
                            <?php } else { ?>
                              <a class="btn btn-sm btn-primary" href="index.php?halaman=lpo&id=<?= $_GET['id'] ?>&igd&view=<?= $data['id_lpo'] ?>&idigd=<?= $_GET['idigd'] ?>"><i class="bi bi-eye"></i></a>
                            <?php } ?>
                          </td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        </from>
    </div>
  </main>
  <?php
  if (isset($_POST['save'])) {
    if (isset($_GET['igd'])) {

      $koneksi->query("INSERT INTO lpo (pasien, norm, tgl_lahir, alamat, kamar, jenis_kelamin, diagnosa, tgl_waktu, tensi, suhu, cairan, volume, keadaan_umum, keluhan_pasien, infus, obat_injeksi, obat_oral, tindakan, perawat, status, idigd) VALUES ('$_POST[pasien]', '$_POST[norm]', '$_POST[tgl_lahir]', '$_POST[alamat]', '$_POST[kamar]', '$_POST[jenis_kelamin]', '$_POST[diagnosa]', '$_POST[tgl_waktu]', '$_POST[tensi]', '$_POST[suhu]', '$_POST[cairan]', '$_POST[volume]', '$_POST[keadaan_umum]', '$_POST[keluhan_pasien]', '$_POST[infus]', '$_POST[obat_injeksi]', '$_POST[obat_oral]', '$_POST[tindakan]', '$_POST[perawat]', 'igd', '$_GET[idigd]')");
    } else {
      $koneksi->query("INSERT INTO lpo (pasien, norm, tgl_lahir, alamat, kamar, jenis_kelamin, diagnosa, tgl_waktu, tensi, suhu, cairan, volume, keadaan_umum, keluhan_pasien, infus, obat_injeksi, obat_oral, tindakan, perawat, status) VALUES ('$_POST[pasien]', '$_POST[norm]', '$_POST[tgl_lahir]', '$_POST[alamat]', '$_POST[kamar]', '$_POST[jenis_kelamin]', '$_POST[diagnosa]', '$_POST[tgl_waktu]', '$_POST[tensi]', '$_POST[suhu]', '$_POST[cairan]', '$_POST[volume]', '$_POST[keadaan_umum]', '$_POST[keluhan_pasien]', '$_POST[infus]', '$_POST[obat_injeksi]', '$_POST[obat_oral]', '$_POST[tindakan]', '$_POST[perawat]', 'inap')");
    }
    if (isset($_GET['igd'])) {
      echo "
                <script>
                alert('Yey Berhasil!');
                document.location.href='index.php?halaman=lpo&id=$_GET[id]&idigd=$_GET[idigd]&tgl=$_GET[tgl]&igd';
                </script>
                ";
    } else {
      echo "
                <script>
                alert('Yey Berhasil!');
                document.location.href='index.php?halaman=lpo&id=$_GET[id]&inap&tgl=$_GET[tgl]';
                </script>
                ";
    }
  }

  if (isset($_POST['saveob'])) {
    $catatan_obat = $_POST['catatan_obat'];
    $nama = $_POST['nama_obat'];
    $jml_dokter = $_POST['jml_dokter'];
    $dosis1_obat = $_POST['dosis1_obat'];
    $dosis2_obat = $_POST['dosis2_obat'];
    $per_obat = $_POST['per_obat'];
    $durasi_obat = $_POST['durasi_obat'];
    $petunjuk_obat = $_POST['petunjuk_obat'];
    $end = date("H:i:s");

    $koneksi->query("UPDATE registrasi_rawat SET end='$end' WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]';");
    $cekPemOb = $koneksi->query("SELECT * FROM registrasi_rawat WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' limit 1")->fetch_assoc();
    $koneksi->query("UPDATE biaya_rawat SET poli = '35000' WHERE idregis='$cekPemOb[idrawat]'");

    for ($i = 0; $i < count($nama) - 1; $i++) {
      foreach ($_POST['catatan_obat'] as $catatan_obat) {
        foreach ($_POST['per_obat'] as $per_obat) {
          foreach ($_POST['durasi_obat'] as $durasi_obat) {
            foreach ($_POST['petunjuk_obat'] as $petunjuk_obat) {
              foreach ($_POST['jenis_obat'] as $jenis_obat) {
                foreach ($_POST['racik'] as $racik) {
                  $ObatKode = $koneksi->query("SELECT id_obat, jml_obat, margininap, harga_beli FROM apotek WHERE tipe = 'Ranap' AND nama_obat= '" . $nama[$i] . "'")->fetch_assoc();
                  $stokAkhir = $ObatKode['jml_obat'] - $jml_dokter[$i];
                  $m = $ObatKode['margininap'];
                  if ($m < 100) {
                    $margin = 1.30;
                  } else {
                    $margin = $m / 100;
                  }
                  $subtotal = 0;
                  $harga = $ObatKode['harga_beli'] * $margin * $jml_dokter[$i];

                  date_default_timezone_set('Asia/Jakarta');
                  $tanggal = date('Y-m-d');
                  $biaya = 'biayaobat igd';
                  $id = $_POST["idrm"];
                  $resep = 'Resep' . ' ' . $id;

                  $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran, ket, petugas ) VALUES ('$_POST[idrm]', '$tanggal', '$biaya', '$harga', '$resep', '$petugas') ");

                  // $subtotal += $harga;

                  $koneksi->query("UPDATE apotek SET jml_obat = '$stokAkhir' WHERE id_obat = '$ObatKode[id_obat]'");

                  $koneksi->query("INSERT INTO obat_rm SET catatan_obat = '$catatan_obat', nama_obat = '$nama[$i]', kode_obat = '$ObatKode[id_obat]', jml_dokter = '$jml_dokter[$i]', dosis1_obat = '$dosis1_obat', dosis2_obat = '$dosis2_obat', per_obat = '$per_obat', durasi_obat = '$durasi_obat', petunjuk_obat = '$petunjuk_obat', jenis_obat = '$jenis_obat', idigd = '$_GET[idigd]', tgl_pasien = '$_GET[tgl]', obat_igd = '$_POST[jenis]', racik = '$racik', idrm = '$_GET[id]'");
                }
              }
            }
          }
        }
      }
    }

    if (isset($_GET['igd'])) {
      echo "
              <script>
                document.location.href='index.php?halaman=lpo&id=$_GET[id]&idigd=$_GET[idigd]&tgl=$_GET[tgl]&igd';
              </script>
            ";
    } else {
      echo "
              <script>
                document.location.href='index.php?halaman=lpo&id=$_GET[id]&inap&tgl=$_GET[tgl]';
              </script>
            ";
    }
  }

  if (isset($_POST['saveobnew'])) {
    $catatan_obat = $_POST['catatan_obat'];
    $nama = $_POST['nama_obat'];
    $jml_dokter = $_POST['jml_dokter'];
    $dosis1_obat = $_POST['dosis1_obat'];
    $dosis2_obat = $_POST['dosis2_obat'];
    $per_obat = $_POST['per_obat'];
    $durasi_obat = $_POST['durasi_obat'];
    $jenis_obat = $_POST['jenis_obat'];
    $petunjuk_obat = $_POST['petunjuk_obat'];
    $idrm = $_POST['idrm'];

    $end = date("H:i:s");
    for ($i = 0; $i < count($nama) - 1; $i++) {
      $ObatKode = $koneksi->query("SELECT id_obat, jml_obat, margininap, harga_beli FROM apotek WHERE tipe = 'Ranap' AND nama_obat= '" . $nama[$i] . "'")->fetch_assoc();
      $stokAkhir = $ObatKode['jml_obat'] - $jml_dokter[$i];
      $m = $ObatKode['margininap'];
      if ($m < 100) {
        $margin = 1.30;
      } else {
        $margin = $m / 100;
      }
      $subtotal = 0;
      $harga = $ObatKode['harga_beli'] * $margin * $jml_dokter[$i];
      $subtotal += $harga;
      date_default_timezone_set('Asia/Jakarta');
      $tanggal = date('Y-m-d');
      $biaya = 'biayaobat igd';
      $id = $_POST["idrm"];
      $resep = 'Resep' . ' ' . $id;

      $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran, ket, petugas ) VALUES ('$_POST[idrm]', '$tanggal', '$biaya', '$harga', '$resep', '$petugas') ");
      $koneksi->query("INSERT INTO obat_rm SET catatan_obat = '$catatan_obat[$i]', nama_obat = '$nama[$i]', kode_obat = '$ObatKode[id_obat]', jml_dokter = '$jml_dokter[$i]', dosis1_obat = '$dosis1_obat[$i]', dosis2_obat = '$dosis2_obat[$i]', per_obat = '$per_obat[$i]', durasi_obat = '$durasi_obat[$i]', tgl_pasien = '$_GET[tgl]', petunjuk_obat = '$petunjuk_obat[$i]', jenis_obat = '$jenis_obat[$i]', idigd = '$_GET[idigd]', obat_igd = '$_POST[jenis]', idrm = '$_GET[id]'");
    }

    if (isset($_GET['igd'])) {
      echo "
          <script>
            document.location.href='index.php?halaman=lpo&id=$_GET[id]&idigd=$_GET[idigd]&tgl=$_GET[tgl]&igd';
          </script>
        ";
    } else {
      echo "
          <script>
            document.location.href='index.php?halaman=lpo&id=$_GET[id]&inap&tgl=$_GET[tgl]';
          </script>
        ";
    }
  }
  ?>
<?php } else { ?>
  <?php $lpo = $koneksi->query("SELECT * FROM lpo WHERE id_lpo = '$_GET[view]'")->fetch_assoc(); ?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>KHM WONOREJO</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script rel="javascript" type="text/javascript" href="js/jquery-1.11.3.min.js"></script>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>


  </head>

  <body>
    <main>
      <div class="container">
        <div class="pagetitle">
          <h1>LAPORAN OBSERVASI PERAWAT</h1>
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item active"><a href="index.php?halaman=daftarrmedis" style="color:blue;">Rekam Medis</a></li>
              <li class="breadcrumb-item">Laporan Observasi Perawat</li>
            </ol>
          </nav>
        </div><!-- End Page Title -->
        <form class="row g-3" method="post" enctype="multipart/form-data">
          <div class="container">
            <div class="row">
              <div class="col-md-12">
                <?php if (isset($_GET['igd'])) { ?>
                  <a href="index.php?halaman=lpo&igd&id=<?= $_GET['id'] ?>&inap" class="btn btn-sm btn-dark">Kembali</a>
                <?php } else { ?>
                  <a href="index.php?halaman=lpo&id=<?= $_GET['id'] ?>&inap&tgl=<?= $_GET['tgl'] ?>" class="btn btn-sm btn-dark">Kembali</a>
                <?php } ?>
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
                        <input type="text" class="form-control" id="inputName5" name="norm" value="<?php echo $pasien['no_rm'] ?>" placeholder="Masukkan Nama Pasien" readonly>
                      </div>
                      <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                        <label for="inputName5" class="form-label">Tanggal Lahir</label>
                        <input type="text" class="form-control" id="inputName5" name="tgl_lahir" value="<?php echo date("d-m-Y", strtotime($pasien['tgl_lahir'])) ?>" placeholder="Masukkan Nama Pasien" readonly>
                      </div>
                      <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                        <label for="inputName5" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="inputName5" name="alamat" value="<?php echo $pasien['alamat'] ?>" placeholder="Masukkan Nama Pasien" readonly>
                      </div>
                      <?php if (!isset($_GET['igd'])) { ?>
                        <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                          <label for="inputName5" class="form-label">Ruangan</label>
                          <input type="text" class="form-control" id="inputName5" name="kamar" value="<?php echo $jadwal['kamar'] ?>" placeholder="Masukkan Nama Pasien">
                        </div>
                      <?php } ?>
                      <?php if ($pasien["jenis_kelamin"] == 1) { ?>
                        <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                          <label for="inputName5" class="form-label">Jenis Kelamin</label>
                          <input type="text" class="form-control" id="inputName5" name="jenis_kelamin" value="Laki-laki" placeholder="Masukkan Nama Pasien" readonly>
                        </div>
                      <?php } else { ?>
                        <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                          <label for="inputName5" class="form-label">Jenis Kelamin</label>
                          <input type="text" class="form-control" id="inputName5" name="jenis_kelamin" value="Perempuan" placeholder="Masukkan Nama Pasien" readonly>
                        </div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
                <br>
                <div class="card shadow p-3">
                  <h5 class="card-title">OBSERVASI PERAWAT</h5>
                  <div class="row">
                    <div class="col-md-6">
                      <label for="">Diagnosa</label>
                      <input type="text" class="form-control mb-3" readonly value="<?= $lpo['diagnosa'] ?>" name="diagnosa" id="" placeholder="Diagnosa">
                    </div>
                    <div class="col-md-6">
                      <label for="">Tanggal & Waktu</label>
                      <input type="datetime-local" readonly value="<?= $lpo['tgl_waktu'] ?>" class="form-control mb-3" name="tgl_waktu" id="" placeholder="Tanggal dan Waktu">
                    </div>
                    <div class="col-md-6">
                      <label for="">Tensi Darah</label>
                      <input type="text" class="form-control mb-3" readonly value="<?= $lpo['tensi'] ?>" name="tensi" id="" placeholder="Tensi Darah">
                    </div>
                    <div class="col-md-6">
                      <label for="">Suhu Tubuh</label>
                      <input type="text" class="form-control mb-3" readonly value="<?= $lpo['suhu'] ?>" name="suhu" id="" placeholder="Suhu Tubuh">
                    </div>
                    <div class="col-md-6">
                      <label for="">Cairan Ke</label>
                      <input type="text" class="form-control mb-3" readonly value="<?= $lpo['cairan'] ?>" name="cairan" id="" placeholder="Cairan Ke">
                    </div>
                    <div class="col-md-6">
                      <label for="">Volume Cairan</label>
                      <input type="text" class="form-control mb-3" readonly value="<?= $lpo['volume'] ?>" name="volume" id="" placeholder="Volume Cairan">
                    </div>
                    <div class="col-md-6">
                      <label for="">Keadaan Umum</label>
                      <input type="text" class="form-control mb-3" readonly value="<?= $lpo['keadaan_umum'] ?>" name="keadaan_umum" id="" placeholder="Keadaan Umum">
                    </div>
                    <div class="col-md-6">
                      <label for="">Keluhan Pasien</label>
                      <textarea readonly name="keluhan_pasien" id="" class="form-control mb-2" placeholder="Keluahan Pasien"><?= $lpo['keluhan_pasien'] ?></textarea>
                    </div>
                    <div class="col-md-6">
                      <label for="">Cairan Infus</label>
                      <input type="text" class="form-control mb-3" readonly value="<?= $lpo['infus'] ?>" name="infus" id="" placeholder="Cairan Infus">
                    </div>
                    <!-- <div class="col-md-6">
                                            <label for="">Obat Injeksi</label>
                                            <textarea readonly name="obat_injeksi" id="" class="form-control mb-2" placeholder="Obat Injeksi"><?= $lpo['obat_injeksi'] ?></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="">Obat Oral</label>
                                            <textarea readonly name="obat_oral" id="" class="form-control mb-2" placeholder="Obat Oral"><?= $lpo['obat_oral'] ?></textarea>
                                        </div> -->
                    <div class="col-md-6">
                      <label for="">Tindakan</label>
                      <textarea readonly name="tindakan" id="" class="form-control mb-2" placeholder="Tindakan"><?= $lpo['tindakan'] ?></textarea>
                    </div>
                    <div class="col-md-6">
                      <label for="">Perawat</label>
                      <input type="text" class="form-control mb-3" readonly value="<?= $lpo['perawat'] ?>" name="perawat" readonly value="<?= $petugas ?>" placeholder="">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card shadow p-3">
            <h5 class="card-title">Obat Oral</h5>

            <?php if (isset($_GET['igd'])) {

              $oral = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND idigd='$_GET[idigd]' AND obat_igd = 'oral'");
            } else {
              $oral = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND tgl_pasien='$_GET[tgl]' AND obat_igd = 'oral'");
            } ?>


            <br>
            <div id="employee_table">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th width="5%">No.</th>
                    <th width="50%">Obat</th>
                    <th width="50%">Kode Obat</th>
                    <th width="50%">Jumlah Obat</th>
                    <th width="20%">Dosis</th>
                    <th width="20%">Jenis</th>
                    <th width="20%">Durasi</th>
                    <!-- <th width="20%"></th> -->
                  </tr>
                </thead>
                <tbody>

                  <?php $no = 1 ?>

                  <?php foreach ($oral as $obat) : ?>

                    <tr>
                      <td><?php echo $no; ?></td>
                      <td style="margin-top:10px;"><?php echo $obat["nama_obat"]; ?></td>
                      <td style="margin-top:10px;"><?php echo $obat["kode_obat"]; ?></td>
                      <td style="margin-top:10px;"><?php echo $obat["jml_dokter"]; ?></td>
                      <td style="margin-top:10px;"><?php echo $obat["dosis1_obat"]; ?> X <?php echo $obat["dosis2_obat"]; ?> <?php echo $obat["per_obat"]; ?></td>
                      <td style="margin-top:10px;"><?php echo $obat["jenis_obat"]; ?> <?php echo $obat["racik"]; ?></td>
                      <td style="margin-top:10px;"><?php echo $obat["durasi_obat"]; ?> hari</td>
                      <!-- <td style="margin-top:10px;"> <button type="button" class="btn btn-primary text-right" data-bs-toggle="modal" data-bs-target="#exampleModalEdit<?php echo $obat["idobat"]; ?>">Edit</button></td> -->
                    </tr>
                  <?php endforeach ?>
              </table>
            </div>

            <h5 class="card-title">Obat Injeksi</h5>

            <?php if (isset($_GET['igd'])) {

              $injek = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND idigd='$_GET[idigd]' AND obat_igd = 'injeksi'");
            } else {
              $injek = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND tgl_pasien='$_GET[tgl]' AND obat_igd = 'injeksi'");
            }

            ?>
            <br>
            <div id="employee_table">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th width="5%">No.</th>
                    <th width="50%">Obat</th>
                    <th width="50%">Kode Obat</th>
                    <th width="50%">Jumlah Obat</th>
                    <th width="20%">Dosis</th>
                    <th width="20%">Jenis</th>
                    <th width="20%">Durasi</th>
                    <!-- <th width="20%"></th> -->
                  </tr>
                </thead>
                <tbody>

                  <?php $no = 1 ?>

                  <?php foreach ($injek as $obat) : ?>

                    <tr>
                      <td><?php echo $no; ?></td>
                      <td style="margin-top:10px;"><?php echo $obat["nama_obat"]; ?></td>
                      <td style="margin-top:10px;"><?php echo $obat["kode_obat"]; ?></td>
                      <td style="margin-top:10px;"><?php echo $obat["jml_dokter"]; ?></td>
                      <td style="margin-top:10px;"><?php echo $obat["dosis1_obat"]; ?> X <?php echo $obat["dosis2_obat"]; ?> <?php echo $obat["per_obat"]; ?></td>
                      <td style="margin-top:10px;"><?php echo $obat["jenis_obat"]; ?> <?php echo $obat["racik"]; ?></td>
                      <td style="margin-top:10px;"><?php echo $obat["durasi_obat"]; ?> hari</td>
                      <!-- <td style="margin-top:10px;"> <button type="button" class="btn btn-primary text-right" data-bs-toggle="modal" data-bs-target="#exampleModalEdit<?php echo $obat["idobat"]; ?>">Edit</button></td> -->
                    </tr>
                  <?php endforeach ?>
              </table>
            </div>
            </from>
            <?php
            if (isset($_POST['save'])) {
              $koneksi->query("INSERT INTO lpo (pasien, norm, tgl_lahir, alamat, kamar, jenis_kelamin, diagnosa, tgl_waktu, tensi, suhu, cairan, volume, keadaan_umum, keluhan_pasien, infus, obat_injeksi, obat_oral, tindakan, perawat) VALUES ('$_POST[pasien]', '$_POST[norm]', '$_POST[tgl_lahir]', '$_POST[alamat]', '$_POST[kamar]', '$_POST[jenis_kelamin]', '$_POST[diagnosa]', '$_POST[tgl_waktu]', '$_POST[tensi]', '$_POST[suhu]', '$_POST[cairan]', '$_POST[volume]', '$_POST[keadaan_umum]', '$_POST[keluhan_pasien]', '$_POST[infus]', '$_POST[obat_injeksi]', '$_POST[obat_oral]', '$_POST[tindakan]', 'perawat')");

              echo "
                            <script>
                                alert('BERHASIL MENAMBAHKAN OBSERVASI');
                                document.location.href='index.php?halaman=lpo&id=$_GET[id]&inap&tgl=$_GET[tgl]';
                            </script>
                        ";
            }
            ?>
          </div>
    </main>
  </body>

  </html>
<?php } ?>