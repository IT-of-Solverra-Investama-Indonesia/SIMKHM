<div>
    <h5 class="card-title">Data Pasien Rujuk dan Rekap Perujuk</h5>
    <div class="card shadow p-2">
        <div class="table-reponsive">
            <table class="table table-hover table-striped table-sm" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NoRM</th>
                        <th>Jadwal</th>
                        <th>DiRujuk</th>
                        <th>RujukKe</th>
                        <th>AlsanRujuk</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $getData = $koneksi->query("SELECT nama_pasien, no_rm, tgl_masuk, 'Dari IGD' as dariIGD, tindak_rujuk, tindak_rujuk_keterangan FROM igd WHERE tindak_rujuk != '' UNION SELECT pasien, norm, tgl, 'Dari Rawat Inap', rujuk, rujuk_keterangan FROM pulang WHERE rujuk != '' ORDER BY tgl_masuk DESC");
                    foreach($getData as $data){
                    ?>
                    <tr>
                        <td><?= $no++?></td>
                        <td><?= $data['nama_pasien']?></td>
                        <td><?= $data['no_rm']?></td>
                        <td><?= $data['tgl_masuk']?></td>
                        <td><?= $data['dariIGD']?></td>
                        <td><?= $data['tindak_rujuk']?></td>
                        <td><?= $data['tindak_rujuk_keterangan']?></td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
    </div>
</div>