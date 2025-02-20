<div>
    <div class="card shadow-sm p-2 mb-1">
        <form method="POST">
            <div class="row">
                <div class="col-8">
                    <input type="text" placeholder="Cari..." name="key" class="form-control" id="">
                </div>
                <div class="col-4">
                    <button class="btn btn-primary" name="src"><i class="bi bi-search"></i></button>
                </div>
            </div>
        </form>
    </div>
    <div class="card shadow-sm p-2 mb-1">
        <div class="table-responsive">
            <table class="table table-hover table-striped" style="font-size: 11px;">
                <thead>
                    <tr>
                        <th>Tanggal Beli</th>
                        <th>Tanggal Expired</th>
                        <th>Nama Obat</th>
                        <th>Kode Obat</th>
                        <th>Tipe</th>
                        <th>Jumlah</th>
                        <th>Harga Beli</th>
                        <th>Diskon</th>
                        <th>PPN</th>
                        <th>Total</th>
                        <th>Tipe Faktur</th>
                        <th>Sudah Diterima</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        if(isset($_POST['src'])){
                            echo "
                                <script>
                                    document.location.href='index.php?halaman=apotek_terima&src&key=".htmlspecialchars($_POST['key'])."';
                                </script>
                            ";
                        }

                        $searchWhere = "";
                        $urlPage = 'index.php?halaman=apotek_terima';
                        if(isset($_GET['src'])){
                            $searchWhere = "WHERE pembelian_obat.nama_obat LIKE '%".htmlspecialchars($_GET['key'])."'%";
                            $urlPage = 'index.php?halaman=apotek_terima&src&key='.htmlspecialchars($_GET['key']);
                        }
                        // ALTER TABLE `apotek` ADD `pembelian_id` INT(11) NULL AFTER `produsen`, ADD `tgl_datang` DATE NULL AFTER `pembelian_id`;
                        // ALTER TABLE `apotek` ADD `batch` VARCHAR(100) NOT NULL AFTER `tgl_datang`, ADD `foto_faktur` TEXT NOT NULL AFTER `batch`, ADD `foto_barang` TEXT NOT NULL AFTER `foto_faktur`;
                        $query = "SELECT pembelian_obat.*, COALESCE(SUM(apotek.jml_obat), 0) AS diterima FROM pembelian_obat LEFT JOIN apotek ON apotek.pembelian_id = pembelian_obat.id_beli ".$searchWhere." GROUP BY id_beli ORDER BY pembelian_obat.tgl_beli DESC";

                        //   Pagination
                            // Parameters for pagination
                            $limit = 50; // Number of entries to show in a page
                            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                            $start = ($page - 1) * $limit;
                            
                            // Get the total number of records
                            
                            $total_records = $koneksi->query($query)->num_rows;
                        
                            // Calculate total pages
                            $total_pages = ceil($total_records / $limit);
                        
                            $cekPage = '';
                            if(isset($_GET['page'])){
                                $cekPage = $_GET['page'];
                            }else{
                                $cekPage = '1';
                            }
                        // End Pagination
                    
                        $getData = $koneksi->query($query." LIMIT $start, $limit");

                        foreach ($getData as $data) {
                    ?>
                        <tr>
                            <td><?= $data['tgl_beli']?></td>
                            <td><?= $data['tgl_expired']?></td>
                            <td><?= $data['nama_obat']?></td>
                            <td><?= $data['id_obat']?></td>
                            <td><?= $data['tipe']?></td>
                            <td><?= $data['jml_obat']?></td>
                            <td>Rp<?= $data['harga_beli']?></td>
                            <td>Rp<?= $data['diskon']?></td>
                            <td>Rp<?= $data['ppn']?></td>
                            <td>Rp<?= $data['total']?></td>
                            <td><?= $data['tipe_faktur']?></td>
                            <td>
                                <a href="index.php?halaman=apotek_terima_riwayatpenerimaan&beli=<?= $data['id_beli']?>" class="btn btn-sm btn-warning px-2 py-1">
                                    <?= $data['diterima']?>
                                </a>
                            </td>
                            <td>
                                <?php if($data['diterima'] == '0'){?>
                                    <a href="index.php?halaman=apotek_terima&del=<?= $data['id_beli']?>" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
                                <?php }?>
                                <?php if($data['jml_obat'] > ($data['diterima'])){?>
                                    <a href="index.php?halaman=apotek_terima_penerimaan&beli=<?= $data['id_beli']?>" class="btn btn-sm btn-success"><i class="bi bi-dropbox"></i></a>
                                <?php }?>
                            </td>
                        </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
    </div>
    <?php 
        if(isset($_GET['del'])){
            $koneksi->query("DELETE FROM pembelian_obat WHERE id_beli = '".htmlspecialchars($_GET['del'])."'");
            echo "
                <script>
                    alert('Successfully');
                    document.location.href='index.php?halaman=apotek_terima';
                </script>
            ";
        }
    ?>
    <div class="card shadow-sm p-2">
        <?php
            // Display pagination
            echo '<nav>';
            echo '<ul class="pagination justify-content-center">';
            
            // Back button
            if ($page > 1) {
                echo '<li class="page-item mb-0"><a class="page-link" href="index.php?halaman=pendaftaranall&page=' . ($page - 1) . '">Back</a></li>';
            }
            
            // Determine the start and end page
            $start_page = max(1, $page - 2);
            $end_page = min($total_pages, $page + 2);
            
            if ($start_page > 1) {
                echo '<li class="page-item mb-0"><a class="page-link" href="index.php?halaman=pendaftaranall&page=1">1</a></li>';
                if ($start_page > 2) {
                    echo '<li class="page-item mb-0"><span class="page-link">...</span></li>';
                }
            }
            
            for ($i = $start_page; $i <= $end_page; $i++) {
                if ($i == $page) {
                    echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
                } else {
                    echo '<li class="page-item mb-0"><a class="page-link" href="index.php?halaman=pendaftaranall&page=' . $i . '">' . $i . '</a></li>';
                }
            }
            
            if ($end_page < $total_pages) {
                if ($end_page < $total_pages - 1) {
                    echo '<li class="page-item mb-0"><span class="page-link">...</span></li>';
                }
                echo '<li class="page-item mb-0"><a class="page-link" href="index.php?halaman=pendaftaranall&page=' . $total_pages . '">' . $total_pages . '</a></li>';
            }
            
            // Next button
            if ($page < $total_pages) {
                echo '<li class="page-item mb-0"><a class="page-link" href="index.php?halaman=pendaftaranall&page=' . ($page + 1) . '">Next</a></li>';
            }
            
            echo '</ul>';
            echo '</nav>';
        ?>
    </div>
</div>

