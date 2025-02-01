<?php
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $koneksi->query("DELETE FROM informasi WHERE id='$id'");
    echo "<script>alert('Data berhasil dihapus');</script>";    
    echo "<meta http-equiv='refresh' content='1;url=index.php?halaman=informasi'>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>KHM WONOREJO</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
    <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <link src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link src="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css">
</head>

<style>
    p {
        font-size: 12px;
    }
</style>

<body>
    <main>
        <div class="container">
            <div class="pagetitle">
                <h1>Daftar Informasi</h1>
            </div>
            <a href="index.php?halaman=tambahinformasi" class="btn btn-primary">
                + Tambah
            </a>
            <section class="section py-4">
                <div class="container">
                    <div class="row row-cols-md-2 row-cols-sm-2 row-cols-lg-3">
                        <?php
                        $get = $koneksi->query("SELECT * FROM informasi");
                        while ($pecah = $get->fetch_assoc()) {
                        ?>
                            <div class="col mb-4">
                                <div class="card">
                                    <div class="card-body p-2">
                                    <img src="assets/img/informasi/<?=$pecah['gambar'] ?>" class="card-img-top">
                                    <h5 class="card-title"><?= htmlspecialchars($pecah['judul']) ?></h5>
                                        <p><b>Dibuat Tanggal : <?= htmlspecialchars($pecah['created_at']) ?></b></p>
                                        <p><?= htmlspecialchars($pecah['detail']) ?></p>
                                        <div class="row">
                                            <div class="col-3"><a href="index.php?halaman=editinformasi&id=<?= $pecah['id'] ?>" class="btn btn-success w-100"><i class="bi bi-pen"></i></a></div>
                                            <div class="col-3">
                                                <form action="" method="post" style="display:inline;">
                                                    <input type="hidden" name="id" value="<?= $pecah['id'] ?>">
                                                    <button type="submit" name="delete" class="btn btn-danger w-100" onclick="return confirm('Apakah Anda yakin ingin menghapus item ini?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>

            </section>
        </div>
    </main><!-- End #main -->
    </div>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
</body>

</html>