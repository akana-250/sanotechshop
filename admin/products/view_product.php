<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors','1');


if(session_status() === PHP_SESSION_NONE){
    session_start();
}


require_once __DIR__ . "/../../config/config.php";
require_once __DIR__ . "/../../includes/admin_check.php";
require_once __DIR__ . "/../../includes/db.php";


$page_title = "View Product";


$id = (int)($_GET['id'] ?? 0);



if($id <= 0){

    header("Location:index.php");
    exit();

}



/*
========================
GET PRODUCT DETAILS
========================
*/


$stmt = $conn->prepare(

"
SELECT 

products.*,

categories.category_name

FROM products

LEFT JOIN categories

ON products.category_id = categories.id

WHERE products.id = ?

"

);


$stmt->execute([$id]);


$product = $stmt->fetch(PDO::FETCH_ASSOC);



if(!$product){

    header("Location:index.php");
    exit();

}



require_once __DIR__ . "/../../includes/admin_header.php";

require_once __DIR__ . "/../../includes/admin_sidebar.php";


?>



<main>


<div class="container-fluid">



<div class="d-flex justify-content-between align-items-center mb-4">


<div>

<h2 class="fw-bold">

<i class="fa-solid fa-box text-primary"></i>

Product Details

</h2>


<p class="text-muted">

View complete product information

</p>


</div>




<div>


<a href="edit_product.php?id=<?= $product['id']; ?>"
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







<div class="card shadow-sm">


<div class="card-body">



<div class="row g-4">





<div class="col-md-4 text-center">



<?php if(!empty($product['image'])): ?>


<img

src="<?= BASE_URL; ?>/uploads/products/<?= htmlspecialchars($product['image']); ?>"

class="img-fluid rounded"

style="max-height:250px;">



<?php else: ?>


<div class="border rounded p-5">


<i class="fa-solid fa-box fa-5x text-secondary"></i>


<p class="mt-3 text-muted">

No Image

</p>


</div>


<?php endif; ?>



</div>






<div class="col-md-8">



<table class="table table-bordered">


<tr>

<th width="35%">
Product Name
</th>


<td>

<?= htmlspecialchars($product['product_name']); ?>

</td>


</tr>





<tr>

<th>
Product Code
</th>


<td>

<?= htmlspecialchars($product['product_code'] ?? '-'); ?>

</td>


</tr>





<tr>

<th>
Barcode
</th>


<td>

<?= htmlspecialchars($product['barcode'] ?? '-'); ?>

</td>


</tr>





<tr>

<th>
Category
</th>


<td>

<?= htmlspecialchars($product['category_name'] ?? 'No Category'); ?>

</td>


</tr>





<tr>

<th>
Buying Price
</th>


<td>

<?= number_format((float)$product['buying_price'],2); ?>

<?= CURRENCY; ?>

</td>


</tr>





<tr>

<th>
Selling Price
</th>


<td>

<?= number_format((float)$product['selling_price'],2); ?>

<?= CURRENCY; ?>

</td>


</tr>





<tr>

<th>
Quantity
</th>


<td>


<?= (int)$product['quantity']; ?>


</td>


</tr>





<tr>

<th>
Minimum Stock
</th>


<td>

<?= (int)$product['minimum_stock']; ?>

</td>


</tr>






<tr>

<th>
Stock Status
</th>


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






<tr>

<th>
Status
</th>


<td>


<?php if($product['status']=="Active"): ?>


<span class="badge bg-success">

Active

</span>


<?php else: ?>


<span class="badge bg-secondary">

Inactive

</span>


<?php endif; ?>


</td>


</tr>





<tr>

<th>
Created At
</th>


<td>

<?= $product['created_at']; ?>

</td>


</tr>




</table>



</div>





<?php if(!empty($product['description'])): ?>


<div class="col-12">


<h5 class="fw-bold">

Description

</h5>


<div class="p-3 bg-light rounded">


<?= nl2br(htmlspecialchars($product['description'])); ?>


</div>


</div>


<?php endif; ?>






</div>


</div>


</div>



</div>


</main>



<?php

require_once __DIR__ . "/../../includes/admin_footer.php";

?>