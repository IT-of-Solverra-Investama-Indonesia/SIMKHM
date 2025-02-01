<?php

$id = $_GET['id'];
$username = $_SESSION['admin']['username'];

$user = $koneksi->query("SELECT * FROM admin WHERE username='$username';");


$ambil = $koneksi->query("SELECT * FROM pasien WHERE idpasien='$_GET[id]' ");
$pecah = $ambil->fetch_assoc();


?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ubah Pasien</title>
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
        <h1>Data Diri</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?halaman=daftarpasien" style="color:blue;">Pasien</a></li>
            <li class="breadcrumb-item"><a href="index.php?halaman=detailpasien&id=<?php echo $pecah["idpasien"]; ?>" style="color:blue;">Detail Pasien</a></li>
            <li class="breadcrumb-item active"><a href="index.php?halaman=ubahpasien">Ubah Pasien</a></li>
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
                    <?php foreach ($user as $user) : ?>
                      <input type="hidden" name="idadmin" class="form-control" value="<?php echo $user['idadmin'] ?>" placeholder="Masukkan nama karyawan">
                      <input type="hidden" name="username_admin" class="form-control" value="<?php echo $user['namalengkap'] ?>" placeholder="Masukkan nama karyawan">
                      <input type="hidden" name="status_log" class="form-control" value="<?php echo $user['namalengkap'] ?> Mengubah Data Pasien" placeholder="Masukkan nama karyawan">
                    <?php endforeach ?>
                    <div class="row mb-3">
                      <label for="profileImage" class="col-md-2 col-form-label">Foto Profil</label>
                      <div class="col-md-6 col-lg-5">
                        <img style="border-radius: 20px; width:100px" src="../pasien/foto/<?= $pecah['foto'] ?>" alt="Profile">
                        <div class="pt-3">
                          <input type="file" name="foto" style="width:260px" class="form-control">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <label for="inputName5" class="form-label">Nama Lengkap</label>
                      <input type="text" class="form-control" name="nama_lengkap" value="<?= $pecah['nama_lengkap'] ?>" id="inputName5" placeholder="Masukkan Nama Pasien">
                    </div>
                    <div class="col-md-6">
                      <label for="inputName5" class="form-label">Nama Ayah</label>
                      <input type="text" class="form-control" name="nama_ibu" value="<?= $pecah['nama_ibu'] ?>" id="inputName5" placeholder="Masukkan Nama Ayah Pasien">
                    </div>
                    <div class="col-md-6">
                      <label for="inputName5" class="form-label">No RM</label>
                      <input type="text" class="form-control" name="no_rm" value="<?= $pecah['no_rm'] ?>" id="inputName5" placeholder="Masukkan RM Pasien">
                    </div>
                    <div class="col-md-6">
                      <label for="inputName5" class="form-label">Tanggal Lahir</label>
                      <input type="date" class="form-control" name="tgl_lahir" value="<?= $pecah['tgl_lahir'] ?>" id="inputName5" placeholder="Masukkan Nama Pasien">
                    </div>
                    <div class="col-md-6">
                      <label for="inputName5" class="form-label">Suku</label>
                      <input type="text" class="form-control" name="suku" value="<?= $pecah['suku'] ?>" id="inputName5" placeholder="Masukkan Suku Pasien">
                    </div>
                    <div class="col-md-6">
                      <label for="inputName5" class="form-label">Bahasa Yang Dikuasai</label>
                      <input type="text" class="form-control" name="bahasa" value="<?= $pecah['bahasa'] ?>" id="inputName5" placeholder="Masukkan Bahasa Pasien">
                    </div>

                    <div class="col-md-6">
                      <label for="inputState" class="form-label">Jenis Kelamin</label>
                      <select id="inputState" name="jenis_kelamin" class="form-select">
                        <option>Pilih</option>
                        <option value="1" <?= ($pecah['jenis_kelamin'] == '1') ? 'selected' : '' ?>>Laki-Laki</option>
                        <option value="2" <?= ($pecah['jenis_kelamin'] == '2') ? 'selected' : '' ?>>Perempuan</option>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label for="inputState" class="form-label">Agama</label>
                      <select id="inputState" name="agama" class="form-select">
                        <option value="<?= $pecah['agama'] ?>" selected><?= $pecah['agama'] ?></option>
                        <option>Pilih</option>
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
                        <option value="<?= $pecah['pendidikan'] ?>" selected><?= $pecah['pendidikan'] ?></option>
                        <option>Pilih</option>
                        <option value="0" <?= ($pecah['pendidikan'] == '0') ? 'selected' : '' ?>>Tidak Sekolah</option>
                        <option value="1" <?= ($pecah['pendidikan'] == '1') ? 'selected' : '' ?>>SD</option>
                        <option value="2" <?= ($pecah['pendidikan'] == '2') ? 'selected' : '' ?>>SLTP Sederajat</option>
                        <option value="3" <?= ($pecah['pendidikan'] == '3') ? 'selected' : '' ?>>SLTA Sederajat</option>
                        <option value="4" <?= ($pecah['pendidikan'] == '4') ? 'selected' : '' ?>>D1-D3 Sederajat</option>
                        <option value="5" <?= ($pecah['pendidikan'] == '5') ? 'selected' : '' ?>>D4</option>
                        <option value="6" <?= ($pecah['pendidikan'] == '6') ? 'selected' : '' ?>>S1</option>
                        <option value="7" <?= ($pecah['pendidikan'] == '7') ? 'selected' : '' ?>>S2</option>
                        <option value="8" <?= ($pecah['pendidikan'] == '8') ? 'selected' : '' ?>>S3</option>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label for="inputState" class="form-label">Pekerjaan</label>
                      <select id="inputState" name="pekerjaan" class="form-select">
                        <option value="<?= $pecah['pekerjaan'] ?>" selected><?= $pecah['pekerjaan'] ?></option>
                        <option>Pilih</option>
                        <option value="0" <?= ($pecah['pekerjaan'] == '0') ? 'selected' : '' ?>>Tidak Bekerja</option>
                        <option value="1" <?= ($pecah['pekerjaan'] == '1') ? 'selected' : '' ?>>PNS</option>
                        <option value="2" <?= ($pecah['pekerjaan'] == '2') ? 'selected' : '' ?>>TNI/POLRI</option>
                        <option value="3" <?= ($pecah['pekerjaan'] == '3') ? 'selected' : '' ?>>BUMN</option>
                        <option value="4" <?= ($pecah['pekerjaan'] == '4') ? 'selected' : '' ?>>Pegawai Swasta/Wirausaha</option>
                        <option value="5" <?= ($pecah['pekerjaan'] == '5') ? 'selected' : '' ?>>Lain-lain</option>
                      </select>
                    </div>
                    <div class="col-md-12">
                      <label for="inputState" class="form-label">Status Pernikahan</label>
                      <select id="inputState" name="status_nikah" class="form-select">
                        <option value="<?= $pecah['status_nikah'] ?>" selected><?= $pecah['status_nikah'] ?></option>
                        <option>Pilih</option>
                        <option value="0" <?= ($pecah['status_nikah'] == '0') ? 'selected' : '' ?>>Belum Kawin</option>
                        <option value="1" <?= ($pecah['status_nikah'] == '1') ? 'selected' : '' ?>>Kawin</option>
                        <option value="2" <?= ($pecah['status_nikah'] == '2') ? 'selected' : '' ?>>Cerai Hidup</option>
                        <option value="3" <?= ($pecah['status_nikah'] == '3') ? 'selected' : '' ?>>Cerai Mati</option>
                      </select>
                    </div>
                    <br>
                    <br>
                    <div>
                      <h5 class="card-title">Data Kontak</h5>
                    </div>
                    <div class="col-md-6">
                      <label for="inputCity" class="form-label">No. HP </label>
                      <input type="text" name="nohp" value="<?= $pecah['nohp'] ?>" class="form-control" id="inputCity" placeholder="Masukkan No. HP Pasien">
                    </div>
                    <div class="col-md-6">
                      <label for="inputCity" class="form-label">Email</label>
                      <input type="text" name="email" value="<?= $pecah['email'] ?>" class="form-control" id="inputCity" placeholder="Masukkan Email Pasien">
                    </div>
                    <div class="col-md-6">
                      <label for="inputState" class="form-label">Jenis Kartu Identitas</label>
                      <select id="inputState" name="jenis_identitas" class="form-select">
                        <option value="<?= $pecah['jenis_identitas'] ?>" selected><?= $pecah['jenis_identitas'] ?></option>
                        <option>Pilih</option>
                        <option value="KTP">KTP</option>
                        <!-- <option value="SIM">SIM</option>
                      <option value="KIA">KIA</option>
                      <option value="KK">KK</option>
                      <option value="Passport">Passport</option> -->
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label for="inputCity" class="form-label">No. Kartu Identitas</label>
                      <input type="number" name="no_identitas" value="<?= $pecah['no_identitas'] ?>" class="form-control" id="inputCity" placeholder="Masukkan No. Identitas Pasien">
                    </div>

                    <div>
                      <h5 class="card-title">Data Alamat Asal</h5>
                    </div>

                    <div class="col-md-6">
                      <label for="inputState" class="form-label">Provinsi</label>
                      <select id="provinsi" class="form-select">
                        <option value="<?= $pecah['provinsi'] ?>" selected><?= $pecah['provinsi'] ?></option>
                        <option hidden>Pilih</option>
                      </select>
                      <input type="text" hidden id="provins" name="provinsi" value="<?= $pecah['provinsi'] ?>">
                    </div>
                    <div class="col-md-6">
                      <label for="inputState" class="form-label">Kota/Kabupaten</label>
                      <select id="kota" class="form-select">
                        <option value="<?= $pecah['kota'] ?>" selected><?= $pecah['kota'] ?></option>
                        <option hidden>Pilih</option>
                      </select>
                      <input type="text" hidden id="kot" name="kota" value="<?= $pecah['kota'] ?>">
                    </div>
                    <div class="col-md-6">
                      <label for="inputState" class="form-label">Kecamatan</label>
                      <select id="kecamatan" class="form-select">
                        <option value="<?= $pecah['kecamatan'] ?>" selected><?= $pecah['kecamatan'] ?></option>
                        <option hidden>Pilih</option>
                      </select>
                      <input type="text" hidden id="kecamata" name="kecamatan" value="<?= $pecah['kecamatan'] ?>">
                    </div>
                    <div class="col-md-6">
                      <label for="inputState" class="form-label">Desa/Kelurahan</label>
                      <select id="kelurahan" class="form-select">
                        <option value="<?= $pecah['kelurahan'] ?>" selected><?= $pecah['kelurahan'] ?></option>
                        <option hidden>Pilih</option>
                      </select>
                      <input type="text" hidden id="keluraha" name="kelurahan" value="<?= $pecah['kelurahan'] ?>">
                    </div>

                    <div class="col-md-6">
                      <label for="inputCity" class="form-label">Kode Pos</label>
                      <input type="text" name="kode_pos" value="<?= $pecah['kode_pos'] ?>" id="kode_pos" class="form-control" placeholder="Masukkan kode pos">
                    </div>
                    <div class="col-md-6">
                      <label for="inputCity" class="form-label">Alamat Rumah</label>

                      <textarea type="text" name="alamat" value="<?= $pecah['alamat'] ?>" class="form-control" style="height: 100px;" id="alamatAsal" placeholder="Masukkan alamat"><?= $pecah['alamat'] ?></textarea>
                    </div>

                    <div>
                      <h5 class="card-title">Data Alamat Domisili</h5>
                    </div>

                    <div class="col-md-12">
                      <label for="inputCity" class="form-label my-0">Alamat Rumah</label><br>
                      <button type="button" onclick="samakanAlamat()" class="btn btn-sm btn-primary mb-2">Samakan dengan Alamat Asal</button>
                      <textarea type="text" id="alamatDomisili" name="alamat_dom" class="form-control" style="height: 100px;" placeholder="Masukkan alamat"><?= $pecah['alamat_dom'] ?></textarea>
                    </div>

                    <div>
                      <h5 class="card-title">Lain-Lain</h5>
                    </div>

                    <div class="col-md-6">
                      <label for="inputState" class="form-label">Pembiayaan</label>
                      <select id="inputState" name="pembiayaan" class="form-select">
                        <option selected value="<?= $pecah['pembiayaan'] ?>"><?= $pecah['pembiayaan'] ?></option>
                        <option>Pilih</option>
                        <option value="1. JKN">JKN</option>
                        <option value="2. Mandiri">Mandiri</option>
                        <option value="3. Asuransi">Asuransi</option>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label for="inputState" class="form-label">Nomor BPJS</label>
                      <input type="text" class="form-control" name="no_bpjs" value="<?= $pecah['no_bpjs'] ?>">
                    </div>
                </div>
                <!-- <div class="row mb-3">
                      <label for="profileImage" class="col-md-2 col-form-label">Foto Profil</label>
                      <div class="col-md-6 col-lg-5">
                        <img style="border-radius: 10px; width:100px" src="../pasien/foto/<?php echo $pecah['foto'] ?>" alt="Profile">
                        <div class="pt-3">
                              <input type="file" name="foto" style="width:260px" class="form-control">
                        </div>
                      </div>
                    </div>
                <div class="col-md-12">
                  <label for="inputName5" class="form-label">Nama Lengkap</label>
                  <input type="text" class="form-control" value="<?php echo $pecah['nama_lengkap'] ?>"  name="nama_lengkap" id="inputName5" placeholder="Masukkan Nama Pasien">
                </div>
                <div class="col-md-6">
                  <label for="inputName5" class="form-label">Tanggal Lahir</label>
                  <input type="date" class="form-control" value="<?php echo $pecah['tgl_lahir'] ?>" name="tgl_lahir" id="inputName5" placeholder="Masukkan Nama Pasien">
                </div>
               
               <div class="col-md-6">
                  <label for="inputState" class="form-label">Jenis Kelamin</label>
                  <select id="inputState" name="jenis_kelamin" class="form-select">
                    <option value="<?php echo $pecah['jenis_kelamin'] ?>"><?php echo $pecah['jenis_kelamin'] ?></option>
                    <option value="1">Laki-laki</option>
                    <option value="2">Perempuan</option>
                  </select>
                </div>
                <br>
                <br>
                <div>
                  <h5 class="card-title">Data Kontak</h5>
                </div>
                <div class="col-md-6">
                  <label for="inputCity" class="form-label">No. HP </label>
                  <input type="text" name="nohp" value="<?php echo $pecah['nohp'] ?>" class="form-control" id="inputCity" placeholder="Masukkan No. HP Pasien">
                </div> 
                <div class="col-md-6">
                  <label for="inputCity" class="form-label">Email</label>
                  <input type="text" name="email" value="<?php echo $pecah['email'] ?>" class="form-control" id="inputCity" placeholder="Masukkan Email Pasien">
                </div>
                <div class="col-md-6">
                  <label for="inputState" class="form-label">Jenis Kartu Identitas</label>
                  <select id="inputState" name="jenis_identitas" class="form-select">
                    <option value="<?php echo $pecah['jenis_identitas'] ?>"><?php echo $pecah['jenis_identitas'] ?></option>
                    <option value="KTP">KTP</option>
                    <option value="SIM">SIM</option>
                    <option value="KIA">KIA</option>
                    <option value="KK">KK</option>
                    <option value="Passport">Passport</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label for="inputCity" class="form-label">No. Kartu Identitas</label>
                  <input type="text" value="<?php echo $pecah['no_identitas'] ?>" name="no_identitas" class="form-control" id="inputCity" placeholder="Masukkan No. Identitas Pasien">
                </div>
                
                <div>
                  <h5 class="card-title">Data Alamat</h5>
                </div>
              
                <div class="col-md-6">
                  <label for="inputState" class="form-label">Provinsi</label>
                  <select id="provinsi" value="<?php echo $pecah['provinsi'] ?>" name="provinsi" class="form-select">
                    <?php echo $pecah['provinsi'] ?>
                  </select>
                </div>
                <div class="col-md-6">
                  <label for="inputState" class="form-label">Kota/Kabupaten</label>
                  <select id="kota" value="<?php echo $pecah['kota'] ?>" name="kota" class="form-select">
                   
                  </select>
                </div>
                <div class="col-md-6">
                  <label for="inputState" class="form-label">Kecamatan</label>
                  <select id="kecamatan" value="<?php echo $pecah['kecamatan'] ?>" name="kecamatan" class="form-select">
                  
                  </select>
                </div>
                <div class="col-md-6">
                  <label for="inputState" class="form-label">Desa/Kelurahan</label>
                  <select id="kelurahan" value="<?php echo $pecah['kelurahan'] ?>" name="kelurahan" class="form-select">
                 
                  </select>
                </div>

                <div class="col-md-6">
                  <label for="inputCity" class="form-label">Kode Pos</label>
                  <input type="text" value="<?php echo $pecah['kode_pos'] ?>" name="kode_pos" class="form-control" id="inputCity" placeholder="Masukkan kode pos">
                </div>
                 <div class="col-md-6">
                  <label for="inputCity" class="form-label">Alamat Rumah</label>
                
                   <textarea type="text" value="<?php echo $pecah['alamat'] ?>" name="alamat" class="form-control" style="height: 100px;" value="" placeholder="Masukkan alamat"><?php echo $pecah['alamat'] ?></textarea>
                </div>

                <div>
                  <h5 class="card-title">Lain-Lain</h5>
                </div>
                <div class="col-md-4">
                  <label for="inputState" class="form-label">Kategori Pasien</label>
                  <select id="inputState" name="kategori" class="form-select">
                    <option value="<?php echo $pecah['kategori'] ?>"><?php echo $pecah['kategori'] ?></option>
                    <option value="Regular">Regular</option>
                    <option value="Karyawan">Karyawan</option>
                    <option value="BPJS">BPJS</option>
                    <option value="Perusahaan">Perusahaan</option>
                  </select>
                </div>
                <div class="col-md-4">
                  <label for="inputState" class="form-label">Pembiayaan</label>
                  <select id="inputState" name="pembiayaan" class="form-select">
                    <option value="<?php echo $pecah['pembiayaan'] ?>"><?php echo $pecah['pembiayaan'] ?></option>
                    <option value="Pribadi">Pribadi</option>
                    <option value="BPJS">BPJS</option>
                  </select>
                </div>
                <div class="col-md-4">
                  <label for="inputState" class="form-label">No Bpjs</label>
                 <input type="number" value="<?php echo $pecah['no_bpjs'] ?>" name="no_bpjs" class="form-control" id="inputCity" placeholder="Masukkan no bpjs">
                </div>
              </div> -->

                <div class="text-center" style="margin-top: 50px; margin-bottom: 80px;">
                  <button type="submit" name="save" class="btn btn-primary">Edit</button>
                  <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
                </form>

              </div>
            </div>
          </div>

        </div>
    </div>
    </div>

    </section>

    </div>
  </main>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
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
    </script> -->

</body>

</html>


<?php

$ambil2 = $koneksi->query("SELECT * FROM pasien WHERE idpasien='$_GET[id]'");
$pecah2 = $ambil2->fetch_assoc();


if (isset($_POST['save'])) {

  $nama_lengkap = htmlspecialchars($_POST["nama_lengkap"]);
  $nama_ibu = htmlspecialchars($_POST["nama_ibu"]);

  $nohp = htmlspecialchars($_POST["nohp"]);

  $email = htmlspecialchars($_POST["email"]);

  $tgl_lahir = htmlspecialchars($_POST["tgl_lahir"]);

  $jenis_identitas = htmlspecialchars($_POST["jenis_identitas"]);

  $no_identitas = ($_POST["no_identitas"]);

  $suku = htmlspecialchars($_POST["suku"]);

  $agama = htmlspecialchars($_POST["agama"]);

  $bahasa = htmlspecialchars($_POST["bahasa"]);

  $pekerjaan = htmlspecialchars($_POST["pekerjaan"]);

  $pendidikan = htmlspecialchars($_POST["pendidikan"]);

  $jenis_kelamin = htmlspecialchars($_POST["jenis_kelamin"]);

  $provinsi = htmlspecialchars($_POST["provinsi"]);

  $kota = htmlspecialchars($_POST["kota"]);

  $kelurahan = htmlspecialchars($_POST["kelurahan"]);

  $kecamatan = htmlspecialchars($_POST["kecamatan"]);

  $kode_pos = htmlspecialchars($_POST["kode_pos"]);

  $no_rm = htmlspecialchars($_POST["no_rm"]);

  $alamat = htmlspecialchars($_POST["alamat"]);

  $kategori = htmlspecialchars($_POST["kategori"]);

  $pembiayaan = htmlspecialchars($_POST["pembiayaan"]);
  $status_nikah = htmlspecialchars($_POST["status_nikah"]);

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

  if (!empty($lokasi)) {

    move_uploaded_file($lokasi, '../pasien/foto/' . $foto);

    $koneksi->query("UPDATE pasien SET nama_lengkap = '$nama_lengkap', nama_ibu = '$nama_ibu', suku = '$suku',agama = '$agama',pekerjaan = '$pekerjaan',bahasa = '$bahasa', pendidikan='$pendidikan', nohp = '$nohp', email = '$email', no_identitas = '$no_identitas',  tgl_lahir = '$tgl_lahir', jenis_kelamin = '$jenis_kelamin', jenis_identitas = '$jenis_identitas', provinsi = '$provinsi', kota = '$kota', kelurahan = '$kelurahan', kecamatan = '$kecamatan', kode_pos = '$kode_pos', alamat = '$alamat', kategori = '$kategori', status_nikah = '$status_nikah', pembiayaan = '$pembiayaan', no_rm = '$no_rm', foto='$foto', umur = '$umur2', no_bpjs = '$_POST[no_bpjs]' WHERE idpasien='$_GET[id]' ");

    $koneksi->query("INSERT INTO log_user 

    (status_log, username_admin, idadmin)

    VALUES ('$status_log', '$username_admin', '$idadmin')

    ");
  } else {

    $koneksi->query("UPDATE pasien SET nama_lengkap = '$nama_lengkap', nama_ibu = '$nama_ibu', suku = '$suku',pendidikan='$pendidikan',agama = '$agama',pekerjaan = '$pekerjaan',bahasa = '$bahasa', nohp = '$nohp', email = '$email', no_identitas = '$no_identitas',  tgl_lahir = '$tgl_lahir', jenis_kelamin = '$jenis_kelamin', jenis_identitas = '$jenis_identitas', provinsi = '$provinsi', kota = '$kota', kelurahan = '$kelurahan', kecamatan = '$kecamatan', kode_pos = '$kode_pos', alamat = '$alamat', kategori = '$kategori', status_nikah = '$status_nikah', pembiayaan = '$pembiayaan', no_rm = '$no_rm', umur = '$umur2', no_bpjs = '$_POST[no_bpjs]' WHERE idpasien='$_GET[id]' ");

    $koneksi->query("INSERT INTO log_user 

    (status_log, username_admin, idadmin)

    VALUES ('$status_log', '$username_admin', '$idadmin')

    ");
  }


  if (mysqli_affected_rows($koneksi) > 0) {
    echo "
    <script>

    alert('Data berhasil diubah');

    document.location.href='index.php?halaman=detailpasien&id=" . $pecah2["idpasien"] . "';

    </script>

    ";
  }
}
?>