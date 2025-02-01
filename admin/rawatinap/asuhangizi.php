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
        <title>Document</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" /> 
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
      </head>
      <body>
        <main>
          <div class="container">
            <div class="pagetitle">
              <h1>Asuhan Gizi Pasien Rawat Inap</h1>
              <nav>
                <ol class="breadcrumb">
                  <li class="breadcrumb-item active">
                    <a href="index.php?halaman=daftarrmedis" style="color:blue;">Rekam Medis</a>
                  </li>
                  <li class="breadcrumb-item">Asuhan Rawat Inap</li>
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
                        <!-- Multi Columns Form -->
                        <div class="row">
                          <div class="col-md-6">
                            <label for="inputName5" class="form-label">Nama Pasien</label>
                            <input type="text" class="form-control" name="pasien" id="inputName5" value="<?php echo $pasien['nama_lengkap']?>" placeholder="Masukkan Nama Pasien" readonly>
                          </div>
                          <div class="col-md-6" style="margin-bottom:20px;">
                            <label for="inputName5" class="form-label">No RM</label>
                            <input type="text" class="form-control" id="inputName5" name="jadwal" value="<?php echo $pasien['no_rm']?>" placeholder="Masukkan Nama Pasien" readonly>
                          </div>
                          <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                            <label for="inputName5" class="form-label">Tanggal Lahir</label>
                            <input type="text" class="form-control" id="inputName5" name="jadwal" value="<?php echo date("d-m-Y", strtotime($pasien['tgl_lahir']))?>" placeholder="Masukkan Nama Pasien" readonly>
                          </div>
                          <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                            <label for="inputName5" class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="inputName5" name="jadwal" value="<?php echo $pasien['alamat']?>" placeholder="Masukkan Nama Pasien" readonly>
                          </div>
                          <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                            <label for="inputName5" class="form-label">Ruangan</label>
                            <input type="text" class="form-control" readonly id="inputName5" name="kamar" value="<?php echo $jadwal['kamar']?>" placeholder="Masukkan Nama Pasien">
                          </div> <?php if($pasien["jenis_kelamin"] == 1){ ?> <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                            <label for="inputName5" class="form-label">Jenis Kelamin</label>
                            <input type="text" class="form-control" id="inputName5" name="jadwal" value="Laki-laki" placeholder="Masukkan Nama Pasien" readonly>
                          </div> <?php }else{ ?> <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                            <label for="inputName5" class="form-label">Jenis Kelamin</label>
                            <input type="text" class="form-control" id="inputName5" name="jadwal" value="Perempuan" placeholder="Masukkan Nama Pasien" readonly>
                          </div> <?php } ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <br>
                <div class="card shadow p-3">
                    <h5 class="card-title">ANTROPOMETRI DEWASA</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">BB (kg)</label>
                            <input type="text" class="form-control mb-3" name="bb_d" id="" value="" placeholder="BB (kg)"> 
                        </div>
                        <div class="col-md-6">
                            <label for="">TL (cm)</label>
                            <input type="text" class="form-control mb-3" name="tl_d" id="" value="" placeholder="TL (cm)"> 
                        </div>
                        <div class="col-md-6">
                            <label for="">TB (cm)</label>
                            <input type="text" class="form-control mb-3" name="tb_d" id="" value="" placeholder="TB (cm)"> 
                        </div>
                        <div class="col-md-6">
                            <label for="">LILA (cm)</label>
                            <input type="text" class="form-control mb-3" name="lila_d" id="" value="" placeholder="LILA (cm)"> 
                        </div>
                        <div class="col-md-6">
                            <label for="">BBI (kg)</label>
                            <input type="text" class="form-control mb-3" name="bbi_d" id="" value="" placeholder="BBI (kg)"> 
                        </div>
                        <div class="col-md-6">
                            <label for="">%LILA (%)</label>
                            <input type="text" class="form-control mb-3" name="lila_per_d" id="" value="" placeholder="%LILA (%)"> 
                        </div>
                        <div class="col-md-6">
                            <label for="">IMT (kg/m)</label>
                            <input type="text" class="form-control mb-3" name="imt_d" id="" value="" placeholder="IMT (kg/m)"> 
                        </div>
                        <div class="col-md-6">
                            <label for="">Status Gizi</label>
                            <select name="status_gizi_d" id="" class="form-control">
                                <option hidden>Pilih Status Gizi</option>
                                <option value="Buruk/Malnutrisi">Buruk/Malnutrisi</option>
                                <option value="Sangat Kurus">Sangat Kurus</option>
                                <option value="Kurus">Kurus</option>
                                <option value="Normal">Normal</option>
                                <option value="Lebih">Lebih</option>
                            </select>
                        </div>    
                    </div>
                    <br>
                    <h5 class="card-title">ANTROPOMETRI ANAK</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">BB (kg)</label>
                            <input type="text" class="form-control mb-3" name="bb_a" id="" placeholder="BB (kg)">
                        </div>
                        <div class="col-md-6">
                            <label for="">TB (cm)</label>
                            <input type="text" class="form-control mb-3" name="tb_a" id="" placeholder="TB (cm)">
                        </div>
                        <div class="col-md-6">
                            <label for="">BB/U</label>
                            <input type="text" class="form-control mb-3" name="bbu_a" id="" placeholder="BB/U">
                        </div>
                        <div class="col-md-6">
                            <label for="">TB/U atau PB/U</label>
                            <input type="text" class="form-control mb-3" name="tbu_a" id="" placeholder="TB/U atau PB/U">
                        </div>
                        <div class="col-md-6">
                            <label for="">IMT</label>
                            <input type="text" class="form-control mb-3" name="imt_a" id="" placeholder="IMT">
                        </div>
                        <div class="col-md-6">
                            <label for="">BBI</label>
                            <input type="text" class="form-control mb-3" name="bbi_a" id="" placeholder="BBI">
                        </div>
                        <div class="col-md-6">
                            <label for="">BBI (%)</label>
                            <input type="text" class="form-control mb-3" name="bbi_per_a" id="" placeholder="BBI (%)">
                        </div>
                        <div class="col-md-6">
                            <label for="">LLA (cm)</label>
                            <input type="text" class="form-control mb-3" name="lla_a" id="" placeholder="LLA (cm)">
                        </div>
                        <div class="col-md-6">
                            <label for="">LLA/U</label>
                            <input type="text" class="form-control mb-3" name="llau_a" id="" placeholder="LLA/U">
                        </div>
                        <div class="col-md-6">
                            <label for="">Status Gizi</label>
                            <select name="status_gizi_a" class="form-control mb-3" id="">
                                <option hidden>Pilih Status Gizi</option>
                                <option value="Buruk/Malnutrisi">Buruk/Malnutrisi</option>
                                <option value="Sangat Kurus">Sangat Kurus</option>
                                <option value="Kurus">Kurus</option>
                                <option value="Normal">Normal</option>
                                <option value="Lebih">Lebih</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="">Biokimia</label>
                            <textarea name="biokimia" id="" class="form-control w-100"></textarea>
                        </div>
                    </div>
                    <br>
                    <h5 class="card-title">FISIK KLINIS</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Suhu</label>
                            <input type="text" class="form-control mb-3" name="suhu" id="" value="" placeholder="Suhu"> 
                        </div>
                        <div class="col-md-6">
                            <label for="">Tekanan Darah</label>
                            <input type="text" class="form-control mb-3" name="tekanan_darah" id="" value="" placeholder="Tekanan Darah"> 
                        </div>
                        <div class="col-md-6">
                            <label for="">Nadi</label>
                            <input type="text" class="form-control mb-3" name="nadi" id="" value="" placeholder="Nadi"> 
                        </div>
                        <div class="col-md-6">
                            <label for="">RR</label>
                            <input type="text" class="form-control mb-3" name="rr" id="" value="" placeholder="RR"> 
                        </div>
                        <div class="col-md-6">
                            <label for="">K/U</label>
                            <input type="text" class="form-control mb-3" name="ku" id="" value="" placeholder="K/U"> 
                        </div>
                    </div>
                    <br>
                    <h5 class="card-title">RIWAYAT GIZI (DIETARY)</h5>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">Alergi Makanan</label>
                            <textarea name="alergi_makanan" id="" class="form-control w-100 mb-3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="">Pola Makan (x Sehari)</label>
                            <input type="text" class="form-control mb-3" name="pola_makan" id="" value="" placeholder="Pola Makan (x Sehari)"> 
                        </div>
                        <div class="col-md-6">
                            <label for="">Asupan</label>
                            <select name="asupan" id="" class="form-control">
                                <option hidden>Pilih % Asupan</option>
                                <option value="0%">0%</option>
                                <option value="25%">25%</option>
                                <option value="50%">50%</option>
                                <option value="75%">75%</option>
                                <option value="100%">100%</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="">Nafsu Makan</label>
                            <select name="nafsu_makan" id="" class="form-control">
                                <option hidden>Pilih Nafsu Makan</option>
                                <option value="Baik">Baik</option>
                                <option value="Menurun">Menurun</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <h5 class="card-title">RIWAYAT PERSONAL</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">DM</label>
                            <input type="text" class="form-control mb-3" name="dm" id="" value="" placeholder="DM"> 
                        </div>
                        <div class="col-md-6">
                            <label for="">HT</label>
                            <input type="text" class="form-control mb-3" name="ht" id="" value="" placeholder="HT"> 
                        </div>
                        <div class="col-md-6">
                            <label for="">Stroke</label>
                            <input type="text" class="form-control mb-3" name="stroke" id="" value="" placeholder="Stroke"> 
                        </div>
                        <div class="col-md-6">
                            <label for="">Jantung</label>
                            <input type="text" class="form-control mb-3" name="jantung" id="" value="" placeholder="Jantung"> 
                        </div>
                        <div class="col-md-6">
                            <label for="">Lain-lain</label>
                            <select name="lain_lain" id="" class="form-control">
                                <option hidden>Lain Lain</option>
                                <option value="Merokok">Merokok</option>
                                <option value="Minum-minuman Beralkohol">Minum-minuman Beralkohol</option>
                                <option value="OR">OR</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <h5 class="card-title">DIAGNOSA GIZI</h5>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">Diagnosa Gizi</label>
                            <select  class="form-control w-100" id="selUser">
                                <option hidden>Pilih Diagnosa Gizi</option>
                                <option value="NO-1.1 Tidak ada diagnosa gizi saat ini">NO-1.1 Tidak ada diagnosa gizi saat ini</option>
                                <option value="NI-2.1 Kekurangan intake makanan dan minuman oral terkait dengan faktor fisiologis yang ditandaii dengan intake kurang/ penurunan berat badan/ anorexia/ lemah/ nyeri perut/ perut kembung/ pusing/ TD rendah/ __________">NI-2.1 Kekurangan intake makanan dan minuman oral terkait dengan faktor fisiologis yang ditandaii dengan intake kurang/ penurunan berat badan/ anorexia/ lemah/ nyeri perut/ perut kembung/ pusing/ TD rendah/ __________</option>
                                <option value="NI-3.1 Asupan cairan tidak adekuat terkait dengan diare/ mual/ muntah/ hipertermi yang ditandai dengan keseimbangan cairan negatif/ demam / BAB cair/ lemah / ___________">NI-3.1 Asupan cairan tidak adekuat terkait dengan diare/ mual/ muntah/ hipertermi yang ditandai dengan keseimbangan cairan negatif/ demam / BAB cair/ lemah / ___________</option>
                                <option value="NI-5.1 Peningkatan kebutuhan energi/ protein/ lemak/ karbohidrat/ cairan _________ terkait infeksi/ hipermatabolisme/ kondisi fisiologis pasien/ hipertermi___________ yang ditandai dengan leukositosis/ demam/ widal (+) / trombosit turun / __________">NI-5.1 Peningkatan kebutuhan energi/ protein/ lemak/ karbohidrat/ cairan _________ terkait infeksi/ hipermatabolisme/ kondisi fisiologis pasien/ hipertermi___________ yang ditandai dengan leukositosis/ demam/ widal (+) / trombosit turun / __________</option>
                                <option value="NI-5.4 Penurunan kebutuhan natrium/ kolesterol/ purin/ serat/ KH / Glukosa __________ terkait dengan Hipertensi/ perubahan metabolisme/ disfungsi ginjal/ disfungsi hati/ sesak nafas/ DM/ diare / ___________ yang ditandai dengan TD tinggi/ Kolesterol tinggi/ Asam urat tinggi/ diare/ SPO kurang/ GDA tinggi / BAB cair/ lemah / ____________">NI-5.4 Penurunan kebutuhan natrium/ kolesterol/ purin/ serat/ KH / Glukosa __________ terkait dengan Hipertensi/ perubahan metabolisme/ disfungsi ginjal/ disfungsi hati/ sesak nafas/ DM/ diare / ___________ yang ditandai dengan TD tinggi/ Kolesterol tinggi/ Asam urat tinggi/ diare/ SPO kurang/ GDA tinggi / BAB cair/ lemah / ____________</option>
                                <option value="NI- 5.10.1 Kekurangan intake mineral Fe terkait dengan faktor fisiologis/ kurang pengetahuan tentang makanan tinggi Fe yang ditandai dengan Hb rendah">NI- 5.10.1 Kekurangan intake mineral Fe terkait dengan faktor fisiologis/ kurang pengetahuan tentang makanan tinggi Fe yang ditandai dengan Hb rendah</option>
                                <option value="NI-5.8.3 Konsumsi jenis Karbohidrat tidak tepat terkait dengan pengetahuan dalam pemilihan makanan/ tidak mau mengubah pola makan yang ditandai dengan hipogilkemia/ hiperglikemia">NI-5.8.3 Konsumsi jenis Karbohidrat tidak tepat terkait dengan pengetahuan dalam pemilihan makanan/ tidak mau mengubah pola makan yang ditandai dengan hipogilkemia/ hiperglikemia</option>
                                <option value="NC-2.2 Perubahan nilai laboratorium gula darah/ leukosit/ widal/ trombosit/___________ terkait dengan gangguan fungsi endokrin/ infeksi/ riwayat pola makan yang salah______________ ditandai dengan GDA (tinggi / rendah) / Leukosit tinggi/ Widal (+) / trombosit turun/ __________">NC-2.2 Perubahan nilai laboratorium gula darah/ leukosit/ widal/ trombosit/___________ terkait dengan gangguan fungsi endokrin/ infeksi/ riwayat pola makan yang salah______________ ditandai dengan GDA (tinggi / rendah) / Leukosit tinggi/ Widal (+) / trombosit turun/ __________</option>
                                <option value="NC-3.2 Penurunan berat badan yang tidak diharapkan terkait dengan kondisi fisiologis/ sosial/ kurang kemampuan makan/___________ yang ditandai dengan demam/ intake kurang menjelang sakit/ lemah__________">NC-3.2 Penurunan berat badan yang tidak diharapkan terkait dengan kondisi fisiologis/ sosial/ kurang kemampuan makan/___________ yang ditandai dengan demam/ intake kurang menjelang sakit/ lemah__________</option>
                                <option value="NB-1.1 Pengetahuan yang kurang dikatikan dengan pangan dan gizi terkait dengan keyakinan/ budaya/___________ yang ditandai dengan kondisi pasien/ ketidakinginan mendapat informasi gizi/______________">NB-1.1 Pengetahuan yang kurang dikatikan dengan pangan dan gizi terkait dengan keyakinan/ budaya/___________ yang ditandai dengan kondisi pasien/ ketidakinginan mendapat informasi gizi/______________</option>
                                <option value="NB-1.3 Belum siap melakuakan diet terkait dengan keyakinan/ kurangnya kemauan/ penolakan/___________ yang ditandai dengan bahasa tubuh menolak/ kurangnya kemauan untuk mengubah kebiasaan/_______________">NB-1.3 Belum siap melakuakan diet terkait dengan keyakinan/ kurangnya kemauan/ penolakan/___________ yang ditandai dengan bahasa tubuh menolak/ kurangnya kemauan untuk mengubah kebiasaan/_______________</option>
                                <option value="NB-1.5 Kekeliruan pola makan terkait dengan pengetahuan yang salah tentang makanan sehat/ kesesuaian diet penyakit ditandai dengan intake makan berlebih/ kurang">NB-1.5 Kekeliruan pola makan terkait dengan pengetahuan yang salah tentang makanan sehat/ kesesuaian diet penyakit ditandai dengan intake makan berlebih/ kurang</option>
                            </select>
                            <textarea name="diagnosa_gizi" id="diag" class="form-control"></textarea>
                            <script type="text/javascript">
                                $(document).ready(function(){
                                    // Initialize select2
                                    $("#selUser").select2();
                                    $("#selUser").on("change", function() {
                                        // Ambil teks dari elemen select yang dipilih
                                        var selectedText = $("#selUser option:selected").text();
                                        
                                        // Ambil teks yang sudah ada di dalam textarea
                                        var currentText = $("#diag").val();
                                        
                                        // Gabungkan teks yang sudah ada dengan teks baru dan tambahkan pemisah jika diperlukan
                                        var newText = currentText + (currentText ? ', ' : '') + selectedText;
                                        
                                        // Tampilkan teks yang baru pada textarea dengan id "icds"
                                        $("#diag").val(newText);
                                    });
                                });
                            </script>
                        </div>
                        <br><br><br>
                        <span><b>Kebutuhan Gizi</b></span>
                        <div class="col-md-6">
                            <label for="">Energi</label>
                            <input type="text" class="form-control mb-3" name="energi" id="" value="" placeholder="Energi">
                        </div>
                        <div class="col-md-6">
                            <label for="">Protein</label>
                            <input type="text" class="form-control mb-3" name="protein" id="" value="" placeholder="Protein">
                        </div>
                        <div class="col-md-6">
                            <label for="">KH</label>
                            <input type="text" class="form-control mb-3" name="kh" id="" value="" placeholder="KH">
                        </div>
                        <div class="col-md-6">
                            <label for="">Lemak</label>
                            <input type="text" class="form-control mb-3" name="lemak" id="" value="" placeholder="Lemak">
                        </div>
                        <div class="col-md-12">
                            <label for="">Prinsip Diet</label>
                            <textarea name="prinsip_diet" id="" class="form-control w-100 mb-3"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="">Konsistensi Diet</label>
                            <input type="text" class="form-control w-100 mb-3" name="konsistensi_diet" id="">
                        </div>
                        <div class="col-md-12">
                            <label for="">Edukasi Gizi</label>
                            <textarea name="edukasi_gizi" id="" class="form-control w-100 mb-3"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="">Rencana Monev</label>
                            <textarea name="rencana_monev" id="" class="form-control w-100 mb-3"></textarea>
                        </div>
                        <!-- <button class="btn btn-primary" name="save">Simpan</button> -->
                        <div class="text-center" style="margin-top: 10px; margin-bottom: 40px;">
                                    <button type="submit" name="save" class="btn btn-primary">Simpan</button>
                                    <button type="reset" class="btn btn-secondary">Reset</button>
                                    </div>
                    </div>
                </div>
                <div class="card shadow p-3">
                    <h5 class="card-title">Riwayat Asuahan Gizi</h5>
                    <div class="row">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Pasien</th>
                                    <th>No RM</th>
                                    <th>Tgl Pengisian</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $getAsuhan = $koneksi->query("SELECT * FROM asuhan_gizi WHERE pasien='$pasien[nama_lengkap]' AND norm = '$pasien[no_rm]'");
                                    foreach ($getAsuhan as $data) {
                                ?>
                                    <tr>
                                        <td><?=$data['pasien']?></td>
                                        <td><?=$data['norm']?></td>
                                        <td><?=$data['created_at']?></td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="index.php?halaman=asuhangizi&id=<?= $_GET['id']?>&inap&tgl=<?= $_GET['tgl']?>&view=<?= $data['id_asuhan']?>"><i class="bi bi-eye"></i></a>
                                        </td>
                                    </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
              </div>
            </form>
            <?php
                if(isset($_POST['save'])){
                    $koneksi->query("INSERT INTO asuhan_gizi (pasien, norm, tgl_lahir, alamat, ruangan, jenis_kelamin, bb_d, tl_d, tb_d, lila_d, bbi_d, lila_per_d, imt_d, status_gizi_d, bb_a, tb_a, bbu_a, tbu_a, imt_a, bbi_a, bbi_per_a, lla_a, llau_a, status_gizi_a, biokimia, suhu, tekanan_darah, nadi, rr, ku, alergi_makanan, pola_makan, asupan, nafsu_makan, dm, ht, stroke, jantung, lain_lain, diagnosa_gizi, energi, protein, kh, lemak, prinsip_diet, konsistensi_diet, edukasi_gizi, rencana_monev) VALUES ('$pasien[nama_lengkap]', '$pasien[no_rm]', '$pasien[tgl_lahir]', '$pasien[alamat]', '$jadwal[kamar]', '$pasien[jenis_kelamin]', '$_POST[bb_d]', '$_POST[tl_d]', '$_POST[tb_d]', '$_POST[lila_d]', '$_POST[bbi_d]', '$_POST[lila_per_d]', '$_POST[imt_d]', '$_POST[status_gizi_d]', '$_POST[bb_a]', '$_POST[tb_a]', '$_POST[bbu_a]', '$_POST[tbu_a]', '$_POST[imt_a]', '$_POST[bbi_a]', '$_POST[bbi_per_a]', '$_POST[lla_a]', '$_POST[llau_a]', '$_POST[status_gizi_a]', '$_POST[biokimia]', '$_POST[suhu]', '$_POST[tekanan_darah]', '$_POST[nadi]', '$_POST[rr]', '$_POST[ku]', '$_POST[alergi_makanan]', '$_POST[pola_makan]', '$_POST[asupan]', '$_POST[nafsu_makan]', '$_POST[dm]', '$_POST[ht]', '$_POST[stroke]', '$_POST[jantung]', '$_POST[lain_lain]', '$_POST[diagnosa_gizi]', '$_POST[energi]', '$_POST[protein]', '$_POST[kh]', '$_POST[lemak]', '$_POST[prinsip_diet]', '$_POST[konsistensi_diet]', '$_POST[edukasi_gizi]', '$_POST[rencana_monev]')");
    
                    echo "
                        <script>
                            alert('Berhasil Menambah Asuhan Gizi');
                            document.location.href='index.php?halaman=asuhangizi&id=$_GET[id]&inap&tgl=$_GET[tgl]';
                        </script>
                    ";
                }
            ?>
          </div>
        </main>
      </body>
    </html>
<?php }else{?>
    <?php $asuhan = $koneksi->query("SELECT * FROM asuhan_gizi WHERE id_asuhan = '$_GET[view]'")->fetch_assoc();?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        </head>
        <body>
        <main>
            <div class="container">
            <div class="pagetitle">
                <h1>Asuhan Gizi Pasien Rawat Inap</h1>
                <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">
                    <a href="index.php?halaman=daftarrmedis" style="color:blue;">Rekam Medis</a>
                    </li>
                    <li class="breadcrumb-item">Asuhan Rawat Inap</li>
                </ol>
                </nav>
            </div>
            <!-- End Page Title -->
            <form readonly class="row g-3" method="post" enctype="multipart/form-data">
                <div class="container">
                <div class="row">
                    <div class="col-md-12">
                    <a href="index.php?halaman=asuhangizi&id=<?= $_GET['id']?>&inap&tgl=<?= $_GET['tgl']?>" class="btn btn-dark">Kembali</a>
                    <div class="card" style="margin-top:10px">
                        <div class="card-body col-md-12">
                        <h5 class="card-title">Data Pasien</h5>
                        <!-- Multi Columns Form -->
                        <div class="row">
                            <div class="col-md-6">
                            <label for="inputName5" class="form-label">Nama Pasien</label>
                            <input type="text" class="form-control" name="pasien" id="inputName5" value="<?php echo $pasien['nama_lengkap']?>" placeholder="Masukkan Nama Pasien" readonly>
                            </div>
                            <div class="col-md-6" style="margin-bottom:20px;">
                            <label for="inputName5" class="form-label">No RM</label>
                            <input type="text" class="form-control" id="inputName5" name="jadwal" value="<?php echo $pasien['no_rm']?>" placeholder="Masukkan Nama Pasien" readonly>
                            </div>
                            <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                            <label for="inputName5" class="form-label">Tanggal Lahir</label>
                            <input type="text" class="form-control" id="inputName5" name="jadwal" value="<?php echo date("d-m-Y", strtotime($pasien['tgl_lahir']))?>" placeholder="Masukkan Nama Pasien" readonly>
                            </div>
                            <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                            <label for="inputName5" class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="inputName5" name="jadwal" value="<?php echo $pasien['alamat']?>" placeholder="Masukkan Nama Pasien" readonly>
                            </div>
                            <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                            <label for="inputName5" class="form-label">Ruangan</label>
                            <input type="text" class="form-control" readonly id="inputName5" name="kamar" value="<?php echo $jadwal['kamar']?>" placeholder="Masukkan Nama Pasien">
                            </div> <?php if($pasien["jenis_kelamin"] == 1){ ?> <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                            <label for="inputName5" class="form-label">Jenis Kelamin</label>
                            <input type="text" class="form-control" id="inputName5" name="jadwal" value="Laki-laki" placeholder="Masukkan Nama Pasien" readonly>
                            </div> <?php }else{ ?> <div class="col-md-6" style="margin-top: 10px; margin-bottom:20px;">
                            <label for="inputName5" class="form-label">Jenis Kelamin</label>
                            <input type="text" class="form-control" id="inputName5" name="jadwal" value="Perempuan" placeholder="Masukkan Nama Pasien" readonly>
                            </div> <?php } ?>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                <br>
                <div class="card shadow p-3">
                    <h5 class="card-title">ANTROPOMETRI DEWASA</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">BB (kg)</label>
                            <input type="text" class="form-control mb-3" name="bb_d" id="" readonly value="<?= $asuhan['bb_d']?>" readonly placeholder="BB (kg)"> 
                        </div>
                        <div class="col-md-6">
                            <label for="">TL (cm)</label>
                            <input type="text" class="form-control mb-3" name="tl_d" id="" readonly value="<?= $asuhan['tl_d']?>" readonly placeholder="TL (cm)"> 
                        </div>
                        <div class="col-md-6">
                            <label for="">TB (cm)</label>
                            <input type="text" class="form-control mb-3" name="tb_d" id="" readonly value="<?= $asuhan['tb_d']?>" readonly placeholder="TB (cm)"> 
                        </div>
                        <div class="col-md-6">
                            <label for="">LILA (cm)</label>
                            <input type="text" class="form-control mb-3" name="lila_d" id="" readonly value="<?= $asuhan['lila_d']?>" readonly placeholder="LILA (cm)"> 
                        </div>
                        <div class="col-md-6">
                            <label for="">BBI (kg)</label>
                            <input type="text" class="form-control mb-3" name="bbi_d" id="" readonly value="<?= $asuhan['bbi_d']?>" readonly placeholder="BBI (kg)"> 
                        </div>
                        <div class="col-md-6">
                            <label for="">%LILA (%)</label>
                            <input type="text" class="form-control mb-3" name="lila_per_d" id="" readonly value="<?= $asuhan['lila_per_d']?>" readonly placeholder="%LILA (%)"> 
                        </div>
                        <div class="col-md-6">
                            <label for="">IMT (kg/m)</label>
                            <input type="text" class="form-control mb-3" name="imt_d" id="" readonly value="<?= $asuhan['imt_d']?>" readonly placeholder="IMT (kg/m)"> 
                        </div>
                        <div class="col-md-6">
                            <label for="">Status Gizi</label>
                            <select disabled name="status_gizi_d" id="" class="form-control">
                                <option hidden><?=  $asuhan['status_gizi_d']?></option>
                                <option value="Buruk/Malnutrisi">Buruk/Malnutrisi</option>
                                <option value="Sangat Kurus">Sangat Kurus</option>
                                <option value="Kurus">Kurus</option>
                                <option value="Normal">Normal</option>
                                <option value="Lebih">Lebih</option>
                            </select>
                        </div>    
                    </div>
                    <br>
                    <h5 class="card-title">ANTROPOMETRI ANAK</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">BB (kg)</label>
                            <input type="text" class="form-control mb-3" name="bb_a" readonly value="<?= $asuhan['bb_a']?>" readonly id="" placeholder="BB (kg)">
                        </div>
                        <div class="col-md-6">
                            <label for="">TB (cm)</label>
                            <input type="text" class="form-control mb-3" name="tb_a" readonly value="<?= $asuhan['tb_a']?>" readonly id="" placeholder="TB (cm)">
                        </div>
                        <div class="col-md-6">
                            <label for="">BB/U</label>
                            <input type="text" class="form-control mb-3" name="bbu_a" readonly value="<?= $asuhan['bbu_a']?>" readonly id="" placeholder="BB/U">
                        </div>
                        <div class="col-md-6">
                            <label for="">TB/U atau PB/U</label>
                            <input type="text" class="form-control mb-3" name="tbu_a" readonly value="<?= $asuhan['tbu_a']?>" readonly id="" placeholder="TB/U atau PB/U">
                        </div>
                        <div class="col-md-6">
                            <label for="">IMT</label>
                            <input type="text" class="form-control mb-3" name="imt_a" readonly value="<?= $asuhan['imt_a']?>" readonly id="" placeholder="IMT">
                        </div>
                        <div class="col-md-6">
                            <label for="">BBI</label>
                            <input type="text" class="form-control mb-3" name="bbi_a" readonly value="<?= $asuhan['bbi_a']?>" readonly id="" placeholder="BBI">
                        </div>
                        <div class="col-md-6">
                            <label for="">BBI (%)</label>
                            <input type="text" class="form-control mb-3" name="bbi_per_a" readonly value="<?= $asuhan['bbi_per_a']?>" readonly id="" placeholder="BBI (%)">
                        </div>
                        <div class="col-md-6">
                            <label for="">LLA (cm)</label>
                            <input type="text" class="form-control mb-3" name="lla_a" readonly value="<?= $asuhan['lla_a']?>" readonly id="" placeholder="LLA (cm)">
                        </div>
                        <div class="col-md-6">
                            <label for="">LLA/U</label>
                            <input type="text" class="form-control mb-3" name="llau_a" readonly value="<?= $asuhan['llau_a']?>" readonly id="" placeholder="LLA/U">
                        </div>
                        <div class="col-md-6">
                            <label for="">Status Gizi</label>
                            <select disabled name="status_gizi_a" class="form-control mb-3" id="">
                                <option hidden><?= $asuhan['status_gizi_a']?></option>
                                <option value="Buruk/Malnutrisi">Buruk/Malnutrisi</option>
                                <option value="Sangat Kurus">Sangat Kurus</option>
                                <option value="Kurus">Kurus</option>
                                <option value="Normal">Normal</option>
                                <option value="Lebih">Lebih</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="">Biokimia</label>
                            <textarea name="biokimia" id="" readonly class="form-control w-100"><?= $asuhan['biokimia']?></textarea>
                        </div>
                    </div>
                    <br>
                    <h5 class="card-title">FISIK KLINIS</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Suhu</label>
                            <input type="text" class="form-control mb-3" name="suhu" id="" readonly value="<?= $asuhan['suhu']?>" placeholder="Suhu"> 
                        </div>
                        <div class="col-md-6">
                            <label for="">Tekanan Darah</label>
                            <input type="text" class="form-control mb-3" name="tekanan_darah" id="" readonly value="<?= $asuhan['tekanan_darah']?>" placeholder="Tekanan Darah"> 
                        </div>
                        <div class="col-md-6">
                            <label for="">Nadi</label>
                            <input type="text" class="form-control mb-3" name="nadi" id="" readonly value="<?= $asuhan['nadi']?>" placeholder="Nadi"> 
                        </div>
                        <div class="col-md-6">
                            <label for="">RR</label>
                            <input type="text" class="form-control mb-3" name="rr" id="" readonly value="<?= $asuhan['rr']?>" placeholder="RR"> 
                        </div>
                        <div class="col-md-6">
                            <label for="">K/U</label>
                            <input type="text" class="form-control mb-3" name="ku" id="" readonly value="<?= $asuhan['ku']?>" placeholder="K/U"> 
                        </div>
                    </div>
                    <br>
                    <h5 class="card-title">RIWAYAT GIZI (DIETARY)</h5>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">Alergi Makanan</label>
                            <textarea name="alergi_makanan" id="" readonly class="form-control w-100 mb-3"><?= $asuhan['alergi_makanan']?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="">Pola Makan (x Sehari)</label>
                            <input type="text" class="form-control mb-3" name="pola_makan" id="" readonly value="<?= $asuhan['pola_makan']?>" readonly placeholder="Pola Makan (x Sehari)"> 
                        </div>
                        <div class="col-md-6">
                            <label for="">Asupan</label>
                            <select name="asupan" id="" class="form-control" disabled>
                                <option hidden><?= $asuhan['asupan']?></option>
                                <option value="0%">0%</option>
                                <option value="25%">25%</option>
                                <option value="50%">50%</option>
                                <option value="75%">75%</option>
                                <option value="100%">100%</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="">Nafsu Makan</label>
                            <select name="nafsu_makan" id="" class="form-control" disabled>
                                <option hidden><?= $asuhan['nafsu_makan']?></option>
                                <option value="Baik">Baik</option>
                                <option value="Menurun">Menurun</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <h5 class="card-title">RIWAYAT PERSONAL</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">DM</label>
                            <input type="text" class="form-control mb-3" name="dm"  id="" readonly value="<?= $asuhan['dm']?>" placeholder="DM"> 
                        </div>
                        <div class="col-md-6">
                            <label for="">HT</label>
                            <input type="text" class="form-control mb-3" name="ht"  id="" readonly value="<?= $asuhan['ht']?>" placeholder="HT"> 
                        </div>
                        <div class="col-md-6">
                            <label for="">Stroke</label>
                            <input type="text" class="form-control mb-3" name="stroke"  id="" readonly value="<?= $asuhan['stroke']?>" placeholder="Stroke"> 
                        </div>
                        <div class="col-md-6">
                            <label for="">Jantung</label>
                            <input type="text" class="form-control mb-3" name="jantung"  id="" readonly value="<?= $asuhan['jantung']?>" placeholder="Jantung"> 
                        </div>
                        <div class="col-md-6">
                            <label for="">Lain-lain</label>
                            <select disabled name="lain_lain" id="" class="form-control">
                                <option hidden><?= $asuhan['lain_lain']?></option>
                                <option value="Merokok">Merokok</option>
                                <option value="Minum-minuman Beralkohol">Minum-minuman Beralkohol</option>
                                <option value="OR">OR</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <h5 class="card-title">DIAGNOSA GIZI</h5>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">Diagnosa Gizi</label>
                            <textarea name="" class="form-control" disabled id="" rows="8">
                                <?= $asuhan['diagnosa_gizi']?>
                            </textarea>
                            <!-- <select disabled name="diagnosa_gizi" class="form-control w-100" id="">
                                <option hidden><?= $asuhan['diagnosa_gizi']?></option>
                                <option value="NO-1.1 Tidak ada diagnosa gizi saat ini">NO-1.1 Tidak ada diagnosa gizi saat ini</option>
                                <option value="NI-2.1 Kekurangan intake makanan dan minuman oral terkait dengan faktor fisiologis yang ditandaii dengan intake kurang/ penurunan berat badan/ anorexia/ lemah/ nyeri perut/ perut kembung/ pusing/ TD rendah/ __________">NI-2.1 Kekurangan intake makanan dan minuman oral terkait dengan faktor fisiologis yang ditandaii dengan intake kurang/ penurunan berat badan/ anorexia/ lemah/ nyeri perut/ perut kembung/ pusing/ TD rendah/ __________</option>
                                <option value="NI-3.1 Asupan cairan tidak adekuat terkait dengan diare/ mual/ muntah/ hipertermi yang ditandai dengan keseimbangan cairan negatif/ demam / BAB cair/ lemah / ___________">NI-3.1 Asupan cairan tidak adekuat terkait dengan diare/ mual/ muntah/ hipertermi yang ditandai dengan keseimbangan cairan negatif/ demam / BAB cair/ lemah / ___________</option>
                                <option value="NI-5.1 Peningkatan kebutuhan energi/ protein/ lemak/ karbohidrat/ cairan _________ terkait infeksi/ hipermatabolisme/ kondisi fisiologis pasien/ hipertermi___________ yang ditandai dengan leukositosis/ demam/ widal (+) / trombosit turun / __________">NI-5.1 Peningkatan kebutuhan energi/ protein/ lemak/ karbohidrat/ cairan _________ terkait infeksi/ hipermatabolisme/ kondisi fisiologis pasien/ hipertermi___________ yang ditandai dengan leukositosis/ demam/ widal (+) / trombosit turun / __________</option>
                                <option value="NI-5.4 Penurunan kebutuhan natrium/ kolesterol/ purin/ serat/ KH / Glukosa __________ terkait dengan Hipertensi/ perubahan metabolisme/ disfungsi ginjal/ disfungsi hati/ sesak nafas/ DM/ diare / ___________ yang ditandai dengan TD tinggi/ Kolesterol tinggi/ Asam urat tinggi/ diare/ SPO kurang/ GDA tinggi / BAB cair/ lemah / ____________">NI-5.4 Penurunan kebutuhan natrium/ kolesterol/ purin/ serat/ KH / Glukosa __________ terkait dengan Hipertensi/ perubahan metabolisme/ disfungsi ginjal/ disfungsi hati/ sesak nafas/ DM/ diare / ___________ yang ditandai dengan TD tinggi/ Kolesterol tinggi/ Asam urat tinggi/ diare/ SPO kurang/ GDA tinggi / BAB cair/ lemah / ____________</option>
                                <option value="NI- 5.10.1 Kekurangan intake mineral Fe terkait dengan faktor fisiologis/ kurang pengetahuan tentang makanan tinggi Fe yang ditandai dengan Hb rendah">NI- 5.10.1 Kekurangan intake mineral Fe terkait dengan faktor fisiologis/ kurang pengetahuan tentang makanan tinggi Fe yang ditandai dengan Hb rendah</option>
                                <option value="NI-5.8.3 Konsumsi jenis Karbohidrat tidak tepat terkait dengan pengetahuan dalam pemilihan makanan/ tidak mau mengubah pola makan yang ditandai dengan hipogilkemia/ hiperglikemia">NI-5.8.3 Konsumsi jenis Karbohidrat tidak tepat terkait dengan pengetahuan dalam pemilihan makanan/ tidak mau mengubah pola makan yang ditandai dengan hipogilkemia/ hiperglikemia</option>
                                <option value="NC-2.2 Perubahan nilai laboratorium gula darah/ leukosit/ widal/ trombosit/___________ terkait dengan gangguan fungsi endokrin/ infeksi/ riwayat pola makan yang salah______________ ditandai dengan GDA (tinggi / rendah) / Leukosit tinggi/ Widal (+) / trombosit turun/ __________">NC-2.2 Perubahan nilai laboratorium gula darah/ leukosit/ widal/ trombosit/___________ terkait dengan gangguan fungsi endokrin/ infeksi/ riwayat pola makan yang salah______________ ditandai dengan GDA (tinggi / rendah) / Leukosit tinggi/ Widal (+) / trombosit turun/ __________</option>
                                <option value="NC-3.2 Penurunan berat badan yang tidak diharapkan terkait dengan kondisi fisiologis/ sosial/ kurang kemampuan makan/___________ yang ditandai dengan demam/ intake kurang menjelang sakit/ lemah__________">NC-3.2 Penurunan berat badan yang tidak diharapkan terkait dengan kondisi fisiologis/ sosial/ kurang kemampuan makan/___________ yang ditandai dengan demam/ intake kurang menjelang sakit/ lemah__________</option>
                                <option value="NB-1.1 Pengetahuan yang kurang dikatikan dengan pangan dan gizi terkait dengan keyakinan/ budaya/___________ yang ditandai dengan kondisi pasien/ ketidakinginan mendapat informasi gizi/______________">NB-1.1 Pengetahuan yang kurang dikatikan dengan pangan dan gizi terkait dengan keyakinan/ budaya/___________ yang ditandai dengan kondisi pasien/ ketidakinginan mendapat informasi gizi/______________</option>
                                <option value="NB-1.3 Belum siap melakuakan diet terkait dengan keyakinan/ kurangnya kemauan/ penolakan/___________ yang ditandai dengan bahasa tubuh menolak/ kurangnya kemauan untuk mengubah kebiasaan/_______________">NB-1.3 Belum siap melakuakan diet terkait dengan keyakinan/ kurangnya kemauan/ penolakan/___________ yang ditandai dengan bahasa tubuh menolak/ kurangnya kemauan untuk mengubah kebiasaan/_______________</option>
                                <option value="NB-1.5 Kekeliruan pola makan terkait dengan pengetahuan yang salah tentang makanan sehat/ kesesuaian diet penyakit ditandai dengan intake makan berlebih/ kurang">NB-1.5 Kekeliruan pola makan terkait dengan pengetahuan yang salah tentang makanan sehat/ kesesuaian diet penyakit ditandai dengan intake makan berlebih/ kurang</option>
                            </select> -->
                        </div>
                        <br><br><br>
                        <span><b>Kebutuhan Gizi</b></span>
                        <div class="col-md-6">
                            <label for="">Energi</label>
                            <input type="text" class="form-control mb-3" name="energi" id="" readonly value="<?= $asuhan['energi']?>" placeholder="Energi">
                        </div>
                        <div class="col-md-6">
                            <label for="">Protein</label>
                            <input type="text" class="form-control mb-3" name="protein" id="" readonly value="<?= $asuhan['protein']?>" placeholder="Protein">
                        </div>
                        <div class="col-md-6">
                            <label for="">KH</label>
                            <input type="text" class="form-control mb-3" name="kh" id="" readonly value="<?= $asuhan['kh']?>" placeholder="KH">
                        </div>
                        <div class="col-md-6">
                            <label for="">Lemak</label>
                            <input type="text" class="form-control mb-3" name="lemak" id="" readonly value="<?= $asuhan['lemak']?>" placeholder="Lemak">
                        </div>
                        <div class="col-md-12">
                            <label for="">Prinsip Diet</label>
                            <textarea readonly name="prinsip_diet" id="" class="form-control w-100 mb-3"><?= $asuhan['prinsip_diet']?></textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="">Konsistensi Diet</label>
                            <input type="text" class="form-control w-100 mb-3" name="konsistensi_diet" readonly value="<?= $asuhan['konsistensi_diet']?>" id="">
                        </div>
                        <div class="col-md-12">
                            <label for="">Edukasi Gizi</label>
                            <textarea readonly name="edukasi_gizi" id="" class="form-control w-100 mb-3"><?= $asuhan['edukasi_gizi']?></textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="">Rencana Monev</label>
                            <textarea readonly name="rencana_monev" id="" class="form-control w-100 mb-3"><?= $asuhan['rencana_monev']?></textarea>
                        </div>
                    </div>
                </div>

                </div>
            </form>
            <?php
                if(isset($_POST['save'])){
                    $koneksi->query("INSERT INTO asuhan_gizi (pasien, norm, tgl_lahir, alamat, ruangan, jenis_kelamin, bb_d, tl_d, tb_d, lila_d, bbi_d, lila_per_d, imt_d, status_gizi_d, bb_a, tb_a, bbu_a, tbu_a, imt_a, bbi_a, bbi_per_a, lla_a, llau_a, status_gizi_a, biokimia, suhu, tekanan_darah, nadi, rr, ku, alergi_makanan, pola_makan, asupan, nafsu_makan, dm, ht, stroke, jantung, lain_lain, diagnosa_gizi, energi, protein, kh, lemak, prinsip_diet, konsistensi_diet, edukasi_gizi, rencana_monev) VALUES ('$pasien[nama_lengkap]', '$pasien[no_rm]', '$pasien[tgl_lahir]', '$pasien[alamat]', '$jadwal[kamar]', '$pasien[jenis_kelamin]', '$_POST[bb_d]', '$_POST[tl_d]', '$_POST[tb_d]', '$_POST[lila_d]', '$_POST[bbi_d]', '$_POST[lila_per_d]', '$_POST[imt_d]', '$_POST[status_gizi_d]', '$_POST[bb_a]', '$_POST[tb_a]', '$_POST[bbu_a]', '$_POST[tbu_a]', '$_POST[imt_a]', '$_POST[bbi_a]', '$_POST[bbi_per_a]', '$_POST[lla_a]', '$_POST[llau_a]', '$_POST[status_gizi_a]', '$_POST[biokimia]', '$_POST[suhu]', '$_POST[tekanan_darah]', '$_POST[nadi]', '$_POST[rr]', '$_POST[ku]', '$_POST[alergi_makanan]', '$_POST[pola_makan]', '$_POST[asupan]', '$_POST[nafsu_makan]', '$_POST[dm]', '$_POST[ht]', '$_POST[stroke]', '$_POST[jantung]', '$_POST[lain_lain]', '$_POST[diagnosa_gizi]', '$_POST[energi]', '$_POST[protein]', '$_POST[kh]', '$_POST[lemak]', '$_POST[prinsip_diet]', '$_POST[konsistensi_diet]', '$_POST[edukasi_gizi]', '$_POST[rencana_monev]')");
    
                    echo "
                        <script>
                            alert('Berhasil Menambah Asuhan Gizi');
                            document.location.href='index.php?halaman=asuhangizi&id=$_GET[id]&inap&tgl=$_GET[tgl]';
                        </script>
                    ";
                }
            ?>
            </div>
        </main>
        </body>
    </html>
<?php }?>

