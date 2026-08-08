<?php

error_reporting(E_ALL);
ini_set('display_errors',1);


if(session_status() === PHP_SESSION_NONE){
    session_start();
}


require_once "../config/config.php";
require_once "../includes/admin_check.php";



// Check ID

if(!isset($_GET['id'])){

    header("Location: users.php");
    exit();

}



$id = $_GET['id'];



// Prevent deleting yourself

if($id == $_SESSION['user_id']){


    $_SESSION['error'] = 
    "You cannot delete your own account";


    header("Location: users.php");
    exit();

}




// Check user exists

$check = $conn->prepare("
    SELECT id
    FROM users
    WHERE id = ?
");


$check->execute([$id]);



if($check->fetch()){



    $delete = $conn->prepare("
        DELETE FROM users
        WHERE id = ?
    ");



    $delete->execute([$id]);



    $_SESSION['success'] =
    "User deleted successfully";



}



header("Location: users.php");

exit();


?>