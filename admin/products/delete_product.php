<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors','1');


if(session_status() === PHP_SESSION_NONE){
    session_start();
}


require_once __DIR__ . "/../../config/config.php";
require_once __DIR__ . "/../../includes/admin_check.php";
require_once __DIR__ . "/../../includes/db.php";



$id = (int)($_GET['id'] ?? 0);



if($id <= 0){

    header("Location:index.php");
    exit();

}




try{


/*
========================
CHECK PRODUCT EXISTS
========================
*/


$check = $conn->prepare(

"
SELECT image

FROM products

WHERE id = ?

"

);


$check->execute([$id]);


$product = $check->fetch(PDO::FETCH_ASSOC);



if(!$product){


header("Location:index.php");

exit();


}





/*
========================
DELETE PRODUCT
========================
*/


$delete = $conn->prepare(

"
DELETE FROM products

WHERE id = ?

"

);


$delete->execute([$id]);






/*
========================
DELETE IMAGE IF EXISTS
========================
*/


if(!empty($product['image'])){


$imagePath = __DIR__ . "/../../uploads/products/" . $product['image'];



if(file_exists($imagePath)){


unlink($imagePath);


}


}




header("Location:index.php?deleted=1");

exit();




}catch(PDOException $e){



die(

"Delete Error: " . $e->getMessage()

);


}