<?php
session_start();
include '../dist/function.php';

if (!isset($_GET["igd"])) {
  $pasien = $koneksi->query("SELECT * FROM pasien WHERE idpasien = '$_GET[id]'")->fetch_assoc();
} else {
  $pasien = $koneksi->query("SELECT * FROM pasien INNER JOIN igd WHERE pasien.no_rm = '$_GET[id]'")->fetch_assoc();
}
?>
<?php
if (isset($_GET['ttdPas'])) {
  $hub = $koneksi->query("SELECT * FROM general WHERE id_pasien = '$_GET[id]' LIMIT 1")->fetch_assoc();
  require '../phpqrcode/qrlib.php';
  $tempdir = "img-qrcode/";
  if (!file_exists($tempdir))   mkdir($tempdir, 0755);
  $file_name = $hub['nama_wali'] . ".png";  
  $file_path = $tempdir . $file_name;
  QRcode::png($hub['nama_wali'], $file_path, "H", 6, 4);
  /* param (1)qrcontent,(2)filename,(3)errorcorrectionlevel,(4)pixelwidth,(5)margin */
  // echo "<p class='result'>Hasil QRcode :</p>";
  // echo "<p><img src='".$file_path."' /></p>";
  if (!isset($_GET["igd"])) {
    echo "
          <script>
              alert('Berhasil TTD');
              document.location.href='gen_con.php?id=$_GET[id]';
          </script>
      ";
  } else {
    echo "
      <script>
          alert('Berhasil TTD');
          document.location.href='gen_con.php?id=$_GET[id]&igd';
      </script>
  ";
  }
}
if (isset($_GET['ttdPet'])) {
  require '../phpqrcode/qrlib.php';
  $tempdir = "img-qrcode/";
  if (!file_exists($tempdir))   mkdir($tempdir, 0755);
  $file_name = $_SESSION['admin']['namalengkap'] . ".png";
  $file_path = $tempdir . $file_name;
  QRcode::png($_SESSION['admin']['namalengkap'], $file_path, "H", 6, 4);
  /* param (1)qrcontent,(2)filename,(3)errorcorrectionlevel,(4)pixelwidth,(5)margin */
  // echo "<p class='result'>Hasil QRcode :</p>";
  // echo "<p><img src='".$file_path."' /></p>";
  if (!isset($_GET["igd"])) {
    echo "
          <script>
              alert('Berhasil TTD');
              document.location.href='gen_con.php?id=$_GET[id]';
          </script>
      ";
  } else {
    echo "
      <script>
          alert('Berhasil TTD');
          document.location.href='gen_con.php?id=$_GET[id]&igd';
      </script>
  ";
  }
}
?>

<html>

<head>
  <meta http-equiv=Content-Type content="text/html; charset=utf-8">
  <meta name=Generator content="Microsoft Word 15 (filtered)">
  <style>
    <!--
    /* Font Definitions */
    @font-face {
      font-family: "Cambria Math";
      panose-1: 2 4 5 3 5 4 6 3 2 4;
    }

    @font-face {
      font-family: Calibri;
      panose-1: 2 15 5 2 2 2 4 3 2 4;
    }

    @font-face {
      font-family: "Arial Black";
      panose-1: 2 11 10 4 2 1 2 2 2 4;
    }

    @font-face {
      font-family: "Arial Narrow";
      panose-1: 2 11 6 6 2 2 2 3 2 4;
    }

    /* Style Definitions */
    p.MsoNormal,
    li.MsoNormal,
    div.MsoNormal {
      margin-top: 0in;
      margin-right: 0in;
      margin-bottom: 10.0pt;
      margin-left: 0in;
      line-height: 115%;
      font-size: 11.0pt;
      font-family: "Calibri", sans-serif;
    }

    p.MsoNoSpacing,
    li.MsoNoSpacing,
    div.MsoNoSpacing {
      margin: 0in;
      font-size: 12.0pt;
      font-family: "Times New Roman", serif;
    }

    p.MsoListParagraph,
    li.MsoListParagraph,
    div.MsoListParagraph {
      margin-top: 0in;
      margin-right: 0in;
      margin-bottom: 0in;
      margin-left: .5in;
      font-size: 12.0pt;
      font-family: "Times New Roman", serif;
    }

    p.MsoListParagraphCxSpFirst,
    li.MsoListParagraphCxSpFirst,
    div.MsoListParagraphCxSpFirst {
      margin-top: 0in;
      margin-right: 0in;
      margin-bottom: 0in;
      margin-left: .5in;
      font-size: 12.0pt;
      font-family: "Times New Roman", serif;
    }

    p.MsoListParagraphCxSpMiddle,
    li.MsoListParagraphCxSpMiddle,
    div.MsoListParagraphCxSpMiddle {
      margin-top: 0in;
      margin-right: 0in;
      margin-bottom: 0in;
      margin-left: .5in;
      font-size: 12.0pt;
      font-family: "Times New Roman", serif;
    }

    p.MsoListParagraphCxSpLast,
    li.MsoListParagraphCxSpLast,
    div.MsoListParagraphCxSpLast {
      margin-top: 0in;
      margin-right: 0in;
      margin-bottom: 0in;
      margin-left: .5in;
      font-size: 12.0pt;
      font-family: "Times New Roman", serif;
    }

    p.Default,
    li.Default,
    div.Default {
      mso-style-name: Default;
      margin: 0in;
      text-autospace: none;
      font-size: 12.0pt;
      font-family: "Arial", sans-serif;
      color: black;
    }

    .MsoChpDefault {
      font-family: "Calibri", sans-serif;
    }

    .MsoPapDefault {
      margin-bottom: 10.0pt;
      line-height: 115%;
    }

    @page WordSection1 {
      size: 612.1pt 935.55pt;
      margin: .5in .5in .5in .5in;
    }

    div.WordSection1 {
      page: WordSection1;
    }

    /* List Definitions */
    ol {
      margin-bottom: 0in;
    }

    ul {
      margin-bottom: 0in;
    }
    -->
  </style>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>


</head>

<body lang=EN-US link=blue vlink=purple style='word-wrap:break-word'>

  <div class=WordSection1>
    <br>
    <table class=MsoTableGrid border=1 cellspacing=0 cellpadding=0 width=718 style='width:538.65pt;margin-left:5.4pt;border-collapse:collapse;border:none'>
      <tr>
        <td width=360 style='width:270.05pt;border:solid windowtext 1.0pt;padding:
  0in 5.4pt 0in 5.4pt'>
          <p class=MsoNormal align=center style='margin-top:0in;margin-right:0in;
  margin-bottom:0in;margin-left:115.25pt;text-align:center;line-height:normal'><span style='position:relative;z-index:1'><span style='left:0px;position:
  absolute;left:-170px;top:-5px;width:154px;height:86px'><img width=154 height=86 src="RM%2001%20REVISI%202%20GENERAL%20CONSENT_files/image001.png"></span></span><span style='font-size:12.0pt;font-family:"Arial Narrow",sans-serif'>Jalan Raya
              Wonorejo No. 167 Kedungjajang, Lumajang</span></p>
          <p class=MsoNormal align=center style='margin-top:0in;margin-right:0in;
  margin-bottom:0in;margin-left:115.1pt;text-align:center;line-height:normal'><span style='font-size:12.0pt;font-family:"Arial Narrow",sans-serif'>Telp.
              0822-3388-0001</span></p>
          <p class=MsoNormal align=center style='margin-top:0in;margin-right:0in;
  margin-bottom:0in;margin-left:115.25pt;text-align:center;line-height:normal'><span style='font-size:12.0pt;font-family:"Arial Narrow",sans-serif'>Email. </span><a href="mailto:husada.mulia@gmail.com"><i><span style='font-size:12.0pt;
  font-family:"Arial Narrow",sans-serif'>husada.mulia@gmail.com</span></i></a></p>
        </td>
        <td width=358 valign=top style='width:268.6pt;border:solid windowtext 1.0pt;
  border-left:none;padding:0in 5.4pt 0in 5.4pt'>
          <p class=MsoNormal style='margin-bottom:0in;line-height:normal'><span style='font-size:12.0pt;font-family:"Arial Narrow",sans-serif'>No</span><span style='font-size:13.0pt;font-family:"Arial Narrow",sans-serif'>. </span><span style='font-size:12.0pt;font-family:"Arial Narrow",sans-serif'>RM             :  <?= $pasien['no_rm'] ?></span></p>
          <p class=MsoNormal style='margin-bottom:0in;line-height:normal'><span style='font-size:12.0pt;font-family:"Arial Narrow",sans-serif'>Nama Pasien  
              :  <?= $pasien['nama_lengkap'] ?></span></p>
          <p class=MsoNormal style='margin-bottom:0in;line-height:normal'><span style='font-size:12.0pt;font-family:"Arial Narrow",sans-serif'>Tanggal
              Lahir   :  <?= $pasien['tgl_lahir'] ?> <b><?php if ($pasien['jenis_kelamin'] == '1') { ?>L/<s>P</s><?php } elseif ($pasien['jenis_kelamin'] == '2') { ?><s>L</s>/P<?php } ?></b></span></p>
          <p class=MsoNormal style='margin-bottom:0in;line-height:normal'><span style='font-size:12.0pt;font-family:"Arial Narrow",sans-serif'>Alamat             
              :  <?= $pasien['alamat'] ?></span></p>
          <form method="POST">
            <?php
            if (!isset($_GET['igd'])) {
              $getGCC = $koneksi->query("SELECT *, COUNT(*) as jumlah FROM general WHERE id_pasien = '$_GET[id]' LIMIT 1")->fetch_assoc();
            } else {
              $getGCC = $koneksi->query("SELECT *, COUNT(*) as jumlah FROM general WHERE no_rm = '$_GET[id]' LIMIT 1")->fetch_assoc();
            }
            ?>
            <p class=MsoNormal style='margin-bottom:0in;line-height:normal'><span style='font-size:12.0pt;font-family:"Arial Narrow",sans-serif'>Ruangan          
                : <?php if ($getGCC['jumlah'] == 0) { ?><input type="text" name="ruang"><?php } else { ?><?= $getGCC['ruang'] ?><?php } ?></span></p>
        </td>
      </tr>
    </table>

    <p class=MsoNormal align=center style='margin-top:3.0pt;margin-right:0in;
margin-bottom:3.0pt;margin-left:0in;text-align:center;line-height:normal'>
      <spanx` style='position:relative;z-index:1'><span style='left:0px;position:
absolute;left:430px;top:-130px;width:236px;height:28px'><img width=236 height=28 src="RM%2001%20REVISI%202%20GENERAL%20CONSENT_files/image002.png" alt="RM 01/RJ/RI/KHM/REV.2/2023"></span></spanx><span style='margin-left: -80;font-size:13.0pt;
font-family:"Arial Black",sans-serif'>PEMBERIAN INFORMASI &amp; PERSETUJUAN
          UMUM (<i>GENERAL CONSENT)</i></span>
    </p>

    <table class=MsoTableGrid border=1 cellspacing=0 cellpadding=0 style='margin-left:5.4pt;border-collapse:collapse;border:none'>
      <tr style='height:761.7pt'>
        <td width=718 valign=top style='width:538.65pt;border:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt;height:718.7pt'>
          <?php
          if (!isset($_GET['igd'])) {
            $getGCC = $koneksi->query("SELECT *, COUNT(*) as jumlah FROM general WHERE id_pasien = '$_GET[id]' LIMIT 1")->fetch_assoc();
          } else {
            $getGCC = $koneksi->query("SELECT *, COUNT(*) as jumlah FROM general WHERE no_rm = '$_GET[id]' LIMIT 1")->fetch_assoc();
          }
          if ($getGCC['jumlah'] == 0) {
          ?>
            <p class=Default style='text-align:justify;line-height:115%'><span lang=IN style='font-size:11.0pt;line-height:115%'>Saya yang bertanda tangan di bawah
                ini :</span></p>
            <p class=Default style='text-align:justify;line-height:115%'><span lang=IN style='font-size:11.0pt;line-height:115%'>Nama                          :<input type="text" name="nama_wali" value="<?= $pasien['nama_lengkap'] ?>"> 
            <select name="jk" id="">
                  <option value="1">L</option>
                  <option value="2">P</option>
                </select>              </span></p>
            <?php
            // Menggunakan fungsi date()
            $tanggal_lahir_pasien = $pasien['tgl_lahir'];

            $umur_pasien = date("Y") - substr($tanggal_lahir_pasien, 0, 4);

            // echo "Umur: $umur_pasien tahun";
            ?>
            <p class=Default style='text-align:justify;line-height:115%'><span lang=IN style='font-size:11.0pt;line-height:115%'>Tanggal Lahir/Umur    :
                <input type="date" name="tgl_lahir" value="<?= $pasien['tgl_lahir'] ?>"> / <input type="number" name="umur" value="<?= $umur_pasien ?>">Tahun</span></p>
            <p class=Default style='text-align:justify;line-height:115%'><span lang=IN style='font-size:11.0pt;line-height:115%'>Alamat             </span><span style='font-size:11.0pt;line-height:115%'>            </span><span lang=IN style='font-size:11.0pt;line-height:115%'>:
                <input type="text" name="alamat" value="<?= $pasien['alamat'] ?>" style="max-width: 200px;"></span></p>
            <p class=Default style='text-align:justify;line-height:115%'><span lang=IN style='font-size:11.0pt;line-height:115%'>Telepon/Hp                 :
                <input type="text" name="notelp" value="<?= $pasien['nohp'] ?>"></span></p>
            <p class=Default style='text-align:justify;line-height:115%'><span lang=IN style='font-size:11.0pt;line-height:115%'>No. KTP/SIM              :
                <input type="text" name="no_identitas" value="<?= $pasien['no_identitas'] ?>"></span></p>
            <p class=Default style='text-align:justify;line-height:115%'><span lang=IN style='font-size:7.0pt;line-height:115%'>&nbsp;</span></p>

            <p class=Default style='text-align:justify;line-height:115%'><span lang=IN style='font-size:11.0pt;line-height:115%'>Bertindak atas
                <select name="hubungan" id="">
                  <option value="Diri Sendiri">Diri Sendiri</option>
                  <option value="Suami">Suami</option>
                  <option value="Istri">Istri</option>
                  <option value="Anak">Anak</option>
                  <option value="Ayah">Ayah</option>
                  <option value="Ibu">Ibu</option>
                  <option value="Saudara Kandung">Saudara Kandung</option>
                  <option value="Wali">Wali</option>
                </select>
                dari pasien</span><span style='font-size:11.0pt;line-height:115%'>*)</span><span lang=IN style='font-size:11.0pt;line-height:115%'> :</span><span lang=IN style='font-size:11.0pt;line-height:115%'> </span></p>
          <?php } else { ?>
            <p class=Default style='text-align:justify;line-height:115%'><span lang=IN style='font-size:11.0pt;line-height:115%'>Saya yang bertanda tangan di bawah
                ini :</span></p>
            <p class=Default style='text-align:justify;line-height:115%'><span lang=IN style='font-size:11.0pt;line-height:115%'>Nama                          : <?= $getGCC['nama_wali'] ?> <b><?php if ($getGCC['jk'] == '1') { ?>L/<s>P</s><?php } elseif ($getGCC['jk'] == '2') { ?><s>L</s>/P<?php } ?></b>
                 </span></p>
            <?php
            // Menggunakan fungsi date()
            $tanggal_lahir_wali = $getGCC['tgl_lahir'];

            $umur_wali = date("Y") - substr($tanggal_lahir_wali, 0, 4);

            // echo "Umur: $umur_wali tahun";
            ?>
            <p class=Default style='text-align:justify;line-height:115%'><span lang=IN style='font-size:11.0pt;line-height:115%'>Tanggal Lahir/Umur    :
                <?= $getGCC['tgl_lahir'] ?>/ <?= $getGCC['umur'] ?>Tahun</span></p>
            <p class=Default style='text-align:justify;line-height:115%'><span lang=IN style='font-size:11.0pt;line-height:115%'>Alamat             </span><span style='font-size:11.0pt;line-height:115%'>            </span><span lang=IN style='font-size:11.0pt;line-height:115%'>:
                <?= $getGCC['alamat'] ?></span></p>
            <p class=Default style='text-align:justify;line-height:115%'><span lang=IN style='font-size:11.0pt;line-height:115%'>Telepon/Hp                 :
                <?= $getGCC['notelp'] ?></span></p>
            <p class=Default style='text-align:justify;line-height:115%'><span lang=IN style='font-size:11.0pt;line-height:115%'>No. KTP/SIM              :
                <?= $getGCC['no_identitas'] ?></span></p>
            <p class=Default style='text-align:justify;line-height:115%'><span lang=IN style='font-size:7.0pt;line-height:115%'>&nbsp;</span></p>

            <p class=Default style='text-align:justify;line-height:115%'><span lang=IN style='font-size:11.0pt;line-height:115%'>Bertindak atas
                <?= $getGCC['hubungan'] ?>
                dari pasien</span><span style='font-size:11.0pt;line-height:115%'>*)</span><span lang=IN style='font-size:11.0pt;line-height:115%'> :</span><span lang=IN style='font-size:11.0pt;line-height:115%'> </span></p>
          <?php } ?>

          <p class=Default style='text-align:justify;line-height:115%'><span lang=IN style='font-size:11.0pt;line-height:115%'>Nama                          : <?= $pasien['nama_lengkap'] ?>                  <b><?php if ($pasien['jenis_kelamin'] == '1') { ?>L/<s>P</s><?php } elseif ($pasien['jenis_kelamin'] == '2') { ?><s>L</s>/P<?php } ?></b>
            </span></p>
          <?php
          // Menggunakan fungsi date()
          $tanggal_lahir_pasien = $pasien['tgl_lahir'];

          $umur_pasien = date("Y") - substr($tanggal_lahir_pasien, 0, 4);

          // echo "Umur: $umur_pasien tahun";
          ?>
          <p class=Default style='text-align:justify;line-height:115%'><span lang=IN style='font-size:11.0pt;line-height:115%'>Tanggal Lahir/Umur    :
              <?= $pasien['tgl_lahir'] ?>/ <?= $umur_pasien ?> Tahun</span></p>
          <p class=Default style='text-align:justify;line-height:115%'><span lang=IN style='font-size:11.0pt;line-height:115%'>Alamat             </span><span style='font-size:11.0pt;line-height:115%'>            </span><span lang=IN style='font-size:11.0pt;line-height:115%'>: <?= $pasien['alamat'] ?></span></p>
          <p class=Default style='text-align:justify;line-height:115%'><span lang=IN style='font-size:11.0pt;line-height:115%'>Telepon/Hp                 :
              <?= $pasien['nohp'] ?></span></p>
          <p class=Default style='text-align:justify;line-height:115%'><span lang=IN style='font-size:11.0pt;line-height:115%'>No. KTP/SIM              :
              <?= $pasien['no_identitas'] ?><br>
              <?php if ($getGCC['jumlah'] != 0) { ?>
                <span class="btn btn-success" data-bs-toggle="modal" data-bs-target="#edit<?php $getGCC['nama_wali'] ?>">Edit</span>
              <?php } else { ?>
                <button name="save">Simpan</button>
              <?php } ?></span></p>
          </form>

          <?php
          if (isset($_POST['save'])) {
            if (!isset($_GET['igd'])) {
              $koneksi->query("INSERT INTO general (nama_wali, tgl_lahir, umur, alamat, notelp, no_identitas, jk, hubungan, id_pasien, ruang) VALUES ('$_POST[nama_wali]', '$_POST[tgl_lahir]', '$_POST[umur]', '$_POST[alamat]', '$_POST[notelp]', '$_POST[no_identitas]', '$_POST[jk]', '$_POST[hubungan]', '$_GET[id]', '$_POST[ruang]');");
            } else {
              $koneksi->query("INSERT INTO general (nama_wali, tgl_lahir, umur, alamat, notelp, no_identitas, jk, hubungan, no_rm, ruang) VALUES ('$_POST[nama_wali]', '$_POST[tgl_lahir]', '$_POST[umur]', '$_POST[alamat]', '$_POST[notelp]', '$_POST[no_identitas]', '$_POST[jk]', '$_POST[hubungan]', '$_GET[id]', '$_POST[ruang]');");
            }

            echo "
        <script>
          alert('Berhasil Mengisi General Consent');
          document.location.href='gen_con.php?id=$_GET[id]';
        </script>
      ";
          }
          ?>

          <p class=Default style='text-align:justify;line-height:115%'><span lang=IN style='font-size:9.0pt;line-height:115%'>&nbsp;</span></p>
          <p class=Default style='text-align:justify;line-height:115%'><span lang=IN style='font-size:11.0pt;line-height:115%'>Menyatakan bahwa telah menerima
              informasi dari pihak </span><span style='font-size:11.0pt;line-height:115%'>Klinik
              Husada Mulia</span><span lang=IN style='font-size:11.0pt;line-height:115%'>
              sebagai berikut :</span></p>
          <p class=Default style='margin-left:21.3pt;text-align:justify;text-indent:
  -21.3pt;line-height:115%'><span lang=IN style='font-size:11.0pt;line-height:
  115%'>1.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              </span></span><b><span style='font-size:11.0pt;line-height:115%'>Informasi
                Pelayanan</span></b><b><span lang=IN style='font-size:11.0pt;line-height:
  115%'>: </span></b><span style='font-size:11.0pt;line-height:115%'>S</span><span lang=IN style='font-size:11.0pt;line-height:115%'>aya mengakui bahwa pada
              proses pendaftaran untuk mendapatkan perawatan di Klinik Husada Mulia saya
              telah m</span><span style='font-size:11.0pt;line-height:115%'>e</span><span lang=IN style='font-size:11.0pt;line-height:115%'>ndapat informasi</span><span style='font-size:11.0pt;line-height:115%'> jenis pelayanan, </span><span lang=IN style='font-size:11.0pt;line-height:115%'>jam pelayanan,</span><span lang=IN style='font-size:11.0pt;line-height:115%'> </span><span lang=IN style='font-size:11.0pt;line-height:115%'>prosedur pendaftaran, pr</span><span style='font-size:11.0pt;line-height:115%'>o</span><span lang=IN style='font-size:11.0pt;line-height:115%'>sedur rujukan, alur pelayanan,
              tarif</span><span style='font-size:11.0pt;line-height:115%'>, dan </span><span lang=IN style='font-size:11.0pt;line-height:115%'>tentang hak dan kewajiban
              saya s</span><span style='font-size:11.0pt;line-height:115%'>e</span><span lang=IN style='font-size:11.0pt;line-height:115%'>bagai pasien</span><span style='font-size:11.0pt;line-height:115%'> meliputi :</span></p>
          <p class=Default style='margin-left:38.75pt;text-align:justify;text-indent:
  -.25in;line-height:115%'><span style='font-size:11.0pt;line-height:115%'>a.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp; </span></span><span style='font-size:11.0pt;line-height:115%'>Hak pasien</span></p>
          <p class=Default style='margin-left:60.0pt;text-align:justify;text-indent:
  -.25in;line-height:115%'><span style='font-size:11.0pt;line-height:115%'>1)<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp; </span></span><span style='font-size:11.0pt;line-height:115%'>Pasien berhak memperoleh informasi
              mengenai tata tertib dan peraturan Klinik Husada Mulia Wonorejo</span></p>
          <p class=Default style='margin-left:60.0pt;text-align:justify;text-indent:
  -.25in;line-height:115%'><span style='font-size:11.0pt;line-height:115%'>2)<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp; </span></span><span style='font-size:11.0pt;line-height:115%'>Pasien berhak mendapatkan pelayanan
              yang aman, bermutu, manusiawi, dan adil sesuai dengan standar profesi
              kedokteran.</span></p>
          <p class=Default style='margin-left:60.0pt;text-align:justify;text-indent:
  -.25in;line-height:115%'><span style='font-size:11.0pt;line-height:115%'>3)<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp; </span></span><span style='font-size:11.0pt;line-height:115%'>Pasien berhak mendapat informasi
              tentang data hasil pemeriksaan Kesehatan dirinya termasuk jenis dan tujuan
              tindakan atau pengobatan.</span></p>
          <p class=Default style='margin-left:60.0pt;text-align:justify;text-indent:
  -.25in;line-height:115%'><span style='font-size:11.0pt;line-height:115%'>4)<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp; </span></span><span style='font-size:11.0pt;line-height:115%'>Pasien berhak  mendapatkan
              informasi tentang biaya sesuai tarif yang berlaku. </span></p>
          <p class=Default style='margin-left:60.0pt;text-align:justify;text-indent:
  -.25in;line-height:115%'><span style='font-size:11.0pt;line-height:115%'>5)<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp; </span></span><span style='font-size:11.0pt;line-height:115%'>Pasien berhak memberikan
              persetujuan atau menolak atas tindakan yang dilakukan oleh tenaga kesehatan
              di Klinik Husada Mulia Wonorejo </span></p>
          <p class=Default style='margin-left:60.0pt;text-align:justify;text-indent:
  -.25in;line-height:115%'><span style='font-size:11.0pt;line-height:115%'>6)<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp; </span></span><span style='font-size:11.0pt;line-height:115%'>Pasien berhak mendapatkan kepastian
              isi rekam medis terjaga kerahasiaannya.</span></p>
          <p class=Default style='margin-left:38.75pt;text-align:justify;text-indent:
  -.25in;line-height:115%'><span style='font-size:11.0pt;line-height:115%'>b.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp; </span></span><span style='font-size:11.0pt;line-height:115%'>Kewajiban pasien</span></p>
          <p class=Default style='margin-left:60.0pt;text-align:justify;text-indent:
  -.25in;line-height:115%'><span style='font-size:11.0pt;line-height:115%'>1)<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp; </span></span><span style='font-size:11.0pt;line-height:115%'>Mematuhi peraturan yang berlaku di
              Klinik Husada Mulia Wonorejo antara lain :</span></p>
          <p class=Default style='margin-left:81.25pt;text-align:justify;text-indent:
  -.25in;line-height:115%'><span style='font-size:11.0pt;line-height:115%'>a)<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp; </span></span><span style='font-size:11.0pt;line-height:115%'>Antri sesuai urutan </span></p>
          <p class=Default style='margin-left:81.25pt;text-align:justify;text-indent:
  -.25in;line-height:115%'><span style='font-size:11.0pt;line-height:115%'>b)<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp; </span></span><span style='font-size:11.0pt;line-height:115%'>Menandatangani surat surat
              persetujuan tindakan medis</span></p>
          <p class=Default style='margin-left:81.25pt;text-align:justify;text-indent:
  -.25in;line-height:115%'><span style='font-size:11.0pt;line-height:115%'>c)<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp; </span></span><span style='font-size:11.0pt;line-height:115%'>Tidak merokok di lingkungan klinik.</span></p>
          <p class=Default style='margin-left:60.0pt;text-align:justify;text-indent:
  -.25in;line-height:115%'><span style='font-size:11.0pt;line-height:115%'>2)<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp; </span></span><span style='font-size:11.0pt;line-height:115%'>Memberikan informasi yang lengkap
              dan jujur tentang masalah kesehatan</span></p>
          <p class=Default style='margin-left:60.0pt;text-align:justify;text-indent:
  -.25in;line-height:115%'><span style='font-size:11.0pt;line-height:115%'>3)<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp; </span></span><span style='font-size:11.0pt;line-height:115%'>Mematuhi rencana terapi yang
              direkomendasikan oleh tenaga Kesehatan di Klinik Husada Mulia Wonorejo</span></p>
          <p class=Default style='margin-left:60.0pt;text-align:justify;text-indent:
  -.25in;line-height:115%'><span style='font-size:11.0pt;line-height:115%'>4)<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp; </span></span><span style='font-size:11.0pt;line-height:115%'>Menerima segala konsekuensi atas
              keputusan pribadinya untuk menolak rencana terapi yang direkomendasikan oleh
              tenaga medis untuk penyembuhan penyakit dan masalah kesehatan.</span></p>
          <p class=Default style='margin-left:60.0pt;text-align:justify;text-indent:
  -.25in;line-height:115%'><span style='font-size:11.0pt;line-height:115%'>5)<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp; </span></span><span style='font-size:11.0pt;line-height:115%'>Memberikan imbalan jasa atas
              pelayanan yang diterima sesuai dengan peraturan yang berlaku.</span></p>
          <p class=Default style='margin-left:60.0pt;text-align:justify;line-height:
  115%'><span style='font-size:11.0pt;line-height:115%'>&nbsp;</span></p>
          <p class=Default style='margin-left:21.3pt;text-align:justify;text-indent:
  -21.3pt;line-height:115%'><span lang=IN style='font-size:11.0pt;line-height:
  115%'>2.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              </span></span><b><span lang=IN style='font-size:11.0pt;line-height:115%'>Persetujuan
                pelayanan kesehata</span></b><span lang=IN style='font-size:11.0pt;
  line-height:115%'>n<b>:</b> Saya menyetujui dan memberikan persetujuan untuk
              dirawat di </span><span style='font-size:11.0pt;line-height:115%'>Klinik
              Husada Mulia</span><span lang=IN style='font-size:11.0pt;line-height:115%'> d</span><span lang=IN style='font-size:11.0pt;line-height:115%'>an dengan ini saya
              memberikan kuasa kepada Dokter dan Perawat di </span><span style='font-size:
  11.0pt;line-height:115%'>Klinik Husada Mulia</span><span style='font-size:
  11.0pt;line-height:115%'> </span><span lang=IN style='font-size:11.0pt;
  line-height:115%'>untuk memberikan asuhan keperawatan, pemeriksaan fisik,
              melakukan prosedur diagnostik dan/atau tatalaksana sesuai pertimbangan dokter
              yang disarankan pada perawatan saya</span><span style='font-size:11.0pt;
  line-height:115%'>.</span></p>
          <p class=Default style='margin-left:21.3pt;text-align:justify;line-height:
  115%'><span lang=IN style='font-size:11.0pt;line-height:115%'>&nbsp;</span></p>
          <p class=Default style='margin-left:21.3pt;text-align:justify;text-indent:
  -21.3pt;line-height:115%'><span lang=IN style='font-size:11.0pt;line-height:
  115%'>3.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              </span></span><b><span lang=IN style='font-size:11.0pt;line-height:115%'>Rahasia
                Kedokteran: </span></b><span lang=IN style='font-size:11.0pt;line-height:
  115%'>Saya memberikan kuasa kepada </span><span style='font-size:11.0pt;
  line-height:115%'>Klinik Husada Mulia</span><span style='font-size:11.0pt;
  line-height:115%'> </span><span lang=IN style='font-size:11.0pt;line-height:
  115%'>untuk menjaga privasi dan kerahasian penyakit saya selama dalam
              perawatan</span><span style='font-size:11.0pt;line-height:115%'>. S</span><span lang=IN style='font-size:11.0pt;line-height:115%'>aya menyetujui bahwa </span><span style='font-size:11.0pt;line-height:115%'>klinik</span><span lang=IN style='font-size:11.0pt;line-height:115%'> menjamin kerahasiaan informasi
              medis saya, baik untuk kepentingan perawatan dan pengobatan, pendidikan
              maupun penelitian</span><span style='font-size:11.0pt;line-height:115%'>,</span><span lang=IN style='font-size:11.0pt;line-height:115%'> kecuali saya mengungkapkan
              sendiri atau orang lain yang saya beri kuasa untuk itu.</span></p>
          <p class=MsoListParagraph><span style='font-size:11.0pt'>&nbsp;</span></p>
          <p class=Default style='text-align:justify;line-height:115%'><i><span style='font-size:9.0pt;line-height:115%'>*) lingkari pilihan yang dipilih</span></i></p>
          <p class=Default style='margin-left:21.3pt;text-align:justify;line-height:
  115%'><span lang=IN style='font-size:11.0pt;line-height:115%'>&nbsp;</span></p>
          <p class=Default style='margin-left:21.3pt;text-align:justify;line-height:
  115%'><span lang=IN style='font-size:11.0pt;line-height:115%'>&nbsp;</span></p>
          <p class=Default style='margin-left:21.3pt;text-align:justify;text-indent:
  -21.3pt;line-height:115%'><span lang=IN style='font-size:11.0pt;line-height:
  115%'>4.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              </span></span><b><span lang=IN style='font-size:11.0pt;line-height:115%'>Membuka
                rahasia kedokteran: </span></b><span lang=IN style='font-size:11.0pt;
  line-height:115%'>Saya </span><span style='font-size:11.0pt;line-height:115%'>setuju
              membuka rahasia kedokteran terkait dengan kondisi ksehatan asuhan dan
              pengobatan yang saya terima kepada :</span></p>
          <p class=Default style='margin-left:37.15pt;text-align:justify;text-indent:
  -.25in;line-height:115%'><span style='font-size:11.0pt;line-height:115%'>a.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp; </span></span><span style='font-size:11.0pt;line-height:115%'>Dokter dan tenaga kesehatan lain
              yang memberikan asuhan kepada saya.</span></p>
          <p class=Default style='margin-left:37.15pt;text-align:justify;text-indent:
  -.25in;line-height:115%'><span style='font-size:11.0pt;line-height:115%'>b.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp; </span></span><span style='font-size:11.0pt;line-height:115%'>Perusahaan asuransi kesehatan atau
              BPJS atau pihak lain yang menjamin pembiayaan saya dan lembaga pemerintah.</span></p>
          <p class=Default style='margin-left:37.15pt;text-align:justify;line-height:
  115%'><span style='font-size:11.0pt;line-height:115%'>&nbsp;</span></p>
          <p class=Default style='margin-left:21.3pt;text-align:justify;text-indent:
  -21.3pt;line-height:115%'><span lang=IN style='font-size:11.0pt;line-height:
  115%'>5.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              </span></span><span lang=IN style='font-size:11.0pt;line-height:115%'>Bila
              status </span><span style='font-size:11.0pt;line-height:115%'>p</span><span lang=IN style='font-size:11.0pt;line-height:115%'>asien </span><span style='font-size:11.0pt;line-height:115%'>u</span><span lang=IN style='font-size:11.0pt;line-height:115%'>mum</span><span style='font-size:
  11.0pt;line-height:115%'>,</span><span lang=ES-US style='font-size:11.0pt;
  line-height:115%'> saya </span><span lang=IN style='font-size:11.0pt;
  line-height:115%'>telah MENGERTI dan MENYETUJUI bahwa saya wajib untuk
              membayar total biaya perawatan sesuai tarif dan ketentuan </span><span style='font-size:11.0pt;line-height:115%'>Klinik Husada Mulia.</span></p>
          <p class=Default style='margin-left:21.3pt;text-align:justify;line-height:
  115%'><span lang=IN style='font-size:11.0pt;line-height:115%'>&nbsp;</span></p>
          <p class=Default style='margin-left:21.3pt;text-align:justify;text-indent:
  -21.3pt;line-height:115%'><span lang=IN style='font-size:11.0pt;line-height:
  115%'>6.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              </span></span><span lang=IN style='font-size:11.0pt;line-height:115%'>Bila
              pasien jaminan</span><span lang=IN style='font-size:11.0pt;line-height:115%'>
            </span><span lang=IN style='font-size:11.0pt;line-height:115%'>BPJS</span><span style='font-size:11.0pt;line-height:115%'>,</span><span style='font-size:
  11.0pt;line-height:115%'> </span><span style='font-size:11.0pt;line-height:
  115%'>s</span><span lang=IN style='font-size:11.0pt;line-height:115%'>aya
              menyatakan setuju, sebagai pasien/penanggung jawab pasien dengan status
              peserta BPJS untuk membayar</span><span lang=IN style='font-size:11.0pt;
  line-height:115%'> </span><span lang=IN style='font-size:11.0pt;line-height:
  115%'>biaya perawatan di luar klaim/jaminan</span><span style='font-size:
  11.0pt;line-height:115%'>.</span></p>
          <p class=MsoListParagraph><span style='font-size:11.0pt'>&nbsp;</span></p>
          <p class=Default style='margin-left:21.3pt;text-align:justify;text-indent:
  -21.3pt;line-height:115%'><span lang=IN style='font-size:11.0pt;line-height:
  115%'>7.<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              </span></span><span lang=IN style='font-size:11.0pt;line-height:115%;
  color:windowtext'>Melalui ini saya mendelegasikan kepada pihak </span><span style='font-size:11.0pt;line-height:115%;color:windowtext'>Klinik Husada
              Mulia </span><span lang=IN style='font-size:11.0pt;line-height:115%;
  color:windowtext'>dalam tindakan </span><span style='font-size:11.0pt;
  line-height:115%;color:windowtext'>bersifat umum </span><span lang=IN style='font-size:11.0pt;line-height:115%;color:windowtext'>berupa: pemasangan
              infus, pemberian obat-obatan (oral maupun injeksi), pemasangan cateter,
              pemasangan NGT dan lain-lain.</span></p>
          <p class=MsoListParagraph><span style='font-size:11.0pt'>&nbsp;</span></p>
          <p class=Default style='text-align:justify;line-height:115%'><span lang=ES-US style='font-size:11.0pt;line-height:115%;color:windowtext'>Dengan</span><span lang=ES-US style='font-size:11.0pt;line-height:115%;color:windowtext'> </span><span lang=ES-US style='font-size:11.0pt;line-height:115%;color:windowtext'>ini
              saya menyatakan</span><span lang=ES-US style='font-size:11.0pt;line-height:
  115%;color:windowtext'> </span><span lang=ES-US style='font-size:11.0pt;
  line-height:115%;color:windowtext'>bahwa saya telah</span><span lang=ES-US style='font-size:11.0pt;line-height:115%;color:windowtext'> </span><span lang=ES-US style='font-size:11.0pt;line-height:115%;color:windowtext'>menerima</span><span lang=ES-US style='font-size:11.0pt;line-height:115%;color:windowtext'> </span><span lang=ES-US style='font-size:11.0pt;line-height:115%;color:windowtext'>informasi</span><span lang=ES-US style='font-size:11.0pt;line-height:115%;color:windowtext'> </span><span lang=ES-US style='font-size:11.0pt;line-height:115%;color:windowtext'>sebagaimana</span><span lang=ES-US style='font-size:11.0pt;line-height:115%;color:windowtext'> </span><span lang=ES-US style='font-size:11.0pt;line-height:115%;color:windowtext'>di atas
              dan telah</span><span lang=ES-US style='font-size:11.0pt;line-height:115%;
  color:windowtext'> </span><span lang=ES-US style='font-size:11.0pt;
  line-height:115%;color:windowtext'>memahaminya.</span></p>
          <p class=MsoNormal style='margin-bottom:0in;text-align:justify;line-height:
  115%'><span lang=IN style='font-family:"Arial",sans-serif'>&nbsp;</span></p>
          <p class=MsoNormal align=right style='margin-bottom:0in;text-align:right;
  line-height:115%'><span style='font-family:"Arial",sans-serif'>&nbsp;</span></p>
          <p class=MsoNormal align=right style='margin-bottom:0in;text-align:right;
  line-height:115%'><span style='font-family:"Arial",sans-serif'>&nbsp;</span></p>
          <p class=MsoNormal align=right style='margin-bottom:0in; margin-right: 100px;text-align:right;
  line-height:115%'><span style='font-family:"Arial",sans-serif'>Lumajang</span><span lang=IN style='font-family:"Arial",sans-serif'>, <?= date('d F Y') ?></span></p>
          <div align=center>
            <table class=MsoTableGrid border=0 cellspacing=0 cellpadding=0 style='border-collapse:collapse;border:none'>
              <tr>
                <td width=308 valign=top style='width:231.05pt;padding:0in 5.4pt 0in 5.4pt'>
                  <p class=MsoNoSpacing align=center style='text-align:center;line-height:
    115%'><span lang=IN style='font-size:11.0pt;line-height:115%;font-family:
    "Arial",sans-serif'>Petugas Yang Memberikan Informasi,</span></p>


                  <?php if (!isset($_GET["igd"])) { ?>

                    <p class=MsoNoSpacing style='line-height:115%'><span style='font-size:11.0pt;
    line-height:115%;font-family:"Arial",sans-serif'>
                        <center><img style="max-width: 100px; margin: 0px 0px 0px 0px;" src="img-qrcode/<?= $_SESSION['admin']['namalengkap'] ?>.png" alt=""><br><a class="btn btn-success" href="gen_con.php?id=<?= $_GET['id'] ?>&ttdPet">TTD</a></center>
                      </span></p>
                  <?php } else { ?>
                    <p class=MsoNoSpacing style='line-height:115%'><span style='font-size:11.0pt;
    line-height:115%;font-family:"Arial",sans-serif'>
                        <center><img style="max-width: 100px; margin: 0px 0px 0px 0px;" src="img-qrcode/<?= $_SESSION['admin']['namalengkap'] ?>.png" alt=""><br><a class="btn btn-success" href="gen_con.php?id=<?= $_GET['id'] ?>&ttdPet&igd">TTD</a></center>
                      </span></p>
                  <?php } ?>

                  <p class=MsoNoSpacing align=center style='text-align:center;line-height:
    115%'><span lang=IN style='font-size:11.0pt;line-height:115%;font-family:
    "Arial",sans-serif'>(<?= $_SESSION['admin']['namalengkap'] ?>)</span></p>
                </td>
                <td width=335 valign=top style='width:250.9pt;padding:0in 5.4pt 0in 5.4pt'>
                  <p class=MsoNoSpacing align=center style='text-align:center;line-height:
    115%'><span lang=IN style='font-size:11.0pt;line-height:115%;font-family:
    "Arial",sans-serif'>Yang Memberi Persetujuan</span></p>
                  <p class=MsoNoSpacing align=center style='text-align:center;line-height:
    115%'><span lang=IN style='font-size:11.0pt;line-height:115%;font-family:
    "Arial",sans-serif'>Pasien/Penanggung Jawab Pasien *)</span></p>

                  <?php if (!isset($_GET["igd"])) { ?>
                    <p class=MsoNoSpacing align=center style='text-align:center;line-height:
    115%'><span lang=IN style='font-size:14.0pt;line-height:100%;font-family:
    "Arial",sans-serif'>
                        <center><?php if ($getGCC['jumlah'] != 0) { ?><img style="max-width: 100px; margin: 0px 0px 0px 0px;" src="img-qrcode/<?= $getGCC['nama_wali'] ?>.png" alt=""><br><a class="btn btn-success" href="gen_con.php?id=<?= $_GET['id'] ?>&ttdPas">TTD</a><?php } ?></center>
                      </span></p>
                  <?php } else { ?>
                    <p class=MsoNoSpacing align=center style='text-align:center;line-height:
    115%'><span lang=IN style='font-size:14.0pt;line-height:100%;font-family:
    "Arial",sans-serif'>
                        <center><?php if ($getGCC['jumlah'] != 0) { ?><img style="max-width: 100px; margin: 0px 0px 0px 0px;" src="img-qrcode/<?= $getGCC['nama_wali'] ?>.png" alt=""><br><a class="btn btn-success" href="gen_con.php?id=<?= $_GET['id'] ?>&ttdPas&igd">TTD</a><?php } ?></center>
                      </span></p>
                  <?php } ?>

                  <p class=MsoNoSpacing align=center style='text-align:center;line-height:
    115%'><span lang=IN style='font-size:11.0pt;line-height:115%;font-family:
    "Arial",sans-serif'><?= $getGCC['nama_wali'] ?></span></p>
                  <p class=MsoNoSpacing align=center style='text-align:center;line-height:
    115%'><span lang=IN style='font-size:11.0pt;line-height:115%;font-family:
    "Arial",sans-serif'>(Hubungan dengan pasien : <?= $getGCC['hubungan'] ?>)</span></p>
                </td>
              </tr>
              <tr>
                <td width=308 valign=top style='width:231.05pt;padding:0in 5.4pt 0in 5.4pt'>
                  <p class=MsoNoSpacing align=center style='text-align:center;line-height:
    115%'><span lang=IN style='font-size:11.0pt;line-height:115%;font-family:
    "Arial",sans-serif'>&nbsp;</span></p>
                </td>
                <td width=335 valign=top style='width:250.9pt;padding:0in 5.4pt 0in 5.4pt'>
                  <p class=MsoNoSpacing align=center style='text-align:center;line-height:
    115%'><span lang=IN style='font-size:11.0pt;line-height:115%;font-family:
    "Arial",sans-serif'>&nbsp;</span></p>
                </td>
              </tr>
            </table>
          </div>
          <p class=Default style='text-align:justify;line-height:115%'></p>
        </td>
      </tr>
    </table>

    <p class=MsoNormal style='margin-bottom:0in;line-height:normal'><span style='font-size:1.0pt;font-family:"Arial Black",sans-serif'>&nbsp;</span></p>

  </div>

  <!-- Modal Edit -->
  <div class="modal fade" id="edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="post">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
          <div class="mb-3">
              <label for="name" class="form-label">Ruangan</label>
              <input type="text" class="form-control" name="ruangan"  value="<?= $getGCC['ruang']?>">
            </div>
            <legend>Saya yang bertanda tangan di bawah ini : </legend>
            <div class="mb-3">
              <label for="name" class="form-label">Nama</label>
              <input type="text" class="form-control" name="nama" value="<?= $getGCC['nama_wali']?>">
            </div>
            <div class="mb-3">
              <label for="gender" class="form-label">Jenis Kelamin</label>
              <select class="form-select" name="jk">
                <option value="L" <?= $getGCC['jk'] == 'L' ? 'selected' : '' ?> >Laki-Laki</option>
                <option value="P" <?= $getGCC['jk'] == 'P' ? 'selected' : '' ?>>Perempuan</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="birthdate" class="form-label">Tanggal Lahir</label>
              <input type="date" class="form-control" name="birthdate" value="<?= $getGCC['tgl_lahir']?>">
            </div>
            <div class="mb-3">
              <label for="age" class="form-label">Umur</label>
              <div class="input-group">
                <input type="number" class="form-control" name="age" value="<?= $getGCC['umur'] ?>">
                <span class="input-group-text" id="basic-addon2">Tahun</span>
              </div>
            </div>
            <div class="mb-3">
              <label for="address" class="form-label">Alamat</label>
              <input type="text" class="form-control" name="address" value="<?= $getGCC['alamat']?>">
            </div>
            <div class="mb-3">
              <label for="phonenumber" class="form-label">Telepon/HP</label>
              <input type="text" class="form-control" name="phonenumber" value="<?= $getGCC['notelp']?>">
            </div>
            <div class="mb-3">
              <label for="idktpsim" class="form-label">No. KTP/SIM</label>
              <input type="text" class="form-control" name="idktpsim" value="<?= $getGCC['no_identitas']?>">
            </div>
            <div class="row g-3">
              <div class="col-auto">
                Bertindak atas 
              </div>
              <div class="col-auto">
                <select class="form-select" name="hubungan">
                  <option value="Diri Sendiri" <?= $getGCC['hubungan'] == 'Diri Sendiri' ? 'selected' : '' ?> >Diri Sendiri</option>
                  <option value="Suami" <?= $getGCC['hubungan'] == 'Suami' ? 'selected' : '' ?> >Suami</option>
                  <option value="Istri" <?= $getGCC['hubungan'] == 'Istri' ? 'selected' : '' ?> >Istri</option>
                  <option value="Anak" <?= $getGCC['hubungan'] == 'Anak' ? 'selected' : '' ?> >Anak</option>
                  <option value="Ayah" <?= $getGCC['hubungan'] == 'Ayah' ? 'selected' : '' ?> >Ayah</option>
                  <option value="Ibu" <?= $getGCC['hubungan'] == 'Ibu' ? 'selected' : '' ?> >Ibu</option>
                  <option value="Saudara Kandung" <?= $getGCC['hubungan'] == 'Saudara Kandung' ? 'selected' : '' ?> >Saudara Kandung</option>
                  <option value="Wali" <?= $getGCC['hubungan'] == 'Wali' ? 'selected' : '' ?> >Wali</option>
                </select>
              </div>
              <div class="col-auto">
                dari pasien*) :
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary" name="update">Simpan</button>
          </div>
        </form>
        <?php
        if (isset($_POST['update'])){
          $koneksi->query("UPDATE general SET  nama_wali = '$_POST[nama]', tgl_lahir ='$_POST[birthdate]', umur ='$_POST[age]',
          alamat ='$_POST[address]', notelp ='$_POST[phonenumber]', no_identitas ='$_POST[idktpsim]', jk ='$_POST[jk]',
          hubungan ='$_POST[hubungan]', ruang ='$_POST[ruangan]' where id_pasien = '$_GET[id]'");

          echo "
          <script>
          alert('Berhasil Mengupdate ');
          document.location.href='gen_con.php?id=$_GET[id]';
          </script>
          ";
        }
        ?>
      </div>
    </div>
  </div>
 
</body>

</html>

<a href="../dist/index.php?halaman=daftarpasien" class="btn btn-sm btn-dark mb-2 mt-4 ms-2">Kembali</a>