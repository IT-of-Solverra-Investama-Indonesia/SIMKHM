<?php
$username = $_SESSION['admin']['namalengkap'];
$username_login = $_SESSION['admin']['username'];
$password_login = $_SESSION['admin']['password'];
$level = $_SESSION['admin']['level'];
?>

<div class="container py-4">

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-body">
                    <h3 class="card-title mb-4 text-center">Profile</h3>

                    <div class="mb-3 row">
                        <label for="namaLengkap" class="col-sm-3 col-form-label fw-semibold">Nama Lengkap</label>
                        <div class="col-sm-9">
                            <input type="text" id="namaLengkap" class="form-control" value="<?= htmlspecialchars($username); ?>" disabled>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="username" class="col-sm-3 col-form-label fw-semibold">Username</label>
                        <div class="col-sm-9">
                            <input type="text" id="username" class="form-control" value="<?= htmlspecialchars($username_login); ?>" disabled>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="password" class="col-sm-3 col-form-label fw-semibold">Password</label>
                        <div class="col-sm-9">
                            <input type="text" id="password" class="form-control" value="<?= htmlspecialchars($password_login); ?>" disabled>
                        </div>
                    </div>

                    <div class="mb-4 row">
                        <label for="level" class="col-sm-3 col-form-label fw-semibold">Level</label>
                        <div class="col-sm-9">
                            <input type="text" id="level" class="form-control" value="<?= htmlspecialchars($level); ?>" disabled>
                        </div>
                    </div>

                    <div class="text-end">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">
                            Edit Username & Password
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Username & Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="newUsername" class="form-label">Username Baru</label>
                        <input type="text" class="form-control" id="newUsername" name="username" value="<?= htmlspecialchars($username_login); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">Password Baru</label>
                        <input type="text" class="form-control" id="newPassword" name="password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
if (isset($_POST['username']) && isset($_POST['password'])) {
    $new_username = $_POST['username'];
    $new_password = $_POST['password'];

    $stmt = $koneksi->prepare("SELECT COUNT(*) FROM admin WHERE username = ? AND idadmin != ?");
    $stmt->bind_param("si", $new_username, $_SESSION['admin']['idadmin']);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo "<script>alert('Username sudah terpakai. Silakan gunakan username lain.');</script>";
    } else {
        $stmt = $koneksi->prepare("UPDATE admin SET username = ?, password = ? WHERE idadmin = ?");
        $stmt->bind_param("ssi", $new_username, $new_password, $_SESSION['admin']['idadmin']);

        if ($stmt->execute()) {
            $_SESSION['admin']['username'] = $new_username;
            $_SESSION['admin']['password'] = $new_password;
            echo "<script>alert('Profile updated successfully!');</script>";
            echo "<script>window.location.href = 'index.php?halaman=profile';</script>";
        } else {
            echo "<script>alert('Failed to update profile. Please try again.');</script>";
        }
        $stmt->close();
    }
}
$koneksi->close();
?>