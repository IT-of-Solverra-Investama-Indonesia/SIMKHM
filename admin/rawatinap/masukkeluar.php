<?php
  $date= date("Y-m-d");
  date_default_timezone_set('Asia/Jakarta');
  $pasien=$koneksi->query("SELECT * FROM pasien  WHERE no_rm='$_GET[id]';")->fetch_assoc();
  $jadwal=$koneksi->query("SELECT * FROM registrasi_rawat  WHERE no_rm='$_GET[id]' AND date_format(jadwal, '%Y-%m-%d') = '$_GET[tgl]' ")->fetch_assoc();
?>
<?php if(!isset($_GET['view'])){?>
    <!DOCTYPE html>
    <html lang="en">
      <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
      <script rel="javascript" type="text/javascript" href="js/jquery-1.11.3.min.js"></script>
      <!-- Select2 CSS --> 
      <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" /> 
    
      <!-- jQuery --> 
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
    
      <!-- Select2 JS --> 
      <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
        <title>Document</title>
      </head>
      <body>
        <main>
          <div class="container">
            <div class="pagetitle">
              <h1>Ringkasan Masuk Keluar</h1>
              <nav>
                <ol class="breadcrumb">
                  <li class="breadcrumb-item active">
                    <a href="index.php?halaman=daftarrmedis" style="color:blue;">Rekam Medis</a>
                  </li>
                  <li class="breadcrumb-item">Ringkasan Masuk Keluar</li>
                </ol>
              </nav>
            </div>
            <!-- End Page Title -->
            <form class="row g-3" method="post" enctype="multipart/form-data">
              <div class="container">
                <div class="row">
                  <div class="col-md-12">
                    <div class="card" style="margin-top:10px">
                      <div class="card-body col-md-12">
                        <h5 class="card-title">Data Pasien</h5>
                        <div class="row">
                          <div class="col-md-6">
                            <label for="inputName5" class="form-label">Nama Pasien</label>
                            <input type="text" class="form-control" name="pasien" id="inputName5" value="<?php echo $pasien['nama_lengkap']?>" placeholder="Masukkan Nama Pasien" readonly>
                          </div>
                          <div class="col-md-6" style="margin-bottom:20px;">
                            <label for="inputName5" class="form-label">No RM</label>
                            <input type="text" class="form-control" id="inputName5" name="norm" value="<?php echo $pasien['no_rm']?>" placeholder="Masukkan Nama Pasien" readonly>
                          </div>
                          <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                            <label for="inputName5" class="form-label">Tanggal Lahir</label>
                            <input type="text" class="form-control" id="inputName5" name="tgl_lahir" value="<?php echo date("d-m-Y", strtotime($pasien['tgl_lahir']))?>" placeholder="Masukkan Nama Pasien" readonly>
                          </div>
                          <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                            <label for="inputName5" class="form-label">Alamat Lengkap:</label>
                            <input type="text" class="form-control" id="inputName5" name="alamat" value="<?php echo $pasien['alamat']?>" placeholder="Masukkan Nama Pasien" readonly>
                          </div>
                          <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                            <label for="inputName5" class="form-label">RT:</label>
                            <input type="text" class="form-control" id="inputName5" name="rt" value="<?php echo $pasien['rt']?>" placeholder="Masukkan Nama Pasien" readonly>
                          </div>
                          <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                            <label for="inputName5" class="form-label">RW:</label>
                            <input type="text" class="form-control" id="inputName5" name="rw" value="<?php echo $pasien['rw']?>" placeholder="Masukkan Nama Pasien" readonly>
                          </div>
                          <div class="col-md-4" style="margin-top: 10px; margin-bottom:20px;">
                            <label for="inputName5" class="form-label">Desa/Kelurahan:</label>
                            <input type="text" class="form-control" id="inputName5" name="kelurahan" value="<?php echo $pasien['kelurahan']?>" placeholder="Masukkan Nama Pasien" readonly>
                          </div>
                          <div class="col-md-4" style="margin-top: 10px; margin-bottom:20px;">
                            <label for="inputName5" class="form-label">Kab:</label>
                            <input type="text" class="form-control" id="inputName5" name="kabupaten" value="<?php echo $pasien['kecamatan']?>" placeholder="Masukkan Nama Pasien" readonly>
                          </div>
                          <div class="col-md-4" style="margin-top: 10px; margin-bottom:20px;">
                            <label for="inputName5" class="form-label">Kot:</label>
                            <input type="text" class="form-control" id="inputName5" name="kota" value="<?php echo $pasien['kota']?>" placeholder="Masukkan Nama Pasien" readonly>
                          </div>
                          <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                            <label for="inputName5" class="form-label">Ruangan</label>
                            <input type="text" class="form-control" readonly id="inputName5" name="kamar" value="<?php echo $jadwal['kamar']?>" placeholder="Masukkan Nama Pasien">
                          </div> <?php if($pasien["jenis_kelamin"] == 1){ ?> <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                            <label for="inputName5" class="form-label">Jenis Kelamin</label>
                            <input type="text" class="form-control" id="inputName5" name="jenis_kelamin" value="Laki-laki" placeholder="Masukkan Nama Pasien" readonly>
                          </div> <?php }else{ ?> <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                            <label for="inputName5" class="form-label">Jenis Kelamin</label>
                            <input type="text" class="form-control" id="inputName5" name="jenis_kelamin" value="Perempuan" placeholder="Masukkan Nama Pasien" readonly>
                          </div> <?php } ?>
                          <div class="col-md-3" style="margin-top: 10px; margin-bottom:20px;">
                            <label for="inputName5" class="form-label">Agama:</label>
                            <input type="text" class="form-control" id="inputName5" name="agama" value="<?php echo $pasien['agama']?>" placeholder="Masukkan Nama Pasien" readonly>
                          </div>
                          <div class="col-md-3" style="margin-top: 10px; margin-bottom:20px;">
                            <label for="inputName5" class="form-label">Perkawinan:</label>
                            <input type="text" class="form-control" id="inputName5" name="status_nikah" value="<?php echo $pasien['status_nikah']?>" placeholder="Masukkan Nama Pasien" readonly>
                          </div>
                          <div class="col-md-3" style="margin-top: 10px; margin-bottom:20px;">
                            <label for="inputName5" class="form-label">Pendidikan:</label>
                            <input type="text" class="form-control" id="inputName5" name="pendidikan" value="<?php echo $pasien['pendidikan']?>" placeholder="Masukkan Nama Pasien" readonly>
                          </div>
                          <div class="col-md-3" style="margin-top: 10px; margin-bottom:20px;">
                            <label for="inputName5" class="form-label">Pekerjaan:</label>
                            <input type="text" class="form-control" id="inputName5" name="pekerjaan" value="<?php echo $pasien['pekerjaan']?>" placeholder="Masukkan Nama Pasien" readonly>
                          </div>
                        </div>
                        <br>
                        <h5 class="card-title">Cara Masuk & Pemiayaan</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Cara Masuk Dikirim</label>
                                <select name="cara_masuk" class="form-control mb-3" id="">
                                    <option value="" hidden>Pilih Cara Masuk</option>
                                    <option value="Dokter">Dokter</option>
                                    <option value="Kasus Polisi">Kasus Polisi</option>
                                    <option value="Puskesmas">Puskesmas</option>
                                    <option value="Sendiri">Sendiri</option>
                                    <option value="Instansi Lain">Instansi Lain</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="">Cara Pembayaran</label>
                                <select name="cara_bayar" class="form-control mb-3" id="">
                                    <option value="" hidden>Pilih Cara Pembayaran</option>
                                    <option value="Umum">Umum</option>
                                    <option value="BPJS">BPJS</option>
                                </select>
                            </div>
                        </div>
                        <br>
                        <h5 class="card-title">Assesment</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Penderita Alergi Akan</label>
                                <input type="text" class="form-control mb-3" name="alergi" id="" placeholder="Penderita Alergi Akan">
                            </div>
                            <div class="col-md-6">
                                <label for="">Diagnosa Awal</label>
                                <input type="text" class="form-control mb-3" name="diagnosis_awal" id="" placeholder="Diagnosa Awal">
                            </div>
                            <div class="col-md-6">
                                <label for="">Diagnosa Utama</label>
                                <input type="text" class="form-control mb-3" name="diagnosis_utama" id="" placeholder="Diagnosa Utama">
                            </div>
                            <div class="col-md-6">
                                <label for="">Diagnosa Sekunder</label>
                                <textarea name="diagnosis_sekunder" id="" class="form-control w-100 mb-3" placeholder="Diagnosa Sekunder"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="">ICD 10</label>
                                <select class="form-select" style="height: 20px;" id="selUser" aria-label="Default select example"  >
                                    <option value="">Pilih</option>
                                    <?php 
                                        $ambil2=$koneksi->query("SELECT * FROM icds");
                                        while($perkat2=$ambil2->fetch_assoc()) {
                                    ?>
                                        <option value="<?php echo $perkat2['code']; ?>"> <?php echo $perkat2['code']; ?> - <?php echo $perkat2['name_en']; ?> </option>
                                    <?php } ?>                                    
                                </select>
                                <script type="text/javascript">
                                    $(document).ready(function(){
                                        // Initialize select2
                                        $("#selUser").select2();
                                        $("#selUser").on("change", function() {
                                            // Ambil teks dari elemen select yang dipilih
                                            var selectedText = $("#selUser option:selected").text();
                                            
                                            // Ambil teks yang sudah ada di dalam textarea
                                            var currentText = $("#icds").val();
                                            
                                            // Gabungkan teks yang sudah ada dengan teks baru dan tambahkan pemisah jika diperlukan
                                            var newText = currentText + (currentText ? ', ' : '') + selectedText;
                                            
                                            // Tampilkan teks yang baru pada textarea dengan id "icds"
                                            $("#icds").val(newText);
                                        });
                                    });
                                </script>
                                <textarea name="icd10" id="icds" class="form-control w-100 mb-3" placeholder="Diagnosa Sekunder"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="">Masuk Tanggal</label>
                                <input type="datetime-local" name="masuk_tanggal" id="" class="form-control w-100 mb-3" placeholder="Masuk Tanggal">
                            </div>
                            <div class="col-md-6">
                                <label for="">Masuk Di Ruang</label>
                                <input type="text" name="masuk_kamar" id="" class="form-control w-100 mb-3" placeholder="Masuk Di Ruang">
                            </div>
                            <div class="col-md-6">
                                <label for="">Dipindah Tanggal</label>
                                <input type="datetime-local" name="pindah_tanggal" id="" class="form-control w-100 mb-3" placeholder="Dipindah Tanggal">
                            </div>
                            <div class="col-md-6">
                                <label for="">Dipindah Ke-ruang</label>
                                <input type="text" name="pindah_ruang" id="" class="form-control w-100 mb-3" placeholder="Dipindah Ke-ruang">
                            </div>
                            <div class="col-md-6">
                                <label for="">Keluar Tanggal</label>
                                <input type="datetime-local" name="keluar_tanggal" id="" class="form-control w-100 mb-3" placeholder="Keluar Tanggal">
                            </div>
                            <div class="col-md-6">
                                <label for="">Cara Keluar</label>
                                <input type="text" name="cara_keluar" id="cara_keluar" class="form-control w-100 mb-3" placeholder="Cara Keluar">
                            </div>
                            <div class="col-md-6">
                                <label for="">Keadaan Keluar</label>
                                <input type="text" name="keadaan_keluar" id="" class="form-control w-100 mb-3" placeholder="Keadaan Keluar">
                            </div>
                            <div class="col-md-12">
                                <label for="">Terapi</label>
                                <textarea name="terapi" id="" class="form-control w-100" placeholder="Terapi"></textarea>
                            </div>
                        </div>
                        <br>
                        <h5 class="card-title">Penanggung Jawab</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Nama Penanggung Jawab</label>
                                <input type="text" class="form-control mb-3" name="penanggung_jawab"  id="" placeholder="Nama Penanggung Jawab">
                            </div>
                            <div class="col-md-6">
                                <label for="">Hubungan dengan Pasien</label>
                                <input type="text" class="form-control mb-3" name="hubungan"  id="" placeholder="Hubungan dengan Pasien">
                            </div>
                            <div class="col-md-6">
                                <label for="">Alamat</label>
                                <input type="text" class="form-control mb-3" name="alamat_penganggung_jawab"  id="" placeholder="Alamat">
                            </div>
                            <div class="col-md-6">
                                <label for="">Telepon</label>
                                <input type="text" class="form-control mb-3" name="telepon"  id="" placeholder="Telepon">
                            </div>
                            <!-- <button class="btn btn-primary w-100" name="save">Simpan</button> -->
                            <div class="text-center" style="margin-top: 10px; margin-bottom: 40px;">
                                    <button type="submit" name="save" class="btn btn-primary">Simpan</button>
                                    <button type="reset" class="btn btn-secondary">Reset</button>
                                    </div>
                        </div>
                      </div>
                    </div>
                    <br>
                    <div class="card shadow p-3">
                        <h5 class="card-title">Riwayat Masuk Keluar</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Pasien</th>
                                    <th>No RM</th>
                                    <th>Tanggal Pengisian</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $getMasuk = $koneksi->query("SELECT * FROM masuk_keluar WHERE pasien ='$pasien[nama_lengkap]' AND norm = '$pasien[no_rm]'");
                                ?>
                                <?php foreach($getMasuk as $data){?>
                                    <tr>
                                        <td><?= $data['pasien']?></td>
                                        <td><?= $data['norm']?></td>
                                        <td><?= $data['created_at']?></td>
                                        <td>
                                            <a href="index.php?halaman=masukkeluar&id=<?= $_GET['id']?>&inap&tgl=<?= $_GET['tgl']?>&view=<?= $data['id_masuk_keluar']?>" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i></a>
                                        </td>
                                    </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                  </div>
                </div>
              </div>
            </form>
            <?php
                if(isset($_POST['save'])){
                    $koneksi->query("INSERT INTO masuk_keluar (pasien, norm, tgl_lahir, alamat, rt, rw, kelurahan, kabupaten, kota, kamar, agama, status_nikah, pendidikan, pekerjaan, cara_masuk, cara_bayar, alergi, diagnosis_awal, diagnosis_utama, diagnosis_sekunder, icd10, masuk_tanggal, masuk_kamar, pindah_tanggal, pindah_ruang, keluar_tanggal, cara_keluar, keadaan_keluar, terapi, penanggung_jawab, hubungan, alamat_penganggung_jawab, telepon) VALUES ('$_POST[pasien]', '$_POST[norm]', '$_POST[tgl_lahir]', '$_POST[alamat]', '$_POST[rt]', '$_POST[rw]', '$_POST[kelurahan]', '$_POST[kabupaten]', '$_POST[kota]', '$_POST[kamar]', '$_POST[agama]', '$_POST[status_nikah]', '$_POST[pendidikan]', '$_POST[pekerjaan]', '$_POST[cara_masuk]', '$_POST[cara_bayar]', '$_POST[alergi]', '$_POST[diagnosis_awal]', '$_POST[diagnosis_utama]', '$_POST[diagnosis_sekunder]', '$_POST[icd10]', '$_POST[masuk_tanggal]', '$_POST[masuk_kamar]', '$_POST[pindah_tanggal]', '$_POST[pindah_ruang]', '$_POST[keluar_tanggal]', '$_POST[cara_keluar]', '$_POST[keadaan_keluar]', '$_POST[terapi]', '$_POST[penanggung_jawab]', '$_POST[hubungan]', '$_POST[alamat_penganggung_jawab]', '$_POST[telepon]')");
                    echo "
                        <script>
                            alert('Berhasil Menambah Ringkasan Masuk Keluar');
                            document.location.href='index.php?halaman=masukkeluar&id=$_GET[id]&inap&tgl=$_GET[tgl]';
                        </script>
                    ";
                }
            ?>
          </div>
        </main>
      </body>
    </html>
<?php }else{?>
    <?php $mk = $koneksi->query("SELECT * FROM masuk_keluar WHERE id_masuk_keluar = '$_GET[view]'")->fetch_assoc();?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script rel="javascript" type="text/javascript" href="js/jquery-1.11.3.min.js"></script>
        <!-- Select2 CSS --> 
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" /> 
    
        <!-- jQuery --> 
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
    
        <!-- Select2 JS --> 
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
        <title>Document</title>
        </head>
        <body>
        <main>
            <div class="container">
            <div class="pagetitle">
                <h1>Ringkasan Masuk Keluar</h1>
                <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">
                    <a href="index.php?halaman=daftarrmedis" style="color:blue;">Rekam Medis</a>
                    </li>
                    <li class="breadcrumb-item">Ringkasan Masuk Keluar</li>
                </ol>
                </nav>
            </div>
            <!-- End Page Title -->
            <form class="row g-3" method="post" enctype="multipart/form-data">
                <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <a href="index.php?halaman=masukkeluar&id=<?= $_GET['id']?>&inap&tgl=<?= $_GET['tgl']?>" class="btn btn-sm btn-dark">Kembali</a>
                        <div class="card" style="margin-top:10px">
                            <div class="card-body col-md-12">
                            <h5 class="card-title">Data Pasien</h5>
                            <div class="row">
                                <div class="col-md-6">
                                <label for="inputName5" class="form-label">Nama Pasien</label>
                                <input type="text" class="form-control" name="pasien" id="inputName5" value="<?php echo $pasien['nama_lengkap']?>" placeholder="Masukkan Nama Pasien" readonly>
                                </div>
                                <div class="col-md-6" style="margin-bottom:20px;">
                                <label for="inputName5" class="form-label">No RM</label>
                                <input type="text" class="form-control" id="inputName5" name="norm" value="<?php echo $pasien['no_rm']?>" placeholder="Masukkan Nama Pasien" readonly>
                                </div>
                                <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                                <label for="inputName5" class="form-label">Tanggal Lahir</label>
                                <input type="text" class="form-control" id="inputName5" name="tgl_lahir" value="<?php echo date("d-m-Y", strtotime($pasien['tgl_lahir']))?>" placeholder="Masukkan Nama Pasien" readonly>
                                </div>
                                <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                                <label for="inputName5" class="form-label">Alamat Lengkap:</label>
                                <input type="text" class="form-control" id="inputName5" name="alamat" value="<?php echo $pasien['alamat']?>" placeholder="Masukkan Nama Pasien" readonly>
                                </div>
                                <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                                <label for="inputName5" class="form-label">RT:</label>
                                <input type="text" class="form-control" id="inputName5" name="rt" value="<?php echo $pasien['rt']?>" placeholder="Masukkan Nama Pasien" readonly>
                                </div>
                                <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                                <label for="inputName5" class="form-label">RW:</label>
                                <input type="text" class="form-control" id="inputName5" name="rw" value="<?php echo $pasien['rw']?>" placeholder="Masukkan Nama Pasien" readonly>
                                </div>
                                <div class="col-md-4" style="margin-top: 10px; margin-bottom:20px;">
                                <label for="inputName5" class="form-label">Desa/Kelurahan:</label>
                                <input type="text" class="form-control" id="inputName5" name="kelurahan" value="<?php echo $pasien['kelurahan']?>" placeholder="Masukkan Nama Pasien" readonly>
                                </div>
                                <div class="col-md-4" style="margin-top: 10px; margin-bottom:20px;">
                                <label for="inputName5" class="form-label">Kab:</label>
                                <input type="text" class="form-control" id="inputName5" name="kabupaten" value="<?php echo $pasien['kecamatan']?>" placeholder="Masukkan Nama Pasien" readonly>
                                </div>
                                <div class="col-md-4" style="margin-top: 10px; margin-bottom:20px;">
                                <label for="inputName5" class="form-label">Kot:</label>
                                <input type="text" class="form-control" id="inputName5" name="kota" value="<?php echo $pasien['kota']?>" placeholder="Masukkan Nama Pasien" readonly>
                                </div>
                                <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                                <label for="inputName5" class="form-label">Ruangan</label>
                                <input type="text" class="form-control" readonly id="inputName5" name="kamar" value="<?php echo $jadwal['kamar']?>" placeholder="Masukkan Nama Pasien">
                                </div> <?php if($pasien["jenis_kelamin"] == 1){ ?> <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                                <label for="inputName5" class="form-label">Jenis Kelamin</label>
                                <input type="text" class="form-control" id="inputName5" name="jenis_kelamin" value="Laki-laki" placeholder="Masukkan Nama Pasien" readonly>
                                </div> <?php }else{ ?> <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                                <label for="inputName5" class="form-label">Jenis Kelamin</label>
                                <input type="text" class="form-control" id="inputName5" name="jenis_kelamin" value="Perempuan" placeholder="Masukkan Nama Pasien" readonly>
                                </div> <?php } ?>
                                <div class="col-md-3" style="margin-top: 10px; margin-bottom:20px;">
                                <label for="inputName5" class="form-label">Agama:</label>
                                <input type="text" class="form-control" id="inputName5" name="agama" value="<?php echo $pasien['agama']?>" placeholder="Masukkan Nama Pasien" readonly>
                                </div>
                                <div class="col-md-3" style="margin-top: 10px; margin-bottom:20px;">
                                <label for="inputName5" class="form-label">Perkawinan:</label>
                                <input type="text" class="form-control" id="inputName5" name="status_nikah" value="<?php echo $pasien['status_nikah']?>" placeholder="Masukkan Nama Pasien" readonly>
                                </div>
                                <div class="col-md-3" style="margin-top: 10px; margin-bottom:20px;">
                                <label for="inputName5" class="form-label">Pendidikan:</label>
                                <input type="text" class="form-control" id="inputName5" name="pendidikan" value="<?php echo $pasien['pendidikan']?>" placeholder="Masukkan Nama Pasien" readonly>
                                </div>
                                <div class="col-md-3" style="margin-top: 10px; margin-bottom:20px;">
                                <label for="inputName5" class="form-label">Pekerjaan:</label>
                                <input type="text" class="form-control" id="inputName5" name="pekerjaan" value="<?php echo $pasien['pekerjaan']?>" placeholder="Masukkan Nama Pasien" readonly>
                                </div>
                            </div>
                            <br>
                            <h5 class="card-title">Cara Masuk & Pemiayaan</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Cara Masuk Dikirim</label>
                                    <select name="cara_masuk" disabled class="form-control mb-3" id="">
                                        <option value="" hidden><?= $mk['cara_masuk']?></option>
                                        <option value="Dokter">Dokter</option>
                                        <option value="Kasus Polisi">Kasus Polisi</option>
                                        <option value="Puskesmas">Puskesmas</option>
                                        <option value="Sendiri">Sendiri</option>
                                        <option value="Instansi Lain">Instansi Lain</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Cara Pembayaran</label>
                                    <select name="cara_bayar" disabled class="form-control mb-3" id="">
                                        <option value="" hidden><?= $mk['cara_bayar']?></option>
                                        <option value="Umum">Umum</option>
                                        <option value="BPJS">BPJS</option>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <h5 class="card-title">Assesment</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Penderita Alergi Akan</label>
                                    <input type="text" class="form-control mb-3" value="<?= $mk['alergi']?>" readonly name="alergi" id="" placeholder="Penderita Alergi Akan">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Diagnosa Awal</label>
                                    <input type="text" class="form-control mb-3" value="<?= $mk['diagnosis_awal']?>" readonly name="diagnosis_awal" id="" placeholder="Diagnosa Awal">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Diagnosa Utama</label>
                                    <input type="text" class="form-control mb-3" value="<?= $mk['diagnosis_utama']?>" readonly name="diagnosis_utama" id="" placeholder="Diagnosa Utama">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Diagnosa Sekunder</label>
                                    <textarea value="<?= $mk['diagnosis_sekunder']?>" readonly name="diagnosis_sekunder" id="" class="form-control w-100 mb-3" placeholder="Diagnosa Sekunder"><?= $mk['diagnosis_sekunder']?></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="">ICD 10</label>
                                    <select class="form-select" style="height: 20px;" id="selUser" aria-label="Default select example"  >
                                        <option value="">Pilih</option>
                                        <?php 
                                            $ambil2=$koneksi->query("SELECT * FROM icds");
                                            while($perkat2=$ambil2->fetch_assoc()) {
                                        ?>
                                            <option value="<?php echo $perkat2['code']; ?>"> <?php echo $perkat2['code']; ?> - <?php echo $perkat2['name_en']; ?> </option>
                                        <?php } ?>                                    
                                    </select>
                                    <script type="text/javascript">
                                        $(document).ready(function(){
                                            // Initialize select2
                                            $("#selUser").select2();
                                            $("#selUser").on("change", function() {
                                                // Ambil teks dari elemen select yang dipilih
                                                var selectedText = $("#selUser option:selected").text();
                                                
                                                // Ambil teks yang sudah ada di dalam textarea
                                                var currentText = $("#icds").val();
                                                
                                                // Gabungkan teks yang sudah ada dengan teks baru dan tambahkan pemisah jika diperlukan
                                                var newText = currentText + (currentText ? ', ' : '') + selectedText;
                                                
                                                // Tampilkan teks yang baru pada textarea dengan id "icds"
                                                $("#icds").val(newText);
                                            });
                                        });
                                    </script>
                                    <textarea value="<?= $mk['icd10']?>" readonly name="icd10" id="icds" class="form-control w-100 mb-3" placeholder="ICD 10"><?= $mk['icd10']?></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Masuk Tanggal</label>
                                    <input type="datetime-local" value="<?= $mk['masuk_tanggal']?>" readonly name="masuk_tanggal" id="" class="form-control w-100 mb-3" placeholder="Masuk Tanggal">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Masuk Di Ruang</label>
                                    <input type="text" value="<?= $mk['masuk_kamar']?>" readonly name="masuk_kamar" id="" class="form-control w-100 mb-3" placeholder="Masuk Di Ruang">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Dipindah Tanggal</label>
                                    <input type="datetime-local" value="<?= $mk['pindah_tanggal']?>" readonly name="pindah_tanggal" id="" class="form-control w-100 mb-3" placeholder="Dipindah Tanggal">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Dipindah Ke-ruang</label>
                                    <input type="text" value="<?= $mk['pindah_ruang']?>" readonly name="pindah_ruang" id="" class="form-control w-100 mb-3" placeholder="Dipindah Ke-ruang">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Keluar Tanggal</label>
                                    <input type="datetime-local" value="<?= $mk['keluar_tanggal']?>" readonly name="keluar_tanggal" id="" class="form-control w-100 mb-3" placeholder="Keluar Tanggal">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Cara Keluar</label>
                                    <input type="text" value="<?= $mk['cara_keluar']?>" readonly name="cara_keluar" id="cara_keluar" class="form-control w-100 mb-3" placeholder="Cara Keluar">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Keadaan Keluar</label>
                                    <input type="text" value="<?= $mk['keadaan_keluar']?>" readonly name="keadaan_keluar" id="" class="form-control w-100 mb-3" placeholder="Keadaan Keluar">
                                </div>
                                <div class="col-md-12">
                                    <label for="">Terapi</label>
                                    <textarea value="<?= $mk['terapi']?>" readonly name="terapi" id="" class="form-control w-100" placeholder="Terapi"><?= $mk['terapi']?></textarea>
                                </div>
                            </div>
                            <br>
                            <h5 class="card-title">Penanggung Jawab</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Nama Penanggung Jawab</label>
                                    <input type="text" class="form-control mb-3" value="<?= $mk['penanggung_jawab']?>" readonly name="penanggung_jawab"  id="" placeholder="Nama Penanggung Jawab">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Hubungan dengan Pasien</label>
                                    <input type="text" class="form-control mb-3" value="<?= $mk['hubungan']?>" readonly name="hubungan"  id="" placeholder="Hubungan dengan Pasien">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Alamat</label>
                                    <input type="text" class="form-control mb-3" value="<?= $mk['alamat_penganggung_jawab']?>" readonly name="alamat_penganggung_jawab"  id="" placeholder="Alamat">
                                </div>
                                <div class="col-md-6">
                                    <label for="">Telepon</label>
                                    <input type="text" class="form-control mb-3" value="<?= $mk['telepon']?>" readonly name="telepon"  id="" placeholder="Telepon">
                                </div>
                            </div>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
                </div>
            </form>
            <?php
                if(isset($_POST['save'])){
                    $koneksi->query("INSERT INTO masuk_keluar (pasien, norm, tgl_lahir, alamat, rt, rw, kelurahan, kabupaten, kota, kamar, agama, status_nikah, pendidikan, pekerjaan, cara_masuk, cara_bayar, alergi, diagnosis_awal, diagnosis_utama, diagnosis_sekunder, icd10, masuk_tanggal, masuk_kamar, pindah_tanggal, pindah_ruang, keluar_tanggal, cara_keluar, keadaan_keluar, terapi, penanggung_jawab, hubungan, alamat_penganggung_jawab, telepon) VALUES ('$_POST[pasien]', '$_POST[norm]', '$_POST[tgl_lahir]', '$_POST[alamat]', '$_POST[rt]', '$_POST[rw]', '$_POST[kelurahan]', '$_POST[kabupaten]', '$_POST[kota]', '$_POST[kamar]', '$_POST[agama]', '$_POST[status_nikah]', '$_POST[pendidikan]', '$_POST[pekerjaan]', '$_POST[cara_masuk]', '$_POST[cara_bayar]', '$_POST[alergi]', '$_POST[diagnosis_awal]', '$_POST[diagnosis_utama]', '$_POST[diagnosis_sekunder]', '$_POST[icd10]', '$_POST[masuk_tanggal]', '$_POST[masuk_kamar]', '$_POST[pindah_tanggal]', '$_POST[pindah_ruang]', '$_POST[keluar_tanggal]', '$_POST[cara_keluar]', '$_POST[keadaan_keluar]', '$_POST[terapi]', '$_POST[penanggung_jawab]', '$_POST[hubungan]', '$_POST[alamat_penganggung_jawab]', '$_POST[telepon]')");
                    echo "
                        <script>
                            alert('Berhasil Menambah Ringkasan Masuk Keluar');
                            document.location.href='index.php?halaman=masukkeluar&id=$_GET[id]&inap&tgl=$_GET[tgl]';
                        </script>
                    ";
                }
            ?>
            </div>
        </main>
        </body>
    </html>
<?php }?>