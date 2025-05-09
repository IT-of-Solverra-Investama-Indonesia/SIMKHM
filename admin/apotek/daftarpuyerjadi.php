<div class="">
    <h3>Paket Jadi</h3>
    <?php if (isset($_GET['add'])) { ?>
        <a href="index.php?halaman=daftarpuyerjadi" class="btn btn-dark btn-sm mb-2">Kembali</a>
        <div class="card shadow p-2">
            <h5>Tambah Paket Jadi</h5>
            <form method="post">
                <div class="row">
                    <div class="col-md-12">
                        <label for="">Nama Paket</label>
                        <input type="text" name="nama_paket" id="" class="form-control mb-2">
                    </div>
                    <div class="col-md-12">
                        <label for="">Deskripsi Paket</label>
                        <textarea name="deskripsi" id="" class="form-control mb-2"></textarea>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-primary" name="tambah">[+] Tambah</button>
                    </div>
                </div>
            </form>
        </div>
        <?php
        if (isset($_POST['tambah'])) {
            $nama_paket = htmlspecialchars($_POST['nama_paket']);
            $deskripsi = htmlspecialchars($_POST['deskripsi']);
            $koneksimaster->query("INSERT INTO puyerjadi (nama_paket, deskripsi) VALUES ('$nama_paket', '$deskripsi')");
            echo "<script>alert('Data berhasil ditambahkan');</script>";
            echo "<script>location='index.php?halaman=daftarpuyerjadi';</script>";
        }
        ?>
    <?php } elseif (isset($_GET['edit'])) { ?>
        <?php $single = $koneksimaster->query("SELECT * FROM puyerjadi WHERE id = '" . htmlspecialchars($_GET['id']) . "'")->fetch_assoc() ?>
        <a href="index.php?halaman=daftarpuyerjadi" class="btn btn-dark btn-sm mb-2">Kembali</a>
        <div class="card shadow p-2">
            <h5>Edit Paket Jadi</h5>
            <form method="post">
                <div class="row">
                    <div class="col-md-12">
                        <label for="">Nama Paket</label>
                        <input type="text" name="nama_paket" value="<?= $single['nama_paket'] ?>" id="" class="form-control mb-2">
                    </div>
                    <div class="col-md-12">
                        <label for="">Deskripsi Paket</label>
                        <textarea name="deskripsi" id="" class="form-control mb-2"><?= $single['deskripsi'] ?></textarea>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-success" name="tambah">[+] Save</button>
                    </div>
                </div>
            </form>
        </div>
        <?php
        if (isset($_POST['tambah'])) {
            $nama_paket = htmlspecialchars($_POST['nama_paket']);
            $deskripsi = htmlspecialchars($_POST['deskripsi']);
            $koneksimaster->query("UPDATE puyerjadi SET nama_paket = '$nama_paket', deskripsi = '$deskripsi' WHERE id = '" . htmlspecialchars($_GET['id']) . "'");
            echo "<script>alert('Data berhasil diedit');</script>";
            echo "<script>location='index.php?halaman=daftarpuyerjadi';</script>";
        }
        ?>

    <?php } elseif (isset($_GET['delete'])) { ?>
        <?php
        $koneksimaster->query("DELETE FROM puyerjadi WHERE id = '" . htmlspecialchars($_GET['id']) . "'");
        $koneksimaster->query("DELETE FROM puyerjadi_detail WHERE puyer_id = '" . htmlspecialchars($_GET['id']) . "'");
        echo "<script>alert('Data berhasil dihapus');</script>";
        echo "<script>location='index.php?halaman=daftarpuyerjadi';</script>";
        ?>
    <?php } elseif (isset($_GET['obat'])) { ?>
        <a href="index.php?halaman=daftarpuyerjadi" class="btn btn-dark btn-sm mb-2">Kembali</a>
        <div class="card shadow p-2">
            <h5>Tambah Obat Pada Racik</h5>
            <form action="" method="POST">
                <div class="row">
                    <div class="col-12">
                        <label for="">Obat</label>
                        <select name="kode_obat" id="" required class="form-control mb-2">
                            <option value="" hidden>Pilih Obat</option>
                            <?php
                            $getObat = $koneksi->query("SELECT * FROM apotek GROUP BY id_obat ORDER BY nama_obat ASC");
                            foreach ($getObat as $obat) {
                            ?>
                                <option value="<?= $obat['id_obat'] ?>"><?= $obat['nama_obat'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="">Dosis</label>
                        <div class="input-group">
                            <input type="text" class="form-control mb-2" id="dosis1" name="dosis1">
                            <span type="text" style="text-align: center;" class="form-control mb-2" placeholder="X" disabled>X</span>
                            <input type="text" class="form-control mb-2" id="dosis2" name="dosis2">
                        </div>
                    </div>
                    <div class="col-md-4">
                        Per
                        <select id="inputState" name="per" class="form-select">
                            <option>Per Hari</option>
                            <option>Per Jam</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        Jumlah
                        <input type="number" name="jumlah" id="" class="form-control mb-2">
                    </div>
                    <div class="col-md-6">
                        Petunjuk Pemakaian
                        <input type="text" name="petunjuk_pemakaian" id="" class="form-control mb-2">
                    </div>
                    <div class="col-md-6">
                        Durasi (Hari)
                        <input type="number" name="durasi" id="" class="form-control mb-2">
                    </div>
                    <div class="col-md-12">
                        Catatan Obat
                        <input type="text" name="ctt_obat" id="" class="form-control mb-2">
                        <button class="btn btn-sm btn-primary" name="save">Tambah</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card shadow p-2 mt-1">
            <div class="table-responsive">
                <table class="table table-hover table-striped" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Kode</th>
                            <th>Jumlah</th>
                            <th>Dosis</th>
                            <th>Durasi</th>
                            <th>Petunjuk</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $getObatPuyer = $koneksimaster->query("SELECT * FROM puyerjadi_detail WHERE puyer_id = '" . htmlspecialchars($_GET['id']) . "'");
                        foreach ($getObatPuyer as $obatPuyer) {
                        ?>
                            <tr>
                                <td><?= $obatPuyer['nama_obat'] ?></td>
                                <td><?= $obatPuyer['kode_obat'] ?></td>
                                <td><?= $obatPuyer['jumlah'] ?></td>
                                <td><?= $obatPuyer['dosis1'] ?> x <?= $obatPuyer['dosis2'] ?> <?= $obatPuyer['per'] ?></td>
                                <td><?= $obatPuyer['durasi'] ?> Hari</td>
                                <td><?= $obatPuyer['petunjuk_pemakaian'] ?></td>
                                <td><?= $obatPuyer['ctt_obat'] ?></td>
                                <td><a href="index.php?halaman=daftarpuyerjadi&obat&id=<?= htmlspecialchars($_GET['id']) ?>&delOb=<?= $obatPuyer['id'] ?>" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
        if (isset($_POST['save'])) {
            $puyer_id = htmlspecialchars($_GET['id']);
            $kode_obat = htmlspecialchars($_POST['kode_obat']);
            $getSingleObat = $koneksi->query("SELECT * FROM apotek WHERE id_obat = '$kode_obat'")->fetch_assoc();
            $nama_obat = htmlspecialchars($getSingleObat['nama_obat']);
            $jumlah = htmlspecialchars($_POST['jumlah']);
            $dosis1 = htmlspecialchars($_POST['dosis1']);
            $dosis2 = htmlspecialchars($_POST['dosis2']);
            $per = htmlspecialchars($_POST['per']);
            $petunjuk_pemakaian = htmlspecialchars($_POST['petunjuk_pemakaian']);
            $durasi = htmlspecialchars($_POST['durasi']);
            $ctt_obat = htmlspecialchars($_POST['ctt_obat']);

            $koneksimaster->query("INSERT INTO `puyerjadi_detail`(`puyer_id`, `kode_obat`, `nama_obat`, `jumlah`, `dosis1`, `dosis2`, `per`, `petunjuk_pemakaian`, `durasi`, `ctt_obat`) VALUES ('$puyer_id', '$kode_obat', '$nama_obat', '$jumlah', '$dosis1', '$dosis2', '$per', '$petunjuk_pemakaian', '$durasi', '$ctt_obat')");

            echo "
                    <script>
                        alert('Successfully');
                        document.location.href = 'index.php?halaman=daftarpuyerjadi&obat&id=$puyer_id';
                    </script>
                ";
        }
        if (isset($_GET['delOb'])) {
            $koneksi->query("DELETE FROM puyerjadi_detail WHERE id = '" . htmlspecialchars($_GET['delOb']) . "'");
            echo "<script>alert('Data berhasil dihapus');</script>";
            echo "<script>location='index.php?halaman=daftarpuyerjadi&obat&id=" . htmlspecialchars($_GET['id']) . "';</script>";
        }
        ?>
    <?php } else { ?>
        <a href="index.php?halaman=daftarpuyerjadi&add" class="btn btn-sm btn-primary mb-2">[+] Tambah</a>
        <div class="card shadow p-2">
            <div class="table-responsive">
                <table class="table table-hover table-striped" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Paket</th>
                            <th>Deskripsi Paket</th>
                            <th>Obat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $getPuyerJadi = $koneksimaster->query("SELECT * FROM puyerjadi ORDER BY created_at DESC");
                        foreach ($getPuyerJadi as $pj) {
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $pj['nama_paket'] ?></td>
                                <td><?= $pj['deskripsi'] ?></td>
                                <td></td>
                                <td>
                                    <a href="index.php?halaman=daftarpuyerjadi&obat&id=<?= $pj['id'] ?>" class="btn btn-sm btn-success"><i class="bi bi-capsule"></i></a>
                                    <a href="index.php?halaman=daftarpuyerjadi&edit&id=<?= $pj['id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                    <a href="index.php?halaman=daftarpuyerjadi&delete&id=<?= $pj['id'] ?>" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php } ?>
</div>