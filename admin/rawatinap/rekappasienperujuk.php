<div>
    <?php if (!isset($_GET['detail'])) { ?>
        <div class="">
            <h5 class="card-title">Data Perujuk Pasien</h5>
            <div class="card shadow p-2 mb-0">
                <form method="get">
                    <div class="row g-1">
                        <div class="col-5">
                            <input type="text" name="halaman" hidden value="rekappasienperujuk" id="">
                            <input onblur="this.type='text'" onfocus="(this.type='date')" placeholder="Dari Tanggal" name="date_start" class="form-control form-control-sm" value="<?= $date_start = $_GET['date_start'] ?? '0000-00-00' ?>" id="">
                        </div>
                        <div class="col-5">
                            <input onblur="this.type='text'" onfocus="(this.type='date')" placeholder="Hingga Tanggal" name="date_end" class="form-control form-control-sm" value="<?= $date_end = $_GET['date_end'] ?? date('Y-m-d') ?>" id="">
                        </div>
                        <div class="col-2">
                            <button class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card shadow p-2 mt-2">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-sm" style="font-size: 12px;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>NamaPerujuk</th>
                                <th>NoHpPerujuk</th>
                                <th>JumlahRujukan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $getDataPerujuk = $koneksi->query("SELECT *, COUNT(*) as jum FROM registrasi_rawat WHERE perujuk != '' AND DATE_FORMAT(jadwal, '%Y-%m-%d') BETWEEN '$date_start' AND '$date_end' group by perujuk, perujuk_hp order by perujuk asc");
                            $no = 1;
                            foreach ($getDataPerujuk as $perujuk) {
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <?= $date_start ?> - <?= $date_end ?>
                                    </td>
                                    <td><?= $perujuk['perujuk'] ?></td>
                                    <td><?= $perujuk['perujuk_hp'] ?></td>
                                    <td>
                                        <?= $perujuk['jum'] ?>
                                    </td>
                                    <td>
                                        <a href="index.php?halaman=rekappasienperujuk&detail&date_start=<?= $date_start ?>&date_end=<?= $date_end ?>&perujuk=<?= $perujuk['perujuk'] ?>&perujuk_hp=<?= $perujuk['perujuk_hp'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-eye"></i></a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php if (isset($_GET['detail'])) { ?>
        <div class="">
            <?php
            $date_start = $_GET['date_start'];
            $date_end = $_GET['date_end'];
            $perujuk = $_GET['perujuk'];
            $perujuk_hp = $_GET['perujuk_hp'];
            ?>
            <h5 class="card-title text-capitalize">Data Pasien Rujuk <?= $_GET['perujuk'] ?></h5>
            <div class="card shadow p-2">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-sm" style="font-size: 12px;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>NoRM</th>
                                <th>Jadwal</th>
                                <th>BuktiRujuk</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $getData = $koneksi->query("SELECT * FROM registrasi_rawat WHERE perujuk = '$perujuk' AND perujuk_hp = '$perujuk_hp' AND DATE_FORMAT(jadwal, '%Y-%m-%d') BETWEEN '$date_start' AND '$date_end' order by jadwal asc");
                            $no = 1;
                            foreach ($getData as $data) {
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $data['nama_pasien'] ?></td>
                                    <td><?= $data['no_rm'] ?></td>
                                    <td><?= $data['jadwal'] ?></td>
                                    <td>
                                        <?php if (empty($data['perujuk_file'])) { ?>
                                            <span class="badge bg-danger" style="font-size: 12px;">Tidak Ada</span>
                                        <?php } else { ?>
                                            <a href="../rawatinap/perujuk_bukti/<?= $data['perujuk_file'] ?>" class="badge bg-warning" style="font-size: 12px;"><i class="bi bi-eye"> Lihat</i></a>
                                        <?php } ?>
                                    </td>
                                    <td><?= $data['status_antri'] ?></td>
                                </tr>
                            <?php }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php } ?>
</div>