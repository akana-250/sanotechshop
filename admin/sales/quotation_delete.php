<?php

declare(strict_types=1);

ob_start();


error_reporting(E_ALL);
ini_set('display_errors','1');



if(session_status() === PHP_SESSION_NONE){

    session_start();

}



require_once __DIR__ . "/../../config/config.php";

require_once __DIR__ . "/../../includes/admin_check.php";

require_once __DIR__ . "/../../includes/db.php";






$id = isset($_GET['id'])

    ? (int)$_GET['id']

    : 0;





if($id <= 0){


    header("Location: quotation.php");

    exit;


}







try{


$conn->beginTransaction();







/*
|--------------------------------------------------------------------------
| DELETE QUOTATION ITEMS
|--------------------------------------------------------------------------
*/


$item_delete = $conn->prepare("

DELETE FROM quotation_items

WHERE quotation_id = ?

");



$item_delete->execute([$id]);







/*
|--------------------------------------------------------------------------
| DELETE QUOTATION
|--------------------------------------------------------------------------
*/


$quotation_delete = $conn->prepare("

DELETE FROM quotations

WHERE id = ?

");



$quotation_delete->execute([$id]);

$conn->commit();






$_SESSION['success'] = "Quotation deleted successfully.";





header("Location: quotation.php");

exit;








}catch(Exception $e){



if($conn->inTransaction()){


    $conn->rollBack();


}





$_SESSION['error'] = $e->getMessage();



header("Location: quotation.php");


exit;


}




?>