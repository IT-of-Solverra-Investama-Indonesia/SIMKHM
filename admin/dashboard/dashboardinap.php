<div>
    <h5 class="card-title">Dashboard Inap</h5>
    <div class="card shadow p-2 mb-2">
        <form method="get">
            <input type="text" hidden name="halaman" value="dashboardinap" id="">
            <div class="row g-1">
                <div class="col-9">
                    <select name="dashboardinap" id="" required class="form-control form-control-sm">
                        <option value="">Pilih Jenis Dashboard</option>
                        <option value="dashboardinapglobal">Dashboard Inap Global</option>
                    </select>
                </div>
                <div class="col-3">
                    <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </div>
        </form>
    </div>
    <?php if (isset($_GET['dashboardinap'])) { ?>
        <?php
        include '../dashboard/dashboardinap_' . $_GET['dashboardinap'] . '.php'; // Include the global dashboard for inpatient
        ?>
    <?php } else { ?>
        <div class="card shadow p-2">
            <i>Silahkan Pilih Jenis Dashboard</i>
        </div>
    <?php } ?>
</div>