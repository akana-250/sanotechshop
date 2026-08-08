<?php

ob_start();


error_reporting(E_ALL);
ini_set('display_errors',1);



if(session_status() === PHP_SESSION_NONE){

    session_start();

}




require_once __DIR__ . "/../../config/config.php";

require_once __DIR__ . "/../../includes/admin_check.php";



$page_title = "Purchases";






/* ===========================
   GET PURCHASES
=========================== */


try{


$stmt = $conn->prepare("

SELECT 

p.*,

s.name AS supplier_name,

u.fullname AS user_name


FROM purchases p


LEFT JOIN suppliers s

ON p.supplier_id = s.id



LEFT JOIN users u

ON p.user_id = u.id



ORDER BY p.id DESC


");



$stmt->execute();


$purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);



}catch(PDOException $e){


$purchases = [];


$_SESSION['error']=$e->getMessage();


}







/* ===========================
   TOTAL PURCHASES
=========================== */


try{


$total_stmt = $conn->prepare("

SELECT SUM(total)

FROM purchases

");


$total_stmt->execute();


$total_purchases = $total_stmt->fetchColumn() ?? 0;



}catch(PDOException $e){


$total_purchases = 0;


}







require_once __DIR__ . "/../../includes/admin_header.php";

require_once __DIR__ . "/../../includes/admin_sidebar.php";


?>





<main class="admin-content">


<div class="container-fluid">
<div class="d-flex justify-content-between align-items-center mb-4">



<div>


<h2>

<i class="fa-solid fa-cart-flatbed"></i>

Purchases

</h2>



<p class="text-muted">

Manage supplier purchases

</p>



</div>








<a href="add.php"

class="btn btn-primary">


<i class="fa-solid fa-plus"></i>


New Purchase


</a>



</div>









<div class="row mb-4">



<div class="col-md-4">


<div class="content-card">


<h6 class="text-muted">

Total Purchases

</h6>



<h3>


<?= number_format($total_purchases,2); ?>

RWF


</h3>



</div>



</div>



</div>









<div class="content-card">



<div class="table-responsive">



<table class="erp-table">



<thead>


<tr>


<th>#</th>


<th>Supplier</th>


<th>User</th>


<th>Total</th>


<th>Date</th>


<th>Actions</th>


</tr>


</thead>







<tbody>




<?php if(!empty($purchases)): ?>





<?php foreach($purchases as $key=>$purchase): ?>



<tr>





<td>


<?= $key + 1; ?>


</td>








<td>


<?= htmlspecialchars($purchase['supplier_name'] ?? 'N/A'); ?>


</td>








<td>


<?= htmlspecialchars($purchase['user_name'] ?? 'N/A'); ?>


</td>








<td>


<?= number_format($purchase['total'] ?? 0,2); ?>

RWF


</td>








<td>


<?= htmlspecialchars($purchase['purchase_date']); ?>


</td>








<td>



<div class="action-buttons">






<a href="view.php?id=<?= $purchase['id']; ?>"

class="btn btn-info btn-sm">


<i class="fa-solid fa-eye"></i>


</a>








<a href="edit.php?id=<?= $purchase['id']; ?>"

class="btn btn-warning btn-sm">


<i class="fa-solid fa-pen"></i>


</a>








<a href="delete.php?id=<?= $purchase['id']; ?>"

class="btn btn-danger btn-sm"

onclick="return confirm('Delete this purchase?');">


<i class="fa-solid fa-trash"></i>


</a>





</div>



</td>







</tr>





<?php endforeach; ?>





<?php else: ?>



<tr>


<td colspan="6" class="text-center">


No purchases found


</td>


</tr>



<?php endif; ?>






</tbody>



</table>



</div>


</div>
</div>


</main>






<?php


require_once __DIR__ . "/../../includes/admin_footer.php";


ob_end_flush();


?>