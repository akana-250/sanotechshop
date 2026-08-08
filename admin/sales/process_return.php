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



$product_ids = $_POST['product_id'] ?? [];



$return_qty = $_POST['return_qty'] ?? [];



$reasons = $_POST['reason'] ?? [];






if($sale_id <= 0){


    throw new Exception("Invalid sale");


}





/*
|--------------------------------------------------------------------------
| PREPARE STOCK UPDATE
|--------------------------------------------------------------------------
*/


$update_stock = $conn->prepare("

UPDATE products

SET quantity = quantity + ?

WHERE id = ?

");






/*
|--------------------------------------------------------------------------
| STOCK MOVEMENT
|--------------------------------------------------------------------------
*/


$movement = $conn->prepare("

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
| PROCESS RETURN PRODUCTS
|--------------------------------------------------------------------------
*/


foreach($product_ids as $key => $product_id){



    $product_id = (int)$product_id;



    $qty = (int)($return_qty[$key] ?? 0);






    if($product_id <= 0 || $qty <= 0){


        continue;


    }







    /*
    UPDATE PRODUCT STOCK
    */


    $update_stock->execute([


        $qty,

        $product_id


    ]);







    /*
    SAVE STOCK MOVEMENT
    */


    $movement->execute([


        $product_id,

        "IN",

        $qty


    ]);



}






/*
|--------------------------------------------------------------------------
| COMPLETE RETURN
|--------------------------------------------------------------------------
*/


$conn->commit();




$_SESSION['success'] = "Product return processed successfully.";



header("Location: view.php?id=".$sale_id);

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