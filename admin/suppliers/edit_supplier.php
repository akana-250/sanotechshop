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


$page_title = "Edit Supplier";



$id = (int)($_GET['id'] ?? 0);



if($id <= 0){

    header("Location:index.php");
    exit();

}




try{


/*
========================
GET SUPPLIER
========================
*/


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




$error = "";





/*
========================
UPDATE SUPPLIER
========================
*/


if($_SERVER['REQUEST_METHOD'] === 'POST'){



$company_name = trim($_POST['company_name'] ?? '');

$contact_person = trim($_POST['contact_person'] ?? '');

$phone = trim($_POST['phone'] ?? '');

$email = trim($_POST['email'] ?? '');

$address = trim($_POST['address'] ?? '');





if($company_name === ''){


$error = "Company name is required";


}else{



$update = $conn->prepare("

UPDATE suppliers SET


company_name = ?,

contact_person = ?,

phone = ?,

email = ?,

address = ?



WHERE id = ?


");



$update->execute([


$company_name,

$contact_person,

$phone,

$email,

$address,

$id


]);





$_SESSION['success'] = "Supplier updated successfully";


header("Location:index.php");

exit();



}



}



}catch(PDOException $e){


$error = $e->getMessage();


}






require_once __DIR__ . "/../../includes/admin_header.php";

require_once __DIR__ . "/../../includes/admin_sidebar.php";

?>



<main>


<div class="container-fluid">





<div class="d-flex justify-content-between align-items-center mb-4">


<div>


<h2 class="fw-bold">

<i class="fa-solid fa-pen text-warning"></i>

Edit Supplier

</h2>


<p class="text-muted">

Update supplier information

</p>


</div>




<a href="index.php"

class="btn btn-secondary">


<i class="fa-solid fa-arrow-left"></i>

Back


</a>



</div>







<?php if($error): ?>


<div class="alert alert-danger">

<?= htmlspecialchars($error); ?>

</div>


<?php endif; ?>







<div class="card shadow-sm">


<div class="card-body">



<form method="POST">



<div class="row g-3">





<div class="col-md-6">


<label class="form-label">

Company Name *

</label>


<input

type="text"

name="company_name"

class="form-control"

value="<?= htmlspecialchars($supplier['company_name'] ?? ''); ?>"

required>


</div>







<div class="col-md-6">


<label class="form-label">

Contact Person

</label>


<input

type="text"

name="contact_person"

class="form-control"

value="<?= htmlspecialchars($supplier['contact_person'] ?? ''); ?>">


</div>








<div class="col-md-6">


<label class="form-label">

Phone

</label>


<input

type="text"

name="phone"

class="form-control"

value="<?= htmlspecialchars($supplier['phone'] ?? ''); ?>">


</div>







<div class="col-md-6">


<label class="form-label">

Email

</label>


<input

type="email"

name="email"

class="form-control"

value="<?= htmlspecialchars($supplier['email'] ?? ''); ?>">


</div>








<div class="col-12">


<label class="form-label">

Address

</label>


<textarea

name="address"

class="form-control"

rows="4"><?= htmlspecialchars($supplier['address'] ?? ''); ?></textarea>


</div>







<div class="col-12">


<button class="btn btn-warning">


<i class="fa-solid fa-save"></i>

Update Supplier


</button>




<a href="index.php"

class="btn btn-light">


Cancel


</a>



</div>






</div>




</form>



</div>


</div>





</div>


</main>





<?php

require_once __DIR__ . "/../../includes/admin_footer.php";

?>