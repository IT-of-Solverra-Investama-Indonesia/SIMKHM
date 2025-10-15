<style>
    @media (max-width: 768px) {
        .table-responsive tr {
            font-size: 12px;
        }

        .dataTables_info,
        .dataTables_paginate {
            font-size: 12px !important;
        }

        .dataTables_paginate a {
            font-size: 12px !important;
        }
    }
</style>


<!-- Modal -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="tambahModalLabel">Tambah bpjs</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label" for="">Tanggal</label>
                        <input type="date" class="form-control mb-3" id="tanggal" name="tanggal" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Nama</label>
                        <input type="text" class="form-control mb-3" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">NIK</label>
                        <input type="text" class="form-control mb-3" id="nik" name="nik" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Asal Faskes</label>
                        <input type="text" class="form-control mb-3" id="asal_faskes" name="asal_faskes" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Foto</label>
                        <input class="form-control" type="file" name="foto" id="foto">
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="save" class="btn btn-primary">Simpan</button>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Data Ubah BPJS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data">
                    <input type="text" id="edit-id" name="id" class="form-control" hidden>
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" class="form-control mb-3" id="edit-tanggal" name="tanggal" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Nama</label>
                        <input type="text" class="form-control mb-3" id="edit-nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">NIK</label>
                        <input type="text" class="form-control mb-3" id="edit-nik" name="nik" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Asal Faskes</label>
                        <input type="text" class="form-control mb-3" id="edit-asal_faskes" name="asal_faskes" required>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Foto</label>
                        <input class="form-control" type="file" name="foto" id="foto">
                        <input type="hidden" name="foto_lama" id="edit-foto-lama">
                        <div id="preview-foto-lama" class="mt-2"></div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" name="update" class="btn btn-primary">Update</button>
            </div>
            </form>
        </div>
    </div>
</div>
</div>

<button class="btn btn-sm btn-primary" id="tambah" name="tambah" data-bs-toggle="modal" data-bs-target="#tambahModal">
    + Tambah Data
</button>
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4 mt-4">
            <div class="card-header pb-0">
                <h6>Data Ubah BPJS</h6>
            </div>
            <div class="p-2">
                <div class="table-responsive">
                    <table class="display table table-sm table-striped nowrap" style="width: 100%" id="myTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Tanggal</th>
                                <th>Nama</th>
                                <th>NIK</th>
                                <th>Asal Faskes</th>
                                <th>Foto BPJS</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $get = $koneksi->query("SELECT * FROM ubahbpjs");
                            while ($row = $get->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $row['tanggal'] ?></td>
                                    <td><?= $row['nama'] ?></td>
                                    <td><?= $row['nik'] ?></td>
                                    <td><?= $row['asal_faskes'] ?></td>
                                    <td><img src="<?= htmlspecialchars($row['foto']) ?>" alt="" width="60"></td>
                                    <td>
                                        <a href="#" class="btn btn-warning btn-sm btn-edit"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal"
                                            data-id="<?= $row['id'] ?>"
                                            data-tanggal="<?= $row['tanggal'] ?>"
                                            data-nama="<?= $row['nama'] ?>"
                                            data-nik="<?= $row['nik'] ?>"
                                            data-asal_faskes="<?= $row['asal_faskes'] ?>"
                                            data-foto="<?= htmlspecialchars($row['foto']) ?>">
                                            Edit
                                        </a>
                                        <a href="index.php?halaman=ubahbpjs&delete=<?= $row['id'] ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin ingin menghapus data bpjs ini?')">
                                            Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if (isset($_POST['save'])) {
    $tanggal = htmlspecialchars($_POST['tanggal']);
    $nama = htmlspecialchars($_POST['nama']);
    $nik = htmlspecialchars($_POST['nik']);
    $asal_faskes = htmlspecialchars($_POST['asal_faskes']);
    $foto = null;
    if (!empty($_FILES['foto']['name'])) {
        $target_dir = "foto/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $ext = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
        $file_name = uniqid('bpjs_') . '.' . $ext;
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
            $foto = $target_file;
        }
    }
    $sql = "INSERT INTO ubahbpjs (tanggal, nama, nik, asal_faskes, foto) VALUES (?, ?, ?, ?, ?)";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("sssss", $tanggal, $nama, $nik, $asal_faskes, $foto);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil ditambahkan');document.location.href='index.php?halaman=ubahbpjs';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data');document.location.href='index.php?halaman=ubahbpjs';</script>";
    }
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $tanggal = htmlspecialchars($_POST['tanggal']);
    $nama = htmlspecialchars($_POST['nama']);
    $nik = htmlspecialchars($_POST['nik']);
    $asal_faskes = htmlspecialchars($_POST['asal_faskes']);
    $foto_lama = $_POST['foto_lama'];
    $foto = $foto_lama;

    if (!empty($_FILES['foto']['name'])) {
        $target_dir = "foto/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $ext = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
        $file_name = uniqid('bpjs_') . '.' . $ext;
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
            if ($foto_lama && file_exists($foto_lama)) unlink($foto_lama);
            $foto = $target_file;
        }
    }

    $sql = "UPDATE ubahbpjs SET tanggal = ?, nama = ?, nik = ?, asal_faskes = ?, foto = ? WHERE id = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("ssssss", $tanggal, $nama, $nik, $asal_faskes, $foto, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diupdate');document.location.href='index.php?halaman=ubahbpjs';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate data');document.location.href='index.php?halaman=ubahbpjs';</script>";
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM ubahbpjs WHERE id = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("s", $id);
    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil dihapus');document.location.href='index.php?halaman=ubahbpjs';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data');document.location.href='index.php?halaman=ubahbpjs';</script>";
    }
}
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            ordering: false,
            scrollX: true,
        });

        $('.btn-edit').on('click', function() {
            var button = $(this);
            $('#edit-id').val(button.data('id'));
            $('#edit-tanggal').val(button.data('tanggal'));
            $('#edit-nama').val(button.data('nama'));
            $('#edit-nik').val(button.data('nik'));
            $('#edit-asal_faskes').val(button.data('asal_faskes'));
            $('#edit-foto-lama').val(button.data('foto'));

            if (button.data('foto')) {
                $('#preview-foto-lama').html(
                    `<img src="${button.data('foto')}" alt="Foto Lama" width="100" class="img-thumbnail mt-2">`
                );
            } else {
                $('#preview-foto-lama').html('<small class="text-muted">Tidak ada foto lama</small>');
            }
        });
    });
</script>