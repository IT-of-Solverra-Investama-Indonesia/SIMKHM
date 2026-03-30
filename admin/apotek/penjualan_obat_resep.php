<?php
// ==================== SECTION: ACTION HANDLERS ====================
// Semua action/logic diproses di bagian atas sebelum HTML dirender

// Inisialisasi keranjang jika belum ada
if (!isset($_SESSION['keranjang_obat_resep'])) {
    $_SESSION['keranjang_obat_resep'] = [];
}

// Handler 1: Masukkan Obat Tambahan ke Keranjang

if (isset($_GET['masukanKeranjang'])) {
    $getObatTambahan = $koneksi->query("SELECT * FROM obat_tambahan WHERE rekam_medis_id = '" . htmlspecialchars($_GET['id_rm']) . "' ");
    $getRM = $koneksi->query("SELECT * FROM rekam_medis WHERE id_rm = '" . htmlspecialchars($_GET['id_rm']) . "'")->fetch_assoc();
    foreach ($getObatTambahan as $obat) {
        $kode_obat = $obat['kode_obat'];
        $jumlah = $obat['jumlah'];
        $diskon = 0;

        $obatLokalSingle = $koneksi->query("SELECT * FROM apotek WHERE id_obat = '$kode_obat' ORDER BY idapotek DESC LIMIT 1")->fetch_assoc();
        $obatMasterSingle = $koneksimaster->query("SELECT * FROM master_obat WHERE kode_obat = '$kode_obat'")->fetch_assoc();

        $harga = ($obatLokalSingle['harga_beli'] * ($obatMasterSingle['margin_jual'] / 100));
        $sub = $harga * $jumlah;
        $subtotal = $sub - ($sub * $diskon / 100);

        // Tambahkan ke keranjang
        $_SESSION['keranjang_obat_resep'][] = [
            'kode' => $obatMasterSingle['kode_obat'],
            'nama' => $obatMasterSingle['obat_master'],
            'harga' => $harga,
            'jumlah' => $jumlah,
            'diskon' => $diskon,
            'subtotal' => $subtotal
        ];
    }

    echo "<script>
        alert('Obat berhasil ditambahkan ke keranjang');
        window.location.href = 'index.php?halaman=$_GET[halaman]&pembeli=" . $getRM['nama_pasien'] . "&id_rm=" . $getRM['id_rm'] . "';
    </script>";
    exit();
}

// Handler 2: Tambah Obat ke Keranjang (Manual)
if (isset($_POST['addToCart'])) {
    $kode_obat = $_POST['kode_obat'];
    $jumlah = $_POST['jumlah_obat'];
    $diskon = $_POST['diskon_obat'] ?? 0;

    $obatLokalSingle = $koneksi->query("SELECT * FROM apotek WHERE id_obat = '$kode_obat' ORDER BY idapotek DESC LIMIT 1")->fetch_assoc();
    $obatMasterSingle = $koneksimaster->query("SELECT * FROM master_obat WHERE kode_obat = '$kode_obat'")->fetch_assoc();

    $harga = $obatLokalSingle['harga_beli'] * ($obatMasterSingle['margin_resep'] / 100);
    $sub = $harga * $jumlah;
    $subtotal = $sub - ($sub * $diskon / 100);

    // Tambahkan ke keranjang
    $_SESSION['keranjang_obat_resep'][] = [
        'kode' => $obatMasterSingle['kode_obat'],
        'nama' => $obatMasterSingle['obat_master'],
        'harga' => $harga,
        'jumlah' => $jumlah,
        'diskon' => $diskon,
        'subtotal' => $subtotal
    ];
}

// Handler 3: Hapus Item dari Keranjang
if (isset($_GET['deleteCart'])) {
    $index = $_GET['deleteCart'];
    // Pastikan index valid
    if (isset($_SESSION['keranjang_obat_resep'][$index])) {
        unset($_SESSION['keranjang_obat_resep'][$index]);
        // Re-index array
        $_SESSION['keranjang_obat_resep'] = array_values($_SESSION['keranjang_obat_resep']);
    }
    echo "<script>
        window.location.href = 'index.php?halaman=penjualan_obat_resep';
    </script>";
    exit();
}

// Handler 4: Simpan Transaksi
if (isset($_POST['saveTransaction'])) {
    if (!empty($_SESSION['keranjang_obat_resep'])) {
        foreach ($_SESSION['keranjang_obat_resep'] as $index => $data) {
            $obatLokalSingleSave = $koneksi->query("SELECT * FROM apotek WHERE id_obat = '$data[kode]' ORDER BY idapotek DESC LIMIT 1")->fetch_assoc();
            $diskon = ($data['harga'] * $data['diskon'] / 100);

            $koneksi->query("INSERT INTO `penjualan_resep`(`nota`, `tgl_jual`, `kode_obat`, `nama_obat`, `harga_umum`, `diskon_obat`, `jumlah`, `harga_beli`, `akun`, `petugas`, `shift`) VALUES ('" . htmlspecialchars($_POST['nota']) . "','" . htmlspecialchars($_POST['tgl_jual']) . "','" . htmlspecialchars($data['kode']) . "','" . htmlspecialchars($data['nama']) . "','" . htmlspecialchars($data['harga']) . "','" . htmlspecialchars($diskon) . "','" . htmlspecialchars($data['jumlah']) . "','" . htmlspecialchars($obatLokalSingleSave['harga_beli']) . "','" . htmlspecialchars($_POST['akun']) . "','" . htmlspecialchars($_SESSION['admin']['namalengkap']) . "', '" . htmlspecialchars($_SESSION['shift']) . "')");
        }
    }
    if (isset($_GET['id_rm'])) {
        $koneksi->query("UPDATE obat_tambahan SET status_beli = 'Sudah Beli' WHERE rekam_medis_id = '" . htmlspecialchars($_GET['id_rm']) . "'");
    }

    unset($_SESSION['keranjang_obat_resep']);
    echo "<script>
        alert('Transaksi Berhasil Disimpan!');
        window.location.href = '../apotek/penjualan_obat_resep_nota.php?nota=" . htmlspecialchars($_POST['nota']) . "';
    </script>";
    exit();
}

// Handler 5: Tandai Selesai Rencana Rawat IGD
if (isset($_POST['tandaiSelesaiIsi'])) {
    $idigd = $_POST['idigd'];
    $nama_pasien = $_POST['nama_pasien'];
    $plan = $_POST['plan'];
    $koneksi->query("UPDATE igd SET rencana_rawat_at = CURRENT_TIMESTAMP WHERE idigd = '$idigd'");
    echo "<script>window.location.href = '?halaman=penjualan_obat_resep';</script>";
    exit();
}

// Hitung Total Keranjang
$total = 0;
if (!empty($_SESSION['keranjang_obat_resep'])) {
    foreach ($_SESSION['keranjang_obat_resep'] as $item) {
        $total += $item['subtotal'];
    }
}

// ==================== END SECTION: ACTION HANDLERS ====================
?>

<div class="row g-1">
    <div class="d-flex justify-content-between align-items-center my-0">
        <h5 class="card-title">Penjualan Obat Resep</h5>
        <!-- <button class="btn btn-sm btn-primary mb-0" data-bs-toggle="modal" data-bs-target="#pasienObatTambahan">Tambah Obat</button> -->
    </div>
    <div class="col-md-8">
        <!-- Modal -->
        <div class="modal fade" id="pasienObatTambahan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Obat Tambahan</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-sm" style="font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th>Nama Pasien</th>
                                        <th>Jadwal</th>
                                        <th>Status Pasien</th>
                                        <th>Obat</th>
                                        <th>Act</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $getPasienHaveObatTambahan = $koneksi->query("SELECT * FROM rekam_medis INNER JOIN obat_tambahan ON rekam_medis.id_rm = obat_tambahan.rekam_medis_id INNER JOIN registrasi_rawat ON rekam_medis.jadwal = registrasi_rawat.jadwal AND rekam_medis.norm = registrasi_rawat.no_rm  WHERE status_beli = 'Belum Beli' GROUP BY rekam_medis.id_rm ORDER BY rekam_medis.id_rm ASC");
                                    foreach ($getPasienHaveObatTambahan as $row) {
                                    ?>
                                        <tr>
                                            <td><?= $row['nama_pasien'] ?></td>
                                            <td><?= $row['jadwal'] ?></td>
                                            <td>
                                                <span style="font-size: 10px; margin: 1px;" onclick="alert('Pada <?= $row['datang_at'] ?>')" class="badge <?= $row['datang_at'] == null ? 'bg-danger' : 'bg-success' ?>">Datang</span>
                                                <span style="font-size: 10px; margin: 1px;" onclick="alert('Pada <?= $row['perawat_at'] ?>')" class="badge <?= $row['perawat_at'] == null ? 'bg-danger' : 'bg-success' ?>">KajianAwal</span> <br>
                                                <span style="font-size: 10px; margin: 1px;" onclick="alert('Pada <?= $row['dokter_at'] ?>')" class="badge <?= $row['dokter_at'] == null ? 'bg-danger' : 'bg-success' ?>">RekamMedis</span>
                                                <span style="font-size: 10px; margin: 1px;" onclick="alert('Pada <?= $row['pembayaran_at'] ?>')" class="badge <?= $row['pembayaran_at'] == null ? 'bg-danger' : 'bg-success' ?>">Pembayaran</span> <br>
                                                <span style="font-size: 10px; margin: 1px;" onclick="alert('Pada <?= $row['apoteker_check_at'] ?>')" class="badge <?= $row['apoteker_check_at'] == null ? 'bg-danger' : 'bg-success' ?>">Skrining Obat</span>
                                            </td>
                                            <td>
                                                <?php
                                                $getObat = $koneksi->query("SELECT * FROM obat_tambahan WHERE rekam_medis_id = '$row[id_rm]' ORDER BY id DESC");
                                                foreach ($getObat as $obat) {
                                                    echo "- " . $obat['nama_obat'] . " | " . $obat['jumlah'] . "Pcs <br>";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <a onclick="return confirm('Apakah anda yakin ingin masukan obat ini ke keranjang?')" href="index.php?halaman=<?= htmlspecialchars($_GET['halaman']) ?>&masukanKeranjang&id_rm=<?= $row['id_rm'] ?>" class="btn btn-sm btn-warning">Masukan Keranjang</a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Ganti 'targetID' dengan ID div tujuan Anda
                const targetElement = document.getElementById('RuangIsi');

                if (targetElement) {
                    targetElement.scrollIntoView();

                    // Opsional: Untuk animasi scroll halus
                    targetElement.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });


            // Ambil Obat Dari API yang support Pada Lokal yang bersangkutan
            document.addEventListener('DOMContentLoaded', async () => {
                try {
                    <?php

                    use BcMath\Number;

                    $apiUrlgetObat = '../apotek/api_getObatMasterLokal.php';
                    $apiUrlgetObat .= '?umum';
                    ?>
                    const obatData = await (await fetch('<?= $apiUrlgetObat ?>')).json();

                    document.querySelectorAll('.obat-select').forEach(select => {
                        // Simpan nilai yang sedang dipilih (jika ada)
                        const selectedValue = select.value;

                        // Buat array dari nilai option yang sudah ada
                        const existingOptions = Array.from(select.options).map(opt => opt.value);

                        // Filter data obat untuk hanya menambahkan yang belum ada
                        const newOptions = obatData.filter(obat =>
                            !existingOptions.includes(obat.kode_obat)
                        );

                        // Tambahkan option baru
                        newOptions.forEach(obat => {
                            select.add(new Option(obat.nama_obat, obat.kode_obat));
                        });

                        // Kembalikan nilai yang dipilih sebelumnya (jika masih ada)
                        if (selectedValue && select.querySelector(`option[value="${selectedValue}"]`)) {
                            select.value = selectedValue;
                        }
                    });
                } catch (error) {
                    console.error('Error:', error);
                }
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#obat_kode').select2();

                $('#obat_kode').change(function() {
                    const kodeObat = $(this).val();
                    // Ambil data dari API menggunakan nilai kodeObat
                    $.get('../apotek/getPadananApi.php', {
                            dataPadanan: 'padanan_obat',
                            kodeObat: kodeObat
                        })
                        .then(data => {
                            // Buat list pada elemen dengan id PadananObat
                            const list = $('#PadananObat');
                            list.empty();
                            data.forEach(item => {
                                const listItem = $('<li>').text(`${item.kode_obat_padanan} | ${item.nama_obat}`);
                                list.append(listItem);
                            });
                        })
                        .fail(error => console.error('Error:', error));
                });

                window.toPadanan = function() {
                    const selectedValue = $('#obat_kode').val();
                    if (selectedValue) {
                        const currentUrl = window.location.href;
                        const newUrl = `index.php?halaman=padanan_obat&kodeObat=${selectedValue}`;
                        window.open(newUrl, '_blank');
                    } else {
                        alert('Silakan pilih obat terlebih dahulu.');
                    }
                };
            });
        </script>
        <div class="card shadow p-2 mb-0">
            <form method="post">
                <div class="row g-1">
                    <div class="col-md-5">
                        <select name="kode_obat" class="form-control w-100 obat-select form-control-sm mb-2" id="obat_kode">

                        </select>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-sm btn-warning mb-2" type="button" data-bs-toggle="modal" data-bs-target="#modalPadanan"><i class="bi bi-prescription"></i></button>
                        <!-- Modal -->
                        <div class="modal fade" id="modalPadanan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title fs-5" id="staticBackdropLabel">Padanan Obat</h3>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <span id="PadananObat">

                                        </span>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-sm btn-primary" onclick="toPadanan()">Lihat Padanan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="jumlah_obat" value="1" class="form-control form-control-sm" id="" placeholder="Jumlah">
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="diskon_obat" value="0" class="form-control form-control-sm" id="" placeholder="Diskon (%)">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-sm btn-warning" name="addToCart">[+]</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card shadow mt-1 mb-1 p-2">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-sm" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th>Kode Obat</th>
                            <th>Nama Obat</th>
                            <th>Harga Umum</th>
                            <th>Jumlah</th>
                            <th>Diskon(%)</th>
                            <th>SubTotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($_SESSION['keranjang_obat_resep'])) { ?>
                            <?php
                            foreach ($_SESSION['keranjang_obat_resep'] as $index => $item) {
                            ?>
                                <tr>
                                    <td><?= $item['kode'] ?></td>
                                    <td><?= $item['nama'] ?></td>
                                    <td>Rp <?= number_format($item['harga'], 0, 0, '.') ?></td>
                                    <td><?= $item['jumlah'] ?></td>
                                    <td><?= $item['diskon'] ?>%</td>
                                    <td>Rp <?= number_format($item['subtotal'], 0, 0, '.') ?></td>
                                    <td>
                                        <a href="index.php?halaman=penjualan_obat_resep&deleteCart=<?= $index ?>" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Hapus obat ini dari keranjang?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5"><b>Total</b></td>
                            <td colspan="2"><b>Rp <?= number_format($total, 0, 0, '.') ?></b></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="card shadow p-2 mt-0 mb-1">
            <form method="POST">
                <div class="row g-1">
                    <div class="col-md-3">
                        <input type="text" readonly name="nota" id="" class="form-control form-control-sm" value="<?= date('ymdhis') . '-' . rand(1000, 9999) ?>" placeholder="No Nota">
                        <input type="date" name="tgl_jual" value="<?= date('Y-m-d') ?>" hidden id="">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="akun" class="form-control form-control-sm" id="" placeholder="Nama Pembeli" required value="<?= htmlspecialchars($_GET['pembeli'] ?? "") ?>">
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="" id="dibayar_id" class="form-control form-control-sm" placeholder="Dibayar" oninput="hitungKembalian()">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="" id="kembalian_id" class="form-control form-control-sm" readonly placeholder="Kembalian">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-sm btn-warning" name="saveTransaction" onclick="return confirm('Are You Sure To Save Transaction?')">Transaksi</button>
                    </div>
                </div>
            </form>
        </div>
        <script>
            function hitungKembalian() {
                var total = <?= number_format($total, 0, 0, '') ?>;
                var dibayar = document.getElementById('dibayar_id').value;
                var kembalian = dibayar - total;
                document.getElementById('kembalian_id').value = parseInt(kembalian);
            }
            // document.getElementById('dibayar_id').addEventListener('input', hitungKembalian);
        </script>
        <div class="card shadow p-2">
            <b>Penjualan Terakhir Untuk Print (5 Terakhir)</b>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-sm" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Nota</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $getRiwayat5 = $koneksi->query("SELECT *, SUM((harga_umum - diskon_obat)*jumlah) as hargaTotal FROM penjualan_resep GROUP BY nota ORDER BY id_penjualan DESC LIMIT 5");
                        foreach ($getRiwayat5 as $data5) {
                        ?>
                            <tr>
                                <td><?= $data5['created_at'] ?></td>
                                <td><?= $data5['nota'] ?></td>
                                <td>Rp <?= number_format($data5['hargaTotal'], 0, 0, '.') ?></td>
                                <td>
                                    <a target="_blank" href="../apotek/penjualan_obat_resep_nota.php?nota=<?= $data5['nota'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-printer"></i></a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <style>
            .pasien-list {
                cursor: pointer;
            }

            .pasien-list:hover {
                background-color: #f1f1f1;
            }
        </style>
        <div class="card shadow-sm h-100 p-2">
            <h6 class=""><b>Planing IGD</b>
                <button type="button" class="btn btn-sm btn-outline-danger float-end" onclick="clearPlan()" title="Clear Planing">✕</button>
            </h6>
            <form method="post">
                <input type="text" name="idigd" id="idigd" hidden>
                <input type="text" name="nama_pasien" class="form-control-sm form-control mb-1" readonly id="nama_pasien">
                <textarea name="plan" id="plan" readonly class="form-control-sm form-control"></textarea>
                <p class="my-0 mt-1 d-none" align="right">
                    <button type="submit" name="tandaiSelesaiIsi" id="check" class="btn btn-sm btn-primary" onclick="clearPlanOnSubmit()"><i class="bi bi-check-circle-fill"></i></button>
                </p>
            </form>
            <br>
            <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
            <script src="https://cdn.ckeditor.com/ckeditor5/37.1.0/classic/ckeditor.js"></script>
            <script>
                let planEditor; // Variable global untuk menyimpan instance CKEditor

                document.addEventListener('DOMContentLoaded', function() {
                    ClassicEditor
                        .create(document.querySelector('#plan'), {
                            ckfinder: {
                                uploadUrl: 'https://www.gkjwtunjungrejo.com/image-upload?_token=i99BxDXahocEmpYJ9vCLcLSTnfwaDPss37KbA71C',
                            },
                            toolbar: [
                                'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'undo', 'redo'
                            ]
                        })
                        .then(editor => {
                            planEditor = editor; // Simpan instance untuk digunakan nanti
                            editor.enableReadOnlyMode('planReadOnly'); // Set editor menjadi readonly
                        })
                        .catch(error => {
                            console.error(error);
                        });
                });

                // Function untuk load data pasien ke form
                function loadPlanData(idigd, namaPasien, rencanaRawat) {
                    document.getElementById('idigd').value = idigd;
                    document.getElementById('nama_pasien').value = namaPasien;
                    document.getElementById('check').parentElement.classList.remove('d-none'); // Tampilkan tombol tandai selesai

                    // Set data ke CKEditor menggunakan setData()
                    if (planEditor) {
                        planEditor.setData(rencanaRawat || '');
                    }

                    // Simpan ke localStorage agar tetap tersimpan saat halaman reload
                    localStorage.setItem('selectedPlan', JSON.stringify({
                        idigd: idigd,
                        namaPasien: namaPasien,
                        rencanaRawat: rencanaRawat
                    }));
                }

                // Restore data planing dari localStorage saat halaman dimuat
                window.addEventListener('load', function() {
                    const savedPlan = localStorage.getItem('selectedPlan');
                    if (savedPlan) {
                        const planData = JSON.parse(savedPlan);
                        loadPlanData(planData.idigd, planData.namaPasien, planData.rencanaRawat);
                    }
                });

                // Function untuk clear localStorage
                function clearPlan() {
                    if (confirm('Clear planing IGD ini?')) {
                        localStorage.removeItem('selectedPlan');
                        document.getElementById('idigd').value = '';
                        document.getElementById('nama_pasien').value = '';
                        document.getElementById('check').parentElement.classList.add('d-none');
                        if (planEditor) {
                            planEditor.setData('');
                        }
                    }
                }

                // Function untuk clear pada saat tandai selesai
                function clearPlanOnSubmit() {
                    localStorage.removeItem('selectedPlan');
                }
            </script>
            <?php $getDataIGD = $koneksi->query("SELECT * FROM igd WHERE tindak != 'Rawat' AND tindak_at IS NOT NULL AND rencana_rawat_at IS NULL ORDER BY rencana_rawat_at DESC"); ?>
            <?php foreach ($getDataIGD as $igd) { ?>
                <div style="font-size: 12px;">
                    <div class="pasien-list mb-0 p-1" onclick="loadPlanData('<?= $igd['idigd'] ?>', '<?= htmlspecialchars($igd['nama_pasien'], ENT_QUOTES) ?>', `<?= htmlspecialchars($igd['rencana_rawat'], ENT_QUOTES) ?>`);">
                        <span><b><?= $igd['nama_pasien'] ?></b></span><br>
                        <span class="badge bg-secondary"><?= $igd['no_rm'] ?></span>
                        <span class="badge bg-secondary"><?= $igd['tgl'] ?></span>
                    </div>
                    <hr>
                </div>
            <?php } ?>
        </div>
    </div>
</div>