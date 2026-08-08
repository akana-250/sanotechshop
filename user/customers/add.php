<?php

error_reporting(E_ALL);
ini_set('display_errors',1);


if(session_status()===PHP_SESSION_NONE){
    session_start();
}


require_once "../../config/config.php";
require_once "../../includes/user_check.php";



if(isset($_POST['save'])){


    $customer_name = trim($_POST['customer_name']);

    $phone = trim($_POST['phone']);

    $email = trim($_POST['email']);

    $address = trim($_POST['address']);




    $stmt = $conn->prepare("

        INSERT INTO customers

        (
        customer_name,
        phone,
        email,
        address
        )

        VALUES(?,?,?,?)

    ");



    $stmt->execute([

        $customer_name,

        $phone,

        $email,

        $address

    ]);





    // Activity Log

    try{


        $log = $conn->prepare("

        INSERT INTO activity_logs

        (user_id,activity)

        VALUES(?,?)

        ");


        $log->execute([

            $_SESSION['user_id'],

            "Added Customer: ".$customer_name

        ]);


    }catch(Exception $e){}




    header("Location:index.php");

    exit();


}




$page_title="Add Customer";



include "../../includes/header.php";

include "../../includes/user_sidebar.php";


?>




<div class="container-fluid py-4">



<div class="d-flex justify-content-between align-items-center mb-4">



<div>

<h2 class="fw-bold">

<i class="bi bi-person-plus"></i>

Add Customer

</h2>


<p class="text-muted">

Create new customer

</p>


</div>




<a href="index.php"

class="btn btn-dark">


<i class="bi bi-arrow-left"></i>

Back Customers


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

required>


</div>







<div class="col-md-6 mb-3">


<label class="fw-bold">

Phone

</label>


<input

type="text"

name="phone"

class="form-control">


</div>







<div class="col-md-6 mb-3">


<label class="fw-bold">

Email

</label>


<input

type="email"

name="email"

class="form-control">


</div>







<div class="col-md-12 mb-3">


<label class="fw-bold">

Address

</label>


<textarea

name="address"

class="form-control"

rows="4"></textarea>


</div>






</div>





<button

type="submit"

name="save"

class="btn btn-primary">


<i class="bi bi-save"></i>

Save Customer


</button>




<a href="index.php"

class="btn btn-secondary">

Cancel

</a>





</form>



</div>


</div>





</div>




<?php include "../../includes/footer.php"; ?>