<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" />
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script>
    $(document).ready( function () {
        $('#myTable').DataTable({
            order : [0, 'desc']
        });
    } );
</script>
<h3>Pendapatan</h3>
<br>
<?php
    if(isset($_POST['src'])){
        $dari = date('d-m-Y', strtotime($_POST['dari']));
        $hingga = date('d-m-Y', strtotime($_POST['hingga']));
        $getBiaya = $koneksi->query("SELECT *, registrasi_rawat.kasir, registrasi_rawat.shift, DATE_FORMAT(biaya_rawat.created_at, '%d-%m-%Y') as bulan, sum((poli+total_lain+biaya_lab)-potongan) as pendapatan  FROM registrasi_rawat INNER JOIN biaya_rawat WHERE idregis=idrawat and (status_antri = 'Datang' or status_antri = 'Pembayaran')  and perawatan = 'Rawat Jalan' AND DATE_FORMAT(biaya_rawat.created_at, '%d-%m-%Y') >= '$dari' and  DATE_FORMAT(biaya_rawat.created_at, '%d-%m-%Y') <= '$hingga' GROUP BY bulan;");
    }else{
        $getBiaya = $koneksi->query("SELECT *, registrasi_rawat.kasir, registrasi_rawat.shift, DATE_FORMAT(biaya_rawat.created_at, '%d/%m/%Y') as bulan, sum((poli+total_lain+biaya_lab)-potongan) as pendapatan  FROM registrasi_rawat INNER JOIN biaya_rawat WHERE idregis=idrawat and (status_antri = 'Datang' or status_antri = 'Pembayaran')  and perawatan = 'Rawat Jalan' GROUP BY bulan;");
    }
    // $getBiaya = $koneksi->query("SELECT *, DATE_FORMAT(biaya_rawat.created_at, '%d-%m-%Y') as bulan, sum((poli+total_lain+biaya_lab)-potongan) as pendapatan FROM biaya_rawat  group by bulan;");
?>
<div class="card shadow p-2">
    <form method="POST">
        <div class="row">
            <div class="col-md-5">
                <label for="" class="my-0">Dari :</label>
                <input type="date" name="dari" id="" class="form-control mb-2">
            </div>
            <div class="col-md-5">
                <label for="" class="my-0">Hingga :</label>
                <input type="date" name="hingga" id="" class="form-control">
            </div>
            <div class="col-md-2">
                <br>
                <button class="btn btn-primary" name="src"><i class="bi bi-search"></i></button>
            </div>
        </div>
    </form>
</div>

<div class="card shadow p-2">
    <div class="table-responsive">
        <table class="table" id="myTable">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($getBiaya as $data){?>
                    <tr>
                        <td><a href="index.php?halaman=daftarbayar&detail&tgl=<?= $data['bulan']?>"><?= $data['bulan']?></a></td>
                        <td>Rp <?= number_format(intval($data['pendapatan']),0,'','.')?></td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
</div>