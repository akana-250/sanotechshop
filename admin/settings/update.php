<?php

error_reporting(E_ALL);
ini_set('display_errors',1);


if(session_status() === PHP_SESSION_NONE){

    session_start();

}


require_once "../../config/config.php";

require_once "../../includes/admin_check.php";





if(isset($_POST['update'])){


$company_name = trim($_POST['company_name']);

$phone = trim($_POST['phone']);

$email = trim($_POST['email']);

$address = trim($_POST['address']);

$currency = trim($_POST['currency']);





/* GET CURRENT SETTINGS */


$check = $conn->prepare("

SELECT *

FROM settings

LIMIT 1

");


$check->execute();


$old = $check->fetch(PDO::FETCH_ASSOC);



$logo = $old['logo'] ?? null;







/* LOGO UPLOAD */


if(isset($_FILES['logo']) && $_FILES['logo']['error'] == 0){



$allowed = [

'image/jpeg',

'image/png',

'image/webp'

];



if(in_array($_FILES['logo']['type'],$allowed)){



$folder = "../../assets/img/";



if(!is_dir($folder)){

mkdir($folder,0777,true);

}



$logo = time()."_".basename($_FILES['logo']['name']);



move_uploaded_file(

$_FILES['logo']['tmp_name'],

$folder.$logo

);



}



}








if($old){



$stmt = $conn->prepare("

UPDATE settings SET

company_name=?,

phone=?,

email=?,

address=?,

logo=?,

currency=?

WHERE id=?

");



$stmt->execute([

$company_name,

$phone,

$email,

$address,

$logo,

$currency,

$old['id']

]);





}else{



$stmt=$conn->prepare("

INSERT INTO settings

(

company_name,

phone,

email,

address,

logo,

currency

)

VALUES(?,?,?,?,?,?)

");



$stmt->execute([

$company_name,

$phone,

$email,

$address,

$logo,

$currency

]);



}





$_SESSION['success']="Settings updated successfully";


header("Location:index.php");

exit();



}

?>