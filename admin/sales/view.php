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




$page_title = "View Sale";





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
| SALE INFORMATION
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
| PRODUCTS SOLD
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



$_SESSION['error'] = $e->getMessage();


header("Location: index.php");

exit;


}






require_once __DIR__ . "/../../includes/admin_header.php";

require_once __DIR__ . "/../../includes/admin_sidebar.php";

?>


<main>


<div class="container-fluid">
<!-- ===========================
     PAGE HEADER
=========================== -->


<div class="d-flex justify-content-between align-items-center mb-4">


<div>


<h2 class="fw-bold">


<i class="fa-solid fa-file-invoice text-primary"></i>


Sale Details


</h2>



<p class="text-muted">


View complete sale information


</p>


</div>





<div class="d-flex gap-2">


<a href="invoice.php?id=<?= $sale['id']; ?>"

class="btn btn-primary">


<i class="fa-solid fa-file-invoice"></i>


Invoice


</a>





<a href="print.php?id=<?= $sale['id']; ?>"

class="btn btn-dark">


<i class="fa-solid fa-print"></i>


Print


</a>





<?php if((float)$sale['balance'] > 0): ?>


<a href="payment.php?id=<?= $sale['id']; ?>"

class="btn btn-success">


<i class="fa-solid fa-money-bill"></i>


Payment


</a>


<?php endif; ?>





<a href="index.php"

class="btn btn-secondary">


<i class="fa-solid fa-arrow-left"></i>


Back


</a>



</div>


</div>






<!-- ===========================
     SALE INFORMATION CARD
=========================== -->


<div class="card shadow-sm mb-4">


<div class="card-body">



<div class="row">





<div class="col-md-4">


<h6 class="text-muted">

Invoice Number

</h6>


<h5>


<?= htmlspecialchars($sale['invoice_no']); ?>


</h5>


</div>





<div class="col-md-4">


<h6 class="text-muted">

Sale Date

</h6>


<h5>


<?= htmlspecialchars($sale['sale_date']); ?>


</h5>


</div>





<div class="col-md-4">


<h6 class="text-muted">

Status

</h6>



<?php if($sale['status']=="Paid"): ?>


<span class="badge bg-success fs-6">

Paid

</span>



<?php elseif($sale['status']=="Partial"): ?>


<span class="badge bg-warning fs-6">

Partial

</span>



<?php else: ?>


<span class="badge bg-danger fs-6">

Unpaid

</span>



<?php endif; ?>



</div>




</div>



</div>


</div>
<!-- ===========================
     CUSTOMER INFORMATION
=========================== -->


<div class="row mb-4">


<div class="col-md-6">



<div class="card shadow-sm h-100">


<div class="card-body">



<h5 class="fw-bold mb-3">


<i class="fa-solid fa-user text-primary"></i>


Customer Information


</h5>



<p class="mb-2">


<strong>Name:</strong>


<?= htmlspecialchars($sale['customer_name'] ?? 'Walk In Customer'); ?>


</p>





<?php if(!empty($sale['phone'])): ?>


<p class="mb-2">


<strong>Phone:</strong>


<?= htmlspecialchars($sale['phone']); ?>


</p>


<?php endif; ?>






<?php if(!empty($sale['email'])): ?>


<p class="mb-2">


<strong>Email:</strong>


<?= htmlspecialchars($sale['email']); ?>


</p>


<?php endif; ?>





<?php if(!empty($sale['address'])): ?>


<p class="mb-0">


<strong>Address:</strong>


<?= htmlspecialchars($sale['address']); ?>


</p>


<?php endif; ?>



</div>


</div>



</div>






<div class="col-md-6">



<div class="card shadow-sm h-100">


<div class="card-body">



<h5 class="fw-bold mb-3">


<i class="fa-solid fa-money-check-dollar text-success"></i>


Payment Information


</h5>





<p class="mb-2">


<strong>Total:</strong>


<?= number_format((float)$sale['total'],2); ?>


</p>





<p class="mb-2 text-success">


<strong>Paid:</strong>


<?= number_format((float)$sale['paid'],2); ?>


</p>





<p class="mb-2 text-danger">


<strong>Balance:</strong>


<?= number_format((float)$sale['balance'],2); ?>


</p>





<p class="mb-0">


<strong>Method:</strong>


<?= htmlspecialchars($sale['payment_method']); ?>


</p>



</div>


</div>



</div>



</div>








<!-- ===========================
     PRODUCTS SOLD
=========================== -->


<div class="card shadow-sm">


<div class="card-header">


<h5 class="mb-0">


<i class="fa-solid fa-box"></i>


Products Sold


</h5>


</div>





<div class="card-body p-0">


<div class="table-responsive">



<table class="table table-hover mb-0">



<thead class="table-light">


<tr>


<th>#</th>

<th>Product</th>

<th>Code</th>

<th>Quantity</th>

<th>Price</th>

<th>Subtotal</th>


</tr>


</thead>




<tbody>



<?php foreach($items as $key=>$item): ?>


<tr>


<td>


<?= $key+1; ?>


</td>




<td>


<?= htmlspecialchars($item['product_name']); ?>


</td>





<td>


<?= htmlspecialchars($item['product_code']); ?>


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


</div>


</div>


</div>
<!-- ===========================
     SUMMARY FOOTER
=========================== -->


<div class="card shadow-sm mt-4">


<div class="card-body">


<div class="row">



<div class="col-md-6">


<h6 class="fw-bold">

Notes

</h6>


<p class="text-muted mb-0">


Thank you for choosing SANO TECH SHOP.


</p>


</div>





<div class="col-md-6 text-md-end">



<h5 class="fw-bold">


Grand Total:


<?= number_format((float)$sale['total'],2); ?>


</h5>




<h6 class="text-success">


Paid:


<?= number_format((float)$sale['paid'],2); ?>


</h6>





<h6 class="text-danger">


Balance:


<?= number_format((float)$sale['balance'],2); ?>


</h6>



</div>



</div>



</div>


</div>






</div>

</main>






<?php


require_once __DIR__ . "/../../includes/admin_footer.php";


ob_end_flush();


?>