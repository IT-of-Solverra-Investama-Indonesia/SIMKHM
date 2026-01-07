<?php



session_start();

$_SESSION = [];

session_unset();

session_destroy();



setcookie('id', '', time() - 36000000);

setcookie('key', '', time() - 36000000);

?>
<!DOCTYPE html>
<html>

<head>
    <title>Logout</title>
</head>

<body>
    <script>
        // Hapus data login dari localStorage
        localStorage.removeItem('khm_login_data');
        // Redirect ke halaman login
        window.location.href = 'login.php';
    </script>
</body>

</html>