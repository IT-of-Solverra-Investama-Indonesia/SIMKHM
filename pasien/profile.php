<?php 
  error_reporting(0);
  session_start();
  if (!isset($_SESSION['pasien']['nama_lengkap'])) {
      header("Location: login.php");
      exit();
  }
  include "function.php";
  // Sanitize session variables
  $nohp = sani($_SESSION['pasien']['nohp']);
  $password = sani($_SESSION['pasien']['password']);
  $stmt = $koneksi->prepare("SELECT * FROM pasien WHERE nohp = ? AND password = ? LIMIT 1");
  $stmt->bind_param("ss", $nohp, $password);
  $stmt->execute();
  $getPasien = $stmt->get_result()->fetch_assoc();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Klinik Husada Mulia</title>
    <link rel="icon" type="image/png" href="../admin/dist/assets/img/khm.png" />
    <style>
      .hide-form {
        visibility : hidden;
        max-height: 0.1px;
        overflow : hidden;
      }
    </style>
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> -->
  </head>
  <body>
    <br>
    <div class="container">
        <h3>Profile <?= $_SESSION['pasien']['nama_lengkap']?></h3>
        <br>
        <br>
        <div class="card shadow p-3">
            <h5><b>Update Profile</b></h5>
            <form method="POST">
                <label>Nomor HP</label>
                <input value="<?= $getPasien['nohp']?>" type="text" class="form-control w-100 mb-2" name="nohp" placeholder="Masukkan Nomor Hp"> 
                <label>Password Baru</label>
                <input type="password" class="form-control w-100 mb-2" name="password" placeholder="Masukkan Password Baru">
                <button type="submit" class="btn btn-success float-end" name="update">Update Profile</button>
                <a href="menupasien.php" class="btn btn-dark" style="float: right; margin-right:5px">Batal</a>
            </form>
        </div>
    </div>
    <?php 
        if(isset($_POST['update'])){
            $nohp = sani($_POST['nohp']);
            $password_plain = sani($_POST['password']);
            $password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);

            $stmt = $koneksi->prepare("UPDATE pasien SET nohp = ?, password = ? WHERE idpasien = ?");
            $stmt->bind_param("ssi", $nohp, $password_hashed, $getPasien['idpasien']);
            $stmt->execute();

            echo  "
          <script>
              alert('Update Berhasil');
              document.location.href='menupasien.php';
          </script>
            ";
            $_SESSION['pasien']['password'] = $password_hashed;
        }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
  </body>
</html>