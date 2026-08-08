<?php

ob_start();

error_reporting(E_ALL);
ini_set('display_errors',1);


if(session_status() === PHP_SESSION_NONE){

    session_start();

}



require_once __DIR__ . "/../../config/config.php";

require_once __DIR__ . "/../../includes/admin_check.php";



$page_title = "Expenses";





/* ===========================
   GET EXPENSES
=========================== */


try{


$stmt = $conn->prepare("

SELECT *

FROM expenses

ORDER BY id DESC

");


$stmt->execute();


$expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);




}catch(PDOException $e){


$expenses = [];

$_SESSION['error'] = $e->getMessage();


}







/* ===========================
   TOTAL EXPENSES
=========================== */


try{


$total_stmt = $conn->prepare("

SELECT SUM(amount)

FROM expenses

");


$total_stmt->execute();


$total_expenses = $total_stmt->fetchColumn() ?? 0;



}catch(PDOException $e){


$total_expenses = 0;


}





require_once __DIR__ . "/../../includes/admin_header.php";

require_once __DIR__ . "/../../includes/admin_sidebar.php";


?>





<main class="admin-content">


<div class="container-fluid">
<div class="d-flex justify-content-between align-items-center mb-4">


<div>

<h2>

<i class="fa-solid fa-money-bill-wave"></i>

Expenses

</h2>


<p class="text-muted">

Manage company expenses

</p>


</div>





<a href="add.php"

class="btn btn-primary">


<i class="fa-solid fa-plus"></i>


Add Expense


</a>



</div>








<div class="row mb-4">



<div class="col-md-4">



<div class="content-card">



<h6 class="text-muted">

Total Expenses

</h6>




<h3>


<?= number_format($total_expenses,2); ?>


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


<th>Expense Name</th>


<th>Amount</th>


<th>Date</th>


<th>Description</th>


<th>Actions</th>


</tr>


</thead>







<tbody>



<?php if(!empty($expenses)): ?>



<?php foreach($expenses as $key=>$expense): ?>



<tr>


<td>


<?= $key + 1; ?>


</td>






<td>


<?= htmlspecialchars($expense['expense_name']); ?>


</td>







<td>


<?= number_format($expense['amount'],2); ?>

RWF


</td>







<td>


<?= htmlspecialchars($expense['expense_date']); ?>


</td>







<td>


<?= htmlspecialchars($expense['description']); ?>


</td>








<td>


<div class="action-buttons">





<a href="edit.php?id=<?= $expense['id']; ?>"

class="btn btn-warning btn-sm">


<i class="fa-solid fa-pen"></i>


</a>








<a href="delete.php?id=<?= $expense['id']; ?>"

class="btn btn-danger btn-sm"

onclick="return confirm('Delete this expense?');">


<i class="fa-solid fa-trash"></i>


</a>





</div>


</td>





</tr>



<?php endforeach; ?>



<?php else: ?>



<tr>


<td colspan="6" class="text-center">


No expenses found


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