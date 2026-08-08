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





$page_title = "Sales Reports";







try{



/*
|--------------------------------------------------------------------------
| SALES SUMMARY
|--------------------------------------------------------------------------
*/


$total_sales = $conn->query("

SELECT COALESCE(SUM(total),0)

FROM sales

")->fetchColumn();





$total_paid = $conn->query("

SELECT COALESCE(SUM(paid),0)

FROM sales

")->fetchColumn();






$total_balance = $conn->query("

SELECT COALESCE(SUM(balance),0)

FROM sales

")->fetchColumn();







$total_orders = $conn->query("

SELECT COUNT(*)

FROM sales

")->fetchColumn();








/*
|--------------------------------------------------------------------------
| SALES DATA
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
     REPORT HEADER
=========================== -->


<div class="d-flex justify-content-between align-items-center mb-4">


<div>


<h2 class="fw-bold">


<i class="fa-solid fa-chart-line text-success"></i>


Sales Reports


</h2>



<p class="text-muted">


Analyze sales performance


</p>


</div>







<div class="d-flex gap-2">



<a href="export_excel.php"

class="btn btn-success">


<i class="fa-solid fa-file-excel"></i>


Excel


</a>






<a href="export_pdf.php"

class="btn btn-danger">


<i class="fa-solid fa-file-pdf"></i>


PDF


</a>




</div>



</div>








<!-- ===========================
     SUMMARY CARDS
=========================== -->


<div class="row g-3 mb-4">





<div class="col-md-3">


<div class="card shadow-sm">


<div class="card-body">



<h6 class="text-muted">

Total Sales

</h6>



<h3 class="fw-bold">


<?= number_format((float)$total_sales,2); ?>


</h3>



</div>


</div>


</div>







<div class="col-md-3">


<div class="card shadow-sm">


<div class="card-body">



<h6 class="text-muted">

Paid

</h6>



<h3 class="fw-bold text-success">


<?= number_format((float)$total_paid,2); ?>


</h3>



</div>


</div>


</div>







<div class="col-md-3">


<div class="card shadow-sm">


<div class="card-body">



<h6 class="text-muted">

Balance

</h6>



<h3 class="fw-bold text-danger">


<?= number_format((float)$total_balance,2); ?>


</h3>



</div>


</div>


</div>







<div class="col-md-3">


<div class="card shadow-sm">


<div class="card-body">



<h6 class="text-muted">

Orders

</h6>



<h3 class="fw-bold">


<?= number_format((int)$total_orders); ?>


</h3>



</div>


</div>


</div>



</div>

<!-- ===========================
     SALES REPORT TABLE
=========================== -->


<div class="card shadow-sm">


<div class="card-header bg-white">


<h5 class="mb-0">


<i class="fa-solid fa-receipt"></i>


Sales Transactions


</h5>


</div>





<div class="card-body p-0">



<div class="table-responsive">



<table class="table table-hover mb-0">



<thead class="table-light">


<tr>


<th>#</th>

<th>Invoice</th>

<th>Customer</th>

<th>Total</th>

<th>Paid</th>

<th>Balance</th>

<th>Status</th>

<th>Date</th>


</tr>


</thead>





<tbody>



<?php if(!empty($sales)): ?>



<?php foreach($sales as $key=>$sale): ?>


<tr>



<td>

<?= $key+1; ?>

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


<span class="badge bg-primary">


<?= htmlspecialchars($sale['status']); ?>


</span>


</td>





<td>


<?= htmlspecialchars($sale['sale_date']); ?>


</td>




</tr>



<?php endforeach; ?>



<?php else: ?>


<tr>


<td colspan="8" class="text-center">


No sales records found


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