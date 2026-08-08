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
CHECK SUPPLIER
========================
*/


$check = $conn->prepare("

SELECT id

FROM suppliers

WHERE id = ?

LIMIT 1

");


$check->execute([$id]);



if(!$check->fetch()){


$_SESSION['error'] = "Supplier not found";


header("Location:index.php");

exit();


}







/*
========================
DELETE
========================
*/


$delete = $conn->prepare("

DELETE FROM suppliers

WHERE id = ?

");


$delete->execute([$id]);





$_SESSION['success'] = "Supplier deleted successfully";



header("Location:index.php");

exit();





}catch(PDOException $e){



$_SESSION['error'] = "Delete failed: ".$e->getMessage();



header("Location:index.php");

exit();



}

?>