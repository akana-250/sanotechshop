<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

if(session_status()===PHP_SESSION_NONE){
    session_start();
}

require_once "../../config/config.php";
require_once "../../includes/user_check.php";

$page_title="Create Quotation";

$user_id=$_SESSION['user_id'];

$product_id=0;
$quantity=1;
$message="";
$error="";

/* ===========================
   LOAD PRODUCTS
=========================== */

$productStmt=$conn->prepare("
SELECT
id,
product_name,
product_code,
selling_price,
quantity,
status
FROM products
WHERE status='Active'
ORDER BY product_name ASC
");

$productStmt->execute();

$products=$productStmt->fetchAll(PDO::FETCH_ASSOC);

/* ===========================
   SAVE QUOTATION
=========================== */

if($_SERVER['REQUEST_METHOD']=="POST"){

$product_id=(int)$_POST['product_id'];
$quantity=(int)$_POST['quantity'];

if($product_id<=0){

$error="Please select a product.";

}elseif($quantity<=0){

$error="Quantity must be greater than zero.";

}else{

$getProduct=$conn->prepare("
SELECT
id,
selling_price,
quantity,
product_name
FROM products
WHERE id=?
LIMIT 1
");

$getProduct->execute([$product_id]);

$product=$getProduct->fetch(PDO::FETCH_ASSOC);

if(!$product){

$error="Selected product not found.";

}elseif($quantity>$product['quantity']){

$error="Requested quantity exceeds available stock.";

}else{

$total=$product['selling_price']*$quantity;

try{

$conn->beginTransaction();

$insert=$conn->prepare("
INSERT INTO quotations
(
user_id,
product_id,
quantity,
total_amount,
status
)
VALUES
(
?,
?,
?,
?,
'Pending'
)
");

$insert->execute([
$user_id,
$product_id,
$quantity,
$total
]);

/* Activity Log */

try{

$log=$conn->prepare("
INSERT INTO activity_logs
(user_id,activity)
VALUES(?,?)
");

$log->execute([
$user_id,
"Created quotation for ".$product['product_name']
]);

}catch(Exception $e){}

$conn->commit();

$message="Quotation request submitted successfully.";

$product_id=0;
$quantity=1;

}catch(Exception $e){

$conn->rollBack();

$error=$e->getMessage();

}

}

}

}

include "../../includes/header.php";
include "../../includes/user_sidebar.php";

?>

<div class="container-fluid py-4">

<div class="row justify-content-center">

<div class="col-lg-8">

<div class="card shadow border-0 rounded-4">

<div class="card-header bg-primary text-white">

<h4 class="mb-0">

<i class="bi bi-file-earmark-plus"></i>

Create New Quotation

</h4>

</div>

<div class="card-body">

<?php if($message!=""){ ?>

<div class="alert alert-success">

<?= htmlspecialchars($message) ?>

</div>

<?php } ?>

<?php if($error!=""){ ?>

<div class="alert alert-danger">

<?= htmlspecialchars($error) ?>

</div>

<?php } ?>

<form method="POST">

<div class="mb-3">

<label class="form-label fw-bold">

Select Product

</label>

<select
name="product_id"
id="product_id"
class="form-select"
required>

<option value="">

-- Select Product --

</option>

<?php foreach($products as $item){ ?>

<option
value="<?= $item['id'] ?>"
data-price="<?= $item['selling_price'] ?>"
<?= ($product_id==$item['id'])?'selected':''; ?>>

<?= htmlspecialchars($item['product_name']) ?>

(
<?= number_format($item['selling_price'],0) ?> Frw
)

</option>

<?php } ?>

</select>

</div>

<div class="row">

<div class="col-md-6">

<div class="mb-3">

<label class="form-label fw-bold">

Quantity

</label>

<input
type="number"
name="quantity"
id="quantity"
class="form-control"
min="1"
value="<?= $quantity ?>"
required>

</div>

</div>

<div class="col-md-6">

<div class="mb-3">

<label class="form-label fw-bold">

Estimated Total

</label>

<input
type="text"
id="total_amount"
class="form-control"
readonly
value="0 Frw">

</div>

</div>

</div><div class="mb-3">

<label class="form-label fw-bold">

Additional Notes (Optional)

</label>

<textarea
name="notes"
class="form-control"
rows="4"
placeholder="Write additional information if needed..."></textarea>

</div>

<hr>

<div class="d-flex justify-content-between">

<a
href="index.php"
class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

<button
type="submit"
class="btn btn-primary">

<i class="bi bi-check-circle"></i>

Submit Quotation

</button>

</div>

</form>

</div>

</div>

</div>

</div>

</div>

<?php include "../../includes/footer.php"; ?>

<script>

const product=document.getElementById("product_id");
const quantity=document.getElementById("quantity");
const total=document.getElementById("total_amount");

function calculateTotal(){

let selected=product.options[product.selectedIndex];

let price=parseFloat(selected.getAttribute("data-price"))||0;

let qty=parseInt(quantity.value)||0;

let amount=price*qty;

total.value=amount.toLocaleString()+" Frw";

}

product.addEventListener("change",calculateTotal);

quantity.addEventListener("keyup",calculateTotal);

quantity.addEventListener("change",calculateTotal);

calculateTotal();

</script>

<style>

.card{

border-radius:18px;

}

.card-header{

border-radius:18px 18px 0 0 !important;

}

.form-control,
.form-select{

height:50px;

}

textarea.form-control{

height:auto;

}

.btn{

min-width:150px;

}

</style>