<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors','1');


if(session_status() === PHP_SESSION_NONE){
    session_start();
}


require_once __DIR__ . "/../../config/config.php";
require_once __DIR__ . "/../../includes/admin_check.php";


$page_title = "Add Product";


$message = "";



/*
=====================================================
LOAD CATEGORIES
=====================================================
*/


$categoryStmt = $conn->prepare(
"
SELECT id, category_name
FROM categories
ORDER BY category_name ASC
"
);


$categoryStmt->execute();


$categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);





/*
=====================================================
SAVE PRODUCT
=====================================================
*/


if(isset($_POST['save'])){


$product_code = trim($_POST['product_code']);

$barcode = trim($_POST['barcode']);

$product_name = trim($_POST['product_name']);

$category_id = $_POST['category_id'] ?: null;

$buying_price = (float)$_POST['buying_price'];

$selling_price = (float)$_POST['selling_price'];

$quantity = (int)$_POST['quantity'];

$minimum_stock = (int)$_POST['minimum_stock'];

$description = trim($_POST['description']);

$status = $_POST['status'];





if(empty($product_name)){


$message = "Product name is required";


}else{



$image = null;



/*
IMAGE UPLOAD
*/


if(!empty($_FILES['image']['name'])){


$uploadDir = __DIR__ . "/../../assets/uploads/products/";


if(!is_dir($uploadDir)){

mkdir($uploadDir,0777,true);

}



$fileName =
time() . "_" .
basename($_FILES['image']['name']);



$target =
$uploadDir . $fileName;



move_uploaded_file(

$_FILES['image']['tmp_name'],

$target

);



$image = $fileName;


}







$stmt = $conn->prepare(
"
INSERT INTO products

(
product_code,
barcode,
product_name,
category_id,
buying_price,
selling_price,
quantity,
minimum_stock,
image,
description,
status

)

VALUES

(?,?,?,?,?,?,?,?,?,?,?)

"
);



$stmt->execute([

$product_code,

$barcode,

$product_name,

$category_id,

$buying_price,

$selling_price,

$quantity,

$minimum_stock,

$image,

$description,

$status

]);




header(
"Location:index.php"
);

exit();



}



}






require_once __DIR__ . "/../../includes/admin_header.php";

require_once __DIR__ . "/../../includes/admin_sidebar.php";


?>



<main class="admin-content">



<div class="d-flex justify-content-between mb-4">


<div>

<h2 class="fw-bold">

<i class="fa-solid fa-plus text-primary"></i>

Add Product

</h2>


<p class="text-muted">

Create new inventory item

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

<?= $message; ?>

</div>

<?php endif; ?>






<div class="card">


<div class="card-body">


<form method="POST"
enctype="multipart/form-data">



<div class="row g-3">





<div class="col-md-6">


<label class="form-label">

Product Code

</label>


<input

type="text"

name="product_code"

class="form-control"

required>


</div>






<div class="col-md-6">


<label class="form-label">

Barcode

</label>


<input

type="text"

name="barcode"

class="form-control">


</div>







<div class="col-md-6">


<label class="form-label">

Product Name

</label>


<input

type="text"

name="product_name"

class="form-control"

required>


</div>







<div class="col-md-6">


<label class="form-label">

Category

</label>


<select name="category_id"
class="form-select">


<option value="">

Select Category

</option>



<?php foreach($categories as $cat): ?>


<option value="<?= $cat['id']; ?>">


<?= htmlspecialchars($cat['category_name']); ?>


</option>


<?php endforeach; ?>


</select>


</div>







<div class="col-md-4">


<label>

Buying Price

</label>


<input

type="number"

step="0.01"

name="buying_price"

class="form-control"

required>


</div>






<div class="col-md-4">


<label>

Selling Price

</label>


<input

type="number"

step="0.01"

name="selling_price"

class="form-control"

required>


</div>






<div class="col-md-4">


<label>

Quantity

</label>


<input

type="number"

name="quantity"

class="form-control"

value="0">


</div>







<div class="col-md-6">


<label>

Minimum Stock

</label>


<input

type="number"

name="minimum_stock"

class="form-control"

value="5">


</div>








<div class="col-md-6">


<label>

Product Image

</label>


<input

type="file"

name="image"

class="form-control">


</div>







<div class="col-12">


<label>

Description

</label>


<textarea

name="description"

class="form-control"

rows="4"></textarea>


</div>







<div class="col-md-6">


<label>

Status

</label>


<select name="status"
class="form-select">


<option value="Active">

Active

</option>


<option value="Inactive">

Inactive

</option>


</select>


</div>





</div>





<button name="save"
class="btn btn-primary mt-4">


<i class="fa-solid fa-save"></i>

Save Product


</button>



</form>



</div>


</div>



</main>




<?php

require_once __DIR__ . "/../../includes/admin_footer.php";

?>