<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

if(session_status()===PHP_SESSION_NONE){
    session_start();
}

require_once "../../config/config.php";
require_once "../../includes/user_check.php";

$page_title="Quotation Details";

if(
    !isset($_GET['id']) ||
    !is_numeric($_GET['id'])
){
    header("Location:index.php");
    exit();
}

$id=(int)$_GET['id'];
$user_id=$_SESSION['user_id'];

$stmt=$conn->prepare("
SELECT

quotations.*,

products.product_name,
products.product_code,
products.barcode,
products.selling_price,
products.image,
products.description,

customers.customer_name,
customers.phone,
customers.email

FROM quotations

LEFT JOIN products
ON quotations.product_id=products.id

LEFT JOIN customers
ON quotations.customer_id=customers.id

WHERE quotations.id=?
AND quotations.user_id=?

LIMIT 1
");

$stmt->execute([$id,$user_id]);

$quotation=$stmt->fetch(PDO::FETCH_ASSOC);

if(!$quotation){

header("Location:index.php");
exit();

}

/* ===========================
   ACTIVITY LOG
=========================== */

try{

$log=$conn->prepare("
INSERT INTO activity_logs
(user_id,activity)
VALUES(?,?)
");

$log->execute([
$user_id,
"Viewed quotation #".$quotation['id']
]);

}catch(Exception $e){}

/* ===========================
   PRODUCT IMAGE
=========================== */

$image="../../assets/img/no-image.png";

if(
!empty($quotation['image']) &&
file_exists("../../uploads/products/".$quotation['image'])
){

$image="../../uploads/products/".$quotation['image'];

}

include "../../includes/header.php";
include "../../includes/user_sidebar.php";

?>

<div class="container-fluid py-4">

<div id="printArea">

<div class="row">

<div class="col-lg-5 mb-4">

<div class="card shadow border-0 rounded-4">

<img
src="<?= $image ?>"
class="img-fluid rounded-top"
style="height:420px;object-fit:cover;"
alt="<?= htmlspecialchars($quotation['product_name']) ?>">

<div class="card-body text-center">

<h5 class="fw-bold">

<?= htmlspecialchars($quotation['product_name']) ?>

</h5>

<p class="text-muted">

<?= htmlspecialchars($quotation['product_code']) ?>

</p>

</div>

</div>

</div>

<div class="col-lg-7">

<div class="card shadow border-0 rounded-4">

<div class="card-header bg-primary text-white">

<h4 class="mb-0">

<i class="bi bi-file-earmark-text"></i>

Quotation Details

</h4>

</div>

<div class="card-body">

<div class="row">

<div class="col-md-6 mb-3">

<label class="fw-bold">

Quotation ID

</label>

<p>

#<?= $quotation['id'] ?>

</p>

</div>

<div class="col-md-6 mb-3">

<label class="fw-bold">

Status

</label>

<p>

<?php

$status=strtolower($quotation['status']);

if($status=="approved"){

echo '<span class="badge bg-success fs-6">Approved</span>';

}elseif($status=="rejected"){

echo '<span class="badge bg-danger fs-6">Rejected</span>';

}else{

echo '<span class="badge bg-warning text-dark fs-6">Pending</span>';

}

?>

</p>

</div>

<div class="col-md-6 mb-3">

<label class="fw-bold">

Quantity

</label>

<p>

<?= (int)$quotation['quantity'] ?>

</p>

</div>

<div class="col-md-6 mb-3">

<label class="fw-bold">

Unit Price

</label>

<p class="fw-bold text-primary">

<?= number_format($quotation['selling_price'],0) ?>

Frw

</p>

</div>

<div class="col-md-6 mb-3">

<label class="fw-bold">

Total Amount

</label>

<h3 class="text-success">

<?= number_format($quotation['total_amount'],0) ?>

Frw

</h3>

</div>

<div class="col-md-6 mb-3">

<label class="fw-bold">

Barcode

</label>

<p>

<?= !empty($quotation['barcode']) ? htmlspecialchars($quotation['barcode']) : "-" ?>

</p>

</div><div class="col-md-6 mb-3">

<label class="fw-bold">

Request Date

</label>

<p>

<?= date("d M Y H:i",strtotime($quotation['created_at'])) ?>

</p>

</div>

<div class="col-12">

<hr>

<h5 class="fw-bold mb-3">

Customer Information

</h5>

<div class="row">

<div class="col-md-4">

<label class="fw-bold">

Customer

</label>

<p>

<?= !empty($quotation['customer_name']) ? htmlspecialchars($quotation['customer_name']) : "Walk-in Customer" ?>

</p>

</div>

<div class="col-md-4">

<label class="fw-bold">

Phone

</label>

<p>

<?= !empty($quotation['phone']) ? htmlspecialchars($quotation['phone']) : "-" ?>

</p>

</div>

<div class="col-md-4">

<label class="fw-bold">

Email

</label>

<p>

<?= !empty($quotation['email']) ? htmlspecialchars($quotation['email']) : "-" ?>

</p>

</div>

</div>

</div>

<div class="col-12 mt-4">

<hr>

<h5 class="fw-bold mb-3">

Product Description

</h5>

<div class="border rounded p-3 bg-light">

<?php

if(!empty($quotation['description'])){

echo nl2br(htmlspecialchars($quotation['description']));

}else{

echo "<span class='text-muted'>No description available.</span>";

}

?>

</div>

</div>

<?php if(isset($quotation['notes'])){ ?>

<div class="col-12 mt-4">

<h5 class="fw-bold">

Additional Notes

</h5>

<div class="border rounded p-3">

<?= !empty($quotation['notes']) ? nl2br(htmlspecialchars($quotation['notes'])) : "No notes." ?>

</div>

</div>

<?php } ?>

<hr class="mt-4">

<div class="d-flex flex-wrap gap-2">

<a
href="index.php"
class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

<button
type="button"
class="btn btn-primary"
onclick="printQuotation()">

<i class="bi bi-printer"></i>

Print

</button>

</div>

</div>

</div>

</div>

</div>

</div>

<?php include "../../includes/footer.php"; ?>

<script>

function printQuotation(){

let content=document.getElementById("printArea").innerHTML;

let win=window.open("","PRINT","width=900,height=700");

win.document.write(`

<!DOCTYPE html>

<html>

<head>

<title>SANO TECH SHOP - Quotation</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<style>

body{

padding:40px;
font-family:Arial,sans-serif;

}

.logo{

text-align:center;
margin-bottom:20px;

}

img{

max-width:250px;

}

.badge{

padding:8px 15px;

}

</style>

</head>

<body>

<div class="logo">

<h2>SANO TECH SHOP</h2>

<p>Quotation Details</p>

<hr>

</div>

${content}

<hr>

<p style="text-align:center">

Printed on:
${new Date().toLocaleString()}

</p>

</body>

</html>

`);

win.document.close();

win.focus();

win.print();

win.close();

}

</script>

<style>

.card{

transition:.3s;

}

.card:hover{

transform:translateY(-5px);

}

@media print{

.navbar,
.sidebar,
.btn,
footer{

display:none !important;

}

body{

background:#fff;

}

}

</style>