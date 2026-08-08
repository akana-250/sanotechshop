<?php

ob_start();

session_start();


// Clear all session data
$_SESSION = [];


// Destroy session
if(session_status() === PHP_SESSION_ACTIVE){

    session_destroy();

}


// Remove session cookie
if(isset($_COOKIE[session_name()])){

    setcookie(
        session_name(),
        '',
        time() - 42000,
        '/'
    );

}


// Redirect to homepage

header("Location: http://localhost/SANO%20TECH%20SHOP/index.php");

exit;

?>