<?php
// error_reporting(0);
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

$getLastRM = $koneksi->query("SELECT  *, COUNT(*) AS jumm, MAX(id_rm) as id_rm, MAX(jadwal) as jadwall FROM rekam_medis WHERE norm = '" . htmlspecialchars($_GET['id']) . "' AND DATE_FORMAT(jadwal, '%Y-%m-%d') <= '" . date('Y-m-d', strtotime($_GET['tgl'])) . "' ORDER BY id_rm DESC LIMIT 1")->fetch_assoc();
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const targetElement = document.getElementById('RuangIsi');
        if (targetElement) {
            targetElement.scrollIntoView();
            targetElement.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
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
                const selectedValue = select.value;
                const existingOptions = Array.from(select.options).map(opt => opt.value);
                const newOptions = obatData.filter(obat =>
                    !existingOptions.includes(obat.kode_obat)
                );
                newOptions.forEach(obat => {
                    select.add(new Option(obat.nama_obat, obat.kode_obat));
                });
                if (selectedValue && select.querySelector(`option[value="${selectedValue}"]`)) {
                    select.value = selectedValue;
                }
            });
        } catch (error) {
            console.error('Error:', error);
        }
    });
</script>

<div class="card p-2 mb-2">
    <h5 class="card-title"><?php echo $jadwal['nama_pasien'] ?> (<?php echo $jadwal['no_rm'] ?>) | Lahir: <?php echo $pas['tgl_lahir'] ?> | JK: <?php echo $jk ?> <br> BPJS: <?php echo $pas['no_bpjs'] ?? '-' ?> | Jadwal: <?php echo $jadwal['jadwal'] ?> | Pembiayaan: <?php echo $pas['pembiayaan'] ?></h5>
</div>
<a href="index.php?halaman=rmedis&id=<?= htmlspecialchars($_GET['id']) ?>&tgl=<?= htmlspecialchars($_GET['tgl']) ?>" class="btn btn-sm btn-dark mb-1" style="max-width: 110px;">Black</a>
<div class="card p-2">
    <h5 class="card-title">Daftar Obat :</h5>
    <div class="table-responsive">
        <table class="table table-hover table-sm table-striped" style="font-size: 12px;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $getLastRM['jumm'] > 0 ? $whereConditionObatRm = "AND rekam_medis_id = '$getLastRM[id_rm]'" : $whereConditionObatRm = "AND rekam_medis_id IS NULL";
                $getObat = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND jenis_obat = '" . htmlspecialchars($_GET['jenis']) . "' AND racik = '" . htmlspecialchars($_GET['racik']) . "' " . $whereConditionObatRm . " ");
                ?>
                <?php foreach ($getObat as $obat) { ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $obat['nama_obat'] ?></td>
                        <td><?= $obat['jml_dokter'] ?></td>
                        <td>
                            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#staticBackdrop<?= $obat['idobat'] ?>"><i class="bi bi-pencil"></i></button>
                            <a href="" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <!-- Modal -->
                    <div class="modal fade" id="staticBackdrop<?= $obat['idobat'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Obat</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="" method="post">
                                    <div class="modal-body">
                                        <label for="" class="mb-0">Obat</label>
                                        <select name="nama_obat" id="nama_obat_id" class="obat-select form-control form-control-sm">
                                            <option value="<?= $obat['kode_obat'] ?>"><?= $obat['nama_obat'] ?></option>
                                        </select>
                                        <label for="" class="mb-0"></label>
                                        <input type="text" name="jml_dokter" value="<?= $obat['jml_dokter'] ?>" class="form-control form-control-sm">
                                        <input type="text" name="idobat" value="<?= $obat['idobat'] ?>" id="idobat_id" hidden>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="sabmit" name="editObat" class="btn btn-sm btn-success">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php
if (isset($_POST['editObat'])) {
    $idobat = $_POST['idobat'];
    $jml_dokter = $_POST['jml_dokter'];
    $nama_obat = $_POST['nama_obat'];
    $getSingleObat = $koneksimaster->query("SELECT * FROM master_obat WHERE kode_obat='$nama_obat'")->fetch_assoc();
    $koneksi->query("UPDATE obat_rm SET jml_dokter='$jml_dokter', kode_obat='$getSingleObat[kode_obat]', nama_obat='$getSingleObat[obat_master]' WHERE idobat='$idobat'");
    echo "<script>alert('Data Berhasil Diubah');</script>";
    echo "<script>location='index.php?halaman=rmedis_editObatJenis&id=" . htmlspecialchars($_GET['id']) . "&tgl=" . htmlspecialchars($_GET['tgl']) . "&editObat&jenis=" . htmlspecialchars($_GET['jenis']) . "&racik=" . htmlspecialchars($_GET['racik']) . "';</script>";
}
?>