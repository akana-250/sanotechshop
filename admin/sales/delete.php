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






$sale_id = isset($_GET['id'])

    ? (int)$_GET['id']

    : 0;





if($sale_id <= 0){


    header("Location: index.php");

    exit;


}





try{


$conn->beginTransaction();






/*
|--------------------------------------------------------------------------
| GET SALE ITEMS
|--------------------------------------------------------------------------
*/


$item_stmt = $conn->prepare("

SELECT

product_id,

quantity


FROM sale_items


WHERE sale_id = ?


");



$item_stmt->execute([$sale_id]);



$items = $item_stmt->fetchAll(PDO::FETCH_ASSOC);








/*
|--------------------------------------------------------------------------
| PREPARE STOCK RETURN
|--------------------------------------------------------------------------
*/


$stock_update = $conn->prepare("

UPDATE products

SET quantity = quantity + ?

WHERE id = ?

");


/*
|--------------------------------------------------------------------------
| RESTORE STOCK
|--------------------------------------------------------------------------
*/


foreach($items as $item){



    $stock_update->execute([


        (int)$item['quantity'],


        (int)$item['product_id']


    ]);



}







/*
|--------------------------------------------------------------------------
| DELETE SALE ITEMS
|--------------------------------------------------------------------------
*/


$delete_items = $conn->prepare("

DELETE FROM sale_items

WHERE sale_id = ?

");



$delete_items->execute([$sale_id]);








/*
|--------------------------------------------------------------------------
| DELETE SALE
|--------------------------------------------------------------------------
*/


$delete_sale = $conn->prepare("

DELETE FROM sales

WHERE id = ?

");



$delete_sale->execute([$sale_id]);







$conn->commit();






$_SESSION['success'] = "Sale deleted successfully.";





header("Location: index.php");

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