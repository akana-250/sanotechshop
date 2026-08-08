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


// Get Customer

$stmt = $conn->prepare(
    "SELECT * FROM customers WHERE id = ?"
);

$stmt->execute([$id]);

$customer = $stmt->fetch();



if(!$customer){

    header("Location: index.php");
    exit();

}


?>


<!DOCTYPE html>
<html>

<head>

<title>
Customer Details - SANO TECH SHOP
</title>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


</head>


<body>


<div class="container mt-4">


<h2>
Customer Details
</h2>



<div class="card shadow">


<div class="card-body">



<table class="table table-bordered">


<tr>

<th width="30%">
Customer Name
</th>

<td>
<?= htmlspecialchars($customer['customer_name']); ?>
</td>

</tr>



<tr>

<th>
Phone
</th>

<td>
<?= htmlspecialchars($customer['phone']); ?>
</td>

</tr>



<tr>

<th>
Email
</th>

<td>
<?= htmlspecialchars($customer['email']); ?>
</td>

</tr>



<tr>

<th>
Address
</th>

<td>
<?= htmlspecialchars($customer['address']); ?>
</td>

</tr>



<tr>

<th>
Created Date
</th>

<td>
<?= $customer['created_at']; ?>
</td>

</tr>



</table>



<a href="index.php"
class="btn btn-secondary">

Back

</a>


<a href="edit.php?id=<?= $customer['id']; ?>"
class="btn btn-warning">

Edit Customer

</a>



</div>

</div>


</div>


</body>

</html>