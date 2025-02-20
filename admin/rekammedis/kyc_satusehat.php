<h3><b>KYC (Know Your Customer) Satu Sehat</b></h3>
<?php
    $dokter = $koneksi->query("SELECT * FROM admin WHERE namalengkap = '".$_SESSION['dokter_rawat']."'")->fetch_assoc();
?>
<div class="card shadow p-2 mb-2">
    <h5><b>Input Kan Data Untuk Membuka KYC</b></h5>
    <form method="post">
        <div class="row">
            <div class="col-5">
                <label for="">Input Nama</label>
                <input type="text" value="<?= $dokter['namalengkap']?>" name="nama" id="" class="form-control mb-2">
            </div>
            <div class="col-5">
                <label for="">NIK</label>
                <input type="number" value="<?= $dokter['NIK']?>" name="nik" id="" class="form-control mb-2">
            </div>
            <div class="col-2">
                <br>
                <button name="open" class="btn btn-primary"><i class="bi bi-arrow-right"></i></button>
            </div>
        </div>
    </form>
</div>
<?php
    if(isset($_POST['open'])){
        echo "
          <script>
            document.location.href='../rekammedis?agent_name=".htmlspecialchars($_POST['nama'])."&agent_nik=".htmlspecialchars($_POST['nik'])."';
          </script>  
        ";
    }
?>