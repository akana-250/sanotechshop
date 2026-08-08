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


$page_title = "Edit Product";


$id = (int)($_GET['id'] ?? 0);


if($id <= 0){

    header("Location:index.php");
    exit();

}



/*
========================
LOAD PRODUCT
========================
*/

$stmt = $conn->prepare(
"
SELECT *

FROM products

WHERE id = ?

"
);


$stmt->execute([$id]);


$product = $stmt->fetch(PDO::FETCH_ASSOC);



if(!$product){

    header("Location:index.php");
    exit();

}



/*
========================
LOAD CATEGORIES
========================
*/


$categories = $conn->query(

"
SELECT *

FROM categories

ORDER BY category_name ASC

"

)->fetchAll(PDO::FETCH_ASSOC);






$message = "";




/*
========================
UPDATE PRODUCT
========================
*/


if(isset($_POST['update'])){


$product_name  = trim($_POST['product_name']);
$category_id   = $_POST['category_id'];
$barcode       = trim($_POST['barcode']);
$buying_price  = (float)$_POST['buying_price'];
$selling_price = (float)$_POST['selling_price'];
$quantity      = (int)$_POST['quantity'];
$minimum_stock = (int)$_POST['minimum_stock'];
$status        = $_POST['status'];




if(empty($product_name)){


$message = "Product name is required";


}else{


$update = $conn->prepare(

"
UPDATE products SET

product_name=?,
category_id=?,
barcode=?,
buying_price=?,
selling_price=?,
quantity=?,
minimum_stock=?,
status=?

WHERE id=?

"

);



$update->execute([

$product_name,
$category_id,
$barcode,
$buying_price,
$selling_price,
$quantity,
$minimum_stock,
$status,
$id

]);



header("Location:index.php");

exit();


}



}



require_once __DIR__ . "/../../includes/admin_header.php";
require_once __DIR__ . "/../../includes/admin_sidebar.php";

?>

<main>


<div class="container-fluid">


<h2 class="fw-bold mb-4">

<i class="fa-solid fa-pen text-warning"></i>

Edit Product

</h2>



<?php if($message): ?>

<div class="alert alert-danger">

<?= $message; ?>

</div>

<?php endif; ?>





<div class="card shadow-sm">


<div class="card-body">



<form method="POST">



<div class="row g-3">



<div class="col-md-6">

<label class="form-label">
Product Name
</label>

<input 
type="text"
name="product_name"
class="form-control"
value="<?= htmlspecialchars($product['product_name']); ?>"
required>

</div>




<div class="col-md-6">

<label class="form-label">
Category
</label>


<select name="category_id" class="form-select">


<?php foreach($categories as $cat): ?>

<option 
value="<?= $cat['id']; ?>"
<?= $cat['id']==$product['category_id']?'selected':''; ?>
>

<?= htmlspecialchars($cat['category_name']); ?>

</option>


<?php endforeach; ?>


</select>


</div>





<div class="col-md-6">

<label class="form-label">
Barcode
</label>


<input 
type="text"
name="barcode"
class="form-control"
value="<?= htmlspecialchars($product['barcode']); ?>">


</div>





<div class="col-md-3">

<label>
Buying Price
</label>


<input 
type="number"
step="0.01"
name="buying_price"
class="form-control"
value="<?= $product['buying_price']; ?>">


</div>





<div class="col-md-3">

<label>
Selling Price
</label>


<input 
type="number"
step="0.01"
name="selling_price"
class="form-control"
value="<?= $product['selling_price']; ?>">


</div>






<div class="col-md-6">

<label>
Quantity
</label>


<input 
type="number"
name="quantity"
class="form-control"
value="<?= $product['quantity']; ?>">


</div>





<div class="col-md-6">

<label>
Minimum Stock
</label>


<input 
type="number"
name="minimum_stock"
class="form-control"
value="<?= $product['minimum_stock']; ?>">


</div>





<div class="col-md-6">

<label>
Status
</label>


<select name="status" class="form-select">


<option <?= $product['status']=="Active"?"selected":""; ?>>
Active
</option>


<option <?= $product['status']=="Inactive"?"selected":""; ?>>
Inactive
</option>


</select>


</div>




</div>



<button 
class="btn btn-primary mt-4"
name="update">


<i class="fa-solid fa-save"></i>

Update Product

</button>



<a href="index.php"
class="btn btn-secondary mt-4">

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