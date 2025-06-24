<?php
error_reporting(0);
$username = $_SESSION['admin']['username'];
$ambil = $koneksi->query("SELECT * FROM admin  WHERE username='$username';");
if (isset($_GET['inap'])) {
  $pasien = $koneksi->query("SELECT * FROM kajian_awal_inap WHERE kajian_awal_inap.norm='$_GET[id]';")->fetch_assoc();
  $lab = $koneksi->query("SELECT * FROM lab_hasil JOIN daftartes WHERE id_inap='$_GET[rawat]' AND nama_periksa=nama_tes ORDER BY idhasil");
} else {
  $pasien = $koneksi->query("SELECT * FROM kajian_awal WHERE norm='$_GET[id]' and tgl_rm = '$_GET[tgl]' ORDER BY id_rm DESC LIMIT 1;")->fetch_assoc();

  $lab = $koneksi->query("SELECT * FROM lab_hasil JOIN daftartes WHERE id_lab_h='$_GET[rawat]' AND nama_periksa=nama_tes");
}


if (isset($_GET['all'])) {
  $rm = $koneksi->query("SELECT * FROM rekam_medis WHERE rekam_medis.norm='$_GET[id]' AND DATE_FORMAT(jadwal, '%Y-%m-%d') = '$_GET[tgl]';")->fetch_assoc();
} else {
  $rm = $koneksi->query("SELECT * FROM rekam_medis WHERE rekam_medis.norm='$_GET[id]' AND DATE_FORMAT(jadwal, '%Y-%m-%d') = '$_GET[tgl]';")->fetch_assoc();
}
$p = $koneksi->query("SELECT * FROM pasien WHERE no_rm='$_GET[id]';")->fetch_assoc();


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>KHM WONOREJO</title>
  <meta content="" name="description">
  <meta content="" name="keywords">


  <?php if (!isset($_GET['racik'])) { ?>

<body>
  <main>
    <div class="container">
      <div class="pagetitle">
        <h1>Detail Rekam Medis</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="index.php?halaman=rekammedisall" style="color:blue;">Rekam Medis</a></li>
            <li class="breadcrumb-item">Detail RM</li>
          </ol>
        </nav>
      </div><!-- End Page Title -->

      <section class="section  py-4">
        <div class="container">
          <div class="row">
            <div class="col-lg-12 col-md-12 d-flex">

              <div class="card" style="max-width: 70%; display: inline-flex; position: absolute;">
                <div class="card-body">
                  <h6 class="card-title">Data Pasien</h6>

                  <!-- Multi Columns Form -->
                  <form class="row g-3" method="post" enctype="multipart/form-data">
                    <div style="margin-bottom: 10px; text-align: right;">
                      <a href="index.php?halaman=ubahpasien&id=<?php echo $p["idpasien"]; ?>" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i> Edit Pasien</a>
                    </div>
                    <div class="col-md-3">
                      <b><label for="h6Name5" class="form-label">Nama Pasien</label></b>
                      <h6 type="text" id="h6Name5" placeholder="Masukkan Nama Pasien" value="<?php echo $p['nama_lengkap'] ?>" name="nama_pasien"><?php echo $p['nama_lengkap'] ?></h6>
                    </div>
                    <div class="col-md-3">
                      <b><label for="h6Name5" class="form-label">NIK Pasien</label></b>
                      <h6 type="text" id="h6Name5" placeholder="Masukkan Nama Pasien" value="<?php echo $p['nama_lengkap'] ?>" name="nik"><?php echo $p['no_identitas'] ?></h6>
                    </div>
                    <div class="col-md-3">
                      <b><label for="h6Name5" class="form-label">Umur Pasien</label></b>
                      <h6 type="text" id="h6Name5" placeholder="Masukkan Nama Pasien" value="<?php echo $p['umur'] ?>" name="nama_pasien"><?php echo $p['umur'] ?></h6>
                    </div>
                    <div class="col-md-3">
                      <b><label for="h6Name5" class="form-label">Nama Ayah</label></b>
                      <h6 type="text" id="h6Name5" placeholder="Masukkan Nama Pasien" value="<?php echo $p['nama_ibu'] ?>" name="nama_ibu"><?php echo $p['nama_ibu'] ?></h6>
                    </div>
                    <br>
                    <br>



                    <div>
                      <div style="margin-bottom: 10px; text-align: right;">
                        <a href="index.php?halaman=rmedis&id=<?php echo $_GET["id"]; ?>&tgl=<?php echo $_GET["tgl"]; ?>&ed" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i> Edit RME</a>
                      </div>
                      <h6 class="card-title">Data Kesehatan</h6>
                      <h5 class="card-title">Tanda-Tanda Vital</h5>
                    </div>

                    <div class="col-md-6">
                      <label for="inputCity" class="form-label">Suhu Tubuh</label>
                      <div class="input-group mb-6" style="margin-bottom:10px">
                        <input type="text" class="form-control" placeholder="Suhu Tubuh" name="suhu_tubuh" aria-describedby="basic-addon2" value="<?php echo $pasien['suhu_tubuh'] ?>" readonly>
                        <span class="input-group-text" id="basic-addon2">celcius</span>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <label for="inputCity" class="form-label">Saturasi Oksigen</label>
                      <div class="input-group mb-6" style="margin-bottom:10px">
                        <input type="text" class="form-control" placeholder="Saturasi Oksigen" name="oksigen" aria-describedby="basic-addon2" value="<?php echo $pasien['oksigen'] ?>" readonly>
                        <span class="input-group-text" id="basic-addon2">%</span>
                      </div>
                    </div>

                    <div class="col-md-6">

                      <label for="inputCity" class="form-label">Sistole</label>
                      <div class="input-group mb-6" style="margin-bottom:10px">
                        <input type="text" class="form-control" placeholder="Tekanan Darah" name="sistole" aria-describedby="basic-addon2" value="<?php echo $pasien['sistole'] ?>" readonly>
                        <span class="input-group-text" id="basic-addon2">mmHg</span>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <label for="inputCity" class="form-label">Distole</label>
                      <div class="input-group mb-6" style="margin-bottom:10px">
                        <input type="text" class="form-control" placeholder="Tekanan Darah" name="distole" aria-describedby="basic-addon2" value="<?php echo $pasien['distole'] ?>" readonly>
                        <span class="input-group-text" id="basic-addon2">mmHg</span>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <label for="inputCity" class="form-label">Nadi</label>
                      <div class="input-group mb-6" style="margin-bottom:10px">
                        <input type="text" class="form-control" placeholder="Denyut Nadi" name="nadi" aria-describedby="basic-addon2" value="<?php echo $pasien['nadi'] ?>" readonly>
                        <span class="input-group-text" id="basic-addon2">kali/menit</span>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <label for="inputCity" class="form-label">Frekuensi Pernafasan</label>
                      <div class="input-group mb-6" style="margin-bottom:10px">
                        <input type="text" class="form-control" placeholder="Frekuensi Pernafasan" name="frek_nafas" aria-describedby="basic-addon2" value="<?php echo $pasien['frek_nafas'] ?>" readonly>
                        <span class="input-group-text" id="basic-addon2">kali/menit</span>
                      </div>
                      <br>
                    </div>
                    <hr>
                    <?php
                    $getLabPoli = $koneksi->query("SELECT * FROM lab_poli WHERE jadwal = '$rm[jadwal]'");
                    foreach ($getLabPoli as $labpoli) {
                    ?>
                      <div class="col-md-4">
                        <label for=""><b>Gula Darah</b></label>
                        <h6><?= $labpoli['gula_darah'] ?></h6>
                      </div>
                      <div class="col-md-4">
                        <label for=""><b>Kolestrol</b></label>
                        <h6><?= $labpoli['kolestrol'] ?></h6>
                      </div>
                      <div class="col-md-4">
                        <label for=""><b>Asam Urat</b></label>
                        <h6><?= $labpoli['asam_urat'] ?></h6>
                      </div>
                    <?php } ?>
                    <div class="col-md-6">
                      <b><label for="h6City" class="form-label">Keluhan Utama </label></b>
                      <h6 type="text" id="h6City" placeholder="Masukkan No. HP Pasien" name="keluhan_utama"><?php echo $pasien['keluhan_utama'] ?></h6>
                    </div>
                    <div class="col-md-6">
                      <b><label for="h6City" class="form-label">Riwayat Penyakit</label></b>
                      <h6 type="text" id="h6City" placeholder="Masukkan Email Pasien" name="riwayat_penyakit"><?php echo $pasien['riwayat_penyakit'] ?></h6>
                    </div>
                    <div class="col-md-6">
                      <b><label for="h6State" class="form-label">Riwayat Alergi</label></b>
                      <h6 type="text" id="h6City" placeholder="Masukkan Email Pasien" name="riwayat_alergi"><?php echo $pasien['riwayat_alergi'] ?></h6>
                    </div>
                    <div class="col-md-6">
                      <b><label for="h6State" class="form-label">Gol. Darah</label></b>
                      <h6 type="text" id="h6City" placeholder="Masukkan Email Pasien" name="gol_darah"><?php echo $rm['gol_darah'] ?></h6>
                    </div>
                    <div class="col-md-6">
                      <b><label for="h6State" class="form-label">Status Perokok</label></b>
                      <h6 type="text" id="h6City" placeholder="Masukkan Email Pasien" name="status_perokok"><?php echo $rm['status_perokok'] ?></h6>
                    </div>
                    <div class="col-md-6">
                      <b><label for="h6State" class="form-label">Anamnesa</label></b>
                      <h6 type="text" id="h6City" placeholder="Masukkan Email Pasien" name="anamnesa"><?php echo $rm['anamnesa'] ?></h6>
                    </div>
                    <div class="col-md-6">
                      <b><label for="h6State" class="form-label">Diagnosis</label></b>
                      <h6 type="text" id="h6City" placeholder="Masukkan Email Pasien" name="anamnesa"><?php echo $rm['diagnosis'] ?></h6>
                    </div>
                    <div class="col-md-6">
                      <b><label for="h6State" class="form-label">Prognosis</label></b>
                      <h6 type="text" id="h6City" placeholder="Masukkan Email Pasien" name="anamnesa"><?php echo $rm['prognosa'] ?></h6>
                    </div>
                    <div class="col-md-6">
                      <b><label for="h6State" class="form-label">ICD</label></b>
                      <h6 type="text" id="h6City" placeholder="Masukkan Email Pasien" name="anamnesa"><?php echo $rm['icd'] ?></h6>
                    </div>
                    <div class="col-md-6">
                      <b><label for="h6State" class="form-label">Medikametosa</label></b>
                      <h6 type="text" id="h6City" placeholder="Masukkan Email Pasien" name="anamnesa"><?php echo $rm['medika'] ?></h6>
                    </div>
                    <div class="col-md-6">
                      <b><label for="h6State" class="form-label">Non Medikametosa</label></b>
                      <h6 type="text" id="h6City" placeholder="Masukkan Email Pasien" name="anamnesa"><?php echo $rm['nonmedika'] ?></h6>
                    </div>

                    <br>
                    <br>

                    <div>
                      <h6 class="card-title">Riwayat Vaksinasi</h6>
                    </div>
                    <div class="col-md-6">
                      <b><label for="h6City" class="form-label">Nama Vaksin </label></b>
                      <h6 type="text" id="h6City" placeholder="Masukkan No. HP Pasien" name="nama_vaksin"><?php echo $pasien['nama_vaksin'] ?></h6>
                    </div>
                    <div class="col-md-6">
                      <b><label for="h6City" class="form-label">Pemberian Ke-</label></b>
                      <h6 type="text" id="h6City" placeholder="Masukkan Email Pasien" name="pemberian_vaksin"><?php echo $pasien['pemberian_vaksin'] ?></h6>
                    </div>
                    <div class="col-md-12">
                      <b><label for="h6City" class="form-label">Tanggal Pemberian</label></b>
                      <h6 type="date" id="h6City" placeholder="Masukkan Email Pasien" name="tgl_vaksin"><?php echo $pasien['tgl_vaksin'] ?></h6>
                    </div>

                    <br>
                    <br>

                    <!-- <div>
                  <h6 class="card-title">Tanda-Tanda Vital</h6>
                </div>

                <div class="col-md-12">
                <b><label for="h6City" class="form-label">Suhu Tubuh</label></b>
                <div class="h6-group mb-6">
                      <h6 type="text"    placeholder="Suhu Tubuh" name="suhu_tubuh" aria-describedby="basic-addon2"><?php echo $pasien['suhu_tubuh'] ?>
                      <span class="h6-group-text" id="basic-addon2">celcius</span></h6>
                </div>
                </div>

                <div class="col-md-6">

                <b><label for="h6City" class="form-label">Sistole</label></b>
                 <div class="h6-group mb-6">
                      <h6 type="text"    placeholder="Tekanan Darah" name="sistole" aria-describedby="basic-addon2"><?php echo $pasien['sistole'] ?>
                      <span class="h6-group-text" id="basic-addon2">mmHg</span></h6>
                </div>
                </div>

                <div class="col-md-6">
                <b><label for="h6City" class="form-label">Distole</label></b>
                 <div class="h6-group mb-6">
                      <h6 type="text"    placeholder="Tekanan Darah" name="distole" aria-describedby="basic-addon2"><?php echo $pasien['distole'] ?>
                      <span class="h6-group-text" id="basic-addon2">mmHg</span></h6>
                </div>
                </div>
               
                <div class="col-md-12">
                <b><label for="h6City" class="form-label">Nadi</label></b>
                <div class="h6-group mb-6">
                      <h6 type="text"    placeholder="Denyut Nadi" name="nadi" aria-describedby="basic-addon2"><?php echo $pasien['nadi'] ?>
                      <span class="h6-group-text" id="basic-addon2">kali/menit</span></h6>
                </div>
                </div>

                 <div class="col-md-12">
                <b><label for="h6City" class="form-label">Frekuensi Pernafasan</label></b>
                <div class="h6-group mb-6">
                      <h6 type="text"    placeholder="Frekuensi Pernafasan" name="frek_nafas" aria-describedby="basic-addon2"><?php echo $pasien['frek_nafas'] ?>
                      <span class="h6-group-text" id="basic-addon2">kali/menit</span></h6>
                </div>
                </div>
                
                 <br>
                <br> -->

                    <div>
                      <h6 class="card-title">Pemeriksaan Fisik</h6>
                    </div>

                    <div class="col-md-6">
                      <b><label for="h6City" class="form-label">Lingkar Kepala</label></b>
                      <div class="h6-group mb-6">
                        <h6 type="text" placeholder="Lingkar Kepala" name="kepala" aria-describedby="basic-addon2"><?php echo $pasien['kepala'] ?>
                          <span class="h6-group-text" id="basic-addon2">cm</span>
                        </h6>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <b><label for="h6City" class="form-label">Lingkar Perut</label></b>
                      <div class="h6-group mb-6">
                        <h6 type="text" placeholder="Lingkar Perut" name="perut" aria-describedby="basic-addon2"><?php echo $pasien['perut'] ?>
                          <span class="h6-group-text" id="basic-addon2">cm</span>
                        </h6>
                      </div>
                    </div>


                    <div class="col-md-6">
                      <b><label for="h6Name5" class="form-label">Mata</label></b>
                      <h6 type="text" name="mata" id="h6Name5" placeholder="Mata"><?php echo $pasien['mata'] ?></h6>
                    </div>

                    <div class="col-md-6">
                      <b><label for="h6Name5" class="form-label">Telinga</label></b>
                      <h6 type="text" name="telinga" id="h6Name5" placeholder="Telinga"><?php echo $pasien['telinga'] ?></h6>
                    </div>

                    <div class="col-md-6">
                      <b><label for="h6Name5" class="form-label">Hidung</label></b>
                      <h6 type="text" name="hidung" id="h6Name5" placeholder="Hidung"><?php echo $pasien['hidung'] ?></h6>
                    </div>

                    <div class="col-md-6">
                      <b><label for="h6Name5" class="form-label">Rambut</label></b>
                      <h6 type="text" name="rambut" id="h6Name5" placeholder="Rambut"><?php echo $pasien['rambut'] ?></h6>
                    </div>

                    <div class="col-md-6">
                      <b><label for="h6Name5" class="form-label">Bibir</label></b>
                      <h6 type="text" name="bibir" id="h6Name5" placeholder="Bibir"><?php echo $pasien['bibir'] ?></h6>
                    </div>
                    <div class="col-md-6">
                      <b><label for="h6Name5" class="form-label">Gigi Geligi</label></b>
                      <h6 type="text" name="gigi" id="h6Name5" placeholder="Gigi Geligi"><?php echo $pasien['gigi'] ?></h6>
                    </div>

                    <div class="col-md-6">
                      <b><label for="h6Name5" class="form-label">Lidah</label></b>
                      <h6 type="text" name="lidah" id="h6Name5" placeholder="Lidah"><?php echo $pasien['lidah'] ?></h6>
                    </div>

                    <div class="col-md-6">
                      <b><label for="h6Name5" class="form-label">Langit-langit</label></b>
                      <h6 type="text" name="langit_langit" id="h6Name5" placeholder="Langit-langit"><?php echo $pasien['langit_langit'] ?></h6>
                    </div>

                    <div class="col-md-6">
                      <b><label for="h6Name5" class="form-label">Leher</label></b>
                      <h6 type="text" name="leher" id="h6Name5" placeholder="Leher"><?php echo $pasien['leher'] ?></h6>
                    </div>

                    <div class="col-md-6">
                      <b><label for="h6Name5" class="form-label">Tenggorokan</label></b>
                      <h6 type="text" name="tenggorokan" id="h6Name5" placeholder="Tenggorokan"><?php echo $pasien['tenggorokan'] ?></h6>
                    </div>

                    <div class="col-md-6">
                      <b><label for="h6Name5" class="form-label">Tonsil</label></b>
                      <h6 type="text" name="tonsil" id="h6Name5" placeholder="Tonsil"><?php echo $pasien['tonsil'] ?></h6>
                    </div>

                    <div class="col-md-6">
                      <b><label for="h6Name5" class="form-label">Dada</label></b>
                      <h6 type="text" name="dada" id="h6Name5" placeholder="Dada"><?php echo $pasien['dada'] ?></h6>
                    </div>

                    <div class="col-md-6">
                      <b><label for="h6Name5" class="form-label">Payudara</label></b>
                      <h6 type="text" name="payudara" id="h6Name5" placeholder="Payudara"><?php echo $pasien['payudara'] ?></h6>
                    </div>

                    <div class="col-md-6">
                      <b><label for="h6Name5" class="form-label">Punggung</label></b>
                      <h6 type="text" name="punggung" id="h6Name5" placeholder="Punggung"><?php echo $pasien['punggung'] ?></h6>
                    </div>

                    <div class="col-md-6">
                      <b><label for="h6Name5" class="form-label">Genital</label></b>
                      <h6 type="text" name="genital" id="h6Name5" placeholder="Genital"><?php echo $pasien['genital'] ?></h6>
                    </div>

                    <div class="col-md-6">
                      <b><label for="h6Name5" class="form-label">Anus</label></b>
                      <h6 type="text" name="anus" id="h6Name5" placeholder="Anus"><?php echo $pasien['anus'] ?></h6>
                    </div>

                    <div class="col-md-6">
                      <b><label for="h6Name5" class="form-label">Lengan Atas</label></b>
                      <h6 type="text" name="lengan_atas" id="h6Name5" placeholder="Lengan Atas"><?php echo $pasien['lengan_atas'] ?></h6>
                    </div>

                    <div class="col-md-6">
                      <b><label for="h6Name5" class="form-label">Lengan Bawah</label></b>
                      <h6 type="text" name="lengan_bawah" id="h6Name5" placeholder="Lengan Bawah"><?php echo $pasien['lengan_bawah'] ?></h6>
                    </div>

                    <div class="col-md-6">
                      <b><label for="h6Name5" class="form-label">Jari Tangan</label></b>
                      <h6 type="text" name="jari_tangan" id="h6Name5" placeholder="Jari Tangan"><?php echo $pasien['jari_tangan'] ?></h6>
                    </div>

                    <div class="col-md-6">
                      <b><label for="h6Name5" class="form-label">Kuku Tangan</label></b>
                      <h6 type="text" name="kuku_tangan" id="h6Name5" placeholder="Kuku Tangan"><?php echo $pasien['kuku_tangan'] ?></h6>
                    </div>

                    <div class="col-md-6">
                      <b><label for="h6Name5" class="form-label">Persendian Tangan</label></b>
                      <h6 type="text" name="persendian_tangan" id="h6Name5" placeholder="Persendian Tangan"><?php echo $pasien['persendian_tangan'] ?></h6>
                    </div>

                    <div class="col-md-6">
                      <b><label for="h6Name5" class="form-label">Tungkai Atas</label></b>
                      <h6 type="text" name="tungkai_atas" id="h6Name5" placeholder="Tungkai Atas"><?php echo $pasien['tungkai_atas'] ?></h6>
                    </div>

                    <div class="col-md-6">
                      <b><label for="h6Name5" class="form-label">Tungkai Bawah</label></b>
                      <h6 type="text" name="tungkai_bawah" id="h6Name5" placeholder="Tungkai Bawah"><?php echo $pasien['tungkai_bawah'] ?></h6>
                    </div>

                    <div class="col-md-6">
                      <b><label for="h6Name5" class="form-label">Jari Kaki</label></b>
                      <h6 type="text" name="jari_kaki" id="h6Name5" placeholder="Jari Kaki"><?php echo $pasien['jari_kaki'] ?></h6>
                    </div>

                    <div class="col-md-6">
                      <b><label for="h6Name5" class="form-label">Kuku Kaki</label></b>
                      <h6 type="text" name="kuku_kaki" id="h6Name5" placeholder="Kuku Kaki"><?php echo $pasien['kuku_kaki'] ?></h6>
                    </div>

                    <div class="col-md-6">
                      <b><label for="h6Name5" class="form-label">Persendian Kaki</label></b>
                      <h6 type="text" name="persendian_kaki" id="h6Name5" placeholder="Persendian Kaki"><?php echo $pasien['persendian_kaki'] ?></h6>
                    </div>

                    <div class="col-md-6">
                      <b><label for="h6City" class="form-label">Tinggi Badan</label></b>
                      <div class="h6-group mb-6">
                        <h6 type="text" placeholder="Tinggi Badan" name="tb" aria-describedby="basic-addon2"><?php echo $pasien['tb'] ?>
                          <span class="h6-group-text" id="basic-addon2">m</span>
                        </h6>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <b><label for="h6City" class="form-label">Berat Badan</label></b>
                      <div class="h6-group mb-6">
                        <h6 type="text" placeholder="Berat Badan" name="bb" aria-describedby="basic-addon2"><?php echo $pasien['bb'] ?>
                          <span class="h6-group-text" id="basic-addon2">kg</span>
                        </h6>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <b><label for="h6City" class="form-label">IMT</label></b>
                      <div class="h6-group mb-6">
                        <h6 type="text" placeholder="IMT" name="imt" aria-describedby="basic-addon2"><?php echo $pasien['imt'] ?></h6>
                        <!-- <span class="h6-group-text" id="basic-addon2">celcius</span> -->
                      </div>
                    </div>

                    <br>
                    <br>

                    <div>
                      <h6 class="card-title">Pemeriksaan Psikologis, Sosial Ekonomi, Spiritual</h6>
                    </div>

                    <div class="col-md-12">
                      <b><label for="h6State" class="form-label">Status Psikologis</label></b>
                      <h6 type="text" placeholder="Psikologis" name="psiko" aria-describedby="basic-addon2"><?php echo $pasien['psiko'] ?></h6>

                    </div>
                    <div class="col-md-12">
                      <b><label for="h6Name5" class="form-label">Lain-Lain Psiko</label></b>
                      <h6 type="text" name="psiko" id="h6Name5" placeholder="Masukkan status psikologis"><?php echo $pasien['psiko'] ?></h6>
                    </div>

                    <div class="col-md-12">
                      <b><label for="h6Name5" class="form-label">Sosial Ekonomi</label></b>
                      <h6>a. Status Pernikahan: <?php echo $p['status_nikah'] ?></h6>
                      <h6>b. Pekerjaan: <?php echo $p['pekerjaan'] ?></h6>
                      <h6>c. Tempat Tinggal: <?php echo $pasien['status_tinggal'] ?></h6>
                      <h6>d. Hub Pasien Dengan Keluarga: <?php echo $pasien['hub_keluarga'] ?></h6>
                      <h6>e. Pengobatan Alternatif: <?php echo $pasien['pengobatan'] ?></h6>
                      <h6>f. Pantangan: <?php echo $pasien['pantangan'] ?></h6>
                    </div>

                    <div class="col-md-12">
                      <b><label for="h6Name5" class="form-label">Spiritual</label></b>
                      <h6 type="text" name="spiritual" id="h6Name5" placeholder="Masukkan Agama/Nilai-nilai spiritual Pasien"><?php echo $pasien['spiritual'] ?></h6>
                    </div>

                    <div>
                      <h6 class="card-title">Obat Pasien</h6>
                    </div>
                    <?php

                    $obat = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND rekam_medis_id = '" . htmlspecialchars($_GET['idrekammedis']) . "' AND DATE_FORMAT(tgl_pasien, '%Y-%m-%d') = DATE_FORMAT('$_GET[tgl]', '%Y-%m-%d')");
                    $obatP = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND rekam_medis_id = '" . htmlspecialchars($_GET['idrekammedis']) . "' AND DATE_FORMAT(tgl_pasien, '%Y-%m-%d') = DATE_FORMAT('$_GET[tgl]', '%Y-%m-%d')");

                    ?>
                    <!-- <?php
                          $cek = $koneksi->query("SELECT *, COUNT(*) as jumlah FROM resep INNER JOIN rekam_medis ON rekam_medis.norm = resep.no_rm WHERE resep.no_rm = '$_GET[id]' AND resep.jadwal = '$_GET[tgl]'")->fetch_assoc();
                          ?> -->

                    <div class="row">
                      <div class="table-responsive" id="obat">

                        <br>
                        <div id="employee_table">
                          <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th width="5%">No.</th>
                                <th width="50%">Obat</th>
                                <th width="20%">Dosis</th>
                                <th width="20%">Jumlah</th>
                                <th width="20%">Jenis</th>
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
                                  <td style="margin-top:10px;"><?php echo $obat["jml_dokter"]; ?></td>
                                  <td style="margin-top:10px;"><?php echo $obat["jenis_obat"]; ?> <?= $obat['racik'] ?></td>
                                  <td style="margin-top:10px;"><?php echo $obat["durasi_obat"]; ?> hari</td>
                                </tr>

                                <?php $no += 1 ?>
                              <?php endforeach ?>

                            </tbody>

                          </table>
                        </div>
                      </div>
                    </div>


                    <div>
                      <h6 class="card-title">Data Laboratorium</h6>
                    </div>


                    <div class="table-responsive">
                      <table class="table" style="width:100%;" id="myTable">
                        <thead>
                          <tr>
                            <th>pasien</th>
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
                      <?php if (isset($_GET['pemeriksaan'])) { ?>
                        <br>
                        <div>
                          <h6 class="card-title">Pembayaran</h6>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="table-responsive">
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
                                  <?php foreach ($obatP as $obat) { ?>
                                    <tr>
                                      <td><?php echo $no; ?></td>
                                      <td style="margin-top:10px;"><?php echo $obat["nama_obat"]; ?></td>
                                      <td style="margin-top:10px;"><?php echo $obat["dosis1_obat"]; ?> X <?php echo $obat["dosis2_obat"]; ?> <?php echo $obat["per_obat"]; ?></td>
                                      <td style="margin-top:10px;"><?php echo $obat["durasi_obat"]; ?> hari</td>
                                    </tr>
                                    <?php $no += 1 ?>
                                  <?php } ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="table-responsive">
                              <table class="table table-bordered">
                                <thead>
                                  <tr>
                                    <th width="5%">No.</th>
                                    <th width="50%">Layanan</th>
                                    <th width="20%">Jumlah</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                  $plan = $koneksi->query("SELECT * FROM layanan WHERE idrm = '$_GET[id]' AND DATE_FORMAT(tgl_layanan, '%Y-%m-%d') = DATE_FORMAT('$_GET[tgl]', '%Y-%m-%d')");
                                  $no = 1;
                                  ?>
                                  <?php foreach ($plan as $obat) { ?>
                                    <tr>
                                      <td><?php echo $no; ?></td>
                                      <td style="margin-top:10px;"><?php echo $obat["layanan"]; ?></td>
                                      <td style="margin-top:10px;"><?php echo $obat["jumlah_layanan"]; ?> hari</td>
                                    </tr>
                                    <?php $no += 1 ?>
                                  <?php } ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                        <br>
                        <?php
                        $cekRegis = $koneksi->query("SELECT * FROM registrasi_rawat WHERE DATE_FORMAT(jadwal, '%Y-%m-%d') = '$_GET[tgl]' AND no_rm='$_GET[id]' ORDER BY idrawat DESC LIMIT 1")->fetch_assoc();
                        // $biaya = $koneksi->query("SELECT * FROM  biaya_rawat WHERE idregis = '$cekRegis[idrawat]' ")->fetch_assoc();
                        $biaya = $koneksi->query("SELECT * FROM registrasi_rawat INNER JOIN biaya_rawat WHERE idregis=idrawat and idrawat = '$cekRegis[idrawat]' and status_antri = 'Pembayaran' and perawatan = 'Rawat Jalan';");

                        ?>
                        <!-- Multi Columns Form -->
                        <div class="table-responsive">
                          <table id="myTable" class="table table-striped" style="width:100%">
                            <thead>
                              <tr>
                                <th>No</th>
                                <th>Nama Pasien</th>
                                <th>Jenis Perawatan</th>
                                <th>Dokter</th>
                                <th>No RM</th>
                                <th>Jadwal</th>
                                <th>Antrian</th>
                                <th>Status</th>
                                <!-- <th>Status Bayar</th> -->
                                <th>Biaya Poli</th>
                                <th>Periksa lab</th>
                                <th>Total lab</th>
                                <th>Biaya Lain</th>
                                <th>Total Biaya Lain</th>
                                <th>Potongan</th>
                                <th>Total</th>
                                <th></th>
                                <!-- <th>Aksi</th> -->

                              </tr>
                            </thead>
                            <tbody>

                              <?php $subtotal = 0; ?>
                              <?php $subpoli = 0; ?>
                              <?php $sublain = 0; ?>
                              <?php $subigd = 0; ?>
                              <?php $sublab = 0; ?>

                              <?php $no = 1 ?>

                              <?php foreach ($biaya as $pecah) : ?>

                                <tr>
                                  <td><?php echo $no; ?></td>
                                  <td style="margin-top:10px;"><?php echo $pecah["nama_pasien"]; ?></td>
                                  <td style="margin-top:10px;"><?php echo $pecah["perawatan"]; ?></td>
                                  <td style="margin-top:10px;"><?php echo $pecah["dokter_rawat"]; ?></td>
                                  <td style="margin-top:10px;"><?php echo $pecah["no_rm"]; ?></td>
                                  <?php

                                  $jadwal = strtotime($pecah['jadwal']) - (3600 * 7);
                                  $date = $jadwal;
                                  // date_add($date, date_interval_create_from_date_string('-2 hours'));
                                  // echo date_format($date, 'Y-m-d\TH:i:s');
                                  ?>
                                  <td style="margin-top:10px;"> <?= $pecah['jadwal'] ?></td>
                                  <td style="margin-top:10px;"><?php echo $pecah["antrian"]; ?></td>
                                  <td style="margin-top:10px;">
                                    <?php if ($pecah["status_antri"] == 'Datang') { ?>
                                      <h6 style="color:green"><?php echo $pecah["status_antri"]; ?></h6>
                                    <?php } else { ?>
                                      <h6 style="color:red"><?php echo $pecah["status_antri"]; ?></h6>
                                    <?php }  ?>
                                    <!-- <td><?php echo $pecah["status"]; ?></td> -->
                                  <td><?php echo $pecah["poli"]; ?></td>
                                  <td><?php echo $pecah["periksa_lab"]; ?></td>
                                  <td><?php echo $pecah["biaya_lab"]; ?></td>
                                  <td><?php echo $pecah["biaya_lain"]; ?></td>
                                  <td><?php echo $pecah["total_lain"]; ?></td>
                                  <td><?php echo $pecah["potongan"]; ?></td>
                                  </td>
                                  <?php
                                  $t = intval($pecah["poli"]) + intval($pecah["biaya_lab"]) + intval($pecah["total_lain"]) - intval($pecah["potongan"]);
                                  ?>

                                  <td><?php echo $t; ?></td>
                                  <td>
                                    <div class="dropdown">
                                      <?php
                                      $ubah = $koneksi->query("SELECT * FROM kajian_awal WHERE nama_pasien = '$pecah[nama_pasien]';")->fetch_assoc();
                                      ?>
                                      <i data-bs-toggle="dropdown" style="color: blue; font-weight: bold; font-size: 20px;" class="bi bi-three-dots-vertical"></i>
                                      <ul class="dropdown-menu">
                                        <li>
                                          <a href="index.php?halaman=bayarrawat&rm=<?php echo $pecah["no_rm"]; ?>&id=<?php echo $pecah["idregis"]; ?>&tgl=<?php echo $pecah["jadwal"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-currency-dollar" style="color:blue;"></i> Bayar</a>
                                        </li>
                                        <li><a href="index.php?halaman=rujuklab2&rm=<?php echo $pecah["no_rm"]; ?>&id=<?php echo $pecah["idregis"]; ?>&tgl=<?php echo $pecah["jadwal"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-clipboard2-pulse" style="color:hotpink;"></i> Rujuk Lab</a></li>

                                        <li><a href="../bayar/rekappoli.php?rm=<?php echo $pecah["no_rm"]; ?>&id=<?php echo $pecah["idregis"]; ?>&tgl=<?php echo $pecah["jadwal"]; ?>&dr=<?php echo $pecah['dokter_rawat'] ?>&shift=<?php echo $pecah['shift'] ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-file" style="color:hotpink;"></i> Rekap Shift</a></li>

                                        <li><a href="../bayar/rekappolikasir.php?rm=<?php echo $pecah["no_rm"]; ?>&id=<?php echo $pecah["idregis"]; ?>&tgl=<?php echo $pecah["jadwal"]; ?>&dr=<?php echo $pecah['dokter_rawat'] ?>&shift=<?php echo $pecah['shift'] ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-file" style="color:hotpink;"></i> Rekap Kasir</a></li>
                                      </ul>
                                    </div>
                                  </td>
                                  <td>

                                  </td>
                                </tr>

                                <?php $no += 1 ?>
                                <?php $subtotal += $t; ?>
                              <?php endforeach; ?>

                              <tr>

                                <td></td>

                                <td colspan="11">

                                  TOTAL

                                </td>
                                <td></td>
                                <td></td>

                                <td>

                                  <h3> Rp.<?php echo  number_format($subtotal) ?></h3>

                                </td>
                                <td></td>

                              </tr>


                            </tbody>
                          </table>

                        </div>
                      <?php } ?>
                    </div>






                  <?php } else {
                  $start = $koneksi->query("SELECT * FROM registrasi_rawat WHERE no_rm='$_GET[id]' AND jadwal = '$_GET[tgl]';")->fetch_assoc();
                  ?>


                    <div class="card" style="max-width: 70%; display: inline-flex; position: absolute;">
                      <div class="card-body">
                        <h6 class="card-title">Data Dokter</h6>

                        <!-- Multi Columns Form -->
                        <div class="row g-3">
                          <div class="col-md-12">
                            <b><label for="h6Name5" class="form-label">Nama Dokter</label></b>
                            <h6 type="text" id="h6Name5" placeholder="Masukkan Nama Pasien" value="<?php echo $start['dokter_rawat'] ?>" name="nama_pasien"><?php echo $start['dokter_rawat'] ?></h6>
                          </div>

                          <!-- <div class="col-md-6">
                  <b><label for="h6Name5" class="form-label">SIP</label></b>
                  <h6 type="text"    id="h6Name5" placeholder="Masukkan Nama Pasien" value="<?php echo $rm['sip'] ?>" name="nama_pasien"><?php echo $rm['sip'] ?></h6>
                </div> -->



                          <div class="col-md-6">
                            <b><label for="h6State" class="form-label">Ruang/Poli</label></b>
                            <h6 type="text" id="h6City" placeholder="Masukkan Email Pasien" name="anamnesa"><?php echo $start['kamar'] ?> </h6>
                          </div>

                          <div class="col-md-6">
                            <b><label for="h6State" class="form-label">Jenis Pelayanan</label></b>
                            <h6 type="text" id="h6City" placeholder="Masukkan Email Pasien" name="anamnesa"><?php echo $p['kategori'] ?></h6>
                          </div>
                          <div class="col-md-6">
                            <b><label for="h6State" class="form-label">Status Pasien</label></b>
                            <h6 type="text" id="h6City" placeholder="Masukkan Email Pasien" name="anamnesa"><?php echo $p['pembiayaan'] ?></h6>
                          </div>
                        </div>

                        <h6 class="card-title">Data Pasien</h6>

                        <!-- Multi Columns Form -->
                        <div class="row g-3">
                          <div class="col-md-6">
                            <b><label for="h6Name5" class="form-label">Nama Pasien</label></b>
                            <h6 type="text" id="h6Name5" placeholder="Masukkan Nama Pasien" value="<?php echo $p['nama_lengkap'] ?>" name="nama_pasien"><?php echo $p['nama_lengkap'] ?></h6>
                          </div>

                          <div class="col-md-6">
                            <b><label for="h6Name5" class="form-label">Alamat</label></b>
                            <h6 type="text" id="h6Name5" placeholder="Masukkan Nama Pasien" value="<?php echo $p['alamat'] ?>" name="nama_pasien"><?php echo $p['alamat'] ?></h6>
                          </div>

                          <div class="col-md-6">
                            <b><label for="h6State" class="form-label">Diagnosis</label></b>
                            <h6 type="text" id="h6City" placeholder="Masukkan Email Pasien" name="anamnesa"><?php echo $rm['diagnosis'] ?></h6>
                          </div>

                          <div class="col-md-6">
                            <b><label for="h6State" class="form-label">BB</label></b>
                            <h6 type="text" id="h6City" placeholder="Masukkan Email Pasien" name="anamnesa"><?php echo $rm['bb'] ?> Kg</h6>
                          </div>

                          <div class="col-md-6">
                            <b><label for="h6State" class="form-label">Tgl Lahir</label></b>
                            <h6 type="text" id="h6City" placeholder="Masukkan Email Pasien" name="anamnesa"><?php echo $p['tgl_lahir'] ?></h6>
                          </div>
                        </div>



                        <div>
                          <h6 class="card-title">Obat Pasien</h6>
                        </div>

                        <?php
                        $obat = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND rekam_medis_id = '" . htmlspecialchars($_GET['idrekammedis']) . "' AND DATE_FORMAT(tgl_pasien, '%Y-%m-%d') = DATE_FORMAT('$_GET[tgl]', '%Y-%m-%d')");

                        ?>
                        <form action="" method="post">
                          <?php
                          $cekk = $koneksi->query("SELECT *, COUNT(*) as jumlah FROM resep INNER JOIN rekam_medis ON rekam_medis.norm = resep.no_rm WHERE resep.no_rm = '$_GET[id]' AND resep.jadwal = '$_GET[tgl]'")->fetch_assoc();
                          ?>
                          <div class="row">
                            <!-- <a href="index.php?halaman=racik&id=<?php echo $_GET["id"]; ?>&tgl=<?php echo $_GET["tgl"]; ?>" style="width: 200px;" class="btn btn-sm btn-primary">Pengkajian Telaah Resep</a> -->
                            <div class="col-md-6">
                              <b><label for="h6State" class="form-label">Tgl: <?php echo $start['jadwal'] ?></label></b>
                            </div>

                            <?php if ($cekk['jumlah'] == 0) { ?>
                              <div class="col-md-6">
                                <b><label for="h6State" class="form-label">No. Resep</label></b><br>
                                <?php
                                $getNotaPembayaranSingle = $koneksi->query("SELECT * FROM biaya_rawat WHERE idregis = '$start[idrawat]'")->fetch_assoc();
                                ?>
                                <input type="text" id="h6City" placeholder="" name="noresep" value="<?= $getNotaPembayaranSingle['nota'] ?>">
                              </div>
                            <?php } else { ?>
                              <div class="col-md-6">
                                <b><label for="h6State" class="form-label">No. Resep</label></b><br>
                                <h6 type="text" id="h6City" placeholder="Masukkan Email Pasien" name="noresep"><?php echo $cekk['noresep'] ?></h6>
                              </div>
                            <?php } ?>

                            <div class="table-responsive" id="obat">
                              <!-- <div align="right">
                    <button type="button" class="btn btn-primary text-right" data-bs-toggle="modal" data-bs-target="#exampleModal2">Add</button>
                </div> -->
                              <br>
                              <div id="employee_table">
                                <table class="table table-bordered">
                                  <thead>
                                    <tr>
                                      <th width="5%">No.</th>
                                      <th width="50%">Obat</th>
                                      <th width="20%">Dosis</th>
                                      <th width="20%">Jumlah</th>
                                      <th width="20%">Jenis</th>
                                      <th width="20%">Durasi</th>
                                    </tr>
                                  </thead>
                                  <tbody>

                                    <?php $no = 1 ?>

                                    <?php foreach ($obat as $obat) : ?>

                                      <tr>
                                        <td><?php echo $no; ?></td>
                                        <td style="margin-top:10px;"><?php echo $obat["nama_obat"]; ?></td>
                                        <td style="margin-top:10px;"><?php echo $obat["dosis1_obat"]; ?> x <?php echo $obat["dosis2_obat"]; ?> <?php echo $obat["per_obat"]; ?></td>
                                        <td style="margin-top:10px;"><?php echo $obat["jml_dokter"]; ?></td>
                                        <td style="margin-top:10px;"><?php echo $obat["jenis_obat"]; ?> <?= $obat['racik'] ?></td>
                                        <td style="margin-top:10px;"><?php echo $obat["durasi_obat"]; ?> hari</td>
                                      </tr>

                                      <?php $no += 1 ?>
                                    <?php endforeach ?>

                                  </tbody>
                                  <?php if ($obat["status_obat"] == "selesai") {
                                    echo "<p>Status Obat: <b><span>Obat Sudah diKonfirmasi<span></b> </p>";
                                  } else {
                                    echo "<p>Status Obat: <b><span>Menunggu Konfirmasi Dokter<span></b> </p>";
                                  }
                                  ?>
                                  <h6></h6>
                                </table>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-md-6">
                              <b>
                                <h6 for="h6State" class="form-label">Jam Terima Resep: <?php echo $start['end'] ?></h6>
                              </b>
                            </div>




                            <?php if ($cekk['jumlah'] == 0) { ?>
                              <div class="col-md-6">
                                <?php
                                date_default_timezone_set('Asia/Jakarta');
                                ?>
                                <b>
                                  <h6 for="h6State" class="form-label">Jam Penyerahan Obat: <input name="serah_obat" type="text" value="<?= date('H:i:s') ?>"></h6>
                                </b>
                              </div>
                            <?php } else { ?>
                              <div class="col-md-6">
                                <b>
                                  <h6 for="h6State" class="form-label">Jam Penyerahan Obat: <input name="serah_obat" type="text" value="<?= $cekk['serah_obat'] ?>" disabled></h6>
                                </b>
                              </div>
                            <?php } ?>


                          </div>

                          <br>
                          <br>
                          <?php
                          $cek = $koneksi->query("SELECT *, COUNT(*) as jumlah FROM resep INNER JOIN rekam_medis ON rekam_medis.norm = resep.no_rm WHERE resep.no_rm = '$_GET[id]' AND resep.jadwal = '$_GET[tgl]'")->fetch_assoc();
                          // var_dump($cek);
                          ?>
                          <div class="row">
                            <div class="col-6">
                              <table class="table table-bordered">
                                <thead>
                                  <th>
                                    Pelayanan Obat
                                  </th>
                                  <th>
                                    Paraf
                                  </th>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>Verifikasi Resep</td>
                                    <td>
                                      <center><img style="max-width: 60px; margin: 0px 0px 0px 0px;" src="img-qrcode/<?php if ($cek2['petugas'] == '') {
                                                                                                                        echo $_SESSION['admin']['namalengkap'];
                                                                                                                      } else {
                                                                                                                        echo $cek2['petugas'];
                                                                                                                      }  ?>.png" alt=""><br>
                                        <p style="font-size:12px">(<?php if ($cek2['petugas'] == '') {
                                                                      echo $_SESSION['admin']['namalengkap'];
                                                                    } else {
                                                                      echo $cek2['petugas'];
                                                                    }  ?>)</p>

                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Dispensing</td>
                                    <td>
                                      <center><img style="max-width: 60px; margin: 0px 0px 0px 0px;" src="img-qrcode/<?php if ($cek2['petugas'] == '') {
                                                                                                                        echo $_SESSION['admin']['namalengkap'];
                                                                                                                      } else {
                                                                                                                        echo $cek2['petugas'];
                                                                                                                      }  ?>.png" alt=""><br>
                                        <p style="font-size:12px">(<?php if ($cek2['petugas'] == '') {
                                                                      echo $_SESSION['admin']['namalengkap'];
                                                                    } else {
                                                                      echo $cek2['petugas'];
                                                                    }  ?>)</p>

                                        <!-- <a class="btn btn-sm btn-success" href="index.php?halaman=detailrm&id=<?php echo $_GET["id"]; ?>&tgl=<?php echo $_GET["tgl"]; ?>&racik&ttdPet&&idrekammedis=<?= $_GET['idrekammedis'] ?>">TTD</a></center> -->
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Verifikasi Obat</td>
                                    <td>
                                      <center><img style="max-width: 60px; margin: 0px 0px 0px 0px;" src="img-qrcode/<?php if ($cek2['petugas'] == '') {
                                                                                                                        echo $_SESSION['admin']['namalengkap'];
                                                                                                                      } else {
                                                                                                                        echo $cek2['petugas'];
                                                                                                                      }  ?>.png" alt=""><br>
                                        <p style="font-size:12px">(<?php if ($cek2['petugas'] == '') {
                                                                      echo $_SESSION['admin']['namalengkap'];
                                                                    } else {
                                                                      echo $cek2['petugas'];
                                                                    }  ?>)</p>

                                        <!-- <a class="btn btn-sm btn-success" href="index.php?halaman=detailrm&id=<?php echo $_GET["id"]; ?>&tgl=<?php echo $_GET["tgl"]; ?>&racik&ttdPet&&idrekammedis=<?= $_GET['idrekammedis'] ?>">TTD</a></center> -->
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Penerimaan Obat</td>
                                    <td>
                                      <center><img style="max-width: 60px; margin: 0px 0px 0px 0px;" src="img-qrcode/<?php if ($cek2['petugas'] == '') {
                                                                                                                        echo $_SESSION['admin']['namalengkap'];
                                                                                                                      } else {
                                                                                                                        echo $cek2['petugas'];
                                                                                                                      }  ?>.png" alt=""><br>
                                        <p style="font-size:12px">(<?php if ($cek2['petugas'] == '') {
                                                                      echo $_SESSION['admin']['namalengkap'];
                                                                    } else {
                                                                      echo $cek2['petugas'];
                                                                    }  ?>)</p>

                                        <!-- <a class="btn btn-sm btn-success" href="index.php?halaman=detailrm&id=<?php echo $_GET["id"]; ?>&tgl=<?php echo $_GET["tgl"]; ?>&racik&ttdPet&&idrekammedis=<?= $_GET['idrekammedis'] ?>">TTD</a></center> -->
                                    </td>
                                  </tr>
                                  <tr>
                                    <td></td>
                                    <td>
                                      <center>
                                        <a class="btn btn-sm btn-success" href="index.php?halaman=detailrm&id=<?php echo $_GET["id"]; ?>&tgl=<?php echo $_GET["tgl"]; ?>&racik&ttdPet&&idrekammedis=<?= $_GET['idrekammedis'] ?>">Paraf</a>
                                      </center>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>



                            <?php if ($cek['jumlah'] == 0) { ?>
                              <div class="col-6">
                                <form method="post" action="">
                                  <table class="table table-bordered">
                                    <!-- belum post -->
                                    <tbody>
                                      <tr>
                                        <td>Tepat Pasien</td>

                                        <td><input type="checkbox" name="tepat1" id=""></td>
                                      </tr>
                                      <tr>
                                        <td>Tepat Obat</td>
                                        <td><input type="checkbox" name="tepat2" id=""></td>
                                      </tr>
                                      <tr>
                                        <td>Tepat Dosis</td>
                                        <td><input type="checkbox" name="tepat3" id=""></td>
                                      </tr>
                                      <tr>
                                        <td>Tepat Rute</td>
                                        <td><input type="checkbox" name="tepat4" id=""></td>
                                      </tr>
                                      <tr>
                                        <td>Tepat Waktu</td>
                                        <td><input type="checkbox" name="tepat5" id=""></td>
                                      </tr>
                                    </tbody>
                                  </table>
                                  <button type="submit" name="save" class="btn btn-primary">Simpan</button>
                                </form>
                              </div>
                            <?php } else { ?>
                              <div class="col-6">
                                <table class="table table-bordered">

                                  <tbody>
                                    <tr>
                                      <td>Tepat Pasien</td>
                                      <!-- sudah -->

                                      <td><?php if ($cek['tepat1'] == 'on') { ?> ✔ <?php } ?></td>
                                    </tr>
                                    <tr>
                                      <td>Tepat Obat</td>
                                      <td><?php if ($cek['tepat2'] == 'on') { ?> ✔ <?php } ?></td>
                                    </tr>
                                    <tr>
                                      <td>Tepat Dosis</td>
                                      <td><?php if ($cek['tepat3'] == 'on') { ?> ✔ <?php } ?></td>
                                    </tr>
                                    <tr>
                                      <td>Tepat Rute</td>
                                      <td><?php if ($cek['tepat4'] == 'on') { ?> ✔ <?php } ?></td>
                                    </tr>
                                    <tr>
                                      <td>Tepat Waktu</td>
                                      <td><?php if ($cek['tepat5'] == 'on') { ?> ✔ <?php } ?></td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            <?php } ?>

                          </div>
                          <br>
                          <br>

                          <?php
                          $cekr = $koneksi->query("SELECT *, COUNT(*) as jumlah FROM telaah_resep INNER JOIN rekam_medis ON rekam_medis.norm = telaah_resep.no_rm WHERE telaah_resep.no_rm = '$_GET[id]' AND telaah_resep.jadwal = '$_GET[tgl]'")->fetch_assoc();
                          ?>

                          <?php if ($cekr['jumlah'] == 0) { ?>

                            <div class="card shadow p-2">
                              <center>
                                <h5>PENGKAJIAN TELAAH RESEP</h5>
                                <p>PERSYARATAN ADMINISTRASI</p>
                              </center>
                              <table class="table table-bordered">
                                <thead>
                                  <tr>
                                    <th>Parameter</th>
                                    <th>Status</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>Nama Pasien</td>
                                    <td>
                                      <select name="pa1" id="" class="form-control">
                                        <option value="Ya">Ya</option>
                                        <option value="Tidak">Tidak</option>
                                      </select>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>No.Rm Pasien</td>
                                    <td>
                                      <select name="pa2" id="" class="form-control">
                                        <option value="Ya">Ya</option>
                                        <option value="Tidak">Tidak</option>
                                      </select>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Jenis Kelamin</td>
                                    <td>
                                      <select name="pa3" id="" class="form-control">
                                        <option value="Ya">Ya</option>
                                        <option value="Tidak">Tidak</option>
                                      </select>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Umur/BB/TB</td>
                                    <td>
                                      <select name="pa4" id="" class="form-control">
                                        <option value="Ya">Ya</option>
                                        <option value="Tidak">Tidak</option>
                                      </select>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Nama dan Paraf Dokter</td>
                                    <td>
                                      <select name="pa5" id="" class="form-control">
                                        <option value="Ya">Ya</option>
                                        <option value="Tidak">Tidak</option>
                                      </select>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Tanggal Resep</td>
                                    <td>
                                      <select name="pa6" id="" class="form-control">
                                        <option value="Ya">Ya</option>
                                        <option value="Tidak">Tidak</option>
                                      </select>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Asal Poli/Ruangan</td>
                                    <td>
                                      <select name="pa7" id="" class="form-control">
                                        <option value="Ya">Ya</option>
                                        <option value="Tidak">Tidak</option>
                                      </select>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                              <br>
                              <center>
                                <p>PERSYARATAN FARMASETIS</p>
                              </center>
                              <table class="table table-bordered">
                                <thead>
                                  <tr>
                                    <th>Parameter</th>
                                    <th>Status</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>Nama Obat</td>
                                    <td>
                                      <select name="pa8" id="" class="form-control">
                                        <option value="Ya">Ya</option>
                                        <option value="Tidak">Tidak</option>
                                      </select>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Bentuk Sediaan</td>
                                    <td>
                                      <select name="pa9" id="" class="form-control">
                                        <option value="Ya">Ya</option>
                                        <option value="Tidak">Tidak</option>
                                      </select>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Kekuatan Sediaan</td>
                                    <td>
                                      <select name="pa10" id="" class="form-control">
                                        <option value="Ya">Ya</option>
                                        <option value="Tidak">Tidak</option>
                                      </select>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Dosis dan Jumlah Obat</td>
                                    <td>
                                      <select name="pa11" id="" class="form-control">
                                        <option value="Ya">Ya</option>
                                        <option value="Tidak">Tidak</option>
                                      </select>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Aturan dan Cara Pakai</td>
                                    <td>
                                      <select name="pa12" id="" class="form-control">
                                        <option value="Ya">Ya</option>
                                        <option value="Tidak">Tidak</option>
                                      </select>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                              <br>
                              <center>
                                <p>PERSYARATAN KLINIS</p>
                              </center>
                              <table class="table table-bordered">
                                <thead>
                                  <tr>
                                    <th>Parameter</th>
                                    <th>Status</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>Duplikasi Pengobatan</td>
                                    <td>
                                      <select name="pa13" id="" class="form-control">
                                        <option value="Ya">Ya</option>
                                        <option value="Tidak">Tidak</option>
                                      </select>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Tepat Indikasi Dosis, Waktu Penggunaan Obat</td>
                                    <td>
                                      <select name="pa14" id="" class="form-control">
                                        <option value="Ya">Ya</option>
                                        <option value="Tidak">Tidak</option>
                                      </select>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Alergi dan Reaksi Obat yang Tidak Dikehendaki (ROTD)</td>
                                    <td>
                                      <select name="pa15" id="" class="form-control">
                                        <option value="Ya">Ya</option>
                                        <option value="Tidak">Tidak</option>
                                      </select>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Inteaksi Obat</td>
                                    <td>
                                      <select name="pa16" id="" class="form-control">
                                        <option value="Ya">Ya</option>
                                        <option value="Tidak">Tidak</option>
                                      </select>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Poli Farmasi</td>
                                    <td>
                                      <select name="pa17" id="" class="form-control">
                                        <option value="Ya">Ya</option>
                                        <option value="Tidak">Tidak</option>
                                      </select>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                              <br>
                              <?php
                              $obatt = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND rekam_medis_id = '" . htmlspecialchars($_GET['idrekammedis']) . "' AND DATE_FORMAT(tgl_pasien, '%Y-%m-%d') = DATE_FORMAT('$_GET[tgl]', '%Y-%m-%d')");
                              ?>
                              <center>
                                <p></p>PIO</p>
                              </center>
                              <table class="table table-bordered">
                                <thead>
                                  <tr>
                                    <th width="5%">No.</th>
                                    <th width="50%">Obat</th>
                                    <th width="20%">Dosis</th>
                                    <th width="20%">Jumlah</th>
                                    <th width="20%">Paraf</th>
                                  </tr>
                                </thead>
                                <tbody>

                                  <?php $no = 1 ?>

                                  <?php foreach ($obatt as $obat) : ?>

                                    <tr>
                                      <td><?php echo $no; ?></td>
                                      <td style="margin-top:10px;"><?php echo $obat["nama_obat"]; ?></td>
                                      <td style="margin-top:10px;"><?php echo $obat["dosis1_obat"]; ?> x <?php echo $obat["dosis2_obat"]; ?> <?php echo $obat["per_obat"]; ?></td>
                                      <td style="margin-top:10px;"><?php echo $obat["jml_dokter"]; ?></td>
                                      <td style="margin-top:10px;">
                                        <center><img style="max-width: 60px; margin: 0px 0px 0px 0px;" src="img-qrcode/<?= $_SESSION['admin']['namalengkap'] ?>.png" alt=""><br>
                                          <p style="font-size:12px">(<?= $_SESSION['admin']['namalengkap'] ?>)</p>
                                          <a class="btn btn-sm btn-success" href="index.php?halaman=detailrm&id=<?php echo $_GET["id"]; ?>&tgl=<?php echo $_GET["tgl"]; ?>&racik&ttdPio&&idrekammedis=<?= $_GET['idrekammedis'] ?>">Paraf</a>
                                        </center>
                                      </td>
                                    </tr>

                                    <?php $no += 1 ?>
                                  <?php endforeach ?>

                                </tbody>

                              </table>

                              <table class="table table-bordered" style="margin-top: -20px;">
                                <thead>
                                  <tr>
                                    <th></th>
                                    <th></th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td><b>Edukasi</b></td>
                                    <td>
                                      <textarea name="edukasi" id="" style="width:100%; height:140px"></textarea>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td><b>Paraf Pasien</b></td>
                                    <td>
                                      <center><img style="max-width: 60px; margin: 0px 0px 0px 0px;" src="img-qrcode/<?= $p['nama_lengkap'] ?>.png" alt=""><br>
                                        <p style="font-size:12px">(<?= $p['nama_lengkap'] ?>)</p>
                                        <a class="btn btn-sm btn-success" href="index.php?halaman=detailrm&id=<?php echo $_GET["id"]; ?>&tgl=<?php echo $_GET["tgl"]; ?>&racik&ttdPas&&idrekammedis=<?= $_GET['idrekammedis'] ?>">Paraf</a>
                                      </center>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                              <br>
                              <center>
                                <div style="max-width: 90%;">
                                  <div class="row">
                                    <div class="col-6">
                                      <div style="padding : 20px; border: solid black 1px;">
                                        <h6>Persetujuan Perubahan Resep</h6><br>
                                        <center><img style="max-width: 60px; margin: 0px 0px 0px 0px;" src="img-qrcode/<?= $_SESSION['admin']['namalengkap'] ?>.png" alt=""><br>
                                          <p style="font-size:12px">(<?= $_SESSION['admin']['namalengkap'] ?>)</p>
                                          <a class="btn btn-sm btn-success" href="index.php?halaman=detailrm&id=<?php echo $_GET["id"]; ?>&tgl=<?php echo $_GET["tgl"]; ?>&racik&ttdSet&&idrekammedis=<?= $_GET['idrekammedis'] ?>">Paraf</a>
                                        </center>
                                      </div>
                                    </div>
                                    <div class="col-6">
                                      <div style="padding : 20px; border: solid black 1px;">
                                        <h6>Petugas Farmasi</h6><br>
                                        <center><img style="max-width: 60px; margin: 0px 0px 0px 0px;" src="img-qrcode/<?= $_SESSION['admin']['namalengkap'] ?>.png" alt=""><br>
                                          <p style="font-size:12px">(<?= $_SESSION['admin']['namalengkap'] ?>)</p>
                                          <a class="btn btn-sm btn-success" href="index.php?halaman=detailrm&id=<?php echo $_GET["id"]; ?>&tgl=<?php echo $_GET["tgl"]; ?>&racik&ttdFar&&idrekammedis=<?= $_GET['idrekammedis'] ?>">Paraf</a>
                                        </center>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </center>
                              <br>

                              <center>
                                <button type="submit" style="width:40%" name="tel" class="btn btn-primary">Simpan</button>
                              </center>

                            </div>

                          <?php } else { ?>
                            <div class="card shadow p-2">
                              <center>
                                <h5>PENGKAJIAN TELAAH RESEP</h5>
                                <p>PERSYARATAN ADMINISTRASI</p>
                              </center>
                              <table class="table table-bordered">
                                <thead>
                                  <tr>
                                    <th>Parameter</th>
                                    <th>Status</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>Nama Pasien</td>
                                    <td>
                                      <select name="pa1" id="" class="form-control">
                                        <option value="<?= $cekr['pa1'] ?>"><?= $cekr['pa1'] ?></option>
                                      </select>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>No.Rm Pasien</td>
                                    <td>
                                      <select name="pa2" id="" class="form-control">
                                        <option value="<?= $cekr['pa2'] ?>"><?= $cekr['pa2'] ?></option>

                                      </select>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Jenis Kelamin</td>
                                    <td>
                                      <select name="pa3" id="" class="form-control">
                                        <option value="<?= $cekr['pa3'] ?>"><?= $cekr['pa3'] ?></option>

                                      </select>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Umur/BB/TB</td>
                                    <td>
                                      <select name="pa4" id="" class="form-control">
                                        <option value="<?= $cekr['pa4'] ?>"><?= $cekr['pa4'] ?></option>

                                      </select>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Nama dan Paraf Dokter</td>
                                    <td>
                                      <select name="pa5" id="" class="form-control">
                                        <option value="<?= $cekr['pa5'] ?>"><?= $cekr['pa5'] ?></option>

                                      </select>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Tanggal Resep</td>
                                    <td>
                                      <select name="pa6" id="" class="form-control">
                                        <option value="<?= $cekr['pa6'] ?>"><?= $cekr['pa6'] ?></option>

                                      </select>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Asal Poli/Ruangan</td>
                                    <td>
                                      <select name="pa7" id="" class="form-control">
                                        <option value="<?= $cekr['pa7'] ?>"><?= $cekr['pa7'] ?></option>

                                      </select>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                              <br>
                              <center>
                                <p>PERSYARATAN FARMASETIS</p>
                              </center>
                              <table class="table table-bordered">
                                <thead>
                                  <tr>
                                    <th>Parameter</th>
                                    <th>Status</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>Nama Obat</td>
                                    <td>
                                      <select name="pa8" id="" class="form-control">
                                        <option value="<?= $cekr['pa8'] ?>"><?= $cekr['pa8'] ?></option>

                                      </select>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Bentuk Sediaan</td>
                                    <td>
                                      <select name="pa9" id="" class="form-control">
                                        <option value="<?= $cekr['pa9'] ?>"><?= $cekr['pa9'] ?></option>

                                      </select>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Kekuatan Sediaan</td>
                                    <td>
                                      <select name="pa10" id="" class="form-control">
                                        <option value="<?= $cekr['pa10'] ?>"><?= $cekr['pa10'] ?></option>

                                      </select>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Dosis dan Jumlah Obat</td>
                                    <td>
                                      <select name="pa11" id="" class="form-control">
                                        <option value="<?= $cekr['pa11'] ?>"><?= $cekr['pa11'] ?></option>

                                      </select>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Aturan dan Cara Pakai</td>
                                    <td>
                                      <select name="pa12" id="" class="form-control">
                                        <option value="<?= $cekr['pa12'] ?>"><?= $cekr['pa12'] ?></option>

                                      </select>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                              <br>
                              <center>
                                <p>PERSYARATAN KLINIS</p>
                              </center>
                              <table class="table table-bordered">
                                <thead>
                                  <tr>
                                    <th>Parameter</th>
                                    <th>Status</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>Duplikasi Pengobatan</td>
                                    <td>
                                      <select name="pa13" id="" class="form-control">
                                        <option value="<?= $cekr['pa13'] ?>"><?= $cekr['pa13'] ?></option>

                                      </select>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Tepat Indikasi Dosis, Waktu Penggunaan Obat</td>
                                    <td>
                                      <select name="pa14" id="" class="form-control">
                                        <option value="<?= $cekr['pa14'] ?>"><?= $cekr['pa14'] ?></option>

                                      </select>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Alergi dan Reaksi Obat yang Tidak Dikehendaki (ROTD)</td>
                                    <td>
                                      <select name="pa15" id="" class="form-control">
                                        <option value="<?= $cekr['pa15'] ?>"><?= $cekr['pa15'] ?></option>

                                      </select>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Inteaksi Obat</td>
                                    <td>
                                      <select name="pa16" id="" class="form-control">
                                        <option value="<?= $cekr['pa16'] ?>"><?= $cekr['pa16'] ?></option>

                                      </select>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>Poli Farmasi</td>
                                    <td>
                                      <select name="pa17" id="" class="form-control">
                                        <option value="<?= $cekr['pa17'] ?>"><?= $cekr['pa17'] ?></option>

                                      </select>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                              <br>
                              <?php
                              $obatt = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND rekam_medis_id = '" . htmlspecialchars($_GET['idrekammedis']) . "' AND DATE_FORMAT(tgl_pasien, '%Y-%m-%d') = DATE_FORMAT('$_GET[tgl]', '%Y-%m-%d')");

                              ?>
                              <center>
                                <p></p>PIO</p>
                              </center>
                              <table class="table table-bordered">
                                <thead>
                                  <tr>
                                    <th width="5%">No.</th>
                                    <th width="50%">Obat</th>
                                    <th width="20%">Dosis</th>
                                    <th width="20%">Jumlah</th>
                                    <th width="20%">Paraf</th>
                                  </tr>
                                </thead>
                                <tbody>

                                  <?php $no = 1 ?>

                                  <?php foreach ($obatt as $obat) : ?>

                                    <tr>
                                      <td><?php echo $no; ?></td>
                                      <td style="margin-top:10px;"><?php echo $obat["nama_obat"]; ?></td>
                                      <td style="margin-top:10px;"><?php echo $obat["dosis1_obat"]; ?> x <?php echo $obat["dosis2_obat"]; ?> <?php echo $obat["per_obat"]; ?></td>
                                      <td style="margin-top:10px;"><?php echo $obat["jml_dokter"]; ?></td>
                                      <td style="margin-top:10px;">
                                        <center><img style="max-width: 60px; margin: 0px 0px 0px 0px;" src="img-qrcode/<?= $cekr['petugas'] ?>.png" alt=""><br>
                                          <p style="font-size:12px">(<?= $cekr['petugas'] ?>)</p>
                                          <a class="btn btn-sm btn-success" href="index.php?halaman=detailrm&id=<?php echo $_GET["id"]; ?>&tgl=<?php echo $_GET["tgl"]; ?>&racik&ttdPio&&idrekammedis=<?= $_GET['idrekammedis'] ?>">Paraf</a>
                                        </center>
                                      </td>
                                    </tr>

                                    <?php $no += 1 ?>
                                  <?php endforeach ?>

                                </tbody>

                              </table>

                              <table class="table table-bordered" style="margin-top: -20px;">
                                <thead>
                                  <tr>
                                    <th></th>
                                    <th></th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td><b>Edukasi</b></td>
                                    <td>
                                      <textarea name="edukasi" id="" style="width:100%; height:140px">
                      <?= $cekr['edukasi'] ?>
                    </textarea>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td><b>Paraf Pasien</b></td>
                                    <td>
                                      <center><img style="max-width: 60px; margin: 0px 0px 0px 0px;" src="img-qrcode/<?= $p['nama_lengkap'] ?>.png" alt=""><br>
                                        <p style="font-size:12px">(<?= $p['nama_lengkap'] ?>)</p>
                                        <a class="btn btn-sm btn-success" href="index.php?halaman=detailrm&id=<?php echo $_GET["id"]; ?>&tgl=<?php echo $_GET["tgl"]; ?>&racik&ttdPas&&idrekammedis=<?= $_GET['idrekammedis'] ?>">Paraf</a>
                                      </center>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                              <br>
                              <center>
                                <div style="max-width: 90%;">
                                  <div class="row">
                                    <div class="col-6">
                                      <div style="padding : 20px; border: solid black 1px;">
                                        <h6>Persetujuan Perubahan Resep</h6><br>
                                        <center><img style="max-width: 60px; margin: 0px 0px 0px 0px;" src="img-qrcode/<?= $cekr['petugas'] ?>.png" alt=""><br>
                                          <p style="font-size:12px">(<?= $cekr['petugas'] ?>)</p>
                                          <a class="btn btn-sm btn-success" href="index.php?halaman=detailrm&id=<?php echo $_GET["id"]; ?>&tgl=<?php echo $_GET["tgl"]; ?>&racik&ttdSet&&idrekammedis=<?= $_GET['idrekammedis'] ?>">Paraf</a>
                                        </center>
                                      </div>
                                    </div>
                                    <div class="col-6">
                                      <div style="padding : 20px; border: solid black 1px;">
                                        <h6>Petugas Farmasi</h6><br>
                                        <center><img style="max-width: 60px; margin: 0px 0px 0px 0px;" src="img-qrcode/<?= $cekr['petugas'] ?>.png" alt=""><br>
                                          <p style="font-size:12px">(<?= $cekr['petugas'] ?>)</p>
                                          <a class="btn btn-sm btn-success" href="index.php?halaman=detailrm&id=<?php echo $_GET["id"]; ?>&tgl=<?php echo $_GET["tgl"]; ?>&racik&ttdFar&&idrekammedis=<?= $_GET['idrekammedis'] ?>">Paraf</a>
                                        </center>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <a target="_blank" href="../rekammedis/e-resep.php?id=<?= $_GET['id'] ?>&tgl=<?= $_GET['tgl'] ?>&idrekammedis=<?= $_GET['idrekammedis'] ?>" class="btn btn-secondary mt-3"><i class="bi bi-printer mt-3"></i> Print</a>
                              </center>
                              <br>

                            </div>
                          <?php } ?>
                        </form>

                        <?php
                        if (isset($_GET['ttdPet'])) {
                          require '../phpqrcode/qrlib.php';
                          $tempdir = "img-qrcode/";
                          if (!file_exists($tempdir))   mkdir($tempdir, 0755);
                          $file_name = $_SESSION['admin']['namalengkap'] . ".png";
                          $file_path = $tempdir . $file_name;
                          QRcode::png($_SESSION['admin']['namalengkap'], $file_path, "H", 6, 4);
                          /* param (1)qrcontent,(2)filename,(3)errorcorrectionlevel,(4)pixelwidth,(5)margin */
                          // echo "<p class='result'>Hasil QRcode :</p>";
                          // echo "<p><img src='".$file_path."' /></p>";
                          echo "
                  <script>
                      document.location.href='index.php?halaman=detailrm&id=$_GET[id]&tgl=$_GET[tgl]&racik&idrekammedis=$_GET[idrekammedis]';
                  </script>
              ";
                        }

                        if (isset($_GET['ttdPio'])) {
                          require '../phpqrcode/qrlib.php';
                          $tempdir = "img-qrcode/";
                          if (!file_exists($tempdir))   mkdir($tempdir, 0755);
                          $file_name = $_SESSION['admin']['namalengkap'] . ".png";
                          $file_path = $tempdir . $file_name;
                          QRcode::png($_SESSION['admin']['namalengkap'], $file_path, "H", 6, 4);
                          /* param (1)qrcontent,(2)filename,(3)errorcorrectionlevel,(4)pixelwidth,(5)margin */
                          // echo "<p class='result'>Hasil QRcode :</p>";
                          // echo "<p><img src='".$file_path."' /></p>";
                          echo "
                  <script>
                      document.location.href='index.php?halaman=detailrm&id=$_GET[id]&tgl=$_GET[tgl]&racik&idrekammedis=$_GET[idrekammedis]';
                  </script>
              ";
                        }

                        if (isset($_GET['ttdPas'])) {
                          require '../phpqrcode/qrlib.php';
                          $tempdir = "img-qrcode/";
                          if (!file_exists($tempdir))   mkdir($tempdir, 0755);
                          $file_name = $p['nama_lengkap'] . ".png";
                          $file_path = $tempdir . $file_name;
                          QRcode::png($p['nama_lengkap'], $file_path, "H", 6, 4);
                          /* param (1)qrcontent,(2)filename,(3)errorcorrectionlevel,(4)pixelwidth,(5)margin */
                          // echo "<p class='result'>Hasil QRcode :</p>";
                          // echo "<p><img src='".$file_path."' /></p>";
                          echo "
                  <script>
                      document.location.href='index.php?halaman=detailrm&id=$_GET[id]&tgl=$_GET[tgl]&racik&idrekammedis=$_GET[idrekammedis]';
                  </script>
              ";
                        }

                        if (isset($_GET['ttdSet'])) {
                          require '../phpqrcode/qrlib.php';
                          $tempdir = "img-qrcode/";
                          if (!file_exists($tempdir))   mkdir($tempdir, 0755);
                          $file_name = $_SESSION['admin']['namalengkap'] . ".png";
                          $file_path = $tempdir . $file_name;
                          QRcode::png($_SESSION['admin']['namalengkap'], $file_path, "H", 6, 4);
                          /* param (1)qrcontent,(2)filename,(3)errorcorrectionlevel,(4)pixelwidth,(5)margin */
                          // echo "<p class='result'>Hasil QRcode :</p>";
                          // echo "<p><img src='".$file_path."' /></p>";
                          echo "
                  <script>
                      document.location.href='index.php?halaman=detailrm&id=$_GET[id]&tgl=$_GET[tgl]&racik&idrekammedis=$_GET[idrekammedis]';
                  </script>
              ";
                        }

                        if (isset($_GET['ttdFar'])) {
                          require '../phpqrcode/qrlib.php';
                          $tempdir = "img-qrcode/";
                          if (!file_exists($tempdir))   mkdir($tempdir, 0755);
                          $file_name = $_SESSION['admin']['namalengkap'] . ".png";
                          $file_path = $tempdir . $file_name;
                          QRcode::png($_SESSION['admin']['namalengkap'], $file_path, "H", 6, 4);
                          /* param (1)qrcontent,(2)filename,(3)errorcorrectionlevel,(4)pixelwidth,(5)margin */
                          // echo "<p class='result'>Hasil QRcode :</p>";
                          // echo "<p><img src='".$file_path."' /></p>";
                          echo "
                  <script>
                      document.location.href='index.php?halaman=detailrm&id=$_GET[id]&tgl=$_GET[tgl]&racik&idrekammedis=$_GET[idrekammedis]';
                  </script>
              ";
                        }

                        if (isset($_POST['save'])) {

                          // var_dump('hasil');
                          $koneksi->query("INSERT INTO resep 

              (serah_obat, tepat1, tepat2, tepat3, tepat4, tepat5, no_rm, jadwal, noresep, adminid, petugas)
          
              VALUES ('$_POST[serah_obat]', '$_POST[tepat1]', '$_POST[tepat2]', '$_POST[tepat3]', '$_POST[tepat4]', '$_POST[tepat5]', '$_GET[id]', '$_GET[tgl]', '$_POST[noresep]', '" . $_SESSION['admin']['idadmin'] . "', '" . $_SESSION['admin']['namalengkap'] . "')
          
              ");
                          $koneksi->query("UPDATE registrasi_rawat SET apoteker_check_at = '" . date('Y-m-d H:i:s') . "' WHERE jadwal = '" . $_GET['tgl'] . "'");
                          echo "
              <script>
              alert('Berhasil' );
              
                  document.location.href='index.php?halaman=detailrm&id=$_GET[id]&tgl=$_GET[tgl]&racik&idrekammedis=$_GET[idrekammedis]';
              </script>
              ";
                        }

                        if (isset($_POST['tel'])) {

                          $koneksi->query("INSERT INTO telaah_resep 

              (pa1, pa2, pa3, pa4, pa5, pa6,pa7,pa8,pa9,pa10,pa11,pa12,pa13,pa14,pa15,pa16,pa17, edukasi, no_rm, jadwal, adminid, petugas)
          
              VALUES ('$_POST[pa1]', '$_POST[pa2]', '$_POST[pa3]', '$_POST[pa4]', '$_POST[pa5]', '$_POST[pa6]','$_POST[pa7]','$_POST[pa8]','$_POST[pa9]','$_POST[pa10]','$_POST[pa11]','$_POST[pa12]','$_POST[pa13]','$_POST[pa14]','$_POST[pa15]','$_POST[pa16]', '$_POST[pa17]', '$_POST[edukasi]', '$_GET[id]', '$_GET[tgl]', '" . $_SESSION['admin']['idadmin'] . "', '" . $_SESSION['admin']['namalengkap'] . "')
          
              ");

                          echo "
              <script>
              alert('Berhasil update ' );

                  document.location.href='index.php?halaman=detailrm&id=$_GET[id]&tgl=$_GET[tgl]&racik&idrekammedis=$_GET[idrekammedis]';
              </script>
              ";
                        }

                        ?>

                      </div><!-- End Multi Columns Form -->
                    </div>
                </div>
              </div>




            <?php } ?>


            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


</body>

</html>