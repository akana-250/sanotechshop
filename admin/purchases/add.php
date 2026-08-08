<?php

ob_start();


error_reporting(E_ALL);
ini_set('display_errors',1);



if(session_status() === PHP_SESSION_NONE){

    session_start();

}





require_once __DIR__ . "/../../config/config.php";

require_once __DIR__ . "/../../includes/admin_check.php";




$page_title = "Add Purchase";






/* ===========================
   GET SUPPLIERS
=========================== */


$supplier_stmt = $conn->prepare("

SELECT *

FROM suppliers

ORDER BY id DESC

");


$supplier_stmt->execute();


$suppliers = $supplier_stmt->fetchAll(PDO::FETCH_ASSOC);







/* ===========================
   GET PRODUCTS
=========================== */


$product_stmt = $conn->prepare("

SELECT *

FROM products

ORDER BY id DESC

");


$product_stmt->execute();


$products = $product_stmt->fetchAll(PDO::FETCH_ASSOC);







/* ===========================
   SAVE PURCHASE
=========================== */


if(isset($_POST['save_purchase'])){


$supplier_id = $_POST['supplier_id'];

$user_id = $_SESSION['user_id'] ?? 1;



$product_ids = $_POST['product_id'];

$quantities = $_POST['quantity'];

$prices = $_POST['price'];






try{


$conn->beginTransaction();





$total = 0;





foreach($product_ids as $key=>$product_id){


$total += $quantities[$key] * $prices[$key];


}






$stmt = $conn->prepare("

INSERT INTO purchases

(

supplier_id,

user_id,

total

)

VALUES

(?,?,?)

");




$stmt->execute([


$supplier_id,

$user_id,

$total


]);





$purchase_id = $conn->lastInsertId();





/* ===========================
   SAVE PURCHASE ITEMS
=========================== */



$item_stmt = $conn->prepare("

INSERT INTO purchase_items

(

purchase_id,

product_id,

quantity,

price

)

VALUES

(?,?,?,?)

");







$stock_stmt = $conn->prepare("

UPDATE products

SET stock = stock + ?

WHERE id = ?

");







foreach($product_ids as $key=>$product_id){



$quantity = $quantities[$key];

$price = $prices[$key];






$item_stmt->execute([


$purchase_id,

$product_id,

$quantity,

$price


]);








/* UPDATE STOCK */


$stock_stmt->execute([


$quantity,

$product_id


]);




}







$conn->commit();





$_SESSION['success']="Purchase created successfully";



header("Location:index.php");

exit;







}catch(PDOException $e){



if($conn->inTransaction()){


$conn->rollBack();


}



$_SESSION['error']=$e->getMessage();



}



}




require_once __DIR__ . "/../../includes/admin_header.php";

require_once __DIR__ . "/../../includes/admin_sidebar.php";


?>





<main class="admin-content">


<div class="container-fluid">






<div class="d-flex justify-content-between align-items-center mb-4">


<div>


<h2>

<i class="fa-solid fa-cart-plus"></i>

Add Purchase

</h2>


<p class="text-muted">

Create supplier purchase

</p>


</div>






<a href="index.php"

class="btn btn-secondary">


<i class="fa-solid fa-arrow-left"></i>


Back


</a>



</div>








<?php if(isset($_SESSION['error'])): ?>


<div class="alert alert-danger">

<?= $_SESSION['error']; ?>

</div>


<?php unset($_SESSION['error']); ?>


<?php endif; ?>









<div class="content-card">






<form method="POST">








<div class="mb-3">


<label class="form-label">

Supplier

</label>



<select name="supplier_id"

class="form-select"

required>


<option value="">Select Supplier</option>



<?php foreach($suppliers as $supplier): ?>


<option value="<?= $supplier['id']; ?>">


<?= htmlspecialchars($supplier['name']); ?>


</option>



<?php endforeach; ?>


</select>


</div>









<h5>

Products

</h5>








<div class="row mb-3">



<div class="col-md-4">

<label class="form-label">

Product

</label>

<select name="product_id[]"

class="form-select"

required>


<option value="">Select Product</option>



<?php foreach($products as $product): ?>


<option value="<?= $product['id']; ?>">


<?= htmlspecialchars($product['name']); ?>


</option>


<?php endforeach; ?>


</select>


</div>






<div class="col-md-3">


<label class="form-label">

Quantity

</label>


<input

type="number"

name="quantity[]"

class="form-control"

min="1"

required>


</div>






<div class="col-md-3">


<label class="form-label">

Price

</label>


<input

type="number"

step="0.01"

name="price[]"

class="form-control"

required>


</div>





</div>








<button

type="submit"

name="save_purchase"

class="btn btn-primary">


<i class="fa-solid fa-save"></i>


Save Purchase


</button>





</form>






</div>







</div>


</main>







<?php


require_once __DIR__ . "/../../includes/admin_footer.php";


ob_end_flush();


?>