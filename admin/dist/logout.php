<?php 



session_start();

$_SESSION = [];

session_unset();

session_destroy();



setcookie('id', '', time()-36000000);

setcookie('key', '', time()-36000000);



header("location: login.php");

exit;



?>