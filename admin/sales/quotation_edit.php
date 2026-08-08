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





$page_title = "Edit Quotation";





$id = isset($_GET['id'])

    ? (int)$_GET['id']

    : 0;





if($id <= 0){


    header("Location: quotation.php");

    exit;


}







try{



/*
|--------------------------------------------------------------------------
| GET QUOTATION
|--------------------------------------------------------------------------
*/


$stmt = $conn->prepare("

SELECT *

FROM quotations

WHERE id = ?

");



$stmt->execute([$id]);



$quotation = $stmt->fetch(PDO::FETCH_ASSOC);






if(!$quotation){


    throw new Exception("Quotation not found");


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



header("Location: quotation.php");


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


<i class="fa-solid fa-pen-to-square text-warning"></i>


Edit Quotation


</h2>



<p class="text-muted">


Update quotation information


</p>


</div>





<a href="quotation_view.php?id=<?= $quotation['id']; ?>"

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



<form action="quotation_update.php" method="POST">



<input type="hidden"

name="quotation_id"

value="<?= $quotation['id']; ?>">







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

<?= ($quotation['customer_id'] == $customer['id']) ? 'selected' : ''; ?>

>


<?= htmlspecialchars($customer['customer_name']); ?>


</option>



<?php endforeach; ?>



</select>



</div>







<div class="col-md-6">


<label class="form-label fw-bold">

Quotation Number

</label>




<input type="text"

class="form-control"

value="<?= htmlspecialchars($quotation['quotation_no']); ?>"

readonly>



</div>







<div class="col-md-6">


<label class="form-label fw-bold">

Status

</label>




<select name="status"

class="form-select">



<option value="Pending"

<?= $quotation['status']=="Pending"?'selected':''; ?>

>

Pending

</option>




<option value="Approved"

<?= $quotation['status']=="Approved"?'selected':''; ?>

>

Approved

</option>




<option value="Rejected"

<?= $quotation['status']=="Rejected"?'selected':''; ?>

>

Rejected

</option>



</select>



</div>







<div class="col-md-6">


<label class="form-label fw-bold">

Total

</label>



<input type="number"

name="total"

step="0.01"

class="form-control"

value="<?= $quotation['total']; ?>">



</div>

<div class="col-md-12">


<label class="form-label fw-bold">

Notes

</label>



<textarea name="notes"

class="form-control"

rows="4"

placeholder="Quotation notes..."><?= htmlspecialchars($quotation['notes'] ?? ''); ?></textarea>



</div>





</div>








<div class="d-flex justify-content-end gap-2 mt-4">



<a href="quotation_view.php?id=<?= $quotation['id']; ?>"

class="btn btn-secondary">


Cancel


</a>






<button type="submit"

class="btn btn-warning">


<i class="fa-solid fa-save"></i>


Update Quotation


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