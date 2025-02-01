<?php 
    if(isset($_SESSION['kosmetik'])){
        $id_pasien = $_SESSION['kosmetik']['idpasien'];
        $products = $koneksi->query("SELECT cart_kosmetik.*,produk_kosmetik.* FROM cart_kosmetik join produk_kosmetik on cart_kosmetik.produk_id = produk_kosmetik.id_produk  WHERE cart_kosmetik.user_id = '$id_pasien'");
    }else{
        $products = $koneksi->query("SELECT cart_kosmetik.*,produk_kosmetik.* FROM cart_kosmetik join produk_kosmetik on cart_kosmetik.produk_id = produk_kosmetik.id_produk LIMIT 0");
    }
?>
<div class="container">
    <style>
        .pagination {
            --bs-pagination-active-bg: #198754;
            --bs-pagination-active-border-color: #198754;
        }
        #hide{
            display:block;
        }
        #hide2{
            display: none;
        }

        @media (min-width: 576px) {
            .filter-product {
                position: -webkit-sticky;
                position: sticky;
                top: 0px;
                display: none;
            }
            #hide{
                position: -webkit-sticky;
                position: sticky;
                top: 0px;
                display: none;
            }
            #hide2{
                display: block;
            }
        }

    </style>
    <div class="mb-0 d-flex pt-3 align-items-end justify-content-end">
        <div class="row">
        <div class="col-2 mx-3" id="hide" >
            <h3>
                <a class="nav-link" style="float: left;" href="index.php?halaman=cart"><b><i class="bi bi-cart4"></i></b></a>
                <sup style="background-color: green;  font-size:12px; color: white; padding: 2px; border-radius: 5px;"><?= $products->num_rows?></sup>
            </h3>
        </div> 
        <div class="col-2" id="hide" >
            <h3>
            <a class="nav-link"   href="index.php?halaman=history"><b><i class="bi bi-clock-history"></i></b> </a>
            </h3>
        </div> 
        </div> 
    </div>
    <div class="container mx-auto mb-0 d-flex flex-sm-row flex-column align-items-start justify-content-center gap-sm-4">        
    <div class="d-flex flex-column col-sm-3 filter-product">
            <form method="POST">           
                <h5>Urutkan</h5>
                <div class="input-group">
                    <select class="form-select  mb-2" name="fil" aria-label="Default select example">
                        <option selected value="Rendah">Harga Terendah</option>
                        <option value="Tinggi">Harga Tertinggi</option>
                    </select>
                    <button class="btn h-100 btn-success" name="filter" type="submit"><i class="bi bi-funnel"></i></button>
                </div>
            </form>
        </div>
        <div class="d-flex flex-column col-sm-8 pt-2">
            <div class="row">
                <div class="col-md-10">
                    <form method="POST">
                        <div class="input-group mb-3">
                            <input type="text" name="keyword" class="form-control" placeholder="Cari Produk Kosmetik ...">
                            <button class="btn btn-success" type="submit"><i class="bi bi-search"></i></button>
                        </div>
                    </form>
                </div>
                <div class="col-md-2" id="hide2">
                    <h3>
                        <a class="nav-link me-3" style="float: left;" href="index.php?halaman=cart"><b><i class="bi bi-cart4"></i><sup style="background-color: green; color: white; padding: 1px; border-radius: 5px;"><?= $products->num_rows?></sup></b> </a>
                        <a class="nav-link" href="index.php?halaman=history"><b><i class="bi bi-clock-history"></i></b> </a>
                    </h3>
                </div>
            </div>
            <div class="container">
                <div class="row row-cols-sm-3 row-cols-2">
                    <?php

                    //   Pagination
                    if (isset($_POST['keyword'])) {
                        $result = $koneksi->query("SELECT * FROM produk_kosmetik WHERE nama_produk LIKE '%$_POST[keyword]%' ORDER BY id_produk DESC");
                    } elseif (isset($_POST['filter'])) {
                        if ($_POST['fil'] == 'Tinggi') {
                            $result = $koneksi->query("SELECT * FROM produk_kosmetik ORDER BY harga DESC");
                        } elseif ($_POST['fil'] == 'Rendah') {
                            $result = $koneksi->query("SELECT * FROM produk_kosmetik ORDER BY harga ASC");
                        }
                    } else {
                        $result = $koneksi->query("SELECT * FROM produk_kosmetik ORDER BY id_produk DESC");
                    }
                    // Parameters for pagination
                    $limit = 10; // Number of entries to show in a page
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $start = ($page - 1) * $limit;

                    // Get the total number of records
                    $tgl_mulaii = date('Y-m-d', strtotime('2024-03-28'));
                    $total_records = $result->num_rows;

                    // Calculate total pages
                    $total_pages = ceil($total_records / $limit);

                    $cekPage = '';
                    if (isset($_GET['page'])) {
                        $cekPage = $_GET['page'];
                    } else {
                        $cekPage = '1';
                    }
                    // End Pagination

                    if (isset($_POST['keyword'])) {
                        $products = $koneksi->query("SELECT * FROM produk_kosmetik WHERE nama_produk LIKE '%$_POST[keyword]%' ORDER BY id_produk DESC LIMIT $start, $limit;");
                    } elseif (isset($_POST['filter'])) {
                        if ($_POST['fil'] == 'Tinggi') {
                            $products = $koneksi->query("SELECT * FROM produk_kosmetik ORDER BY harga asc LIMIT $start, $limit;");
                        } elseif ($_POST['fil'] == 'Rendah') {
                            $products = $koneksi->query("SELECT * FROM produk_kosmetik ORDER BY harga desc LIMIT $start, $limit;");
                        }
                    } else {
                        $products = $koneksi->query("SELECT * FROM produk_kosmetik ORDER BY id_produk DESC LIMIT $start, $limit;");
                    }

                    foreach ($products as $product) {
                    ?>
                        <div class="col p-2">
                            <div class="card w-100 h-100">
                                <a href="index.php?halaman=detail_produk&id_produk=<?= $product['id_produk'] ?>">

                                    <img src="produk_kosmetik/<?= $product['foto'] ?>" class="card-img-top" alt="https://picsum.photos/200">

                                </a>
                                <div class="card-body p-2">
                                    <h6 class="card-title"><?= $product['nama_produk'] ?></h6>
                                    <h5 class="card-text text-success">Rp <?= number_format($product['harga'], 0, '', '.') ?></h5>
                                    <a href="index.php?halaman=shop&add=<?= $product['id_produk'] ?>&kategori=<?= $product['kategori']?>" class="btn btn-success w-100">+ Keranjang</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <?php
            if (isset($_GET['add'])) {
                if (isset($_SESSION['kosmetik'])) {
                    $getProd = $koneksi->query("SELECT * FROM produk_kosmetik WHERE id_produk = '" . htmlspecialchars($_GET['add']) . "'")->fetch_assoc();
                    $user_id = $_SESSION['kosmetik']['idpasien'];
                    $username = $_SESSION['kosmetik']['nama_lengkap'];
                    $produk_id = $getProd['id_produk'];
                    $produk = $getProd['nama_produk'];
                    $harga = $getProd['harga'];
                    $diskon = $getProd['diskon'];
                    $jumlah = '1';
                    $sub_harga = 1 * $getProd['harga'];

                    $koneksi->query("INSERT INTO cart_kosmetik (user_id, username, produk_id, produk, harga, diskon, jumlah, sub_harga) VALUES ('$user_id', '$username', '$produk_id', '$produk', '$harga', '$diskon', '$jumlah', '$sub_harga')");
                    
                    if($_GET['kategori'] =='Konsultasi'){
                        echo "
                            <script>
                                alert('Berhasil Menambahkan Produk Ke Keranjang, Tetapi Untuk Menggunakan Produk Ini, Silahkan Lakukan Konsultasi Terlebih Dahulu Kepada Dokter Kami !');
                                document.location.href='chat.php';
                            </script>
                        ";
                    }else{
                        echo "
                            <script>
                                alert('Berhasil Menambahkan Produk Ke Keranjang');
                                document.location.href='index.php?halaman=shop';
                            </script>
                        ";
                    }
                } else {
                    echo "
                            <script>
                                alert('Lakukan Login Terlebih Dahulu Sebelum Melakukan Pembelian');
                                document.location.href='login.php';
                            </script>
                        ";
                }
            }

            ?>
            <div class="d-flex justify-content-center my-3">
                <?php
                // Display pagination
                echo '<nav>';
                echo '<ul class="pagination justify-content-center">';

                // Back button
                if ($page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="index.php?halaman=shop&page=' . ($page - 1) . '">Back</a></li>';
                }

                // Determine the start and end page
                $start_page = max(1, $page - 2);
                $end_page = min($total_pages, $page + 2);

                if ($start_page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="index.php?halaman=shop&page=1">1</a></li>';
                    if ($start_page > 2) {
                        echo '<li class="page-item"><span class="page-link">...</span></li>';
                    }
                }

                for ($i = $start_page; $i <= $end_page; $i++) {
                    if ($i == $page) {
                        echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
                    } else {
                        echo '<li class="page-item"><a class="page-link" href="index.php?halaman=shop&page=' . $i . '">' . $i . '</a></li>';
                    }
                }

                if ($end_page < $total_pages) {
                    if ($end_page < $total_pages - 1) {
                        echo '<li class="page-item"><span class="page-link">...</span></li>';
                    }
                    echo '<li class="page-item"><a class="page-link" href="index.php?halaman=shop&page=' . $total_pages . '">' . $total_pages . '</a></li>';
                }

                // Next button
                if ($page < $total_pages) {
                    echo '<li class="page-item"><a class="page-link" href="index.php?halaman=shop&page=' . ($page + 1) . '">Next</a></li>';
                }

                echo '</ul>';
                echo '</nav>';
                ?>
            </div>
        </div>
    </div>
</div>