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





$page_title = "Sales Receipt";





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
| GET SALE DATA
|--------------------------------------------------------------------------
*/


$stmt = $conn->prepare("

SELECT


sales.*,


customers.customer_name,

customers.phone


FROM sales


LEFT JOIN customers


ON customers.id = sales.customer_id


WHERE sales.id = ?



");



$stmt->execute([$sale_id]);



$sale = $stmt->fetch(PDO::FETCH_ASSOC);






if(!$sale){


    throw new Exception("Receipt not found");


}








/*
|--------------------------------------------------------------------------
| GET RECEIPT ITEMS
|--------------------------------------------------------------------------
*/


$item_stmt = $conn->prepare("

SELECT


sale_items.*,


products.product_name


FROM sale_items


LEFT JOIN products


ON products.id = sale_items.product_id


WHERE sale_items.sale_id = ?



");



$item_stmt->execute([$sale_id]);



$items = $item_stmt->fetchAll(PDO::FETCH_ASSOC);





}catch(Exception $e){



$_SESSION['error'] = $e->getMessage();



header("Location: index.php");

exit;


}


?><!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>

Receipt <?= htmlspecialchars($sale['invoice_no']); ?>

</title>



<style>


body{

    font-family: monospace;

    background:#f5f5f5;

}



.receipt{

    width:320px;

    margin:20px auto;

    background:white;

    padding:20px;

}



.text-center{

    text-align:center;

}



.line{

    border-top:1px dashed #333;

    margin:10px 0;

}



table{

    width:100%;

    border-collapse:collapse;

    font-size:13px;

}



td{

    padding:5px 0;

}



.total{

    font-weight:bold;

    font-size:16px;

}



.print-btn{

    text-align:center;

    margin:20px;

}



@media print{


.print-btn{

display:none;

}



body{

background:white;

}



.receipt{

margin:0 auto;

}



}



</style>



</head>



<body>




<div class="print-btn">


<button onclick="window.print()">

Print Receipt

</button>



<a href="view.php?id=<?= $sale['id']; ?>">


Back


</a>


</div>






<div class="receipt">



<div class="text-center">


<h3>

SANO TECH SHOP

</h3>



<p>

ERP SYSTEM

</p>



<p>

<?= htmlspecialchars($sale['invoice_no']); ?>

</p>



</div>





<div class="line"></div>






<p>


Customer:

<?= htmlspecialchars($sale['customer_name'] ?? 'Walk In'); ?>


<br>


Phone:

<?= htmlspecialchars($sale['phone'] ?? '-'); ?>


</p>





<div class="line"></div>

<table>


<tbody>



<?php foreach($items as $item): ?>


<tr>


<td>


<?= htmlspecialchars($item['product_name']); ?>


<br>


<?= $item['quantity']; ?> x


<?= number_format((float)$item['price'],2); ?>


</td>




<td style="text-align:right;">


<?= number_format((float)$item['subtotal'],2); ?>


</td>



</tr>



<?php endforeach; ?>



</tbody>



</table>






<div class="line"></div>






<table>



<tr>


<td>

Total

</td>


<td style="text-align:right;">


<?= number_format((float)$sale['total'],2); ?>


</td>


</tr>





<tr>


<td>

Paid

</td>


<td style="text-align:right;">


<?= number_format((float)$sale['paid'],2); ?>


</td>


</tr>





<tr class="total">


<td>

Balance

</td>


<td style="text-align:right;">


<?= number_format((float)$sale['balance'],2); ?>


</td>


</tr>




</table>







<div class="line"></div>





<div class="text-center">


<p>


Thank you for shopping with us!


</p>



<p>


SANO TECH SHOP


</p>


</div>






</div>





</body>


</html>