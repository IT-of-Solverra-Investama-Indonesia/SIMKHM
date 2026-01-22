<div>
    <h5>Dashboard Staff</h5>
    <div class="card shadow p-2 m-0">
        <form method="get">
            <div class="row g-1">
                <div class="col-9">
                    <input type="hidden" name="halaman" value="dashboardstaff">
                    <select name="tipe" class="form-control form-control-sm" id="" required>
                        <option value="">Pilih Dashboard Staff</option>
                        <option value="rekapKunjunganPerPendaftaran">Rekap Kunjungan per Pendaftaran</option>
                        <option value="rekapPendapatanKasir">Rekap Pendapatan Kasir</option>
                        <option value="rekapKunjunganPerawatPoli">Rekap Kunjungan Perawat Poli</option>
                        <option value="rekapKunjunganDokterPoli">Rekap Kunjungan Dokter Poli</option>
                        <option value="rekapKunjunganDokterRanap">Rekap Kunjungan Dokter Ranap</option>
                        <option value="rekapStaffInap">Rekap Staff Inap</option>
                        <?php if($_SESSION['admin']['level'] == 'sup'){?>
                            <!-- <option value="rekapBonusKPIMStaff">Rekap Bonus KPIM Staff</option> -->
                        <?php }?>
                    </select>
                </div>
                <div class="col-3">
                    <button class="btn btn-sm btn-primary">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
    <?php if (isset($_GET['tipe'])) { ?>
        <br>
        <?php include "../dashboard/dashboardstaff_" . $_GET['tipe'] . ".php"; ?>
    <?php } ?>
</div>