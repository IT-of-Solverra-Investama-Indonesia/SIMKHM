<h4 class="card-title">Padanan Obat</h4>
<?php
$getSingle = $koneksimaster->query("SELECT * FROM master_obat WHERE kode_obat = '{$_GET['kodeObat']}'")->fetch_assoc();
?>
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
        $('#obat_kode_sepadan').select2();
    });
</script>
<div class="card shadow p-2 mb-1">
    <form method="post">
        <div class="row g-1">
            <div class="col-5 col-md-5">
                <select name="kode_obat" class="form-control w-100 obat-select form-control-sm mb-2" id="obat_kode">
                    <option value="<?= $getSingle['kode_obat'] ?>" selected><?= $getSingle['obat_master'] ?></option>
                </select>
            </div>
            <div class="col-1 col-md-1">
                <center>
                    <b>==</b>
                </center>
            </div>
            <div class="col-4 col-md-5">
                <select name="kode_obat_sepadan" class="form-control w-100 obat-select form-control-sm mb-2" id="obat_kode_sepadan">

                </select>
            </div>
            <div class="col-2 col-md-1">
                <button class="btn btn-sm btn-primary" name="addToPadanan">[+]</button>
            </div>
        </div>
    </form>
</div>
<?php
if (isset($_POST['addToPadanan'])) {
    $koneksimaster->query("INSERT INTO padanan_obat (kode_obat, kode_obat_padanan, petugas) VALUES ('{$_POST['kode_obat']}', '{$_POST['kode_obat_sepadan']}', '{$_SESSION['admin']['namalengkap']}')");
    $koneksimaster->query("INSERT INTO padanan_obat (kode_obat, kode_obat_padanan, petugas) VALUES ('{$_POST['kode_obat_sepadan']}', '{$_POST['kode_obat']}', '{$_SESSION['admin']['namalengkap']}')");
    // echo "<script>alert('Berhasil menambahkan padanan obat');</script>";
    exit("<script>location.href='?halaman=padanan_obat&kodeObat=$_GET[kodeObat]';</script>");
}
if (isset($_GET['hapus'])) {
    $koneksimaster->query("DELETE FROM padanan_obat WHERE id = '{$_GET['hapus']}'");
    // echo "<script>alert('Berhasil menghapus padanan obat');</script>";
    exit("<script>alert('Successfully');location.href='?halaman=padanan_obat&kodeObat=$_GET[kodeObat]';</script>");
}
?>
<div class="card shadow p-2">
    <h6 class="mb-0">Padanan Obat</h6>
    <i style="font-size: 10px;" class="mb-2">Data yang dimunculkan adalah obat yang sudah ada pada sistem</i>
    <div class="table-responsive">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
        <script>
            $(document).ready(function() {
                $('#tabel_obat').DataTable({
                    "pageLength": 100,
                    "ordering": true,
                    "order": []
                });
            });
        </script>
        <table class="table table-sm table-hover table-striped" id="tabel_obat" style="font-size: 11px;">
            <thead>
                <tr>
                    <th>Kode Obat</th>
                    <th>Nama Obat</th>
                    <th>Margin</th>
                    <th>HargaBeli</th>
                    <th>KodeObatSepadan</th>
                    <th>NamaObatSepadan</th>
                    <th>MarginSepadan</th>
                    <th>HargaBeliSepadan</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $getData = $koneksimaster->query("SELECT * FROM padanan_obat ORDER BY id DESC");
                foreach ($getData as $data) {
                ?>
                    <?php
                    $getObat = $koneksi->query("SELECT * FROM apotek WHERE id_obat = '{$data['kode_obat']}' ORDER BY tgl_beli DESC LIMIT 1")->fetch_assoc();
                    $getObatPadanan = $koneksi->query("SELECT * FROM apotek WHERE id_obat = '{$data['kode_obat_padanan']}' ORDER BY tgl_beli DESC LIMIT 1")->fetch_assoc();
                    ?>
                    <tr>
                        <td><?= $getObat['id_obat'] ?></td>
                        <td><?= $getObat['nama_obat'] ?></td>
                        <td>U:<?= $getObat['margin_jual'] ?> | I:<?= $getObat['margininap'] ?> | R:<?= $getObat['margin_resep'] ?></td>
                        <td><?= $getObat['harga_beli'] ?></td>
                        <td><?= $getObatPadanan['id_obat'] ?></td>
                        <td><?= $getObatPadanan['nama_obat'] ?></td>
                        <td>U:<?= $getObatPadanan['margin_jual'] ?> | I:<?= $getObatPadanan['margininap'] ?> | R:<?= $getObatPadanan['margin_resep'] ?></td>
                        <td><?= $getObatPadanan['harga_beli'] ?></td>
                        <td>
                            <a href="index.php?halaman=padanan_obat&kodeObat=<?= $_GET['kodeObat'] ?>&hapus=<?= $data['id'] ?>" class="badge bg-danger text-light" style="font-size: 12px;"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>