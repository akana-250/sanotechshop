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


$conn->beginTransaction();






/*
|--------------------------------------------------------------------------
| FORM DATA
|--------------------------------------------------------------------------
*/


$customer_id = !empty($_POST['customer_id'])

    ? (int)$_POST['customer_id']

    : null;





$quotation_date = $_POST['quotation_date'] ?? date('Y-m-d');



$status = $_POST['status'] ?? 'Pending';



$notes = $_POST['notes'] ?? '';






$product_ids = $_POST['product_id'] ?? [];



$prices = $_POST['price'] ?? [];



$quantities = $_POST['quantity'] ?? [];








/*
|--------------------------------------------------------------------------
| GENERATE QUOTATION NUMBER
|--------------------------------------------------------------------------
*/


$quotation_no = "QT-" . date("YmdHis");






/*
|--------------------------------------------------------------------------
| CALCULATE TOTAL
|--------------------------------------------------------------------------
*/


$total = 0;



foreach($product_ids as $key=>$product_id){



    $qty = (int)($quantities[$key] ?? 0);



    $price = (float)($prices[$key] ?? 0);




    if($qty > 0){


        $total += ($qty * $price);


    }


}

/*
|--------------------------------------------------------------------------
| INSERT QUOTATION
|--------------------------------------------------------------------------
*/


$stmt = $conn->prepare("

INSERT INTO quotations

(

quotation_no,

customer_id,

total,

status,

notes,

created_at

)

VALUES

(

?,?,?,?,?,NOW()

)


");



$stmt->execute([


    $quotation_no,

    $customer_id,

    $total,

    $status,

    $notes


]);






$quotation_id = (int)$conn->lastInsertId();








/*
|--------------------------------------------------------------------------
| INSERT QUOTATION ITEMS
|--------------------------------------------------------------------------
*/


$item_stmt = $conn->prepare("

INSERT INTO quotation_items

(

quotation_id,

product_id,

quantity,

price,

subtotal

)

VALUES

(

?,?,?,?,?

)


");







foreach($product_ids as $key=>$product_id){



    $qty = (int)($quantities[$key] ?? 0);



    $price = (float)($prices[$key] ?? 0);






    if($qty <= 0){


        continue;


    }







    $subtotal = $qty * $price;






    $item_stmt->execute([


        $quotation_id,

        (int)$product_id,

        $qty,

        $price,

        $subtotal


    ]);



}






$conn->commit();






$_SESSION['success'] = "Quotation created successfully.";




header("Location: quotation_view.php?id=".$quotation_id);


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