
<?php 
$user=$_SESSION['admin']['username'];
$level=$_SESSION['admin']['level'];


$ambil=$koneksi->query("SELECT * FROM daftartes;");


?>


 <!-- !-- DataTables  -->

<!-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css"> -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<br>
<br>
<br>

<div>
    <a href="index.php?halaman=tambahlab" target="_blank" class="btn btn-success">Tambah Data</a>

</div>
<br>
<br>
<br>
<div class="table-responsive">
    <table class="table" style="width:100%;" id="myTable" >
    <thead>
        <tr>
            <th>Nama Pemeriksaan</th>
            <th>Indikator</th>
            <th>Tipe</th>
            <th>Harga</th>
            <th>Aksi</th>

        </tr>

    </thead>

    <tbody>

            <?php while ($pecah=$ambil->fetch_assoc())  { ?>
        <tr>
            <td><?php echo $pecah["nama_tes"]; ?></td>
            <td><?php echo $pecah["indikator"]; ?></td>
            <td><?php echo $pecah["tipe"]; ?></td>
            <td><?php echo $pecah["harga_lab"]; ?></td>
            <td>
             <?php if ($level==='lab' or $level==='ceo' or $level==='kasir') : ?>
             <a href="index.php?halaman=ubahdaftar&id=<?php echo $pecah["idtes"]; ?>" class="btn btn-sm btn-success" style="text-decoration: none; margin-left: 1px; font-weight: bold;">Ubah</a>
             <?php endif ?>&nbsp;&nbsp;<a href="index.php?halaman=hapuslabdata&id=<?php echo $pecah["idtes"]; ?>" class="btn btn-sm btn-danger" style="text-decoration: none; font-weight: bold; margin-left: 2px;" onclick="return confirm('Anda yakin mau menghapus item ini ?')">Hapus</a></td>

        </tr>
        <?php } ?>

    </tbody>

</table>
</div>



<script>
    $(document).ready(function() {
        $('#myTable').DataTable( {
       
        paging: true,
        pageLength: 50,
        lengthChange: true,
        order: true,
        lengthMenu: [[10, 50, 25, 100, 300, -1], [10, 25, 100, 300, "All"]],
        dom: 'B<"clear">lfrtip',
         buttons: [
               'excel'
            ]
        } );
    } );
</script> 