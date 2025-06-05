<div class="pagetitle">
    <h1>Master Layanan</h1>
</div>
<button class="btn btn-sm btn-primary mb-1" style="max-width: 100px;" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">[+] Layanan</button>
<!-- Modal -->
<div class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Layanan Master</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <label for="">Nama Layanan</label>
                    <input type="text" name="nama_layanan" id="" class="form-control mb-2" required>
                    <label for="">Harga</label>
                    <input type="number" name="harga" id="" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="addLayanan" class="btn btn-sm btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
if (isset($_POST["addLayanan"])) {
    $nama_layanan = htmlspecialchars($_POST['nama_layanan']);
    $harga = htmlspecialchars($_POST['harga']);
    $user = $_SESSION['admin']['namalengkap'];
    $insertLayanan = $koneksimaster->query("INSERT INTO master_layanan (nama_layanan, harga, user) VALUES ('$nama_layanan', '$harga', '$user')");
    if ($insertLayanan) {
        echo "<script>alert('Berhasil menambah layanan');</script>";
    } else {
        echo "<script>alert('Gagal menambah layanan');</script>";
    }
    echo "<script>window.location.href='?halaman=master_layanan';</script>";
}
?>
<div class="card shadow p-2 mb-2">
    <form method="get">
        <div class="row">
            <div class="col-10">
                <input type="text" name="key" id="" class="form-control form-control-sm" placeholder="Cari..." value="<?= isset($_GET['key']) ? $_GET['key'] : '' ?>">
            </div>
            <div class="col-2">
                <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-search"></i></button>
            </div>
        </div>
    </form>
</div>
<div class="card shadow p-2">
    <div class="table-responsive">
        <table class="table table-sm table-hover table-striped" style="font-size: 12px;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Oleh</th>
                    <th>Nama Layanan</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $key = isset($_GET['key']) ? " AND nama_layanan LIKE '%" . htmlspecialchars($_GET['key']) . "%'" : "";
                $no = 1;
                $getDataLayanan = $koneksimaster->query("SELECT * FROM master_layanan WHERE 1=1 $key");
                ?>
                <?php foreach ($getDataLayanan as $layanan) { ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $layanan['user'] ?></td>
                        <td><?= $layanan['nama_layanan'] ?></td>
                        <td>Rp <?= number_format($layanan['harga'], 0, 0, '.') ?></td>
                        <td>
                            <button class="btn btn-sm btn-success" onclick="upDataUpdate('<?= $layanan['id'] ?>', '<?= $layanan['nama_layanan'] ?>', '<?= $layanan['harga'] ?>')" data-bs-toggle="modal" data-bs-target="#updateModal"><i class="bi bi-pencil"></i></button>
                            <a onclick="return confirm('Are you sure?')" href="index.php?halaman=master_layanan&delete=<?= $layanan['id'] ?>" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <script>
        function upDataUpdate(id, nama_layanan, harga) {
            document.getElementById('nama_layanan_id').value = nama_layanan;
            document.getElementById('harga_id').value = harga;
            document.getElementById('id_id').value = id;
        }
    </script>
    <!-- Modal Update -->
    <div class="modal fade" id="updateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Update Layanan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <label for="">Nama Layanan</label>
                        <input type="text" name="nama_layanan" id="nama_layanan_id" class="form-control mb-2" required>
                        <label for="">Harga</label>
                        <input type="number" name="harga" id="harga_id" class="form-control" required>
                        <input type="text" name="updateid" id="id_id" class="form-control" hidden required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="updateLayanan" class="btn btn-sm btn-success">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php 
        if(isset($_POST['updateLayanan'])){
            $koneksimaster->query("UPDATE master_layanan SET nama_layanan = '" . htmlspecialchars($_POST['nama_layanan']) . "', harga = '" . htmlspecialchars($_POST['harga']) . "' WHERE id = '" . htmlspecialchars($_POST['updateid']) . "'");
            echo "<script>alert('Berhasil mengupdate layanan');</script>";
            echo "<script>window.location.href='index.php?halaman=master_layanan';</script>";
        }

        if(isset($_GET['delete'])){
            $koneksimaster->query("DELETE FROM master_layanan WHERE id = '" . htmlspecialchars($_GET['delete']) . "'");
            echo "<script>alert('Berhasil menghapus layanan');</script>";
            echo "<script>window.location.href='index.php?halaman=master_layanan';</script>";
        }
    ?>
</div>