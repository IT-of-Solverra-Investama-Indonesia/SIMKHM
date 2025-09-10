<div class="">
    <h3>Dashboard Keuangan</h3>
    <div class="card shadow p-2 mb-2">
        <form method="post">
            <div class="row g-1">
                <div class="col-10">
                    <select name="hal" id="" class="form-control form-control-sm" required>
                        <option value="" hidden>Pilih Dashboard</option>
                        <option value="omsetKHM">Omset KHM Lama</option>
                        <option value="cashflow">Cashflow (Dari akuntansi Aplikasi Lama)</option>
                        <option value="rasioBHP">Rasio BHP</option>
                        <option value="rasioCashflow">Rasio Cashflow</option>
                    </select>
                </div>
                <div class="col-2">
                    <button name="searching" class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
                </div>
            </div>
        </form>
    </div>
    <?php
    if (isset($_POST['searching'])) {
        $hal = htmlspecialchars($_POST['hal']);
        if ($hal == 'polibulan' or $hal == 'polibpjs') {
            echo "<script>document.location='index.php?halaman=dashboardkeuangan&$hal=" . date('y/m') . "'</script>";
        } else {
            echo "<script>document.location='index.php?halaman=dashboardkeuangan&$hal'</script>";
        }
    }

    include "../dist/baseUrlAPI.php";
    ?>
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
    } elseif (isset($_GET['rasioBHP'])) {
        include '../dashboard/dashboard_detail_rasioBHP.php';
    } elseif (isset($_GET['rasioBHP'])) {
        include '../dashboard/dashboard_detail_rasioBHP.php';
    } elseif (isset($_GET['rasioCashflow'])) {
        include '../dashboard/dashboard_detail_rasioCashflow.php';
    } else { ?>
        <div class="card shadow p-2 mt-0">
            <span><i>Pilih Terlebih Dahulu Dashboard Yang Ingin Anda Lihat</i></span>
        </div>
    <?php } ?>
</div>