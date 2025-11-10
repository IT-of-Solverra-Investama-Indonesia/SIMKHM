<?php
session_start();
if(!isset($_GET['agent_name']) AND !isset($_SESSION['dokter_rawat'])){
  echo "
    <script>
      document.location.href='index.php?halaman=kyc_satusehat';
    </script>
  ";
}
include "../rawatjalan/api_satusehat.php";
// Tentukan file .ini berdasarkan URL
$currentURL = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] : '';
$currentURL = strtolower($currentURL);

if (strpos($currentURL, 'wonorejo') !== false) {
  $iniFile = "satusehat_wonorejo.ini";
} elseif (strpos($currentURL, 'klakah') !== false) {
  $iniFile = "satusehat_klakah.ini";
} elseif (strpos($currentURL, 'tunjung') !== false) {
  $iniFile = "satusehat_tunjung.ini";
} else {
  // Default ke kunir
  $iniFile = "satusehat_kunir.ini";
}

$init = parse_ini_file($iniFile);

$client_id = $clientKey;
$client_secret = $secretKey;
$auth_url = $init["auth_url"];
$api_url = $init["api_url"];

include('auth.php');
include('function.php');

// nama petugas/operator Fasilitas Pelayanan Kesehatan (Fasyankes) yang akan melakukan validasi
$agent_name = $_GET['agent_name'];

// NIK petugas/operator Fasilitas Pelayanan Kesehatan (Fasyankes) yang akan melakukan validasi
$agent_nik = $_GET['agent_nik'];

// auth to satusehat
$auth_result = authenticateWithOAuth2($client_id, $client_secret, $auth_url);

// Example usage
$json = generateUrl($agent_name, $agent_nik , $auth_result, $api_url);

$validation_web = json_decode($json, TRUE);

?><html>
<head>
  <script type="text/javascript">

    const url = "<?php echo $validation_web["data"]["url"]?>"

    function loadFormPopup() {
      let params = `scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no,width=0,height=0,left=100,top=100`;
      window.open(url,"KYC",params)
    }

    function loadFormNewTab() {
      window.open(url,"_blank")
    }

  </script>
</head>
<body>
  <button onclick="loadFormPopup()">KYC Pasien Popup</button>
  <button onclick="loadFormNewTab()">KYC Pasien New Tab</button>
</body>
</html>