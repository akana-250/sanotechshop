<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

require_once "../../config/config.php";
require_once "../../includes/admin_check.php";

require_once "../../includes/header.php";
require_once "../../includes/admin_sidebar.php";



// Get Sales Report

$stmt = $conn->prepare("

SELECT 

sales.*,

customers.customer_name


FROM sales


LEFT JOIN customers

ON sales.customer_id = customers.id


ORDER BY sales.id DESC


");


$stmt->execute();


$sales = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>


<div class="container-fluid">


<div class="d-flex justify-content-between align-items-center mb-4">


<h2>
Sales Report
</h2>


<a href="../sales/add.php" class="btn btn-primary">

New Sale

</a>


</div>




<table class="table table-bordered table-striped">


<thead class="table-dark">


<tr>

<th>#</th>

<th>Invoice No</th>

<th>Customer</th>

<th>Total</th>

<th>Paid</th>

<th>Balance</th>

<th>Payment</th>

<th>Date</th>

<th>Action</th>


</tr>


</thead>



<tbody>



<?php if(count($sales)>0): ?>


<?php foreach($sales as $sale): ?>


<tr>


<td>
<?= $sale['id']; ?>
</td>



<td>
<?= htmlspecialchars($sale['invoice_no']); ?>
</td>



<td>

<?= htmlspecialchars($sale['customer_name'] ?? 'Walk In'); ?>

</td>



<td>

<?= number_format($sale['total']); ?> RWF

</td>



<td>

<?= number_format($sale['paid']); ?> RWF

</td>



<td>

<?= number_format($sale['balance']); ?> RWF

</td>



<td>

<?= htmlspecialchars($sale['payment_method']); ?>

</td>



<td>

<?= $sale['sale_date']; ?>

</td>



<td>


<a href="../sales/view.php?id=<?= $sale['id']; ?>"
class="btn btn-info btn-sm">

View

</a>


<a href="../sales/print.php?id=<?= $sale['id']; ?>"
class="btn btn-success btn-sm">

Print

</a>


</td>


</tr>



<?php endforeach; ?>


<?php else: ?>


<tr>

<td colspan="9" class="text-center">

No Sales Found

</td>

</tr>


<?php endif; ?>


</tbody>


</table>


</div>



<?php

require_once "../../includes/footer.php";

?>