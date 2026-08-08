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






if($_SERVER['REQUEST_METHOD'] !== 'POST'){


    header("Location: quotation.php");

    exit;


}







try{



/*
|--------------------------------------------------------------------------
| FORM DATA
|--------------------------------------------------------------------------
*/


$quotation_id = (int)($_POST['quotation_id'] ?? 0);



$customer_id = !empty($_POST['customer_id'])

    ? (int)$_POST['customer_id']

    : null;




$total = (float)($_POST['total'] ?? 0);



$status = $_POST['status'] ?? 'Pending';



$notes = $_POST['notes'] ?? '';






if($quotation_id <= 0){


    throw new Exception("Invalid quotation ID");


}







/*
|--------------------------------------------------------------------------
| UPDATE QUOTATION
|--------------------------------------------------------------------------
*/


$stmt = $conn->prepare("

UPDATE quotations SET


customer_id = ?,


total = ?,


status = ?,


notes = ?



WHERE id = ?



");

$stmt->execute([


    $customer_id,

    $total,

    $status,

    $notes,

    $quotation_id


]);







$_SESSION['success'] = "Quotation updated successfully.";





header("Location: quotation_view.php?id=".$quotation_id);

exit;







}catch(Exception $e){



$_SESSION['error'] = $e->getMessage();



header("Location: quotation.php");

exit;


}





?>