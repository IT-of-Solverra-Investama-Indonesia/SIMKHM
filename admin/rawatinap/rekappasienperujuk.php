<div>
    <?php if (!isset($_GET['detail'])) { ?>
        <div class="">
            <h5 class="card-title">Data Perujuk Pasien</h5>
            <div class="card shadow p-2 mb-0">
                <form method="get">
                    <div class="row g-1">
                        <div class="col-5">
                            <input type="text" name="halaman" hidden value="rekappasienperujuk" id="">
                            <input onblur="this.type='text'" onfocus="(this.type='date')" placeholder="Dari Tanggal" name="date_start" class="form-control form-control-sm" value="<?= $date_start = $_GET['date_start'] ?? '0000-00-00' ?>" id="">
                        </div>
                        <div class="col-5">
                            <input onblur="this.type='text'" onfocus="(this.type='date')" placeholder="Hingga Tanggal" name="date_end" class="form-control form-control-sm" value="<?= $date_end = $_GET['date_end'] ?? date('Y-m-d') ?>" id="">
                        </div>
                        <div class="col-2">
                            <button class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card shadow p-2 mt-2">
                <div class="table-responsive">
                    <?php if (!isset($_GET['rinci'])) { ?>
                        <a href="index.php?halaman=rekappasienperujuk&rinci" class="badge bg-warning">Data Rinci</a>
                        <table class="table table-hover table-striped table-sm" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>NamaPerujuk</th>
                                    <th>NoHpPerujuk</th>
                                    <th>JumlahRujukan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $getDataPerujuk = $koneksi->query("SELECT *, COUNT(*) as jum FROM registrasi_rawat WHERE perujuk != '' AND DATE_FORMAT(jadwal, '%Y-%m-%d') BETWEEN '$date_start' AND '$date_end' group by perujuk, perujuk_hp order by perujuk asc");
                                $no = 1;
                                foreach ($getDataPerujuk as $perujuk) {
                                ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td>
                                            <?= $date_start ?> - <?= $date_end ?>
                                        </td>
                                        <td><?= $perujuk['perujuk'] ?></td>
                                        <td><?= $perujuk['perujuk_hp'] ?></td>
                                        <td>
                                            <?= $perujuk['jum'] ?>
                                        </td>
                                        <td>
                                            <a href="index.php?halaman=rekappasienperujuk&detail&date_start=<?= $date_start ?>&date_end=<?= $date_end ?>&perujuk=<?= $perujuk['perujuk'] ?>&perujuk_hp=<?= $perujuk['perujuk_hp'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-eye"></i></a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <?php
                        if (isset($_GET['del'])) {
                            $idrawat = $_GET['del'];
                            $koneksi->query("UPDATE registrasi_rawat SET perujuk = '', perujuk_hp = '', perujuk_file = '' WHERE idrawat = '$idrawat'");
                            echo "
                                <script>
                                    document.location.href='index.php?halaman=rekappasienperujuk&rinci';
                                </script>
                            ";
                        }
                        ?>
                        <a href="index.php?halaman=rekappasienperujuk" class="badge bg-warning">Data Per Perujuk</a>
                        <table class="table table-sm table-striped table-hover" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Pasien</th>
                                    <th>Jadwal</th>
                                    <th>Perujuk</th>
                                    <th>No Perujuk</th>
                                    <th>Bukti</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $getDataPerujuk = $koneksi->query("SELECT * FROM registrasi_rawat WHERE perujuk != '' AND DATE_FORMAT(jadwal, '%Y-%m-%d') BETWEEN '$date_start' AND '$date_end' order by jadwal desc");
                                $no = 1;
                                foreach ($getDataPerujuk as $perujuk) {
                                ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $perujuk['nama_pasien'] ?></td>
                                        <td><?= $perujuk['jadwal'] ?></td>
                                        <td><?= $perujuk['perujuk'] ?></td>
                                        <td><?= $perujuk['perujuk_hp'] ?></td>
                                        <td><a href="../rawatinap/perujuk_bukti/<?= $perujuk['perujuk_file'] ?>" class="btn btn-sm btn-warning" target="_blank"><i class="bi bi-eye"></i> Lihat</a></td>
                                        <td>
                                            <a href="index.php?halaman=rekappasienperujuk&rinci&del=<?= $perujuk['idrawat'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah anda yakin ingin menghapus data ini ?')"><i class="bi bi-trash"></i></a>
                                            <button type="button" onclick="upData('<?= $perujuk['idrawat'] ?>','<?= $perujuk['perujuk'] ?>','<?= $perujuk['perujuk_hp'] ?>','<?= $perujuk['perujuk_file'] ?>')" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } ?>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <script>
            function upData(idrawat, perujuk, perujuk_hp, perujuk_file) {
                document.getElementById('idrawat').value = idrawat;
                document.getElementById('perujuk').value = perujuk;
                document.getElementById('perujuk_hp').value = perujuk_hp;
                document.getElementById('perujuk_file').value = perujuk_file;
            }
        </script>
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Update Data</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <input type="text" name="idrawat" id="idrawat" hidden>
                            <div class="mb-3">
                                <label for="perujuk" class="form-label">Nama Perujuk</label>
                                <input type="text" class="form-control" name="perujuk" id="perujuk" required>
                            </div>
                            <div class="mb-3">
                                <label for="perujuk_hp" class="form-label">No Hp Perujuk</label>
                                <input type="text" class="form-control" name="perujuk_hp" id="perujuk_hp" required>
                            </div>
                            <div class="mb-3">
                                <label for="perujuk_file" class="form-label">Bukti Rujuk (Nama File)</label>
                                <input type="file" class="form-control" name="perujuk_file" >
                                <input type="text" hidden class="form-control" name="perujuk_file_old" id="perujuk_file">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="updateData" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
        if (isset($_POST['updateData'])) {
            $idrawat = $_POST['idrawat'];
            $perujuk = $_POST['perujuk'];
            $perujuk_hp = $_POST['perujuk_hp'];
            $perujuk_file = $_FILES['perujuk_file'];
            $perujuk_file_old = $_POST['perujuk_file_old'];

            if ($perujuk_file && $perujuk_file['error'] == UPLOAD_ERR_OK) {
                $uploadDir = '../rawatinap/perujuk_bukti/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $ext = strtolower(pathinfo($perujuk_file['name'], PATHINFO_EXTENSION));
                $uniqueName = uniqid('perujuk_', true) . ($ext ? '.' . $ext : '');
                $uploadFile = $uploadDir . $uniqueName;

                // Kompres gambar hingga di bawah 100 KB
                if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                    $tmpPath = $perujuk_file['tmp_name'];
                    $maxSize = 100 * 1024; // 100 KB
                    $quality = 20; // Awal kualitas

                    if ($ext == 'jpg' || $ext == 'jpeg') {
                        $img = imagecreatefromjpeg($tmpPath);
                        do {
                            ob_start();
                            imagejpeg($img, null, $quality);
                            $imgData = ob_get_clean();
                            $size = strlen($imgData);
                            $quality -= 5;
                        } while ($size > $maxSize && $quality > 5);
                        file_put_contents($uploadFile, $imgData);
                        imagedestroy($img);
                    } elseif ($ext == 'png') {
                        $img = imagecreatefrompng($tmpPath);
                        $compression = 9;
                        do {
                            ob_start();
                            imagepng($img, null, $compression);
                            $imgData = ob_get_clean();
                            $size = strlen($imgData);
                            $compression++;
                        } while ($size > $maxSize && $compression <= 9);
                        file_put_contents($uploadFile, $imgData);
                        imagedestroy($img);
                    }
                } else {
                    move_uploaded_file($perujuk_file['tmp_name'], $uploadFile);
                }
                unlink('../rawatinap/perujuk_bukti/' . $perujuk_file_old);
            } else {
                $uniqueName = $perujuk_file_old;
            }

            $koneksi->query("UPDATE registrasi_rawat SET perujuk = '$perujuk', perujuk_hp = '$perujuk_hp', perujuk_file = '$uniqueName' WHERE idrawat = '$idrawat'");
            echo "
                <script>
                    document.location.href='index.php?halaman=rekappasienperujuk&rinci';
                </script>
            ";
        }
        ?>
    <?php } ?>

    <?php if (isset($_GET['detail'])) { ?>
        <div class="">
            <?php
            $date_start = $_GET['date_start'];
            $date_end = $_GET['date_end'];
            $perujuk = $_GET['perujuk'];
            $perujuk_hp = $_GET['perujuk_hp'];

            if (isset($_GET['hapus'])) {
                $idrawat = $_GET['hapus'];
                $koneksi->query("UPDATE registrasi_rawat SET perujuk = '', perujuk_hp = '', perujuk_file = '' WHERE idrawat = '$idrawat'");
                echo "
                    <script>
                        document.location.href='index.php?halaman=rekappasienperujuk&detail&date_start=$date_start&date_end=$date_end&perujuk=$perujuk&perujuk_hp=$perujuk_hp';
                    </script>
                ";
            }
            ?>
            <h5 class="card-title text-capitalize">Data Pasien Rujuk <?= $_GET['perujuk'] ?></h5>
            <div class="card shadow p-2">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-sm" style="font-size: 12px;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>NoRM</th>
                                <th>Jadwal</th>
                                <th>BuktiRujuk</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $getData = $koneksi->query("SELECT * FROM registrasi_rawat WHERE perujuk = '$perujuk' AND perujuk_hp = '$perujuk_hp' AND DATE_FORMAT(jadwal, '%Y-%m-%d') BETWEEN '$date_start' AND '$date_end' order by jadwal asc");
                            if ($getData->num_rows == 0) {
                                echo "
                                    <script>
                                        document.location.href='index.php?halaman=rekappasienperujuk';
                                    </script>
                                ";
                            }
                            $no = 1;
                            foreach ($getData as $data) {
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $data['nama_pasien'] ?></td>
                                    <td><?= $data['no_rm'] ?></td>
                                    <td><?= $data['jadwal'] ?></td>
                                    <td>
                                        <?php if (empty($data['perujuk_file'])) { ?>
                                            <span class="badge bg-danger" style="font-size: 12px;">Tidak Ada</span>
                                        <?php } else { ?>
                                            <a href="../rawatinap/perujuk_bukti/<?= $data['perujuk_file'] ?>" class="badge bg-warning" style="font-size: 12px;"><i class="bi bi-eye"> Lihat</i></a>
                                        <?php } ?>
                                    </td>
                                    <td><?= $data['status_antri'] ?></td>
                                    <?php if ($_SESSION['admin']['level'] == 'sup') { ?>
                                        <td>
                                            <a href="index.php?halaman=rekappasienperujuk&detail&date_start=<?= $_GET['date_start'] ?>&date_end=<?= $_GET['date_end'] ?>&perujuk=<?= $_GET['perujuk'] ?>&perujuk_hp=<?= $_GET['perujuk_hp'] ?>&hapus=<?= $data['idrawat'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="bi bi-trash"></i></a>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php } ?>
</div>