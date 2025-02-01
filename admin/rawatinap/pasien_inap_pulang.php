<div class="card shadow-sm p-2 mb-2">
    <form method="POST">
        <div class="row">
            <div class="col-8">
                <input type="text" name="key" id="" class="form-control" placeholder="Cari...">
            </div>
            <div class="col-4">
                <button class="btn btn-primary" name="src"><i class="bi bi-search"></i></button>
            </div>
        </div>
    </form>
</div>

<div class="card shadow-sm p-2 mb-2">
    <div class="table-responsive">
        <table class="table-hover table-striped table" style="font-size: 12px;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pasien</th>
                    <th>Jenis Perawatan</th>
                    <th>Dokter</th>
                    <th>NoRm</th>
                    <th>Jadwal</th>
                    <th>Kamar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if(isset($_POST['src'])){
                        echo "
                            <script>
                                document.location.href='index.php?halaman=pasien_inap_pulang&src&key=".htmlspecialchars($_POST['key'])."';
                            </script>
                        ";
                    }

                    if(!isset($_GET['src'])){
                        $whereQuery = "";
                        $linkPage = "index.php?halaman=pasien_inap_pulang";
                    }else{
                        $key = htmlspecialchars($_GET['key']);
                        $whereQuery = "AND (nama_pasien LIKE '%".$key."%' OR perawatan LIKE '%".$key."%' OR dokter_rawat LIKE '%".$key."%' OR no_rm LIKE '%".$key."%' OR jadwal LIKE '%".$key."%' OR kamar LIKE '%".$key."%' OR status_antri LIKE '%".$key."%')";
                        $linkPage = "index.php?halaman=pasien_inap_pulang&src&key=".$key;
                    }
                    $query = "SELECT *, DATE_FORMAT(jadwal, '%Y-%m-%d') AS tgl FROM registrasi_rawat WHERE  (status_antri='Pulang') AND perawatan ='Rawat Inap' ".$whereQuery." ORDER BY idrawat DESC";

                    //   Pagination
                        // Parameters for pagination
                        $limit = 50; // Number of entries to show in a page
                        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        $start = ($page - 1) * $limit;
                
                    // Get the total number of records
                        $tgl_mulaii = date('Y-m-d', strtotime('2024-03-28'));
                        $result = $koneksi->query($query);
                        $total_records = $result->num_rows;
                
                    // Calculate total pages
                        $total_pages = ceil($total_records / $limit);
                
                        $cekPage = '';
                        if(isset($_GET['page'])){
                            $cekPage = $_GET['page'];
                        }else{
                            $cekPage = '1';
                        }
                    // End Pagination
                
                    $no = ($cekPage * $limit) - ($limit-1);

                    $getPasien = $koneksi->query($query." LIMIT $start, $limit;");

                    foreach ($getPasien as $kamar) {
                ?>
                    <tr>
                        <td><?= $no++?></td>
                        <td><?= $kamar['nama_pasien']?></td>
                        <td><?= $kamar['perawatan']?></td>
                        <td><?= $kamar['dokter_rawat']?></td>
                        <td><?= $kamar['no_rm']?></td>
                        <td><?= $kamar['jadwal']?></td>
                        <td><?= $kamar['kamar']?></td>
                        <td><?= $kamar['status_antri']?></td>
                        <td>
                            <div class="dropdown" >
                                <i data-bs-toggle="dropdown" style="color: blue; font-weight: bold; font-size: 20px;" class="bi bi-three-dots-vertical"></i>
                                <ul class="dropdown-menu">
                                    <li><a href="index.php?halaman=detailrm&inap&id=<?php echo $kamar["no_rm"]; ?>&tgl=<?php echo $kamar["jadwal"]; ?>&rawat=<?php echo $kamar["idrawat"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-eye-fill" style="color:darkblue;"></i> Detail</a></li>
                                    <li><a href="index.php?halaman=rekapinap&id=<?php echo $kamar["idrawat"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-cash-coin" style="color: red"></i> Rekap</a></li>
                                    <?php 
                                        $cekKajian = $koneksi->query("SELECT COUNT(*) as jumlah FROM kajian_awal_inap WHERE norm = '$kamar[no_rm]'")->fetch_assoc();
                                        if($cekKajian['jumlah'] != 0){
                                    ?>
                                        <li><a href="index.php?halaman=pengkajian&inap&norm=<?php echo $kamar["no_rm"]; ?>&id=<?php echo $kamar["jadwal"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-eye-fill" style="color:darkblue;"></i> Detail Pengkajian</a></li>
                                    <?php }?>
                                    <?php
                                        $ubah=$koneksi->query("SELECT * FROM rekam_medis WHERE nama_pasien = '$kamar[nama_pasien]' AND jadwal = '$kamar[jadwal]';")->fetch_assoc(); 
                                    ?>
                                    <?php if(empty($ubah["nama_pasien"])){ ?>
                                        <li><a href="index.php?halaman=rmedis&inap=inap&id=<?php echo $kamar["no_rm"]; ?>&tgl=<?php echo $kamar["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-card-list" style="color:orangered;"></i> Isi Rekam Medis</a></li>
                                    <?php }else{ ?>
                                        <!-- <li><a href="index.php?halaman=rmedis&id=<?php echo $kamar["no_rm"]; ?>&tgl=<?php echo $kamar["tgl"]; ?>&ubahrm" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-card-list" style="color:orangered;"></i> Ubah Rekam Medis</a></li> -->
                                    <?php } ?>
                                    <li><a href="index.php?halaman=falanak&id=<?php echo $kamar["no_rm"]?>&inap&tgl=<?php echo $kamar["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-file" style="color:blueviolet;"></i> Fallrisk Pediatri (Anak)</a></li>
                                    <li><a href="index.php?halaman=faldewasa&id=<?php echo $kamar["no_rm"]?>&inap&tgl=<?php echo $kamar["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-file" style="color:blueviolet;"></i> Fallrisk (Dewasa)</a></li>
                                    <li><a href="index.php?halaman=masukkeluar&id=<?php echo $kamar["no_rm"]?>&inap&tgl=<?php echo $kamar["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-box-arrow-left" style="color:black;"></i> Ringkasan Masuk Keluar</a></li>
                                    <li><a href="index.php?halaman=cttpenyakit&id=<?php echo $kamar["no_rm"]?>&inap&tgl=<?php echo $kamar["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-clipboard2-pulse" style="color:green;"></i> Catatan Penyakit</a></li>
                                    <li><a href="index.php?halaman=lpo&id=<?php echo $kamar["no_rm"]?>&inap&tgl=<?php echo $kamar["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-file-earmark-spreadsheet" style="color:orange;"></i>Observasi Perawat</a></li>
                                    <li><a href="index.php?halaman=rekonobat&id=<?php echo $kamar["no_rm"]?>&inap&tgl=<?php echo $kamar["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-capsule" style="color:orange;"></i> Rekonsiliasi Obat</a></li>
                                    <li><a href="index.php?halaman=asuhangizi&id=<?php echo $kamar["no_rm"]?>&inap&tgl=<?php echo $kamar["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-file-medical" style="color:darkblue;"></i> Asuhan Gizi</a></li>
                                    <li><a href="index.php?halaman=edukasi&id=<?php echo $kamar["no_rm"]?>&inap&tgl=<?php echo $kamar["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-journal-text" style="color:maroon;"></i> Edukasi</a></li>
                                    <li><a href="index.php?halaman=pulang&id=<?php echo $kamar["no_rm"]?>&inap&tgl=<?php echo $kamar["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-house-heart-fill" style="color:tomato;"></i> Discharge Planning</a></li>
                                    <li><a href="index.php?halaman=ivl&id=<?php echo $kamar["no_rm"]?>&inap&tgl=<?php echo $kamar["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-bandaid-fill" style="color:brown;"></i> IVL</a></li>
                                    <?php if(isset($_GET['inap'])) { ?>             
                                        <li><a href="index.php?halaman=rujuklab2&id=<?php echo $kamar["idrawat"]; ?>&rm=<?php echo $kamar["no_rm"]?>&inap&tgl=<?php echo $kamar["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-clipboard2-pulse" style="color:hotpink;"></i> Rujuk Lab</a></li>
                                    <?php }else{ ?>
                                        <li><a href="index.php?halaman=rujuklab2&id=<?php echo $kamar["idrawat"]; ?>&rm=<?php echo $kamar["no_rm"]?>&tgl=<?php echo $kamar["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-clipboard2-pulse" style="color:hotpink;"></i> Rujuk Lab</a></li>
                                    <?php } ?>
                                    <li><a href="index.php?halaman=tambahterapi&id=<?php echo $kamar["no_rm"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-clipboard2-plus" style="color:darkorchid;"></i>Terapi</a></li>
                                    <li><a href="index.php?halaman=hapusrm&id=<?php echo $kamar["idrawat"]; ?>" class="dropdown-item" style="text-decoration: none; font-weight: bold; margin-left: 2px;" onclick="return confirm('Anda yakin mau menghapus item ini ?')">
                                    <i class="bi bi-trash" style="color:red;"></i> Hapus</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
</div>

<div class="card shadow-sm p-2">
    <?php
        // Display pagination
        echo '<nav>';
        echo '<ul class="pagination justify-content-center">';
        
        // Back button
        if ($page > 1) {
            echo '<li class="page-item"><a class="page-link" href="'.$linkPage.'&page=' . ($page - 1) . '">Back</a></li>';
        }
        
        // Determine the start and end page
        $start_page = max(1, $page - 2);
        $end_page = min($total_pages, $page + 2);
        
        if ($start_page > 1) {
            echo '<li class="page-item"><a class="page-link" href="'.$linkPage.'&page=1">1</a></li>';
            if ($start_page > 2) {
                echo '<li class="page-item"><span class="page-link">...</span></li>';
            }
        }
        
        for ($i = $start_page; $i <= $end_page; $i++) {
            if ($i == $page) {
                echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
            } else {
                echo '<li class="page-item"><a class="page-link" href="'.$linkPage.'&page=' . $i . '">' . $i . '</a></li>';
            }
        }
        
        if ($end_page < $total_pages) {
            if ($end_page < $total_pages - 1) {
                echo '<li class="page-item"><span class="page-link">...</span></li>';
            }
            echo '<li class="page-item"><a class="page-link" href="'.$linkPage.'&page=' . $total_pages . '">' . $total_pages . '</a></li>';
        }
        
        // Next button
        if ($page < $total_pages) {
            echo '<li class="page-item"><a class="page-link" href="'.$linkPage.'&page=' . ($page + 1) . '">Next</a></li>';
        }
        
        echo '</ul>';
        echo '</nav>';
    ?>
</div>