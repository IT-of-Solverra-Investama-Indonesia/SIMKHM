<?php
if (isset($_GET['id'])) {
  $pasienkosmetik = $koneksi->query("SELECT * FROM pasien_kosmetik where idpasien = '$_GET[id]' limit 1")->fetch_assoc();
  // print_r($pasienkosmetik); 
  $jenis_kelamin = $pasienkosmetik['jenis_kelamin'];
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
  <!-- DATATABLES -->
  <!-- !-- DataTables  -->

  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
  <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <link src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
  <link src="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css">
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>

  <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
</head>


<body>
  <main>
    <div class="container">
      <div class="pagetitle">
        <h1> Pasien Kosmetik :<?= $pasienkosmetik['nama_lengkap']; ?></h1>

      </div><!-- End Page Title -->

      <section class="section  py-4">
        <div class="container">



          <div class="row">
            <div class="col-lg-12 col-md-12">

              <div class="card">
                <div class="card-body">

                  <h5 class="card-title">Data Pasien Kosmetik</h5>


                </div>

              </div>

              <div class="card shadow p-2">
                <h4>Daftar Pasien</h4>
                <form method="POST">
                  <div class="row" id="f1">
                    <div class="col-md-12 mb-2">
                      <p class="mb-0" align="left">Email</p>
                      <input type="email" value="<?= $pasienkosmetik['email'] ?>" required class="form-control" name="email" id="inputName5" placeholder="Masukan Email Anda">
                    </div>
                    <div class="col-md-12 mb-2">
                      <p class="mb-0" align="left">Password</p>
                      <input type="password" class="form-control" value="<?= $pasienkosmetik['password'] ?>" name="password" id="inputName5" placeholder="Password">
                    </div>
                    <div class="col-md-12 mb-2">
                      <p class="mb-0" align="left">Nama Lengkap</p>
                      <input type="text" value="<?= $pasienkosmetik['nama_lengkap'] ?>" required class="form-control" name="nama_lengkap" id="inputName5" placeholder="Masukkan Nama Pasien">
                    </div>
                    <div class="col-md-12 mb-2">
                      <p class="mb-0" align="left">Nama Ayah</p>
                      <input type="text" value="<?= $pasienkosmetik['nama_ibu'] ?>" required class="form-control" name="nama_ibu" id="inputName5" placeholder="Masukkan Nama Ayah Pasien">
                    </div>
                    <div class="col-md-12 mb-2">
                      <p class="mb-0" align="left">Tanggal Lahir</p>
                      <input type="date" value="<?= $pasienkosmetik['tgl_lahir'] ?>" required class="form-control" name="tgl_lahir" id="inputName5" placeholder="Masukkan Nama Pasien">
                    </div>
                    <div class="col-md-12 mb-2">
                      <p class="mb-0" align="left">Jenis Kelamin</p>
                      <select id="inputState" name="jenis_kelamin" required class="form-select">

                        <option disabled selected>Pilih</option>
                        <!-- <option value="0">Tidak Diketahui</option> -->
                        <!-- <option value="" <?php echo ($jenis_kelamin == '') ? 'selected' : ''; ?>>Pilih</option> -->
                        <option value="1" <?php echo ($jenis_kelamin == '1') ? 'selected' : ''; ?>>Laki-Laki</option>
                        <option value="2" <?php echo ($jenis_kelamin == '2') ? 'selected' : ''; ?>>Perempuan</option>
                        <!-- <option value="3">Tidak Dapat Ditentukan</option>
                            <option value="4">Tidak Mengisi</option> -->
                      </select>
                    </div>
                  </div>
                  <div class="row hide-form" id="f2">
                    <div class="col-md-12 mb-2">
                      <p class="mb-0" align="left" for="inputCity" required class="form-label">No. HP </p>
                      <input type="text" value="<?= $pasienkosmetik['nohp'] ?>" name="nohp" required class="form-control" id="inputCity" placeholder="Masukkan No. HP Pasien">
                    </div>
                    <div class="col-md-12 mb-2">
                      <p class="mb-0" align="left" for="inputCity" required class="form-label">No. KTP</p>
                      <input type="number" value="<?= $pasienkosmetik['no_identitas'] ?>" name="no_identitas" required class="form-control" id="inputCity" placeholder="Masukkan No. Identitas Pasien">
                    </div>
                    <div class="col-md-12 mb-2">
                      <p class="mb-0" align="left" for="inputState" required class="form-label">Provinsi</p>
                      <select id="provinsi" required class="form-select">

                        <option value="<?= $pasienkosmetik['provinsi'] ?>" selected><?= $pasienkosmetik['provinsi'] ?></option>
                        <option hidden>Pilih</option>
                      </select>
                      <input type="text" hidden id="provins" name="provinsi">
                    </div>
                    <div class="col-md-12 mb-2">
                      <p class="mb-0" align="left" for="inputState" required class="form-label">Kota/Kabupaten</p>
                      <select id="kota" required class="form-select">
                        <option value="<?= $pasienkosmetik['kota'] ?>" selected><?= $pasienkosmetik['kota'] ?></option>
                        <option hidden>Pilih</option>
                      </select>
                      <input type="text" hidden id="kot" name="kota">
                    </div>
                    <div class="col-md-12 mb-2">
                      <p class="mb-0" align="left" for="inputState" required class="form-label">Kecamatan</p>
                      <select id="kecamatan" required class="form-select">
                        <option value="<?= $pasienkosmetik['provinsi'] ?>" selected><?= $pasienkosmetik['kecamatan'] ?></option>

                        <option hidden>Pilih</option>
                      </select>
                      <input type="text" hidden id="kecamata" name="kecamatan">
                    </div>
                    <div class="col-md-12 mb-2">
                      <p class="mb-0" align="left" for="inputState" required class="form-label">Desa/Kelurahan</p>
                      <select id="kelurahan" required class="form-select">
                        <option value="<?= $pasienkosmetik['kelurahan'] ?>" selected><?= $pasienkosmetik['kelurahan'] ?></option>

                        <option hidden>Pilih</option>
                      </select>
                      <input type="text" hidden id="keluraha" name="kelurahan">
                    </div>
                    <div class="col-md-12 mb-2">
                      <p class="mb-0" align="left" for="inputCity" required class="form-label">Kode Pos</p>
                      <input type="text" value="<?= $pasienkosmetik['kode_pos'] ?>" name="kode_pos" id="kode_pos" required class="form-control" placeholder="Masukkan kode pos">
                    </div>
                    <div class="col-md-12 mb-2">
                      <p class="mb-0" align="left" for="inputCity" required class="form-label">Alamat Rumah (Dusun, RT, RW)</p>
                      <textarea type="text" value="<?= $pasienkosmetik['alamat'] ?>" name="alamat" required class="form-control" style="height: 100px;" id="alamatAsal" value="" placeholder="Masukkan alamat"><?= $pasienkosmetik['alamat'] ?></textarea>
                    </div>
                    <div class="col-md-12 mb-2">
                      <p align="left" class="mb-0" for="inputCity" required class="form-label my-0">Alamat Domisili Sekarang</p>
                      <p align="left" class="mb-0">
                        <span type="button" onclick="samakanAlamat()" class="btn btn-sm btn-primary mb-2">Samakan dengan Alamat Asal</span>
                      </p>
                      <textarea type="text" id="alamatDomisili" name="alamat_dom" required class="form-control" style="height: 100px;" value="<?= $pasienkosmetik['alamat_dom'] ?>" placeholder="Masukkan Alamat Domisili Sekarang"><?= $pasienkosmetik['alamat_dom'] ?></textarea>
                    </div>
                    <div class="col-md-12 mb-2 hide-form">
                      <p align="left" class="mb-0" for="inputState" required class="form-label">Nomor BPJS</p>
                      <input type="text" required class="form-control" name="no_bpjs" value="<?= $pasienkosmetik['no_bpjs'] ?>">
                    </div>
                    <div class="col-md-12 mb-2 hide-form">
                      <p align="left" class="mb-0" for="inputState" required class="form-label">Nomor RM</p>
                      <input type="text" required class="form-control" name="no_rm" value="<?= $pasienkosmetik['no_rm'] ?>">
                    </div>
                  </div>
                  <center>
                    <button type="submit" name="update" class="btn btn-success ">Update</button>

                  </center>

                </form>
              </div>
            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->

  </div>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


</body>

</html>

<script>
  $(document).ready(function() {
    $('#myTable').DataTable({
      search: true,
      pagination: true
    });
  });
</script>
<script>
  function samakanAlamat() {
    document.getElementById("alamatDomisili").innerHTML = 'Provinsi ' + document.getElementById("provins").value + ', ' + 'Kabupaten/Kota ' + document.getElementById("kot").value + ', ' + 'Kecamatan ' + document.getElementById("kecamata").value + ', ' + 'Desa/Kelurahan ' + document.getElementById("keluraha").value + ', ' + document.getElementById("alamatAsal").value + ', ' + document.getElementById("kode_pos").value;
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
          optionElement.text = propinsiName; // Teks yang akan ditampilkan pada option
          // if (propinsiName === savedProvinsi) {
          //   optionElement.selected = true;
          // }

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
<?php
if (isset($_POST['update'])) {
  $nama_lengkap = htmlspecialchars($_POST["nama_lengkap"]);
  $nama_ibu = htmlspecialchars($_POST["nama_ibu"]);
  $nohp = htmlspecialchars($_POST["nohp"]);
  $tgl_lahir = htmlspecialchars($_POST["tgl_lahir"]);
  $jenis_identitas = 'KTP';
  $no_identitas = htmlspecialchars($_POST["no_identitas"]);
  $jenis_kelamin = htmlspecialchars($_POST["jenis_kelamin"]);
  $provinsi = htmlspecialchars($_POST["provinsi"]);
  $kota = htmlspecialchars($_POST["kota"]);
  $kelurahan = htmlspecialchars($_POST["kelurahan"]);
  $kecamatan = htmlspecialchars($_POST["kecamatan"]);
  $kode_pos = htmlspecialchars($_POST["kode_pos"]);
  $alamat = htmlspecialchars($_POST["alamat"]);
  $alamat_dom = htmlspecialchars($_POST["alamat_dom"]);
  $email = htmlspecialchars($_POST["email"]);
  $password = htmlspecialchars($_POST["password"]);
  $no_rm = htmlspecialchars($_POST["no_rm"]);
  $kategori = '';
  $pembiayaan = '';
  $status_nikah = '';
  $foto = '';

  // Ambil Nomor RM Terakhir + 1
  // $no_rm = '';

  //hitung usia
  $lahir    = new DateTime($tgl_lahir);
  $today        = new DateTime();
  $umur = $today->diff($lahir);
  $umur2 = $umur->y . " Tahun," . $umur->m . " Bulan," . $umur->d . " Hari";

  if ($password) {
    $koneksi->query("UPDATE pasien_kosmetik SET nama_lengkap = '$nama_lengkap', nama_ibu = '$nama_ibu', 
    nohp = '$nohp', email = '$email',  no_identitas = '$no_identitas',  tgl_lahir = '$tgl_lahir',  jenis_kelamin = '$jenis_kelamin', 
    jenis_identitas = '$jenis_identitas', provinsi = '$provinsi', kota = '$kota', kelurahan = '$kelurahan', kecamatan = '$kecamatan',  
    kode_pos = '$kode_pos', alamat = '$alamat',alamat_dom = '$alamat_dom', kategori = '$kategori', status_nikah = '$status_nikah', pembiayaan = '$pembiayaan', 
    foto = '$foto', no_rm = '$no_rm', umur = '$umur2', no_bpjs = '$_POST[no_bpjs]', password = '$password' WHERE idpasien = '$_GET[id]'");
  } else {
    $koneksi->query("UPDATE pasien_kosmetik SET nama_lengkap = '$nama_lengkap', nama_ibu = '$nama_ibu', 
    nohp = '$nohp', email = '$email',  no_identitas = '$no_identitas',  tgl_lahir = '$tgl_lahir',  jenis_kelamin = '$jenis_kelamin', 
    jenis_identitas = '$jenis_identitas', provinsi = '$provinsi', kota = '$kota', kelurahan = '$kelurahan', kecamatan = '$kecamatan',  
    kode_pos = '$kode_pos', alamat = '$alamat',alamat_dom = '$alamat_dom', kategori = '$kategori', status_nikah = '$status_nikah', pembiayaan = '$pembiayaan', 
    foto = '$foto', no_rm = '$no_rm', umur = '$umur2', no_bpjs = '$_POST[no_bpjs]' WHERE idpasien = '$_GET[id]'");
  }



  echo  "
                          <script>
                            alert('Berhasil mengupdate data');
                            document.location.href='index.php?halaman=daftarpasienkosmetik';

                  
                          </script>
                        ";
}
?>