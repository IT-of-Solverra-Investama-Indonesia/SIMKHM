<?php
session_start();
    include '../dist/function.php';
    $pasien = $koneksi->query("SELECT * FROM pasien WHERE idpasien = '$_GET[id]'")->fetch_assoc();
    $petugas = $_SESSION['admin']['namalengkap'];

    if(isset($_GET['jadwal'])){
      $getIdRawat = $koneksi->query("SELECT *, COUNT(*) as jumlah FROM registrasi_rawat WHERE nama_pasien= '$pasien[nama_lengkap]' and jadwal='$_GET[jadwal]'")->fetch_assoc();
      
      if($getIdRawat['jumlah'] == 0){

      }else{
        echo "
          <script>
            document.location.href='fal-risk.php?id=$_GET[id]&kunjungan='$getIdRawat[idrawat]';
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
.kbw-signature { width: 400px; height: 200px;}
#signature canvas{
width: 100% !important;
height: auto;
}
.container{
margin-left: 420px;
 
}
</style>

<style>
<!--
 /* Font Definitions */
 @font-face
	{font-family:"Cambria Math";
	panose-1:2 4 5 3 5 4 6 3 2 4;}
@font-face
	{font-family:Calibri;
	panose-1:2 15 5 2 2 2 4 3 2 4;}
@font-face
	{font-family:"Arial Black";
	panose-1:2 11 10 4 2 1 2 2 2 4;}
@font-face
	{font-family:"Arial Narrow";
	panose-1:2 11 6 6 2 2 2 3 2 4;}
 /* Style Definitions */
 p.MsoNormal, li.MsoNormal, div.MsoNormal
	{margin-top:0in;
	margin-right:0in;
	margin-bottom:8.0pt;
	margin-left:0in;
	line-height:107%;
	font-size:11.0pt;
	font-family:"Calibri",sans-serif;}
a:link, span.MsoHyperlink
	{color:#0563C1;
	text-decoration:underline;}
p.MsoListParagraph, li.MsoListParagraph, div.MsoListParagraph
	{mso-style-link:"List Paragraph Char";
	margin-top:0in;
	margin-right:0in;
	margin-bottom:10.0pt;
	margin-left:.5in;
	line-height:115%;
	font-size:11.0pt;
	font-family:"Calibri",sans-serif;}
p.MsoListParagraphCxSpFirst, li.MsoListParagraphCxSpFirst, div.MsoListParagraphCxSpFirst
	{mso-style-link:"List Paragraph Char";
	margin-top:0in;
	margin-right:0in;
	margin-bottom:0in;
	margin-left:.5in;
	line-height:115%;
	font-size:11.0pt;
	font-family:"Calibri",sans-serif;}
p.MsoListParagraphCxSpMiddle, li.MsoListParagraphCxSpMiddle, div.MsoListParagraphCxSpMiddle
	{mso-style-link:"List Paragraph Char";
	margin-top:0in;
	margin-right:0in;
	margin-bottom:0in;
	margin-left:.5in;
	line-height:115%;
	font-size:11.0pt;
	font-family:"Calibri",sans-serif;}
p.MsoListParagraphCxSpLast, li.MsoListParagraphCxSpLast, div.MsoListParagraphCxSpLast
	{mso-style-link:"List Paragraph Char";
	margin-top:0in;
	margin-right:0in;
	margin-bottom:10.0pt;
	margin-left:.5in;
	line-height:115%;
	font-size:11.0pt;
	font-family:"Calibri",sans-serif;}
span.ListParagraphChar
	{mso-style-name:"List Paragraph Char";
	mso-style-link:"List Paragraph";
	font-family:"Calibri",sans-serif;}
.MsoChpDefault
	{font-family:"Calibri",sans-serif;}
.MsoPapDefault
	{margin-bottom:8.0pt;
	line-height:107%;}
@page WordSection1
	{size:612.1pt 935.55pt;
	margin:.5in .5in .5in .5in;}
div.WordSection1
	{page:WordSection1;}
 /* List Definitions */
 ol
	{margin-bottom:0in;}
ul
	{margin-bottom:0in;}
-->
</style>

</head>

<body lang=EN-US link="#0563C1" vlink="#954F72" style='word-wrap:break-word'>

<div class=WordSection1>
<br><br>
<form method="POST">

<table class=MsoTableGrid border=1 cellspacing=0 cellpadding=0
 style='margin-left:5.4pt;border-collapse:collapse;border:none'>
 <tr style='height:63.3pt'>
  <td width=370 style='width:277.85pt;border:solid windowtext 1.0pt;padding:
  0in 5.4pt 0in 5.4pt;height:63.3pt'>
  <p class=MsoNormal align=center style='margin-top:0in;margin-right:0in;
  margin-bottom:0in;margin-left:102.5pt;text-align:center;line-height:normal'><span
  style='position:relative;z-index:251659264'><span style='left:0px;margin-left: -160px;position:
  absolute;left:0px;top:-6px;width:154px;height:86px'><img width=154 height=86
  src="RM%2004.4%20PENGKAJIAN%20RESIKO%20JATUH%20RAWAT%20JALAN_files/image001.png"></span></span><span
  style='font-size:12.0pt;font-family:"Arial Narrow",sans-serif'>Jalan Raya
  Wonorejo No. 167 Kedungjajang, Lumajang</span></p>
  <p class=MsoNormal align=center style='margin-top:0in;margin-right:0in;
  margin-bottom:0in;margin-left:102.5pt;text-align:center;line-height:normal'><span
  style='font-size:12.0pt;font-family:"Arial Narrow",sans-serif'>Telp.
  0822-3388-0001</span></p>
  <p class=MsoNormal align=center style='margin-top:0in;margin-right:0in;
  margin-bottom:0in;margin-left:102.5pt;text-align:center;line-height:normal'><span
  style='font-size:12.0pt;font-family:"Arial Narrow",sans-serif'>Email. </span><a
  href="mailto:husada.mulia@gmail.com"><i><span style='font-size:12.0pt;
  font-family:"Arial Narrow",sans-serif'>husada.mulia@gmail.com</span></i></a></p>
  </td>
  <td width=342 valign=top style='width:256.35pt;border:solid windowtext 1.0pt;
  border-left:none;padding:0in 5.4pt 0in 5.4pt;height:63.3pt'>
  <p class=MsoNormal style='margin-bottom:0in;line-height:normal'><span
  style='font-size:14.0pt;font-family:"Arial Narrow",sans-serif'>No.
  RM             : <?= $pasien['no_rm']?></span></p>
  <p class=MsoNormal style='margin-bottom:0in;line-height:normal'><span
  style='font-size:14.0pt;font-family:"Arial Narrow",sans-serif'>Nama Pasien    :  <?= $pasien['nama_lengkap']?></span></p>
  <p class=MsoNormal style='margin-bottom:0in;line-height:normal'><span
  style='font-size:14.0pt;font-family:"Arial Narrow",sans-serif'>Tanggal
  Lahir   :  <?= $pasien['tgl_lahir']?> <b><?php if($pasien['jenis_kelamin'] == '1'){?>L/<s>P</s><?php }elseif($pasien['jenis_kelamin'] =='2'){?><s>L</s>/P<?php }?></b></span></p>
  <p class=MsoNormal style='margin-bottom:0in;line-height:normal'><span
  style='font-size:14.0pt;font-family:"Arial Narrow",sans-serif'>Alamat             
  : <?= $pasien['alamat']?></span></p>
  <?php
  $falll = $koneksi->query("SELECT *, COUNT(*) as jumlah FROM fallrisk WHERE id_pasien = '$_GET[kunjungan]' LIMIT 1")->fetch_assoc();
  ?>
  <p class=MsoNormal style='margin-bottom:0in;line-height:normal'><span
  style='font-size:14.0pt;font-family:"Arial Narrow",sans-serif'>Ruangan         
   : <?php if($falll['jumlah'] != 0){?><?= $falll['kamar']?><?php }else{?><input type="text" name="kamar"><?php }?></span></p>
  </td>
 </tr>
</table>

<p class=MsoNormal style='margin-bottom:0in'><span style='position:relative;
z-index:251660288'><span style='position:absolute;left:568px;top:-140px;
width:168px;height:28px'><img width=168 height=28
src="RM%2004.4%20PENGKAJIAN%20RESIKO%20JATUH%20RAWAT%20JALAN_files/image002.png"
alt="RM 04.4/RJ/KHM"></span></span></p>

<p class=MsoNormal align=center style='margin-top:1.0pt;margin-right:0in;
margin-bottom:0in;margin-left:0in;text-align:center;line-height:115%'><span
style='font-size:14.0pt;line-height:115%;font-family:"Arial Black",sans-serif'>PENGKAJIAN
RESIKO JATUH RAWAT JALAN</span></p>

<p class=MsoNormal style='margin-bottom:0in;text-align:justify;line-height:
normal'><span style='font-size:12.0pt;font-family:"Arial",sans-serif'> </span></p>

<p class=MsoNormal align=center style='margin-bottom:0in;text-align:center;
line-height:115%'><span style='font-size:12.0pt;line-height:115%;font-family:
"Arial",sans-serif'>As</span><span lang=IN style='font-size:12.0pt;line-height:
115%;font-family:"Arial",sans-serif'>s</span><span style='font-size:12.0pt;
line-height:115%;font-family:"Arial",sans-serif'>esmen</span><span lang=IN
style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>t</span><span
style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>/pengkajian</span><span
lang=IN style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>
R</span><span style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>isiko</span><span
style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'> </span><span
style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>jatuh</span><span
style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'> </span><span
style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>pada</span><span
style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'> </span><span
style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>pasien</span><span
lang=IN style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>
R</span><span style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>awa</span><span
lang=IN style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>t
J</span><span style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>alan</span><span
style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'> </span><span
style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>menggunakan
GET UP AND GO.</span></p>

<p class=MsoNormal style='margin-bottom:0in;text-align:justify;line-height:
115%'><span style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>&nbsp;</span></p>

<p class=MsoListParagraphCxSpFirst style='margin-bottom:0in;text-indent:-.25in'><span
style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>1.<span
style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp; </span></span><span
style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>Pengkajian</span></p>

<div align=center>

<table class=MsoTableGrid border=1 cellspacing=0 cellpadding=0
 style='border-collapse:collapse;border:none'>
 <tr>
  <td width=49 style='width:36.45pt;border:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle align=center style='margin-left:0in;
  text-align:center'><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'>No.</span></p>
  </td>
  <td width=380 style='width:284.95pt;border:solid windowtext 1.0pt;border-left:
  none;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle align=center style='margin-left:0in;
  text-align:center'><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'>Penilaian</span></p>
  </td>
  <td width=94 style='width:70.85pt;border:solid windowtext 1.0pt;border-left:
  none;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle align=center style='margin-left:0in;
  text-align:center'><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'>Ya=1</span></p>
  </td>
  <td width=95 style='width:70.9pt;border:solid windowtext 1.0pt;border-left:
  none;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpLast align=center style='margin-left:0in;
  text-align:center'><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'>Tidak=0</span></p>
  </td>
 </tr>
 <?php
    $fall = $koneksi->query("SELECT *, COUNT(*) as jumlah FROM fallrisk WHERE id_pasien = '$_GET[kunjungan]' ORDER BY id desc")->fetch_assoc();
    if($fall['jumlah'] != 0){
  ?>

<tr>
<?= $fall['jumlah'] ?>
  <td width=49 valign=top style='width:36.45pt;border:solid windowtext 1.0pt;
  border-top:none;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraph align=center style='margin-left:0in;text-align:
  center'><span style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>1. </span></p>
  </td>
  <td width=380 valign=top style='width:284.95pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0in;text-align:justify;line-height:
  115%'><span style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>Cara
  berjalan</span><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'> </span><span style='font-size:12.0pt;line-height:115%;
  font-family:"Arial",sans-serif'>pasien (salah</span><span style='font-size:
  12.0pt;line-height:115%;font-family:"Arial",sans-serif'> </span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>satu
  / lebih)</span><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'> </span></p>
  <p class=MsoNormal style='margin-bottom:0in;text-align:justify;line-height:
  115%'><span style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>
  <?php if($fall['cara1'] == 'on'){ ?>  ✔<?php } ?>
  Jalan</span><span style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>
  </span><span style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>tidak</span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'> </span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>seimbang
  /sempoyongan / limbung</span></p>
  <p class=MsoListParagraphCxSpFirst style='margin-left:0in;text-align:justify'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'><?php if($fall['cara2'] == 'on'){ ?>  ✔ <?php }?>

  Jalan</span><span style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>
  </span><span style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>menggunakan</span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'> </span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>alat
  bantu (kruk, tripot, kursiroda, orang lain)</span></p>
  </td>
  <td width=94 valign=top style='width:70.85pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>&nbsp;</span><?= $fall['penilaianya1']?></p>
  </td>
  <td width=95 valign=top style='width:70.9pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpLast style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>&nbsp;</span><?= $fall['penilaiantdk2']?></p>
  </td>
 </tr>
 <tr style='height:49.65pt'>
  <td width=49 valign=top style='width:36.45pt;border:solid windowtext 1.0pt;
  border-top:none;padding:0in 5.4pt 0in 5.4pt;height:49.65pt'>
  <p class=MsoListParagraphCxSpFirst align=center style='margin-left:0in;
  text-align:center'><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'>2.</span></p>
  <p class=MsoListParagraphCxSpLast align=center style='margin-left:0in;
  text-align:center'><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'>&nbsp;</span></p>
  </td>
  <td width=380 valign=top style='width:284.95pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt;height:49.65pt'>
  <p class=MsoNormal style='margin-bottom:0in;text-align:justify;line-height:
  115%'><span style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>Menopang</span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'> </span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>saat</span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'> </span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>akan</span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'> </span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>duduk
  : tampak</span><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'> </span><span style='font-size:12.0pt;line-height:115%;
  font-family:"Arial",sans-serif'>memegang</span><span style='font-size:12.0pt;
  line-height:115%;font-family:"Arial",sans-serif'> </span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>pinggiran</span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'> </span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>kursi</span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'> </span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>atau</span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'> </span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>meja/benda
  lain sebagai</span><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'> </span><span style='font-size:12.0pt;line-height:115%;
  font-family:"Arial",sans-serif'>penopang</span><span style='font-size:12.0pt;
  line-height:115%;font-family:"Arial",sans-serif'> </span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>saat</span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'> </span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>akan</span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'> </span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>duduk</span></p>
  </td>
  <td width=94 valign=top style='width:70.85pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt;height:49.65pt'>
  <p class=MsoListParagraphCxSpFirst style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>&nbsp;</span><?= $fall['penilaianya2']?></p>
  </td>
  <td width=95 valign=top style='width:70.9pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt;height:49.65pt'>
  <p class=MsoListParagraphCxSpMiddle style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>&nbsp;</span><?= $fall['penilaiantdk2']?></p>
  <p class=MsoListParagraphCxSpLast style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>&nbsp;</span></p>
  </td>
 </tr>
 <tr>
  <td width=523 colspan=3 style='width:392.55pt;border:solid windowtext 1.0pt;
  border-top:none;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpFirst align=right style='margin-left:0in;
  text-align:right'><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'>Total Score</span></p>
  </td>
  <td width=95 valign=top style='width:70.9pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpLast style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>&nbsp;</span><?= $fall['skor_penilaian']?></p>
  </td>
 </tr>
</table>

</div>

<p class=MsoNormal style='margin-bottom:0in'><span lang=IN style='font-size:
12.0pt;line-height:107%;font-family:"Arial",sans-serif'>&nbsp;</span></p>

<p class=MsoNormal style='margin-bottom:0in'><span lang=IN style='font-size:
12.0pt;line-height:107%;font-family:"Arial",sans-serif'>&nbsp;</span></p>

<p class=MsoListParagraphCxSpFirst style='margin-bottom:0in;text-indent:-.25in'><span
style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>2.<span
style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp; </span></span><span
style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>Hasil
&amp; Tindakan</span></p>

<table class=MsoTableGrid border=1 cellspacing=0 cellpadding=0
 style='margin-left:35.2pt;border-collapse:collapse;border:none'>
 <tr>
  <td width=88 style='width:66.35pt;border:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle align=center style='margin-left:0in;
  text-align:center'><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'>Total score</span></p>
  </td>
  <td width=123 style='width:92.05pt;border:solid windowtext 1.0pt;border-left:
  none;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle align=center style='margin-left:0in;
  text-align:center'><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'>Kategori</span></p>
  </td>
  <td width=151 style='width:113.4pt;border:solid windowtext 1.0pt;border-left:
  none;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle align=center style='margin-left:0in;
  text-align:center'><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'>Tindakan</span></p>
  </td>
  <td width=66 style='width:49.6pt;border:solid windowtext 1.0pt;border-left:
  none;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle align=center style='margin-left:0in;
  text-align:center'><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'>Ya</span></p>
  </td>
  <td width=63 style='width:47.15pt;border:solid windowtext 1.0pt;border-left:
  none;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle align=center style='margin-left:0in;
  text-align:center'><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'>Tidak</span></p>
  </td>
  <td width=141 style='width:105.5pt;border:solid windowtext 1.0pt;border-left:
  none;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpLast align=center style='margin-left:0in;
  text-align:center'><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'>TTD Nama</span><span lang=IN style='font-size:12.0pt;
  line-height:115%;font-family:"Arial",sans-serif'> P</span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>etugas</span></p>
  </td>
 </tr>
 <tr>
  <td width=88 style='width:66.35pt;border:solid windowtext 1.0pt;border-top:
  none;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpFirst align=center style='margin-left:0in;
  text-align:center'><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'>0</span></p>
  </td>
  <td width=123 style='width:92.05pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle align=center style='margin-left:0in;
  text-align:center'><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'>Tidak</span><span lang=IN style='font-size:12.0pt;
  line-height:115%;font-family:"Arial",sans-serif'>  </span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>berisiko</span></p>
  </td>
  <td width=151 style='width:113.4pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>Tidak
  ada tindakan</span></p>
  </td>
  <td width=66 valign=top style='width:49.6pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>&nbsp;</span>  <?php if($fall['ya0'] == 'on'){ ?>  ✔ <?php } ?>
</p>
  </td>
  <td width=63 valign=top style='width:47.15pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>&nbsp;</span>  <?php if($fall['tidak0'] == 'on'){ ?>  ✔ <?php } ?>
</p>
  </td>
  <td width=141 valign=top style='width:105.5pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpLast style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>&nbsp;</span><?= $petugas ?></p>
  </td>
 </tr>
 <tr>
  <td width=88 style='width:66.35pt;border:solid windowtext 1.0pt;border-top:
  none;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpFirst align=center style='margin-left:0in;
  text-align:center'><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'>1</span></p>
  </td>
  <td width=123 style='width:92.05pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle align=center style='margin-left:0in;
  text-align:center'><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'>Risiko</span><span style='font-size:12.0pt;line-height:
  115%;font-family:"Arial",sans-serif'> </span><span style='font-size:12.0pt;
  line-height:115%;font-family:"Arial",sans-serif'>rendah</span></p>
  </td>
  <td width=151 style='width:113.4pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>Edukasi</span></p>
  </td>
  <td width=66 valign=top style='width:49.6pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>&nbsp;</span>  <?php if($fall['ya1'] == 'on'){ ?>  ✔ <?php } ?>
</p>
  </td>
  <td width=63 valign=top style='width:47.15pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>&nbsp;</span>  <?php if($fall['tidak1'] == 'on'){ ?>  ✔ <?php } ?>
</p>
  </td>
  <td width=141 valign=top style='width:105.5pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpLast style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>&nbsp;</span><?= $petugas ?></p>
  </td>
 </tr>
 <tr>
  <td width=88 style='width:66.35pt;border:solid windowtext 1.0pt;border-top:
  none;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpFirst align=center style='margin-left:0in;
  text-align:center'><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'>2</span></p>
  </td>
  <td width=123 style='width:92.05pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle align=center style='margin-left:0in;
  text-align:center'><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'>Risiko</span><span style='font-size:12.0pt;line-height:
  115%;font-family:"Arial",sans-serif'> </span><span style='font-size:12.0pt;
  line-height:115%;font-family:"Arial",sans-serif'>tinggi</span></p>
  </td>
  <td width=151 style='width:113.4pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>Pita
  kuning</span></p>
  </td>
  <td width=66 valign=top style='width:49.6pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>&nbsp;</span>  <?php if($fall['ya2'] == 'on'){ ?>  ✔ <?php } ?>
</p>
  </td>
  <td width=63 valign=top style='width:47.15pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>&nbsp;</span>  <?php if($fall['tidak2'] == 'on'){ ?>  ✔ <?php } ?>
</p>
  </td>
  <td width=141 valign=top style='width:105.5pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpLast style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>&nbsp;</span><?= $petugas ?></p>
  </td>
 </tr>
</table>

<p class=MsoNormal align=center style='margin-top:5.0pt;text-align:center'><span
style='font-size:14.0pt;line-height:107%;font-family:"Arial Black",sans-serif'>&nbsp;</span></p>

<br>


</div>
</form>
  <?php }else{ ?>
 <tr>
  <td width=49 valign=top style='width:36.45pt;border:solid windowtext 1.0pt;
  border-top:none;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraph align=center style='margin-left:0in;text-align:
  center'><span style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>1. </span></p>
  </td>
  <td width=380 valign=top style='width:284.95pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal style='margin-bottom:0in;text-align:justify;line-height:
  115%'><span style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>Cara
  berjalan</span><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'> </span><span style='font-size:12.0pt;line-height:115%;
  font-family:"Arial",sans-serif'>pasien (salah</span><span style='font-size:
  12.0pt;line-height:115%;font-family:"Arial",sans-serif'> </span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>satu
  / lebih)</span><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'> </span></p>
  <p class=MsoNormal style='margin-bottom:0in;text-align:justify;line-height:
  115%'><span style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'><input type="checkbox" name="cara1" id="" >
  Jalan</span><span style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>
  </span><span style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>tidak</span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'> </span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>seimbang
  /sempoyongan / limbung</span></p>
  <p class=MsoListParagraphCxSpFirst style='margin-left:0in;text-align:justify'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'><input type="checkbox" name="cara2" id="" >
  Jalan</span><span style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>
  </span><span style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>menggunakan</span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'> </span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>alat
  bantu (kruk, tripot, kursiroda, orang lain)</span></p>
  </td>
  <td width=94 valign=top style='width:70.85pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>&nbsp;</span><input name="penilaianya1" type="number" value="0"></p>
  </td>
  <td width=95 valign=top style='width:70.9pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpLast style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>&nbsp;</span><input name="penilaiantdk1" type="number" value="0"></p>
  </td>
 </tr>
 <tr style='height:49.65pt'>
  <td width=49 valign=top style='width:36.45pt;border:solid windowtext 1.0pt;
  border-top:none;padding:0in 5.4pt 0in 5.4pt;height:49.65pt'>
  <p class=MsoListParagraphCxSpFirst align=center style='margin-left:0in;
  text-align:center'><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'>2.</span></p>
  <p class=MsoListParagraphCxSpLast align=center style='margin-left:0in;
  text-align:center'><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'>&nbsp;</span></p>
  </td>
  <td width=380 valign=top style='width:284.95pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt;height:49.65pt'>
  <p class=MsoNormal style='margin-bottom:0in;text-align:justify;line-height:
  115%'><span style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>Menopang</span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'> </span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>saat</span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'> </span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>akan</span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'> </span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>duduk
  : tampak</span><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'> </span><span style='font-size:12.0pt;line-height:115%;
  font-family:"Arial",sans-serif'>memegang</span><span style='font-size:12.0pt;
  line-height:115%;font-family:"Arial",sans-serif'> </span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>pinggiran</span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'> </span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>kursi</span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'> </span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>atau</span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'> </span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>meja/benda
  lain sebagai</span><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'> </span><span style='font-size:12.0pt;line-height:115%;
  font-family:"Arial",sans-serif'>penopang</span><span style='font-size:12.0pt;
  line-height:115%;font-family:"Arial",sans-serif'> </span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>saat</span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'> </span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>akan</span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'> </span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>duduk</span></p>
  </td>
  <td width=94 valign=top style='width:70.85pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt;height:49.65pt'>
  <p class=MsoListParagraphCxSpFirst style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>&nbsp;</span><input name="penilaianya2" type="number" value="0"></p>
  </td>
  <td width=95 valign=top style='width:70.9pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt;height:49.65pt'>
  <p class=MsoListParagraphCxSpMiddle style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>&nbsp;</span><input name="penilaiantdk2" type="number" value="0"></p>
  <p class=MsoListParagraphCxSpLast style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>&nbsp;</span></p>
  </td>
 </tr>
 <tr>
  <td width=523 colspan=3 style='width:392.55pt;border:solid windowtext 1.0pt;
  border-top:none;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpFirst align=right style='margin-left:0in;
  text-align:right'><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'>Total Score</span></p>
  </td>
  <td width=95 valign=top style='width:70.9pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpLast style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>&nbsp;</span><input name="skor_penilaian" type="number" value="0"></p>
  </td>
 </tr>
</table>

</div>

<p class=MsoNormal style='margin-bottom:0in'><span lang=IN style='font-size:
12.0pt;line-height:107%;font-family:"Arial",sans-serif'>&nbsp;</span></p>

<p class=MsoNormal style='margin-bottom:0in'><span lang=IN style='font-size:
12.0pt;line-height:107%;font-family:"Arial",sans-serif'>&nbsp;</span></p>

<p class=MsoListParagraphCxSpFirst style='margin-bottom:0in;text-indent:-.25in'><span
style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>2.<span
style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp; </span></span><span
style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>Hasil
&amp; Tindakan</span></p>

<table class=MsoTableGrid border=1 cellspacing=0 cellpadding=0
 style='margin-left:35.2pt;border-collapse:collapse;border:none'>
 <tr>
  <td width=88 style='width:66.35pt;border:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle align=center style='margin-left:0in;
  text-align:center'><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'>Total score</span></p>
  </td>
  <td width=123 style='width:92.05pt;border:solid windowtext 1.0pt;border-left:
  none;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle align=center style='margin-left:0in;
  text-align:center'><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'>Kategori</span></p>
  </td>
  <td width=151 style='width:113.4pt;border:solid windowtext 1.0pt;border-left:
  none;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle align=center style='margin-left:0in;
  text-align:center'><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'>Tindakan</span></p>
  </td>
  <td width=66 style='width:49.6pt;border:solid windowtext 1.0pt;border-left:
  none;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle align=center style='margin-left:0in;
  text-align:center'><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'>Ya</span></p>
  </td>
  <td width=63 style='width:47.15pt;border:solid windowtext 1.0pt;border-left:
  none;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle align=center style='margin-left:0in;
  text-align:center'><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'>Tidak</span></p>
  </td>
  <td width=141 style='width:105.5pt;border:solid windowtext 1.0pt;border-left:
  none;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpLast align=center style='margin-left:0in;
  text-align:center'><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'>TTD Nama</span><span lang=IN style='font-size:12.0pt;
  line-height:115%;font-family:"Arial",sans-serif'> P</span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>etugas</span></p>
  </td>
 </tr>
 <tr>
  <td width=88 style='width:66.35pt;border:solid windowtext 1.0pt;border-top:
  none;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpFirst align=center style='margin-left:0in;
  text-align:center'><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'>0</span></p>
  </td>
  <td width=123 style='width:92.05pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle align=center style='margin-left:0in;
  text-align:center'><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'>Tidak</span><span lang=IN style='font-size:12.0pt;
  line-height:115%;font-family:"Arial",sans-serif'>  </span><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>berisiko</span></p>
  </td>
  <td width=151 style='width:113.4pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>Tidak
  ada tindakan</span></p>
  </td>
  <td width=66 valign=top style='width:49.6pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>&nbsp;</span><input type="checkbox"  name="ya0" id="" ></p>
  </td>
  <td width=63 valign=top style='width:47.15pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>&nbsp;</span><input type="checkbox"  name="tidak0" id="" ></p>
  </td>
  <td width=141 valign=top style='width:105.5pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpLast style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>&nbsp;</span><?= $petugas ?></p>
  </td>
 </tr>
 <tr>
  <td width=88 style='width:66.35pt;border:solid windowtext 1.0pt;border-top:
  none;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpFirst align=center style='margin-left:0in;
  text-align:center'><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'>1</span></p>
  </td>
  <td width=123 style='width:92.05pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle align=center style='margin-left:0in;
  text-align:center'><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'>Risiko</span><span style='font-size:12.0pt;line-height:
  115%;font-family:"Arial",sans-serif'> </span><span style='font-size:12.0pt;
  line-height:115%;font-family:"Arial",sans-serif'>rendah</span></p>
  </td>
  <td width=151 style='width:113.4pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>Edukasi</span></p>
  </td>
  <td width=66 valign=top style='width:49.6pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>&nbsp;</span><input type="checkbox"  name="ya1" id="" ></p>
  </td>
  <td width=63 valign=top style='width:47.15pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>&nbsp;</span><input type="checkbox"  name="tidak1" id="" ></p>
  </td>
  <td width=141 valign=top style='width:105.5pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpLast style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>&nbsp;</span><?= $petugas ?></p>
  </td>
 </tr>
 <tr>
  <td width=88 style='width:66.35pt;border:solid windowtext 1.0pt;border-top:
  none;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpFirst align=center style='margin-left:0in;
  text-align:center'><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'>2</span></p>
  </td>
  <td width=123 style='width:92.05pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle align=center style='margin-left:0in;
  text-align:center'><span style='font-size:12.0pt;line-height:115%;font-family:
  "Arial",sans-serif'>Risiko</span><span style='font-size:12.0pt;line-height:
  115%;font-family:"Arial",sans-serif'> </span><span style='font-size:12.0pt;
  line-height:115%;font-family:"Arial",sans-serif'>tinggi</span></p>
  </td>
  <td width=151 style='width:113.4pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>Pita
  kuning</span></p>
  </td>
  <td width=66 valign=top style='width:49.6pt;border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>&nbsp;</span><input type="checkbox"  name="ya2" id="" ></p>
  </td>
  <td width=63 valign=top style='width:47.15pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpMiddle style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>&nbsp;</span><input type="checkbox"  name="tidak2" id="" ></p>
  </td>
  <td width=141 valign=top style='width:105.5pt;border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoListParagraphCxSpLast style='margin-left:0in'><span
  style='font-size:12.0pt;line-height:115%;font-family:"Arial",sans-serif'>&nbsp;</span><?= $petugas ?></p>
  </td>
 </tr>
</table>

<p class=MsoNormal align=center style='margin-top:5.0pt;text-align:center'><span
style='font-size:14.0pt;line-height:107%;font-family:"Arial Black",sans-serif'>&nbsp;</span></p>

<br>
<center>
<button type="submit" name="simpan" class="btn btn-success">Simpan</button>
</center>

</div>
</form>
<?php } ?>


<?php
    if(isset($_POST['simpan'])){
      $koneksi->query("INSERT INTO fallrisk (cara1, cara2, penilaianya1, penilaianya2, penilaiantdk1, penilaiantdk2, skor_penilaian, ya0, ya1, ya2, tidak0, tidak1, tidak2, ttd0, ttd1, ttd2,  id_pasien, kamar) VALUES ('$_POST[cara1]', '$_POST[cara2]', '$_POST[penilaianya1]', '$_POST[penilaianya2]', '$_POST[penilaiantdk1]', '$_POST[penilaiantdk2]', '$_POST[skor_penilaian]', '$_POST[ya0]', '$_POST[ya1]', '$_POST[ya2]', '$_POST[tidak0]', '$_POST[tidak1]', '$_POST[tidak2]', '$petugas', '$petugas', '$petugas', '$_GET[kunjungan]', '$_POST[kamar]');");

      echo "
        <script>
          alert('Berhasil Mengisi Fallrisk');
          document.location.href='fal-risk.php?id=$_GET[id]&kunjungan=$_GET[kunjungan]';
        </script>
      ";
    }
  ?>

<script>
    /* window.print(); */
</script>


</body>

</html>
