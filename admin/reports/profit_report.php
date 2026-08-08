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



// Total Sales

$stmt = $conn->prepare("
SELECT SUM(total) AS total_sales
FROM sales
");

$stmt->execute();

$sales = $stmt->fetch(PDO::FETCH_ASSOC);


$total_sales = $sales['total_sales'] ?? 0;



// Total Expenses

$stmt = $conn->prepare("
SELECT SUM(amount) AS total_expenses
FROM expenses
");

$stmt->execute();


$expenses = $stmt->fetch(PDO::FETCH_ASSOC);


$total_expenses = $expenses['total_expenses'] ?? 0;



// Profit

$profit = $total_sales - $total_expenses;


?>



<div class="container-fluid">


<h2 class="mb-4">

Profit Report

</h2>




<div class="row">



<div class="col-md-4">


<div class="card bg-success text-white shadow">


<div class="card-body">


<h5>
Total Sales
</h5>


<h3>

<?= number_format($total_sales); ?> RWF

</h3>


</div>


</div>


</div>





<div class="col-md-4">


<div class="card bg-danger text-white shadow">


<div class="card-body">


<h5>
Total Expenses
</h5>


<h3>

<?= number_format($total_expenses); ?> RWF

</h3>


</div>


</div>


</div>





<div class="col-md-4">


<div class="card bg-primary text-white shadow">


<div class="card-body">


<h5>
Net Profit
</h5>


<h3>

<?= number_format($profit); ?> RWF

</h3>


</div>


</div>


</div>



</div>



<hr>



<div class="card shadow mt-4">


<div class="card-body">


<h4>
Calculation
</h4>


<p>

Total Sales - Total Expenses

</p>


<p>

<?= number_format($total_sales); ?>

-

<?= number_format($total_expenses); ?>

=

<strong>

<?= number_format($profit); ?> RWF

</strong>


</p>


</div>


</div>




</div>



<?php

require_once "../../includes/footer.php";

?>