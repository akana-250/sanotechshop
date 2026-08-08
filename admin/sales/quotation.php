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





$page_title = "Sales Quotations";





try{



/*
|--------------------------------------------------------------------------
| GET QUOTATIONS
|--------------------------------------------------------------------------
*/


$stmt = $conn->query("

SELECT


quotations.*,


customers.customer_name,


customers.phone



FROM quotations



LEFT JOIN customers


ON customers.id = quotations.customer_id



ORDER BY quotations.id DESC



");



$quotations = $stmt->fetchAll(PDO::FETCH_ASSOC);






}catch(Exception $e){



$_SESSION['error'] = $e->getMessage();


$quotations = [];


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


<i class="fa-solid fa-file-signature text-primary"></i>


Quotations


</h2>



<p class="text-muted">


Manage customer quotations


</p>


</div>





<a href="quotation_add.php"

class="btn btn-primary">


<i class="fa-solid fa-plus"></i>


New Quotation


</a>



</div>







<!-- ===========================
     QUOTATIONS TABLE
=========================== -->


<div class="card shadow-sm">


<div class="card-body">



<div class="table-responsive">



<table class="table table-hover align-middle">



<thead class="table-light">


<tr>


<th>#</th>

<th>Quotation No</th>

<th>Customer</th>

<th>Phone</th>

<th>Total</th>

<th>Status</th>

<th>Date</th>

<th>Actions</th>


</tr>


</thead>





<tbody>



<?php if(!empty($quotations)): ?>



<?php foreach($quotations as $key=>$quote): ?>


<tr>



<td>


<?= $key+1; ?>


</td>





<td>


<strong>


<?= htmlspecialchars($quote['quotation_no'] ?? '-'); ?>


</strong>


</td>





<td>


<?= htmlspecialchars($quote['customer_name'] ?? 'Walk In'); ?>


</td>





<td>


<?= htmlspecialchars($quote['phone'] ?? '-'); ?>


</td>





<td>


<?= number_format((float)($quote['total'] ?? 0),2); ?>


</td>





<td>


<span class="badge bg-secondary">


<?= htmlspecialchars($quote['status'] ?? 'Pending'); ?>


</span>


</td>





<td>


<?= htmlspecialchars($quote['created_at'] ?? '-'); ?>


</td>





<td>



<a href="quotation_view.php?id=<?= $quote['id']; ?>"

class="btn btn-sm btn-info">


<i class="fa-solid fa-eye"></i>


</a>






<a href="quotation_edit.php?id=<?= $quote['id']; ?>"

class="btn btn-sm btn-warning">


<i class="fa-solid fa-edit"></i>


</a>






</td>



</tr>



<?php endforeach; ?>



<?php else: ?>


<tr>


<td colspan="8" class="text-center">


No quotations found


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