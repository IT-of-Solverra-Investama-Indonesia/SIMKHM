<?php error_reporting(0)?>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" />
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<!-- !-- DataTables  -->

<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.colVis.min.js"></script>

<script>
    $(document).ready( function () {
        $('#myTable').DataTable( {
            dom: 'Bfrtip',
            buttons: [ {
            extend: 'excelHtml5'     
            }
            ]
        } );
    } );
</script>
<div class="pagetitle mb-0">
    <h1>Rekap Obat</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?halaman=daftarpuyer" style="color:blue;">Rekap Obat</a></li>
        </ol>
    </nav>
</div>
<div class="card shadow p-2 mb-2">
    <form method="post">
        <div class="row">
            <div class="col-md-4">
                <label for="" class="mb-0">Cari Mulai Tanggal :</label>
                <input type="date" name="mulai" class="form-control mt-0 mb-1">
            </div>
            <div class="col-md-4">
                <label for="" class="mb-0">Hingga Tanggal :</label>
                <input type="date" name="hingga" class="form-control mt-0 mb-1">
            </div>
            <div class="col-md-4">
                <br>
                <button class="btn btn-primary" name="srcInap"><i class="bi bi-search"></i> Inap</button>
                <button class="btn btn-primary" name="srcPoli"><i class="bi bi-search"></i> Poli</button>
                <button class="btn btn-primary" name="src"><i class="bi bi-search"></i> Filter</button>
                <!-- <button style="float:left; margin-right: 10px;" class="btn btn-info" name="src2"><i class="bi bi-search"></i> Inap</button> -->
            </div>
         
        </div>
    </form>
</div>
<div class="card shadow p-2">
    <div class="table-responsive">
        <table class="table" id="myTable" style="font-size: 12px;">
            <thead>
                <tr>
                    <th>Nama Obat</th>
                    <th>Keluar</th>
                    <th>Harga Beli</th>
                    <th>Jumlah<br>(Terbaru)</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    if(isset($_POST['src'])){ 
                        $getObat = $koneksi->query("SELECT *, SUM(jml_obat) as jumlah_beli FROM apotek GROUP BY nama_obat, id_obat order by idapotek desc");
                    }
                    if(isset($_POST['srcInap'])){ 
                        $getObat = $koneksi->query("SELECT *, SUM(jml_obat) as jumlah_beli FROM apotek WHERE tipe = 'Ranap' GROUP BY nama_obat, id_obat order by idapotek desc");
                    }
                    if(isset($_POST['srcPoli'])){ 
                        $getObat = $koneksi->query("SELECT *, SUM(jml_obat) as jumlah_beli FROM apotek WHERE tipe = 'Rajal' GROUP BY nama_obat, id_obat order by idapotek desc");
                    }
                    // elseif(isset($_POST['src2'])){
                    //     $getObat = $koneksi->query("SELECT * FROM apotek WHERE tipe = 'Ranap' ORDER BY nama_obat ASC");
                    // }
                ?>
                
                <?php foreach($getObat as $obat){ ?>
                    <?php
                        if(isset($_POST['src'])){
                            $getKeluar = $koneksi->query("SELECT *, SUM(jml_dokter) as jumKeluar FROM obat_rm WHERE kode_obat = '$obat[id_obat]' AND tgl_pasien >= '$_POST[mulai]' AND tgl_pasien <= '$_POST[hingga]' GROUP BY kode_obat")->fetch_assoc();    
                        }
                        if(isset($_POST['srcInap'])){
                            $getKeluar = $koneksi->query("SELECT *, SUM(jml_dokter) as jumKeluar FROM obat_rm WHERE kode_obat = '$obat[id_obat]' AND tgl_pasien >= '$_POST[mulai]' AND tgl_pasien <= '$_POST[hingga]' AND idigd != '0' GROUP BY kode_obat")->fetch_assoc();    
                        }
                        if(isset($_POST['srcPoli'])){
                            $getKeluar = $koneksi->query("SELECT *, SUM(jml_dokter) as jumKeluar FROM obat_rm WHERE kode_obat = '$obat[id_obat]' AND tgl_pasien >= '$_POST[mulai]' AND tgl_pasien <= '$_POST[hingga]' AND idigd = '0' GROUP BY kode_obat")->fetch_assoc();    
                        }
                        // elseif(isset($_POST['src2'])){
                        //     // $getKeluar = $koneksi->query("SELECT *, SUM(jml_dokter) as jumKeluar FROM obat_rm WHERE kode_obat = '$obat[id_obat]' and obat_igd = '' GROUP BY kode_obat")->fetch_assoc();    
                        //     $getKeluar = $koneksi->query("SELECT *, SUM(jml_dokter) as jumKeluar FROM obat_rm WHERE kode_obat = '$obat[id_obat]' AND tgl_pasien >= '$_POST[mulai]' AND tgl_pasien <= '$_POST[hingga]' and obat_igd != '' GROUP BY kode_obat")->fetch_assoc();    
                        // }
                    ?>
                    <tr>
                        <td><?= $obat['nama_obat']?></td>
                        <td>
                            <?php
                                if($getKeluar['jumKeluar'] == '' or $getKeluar['jumKeluar'] == '0'){
                                    echo "0";
                                    $keluar = 0;
                                } else{
                                    echo $getKeluar['jumKeluar'];
                                    $keluar = $getKeluar['jumKeluar'];
                                }
                            ?>
                        </td>
                        <td>Rp<?= number_format($obat['harga_beli'],0,0,'.')?></td>
                        <td><?= $obat['jml_obat']?></td>
                        <td>
                            Rp<?= number_format($obat['harga_beli']*$keluar,0,0,'.')?>
                        </td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
</div>
