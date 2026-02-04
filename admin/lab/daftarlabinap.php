<?php
$user = $_SESSION['admin']['username'];
$level = $_SESSION['admin']['level'];


$ambil = $koneksi->query("SELECT * FROM lab JOIN registrasi_rawat WHERE id_lab_inap=idrawat AND perawatan='Rawat Inap' GROUP BY pasienlab, tgl ORDER BY idlab DESC;");


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

<div class="container">
    <div class="pagetitle">
        <h1>Daftar Rujukan Lab Inap</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php?halaman=daftarlabinap" style="color:blue;">Rujukan Lab Inap</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <!-- <?php if ($level === 'lab' or $level === 'ceo') : ?>
<a href="index.php?halaman=daftarlabdata" target="_blank" class="btn btn-success" style="width:200px; height:40px">Daftar Data Lab</a>
<?php endif ?> -->

    <br>
    <br>
    <br>
    <div class="table-responsive">
        <table class="table" style="width:100%;" id="myTable">
            <thead>
                <tr>
                    <th>Tgl</th>
                    <th>No RM</th>
                    <th>Nama</th>
                    <th>Aksi</th>

                </tr>

            </thead>

            <tbody>

                <?php foreach ($ambil as $pecah) : ?>
                    <tr>
                        <td><?php echo $pecah["tgl"]; ?></td>
                        <td><?php echo $pecah["normlab"]; ?></td>
                        <td><?php echo $pecah["pasienlab"]; ?></td>
                        <td>
                            <div class="dropdown">
                                <i data-bs-toggle="dropdown" style="color: blue; font-weight: bold; font-size: 20px;" class="bi bi-three-dots-vertical"></i>
                                <ul class="dropdown-menu">
                                    <li><a href="index.php?halaman=detaillabinap&id=<?php echo $pecah["idrawat"] ?>&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-eye-fill" style="color:blue;"></i> Detail</a></li>
                                    <li><a href="index.php?halaman=catatanlab&idrawat=<?php echo $pecah["idrawat"] ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-eyedropper" style="color:indigo"></i> Catatan Lab</a></li>
                                    <li><a href="index.php?halaman=isilabinap&id=<?php echo $pecah["idrawat"] ?>&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-card-list" style="color:blueviolet;"></i> Isi Data Lab</a></li>
                                    <li><a href="index.php?halaman=ubahhasilinap&id=<?php echo $pecah["idrawat"] ?>&tgl=<?php echo $pecah["tgl"]; ?>" class="dropdown-item" style="text-decoration: none; margin-left: 1px; font-weight: bold;"><i class="bi bi-pencil" style="color:hotpink;"></i> Ubah</a></li>
                                    <li><a href="index.php?halaman=hapuslabinap&id=<?php echo $pecah["idlab"]; ?>" class="dropdown-item" style="text-decoration: none; font-weight: bold; margin-left: 2px;" onclick="return confirm('Anda yakin mau menghapus item ini ?')">
                                            <i class="bi bi-trash" style="color:red;"></i> Hapus</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endforeach ?>

            </tbody>

        </table>
    </div>



    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({

                paging: true,
                pageLength: 50,
                sorting: true,
                lengthChange: true,
                lengthMenu: [
                    [10, 50, 25, 100, 300, -1],
                    [10, 25, 100, 300, "All"]
                ],
                dom: 'B<"clear">lfrtip',
                buttons: [
                    'excel'
                ]
            });
        });
    </script>