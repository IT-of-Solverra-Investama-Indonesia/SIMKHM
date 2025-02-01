<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<h4>Tambah Obat Masuk</h4>
<a href="index.php?halaman=daftarapotek" class="btn btn-sm btn-dark mb-2" style="max-width: 100px;">Kembali</a>

<form method="POST">
    <div class="card shadow-sm p-2" style="border-radius: 15px;">
        <div class="row">
            <div class="col-md-12">
                <label for="">Pilih Obat :</label>
                <select required name="nama_obat" class="js-example-basic-single w-100" id="nama_obat_id">
                    <option value="" hidden>Pilih Obat / Tambah Baru</option>
                    <option value="Obat Baru">Tambah Obat Baru</option>
                    <?php
                        $getObat = $koneksi->query("SELECT * FROM apotek GROUP BY id_obat ORDER BY nama_obat ASC");
                        foreach($getObat as $obat){
                    ?>
                        <option value="<?= $obat['id_obat']?>"><?= $obat['id_obat']?> | <?= $obat['nama_obat']?></option>
                    <?php }?>
                </select>
                <script>
                    $(document).ready(function() {
                        $('.js-example-basic-single').select2();
                        $('.js-example-basic-singleee').select2();
                    
                        // Sembunyikan form input obat baru secara default
                        $('#nama_obat_new_id, #id_obat_id').hide();
                        $('#namasuplier_new_id, #nohp_new_id, #alamat_new_id').hide();
                    
                        // Event listener untuk perubahan pada dropdown
                        $('#nama_obat_id').on('change', function() {
                            if ($(this).val() === 'Obat Baru') {
                                // Tampilkan form input obat baru
                                $('#nama_obat_new_id, #id_obat_id').show();
                            } else {
                                // Sembunyikan form input obat baru
                                $('#nama_obat_new_id, #id_obat_id').hide();
                            }
                        });

                        $('#namasuplier_id').on('change', function() {
                            if ($(this).val() === 'Suplier Baru') {
                                // Tampilkan form input obat baru
                                $('#namasuplier_new_id, #nohp_new_id, #alamat_new_id').show();
                            } else {
                                // Sembunyikan form input obat baru
                                $('#namasuplier_new_id, #nohp_new_id, #alamat_new_id').hide();
                            }
                        });
                    });
                </script>
            </div>
            <div class="col-8">
                <input type="text" name="nama_obat_new" id="nama_obat_new_id" class="form-control mt-2 w-100" placeholder="Masukan Nama Obat Baru">
            </div>
            <div class="col-4">
                <input type="number" name="id_obat_new" id="id_obat_id" class="form-control mt-2 w-100" placeholder="Masukan Kode Obat Baru">
            </div>
            <div class="col-12">
                <label for="" class="mt-2">Pilih Suplier:</label>
                <select name="namasuplier" required class="js-example-basic-singleee w-100" id="namasuplier_id">
                    <option value="" hidden>Pilih Suplieer / Tambah Baru</option>
                    <option value="Suplier Baru">Tambah Suplieer Baru</option>
                    <?php
                        $getSuplier = $koneksi->query("SELECT * FROM suplier GROUP BY namasuplier ORDER BY namasuplier ASC");
                        foreach($getSuplier as $suplier){
                    ?>
                        <option value="<?= $suplier['namasuplier']?>"><?= $suplier['namasuplier']?> | <?= $suplier['nohp']?> </option>
                    <?php }?>
                </select>
            </div>
            <div class="col-md-5">
                <input type="text" name="namasuplier_new" id="namasuplier_new_id" class="form-control my-2" placeholder="Masukan Nama Suplier">
            </div>
            <div class="col-md-2">
                <input type="text" name="nohp_new" id="nohp_new_id" class="form-control my-2" placeholder="Nomor Suplier">
            </div>
            <div class="col-md-5">
                <input type="text" name="alamat_new" id="alamat_new_id" class="form-control my-2" placeholder="Alamat Suplier">
            </div>
            <div class="col-md-4" style="margin-top:10px;">
                <label for="inputName5" class="mb-0">Satuan </label>
                <select id="inputState" required name="bentuk" class="form-select">
                  <option value="">Pilih</option>
                  <option value="Box">Box</option>
                  <option value="Pcs">Pcs</option>
                  <option value="Strip">Strip</option>
                  <option value="Tablet">Tablet</option>
                  <option value="Kaplet">Kaplet</option>
                  <option value="Kapsul">Kapsul</option>
                  <option value="Pil">Pil</option>
                  <option value="Tube">Tube</option>
                  <option value="Botol">Botol</option>
                  <option value="ml">ml</option>
                  <option value="Tetes">Tetes</option>
                  <option value="Suspensi">Suspensi</option>
                  <option value="Emulsi">Emulsi</option>
                  <option value="Larutan">Larutan</option>
                  <option value="Puyer">Puyer</option>
                </select>
            </div>    
            <div class="col-md-4">
                <label for="inputName5" style="margin-top: 10px;" class="mb-0">Jumlah</label>
                <input oninput="hitungtotal()" type="text" required style="background: white;" class="form-control" name="jml_obat" id="jumlah" value="" placeholder="Masukkan Jumlah">
            </div>
            <div class="col-md-4">
                <label for="inputName5" style="margin-top: 10px;" class="mb-0">Tipe</label>
                <select name="tipe" id="" required class="form-control">
                    <option hidden>Pilih</option>
                    <option value="Rajal">Rajal</option>
                    <option value="Ranap">Ranap</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="" class="mb-0 mt-2">Tanggal Beli</label>
                <input type="date" required name="tgl_beli" id="" class="form-control mb-2">
            </div>
            <div class="col-md-6">
                <label for="" class="mb-0 mt-2">Harga Beli/Satuan</label>
                <input type="number" oninput="hitungtotal()" required name="harga_beli" id="harga" class="form-control mb-2">
            </div>
            <div class="col-md-12">
                <label for="" class="mb-0">Tipe Faktur</label>
                <select onchange="changeForm()" name="tipe_faktur" class="form-control mb-2" id="fak">
                    <option value="Pajak" selected>Faktur</option>
                    <option value="Non Pajak">Non Faktur</option>
                </select>
            </div>
            <div class="col-md-12" id="formdisk" style="display: none;">
                <label for="" class="mb-0">Diskon (%)</label>
                <input type="number" oninput="hitungtotal()" value="0" class="form-control mb-2" placeholder="Diskon" name="diskon" id="diskon">
            </div>
            <div class="col-md-12">
                <label for="mb-0">PPN (%)</label>
                <input type="number" value="12" oninput="hitungtotal()" placeholder="PPN" name="ppn" class="form-control mb-2" id="ppn">
            </div>
            <div class="col-md-12">
                <label for="">Total</label>
                <input type="number" readonly placeholder="Total" name="total" id="total" class="form-control mb-2">
            </div>
            <div class="col-md-12">
                <button type="submit" name="save" class="btn btn-primary float-end">Simpan</button>
            </div>
        </div>  
    </div>
</form>
<script>
    function changeForm() {
		var pil = document.getElementById('fak');
		var pilval = pil.options[pil.selectedIndex].value;
		var fd = document.getElementById('formdisk');
		if(pilval === "Non Pajak"){
			document.getElementById('ppn').value=0;
			fd.style.display='block';
			
		}
		if(pilval === "Pajak"){
			document.getElementById('ppn').value=11;
			fd.style.display='none';
		}
	}

    function hitungtotal() {
		var jmlh = document.getElementById('jumlah').value; 
		var hrg = document.getElementById('harga').value; 
		var pajak = document.getElementById('ppn').value;
		var disk = document.getElementById('diskon').value;

		var ttl = (jmlh*hrg)+(((jmlh*hrg)*pajak)/100)-(((jmlh*hrg)*disk)/100);
		document.getElementById('total').value=ttl;
	}
</script>
<?php 
    if(isset($_POST['save'])){
        if($_POST['nama_obat'] != 'Obat Baru'){
            $getObatSingle = $koneksi->query("SELECT * FROM apotek WHERE id_obat = '".htmlspecialchars($_POST['nama_obat'])."' ORDER BY idapotek DESC LIMIT 1")->fetch_assoc();
            $nama_obat = $getObatSingle['nama_obat'];
            $id_obat = $getObatSingle['id_obat'];
            $margininap = $getObatSingle['margininap'];
            $margin_jual = $getObatSingle['margin_jual'];
            $jml_obat_minim = $getObatSingle['jml_obat_minim'];
        }else{
            $nama_obat = htmlspecialchars($_POST['nama_obat_new']);
            $id_obat = htmlspecialchars($_POST['id_obat_new']);
            $margininap = '0';
            $margin_jual = '0';
            $jml_obat_minim = '0'; 
        }

        if($_POST['namasuplier'] != 'Suplier Baru'){
            $produsen = htmlspecialchars($_POST['namasuplier']);
        }else{
            $produsen = htmlspecialchars($_POST['namasuplier_new']);
            $nohp = htmlspecialchars($_POST['nohp_new']);
            $Alamat = htmlspecialchars($_POST['alamat_new']);

            $koneksi->query("INSERT INTO `suplier`(`namasuplier`, `nohp`, `Alamat`) VALUES ('$produsen','$nohp','$Alamat')");
        }

        $tipe = htmlspecialchars($_POST['tipe']);
        $tgl_beli = htmlspecialchars($_POST['tgl_beli']);
        $bentuk = htmlspecialchars($_POST['bentuk']);
        $jml_obat = htmlspecialchars($_POST['jml_obat']);
        $harga_beli = htmlspecialchars($_POST['harga_beli']);
        $tipe_faktur = htmlspecialchars($_POST['tipe_faktur']);
        $diskon = $harga_beli*(htmlspecialchars($_POST['diskon'])/100);
        $ppn = $harga_beli*(htmlspecialchars($_POST['ppn'])/100);
        $total = htmlspecialchars($_POST['total']);

        $koneksi->query("INSERT INTO pembelian_obat (jml_obat_minim, nama_obat, id_obat, jml_obat, produsen, bentuk, tipe, harga_beli, margininap, tgl_beli, margin_jual, tipe_faktur, diskon, ppn, total) VALUES ('$jml_obat_minim', '$nama_obat', '$id_obat', '$jml_obat', '$produsen', '$bentuk', '$tipe', '$harga_beli', '$margininap', '$tgl_beli', '$margin_jual', '$tipe_faktur', '$diskon', '$ppn', '$total') ");
        
        // $koneksi->query("INSERT INTO apotek (jml_obat_minim, nama_obat, id_obat, jml_obat, produsen, bentuk, tipe, harga_beli, margininap, tgl_beli, margin_jual) VALUES ('$jml_obat_minimum', '$nama_obat', '$id_obat', '$jml_obat', '$produsen', '$bentuk', '$tipe', '$harga_beli', '$margininap', '$tgl_beli', '$margin_jual') ");

        echo "
            <script>
                alert('Berhasil Order Obat, Silahkan Tunggu Hingga Obat Datang, dan Terima pada Menu Apotek Terima');
                document.location.href='index.php?halaman=tambah_obatmasuk';
            </script>
        ";
    }
?>

<!-- ALTER TABLE `apotek` ADD `tgl_beli` DATE NULL DEFAULT NULL AFTER `harga_beli`; -->