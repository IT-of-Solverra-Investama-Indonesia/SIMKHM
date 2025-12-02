<div class="">
    <?php
    $pasien = $koneksi->query("SELECT * FROM registrasi_rawat INNER JOIN pasien ON pasien.no_rm = registrasi_rawat.no_rm WHERE idrawat = '" . htmlspecialchars($_GET['idrawat']) . "'")->fetch_assoc();

    // CREATE - Simpan Catatan Lab
    if (isset($_POST['simpan_catatan'])) {
        $idrawat = htmlspecialchars($_GET['idrawat']);
        $nama_pasien = $pasien['nama_lengkap'];
        $jadwal = $pasien['jadwal'];
        $no_rm = $pasien['no_rm'];
        $catatan = mysqli_real_escape_string($koneksi, $_POST['catatan']);
        $created_by = $_SESSION['admin']['namalengkap'];

        $query = "INSERT INTO catatan_lab (idrawat_registrasi, nama_pasien, jadwal, no_rm, catatan, created_by) 
                  VALUES ('$idrawat', '$nama_pasien', '$jadwal', '$no_rm', '$catatan', '$created_by')";

        if ($koneksi->query($query)) {
            echo "<script>alert('Catatan lab berhasil disimpan!');</script>";
            echo "<script>window.location.href='index.php?halaman=catatanlab&idrawat=" . $idrawat . "';</script>";
        } else {
            echo "<script>alert('Gagal menyimpan catatan lab!');</script>";
        }
    }

    // UPDATE - Update Catatan Lab
    if (isset($_POST['update_catatan'])) {
        $id = htmlspecialchars($_POST['id_catatan']);
        $catatan = mysqli_real_escape_string($koneksi, $_POST['catatan_edit']);

        $query = "UPDATE catatan_lab SET catatan = '$catatan' WHERE id = '$id'";

        if ($koneksi->query($query)) {
            echo "<script>alert('Catatan lab berhasil diupdate!');</script>";
            echo "<script>window.location.href='index.php?halaman=catatanlab&idrawat=" . htmlspecialchars($_GET['idrawat']) . "';</script>";
        } else {
            echo "<script>alert('Gagal mengupdate catatan lab!');</script>";
        }
    }

    // DELETE - Hapus Catatan Lab
    if (isset($_GET['hapus_catatan'])) {
        $id = htmlspecialchars($_GET['hapus_catatan']);
        $query = "DELETE FROM catatan_lab WHERE id = '$id'";

        if ($koneksi->query($query)) {
            echo "<script>alert('Catatan lab berhasil dihapus!');</script>";
            echo "<script>window.location.href='index.php?halaman=catatanlab&idrawat=" . htmlspecialchars($_GET['idrawat']) . "';</script>";
        } else {
            echo "<script>alert('Gagal menghapus catatan lab!');</script>";
        }
    }

    // READ - Ambil Riwayat Catatan Lab
    $riwayat_catatan = $koneksi->query("SELECT * FROM catatan_lab WHERE idrawat_registrasi = '" . htmlspecialchars($_GET['idrawat']) . "' ORDER BY created_at DESC");
    ?>
    <div class="card shadow p-2 mb-2">
        <h5 class="card-title">
            Catatan Lab <br>
            <?php echo $pasien['nama_lengkap'] ?> (<?php echo $pasien['no_rm'] ?>) | TglLahir:
            <?php echo date("d-m-Y", strtotime($pasien['tgl_lahir'])) ?> | Alamat: <?php echo $pasien['alamat'] ?>
            <br> <?php if (!isset($_GET['igd'])) { ?>Kamar: <?php echo $pasien['kamar'] ?> |<?php } ?> JK:
            <?php if ($pasien["jenis_kelamin"] == 1) {
                echo "Laki-Laki";
            } else {
                echo "Perempuan";
            } ?>
        </h5>
        <form method="post">
            <label for=""><b><i class="bi bi-journal-text"></i> Input Catatan Lab :</b></label>
            <textarea name="catatan" class="form-control mb-2" id="" rows="4" required placeholder="Masukkan catatan lab..."></textarea>
            <button type="submit" name="simpan_catatan" class="btn btn-primary float-end"><i class="bi bi-save"></i> Simpan</button>
        </form>
    </div>
    <div class="card shadow p-2 mt-0">
        <h5 class="card-title">Riwayat Catatan Lab</h5>
        <div class="table-responsive">
            <table class="table table-hover table-striped table-sm" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pasien</th>
                        <th>No RM</th>
                        <th>Jadwal</th>
                        <th>Dibuat Oleh</th>
                        <th>Dibuat Pada</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($riwayat_catatan->num_rows > 0) {
                        $no = 1;
                        while ($row = $riwayat_catatan->fetch_assoc()) {
                    ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['nama_pasien']) ?></td>
                                <td><?= htmlspecialchars($row['no_rm']) ?></td>
                                <td><?= date('d-m-Y H:i', strtotime($row['jadwal'])) ?></td>
                                <td><?= htmlspecialchars($row['created_by']) ?></td>
                                <td><?= date('d-m-Y H:i:s', strtotime($row['created_at'])) ?></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewModal<?= $row['id'] ?>" title="Lihat Catatan">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>" title="Edit Catatan">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <a href="index.php?halaman=catatanlab&idrawat=<?= htmlspecialchars($_GET['idrawat']) ?>&hapus_catatan=<?= $row['id'] ?>"
                                        class="btn btn-sm btn-danger"
                                        title="Hapus Catatan"
                                        onclick="return confirm('Yakin ingin menghapus catatan ini?')">
                                        <i class="bi bi-trash-fill"></i>
                                    </a>
                                </td>
                            </tr>

                            <!-- Modal View Catatan -->
                            <div class="modal fade" id="viewModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-info text-white">
                                            <h5 class="modal-title" id="viewModalLabel">
                                                <i class="bi bi-journal-text"></i> Detail Catatan Lab
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <h6 class="text-muted mb-3"><i class="bi bi-person-badge"></i> Informasi Pasien:</h6>
                                                <div class="row g-2">
                                                    <div class="col-md-4">
                                                        <div class="p-2 bg-light rounded">
                                                            <small class="text-muted d-block">Nama Pasien</small>
                                                            <strong><?= htmlspecialchars($row['nama_pasien']) ?></strong>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="p-2 bg-light rounded">
                                                            <small class="text-muted d-block">No. RM</small>
                                                            <strong><?= htmlspecialchars($row['no_rm']) ?></strong>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="p-2 bg-light rounded">
                                                            <small class="text-muted d-block">Jadwal Rawat</small>
                                                            <strong><?= date('d-m-Y H:i', strtotime($row['jadwal'])) ?></strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="mb-3">
                                                <h6 class="text-muted mb-2"><i class="bi bi-journal-medical"></i> Catatan Lab:</h6>
                                                <div class="p-3 bg-light rounded border">
                                                    <?= nl2br(htmlspecialchars($row['catatan'])) ?>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <div class="p-2 bg-light rounded">
                                                        <small class="text-muted d-block">
                                                            <i class="bi bi-person-fill"></i> Dibuat Oleh
                                                        </small>
                                                        <strong><?= htmlspecialchars($row['created_by']) ?></strong>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="p-2 bg-light rounded">
                                                        <small class="text-muted d-block">
                                                            <i class="bi bi-calendar-check"></i> Tanggal Dibuat
                                                        </small>
                                                        <strong><?= date('d-m-Y H:i:s', strtotime($row['created_at'])) ?></strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="bi bi-x-circle"></i> Tutup
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning">
                                            <h5 class="modal-title" id="editModalLabel">
                                                <i class="bi bi-pencil-square"></i> Edit Catatan Lab
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form method="post">
                                            <div class="modal-body">
                                                <input type="hidden" name="id_catatan" value="<?= $row['id'] ?>">
                                                <div class="mb-3">
                                                    <label class="form-label"><b><i class="bi bi-journal-text"></i> Catatan Lab:</b></label>
                                                    <textarea name="catatan_edit" class="form-control" rows="6" required placeholder="Masukkan catatan lab..."><?= htmlspecialchars($row['catatan']) ?></textarea>
                                                </div>
                                                <div class="alert alert-info mb-0">
                                                    <small>
                                                        <i class="bi bi-info-circle-fill"></i>
                                                        <strong>Informasi:</strong><br>
                                                        Dibuat oleh: <?= htmlspecialchars($row['created_by']) ?> <br>
                                                        Pada: <?= date('d-m-Y H:i:s', strtotime($row['created_at'])) ?>
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="bi bi-x-circle"></i> Batal
                                                </button>
                                                <button type="submit" name="update_catatan" class="btn btn-primary">
                                                    <i class="bi bi-save"></i> Update
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                <i class="bi bi-inbox"></i> Belum ada catatan lab untuk pasien ini
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>