<div class="">
    <div class="card shadow p-2 mb-1">
        <h5>Riwayat Kajian Awal Poli</h5>
        <div class="table">
            <table class="table table-sm table-hover table-striped" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $getKajianAwalPoli = $koneksi->query("SELECT kajian_awal.*, rr.idrawat FROM kajian_awal LEFT JOIN registrasi_rawat rr ON DATE_FORMAT(rr.jadwal, '%Y-%m-%d') = kajian_awal.tgl_rm AND rr.no_rm = kajian_awal.norm  WHERE norm = '$_GET[norm]' AND rr.perawatan='Rawat Jalan' ORDER BY id_rm DESC");
                    $no = 1;
                    foreach ($getKajianAwalPoli as $kajian) {
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $kajian['tgl_rm']; ?></td>
                            <td><?php echo $kajian['nama_pasien']; ?></td>
                            <td>
                                <span class="badge bg-primary open-riwayat-modal" style="cursor: pointer;" data-url="index.php?halaman=resume&id=<?= urlencode($kajian['idrawat']) ?>&norm=<?= urlencode($kajian['norm']) ?>&ubah&kj=<?= urlencode($kajian['id_rm']) ?>"><i class="bi bi-eye"></i> Lihat</span>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card shadow p-2 mb-1">
        <h5>Riwayat Kajian Awal Ranap</h5>
        <div class="table">
            <table class="table table-sm table-hover table-striped" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $getKajianAwalRanap = $koneksi->query("SELECT kajian_awal.*, rr.idrawat FROM kajian_awal LEFT JOIN registrasi_rawat rr ON DATE_FORMAT(rr.jadwal, '%Y-%m-%d') = kajian_awal.tgl_rm AND rr.no_rm = kajian_awal.norm  WHERE norm = '$_GET[norm]' AND rr.perawatan='Rawat Inap' ORDER BY id_rm DESC");
                    $no = 1;
                    foreach ($getKajianAwalRanap as $kajian) {
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $kajian['tgl_rm']; ?></td>
                            <td><?php echo $kajian['nama_pasien']; ?></td>
                            <td>
                                <span class="badge bg-primary open-riwayat-modal" style="cursor: pointer;" data-url="index.php?halaman=resumeinap&id=<?= urlencode($kajian['idrawat']) ?>&norm=<?= urlencode($kajian['norm']) ?>&ubah&kj=<?= urlencode($kajian['id_rm']) ?>"><i class="bi bi-eye"></i> Lihat</span>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card shadow p-2 mb-1">
        <h5>Riwayat Rekam Medis Poli</h5>
        <div class="table">
            <table class="table table-sm table-hover table-striped" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $getRekamMedisPoli = $koneksi->query("SELECT rekam_medis.*, rr.idrawat FROM rekam_medis LEFT JOIN registrasi_rawat rr ON DATE_FORMAT(rr.jadwal, '%Y-%m-%d') = rekam_medis.tgl_rm AND rr.no_rm = rekam_medis.norm WHERE norm = '$_GET[norm]' ORDER BY id_rm DESC");
                    $no = 1;
                    foreach ($getRekamMedisPoli as $rm) {
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $rm['jadwal']; ?></td>
                            <td><?php echo $rm['nama_pasien']; ?></td>
                            <td>
                                <span class="badge bg-primary open-riwayat-modal" style="cursor: pointer;" data-url="index.php?halaman=detailrm&id=<?= urlencode($rm['norm']) ?>&tgl=<?= urlencode($rm['tgl_rm']) ?>&rawat=<?= urlencode($rm['idrawat']) ?>&cekrm&idrekammedis=<?= urlencode($rm['id_rm']) ?>"><i class="bi bi-eye"></i> Lihat</span>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card shadow p-2 mb-1">
        <h5>Riwayat IGD</h5>
        <div class="table">
            <table class="table table-sm table-hover table-striped" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $getRiwayatIgd = $koneksi->query("SELECT idigd, tgl_masuk, nama_pasien FROM igd WHERE no_rm = '$_GET[norm]' ORDER BY idigd DESC");
                    $no = 1;
                    foreach ($getRiwayatIgd as $riwayatIgd) {
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $riwayatIgd['tgl_masuk']; ?></td>
                            <td><?php echo $riwayatIgd['nama_pasien']; ?></td>
                            <td>
                                <span class="badge bg-primary open-riwayat-modal" style="cursor: pointer;" data-url="index.php?halaman=detailigd&id=<?= urlencode($riwayatIgd['idigd']) ?>"><i class="bi bi-eye"></i> Lihat</span>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card shadow p-2 mb-1">
        <h5>Riwayat Catatan Penyakit (Ranap)</h5>
        <div class="table">
            <table class="table table-sm table-hover table-striped" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $getCatatanPenyakit = $koneksi->query("SELECT * FROM ctt_penyakit_inap WHERE norm = '$_GET[norm]' ORDER BY id DESC");
                    $no = 1;
                    foreach ($getCatatanPenyakit as $cppt) {
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $cppt['tgl']; ?></td>
                            <td><?php echo $cppt['pasien']; ?></td>
                            <td>
                                <span class="badge bg-primary open-cppt-modal" style="cursor: pointer;"
                                    data-id="<?= htmlspecialchars($cppt['id'], ENT_QUOTES) ?>"
                                    data-tgl="<?= htmlspecialchars($cppt['tgl'], ENT_QUOTES) ?>"
                                    data-pasien="<?= htmlspecialchars($cppt['pasien'], ENT_QUOTES) ?>"
                                    data-object="<?= htmlspecialchars($cppt['object'], ENT_QUOTES) ?>"
                                    data-assesment="<?= htmlspecialchars($cppt['assesment'], ENT_QUOTES) ?>"
                                    data-plan="<?= htmlspecialchars($cppt['plan'], ENT_QUOTES) ?>"
                                    data-edukasi="<?= htmlspecialchars($cppt['edukasi'], ENT_QUOTES) ?>"><i class="bi bi-eye"></i> Lihat</span>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card shadow p-2">
        <h5>Riwayat Laboratorium</h5>
        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>NoRM</th>
                        <th>TglInap</th>
                        <th>TglPengisian</th>
                        <th>Pemeriksaan</th>
                        <!-- <th>Act</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $norm = sani($_GET['norm']);
                    // $tgl = sani($_GET['tgl']);
                    $getRiwayat = $koneksi->query("SELECT * FROM lab_hasil WHERE norm = '$norm' GROUP BY tgl_hasil ORDER BY tgl_inap DESC");
                    foreach ($getRiwayat as $riwayat) {

                    ?>
                        <tr>
                            <td><?= $riwayat['pasien'] ?></td>
                            <td><?= $riwayat['norm'] ?></td>
                            <td><?= $riwayat['tgl_inap'] ?></td>
                            <td><?= $riwayat['tgl_hasil'] ?></td>
                            <td>
                                <?php
                                $labDetailUrl = '';
                                if ($riwayat['id_inap'] != '0') {
                                    $labDetailUrl = "index.php?halaman=detaillabinap&id=" . urlencode($riwayat['id_inap']) . "&tgl=" . urlencode(date('Y-m-d', strtotime($riwayat['tgl_hasil'])));
                                } elseif ($riwayat['id_lab_h'] != '0') {
                                    $labDetailUrl = "index.php?halaman=detaillab2&id=" . urlencode($riwayat['id_lab_h']);
                                } elseif ($riwayat['id_igd'] != '0') {
                                    $labDetailUrl = "index.php?halaman=detaillabigd&id=" . urlencode($riwayat['id_igd']) . "&tgl=" . urlencode(date('Y-m-d', strtotime($riwayat['tgl_hasil'])));
                                }
                                ?>

                                <?php if ($labDetailUrl != '') { ?>
                                    <span class="btn btn-sm btn-primary open-riwayat-modal" style="cursor: pointer;" data-url="<?= htmlspecialchars($labDetailUrl, ENT_QUOTES) ?>">
                                        <i class="bi bi-eye"></i>
                                    </span>
                                <?php } ?>
                            </td>
                            <!-- <td><?= $riwayat['pasien'] ?></td> -->
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Pemeriksaan dan Hasil</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <span id="hasilPemeriksaan"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                        <!-- <button type="button" class="btn btn-sm btn-primary">Understood</button> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="riwayatIframeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Riwayat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" style="min-height: 75vh;">
                <iframe id="riwayatDetailIframe" src="" style="width: 100%; height: 75vh; border: 0;" loading="lazy"></iframe>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cpptDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail CPPT</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    <div class="col-md-2">
                        <label class="form-label">ID</label>
                        <input type="text" id="cppt_id" class="form-control form-control-sm" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tanggal</label>
                        <input type="text" id="cppt_tgl" class="form-control form-control-sm" readonly>
                    </div>
                    <div class="col-md-7">
                        <label class="form-label">Pasien</label>
                        <input type="text" id="cppt_pasien" class="form-control form-control-sm" readonly>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Object</label>
                        <textarea id="cppt_object" class="form-control form-control-sm"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Assesment</label>
                        <textarea id="cppt_assesment" class="form-control form-control-sm"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Plan</label>
                        <textarea id="cppt_plan" class="form-control form-control-sm"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Edukasi</label>
                        <textarea id="cppt_edukasi" class="form-control form-control-sm" rows="3" readonly></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/37.1.0/classic/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const iframeModalEl = document.getElementById('riwayatIframeModal');
        const iframeEl = document.getElementById('riwayatDetailIframe');
        const cpptModalEl = document.getElementById('cpptDetailModal');
        const iframeModal = bootstrap.Modal.getOrCreateInstance(iframeModalEl);
        const cpptModal = bootstrap.Modal.getOrCreateInstance(cpptModalEl);

        const cpptFields = {
            id: document.getElementById('cppt_id'),
            tgl: document.getElementById('cppt_tgl'),
            pasien: document.getElementById('cppt_pasien'),
            object: document.getElementById('cppt_object'),
            assesment: document.getElementById('cppt_assesment'),
            plan: document.getElementById('cppt_plan'),
            edukasi: document.getElementById('cppt_edukasi')
        };

        const cpptEditors = {
            object: null,
            assesment: null,
            plan: null,
            edukasi: null
        };

        function setEditorOrTextarea(key, value) {
            if (cpptEditors[key]) {
                cpptEditors[key].setData(value || '');
            } else if (cpptFields[key]) {
                cpptFields[key].value = value || '';
            }
        }

        function initCpptEditors() {
            const editorTargets = [{
                    key: 'object',
                    selector: '#cppt_object'
                },
                {
                    key: 'assesment',
                    selector: '#cppt_assesment'
                },
                {
                    key: 'plan',
                    selector: '#cppt_plan'
                },
                {
                    key: 'edukasi',
                    selector: '#cppt_edukasi'
                }
            ];

            editorTargets.forEach(function(item) {
                if (cpptEditors[item.key] || typeof ClassicEditor === 'undefined') return;
                const target = document.querySelector(item.selector);
                if (!target) return;

                ClassicEditor
                    .create(target, {
                        toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'undo', 'redo']
                    })
                    .then(function(editor) {
                        cpptEditors[item.key] = editor;
                        editor.enableReadOnlyMode('cpptReadOnly');
                    })
                    .catch(function(error) {
                        console.error(error);
                    });
            });
        }

        document.querySelectorAll('.open-riwayat-modal').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const url = btn.getAttribute('data-url') || '';
                iframeEl.src = url;
                iframeModal.show();
            });
        });

        iframeModalEl.addEventListener('hidden.bs.modal', function() {
            iframeEl.src = '';
        });

        document.querySelectorAll('.open-cppt-modal').forEach(function(btn) {
            btn.addEventListener('click', function() {
                initCpptEditors();

                cpptFields.id.value = btn.getAttribute('data-id') || '';
                cpptFields.tgl.value = btn.getAttribute('data-tgl') || '';
                cpptFields.pasien.value = btn.getAttribute('data-pasien') || '';
                setEditorOrTextarea('object', btn.getAttribute('data-object') || '');
                setEditorOrTextarea('assesment', btn.getAttribute('data-assesment') || '');
                setEditorOrTextarea('plan', btn.getAttribute('data-plan') || '');
                setEditorOrTextarea('edukasi', btn.getAttribute('data-edukasi') || '');

                cpptModal.show();
            });
        });
    });
</script>