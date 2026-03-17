<?php
$date = date("Y-m-d");
date_default_timezone_set('Asia/Jakarta');
$username = $_SESSION['admin']['username'];
$petugas = $_SESSION['admin']['namalengkap'];
$ambil = $koneksi->query("SELECT * FROM admin  WHERE username='$username';");
$pasien = $koneksi->query("SELECT * FROM pasien  WHERE no_rm='$_GET[id]';")->fetch_assoc();

$id = $koneksi->query("SELECT * FROM registrasi_rawat  WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' AND perawatan = 'Rawat Inap' ORDER BY idrawat DESC LIMIT 1")->fetch_assoc();

$jadwal = $koneksi->query("SELECT * FROM registrasi_rawat  WHERE no_rm='$_GET[id]' AND date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' ")->fetch_assoc();

function getFullUrl()
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ||
        $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

    return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

function getUniqeIdObat($koneksi)
{
    $newId = $koneksi->query("SELECT * FROM obat_rm ORDER BY idobat DESC LIMIT 1")->fetch_assoc()['idobat'] + 1;
    while ($koneksi->query("SELECT COUNT(*) FROM obat_rm WHERE idobat = $newId")->fetch_row()[0] > 0) {
        $newId++;
    }
    return $newId;
}

function getUniqeIdObatRequest($koneksi)
{
    $newId = $koneksi->query("SELECT * FROM obat_rm_request ORDER BY idobat DESC LIMIT 1")->fetch_assoc()['idobat'] + 1;
    while ($koneksi->query("SELECT COUNT(*) FROM obat_rm_request WHERE idobat = $newId")->fetch_row()[0] > 0) {
        $newId++;
    }
    return $newId;
}

function getUniqeIdObatRetur($koneksi)
{
    $newId = $koneksi->query("SELECT * FROM retur_obat_inap ORDER BY idretur DESC LIMIT 1")->fetch_assoc()['idretur'] + 1;
    while ($koneksi->query("SELECT COUNT(*) FROM retur_obat_inap WHERE idretur = $newId")->fetch_row()[0] > 0) {
        $newId++;
    }
    return $newId;
}

function getLastWord($inputString)
{
    // Trim the input string to remove any leading or trailing whitespace
    $trimmedString = trim($inputString);

    // Check if the trimmed string is empty
    if (empty($trimmedString)) {
        return "The input string is empty.";
    }

    // Split the string into an array of words using space as the delimiter
    $wordsArray = explode(' ', $trimmedString);

    // Count the number of words in the array
    $wordCount = count($wordsArray);

    // Check if the string contains exactly three words
    if ($wordCount !== 3) {
        return "The input string does not contain exactly three words.";
    }

    // Get the last word from the array
    $lastWord = $wordsArray[$wordCount - 1];

    // Return the last word
    return $lastWord;
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

    $koneksi->query("UPDATE registrasi_rawat SET end='$end' WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' AND perawatan = 'Rawat Inap' ORDER BY idrawat DESC LIMIT 1;");
    $cekPemOb = $koneksi->query("SELECT * FROM registrasi_rawat WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' AND perawatan = 'Rawat Inap' ORDER BY idrawat DESC LIMIT 1")->fetch_assoc();
    $koneksi->query("UPDATE biaya_rawat SET poli = '35000' WHERE idregis='$cekPemOb[idrawat]'");

    for ($i = 0; $i < count($nama) - 1; $i++) {
        foreach ($_POST['catatan_obat'] as $catatan_obat) {
            foreach ($_POST['per_obat'] as $per_obat) {
                foreach ($_POST['durasi_obat'] as $durasi_obat) {
                    foreach ($_POST['petunjuk_obat'] as $petunjuk_obat) {
                        foreach ($_POST['jenis_obat'] as $jenis_obat) {
                            foreach ($_POST['racik'] as $racik) {

                                if ($_SESSION['admin']['level'] != 'dokter') {
                                    $uniqueId = getUniqeIdObat($koneksi);
                                } else {
                                    $uniqueId = getUniqeIdObatRequest($koneksi);
                                }

                                $ObatKode = $koneksi->query("SELECT id_obat, jml_obat, margininap, harga_beli, nama_obat FROM apotek WHERE tipe != '' AND id_obat = '" . $nama[$i] . "' ORDER BY idapotek DESC LIMIT 1")->fetch_assoc();

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
                                $biaya = isset($_GET['inap']) ? 'biayaobat inap' : 'biayaobat igd';
                                $id = $_POST["idrm"];
                                $resep = 'Resep' . ' ' . $id . ' ' . $uniqueId;


                                // $subtotal += $harga;

                                // $koneksi->query("UPDATE apotek SET jml_obat = '$stokAkhir' WHERE id_obat = '$ObatKode[id_obat]'");
                                if ($_SESSION['admin']['level'] != 'dokter') {
                                    $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran, ket, petugas, shiftinap) VALUES ('$_POST[idrm]', '$tanggal', '$biaya', '$harga', '$resep', '$petugas', '" . $_SESSION['shift'] . "') ");

                                    $koneksi->query("INSERT INTO obat_rm SET idobat = '$uniqueId', catatan_obat = '$catatan_obat', nama_obat = '$ObatKode[nama_obat]', kode_obat = '$nama[$i]', jml_dokter = '$jml_dokter[$i]', dosis1_obat = '$dosis1_obat', dosis2_obat = '$dosis2_obat', per_obat = '$per_obat', durasi_obat = '$durasi_obat', petunjuk_obat = '$petunjuk_obat', jenis_obat = '$jenis_obat', idigd = '$_GET[idigd]', tgl_pasien = '$_GET[tgl]', obat_igd = '$_POST[jenis]', racik = '$racik', idrm = '$_GET[id]', petugas = '" . $_SESSION['admin']['namalengkap'] . "'");
                                } else {
                                    $koneksi->query("INSERT INTO obat_rm_request SET idobat = '$uniqueId', catatan_obat = '$catatan_obat', nama_obat = '$ObatKode[nama_obat]', kode_obat = '$nama[$i]', jml_dokter = '$jml_dokter[$i]', dosis1_obat = '$dosis1_obat', dosis2_obat = '$dosis2_obat', per_obat = '$per_obat', durasi_obat = '$durasi_obat', petunjuk_obat = '$petunjuk_obat', jenis_obat = '$jenis_obat', idigd = '$_GET[idigd]', tgl_pasien = '$_GET[tgl]', obat_igd = '$_POST[jenis]', racik = '$racik', idrm = '$_GET[id]', petugas = '" . $_SESSION['admin']['namalengkap'] . "'");
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    echo "
  <script>
    document.location.href='index.php?halaman=cttpenyakit_obat&id=$_GET[id]&inap&tgl=$_GET[tgl]';
  </script>
  ";
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
        if ($_SESSION['admin']['level'] != 'dokter') {
            $uniqueId = getUniqeIdObat($koneksi);
        } else {
            $uniqueId = getUniqeIdObatRequest($koneksi);
        }

        $ObatKode = $koneksi->query("SELECT id_obat, jml_obat, margininap, harga_beli, nama_obat FROM apotek WHERE tipe != '' AND id_obat= '" . $nama[$i] . "' ORDER BY idapotek DESC LIMIT 1")->fetch_assoc();
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
        $biaya = isset($_GET['inap']) ? 'biayaobat inap' : 'biayaobat igd';
        $id = $_POST["idrm"];
        $resep = 'Resep' . ' ' . $id . ' ' . $uniqueId;

        if ($_SESSION['admin']['level'] != 'dokter') {
            $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran, ket, petugas, shiftinap ) VALUES ('$_POST[idrm]', '$tanggal', '$biaya', '$harga', '$resep', '$petugas', '" . $_SESSION['shift'] . "') ");
            $koneksi->query("INSERT INTO obat_rm SET idobat='$uniqueId', catatan_obat = '$catatan_obat[$i]', nama_obat = '$ObatKode[nama_obat]', kode_obat = '$nama[$i]', jml_dokter = '$jml_dokter[$i]', dosis1_obat = '$dosis1_obat[$i]', dosis2_obat = '$dosis2_obat[$i]', per_obat = '$per_obat[$i]', durasi_obat = '$durasi_obat[$i]', tgl_pasien = '$_GET[tgl]', petunjuk_obat = '$petunjuk_obat[$i]', jenis_obat = '$jenis_obat[$i]', idigd = '$_GET[idigd]', obat_igd = '$_POST[jenis]', idrm = '$_GET[id]', petugas = '" . $_SESSION['admin']['namalengkap'] . "'");
        } else {
            $koneksi->query("INSERT INTO obat_rm_request SET idobat='$uniqueId', catatan_obat = '$catatan_obat[$i]', nama_obat = '$ObatKode[nama_obat]', kode_obat = '$nama[$i]', jml_dokter = '$jml_dokter[$i]', dosis1_obat = '$dosis1_obat[$i]', dosis2_obat = '$dosis2_obat[$i]', per_obat = '$per_obat[$i]', durasi_obat = '$durasi_obat[$i]', tgl_pasien = '$_GET[tgl]', petunjuk_obat = '$petunjuk_obat[$i]', jenis_obat = '$jenis_obat[$i]', idigd = '$_GET[idigd]', obat_igd = '$_POST[jenis]', idrm = '$_GET[id]', petugas = '" . $_SESSION['admin']['namalengkap'] . "'");
        }
    }

    echo "
  <script>
    document.location.href='index.php?halaman=cttpenyakit_obat&id=$_GET[id]&inap&tgl=$_GET[tgl]';
  </script>
  ";
}

if (isset($_POST['addRetur'])) {
    $pasien = $koneksi->query("SELECT * FROM pasien  WHERE no_rm='$_GET[id]';")->fetch_assoc();

    $idrawat = $id['idrawat'];
    $no_rm = $pasien['no_rm'];
    $nama_pasien = $pasien['nama_lengkap'];

    $obat_rm_id = $_POST['idobat'];
    $kode_obat = $_POST['kode_obat'];
    $nama_obat = $_POST['nama_obat'];
    $jenis_obat = $_POST['jenis_obat'];
    $jumlah_retur = $_POST['jumlah_retur'];

    $tgl_retur = date('Y-m-d');

    $getHargaBeliAkhir = $koneksi->query("SELECT * FROM rawatinapdetail WHERE id = '" . htmlspecialchars($id['idrawat']) . "' AND ket LIKE '%$obat_rm_id%' AND ket LIKE '%Resep%' ORDER BY created_at DESC LIMIT 1")->fetch_assoc();
    $getJum = $koneksi->query("SELECT * FROM obat_rm WHERE idobat='$obat_rm_id' LIMIT 1")->fetch_assoc();

    $hargaSatuan = -1 * $_POST['harga'];
    $uniqueId = getUniqeIdObatRetur($koneksi);

    $koneksi->query("INSERT INTO rawatinapdetail (id, biaya, ket, besaran, tgl, petugas, shiftinap) VALUES ('$idrawat', 'Retur Obat Inap', 'Retur Obat $uniqueId', '" . ($hargaSatuan) * $jumlah_retur . "', '$tgl_retur', '" . $_SESSION['admin']['namalengkap'] . "', '" . $_SESSION['shift'] . "')");

    $koneksi->query("INSERT INTO `retur_obat_inap`(`idretur`, `idrawat`, `no_rm`, `nama_pasien`, `obat_rm_id`, `kode_obat`, `nama_obat`, `jenis_obat`, `jumlah_retur`, `tgl_retur`) VALUES ('$uniqueId', '$idrawat','$no_rm','$nama_pasien','$obat_rm_id','$kode_obat','$nama_obat','$jenis_obat','$jumlah_retur','$tgl_retur')");

    echo "
            <script>
                alert('Successfully');
                window.location.href = '?halaman=cttpenyakit_obat&id=$_GET[id]&inap&tgl=$_GET[tgl]';
            </script>
        ";
}

if (isset($_GET['idObat'])) {
    $idObat = $_GET['idObat'];
    if ($_SESSION['admin']['level'] != 'dokter') {
        $koneksi->query("DELETE FROM obat_rm WHERE idobat = '$idObat'");
        $koneksi->query("DELETE FROM rawatinapdetail WHERE TRIM(SUBSTRING_INDEX(ket, ' ', -1)) = '$idObat'");
    } else {
        $koneksi->query("DELETE FROM obat_rm_request WHERE idobat = '$idObat'");
    }
    echo "
  <script>
    document.location.href='index.php?halaman=cttpenyakit_obat&id=$_GET[id]&inap&tgl=$_GET[tgl]';
  </script>
  ";
}

if (isset($_GET['copyObat'])) {
    $tgl = $_GET['tglobat'];
    $whereTgl = " AND date_format(created_at, '%Y-%m-%d') = '$tgl'";
    $obat = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND tgl_pasien='$_GET[tgl]' " . $whereTgl);
    foreach ($obat as $row) {
        $catatan_obat = $row['catatan_obat'];
        $nama = $row['kode_obat'];
        $jml_dokter = $row['jml_dokter'];
        $dosis1_obat = $row['dosis1_obat'];
        $dosis2_obat = $row['dosis2_obat'];
        $per_obat = $row['per_obat'];
        $durasi_obat = $row['durasi_obat'];
        $jenis_obat = $row['jenis_obat'];
        $petunjuk_obat = $row['petunjuk_obat'];
        $idrm = $row['idrm'];

        $end = date("H:i:s");
        if ($_SESSION['admin']['level'] != 'dokter') {
            $uniqueId = getUniqeIdObat($koneksi);
        } else {
            $uniqueId = getUniqeIdObatRequest($koneksi);
        }

        $ObatKode = $koneksi->query("SELECT id_obat, jml_obat, margininap, harga_beli, nama_obat FROM apotek WHERE tipe != '' AND id_obat= '" . $nama . "' ORDER BY idapotek DESC LIMIT 1")->fetch_assoc();
        $stokAkhir = $ObatKode['jml_obat'] - $jml_dokter;
        $m = $ObatKode['margininap'];
        if ($m < 100) {
            $margin = 1.30;
        } else {
            $margin = $m / 100;
        }
        $subtotal = 0;
        $harga = $ObatKode['harga_beli'] * $margin * $jml_dokter;
        $subtotal += $harga;
        date_default_timezone_set('Asia/Jakarta');
        $tanggal = date('Y-m-d');
        $biaya = isset($_GET['inap']) ? 'biayaobat inap' : 'biayaobat igd';
        $id = $row["idrm"];
        $resep = 'Resep' . ' ' . $id . ' ' . $uniqueId;
        if ($_SESSION['admin']['level'] != 'dokter') {
            $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran, ket, petugas ) VALUES ('$row[idrm]', '$tanggal', '$biaya', '$harga', '$resep', '$petugas') ");
            $koneksi->query("INSERT INTO obat_rm SET idobat='$uniqueId', catatan_obat = '$catatan_obat', nama_obat = '$ObatKode[nama_obat]', kode_obat = '$nama', jml_dokter = '$jml_dokter', dosis1_obat = '$dosis1_obat', dosis2_obat = '$dosis2_obat', per_obat = '$per_obat', durasi_obat = '$durasi_obat', tgl_pasien = '$_GET[tgl]', petunjuk_obat = '$petunjuk_obat', jenis_obat = '$jenis_obat', idigd = '$row[idigd]', obat_igd = '$row[obat_igd]', idrm = '$_GET[id]', petugas = '" . $_SESSION['admin']['namalengkap'] . "'");
        } else {
            $koneksi->query("INSERT INTO obat_rm_request SET idobat='$uniqueId', catatan_obat = '$catatan_obat', nama_obat = '$ObatKode[nama_obat]', kode_obat = '$nama', jml_dokter = '$jml_dokter', dosis1_obat = '$dosis1_obat', dosis2_obat = '$dosis2_obat', per_obat = '$per_obat', durasi_obat = '$durasi_obat', tgl_pasien = '$_GET[tgl]', petunjuk_obat = '$petunjuk_obat', jenis_obat = '$jenis_obat', idigd = '$row[idigd]', obat_igd = '$row[obat_igd]', idrm = '$_GET[id]', petugas = '" . $_SESSION['admin']['namalengkap'] . "'");
        }
    }
    echo "
  <script>
    alert('Obat pada Tanggal $_GET[tglobat] Berhasil Di Copy');
    document.location.href='index.php?halaman=cttpenyakit_obat&id=$_GET[id]&inap&tgl=$_GET[tgl]';
  </script>
  ";
}

if ($_SESSION['admin']['level'] == 'racik' or $_SESSION['admin']['level'] == 'apoteker') {
    // $getLatLpo = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND tgl_pasien='$_GET[tgl]' AND obat_igd = 'injeksi' GROUP BY date_format(created_at, '%Y-%m-%d') ORDER BY idobat DESC LIMIT 1")->fetch_assoc();
    // if (!isset($_GET['tglobat'])) {
    //     $tgl = date('Y-m-d', strtotime($getLatLpo['created_at']));
    // } else {
    //     $tgl = $_GET['tglobat'];
    // }
    // $whereTgl = " AND date_format(created_at, '%Y-%m-%d') = '$tgl'";
    $injek = $koneksi->query("UPDATE obat_rm SET see_apotek_at = CURDATE() WHERE idrm = '$_GET[id]' AND tgl_pasien='$_GET[tgl]' AND see_apotek_at IS NULL ");
}

if (isset($_GET['seeApotek'])) {
    $koneksi->query("UPDATE obat_rm_request SET see_apotek_at = CURDATE() WHERE idobat = '$_GET[seeApotek]'");
    echo "  <script>
    document.location.href='index.php?halaman=cttpenyakit_obat&id=$_GET[id]&inap&tgl=$_GET[tgl]';
  </script>
  ";
}

if (isset($_GET['checkCppt'])) {
    $koneksi->query("UPDATE ctt_penyakit_inap SET plan_at = '" . date('Y-m-d H:i:s') . "' WHERE id = '$_GET[checkCppt]'");

    echo "
        <script>
            document.location.href='index.php?halaman=cttpenyakit_obat&id=$_GET[id]&inap&tgl=$_GET[tgl]';
        </script>
    ";
    exit;
}

if (isset($_POST['savePenggunaan'])) {
    // Cek apakah ada obat yang dipilih
    if (isset($_POST['idobatcheck']) && !empty($_POST['idobatcheck'])) {
        // Cek apakah ada jam yang dipilih
        if (isset($_POST['digunakan']) && !empty($_POST['digunakan'])) {
            $jam_dipilih = $_POST['digunakan']; // Array jam yang dipilih
            $id_obat_list = $_POST['idobatcheck']; // Array id obat yang dipilih

            // Gabungkan jam-jam yang dipilih menjadi string
            $jam_baru = implode(', ', $jam_dipilih);

            $success_count = 0;
            $error_count = 0;

            // Loop untuk setiap obat yang dipilih
            foreach ($id_obat_list as $idobat) {
                // Escape untuk keamanan
                $idobat = mysqli_real_escape_string($koneksi, $idobat);

                // Ambil data penggunaan yang sudah ada
                $query_cek = mysqli_query($koneksi, "SELECT digunakan_pada FROM obat_rm WHERE idobat = '$idobat'");
                $data_obat = mysqli_fetch_assoc($query_cek);

                if ($data_obat) {
                    $digunakan_lama = trim($data_obat['digunakan_pada']);

                    // Jika sudah ada jam sebelumnya, pisahkan dan gabung dengan yang baru
                    if (!empty($digunakan_lama)) {
                        // Pecah jam lama menjadi array
                        $jam_lama_array = array_map('trim', explode(',', $digunakan_lama));

                        // Gabungkan dengan jam baru
                        $jam_gabung_array = array_merge($jam_lama_array, $jam_dipilih);

                        // Hilangkan duplikat dan urutkan
                        $jam_gabung_array = array_unique($jam_gabung_array);

                        // Urutkan jam secara kronologis
                        usort($jam_gabung_array, function ($a, $b) {
                            return strcmp($a, $b);
                        });

                        // Gabungkan kembali menjadi string
                        $jam_final = implode(', ', $jam_gabung_array);
                    } else {
                        // Jika belum ada jam sebelumnya, gunakan jam baru saja
                        $jam_final = $jam_baru;
                    }

                    // Update ke database
                    $update_query = "UPDATE obat_rm SET digunakan_pada = '$jam_final' WHERE idobat = '$idobat'";

                    if (mysqli_query($koneksi, $update_query)) {
                        $success_count++;
                    } else {
                        $error_count++;
                    }
                }
            }

            echo "<script>alert('Penggunaan obat berhasil disimpan!\\n\\nBerhasil: $success_count obat\\nGagal: $error_count obat');</script>";

            // Redirect untuk refresh halaman
            echo "<script>location='index.php?halaman=cttpenyakit_obat&id=" . $_GET['id'] . "&inap&tgl=" . $_GET['tgl'] . "';</script>";
        } else {
            echo "<script>alert('Silakan pilih minimal satu jam penggunaan!');</script>";
        }
    } else {
        echo "<script>alert('Silakan pilih obat yang akan dicatat penggunaannya!');</script>";
    }
}
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', async () => {
        try {
            <?php
            $apiUrlgetObat = '../apotek/api_getObatMasterLokal.php';
            // if (isset($_GET['inap'])) {
            //   $apiUrlgetObat .= '?inap';
            // } elseif (isset($_GET['penjualan'])) {
            //   $apiUrlgetObat .= '?penjualan';
            // } else {
            //   $apiUrlgetObat .= '';
            // }
            ?>
            const obatData = await (await fetch('<?= $apiUrlgetObat . '?inap' ?>')).json();

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
<div class="card shadow-sm mb-2 p-2">
    <h5 class="card-title">Data Pasien</h5>
    <!-- Multi Columns Form -->
    <div class="row">
        <div class="col-md-6">
            <label>Nama Pasien</label>
            <input type="text" class="form-control mb-2" name="pasien" id="inputName5" value="<?php echo $pasien['nama_lengkap'] ?>" placeholder="Masukkan Nama Pasien" readonly>
        </div>
        <div class="col-md-6">
            <label>No RM</label>
            <input type="text" class="form-control mb-2" id="inputName5" name="jadwal" value="<?php echo $pasien['no_rm'] ?>" placeholder="Masukkan Nama Pasien" readonly>
        </div>
        <div class="col-md-6">
            <label>Tanggal Lahir</label>
            <input type="text" class="form-control mb-2" id="inputName5" name="jadwal" value="<?php echo date("d-m-Y", strtotime($pasien['tgl_lahir'])) ?>" placeholder="Masukkan Nama Pasien" readonly>
        </div>
        <div class="col-md-6">
            <label>Alamat</label>
            <input type="text" class="form-control mb-2" id="inputName5" name="jadwal" value="<?php echo $pasien['alamat'] ?>" placeholder="Masukkan Nama Pasien" readonly>
        </div>
        <div class="col-md-6">
            <label>Ruangan</label>
            <input type="text" class="form-control mb-2" id="inputName5" name="kamar" value="<?php echo $jadwal['kamar'] ?>" placeholder="Masukkan Nama Pasien">
        </div>
        <?php if ($pasien["jenis_kelamin"] == 1) { ?>
            <div class="col-md-6">
                <label>Jenis Kelamin</label>
                <input type="text" class="form-control mb-2" id="inputName5" name="jadwal" value="Laki-laki" placeholder="Masukkan Nama Pasien" readonly>
            </div>
        <?php } else { ?>
            <div class="col-md-6">
                <label>Jenis Kelamin</label>
                <input type="text" class="form-control mb-2" id="inputName5" name="jadwal" value="Perempuan" placeholder="Masukkan Nama Pasien" readonly>
            </div>
        <?php } ?>
    </div>
</div>
<div class="card shadow-sm p-2 mb-1">
    <label for="">Obat Request</label>
    <div class="table-responsive">
        <table class="table table-striped table-hover table-sm" style="font-size: 12px;">
            <thead class="">
                <tr>
                    <th>Waktu</th>
                    <th>Petugas</th>
                    <th>Plan</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $getPulangTerakhir = $koneksi->query("SELECT * FROM pulang WHERE norm='$_GET[id]' ORDER BY id DESC LIMIT 1")->fetch_assoc();
                $getIGD = $koneksi->query("SELECT * FROM igd WHERE no_rm = '$_GET[id]' AND tindak = 'Rawat' AND tgl_masuk <= '$_GET[tgl]' ORDER BY idigd DESC LIMIT 1")->fetch_assoc();

                $getDataCppt = $koneksi->query("
                    SELECT 
                        id,
                        tgl,
                        ctt_tedis,
                        object,
                        alergi,
                        assesment,
                        plan,
                        intruksi,
                        edukasi,
                        petugas,
                        dokter,
                        norm,
                        'Catatan Penyakit' as tipe_data
                    FROM ctt_penyakit_inap 
                    WHERE norm='$_GET[id]' AND plan_at IS NULL
                        AND DATE_FORMAT(tgl, '%Y-%m-%d') > '" . ($getPulangTerakhir['tgl'] ?? date('Y-m-d', strtotime('20000-01-01'))) . "' 
                        
                    UNION ALL
                    
                    SELECT
                        NULL as id,
                        tgl_masuk as tgl,
                        keluhan as ctt_tedis,
                        '' as object,
                        riw_alergi as alergi,
                        '' as assesment,
                        rencana_rawat as plan,
                        '' as intruksi,
                        '' as edukasi,
                        perawat as petugas,
                        dokter as dokter,
                        no_rm as norm,
                        'Catatan IGD' as tipe_data
                    FROM igd
                    WHERE idigd = '$getIGD[idigd]' AND rencana_rawat_at IS NULL

                    ORDER BY tgl DESC
                ");
                foreach ($getDataCppt as $cppt) {
                ?>
                    <tr>
                        <td>
                            <?= $cppt['tgl'] ?><br>
                            <span class="badge bg-secondary"><?= $cppt['tipe_data'] ?></span>
                        </td>
                        <td>
                            <span class="badge bg-primary"><?= $cppt['dokter'] ?></span><br>
                            <span class="badge bg-primary"><?= $cppt['petugas'] ?></span>
                        </td>
                        <td><?= $cppt['plan'] ?></td>
                        <td>
                            <a href="<?= getFullUrl() ?>&checkCppt=<?= $cppt['id'] ?>" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i></a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php if (!isset($_GET['entriObat'])) { ?>
    <form class="p-0" method="post">
        <script>
            function changeJenis(jenisObat) {
                var jenis3 = document.getElementById('jenis3');
                var jenis2 = document.getElementById('jenis2');
                jenis3.value = jenisObat;
                jenis2.value = jenisObat;
            }
        </script>
        <div class="card shadow p-2 mb-1">
            <label for="">Obat Injeksi </label>
            <div>
                <?php if ($_SESSION['admin']['level'] != 'dokter') { ?>
                    <button type="button" onclick="changeJenis('Injeksi')" class="btn btn-primary btn-sm text-right"
                        data-bs-toggle="modal" data-bs-target="#exampleModal45"><?= $_SESSION['admin']['level'] == 'dokter' ? 'Request' : 'Add' ?> Jadi</button>
                    <a class="btn btn-sm btn-warning" href="../apotek/lpo_print_obat.php?id=<?= htmlspecialchars($_GET['id']) ?>&inap&tgl=<?= htmlspecialchars($_GET['tgl']) ?>&tglObat=All&jenis=All" target="_blank">Print All</a>
                <?php } ?>
                <!-- <button type="button" onclick="changeJenis('Injeksi')" class="btn btn-primary btn-sm text-right"
                      data-bs-toggle="modal" data-bs-target="#exampleModal2">Add Racik</button> -->
                <?php if ($id['apoteker_check_at'] == null) { ?>
                <?php } ?>
            </div>
            <div>
                <?php
                $getLpo = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND tgl_pasien='$_GET[tgl]' AND obat_igd = 'injeksi' GROUP BY date_format(created_at, '%Y-%m-%d') ORDER BY idobat DESC");
                foreach ($getLpo as $lpop) {
                ?>
                    <span class="badge bg-warning" style="font-size"><span onclick="document.location.href='index.php?halaman=cttpenyakit_obat&id=<?= $_GET['id'] ?>&inap&tgl=<?= $_GET['tgl'] ?>&tglobat=<?= date('Y-m-d', strtotime($lpop['created_at'])) ?>'"><?= date('Y-m-d', strtotime($lpop['created_at'])) ?></span> <i onclick="document.location.href='?halaman=cttpenyakit_obat&id=<?= $_GET['id'] ?>&inap&tgl=<?= $_GET['tgl'] ?>&tglobat=<?= date('Y-m-d', strtotime($lpop['created_at'])) ?>&copyObat'" class="bi bi-copy"></i></span>
                <?php } ?>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-striped" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th></th>
                            <th>No</th>
                            <th>Obat</th>
                            <th>Kode Obat</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Sub</th>
                            <th>Dosis</th>
                            <th>Jenis</th>
                            <th>Durasi</th>
                            <th>Tanggal</th>
                            <th>Petugas</th>
                            <th>Act</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $getLatLpo = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND tgl_pasien='$_GET[tgl]' AND obat_igd = 'injeksi' GROUP BY date_format(created_at, '%Y-%m-%d') ORDER BY idobat DESC LIMIT 1")->fetch_assoc();
                        if (!isset($_GET['tglobat'])) {
                            $tgl = date('Y-m-d', strtotime($getLatLpo['created_at']));
                        } else {
                            $tgl = $_GET['tglobat'];
                        }
                        $whereTgl = " AND date_format(created_at, '%Y-%m-%d') = '$tgl'";
                        $injek = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND tgl_pasien='$_GET[tgl]' AND obat_igd = 'injeksi'" . $whereTgl);
                        $urlBase = "index.php?halaman=cttpenyakit_obat&id=" . htmlspecialchars($_GET['id']) . "&inap&tgl=" . htmlspecialchars($_GET['tgl']);
                        $noo = 1;
                        foreach ($injek as $in) {
                        ?>
                            <tr>
                                <td><input type="checkbox" name="idobatcheck[]" value="<?= $or['idobat'] ?>" id=""></td>
                                <td class="text-light <?= $in['see_apotek_at'] == null ? 'bg-danger' : 'bg-success' ?>"><?php echo $noo++; ?></td>
                                <td><?php echo $in["nama_obat"]; ?> <br> <span style="font-size: 10px;"><?= $in['digunakan_pada'] ?></td>
                                <td><?php echo $in["kode_obat"]; ?></td>
                                <td><?php echo $in["jml_dokter"]; ?></td>
                                <td>
                                    <?php
                                    $getPriceInDate = $koneksi->query("SELECT * FROM apotek WHERE tgl_beli <= '" . date('Y-m-d', strtotime($in['created_at'])) . "' AND nama_obat = '$in[nama_obat]' ORDER BY tgl_beli DESC LIMIT 1")->fetch_assoc();
                                    ?>
                                    Rp
                                    <?= number_format($harga = $getPriceInDate['harga_beli'] * ($getPriceInDate['margininap'] / 100), 0, 0, '.') ?>
                                </td>
                                <td>
                                    Rp <?= number_format($harga * $in['jml_dokter'], 0, 0, '.') ?>
                                </td>
                                <td><?php echo $in["dosis1_obat"]; ?> X <?php echo $in["dosis2_obat"]; ?>
                                    <?php echo $in["per_obat"]; ?>
                                </td>
                                <td><?php echo $in["jenis_obat"]; ?> <?php echo $in["racik"]; ?></td>
                                <td><?php echo $in["durasi_obat"]; ?> hari</td>
                                <td>
                                    <a target="_blank"
                                        href="../apotek/lpo_print_obat.php?id=<?= htmlspecialchars($_GET['id']) ?>&inap&tgl=<?= htmlspecialchars($_GET['tgl']) ?>&tglObat=<?php echo date('Y-m-d', strtotime($in["created_at"])) ?>&jenis=<?= $in['obat_igd'] ?>"
                                        class="badge bg-warning text-dark" style="font-size: 12px;">
                                        <?php echo date('Y-m-d', strtotime($in["created_at"])) ?>
                                    </a>
                                </td>
                                <td><?= $in['petugas'] ?></td>
                                <!-- <td> <button type="button" class="btn btn-primary text-right" data-bs-toggle="modal" data-bs-target="#exampleModalEdit<?php echo $in["idobat"]; ?>">Edit</button></td> -->
                                <td>
                                    <?php if ($_SESSION['admin']['level'] == 'sup' or $_SESSION['admin']['level'] == 'apoteker' or $_SESSION['admin']['level'] == 'racik') { ?>
                                        <button class="btn btn-sm btn-warning" onclick="upData('<?= $in['idobat'] ?>','<?= $in['nama_obat'] ?>','<?= $in['kode_obat'] ?>','<?= $in['jenis_obat'] ?>', '<?= number_format($harga, 0, 0, '') ?>')" data-bs-toggle="modal" data-bs-target="#AddRetur" type="button"><i class="bi bi-capsule-pill"></i></button>
                                        <a href="<?= $urlBase ?>&idObat=<?= $in['idobat'] ?>" class="btn btn-sm btn-danger"><i
                                                class="bi bi-trash"></i></a>
                                    <?php } else { ?>
                                        <span style="font-size: 6.5px;">Kesalahan Silahkan Lapor Wadir</span>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div>
                    <input type="checkbox" class="ms-2" name="digunakan[]" id="" value="09:00"> 09:00
                    <input type="checkbox" class="ms-2" name="digunakan[]" id="" value="12:00"> 12:00
                    <input type="checkbox" class="ms-2" name="digunakan[]" id="" value="15:00"> 15:00
                    <input type="checkbox" class="ms-2" name="digunakan[]" id="" value="18:00"> 18:00
                    <input type="checkbox" class="ms-2" name="digunakan[]" id="" value="21:00"> 21:00
                    <input type="checkbox" class="ms-2" name="digunakan[]" id="" value="24:00"> 24:00
                    <input type="checkbox" class="ms-2" name="digunakan[]" id="" value="05:00"> 05:00
                    <input type="time" name="digunakan[]" class="h-100" id="">
                    <button class="btn btn-primary btn-sm text-right" type="submit" name="savePenggunaan">Simpan Penggunaan</button>
                </div>
            </div>
        </div>
        <div class="card shadow p-2 mb-1">
            <label for="">Obat Oral</label>
            <div align="left">
                <?php if ($_SESSION['admin']['level'] != 'dokter') { ?>
                    <button type="button" onclick="changeJenis('Oral')" class="btn btn-primary btn-sm text-right"
                        data-bs-toggle="modal" data-bs-target="#exampleModal45"><?= $_SESSION['admin']['level'] == 'dokter' ? 'Request' : 'Add' ?> Jadi</button>
                    <button type="button" onclick="changeJenis('Oral')" class="btn btn-primary btn-sm text-right"
                        data-bs-toggle="modal" data-bs-target="#exampleModal2"><?= $_SESSION['admin']['level'] == 'dokter' ? 'Request' : 'Add' ?> Racik</button>
                    <a class="btn btn-sm btn-warning" href="../apotek/lpo_print_obat.php?id=<?= htmlspecialchars($_GET['id']) ?>&inap&tgl=<?= htmlspecialchars($_GET['tgl']) ?>&tglObat=All&jenis=All">Print All</a>
                <?php } ?>
            </div>
            <div>
                <?php
                $getLpo = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND tgl_pasien='$_GET[tgl]' AND obat_igd = 'injeksi' GROUP BY date_format(created_at, '%Y-%m-%d') ORDER BY idobat DESC");
                foreach ($getLpo as $lpop) {
                ?>
                    <span class="badge bg-warning" style="font-size"><span onclick="document.location.href='index.php?halaman=cttpenyakit_obat&id=<?= $_GET['id'] ?>&inap&tgl=<?= $_GET['tgl'] ?>&tglobat=<?= date('Y-m-d', strtotime($lpop['created_at'])) ?>'"><?= date('Y-m-d', strtotime($lpop['created_at'])) ?></span> <i onclick="document.location.href='?halaman=cttpenyakit_obat&id=<?= $_GET['id'] ?>&inap&tgl=<?= $_GET['tgl'] ?>&tglobat=<?= date('Y-m-d', strtotime($lpop['created_at'])) ?>&copyObat'" class="bi bi-copy"></i></span>
                <?php } ?>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-striped" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th></th>
                            <th>No</th>
                            <th>Obat</th>
                            <th>Kode Obat</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Sub</th>
                            <th>Dosis</th>
                            <th>Jenis</th>
                            <th>Durasi</th>
                            <th>Tanggal</th>
                            <th>Petugas</th>
                            <th>Act</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $getLatLpo = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND tgl_pasien='$_GET[tgl]' AND obat_igd = 'oral' GROUP BY date_format(created_at, '%Y-%m-%d') ORDER BY idobat DESC LIMIT 1")->fetch_assoc();
                        if (!isset($_GET['tglobat'])) {
                            $tgl = date('Y-m-d', strtotime($getLatLpo['created_at']));
                        } else {
                            $tgl = $_GET['tglobat'];
                        }
                        $whereTgl = " AND date_format(created_at, '%Y-%m-%d') = '$tgl'";
                        $oral = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND tgl_pasien='$_GET[tgl]' AND obat_igd = 'oral'" . $whereTgl);

                        $no = 1;
                        foreach ($oral as $or) {
                        ?>
                            <tr>
                                <td><input type="checkbox" name="idobatcheck[]" value="<?= $or['idobat'] ?>" id=""></td>
                                <td class="text-light <?= $or['see_apotek_at'] == null ? 'bg-danger' : 'bg-success' ?>"><?php echo $no++; ?></td>
                                <td><?php echo $or["nama_obat"]; ?><br> <span style="font-size: 10px;"><?= $or['digunakan_pada'] ?></td>
                                <td><?php echo $or["kode_obat"]; ?></td>
                                <td><?php echo $or["jml_dokter"]; ?></td>
                                <td>
                                    <?php
                                    $getPriceInDate = $koneksi->query("SELECT * FROM apotek WHERE tgl_beli <= '" . date('Y-m-d', strtotime($or['created_at'])) . "' AND id_obat = '$or[kode_obat]' ORDER BY tgl_beli DESC LIMIT 1")->fetch_assoc();
                                    ?>
                                    Rp
                                    <?= number_format($harga = $getPriceInDate['harga_beli'] * ($getPriceInDate['margininap'] / 100), 0, 0, '.') ?>
                                </td>
                                <td>
                                    Rp <?= number_format($harga * $or['jml_dokter'], 0, 0, '.') ?>
                                </td>
                                <td><?php echo $or["dosis1_obat"]; ?> X <?php echo $or["dosis2_obat"]; ?>
                                    <?php echo $or["per_obat"]; ?>
                                </td>
                                <td><?php echo $or["jenis_obat"]; ?> <?php echo $or["racik"]; ?></td>
                                <td><?php echo $or["durasi_obat"]; ?> hari</td>
                                <td>
                                    <a target="_blank"
                                        href="../apotek/lpo_print_obat.php?id=<?= htmlspecialchars($_GET['id']) ?>&inap&tgl=<?= htmlspecialchars($_GET['tgl']) ?>&tglObat=<?php echo date('Y-m-d', strtotime($or["created_at"])) ?>&jenis=<?= $or['obat_igd'] ?>"
                                        class="badge bg-warning text-dark" style="font-size: 12px;">
                                        <?php echo date('Y-m-d', strtotime($or["created_at"])) ?>
                                    </a>
                                </td>
                                <td>
                                    <?= $or['petugas'] ?>
                                </td>
                                <!-- <td> <button type="button" class="btn btn-primary text-right" data-bs-toggle="modal" data-bs-target="#exampleModalEdit<?php echo $or["idobat"]; ?>">Edit</button></td> -->
                                <td>
                                    <?php if ($_SESSION['admin']['level'] == 'sup' or $_SESSION['admin']['level'] == 'apoteker' or $_SESSION['admin']['level'] == 'racik') { ?>
                                        <button class="btn btn-sm btn-warning" onclick="upData('<?= $or['idobat'] ?>','<?= $or['nama_obat'] ?>','<?= $or['kode_obat'] ?>','<?= $or['jenis_obat'] ?>', '<?= number_format($harga, 0, 0, '') ?>')" data-bs-toggle="modal" data-bs-target="#AddRetur" type="button"><i class="bi bi-capsule-pill"></i></button>
                                        <a href="<?= $urlBase ?>&idObat=<?= $or['idobat'] ?>" class="btn btn-sm btn-danger"><i
                                                class="bi bi-trash"></i></a>
                                    <?php } else { ?>
                                        <span style="font-size: 6.5px;">Kesalahan Silahkan Lapor Wadir</span>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div>
                    <input type="checkbox" class="ms-2" name="digunakan[]" id="" value="09:00"> 09:00
                    <input type="checkbox" class="ms-2" name="digunakan[]" id="" value="12:00"> 12:00
                    <input type="checkbox" class="ms-2" name="digunakan[]" id="" value="15:00"> 15:00
                    <input type="checkbox" class="ms-2" name="digunakan[]" id="" value="18:00"> 18:00
                    <input type="checkbox" class="ms-2" name="digunakan[]" id="" value="21:00"> 21:00
                    <input type="checkbox" class="ms-2" name="digunakan[]" id="" value="24:00"> 24:00
                    <input type="checkbox" class="ms-2" name="digunakan[]" id="" value="05:00"> 05:00
                    <input type="time" name="digunakan[]" class="h-100" id="">
                    <button class="btn btn-primary btn-sm text-right" type="submit" name="savePenggunaan">Simpan Penggunaan</button>
                </div>
            </div>
        </div>
    </form>
    <div class="card shadow p-2 mb-1">
        <b>Riwayat Retur Obat</b>
        <div class="table-responsive">
            <table class="table table-hover table-striped table-sm" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tgl</th>
                        <th>Kode</th>
                        <th>Nama Obat</th>
                        <th>Jenis Obat</th>
                        <th>Jenis</th>
                        <th>Harga</th>
                        <th>Jumlah Retur</th>
                        <th>Sub</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $nooo = 1;
                    $getRetur = $koneksi->query("SELECT *, obat_rm.obat_igd FROM retur_obat_inap INNER JOIN obat_rm ON obat_rm.idobat = retur_obat_inap.obat_rm_id WHERE idrawat = '" . htmlspecialchars($id['idrawat']) . "'");
                    foreach ($getRetur as $retur) {
                    ?>
                        <tr>
                            <td><?= $nooo++ ?></td>
                            <td><?= $retur['tgl_retur'] ?></td>
                            <td>
                                <a target="_blank" href="../apotek/retur_obat_inap_print.php?idrawat=<?= $retur['idrawat'] ?>&tgl=<?= $retur['tgl_retur'] ?>" class="badge bg-warning text-light" style="font-size: 12px;">
                                    <?= $retur['kode_obat'] ?>
                                </a>
                            </td>
                            <td><?= $retur['nama_obat'] ?></td>
                            <td><?= $retur['jenis_obat'] ?></td>
                            <td><?= $retur['obat_igd'] ?></td>
                            <td>
                                <?php
                                $getPriceInDate = $koneksi->query("SELECT * FROM rawatinapdetail WHERE ket LIKE '%Retur%' AND ket LIKE '%$retur[idretur]%' ORDER BY created_at DESC LIMIT 1")->fetch_assoc();
                                $harga = $getPriceInDate['besaran'] / $retur['jumlah_retur'];
                                ?>
                                Rp <?= number_format($harga, 0, 0, '.') ?>
                            </td>
                            <td><?= $retur['jumlah_retur'] ?></td>
                            <td>Rp <?= number_format($harga * $retur['jumlah_retur'], 0, 0, '.') ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        function upData(idobat, nama_obat, kode_obat, jenis_obat, harga) {
            document.getElementById('idobat_id').value = idobat;
            document.getElementById('nama_obat_id').value = nama_obat;
            document.getElementById('kode_obat_id').value = kode_obat;
            document.getElementById('jenis_obat_id').value = jenis_obat;
            document.getElementById('harga_id').value = harga;
        }
    </script>

    <!-- Add Retur Modal -->
    <div class="modal fade" id="AddRetur" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Retur Obat</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-3">
                                <input type="text" readonly name="nama_obat" id="nama_obat_id" class="form-control form-control-sm mb-1">
                                <input type="text" readonly name="idobat" id="idobat_id" hidden class="form-control form-control-sm mb-1">
                            </div>
                            <div class="col-3">
                                <input type="text" readonly name="kode_obat" id="kode_obat_id" class="form-control form-control-sm mb-1">
                            </div>
                            <div class="col-3">
                                <input type="text" readonly name="harga" id="harga_id" class="form-control form-control-sm mb-1">
                            </div>
                            <div class="col-3">
                                <input type="text" readonly name="jenis_obat" id="jenis_obat_id" class="form-control form-control-sm mb-1">
                            </div>
                            <div class="col-12">
                                <input type="number" autofocus name="jumlah_retur" id="jumlah_retur_id" placeholder="Jumlah Retur" class="form-control form-control-sm mb-1">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="addRetur" class="btn btn-sm btn-primary">Retur</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Add Retur Modal -->

    <!-- Add Data Modal Obat  Jadi -->
    <div class="modal  fade" role="dialog" id="exampleModal45" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Obat <sup class="badge bg-primary text-light"><a
                                href="<?= getFullUrl() ?>&entriObat=Jadi" class="text-light">NewTab</a></sup></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-1">
                        <div class="col-6">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-sm" style="font-size: 12px;">
                                    <thead class="">
                                        <tr>
                                            <th>Waktu</th>
                                            <th>Petugas</th>
                                            <th>Plan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($getDataCppt as $cppt) {
                                        ?>
                                            <tr>
                                                <td>
                                                    <?= $cppt['tgl'] ?><br>
                                                    <span class="badge bg-secondary"><?= $cppt['tipe_data'] ?></span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary"><?= $cppt['dokter'] ?></span><br>
                                                    <span class="badge bg-primary"><?= $cppt['petugas'] ?></span>
                                                </td>
                                                <td><?= $cppt['plan'] ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-6">
                            <form method="post" enctype="multipart/form-data">
                                <div class="control-group">
                                    <!-- <div class="modal-body"> -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="inputName5" class="form-label">Nama Obat</label><br>
                                            <input type="text" name="jenis" id="jenis3" hidden>
                                            <!-- <input type="text" name="nama_obat" class="form-control" id="inputName5" placeholder="Layanan/Tindakan"> -->
                                            <select name="nama_obat[]" class="obat-select form-select mb-2 w-100" style="width:100%;"
                                                id="selObat1" aria-label="Default select example">
                                                <option value="">Pilih</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="inputName5">Dosis</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control mb-2" id="dosis1_obat" name="dosis1_obat[]">
                                                <span type="text" style="text-align: center;" class="form-control mb-2" placeholder="X"
                                                    disabled>X</span>
                                                <input type="text" class="form-control mb-2" id="dosis2_obat" name="dosis2_obat[]">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            Per
                                            <select id="inputState" name="per_obat[]" class="form-select">
                                                <option>Per Hari</option>
                                                <option>Per Jam</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="">Jumlah Obat</label>
                                            <input type="number" name="jml_dokter[]" class="form-control mb-2" id="inputName5"
                                                placeholder="Jumlah Obat">
                                        </div>
                                        <div class="col-md-12">
                                            <label for="inputName5" class="">Petunjuk Pemakaian</label>
                                            <input type="text" name="petunjuk_obat[]" class="form-control mb-2" id="inputName5"
                                                placeholder="Masukkan Petunjuk Pemakaian">
                                        </div>
                                        <div class="col-md-12">
                                            <!-- <label for="inputName5" class="">Catatan Interaksi Obat</label> -->
                                            <input type="text" name="catatan_obat[]" value="-" hidden class="form-control mb-2"
                                                id="inputName5" placeholder="Masukkan Jumlah">
                                        </div>
                                        <div class="col-md-12">
                                            <label for="inputName5" class="">Jenis Obat</label>
                                            <select name="jenis_obat[]" class="form-select mb-2">
                                                <option value="Jadi">Jadi</option>
                                                <!-- <option value="Jadi">Jadi</option> -->
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="inputCity" class="">Durasi</label>
                                            <div class="input-group mb-3">
                                                <input type="text" name="durasi_obat[]" class="form-control" placeholder="Durasi"
                                                    aria-describedby="basic-addon2">
                                                <span class="input-group-text" id="basic-addon2">Hari</span>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                                <button class="btn btn-warning add-more2" type="button">
                                    <i class="glyphicon glyphicon-plus"></i> Tambah Lagi
                                </button>
                                <hr>
                                <div class="after-add-more2"></div>
                                <div class="copy2 invisible" style="display: none;">
                                    <br>
                                    <div class="control-group2">
                                        <label for="inputName5" class="form-label">Nama Obat</label>
                                        <!-- <input type="text" name="nama_obat" class="form-control" id="inputName5" placeholder="Layanan/Tindakan"> -->
                                        <select name="nama_obat[]" class="obat-select form-select mb-2" id="selObat1"
                                            aria-label="Default select example">
                                            <option value="">Pilih</option>
                                        </select>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="inputName5" class="">Dosis</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control mb-2" id="dosis1_obat" name="dosis1_obat[]">
                                                    <span type="text" style="text-align: center;" class="form-control mb-2"
                                                        placeholder="X">X</span>
                                                    <input type="text" class="form-control mb-2" id="dosis2_obat" name="dosis2_obat[]">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="inputName5" class="">Per</label>
                                                <select id="inputState" name="per_obat[]" class="form-select">
                                                    <option>Per Hari</option>
                                                    <option>Per Jam</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="">Jumlah Obat</label>
                                            <input type="number" name="jml_dokter[]" class="form-control mb-2" id="inputName5"
                                                placeholder="jumlah obat">
                                        </div>
                                        <div class="col-md-12">
                                            <label for="inputName5">Petunjuk Pemakaian</label>
                                            <input type="text" name="petunjuk_obat[]" class="form-control mb-2" id="inputName5"
                                                placeholder="Masukkan Petunjuk Pemakaian">
                                        </div>
                                        <div class="col-md-12">
                                            <!-- <label for="inputName5">Catatan Interaksi Obat</label> -->
                                            <input type="text" name="catatan_obat[]" value="-" hidden class="form-control mb-2"
                                                id="inputName5" placeholder="Masukkan Jumlah">
                                        </div>
                                        <div class="col-md-12">
                                            <label for="inputName5">Jenis Obat</label>
                                            <select name="jenis_obat[]" class="form-select mb-2">
                                                <option value="Jadi">Jadi</option>
                                                <!-- <option value="Jadi">Jadi</option> -->
                                            </select>
                                        </div>

                                        <div class="col-md-12">
                                            <label for="inputCity">Durasi</label>
                                            <div class="input-group mb-3">
                                                <input type="text" name="durasi_obat[]" class="form-control mb-2" placeholder="Durasi"
                                                    aria-describedby="basic-addon2">
                                                <span class="input-group-text" id="basic-addon2">Hari</span>
                                            </div>
                                        </div>
                                        <button class="btn btn-danger remove2" type="button"><i
                                                class="glyphicon glyphicon-remove"></i> Batal</button>
                                        <button class="btn btn-warning"
                                            onclick="document.getElementsByClassName('add-more2')[0].click()" type="button">
                                            <i class="glyphicon glyphicon-plus"></i> Tambah Lagi
                                        </button>
                                        <hr>
                                    </div>
                                </div>

                                <!-- <input type="hidden" name="id_pasien" value="<?php echo $pecah['idpasien'] ?>"> -->
                                <input type="hidden" name="idrm" value="<?php echo $id['idrawat'] ?>">
                                <input type="hidden" class="form-control" id="inputName5" name="id"
                                    value="<?php echo $jadwal['idrawat'] ?>" placeholder="Masukkan Nama Pasien">
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
    </div>
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
    <!-- End Add Data Modal Obat  Jadi -->

    <!-- Add Data Modal Obat Racik -->
    <div class="modal  fade" role="dialog" id="exampleModal2" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Obat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-1">
                        <div class="col-6">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-sm" style="font-size: 12px;">
                                    <thead class="">
                                        <tr>
                                            <th>Waktu</th>
                                            <th>Petugas</th>
                                            <th>Plan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($getDataCppt as $cppt) {
                                        ?>
                                            <tr>
                                                <td>
                                                    <?= $cppt['tgl'] ?><br>
                                                    <span class="badge bg-secondary"><?= $cppt['tipe_data'] ?></span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary"><?= $cppt['dokter'] ?></span><br>
                                                    <span class="badge bg-primary"><?= $cppt['petugas'] ?></span>
                                                </td>
                                                <td><?= $cppt['plan'] ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-6">
                            <form method="post" enctype="multipart/form-data">
                                <div class="control-group after-add-more">
                                    <!-- <div class="modal-body"> -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <input hidden type="text" id="jenis2" name="jenis" class="form-control">
                                            <label for="inputName5" class="form-label">Nama Obat</label><br>
                                            <select name="nama_obat[]" class="obat-select form-control w-100" style="width:100%;"
                                                id="selObat1" aria-label="Default select example">
                                                <option value="">Pilih</option>
                                            </select>
                                        </div>

                                        <script></script>
                                        <div class="col-md-12" style="margin-top:20px">
                                            <label for="">Jumlah Obat</label>
                                            <input type="number" name="jml_dokter[]" class="form-control" id="inputName5"
                                                placeholder="jumlah obat">
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
                                        <select name="nama_obat[]" class="obat-select form-control " id="selObat1"
                                            aria-label="Default select example">
                                            <option value="">Pilih</option>
                                        </select>
                                        <div class="col-md-12" style="margin-top:20px">
                                            <label for="">Jumlah Obat</label>
                                            <input type="number" name="jml_dokter[]" class="form-control" id="inputName5"
                                                placeholder="jumlah obat">
                                        </div>
                                        <br>
                                        <button class="btn btn-danger remove" type="button"><i
                                                class="glyphicon glyphicon-remove"></i> Batal</button>
                                        <hr>
                                    </div>
                                </div>
                                <div class="col-md-12" style="margin-top:20px; margin-bottom:20px;">
                                    <label for="inputName5" class="form-label">Catatan Interaksi Obat</label>
                                    <input type="text" name="catatan_obat[]" class="form-control" id="inputName5"
                                        placeholder="Masukkan Jumlah">
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
                                    <input type="number" name="racik[]" class="form-control" id="inputName5"
                                        placeholder="Masukkan racik">
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
                                        <input type="text" name="durasi_obat[]" class="form-control" placeholder="Durasi"
                                            aria-describedby="basic-addon2">
                                        <span class="input-group-text" id="basic-addon2">Hari</span>
                                    </div>
                                </div>
                                <div class="col-md-12" style="margin-top:10px">
                                    <label for="inputName5" class="form-label">Petunjuk Pemakaian</label>
                                    <input type="text" name="petunjuk_obat[]" class="form-control" id="inputName5"
                                        placeholder="Masukkan Petunjuk Pemakaian">
                                </div>
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
    </div>
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
    <!-- End Add Data Modal Obat Racik -->
<?php } else { ?>
    <div class="card shadow-sm mb-2 p-2">
        <h5><b>Entri Obat Jadi</b></h5>
        <form method="post">
            <div class="row g-1">
                <div class="col-md-2">
                    <label>Nama Obat</label>
                    <select name="nama_obat" class="obat-select form-control form-control-sm mb-2 w-100"
                        style="width:100%;" id="selectObatJadiEntriObat" aria-label="Default select example">
                        <option value="">Pilih</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label for="">Jenis</label>
                    <select name="jenis_obat_2" class="form-control form-control-sm" id="">
                        <option value="Oral">Oral</option>
                        <option value="Injeksi">Injeksi</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="">Dosis</label>
                    <div class="input-group input-group-sm ">
                        <input type="text" class="form-control form-control-sm mb-2" id="dosis1_obat" name="dosis1_obat">
                        <span type="text" style="text-align: center;" class="form-control form-control-sm mb-2"
                            placeholder="X" disabled>X</span>
                        <input type="text" class="form-control form-control-sm mb-2" id="dosis2_obat" name="dosis2_obat">
                    </div>
                </div>
                <div class="col-md-2">
                    <label for="">Per</label>
                    <select id="inputState" name="per_obat" class=" form-control form-control-sm">
                        <option>Per Hari</option>
                        <option>Per Jam</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="">Jumlah</label>
                    <input type="number" name="jml_dokter" class="form-control form-control-sm mb-2" id="inputName5"
                        placeholder="Jumlah Obat">
                </div>
                <div class="col-md-2">
                    <label for="inputName5" class="">Petunjuk</label>
                    <input type="text" name="petunjuk_obat" class="form-control form-control-sm mb-2" id="inputName5"
                        placeholder=" Petunjuk Pemakaian">
                    <input type="text" name="catatan_obat" value="-" hidden class="form-control form-control-sm mb-2"
                        id="inputName5" placeholder="Masukkan Jumlah">
                    <select name="jenis_obat" hidden class=" form-control form-control-sm mb-2">
                        <option value="Jadi">Jadi</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label for="inputCity" class="">Durasi</label>
                    <div class="input-group mb-3">
                        <input type="text" name="durasi_obat" class="form-control form-control-sm" placeholder="Durasi"
                            aria-describedby="basic-addon2">
                    </div>
                </div>
                <div class="col-md-12 text-end">
                    <button class="btn btn-sm btn-primary" name="addToSession">Tambah [+]</button>
                </div>
            </div>
        </form>
        <?php
        // Proses tambah ke session
        if (isset($_POST['addToSession'])) {
            // Inisialisasi session jika belum ada
            if (!isset($_SESSION['temp_obat'])) {
                $_SESSION['temp_obat'] = array();
            }

            // Ambil data obat dari database untuk mendapatkan nama obat
            $id_obat = $_POST['nama_obat'];
            $query = $koneksi->query("SELECT * FROM apotek WHERE id_obat = '$id_obat' ORDER BY idapotek DESC LIMIT 1");
            $data_obat = $query->fetch_assoc();

            // Tambahkan data ke session
            $_SESSION['temp_obat'][] = array(
                'id_obat' => $id_obat,
                'nama_obat' => $data_obat['nama_obat'],
                'dosis1_obat' => $_POST['dosis1_obat'],
                'dosis2_obat' => $_POST['dosis2_obat'],
                'per' => $_POST['per_obat'],
                'jumlah' => $_POST['jml_dokter'],
                'petunjuk' => $_POST['petunjuk_obat'],
                'catatan' => $_POST['catatan_obat'],
                'jenis' => $_POST['jenis_obat'],
                'jenis2' => $_POST['jenis_obat_2'],
                'durasi' => $_POST['durasi_obat']
            );
        }

        // Proses hapus dari session
        if (isset($_GET['hapusObatSession'])) {
            $index = $_GET['hapusObatSession'];
            unset($_SESSION['temp_obat'][$index]);
        }

        if (isset($_GET['clear_session'])) {
            unset($_SESSION['temp_obat']);
            echo "<script>window.location.href = 'index.php?halaman=cttpenyakit_obat&id=" . htmlspecialchars($_GET['id']) . "&tgl=" . htmlspecialchars($_GET['tgl']) . "&entriObat=" . htmlspecialchars($_GET['entriObat']) . "';</script>";
        }

        if (isset($_GET['saveToDatabase'])) {
            if (isset($_SESSION['temp_obat']) && count($_SESSION['temp_obat']) > 0) {
                foreach ($_SESSION['temp_obat'] as $obatSave) {

                    // $koneksi->query("INSERT INTO obat_rm SET catatan_obat = '', kode_obat = '', nama_obat = '', jml_dokter = '', dosis1_obat = '$obatSave[dosis1_obat]', dosis2_obat = '$obatSave[dosis2_obat]', per_obat = '$obatSave[per]', durasi_obat = '$obatSave[durasi]', petunjuk_obat = '$obatSave[petunjuk]', jenis_obat = '$obatSave[jenis]', tgl_pasien = '$_GET[tgl]', rekam_medis_id = '$getLastRM[id_rm]', idrm = '$_GET[id]'");

                    if ($_SESSION['admin']['level'] != 'dokter') {
                        $uniqueId = getUniqeIdObat($koneksi);
                        $koneksi->query("INSERT INTO obat_rm SET idobat='$uniqueId', catatan_obat = '$obatSave[catatan]', nama_obat = '$obatSave[nama_obat]', kode_obat = '$obatSave[id_obat]', jml_dokter = '$obatSave[jumlah]', dosis1_obat = '$obatSave[dosis1_obat]', dosis2_obat = '$obatSave[dosis2_obat]', per_obat = '$obatSave[per]', durasi_obat = '$obatSave[durasi]', tgl_pasien = '$_GET[tgl]', petunjuk_obat = '$obatSave[petunjuk]', jenis_obat = '$obatSave[jenis]', idigd = '" . (isset($_GET['idigd']) ? $_GET['idigd'] : '') . "', obat_igd = '$obatSave[jenis2]', idrm = '$id[no_rm]', petugas = '" . $_SESSION['admin']['namalengkap'] . "'");
                    } else {
                        $uniqueId = getUniqeIdObatRequest($koneksi);
                        $koneksi->query("INSERT INTO obat_rm_request SET idobat='$uniqueId', catatan_obat = '$obatSave[catatan]', nama_obat = '$obatSave[nama_obat]', kode_obat = '$obatSave[id_obat]', jml_dokter = '$obatSave[jumlah]', dosis1_obat = '$obatSave[dosis1_obat]', dosis2_obat = '$obatSave[dosis2_obat]', per_obat = '$obatSave[per]', durasi_obat = '$obatSave[durasi]', tgl_pasien = '$_GET[tgl]', petunjuk_obat = '$obatSave[petunjuk]', jenis_obat = '$obatSave[jenis]', idigd = '" . (isset($_GET['idigd']) ? $_GET['idigd'] : '') . "', obat_igd = '$obatSave[jenis2]', idrm = '$id[no_rm]', petugas = '" . $_SESSION['admin']['namalengkap'] . "'");
                    }

                    $ObatKode = $koneksi->query("SELECT id_obat, jml_obat, margininap, harga_beli, nama_obat FROM apotek WHERE tipe != '' AND id_obat= '" . $obatSave['id_obat'] . "' ORDER BY idapotek DESC LIMIT 1")->fetch_assoc();

                    $stokAkhir = $ObatKode['jml_obat'] - $obatSave['jumlah'];
                    $m = $ObatKode['margininap'];
                    if ($m < 100) {
                        $margin = 1.30;
                    } else {
                        $margin = $m / 100;
                    }
                    $subtotal = 0;
                    $harga = $ObatKode['harga_beli'] * $margin * $obatSave['jumlah'];
                    $subtotal += $harga;
                    date_default_timezone_set('Asia/Jakarta');
                    $tanggal = date('Y-m-d');
                    $biaya = isset($_GET['inap']) ? 'biayaobat inap' : 'biayaobat igd';
                    $id = $id['idrawat'];
                    $resep = 'Resep' . ' ' . $id . ' ' . $uniqueId;

                    if ($_SESSION['admin']['level'] != 'dokter') {
                        $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran, ket, petugas ) VALUES ('$id', '$tanggal', '$biaya', '$harga', '$resep', '" . $_SESSION['admin']['namalengkap'] . "') ");
                    }

                    // $koneksi->query("INSERT INTO rmedis_obat (id_rm, id_obat, dosis1_obat, dosis2_obat, per_obat, jumlah_obat, petunjuk_obat, catatan_obat, jenis_obat, durasi_obat) VALUES ('" . htmlspecialchars($_GET['id']) . "', '" . $obat['id_obat'] . "', '" . $obat['dosis1_obat'] . "', '" . $obat['dosis2_obat'] . "', '" . $obat['per'] . "', '" . $obat['jumlah'] . "', '" . $obat['petunjuk'] . "', '" . $obat['catatan'] . "', '" . $obat['jenis'] . "', '" . $obat['durasi'] . "')");
                }
                unset($_SESSION['temp_obat']);
                echo "<script>alert('Data berhasil disimpan ke database.'); window.location.href = 'index.php?halaman=cttpenyakit_obat&id=" . htmlspecialchars($_GET['id']) . "&inap&tgl=" . htmlspecialchars($_GET['tgl']) . "';</script>";
            } else {
                echo "<script>alert('Tidak ada data obat untuk disimpan.');</script>";
            }
        }
        ?>
        <br>
        <div class="table-responsive">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-sm" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th>Obat</th>
                            <th>Kode</th>
                            <th>Dosis</th>
                            <th>Per</th>
                            <th>Jumlah</th>
                            <th>Petunjuk</th>
                            <th>Catatan</th>
                            <th>Durasi</th>
                            <th>JenisObat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($_SESSION['temp_obat']) && count($_SESSION['temp_obat']) > 0): ?>
                            <?php foreach ($_SESSION['temp_obat'] as $index => $obat): ?>
                                <tr>
                                    <td><?= $obat['nama_obat'] ?></td>
                                    <td><?= $obat['id_obat'] ?></td>
                                    <td><?= $obat['dosis1_obat'] ?> X <?= $obat['dosis2_obat'] ?></td>
                                    <td><?= $obat['per'] ?></td>
                                    <td><?= $obat['jumlah'] ?></td>
                                    <td><?= $obat['petunjuk'] ?></td>
                                    <td><?= $obat['catatan'] ?></td>
                                    <td><?= $obat['durasi'] ?></td>
                                    <td><?= $obat['jenis2'] ?></td>
                                    <td>
                                        <a href="<?= getFullUrl() ?>&entriObat=<?= htmlspecialchars($_GET['entriObat']) ?>&hapusObatSession=<?= $index ?>"
                                            class="btn btn-danger btn-sm">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center">Tidak ada data obat</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <?php if (isset($_SESSION['temp_obat']) && count($_SESSION['temp_obat']) > 0): ?>
                    <div class="text-end mt-3">
                        <a href="<?= getFullUrl() ?>&entriObat=<?= htmlspecialchars($_GET['entriObat']) ?>&saveToDatabase"
                            class="btn btn-sm btn-success">Simpan ke Database</a>
                        <a href="<?= getFullUrl() ?>&entriObat=<?= htmlspecialchars($_GET['entriObat']) ?>&clear_session=true"
                            class="btn btn-sm btn-danger">Bersihkan Session</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $('#selectObatJadiEntriObat').select2();
            });
        </script>
    </div>
<?php } ?>