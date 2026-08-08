<?php

error_reporting(E_ALL);
ini_set('display_errors',1);


if(session_status() === PHP_SESSION_NONE){
    session_start();
}


require_once "../../config/config.php";
require_once "../../includes/admin_check.php";



// Get Expenses

$stmt = $conn->prepare("

SELECT *

FROM expenses

ORDER BY expense_date DESC

");


$stmt->execute();


$expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);




// Total Expenses

$totalStmt = $conn->prepare("

SELECT SUM(amount) AS total

FROM expenses

");


$totalStmt->execute();


$total = $totalStmt->fetch(PDO::FETCH_ASSOC);



require_once "../../includes/header.php";
require_once "../../includes/admin_sidebar.php";

?>


<div class="container-fluid">



<div class="d-flex justify-content-between align-items-center mb-4">


<h2>
Expense Report
</h2>


<button onclick="window.print()"
class="btn btn-primary">

Print Report

</button>


</div>





<div class="card shadow mb-4">


<div class="card-body">


<h4>

Total Expenses:

<span class="text-danger">

<?= number_format($total['total'] ?? 0); ?>

RWF

</span>

</h4>


</div>


</div>





<div class="card shadow">


<div class="card-body">


<table class="table table-bordered table-striped">


<thead class="table-dark">


<tr>

<th>
ID
</th>


<th>
Expense Name
</th>


<th>
Amount
</th>


<th>
Date
</th>


<th>
Description
</th>


</tr>


</thead>



<tbody>



<?php if(count($expenses)>0): ?>


<?php foreach($expenses as $expense): ?>


<tr>


<td>

<?= $expense['id']; ?>

</td>



<td>

<?= htmlspecialchars($expense['expense_name']); ?>

</td>



<td>

<?= number_format($expense['amount']); ?>

RWF

</td>



<td>

<?= $expense['expense_date']; ?>

</td>



<td>

<?= htmlspecialchars($expense['description']); ?>

</td>



</tr>


<?php endforeach; ?>


<?php else: ?>


<tr>

<td colspan="5" class="text-center">

No Expenses Found

</td>

</tr>


<?php endif; ?>



</tbody>


</table>



</div>


</div>


</div>




<?php require_once "../../includes/footer.php"; ?>