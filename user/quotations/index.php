<?php

error_reporting(E_ALL);
ini_set('display_errors',1);


if(session_status()===PHP_SESSION_NONE){
    session_start();
}



require_once "../../config/config.php";
require_once "../../includes/user_check.php";



$user_id = $_SESSION['user_id'];

$page_title = "My Quotations";




// ===============================
// SEARCH
// ===============================

$search="";



$sql="

SELECT

q.id,

q.quantity,

q.total_amount,

q.status,

q.created_at,


c.customer_name,


p.product_name,

p.product_code


FROM quotations q



LEFT JOIN customers c

ON q.customer_id = c.id



LEFT JOIN products p

ON q.product_id = p.id



WHERE q.user_id = ?



";



$params = [$user_id];




if(isset($_GET['search']) && trim($_GET['search'])!=""){


$search = trim($_GET['search']);



$sql.=" AND (

c.customer_name LIKE ?

OR p.product_name LIKE ?

OR q.status LIKE ?

)";


$params[]="%".$search."%";
$params[]="%".$search."%";
$params[]="%".$search."%";

}



$sql.=" ORDER BY q.id DESC";





$stmt=$conn->prepare($sql);

$stmt->execute($params);



$quotations=$stmt->fetchAll(PDO::FETCH_ASSOC);






// ===============================
// ACTIVITY LOG
// ===============================

try{


$log=$conn->prepare("

INSERT INTO activity_logs

(user_id,activity)

VALUES(?,?)

");


$log->execute([

$user_id,

"Viewed My Quotations"

]);


}catch(Exception $e){}





include "../../includes/header.php";

include "../../includes/user_sidebar.php";

?>





<div class="container-fluid py-4">



<div class="d-flex justify-content-between align-items-center mb-4">



<div>


<h2 class="fw-bold">

<i class="bi bi-file-earmark-text"></i>

My Quotations

</h2>



<p class="text-muted">

View your quotation requests

</p>



</div>




<a href="create.php"

class="btn btn-primary">


<i class="bi bi-plus-circle"></i>

New Quotation


</a>



</div>







<form method="GET" class="mb-4">


<div class="row">


<div class="col-md-6">


<input

type="text"

name="search"

class="form-control"

placeholder="Search quotation..."

value="<?= htmlspecialchars($search); ?>">



</div>




<div class="col-md-2">


<button class="btn btn-primary w-100">


<i class="bi bi-search"></i>

Search


</button>


</div>



</div>


</form>







<div class="card shadow-sm">


<div class="card-body">



<div class="table-responsive">



<table class="table table-hover align-middle">



<thead class="table-dark">


<tr>

<th>#</th>

<th>Customer</th>

<th>Product</th>

<th>Qty</th>

<th>Total</th>

<th>Status</th>

<th>Date</th>

<th>Action</th>


</tr>


</thead>




<tbody>




<?php if(count($quotations)>0): ?>



<?php foreach($quotations as $i=>$row): ?>



<tr>


<td>

<?= $i+1 ?>

</td>



<td>

<?= htmlspecialchars($row['customer_name'] ?? 'N/A'); ?>

</td>




<td>

<strong>

<?= htmlspecialchars($row['product_name'] ?? 'N/A'); ?>

</strong>


<br>


<small class="text-muted">

<?= htmlspecialchars($row['product_code'] ?? ''); ?>

</small>


</td>




<td>

<?= $row['quantity']; ?>

</td>




<td class="fw-bold text-primary">


<?= number_format($row['total_amount'],0); ?>

Frw


</td>




<td>


<?php


$status=strtolower($row['status']);



if($status=="approved"){


echo '<span class="badge bg-success">
Approved
</span>';



}elseif($status=="rejected"){


echo '<span class="badge bg-danger">
Rejected
</span>';



}else{


echo '<span class="badge bg-warning text-dark">
Pending
</span>';

}


?>


</td>





<td>

<?= date("d M Y",strtotime($row['created_at'])); ?>

</td>




<td>


<a href="view.php?id=<?= $row['id']; ?>"

class="btn btn-sm btn-primary">


<i class="bi bi-eye"></i>


</a>



<button onclick="printQuotation(<?= $row['id']; ?>)"

class="btn btn-sm btn-secondary">


<i class="bi bi-printer"></i>


</button>


</td>




</tr>




<?php endforeach; ?>





<?php else: ?>



<tr>


<td colspan="8" class="text-center py-5">


<i class="bi bi-file-earmark-x display-4 text-muted"></i>


<h5 class="mt-3">

No Quotations Found

</h5>


<p class="text-muted">

You haven't created any quotation yet.

</p>



</td>


</tr>



<?php endif; ?>




</tbody>


</table>


</div>


</div>


</div>



</div>






<?php include "../../includes/footer.php"; ?>




<script>


function printQuotation(id){


window.open(

"view.php?id="+id+"&print=1",

"_blank"

);


}


</script>