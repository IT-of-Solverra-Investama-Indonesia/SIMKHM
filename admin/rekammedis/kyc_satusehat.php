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
        include "../rawatjalan/api_satusehat.php";
        // Get Token From API Satu Sehat 
            $getToken = curl_init();
            curl_setopt_array($getToken, array(
                CURLOPT_URL => 'https://api-satusehat.kemkes.go.id/oauth2/v1/accesstoken?grant_type=client_credentials',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'client_id=' . $clientKey . '&client_secret=' . $secretKey . '',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                ),
            ));
            $responseToken = curl_exec($getToken);
            curl_close($getToken);
            $pecahToken = json_decode($responseToken, true);
            $token = $pecahToken['access_token'];
        // End Get Token From API Satu Sehat 
        
        echo $bearerToken = $token; // Ganti dengan token akses yang Anda miliki
        
        // $data = array(
        //     "agent_name" => "$_POST[nama]",
        //     "agent_nik" => "$_POST[nik]",
        //     "public_key" => "-----BEGIN PUBLIC KEY-----\nMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwqoicEXIYWYV3PvLIdvB\nqFkHn2IMhPGKTiB2XA56enpPb0UbI9oHoetRF41vfwMqfFsy5Yd5LABxMGyHJBbP\n+3fk2/PIfv+7+9/dKK7h1CaRTeT4lzJBiUM81hkCFlZjVFyHUFtaNfvQeO2OYb7U\nkK5JrdrB4sgf50gHikeDsyFUZD1o5JspdlfqDjANYAhfz3aam7kCjfYvjgneqkV8\npZDVqJpQA3MHAWBjGEJ+R8y03hs0aafWRfFG9AcyaA5Ct5waUOKHWWV9sv5DQXmb\nEAoqcx0ZPzmHJDQYlihPW4FIvb93fMik+eW8eZF3A920DzuuFucpblWU9J9o5w+2\noQIDAQAB\n-----END PUBLIC KEY-----"
        // );
        
        // $ch = curl_init();
        
        // curl_setopt_array($ch, array(
        //     CURLOPT_URL => 'https://api-satusehat.dto.kemkes.go.id/kyc/v1/generate-url',
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'POST',
        //     CURLOPT_POSTFIELDS => json_encode($data),
        //     CURLOPT_HTTPHEADER => array(
        //         'Content-Type: application/json',
        //         'Authorization: Bearer ' . $bearerToken
        //     ),
        // ));
        
        // $response = curl_exec($ch);
        
        // curl_close($ch);
        
        // $responseData = json_decode($response, true);
        
        // // Lakukan sesuatu dengan responseData
        // print_r($responseData);
    }
?>