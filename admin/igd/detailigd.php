
<?php 

error_reporting(0);
$username=$_SESSION['admin']['username'];
$level=$_SESSION['admin']['level'];
$shift=$_SESSION['shift'];
$ambil=$koneksi->query("SELECT * FROM admin  WHERE username='$username';");
$igd=$koneksi->query("SELECT * FROM igd WHERE idigd='$_GET[id]';");
$igd=$igd->fetch_assoc();
$pasien=$koneksi->query("SELECT * FROM pasien INNER JOIN kajian_awal WHERE idpasien='$_GET[id]' AND no_rm = norm;");
$pecah=$pasien->fetch_assoc();


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
<body>

 

   <main>
    <div class="container">
      <div class="pagetitle">
      <h1>IGD</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active"><a href="index.php?halaman=daftarterapi" style="color:blue;">IGD</a></li>
          <li class="breadcrumb-item">Buat IGD</li>
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
                  <input readonly  type="text" class="form-control" name="nama_pasien" id="inputName5" value="<?php echo $igd['nama_pasien']?>" placeholder="Masukkan Nama Pasien">
                </div>
                <div class="col-md-12">
                  <label for="inputName5" style="margin-top: 10px;" class="form-label">NO RM Pasien</label>
                  <input readonly  type="text" class="form-control" name="no_rm" id="inputName5" value="<?php echo $igd['no_rm']?>" placeholder="Masukkan No RM Pasien">
                </div>
                <!-- <input readonly  type="hidden" class="form-control" id="inputName5" name="id_rm" value="<?php echo $pecah['id_rm']?>"> -->

                <div class="col-md-12">
                  <label for="inputState" class="form-label">Dokter</label>
                  <input readonly  type="text" class="form-control" name="no_rm" id="inputName5" value="<?php echo $igd['dokter']?>" placeholder="Masukkan No RM Pasien">
                </div>
                <!-- <div class="col-md-12">
                  <label for="inputState" class="form-label">Pembayaran</label>
                  <select id="inputState" name="carabayar" class="form-select">
                    <option hidden>Pilih Pembayaran</option>
                    <option value="bpjs">bpjs</option>
                    <option value="umum">umum</option>
                  </select>
                </div> -->
              
                </div>
                </div>

            <div class="card">
            <div class="card-body">
              <h6 class="card-title">Formulir Triase dan Gawat Darurat</h6>

             
              
              <hr>
              <div style="margin-top:10px">
              <h5 class="card-title">Asesmen Keperawatan</h5>
              </div>

              <!-- Multi Columns Form -->
              <div class="row">
                <?php 
                date_default_timezone_set('Asia/Jakarta');
                ?>
                 <div class="col-md-6" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Tanggal Masuk</label>
                  <input readonly  type="date" class="form-control" name="tgl_masuk"  id="inputName5" placeholder="Sesuai dengan ICD 9 CM dan standar lainnya" value="<?php echo $igd['tgl_masuk']?>">
                </div>
                <div class="col-md-6" style="margin-top:10px;">
                  <label for="inputName5" class="form-label">Jam Masuk</label>
                  <input readonly  type="time" class="form-control" name="jam_masuk" id="inputName5" value="<?php echo $igd['jam_masuk']?>" placeholder="Nama lengkap sesuai dengan kartu identitas">
                </div>
                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Sarana Transportasi Kedatangan </label>
                  <select id="inputState" name="transportasi" class="form-select">
                    <option value="<?php echo $igd['transportasi']?>" hidden><?php echo $igd['transportasi']?></option>
                    <option value="Ambulans">1. Ambulans</option>
                    <option value="Mobil">2. Mobil</option>
                    <option value="Motor">3. Motor</option>
                    <option value="Lain-lain">4. Lain-lain</option>
                  </select>
                </div>    
                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Surat Pengantar Rujukan </label>
                  <select id="inputState" name="surat_pengantar" class="form-select">
                    <option value="<?php echo $igd['surat_pengantar']?>" hidden><?php echo $igd['surat_pengantar']?></option>
                    <option value="Ada">Ada</option>
                    <option value="Tidak Ada">Tidak Ada</option>
                  </select>
                </div>  
                <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Kondisi Pasien Tiba </label>
                  <select id="inputState" name="kondisi_tiba" class="form-select">
                    <option value="<?php echo $igd['kondisi_tiba']?>" hidden><?php echo $igd['kondisi_tiba']?></option>
                    <!-- <option value="1">Resusitasi</option> -->
                    <option value="2">Emergency</option>
                    <option value="3">Urgent</option>
                    <!-- <option value="4">Less Urgent</option> -->
                    <option value="5">Non Urgent</option>
                    <option value="6">Death on Arrival</option>
                  </select>
                </div>  

              <div style="margin-bottom:2px; margin-top:30px">
              <hr>
              <h6 class="card-title">Identitas Pengantar Pasien</h6>
              </div>

              <!-- Multi Columns Form -->
              <div class="row">
               <div class="col-md-6">
                  <label for="inputName5" class="form-label">Nama Pengantar</label>
                  <input readonly  type="text" class="form-control" name="nama_pengantar" id="inputName5" value="<?php echo $igd['nama_pengantar']?>" placeholder="Masukkan Nama Pengantar">
                </div>
                <div class="col-md-6">
                  <label for="inputName5" class="form-label">Nomor Telepon Seluler Penanggung Jawab</label>
                  <input readonly  type="text" class="form-control" id="inputName5" value="<?php echo $igd['notelp_pengantar']?>" name="notelp_pengantar" placeholder="Masukkan No Telepon">
                </div>
             

              <!-- Multi Columns Form -->
              <div class="row">
                
                <div class="col-md-12" style="margin-top:5px;">
                  <label for="inputName5" class="form-label">Keluhan </label>
                 <input readonly  type="text" class="form-control" name="keluhan" value="<?php echo $igd['keluhan']?>">
                </div>  
                <div class="col-md-12" style="margin-top:5px;">
                  <label for="inputName5" class="form-label">Riwayat Penyakit Sebelumnya </label>
                 <input readonly  type="text" class="form-control" name="riw_penyakit" value="<?php echo $igd['riw_penyakit']?>">
                </div>  
                <div class="col-md-12" style="margin-top:5px;">
                  <label for="inputName5" class="form-label">Riw Alergi </label>
                 <input readonly  type="text" class="form-control" name="riw_alergi" value="<?php echo $igd['riw_alergi']?>">
                </div>  

                <div>
                      <h5 class="card-title">Tanda-Tanda Vital</h5>
                    </div>
    
                    <div class="col-md-4">
                    <label for="inputCity" class="form-label">E</label>
                    <div class="input-group mb-10">
                          <input readonly  type="text" class="form-control" placeholder="E" name="e" aria-describedby="basic-addon2" value="<?php echo $igd['e']?>">
                          <!-- <span class="input-group-text" id="basic-addon2">celcius</span> -->
                    </div>
                    </div>
    
                    <div class="col-md-4">
                    <label for="inputCity" class="form-label">V</label>
                    <div class="input-group mb-10">
                          <input readonly  type="text" class="form-control" placeholder="V" name="v" aria-describedby="basic-addon2" value="<?php echo $igd['v']?>">
                          <!-- <span class="input-group-text" id="basic-addon2">%</span> -->
                    </div>
                    </div>
    
                    <div class="col-md-4">
    
                    <label for="inputCity" class="form-label">M</label>
                     <div class="input-group mb-10">
                          <input readonly  type="text" class="form-control" placeholder="M" name="m" aria-describedby="basic-addon2" value="<?php echo $igd['m']?>">
                          <!-- <span class="input-group-text" id="basic-addon2">mmHg</span> -->
                    </div>
                    </div>
    
                    <div class="col-md-6" >
                    <label for="inputCity" class="form-label" style="margin-top:10px;">Td</label>
                     <div class="input-group mb-10">
                          <input readonly  type="text" class="form-control" placeholder="Tekanan Darah" name="td" aria-describedby="basic-addon2" value="<?php echo $igd['td']?>">
                          <span class="input-group-text" id="basic-addon2">mmHg</span>
                    </div>
                    </div>
                    <div class="col-md-6">
                   <label for="inputCity" class="form-label" style="margin-top:10px;">Rr</label>
                   <div class="input-group mb-10">
                         <input readonly  type="text" class="form-control" placeholder="Rr" name="rr" aria-describedby="basic-addon2" value="<?php echo $igd['rr']?>">
                         <span class="input-group-text" id="basic-addon2">kali/menit</span>
                   </div>
                   </div>
                   
                    <div class="col-md-6">
                    <label for="inputCity" class="form-label" style="margin-top:10px;">Nadi</label>
                    <div class="input-group mb-10">
                          <input readonly  type="text" class="form-control" placeholder="Denyut Nadi" name="n" aria-describedby="basic-addon2" value="<?php echo $igd['n']?>">
                          <span class="input-group-text" id="basic-addon2">kali/menit</span>
                    </div>
                    </div>
                    
                    <div class="col-md-6">
                    <label for="inputCity" class="form-label" style="margin-top:10px;">Suhu Tubuh</label>
                    <div class="input-group mb-10">
                          <input readonly  type="text" class="form-control" placeholder="Suhu Tubuh" name="s" aria-describedby="basic-addon2" value="<?php echo $igd['s']?>">
                          <span class="input-group-text" id="basic-addon2">celcius</span>
                    </div>
                    </div>
                    <div class="col-md-12">
                    <label for="inputCity" class="form-label" style="margin-top:10px;">GDA</label>
                    <div class="input-group mb-10">
                          <input readonly  type="text" class="form-control" placeholder="GDA" name="gda" aria-describedby="basic-addon2" value="<?php echo $igd['gda']?>">
                          <!-- <span class="input-group-text" id="basic-addon2">celcius</span> -->
                    </div>
                    </div>
                    <div class="col-md-6">
                    <label for="inputCity" class="form-label" style="margin-top:10px;">BB</label>
                    <div class="input-group mb-10">
                          <input readonly  type="text" class="form-control" placeholder="BB" name="bb" aria-describedby="basic-addon2" value="<?php echo $igd['bb']?>">
                          <span class="input-group-text" id="basic-addon2">Kg</span>
                    </div>
                    </div>
                    <div class="col-md-6">
                    <label for="inputCity" class="form-label" style="margin-top:10px;">TB</label>
                    <div class="input-group mb-10">
                          <input readonly  type="text" class="form-control" placeholder="TB" name="tb" aria-describedby="basic-addon2" value="<?php echo $igd['tb']?>">
                          <span class="input-group-text" id="basic-addon2">Cm</span>
                    </div>
                    </div>
                    <div class="col-md-12" style="margin-top:5px;">
                  <label for="inputName5" class="form-label" style="margin-top:10px;">Asesmen Nyeri </label>
                  <select id="pilihan" name="asesmen_nyeri" class="form-select">
                    <option value="<?php echo $igd['asesmen_nyeri']?>"><?php echo $igd['asesmen_nyeri']?></option>
                    <option value="Ada">Ada</option>
                    <option value="Tidak Ada">Tidak Ada</option>
                  </select>
                </div>  

                <div class="hidden" id="nyr">
                  <div class="row">
                    <div class="col-md-12 mt-3">
                      <label for="" class="form-label">Skala Nyeri (dari 1 -10)</label>
                      <input readonly  type="number" class="form-control" value="<?php echo $igd['skala_nyeri']?>" name="skala_nyeri" id="" max="10">
                    </div>
                    <!-- <div class="col-md-6" style="margin-top:20px;">
                      <label for="inputName5" class="form-label">Lokasi Nyeri</label>
                      <input readonly  type="text" class="form-control" id="inputName5" value="" name="lokasi_nyeri" placeholder="Lokasi Nyeri">
                    </div>
                     <div class="col-md-6" style="margin-top:20px;">
                      <label for="inputName5" class="form-label">Penyebab Nyeri</label>
                      <input readonly  type="text" class="form-control" id="inputName5" name="penyebab_nyeri" value="" placeholder="Penyebab Nyeri">
                    </div>
                     <div class="col-md-6" style="margin-top:20px;">
                      <label for="inputName5" class="form-label">Durasi Nyeri</label>
                      <input readonly  type="text" class="form-control" id="inputName5" name="durasi_nyeri" value="" placeholder="Durasi Nyeri">
                    </div>
                    <div class="col-md-6" style="margin-top:20px;">
                      <label for="inputName5" class="form-label">Frekuensi Nyeri</label>
                      <input readonly  type="number" class="form-control" id="inputName5" name="frekuensi_nyeri" value="" placeholder="Frekuensi Nyeri">
                    </div> -->
                  </div>
                </div>
                <script>
                  document.getElementById('pilihan').addEventListener('change', function() {
                    var formLain = document.getElementById('nyr');
                    if (this.value === 'Ada') {
                      formLain.classList.remove('hidden');
                    } else {
                      formLain.classList.add('hidden');
                    }
                  });
                </script>

                <!-- <div class="col-md-12 mt-2">
                    <label for="">Resiko Jatuh</label>
                    <input readonly  type="number" class="form-control" id="inputName5" name="resiko_jatuh" value="" placeholder="Kajian resiko Jatuh">
                </div>
                <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Gambar anatomi tubuh</label>
                  <input readonly  type="file" class="form-control" id="inputName5" name="gambar_anatomi" value="" placeholder="Frekuensi Nyeri">
                </div>
                <div class="col-md-6" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Risiko Luka Decubitus</label>
                  <select id="inputState" name="resiko_decubitus" class="form-select">
                    <option hidden>Pilih</option>
                    <option value="Ada">Ya</option>
                    <option value="Tidak">Tidak</option>
                  </select>
                </div>
                <div class="col-md-6" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Penurunan BB dalam waktu 6 bulan terakhir</label>
                  <select id="inputState" name="penurunan_bb" class="form-select">
                    <option hidden>Pilih</option>
                    <option value="Ada">Ya</option>
                    <option value="Tidak">Tidak</option>
                  </select>
                </div>
                <div class="col-md-6" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Penurunan asupan makanan</label>
                  <select id="inputState" name="penurunan_asupan" class="form-select">
                    <option hidden>Pilih</option>
                    <option value="Ada">Ya</option>
                    <option value="Tidak">Tidak</option>
                  </select>
                </div>
                <div class="col-md-6" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Gejala gastrointestinal (mual, muntah, diare, anorexia)</label>
                  <select id="inputState" name="gejala_gastro" class="form-select">
                    <option hidden>Pilih</option>
                    <option value="Ada">Ya</option>
                    <option value="Tidak">Tidak</option>
                  </select>
                </div>
                <div class="col-md-6" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Faktor pemberat (komorbid)</label>
                  <select id="inputState" name="faktor_pemberat" class="form-select">
                    <option hidden>Pilih</option>
                    <option value="Ada">Ya</option>
                    <option value="Tidak">Tidak</option>
                  </select>
                </div>
                <div class="col-md-6" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Penurunan kapasitas fungsional</label>
                  <select id="inputState" name="penurunan_fungsional" class="form-select">
                    <option hidden>Pilih</option>
                    <option value="Ada">Ya</option>
                    <option value="Tidak">Tidak</option>
                  </select>
                </div> -->

                <div class="col-md-12">
                <div>
                      <h5 class="card-title">Skrining Status Gizi</h5>
                    </div>
                      <p style="color:blue">Ya = 1 , Tidak = 0</p>
                      <div class="row">
                        <div class="col-md-4">
                            <p>1. Apakah pasien terlihat kurus ?</p>
                            <input readonly  type="number" oninput="hitungGizi()" name="resiko_decubitus" id="no1" class="form-control" value="<?php echo $igd['resiko_decubitus']?>">
                          </div>
                        <div class="col-md-4">
                          <p>2. Apakah pakaian anda terasa longgar ?</p>
                          <input readonly  type="number" oninput="hitungGizi()" name="penurunan_bb" id="no2" class="form-control" value="<?php echo $igd['penurunan_bb']?>">
                        </div>
                        <div class="col-md-4">
                          <p>3. Apakah akhir-akhir ini anda kehilangan berat badan yang tidak sengaja ?</p>
                          <input readonly  type="number" oninput="hitungGizi()" name="penurunan_asupan" id="no3" class="form-control" value="<?php echo $igd['penurunan_asupan']?>">
                        </div>
                        <div class="col-md-4">
                          <p>4. Apakah anda mengalami penurunan berat badan selama 1 minggu terakhir ?</p>
                          <input readonly  type="number" oninput="hitungGizi()" name="gejala_gastro" id="no4" class="form-control" value="<?php echo $igd['gejala_gastro']?>">
                        </div>
                        <div class="col-md-4">
                          <p>5. Apakah anda menderita suatu penyakit yang menyebabkan adanya perubahan jumlah jenis makanan yang anda makan ?</p>
                          <input readonly  type="number" oninput="hitungGizi()" name="faktor_pemberat" id="no5" class="form-control" value="<?php echo $igd['faktor_pemberat']?>">
                        </div>
                        <div class="col-md-4">
                          <p>6. Apakah anda merasa lemah, loyo dan tidak bertenaga ?</p>
                          <input readonly  type="number" oninput="hitungGizi()" name="penurunan_fungsional" id="no6" class="form-control" value="<?php echo $igd['penurunan_fungsional']?>">
                        </div>
                      </div>
                      <h5 class="mb-0"><b>Jumlah : <span id="totalGizi"></span> (<span id="interpretasiGizi"></span>)</b></h5>
                      
                    </div>

                    <script>
                      // function hitungIMT(){
                      //   var tinggi_b =document.getElementById("tb").value;
                      //   var berat_b =document.getElementById("bb").value;
                      //   var imtt = berat_b/(tinggi_b*tinggi_b);
                      //   document.getElementById("imt").value = imtt.toFixed(2);
                      // }
                      function hitungGizi() {
                        var n1 = document.getElementById("no1").value;
                        var n2 = document.getElementById("no2").value;
                        var n3 = document.getElementById("no3").value;
                        var n4 = document.getElementById("no4").value;
                        var n5 = document.getElementById("no5").value;
                        var n6 = document.getElementById("no6").value;
                        var ttlGizi = Number(n1)+Number(n2)+Number(n3)+Number(n4)+Number(n5)+Number(n6);
                        if(ttlGizi <= 2){
                          document.getElementById('interpretasiGizi').innerHTML = 'Tidak beresiko Malnutrisi';
                        }else{
                          document.getElementById('interpretasiGizi').innerHTML = 'Beresiko Malnutrisi';
                        }
                        document.getElementById("totalGizi").innerHTML = ttlGizi;
                      }
                    </script>


                <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Pengkajian Psikologis </label>
                 <select name="psiko" id="" class="form-control">
                  <option value="<?= $igd['psiko'] ?>"><?= $igd['psiko'] ?></option>
                  <option value="Tenang">Tenang</option>
                  <option value="Cemas">Cemas</option>
                  <option value="Sedih">Sedih</option>
                  <option value="Takut thd lingkungan">Takut thd lingkungan</option>
                  <option value="Marah">Marah</option>
                 </select>
                </div>  

                <div class="col-md-12" style="margin-top:5px;">
                  <label for="inputName5" class="form-label">Pengkajian Sosial </label>
                 <select name="sosial" id="" class="form-control">
                  <option value="<?= $igd['sosial'] ?>"><?= $igd['sosial'] ?></option>
                  <option value="Sendiri">Sendiri</option>
                  <option value="Kontrak">Kontrak</option>
                  <option value="Rumah Ortu">Rumah Ortu</option>
                  <option value="Lainnya">Lainnya</option>
                 </select>
                </div>  

                <div class="col-md-12" style="margin-top:5px;">
                  <label for="inputName5" class="form-label">Bantuan Yang Dibutuhkan Pasien Dirumah </label>
                 <select name="bantuan" id="" class="form-control">
                  <option value="<?php echo $igd['bantuan']?>"><?php echo $igd['bantuan']?></option>
                  <option value="Makan/Minum">Makan/Minum</option>
                  <option value="BAB">BAB</option>
                  <option value="BAK">BAK</option>
                  <option value="Perawatan Luka">Perawatan Luka</option>
                  <option value="Pemberian Obat">Pemberian Obat</option>
                  <option value="Mobilisasi">Mobilisasi</option>
                  <option value="Lainnya">Lainnya</option>
                 </select>
                </div>  

                <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Tindak Lanjut</label>
                  <select id="pilRujuk"  name="tindak" class="form-select">
                    <option value="<?php echo $igd['tindak']?>" hidden><?php echo $igd['tindak']?></option>
                    <option value="Pulang">1.Pulang</option>
                    <option value="Pulang Paksa">2. Pulang Paksa</option>
                    <option value="Rawat">3.Rawat</option>
                    <option value="Meninggal">4.Meninggal</option>
                    <option value="Rujuk">5.Rujuk</option>
                  </select>
                </div>
                <div class="hidden mt-2" id="rjk">
                  <div class="row">
                    <div class="col-md-12">
                      <input type="text" placeholder="Tujuan Rujukan" class="form-control" name="tindak_rujuk" value="<?php echo $igd['tindak_rujuk']?>">
                    </div>
                  </div>
                </div>

                <div class="col-md-12" style="margin-top:5px;">
                  <label for="inputName5" class="form-label">Perawat Yang Mengkaji </label>
                <input readonly  type="text" name="perawat" id="" class="form-control" value="<?php echo $igd['perawat']?>">
                </div>  

            
              
              <div style="margin-bottom:2px; margin-top:30px">
              <hr>
              <h5 class="card-title">Asesmen Medis</h5>
              </div>
              
                <div class="col-md-4" style="margin-top:5px; margin-bottom:10px">
                  <label for="inputName5" class="form-label">Tanggal dan Jam </label>
                <input readonly  type="datetime-local" name="tgl" id="" class="form-control" value="<?php echo $igd['tgl']?>">
                </div> 

                <div class="col-md-12" style="margin-top:5px;">
                  <label for="inputName5" class="form-label">Subjektif</label>
                  <textarea name="sub" id="" style="width:100%; height: 150px" class="form-control" value="<?php echo $igd['sub']?>"><?php echo $igd['sub']?></textarea>
                </div>  
                <div class="col-md-12" style="margin-top:5px;">
                  <label for="inputName5" class="form-label">Objektif</label>
                  <textarea name="ob" id="" style="width:100%; height: 150px" class="form-control" value="<?php echo $igd['ob']?>"><?php echo $igd['ob']?></textarea>
                </div>  

                <div class="col-md-12" style="margin-top:5px;">
                  <label for="inputName5" class="form-label">Pemeriksaan Penunjang </label>
                <input readonly  type="text" name="penunjang" id="" value="<?php echo $igd['penunjang']?>" class="form-control">
                </div>  

                <div class="col-md-12" style="margin-top:5px;">
                  <label for="inputName5" class="form-label">Diagnosa Kerja </label>
                <input readonly  type="text" name="dkerja" id="" class="form-control" value="<?php echo $igd['dkerja']?>">
                </div>  
                <div class="col-md-12" style="margin-top:5px;">
                  <label for="inputName5" class="form-label">Diagnosa Banding </label>
                <input readonly  type="text" name="dbanding" id="" class="form-control" value="<?php echo $igd['dbanding']?>">
                </div>  
                <div class="col-md-12" style="margin-top:5px;">
                  <label for="inputName5" class="form-label">Planning </label>
                <input readonly  type="text" name="dbanding" id="" class="form-control" value="<?php echo $igd['dbanding']?>">
                </div>  

                <div class="col-md-12" style="margin-top:5px;">
                  <label for="inputName5" class="form-label">Dokter Jaga </label>
                <input readonly  type="text" name="dokter" id="" class="form-control" value="<?php echo $igd['dokter']?>">
                </div>  
                
                 <!-- <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Rencana Pemulangan Pasien</label>
                  <select id="inputState" name="rencana_pemulangan" class="form-select">
                    <option value="">Pilih</option>
                    <option value="Pasien lansia">1.Pasien lansia</option>
                    <option value="Gangguan anggota gerak">2. Gangguan anggota gerak</option>
                    <option value="Pasien dengan perawatan berkelanjutan atau panjang">3.Pasien dengan perawatan berkelanjutan atau panjang</option>
                    <option value="Memerlukan bantuan dalam aktivitas sehari-hari">4.Memerlukan bantuan dalam aktivitas sehari-hari</option>
                    <option value="Tidak masuk kriteria">5.Tidak masuk kriteria</option>
                  </select>
                </div> -->
                 <!-- <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Rencana Rawat</label>
                  <input readonly  type="text" class="form-control" id="inputName5" name="rencana_rawat" value="" placeholder="Rencana Perawatan">
                </div>
                 <div class="col-md-12" style="margin-top:20px;">
                  <label for="inputName5" class="form-label">Instruksi Medik dan Keperawatan</label>
                  <input readonly  type="text" class="form-control" id="inputName5" name="intruksi_medik" value="" placeholder="Intruksi Medis">
                </div> -->


               <div class="hidden mt-2" id="kamar">
                  <div class="row">
                    <div class="col-md-12">
                      <label for="inputState" class="form-label">Kamar / Ruangan</label>
                      <!-- <input readonly  type="text" class="form-control" value="" name="kamar"> -->
                    <select name="kamar" class="form-select" id="">
                        <option hidden>Pilih Kamar</option>
                        <?php
                          $getKamar = $koneksi->query("SELECT * FROM kamar LIMIT 10");
                          foreach($getKamar as $kamar){
                        ?>
                          <?php $cekKamar = $koneksi->query("SELECT COUNT(*) as jumlah FROM registrasi_rawat WHERE kamar = '$kamar[namakamar]' and status_antri != 'Pulang'")->fetch_assoc();?>
                          <?php if($cekKamar['jumlah'] == 0){?>
                            <option value="<?= $kamar['namakamar']?>"><?= $kamar['namakamar']?></option>
                          <?php }else{?>
                          <?php }?>
                        <?php }?>
                      </select>
                    </div>
                  </div>
                </div> 
               

                <script>
                  document.getElementById('pilRujuk').addEventListener('change', function() {
                    var formLain = document.getElementById('rjk');
                    var formLain2 = document.getElementById('kamar');
                    if (this.value === 'Rujuk') {
                      formLain.classList.remove('hidden');
                    }else if (this.value === 'Rawat') {
                      formLain2.classList.remove('hidden');
                    }else if (this.value != 'Rujuk'){
                      formLain.classList.add('hidden');
                    }else if (this.value != 'Rawat'){
                      formLain2.classList.add('hidden');
                    }
                  });
                </script>


                <!-- <div class="text-center" style="margin-top: 50px; margin-bottom: 80px;">
                  <button type="submit" name="save" class="btn btn-primary">Simpan</button>
                </div> -->
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

</html>


<?php  
if (isset ($_POST['save'])) {

    $no_rm=$_POST['no_rm'];
    $nama_pasien=$_POST['nama_pasien'];
    $tgl_masuk=$_POST['tgl_masuk'];
    $jam_masuk=$_POST['jam_masuk'];
    $transportasi=$_POST['transportasi'];
    $surat_pengantar=$_POST['surat_pengantar'];
    $kondisi_tiba=$_POST['kondisi_tiba'];
    $nama_pengantar=$_POST['nama_pengantar'];
    $notelp_pengantar=$_POST['notelp_pengantar'];
    $asesmen_nyeri=$_POST['asesmen_nyeri'];
    // $skala_nyeri=$_POST['skala_nyeri'];
    // $lokasi_nyeri=$_POST['lokasi_nyeri'];
    // $penyebab_nyeri=$_POST['penyebab_nyeri'];
    // $durasi_nyeri=$_POST['durasi_nyeri'];
    // $frekuensi_nyeri=$_POST['frekuensi_nyeri'];
    // $kajian_jatuh=$_POST['kajian_jatuh'];
    // $gambar_anatomi=$_POST['gambar_anatomi'];
    // $tingkat_kesadaran=$_POST['tingkat_kesadaran'];
    $resiko_decubitus=$_POST['resiko_decubitus'];
    $penurunan_bb=$_POST['penurunan_bb'];
    $penurunan_asupan=$_POST['penurunan_asupan'];
    $gejala_gastro=$_POST['gejala_gastro'];
    $faktor_pemberat=$_POST['faktor_pemberat'];
    $penurunan_fungsional=$_POST['penurunan_fungsional'];
    // $rencana_pemulangan=$_POST['rencana_pemulangan'];
    // $rencana_rawat=$_POST['rencana_rawat'];
    // $intruksi_medik=$_POST['intruksi_medik'];
    // $idrm=$_POST['idrm'];

  //   $gambar_anatomi=$_FILES['gambar_anatomi']['name'];

  //   $lokasi=$_FILES['gambar_anatomi']['tmp_name'];

  //   move_uploaded_file($lokasi, '../igd/foto/'.$gambar_anatomi);

   
  //  if (!empty($lokasi))

  //  {

  // move_uploaded_file($lokasi, '../igd/foto/'.$gambar_anatomi);

   $koneksi->query("UPDATE igd SET no_rm='$no_rm', nama_pasien='$nama_pasien', tgl_masuk='$tgl_masuk', jam_masuk='$jam_masuk', transportasi='$transportasi', surat_pengantar='$surat_pengantar', kondisi_tiba='$kondisi_tiba', nama_pengantar='$nama_pengantar', notelp_pengantar='$notelp_pengantar', asesmen_nyeri='$asesmen_nyeri', resiko_decubitus='$resiko_decubitus',penurunan_bb='$penurunan_bb',penurunan_asupan='$penurunan_asupan',gejala_gastro='$gejala_gastro',faktor_pemberat='$faktor_pemberat',penurunan_fungsional='$penurunan_fungsional',psiko='$_POST[psiko]',sosial='$_POST[sosial]',bantuan='$_POST[bantuan]',penunjang='$_POST[penunjang]',dkerja='$_POST[dkerja]',dbanding='$_POST[dbanding]',tindak='$_POST[tindak]',skala_nyeri='$_POST[skala_nyeri]',tindak_rujuk='$_POST[tindak_rujuk]',keluhan='$_POST[keluhan]',riw_penyakit='$_POST[riw_penyakit]',riw_alergi='$_POST[riw_alergi]',perawat='$_POST[perawat]', e='$_POST[e]', v='$_POST[v]', m='$_POST[m]', td='$_POST[td]', rr='$_POST[rr]', n='$_POST[n]', s='$_POST[s]', gda='$_POST[gda]', bb='$_POST[bb]', tb='$_POST[tb]', tgl='$_POST[tgl]', sub='$_POST[sub]', ob='$_POST[ob]', dokter='$_POST[dokter]' WHERE idigd='$_GET[id]' ");

   $countigd = $koneksi->query("SELECT *, COUNT(perawat) as jumlah FROM igd WHERE idigd = '$_GET[id]' LIMIT 1")->fetch_assoc();

  if($countigd["jumlah"] == 0){
   if($_POST['tindak'] == "Rawat"){
   $koneksi->query("INSERT INTO registrasi_rawat (nama_pasien, dokter_rawat, perawatan, kamar, jenis_kunjungan, id_pasien, no_rm, jadwal, antrian, status_antri, carabayar, shift) VALUES ('$nama_pasien', '$_POST[dokter_rawat]', 'Rawat Inap', '$_POST[kamar]', 'Kunjungan Sakit', '', '$no_rm', '$tgl_masuk', '', 'Belum Datang', '$_POST[carabayar]', '$shift')");
   }
  }


  echo "
  <script>
  alert('Data berhasil ditambah');
  document.location.href='index.php?halaman=daftarregistrasiinap';
  </script>

  ";


  }

  // else {

  //    $koneksi->query("UPDATE igd SET no_rm='$no_rm', nama_pasien='$nama_pasien', tgl_masuk='$tgl_masuk', jam_masuk='$jam_masuk', transportasi='$transportasi', surat_pengantar='$surat_pengantar', kondisi_tiba='$kondisi_tiba', nama_pengantar='$nama_pengantar', notelp_pengantar='$notelp_pengantar', asesmen_nyeri='$asesmen_nyeri', lokasi_nyeri='$lokasi_nyeri', penyebab_nyeri='$penyebab_nyeri', durasi_nyeri='$durasi_nyeri', frekuensi_nyeri='$frekuensi_nyeri', resiko_decubitus='$resiko_decubitus',penurunan_bb='$penurunan_bb',penurunan_asupan='$penurunan_asupan',gejala_gastro='$gejala_gastro',faktor_pemberat='$faktor_pemberat',penurunan_fungsional='$penurunan_fungsional',rencana_pemulangan='$rencana_pemulangan',psiko='$psiko',sosial='$sosial',bantuan='$bantuan',penunjang='$penunjang',dkerja='$dkerja',dbanding='$dbanding',tindak='$tindak',skala_nyeri='$skala_nyeri',tindak_rujuk='$tindak_rujuk',keluhan='$keluhan',riw_penyakit='$riw_penyakit',riw_alergi='$riw_alergi',perawat='$_SESSION[admin][nama_lengkap]', e='$_POST[e]', v='$_POST[v]', m='$_POST[m]', td='$_POST[td]', rr='$_POST[rr]', n='$_POST[n]', s='$_POST[s]', gda='$_POST[gda]', bb='$_POST[bb]', tb='$_POST[tb]' WHERE idigd='$_GET[id]' ");

  //    if($_POST['tindak'] == "Rawat"){
  //     $koneksi->query("INSERT INTO registrasi_rawat (nama_pasien, dokter_rawat, perawatan, kamar, jenis_kunjungan, id_pasien, no_rm, jadwal, antrian, status_antri, carabayar, shift) VALUES ('$nama_pasien', '$_POST[dokter_rawat]', 'Rawat Inap', '$_POST[kamar]', 'Kunjungan Sakit', '', '$no_rm', '$tgl_masuk', '', 'Belum Datang', '$_POST[carabayar]', '$shift')");
  //     }


  //   }

   


  // if (mysqli_affected_rows($koneksi)>0) {
 

// } else {

//   echo "
//   <script>
//   alert('GAGAL MENGHAPUS DATA');
//   document.location.href='index.php?halaman=daftarigd;
//   </script>

//   ";

// }




//   // $koneksi->query("INSERT INTO log_user 

//   //   (status_log, username_admin, idadmin)

//   //   VALUES ('$status_log', '$username_admin', '$idadmin')

//   //   ");

// }

?>