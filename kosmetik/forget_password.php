<?php
    include "function.php";
    include '../admin/rawatjalan/api_token_wa.php';
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  </head>
  <body>
    <div class="container-fluid vh-100 d-flex justify-content-center align-items-center">
        <div class="card shadow" style="max-width: 600px; min-width: 300px;">
            <div class="card-body">
                <?php if(!isset($_GET['yt6069958218b765b0c16629b4b7f6ddfcytgh'])){?>
                    <?php if(!isset($_GET['noHp'])){?>
                        <h5 class="card-title">Lupa Password ???</h5>
                        <p class="card-text text-capitalize" style="font-size: 13px;">Masukan Nomer Hp Anda dan kami akan mengirim link untuk mengubah password anda</p>
                        <form method="POST">
                            <input type="number" name="nohp" class="form-control mb-2" placeholder="Contoh : 6281xxxxxx" required>
                            <button name="kirimLink"  class="btn float-end btn-success">Kirim Link Reset Passoword</button>
                        </form>
                    <?php }else{?>
                        <?php
                            $hp_mod = substr(htmlspecialchars($_GET['noHp']), 2);        
                            $cek = $koneksi->query("SELECT *, COUNT(*) as jum FROM pasien_kosmetik WHERE SUBSTRING(nohp, 2) = '$hp_mod' LIMIT 1")->fetch_assoc();
                        ?>
                        <?php if($cek['jum'] == 1){?>
                            <h5 class="card-title">
                                Apakah Ini Anda ???
                            </h5>
                            <p class="card-text text-capitalize" style="font-size: 13px;">
                                Kami Menemukan <b><?= $cek['nama_lengkap']?></b>, Apakah benar anda bernama <b><?= $cek['nama_lengkap']?></b> ???
                            </p>
                            <form method="POST">
                                <input type="text" name="id" value="<?= $cek['idpasien']?>" hidden id="">
                                <div class="row">
                                    <div class="col-6">
                                        <a href="forget_password.php" class="btn btn-secondary w-100">Bukan</a>
                                    </div>
                                    <div class="col-6">
                                        <button name="send" class="btn btn-success w-100">Ya Benar</button>
                                    </div>
                                </div>
                            </form>
                        <?php }else{?>
                            <h5 class="card-title">Tidak Ditemukan</h5>
                            <p class="card-text text-capitalize" style="font-size: 13px;">Mohon maaf untuk saat ini kami tidak menemukan user dengan nomor hp <?= htmlspecialchars(htmlspecialchars($_GET['noHp']))?></p>
                            <a href="forget_password.php" class="btn btn-success w-100">Nomor Lanya</a>
                        <?php }?>
                    <?php }?>
                <?php }else{?>
                    <?php 
                        $cek = $koneksi->query("SELECT * FROM pasien_kosmetik WHERE idpasien = '".htmlspecialchars($_GET['resetpassword'])."' LIMIT 1")->fetch_assoc();    
                    ?>
                    <h5 class="card-title">Password Baru</h5>
                    <p class="card-text text-capitalize" style="font-size: 13px;">Demi Melindungi hak dan privasi pengguna, silahkan ubah password anda <b><?= $cek['nama_lengkap']?></p></b>
                    <form method="POST">
                        <div class="input-group mb-3">
                          <input type="password" class="form-control mb-2" name="password" id="password" placeholder="Password" aria-label="Recipient's password" aria-describedby="button-addon2">
                          <button class="btn h-100 btn-outline-success" type="button" id="button-addon2" onclick="togglePassword()"><i class="bi bi-eye" id="toggleIcon"></i></button>
                        </div>
                        <button name="ubahPassword" class="btn btn-success w-100">Ubah Password</button>
                    </form>
                <?php }?>
            </div>
        </div>
    </div>
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            toggleIcon.classList.toggle('bi-eye');
            toggleIcon.classList.toggle('bi-eye-slash');
        }
    </script>
    <?php 
        if(isset($_POST['kirimLink'])){
            $hp = htmlspecialchars($_POST['nohp']);
            echo"
                <script>
                    document.location.href='forget_password.php?noHp=$hp';
                </script>
            ";
        }

        if(isset($_POST['send'])){
            $hp = htmlspecialchars(htmlspecialchars($_GET['noHp']));
            $curl = curl_init();
            $phone = $hp;
            $message = urlencode("Klik Link Berikut Untuk Melakukan Perubahan Pada Password Anda : https://simkhm.id/wonorejo/kosmetik/forget_password?yt6069958218b765b0c16629b4b7f6ddfcytgh&resetpassword=".htmlspecialchars($_POST['id'])."&yt6069958218b765b0c16629b4b7f6ddfcyt");

            curl_setopt($curl, CURLOPT_URL, "https://jogja.wablas.com/api/send-message?phone=$phone&message=$message&token=$token");
            $result = curl_exec($curl);
            curl_close($curl);

            echo"
                <script>
                    alert('Link Reset Password Telah Terkirim ke WhatsApp Anda, Silahkan Reset Password Anda Dengan Link Yang Sudah di Sediakan');
                    document.location.href='login.php';
                </script>
            ";
        } 
        
        if(isset($_POST['ubahPassword'])){
            $koneksi->query("UPDATE pasien_kosmetik SET password = '".htmlspecialchars($_POST['password'])."' WHERE idpasien = '".htmlspecialchars($_GET['resetpassword'])."'");
            echo"
                <script>
                    alert('Password Berhasil Di Ubah, Silahkan Login Kembali');
                    document.location.href='login.php';
                </script>
            ";
        }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>