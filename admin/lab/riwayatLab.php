<div class="">
    <h3>Riwayat Lab</h3>
    <div class="card shadow p-2">
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
                    $norm = sani($_GET['rm']);
                    $tgl = sani($_GET['tgl']);
                    $getRiwayat = $koneksi->query("SELECT * FROM lab_hasil WHERE norm = '$norm' GROUP BY tgl_hasil ORDER BY tgl_inap DESC");
                    foreach ($getRiwayat as $riwayat) {

                    ?>
                        <tr>
                            <td><?= $riwayat['pasien'] ?></td>
                            <td><?= $riwayat['norm'] ?></td>
                            <td><?= $riwayat['tgl_inap'] ?></td>
                            <td><?= $riwayat['tgl_hasil'] ?></td>
                            <td>
                                <?php if ($riwayat['id_inap'] != '0') { ?>
                                    <a href="../lab/printlabinap.php?id=<?= $riwayat['id_inap'] ?>&tgl=<?= date('Y-m-d', strtotime($riwayat['tgl_inap'])) ?>" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>


                                <?php } else if ($riwayat['id_lab_h'] != '0') { ?>
                                    <a href="../lab/printlab.php?id=<?= $riwayat['id_lab_h'] ?>" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                <?php } else { ?>
                                    <a href="../lab/printlabigd.php?id=<?= $riwayat['id_igd'] ?>&tgl=<?= date('Y-m-d', strtotime($riwayat['tgl_hasil'])) ?>" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
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