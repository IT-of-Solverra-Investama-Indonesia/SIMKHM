<div>
    <?php
    $date_start = $_GET['date_start'] ?? date('Y-m-01');
    $date_end = $_GET['date_end'] ?? date('Y-m-d');
    ?>
    <h3>Rasio Cashflow</h3>
    <div class="card shadow p-2 mb-2">
        <form method="get">
            <input type="text" hidden name="halaman" id="" value="dashboard_detail">
            <input type="text" hidden name="rasioCashflow" id="" value="">
            <div class="row">
                <div class="col-5">
                    <label for="date_start">Tanggal Awal</label>
                    <input type="date" name="date_start" id="date_start" class="form-control form-control-sm" value="<?= $date_start ?>">
                </div>
                <div class="col-5">
                    <label for="date_end">Tanggal Akhir</label>
                    <input type="date" name="date_end" id="date_end" class="form-control form-control-sm" value="<?= $date_end ?>">
                </div>
                <div class="col-2">
                    <br>
                    <button name="searching" class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
                </div>
            </div>
        </form>
    </div>
    <div class="card shadow p-2">
        <div class="table-responsive">
            <?php
            $uri = $_SERVER['REQUEST_URI'];
            $segments = explode('/', trim($uri, '/'));
            $folder = $segments[0];
            ?>
            <?php 
            if($folder != 'simkhm'){
                echo file_get_contents($baseUrlLama . "api_personal/api_rasioCashflow.php?getRasioCashflow&date_start=" . htmlspecialchars($date_start) . "&date_end=" . htmlspecialchars($date_end) . "&html");
            }else{
                echo file_get_contents("http://localhost:81/khm/api_personal/api_rasioCashflow.php?getRasioCashflow&date_start=" . htmlspecialchars($date_start) . "&date_end=" . htmlspecialchars($date_end) . "&html");
            }
            ?>
        </div>
    </div>
</div>