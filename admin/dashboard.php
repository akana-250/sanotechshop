<?php

declare(strict_types=1);

ob_start();

error_reporting(E_ALL);
ini_set('display_errors','1');


if(session_status() === PHP_SESSION_NONE){
    session_start();
}


require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../includes/admin_check.php";
require_once __DIR__ . "/../includes/db.php";


$page_title = "Dashboard";



/*
====================================================
STATISTICS
====================================================
*/


try{


$total_products = $conn->query(
    "SELECT COUNT(*) FROM products"
)->fetchColumn();



$total_customers = $conn->query(
    "SELECT COUNT(*) FROM customers"
)->fetchColumn();



$total_suppliers = $conn->query(
    "SELECT COUNT(*) FROM suppliers"
)->fetchColumn();



$total_sales = $conn->query(
    "SELECT COUNT(*) FROM sales"
)->fetchColumn();



$low_stock = $conn->query(

"
SELECT COUNT(*)

FROM products

WHERE quantity <= minimum_stock

"

)->fetchColumn();



$recent_products = $conn->query(

"
SELECT *

FROM products

ORDER BY id DESC

LIMIT 5

"

)->fetchAll(PDO::FETCH_ASSOC);



}catch(PDOException $e){


$total_products = 0;
$total_customers = 0;
$total_suppliers = 0;
$total_sales = 0;
$low_stock = 0;
$recent_products = [];


}





require_once __DIR__ . "/../includes/admin_header.php";

require_once __DIR__ . "/../includes/admin_sidebar.php";


?>



<main>



<div class="container-fluid">



<!-- PAGE TITLE -->

<div class="mb-4">


<h2 class="fw-bold">

<i class="fa-solid fa-gauge text-primary"></i>

Dashboard

</h2>


<p class="text-muted">

Welcome to SANO TECH SHOP ERP Management System

</p>


</div>







<!-- STAT CARDS -->

<div class="row g-4">



<div class="col-xl-3 col-md-6">


<div class="card shadow-sm p-3">


<div class="d-flex justify-content-between">


<div>

<h6 class="text-muted">

Products

</h6>


<h2>

<?= $total_products; ?>

</h2>


</div>



<i class="fa-solid fa-box fa-2x text-primary"></i>


</div>


</div>


</div>







<div class="col-xl-3 col-md-6">


<div class="card shadow-sm p-3">


<div class="d-flex justify-content-between">


<div>

<h6 class="text-muted">

Customers

</h6>


<h2>

<?= $total_customers; ?>

</h2>


</div>



<i class="fa-solid fa-users fa-2x text-success"></i>


</div>


</div>


</div>








<div class="col-xl-3 col-md-6">


<div class="card shadow-sm p-3">


<div class="d-flex justify-content-between">


<div>

<h6 class="text-muted">

Suppliers

</h6>


<h2>

<?= $total_suppliers; ?>

</h2>


</div>



<i class="fa-solid fa-truck fa-2x text-warning"></i>


</div>


</div>


</div>








<div class="col-xl-3 col-md-6">


<div class="card shadow-sm p-3">


<div class="d-flex justify-content-between">


<div>

<h6 class="text-muted">

Low Stock

</h6>


<h2>

<?= $low_stock; ?>

</h2>


</div>



<i class="fa-solid fa-triangle-exclamation fa-2x text-danger"></i>


</div>


</div>


</div>



</div>







<!-- QUICK ACTIONS -->


<div class="card shadow-sm mt-4">


<div class="card-body">


<h5 class="fw-bold mb-3">

Quick Actions

</h5>



<div class="row g-3">



<div class="col-md-3">


<a href="<?= BASE_URL; ?>/admin/products/add_product.php"

class="btn btn-primary w-100">


<i class="fa-solid fa-plus"></i>

Add Product


</a>


</div>





<div class="col-md-3">


<a href="<?= BASE_URL; ?>/admin/sales/index.php"

class="btn btn-success w-100">


<i class="fa-solid fa-cart-shopping"></i>

New Sale


</a>


</div>





<div class="col-md-3">


<a href="<?= BASE_URL; ?>/admin/customers/index.php"

class="btn btn-info w-100 text-white">


<i class="fa-solid fa-user-plus"></i>

Customers


</a>


</div>





<div class="col-md-3">


<a href="<?= BASE_URL; ?>/admin/reports.php"

class="btn btn-dark w-100">


<i class="fa-solid fa-chart-line"></i>

Reports


</a>


</div>




</div>


</div>


</div>








<!-- RECENT PRODUCTS -->


<div class="card shadow-sm mt-4">


<div class="card-header bg-white">


<h5 class="mb-0">

Recent Products

</h5>


</div>



<div class="card-body p-0">



<div class="table-responsive">


<table class="table mb-0">


<thead class="table-dark">

<tr>

<th>
Product
</th>


<th>
Price
</th>


<th>
Stock
</th>


<th>
Status
</th>


</tr>

</thead>



<tbody>


<?php foreach($recent_products as $product): ?>


<tr>


<td>

<?= htmlspecialchars($product['product_name']); ?>

</td>



<td>

<?= number_format((float)$product['selling_price'],2); ?>

<?= CURRENCY; ?>

</td>




<td>

<?= (int)$product['quantity']; ?>

</td>




<td>


<?php if($product['quantity'] <= $product['minimum_stock']): ?>


<span class="badge bg-danger">

Low Stock

</span>


<?php else: ?>


<span class="badge bg-success">

Available

</span>


<?php endif; ?>


</td>



</tr>


<?php endforeach; ?>


</tbody>


</table>


</div>


</div>


</div>




</div>


</main>




<?php

require_once __DIR__ . "/../includes/admin_footer.php";

?>