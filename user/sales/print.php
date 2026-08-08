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




// SALE DATA

$stmt=$conn->prepare("

SELECT

sales.*,

customers.customer_name,
customers.phone,
customers.email,
customers.address


FROM sales


LEFT JOIN customers

ON sales.customer_id = customers.id


WHERE sales.id=? AND sales.user_id=?


");


$stmt->execute([$id,$user_id]);


$sale=$stmt->fetch(PDO::FETCH_ASSOC);



if(!$sale){

    die("Invoice not found");

}





// ITEMS

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



?>



<!DOCTYPE html>

<html>

<head>

<title>
Invoice <?= $sale['invoice_no']; ?>
</title>


<style>


body{

font-family:Arial, sans-serif;

background:#eee;

padding:30px;

}


.invoice{

background:white;

max-width:900px;

margin:auto;

padding:40px;

}



.header{

display:flex;

justify-content:space-between;

}



.logo{

font-size:28px;

font-weight:bold;

color:#0d6efd;

}



table{

width:100%;

border-collapse:collapse;

margin-top:30px;

}



th{

background:#111827;

color:white;

}



th,td{

padding:12px;

border:1px solid #ddd;

}



.total{

width:350px;

margin-left:auto;

margin-top:20px;

}



.footer{

margin-top:50px;

text-align:center;

color:#666;

}




button{

padding:10px 20px;

background:#0d6efd;

color:white;

border:0;

cursor:pointer;

margin-bottom:20px;

}



@media print{

button{

display:none;

}


body{

background:white;

padding:0;

}

}


</style>



</head>



<body>



<button onclick="window.print()">

🖨 Print Invoice

</button>




<div class="invoice">



<div class="header">


<div>


<div class="logo">

SANO TECH SHOP

</div>


<p>

CCTV | Security | Technology Solutions

</p>


</div>




<div>


<h3>

INVOICE

</h3>


<p>

No:

<?= $sale['invoice_no']; ?>

</p>


<p>

Date:

<?= date("d M Y",strtotime($sale['sale_date'])); ?>

</p>


</div>



</div>



<hr>




<h3>

Bill To:

</h3>


<p>

<b>Name:</b>

<?= htmlspecialchars($sale['customer_name']); ?>

<br>


<b>Phone:</b>

<?= htmlspecialchars($sale['phone']); ?>

<br>


<b>Email:</b>

<?= htmlspecialchars($sale['email']); ?>

<br>


<b>Address:</b>

<?= htmlspecialchars($sale['address']); ?>


</p>





<table>


<tr>

<th>#</th>

<th>Product</th>

<th>Qty</th>

<th>Price</th>

<th>Total</th>


</tr>




<?php foreach($items as $i=>$row): ?>


<tr>


<td>

<?= $i+1; ?>

</td>


<td>

<?= htmlspecialchars($row['product_name']); ?>

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



</table>






<div class="total">


<table>


<tr>

<th>Total</th>

<td>

<?= number_format($sale['total'],0); ?>

Frw

</td>


</tr>



<tr>

<th>Paid</th>

<td>

<?= number_format($sale['paid'],0); ?>

Frw

</td>


</tr>



<tr>

<th>Balance</th>

<td>

<?= number_format($sale['balance'],0); ?>

Frw

</td>


</tr>



</table>


</div>






<div class="footer">


<p>

Thank you for choosing SANO TECH SHOP

</p>


<p>

Reliable Technology Solutions

</p>


</div>



</div>



</body>

</html>