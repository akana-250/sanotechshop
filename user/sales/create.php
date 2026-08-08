<?php

error_reporting(E_ALL);
ini_set('display_errors',1);


if(session_status()===PHP_SESSION_NONE){
    session_start();
}


require_once "../../config/config.php";
require_once "../../includes/user_check.php";


$user_id = $_SESSION['user_id'];




// ===============================
// GET CUSTOMERS
// ===============================

$customers = $conn->query("

SELECT id, customer_name, phone

FROM customers

ORDER BY customer_name ASC

")->fetchAll(PDO::FETCH_ASSOC);




// ===============================
// GET PRODUCTS
// ===============================

$products = $conn->query("

SELECT 

id,
product_name,
selling_price,
quantity

FROM products

WHERE status='Active'

ORDER BY product_name ASC

")->fetchAll(PDO::FETCH_ASSOC);







// ===============================
// SAVE SALE
// ===============================


if(isset($_POST['save'])){


$customer_id = $_POST['customer_id'];

$product_id = $_POST['product_id'];

$quantity = (int)$_POST['quantity'];

$paid = (float)$_POST['paid'];

$payment_method = $_POST['payment_method'];





// GET PRODUCT

$stmt=$conn->prepare("

SELECT *

FROM products

WHERE id=?

");


$stmt->execute([$product_id]);


$product=$stmt->fetch(PDO::FETCH_ASSOC);




if(!$product){


$_SESSION['error']="Product not found.";

header("Location:create.php");

exit();


}




// ===============================
// STOCK CHECK
// ===============================


$current_stock=(int)$product['quantity'];



if($quantity <=0){


$_SESSION['error']="Invalid quantity.";

header("Location:create.php");

exit();


}




if($current_stock <=0){


$_SESSION['error']="This product is out of stock.";

header("Location:create.php");

exit();


}




if($quantity > $current_stock){


$_SESSION['error']="Not enough stock. Available stock is ".$current_stock;


header("Location:create.php");

exit();


}





// PRICE CALCULATION


$price=(float)$product['selling_price'];


$subtotal=$price*$quantity;


$total=$subtotal;


$balance=$total-$paid;





if($balance<=0){

$status="Paid";

}
elseif($paid>0){

$status="Partial";

}
else{

$status="Unpaid";

}




// INVOICE NUMBER

$invoice_no="INV-".date("YmdHis");





try{


$conn->beginTransaction();




// INSERT SALE


$sale=$conn->prepare("

INSERT INTO sales

(
invoice_no,
customer_id,
user_id,
total,
paid,
balance,
payment_method,
status
)

VALUES(?,?,?,?,?,?,?,?)

");



$sale->execute([

$invoice_no,

$customer_id,

$user_id,

$total,

$paid,

$balance,

$payment_method,

$status

]);





$sale_id=$conn->lastInsertId();






// INSERT SALE ITEM


$item=$conn->prepare("

INSERT INTO sale_items

(
sale_id,
product_id,
quantity,
price,
subtotal
)

VALUES(?,?,?,?,?)

");



$item->execute([

$sale_id,

$product_id,

$quantity,

$price,

$subtotal

]);







// UPDATE STOCK


$update=$conn->prepare("

UPDATE products

SET quantity = quantity - ?

WHERE id=?

");


$update->execute([

$quantity,

$product_id

]);






// ACTIVITY LOG


$log=$conn->prepare("

INSERT INTO activity_logs

(user_id,activity)

VALUES(?,?)

");



$log->execute([

$user_id,

"Created Sale ".$invoice_no

]);





$conn->commit();




$_SESSION['success']="Sale created successfully.";



header("Location:index.php");

exit();




}catch(Exception $e){


$conn->rollBack();


$_SESSION['error']=$e->getMessage();


header("Location:create.php");

exit();


}



}




include "../../includes/header.php";

include "../../includes/user_sidebar.php";


?>




<div class="container-fluid py-4">



<?php

if(isset($_SESSION['error'])){

?>

<div class="alert alert-danger">

<i class="bi bi-exclamation-triangle"></i>

<?= $_SESSION['error']; ?>

</div>

<?php

unset($_SESSION['error']);

}



if(isset($_SESSION['success'])){

?>

<div class="alert alert-success">

<?= $_SESSION['success']; ?>

</div>

<?php

unset($_SESSION['success']);

}


?>






<div class="d-flex justify-content-between mb-4">


<h2 class="fw-bold">

<i class="bi bi-cart-plus"></i>

New Sale

</h2>


<a href="index.php" class="btn btn-secondary">

Back

</a>


</div>







<div class="card shadow">


<div class="card-body">



<form method="POST">





<div class="row">





<div class="col-md-6 mb-3">


<label class="fw-bold">

Customer

</label>


<select name="customer_id" class="form-control" required>


<option value="">

Select Customer

</option>


<?php foreach($customers as $c): ?>


<option value="<?= $c['id']; ?>">

<?= htmlspecialchars($c['customer_name']); ?>

(<?= $c['phone']; ?>)

</option>


<?php endforeach; ?>


</select>


</div>







<div class="col-md-6 mb-3">


<label class="fw-bold">

Product

</label>


<select name="product_id" id="product"

class="form-control" required>


<option value="">

Select Product

</option>



<?php foreach($products as $p): ?>


<option

value="<?= $p['id']; ?>"

data-price="<?= $p['selling_price']; ?>"

data-stock="<?= $p['quantity']; ?>"

>


<?= htmlspecialchars($p['product_name']); ?>

|

Stock <?= $p['quantity']; ?>

</option>


<?php endforeach; ?>


</select>



</div>








<div class="col-md-4 mb-3">


<label>

Price

</label>


<input

type="text"

id="price"

class="form-control"

readonly>


</div>





<div class="col-md-4 mb-3">


<label>

Quantity

</label>


<input

type="number"

name="quantity"

id="quantity"

min="1"

value="1"

class="form-control"

required>


</div>





<div class="col-md-4 mb-3">


<label>

Total

</label>


<input

type="text"

id="total"

class="form-control"

readonly>


</div>







<div class="col-md-4 mb-3">


<label>

Paid

</label>


<input

type="number"

name="paid"

value="0"

class="form-control">


</div>







<div class="col-md-4 mb-3">


<label>

Payment Method

</label>


<select name="payment_method"

class="form-control">


<option>Cash</option>

<option>Mobile Money</option>

<option>Bank</option>


</select>


</div>



</div>





<button name="save"

class="btn btn-primary">


<i class="bi bi-save"></i>

Save Sale


</button>




</form>


</div>


</div>


</div>






<script>


let product=document.getElementById("product");

let qty=document.getElementById("quantity");

let price=document.getElementById("price");

let total=document.getElementById("total");



function calculate(){


let p=product.options[product.selectedIndex];


let amount=p.getAttribute("data-price");


price.value=amount+" Frw";


total.value=(amount*qty.value)+" Frw";


}



product.addEventListener("change",calculate);

qty.addEventListener("input",calculate);



</script>




<?php include "../../includes/footer.php"; ?>