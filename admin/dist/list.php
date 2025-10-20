<?php 

// $hal=$_GET['halaman'];
$hal = isset($_GET['halaman']) ? $_GET['halaman'] : '';

// $level=$_SESSION['admin']['level'];

// $username=$_SESSION['admin']['username'];



                   // rawatjalan
                   if ($hal=="registrasirawat"){include '../rawatjalan/registrasirawat.php';}
                   elseif ($hal=="editrawat"){include '../rawatjalan/editrawat.php';}
                   elseif ($hal=="daftarregistrasi"){include '../rawatjalan/daftarregistrasi.php';}
                   elseif ($hal=="pendaftaranall"){include '../rawatjalan/pendaftaranall.php';}
                   elseif ($hal=="dasboar"){include '../rawatjalan/dasboar.php';}
                   
                   
                   elseif ($hal=="dashboard_detail"){include '../dashboard/dashboard_detail.php';}
                   elseif ($hal=="dashboardinap"){include '../dashboard/dashboardinap.php';}
                   elseif ($hal=="dashboardstaff"){include '../dashboard/dashboardstaff.php';}

                   //    Gaji Dokter 
                   elseif ($hal=="gajidokter"){include '../gajidokter/gajidokter.php';}
                   elseif ($hal=="akun_gajidokter"){include '../gajidokter/akun_gajidokter.php';}
                   elseif ($hal=="gajidokter_history"){include '../gajidokter/gajidokter_history.php';}
                   //    ENd Gaji Dokter
                   
                   //    Absensi Dokter
                   elseif ($hal=="absensidokter"){include '../dokter/absensidokter.php';}
                     elseif ($hal=="absensidokter_history"){include '../dokter/absensidokter_history.php';}
                   //    End Absensi Dokter

                   //inap
                   elseif ($hal=="daftarregistrasiinap"){include '../rawatinap/daftarregistrasiinap.php';}
                   elseif ($hal=="falanakinap"){include '../rawatinap/falanakinap.php';}
                   elseif ($hal=="faldewasainap"){include '../rawatinap/faldewasainap.php';}
                   elseif ($hal=="pengkajian"){include '../rawatinap/pengkajian.php';}
                   elseif ($hal=="resumeinap"){include '../rekammedis/resumeinap.php';}
                   elseif ($hal=="asuhangizi"){include '../rawatinap/asuhangizi.php';}
                   elseif ($hal=="masukkeluar"){include '../rawatinap/masukkeluar.php';}
                   elseif ($hal=="lpo"){include '../rawatinap/lpo.php';}
                   elseif ($hal=="kajianawalinap"){include '../rawatinap/kajianawalinap.php';}
                   elseif ($hal=="rekappasienpulang"){include '../rawatinap/rekappasienpulang.php';}
                   elseif ($hal=="lpogizi"){include '../rawatinap/lpogizi.php';}
                   elseif ($hal=="rekappasienrujuk"){include '../rawatinap/rekappasienrujuk.php';}
                   elseif ($hal=="rekappasienperujuk"){include '../rawatinap/rekappasienperujuk.php';}

                   elseif ($hal=="cttpenyakit"){include '../rawatinap/cttpenyakit.php';}
                   elseif ($hal=="rekonobat"){include '../rawatinap/rekonobat.php';}
                   elseif ($hal=="edukasi"){include '../rawatinap/edukasi.php';}
                   elseif ($hal=="pulang"){include '../rawatinap/pulang.php';}
                   elseif ($hal=="ivl"){include '../rawatinap/ivl.php';}
                   elseif ($hal=="rekapinap"){include '../rawatinap/rekapinap.php';}
                   elseif ($hal=="entridetailinap"){include '../rawatinap/entridetailinap.php';}
                   elseif ($hal=="visitedokter"){include '../rawatinap/visitedokter.php';}
                   elseif ($hal=="kamar_inap"){include '../rawatinap/kamar_inap.php';}
                   elseif ($hal=="pasien_inap_pulang"){include '../rawatinap/pasien_inap_pulang.php';}


                   // pasien
                   elseif ($hal=="daftarpasien"){include '../pasien/daftarpasien.php';}
                   elseif ($hal=="detailpasien"){include '../pasien/detailpasien.php';}
                   elseif ($hal=="ubahpasien"){include '../pasien/ubahpasien.php';}
                   elseif ($hal=="hapuspasien"){include '../pasien/hapuspasien.php';}
                   elseif ($hal=="pasien"){include '../pasien/pasien.php';}
                   elseif ($hal=="ubahbpjs"){include '../pasien/ubahbpjs.php';}

                   //rating
                   elseif ($hal=="ratingall"){include '../rating/ratingall.php';}
                   elseif ($hal=="rating_user"){include '../rating/rating_user.php';}
                   //informasi
                   elseif ($hal=="informasi"){include '../informasi/informasi.php';}
                   elseif ($hal=="tambahinformasi"){include '../informasi/tambah-informasi.php';}
                   elseif ($hal=="editinformasi"){include '../informasi/edit-informasi.php';}

                   //rekam medis
                   elseif ($hal=="rekammedisall"){include '../rekammedis/rekammedisall.php';}
                   elseif ($hal=="resume"){include '../rekammedis/resume.php';}
                   elseif ($hal=="resumeedit"){include '../rekammedis/resumeedit.php';}
                   elseif ($hal=="rmedis"){include '../rekammedis/rmedis.php';}
                   elseif ($hal=="daftarrmedis"){include '../rekammedis/daftarrmedis.php';}
                   elseif ($hal=="detailrm"){include '../rekammedis/detailrm.php';}
                   elseif ($hal=="hapusrm"){include '../rekammedis/hapusrm.php';}
                   elseif ($hal=="editrm"){include '../rekammedis/editrm.php';}
                   elseif ($hal=="verif_obat"){include '../rekammedis/verif_obat.php';}
                   elseif ($hal=="kyc_satusehat"){include '../rekammedis/kyc_satusehat.php';}
                   elseif ($hal=="master_poli"){include '../rekammedis/master_poli.php';}
                   elseif ($hal=="master_layanan"){include '../rekammedis/master_layanan.php';}
                   elseif ($hal=="rmedis_editObatJenis"){include '../rekammedis/rmedis_editObatJenis.php';}

                   //pembayaran
                   elseif ($hal=="bayarrawat"){include '../bayar/bayarrawat.php';}
                   elseif ($hal=="daftarbayar"){include '../bayar/daftarbayar.php';}
                   elseif ($hal=="keuangan"){include '../bayar/keuangan.php';}

                   //log
                   elseif ($hal=="detaillog"){include '../log/detaillog.php';}

                   //lab
                   elseif ($hal=="daftarlab"){include '../lab/daftarlab.php';}
                   elseif ($hal=="rujuklab"){include '../lab/rujuklab.php';}
                   elseif ($hal=="detaillab"){include '../lab/detaillab.php';}
                   elseif ($hal=="ubahlab"){include '../lab/ubahlab.php';}
                   elseif ($hal=="hapuslab"){include '../lab/hapuslab.php';}
                   elseif ($hal=="rujuklab2"){include '../lab/rujuklab2.php';}
                   elseif ($hal=="isilab"){include '../lab/isilab.php';}
                   elseif ($hal=="detaillab2"){include '../lab/detaillab2.php';}
                   elseif ($hal=="ubahhasil"){include '../lab/ubahhasil.php';}
                   elseif ($hal=="riwayatLab"){include '../lab/riwayatLab.php';}

                   elseif ($hal=="daftarlabinap"){include '../lab/daftarlabinap.php';}
                   elseif ($hal=="isilabinap"){include '../lab/isilabinap.php';}
                   elseif ($hal=="detaillabinap"){include '../lab/detaillabinap.php';}
                   elseif ($hal=="ubahhasilinap"){include '../lab/ubahhasilinap.php';}
                   elseif ($hal=="daftarlabdata"){include '../lab/daftarlabdata.php';}
                   elseif ($hal=="tambahlab"){include '../lab/tambahlab.php';}
                   elseif ($hal=="ubahdaftar"){include '../lab/ubahdaftar.php';}

                   //radio
                   elseif ($hal=="daftarradio"){include '../radiologi/daftarradio.php';}
                   elseif ($hal=="tambahradio"){include '../radiologi/tambahradio.php';}
                   elseif ($hal=="detailradio"){include '../radiologi/detailradio.php';}
                   elseif ($hal=="ubahradio"){include '../radiologi/ubahradio.php';}
                   elseif ($hal=="hapusradio"){include '../radiologi/hapusradio.php';}

                   //terapi
                   elseif ($hal=="daftarterapi"){include '../terapi/daftarterapi.php';}
                   elseif ($hal=="tambahterapi"){include '../terapi/tambahterapi.php';}
                   elseif ($hal=="detailterapi"){include '../terapi/detailterapi.php';}
                   elseif ($hal=="ubahterapi"){include '../terapi/ubahterapi.php';}
                   elseif ($hal=="hapusterapi"){include '../terapi/hapusterapi.php';}

                   //igd
                   elseif ($hal=="daftarigd"){include '../igd/daftarigd.php';}
                   elseif ($hal=="tambahigd"){include '../igd/tambahigd.php';}
                   elseif ($hal=="detailigd"){include '../igd/detailigd.php';}
                   elseif ($hal=="ubahigd"){include '../igd/ubahigd.php';}
                   elseif ($hal=="hapusigd"){include '../igd/hapusigd.php';}
                   elseif ($hal=="falanak"){include '../igd/falanak.php';}
                   elseif ($hal=="faldewasa"){include '../igd/faldewasa.php';}
                   elseif ($hal=="biayaigd"){include '../igd/biayaigd.php';}

                    //apotek
                   elseif ($hal=="dashboardapotek"){include '../apotek/dashboardapotek.php';}
                   elseif ($hal=="dashboardkeuangan"){include '../dashboard/dashboardkeuangan.php';}
                   elseif ($hal=="daftarapotek"){include '../apotek/daftarapotek.php';}
                   elseif ($hal=="tambahapotek"){include '../apotek/tambahapotek.php';}
                   elseif ($hal=="detailapotek"){include '../apotek/detailapotek.php';}
                   elseif ($hal=="ubahapotek"){include '../apotek/ubahapotek.php';}
                   elseif ($hal=="hapusapotek"){include '../apotek/hapusapotek.php';}
                   elseif ($hal=="racik"){include '../apotek/racik.php';}
                   elseif ($hal=="daftarpuyer"){include '../apotek/daftarpuyer.php';}
                   elseif ($hal=="daftarpuyerjadi"){include '../apotek/daftarpuyerjadi.php';}
                   elseif ($hal=="tambahpuyer"){include '../apotek/tambahpuyer.php';}
                   elseif ($hal=="tambahpuyer2"){include '../apotek/tambahpuyer2.php';}
                   elseif ($hal=="rekapobat"){include '../apotek/rekapobat.php';}
                   elseif ($hal=="tambah_obatmasuk"){include '../apotek/tambah_obatmasuk.php';}
                   elseif ($hal=="daftar_obat_master"){include '../apotek/daftar_obat_master.php';}
                   elseif ($hal=="daftar_obat_selaras"){include '../apotek/daftar_obat_selaras.php';}
                   elseif ($hal=="harga_beli_tarakhir"){include '../apotek/harga_beli_terakhir.php';}
                   elseif ($hal=="margin_obat"){include '../apotek/margin_obat.php';}
                   elseif ($hal=="apotek_terima"){include '../apotek/apotek_terima.php';}
                   elseif ($hal=="apotek_terima_penerimaan"){include '../apotek/apotek_terima_penerimaan.php';}
                   elseif ($hal=="apotek_terima_riwayatpenerimaan"){include '../apotek/apotek_terima_riwayatpenerimaan.php';}
                   elseif ($hal=="apotek_obat_expired"){include '../apotek/apotek_obat_expired.php';}
                   elseif ($hal=="entri_obat_inap"){include '../apotek/entri_obat_inap.php';}
                   elseif ($hal=="stok_obat_apoteker"){include '../apotek/stok_obat_apoteker.php';}
                   elseif ($hal=="penjualan_obat_umum"){include '../apotek/penjualan_obat_umum.php';}
                   elseif ($hal=="penjualan_obat_resep"){include '../apotek/penjualan_obat_resep.php';}
                   elseif ($hal=="penjualan_obat_internal"){include '../apotek/penjualan_obat_internal.php';}
                   elseif ($hal=="penjualan_obat_rekanan"){include '../apotek/penjualan_obat_rekanan.php';}
                   elseif ($hal=="penjualan_obat_umum_riwayat"){include '../apotek/penjualan_obat_umum_riwayat.php';}
                   elseif ($hal=="penjualan_obat_resep_riwayat"){include '../apotek/penjualan_obat_resep_riwayat.php';}
                   elseif ($hal=="penjualan_obat_all_riwayat"){include '../apotek/penjualan_obat_all_riwayat.php';}
                   elseif ($hal=="setoran_shift_apoteker"){include '../apotek/setoran_shift_apoteker.php';}
                   elseif ($hal=="apoteker_hpp"){include '../apotek/apoteker_hpp.php';}
                   elseif ($hal=="padanan_obat"){include '../apotek/padanan_obat.php';}
                   elseif ($hal=="retur_obat_inap"){include '../apotek/retur_obat_inap.php';} elseif ($hal == "apotek_riwayat_faktur") {
    include '../apotek/apotek_riwayat_faktur.php';
}
                    
                    //laporan
                   elseif ($hal=="laporanmedis"){include '../laporan/laporanmedis.php';}
                   elseif ($hal=="pendapatan"){include '../laporan/pendapatan.php';}
                   
                   elseif ($hal=="polidaerah"){include 'polidaerah.php';}
                   
                    // Tenaga
                   elseif ($hal=="tenagamedis"){include '../tenagamedis/tenagamedis.php';}
                   elseif ($hal=="deltenagamedis"){include '../tenagamedis/deltenagamedis.php';}
                  //    elseif ($hal=="halamanutama"){include '../dist/halamanutama.php';}
                  elseif ($hal=="konsultasi"){include '../dist/konsultasi.php';}
                  elseif ($hal=="konsultasiall"){include '../dist/konsultasiall.php';}

                  elseif ($hal=="dokter_konsul"){include '../tenagamedis/dokter_konsul.php';}

                  
                  //  KOSMETIK
                  elseif ($hal=="produk_kosmetik"){include '../../kosmetik/produk_kosmetik.php';}
                  elseif ($hal=="pemesanan"){include '../dist/pemesanan.php';}
                  elseif ($hal=="daftarpasienkosmetik"){include '../dist/daftarpasienkosmetik.php';}
                  elseif ($hal=="editdaftarpasienkosmetik"){include '../dist/editdaftarpasienkosmetik.php';}

                  elseif ($hal=="profile"){include '../dist/profile.php';}  



                   ELSE {
                   include '../dist/halamanutama.php';};
                
// if(!isset($_GET['halaman'])){
//     include '../dist/halamanutama.php';
// }
              














 ?>