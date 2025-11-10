<?php
$currentURL = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] : '';
$currentURL = strtolower($currentURL);
if (strpos($currentURL, 'wonorejo') !== false) {
    // khmWonorejo
    $token = "yy2YJNMSV6ukymA9ESSJZgzET9934vntnlefVRlv3i7qLHpWR1MWAXdY4FjOniU6.kDnTH35X";
    $mes = "Terimakasih telah berkunjung ke KHM Wonorejo. Demi meningkatkan pelayanan kami, mohon berikan penilaian kepada staf kami. Klik link berikut: www.simkhm.id/wonorejo/admin/rating/rating.php?id=";
}elseif (strpos($currentURL, 'klakah') !== false) {
    // khmKlakah
    $token = "o4lREoqBdejIiazuXL1LUoR4DQmEuFYAyHLpsNG6XLE95gQ0Ki6OyEe3LwbzETfP.RXfzTcve";
    $mes = "Terimakasih telah berkunjung ke KHM Klakah. Demi meningkatkan pelayanan kami, mohon berikan penilaian kepada staf kami. Klik link berikut: www.simkhm.id/klakah/admin/rating/rating.php?id=";
}elseif (strpos($currentURL, 'tunjung') !== false) {
    // khmTunjung
    $token = "uCRKkADR4R1ksQCUzNZlGYlBwdOVFLxNuKbFhIaGBrcAg68L20x521Ky20Qp9AFI.YcL8YiI9";
    $mes = "Terimakasih telah berkunjung ke KHM Tunjung. Demi meningkatkan pelayanan kami, mohon berikan penilaian kepada staf kami. Klik link berikut: www.simkhm.id/tunjung/admin/rating/rating.php?id=";
}else{
    // khmKunir
    $token = "Z8ziaT2eJGsWqLu99IhcZyAa3V6GUtVA58nsFIrkMGt8dYa8Dw9oASO.qQotwuaD";
    $mes = "Terimakasih telah berkunjung ke KHM Kunir. Demi meningkatkan pelayanan kami, mohon berikan penilaian kepada staf kami. Klik link berikut: www.simkhm.id/kunir/admin/rating/rating.php?id=";
}

// Wonorejo
// $token = "yy2YJNMSV6ukymA9ESSJZgzET9934vntnlefVRlv3i7qLHpWR1MWAXdY4FjOniU6.kDnTH35X";
// $mes = "Terimakasih telah berkunjung ke KHM Wonorejo. Demi meningkatkan pelayanan kami, mohon berikan penilaian kepada staf kami. Klik link berikut: www.simkhm.id/wonorejo/admin/rating/rating.php?id=";

// KUNIR
// $token = "Z8ziaT2eJGsWqLu99IhcZyAa3V6GUtVA58nsFIrkMGt8dYa8Dw9oASO.qQotwuaD";
//     $mes = "Terimakasih telah berkunjung ke KHM Kunir. Demi meningkatkan pelayanan kami, mohon berikan penilaian kepada staf kami. Klik link berikut: www.simkhm.id/kunir/admin/rating/rating.php?id=";

    // Klakah
    // $token = "o4lREoqBdejIiazuXL1LUoR4DQmEuFYAyHLpsNG6XLE95gQ0Ki6OyEe3LwbzETfP.RXfzTcve";
    // $mes = "Terimakasih telah berkunjung ke KHM Klakah. Demi meningkatkan pelayanan kami, mohon berikan penilaian kepada staf kami. Klik link berikut: www.simkhm.id/klakah/admin/rating/rating.php?id=";

    // Tunjung
    // $token = "uCRKkADR4R1ksQCUzNZlGYlBwdOVFLxNuKbFhIaGBrcAg68L20x521Ky20Qp9AFI.YcL8YiI9";
    // $mes = "Terimakasih telah berkunjung ke KHM Tunjung. Demi meningkatkan pelayanan kami, mohon berikan penilaian kepada staf kami. Klik link berikut: www.simkhm.id/tunjung/admin/rating/rating.php?id=";
?>