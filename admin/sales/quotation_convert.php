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






$quotation_id = isset($_GET['id'])

    ? (int)$_GET['id']

    : 0;





if($quotation_id <= 0){


    header("Location: quotation.php");

    exit;


}







try{


$conn->beginTransaction();






/*
|--------------------------------------------------------------------------
| GET QUOTATION
|--------------------------------------------------------------------------
*/


$stmt = $conn->prepare("

SELECT *

FROM quotations

WHERE id = ?

");



$stmt->execute([$quotation_id]);



$quotation = $stmt->fetch(PDO::FETCH_ASSOC);






if(!$quotation){


    throw new Exception("Quotation not found");


}








/*
|--------------------------------------------------------------------------
| GET QUOTATION ITEMS
|--------------------------------------------------------------------------
*/


$item_stmt = $conn->prepare("

SELECT *

FROM quotation_items

WHERE quotation_id = ?

");



$item_stmt->execute([$quotation_id]);



$items = $item_stmt->fetchAll(PDO::FETCH_ASSOC);






if(empty($items)){


    throw new Exception("Quotation has no products");


}

/*
|--------------------------------------------------------------------------
| CREATE SALE
|--------------------------------------------------------------------------
*/


$invoice_no = "INV-" . date("YmdHis");





$sale_stmt = $conn->prepare("

INSERT INTO sales

(

invoice_no,

customer_id,

total,

paid,

balance,

payment_method,

status,

sale_date

)

VALUES

(

?,?,?,?,?,?,?,NOW()

)

");





$sale_stmt->execute([


    $invoice_no,

    $quotation['customer_id'],

    $quotation['total'],

    0,

    $quotation['total'],

    'Cash',

    'Unpaid'


]);






$sale_id = (int)$conn->lastInsertId();








/*
|--------------------------------------------------------------------------
| PREPARE SALE ITEM INSERT
|--------------------------------------------------------------------------
*/


$item_insert = $conn->prepare("

INSERT INTO sale_items

(

sale_id,

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







/*
|--------------------------------------------------------------------------
| PREPARE STOCK OUT
|--------------------------------------------------------------------------
*/


$stock_update = $conn->prepare("

UPDATE products

SET quantity = quantity - ?

WHERE id = ?

");






$stock_move = $conn->prepare("

INSERT INTO stock_movements

(

product_id,

movement_type,

quantity

)

VALUES

(

?,?,?

)

");

/*
|--------------------------------------------------------------------------
| INSERT SALE ITEMS + STOCK OUT
|--------------------------------------------------------------------------
*/


foreach($items as $item){



    $quantity = (int)$item['quantity'];



    $price = (float)$item['price'];



    $subtotal = (float)$item['subtotal'];






    /*
    SAVE SALE ITEM
    */


    $item_insert->execute([


        $sale_id,

        $item['product_id'],

        $quantity,

        $price,

        $subtotal


    ]);







    /*
    UPDATE STOCK
    */


    $stock_update->execute([


        $quantity,

        $item['product_id']


    ]);







    /*
    STOCK MOVEMENT
    */


    $stock_move->execute([


        $item['product_id'],

        "OUT",

        $quantity


    ]);



}








/*
|--------------------------------------------------------------------------
| UPDATE QUOTATION STATUS
|--------------------------------------------------------------------------
*/


$update_quote = $conn->prepare("

UPDATE quotations

SET status = 'Converted'

WHERE id = ?

");



$update_quote->execute([$quotation_id]);







$conn->commit();







$_SESSION['success'] = "Quotation converted to sale successfully.";





header("Location: view.php?id=".$sale_id);

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