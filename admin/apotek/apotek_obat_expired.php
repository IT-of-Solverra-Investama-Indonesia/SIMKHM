<div class="card shadow p-2">
    <div class="pagetitle mb-0">
        <h1>Daftar Obat Akan Expired</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php?halaman=daftarapotek" style="color:blue;">Apotek</a></li>
            </ol>
        </nav>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped" style="font-size: 12px;">
            <thead>
                <tr>
                    <th>Tgl Ecpired</th>
                    <th>Nama Obat</th>
                    <th>Kode</th>
                    <th>Tgl Beli</th>
                    <th>Harga Beli</th>
                    <th>Jumlah</th>
                    <th>Bacth</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $date_end = date('Y-m-d', strtotime('+3 months'));
                    $date_start = date('Y-m-d');

                    $getData = $koneksi->query("SELECT * FROM apotek WHERE tgl_expired BETWEEN '".$date_start."' AND '".$date_end."' ");
                    foreach ($getData as $data) {
                ?>
                <tr>
                    <td><?= $data['tgl_expired']?></td>
                    <td><?= $data['nama_obat']?></td>
                    <td><?= $data['id_obat']?></td>
                    <td><?= $data['tgl_beli']?></td>
                    <td><?= $data['harga_beli']?></td>
                    <td><?= $data['jml_obat']?></td>
                    <td><?= $data['batch']?></td>
                </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
</div>