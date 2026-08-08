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


    header("Location: index.php");

    exit;


}







try{



/*
|--------------------------------------------------------------------------
| FORM DATA
|--------------------------------------------------------------------------
*/


$sale_id = (int)($_POST['sale_id'] ?? 0);



$customer_id = !empty($_POST['customer_id'])

    ? (int)$_POST['customer_id']

    : null;





$total = (float)($_POST['total'] ?? 0);



$paid = (float)($_POST['paid'] ?? 0);



$payment_method = $_POST['payment_method'] ?? 'Cash';



$status = $_POST['status'] ?? 'Unpaid';







if($sale_id <= 0){


    throw new Exception("Invalid sale ID");


}






/*
|--------------------------------------------------------------------------
| CALCULATE BALANCE
|--------------------------------------------------------------------------
*/


$balance = $total - $paid;




if($balance < 0){


    $balance = 0;


}





/*
|--------------------------------------------------------------------------
| UPDATE SALE
|--------------------------------------------------------------------------
*/


$stmt = $conn->prepare("

UPDATE sales SET


customer_id = ?,


total = ?,


paid = ?,


balance = ?,


payment_method = ?,


status = ?



WHERE id = ?



");

$stmt->execute([


    $customer_id,

    $total,

    $paid,

    $balance,

    $payment_method,

    $status,

    $sale_id


]);







$_SESSION['success'] = "Sale updated successfully.";



header("Location: view.php?id=".$sale_id);

exit;







}catch(Exception $e){



$_SESSION['error'] = $e->getMessage();



header("Location: index.php");

exit;


}



?>