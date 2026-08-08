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



$id = (int)($_GET['id'] ?? 0);



if($id <= 0){

    header("Location:index.php");
    exit();

}



try{



/*
========================
CHECK CATEGORY
========================
*/


$check = $conn->prepare("

SELECT *

FROM categories

WHERE id = ?

LIMIT 1

");


$check->execute([$id]);


$category = $check->fetch(PDO::FETCH_ASSOC);



if(!$category){


header("Location:index.php");

exit();


}





/*
========================
CHECK PRODUCTS
========================
*/


$productCheck = $conn->prepare("

SELECT COUNT(*)

FROM products

WHERE category_id = ?

");


$productCheck->execute([$id]);


$totalProducts = (int)$productCheck->fetchColumn();





if($totalProducts > 0){



$_SESSION['error'] = 

"Cannot delete this category. It contains products.";



header("Location:index.php");

exit();



}






/*
========================
DELETE CATEGORY
========================
*/


$delete = $conn->prepare("

DELETE FROM categories

WHERE id = ?

");


$delete->execute([$id]);




$_SESSION['success'] =

"Category deleted successfully";




header("Location:index.php");

exit();





}catch(PDOException $e){



$_SESSION['error'] = 

"Delete failed: ".$e->getMessage();



header("Location:index.php");

exit();



}