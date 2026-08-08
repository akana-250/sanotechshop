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





$page_title = "View Quotation";





$quotation_id = isset($_GET['id'])

    ? (int)$_GET['id']

    : 0;





if($quotation_id <= 0){


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

SELECT


quotations.*,


customers.customer_name,

customers.phone,

customers.email


FROM quotations



LEFT JOIN customers


ON customers.id = quotations.customer_id



WHERE quotations.id = ?



");



$stmt->execute([$quotation_id]);



$quotation = $stmt->fetch(PDO::FETCH_ASSOC);






if(!$quotation){


    throw new Exception("Quotation not found");


}







/*
|--------------------------------------------------------------------------
| GET QUOTATION ITEMS
|--------------------------------------------------------------------------
*/


$item_stmt = $conn->prepare("

SELECT


quotation_items.*,


products.product_name,

products.product_code



FROM quotation_items



LEFT JOIN products


ON products.id = quotation_items.product_id



WHERE quotation_items.quotation_id = ?



");



$item_stmt->execute([$quotation_id]);



$items = $item_stmt->fetchAll(PDO::FETCH_ASSOC);






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


<i class="fa-solid fa-file-contract text-primary"></i>


Quotation Details


</h2>



<p class="text-muted">


View quotation information


</p>


</div>






<div class="d-flex gap-2">



<a href="quotation_edit.php?id=<?= $quotation['id']; ?>"

class="btn btn-warning">


<i class="fa-solid fa-edit"></i>


Edit


</a>






<a href="quotation_print.php?id=<?= $quotation['id']; ?>"

class="btn btn-dark">


<i class="fa-solid fa-print"></i>


Print


</a>






<a href="quotation_convert.php?id=<?= $quotation['id']; ?>"

class="btn btn-success">


<i class="fa-solid fa-cart-shopping"></i>


Convert Sale


</a>






<a href="quotation.php"

class="btn btn-secondary">


<i class="fa-solid fa-arrow-left"></i>


Back


</a>



</div>


</div>








<!-- ===========================
     QUOTATION INFORMATION
=========================== -->


<div class="card shadow-sm mb-4">


<div class="card-body">



<div class="row">





<div class="col-md-4">


<h6 class="text-muted">

Quotation No

</h6>


<h5>


<?= htmlspecialchars($quotation['quotation_no']); ?>


</h5>


</div>







<div class="col-md-4">


<h6 class="text-muted">

Customer

</h6>


<h5>


<?= htmlspecialchars($quotation['customer_name'] ?? 'Walk In Customer'); ?>


</h5>


</div>







<div class="col-md-4">


<h6 class="text-muted">

Status

</h6>



<span class="badge bg-primary">


<?= htmlspecialchars($quotation['status']); ?>


</span>



</div>




</div>



</div>


</div>
<!-- ===========================
     PAGE HEADER
=========================== -->


<div class="d-flex justify-content-between align-items-center mb-4">


<div>


<h2 class="fw-bold">


<i class="fa-solid fa-file-contract text-primary"></i>


Quotation Details


</h2>



<p class="text-muted">


View quotation information


</p>


</div>






<div class="d-flex gap-2">



<a href="quotation_edit.php?id=<?= $quotation['id']; ?>"

class="btn btn-warning">


<i class="fa-solid fa-edit"></i>


Edit


</a>






<a href="quotation_print.php?id=<?= $quotation['id']; ?>"

class="btn btn-dark">


<i class="fa-solid fa-print"></i>


Print


</a>






<a href="quotation_convert.php?id=<?= $quotation['id']; ?>"

class="btn btn-success">


<i class="fa-solid fa-cart-shopping"></i>


Convert Sale


</a>






<a href="quotation.php"

class="btn btn-secondary">


<i class="fa-solid fa-arrow-left"></i>


Back


</a>



</div>


</div>








<!-- ===========================
     QUOTATION INFORMATION
=========================== -->


<div class="card shadow-sm mb-4">


<div class="card-body">



<div class="row">





<div class="col-md-4">


<h6 class="text-muted">

Quotation No

</h6>


<h5>


<?= htmlspecialchars($quotation['quotation_no']); ?>


</h5>


</div>







<div class="col-md-4">


<h6 class="text-muted">

Customer

</h6>


<h5>


<?= htmlspecialchars($quotation['customer_name'] ?? 'Walk In Customer'); ?>


</h5>


</div>







<div class="col-md-4">


<h6 class="text-muted">

Status

</h6>



<span class="badge bg-primary">


<?= htmlspecialchars($quotation['status']); ?>


</span>



</div>




</div>



</div>


</div>