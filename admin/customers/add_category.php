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


$page_title = "Add Category";


$message = "";




/*
========================
SAVE CATEGORY
========================
*/


if(isset($_POST['save'])){


$category_name = trim($_POST['category_name']);

$description = trim($_POST['description']);




if(empty($category_name)){


$message = "Category name is required";


}else{



try{


$stmt = $conn->prepare(

"
INSERT INTO categories

(
category_name,
description

)

VALUES

(
?,
?

)

"

);



$stmt->execute([

$category_name,
$description

]);



header("Location:index.php");

exit();



}catch(PDOException $e){


$message = $e->getMessage();


}



}



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


Add Category


</h2>


<p class="text-muted">

Create new product category

</p>


</div>



<a href="index.php"

class="btn btn-secondary">


<i class="fa-solid fa-arrow-left"></i>

Back


</a>


</div>







<?php if($message): ?>


<div class="alert alert-danger">

<?= htmlspecialchars($message); ?>

</div>


<?php endif; ?>








<div class="card shadow-sm">


<div class="card-body">


<form method="POST">





<div class="mb-3">


<label class="form-label">

Category Name

</label>



<input

type="text"

name="category_name"

class="form-control"

placeholder="Enter category name"

required>



</div>







<div class="mb-3">


<label class="form-label">

Description

</label>


<textarea

name="description"

class="form-control"

rows="5"

placeholder="Category description"></textarea>


</div>







<button

type="submit"

name="save"

class="btn btn-primary">


<i class="fa-solid fa-save"></i>


Save Category


</button>





<a href="index.php"

class="btn btn-secondary">


Cancel


</a>




</form>


</div>


</div>




</div>


</main>




<?php

require_once __DIR__ . "/../../includes/admin_footer.php";

?>