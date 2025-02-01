<?php if(!isset($_GET['mulai']) and !isset($_GET['hingga']) and !isset($_GET['val'])){?>
    <h3>Poli Daerah</h3>
    <div class="card shadow p-2 mb-2">
        <form method="post">
            <div class="row">
                <div class="col-md-4">
                    <label >Mulai Tanggal</label>
                    <input type="date" class="form-control" name="mulai">
                </div>
                <div class="col-md-4">
                    <label >Hingga Tanggal</label>
                    <input type="date" class="form-control" name="hingga">
                </div>
                <div class="col-md-4">
                    <br>
                    <button type="submit" name="desa" class="btn btn-primary">PerDesa</button>
                    <button type="submit" name="diagnosis" class="btn btn-success">PerDiagnosis</button>
                </div>
            </div>
        </form>
    </div>
    <?php
        if(isset($_POST['desa'])){
            $tipe = 'Desa';
            $getData = $koneksi->query("SELECT *, COUNT(*) AS jumlah FROM registrasi_rawat INNER JOIN pasien ON pasien.nama_lengkap = registrasi_rawat.nama_pasien WHERE registrasi_rawat.jadwal >= '$_POST[mulai]' AND registrasi_rawat.jadwal <= '$_POST[hingga]' AND (status_antri = 'Datang' OR status_antri = 'Pembayaran') GROUP BY pasien.kelurahan");
        }
        if(isset($_POST['diagnosis'])){
            $tipe = 'Diagnosis';
            $getData = $koneksi->query("SELECT *, COUNT(*) AS jumlah, rekam_medis.diagnosis AS diagnosis_dokter FROM registrasi_rawat INNER JOIN rekam_medis ON rekam_medis.jadwal=registrasi_rawat.jadwal WHERE registrasi_rawat.jadwal >= '$_POST[mulai]' AND registrasi_rawat.jadwal <= '$_POST[hingga]' AND (status_antri = 'Datang' OR status_antri = 'Pembayaran') GROUP BY rekam_medis.diagnosis");
        }
    ?>
    <?php if(isset($_POST['desa']) or isset($_POST['diagnosis'])){?>
        <div class="card shadow p-2">
            <h5>Dicari Dari Tanggal <?= $_POST['mulai']?> Hingga <?= $_POST['hingga']?></h5>
            <table class="table" id="myTable">
                <thead>
                    <tr>
                        <th><?= $tipe?></th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($getData as $data){?>
                        <tr>
                            <td><?= $data['kelurahan'] ?? $data['diagnosis_dokter']?></td>
                            <td>
                                <a href="index.php?halaman=polidaerah&mulai=<?= $_POST['mulai']?>&hingga=<?= $_POST['hingga']?>&tipe=<?= $tipe?>&val=<?= $data['kelurahan'] ?? $data['diagnosis_dokter']?>"><?= $data['jumlah']?></a>
                            </td>
                        </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
    <?php }?>
<?php }else{?>
    <?php
        if($_GET['tipe'] != 'Diagnosis'){
            $getData = $koneksi->query("SELECT *, registrasi_rawat.jadwal AS jadwalFinal FROM registrasi_rawat INNER JOIN pasien ON pasien.nama_lengkap = registrasi_rawat.nama_pasien WHERE registrasi_rawat.jadwal >= '$_GET[mulai]' AND registrasi_rawat.jadwal <= '$_GET[hingga]' AND pasien.kelurahan = '$_GET[val]' AND (status_antri = 'Datang' OR status_antri = 'Pembayaran')");
        }else{
            $getData = $koneksi->query("SELECT *, registrasi_rawat.jadwal AS jadwalFinal, rekam_medis.diagnosis AS diagnosis_dokter FROM registrasi_rawat INNER JOIN rekam_medis ON rekam_medis.jadwal=registrasi_rawat.jadwal WHERE registrasi_rawat.jadwal >= '$_GET[mulai]' AND registrasi_rawat.jadwal <= '$_GET[hingga]' AND rekam_medis.diagnosis = '$_GET[val]' AND (status_antri = 'Datang' OR status_antri = 'Pembayaran')");
        }
    ?>
    <h4>Poli <?= $_GET['tipe']?> <?= $_GET['val']?> Pada Tanggal <?= $_GET['mulai']?> Hingga <?= $_GET['hingga']?></h4>
    <div class="card shadow p-2">
        <table class="table" id="myTable">
            <thead >
                <tr>
                    <th>No RM</th>
                    <th>Nama Pasien</th>
                    <th>Jadwal</th>
                    <th><?= $_GET['tipe']?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($getData as $data){?>
                    <tr>
                        <td><?= $data['no_rm']?></td>
                        <td><?= $data['nama_pasien']?></td>
                        <td><?= $data['jadwalFinal']?></td>
                        <td><?= $data['kelurahan'] ?? $data['diagnosis_dokter']?></td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
<?php }?>

<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" />
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script>
    $(document).ready( function () {
        $('#myTable').DataTable({
            order :{
                idx:1,
                dir:'desc'
            }
        });
    } );
</script>