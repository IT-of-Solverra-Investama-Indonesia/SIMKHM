<?php
error_reporting(1);
$rmSingle = $koneksi->query("SELECT * FROM  rekam_medis WHERE id_rm = '" . htmlspecialchars($_GET['id']) . "'")->fetch_assoc();
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
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
<div class="pagetitle">
    <h1>Edit Rekam Medis <?= $rmSingle['nama_pasien'] ?></h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="index.php?halaman=daftarrmedis" style="color:blue;">Rekam Medis</a></li>
            <li class="breadcrumb-item">Edit Rekam Medis <?= $rmSingle['nama_pasien'] ?> / <?= $rmSingle['jadwal'] ?></li>
        </ol>
    </nav>
</div>
<div class="card p-2 mb-2">
    <h5 class="card-title mb-0">Data Pasien</h5>
    <label for="">Nama Pasien</label>
    <input type="text" name="" id="" class="form-control mb-2" readonly value="<?= $rmSingle['nama_pasien'] ?>">
    <label for="">No RM Pasien</label>
    <input type="text" name="" id="" class="form-control mb-2" readonly value="<?= $rmSingle['norm'] ?>">
    <label for="">Jadwal Pasien</label>
    <input type="text" name="" id="" class="form-control mb-2" readonly value="<?= $rmSingle['jadwal'] ?>">
</div>
<div class="card p-2 mb-2">
    <form method="post">
        <h5 class="card-title mb-0">Data Rekam Medis</h5>
        <label>Diagnosis</label>
        <select name="diagnosis" required id="diagnosis_id" class="form-control mb-2">
            <option value="">Pilih Diagnosis</option>
            <option value="Diagnosis Baru">Diagnosis Baru</option>
            <option value="<?= $rmSingle['diagnosis'] ?>" selected><?= $rmSingle['diagnosis'] ?></option>
            <?php
            $getAllDiagnosis = $koneksi->query("SELECT * FROM rekam_medis GROUP BY diagnosis ORDER BY diagnosis ASC");
            foreach ($getAllDiagnosis as $allDiagnosis) {
            ?>
                <option value="<?= $allDiagnosis['diagnosis'] ?>"><?= $allDiagnosis['diagnosis'] ?></option>
            <?php } ?>
        </select>
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
        <label for="">Prognosis</label>
        <select name="prognosa" class="form-select mb-2">
            <option value="<?= $rmSingle['prognosa'] ?>" selected><?= $rmSingle['prognosa'] ?></option>
            <option value="Prognosis good">BONAM (BAIK)</option>
            <option value="Guarded prognosis">MALAM (BURUK/JELEK)</option>
            <option value="SANAM (SEMBUH)">SANAM (SEMBUH)</option>
            <option value="Fair prognosis">DUBIA (TIDAK TENTU/RAGU-RAGU)</option>
        </select>
        <label>ICD 10</label>
        <select class="form-select mb-2" style="height: 20px;" id="selUser" name="icd">
            <option value="<?= $rmSingle['icd'] ?>"><?= $rmSingle['icd'] ?></option>
            <?php
            $getIcd = $koneksi->query("SELECT * FROM icds");
            while ($icd = $getIcd->fetch_assoc()) {
            ?>
                <option value="<?php echo $icd['code']; ?>"> <?php echo $icd['code']; ?> - <?php echo $icd['name_en']; ?> </option>
            <?php } ?>
        </select>
        <label for="inputName5" class="">Status Perokok</label>
        <select name="status_perokok" id="" class="form-select mb-2">
            <option value="<?php echo $rmSingle['status_perokok'] ?>" hidden><?php echo $rmSingle['status_perokok'] ?></option>
            <option value="Aktif">Aktif</option>
            <option value="Pasif">Pasif</option>
        </select>
        <label for="inputName5" class="">Gol. Darah</label>
        <input type="text" class="form-control mb-2" id="inputName5" name="gol_darah" value="<?php echo $rmSingle['gol_darah'] ?>" placeholder="Masukkan Gol Darah Pasien">
        <label for="inputName5" class="">Keluhan Tambahan / Anamnesa</label>
        <input type="text" class="form-control mb-2" id="inputName5" name="anamnesa" value="<?php echo $rmSingle['anamnesa'] ?>" placeholder="Anamnesa">
        <label for="inputState" class="">Status Pulang</label>
        <select id="inputState" name="status_pulang" class="form-select">
            <option selected>Berobat Jalan</option>
            <option>Berobat Jalan</option>
            <option>Rawat Inap</option>
        </select>
        <center>
            <button class="btn btn-sm btn-primary mt-2" name="Update">Update</button>
        </center>
    </form>
</div>
<?php
if (isset($_POST['Update'])) {
    $diagnosis = htmlspecialchars($_POST['diagnosis']);
    $prognosa = htmlspecialchars($_POST['prognosa']);
    $icd = htmlspecialchars($_POST['icd']);
    $status_perokok = htmlspecialchars($_POST['status_perokok']);
    $gol_darah = htmlspecialchars($_POST['gol_darah']);
    $anamnesa = htmlspecialchars($_POST['anamnesa']);
    $status_pulang = htmlspecialchars($_POST['status_pulang']);

    if ($prognosa == 'Prognosis good') {
        $prognosacode = '170968001';
    } elseif ($prognosa == 'Guarded prognosis') {
        $prognosacode = '170969009';
    } elseif ($prognosa == 'Fair prognosis') {
        $prognosacode = '170970005';
    } else {
        $prognosacode = '170968001';
    }

    $koneksi->query("UPDATE rekam_medis SET diagnosis = '$diagnosis', prognosa = '$prognosa', icd = '$icd', status_perokok = '$status_perokok', gol_darah = '$gol_darah', anamnesa = '$anamnesa', status_plg = '$status_pulang' WHERE id_rm = '$rmSingle[id_rm]'");

    echo "
            <script>
                alert('Successfully');
                document.location.href='index.php?halaman=editrm&id=$rmSingle[id_rm]';
            </script>
        ";
}
?>


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
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="inputName5" class="form-label">Nama Obat</label><br>
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
                        <input type="hidden" name="idrm" value="<?php echo $rmSingle['norm'] ?>">
                        <?php
                        $registrasi = $koneksi->query("SELECT * FROM registrasi_rawat WHERE jadwal = '$rmSingle[jadwal]'")->fetch_assoc();
                        ?>
                        <input type="hidden" class="form-control" id="inputName5" name="id" value="<?php echo $registrasi['idrawat'] ?>" placeholder="Masukkan Nama Pasien">
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
<script type="text/javascript">
    $(document).ready(function() {
        $(".add-more").click(function() {
            var html = $(".copy").html();
            $(".after-add-more").after(html);
        });
        $("body").on("click", ".remove", function() {
            $(this).parents(".control-group").remove();
        });
    });
</script>
<?php
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
    $koneksi->query("UPDATE registrasi_rawat SET end='$end', kasir='$username' WHERE no_rm='$rmSingle[norm]' and jadwal = '$rmSingle[jadwal]';");
    $cekPemOb = $koneksi->query("SELECT * FROM registrasi_rawat WHERE no_rm='$rmSingle[norm]' and jadwal = '$rmSingle[jadwal]' limit 1")->fetch_assoc();

    if ($cekPemOb['carabayar'] == 'umum') {
        $koneksi->query("UPDATE biaya_rawat SET poli = '35000' WHERE idregis='$cekPemOb[idrawat]'");
    } elseif ($cekPemOb['carabayar'] == 'malam') {
        $koneksi->query("UPDATE biaya_rawat SET poli = '50000' WHERE idregis='$cekPemOb[idrawat]'");
    }

    for ($i = 0; $i < count($nama) - 1; $i++) {
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
        }
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
            tgl_pasien      = '" . date('Y-m-d', strtotime($rmSingle['jadwal'])) . "',
            rekam_medis_id = '$rmSingle[id_rm]',
            idrm      = '$rmSingle[norm]'
        ");
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
            document.location.href='index.php?halaman=editrm&inap&id=$rmSingle[id_rm]';
        </script>
      ";
    } else {
        echo "
        <script>
            document.location.href='index.php?halaman=editrm&id=$rmSingle[id_rm]';
        </script>
      ";
    }
}
?>
<!-- End Add Data Modal Obat -->

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
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="inputName5" class="form-label">Racik Ke-</label><br>
                                    <input type="number" name="racik" class="form-control w-100" style="width:100%;" aria-label="Default select example">
                                    <label for="inputName5" class="form-label">Nama Obat</label><br>
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
                        <input type="hidden" name="idrm" value="<?php echo $rmSingle['norm'] ?>">
                        <input type="hidden" class="form-control" id="inputName5" name="id" value="<?php echo $registrasi['idrawat'] ?>" placeholder="Masukkan Nama Pasien">
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
<script type="text/javascript">
    $(document).ready(function() {
        $(".add-more2").click(function() {
            var html = $(".copy2").html();
            $(".after-add-more2").after(html);
        });
        $("body").on("click", ".remove2", function() {
            $(this).parents(".control-group2").remove();
        });
    });
</script>
<?php
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
    $koneksi->query("UPDATE registrasi_rawat SET end='$end', kasir='$username' WHERE no_rm='$rmSingle[norm]' and jadwal = '$rmSingle[jadwal]';");
    $cekPemOb = $koneksi->query("SELECT * FROM registrasi_rawat WHERE no_rm='$rmSingle[norm]' and jadwal = '$rmSingle[jadwal]' limit 1")->fetch_assoc();

    if ($cekPemOb['carabayar'] == 'umum') {
        $koneksi->query("UPDATE biaya_rawat SET poli = '35000' WHERE idregis='$cekPemOb[idrawat]'");
    } elseif ($cekPemOb['carabayar'] == 'malam') {
        $koneksi->query("UPDATE biaya_rawat SET poli = '50000' WHERE idregis='$cekPemOb[idrawat]'");
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
                                        idrm      = '$rmSingle[norm]',
                                        tgl_pasien      = '" . date('Y-m-d', strtotime($rmSingle['jadwal'])) . "',
                                        rekam_medis_id = '$_GET[id]',
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
        document.location.href='index.php?halaman=editrm&inap&id=$rmSingle[id_rm]';
    </script>
  ";
    } else {
        echo "
    <script>
        document.location.href='index.php?halaman=editrm&id=$rmSingle[id_rm]';
    </script>
  ";
    }
}
?>
<!-- End Add Data Modal Obat Racik -->

<div class="card p-2 mb-2">
    <h5 class="card-title mb-0">Data Obat</h5>
    <p align="right">
        <span type="button" style="max-width: 150px;" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal45">Add Jadi <?= $rmSingle['id_rm'] ?></span><span type="button" style="max-width: 120px;" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal2">Add Racik</span>
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#obatTambahan">+ Obat Tambahan</button>
        <!-- Modal Obat Tambahan -->
        <script>
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
                                            <!-- <option value="">Pilih</option> -->
                                            <?php
                                            if (!isset($_GET['inap'])) {
                                                $getObat = $koneksi->query("SELECT * FROM apotek WHERE tipe = 'Rajal' GROUP BY nama_obat ORDER BY nama_obat ASC");
                                            } else {
                                                $getObat = $koneksi->query("SELECT * FROM apotek WHERE tipe = 'Ranap' GROUP BY nama_obat ORDER BY nama_obat ASC");
                                            }
                                            foreach ($getObat as $data) {
                                            ?>
                                                <option value="<?= $data['id_obat'] ?>"><?= $data['nama_obat'] ?></option>
                                            <?php } ?>
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
                                    <input type="hidden" name="rekam_medis_id" id="inputRekamMedisIdObatTambahan" value="<?= $_GET['id'] ?>">
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
    </div>
    </p>
    <div class="table-responsive">
        <table class="table table-bordered" style="font-size: 12px;">
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
                <?php $subtotal = 0; ?>
                <?php
                $getDataObat = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$rmSingle[norm]' AND rekam_medis_id = '$rmSingle[id_rm]'");
                ?>
                <?php foreach ($getDataObat as $obat) : ?>
                    <?php
                    // <!-- setting margin --> 
                    if (isset($_GET['inap'])) {
                        $ambil2 = $koneksi->query("SELECT * FROM apotek WHERE nama_obat='$obat[nama_obat]' AND tipe='Ranap' ");
                        $pecah2 = $ambil2->fetch_assoc();
                    } else {
                        $ambil2 = $koneksi->query("SELECT * FROM apotek WHERE nama_obat='$obat[nama_obat]' AND tipe='Rajal' ");
                        $pecah2 = $ambil2->fetch_assoc();
                    }

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
                        <td style="margin-top:10px;">
                            <!-- <button type="button" class="btn btn-sm btn-primary text-right" data-bs-toggle="modal" data-bs-target="#exampleModalEdit<?php echo $obat["idobat"]; ?>"><i class="bi bi-pencil"></i></button> -->
                            <?php if (isset($_GET['inap'])) { ?>
                                <a href="index.php?halaman=editrm&id=<?= htmlspecialchars($_GET['id']) ?>&hapus&inap=<?= $_GET['inap'] ?>" class="btn btn-sm btn-danger text-right"><i class="bi bi-trash"></i></a>
                            <?php } else { ?>
                                <a href="index.php?halaman=editrm&id=<?= htmlspecialchars($_GET['id']) ?>&hapus=<?= $obat['idobat'] ?>" class="btn btn-sm btn-danger text-right"><i class="bi bi-trash"></i></a>
                            <?php } ?>
                        </td>
                        <?php $subtotal += $subharga; ?>
                    </tr>
                    <?php $no += 1 ?>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
<?php
if (isset($_GET['hapus'])) {
    $koneksi->query("DELETE FROM obat_rm WHERE idobat = '" . htmlspecialchars($_GET['hapus']) . "'");
    echo "
            <script>
                alert('Successfully');
                document.location.href='index.php?halaman=editrm&id=$_GET[id]';
            </script>
        ";
}
?>

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
                    <?php
                    $pasienSingle = $koneksi->query("SELECT * FROM pasien WHERE TRIM(no_rm) = '$rmSingle[norm]' LIMIT 1")->fetch_assoc();
                    ?>
                    <input type="hidden" name="id_pasien" value="<?php echo $pasienSingle['idpasien'] ?>">
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
<?php
if (isset($_POST['savelay'])) {
    $layanan = $_POST['layanan'];
    $kode_layanan = $_POST['kode_layanan'];
    $jumlah_layanan = $_POST['jumlah_layanan'];
    $id_pasien = $_POST['id_pasien'];
    $idrm = $rmSingle['norm'];

    if ($_POST['layanan'] == 'lain-lain') {

        $koneksi->query("INSERT INTO layanan 
    
        (layanan, kode_layanan, jumlah_layanan, id_pasien, idrm, tgl_layanan)
    
        VALUES ('$_POST[layanan2]', '-', '$jumlah_layanan', '$id_pasien', '$rmSingle[norm]', '$rmSingle[jadwal]')
    
        ");
        $cekPemLay = $koneksi->query("SELECT * FROM registrasi_rawat WHERE no_rm='$rmSingle[norm]' and jadwal = '$rmSingle[jadwal]' limit 1")->fetch_assoc();
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
    
        (layanan, kode_layanan, jumlah_layanan, id_pasien, idrm, tgl_layanan)
    
        VALUES ('$layanan', '-', '$jumlah_layanan', '$id_pasien', '$rmSingle[norm]', '$rmSingle[jadwal]')
    
        ");
        $cekPemLay = $koneksi->query("SELECT * FROM registrasi_rawat WHERE no_rm='$rmSingle[norm]' and jadwal = '$rmSingle[jadwal]' limit 1")->fetch_assoc();
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
            document.location.href='index.php?halaman=editrm&inap&id=$rmSingle[id_rm]';
        </script>
      ";
    } else {
        echo "
        <script>
            document.location.href='index.php?halaman=editrm&id=$rmSingle[id_rm]';
        </script>
      ";
    }
}
?>
<!-- End Add Data Modal Tindakan -->
<div class="card p-2 mb-2">
    <h5 class="card-title">Plan</h5>
    <div align="right">
        <button type="button" class="btn btn-sm btn-primary text-right mb-2" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Plan</button>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered" style="font-size: 12px;">
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
                $getPlan = $koneksi->query("SELECT * FROM layanan WHERE idrm = '$rmSingle[norm]' AND DATE_FORMAT(tgl_layanan,'%Y-%m-%d') = '" . date('Y-m-d', strtotime($rmSingle['jadwal'])) . "'");
                ?>
                <?php foreach ($getPlan as $plan) : ?>
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