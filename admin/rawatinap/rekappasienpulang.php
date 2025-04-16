<div class="pagetitle">
    <h1>Rekap Pasien Pulang </h1>
</div>
<div class="">
    <div class="card shadow p-2 mb-2">
        <form method="post">
            <div class="row">
                <div class="col-6">
                    <label for="">Dari Tanggal</label>
                    <input type="date" name="date_start" class="form-control " required>
                </div>
                <div class="col-6">
                    <label for="">Hingga Tanggal</label>
                    <input type="date" name="date_end" class="form-control " required>
                </div>
                <div class="col-12">
                    <p align="right" class="mb-0">
                        <button type="submit" name="rekap" class="btn btn-primary mt-3 btn-sm">Rekap</button>
                    </p>
                </div>
            </div>
        </form>
    </div>
    <div class="card shadow p-2">
        <div class="table-responsive">
            <table class="table table-hover table-striped" style="font-size: 12px;" id="myTable">
                <thead>
                    <tr>
                        <th>Nama Pasien</th>
                        <th>NoRm</th>
                        <th>Kamar</th>
                        <th>Jadwal</th>
                        <th>Tgl Pulang</th>
                        <th>Diagnosa Perawat</th>
                        <th>Perawat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($_POST['rekap'])) {
                        $date_start = $_POST['date_start'];
                        $date_end = $_POST['date_end'];
                        $query = "SELECT * FROM pulang WHERE tgl BETWEEN '$date_start' AND '$date_end' ORDER BY tgl DESC";
                        $getData = $koneksi->query($query);
                        foreach ($getData as $data) {
                    ?>
                            <tr>
                                <td><?= $data['pasien'] ?></td>
                                <td><?= $data['norm'] ?></td>
                                <td><?= $data['kamar'] ?></td>
                                <td><?= $data['tgl_masuk'] ?></td>
                                <td><?= $data['tgl'] ?></td>
                                <td><?= $data['diag_prwt'] ?></td>
                                <td><?= $data['perawat'] ?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/2.1.2/css/dataTables.dataTables.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.0/css/buttons.dataTables.css" />
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/2.1.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.0/js/dataTables.buttons.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.0/js/buttons.html5.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.0/js/buttons.print.js"></script>

<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
            paging: false,
            order: false,
            searching: true ,
        });
    });
</script>
<style>
    .dt-button {
        float: right !important;
        border: none;
        padding: 8px 16px !important;
        border-radius: 4px !important;
        cursor: pointer !important;
        margin-left: 10px !important;
    }
</style>