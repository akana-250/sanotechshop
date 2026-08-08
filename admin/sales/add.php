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



$page_title = "New Sale";





try{


/*
|--------------------------------------------------------------------------
| CUSTOMERS
|--------------------------------------------------------------------------
*/


$customer_stmt = $conn->query("

SELECT

id,

customer_name,

phone


FROM customers


ORDER BY customer_name ASC


");


$customers = $customer_stmt->fetchAll(PDO::FETCH_ASSOC);







/*
|--------------------------------------------------------------------------
| PRODUCTS
|--------------------------------------------------------------------------
*/


$product_stmt = $conn->query("

SELECT


id,

product_code,

product_name,

selling_price,

quantity



FROM products



WHERE status = 'Active'


AND quantity > 0



ORDER BY product_name ASC



");


$products = $product_stmt->fetchAll(PDO::FETCH_ASSOC);






}catch(Exception $e){


$_SESSION['error'] = $e->getMessage();


$customers = [];

$products = [];


}





require_once __DIR__ . "/../../includes/admin_header.php";

require_once __DIR__ . "/../../includes/admin_sidebar.php";


?>


<main>


<div class="container-fluid">

<!-- ===========================
     PAGE HEADER
=========================== -->


<div class="d-flex justify-content-between align-items-center mb-4">


<div>


<h2 class="fw-bold">


<i class="fa-solid fa-cart-plus text-primary"></i>


Create New Sale


</h2>



<p class="text-muted">


Select customer and products


</p>


</div>







<a href="index.php"

class="btn btn-secondary">


<i class="fa-solid fa-arrow-left"></i>


Back


</a>



</div>








<!-- ===========================
     SALE FORM
=========================== -->


<form action="create.php" method="POST">





<div class="card shadow-sm mb-4">


<div class="card-body">



<div class="row g-3">





<div class="col-md-6">


<label class="form-label fw-bold">

Customer

</label>




<select name="customer_id"

class="form-select">


<option value="">Walk In Customer</option>



<?php foreach($customers as $customer): ?>


<option value="<?= $customer['id']; ?>">


<?= htmlspecialchars($customer['customer_name']); ?>


-
<?= htmlspecialchars($customer['phone'] ?? ''); ?>


</option>



<?php endforeach; ?>



</select>


</div>








<div class="col-md-6">


<label class="form-label fw-bold">

Payment Method

</label>




<select name="payment_method"

class="form-select">



<option value="Cash">

Cash

</option>



<option value="Mobile Money">

Mobile Money

</option>



<option value="Bank">

Bank

</option>



</select>



</div>





</div>


</div>


</div>








<!-- ===========================
     ADD PRODUCTS
=========================== -->


<div class="card shadow-sm">


<div class="card-header">


<h5 class="mb-0">


<i class="fa-solid fa-box"></i>


Products


</h5>


</div>






<div class="card-body">



<div class="row g-3">





<div class="col-md-6">


<label class="form-label">

Product

</label>



<select id="productSelect"

class="form-select">



<option value="">Choose Product</option>




<?php foreach($products as $product): ?>


<option

value="<?= $product['id']; ?>"

data-name="<?= htmlspecialchars($product['product_name']); ?>"

data-price="<?= $product['selling_price']; ?>"

data-stock="<?= $product['quantity']; ?>"



>


<?= htmlspecialchars($product['product_name']); ?>


-

<?= number_format($product['selling_price'],2); ?>


(Stock: <?= $product['quantity']; ?>)



</option>



<?php endforeach; ?>



</select>



</div>

<div class="col-md-3">


<label class="form-label">

Quantity

</label>



<input type="number"

id="quantity"

class="form-control"

value="1"

min="1">


</div>







<div class="col-md-3 d-flex align-items-end">


<button type="button"

id="addProduct"

class="btn btn-primary w-100">


<i class="fa-solid fa-plus"></i>


Add


</button>



</div>







</div>







<hr>







<div class="table-responsive">


<table class="table table-bordered"

id="cartTable">



<thead class="table-light">


<tr>


<th>Product</th>

<th>Price</th>

<th>Qty</th>

<th>Subtotal</th>

<th>Action</th>


</tr>


</thead>



<tbody>



</tbody>



</table>


</div>







<div class="text-end">


<h4>


Total:

<span id="totalAmount">

0.00

</span>


</h4>



<input type="hidden"

name="total"

id="totalInput"

value="0">



</div>








<div class="text-end mt-3">


<button type="submit"

class="btn btn-success">


<i class="fa-solid fa-save"></i>


Complete Sale


</button>


</div>





</div>


</div>




</form>









<script>


let cart = [];





document.getElementById("addProduct").addEventListener("click",function(){



let select = document.getElementById("productSelect");



let productId = select.value;



if(productId === ""){


alert("Select product first");

return;


}





let option = select.options[select.selectedIndex];



let name = option.dataset.name;


let price = parseFloat(option.dataset.price);


let stock = parseInt(option.dataset.stock);



let qty = parseInt(document.getElementById("quantity").value);






if(qty > stock){


alert("Quantity exceeds stock");


return;


}






let existing = cart.find(item => item.id == productId);




if(existing){



existing.qty += qty;



}else{



cart.push({


id:productId,


name:name,


price:price,


qty:qty



});



}



renderCart();



});







function renderCart(){



let tbody = document.querySelector("#cartTable tbody");



tbody.innerHTML = "";



let total = 0;





cart.forEach((item,index)=>{


let subtotal = item.price * item.qty;


total += subtotal;




tbody.innerHTML += `


<tr>


<td>${item.name}</td>


<td>${item.price.toFixed(2)}</td>


<td>${item.qty}</td>


<td>${subtotal.toFixed(2)}</td>


<td>


<button type="button"

class="btn btn-danger btn-sm"

onclick="removeItem(${index})">


Delete


</button>


</td>


</tr>



`;





});






document.getElementById("totalAmount").innerHTML = total.toFixed(2);



document.getElementById("totalInput").value = total.toFixed(2);



}






function removeItem(index){


cart.splice(index,1);


renderCart();


}







</script>








</div>

</main>







<?php


require_once __DIR__ . "/../../includes/admin_footer.php";


ob_end_flush();


?>