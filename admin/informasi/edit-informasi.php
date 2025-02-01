<?php
$user = $_SESSION['admin']['username'];
$id = $_GET['id'];
$get = $koneksi->query("SELECT * FROM informasi WHERE id='$id'");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>INFORMASI</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script rel="javascript" type="text/javascript" href="js/jquery-1.11.3.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
</head>

<body>
    <main>
        <div class="container">
            <div class="pagetitle">
                <h1>Edit Informasi</h1>
            </div><!-- End Page Title -->

            <form class="row" method="post" enctype="multipart/form-data">
                <div class="container">
                    <div class="row">
                        <!-- Multi Columns Form -->
                        <?php while ($pecah = $get->fetch_assoc()) { ?>
                            <input type="hidden" name="id" value="<?= $pecah['id'] ?>">
                            <div class="col-md-12">
                                <label for="inputName5" class="form-label">Judul</label>
                                <input type="text" class="form-control" name="judul" value="<?= $pecah['judul'] ?>" style="width: 100%">
                            </div>
                            <div class="col-md-12">
                                <label for="inputName5" style="margin-top: 10px;" class="form-label">Deskripsi</label>
                                <textarea rows="4" cols="50" class="form-control" name="desk" value="" style="width: 100%"><?= $pecah['detail'] ?></textarea>
                            </div>
                            <div class="col-md-12">
                                <label for="inputName5" style="margin-top: 10px;" class="form-label">Foto</label>
                                <input type="file" accept="image/*" class="form-control" name="gambar" style="width: 100%">
                                <img src="assets/img/informasi/<?= htmlspecialchars($pecah['gambar']) ?>" style="margin-top: 10px; max-width: 300px;" name="foto_lama"><p>foto saat ini</p>
                            </div>
                            <div class="text-start mt-3">
                                <button class="btn btn-success" type="submit" name="update" id="checkBtn">Update</button>
                            </div>
                            <br>
                        <?php } ?>
            </form><!-- End Multi Columns Form -->
        </div>
    </main>
</body>


<style>
    table,
    th,
    td {
        border: 1px solid black;
        border-collapse: collapse;
    }
</style>

<?php
if (isset($_POST['update'])) {
    $judul = $_POST['judul'];
    $desk = $_POST['desk'];
    $id = $_GET['id'];

    $foto_baru = '';
    if (!empty($_FILES['gambar']['name'])) {
        $foto_lama = $_POST['foto_lama'];

        if (!empty($foto_lama)) {
            $path_lama = "assets/img/informasi/" . $foto_lama;
            if (file_exists($path_lama)) {
                unlink($path_lama);
            }
        }

        $foto_baru = $_FILES['gambar']['name'];
        $tmp_name = $_FILES['gambar']['tmp_name'];
        $foto_ext = pathinfo($foto_baru, PATHINFO_EXTENSION);
        $foto_nama_baru = uniqid() . '.' . $foto_ext;
        $folder_tujuan = "assets/img/informasi/";
        
        if (!is_dir($folder_tujuan)) {
            mkdir($folder_tujuan, 0777, true);
        }

        move_uploaded_file($tmp_name, $folder_tujuan . $foto_nama_baru);
        $foto_baru = $foto_nama_baru;
    } else {
        $foto_baru = $_POST['foto_lama'];
    }

    $query = "UPDATE informasi SET judul='$judul', detail='$desk', gambar='$foto_baru' WHERE id='$id'";
    if ($koneksi->query($query) === TRUE) {
        echo "<script>alert('Data berhasil diupdate');</script>";
        echo "<meta http-equiv='refresh' content='1;url=index.php?halaman=informasi'>";
    } else {
        echo "Error: " . $koneksi->error;
    }
}
?>

