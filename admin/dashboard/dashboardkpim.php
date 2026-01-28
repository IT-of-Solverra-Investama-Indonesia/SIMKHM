<h5 class="card-title">Dashboard KPIM</h5>
<div class="card shadow p-2">
    <form method="GET">
        <div class="row g-1">
            <div class="col-10">
                <input type="text" name="halaman" value="dashboardkpim" hidden id="">
                <select name="kpim" id="" required class="form-select form-select-sm">
                    <option value="" hidden>Pilih Dashboard KPIM</option>
                    <option value="perawat">Perawat</option>
                    <option value="kasir">Kasir</option>
                    <option value="pendaftaran">Pendaftaran</option>
                    <option value="ahliGiziJuruMasak">Ahli Gizi Juru Masak</option>
                    <option value="lab">Lab</option>
                    <option value="racik">Racik</option>
                    <option value="kebersihan">Kebersihan</option>
                    <option value="igd">IGD</option>
                    <option value="perawatInap">Perawat Inap</option>
                </select>
            </div>
            <div class="col-2">
                <button class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
            </div>
        </div>
    </form>
</div>
<div class="p-0">
<?php
    if (isset($_GET['kpim'])) {
        $kpim = htmlspecialchars($_GET['kpim']);
        include 'dashboardkpim_' . $kpim . '.php';
    }
?>
</div>