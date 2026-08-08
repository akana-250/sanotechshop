<?php

include_once __DIR__ . "/../includes/user_check.php";
include_once __DIR__ . "/../includes/db.php";


$user_id = $_SESSION['user_id'];


// Get sales made by user

$stmt = $conn->prepare("
    SELECT * FROM sales 
    WHERE user_id = ?
    ORDER BY id DESC
");

$stmt->execute([$user_id]);

$sales = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>


<!DOCTYPE html>
<html>
<head>

<title>My Sales - SANO TECH SHOP</title>


<style>

body{
    margin:0;
    font-family:Arial;
    background:#f4f6f9;
}


.sidebar{

width:230px;
height:100vh;
background:#1e293b;
color:white;
position:fixed;
padding-top:20px;

}


.sidebar h2{

text-align:center;

}


.sidebar a{

display:block;
padding:15px;
color:white;
text-decoration:none;

}


.sidebar a:hover{

background:#334155;

}



.main{

margin-left:230px;
padding:30px;

}


table{

width:100%;
background:white;
border-collapse:collapse;

}


th,td{

padding:12px;
border-bottom:1px solid #ddd;
text-align:left;

}


th{

background:#2563eb;
color:white;

}


</style>


</head>


<body>


<div class="sidebar">

<h2>SANO TECH</h2>

<a href="dashboard.php">Dashboard</a>

<a href="sales.php">My Sales</a>

<a href="customers.php">Customers</a>

<a href="quotations.php">Quotations</a>

<a href="profile.php">Profile</a>

<a href="../auth/logout.php">Logout</a>


</div>



<div class="main">


<h1>My Sales</h1>


<table>

<tr>

<th>ID</th>
<th>Customer</th>
<th>Amount</th>
<th>Date</th>


</tr>


<?php foreach($sales as $sale): ?>


<tr>

<td>
<?php echo $sale['id']; ?>
</td>


<td>
<?php echo $sale['customer_id']; ?>
</td>


<td>
<?php echo $sale['total_amount']; ?>
</td>


<td>
<?php echo $sale['created_at']; ?>
</td>


</tr>


<?php endforeach; ?>


</table>


</div>


</body>
</html>