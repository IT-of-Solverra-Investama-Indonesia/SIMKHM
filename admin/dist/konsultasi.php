<?php if (!isset($_GET['room'])) { ?>
    <h3>Daftar Chat Konsultasi Belum Terbalas <sup><?php if (!isset($_GET['dokter'])) { ?><a href="index.php?halaman=konsultasi&dokter" class="btn btn-primary btn-sm">Pasien Dokter</a><?php } else { ?><a href="index.php?halaman=konsultasi" class="btn btn-dark btn-sm">Kembali</a><?php } ?></sup></h3>
    <?php
    if (isset($_GET['dokter'])) {
        $getRoom = $koneksi->query("SELECT * FROM room_konsultasi WHERE dokter = '" . $_SESSION['dokter_rawat'] . "' ORDER BY created_at DESC");
    } else {
        $getRoom = $koneksi->query("SELECT * FROM room_konsultasi WHERE dokter = '' ORDER BY created_at DESC");
    }
    foreach ($getRoom as $data) {
    ?>
        <?php
        $getPasien = $koneksi->query("SELECT * FROM pasien_kosmetik WHERE idpasien = '$data[pasien_id]'")->fetch_assoc();
        $getPesan = $koneksi->query("SELECT * FROM chat_konsultasi WHERE room_id = '$data[id]' ORDER BY created_at DESC LIMIT 1")->fetch_assoc();
        ?>
        <?php if (!empty($getPesan)) { ?>
            <a href="index.php?halaman=konsultasi&room=<?= $data['id'] ?>" class="text-dark link-dark">
                <div class="card shadow p-2 mb-2">
                    <h5 class="my-0"><?= $getPasien['nama_lengkap'] ?></h5>
                    <span>
                        [<?= date('d/m/Y H:i', strtotime($getPesan['created_at'])) ?>] <?= $getPesan['chat'] ?>
                    </span>
                </div>
            </a>
        <?php } ?>
    <?php } ?>
<?php } else { ?>
    <?php
    $getRoom = $koneksi->query("SELECT * FROM room_konsultasi WHERE id = '$_GET[room]' ORDER BY created_at DESC")->fetch_assoc();

    if (isset($_GET['room'])) {
        if($getRoom['dokter']==''){
            $koneksi->query("UPDATE room_konsultasi SET dokter = '" . $_SESSION['dokter_rawat'] . "', admin_id = '" . $_SESSION['admin']['idadmin'] . "' WHERE id = '$_GET[room]'");
        }else{
            // var_dump($getRoom ); 
            // var_dump('getroom : '.$getRoom['dokter']);
            // var_dump( 'session : '.$_SESSION['dokter_rawat']);
            if ($getRoom['dokter'] != $_SESSION['dokter_rawat']) {
                echo  "
               <script>
                  alert('sudah diambil oleh dokter $getRoom[dokter], Tetap semangat ya dok 😊');
                  document.location.href='index.php?halaman=konsultasi';
                </script>
              ";
              
            }
        }
    }
    ?>
    <?php
        $getPasien = $koneksi->query("SELECT * FROM pasien_kosmetik WHERE idpasien = '$getRoom[pasien_id]'")->fetch_assoc();
    ?>
    <style>
        #cardChat {
            height: 410px;
            overflow-y: scroll;
            overflow-x: hidden;
        }

        #dengan {
            font-size: 20px;
            align-items: end;
        }

        @media only screen and (max-width: 480px) {
            #dengan {
                font-size: 12px;
                align-items: end;
            }

            #cardChat {
                height: 460px;
            }
        }
    </style>
    <div class="row mb-1">
        <div class="col-12">
            <a href="index.php?halaman=konsultasi" style="max-width: 100px;" class="btn btn-sm btn-dark">Kembali</a>
            <span id="dengan"><b>Pasien: <?= $getPasien['nama_lengkap'] ?></b></span>
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
                    formData.append('room', '<?= htmlspecialchars($_GET['room']) ?>');
                    formData.append('dari', 'dokter');
                    formData.append('dari_id', '<?= $_SESSION['admin']['idadmin'] ?>');
                    formData.append('type', 'file');
                    formData.append('message', this.files[0].name);
    
                    $.ajax({
                        url: '../../kosmetik/chat_api.php',
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
                    url: '../../kosmetik/chat_api.php?getPesan=<?= htmlspecialchars($_GET['room']) ?>',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#chatBox').html('');
                        data.forEach(function(message) {
                            var messageDiv = document.createElement('div');
                            messageDiv.className = 'row';
    
                            var content = '';
                            if (message.type_chat === 'file') {
                                content = '<img src="../../kosmetik/' + message.chat + '" class="img-fluid">';
                            } else {
                                content = '<p class="my-0">' + message.chat + '</p>';
                            }
    
                            if (message.dari != 'pasien') {
                                messageDiv.innerHTML = `
                                    <div class="col-3"></div>
                                    <div class="col-9">
                                        <div class="card mb-2 p-2">${content}</div>
                                    </div>
                                `;
                            } else {
                                messageDiv.innerHTML = `
                                    <div class="col-9">
                                        <div class="card mb-2 p-2">${content}</div>
                                    </div>
                                    <div class="col-3"></div>
                                `;
                            }
    
                            $('#chatBox').append(messageDiv);
                        });
    
                        // Scroll ke bagian bawah chatBox
                        if (isFirstLoad) {
                            $('#cardChat').scrollTop($('#cardChat')[0].scrollHeight);
                            isFirstLoad = false;
                        }
                    }
                });
            }
    
            // Panggil fetchMessages setiap 5 detik
            setInterval(fetchMessages, 1000);
    
            // Kirim pesan saat form disubmit
            $('#chatForm').submit(function(event) {
                event.preventDefault();
    
                var formData = new FormData(this);
                formData.append('room', '<?= htmlspecialchars($_GET['room']) ?>');
                formData.append('dari', 'dokter');
                formData.append('dari_id', '<?= $_SESSION['admin']['idadmin'] ?>');
    
                var fileInput = $('#fileInput')[0];
                if (fileInput.files.length > 0) {
                    formData.append('type', 'file');
                    formData.append('message', fileInput.files[0].name);
                } else {
                    formData.append('type', 'text');
                    formData.append('message', $('#message').val());
                }
    
                $.ajax({
                    url: '../../kosmetik/chat_api.php',
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        fetchMessages(); // Ambil pesan terbaru
                        if (response.status === 'success') {
                            // Pesan berhasil dikirim
                        } else {
                            alert('Gagal mengirim pesan');
                        }
                    }
                });
                $('#message').val(''); // Bersihkan field pesan
                $('#fileInput').val(''); // Bersihkan input file
            });
            isFirstLoad = true;
    
            // Panggil fetchMessages saat halaman pertama kali dimuat
            fetchMessages();
        });
    </script>


<?php } ?>