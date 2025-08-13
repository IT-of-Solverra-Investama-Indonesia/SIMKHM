<h3>Detail Dashboard</h3>
<div class="card shadow p-2">
    <form method="post">
        <div class="row">
            <div class="col-9">
                <select name="hal" id="" class="form-control">
                    <option value="Poli">Poli</option>
                    <option value="verif">Verif</option>
                    <option value="polibulan">Poli Bulan</option>
                    <option value="polibpjs">Poli BPJS</option>
                    <option value="omsetKHM">Omset KHM Lama</option>
                    <option value="RekapPasienOnlineOffline">Rekap Pasien Online Offline</option>
                    <option value="cashflow">Cashflow (Dari akuntansi Aplikasi Lama)</option>
                    <option value="rataObatPasienBPJS">Rata Obat Pasien BPJS</option>
                    <option value="rataObatPasienBPJSperBulan">Rata Obat Pasien BPJS per Dokter Bulan</option>
                    <option value="rasioBHP">Rasio BHP</option>
                    <option value="rasioCashflow">Rasio Cashflow</option>
                </select>
            </div>
            <div class="col-3">
                <button name="searching" class="btn btn-primary"><i class="bi bi-search"></i></button>
            </div>
        </div>
    </form>
</div>
<?php
include "../dist/baseUrlAPI.php";
if (isset($_POST['searching'])) {
    $hal = htmlspecialchars($_POST['hal']);
    if ($hal == 'polibulan' or $hal == 'polibpjs') {
        echo "<script>document.location='index.php?halaman=dashboard_detail&$hal=" . date('y/m') . "'</script>";
    } else {
        echo "<script>document.location='index.php?halaman=dashboard_detail&$hal'</script>";
    }
}
?>
<!-- <a href="index.php?halaman=dashboard_detail&Poli" class="btn btn-sm btn-primary m-1" style="max-width: 190px; float: left;">Poli</a>
<a href="index.php?halaman=dashboard_detail&verif" class="btn btn-sm btn-primary m-1" style="max-width: 190px; float: left;">Verif</a>
<a href="index.php?halaman=dashboard_detail&polibulan=<?= date('y/m') ?>" class="btn btn-sm btn-primary m-1" style="max-width: 190px; float: left;">Poli Bulan</a>
<a href="index.php?halaman=dashboard_detail&polibpjs=<?= date('y/m') ?>" class="btn btn-sm btn-primary m-1" style="max-width: 190px; float: left;">Poli Bpjs</a>
<a href="index.php?halaman=dashboard_detail&omsetKHM" class="btn btn-sm btn-primary m-1" style="max-width: 190px; float: left;">Omset KHM</a>
<a href="index.php?halaman=dashboard_detail&RekapPasienOnlineOffline" class="btn btn-sm btn-primary m-1" style="max-width: 190px; float: left;">Rekap Pasien Online Offline</a> -->
<br>
<?php
if (isset($_GET['Poli'])) {
    include '../dashboard/dashboard_detail_Poli.php';
} elseif (isset($_GET['verif'])) {
    include '../dashboard/dashboard_detail_verif.php';
} elseif (isset($_GET['polibulan'])) {
    include '../dashboard/dashboard_detail_polibulan.php';
} elseif (isset($_GET['polibpjs'])) {
    include '../dashboard/dashboard_detail_polibpjs.php';
} elseif (isset($_GET['omsetKHM'])) {
    include '../dashboard/dashboard_detail_omsetKHM.php';
} elseif (isset($_GET['RekapPasienOnlineOffline'])) {
    include '../dashboard/dashboard_detail_RekapPasienOnlineOffline.php';
} elseif (isset($_GET['cashflow'])) {
    include '../dashboard/dashboard_detail_cashflow.php';
} elseif (isset($_GET['rataObatPasienBPJS'])) {
    include '../dashboard/dashboard_detail_rataObatPasienBPJS.php';
} elseif (isset($_GET['rataObatPasienBPJSperBulan'])) {
    include '../dashboard/dashboard_detail_rataObatPasienBPJSperBulan.php';
}elseif (isset($_GET['rasioBHP'])) {
    include '../dashboard/dashboard_detail_rasioBHP.php';
} elseif (isset($_GET['rasioBHP'])) {
    include '../dashboard/dashboard_detail_rasioBHP.php';
} elseif (isset($_GET['rasioCashflow'])) {
    include '../dashboard/dashboard_detail_rasioCashflow.php';
} else { ?>
    <div class="card shadow p-2">
        <span><i>Pilih Terlebih Dahulu Dashboard Yang Ingin Anda Lihat</i></span>
    </div>
<?php } ?>