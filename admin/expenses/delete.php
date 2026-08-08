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
   CHECK ID
=========================== */


if(!isset($_GET['id'])){


header("Location:index.php");

exit;


}



$id = (int)$_GET['id'];







try{



/* ===========================
   DELETE EXPENSE
=========================== */


$stmt = $conn->prepare("

DELETE FROM expenses

WHERE id=?

");



$stmt->execute([$id]);






$_SESSION['success']="Expense deleted successfully";



}catch(PDOException $e){



$_SESSION['error']=$e->getMessage();



}




header("Location:index.php");

exit;



?>