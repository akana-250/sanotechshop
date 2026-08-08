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


$page_title = "Supplier Details";



$id = (int)($_GET['id'] ?? 0);



if($id <= 0){

    header("Location:index.php");
    exit();

}




try{


$stmt = $conn->prepare("

SELECT *

FROM suppliers

WHERE id = ?

LIMIT 1

");


$stmt->execute([$id]);


$supplier = $stmt->fetch(PDO::FETCH_ASSOC);



if(!$supplier){

    header("Location:index.php");
    exit();

}



}catch(PDOException $e){


die("Database Error: ".$e->getMessage());


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

Supplier Details


</h2>


<p class="text-muted mb-0">

View supplier information

</p>


</div>




<div>


<a href="edit_supplier.php?id=<?= $supplier['id']; ?>"

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








<div class="row">



<div class="col-lg-6">



<div class="card shadow-sm">


<div class="card-body">



<div class="text-center mb-4">


<i class="fa-solid fa-building fa-5x text-primary"></i>



<h3 class="mt-3">

<?= htmlspecialchars($supplier['company_name'] ?? '-'); ?>

</h3>



</div>







<table class="table table-bordered">



<tr>


<th width="35%">

Contact Person

</th>


<td>

<?= htmlspecialchars($supplier['contact_person'] ?? '-'); ?>

</td>


</tr>






<tr>


<th>

Phone

</th>


<td>

<?= htmlspecialchars($supplier['phone'] ?? '-'); ?>

</td>


</tr>







<tr>


<th>

Email

</th>


<td>

<?= htmlspecialchars($supplier['email'] ?? '-'); ?>

</td>


</tr>








<tr>


<th>

Address

</th>


<td>

<?= htmlspecialchars($supplier['address'] ?? '-'); ?>

</td>


</tr>







<tr>


<th>

Created

</th>


<td>

<?= htmlspecialchars($supplier['created_at'] ?? '-'); ?>

</td>


</tr>




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