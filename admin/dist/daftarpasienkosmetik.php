<?php
$pasienkosmetik = $koneksi->query("SELECT * FROM pasien_kosmetik;");
if(isset($_GET['del'])){
    $koneksi->query("DELETE FROM pasien_kosmetik WHERE idpasien = '$_GET[del]'");
    echo "
        <script>
            alert('Berhasil Menghapus Data');
            document.location.href='index.php?halaman=daftarpasienkosmetik';
        </script>
    ";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>KHM WONOREJO</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <!-- DATATABLES -->
    <!-- !-- DataTables  -->

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
    <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <link src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link src="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css">
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>

    <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
</head>


<body>
    <main>
        <div class="container">
            <div class="pagetitle">
                <h1>Daftar Pasien Kosmetik</h1>

            </div><!-- End Page Title -->

            <section class="section  py-4">
                <div class="container">



                    <div class="row">
                        <div class="col-lg-12 col-md-12">

                            <div class="card">
                                <div class="card-body">
                                    <!-- <div style="margin-top: 50px; margin-bottom: 30px; text-align: right;">
               
                <a href="index.php?halaman=pasien" class="btn btn-primary"><i class="bi bi-plus"></i> Pasien</a>

                </div> -->
                                    <h5 class="card-title">Data Pasien Kosmetik</h5>

                                    <!-- Multi Columns Form -->
                                    <div class="table-responsive">
                                        <table id="myTable" class="table table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Pasien</th>
                                                    <th>Tgl Lahir</th>
                                                    <th>Email</th>
                                                    <th></th>
                                                    <!-- <th>Aksi</th> -->

                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php $no = 1 ?>

                                                <?php foreach ($pasienkosmetik as $pecah) : ?>

                                                    <tr>
                                                        <td><?php echo $no; ?></td>
                                                        <td style="margin-top:10px;"><?php echo $pecah["nama_lengkap"]; ?></td>
                                                        <td style="margin-top:10px;"><?php echo date("d-m-Y", strtotime($pecah['tgl_lahir'])) ?></td>
                                                        <td style="margin-top:10px;"><?php echo $pecah["email"]; ?></td>
                                                        <td>
                                                            <a href="index.php?halaman=editdaftarpasienkosmetik&id=<?php echo $pecah["idpasien"]; ?>" class="btn btn-sm btn-primary" ><i class="bi bi-pencil-fill" ></i></a>
                                                            <a href="index.php?halaman=daftarpasienkosmetik&del=<?php echo $pecah["idpasien"]; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Data Ini ??')"><i class="bi bi-trash-fill" ></i></a>
                                                        </td>
                                                    </tr>

                                                    <?php $no += 1 ?>

                                                <?php endforeach ?>

                                            </tbody>
                                        </table>


                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </section>

        </div>
    </main><!-- End #main -->

    </div>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


</body>

</html>

<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            search: true,
            pagination: true
        });
    });
</script>