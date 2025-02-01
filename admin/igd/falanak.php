
<?php 

$username=$_SESSION['admin']['username'];
$perawat=$_SESSION['admin']['namalengkap'];
$ambil=$koneksi->query("SELECT * FROM admin  WHERE username='$username';");
$pasien=$koneksi->query("SELECT * FROM igd WHERE idigd='$_GET[id]'");
$pecah=$pasien->fetch_assoc();
$fal=$koneksi->query("SELECT * FROM humpty_igd INNER JOIN igd WHERE idigd='$_GET[id]' AND tipe!= 'inap'");


 ?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>KHM WONOREJO</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <style>
    .hidden{
      display: hidden;
      max-height: 1px;
      overflow: hidden;
    }
  </style>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <script rel="javascript" type="text/javascript" href="js/jquery-1.11.3.min.js"></script>
</head>

<?php if(!isset($_GET['detail'])){ ?>
<body>
   <main>
    <div class="container">
      <div class="pagetitle">
      <h1>IGD</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active"><a href="index.php?halaman=daftarterapi" style="color:blue;">IGD</a></li>
          <li class="breadcrumb-item">Fallrisk Pediatri</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

 <form class="row g-3" method="post" enctype="multipart/form-data">
   <div class="container">
          <div class="row">
            <div class="col-md-12">

            <div class="card" style="margin-top:10px">
            <div class="card-body col-md-12">
              <h5 class="card-title">Data Pasien</h5>
              <!-- Multi Columns Form -->
                <div class="col-md-12">
                  <label for="inputName5" class="form-label">Nama Pasien</label>
                  <input type="text" class="form-control" name="pasien" id="inputName5" value="<?php echo $pecah['nama_pasien']?>" placeholder="Masukkan Nama Pasien">
                </div>
                <div class="col-md-12">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">NO RM Pasien</label>
                  <input type="text" class="form-control" name="no_rm" id="inputName5" value="<?php echo $pecah['no_rm']?>" placeholder="Masukkan No RM Pasien">
                </div>
                <input type="hidden" class="form-control" id="inputName5" name="tgl_masuk" value="<?php echo $pecah['tgl_masuk']?>">
              
                </div>
                </div>

            <div class="card">
            <div class="card-body">
              <h6 class="card-title">Asesmen</h6>

              <!-- Multi Columns Form -->
              <div class="row">
                <?php 
                date_default_timezone_set('Asia/Jakarta');
                ?>
                 <div class="col-md-12" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Tanggal</label>
                  <input type="datetime" class="form-control" name="tgl"  id="inputName5" placeholder="Sesuai dengan ICD 9 CM dan standar lainnya" value="<?= date("Y-m-d H:i:s") ?>">
                </div>
                <div class="col-md-12" style="margin-top:10px;">
                <label for="inputName5" class="form-label">Jenis</label><br>

                  <input type="checkbox" name="jenis_ket[]" value="PK"> PK</input><br>
                  <input type="checkbox" name="jenis_ket[]" value="J"> J</input><br>
                  <input type="checkbox" name="jenis_ket[]" value="I"> I</input><br>
                  <input type="checkbox" name="jenis_ket[]" value="DLL"> DLL</input><br>

                </div>
                 <div class="col-md-6" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Usia</label>
                    <select onchange="updateTotal()" name="usia" id="" class="form-control">
                        <option value="0">Pilih</option>
                        <option value="4">Dibawah 3 th</option>
                        <option value="3">3 - 7 th</option>
                        <option value="2">7 - 13 th</option>
                        <option value="1">> 13 th</option>
                    </select>
                </div>
                 <div class="col-md-6" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Jenis Kelamin</label>
                  <select onchange="updateTotal()" name="jk" id="" class="form-control">
                         <option value="0">Pilih</option>
                        <option value="2">Laki-laki</option>
                        <option value="1">Perempuan</option>
                    </select>
                </div>
                 <div class="col-md-6" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Diagnosis</label>
                  <select onchange="updateTotal()" name="diagnosis" id="" class="form-control">
                  <option value="0">Pilih</option>

                        <option value="4">Kelainan neurologi</option>
                        <option value="3">Perubahan oksigenasi
                                            (diagnostik respiratorik,
                                            dehidrasi, anemia, anoreksia,
                                            sinkop, pusing, dsb)</option>
                        <option value="2">Kelainan psikis/perilaku</option>
                        <option value="1">Diagnosis lain</option>
                    </select>
                </div>
                 <div class="col-md-6" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Gangguan Kognitif</label>
                  <select onchange="updateTotal()" name="kognitif" id="" class="form-control">
                  <option value="0">Pilih</option>

                        <option value="3">Tidak sadar keterbatasan diri</option>
                        <option value="2">Lupa keterbatasan</option>
                        <option value="1">Mengetahui kemampuan diri</option>
                    </select>
                </div>
                 <div class="col-md-6" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Faktor Lingkungan</label>
                  <select onchange="updateTotal()" name="lingkungan" id="" class="form-control">
                  <option value="0">Pilih</option>

                        <option value="4">Riwayat jatuh dari tempat tidur saat bayi-anak</option>
                        <option value="3">Px menggunakan alat bantu/diletakkan di box bayi/perabot</option>
                        <option value="2">Px berada di tempat tidur</option>
                        <option value="1">Area di luar ruang rawat</option>
                    </select>
                </div>
                 <div class="col-md-6" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Respon terhadap Operasi/sedasi/anastesi</label>
                  <select onchange="updateTotal()" name="respon" id="" class="form-control">
                  <option value="0">Pilih</option>

                        <option value="3">Dalam 24 jam</option>
                        <option value="2">Dalam 48 jam</option>
                        <option value="1">> 48 jam/lebih</option>
                    </select>
                </div>
                 <div class="col-md-12" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Penggunaan Medikametosa</label>
                  <select onchange="updateTotal()" name="medik" id="" class="form-control">
                  <option value="0">Pilih</option>

                        <option value="3">Penggunaan multiple: sedatif
                        (kec.pxICU yg menggunakan
                        sedasi dan paralisis), hipnosis,
                        barbiturat, fenotiazin,
                        antidepresan, pencahar, diuretik,
                        narkotse</option>
                        <option value="2">Penggunaan salah 1 obat diatas</option>
                        <option value="1">Pengobatan lain</option>
                    </select>
                </div>
                 <div class="col-md-6" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Jumlah Skor</label>
                  <input type="number" class="form-control" name="total_skor"  id="total" readonly>
                </div>
                 <div class="col-md-6" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Hasil</label>
                  <input type="text" class="form-control" name="jenis_skor"  id="status" readonly>
                </div><br>

                <script>
                               function updateTotal() {
                                 // Ambil nilai dari setiap elemen select
                                //  var riwayat_jatuh = parseInt(document.getElementsByName("riwayat_jatuh")[0].value);
                                 var riwayat_jatuh = parseInt(document.getElementsByName("usia")[0].value);
                                 var medis_sekunder = parseInt(document.getElementsByName("jk")[0].value);
                                 var alat_bantu = parseInt(document.getElementsByName("diagnosis")[0].value);
                                 var terpasang = parseInt(document.getElementsByName("kognitif")[0].value);
                                 var cara_jalan = parseInt(document.getElementsByName("lingkungan")[0].value);
                                 var status_mental = parseInt(document.getElementsByName("respon")[0].value);
                                 var status_mental2 = parseInt(document.getElementsByName("medik")[0].value);
                                 // Tambahkan elemen select lainnya di sini
                           
                                 // Hitung total nilai
                                 var total = riwayat_jatuh + medis_sekunder + alat_bantu + terpasang + cara_jalan + status_mental + status_mental2;
                                //  var total = riwayat_jatuh ;
                                 // Tambahkan nilai elemen select lainnya di sini
                               
                                 
                                 if(total >= 7 && total <= 11){
                                    var stts = 'Resiko Rendah';
                                 }

                                 if(total >= 12){
                                    var stts = 'Resiko Tinggi';
                                 }
                           
                                 // Tampilkan total nilai dalam elemen dengan ID 'total'
                                 document.getElementById('total').value = total;
                                 document.getElementById('status').value = stts;
                               }
                           </script>

                <label for="inputName5" style="margin-top:20px;" class="form-label">Obat-Obatan Yang Dikonsumsi</label><br>
                <div class="col-md-6" style="margin-top:10px;">
                  <input type="checkbox" name="obat[]" value="Psikotropika"> Psikotropika</input><br>
                  <input type="checkbox" name="obat[]" value="Opioid/Narkotik"> Opioid/Narkotik</input><br>
                  <input type="checkbox" name="obat[]" value="Anti- ansietas/CPZ"> Anti- ansietas/CPZ</input><br>
                  <input type="checkbox" name="obat[]" value="Diuretik"> Diuretik</input><br>
                  <input type="checkbox" name="obat[]" value="Hipnotik/sedative"> Hipnotik/sedative</input><br>

                </div>
                 <div class="col-md-6" style="margin-top:10px;">
                  <input type="checkbox" name="obat[]" value="Laksatif"> Laksatif</input><br>
                  <input type="checkbox" name="obat[]" value="Anti hipertensi"> Anti hipertensi</input><br>
                  <input type="checkbox" name="obat[]" value="Kardiovaskular"> Kardiovaskular</input><br>
                  <input type="checkbox" name="obat[]" value="Anti parkinson"> Anti parkinson</input><br>
                </div>
                </div>
        <br>
        <br>
              <h6 class="card-title">INTERVENSI RESIKO JATUH (dilakukan pada semua pasien saat setelah asesmen awal)</h6>
                <table class="table table-bordered">
                    <thead>
                        <th>No. </th>
                        <th>INTERVENSI RESIKO JATUH STANDAR</th>
                        <th>CHECKLIST</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1.</td>
                            <td>Orientasikan ruangan pada pasien dan keluarga.</td>
                            <td><input type="checkbox" name="no1"></td>
                        </tr>
                        <tr>
                            <td>2.</td>
                            <td>Beri edukasi pada pasien mengenai resiko terjadinya jatuh, sosialisasikan, tempelkan penanda
                                pada bed sisi kaki pasien dan digantung pada nomor kamar pasien,agar mudah dibaca dan tidak
                                hilang.</td>
                            <td><input type="checkbox" name="no2"></td>
                        </tr>
                        <tr>
                            <td>3.</td>
                            <td>Tempatkan pasien pada bed dan dilengkapi dengan penghalang, roda terkunci.</td>
                            <td><input type="checkbox" name="no3"></td>
                        </tr>
                        <tr>
                            <td>4.</td>
                            <td>Ciptakan lingkungan/ kamar cukup penerangan dan pencahayaan.</td>
                            <td><input type="checkbox" name="no4"></td>
                        </tr>
                        <tr>
                            <td>5.</td>
                            <td>Anjurkan pasien untuk menggunakan alas kaki atau sepatu yang tidak licin.</td>
                            <td><input type="checkbox" name="no5"></td>
                        </tr>
                        <tr>
                            <td>6.</td>
                            <td>Nilai kemampuan ke kamar mandi dan memberikan bantuan bila dibutuhkan.</td>
                            <td><input type="checkbox" name="no6"></td>
                        </tr>
                        <tr>
                            <td>7.</td>
                            <td>Anjurkan pasien/keluarga untuk meminta bantuan dengan memanggil petugas yang sedang
                                berjaga</td>
                            <td><input type="checkbox" name="no7"></td>
                        </tr>
                        <tr>
                            <td>8.</td>
                            <td>Ciptakan lingkungan bebas dari peralatan yang mengandung resiko seperti;kain karpet yang licin
                                dan selimut yang diletakkan dilantai bawah bed pasien</td>
                            <td><input type="checkbox" name="no8"></td>
                        </tr>
                    </tbody>
                </table>

                

                <div class="text-center" style="margin-top: 50px; margin-bottom: 80px;">
                  <button type="submit" name="save" class="btn btn-primary">Simpan</button>
                </div>
              </form><!-- End Multi Columns Form -->
              
            </div>
          </div>
        </div>
        </div>

        <div class="container">
          <div class="row">
            <div class="col-md-12">

            <div class="card" style="margin-top:10px">
            <div class="card-body col-md-12">
              <h5 class="card-title">Data Riwayat PENGKAJIAN SKALA RESIKO JATUH HUMPTY DUMPTY UNTUK PASIEN PEDIATRI (0 - 14 TH)</h5>

              <div class="table-responsive">
            <table id="myTable" class="table table-striped" style="width:100%">
            <thead>
            <tr>
                <th>No</th>
                <th>No RM</th>
                <th>Nama Pasien</th>
                <th>Tanggal Masuk</th>
                <th></th>
                <!-- <th>Aksi</th> -->
               
            </tr>
            </thead>
           <tbody>

        <?php $no=1 ?>

        <?php foreach ($fal as $pecah) : ?>

        <tr>
            <td><?php echo $no; ?></td>
            <td style="margin-top:10px;"><?php echo $pecah["no_rm"]; ?></td>
            <td style="margin-top:10px;"><?php echo $pecah["pasien"]; ?></td>
            <td style="margin-top:10px;"><?php echo $pecah["tgl_masuk"]; ?></td>
            <td>
            <div class="dropdown">
             <i data-bs-toggle="dropdown" style="color: blue; font-weight: bold; font-size: 20px;" class="bi bi-three-dots-vertical"></i>
             <ul class="dropdown-menu">

             <li><a href="index.php?halaman=falanak&id=<?php echo $pecah["id"]; ?>&detail" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-eye-fill" style="color:darkblue;"></i> Detail</a></li>
             <li><a href="index.php?halaman=hapusigd&id=<?php echo $pecah["id"]; ?>" class="dropdown-item" style="text-decoration: none; font-weight: bold; margin-left: 2px;" onclick="return confirm('Anda yakin mau menghapus item ini ?')">
            <i class="bi bi-trash" style="color:red;"></i> Hapus</a></li>
             </ul>
            </div>
            </td>
        </tr>

        <?php $no +=1 ?>
        <?php endforeach ?>

    </tbody>
        </table>
                    
            </div>

                    </div>
                 </div>
             </div>
           </div>
        </div>
    
      </div>
     </div>
    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

</body>
<?php }else{ 
        $d=$koneksi->query("SELECT * FROM humpty_igd WHERE id='$_GET[id]'")->fetch_assoc();
    
    ?>

    <body>
   <main>
    <div class="container">
      <div class="pagetitle">
      <h1>IGD</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active"><a href="index.php?halaman=daftarterapi" style="color:blue;">IGD</a></li>
          <li class="breadcrumb-item">Detail Fallrisk Pediatri</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

 <form class="row g-3" method="post" enctype="multipart/form-data">
   <div class="container">
          <div class="row">
            <div class="col-md-12">

            <div class="card" style="margin-top:10px">
            <div class="card-body col-md-12">
              <h5 class="card-title">Data Pasien</h5>
              <!-- Multi Columns Form -->
                <div class="col-md-12">
                  <label for="inputName5" class="form-label">Nama Pasien</label>
                  <input type="text" class="form-control" name="pasien" id="inputName5" value="<?php echo $d['pasien']?>" placeholder="Masukkan Nama Pasien" readonly>
                </div>
                <div class="col-md-12">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">NO RM Pasien</label>
                  <input type="text" class="form-control" name="no_rm" id="inputName5" value="<?php echo $d['no_rm']?>" placeholder="Masukkan No RM Pasien" readonly>
                </div>
                <input type="hidden" class="form-control" id="inputName5" name="tgl_masuk" value="<?php echo $d['tgl_masuk']?>" readonly>
              
                </div>
                </div>

            <div class="card">
            <div class="card-body">
              <h6 class="card-title">Asesmen</h6>

              <!-- Multi Columns Form -->
              <div class="row">
                <?php 
                date_default_timezone_set('Asia/Jakarta');
                ?>
                 <div class="col-md-12" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Tanggal</label>
                  <input type="datetime" class="form-control" name="tgl"  id="inputName5" placeholder="Sesuai dengan ICD 9 CM dan standar lainnya" value="<?= $d["tgl"] ?>" readonly>
                </div>
                <div class="col-md-12" style="margin-top:10px;">
                <label for="inputName5" class="form-label">Jenis</label><br>
                <input type="text" class="form-control" name="jenis_ket"  id="inputName5" placeholder="" value="<?= $d["jenis_ket"] ?>" readonly>

                </div>
                 <div class="col-md-6" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Usia</label>
                    <select onchange="updateTotal()" name="usia" id="" class="form-control" readonly>
                        <option value="<?= $d["usia"] ?>"><?= $d["usia"] ?></option>
                        <!-- <option value="4">Dibawah 3 th</option>
                        <option value="3">3 - 7 th</option>
                        <option value="2">7 - 13 th</option>
                        <option value="1">> 13 th</option> -->
                    </select>
                </div>
                 <div class="col-md-6" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Jenis Kelamin</label>
                  <select onchange="updateTotal()" name="jk" id="" class="form-control" readonly>
                  <option value="<?= $d["jk"] ?>"><?= $d["jk"] ?></option>
<!-- 
                        <option value="2">Laki-laki</option>
                        <option value="1">Perempuan</option> -->
                    </select>
                </div>
                 <div class="col-md-6" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Diagnosis</label>
                  <select onchange="updateTotal()" name="diagnosis" id="" class="form-control" readonly>
                  <option value="<?= $d["diagnosis"] ?>"><?= $d["diagnosis"] ?></option>
                        <!-- <option value="4">Kelainan neurologi</option>
                        <option value="3">Perubahan oksigenasi
                                            (diagnostik respiratorik,
                                            dehidrasi, anemia, anoreksia,
                                            sinkop, pusing, dsb)</option>
                        <option value="2">Kelainan psikis/perilaku</option>
                        <option value="1">Diagnosis lain</option> -->
                    </select>
                </div>
                 <div class="col-md-6" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Gangguan Kognitif</label>
                  <select onchange="updateTotal()" name="kognitif" id="" class="form-control" readonly>                        
                  <option value="<?= $d["kognitif"] ?>"><?= $d["kognitif"] ?></option>
<!-- 
                        <option value="3">Tidak sadar keterbatasan diri</option>
                        <option value="2">Lupa keterbatasan</option>
                        <option value="1">Mengetahui kemampuan diri</option> -->
                    </select>
                </div>
                 <div class="col-md-6" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Faktor Lingkungan</label>
                  <select onchange="updateTotal()" name="lingkungan" id="" class="form-control" readonly>
                  <option value="<?= $d["lingkungan"] ?>"><?= $d["lingkungan"] ?></option>
<!-- 
                        <option value="4">Riwayat jatuh dari tempat tidur saat bayi-anak</option>
                        <option value="3">Px menggunakan alat bantu/diletakkan di box bayi/perabot</option>
                        <option value="2">Px berada di tempat tidur</option>
                        <option value="1">Area di luar ruang rawat</option> -->
                    </select>
                </div>
                 <div class="col-md-6" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Respon terhadap Operasi/sedasi/anastesi</label>
                  <select onchange="updateTotal()" name="respon" id="" class="form-control" readonly>
                  <option value="<?= $d["respon"] ?>"><?= $d["respon"] ?></option>
<!-- 
                        <option value="3">Dalam 24 jam</option>
                        <option value="2">Dalam 48 jam</option>
                        <option value="1">> 48 jam/lebih</option> -->
                    </select>
                </div>
                 <div class="col-md-12" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Penggunaan Medikametosa</label>
                  <select onchange="updateTotal()" name="medik" id="" class="form-control" readonly>
                  <!-- <option value="<?= $d["medik"] ?>"><?= $d["medik"] ?></option>

                        <option value="3">Penggunaan multiple: sedatif
                        (kec.pxICU yg menggunakan
                        sedasi dan paralisis), hipnosis,
                        barbiturat, fenotiazin,
                        antidepresan, pencahar, diuretik,
                        narkotse</option>
                        <option value="2">Penggunaan salah 1 obat diatas</option>
                        <option value="1">Pengobatan lain</option> -->
                    </select>
                </div>
                 <div class="col-md-6" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Jumlah Skor</label>
                  <input type="number" class="form-control" name="total_skor" value="<?= $d["total_skor"] ?>"  id="total" readonly>
                </div>
                 <div class="col-md-6" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Hasil</label>
                  <input type="text" class="form-control" name="jenis_skor" value="<?= $d["jenis_skor"] ?>"  id="status" readonly>
                </div><br>

                <!-- <script>
                               function updateTotal() {
                                 // Ambil nilai dari setiap elemen select
                                //  var riwayat_jatuh = parseInt(document.getElementsByName("riwayat_jatuh")[0].value);
                                 var riwayat_jatuh = parseInt(document.getElementsByName("usia")[0].value);
                                 var medis_sekunder = parseInt(document.getElementsByName("jk")[0].value);
                                 var alat_bantu = parseInt(document.getElementsByName("diagnosis")[0].value);
                                 var terpasang = parseInt(document.getElementsByName("kognitif")[0].value);
                                 var cara_jalan = parseInt(document.getElementsByName("lingkungan")[0].value);
                                 var status_mental = parseInt(document.getElementsByName("respon")[0].value);
                                 var status_mental2 = parseInt(document.getElementsByName("medik")[0].value);
                                 // Tambahkan elemen select lainnya di sini
                           
                                 // Hitung total nilai
                                 var total = riwayat_jatuh + medis_sekunder + alat_bantu + terpasang + cara_jalan + status_mental + status_mental2;
                                //  var total = riwayat_jatuh ;
                                 // Tambahkan nilai elemen select lainnya di sini
                               
                                 
                                 if(total >= 7 && total <= 11){
                                    var stts = 'Resiko Rendah';
                                 }

                                 if(total >= 12){
                                    var stts = 'Resiko Tinggi';
                                 }
                           
                                 // Tampilkan total nilai dalam elemen dengan ID 'total'
                                 document.getElementById('total').value = total;
                                 document.getElementById('status').value = stts;
                               }
                           </script> -->

                <label for="inputName5" style="margin-top:20px;" class="form-label">Obat-Obatan Yang Dikonsumsi</label><br>
                <div class="col-md-6" style="margin-top:10px;">
                  <input type="text" name="obat" value="<?= $d["obat"] ?>" class="form-control"></input>
                 
                </div>
               
                </div>
        <br>
        <br>
              <h6 class="card-title">INTERVENSI RESIKO JATUH (dilakukan pada semua pasien saat setelah asesmen awal)</h6>
                <table class="table table-bordered">
                    <thead>
                        <th>No. </th>
                        <th>INTERVENSI RESIKO JATUH STANDAR</th>
                        <th>CHECKLIST</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1.</td>
                            <td>Orientasikan ruangan pada pasien dan keluarga.</td>
                            <td><?php if($d['no1'] == 'on'){ ?>  ✔ <?php } ?></td>
                        </tr>
                        <tr>
                            <td>2.</td>
                            <td>Beri edukasi pada pasien mengenai resiko terjadinya jatuh, sosialisasikan, tempelkan penanda
                                pada bed sisi kaki pasien dan digantung pada nomor kamar pasien,agar mudah dibaca dan tidak
                                hilang.</td>
                            <td><?php if($d['no2'] == 'on'){ ?>  ✔ <?php } ?></td>
                        </tr>
                        <tr>
                            <td>3.</td>
                            <td>Tempatkan pasien pada bed dan dilengkapi dengan penghalang, roda terkunci.</td>
                            <td><?php if($d['no3'] == 'on'){ ?>  ✔ <?php } ?></td>
                        </tr>
                        <tr>
                            <td>4.</td>
                            <td>Ciptakan lingkungan/ kamar cukup penerangan dan pencahayaan.</td>
                            <td><?php if($d['no4'] == 'on'){ ?>  ✔ <?php } ?></td>
                        </tr>
                        <tr>
                            <td>5.</td>
                            <td>Anjurkan pasien untuk menggunakan alas kaki atau sepatu yang tidak licin.</td>
                            <td><?php if($d['no5'] == 'on'){ ?>  ✔ <?php } ?></td>
                        </tr>
                        <tr>
                            <td>6.</td>
                            <td>Nilai kemampuan ke kamar mandi dan memberikan bantuan bila dibutuhkan.</td>
                            <td><?php if($d['no6'] == 'on'){ ?>  ✔ <?php } ?></td>
                        </tr>
                        <tr>
                            <td>7.</td>
                            <td>Anjurkan pasien/keluarga untuk meminta bantuan dengan memanggil petugas yang sedang
                                berjaga</td>
                            <td><?php if($d['no7'] == 'on'){ ?>  ✔ <?php } ?></td>
                        </tr>
                        <tr>
                            <td>8.</td>
                            <td>Ciptakan lingkungan bebas dari peralatan yang mengandung resiko seperti;kain karpet yang licin
                                dan selimut yang diletakkan dilantai bawah bed pasien</td>
                            <td><?php if($d['no8'] == 'on'){ ?>  ✔ <?php } ?></td>
                        </tr>
                    </tbody>
                </table>

                

              </form><!-- End Multi Columns Form -->
              
            </div>
          </div>
        </div>
        </div>

      
    
      </div>
     </div>
    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

</body>
<?php }  ?>





</html>


<?php  
if (isset ($_POST['save'])) {


$ob = $_POST["obat"];
 $obat = implode(", ",$ob);

   
  $koneksi->query("INSERT INTO humpty_igd 

    (tgl, jenis_ket, usia, jk, diagnosis, kognitif, lingkungan, respon, medik, total_skor, jenis_skor, perawat, ruang, obat, no1, no2, no3, no4, no5, no6, no7, no8, pasien, no_rm, tgl_masuk)

    VALUES ('$_POST[tgl]', '$_POST[jenis_ket]', '$_POST[usia]', '$_POST[jk]', '$_POST[diagnosis]', '$_POST[kognitif]', '$_POST[lingkungan]', '$_POST[respon]', '$_POST[medik]', '$_POST[total_skor]', '$_POST[jenis_skor]', '$perawat', '$_POST[ruang]', '".$obat."', '$_POST[no1]','$_POST[no2]','$_POST[no3]','$_POST[no4]','$_POST[no5]','$_POST[no6]','$_POST[no7]','$_POST[no8]','$_POST[pasien]','$_POST[no_rm]', '$_POST[tgl_masuk]')

    ");

  echo "
  <script>
  alert('Data berhasil ditambah');
  document.location.href='index.php?halaman=daftarigd;

  </script>

  ";

// } else {

//   echo "
//   <script>
//   alert('GAGAL MENGHAPUS DATA');
//   document.location.href='index.php?halaman=daftarigd;
  
//   </script>

//   ";

// }


}


//   // $koneksi->query("INSERT INTO log_user 

//   //   (status_log, username_admin, idadmin)

//   //   VALUES ('$status_log', '$username_admin', '$idadmin')

//   //   ");

// }

?>