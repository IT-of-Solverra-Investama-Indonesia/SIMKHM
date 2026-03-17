<?php if (!isset($_GET['mulai']) and !isset($_GET['hingga']) and !isset($_GET['val'])) { ?>
    <h3>Poli Daerah</h3>
    <div class="card shadow p-2 mb-2">
        <form method="post">
            <div class="row">
                <div class="col-md-4">
                    <label>Mulai Tanggal</label>
                    <input type="date" class="form-control" name="mulai">
                </div>
                <div class="col-md-4">
                    <label>Hingga Tanggal</label>
                    <input type="date" class="form-control" name="hingga">
                </div>
                <div class="col-md-4">
                    <br>
                    <button type="submit" name="desa" class="btn btn-primary">PerDesa</button>
                    <button type="submit" name="diagnosis" class="btn btn-success">PerDiagnosis</button>
                </div>
            </div>
        </form>
    </div>
    <?php
    if (isset($_POST['desa'])) {
        $tipe = 'Desa';
        $getData = $koneksi->query("SELECT *, COUNT(*) AS jumlah FROM registrasi_rawat INNER JOIN pasien ON pasien.nama_lengkap = registrasi_rawat.nama_pasien WHERE registrasi_rawat.jadwal >= '$_POST[mulai]' AND registrasi_rawat.jadwal <= '$_POST[hingga]' AND (status_antri = 'Datang' OR status_antri = 'Pembayaran') GROUP BY pasien.kelurahan");
    }
    if (isset($_POST['diagnosis'])) {
        $tipe = 'Diagnosis';
        $getData = $koneksi->query("SELECT *, COUNT(*) AS jumlah, rekam_medis.diagnosis AS diagnosis_dokter FROM registrasi_rawat INNER JOIN rekam_medis ON rekam_medis.jadwal=registrasi_rawat.jadwal WHERE registrasi_rawat.jadwal >= '$_POST[mulai]' AND registrasi_rawat.jadwal <= '$_POST[hingga]' AND (status_antri = 'Datang' OR status_antri = 'Pembayaran') GROUP BY rekam_medis.diagnosis");
    }
    ?>
    <?php if (isset($_POST['desa']) or isset($_POST['diagnosis'])) { ?>
        <div class="card shadow p-2">
            <h5>Dicari Dari Tanggal <?= $_POST['mulai'] ?> Hingga <?= $_POST['hingga'] ?></h5>
            <table class="table" id="myTable">
                <thead>
                    <tr>
                        <th><?= $tipe ?></th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($getData as $data) { ?>
                        <tr>
                            <td><?= $data['kelurahan'] ?? $data['diagnosis_dokter'] ?></td>
                            <td>
                                <a href="index.php?halaman=polidaerah&mulai=<?= $_POST['mulai'] ?>&hingga=<?= $_POST['hingga'] ?>&tipe=<?= $tipe ?>&val=<?= $data['kelurahan'] ?? $data['diagnosis_dokter'] ?>"><?= $data['jumlah'] ?></a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
<?php } else { ?>
    <?php
    if ($_GET['tipe'] != 'Diagnosis') {
        $getData = $koneksi->query("SELECT *, registrasi_rawat.jadwal AS jadwalFinal FROM registrasi_rawat INNER JOIN pasien ON pasien.nama_lengkap = registrasi_rawat.nama_pasien WHERE registrasi_rawat.jadwal >= '$_GET[mulai]' AND registrasi_rawat.jadwal <= '$_GET[hingga]' AND pasien.kelurahan = '$_GET[val]' AND (status_antri = 'Datang' OR status_antri = 'Pembayaran')");
    } else {
        $getData = $koneksi->query("SELECT *, registrasi_rawat.jadwal AS jadwalFinal, rekam_medis.diagnosis AS diagnosis_dokter FROM registrasi_rawat INNER JOIN rekam_medis ON rekam_medis.jadwal=registrasi_rawat.jadwal INNER JOIN pasien ON pasien.no_rm = rekam_medis.norm WHERE registrasi_rawat.jadwal >= '$_GET[mulai]' AND registrasi_rawat.jadwal <= '$_GET[hingga]' AND rekam_medis.diagnosis = '$_GET[val]' AND (status_antri = 'Datang' OR status_antri = 'Pembayaran')");
    }

    // Penampung array JSON untuk Broadcast (Tidak mengubah logika asli)
    $listPasienBroadcast = [];
    ?>
    <h4>Poli <?= $_GET['tipe'] ?> <?= $_GET['val'] ?> Pada Tanggal <?= $_GET['mulai'] ?> Hingga <?= $_GET['hingga'] ?></h4>

    <div class="mb-3 mt-3">
        <button type="button" class="btn btn-warning" id="btnKirimAll">
            Kirim Pesan ke Semua Pasien
        </button>
    </div>

    <div class="card shadow p-2">
        <table class="table" id="myTable">
            <thead>
                <tr>
                    <th>No RM</th>
                    <th>Nama Pasien</th>
                    <th>Alamat</th>
                    <th>Jadwal</th>
                    <th><?= $_GET['tipe'] ?></th>
                    <th>WA</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($getData as $data) { ?>
                    <?php
                    $hp = $data["nohp"];
                    $hp2 = substr($hp, 1);
                    $hp = '62' . $hp2;

                    // Masukkan data ke array untuk fitur Broadcast (Kirim Semua)
                    if ($hp != '62') {
                        $listPasienBroadcast[] = [
                            'hp' => $hp,
                            'nama' => $data['nama_pasien']
                        ];
                    }
                    ?>
                    <tr>
                        <td><?= $data['no_rm'] ?></td>
                        <td><?= $data['nama_pasien'] ?></td>
                        <td>
                            <?= $data['kota'] ?>, <?= $data['kecamatan'] ?>, <?= $data['kelurahan'] ?> (<?= $data['kode_pos'] ?>), <?= $data['alamat'] ?>
                        </td>
                        <td><?= $data['jadwalFinal'] ?></td>
                        <td><?= $data['kelurahan'] ?? $data['diagnosis_dokter'] ?></td>
                        <td><a href="https://wa.me/<?= $hp ?? '' ?>" target="_blank"><?= $hp ?? '' ?></a></td>
                        <td>
                            <button type="button" class="btn btn-primary btn-sm btn-kirim-pesan"
                                data-hp="<?= $hp ?? '' ?>"
                                data-nama="<?= $data['nama_pasien'] ?>">
                                Kirim Pesan
                            </button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
<?php } ?>

<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css" />

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>

<div class="modal fade" id="modalKirimPesan" tabindex="-1" aria-labelledby="modalKirimPesanLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalKirimPesanLabel">Kirim Pesan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formKirimPesan">
                <div class="modal-body">
                    <input type="hidden" id="inputHp" name="hp">
                    <input type="hidden" id="inputNama" name="nama">

                    <div class="mb-3">
                        <label>Kirim Ke:</label>
                        <input type="text" class="form-control" id="displayPasien" readonly disabled>
                    </div>

                    <div class="mb-3">
                        <label>Isi Pesan:</label>
                        <textarea class="form-control" id="inputPesan" name="pesan" rows="4" placeholder="Ketik isi pesan..." required></textarea>
                    </div>

                    <div class="mb-3 d-none" id="progressContainer">
                        <label>Proses Pengiriman:</label>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                        </div>
                        <small class="text-muted mt-1 d-block" id="progressText">Menunggu...</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success" id="btnSubmitPesan">
                        <span class="spinner-border spinner-border-sm d-none" id="loadingPesan" role="status" aria-hidden="true"></span>
                        <span id="textBtnSubmit">Kirim Pesan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            order: [
                [1, 'desc']
            ],
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excelHtml5',
                text: '<i class="bi bi-file-earmark-excel"></i> Export Excel',
                className: 'btn btn-success btn-sm',
                title: 'Data Poli Daerah - <?= date("d-m-Y") ?>',
                exportOptions: {
                    columns: ':visible'
                }
            }],
            language: {
                "emptyTable": "Tidak ada data yang tersedia pada tabel ini",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                "infoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                "lengthMenu": "Tampilkan _MENU_ entri",
                "loadingRecords": "Sedang memuat...",
                "processing": "Sedang memproses...",
                "search": "Cari:",
                "zeroRecords": "Tidak ditemukan data yang sesuai",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            }
        });

        // Ambil data untuk broadcast
        let broadcastList = <?= isset($listPasienBroadcast) ? json_encode($listPasienBroadcast) : '[]' ?>;
        let isBroadcastMode = false;

        $('#myTable').on('click', '.btn-kirim-pesan', function() {
            let hp = $(this).data('hp');
            let nama = $(this).data('nama');

            if (!hp || hp == '62') {
                alert('Nomor HP tidak valid untuk pasien ini.');
                return;
            }

            isBroadcastMode = false;
            $('#inputHp').val(hp);
            $('#inputNama').val(nama);
            $('#displayPasien').val(nama + ' (' + hp + ')');
            $('#inputPesan').val('');

            $('#progressContainer').addClass('d-none');
            $('#textBtnSubmit').text('Kirim Pesan');

            var myModal = new bootstrap.Modal(document.getElementById('modalKirimPesan'));
            myModal.show();
        });

        $('#btnKirimAll').on('click', function() {
            if (broadcastList.length === 0) {
                alert('Tidak ada pasien dengan nomor HP yang valid di tabel ini.');
                return;
            }

            isBroadcastMode = true;
            $('#displayPasien').val(broadcastList.length + ' Pasien (Broadcast Keseluruhan)');
            $('#inputPesan').val('');

            $('#progressContainer').removeClass('d-none');
            $('.progress-bar').css('width', '0%').text('0%');
            $('#progressText').text('Siap mengirim ke ' + broadcastList.length + ' kontak.');
            $('#textBtnSubmit').text('Kirim Pesan');

            var myModal = new bootstrap.Modal(document.getElementById('modalKirimPesan'));
            myModal.show();
        });

        $('#formKirimPesan').on('submit', async function(e) {
            e.preventDefault();

            let pesan = $('#inputPesan').val();
            let btnSubmit = $('#btnSubmitPesan');
            let loading = $('#loadingPesan');

            btnSubmit.prop('disabled', true);
            loading.removeClass('d-none');

            if (!isBroadcastMode) {
                $.ajax({
                    url: '../api/api_send_qiscus.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            alert('Berhasil! Pesan telah terkirim ke ' + response.nama);
                            $('#modalKirimPesan').modal('hide');
                        } else {
                            alert('Gagal: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Terjadi kesalahan sistem. Silakan cek koneksi/log server.');
                        console.error(xhr.responseText);
                    },
                    complete: function() {
                        btnSubmit.prop('disabled', false);
                        loading.addClass('d-none');
                    }
                });
            } else {
                let total = broadcastList.length;
                let successCount = 0;
                let failCount = 0;

                $('#progressText').text('Memproses pengiriman... Harap jangan tutup jendela ini.');

                for (let i = 0; i < total; i++) {
                    let target = broadcastList[i];

                    try {
                        let response = await $.ajax({
                            url: '../api/api_send_qiscus.php',
                            type: 'POST',
                            data: {
                                hp: target.hp,
                                nama: target.nama,
                                pesan: pesan
                            },
                            dataType: 'json'
                        });

                        if (response.status === 'success') successCount++;
                        else failCount++;
                    } catch (err) {
                        failCount++;
                    }

                    let percent = Math.round(((i + 1) / total) * 100);
                    $('.progress-bar').css('width', percent + '%').text(percent + '%');
                    $('#progressText').text(`Terkirim: ${i + 1} / ${total} kontak`);
                }

                alert(`Broadcast Selesai!\nBerhasil: ${successCount} kontak\nGagal: ${failCount} kontak`);
                btnSubmit.prop('disabled', false);
                loading.addClass('d-none');
                $('#textBtnSubmit').text('Broadcast Selesai');
            }
        });
    });
</script>