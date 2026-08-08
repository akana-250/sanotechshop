<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

require_once "../config/config.php";
require_once "../includes/admin_check.php";


if(!isset($_GET['id'])){

    header("Location: user.php");
    exit();

}


$id = $_GET['id'];



$stmt = $conn->prepare("
    UPDATE users
    SET status = 'Approved'
    WHERE id = ?
");


$stmt->execute([$id]);



header("Location: user.php");
exit();

?>