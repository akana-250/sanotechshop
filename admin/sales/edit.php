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




$page_title = "Edit Sale";





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

SELECT *

FROM sales

WHERE id = ?

");



$stmt->execute([$sale_id]);



$sale = $stmt->fetch(PDO::FETCH_ASSOC);






if(!$sale){


    throw new Exception("Sale not found");


}







/*
|--------------------------------------------------------------------------
| GET CUSTOMERS
|--------------------------------------------------------------------------
*/


$customer_stmt = $conn->query("

SELECT *

FROM customers

ORDER BY customer_name ASC

");



$customers = $customer_stmt->fetchAll(PDO::FETCH_ASSOC);






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


<i class="fa-solid fa-pen text-warning"></i>


Edit Sale


</h2>



<p class="text-muted">


Update sale information


</p>


</div>





<a href="view.php?id=<?= $sale['id']; ?>"

class="btn btn-secondary">


<i class="fa-solid fa-arrow-left"></i>


Back


</a>



</div>








<!-- ===========================
     EDIT FORM
=========================== -->


<div class="card shadow-sm">


<div class="card-body">



<form action="update_sale.php" method="POST">



<input type="hidden"

name="sale_id"

value="<?= $sale['id']; ?>">






<div class="row g-3">



<div class="col-md-6">


<label class="form-label fw-bold">

Customer

</label>



<select name="customer_id"

class="form-select">



<option value="">Walk In Customer</option>




<?php foreach($customers as $customer): ?>


<option value="<?= $customer['id']; ?>"

<?= ($customer['id']==$sale['customer_id']) ? 'selected' : ''; ?>


>


<?= htmlspecialchars($customer['customer_name']); ?>


</option>



<?php endforeach; ?>



</select>


</div>








<div class="col-md-6">


<label class="form-label fw-bold">

Invoice Number

</label>



<input type="text"

class="form-control"

value="<?= htmlspecialchars($sale['invoice_no']); ?>"

readonly>


</div>







<div class="col-md-4">


<label class="form-label fw-bold">

Total Amount

</label>



<input type="number"

name="total"

class="form-control"

value="<?= $sale['total']; ?>"

step="0.01"

required>


</div>







<div class="col-md-4">


<label class="form-label fw-bold">

Paid Amount

</label>



<input type="number"

name="paid"

class="form-control"

value="<?= $sale['paid']; ?>"

step="0.01"

required>


</div>







<div class="col-md-4">


<label class="form-label fw-bold">

Payment Method

</label>



<select name="payment_method"

class="form-select">


<option value="Cash"

<?= $sale['payment_method']=="Cash"?'selected':''; ?>

>

Cash

</option>



<option value="Mobile Money"

<?= $sale['payment_method']=="Mobile Money"?'selected':''; ?>

>

Mobile Money

</option>



<option value="Bank"

<?= $sale['payment_method']=="Bank"?'selected':''; ?>

>

Bank

</option>



</select>


</div>



<div class="col-md-4">


<label class="form-label fw-bold">

Status

</label>



<select name="status"

class="form-select">



<option value="Paid"

<?= $sale['status']=="Paid" ? 'selected':''; ?>

>

Paid

</option>



<option value="Partial"

<?= $sale['status']=="Partial" ? 'selected':''; ?>

>

Partial

</option>



<option value="Unpaid"

<?= $sale['status']=="Unpaid" ? 'selected':''; ?>

>

Unpaid

</option>



</select>


</div>






<div class="col-md-8">


<label class="form-label fw-bold">

Notes

</label>



<textarea name="notes"

class="form-control"

rows="3"

placeholder="Sale notes..."></textarea>


</div>





</div>








<div class="d-flex justify-content-end gap-2 mt-4">



<a href="view.php?id=<?= $sale['id']; ?>"

class="btn btn-secondary">


Cancel


</a>





<button type="submit"

class="btn btn-warning">


<i class="fa-solid fa-save"></i>


Update Sale


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