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





$page_title = "Invoice";





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
| GET SALE DETAILS
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


    throw new Exception("Invoice not found");


}






/*
|--------------------------------------------------------------------------
| GET SALE ITEMS
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


<div class="container-fluid"><!-- ===========================
     INVOICE HEADER
=========================== -->


<div class="card shadow-sm border-0 mb-4">


<div class="card-body">



<div class="d-flex justify-content-between align-items-start">



    <div>


        <h2 class="fw-bold text-primary mb-1">


            <i class="fa-solid fa-store"></i>

            SANO TECH SHOP


        </h2>


        <p class="mb-0 text-muted">

            ERP SALES INVOICE

        </p>


    </div>




    <div class="text-end">


        <h4 class="fw-bold mb-2">

            INVOICE

        </h4>


        <p class="mb-1">

            <strong>No:</strong>

            <?= htmlspecialchars($sale['invoice_no']); ?>

        </p>


        <p class="mb-0">

            <strong>Date:</strong>

            <?= htmlspecialchars($sale['sale_date']); ?>

        </p>


    </div>



</div>





<hr>






<!-- CUSTOMER INFORMATION -->


<div class="row">



<div class="col-md-6">


<h6 class="fw-bold">

    Bill To:

</h6>



<p class="mb-1">


<strong>

<?= htmlspecialchars($sale['customer_name'] ?? 'Walk In Customer'); ?>

</strong>


</p>



<?php if(!empty($sale['phone'])): ?>


<p class="mb-1">


Phone:

<?= htmlspecialchars($sale['phone']); ?>


</p>


<?php endif; ?>





<?php if(!empty($sale['email'])): ?>


<p class="mb-1">


Email:

<?= htmlspecialchars($sale['email']); ?>


</p>


<?php endif; ?>





<?php if(!empty($sale['address'])): ?>


<p class="mb-0">


Address:

<?= htmlspecialchars($sale['address']); ?>


</p>


<?php endif; ?>


</div>






<div class="col-md-6 text-md-end">


<a href="print.php?id=<?= $sale['id']; ?>"

class="btn btn-dark">


<i class="fa-solid fa-print"></i>


Print Invoice


</a>



<a href="index.php"

class="btn btn-secondary">


<i class="fa-solid fa-arrow-left"></i>


Back


</a>


</div>




</div>


</div>


</div><!-- ===========================
     INVOICE HEADER
=========================== -->


<div class="card shadow-sm border-0 mb-4">


<div class="card-body">



<div class="d-flex justify-content-between align-items-start">



    <div>


        <h2 class="fw-bold text-primary mb-1">


            <i class="fa-solid fa-store"></i>

            SANO TECH SHOP


        </h2>


        <p class="mb-0 text-muted">

            ERP SALES INVOICE

        </p>


    </div>




    <div class="text-end">


        <h4 class="fw-bold mb-2">

            INVOICE

        </h4>


        <p class="mb-1">

            <strong>No:</strong>

            <?= htmlspecialchars($sale['invoice_no']); ?>

        </p>


        <p class="mb-0">

            <strong>Date:</strong>

            <?= htmlspecialchars($sale['sale_date']); ?>

        </p>


    </div>



</div>





<hr>






<!-- CUSTOMER INFORMATION -->


<div class="row">



<div class="col-md-6">


<h6 class="fw-bold">

    Bill To:

</h6>



<p class="mb-1">


<strong>

<?= htmlspecialchars($sale['customer_name'] ?? 'Walk In Customer'); ?>

</strong>


</p>



<?php if(!empty($sale['phone'])): ?>


<p class="mb-1">


Phone:

<?= htmlspecialchars($sale['phone']); ?>


</p>


<?php endif; ?>





<?php if(!empty($sale['email'])): ?>


<p class="mb-1">


Email:

<?= htmlspecialchars($sale['email']); ?>


</p>


<?php endif; ?>





<?php if(!empty($sale['address'])): ?>


<p class="mb-0">


Address:

<?= htmlspecialchars($sale['address']); ?>


</p>


<?php endif; ?>


</div>






<div class="col-md-6 text-md-end">


<a href="print.php?id=<?= $sale['id']; ?>"

class="btn btn-dark">


<i class="fa-solid fa-print"></i>


Print Invoice


</a>



<a href="index.php"

class="btn btn-secondary">


<i class="fa-solid fa-arrow-left"></i>


Back


</a>


</div>




</div>


</div>


</div><!-- ===========================
     INVOICE FOOTER
=========================== -->


<div class="card shadow-sm border-0 mt-4">


<div class="card-body text-center">



<h6 class="fw-bold mb-2">

Thank you for your business!

</h6>



<p class="text-muted mb-0">


SANO TECH SHOP ERP SYSTEM


</p>



</div>


</div>





</div>

</main>




<?php


require_once __DIR__ . "/../../includes/admin_footer.php";


ob_end_flush();


?>