<?php

error_reporting(E_ALL);
ini_set('display_errors',1);


if(session_status()===PHP_SESSION_NONE){
    session_start();
}


require_once "../../config/config.php";
require_once "../../includes/user_check.php";



if(!isset($_GET['id']) || !is_numeric($_GET['id'])){

    header("Location:index.php");
    exit();

}


$id=(int)$_GET['id'];




// GET CUSTOMER


$stmt=$conn->prepare("

SELECT *

FROM customers

WHERE id=?

");


$stmt->execute([$id]);


$customer=$stmt->fetch(PDO::FETCH_ASSOC);



if(!$customer){

    die("Customer not found");

}




$page_title="Customer Details";



include "../../includes/header.php";
include "../../includes/user_sidebar.php";


?>





<div class="container-fluid py-4">



<div class="d-flex justify-content-between align-items-center mb-4">


<div>

<h2 class="fw-bold">

<i class="bi bi-person-circle"></i>

Customer Details

</h2>


<p class="text-muted">

Customer information

</p>


</div>



<a href="index.php" class="btn btn-dark">


<i class="bi bi-arrow-left"></i>

Back Customers


</a>


</div>







<div class="card shadow-sm">


<div class="card-body">



<div class="row">



<div class="col-md-6 mb-3">


<label class="fw-bold">

Customer Name

</label>


<div class="form-control bg-light">


<?= htmlspecialchars($customer['customer_name']); ?>


</div>


</div>






<div class="col-md-6 mb-3">


<label class="fw-bold">

Phone

</label>


<div class="form-control bg-light">


<?= htmlspecialchars($customer['phone'] ?? '-'); ?>


</div>


</div>







<div class="col-md-6 mb-3">


<label class="fw-bold">

Email

</label>


<div class="form-control bg-light">


<?= htmlspecialchars($customer['email'] ?? '-'); ?>


</div>


</div>







<div class="col-md-6 mb-3">


<label class="fw-bold">

Created Date

</label>


<div class="form-control bg-light">


<?= date(
"d M Y",
strtotime($customer['created_at'])
); ?>


</div>


</div>







<div class="col-md-12 mb-3">


<label class="fw-bold">

Address

</label>


<div class="form-control bg-light" style="height:auto">


<?= nl2br(htmlspecialchars($customer['address'] ?? '-')); ?>


</div>


</div>




</div>




</div>


</div>




</div>







<?php include "../../includes/footer.php"; ?>