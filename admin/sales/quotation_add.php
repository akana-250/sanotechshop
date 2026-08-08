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





$page_title = "Add Quotation";





try{



/*
|--------------------------------------------------------------------------
| CUSTOMERS
|--------------------------------------------------------------------------
*/


$customer_stmt = $conn->query("

SELECT *

FROM customers

ORDER BY customer_name ASC


");



$customers = $customer_stmt->fetchAll(PDO::FETCH_ASSOC);







/*
|--------------------------------------------------------------------------
| PRODUCTS
|--------------------------------------------------------------------------
*/


$product_stmt = $conn->query("

SELECT *

FROM products

WHERE status='Active'

ORDER BY product_name ASC


");



$products = $product_stmt->fetchAll(PDO::FETCH_ASSOC);







}catch(Exception $e){



$_SESSION['error'] = $e->getMessage();


$customers = [];

$products = [];

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


<i class="fa-solid fa-file-circle-plus text-primary"></i>


Create Quotation


</h2>



<p class="text-muted">


Prepare quotation for customer


</p>


</div>





<a href="quotation.php"

class="btn btn-secondary">


<i class="fa-solid fa-arrow-left"></i>


Back


</a>



</div>








<!-- ===========================
     QUOTATION FORM
=========================== -->


<div class="card shadow-sm">


<div class="card-body">



<form action="quotation_create.php" method="POST">





<div class="row mb-4">



<div class="col-md-6">


<label class="form-label fw-bold">

Customer

</label>




<select name="customer_id"

class="form-select">



<option value="">Walk In Customer</option>




<?php foreach($customers as $customer): ?>


<option value="<?= $customer['id']; ?>">


<?= htmlspecialchars($customer['customer_name']); ?>


</option>



<?php endforeach; ?>



</select>



</div>







<div class="col-md-6">


<label class="form-label fw-bold">

Quotation Date

</label>



<input type="date"

name="quotation_date"

class="form-control"

value="<?= date('Y-m-d'); ?>">


</div>



</div>







<h5 class="fw-bold mb-3">


Products


</h5>






<div class="table-responsive">



<table class="table table-bordered">



<thead class="table-light">


<tr>


<th>Product</th>

<th>Price</th>

<th>Quantity</th>


</tr>


</thead>





<tbody>



<?php foreach($products as $product): ?>


<tr>



<td>


<?= htmlspecialchars($product['product_name']); ?>


<input type="hidden"

name="product_id[]"

value="<?= $product['id']; ?>">


</td>





<td>


<?= number_format((float)$product['selling_price'],2); ?>


<input type="hidden"

name="price[]"

value="<?= $product['selling_price']; ?>">


</td>





<td>


<input type="number"

name="quantity[]"

class="form-control"

min="0"

value="0">


</td>



</tr>



<?php endforeach; ?>



</tbody>



</table>



</div>

<div class="row mt-4">



<div class="col-md-6">


<label class="form-label fw-bold">

Notes

</label>



<textarea name="notes"

class="form-control"

rows="3"

placeholder="Quotation notes..."></textarea>


</div>






<div class="col-md-6">


<label class="form-label fw-bold">

Status

</label>



<select name="status"

class="form-select">



<option value="Pending">

Pending

</option>



<option value="Approved">

Approved

</option>



<option value="Rejected">

Rejected

</option>



</select>



</div>



</div>








<div class="d-flex justify-content-end gap-2 mt-4">



<a href="quotation.php"

class="btn btn-secondary">


Cancel


</a>






<button type="submit"

class="btn btn-primary">


<i class="fa-solid fa-save"></i>


Save Quotation


</button>



</div>





</form>



</div>


</div>







</div>

</main>






<?php


require_once __DIR__ . "/../../includes/admin_footer.php";


ob_end_flush();


?>