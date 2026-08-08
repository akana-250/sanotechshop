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



/*
|--------------------------------------------------------------------------
| SALE DETAILS
|--------------------------------------------------------------------------
*/


$stmt = $conn->prepare("

SELECT


sales.*,


customers.customer_name,

customers.phone,

customers.email,

customers.address


FROM sales


LEFT JOIN customers

ON customers.id = sales.customer_id


WHERE sales.id = ?



");



$stmt->execute([$sale_id]);



$sale = $stmt->fetch(PDO::FETCH_ASSOC);






if(!$sale){


    throw new Exception("Sale not found");


}






/*
|--------------------------------------------------------------------------
| SALE ITEMS
|--------------------------------------------------------------------------
*/


$item_stmt = $conn->prepare("

SELECT


sale_items.*,


products.product_name,

products.product_code


FROM sale_items


LEFT JOIN products

ON products.id = sale_items.product_id


WHERE sale_items.sale_id = ?



");



$item_stmt->execute([$sale_id]);



$items = $item_stmt->fetchAll(PDO::FETCH_ASSOC);





}catch(Exception $e){



header("Location: index.php");

exit;


}


?><!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>

Invoice <?= htmlspecialchars($sale['invoice_no']); ?>

</title>




<style>


body{

    font-family: Arial, sans-serif;

    font-size:14px;

    color:#333;

}



.invoice-box{

    width:800px;

    margin:20px auto;

    padding:30px;

    border:1px solid #ddd;

}



.header{

    display:flex;

    justify-content:space-between;

    margin-bottom:20px;

}



.company h2{

    margin:0;

    color:#0d6efd;

}



.text-right{

    text-align:right;

}



hr{

    border:0;

    border-top:1px solid #ddd;

}



table{

    width:100%;

    border-collapse:collapse;

    margin-top:20px;

}



table th,

table td{

    border:1px solid #ddd;

    padding:8px;

}



table th{

    background:#f5f5f5;

}



.total-box{

    width:300px;

    margin-left:auto;

    margin-top:20px;

}



.total-box div{

    display:flex;

    justify-content:space-between;

    margin-bottom:8px;

}



.footer{

    text-align:center;

    margin-top:30px;

}



.print-btn{

    text-align:center;

    margin:20px;

}



@media print{


.print-btn{

    display:none;

}


.invoice-box{

    border:none;

    margin:0;

}



}



</style>


</head>




<body>


<div class="print-btn">


<button onclick="window.print()">


Print Invoice


</button>


<a href="invoice.php?id=<?= $sale['id']; ?>">


Back


</a>


</div>





<div class="invoice-box">



<div class="header">


<div class="company">


<h2>

SANO TECH SHOP

</h2>


<p>

ERP SYSTEM

</p>


</div>




<div class="text-right">


<strong>

INVOICE

</strong>


<br>


No:

<?= htmlspecialchars($sale['invoice_no']); ?>


<br>


Date:

<?= htmlspecialchars($sale['sale_date']); ?>


</div>



</div>





<hr>





<h4>

Customer Information

</h4>


<p>


<strong>

<?= htmlspecialchars($sale['customer_name'] ?? 'Walk In Customer'); ?>

</strong>


<br>


<?= htmlspecialchars($sale['phone'] ?? ''); ?>


<br>


<?= htmlspecialchars($sale['email'] ?? ''); ?>


</p><!-- ===========================
     PRODUCTS TABLE
=========================== -->


<table>


<thead>


<tr>


<th>#</th>

<th>Product</th>

<th>Qty</th>

<th>Price</th>

<th>Subtotal</th>


</tr>


</thead>



<tbody>



<?php foreach($items as $key => $item): ?>


<tr>


<td>


<?= $key + 1; ?>


</td>




<td>


<?= htmlspecialchars($item['product_name'] ?? '-'); ?>


</td>




<td>


<?= number_format((int)$item['quantity']); ?>


</td>




<td>


<?= number_format((float)$item['price'],2); ?>


</td>




<td>


<?= number_format((float)$item['subtotal'],2); ?>


</td>



</tr>



<?php endforeach; ?>



</tbody>



</table>






<!-- ===========================
     PAYMENT SUMMARY
=========================== -->


<div class="total-box">



<div>


<strong>

Total:

</strong>


<span>


<?= number_format((float)$sale['total'],2); ?>


</span>


</div>





<div>


<strong>

Paid:

</strong>


<span>


<?= number_format((float)$sale['paid'],2); ?>


</span>


</div>





<div>


<strong>

Balance:

</strong>


<span>


<?= number_format((float)$sale['balance'],2); ?>


</span>


</div>





<div>


<strong>

Payment:

</strong>


<span>


<?= htmlspecialchars($sale['payment_method']); ?>


</span>


</div>





<div>


<strong>

Status:

</strong>


<span>


<?= htmlspecialchars($sale['status']); ?>


</span>


</div>



</div><!-- ===========================
     FOOTER
=========================== -->


<div class="footer">


<hr>



<h4>

Thank You For Your Purchase

</h4>



<p>

SANO TECH SHOP ERP SYSTEM

</p>



<p>

Generated automatically

</p>



</div>




</div>





<script>


// Auto open print dialog

window.onload = function(){


    // Optional: remove comment if you want auto print

    // window.print();


};



</script>




</body>


</html>