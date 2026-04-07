<?php
// error_reporting(0);
$date = date("Y-m-d");
date_default_timezone_set('Asia/Jakarta');

$noResepObat = $_GET['id'] . date("ymdhis");

if (isset($_GET['tandaiSelesaiIsiIGD'])) {
  $koneksi->query("UPDATE igd SET rencana_rawat_at_poli = '" . date('Y-m-d H:i:s') . "' WHERE idigd = '$_GET[tandaiSelesaiIsiIGD]'");
  $getPickRm = $koneksi->query("SELECT * FROM igd_pick_rm WHERE igd_id = '$_GET[tandaiSelesaiIsiIGD]'")->fetch_assoc();

  $koneksi->query("UPDATE registrasi_rawat SET status_antri = 'Pembayaran' WHERE idrawat = '$getPickRm[registrasi_id]'");
  echo "
    <script>
      alert('Berhasil diTandai Sebagai Selesai');
      window.close();
    </script>
  ";
  exit();
}

$username = $_SESSION['admin']['username'];
$ambil = $koneksi->query("SELECT * FROM admin  WHERE username='$username';");

// ==========================================
// BAGIAN ACTION - DIEKSEKUSI TERLEBIH DAHULU
// ==========================================

// Action: Selesai Obat
if (isset($_POST['selesai'])) {
  $getLastRM = $koneksi->query("SELECT  *, COUNT(*) AS jumm, MAX(id_rm) as id_rm, MAX(jadwal) as jadwall FROM rekam_medis WHERE norm = '" . htmlspecialchars($_GET['id']) . "' AND DATE_FORMAT(jadwal, '%Y-%m-%d') <= '" . date('Y-m-d', strtotime($_GET['tgl'])) . "' ORDER BY id_rm DESC LIMIT 1")->fetch_assoc();
  $koneksi->query("UPDATE obat_rm SET status_obat='selesai' WHERE rekam_medis_id='$getLastRM[id_rm]'");
  echo "<script>alert('Status berhasil diubah menjadi selesai'); document.location.href='index.php?halaman=daftarrmedis';</script>";
  exit;
}

if(isset($_GET['masukODC'])) {
  // Collect All Data 
  $getRekamMedis = $koneksi->query("SELECT * FROM rekam_medis WHERE id_rm = '$_GET[rekamMedisId]'")->fetch_assoc();
  $getRawatRegistrasi = $koneksi->query("SELECT * FROM registrasi_rawat WHERE no_rm = '$getRekamMedis[norm]' AND jadwal = '$getRekamMedis[jadwal]' ORDER BY idrawat DESC LIMIT 1")->fetch_assoc();

  $idrawat = $getRawatRegistrasi['idrawat'];
  // $getRawatRegistrasi = $koneksi->query("SELECT * FROM registrasi_rawat WHERE idrawat = '$idrawat'")->fetch_assoc();
  // $getRekamMedis = $koneksi->query("SELECT * FROM rekam_medis WHERE jadwal = '$getRawatRegistrasi[jadwal]' AND norm = '$getRawatRegistrasi[no_rm]'")->fetch_assoc();
  $getPemeriksaanFisik = $koneksi->query("SELECT * FROM pemeriksaan_fisik WHERE id_regis = '$idrawat'")->fetch_assoc();
  $getKajianAwal = $koneksi->query("SELECT * FROM kajian_awal WHERE norm = '$getRekamMedis[norm]' ORDER BY id_rm DESC LIMIT 1")->fetch_assoc();

  $getLastIdIGD = $koneksi->query("SELECT idigd FROM igd ORDER BY idigd DESC LIMIT 1")->fetch_assoc();

  // End Collect All Data

  $lastIgdId = isset($getLastIdIGD['idigd']) ? intval($getLastIdIGD['idigd']) : 0;
  $idigd = $lastIgdId + 1;

  // Cari idigd yang benar-benar belum terpakai di igd_pick_rm
  do {
    $checkDoubleIGD = $koneksi->query("SELECT COUNT(*) AS jum FROM igd_pick_rm WHERE igd_id='$idigd'")->fetch_assoc();
    if (intval($checkDoubleIGD['jum']) > 0) {
      $idigd++;
    }
  } while (intval($checkDoubleIGD['jum']) > 0);

  $tgl_masuk = date('Y-m-d', strtotime($getRawatRegistrasi['jadwal']));
  $jam_masuk = date('H:i:s', strtotime($getRawatRegistrasi['jadwal']));

  // INSERT TO PEMERIKSAAN FISIK IGD
  $koneksi->query("INSERT INTO `pemeriksaan_fisik_igd`(`id_igd`, `norm`, `gcs_e`, `gcs_v`, `gcs_m`, `rangsangan_meninggal`, `refleks_fisiologis1`, `refleks_fisiologis2`, `refleks_patologis`, `flat`, `hl`, `assistos`, `thympani`, `soepel`, `ntf_atas_kiri`, `ntf_atas`, `ntf_atas_kanan`, `ntf_tengah_kiri`, `ntf_tengah`, `ntf_tengah_kanan`, `ntf_bawah_kiri`, `ntf_bawah`, `ntf_bawah_kanan`, `bu`, `bu_komen`, `anemis_kiri`, `anemis_kanan`, `ikterik_kiri`, `ikterik_kanan`, `rcl_kiri`, `rcl_kanan`, `pupil_kiri`, `pupil_kanan`, `visus_kiri`, `visus_kanan`, `torax`, `retraksi`, `vesikuler_kiri`, `vesikuler_kanan`, `wheezing_kiri`, `wheezing_kanan`, `rongki_kiri`, `rongki_kanan`, `s1s2`, `murmur`, `golop`, `nch_kiri`, `nch_kanan`, `polip_kiri`, `polip_kanan`, `conca_kiri`, `conca_kanan`, `faring_hipertermis`, `halitosis`, `pembesaran_tonsil`, `serumin_kiri`, `serumin_kanan`, `typani_intak_kiri`, `typani_intak_kanan`, `pembesaran_getah_bening`, `akral_hangat_atas_kiri`, `akral_hangat_atas_kanan`, `akral_hangat_bawah_kiri`, `akral_hangat_bawah_kanan`, `oe_atas_kiri`, `oe_atas_kanan`, `oe_bawah_kiri`, `oe_bawah_kanan`, `crt`, `motorik_atas_kiri`, `motorik_atas_kanan`, `motorik_bawah_kiri`, `motorik_bawah_kanan`, `kognitif`, `created_at`) VALUES ('$idigd','$getPemeriksaanFisik[norm]','$getPemeriksaanFisik[gcs_e]','$getPemeriksaanFisik[gcs_v]','$getPemeriksaanFisik[gcs_m]','$getPemeriksaanFisik[rangsangan_meninggal]','$getPemeriksaanFisik[refleks_fisiologis1]','$getPemeriksaanFisik[refleks_fisiologis2]','$getPemeriksaanFisik[refleks_patologis]','$getPemeriksaanFisik[flat]','$getPemeriksaanFisik[hl]','$getPemeriksaanFisik[assistos]','$getPemeriksaanFisik[thympani]','$getPemeriksaanFisik[soepel]','$getPemeriksaanFisik[ntf_atas_kiri]','$getPemeriksaanFisik[ntf_atas]','$getPemeriksaanFisik[ntf_atas_kanan]','$getPemeriksaanFisik[ntf_tengah_kiri]','$getPemeriksaanFisik[ntf_tengah]','$getPemeriksaanFisik[ntf_tengah_kanan]','$getPemeriksaanFisik[ntf_bawah_kiri]','$getPemeriksaanFisik[ntf_bawah]','$getPemeriksaanFisik[ntf_bawah_kanan]','$getPemeriksaanFisik[bu]','$getPemeriksaanFisik[bu_komen]','$getPemeriksaanFisik[anemis_kiri]','$getPemeriksaanFisik[anemis_kanan]','$getPemeriksaanFisik[ikterik_kiri]','$getPemeriksaanFisik[ikterik_kanan]','$getPemeriksaanFisik[rcl_kiri]','$getPemeriksaanFisik[rcl_kanan]','$getPemeriksaanFisik[pupil_kiri]','$getPemeriksaanFisik[pupil_kanan]','$getPemeriksaanFisik[visus_kiri]','$getPemeriksaanFisik[visus_kanan]','$getPemeriksaanFisik[torax]','$getPemeriksaanFisik[retraksi]','$getPemeriksaanFisik[vesikuler_kiri]','$getPemeriksaanFisik[vesikuler_kanan]','$getPemeriksaanFisik[wheezing_kiri]','$getPemeriksaanFisik[wheezing_kanan]','$getPemeriksaanFisik[rongki_kiri]','$getPemeriksaanFisik[rongki_kanan]','$getPemeriksaanFisik[s1s2]','$getPemeriksaanFisik[murmur]','$getPemeriksaanFisik[golop]','$getPemeriksaanFisik[nch_kiri]','$getPemeriksaanFisik[nch_kanan]','$getPemeriksaanFisik[polip_kiri]','$getPemeriksaanFisik[polip_kanan]','$getPemeriksaanFisik[conca_kiri]','$getPemeriksaanFisik[conca_kanan]','$getPemeriksaanFisik[faring_hipertermis]','$getPemeriksaanFisik[halitosis]','$getPemeriksaanFisik[pembesaran_tonsil]','$getPemeriksaanFisik[serumin_kiri]','$getPemeriksaanFisik[serumin_kanan]','$getPemeriksaanFisik[typani_intak_kiri]','$getPemeriksaanFisik[typani_intak_kanan]','$getPemeriksaanFisik[pembesaran_getah_bening]','$getPemeriksaanFisik[akral_hangat_atas_kiri]','$getPemeriksaanFisik[akral_hangat_atas_kanan]','$getPemeriksaanFisik[akral_hangat_bawah_kiri]','$getPemeriksaanFisik[akral_hangat_bawah_kanan]','$getPemeriksaanFisik[oe_atas_kiri]','$getPemeriksaanFisik[oe_atas_kanan]','$getPemeriksaanFisik[oe_bawah_kiri]','$getPemeriksaanFisik[oe_bawah_kanan]','$getPemeriksaanFisik[crt]','$getPemeriksaanFisik[motorik_atas_kiri]','$getPemeriksaanFisik[motorik_atas_kanan]','$getPemeriksaanFisik[motorik_bawah_kiri]','$getPemeriksaanFisik[motorik_bawah_kanan]','$getPemeriksaanFisik[kognitif]','$getPemeriksaanFisik[created_at]')");

  $koneksi->query("INSERT INTO igd (idigd, tgl_masuk, no_rm, nama_pasien, jam_masuk, keluhan, riw_penyakit, riw_alergi, dkerja, icd10, tindak, psiko, bb, tb, sat_oksigen, tgl, carabayar) VALUES ('$idigd', '$tgl_masuk', '$getRekamMedis[norm]', '$getRekamMedis[nama_pasien]', '$jam_masuk', '$getKajianAwal[keluhan_utama]', '$getKajianAwal[riwayat_penyakit]', '$getKajianAwal[riwayat_alergi]', '$getRekamMedis[diagnosis]', '$getRekamMedis[icd]', 'ODC', '$getKajianAwal[psiko]', '$getKajianAwal[bb]', '$getKajianAwal[tb]', '$getKajianAwal[oksigen]', '$getRawatRegistrasi[jadwal]', '$getRawatRegistrasi[carabayar]');");

  $getPasien = $koneksi->query("SELECT * FROM pasien WHERE no_rm = '$getRekamMedis[norm]'")->fetch_assoc();

  $layanan = $koneksimaster->query("SELECT * FROM master_layanan WHERE nama_layanan = 'ODC'")->fetch_assoc();
  $koneksi->query("INSERT INTO `layanan`(`layanan`, `kode_layanan`, `harga`, `jumlah_layanan`, `id_pasien`, `idrm`, `tgl_layanan`) VALUES ('$layanan[nama_layanan]', '$layanan[id]', '$layanan[harga]', '1', '$getPasien[idpasien]', '$getRekamMedis[norm]', '$getRawatRegistrasi[jadwal]')");

  $getBiayaRawat = $koneksi->query("SELECT * FROM biaya_rawat WHERE idregis = '$idrawat'")->fetch_assoc();
  $biayaLayanan = $getBiayaRawat['biaya_lain'] . '+' . $layanan['nama_layanan'];
  $totalLain =  intval($getBiayaRawat['total_lain']) + intval($layanan['harga']);
  $koneksi->query("UPDATE biaya_rawat SET biaya_lain = '$biayaLayanan', total_lain = '$totalLain' WHERE idregis = '$idrawat'");

  $koneksi->query("INSERT INTO igddetail (id, tgl, biaya, besaran, ket, petugas, shiftinap) VALUES ('$idigd', '" . date('Y-m-d') . "', 'Layanan $layanan[nama_layanan]', '$layanan[harga]', 'Layanan $layanan[nama_layanan]', '" . $_SESSION['admin']['namalengkap'] . "', '" . $_SESSION['shift'] . "')");

  $koneksi->query("INSERT INTO igd_pick_rm (rekam_medis_id, registrasi_id, igd_id) VALUES ('$getRekamMedis[id_rm]', '$idrawat', '$idigd')");
  // END INSERT TO PEMERIKSAAN FISIK IGD

  $redirect = "index.php?halaman=daftarigd";
  echo "
    <script>
      alert('Pasien berhasil dimasukkan ke ODC');
      window.location.href='$redirect';
    </script>
  ";
  exit();
}

// Action: Save Rekam Medis
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

  $getRegistrasiRawat = $koneksi->query("SELECT * FROM registrasi_rawat  WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' ORDER BY idrawat DESC LIMIT 1")->fetch_assoc();
  $checkPickIgd = $koneksi->query("SELECT *, COUNT(*) AS jum FROM igd_pick_rm WHERE registrasi_id='$getRegistrasiRawat[idrawat]'")->fetch_assoc();

  if ($checkPickIgd['jum'] > 0) {
    $newId = $checkPickIgd['rekam_medis_id'];
  } else {
    $getLastRekamMedis = $koneksi->query("SELECT * FROM rekam_medis ORDER BY id_rm DESC LIMIT 1")->fetch_assoc();
    $newId = $getLastRekamMedis['id_rm'] + 1;

    $checkDoublePick = $koneksi->query("SELECT *, COUNT(*) AS jum FROM igd_pick_rm WHERE rekam_medis_id='$newId'")->fetch_assoc();

    if ($checkDoublePick['jum'] > 0) {
      $newId = $newId + 1;
    }
  }

  $koneksi->query("INSERT INTO rekam_medis(id_rm, nama_pasien, norm, jadwal, status_perokok, anamnesa, diagnosis, prognosa, icd, status_plg, id_pasien, gol_darah, dokter, kode_prognosa, objective, keluhan_utama) VALUES ('$newId', '$_POST[nama_pasien]', '$_GET[id]','$jadwal', '$status_perokok', '$anamnesa', '$diagnosis', '$prognosa', '$icd', '$status_pulang', '$id_pasien', '$_POST[gol_darah]', '$_POST[dokter]', '$prognosacode', '$objective', '" . htmlspecialchars($_POST['keluhan_utama']) . "')");

  $koneksi->query("INSERT INTO lab_poli (nama_pasien, jadwal, gula_darah, kolestrol, asam_urat) VALUES ('$_POST[nama_pasien]', '$jadwal', '$gula_darah', '$kolestrol', '$asam_urat')");

  $koneksi->query("UPDATE registrasi_rawat SET status_antri='Pembayaran', dokter_at = '" . date('Y-m-d H:i:s') . "' WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]'");

  $redirect = isset($_GET['inap']) ? "index.php?halaman=rmedis&inap&id=$_GET[id]&tgl=$_GET[tgl]" : "index.php?halaman=rmedis&id=$_GET[id]&tgl=$_GET[tgl]";

  if ($status_pulang == 'ODC') {
    // Collect All Data 
    $idrawat = $getRegistrasiRawat['idrawat'];
    $getRawatRegistrasi = $koneksi->query("SELECT * FROM registrasi_rawat WHERE idrawat = '$idrawat'")->fetch_assoc();
    $getRekamMedis = $koneksi->query("SELECT * FROM rekam_medis WHERE jadwal = '$getRawatRegistrasi[jadwal]' AND norm = '$getRawatRegistrasi[no_rm]'")->fetch_assoc();
    $getPemeriksaanFisik = $koneksi->query("SELECT * FROM pemeriksaan_fisik WHERE id_regis = '$idrawat'")->fetch_assoc();
    $getKajianAwal = $koneksi->query("SELECT * FROM kajian_awal WHERE norm = '$getRekamMedis[norm]' ORDER BY id_rm DESC LIMIT 1")->fetch_assoc();

    $getLastIdIGD = $koneksi->query("SELECT idigd FROM igd ORDER BY idigd DESC LIMIT 1")->fetch_assoc();

    // End Collect All Data

    $lastIgdId = isset($getLastIdIGD['idigd']) ? intval($getLastIdIGD['idigd']) : 0;
    $idigd = $lastIgdId + 1;

    // Cari idigd yang benar-benar belum terpakai di igd_pick_rm
    do {
      $checkDoubleIGD = $koneksi->query("SELECT COUNT(*) AS jum FROM igd_pick_rm WHERE igd_id='$idigd'")->fetch_assoc();
      if (intval($checkDoubleIGD['jum']) > 0) {
        $idigd++;
      }
    } while (intval($checkDoubleIGD['jum']) > 0);

    $tgl_masuk = date('Y-m-d', strtotime($getRawatRegistrasi['jadwal']));
    $jam_masuk = date('H:i:s', strtotime($getRawatRegistrasi['jadwal']));

    // INSERT TO PEMERIKSAAN FISIK IGD
    $koneksi->query("INSERT INTO `pemeriksaan_fisik_igd`(`id_igd`, `norm`, `gcs_e`, `gcs_v`, `gcs_m`, `rangsangan_meninggal`, `refleks_fisiologis1`, `refleks_fisiologis2`, `refleks_patologis`, `flat`, `hl`, `assistos`, `thympani`, `soepel`, `ntf_atas_kiri`, `ntf_atas`, `ntf_atas_kanan`, `ntf_tengah_kiri`, `ntf_tengah`, `ntf_tengah_kanan`, `ntf_bawah_kiri`, `ntf_bawah`, `ntf_bawah_kanan`, `bu`, `bu_komen`, `anemis_kiri`, `anemis_kanan`, `ikterik_kiri`, `ikterik_kanan`, `rcl_kiri`, `rcl_kanan`, `pupil_kiri`, `pupil_kanan`, `visus_kiri`, `visus_kanan`, `torax`, `retraksi`, `vesikuler_kiri`, `vesikuler_kanan`, `wheezing_kiri`, `wheezing_kanan`, `rongki_kiri`, `rongki_kanan`, `s1s2`, `murmur`, `golop`, `nch_kiri`, `nch_kanan`, `polip_kiri`, `polip_kanan`, `conca_kiri`, `conca_kanan`, `faring_hipertermis`, `halitosis`, `pembesaran_tonsil`, `serumin_kiri`, `serumin_kanan`, `typani_intak_kiri`, `typani_intak_kanan`, `pembesaran_getah_bening`, `akral_hangat_atas_kiri`, `akral_hangat_atas_kanan`, `akral_hangat_bawah_kiri`, `akral_hangat_bawah_kanan`, `oe_atas_kiri`, `oe_atas_kanan`, `oe_bawah_kiri`, `oe_bawah_kanan`, `crt`, `motorik_atas_kiri`, `motorik_atas_kanan`, `motorik_bawah_kiri`, `motorik_bawah_kanan`, `kognitif`, `created_at`) VALUES ('$idigd','$getPemeriksaanFisik[norm]','$getPemeriksaanFisik[gcs_e]','$getPemeriksaanFisik[gcs_v]','$getPemeriksaanFisik[gcs_m]','$getPemeriksaanFisik[rangsangan_meninggal]','$getPemeriksaanFisik[refleks_fisiologis1]','$getPemeriksaanFisik[refleks_fisiologis2]','$getPemeriksaanFisik[refleks_patologis]','$getPemeriksaanFisik[flat]','$getPemeriksaanFisik[hl]','$getPemeriksaanFisik[assistos]','$getPemeriksaanFisik[thympani]','$getPemeriksaanFisik[soepel]','$getPemeriksaanFisik[ntf_atas_kiri]','$getPemeriksaanFisik[ntf_atas]','$getPemeriksaanFisik[ntf_atas_kanan]','$getPemeriksaanFisik[ntf_tengah_kiri]','$getPemeriksaanFisik[ntf_tengah]','$getPemeriksaanFisik[ntf_tengah_kanan]','$getPemeriksaanFisik[ntf_bawah_kiri]','$getPemeriksaanFisik[ntf_bawah]','$getPemeriksaanFisik[ntf_bawah_kanan]','$getPemeriksaanFisik[bu]','$getPemeriksaanFisik[bu_komen]','$getPemeriksaanFisik[anemis_kiri]','$getPemeriksaanFisik[anemis_kanan]','$getPemeriksaanFisik[ikterik_kiri]','$getPemeriksaanFisik[ikterik_kanan]','$getPemeriksaanFisik[rcl_kiri]','$getPemeriksaanFisik[rcl_kanan]','$getPemeriksaanFisik[pupil_kiri]','$getPemeriksaanFisik[pupil_kanan]','$getPemeriksaanFisik[visus_kiri]','$getPemeriksaanFisik[visus_kanan]','$getPemeriksaanFisik[torax]','$getPemeriksaanFisik[retraksi]','$getPemeriksaanFisik[vesikuler_kiri]','$getPemeriksaanFisik[vesikuler_kanan]','$getPemeriksaanFisik[wheezing_kiri]','$getPemeriksaanFisik[wheezing_kanan]','$getPemeriksaanFisik[rongki_kiri]','$getPemeriksaanFisik[rongki_kanan]','$getPemeriksaanFisik[s1s2]','$getPemeriksaanFisik[murmur]','$getPemeriksaanFisik[golop]','$getPemeriksaanFisik[nch_kiri]','$getPemeriksaanFisik[nch_kanan]','$getPemeriksaanFisik[polip_kiri]','$getPemeriksaanFisik[polip_kanan]','$getPemeriksaanFisik[conca_kiri]','$getPemeriksaanFisik[conca_kanan]','$getPemeriksaanFisik[faring_hipertermis]','$getPemeriksaanFisik[halitosis]','$getPemeriksaanFisik[pembesaran_tonsil]','$getPemeriksaanFisik[serumin_kiri]','$getPemeriksaanFisik[serumin_kanan]','$getPemeriksaanFisik[typani_intak_kiri]','$getPemeriksaanFisik[typani_intak_kanan]','$getPemeriksaanFisik[pembesaran_getah_bening]','$getPemeriksaanFisik[akral_hangat_atas_kiri]','$getPemeriksaanFisik[akral_hangat_atas_kanan]','$getPemeriksaanFisik[akral_hangat_bawah_kiri]','$getPemeriksaanFisik[akral_hangat_bawah_kanan]','$getPemeriksaanFisik[oe_atas_kiri]','$getPemeriksaanFisik[oe_atas_kanan]','$getPemeriksaanFisik[oe_bawah_kiri]','$getPemeriksaanFisik[oe_bawah_kanan]','$getPemeriksaanFisik[crt]','$getPemeriksaanFisik[motorik_atas_kiri]','$getPemeriksaanFisik[motorik_atas_kanan]','$getPemeriksaanFisik[motorik_bawah_kiri]','$getPemeriksaanFisik[motorik_bawah_kanan]','$getPemeriksaanFisik[kognitif]','$getPemeriksaanFisik[created_at]')");

    $koneksi->query("INSERT INTO igd (idigd, tgl_masuk, no_rm, nama_pasien, jam_masuk, keluhan, riw_penyakit, riw_alergi, dkerja, icd10, tindak, psiko, bb, tb, sat_oksigen, tgl, carabayar) VALUES ('$idigd', '$tgl_masuk', '$getRekamMedis[norm]', '$getRekamMedis[nama_pasien]', '$jam_masuk', '$getKajianAwal[keluhan_utama]', '$getKajianAwal[riwayat_penyakit]', '$getKajianAwal[riwayat_alergi]', '$getRekamMedis[diagnosis]', '$getRekamMedis[icd]', 'ODC', '$getKajianAwal[psiko]', '$getKajianAwal[bb]', '$getKajianAwal[tb]', '$getKajianAwal[oksigen]', '$getRawatRegistrasi[jadwal]', '$getRawatRegistrasi[carabayar]');");

    $getPasien = $koneksi->query("SELECT * FROM pasien WHERE no_rm = '$getRekamMedis[norm]'")->fetch_assoc();

    $layanan = $koneksimaster->query("SELECT * FROM master_layanan WHERE nama_layanan = 'ODC'")->fetch_assoc();
    $koneksi->query("INSERT INTO `layanan`(`layanan`, `kode_layanan`, `harga`, `jumlah_layanan`, `id_pasien`, `idrm`, `tgl_layanan`) VALUES ('$layanan[nama_layanan]', '$layanan[id]', '$layanan[harga]', '1', '$getPasien[idpasien]', '$getRekamMedis[norm]', '$getRawatRegistrasi[jadwal]')");

    $getBiayaRawat = $koneksi->query("SELECT * FROM biaya_rawat WHERE idregis = '$idrawat'")->fetch_assoc();
    $biayaLayanan = $getBiayaRawat['biaya_lain'] . '+' . $layanan['nama_layanan'];
    $totalLain =  intval($getBiayaRawat['total_lain']) + intval($layanan['harga']);
    $koneksi->query("UPDATE biaya_rawat SET biaya_lain = '$biayaLayanan', total_lain = '$totalLain' WHERE idregis = '$idrawat'");

    $koneksi->query("INSERT INTO igddetail (id, tgl, biaya, besaran, ket, petugas, shiftinap) VALUES ('$idigd', '" . date('Y-m-d') . "', 'Layanan $layanan[nama_layanan]', '$layanan[harga]', 'Layanan $layanan[nama_layanan]', '" . $_SESSION['admin']['namalengkap'] . "', '" . $_SESSION['shift'] . "')");

    $koneksi->query("INSERT INTO igd_pick_rm (rekam_medis_id, registrasi_id, igd_id) VALUES ('$getRekamMedis[id_rm]', '$idrawat', '$idigd')");
    // END INSERT TO PEMERIKSAAN FISIK IGD

    $redirect = "index.php?halaman=daftarigd";
  }

  echo "<script>alert('Successfully to add data'); document.location.href='$redirect';</script>";
  exit;
}

// Action: Edit Rekam Medis
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

  $redirect = isset($_GET['inap']) ? "index.php?halaman=rmedis&inap&id=$_GET[id]&tgl=$_GET[tgl]" : "index.php?halaman=rmedis&id=$_GET[id]&tgl=$_GET[tgl]";
  echo "<script>document.location.href='$redirect';</script>";
  exit;
}

// Action: Hapus Obat
if (isset($_GET['hapus'])) {
  $getObatById = $koneksi->query("SELECT * FROM obat_rm WHERE idobat= '$_GET[id]' LIMIT 1")->fetch_assoc();
  $ObatKode = $koneksi->query("SELECT id_obat, jml_obat, nama_obat FROM apotek WHERE nama_obat= '$getObatById[nama_obat]'")->fetch_assoc();
  $stokAkhir = $ObatKode['jml_obat'] + $getObatById['jml_dokter'];
  $koneksi->query("DELETE FROM obat_rm WHERE idobat = '$_GET[id]'");

  $redirect = isset($_GET['inap']) ? "index.php?halaman=rmedis&inap&id=$_GET[rm]&tgl=$_GET[tgl]" : "index.php?halaman=rmedis&id=$_GET[rm]&tgl=$_GET[tgl]";
  echo "<script>document.location.href='$redirect';</script>";
  exit;
}

// Action: Save Obat Racik
if (isset($_POST['saveob'])) {
  $getLastRM = $koneksi->query("SELECT  *, COUNT(*) AS jumm, MAX(id_rm) as id_rm, MAX(jadwal) as jadwall FROM rekam_medis WHERE norm = '" . htmlspecialchars($_GET['id']) . "' AND DATE_FORMAT(jadwal, '%Y-%m-%d') <= '" . date('Y-m-d', strtotime($_GET['tgl'])) . "' ORDER BY id_rm DESC LIMIT 1")->fetch_assoc();

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

  $subtotal = 0;
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
                    $m = $ObatKode['margininap'] ?? 0;
                    if ($m < 100) {
                      $margin = 1.30;
                    } else {
                      $margin = $m / 100;
                    }
                    $harga = $ObatKode['harga_beli'] * $margin * $jml_dokter[$i];
                    $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran, ket, petugas ) VALUES ('$_POST[id]', '" . date('Y-m-d') . "', 'biayaobat', '$harga', 'Resep $_POST[id]', '$username') ");
                    $subtotal += $harga;
                  } else {
                    $ObatKode = $koneksi->query("SELECT id_obat, jml_obat, nama_obat FROM apotek WHERE tipe != '' AND id_obat= '" . $nama[$i] . "'")->fetch_assoc();
                  }

                  if ($nama[$i] != '') {
                    $koneksi->query("INSERT INTO obat_rm SET catatan_obat = '$catatan_obat', kode_obat = '$nama[$i]', nama_obat = '$ObatKode[nama_obat]', jml_dokter = '" . ($jml_dokter[$i] == "" ? 0 : $jml_dokter[$i]) . "', dosis1_obat = '$value2', dosis2_obat = '$value3', per_obat = '$per_obat', durasi_obat = '$durasi_obat', petunjuk_obat = '$petunjuk_obat', jenis_obat = '$jenis_obat', idrm = '$_GET[id]', tgl_pasien = '$_GET[tgl]', rekam_medis_id = '$getLastRM[id_rm]', racik = '$_POST[racik]', resep_nota = '$noResepObat';");
                  }
                }
              }
            }
          }
        }
      }
    }
  }

  $redirect = isset($_GET['inap']) ? "index.php?halaman=rmedis&inap&id=$_GET[id]&tgl=$_GET[tgl]" : "index.php?halaman=rmedis&id=$_GET[id]&tgl=$_GET[tgl]";
  echo "<script>document.location.href='$redirect';</script>";
  exit;
}

// Action: Save Obat Jadi Baru
if (isset($_POST['saveobnew'])) {
  $getLastRM = $koneksi->query("SELECT  *, COUNT(*) AS jumm, MAX(id_rm) as id_rm, MAX(jadwal) as jadwall FROM rekam_medis WHERE norm = '" . htmlspecialchars($_GET['id']) . "' AND DATE_FORMAT(jadwal, '%Y-%m-%d') <= '" . date('Y-m-d', strtotime($_GET['tgl'])) . "' ORDER BY id_rm DESC LIMIT 1")->fetch_assoc();

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
    if ($nama[$i] != '') {
      if (isset($_GET['inap'])) {
        $ObatKode = $koneksi->query("SELECT harga_beli, margininap, nama_obat, id_obat FROM apotek WHERE id_obat= '" . $nama[$i] . "'")->fetch_assoc();
        $m = $ObatKode['margininap'] ?? 0;
        if ($m < 100) {
          $margin = 1.30;
        } else {
          $margin = $m / 100;
        }
        $harga = $ObatKode['harga_beli'] * $margin * ($jml_dokter[$i] == "" ? 0 : $jml_dokter[$i]);
        $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran, ket, petugas ) VALUES ('$_POST[id]', '" . date('Y-m-d') . "', 'biayaobat', '$harga', 'Resep $_POST[id]', '$username') ");
      } else {
        $ObatKode = $koneksi->query("SELECT id_obat, jml_obat, nama_obat FROM apotek WHERE tipe != '' AND id_obat= '" . $nama[$i] . "'")->fetch_assoc();
      }

      $koneksi->query("INSERT INTO obat_rm SET catatan_obat = '$catatan_obat[$i]', kode_obat = '$nama[$i]', nama_obat = '$ObatKode[nama_obat]', jml_dokter = '" . ($jml_dokter[$i] == "" ? 0 : $jml_dokter[$i]) . "', dosis1_obat = '$dosis1_obat[$i]', dosis2_obat = '$dosis2_obat[$i]', per_obat = '$per_obat[$i]', durasi_obat = '$durasi_obat[$i]', petunjuk_obat = '$petunjuk_obat[$i]', jenis_obat = '$jenis_obat[$i]', tgl_pasien = '$_GET[tgl]', rekam_medis_id = '$getLastRM[id_rm]', idrm = '$_GET[id]', resep_nota = '$noResepObat'");
    }
  }

  $redirect = isset($_GET['inap']) ? "index.php?halaman=rmedis&inap&id=$_GET[id]&tgl=$_GET[tgl]" : "index.php?halaman=rmedis&id=$_GET[id]&tgl=$_GET[tgl]";
  echo "<script>document.location.href='$redirect';</script>";
  exit;
}

// Action: Save Obat Paket Jadi
if (isset($_POST['saveobpaketjadi'])) {
  $getLastRM = $koneksi->query("SELECT  *, COUNT(*) AS jumm, MAX(id_rm) as id_rm, MAX(jadwal) as jadwall FROM rekam_medis WHERE norm = '" . htmlspecialchars($_GET['id']) . "' AND DATE_FORMAT(jadwal, '%Y-%m-%d') <= '" . date('Y-m-d', strtotime($_GET['tgl'])) . "' ORDER BY id_rm DESC LIMIT 1")->fetch_assoc();

  $getPaketObat = $koneksimaster->query("SELECT puyerjadi_detail.* FROM puyerjadi_detail WHERE puyer_id = '" . htmlspecialchars($_POST['paket']) . "'");

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
      $m = $ObatKode['margininap'] ?? 0;
      if ($m < 100) {
        $margin = 1.30;
      } else {
        $margin = $m / 100;
      }
      $harga = $ObatKode['harga_beli'] * $margin * $obat['jumlah'];
      $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran, ket, petugas ) VALUES ('$_POST[id]', '" . date('Y-m-d') . "', 'biayaobat', '$harga', 'Resep $_POST[id]', '$username') ");
    } else {
      $ObatKode = $koneksi->query("SELECT id_obat, jml_obat, nama_obat FROM apotek WHERE tipe != '' AND nama_obat= '" . $obat['nama_obat'] . "'")->fetch_assoc();
    }

    $koneksi->query("INSERT INTO obat_rm SET catatan_obat = '$obat[ctt_obat]', nama_obat = '$obat[nama_obat]', kode_obat = '$obat[kode_obat]', jml_dokter = '$obat[jumlah]', dosis1_obat = '$obat[dosis1]', dosis2_obat = '$obat[dosis2]', per_obat = '$obat[per]', durasi_obat = '$obat[durasi]', petunjuk_obat = '$obat[petunjuk_pemakaian]', jenis_obat = 'Jadi', tgl_pasien = '$_GET[tgl]', rekam_medis_id = '$getLastRM[id_rm]', idrm = '$_GET[id]', resep_nota = '$noResepObat'");
  }

  $redirect = isset($_GET['inap']) ? "index.php?halaman=rmedis&inap&id=$_GET[id]&tgl=$_GET[tgl]" : "index.php?halaman=rmedis&id=$_GET[id]&tgl=$_GET[tgl]";
  echo "<script>document.location.href='$redirect';</script>";
  exit;
}

// Action: Edit Obat
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

  $getObatById = $koneksi->query("SELECT * FROM obat_rm WHERE idobat= '$_POST[id_obat_sebelum]' LIMIT 1")->fetch_assoc();
  $ObatKodeUp = $koneksi->query("SELECT id_obat, jml_obat, nama_obat FROM apotek WHERE nama_obat= '$getObatById[nama_obat]'")->fetch_assoc();
  $stokAkhirUp = intval($ObatKodeUp['jml_obat']) + intval($getObatById['jml_dokter']);

  $koneksi->query("UPDATE registrasi_rawat SET end='$end' WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]';");
  $ObatKode = $koneksi->query("SELECT id_obat, jml_obat, nama_obat FROM apotek WHERE id_obat= '" . $nama . "'")->fetch_assoc();
  $stokAkhir = intval($ObatKode['jml_obat']) - intval($jml_dokter);

  $koneksi->query("UPDATE obat_rm SET catatan_obat = '$catatan_obat', nama_obat = '$ObatKode[nama_obat]', kode_obat = '$ObatKode[id_obat]', jml_dokter = '" . ($jml_dokter == "" ? 0 : $jml_dokter) . "', dosis1_obat = '$dosis1_obat', dosis2_obat = '$dosis2_obat', per_obat = '$per_obat', durasi_obat = '$durasi_obat', petunjuk_obat = '$petunjuk_obat', jenis_obat = '$_POST[jenis_obat]', idrm = '$idrm' WHERE idobat = '$_POST[id]'");

  $redirect = isset($_GET['inap']) ? "index.php?halaman=rmedis&inap&id=$_GET[id]&tgl=$_GET[tgl]" : "index.php?halaman=rmedis&id=$_GET[id]&tgl=$_GET[tgl]";
  echo "<script>document.location.href='$redirect';</script>";
  exit;
}

// Action: Copy Rekam Medis
if (isset($_POST['copy'])) {
  $getLastRM = $koneksi->query("SELECT  *, COUNT(*) AS jumm, MAX(id_rm) as id_rm, MAX(jadwal) as jadwall FROM rekam_medis WHERE norm = '" . htmlspecialchars($_GET['id']) . "' AND DATE_FORMAT(jadwal, '%Y-%m-%d') <= '" . date('Y-m-d', strtotime($_GET['tgl'])) . "' ORDER BY id_rm DESC LIMIT 1")->fetch_assoc();

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

  $getRegistrasiRawat = $koneksi->query("SELECT * FROM registrasi_rawat  WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' ORDER BY idrawat DESC LIMIT 1")->fetch_assoc();
  $checkPickIgd = $koneksi->query("SELECT *, COUNT(*) AS jum FROM igd_pick_rm WHERE registrasi_id='$getRegistrasiRawat[idrawat]'")->fetch_assoc();

  if ($checkPickIgd['jum'] > 0) {
    $newId = $checkPickIgd['rekam_medis_id'];
  } else {
    $getLastRekamMedis = $koneksi->query("SELECT * FROM rekam_medis ORDER BY id_rm DESC LIMIT 1")->fetch_assoc();
    $newId = $getLastRekamMedis['id_rm'] + 1;

    $checkDoublePick = $koneksi->query("SELECT *, COUNT(*) AS jum FROM igd_pick_rm WHERE rekam_medis_id='$newId'")->fetch_assoc();

    if ($checkDoublePick['jum'] > 0) {
      $newId = $newId + 1;
    }
  }

  $koneksi->query("INSERT INTO rekam_medis(id_rm, nama_pasien, norm, jadwal, status_perokok, anamnesa, diagnosis, prognosa, icd, status_plg, id_pasien, gol_darah, dokter, gula_darah, kolestrol, asam_urat) VALUES ('$newId', '$copy_rm[nama_pasien]', '$_GET[id]','$jadwal', '$status_perokok', '$anamnesa', '$diagnosis', '$prognosa', '$icd', '$status_pulang', '$id_pasien', '$copy_rm[gol_darah]', '$copy_rm[dokter]', '$gula_darah', '$kolestrol', '$asam_urat')");

  $koneksi->query("INSERT INTO lab_poli (nama_pasien, jadwal, gula_darah, kolestrol, asam_urat) VALUES ('$copy_rm[nama_pasien]', '$jadwal', '$gula_darah', '$kolestrol', '$asam_urat')");

  $koneksi->query("UPDATE registrasi_rawat SET status_antri='Pembayaran', dokter_at = '" . date('Y-m-d H:i:s') . "' WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]'");

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

  $end = date("H:i:s");
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

    $ObatKode = $koneksi->query("SELECT id_obat, jml_obat, nama_obat FROM apotek WHERE nama_obat= '" . $nama . "'")->fetch_assoc();

    $cekPemOb = $koneksi->query("SELECT * FROM registrasi_rawat WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' limit 1")->fetch_assoc();

    if ($cekPemOb['carabayar'] == 'umum') {
      $koneksi->query("UPDATE biaya_rawat SET poli = '35000' WHERE idregis='$cekPemOb[idrawat]'");
    } elseif ($cekPemOb['carabayar'] == 'malam') {
      $koneksi->query("UPDATE biaya_rawat SET poli = '50000' WHERE idregis='$cekPemOb[idrawat]'");
    } elseif ($cekPemOb['carabayar'] == 'spesialis penyakit dalam' or $cekPemOb['carabayar'] == 'spesialis anak') {
      $koneksi->query("UPDATE biaya_rawat SET poli = '300000' WHERE idregis='$cekPemOb[idrawat]'");
    }

    $koneksi->query("INSERT INTO obat_rm SET catatan_obat = '$catatan_obat', nama_obat = '$nama', kode_obat = '$ObatKode[id_obat]', jml_dokter = '$jml_dokter', dosis1_obat = '$dosis1_obat', dosis2_obat = '$dosis2_obat', per_obat = '$per_obat', durasi_obat = '$durasi_obat', petunjuk_obat = '$petunjuk_obat', jenis_obat = '$dataOb[jenis_obat]', rekam_medis_id = '$getLastRM[id_rm]', idrm = '$_GET[id]', resep_nota = '$noResepObat'");
  }

  echo "<script>alert('Data berhasil diubah'); document.location.href='index.php?halaman=daftarrmedis';</script>";
  exit;
}

// ==========================================
// BAGIAN QUERY DATA - SETELAH ACTION
// ==========================================

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
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Ganti 'targetID' dengan ID div tujuan Anda
    const targetElement = document.getElementById('RuangIsi');

    if (targetElement) {
      targetElement.scrollIntoView();

      // Opsional: Untuk animasi scroll halus
      targetElement.scrollIntoView({
        behavior: 'smooth'
      });
    }
  });


  // Ambil Obat Dari API yang support Pada Lokal yang bersangkutan
  document.addEventListener('DOMContentLoaded', async () => {
    try {
      <?php
      $apiUrlgetObat = '../apotek/api_getObatMasterLokal.php';
      if (isset($_GET['inap'])) {
        $apiUrlgetObat .= '?inap';
      } elseif (isset($_GET['penjualan'])) {
        $apiUrlgetObat .= '?penjualan';
      } else {
        $apiUrlgetObat .= '';
      }
      ?>
      const obatData = await (await fetch('<?= $apiUrlgetObat ?>')).json();

      document.querySelectorAll('.obat-select').forEach(select => {
        // Simpan nilai yang sedang dipilih (jika ada)
        const selectedValue = select.value;

        // Buat array dari nilai option yang sudah ada
        const existingOptions = Array.from(select.options).map(opt => opt.value);

        // Filter data obat untuk hanya menambahkan yang belum ada
        const newOptions = obatData.filter(obat =>
          !existingOptions.includes(obat.kode_obat)
        );

        // Tambahkan option baru
        newOptions.forEach(obat => {
          select.add(new Option(obat.nama_obat, obat.kode_obat));
        });

        // Kembalikan nilai yang dipilih sebelumnya (jika masih ada)
        if (selectedValue && select.querySelector(`option[value="${selectedValue}"]`)) {
          select.value = selectedValue;
        }
      });
    } catch (error) {
      console.error('Error:', error);
    }
  });
</script>
<script>
  // Fungsi untuk mengambil dan render data obat tambahan
  async function getObatTambahan() {
    const rekamMedisId = document.getElementById('inputRekamMedisIdObatTambahan')?.value;
    if (!rekamMedisId) return;
    try {
      const res = await fetch(`../api/obat_tambahan.php?rekam_medis_id=${encodeURIComponent(rekamMedisId)}`);
      const data = await res.json();
      const tbody = document.querySelector('#tabelObatTambahan tbody');
      tbody.innerHTML = '';
      if (data.status === 'success' && Array.isArray(data.data)) {
        data.data.forEach((row, idx) => {
          const tr = document.createElement('tr');
          tr.innerHTML = `
          <td>${idx + 1}</td>
          <td>${row.nama_obat || ''}</td>
          <td>${row.kode_obat || ''}</td>
          <td>${row.jumlah || ''}</td>
          <td>${row.dosis_1 || ''} X ${row.dosis_2 || ''}</td>
          <td>${row.periode || ''}</td>
          <td>
            <button class="btn btn-sm btn-danger" type="button" onclick="hapusObatTambahan(${row.id})">Hapus</button>
          </td>
        `;
          tbody.appendChild(tr);
        });
      } else {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center">Tidak ada data</td></tr>';
      }
    } catch (e) {
      // Error handling
      const tbody = document.querySelector('#tabelObatTambahan tbody');
      if (tbody) tbody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Gagal mengambil data</td></tr>';
    }
  }

  // Hapus data obat tambahan
  async function hapusObatTambahan(id) {
    if (!confirm('Yakin hapus data ini?')) return;
    try {
      const res = await fetch('../api/obat_tambahan.php', {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          id
        })
      });
      const data = await res.json();
      if (data.status === 'success') {
        getObatTambahan();
      } else {
        alert(data.message || 'Gagal hapus data');
      }
    } catch (e) {
      alert('Gagal hapus data');
    }
  }

  // Render data obat tambahan saat halaman pertama kali load
  document.addEventListener('DOMContentLoaded', getObatTambahan);

  // Panggil getObatTambahan setelah submit
  document.addEventListener('DOMContentLoaded', function() {
    const formObatTambahan = document.getElementById('formObatTambahan');
    if (formObatTambahan) {
      formObatTambahan.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(formObatTambahan);
        try {
          const response = await fetch('../api/obat_tambahan.php', {
            method: 'POST',
            body: formData
          });
          const result = await response.json();
          if (result.status === 'success' || result.success) {
            alert('Obat tambahan berhasil disimpan!');
            formObatTambahan.reset();
            getObatTambahan();
            const modal = bootstrap.Modal.getInstance(document.getElementById('obatTambahan'));
            // if (modal) modal.hide();
          } else {
            let errorMsg = result.message || 'Gagal menyimpan obat tambahan';
            if (result.error) errorMsg += "\n" + result.error;
            alert(errorMsg);
          }
        } catch (err) {
          if (err instanceof Response) {
            err.text().then(txt => alert('Error: ' + txt));
          } else {
            alert('Terjadi kesalahan saat menyimpan obat tambahan: ' + (err.message || err));
          }
        }
      });
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
            <?php if (!isset($_GET['entriObat'])) { ?>
              <div class="row g-1 mb-2" style="font-size: 12px;" id="RuangIsi">
                <div class="col-3">
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
                              <div class="row g-1">
                                <div style="">
                                  <h6 class="mb-0"><b>Riwayat Vaksinasi</b></h6>
                                </div>
                                <div class="col-md-5">
                                  <label for="inputName5" class="form-label">Vaksin</label>
                                  <input type="text" class="form-control form-control-sm" id="inputName5" name="nama_vaksin" value="<?php echo $pecah['nama_vaksin'] ?>" placeholder="Masukkan Gol Darah Pasien">
                                </div>
                                <div class="col-md-7">
                                  <label for="inputName5" class="form-label">Pemberian</label>
                                  <div class="input-group mb-3">
                                    <input type="date" class="form-control form-control-sm" id="inputName5" name="tgl_vaksin" value="<?php echo $pecah['tgl_vaksin'] ?>" placeholder="Status Perokok">
                                    <!-- <a href="#anamnesa" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-down"></i></a> -->
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
                              <h6 class="mt-2 mb-0"><b>Anamnesa</b> <sup class="badge bg-primary text-light" data-bs-toggle="modal" data-bs-target="#masterAssesment"> Copy Master</sup> </h6>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="masterAssesment" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Master</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">
                                    <select name="obj" id="masterSelect" class="form-select form-control form-control-sm" onchange="handleSelectChange(this)">
                                      <option value="" hidden>Pilih Master</option>
                                      <?php
                                      $master = $koneksimaster->query("SELECT 
                                mp.*, 
                                i.name_en as icd_name 
                                FROM master_poli mp
                                LEFT JOIN icds i ON i.code = mp.icd10
                                GROUP BY mp.diagnosis 
                                ORDER BY mp.diagnosis ASC");
                                      foreach ($master as $m) { ?>
                                        <option value="<?= $m['id'] ?>"
                                          data-diagnosis="<?= htmlspecialchars($m['diagnosis']) ?>"
                                          data-keluhan-utama="<?= htmlspecialchars($m['keluhan_utama']) ?>"
                                          data-keluhan-tambahan="<?= htmlspecialchars($m['keluhan_tambahan']) ?>"
                                          data-icd10="<?= htmlspecialchars($m['icd10']) ?>"
                                          data-icd-name="<?= htmlspecialchars($m['icd_name'] ?? '') ?>"
                                          data-objective="<?= htmlspecialchars($m['objective']) ?>">
                                          <?= htmlspecialchars($m['diagnosis']) ?>
                                        </option>
                                      <?php } ?>
                                    </select>
                                    <!-- <script>
                                      function handleSelectChange(selectElement) {
                                        const selectedOption = selectElement.options[selectElement.selectedIndex];
                                        const diagnosisId = selectedOption.getAttribute('data-diagnosis');
                                        const icd10 = selectedOption.getAttribute('data-icd10');
                                        const keluhanUtama = selectedOption.getAttribute('data-keluhan-utama');
                                        const keluhanTambahan = selectedOption.getAttribute('data-keluhan-tambahan');
                                        const objective = selectedOption.getAttribute('data-objective');

                                        $('#diagnosis_id').val(diagnosisId).trigger('change');
                                        $('#selUser').val(icd10).trigger('change');
                                        $('#keluhanUtama').val(keluhanUtama);
                                        $('#keluhanTambahan').val(keluhanTambahan);
                                        $('#objective').val(objective);

                                        document.getElementById('getMasterClose').click();
                                        copyDataMaster(diagnosisId, keluhanUtama, keluhanTambahan, objective);
                                      }
                                    </script> -->
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" id="getMasterClose" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <script>
                            </script>
                            <!-- end Modal  -->

                            <div class="col-md-6">
                              <label for="inputName5" class="form-label mt-2 mb-0">Keluhan Utama</label>
                              <!-- <input type="text" class="form-control form-control-sm" id="inputName5" name="keluhan_utama" value="<?php echo $pecah['keluhan_utama'] ?>" placeholder="Masukkan Keluhan Pasien"> -->
                              <textarea name="keluhan_utama" placeholder="Keluhan Utama" id="keluhanUtama" class="form-control form-control-sm"><?php echo $pecah['keluhan_utama'] ?></textarea>
                            </div>
                            <div class="col-md-6">
                              <label for="inputName5" class="form-label mt-2 mb-0">Keluhan Tambahan</label>
                              <!-- <input type="text" class="form-control form-control-sm" id="inputName5" name="anamnesa" value="<?php echo $rm['anamnesa'] ?>" placeholder="Anamnesa"> -->
                              <textarea name="anamnesa" id="keluhanTambahan" placeholder="Anamnesa" required class="form-control form-control-sm"><?php echo $rm['anamnesa'] ?></textarea>
                            </div>
                            <div class="col-md-12">
                              <label for="inputName5" class="form-label mt-2 mb-0">Objective</label>
                              <!-- <input type="text" class="form-control form-control-sm" id="inputName5" name="objective" value="<?php echo $pecah['objective'] ?? '' ?>" placeholder="Objectieve"> -->
                              <textarea name="objective" id="objective" class="form-control form-control-sm"><?php echo $rm['objective'] ?></textarea>
                            </div>
                          </div>
                        </div>
                        <h6 class="mt-3 mb-0">
                          <b>Assessment</b>
                          <button type="button" class="btn btn-sm btn-success" id="btnTambahDiagnosis">+ Tambah Diagnosis</button>
                        </h6>

                        <!-- Container untuk multiple diagnosis dan ICD -->
                        <div id="diagnosisContainer">
                          <!-- Diagnosis pertama akan ditambahkan oleh JavaScript -->
                        </div>

                        <!-- Hidden inputs untuk menyimpan data gabungan -->
                        <input type="hidden" name="diagnosis" id="finalDiagnosisInput">
                        <input type="hidden" name="icd" id="finalIcdInput">

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
                      </div>

                      <!-- CSS untuk styling multiple diagnosis -->
                      <style>
                        .diagnosis-item {
                          background: #f8f9fa;
                          border: 1px solid #dee2e6;
                          border-radius: 8px;
                          transition: all 0.3s ease;
                          position: relative;
                        }

                        .diagnosis-item:hover {
                          box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                          border-color: #0d6efd;
                        }

                        .diagnosis-item label {
                          color: #0d6efd;
                          font-weight: 600;
                        }

                        .btn-remove-diagnosis {
                          position: relative;
                          padding: 0.25rem 0.5rem;
                        }

                        .btn-remove-diagnosis:hover {
                          transform: scale(1.1);
                        }

                        #btnTambahDiagnosis {
                          font-size: 0.875rem;
                          padding: 0.25rem 0.75rem;
                        }

                        #diagnosisContainer {
                          max-height: 600px;
                          overflow-y: auto;
                          padding: 2px;
                        }

                        /* Custom scrollbar */
                        #diagnosisContainer::-webkit-scrollbar {
                          width: 6px;
                        }

                        #diagnosisContainer::-webkit-scrollbar-track {
                          background: #f1f1f1;
                          border-radius: 10px;
                        }

                        #diagnosisContainer::-webkit-scrollbar-thumb {
                          background: #888;
                          border-radius: 10px;
                        }

                        #diagnosisContainer::-webkit-scrollbar-thumb:hover {
                          background: #555;
                        }

                        .diagnosis-new-textarea {
                          animation: slideDown 0.3s ease;
                        }

                        @keyframes slideDown {
                          from {
                            opacity: 0;
                            transform: translateY(-10px);
                          }

                          to {
                            opacity: 1;
                            transform: translateY(0);
                          }
                        }

                        .diagnosis-item.removing {
                          animation: slideOut 0.3s ease;
                        }

                        @keyframes slideOut {
                          to {
                            opacity: 0;
                            transform: translateX(20px);
                          }
                        }
                      </style>

                      <script>
                        // Data diagnosis dari PHP untuk dropdown
                        const diagnosisOptions = <?php
                                                  $getAllDiagnosis = $koneksi->query("SELECT DISTINCT diagnosis, icd FROM rekam_medis WHERE diagnosis IS NOT NULL AND diagnosis != '' ORDER BY diagnosis ASC");
                                                  $diagList = [];

                                                  while ($allDiagnosis = $getAllDiagnosis->fetch_assoc()) {
                                                    // Trim dulu untuk handle whitespace
                                                    $diagnosisStr = trim($allDiagnosis['diagnosis']);
                                                    $icdStr = trim($allDiagnosis['icd']);

                                                    // Hapus trailing '+' jika ada
                                                    $diagnosisStr = rtrim($diagnosisStr, '+');
                                                    $icdStr = rtrim($icdStr, '+');

                                                    // Split diagnosis dan ICD berdasarkan separator '+'
                                                    $diagnosisArray = array_filter(array_map('trim', explode('+', $diagnosisStr)), function ($val) {
                                                      return !empty($val);
                                                    });

                                                    $icdArray = array_filter(array_map('trim', explode('+', $icdStr)), function ($val) {
                                                      return !empty($val);
                                                    });

                                                    // Re-index array setelah filter
                                                    $diagnosisArray = array_values($diagnosisArray);
                                                    $icdArray = array_values($icdArray);

                                                    // Loop setiap diagnosis
                                                    foreach ($diagnosisArray as $index => $diagTrimmed) {
                                                      // Cek apakah ada ICD untuk index ini
                                                      $icdTrimmed = isset($icdArray[$index]) ? $icdArray[$index] : '';

                                                      // Hanya tambahkan jika diagnosis tidak kosong DAN memiliki ICD yang tidak kosong
                                                      if (!empty($diagTrimmed) && !empty($icdTrimmed) && $icdTrimmed !== '') {
                                                        // Cek duplikasi sebelum menambahkan
                                                        if (!in_array($diagTrimmed, $diagList)) {
                                                          $diagList[] = htmlspecialchars($diagTrimmed);
                                                        }
                                                      }
                                                    }
                                                  }

                                                  // Sort array agar alfabetis
                                                  sort($diagList);

                                                  echo json_encode($diagList);
                                                  ?>;

                        // Counter untuk ID unik
                        let diagnosisCounter = 0;

                        // Load existing data dari PHP
                        const existingDiagnosis = "<?= htmlspecialchars($rm['diagnosis'] ?? '') ?>";
                        const existingIcd = "<?= htmlspecialchars($rm['icd'] ?? '') ?>";

                        // Function untuk menambah diagnosis baru
                        function addDiagnosisField(diagnosisValue = '', icdValue = '') {
                          diagnosisCounter++;
                          const container = $('#diagnosisContainer');

                          const html = `
                            <div class="diagnosis-item border p-2 mb-2" data-index="${diagnosisCounter}">
                              <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="mb-0"><b>Diagnosis ${diagnosisCounter}</b></label>
                                ${diagnosisCounter > 1 ? '<button type="button" class="btn btn-sm btn-danger btn-remove-diagnosis"><i class="bi bi-trash"></i></button>' : ''}
                              </div>
                              
                              <div class="form-group mb-2">
                                <select class="form-control form-control-sm diagnosis-select" data-index="${diagnosisCounter}">
                                  <option value="">Pilih Diagnosis</option>
                                  <option value="Diagnosis Baru">Diagnosis Baru</option>
                                  ${diagnosisOptions.map(d => `<option value="${d}" ${d === diagnosisValue ? 'selected' : ''}>${d}</option>`).join('')}
                                </select>
                              </div>
                              
                              <div class="form-group mb-2 diagnosis-new-textarea" style="display: none;">
                                <textarea class="form-control form-control-sm diagnosis-new-input" style="height: 80px;" placeholder="Masukkan diagnosis baru...">${diagnosisValue}</textarea>
                              </div>
                              
                              <div class="form-group mb-0">
                                <label class="mb-0">ICD 10</label>
                                <select class="form-select form-control-sm icd-select" data-index="${diagnosisCounter}">
                                  <option value="">Pilih ICD</option>
                                </select>
                              </div>
                            </div>
                          `;

                          container.append(html);

                          // Get reference ke item yang baru ditambahkan
                          const $item = $(`.diagnosis-item[data-index="${diagnosisCounter}"]`);

                          // Initialize Select2
                          const $diagSelect = $item.find('.diagnosis-select');
                          const $icdSelect = $item.find('.icd-select');

                          $diagSelect.select2();
                          $icdSelect.select2();

                          // Set ICD value jika ada
                          if (icdValue) {
                            $icdSelect.append(new Option(icdValue, icdValue, true, true)).trigger('change');
                          }

                          // Event handler untuk diagnosis select
                          $diagSelect.on('change', function() {
                            const val = $(this).val();
                            const index = $(this).data('index');
                            const $item = $(`.diagnosis-item[data-index="${index}"]`);
                            const $newTextarea = $item.find('.diagnosis-new-textarea');
                            const $icd = $item.find('.icd-select');

                            if (val === 'Diagnosis Baru') {
                              $newTextarea.show();
                              $icd.empty().append('<option value="">Pilih ICD</option>');

                              // Setup Select2 dengan AJAX untuk search
                              $icd.select2({
                                placeholder: 'Cari ICD...',
                                allowClear: true,
                                ajax: {
                                  url: '../rekammedis/get_icd_api.php',
                                  type: 'POST',
                                  dataType: 'json',
                                  delay: 50,
                                  data: function(params) {
                                    return {
                                      search: params.term,
                                      diagnosis: val
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
                              $newTextarea.hide();

                              if (val) {
                                // Load ICD untuk diagnosis yang dipilih
                                $.ajax({
                                  url: '../rekammedis/get_icd_api.php',
                                  type: 'POST',
                                  data: {
                                    diagnosis: val
                                  },
                                  dataType: 'json',
                                  success: function(data) {
                                    $icd.empty().append('<option value="">Pilih ICD</option>');
                                    data.forEach(function(item) {
                                      $icd.append(new Option(item.icd + ' - ' + item.name_en, item.icd, true, true));
                                    });
                                    $icd.trigger('change');
                                  }
                                });
                              } else {
                                $icd.empty().append('<option value="">Pilih ICD</option>').trigger('change');
                              }
                            }
                          });

                          // Event handler untuk remove dengan animasi
                          $item.find('.btn-remove-diagnosis').on('click', function() {
                            const $diagnItem = $(this).closest('.diagnosis-item');
                            $diagnItem.addClass('removing');

                            // Tunggu animasi selesai baru remove
                            setTimeout(function() {
                              $diagnItem.remove();
                              updateFinalInputs();
                              renumberDiagnosisItems();
                            }, 300);
                          });

                          // Event handler untuk update saat ada perubahan
                          $diagSelect.on('change', updateFinalInputs);
                          $icdSelect.on('change', updateFinalInputs);
                          $item.find('.diagnosis-new-input').on('input', updateFinalInputs);
                        }

                        // Function untuk update hidden inputs
                        function updateFinalInputs() {
                          const diagnosisValues = [];
                          const icdValues = [];

                          $('.diagnosis-item').each(function() {
                            const $item = $(this);
                            const $select = $item.find('.diagnosis-select');
                            const $newInput = $item.find('.diagnosis-new-input');
                            const $icd = $item.find('.icd-select');

                            let diagValue = '';
                            if ($select.val() === 'Diagnosis Baru') {
                              diagValue = $newInput.val().trim();
                            } else {
                              diagValue = $select.val();
                            }

                            const icdValue = $icd.val();

                            if (diagValue) {
                              diagnosisValues.push(diagValue);
                              icdValues.push(icdValue || '');
                            }
                          });

                          $('#finalDiagnosisInput').val(diagnosisValues.join('+'));
                          $('#finalIcdInput').val(icdValues.join('+'));
                        }

                        // Function untuk renumber label diagnosis setelah ada yang dihapus
                        function renumberDiagnosisItems() {
                          $('.diagnosis-item').each(function(index) {
                            $(this).find('label b').text('Diagnosis ' + (index + 1));
                          });
                        }

                        // Function untuk copy data dari master
                        function handleSelectChange(selectElement) {
                          const selectedOption = selectElement.options[selectElement.selectedIndex];
                          const diagnosis = selectedOption.getAttribute('data-diagnosis');
                          const icd10 = selectedOption.getAttribute('data-icd10');
                          const icdName = selectedOption.getAttribute('data-icd-name') || '';
                          const keluhanUtama = selectedOption.getAttribute('data-keluhan-utama');
                          const keluhanTambahan = selectedOption.getAttribute('data-keluhan-tambahan');
                          const objective = selectedOption.getAttribute('data-objective');

                          // Update keluhan dan objective
                          $('#keluhanUtama').val(keluhanUtama || '');
                          $('#objective').val(objective || '');

                          // Tambah diagnosis baru dengan data dari master
                          if (diagnosis && icd10) {
                            addDiagnosisField(diagnosis, icd10);
                            updateFinalInputs();
                          }

                          // Tutup modal
                          $('#getMasterClose').trigger('click');
                        }

                        // Initialize saat document ready
                        $(document).ready(function() {
                          // Load existing data atau buat field pertama
                          if (existingDiagnosis) {
                            const diagList = existingDiagnosis.split('+');
                            const icdList = existingIcd.split('+');

                            diagList.forEach((diag, index) => {
                              const icd = icdList[index] || '';
                              addDiagnosisField(diag.trim(), icd.trim());
                            });
                          } else {
                            addDiagnosisField();
                          }

                          // Event handler untuk tombol tambah
                          $('#btnTambahDiagnosis').on('click', function() {
                            addDiagnosisField();
                          });

                          // Update saat form submit
                          $('form').on('submit', function() {
                            updateFinalInputs();
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
                          <option value="Berobat Jalan">Berobat Jalan</option>
                          <option value="Rawat Inap">Rawat Inap</option>
                          <option value="ODC">ODC</option>
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
                <div class="col-5">
                  <div class="card h-100 p-2">
                    <!-- <h6 class="mt-2 mb-0"><b>Plan</b> <span class="badge bg-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">+</span></h6>
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
                    </div> -->

                    <?php
                    $getLastRM = $koneksi->query("SELECT  *, COUNT(*) AS jumm, MAX(id_rm) as id_rm, MAX(jadwal) as jadwall FROM rekam_medis WHERE norm = '" . htmlspecialchars($_GET['id']) . "' AND DATE_FORMAT(jadwal, '%Y-%m-%d') <= '" . date('Y-m-d', strtotime($_GET['tgl'])) . "' ORDER BY id_rm DESC LIMIT 1")->fetch_assoc();
                    $getLastRM['jumm'] > 0 ? $whereConditionObatRm = "AND rekam_medis_id = '$getLastRM[id_rm]'" : $whereConditionObatRm = "AND rekam_medis_id IS NULL";
                    $obat = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' " . $whereConditionObatRm . " ");

                    $hasIgdPickRmForModal = false;
                    $igdPlanRawatForModal = '';
                    $igdIdForModal = '';
                    if (!empty($getLastRM['id_rm'])) {
                      $checkIgdPickForModal = $koneksi->query("SELECT * FROM igd_pick_rm WHERE rekam_medis_id = '$getLastRM[id_rm]' ORDER BY id DESC LIMIT 1")->fetch_assoc();
                      if (!empty($checkIgdPickForModal['igd_id'])) {
                        $getIgdForModal = $koneksi->query("SELECT idigd, rencana_rawat FROM igd WHERE idigd = '$checkIgdPickForModal[igd_id]' LIMIT 1")->fetch_assoc();
                        if (!empty($getIgdForModal['idigd'])) {
                          $hasIgdPickRmForModal = true;
                          $igdPlanRawatForModal = $getIgdForModal['rencana_rawat'] ?? '';
                          $igdIdForModal = $getIgdForModal['idigd'];
                        }
                      }
                    }
                    ?>
                    <div>
                      <h6 class="mt-2 mb-0"><b>Tambah Obat Untuk Jadwal <?= $getLastRM['jadwall'] ?></b></h6>
                      <?php $obatData = $obat->fetch_assoc(); ?>
                      <?php if ($obatData['status_obat'] != "selesai") { ?>
                        <div align="right">
                          <button type="button" class="btn btn-sm mb-2 btn-primary text-right" data-bs-toggle="modal" data-bs-target="#exampleModal45" disable>Jadi <?= $getLastRM['id_rm'] ?></button>
                          <button type="button" class="btn btn-sm mb-2 btn-success text-right" data-bs-toggle="modal" data-bs-target="#exampleModal2">Racik</button>
                          <a type="button" class="btn btn-sm mb-2 btn-info text-right" href="index.php?halaman=tambahpuyer2&id=<?= $_GET['id'] ?>&tgl=<?= $_GET['tgl'] ?>">Paket Racik</a>
                          <span type="button" class="btn btn-sm mb-2 btn-warning text-right" data-bs-toggle="modal" data-bs-target="#addPaketJadi">Paket Jadi</span>
                        </div>

                        <?php if ($hasIgdPickRmForModal) { ?>
                          <div class="position-fixed top-0 end-0 p-3" style="z-index: 1090; width: min(480px, 92vw); transform: translateX(20%);">
                            <div id="toastIgdPickRmReminder" class="toast border-0 shadow" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                              <div class="toast-header bg-warning-subtle">
                                <strong class="me-auto">Rencana Rawat IGD</strong>
                                <small>Acuan Obat</small>
                              </div>
                              <div class="toast-body">
                                <textarea id="planToastIgd" readonly class="form-control form-control-sm" style="min-height: 140px; max-height: 42vh;"><?= htmlspecialchars($igdPlanRawatForModal) ?></textarea>
                                <?php if (!empty($igdIdForModal)) { ?>
                                  <p class="my-0 mt-2 text-end">
                                    <a href="index.php?halaman=rmedis&id=<?= htmlspecialchars($_GET['id']) ?>&tgl=<?= htmlspecialchars($_GET['tgl']) ?>&tandaiSelesaiIsiIGD=<?= $igdIdForModal ?>" target="_blank" class="btn btn-sm btn-primary">Selesai</a>
                                  </p>
                                <?php } ?>
                              </div>
                            </div>
                          </div>

                          <script>
                            document.addEventListener('DOMContentLoaded', function() {
                              let planToastEditor;

                              const toastEl = document.getElementById('toastIgdPickRmReminder');
                              if (!toastEl || typeof bootstrap === 'undefined') return;

                              const toast = bootstrap.Toast.getOrCreateInstance(toastEl, {
                                autohide: false
                              });

                              function initPlanToastEditor() {
                                if (planToastEditor || typeof ClassicEditor === 'undefined') return;
                                const planToastTextarea = document.querySelector('#planToastIgd');
                                if (!planToastTextarea) return;

                                ClassicEditor
                                  .create(planToastTextarea, {
                                    toolbar: [
                                      'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'undo', 'redo'
                                    ]
                                  })
                                  .then(editor => {
                                    planToastEditor = editor;
                                    editor.enableReadOnlyMode('planToastReadOnly');
                                  })
                                  .catch(error => {
                                    console.error(error);
                                  });
                              }

                              toastEl.addEventListener('shown.bs.toast', function() {
                                initPlanToastEditor();
                              });

                              ['exampleModal2', 'exampleModal45'].forEach(function(modalId) {
                                const modalEl = document.getElementById(modalId);
                                if (!modalEl) return;

                                modalEl.addEventListener('show.bs.modal', function() {
                                  toast.show();
                                  initPlanToastEditor();
                                });

                                modalEl.addEventListener('hidden.bs.modal', function() {
                                  toast.hide();
                                });
                              });
                            });
                          </script>
                          <script src="https://cdn.ckeditor.com/ckeditor5/37.1.0/classic/ckeditor.js"></script>
                        <?php } ?>
                      <?php } ?>
                      <div class="table-responsive">
                        <?php $subtotal = 0; ?>
                        <div id="employee_table">
                          <table class="table table-sm table-striped table-hover" style="font-size: 12px;">
                            <thead>
                              <tr>
                                <th>No.</th>
                                <th>Obat</th>
                                <!-- <th>Kode Obat</th> -->
                                <th>Jumlah Obat</th>
                                <th>Dosis</th>
                                <!-- <th>Jenis</th> -->
                                <!-- <th>Durasi</th> -->
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
                                  <td style="margin-top:10px;">
                                    <?php echo $obat["nama_obat"]; ?>
                                    <?php if ($obat["jenis_obat"] != "Jadi") { ?>
                                      <a href="index.php?halaman=rmedis_editObatJenis&id=<?= htmlspecialchars($_GET['id']) ?>&tgl=<?= htmlspecialchars($_GET['tgl']) ?>&editObat&jenis=<?php echo $obat["jenis_obat"]; ?>&racik=<?= $obat['racik'] == "" ? "" : $obat['racik']; ?>" class="">
                                        (<?php echo $obat["jenis_obat"]; ?><?= $obat['racik'] == "" ? "" : $obat['racik']; ?>)<br>
                                      </a>
                                    <?php } else { ?>
                                      (<?php echo $obat["jenis_obat"]; ?><?= $obat['racik'] == "" ? "" : $obat['racik']; ?>)<br>
                                    <?php } ?>
                                    <span style="font-size: 10px;"><?php echo $obat["kode_obat"]; ?> | <?php echo $obat["durasi_obat"]; ?>Hari</span>
                                  </td>
                                  <!-- <td style="margin-top:10px;"><?php echo $obat["kode_obat"]; ?></td> -->
                                  <td style="margin-top:10px;"><?php echo $obat["jml_dokter"]; ?></td>
                                  <td style="margin-top:10px;"><?php echo $obat["dosis1_obat"]; ?> X <?php echo $obat["dosis2_obat"]; ?> <?php echo $obat["per_obat"]; ?></td>
                                  <!-- <td style="margin-top:10px;"><?php echo $obat["jenis_obat"]; ?> <?= $obat['racik'] ?></td> -->
                                  <!-- <td style="margin-top:10px;"><?php echo $obat["durasi_obat"]; ?> hari</td> -->
                                  <?php if ($obat["status_obat"] != "selesai") { ?>
                                    <td style="margin-top:10px;">
                                      <button type="button"
                                        class="btn btn-sm btn-primary text-right"
                                        onclick="openUpdateObat(
                                                '<?php echo $obat["idobat"]; ?>',
                                                '<?php echo $obat["kode_obat"]; ?>',
                                                '<?php echo addslashes($obat["nama_obat"]); ?>',
                                                '<?php echo addslashes($obat["catatan_obat"]); ?>',
                                                '<?php echo $obat["jenis_obat"]; ?>',
                                                '<?php echo $obat["dosis1_obat"]; ?>',
                                                '<?php echo $obat["dosis2_obat"]; ?>',
                                                '<?php echo $obat["per_obat"]; ?>',
                                                '<?php echo $obat["jml_dokter"]; ?>',
                                                '<?php echo $obat["durasi_obat"]; ?>',
                                                '<?php echo addslashes($obat["petunjuk_obat"]); ?>'
                                              )">
                                        <i class="bi bi-pencil"></i>
                                      </button>
                                      <?php if (isset($_GET['inap'])) { ?>
                                        <a href="index.php?halaman=rmedis&id=<?php echo $obat["idobat"]; ?>&rm=<?php echo $_GET["id"]; ?>&tgl=<?php echo $_GET["tgl"]; ?>&hapus&inap=<?= $_GET['inap'] ?>" class="btn btn-sm btn-danger text-right"><i class="bi bi-trash"></i></a>
                                      <?php } else { ?>
                                        <a href="index.php?halaman=rmedis&id=<?php echo $obat["idobat"]; ?>&rm=<?php echo $_GET["id"]; ?>&tgl=<?php echo $_GET["tgl"]; ?>&hapus" class="btn btn-sm btn-danger text-right"><i class="bi bi-trash"></i></a>
                                      <?php } ?>
                                    </td>
                                  <?php } ?>
                                  <?php $subtotal += $subharga; ?>
                                </tr>
                                <?php $no += 1 ?>
                              <?php endforeach ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <div>
                        <div>
                          <form method="post">
                            <!-- <button type="submit" name="selesai" class="btn btn-sm btn-primary" onclick="return confirm('Apakah anda untuk menyelesaikan rekam medis ini ???')">Selesai</button> -->
                          </form>
                        </div>
                        <?php
                        if (isset($_POST['selesai'])) {
                          $koneksi->query("UPDATE obat_rm SET status_obat='selesai' WHERE rekam_medis_id='$getLastRM[id_rm]'");
                          echo "
                            <script>
                              alert('Status berhasil diubah menjadi selesai');
                              document.location.href='index.php?halaman=daftarrmedis';
                            </script>
                          ";
                        }
                        ?>
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
                      <br>
                      <!-- <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#obatTambahan">+ Obat Tambahan</button>
                      </div> -->
                      <!-- Modal Obat Tambahan -->
                      <!-- <script>
                        $(document).ready(function() {
                          $('#ObatTambahanSelect').select2({
                            dropdownParent: $('#obatTambahan')
                          });
                        });
                      </script>
                      <div class="modal fade" id="obatTambahan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h1 class="modal-title fs-5" id="staticBackdropLabel">Obat Tambahan</h1>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <div class="row g-1">
                                <div class="col-md-4">
                                  <form id="formObatTambahan" autocomplete="off">
                                    <div class="row g-1">
                                      <div class="col-12">
                                        <select name="kode_obat" class="obat-select form-select form-control form-control-sm mb-2 w-100" style="width:100%;" id="ObatTambahanSelect" autocomplete="off" aria-label="Default select example">
                                          <option value="">Pilih</option>
                                        </select>
                                      </div>
                                      <div class="col-md-4">
                                        Jumlah
                                        <input type="text" name="jumlah" class="form-control form-control-sm" id="inputJumlahObatTambahan" placeholder="Jumlah Obat">
                                      </div>
                                      <div class="col-md-8">
                                        Dosis
                                        <div class="row g-1">
                                          <div class="col-8">
                                            <div class="input-group">
                                              <input type="text" class="form-control form-control-sm" name="dosis_1" id="inputDosis1ObatTambahan">
                                              <input type="text" style="text-align: center;" class="form-control form-control-sm" placeholder="X" disabled>
                                              <input type="text" class="form-control form-control-sm" name="dosis_2" id="inputDosis2ObatTambahan">
                                            </div>
                                          </div>
                                          <div class="col-4">
                                            <select id="inputPeriodeObatTambahan" name="periode" class="form-select form-select-sm form-control form-control-sm">
                                              <option>Per Hari</option>
                                              <option>Per Jam</option>
                                            </select>
                                          </div>
                                        </div>
                                      </div>
                                      <input type="hidden" name="rekam_medis_id" id="inputRekamMedisIdObatTambahan" value="<?= $getLastRM['id_rm'] ?>">
                                      <button class="btn btn-sm btn-primary" type="submit" id="btnSimpanObatTambahan">Simpan</button>
                                      <input type="hidden" id="editIdObatTambahan" name="id">
                                    </div>
                                  </form>
                                </div>
                                <div class="col-md-8">
                                  <div class="table-responsive">
                                    <table class="table table-sm table-bordered" id="tabelObatTambahan" style="font-size:12px;">
                                      <thead>
                                        <tr>
                                          <th>No</th>
                                          <th>Nama</th>
                                          <th>Kode Obat</th>
                                          <th>Jumlah</th>
                                          <th>Dosis</th>
                                          <th>Periode</th>
                                          <th>Aksi</th>
                                        </tr>
                                      </thead>
                                      <tbody></tbody>
                                    </table>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                          </div>
                        </div>
                      </div> -->
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
                      <span class="badge bg-success mb-1 " onclick="document.getElementById('racikFormId').value='Capsule'">Capsule</span>
                      <span class="badge bg-warning mb-1 " onclick="document.getElementById('racikFormId').value='Puyer'">Puyer</span>
                      <input type="text" name="racik" id="racikFormId" class="form-control form-control-sm w-100" style="width:100%;" aria-label="Default select example">
                    </div>
                    <?php foreach (range(1, 8) as $i) { ?>
                      <div class="col-md-12">
                        <label for="inputName5" class="form-label">Nama Obat</label><br>
                        <select name="nama_obat[]" class="obat-select form-control form-control-sm w-100" style="width:100%;" id="ObatRacikSelect2<?= $i ?>" aria-label="Default select example">
                          <option value="">Pilih</option>
                        </select>
                      </div>
                      <script>
                        document.addEventListener('DOMContentLoaded', function() {
                          // Inisialisasi Tom Select
                          const selectObat = new TomSelect("#ObatRacikSelect2<?= $i ?>", {
                            valueField: 'kode_obat',
                            labelField: 'nama_obat',
                            searchField: 'nama_obat',
                            options: [], // Awalnya kosong, akan diisi dari API
                            maxItems: 1, // Tidak ada batas item
                            create: false, // Tidak boleh buat opsi baru
                            placeholder: 'Cari obat...',
                            render: {
                              option: function(data, escape) {
                                return `<div>${escape(data.nama_obat)}</div>`;
                              },
                              item: function(data, escape) {
                                return `<div>${escape(data.nama_obat)}</div>`;
                              }
                            }
                          });

                          // Ambil data obat dari API
                          async function loadObatData() {
                            try {
                              <?php
                              $apiUrlgetObat = '../apotek/api_getObatMasterLokal.php';
                              if (isset($_GET['inap'])) {
                                $apiUrlgetObat .= '?inap';
                              } elseif (isset($_GET['penjualan'])) {
                                $apiUrlgetObat .= '?penjualan';
                              }
                              ?>
                              const response = await fetch('<?= $apiUrlgetObat ?>');
                              const obatData = await response.json();

                              // Tambahkan opsi ke Tom Select
                              selectObat.addOptions(obatData);

                              // Jika ingin memilih beberapa obat secara default:
                              // selectObat.setValue(['kode1', 'kode2']);

                            } catch (error) {
                              console.error('Error loading obat data:', error);
                            }
                          }

                          // Panggil fungsi untuk memuat data
                          loadObatData();
                        });
                      </script>
                      <div class="col-md-12 mb-2" style="margin-top:5px">
                        <label for="">Jumlah Obat</label>
                        <input type="text" name="jml_dokter[]" class="form-control form-control-sm" id="inputName5" placeholder="jumlah obat">
                      </div>
                      <hr>
                    <?php } ?>
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
                    <select name="nama_obat[]" class="obat-select form-control form-control-sm " id="selObat1" aria-label="Default select example">
                      <option value="">Pilih</option>
                    </select>
                    <div class="col-md-12" style="margin-top:20px">
                      <label for="">Jumlah Obat</label>
                      <input type="text" name="jml_dokter[]" class="form-control form-control-sm" id="inputName5" placeholder="jumlah obat">
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

    <!-- Modal Edit Obat (Single Instance) -->
    <div class="modal fade" id="modalEditObat" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit Obat</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <form method="post" enctype="multipart/form-data" id="formEditObat">
                <div class="control-group after-add-more">
                  <div class="row">
                    <div class="col-md-12">
                      <label for="inputName5" class="form-label">Nama Obat</label>
                      <select name="nama_obat" onchange="updateCatatanEdit()" id="namaObat_edit_id" class="obat-select form-select w-100" style="width: 100%;">
                        <option value="">Pilih Obat</option>
                        <?php
                        $getObat = $koneksimaster->query("SELECT * FROM master_obat WHERE kode_obat != 'V.1394489' AND aktif_poli = 'aktif' ORDER BY obat_master ASC");
                        foreach ($getObat as $data) {
                        ?>
                          <option value="<?= $data['kode_obat'] ?>"><?= $data['obat_master'] ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12" style="margin-top:20px; margin-bottom:20px;">
                    <label for="inputName5" class="form-label">Catatan Obat</label>
                    <input type="text" name="catatan_obat" class="form-control form-control-sm" id="catatan_edit_id" placeholder="Masukkan Catatan Waktu">
                  </div>
                  <div class="col-md-12" style="margin-top:0px; margin-bottom:20px;">
                    <label for="inputName5" class="form-label">Jenis Obat</label>
                    <select name="jenis_obat" class="form-select" id="jenisObat_edit_id">
                      <option value="Racik">Racik</option>
                      <option value="Jadi">Jadi</option>
                    </select>
                  </div>
                  <label for="inputName5" class="form-label">Dosis</label>
                  <div class="col-md-6">
                    <div class="input-group">
                      <input oninput="updateJumlahEdit()" type="text" class="form-control form-control-sm" id="dosis1_obat_edit_id" name="dosis1_obat">
                      <input type="text" style="text-align: center;" class="form-control form-control-sm" placeholder="X">
                      <input oninput="updateJumlahEdit()" type="text" class="form-control form-control-sm" id="dosis2_obat_edit_id" name="dosis2_obat">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <select id="perObat_edit_id" name="per_obat" class="form-select">
                      <option>Per Hari</option>
                      <option>Per Jam</option>
                    </select>
                  </div>
                  <div class="col-md-12" style="margin-top:20px">
                    <label for="">Jumlah Obat</label>
                    <input type="number" name="id_obat_sebelum" class="form-control form-control-sm" id="id_obat_sebelum_edit_id" hidden placeholder="jumlah obat">
                    <input type="number" name="jml_obat_sebelum" class="form-control form-control-sm" id="jml_obat_sebelum_edit_id" hidden placeholder="jumlah obat">
                    <input type="number" name="jml_dokter" class="form-control form-control-sm" id="jml_obat_edit_id" placeholder="jumlah obat">
                  </div>
                  <div class="col-md-12" style="margin-top:20px">
                    <label for="inputCity" class="form-label">Durasi</label>
                    <div class="input-group mb-3">
                      <input type="text" name="durasi_obat" id="durasiObat_edit_id" class="form-control form-control-sm" placeholder="Durasi" aria-describedby="basic-addon2">
                      <span class="input-group-text" id="basic-addon2">Hari</span>
                    </div>
                  </div>
                  <div class="col-md-12" style="margin-top:10px">
                    <label for="inputName5" class="form-label">Petunjuk Pemakaian</label>
                    <input type="text" name="petunjuk_obat" id="petunjuk_edit_id" class="form-control form-control-sm" placeholder="Masukkan Petunjuk Pemakaian">
                  </div>
                </div>
                <input type="number" name="id" id="idObat_edit_id" hidden>
                <input type="hidden" name="idrm" value="<?php echo $_GET['id'] ?>">
                <div class="modal-footer">
                  <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                  <input type="submit" class="btn btn-sm btn-primary" name="edt" value="Save changes">
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
                  <?php
                  $getLayananMaster = $koneksimaster->query("SELECT * FROM  master_layanan ORDER BY nama_layanan DESC");
                  ?>
                  <?php foreach ($getLayananMaster as $masterLayanan) { ?>
                    <option value="<?= $masterLayanan['id'] ?>"><?= $masterLayanan['nama_layanan'] ?></option>
                  <?php } ?>
                  <!-- <option value="glukosa"><span style="text-transform: 'capitalize';">glukosa</span></option>
                  <option value="asam urat"><span style="text-transform: 'capitalize';">asam urat</span></option>
                  <option value="kolestrol"><span style="text-transform: 'capitalize';">kolestrol</span></option>
                  <option value="irigasi mata"><span style="text-transform: 'capitalize';">irigasi mata</span></option>
                  <option value="irigasi telinga"><span style="text-transform: 'capitalize';">irigasi telinga</span></option>
                  <option value="suntik kb"><span style="text-transform: 'capitalize';">suntik kb</span></option>
                  <option value="lain-lain"><span style="text-transform: 'capitalize';">lain-lain</span></option> -->
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
      <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Obat <a href="index.php?halaman=rmedis&id=<?= htmlspecialchars($_GET['id']) ?>&tgl=<?= htmlspecialchars($_GET['tgl']) ?>&entriObat=Jadi" target="_blank" class="btn btn-sm btn-primary">New Tab</a> </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <span class="" style="font-size: 9px;">Akan langsung memunculkan 5 Form Obat Untuk Memudahkan Searching Obat</span>
            <div class="row">
              <form method="post" enctype="multipart/form-data">
                <div class="control-group">
                  <?php foreach (range(1, 6) as $i) { ?>
                    <div class="row">
                      <div class="col-md-12">
                        <label for="inputName5" class="form-label">Nama Obat</label><br>
                        <select name="nama_obat[]" class="obat-select form-select form-control form-control-sm mb-2 w-100" style="width:100%;" id="ObatJadiSelect2<?= $i ?>" autocomplete="off" aria-label="Default select example">
                          <option value="">Pilih</option>
                        </select>
                        <script>
                          document.addEventListener('DOMContentLoaded', function() {
                            // Inisialisasi Tom Select
                            const selectObat = new TomSelect("#ObatJadiSelect2<?= $i ?>", {
                              valueField: 'kode_obat',
                              labelField: 'nama_obat',
                              searchField: 'nama_obat',
                              options: [], // Awalnya kosong, akan diisi dari API
                              maxItems: 1, // Tidak ada batas item
                              create: false, // Tidak boleh buat opsi baru
                              placeholder: 'Cari obat...',
                              render: {
                                option: function(data, escape) {
                                  return `<div>${escape(data.nama_obat)}</div>`;
                                },
                                item: function(data, escape) {
                                  return `<div>${escape(data.nama_obat)}</div>`;
                                }
                              }
                            });

                            // Ambil data obat dari API
                            async function loadObatData() {
                              try {
                                <?php
                                $apiUrlgetObat = '../apotek/api_getObatMasterLokal.php';
                                if (isset($_GET['inap'])) {
                                  $apiUrlgetObat .= '?inap';
                                } elseif (isset($_GET['penjualan'])) {
                                  $apiUrlgetObat .= '?penjualan';
                                }
                                ?>
                                const response = await fetch('<?= $apiUrlgetObat ?>');
                                const obatData = await response.json();

                                // Tambahkan opsi ke Tom Select
                                selectObat.addOptions(obatData);

                                // Jika ingin memilih beberapa obat secara default:
                                // selectObat.setValue(['kode1', 'kode2']);

                              } catch (error) {
                                console.error('Error loading obat data:', error);
                              }
                            }

                            // Panggil fungsi untuk memuat data
                            loadObatData();
                          });
                        </script>

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
                      <div class="col-md-6">
                        <label for="">Jumlah Obat</label>
                        <input type="text" name="jml_dokter[]" class="form-control form-control-sm mb-2" id="inputName5" placeholder="Jumlah Obat">
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
                  <?php } ?>
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
                    <select name="nama_obat[]" class="obat-select form-select mb-2 form-control form-control-sm" id="selObat1" aria-label="Default select example">
                      <option value="">Pilih</option>
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
                        <input type="text" name="jml_dokter[]" class="form-control form-control-sm mb-2" id="inputName5" placeholder="jumlah obat">
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
                $getPakek = $koneksimaster->query("SELECT * FROM puyerjadi GROUP BY nama_paket ORDER BY nama_paket ASC");
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
              <td>
                <?php
                $getKajianAwalSingle = $koneksi->query("SELECT * FROM kajian_awal WHERE tgl_rm = '" . date('Y-m-d', strtotime($item['jadwal'])) . "' AND norm = '$item[norm]'")->fetch_assoc();
                ?>
                <?= $item['keluhan_utama'] == '' ? ($getKajianAwalSingle['keluhan_utama'] ?? '') : $item['keluhan_utama'] ?>
              </td>
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
                  <a href="index.php?halaman=rmedis&id=<?= $_GET['id'] ?>&tgl=<?= $_GET['tgl'] ?>&rekamMedisId=<?= $item['id_rm'] ?>&masukODC" class="btn btn-sm btn-info">Masukan ODC</a>
                </form>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</main><!-- End #main -->
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

<script>
  // Inisialisasi Select2 untuk modal edit obat
  $(document).ready(function() {
    $('#namaObat_edit_id').select2({
      dropdownParent: $('#modalEditObat')
    });
  });

  // Fungsi untuk membuka modal dan mengisi data
  function openUpdateObat(idobat, kodeObat, namaObat, catatanObat, jenisObat, dosis1, dosis2, perObat, jumlah, durasi, petunjuk) {
    // Set nilai ke masing-masing field
    $('#idObat_edit_id').val(idobat);
    $('#id_obat_sebelum_edit_id').val(idobat);
    $('#jml_obat_sebelum_edit_id').val(jumlah);

    // Set Select2 untuk nama obat
    // Cek apakah option sudah ada
    if ($('#namaObat_edit_id option[value="' + kodeObat + '"]').length === 0) {
      // Tambah option baru jika belum ada
      var newOption = new Option(namaObat, kodeObat, true, true);
      $('#namaObat_edit_id').append(newOption).trigger('change');
    } else {
      // Pilih option yang sudah ada
      $('#namaObat_edit_id').val(kodeObat).trigger('change');
    }

    $('#catatan_edit_id').val(catatanObat);
    $('#jenisObat_edit_id').val(jenisObat);
    $('#dosis1_obat_edit_id').val(dosis1);
    $('#dosis2_obat_edit_id').val(dosis2);
    $('#perObat_edit_id').val(perObat);
    $('#jml_obat_edit_id').val(jumlah);
    $('#durasiObat_edit_id').val(durasi);
    $('#petunjuk_edit_id').val(petunjuk);

    // Buka modal
    var modalEditObat = new bootstrap.Modal(document.getElementById('modalEditObat'));
    modalEditObat.show();
  }

  // Fungsi untuk update catatan berdasarkan obat yang dipilih
  function updateCatatanEdit() {
    var selectElement = document.getElementById('namaObat_edit_id');
    var selectedText = selectElement.options[selectElement.selectedIndex].text;
    var catatanInput = document.getElementById('catatan_edit_id');
    var petunjukInput = document.getElementById('petunjuk_edit_id');

    if (selectedText === 'glibenclamid' || selectedText === 'metformin') {
      catatanInput.value = 'Pagi, Siang, Malam';
    } else if (selectedText === 'furosemid') {
      catatanInput.value = 'Pagi, Siang';
    } else if (selectedText === 'Allupurinol 100' || selectedText === 'Amlodipin 5 mg' || selectedText === 'Amlodipin 10 mg') {
      catatanInput.value = 'Pagi, Malam';
    }

    if (selectedText === 'Antasida tab' || selectedText === 'Omeprazol tab') {
      petunjukInput.value = 'Sebelum Makan';
    }
  }

  // Fungsi untuk auto-update jumlah berdasarkan dosis
  function updateJumlahEdit() {
    var dosis1 = document.getElementById('dosis1_obat_edit_id');
    var dosis2 = document.getElementById('dosis2_obat_edit_id');
    var jml = document.getElementById('jml_obat_edit_id');

    if (dosis1.value == '3' && dosis2.value == '1') {
      jml.value = 9;
    } else if (dosis1.value == '2' && dosis2.value == '1') {
      jml.value = 6;
    } else if (dosis1.value == '1' && dosis2.value == '1') {
      jml.value = 3;
    }
  }
</script>