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






$id = isset($_GET['id'])

    ? (int)$_GET['id']

    : 0;





if($id <= 0){


    header("Location: quotation.php");

    exit;


}







try{



/*
|--------------------------------------------------------------------------
| GET QUOTATION
|--------------------------------------------------------------------------
*/


$stmt = $conn->prepare("

SELECT


quotations.*,


customers.customer_name,

customers.phone,

customers.email,

customers.address



FROM quotations



LEFT JOIN customers


ON customers.id = quotations.customer_id



WHERE quotations.id = ?



");



$stmt->execute([$id]);



$quotation = $stmt->fetch(PDO::FETCH_ASSOC);






if(!$quotation){


    throw new Exception("Quotation not found");


}







/*
|--------------------------------------------------------------------------
| GET ITEMS
|--------------------------------------------------------------------------
*/


$item_stmt = $conn->prepare("

SELECT


quotation_items.*,


products.product_name,

products.product_code



FROM quotation_items



LEFT JOIN products


ON products.id = quotation_items.product_id



WHERE quotation_items.quotation_id = ?



");



$item_stmt->execute([$id]);



$items = $item_stmt->fetchAll(PDO::FETCH_ASSOC);






}catch(Exception $e){



$_SESSION['error'] = $e->getMessage();



header("Location: quotation.php");

exit;


}


?><?php

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






$id = isset($_GET['id'])

    ? (int)$_GET['id']

    : 0;





if($id <= 0){


    header("Location: quotation.php");

    exit;


}







try{



/*
|--------------------------------------------------------------------------
| GET QUOTATION
|--------------------------------------------------------------------------
*/


$stmt = $conn->prepare("

SELECT


quotations.*,


customers.customer_name,

customers.phone,

customers.email,

customers.address



FROM quotations



LEFT JOIN customers


ON customers.id = quotations.customer_id



WHERE quotations.id = ?



");



$stmt->execute([$id]);



$quotation = $stmt->fetch(PDO::FETCH_ASSOC);






if(!$quotation){


    throw new Exception("Quotation not found");


}







/*
|--------------------------------------------------------------------------
| GET ITEMS
|--------------------------------------------------------------------------
*/


$item_stmt = $conn->prepare("

SELECT


quotation_items.*,


products.product_name,

products.product_code



FROM quotation_items



LEFT JOIN products


ON products.id = quotation_items.product_id



WHERE quotation_items.quotation_id = ?



");



$item_stmt->execute([$id]);



$items = $item_stmt->fetchAll(PDO::FETCH_ASSOC);






}catch(Exception $e){



$_SESSION['error'] = $e->getMessage();



header("Location: quotation.php");

exit;


}


?>

<table>


<thead>


<tr>


<th>#</th>

<th>Product</th>

<th>Code</th>

<th>Qty</th>

<th>Price</th>

<th>Subtotal</th>


</tr>


</thead>





<tbody>



<?php foreach($items as $key=>$item): ?>


<tr>



<td>

<?= $key+1; ?>

</td>





<td>

<?= htmlspecialchars($item['product_name']); ?>

</td>





<td>

<?= htmlspecialchars($item['product_code']); ?>

</td>





<td>

<?= number_format((int)$item['quantity']); ?>

</td>





<td>

<?= number_format((float)$item['price'],2); ?>

</td>





<td>

<?= number_format((float)$item['subtotal'],2); ?>

</td>




</tr>



<?php endforeach; ?>



</tbody>



</table>







<div class="line"></div>







<div class="total">


Total:


<?= number_format((float)$quotation['total'],2); ?>


</div>







<div class="line"></div>






<div style="text-align:center;">


<p>


Thank you for choosing SANO TECH SHOP


</p>



<p>


This quotation is valid according to company terms.


</p>


</div>






</div>





</body>


</html>