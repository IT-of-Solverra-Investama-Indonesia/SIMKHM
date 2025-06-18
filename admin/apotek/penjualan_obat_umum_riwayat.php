<div class="">
    <?php if (!isset($_GET['detail'])) { ?>
        <div class="">
            <h5 class="card-title">Riwayat Penjualan Umum</h5>
            <div class="card shadow p-2 mb-1">
                <form method="GET">
                    <div class="row">
                        <div class="col-5">
                            <input type="text" name="halaman" id="" value="penjualan_obat_umum_riwayat" class="form-control form-control-sm" placeholder="Cari Nota" hidden>
                            <input name="date_start" id="" class="form-control form-control-sm" placeholder="Tanggal Awal" value="<?= isset($_GET['date_start']) ? htmlspecialchars($_GET['date_start']) : "0000-00-00" ?>" onfocus="(this.type='date')" onblur="(this.type='text')">
                        </div>
                        <div class="col-5">
                            <input name="date_end" id="" class="form-control form-control-sm" placeholder="Tanggal Akhir" value="<?= isset($_GET['date_end']) ? htmlspecialchars($_GET['date_end']) : date('Y-m-d') ?>" onfocus="(this.type='date')" onblur="(this.type='text')">
                        </div>
                        <div class="col-2">
                            <button class="btn btn-sm btn-primary" name="filter"><i class="bi bi-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card shadow p-2 mb-1">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-sm" style="font-size: 12px;">
                        <thead>
                            <tr>
                                <th>Tgl Penjualan</th>
                                <th>Nota</th>
                                <th>Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            if(isset($_GET['filter'])) {
                                $dateStart = htmlspecialchars($_GET['date_start']) == "" ? "0000-00-00" : htmlspecialchars($_GET['date_start']);
                                $dateEnd = htmlspecialchars($_GET['date_end']);
                                $getDataPenjualan = $koneksi->query("SELECT *, SUM((harga_umum - diskon_obat)*jumlah) as total FROM penjualan_umum WHERE tgl_jual BETWEEN '$dateStart' AND '$dateEnd' GROUP BY nota ORDER BY tgl_jual DESC");
                            } else {
                                $getDataPenjualan = $koneksi->query("SELECT *, SUM((harga_umum - diskon_obat)*jumlah) as total FROM penjualan_umum WHERE 1 = 1 GROUP BY nota ORDER BY tgl_jual DESC");

                            }
                            foreach ($getDataPenjualan as $dataPenjualan) {
                            ?>
                                <tr>
                                    <td><?= $dataPenjualan['tgl_jual'] ?></td>
                                    <td>
                                        <a href="index.php?halaman=penjualan_obat_umum_riwayat&detail&nota=<?= $dataPenjualan['nota'] ?>" class="btn btn-sm btn-warning">
                                            <?= $dataPenjualan['nota'] ?>
                                        </a>
                                    </td>
                                    <td>Rp <?= number_format($dataPenjualan['total'], 0, 0, '.') ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="">
            <h5 class="card-title">Detail Penjualan Nota <?= htmlspecialchars($_GET['nota']) ?></h5>
            <div class="card shadow p-2">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-sm" style="font-size: 12px;">
                        <thead>
                            <tr>
                                <th>Kode Obat</th>
                                <th>Nama Obat</th>
                                <th>Tgl Jual</th>
                                <th>Harga Beli</th>
                                <th>Harga Umum</th>
                                <th>Diskon</th>
                                <th>Jumlah</th>
                                <th>Sub Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                            $getDetailPenjualan = $koneksi->query("SELECT * FROM penjualan_umum WHERE nota = '" . htmlspecialchars($_GET['nota']) . "' ORDER BY id_penjualan DESC");
                            foreach ($getDetailPenjualan as $dataDetail) {
                            ?>
                                <tr>
                                    <td><?= $dataDetail['kode_obat'] ?></td>
                                    <td><?= $dataDetail['nama_obat'] ?></td>
                                    <td><?= $dataDetail['tgl_jual'] ?></td>
                                    <td><?= number_format($dataDetail['harga_beli'], 0, 0, '.') ?></td>
                                    <td><?= number_format($dataDetail['harga_umum'], 0, 0, '.') ?></td>
                                    <td><?= number_format($dataDetail['diskon_obat'], 0, 0, '.') ?></td>
                                    <td><?= $dataDetail['jumlah'] ?></td>
                                    <td>Rp <?= number_format(($dataDetail['harga_umum'] - $dataDetail['diskon_obat']) * $dataDetail['jumlah'], 0, 0, '.') ?></td>
                                    <td>
                                        <?php if($_SESSION['admin']['level'] == 'sup'){?>
                                            <a onclick="return confirm('Are you sure to delete this data ?')" href="index.php?halaman=penjualan_obat_umum_riwayat&detail&nota=<?= $dataDetail['nota'] ?>&delete=<?= $dataDetail['id_penjualan'] ?>" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
                                        <?php }?>
                                    </td>
                                </tr>
                                <?php $total += ($dataDetail['harga_umum'] - $dataDetail['diskon_obat']) * $dataDetail['jumlah'] ?>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="7"><b>Total</b></td>
                                <td colspan="2"><b>Rp <?= number_format($total, 0, 0, '.') ?></b></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <?php
        if (isset($_GET['delete'])) {
            $idToDelete = htmlspecialchars($_GET['delete']);
            $deleteQuery = $koneksi->query("DELETE FROM penjualan_umum WHERE id_penjualan = '$idToDelete'");
            if ($deleteQuery) {
                echo "<script>alert('Data berhasil dihapus'); document.location.href='index.php?halaman=penjualan_obat_umum_riwayat&detail&nota=" . htmlspecialchars($_GET['nota']) . "';</script>";
            } else {
                echo "<script>alert('Gagal menghapus data');</script>";
            }
        }
        ?>
    <?php } ?>
</div>