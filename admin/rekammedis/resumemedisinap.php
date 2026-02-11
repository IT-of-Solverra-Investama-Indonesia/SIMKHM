<?php if (!isset($_GET['detailResume'])) { ?>
    <?php
    // Mode API - Jika tidak ada halaman, return JSON untuk API
    if (!isset($_GET['halaman']) && isset($_GET['api'])) {
        header('Content-Type: application/json');

        if ($_GET['api'] === 'get_icd') {
            include '../dist/function.php';
            $getICD = $koneksi->query("SELECT code, name_id FROM icds ORDER BY code ASC");
            $data = [];
            foreach ($getICD as $icd) {
                $data[] = [
                    'code' => $icd['code'],
                    'name' => $icd['name_id'],
                    'full' => $icd['code'] . ' ' . $icd['name_id']
                ];
            }
            echo json_encode($data);
            exit;
        }
    }
    ?>
    <?php
    $no_rm = htmlspecialchars($_GET['id']);
    $tgl_pasien = htmlspecialchars($_GET['tgl']);
    $idrawat = htmlspecialchars($_GET['idrawat']);

    $pasien = $koneksi->query("SELECT * FROM pasien WHERE no_rm='$no_rm'")->fetch_assoc();
    $registrasi = $koneksi->query("SELECT * FROM registrasi_rawat WHERE idrawat='$idrawat'")->fetch_assoc();
    $igd = $koneksi->query("SELECT * FROM igd WHERE no_rm='$no_rm' AND tgl_masuk='$tgl_pasien' ORDER BY idigd DESC")->fetch_assoc();

    $getPulangTerakhir = $koneksi->query("SELECT * FROM pulang WHERE norm='$no_rm' ORDER BY id DESC LIMIT 1")->fetch_assoc();

    $ctt = $koneksi->query("SELECT * FROM ctt_penyakit_inap WHERE norm='$no_rm' AND DATE_FORMAT(tgl, '%Y-%m-%d') > '" . ($getPulangTerakhir['tgl'] ?? date('Y-m-d', strtotime('20000-01-01'))) . "' order by id DESC LIMIT 1")->fetch_assoc();

    function getFullUrl()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ||
            $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

        return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    $lastResume = $koneksi->query("SELECT * FROM resumemedisinap WHERE idrawat='$idrawat' ORDER BY id DESC LIMIT 1")->fetch_assoc();

    if (isset($_POST['simpan'])) {
        $diagnosis_masuk = htmlspecialchars($_POST['diagnosis_masuk']);
        $anamnesis = htmlspecialchars($_POST['anamnesis']);
        $e = htmlspecialchars($_POST['e']);
        $v = htmlspecialchars($_POST['v']);
        $m = htmlspecialchars($_POST['m']);
        $td = htmlspecialchars($_POST['td']);
        $rr = htmlspecialchars($_POST['rr']);
        $nadi = htmlspecialchars($_POST['nadi']);
        $suhu = htmlspecialchars($_POST['suhu']);
        $gd = htmlspecialchars($_POST['gd']);
        $bb = htmlspecialchars($_POST['bb']);
        $tb = htmlspecialchars($_POST['tb']);
        $pemeriksaan_penunjang = htmlspecialchars($_POST['pemeriksaan_penunjang']);
        $diagnosa_akhir = htmlspecialchars($_POST['diagnosa_akhir']);
        $icd_diag_akhir = htmlspecialchars($_POST['icd_diag_akhir']);

        // Process diagnosa sekunder sebagai array
        $diagnosa_sekunder_arr = [];
        $icd_diag_sekunder_arr = [];
        if (isset($_POST['diagnosa_sekunder']) && is_array($_POST['diagnosa_sekunder'])) {
            foreach ($_POST['diagnosa_sekunder'] as $idx => $diag) {
                if (!empty(trim($diag))) {
                    $diagnosa_sekunder_arr[] = htmlspecialchars($diag);
                    $icd_diag_sekunder_arr[] = htmlspecialchars($_POST['icd_diag_sekunder'][$idx] ?? '');
                }
            }
        }
        $diagnosa_sekunder = json_encode($diagnosa_sekunder_arr);
        $icd_diag_sekunder = json_encode($icd_diag_sekunder_arr);

        $prognosis = htmlspecialchars($_POST['prognosis']);
        $intruksi = htmlspecialchars($_POST['intruksi']);
        $kondisi_keluar = htmlspecialchars($_POST['kondisi_keluar']);
        $tgl_kontrol = htmlspecialchars($_POST['tgl_kontrol']);

        $koneksi->query("INSERT INTO `resumemedisinap`(`idrawat`, `no_rm`, `nama_pasien`, `diagnosis_masuk`, `anamnesis`, `e`, `v`, `m`, `td`, `rr`, `nadi`, `suhu`, `gd`, `bb`, `tb`, `pemeriksaan_penunjang`, `diagnosa_akhir`, `icd_diag_akhir`, `diagnosa_sekunder`, `icd_diag_sekunder`, `prognosis`, `intruksi`, `kondisi_keluar`, `tgl_kontrol`) VALUES ('$idrawat','$no_rm','$pasien[nama_lengkap]','$diagnosis_masuk','$anamnesis','$e','$v','$m','$td','$rr','$nadi','$suhu','$gd','$bb','$tb','$pemeriksaan_penunjang','$diagnosa_akhir','$icd_diag_akhir','$diagnosa_sekunder','$icd_diag_sekunder','$prognosis','$intruksi','$kondisi_keluar','$tgl_kontrol')");

        echo "
                <script>
                    alert('Resume Medis Berhasil Disimpan');
                    window.location.href = '" . getFullUrl() . "';
                </script>
            ";
    }

    ?>
    <div class="card shadow p-2 mb-1">
        <h5 class="card-title my-0">
            Resume Medis Rawat Inap <br>
            <?php echo $pasien['nama_lengkap'] ?>(<b><?php echo $pasien['no_rm'] ?></b>) | TglLahir:
            <?php echo date("d-m-Y", strtotime($pasien['tgl_lahir'])) ?> | Alamat: <?php echo $pasien['alamat'] ?>
            <br> <?php if (!isset($_GET['igd'])) { ?>Kamar: <?php echo $registrasi['kamar'] ?> |<?php } ?> JK:
            <?php if ($pasien["jenis_kelamin"] == 1) {
                echo "Laki-Laki";
            } else {
                echo "Perempuan";
            } ?>
        </h5>
    </div>

    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        .diag-sekunder-row {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .diag-sekunder-row:hover {
            background-color: #e9ecef;
        }
    </style>

    <div class="card shadow p-2 mb-1">
        <form method="post">
            <div class="row g-1">
                <div class="col-6">
                    <label for="">Diagnosis Masuk</label>
                    <input type="text" name="diagnosis_masuk" value="<?= $lastResume['diagnosis_masuk'] ?? $igd['dkerja'] ?>" class="form-control form-control-sm">
                </div>
                <div class="col-6">
                    <label for="">Anamnesis</label>
                    <input type="text" name="anamnesis" value="<?= $lastResume['anamnesis'] ?? $igd['sub'] ?>" class="form-control form-control-sm">
                </div>
                <div class="col-12">
                    <label for="">Pemeriksaan Fisik</label>
                    <div class="row g-1">
                        <div class="col-md-4">
                            <label for="inputCity" class="">E</label>
                            <div class="input-group mb-10">
                                <input type="text" class="form-control form-control-sm" placeholder="E" name="e" aria-describedby="basic-addon2" value="<?php echo $lastResume['e'] ?? $igd['e'] ?>">
                                <!-- <span class="input-group-text" id="basic-addon2">celcius</span> -->
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="inputCity" class="">V</label>
                            <div class="input-group mb-10">
                                <input type="text" class="form-control form-control-sm" placeholder="V" name="v" aria-describedby="basic-addon2" value="<?php echo $lastResume['v'] ?? $igd['v'] ?>">
                                <!-- <span class="input-group-text" id="basic-addon2">%</span> -->
                            </div>
                        </div>

                        <div class="col-md-4">

                            <label for="inputCity" class="">M</label>
                            <div class="input-group mb-10">
                                <input type="text" class="form-control form-control-sm" placeholder="M" name="m" aria-describedby="basic-addon2" value="<?php echo $lastResume['m'] ?? $igd['m'] ?>">
                                <!-- <span class="input-group-text" id="basic-addon2">mmHg</span> -->
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="inputCity" class="" style="margin-top:10px;">Td</label>
                            <div class="input-group mb-10">
                                <input type="text" class="form-control form-control-sm" placeholder="Tekanan Darah" name="td" aria-describedby="basic-addon2" value="<?php echo $lastResume['td'] ?? $igd['td'] ?>">
                                <span class="input-group-text" id="basic-addon2">mmHg</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="inputCity" class="" style="margin-top:10px;">Rr</label>
                            <div class="input-group mb-10">
                                <input type="text" class="form-control form-control-sm" placeholder="Rr" name="rr" aria-describedby="basic-addon2" value="<?php echo $lastResume['rr'] ?? $igd['rr'] ?>">
                                <span class="input-group-text" id="basic-addon2">kali/menit</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="inputCity" class="" style="margin-top:10px;">Nadi</label>
                            <div class="input-group mb-10">
                                <input type="text" class="form-control form-control-sm" placeholder="Denyut Nadi" name="nadi" aria-describedby="basic-addon2" value="<?php echo $lastResume['nadi'] ?? $igd['n'] ?>">
                                <span class="input-group-text" id="basic-addon2">kali/menit</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="inputCity" class="" style="margin-top:10px;">Suhu Tubuh</label>
                            <div class="input-group mb-10">
                                <input type="text" class="form-control form-control-sm" placeholder="Suhu Tubuh" name="suhu" aria-describedby="basic-addon2" value="<?php echo $lastResume['suhu'] ?? $igd['s'] ?>">
                                <span class="input-group-text" id="basic-addon2">celcius</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="inputCity" class="" style="margin-top:10px;">GDA</label>
                            <div class="input-group mb-10">
                                <input type="text" class="form-control form-control-sm" placeholder="GDA" name="gd" aria-describedby="basic-addon2" value="<?php echo $lastResume['gd'] ?? $igd['gda'] ?>">
                                <!-- <span class="input-group-text" id="basic-addon2">celcius</span> -->
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="inputCity" class="" style="margin-top:10px;">BB</label>
                            <div class="input-group mb-10">
                                <input type="text" class="form-control form-control-sm" placeholder="BB" name="bb" aria-describedby="basic-addon2" value="<?php echo $lastResume['bb'] ?? $igd['bb'] ?>">
                                <span class="input-group-text" id="basic-addon2">Kg</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="inputCity" class="" style="margin-top:10px;">TB</label>
                            <div class="input-group mb-10">
                                <input type="text" class="form-control form-control-sm" placeholder="TB" name="tb" aria-describedby="basic-addon2" value="<?php echo $lastResume['tb'] ?? $igd['tb'] ?>">
                                <span class="input-group-text" id="basic-addon2">Cm</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <label for="">Pemeriksaan Penunjang</label>
                    <textarea name="pemeriksaan_penunjang" class="form-control form-control-sm" id=""><?= $lastResume['pemeriksaan_penunjang'] ?? '' ?></textarea>
                </div>
                <div class="col-6">
                    <label for="">Diagnosa Akhir</label>
                    <input type="text" name="diagnosa_akhir" value="<?= strip_tags($lastResume['diagnosa_akhir'] ?? ($getPulangTerakhir['diag_krs'] ?? ($ctt['assesment'] ?? ''))) ?>" class="form-control form-control-sm">
                </div>
                <div class="col-6">
                    <label for="">ICD10</label>
                    <select name="icd_diag_akhir" class="form-select form-select-sm" id="icdSelect">
                        <option value="">Loading...</option>
                    </select>
                </div>
                <div class="col-12">
                    <label for="">Diagnosa Sekunder</label>
                    <span type="button" class="badge bg-success text-light mb-2" id="addDiagSekunder">
                        <i class="bi bi-plus-circle"></i> Tambah
                    </span>
                    <div id="diagSekunderContainer">
                        <?php
                        // Parse data dari database
                        $diagSekunderArr = !empty($lastResume['diagnosa_sekunder']) ? json_decode($lastResume['diagnosa_sekunder'], true) : [];
                        $icdSekunderArr = !empty($lastResume['icd_diag_sekunder']) ? json_decode($lastResume['icd_diag_sekunder'], true) : [];

                        if (empty($diagSekunderArr)) {
                            // Tampilkan 1 baris kosong jika belum ada data
                            $diagSekunderArr = [''];
                            $icdSekunderArr = [''];
                        }

                        foreach ($diagSekunderArr as $idx => $diag) {
                        ?>
                            <div class="row g-2 mb-2 diag-sekunder-row">
                                <div class="col-5">
                                    <input type="text" name="diagnosa_sekunder[]" value="<?= htmlspecialchars($diag) ?>" class="form-control form-control-sm" placeholder="Diagnosa Sekunder">
                                </div>
                                <div class="col-6">
                                    <select name="icd_diag_sekunder[]" class="form-select form-select-sm icd-sekunder-select" data-selected="<?= htmlspecialchars($icdSekunderArr[$idx] ?? '') ?>">
                                        <option value="">Loading...</option>
                                    </select>
                                </div>
                                <div class="col-1">
                                    <button type="button" class="btn btn-sm btn-danger remove-diag-sekunder">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-12">
                    <label for="">Terapi</label>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" style="font-size: 12px;">
                            <thead>
                                <tr>
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
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $getLatLpo = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$no_rm' AND tgl_pasien='$tgl_pasien' GROUP BY date_format(created_at, '%Y-%m-%d') ORDER BY idobat DESC LIMIT 1")->fetch_assoc();
                                if (!isset($_GET['tglobat'])) {
                                    $tgl = date('Y-m-d', strtotime($getLatLpo['created_at']));
                                } else {
                                    $tgl = $_GET['tglobat'];
                                }
                                $whereTgl = " AND date_format(created_at, '%Y-%m-%d') = '$tgl'";
                                $injek = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$no_rm' AND tgl_pasien='$tgl_pasien'" . $whereTgl);
                                $urlBase = "index.php?halaman=cttpenyakit&id=" . htmlspecialchars($no_rm) . "&inap&tgl=" . htmlspecialchars($tgl_pasien);
                                $noo = 1;
                                foreach ($injek as $in) {
                                ?>
                                    <tr>
                                        <td class="text-light <?= $in['see_apotek_at'] == null ? 'bg-danger' : 'bg-success' ?>"><?php echo $noo++; ?></td>
                                        <td><?php echo $in["nama_obat"]; ?> <br> <span style="font-size: 10px;">(<?= $in['obat_igd'] ?>)</span></td>
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
                                                href="../apotek/lpo_print_obat.php?id=<?= htmlspecialchars($no_rm) ?>&inap&tgl=<?= htmlspecialchars($tgl_pasien) ?>&tglObat=<?php echo date('Y-m-d', strtotime($in["created_at"])) ?>&jenis=<?= $in['obat_igd'] ?>"
                                                class="badge bg-warning text-dark" style="font-size: 12px;">
                                                <?php echo date('Y-m-d', strtotime($in["created_at"])) ?>
                                            </a>
                                        </td>
                                        <td><?= $in['petugas'] ?></td>
                                        <!-- <td> <button type="button" class="btn btn-primary text-right" data-bs-toggle="modal" data-bs-target="#exampleModalEdit<?php echo $in["idobat"]; ?>">Edit</button></td> -->
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-12">
                    <label for="">Prognosis</label>
                    <textarea name="prognosis" class="form-control form-control-sm"><?= $lastResume['prognosis'] ?? '' ?></textarea>
                </div>
                <div class="col-12">
                    <label for="">Pengobatan Dirumah</label>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" style="font-size: 12px;">
                            <thead>
                                <tr>
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
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $getLatLpo = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$no_rm' AND tgl_pasien='$tgl_pasien' GROUP BY date_format(created_at, '%Y-%m-%d') ORDER BY idobat DESC LIMIT 1")->fetch_assoc();
                                if (!isset($_GET['tglobat'])) {
                                    $tgl = date('Y-m-d', strtotime($getLatLpo['created_at']));
                                } else {
                                    $tgl = $_GET['tglobat'];
                                }
                                $whereTgl = " AND date_format(created_at, '%Y-%m-%d') = '$tgl'";
                                $injek = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$no_rm' AND tgl_pasien='$tgl_pasien' AND obat_igd = 'oral'" . $whereTgl);
                                $urlBase = "index.php?halaman=cttpenyakit&id=" . htmlspecialchars($no_rm) . "&inap&tgl=" . htmlspecialchars($tgl_pasien);
                                $noo = 1;
                                foreach ($injek as $in) {
                                ?>
                                    <tr>
                                        <td class="text-light <?= $in['see_apotek_at'] == null ? 'bg-danger' : 'bg-success' ?>"><?php echo $noo++; ?></td>
                                        <td><?php echo $in["nama_obat"]; ?> <br> <span style="font-size: 10px;">(<?= $in['obat_igd'] ?>)</span></td>
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
                                                href="../apotek/lpo_print_obat.php?id=<?= htmlspecialchars($no_rm) ?>&inap&tgl=<?= htmlspecialchars($tgl_pasien) ?>&tglObat=<?php echo date('Y-m-d', strtotime($in["created_at"])) ?>&jenis=<?= $in['obat_igd'] ?>"
                                                class="badge bg-warning text-dark" style="font-size: 12px;">
                                                <?php echo date('Y-m-d', strtotime($in["created_at"])) ?>
                                            </a>
                                        </td>
                                        <td><?= $in['petugas'] ?></td>
                                        <!-- <td> <button type="button" class="btn btn-primary text-right" data-bs-toggle="modal" data-bs-target="#exampleModalEdit<?php echo $in["idobat"]; ?>">Edit</button></td> -->
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-12">
                    <label for="">Intruksi/Edukasi</label>
                    <textarea name="intruksi" id="" class="form-control form-control-sm"><?= $lastResume['intruksi'] ?? '' ?></textarea>
                </div>
                <div class="col-6">
                    <label for="">Kondisi Saat Pulang</label>
                    <select name="kondisi_keluar" class="form-select form-select-sm" id="">
                        <option <?= ($lastResume['kondisi_keluar'] ?? '') == 'Sembuh' ? 'selected' : '' ?> value="Sembuh">Sembuh</option>
                        <option <?= ($lastResume['kondisi_keluar'] ?? '') == 'Berobat Jalan' ? 'selected' : '' ?> value="Berobat Jalan">Berobat Jalan</option>
                        <option <?= ($lastResume['kondisi_keluar'] ?? '') == 'Rujuk RS' ? 'selected' : '' ?> value="Rujuk RS">Rujuk RS</option>
                        <option <?= ($lastResume['kondisi_keluar'] ?? '') == 'Pulang Paksa' ? 'selected' : '' ?> value="Pulang Paksa">Pulang Paksa</option>
                        <option <?= ($lastResume['kondisi_keluar'] ?? '') == 'Meninggal' ? 'selected' : '' ?> value="Meninggal">Meninggal</option>
                    </select>
                </div>
                <div class="col-6">
                    <label for="">Tanggal Kontrol</label>
                    <input type="date" name="tgl_kontrol" class="form-control form-control-sm" value="<?= $lastResume['tgl_kontrol'] ?? date('Y-m-d', strtotime('+3 days')) ?>" id="">
                </div>
                <div class="col-12 text-end">
                    <br>
                    <button type="submit" name="simpan" class="btn btn-sm btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Load ICD10 data dari API secara asynchronous dengan Select2
        let icdDataGlobal = [];

        $(document).ready(async function() {
            try {
                const response = await fetch('../rekammedis/resumemedisinap.php?api=get_icd');
                icdDataGlobal = await response.json();

                // Initialize ICD Diagnosa Akhir
                initializeIcdSelect('#icdSelect', '<?= $lastResume['icd_diag_akhir'] ?? '' ?>');

                // Initialize semua ICD Diagnosa Sekunder yang sudah ada
                $('.icd-sekunder-select').each(function() {
                    const selectedValue = $(this).data('selected');
                    initializeIcdSelect(this, selectedValue);
                });

            } catch (error) {
                console.error('Error loading ICD data:', error);
            }
        });

        // Fungsi untuk initialize select ICD dengan Select2
        function initializeIcdSelect(selector, selectedValue = '') {
            const $select = $(selector);

            // Clear dan populate
            $select.empty();
            $select.append('<option value="">Pilih ICD10</option>');

            icdDataGlobal.forEach(icd => {
                const option = new Option(`${icd.code} - ${icd.name}`, icd.full, false, false);
                if (selectedValue && selectedValue === icd.full) {
                    option.selected = true;
                }
                $select.append(option);
            });

            // Initialize Select2
            $select.select2({
                placeholder: 'Pilih ICD10',
                allowClear: true,
                width: '100%'
            });

            // Set value if exists
            if (selectedValue) {
                $select.val(selectedValue).trigger('change');
            }
        }

        // Tambah Diagnosa Sekunder
        $('#addDiagSekunder').on('click', function() {
            const newRow = `
                <div class="row g-2 mb-2 diag-sekunder-row">
                    <div class="col-5">
                        <input type="text" name="diagnosa_sekunder[]" class="form-control form-control-sm" placeholder="Diagnosa Sekunder">
                    </div>
                    <div class="col-6">
                        <select name="icd_diag_sekunder[]" class="form-select form-select-sm icd-sekunder-select">
                            <option value="">Pilih ICD10</option>
                        </select>
                    </div>
                    <div class="col-1">
                        <button type="button" class="btn btn-sm btn-danger remove-diag-sekunder">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `;

            $('#diagSekunderContainer').append(newRow);

            // Initialize Select2 untuk row baru
            const $newSelect = $('#diagSekunderContainer .diag-sekunder-row:last-child .icd-sekunder-select');
            initializeIcdSelect($newSelect, '');
        });

        // Remove Diagnosa Sekunder
        $(document).on('click', '.remove-diag-sekunder', function() {
            if ($('.diag-sekunder-row').length > 1) {
                $(this).closest('.diag-sekunder-row').remove();
            } else {
                alert('Minimal harus ada 1 diagnosa sekunder');
            }
        });
    </script>

    <div class="card shadow p-2">
        <h5 class="card-title">Riwayat Resume Medis</h5>
        <div class="table-responsive">
            <table class="table table-sm table-hover table-striped" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Kondisi Keluar</th>
                        <th>Tanggal Kontrol</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $riwayatResume = $koneksi->query("SELECT * FROM resumemedisinap WHERE idrawat='$idrawat' ORDER BY id DESC");
                    foreach ($riwayatResume as $in) { ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $in['tgl_kontrol'] ?></td>
                            <td><?= $in['kondisi_keluar'] ?></td>
                            <td><?= $in['tgl_kontrol'] ?></td>
                            <td>
                                <a href="../rekammedis/resumemedisinap.php?detailResume=<?= $in['id'] ?>" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i></a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
<?php } else { ?>
    <?php
    include '../dist/function.php';
    $resume = $koneksi->query("SELECT * FROM resumemedisinap WHERE id='" . htmlspecialchars($_GET['detailResume']) . "'")->fetch_assoc();
    $registrasi = $koneksi->query("SELECT * FROM registrasi_rawat WHERE idrawat='" . $resume['idrawat'] . "'")->fetch_assoc();
    $pasien = $koneksi->query("SELECT * FROM pasien WHERE no_rm='" . $resume['no_rm'] . "'")->fetch_assoc();

    $tgl_pasien = date('Y-m-d', strtotime(datetime: $registrasi['jadwal']));
    $no_rm = $resume['no_rm'];

    $getPulangTerakhir = $koneksi->query("SELECT * FROM pulang WHERE norm='$no_rm' ORDER BY id DESC LIMIT 1")->fetch_assoc();
    $ctt = $koneksi->query("SELECT * FROM ctt_penyakit_inap WHERE norm='$no_rm' AND DATE_FORMAT(tgl, '%Y-%m-%d') > '" . ($getPulangTerakhir['tgl'] ?? date('Y-m-d', strtotime('20000-01-01'))) . "' order by id DESC LIMIT 1")->fetch_assoc();

    ?>
    <!doctype html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Resume Medis Inap</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
        <style>
            .table-base {
                border: 1px solid #dee2e6;
            }

            .table-base>div {
                border: 1px solid #dee2e6;
                padding: 8px;
                min-height: 40px;
            }

            .table-base>div:not(:last-child) {
                border-bottom: 1px solid #dee2e6;
            }
        </style>
    </head>

    <body class="container-fluid">
        <?php
        $currentURL = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] : '';
        $currentURL = strtolower($currentURL);

        if (strpos($currentURL, 'wonorejo') !== false) {
            $alamat = "Jalan Raya Wonorejo No. 167 Kedungjajang, Lumajang";
            $nomor = "0822-3388-0001";
            $email = "husada.mulia@gmail.com";
            $namaInstansi = "Wonorejo";
        } elseif (strpos($currentURL, 'klakah') !== false) {
            $alamat = "Jl. Raya Mlawang - Klakah Lumajang 67356";
            $nomor = "0812-3457-1010";
            $email = "husadamuliaklakah@gmail.com";
            $namaInstansi = "Tunjung";
        } elseif (strpos($currentURL, 'tunjung') !== false) {
            $alamat = "Krajani Satu, Tunjung, Kec. Randuagung, Kabupaten Lumajang, Jawa Timur";
            $nomor = "0813-5555-0275";
            $email = "husadamuliatunjung@gmail.com";
            $namaInstansi = "Tunjung";
        } else {
            $alamat = "Dsn. Sumber Eling RT.013 RW.003, Ds. Kunir Lor, Kec.Kunir, Kab. Lumajang";
            $nomor = "0822-3388-0001";
            $email = "husadamuliakunir@gmail.com";
            $namaInstansi = "Kunir";
        }
        ?>
        <div class="row g-1 table-base">
            <div class="col-6">
                <div class="row g-1">
                    <div class="col-6 d-flex align-items-center justify-content-center">
                        <img src="https://simkhm.id/klakah/admin/dist/assets/img/khm.png" style="max-width: 80%;" alt="">
                    </div>
                    <div class="col-6 d-flex flex-column justify-content-center" style="word-wrap: break-word; overflow-wrap: break-word;">
                        <?= $alamat ?><br>
                        Telp. <?= $nomor ?> <br>
                        Email: <br><?= $email ?>
                    </div>
                </div>
            </div>
            <div class="col-6">
                NO. RM : <?= $no_rm ?><br>
                Nama Pasien : <?= $pasien['nama_lengkap'] ?><br>
                Tanggal Lahir : <?= $pasien['tgl_lahir'] ?> (<?= $pasien['jenis_kelamin'] == '2' ? 'P' : 'L' ?>)<br>
                Alamat : <?= $pasien['alamat'] ?><br>
                Ruangan : <?= $registrasi['kamar'] ?>
            </div>
        </div>
        <br>
        <center>
            <h5><b>Resume Medis Rawat Inap</b></h5>
        </center>
        <div class="row g-1 table-base">
            <div class="col-4">
                Tgl Masuk : <?= $registrasi['jadwal'] ?>
            </div>
            <div class="col-4">
                Tgl Keluar : <?= $ctt['tgl'] ?>
            </div>
            <div class="col-4">
                Ruang : <?= $registrasi['kamar'] ?>
            </div>
            <div class="col-6">
                Dokter : <?= $ctt['dokter'] ?>
            </div>
            <div class="col-6">
                Diagnosis Masuk : <?= $resume['diagnosis_masuk'] ?>
            </div>
            <div class="col-12">
                Anamnesis : <?= $resume['anamnesis'] ?>
            </div>
            <div class="col-12">
                Pemeriksaan Fisik : <br>
                E: <?= $resume['e'] ?>, V: <?= $resume['v'] ?>, M: <?= $resume['m'] ?> <br>
                Td: <?= $resume['td'] ?> mmHg, Rr: <?= $resume['rr'] ?> kali/menit, Nadi: <?= $resume['nadi'] ?> kali/menit, Suhu:
                <?= $resume['suhu'] ?> celcius, GDA: <?= $resume['gd'] ?>, BB: <?= $resume['bb'] ?> Kg, TB: <?= $resume['tb'] ?> Cm
            </div>
            <div class="col-12">
                Pemeriksaan Penunjang :
                <br>
                <?= nl2br($resume['pemeriksaan_penunjang']) ?>
            </div>
            <div class="col-9">
                Diagnosa Akhir <br>
                Diagnosa Utama : <?= $resume['diagnosa_akhir'] ?>
            </div>
            <div class="col-3">
                ICD10 : <br> <?= $resume['icd_diag_akhir'] ?>
            </div>
            <div class="col-9">
                Diagnosa Sekunder :
                <br>
                <?php
                $diagSekunderArr = !empty($resume['diagnosa_sekunder']) ? json_decode($resume['diagnosa_sekunder'], true) : [];
                foreach ($diagSekunderArr as $diag) {
                    echo "- " . htmlspecialchars($diag) . "<br>";
                }
                ?>
            </div>
            <div class="col-3">
                ICD10 : <br>
                <?php
                $icdSekunderArr = !empty($resume['icd_diag_sekunder']) ? json_decode($resume['icd_diag_sekunder'], true) : [];
                foreach ($icdSekunderArr as $icd) {
                    echo "- " . htmlspecialchars($icd) . "<br>";
                }
                ?>
            </div>
            <div class="col-12">
                Terapi :
                <br>
                <div class="table-responsive">
                    <table class="table table-hover table-striped" style="font-size: 12px;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Obat</th>
                                <th>Kode Obat</th>
                                <th>Jumlah</th>
                                <!-- <th>Harga</th> -->
                                <!-- <th>Sub</th> -->
                                <th>Dosis</th>
                                <th>Jenis</th>
                                <th>Durasi</th>
                                <th>Tanggal</th>
                                <th>Petugas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $getLatLpo = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$no_rm' AND tgl_pasien='$tgl_pasien' GROUP BY date_format(created_at, '%Y-%m-%d') ORDER BY idobat DESC LIMIT 1")->fetch_assoc();
                            if (!isset($_GET['tglobat'])) {
                                $tgl = date('Y-m-d', strtotime($getLatLpo['created_at']));
                            } else {
                                $tgl = $_GET['tglobat'];
                            }
                            $whereTgl = " AND date_format(created_at, '%Y-%m-%d') = '$tgl'";
                            $injek = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$no_rm' AND tgl_pasien='$tgl_pasien'" . $whereTgl);
                            $urlBase = "index.php?halaman=cttpenyakit&id=" . htmlspecialchars($no_rm) . "&inap&tgl=" . htmlspecialchars($tgl_pasien);
                            $noo = 1;
                            foreach ($injek as $in) {
                            ?>
                                <tr>
                                    <td><?php echo $noo++; ?></td>
                                    <td><?php echo $in["nama_obat"]; ?> <br> <span style="font-size: 10px;">(<?= $in['obat_igd'] ?>)</span></td>
                                    <td><?php echo $in["kode_obat"]; ?></td>
                                    <td><?php echo $in["jml_dokter"]; ?></td>
                                    <!-- <td>
                                        <?php
                                        $getPriceInDate = $koneksi->query("SELECT * FROM apotek WHERE tgl_beli <= '" . date('Y-m-d', strtotime($in['created_at'])) . "' AND nama_obat = '$in[nama_obat]' ORDER BY tgl_beli DESC LIMIT 1")->fetch_assoc();
                                        ?>
                                        Rp
                                        <?= number_format($harga = $getPriceInDate['harga_beli'] * ($getPriceInDate['margininap'] / 100), 0, 0, '.') ?>
                                    </td>
                                    <td>
                                        Rp <?= number_format($harga * $in['jml_dokter'], 0, 0, '.') ?>
                                    </td> -->
                                    <td><?php echo $in["dosis1_obat"]; ?> X <?php echo $in["dosis2_obat"]; ?>
                                        <?php echo $in["per_obat"]; ?>
                                    </td>
                                    <td><?php echo $in["jenis_obat"]; ?> <?php echo $in["racik"]; ?></td>
                                    <td><?php echo $in["durasi_obat"]; ?> hari</td>
                                    <td>
                                        <?php echo date('Y-m-d', strtotime($in["created_at"])) ?>
                                    </td>
                                    <td><?= $in['petugas'] ?></td>
                                    <!-- <td> <button type="button" class="btn btn-primary text-right" data-bs-toggle="modal" data-bs-target="#exampleModalEdit<?php echo $in["idobat"]; ?>">Edit</button></td> -->
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-12">
                Prognosis :
                <br>
                <?= nl2br($resume['prognosis']) ?>
            </div>
            <div class="col-12">
                Pengobat dirumah :
                <br>
                <div class="table-responsive">
                    <table class="table table-hover table-striped" style="font-size: 12px;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Obat</th>
                                <th>Kode Obat</th>
                                <th>Jumlah</th>
                                <!-- <th>Harga</th>
                                <th>Sub</th> -->
                                <th>Dosis</th>
                                <th>Jenis</th>
                                <th>Durasi</th>
                                <th>Tanggal</th>
                                <th>Petugas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $getLatLpo = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$no_rm' AND tgl_pasien='$tgl_pasien' GROUP BY date_format(created_at, '%Y-%m-%d') ORDER BY idobat DESC LIMIT 1")->fetch_assoc();
                            if (!isset($_GET['tglobat'])) {
                                $tgl = date('Y-m-d', strtotime($getLatLpo['created_at']));
                            } else {
                                $tgl = $_GET['tglobat'];
                            }
                            $whereTgl = " AND date_format(created_at, '%Y-%m-%d') = '$tgl'";
                            $injek = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$no_rm' AND tgl_pasien='$tgl_pasien' AND obat_igd = 'oral'" . $whereTgl);
                            $urlBase = "index.php?halaman=cttpenyakit&id=" . htmlspecialchars($no_rm) . "&inap&tgl=" . htmlspecialchars($tgl_pasien);
                            $noo = 1;
                            foreach ($injek as $in) {
                            ?>
                                <tr>
                                    <td><?php echo $noo++; ?></td>
                                    <td><?php echo $in["nama_obat"]; ?> <br> <span style="font-size: 10px;">(<?= $in['obat_igd'] ?>)</span></td>
                                    <td><?php echo $in["kode_obat"]; ?></td>
                                    <td><?php echo $in["jml_dokter"]; ?></td>
                                    <!-- <td>
                                        <?php
                                        $getPriceInDate = $koneksi->query("SELECT * FROM apotek WHERE tgl_beli <= '" . date('Y-m-d', strtotime($in['created_at'])) . "' AND nama_obat = '$in[nama_obat]' ORDER BY tgl_beli DESC LIMIT 1")->fetch_assoc();
                                        ?>
                                        Rp
                                        <?= number_format($harga = $getPriceInDate['harga_beli'] * ($getPriceInDate['margininap'] / 100), 0, 0, '.') ?>
                                    </td>
                                    <td>
                                        Rp <?= number_format($harga * $in['jml_dokter'], 0, 0, '.') ?>
                                    </td> -->
                                    <td><?php echo $in["dosis1_obat"]; ?> X <?php echo $in["dosis2_obat"]; ?>
                                        <?php echo $in["per_obat"]; ?>
                                    </td>
                                    <td><?php echo $in["jenis_obat"]; ?> <?php echo $in["racik"]; ?></td>
                                    <td><?php echo $in["durasi_obat"]; ?> hari</td>
                                    <td>
                                        <!-- <a target="_blank"
                                            href="../apotek/lpo_print_obat.php?id=<?= htmlspecialchars($no_rm) ?>&inap&tgl=<?= htmlspecialchars($tgl_pasien) ?>&tglObat=<?php echo date('Y-m-d', strtotime($in["created_at"])) ?>&jenis=<?= $in['obat_igd'] ?>"
                                            class="badge bg-warning text-dark" style="font-size: 12px;">
                                        </a> -->
                                        <?php echo date('Y-m-d', strtotime($in["created_at"])) ?>
                                    </td>
                                    <td><?= $in['petugas'] ?></td>
                                    <!-- <td> <button type="button" class="btn btn-primary text-right" data-bs-toggle="modal" data-bs-target="#exampleModalEdit<?php echo $in["idobat"]; ?>">Edit</button></td> -->
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-12">
                Intruksi/Edukasi/Anjuran :
                <br>
                <?= nl2br($resume['intruksi']) ?>
            </div>
            <div class="col-12">
                Kondisi Saat Pulang : <?= nl2br($resume['kondisi_keluar']) ?>
            </div>
            <div class="col-12">
                Tanggal Kontrol : <?= nl2br($resume['tgl_kontrol']) ?>
            </div>
        </div>
        <br>
        <div class="row g-1">
            <div class="col-6">
                <center>
                    <br>
                    Pasien<br><br><br>
                    (<?= $pasien['nama_lengkap'] ?>)
                </center>
            </div>
            <div class="col-6">
                <center>
                    Lumajang, <?= date('d-m-Y', strtotime($resume['created_at'])) ?> <br>
                    Dokter Penanggung Jawab <br><br><br>
                    (<?= $ctt['dokter'] ?>)
                </center>
            </div>
        </div>
        <br><br>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
    </body>

    </html>
<?php } ?>