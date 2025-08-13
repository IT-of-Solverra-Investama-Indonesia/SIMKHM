<div class="">
    <?php
    $date = date("Y-m-d");
    date_default_timezone_set('Asia/Jakarta');
    $petugas = $_SESSION['admin']['namalengkap'];
    $pasien = $koneksi->query("SELECT * FROM pasien  WHERE no_rm='$_GET[id]';")->fetch_assoc();
    if (!isset($_GET['igd'])) {
        $jadwal = $koneksi->query("SELECT * FROM registrasi_rawat  WHERE no_rm='$_GET[id]' AND date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' AND perawatan = 'Rawat Inap' ORDER BY idrawat DESC LIMIT 1")->fetch_assoc();
    }

    $id = $koneksi->query("SELECT * FROM registrasi_rawat  WHERE no_rm='$_GET[id]' and date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' AND perawatan = 'Rawat Inap' ORDER BY idrawat DESC LIMIT 1")->fetch_assoc();

    $ConditionCopy = 0;

    if (isset($_GET['idlpo'])) {
        $ConditionCopy = htmlspecialchars($_GET['idlpo']);
        $dataCopy = $koneksi->query("SELECT * FROM lpo WHERE id_lpo = '$ConditionCopy'")->fetch_assoc();
    }

    function getFullUrl()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ||
            $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

        return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }
    function getUniqeIdObat($koneksi)
    {
        $newId = $koneksi->query("SELECT * FROM retur_obat_inap ORDER BY idretur DESC LIMIT 1")->fetch_assoc()['idobat'] + 1;
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
    ?>
    <h5 class="card-title">Retur Obat Inap <?= $pasien['nama_lengkap'] ?> (<?= $pasien['no_rm'] ?>)</h5>
    <div class="col-md-12">
        <div class="card shadow p-2 mb-1">
            <label for="">Obat Injeksi </label>
            <div>
            </div>
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
                            <th>Act</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($_GET['igd'])) {
                            $injek = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND idigd='$_GET[idigd]' AND obat_igd = 'injeksi'");
                            $urlBase = "index.php?halaman=lpo&igd&id=" . htmlspecialchars($_GET['id']) . "&idigd=" . htmlspecialchars($_GET['idigd']) . "&tgl=" . htmlspecialchars($_GET['tgl']);
                        } else {
                            $urlBase = "index.php?halaman=lpo&id=" . htmlspecialchars($_GET['id']) . "&inap&tgl=" . htmlspecialchars($_GET['tgl']);
                            $injek = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND tgl_pasien='$_GET[tgl]' AND obat_igd = 'injeksi'");
                        }
                        $noo = 1;
                        foreach ($injek as $in) {
                        ?>
                            <tr>
                                <td><?php echo $noo++; ?></td>
                                <td><?php echo $in["nama_obat"]; ?></td>
                                <td><?php echo $in["kode_obat"]; ?></td>
                                <td><?php echo $in["jml_dokter"]; ?></td>
                                <td>
                                    <?php
                                    $getPriceInDate = $koneksi->query("SELECT * FROM apotek WHERE tgl_beli <= '" . date('Y-m-d', strtotime($in['created_at'])) . "' AND nama_obat = '$in[nama_obat]' ORDER BY tgl_beli DESC LIMIT 1")->fetch_assoc();
                                    ?>
                                    Rp <?= number_format($harga = $getPriceInDate['harga_beli'] * ($getPriceInDate['margininap'] / 100), 0, 0, '.') ?>
                                </td>
                                <td>
                                    Rp <?= number_format($harga * $in['jml_dokter'], 0, 0, '.') ?>
                                </td>
                                <td><?php echo $in["dosis1_obat"]; ?> X <?php echo $in["dosis2_obat"]; ?> <?php echo $in["per_obat"]; ?></td>
                                <td><?php echo $in["jenis_obat"]; ?> <?php echo $in["racik"]; ?></td>
                                <td><?php echo $in["durasi_obat"]; ?> hari</td>
                                <td><?php echo date('Y-m-d', strtotime($in["created_at"])) ?></td>
                                <td><?= $in['petugas'] ?></td>
                                <!-- <td> <button type="button" class="btn btn-primary text-right" data-bs-toggle="modal" data-bs-target="#exampleModalEdit<?php echo $in["idobat"]; ?>">Edit</button></td> -->
                                <td>
                                    <button class="btn btn-sm btn-warning" onclick="upData('<?= $in['idobat'] ?>','<?= $in['nama_obat'] ?>','<?= $in['kode_obat'] ?>','<?= $in['jenis_obat'] ?>', '<?= number_format($harga, 0, 0, '') ?>')" data-bs-toggle="modal" data-bs-target="#AddRetur"><i class="bi bi-capsule-pill"></i></button>
                                    <?php if ($_SESSION['admin']['level'] == 'sup') { ?>
                                        <a href="<?= $urlBase ?>&idObat=<?= $in['idobat'] ?>" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
                                    <?php } else { ?>
                                        <!-- <span style="font-size: 6.5px;">Kesalahan Silahkan Lapor Wadir</span> -->
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card shadow p-2 mb-1">
            <label for="">Obat Oral</label>
            <div align="left">
            </div>
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
                            <th>Act</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($_GET['igd'])) {
                            $oral = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND idigd='$_GET[idigd]' AND obat_igd = 'oral'");
                        } else {
                            $oral = $koneksi->query("SELECT * FROM obat_rm  WHERE idrm = '$_GET[id]' AND tgl_pasien='$_GET[tgl]' AND obat_igd = 'oral'");
                        }
                        $no = 1;
                        foreach ($oral as $or) {
                        ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $or["nama_obat"]; ?></td>
                                <td><?php echo $or["kode_obat"]; ?></td>
                                <td><?php echo $or["jml_dokter"]; ?></td>
                                <td>
                                    <?php
                                    $getPriceInDate = $koneksi->query("SELECT * FROM apotek WHERE tgl_beli <= '" . date('Y-m-d', strtotime($or['created_at'])) . "' AND id_obat = '$or[kode_obat]' ORDER BY tgl_beli DESC LIMIT 1")->fetch_assoc();
                                    ?>
                                    Rp <?= number_format($harga = $getPriceInDate['harga_beli'] * ($getPriceInDate['margininap'] / 100), 0, 0, '.') ?>
                                </td>
                                <td>
                                    Rp <?= number_format($harga * $or['jml_dokter'], 0, 0, '.') ?>
                                </td>
                                <td><?php echo $or["dosis1_obat"]; ?> X <?php echo $or["dosis2_obat"]; ?> <?php echo $or["per_obat"]; ?></td>
                                <td><?php echo $or["jenis_obat"]; ?> <?php echo $or["racik"]; ?></td>
                                <td><?php echo $or["durasi_obat"]; ?> hari</td>
                                <td><?php echo date('Y-m-d', strtotime($or["created_at"])) ?></td>
                                <td><?= $or['petugas'] ?></td>
                                <!-- <td> <button type="button" class="btn btn-primary text-right" data-bs-toggle="modal" data-bs-target="#exampleModalEdit<?php echo $or["idobat"]; ?>">Edit</button></td> -->
                                <td>
                                    <button class="btn btn-sm btn-warning" onclick="upData('<?= $or['idobat'] ?>','<?= $or['nama_obat'] ?>','<?= $or['kode_obat'] ?>','<?= $or['jenis_obat'] ?>', '<?= number_format($harga, 0, 0, '') ?>')" data-bs-toggle="modal" data-bs-target="#AddRetur"><i class="bi bi-capsule-pill"></i></button>
                                    <?php if ($_SESSION['admin']['level'] == 'sup') { ?>
                                        <a href="<?= $urlBase ?>&idObat=<?= $or['idobat'] ?>" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
                                    <?php } else { ?>
                                        <span style="font-size: 6.5px;">Kesalahan Silahkan Lapor Wadir</span>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card shadow p-2">
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
                    $getRetur = $koneksi->query("SELECT *, obat_rm.obat_igd FROM retur_obat_inap INNER JOIN obat_rm ON obat_rm.idobat = retur_obat_inap.obat_rm_id WHERE idrawat = '" . htmlspecialchars($_GET['idrawat']) . "'");
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
    <!-- Modal -->
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
    <?php
    if (isset($_POST['addRetur'])) {
        $idrawat = $_GET['idrawat'];
        $no_rm = $pasien['no_rm'];
        $nama_pasien = $pasien['nama_lengkap'];

        $obat_rm_id = $_POST['idobat'];
        $kode_obat = $_POST['kode_obat'];
        $nama_obat = $_POST['nama_obat'];
        $jenis_obat = $_POST['jenis_obat'];
        $jumlah_retur = $_POST['jumlah_retur'];

        $tgl_retur = date('Y-m-d');

        $getHargaBeliAkhir = $koneksi->query("SELECT * FROM rawatinapdetail WHERE ket LIKE '%$obat_rm_id%' AND ket LIKE '%Resep%' AND id = '" . htmlspecialchars($_GET['idrawat']) . "' ORDER BY created_at DESC LIMIT 1")->fetch_assoc();
        $getJum = $koneksi->query("SELECT * FROM obat_rm WHERE idobat='$obat_rm_id' LIMIT 1")->fetch_assoc();

        $hargaSatuan = -1 * $_POST['harga'];
        $uniqueId = getUniqeIdObat($koneksi);

        $koneksi->query("INSERT INTO rawatinapdetail (id, biaya, ket, besaran, tgl, petugas) VALUES ('$idrawat', 'Retur Obat Inap', 'Retur Obat $uniqueId', '" . ($hargaSatuan) * $jumlah_retur . "', '$tgl_retur', '" . $_SESSION['admin']['namalengkap'] . "')");

        $koneksi->query("INSERT INTO `retur_obat_inap`(`idretur`, `idrawat`, `no_rm`, `nama_pasien`, `obat_rm_id`, `kode_obat`, `nama_obat`, `jenis_obat`, `jumlah_retur`, `tgl_retur`) VALUES ('$uniqueId', '$idrawat','$no_rm','$nama_pasien','$obat_rm_id','$kode_obat','$nama_obat','$jenis_obat','$jumlah_retur','$tgl_retur')");

        echo "
            <script>
                alert('Successfully');
                window.location.href = '" . getFullUrl() . "';
            </script>
        ";
    }
    ?>
</div>