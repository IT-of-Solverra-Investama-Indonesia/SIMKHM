<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
<!-- Tambahkan jQuery di sini -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
<!-- Tambahkan Bootstrap JS di sini -->
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>No.Inv</th>
                <th>Produk</th>
                <th>Total</th>
                <th>Status</th>
                <th>Nomor Resi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $user_id = sani($_SESSION['kosmetik']['idpasien']);
                $stmtHis = $koneksi->prepare("SELECT * FROM pemesanan WHERE user_id = ? GROUP BY code_nota ORDER BY created_at DESC");
                $stmtHis->bind_param("s", $user_id);
                $stmtHis->execute();
                $getHis = $stmtHis->get_result();
                foreach ($getHis as $data) {
            ?>
                <tr>
                    <td><?= $data['code_nota'] ?></td>
                    <td>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Harga</th>
                                        <th>Diskon</th>
                                        <th>Jumlah</th>
                                        <th>Sub</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $totalProduk = 0;
                                        $stmtProd = $koneksi->prepare("SELECT * FROM pemesanan WHERE user_id = ? AND code_nota = ?");
                                        $user_id_prod = sani($_SESSION['kosmetik']['idpasien']);
                                        $code_nota_prod = sani($data['code_nota']);
                                        $stmtProd->bind_param("ss", $user_id_prod, $code_nota_prod);
                                        $stmtProd->execute();
                                        $getProd = $stmtProd->get_result();
                                        foreach ($getProd as $prod){
                                    ?>
                                    <tr>
                                        <td><?=$prod['produk']?></td>
                                        <td>Rp <?= number_format($prod['harga'], 0, '','.')?></td>
                                        <td>Rp <?= number_format($prod['harga']-($prod['sub_harga']/($prod['jumlah'])), 0, '','.')?></td>
                                        <td>Rp <?= number_format($prod['jumlah'], 0, '','.')?></td>
                                        <td>Rp <?= number_format($prod['sub_harga'], 0, '','.')?></td>
                                    </tr>
                                    <?php $totalProduk += $prod['sub_harga']?>
                                    <?php }?>
                                </tbody>
                                </table>
                            </div>
                    </td>
                    <td>Rp <?= number_format($totalProduk, 0,'','.')?></td>
                    <td>
                        <?= $data['status'] ?><br>
                        <?php if($data['status'] == 'Menunggu_pembayaran'){?>
                            Lakukan Pembayaran Ke Nomor Rekening <b>330101007238502 (BRI)</b> 
                        <?php }else{?>
                            <img src="bukti_pembayaran/<?= $data['bukti_pembayaran']?>" style="max-width: 120px; border-radius: 10px;" alt="">
                        <?php }?>
                    </td>
                    <td><?= $data['no_resi'] ?></td>

                    <td>
                        <?php if ($data['status'] == 'Menunggu_pembayaran') { ?>
                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#edit<?= $data['code_nota']; ?>">Upload Bukti</button>
                        <?php } else { ?>
                            -
                        <?php } ?>
                    </td>

                </tr>
                <div class="modal fade" id="edit<?= $data['code_nota'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel2">Bukti pembayaran</h1>
                            </div>
                            <form method="POST" enctype="multipart/form-data">
                                <div class="modal-body">
                                    <input type="hidden" name="code_nota" class="form-control mb-1" value="<?= $data['code_nota'] ?>" required>
    
                                    <label for="">Upload Bukti Pembayaran</label>
                                    <input type="file" accept="image/*" name="foto_bukti" class="form-control mb-2">
    
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name="update" class="btn btn-success">Kirim</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php
if (isset($_POST['update'])) {
    $target_dir = "bukti_pembayaran/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
    $file_type = $_FILES['foto_bukti']['type'];
    $file_ext = strtolower(pathinfo($_FILES['foto_bukti']['name'], PATHINFO_EXTENSION));

    if (!in_array($file_type, $allowed_types) && !in_array($file_ext, ['jpg', 'jpeg', 'png', 'pdf'])) {
        echo "<script>alert('Hanya file gambar (JPG, JPEG, PNG) dan PDF yang diperbolehkan!'); window.history.back();</script>";
        exit;
    }

    $fileName = uniqid() . sani($_FILES['foto_bukti']['name']);
    move_uploaded_file($_FILES["foto_bukti"]["tmp_name"], $target_dir . $fileName);

    $stmt = $koneksi->prepare("UPDATE pemesanan SET bukti_pembayaran = ?, status = 'Diproses' WHERE code_nota = ?");
    $stmt->bind_param("ss", $fileName, sani($_POST['code_nota']));
    $stmt->execute();
    $stmt->close();

    echo "
        <script>
            alert('Berhasil mengirimkan bukti pembayaran');
            document.location.href='index.php?halaman=history';
        </script>
    ";
}

?>