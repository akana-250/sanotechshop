<?php

error_reporting(E_ALL);
ini_set('display_errors',1);


if(session_status()===PHP_SESSION_NONE){
    session_start();
}


require_once "../../config/config.php";
require_once "../../includes/user_check.php";


$page_title = "My Purchases";


$user_id = $_SESSION['user_id'];

$search = "";



// =============================
// GET PURCHASES
// =============================


$sql = "

SELECT

purchases.*,

suppliers.company_name AS supplier_name


FROM purchases


LEFT JOIN suppliers

ON purchases.supplier_id = suppliers.id


WHERE purchases.user_id = ?


";


$params = [$user_id];





if(isset($_GET['search']) && trim($_GET['search'])!=""){


    $search = trim($_GET['search']);


    $sql .= "

    AND (

        purchases.invoice_no LIKE ?

        OR suppliers.company_name LIKE ?

        OR purchases.status LIKE ?

    )

    ";


    $params[] = "%".$search."%";
    $params[] = "%".$search."%";
    $params[] = "%".$search."%";

}



$sql .= " ORDER BY purchases.id DESC";



$stmt = $conn->prepare($sql);

$stmt->execute($params);


$purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);





// =============================
// ACTIVITY LOG
// =============================

try{


$log = $conn->prepare("

INSERT INTO activity_logs

(user_id,activity)

VALUES(?,?)

");


$log->execute([

$user_id,

"Viewed My Purchases"

]);


}catch(Exception $e){}





include "../../includes/header.php";

include "../../includes/user_sidebar.php";


?>




<div class="container-fluid py-4">



<div class="d-flex justify-content-between align-items-center mb-4">


<div>


<h2 class="fw-bold">

<i class="bi bi-bag-check"></i>

My Purchases

</h2>


<p class="text-muted">

View your purchase history

</p>


</div>


</div>





<form method="GET" class="mb-4">


<div class="row">


<div class="col-md-6">


<input

type="text"

name="search"

class="form-control"

placeholder="Search invoice or supplier..."

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

<th>Invoice</th>

<th>Supplier</th>

<th>Total</th>

<th>Paid</th>

<th>Balance</th>

<th>Status</th>

<th>Date</th>

<th>Action</th>


</tr>


</thead>





<tbody>



<?php if(count($purchases)>0): ?>


<?php foreach($purchases as $i=>$row): ?>



<tr>


<td>

<?= $i+1 ?>

</td>



<td>

<?= htmlspecialchars($row['invoice_no'] ?? '-') ?>

</td>




<td>

<?= htmlspecialchars($row['supplier_name'] ?? 'N/A') ?>

</td>




<td class="fw-bold text-primary">

<?= number_format($row['total'] ?? 0,0) ?>

Frw

</td>




<td>

<?= number_format($row['paid'] ?? 0,0) ?>

Frw

</td>




<td>

<?= number_format($row['balance'] ?? 0,0) ?>

Frw

</td>




<td>


<?php

$status = strtolower($row['status'] ?? '');



if($status=="paid"){

echo '

<span class="badge bg-success">

Paid

</span>

';


}elseif($status=="partial"){


echo '

<span class="badge bg-warning text-dark">

Partial

</span>

';


}else{


echo '

<span class="badge bg-danger">

Unpaid

</span>

';


}


?>


</td>




<td>

<?= date("d M Y",strtotime($row['created_at'])) ?>

</td>




<td>


<a href="view.php?id=<?= $row['id']; ?>"

class="btn btn-sm btn-primary">


<i class="bi bi-eye"></i>


</a>


</td>




</tr>



<?php endforeach; ?>



<?php else: ?>


<tr>


<td colspan="9" class="text-center py-5">


<i class="bi bi-cart-x display-4 text-muted"></i>


<h5 class="mt-3">

No Purchases Found

</h5>


<p class="text-muted">

You don't have purchase records yet.

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