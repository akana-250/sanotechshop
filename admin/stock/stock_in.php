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


$page_title = "Stock In";


$error = "";



/*
========================
GET PRODUCTS
========================
*/


$stmt = $conn->prepare("

SELECT 

id,

product_name,

quantity

FROM products

ORDER BY product_name ASC

");


$stmt->execute();


$products = $stmt->fetchAll(PDO::FETCH_ASSOC);






/*
========================
SAVE STOCK IN
========================
*/


if($_SERVER['REQUEST_METHOD'] === 'POST'){


$product_id = (int)($_POST['product_id'] ?? 0);

$quantity = (int)($_POST['quantity'] ?? 0);




if($product_id <= 0 || $quantity <= 0){


$error = "Please select product and enter valid quantity";


}else{


try{


$conn->beginTransaction();




/*
UPDATE PRODUCT QUANTITY
*/


$update = $conn->prepare("

UPDATE products

SET quantity = quantity + ?

WHERE id = ?

");


$update->execute([

$quantity,

$product_id

]);







/*
SAVE MOVEMENT
*/


$movement = $conn->prepare("

INSERT INTO stock_movements

(
product_id,
movement_type,
quantity

)

VALUES

(?,?,?)

");


$movement->execute([

$product_id,

"IN",

$quantity

]);





$conn->commit();




$_SESSION['success'] = "Stock added successfully";


header("Location:index.php");

exit();



}catch(PDOException $e){



$conn->rollBack();


$error = $e->getMessage();


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

<i class="fa-solid fa-arrow-down text-success"></i>

Stock In

</h2>


<p class="text-muted">

Add products into inventory

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



<div class="mb-3">


<label class="form-label">

Product

</label>



<select name="product_id"

class="form-control"

required>


<option value="">

-- Select Product --

</option>



<?php foreach($products as $product): ?>


<option value="<?= $product['id']; ?>">


<?= htmlspecialchars($product['product_name']); ?>

(Current stock: <?= $product['quantity']; ?>)


</option>



<?php endforeach; ?>



</select>


</div>







<div class="mb-3">


<label class="form-label">

Quantity

</label>



<input

type="number"

name="quantity"

class="form-control"

min="1"

required>



</div>






<button class="btn btn-success">


<i class="fa-solid fa-plus"></i>

Add Stock


</button>



</form>


</div>


</div>





</div>


</main>




<?php

require_once __DIR__ . "/../../includes/admin_footer.php";

?>