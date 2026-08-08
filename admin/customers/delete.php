<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once "../../config/config.php";
require_once "../../includes/admin_check.php";


if(!isset($_GET['id'])){

    header("Location: index.php");
    exit();

}


$id = $_GET['id'];


// Check customer exists

$check = $conn->prepare(
    "SELECT id FROM customers WHERE id = ?"
);

$check->execute([$id]);



if($check->rowCount() == 0){

    header("Location: index.php");
    exit();

}



// Delete customer

$delete = $conn->prepare(
    "DELETE FROM customers WHERE id = ?"
);


$delete->execute([$id]);



// Back to list

header("Location: index.php");
exit();


?>