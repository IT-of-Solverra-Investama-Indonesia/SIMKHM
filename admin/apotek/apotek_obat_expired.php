<div class="pagetitle mb-0">
    <h1>Daftar Obat Akan Expired</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?halaman=daftarapotek" style="color:blue;">Apotek</a></li>
        </ol>
    </nav>
</div>
<div class="card shadow p-2 my-1 p-2">
    <?php
    $filter = htmlspecialchars($_GET['waktu'] ?? '3');
    ?>
    <form method="get">
        <input type="text" name="halaman" value="apotek_obat_expired" hidden id="">
        <div class="row g-1">
            <div class="col-8">
                <select name="waktu" id="" class="form-control form-control-sm">
                    <option value="3" <?= $filter == '3' ? 'selected' : '' ?>>Expired dalam 3 Bulan</option>
                    <option value="6" <?= $filter == '6' ? 'selected' : '' ?>>Expired dalam 6 Bulan</option>
                    <option value="9" <?= $filter == '9' ? 'selected' : '' ?>>Expired dalam 9 Bulan</option>
                    <option value="Sudah" <?= $filter == 'Sudah' ? 'selected' : '' ?>>Sudah Expired</option>
                </select>
            </div>
            <div class="col-4">
                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-filter"></i> Filter</button>
            </div>
        </div>
    </form>
</div>
<div class="card shadow p-2 my-1 p-2">
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
                if ($filter == 'Sudah') {
                    $whereCondition = " AND tgl_expired < '" . date('Y-m-d') . "' ";
                } else {
                    $date_end = date('Y-m-d', strtotime('+' . $filter . ' months'));
                    $date_start = date('Y-m-d');
                    $whereCondition = " AND tgl_expired BETWEEN '" . $date_start . "' AND '" . $date_end . "'";
                }

                $getData = $koneksi->query("SELECT * FROM apotek WHERE 1=1 " . $whereCondition . " ORDER BY tgl_expired ASC");
                foreach ($getData as $data) {
                ?>
                    <tr>
                        <td><?= $data['tgl_expired'] ?></td>
                        <td><?= $data['nama_obat'] ?></td>
                        <td><?= $data['id_obat'] ?></td>
                        <td><?= $data['tgl_beli'] ?></td>
                        <td><?= $data['harga_beli'] ?></td>
                        <td><?= $data['jml_obat'] ?></td>
                        <td><?= $data['batch'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>