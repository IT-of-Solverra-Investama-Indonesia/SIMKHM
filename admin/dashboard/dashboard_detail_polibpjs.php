<div class="card shadow p-2">
    <div class="table-responsive">
        <table class="table table-hover table-striped" id="myTable" style="font-size: 12px;">
            <thead>
                <tr>
                    <th>IdRawat</th>
                    <th>Tgl Kunjungan</th>
                    <th>Nama</th>
                    <th>NoRm</th>
                    <th>NoHp</th>
                    <th>CaraBayar</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                //   $getData = $koneksi->query("SELECT registrasi_rawat.*, pasien.nama_lengkap, pasien.nohp FROM registrasi_rawat INNER JOIN pasien ON pasien.no_rm = registrasi_rawat.no_rm WHERE DATE_FORMAT(jadwal, '%y/%m') = '" . htmlspecialchars($_GET['polibpjs']) . "' AND carabayar = 'bpjs' ORDER BY idrawat DESC");
                $getData = $koneksi->query("SELECT registrasi_rawat.* FROM registrasi_rawat WHERE DATE_FORMAT(jadwal, '%y/%m') = '" . htmlspecialchars($_GET['polibpjs']) . "' AND carabayar = 'bpjs' AND perawatan = 'Rawat Jalan' AND (status_antri = 'Datang' or status_antri = 'Pembayaran') ORDER BY idrawat DESC");
                foreach ($getData as $data) {
                ?>
                    <tr>
                        <td><?= $data['idrawat'] ?></td>
                        <td><?= $data['jadwal'] ?></td>
                        <td><?= $data['nama_pasien'] ?></td>
                        <td><?= $data['no_rm'] ?></td>
                        <td>
                            <?php
                            $getPasien = $koneksi->query("SELECT * FROM pasien WHERE no_rm = '$data[no_rm]'")->fetch_assoc();
                            ?>
                            <?= $getPasien['nohp'] ?? 'Data Tidak Cocok' ?>
                        </td>
                        <td><?= $data['carabayar'] ?></td>
                        <td><?= $data['status_antri'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
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
            searching: true,
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