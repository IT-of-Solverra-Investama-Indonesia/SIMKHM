<?php session_start(); ?>

<?php

include "function.php";
if (isset($_SESSION['kosmetik']['nama_lengkap'])) {
  header("Location: index.php?halaman=shop");
  exit();
}
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
      visibility: hidden;
      max-height: 0.1px;
      overflow: hidden;
    }
  </style>
  <link href="../pasien/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../pasien/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../pasien/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../pasien/assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../pasien/assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../pasien/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../pasien/assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <link href="../pasien/assets/css/style.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> -->
</head>

<body>
  <div class="container">
    <center>
      <div style="max-width: 500px;" class="m-auto mt-4">
        <img src="../admin/dist/assets/img/khm.png" class="mb-4" style="width: 30%;" alt="">
        <?php if (!isset($_GET['pasien'])) { ?> <!-- Halaman Awal memilih sebagai pasien lama atau baru -->
          <!-- <a href="?pasien=lama" class="btn btn-success mb-3 w-100">Pasien Lama</a>
                <a href="?pasien=baru" class="btn btn-success w-100">Pasien Baru</a> -->
          <div class="card shadow p-2 w-100">
            <h5><b>Login</b></h5>
            <p class="mt-0 mb-1" style="font-size: 12px;">Login Menggunakan Email dan Password yang Sudah Didaftarkan</p>
            <br>
            <form method="post">
              <p class="mb-0" align="left">Email :</p>
              <input type="email" class="form-control w-100 mb-2" name="email" placeholder="Email">
              <p class="mb-0" align="left">Password :</p>
              <div class="input-group mb-3">
                <input type="password" class="form-control mb-2" name="password" id="password" placeholder="Password" aria-label="Recipient's password" aria-describedby="button-addon2">
                <button class="btn h-100 btn-outline-success" type="button" id="button-addon2" onclick="togglePassword()"><i class="bi bi-eye" id="toggleIcon"></i></button>
              </div>
              <p class="m-0" align="left" style="font-size: 11px; text-decorative: bold; ">
                <a href="forget_password.php" style="text-decoration: none;">Lupa Password ??</a><br><br>
                <a href="../pasien/login.php" class="text-blue" style="text-decoration: underline blue;"><b>Klik Untuk Pendaftaran Berobat Online</b></a>
              </p>
              <a href="?pasien=baru" class="btn btn-sm btn-success float-end ms-2">Register</a>
              <button type="submit" name="check" class="btn btn-sm btn-success float-end ">Login</button>
            </form>
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
          if (isset($_POST['check'])) {
            $email = sani($_POST['email']);
            $password = sani($_POST['password']);
            $getPasien = $koneksi->prepare("SELECT * FROM pasien_kosmetik WHERE email = ? LIMIT 1");
            $getPasien->bind_param("s", $email);
            $getPasien->execute();
            $result = $getPasien->get_result();
            if ($result->num_rows > 0) {
              $row = $result->fetch_assoc();
              if ($password === $row['password']) {
                $_SESSION['kosmetik'] = $row;
                echo  "
                        <script>
                          alert('Berhasil Login!');
                          document.location.href='index.php?halaman=shop';
                        </script>
                        ";
              } else {
                echo  "
                        <script>
                          alert('Username Dan Sandi Salah');
                          document.location.href='login.php';
                        </script>
                        ";
              }
            } else {
              echo  "
                      <script>
                        alert('Username Dan Sandi Salah');
                        document.location.href='login.php';
                      </script>
                      ";
            }
            $getPasien->close();
          }
          ?>

        <?php } else { ?> <!-- Jika Sudah memilih -->
          <?php if ($_GET['pasien'] == 'lama') { ?><!-- jika memilih sebagai pasien lama -->
            <script>
              document.location.href = 'login.php';
            </script>
          <?php } elseif ($_GET['pasien'] == 'baru') { ?><!-- jika memilih sebagai pasien baru -->
            <div class="card shadow p-2">
              <h4>Daftar Pasien</h4>
              <form method="POST">
                <div class="row" id="f1">
                  <div class="col-md-12 mb-2">
                    <p class="mb-0" align="left">Email</p>
                    <input type="email" required class="form-control" name="email" id="inputName5" placeholder="Masukan Email Anda">
                  </div>
                  <div class="col-md-12 mb-2">
                    <p class="mb-0" align="left">Password</p>
                    <input type="password" required class="form-control" name="password" id="inputName5" placeholder="Password">
                  </div>
                  <div class="col-md-12 mb-2">
                    <p class="mb-0" align="left">Nama Lengkap</p>
                    <input type="text" required class="form-control" name="nama_lengkap" id="inputName5" placeholder="Masukkan Nama Pasien">
                  </div>
                  <!-- <div class="col-md-12 mb-2">
                          <p class="mb-0" align="left">Nama Ayah</p>
                          <input type="text" required class="form-control"  name="nama_ibu" id="inputName5" placeholder="Masukkan Nama Ayah Pasien">
                        </div>  -->
                  <div class="col-md-12 mb-2">
                    <p class="mb-0" align="left">Tanggal Lahir</p>
                    <div class="row">
                      <div class="col-4">
                        <select name="tanggal" class="form-control mb-2">
                          <option hidden>Tanggal</option>
                          <?php for ($i = 1; $i <= 31; $i++) { ?>
                            <?php if ($i < 10) { ?>
                              <option value="0<?= $i ?>">0<?= $i ?></option>
                            <?php } else { ?>
                              <option value="<?= $i ?>"><?= $i ?></option>
                            <?php } ?>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="col-3">
                        <select name="bulan" class="form-control mb-2">
                          <option hidden>Bulan</option>
                          <?php for ($i = 1; $i <= 12; $i++) { ?>
                            <?php if ($i < 10) { ?>
                              <option value="0<?= $i ?>">0<?= $i ?></option>
                            <?php } else { ?>
                              <option value="<?= $i ?>"><?= $i ?></option>
                            <?php } ?>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="col-5">
                        <input type="number" name="tahun" class="form-control mb-2" placeholder="Tahun">
                      </div>
                    </div>
                    <!-- <input type="date" required class="form-control" name="tgl_lahir" id="inputName5" placeholder="Masukkan Nama Pasien"> -->
                  </div>
                  <!-- <div class="col-md-12 mb-2">
                          <p class="mb-0" align="left">Jenis Kelamin</p>
                          <select id="inputState" name="jenis_kelamin" required class="form-select">
                            <option selected>Pilih</option>
                            <option value="1">Laki-Laki</option>
                            <option value="2">Perempuan</option>
                        
                          </select>
                        </div>
                      </div> -->
                  <div class="col-md-12 mb-2">
                    <p class="mb-0" align="left" for="inputCity" required class="form-label">No. HP </p>
                    <input type="text" name="nohp" required class="form-control" id="inputCity" placeholder="Masukkan No. HP Pasien">
                  </div>
                  <!-- <div class="row hide-form" id="f2">
                        <div class="col-md-12 mb-2">
                          <p class="mb-0" align="left" for="inputCity" required class="form-label">No. HP </p>
                          <input type="text" name="nohp" required class="form-control" id="inputCity" placeholder="Masukkan No. HP Pasien">
                        </div> 
                        <div class="col-md-12 mb-2">
                          <p class="mb-0" align="left" for="inputCity" required class="form-label">No. KTP</p>
                          <input type="number" name="no_identitas" required class="form-control" id="inputCity" placeholder="Masukkan No. Identitas Pasien">
                        </div>
                        <div class="col-md-12 mb-2">
                          <p class="mb-0" align="left" for="inputState" required class="form-label">Provinsi</p>
                          <select id="provinsi" required class="form-select">
                            <option hidden Selected>Pilih</option>
                          </select>
                          <input type="text" hidden id="provins" name="provinsi">
                        </div>
                        <div class="col-md-12 mb-2">
                          <p class="mb-0" align="left" for="inputState" required class="form-label">Kota/Kabupaten</p>
                          <select id="kota" required class="form-select">
                          <option hidden Selected>Pilih</option>
                          </select>
                          <input type="text" hidden id="kot" name="kota">
                        </div>
                        <div class="col-md-12 mb-2">
                          <p class="mb-0" align="left" for="inputState" required class="form-label">Kecamatan</p>
                          <select id="kecamatan" required class="form-select">
                          <option hidden Selected>Pilih</option>
                          </select>
                          <input type="text" hidden id="kecamata" name="kecamatan">
                        </div>
                        <div class="col-md-12 mb-2">
                          <p class="mb-0" align="left" for="inputState" required class="form-label">Desa/Kelurahan</p>
                          <select id="kelurahan" required class="form-select">
                          <option hidden Selected>Pilih</option>
                          </select>
                          <input type="text" hidden id="keluraha" name="kelurahan">
                        </div>
                        <div class="col-md-12 mb-2">
                          <p class="mb-0" align="left" for="inputCity" required class="form-label">Kode Pos</p>
                          <input type="text" name="kode_pos" id="kode_pos" required class="form-control"  placeholder="Masukkan kode pos">
                        </div>
                        <div class="col-md-12 mb-2">
                          <p class="mb-0" align="left" for="inputCity" required class="form-label">Alamat Rumah (Dusun, RT, RW)</p>
                          <textarea type="text" name="alamat" required class="form-control" style="height: 100px;" id="alamatAsal" value="" placeholder="Masukkan alamat"></textarea>
                        </div>
                        <div class="col-md-12 mb-2">
                          <p align="left" class="mb-0" for="inputCity" required class="form-label my-0">Alamat Domisili Sekarang</p>
                          <p align="left" class="mb-0">
                            <span type="button" onclick="samakanAlamat()" class="btn btn-sm btn-primary mb-2">Samakan dengan Alamat Asal</span>
                          </p>
                          <textarea type="text" id="alamatDomisili" name="alamat_dom" required class="form-control" style="height: 100px;" value="" placeholder="Masukkan Alamat Domisili Sekarang"></textarea>
                        </div>
                        <div class="col-md-12 mb-2 hide-form">
                          <p align="left" class="mb-0" for="inputState" required class="form-label">Nomor BPJS</p>
                          <input type="text" required class="form-control" name="no_bpjs" value="-">
                        </div>
                      </div> -->
                  <!-- <div class="btn-group mb-3 mt-2" role="group" aria-label="Basic mixed styles example">
                        <button type="button" onclick="changeForm()" class="btn btn-success" id="btn-nav"></button>
                      </div> -->
                  <div id="btn-sub" class="">
                    <p align="left" style="font-size: 12px;">
                      <a href="login.php">Sudah Punya Akun ?</a><br>
                    </p>
                    <button type="submit" name="login" class="btn btn-success w-50 ">Ajukan Pendaftaran</button>
                  </div>
              </form>
            </div>
            <!-- <script>
                    function samakanAlamat() {
                      document.getElementById("alamatDomisili").innerHTML='Provinsi '+document.getElementById("provins").value +', '+'Kabupaten/Kota '+document.getElementById("kot").value +', '+'Kecamatan '+document.getElementById("kecamata").value +', '+ 'Desa/Kelurahan '+document.getElementById("keluraha").value +', '+ document.getElementById("alamatAsal").value +', '+ document.getElementById("kode_pos").value  ;
                    }
                  </script>
                  <script>
                      var nav = document.getElementById("btn-nav");
                      nav.innerHTML = "<i class='bi bi-arrow-right-circle'></i> Selanjutnya";

                    function changeForm(){
                      var form1 = document.getElementById("f1");
                      var form2 = document.getElementById("f2");
                      var sub = document.getElementById("btn-sub");

                      if(form2.classList.contains("hide-form")){
                        form1.classList.add('hide-form');
                        form2.classList.remove('hide-form');
                        nav.innerHTML = " <i class='bi bi-arrow-left-circle'></i> Sebelumnya";
                        sub.classList.remove("hide-form");
                      }else{
                        form1.classList.remove('hide-form');
                        form2.classList.add('hide-form');
                        nav.innerHTML = "<i class='bi bi-arrow-right-circle'></i> Selanjutnya";
                        sub.classList.add("hide-form");

                      }
                    }
                  </script>
                  <script>
                    var selectProvinsi = document.getElementById("provinsi");
                    // Lakukan permintaan HTTP untuk mendapatkan data propinsi dari API
                    fetch("https://kodepos-2d475.firebaseio.com/list_propinsi.json?print=pretty")
                      .then(response => response.json())
                      .then(data => {
                        // Data propinsi telah diterima, lakukan iterasi untuk membuat elemen option
                        for (var propinsiCode in data) {
                          if (data.hasOwnProperty(propinsiCode)) {
                            var propinsiName = data[propinsiCode];
                    
                            // Membuat elemen option
                            var optionElement = document.createElement("option");
                            optionElement.value = propinsiCode; // Nilai option sesuai dengan kode propinsi
                            optionElement.text = propinsiName;  // Teks yang akan ditampilkan pada option
                    
                            // Menambahkan elemen option ke dalam elemen select
                            selectProvinsi.appendChild(optionElement);
                          }
                        }
                      })
                      .catch(error => {
                        console.error("Error fetching propinsi data:", error);
                      });
                    // Mendapatkan referensi ke elemen select
                    var selectProvinsi = document.getElementById("provinsi");
                    var selectKota = document.getElementById("kota");
                    var selectKecamatan = document.getElementById("kecamatan");
                    var selectKelurahan = document.getElementById("kelurahan");
                    var inputProvinsi = document.getElementById("provins");
                    var inputKota = document.getElementById("kot");
                    var inputKecamatan = document.getElementById("kecamata");
                    var inputKelurahan = document.getElementById("keluraha");
                    var inputKodePos = document.getElementById("kode_pos");
                    
                    // Function untuk membuat elemen option
                    function createOption(value, text) {
                      var optionElement = document.createElement("option");
                      optionElement.value = value;
                      optionElement.text = text;
                      return optionElement;
                    }
                    
                    // Function untuk mengambil data kota dari API berdasarkan provinsi yang dipilih
                    function updateKotaList(provinsiCode) {
                      // Lakukan permintaan HTTP untuk mendapatkan data kota dari API
                      fetch(`https://kodepos-2d475.firebaseio.com/list_kotakab/${provinsiCode}.json?print=pretty`)
                        .then(response => response.json())
                        .then(data => {
                          // Hapus opsi yang ada sebelumnya
                          selectKota.innerHTML = "";
                    
                          // Tambahkan opsi baru berdasarkan data kota yang diterima
                          for (var kotaCode in data) {
                            if (data.hasOwnProperty(kotaCode)) {
                              var kotaName = data[kotaCode];
                              selectKota.appendChild(createOption(kotaCode, kotaName));
                            }
                          }
                    
                          // Panggil fungsi untuk memperbarui kecamatan
                          updateKecamatanList();
                        })
                        .catch(error => {
                          console.error("Error fetching kota data:", error);
                        });
                    }
                    
                    // Function untuk mengambil data kecamatan dan kelurahan dari API berdasarkan kota yang dipilih
                    // Function untuk mengambil data kecamatan dari API berdasarkan kota yang dipilih
                    function updateKecamatanList() {
                      // Mendapatkan nilai kota yang dipilih
                      var selectedKota = selectKota.value;
                    
                      // Lakukan permintaan HTTP untuk mendapatkan data kecamatan dan kelurahan dari API
                      fetch(`https://kodepos-2d475.firebaseio.com/kota_kab/${selectedKota}.json?print=pretty`)
                        .then(response => response.json())
                        .then(data => {
                          // Hapus opsi yang ada sebelumnya
                          selectKecamatan.innerHTML = "";
                          selectKelurahan.innerHTML = "";
                    
                          // Buat objek untuk menyimpan kecamatan yang unik
                          var kecamatanSet = new Set();
                    
                          // Tambahkan kecamatan ke objek set
                          data.forEach(entry => {
                            kecamatanSet.add(entry.kecamatan);
                          });
                    
                          // Tambahkan opsi baru ke dalam elemen select untuk kecamatan
                          kecamatanSet.forEach(kecamatan => {
                            selectKecamatan.appendChild(createOption(kecamatan, kecamatan));
                          });
                        })
                        .catch(error => {
                          console.error("Error fetching kecamatan data:", error);
                        });
                    }
                    // Function untuk mengambil data kelurahan dari API berdasarkan kecamatan yang dipilih
                    function updateKelurahanList() {
                      // Mendapatkan nilai kecamatan yang dipilih
                      var selectedKecamatan = selectKecamatan.value;
                    
                      // Mendapatkan nilai kota yang dipilih
                      var selectedKota = selectKota.value;
                    
                      // Lakukan permintaan HTTP untuk mendapatkan data kelurahan dari API
                      fetch(`https://kodepos-2d475.firebaseio.com/kota_kab/${selectedKota}.json?print=pretty`)
                        .then(response => response.json())
                        .then(data => {
                          // Hapus opsi yang ada sebelumnya
                          selectKelurahan.innerHTML = "";
                    
                          // Filter data berdasarkan kecamatan yang dipilih
                          var filteredData = data.filter(entry => entry.kecamatan === selectedKecamatan);
                    
                          // Tambahkan opsi baru ke dalam elemen select untuk kelurahan
                          filteredData.forEach(entry => {
                            var option = createOption(entry.kelurahan, entry.kelurahan);
                            selectKelurahan.appendChild(option);
                          });
                        })
                        .catch(error => {
                          console.error("Error fetching kelurahan data:", error);
                        });
                    }
                    
                    // Menambahkan event listener untuk elemen provinsi
                    selectProvinsi.addEventListener("change", function () {
                      // Mendapatkan nilai provinsi yang dipilih
                      var selectedProvinsi = selectProvinsi.value;
                      var selectedProvinsii = selectProvinsi.options[selectProvinsi.selectedIndex].text;
                      inputProvinsi.value=selectedProvinsii;
                      // Memanggil fungsi untuk memperbarui daftar kota berdasarkan provinsi yang dipilih
                      updateKotaList(selectedProvinsi);
                    });
                    
                    // Menambahkan event listener untuk elemen kota
                    selectKota.addEventListener("change", function () {
                      // Memanggil fungsi untuk memperbarui daftar kecamatan dan kelurahan berdasarkan kota yang dipilih
                      var selectedKotaa = selectKota.options[selectKota.selectedIndex].text;
                      inputKota.value=selectedKotaa;
                      updateKecamatanList();
                    });
                    // Menambahkan event listener untuk elemen kecamatan
                    selectKecamatan.addEventListener("change", function () {
                      // Memanggil fungsi untuk memperbarui daftar kelurahan berdasarkan kecamatan yang dipilih
                      var selectedKecamatann = selectKecamatan.options[selectKecamatan.selectedIndex].text;
                      inputKecamatan.value=selectedKecamatann;
                      updateKelurahanList();
                    });
                    selectKelurahan.addEventListener("change", function () {
                      // Mendapatkan nilai kelurahan yang dipilih
                      var selectedKelurahan = selectKelurahan.value;
                    
                      // Mendapatkan nilai kota yang dipilih
                      var selectedKota = selectKota.value;
                      var selectedKelurahann = selectKelurahan.options[selectKelurahan.selectedIndex].text;
                      inputKelurahan.value=selectedKelurahann;
                      // Lakukan permintaan HTTP untuk mendapatkan kode pos berdasarkan kelurahan yang dipilih
                      fetch(`https://kodepos-2d475.firebaseio.com/kota_kab/${selectedKota}.json?print=pretty`)
                        .then(response => response.json())
                        .then(data => {
                          // Temukan data yang sesuai dengan kelurahan yang dipilih
                          var kodePosData = data.find(entry => entry.kelurahan === selectedKelurahan);
                    
                          // Tampilkan kode pos di elemen input kode_pos
                          if (kodePosData) {
                            inputKodePos.value = kodePosData.kodepos;
                          } else {
                            console.error("Kode pos not found for selected kelurahan.");
                          }
                        })
                        .catch(error => {
                          console.error("Error fetching kode pos data:", error);
                        });
                    });
                  </script> -->
            <?php
            if (isset($_POST['login'])) {
              $nama_lengkap = sani($_POST["nama_lengkap"]);
              // $nama_ibu = sani($_POST["nama_ibu"]);
              $nohp = sani($_POST["nohp"]);
              $tanggal = sani($_POST['tanggal']);
              $bulan = sani($_POST['bulan']);
              $tahun = sani($_POST['tahun']);
              $tgl_lahir = date('Y-m-d', strtotime($tanggal . '-' . $bulan . '-' . $tahun));
              // $tgl_lahir = sani($_POST["tgl_lahir"]);
              $jenis_identitas = 'KTP';
              // $no_identitas = sani($_POST["no_identitas"]);
              // $jenis_kelamin = sani($_POST["jenis_kelamin"]);
              // $provinsi = sani($_POST["provinsi"]);
              // $kota = sani($_POST["kota"]);
              // $kelurahan = sani($_POST["kelurahan"]);
              // $kecamatan = sani($_POST["kecamatan"]);
              // $kode_pos = sani($_POST["kode_pos"]);
              // $alamat = sani($_POST["alamat"]);
              $email = sani($_POST["email"]);
              $password = sani($_POST["password"]);
              $kategori = '';
              $pembiayaan = '';
              $status_nikah = '';
              $foto = '';

              // Ambil Nomor RM Terakhir + 1
              $no_rm = '';

              // hitung usia
              $lahir = new DateTime($tgl_lahir);
              $today = new DateTime();
              $umur = $today->diff($lahir);
              $umur2 = $umur->y . " Tahun," . $umur->m . " Bulan," . $umur->d . " Hari";

              $stmt = $koneksi->prepare("INSERT INTO pasien_kosmetik (nama_lengkap, nohp, email, tgl_lahir, jenis_identitas, umur, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
              $stmt->bind_param("sssssss", $nama_lengkap, $nohp, $email, $tgl_lahir, $jenis_identitas, $umur2, $password);
              $stmt->execute();
              $stmt->close();

              echo  "
                          <script>
                            alert('Yeayyy, Akun sudah terdaftar, silahkan login menggunakan email dan password yang sudah di daftarkan');
                            document.location.href='login.php';
                          </script>
                        ";
            }
            ?>
          <?php } ?>
        <?php } ?>
      </div>
    </center>
    <br>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>