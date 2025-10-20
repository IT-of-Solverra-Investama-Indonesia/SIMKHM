<div>
    <?php
    if (isset($_GET['updateStokReal'])) {
        $koneksi->query("INSERT INTO revisi_sementara (kode_obat, stok_seharusnya) VALUES ('$_GET[kode_obat]', '$_GET[stok_seharusnya]') ");
        $revisiStokSeharusnya = $_GET['stok_sekarang'] - $_GET['stok_seharusnya'];
        $getDataSingle = $koneksimaster->query("SELECT * FROM master_obat WHERE kode_obat = '$_GET[kode_obat]'")->fetch_assoc();
        $koneksi->query("INSERT INTO revisi_obat (kode_obat, nama_obat, jumlah, keterangan, tanggal, petugas, shift) VALUES ('$_GET[kode_obat]', '$getDataSingle[obat_master]', '$revisiStokSeharusnya', 'Revisi IT', NOW(), 'IT Solverra', 'Pagi')");
    }
    ?>
    <?php if (!isset($_GET['detail']) and !isset($_GET['AllRevisi'])) { ?>
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

                    // use BcMath\Number;

                    $apiUrlgetObat = '../apotek/api_getObatMasterLokal.php';
                    $apiUrlgetObat .= '?all';
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
        <script>
            // In your Javascript (external .js resource or <script> tag)
            $(document).ready(function() {
                $('#selectRevisi').select2({
                    dropdownParent: $('#addRevisi')
                });
            });
        </script>
        <div>
            <h5 class="card-title mt-0">Stok Obat</h5>
            <div class="card shadow p-2 mb-2">
                <form method="GET">
                    <div class="row g-1">
                        <div class="col-5">
                            <input type="text" name="halaman" value="stok_obat_apoteker" hidden id="">
                            <input name="tgl" id="" class="form-control form-control-sm" value="<?= $tglCari = isset($_GET['tgl']) ? $_GET['tgl'] : date('Y-m-d') ?>" placeholder="Sampai Tgl" onfocus="(this.type='date')" onblur="(this.type='text')">
                        </div>
                        <div class="col-5">
                            <input type="text" name="key" id="" class="form-control form-control-sm" value="<?= isset($_GET['key']) ? $_GET['key'] : "" ?>" placeholder="Pencarian Obat">
                        </div>
                        <div class="col-2">
                            <button class="btn btn-sm btn-primary" type="submit" name="src"><i class="bi bi-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card shadow p-2">
                <?php if ($_SESSION['admin']['level'] == 'sup') { ?>
                    <p align="right">
                        <a href="index.php?halaman=stok_obat_apoteker&AllRevisi" class="btn btn-sm btn-dark">Riwayat Revisi Stok</a>
                        <button class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#addRevisi">Revisi Stok</button>
                    </p>
                    <!-- Modal Revisi Obat -->
                    <div class="modal fade" id="addRevisi" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Revisi Stok</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="post">
                                    <div class="modal-body">
                                        <span style="font-size: 12px;">
                                            "Ketika menambah revisi, akan mengurangi stok yang yang sekarang, gunakana minus(-) untuk menambah stok."
                                        </span> <br>
                                        <label for="" class="mb-0">Obat</label>
                                        <select name="kode_obat" id="selectRevisi" style="width: 100%;" class="w-100 form-control form-control-sm mb-2 obat-select">

                                        </select>
                                        <br>
                                        <label for="" class="mb-0 mt-2">Jumlah Revisi</label>
                                        <input type="number" name="jumlah" id="" class="form-control form-control-sm mb-2">
                                        <label for="" class="mb-0">Keterangan Revisi</label>
                                        <textarea name="keterangan" id="" class="form-control"></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" name="addRevisi" class="btn btn-dark">Tambah</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php
                    if (isset($_POST['addRevisi'])) {
                        $kode_obat = htmlspecialchars($_POST['kode_obat']);
                        $jumlah = htmlspecialchars($_POST['jumlah']);
                        $keterangan = htmlspecialchars($_POST['keterangan']);
                        $getSinglNamaObat = $koneksimaster->query("SELECT * FROM master_obat WHERE kode_obat = '$kode_obat' ORDER BY obat_master DESC")->fetch_assoc();
                        $nama_obat = $getSinglNamaObat['obat_master'];
                        $tanggal = date('Y-m-d');
                        $petugas = $_SESSION['admin']['namalengkap'];
                        $shift = $_SESSION['shift'];

                        $koneksi->query("INSERT INTO revisi_obat (kode_obat, nama_obat, jumlah, keterangan, tanggal, petugas, shift) VALUES ('$kode_obat', '$nama_obat', '$jumlah', '$keterangan', '$tanggal', '$petugas', '$shift')");

                        echo "
                        <script>
                            alert('Berhasil menambahkan revisi stok');
                            window.location.href = 'index.php?halaman=stok_obat_apoteker';
                        </script>
                    ";
                    }
                    ?>
                <?php } ?>
                <div class="table-responsive">
                    <!-- Include jQuery -->
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                    <!-- Include DataTables and Buttons -->
                    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
                    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css" />
                    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
                    <script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
                    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
                    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>

                    <table class="table table-hover table-striped table-sm" style="font-size: 12px;" id="myTable">
                        <script>
                            $(document).ready(function() {
                                $('#myTable').DataTable({
                                    dom: 'Bfrtip',
                                    buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                                    "pageLength": 100
                                });
                            });
                        </script>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Obat</th>
                                <th>Kode Obat</th>
                                <th>Obat Datang</th>
                                <th>Obat Kembali(Retur)</th>
                                <th>Obat Keluar(Poli&Ranap)</th>
                                <th>Penjualan Umum</th>
                                <th>Penjualan Resep</th>
                                <th>Penjualan Rekanan</th>
                                <th>Penjualan Internal</th>
                                <th>Revisi</th>
                                <th>Stok</th>
                                <th>Query</th>
                                <th>Aktif Poli</th>
                                <th>Aktif Ranap</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $whereConditionMaster = "";
                            $whereConditionMasuk = "";
                            $whereConditionKeluar = "";
                            $whereConditionPenjualan = "";
                            $whereConditionRetur = "";
                            $urlPage = "index.php?halaman=stok_obat_apoteker";
                            if (isset($_GET['src'])) {
                                $urlPage = "index.php?halaman=stok_obat_apoteker&src&key=" . $_GET['key'] . "&tgl=" . $_GET['tgl'];
                                if ($_GET['key'] != "") {
                                    $whereConditionMaster = " AND (obat_master LIKE '%$_GET[key]%' OR kode_obat LIKE '%$_GET[key]%')";
                                }
                                if ($_GET['tgl'] != "") {
                                    $whereConditionMasuk = " AND tgl_beli <= '$_GET[tgl]'";
                                    $whereConditionKeluar = " AND tgl_pasien <= '$_GET[tgl]'";
                                    $whereConditionPenjualan = " AND tgl_jual <= '$_GET[tgl]'";
                                    $whereConditionRetur = " AND tgl_retur <= '$_GET[tgl]'";
                                }
                            }
                            $queryMaster = "SELECT * FROM master_obat WHERE (aktif_poli = 'aktif' OR aktif_ranap = 'aktif' OR aktif_umum = 'aktif') $whereConditionMaster ORDER BY obat_master ASC";

                            $limit = 100; // Number of entries to show in a page
                            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                            $start = ($page - 1) * $limit;

                            // Get the total number of records
                            $result = $koneksimaster->query($queryMaster);
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

                            $getData = $koneksimaster->query($queryMaster . " LIMIT $start, $limit;");
                            foreach ($getData as $data) {
                            ?>
                                <tr>
                                    <th><?= $no++ ?></th>
                                    <td><?= $data['obat_master'] ?></td>
                                    <td><?= $data['kode_obat'] ?></td>
                                    <td>
                                        <?php
                                        $getSingleObatMasuk = $koneksi->query("SELECT *, SUM(jml_obat) as jumlahMasuk FROM apotek WHERE id_obat = '$data[kode_obat]' $whereConditionMasuk ")->fetch_assoc();
                                        ?>
                                        <button class="btn btn-sm btn-warning">
                                            <a target="_blank" href="index.php?halaman=stok_obat_apoteker&detail=masuk&kode_obat=<?= $data['kode_obat'] ?>&hinggaTanggal=<?= $tglCari ?>" class="text-dark">
                                                <?= $getSingleObatMasuk['jumlahMasuk'] ?>
                                            </a>
                                            <!-- <span class="badge bg-success" data-bs-toggle="modal" data-bs-target="#updateDatang" onclick="upData('<?= $getSingleObatMasuk['id_obat'] ?>','<?= $getSingleObatMasuk['jumlahMasuk'] ?>')"><i class="bi bi-pencil"></i></span> -->
                                        </button>
                                    </td>
                                    <td>
                                        <?php
                                        $getSingleObatRetur = $koneksi->query("SELECT *, SUM(jumlah_retur) as jumlahRetur FROM retur_obat_inap WHERE kode_obat = '$data[kode_obat]' $whereConditionRetur")->fetch_assoc();
                                        ?>
                                        <a target="_blank" href="index.php?halaman=stok_obat_apoteker&detail=retur&kode_obat=<?= $data['kode_obat'] ?>&hinggaTanggal=<?= $tglCari ?>" class="btn btn-sm btn-success">
                                            <?= $getSingleObatRetur['jumlahRetur'] ?? 0 ?>
                                        </a>

                                    </td>
                                    <td>
                                        <?php
                                        $getSingleObatKeluar = $koneksi->query("SELECT *, SUM(jml_dokter) as jumlahKeluar FROM obat_rm WHERE kode_obat = '$data[kode_obat]' $whereConditionKeluar")->fetch_assoc();
                                        ?>
                                        <a target="_blank" href="index.php?halaman=stok_obat_apoteker&detail=keluar&kode_obat=<?= $data['kode_obat'] ?>&hinggaTanggal=<?= $tglCari ?>" class="btn btn-sm btn-danger">
                                            <?= $getSingleObatKeluar['jumlahKeluar'] ?? 0 ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php
                                        $getPenjualanUmum = $koneksi->query("SELECT SUM(jumlah) as jumlah FROM penjualan_umum WHERE kode_obat = '$data[kode_obat]' $whereConditionPenjualan")->fetch_assoc();
                                        ?>
                                        <a target="_blank" href="index.php?halaman=penjualan_obat_all_riwayat&date_start=&date_end=<?= $tglCari ?>&sumber=UMUM&key=<?= $data['kode_obat'] ?>&src=" class="btn btn-sm btn-warning">
                                            <?= number_format($getPenjualanUmum['jumlah'] == '' ? 0 : $getPenjualanUmum['jumlah'], 0, 0, '.') ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php
                                        $getPenjualanResep = $koneksi->query("SELECT SUM(jumlah) as jumlah FROM penjualan_resep WHERE kode_obat = '$data[kode_obat]' $whereConditionPenjualan")->fetch_assoc();
                                        ?>
                                        <a target="_blank" href="index.php?halaman=penjualan_obat_all_riwayat&date_start=&date_end=<?= $tglCari ?>&sumber=RESEP&key=<?= $data['kode_obat'] ?>&src=" class="btn btn-sm btn-warning">
                                            <?= number_format($getPenjualanResep['jumlah'] == '' ? 0 : $getPenjualanResep['jumlah'], 0, 0, '.') ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php
                                        $getPenjualanRekanan = $koneksi->query("SELECT SUM(jumlah) as jumlah FROM penjualan_rekanan WHERE kode_obat = '$data[kode_obat]' $whereConditionPenjualan")->fetch_assoc();
                                        ?>
                                        <a target="_blank" href="index.php?halaman=penjualan_obat_all_riwayat&date_start=&date_end=<?= $tglCari ?>&sumber=REKANAN&key=<?= $data['kode_obat'] ?>&src=" class="btn btn-sm btn-warning">
                                            <?= number_format($getPenjualanRekanan['jumlah'] == '' ? 0 : $getPenjualanRekanan['jumlah'], 0, 0, '.') ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php
                                        $getPenjualanInternal = $koneksi->query("SELECT SUM(jumlah) as jumlah FROM penjualan_internal WHERE kode_obat = '$data[kode_obat]' $whereConditionPenjualan")->fetch_assoc();
                                        ?>
                                        <a target="_blank" href="index.php?halaman=penjualan_obat_all_riwayat&date_start=&date_end=<?= $tglCari ?>&sumber=INTERNAL&key=<?= $data['kode_obat'] ?>&src=" class="btn btn-sm btn-warning">
                                            <?= number_format($getPenjualanInternal['jumlah'] == '' ? 0 : $getPenjualanInternal['jumlah'], 0, 0, '.') ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php
                                        $getRevisi = $koneksi->query("SELECT SUM(jumlah) as jumlah FROM revisi_obat WHERE kode_obat = '$data[kode_obat]'")->fetch_assoc();
                                        ?>
                                        <a target="_blank" href="index.php?halaman=stok_obat_apoteker&detail=revisi&kode_obat=<?= $data['kode_obat'] ?>&hinggaTanggal=<?= $tglCari ?>" class="btn btn-sm btn-secondary">
                                            <?= number_format($getRevisi['jumlah'] == '' ? 0 : $getRevisi['jumlah'], 0, 0, '.') ?>
                                        </a>
                                    </td>
                                    <td>
                                        <b><?= $stokSekarang = (($getSingleObatMasuk['jumlahMasuk'] ?? 0) + ($getSingleObatRetur['jumlahRetur'] ?? 0)) - (($getSingleObatKeluar['jumlahKeluar'] ?? 0) + ($getPenjualanUmum['jumlah'] == '' ? 0 : $getPenjualanUmum['jumlah']) + ($getPenjualanResep['jumlah'] == '' ? 0 : $getPenjualanResep['jumlah']) + ($getPenjualanRekanan['jumlah'] == '' ? 0 : $getPenjualanRekanan['jumlah']) + ($getPenjualanInternal['jumlah'] == '' ? 0 : $getPenjualanInternal['jumlah']) + ($getRevisi['jumlah'] == '' ? 0 : $getRevisi['jumlah'])) ?></b>

                                    </td>
                                    <td>
                                        <?php if($_SESSION['admin']['username'] == 'shab'){?>
                                            <button type="button" onclick="upDataStok('<?= $data['kode_obat'] ?>', '<?= $stokSekarang ?>')" class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#modalUpdateStok">Update Stok</button>
                                        <?php }?>

                                        <?php
                                        $getDataRevisiSementara = $koneksi->query("SELECT *, COUNT(*) as jum FROM revisi_sementara WHERE kode_obat = '$data[kode_obat]' LIMiT 1")->fetch_assoc();
                                        ?>
                                        <?php if ($getDataRevisiSementara['jum'] != 0) { ?>
                                            <?php
                                            $getSingleNamaObat = $koneksimaster->query("SELECT * FROM master_obat WHERE kode_obat = '$getDataRevisiSementara[kode_obat]' LIMIT 1")->fetch_assoc();
                                            ?>
                                            <?php if (((($getSingleObatMasuk['jumlahMasuk'] ?? 0) + ($getSingleObatRetur['jumlahRetur'] ?? 0)) - (($getSingleObatKeluar['jumlahKeluar'] ?? 0) + ($getPenjualanUmum['jumlah'] == '' ? 0 : $getPenjualanUmum['jumlah']) + ($getPenjualanResep['jumlah'] == '' ? 0 : $getPenjualanResep['jumlah']) + ($getPenjualanRekanan['jumlah'] == '' ? 0 : $getPenjualanRekanan['jumlah']) + ($getPenjualanInternal['jumlah'] == '' ? 0 : $getPenjualanInternal['jumlah']) + ($getRevisi['jumlah'] == '' ? 0 : $getRevisi['jumlah']))) - $getDataRevisiSementara['stok_seharusnya'] != 0) { ?>
                                                <!-- INSERT INTO `revisi_obat`(`kode_obat`, `nama_obat`, `jumlah`, `keterangan`, `tanggal`, `petugas`, `shift`) VALUES ('<?= $getDataRevisiSementara['kode_obat'] ?>','<?= $getSingleNamaObat['obat_master'] ?>','<?= ((($getSingleObatMasuk['jumlahMasuk'] ?? 0) + ($getSingleObatRetur['jumlahRetur'] ?? 0)) - (($getSingleObatKeluar['jumlahKeluar'] ?? 0) + ($getPenjualanUmum['jumlah'] == '' ? 0 : $getPenjualanUmum['jumlah']) + ($getPenjualanResep['jumlah'] == '' ? 0 : $getPenjualanResep['jumlah']) + ($getPenjualanRekanan['jumlah'] == '' ? 0 : $getPenjualanRekanan['jumlah']) + ($getPenjualanInternal['jumlah'] == '' ? 0 : $getPenjualanInternal['jumlah']) + ($getRevisi['jumlah'] == '' ? 0 : $getRevisi['jumlah']))) - $getDataRevisiSementara['stok_seharusnya'] ?>','Revisi IT','2025-06-17','IT Solverra','Pagi'); -->
                                            <?php } ?>

                                        <?php } ?>
                                    </td>
                                    <td><?= $data['aktif_poli'] ?></td>
                                    <td><?= $data['aktif_ranap'] ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <script>
                    function upDataStok(kode_obat, stok_sekarang) {
                        document.getElementById('kode_obat_id_id').value = kode_obat;
                        document.getElementById('stok_sekarang_id_id').value = stok_sekarang;
                    }
                </script>
                <!-- Modal -->
                <div class="modal fade" id="modalUpdateStok" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Update Stok</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form method="get" action="../apotek/actionToUpdateStok.php" target="_blank">
                                <div class="modal-body">
                                    <input type="text" name="halaman" id="" value="stok_obat_apoteker" hidden>
                                    <input type="text" name="updateStokReal" id="" value="" hidden>
                                    <input type="text" readonly name="kode_obat" id="kode_obat_id_id" class="form-control mb-2 form-control-sm">
                                    <input type="text" readonly name="stok_sekarang" id="stok_sekarang_id_id" class="form-control mb-2 form-control-sm">
                                    <input type="text" name="stok_seharusnya" id="" class="form-control form-control-sm">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="">Update To Revisi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <script>
                    function upData(apoteker_id, jumlahDatang) {
                        document.getElementById('apoteker_id_id').value = apoteker_id;
                        document.getElementById('jumlahDatang_id').value = jumlahDatang;
                    }
                </script>
                <!-- Modal -->
                <div class="modal fade" id="updateDatang" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Update Kedatangan Modal</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form method="post">
                                <div class="modal-body">
                                    <label for="">Datang</label>
                                    <input type="number" name="jumlahDatang" id="jumlahDatang_id" class="form-control form-control-sm" placeholder="Jumlah Obat Datang">
                                    <input type="text" name="apoteker_id" hidden id="apoteker_id_id" class="form-control form-control-sm">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name="saveKedatang" class="btn btn-sm btn-primary">Perbarui</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
                if (isset($_POST['saveKedatang'])) {
                    $jumlahDatang = htmlspecialchars($_POST['jumlahDatang']);
                    $apoteker_id = htmlspecialchars($_POST['apoteker_id']);

                    $koneksi->query("UPDATE apotek SET jml_obat = '0' WHERE id_obat = '$apoteker_id'");

                    $koneksi->query("UPDATE apotek SET jml_obat = '$jumlahDatang' WHERE id_obat = '$apoteker_id' ORDER BY idapotek DESC LIMIT 1");

                    echo "<script>alert('Berhasil memperbarui kedatangan obat');</script>";
                    echo "<script>window.location.href='index.php?halaman=stok_obat_apoteker';</script>";
                }
                ?>

                <br>
                <?php
                // Display pagination
                echo '<nav>';
                echo '<ul class="pagination justify-content-center">';

                // Back button
                if ($page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . ($page - 1) . '">Back</a></li>';
                }

                // Determine the start and end page
                $start_page = max(1, $page - 2);
                $end_page = min($total_pages, $page + 2);

                if ($start_page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=1">1</a></li>';
                    if ($start_page > 2) {
                        echo '<li class="page-item"><span class="page-link">...</span></li>';
                    }
                }

                for ($i = $start_page; $i <= $end_page; $i++) {
                    if ($i == $page) {
                        echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
                    } else {
                        echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . $i . '">' . $i . '</a></li>';
                    }
                }

                if ($end_page < $total_pages) {
                    if ($end_page < $total_pages - 1) {
                        echo '<li class="page-item"><span class="page-link">...</span></li>';
                    }
                    echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . $total_pages . '">' . $total_pages . '</a></li>';
                }

                // Next button
                if ($page < $total_pages) {
                    echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . ($page + 1) . '">Next</a></li>';
                }

                echo '</ul>';
                echo '</nav>';
                ?>
                <br>
            </div>
        </div>
    <?php } elseif (isset($_GET['detail'])) { ?>
        <?php
        $total = 0;
        $dataSingle = $koneksimaster->query("SELECT * FROM master_obat WHERE kode_obat = '$_GET[kode_obat]'")->fetch_assoc();
        ?>
        <?php if ($_GET['detail'] == 'masuk') { ?>
            <h5 class="card-title">Stok Masuk <?= $dataSingle['obat_master'] ?></h5>
            <div class="card shadow p-2">
                <div class="table-responsive">
                    <table class="table-sm table table-hover table-striped" style="font-size: 12px;">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Kode</th>
                                <th>Tanggal</th>
                                <th>Batch</th>
                                <th>Expired</th>
                                <th>HargaBeli</th>
                                <th>Jumlah Obat</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totalNominal = 0;
                            $getData = $koneksi->query("SELECT * FROM apotek WHERE id_obat = '$_GET[kode_obat]' AND tgl_beli <= '$_GET[hinggaTanggal]' ORDER BY tgl_beli DESC");
                            foreach ($getData as $data) {
                            ?>
                                <tr>
                                    <td><?= $data['nama_obat'] ?></td>
                                    <td><?= $data['id_obat'] ?></td>
                                    <td><?= $data['tgl_datang'] ?? "0000-00-00" ?></td>
                                    <td><?= $data['batch'] ==  "" ? "-" : $data['batch'] ?></td>
                                    <td><?= $data['tgl_expired'] ?></td>
                                    <td>
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#updateHargaBeli" onclick="upData('<?= $data['idapotek'] ?>', '<?= $data['harga_beli'] ?>')" class="btn badge bg-warning" style="font-size: 12px;">
                                            Rp <?= number_format($data['harga_beli'], 0, 0, '.') ?>
                                        </button>
                                    </td>
                                    <td><?= $data['jml_obat'] ?></td>
                                    <td>Rp <?= number_format($data['jml_obat'] * $data['harga_beli'], 0, 0, '.') ?></td>
                                </tr>
                                <?php
                                $total += $data['jml_obat'];
                                $totalNominal += $data['jml_obat'] * $data['harga_beli'];
                                ?>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6"><b>Total</b></td>
                                <td><b><?= $total ?></b></td>
                                <td><b>Rp <?= number_format($totalNominal, 0, 0, '.') ?></b></td>
                            </tr>
                        </tfoot>
                    </table>
                    <script>
                        function upData(idapotek, harga_beli) {
                            document.getElementById('idapotek_id').value = idapotek;
                            document.getElementById('harga_beli').value = harga_beli;
                        }
                    </script>
                    <div class="modal fade" id="updateHargaBeli" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="modal-title fs-5" id="staticBackdropLabel">Update Harga Belli</h3>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="post">
                                    <div class="modal-body">
                                        <input type="text" hidden name="idapotek" id="idapotek_id" class="form-control form-control-sm">
                                        <input type="number" name="harga_beli" id="harga_beli" class="form-control form-control-sm" placeholder="Harga Beli">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" name="updateHargaBeli" class="btn btn-sm btn-dark">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php 
                    if (isset($_POST['updateHargaBeli'])) {
                        $idapotek = htmlspecialchars($_POST['idapotek']);
                        $harga_beli = htmlspecialchars($_POST['harga_beli']);

                        $koneksi->query("UPDATE apotek SET harga_beli = '$harga_beli' WHERE idapotek = '$idapotek'");

                        echo "
                        <script>
                            alert('Berhasil memperbarui harga beli');
                            window.location.href = 'index.php?halaman=stok_obat_apoteker&detail=masuk&kode_obat=$_GET[kode_obat]&hinggaTanggal=$_GET[hinggaTanggal]';
                        </script>
                        ";
                    }
                    ?>
                </div>
            </div>
        <?php } elseif ($_GET['detail'] == 'keluar') { ?>
            <h5 class="card-title">Stok Keluar <?= $dataSingle['obat_master'] ?></h5>
            <div class="card shadow p-2">
                <div class="table-responsive">
                    <table class="table-sm table table-hover table-striped" style="font-size: 12px;">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Kode</th>
                                <th>Tanggal</th>
                                <th>Jumlah Obat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $getData = $koneksi->query("SELECT * FROM obat_rm WHERE kode_obat = '$_GET[kode_obat]' AND tgl_pasien <= '$_GET[hinggaTanggal]' ORDER BY tgl_pasien DESC");
                            foreach ($getData as $data) {
                            ?>
                                <tr>
                                    <td><?= $data['nama_obat'] ?></td>
                                    <td><?= $data['kode_obat'] ?></td>
                                    <td><?= date('Y-m-d', strtotime($data['tgl_pasien'])) ?></td>
                                    <td><?= $data['jml_dokter'] ?></td>
                                </tr>
                                <?php $total += $data['jml_dokter'] ?>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3"><b>Total</b></td>
                                <td><b><?= $total ?></b></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        <?php } elseif ($_GET['detail'] == 'revisi') { ?>
            <h5 class="card-title">Stok Revisi <?= $dataSingle['obat_master'] ?></h5>
            <div class="card shadow p-2">
                <div class="table-responsive">
                    <table class="table-sm table table-hover table-striped" style="font-size: 12px;">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Kode</th>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th>Petugas</th>
                                <th>Shift</th>
                                <th>Jumlah Obat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $getData = $koneksi->query("SELECT * FROM revisi_obat WHERE kode_obat = '$_GET[kode_obat]' AND tanggal <= '$_GET[hinggaTanggal]' ORDER BY tanggal DESC");
                            foreach ($getData as $data) {
                            ?>
                                <tr>
                                    <td><?= $data['nama_obat'] ?></td>
                                    <td><?= $data['kode_obat'] ?></td>
                                    <td><?= date('Y-m-d', strtotime($data['tanggal'])) ?></td>
                                    <td><?= $data['keterangan'] ?></td>
                                    <td><?= $data['petugas'] ?></td>
                                    <td><?= $data['shift'] ?></td>
                                    <td><?= $data['jumlah'] ?></td>
                                </tr>
                                <?php $total += $data['jumlah'] ?>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6"><b>Total</b></td>
                                <td><b><?= $total ?></b></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        <?php } elseif ($_GET['detail'] == 'retur') { ?>
            <h5 class="card-title">Stok Retur <?= $dataSingle['obat_master'] ?></h5>
            <div class="card shadow p-2">
                <div class="table-responsive">
                    <table class="table-sm table table-hover table-striped" style="font-size: 12px;">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Kode</th>
                                <th>Tanggal</th>
                                <th>Jumlah Obat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $getData = $koneksi->query("SELECT * FROM retur_obat_inap WHERE kode_obat = '$_GET[kode_obat]' AND tgl_retur <= '$_GET[hinggaTanggal]' ORDER BY tgl_retur DESC");
                            foreach ($getData as $data) {
                            ?>
                                <tr>
                                    <td><?= $data['nama_obat'] ?></td>
                                    <td><?= $data['kode_obat'] ?></td>
                                    <td><?= date('Y-m-d', strtotime($data['tgl_retur'])) ?></td>
                                    <td><?= $data['jumlah_retur'] ?></td>
                                </tr>
                                <?php $total += $data['jumlah_retur'] ?>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3"><b>Total</b></td>
                                <td><b><?= $total ?></b></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        <?php } ?>
    <?php } elseif (isset($_GET['AllRevisi'])) { ?>
        <h5>All Revisi</h5>

        <?php if (!isset($_GET['nota'])) { ?>
            <div class="card shadow p-2">
                <table class="table table-hover table-striped table-sm" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nota</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $getData = $koneksi->query("SELECT * FROM revisi_obat GROUP BY nota ORDER BY tanggal DESC");
                        foreach ($getData as $data) {
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $data['tanggal'] ?></td>
                                <td>
                                    <a href="index.php?halaman=stok_obat_apoteker&AllRevisi&nota=<?= $data['nota'] ?>" class="badge bg-warning" style="font-size: 12px;">
                                        <?= $data['nota'] ?>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>

        <?php if (isset($_GET['nota'])) { ?>
            <div class="card shadow p-2">
                <div class="table-responsive">
                    <!-- Include jQuery -->
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                    <!-- Include DataTables -->
                    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
                    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
                    <script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
                    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
                    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>

                    <table class="table table-hover table-striped table-sm" style="font-size: 12px;" id="myTable">
                        <script>
                            $(document).ready(function() {
                                $('#myTable').DataTable({
                                    dom: 'Bfrtip',
                                    buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                                    "pageLength": 25
                                });
                            });
                        </script>
                        <thead>
                            <tr>
                                <th>Tgl</th>
                                <th>Nota</th>
                                <th>Kode Obat</th>
                                <th>Nama Obat</th>
                                <th>Jumlah Revisi</th>
                                <th>Harga Beli</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $jumlahTotal = 0;
                            $total = 0;
                            $getData = $koneksi->query("SELECT * FROM revisi_obat WHERE nota = '$_GET[nota]' ORDER BY created_at DESC");
                            foreach ($getData as $data) {
                                $jumlahTotal += $data['jumlah'];
                            ?>
                                <tr>
                                    <td>
                                        <?= $data['tanggal'] ?>
                                    </td>
                                    <td>
                                        <?= $data['nota'] ?>
                                    </td>
                                    <td><?= $data['kode_obat'] ?></td>
                                    <td><?= $data['nama_obat'] ?></td>
                                    <td><?= $data['jumlah'] ?></td>
                                    <td>
                                        <?php
                                        $getLastPrice = $koneksi->query("SELECT * FROM apotek WHERE id_obat = '$data[kode_obat]' AND  tgl_beli <= '$data[tanggal]'  ORDER BY tgl_beli DESC LIMIT 1")->fetch_assoc();
                                        $total += ($getLastPrice['harga_beli'] ?? 0) * $data['jumlah'];
                                        ?>
                                        <?= $harga = $getLastPrice['harga_beli'] ?? 0 ?>
                                    </td>
                                    <td>
                                        Rp <?= number_format($harga * $data['jumlah'], 0, 0, '.') ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4"><b>Total</b></td>
                                <td><b><?= number_format($jumlahTotal, 0, 0, '.') ?></b></td>
                                <td></td>
                                <td><b>Rp <?= number_format($total, 0, 0, '.') ?></b></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
</div>