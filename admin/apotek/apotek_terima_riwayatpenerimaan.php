<?php
    $id = htmlspecialchars($_GET['beli']);
    $getData = $koneksi->query("SELECT * FROM apotek WHERE pembelian_id = '".htmlspecialchars($_GET['beli'])."' ORDER BY tgl_beli DESC");
?>
<a href="index.php?halaman=apotek_terima" class="btn btn-sm btn-dark mb-2" style="max-width: 100px;"><i class="bi bi-arrow-left"></i> Kembali</a>
<div class="card shadow-sm p-2">
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>Tgl Terima</th>
                    <th>Nama Obat</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($getData as $data){?>
                    <tr>
                        <td><?= $data['tgl_datang']?></td>
                        <td><?= $data['nama_obat']?></td>
                        <td><?= $data['jml_obat']?></td>
                        <td>
                            <a href="index.php?halaman=apotek_terima_riwayatpenerimaan&beli=<?= $id?>&del=<?= $data['idapotek']?>" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
</div>
<?php 
    if(isset($_GET['del'])){
        $koneksi->query("DELETE FROM apotek WHERE idapotek = '".htmlspecialchars($_GET['del'])."'");

        echo "
            <script>
                alert('Successfully');
                document.location.href='index.php?halaman=apotek_terima_riwayatpenerimaan&beli=$id';
            </script>
        ";
    }
?>