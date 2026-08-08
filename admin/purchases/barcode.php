<?php

error_reporting(E_ALL);
ini_set('display_errors',1);


if(session_status() === PHP_SESSION_NONE){
    session_start();
}


require_once "../../config/config.php";
require_once "../../includes/admin_check.php";



// Get Products

$stmt = $conn->prepare("

SELECT *

FROM products

ORDER BY id DESC

");


$stmt->execute();


$products = $stmt->fetchAll(PDO::FETCH_ASSOC);



require_once "../../includes/header.php";
require_once "../../includes/admin_sidebar.php";


?>


<div class="container-fluid">


<h2 class="mb-4">

Product Barcode

</h2>



<div class="card shadow">


<div class="card-body">


<table class="table table-bordered table-striped">


<thead class="table-dark">


<tr>


<th>
Product Code
</th>


<th>
Product Name
</th>


<th>
Price
</th>


<th>
Action
</th>


</tr>


</thead>



<tbody>



<?php foreach($products as $product): ?>


<tr>


<td>

<?= htmlspecialchars($product['product_code']); ?>

</td>



<td>

<?= htmlspecialchars($product['product_name']); ?>

</td>



<td>

<?= number_format($product['selling_price']); ?>

RWF

</td>



<td>


<a href="print_barcode.php?id=<?= $product['id']; ?>"

class="btn btn-primary btn-sm">

Print Barcode

</a>


</td>



</tr>



<?php endforeach; ?>



</tbody>


</table>


</div>


</div>


</div>



<?php

require_once "../../includes/footer.php";

?>