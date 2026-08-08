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


// Get Products

$stmt = $conn->prepare("
SELECT *
FROM products
ORDER BY quantity ASC
");

$stmt->execute();

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>


<div class="container-fluid">


<h2 class="mb-4">

Stock Report

</h2>



<table class="table table-bordered table-striped">


<thead class="table-dark">

<tr>

<th>#</th>
<th>Product Code</th>
<th>Product Name</th>
<th>Current Stock</th>
<th>Minimum Stock</th>
<th>Status</th>

</tr>

</thead>



<tbody>


<?php if(count($products)>0): ?>


<?php foreach($products as $product): ?>


<tr>


<td>
<?= $product['id']; ?>
</td>


<td>
<?= htmlspecialchars($product['product_code']); ?>
</td>


<td>
<?= htmlspecialchars($product['product_name']); ?>
</td>


<td>

<?= $product['quantity']; ?>

</td>


<td>

<?= $product['minimum_stock']; ?>

</td>


<td>


<?php if($product['quantity'] <= $product['minimum_stock']): ?>


<span class="badge bg-danger">

Low Stock

</span>


<?php else: ?>


<span class="badge bg-success">

Available

</span>


<?php endif; ?>


</td>


</tr>


<?php endforeach; ?>


<?php else: ?>


<tr>

<td colspan="6" class="text-center">

No Products Found

</td>

</tr>


<?php endif; ?>


</tbody>


</table>


</div>



<?php

require_once "../../includes/footer.php";

?>