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
    <form method="post">
        <div class="row">
            <div class="col-md-4">
                <label for="" class="mb-0">Cari Mulai Tanggal :</label>
                <input type="date" name="mulai" class="form-control mt-0 mb-1">
            </div>
            <div class="col-md-4">
                <label for="" class="mb-0">Hingga Tanggal :</label>
                <input type="date" name="hingga" class="form-control mt-0 mb-1">
            </div>
            <div class="col-md-4">
                <br>
                <button class="btn btn-primary" name="srcInap"><i class="bi bi-search"></i> Inap</button>
                <button class="btn btn-primary" name="srcPoli"><i class="bi bi-search"></i> Poli</button>
                <button class="btn btn-primary" name="src"><i class="bi bi-search"></i> Filter</button>
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
                    <th>Nama Obat</th>
                    <th>Masuk <br> (Hingga Tanggal Akhir)</th>
                    <th>Keluar <br> (By Tanggal)</th>
                    <th>Harga Beli</th>
                    <th>Sisa</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($_POST['src'])) {
                    $getObat = $koneksi->query("SELECT *, SUM(jml_dokter) as jumlah_keluar  FROM (
                                SELECT SUM(jml_dokter) as jml_dokter, kode_obat , obat_rm.nama_obat FROM obat_rm INNER JOIN registrasi_rawat ON registrasi_rawat.no_rm = obat_rm.idrm AND DATE_FORMAT(registrasi_rawat.jadwal, '%Y-%m-%d') = obat_rm.tgl_pasien WHERE obat_rm.tgl_pasien >= '$_POST[mulai]' AND obat_rm.tgl_pasien <= '$_POST[hingga]' AND perawatan = 'Rawat Inap' GROUP BY kode_obat
                                UNION ALL
                                SELECT SUM(jml_dokter) as jml_dokter, kode_obat , obat_rm.nama_obat FROM obat_rm INNER JOIN rekam_medis ON rekam_medis.id_rm = obat_rm.rekam_medis_id WHERE obat_rm.tgl_pasien >= '$_POST[mulai]' AND obat_rm.tgl_pasien <= '$_POST[hingga]' AND idigd = '0' GROUP BY kode_obat
                                ) as a GROUP BY kode_obat ORDER BY nama_obat DESC");
                }
                if (isset($_POST['srcInap'])) {
                    $getObat = $koneksi->query("SELECT *, SUM(jml_dokter) as jumlah_keluar FROM obat_rm INNER JOIN registrasi_rawat ON registrasi_rawat.no_rm = obat_rm.idrm AND DATE_FORMAT(registrasi_rawat.jadwal, '%Y-%m-%d') = obat_rm.tgl_pasien WHERE obat_rm.tgl_pasien >= '$_POST[mulai]' AND obat_rm.tgl_pasien <= '$_POST[hingga]' AND perawatan = 'Rawat Inap' GROUP BY kode_obat ORDER BY nama_obat DESC");
                }
                if (isset($_POST['srcPoli'])) {
                    $getObat = $koneksi->query("SELECT *, SUM(jml_dokter) as jumlah_keluar FROM obat_rm INNER JOIN rekam_medis ON rekam_medis.id_rm = obat_rm.rekam_medis_id WHERE obat_rm.tgl_pasien >= '$_POST[mulai]' AND obat_rm.tgl_pasien <= '$_POST[hingga]' AND idigd = '0' GROUP BY kode_obat ORDER BY nama_obat DESC");
                }
                ?>

                <?php foreach ($getObat as $obat) { ?>
                    <?php
                    $getObatMasuk = $koneksi->query("SELECT *, SUM(jml_obat) as jumlah_masuk, (SELECT harga_beli FROM apotek WHERE id_obat = '$obat[kode_obat]' AND  tgl_beli <= '$_POST[hingga]' ORDER BY idapotek DESC LIMIT 1) as harga_beli FROM apotek WHERE id_obat = '$obat[kode_obat]' AND tgl_beli <= '$_POST[hingga]'")->fetch_assoc();
                    ?>
                    <tr>
                        <td><?= $obat['nama_obat'] ?> (<b><?= $obat['kode_obat'] ?></b>)</td>
                        <td><?= $getObatMasuk['jumlah_masuk'] ?></td>
                        <td>
                            <?= $obat['jumlah_keluar'] ?>
                        </td>
                        <td>Rp<?= number_format($getObatMasuk['harga_beli'], 0, 0, '.') ?></td>
                        <td><?= $sisa = $getObatMasuk['jumlah_masuk'] - $obat['jumlah_keluar'] ?></td>
                        <td>Rp<?= number_format($sisa * $getObatMasuk['harga_beli'], 0, 0, '.') ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>