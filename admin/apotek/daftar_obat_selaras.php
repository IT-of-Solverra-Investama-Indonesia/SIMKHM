<div class="pagetitle">
    <h1>Daftar Obat Penyelarasan</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php?halaman=daftarapotek" style="color:blue;">Apotek</a></li>
      </ol>
    </nav>
</div>
<a href="index.php?halaman=daftarapotek" class="btn btn-dark btn-sm mb-2" style="max-width: 100px;">Kembali</a>
<div class="card shadow-sm p-2">
    <div class="table-responsive">
        <table class="table table-hover table-striped" style="font-size: 13px;" id="myTable">
            <thead>
                <tr>
                    <th>Nomor</th>
                    <th>Nama Obat Lama</th>
                    <th>Daftar Obat Master</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $no = 1;    
                    $obatLama=$koneksi->query("SELECT *, SUM(jml_obat) as jumlah_beli FROM apotek GROUP BY nama_obat, id_obat order by idapotek desc;");
                    foreach($obatLama as $lama){
                ?>
                    <tr>
                        <td><?= $no++?></td>
                        <td><?= $lama['nama_obat']?></td>
                        <td>
                            <?php
                                $getBaruSingle = $koneksimaster->query("SELECT * FROM master_obat WHERE obat_master = '$lama[nama_obat]'");
                                if($getBaruSingle->num_rows >= 1){
                            ?>
                                <button onclick="upName('<?= $lama['nama_obat']?>')" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="btn btn-sm btn-warning"><?= $lama['nama_obat']?></button>
                            <?php }else{?>
                                <button onclick="upName('<?= $lama['nama_obat']?>')" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="btn btn-sm btn-warning">Pilih</button>
                            <?php }?>
                        </td>
                    </tr>
                <?php }?>
            </tbody>
        </table>

        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Ubah Nama Sesuai Master</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form method="post">
                  <div class="modal-body">
                    <input type="nama_lama" readonly name="nama_lama" id="nama_lama_id" class="form-control mb-2">
                    <select name="nama_baru" required id="nama_baru_id" class="form-control mb-2 w-100" style="width: 100%;">
                        <option value="">Pilih Obat</option>
                        <?php
                            $getBaru = $koneksimaster->query("SELECT * FROM master_obat ORDER BY obat_master ASC");
                            foreach($getBaru as $baru){
                        ?>
                            <option value="<?= $baru['obat_master']?>"><?= $baru['obat_master']?></option>
                        <?php }?>
                    </select>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="save" class="btn btn-warning">Ubah Nama</button>
                  </div>
              </form>
            </div>
          </div>
        </div>
    </div>
</div>
<?php 
    if(isset($_POST['save'])){
        $koneksi->query("UPDATE obat_rm SET nama_obat = '".htmlspecialchars($_POST['nama_baru'])."' WHERE nama_obat = '".htmlspecialchars($_POST['nama_lama'])."'");

        $koneksi->query("UPDATE apotek SET nama_obat = '".htmlspecialchars($_POST['nama_baru'])."' WHERE nama_obat = '".htmlspecialchars($_POST['nama_lama'])."'");

        $koneksi->query("UPDATE pembelian_obat SET nama_obat = '".htmlspecialchars($_POST['nama_baru'])."' WHERE nama_obat = '".htmlspecialchars($_POST['nama_lama'])."'");
        
        echo "
            <script>
                alert('successfully');
                document.location.href='index.php?halaman=daftar_obat_selaras';
            </script>
        ";
    }
?>
<script>
    function upName(old_name) {
        var nama_lama_var = document.getElementById('nama_lama_id');
        nama_lama_var.value = old_name;
    }
</script>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script>
    $('#nama_baru_id').select2({
        dropdownParent: $('#staticBackdrop')
    });
    $('#myTable').DataTable();
</script>
