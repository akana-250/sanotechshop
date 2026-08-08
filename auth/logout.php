<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Siba session zose
$_SESSION = [];

// Siba session cookie niba iriho
if (ini_get("session.use_cookies")) {

    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

// Hagarika session
session_destroy();

// Ohereza kuri Home Page
header("Location: http://localhost/SANO%20TECH%20SHOP/index.php");
exit;