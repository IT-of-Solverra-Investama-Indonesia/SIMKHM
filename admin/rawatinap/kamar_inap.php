<div>
    <h3>Data Kamar dan Tarif</h3>
    <button class="btn btn-sm btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#addKamar">[+] Tambah</button>
    <!-- Modal -->
    <div class="modal fade" id="addKamar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Kamar</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form method="POST">
              <div class="modal-body">
                <input type="text" name="namakamar" class="form-control mb-2" placeholder="Nama Kamar">
                <input type="number" name="tarif" class="form-control mb-2" placeholder="Tarif Kamar">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="saveKamar" class="btn btn-primary">Simpan</button>
              </div>
          </form>
        </div>
      </div>
    </div>
    <?php
        if(isset($_POST['saveKamar'])){
            $koneksi->query("INSERT INTO kamar (namakamar, tarif) VALUES ('".htmlspecialchars($_POST['namakamar'])."', '".htmlspecialchars($_POST['tarif'])."')");

            echo "
                <script>
                    alert('Successfully');
                    document.location.href='index.php?halaman=kamar_inap';
                </script>
            ";
        }
    ?>

    <div class="card shadow-sm p-2">
        <div class="table-responsive">
            <table class="table table-hover table-striped" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kamar</th>
                        <th>Tarif</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $getKamar = $koneksi->query("SELECT * FROM kamar ORDER BY urut ASC");
                        $no = 1;
                        foreach($getKamar as $data){
                    ?>
                        <tr>
                            <td><?= $no++?></td>
                            <td><?= $data['namakamar']?></td>
                            <td><?= $data['tarif']?></td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="upKamar('<?= $data['urut']?>','<?= $data['namakamar']?>','<?= $data['tarif']?>')" data-bs-toggle="modal" data-bs-target="#updateKamar">Update</button>
                            </td>
                        </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        function upKamar(urut, namakamar, tarif) {
            var urut_var =document.getElementById('urut_id');
            var namakamar_var =document.getElementById('namakamar_id');
            var tarif_var =document.getElementById('tarif_id');

            urut_var.value = urut;
            namakamar_var.value = namakamar;
            tarif_var.value = tarif;
        }
    </script>
    <!-- Modal -->
    <div class="modal fade" id="updateKamar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">Update Kamar</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form method="POST">
              <div class="modal-body">
                <input type="text" name="urut" hidden id="urut_id" class="form-control mb-2" placeholder="Urutan">
                <input type="text" name="namakamar" id="namakamar_id" class="form-control mb-2" placeholder="Nama Kamar">
                <input type="text" name="tarif" id="tarif_id" class="form-control mb-2" placeholder="Tarif Per Hari">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="updateKamarAction" class="btn btn-primary">Simpan</button>
              </div>
          </form>
        </div>
      </div>
    </div>
    <?php
        if(isset($_POST['updateKamarAction'])){
            $koneksi->query("UPDATE kamar SET namakamar = '".htmlspecialchars($_POST['namakamar'])."', tarif = '".htmlspecialchars($_POST['tarif'])."' WHERE urut = '".htmlspecialchars($_POST['urut'])."'");

            echo "
                <script>
                    alert('Successfully');
                    document.location.href='index.php?halaman=kamar_inap';
                </script>
            ";
        }  
    ?>
</div>
