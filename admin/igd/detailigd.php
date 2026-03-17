<?php
error_reporting(0);
$username = $_SESSION['admin']['username'];
$level = $_SESSION['admin']['level'];
$shift = $_SESSION['shift'];

// Query data IGD
$igd = $koneksi->query("SELECT * FROM igd WHERE idigd='" . htmlspecialchars($_GET['id']) . "'");
$igd = $igd->fetch_assoc();

// Query data pemeriksaan fisik
$pemeriksaanFisik = $koneksi->query("SELECT * FROM pemeriksaan_fisik_igd WHERE id_igd='" . htmlspecialchars($_GET['id']) . "'");
$pf = $pemeriksaanFisik->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Detail IGD - KHM WONOREJO</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <style>
    @media print {
      .no-print {
        display: none !important;
      }

      .card {
        page-break-inside: avoid;
      }
    }

    .section-title {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 12px 20px;
      border-radius: 8px;
      margin-top: 20px;
      margin-bottom: 15px;
      font-weight: 600;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .sub-section-title {
      background: #f8f9fa;
      padding: 10px 15px;
      border-left: 4px solid #667eea;
      margin-top: 15px;
      margin-bottom: 10px;
      font-weight: 600;
      color: #333;
    }

    .form-label {
      font-weight: 600;
      color: #495057;
      margin-bottom: 5px;
    }

    .form-control:disabled,
    .form-control[readonly] {
      background-color: #f8f9fa;
      border-color: #dee2e6;
    }

    .card {
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      border: none;
      border-radius: 10px;
    }

    .grid-2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 15px;
    }

    .grid-3 {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 15px;
    }

    .grid-4 {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 15px;
    }

    @media (max-width: 768px) {

      .grid-2,
      .grid-3,
      .grid-4 {
        grid-template-columns: 1fr;
      }
    }

    .btn-print {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
      padding: 10px 30px;
      border-radius: 8px;
      font-weight: 600;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .btn-print:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }
  </style>
</head>

<body>
  <main>
    <div class="">
      <div class="pagetitle no-print">
        <h1>Detail IGD</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?halaman=daftarigd" style="color:blue;">IGD</a></li>
            <li class="breadcrumb-item active">Detail Pasien</li>
          </ol>
        </nav>
      </div><!-- End Page Title -->

      <div class="row">
        <div class="col-md-12">
          <!-- Data Pasien -->
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="section-title mb-3">Data Pasien</h5>
              <div class="grid-2">
                <div>
                  <label class="form-label">Nama Pasien</label>
                  <input type="text" class="form-control" value="<?= $igd['nama_pasien'] ?>" readonly>
                </div>
                <div>
                  <label class="form-label">NO RM Pasien</label>
                  <input type="text" class="form-control" value="<?= $igd['no_rm'] ?>" readonly>
                </div>
                <div>
                  <label class="form-label">Dokter</label>
                  <input type="text" class="form-control" value="<?= $igd['dokter'] ?>" readonly>
                </div>
                <div>
                  <label class="form-label">Pembayaran</label>
                  <input type="text" class="form-control" value="<?= $igd['carabayar'] ?>" readonly>
                </div>
              </div>
            </div>
          </div>

          <!-- Asesmen Keperawatan -->
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="section-title">Asesmen Keperawatan</h5>

              <div class="grid-2 mb-3">
                <div>
                  <label class="form-label">Tanggal Masuk</label>
                  <input type="date" class="form-control" value="<?= $igd['tgl_masuk'] ?>" readonly>
                </div>
                <div>
                  <label class="form-label">Jam Masuk</label>
                  <input type="time" class="form-control" value="<?= $igd['jam_masuk'] ?>" readonly>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label">Sarana Transportasi Kedatangan</label>
                <input type="text" class="form-control" value="<?= $igd['transportasi'] ?>" readonly>
              </div>

              <div class="mb-3">
                <label class="form-label">Surat Pengantar Rujukan</label>
                <input type="text" class="form-control" value="<?= $igd['surat_pengantar'] ?>" readonly>
              </div>

              <div class="mb-3">
                <label class="form-label">Kondisi Pasien Tiba</label>
                <input type="text" class="form-control" value="<?= $igd['kondisi_tiba'] ?>" readonly>
              </div>

              <h6 class="sub-section-title">Identitas Pengantar Pasien</h6>
              <div class="grid-2">
                <div>
                  <label class="form-label">Nama Pengantar</label>
                  <input type="text" class="form-control" value="<?= $igd['nama_pengantar'] ?>" readonly>
                </div>
                <div>
                  <label class="form-label">Nomor Telepon Seluler Penanggung Jawab</label>
                  <input type="text" class="form-control" value="<?= $igd['notelp_pengantar'] ?>" readonly>
                </div>
              </div>

              <h6 class="sub-section-title">Riwayat Medis</h6>
              <div class="mb-3">
                <label class="form-label">Keluhan</label>
                <textarea class="form-control" rows="2" readonly><?= $igd['keluhan'] ?></textarea>
              </div>

              <div class="mb-3">
                <label class="form-label">Riwayat Penyakit Sebelumnya</label>
                <textarea class="form-control" rows="2" readonly><?= $igd['riw_penyakit'] ?></textarea>
              </div>

              <div class="mb-3">
                <label class="form-label">Riwayat Alergi</label>
                <textarea class="form-control" rows="2" readonly><?= $igd['riw_alergi'] ?></textarea>
              </div>

              <h6 class="sub-section-title">Tanda-Tanda Vital</h6>
              <div class="grid-4 mb-3">
                <div>
                  <label class="form-label">E</label>
                  <input type="text" class="form-control" value="<?= $igd['e'] ?>" readonly>
                </div>
                <div>
                  <label class="form-label">V</label>
                  <input type="text" class="form-control" value="<?= $igd['v'] ?>" readonly>
                </div>
                <div>
                  <label class="form-label">M</label>
                  <input type="text" class="form-control" value="<?= $igd['m'] ?>" readonly>
                </div>
                <div>
                  <label class="form-label">Saturasi Oksigen (%)</label>
                  <input type="text" class="form-control" value="<?= $igd['sat_oksigen'] ?>" readonly>
                </div>
              </div>

              <div class="grid-2 mb-3">
                <div>
                  <label class="form-label">TD (mmHg)</label>
                  <input type="text" class="form-control" value="<?= $igd['td'] ?>" readonly>
                </div>
                <div>
                  <label class="form-label">RR (kali/menit)</label>
                  <input type="text" class="form-control" value="<?= $igd['rr'] ?>" readonly>
                </div>
                <div>
                  <label class="form-label">Nadi (kali/menit)</label>
                  <input type="text" class="form-control" value="<?= $igd['n'] ?>" readonly>
                </div>
                <div>
                  <label class="form-label">Suhu Tubuh (°C)</label>
                  <input type="text" class="form-control" value="<?= $igd['s'] ?>" readonly>
                </div>
                <div>
                  <label class="form-label">GDA (mg/dL)</label>
                  <input type="text" class="form-control" value="<?= $igd['gda'] ?>" readonly>
                </div>
                <div>
                  <label class="form-label">Berat Badan (kg)</label>
                  <input type="text" class="form-control" value="<?= $igd['bb'] ?>" readonly>
                </div>
                <div>
                  <label class="form-label">Tinggi Badan (cm)</label>
                  <input type="text" class="form-control" value="<?= $igd['tb'] ?>" readonly>
                </div>
              </div>

              <h6 class="sub-section-title">Asesmen Nyeri</h6>
              <div class="grid-2 mb-3">
                <div>
                  <label class="form-label">Status Nyeri</label>
                  <input type="text" class="form-control" value="<?= $igd['asesmen_nyeri'] ?>" readonly>
                </div>
                <div>
                  <label class="form-label">Skala Nyeri</label>
                  <input type="text" class="form-control" value="<?= $igd['skala_nyeri'] ?>" readonly>
                </div>
              </div>

              <h6 class="sub-section-title">Skrining Status Gizi</h6>
              <div class="grid-2 mb-3">
                <div>
                  <label class="form-label">Risiko Luka Decubitus</label>
                  <input type="text" class="form-control" value="<?= $igd['resiko_decubitus'] ?>" readonly>
                </div>
                <div>
                  <label class="form-label">Penurunan BB (6 bulan terakhir)</label>
                  <input type="text" class="form-control" value="<?= $igd['penurunan_bb'] ?>" readonly>
                </div>
                <div>
                  <label class="form-label">Penurunan Asupan Makanan</label>
                  <input type="text" class="form-control" value="<?= $igd['penurunan_asupan'] ?>" readonly>
                </div>
                <div>
                  <label class="form-label">Gejala Gastrointestinal</label>
                  <input type="text" class="form-control" value="<?= $igd['gejala_gastro'] ?>" readonly>
                </div>
                <div>
                  <label class="form-label">Faktor Pemberat (Komorbid)</label>
                  <input type="text" class="form-control" value="<?= $igd['faktor_pemberat'] ?>" readonly>
                </div>
                <div>
                  <label class="form-label">Penurunan Kapasitas Fungsional</label>
                  <input type="text" class="form-control" value="<?= $igd['penurunan_fungsional'] ?>" readonly>
                </div>
              </div>

              <h6 class="sub-section-title">Pengkajian Psikososial</h6>
              <div class="grid-3 mb-3">
                <div>
                  <label class="form-label">Pengkajian Psikologis</label>
                  <input type="text" class="form-control" value="<?= $igd['psiko'] ?>" readonly>
                </div>
                <div>
                  <label class="form-label">Pengkajian Sosial</label>
                  <input type="text" class="form-control" value="<?= $igd['sosial'] ?>" readonly>
                </div>
                <div>
                  <label class="form-label">Bantuan Yang Dibutuhkan</label>
                  <input type="text" class="form-control" value="<?= $igd['bantuan'] ?>" readonly>
                </div>
              </div>

              <div class="grid-2 mb-3">
                <div>
                  <label class="form-label">Tindak Lanjut</label>
                  <input type="text" class="form-control" value="<?= $igd['tindak'] ?>" readonly>
                </div>
                <div>
                  <label class="form-label">Keterangan Rujukan</label>
                  <input type="text" class="form-control" value="<?= $igd['tindak_rujuk_keterangan'] ?>" readonly>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label">Perawat Yang Mengkaji</label>
                <input type="text" class="form-control" value="<?= $igd['perawat'] ?>" readonly>
              </div>
            </div>
          </div>

          <!-- Asesmen Medis -->
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="section-title">Asesmen Medis</h5>

              <div class="mb-3">
                <label class="form-label">Tanggal dan Jam</label>
                <input type="datetime-local" class="form-control" value="<?= $igd['tgl'] ?>" readonly>
              </div>

              <div class="mb-3">
                <label class="form-label">Subjektif</label>
                <textarea class="form-control" rows="4" readonly><?= $igd['sub'] ?></textarea>
              </div>

              <!-- <div class="mb-3">
                <label class="form-label">Objektif</label>
                <textarea class="form-control" rows="4" readonly><?= $igd['ob'] ?></textarea>
              </div> -->

              <div class="mb-3">
                <label class="form-label">Pemeriksaan Penunjang</label>
                <?php
                $penunjang_data = json_decode($igd['penunjang'], true);
                if (is_array($penunjang_data) && !empty($penunjang_data)) {
                  echo '<div class="row g-2">';
                  foreach ($penunjang_data as $foto) {
                    echo '<div class="col-md-3">';
                    echo '<img src="../igd/pemeriksaan_penunjang/' . htmlspecialchars($foto) . '" class="img-fluid rounded" style="max-height: 200px; object-fit: cover; cursor: pointer;" onclick="window.open(this.src, \'_blank\')">';
                    echo '</div>';
                  }
                  echo '</div>';
                } else {
                  echo '<p class="text-muted">Tidak ada foto pemeriksaan penunjang</p>';
                }
                ?>
              </div>

              <div class="mb-3">
                <label class="form-label">Diagnosa Kerja & ICD10</label>
                <?php
                $dkerja_list = !empty($igd['dkerja']) ? explode(' + ', $igd['dkerja']) : [];
                $icd10_list = !empty($igd['icd10']) ? explode(' + ', $igd['icd10']) : [];

                if (!empty($dkerja_list) && $dkerja_list[0] != '') {
                  echo '<div class="border rounded p-3" style="background: #f8f9fa;">';
                  foreach ($dkerja_list as $index => $dkerja_item) {
                    $icd10_item = isset($icd10_list[$index]) ? trim($icd10_list[$index]) : '-';
                    echo '<div class="mb-2 pb-2' . ($index < count($dkerja_list) - 1 ? ' border-bottom' : '') . '">';
                    echo '<strong>' . ($index + 1) . '. ' . htmlspecialchars(trim($dkerja_item)) . '</strong>';
                    if ($icd10_item != '-' && $icd10_item != '') {
                      echo '<br><small class="text-muted">ICD10: ' . htmlspecialchars($icd10_item) . '</small>';
                    }
                    echo '</div>';
                  }
                  echo '</div>';
                } else {
                  echo '<input type="text" class="form-control" value="-" readonly>';
                }
                ?>
              </div>

              <div>
                <label class="form-label">Diagnosa Banding</label>
                <input type="text" class="form-control" value="<?= $igd['dbanding'] ?>" readonly>
              </div>

              <div class="mb-3">
                <label class="form-label">Planning/Rencana Rawat</label>
                <div class="border rounded p-3" style="background: #f8f9fa; min-height: 100px;">
                  <?= $igd['rencana_rawat'] ?>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label">Dokter Jaga</label>
                <input type="text" class="form-control" value="<?= $igd['dokter'] ?>" readonly>
              </div>
            </div>
          </div>

          <!-- Pemeriksaan Fisik Detail -->
          <?php if ($pf) { ?>
            <div class="card mb-3">
              <div class="card-body">
                <h5 class="section-title">Pemeriksaan Fisik Detail</h5>

                <h6 class="sub-section-title">Sistem Saraf</h6>
                <div class="grid-4 mb-3">
                  <div>
                    <label class="form-label">GCS E</label>
                    <input type="text" class="form-control" value="<?= $pf['gcs_e'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">GCS V</label>
                    <input type="text" class="form-control" value="<?= $pf['gcs_v'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">GCS M</label>
                    <input type="text" class="form-control" value="<?= $pf['gcs_m'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Kognitif</label>
                    <input type="text" class="form-control" value="<?= $pf['kognitif'] ?>" readonly>
                  </div>
                </div>

                <div class="grid-4 mb-3">
                  <div>
                    <label class="form-label">Rangsangan Meninggal</label>
                    <input type="text" class="form-control" value="<?= $pf['rangsangan_meninggal'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Refleks Fisiologis 1</label>
                    <input type="text" class="form-control" value="<?= $pf['refleks_fisiologis1'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Refleks Fisiologis 2</label>
                    <input type="text" class="form-control" value="<?= $pf['refleks_fisiologis2'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Refleks Patologis</label>
                    <input type="text" class="form-control" value="<?= $pf['refleks_patologis'] ?>" readonly>
                  </div>
                </div>

                <h6 class="sub-section-title">Sistem Penglihatan</h6>
                <div class="grid-4 mb-3">
                  <div>
                    <label class="form-label">Anemis Kiri</label>
                    <input type="text" class="form-control" value="<?= $pf['anemis_kiri'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Anemis Kanan</label>
                    <input type="text" class="form-control" value="<?= $pf['anemis_kanan'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Ikterik Kiri</label>
                    <input type="text" class="form-control" value="<?= $pf['ikterik_kiri'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Ikterik Kanan</label>
                    <input type="text" class="form-control" value="<?= $pf['ikterik_kanan'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">RCL Kiri</label>
                    <input type="text" class="form-control" value="<?= $pf['rcl_kiri'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">RCL Kanan</label>
                    <input type="text" class="form-control" value="<?= $pf['rcl_kanan'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Pupil Kiri</label>
                    <input type="text" class="form-control" value="<?= $pf['pupil_kiri'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Pupil Kanan</label>
                    <input type="text" class="form-control" value="<?= $pf['pupil_kanan'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Visus Kiri</label>
                    <input type="text" class="form-control" value="<?= $pf['visus_kiri'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Visus Kanan</label>
                    <input type="text" class="form-control" value="<?= $pf['visus_kanan'] ?>" readonly>
                  </div>
                </div>

                <h6 class="sub-section-title">THT</h6>
                <div class="grid-4 mb-3">
                  <div>
                    <label class="form-label">NCH Kiri</label>
                    <input type="text" class="form-control" value="<?= $pf['nch_kiri'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">NCH Kanan</label>
                    <input type="text" class="form-control" value="<?= $pf['nch_kanan'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Polip Kiri</label>
                    <input type="text" class="form-control" value="<?= $pf['polip_kiri'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Polip Kanan</label>
                    <input type="text" class="form-control" value="<?= $pf['polip_kanan'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Conca Kiri</label>
                    <input type="text" class="form-control" value="<?= $pf['conca_kiri'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Conca Kanan</label>
                    <input type="text" class="form-control" value="<?= $pf['conca_kanan'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Faring Hipertermis</label>
                    <input type="text" class="form-control" value="<?= $pf['faring_hipertermis'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Halitosis</label>
                    <input type="text" class="form-control" value="<?= $pf['halitosis'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Pembesaran Tonsil</label>
                    <input type="text" class="form-control" value="<?= $pf['pembesaran_tonsil'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Serumin Kiri</label>
                    <input type="text" class="form-control" value="<?= $pf['serumin_kiri'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Serumin Kanan</label>
                    <input type="text" class="form-control" value="<?= $pf['serumin_kanan'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Tympani Intak Kiri</label>
                    <input type="text" class="form-control" value="<?= $pf['typani_intak_kiri'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Tympani Intak Kanan</label>
                    <input type="text" class="form-control" value="<?= $pf['typani_intak_kanan'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Pembesaran Getah Bening</label>
                    <input type="text" class="form-control" value="<?= $pf['pembesaran_getah_bening'] ?>" readonly>
                  </div>
                </div>

                <h6 class="sub-section-title">Sistem Pernafasan</h6>
                <div class="grid-3 mb-3">
                  <div>
                    <label class="form-label">Torax</label>
                    <input type="text" class="form-control" value="<?= $pf['torax'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Retraksi</label>
                    <input type="text" class="form-control" value="<?= $pf['retraksi'] ?>" readonly>
                  </div>
                </div>

                <div class="grid-2 mb-3">
                  <div>
                    <label class="form-label">Vesikuler Kiri</label>
                    <input type="text" class="form-control" value="<?= $pf['vesikuler_kiri'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Vesikuler Kanan</label>
                    <input type="text" class="form-control" value="<?= $pf['vesikuler_kanan'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Wheezing Kiri</label>
                    <input type="text" class="form-control" value="<?= $pf['wheezing_kiri'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Wheezing Kanan</label>
                    <input type="text" class="form-control" value="<?= $pf['wheezing_kanan'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Rongki Kiri</label>
                    <input type="text" class="form-control" value="<?= $pf['rongki_kiri'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Rongki Kanan</label>
                    <input type="text" class="form-control" value="<?= $pf['rongki_kanan'] ?>" readonly>
                  </div>
                </div>

                <h6 class="sub-section-title">Sistem Jantung</h6>
                <div class="grid-3 mb-3">
                  <div>
                    <label class="form-label">S1 S2</label>
                    <input type="text" class="form-control" value="<?= $pf['s1s2'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Murmur</label>
                    <input type="text" class="form-control" value="<?= $pf['murmur'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Gallop</label>
                    <input type="text" class="form-control" value="<?= $pf['golop'] ?>" readonly>
                  </div>
                </div>

                <h6 class="sub-section-title">Sistem Pencernaan</h6>
                <div class="grid-4 mb-3">
                  <div>
                    <label class="form-label">Flat</label>
                    <input type="text" class="form-control" value="<?= $pf['flat'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Hepar & Lien</label>
                    <input type="text" class="form-control" value="<?= $pf['hl'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Assistos</label>
                    <input type="text" class="form-control" value="<?= $pf['assistos'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Thympani</label>
                    <input type="text" class="form-control" value="<?= $pf['thympani'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Soepel</label>
                    <input type="text" class="form-control" value="<?= $pf['soepel'] ?>" readonly>
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label"><strong>Nyeri Tekan Fossa (NTF)</strong></label>
                  <div class="grid-3">
                    <div>
                      <label class="form-label small">NTF Atas Kiri</label>
                      <input type="text" class="form-control form-control-sm" value="<?= $pf['ntf_atas_kiri'] ?>" readonly>
                    </div>
                    <div>
                      <label class="form-label small">NTF Atas</label>
                      <input type="text" class="form-control form-control-sm" value="<?= $pf['ntf_atas'] ?>" readonly>
                    </div>
                    <div>
                      <label class="form-label small">NTF Atas Kanan</label>
                      <input type="text" class="form-control form-control-sm" value="<?= $pf['ntf_atas_kanan'] ?>" readonly>
                    </div>
                    <div>
                      <label class="form-label small">NTF Tengah Kiri</label>
                      <input type="text" class="form-control form-control-sm" value="<?= $pf['ntf_tengah_kiri'] ?>" readonly>
                    </div>
                    <div>
                      <label class="form-label small">NTF Tengah</label>
                      <input type="text" class="form-control form-control-sm" value="<?= $pf['ntf_tengah'] ?>" readonly>
                    </div>
                    <div>
                      <label class="form-label small">NTF Tengah Kanan</label>
                      <input type="text" class="form-control form-control-sm" value="<?= $pf['ntf_tengah_kanan'] ?>" readonly>
                    </div>
                    <div>
                      <label class="form-label small">NTF Bawah Kiri</label>
                      <input type="text" class="form-control form-control-sm" value="<?= $pf['ntf_bawah_kiri'] ?>" readonly>
                    </div>
                    <div>
                      <label class="form-label small">NTF Bawah</label>
                      <input type="text" class="form-control form-control-sm" value="<?= $pf['ntf_bawah'] ?>" readonly>
                    </div>
                    <div>
                      <label class="form-label small">NTF Bawah Kanan</label>
                      <input type="text" class="form-control form-control-sm" value="<?= $pf['ntf_bawah_kanan'] ?>" readonly>
                    </div>
                  </div>
                </div>

                <div class="grid-3 mb-3">
                  <div>
                    <label class="form-label">Bising Usus (BU)</label>
                    <input type="text" class="form-control" value="<?= $pf['bu'] ?>" readonly>
                  </div>
                  <div>
                    <label class="form-label">Komentar BU</label>
                    <input type="text" class="form-control" value="<?= $pf['bu_komen'] ?>" readonly>
                  </div>
                </div>

                <h6 class="sub-section-title">Ekstremitas</h6>
                <div class="mb-3">
                  <label class="form-label"><strong>Akral Hangat</strong></label>
                  <div class="grid-4">
                    <div>
                      <label class="form-label small">Atas Kiri</label>
                      <input type="text" class="form-control form-control-sm" value="<?= $pf['akral_hangat_atas_kiri'] ?>" readonly>
                    </div>
                    <div>
                      <label class="form-label small">Atas Kanan</label>
                      <input type="text" class="form-control form-control-sm" value="<?= $pf['akral_hangat_atas_kanan'] ?>" readonly>
                    </div>
                    <div>
                      <label class="form-label small">Bawah Kiri</label>
                      <input type="text" class="form-control form-control-sm" value="<?= $pf['akral_hangat_bawah_kiri'] ?>" readonly>
                    </div>
                    <div>
                      <label class="form-label small">Bawah Kanan</label>
                      <input type="text" class="form-control form-control-sm" value="<?= $pf['akral_hangat_bawah_kanan'] ?>" readonly>
                    </div>
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label"><strong>Oedema (OE)</strong></label>
                  <div class="grid-4">
                    <div>
                      <label class="form-label small">Atas Kiri</label>
                      <input type="text" class="form-control form-control-sm" value="<?= $pf['oe_atas_kiri'] ?>" readonly>
                    </div>
                    <div>
                      <label class="form-label small">Atas Kanan</label>
                      <input type="text" class="form-control form-control-sm" value="<?= $pf['oe_atas_kanan'] ?>" readonly>
                    </div>
                    <div>
                      <label class="form-label small">Bawah Kiri</label>
                      <input type="text" class="form-control form-control-sm" value="<?= $pf['oe_bawah_kiri'] ?>" readonly>
                    </div>
                    <div>
                      <label class="form-label small">Bawah Kanan</label>
                      <input type="text" class="form-control form-control-sm" value="<?= $pf['oe_bawah_kanan'] ?>" readonly>
                    </div>
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label"><strong>Kekuatan Motorik</strong></label>
                  <div class="grid-4">
                    <div>
                      <label class="form-label small">Atas Kiri</label>
                      <input type="text" class="form-control form-control-sm" value="<?= $pf['motorik_atas_kiri'] ?>" readonly>
                    </div>
                    <div>
                      <label class="form-label small">Atas Kanan</label>
                      <input type="text" class="form-control form-control-sm" value="<?= $pf['motorik_atas_kanan'] ?>" readonly>
                    </div>
                    <div>
                      <label class="form-label small">Bawah Kiri</label>
                      <input type="text" class="form-control form-control-sm" value="<?= $pf['motorik_bawah_kiri'] ?>" readonly>
                    </div>
                    <div>
                      <label class="form-label small">Bawah Kanan</label>
                      <input type="text" class="form-control form-control-sm" value="<?= $pf['motorik_bawah_kanan'] ?>" readonly>
                    </div>
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label">CRT (Capillary Refill Time)</label>
                  <input type="text" class="form-control" value="<?= $pf['crt'] ?>" readonly>
                </div>
              </div>
            </div>
          <?php } ?>

          <!-- Tombol Aksi -->
          <div class="card p-2">
            <div>
              <center>
                <a href="index.php?halaman=ubahigd&id=<?= $_GET['id'] ?>" class="btn btn-sm btn-primary me-2">
                  <i class="bi bi-pencil-square"></i> Edit Data
                </a>
                <a href="index.php?halaman=daftarigd" class="btn btn-sm btn-secondary">
                  <i class="bi bi-arrow-left"></i> Kembali
                </a>
              </center>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center no-print"><i class="bi bi-arrow-up-short"></i></a>

</body>

</html>