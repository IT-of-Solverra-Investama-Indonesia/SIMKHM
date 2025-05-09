<?php
function getFullUrl()
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ||
        $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

    return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}
$arrayListHalamanLain = array('tambahMaster', 'editMaster', 'deleteMaster');
$getParams = array_keys($_GET);
?>
<div class="pagetitle">
    <h1>Master Poli (Template)</h1>
</div>
<?php if (empty(array_intersect($arrayListHalamanLain, $getParams))) { ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#icd10_id').select2({
                dropdownParent: $('#updateModal')
            });
        });
    </script>
    <a href="<?= getFullUrl(); ?>&tambahMaster" class="btn btn btn-sm btn-primary mb-2" style="max-width: 200px;">[+] Tambah Master Poli</a>
    <div class="card shadow p-2">
        <div class="table-responsive">
            <table class="table table-sm table-hover table-striped" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Oleh</th>
                        <th>Diagnosis</th>
                        <th>ICD10</th>
                        <th>KeluhanUtama</th>
                        <th>KeluhanTambahan(Anamnesa)</th>
                        <th>Objective</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $whereContionSelectMaster = "";
                    $query = "SELECT * FROM master_poli WHERE id != '' " . $whereContionSelectMaster . " ORDER BY id DESC";
                    $getData = $koneksimaster->query($query);
                    foreach ($getData as $data) {
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $data['user'] ?></td>
                            <td><?= $data['diagnosis'] ?></td>
                            <td><?= $data['icd10'] ?></td>
                            <td><?= $data['keluhan_utama'] ?></td>
                            <td><?= $data['keluhan_tambahan'] ?></td>
                            <td><?= $data['objective'] ?></td>
                            <td>
                                <button type="button" onclick="upDataUpdate('<?= $data['id'] ?>', '<?= $data['diagnosis'] ?>', '<?= $data['keluhan_utama'] ?>', '<?= $data['keluhan_tambahan'] ?>', '<?= $data['objective'] ?>', '<?= $data['icd10'] ?>')" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#updateModal"><i class="bi bi-pencil"></i></button>
                                <a href="<?= getFullUrl(); ?>&deleteMaster=<?= $data['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        function upDataUpdate(id, diagnosis, keluhan_utama, keluhan_tambahan, objective, icd10) {
            document.getElementById('diagnosis_id').value = diagnosis;
            document.getElementById('keluhan_utama_id').value = keluhan_utama;
            document.getElementById('keluhan_tambahan_id').value = keluhan_tambahan;
            document.getElementById('objective_id').value = objective;
            document.getElementById('icd10_id').value = icd10;
            document.getElementById('id_id').value = id;
        }
    </script>
    <!-- Modal -->
    <div class="modal fade" id="updateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Update Modal</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <div class="row g-2">
                            <div class="col-12">
                                <p class="mb-0" align="left">Diagnosis</p>
                                <input type="text" class="form-control form-control-sm mb-2" placeholder="Diagnosis" id="diagnosis_id" name="diagnosis">
                                <input type="text" class="form-control form-control-sm mb-2" placeholder="Diagnosis" id="id_id" name="id" hidden>
                            </div>
                            <div class="col-12">
                                <p class="mb-0" align="left">ICD10</p>
                                <select name="icd10" id="icd10_id" class="form-control form-control-sm">
                                    <?php
                                    $getICD = $koneksimaster->query("SELECT * FROM icds ORDER BY code ASC");
                                    foreach ($getICD as $dataICD) {
                                    ?>
                                        <option value="<?= $dataICD['code'] ?>"><?= $dataICD['code'] ?> - <?= $dataICD['name_en'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-6">
                                <p class="mb-0" align="left">Keluhan Utama</p>
                                <textarea id="keluhan_utama_id" name="keluhan_utama" placeholder="Keluhan Utama" class="form-control form-control-sm mb-2" id=""></textarea>
                            </div>
                            <div class="col-6">
                                <p class="mb-0" align="left">Keluhan Tambahan</p>
                                <textarea id="keluhan_tambahan_id" name="keluhan_tambahan" placeholder="Keluhan Tambahan" class="form-control form-control-sm mb-2" id=""></textarea>
                            </div>
                            <div class="col-12">
                                <p class="mb-0" align="left">Objective</p>
                                <textarea id="objective_id" name="objective" placeholder="Objective" class="form-control form-control-sm mb-2" id=""></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="editMaster" class="btn btn-sm btn-warning">Save Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
    if (isset($_POST['editMaster'])) {
        $id = htmlspecialchars($_POST['id']);
        $diagnosis = htmlspecialchars($_POST['diagnosis']);
        $icd10 = htmlspecialchars($_POST['icd10']);
        $keluhan_utama = htmlspecialchars($_POST['keluhan_utama']);
        $keluhan_tambahan = htmlspecialchars($_POST['keluhan_tambahan']);
        $objective = htmlspecialchars($_POST['objective']);

        $queryUpdateMaster = "UPDATE master_poli SET diagnosis='$diagnosis', icd10='$icd10', keluhan_utama='$keluhan_utama', keluhan_tambahan='$keluhan_tambahan', objective='$objective' WHERE id='$id'";
        $koneksimaster->query($queryUpdateMaster);
        echo "<script>alert('Berhasil mengupdate master poli');</script>";
        echo "<script>window.location.href='" . str_replace('&editMaster', '', getFullUrl()) . "';</script>";
    }
    ?>
<?php } elseif (isset($_GET['tambahMaster'])) { ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <center>
        <div class="card shadow-sm p-2" style="max-width: 600px;">
            <h4>Tambah Master</h4>
            <form method="POST">
                <div class="row g-2">
                    <div class="col-12">
                        <p class="mb-0" align="left">Diagnosis</p>
                        <input type="text" class="form-control form-control-sm mb-2" placeholder="Diagnosis" name="diagnosis">
                    </div>
                    <div class="col-12">
                        <p class="mb-0" align="left">ICD10</p>
                        <select name="icd10" id="icd10" class="form-control form-control-sm">
                            <?php
                            $getICD = $koneksimaster->query("SELECT * FROM icds ORDER BY code ASC");
                            foreach ($getICD as $dataICD) {
                            ?>
                                <option value="<?= $dataICD['code'] ?>"><?= $dataICD['code'] ?> - <?= $dataICD['name_en'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-6">
                        <p class="mb-0" align="left">Keluhan Utama</p>
                        <textarea name="keluhan_utama" placeholder="Keluhan Utama" class="form-control form-control-sm mb-2" id=""></textarea>
                    </div>
                    <div class="col-6">
                        <p class="mb-0" align="left">Keluhan Tambahan</p>
                        <textarea name="keluhan_tambahan" placeholder="Keluhan Tambahan" class="form-control form-control-sm mb-2" id=""></textarea>
                    </div>
                    <div class="col-12">
                        <p class="mb-0" align="left">Objective</p>
                        <textarea name="objective" placeholder="Objective" class="form-control form-control-sm mb-2" id=""></textarea>
                    </div>
                    <div class="col-12">
                        <a href="<?= str_replace('&tambahMaster', '', getFullUrl()) ?>" class="btn btn-dark btn-sm">Back</a>
                        <button class="btn btn-sm btn-primary" type="submit" name="saveMaster">Save Master</button>
                    </div>
                </div>
            </form>
        </div>
    </center>
    <script>
        $(document).ready(function() {
            $('#icd10').select2();
        });
    </script>
    <?php
    if (isset($_POST['saveMaster'])) {
        $diagnosis = htmlspecialchars($_POST['diagnosis']);
        $icd10 = htmlspecialchars($_POST['icd10']);
        $keluhan_utama = htmlspecialchars($_POST['keluhan_utama']);
        $keluhan_tambahan = htmlspecialchars($_POST['keluhan_tambahan']);
        $objective = htmlspecialchars($_POST['objective']);
        $user = htmlspecialchars($_SESSION['admin']['namalengkap']);

        $queryInsertMaster = "INSERT INTO master_poli (user, diagnosis, keluhan_utama, keluhan_tambahan, objective, icd10) VALUES ('$user', '$diagnosis', '$keluhan_utama', '$keluhan_tambahan', '$objective', '$icd10')";
        $koneksimaster->query($queryInsertMaster);
        echo "<script>alert('Berhasil menambah master poli');</script>";
        echo "<script>window.location.href='" . str_replace('&tambahMaster', '', getFullUrl()) . "';</script>";
    }
    ?>
<?php } elseif ($_GET['deleteMaster']) { ?>
    <?php
    $id = htmlspecialchars($_GET['deleteMaster']);
    $queryDeleteMaster = "DELETE FROM master_poli WHERE id = '$id'";
    $koneksimaster->query($queryDeleteMaster);
    echo "<script>alert('Berhasil menghapus master poli');</script>";
    echo "<script>window.location.href='" . str_replace('deleteMaster=', '', getFullUrl()) . "';</script>";
    ?>
<?php } ?>