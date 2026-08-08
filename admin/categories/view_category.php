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


$page_title = "Category Details";



$id = (int)($_GET['id'] ?? 0);



if($id <= 0){

    header("Location:index.php");
    exit();

}



/*
========================
GET CATEGORY
========================
*/


try{


$stmt = $conn->prepare("

SELECT *

FROM categories

WHERE id = ?

LIMIT 1

");


$stmt->execute([$id]);


$category = $stmt->fetch(PDO::FETCH_ASSOC);



if(!$category){

    header("Location:index.php");
    exit();

}




/*
========================
GET PRODUCTS
========================
*/


$productStmt = $conn->prepare("

SELECT *

FROM products

WHERE category_id = ?

ORDER BY id DESC

");


$productStmt->execute([$id]);


$products = $productStmt->fetchAll(PDO::FETCH_ASSOC);



}catch(PDOException $e){


die("Database Error: ".$e->getMessage());


}





require_once __DIR__ . "/../../includes/admin_header.php";

require_once __DIR__ . "/../../includes/admin_sidebar.php";

?>



<main>


<div class="container-fluid">





<!-- HEADER -->


<div class="d-flex justify-content-between align-items-center mb-4">


<div>


<h2 class="fw-bold">


<i class="fa-solid fa-layer-group text-primary"></i>

Category Details


</h2>


<p class="text-muted mb-0">

View category information and products

</p>


</div>





<div>


<a href="edit_category.php?id=<?= $category['id']; ?>"

class="btn btn-warning">


<i class="fa-solid fa-pen"></i>

Edit


</a>




<a href="index.php"

class="btn btn-secondary">


<i class="fa-solid fa-arrow-left"></i>

Back


</a>


</div>



</div>










<div class="row g-4">





<!-- CATEGORY INFO -->


<div class="col-lg-4">


<div class="card shadow-sm">


<div class="card-body text-center">


<i class="fa-solid fa-layer-group fa-5x text-primary"></i>




<h4 class="mt-3">


<?= htmlspecialchars($category['category_name']); ?>


</h4>




<span class="badge bg-primary">


<?= count($products); ?> Products


</span>





<hr>





<p class="text-muted">


<?= htmlspecialchars($category['description'] ?? 'No description'); ?>


</p>







<table class="table table-bordered">



<tr>


<th>
Created
</th>


<td>

<?= htmlspecialchars($category['created_at'] ?? '-'); ?>


</td>


</tr>






<tr>


<th>
Updated
</th>


<td>

<?= htmlspecialchars($category['updated_at'] ?? '-'); ?>


</td>


</tr>



</table>





</div>


</div>


</div>










<!-- PRODUCTS -->


<div class="col-lg-8">



<div class="card shadow-sm">



<div class="card-header bg-white">


<h5 class="mb-0">


<i class="fa-solid fa-box"></i>

Products in this Category


</h5>


</div>





<div class="card-body p-0">



<div class="table-responsive">



<table class="table table-hover align-middle mb-0">



<thead class="table-dark">


<tr>


<th>
#
</th>


<th>
Product
</th>


<th>
Buying
</th>


<th>
Selling
</th>


<th>
Stock
</th>


<th>
Status
</th>


</tr>


</thead>






<tbody>




<?php if(!empty($products)): ?>



<?php foreach($products as $product): ?>


<tr>


<td>

<?= (int)$product['id']; ?>

</td>





<td>

<strong>

<?= htmlspecialchars($product['product_name']); ?>

</strong>


</td>






<td>


<?= number_format((float)$product['buying_price'],2); ?>


<?= CURRENCY; ?>


</td>







<td>


<?= number_format((float)$product['selling_price'],2); ?>


<?= CURRENCY; ?>


</td>








<td>


<?= (int)($product['quantity'] ?? 0); ?>


</td>







<td>



<?php


$qty = (int)($product['quantity'] ?? 0);

$min = (int)($product['minimum_stock'] ?? 0);



if($qty <= $min):

?>


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


<td colspan="6" class="text-center py-5">


<i class="fa-solid fa-box-open fa-3x text-secondary"></i>



<h5 class="mt-3">

No products in this category

</h5>



</td>


</tr>




<?php endif; ?>




</tbody>



</table>



</div>


</div>


</div>



</div>





</div>


</main>




<?php

require_once __DIR__ . "/../../includes/admin_footer.php";

?>