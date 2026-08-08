<?php

error_reporting(E_ALL);
ini_set('display_errors',1);


if(session_status()===PHP_SESSION_NONE){
    session_start();
}


require_once "../../config/config.php";
require_once "../../includes/user_check.php";


$page_title="Add Product";


$user_id=$_SESSION['user_id'];



// =========================
// GET CATEGORIES
// =========================

$categories=$conn->query("

SELECT id,category_name

FROM categories

ORDER BY category_name ASC

")->fetchAll(PDO::FETCH_ASSOC);





// =========================
// SAVE PRODUCT
// =========================


if(isset($_POST['save'])){


$product_code=trim($_POST['product_code']);

$barcode=trim($_POST['barcode']);

$product_name=trim($_POST['product_name']);

$category_id=$_POST['category_id'];

$buying_price=$_POST['buying_price'];

$selling_price=$_POST['selling_price'];

$quantity=$_POST['quantity'];

$minimum_stock=$_POST['minimum_stock'];

$description=$_POST['description'];

$status=$_POST['status'];

$image=NULL;




// =========================
// IMAGE UPLOAD
// =========================


if(!empty($_FILES['image']['name'])){


$upload_dir="../../uploads/products/";


if(!is_dir($upload_dir)){

mkdir($upload_dir,0777,true);

}



$file_name=time()."_".basename($_FILES['image']['name']);


$target=$upload_dir.$file_name;



$image_type=strtolower(
pathinfo($target,PATHINFO_EXTENSION)
);



$allowed=['jpg','jpeg','png','webp'];



if(in_array($image_type,$allowed)){


move_uploaded_file(

$_FILES['image']['tmp_name'],

$target

);


$image=$file_name;


}



}







try{


$stmt=$conn->prepare("

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

VALUES(?,?,?,?,?,?,?,?,?,?,?)

");



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







// ACTIVITY LOG


$log=$conn->prepare("

INSERT INTO activity_logs

(user_id,activity)

VALUES(?,?)

");



$log->execute([

$user_id,

"Added Product ".$product_name

]);







header("Location:index.php");

exit();



}catch(Exception $e){


echo $e->getMessage();


}




}



include "../../includes/header.php";

include "../../includes/user_sidebar.php";


?>




<div class="container-fluid py-4">



<div class="d-flex justify-content-between align-items-center mb-4">


<h2 class="fw-bold">

<i class="bi bi-plus-circle"></i>

Add Product

</h2>


<a href="index.php"

class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>


</div>






<div class="card shadow">


<div class="card-body">



<form method="POST" enctype="multipart/form-data">



<div class="row">



<div class="col-md-6 mb-3">

<label class="fw-bold">

Product Code

</label>


<input type="text"

name="product_code"

class="form-control"

required>


</div>





<div class="col-md-6 mb-3">


<label class="fw-bold">

Barcode

</label>


<input type="text"

name="barcode"

class="form-control">


</div>






<div class="col-md-6 mb-3">


<label>

Product Name

</label>


<input type="text"

name="product_name"

class="form-control"

required>


</div>






<div class="col-md-6 mb-3">


<label>

Category

</label>


<select name="category_id"

class="form-control"

required>


<option value="">

Select Category

</option>



<?php foreach($categories as $cat): ?>


<option value="<?=$cat['id']?>">

<?=htmlspecialchars($cat['category_name'])?>

</option>


<?php endforeach; ?>


</select>


</div>






<div class="col-md-4 mb-3">


<label>

Buying Price

</label>


<input type="number"

name="buying_price"

class="form-control"

required>


</div>






<div class="col-md-4 mb-3">


<label>

Selling Price

</label>


<input type="number"

name="selling_price"

class="form-control"

required>


</div>






<div class="col-md-4 mb-3">


<label>

Quantity

</label>


<input type="number"

name="quantity"

class="form-control"

value="0">


</div>







<div class="col-md-4 mb-3">


<label>

Minimum Stock

</label>


<input type="number"

name="minimum_stock"

class="form-control"

value="5">


</div>







<div class="col-md-4 mb-3">


<label>

Image

</label>


<input type="file"

name="image"

class="form-control">


</div>








<div class="col-md-4 mb-3">


<label>

Status

</label>


<select name="status"

class="form-control">


<option value="Active">

Active

</option>


<option value="Inactive">

Inactive

</option>


</select>


</div>







<div class="col-md-12 mb-3">


<label>

Description

</label>


<textarea

name="description"

class="form-control"

rows="4"></textarea>


</div>



</div>






<button name="save"

class="btn btn-primary">

<i class="bi bi-save"></i>

Save Product

</button>



<a href="index.php"

class="btn btn-secondary">

Cancel

</a>




</form>


</div>


</div>


</div>



<?php include "../../includes/footer.php"; ?>