<?php
error_reporting(0);
$date = date("Y-m-d");
date_default_timezone_set('Asia/Jakarta');

$username = $_SESSION['admin']['username'];
$ambil = $koneksi->query("SELECT * FROM admin  WHERE username='$username';");

if (isset($_GET['inap'])) {
  $pasien = $koneksi->query("SELECT * FROM kajian_awal_inap INNER JOIN pasien  WHERE norm='$_GET[id]' ORDER BY id_rm DESC LIMIT 1;");
  $pecah = $pasien->fetch_assoc();
  $suhu = $pecah['suhu'];
  $jadwal = $koneksi->query("SELECT * FROM registrasi_rawat  WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' ORDER BY idrawat DESC LIMIT 1")->fetch_assoc();
  $lab = $koneksi->query("SELECT * FROM lab_hasil JOIN daftartes WHERE id_inap='$jadwal[idrawat]' AND nama_periksa=nama_tes ORDER BY idhasil");
} else {
  $pasien = $koneksi->query("SELECT * FROM kajian_awal WHERE norm='$_GET[id]' AND tgl_rm = '$_GET[tgl]' ORDER BY id_rm DESC LIMIT 1;");
  $pecah = $pasien->fetch_assoc();
  $suhu = $pecah['suhu_tubuh'];
  $jadwal = $koneksi->query("SELECT * FROM registrasi_rawat  WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' ORDER BY idrawat DESC LIMIT 1")->fetch_assoc();
  $lab = $koneksi->query("SELECT * FROM lab_hasil JOIN daftartes WHERE id_lab_h='$jadwal[idrawat]' AND nama_periksa=nama_tes");
}

$pas = $koneksi->query("SELECT * FROM pasien WHERE TRIM(no_rm) = '$_GET[id]' ORDER BY idpasien DESC LIMIT 1 ")->fetch_assoc();
$rm = $koneksi->query("SELECT * FROM rekam_medis WHERE rekam_medis.norm='$_GET[id]' AND DATE_FORMAT(jadwal, '%Y-%m-%d') = '$_GET[tgl]';")->fetch_assoc();

if ($pas['jenis_kelamin'] == '1') {
  $jk = 'Laki-Laki';
} elseif ($pas['jenis_kelamin'] == '2') {
  $jk = 'Perempuan';
}
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Ganti 'targetID' dengan ID div tujuan Anda
    const targetElement = document.getElementById('tandaTandaVital');

    if (targetElement) {
      targetElement.scrollIntoView();

      // Opsional: Untuk animasi scroll halus
      // targetElement.scrollIntoView({ behavior: 'smooth' });
    }
  });
</script>
<main>
  <div class="">
    <div class="pagetitle mb-0">
      <h1>Rekam Medis</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active"><a href="index.php?halaman=daftarrmedis" style="color:blue;">Rekam Medis</a></li>
          <li class="breadcrumb-item">Buat Rekam Medis</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    <!-- <?= $start ?> -->
    <form class="row g-3" method="post" enctype="multipart/form-data">
      <div class="">
        <div class="row">
          <div class="col-md-12">
            <div class="card" style="margin-top:10px">
              <div class="card-body col-md-12">
                <h5 class="card-title"><?php echo $jadwal['nama_pasien'] ?> (<?php echo $jadwal['no_rm'] ?>) | Lahir: <?php echo $pas['tgl_lahir'] ?> | JK: <?php echo $jk ?> <br> BPJS: <?php echo $pas['no_bpjs'] ?? '-' ?> | Jadwal: <?php echo $jadwal['jadwal'] ?> | Pembiayaan: <?php echo $pas['pembiayaan'] ?></h5>
                <div class="col-md-12" style="visibility: hidden; height: 0.1px; margin: 0;">
                  <label for="inputName5" class="form-label">Nama Pasien</label>
                  <input type="text" class="form-control form-control-sm" name="nama_pasien" id="inputName5" value="<?php echo $jadwal['nama_pasien'] ?>" placeholder="Masukkan Nama Pasien">
                </div>
                <div class="col-md-12" style="visibility: hidden; height: 0.1px; margin: 0;">
                  <label for="inputName5" class="form-label">Nomor BPJS</label>
                  <input type="text" class="form-control form-control-sm" name="no_bpjs" id="inputName5" value="<?php echo $pas['no_bpjs'] ?? '-' ?>" placeholder="Masukkan No BPJS Pasien">
                </div>
                <div class="col-md-12" style="visibility: hidden; height: 0.1px; margin: 0;">
                  <label for="inputName5" class="form-label">Jadwal</label>
                  <input type="datetime-local" class="form-control form-control-sm" id="inputName5" name="jadwal" value="<?php echo $jadwal['jadwal'] ?>" placeholder="Masukkan Nama Pasien">
                  <input type="hidden" class="form-control form-control-sm" id="inputName5" name="dokter" value="<?php echo $jadwal['dokter_rawat'] ?>" placeholder="Masukkan Nama Pasien">
                  <input type="hidden" class="form-control form-control-sm" id="inputName5" name="id" value="<?php echo $jadwal['idrawat'] ?>" placeholder="Masukkan Nama Pasien">
                </div>
              </div>
            </div>
            <div class="card mb-0" style="visibility: hidden; height: 0.1px; margin: 0;">
              <div class="card-body">
                <!-- <h5 class="card-title">Subjective</h5> -->
                <hr style="margin-bottom:2px">
                <h6 class="card-title">Data Umum</h6>
                <div class="row">
                  <div class="col-md-6" style="visibility: hidden; height: 0.1px; margin: 0;">
                    <label for="inputName5" class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control form-control-sm" name="tgl_lahir" value="<?php echo $pas['tgl_lahir'] ?>" id="inputName5" placeholder="Masukkan Tanggal Pasien">
                  </div>
                  <div class="col-md-6" style="visibility: hidden; height: 0.1px; margin: 0;">
                    <label for="inputName5" class="form-label">Jenis Kelamin</label>
                    <input type="text" class="form-control form-control-sm" name="jenis_kelamin" value="<?php echo $jk ?>" id="inputName5" placeholder="Masukkan JK Pasien">
                  </div>
                  <div class="col-md-6" style="visibility: hidden; height: 0.1px; margin: 0;">
                    <label for="inputName5" class="form-label">Pembiayaan</label>
                    <input type="text" class="form-control form-control-sm" name="pembiayaan" id="inputName5" value="<?php echo $pas['pembiayaan'] ?>" placeholder="Masukkan Pembiayaan Pasien">
                  </div>
                </div>
              </div>
            </div>
            <!-- <a href="" class="btn btn-sm btn-primary mb-2">Copy From Another Medical Record</a> -->
            <?php if (!isset($_GET['entriObat'])) { ?>
              <div class="row g-2 mb-2" style="font-size: 12px;" id="tandaTandaVital">
                <div class="col-4">
                  <div class="card h-100 pt-2">
                    <div class="card-body">
                      <div class="row">
                        <div>
                          <div class="row g-1">
                            <div>
                              <h6 class="mt-2"><b>Tanda-Tanda Vital</b></h6>
                            </div>
                            <div class="col-md-4">
                              <label for="inputCity" class="form-label mb-0 mt-2">Suhu</label>
                              <input type="text" class="form-control form-control-sm" placeholder="C" name="suhu_tubuh" aria-describedby="basic-addon2" value="<?php echo $suhu ?>">
                            </div>
                            <div class="col-md-4">
                              <label for="inputCity" class="form-label mb-0 mt-2">Oksigen</label>
                              <input type="text" class="form-control form-control-sm" placeholder="%" name="oksigen" aria-describedby="basic-addon2" value="<?php echo $pecah['oksigen'] ?>">
                            </div>
                            <div class="col-md-4">
                              <label for="inputCity" class="form-label mb-0 mt-2">Sistole</label>
                              <input type="text" class="form-control form-control-sm" placeholder="mmHg" name="sistole" aria-describedby="basic-addon2" value="<?php echo $pecah['sistole'] ?>">
                            </div>
                            <div class="col-md-4">
                              <label for="inputCity" class="form-label mb-0 mt-2">Distole</label>
                              <input type="text" class="form-control form-control-sm" placeholder="mmHg" name="distole" aria-describedby="basic-addon2" value="<?php echo $pecah['distole'] ?>">
                            </div>
                            <div class="col-md-4">
                              <label for="inputCity" class="form-label mb-0 mt-2">Nadi</label>
                              <input type="text" class="form-control form-control-sm" placeholder="Kali/Menit" name="nadi" aria-describedby="basic-addon2" value="<?php echo $pecah['nadi'] ?>">
                            </div>
                            <div class="col-md-4">
                              <label for="inputCity" class="form-label mb-0 mt-2">Frek. Nafas</label>
                              <input type="text" class="form-control form-control-sm" placeholder="Kali/Menit" name="frek_nafas" aria-describedby="basic-addon2" value="<?php echo $pecah['frek_nafas'] ?>">
                            </div>
                            <div class="col-4">
                              <label for="" class="form-label mb-0 mt-2">BB</label>
                              <input type="text" name="" readonly id="" class="form-control form-control-sm" value="<?php echo $pecah['bb'] ?>">
                            </div>
                            <div class="col-4">
                              <label for="" class="form-label mb-0 mt-2">TB</label>
                              <input type="text" name="" readonly id="" class="form-control form-control-sm" value="<?php echo $pecah['tb'] ?>">
                            </div>
                            <div class="col-4">
                              <label for="" class="form-label mb-0 mt-2">IMT</label>
                              <input type="text" name="" readonly id="" class="form-control form-control-sm" value="<?php echo $pecah['imt'] ?>">
                            </div>
                            <?php
                            $getLabPoli = $koneksi->query("SELECT * FROM lab_poli WHERE DATE_FORMAT(jadwal, '%Y-%m-%d') = '" . date('Y-m-d', strtotime($jadwal['jadwal'])) . "' AND nama_pasien='" . $pecah['nama_pasien'] . "' ORDER BY created_at DESC LIMIT 1")->fetch_assoc();
                            ?>
                            <div class="col-md-4">
                              <label for="inputCity" class="form-label mb-0 mt-2">Gula Darah</label>
                              <div class="input-group mb-6" accesskey="">
                                <input type="text" class="form-control form-control-sm" placeholder="Gula Darah" name="gula_darah" aria-describedby="basic-addon2" value="<?php echo $getLabPoli['gula_darah'] ?>">
                              </div>
                            </div>
                            <div class="col-md-4">
                              <label for="inputCity" class="form-label mb-0 mt-2">Kolestrol</label>
                              <div class="input-group mb-6" accesskey="">
                                <input type="text" class="form-control form-control-sm" placeholder="Kolestrol" name="kolestrol" aria-describedby="basic-addon2" value="<?php echo $getLabPoli['kolestrol'] ?>">
                              </div>
                            </div>
                            <div class="col-md-4">
                              <label for="inputCity" class="form-label mb-0 mt-2">Asam Urat</label>
                              <div class="input-group mb-6" accesskey="">
                                <input type="text" class="form-control form-control-sm" placeholder="Asam Urat" name="asam_urat" aria-describedby="basic-addon2" value="<?php echo $getLabPoli['asam_urat'] ?>">
                              </div>
                            </div>
                            <div class="col-md-6">
                              <label for="inputName5" class="form-label mb-0 mt-2">Gol. Darah</label>
                              <input type="text" class="form-control form-control-sm" id="inputName5" name="gol_darah" value="<?php echo $rm['gol_darah'] ?>" placeholder="Masukkan Gol Darah Pasien">
                            </div>
                            <div class="col-md-6">
                              <label for="inputName5" class="form-label mb-0 mt-2">Status Perokok</label>
                              <select name="status_perokok" id="" class="form-control form-control-sm">
                                <option value="<?php echo $pecah['status_perokok'] ?>" hidden><?php echo $rm['status_perokok'] ?></option>
                                <option value="Aktif">Aktif</option>
                                <option value="Pasif">Pasif</option>
                              </select>
                            </div>
                            <div class="col-md-12">
                              <label for="inputName5" class="form-label mb-0 mt-2">Riwayat Alergi</label>
                              <input type="text" class="form-control form-control-sm" id="inputName5" name="riwayat_alergi" value="<?php echo $pecah['riwayat_alergi'] ?>" placeholder="Masukkan Nama Pasien">
                            </div>
                            <div class="col-md-12">
                              <label for="inputName5" class="form-label mb-0 mt-2">Riwayat Penyakit</label>
                              <div class="input-group mb-3">
                                <input type="text" class="form-control form-control-sm" id="inputName5" name="riwayat_penyakit" value="<?php echo $pecah['riwayat_penyakit'] ?>" placeholder="Masukkan Nama Pasien">
                                <a href="#riwayatVaksi" class="btn btn-sm btn-secondary float-end"><i class="bi bi-arrow-down"></i></a>
                              </div>
                            </div>
                            <div id="riwayatVaksi">
                              <div class="row">
                                <div style="">
                                  <h6 class="mb-0"><b>Riwayat Vaksinasi</b></h6>
                                </div>
                                <div class="col-md-6">
                                  <label for="inputName5" class="form-label">Nama Vaksin</label>
                                  <input type="text" class="form-control form-control-sm" id="inputName5" name="nama_vaksin" value="<?php echo $pecah['nama_vaksin'] ?>" placeholder="Masukkan Gol Darah Pasien">
                                </div>
                                <div class="col-md-6">
                                  <label for="inputName5" class="form-label">Tanggal Pemberian</label>
                                  <div class="input-group mb-3">
                                    <input type="date" class="form-control form-control-sm" id="inputName5" name="tgl_vaksin" value="<?php echo $pecah['tgl_vaksin'] ?>" placeholder="Status Perokok">
                                    <a href="#anamnesa" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-down"></i></a>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-4">
                  <div class="card h-100 pt-2">
                    <div class="card-body">
                      <div class="row g-1">
                        <div id="anamnesa">
                          <div class="row g-1">
                            <div>
                              <h6 class="mt-2 mb-0"><b>Anamnesa</b></h6>
                            </div>
                            <!-- Modal -->
                            <!-- <div class="modal fade" id="masterAssesment" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Master</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">
                                    <select name="obj" id="masterSelect" class="form-select form-control form-control-sm" required>
                                      <option value="" hidden>Pilih Master</option>
                                      <?php
                                      $master = $koneksi->query("SELECT * FROM rekam_medis WHERE objective != '' GROUP BY diagnosis ORDER BY jadwal DESC");
                                      ?>
                                      <?php foreach ($master as $m) { ?>
                                        <option value="<?= $m['id_rm'] ?>"><?= $m['diagnosis'] ?> | | <?= $m['objective'] ?></option>
                                      <?php } ?>
                                    </select>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-sm btn-primary" onclick="copyMasterData()">Copy</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <script>
                              function copyMasterData() {
                                // Dapatkan elemen select
                                const selectElement = document.getElementById('masterSelect');
  
                                // Dapatkan text yang dipilih
                                const selectedText = selectElement.options[selectElement.selectedIndex].text;
  
                                // Pisahkan text berdasarkan tanda pipe '|'
                                const parts = selectedText.split('|').map(part => part.trim());
  
                                // Pastikan ada 3 bagian (diagnosis | anamnesa | objective)
                                if (parts.length >= 3) {
                                  // Masukkan bagian anamnesa (indeks 1) ke textarea keluhan utama
                                  // document.getElementById('keluhanUtama').value = parts[1];
  
                                  // Masukkan bagian objective (indeks 2) ke textarea objective
                                  document.getElementById('objectiveText').value = parts[2];
                                } else if (parts.length === 2) {
                                  // Jika hanya ada 2 bagian, asumsikan format: anamnesa | objective
                                  document.getElementById('keluhanUtama').value = parts[0];
                                  document.getElementById('objectiveText').value = parts[1];
                                }
  
                                // Tutup modal (jika menggunakan Bootstrap modal)
                                const modal = bootstrap.Modal.getInstance(document.querySelector('.modal'));
                                if (modal) {
                                  modal.hide();
                                }
                              }
                            </script> -->
                            <!-- end Modal  -->
                            <div class="col-md-6">
                              <label for="inputName5" class="form-label mt-2 mb-0">Keluhan Utama</label>
                              <!-- <input type="text" class="form-control form-control-sm" id="inputName5" name="keluhan_utama" value="<?php echo $pecah['keluhan_utama'] ?>" placeholder="Masukkan Keluhan Pasien"> -->
                              <textarea name="keluhan_utama" placeholder="Keluhan Utama" id="keluhanUtama" class="form-control form-control-sm"><?php echo $pecah['keluhan_utama'] ?></textarea>
                            </div>
                            <div class="col-md-6">
                              <label for="inputName5" class="form-label mt-2 mb-0">Keluhan Tambahan</label>
                              <!-- <input type="text" class="form-control form-control-sm" id="inputName5" name="anamnesa" value="<?php echo $rm['anamnesa'] ?>" placeholder="Anamnesa"> -->
                              <textarea name="anamnesa" id="inputName5" placeholder="Anamnesa" class="form-control form-control-sm"><?php echo $rm['anamnesa'] ?></textarea>
                            </div>
                            <div class="col-md-12">
                              <label for="inputName5" class="form-label mt-2 mb-0">Objective</label>
                              <!-- <input type="text" class="form-control form-control-sm" id="inputName5" name="objective" value="<?php echo $pecah['objective'] ?? '' ?>" placeholder="Objectieve"> -->
                              <textarea name="objective" id="objectiveText" class="form-control form-control-sm"><?php echo $rm['objective'] ?></textarea>
                            </div>
                          </div>
                        </div>
                        <h6 class="mt-3 mb-0"><b>Assessment</b></h6>
                        <div class="form-group">
                          <label class=" mb-0">Diagnosis</label>
                          <select name="diagnosis" required id="diagnosis_id" class="form-control form-control-sm">
                            <option value="<?= $rm['diagnosis'] ?>"><?= $rm['diagnosis'] ?></option>
                            <option value="">Pilih Diagnosis</option>
                            <option value="Diagnosis Baru">Diagnosis Baru</option>
                            <?php
                            $getAllDiagnosis = $koneksi->query("SELECT * FROM rekam_medis GROUP BY diagnosis ORDER BY diagnosis ASC");
                            foreach ($getAllDiagnosis as $allDiagnosis) {
                            ?>
                              <option value="<?= $allDiagnosis['diagnosis'] ?>"><?= $allDiagnosis['diagnosis'] ?></option>
                            <?php } ?>
                          </select>
                          <textarea type="text" name="diagnosis_new" id="diagnosis_new_id" class="form-control form-control-sm mt-2" style="height: 100px;" value="<?php echo $rm['diagnosis'] ?>" placeholder="Diagnosis"><?php echo $rm['diagnosis'] ?></textarea>
                        </div>
                        <div class="form-group">
                          <label class="mt-2 mb-0">Prognosis</label>
                          <select name="prognosa" class="form-select form-control-sm form-select-sm">
                            <option value="Prognosis good" selected>BONAM (BAIK)</option>
                            <option value="Guarded prognosis">MALAM (BURUK/JELEK)</option>
                            <option value="SANAM (SEMBUH)">SANAM (SEMBUH)</option>
                            <option value="Fair prognosis">DUBIA (TIDAK TENTU/RAGU-RAGU)</option>
                            <?php if ($rm['prognosa'] != '') { ?>
                              <option value="<?= $rm['prognosa'] ?>" selected><?= $rm['prognosa'] ?></option>
                            <?php } ?>
                          </select>
                        </div>
                        <div class="form-group">
                          <div class="col-md-12">
                            <label class="mt-2 mb-0">ICD 10</label>
                            <select class="form-select" id="selUser" aria-label="Default select example" name="icd">
                              <option value="<?= $rm['icd'] ?>"><?= $rm['icd'] ?></option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <script>
                        $(document).ready(function() {
                          $('#diagnosis_new_id').hide(); // Sembunyikan textarea
                          $('#selUser').select2();
                          $('#diagnosis_id').select2();

                          $('#diagnosis_id').on('change', function() {
                            const diagnosis = $(this).val();
                            const icdDropdown = $('#selUser');

                            if (diagnosis === 'Diagnosis Baru') {
                              $('#diagnosis_new_id').show(); // Tampilkan textarea jika Diagnosis Baru

                              // Hapus semua opsi sebelumnya, kecuali opsi pertama
                              icdDropdown.find('option').not(':first').remove();

                              // Konfigurasi Select2 dengan AJAX untuk pencarian dinamis
                              icdDropdown.select2({
                                placeholder: 'Cari ICD...',
                                allowClear: true,
                                ajax: {
                                  url: '../rekammedis/get_icd_api.php',
                                  type: 'POST',
                                  dataType: 'json',
                                  delay: 250,
                                  data: function(params) {
                                    return {
                                      search: params.term, // kirimkan input pencarian ke server
                                      diagnosis: diagnosis
                                    };
                                  },
                                  processResults: function(data) {
                                    return {
                                      results: data.map(function(item) {
                                        return {
                                          id: item.icd,
                                          text: item.icd + ' - ' + item.name_en
                                        };
                                      })
                                    };
                                  },
                                  cache: true
                                }
                              });
                            } else {
                              $('#diagnosis_new_id').hide(); // Sembunyikan textarea jika diagnosis bukan baru

                              // Ambil data ICD berdasarkan diagnosis yang dipilih
                              $.ajax({
                                url: '../rekammedis/get_icd_api.php',
                                type: 'POST',
                                data: {
                                  diagnosis: diagnosis
                                },
                                success: function(response) {
                                  const icdData = JSON.parse(response);

                                  // Hapus semua opsi sebelumnya, kecuali opsi pertama
                                  icdDropdown.find('option').not(':first').remove();

                                  // Tambahkan opsi baru ke dropdown ICD
                                  icdData.forEach(icd => {
                                    icdDropdown.append(
                                      `<option selected value="${icd.icd}">${icd.icd} - ${icd.name_en}</option>`
                                    );
                                  });

                                  // Refresh dropdown
                                  icdDropdown.select2();
                                },
                                error: function(error) {
                                  console.error('Error fetching ICD data:', error);
                                }
                              });
                            }
                          });
                        });
                      </script>
                      <div>
                        <h6 class="mt-2 mb-0"><b>Status Pulang</b></h6>
                      </div>
                      <div class="col-md-12">
                        <label for="inputState" class="form-label mt-2 mb-0">Status Pulang</label>
                        <select id="inputState" name="status_pulang" class="form-select form-control-sm form-select-sm">
                          <option selected>Berobat Jalan</option>
                          <option>Berobat Jalan</option>
                          <option>Rawat Inap</option>
                        </select>
                      </div>
                      <?php
                      $getRMinDate = $koneksi->query("SELECT * FROM rekam_medis WHERE jadwal = '$jadwal[jadwal]'");
                      if ($getRMinDate->num_rows == 0) {
                      ?>
                      <?php } ?>
                      <div class="text-center mb-2 mt-3">
                        <?php if (!isset($_GET['ed'])) { ?>
                          <button type="submit" name="save" class="btn btn-sm btn-primary">Tambah</button>
                        <?php } else { ?>
                          <button type="submit" name="editrm" class="btn btn-sm btn-warning">Edit</button>
                        <?php } ?>
                        <button type="reset" class="btn btn-sm btn-secondary">Reset</button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-4">
                  <div class="card h-100 p-2">
                    <h6 class="mt-2 mb-0"><b>Plan</b> <span class="badge bg-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">+</span></h6>
                    <div id="plan">
                      <div class="table-responsive">
                        <div id="userList">
                          <table class="table table-sm table-striped table-hover" style="font-size: 12px;">
                            <thead>
                              <tr>
                                <th width="5%">No</th>
                                <th width="40%">Layanan/Tindakan</th>
                                <th width="30%">Kode</th>
                                <th width="30%">Jml</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php $no = 1 ?>
                              <?php
                              $plan = $koneksi->query("SELECT * FROM layanan WHERE idrm = '$_GET[id]' AND DATE_FORMAT(tgl_layanan, '%Y-%m-%d') = DATE_FORMAT('$_GET[tgl]', '%Y-%m-%d')");
                              ?>
                              <?php foreach ($plan as $plan) : ?>
                                <tr>
                                  <td><?php echo $no; ?></td>
                                  <td style="margin-top:10px;"><?php echo $plan["layanan"]; ?></td>
                                  <td style="margin-top:10px;"><?php echo $plan["kode_layanan"]; ?></td>
                                  <td style="margin-top:10px;"><?php echo $plan["jumlah_layanan"]; ?></td>
                                </tr>
                                <?php $no += 1 ?>
                              <?php endforeach ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>

                    <?php
                    $getLastRM = $koneksi->query("SELECT  *, COUNT(*) AS jumm, MAX(id_rm) as id_rm FROM rekam_medis WHERE norm = '" . htmlspecialchars($_GET['id']) . "' AND DATE_FORMAT(jadwal, '%Y-%m-%d') = '" . date('Y-m-d', strtotime($_GET['tgl'])) . "' ORDER BY id_rm DESC LIMIT 1")->fetch_assoc();
                    $getLastRM['jumm'] > 0 ? $whereConditionObatRm = "AND rekam_medis_id = '$getLastRM[id_rm]'" : $whereConditionObatRm = "AND rekam_medis_id IS NULL";
                    $obat = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' " . $whereConditionObatRm . " ");
                    ?>
                    <div>
                      <h6 class="mt-2 mb-0"><b>Tambah Obat Untuk Jadwal <?= $jadwal['jadwal'] ?></b></h6>
                      <?php $obatData = $obat->fetch_assoc(); ?>
                      <?php if ($obatData['status_obat'] != "selesai") { ?>
                        <div align="right">
                          <button type="button" class="btn btn-sm mb-2 btn-primary text-right" data-bs-toggle="modal" data-bs-target="#exampleModal45" disable>[+] Jadi <?= $getLastRM['id_rm'] ?></button>
                          <button type="button" class="btn btn-sm mb-2 btn-success text-right" data-bs-toggle="modal" data-bs-target="#exampleModal2">[+] Racik</button>
                          <a type="button" class="btn btn-sm mb-2 btn-info text-right" href="index.php?halaman=tambahpuyer2&id=<?= $_GET['id'] ?>&tgl=<?= $_GET['tgl'] ?>">[+] Paket Racik</a>
                          <span type="button" class="btn btn-sm mb-2 btn-warning text-right" data-bs-toggle="modal" data-bs-target="#addPaketJadi">[+] Paket Jadi</span>
                        </div>
                      <?php } ?>
                      <div class="table-responsive">
                        <?php $subtotal = 0; ?>
                        <div id="employee_table">
                          <table class="table table-sm table-striped table-hover" style="font-size: 12px;">
                            <thead>
                              <tr>
                                <th>No.</th>
                                <th>Obat</th>
                                <th>Kode Obat</th>
                                <th>Jumlah Obat</th>
                                <th>Dosis</th>
                                <th>Jenis</th>
                                <th>Durasi</th>
                                <th></th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php $no = 1 ?>
                              <?php foreach ($obat as $obat) :
                                if (isset($_GET['inap'])) {
                                  $ambil2 = $koneksi->query("SELECT * FROM apotek WHERE nama_obat='$obat[nama_obat]' AND tipe='Ranap' ");
                                  $pecah2 = $ambil2->fetch_assoc();
                                } else {
                                  $ambil2 = $koneksi->query("SELECT * FROM apotek WHERE nama_obat='$obat[nama_obat]' AND tipe='Rajal' ");
                                  $pecah2 = $ambil2->fetch_assoc();
                                }
                              ?>
                                <?php
                                $m = $pecah2['margininap'] ?? 100;
                                if ($m < 100) {
                                  $margin = 1.30;
                                } else {
                                  $margin = $m / 100;
                                }
                                ?>
                                <?php
                                $subharga = intval($pecah2['harga_beli'] ?? 0) * intval($obat['jml_dokter'] ?? 0) * $margin;
                                ?>
                                <tr>
                                  <td><?php echo $no; ?></td>
                                  <td style="margin-top:10px;"><?php echo $obat["nama_obat"]; ?></td>
                                  <td style="margin-top:10px;"><?php echo $obat["kode_obat"]; ?></td>
                                  <td style="margin-top:10px;"><?php echo $obat["jml_dokter"]; ?></td>
                                  <td style="margin-top:10px;"><?php echo $obat["dosis1_obat"]; ?> X <?php echo $obat["dosis2_obat"]; ?> <?php echo $obat["per_obat"]; ?></td>
                                  <td style="margin-top:10px;"><?php echo $obat["jenis_obat"]; ?> <?= $obat['racik'] ?></td>
                                  <td style="margin-top:10px;"><?php echo $obat["durasi_obat"]; ?> hari</td>
                                  <?php if ($obat["status_obat"] != "selesai") { ?>
                                    <td style="margin-top:10px;"> <button type="button" class="btn btn-sm btn-primary text-right" data-bs-toggle="modal" data-bs-target="#exampleModalEdit<?php echo $obat["idobat"]; ?>">Edit</button>
                                      <?php if (isset($_GET['inap'])) { ?>
                                        <a href="index.php?halaman=rmedis&id=<?php echo $obat["idobat"]; ?>&rm=<?php echo $_GET["id"]; ?>&tgl=<?php echo $_GET["tgl"]; ?>&hapus&inap=<?= $_GET['inap'] ?>" class="btn btn-sm btn-danger text-right"><i class="bi bi-trash"></i></a>
                                      <?php } else { ?>
                                        <a href="index.php?halaman=rmedis&id=<?php echo $obat["idobat"]; ?>&rm=<?php echo $_GET["id"]; ?>&tgl=<?php echo $_GET["tgl"]; ?>&hapus" class="btn btn-sm btn-danger text-right"><i class="bi bi-trash"></i></a>
                                      <?php } ?>
                                    </td>
                                  <?php } ?>
                                  <?php $subtotal += $subharga; ?>
                                </tr>
                                <!-- Add Data Modal Obat -->
                                <div class="modal fade" id="exampleModalEdit<?php echo $obat["idobat"]; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Edit Obat</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <div class="modal-body">
                                        <div class="row">
                                          <form method="post" enctype="multipart/form-data">
                                            <div class="control-group after-add-more">
                                              <!-- <div class="modal-body"> -->
                                              <div class="row">
                                                <div class="col-md-12">
                                                  <label for="inputName5" class="form-label">Nama Obat</label>
                                                  <!-- <input type="text" name="nama_obat" class="form-control form-control-sm" id="inputName5" placeholder="Layanan/Tindakan"> -->
                                                  <select name="nama_obat" onchange="updateCatatan()" id="nama_obat" class="form-select">
                                                    <option value="<?php echo $obat["nama_obat"]; ?>"><?php echo $obat["nama_obat"]; ?></option>
                                                    <?php

                                                    $getObat = $koneksi->query("SELECT * FROM apotek ORDER BY nama_obat ASC");
                                                    foreach ($getObat as $data) {
                                                    ?>
                                                      <option value="<?= $data['id_obat'] ?>"><?= $data['nama_obat'] ?></option>
                                                    <?php } ?>
                                                  </select>
                                                </div>

                                                <script>
                                                  function updateCatatan() {
                                                    var inputState = document.getElementById('nama_obat');
                                                    var catatanInput = document.getElementById('catatan');
                                                    var petunjukInput = document.getElementById('petunjuk');

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

                                                    if (dosis1.value == '3' && dosis2.value == '1') {
                                                      jml.value = 9;
                                                    } else if (dosis1.value == '2' && dosis2.value == '1') {
                                                      jml.value = 6;
                                                    } else if (dosis1.value == '1' && dosis2.value == '1') {
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
                                                <input type="text" name="catatan_obat" class="form-control form-control-sm" id="catatan" placeholder="Masukkan Catatan Waktu" value="<?php echo $obat["catatan_obat"]; ?>">
                                              </div>
                                              <div class="col-md-12" style="margin-top:0px; margin-bottom:20px;">
                                                <label for="inputName5" class="form-label">Jenis Obat</label>
                                                <select name="jenis_obat" class="form-select">
                                                  <option value="Racik">Racik</option>
                                                  <option value="Jadi">Jadi</option>
                                                </select>
                                              </div>
                                              <label for="inputName5" class="form-label">Dosis</label>
                                              <div class="col-md-6">
                                                <div class="input-group">
                                                  <input oninput="updateJumlah()" type="text" class="form-control form-control-sm" id="dosis1_obat" name="dosis1_obat" value="<?php echo $obat["dosis1_obat"]; ?>">
                                                  <input type="text" style="text-align: center;" class="form-control form-control-sm" placeholder="X">
                                                  <input oninput="updateJumlah()" type="text" class="form-control form-control-sm" id="dosis2_obat" name="dosis2_obat" value="<?php echo $obat["dosis2_obat"]; ?>">
                                                </div>
                                              </div>

                                              <div class="col-md-6">
                                                <select id="inputState" name="per_obat" class="form-select">
                                                  <option value="<?php echo $obat["per_obat"]; ?>"><?php echo $obat["per_obat"]; ?></option>
                                                  <option>Per Hari</option>
                                                  <option>Per Jam</option>
                                                </select>
                                              </div>
                                              <div class="col-md-12" style="margin-top:20px">
                                                <label for="">Jumlah Obat</label>
                                                <input type="number" name="id_obat_sebelum" class="form-control form-control-sm" id="id_obat_sebelum" hidden placeholder="jumlah obat" value="<?php echo $obat["idobat"]; ?>">
                                                <input type="number" name="jml_obat_sebelum" class="form-control form-control-sm" id="jml_obat_sebelum" hidden placeholder="jumlah obat" value="<?php echo $obat["jml_dokter"]; ?>">
                                                <input type="number" name="jml_dokter" class="form-control form-control-sm" id="jml_obat" placeholder="jumlah obat" value="<?php echo $obat["jml_dokter"]; ?>">
                                              </div>
                                              <div class="col-md-12" style="margin-top:20px">
                                                <label for="inputCity" class="form-label">Durasi</label>
                                                <div class="input-group mb-3">
                                                  <input type="text" name="durasi_obat" value="<?php echo $obat["durasi_obat"]; ?>" class="form-control form-control-sm" placeholder="Durasi" aria-describedby="basic-addon2">
                                                  <span class="input-group-text" id="basic-addon2">Hari</span>
                                                </div>
                                              </div>
                                              <div class="col-md-12" style="margin-top:10px">
                                                <label for="inputName5" class="form-label">Petunjuk Pemakaian</label>
                                                <input type="text" name="petunjuk_obat" value="<?php echo $obat["petunjuk_obat"]; ?>" class="form-control form-control-sm" id="petunjuk" placeholder="Masukkan Petunjuk Pemakaian">
                                              </div>
                                            </div>
                                            <input type="number" name="id" value="<?php echo $obat["idobat"]; ?>" hidden>

                                            <!-- <input type="hidden" name="id_pasien" value="<?php echo $pecah['idpasien'] ?>"> -->
                                            <input type="hidden" name="idrm" value="<?php echo $_GET['id'] ?>">
                                            <div class="modal-footer">
                                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                              <input type="submit" class="btn btn-primary" name="edt" value="Save changes">
                                            </div>
                                          </form>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <!-- end -->
                                <?php $no += 1 ?>
                              <?php endforeach ?>
                            </tbody>
                          </table>
                        </div>
                        <div>
                          <div>
                            <form method="post">
                              <button type="submit" name="selesai" class="btn btn-sm btn-primary" onclick="return confirm('Apakah anda untuk menyelesaikan rekam medis ini ???')">Selesai</button>
                            </form>
                          </div>
                          <?php
                          if (isset($_POST['selesai'])) {
                            $koneksi->query("UPDATE obat_rm SET status_obat='selesai' WHERE rekam_medis_id='$getLastRM[id_rm]'");
                            echo "
                              <script>
                                alert('Status berhasil diubah menjadi selesai');
                                document.location.href='index.php?halaman=rmedis&id=$_GET[id]&tgl=$_GET[tgl]';
                              </script>
                            ";
                          }
                          ?>
                        </div>
                      </div>
                      <h6 class="mt-3 mb-0"><b>Data Laboratorium</b></h6>
                      <div class="table-responsive">
                        <table class="table table-sm table-hover table-striped" style="font-size: 12px;">
                          <thead>
                            <tr>
                              <th>Pasien</th>
                              <th>Tipe</th>
                              <th>Pemeriksaan</th>
                              <th>Hasil</th>
                              <th>NilaiNormal</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php while ($pecah = $lab->fetch_assoc()) { ?>
                              <tr>
                                <td> <?php echo $pecah["pasien"]; ?></td>
                                <td> <?php echo $pecah["tipe"]; ?></td>
                                <td>
                                  <?php echo $pecah["nama_periksa"]; ?>
                                </td>
                                <td><?php echo $pecah["hasil_periksa"]; ?></td>
                                <td><?php echo $pecah["indikator"]; ?></td>
                              </tr>
                            <?php } ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php } elseif (isset($_GET['entriObat'])) { ?>
              <?php include '../rekammedis/rmedis_entriObat.php'; ?>
            <?php } ?>
          </div>
        </div>
      </div>
    </form>
  </div>
  <?php if (!isset($_GET['entriObat'])) { ?>
    <!-- Add Data Modal Obat Racik -->
    <div class="modal  fade" role="dialog" id="exampleModal2" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Obat <a href="index.php?halaman=rmedis&id=<?= htmlspecialchars($_GET['id']) ?>&tgl=<?= htmlspecialchars($_GET['tgl']) ?>&entriObat=Racik" target="_blank" class="btn btn-sm btn-primary">New Tab</a></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <form method="post" enctype="multipart/form-data">
                <div class="control-group after-add-more">
                  <!-- <div class="modal-body"> -->
                  <div class="row">
                    <div class="col-md-12">
                      <label for="inputName5" class="form-label">Racik Ke-</label><br>
                      <input type="number" name="racik" class="form-control form-control-sm w-100" style="width:100%;" aria-label="Default select example">
                      <label for="inputName5" class="form-label">Nama Obat</label><br>
                      <select name="nama_obat[]" class="form-control form-control-sm w-100" style="width:100%;" id="selObat1" aria-label="Default select example">
                        <option value="">Pilih</option>
                        <?php
                        if (!isset($_GET['inap'])) {
                          $getObat = $koneksi->query("SELECT * FROM apotek WHERE tipe != '' AND aktif_poli = 'aktif' GROUP BY nama_obat ORDER BY nama_obat ASC");
                        } else {
                          $getObat = $koneksi->query("SELECT * FROM apotek WHERE tipe != '' AND aktif_ranap = 'aktif' GROUP BY nama_obat ORDER BY nama_obat ASC");
                        }
                        foreach ($getObat as $data) {
                        ?>
                          <option value="<?= $data['id_obat'] ?>"><?= $data['nama_obat'] ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <script></script>
                    <div class="col-md-12" style="margin-top:20px">
                      <label for="">Jumlah Obat</label>
                      <input type="number" name="jml_dokter[]" class="form-control form-control-sm" id="inputName5" placeholder="jumlah obat">
                    </div>
                  </div>
                </div>
                <button class="btn btn-sm btn-warning add-more" type="button">
                  <i class="glyphicon glyphicon-plus"></i> Tambah Lagi
                </button>
                <hr>
                <div class="copy invisible" style="display: none;">
                  <br>
                  <div class="control-group">
                    <label for="inputName5" class="form-label">Nama Obat</label>
                    <select name="nama_obat[]" class="form-control form-control-sm " id="selObat1" aria-label="Default select example">
                      <option value="">Pilih</option>

                      <?php
                      if (!isset($_GET['inap'])) {
                        $getObat = $koneksi->query("SELECT * FROM apotek WHERE tipe != '' AND aktif_poli = 'aktif' GROUP BY nama_obat ORDER BY nama_obat ASC");
                      } else {
                        $getObat = $koneksi->query("SELECT * FROM apotek WHERE tipe != '' AND aktif_ranap = 'aktif' GROUP BY nama_obat ORDER BY nama_obat ASC");
                      }
                      foreach ($getObat as $data) {
                      ?>
                        <option value="<?= $data['id_obat'] ?>"><?= $data['nama_obat'] ?></option>
                      <?php } ?>
                    </select>
                    <div class="col-md-12" style="margin-top:20px">
                      <label for="">Jumlah Obat</label>
                      <input type="number" name="jml_dokter[]" class="form-control form-control-sm" id="inputName5" placeholder="jumlah obat">
                    </div>
                    <br>
                    <button class="btn btn-sm btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i> Batal</button>
                    <hr>
                  </div>
                </div>
                <div class="col-md-12" style="margin-top:20px; margin-bottom:20px;">
                  <input type="text" name="catatan_obat[]" value="-" hidden class="form-control form-control-sm" id="inputName5" placeholder="Masukkan Jumlah">
                </div>
                <div class="col-md-12" style="margin-top:0px; margin-bottom:20px;">
                  <label for="inputName5" class="form-label">Jenis Obat</label>
                  <select name="jenis_obat[]" class="form-control form-control-sm">
                    <option value="Racik">Racik</option>
                  </select>
                </div>
                <label for="inputName5" class="form-label">Dosis</label>
                <div class="row">
                  <div class="col-md-6">
                    <div class="input-group">
                      <input type="text" class="form-control form-control-sm" name="dosis1_obat[]">
                      <input type="text" style="text-align: center;" class="form-control form-control-sm" placeholder="X">
                      <input type="text" class="form-control form-control-sm" name="dosis2_obat[]">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <select id="inputState" name="per_obat[]" class="form-control form-control-sm">
                      <option>Per Hari</option>
                      <option>Per Jam</option>
                    </select>
                  </div>
                  <div class="col-md-6" style="margin-top:10px">
                    <label for="inputCity" class="form-label">Durasi</label>
                    <div class="input-group mb-3">
                      <input type="text" name="durasi_obat[]" class="form-control form-control-sm" placeholder="Durasi" aria-describedby="basic-addon2">
                      <span class="input-group-text form-control form-control-sm" id="basic-addon2">Hari</span>
                    </div>
                  </div>
                  <div class="col-md-6" style="margin-top:10px">
                    <label for="inputName5" class="form-label">Petunjuk Pemakaian</label>
                    <input type="text" name="petunjuk_obat[]" class="form-control form-control-sm" id="inputName5" placeholder="Masukkan Petunjuk Pemakaian">
                  </div>
                </div>
                <!-- <input type="hidden" name="id_pasien" value="<?php echo $pecah['idpasien'] ?>"> -->
                <input type="hidden" name="idrm" value="<?php echo $_GET['id'] ?>">
                <input type="hidden" class="form-control form-control-sm" id="inputName5" name="id" value="<?php echo $jadwal['idrawat'] ?>" placeholder="Masukkan Nama Pasien">
                <div class="modal-footer">
                  <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                  <input type="submit" class="btn btn-sm btn-primary" name="saveob" value="Save changes">
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
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
                  <option value="glukosa"><span style="text-transform: 'capitalize';">glukosa</span></option>
                  <option value="asam urat"><span style="text-transform: 'capitalize';">asam urat</span></option>
                  <option value="kolestrol"><span style="text-transform: 'capitalize';">kolestrol</span></option>
                  <option value="irigasi mata"><span style="text-transform: 'capitalize';">irigasi mata</span></option>
                  <option value="irigasi telinga"><span style="text-transform: 'capitalize';">irigasi telinga</span></option>
                  <option value="suntik kb"><span style="text-transform: 'capitalize';">suntik kb</span></option>
                  <option value="lain-lain"><span style="text-transform: 'capitalize';">lain-lain</span></option>
                </select>
                <script>
                  function SelLay(selectElement) {
                    var otherInput = document.getElementById('inpLay');
                    var hrgInput = document.getElementById('hrgLay');

                    if (selectElement.value === 'lain-lain') {
                      otherInput.style.display = 'block';
                      hrgInput.value = '';
                    } else {
                      otherInput.style.display = 'none';
                    }

                    if (selectElement.value === 'glukosa') {
                      hrgInput.value = '15000';
                    }
                    if (selectElement.value === 'asam urat') {
                      hrgInput.value = '15000';
                    }
                    if (selectElement.value === 'kolestrol') {
                      hrgInput.value = '25000';
                    }
                    if (selectElement.value === 'irigasi mata') {
                      hrgInput.value = '35000';
                    }
                    if (selectElement.value === 'irigasi kuping') {
                      hrgInput.vallue = '100000';
                    }
                    if (selectElement.value === 'suntik kb') {
                      hrgInput.value = '25000';
                    }
                  }
                </script>
                <input type="text" name="layanan2" style="display: none;" class="form-control form-control-sm" id="inpLay" placeholder="Layanan/Tindakan Lain">
              </div>
              <div class="col-md-12" style="margin-top:20px">
                <label for="inputName5" class="form-label">Harga Layanan</label>
                <input type="text" name="harga_layanan" class="form-control form-control-sm" id="hrgLay" placeholder="Harga Layanan">
              </div>
              <div class="col-md-12" style="margin-top:0px; height: 0.1px; visibility : hidden;">
                <label for="inputName5" class="form-label">Jumlah</label>
                <input type="text" name="jumlah_layanan" value="1" class="form-control form-control-sm" id="inputName5" placeholder="Masukkan Jumlah">
              </div>
              <input type="hidden" name="id_pasien" value="<?php echo $pecah['idpasien'] ?>">
              <!-- <input type="hidden" name="idrm" value="<?php echo $pecah['norm'] ?>"> -->
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              <input type="submit" class="btn btn-primary" name="savelay" value="Save changes" />

            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Add Data Modal Obat Jadi -->
    <div class="modal  fade" role="dialog" id="exampleModal45" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Obat <a href="index.php?halaman=rmedis&id=<?= htmlspecialchars($_GET['id']) ?>&tgl=<?= htmlspecialchars($_GET['tgl']) ?>&entriObat=Jadi" target="_blank" class="btn btn-sm btn-primary">New Tab</a> </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <form method="post" enctype="multipart/form-data">
                <div class="control-group">
                  <!-- <div class="modal-body"> -->
                  <div class="row">
                    <div class="col-md-12">
                      <label for="inputName5" class="form-label">Nama Obat</label><br>
                      <!-- <input type="text" name="nama_obat" class="form-control form-control-sm" id="inputName5" placeholder="Layanan/Tindakan"> -->
                      <select name="nama_obat[]" class="form-select form-control form-control-sm mb-2 w-100" style="width:100%;" id="selObat1" aria-label="Default select example">
                        <option value="">Pilih</option>
                        <?php
                        if (!isset($_GET['inap'])) {
                          $getObat = $koneksi->query("SELECT * FROM apotek WHERE tipe != '' AND aktif_poli = 'aktif' GROUP BY nama_obat ORDER BY nama_obat ASC");
                        } else {
                          $getObat = $koneksi->query("SELECT * FROM apotek WHERE tipe != '' AND aktif_ranap = 'aktif' GROUP BY nama_obat ORDER BY nama_obat ASC");
                        }
                        foreach ($getObat as $data) {
                        ?>
                          <option value="<?= $data['id_obat'] ?>"><?= $data['nama_obat'] ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label for="inputName5">Dosis</label>
                      <div class="input-group">
                        <input type="text" class="form-control form-control-sm mb-2" id="dosis1_obat" name="dosis1_obat[]">
                        <span type="text" style="text-align: center;" class="form-control form-control-sm mb-2" placeholder="X" disabled>X</span>
                        <input type="text" class="form-control form-control-sm mb-2" id="dosis2_obat" name="dosis2_obat[]">
                      </div>
                    </div>
                    <div class="col-md-6">
                      Per
                      <select id="inputState" name="per_obat[]" class=" form-control form-control-sm">
                        <option>Per Hari</option>
                        <option>Per Jam</option>
                      </select>
                    </div>
                    <!-- <div class="col-md-12" style="margin-top:20px">
                      <label for="inputName5" class="form-label">Kode Obat</label>
                      <select name="kode_obat[]" id="" class="form-select">
                        <?php
                        $getObat = $koneksi->query("SELECT * FROM apotek ORDER BY nama_obat ASC");
                        foreach ($getObat as $data) {
                        ?>
                          <option value="<?= $data['id_obat'] ?>"><?= $data['nama_obat'] . " || " . $data['id_obat'] ?></option>
                        <?php } ?>
                      </select>
                  </div> -->
                    <div class="col-md-6">
                      <label for="">Jumlah Obat</label>
                      <input type="number" name="jml_dokter[]" class="form-control form-control-sm mb-2" id="inputName5" placeholder="Jumlah Obat">
                    </div>
                    <div class="col-md-6">
                      <label for="inputName5" class="">Petunjuk Pemakaian</label>
                      <input type="text" name="petunjuk_obat[]" class="form-control form-control-sm mb-2" id="inputName5" placeholder="Masukkan Petunjuk Pemakaian">
                    </div>
                    <div class="col-md-12">
                      <!-- <label for="inputName5" class="">Catatan Interaksi Obat</label> -->
                      <input type="text" name="catatan_obat[]" value="-" hidden class="form-control form-control-sm mb-2" id="inputName5" placeholder="Masukkan Jumlah">
                    </div>
                    <div class="col-md-6">
                      <label for="inputName5" class="">Jenis Obat</label>
                      <select name="jenis_obat[]" class=" form-control form-control-sm mb-2">
                        <option value="Jadi">Jadi</option>
                        <!-- <option value="Jadi">Jadi</option> -->
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label for="inputCity" class="">Durasi</label>
                      <div class="input-group mb-3">
                        <input type="text" name="durasi_obat[]" class="form-control form-control-sm" placeholder="Durasi" aria-describedby="basic-addon2">
                        <span class="input-group-text form-control form-control-sm" id="basic-addon2">Hari</span>
                      </div>
                    </div>
                  </div>
                  <hr>
                </div>
                <button class="btn btn-sm btn-warning add-more2" type="button">
                  <i class="glyphicon glyphicon-plus"></i> Tambah Lagi
                </button>
                <hr>
                <div class="after-add-more2"></div>
                <div class="copy2 invisible" style="display: none;">
                  <br>
                  <div class="control-group2">
                    <label for="inputName5" class="form-label">Nama Obat</label>
                    <!-- <input type="text" name="nama_obat" class="form-control form-control-sm" id="inputName5" placeholder="Layanan/Tindakan"> -->
                    <select name="nama_obat[]" class="form-select mb-2 form-control form-control-sm" id="selObat1" aria-label="Default select example">
                      <option value="">Pilih</option>

                      <?php
                      if (!isset($_GET['inap'])) {
                        $getObat = $koneksi->query("SELECT * FROM apotek WHERE tipe != '' AND aktif_poli = 'aktif' GROUP BY nama_obat ORDER BY nama_obat ASC");
                      } else {
                        $getObat = $koneksi->query("SELECT * FROM apotek WHERE tipe != '' AND aktif_ranap = 'aktif' GROUP BY nama_obat ORDER BY nama_obat ASC");
                      }
                      foreach ($getObat as $data) {
                      ?>
                        <option value="<?= $data['id_obat'] ?>"><?= $data['nama_obat'] ?></option>
                      <?php } ?>
                    </select>

                    <div class="row">
                      <div class="col-md-6">
                        <label for="inputName5" class="">Dosis</label>
                        <div class="input-group">
                          <input type="text" class="form-control form-control-sm mb-2" id="dosis1_obat" name="dosis1_obat[]">
                          <span type="text" style="text-align: center;" class="form-control form-control-sm mb-2" placeholder="X">X</span>
                          <input type="text" class="form-control form-control-sm mb-2" id="dosis2_obat" name="dosis2_obat[]">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <label for="inputName5" class="">Per</label>
                        <select id="inputState" name="per_obat[]" class="form-control form-control-sm">
                          <option>Per Hari</option>
                          <option>Per Jam</option>
                        </select>
                      </div>
                      <div class="col-md-6">
                        <label for="">Jumlah Obat</label>
                        <input type="number" name="jml_dokter[]" class="form-control form-control-sm mb-2" id="inputName5" placeholder="jumlah obat">
                      </div>
                      <div class="col-md-6">
                        <label for="inputName5">Petunjuk Pemakaian</label>
                        <input type="text" name="petunjuk_obat[]" class="form-control form-control-sm mb-2" id="inputName5" placeholder="Masukkan Petunjuk Pemakaian">
                      </div>
                      <div class="col-md-12">
                        <!-- <label for="inputName5">Catatan Interaksi Obat</label> -->
                        <input type="text" name="catatan_obat[]" value="-" hidden class="form-control form-control-sm mb-2" id="inputName5" placeholder="Masukkan Jumlah">
                      </div>
                      <div class="col-md-6">
                        <label for="inputName5">Jenis Obat</label>
                        <select name="jenis_obat[]" class=" form-control form-control-sm mb-2">
                          <option value="Jadi">Jadi</option>
                          <!-- <option value="Jadi">Jadi</option> -->
                        </select>
                      </div>

                      <div class="col-md-6">
                        <label for="inputCity">Durasi</label>
                        <div class="input-group mb-3">
                          <input type="text" name="durasi_obat[]" class="form-control form-control-sm" placeholder="Durasi" aria-describedby="basic-addon2">
                          <span class="input-group-text form-control form-control-sm" id="basic-addon2">Hari</span>
                        </div>
                      </div>
                    </div>
                    <button class="btn btn-sm btn-danger remove2" type="button"><i class="glyphicon glyphicon-remove"></i> Batal</button>
                    <button class="btn btn-sm btn-warning" onclick="document.getElementsByClassName('add-more2')[0].click()" type="button">
                      <i class="glyphicon glyphicon-plus"></i> Tambah Lagi
                    </button>
                    <hr>
                  </div>
                </div>

                <!-- <input type="hidden" name="id_pasien" value="<?php echo $pecah['idpasien'] ?>"> -->
                <input type="hidden" name="idrm" value="<?php echo $_GET['id'] ?>">
                <input type="hidden" class="form-control form-control-sm" id="inputName5" name="id" value="<?php echo $jadwal['idrawat'] ?>" placeholder="Masukkan Nama Pasien">
                <div class="modal-footer">
                  <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                  <input type="submit" class="btn btn-sm btn-primary" name="saveobnew" value="Save changes">
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Add data Modal Paket Obat Jadi -->
    <div class="modal fade" id="addPaketJadi" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Obat Paket</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="" method="POST">
            <div class="modal-body">
              <label for="" class="">Paket</label>
              <select name="paket" id="" class="form-select">
                <option value="" hidden>Pilih Paket</option>
                <?php
                $getPakek = $koneksi->query("SELECT * FROM puyerjadi GROUP BY nama_paket ORDER BY nama_paket ASC");
                foreach ($getPakek as $paket) {
                ?>
                  <option value="<?= $paket['id'] ?>"><?= $paket['nama_paket'] ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="modal-footer">
              <button type="submit" name="lihatObatJadi" class="btn btn-sm btn-success">Lihat</button>
              <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
              <input type="hidden" name="idrm" value="<?php echo $_GET['id'] ?>">
              <input type="hidden" class="form-control form-control-sm" id="inputName5" name="id" value="<?php echo $jadwal['idrawat'] ?>" placeholder="Masukkan Nama Pasien">
              <button type="submit" name="saveobpaketjadi" class="btn btn-sm btn-primary">Pakai</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <?php
    if (isset($_POST['lihatObatJadi'])) {
      echo "
      <script>
          document.location.href='index.php?halaman=daftarpuyerjadi&obat&id=$_POST[paket]';
      </script>
    ";
    }
    ?>
  <?php } ?>

  <?php
  $obat = $koneksi->query("SELECT * FROM obat_rm ORDER BY nama_obat ASC")->fetch_assoc();
  ?>

  <!-- Riwayat Rekam Medis -->
  <div class="card shadow p-3">
    <h5 class="card-title">Riwayat Rekam Medis</h5>
    <div class="table-responsive" style="max-height: 300px; overflow-y: scroll;">
      <table class="table table-sm table-hover table-striped" style="font-size: 12px;">
        <thead>
          <tr>
            <th>Nama</th>
            <th>Tgl</th>
            <th>Diagnosis</th>
            <th>Anamnesa</th>
            <th>KeluhanUtama</th>
            <th>Objective</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $getRM = $koneksi->query("SELECT *, DATE_FORMAT(jadwal, '%Y-%m-%d') as tgl FROM rekam_medis WHERE norm = '$_GET[id]' ORDER BY jadwal DESC");
          foreach ($getRM as $item) {
          ?>
            <tr>
              <td><?= $item['nama_pasien'] ?></td>
              <td><?= $item['jadwal'] ?></td>
              <?php $getRawat = $koneksi->query("SELECT * FROM registrasi_rawat WHERE no_rm = '$item[norm]' AND DATE_FORMAT(jadwal, '%Y-%m-%d') = '$item[tgl]' LIMIT 1")->fetch_assoc(); ?>
              <td><?= $item['diagnosis'] ?></td>
              <td><?= $item['anamnesa'] ?></td>
              <td><?= $item['keluhan_utama'] ?></td>
              <td><?= $item['objective'] ?></td>
              <td>
                <form method="POST">
                  <?php if ($_SESSION['admin']['level'] == 'rekam medis' or $_SESSION['admin']['level'] == 'sup') { ?>
                    <a href="index.php?halaman=editrm&id=<?= $item['id_rm'] ?>" targe="_blank" class="btn btn-sm btn-success"><i class="bi bi-pencil"></i></a>
                  <?php } ?>
                  <a href="index.php?halaman=detailrm&id=<?php echo $item["norm"]; ?>&tgl=<?php echo $item["tgl"]; ?>&rawat=<?php echo $getRawat["idrawat"]; ?>&cekrm&idrekammedis=<?= $item['id_rm'] ?>" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i></a>
                  <input type="text" name="idRawatSekarang" value="<?= $jadwal['idrawat'] ?>" hidden>
                  <input type="datetime-local" name="jadwalSekarang" value="<?= $jadwal['jadwal'] ?>" hidden>
                  <input type="text" name="idKJSekarang" value="<?= $pecah['id_rm'] ?>" hidden>
                  <input type="text" name="idRawatSumber" value="<?= $getRawat['idrawat'] ?>" hidden>
                  <input type="datetime-local" name="jadwalSumber" value="<?= $item['jadwal'] ?>" hidden>
                  <input type="datetime-local" name="jadwalSumberYmd" value="<?= $item['tgl'] ?>" hidden>
                  <input type="text" name="idRmSumber" value="<?= $item['id_rm'] ?>" hidden>
                  <button name="copy" onclick="return confirm('Apakah anda yakin ingin menyamakan RM sekarang dengan RM pada tanggal tersebut ???')" class="btn btn-sm btn-warning">Copy</button>
                </form>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</main><!-- End #main -->
<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
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

<?php
if (isset($_POST['save'])) {
  $jadwal = htmlspecialchars($_POST["jadwal"]);
  $status_perokok = htmlspecialchars($_POST["status_perokok"]);
  $anamnesa = htmlspecialchars($_POST["anamnesa"]);
  $diagnosis = ($_POST['diagnosis'] != 'Diagnosis Baru') ? htmlspecialchars($_POST['diagnosis']) : htmlspecialchars($_POST['diagnosis_new']);
  $prognosa = htmlspecialchars($_POST["prognosa"]);
  $icd = htmlspecialchars($_POST["icd"]);
  $status_pulang = htmlspecialchars($_POST["status_pulang"]);
  $id_pasien = htmlspecialchars($_POST["id_pasien"]);
  $gula_darah = htmlspecialchars($_POST['gula_darah']);
  $kolestrol = htmlspecialchars($_POST['kolestrol']);
  $asam_urat = htmlspecialchars($_POST['asam_urat']);
  $objective = htmlspecialchars($_POST['objective']);

  if ($_POST['diagnosis'] == 'Diagnosis Baru') {
    $koneksimaster->query("INSERT INTO icds_diagnosis (diagnosis, icd, unit, petugas) VALUES ('$diagnosis', '$icd', 'KHM 1', '" . $_SESSION['admin']['namalengkap'] . "')");
  }

  if ($prognosa == 'Prognosis good') {
    $prognosacode = '170968001';
  } elseif ($prognosa == 'Guarded prognosis') {
    $prognosacode = '170969009';
  } elseif ($prognosa == 'Fair prognosis') {
    $prognosacode = '170970005';
  } else {
    $prognosacode = '170968001';
  }

  $koneksi->query("INSERT INTO rekam_medis(nama_pasien, norm, jadwal, status_perokok, anamnesa, diagnosis, prognosa, icd, status_plg, id_pasien, gol_darah, dokter, kode_prognosa, objective) VALUES ('$_POST[nama_pasien]', '$_GET[id]','$jadwal', '$status_perokok', '$anamnesa', '$diagnosis', '$prognosa', '$icd', '$status_pulang', '$id_pasien', '$_POST[gol_darah]', '$_POST[dokter]', '$prognosacode', '$objective')");

  $koneksi->query("INSERT INTO lab_poli (nama_pasien, jadwal, gula_darah, kolestrol, asam_urat) VALUES ('$_POST[nama_pasien]', '$jadwal', '$gula_darah', '$kolestrol', '$asam_urat')");

  $koneksi->query("UPDATE registrasi_rawat SET status_antri='Pembayaran' WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]'");

  if (isset($_GET['inap'])) {
    // $koneksi->query("UPDATE kajian_awal_inap SET  gol_darah = '$_POST[gol_darah]', anamnesa = '$_POST[anamnesa]', diagnosis = '$_POST[diagnosis]', icd = '$_POST[icd]', status_perokok = '$_POST[status_perokok]', status_plg = '$_POST[status_pulang]' WHERE norm = '$_GET[id]' AND jadwal = '$_POST[jadwal]'");
  }

  if (isset($_GET['inap'])) {
    echo "
      <script>
        alert('Successfully to add data');
        document.location.href='index.php?halaman=rmedis&inap&id=$_GET[id]&tgl=$_GET[tgl]';
      </script>
    ";
  } else {
    echo "
      <script>
        alert('Successfully to add data');
        document.location.href='index.php?halaman=rmedis&id=$_GET[id]&tgl=$_GET[tgl]';
      </script>
    ";
  }
}

if (isset($_POST['editrm'])) {
  $jadwal = htmlspecialchars($_POST["jadwal"]);
  $status_perokok = htmlspecialchars($_POST["status_perokok"]);
  $anamnesa = htmlspecialchars($_POST["anamnesa"]);
  $diagnosis = ($_POST['diagnosis'] == 'Diagnosis Baru') ? htmlspecialchars($_POST['diagnosis']) : htmlspecialchars($_POST['diagnosis_new']);
  $prognosa = htmlspecialchars($_POST["prognosa"]);
  $icd = htmlspecialchars($_POST["icd"]);
  $status_pulang = htmlspecialchars($_POST["status_pulang"]);
  $id_pasien = htmlspecialchars($_POST["id_pasien"]);

  if ($prognosa == 'Prognosis good') {
    $prognosacode = '170968001';
  } elseif ($prognosa == 'Guarded prognosis') {
    $prognosacode = '170969009';
  } elseif ($prognosa == 'Fair prognosis') {
    $prognosacode = '170970005';
  } else {
    $prognosacode = '170968001';
  }

  $koneksi->query("UPDATE rekam_medis SET  gol_darah = '$_POST[gol_darah]', anamnesa = '$_POST[anamnesa]', diagnosis = '$diagnosis', prognosa = '$_POST[prognosa]' , icd = '$_POST[icd]', status_perokok = '$_POST[status_perokok]', status_plg ='$status_pulang', dokter = '$_POST[dokter]', kode_prognosa = '$prognosacode' WHERE norm = '$_GET[id]' AND DATE_FORMAT(jadwal, '%Y-%m-%d') = '$_GET[tgl]';");

  $koneksi->query("UPDATE kajian_awal SET keluhan_utama='$_POST[keluhan_utama]', riwayat_penyakit='$_POST[riwayat_penyakit]', riwayat_alergi='$_POST[riwayat_alergi]', suhu_tubuh='$_POST[suhu_tubuh]', oksigen='$_POST[oksigen]', sistole='$_POST[sistole]', distole='$_POST[distole]', nadi='$_POST[nadi]', frek_nafas='$_POST[frek_nafas]', nama_vaksin = '$_POST[nama_vaksin]', tgl_vaksin = '$_POST[tgl_vaksin]' WHERE norm='$_GET[id]' AND tgl_rm = '$_GET[tgl]';");

  if (isset($_GET['inap'])) {
    echo "
      <script>
          document.location.href='index.php?halaman=rmedis&inap&id=$_GET[id]&tgl=$_GET[tgl]';
      </script>
    ";
  } else {
    echo "
      <script>
          document.location.href='index.php?halaman=rmedis&id=$_GET[id]&tgl=$_GET[tgl]';
      </script>
    ";
  }
}

if (isset($_GET['hapus'])) {
  // Mengambil Obat RM dan Data Stok Sekarang
  $getObatById = $koneksi->query("SELECT * FROM obat_rm WHERE idobat= '$_GET[id]' LIMIT 1")->fetch_assoc();
  $ObatKode = $koneksi->query("SELECT id_obat, jml_obat, nama_obat FROM apotek WHERE nama_obat= '$getObatById[nama_obat]'")->fetch_assoc();

  // Membuat Penambahan obat yang sebelumnya di kurangi saat nambah obat
  $stokAkhir = $ObatKode['jml_obat'] + $getObatById['jml_dokter'];

  // Obat Jmlah obat sesuai dengan stok yang sudah di kembalikan
  // $koneksi->query("UPDATE apotek SET jml_obat = '$stokAkhir' WHERE id_obat = '$ObatKode[id_obat]'");
  $koneksi->query("DELETE FROM obat_rm WHERE idobat = '$_GET[id]'");

  if (isset($_GET['inap'])) {
    echo "
      <script>
        document.location.href='index.php?halaman=rmedis&inap&id=$_GET[rm]&tgl=$_GET[tgl]';
      </script>
    ";
  } else {
    echo "
      <script>
        document.location.href='index.php?halaman=rmedis&id=$_GET[rm]&tgl=$_GET[tgl]';
      </script>
    ";
  }
}

if (isset($_POST['savelay'])) {
  $layanan = $_POST['layanan'];
  $kode_layanan = $_POST['kode_layanan'];
  $jumlah_layanan = $_POST['jumlah_layanan'];
  $id_pasien = $_POST['id_pasien'];
  $idrm = $_POST['idrm'];

  if ($_POST['layanan'] == 'lain-lain') {
    $koneksi->query("INSERT INTO layanan (layanan, kode_layanan, jumlah_layanan, id_pasien, idrm) VALUES ('$_POST[layanan2]', '-', '$jumlah_layanan', '$id_pasien', '$_GET[id]')");

    $cekPemLay = $koneksi->query("SELECT * FROM registrasi_rawat WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' limit 1")->fetch_assoc();

    $getBiyLain = $koneksi->query("SELECT * FROM biaya_rawat WHERE idregis = '$cekPemLay[idrawat]' limit 1")->fetch_assoc();

    if ($getBiyLain['biaya_lain'] == '') {
      $biyLain = $getBiyLain['biaya_lain'] . $_POST['layanan2'];
    } else {
      $biyLain = $getBiyLain['biaya_lain'] . ',' . $_POST['layanan2'];
    }

    $ttlBiyLain = intval($getBiyLain['total_lain']) + intval($_POST['harga_layanan']);

    $koneksi->query("UPDATE biaya_rawat SET biaya_lain = '$biyLain', total_lain = '$ttlBiyLain' WHERE idregis='$cekPemLay[idrawat]'");
  } else {
    $koneksi->query("INSERT INTO layanan (layanan, kode_layanan, jumlah_layanan, id_pasien, idrm) VALUES ('$layanan', '-', '$jumlah_layanan', '$id_pasien', '$_GET[id]')");

    $cekPemLay = $koneksi->query("SELECT * FROM registrasi_rawat WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' limit 1")->fetch_assoc();

    $getBiyLain = $koneksi->query("SELECT * FROM biaya_rawat WHERE idregis = '$cekPemLay[idrawat]' limit 1")->fetch_assoc();

    if ($getBiyLain['biaya_lain'] == '') {
      $biyLain = $getBiyLain['biaya_lain'] . $_POST['layanan'];
    } else {
      $biyLain = $getBiyLain['biaya_lain'] . ',' . $_POST['layanan'];
    }

    $ttlBiyLain = intval($getBiyLain['total_lain']) + intval($_POST['harga_layanan']);

    $koneksi->query("UPDATE biaya_rawat SET biaya_lain = '$biyLain', total_lain = '$ttlBiyLain' WHERE idregis='$cekPemLay[idrawat]'");
  }

  // echo "<meta http-equiv='refresh' content='1;url=index.php?halaman=rmedis&norm=".$_GET[norm]."'>";
  if (isset($_GET['inap'])) {
    echo "
        <script>
            document.location.href='index.php?halaman=rmedis&inap&id=$_GET[id]&tgl=$_GET[tgl]';
        </script>
      ";
  } else {
    echo "
        <script>
            document.location.href='index.php?halaman=rmedis&id=$_GET[id]&tgl=$_GET[tgl]';
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
  $koneksi->query("UPDATE registrasi_rawat SET end='$end', kasir='$username' WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]';");

  $cekPemOb = $koneksi->query("SELECT * FROM registrasi_rawat WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' limit 1")->fetch_assoc();

  if ($cekPemOb['carabayar'] == 'umum') {
    $koneksi->query("UPDATE biaya_rawat SET poli = '35000' WHERE idregis='$cekPemOb[idrawat]'");
  } elseif ($cekPemOb['carabayar'] == 'malam') {
    $koneksi->query("UPDATE biaya_rawat SET poli = '50000' WHERE idregis='$cekPemOb[idrawat]'");
  } elseif ($cekPemOb['carabayar'] == 'spesialis penyakit dalam' or $cekPemOb['carabayar'] == 'spesialis anak') {
    $koneksi->query("UPDATE biaya_rawat SET poli = '300000' WHERE idregis='$cekPemOb[idrawat]'");
  }

  for ($i = 0; $i < count($nama) - 1; $i++) {
    foreach ($_POST['catatan_obat'] as $catatan_obat) {
      foreach ($_POST['dosis1_obat'] as $value2) {
        foreach ($_POST['dosis2_obat'] as $value3) {
          foreach ($_POST['per_obat'] as $per_obat) {
            foreach ($_POST['durasi_obat'] as $durasi_obat) {
              foreach ($_POST['petunjuk_obat'] as $petunjuk_obat) {
                foreach ($_POST['jenis_obat'] as $jenis_obat) {
                  if (isset($_GET['inap'])) {
                    $ObatKode = $koneksi->query("SELECT harga_beli, margininap, nama_obat, id_obat FROM apotek WHERE tipe != '' AND id_obat= '" . $nama[$i] . "'")->fetch_assoc();
                    // $stokAkhir = $ObatKode['jml_obat'] - $jml_dokter[$i];
                    $m = $ObatKode['margininap'] ?? 0;
                    if ($m < 100) {
                      $margin = 1.30;
                    } else {
                      $margin = $m / 100;
                    }
                    $harga = $ObatKode['harga_beli'] * $margin * $jml_dokter[$i];
                    if (isset($_GET['inap'])) {
                      date_default_timezone_set('Asia/Jakarta');
                      $tanggal = date('Y-m-d');
                      $biaya = 'biayaobat';
                      $id = $_POST["id"];
                      $resep = 'Resep' . ' ' . $id;

                      $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran, ket, petugas ) VALUES ('$_POST[id]', '$tanggal', '$biaya', '$harga', '$resep', '$username') ");
                    }
                    // $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran, ket, petugas, shiftinap) VALUES ('$jadwal[idrawat]','" . date('Y-m-d') . "','Obat Inap Apotek " . $nama[$i] . "', '$harga','" . $nama[$i] . "','" . $_SESSION['admin']['username'] . "', '" . $_SESSION['shift'] . "')");
                    $subtotal += $harga;
                  } else {
                    $ObatKode = $koneksi->query("SELECT id_obat, jml_obat, nama_obat FROM apotek WHERE tipe != '' AND id_obat= '" . $nama[$i] . "'")->fetch_assoc();
                    $stokAkhir = $ObatKode['jml_obat'] - $jml_dokter[$i];
                  }

                  $koneksi->query("INSERT INTO obat_rm SET catatan_obat = '$catatan_obat', kode_obat = '$nama[$i]', nama_obat = '$ObatKode[nama_obat]', jml_dokter = '$jml_dokter[$i]', dosis1_obat = '$value2', dosis2_obat = '$value3', per_obat = '$per_obat', durasi_obat = '$durasi_obat', petunjuk_obat = '$petunjuk_obat', jenis_obat = '$jenis_obat', idrm = '$_GET[id]', tgl_pasien = '$_GET[tgl]', rekam_medis_id = '$getLastRM[id_rm]', racik = '$_POST[racik]';");
                }
              }
            }
          }
        }
      }
    }
  }
  if (isset($_GET['inap'])) {
    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date('Y-m-d');
    $biaya = 'biayaobat';
    $id = $_POST["id"];
    $resep = 'Resep' . ' ' . $id;

    $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran, ket, petugas ) VALUES ('$_POST[id]', '$tanggal', '$biaya', '$subtotal', '$resep', '$username') ");
  }

  if (isset($_GET['inap'])) {
    echo "
      <script>
          document.location.href='index.php?halaman=rmedis&inap&id=$_GET[id]&tgl=$_GET[tgl]';
      </script>
    ";
  } else {
    echo "
      <script>
          document.location.href='index.php?halaman=rmedis&id=$_GET[id]&tgl=$_GET[tgl]';
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
  $petunjuk_obat = $_POST['petunjuk_obat'];
  $jenis_obat = $_POST['jenis_obat'];

  $end = date("H:i:s");
  $koneksi->query("UPDATE registrasi_rawat SET end='$end', kasir='$username' WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]';");

  $cekPemOb = $koneksi->query("SELECT * FROM registrasi_rawat WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' limit 1")->fetch_assoc();

  if ($cekPemOb['carabayar'] == 'umum') {
    $koneksi->query("UPDATE biaya_rawat SET poli = '35000' WHERE idregis='$cekPemOb[idrawat]'");
  } elseif ($cekPemOb['carabayar'] == 'malam') {
    $koneksi->query("UPDATE biaya_rawat SET poli = '50000' WHERE idregis='$cekPemOb[idrawat]'");
  } elseif ($cekPemOb['carabayar'] == 'spesialis penyakit dalam' or $cekPemOb['carabayar'] == 'spesialis anak') {
    $koneksi->query("UPDATE biaya_rawat SET poli = '300000' WHERE idregis='$cekPemOb[idrawat]'");
  }

  for ($i = 0; $i < count($nama) - 1; $i++) {
    if (isset($_GET['inap'])) {
      $ObatKode = $koneksi->query("SELECT harga_beli, margininap, nama_obat, id_obat FROM apotek WHERE id_obat= '" . $nama[$i] . "'")->fetch_assoc();
      // $stokAkhir = $ObatKode['jml_obat'] - $jml_dokter[$i];
      $m = $ObatKode['margininap'] ?? 0;
      if ($m < 100) {
        $margin = 1.30;
      } else {
        $margin = $m / 100;
      }
      $harga = $ObatKode['harga_beli'] * $margin * $jml_dokter[$i];
      if (isset($_GET['inap'])) {
        date_default_timezone_set('Asia/Jakarta');
        $tanggal = date('Y-m-d');
        $biaya = 'biayaobat';
        $id = $_POST["id"];
        $resep = 'Resep' . ' ' . $id;

        $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran, ket, petugas ) VALUES ('$_POST[id]', '$tanggal', '$biaya', '$harga', '$resep', '$username') ");
      }
      // $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran, ket, petugas, shiftinap) VALUES ('$jadwal[idrawat]','" . date('Y-m-d') . "','Obat Inap Apotek " . $nama[$i] . "', '$harga','" . $nama[$i] . "','" . $_SESSION['admin']['username'] . "', '" . $_SESSION['shift'] . "')");
      $subtotal += $harga;
    } else {
      $ObatKode = $koneksi->query("SELECT id_obat, jml_obat, nama_obat FROM apotek WHERE tipe != '' AND id_obat= '" . $nama[$i] . "'")->fetch_assoc();
      $stokAkhir = $ObatKode['jml_obat'] - $jml_dokter[$i];
    }

    $koneksi->query("INSERT INTO obat_rm SET catatan_obat = '$catatan_obat[$i]', kode_obat = '$nama[$i]', nama_obat = '$ObatKode[nama_obat]', jml_dokter = '$jml_dokter[$i]', dosis1_obat = '$dosis1_obat[$i]', dosis2_obat = '$dosis2_obat[$i]', per_obat = '$per_obat[$i]', durasi_obat = '$durasi_obat[$i]', petunjuk_obat = '$petunjuk_obat[$i]', jenis_obat = '$jenis_obat[$i]', tgl_pasien = '$_GET[tgl]', rekam_medis_id = '$getLastRM[id_rm]', idrm = '$_GET[id]'");
  }


  if (isset($_GET['inap'])) {
    echo "
      <script>
          document.location.href='index.php?halaman=rmedis&inap&id=$_GET[id]&tgl=$_GET[tgl]';
      </script>
    ";
  } else {
    echo "
      <script>
          document.location.href='index.php?halaman=rmedis&id=$_GET[id]&tgl=$_GET[tgl]';
      </script>
    ";
  }
}

if (isset($_POST['saveobpaketjadi'])) {
  $getPaketObat = $koneksi->query("SELECT puyerjadi_detail.* FROM puyerjadi_detail WHERE puyer_id = '" . htmlspecialchars($_POST['paket']) . "'");

  $cekPemOb = $koneksi->query("SELECT * FROM registrasi_rawat WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' limit 1")->fetch_assoc();
  if ($cekPemOb['carabayar'] == 'umum') {
    $koneksi->query("UPDATE biaya_rawat SET poli = '35000' WHERE idregis='$cekPemOb[idrawat]'");
  } elseif ($cekPemOb['carabayar'] == 'malam') {
    $koneksi->query("UPDATE biaya_rawat SET poli = '50000' WHERE idregis='$cekPemOb[idrawat]'");
  } elseif ($cekPemOb['carabayar'] == 'spesialis penyakit dalam' or $cekPemOb['carabayar'] == 'spesialis anak') {
    $koneksi->query("UPDATE biaya_rawat SET poli = '300000' WHERE idregis='$cekPemOb[idrawat]'");
  }

  foreach ($getPaketObat as $obat) {
    if (isset($_GET['inap'])) {
      $ObatKode = $koneksi->query("SELECT harga_beli, margininap, nama_obat, id_obat FROM apotek WHERE id_obat= '" . $obat['kode_obat'] . "'")->fetch_assoc();
      // $stokAkhir = $ObatKode['jml_obat'] - $jml_dokter[$i];
      $m = $ObatKode['margininap'] ?? 0;
      if ($m < 100) {
        $margin = 1.30;
      } else {
        $margin = $m / 100;
      }
      $harga = $ObatKode['harga_beli'] * $margin * $obat['jumlah'];
      if (isset($_GET['inap'])) {
        date_default_timezone_set('Asia/Jakarta');
        $tanggal = date('Y-m-d');
        $biaya = 'biayaobat';
        $id = $_POST["id"];
        $resep = 'Resep' . ' ' . $id;

        $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran, ket, petugas ) VALUES ('$_POST[id]', '$tanggal', '$biaya', '$harga', '$resep', '$username') ");
      }
      // $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran, ket, petugas, shiftinap) VALUES ('$jadwal[idrawat]','" . date('Y-m-d') . "','Obat Inap Apotek " . $nama[$i] . "', '$harga','" . $nama[$i] . "','" . $_SESSION['admin']['username'] . "', '" . $_SESSION['shift'] . "')");
      $subtotal += $harga;
    } else {
      $ObatKode = $koneksi->query("SELECT id_obat, jml_obat, nama_obat FROM apotek WHERE tipe != '' AND nama_obat= '" . $obat['nama_obat'] . "'")->fetch_assoc();
      $stokAkhir = $ObatKode['jml_obat'] - $jml_dokter[$i];
    }

    $koneksi->query("INSERT INTO obat_rm SET catatan_obat = '$obat[ctt_obat]', nama_obat = '$obat[nama_obat]', kode_obat = '$obat[kode_obat]', jml_dokter = '$obat[jumlah]', dosis1_obat = '$obat[dosis1]', dosis2_obat = '$obat[dosis2]', per_obat = '$obat[per]', durasi_obat = '$obat[durasi]', petunjuk_obat = '$obat[petunjuk_pemakaian]', jenis_obat = 'Jadi', tgl_pasien = '$_GET[tgl]', rekam_medis_id = '$getLastRM[id_rm]', idrm = '$_GET[id]'");
  }

  if (isset($_GET['inap'])) {
    echo "
      <script>
          document.location.href='index.php?halaman=rmedis&inap&id=$_GET[id]&tgl=$_GET[tgl]';
      </script>
    ";
  } else {
    echo "
      <script>
          document.location.href='index.php?halaman=rmedis&id=$_GET[id]&tgl=$_GET[tgl]';
      </script>
    ";
  }
}

if (isset($_POST['edt'])) {
  $catatan_obat = $_POST['catatan_obat'];
  $nama = $_POST['nama_obat'];
  $jml_dokter = $_POST['jml_dokter'];
  $dosis1_obat = $_POST['dosis1_obat'];
  $dosis2_obat = $_POST['dosis2_obat'];
  $per_obat = $_POST['per_obat'];
  $durasi_obat = $_POST['durasi_obat'];
  $petunjuk_obat = $_POST['petunjuk_obat'];
  $idrm = $_POST['idrm'];

  $end = date("H:i:s");

  // Mengambil Obat RM dan Data Stok Sekarang
  $getObatById = $koneksi->query("SELECT * FROM obat_rm WHERE idobat= '$_POST[id_obat_sebelum]' LIMIT 1")->fetch_assoc();
  $ObatKodeUp = $koneksi->query("SELECT id_obat, jml_obat, nama_obat FROM apotek WHERE nama_obat= '$getObatById[nama_obat]'")->fetch_assoc();

  // Membuat Penambahan obat yang sebelumnya di kurangi saat nambah obat
  $stokAkhirUp = $ObatKodeUp['jml_obat'] + $getObatById['jml_dokter'];

  // Obat Jmlah obat sesuai dengan stok yang sudah di kembalikan
  // $koneksi->query("UPDATE apotek SET jml_obat = '$stokAkhirUp' WHERE id_obat = '$ObatKodeUp[id_obat]'");

  $koneksi->query("UPDATE registrasi_rawat SET end='$end' WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]';");
  $ObatKode = $koneksi->query("SELECT id_obat, jml_obat, nama_obat FROM apotek WHERE nama_obat= '" . $nama . "'")->fetch_assoc();
  $stokAkhir = $ObatKode['jml_obat'] - $jml_dokter;
  // $koneksi->query("UPDATE apotek SET jml_obat = '$stokAkhir' WHERE id_obat = '$ObatKode[id_obat]'");


  $koneksi->query("UPDATE obat_rm SET catatan_obat = '$catatan_obat', nama_obat = '$nama', kode_obat = '$ObatKode[id_obat]', jml_dokter = '$jml_dokter', dosis1_obat = '$dosis1_obat', dosis2_obat = '$dosis2_obat', per_obat = '$per_obat', durasi_obat = '$durasi_obat', petunjuk_obat = '$petunjuk_obat', jenis_obat = '$_POST[jenis_obat]', idrm = '$idrm' WHERE idobat = '$_POST[id]'
  ");

  if (isset($_GET['inap'])) {
    echo "
      <script>
          document.location.href='index.php?halaman=rmedis&inap&id=$_GET[id]&tgl=$_GET[tgl]';
      </script>
    ";
  } else {
    echo "
      <script>
          document.location.href='index.php?halaman=rmedis&id=$_GET[id]&tgl=$_GET[tgl]';
      </script>
    ";
  }
}

if (isset($_POST['copy'])) {
  $copy_rm = $koneksi->query("SELECT * FROM rekam_medis WHERE norm = '$_GET[id]' AND jadwal = '$_POST[jadwalSumber]'")->fetch_assoc();
  $copy_labpoli = $koneksi->query("SELECT * FROM lab_poli WHERE nama_pasien='$copy_rm[nama_pasien]' AND jadwal = '$_POST[jadwalSumber]' ORDER BY created_at DESC LIMIT 1")->fetch_assoc();

  $jadwal = htmlspecialchars($_POST['jadwalSekarang']);
  $status_perokok = htmlspecialchars($copy_rm["status_perokok"]);
  $anamnesa = htmlspecialchars($copy_rm["anamnesa"]);
  $diagnosis = htmlspecialchars($copy_rm["diagnosis"]);
  $prognosa = htmlspecialchars($copy_rm["prognosa"]);
  $icd = htmlspecialchars($copy_rm["icd"]);
  $status_pulang = htmlspecialchars($copy_rm["status_pulang"]);
  $id_pasien = htmlspecialchars($copy_rm["id_pasien"]);

  $gula_darah = htmlspecialchars($copy_labpoli['gula_darah']);
  $kolestrol = htmlspecialchars($copy_labpoli['kolestrol']);
  $asam_urat = htmlspecialchars($copy_labpoli['asam_urat']);

  $koneksi->query("INSERT INTO rekam_medis(nama_pasien, norm, jadwal, status_perokok, anamnesa, diagnosis, prognosa, icd, status_plg, id_pasien, gol_darah, dokter, gula_darah, kolestrol, asam_urat) VALUES ('$copy_rm[nama_pasien]', '$_GET[id]','$jadwal', '$status_perokok', '$anamnesa', '$diagnosis', '$prognosa', '$icd', '$status_pulang', '$id_pasien', '$copy_rm[gol_darah]', '$copy_rm[dokter]', '$gula_darah', '$kolestrol', '$asam_urat')");

  $koneksi->query("INSERT INTO lab_poli (nama_pasien, jadwal, gula_darah, kolestrol, asam_urat) VALUES ('$copy_rm[nama_pasien]', '$jadwal', '$gula_darah', '$kolestrol', '$asam_urat')");

  $koneksi->query("UPDATE registrasi_rawat SET status_antri='Pembayaran' WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]'");

  if (isset($_GET['inap'])) {
    // $koneksi->query("UPDATE kajian_awal_inap SET  gol_darah = '$_POST[gol_darah]', anamnesa = '$_POST[anamnesa]', diagnosis = '$_POST[diagnosis]', icd = '$_POST[icd]', status_perokok = '$_POST[status_perokok]', medika = '$_POST[medika]', nonmedika = '$_POST[nonmedika]', status_plg = '$_POST[status_pulang]' WHERE norm = '$_GET[id]' AND jadwal = '$_POST[jadwal]'");
  }

  $getLay = $koneksi->query("SELECT * FROM layanan WHERE idrm='$_POST[idRmSumber]' AND DATE_FORMAT(tgl_layanan, '%Y-%m-%d') = '$_POST[jadwalSumberYmd]'");
  foreach ($getLay as $dataLay) {
    $layanan = $dataLay['layanan'];
    $kode_layanan = $dataLay['kode_layanan'];
    $jumlah_layanan = $dataLay['jumlah_layanan'];
    $id_pasien = $dataLay['id_pasien'];
    $idrm = $_POST['idKJSekarang'];
    $koneksi->query("INSERT INTO layanan (layanan, kode_layanan, jumlah_layanan, id_pasien, idrm) VALUES ('$layanan', '-', '$jumlah_layanan', '$id_pasien', '$_GET[id]')");

    $cekPemLay = $koneksi->query("SELECT * FROM registrasi_rawat WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' limit 1")->fetch_assoc();
    $getBiyLain = $koneksi->query("SELECT * FROM biaya_rawat WHERE idregis = '$cekPemLay[idrawat]' limit 1")->fetch_assoc();

    if ($getBiyLain['biaya_lain'] == '') {
      $biyLain = $getBiyLain['biaya_lain'] . $_POST['layanan'];
    } else {
      $biyLain = $getBiyLain['biaya_lain'] . ',' . $_POST['layanan'];
    }

    $ttlBiyLain = intval($getBiyLain['total_lain']) + intval($_POST['harga_layanan']);

    $koneksi->query("UPDATE biaya_rawat SET biaya_lain = '$biyLain', total_lain = '$ttlBiyLain' WHERE idregis='$cekPemLay[idrawat]'");
  }
  $koneksi->query("UPDATE registrasi_rawat SET end='$end', kasir='$username' WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]';");

  $getOb = $koneksi->query("SELECT * FROM obat_rm WHERE idrm = '$_POST[idRmSumber]' AND DATE_FORMAT(tgl_pasien, '%Y-%m-%d') = '$_POST[jadwalSumberYmd]'");
  foreach ($getOb as $dataOb) {
    $catatan_obat = $dataOb['catatan_obat'];
    $nama = $dataOb['nama_obat'];
    $jml_dokter = $dataOb['jml_dokter'];
    $dosis1_obat = $dataOb['dosis1_obat'];
    $dosis2_obat = $dataOb['dosis2_obat'];
    $per_obat = $dataOb['per_obat'];
    $durasi_obat = $dataOb['durasi_obat'];
    $petunjuk_obat = $dataOb['petunjuk_obat'];
    $idrm = $dataOb['idrm'];

    $end = date("H:i:s");
    $koneksi->query("UPDATE registrasi_rawat SET end='$end' WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]';");
    $ObatKode = $koneksi->query("SELECT id_obat, jml_obat, nama_obat FROM apotek WHERE nama_obat= '" . $nama . "'")->fetch_assoc();
    $stokAkhir = $ObatKode['jml_obat'] - $jml_dokter;
    // $koneksi->query("UPDATE apotek SET jml_obat = '$stokAkhir' WHERE id_obat = '$ObatKode[id_obat]'");

    $cekPemOb = $koneksi->query("SELECT * FROM registrasi_rawat WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' limit 1")->fetch_assoc();

    if ($cekPemOb['carabayar'] == 'umum') {
      $koneksi->query("UPDATE biaya_rawat SET poli = '35000' WHERE idregis='$cekPemOb[idrawat]'");
    } elseif ($cekPemOb['carabayar'] == 'malam') {
      $koneksi->query("UPDATE biaya_rawat SET poli = '50000' WHERE idregis='$cekPemOb[idrawat]'");
    } elseif ($cekPemOb['carabayar'] == 'spesialis penyakit dalam' or $cekPemOb['carabayar'] == 'spesialis anak') {
      $koneksi->query("UPDATE biaya_rawat SET poli = '300000' WHERE idregis='$cekPemOb[idrawat]'");
    }

    $koneksi->query("INSERT INTO obat_rm SET catatan_obat = '$catatan_obat', nama_obat = '$nama', kode_obat = '$ObatKode[id_obat]', jml_dokter = '$jml_dokter', dosis1_obat = '$dosis1_obat', dosis2_obat = '$dosis2_obat', per_obat = '$per_obat', durasi_obat = '$durasi_obat', petunjuk_obat = '$petunjuk_obat', jenis_obat = '$dataOb[jenis_obat]', rekam_medis_id = '$getLastRM[id_rm]', idrm = '$_GET[id]'");
  }
  echo "
    <script>
      alert('Data berhasil diubah');
      document.location.href='index.php?halaman=daftarrmedis';
    </script>
  ";
}

?>