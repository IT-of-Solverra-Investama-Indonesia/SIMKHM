<?php
$currentURL = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] : '';
$currentURL = strtolower($currentURL);
if (strpos($currentURL, 'wonorejo') !== false) {
  // hmWonorejo
  $organizationId = '100015704';
  $locationId = 'b898e537-81a8-4aca-9d76-baff855a9a31';
  $clientKey = 'pnZFT0j4Hs1FKIqQKeRspG1ncJwauPVKNnrT2OeiuPpP2E3l';
  $secretKey = 'FNTJCctvzsWjmjb7VHGbdzLT1xLG9FcV8bAWql27GKJ8o9S5iXxHvOQYpi85qzzv';
  $baseUrl  = 'https://api-satusehat.kemkes.go.id/fhir-r4/v1';
} elseif (strpos($currentURL, 'klakah') !== false) {
  // khmKlakah
  $organizationId = '100015693';
  $locationId = '366b8975-f968-4aa1-ad07-2ed54097c3af';
  $clientKey = '2tPO4k5CJXZO6MDrrLTIGadXAiYdZ3MvBMhA2LGHqxMcMsdB';
  $secretKey = 'ifcWUmxjm8I7CsWNFyTzP0Al7ZcFQTlkw8VpvwGwJYrg2lLK3GV6ilRA2tmWhx0K';
  $baseUrl  = 'https://api-satusehat.kemkes.go.id/fhir-r4/v1';
} elseif (strpos($currentURL, 'tunjung') !== false) {
  // khmTunjung
  $organizationId = '100117960';
  $locationId = '1a06f363-9c03-4b01-bc3c-e0465aed9917';
  $clientKey = 'lApZ8Rr9TQaRHXEQYXWJWHh3wAVY0nFPYX39OOU26jl0j9rY';
  $secretKey = 'oDtIeoawi0L7c20LcmBshzBHhdF614CabKhBX9fWSnuZAfsfWisovkumuy0CkYwx';
  $baseUrl  = 'https://api-satusehat.kemkes.go.id/fhir-r4/v1';
} else {
  // khmKunir
  $organizationId = '100501839';
  $locationId = 'd8bf801b-0578-47d9-b233-361bbb229e46';
  $clientKey = 'GuOjmsR7JghJtN7wA5iLm90RJA7Gcpr0PtSk3MnlNGqu1teo';
  $secretKey = 'oFdATkxEM1yW8s3I8fQ466s2YAfPAQJeVxC9AVO0UpSdufCfKMSStc2u0I6F8vhR';
  $baseUrl  = 'https://api-satusehat.kemkes.go.id/fhir-r4/v1';
}
?>