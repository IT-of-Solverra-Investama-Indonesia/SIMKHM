<?php
    // Jadi Aku Akn ngambil Ini dari google library, ini itu di dapat dari kita nginstall composer require google/auth
    use Google\Auth\Credentials\ServiceAccountCredentials;
    use Google\Auth\HttpHandler\HttpHandlerFactory;

    // Ambil Dari Vendor Hsil dari  install Library
    require 'vendor/autoload.php';

    // Bikin $credential Untuk handle Semua Key yang di butuhkan, dan file .json yang ada bisa di download di console firebase nya yaa
    $credential = new ServiceAccountCredentials(
        "https://www.googleapis.com/auth/firebase.messaging", json_decode(file_get_contents("khm-app-2024-firebase-adminsdk-m7jnb-aa26b7f4e9.json"), true)
    );

    // Nge Generate Token yang ada dari $credential yang udah di buat sebelumnya, jadi key dll nya udah ke handle
    $token = $credential->fetchAuthToken(HttpHandlerFactory::build()); 
    $accessToken = $token['access_token'];

    // Setting Payload mirip kayak bikin API biasa 
    $ch = curl_init("https://fcm.googleapis.com/v1/projects/khm-app-2024/messages:send");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [ 
        'Content-Type: application/json',
        'Authorization: Bearer '. $accessToken .''
    ]);

    curl_setopt($ch, CURLOPT_POSTFIELDS, '{
        "message" : {
            "token":"",
            "notification" : {
                "title": "Hai Sayang",
                "body" : "Mawar itu Biru Violet Itu Merah",
                "image" : ""
            },
            "webpush" : {
                "fcm_options" : {
                    "link" : ""
                }
            }
        }
    }');

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_VERBOSE, true);

    $response = curl_exec($ch);

    if(curl_errno($ch)){
        echo 'Error'.curl_errno($ch);
    }else{
        echo $response;
    }

    curl_close($ch);

    // Print Access Token, sebenre e ini gausa untuk keamanan, tapi ini sebagai Test
    echo "
        <script>console.log('accessToken : ".$accessToken."')</script>
    ";
?>