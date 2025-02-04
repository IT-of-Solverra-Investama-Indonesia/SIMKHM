<?php
    $id = htmlspecialchars($_GET['beli']);
    
    $getSinglePembelian = $koneksi->query("SELECT * FROM pembelian_obat WHERE id_beli = '$id'")->fetch_assoc();
    $getSingleCek = $koneksi->query("SELECT * FROM apotek WHERE nama_obat = '$getSinglePembelian[nama_obat]'");
    if ($getSingleCek->num_rows == 0) {
        $koneksi->query("INSERT INTO apotek (nama_obat, tipe, id_obat, bentuk, jml_obat, jml_obat_minim, harga_beli, tgl_beli, margininap, margin_jual, produsen) VALUES ('$getSinglePembelian[nama_obat]', '$getSinglePembelian[tipe]', '$getSinglePembelian[id_obat]', '$getSinglePembelian[bentuk]', '0', '$getSinglePembelian[jml_obat_minim]', '$getSinglePembelian[harga_beli]', '$getSinglePembelian[tgl_beli]', '100', '100', '$getSinglePembelian[produsen]')");
    
        echo "
            <script>
                document.location.href='index.php?halaman=apotek_terima_penerimaan&beli=$_GET[beli]';
            </script>
        ";
    }

    $single = $koneksi->query("SELECT * FROM pembelian_obat INNER JOIN apotek ON apotek.id_obat = pembelian_obat.id_obat WHERE id_beli = '$id' GROUP BY apotek.id_obat")->fetch_assoc();
?>
<a href="index.php?halaman=apotek_terima" class="btn btn-sm btn-dark mb-2" style="max-width: 100px;"><i class="bi bi-arrow-left"></i> Kembali</a>
<div class="card shadow-sm p-2">
    <h4>Terima Barang <?= $single['nama_obat']?></h4>
    <form method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <label for="">Jumlah : </label>
                <input type="number" name="jml_obat" class="form-control mb-2" placeholder="Jumlah Obat Diterima">
            </div>
            <div class="col-md-6">
                <label for="">Tanggal Diterima : </label>
                <input type="date" name="tgl_datang" class="form-control mb-2" placeholder="Jumlah Obat Diterima">
            </div>
            <div class="col-md-12">
                <label for="">Batch : </label>
                <input type="text" name="batch" id="" class="form-control mb-2" placeholder="Batch">
            </div>
            <div class="col-md-6">
                <label for="">Foto Barang : </label>
                <input type="file" name="foto_barang" class="form-control mb-2">
            </div>
            <div class="col-md-6">
                <label for="">Foto Faktur :</label>
                <input type="file" name="foto_faktur" class="form-control mb-2">
            </div>
            <div class="col-md-12">
                <button class="btn btn-sm btn-primary float-end" name="terima">Terima</button>
            </div>
        </div>
    </form>
</div>
<?php
    if(isset($_POST['terima'])){
        $nama_obat = $single['nama_obat'];
        $id_obat = $single['id_obat'];
        $margininap = $single['margininap'] ?? 100;
        $margin_jual = $single['margin_jual'] ?? 100;
        $jml_obat_minim = $single['jml_obat_minim'];
        $tipe = $single['tipe'];
        $tgl_beli = $single['tgl_beli'];
        $bentuk = $single['bentuk'];
        $jml_obat = htmlspecialchars($_POST['jml_obat']);
        $tgl_datang = htmlspecialchars($_POST['tgl_datang']);
        $batch = htmlspecialchars($_POST['batch']);
        $harga_beli = $single['harga_beli'];
        $tipe_faktur = $single['tipe_faktur'];
        $diskon = $single['diskon'];
        $ppn = $single['ppn'];
        $total = $single['total'];
        $produsen = $single['produsen'];
        
        $fileName = $_FILES['foto_barang']['name'];
        $tmpName = $_FILES['foto_barang']['tmp_name'];
        $fileSize = $_FILES['foto_barang']['size'];
        $fileType = $_FILES['foto_barang']['type'];
        $uploadDir = "../apotek/foto_barang_datang/";
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        $foto_barang = uniqid() . date('Ymdhis') .'.' . $ext;
        $filePath = $uploadDir . $foto_barang;
        move_uploaded_file($tmpName, $filePath);
        
        $fileName1 = $_FILES['foto_faktur']['name'];
        $tmpName1 = $_FILES['foto_faktur']['tmp_name'];
        $fileSize1 = $_FILES['foto_faktur']['size'];
        $fileType1 = $_FILES['foto_faktur']['type'];
        $uploadDir1 = "../apotek/foto_faktur/";
        $ext1 = pathinfo($fileName1, PATHINFO_EXTENSION);
        $foto_faktur = uniqid() . date('Ymdhis') .'.' . $ext1;
        $filePath1 = $uploadDir1 . $foto_faktur;
        move_uploaded_file($tmpName1, $filePath1);

        $koneksi->query("INSERT INTO apotek (jml_obat_minim, nama_obat, id_obat, jml_obat, produsen, bentuk, tipe, harga_beli, margininap, tgl_beli, margin_jual, pembelian_id, tgl_datang, batch, foto_faktur, foto_barang) VALUES ('$jml_obat_minim', '$nama_obat', '$id_obat', '$jml_obat', '$produsen', '$bentuk', '$tipe', '$harga_beli', '$margininap', '$tgl_beli', '$margin_jual', '$id', '$tgl_datang', '$batch', '$foto_faktur', '$foto_barang') ");

        echo "
            <script>
                alert('Successfully');
                document.location.href='index.php?halaman=apotek_terima_penerimaan&beli=$id';
            </script>
        ";
    }
?>