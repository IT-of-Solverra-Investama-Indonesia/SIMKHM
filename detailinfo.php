<?php
$id = $_GET['id'] ? ($_GET['id']) : null;
include 'wonorejo/admin/dist/function.php';
if ($id) {
    $get = $koneksi->query("SELECT * FROM informasi WHERE id='$id'");

    if ($get && $get->num_rows > 0) {
        $pecah = $get->fetch_assoc();
    } else {
        echo "Informasi tidak ditemukan.";
        exit();
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="pasien/assets/img/khm.png">

    <title>SIMKHM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- bikin icon -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />

    <div class="p-5">
        <center>
            <h3 class="mb-4" style="margin-top: 0px;"><?= htmlspecialchars($pecah['judul']) ?></h3>
        <img src="wonorejo/admin/dist/assets/img/informasi/<?= htmlspecialchars($pecah['gambar']) ?>" style="max-width: 100%; height: auto;">        </center>
        <p><?= nl2br(htmlspecialchars($pecah['detail'])) ?></p>
        <p><b>Dibuat pada : <?=htmlspecialchars($pecah['created_at'])?></b></p>
    </div>
