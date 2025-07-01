<div>
    <h5 class="card-title">Akun Gaji Dokter</h5>
    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addAkunGajiDokter">Add Gaji Dokter</button>
    <div class="modal fade" id="addAkunGajiDokter" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fs-5" id="staticBackdropLabel">Tambah Akun Gaji Dokter</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <input type="text" class="form-control form-control-sm" name="namaakungajidokter" placeholder="Nama Akun Gaji Dokter">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="save" class="btn btn-sm btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
    if(isset($_GET['delete'])){
        $id = htmlspecialchars($_GET['id']);
        $deleteAkunGajiDokter = $koneksimaster->query("DELETE FROM akungajidokter WHERE idakungajidokter = '$id'");
        if ($deleteAkunGajiDokter) {
            echo "<script>alert('Akun Gaji Dokter berhasil dihapus'); window.location.href='index.php?halaman=akun_gajidokter';</script>";
        } else {
            echo "<script>alert('Gagal menghapus Akun Gaji Dokter');</script>";
        }
        exit();
    }

    if (isset($_POST['save'])) {
        $koneksimaster->query("INSERT INTO akungajidokter (namaakungajidokter) VALUES ('$_POST[namaakungajidokter]')");
        echo "<script>alert('Akun Gaji Dokter berhasil ditambahkan'); window.location.href='index.php?halaman=akun_gajidokter';</script>";
        exit();
    }
    ?>
    <div class="card shadow p-2 mt-2">
        <div class="table-responsive">
            <table class="table table-hover table-striped table-sm" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Akun</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $getData = $koneksimaster->query("SELECT * FROM akungajidokter ORDER BY namaakungajidokter DESC");
                    foreach ($getData as $data) {
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $data['namaakungajidokter'] ?></td>
                            <td>
                                <a href="index.php?halaman=akun_gajidokter&delete&id=<?= $data['idakungajidokter'] ?>" onclick="return confirm('Apakah anda yakin ingin menghapus data ini ?')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
                                <button onclick="upData('<?= $data['idakungajidokter'] ?>', '<?= $data['namaakungajidokter'] ?>')" data-bs-toggle="modal" data-bs-target="#updateAkunGajiDokter" class="btn btn-sm btn-success"><i class="bi bi-pencil"></i></button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        function upData(id, nama) {
            document.getElementById('idakungajidokter_id').value = id;
            document.getElementById('namaakungajidokter_id').value = nama;
        }
    </script>
    <div class="modal fade" id="updateAkunGajiDokter" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fs-5" id="staticBackdropLabel">Update Akun Gaji Dokter</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <input type="text" class="form-control form-control-sm" name="idakungajidokter" id="idakungajidokter_id" hidden>
                        <input type="text" class="form-control form-control-sm" name="namaakungajidokter" id="namaakungajidokter_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="update" class="btn btn-sm btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
    if (isset($_POST['update'])) {
        $idakungajidokter = htmlspecialchars($_POST['idakungajidokter']);
        $namaakungajidokter = htmlspecialchars($_POST['namaakungajidokter']);
        $updateAkunGajiDokter = $koneksimaster->query("UPDATE akungajidokter SET namaakungajidokter = '$namaakungajidokter' WHERE idakungajidokter = '$idakungajidokter'");
        if ($updateAkunGajiDokter) {
            echo "<script>alert('Akun Gaji Dokter berhasil diupdate'); window.location.href='index.php?halaman=akun_gajidokter';</script>";
        } else {
            echo "<script>alert('Gagal mengupdate Akun Gaji Dokter');</script>";
        }
        exit();
    }
    ?>
</div>