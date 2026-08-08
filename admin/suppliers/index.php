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


$page_title = "Suppliers Management";


$search = trim($_GET['search'] ?? '');



try{


if($search !== ''){


$stmt = $conn->prepare("

SELECT *

FROM suppliers

WHERE company_name LIKE ?

OR contact_person LIKE ?

OR phone LIKE ?

OR email LIKE ?

ORDER BY id DESC

");


$stmt->execute([

"%".$search."%",
"%".$search."%",
"%".$search."%",
"%".$search."%"

]);



}else{


$stmt = $conn->prepare("

SELECT *

FROM suppliers

ORDER BY id DESC

");


$stmt->execute();


}



$suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);



}catch(PDOException $e){


$suppliers = [];

}



require_once __DIR__ . "/../../includes/admin_header.php";

require_once __DIR__ . "/../../includes/admin_sidebar.php";

?>



<main>


<div class="container-fluid">



<div class="d-flex justify-content-between align-items-center mb-4">


<div>


<h2 class="fw-bold">

<i class="fa-solid fa-truck text-primary"></i>

Suppliers Management

</h2>


<p class="text-muted mb-0">

Manage suppliers information

</p>


</div>




<a href="add_supplier.php"

class="btn btn-primary">


<i class="fa-solid fa-plus"></i>

Add Supplier


</a>


</div>







<?php if(isset($_SESSION['success'])): ?>


<div class="alert alert-success">

<?= htmlspecialchars($_SESSION['success']); ?>

</div>


<?php unset($_SESSION['success']); ?>


<?php endif; ?>





<?php if(isset($_SESSION['error'])): ?>


<div class="alert alert-danger">

<?= htmlspecialchars($_SESSION['error']); ?>

</div>


<?php unset($_SESSION['error']); ?>


<?php endif; ?>









<div class="card shadow-sm mb-4">


<div class="card-body">


<form method="GET">


<div class="row g-2">


<div class="col-md-10">


<input

type="text"

name="search"

class="form-control"

placeholder="Search company, contact, phone or email..."

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

Suppliers List

</h5>


</div>





<div class="card-body p-0">


<div class="table-responsive">



<table class="table table-hover align-middle mb-0">



<thead class="table-dark">


<tr>


<th>#</th>

<th>Company</th>

<th>Contact Person</th>

<th>Phone</th>

<th>Email</th>

<th>Address</th>

<th>Actions</th>


</tr>


</thead>




<tbody>



<?php if(!empty($suppliers)): ?>



<?php foreach($suppliers as $supplier): ?>


<tr>


<td>

<?= (int)$supplier['id']; ?>

</td>





<td>


<strong>

<?= htmlspecialchars($supplier['company_name'] ?? '-'); ?>

</strong>


</td>






<td>

<?= htmlspecialchars($supplier['contact_person'] ?? '-'); ?>

</td>






<td>

<?= htmlspecialchars($supplier['phone'] ?? '-'); ?>

</td>






<td>

<?= htmlspecialchars($supplier['email'] ?? '-'); ?>

</td>






<td>

<?= htmlspecialchars($supplier['address'] ?? '-'); ?>

</td>







<td>


<a href="view_supplier.php?id=<?= $supplier['id']; ?>"

class="btn btn-sm btn-info text-white me-1"

title="View">


<i class="fa-solid fa-eye"></i>


</a>





<a href="edit_supplier.php?id=<?= $supplier['id']; ?>"

class="btn btn-sm btn-warning me-1"

title="Edit">


<i class="fa-solid fa-pen"></i>


</a>






<a href="delete_supplier.php?id=<?= $supplier['id']; ?>"

class="btn btn-sm btn-danger"

title="Delete"

onclick="return confirm('Delete this supplier?');">


<i class="fa-solid fa-trash"></i>


</a>



</td>



</tr>



<?php endforeach; ?>





<?php else: ?>


<tr>


<td colspan="7" class="text-center py-5">


<i class="fa-solid fa-truck fa-4x text-secondary"></i>


<h5 class="mt-3">

No Suppliers Found

</h5>


<a href="add_supplier.php"

class="btn btn-primary">


Add Supplier

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