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


$page_title = "Categories Management";



/*
========================
SEARCH
========================
*/


$search = trim($_GET['search'] ?? '');



try{


if($search !== ''){


$stmt = $conn->prepare(

"
SELECT *

FROM categories

WHERE category_name LIKE ?

ORDER BY id DESC

"

);


$stmt->execute([

"%".$search."%"

]);



}else{


$stmt = $conn->prepare(

"
SELECT *

FROM categories

ORDER BY id DESC

"

);


$stmt->execute();



}



$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);



}catch(PDOException $e){


$categories=[];


}



require_once __DIR__ . "/../../includes/admin_header.php";

require_once __DIR__ . "/../../includes/admin_sidebar.php";

?>



<main>


<div class="container-fluid">



<div class="d-flex justify-content-between align-items-center mb-4">


<div>

<h2 class="fw-bold">

<i class="fa-solid fa-layer-group text-primary"></i>

Categories

</h2>


<p class="text-muted">

Manage product categories

</p>


</div>



<a href="<?= BASE_URL; ?>/admin/categories/add_category.php"

class="btn btn-primary">


<i class="fa-solid fa-plus"></i>

Add Category


</a>


</div>







<div class="card shadow-sm mb-4">


<div class="card-body">


<form method="GET">


<div class="row g-2">


<div class="col-md-10">


<input

type="text"

name="search"

class="form-control"

placeholder="Search category..."

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








<div class="card shadow-sm">


<div class="card-header bg-white">


<h5 class="mb-0">


<i class="fa-solid fa-list"></i>

Categories List


</h5>


</div>




<div class="card-body p-0">


<div class="table-responsive">


<table class="table table-hover mb-0">


<thead class="table-dark">


<tr>


<th>#</th>

<th>Category Name</th>

<th>Description</th>

<th>Date</th>

<th width="180">
Actions
</th>


</tr>


</thead>




<tbody>


<?php if(!empty($categories)): ?>



<?php foreach($categories as $category): ?>


<tr>


<td>

<?= $category['id']; ?>

</td>




<td>

<strong>

<?= htmlspecialchars($category['category_name']); ?>

</strong>

</td>




<td>

<?= htmlspecialchars($category['description'] ?? '-'); ?>

</td>




<td>

<?= $category['created_at']; ?>

</td>




<td>


<a href="view_category.php?id=<?= $category['id']; ?>"

class="btn btn-sm btn-info text-white">


<i class="fa-solid fa-eye"></i>


</a>





<a href="edit_category.php?id=<?= $category['id']; ?>"

class="btn btn-sm btn-warning">


<i class="fa-solid fa-pen"></i>


</a>






<a href="delete_category.php?id=<?= $category['id']; ?>"

class="btn btn-sm btn-danger"

onclick="return confirm('Delete this category?');">


<i class="fa-solid fa-trash"></i>


</a>




</td>



</tr>



<?php endforeach; ?>



<?php else: ?>


<tr>


<td colspan="5" class="text-center py-5">


<i class="fa-solid fa-layer-group fa-4x text-secondary"></i>


<h5 class="mt-3">

No Categories Found

</h5>


<a href="add_category.php"

class="btn btn-primary">


Add Category

</a>



</td>


</tr>


<?php endif; ?>



</tbody>


</table>



</div>


</div>


</div>




</div>


</main>



<?php

require_once __DIR__ . "/../../includes/admin_footer.php";

?>