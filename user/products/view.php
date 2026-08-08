<?php

error_reporting(E_ALL);
ini_set('display_errors',1);


if(session_status()===PHP_SESSION_NONE){
    session_start();
}


require_once "../../config/config.php";
require_once "../../includes/user_check.php";



if(!isset($_GET['id']) || !is_numeric($_GET['id'])){

    header("Location:index.php");
    exit();

}



$id=(int)$_GET['id'];




// GET PRODUCT


$stmt=$conn->prepare("

SELECT

products.*,

categories.category_name

FROM products

LEFT JOIN categories

ON products.category_id = categories.id

WHERE products.id=?

");


$stmt->execute([$id]);


$product=$stmt->fetch(PDO::FETCH_ASSOC);



if(!$product){

    die("Product not found");

}




include "../../includes/header.php";
include "../../includes/user_sidebar.php";


?>



<div class="container-fluid py-4">



<div class="d-flex justify-content-between align-items-center mb-4">


<h2 class="fw-bold">

<i class="bi bi-box-seam"></i>

Product Details

</h2>



<div>


<a href="edit.php?id=<?=$product['id']?>"

class="btn btn-warning">

<i class="bi bi-pencil"></i>

Edit

</a>



<a href="index.php"

class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>


</div>


</div>







<div class="card shadow border-0 rounded-4">


<div class="card-body">


<div class="row">





<div class="col-md-5 text-center">



<?php

$image="../../assets/img/no-image.png";


if(!empty($product['image']) &&
file_exists("../../uploads/products/".$product['image'])){


$image="../../uploads/products/".$product['image'];


}

?>


<img src="<?=$image?>"

class="img-fluid rounded-4 shadow"

style="height:350px;width:100%;object-fit:cover;">



</div>







<div class="col-md-7">



<h3 class="fw-bold">

<?=htmlspecialchars($product['product_name'])?>

</h3>



<span class="badge bg-secondary mb-3">

<?=htmlspecialchars($product['category_name'] ?? 'No Category')?>

</span>





<hr>




<div class="row mb-3">


<div class="col-md-6">

<strong>

Product Code

</strong>

<br>

<?=$product['product_code']?>


</div>



<div class="col-md-6">


<strong>

Barcode

</strong>

<br>


<?=

$product['barcode'] ?: '-'

?>


</div>


</div>







<div class="row mb-3">


<div class="col-md-6">


<strong>

Buying Price

</strong>


<br>


<?=number_format($product['buying_price'],0)?> Frw


</div>




<div class="col-md-6">


<strong>

Selling Price

</strong>


<br>


<span class="text-primary fw-bold">


<?=number_format($product['selling_price'],0)?> Frw


</span>


</div>


</div>







<div class="row mb-3">



<div class="col-md-6">


<strong>

Current Stock

</strong>


<br>



<?php if($product['quantity'] <= $product['minimum_stock']): ?>


<span class="badge bg-danger">

<?=$product['quantity']?> Low Stock

</span>



<?php else: ?>


<span class="badge bg-success">

<?=$product['quantity']?> Available

</span>


<?php endif; ?>



</div>





<div class="col-md-6">


<strong>

Status

</strong>


<br>


<?php if($product['status']=="Active"): ?>


<span class="badge bg-success">

Active

</span>


<?php else: ?>


<span class="badge bg-danger">

Inactive

</span>


<?php endif; ?>


</div>



</div>







<hr>



<h5>

Description

</h5>


<p class="text-muted">


<?=nl2br(htmlspecialchars($product['description'] ?? 'No description'))?>


</p>





</div>



</div>


</div>


</div>





</div>



<?php include "../../includes/footer.php"; ?>