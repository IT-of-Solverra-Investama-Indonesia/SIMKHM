<?php 

$id=$_GET['id'];

//var_dump($id)

$ambil=$koneksi->query("SELECT * FROM pasien WHERE idpasien='$_GET[id]' ");

$pecah=$ambil->fetch_assoc();


 ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Pasien</title>
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
          <li class="breadcrumb-item active"><a href="index.php?halaman=detailpasien">Detail Pasien</a></li>
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
              <form class="row g-3" method="post">

                <div style="margin-top: 50px; margin-bottom: 80px; text-align: right;">
               
                <a href="index.php?halaman=ubahpasien&id=<?php echo $pecah["idpasien"]; ?>" class="btn btn-primary"><i class="bi bi-pencil"></i> Edit</a>

                </div>

                 <div class="row mb-3">
                      <label for="profileImage" class="col-md-2 col-form-label">Foto Profil</label>
                      <div class="col-md-6 col-lg-5">
                        <img style="border-radius: 5px; width:90px;" src="../pasien/foto/<?php echo $pecah['foto'] ?>" alt="Profile">
                      </div>
                    </div>
                <div class="col-md-6">
                  <label style="font-weight: bold;" for="inputName5" class="form-label">Nama Lengkap</label>
                  <h6><?php echo $pecah['nama_lengkap'] ?></h6>
                </div>
                <div class="col-md-6">
                  <label style="font-weight: bold;" for="inputName5" class="form-label">Nama Ayah</label>
                  <h6><?php echo $pecah['nama_ibu'] ?></h6>
                </div>
                <div class="col-md-6">
                   <label style="font-weight: bold;" for="inputName5" class="form-label">Tanggal Lahir</label>
                  <h6><?php echo date("d-m-Y", strtotime($pecah['tgl_lahir'])) ?></h6>
                </div>
               
               <div class="col-md-6">
                  <label style="font-weight: bold;" for="inputName5" class="form-label">Jenis Kelamin</label>
                  <?php if($pecah['jenis_kelamin'] != ''){?>
                      <?php if($pecah['jenis_kelamin'] == '1'){?>
                        <h6>Laki-laki</h6>
                      <?php }elseif($pecah['jenis_kelamin'] =='2'){?>
                        <h6>Perempuan</h6>
                      <?php }?>
                  <?php } ?>
                </div>
                <br>
                <br>
                <div>
                  <h5 class="card-title">Data Kontak</h5>
                </div>
                <div class="col-md-6">
                   <label style="font-weight: bold;" for="inputName5" class="form-label">No. HP</label>
                  <h6><?php echo $pecah['nohp'] ?></h6>
                </div> 
                <div class="col-md-6">
                  <label style="font-weight: bold;" for="inputName5" class="form-label">Email</label>
                  <h6><?php echo $pecah['email'] ?></h6>
                </div>
                <div class="col-md-6">
                  <label style="font-weight: bold;" for="inputName5" class="form-label">Jenis Kartu Identitas</label>
                  <h6><?php echo $pecah['jenis_identitas'] ?></h6>
                </div>
                <div class="col-md-6">
                  <label style="font-weight: bold;" for="inputName5" class="form-label">No. Kartu Identitas</label>
                  <h6><?php echo $pecah['no_identitas'] ?></h6>
                </div>
                
                <div>
                  <h5 class="card-title">Data Alamat</h5>
                </div>
              
                <div class="col-md-6">
                  <label style="font-weight: bold;" for="inputName5" class="form-label">Provinsi</label>
                  <h6><?php echo $pecah['provinsi'] ?></h6>
                </div>
                <div class="col-md-6">
                  <label style="font-weight: bold;" for="inputName5" class="form-label">Kabupaten/Kota</label>
                  <h6><?php echo $pecah['kota'] ?></h6>
                </div>
                <div class="col-md-6">
                  <label style="font-weight: bold;" for="inputName5" class="form-label">Kecamatan</label>
                  <h6><?php echo $pecah['kecamatan'] ?></h6>
                </div>
                <div class="col-md-6">
                 <label style="font-weight: bold;" for="inputName5" class="form-label">Desa/Kelurahan</label>
                  <h6><?php echo $pecah['kelurahan'] ?></h6>
                </div>

                <div class="col-md-6">
                  <label style="font-weight: bold;" for="inputName5" class="form-label">Kode Pos</label>
                  <h6><?php echo $pecah['kode_pos'] ?></h6>
                </div>

                 <div class="col-md-6">
                <label style="font-weight: bold;" for="inputName5" class="form-label">Alamat Rumah</label>
                  <h6><?php echo $pecah['alamat'] ?></h6>
                </div>

                <div>
                  <h5 class="card-title">Lain-Lain</h5>
                </div>
                <!-- <div class="col-md-6">
                  <label style="font-weight: bold;" for="inputName5" class="form-label">Kategori Pasien</label>
                  <h6><?php echo $pecah['kategori'] ?></h6>
                </div> -->
                <div class="col-md-12">
                  <label style="font-weight: bold;" for="inputName5" class="form-label">Pembiayaan</label>
                  <h6><?php echo $pecah['pembiayaan'] ?></h6>
                </div>
                <div class="col-md-12">
                  <label style="font-weight: bold;" for="inputName5" class="form-label">Nomor BPJS</label>
                  <h6><?php echo $pecah['no_bpjs'] ?></h6>
                </div>
              </div>
            
              </form><!-- End Multi Columns Form -->
              <br>
             <br>
             <br>
            </div>
          </div>

        </div>
    
            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

 <script>
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

</body>

</html>


<?php 


if (isset ($_POST['save'])) 
{

$nama_lengkap=htmlspecialchars($_POST["nama_lengkap"]);

$nohp=htmlspecialchars($_POST["nohp"]);

$email=htmlspecialchars($_POST["email"]);

$tgl_lahir=htmlspecialchars($_POST["tgl_lahir"]);

$jenis_identitas=htmlspecialchars($_POST["jenis_identitas"]);

$no_identitas=($_POST["no_identitas"]);

$jenis_kelamin=htmlspecialchars($_POST["jenis_kelamin"]);

$provinsi=htmlspecialchars($_POST["provinsi"]);

$kota=htmlspecialchars($_POST["kota"]);

$kelurahan=htmlspecialchars($_POST["kelurahan"]);

$kode_pos=htmlspecialchars($_POST["kode_pos"]);

$alamat=htmlspecialchars($_POST["alamat"]);

$kategori=htmlspecialchars($_POST["kategori"]);

$pembiayaan=htmlspecialchars($_POST["pembiayaan"]);

  $foto=$_FILES['foto']['name'];

  $lokasi=$_FILES['foto']['tmp_name'];

  move_uploaded_file($lokasi, '../pasien/foto/'.$foto);

  $koneksi->query("INSERT INTO pasien 

    (nama_lengkap, nohp, email, no_identitas,  tgl_lahir, jenis_kelamin, jenis_identitas, provinsi, kota, kelurahan, kode_pos, alamat, kategori, pembiayaan, foto)

    VALUES ('$nama_lengkap', '$nohp', '$email', '$no_identitas', '$tgl_lahir', '$jenis_kelamin', '$jenis_identitas', '$provinsi', '$kota', '$kelurahan', '$kode_pos', '$alamat', '$kategori', '$pembiayaan', '$foto')

    ");


if (mysqli_affected_rows($koneksi)>0) {
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

}

?>


