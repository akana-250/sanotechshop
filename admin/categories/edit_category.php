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


$page_title = "Edit Category";



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


$stmt = $conn->prepare(

"
SELECT *

FROM categories

WHERE id = ?

"

);


$stmt->execute([$id]);


$category = $stmt->fetch(PDO::FETCH_ASSOC);



if(!$category){

    header("Location:index.php");
    exit();

}




$message = "";




/*
========================
UPDATE CATEGORY
========================
*/


if(isset($_POST['update'])){


$category_name = trim($_POST['category_name']);

$description = trim($_POST['description']);



if(empty($category_name)){


$message = "Category name is required";


}else{


try{


$update = $conn->prepare(

"
UPDATE categories SET

category_name = ?,

description = ?

WHERE id = ?

"

);



$update->execute([

$category_name,

$description,

$id

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


<i class="fa-solid fa-pen text-warning"></i>

Edit Category


</h2>


<p class="text-muted">

Update category information

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

value="<?= htmlspecialchars($category['category_name']); ?>"

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

><?= htmlspecialchars($category['description'] ?? ''); ?></textarea>


</div>







<button

type="submit"

name="update"

class="btn btn-primary">


<i class="fa-solid fa-save"></i>

Update Category


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