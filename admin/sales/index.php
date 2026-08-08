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





$page_title = "Sales Management";






try{


/*
|--------------------------------------------------------------------------
| GET SALES
|--------------------------------------------------------------------------
*/


$stmt = $conn->query("

SELECT


sales.*,


customers.customer_name



FROM sales



LEFT JOIN customers


ON customers.id = sales.customer_id



ORDER BY sales.id DESC



");



$sales = $stmt->fetchAll(PDO::FETCH_ASSOC);






}catch(Exception $e){



$_SESSION['error'] = $e->getMessage();


$sales = [];



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


<i class="fa-solid fa-cart-shopping text-primary"></i>


Sales Management


</h2>



<p class="text-muted">


Manage invoices and customer sales


</p>


</div>






<div class="d-flex gap-2">



<a href="add.php"

class="btn btn-primary">


<i class="fa-solid fa-plus"></i>


New Sale


</a>







<a href="reports.php"

class="btn btn-success">


<i class="fa-solid fa-chart-line"></i>


Reports


</a>





</div>



</div>








<!-- ===========================
     SALES TABLE
=========================== -->


<div class="card shadow-sm">


<div class="card-body">


<div class="table-responsive">



<table class="table table-hover align-middle">



<thead class="table-light">


<tr>


<th>#</th>

<th>Invoice</th>

<th>Customer</th>

<th>Total</th>

<th>Paid</th>

<th>Balance</th>

<th>Payment</th>

<th>Status</th>

<th>Date</th>

<th>Action</th>


</tr>


</thead>



<tbody>

<?php if(!empty($sales)): ?>



<?php foreach($sales as $key=>$sale): ?>


<tr>



<td>

<?= $key + 1; ?>

</td>





<td>


<?= htmlspecialchars($sale['invoice_no']); ?>


</td>





<td>


<?= htmlspecialchars($sale['customer_name'] ?? 'Walk In'); ?>


</td>





<td>


<?= number_format((float)$sale['total'],2); ?>


</td>





<td>


<?= number_format((float)$sale['paid'],2); ?>


</td>





<td>


<?= number_format((float)$sale['balance'],2); ?>


</td>





<td>


<?= htmlspecialchars($sale['payment_method']); ?>


</td>





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







<td>


<?= htmlspecialchars($sale['sale_date']); ?>


</td>







<td>



<div class="btn-group">



<a href="view.php?id=<?= $sale['id']; ?>"

class="btn btn-sm btn-info">


<i class="fa-solid fa-eye"></i>


</a>







<a href="invoice.php?id=<?= $sale['id']; ?>"

class="btn btn-sm btn-primary">


<i class="fa-solid fa-file-invoice"></i>


</a>







<a href="receipt.php?id=<?= $sale['id']; ?>"

class="btn btn-sm btn-success">


<i class="fa-solid fa-receipt"></i>


</a>







<a href="print.php?id=<?= $sale['id']; ?>"

class="btn btn-sm btn-dark">


<i class="fa-solid fa-print"></i>


</a>






<a href="delete.php?id=<?= $sale['id']; ?>"

class="btn btn-sm btn-danger"

onclick="return confirm('Delete this sale?')">


<i class="fa-solid fa-trash"></i>


</a>




</div>



</td>




</tr>



<?php endforeach; ?>



<?php else: ?>


<tr>


<td colspan="10" class="text-center">


No sales found


</td>


</tr>



<?php endif; ?>



</tbody>



</table>



</div>


</div>


</div>







</div>

</main>







<?php


require_once __DIR__ . "/../../includes/admin_footer.php";


ob_end_flush();


?>