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




$page_title = "Sales Return";





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
| GET SALE INFORMATION
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


    throw new Exception("Sale not found");


}







/*
|--------------------------------------------------------------------------
| GET SOLD PRODUCTS
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


<i class="fa-solid fa-rotate-left text-danger"></i>


Sales Return


</h2>



<p class="text-muted">


Return products from invoice


</p>


</div>




<a href="view.php?id=<?= $sale['id']; ?>"

class="btn btn-secondary">


<i class="fa-solid fa-arrow-left"></i>


Back


</a>



</div>








<!-- ===========================
     SALE INFO
=========================== -->


<div class="card shadow-sm mb-4">


<div class="card-body">


<div class="row">



<div class="col-md-4">


<strong>

Invoice:

</strong>


<?= htmlspecialchars($sale['invoice_no']); ?>


</div>





<div class="col-md-4">


<strong>

Customer:

</strong>


<?= htmlspecialchars($sale['customer_name'] ?? 'Walk In Customer'); ?>


</div>





<div class="col-md-4">


<strong>

Date:

</strong>


<?= htmlspecialchars($sale['sale_date']); ?>


</div>



</div>


</div>


</div>








<!-- ===========================
     RETURN FORM
=========================== -->


<div class="card shadow-sm">


<div class="card-body">



<form action="process_return.php" method="POST">



<input type="hidden"

name="sale_id"

value="<?= $sale['id']; ?>">







<div class="table-responsive">



<table class="table table-bordered align-middle">



<thead class="table-light">


<tr>


<th>#</th>

<th>Product</th>

<th>Sold Qty</th>

<th>Return Qty</th>

<th>Reason</th>


</tr>


</thead>




<tbody>



<?php foreach($items as $key=>$item): ?>


<tr>



<td>


<?= $key+1; ?>


</td>





<td>


<strong>

<?= htmlspecialchars($item['product_name']); ?>

</strong>


<br>


<small>


<?= htmlspecialchars($item['product_code']); ?>


</small>


<input type="hidden"

name="product_id[]"

value="<?= $item['product_id']; ?>">


</td>





<td>


<?= number_format((int)$item['quantity']); ?>


</td>





<td>


<input type="number"

name="return_qty[]"

class="form-control"

min="0"

max="<?= $item['quantity']; ?>"

value="0">


</td>





<td>


<input type="text"

name="reason[]"

class="form-control"

placeholder="Reason">


</td>



</tr>



<?php endforeach; ?>



</tbody>



</table>



</div>

<div class="d-flex justify-content-end gap-2 mt-4">



<a href="view.php?id=<?= $sale['id']; ?>"

class="btn btn-secondary">


Cancel


</a>






<button type="submit"

class="btn btn-danger">


<i class="fa-solid fa-rotate-left"></i>


Process Return


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