<?php if (!isset($_GET['api'])) { ?>
    <?php
    $getLastRM = $koneksi->query("SELECT  *, COUNT(*) AS jumm, MAX(id_rm) as id_rm FROM rekam_medis WHERE norm = '" . htmlspecialchars($_GET['id']) . "' AND DATE_FORMAT(jadwal, '%Y-%m-%d') <= '" . date('Y-m-d', strtotime($_GET['tgl'])) . "' ORDER BY id_rm DESC LIMIT 1")->fetch_assoc();
    ?>
    <div class="card shadow-sm mb-2 p-2">
        <h5><b>Entri Obat Jadi</b></h5>
        <div class="row">
            <div class="col-md-3">
                <label>Nama Obat</label>
                <select name="nama_obat" class=" form-control form-control-sm mb-2 w-100" style="width:100%;" id="selectObatJadiEntriObat" aria-label="Default select example">
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
            <div class="col-md-2">
                <label for="">Dosis</label>
                <div class="input-group input-group-sm ">
                    <input type="text" class="form-control form-control-sm mb-2" id="dosis1_obat" name="dosis1_obat">
                    <span type="text" style="text-align: center;" class="form-control form-control-sm mb-2" placeholder="X" disabled>X</span>
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
                <input type="number" name="jml_dokter" class="form-control form-control-sm mb-2" id="inputName5" placeholder="Jumlah Obat">
            </div>
            <div class="col-md-2">
                <label for="inputName5" class="">Petunjuk</label>
                <input type="text" name="petunjuk_obat" class="form-control form-control-sm mb-2" id="inputName5" placeholder=" Petunjuk Pemakaian">
                <input type="text" name="catatan_obat" value="-" hidden class="form-control form-control-sm mb-2" id="inputName5" placeholder="Masukkan Jumlah">
                <select name="jenis_obat" hidden class=" form-control form-control-sm mb-2">
                    <option value="Jadi">Jadi</option>
                </select>
            </div>
            <div class="col-md-1">
                <label for="inputCity" class="">Durasi</label>
                <div class="input-group mb-3">
                    <input type="text" name="durasi_obat" class="form-control form-control-sm" placeholder="Durasi" aria-describedby="basic-addon2">
                </div>
            </div>
            <div class="col-md-12 text-end">
                <button class="btn btn-sm btn-primary" name="addToSession">Tambah [+]</button>
            </div>
        </div>
        <?php
        // Proses tambah ke session
        if (isset($_POST['addToSession'])) {
            // Inisialisasi session jika belum ada
            if (!isset($_SESSION['temp_obat'])) {
                $_SESSION['temp_obat'] = array();
            }

            // Ambil data obat dari database untuk mendapatkan nama obat
            $id_obat = $_POST['nama_obat'];
            $query = $koneksi->query("SELECT * FROM apotek WHERE id_obat = '$id_obat'");
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
            echo "<script>window.location.href = 'index.php?halaman=rmedis&id=" . htmlspecialchars($_GET['id']) . "&tgl=" . htmlspecialchars($_GET['tgl']) . "&entriObat=" . htmlspecialchars($_GET['entriObat']) . "';</script>";
        }

        if (isset($_GET['saveToDatabase'])) {
            if (isset($_SESSION['temp_obat']) && count($_SESSION['temp_obat']) > 0) {
                foreach ($_SESSION['temp_obat'] as $obatSave) {
                    $koneksi->query("INSERT INTO obat_rm SET catatan_obat = '$obatSave[catatan]', kode_obat = '$obatSave[id_obat]', nama_obat = '$obatSave[nama_obat]', jml_dokter = '$obatSave[jumlah]', dosis1_obat = '$obatSave[dosis1_obat]', dosis2_obat = '$obatSave[dosis2_obat]', per_obat = '$obatSave[per]', durasi_obat = '$obatSave[durasi]', petunjuk_obat = '$obatSave[petunjuk]', jenis_obat = '$obatSave[jenis]', tgl_pasien = '$_GET[tgl]', rekam_medis_id = '$getLastRM[id_rm]', idrm = '$_GET[id]'");
                    // $koneksi->query("INSERT INTO rmedis_obat (id_rm, id_obat, dosis1_obat, dosis2_obat, per_obat, jumlah_obat, petunjuk_obat, catatan_obat, jenis_obat, durasi_obat) VALUES ('" . htmlspecialchars($_GET['id']) . "', '" . $obat['id_obat'] . "', '" . $obat['dosis1_obat'] . "', '" . $obat['dosis2_obat'] . "', '" . $obat['per'] . "', '" . $obat['jumlah'] . "', '" . $obat['petunjuk'] . "', '" . $obat['catatan'] . "', '" . $obat['jenis'] . "', '" . $obat['durasi'] . "')");
                }
                unset($_SESSION['temp_obat']);
                echo "<script>alert('Data berhasil disimpan ke database.'); window.location.href = 'index.php?halaman=rmedis&id=" . htmlspecialchars($_GET['id']) . "&tgl=" . htmlspecialchars($_GET['tgl']) . "';</script>";
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
                                    <td>
                                        <a href="index.php?halaman=<?= htmlspecialchars($_GET['halaman']) ?>&id=<?= htmlspecialchars($_GET['id']) ?>&tgl=<?= htmlspecialchars($_GET['tgl']) ?>&entriObat=<?= htmlspecialchars($_GET['entriObat']) ?>&hapusObatSession=<?= $index ?>" class="btn btn-danger btn-sm">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data obat</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <?php if (isset($_SESSION['temp_obat']) && count($_SESSION['temp_obat']) > 0): ?>
                    <div class="text-end mt-3">
                        <a href="index.php?halaman=<?= htmlspecialchars($_GET['halaman']) ?>&id=<?= htmlspecialchars($_GET['id']) ?>&tgl=<?= htmlspecialchars($_GET['tgl']) ?>&entriObat=<?= htmlspecialchars($_GET['entriObat']) ?>&saveToDatabase" class="btn btn-sm btn-success">Simpan ke Database</a>
                        <a href="index.php?halaman=<?= htmlspecialchars($_GET['halaman']) ?>&id=<?= htmlspecialchars($_GET['id']) ?>&tgl=<?= htmlspecialchars($_GET['tgl']) ?>&entriObat=<?= htmlspecialchars($_GET['entriObat']) ?>&clear_session=true" class="btn btn-sm btn-danger">Bersihkan Session</a>
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
<?php } else { ?>
    <?php

    ?>
<?php } ?>