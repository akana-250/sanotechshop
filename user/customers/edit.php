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




// ============================
// GET CUSTOMER
// ============================


$stmt=$conn->prepare("

SELECT *

FROM customers

WHERE id=?

");


$stmt->execute([$id]);


$customer=$stmt->fetch(PDO::FETCH_ASSOC);



if(!$customer){

    die("Customer not found");

}






// ============================
// UPDATE CUSTOMER
// ============================


if(isset($_POST['update'])){


$name=trim($_POST['customer_name']);

$phone=trim($_POST['phone']);

$email=trim($_POST['email']);

$address=trim($_POST['address']);




$update=$conn->prepare("

UPDATE customers

SET

customer_name=?,

phone=?,

email=?,

address=?

WHERE id=?

");



$update->execute([

$name,

$phone,

$email,

$address,

$id

]);





// Activity Log

try{


$log=$conn->prepare("

INSERT INTO activity_logs

(user_id,activity)

VALUES(?,?)

");


$log->execute([

$_SESSION['user_id'],

"Updated Customer: ".$name

]);


}catch(Exception $e){}





header("Location:view.php?id=".$id);

exit();



}




$page_title="Edit Customer";



include "../../includes/header.php";

include "../../includes/user_sidebar.php";

?>






<div class="container-fluid py-4">



<div class="d-flex justify-content-between align-items-center mb-4">



<div>

<h2 class="fw-bold">

<i class="bi bi-pencil-square"></i>

Edit Customer

</h2>


<p class="text-muted">

Update customer information

</p>


</div>



<a href="view.php?id=<?= $id ?>" 

class="btn btn-dark">


<i class="bi bi-arrow-left"></i>

Back


</a>



</div>







<div class="card shadow-sm">


<div class="card-body">





<form method="POST">





<div class="row">





<div class="col-md-6 mb-3">


<label class="fw-bold">

Customer Name

</label>


<input

type="text"

name="customer_name"

class="form-control"

value="<?= htmlspecialchars($customer['customer_name']); ?>"

required>


</div>






<div class="col-md-6 mb-3">


<label class="fw-bold">

Phone

</label>


<input

type="text"

name="phone"

class="form-control"

value="<?= htmlspecialchars($customer['phone']); ?>">


</div>







<div class="col-md-6 mb-3">


<label class="fw-bold">

Email

</label>


<input

type="email"

name="email"

class="form-control"

value="<?= htmlspecialchars($customer['email']); ?>">


</div>








<div class="col-md-12 mb-3">


<label class="fw-bold">

Address

</label>



<textarea

name="address"

class="form-control"

rows="4"><?= htmlspecialchars($customer['address']); ?></textarea>


</div>







</div>





<button 

type="submit"

name="update"

class="btn btn-primary">


<i class="bi bi-save"></i>

Update Customer


</button>





<a href="view.php?id=<?= $id ?>"

class="btn btn-secondary">

Cancel

</a>






</form>





</div>


</div>






</div>








<?php include "../../includes/footer.php"; ?>