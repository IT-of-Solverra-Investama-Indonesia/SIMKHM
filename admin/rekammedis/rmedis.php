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

$pas = $koneksi->query("SELECT * FROM pasien WHERE no_rm = '$_GET[id]' ORDER BY idpasien DESC LIMIT 1 ")->fetch_assoc();
$rm = $koneksi->query("SELECT * FROM rekam_medis WHERE rekam_medis.norm='$_GET[id]' AND DATE_FORMAT(jadwal, '%Y-%m-%d') = '$_GET[tgl]';")->fetch_assoc();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>KHM WONOREJO</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script> -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>





  <main>
    <div class="container">
      <div class="pagetitle">
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
        <div class="container">
          <div class="row">
            <div class="col-md-12">

              <div class="card" style="margin-top:10px">
                <div class="card-body col-md-12">
                  <h5 class="card-title">Data Pasien</h5>
                  <!-- Multi Columns Form -->
                  <div class="col-md-12">
                    <label for="inputName5" class="form-label">Nama Pasien</label>
                    <input type="text" class="form-control" name="nama_pasien" id="inputName5" value="<?php echo $jadwal['nama_pasien'] ?>" placeholder="Masukkan Nama Pasien">
                  </div>
                  <div class="col-md-12" style="margin-top: 10px; margin-bottom:20px;">
                    <label for="inputName5" class="form-label">Nomor BPJS</label>
                    <input type="text" class="form-control" name="no_bpjs" id="inputName5" value="<?php echo $pas['no_bpjs'] ?? '-' ?>" placeholder="Masukkan No BPJS Pasien">
                  </div>
                  <div class="col-md-12" style="margin-top: 0px; margin-bottom: 10px;">
                    <label for="inputName5" class="form-label">Jadwal</label>
                    <input type="datetime-local" class="form-control" id="inputName5" name="jadwal" value="<?php echo $jadwal['jadwal'] ?>" placeholder="Masukkan Nama Pasien">
                    <input type="hidden" class="form-control" id="inputName5" name="dokter" value="<?php echo $jadwal['dokter_rawat'] ?>" placeholder="Masukkan Nama Pasien">
                    <input type="hidden" class="form-control" id="inputName5" name="id" value="<?php echo $jadwal['idrawat'] ?>" placeholder="Masukkan Nama Pasien">
                  </div>

                </div>
              </div>

              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Subjective</h5>
                  <hr style="margin-bottom:2px">
                  <h6 class="card-title">Data Umum</h6>

                  <!-- Multi Columns Form -->
                  <div class="row">
                    <div class="col-md-6">
                      <label for="inputName5" class="form-label">Tanggal Lahir</label>
                      <input type="date" class="form-control" name="tgl_lahir" value="<?php echo $pas['tgl_lahir'] ?>" id="inputName5" placeholder="Masukkan Tanggal Pasien">
                    </div>
                    <div class="col-md-6">
                      <label for="inputName5" class="form-label">Jenis Kelamin</label>
                      <?php
                      if ($pas['jenis_kelamin'] == '1') {
                        $jk = 'Laki-Laki';
                      } elseif ($pas['jenis_kelamin'] == '2') {
                        $jk = 'Perempuan';
                      }
                      ?>
                      <input type="text" class="form-control" name="jenis_kelamin" value="<?php echo $jk ?>" id="inputName5" placeholder="Masukkan JK Pasien">
                    </div>
                    <!-- <div class="col-md-6" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Status</label>
                  <input type="text" class="form-control" name="status_nikah" value="<?php echo $pas['status_nikah'] ?>" id="inputName5" placeholder="Masukkan Status Pasien">
                </div> -->
                    <div class="col-md-6" style="margin-top:20px;">
                      <label for="inputName5" class="form-label">Pembiayaan</label>
                      <input type="text" class="form-control" name="pembiayaan" id="inputName5" value="<?php echo $pas['pembiayaan'] ?>" placeholder="Masukkan Pembiayaan Pasien">
                    </div>
                  </div>
                </div>
              </div>



              <div class="card">
                <div class="card-body">
                  <div style="margin-bottom:2px; margin-top:30px">
                    <h6 class="card-title">Data Kesehatan</h6>
                  </div>
                  <!-- Multi Columns Form -->
                  <div class="row">
                    <div id="tandaTandaVital">
                      <div class="row">
                        <div>
                          <h5 class="card-title">Tanda-Tanda Vital</h5>
                        </div>
                        <div class="col-md-6">
                          <label for="inputCity" class="form-label">Suhu Tubuh</label>
                          <div class="input-group mb-6" style="margin-bottom:10px">
                            <input type="text" class="form-control" placeholder="Suhu Tubuh" name="suhu_tubuh" aria-describedby="basic-addon2" value="<?php echo $suhu ?>">
                            <span class="input-group-text" id="basic-addon2">celcius</span>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <label for="inputCity" class="form-label">Saturasi Oksigen</label>
                          <div class="input-group mb-6" style="margin-bottom:10px">
                            <input type="text" class="form-control" placeholder="Saturasi Oksigen" name="oksigen" aria-describedby="basic-addon2" value="<?php echo $pecah['oksigen'] ?>">
                            <span class="input-group-text" id="basic-addon2">%</span>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <label for="inputCity" class="form-label">Sistole</label>
                          <div class="input-group mb-6" style="margin-bottom:10px">
                            <input type="text" class="form-control" placeholder="Tekanan Darah" name="sistole" aria-describedby="basic-addon2" value="<?php echo $pecah['sistole'] ?>">
                            <span class="input-group-text" id="basic-addon2">mmHg</span>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <label for="inputCity" class="form-label">Distole</label>
                          <div class="input-group mb-6" style="margin-bottom:10px">
                            <input type="text" class="form-control" placeholder="Tekanan Darah" name="distole" aria-describedby="basic-addon2" value="<?php echo $pecah['distole'] ?>">
                            <span class="input-group-text" id="basic-addon2">mmHg</span>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <label for="inputCity" class="form-label">Nadi</label>
                          <div class="input-group mb-6" style="margin-bottom:10px">
                            <input type="text" class="form-control" placeholder="Denyut Nadi" name="nadi" aria-describedby="basic-addon2" value="<?php echo $pecah['nadi'] ?>">
                            <span class="input-group-text" id="basic-addon2">kali/menit</span>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <label for="inputCity" class="form-label">Frekuensi Pernafasan</label>
                          <div class="input-group mb-6" style="margin-bottom:10px">
                            <input type="text" class="form-control" placeholder="Frekuensi Pernafasan" name="frek_nafas" aria-describedby="basic-addon2" value="<?php echo $pecah['frek_nafas'] ?>">
                            <span class="input-group-text" id="basic-addon2">kali/menit</span>
                          </div>
                          <br>
                        </div>
                        <div class="col-md-4">
                          <label for="inputCity" class="form-label">Gula Darah</label>
                          <div class="input-group mb-6" style="margin-bottom:10px">
                            <input type="text" class="form-control" placeholder="Gula Darah" name="gula_darah" aria-describedby="basic-addon2" value="<?php echo $pecah['dula_darah'] ?>">
                          </div>
                          <br>
                        </div>
                        <div class="col-md-4">
                          <label for="inputCity" class="form-label">Kolestrol</label>
                          <div class="input-group mb-6" style="margin-bottom:10px">
                            <input type="text" class="form-control" placeholder="Kolestrol" name="kolestrol" aria-describedby="basic-addon2" value="<?php echo $pecah['kolestrol'] ?>">
                          </div>
                          <br>
                        </div>
                        <div class="col-md-4">
                          <label for="inputCity" class="form-label">Asam Urat</label>
                          <div class="input-group mb-6" style="margin-bottom:10px">
                            <input type="text" class="form-control" placeholder="Asam Urat" name="asam_urat" aria-describedby="basic-addon2" value="<?php echo $pecah['asam_urat'] ?>">
                          </div>
                          <br>
                        </div>
                        <div class="col-md-6">
                          <label for="inputName5" class="form-label">Gol. Darah</label>
                          <input type="text" class="form-control" id="inputName5" name="gol_darah" value="<?php echo $pecah['gol_darah'] ?>" placeholder="Masukkan Gol Darah Pasien">
                        </div>
                        <div class="col-md-6">
                          <label for="inputName5" class="form-label">Status Perokok</label>
                          <select name="status_perokok" id="" class="form-control">
                            <option value="<?php echo $pecah['status_perokok'] ?>" hidden><?php echo $pecah['status_perokok'] ?></option>
                            <option value="Aktif">Aktif</option>
                            <option value="Pasif">Pasif</option>
                          </select>
                        </div>
                        <div class="col-md-12" style="margin-top:20px;">
                          <label for="inputName5" class="form-label">Riwayat Alergi</label>
                          <input type="text" class="form-control" id="inputName5" name="riwayat_alergi" value="<?php echo $pecah['riwayat_alergi'] ?>" placeholder="Masukkan Nama Pasien">
                        </div>
                        <div class="col-md-12" style="margin-top:20px;">
                          <label for="inputName5" class="form-label">Riwayat Penyakit</label>
                          <div class="input-group mb-3">
                            <input type="text" class="form-control" id="inputName5" name="riwayat_penyakit" value="<?php echo $pecah['riwayat_penyakit'] ?>" placeholder="Masukkan Nama Pasien">
                            <a href="#riwayatVaksi" class="btn btn-secondary float-end"><i class="bi bi-arrow-down"></i></a>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- <hr> -->


                    <!-- Ini yang mau Pindahkan Ke Dokter -->
                    <!-- <div>
                    <div class="col-md-6">
                     <label for="inputName5" class="form-label">Lidah</label>
                     <input type="text" class="form-control"  name="lidah" id="inputName5" placeholder="Lidah">
                    </div>
    
                    <div class="col-md-6">
                     <label for="inputName5" class="form-label">Langit-langit</label>
                     <input type="text" class="form-control"  name="langit_langit" id="inputName5" placeholder="Langit-langit">
                   </div>
    
                    <div class="col-md-6">
                     <label for="inputName5" class="form-label">Leher</label>
                     <input type="text" class="form-control"  name="leher" id="inputName5" placeholder="Leher">
                   </div>
    
                    <div class="col-md-6">
                     <label for="inputName5" class="form-label">Tenggorokan</label>
                     <input type="text" class="form-control"  name="tenggorokan" id="inputName5" placeholder="Tenggorokan">
                   </div>
    
                    <div class="col-md-6">
                     <label for="inputName5" class="form-label">Tonsil</label>
                     <input type="text" class="form-control"  name="tonsil" id="inputName5" placeholder="Tonsil">
                   </div>
    
                    <div class="col-md-6">
                     <label for="inputName5" class="form-label">Dada</label>
                     <input type="text" class="form-control"  name="dada" id="inputName5" placeholder="Dada">
                   </div>
    
                    <div class="col-md-6">
                     <label for="inputName5" class="form-label">Payudara</label>
                     <input type="text" class="form-control"  name="payudara" id="inputName5" placeholder="Payudara">
                   </div>
    
                    <div class="col-md-6">
                     <label for="inputName5" class="form-label">Punggung</label>
                     <input type="text" class="form-control"  name="punggung" id="inputName5" placeholder="Punggung">
                   </div>
    
                    <div class="col-md-6">
                     <label for="inputName5" class="form-label">Genital</label>
                     <input type="text" class="form-control"  name="genital" id="inputName5" placeholder="Genital">
                   </div>
    
                    <div class="col-md-6">
                     <label for="inputName5" class="form-label">Anus</label>
                     <input type="text" class="form-control"  name="anus" id="inputName5" placeholder="Anus">
                   </div>
    
                    <div class="col-md-6">
                     <label for="inputName5" class="form-label">Lengan Atas</label>
                     <input type="text" class="form-control"  name="lengan_atas" id="inputName5" placeholder="Lengan Atas">
                   </div>
    
                    <div class="col-md-6">
                     <label for="inputName5" class="form-label">Lengan Bawah</label>
                     <input type="text" class="form-control"  name="lengan_bawah" id="inputName5" placeholder="Lengan Bawah">
                   </div>
    
                    <div class="col-md-6">
                     <label for="inputName5" class="form-label">Jari Tangan</label>
                     <input type="text" class="form-control"  name="jari_tangan" id="inputName5" placeholder="Jari Tangan">
                   </div>
    
                    <div class="col-md-6">
                     <label for="inputName5" class="form-label">Kuku Tangan</label>
                     <input type="text" class="form-control"  name="kuku_tangan" id="inputName5" placeholder="Kuku Tangan">
                   </div>
    
                    <div class="col-md-6">
                     <label for="inputName5" class="form-label">Persendian Tangan</label>
                     <input type="text" class="form-control"  name="persendian_tangan" id="inputName5" placeholder="Persendian Tangan">
                   </div>
    
                   <div class="col-md-6">
                     <label for="inputName5" class="form-label">Tungkai Atas</label>
                     <input type="text" class="form-control"  name="tungkai_atas" id="inputName5" placeholder="Tungkai Atas">
                   </div>
    
                   <div class="col-md-6">
                     <label for="inputName5" class="form-label">Tungkai Bawah</label>
                     <input type="text" class="form-control"  name="tungkai_bawah" id="inputName5" placeholder="Tungkai Bawah">
                   </div>
    
                   <div class="col-md-6">
                     <label for="inputName5" class="form-label">Jari Kaki</label>
                     <input type="text" class="form-control"  name="jari_kaki" id="inputName5" placeholder="Jari Kaki">
                   </div>
    
                   <div class="col-md-6">
                     <label for="inputName5" class="form-label">Kuku Kaki</label>
                     <input type="text" class="form-control"  name="kuku_kaki" id="inputName5" placeholder="Kuku Kaki">
                   </div>
    
                   <div class="col-md-6">
                     <label for="inputName5" class="form-label">Persendian Kaki</label>
                     <input type="text" class="form-control"  name="persendian_kaki" id="inputName5" placeholder="Persendian Kaki">
                   </div> -->
                    <!-- </div>
                  </div> -->

                    <div id="riwayatVaksi">
                      <div class="row">
                        <div style="margin-bottom:2px; margin-top:30px">
                          <hr>
                          <h6 class="card-title">Riwayat Vaksinasi</h6>
                        </div>
                        <div class="col-md-6">
                          <label for="inputName5" class="form-label">Nama Vaksin</label>
                          <input type="text" class="form-control" id="inputName5" name="nama_vaksin" value="<?php echo $pecah['nama_vaksin'] ?>" placeholder="Masukkan Gol Darah Pasien">
                        </div>
                        <div class="col-md-6">
                          <label for="inputName5" class="form-label">Tanggal Pemberian</label>
                          <div class="input-group mb-3">
                            <input type="date" class="form-control" id="inputName5" name="tgl_vaksin" value="<?php echo $pecah['tgl_vaksin'] ?>" placeholder="Status Perokok">
                            <a href="#anamnesa" class="btn btn-secondary"><i class="bi bi-arrow-down"></i></a>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- Multi Columns Form -->
                    <div id="anamnesa">
                      <div class="row">
                        <div style="margin-bottom:2px; margin-top:30px">
                          <hr>
                          <h6 class="card-title">Anamnesa</h6>
                        </div>
                        <div class="col-md-12">
                          <label for="inputName5" class="form-label">Keluhan Utama</label>
                          <input type="text" class="form-control" id="inputName5" name="keluhan_utama" value="<?php echo $pecah['keluhan_utama'] ?>" placeholder="Masukkan Keluhan Pasien">
                        </div>
                        <div class="col-md-12" style="margin-bottom:50px; margin-top:20px;">
                          <label for="inputName5" class="form-label">Keluhan Tambahan</label>
                          <input type="text" class="form-control" id="inputName5" name="anamnesa" value="<?php echo $rm['anamnesa'] ?>" placeholder="Anamnesa">
                        </div>
                      </div>
                    </div>
                    <!-- Multi Columns Form -->
                  </div>
                </div>
              </div>

              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Assessment</h5>

                  <!-- Multi Columns Form -->
                  <div class="row">
                    <div class="form-group">
                      <label>Diagnosis</label>
                      <select name="diagnosis" required id="diagnosis_id" class="form-control">
                        <option value="">Pilih Diagnosis</option>
                        <option value="Diagnosis Baru">Diagnosis Baru</option>
                        <?php
                        $getAllDiagnosis = $koneksi->query("SELECT * FROM rekam_medis GROUP BY diagnosis ORDER BY diagnosis ASC");
                        foreach ($getAllDiagnosis as $allDiagnosis) {
                        ?>
                          <option value="<?= $allDiagnosis['diagnosis'] ?>"><?= $allDiagnosis['diagnosis'] ?></option>
                        <?php } ?>
                      </select>
                      <textarea type="text" name="diagnosis_new" id="diagnosis_new_id" class="form-control mt-2" style="height: 100px;" value="<?php echo $rm['diagnosis'] ?>" placeholder="Diagnosis"><?php echo $rm['diagnosis'] ?></textarea>
                    </div>
                    <div class="form-group" style="margin-top:20px">
                      <label>Prognosis</label>
                      <select name="prognosa" class="form-select">
                        <option value="<?= $rm['prognosa'] ?>"><?= $rm['prognosa'] ?></option>
                        <option value="Prognosis good">BONAM (BAIK)</option>
                        <option value="Guarded prognosis">MALAM (BURUK/JELEK)</option>
                        <option value="SANAM (SEMBUH)">SANAM (SEMBUH)</option>
                        <option value="Fair prognosis">DUBIA (TIDAK TENTU/RAGU-RAGU)</option>
                      </select>
                      <!-- <textarea type="text" name="prognosa" class="form-control" style="height: 100px;" value="<?php echo $pecah['prognosa'] ?>" placeholder="Prognosa"><?php echo $pecah['prognosa'] ?></textarea> -->
                    </div>
                    <div class="form-group">

                      <div class="col-md-12" style="margin-top:20px; margin-bottom: 20px;">

                        <label>ICD 10</label>

                        <select class="form-select" style="height: 20px;" id="selUser" aria-label="Default select example" name="icd">
                          <option value="<?= $rm['icd'] ?>"><?= $rm['icd'] ?></option>
                          <?php
                          $ambil2 = $koneksi->query("SELECT * FROM icds");
                          while ($perkat2 = $ambil2->fetch_assoc()) {
                          ?>
                            <option value="<?php echo $perkat2['code']; ?>"> <?php echo $perkat2['code']; ?> - <?php echo $perkat2['name_en']; ?> </option>
                          <?php } ?>
                        </select>

                      </div>

                    </div>
                  </div>

                  <script type="text/javascript">
                    $(document).ready(function() {
                      // Initialize select2
                      // $("#selUser").select2();
                      // $("#selObat1").select2({
                      //   dropdownParent: $('#exampleModal2')
                      // }); 
                      // $("#selCopy").select2({
                      //   dropdownParent: $('#exampleModal2')
                      // }); 

                    });
                  </script>

                  <script>
                    $(document).ready(function() {
                      $('#diagnosis_new_id').hide(); // Sembunyikan textarea
                      $('#selUser').select2();
                      $('#diagnosis_id').select2();
                      $('#diagnosis_id').on('change', function() {
                        const diagnosis = $(this).val();
                        if (diagnosis !== 'Diagnosis Baru') {
                          // Ambil data ICD berdasarkan diagnosis yang dipilih
                          $.ajax({
                            url: '../rekammedis/get_icd_api.php',
                            type: 'POST',
                            data: {
                              diagnosis: diagnosis
                            },
                            success: function(response) {
                              const icdData = JSON.parse(response);
                              const icdDropdown = $('#selUser');

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

                          $('#diagnosis_new_id').hide(); // Sembunyikan textarea jika diagnosis bukan baru
                        } else {
                          $('#diagnosis_new_id').show(); // Tampilkan textarea jika Diagnosis Baru
                          // Ambil data ICD berdasarkan diagnosis yang dipilih
                          $.ajax({
                            url: '../rekammedis/get_icd_api.php',
                            type: 'POST',
                            data: {
                              diagnosis: diagnosis
                            },
                            success: function(response) {
                              const icdData = JSON.parse(response);
                              const icdDropdown = $('#selUser');

                              // Hapus semua opsi sebelumnya, kecuali opsi pertama
                              icdDropdown.find('option').not(':first').remove();

                              // Tambahkan opsi baru ke dropdown ICD
                              icdData.forEach(icd => {
                                icdDropdown.append(
                                  `<option value="${icd.icd}">${icd.icd} - ${icd.name_en}</option>`
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




                  <!-- <div style="margin-bottom:2px; margin-top:30px">
              <hr>
              <h6 class="card-title">Medikametosa</h6>
              </div> -->

                  <!-- Multi Columns Form -->

                  <!-- <div class="form-group">

                <textarea type="text" name="medika" class="form-control" style="height: 200px;" value="<?php echo $pecah['medika'] ?>" placeholder="Masukkan Medikametosa"><?php echo $pecah['medika'] ?></textarea>

               </div>

              <div style="margin-bottom:2px; margin-top:30px">
              <hr>
              <h6 class="card-title">Non Medikametosa</h6>
              </div> -->

                  <!-- Multi Columns Form -->

                  <!-- <div class="form-group">

                <textarea type="text" name="nonmedika" class="form-control" style="height: 200px;" value="<?php echo $pecah['nonmedika'] ?>" placeholder="Masukkan Non Medikametosa"><?php echo $pecah['nonmedika'] ?></textarea>

               </div> -->

                  <div style="margin-bottom:2px; margin-top:30px">
                    <hr>
                    <h6 class="card-title">Status Pulang</h6>
                  </div>

                  <!-- Multi Columns Form -->

                  <div class="col-md-12">
                    <label for="inputState" class="form-label">Status Pulang</label>
                    <select id="inputState" name="status_pulang" class="form-select">
                      <option selected>Berobat Jalan</option>
                      <option>Berobat Jalan</option>
                      <option>Rawat Inap</option>
                    </select>
                  </div>


                  <!-- end -->

                  <!-- Add Data Modal Obat -->
                  <div class="modal fade" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Obat</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="post" enctype="multipart/form-data">
                          <div class="control-group after-add-more">
                            <!-- <div class="modal-body"> -->
                            <div class="row">
                              <div class="col-md-12">
                                <label for="inputName5" class="form-label">Nama Obat</label>
                                <!-- <input type="text" name="nama_obat" class="form-control" id="inputName5" placeholder="Layanan/Tindakan"> -->
                                <!-- <select name="nama_obat[]" id="" class="form-select"> -->
                                <select class="form-select" style="height: 20px;" aria-label="Default select example" name="nama_obat[]">
                                  <?php
                                  if (!isset($_GET['inap'])) {
                                    $getObat = $koneksi->query("SELECT * FROM apotek WHERE tipe = 'Rajal' GROUP BY nama_obat ORDER BY nama_obat ASC");
                                  } else {
                                    $getObat = $koneksi->query("SELECT * FROM apotek WHERE tipe = 'Ranap' GROUP BY nama_obat ORDER BY nama_obat ASC");
                                  }
                                  foreach ($getObat as $data) {
                                  ?>
                                    <option value="<?= $data['nama_obat'] ?>"><?= $data['nama_obat'] ?></option>
                                  <?php } ?>
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
                              <script>

                              </script>
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

                          <div class="copy invisible">
                            <br>
                            <div class="control-group">

                              <label for="inputName5" class="form-label">Nama Obat</label>
                              <!-- <input type="text" name="nama_obat" class="form-control" id="inputName5" placeholder="Layanan/Tindakan"> -->
                              <select class="form-select" aria-label="Default select example" name="nama_obat[]">
                                <?php
                                if (!isset($_GET['inap'])) {
                                  $getObat = $koneksi->query("SELECT * FROM apotek WHERE tipe = 'Rajal' GROUP BY nama_obat ORDER BY nama_obat ASC");
                                } else {
                                  $getObat = $koneksi->query("SELECT * FROM apotek WHERE tipe = 'Ranap' GROUP BY nama_obat ORDER BY nama_obat ASC");
                                }
                                foreach ($getObat as $data) {
                                ?>
                                  <option value="<?= $data['nama_obat'] ?>"><?= $data['nama_obat'] ?></option>
                                <?php } ?>
                              </select>

                              <!-- <label for="inputName5" class="form-label">Kode Obat</label>
                    <select name="kode_obat[]" id="" class="form-select">
                      <?php
                      $getObat = $koneksi->query("SELECT * FROM apotek ORDER BY nama_obat ASC");
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
                            <select name="jenis_obat[]" class="form-select">
                              <option value="Racik">Racik</option>
                              <!-- <option value="Jadi">Jadi</option> -->
                            </select>
                          </div>
                          <label for="inputName5" class="form-label">Dosis</label>
                          <div class="col-md-6">
                            <div class="input-group mb-6">
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
                          <!-- <input type="hidden" name="id_pasien" value="<?php echo $pecah['idpasien'] ?>"> -->
                          <input type="hidden" name="idrm" value="<?php echo $_GET['id'] ?>">

                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                      <input type="submit" class="btn btn-primary" name="saveob" value="Save changes">
                    </div>
      </form>
    </div>
    </div>
    </div>
    </div>

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
                      <label for="inputName5" class="form-label">Racik Ke-</label><br>
                      <input type="number" name="racik" class="form-control w-100" style="width:100%;" aria-label="Default select example">
                      <label for="inputName5" class="form-label">Nama Obat</label><br>
                      <!-- <input type="text" name="nama_obat" class="form-control" id="inputName5" placeholder="Layanan/Tindakan"> -->
                      <select name="nama_obat[]" class="form-control w-100" style="width:100%;" id="selObat1" aria-label="Default select example">
                        <option value="">Pilih</option>
                        <?php
                        if (!isset($_GET['inap'])) {
                          $getObat = $koneksi->query("SELECT * FROM apotek WHERE tipe = 'Rajal' GROUP BY nama_obat ORDER BY nama_obat ASC");
                        } else {
                          $getObat = $koneksi->query("SELECT * FROM apotek WHERE tipe = 'Ranap' GROUP BY nama_obat ORDER BY nama_obat ASC");
                        }
                        foreach ($getObat as $data) {
                        ?>
                          <option value="<?= $data['nama_obat'] ?>"><?= $data['nama_obat'] ?></option>
                        <?php } ?>
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
                      if (!isset($_GET['inap'])) {
                        $getObat = $koneksi->query("SELECT * FROM apotek WHERE tipe = 'Rajal' GROUP BY nama_obat ORDER BY nama_obat ASC");
                      } else {
                        $getObat = $koneksi->query("SELECT * FROM apotek WHERE tipe = 'Ranap' GROUP BY nama_obat ORDER BY nama_obat ASC");
                      }
                      foreach ($getObat as $data) {
                      ?>
                        <option value="<?= $data['nama_obat'] ?>"><?= $data['nama_obat'] ?></option>
                      <?php } ?>
                    </select>
                    <!-- <label for="inputName5" class="form-label">Kode Obat</label>
                      <select name="kode_obat[]" id="" class="form-select">
                          <?php
                          $getObat = $koneksi->query("SELECT * FROM apotek ORDER BY nama_obat ASC");
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
                  <select name="jenis_obat[]" class="form-select">
                    <option value="Racik">Racik</option>
                    <!-- <option value="Jadi">Jadi</option> -->
                  </select>
                </div>
                <label for="inputName5" class="form-label">Dosis</label>
                <div class="row">
                  <div class="col-md-6">
                    <div class="input-group">
                      <input type="text" class="form-control" name="dosis1_obat[]">
                      <input type="text" style="text-align: center;" class="form-control" placeholder="X">
                      <input type="text" class="form-control" name="dosis2_obat[]">
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
                <!-- <input type="hidden" name="id_pasien" value="<?php echo $pecah['idpasien'] ?>"> -->
                <input type="hidden" name="idrm" value="<?php echo $_GET['id'] ?>">
                <input type="hidden" class="form-control" id="inputName5" name="id" value="<?php echo $jadwal['idrawat'] ?>" placeholder="Masukkan Nama Pasien">
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

    <!-- Add Data Modal Tindakan -->
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
                <select name="layanan" class="form-control" id="selLay" onchange="SelLay(this)">
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
                <input type="text" name="layanan2" style="display: none;" class="form-control" id="inpLay" placeholder="Layanan/Tindakan Lain">
              </div>
              <div class="col-md-12" style="margin-top:20px">
                <label for="inputName5" class="form-label">Harga Layanan</label>
                <input type="text" name="harga_layanan" class="form-control" id="hrgLay" placeholder="Harga Layanan">
              </div>
              <div class="col-md-12" style="margin-top:0px; height: 0.1px; visibility : hidden;">
                <label for="inputName5" class="form-label">Jumlah</label>
                <input type="text" name="jumlah_layanan" value="1" class="form-control" id="inputName5" placeholder="Masukkan Jumlah">
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
                      <select name="nama_obat[]" class="form-control w-100" style="width:100%;" id="selObat1" aria-label="Default select example">
                        <option value="">Pilih</option>
                        <?php
                        if (!isset($_GET['inap'])) {
                          $getObat = $koneksi->query("SELECT * FROM apotek WHERE tipe = 'Rajal' GROUP BY nama_obat ORDER BY nama_obat ASC");
                        } else {
                          $getObat = $koneksi->query("SELECT * FROM apotek WHERE tipe = 'Ranap' GROUP BY nama_obat ORDER BY nama_obat ASC");
                        }
                        foreach ($getObat as $data) {
                        ?>
                          <option value="<?= $data['nama_obat'] ?>"><?= $data['nama_obat'] ?></option>
                        <?php } ?>
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
                      <select name="jenis_obat[]" class="form-select">
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
                    <select name="nama_obat[]" class="form-control " id="selObat1" aria-label="Default select example">
                      <option value="">Pilih</option>

                      <?php
                      if (!isset($_GET['inap'])) {
                        $getObat = $koneksi->query("SELECT * FROM apotek WHERE tipe = 'Rajal' GROUP BY nama_obat ORDER BY nama_obat ASC");
                      } else {
                        $getObat = $koneksi->query("SELECT * FROM apotek WHERE tipe = 'Ranap' GROUP BY nama_obat ORDER BY nama_obat ASC");
                      }
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
                      <select name="jenis_obat[]" class="form-select">
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
                <input type="hidden" name="idrm" value="<?php echo $_GET['id'] ?>">
                <input type="hidden" class="form-control" id="inputName5" name="id" value="<?php echo $jadwal['idrawat'] ?>" placeholder="Masukkan Nama Pasien">
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

    <!-- <?php
          $obat = $koneksi->query("SELECT * FROM obat_rm ORDER BY nama_obat ASC")->fetch_assoc();
          ?> -->



    <div class="text-center" style="margin-top: -10px; margin-bottom: 40px;">
      <?php if (!isset($_GET['ed'])) { ?>
        <button type="submit" name="save" class="btn btn-primary">Tambah</button>
      <?php } else { ?>
        <button type="submit" name="editrm" class="btn btn-warning">Edit</button>
      <?php } ?>
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
    <div class="card shadow p-2">
      <h5 class="card-title">Plan</h5>

      <!-- Multi Columns Form -->
      <div class="row" id="plan">
        <div class="table-responsive">
          <!-- Button trigger modal -->
          <div align="right">
            <button type="button" class="btn btn-primary text-right" data-bs-toggle="modal" data-bs-target="#exampleModal">Add</button>
          </div>
          <br>

          <div id="userList">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th width="5%">No.</th>
                  <th width="40%">Layanan/Tindakan</th>
                  <th width="30%">Kode Layanan</th>
                  <th width="30%">Jumlah</th>
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

      <br>
      <br>
      <?php
      $getLastRM = $koneksi->query("SELECT  *, COUNT(*) AS jumm, MAX(id_rm) as id_rm FROM rekam_medis WHERE norm = '" . htmlspecialchars($_GET['id']) . "' AND DATE_FORMAT(jadwal, '%Y-%m-%d') = '" . date('Y-m-d', strtotime($_GET['tgl'])) . "' ORDER BY id_rm DESC LIMIT 1")->fetch_assoc();

      $getLastRM['jumm'] > 0 ? $whereConditionObatRm = "AND rekam_medis_id = '$getLastRM[id_rm]'" : $whereConditionObatRm = "AND rekam_medis_id IS NULL";

      $obat = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' " . $whereConditionObatRm . " ");


      ?>
      <div class="row" id="obt">
        <div class="table-responsive" id="obat">
          <h5 class="card-title">Tambah Obat Untuk Jadwal <?= $jadwal['jadwal'] ?></h5>
          <div align="right">
            <button type="button" class="btn btn-primary text-right" data-bs-toggle="modal" data-bs-target="#exampleModal45">Add Jadi <?= $getLastRM['id_rm'] ?></button>
            <button type="button" class="btn btn-primary text-right" data-bs-toggle="modal" data-bs-target="#exampleModal2">Add Racik</button>
            <a type="button" class="btn btn-info text-right" href="index.php?halaman=tambahpuyer2&id=<?= $_GET['id'] ?>&tgl=<?= $_GET['tgl'] ?>">Add Racik Paket</a>
          </div>
          <br>
          <?php $subtotal = 0; ?>

          <O id="employee_table">
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
                  <th width="20%"></th>
                </tr>
              </thead>
              <tbody>

                <?php $no = 1 ?>

                <?php foreach ($obat as $obat) :

                  // <!-- setting margin --> 
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
                  // var_dump($subharga);
                  ?>


                  <tr>
                    <td><?php echo $no; ?></td>
                    <td style="margin-top:10px;"><?php echo $obat["nama_obat"]; ?></td>
                    <td style="margin-top:10px;"><?php echo $obat["kode_obat"]; ?></td>
                    <td style="margin-top:10px;"><?php echo $obat["jml_dokter"]; ?></td>
                    <td style="margin-top:10px;"><?php echo $obat["dosis1_obat"]; ?> X <?php echo $obat["dosis2_obat"]; ?> <?php echo $obat["per_obat"]; ?></td>
                    <td style="margin-top:10px;"><?php echo $obat["jenis_obat"]; ?> <?= $obat['racik'] ?></td>
                    <td style="margin-top:10px;"><?php echo $obat["durasi_obat"]; ?> hari</td>
                    <td style="margin-top:10px;"> <button type="button" class="btn btn-primary text-right" data-bs-toggle="modal" data-bs-target="#exampleModalEdit<?php echo $obat["idobat"]; ?>">Edit</button>
                      <?php if (isset($_GET['inap'])) { ?>
                        <a href="index.php?halaman=rmedis&id=<?php echo $obat["idobat"]; ?>&rm=<?php echo $_GET["id"]; ?>&tgl=<?php echo $_GET["tgl"]; ?>&hapus&inap=<?= $_GET['inap'] ?>" class="btn btn-danger text-right"><i class="bi bi-trash"></i></a>
                      <?php } else { ?>
                        <a href="index.php?halaman=rmedis&id=<?php echo $obat["idobat"]; ?>&rm=<?php echo $_GET["id"]; ?>&tgl=<?php echo $_GET["tgl"]; ?>&hapus" class="btn btn-danger text-right"><i class="bi bi-trash"></i></a>
                      <?php } ?>
                    </td>
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
                                    <!-- <input type="text" name="nama_obat" class="form-control" id="inputName5" placeholder="Layanan/Tindakan"> -->
                                    <select name="nama_obat" onchange="updateCatatan()" id="nama_obat" class="form-select">
                                      <option value="<?php echo $obat["nama_obat"]; ?>"><?php echo $obat["nama_obat"]; ?></option>
                                      <?php

                                      $getObat = $koneksi->query("SELECT * FROM apotek ORDER BY nama_obat ASC");
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
                                  <input type="text" name="catatan_obat" class="form-control" id="catatan" placeholder="Masukkan Catatan Waktu" value="<?php echo $obat["catatan_obat"]; ?>">
                                </div>
                                <div class="col-md-12" style="margin-top:0px; margin-bottom:20px;">
                                  <label for="inputName5" class="form-label">Jenis Obat</label>
                                  <select name="jenis_obat" class="form-select">
                                    <option value="Racik">Racik</option>
                                    <!-- <option value="Jadi">Jadi</option> -->
                                  </select>
                                </div>
                                <label for="inputName5" class="form-label">Dosis</label>
                                <div class="col-md-6">
                                  <div class="input-group">
                                    <input oninput="updateJumlah()" type="text" class="form-control" id="dosis1_obat" name="dosis1_obat" value="<?php echo $obat["dosis1_obat"]; ?>">
                                    <input type="text" style="text-align: center;" class="form-control" placeholder="X">
                                    <input oninput="updateJumlah()" type="text" class="form-control" id="dosis2_obat" name="dosis2_obat" value="<?php echo $obat["dosis2_obat"]; ?>">
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
                                  <input type="number" name="id_obat_sebelum" class="form-control" id="id_obat_sebelum" hidden placeholder="jumlah obat" value="<?php echo $obat["idobat"]; ?>">
                                  <input type="number" name="jml_obat_sebelum" class="form-control" id="jml_obat_sebelum" hidden placeholder="jumlah obat" value="<?php echo $obat["jml_dokter"]; ?>">
                                  <input type="number" name="jml_dokter" class="form-control" id="jml_obat" placeholder="jumlah obat" value="<?php echo $obat["jml_dokter"]; ?>">
                                </div>
                                <div class="col-md-12" style="margin-top:20px">
                                  <label for="inputCity" class="form-label">Durasi</label>
                                  <div class="input-group mb-3">
                                    <input type="text" name="durasi_obat" value="<?php echo $obat["durasi_obat"]; ?>" class="form-control" placeholder="Durasi" aria-describedby="basic-addon2">
                                    <span class="input-group-text" id="basic-addon2">Hari</span>
                                  </div>
                                </div>
                                <div class="col-md-12" style="margin-top:10px">
                                  <label for="inputName5" class="form-label">Petunjuk Pemakaian</label>
                                  <input type="text" name="petunjuk_obat" value="<?php echo $obat["petunjuk_obat"]; ?>" class="form-control" id="petunjuk" placeholder="Masukkan Petunjuk Pemakaian">
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
          </O>
        </div>
      </div>
      <br>
    </div>


    <div class="card shadow p-3">
      <h5 class="card-title">Data Laboratorium</h5>
      <table class="table">
        <thead>
          <tr>
            <th>Pasien</th>
            <th>Tipe</th>
            <th>Pemeriksaan</th>
            <th>Hasil</th>
            <th>Nilai Normal</th>
            <!-- <th>Aksi</th> -->
          </tr>
        </thead>

        <tbody>
          <?php while ($pecah = $lab->fetch_assoc()) { ?>
            <tr>
              <!-- <td></td> -->
              <td> <?php echo $pecah["pasien"]; ?></td>
              <td> <?php echo $pecah["tipe"]; ?></td>
              <td>
                <?php echo $pecah["nama_periksa"]; ?>
              </td>
              <td><?php echo $pecah["hasil_periksa"]; ?></td>
              <td><?php echo $pecah["indikator"]; ?></td>
              <!-- <td>
             <li><a href="index.php?halaman=hapusdetaillab&id=<?php echo $pecah["idhasil"]; ?>" class="btn-sm btn-danger" style="text-decoration: none; font-weight: bold; margin-left: 2px;" onclick="return confirm('Anda yakin mau menghapus item ini ?')">
                 Hapus</a></li>


            </td> -->

            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
    </div>

    <div class="card shadow p-3">
      <h5 class="card-title">Riwayat Rekam Medis</h5>
      <table class="table">
        <thead>
          <tr>
            <th>Nama</th>
            <th>Tgl</th>
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
  </main><!-- End #main -->

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
  // $medika=htmlspecialchars($_POST["medika"]);
  // $nonmedika=htmlspecialchars($_POST["nonmedika"]);
  $status_pulang = htmlspecialchars($_POST["status_pulang"]);
  $id_pasien = htmlspecialchars($_POST["id_pasien"]);
  // $lidah=htmlspecialchars($_POST["lidah"]);
  // $langit_langit=htmlspecialchars($_POST["langit_langit"]);
  // $leher=htmlspecialchars($_POST["leher"]);
  // $tenggorokan=htmlspecialchars($_POST["tenggorokan"]);
  // $tonsil=htmlspecialchars($_POST["tonsil"]);
  // $dada=htmlspecialchars($_POST["dada"]);
  // $payudara=htmlspecialchars($_POST["payudara"]);
  // $punggung=htmlspecialchars($_POST["punggung"]);
  // $genital=htmlspecialchars($_POST["genital"]);
  // $anus=htmlspecialchars($_POST["anus"]);
  // $lengan_atas=htmlspecialchars($_POST["lengan_atas"]);
  // $lengan_bawah=htmlspecialchars($_POST["lengan_bawah"]);
  // $jari_tangan=htmlspecialchars($_POST["jari_tangan"]);
  // $kuku_tangan=htmlspecialchars($_POST["kuku_tangan"]);
  // $persendian_tangan=htmlspecialchars($_POST["persendian_tangan"]);
  // $tungkai_atas=htmlspecialchars($_POST["tungkai_atas"]);
  // $tungkai_bawah=htmlspecialchars($_POST["tungkai_bawah"]);
  // $jari_kaki=htmlspecialchars($_POST["jari_kaki"]);
  // $kuku_kaki=htmlspecialchars($_POST["kuku_kaki"]);
  // $persendian_kaki=htmlspecialchars($_POST["persendian_kaki"]);

  $gula_darah = htmlspecialchars($_POST['gula_darah']);
  $kolestrol = htmlspecialchars($_POST['kolestrol']);
  $asam_urat = htmlspecialchars($_POST['asam_urat']);

  if ($prognosa == 'Prognosis good') {
    $prognosacode = '170968001';
  } elseif ($prognosa == 'Guarded prognosis') {
    $prognosacode = '170969009';
  } elseif ($prognosa == 'Fair prognosis') {
    $prognosacode = '170970005';
  } else {
    $prognosacode = '170968001';
  }


  $koneksi->query("INSERT INTO rekam_medis(nama_pasien, norm, jadwal, status_perokok, anamnesa, diagnosis, prognosa, icd, status_plg, id_pasien, gol_darah, dokter, kode_prognosa) VALUES ('$_POST[nama_pasien]', '$_GET[id]','$jadwal', '$status_perokok', '$anamnesa', '$diagnosis', '$prognosa', '$icd', '$status_pulang', '$id_pasien', '$_POST[gol_darah]', '$_POST[dokter]', '$prognosacode')");

  $koneksi->query("INSERT INTO lab_poli (nama_pasien, jadwal, gula_darah, kolestrol, asam_urat) VALUES ('$_POST[nama_pasien]', '$jadwal', '$gula_darah', '$kolestrol', '$asam_urat')");

  $koneksi->query("UPDATE registrasi_rawat SET status_antri='Pembayaran' WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]'");

  if (isset($_GET['inap'])) {
    // $koneksi->query("UPDATE kajian_awal_inap SET  gol_darah = '$_POST[gol_darah]', anamnesa = '$_POST[anamnesa]', diagnosis = '$_POST[diagnosis]', icd = '$_POST[icd]', status_perokok = '$_POST[status_perokok]', status_plg = '$_POST[status_pulang]' WHERE norm = '$_GET[id]' AND jadwal = '$_POST[jadwal]'");
  }


  // $koneksi->query("INSERT INTO log_user 

  // (status_log, username_admin, idadmin)

  // VALUES ('$status_log', '$username_admin', '$idadmin')

  // ");

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

if (isset($_POST['editrm'])) {
  $jadwal = htmlspecialchars($_POST["jadwal"]);
  $status_perokok = htmlspecialchars($_POST["status_perokok"]);
  $anamnesa = htmlspecialchars($_POST["anamnesa"]);
  $diagnosis = ($_POST['diagnosis'] == 'Diagnosis Baru') ? htmlspecialchars($_POST['diagnosis']) : htmlspecialchars($_POST['diagnosis_new']);
  $prognosa = htmlspecialchars($_POST["prognosa"]);
  $icd = htmlspecialchars($_POST["icd"]);
  // $medika=htmlspecialchars($_POST["medika"]);
  // $nonmedika=htmlspecialchars($_POST["nonmedika"]);
  $status_pulang = htmlspecialchars($_POST["status_pulang"]);
  $id_pasien = htmlspecialchars($_POST["id_pasien"]);
  // $lidah=htmlspecialchars($_POST["lidah"]);
  // $langit_langit=htmlspecialchars($_POST["langit_langit"]);
  // $leher=htmlspecialchars($_POST["leher"]);
  // $tenggorokan=htmlspecialchars($_POST["tenggorokan"]);
  // $tonsil=htmlspecialchars($_POST["tonsil"]);
  // $dada=htmlspecialchars($_POST["dada"]);
  // $payudara=htmlspecialchars($_POST["payudara"]);
  // $punggung=htmlspecialchars($_POST["punggung"]);
  // $genital=htmlspecialchars($_POST["genital"]);
  // $anus=htmlspecialchars($_POST["anus"]);
  // $lengan_atas=htmlspecialchars($_POST["lengan_atas"]);
  // $lengan_bawah=htmlspecialchars($_POST["lengan_bawah"]);
  // $jari_tangan=htmlspecialchars($_POST["jari_tangan"]);
  // $kuku_tangan=htmlspecialchars($_POST["kuku_tangan"]);
  // $persendian_tangan=htmlspecialchars($_POST["persendian_tangan"]);
  // $tungkai_atas=htmlspecialchars($_POST["tungkai_atas"]);
  // $tungkai_bawah=htmlspecialchars($_POST["tungkai_bawah"]);
  // $jari_kaki=htmlspecialchars($_POST["jari_kaki"]);
  // $kuku_kaki=htmlspecialchars($_POST["kuku_kaki"]);
  // $persendian_kaki=htmlspecialchars($_POST["persendian_kaki"]);

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
  $ObatKode = $koneksi->query("SELECT id_obat, jml_obat FROM apotek WHERE nama_obat= '$getObatById[nama_obat]'")->fetch_assoc();

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

    $koneksi->query("INSERT INTO layanan 
    
        (layanan, kode_layanan, jumlah_layanan, id_pasien, idrm)
    
        VALUES ('$_POST[layanan2]', '-', '$jumlah_layanan', '$id_pasien', '$_GET[id]')
    
        ");
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
    $koneksi->query("INSERT INTO layanan 
    
        (layanan, kode_layanan, jumlah_layanan, id_pasien, idrm)
    
        VALUES ('$layanan', '-', '$jumlah_layanan', '$id_pasien', '$_GET[id]')
    
        ");
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
  // $kode_obat=$_POST['kode_obat'];
  $jml_dokter = $_POST['jml_dokter'];
  $dosis1_obat = $_POST['dosis1_obat'];
  $dosis2_obat = $_POST['dosis2_obat'];
  $per_obat = $_POST['per_obat'];
  $durasi_obat = $_POST['durasi_obat'];
  $petunjuk_obat = $_POST['petunjuk_obat'];
  // $id_pasien=$_POST['id_pasien'];_GET[id]=$_POST['idrm'];

  // $koneksi->query("INSERT INTO obat_rm 

  //   (catatan_obat, nama_obat, kode_obat, dosis1_obat, dosis2_obat, per_obat, durasi_obat, petunjuk_obat, id_pasien, idrm, jenis_obat)

  //   VALUES ('$catatan_obat', '$nama_obat', '$kode_obat', '$dosis1_obat', '$dosis2_obat', '$per_obat', '$durasi_obat', '$petunjuk_obat', '$id_pasien'_GET[id]', '$_POST[jenis_obat]')

  //   ");

  $end = date("H:i:s");
  $koneksi->query("UPDATE registrasi_rawat SET end='$end', kasir='$username' WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]';");
  $cekPemOb = $koneksi->query("SELECT * FROM registrasi_rawat WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' limit 1")->fetch_assoc();


  if ($cekPemOb['carabayar'] == 'umum') {
    $koneksi->query("UPDATE biaya_rawat SET poli = '35000' WHERE idregis='$cekPemOb[idrawat]'");
  } elseif ($cekPemOb['carabayar'] == 'malam') {
    $koneksi->query("UPDATE biaya_rawat SET poli = '50000' WHERE idregis='$cekPemOb[idrawat]'");
  }

  for ($i = 0; $i < count($nama) - 1; $i++) {
    // $row = $koneksi->query("SELECT id_obat FROM apotek WHERE nama_obat= '$nama'")->fetch_assoc();
    // $row = $koneksi->query("SELECT id_obat FROM apotek WHERE nama_obat= '$nama'")->fetch_assoc();

    foreach ($_POST['catatan_obat'] as $catatan_obat) {
      foreach ($_POST['dosis1_obat'] as $value2) {
        foreach ($_POST['dosis2_obat'] as $value3) {
          foreach ($_POST['per_obat'] as $per_obat) {
            foreach ($_POST['durasi_obat'] as $durasi_obat) {
              foreach ($_POST['petunjuk_obat'] as $petunjuk_obat) {
                foreach ($_POST['jenis_obat'] as $jenis_obat) {

                  if (isset($_GET['inap'])) {
                    $ObatKode = $koneksi->query("SELECT id_obat, jml_obat, margininap, harga_beli FROM apotek WHERE tipe = 'Ranap' AND nama_obat= '" . $nama[$i] . "'")->fetch_assoc();
                    $stokAkhir = $ObatKode['jml_obat'] - $jml_dokter[$i];

                    $m = $ObatKode['margininap'];

                    if ($m < 100) {
                      $margin = 1.30;
                    } else {
                      $margin = $m / 100;
                    }

                    $harga = $ObatKode['harga_beli'] * $margin * $jml_dokter[$i];
                    $subtotal += $harga;
                  } else {
                    $ObatKode = $koneksi->query("SELECT id_obat, jml_obat FROM apotek WHERE tipe = 'Rajal' AND nama_obat= '" . $nama[$i] . "'")->fetch_assoc();
                    $stokAkhir = $ObatKode['jml_obat'] - $jml_dokter[$i];
                  }

                  //!-- setting margin --> 
                  // $ambil2=$koneksi->query("SELECT * FROM daftarobat WHERE namaobat='$id_produk' ");
                  // $pecah2=$ambil2->fetch_assoc(); 

                  // $m=$ObatKode['margininap'];

                  // if ($m<100) {
                  //   $margin=1.30; 
                  // }
                  // else {
                  //   $margin=$m/100;
                  // }

                  // $harga=$ObatKode['harga_beli']*$margin*$jml_dokter;

                  // Update Stok Obat
                  // $koneksi->query("UPDATE apotek SET jml_obat = '$stokAkhir' WHERE id_obat = '$ObatKode[id_obat]'");

                  $koneksi->query("INSERT INTO obat_rm SET
            catatan_obat    = '$catatan_obat',
            nama_obat      = '$nama[$i]',
            kode_obat      = '$ObatKode[id_obat]',
            jml_dokter      = '$jml_dokter[$i]',
            dosis1_obat      = '$value2',
            dosis2_obat      = '$value3',
            per_obat      = '$per_obat',
            durasi_obat      = '$durasi_obat',
            petunjuk_obat      = '$petunjuk_obat',
            jenis_obat      = '$jenis_obat',
            idrm      = '$_GET[id]',
            tgl_pasien      = '$_GET[tgl]',
            rekam_medis_id = '$getLastRM[id_rm]',
            racik = '$_POST[racik]';
        ");
                  //   foreach ($row['id_obat'] as $kode_obat){
                  // }
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
  // $kode_obat=$_POST['kode_obat'];
  $jml_dokter = $_POST['jml_dokter'];
  $dosis1_obat = $_POST['dosis1_obat'];
  $dosis2_obat = $_POST['dosis2_obat'];
  $per_obat = $_POST['per_obat'];
  $durasi_obat = $_POST['durasi_obat'];
  $petunjuk_obat = $_POST['petunjuk_obat'];
  $jenis_obat = $_POST['jenis_obat'];
  // $id_pasien=$_POST['id_pasien'];_GET[id]=$_POST['idrm'];

  // $koneksi->query("INSERT INTO obat_rm 

  //   (catatan_obat, nama_obat, kode_obat, dosis1_obat, dosis2_obat, per_obat, durasi_obat, petunjuk_obat, id_pasien, idrm, jenis_obat)

  //   VALUES ('$catatan_obat', '$nama_obat', '$kode_obat', '$dosis1_obat', '$dosis2_obat', '$per_obat', '$durasi_obat', '$petunjuk_obat', '$id_pasien'_GET[id]', '$_POST[jenis_obat]')

  //   ");



  $end = date("H:i:s");
  $koneksi->query("UPDATE registrasi_rawat SET end='$end', kasir='$username' WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]';");

  $cekPemOb = $koneksi->query("SELECT * FROM registrasi_rawat WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' limit 1")->fetch_assoc();

  if ($cekPemOb['carabayar'] == 'umum') {
    $koneksi->query("UPDATE biaya_rawat SET poli = '35000' WHERE idregis='$cekPemOb[idrawat]'");
  } elseif ($cekPemOb['carabayar'] == 'malam') {
    $koneksi->query("UPDATE biaya_rawat SET poli = '50000' WHERE idregis='$cekPemOb[idrawat]'");
  }

  for ($i = 0; $i < count($nama) - 1; $i++) {
    // $row = $koneksi->query("SELECT id_obat FROM apotek WHERE nama_obat= '$nama'")->fetch_assoc();
    // $row = $koneksi->query("SELECT id_obat FROM apotek WHERE nama_obat= '$nama'")->fetch_assoc();

    // foreach ($_POST['catatan_obat'] as $catatan_obat){
    // foreach ($_POST['dosis1_obat'] as $value2){
    // foreach ($_POST['dosis2_obat'] as $value3){
    // foreach ($_POST['per_obat'] as $per_obat){
    // foreach ($_POST['durasi_obat'] as $durasi_obat){
    // foreach ($_POST['petunjuk_obat'] as $petunjuk_obat){
    // foreach ($_POST['jenis_obat'] as $jenis_obat){

    // $ObatKode = $koneksi->query("SELECT id_obat, jml_obat FROM apotek WHERE nama_obat= '".$nama[$i]."'")->fetch_assoc();
    // $stokAkhir = $ObatKode['jml_obat']-$jml_dokter[$i];

    if (isset($_GET['inap'])) {
      $ObatKode = $koneksi->query("SELECT id_obat, jml_obat, margininap, harga_beli FROM apotek WHERE tipe = 'Ranap' AND nama_obat= '" . $nama[$i] . "'")->fetch_assoc();
      $stokAkhir = $ObatKode['jml_obat'] - $jml_dokter[$i];

      $m = $ObatKode['margininap'];

      if ($m < 100) {
        $margin = 1.30;
      } else {
        $margin = $m / 100;
      }

      $harga = $ObatKode['harga_beli'] * $margin * $jml_dokter[$i];
      $subtotal += $harga;
    } else {
      $ObatKode = $koneksi->query("SELECT id_obat, jml_obat FROM apotek WHERE tipe = 'Rajal' AND nama_obat= '" . $nama[$i] . "'")->fetch_assoc();
      $stokAkhir = $ObatKode['jml_obat'] - $jml_dokter[$i];
    }

    //!-- setting margin --> 
    // $ambil2=$koneksi->query("SELECT * FROM apotek WHERE namaobat='$id_produk' ");
    // $pecah2=$ambil2->fetch_assoc(); 




    // Update Stok Obat
    // $koneksi->query("UPDATE apotek SET jml_obat = '$stokAkhir' WHERE id_obat = '$ObatKode[id_obat]'");

    $koneksi->query("INSERT INTO obat_rm SET
            catatan_obat    = '$catatan_obat[$i]',
            nama_obat      = '$nama[$i]',
            kode_obat      = '$ObatKode[id_obat]',
            jml_dokter      = '$jml_dokter[$i]',
            dosis1_obat      = '$dosis1_obat[$i]',
            dosis2_obat      = '$dosis2_obat[$i]',
            per_obat      = '$per_obat[$i]',
            durasi_obat      = '$durasi_obat[$i]',
            petunjuk_obat      = '$petunjuk_obat[$i]',
            jenis_obat      = '$jenis_obat[$i]',
            tgl_pasien      = '$_GET[tgl]',
            rekam_medis_id = '$getLastRM[id_rm]',
            idrm      = '$_GET[id]'
        ");
    //   foreach ($row['id_obat'] as $kode_obat){
    // }
    // }
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
//   }
// }
// }


// }

if (isset($_POST['saveobjadi'])) {
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
  $koneksi->query("UPDATE registrasi_rawat SET end='$end', kasir='$username' WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]';");

  $ObatKode = $koneksi->query("SELECT id_obat, jml_obat FROM apotek WHERE nama_obat= '" . $nama . "'")->fetch_assoc();

  // if(isset($_GET['inap'])){
  //   $ObatKode = $koneksi->query("SELECT id_obat, jml_obat, margininap, harga_beli FROM apotek WHERE tipe = 'Ranap' AND nama_obat= '".$nama[$i]."'")->fetch_assoc();
  //   $stokAkhir = $ObatKode['jml_obat']-$jml_dokter[$i];
  // }
  // else{
  //   $ObatKode = $koneksi->query("SELECT id_obat, jml_obat FROM apotek WHERE tipe = 'Rajal' AND nama_obat= '".$nama[$i]."'")->fetch_assoc();
  //   $stokAkhir = $ObatKode['jml_obat']-$jml_dokter[$i];
  // }

  $stokAkhir = $ObatKode['jml_obat'] - $jml_dokter;
  // $koneksi->query("UPDATE apotek SET jml_obat = '$stokAkhir' WHERE id_obat = '$ObatKode[id_obat]'");


  $cekPemOb = $koneksi->query("SELECT * FROM registrasi_rawat WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' ORDER BY idrawat limit 1")->fetch_assoc();


  if ($cekPemOb['carabayar'] == 'umum') {
    $koneksi->query("UPDATE biaya_rawat SET poli = '35000' WHERE idregis='$cekPemOb[idrawat]'");
  } elseif ($cekPemOb['carabayar'] == 'malam') {
    $koneksi->query("UPDATE biaya_rawat SET poli = '50000' WHERE idregis='$cekPemOb[idrawat]'");
  }

  $koneksi->query("INSERT INTO obat_rm SET
      catatan_obat    = '$catatan_obat',
      nama_obat      = '$nama',
      kode_obat      = '$ObatKode[id_obat]',
      jml_dokter      = '$jml_dokter',
      dosis1_obat      = '$dosis1_obat',
      dosis2_obat      = '$dosis2_obat',
      per_obat      = '$per_obat',
      durasi_obat      = '$durasi_obat',
      petunjuk_obat      = '$petunjuk_obat',
      jenis_obat      = '$_POST[jenis_obat]',
      rekam_medis_id = '$getLastRM[id_rm]',
      idrm      = '$_GET[id]'
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
  $ObatKodeUp = $koneksi->query("SELECT id_obat, jml_obat FROM apotek WHERE nama_obat= '$getObatById[nama_obat]'")->fetch_assoc();

  // Membuat Penambahan obat yang sebelumnya di kurangi saat nambah obat
  $stokAkhirUp = $ObatKodeUp['jml_obat'] + $getObatById['jml_dokter'];

  // Obat Jmlah obat sesuai dengan stok yang sudah di kembalikan
  // $koneksi->query("UPDATE apotek SET jml_obat = '$stokAkhirUp' WHERE id_obat = '$ObatKodeUp[id_obat]'");

  $koneksi->query("UPDATE registrasi_rawat SET end='$end' WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]';");
  $ObatKode = $koneksi->query("SELECT id_obat, jml_obat FROM apotek WHERE nama_obat= '" . $nama . "'")->fetch_assoc();
  $stokAkhir = $ObatKode['jml_obat'] - $jml_dokter;
  // $koneksi->query("UPDATE apotek SET jml_obat = '$stokAkhir' WHERE id_obat = '$ObatKode[id_obat]'");


  $koneksi->query("UPDATE obat_rm SET
    catatan_obat    = '$catatan_obat',
    nama_obat      = '$nama',
    kode_obat      = '$ObatKode[id_obat]',
    jml_dokter      = '$jml_dokter',
    dosis1_obat      = '$dosis1_obat',
    dosis2_obat      = '$dosis2_obat',
    per_obat      = '$per_obat',
    durasi_obat      = '$durasi_obat',
    petunjuk_obat      = '$petunjuk_obat',
    jenis_obat      = '$_POST[jenis_obat]',
    idrm      = '$idrm' WHERE idobat = '$_POST[id]'
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
  $copy_labpoli = $koneksi->query("SELECT * FROM lab_poli WHERE nama_pasien='$copy_rm[nama_pasien]' AND jadwal = '$_POST[jadwalSumber]' ORDER BY created_at DESC LIMIT 1");

  $jadwal = htmlspecialchars($_POST['jadwalSekarang']);
  $status_perokok = htmlspecialchars($copy_rm["status_perokok"]);
  $anamnesa = htmlspecialchars($copy_rm["anamnesa"]);
  $diagnosis = htmlspecialchars($copy_rm["diagnosis"]);
  $prognosa = htmlspecialchars($copy_rm["prognosa"]);
  $icd = htmlspecialchars($copy_rm["icd"]);
  // $medika=htmlspecialchars($copy_rm["medika"]);
  // $nonmedika=htmlspecialchars($copy_rm["nonmedika"]);
  $status_pulang = htmlspecialchars($copy_rm["status_pulang"]);
  $id_pasien = htmlspecialchars($copy_rm["id_pasien"]);
  // $lidah=htmlspecialchars($_POST["lidah"]);
  // $langit_langit=htmlspecialchars($_POST["langit_langit"]);
  // $leher=htmlspecialchars($_POST["leher"]);
  // $tenggorokan=htmlspecialchars($_POST["tenggorokan"]);
  // $tonsil=htmlspecialchars($_POST["tonsil"]);
  // $dada=htmlspecialchars($_POST["dada"]);
  // $payudara=htmlspecialchars($_POST["payudara"]);
  // $punggung=htmlspecialchars($_POST["punggung"]);
  // $genital=htmlspecialchars($_POST["genital"]);
  // $anus=htmlspecialchars($_POST["anus"]);
  // $lengan_atas=htmlspecialchars($_POST["lengan_atas"]);
  // $lengan_bawah=htmlspecialchars($_POST["lengan_bawah"]);
  // $jari_tangan=htmlspecialchars($_POST["jari_tangan"]);
  // $kuku_tangan=htmlspecialchars($_POST["kuku_tangan"]);
  // $persendian_tangan=htmlspecialchars($_POST["persendian_tangan"]);
  // $tungkai_atas=htmlspecialchars($_POST["tungkai_atas"]);
  // $tungkai_bawah=htmlspecialchars($_POST["tungkai_bawah"]);
  // $jari_kaki=htmlspecialchars($_POST["jari_kaki"]);
  // $kuku_kaki=htmlspecialchars($_POST["kuku_kaki"]);
  // $persendian_kaki=htmlspecialchars($_POST["persendian_kaki"]);
  $gula_darah = htmlspecialchars($copy_labpoli['gula_darah']);
  $kolestrol = htmlspecialchars($copy_labpoli['kolestrol']);
  $asam_urat = htmlspecialchars($copy_labpoli['asam_urat']);

  $koneksi->query("INSERT INTO rekam_medis(nama_pasien, norm, jadwal, status_perokok, anamnesa, diagnosis, prognosa, icd, status_plg, id_pasien, gol_darah, dokter, gula_darah, kolestrol, asam_urat) VALUES ('$copy_rm[nama_pasien]', '$_GET[id]','$jadwal', '$status_perokok', '$anamnesa', '$diagnosis', '$prognosa', '$icd', '$status_pulang', '$id_pasien', '$copy_rm[gol_darah]', '$copy_rm[dokter]', '$gula_darah', '$kolestrol', '$asam_urat')");

  $koneksi->query("INSERT INTO lab_poli (nama_pasien, jadwal, gula_darah, kolestrol, asam_urat) VALUES ('$copy_rm[nama_pasien]', '$jadwal', '$gula_darah', '$kolestrol', '$asam_urat')");

  $koneksi->query("UPDATE registrasi_rawat SET status_antri='Pembayaran' WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]'");
  if (isset($_GET['inap'])) {
    // $koneksi->query("UPDATE kajian_awal_inap SET  gol_darah = '$_POST[gol_darah]', anamnesa = '$_POST[anamnesa]', diagnosis = '$_POST[diagnosis]', icd = '$_POST[icd]', status_perokok = '$_POST[status_perokok]', medika = '$_POST[medika]', nonmedika = '$_POST[nonmedika]', status_plg = '$_POST[status_pulang]' WHERE norm = '$_GET[id]' AND jadwal = '$_POST[jadwal]'");
  }


  // $koneksi->query("INSERT INTO log_user 

  // (status_log, username_admin, idadmin)

  // VALUES ('$status_log', '$username_admin', '$idadmin')

  // ");

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
    $ObatKode = $koneksi->query("SELECT id_obat, jml_obat FROM apotek WHERE nama_obat= '" . $nama . "'")->fetch_assoc();
    $stokAkhir = $ObatKode['jml_obat'] - $jml_dokter;
    // $koneksi->query("UPDATE apotek SET jml_obat = '$stokAkhir' WHERE id_obat = '$ObatKode[id_obat]'");


    $cekPemOb = $koneksi->query("SELECT * FROM registrasi_rawat WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' limit 1")->fetch_assoc();


    if ($cekPemOb['carabayar'] != 'bpjs') {
      $koneksi->query("UPDATE biaya_rawat SET poli = '35000' WHERE idregis='$cekPemOb[idrawat]'");
    }

    $koneksi->query("INSERT INTO obat_rm SET
            catatan_obat    = '$catatan_obat',
            nama_obat      = '$nama',
            kode_obat      = '$ObatKode[id_obat]',
            jml_dokter      = '$jml_dokter',
            dosis1_obat      = '$dosis1_obat',
            dosis2_obat      = '$dosis2_obat',
            per_obat      = '$per_obat',
            durasi_obat      = '$durasi_obat',
            petunjuk_obat      = '$petunjuk_obat',
            jenis_obat      = '$dataOb[jenis_obat]',
            rekam_medis_id = '$getLastRM[id_rm]',
            idrm      = '$_GET[id]'
          ");
  }

  echo "
          <script>
            alert('Data berhasil diubah');
            document.location.href='index.php?halaman=daftarrmedis';
          </script>
        ";
}
?>