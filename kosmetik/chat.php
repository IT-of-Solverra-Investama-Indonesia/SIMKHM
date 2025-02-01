<?php session_start(); ?>
<?php
// error_reporting(0);
if (!isset($_SESSION['kosmetik']['nama_lengkap'])) {
    header("Location: login.php");
    exit();
}
if (isset($_GET['logout'])) {
    // Hapus semua data sesi
    session_unset();
    // Hancurkan sesi
    session_destroy();
    // Redirect ke halaman login
    header("Location: login.php");
    exit();

}

include "function.php";

$nik = $_SESSION['kosmetik']['no_identitas'];
$norm = $_SESSION['kosmetik']['no_rm'];

$ambil = $koneksi->query("SELECT * FROM pasien_kosmetik WHERE no_identitas='$nik' or no_rm='$norm';");
$admin = $ambil->fetch_assoc();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>KHM WONOREJO</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <link rel="icon" type="image/png" href="../admin/dist/assets/img/logokhm.png" />
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" integrity="sha384-4LISF5TTJX/fLmGSxO53rV4miRxdg84mZsxmO8Rx5jGtp/LbrixFETvWa5a6sESd" crossorigin="anonymous">
  <link href="assets/css/style.css" rel="stylesheet"> -->

    <!-- Favicons -->
    <link href="assets/img/khm.png" rel="icon">
    <link href="assets/img/icons8-hospital-3-100.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Vendor JS Files -->
    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/chart.js/chart.umd.js"></script>
    <script src="assets/vendor/echarts/echarts.min.js"></script>
    <script src="assets/vendor/quill/quill.min.js"></script>
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>

    <!-- DATATABLES -->
    <link href="https://code.jquery.com/jquery-3.5.1.js" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.2/js/dataTables.bootstrap5.min.js" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        #cardChat{
            height: 400px;
            overflow-y: scroll;
            overflow-x: hidden  ;
        }
        #dengan{
            font-size: 20px;
            align-items: end;
        }
        @media only screen and (max-width: 480px){
            #cardChat{
                height: 460px;
            }
            #dengan{
            font-size: 12px;
            align-items: end;
        }
        }
    </style>
</head>

<body>
    <main>
        <div class="container py-4">
            <h3>Konsultasi <?= $_SESSION['kosmetik']['nama_lengkap']?></h3>
            <?php if(!isset($_GET['room'])){?> 
                <a href="index.php" class="btn btn-sm btn-dark" style="margin-bottom: 2px;"><i class="bi bi-arrow-left-circle"></i> Kembali</a>
                <div class="card shadow w-100 p-3" style="overflow-y: scroll; height: 100%;">
                    <div class="row">
                        <?php if($_SESSION['kosmetik']['tgl_lahir'] != '0000-00-00' AND $_SESSION['kosmetik']['jenis_kelamin']!='0' AND $_SESSION['kosmetik']['no_identitas'] != ''){?>
                            <div class="col-md-12">
                                <a href="chat.php?makeNewRoom" style="text-decoration: none;">
                                    <div class="card p-2 mb-3" style="color: green; max-height: 50px;" >
                                        <center>
                                            <h5>[+] Konsultasi Baru</h5>
                                        </center>
                                    </div>
                                </a>
                            </div>
                        <?php }else{?>
                            <div class="col-md-12">
                                <div class="card p-2 mb-3" style="color: green; max-height: 50px;" data-bs-toggle="modal" data-bs-target="#dataDiri">
                                    <center>
                                        <h5>Lengkapi Data Diri Sebelum Konsultasi</h5>
                                    </center>
                                </div>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="dataDiri" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Lengkapi Data Diri</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <form method="post">
                                      <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12 mb-2">
                                              <p class="mb-0" align="left">Jenis Kelamin</p>
                                              <select id="inputState" name="jenis_kelamin" value="<?= $_SESSION['kosmetik']['jenis_kelamin']?>" required class="form-select">
                                                <option selected>Pilih</option>
                                                <option value="1">Laki-Laki</option>
                                                <option value="2">Perempuan</option>
                                              </select>
                                            </div>
                                            <div class="col-md-12 mb-2">
                                              <p class="mb-0" align="left">Tanggal Lahir</p>
                                              <input type="date" class="form-control mb-2" name="tgl_lahir" value="<?= $_SESSION['kosmetik']['tgl_lahir']?>" id="">
                                            </div>
                                            <div class="col-md-12 mb-2">
                                              <p class="mb-0" align="left">NIK</p>
                                              <input type="number" class="form-control mb-2" name="no_identitas" value="<?= $_SESSION['kosmetik']['no_identitas']?>" id="">
                                            </div>
                                        </div>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" name="savedatadiri" class="btn btn-success">Simpan</button>
                                      </div>
                                  </form>
                                </div>
                              </div>
                            </div>
                            <?php
                                if(isset($_POST['savedatadiri'])){
                                    $jenis_kelamin = htmlspecialchars($_POST['jenis_kelamin']);
                                    $tgl_lahir = htmlspecialchars($_POST['tgl_lahir']);
                                    $no_identitas = htmlspecialchars($_POST['no_identitas']);

                                    $koneksi->query("UPDATE pasien_kosmetik SET jenis_kelamin='$jenis_kelamin', tgl_lahir='$tgl_lahir', no_identitas='$no_identitas' WHERE idpasien='".$_SESSION['kosmetik']['idpasien']."'");

                                    $getNewPasien = $koneksi->query("SELECT * FROM pasien_kosmetik WHERE idpasien = '".$_SESSION['kosmetik']['idpasien']."'")->fetch_assoc();
                                    $_SESSION['kosmetik'] = '';
                                    
                                    $_SESSION['kosmetik'] = $getNewPasien;

                                    echo "
                                        <script>
                                            alert('Berhasil Data Diri');
                                            document.location.href='chat.php';
                                        </script>
                                    ";
                                }
                            ?>
                        <?php }?>
                        <?php
                            $getRoom = $koneksi->query("SELECT * FROM room_konsultasi WHERE pasien_id = '".$_SESSION['kosmetik']['idpasien']."' ORDER BY created_at DESC");
                            foreach($getRoom as $data){
                        ?>
                            <div class="col-md-12">
                                <a href="chat.php?room=<?= $data['id']?>" style="text-decoration: none;">
                                    <div class="card p-2 mb-3" style="color: green; max-height: 50px;" >
                                        <center>
                                            <h5><?= date('d F Y H:i', strtotime($data['created_at']))?></h5>
                                        </center>
                                    </div>
                                </a>
                            </div>
                        <?php }?>
                    </div>
                </div>
            <?php }else{?>
                <?php
                    $getRoom = $koneksi->query("SELECT * FROM room_konsultasi WHERE id = '".htmlspecialchars($_GET['room'])."' ORDER BY created_at DESC")->fetch_assoc();
                ?>
                <div class="row mb-1">
                    <div class="col-md-12">
                        <a href="chat.php" class="btn btn-sm btn-dark mb-1">Kembali</a>
                        <?php if($getRoom['dokter'] != ''){?>
                            <span id="dengan"><b>Dokter: <?= $getRoom['dokter']?></b></spanspan>
                        <?php }else{?>
                            <span id="dengan">Menunggu Respond Dokter</span>
                        <?php }?>
                    </div>
                </div>
                <div class="card shadow w-100 mb-1 p-2" id="cardChat">
                    <div>
                        <div class="row" id="chatBox">

                        </div>
                    </div>
                </div>
                <div class="card p-2 fixed ">
                    <form id="chatForm" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-9">
                                <textarea class="form-control w-100" id="message" style="height: 10px;"></textarea>
                                <input type="file" id="fileInput" name="file" accept="image/*" style="display: none;">
                            </div>
                            <div class="col-3 d-flex align-items-center">
                                <h4 class="m-0 me-1" id="paperclip">
                                    <i class="bi bi-paperclip"></i>
                                </h4>
                                <button type="submit" class="btn btn-success"><i class="bi bi-send"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
                <script>
                    $(document).ready(function() {
                        var isFirstLoad = true;
                        // Event handler untuk klik ikon paperclip
                        $('#paperclip').on('click', function() {
                            $('#fileInput').click();
                        });
                    
                        // Event handler untuk perubahan file
                        $('#fileInput').on('change', function() {
                            if (this.files && this.files[0]) {
                                var formData = new FormData();
                                formData.append('file', this.files[0]);
                                formData.append('room', '<?= htmlspecialchars($_GET['room'])?>');
                                formData.append('dari', 'pasien');
                                formData.append('dari_id', '<?= $_SESSION['kosmetik']['idpasien']?>');
                                formData.append('type', 'file');
                                formData.append('message', this.files[0].name);
                    
                                $.ajax({
                                    url: 'chat_api.php',
                                    method: 'POST',
                                    data: formData,
                                    contentType: false,
                                    processData: false,
                                    dataType: 'json',
                                    success: function(response) {
                                        $('#fileInput').val(''); // Bersihkan input file
                                        fetchMessages(); // Ambil pesan terbaru
                                        if (response.status === 'success') {
                                            // Pesan berhasil dikirim
                                        } else {
                                            alert('Gagal mengirim pesan');
                                        }
                                    }
                                });
                            }
                        });
                    
                        // Fungsi untuk mengambil pesan
                        function fetchMessages() {
                            $.ajax({
                                url: 'chat_api.php?getPesan=<?= htmlspecialchars($_GET['room'])?>',
                                method: 'GET',
                                dataType: 'json',
                                success: function(data) {
                                    $('#chatBox').html('');
                                    data.forEach(function(message) {
                                        var messageDiv = document.createElement('div');
                                        messageDiv.className = 'row';
                    
                                        var content = '';
                                        if (message.type_chat === 'file') {
                                            content = '<img src="' + message.chat + '" class="img-fluid">';
                                        } else {
                                            content = '<p class="my-0">' + message.chat + '</p>';
                                        }
                    
                                        if (message.dari != 'pasien') {
                                            messageDiv.innerHTML = `
                                                <div class="col-9">
                                                    <div class="card mb-2 p-2">${content}</div>
                                                </div>
                                                <div class="col-3"></div>
                                            `;
                                        } else {
                                            messageDiv.innerHTML = `
                                                <div class="col-3"></div>
                                                <div class="col-9">
                                                    <div class="card mb-2 p-2">${content}</div>
                                                </div>
                                            `;
                                        }
                    
                                        $('#chatBox').append(messageDiv);
                                    });
                    
                                    // Scroll ke bagian bawah chatBox
                                    if (isFirstLoad == true) {
                                        // Scroll ke bagian bawah chatBox hanya saat pertama kali dimuat
                                        $('#cardChat').scrollTop($('#cardChat')[0].scrollHeight);
                                        isFirstLoad = false;
                                    }
                                    // $('#cardChat').scrollTop($('#cardChat')[0].scrollHeight);
                                }
                            });
                        }
                    
                        // Panggil fetchMessages setiap 5 detik
                        setInterval(fetchMessages, 5000);
                    
                        // Kirim pesan saat form disubmit
                        $('#chatForm').submit(function(event) {
                            event.preventDefault();
                    
                            var formData = new FormData(this);
                            
                            formData.append('room', '<?= htmlspecialchars($_GET['room'])?>');
                            formData.append('dari', 'pasien');
                            formData.append('dari_id', '<?= $_SESSION['kosmetik']['idpasien']?>');
                    
                            var fileInput = $('#fileInput')[0];
                            if (fileInput.files.length > 0) {
                                formData.append('type', 'file');
                                formData.append('message', fileInput.files[0].name);
                            } else {
                                formData.append('type', 'text');
                                formData.append('message', $('#message').val());
                            }
                         

                            $.ajax({
                                url: 'chat_api.php',
                                method: 'POST',
                                data: formData,
                                contentType: false,
                                processData: false,
                                dataType: 'json',
                                success: function(response) {
                                    $('#message').val(''); // Bersihkan field pesan
                                    $('#fileInput').val(''); // Bersihkan input file
                                    fetchMessages(); // Ambil pesan terbaru
                                    if (response.status === 'success') {
                                        // Pesan berhasil dikirim
                                       
                                    } else {
                                        alert('Gagal mengirim pesan');
                                    }
                                }
                            });
                        

                        });
                        isFirstLoad = true;
                    
                        // Panggil fetchMessages saat halaman pertama kali dimuat
                        fetchMessages();
                    });

                </script>
            <?php }?>
        </div>
        <!-- <center>
            <h3 style="color:green">Menu Chat Pasien</h3>
        </center>
        <div class="container">
            <div class="card">

                <div class="card-left">
                    <h5>Data Pasien</h5>
                    <p>Aku.</p>
                </div>
                <div class="card-right">
                    <h5>Riwayat Chat</h5>
                    <div class="content">1</div>
                    <div class="content">1</div>
                    <div class="content">1</div>
                    <div class="content">1</div>
                    <div class="content">1</div>
                    <div class="content">1</div>
                    <div class="content">1</div>
                    <div class="content">1</div>
                    <div class="content">1</div>
                    <div class="content">1</div>
                    <div class="content">1</div>
                    <div class="content">1</div>
                    <div class="content">1</div>
                    <div class="content">1</div>
                    <div class="content">1</div>
                    <div class="content">1</div>
                    <div class="content">1</div>
                    <div class="content">1</div>
                    <div class="content">1</div>
                    <div class="content">1</div>
                    <div class="content">1</div>
                    <div class="content">1</div>
                    <div class="content">1</div>
                    <div class="content">1</div>
                    <div class="content">1</div>
                </div>
            </div>
        </div> -->

    </main>


    <?php
        if(isset($_GET['makeNewRoom'])){
            $id =  'id'.date('ymdhis').$_SESSION['kosmetik']['idpasien'];
            $pasien_id = $_SESSION['kosmetik']['idpasien'];
            $koneksi->query("INSERT INTO room_konsultasi (id, pasien_id, dokter, admin_id) VALUES ('$id', '$pasien_id', '','')");
            echo "
                <script>
                    document.location.href='chat.php?room=$id';
                </script>
            ";
        }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>
</body>

</html>