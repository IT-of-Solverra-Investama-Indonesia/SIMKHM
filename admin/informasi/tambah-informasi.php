<?php
$user = $_SESSION['admin']['username'];
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
                <h1>Tambah Informasi</h1>
            </div><!-- End Page Title -->

            <form class="row" method="post" enctype="multipart/form-data">
                <div class="container">
                    <div class="row">
                        <!-- Multi Columns Form -->
                        <div class="col-md-12">
                            <label for="inputName5" class="form-label">Judul</label>
                            <input type="text" class="form-control" name="judul" value="" placeholder="Masukkan Judul Informasi/Promosi" style="width: 100%;">
                        </div>
                        <div class="col-md-12">
                            <label for="inputName5" style="margin-top: 10px;" class="form-label">Deskripsi</label>
                            <textarea rows="4" cols="50" class="form-control" name="desk" value="" placeholder="Masukkan Detail Informasi" style="width: 100%;"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="inputName5" style="margin-top: 10px;" class="form-label">Foto</label>
                            <input type="file" accept="image/*" class="form-control" name="gambar" style="width: 100%;">
                        </div>                        <div class="text-start mt-3">
                            <button class="btn btn-success" type="submit" name="save" id="checkBtn">Simpan</button>
                        </div>
                        <br>
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
if (isset($_POST['save'])) {
    $judul = $_POST['judul'];
    $desk = $_POST['desk'];
    $created_at = date('Y-m-d H:i:s');
    if (isset($_FILES['gambar'])) {
        $foto_tmp_name = $_FILES['gambar']['tmp_name'];
        $foto_nama = $_FILES['gambar']['name'];
        $foto_tipe = $_FILES['gambar']['type'];
        $foto_ext = pathinfo($foto_nama, PATHINFO_EXTENSION);
        $foto_nama_baru = uniqid() . '.' . $foto_ext;
        $folder_tujuan = "assets/img/informasi/";
        if (!is_dir($folder_tujuan)) {
            mkdir($folder_tujuan, 0777, true);
        }
        move_uploaded_file($foto_tmp_name, $folder_tujuan . $foto_nama_baru);
        $query = "INSERT INTO informasi (judul, detail, gambar, created_at) VALUES ('$judul', '$desk', '$foto_nama_baru', '$created_at')";
    } else {
        $query = "INSERT INTO informasi (judul, detail, created_at) VALUES ('$judul', '$desk', '$created_at')";
    }

    if ($koneksi->query($query) === TRUE) {
        echo "<script>alert('Data berhasil ditambahkan');</script>";
        echo "<meta http-equiv='refresh' content='1;url=index.php?halaman=informasi'>";
    } else {
        echo "Error: " . $query . "<br>" . $koneksi->error;
    }
}
?>
