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


$conn->beginTransaction();






/*
|--------------------------------------------------------------------------
| FORM DATA
|--------------------------------------------------------------------------
*/


$sale_id = (int)($_POST['sale_id'] ?? 0);



$amount = (float)($_POST['amount'] ?? 0);



$payment_method = $_POST['payment_method'] ?? 'Cash';






if($sale_id <= 0 || $amount <= 0){


    throw new Exception("Invalid payment data");


}





/*
|--------------------------------------------------------------------------
| GET CURRENT SALE
|--------------------------------------------------------------------------
*/


$stmt = $conn->prepare("

SELECT *

FROM sales

WHERE id = ?

FOR UPDATE


");



$stmt->execute([$sale_id]);



$sale = $stmt->fetch(PDO::FETCH_ASSOC);






if(!$sale){


    throw new Exception("Sale not found");


}






$new_paid = (float)$sale['paid'] + $amount;


$new_balance = (float)$sale['total'] - $new_paid;






if($new_balance < 0){


    $new_balance = 0;


}

/*
|--------------------------------------------------------------------------
| UPDATE PAYMENT STATUS
|--------------------------------------------------------------------------
*/


if($new_paid >= (float)$sale['total']){


    $status = "Paid";


}
elseif($new_paid > 0){


    $status = "Partial";


}
else{


    $status = "Unpaid";


}







/*
|--------------------------------------------------------------------------
| UPDATE SALES TABLE
|--------------------------------------------------------------------------
*/


$update = $conn->prepare("

UPDATE sales

SET

paid = ?,

balance = ?,

payment_method = ?,

status = ?

WHERE id = ?


");




$update->execute([


$new_paid,

$new_balance,

$payment_method,

$status,

$sale_id


]);







/*
|--------------------------------------------------------------------------
| FINISH
|--------------------------------------------------------------------------
*/


$conn->commit();




$_SESSION['success'] = "Payment updated successfully.";



header("Location: invoice.php?id=".$sale_id);

exit;





}catch(Exception $e){



if($conn->inTransaction()){


    $conn->rollBack();


}



$_SESSION['error'] = $e->getMessage();



header("Location: index.php");


exit;


}



?>

