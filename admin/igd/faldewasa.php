<?php 
    $username=$_SESSION['admin']['username'];
    $ambil=$koneksi->query("SELECT * FROM admin  WHERE username='$username';");
    $pasien=$koneksi->query("SELECT * FROM igd WHERE idigd='$_GET[id]'");
    $pecah=$pasien->fetch_assoc();    
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            .hidden{
                display: hidden;
                height: 0.5px;
                overflow: hidden;
            }
        </style>
    </head>
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
          </div>
          <!-- End Page Title -->
          <?php if(!isset($_GET['idfr'])){?>
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
                                 <input type="text" class="form-control" name="pasien" id="inputName5" readonly value="<?php echo $pecah['nama_pasien']?>" placeholder="Masukkan Nama Pasien">
                              </div>
                              <div class="col-md-12">
                                 <label for="inputName5" style="margin-top: 10px;" class="form-label">NO RM Pasien</label>
                                 <input type="text" class="form-control" name="no_rm" id="inputName5" readonly value="<?php echo $pecah['no_rm']?>" placeholder="Masukkan No RM Pasien">
                              </div>
                              <!-- <input type="hidden" class="form-control" id="inputName5" name="id_rm" value="<?php echo $pecah['id_rm']?>"> -->
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
                                 <div class="col-md-12 mb-2">
                                    <label for="inputName5" class="form-label">Status Asesmen</label>
                                    <select class="form-control" name="status_asesmen" id="status_asesmen">
                                      <option value="Asesmen Awal">Asesmen Awal</option>
                                      <option value="Asesmen Ulang">Asesmen Ulang</option>
                                    </select>
                                 </div>
                                 <div class="hidden" id="as">
                                  <dvi class="row">
                                      <div class="col-md-3">
                                          <input type="checkbox" name="PK" value="1">Perubahan Kondisi (PK)</input>
                                      </div>
                                      <div class="col-md-3">
                                          <input type="checkbox" name="J" value="1">Setelah Jatuh (J)</input>
                                      </div>
                                      <div class="col-md-3">
                                          <input type="checkbox" name="T" value="1">Transfer Pasien (T)</input>
                                      </div>
                                      <div class="col-md-3">
                                          <input type="checkbox" name="DLL" value="1">Kebutuhan Khusun (DLL)</input>
                                      </div>
                                  </dvi>
                                 </div>
                                 <script>
                                     document.getElementById('status_asesmen').addEventListener('change', function() {
                                       var formLain = document.getElementById('as');
                                       if (this.value === 'Asesmen Ulang') {
                                         formLain.classList.remove('hidden');
                                       } else {
                                         formLain.classList.add('hidden');
                                       }
                                     });
                                   </script>
                                 <div class="col-md-12 mb-2">
                                    <label for="inputName5" class="form-label">Tanggal</label>
                                    <input type="datetime" class="form-control" name="tgl_masuk"  id="inputName5" placeholder="Sesuai dengan ICD 9 CM dan standar lainnya" value="<?= date("Y-m-d H:i:s") ?>">
                                 </div>
                                 <div class="col-md-6 mb-2">
                                  <label for="" class="form-label">Riwayat jatuh baru/3 bln terakhir</label>
                                  <select onchange="updateTotal()" name="riwayat_jatuh" class="form-control">
                                      <option hidden>Pilih</option>
                                      <option value="25">Ya</option>
                                      <option value="0">Tidak</option>
                                  </select>
                                 </div>
                                 <div class="col-md-6 mb-2">
                                  <label for="" class="form-label">Diagnosis medissekunder > 1</label>
                                  <select onchange="updateTotal()" name="medis_sekunder" class="form-control">
                                      <option value="0" hidden>Pilih</option>
                                      <option value="15">Ya</option>
                                      <option value="0">Tidak</option>
                                  </select>
                                 </div>
                                 <div class="col-md-6 mb-2">
                                  <label for="" class="form-label">Alat bantu Jalan</label>
                                  <select onchange="updateTotal()" name="alat_bantu" class="form-control">
                                      <option value="0" hidden>Pilih</option>
                                      <option value="15">Tongkat/Alat Bantu Jalan</option>
                                      <option value="30">Berpegang Pada Furniture</option>
                                      <option value="0">Tidak</option>
                                  </select>
                                 </div>
                                 <div class="col-md-6 mb-2">
                                  <label for="" class="form-label">Terpasang infus/terapi antikoagulasi</label>
                                  <select onchange="updateTotal()" name="terpasang" class="form-control">
                                      <option value="0" hidden>Pilih</option>
                                      <option value="25">Ya</option>
                                      <option value="0">Tidak</option>
                                  </select>
                                 </div>
                                 <div class="col-md-6 mb-2">
                                  <label for="" class="form-label">Cara Berjalan Berpindah</label>
                                  <select onchange="updateTotal()" name="cara_jalan" class="form-control">
                                      <option value="0" hidden>Pilih</option>
                                      <option value="15">Lemah</option>
                                      <option value="30">Terganggu</option>
                                      <option value="0">Normal</option>
                                  </select>
                                 </div>
                                 <div class="col-md-6 mb-2">
                                  <label for="" class="form-label">Status Mental</label>
                                  <select onchange="updateTotal()" name="status_mental" class="form-control">
                                      <option value="0" hidden>Pilih</option>
                                      <option value="15">Lupa Keterbatasan Diri</option>
                                      <option value="0">Tidak</option>
                                  </select>
                                 </div>
                                 <div class="col-md-6 mb-2">
                                      <input type="number" id="total" name="total" class="form-control" readonly>
                                  </div>
                                  <div class="col-md-6 mb-2">
                                     <input type="text" name="status" id="status" class="form-control" readonly>
                                     <input type="text" name="perawat" value="<?= $_SESSION['admin']['namalengkap']?>" class="form-control" hidden>
                                 </div>
                                 <script>
                                     function updateTotal() {
                                       // Ambil nilai dari setiap elemen select
                                      //  var riwayat_jatuh = parseInt(document.getElementsByName("riwayat_jatuh")[0].value);
                                       var riwayat_jatuh = parseInt(document.getElementsByName("riwayat_jatuh")[0].value);
                                       var medis_sekunder = parseInt(document.getElementsByName("medis_sekunder")[0].value);
                                       var alat_bantu = parseInt(document.getElementsByName("alat_bantu")[0].value);
                                       var terpasang = parseInt(document.getElementsByName("terpasang")[0].value);
                                       var cara_jalan = parseInt(document.getElementsByName("cara_jalan")[0].value);
                                       var status_mental = parseInt(document.getElementsByName("status_mental")[0].value);
                                       // Tambahkan elemen select lainnya di sini
                                 
                                       // Hitung total nilai
                                       var total = riwayat_jatuh + medis_sekunder + alat_bantu + terpasang + cara_jalan + status_mental;
                                      //  var total = riwayat_jatuh ;
                                       // Tambahkan nilai elemen select lainnya di sini
                                       if(total <= 24){
                                          var stts = 'Tidak Resiko';
                                       }
                                       
                                       if(total >= 25 && total <= 50){
                                          var stts = 'Resiko Rendah';
                                       }
      
                                       if(total >= 51){
                                          var stts = 'Resiko Tinggi';
                                       }
                                 
                                       // Tampilkan total nilai dalam elemen dengan ID 'total'
                                       document.getElementById('total').value = total;
                                       document.getElementById('status').value = stts;
                                     }
                                 </script>
                                 <br>
                                 <br>
                                 <label for="" class="form-label">Obat-obatan yan Dikonsumsi</label>
                                 <div class="col-md-4 mb-2">
                                  <input type="checkbox" name="obat[]" value="Psikotropika">Psikotropika</input> <br>
                                  <input type="checkbox" name="obat[]" value="Opioid/Narkotik">Opioid/Narkotik</input> <br>
                                  <input type="checkbox" name="obat[]" value="Anti- ansietas/CPZ">Anti- ansietas/CPZ</input> <br>
                                 </div>
                                 <div class="col-md-4 mb-2">
                                  <input type="checkbox" name="obat[]" value="Diuretik">Diuretik</input> <br>
                                  <input type="checkbox" name="obat[]" value="Hipnotik/sedative">Hipnotik/sedative</input> <br>
                                  <input type="checkbox" name="obat[]" value="Laksatif">Laksatif</input> <br>
                                 </div>
                                 <div class="col-md-4 mb-2">
                                  <input type="checkbox" name="obat[]" value="Anti hipertensi">Anti hipertensi</input> <br>
                                  <input type="checkbox" name="obat[]" value="Kardiovaskular">Kardiovaskular</input> <br>
                                  <input type="checkbox" name="obat[]" value="Anti parkinson">Anti parkinson</input> <br>
                                 </div>
                                 <br>
                                 <br>
                                 <center>
                                     <h6 class="card-title">INTERVENSI RESIKO JATUH (dilakukan pada semua pasien saat setelah asesmen awal)</h6>
                                 </center>
                                 <table class="table table-bordered">
                                  <thead>
                                      <tr>
                                          <th>NO</th>
                                          <th>INTERVENSI RESIKO JATUH STANDAR</th>
                                          <th>CHECKLIST</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                  <tr>
                                      <td>1</td>
                                      <td>
                                          Orientasikan ruangan pada pasien dan keluarga.
                                      </td>
                                      <td><input type="checkbox" value="1" name="no1"></td>
                                  </tr>
                                  <tr>
                                      <td>2</td>
                                      <td>
                                          Beri edukasi pada pasien mengenai resiko terjadinya jatuh, sosialisasikan, tempelkan penanda pada bed sisi kaki pasien dan digantung pada nomor kamar pasien,agar mudah dibaca dan tidak hilang
                                      </td>
                                      <td><input type="checkbox" value="1" name="no2"></td>
                                  </tr>
                                  <tr>
                                      <td>3</td>
                                      <td>
                                          Tempatkan pasien pada bed dan dilengkapi dengan penghalang, roda terkunci.
                                      </td>
                                      <td><input type="checkbox" value="1" name="no3"></td>
                                  </tr>
                                  <tr>
                                      <td>4</td>
                                      <td>
                                          Ciptakan lingkungan/ kamar cukup penerangan dan pencahayaan.
                                      </td>
                                      <td><input type="checkbox" value="1" name="no4"></td>
                                  </tr>
                                  <tr>
                                      <td>5</td>
                                      <td>
                                          Anjurkan pasien untuk menggunakan alas kaki atau sepatu yang tidak licin
                                      </td>
                                      <td><input type="checkbox" value="1" name="no5"></td>
                                  </tr>
                                  <tr>
                                      <td>6</td>
                                      <td>
                                          Nilai kemampuan ke kamar mandi dan memberikan bantuan bila dibutuhkan
                                      </td>
                                      <td><input type="checkbox" value="1" name="no6"></td>
                                  </tr>
                                  <tr>
                                      <td>7</td>
                                      <td>
                                          Anjurkan pasien/keluarga untuk meminta bantuan dengan memanggil petugas yang sedang berjaga
                                      </td>
                                      <td><input type="checkbox" value="1" name="no7"></td>
                                  </tr>
                                  <tr>
                                      <td>8</td>
                                      <td>
                                          Ciptakan lingkungan bebas dari peralatan yang mengandung resiko seperti;kain karpet yang licin dan selimut yang diletakkan dilantai bawah bed pasien
                                      </td>
                                      <td><input type="checkbox" value="1" name="no8"></td>
                                  </tr>
                                  </tbody>
                                 </table>
                                 <div class="text-center" style="margin-top: 10px; margin-bottom: 20px;">
                                    <button type="submit" name="save" class="btn btn-primary">Simpan</button>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="card p-2">
                          <h6 class="card-title">Riwayat</h6>
                          <table class="table table-bordered">
                            <thead>
                              <tr>
                                  <th>No</th>
                                  <th>Nama</th>
                                  <th>Tanggal</th>
                                  <th>Status</th>
                                  <th>Aksi</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                                  $no = 1;
                                  $getFR = $koneksi->query("SELECT * FROM morse_igd WHERE no_rm = '$pecah[no_rm]' AND tipe!= 'inap' ORDER BY tgl_masuk DESC");
                                  foreach ($getFR as $data) {
                              ?>
                                  <tr>
                                      <td><?= $no++?></td>
                                      <td><?= $data['pasien']?></td>
                                      <td><?= $data['tgl_masuk']?></td>
                                      <td><?= $data['status_asesmen']?></td>
                                      <td><a href="index.php?halaman=faldewasa&id=<?= $_GET['id']?>&idfr=<?= $data['id_morse']?>" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i></a></td>
                                  </tr>
                              <?php }?>
                            </tbody>
                          </table>
                        </div>
                     </div>
                  </div>
               </div>
            </form>
          <?php }else{?>
            <?php $fr = $koneksi->query("SELECT * FROM morse_igd WHERE id_morse = '$_GET[idfr]'")->fetch_assoc();?>
            <form class="row g-3" method="post" enctype="multipart/form-data">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="index.php?halaman=faldewasa&id=<?= $_GET['id']?>" class="btn btn-sm btn-dark ">kembali</a>
                            <div class="card" style="margin-top:10px">
                                <div class="card-body col-md-12">
                                    <h5 class="card-title">Data Pasien</h5>
                                    <!-- Multi Columns Form -->
                                    <div class="col-md-12">
                                        <label for="inputName5" class="form-label">Nama Pasien</label>
                                        <input disabled type="text" class="form-control" name="pasien" id="inputName5"  value="<?php echo $pecah['nama_pasien']?>" placeholder="Masukkan Nama Pasien">
                                    </div>
                                    <div class="col-md-12">
                                        <label for="inputName5" style="margin-top: 10px;" class="form-label">NO RM Pasien</label>
                                        <input disabled type="text" class="form-control" name="no_rm" id="inputName5"  value="<?php echo $pecah['no_rm']?>" placeholder="Masukkan No RM Pasien">
                                    </div>
                                    <!-- <input type="hidden" class="form-control" id="inputName5" name="id_rm" value="<?php echo $pecah['id_rm']?>"> -->
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
                                        <div class="col-md-12 mb-2">
                                        <label for="inputName5" class="form-label">Status Asesmen</label>
                                        <select disabled class="form-control" name="status_asesmen" id="status_asesmen">
                                            <option value="<?= $fr['status_asesmen']?>" selected><?= $fr['status_asesmen']?></option>
                                            <option value="Asesmen Awal">Asesmen Awal</option>
                                            <option value="Asesmen Ulang">Asesmen Ulang</option>
                                        </select>
                                        </div>
                                        <?php if($fr['status_asesmen']=='Asesmen Ulang'){?>
                                            <div >
                                                <dvi class="row">
                                                    <div class="col-md-3">
                                                        <?php if($fr['pk']=='1'){?>
                                                            <input type="checkbox" disabled name="PK" value="1" checked>Perubahan Kondisi (PK)</input>
                                                        <?php }else{?>
                                                            <input type="checkbox" disabled name="PK" value="1">Perubahan Kondisi (PK)</input>
                                                        <?php }?>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <?php if($fr['j']=='1'){?>
                                                            <input type="checkbox" disabled name="J" value="1" checked>Setelah Jatuh (J)</input>
                                                        <?php }else{?>
                                                            <input type="checkbox" disabled name="J" value="1">Setelah Jatuh (J)</input>
                                                        <?php }?>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <?php if($fr['t']=='1'){?>
                                                            <input type="checkbox" disabled name="T" value="1" checked>Transfer Pasien (T)</input>
                                                        <?php }else{?>
                                                            <input type="checkbox" disabled name="T" value="1">Transfer Pasien (T)</input>
                                                        <?php }?>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <?php if($fr['dll']=='1'){?>
                                                            <input type="checkbox" disabled name="DLL" value="1" checked>Kebutuhan Khusun (DLL)</input>
                                                        <?php }else{?>
                                                            <input type="checkbox" disabled name="DLL" value="1">Kebutuhan Khusun (DLL)</input>
                                                        <?php }?>
                                                    </div>
                                                </dvi>
                                            </div>
                                        <?php }?>
                                        <script>
                                            document.getElementById('status_asesmen').addEventListener('change', function() {
                                            var formLain = document.getElementById('as');
                                            if (this.value === 'Asesmen Ulang') {
                                                formLain.classList.remove('hidden');
                                            } else {
                                                formLain.classList.add('hidden');
                                            }
                                            });
                                        </script>
                                        <div class="col-md-12 mb-2">
                                        <label for="inputName5" class="form-label">Tanggal</label>
                                        <input type="datetime" disabled class="form-control" name="tgl_masuk"  id="inputName5" placeholder="Sesuai dengan ICD 9 CM dan standar lainnya" value="<?= $fr['tgl_masuk'] ?>">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                        <label for="" class="form-label">Riwayat jatuh baru/3 bln terakhir</label>
                                        <select disabled onchange="updateTotal()" name="riwayat_jatuh" class="form-control">
                                            <option selected value="<?= $fr['riwayat_jatuh']?>"><?= $fr['riwayat_jatuh']?></option>
                                            <option hidden>Pilih</option>
                                            <option value="25">Ya</option>
                                            <option value="0">Tidak</option>
                                        </select>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                        <label for="" class="form-label">Diagnosis medissekunder > 1</label>
                                        <select disabled onchange="updateTotal()" name="medis_sekunder" class="form-control">
                                            <option selected value="<?= $fr['medis_sekunder']?>"><?= $fr['medis_sekunder']?></option>
                                            <option value="0" hidden>Pilih</option>
                                            <option value="15">Ya</option>
                                            <option value="0">Tidak</option>
                                        </select>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                        <label for="" class="form-label">Alat bantu Jalan</label>
                                        <select disabled onchange="updateTotal()" name="alat_bantu" class="form-control">
                                            <option selected value="<?= $fr['alat_bantu']?>"><?= $fr['alat_bantu']?></option>
                                            <option value="0" hidden>Pilih</option>
                                            <option value="15">Tongkat/Alat Bantu Jalan</option>
                                            <option value="30">Berpegang Pada Furniture</option>
                                            <option value="0">Tidak</option>
                                        </select>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                        <label for="" class="form-label">Terpasang infus/terapi antikoagulasi</label>
                                        <select disabled onchange="updateTotal()" name="terpasang" class="form-control">
                                            <option selected value="<?= $fr['terpasang']?>"><?= $fr['terpasang']?></option>
                                            <option value="0" hidden>Pilih</option>
                                            <option value="25">Ya</option>
                                            <option value="0">Tidak</option>
                                        </select>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                        <label for="" class="form-label">Cara Berjalan Berpindah</label>
                                        <select disabled onchange="updateTotal()" name="cara_jalan" class="form-control">
                                            <option selected value="<?= $fr['cara_jalan']?>"><?= $fr['cara_jalan']?></option>
                                            <option value="0" hidden>Pilih</option>
                                            <option value="15">Lemah</option>
                                            <option value="30">Terganggu</option>
                                            <option value="0">Normal</option>
                                        </select>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                        <label for="" class="form-label">Status Mental</label>
                                        <select disabled onchange="updateTotal()" name="status_mental" class="form-control">
                                            <option selected value="<?= $fr['status_mental']?>"><?= $fr['status_mental']?></option>
                                            <option value="0" hidden>Pilih</option>
                                            <option value="15">Lupa Keterbatasan Diri</option>
                                            <option value="0">Tidak</option>
                                        </select>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <input type="number" id="total" name="total" value="<?= $fr['total']?>" class="form-control" disabled>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <input type="text" name="status" id="status" value="<?= $fr['status_asesmen']?>"  class="form-control" disabled>
                                            <input type="text" name="perawat" value="<?= $_SESSION['admin']['namalengkap']?>" class="form-control" hidden>
                                        </div>
                                        <script>
                                            function updateTotal() {
                                            // Ambil nilai dari setiap elemen select
                                            //  var riwayat_jatuh = parseInt(document.getElementsByName("riwayat_jatuh")[0].value);
                                            var riwayat_jatuh = parseInt(document.getElementsByName("riwayat_jatuh")[0].value);
                                            var medis_sekunder = parseInt(document.getElementsByName("medis_sekunder")[0].value);
                                            var alat_bantu = parseInt(document.getElementsByName("alat_bantu")[0].value);
                                            var terpasang = parseInt(document.getElementsByName("terpasang")[0].value);
                                            var cara_jalan = parseInt(document.getElementsByName("cara_jalan")[0].value);
                                            var status_mental = parseInt(document.getElementsByName("status_mental")[0].value);
                                            // Tambahkan elemen select lainnya di sini
                                        
                                            // Hitung total nilai
                                            var total = riwayat_jatuh + medis_sekunder + alat_bantu + terpasang + cara_jalan + status_mental;
                                            //  var total = riwayat_jatuh ;
                                            // Tambahkan nilai elemen select lainnya di sini
                                            if(total <= 24){
                                                var stts = 'Tidak Resiko';
                                            }
                                            
                                            if(total >= 25 && total <= 50){
                                                var stts = 'Resiko Rendah';
                                            }
            
                                            if(total >= 51){
                                                var stts = 'Resiko Tinggi';
                                            }
                                        
                                            // Tampilkan total nilai dalam elemen dengan ID 'total'
                                            document.getElementById('total').value = total;
                                            document.getElementById('status').value = stts;
                                            }
                                        </script>
                                        <br>
                                        <br>
                                        <br>
                                        <h5 for="" class="form-label">Obat-obatan yan Dikonsumsi</h5>
                                        <div>
                                            <?= $fr['obat']?>
                                        </div>
                                        <br>
                                        <br>
                                        <center>
                                            <h6 class="card-title">INTERVENSI RESIKO JATUH (dilakukan pada semua pasien saat setelah asesmen awal)</h6>
                                        </center>
                                        <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>INTERVENSI RESIKO JATUH STANDAR</th>
                                                <th>CHECKLIST</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>
                                                Orientasikan ruangan pada pasien dan keluarga.
                                            </td>
                                            <td>
                                                <?php if($fr['no1']== '1'){?>
                                                    <input type="checkbox" value="1" name="no1" checked disabled>
                                                <?php }else{?>
                                                    <input type="checkbox" value="1" name="no1" disabled>
                                                <?php }?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>
                                                Beri edukasi pada pasien mengenai resiko terjadinya jatuh, sosialisasikan, tempelkan penanda pada bed sisi kaki pasien dan digantung pada nomor kamar pasien,agar mudah dibaca dan tidak hilang
                                            </td>
                                            <td>
                                                <?php if($fr['no2']== '1'){?>
                                                    <input type="checkbox" value="1" name="no2" checked disabled>
                                                <?php }else{?>
                                                    <input type="checkbox" value="1" name="no2" disabled>
                                                <?php }?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>
                                                Tempatkan pasien pada bed dan dilengkapi dengan penghalang, roda terkunci.
                                            </td>
                                            <td>
                                                <?php if($fr['no3']== '1'){?>
                                                    <input type="checkbox" value="1" name="no3" checked disabled>
                                                <?php }else{?>
                                                    <input type="checkbox" value="1" name="no3" disabled>
                                                <?php }?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>
                                                Ciptakan lingkungan/ kamar cukup penerangan dan pencahayaan.
                                            </td>
                                            <td>
                                                <?php if($fr['no4']== '1'){?>
                                                    <input type="checkbox" value="1" name="no4" checked disabled>
                                                <?php }else{?>
                                                    <input type="checkbox" value="1" name="no4" disabled>
                                                <?php }?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>
                                                Anjurkan pasien untuk menggunakan alas kaki atau sepatu yang tidak licin
                                            </td>
                                            <td>
                                                <?php if($fr['no5']== '1'){?>
                                                    <input type="checkbox" value="1" name="no5" checked disabled>
                                                <?php }else{?>
                                                    <input type="checkbox" value="1" name="no5" disabled>
                                                <?php }?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>
                                                Nilai kemampuan ke kamar mandi dan memberikan bantuan bila dibutuhkan
                                            </td>
                                            <td>
                                                <?php if($fr['no6']== '1'){?>
                                                    <input type="checkbox" value="1" name="no6" checked disabled>
                                                <?php }else{?>
                                                    <input type="checkbox" value="1" name="no6" disabled>
                                                <?php }?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>7</td>
                                            <td>
                                                Anjurkan pasien/keluarga untuk meminta bantuan dengan memanggil petugas yang sedang berjaga
                                            </td>
                                            <td>
                                                <?php if($fr['no7']== '1'){?>
                                                    <input type="checkbox" value="1" name="no7" checked disabled>
                                                <?php }else{?>
                                                    <input type="checkbox" value="1" name="no7" disabled>
                                                <?php }?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>8</td>
                                            <td>
                                                Ciptakan lingkungan bebas dari peralatan yang mengandung resiko seperti;kain karpet yang licin dan selimut yang diletakkan dilantai bawah bed pasien
                                            </td>
                                            <td>
                                                <?php if($fr['no8']== '1'){?>
                                                    <input type="checkbox" value="1" name="no8" checked disabled>
                                                <?php }else{?>
                                                    <input type="checkbox" value="1" name="no8" disabled>
                                                <?php }?>
                                            </td>
                                        </tr>
                                        </tbody>
                                        </table>
                                        <div class="text-center" style="margin-top: 10px; margin-bottom: 20px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
          <?php }?>
          <!-- End Multi Columns Form -->
       </div>
    </main>
    <?php
        if(isset($_POST['save'])){
            
            if(isset($_POST['no1'])){
                $no1 = 1;
            }else{
                $no1 = 0;
            }
            if(isset($_POST['no2'])){
                $no2 = 1;
            }else{
                $no2 = 0;
            }
            if(isset($_POST['no3'])){
                $no3 = 1;
            }else{
                $no3 = 0;
            }
            if(isset($_POST['no4'])){
                $no4 = 1;
            }else{
                $no4 = 0;
            }
            if(isset($_POST['no5'])){
                $no5 = 1;
            }else{
                $no5 = 0;
            }
            if(isset($_POST['no6'])){
                $no6 = 1;
            }else{
                $no6 = 0;
            }
            if(isset($_POST['no7'])){
                $no7 = 1;
            }else{
                $no7 = 0;
            }
            if(isset($_POST['no8'])){
                $no8 = 1;
            }else{
                $no8 = 0;
            }
            if(isset($_POST['PK'])){
                $PK = '1';
            }else{
                $PK = '0';
            }
            if(isset($_POST['J'])){
                $J = '1';
            }else{
                $J = '0';
            }
            if(isset($_POST['T'])){
                $T = '1';
            }else{
                $T = '0';
            }
            if(isset($_POST['DLL'])){
                $DLL = '1';
            }else{
                $DLL = '0';
            }
            
            $obat = implode(", ",$_POST['obat']);
            $koneksi->query("INSERT morse_igd (no_rm, pasien, tgl_masuk, perawat, riwayat_jatuh, medis_sekunder, alat_bantu, terpasang, cara_jalan, status_mental, total, status, obat, no1, no2, no3, no4, no5, no6, no7, no8, status_asesmen, pk, j, t, dll) VALUES ('$_POST[no_rm]', '$_POST[pasien]', '$_POST[tgl_masuk]', '$_POST[perawat]', '$_POST[riwayat_jatuh]', '$_POST[medis_sekunder]', '$_POST[alat_bantu]', '$_POST[terpasang]', '$_POST[cara_jalan]', '$_POST[status_mental]', '$_POST[total]', '$_POST[status]', '$obat', '$no1', '$no2', '$no3', '$no4', '$no5', '$no6', '$no7', '$no8', '$_POST[status_asesmen]', '$PK','$J','$T','$DLL')");
    
            echo "
                <script>
                    alert('Berhasil Menambah FallRisk IGD');
                    document.location.href='index.php?halaman=faldewasa&id=$_GET[id]';
                </script>
            ";
        }
    ?>
    </body>
</html>