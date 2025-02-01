<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<!-- Tambahkan jQuery di sini -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Tambahkan Bootstrap JS di sini -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<?php
// Data produk
// $products = array(
//     array("name" => "Produk Kosmetik salep facial wow", "price" => '100', "image" => "https://images.tokopedia.net/img/cache/500-square/VqbcmM/2023/8/17/642a424b-2b51-4941-88d1-b9d1200a7c3a.jpg.webp?ect=4g"),
//     array("name" => "Produk Produk Kosmetik salep facial wow2", "price" => '200', "image" => "https://images.tokopedia.net/img/cache/500-square/VqbcmM/2023/8/17/642a424b-2b51-4941-88d1-b9d1200a7c3a.jpg.webp?ect=4g"),
// );
?>

<!-- Container for the Cart and Checkout button -->
<div class="container mt-0">
    <div class="row">
        <div class="col-12 text-center">
            <h2 style="color: green; font-weight: bold; font-size: 40px">Cart</h2>
        </div>
        <div class="col-12 p-2 d-flex justify-content-end">
            <button class="btn btn-success" type="button">
                <i class="bi bi-cart"></i>
                Checkout
            </button>
        </div>
    </div>
</div>


<div class="card border border-success">

    <div class="container ">
        <div class="row d-flex flex-sm-row">

            <div class="col-sm-8 p-4 d-flex flex-column gap-2">
                <?php
                    if(isset($_SESSION['kosmetik']) ){
                        $id_pasien = $_SESSION['kosmetik']['idpasien'];

                        $products = $koneksi->query("SELECT cart_kosmetik.*,produk_kosmetik.* FROM cart_kosmetik join produk_kosmetik
                        on cart_kosmetik.produk_id = produk_kosmetik.id_produk  WHERE cart_kosmetik.user_id = '$id_pasien' 
                        GROUP BY cart_kosmetik.produk_id");

                        // print_r($products);

                    }else{
                        echo"
                            <script>
                                alert('Lakukan Login Terlebih Dahulu');
                                document.location.href='login.php';
                            </script>
                        ";
                    }
                ?>

                <?php foreach ($products as $index => $product): ?>
                    <div class="card border border-success p-2" style="">
                        <div class="product">
                            <div class="product-content" style="  display: flex;">
                                <a href="produk_kosmetik/<?= $product['foto']?>" target="_blank">
                                    <img class="product-image"
                                        style=" max-width: 100px;max-height: 100px; margin-bottom: 10px;border-radius: 10px;margin-right: 10px;"
                                        src="produk_kosmetik/<?= $product['foto']?>" alt="<?php echo $product['produk']; ?>">
                                </a>
                                <div class="row">
                                    <h6><?php echo $product['produk']; ?></h6>
                                    <h6>Diskon <?php echo $product['diskon']; ?>%</h6>
                                    <h6><?php echo $product['berat']; ?> gram</h6>
                                    <p class="card-text">Rp. <?= number_format($product['harga'], 0, ',', '.') ?>,00</p>

                                    <h6 id="price" style="display:none"><?php echo $product['harga']; ?></h6>

                                </div>
                            </div>
                            <div class="quantity d-flex">
                                <div class="card-jumlah d-flex gap-2 mb-2 mr-2">

                                    <button class="btn btn-outline-success" onclick="decrement(<?php echo $index; ?>)">
                                        <i class="bi bi-dash"></i>
                                    </button>

                                    <input type="number" id="quantity-<?php echo $index; ?>" type="number" readonly
                                        style="width:80px" class="border border-success btn-sm" value="1">

                                    <button class="btn btn-outline-success" onclick="increment(<?php echo $index; ?>)">
                                        <i class="bi bi-plus"></i>

                                    </button>
                                    <button style="" class="btn btn-outline-danger"
                                        onclick="removeProduct(<?php echo $index; ?>)">
                                        <i class="fas fa-trash"></i>

                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="col-sm-4  p-4 d-flex flex-column gap-2">
                <div class="card border border-success p-4" style="">
                    <div class="total d-flex gap-3 flex-column">
                        <h4>
                            <label for="" class="form-label">Silahkan Isi Data : </label>
                        </h4>
                        <div>
                            <label for="" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control border border-success" id="nama_lengkap"
                                placeholder="Nama Lengkap" value="<?=$_SESSION['kosmetik']['nama_lengkap']?>">
                        </div>
                        <div>
                            <label for="" class="form-label">Alamat Lengkap</label>
                            <textarea type="text" class="form-control border border-success" id="alamat"
                                placeholder="Alamat Lengkap" value=""></textarea>
                        </div>
                        <div>
                            <label for="" class="form-label">Nomor HP</label>
                            <input type="text" class="form-control border border-success" id="no_hp"
                                placeholder="Nomor HP" value="<?=$_SESSION['kosmetik']['nohp']?>">
                        </div>

                        <h4>Total Belanja</h4>
                        <p>Jumlah Barang : <span id="total-quantity">0</span> Item</p>
                        <p>Total Biaya : Rp. <span id="total-cost">0</span>,00</p>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>

    </script>
    <script>

        document.addEventListener('DOMContentLoaded', function () {
            var quantities = document.querySelectorAll('.quantity-input');
            var prices = document.querySelectorAll('#price');
            var totalElement = document.getElementById('total');

            function updateTotals() {
                var total = 0;
                for (var i = 0; i < quantities.length; i++) {
                    var quantity = parseInt(quantities[i].value);
                    var price = parseInt(prices[i].textContent);
                    total += quantity * price;
                }
                totalElement.textContent = 'Total: ' + total;
            }

            quantities.forEach(function (input) {
                input.addEventListener('change', function () {
                    updateTotals();
                });
            });

            updateTotal();
        });

        function increment(index) {
            var input = document.getElementById('quantity-' + index);
            var newValue = parseInt(input.value) + 1;
            input.value = newValue;
            updateTotal();
        }

        function decrement(index) {
            var input = document.getElementById('quantity-' + index);
            var newValue = parseInt(input.value) - 1;
            if (newValue < 1) newValue = 1;
            input.value = newValue;
            updateTotal();
        }

        function removeProduct(index) {
            var product = document.getElementById('product-' + index);
            product.remove();
            updateTotal();
        }

        function updateTotal() {
            var totalQuantity = 0;
            var totalCost = 0;
            var products = document.querySelectorAll('.product');
            products.forEach(function (product) {

                var quantityInput = product.querySelector('.quantity input');
                var quantity = parseInt(quantityInput.value);

                totalQuantity += quantity;
                // console.log('ada'+totalQuantity);

                var priceElement = product.querySelector('#price');
                var price = parseInt(priceElement.textContent);
                totalCost += quantity * price;
            });
            document.getElementById('total-quantity').textContent = totalQuantity;
            document.getElementById('total-cost').textContent = totalCost.toLocaleString('id-ID');

        }

    </script>