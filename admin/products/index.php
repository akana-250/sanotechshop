<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors','1');


if(session_status() === PHP_SESSION_NONE){
    session_start();
}


require_once __DIR__ . "/../../config/config.php";
require_once __DIR__ . "/../../includes/admin_check.php";


$page_title = "Products Management";



$search = trim($_GET['search'] ?? '');



try{


if($search !== ""){


$stmt = $conn->prepare(
"
SELECT 
products.*,
categories.category_name

FROM products

LEFT JOIN categories

ON products.category_id = categories.id

WHERE 

products.product_name LIKE ?

OR products.product_code LIKE ?

OR products.barcode LIKE ?

ORDER BY products.id DESC

"
);


$searchValue = "%".$search."%";


$stmt->execute([
$searchValue,
$searchValue,
$searchValue
]);


}else{


$stmt = $conn->query(
"
SELECT 
products.*,
categories.category_name

FROM products

LEFT JOIN categories

ON products.category_id = categories.id

ORDER BY products.id DESC
"
);


}



$products = $stmt->fetchAll(PDO::FETCH_ASSOC);



}

catch(PDOException $e){

$products=[];

}




require_once __DIR__ . "/../../includes/admin_header.php";

require_once __DIR__ . "/../../includes/admin_sidebar.php";


?>



<main class="admin-content">



<div class="d-flex justify-content-between align-items-center mb-4">


<div>

<h2 class="fw-bold">

<i class="fa-solid fa-box text-primary"></i>

Products

</h2>


<p class="text-muted">

Manage inventory products

</p>


</div>



<a href="add_product.php"
class="btn btn-primary">

<i class="fa-solid fa-plus"></i>

Add Product

</a>



</div>









<div class="card mb-4">


<div class="card-body">


<form method="GET">


<div class="row g-2">


<div class="col-md-10">


<input 
type="text"
name="search"
class="form-control"

placeholder="Search product name, code or barcode..."

value="<?= htmlspecialchars($search); ?>">


</div>



<div class="col-md-2">


<button class="btn btn-primary w-100">

<i class="fa-solid fa-search"></i>

Search

</button>


</div>


</div>


</form>


</div>


</div>









<div class="card">


<div class="card-header">


<h5 class="mb-0">

Products List

<span class="badge bg-primary">

<?= count($products); ?>

</span>


</h5>


</div>





<div class="card-body table-responsive">


<table class="table align-middle">


<thead>


<tr>

<th>#</th>

<th>Product</th>

<th>Code</th>

<th>Category</th>

<th>Buying</th>

<th>Selling</th>

<th>Stock</th>

<th>Status</th>

<th>Action</th>


</tr>


</thead>



<tbody>


<?php foreach($products as $product): ?>


<?php

$qty = (int)($product['quantity'] ?? 0);

$minimum = (int)($product['minimum_stock'] ?? 0);

?>


<tr>


<td>
<?= $product['id']; ?>
</td>



<td>

<strong>

<?= htmlspecialchars($product['product_name']); ?>

</strong>

</td>




<td>

<?= htmlspecialchars(
$product['product_code'] ?? '-'
); ?>


</td>





<td>

<?= htmlspecialchars(
$product['category_name'] ?? 'No Category'
); ?>


</td>






<td>

<?= number_format(
(float)$product['buying_price'],
2
); ?>

<?= CURRENCY; ?>

</td>






<td>

<?= number_format(
(float)$product['selling_price'],
2
); ?>

<?= CURRENCY; ?>


</td>







<td>


<?= $qty; ?>


</td>







<td>


<?php if($qty <= $minimum): ?>


<span class="badge bg-danger">

Low Stock

</span>


<?php else: ?>


<span class="badge bg-success">

Available

</span>


<?php endif; ?>


</td>







<td>


<a href="view_product.php?id=<?= $product['id']; ?>"
class="btn btn-sm btn-info text-white">

<i class="fa-solid fa-eye"></i>

</a>



<a href="edit_product.php?id=<?= $product['id']; ?>"
class="btn btn-sm btn-warning">

<i class="fa-solid fa-pen"></i>

</a>




<a href="delete_product.php?id=<?= $product['id']; ?>"
class="btn btn-sm btn-danger"
onclick="return confirm('Delete this product?');">


<i class="fa-solid fa-trash"></i>


</a>



</td>



</tr>


<?php endforeach; ?>



</tbody>


</table>


</div>


</div>




</main>



<?php

require_once __DIR__ . "/../../includes/admin_footer.php";

?>