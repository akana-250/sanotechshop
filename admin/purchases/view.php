<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once "../../config/config.php";
require_once "../../includes/admin_check.php";


if(!isset($_GET['id'])){

    header("Location: index.php");
    exit();

}


$id = $_GET['id'];


// Purchase Details

$stmt = $conn->prepare("
    SELECT 
        purchases.*,
        suppliers.company_name,
        users.fullname
    FROM purchases
    LEFT JOIN suppliers
    ON purchases.supplier_id = suppliers.id
    LEFT JOIN users
    ON purchases.user_id = users.id
    WHERE purchases.id = ?
");


$stmt->execute([$id]);

$purchase = $stmt->fetch();



if(!$purchase){

    header("Location: index.php");
    exit();

}



// Items

$itemStmt = $conn->prepare("
    SELECT 
        purchase_items.*,
        products.product_name
    FROM purchase_items
    LEFT JOIN products
    ON purchase_items.product_id = products.id
    WHERE purchase_items.purchase_id = ?
");


$itemStmt->execute([$id]);

$items = $itemStmt->fetchAll(PDO::FETCH_ASSOC);



?>


<!DOCTYPE html>
<html>

<head>

<title>
Purchase Details - SANO TECH SHOP
</title>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


</head>


<body>


<div class="container mt-4">


<h2>
Purchase Details
</h2>



<div class="card mb-4">


<div class="card-body">


<p>
<strong>Supplier:</strong>
<?= htmlspecialchars($purchase['company_name'] ?? 'N/A'); ?>
</p>


<p>
<strong>Created By:</strong>
<?= htmlspecialchars($purchase['fullname'] ?? 'N/A'); ?>
</p>


<p>
<strong>Date:</strong>
<?= $purchase['purchase_date']; ?>
</p>


<p>
<strong>Total:</strong>
<?= number_format($purchase['total']); ?>
</p>


</div>

</div>




<table class="table table-bordered table-striped">


<thead class="table-dark">


<tr>

<th>#</th>
<th>Product</th>
<th>Quantity</th>
<th>Cost Price</th>
<th>Subtotal</th>

</tr>


</thead>



<tbody>


<?php foreach($items as $item): ?>


<tr>


<td>
<?= $item['id']; ?>
</td>


<td>
<?= htmlspecialchars($item['product_name']); ?>
</td>


<td>
<?= $item['quantity']; ?>
</td>


<td>
<?= number_format($item['cost_price']); ?>
</td>


<td>
<?= number_format($item['subtotal']); ?>
</td>


</tr>


<?php endforeach; ?>


</tbody>


</table>



<a href="index.php"
class="btn btn-secondary">

Back

</a>


</div>


</body>

</html>