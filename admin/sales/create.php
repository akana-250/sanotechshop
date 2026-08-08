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


    header("Location: add.php");

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




$user_id = $_SESSION['user_id'] ?? 1;



$total = (float)($_POST['total'] ?? 0);



$paid = (float)($_POST['paid'] ?? 0);



$balance = (float)($_POST['balance'] ?? 0);



$payment_method = $_POST['payment_method'] ?? 'Cash';



$status = $_POST['status'] ?? 'Unpaid';






/*
|--------------------------------------------------------------------------
| GENERATE INVOICE NUMBER
|--------------------------------------------------------------------------
*/


$invoice_no = "INV-" . date("YmdHis");
<?php

/*
|--------------------------------------------------------------------------
| INSERT SALE
|--------------------------------------------------------------------------
*/


$stmt = $conn->prepare("

INSERT INTO sales

(

invoice_no,

customer_id,

user_id,

total,

paid,

balance,

payment_method,

status

)

VALUES

(

?,?,?,?,?,?,?,?

)

");



$stmt->execute([


$invoice_no,

$customer_id,

$user_id,

$total,

$paid,

$balance,

$payment_method,

$status


]);




$sale_id = (int)$conn->lastInsertId();
<?php

/*
|--------------------------------------------------------------------------
| INSERT SALE ITEMS + UPDATE STOCK
|--------------------------------------------------------------------------
*/


$product_ids = $_POST['product_id'] ?? [];

$quantities  = $_POST['quantity'] ?? [];

$prices      = $_POST['price'] ?? [];

$subtotals   = $_POST['subtotal'] ?? [];





$item_stmt = $conn->prepare("

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





$stock_stmt = $conn->prepare("

UPDATE products

SET quantity = quantity - ?

WHERE id = ?

AND quantity >= ?

");





$movement_stmt = $conn->prepare("

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






foreach($product_ids as $key => $product_id){



    $product_id = (int)$product_id;



    $qty = (int)($quantities[$key] ?? 0);



    $price = (float)($prices[$key] ?? 0);



    $subtotal = (float)($subtotals[$key] ?? 0);





    if($product_id <= 0 || $qty <= 0){

        continue;

    }





    /*
    SAVE SALE ITEM
    */


    $item_stmt->execute([


        $sale_id,

        $product_id,

        $qty,

        $price,

        $subtotal


    ]);







    /*
    REDUCE PRODUCT STOCK
    */


    $stock_stmt->execute([


        $qty,

        $product_id,

        $qty


    ]);







    if($stock_stmt->rowCount() === 0){


        throw new Exception("Insufficient stock for product ID ".$product_id);


    }







    /*
    STOCK MOVEMENT LOG
    */


    $movement_stmt->execute([


        $product_id,

        "OUT",

        $qty


    ]);



}
<?php


/*
|--------------------------------------------------------------------------
| FINISH TRANSACTION
|--------------------------------------------------------------------------
*/


$conn->commit();



$_SESSION['success'] = "Sale created successfully.";



header("Location: invoice.php?id=".$sale_id);

exit;




}catch(Exception $e){



if($conn->inTransaction()){

    $conn->rollBack();

}



$_SESSION['error'] = $e->getMessage();



header("Location: add.php");

exit;



}

?>