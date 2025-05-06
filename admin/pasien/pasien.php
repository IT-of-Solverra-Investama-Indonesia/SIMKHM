<?php

$username = $_SESSION['admin']['username'];
$ambil = $koneksi->query("SELECT * FROM admin  WHERE username='$username';");
// $user=$ambil->fetch_assoc();


?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daftar Pasien</title>
  <style>
    select {
      width: 300px;
    }
  </style>
</head>

<body>

  <main>
    <div class="container">
      <div class="pagetitle">
        <h1>Data Diri Pasien</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?halaman=daftarpasien" style="color:blue;">Pasien</a></li>
            <li class="breadcrumb-item active"><a href="index.php?halaman=pasien">Data Pasien</a></li>
          </ol>
        </nav>
      </div><!-- End Page Title -->

      <section class="section  py-4">
        <div class="container">

          <div class="row">
            <div class="col-lg-12 col-md-12 d-flex">
              <div class="card" style="max-width: 70%; display: inline-flex; position: absolute;">
                <div class="card-body">
                  <h5 class="card-title">Data Umum</h5>

                  <!-- Multi Columns Form -->
                  <form class="row g-3" method="post" enctype="multipart/form-data">
                    <?php foreach ($ambil as $pecah) : ?>
                      <input type="hidden" name="idadmin" class="form-control" value="<?php echo $pecah['idadmin'] ?>" placeholder="Masukkan nama karyawan">
                      <input type="hidden" name="username_admin" class="form-control" value="<?php echo $pecah['namalengkap'] ?>" placeholder="Masukkan nama karyawan">
                      <input type="hidden" name="status_log" class="form-control" value="<?php echo $pecah['namalengkap'] ?> Menambahkan Data Pasien" placeholder="Masukkan nama karyawan">
                    <?php endforeach ?>

                    <div class="row mb-3">
                      <label for="profileImage" class="col-md-2 col-form-label">Foto Profil</label>
                      <div class="col-md-6 col-lg-5">
                        <img style="border-radius: 20px;" src="../assets/img/profile-img.png" alt="Profile">
                        <div class="pt-3">
                          <input type="file" name="foto" style="width:260px" class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <label for="inputName5" class="form-label">Nama Lengkap*</label>
                      <input type="text" class="form-control" required name="nama_lengkap" id="inputName5" placeholder="Masukkan Nama Pasien">
                    </div>
                    <div class="col-md-6">
                      <label for="inputName5" class="form-label">Nama Ayah</label>
                      <input type="text" class="form-control" name="nama_ibu" id="inputName5" placeholder="Masukkan Nama Ayah Pasien">
                    </div>
                    <div class="col-md-6">
                      <label for="inputName5" class="form-label">No RM*</label>
                      <?php
                      include
                        include "../dist/baseUrlAPI.php";
                      $api_url = $baseUrlLama . "api_personal/api_rekamMedis.php?newRekamMedis";

                      // Inisialisasi cURL
                      $ch = curl_init();
                      curl_setopt($ch, CURLOPT_URL, $api_url);
                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                      curl_setopt($ch, CURLOPT_HEADER, false);

                      // Eksekusi request
                      $response = curl_exec($ch);

                      // Cek error
                      if (curl_errno($ch)) {
                        echo 'Error: ' . curl_error($ch);
                      } else {
                        $noRM = $response;
                        // echo "Nomor Rekam Medis berikutnya: " . $noRM;
                      }

                      // Tutup koneksi
                      curl_close($ch);
                      // $norm = $koneksi->query("SELECT * FROM pasien WHERE no_rm REGEXP '^[0-9]+$' ORDER BY no_rm DESC LIMIT 1")->fetch_assoc();
                      ?>
                      <input type="text" class="form-control" name="no_rm" value="<?= $noRM ?>" id="inputName5" placeholder="Masukkan RM Pasien" required>
                    </div>
                    <div class="col-md-6">
                      <label for="inputName5" class="form-label">Tanggal Lahir*</label>
                      <input type="date" class="form-control" name="tgl_lahir" id="inputName5" placeholder="Masukkan Nama Pasien" required>
                    </div>
                    <div class="col-md-6">
                      <label for="inputName5" class="form-label">Suku</label>
                      <input type="text" class="form-control" name="suku" id="inputName5" placeholder="Masukkan Suku Pasien">
                    </div>
                    <div class="col-md-6">
                      <label for="inputName5" class="form-label">Bahasa Yang Dikuasai</label>
                      <input type="text" class="form-control" name="bahasa" id="inputName5" placeholder="Masukkan Bahasa Pasien">
                    </div>

                    <div class="col-md-6">
                      <label for="inputState" class="form-label">Jenis Kelamin*</label>
                      <select id="inputState" name="jenis_kelamin" class="form-select" required>
                        <option selected>Pilih</option>
                        <!-- <option value="0">Tidak Diketahui</option> -->
                        <option value="1">Laki-Laki</option>
                        <option value="2">Perempuan</option>
                        <!-- <option value="3">Tidak Dapat Ditentukan</option>
                      <option value="4">Tidak Mengisi</option> -->
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label for="inputState" class="form-label">Agama</label>
                      <select id="inputState" name="agama" class="form-select">
                        <option selected>Pilih</option>
                        <option value="1. Islam">Islam</option>
                        <option value="2. Kristen">Kristen (Protestan)</option>
                        <option value="3. Katolik">Katolik</option>
                        <option value="4. Hindu">Hindu</option>
                        <option value="5. Budha">Budha</option>
                        <option value="6. Konghucu">Konghucu</option>
                        <option value="7. Penghayat">Penghayat</option>
                        <option value="8. Lain-lain">Lain-lain</option>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label for="inputState" class="form-label">Pendidikan</label>
                      <select id="inputState" name="pendidikan" class="form-select">
                        <option selected>Pilih</option>
                        <option value="0">Tidak Sekolah</option>
                        <option value="1">SD</option>
                        <option value="2">SLTP Sederajat</option>
                        <option value="3">SLTA Sederajat</option>
                        <option value="4">D1-D3 Sederajat</option>
                        <option value="5">D4</option>
                        <option value="6">S1</option>
                        <option value="7">S2</option>
                        <option value="8">S3</option>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label for="inputState" class="form-label">Pekerjaan</label>
                      <select id="inputState" name="pekerjaan" class="form-select">
                        <option selected>Pilih</option>
                        <option value="0">Tidak Bekerja</option>
                        <option value="1">PNS</option>
                        <option value="2">TNI/POLRI</option>
                        <option value="3">BUMN</option>
                        <option value="4">Pegawai Swasta/Wirausaha</option>
                        <option value="5">Lain-lain</option>
                      </select>
                    </div>
                    <div class="col-md-12">
                      <label for="inputState" class="form-label">Status Pernikahan</label>
                      <select id="inputState" name="status_nikah" class="form-select">
                        <option selected>Pilih</option>
                        <option value="0">Belum Kawin</option>
                        <option value="1">Kawin</option>
                        <option value="2">Cerai Hidup</option>
                        <option value="3">Cerai Mati</option>
                      </select>
                    </div>
                    <br>
                    <br>
                    <div>
                      <h5 class="card-title">Data Kontak</h5>
                    </div>
                    <div class="col-md-6">
                      <label for="inputCity" class="form-label">No. HP </label>
                      <input type="text" name="nohp" class="form-control" id="inputCity" placeholder="Masukkan No. HP Pasien">
                    </div>
                    <div class="col-md-6">
                      <label for="inputCity" class="form-label">Email</label>
                      <input type="text" name="email" class="form-control" id="inputCity" placeholder="Masukkan Email Pasien">
                    </div>
                    <div class="col-md-6">
                      <label for="inputState" class="form-label">Jenis Kartu Identitas</label>
                      <select id="inputState" name="jenis_identitas" class="form-select">
                        <option>Pilih</option>
                        <option value="KTP" selected>KTP</option>
                        <!-- <option value="SIM">SIM</option>
                      <option value="KIA">KIA</option>
                      <option value="KK">KK</option>
                      <option value="Passport">Passport</option> -->
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label for="inputCity" class="form-label">No. Kartu Identitas*</label>
                      <input type="number" name="no_identitas" class="form-control" id="inputCity" placeholder="Masukkan No. Identitas Pasien" required>
                    </div>

                    <div>
                      <h5 class="card-title">Data Alamat Asal</h5>
                    </div>

                    <div class="col-md-6">
                      <label for="inputState" class="form-label">Provinsi</label>
                      <select id="provinsi" class="form-select">
                        <option hidden Selected>Pilih</option>
                      </select>
                      <input type="text" hidden id="provins" name="provinsi">
                    </div>
                    <div class="col-md-6">
                      <label for="inputState" class="form-label">Kota/Kabupaten</label>
                      <select id="kota" class="form-select">
                        <option hidden Selected>Pilih</option>
                      </select>
                      <input type="text" hidden id="kot" name="kota">
                    </div>
                    <div class="col-md-6">
                      <label for="inputState" class="form-label">Kecamatan</label>
                      <select id="kecamatan" class="form-select">
                        <option hidden Selected>Pilih</option>
                      </select>
                      <input type="text" hidden id="kecamata" name="kecamatan">
                    </div>
                    <div class="col-md-6">
                      <label for="inputState" class="form-label">Desa/Kelurahan</label>
                      <select id="kelurahan" class="form-select">
                        <option hidden Selected>Pilih</option>
                      </select>
                      <input type="text" hidden id="keluraha" name="kelurahan">
                    </div>

                    <div class="col-md-6">
                      <label for="inputCity" class="form-label">Kode Pos</label>
                      <input type="text" name="kode_pos" id="kode_pos" class="form-control" placeholder="Masukkan kode pos">
                    </div>
                    <div class="col-md-6">
                      <label for="inputCity" class="form-label">Alamat Rumah</label>

                      <textarea type="text" name="alamat" class="form-control" style="height: 100px;" id="alamatAsal" value="" placeholder="Masukkan alamat"></textarea>
                    </div>

                    <div>
                      <h5 class="card-title">Data Alamat Domisili</h5>
                    </div>

                    <div class="col-md-12">
                      <label for="inputCity" class="form-label my-0">Alamat Rumah</label><br>
                      <button type="button" onclick="samakanAlamat()" class="btn btn-sm btn-primary mb-2">Samakan dengan Alamat Asal</button>
                      <textarea type="text" id="alamatDomisili" name="alamat_dom" class="form-control" style="height: 100px;" value="" placeholder="Masukkan alamat"></textarea>
                    </div>

                    <div>
                      <h5 class="card-title">Lain-Lain</h5>
                    </div>

                    <div class="col-md-6">
                      <label for="inputState" class="form-label">Pembiayaan</label>
                      <select id="inputState" name="pembiayaan" class="form-select">
                        <option selected>Pilih</option>
                        <option value="1. JKN">JKN</option>
                        <option value="2. Mandiri">Mandiri</option>
                        <option value="3. Asuransi">Asuransi</option>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label for="inputState" class="form-label">Nomor BPJS</label>
                      <input type="text" class="form-control" name="no_bpjs" value="-">
                    </div>
                </div>

                <div class="text-center" style="margin-top: 50px; margin-bottom: 80px;">
                  <button type="submit" name="save" class="btn btn-primary">Submit</button>
                  <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
                </form><!-- End Multi Columns Form -->

              </div>
            </div>
          </div>

        </div>
    </div>
    </div>

    </section>


    </div>
  </main><!-- End #main -->
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
            optionElement.text = propinsiName; // Teks yang akan ditampilkan pada option

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
    selectProvinsi.addEventListener("change", function() {
      // Mendapatkan nilai provinsi yang dipilih
      var selectedProvinsi = selectProvinsi.value;
      var selectedProvinsii = selectProvinsi.options[selectProvinsi.selectedIndex].text;
      inputProvinsi.value = selectedProvinsii;
      // Memanggil fungsi untuk memperbarui daftar kota berdasarkan provinsi yang dipilih
      updateKotaList(selectedProvinsi);
    });

    // Menambahkan event listener untuk elemen kota
    selectKota.addEventListener("change", function() {
      // Memanggil fungsi untuk memperbarui daftar kecamatan dan kelurahan berdasarkan kota yang dipilih
      var selectedKotaa = selectKota.options[selectKota.selectedIndex].text;
      inputKota.value = selectedKotaa;
      updateKecamatanList();
    });
    // Menambahkan event listener untuk elemen kecamatan
    selectKecamatan.addEventListener("change", function() {
      // Memanggil fungsi untuk memperbarui daftar kelurahan berdasarkan kecamatan yang dipilih
      var selectedKecamatann = selectKecamatan.options[selectKecamatan.selectedIndex].text;
      inputKecamatan.value = selectedKecamatann;
      updateKelurahanList();
    });
    selectKelurahan.addEventListener("change", function() {
      // Mendapatkan nilai kelurahan yang dipilih
      var selectedKelurahan = selectKelurahan.value;

      // Mendapatkan nilai kota yang dipilih
      var selectedKota = selectKota.value;
      var selectedKelurahann = selectKelurahan.options[selectKelurahan.selectedIndex].text;
      inputKelurahan.value = selectedKelurahann;
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
  </script>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <script>
    function samakanAlamat() {
      document.getElementById("alamatDomisili").innerHTML = 'Provinsi ' + document.getElementById("provins").value + ', ' + 'Kabupaten/Kota ' + document.getElementById("kot").value + ', ' + 'Kecamatan ' + document.getElementById("kecamata").value + ', ' + 'Desa/Kelurahan ' + document.getElementById("keluraha").value + ', ' + document.getElementById("alamatAsal").value + ', ' + document.getElementById("kode_pos").value;
    }
  </script>

  <!-- <script>
        fetch(`https://kanglerian.github.io/api-wilayah-indonesia/api/provinces.json`)
            .then((response) => response.json())
            .then((provinces) => {
                var data = provinces;
                var tampung = `<option>Pilih</option>`;
                data.forEach((element) => {
                    tampung += `<option data-prov="${element.id}" value="${element.name}">${element.name}</option>`;
                });
                document.getElementById("provinsi").innerHTML = tampung;
            });
    </script>
    <script>
        const selectProvinsi = document.getElementById('provinsi');
        const selectKota = document.getElementById('kota');
        const selectKecamatan = document.getElementById('kecamatan');
        const selectKelurahan = document.getElementById('kelurahan');

        selectProvinsi.addEventListener('change', (e) => {
            var provinsi = e.target.options[e.target.selectedIndex].dataset.prov;
            fetch(`https://kanglerian.github.io/api-wilayah-indonesia/api/regencies/${provinsi}.json`)
                .then((response) => response.json())
                .then((regencies) => {
                    var data = regencies;
                    var tampung = `<option>Pilih</option>`;
                    document.getElementById('kota').innerHTML = '<option>Pilih</option>';
                    document.getElementById('kecamatan').innerHTML = '<option>Pilih</option>';
                    document.getElementById('kelurahan').innerHTML = '<option>Pilih</option>';
                    data.forEach((element) => {
                        tampung += `<option data-prov="${element.id}" value="${element.name}">${element.name}</option>`;
                    });
                    document.getElementById("kota").innerHTML = tampung;
                });
        });

        selectKota.addEventListener('change', (e) => {
            var kota = e.target.options[e.target.selectedIndex].dataset.prov;
            fetch(`https://kanglerian.github.io/api-wilayah-indonesia/api/districts/${kota}.json`)
                .then((response) => response.json())
                .then((districts) => {
                    var data = districts;
                    var tampung = `<option>Pilih</option>`;
                    document.getElementById('kecamatan').innerHTML = '<option>Pilih</option>';
                    document.getElementById('kelurahan').innerHTML = '<option>Pilih</option>';
                    data.forEach((element) => {
                        tampung += `<option data-prov="${element.id}" value="${element.name}">${element.name}</option>`;
                    });
                    document.getElementById("kecamatan").innerHTML = tampung;
                });
        });
        selectKecamatan.addEventListener('change', (e) => {
            var kecamatan = e.target.options[e.target.selectedIndex].dataset.prov;
            fetch(`https://kanglerian.github.io/api-wilayah-indonesia/api/villages/${kecamatan}.json`)
                .then((response) => response.json())
                .then((villages) => {
                    var data = villages;
                    var tampung = `<option>Pilih</option>`;
                    document.getElementById('kelurahan').innerHTML = '<option>Pilih</option>';
                    data.forEach((element) => {
                        tampung += `<option data-prov="${element.id}" value="${element.name}">${element.name}</option>`;
                    });
                    document.getElementById("kelurahan").innerHTML = tampung;
                });
        });
    </script>


     <script>
        fetch(`https://kanglerian.github.io/api-wilayah-indonesia/api/provinces.json`)
            .then((response) => response.json())
            .then((provinces_dom) => {
                var data = provinces_dom;
                var tampung = `<option>Pilih</option>`;
                data.forEach((element2) => {
                    tampung += `<option data-prov="${element2.id}" value="${element2.name}">${element2.name}</option>`;
                });
                document.getElementById("provinsi_dom").innerHTML = tampung;
            });
    </script> -->

  <script>
    const selectProvinsiDom = document.getElementById('provinsi_dom');
    const selectKotaDom = document.getElementById('kota_dom');
    const selectKecamatanDom = document.getElementById('kecamatan_dom');
    const selectKelurahanDom = document.getElementById('kelurahan_dom');

    selectProvinsi.addEventListener('change2', (e2) => {
      var provinsi_dom = e2.target.options[e2.target.selectedIndex].dataset.prov;
      fetch(`https://kanglerian.github.io/api-wilayah-indonesia/api/regencies/${provinsi_dom}.json`)
        .then((response) => response.json())
        .then((regencies) => {
          var data = regencies;
          var tampung = `<option>Pilih</option>`;
          document.getElementById('kota_dom').innerHTML = '<option>Pilih</option>';
          document.getElementById('kecamatan_dom').innerHTML = '<option>Pilih</option>';
          document.getElementById('kelurahan_dom').innerHTML = '<option>Pilih</option>';
          data.forEach((element2) => {
            tampung += `<option data-prov="${element2.id}" value="${element2.name}">${element2.name}</option>`;
          });
          document.getElementById("kota_dom").innerHTML = tampung;
        });
    });

    selectKota.addEventListener('change', (e) => {
      var kota_dom = e.target.options[e.target.selectedIndex].dataset.prov;
      fetch(`https://kanglerian.github.io/api-wilayah-indonesia/api/districts/${kota_dom}.json`)
        .then((response) => response.json())
        .then((districts) => {
          var data = districts;
          var tampung = `<option>Pilih</option>`;
          document.getElementById('kecamatan_dom').innerHTML = '<option>Pilih</option>';
          document.getElementById('kelurahan_dom').innerHTML = '<option>Pilih</option>';
          data.forEach((element) => {
            tampung += `<option data-prov="${element.id}" value="${element.name}">${element.name}</option>`;
          });
          document.getElementById("kecamatan_dom").innerHTML = tampung;
        });
    });
    selectKecamatan.addEventListener('change', (e) => {
      var kecamatan_dom = e.target.options[e.target.selectedIndex].dataset.prov;
      fetch(`https://kanglerian.github.io/api-wilayah-indonesia/api/villages/${kecamatan_dom}.json`)
        .then((response) => response.json())
        .then((villages) => {
          var data = villages;
          var tampung = `<option>Pilih</option>`;
          document.getElementById('kelurahan_dom').innerHTML = '<option>Pilih</option>';
          data.forEach((element) => {
            tampung += `<option data-prov="${element.id}" value="${element.name}">${element.name}</option>`;
          });
          document.getElementById("kelurahan_dom").innerHTML = tampung;
        });
    });
  </script>
</body>

</html>

<?php
if (isset($_POST['save'])) {
  $nama_lengkap = htmlspecialchars($_POST["nama_lengkap"]);
  $nama_ibu = htmlspecialchars($_POST["nama_ibu"]);
  $no_rm = str_replace(' ', '', htmlspecialchars($_POST["no_rm"]));
  $tgl_lahir = htmlspecialchars($_POST["tgl_lahir"]);
  $suku = htmlspecialchars($_POST["suku"]);
  $bahasa = htmlspecialchars($_POST["bahasa"]);
  $jenis_kelamin = htmlspecialchars($_POST["jenis_kelamin"]);
  $agama = htmlspecialchars($_POST["agama"]);
  $pendidikan = htmlspecialchars($_POST["pendidikan"]);
  $pekerjaan = htmlspecialchars($_POST["pekerjaan"]);
  $status_nikah = htmlspecialchars($_POST["status_nikah"]);

  $nohp = htmlspecialchars($_POST["nohp"]);
  $email = htmlspecialchars($_POST["email"]);
  $jenis_identitas = htmlspecialchars($_POST["jenis_identitas"]);
  $no_identitas = ($_POST["no_identitas"]);

  $provinsi = htmlspecialchars($_POST["provinsi"]);
  $kota = htmlspecialchars($_POST["kota"]);
  $kecamatan = htmlspecialchars($_POST["kecamatan"]);
  $kelurahan = htmlspecialchars($_POST["kelurahan"]);
  $kode_pos = htmlspecialchars($_POST["kode_pos"]);
  $alamat = htmlspecialchars($_POST["alamat"]);
  $pembiayaan = htmlspecialchars($_POST["pembiayaan"]);
  $no_bpjs = htmlspecialchars($_POST["no_bpjs"]);
  $kategori = htmlspecialchars($_POST["kategori"]);
  $username_admin = htmlspecialchars($_POST["username_admin"]);
  $idadmin = htmlspecialchars($_POST["idadmin"]);
  $status_log = htmlspecialchars($_POST["status_log"]);

  //hitung usia
  $lahir    = new DateTime($tgl_lahir);
  $today        = new DateTime();
  $umur = $today->diff($lahir);
  $umur2 = $umur->y . " Tahun," . $umur->m . " Bulan," . $umur->d . " Hari";
  $foto = $_FILES['foto']['name'];
  $lokasi = $_FILES['foto']['tmp_name'];
  move_uploaded_file($lokasi, '../pasien/foto/' . $foto);

  $getPasienTerdaftar = $koneksi->query("SELECT COUNT(*) as jumlah FROM pasien WHERE no_rm='$no_rm'")->fetch_assoc();
  if ($getPasienTerdaftar['jumlah'] == 0) {
    $koneksi->query("INSERT INTO pasien 
        (nama_lengkap, nama_ibu, nohp, email, no_identitas,  tgl_lahir, jenis_kelamin, jenis_identitas, provinsi, kota, kelurahan, kecamatan, kode_pos, alamat, kategori, status_nikah, pembiayaan, foto, no_rm, umur, no_bpjs, agama, suku, bahasa, pendidikan, pekerjaan)
        VALUES ('$nama_lengkap', '$nama_ibu', '$nohp', '$email', '$no_identitas', '$tgl_lahir', '$jenis_kelamin', '$jenis_identitas', '$provinsi', '$kota', '$kelurahan', '$kecamatan',  '$kode_pos', '$alamat', '$kategori', '$status_nikah','$pembiayaan', '$foto', '$no_rm', '$umur2','$no_bpjs', '$agama', '$suku', '$bahasa', '$pendidikan', '$pekerjaan')
      ");
    $koneksi->query("INSERT INTO log_user 
        (status_log, username_admin, idadmin)
        VALUES ('$status_log', '$username_admin', '$idadmin')
      ");
    if (mysqli_affected_rows($koneksi) > 0) {
      echo "
          <script>
            alert('Data berhasil ditambah');
            document.location.href='index.php?halaman=daftarpasien';
          </script>
        ";
    } else {
      echo "
          <script>
            alert('GAGAL MENGHAPUS DATA');
            document.location.href='index.php?halaman=daftarpasien';
          </script>
        ";
    }
  } else {
    echo "
        <script>
          alert('Pasien sudah terdaftar!!!');
          document.location.href='index.php?halaman=pasien';
        </script>
      ";
  }
}
?>