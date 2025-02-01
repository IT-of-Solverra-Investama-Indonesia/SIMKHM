<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script>
    $(document).ready( function () {
        $('#myTable').DataTable();
    } );
</script>
<div class="pagetitle me-0">
    <h1>Harga Beli Tarakhir</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item mb-0"><a href="index.php?halaman=daftarapotek" style="color:blue;">Apotek</a></li>
      </ol>
    </nav>
</div>
<a href="index.php?halaman=daftarapotek" class="btn btn-sm btn-dark mb-2" style="max-width: 100px;">Kembali</a>
<div class="card shadow-sm p-2">
    <table class="table table-striped table-hover" style="font-size: 13px;" id="myTable">
        <thead>
            <tr>
                <th>Nama Obat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $getData=$koneksi->query("SELECT *, SUM(jml_obat) as jumlah_beli FROM apotek GROUP BY nama_obat, id_obat order by idapotek desc;");
                foreach($getData as $data){
            ?>
                <tr>
                    <td><?= $data['nama_obat']?></td>
                    <td>
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop<?= $data['idapotek']?>">
                          <i class="bi bi-eye"></i>
                        </button>
                        
                        <!-- Modal -->
                        <div class="modal fade" id="staticBackdrop<?= $data['idapotek']?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                <ul class="list-group">
                                    <?php
                                        $getDataDetail = $koneksi->query("SELECT * FROM apotek WHERE nama_obat = '$data[nama_obat]'");
                                        foreach($getDataDetail as $detail){
                                    ?>
                                    <li class="list-group-item" style="font-size: 13px;"><?= $detail['tgl_beli']?> | <?= $detail['produsen']?> | <?= $detail['jml_obat']."xRp".$detail['harga_beli']?></li>
                                    <?php }?>
                                </ul>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Understood</button>
                              </div>
                            </div>
                          </div>
                        </div>
                    </td>
                </tr>
            <?php }?>
        </tbody>
    </table>
</div>