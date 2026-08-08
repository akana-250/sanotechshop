<?php

ob_start();


error_reporting(E_ALL);
ini_set('display_errors',1);



if(session_status() === PHP_SESSION_NONE){

    session_start();

}




require_once __DIR__ . "/../../config/config.php";

require_once __DIR__ . "/../../includes/admin_check.php";




$page_title = "Edit Expense";





/* ===========================
   CHECK ID
=========================== */


if(!isset($_GET['id'])){


header("Location:index.php");

exit;


}



$id = (int)$_GET['id'];








/* ===========================
   GET EXPENSE
=========================== */


$stmt = $conn->prepare("

SELECT *

FROM expenses

WHERE id=?

");


$stmt->execute([$id]);



$expense = $stmt->fetch(PDO::FETCH_ASSOC);





if(!$expense){


$_SESSION['error']="Expense not found";


header("Location:index.php");

exit;


}








/* ===========================
   UPDATE EXPENSE
=========================== */


if(isset($_POST['update_expense'])){


$expense_name = trim($_POST['expense_name']);

$amount = trim($_POST['amount']);

$expense_date = trim($_POST['expense_date']);

$description = trim($_POST['description']);






try{


$update = $conn->prepare("

UPDATE expenses SET

expense_name=?,

amount=?,

expense_date=?,

description=?

WHERE id=?

");





$update->execute([


$expense_name,

$amount,

$expense_date,

$description,

$id


]);






$_SESSION['success']="Expense updated successfully";


header("Location:index.php");

exit;



}catch(PDOException $e){



$_SESSION['error']=$e->getMessage();



}



}





require_once __DIR__ . "/../../includes/admin_header.php";

require_once __DIR__ . "/../../includes/admin_sidebar.php";

?>





<main class="admin-content">


<div class="container-fluid">
<div class="d-flex justify-content-between align-items-center mb-4">


<div>


<h2>

<i class="fa-solid fa-pen"></i>

Edit Expense

</h2>



<p class="text-muted">

Update company expense information

</p>


</div>







<a href="index.php"

class="btn btn-secondary">


<i class="fa-solid fa-arrow-left"></i>


Back


</a>



</div>









<?php if(isset($_SESSION['success'])): ?>


<div class="alert alert-success">


<?= $_SESSION['success']; ?>


</div>


<?php unset($_SESSION['success']); ?>


<?php endif; ?>








<?php if(isset($_SESSION['error'])): ?>


<div class="alert alert-danger">


<?= $_SESSION['error']; ?>


</div>


<?php unset($_SESSION['error']); ?>


<?php endif; ?>









<div class="content-card">






<form method="POST">







<div class="row">








<div class="col-md-6 mb-3">


<label class="form-label">

Expense Name

</label>



<input

type="text"

name="expense_name"

class="form-control"

value="<?= htmlspecialchars($expense['expense_name']); ?>"

required>


</div>









<div class="col-md-6 mb-3">


<label class="form-label">

Amount

</label>




<input

type="number"

step="0.01"

name="amount"

class="form-control"

value="<?= htmlspecialchars($expense['amount']); ?>"

required>


</div>









<div class="col-md-6 mb-3">


<label class="form-label">

Expense Date

</label>




<input

type="date"

name="expense_date"

class="form-control"

value="<?= htmlspecialchars($expense['expense_date']); ?>"

required>


</div>









<div class="col-md-12 mb-3">


<label class="form-label">

Description

</label>




<textarea

name="description"

class="form-control"

rows="4"><?= htmlspecialchars($expense['description']); ?></textarea>



</div>




<div class="mt-3">


<button

type="submit"

name="update_expense"

class="btn btn-primary">


<i class="fa-solid fa-save"></i>


Update Expense


</button>





<a href="index.php"

class="btn btn-secondary">


Cancel


</a>



</div>






</form>



</div>



</div>



</main>







<?php


require_once __DIR__ . "/../../includes/admin_footer.php";


ob_end_flush();


?>