<?php

ob_start();

error_reporting(E_ALL);
ini_set('display_errors',1);


if(session_status() === PHP_SESSION_NONE){

    session_start();

}


require_once __DIR__ . "/../../config/config.php";

require_once __DIR__ . "/../../includes/admin_check.php";





/* ===========================
   CHECK USER ID
=========================== */


if(!isset($_GET['id'])){


    $_SESSION['error'] = "User ID missing.";

    header("Location:index.php");

    exit;


}



$id = (int) $_GET['id'];





try{


/* ===========================
   APPROVE USER
=========================== */


$stmt = $conn->prepare("

UPDATE users

SET status='Approved'

WHERE id=?

");


$stmt->execute([$id]);



$_SESSION['success'] = "User approved successfully.";



}catch(PDOException $e){


$_SESSION['error'] = $e->getMessage();


}





header("Location:index.php");

exit;


ob_end_flush();

?>