<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" />
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<!-- !-- DataTables  -->

<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.colVis.min.js"></script>

<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excelHtml5'
            }]
        });
    });
</script>
<div class="pagetitle mb-0">
    <h1>Rekap Obat</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?halaman=daftarpuyer" style="color:blue;">Rekap Obat</a></li>
        </ol>
    </nav>
</div>
<div class="card shadow p-2 mb-2">
    <form method="get">
        <div class="row g-1">
            <div class="col-3">
                <label for="" class="mb-0">Date Start</label>
                <input type="text" name="halaman" id="rekapobat" hidden value="rekapobat">
                <input type="date" name="mulai" value="<?= $_GET['mulai'] ?? date('Y-m-1') ?>" class="form-control form-control-sm mt-0 mb-1">
            </div>
            <div class="col-3">
                <label for="" class="mb-0">Date End :</label>
                <input type="date" name="hingga" value="<?= $_GET['hingga'] ?? date('Y-m-d') ?>" class="form-control form-control-sm mt-0 mb-1">
            </div>
            <div class="col-3">
                <label for="" class="mb-0">Jenis</label>
                <select name="jenis" id="" class="form-control form-control-sm">
                    <option value="Poli">Poli All</option>
                    <option value="PoliUmum">PoliUmum</option>
                    <option value="PoliBPJS">PoliBPJS</option>
                    <option value="PoliAnak">PoliAnak</option>
                    <option value="PoliPenyakitDalam">PoliPenyakitDalam</option>
                    <option value="Inap">Inap</option>
                    <option value="Umum">Umum</option>
                    <option value="Resep">Resep</option>
                    <option value="Rekanan">Rekanan</option>
                    <option value="Internal">Internal</option>
                </select>
            </div>
            <div class="col-3">
                <br>
                <button class="btn btn-sm btn-primary" name="src"><i class="bi bi-search"></i></button>
                <!-- <button class="btn btn-sm btn-primary" name="srcInap"><i class="bi bi-search"></i> Inap</button>
                <button class="btn btn-sm btn-primary" name="srcPoli"><i class="bi bi-search"></i> Poli</button>
                <button class="btn btn-sm btn-primary" name="src"><i class="bi bi-search"></i> Filter</button> -->
                <!-- <button style="float:left; margin-right: 10px;" class="btn btn-info" name="src2"><i class="bi bi-search"></i> Inap</button> -->
            </div>

        </div>
    </form>
</div>
<div class="card shadow p-2">
    <div class="table-responsive">
        <table class="table" id="myTable" style="font-size: 12px;">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Obat</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>HPP</th>
                    <th>Total</th>
                    <th>TotalHPP</th>
                    <th>Laba</th>
                    <th>Laba Satuan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalTotal = 0;
                $totalHPP = 0;
                $totalLaba = 0;
                // if (isset($_POST['src'])) {
                //     $getObat = $koneksi->query("SELECT *, SUM(jml_dokter) as jumlah_keluar  FROM (
                //                 SELECT SUM(jml_dokter) as jml_dokter, kode_obat , obat_rm.nama_obat FROM obat_rm INNER JOIN registrasi_rawat ON registrasi_rawat.no_rm = obat_rm.idrm AND DATE_FORMAT(registrasi_rawat.jadwal, '%Y-%m-%d') = obat_rm.tgl_pasien WHERE obat_rm.tgl_pasien >= '$_GET[mulai]' AND obat_rm.tgl_pasien <= '$_GET[hingga]' AND perawatan = 'Rawat Inap' GROUP BY kode_obat
                //                 UNION ALL
                //                 SELECT SUM(jml_dokter) as jml_dokter, kode_obat , obat_rm.nama_obat FROM obat_rm INNER JOIN rekam_medis ON rekam_medis.id_rm = obat_rm.rekam_medis_id WHERE obat_rm.tgl_pasien >= '$_GET[mulai]' AND obat_rm.tgl_pasien <= '$_GET[hingga]' AND idigd = '0' GROUP BY kode_obat
                //                 ) as a GROUP BY kode_obat ORDER BY nama_obat DESC");
                // }
                if (isset($_GET['jenis']) && $_GET['jenis'] == 'Inap') {
                    $getObat = $koneksi->query("SELECT *, SUM(jml_dokter) as jumlah_keluar FROM obat_rm INNER JOIN registrasi_rawat ON registrasi_rawat.no_rm = obat_rm.idrm AND DATE_FORMAT(registrasi_rawat.jadwal, '%Y-%m-%d') = obat_rm.tgl_pasien WHERE obat_rm.tgl_pasien >= '$_GET[mulai]' AND obat_rm.tgl_pasien <= '$_GET[hingga]' AND perawatan = 'Rawat Inap' GROUP BY kode_obat ORDER BY nama_obat DESC");
                }
                if (isset($_GET['jenis']) && $_GET['jenis'] == 'Poli') {
                    $getObat = $koneksi->query("SELECT *, SUM(jml_dokter) as jumlah_keluar FROM obat_rm INNER JOIN rekam_medis ON rekam_medis.id_rm = obat_rm.rekam_medis_id WHERE obat_rm.tgl_pasien >= '$_GET[mulai]' AND obat_rm.tgl_pasien <= '$_GET[hingga]' AND idigd = '0' GROUP BY kode_obat ORDER BY nama_obat DESC");
                }
                
                if (isset($_GET['jenis']) && in_array($_GET['jenis'], ['PoliUmum', 'PoliBPJS', 'PoliAnak', 'PoliPenyakitDalam'])) {
                    $queryKey = '';
                    if($_GET['jenis'] == 'PoliUmum'){
                        $queryKey .= " AND registrasi_rawat.carabayar = 'umum'";
                    }elseif($_GET['jenis'] == 'PoliBPJS'){
                        $queryKey .= " AND registrasi_rawat.carabayar = 'bpjs'";
                    }elseif($_GET['jenis'] == 'PoliAnak'){
                        $queryKey .= " AND registrasi_rawat.carabayar = 'spesialis anak'";
                    }elseif($_GET['jenis'] == 'PoliPenyakitDalam'){
                        $queryKey .= " AND registrasi_rawat.carabayar = 'spesialis penyakit dalam'";
                    }

                    $getObat = $koneksi->query("SELECT *, SUM(jml_dokter) as jumlah_keluar FROM obat_rm INNER JOIN rekam_medis ON rekam_medis.id_rm = obat_rm.rekam_medis_id INNER JOIN registrasi_rawat  ON registrasi_rawat.jadwal = rekam_medis.jadwal WHERE obat_rm.tgl_pasien >= '$_GET[mulai]' $queryKey AND obat_rm.tgl_pasien <= '$_GET[hingga]' AND idigd = '0' GROUP BY kode_obat ORDER BY nama_obat DESC");
                }

                if (isset($_GET['jenis']) && ($_GET['jenis'] == 'Umum' || $_GET['jenis'] == 'Resep' || $_GET['jenis'] == 'Rekanan' || $_GET['jenis'] == 'Internal')) {
                    if ($_GET['jenis'] == 'Umum') {
                        $sumber = 'UMUM';
                    } elseif ($_GET['jenis'] == 'Resep') {
                        $sumber = 'RESEP';
                    } elseif ($_GET['jenis'] == 'Rekanan') {
                        $sumber = 'REKANAN';
                    } elseif ($_GET['jenis'] == 'Internal') {
                        $sumber = 'INTERNAL';
                    }

                    $getObat = $koneksi->query("SELECT *, SUM(jumlah) as jumlah_keluar FROM (
                        SELECT 'UMUM' AS sumber, id_penjualan, nota, tgl_jual, kode_obat, nama_obat, harga_umum, diskon_obat, jumlah, harga_beli, akun, petugas, shift, created_at FROM penjualan_umum WHERE tgl_jual >= '$_GET[mulai]' AND tgl_jual <= '$_GET[hingga]'
                        UNION ALL
                        SELECT 'RESEP' AS sumber, id_penjualan, nota, tgl_jual, kode_obat, nama_obat, harga_umum, diskon_obat, jumlah, harga_beli, akun, petugas, shift, created_at FROM penjualan_resep WHERE tgl_jual >= '$_GET[mulai]' AND tgl_jual <= '$_GET[hingga]'
                        UNION ALL
                        SELECT 'REKANAN' AS sumber, id_penjualan, nota, tgl_jual, kode_obat, nama_obat, harga_umum, diskon_obat, jumlah, harga_beli, akun, petugas, shift, created_at FROM penjualan_rekanan WHERE tgl_jual >= '$_GET[mulai]' AND tgl_jual <= '$_GET[hingga]'
                        UNION ALL
                        SELECT 'INTERNAL' AS sumber, id_penjualan, nota, tgl_jual, kode_obat, nama_obat, harga_umum, diskon_obat, jumlah, harga_beli, akun, petugas, shift, created_at FROM penjualan_internal WHERE tgl_jual >= '$_GET[mulai]' AND tgl_jual <= '$_GET[hingga]'
                    ) AS a WHERE sumber = '$sumber' GROUP BY kode_obat ORDER BY nama_obat DESC");
                }
                ?>
                <?php foreach ($getObat as $obat) { ?>
                    <?php
                    if ($_GET['jenis'] == 'Inap' or $_GET['jenis'] == 'Poli' or in_array($_GET['jenis'], ['PoliUmum', 'PoliBPJS', 'PoliAnak', 'PoliPenyakitDalam'])) {
                        $getObatMasuk = $koneksi->query("SELECT *, SUM(jml_obat) as jumlah_masuk, (SELECT harga_beli FROM apotek WHERE id_obat = '" . htmlspecialchars($obat['kode_obat']) . "' AND  tgl_beli <= '$_GET[hingga]' ORDER BY idapotek DESC LIMIT 1) as harga_beli FROM apotek WHERE id_obat = '" . htmlspecialchars('$obat[kode_obat]') . "' AND tgl_beli <= '$_GET[hingga]'")->fetch_assoc();
                        $namaObat = $obat['nama_obat'];
                        $kodeObat = $obat['kode_obat'];
                        $jumlahObatKeluar = $obat['jumlah_keluar'];
                        $hargaObatMasuk = $getObatMasuk['harga_beli'] ?? 0;
                        if ($_GET['jenis'] == 'Inap') {
                            $getObatMaster = $koneksimaster->query("SELECT * FROM master_obat WHERE kode_obat = '$obat[kode_obat]'")->fetch_assoc();
                            $hargaJual = $hargaObatMasuk * $getObatMaster['margin_inap'] / 100;
                            // $getBiayaRanap = $koneksi->query("SELECT * FROM rawatinapdetail WHERE ket = '%" . $obat['idobat'] . "%' LIMIT 1")->fetch_assoc();
                            // $getJumlahRanap = $koneksi->query("SELECT * FROM obat_rm WHERE idobat = '%" . $obat['idobat'] . "%' LIMIT 1")->fetch_assoc();

                            // if (isset($getBiayaRanap['biaya']) && $getBiayaRanap['biaya'] > 0) {
                            //     $hargaJual = $getBiayaRanap['biaya'] / $getJumlahRanap['jml_dokter'];
                            // } else {
                            //     $hargaJual = $hargaObatMasuk * $getObatMaster['margin_inap'];
                            // }
                            // $hargaJual = $getBiayaRanap['biaya'] / $getJumlahRanap['jml_dokter'];
                        } else {
                            $hargaJual = 0;
                        }
                    }
                    if ($_GET['jenis'] == 'Umum' or $_GET['jenis'] == 'Resep' or $_GET['jenis'] == 'Rekanan' or $_GET['jenis'] == 'Internal') {
                        $namaObat = $obat['nama_obat'];
                        $kodeObat = $obat['kode_obat'];
                        $jumlahObatKeluar = $obat['jumlah_keluar'];
                        $hargaJual = $obat['harga_umum'];
                        $hargaObatMasuk = $obat['harga_beli'];
                    }
                    ?>
                    <tr>
                        <td><?= $kodeObat; ?></td>
                        <td><?= $namaObat; ?></td>
                        <td><?= $jumlahObatKeluar; ?></td>
                        <td>Rp <?= number_format($hargaJual, 0, 0, '.'); ?></td>
                        <td>Rp <?= number_format($hargaObatMasuk, 0, 0, '.'); ?></td>
                        <td>Rp <?= number_format($hargaTotal = $jumlahObatKeluar * $hargaJual, 0, 0, '.'); ?></td>
                        <td>Rp <?= number_format($hargaTotalHPP = $jumlahObatKeluar * $hargaObatMasuk, 0, 0, '.'); ?></td>
                        <td>Rp <?= number_format($laba = $hargaTotal - $hargaTotalHPP, 0, 0, '.'); ?></td>
                        <td>Rp <?= number_format(($laba == 0 ? 1 : $laba) / ($jumlahObatKeluar == 0 ? 1 : $jumlahObatKeluar), 0, 0, '.'); ?></td>
                    </tr>
                    <?php
                    $totalTotal += $hargaTotal;
                    $totalHPP += $hargaTotalHPP;
                    $totalLaba += $laba;
                    ?>
                <?php } ?>
            </tbody>
            <tbody>
                <tr>
                    <td colspan="5"><b>Total</b></td>
                    <td><b>Rp <?= number_format($totalTotal, 0, 0, '.') ?></b></td>
                    <td><b>Rp <?= number_format($totalHPP, 0, 0, '.') ?></b></td>
                    <td><b>Rp <?= number_format($totalLaba, 0, 0, '.') ?></b></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>