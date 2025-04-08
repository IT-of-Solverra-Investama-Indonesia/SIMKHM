<div class="pagetitle">
    <h1>Visite Dokter</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?halaman=daftarapotek" style="color:blue;">Rawat Inap</a></li>
        </ol>
    </nav>
</div>

<?php if (!isset($_GET['riwayat']) and !isset($_GET['datagajivisite'])) { ?>
    <a href="index.php?halaman=visitedokter&riwayat" class="btn btn-sm btn-primary mb-2" style="max-width: 100px;">Riwayat</a>
    <a href="index.php?halaman=visitedokter&datagajivisite" class="btn btn-sm btn-primary mb-2 ms-2" style="max-width: 150px;">Data Gaji Visite</a>
    <?php if (isset($_POST['refreshData'])) { ?>
        <?php
        $ambilinap = $koneksi->query("SELECT * FROM registrasi_rawat WHERE perawatan = 'Rawat Inap' AND (status_antri!='Pulang')"); ?>
        <?php while ($pecahinap = $ambilinap->fetch_assoc()) { ?>
            <?php
            $id = $pecahinap['idrawat'];
            $carabayar = $pecahinap['carabayar'];
            //jika belum ada, maka dibeli 1
            $_SESSION['keranjang'][$id] = $carabayar;
            echo "
                <script>
                    alert('Data Berhasil dimunculkan');
                    document.location.href='index.php?halaman=visitedokter';
                </script>
            ";
            ?>
        <?php } ?>
    <?php } ?>
    <?php if (!isset($_SESSION['keranjang'])) { ?>
        <div class="card shadow-sm p-2 mb-2">
            <form method="post">
                <center>
                    Silahkan Klik Untuk Memunculkan Pasien Pasien</h5><br>
                    <button class="btn btn-sm btn-primary" name="refreshData">Refresh Data</button>
                </center>
            </form>
        </div>
    <?php } else { ?>
        <div class="card shadow-sm p-2 mb-2">
            <form method="post">
                <button class="btn btn-sm btn-primary" name="refreshData">Refresh Data</button>
            </form>
            <div class="table-responsive">
                <table class="table table-hover table-striped" style="font-size: 13px;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NoRM</th>
                            <th>Carabayar</th>
                            <th>Tgl Masuk</th>
                            <th>Kamar</th>
                            <th>Biaya Pasien</th>
                            <th>Gaji Dokter</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $total = 0 ?>
                        <?php $totalg = 0 ?>
                        <?php $no = 1 ?>
                        <?php foreach ($_SESSION['keranjang'] as $id => $carabayar):  ?>
                            <?php
                            $getRegis = $koneksi->query("SELECT * FROM registrasi_rawat WHERE idrawat = '$id' LIMIT 1 ")->fetch_assoc();
                            ?>
                            <?php
                            $besaran = 0;
                            $gajidokter = 0;
                            if ($getRegis['carabayar'] == 'umum') {
                                $besaran = 20000;
                                $gajidokter = 10000;
                            };
                            if ($getRegis['carabayar'] == 'bpjs') {
                                $besaran = 5000;
                                $gajidokter = 5000;
                            };
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $getRegis['nama_pasien'] ?></td>
                                <td><?= $getRegis['no_rm'] ?></td>
                                <td><?= $getRegis['carabayar'] ?></td>
                                <td><?= $getRegis['jadwal'] ?></td>
                                <td><?= $getRegis['kamar'] ?></td>
                                <td><?php echo $besaran ?></td>
                                <td><?php echo $gajidokter ?></td>
                                <td>
                                    <a href="index.php?halaman=visitedokter&del=<?= $id ?>" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                            <?php $total += $besaran ?>
                            <?php $totalg += $gajidokter ?>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6"><b>Total</b></td>
                            <td>Rp <?= number_format($total, 0, 0, '.') ?></td>
                            <td>Rp <?= number_format($totalg, 0, 0, '.') ?></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <?php
        if (isset($_GET['del'])) {
            $id = $_GET['del'];

            // var_dump($id); //die;
            unset($_SESSION['keranjang'][$id]);
            $koneksi->query("UPDATE registrasi_rawat set oke='1' where idrawat='$id'");
            echo "
                <script>
                    alert('Successfully');
                    document.location.href='index.php?halaman=visitedokter';
                </script>
            ";
        }
        ?>

        <div class="card shadow-sm p-2 mt-0">
            <form method="post">
                <table class="table table-bordered">
                    <thead>
                    </thead>
                    <tbody>
                        <div class="form-group">
                            <label class="">Dokter Jaga</label>
                            <div class="form_control">
                                <select name="dokter" class="form-control" required>
                                    <option value="" hidden>Pilih Dokter</option>
                                    <?php
                                    $ambildokter = $koneksi->query("SELECT * FROM admin WHERE level = 'dokter'");
                                    while ($pecahdokter = $ambildokter->fetch_assoc()) {
                                    ?>
                                        <option value="<?php echo $pecahdokter['namalengkap']; ?>"><?php echo $pecahdokter['namalengkap']; ?> </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group mt-2">
                            <label>Petugas</label>
                            <?php $user = $_SESSION['admin']['namalengkap'] ?>
                            <input readonly type="text" name="petugas" class="form-control" value="<?php echo $user ?>">
                        </div>
                        <div class="form-group mt-2">
                            <label class="">Shift</label>
                            <div class="form_control">
                                <select name="shift" class="form-control " required="">
                                    <option value="" hidden>Pilih Shift</option>
                                    <option value="pagi">pagi</option>
                                    <option value="sore">sore</option>
                                    <option value="malam">malam</option>
                                </select>
                            </div>
                            </di>
                    </tbody>
                </table>
                <button class="btn btn-primary " name="simpan">Simpan</button>
            </form>
        </div>

        <?php
        if (isset($_POST['simpan'])) {
            date_default_timezone_set('Asia/Jakarta');
            $tgl = date('Y-m-d');
            $dokter = htmlspecialchars($_POST['dokter']);
            $petugas = htmlspecialchars($_POST['petugas']);
            $shift = htmlspecialchars($_POST['shift']);
            $biaya = 'Visite Rawat Inap';

            foreach ($_SESSION['keranjang'] as $id => $carabayar) {
                $getSingleRegis = $koneksi->query("SELECT * FROM registrasi_rawat WHERE idrawat = '$id' LIMIT 1 ")->fetch_assoc();

                $idd = $getSingleRegis['idrawat'];

                if ($carabayar == 'umum') {
                    $besaran = 20000;
                };
                if ($carabayar == 'bpjs') {
                    $besaran = 5000;
                };

                $koneksi->query("INSERT INTO rawatinapdetail (id, tgl, biaya, besaran, ket, petugas ) VALUES ('$idd', '$tgl', '$biaya', '$besaran', '$dokter', '$petugas') ");
            }

            $ambilgaji = $koneksimaster->query("SELECT * from gajidokter where akun='Visite Rawat Inap' and dokter='$dokter' and shiftgaji='$shift' and tgl='$tgl' ");

            $row = $ambilgaji->num_rows;

            $koneksimaster->query("INSERT INTO gajidokter (tgl, dokter, akun, besaran, ket, petugas, shiftgaji, unit) VALUES ('$tgl', '$dokter', '$biaya', '$totalg', '$dokter', '$petugas', '$shift', 'KHM 1') ");

            // $koneksi->query("UPDATE rawatinap SET oke=0");

            unset($_SESSION['keranjang']);
            echo "
                    <script>
                        alert('Successfully');
                        location='index.php?halaman=visitedokter';
                    </script>
                ";
        }
        ?>
    <?php } ?>

<?php } elseif (isset($_GET['riwayat'])) { ?>
    <a href="index.php?halaman=visitedokter" class="btn btn-sm btn-dark mb-2" style="max-width: 100px;">Kembali</a>

    <div class="card shadow-sm p-2">
        <h5>Riwayat Visite Dokter</h5>
        <div class="table-responsive">
            <table class="table tabl-hover table-striped" style="font-size: 13px;">
                <thead>
                    <tr>
                        <th>Tgl</th>
                        <th>Nama Pasien</th>
                        <th>Kamar</th>
                        <th>Cara Bayar</th>
                        <th>Biaya</th>
                        <th>Dokter</th>
                        <th>Besaran</th>
                        <th>Petugas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $tgl = date('Y-m-d');
                    $getVisite = $koneksi->query("SELECT * from rawatinapdetail join registrasi_rawat on registrasi_rawat.idrawat=rawatinapdetail.id where biaya LIKE 'visite%' and tgl='$tgl' order by id_data desc");

                    foreach ($getVisite as $visite) {
                    ?>
                        <tr>
                            <td><?= $visite['tgl'] ?></td>
                            <td><?= $visite['nama_pasien'] ?></td>
                            <td><?= $visite['kamar'] ?></td>
                            <td><?= $visite['carabayar'] ?></td>
                            <td><?= $visite['biaya'] ?></td>
                            <td><?= $visite['ket'] ?></td>
                            <td><?= $visite['besaran'] ?></td>
                            <td><?= $visite['petugas'] ?></td>
                            <td>
                                <a onclick="return confirm('Apakah anda yakin ingin menghapus data ini ?')" href="index.php?halaman=visitedokter&riwayat&del=<?= $visite['id_data'] ?>" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
    if (isset($_GET['del'])) {
        $koneksi->query("DELETE FROM rawatinapdetail WHERE id_data = '" . htmlspecialchars($_GET['del']) . "'");

        echo "
                <script>
                    alert('Successfully');
                    document.location.href='index.php?halaman=visitedokter&riwayat';
                </script>
            ";
    }
    ?>
<?php } elseif (isset($_GET['datagajivisite'])) { ?>
    <a href="index.php?halaman=visitedokter" class="btn btn-sm btn-dark" style="max-width: 100px;">Kembali</a>
    <div class="card shadow-sm p-2 mt-2">
        <div class="table-responsive">
            <table class="table-hover table table-striped" style="font-size: 13px;">
                <thead>
                    <tr>
                        <th>Tgl</th>
                        <th>Dokter</th>
                        <th>Akun</th>
                        <th>Besaran</th>
                        <th>Petugas</th>
                        <th>Shift</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $getGajiDokter = $koneksimaster->query("SELECT * FROM gajidokter WHERE tgl='" . date('Y-m-d') . "'");
                    foreach ($getGajiDokter as $gaji) {
                    ?>
                        <tr>
                            <td><?= $gaji['tgl'] ?></td>
                            <td><?= $gaji['dokter'] ?></td>
                            <td><?= $gaji['akun'] ?></td>
                            <td><?= $gaji['besaran'] ?></td>
                            <td><?= $gaji['petugas'] ?></td>
                            <td><?= $gaji['shiftgaji'] ?></td>
                            <td>
                                <button data-bs-toggle="modal" data-bs-target="#staticBackdrop<?= $gaji['idgaji'] ?>" class="btn btn-sm btn-success"><i class="bi bi-pencil"></i></button>
                            </td>
                        </tr>
                        <!-- Modal -->
                        <div class="modal fade" id="staticBackdrop<?= $gaji['idgaji'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form method="POST">
                                        <div class="modal-body">
                                            <input type="number" value="<?= $gaji['idgaji'] ?>" placeholder="Besaran Nominal" class="form-control mb-2" name="idgaji" id="">
                                            <input type="number" value="<?= $gaji['besaran'] ?>" placeholder="Besaran Nominal" class="form-control mb-2" name="besaran" id="">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" name="simpan" class="btn btn-sm btn-success">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </tbody>
            </table>
            <?php
            if (isset($_POST['simpan'])) {
                $koneksimaster->query("UPDATE gajidokter SET besaran = '" . htmlspecialchars($_POST['besaran']) . "' WHERE idgaji = '" . htmlspecialchars($_POST['idgaji']) . "'");

                echo "
                        <script>
                            alert('Successfully');
                            document.location.href='index.php?halaman=visitedokter&datagajivisite';
                        </script>
                    ";
            }
            ?>

        </div>
    </div>
<?php } ?>