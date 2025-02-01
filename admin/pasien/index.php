<?php 
    session_start();
    
    if (!isset($_SESSION['pasien']['nama_lengkap'])) {
        header("Location: login.php");
        exit();
    }
    if(isset($_GET['logout'])){
      // Hapus semua data sesi
      session_unset();
      // Hancurkan sesi
      session_destroy();
      // Redirect ke halaman login
      header("Location: login.php");
      exit();

    }
    include "function.php";

?>