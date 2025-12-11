<div>
    <?php
    $single = $koneksi->query("SELECT * FROM pasien WHERE idpasien = '" . $_GET['id'] . "'")->fetch_assoc();

    // Handle POST registrasi booking
    if (isset($_POST['registrasiBooking'])) {
        $id_pasien = $_GET['id']; // Dari URL parameter
        $nama_pasien = $single['nama_lengkap']; // Dari data pasien
        $jadwal_booking = $_POST['jadwal_booking']; // Dari form
        $nomor_antrian = $_POST['antrian']; // Dari form
        $carabayar = $_POST['carabayar']; // Dari form

        // Insert ke database
        $insert = $koneksi->prepare("INSERT INTO registrasi_booking (id_pasien, nama_pasien, carabayar, jadwal_booking, nomor_antrian) VALUES (?, ?, ?, ?, ?)");
        $insert->bind_param("sssss", $id_pasien, $nama_pasien, $carabayar, $jadwal_booking, $nomor_antrian);

        if ($insert->execute()) {
            echo "<script>
                alert('Registrasi booking berhasil!');
                window.location.href='?halaman=registrasibooking&id=" . $_GET['id'] . "';
            </script>";
        } else {
            echo "<script>
                alert('Registrasi booking gagal: " . $koneksi->error . "');
            </script>";
        }
        $insert->close();
    }
    ?>
    <div class="card shadow p-2 mb-2">
        <h5 class="card-title">
            Registrasi Booking <br>
            <?= $single['no_rm'] . " - " . $single['nama_lengkap']; ?>
        </h5>
    </div>
    <div class="card shadow p-2 mt-0">
        <form method="POST">
            <div class="row g-1">
                <div class="col-4">
                    <label for="">Booking</label>
                    <input type="datetime-local" name="jadwal_booking" id="jadwal" class="form-control form-control-sm">
                </div>
                <div class="col-4">
                    <label for="">Antrian</label>
                    <select id="antrian" name="antrian" class="form-control form-control-sm" required>
                        <option value="" width="40">Silahkan Pilih Antrian</option>
                    </select>
                    <small id="loadingText" class="form-text text-muted" style="display: none;">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Memuat antrian...
                    </small>
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                    <script>
                        $(document).ready(function() {
                            $('#jadwal').change(function() {
                                var tanggal = $(this).val();

                                // Validasi input tanggal
                                if (!tanggal || tanggal.trim() === '') {
                                    $('#antrian').html('<option value="">Silahkan Pilih Antrian</option>');
                                    return;
                                }

                                // Tampilkan loading
                                $('#loadingText').show();
                                $('#antrian').prop('disabled', true);
                                $('#antrian').html('<option value="">Memuat antrian...</option>');

                                $.ajax({
                                    url: '../../pasien/antrian_api.php',
                                    type: 'POST',
                                    data: {
                                        tanggal: tanggal,
                                        jenis: true
                                    },
                                    beforeSend: function() {
                                        // Sudah ditangani di atas
                                    },
                                    success: function(response) {
                                        // Sembunyikan loading
                                        $('#loadingText').hide();
                                        $('#antrian').prop('disabled', false);

                                        // Cek apakah response kosong atau hanya whitespace
                                        if (response && response.trim() !== '') {
                                            $('#antrian').html(response);
                                        } else {
                                            $('#antrian').html('<option value="">Tidak ada antrian tersedia</option>');
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        // Sembunyikan loading dan tampilkan error
                                        $('#loadingText').hide();
                                        $('#antrian').prop('disabled', false);
                                        $('#antrian').html('<option value="">Error memuat antrian</option>');
                                        console.error('Error:', error);
                                    },
                                    timeout: 30000 // Timeout 30 detik
                                });
                            });
                        });
                    </script>
                </div>
                <div class="col-4">
                    <label for="">Cara Bayar</label>
                    <select id="carabayarr" name="carabayar" class="form-control form-control-sm" required>
                        <option hidden>Pilih Pembayaran</option>
                        <option value="bpjs">bpjs</option>
                        <option selected value="umum">umum</option>
                        <option value="malam">malam</option>
                        <option value="spesialis anak">spesialis anak</option>
                        <option value="spesialis penyakit dalam">spesialis penyakit dalam</option>
                        <option value="gigi umum">gigi umum</option>
                        <option value="gigi bpjs">gigi bpjs</option>
                        <option value="kosmetik">kosmetik</option>
                    </select>
                </div>
                <div class="col-12">
                    <div class="float-end">
                        <button type="submit" name="registrasiBooking" class="btn btn-sm btn-primary mt-3"><i class="bi bi-check2-circle"></i> Registrasi Booking</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>