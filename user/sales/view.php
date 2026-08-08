<?php

error_reporting(E_ALL);
ini_set('display_errors',1);


if(session_status()===PHP_SESSION_NONE){
    session_start();
}


require_once "../../config/config.php";
require_once "../../includes/user_check.php";


$user_id=$_SESSION['user_id'];



if(!isset($_GET['id'])){

    header("Location:index.php");
    exit();

}


$id=(int)$_GET['id'];




// GET SALE

$stmt=$conn->prepare("

SELECT

sales.*,

customers.customer_name,
customers.phone,
customers.email,
customers.address


FROM sales


LEFT JOIN customers

ON sales.customer_id=customers.id


WHERE sales.id=? AND sales.user_id=?


");


$stmt->execute([$id,$user_id]);


$sale=$stmt->fetch(PDO::FETCH_ASSOC);



if(!$sale){

    die("Sale not found");

}





// GET ITEMS

$item=$conn->prepare("

SELECT

sale_items.*,

products.product_name,
products.product_code


FROM sale_items


LEFT JOIN products

ON sale_items.product_id=products.id


WHERE sale_items.sale_id=?


");


$item->execute([$id]);


$items=$item->fetchAll(PDO::FETCH_ASSOC);




include "../../includes/header.php";
include "../../includes/user_sidebar.php";


?>



<div class="container-fluid py-4">



<div class="d-flex justify-content-between mb-4">


<h2 class="fw-bold">

<i class="bi bi-receipt"></i>

Invoice Details

</h2>


<div>


<a href="index.php" class="btn btn-secondary">

← Back

</a>


<button onclick="window.print()" class="btn btn-dark">

<i class="bi bi-printer"></i>

Print

</button>


</div>


</div>







<div class="card shadow">


<div class="card-body">



<div class="row mb-4">


<div class="col-md-6">


<h4 class="fw-bold">

SANO TECH SHOP

</h4>


<p>

CCTV • Security • Technology Solutions

</p>


</div>




<div class="col-md-6 text-end">


<h5>

Invoice:

<?= htmlspecialchars($sale['invoice_no']); ?>

</h5>


<p>

Date:

<?= date("d M Y",strtotime($sale['sale_date'])); ?>

</p>


</div>


</div>





<hr>




<h5 class="fw-bold">

Customer Information

</h5>


<p>

<strong>Name:</strong>

<?= htmlspecialchars($sale['customer_name']); ?>

<br>


<strong>Phone:</strong>

<?= htmlspecialchars($sale['phone']); ?>


<br>


<strong>Email:</strong>

<?= htmlspecialchars($sale['email']); ?>


<br>


<strong>Address:</strong>

<?= htmlspecialchars($sale['address']); ?>


</p>





<hr>





<table class="table table-bordered">


<thead class="table-dark">


<tr>

<th>#</th>

<th>Product</th>

<th>Quantity</th>

<th>Price</th>

<th>Subtotal</th>

</tr>


</thead>



<tbody>



<?php foreach($items as $i=>$row): ?>


<tr>


<td>

<?= $i+1; ?>

</td>



<td>

<?= htmlspecialchars($row['product_name']); ?>

<br>

<small>

<?= htmlspecialchars($row['product_code']); ?>

</small>


</td>



<td>

<?= $row['quantity']; ?>

</td>




<td>

<?= number_format($row['price'],0); ?>

Frw

</td>




<td>

<?= number_format($row['subtotal'],0); ?>

Frw

</td>



</tr>



<?php endforeach; ?>



</tbody>



</table>







<div class="row justify-content-end">


<div class="col-md-4">


<table class="table">


<tr>

<th>Total</th>

<td>

<?= number_format($sale['total'],0); ?>

Frw

</td>

</tr>



<tr>

<th>Paid</th>

<td class="text-success">

<?= number_format($sale['paid'],0); ?>

Frw

</td>

</tr>




<tr>

<th>Balance</th>

<td class="text-danger">

<?= number_format($sale['balance'],0); ?>

Frw

</td>

</tr>



<tr>

<th>Status</th>

<td>


<?php if($sale['status']=="Paid"): ?>


<span class="badge bg-success">

Paid

</span>


<?php elseif($sale['status']=="Partial"): ?>


<span class="badge bg-warning">

Partial

</span>


<?php else: ?>


<span class="badge bg-danger">

Unpaid

</span>


<?php endif; ?>


</td>

</tr>



</table>


</div>


</div>




</div>


</div>


</div>



<?php include "../../includes/footer.php"; ?>