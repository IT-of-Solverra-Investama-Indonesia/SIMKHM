<?php session_start()?>
<?php 
  include "function.php";
  
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SIM KHM</title>
    <style>
        .owl-carousel .owl-nav {
  visibility: hidden;
  height: 10px;
}

/* Style the navigation container */
.owl-carousel .owl-nav-custom {
  position: absolute;
  bottom: 10px; /* Adjust position as needed */
  left: 50%;
  transform: translateX(-50%);
  display: flex; /* Arrange buttons horizontally */
}

/* Style the prev button */
.owl-carousel .owl-nav-custom .owl-prev {
  background-color: #007bff; /* Blue background for prev button */
  color: #fff; /* White text for prev button */
  padding: 10px 15px; /* Adjust padding as needed */
  border: none; /* Remove border */
  border-radius: 5px; /* Add rounded corners */
  cursor: pointer;
  margin-right: 10px; /* Add margin between buttons */
  transition: all 0.3s ease;
}

/* Style the prev button on hover */
.owl-carousel .owl-nav-custom .owl-prev:hover {
  background-color: #0067cc; /* Darken blue on hover */
}

/* Style the next button */
.owl-carousel .owl-nav-custom .owl-next {
  background-color: #dc3545; /* Red background for next button */
  color: #fff; /* White text for next button */
  padding: 10px 15px; /* Adjust padding as needed */
  border: none; /* Remove border */
  border-radius: 5px; /* Add rounded corners */
  cursor: pointer;
  transition: all 0.3s ease;
}

/* Style the next button on hover */
.owl-carousel .owl-nav-custom .owl-next:hover {
  background-color: #bd2130; /* Darken red on hover */
}

    </style>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css"
        integrity="sha512-q3eWabyZPc1XTCmF+8/LuE1ozpg5xxn7iO89yfSOd5/oKvyqLngoNGsx8jq92Y8eXJ/IRxQbEC+FGSYxtk2oiw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link
      rel="icon"
      type="image/png"
      href="../admin/dist/assets/img/logokhm.png"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"
      integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css"
      integrity="sha512-OTcub78R3msOCtY3Tc6FzeDJ8N9qvQn1Ph49ou13xgA9VsH9+LRxoFU6EqLhW4+PKRfU+/HReXmSZXHEkpYoOA=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
      crossorigin="anonymous"
    />
  </head>
  <style></style>
  <body>
    <nav class="navbar navbar-expand-lg bg-light fixed-top shadow-sm">
      <div class="container">
        <a class="navbar-brand" href="#">
          <img
            src="../admin/dist/assets/img/khm.png"
            style="max-width: 90px"
            alt=""
          />
        </a>
        <!-- <span
          class="navbar-toggler"
          style="border: none"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarText"
          aria-controls="navbarText"
          aria-expanded="false"
          aria-label="Toggle navigation"
        > -->
        <span data-bs-toggle="modal" class="navbar-toggler" style="border: none" aria-expanded="false" data-bs-target="#menuHP">
          <span class="navbar-toggler-icon"></span>
        </span>
        <div class="collapse navbar-collapse" id="navbarText">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0"></ul>
          <span class="navbar-text">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link" href="?halaman=home">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="?halaman=about_us">About Us</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="?halaman=shop">Shop</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href=""><b>|</b></a>
              </li>
              <?php if(!isset($_SESSION['kosmetik'])){?>
                <?php
                  $products = $koneksi->query("SELECT cart_kosmetik.*,produk_kosmetik.* FROM cart_kosmetik join produk_kosmetik on cart_kosmetik.produk_id = produk_kosmetik.id_produk LIMIT 0");
                ?>
                
                <li class="nav-item">
                  <a
                    class="btn btn-sm mt-2 border border-success text-success"
                    style="border-radius: 12px"
                    href="login.php"
                    >Login / Register</a
                  >
                </li>
              <?php }else{?>
                <?php 
                  $id_pasien = $_SESSION['kosmetik']['idpasien'];
                  $products = $koneksi->query("SELECT cart_kosmetik.*,produk_kosmetik.* FROM cart_kosmetik join produk_kosmetik on cart_kosmetik.produk_id = produk_kosmetik.id_produk  WHERE cart_kosmetik.user_id = '$id_pasien'");
                ?>
                <li class="nav-item">
                  <a class="nav-link" href="chat.php"><b><i class="bi bi-chat-left-text"></i></b> </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="index.php?halaman=cart"><b><i class="bi bi-cart4"></i><sup style="background-color: green; color: white; padding: 2px; border-radius: 5px;"><?= $products->num_rows?></sup></b> </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="index.php?halaman=history"><b><i class="bi bi-clock-history"></i></b> </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="chat.php?logout"><b><i class="bi bi-box-arrow-in-left"></i></b> </a>
                </li>
                <li class="nav-item ">
                  <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#userModal">
                    <?= $_SESSION['kosmetik']['nama_lengkap']?>
                  </a>
                </li>
              <?php }?>
            </ul>
          </span>
        </div>
      </div>
    </nav>

    <div class="fixed-bottom h4 d-flex justify-content-end mx-4 my-4">
          <div class="rounded-pill p-3 mr-2 shadow-lg" style="background-color: green; width: fit-content; color:white;">
          <a href="https://api.whatsapp.com/send?phone=6282233880001" class="text-white"><i class="bi bi-whatsapp"></i></a>
          </div>
        </div>

    <!-- Modal -->
  <div class="modal fade" id="menuHP" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Menus</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <ul class="list-group">
            <li class="list-group-item">
              <a class="nav-link" href="?halaman=home">Home</a>
            </li>
            <li class="list-group-item">
              <a class="nav-link" href="?halaman=about_us">About Us</a>
            </li>
            <li class="list-group-item">
              <a class="nav-link" href="?halaman=shop">Shop</a>
            </li>
            <?php if(!isset($_SESSION['kosmetik'])){?>
              <li class="list-group-item">
                <a
                  class="btn btn-sm mt-2 border border-success text-success"
                  style="border-radius: 12px"
                  href="login.php"
                  >Login / Register</a
                >
              </li>
            <?php }else{?>
              <li class="list-group-item">
                <a class="nav-link" href="chat.php"><b><i class="bi bi-chat-left-text"></i></b> Konsultasi</a>
              </li>
              <li class="list-group-item">
                <a class="nav-link" href="index.php?halaman=cart"><b><i class="bi bi-cart4"></i><sup style="background-color: green; color: white; padding: 2px; border-radius: 5px;"><?= $products->num_rows?></sup></b> Keranjang</a>
              </li>
              <li class="list-group-item">
                <a class="nav-link" href="index.php?halaman=history"><b><i class="bi bi-clock-history"></i></b> History Belanja</a>
              </li>
              <li class="list-group-item">
                <a class="nav-link" href="chat.php?logout"><b><i class="bi bi-box-arrow-in-left"></i></b> Logout</a>
              </li>
              <li class="list-group-item ">
                <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#userModal">
                  <?= $_SESSION['kosmetik']['nama_lengkap']?>
                </a>
              </li>
            <?php }?>
          </ul>
        </div>
        <div class="modal-footer">
          <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Understood</button> -->
        </div>
      </div>
    </div>
  </div>
    <!-- Modal -->
    <!-- <div class="modal fade" id="userModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">Menu User</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <ul class="list-group">
                
              </ul>
          </div>
          <div class="modal-footer">
          </div>
        </div>
      </div>
    </div> -->
    <br /><br /><br />
    <div class="container mt-2 mb-4">
      <?php 
        if(!isset($_GET['halaman'])){include 'home.php';}
        else{
          if($_GET['halaman']=='home'){include 'home.php';}
          elseif($_GET['halaman']=='shop'){include 'shop.php';}
          elseif($_GET['halaman']=='about_us'){include 'about_us.php';}
          elseif($_GET['halaman']=='cart'){include 'cart.php';}
          elseif($_GET['halaman']=='chat'){include 'chat.php';}
          elseif($_GET['halaman']=='history'){include 'history.php';}
          elseif($_GET['halaman']=='detail_produk'
          ){include 'detail_produk.php';}
        }
      ?>
    </div>
    
    <footer class="footer mt-auto py-3 bg-body-tertiary ">
      <div class="container">
        <span class="text-body-secondary"><i class="bi bi-c-circle"></i> Solverra IT <?= date('Y')?></span>
      </div>
    </footer>
    <script
      src="https://code.jquery.com/jquery-3.7.1.js"
      integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"
      integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
      integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
      crossorigin="anonymous"
    ></script>

    <script>
        
      $(document).ready(function () {
        $(".owl-carousel").owlCarousel({
            loop:true,
            center:true,
            margin:10,
            nav: true,
            lazyLoad:true,
            responsiveClass:true,
            responsive:{
                0:{
                    items:2,
                    nav:true
                },
                600:{
                    items:2,
                    nav:false
                },
                1000:{
                    items:4,
                    nav:true,
                    loop:true
                }
            }
        });
      });
    </script>
  </body>
</html>
